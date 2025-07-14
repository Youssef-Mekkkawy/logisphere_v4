<?php

namespace App\Http\Controllers;

use App\Models\cr;
use App\Models\Shipment;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function index()
    {
        $stats = [
            'active_shipments' => Shipment::where('status', '!=', 'Delivered')->count(),
            'client_companies' => Company::where('type', 'Client')->count(),
            'employees' => Employee::count(),
            'monthly_revenue' => 2.3 // This should be calculated from actual data
        ];

        $recentShipments = Shipment::with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recentShipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        //
    }
}
