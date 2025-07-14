<?php

namespace App\Services;

use App\Models\JobAssignment;
use Illuminate\Support\Facades\DB;

class JobService extends BaseAccountingService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $job = JobAssignment::create([
                'job_number' => $this->generateJobNumber(),
                'employee_id' => $data['employee_id'],
                'shipment_id' => $data['shipment_id'] ?? null,
                'task_description' => $data['task_description'],
                'hours_worked' => $data['hours_worked'] ?? 0,
                'hourly_rate' => $data['hourly_rate'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'is_billable' => $data['is_billable'] ?? true,
                'notes' => $data['notes'] ?? null,
                'status' => 'assigned'
            ]);

            $job->updateTotalAmount();

            return $job;
        });
    }

    public function update(JobAssignment $job, array $data)
    {
        return DB::transaction(function () use ($job, $data) {
            $job->update([
                'employee_id' => $data['employee_id'],
                'shipment_id' => $data['shipment_id'] ?? null,
                'task_description' => $data['task_description'],
                'hours_worked' => $data['hours_worked'],
                'hourly_rate' => $data['hourly_rate'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'is_billable' => $data['is_billable'] ?? true,
                'notes' => $data['notes'] ?? null,
            ]);

            $job->updateTotalAmount();

            return $job;
        });
    }

    public function delete(JobAssignment $job)
    {
        if ($job->status === 'billed') {
            throw new \Exception('Cannot delete jobs that have been billed.');
        }

        $job->delete();
    }

    public function markComplete(JobAssignment $job)
    {
        $job->update([
            'status' => 'completed',
            'end_date' => $job->end_date ?? now()->toDateString()
        ]);

        return $job;
    }

    public function markBillable(JobAssignment $job)
    {
        if ($job->status !== 'completed') {
            throw new \Exception('Only completed jobs can be marked as billable.');
        }

        $job->update(['status' => 'billed']);

        return $job;
    }

    protected function generateJobNumber()
    {
        $prefix = 'JOB';
        $lastJob = JobAssignment::latest()->first();
        $number = $lastJob ? ((int) substr($lastJob->job_number, -4)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
