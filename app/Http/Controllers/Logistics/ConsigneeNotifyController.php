<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\ConsigneeNotify;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ConsigneeNotifyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:consignee-notify.view')->only(['index', 'show']);
        $this->middleware('permission:consignee-notify.create')->only(['create', 'store']);
        $this->middleware('permission:consignee-notify.edit')->only(['edit', 'update']);
        $this->middleware('permission:consignee-notify.delete')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = ConsigneeNotify::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('party_name', 'like', "%{$search}%")
                    ->orWhere('party_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('party_type')) {
            $query->where('party_type', $request->party_type);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('credit_rating')) {
            $query->where('credit_rating', $request->credit_rating);
        }

        // Get parties with pagination
        $parties = $query->with('country')
            ->orderBy('party_name')
            ->paginate(15)
            ->withQueryString();

        // Get filter options
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.consignee-notify.index', compact('parties', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.consignee-notify.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ConsigneeNotify::validationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle notification preferences
            if ($request->has('notification_preferences')) {
                $data['notification_preferences'] = array_filter($request->notification_preferences);
            }

            ConsigneeNotify::create($data);

            DB::commit();

            return redirect()->route('logistics.consignee-notify.index')
                ->with('success', 'Consignee/Notify Party created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create party: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsigneeNotify $consigneeNotify)
    {
        $consigneeNotify->load('country');

        // Get statistics
        $statistics = $consigneeNotify->getStatistics();

        // Get recent shipments
        $recentShipments = $consigneeNotify->shipments()
            ->with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take(5)
            ->get();

        return view('logistics.consignee-notify.show', compact('consigneeNotify', 'statistics', 'recentShipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsigneeNotify $consigneeNotify)
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('logistics.consignee-notify.edit', compact('consigneeNotify', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConsigneeNotify $consigneeNotify)
    {
        $validator = Validator::make($request->all(), ConsigneeNotify::validationRules($consigneeNotify->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Handle notification preferences
            if ($request->has('notification_preferences')) {
                $data['notification_preferences'] = array_filter($request->notification_preferences);
            }

            $consigneeNotify->update($data);

            DB::commit();

            return redirect()->route('logistics.consignee-notify.show', $consigneeNotify)
                ->with('success', 'Consignee/Notify Party updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update party: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsigneeNotify $consigneeNotify)
    {
        try {
            // Check if party is used in any shipments
            if ($consigneeNotify->shipments()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this party as it is being used in shipments.');
            }

            $consigneeNotify->delete();

            return redirect()->route('logistics.consignee-notify.index')
                ->with('success', 'Consignee/Notify Party deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete party: ' . $e->getMessage());
        }
    }

    /**
     * Get parties by type for AJAX requests
     */
    public function getByType(Request $request)
    {
        $type = $request->get('type');
        $search = $request->get('search', '');

        $query = ConsigneeNotify::where('status', 'Active');

        if ($type && $type !== 'all') {
            $query->where(function ($q) use ($type) {
                $q->where('party_type', $type)
                    ->orWhere('party_type', 'Both');
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('party_name', 'like', "%{$search}%")
                    ->orWhere('party_code', 'like', "%{$search}%");
            });
        }

        $parties = $query->select('id', 'party_code', 'party_name', 'party_type')
            ->orderBy('party_name')
            ->limit(20)
            ->get();

        return response()->json($parties);
    }

    /**
     * Toggle party status
     */
    public function toggleStatus(ConsigneeNotify $consigneeNotify)
    {
        $newStatus = $consigneeNotify->status === 'Active' ? 'Inactive' : 'Active';

        $consigneeNotify->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Party status changed to {$newStatus}!");
    }
}
