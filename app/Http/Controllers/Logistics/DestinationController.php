<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;

use App\Models\Destination;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Destination::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('destination_name', 'like', "%{$search}%")
                    ->orWhere('destination_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('destination_type')) {
            $query->where('destination_type', $request->destination_type);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('requires_appointment')) {
            $query->where('requires_appointment', $request->requires_appointment);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get destinations with pagination
        $destinations = $query->with('country')
            ->orderBy('destination_name')
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.destinations.index', compact('destinations', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.destinations.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Destination::validationRules());

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

            Destination::create($data);

            DB::commit();

            return redirect()->route('logistics.destinations.index')
                ->with('success', 'Destination created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create destination: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        $destination->load('country');

        // Get statistics
        $statistics = $destination->getStatistics();

        // Get recent shipments
        $recentShipments = $destination->shipments()
            ->with(['company', 'originPort'])
            ->latest()
            ->take(5)
            ->get();

        return view('logistics.destinations.show', compact('destination', 'statistics', 'recentShipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.destinations.edit', compact('destination', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $validator = Validator::make($request->all(), Destination::validationRules($destination->id));

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

            $destination->update($data);

            DB::commit();

            return redirect()->route('logistics.destinations.show', $destination)
                ->with('success', 'Destination updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update destination: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        try {
            // Check if destination is used in any shipments
            if ($destination->shipments()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this destination as it is being used in shipments.');
            }

            $destination->delete();

            return redirect()->route('logistics.destinations.index')
                ->with('success', 'Destination deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete destination: ' . $e->getMessage());
        }
    }

    /**
     * Get destinations by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $destinationType = $request->get('destination_type');
        $countryId = $request->get('country_id');

        $query = Destination::where('status', 'Active');

        if ($destinationType) {
            $query->where('destination_type', $destinationType);
        }

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('destination_name', 'like', "%{$search}%")
                    ->orWhere('destination_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $destinations = $query->select('id', 'destination_code', 'destination_name', 'destination_type', 'city', 'country')
            ->orderBy('destination_name')
            ->limit(20)
            ->get();

        return response()->json($destinations);
    }

    /**
     * Toggle destination status
     */
    public function toggleStatus(Destination $destination)
    {
        $newStatus = $destination->status === 'Active' ? 'Inactive' : 'Active';

        $destination->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Destination status changed to {$newStatus}!");
    }

    /**
     * Check delivery availability
     */
    public function checkAvailability(Request $request, Destination $destination)
    {
        $requestedTime = $request->get('delivery_time');

        $available = $destination->isAvailableForDelivery($requestedTime);

        return response()->json([
            'available' => $available,
            'requires_appointment' => $destination->requires_appointment,
            'facilities' => $destination->facilities,
            'access_restrictions' => $destination->access_restrictions,
            'message' => $available
                ? 'Destination is available for delivery'
                : 'Destination is not available or requires appointment'
        ]);
    }

    /**
     * Get destinations near coordinates
     */
    public function getNearby(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $radius = $request->get('radius', 50); // Default 50km radius

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Coordinates required'], 400);
        }

        $destinations = Destination::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($destination) use ($latitude, $longitude, $radius) {
                $distance = $destination->getDistanceFrom($latitude, $longitude);
                return $distance !== null && $distance <= $radius;
            })
            ->map(function ($destination) use ($latitude, $longitude) {
                $destination->distance = $destination->getDistanceFrom($latitude, $longitude);
                return $destination;
            })
            ->sortBy('distance')
            ->values();

        return response()->json($destinations);
    }
}
