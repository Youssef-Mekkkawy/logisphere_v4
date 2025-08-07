<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;

use App\Models\Logistics\ShipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ShipmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:shipment-types.view')->only(['index', 'show']);
        $this->middleware('permission:shipment-types.create')->only(['create', 'store']);
        $this->middleware('permission:shipment-types.edit')->only(['edit', 'update']);
        $this->middleware('permission:shipment-types.delete')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = ShipmentType::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('type_name', 'like', "%{$search}%")
                    ->orWhere('type_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('cargo_type')) {
            $query->where('cargo_type', $request->cargo_type);
        }

        if ($request->filled('transit_mode')) {
            $query->where('transit_mode', $request->transit_mode);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('temperature_controlled')) {
            $query->where('temperature_controlled', $request->temperature_controlled);
        }

        if ($request->filled('hazardous_material')) {
            $query->where('hazardous_material', $request->hazardous_material);
        }

        if ($request->filled('priority_level')) {
            $query->where('priority_level', $request->priority_level);
        }

        if ($request->filled('service_level')) {
            $query->where('service_level', $request->service_level);
        }

        // Get shipment types with pagination
        $shipmentTypes = $query->orderBy('type_name')
            ->paginate(15)
            ->withQueryString();

        return view('logistics.shipment-types.index', compact('shipmentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('logistics.shipment-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ShipmentType::validationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle array fields
            $arrayFields = [
                'container_types',
                'handling_requirements',
                'documentation_required',
                'applicable_routes',
                'seasonal_restrictions',
                'equipment_needed',
                'packaging_requirements',
                'labeling_requirements',
                'certification_needed'
            ];

            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            // Handle weight restrictions JSON
            if ($request->has('weight_restrictions') && is_array($request->weight_restrictions)) {
                $data['weight_restrictions'] = array_filter($request->weight_restrictions);
            }

            // Handle volume restrictions JSON
            if ($request->has('volume_restrictions') && is_array($request->volume_restrictions)) {
                $data['volume_restrictions'] = array_filter($request->volume_restrictions);
            }

            // Handle dimension restrictions JSON
            if ($request->has('dimension_restrictions') && is_array($request->dimension_restrictions)) {
                $data['dimension_restrictions'] = array_filter($request->dimension_restrictions);
            }

            ShipmentType::create($data);

            DB::commit();

            return redirect()->route('logistics.shipment-types.index')
                ->with('success', 'Shipment type created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create shipment type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipmentType $shipmentType)
    {
        // Get statistics
        $statistics = $shipmentType->getStatistics();

        // Get recent shipments
        $recentShipments = $shipmentType->shipments()
            ->with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take(10)
            ->get();

        // Get top clients
        $topClients = $shipmentType->getTopClients(5);

        return view('logistics.shipment-types.show', compact('shipmentType', 'statistics', 'recentShipments', 'topClients'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentType $shipmentType)
    {
        return view('logistics.shipment-types.edit', compact('shipmentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShipmentType $shipmentType)
    {
        $validator = Validator::make($request->all(), ShipmentType::validationRules($shipmentType->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle array fields
            $arrayFields = [
                'container_types',
                'handling_requirements',
                'documentation_required',
                'applicable_routes',
                'seasonal_restrictions',
                'equipment_needed',
                'packaging_requirements',
                'labeling_requirements',
                'certification_needed'
            ];

            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            // Handle restrictions JSON
            if ($request->has('weight_restrictions') && is_array($request->weight_restrictions)) {
                $data['weight_restrictions'] = array_filter($request->weight_restrictions);
            }

            if ($request->has('volume_restrictions') && is_array($request->volume_restrictions)) {
                $data['volume_restrictions'] = array_filter($request->volume_restrictions);
            }

            if ($request->has('dimension_restrictions') && is_array($request->dimension_restrictions)) {
                $data['dimension_restrictions'] = array_filter($request->dimension_restrictions);
            }

            $shipmentType->update($data);

            DB::commit();

            return redirect()->route('logistics.shipment-types.show', $shipmentType)
                ->with('success', 'Shipment type updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update shipment type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentType $shipmentType)
    {
        try {
            // Check if shipment type is used in any shipments
            $shipmentsCount = $shipmentType->shipments()->count();

            if ($shipmentsCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this shipment type as it is being used in shipments.');
            }

            $shipmentType->delete();

            return redirect()->route('logistics.shipment-types.index')
                ->with('success', 'Shipment type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete shipment type: ' . $e->getMessage());
        }
    }

    /**
     * Get shipment types by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category');
        $cargoType = $request->get('cargo_type');
        $transitMode = $request->get('transit_mode');
        $serviceLevel = $request->get('service_level');
        $temperatureControlled = $request->get('temperature_controlled');
        $hazardousMaterial = $request->get('hazardous_material');

        $query = ShipmentType::where('status', 'Active');

        if ($category) {
            $query->where('category', $category);
        }

        if ($cargoType) {
            $query->where('cargo_type', $cargoType);
        }

        if ($transitMode) {
            $query->where('transit_mode', $transitMode);
        }

        if ($serviceLevel) {
            $query->where('service_level', $serviceLevel);
        }

        if ($temperatureControlled) {
            $query->where('temperature_controlled', true);
        }

        if ($hazardousMaterial) {
            $query->where('hazardous_material', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('type_name', 'like', "%{$search}%")
                    ->orWhere('type_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $shipmentTypes = $query->select('id', 'type_code', 'type_name', 'category', 'cargo_type', 'transit_mode', 'service_level')
            ->orderBy('type_name')
            ->limit(20)
            ->get();

        return response()->json($shipmentTypes);
    }

    /**
     * Toggle shipment type status
     */
    public function toggleStatus(ShipmentType $shipmentType)
    {
        $newStatus = $shipmentType->status === 'Active' ? 'Inactive' : 'Active';

        $shipmentType->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Shipment type status changed to {$newStatus}!");
    }

    /**
     * Get shipment type statistics
     */
    public function getStatistics(ShipmentType $shipmentType)
    {
        $statistics = $shipmentType->getStatistics();

        return response()->json($statistics);
    }

    /**
     * Get shipment types by category
     */
    public function getByCategory(Request $request)
    {
        $category = $request->get('category');

        if (!$category) {
            return response()->json(['error' => 'Category required'], 400);
        }

        $shipmentTypes = ShipmentType::active()
            ->where('category', $category)
            ->select('id', 'type_code', 'type_name', 'cargo_type', 'transit_mode')
            ->orderBy('type_name')
            ->get();

        return response()->json($shipmentTypes);
    }

    /**
     * Get shipment types by cargo type
     */
    public function getByCargoType(Request $request)
    {
        $cargoType = $request->get('cargo_type');

        if (!$cargoType) {
            return response()->json(['error' => 'Cargo type required'], 400);
        }

        $shipmentTypes = ShipmentType::active()
            ->where('cargo_type', $cargoType)
            ->select('id', 'type_code', 'type_name', 'category', 'transit_mode')
            ->orderBy('type_name')
            ->get();

        return response()->json($shipmentTypes);
    }

    /**
     * Get express service shipment types
     */
    public function getExpressService(Request $request)
    {
        $shipmentTypes = ShipmentType::active()
            ->where('express_service_available', true)
            ->select('id', 'type_code', 'type_name', 'category', 'estimated_transit_days')
            ->orderBy('estimated_transit_days')
            ->get();

        return response()->json($shipmentTypes);
    }

    /**
     * Get economy service shipment types
     */
    public function getEconomyService(Request $request)
    {
        $shipmentTypes = ShipmentType::active()
            ->where('economy_service_available', true)
            ->select('id', 'type_code', 'type_name', 'category', 'cost_factor')
            ->orderBy('cost_factor')
            ->get();

        return response()->json($shipmentTypes);
    }

    /**
     * Calculate cost multiplier for shipment type
     */
    public function calculateCostMultiplier(ShipmentType $shipmentType, Request $request)
    {
        $baseMultiplier = $shipmentType->calculateCostMultiplier();

        $routeAdjustment = $request->get('route_adjustment', 0);
        $seasonalAdjustment = $request->get('seasonal_adjustment', 0);
        $urgencyAdjustment = $request->get('urgency_adjustment', 0);

        $totalMultiplier = $baseMultiplier + $routeAdjustment + $seasonalAdjustment + $urgencyAdjustment;

        return response()->json([
            'base_multiplier' => $baseMultiplier,
            'route_adjustment' => $routeAdjustment,
            'seasonal_adjustment' => $seasonalAdjustment,
            'urgency_adjustment' => $urgencyAdjustment,
            'total_multiplier' => round($totalMultiplier, 3),
            'special_features' => $shipmentType->special_features,
            'service_options' => $shipmentType->service_options
        ]);
    }

    /**
     * Get shipment types for specific route
     */
    public function getForRoute(Request $request)
    {
        $originPort = $request->get('origin_port');
        $destinationPort = $request->get('destination_port');

        if (!$originPort || !$destinationPort) {
            return response()->json(['error' => 'Origin and destination ports required'], 400);
        }

        $shipmentTypes = ShipmentType::active()
            ->get()
            ->filter(function ($type) use ($originPort, $destinationPort) {
                return $type->isAvailableForRoute($originPort, $destinationPort);
            })
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'type_code' => $type->type_code,
                    'type_name' => $type->type_name,
                    'category' => $type->category,
                    'transit_mode' => $type->transit_mode,
                    'estimated_transit' => $type->estimated_transit_display,
                    'cost_multiplier' => $type->calculateCostMultiplier()
                ];
            })
            ->values();

        return response()->json($shipmentTypes);
    }

    /**
     * Check shipment type availability
     */
    public function checkAvailability(ShipmentType $shipmentType, Request $request)
    {
        $date = $request->get('date', now());
        $cargoType = $request->get('cargo_type');
        $originPort = $request->get('origin_port');
        $destinationPort = $request->get('destination_port');

        $isEffective = $shipmentType->isEffectiveOn($date);
        $canHandleCargo = $cargoType ? $shipmentType->canHandleCargoType($cargoType) : true;
        $availableForRoute = ($originPort && $destinationPort)
            ? $shipmentType->isAvailableForRoute($originPort, $destinationPort)
            : true;

        $availability = [
            'available' => $isEffective && $canHandleCargo && $availableForRoute,
            'effective_date_valid' => $isEffective,
            'cargo_type_valid' => $canHandleCargo,
            'route_available' => $availableForRoute,
            'status' => $shipmentType->status,
            'effective_from' => $shipmentType->effective_from,
            'effective_to' => $shipmentType->effective_to,
            'estimated_transit' => $shipmentType->estimated_transit_display,
            'cost_multiplier' => $shipmentType->calculateCostMultiplier(),
            'special_features' => $shipmentType->special_features,
            'service_options' => $shipmentType->service_options
        ];

        return response()->json($availability);
    }

    /**
     * Get shipment types with specific features
     */
    public function getWithFeatures(Request $request)
    {
        $features = $request->get('features', []);

        if (empty($features)) {
            return response()->json(['error' => 'Features required'], 400);
        }

        $query = ShipmentType::active();

        foreach ($features as $feature) {
            switch ($feature) {
                case 'temperature_controlled':
                    $query->where('temperature_controlled', true);
                    break;
                case 'hazardous_material':
                    $query->where('hazardous_material', true);
                    break;
                case 'high_value':
                    $query->where('high_value_cargo', true);
                    break;
                case 'fragile':
                    $query->where('fragile_cargo', true);
                    break;
                case 'oversized':
                    $query->where('oversized_cargo', true);
                    break;
                case 'express':
                    $query->where('express_service_available', true);
                    break;
                case 'door_to_door':
                    $query->where('door_to_door_available', true);
                    break;
            }
        }

        $shipmentTypes = $query->select('id', 'type_code', 'type_name', 'category', 'special_features', 'service_options')
            ->orderBy('type_name')
            ->get();

        return response()->json($shipmentTypes);
    }

    /**
     * Generate shipment type performance report
     */
    public function generatePerformanceReport(ShipmentType $shipmentType, Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $report = [
            'shipment_type_info' => [
                'type_name' => $shipmentType->type_name,
                'type_code' => $shipmentType->type_code,
                'category' => $shipmentType->category,
                'cargo_type' => $shipmentType->cargo_type
            ],
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'performance_metrics' => [
                'total_shipments' => $shipmentType->total_shipments,
                'period_shipments' => $shipmentType->shipments()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'active_shipments' => $shipmentType->getActiveShipments(),
                'avg_transit_time' => $shipmentType->getAverageTransitTime(),
                'cost_effectiveness' => $shipmentType->getCostEffectiveness(),
                'performance_rating' => $shipmentType->getPerformanceRating()
            ],
            'usage_trends' => $shipmentType->getShipmentsByMonth($startDate, $endDate),
            'top_clients' => $shipmentType->getTopClients(10),
            'cost_analysis' => [
                'cost_factor' => $shipmentType->cost_factor,
                'base_multiplier' => $shipmentType->base_rate_multiplier,
                'calculated_multiplier' => $shipmentType->calculateCostMultiplier()
            ]
        ];

        return response()->json($report);
    }
}
