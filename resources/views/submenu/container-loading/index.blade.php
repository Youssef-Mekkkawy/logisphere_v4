@extends('layouts.app')

@section('title', 'Container Loading Points')
@section('page_title', 'Load Containers From')
@section('breadcrumb', 'Home > Submenu > Container Loading')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">📦 Container Loading Points</h2>
            <p style="color: #64748b;">Manage container loading locations and facilities</p>
        </div>
        <a href="{{ route('submenu.container-loading.create') }}" class="btn btn-primary">+ Add New Loading Point</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Loading point name..." />
            <x-form-select name="facility_type" label="Facility Type" :options="[
                'Port Terminal' => 'Port Terminal',
                'Container Yard' => 'Container Yard',
                'Warehouse' => 'Warehouse',
                'Factory' => 'Factory',
                'Inland Depot' => 'Inland Depot',
            ]"
                value="{{ request('facility_type') }}" />
            <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" value="{{ request('country_id') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.container-loading.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Container Loading Points Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Loading Point Name</th>
                    <th>Facility Type</th>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Operating Hours</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loadingPoints as $point)
                    <tr>
                        <td><strong>{{ $point->code }}</strong></td>
                        <td>{{ $point->name }}</td>
                        <td>{{ $point->facility_type }}</td>
                        <td>
                            {{ $point->city }}, {{ $point->country->name ?? 'N/A' }}<br>
                            <small style="color: #64748b;">{{ $point->address }}</small>
                        </td>
                        <td>{{ $point->capacity }} TEU</td>
                        <td>{{ $point->operating_hours }}</td>
                        <td><span class="status-badge status-{{ strtolower($point->status) }}">{{ $point->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.container-loading.show', $point) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.container-loading.edit', $point) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.container-loading.destroy', $point) }}" method="POST"
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
                            No loading points found. <a href="{{ route('submenu.container-loading.create') }}"
                                style="color: var(--primary-color);">Create your first loading point</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($loadingPoints->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $loadingPoints->links() }}
            </div>
        @endif
    </x-card>
@endsection
