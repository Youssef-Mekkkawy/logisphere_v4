@extends('layouts.app')

@section('title', 'Create Shipping Agency')
@section('page_title', 'Create New Shipping Agency')
@section('breadcrumb', 'Home > Submenu > Shipping Agencies > Create')

@section('content')
    <x-card>
        <form action="{{ route('submenu.shipping-agencies.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <x-form-input name="code" label="Agency Code" required placeholder="e.g., MSK, COSCO, EVER" />

                <x-form-input name="name" label="Agency Name" required placeholder="e.g., Maersk Line, COSCO Shipping" />

                <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" required />

                <x-form-select name="service_type" label="Service Type" :options="[
                    'Ocean Freight' => 'Ocean Freight',
                    'Air Freight' => 'Air Freight',
                    'Land Transport' => 'Land Transport',
                    'Full Service' => 'Full Service',
                ]" required />
            </div>

            <div class="form-grid">
                <x-form-input name="contact_person" label="Contact Person" placeholder="Primary contact name" />

                <x-form-input name="email" label="Email Address" type="email" placeholder="agency@example.com" />

                <x-form-input name="phone" label="Phone Number" placeholder="+1234567890" />

                <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="Active" required />
            </div>

            <x-form-textarea name="address" label="Address" placeholder="Enter agency address" />

            <x-form-textarea name="services_offered" label="Services Offered"
                placeholder="Describe the services this agency provides..." />

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Create Shipping Agency</button>
                <a href="{{ route('submenu.shipping-agencies.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
