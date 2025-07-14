<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Port;
use App\Models\ShippingAgency;
use App\Models\ShipmentType;

class SubmenuController extends Controller
{
    //
    function index()
    {
        return view('submenu.index');
    }

    public function ports()
    {
        $ports = Port::paginate(20);
        return view('submenu.ports.index', compact('ports'));
    }

    public function agencies()
    {
        $agencies = ShippingAgency::paginate(20);
        return view('submenu.agencies.index', compact('agencies'));
    }

    public function types()
    {
        $types = ShipmentType::paginate(20);
        return view('submenu.coo-types.index', compact('types'));
    }

    public function storePorts(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:ports,code',
            'name' => 'required|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'type' => 'required|string',
            'status' => 'required|string'
        ]);

        Port::create($request->all());

        return back()->with('success', 'Port created successfully!');
    }

    public function storeAgencies(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:shipping_agencies,code',
            'name' => 'required|string',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'country' => 'nullable|string'
        ]);

        ShippingAgency::create($request->all());

        return back()->with('success', 'Shipping agency created successfully!');
    }

    public function storeTypes(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:shipment_types,code',
            'name' => 'required|string',
            'sub_type' => 'nullable|string',
            'description' => 'nullable|string',
            'category' => 'required|string'
        ]);

        ShipmentType::create($request->all());

        return back()->with('success', 'Shipment type created successfully!');
    }
}
