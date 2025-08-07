<?php

namespace App\Http\Controllers\logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\BoslaGomrok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoslaGomrokController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:bosla-gomrok.view')->only(['index', 'show']);
        $this->middleware('permission:bosla-gomrok.create')->only(['create', 'store']);
        $this->middleware('permission:bosla-gomrok.edit')->only(['edit', 'update']);
        $this->middleware('permission:bosla-gomrok.delete')->only(['destroy']);
    }
    public function index()
    {
        $boslaItems = BoslaGomrok::when(request('search'), function ($query, $search) {
            return $query->search($search);
        })
            ->when(request('document_type'), function ($query, $type) {
                return $query->where('document_type', $type);
            })
            ->when(request('customs_office'), function ($query, $office) {
                return $query->where('customs_office', $office);
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

        // Get unique customs offices for filter dropdown
        $customsOffices = BoslaGomrok::select('customs_office')
            ->distinct()
            ->whereNotNull('customs_office')
            ->get()
            ->map(function ($item) {
                return (object) ['name' => $item->customs_office];
            });

        return view('logistics.bosla-gomrok.index', compact('boslaItems', 'customsOffices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get existing customs offices for dropdown
        $customsOffices = BoslaGomrok::select('customs_office')
            ->distinct()
            ->whereNotNull('customs_office')
            ->pluck('customs_office')
            ->unique()
            ->sort()
            ->values();

        return view('logistics.bosla-gomrok.create', compact('customsOffices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:bosla_gomrok,code',
            'name' => 'required|string|max:255',
            'document_type' => 'required|string|in:Export,Import,Transit,Temporary',
            'customs_office' => 'required|string|max:255',
            'processing_hours' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:0',
            'is_mandatory' => 'required|boolean',
            'status' => 'required|string|in:Active,Inactive',
            'description' => 'nullable|string',
            'required_documents' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        BoslaGomrok::create($request->all());

        return redirect()->route('logistics.bosla-gomrok.index')
            ->with('success', 'Bosla from Gomrok created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BoslaGomrok $boslaGomrok)
    {
        // Get statistics
        $statistics = $boslaGomrok->getStatistics();

        // Get recent usage (adjust based on your relationships)
        $recentUsage = collect(); // $boslaGomrok->shipments()->with('company')->latest()->take(5)->get();

        return view('logistics.bosla-gomrok.show', compact('boslaGomrok', 'statistics', 'recentUsage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoslaGomrok $boslaGomrok)
    {
        // Get existing customs offices for dropdown
        $customsOffices = BoslaGomrok::select('customs_office')
            ->distinct()
            ->whereNotNull('customs_office')
            ->pluck('customs_office')
            ->unique()
            ->sort()
            ->values();

        return view('logistics.bosla-gomrok.edit', compact('boslaGomrok', 'customsOffices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoslaGomrok $boslaGomrok)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:bosla_gomrok,code,' . $boslaGomrok->id,
            'name' => 'required|string|max:255',
            'document_type' => 'required|string|in:Export,Import,Transit,Temporary',
            'customs_office' => 'required|string|max:255',
            'processing_hours' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:0',
            'is_mandatory' => 'required|boolean',
            'status' => 'required|string|in:Active,Inactive',
            'description' => 'nullable|string',
            'required_documents' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $boslaGomrok->update($request->all());

        return redirect()->route('logistics.bosla-gomrok.show', $boslaGomrok)
            ->with('success', 'Bosla from Gomrok updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoslaGomrok $boslaGomrok)
    {
        // Check if bosla gomrok is being used
        if ($boslaGomrok->isInUse()) {
            $usageCount = $boslaGomrok->getUsageCount();
            return redirect()->route('logistics.bosla-gomrok.index')
                ->with('error', 'Cannot delete Bosla from Gomrok. It is being used by ' . $usageCount . ' record(s).');
        }

        $boslaGomrok->delete();

        return redirect()->route('logistics.bosla-gomrok.index')
            ->with('success', 'Bosla from Gomrok deleted successfully!');
    }

    /**
     * Get bosla gomrok statistics for API or AJAX
     */
    public function statistics(BoslaGomrok $boslaGomrok)
    {
        return response()->json($boslaGomrok->getStatistics());
    }

    /**
     * Toggle bosla gomrok status (Active/Inactive)
     */
    public function toggleStatus(BoslaGomrok $boslaGomrok)
    {
        $newStatus = $boslaGomrok->status === 'Active' ? 'Inactive' : 'Active';
        $boslaGomrok->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Bosla from Gomrok status changed to {$newStatus}!");
    }

    /**
     * Get active bosla gomrok items for API/Select options
     */
    public function getActive()
    {
        $boslaItems = BoslaGomrok::active()
            ->select('id', 'code', 'name', 'document_type', 'customs_office', 'cost', 'processing_hours')
            ->orderBy('name')
            ->get();

        return response()->json($boslaItems);
    }

    /**
     * Get bosla gomrok items by customs office
     */
    public function getByOffice($office)
    {
        $boslaItems = BoslaGomrok::active()
            ->where('customs_office', $office)
            ->select('id', 'code', 'name', 'document_type', 'cost', 'processing_hours')
            ->orderBy('name')
            ->get();

        return response()->json($boslaItems);
    }

    /**
     * Get bosla gomrok items by document type
     */
    public function getByType($type)
    {
        $boslaItems = BoslaGomrok::active()
            ->where('document_type', $type)
            ->select('id', 'code', 'name', 'customs_office', 'cost', 'processing_hours')
            ->orderBy('name')
            ->get();

        return response()->json($boslaItems);
    }

    /**
     * Get processing time analysis
     */
    public function getProcessingAnalysis()
    {
        $analysis = [
            'average_processing_time' => BoslaGomrok::active()->avg('processing_hours'),
            'fastest_processing' => BoslaGomrok::active()->min('processing_hours'),
            'slowest_processing' => BoslaGomrok::active()->max('processing_hours'),
            'by_document_type' => BoslaGomrok::active()
                ->selectRaw('document_type, AVG(processing_hours) as avg_hours, COUNT(*) as count')
                ->groupBy('document_type')
                ->get(),
            'by_customs_office' => BoslaGomrok::active()
                ->selectRaw('customs_office, AVG(processing_hours) as avg_hours, COUNT(*) as count')
                ->groupBy('customs_office')
                ->get(),
        ];

        return response()->json($analysis);
    }

    /**
     * Get cost analysis
     */
    public function getCostAnalysis()
    {
        $analysis = [
            'average_cost' => BoslaGomrok::active()->avg('cost'),
            'total_potential_revenue' => BoslaGomrok::active()->sum('cost'),
            'cheapest_option' => BoslaGomrok::active()->min('cost'),
            'most_expensive' => BoslaGomrok::active()->max('cost'),
            'by_document_type' => BoslaGomrok::active()
                ->selectRaw('document_type, AVG(cost) as avg_cost, SUM(cost) as total_cost, COUNT(*) as count')
                ->groupBy('document_type')
                ->get(),
        ];

        return response()->json($analysis);
    }
}
