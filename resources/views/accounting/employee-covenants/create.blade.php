@extends('layouts.app')

@section('title', 'Create Employee Covenant')
@section('page_title', 'Create New Employee Covenant')
@section('breadcrumb', 'Home > Accounting > Employee Covenants > Create')

@section('content')
    <x-card>
        <form action="{{ route('accounting.employee-covenants.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <x-form-input name="covenant_id" label="Covenant ID" required placeholder="AUTO-GENERATED" readonly
                    value="{{ 'COV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" />

                <x-form-select name="employee_id" label="Employee" :options="$employees->pluck('name', 'id')" required placeholder="Select employee" />

                <x-form-select name="equipment_type" label="Equipment Type" :options="[
                    'Laptop' => 'Laptop Computer',
                    'Phone' => 'Mobile Phone',
                    'Vehicle' => 'Company Vehicle',
                    'Tools' => 'Tools & Equipment',
                    'Safety Equipment' => 'Safety Equipment',
                    'Furniture' => 'Office Furniture',
                    'Access Card' => 'Access Card/Key',
                    'Other' => 'Other Equipment',
                ]" required />

                <x-form-input name="issue_date" label="Issue Date" type="date" value="{{ date('Y-m-d') }}" required />
            </div>

            <div class="form-grid">
                <x-form-input name="item_description" label="Item Description" required
                    placeholder="e.g., Dell Laptop XPS 15, iPhone 14 Pro" />

                <x-form-input name="brand_model" label="Brand & Model" placeholder="e.g., Dell XPS 15, Apple iPhone 14" />

                <x-form-input name="serial_number" label="Serial Number" placeholder="Device serial number" />

                <x-form-input name="item_value" label="Item Value (USD)" type="number" step="0.01" min="0"
                    required placeholder="0.00" />
            </div>

            <div class="form-grid">
                <x-form-select name="condition" label="Item Condition" :options="[
                    'New' => 'New',
                    'Excellent' => 'Excellent',
                    'Good' => 'Good',
                    'Fair' => 'Fair',
                    'Poor' => 'Poor',
                ]" value="New" required />

                <x-form-input name="warranty_expiry" label="Warranty Expiry" type="date" />

                <x-form-select name="status" label="Initial Status" :options="[
                    'Active' => 'Active',
                ]" value="Active" required />
            </div>

            <x-form-textarea name="notes" label="Additional Notes"
                placeholder="Any additional information about this equipment assignment..." />

            <x-form-textarea name="conditions_of_use" label="Conditions of Use"
                placeholder="Terms and conditions for using this equipment..." />

            <div class="form-group">
                <label class="form-label">Attachment (Receipt/Photo)</label>
                <input type="file" name="attachment" class="form-input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                <small style="color: #64748b;">Optional: Upload receipt, photo, or related document</small>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Create Covenant</button>
                <a href="{{ route('accounting.employee-covenants.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
