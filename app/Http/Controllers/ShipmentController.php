<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Company;
use App\Models\Port;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shipment::with(['company', 'originPort', 'destinationPort']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('origin_port_id')) {
            $query->where('origin_port_id', $request->origin_port_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('shipment_id', 'like', '%' . $request->search . '%')
                    ->orWhere('cargo_description', 'like', '%' . $request->search . '%');
            });
        }

        $shipments = $query->latest()->paginate(20);
        $companies = Company::clients()->active()->get();
        $ports = Port::active()->get();

        return view('shipments.index', compact('shipments', 'companies', 'ports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::clients()->active()->get();
        $ports = Port::active()->get();

        return view('shipments.create', compact('companies', 'ports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
            'container_type' => 'required|string|max:255',
            'shipping_date' => 'nullable|date',
            'eta' => 'nullable|date|after_or_equal:shipping_date',
            'freight_cost' => 'nullable|numeric|min:0',
            'cargo_description' => 'nullable|string|max:1000',
            'weight' => 'nullable|numeric|min:0',
            'volume' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string|max:1000'
        ]);

        // Generate unique shipment ID
        $shipmentId = 'SH-' . date('Y') . '-' . str_pad(Shipment::count() + 1, 3, '0', STR_PAD_LEFT);

        $shipment = Shipment::create([
            'shipment_id' => $shipmentId,
            'company_id' => $request->company_id,
            'origin_port_id' => $request->origin_port_id,
            'destination_port_id' => $request->destination_port_id,
            'container_type' => $request->container_type,
            'shipping_date' => $request->shipping_date,
            'eta' => $request->eta,
            'freight_cost' => $request->freight_cost,
            'cargo_description' => $request->cargo_description,
            'weight' => $request->weight,
            'volume' => $request->volume,
            'special_instructions' => $request->special_instructions,
            'status' => 'Pending'
        ]);

        return redirect()->route('shipments.index')
            ->with('success', 'Shipment created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        $shipment->load(['company', 'originPort', 'destinationPort']);
        return view('shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        $companies = Company::clients()->active()->get();
        $ports = Port::active()->get();

        return view('shipments.edit', compact('shipment', 'companies', 'ports'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
            'container_type' => 'required|string|max:255',
            'shipping_date' => 'nullable|date',
            'eta' => 'nullable|date|after_or_equal:shipping_date',
            'freight_cost' => 'nullable|numeric|min:0',
            'cargo_description' => 'nullable|string|max:1000',
            'weight' => 'nullable|numeric|min:0',
            'volume' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string|max:1000',
            'status' => 'required|in:Pending,In Transit,At Port,Delivered,Cancelled'
        ]);

        $shipment->update($request->all());

        return redirect()->route('shipments.show', $shipment)
            ->with('success', 'Shipment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()->route('shipments.index')
            ->with('success', 'Shipment deleted successfully!');
    }

    /**
     * Track shipment by ID
     */
    public function track($shipmentId)
    {
        $shipment = Shipment::with(['company', 'originPort', 'destinationPort'])
            ->where('shipment_id', $shipmentId)
            ->firstOrFail();

        return view('shipments.track', compact('shipment'));
    }
}
