@extends('layouts.app')

@section('title', 'Shipment Types')
@section('page_title', 'Shipment Types & Sub Types')
@section('breadcrumb', 'Home > Submenu > Shipment Types')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">📋 Shipment Types & Sub Types</h2>
            <p style="color: #64748b;">Manage shipment classification and categories</p>
        </div>
        <a href="{{ route('submenu.shipment-types.create') }}" class="btn btn-primary">+ Add New Type</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Type name, code..." />
            <x-form-select name="parent_id" label="Parent Type" :options="$parentTypes->pluck('name', 'id')" value="{{ request('parent_id') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <x-form-select name="mode" label="Transport Mode" :options="[
                'Ocean' => 'Ocean Freight',
                'Air' => 'Air Freight',
                'Land' => 'Land Transport',
                'Multimodal' => 'Multimodal',
            ]" value="{{ request('mode') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.shipment-types.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Shipment Types Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Type Code</th>
                    <th>Type Name</th>
                    <th>Parent Type</th>
                    <th>Transport Mode</th>
                    <th>Default Transit Days</th>
                    <th>Status</th>
                    <th>Usage Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipmentTypes as $type)
                    <tr>
                        <td><strong>{{ $type->code }}</strong></td>
                        <td>
                            {{ $type->name }}
                            @if ($type->children_count > 0)
                                <br><small style="color: #64748b;">{{ $type->children_count }} sub-types</small>
                            @endif
                        </td>
                        <td>{{ $type->parent->name ?? 'Main Type' }}</td>
                        <td>{{ $type->transport_mode }}</td>
                        <td>{{ $type->default_transit_days ?? 'N/A' }} days</td>
                        <td><span class="status-badge status-{{ strtolower($type->status) }}">{{ $type->status }}</span>
                        </td>
                        <td>{{ $type->usage_count ?? 0 }}</td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.shipment-types.show', $type) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.shipment-types.edit', $type) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.shipment-types.destroy', $type) }}" method="POST"
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
                            No shipment types found. <a href="{{ route('submenu.shipment-types.create') }}"
                                style="color: var(--primary-color);">Create your first type</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($shipmentTypes->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $shipmentTypes->links() }}
            </div>
        @endif
    </x-card>
@endsection
