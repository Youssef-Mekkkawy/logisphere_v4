@extends('layouts.app')

@section('title', 'Employee Jobs')
@section('page_title', 'Employee Jobs Management')
@section('breadcrumb', 'Home > Accounting > Employee Jobs')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">💼 Employee Jobs</h2>
            <p style="color: #64748b;">Manage employee job assignments and positions</p>
        </div>
        <a href="{{ route('accounting.jobs.create') }}" class="btn btn-primary">+ Add New Job</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}"
                placeholder="Job title, employee name..." />
            <x-form-select name="employee_id" label="Employee" :options="$employees->pluck('name', 'id')" value="{{ request('employee_id') }}" />
            <x-form-select name="job_status" label="Job Status" :options="[
                'Active' => 'Active',
                'Completed' => 'Completed',
                'On Hold' => 'On Hold',
                'Cancelled' => 'Cancelled',
            ]" value="{{ request('job_status') }}" />
            <x-form-select name="priority" label="Priority" :options="[
                'High' => 'High',
                'Medium' => 'Medium',
                'Low' => 'Low',
            ]" value="{{ request('priority') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('accounting.jobs.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Jobs Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Title</th>
                    <th>Assigned Employee</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td><strong>{{ $job->job_id }}</strong></td>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->employee->name ?? 'Unassigned' }}</td>
                        <td>{{ $job->start_date ? $job->start_date->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $job->due_date ? $job->due_date->format('M d, Y') : 'N/A' }}</td>
                        <td><span class="status-badge status-{{ strtolower($job->priority) }}">{{ $job->priority }}</span>
                        </td>
                        <td><span
                                class="status-badge status-{{ strtolower(str_replace(' ', '', $job->status)) }}">{{ $job->status }}</span>
                        </td>
                        <td>
                            <div style="background: #f3f4f6; border-radius: 0.375rem; overflow: hidden; height: 8px;">
                                <div
                                    style="background: var(--primary-color); height: 100%; width: {{ $job->progress_percentage ?? 0 }}%;">
                                </div>
                            </div>
                            <small style="color: #64748b;">{{ $job->progress_percentage ?? 0 }}%</small>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('accounting.jobs.show', $job) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('accounting.jobs.edit', $job) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('accounting.jobs.destroy', $job) }}" method="POST"
                                    style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                            No jobs found. <a href="{{ route('accounting.jobs.create') }}"
                                style="color: var(--primary-color);">Create your first job</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($jobs->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $jobs->links() }}
            </div>
        @endif
    </x-card>
@endsection
