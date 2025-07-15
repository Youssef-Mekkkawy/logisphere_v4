<?php

namespace App\Http\Controllers;

use App\Models\ShippingAgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingAgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shippingAgencies = ShippingAgency::when(request('search'), function($query, $search) {
                return $query->where('code', 'like', "%{$search}%")
                           ->orWhere('name', 'like', "%{$search}%")
                           ->orWhere('contact_person', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                           ->orWhere('country', 'like', "%{$search}%");
            })
            ->when(request('country'), function($query, $country) {
                return $query->where('country', $country);
            })
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $countries = ShippingAgency::distinct()->pluck('country')->filter();
        $statuses = ['Active', 'Inactive'];

        return view('shipping-agencies.index', compact('shippingAgencies', 'countries', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Common countries for shipping agencies
        $countries = [
            'United Arab Emirates' => 'United Arab Emirates',
            'Saudi Arabia' => 'Saudi Arabia',
            'Egypt' => 'Egypt',
            'Singapore' => 'Singapore',
            'China' => 'China',
            'United States' => 'United States',
            'United Kingdom' => 'United Kingdom',
            'Germany' => 'Germany',
            'Netherlands' => 'Netherlands',
            'Belgium' => 'Belgium',
            'India' => 'India',
            'Malaysia' => 'Malaysia',
            'Thailand' => 'Thailand',
            'South Korea' => 'South Korea',
            'Japan' => 'Japan'
        ];

        return view('shipping-agencies.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:shipping_agencies,code',
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ShippingAgency::create($request->all());

        return redirect()->route('shipping-agencies.index')
            ->with('success', 'Shipping agency created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingAgency $shippingAgency)
    {
        // Get usage statistics
        $statistics = [
            'total_bookings' => $shippingAgency->bookings()->count() ?? 0,
            'active_bookings' => $shippingAgency->bookings()->where('status', 'Active')->count() ?? 0,
            'confirmed_bookings' => $shippingAgency->bookings()->where('is_confirmed', true)->count() ?? 0,
            'this_month_bookings' => $shippingAgency->bookings()->whereMonth('created_at', now()->month)->count() ?? 0,
            'total_shipments' => $shippingAgency->shipments()->count() ?? 0
        ];

        // Recent bookings with this agency
        $recentBookings = $shippingAgency->bookings()
            ->with(['shipment', 'company'])
            ->latest()
            ->take(5)
            ->get() ?? collect();

        // Recent shipments
        $recentShipments = $shippingAgency->shipments()
            ->with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take(5)
            ->get() ?? collect();

        return view('shipping-agencies.show', compact('shippingAgency', 'statistics', 'recentBookings', 'recentShipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingAgency $shippingAgency)
    {
        // Common countries for shipping agencies
        $countries = [
            'United Arab Emirates' => 'United Arab Emirates',
            'Saudi Arabia' => 'Saudi Arabia',
            'Egypt' => 'Egypt',
            'Singapore' => 'Singapore',
            'China' => 'China',
            'United States' => 'United States',
            'United Kingdom' => 'United Kingdom',
            'Germany' => 'Germany',
            'Netherlands' => 'Netherlands',
            'Belgium' => 'Belgium',
            'India' => 'India',
            'Malaysia' => 'Malaysia',
            'Thailand' => 'Thailand',
            'South Korea' => 'South Korea',
            'Japan' => 'Japan'
        ];

        return view('shipping-agencies.edit', compact('shippingAgency', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingAgency $shippingAgency)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:shipping_agencies,code,' . $shippingAgency->id,
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'status' => 'required|string|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shippingAgency->update($request->all());

        return redirect()->route('shipping-agencies.index')
            ->with('success', 'Shipping agency updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingAgency $shippingAgency)
    {
        // Check if agency has any bookings or shipments
        $bookingsCount = $shippingAgency->bookings()->count() ?? 0;
        $shipmentsCount = $shippingAgency->shipments()->count() ?? 0;

        if ($bookingsCount > 0 || $shipmentsCount > 0) {
            return redirect()->route('shipping-agencies.index')
                ->with('error', 'Cannot delete shipping agency. It has associated bookings or shipments.');
        }

        $shippingAgency->delete();

        return redirect()->route('shipping-agencies.index')
            ->with('success', 'Shipping agency deleted successfully!');
    }

    /**
     * Get shipping agency statistics for API or AJAX
     */
    public function statistics(ShippingAgency $shippingAgency)
    {
        $statistics = [
            'total_bookings' => $shippingAgency->bookings()->count() ?? 0,
            'active_bookings' => $shippingAgency->bookings()->where('status', 'Active')->count() ?? 0,
            'confirmed_bookings' => $shippingAgency->bookings()->where('is_confirmed', true)->count() ?? 0,
            'pending_bookings' => $shippingAgency->bookings()->where('is_confirmed', false)->count() ?? 0,
            'this_month_bookings' => $shippingAgency->bookings()->whereMonth('created_at', now()->month)->count() ?? 0,
            'last_month_bookings' => $shippingAgency->bookings()->whereMonth('created_at', now()->subMonth()->month)->count() ?? 0,
            'total_shipments' => $shippingAgency->shipments()->count() ?? 0
        ];

        return response()->json($statistics);
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
        $shippingAgencies = ShippingAgency::active()
            ->select('id', 'code', 'name', 'contact_person', 'country')
            ->orderBy('name')
            ->get();

        return response()->json($shippingAgencies);
    }

    /**
     * Get shipping agencies by country
     */
    public function getByCountry($country)
    {
        $shippingAgencies = ShippingAgency::active()
            ->where('country', $country)
            ->select('id', 'code', 'name', 'contact_person')
            ->orderBy('name')
            ->get();

        return response()->json($shippingAgencies);
    }

    /**
     * Search shipping agencies for autocomplete
     */
    public function search(Request $request)
    {
        $query = ShippingAgency::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'shipping_agencies' => $query->limit(10)->get()
        ]);
    }
}