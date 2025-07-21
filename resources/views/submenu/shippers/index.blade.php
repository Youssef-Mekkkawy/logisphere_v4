@extends('layouts.app')

@section('title', 'Shippers')
@section('page_title', 'Shippers Management')
@section('breadcrumb', 'Home > Submenu > Shippers')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">🏢 Shippers Management</h2>
            <p style="color: #64748b;">Manage shipping companies and cargo shippers</p>
        </div>
        <a href="{{ route('submenu.shippers.create') ?? '' }}" class="btn btn-primary">+ Add New Shipper</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Shipper name, code..." />
            <x-form-select name="shipper_type" label="Shipper Type" :options="[
                'Manufacturer' => 'Manufacturer',
                'Exporter' => 'Exporter',
                'Trading Company' => 'Trading Company',
                'Freight Forwarder' => 'Freight Forwarder',
                'Agent' => 'Agent',
            ]"
                value="{{ request('shipper_type') }}" />
            <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" value="{{ request('country_id') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.shippers.index') ?? '' }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Shippers Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Shipper Code</th>
                    <th>Shipper Name</th>
                    <th>Type</th>
                    <th>Contact</th>
                    <th>Country</th>
                    <th>Total Shipments</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shippers as $shipper)
                    <tr>
                        <td><strong>{{ $shipper->code }}</strong></td>
                        <td>{{ $shipper->name }}</td>
                        <td>{{ $shipper->shipper_type }}</td>
                        <td>
                            {{ $shipper->contact_person }}<br>
                            <small style="color: #64748b;">{{ $shipper->email }}</small>
                        </td>
                        <td>{{ $shipper->country->name ?? 'N/A' }}</td>
                        <td>{{ $shipper->shipments_count ?? 0 }}</td>
                        <td><span
                                class="status-badge status-{{ strtolower($shipper->status) }}">{{ $shipper->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.shippers.show' ?? '', $shipper) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.shippers.edit' ?? '', $shipper) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.shippers.destroy' ?? '', $shipper) }}" method="POST"
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
                            No shippers found. <a href="{{ route('submenu.shippers.create') ?? '' }}"
                                style="color: var(--primary-color);">Create your first shipper</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($shippers->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $shippers->links() }}
            </div>
        @endif
    </x-card>
@endsection
