<?php

namespace App\Http\Controllers;

use App\Models\Port;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Port::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('port_name', 'like', "%{$search}%")
                    ->orWhere('port_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('port_authority', 'like', "%{$search}%");
            });
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('port_type')) {
            $query->where('port_type', $request->port_type);
        }

        if ($request->filled('operational_status')) {
            $query->where('operational_status', $request->operational_status);
        }

        if ($request->filled('security_level')) {
            $query->where('security_level', $request->security_level);
        }

        if ($request->filled('is_major_port')) {
            $query->where('is_major_port', $request->is_major_port);
        }

        if ($request->filled('is_container_port')) {
            $query->where('is_container_port', $request->is_container_port);
        }

        if ($request->filled('is_bulk_port')) {
            $query->where('is_bulk_port', $request->is_bulk_port);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Get ports with pagination
        $ports = $query->ordered()
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $countries = Port::select('country')
            ->distinct()
            ->whereNotNull('country')
            ->orderBy('country')
            ->pluck('country');

        $portTypes = Port::select('port_type')
            ->distinct()
            ->whereNotNull('port_type')
            ->orderBy('port_type')
            ->pluck('port_type');

        $operationalStatuses = Port::select('operational_status')
            ->distinct()
            ->whereNotNull('operational_status')
            ->orderBy('operational_status')
            ->pluck('operational_status');

        return view('submenu.ports.index', compact('ports', 'countries', 'portTypes', 'operationalStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get existing data for dropdowns
        $countries = Port::select('country')
            ->distinct()
            ->whereNotNull('country')
            ->orderBy('country')
            ->pluck('country');

        $portAuthorities = Port::select('port_authority')
            ->distinct()
            ->whereNotNull('port_authority')
            ->orderBy('port_authority')
            ->pluck('port_authority');

        return view('submenu.ports.create', compact('countries', 'portAuthorities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Port::validationRules());

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
                'facilities',
                'services',
                'terminal_operators',
                'handling_equipment',
                'cargo_types_handled',
                'restrictions',
                'port_charges',
                'working_hours',
                'weather_conditions'
            ];

            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            // Auto-generate port code if not provided
            if (empty($data['port_code'])) {
                $data['port_code'] = $this->generatePortCode($data['city'], $data['country']);
            }

            Port::create($data);

            DB::commit();

            return redirect()->route('submenu.ports.index')
                ->with('success', 'Port created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create port: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Port $port)
    {
        // Get statistics
        $statistics = $port->getStatistics();

        // Get recent shipments
        $recentOriginShipments = $port->originShipments()
            ->with(['company', 'destinationPort'])
            ->latest()
            ->take(5)
            ->get();

        $recentDestinationShipments = $port->destinationShipments()
            ->with(['company', 'originPort'])
            ->latest()
            ->take(5)
            ->get();

        // Get nearby ports (within 500km)
        $nearbyPorts = collect();
        if ($port->latitude && $port->longitude) {
            $nearbyPorts = Port::where('id', '!=', $port->id)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
                ->map(function ($nearbyPort) use ($port) {
                    $distance = $port->calculateDistance($nearbyPort);
                    return [
                        'port' => $nearbyPort,
                        'distance' => $distance
                    ];
                })
                ->filter(function ($item) {
                    return $item['distance'] && $item['distance'] <= 500;
                })
                ->sortBy('distance')
                ->take(10);
        }

        return view('submenu.ports.show', compact(
            'port',
            'statistics',
            'recentOriginShipments',
            'recentDestinationShipments',
            'nearbyPorts'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Port $port)
    {
        // Get existing data for dropdowns
        $countries = Port::select('country')
            ->distinct()
            ->whereNotNull('country')
            ->orderBy('country')
            ->pluck('country');

        $portAuthorities = Port::select('port_authority')
            ->distinct()
            ->whereNotNull('port_authority')
            ->orderBy('port_authority')
            ->pluck('port_authority');

        return view('submenu.ports.edit', compact('port', 'countries', 'portAuthorities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Port $port)
    {
        $validator = Validator::make($request->all(), Port::validationRules($port->id));

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
                'facilities',
                'services',
                'terminal_operators',
                'handling_equipment',
                'cargo_types_handled',
                'restrictions',
                'port_charges',
                'working_hours',
                'weather_conditions'
            ];

            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            $port->update($data);

            DB::commit();

            return redirect()->route('submenu.ports.show', $port)
                ->with('success', 'Port updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update port: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Port $port)
    {
        try {
            // Check if port is used in any shipments
            $shipmentsCount = $port->originShipments()->count() + $port->destinationShipments()->count();

            if ($shipmentsCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this port as it is being used in shipments.');
            }

            $port->delete();

            return redirect()->route('submenu.ports.index')
                ->with('success', 'Port deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete port: ' . $e->getMessage());
        }
    }

    /**
     * Get ports by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $country = $request->get('country');
        $portType = $request->get('port_type');
        $onlyActive = $request->get('active_only', true);

        $query = Port::query();

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        if ($country) {
            $query->where('country', $country);
        }

        if ($portType) {
            $query->where('port_type', $portType);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('port_name', 'like', "%{$search}%")
                    ->orWhere('port_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $ports = $query->select('id', 'port_code', 'port_name', 'city', 'country', 'port_type')
            ->ordered()
            ->limit(20)
            ->get();

        return response()->json($ports);
    }

    /**
     * Toggle port status
     */
    public function toggleStatus(Port $port)
    {
        $newStatus = !$port->is_active;

        $port->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'Active' : 'Inactive';
        return redirect()->back()
            ->with('success', "Port status changed to {$statusText}!");
    }

    /**
     * Check port operational status
     */
    public function checkOperationalStatus(Port $port)
    {
        $isOperational = $port->isOperational();
        $weatherStatus = $port->getWeatherStatus();

        return response()->json([
            'operational' => $isOperational,
            'status' => $port->operational_status,
            'weather' => $weatherStatus,
            'services' => $port->available_services,
            'last_updated' => $port->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get port facilities and services
     */
    public function getFacilitiesAndServices(Port $port)
    {
        return response()->json([
            'facilities' => $port->facilities ?? [],
            'services' => $port->services ?? [],
            'handling_equipment' => $port->handling_equipment ?? [],
            'cargo_types' => $port->cargo_types_handled ?? [],
            'connections' => [
                'rail' => $port->rail_connection,
                'road' => $port->road_connection,
                'airport_distance' => $port->airport_distance_km
            ],
            'port_services' => [
                'customs' => $port->customs_available,
                'quarantine' => $port->quarantine_available,
                'bunker' => $port->bunker_available,
                'fresh_water' => $port->fresh_water_available,
                'pilot' => $port->pilot_required,
                'tugs' => $port->tugs_available,
                'anchorage' => $port->anchorage_available
            ]
        ]);
    }

    /**
     * Calculate distance between ports
     */
    public function calculateDistance(Request $request)
    {
        $request->validate([
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id'
        ]);

        $originPort = Port::findOrFail($request->origin_port_id);
        $destinationPort = Port::findOrFail($request->destination_port_id);

        $distance = $originPort->calculateDistance($destinationPort);

        return response()->json([
            'origin' => $originPort->full_name,
            'destination' => $destinationPort->full_name,
            'distance_km' => $distance,
            'distance_nm' => $distance ? round($distance * 0.539957, 2) : null
        ]);
    }

    /**
     * Get ports within radius
     */
    public function getPortsInRadius(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'required|numeric|min:1|max:2000'
        ]);

        $ports = Port::nearLocation(
            $request->latitude,
            $request->longitude,
            $request->radius_km
        )->active()->get();

        return response()->json($ports);
    }

    /**
     * Reorder ports
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:ports,id',
            'orders.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->orders as $order) {
                Port::where('id', $order['id'])
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
     * Export ports data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $query = Port::query();

        // Apply same filters as index
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('port_type')) {
            $query->where('port_type', $request->port_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $ports = $query->ordered()->get();

        // This would integrate with export service in production
        return response()->json([
            'message' => 'Export functionality would be implemented here',
            'count' => $ports->count(),
            'format' => $format
        ]);
    }

    /**
     * Generate port code
     */
    private function generatePortCode($city, $country)
    {
        // Create a code based on city and country
        $cityCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $city), 0, 2));
        $countryCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $country), 0, 2));

        $baseCode = $cityCode . $countryCode;

        // Ensure uniqueness
        $counter = 1;
        $code = $baseCode;

        while (Port::where('port_code', $code)->exists()) {
            $code = $baseCode . $counter;
            $counter++;
        }

        return $code;
    }
}
