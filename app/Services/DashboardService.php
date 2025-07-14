<?php

namespace App\services;

use App\Models\Shipment;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected $shipmentService;
    protected $companyService;
    protected $employeeService;

    public function __construct(
        ShipmentService $shipmentService,
        CompanyService $companyService,
        EmployeeService $employeeService
    ) {
        $this->shipmentService = $shipmentService;
        $this->companyService = $companyService;
        $this->employeeService = $employeeService;
    }

    /**
     * Get comprehensive dashboard data
     */
    public function getDashboardData(): array
    {
        return [
            'overview_metrics' => $this->getOverviewMetrics(),
            'recent_activities' => $this->getRecentActivities(),
            'performance_charts' => $this->getPerformanceCharts(),
            'alerts_notifications' => $this->getAlertsAndNotifications()
        ];
    }

    /**
     * Get overview metrics for dashboard cards
     */
    private function getOverviewMetrics(): array
    {
        return [
            'shipments' => $this->shipmentService->getShipmentMetrics(),
            'companies' => [
                'total_clients' => \App\Models\Company::where('type', 'Client')->count(),
                'total_suppliers' => \App\Models\Company::where('type', 'Supplier')->count(),
                'active_companies' => \App\Models\Company::where('status', 'Active')->count()
            ],
            'employees' => [
                'total_employees' => \App\Models\Employee::count(),
                'active_employees' => \App\Models\Employee::where('status', 'Active')->count(),
                'departments' => \App\Models\Employee::distinct('department')->count('department')
            ],
            'revenue' => [
                'current_month' => $this->calculateMonthlyRevenue(),
                'last_month' => $this->calculateMonthlyRevenue(-1),
                'growth_rate' => $this->calculateRevenueGrowthRate()
            ]
        ];
    }

    /**
     * Get recent activities for timeline
     */
    private function getRecentActivities(): array
    {
        // Combine different types of recent activities
        $recentShipments = Shipment::with('company')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($shipment) {
                return [
                    'type' => 'shipment_created',
                    'description' => "New shipment {$shipment->shipment_id} created for {$shipment->company->name}",
                    'timestamp' => $shipment->created_at,
                    'icon' => '📦'
                ];
            });

        $statusUpdates = TrackingEvent::with('shipment')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($event) {
                return [
                    'type' => 'status_update',
                    'description' => "Shipment {$event->shipment->shipment_id} status updated to {$event->status}",
                    'timestamp' => $event->created_at,
                    'icon' => '🚚'
                ];
            });

        return $recentShipments->concat($statusUpdates)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values()
            ->toArray();
    }

    /**
     * Get data for performance charts
     */
    private function getPerformanceCharts(): array
    {
        return [
            'shipments_by_status' => $this->getShipmentsByStatus(),
            'monthly_trends' => $this->getMonthlyTrends(),
            'department_performance' => $this->employeeService->getDepartmentStatistics(),
            'top_clients' => $this->getTopClients()
        ];
    }

    /**
     * Get shipments grouped by status for pie chart
     */
    private function getShipmentsByStatus(): array
    {
        return Shipment::select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->toArray();
    }

    /**
     * Get monthly shipment trends for line chart
     */
    private function getMonthlyTrends(): array
    {
        return Shipment::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as shipment_count'),
            DB::raw('SUM(value) as total_value')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->toArray();
    }

    /**
     * Get top clients by shipment volume
     */
    private function getTopClients(): array
    {
        return \App\Models\Company::where('type', 'Client')
            ->withCount('shipments')
            ->orderByDesc('shipments_count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get alerts and notifications
     */
    private function getAlertsAndNotifications(): array
    {
        return [
            'overdue_shipments' => $this->getOverdueShipments(),
            'pending_approvals' => $this->getPendingApprovals(),
            'system_alerts' => $this->getSystemAlerts()
        ];
    }

    /**
     * Get overdue shipments for alerts
     */
    private function getOverdueShipments(): array
    {
        return Shipment::where('eta', '<', now())
            ->whereNotIn('status', ['Delivered', 'Cancelled'])
            ->with('company')
            ->limit(5)
            ->get()
            ->map(function ($shipment) {
                return [
                    'shipment_id' => $shipment->shipment_id,
                    'company' => $shipment->company->name,
                    'days_overdue' => now()->diffInDays($shipment->eta),
                    'eta' => $shipment->eta
                ];
            })
            ->toArray();
    }

    /**
     * Calculate monthly revenue
     */
    private function calculateMonthlyRevenue(int $monthOffset = 0): float
    {
        $date = now()->addMonths($monthOffset);

        return Shipment::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum('value') ?? 0;
    }

    /**
     * Calculate revenue growth rate
     */
    private function calculateRevenueGrowthRate(): float
    {
        $currentMonth = $this->calculateMonthlyRevenue();
        $lastMonth = $this->calculateMonthlyRevenue(-1);

        if ($lastMonth === 0) {
            return 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Get pending approvals (placeholder)
     */
    private function getPendingApprovals(): array
    {
        // This would depend on your approval workflow system
        return [];
    }

    /**
     * Get system alerts (placeholder)
     */
    private function getSystemAlerts(): array
    {
        // This would include system health, maintenance, etc.
        return [];
    }
}
