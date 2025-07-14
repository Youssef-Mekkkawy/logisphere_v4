@extends('layouts.app')

@section('title', 'Create Job')
@section('page_title', 'Create New Employee Job')
@section('breadcrumb', 'Home > Accounting > Employee Jobs > Create')

@section('content')
    <x-card>
        <form action="{{ route('accounting.jobs.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <x-form-input name="job_id" label="Job ID" required placeholder="AUTO-GENERATED" readonly
                    value="{{ 'JOB-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" />

                <x-form-input name="title" label="Job Title" required
                    placeholder="e.g., Shipment Processing, Document Review" />

                <x-form-select name="employee_id" label="Assign to Employee" :options="$employees->pluck('name', 'id')" required
                    placeholder="Select employee" />

                <x-form-select name="priority" label="Priority Level" :options="[
                    'High' => 'High Priority',
                    'Medium' => 'Medium Priority',
                    'Low' => 'Low Priority',
                ]" value="Medium" required />
            </div>

            <div class="form-grid">
                <x-form-input name="start_date" label="Start Date" type="date" value="{{ date('Y-m-d') }}" required />

                <x-form-input name="due_date" label="Due Date" type="date" required />

                <x-form-input name="estimated_hours" label="Estimated Hours" type="number" step="0.5" min="0"
                    placeholder="e.g., 8.0" />

                <x-form-select name="status" label="Initial Status" :options="[
                    'Active' => 'Active',
                    'On Hold' => 'On Hold',
                ]" value="Active" required />
            </div>

            <x-form-textarea name="description" label="Job Description" required
                placeholder="Detailed description of the job requirements and tasks..." />

            <x-form-textarea name="requirements" label="Special Requirements"
                placeholder="Any special skills, tools, or requirements needed..." />

            <x-form-textarea name="notes" label="Internal Notes"
                placeholder="Additional notes for internal reference..." />

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Create Job</button>
                <a href="{{ route('accounting.jobs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
