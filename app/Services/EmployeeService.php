<?php

namespace App\services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Management\Employee;
class EmployeeService
{
    /**
     * Create a new employee with auto-generated ID
     */
    public function createEmployee(array $data): Employee
    {
        $data['employee_id'] = $this->generateEmployeeId();
        $data['status'] = 'Active';
        $data['created_by'] = Auth::id();

        $employee = Employee::create($data);

        // Log employee creation
        Log::info("Employee created", [
            'employee_id' => $employee->employee_id,
            'name' => $employee->name,
            'department' => $employee->department,
            'position' => $employee->position
        ]);

        return $employee;
    }

    /**
     * Generate unique employee ID
     */
    private function generateEmployeeId(): string
    {
        $lastEmployee = Employee::orderBy('employee_id', 'desc')->first();

        if ($lastEmployee) {
            $lastNumber = intval(substr($lastEmployee->employee_id, 4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'EMP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get employee performance metrics
     */
    public function getEmployeeMetrics(Employee $employee): array
    {
        $assignedShipments = $employee->shipments();

        return [
            'assigned_shipments' => $assignedShipments->count(),
            'active_shipments' => $assignedShipments->whereIn('status', ['Pending', 'In Transit', 'At Port'])->count(),
            'completed_shipments' => $assignedShipments->where('status', 'Delivered')->count(),
            'avg_completion_time' => $this->calculateEmployeeAvgCompletionTime($employee),
            'success_rate' => $this->calculateEmployeeSuccessRate($employee),
            'current_workload' => $assignedShipments->whereIn('status', ['Pending', 'In Transit'])->count()
        ];
    }

    /**
     * Calculate employee average completion time
     */
    private function calculateEmployeeAvgCompletionTime( $employee): float
    {
        $completedShipments = $employee->shipments()
            ->where('status', 'Delivered')
            ->whereNotNull('actual_delivery_date')
            ->get();

        if ($completedShipments->isEmpty()) {
            return 0;
        }

        $totalDays = $completedShipments->sum(function ($shipment) {
            return $shipment->created_at->diffInDays($shipment->actual_delivery_date);
        });

        return round($totalDays / $completedShipments->count(), 1);
    }

    /**
     * Calculate employee success rate (on-time deliveries)
     */
    private function calculateEmployeeSuccessRate(Employee $employee): float
    {
        $completedShipments = $employee->shipments()
            ->where('status', 'Delivered')
            ->count();

        if ($completedShipments === 0) {
            return 0;
        }

        $onTimeShipments = $employee->shipments()
            ->where('status', 'Delivered')
            ->whereColumn('actual_delivery_date', '<=', 'eta')
            ->count();

        return round(($onTimeShipments / $completedShipments) * 100, 1);
    }

    /**
     * Get department statistics
     */
    public function getDepartmentStatistics(): array
    {
        return Employee::select('department')
            ->selectRaw('COUNT(*) as employee_count')
            ->selectRaw('AVG(salary) as avg_salary')
            ->selectRaw('SUM(CASE WHEN status = "Active" THEN 1 ELSE 0 END) as active_count')
            ->groupBy('department')
            ->get()
            ->toArray();
    }
}
