@extends('layouts.app')

@section('title', 'Edit Job')
@section('page_title', 'Edit Employee Job')
@section('breadcrumb', 'Home > Accounting > Employee Jobs > Edit')

@section('content')
    <x-card>
        <form action="{{ route('accounting.jobs.update', $job) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <x-form-input name="job_id" label="Job ID" value="{{ $job->job_id }}" readonly />

                <x-form-input name="title" label="Job Title" value="{{ $job->title }}" required
                    placeholder="e.g., Shipment Processing, Document Review" />

                <x-form-select name="employee_id" label="Assign to Employee" :options="$employees->pluck('name', 'id')"
                    value="{{ $job->employee_id }}" required placeholder="Select employee" />

                <x-form-select name="priority" label="Priority Level" :options="[
                    'High' => 'High Priority',
                    'Medium' => 'Medium Priority',
                    'Low' => 'Low Priority',
                ]" value="{{ $job->priority }}"
                    required />
            </div>

            <div class="form-grid">
                <x-form-input name="start_date" label="Start Date" type="date"
                    value="{{ $job->start_date ? $job->start_date->format('Y-m-d') : '' }}" required />

                <x-form-input name="due_date" label="Due Date" type="date"
                    value="{{ $job->due_date ? $job->due_date->format('Y-m-d') : '' }}" required />

                <x-form-input name="estimated_hours" label="Estimated Hours" type="number" step="0.5" min="0"
                    value="{{ $job->estimated_hours }}" placeholder="e.g., 8.0" />

                <x-form-select name="status" label="Job Status" :options="[
                    'Active' => 'Active',
                    'Completed' => 'Completed',
                    'On Hold' => 'On Hold',
                    'Cancelled' => 'Cancelled',
                ]" value="{{ $job->status }}" required />
            </div>

            <div class="form-grid">
                <x-form-input name="actual_hours" label="Actual Hours Spent" type="number" step="0.5" min="0"
                    value="{{ $job->actual_hours }}" placeholder="e.g., 6.5" />

                <x-form-input name="progress_percentage" label="Progress %" type="number" min="0" max="100"
                    value="{{ $job->progress_percentage }}" placeholder="e.g., 75" />

                <x-form-input name="completion_date" label="Completion Date" type="date"
                    value="{{ $job->completion_date ? $job->completion_date->format('Y-m-d') : '' }}" />
            </div>

            <x-form-textarea name="description" label="Job Description" value="{{ $job->description }}" required
                placeholder="Detailed description of the job requirements and tasks..." />

            <x-form-textarea name="requirements" label="Special Requirements" value="{{ $job->requirements }}"
                placeholder="Any special skills, tools, or requirements needed..." />

            <x-form-textarea name="notes" label="Internal Notes" value="{{ $job->notes }}"
                placeholder="Additional notes for internal reference..." />

            <x-form-textarea name="completion_notes" label="Completion Notes" value="{{ $job->completion_notes }}"
                placeholder="Notes about job completion, issues faced, etc..." />

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Update Job</button>
                <a href="{{ route('accounting.jobs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
