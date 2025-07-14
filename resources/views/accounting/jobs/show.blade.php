@extends('layouts.app')

@section('title', 'Job Details')
@section('page_title', $job->title)
@section('breadcrumb', 'Home > Accounting > Employee Jobs > ' . $job->job_id)

@section('content')
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Job Information -->
        <x-card title="Job Information">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Job ID:</strong><br>
                    <span style="color: #64748b;">{{ $job->job_id }}</span>
                </div>
                <div>
                    <strong>Job Title:</strong><br>
                    <span style="color: #64748b;">{{ $job->title }}</span>
                </div>
                <div>
                    <strong>Assigned Employee:</strong><br>
                    <span style="color: #64748b;">
                        @if ($job->employee)
                            <a href="{{ route('employees.show', $job->employee) }}"
                                style="color: var(--primary-color);">{{ $job->employee->name }}</a>
                        @else
                            Unassigned
                        @endif
                    </span>
                </div>
                <div>
                    <strong>Priority:</strong><br>
                    <span class="status-badge status-{{ strtolower($job->priority) }}">{{ $job->priority }}</span>
                </div>
                <div>
                    <strong>Status:</strong><br>
                    <span
                        class="status-badge status-{{ strtolower(str_replace(' ', '', $job->status)) }}">{{ $job->status }}</span>
                </div>
                <div>
                    <strong>Progress:</strong><br>
                    <div
                        style="background: #f3f4f6; border-radius: 0.375rem; overflow: hidden; height: 8px; margin-top: 0.25rem;">
                        <div
                            style="background: var(--primary-color); height: 100%; width: {{ $job->progress_percentage ?? 0 }}%;">
                        </div>
                    </div>
                    <span style="color: #64748b; font-size: 0.875rem;">{{ $job->progress_percentage ?? 0 }}%</span>
                </div>
            </div>

            <div
                style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Start Date:</strong><br>
                    <span
                        style="color: #64748b;">{{ $job->start_date ? $job->start_date->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div>
                    <strong>Due Date:</strong><br>
                    <span style="color: #64748b;">{{ $job->due_date ? $job->due_date->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div>
                    <strong>Completion Date:</strong><br>
                    <span
                        style="color: #64748b;">{{ $job->completion_date ? $job->completion_date->format('M d, Y') : 'Not completed' }}</span>
                </div>
                <div>
                    <strong>Estimated Hours:</strong><br>
                    <span style="color: #64748b;">{{ $job->estimated_hours ?? 'N/A' }} hours</span>
                </div>
                <div>
                    <strong>Actual Hours:</strong><br>
                    <span style="color: #64748b;">{{ $job->actual_hours ?? 'N/A' }} hours</span>
                </div>
            </div>

            @if ($job->description)
                <div style="margin-top: 1.5rem;">
                    <strong>Description:</strong><br>
                    <p style="color: #64748b; margin-top: 0.5rem;">{{ $job->description }}</p>
                </div>
            @endif

            @if ($job->requirements)
                <div style="margin-top: 1.5rem;">
                    <strong>Requirements:</strong><br>
                    <p style="color: #64748b; margin-top: 0.5rem;">{{ $job->requirements }}</p>
                </div>
            @endif

            @if ($job->notes)
                <div style="margin-top: 1.5rem;">
                    <strong>Internal Notes:</strong><br>
                    <p style="color: #64748b; margin-top: 0.5rem;">{{ $job->notes }}</p>
                </div>
            @endif

            @if ($job->completion_notes)
                <div style="margin-top: 1.5rem;">
                    <strong>Completion Notes:</strong><br>
                    <p style="color: #64748b; margin-top: 0.5rem;">{{ $job->completion_notes }}</p>
                </div>
            @endif
        </x-card>

        <!-- Job Statistics -->
        <x-card title="Job Statistics">
            <div style="space-y: 1rem;">
                <div
                    style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                    <span>Days Since Created</span>
                    <span style="font-weight: 600;">{{ $job->created_at->diffInDays() }}</span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                    <span>Days Until Due</span>
                    <span style="font-weight: 600;">
                        @if ($job->due_date)
                            {{ now()->diffInDays($job->due_date, false) }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                    <span>Time Variance</span>
                    <span style="font-weight: 600;">
                        @if ($job->estimated_hours && $job->actual_hours)
                            {{ round((($job->actual_hours - $job->estimated_hours) / $job->estimated_hours) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                    <span>Last Updated</span>
                    <span style="font-weight: 600;">{{ $job->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Actions -->
    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
        <a href="{{ route('accounting.jobs.edit', $job) }}" class="btn btn-primary">Edit Job</a>
        @if ($job->employee)
            <a href="{{ route('employees.show', $job->employee) }}" class="btn btn-success">View Employee</a>
        @endif
        <a href="{{ route('accounting.jobs.index') }}" class="btn btn-secondary">Back to Jobs</a>
        @if (auth()->user()->isAdmin())
            <form action="{{ route('accounting.jobs.destroy', $job) }}" method="POST" style="display: inline;"
                onsubmit="return confirm('Are you sure you want to delete this job?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Job</button>
            </form>
        @endif
    </div>
@endsection
