@extends('layouts.app')

@section('title', 'Shipping Agencies')
@section('page_title', 'Shipping Agencies Management')
@section('breadcrumb', 'Home > Submenu > Shipping Agencies')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">🏢 Shipping Agencies</h2>
            <p style="color: #64748b;">Manage shipping agency information</p>
        </div>
        <a href="{{ route('submenu.shipping-agencies.create') }}" class="btn btn-primary">+ Add New Agency</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Agency name, code..." />
            <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" value="{{ request('country_id') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <x-form-select name="service_type" label="Service Type" :options="[
                'Ocean Freight' => 'Ocean Freight',
                'Air Freight' => 'Air Freight',
                'Land Transport' => 'Land Transport',
                'Full Service' => 'Full Service',
            ]"
                value="{{ request('service_type') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.shipping-agencies.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Shipping Agencies Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Agency Code</th>
                    <th>Agency Name</th>
                    <th>Country</th>
                    <th>Service Type</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Shipments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agencies as $agency)
                    <tr>
                        <td><strong>{{ $agency->code }}</strong></td>
                        <td>{{ $agency->name }}</td>
                        <td>{{ $agency->country->name ?? 'N/A' }}</td>
                        <td>{{ $agency->service_type }}</td>
                        <td>
                            {{ $agency->contact_person }}<br>
                            <small style="color: #64748b;">{{ $agency->email }}</small>
                        </td>
                        <td><span
                                class="status-badge status-{{ strtolower($agency->status) }}">{{ $agency->status }}</span>
                        </td>
                        <td>{{ $agency->shipments_count ?? 0 }}</td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.shipping-agencies.show', $agency) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.shipping-agencies.edit', $agency) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.shipping-agencies.destroy', $agency) }}" method="POST"
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
                            No shipping agencies found. <a href="{{ route('submenu.shipping-agencies.create') }}"
                                style="color: var(--primary-color);">Create your first agency</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($agencies->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $agencies->links() }}
            </div>
        @endif
    </x-card>
@endsection
