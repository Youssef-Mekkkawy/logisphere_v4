<?php

namespace App\Http\Controllers;

use App\Models\cr;
use App\Models\Shipment;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_shipments' => Shipment::where('status', '!=', 'Delivered')->count(),
            'client_companies' => Company::where('type', 'Client')->count(),
            'employees' => Employee::count(),
            'monthly_revenue' => 2.3 // This should be calculated from actual data
        ];
        // dd($stats);
        // Get key metrics
        $metrics = [
            'total_shipments' => Shipment::count(),
            'active_shipments' => Shipment::whereIn('status', ['Pending', 'In Transit', 'At Port'])->count(),
            'delivered_shipments' => Shipment::where('status', 'Delivered')->count(),
            'total_companies' => Company::count(),
            'active_employees' => Employee::where('status', 'Active')->count(),
        ];

        // Recent shipments
        $recentShipments = Shipment::with(['company', 'originPort', 'destinationPort'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Shipments by status for chart
        $shipmentsByStatus = Shipment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Monthly shipment trends
        $monthlyTrends = Shipment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('dashboard.index', compact('metrics', 'recentShipments', 'shipmentsByStatus', 'monthlyTrends', 'stats'));
    }
    public function getMetrics()
    {
        $dashboardService = app(\App\Services\DashboardService::class);
        return response()->json($dashboardService->getDashboardData());
    }
}
