@extends('layouts.app')

@section('title', 'Create COO Type')
@section('page_title', 'Create New COO Type')
@section('breadcrumb', 'Home > Submenu > COO Types > Create')

@section('content')
    <x-card>
        <form action="{{ route('submenu.coo-types.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <x-form-input name="code" label="COO Code" required placeholder="e.g., COO-COMM, COO-PREF" />

                <x-form-input name="name" label="COO Name" required placeholder="e.g., Commercial Certificate of Origin" />

                <x-form-input name="issuing_authority" label="Issuing Authority" required
                    placeholder="e.g., Chamber of Commerce" />

                <x-form-select name="is_mandatory" label="Is Mandatory" :options="[
                    '1' => 'Yes - Mandatory',
                    '0' => 'No - Optional',
                ]" required />
            </div>

            <div class="form-grid">
                <x-form-input name="processing_days" label="Processing Time (Days)" type="number" min="1" required
                    placeholder="e.g., 3" />

                <x-form-input name="cost" label="Cost (USD)" type="number" step="0.01" min="0" required
                    placeholder="0.00" />

                <x-form-select name="validity_months" label="Validity Period" :options="[
                    '1' => '1 Month',
                    '3' => '3 Months',
                    '6' => '6 Months',
                    '12' => '1 Year',
                    '24' => '2 Years',
                    '0' => 'No Expiry',
                ]" value="12" required />

                <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="Active" required />
            </div>

            <x-form-textarea name="description" label="Description"
                placeholder="Describe this COO type and when it's used..." />

            <x-form-textarea name="required_documents" label="Required Documents"
                placeholder="List the documents required for this COO type..." />

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Create COO Type</button>
                <a href="{{ route('submenu.coo-types.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
