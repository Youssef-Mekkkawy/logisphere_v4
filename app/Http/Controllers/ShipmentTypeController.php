<?php

namespace App\Http\Controllers;

use App\Models\ShipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShipmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipmentTypes = ShipmentType::when(request('search'), function ($query, $search) {
            return $query->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
            ->when(request('category'), function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = ShipmentType::distinct()->pluck('category');
        $statuses = ['Active', 'Inactive'];

        return view('shipment-types.index', compact('shipmentTypes', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'Container' => 'Container',
            'Bulk' => 'Bulk',
            'Break Bulk' => 'Break Bulk',
            'LCL' => 'LCL (Less than Container Load)',
            'FCL' => 'FCL (Full Container Load)',
            'Air Freight' => 'Air Freight',
            'Road Transport' => 'Road Transport',
            'Rail Transport' => 'Rail Transport'
        ];

        return view('shipment-types.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:shipment_types,code',
            'name' => 'required|string|max:255',
            'sub_type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ShipmentType::create($request->all());

        return redirect()->route('shipment-types.index')
            ->with('success', 'Shipment type created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipmentType $shipmentType)
    {
        // Get usage statistics
        $statistics = [
            'total_shipments' => $shipmentType->shipments()->count() ?? 0,
            'active_shipments' => $shipmentType->shipments()->active()->count() ?? 0,
            'completed_shipments' => $shipmentType->shipments()->where('status', 'Delivered')->count() ?? 0,
            'this_month_shipments' => $shipmentType->shipments()->whereMonth('created_at', now()->month)->count() ?? 0
        ];

        // Recent shipments using this type
        $recentShipments = $shipmentType->shipments()
            ->with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take(5)
            ->get() ?? collect();

        return view('shipment-types.show', compact('shipmentType', 'statistics', 'recentShipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentType $shipmentType)
    {
        $categories = [
            'Container' => 'Container',
            'Bulk' => 'Bulk',
            'Break Bulk' => 'Break Bulk',
            'LCL' => 'LCL (Less than Container Load)',
            'FCL' => 'FCL (Full Container Load)',
            'Air Freight' => 'Air Freight',
            'Road Transport' => 'Road Transport',
            'Rail Transport' => 'Rail Transport'
        ];

        return view('shipment-types.edit', compact('shipmentType', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShipmentType $shipmentType)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:shipment_types,code,' . $shipmentType->id,
            'name' => 'required|string|max:255',
            'sub_type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shipmentType->update($request->all());

        return redirect()->route('shipment-types.index')
            ->with('success', 'Shipment type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentType $shipmentType)
    {
        // Check if shipment type is being used
        $shipmentsCount = $shipmentType->shipments()->count() ?? 0;

        if ($shipmentsCount > 0) {
            return redirect()->route('shipment-types.index')
                ->with('error', 'Cannot delete shipment type. It is being used by ' . $shipmentsCount . ' shipment(s).');
        }

        $shipmentType->delete();

        return redirect()->route('shipment-types.index')
            ->with('success', 'Shipment type deleted successfully!');
    }

    /**
     * Get shipment type statistics for API or AJAX
     */
    public function statistics(ShipmentType $shipmentType)
    {
        $statistics = [
            'total_shipments' => $shipmentType->shipments()->count() ?? 0,
            'active_shipments' => $shipmentType->shipments()->active()->count() ?? 0,
            'completed_shipments' => $shipmentType->shipments()->where('status', 'Delivered')->count() ?? 0,
            'cancelled_shipments' => $shipmentType->shipments()->where('status', 'Cancelled')->count() ?? 0,
            'this_month_shipments' => $shipmentType->shipments()->whereMonth('created_at', now()->month)->count() ?? 0,
            'last_month_shipments' => $shipmentType->shipments()->whereMonth('created_at', now()->subMonth()->month)->count() ?? 0
        ];

        return response()->json($statistics);
    }

    /**
     * Toggle shipment type status (Active/Inactive)
     */
    public function toggleStatus(ShipmentType $shipmentType)
    {
        $newStatus = $shipmentType->status === 'Active' ? 'Inactive' : 'Active';
        $shipmentType->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Shipment type status changed to {$newStatus}!");
    }

    /**
     * Get active shipment types for API/Select options
     */
    public function getActive()
    {
        $shipmentTypes = ShipmentType::active()
            ->select('id', 'code', 'name', 'category', 'sub_type')
            ->orderBy('name')
            ->get();

        return response()->json($shipmentTypes);
    }

    /**
     * Get shipment types by category
     */
    public function getByCategory($category)
    {
        $shipmentTypes = ShipmentType::active()
            ->where('category', $category)
            ->select('id', 'code', 'name', 'sub_type')
            ->orderBy('name')
            ->get();

        return response()->json($shipmentTypes);
    }
}
