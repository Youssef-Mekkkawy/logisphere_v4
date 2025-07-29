<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Shipper;
use App\Models\Country;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // 🔥 TEMPORARILY DISABLED FOR TESTING - Re-enable after fixing
        $this->middleware('permission:shippers.view')->only(['index', 'show']);
        $this->middleware('permission:shippers.create')->only(['create', 'store']);
        $this->middleware('permission:shippers.edit')->only(['edit', 'update']);
        $this->middleware('permission:shippers.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Shipper::with(['country']); // Removed ->withCount('shipments') temporarily

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by shipper type
        if ($request->filled('shipper_type')) {
            $query->where('shipper_type', $request->shipper_type);
        }

        // Filter by country
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by credit rating
        if ($request->filled('credit_rating')) {
            $query->where('credit_rating', $request->credit_rating);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'shipper_name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $shippers = $query->paginate(20)->withQueryString();
        $countries = Country::where('status', 'Active')->orderBy('name')->get();

        return view('logistics.shippers.index', compact('shippers', 'countries'));
    }

    public function create()
    {
        $countries = Country::where('status', 'Active')->orderBy('name')->get();

        return view('logistics.shippers.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipper_code' => 'required|string|max:20|unique:shippers,shipper_code',
            'shipper_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'shipper_type' => 'required|string|in:Manufacturer,Exporter,Trading Company,Freight Forwarder,Agent,Importer,Distributor,Retailer',
            'business_license' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state_province' => 'nullable|string|max:100',
            'country_id' => 'required|exists:countries,id',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'alternative_phone' => 'nullable|string|max:50',
            'emergency_contact' => 'nullable|string|max:255',
            'industry_type' => 'nullable|string|max:100',
            'specialization' => 'nullable|string',
            'cargo_types' => 'nullable|array',
            'trade_routes' => 'nullable|array',
            'certifications' => 'nullable|array',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'annual_volume' => 'nullable|integer|min:0',
            'services_offered' => 'nullable|array',
            'equipment_owned' => 'nullable|array',
            'preferred_ports' => 'nullable|array',
            'operating_regions' => 'nullable|array',
            'operating_hours' => 'nullable|string|max:100',
            'time_zone' => 'nullable|string|max:50',
            'credit_rating' => 'nullable|in:A+,A,A-,B+,B,B-,C+,C,C-',
            'payment_terms' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'currency_preference' => 'nullable|string|max:3',
            'insurance_coverage' => 'boolean',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:100',
            'required_documents' => 'nullable|array',
            'customs_broker' => 'boolean',
            'freight_forwarder' => 'boolean',
            'dangerous_goods_certified' => 'boolean',
            'customs_code' => 'nullable|string|max:50',
            'regulatory_compliance' => 'nullable|array',
            'preferred_carriers' => 'nullable|array',
            'preferred_incoterms' => 'nullable|array',
            'track_and_trace_required' => 'boolean',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'notification_language' => 'nullable|string|max:5',
            'status' => 'required|in:Active,Inactive,Suspended,Pending',
            'notes' => 'nullable|string',
            'sales_representative' => 'nullable|string|max:255',
            'account_manager' => 'nullable|string|max:255',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date'
        ]);

        // Set country name from country_id
        if ($validated['country_id']) {
            $country = Country::find($validated['country_id']);
            $validated['country'] = $country->name;
        }

        $shipper = Shipper::create($validated);

        return redirect()->route('logistics.shippers.show', $shipper)
            ->with('success', 'Shipper created successfully!');
    }

    public function show(Shipper $shipper)
    {
        $shipper->load(['country']);

        // Get statistics (with fallback for missing relationships)
        $statistics = [
            'total_shipments' => 0, // Will be updated when shipper_id column exists
            'active_shipments' => 0, // Will be updated when shipper_id column exists  
            'monthly_volume' => 0, // Will be updated when shipper_id column exists
            'performance_score' => $shipper->getPerformanceScoreAttribute()
        ];

        // Get recent shipments (empty for now until relationship exists)
        $recentShipments = collect(); // Empty collection

        return view('logistics.shippers.show', compact('shipper', 'statistics', 'recentShipments'));
    }

    public function edit(Shipper $shipper)
    {
        $countries = Country::where('status', 'Active')->orderBy('name')->get();

        return view('logistics.shippers.edit', compact('shipper', 'countries'));
    }

    public function update(Request $request, Shipper $shipper)
    {
        $validated = $request->validate([
            'shipper_code' => 'required|string|max:20|unique:shippers,shipper_code,' . $shipper->id,
            'shipper_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'shipper_type' => 'required|string|in:Manufacturer,Exporter,Trading Company,Freight Forwarder,Agent,Importer,Distributor,Retailer',
            'business_license' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state_province' => 'nullable|string|max:100',
            'country_id' => 'required|exists:countries,id',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'alternative_phone' => 'nullable|string|max:50',
            'emergency_contact' => 'nullable|string|max:255',
            'industry_type' => 'nullable|string|max:100',
            'specialization' => 'nullable|string',
            'cargo_types' => 'nullable|array',
            'trade_routes' => 'nullable|array',
            'certifications' => 'nullable|array',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'annual_volume' => 'nullable|integer|min:0',
            'services_offered' => 'nullable|array',
            'equipment_owned' => 'nullable|array',
            'preferred_ports' => 'nullable|array',
            'operating_regions' => 'nullable|array',
            'operating_hours' => 'nullable|string|max:100',
            'time_zone' => 'nullable|string|max:50',
            'credit_rating' => 'nullable|in:A+,A,A-,B+,B,B-,C+,C,C-',
            'payment_terms' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'currency_preference' => 'nullable|string|max:3',
            'insurance_coverage' => 'boolean',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:100',
            'required_documents' => 'nullable|array',
            'customs_broker' => 'boolean',
            'freight_forwarder' => 'boolean',
            'dangerous_goods_certified' => 'boolean',
            'customs_code' => 'nullable|string|max:50',
            'regulatory_compliance' => 'nullable|array',
            'preferred_carriers' => 'nullable|array',
            'preferred_incoterms' => 'nullable|array',
            'track_and_trace_required' => 'boolean',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'notification_language' => 'nullable|string|max:5',
            'status' => 'required|in:Active,Inactive,Suspended,Pending',
            'notes' => 'nullable|string',
            'sales_representative' => 'nullable|string|max:255',
            'account_manager' => 'nullable|string|max:255',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date'
        ]);

        // Set country name from country_id
        if ($validated['country_id']) {
            $country = Country::find($validated['country_id']);
            $validated['country'] = $country->name;
        }

        $shipper->update($validated);

        return redirect()->route('logistics.shippers.show', $shipper)
            ->with('success', 'Shipper updated successfully!');
    }

    public function destroy(Shipper $shipper)
    {
        // Check if shipper has any shipments
        if ($shipper->shipments()->count() > 0) {
            return back()->with('error', 'Cannot delete shipper with existing shipments. Please archive instead.');
        }

        $shipper->delete();

        return redirect()->route('logistics.shippers.index')
            ->with('success', 'Shipper deleted successfully!');
    }

    // API endpoints for dropdowns and selections
    public function getActive()
    {
        $shippers = Shipper::active()
            ->select('id', 'shipper_code', 'shipper_name', 'shipper_type')
            ->orderBy('shipper_name')
            ->get();

        return response()->json($shippers);
    }

    public function toggleStatus(Shipper $shipper)
    {
        $newStatus = $shipper->status === 'Active' ? 'Inactive' : 'Active';
        $shipper->update(['status' => $newStatus]);

        return back()->with('success', 'Shipper status updated successfully!');
    }

    public function statistics(Shipper $shipper)
    {
        $stats = [
            'total_shipments' => $shipper->shipments()->count(),
            'active_shipments' => $shipper->getActiveShipmentsCount(),
            'monthly_volume' => $shipper->getMonthlyVolume(),
            'performance_score' => $shipper->getPerformanceScoreAttribute(),
            'utilization_rate' => $shipper->getUtilizationRate(),
            'last_shipment' => $shipper->shipments()->latest()->first()?->created_at,
            'avg_shipment_value' => $shipper->shipments()->avg('total_value'),
            'preferred_routes' => $shipper->trade_routes
        ];

        return response()->json($stats);
    }
}
