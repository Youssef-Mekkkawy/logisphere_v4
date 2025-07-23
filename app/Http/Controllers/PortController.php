<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
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
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('port_type')) {
            $query->where('port_type', $request->port_type);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('major_port')) {
            $query->where('major_port', $request->major_port);
        }

        // Get ports with pagination
        $ports = $query->with('country')
            ->orderBy('port_name')
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $countries = Country::active()->orderBy('name')->get();

        return view('submenu.ports.index', compact('ports', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('submenu.ports.create', compact('countries'));
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

            // Handle facilities array
            if ($request->has('facilities')) {
                $data['facilities'] = array_filter($request->get('facilities', []));
            }

            // Handle services array
            if ($request->has('services')) {
                $data['services'] = array_filter($request->get('services', []));
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
        $port->load('country');

        // Get statistics
        $statistics = $port->getStatistics();

        // Get recent shipments
        $recentShipments = $port->originShipments()
            ->with(['company', 'destinationPort'])
            ->latest()
            ->take(5)
            ->get();

        return view('submenu.ports.show', compact('port', 'statistics', 'recentShipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Port $port)
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('submenu.ports.edit', compact('port', 'countries'));
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

            // Handle facilities array
            if ($request->has('facilities')) {
                $data['facilities'] = array_filter($request->get('facilities', []));
            }

            // Handle services array
            if ($request->has('services')) {
                $data['services'] = array_filter($request->get('services', []));
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
        $portType = $request->get('port_type');
        $countryId = $request->get('country_id');
        $majorOnly = $request->get('major_only', false);

        $query = Port::where('status', 'Active');

        if ($portType) {
            $query->where('port_type', $portType);
        }

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        if ($majorOnly) {
            $query->where('major_port', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('port_name', 'like', "%{$search}%")
                    ->orWhere('port_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $ports = $query->select('id', 'port_code', 'port_name', 'port_type', 'city', 'country')
            ->orderBy('port_name')
            ->limit(20)
            ->get();

        return response()->json($ports);
    }

    /**
     * Toggle port status
     */
    public function toggleStatus(Port $port)
    {
        $newStatus = $port->status === 'Active' ? 'Inactive' : 'Active';

        $port->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Port status changed to {$newStatus}!");
    }

    /**
     * Get port statistics
     */
    public function getStatistics(Port $port)
    {
        $statistics = $port->getStatistics();

        return response()->json($statistics);
    }

    /**
     * Get ports by type
     */
    public function getByType(Request $request)
    {
        $type = $request->get('type');

        if (!$type) {
            return response()->json(['error' => 'Port type required'], 400);
        }

        $ports = Port::active()
            ->where('port_type', $type)
            ->select('id', 'port_code', 'port_name', 'city', 'country')
            ->orderBy('port_name')
            ->get();

        return response()->json($ports);
    }

    /**
     * Get major ports
     */
    public function getMajorPorts(Request $request)
    {
        $countryId = $request->get('country_id');

        $query = Port::active()->where('major_port', true);

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        $ports = $query->select('id', 'port_code', 'port_name', 'port_type', 'city', 'country')
            ->orderBy('port_name')
            ->get();

        return response()->json($ports);
    }

    /**
     * Get ports near coordinates
     */
    public function getNearby(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $radius = $request->get('radius', 100); // Default 100km radius

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Coordinates required'], 400);
        }

        $ports = Port::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($port) use ($latitude, $longitude, $radius) {
                $distance = $port->getDistanceFrom($latitude, $longitude);
                return $distance !== null && $distance <= $radius;
            })
            ->map(function ($port) use ($latitude, $longitude) {
                $port->distance = $port->getDistanceFrom($latitude, $longitude);
                return $port;
            })
            ->sortBy('distance')
            ->values();

        return response()->json($ports);
    }

    /**
     * Get port capacity information
     */
    public function getCapacityInfo(Port $port)
    {
        $capacityInfo = [
            'current_capacity' => $port->getCurrentCapacity(),
            'max_capacity' => $port->max_capacity,
            'utilization_rate' => $port->getUtilizationRate(),
            'available_berths' => $port->getAvailableBerths(),
            'total_berths' => $port->total_berths,
            'avg_handling_time' => $port->getAverageHandlingTime()
        ];

        return response()->json($capacityInfo);
    }

    /**
     * Check port operational status
     */
    public function checkOperationalStatus(Port $port)
    {
        $status = [
            'operational' => $port->isOperational(),
            'weather_status' => $port->getWeatherStatus(),
            'congestion_level' => $port->getCongestionLevel(),
            'next_available_berth' => $port->getNextAvailableBerthTime(),
            'operating_hours' => $port->operating_hours,
            'special_notices' => $port->getSpecialNotices()
        ];

        return response()->json($status);
    }

    /**
     * Get ports with specific facilities
     */
    public function getPortsWithFacilities(Request $request)
    {
        $requiredFacilities = $request->get('facilities', []);

        if (empty($requiredFacilities)) {
            return response()->json(['error' => 'Facilities required'], 400);
        }

        $ports = Port::active()
            ->where(function ($query) use ($requiredFacilities) {
                foreach ($requiredFacilities as $facility) {
                    $query->whereJsonContains('facilities', $facility);
                }
            })
            ->select('id', 'port_code', 'port_name', 'port_type', 'city', 'country', 'facilities')
            ->orderBy('port_name')
            ->get();

        return response()->json($ports);
    }

    /**
     * Generate port performance report
     */
    public function generatePerformanceReport(Port $port, Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $report = [
            'port_info' => [
                'port_name' => $port->port_name,
                'port_code' => $port->port_code,
                'port_type' => $port->port_type
            ],
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'statistics' => $port->getPerformanceStatistics($startDate, $endDate),
            'capacity_utilization' => $port->getCapacityUtilization($startDate, $endDate),
            'top_shipping_lines' => $port->getTopShippingLines($startDate, $endDate),
            'cargo_breakdown' => $port->getCargoBreakdown($startDate, $endDate),
            'efficiency_metrics' => $port->getEfficiencyMetrics($startDate, $endDate)
        ];

        return response()->json($report);
    }
}
