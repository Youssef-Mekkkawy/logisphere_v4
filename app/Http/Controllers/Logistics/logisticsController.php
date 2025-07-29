<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\InspectionType;
use Illuminate\Http\Request;

use App\Models\Port;
use App\Models\Service;
use App\Models\ShippingAgency;
use App\Models\ShipmentType;

class logisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // 🔥 TEMPORARILY DISABLED FOR TESTING - Re-enable after fixing
        $this->middleware('permission:logistics.view')->only(['index', 'show']);
        $this->middleware('permission:logistics.create')->only(['create', 'store']);
        $this->middleware('permission:logistics.edit')->only(['edit', 'update']);
        $this->middleware('permission:logistics.delete')->only(['destroy']);
    }
    //
    function index()
    {
        $configStats = [
            'ports' => Port::count(),
            'shipment_types' => ShipmentType::count(),
            'inspection_types' => InspectionType::count(),
            'destinations' => Destination::count(),
            'services' => Service::count(), // Add this line
        ];

        return view('logistics.index', compact('configStats'));
    }



    public function ports()
    {
        $ports = Port::paginate(20);
        return view('logistics.ports.index', compact('ports'));
    }

    public function agencies()
    {
        $agencies = ShippingAgency::paginate(20);
        return view('logistics.agencies.index', compact('agencies'));
    }

    public function types()
    {
        $types = ShipmentType::paginate(20);
        return view('logistics.coo-types.index', compact('types'));
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
