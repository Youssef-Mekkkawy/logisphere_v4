<?php

// ======================================================================================
// PHASE 4: CONTROLLERS & BUSINESS LOGIC - LOGISTICS MANAGEMENT SYSTEM
// ======================================================================================

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Port;
use App\Models\ShippingAgency;
use App\Models\ShipmentType;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Enhanced Shipment Controller with Professional Business Logic
 */
class ShipmentController extends Controller
{
    protected $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
        $this->middleware('auth');
    }

    /**
     * Display shipments dashboard with advanced filtering
     */
    public function index(Request $request)
    {
        $query = Shipment::with(['company', 'originPort', 'destinationPort', 'employee']);

        // Advanced filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shipment_id', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(20);
        $ports = Port::active()->get();
        // Get filter options
        $companies = Company::where('type', 'Client')->orderBy('name')->get();
        $statuses = ['Pending', 'In Transit', 'At Port', 'Customs Clearance', 'Delivered', 'Cancelled'];

        return view('shipments.index', compact('shipments', 'companies', 'statuses', 'ports'));
    }

    /**
     * Show create shipment form
     */
    public function create(Request $request)
    {
        $companies = Company::where('type', 'Client')->orderBy('name')->get();
        $ports = Port::where('status', 'Active')->orderBy('name')->get();
        $agencies = ShippingAgency::where('status', 'Active')->orderBy('name')->get();
        $shipmentTypes = ShipmentType::where('status', 'Active')->orderBy('name')->get();
        $employees = Employee::where('status', 'Active')->orderBy('name')->get();

        return view('shipments.create', compact('companies', 'ports', 'agencies', 'shipmentTypes', 'employees'));
    }

    /**
     * Store new shipment with business logic validation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id|different:origin_port_id',
            'shipping_agency_id' => 'nullable|exists:shipping_agencies,id',
            'shipment_type_id' => 'nullable|exists:shipment_types,id',
            'container_type' => 'nullable|string',
            'container_number' => 'nullable|string',
            'reference_number' => 'nullable|string|unique:shipments,reference_number',
            'cargo_description' => 'required|string',
            'weight' => 'nullable|numeric|min:0',
            'volume' => 'nullable|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'etd' => 'nullable|date|after_or_equal:today',
            'eta' => 'nullable|date|after:etd',
            'consignee_name' => 'required|string',
            'consignee_address' => 'required|string',
            'notify_party' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'employee_id' => 'required|exists:employees,id'
        ]);

        try {
            DB::beginTransaction();

            $shipment = $this->shipmentService->createShipment($validated);

            // Log activity
            $this->logActivity('shipment_created', $shipment->id, "Shipment {$shipment->shipment_id} created");

            DB::commit();

            return redirect()->route('shipments.show', $shipment)
                ->with('success', 'Shipment created successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'Failed to create shipment: ' . $e->getMessage());
        }
    }

    /**
     * Show detailed shipment view
     */
    public function show(Shipment $shipment)
    {
        $shipment->load([
            'company',
            'originPort',
            'destinationPort',
            'shippingAgency',
            'shipmentType',
            'employee',
            'trackingEvents' => function ($query) {
                $query->orderBy('event_date', 'desc');
            }
        ]);

        return view('shipments.show', compact('shipment'));
    }

    /**
     * Update shipment status with tracking
     */
    public function updateStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Transit,At Port,Customs Clearance,Delivered,Cancelled',
            'location' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $this->shipmentService->updateStatus($shipment, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track shipment with public tracking page
     */
    public function track($shipmentId)
    {
        $shipment = Shipment::where('shipment_id', $shipmentId)
            ->with(['company', 'originPort', 'destinationPort', 'trackingEvents'])
            ->firstOrFail();

        return view('shipments.track', compact('shipment'));
    }

    /**
     * Generate shipment documents (BOL, Invoice, etc.)
     */
    public function generateDocument(Request $request, Shipment $shipment)
    {
        $documentType = $request->get('type', 'bill_of_lading');

        try {
            $pdf = $this->shipmentService->generateDocument($shipment, $documentType);

            return $pdf->download("{$documentType}_{$shipment->shipment_id}.pdf");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate document: ' . $e->getMessage());
        }
    }

    /**
     * Log activity for audit trail
     */
    private function logActivity($action, $shipmentId, $description)
    {
        // This would integrate with your activity logging system
        Log::info("Shipment Activity", [
            'user_id' => Auth::id(),
            'action' => $action,
            'shipment_id' => $shipmentId,
            'description' => $description,
            'timestamp' => now()
        ]);
    }
    public function search(Request $request)
    {
        $query = Shipment::with(['company', 'originPort', 'destinationPort']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('shipment_id', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'shipments' => $query->limit(10)->get()
        ]);
    }
    public function getTracking(Shipment $shipment)
    {
        $tracking = $shipment->trackingEvents()
            ->orderBy('event_date', 'desc')
            ->get();

        return response()->json([
            'tracking_events' => $tracking
        ]);
    }
}
