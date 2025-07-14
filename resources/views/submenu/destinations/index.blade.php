@extends('layouts.app')

@section('title', 'Destinations')
@section('page_title', 'Destinations Management')
@section('breadcrumb', 'Home > Submenu > Destinations')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">📍 Destinations</h2>
            <p style="color: #64748b;">Manage delivery destinations and locations</p>
        </div>
        <a href="{{ route('submenu.destinations.create') }}" class="btn btn-primary">+ Add New Destination</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}"
                placeholder="Destination name, city..." />
            <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" value="{{ request('country_id') }}" />
            <x-form-select name="destination_type" label="Type" :options="[
                'Warehouse' => 'Warehouse',
                'Factory' => 'Factory',
                'Port' => 'Port',
                'Airport' => 'Airport',
                'Distribution Center' => 'Distribution Center',
                'Customer Location' => 'Customer Location',
            ]"
                value="{{ request('destination_type') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.destinations.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Destinations Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Destination Name</th>
                    <th>Type</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($destinations as $destination)
                    <tr>
                        <td><strong>{{ $destination->code }}</strong></td>
                        <td>{{ $destination->name }}</td>
                        <td>{{ $destination->destination_type }}</td>
                        <td>{{ $destination->city }}</td>
                        <td>{{ $destination->country->name ?? 'N/A' }}</td>
                        <td>
                            {{ $destination->contact_person }}<br>
                            <small style="color: #64748b;">{{ $destination->phone }}</small>
                        </td>
                        <td><span
                                class="status-badge status-{{ strtolower($destination->status) }}">{{ $destination->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.destinations.show', $destination) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.destinations.edit', $destination) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.destinations.destroy', $destination) }}" method="POST"
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
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">
                            No destinations found. <a href="{{ route('submenu.destinations.create') }}"
                                style="color: var(--primary-color);">Create your first destination</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($destinations->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $destinations->links() }}
            </div>
        @endif
    </x-card>
@endsection
