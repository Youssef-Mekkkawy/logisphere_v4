@extends('layouts.app')

@section('title', 'Edit Shipping Agency')
@section('page_title', 'Edit Shipping Agency')
@section('breadcrumb', 'Home > Submenu > Shipping Agencies > Edit')

@section('content')
    <x-card>
        <form action="{{ route('submenu.shipping-agencies.update', $agency) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <x-form-input name="code" label="Agency Code" value="{{ $agency->code }}" required
                    placeholder="e.g., MSK, COSCO, EVER" />

                <x-form-input name="name" label="Agency Name" value="{{ $agency->name }}" required
                    placeholder="e.g., Maersk Line, COSCO Shipping" />

                <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" value="{{ $agency->country_id }}"
                    required />

                <x-form-select name="service_type" label="Service Type" :options="[
                    'Ocean Freight' => 'Ocean Freight',
                    'Air Freight' => 'Air Freight',
                    'Land Transport' => 'Land Transport',
                    'Full Service' => 'Full Service',
                ]"
                    value="{{ $agency->service_type }}" required />
            </div>

            <div class="form-grid">
                <x-form-input name="contact_person" label="Contact Person" value="{{ $agency->contact_person }}"
                    placeholder="Primary contact name" />

                <x-form-input name="email" label="Email Address" type="email" value="{{ $agency->email }}"
                    placeholder="agency@example.com" />

                <x-form-input name="phone" label="Phone Number" value="{{ $agency->phone }}" placeholder="+1234567890" />

                <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ $agency->status }}" required />
            </div>

            <x-form-textarea name="address" label="Address" value="{{ $agency->address }}"
                placeholder="Enter agency address" />

            <x-form-textarea name="services_offered" label="Services Offered" value="{{ $agency->services_offered }}"
                placeholder="Describe the services this agency provides..." />

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Update Shipping Agency</button>
                <a href="{{ route('submenu.shipping-agencies.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
