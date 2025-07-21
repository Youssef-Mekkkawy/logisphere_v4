<?php

namespace App\Http\Controllers;

use App\Models\Port;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ports = Port::when(request('search'), function ($query, $search) {
            return $query->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
        })
            ->when(request('type'), function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $types = Port::distinct()->pluck('type');
        $statuses = ['Active', 'Inactive'];

        return view('submenu.ports.index', compact('ports', 'types', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('submenu.ports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:ports,code',
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'type' => 'required|string|in:Seaport,Airport,Dry Port,Container Terminal,Rail Terminal',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Port::create($request->all());

        return redirect()->route('ports.index')
            ->with('success', 'Port created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Port $port)
    {
        // Load relationships and statistics
        $port->load([
            'originShipments' => function ($query) {
                $query->latest()->take(5);
            },
            'destinationShipments' => function ($query) {
                $query->latest()->take(5);
            }
        ]);

        $statistics = [
            'total_origin_shipments' => $port->originShipments()->count(),
            'total_destination_shipments' => $port->destinationShipments()->count(),
            'active_shipments' => $port->originShipments()->where('status', 'In Transit')->count() +
                $port->destinationShipments()->where('status', 'In Transit')->count(),
            'completed_shipments' => $port->originShipments()->where('status', 'Delivered')->count() +
                $port->destinationShipments()->where('status', 'Delivered')->count()
        ];

        return view('submenu.ports.show', compact('port', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Port $port)
    {
        return view('submenu.ports.edit', compact('port'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Port $port)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:ports,code,' . $port->id,
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'type' => 'required|string|in:Seaport,Airport,Dry Port,Container Terminal,Rail Terminal',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $port->update($request->all());

        return redirect()->route('ports.index')
            ->with('success', 'Port updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Port $port)
    {
        // Check if port has any shipments
        $originShipmentsCount = $port->originShipments()->count();
        $destinationShipmentsCount = $port->destinationShipments()->count();

        if ($originShipmentsCount > 0 || $destinationShipmentsCount > 0) {
            return redirect()->route('ports.index')
                ->with('error', 'Cannot delete port. It has associated shipments.');
        }

        $port->delete();

        return redirect()->route('ports.index')
            ->with('success', 'Port deleted successfully!');
    }

    /**
     * Get port statistics for API or AJAX
     */
    public function statistics(Port $port)
    {
        $statistics = [
            'total_routes' => $port->allRoutes()->count(),
            'origin_routes' => $port->originRoutes()->count(),
            'destination_routes' => $port->destinationRoutes()->count(),
            'total_origin_shipments' => $port->originShipments()->count(),
            'total_destination_shipments' => $port->destinationShipments()->count(),
            'active_shipments' => $port->originShipments()->where('status', 'In Transit')->count() +
                $port->destinationShipments()->where('status', 'In Transit')->count(),
        ];

        return response()->json($statistics);
    }

    /**
     * Toggle port status (Active/Inactive)
     */
    public function toggleStatus(Port $port)
    {
        $newStatus = $port->status === 'Active' ? 'Inactive' : 'Active';
        $port->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Port status changed to {$newStatus}!");
    }

    /**
     * Get active ports for API/Select options
     */
    public function getActive()
    {
        $ports = Port::active()
            ->select('id', 'code', 'name', 'country', 'city')
            ->orderBy('name')
            ->get();

        return response()->json($ports);
    }
}
