<?php

namespace App\Http\Controllers;

use App\Models\QuantityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuantityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        if ($request->filled('is_standard')) {
            $query->where('is_standard', $request->is_standard);
        }

        if ($request->filled('is_billable')) {
            $query->where('is_billable', $request->is_billable);
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

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Get quantity types with pagination
        $quantityTypes = $query->ordered()
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $categories = QuantityType::select('quantity_category')
            ->distinct()
            ->whereNotNull('quantity_category')
            ->pluck('quantity_category')
            ->sort();

        return view('submenu.quantity-types.index', compact('quantityTypes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get existing data for dropdowns
        $baseUnits = QuantityType::select('base_unit')
            ->distinct()
            ->whereNotNull('base_unit')
            ->orderBy('base_unit')
            ->pluck('base_unit');

        $reportingCategories = QuantityType::select('reporting_category')
            ->distinct()
            ->whereNotNull('reporting_category')
            ->orderBy('reporting_category')
            ->pluck('reporting_category');

        return view('submenu.quantity-types.create', compact('baseUnits', 'reportingCategories'));
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

            // Handle JSON arrays
            $arrayFields = [
                'applicable_cargo_types',
                'industry_standards',
                'common_ranges',
                'validation_rules'
            ];

            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            // Auto-generate quantity code if not provided
            if (empty($data['quantity_code'])) {
                $data['quantity_code'] = $this->generateQuantityCode($data['quantity_name'], $data['quantity_category']);
            }

            // Set default decimal places based on category if not specified
            if (!isset($data['decimal_places']) || $data['decimal_places'] === null) {
                $data['decimal_places'] = $this->getDefaultDecimalPlaces($data['quantity_category']);
            }

            QuantityType::create($data);

            DB::commit();

            return redirect()->route('submenu.quantity-types.index')
                ->with('success', 'Quantity Type created successfully!');
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
        $statistics = $quantityType->getStatistics();

        // Get compatible units for conversion
        $compatibleUnits = $quantityType->getCompatibleUnits();

        // Get recent shipments using this quantity type
        $recentShipments = $quantityType->shipments()
            ->with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take(10)
            ->get();

        return view('submenu.quantity-types.show', compact(
            'quantityType',
            'statistics',
            'compatibleUnits',
            'recentShipments'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuantityType $quantityType)
    {
        // Get existing data for dropdowns
        $baseUnits = QuantityType::select('base_unit')
            ->distinct()
            ->whereNotNull('base_unit')
            ->orderBy('base_unit')
            ->pluck('base_unit');

        $reportingCategories = QuantityType::select('reporting_category')
            ->distinct()
            ->whereNotNull('reporting_category')
            ->orderBy('reporting_category')
            ->pluck('reporting_category');

        return view('submenu.quantity-types.edit', compact('quantityType', 'baseUnits', 'reportingCategories'));
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

            // Handle JSON arrays
            $arrayFields = [
                'applicable_cargo_types',
                'industry_standards',
                'common_ranges',
                'validation_rules'
            ];

            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            $quantityType->update($data);

            DB::commit();

            return redirect()->route('submenu.quantity-types.show', $quantityType)
                ->with('success', 'Quantity Type updated successfully!');
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
            if ($quantityType->shipments()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this quantity type as it is being used in shipments.');
            }

            $quantityType->delete();

            return redirect()->route('submenu.quantity-types.index')
                ->with('success', 'Quantity Type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete quantity type: ' . $e->getMessage());
        }
    }

    /**
     * Get quantity types by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category');
        $cargoType = $request->get('cargo_type');
        $billableOnly = $request->get('billable_only', false);
        $standardOnly = $request->get('standard_only', false);

        $query = QuantityType::where('is_active', true);

        if ($category) {
            $query->where('quantity_category', $category);
        }

        if ($billableOnly) {
            $query->where('is_billable', true);
        }

        if ($standardOnly) {
            $query->where('is_standard', true);
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

        // Filter by cargo type if applicable
        if ($cargoType) {
            $quantityTypes = $quantityTypes->filter(function ($quantityType) use ($cargoType) {
                return $quantityType->isApplicableToCargoType($cargoType);
            })->values();
        }

        return response()->json($quantityTypes);
    }

    /**
     * Convert quantity between units
     */
    public function convertQuantity(Request $request)
    {
        $request->validate([
            'from_quantity_type_id' => 'required|exists:quantity_types,id',
            'to_quantity_type_id' => 'required|exists:quantity_types,id',
            'value' => 'required|numeric|min:0'
        ]);

        $fromType = QuantityType::findOrFail($request->from_quantity_type_id);
        $toType = QuantityType::findOrFail($request->to_quantity_type_id);
        $value = $request->value;

        $convertedValue = $fromType->convertTo($value, $toType);

        if ($convertedValue === null) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot convert between these quantity types - incompatible base units'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'original_value' => $fromType->formatValue($value),
            'converted_value' => $toType->formatValue($convertedValue),
            'from_type' => $fromType->full_name,
            'to_type' => $toType->full_name,
            'raw_value' => $convertedValue
        ]);
    }

    /**
     * Validate quantity value
     */
    public function validateQuantity(Request $request)
    {
        $request->validate([
            'quantity_type_id' => 'required|exists:quantity_types,id',
            'value' => 'required|numeric'
        ]);

        $quantityType = QuantityType::findOrFail($request->quantity_type_id);
        $value = $request->value;

        $validation = $quantityType->validateValue($value);

        return response()->json([
            'valid' => $validation === true,
            'errors' => $validation === true ? [] : $validation,
            'formatted_value' => $quantityType->formatValue($value),
            'billable_quantity' => $quantityType->calculateBillableQuantity($value)
        ]);
    }

    /**
     * Toggle quantity type status
     */
    public function toggleStatus(QuantityType $quantityType)
    {
        $newStatus = !$quantityType->is_active;

        $quantityType->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'Active' : 'Inactive';
        return redirect()->back()
            ->with('success', "Quantity type status changed to {$statusText}!");
    }

    /**
     * Get quantity types for specific cargo type
     */
    public function getForCargoType(Request $request)
    {
        $cargoType = $request->get('cargo_type');
        $onlyStandard = $request->get('standard_only', false);

        $query = QuantityType::active();

        if ($onlyStandard) {
            $query->standard();
        }

        $quantityTypes = $query->get()->filter(function ($quantityType) use ($cargoType) {
            return $quantityType->isApplicableToCargoType($cargoType);
        })->values();

        return response()->json($quantityTypes);
    }

    /**
     * Reorder quantity types
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:quantity_types,id',
            'orders.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->orders as $order) {
                QuantityType::where('id', $order['id'])
                    ->update(['sort_order' => $order['sort_order']]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update order'], 500);
        }
    }

    /**
     * Get billable quantity calculation
     */
    public function calculateBillable(Request $request)
    {
        $request->validate([
            'quantity_type_id' => 'required|exists:quantity_types,id',
            'actual_quantity' => 'required|numeric|min:0'
        ]);

        $quantityType = QuantityType::findOrFail($request->quantity_type_id);
        $actualQuantity = $request->actual_quantity;

        $billableQuantity = $quantityType->calculateBillableQuantity($actualQuantity);

        return response()->json([
            'actual_quantity' => $quantityType->formatValue($actualQuantity),
            'billable_quantity' => $quantityType->formatValue($billableQuantity),
            'billing_multiplier' => $quantityType->billing_multiplier,
            'minimum_chargeable' => $quantityType->minimum_chargeable,
            'is_billable' => $quantityType->is_billable,
            'rounding_method' => $quantityType->rounding_method
        ]);
    }

    /**
     * Generate quantity code
     */
    private function generateQuantityCode($name, $category)
    {
        // Create a code based on category and name
        $categoryCode = strtoupper(substr($category, 0, 2));
        $nameCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));

        $baseCode = $categoryCode . $nameCode;

        // Ensure uniqueness
        $counter = 1;
        $code = $baseCode;

        while (QuantityType::where('quantity_code', $code)->exists()) {
            $code = $baseCode . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $code;
    }

    /**
     * Get default decimal places for category
     */
    private function getDefaultDecimalPlaces($category)
    {
        $defaults = [
            'Weight' => 3,
            'Volume' => 2,
            'Count' => 0,
            'Dimension' => 2,
            'Container' => 0,
            'Liquid' => 2,
            'Area' => 2,
            'Time' => 1,
            'Custom' => 2
        ];

        return $defaults[$category] ?? 2;
    }
}
