<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;

use App\Models\QuantityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuantityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:quantity-types.view')->only(['index', 'show']);
        $this->middleware('permission:quantity-types.create')->only(['create', 'store']);
        $this->middleware('permission:quantity-types.edit')->only(['edit', 'update']);
        $this->middleware('permission:quantity-types.delete')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = QuantityType::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quantity_name', 'like', "%{$search}%")
                    ->orWhere('quantity_code', 'like', "%{$search}%")
                    ->orWhere('unit_of_measure', 'like', "%{$search}%")
                    ->orWhere('unit_symbol', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('quantity_category')) {
            $query->where('quantity_category', $request->quantity_category);
        }

        if ($request->filled('measurement_type')) {
            switch ($request->measurement_type) {
                case 'weight':
                    $query->where('is_weight_based', true);
                    break;
                case 'volume':
                    $query->where('is_volume_based', true);
                    break;
                case 'count':
                    $query->where('is_count_based', true);
                    break;
                case 'dimension':
                    $query->where('is_dimension_based', true);
                    break;
            }
        }

        if ($request->filled('is_standard')) {
            $query->where('is_standard', $request->is_standard);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('is_billable')) {
            $query->where('is_billable', $request->is_billable);
        }

        // Get quantity types with pagination
        $quantityTypes = $query->ordered()
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $categories = QuantityType::distinct('quantity_category')->pluck('quantity_category')->sort();

        return view('logistics.quantity-types.index', compact('quantityTypes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('logistics.quantity-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), QuantityType::validationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle JSON fields
            $jsonFields = ['applicable_cargo_types', 'industry_standards', 'validation_rules'];
            foreach ($jsonFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            // Handle common_ranges
            if ($request->filled('range_min') || $request->filled('range_max')) {
                $data['common_ranges'] = [
                    'min' => $request->get('range_min'),
                    'max' => $request->get('range_max')
                ];
            }

            QuantityType::create($data);

            DB::commit();

            return redirect()->route('logistics.quantity-types.index')
                ->with('success', 'Quantity type created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create quantity type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(QuantityType $quantityType)
    {
        // Get statistics
        $statistics = $quantityType->getUsageStatistics();

        return view('logistics.quantity-types.show', compact('quantityType', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuantityType $quantityType)
    {
        return view('logistics.quantity-types.edit', compact('quantityType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuantityType $quantityType)
    {
        $validator = Validator::make($request->all(), QuantityType::validationRules($quantityType->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle JSON fields
            $jsonFields = ['applicable_cargo_types', 'industry_standards', 'validation_rules'];
            foreach ($jsonFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            // Handle common_ranges
            if ($request->filled('range_min') || $request->filled('range_max')) {
                $data['common_ranges'] = [
                    'min' => $request->get('range_min'),
                    'max' => $request->get('range_max')
                ];
            }

            $quantityType->update($data);

            DB::commit();

            return redirect()->route('logistics.quantity-types.show', $quantityType)
                ->with('success', 'Quantity type updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update quantity type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuantityType $quantityType)
    {
        try {
            // Check if quantity type is used in any shipments
            $usageCount = $quantityType->usage_count;

            if ($usageCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this quantity type as it is being used in shipments.');
            }

            $quantityType->delete();

            return redirect()->route('logistics.quantity-types.index')
                ->with('success', 'Quantity type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete quantity type: ' . $e->getMessage());
        }
    }

    /**
     * Toggle quantity type status
     */


    /**
     * Get quantity types by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category');
        $measurementType = $request->get('measurement_type');
        $standardOnly = $request->get('standard_only', false);
        $billableOnly = $request->get('billable_only', false);

        $query = QuantityType::where('is_active', true);

        if ($category) {
            $query->where('quantity_category', $category);
        }

        if ($measurementType) {
            switch ($measurementType) {
                case 'weight':
                    $query->where('is_weight_based', true);
                    break;
                case 'volume':
                    $query->where('is_volume_based', true);
                    break;
                case 'count':
                    $query->where('is_count_based', true);
                    break;
                case 'dimension':
                    $query->where('is_dimension_based', true);
                    break;
            }
        }

        if ($standardOnly) {
            $query->where('is_standard', true);
        }

        if ($billableOnly) {
            $query->where('is_billable', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('quantity_name', 'like', "%{$search}%")
                    ->orWhere('quantity_code', 'like', "%{$search}%")
                    ->orWhere('unit_symbol', 'like', "%{$search}%");
            });
        }

        $quantityTypes = $query->select('id', 'quantity_code', 'quantity_name', 'unit_symbol', 'quantity_category')
            ->ordered()
            ->limit(20)
            ->get();

        return response()->json($quantityTypes);
    }

    /**
     * Get quantity types by category
     */
    public function getByCategory(Request $request)
    {
        $category = $request->get('category');

        if (!$category) {
            return response()->json(['error' => 'Category required'], 400);
        }

        $quantityTypes = QuantityType::active()
            ->where('quantity_category', $category)
            ->select('id', 'quantity_code', 'quantity_name', 'unit_symbol', 'is_standard')
            ->ordered()
            ->get();

        return response()->json($quantityTypes);
    }

    /**
     * Get standard quantity types
     */
    public function getStandard(Request $request)
    {
        $category = $request->get('category');

        $query = QuantityType::active()->where('is_standard', true);

        if ($category) {
            $query->where('quantity_category', $category);
        }

        $quantityTypes = $query->select('id', 'quantity_code', 'quantity_name', 'unit_symbol', 'quantity_category')
            ->ordered()
            ->get();

        return response()->json($quantityTypes);
    }

    /**
     * Validate quantity value
     */
    public function validateQuantity(Request $request)
    {
        $quantityTypeId = $request->get('quantity_type_id');
        $value = $request->get('value');

        if (!$quantityTypeId || !$value) {
            return response()->json(['error' => 'Quantity type and value required'], 400);
        }

        $quantityType = QuantityType::find($quantityTypeId);

        if (!$quantityType) {
            return response()->json(['error' => 'Quantity type not found'], 404);
        }

        $errors = $quantityType->validateValue($value);

        if (empty($errors)) {
            $formatted = $quantityType->formatValue($value);
            $billable = $quantityType->calculateBillableAmount($value);

            return response()->json([
                'valid' => true,
                'formatted_value' => $formatted,
                'billable_amount' => $billable,
                'instructions' => $quantityType->getCalculationInstructions()
            ]);
        }

        return response()->json([
            'valid' => false,
            'errors' => $errors
        ]);
    }

    /**
     * Convert between quantity types
     */
    public function convertQuantity(Request $request)
    {
        $fromTypeId = $request->get('from_type_id');
        $toTypeId = $request->get('to_type_id');
        $value = $request->get('value');

        if (!$fromTypeId || !$toTypeId || !$value) {
            return response()->json(['error' => 'From type, to type, and value required'], 400);
        }

        $fromType = QuantityType::find($fromTypeId);
        $toType = QuantityType::find($toTypeId);

        if (!$fromType || !$toType) {
            return response()->json(['error' => 'Quantity types not found'], 404);
        }

        // Check if conversion is possible (same base unit)
        if ($fromType->base_unit !== $toType->base_unit) {
            return response()->json(['error' => 'Cannot convert between different measurement types'], 400);
        }

        // Convert to base unit, then to target unit
        $baseValue = $fromType->convertToBaseUnit($value);
        $convertedValue = $toType->convertFromBaseUnit($baseValue);
        $roundedValue = $toType->roundValue($convertedValue);

        return response()->json([
            'converted_value' => $roundedValue,
            'formatted_value' => $toType->formatValue($roundedValue),
            'conversion_note' => "Converted from {$fromType->quantity_name} to {$toType->quantity_name}"
        ]);
    }

    /**
     * Get quantity type statistics
     */
    public function getStatistics(QuantityType $quantityType)
    {
        $statistics = $quantityType->getUsageStatistics();

        return response()->json($statistics);
    }

    /**
     * Bulk update sort order
     */
    public function updateSortOrder(Request $request)
    {
        $updates = $request->get('updates', []);

        if (empty($updates)) {
            return response()->json(['error' => 'No updates provided'], 400);
        }

        try {
            DB::beginTransaction();

            foreach ($updates as $update) {
                QuantityType::where('id', $update['id'])
                    ->update(['sort_order' => $update['sort_order']]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update sort order'], 500);
        }
    }

    /**
     * Get measurement compatibility
     */
    public function getCompatibility(Request $request)
    {
        $cargoType = $request->get('cargo_type');

        if (!$cargoType) {
            return response()->json(['error' => 'Cargo type required'], 400);
        }

        $quantityTypes = QuantityType::active()
            ->get()
            ->filter(function ($type) use ($cargoType) {
                return $type->isCompatibleWith($cargoType);
            })
            ->values();

        return response()->json($quantityTypes);
    }
    // Add this method to your QuantityTypeController.php

    /**
     * Toggle quantity type status
     */
    /**
     * Toggle quantity type status
     */
    public function toggleStatus(QuantityType $quantityType)
    {
        try {
            $newStatus = !$quantityType->is_active;

            $quantityType->update(['is_active' => $newStatus]);

            $status = $newStatus ? 'activated' : 'deactivated';

            // Check if request expects JSON (AJAX call)
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Quantity type {$status} successfully!",
                    'new_status' => $newStatus
                ]);
            }

            // Fallback for non-AJAX requests
            return redirect()->back()
                ->with('success', "Quantity type {$status} successfully!");
        } catch (\Exception $e) {
            // Check if request expects JSON (AJAX call)
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update status: ' . $e->getMessage()
                ], 500);
            }

            // Fallback for non-AJAX requests
            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
