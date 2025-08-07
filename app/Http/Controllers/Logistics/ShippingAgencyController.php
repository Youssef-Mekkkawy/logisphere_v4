<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;

use App\Models\Logistics\ShippingAgency;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingAgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:shipping-agencies.view')->only(['index', 'show']);
        $this->middleware('permission:shipping-agencies.create')->only(['create', 'store']);
        $this->middleware('permission:shipping-agencies.edit')->only(['edit', 'update']);
        $this->middleware('permission:shipping-agencies.delete')->only(['destroy']);
    }
    public function index()
    {
        $agencies = ShippingAgency::with('country')
            ->when(request('search'), function ($query, $search) {
                return $query->search($search);
            })
            ->when(request('country_id'), function ($query, $countryId) {
                return $query->where('country_id', $countryId);
            })
            ->when(request('service_type'), function ($query, $serviceType) {
                return $query->where('service_type', $serviceType);
            })
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get filter options
        $countries = Country::orderBy('name')->get();

        return view('logistics.shipping-agencies.index', compact('agencies', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('logistics.shipping-agencies.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:shipping_agencies,code',
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'service_type' => 'required|string|in:Ocean Freight,Air Freight,Land Transport,Full Service',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'services_offered' => 'nullable|string',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ShippingAgency::create($request->all());

        return redirect()->route('logistics.shipping-agencies.index')
            ->with('success', 'Shipping agency created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingAgency $shippingAgency)
    {
        $shippingAgency->load('country');

        // Get statistics
        $statistics = $shippingAgency->getStatistics();

        // Get recent shipments
        $recentShipments = $shippingAgency->getRecentShipments(5);

        // Get active shipments count for display
        $activeShipments = $statistics['active_shipments'];

        return view('logistics.shipping-agencies.show', compact('shippingAgency', 'statistics', 'recentShipments', 'activeShipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingAgency $shippingAgency)
    {
        $countries = Country::orderBy('name')->get();

        return view('logistics.shipping-agencies.edit', compact('shippingAgency', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingAgency $shippingAgency)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:shipping_agencies,code,' . $shippingAgency->id,
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'service_type' => 'required|string|in:Ocean Freight,Air Freight,Land Transport,Full Service',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'services_offered' => 'nullable|string',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shippingAgency->update($request->all());

        return redirect()->route('logistics.shipping-agencies.show', $shippingAgency)
            ->with('success', 'Shipping agency updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingAgency $shippingAgency)
    {
        // Check if agency is being used
        if ($shippingAgency->isInUse()) {
            return redirect()->route('logistics.shipping-agencies.index')
                ->with('error', 'Cannot delete shipping agency. It has associated shipments or bookings.');
        }

        $shippingAgency->delete();

        return redirect()->route('logistics.shipping-agencies.index')
            ->with('success', 'Shipping agency deleted successfully!');
    }

    /**
     * Get shipping agency statistics for API or AJAX
     */
    public function statistics(ShippingAgency $shippingAgency)
    {
        return response()->json($shippingAgency->getStatistics());
    }

    /**
     * Toggle shipping agency status (Active/Inactive)
     */
    public function toggleStatus(ShippingAgency $shippingAgency)
    {
        $newStatus = $shippingAgency->status === 'Active' ? 'Inactive' : 'Active';
        $shippingAgency->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Shipping agency status changed to {$newStatus}!");
    }

    /**
     * Get active shipping agencies for API/Select options
     */
    public function getActive()
    {
        $agencies = ShippingAgency::active()
            ->with('country')
            ->select('id', 'code', 'name', 'contact_person', 'country_id', 'service_type')
            ->orderBy('name')
            ->get();

        return response()->json($agencies);
    }

    /**
     * Get shipping agencies by country
     */
    public function getByCountry($countryId)
    {
        $agencies = ShippingAgency::active()
            ->where('country_id', $countryId)
            ->select('id', 'code', 'name', 'contact_person', 'service_type')
            ->orderBy('name')
            ->get();

        return response()->json($agencies);
    }

    /**
     * Search shipping agencies for autocomplete
     */
    public function search(Request $request)
    {
        $query = ShippingAgency::with('country');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agencies = $query->limit(10)->get();

        return response()->json([
            'shipping_agencies' => $agencies
        ]);
    }
}
