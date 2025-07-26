<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;

use App\Models\InspectionType;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InspectionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:inspection-types.view')->only(['index', 'show']);
        $this->middleware('permission:inspection-types.create')->only(['create', 'store']);
        $this->middleware('permission:inspection-types.edit')->only(['edit', 'update']);
        $this->middleware('permission:inspection-types.delete')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = InspectionType::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('inspection_name', 'like', "%{$search}%")
                    ->orWhere('inspection_code', 'like', "%{$search}%")
                    ->orWhere('inspection_category', 'like', "%{$search}%")
                    ->orWhere('regulatory_authority', 'like', "%{$search}%");
            });
        }

        if ($request->filled('inspection_category')) {
            $query->where('inspection_category', $request->inspection_category);
        }

        if ($request->filled('mandatory')) {
            $query->where('mandatory', $request->mandatory);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('regulatory_authority')) {
            $query->where('regulatory_authority', 'like', "%{$request->regulatory_authority}%");
        }

        // Get inspection types with pagination
        $inspectionTypes = $query->orderBy('inspection_name')
            ->paginate(15)
            ->withQueryString();

        return view('logistics.inspection-types.index', compact('inspectionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('logistics.inspection-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), InspectionType::validationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle required_documents array
            if ($request->has('required_documents')) {
                $data['required_documents'] = array_filter($request->get('required_documents', []));
            }

            // Handle applies_to array
            if ($request->has('applies_to')) {
                $data['applies_to'] = array_filter($request->get('applies_to', []));
            }

            InspectionType::create($data);

            DB::commit();

            return redirect()->route('logistics.inspection-types.index')
                ->with('success', 'Inspection Type created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create inspection type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InspectionType $inspectionType)
    {
        // Get statistics
        $statistics = $inspectionType->getStatistics();

        // Get recent shipments that used this inspection type
        $recentShipments = $inspectionType->shipments()
            ->with(['company', 'originPort'])
            ->latest()
            ->take(5)
            ->get();

        return view('logistics.inspection-types.show', compact('inspectionType', 'statistics', 'recentShipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InspectionType $inspectionType)
    {
        return view('logistics.inspection-types.edit', compact('inspectionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InspectionType $inspectionType)
    {
        $validator = Validator::make($request->all(), InspectionType::validationRules($inspectionType->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle required_documents array
            if ($request->has('required_documents')) {
                $data['required_documents'] = array_filter($request->get('required_documents', []));
            }

            // Handle applies_to array
            if ($request->has('applies_to')) {
                $data['applies_to'] = array_filter($request->get('applies_to', []));
            }

            $inspectionType->update($data);

            DB::commit();

            return redirect()->route('logistics.inspection-types.show', $inspectionType)
                ->with('success', 'Inspection Type updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update inspection type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InspectionType $inspectionType)
    {
        try {
            // Check if inspection type is used in any shipments
            if ($inspectionType->shipments()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this inspection type as it is being used in shipments.');
            }

            $inspectionType->delete();

            return redirect()->route('logistics.inspection-types.index')
                ->with('success', 'Inspection Type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete inspection type: ' . $e->getMessage());
        }
    }

    /**
     * Get inspection types by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('inspection_category');
        $mandatory = $request->get('mandatory');

        $query = InspectionType::where('status', 'Active');

        if ($category) {
            $query->where('inspection_category', $category);
        }

        if ($mandatory !== null) {
            $query->where('mandatory', $mandatory);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('inspection_name', 'like', "%{$search}%")
                    ->orWhere('inspection_code', 'like', "%{$search}%")
                    ->orWhere('regulatory_authority', 'like', "%{$search}%");
            });
        }

        $inspectionTypes = $query->select('id', 'inspection_code', 'inspection_name', 'inspection_category', 'regulatory_authority', 'estimated_duration')
            ->orderBy('inspection_name')
            ->limit(20)
            ->get();

        return response()->json($inspectionTypes);
    }

    /**
     * Toggle inspection type status
     */
    public function toggleStatus(InspectionType $inspectionType)
    {
        $newStatus = $inspectionType->status === 'Active' ? 'Inactive' : 'Active';

        $inspectionType->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Inspection Type status changed to {$newStatus}!");
    }

    /**
     * Get inspection types by category
     */
    public function getByCategory(Request $request)
    {
        $category = $request->get('category');

        if (!$category) {
            return response()->json(['error' => 'Category required'], 400);
        }

        $inspectionTypes = InspectionType::active()
            ->where('inspection_category', $category)
            ->select('id', 'inspection_code', 'inspection_name', 'estimated_duration', 'cost_estimate')
            ->orderBy('inspection_name')
            ->get();

        return response()->json($inspectionTypes);
    }

    /**
     * Get mandatory inspection types for shipment type
     */
    public function getMandatoryForShipment(Request $request)
    {
        $shipmentType = $request->get('shipment_type');
        $shipmentCategory = $request->get('shipment_category');

        $query = InspectionType::active()->where('mandatory', true);

        if ($shipmentType) {
            $query->whereJsonContains('applies_to', $shipmentType);
        }

        if ($shipmentCategory) {
            $query->whereJsonContains('applies_to', $shipmentCategory);
        }

        $inspectionTypes = $query->select('id', 'inspection_code', 'inspection_name', 'inspection_category', 'estimated_duration')
            ->orderBy('inspection_category')
            ->orderBy('inspection_name')
            ->get();

        return response()->json($inspectionTypes);
    }

    /**
     * Calculate total inspection cost and duration
     */
    public function calculateCostAndDuration(Request $request)
    {
        $inspectionIds = $request->get('inspection_ids', []);

        if (empty($inspectionIds)) {
            return response()->json([
                'total_cost' => 0,
                'total_duration' => 0,
                'message' => 'No inspections selected'
            ]);
        }

        $inspections = InspectionType::whereIn('id', $inspectionIds)->get();

        $totalCost = $inspections->sum('cost_estimate');
        $totalDuration = $inspections->sum('estimated_duration');

        return response()->json([
            'total_cost' => $totalCost,
            'total_duration' => $totalDuration,
            'breakdown' => $inspections->map(function ($inspection) {
                return [
                    'name' => $inspection->inspection_name,
                    'cost' => $inspection->cost_estimate,
                    'duration' => $inspection->estimated_duration
                ];
            }),
            'message' => "Total: $totalCost USD, {$totalDuration} hours"
        ]);
    }
}
