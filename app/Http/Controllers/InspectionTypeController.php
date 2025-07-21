<?php

namespace App\Http\Controllers;

use App\Models\InspectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InspectionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InspectionType::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('inspection_name', 'like', "%{$search}%")
                    ->orWhere('inspection_code', 'like', "%{$search}%")
                    ->orWhere('inspection_authority', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('inspection_category')) {
            $query->where('inspection_category', $request->inspection_category);
        }

        if ($request->filled('inspection_authority')) {
            $query->where('inspection_authority', $request->inspection_authority);
        }

        if ($request->filled('is_mandatory')) {
            $query->where('is_mandatory', $request->is_mandatory);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Get inspection types with pagination
        $inspectionTypes = $query->ordered()
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $authorities = InspectionType::select('inspection_authority')
            ->distinct()
            ->whereNotNull('inspection_authority')
            ->pluck('inspection_authority')
            ->sort();

        return view('submenu.inspection-types.index', compact('inspectionTypes', 'authorities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get existing authorities for dropdown
        $authorities = InspectionType::select('inspection_authority')
            ->distinct()
            ->whereNotNull('inspection_authority')
            ->pluck('inspection_authority')
            ->sort();

        return view('submenu.inspection-types.create', compact('authorities'));
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

            // Handle JSON arrays
            $arrayFields = ['required_documents', 'inspection_criteria'];
            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            InspectionType::create($data);

            DB::commit();

            return redirect()->route('submenu.inspection-types.index')
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

        // Get recent inspections
        $recentInspections = $inspectionType->shipments()
            ->with(['company'])
            ->latest('shipment_inspections.created_at')
            ->take(5)
            ->get();

        return view('submenu.inspection-types.show', compact('inspectionType', 'statistics', 'recentInspections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InspectionType $inspectionType)
    {
        // Get existing authorities for dropdown
        $authorities = InspectionType::select('inspection_authority')
            ->distinct()
            ->whereNotNull('inspection_authority')
            ->pluck('inspection_authority')
            ->sort();

        return view('submenu.inspection-types.edit', compact('inspectionType', 'authorities'));
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

            // Handle JSON arrays
            $arrayFields = ['required_documents', 'inspection_criteria'];
            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            $inspectionType->update($data);

            DB::commit();

            return redirect()->route('submenu.inspection-types.show', $inspectionType)
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

            return redirect()->route('submenu.inspection-types.index')
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
        $category = $request->get('category');
        $mandatory = $request->get('mandatory');

        $query = InspectionType::where('is_active', true);

        if ($category) {
            $query->where('inspection_category', $category);
        }

        if ($mandatory !== null) {
            $query->where('is_mandatory', $mandatory);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('inspection_name', 'like', "%{$search}%")
                    ->orWhere('inspection_code', 'like', "%{$search}%");
            });
        }

        $inspectionTypes = $query->select('id', 'inspection_code', 'inspection_name', 'inspection_category', 'is_mandatory')
            ->ordered()
            ->limit(20)
            ->get();

        return response()->json($inspectionTypes);
    }

    /**
     * Toggle inspection type status
     */
    public function toggleStatus(InspectionType $inspectionType)
    {
        $newStatus = !$inspectionType->is_active;

        $inspectionType->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'Active' : 'Inactive';
        return redirect()->back()
            ->with('success', "Inspection type status changed to {$statusText}!");
    }

    /**
     * Check inspection availability
     */
    public function checkAvailability(Request $request, InspectionType $inspectionType)
    {
        $requestedDate = $request->get('inspection_date');
        $cargoType = $request->get('cargo_type');

        $available = $inspectionType->canBeScheduled($requestedDate);
        $applicable = $inspectionType->isApplicableToCargoType($cargoType);

        return response()->json([
            'available' => $available && $applicable,
            'applicable_to_cargo' => $applicable,
            'requires_advance_notice' => $inspectionType->requires_advance_notice,
            'notice_period_hours' => $inspectionType->notice_period_hours,
            'duration_hours' => $inspectionType->inspection_duration_hours,
            'fee' => $inspectionType->inspection_fee,
            'required_documents' => $inspectionType->required_documents,
            'message' => $available && $applicable
                ? 'Inspection can be scheduled'
                : 'Inspection cannot be scheduled for the requested date or cargo type'
        ]);
    }

    /**
     * Get mandatory inspections for cargo type
     */
    public function getMandatoryForCargoType(Request $request)
    {
        $cargoType = $request->get('cargo_type');

        $mandatoryInspections = InspectionType::active()
            ->mandatory()
            ->get()
            ->filter(function ($inspection) use ($cargoType) {
                return $inspection->isApplicableToCargoType($cargoType);
            })
            ->values();

        return response()->json($mandatoryInspections);
    }

    /**
     * Reorder inspection types
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:inspection_types,id',
            'orders.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->orders as $order) {
                InspectionType::where('id', $order['id'])
                    ->update(['sort_order' => $order['sort_order']]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update order'], 500);
        }
    }
}
