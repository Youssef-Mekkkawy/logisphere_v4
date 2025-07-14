@extends('layouts.app')

@section('title', 'COO Types')
@section('page_title', 'Certificate of Origin Types')
@section('breadcrumb', 'Home > Submenu > COO Types')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">📜 Certificate of Origin Types</h2>
            <p style="color: #64748b;">Manage different types of certificates of origin</p>
        </div>
        <a href="{{ route('submenu.coo-types.create') }}" class="btn btn-primary">+ Add New COO Type</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="COO name, code..." />
            <x-form-select name="issuing_authority" label="Issuing Authority" :options="$authorities->pluck('name', 'name')"
                value="{{ request('issuing_authority') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <x-form-select name="is_mandatory" label="Mandatory" :options="[
                '1' => 'Yes - Mandatory',
                '0' => 'No - Optional',
            ]" value="{{ request('is_mandatory') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.coo-types.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- COO Types Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>COO Code</th>
                    <th>COO Name</th>
                    <th>Issuing Authority</th>
                    <th>Processing Time</th>
                    <th>Cost (USD)</th>
                    <th>Mandatory</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cooTypes as $cooType)
                    <tr>
                        <td><strong>{{ $cooType->code }}</strong></td>
                        <td>{{ $cooType->name }}</td>
                        <td>{{ $cooType->issuing_authority }}</td>
                        <td>{{ $cooType->processing_days }} days</td>
                        <td>${{ number_format($cooType->cost, 2) }}</td>
                        <td>
                            @if ($cooType->is_mandatory)
                                <span class="status-badge status-warning">Mandatory</span>
                            @else
                                <span class="status-badge status-active">Optional</span>
                            @endif
                        </td>
                        <td><span
                                class="status-badge status-{{ strtolower($cooType->status) }}">{{ $cooType->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.coo-types.show', $cooType) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.coo-types.edit', $cooType) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.coo-types.destroy', $cooType) }}" method="POST"
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
                            No COO types found. <a href="{{ route('submenu.coo-types.create') }}"
                                style="color: var(--primary-color);">Create your first COO type</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($cooTypes->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $cooTypes->links() }}
            </div>
        @endif
    </x-card>
@endsection
