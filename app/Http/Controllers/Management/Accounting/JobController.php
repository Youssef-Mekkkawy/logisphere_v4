<?php

namespace App\Http\Controllers\Management\Accounting;

use App\Http\Controllers\Controller;
use App\Models\{JobAssignment, Employee, Shipment};
use App\Http\Requests\Accounting\{StoreJobRequest, UpdateJobRequest};
use App\Services\JobService;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index()
    {
        $jobs = JobAssignment::with(['employee', 'shipment'])
            ->when(request('status'), fn($q, $status) => $q->where('status', $status))
            ->when(request('employee_id'), fn($q, $employeeId) => $q->where('employee_id', $employeeId))
            ->latest()
            ->paginate(20);

        $employees = Employee::active()->get();

        return view('accounting.jobs.index', compact('jobs', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        $shipments = Shipment::active()->get();

        return view('accounting.jobs.create', compact('employees', 'shipments'));
    }

    public function store(StoreJobRequest $request)
    {
        $job = $this->jobService->create($request->validated());

        return redirect()->route('accounting.jobs.index')
            ->with('success', 'Job assignment created successfully!');
    }

    public function show(JobAssignment $job)
    {
        $job->load(['employee', 'shipment']);
        return view('accounting.jobs.show', compact('job'));
    }

    public function edit(JobAssignment $job)
    {
        $this->authorize('update', $job);

        $employees = Employee::active()->get();
        $shipments = Shipment::active()->get();

        return view('accounting.jobs.edit', compact('job', 'employees', 'shipments'));
    }

    public function update(UpdateJobRequest $request, JobAssignment $job)
    {
        $this->authorize('update', $job);

        $job = $this->jobService->update($job, $request->validated());

        return redirect()->route('accounting.jobs.index')
            ->with('success', 'Job assignment updated successfully!');
    }

    public function destroy(JobAssignment $job)
    {
        $this->authorize('delete', $job);

        $this->jobService->delete($job);

        return redirect()->route('accounting.jobs.index')
            ->with('success', 'Job assignment deleted successfully!');
    }

    public function markComplete(JobAssignment $job)
    {
        $this->jobService->markComplete($job);

        return back()->with('success', 'Job marked as completed!');
    }

    public function markBillable(JobAssignment $job)
    {
        $this->jobService->markBillable($job);

        return back()->with('success', 'Job marked as billable!');
    }
}
