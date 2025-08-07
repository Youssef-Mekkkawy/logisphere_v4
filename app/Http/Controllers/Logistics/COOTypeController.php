<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;


use App\Models\Logistics\COOType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class COOTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:coo-types.view')->only(['index', 'show']);
        $this->middleware('permission:coo-types.create')->only(['create', 'store']);
        $this->middleware('permission:coo-types.edit')->only(['edit', 'update']);
        $this->middleware('permission:coo-types.delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cooTypes = COOType::when(request('search'), function ($query, $search) {
            return $query->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('issuing_authority', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
            ->when(request('issuing_authority'), function ($query, $authority) {
                return $query->where('issuing_authority', $authority);
            })
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('is_mandatory') !== null, function ($query) {
                return $query->where('is_mandatory', request('is_mandatory'));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get unique authorities for filter dropdown
        $authorities = COOType::select('issuing_authority')
            ->distinct()
            ->whereNotNull('issuing_authority')
            ->get()
            ->map(function ($item) {
                return (object) ['name' => $item->issuing_authority];
            });

        return view('logistics.coo-types.index', compact('cooTypes', 'authorities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('logistics.coo-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:coo_types,code',
            'name' => 'required|string|max:255',
            'issuing_authority' => 'required|string|max:255',
            'is_mandatory' => 'required|boolean',
            'processing_days' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'validity_months' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
            'description' => 'nullable|string',
            'required_documents' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        COOType::create($request->all());

        return redirect()->route('logistics.coo-types.index')
            ->with('success', 'COO Type created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(COOType $cooType)
    {
        // Get usage statistics (you'll need to adjust based on your relationships)
        $statistics = [
            'total_usage' => 0, // $cooType->shipments()->count() ?? 0,
            'this_month' => 0, // $cooType->shipments()->whereMonth('created_at', now()->month)->count() ?? 0,
            'last_used' => null, // $cooType->shipments()->latest()->first()?->created_at
        ];

        // Recent usage (adjust based on your relationships)
        $recentUsage = collect(); // $cooType->shipments()->with('company')->latest()->take(5)->get() ?? collect();

        return view('logistics.coo-types.show', compact('cooType', 'statistics', 'recentUsage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(COOType $cooType)
    {
        return view('logistics.coo-types.edit', compact('cooType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, COOType $cooType)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:coo_types,code,' . $cooType->id,
            'name' => 'required|string|max:255',
            'issuing_authority' => 'required|string|max:255',
            'is_mandatory' => 'required|boolean',
            'processing_days' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'validity_months' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
            'description' => 'nullable|string',
            'required_documents' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cooType->update($request->all());

        return redirect()->route('logistics.coo-types.show', $cooType)
            ->with('success', 'COO Type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(COOType $cooType)
    {
        // Check if COO type is being used (adjust based on your relationships)
        // $usageCount = $cooType->shipments()->count() ?? 0;
        $usageCount = 0;

        if ($usageCount > 0) {
            return redirect()->route('logistics.coo-types.index')
                ->with('error', 'Cannot delete COO type. It is being used by ' . $usageCount . ' record(s).');
        }

        $cooType->delete();

        return redirect()->route('logistics.coo-types.index')
            ->with('success', 'COO Type deleted successfully!');
    }

    /**
     * Get COO type statistics for API or AJAX
     */
    public function statistics(COOType $cooType)
    {
        $statistics = [
            'total_usage' => 0, // $cooType->shipments()->count() ?? 0,
            'this_month_usage' => 0, // $cooType->shipments()->whereMonth('created_at', now()->month)->count() ?? 0,
            'last_month_usage' => 0, // $cooType->shipments()->whereMonth('created_at', now()->subMonth()->month)->count() ?? 0,
        ];

        return response()->json($statistics);
    }

    /**
     * Toggle COO type status (Active/Inactive)
     */
    public function toggleStatus(COOType $cooType)
    {
        $newStatus = $cooType->status === 'Active' ? 'Inactive' : 'Active';
        $cooType->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "COO Type status changed to {$newStatus}!");
    }

    /**
     * Get active COO types for API/Select options
     */
    public function getActive()
    {
        $cooTypes = COOType::where('status', 'Active')
            ->select('id', 'code', 'name', 'issuing_authority', 'cost', 'processing_days')
            ->orderBy('name')
            ->get();

        return response()->json($cooTypes);
    }
}
