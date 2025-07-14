@extends('layouts.app')

@section('title', isset($port) ? 'Edit Port' : 'Create Port')
@section('page_title', isset($port) ? 'Edit Port' : 'Create New Port')
@section('breadcrumb', 'Home > Submenu > Ports > ' . (isset($port) ? 'Edit' : 'Create'))

@section('content')
    <x-card>
        <form action="{{ isset($port) ? route('submenu.ports.update', $port) : route('submenu.ports.store') }}"
            method="POST">
            @csrf
            @if (isset($port))
                @method('PUT')
            @endif

            <div class="form-grid">
                <x-form-input name="code" label="Port Code" value="{{ $port->code ?? '' }}" required
                    placeholder="e.g., USNYC, AEDXB" />

                <x-form-input name="name" label="Port Name" value="{{ $port->name ?? '' }}" required
                    placeholder="e.g., New York, Dubai" />

                <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" value="{{ $port->country_id ?? '' }}"
                    required />

                <x-form-select name="type" label="Port Type" :options="[
                    'Seaport' => 'Seaport',
                    'Airport' => 'Airport',
                    'Dry Port' => 'Dry Port',
                    'Container Terminal' => 'Container Terminal',
                ]" value="{{ $port->type ?? '' }}"
                    required />

                <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ $port->status ?? 'Active' }}"
                    required />
            </div>

            <div class="form-grid">
                <x-form-input name="city" label="City" value="{{ $port->city ?? '' }}" />

                <x-form-input name="state" label="State/Region" value="{{ $port->state ?? '' }}" />

                <x-form-input name="latitude" label="Latitude" type="number" step="any"
                    value="{{ $port->latitude ?? '' }}" placeholder="e.g., 40.7128" />

                <x-form-input name="longitude" label="Longitude" type="number" step="any"
                    value="{{ $port->longitude ?? '' }}" placeholder="e.g., -74.0060" />
            </div>

            <x-form-textarea name="description" label="Description" value="{{ $port->description ?? '' }}"
                placeholder="Additional information about this port..." />

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">
                    {{ isset($port) ? 'Update Port' : 'Create Port' }}
                </button>
                <a href="{{ route('submenu.ports.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
