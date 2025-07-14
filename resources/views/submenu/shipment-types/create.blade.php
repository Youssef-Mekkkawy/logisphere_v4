@extends('layouts.app')

@section('title', 'Create Shipment Type')
@section('page_title', 'Create New Shipment Type')
@section('breadcrumb', 'Home > Submenu > Shipment Types > Create')

@section('content')
    <x-card>
        <form action="{{ route('submenu.shipment-types.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <x-form-input name="code" label="Type Code" required placeholder="e.g., FCL, LCL, BBK" />

                <x-form-input name="name" label="Type Name" required placeholder="e.g., Full Container Load" />

                <x-form-select name="parent_id" label="Parent Type" :options="$parentTypes->pluck('name', 'id')"
                    placeholder="Select parent type (optional)" />

                <x-form-select name="transport_mode" label="Transport Mode" :options="[
                    'Ocean' => 'Ocean Freight',
                    'Air' => 'Air Freight',
                    'Land' => 'Land Transport',
                    'Multimodal' => 'Multimodal',
                ]" required />
            </div>

            <div class="form-grid">
                <x-form-input name="default_transit_days" label="Default Transit Days" type="number" min="1"
                    placeholder="e.g., 21" />

                <x-form-input name="base_cost" label="Base Cost (USD)" type="number" step="0.01" min="0"
                    placeholder="0.00" />

                <x-form-select name="requires_customs" label="Requires Customs Clearance" :options="[
                    '1' => 'Yes',
                    '0' => 'No',
                ]" value="1"
                    required />

                <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="Active" required />
            </div>

            <x-form-textarea name="description" label="Description"
                placeholder="Describe this shipment type and its characteristics..." />

            <x-form-textarea name="requirements" label="Special Requirements"
                placeholder="List any special requirements or documentation needed..." />

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Create Shipment Type</button>
                <a href="{{ route('submenu.shipment-types.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
