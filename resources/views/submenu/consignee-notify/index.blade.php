@extends('layouts.app')

@section('title', 'Consignee & Notify Parties')
@section('page_title', 'Consignee & Notify Parties')
@section('breadcrumb', 'Home > Submenu > Consignee & Notify')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">👥 Consignee & Notify Parties</h2>
            <p style="color: #64748b;">Manage delivery parties and notification contacts</p>
        </div>
        <a href="{{ route('submenu.consignee-notify.create') }}" class="btn btn-primary">+ Add New Party</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Party name, email..." />
            <x-form-select name="party_type" label="Party Type" :options="[
                'Consignee' => 'Consignee',
                'Notify Party' => 'Notify Party',
                'Both' => 'Both',
            ]" value="{{ request('party_type') }}" />
            <x-form-select name="country_id" label="Country" :options="$countries->pluck('name', 'id')" value="{{ request('country_id') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.consignee-notify.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Parties Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Party Code</th>
                    <th>Party Name</th>
                    <th>Type</th>
                    <th>Contact Person</th>
                    <th>Email & Phone</th>
                    <th>Country</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parties as $party)
                    <tr>
                        <td><strong>{{ $party->code }}</strong></td>
                        <td>{{ $party->name }}</td>
                        <td><span
                                class="status-badge status-{{ strtolower(str_replace(' ', '', $party->party_type)) }}">{{ $party->party_type }}</span>
                        </td>
                        <td>{{ $party->contact_person }}</td>
                        <td>
                            {{ $party->email }}<br>
                            <small style="color: #64748b;">{{ $party->phone }}</small>
                        </td>
                        <td>{{ $party->country->name ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ strtolower($party->status) }}">{{ $party->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.consignee-notify.show', $party) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.consignee-notify.edit', $party) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.consignee-notify.destroy', $party) }}" method="POST"
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
                            No parties found. <a href="{{ route('submenu.consignee-notify.create') }}"
                                style="color: var(--primary-color);">Create your first party</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($parties->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $parties->links() }}
            </div>
        @endif
    </x-card>
@endsection
