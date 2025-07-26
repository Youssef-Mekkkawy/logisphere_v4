<?php
namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;
use App\Models\ContainerLoading;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContainerLoadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContainerLoading::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('loading_point_name', 'like', "%{$search}%")
                    ->orWhere('loading_point_code', 'like', "%{$search}%")
                    ->orWhere('operator_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('facility_type')) {
            $query->where('facility_type', $request->facility_type);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('requires_appointment')) {
            $query->where('requires_appointment', $request->requires_appointment);
        }

        // Get loading points with pagination
        $loadingPoints = $query->with('country')
            ->orderBy('loading_point_name')
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.container-loading.index', compact('loadingPoints', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.container-loading.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ContainerLoading::validationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle JSON arrays
            $arrayFields = ['operating_hours', 'container_types_handled', 'equipment_available', 'services_offered'];
            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            ContainerLoading::create($data);

            DB::commit();

            return redirect()->route('logistics.container-loading.index')
                ->with('success', 'Container Loading Point created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create loading point: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ContainerLoading $containerLoading)
    {
        $containerLoading->load('country');

        // Get statistics
        $statistics = $containerLoading->getStatistics();

        // Get recent bookings
        $recentBookings = $containerLoading->shipments()
            ->with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take(5)
            ->get();

        return view('logistics.container-loading.show', compact('containerLoading', 'statistics', 'recentBookings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContainerLoading $containerLoading)
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.container-loading.edit', compact('containerLoading', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContainerLoading $containerLoading)
    {
        $validator = Validator::make($request->all(), ContainerLoading::validationRules($containerLoading->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle JSON arrays
            $arrayFields = ['operating_hours', 'container_types_handled', 'equipment_available', 'services_offered'];
            foreach ($arrayFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = array_filter($request->get($field, []));
                }
            }

            $containerLoading->update($data);

            DB::commit();

            return redirect()->route('logistics.container-loading.show', $containerLoading)
                ->with('success', 'Container Loading Point updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update loading point: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContainerLoading $containerLoading)
    {
        try {
            // Check if loading point is used in any shipments
            if ($containerLoading->shipments()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this loading point as it is being used in shipments.');
            }

            $containerLoading->delete();

            return redirect()->route('logistics.container-loading.index')
                ->with('success', 'Container Loading Point deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete loading point: ' . $e->getMessage());
        }
    }

    /**
     * Get loading points by criteria for AJAX requests
     */
    public function getByCriteria(Request $request)
    {
        $search = $request->get('search', '');
        $facilityType = $request->get('facility_type');
        $countryId = $request->get('country_id');

        $query = ContainerLoading::where('status', 'Active');

        if ($facilityType) {
            $query->where('facility_type', $facilityType);
        }

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('loading_point_name', 'like', "%{$search}%")
                    ->orWhere('loading_point_code', 'like', "%{$search}%");
            });
        }

        $loadingPoints = $query->select('id', 'loading_point_code', 'loading_point_name', 'facility_type', 'city')
            ->orderBy('loading_point_name')
            ->limit(20)
            ->get();

        return response()->json($loadingPoints);
    }

    /**
     * Toggle loading point status
     */
    public function toggleStatus(ContainerLoading $containerLoading)
    {
        $statusOptions = ['Active', 'Inactive', 'Maintenance'];
        $currentIndex = array_search($containerLoading->status, $statusOptions);
        $newIndex = ($currentIndex + 1) % count($statusOptions);
        $newStatus = $statusOptions[$newIndex];

        $containerLoading->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Loading point status changed to {$newStatus}!");
    }

    /**
     * Check availability for booking
     */
    public function checkAvailability(Request $request, ContainerLoading $containerLoading)
    {
        $requestedDate = $request->get('date');
        $containerType = $request->get('container_type');

        $available = $containerLoading->isAvailableForBooking($requestedDate);
        $canHandle = $containerLoading->canHandleContainerType($containerType);

        return response()->json([
            'available' => $available && $canHandle,
            'can_handle_type' => $canHandle,
            'requires_appointment' => $containerLoading->requires_appointment,
            'advance_booking_hours' => $containerLoading->advance_booking_hours,
            'message' => $available && $canHandle
                ? 'Loading point is available for booking'
                : 'Loading point is not available or cannot handle this container type'
        ]);
    }
}
