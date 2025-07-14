@extends('layouts.app')

@section('title', 'Inspection Types')
@section('page_title', 'Inspection Types Management')
@section('breadcrumb', 'Home > Submenu > Inspection Types')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">🔍 Inspection Types</h2>
            <p style="color: #64748b;">Manage customs inspection categories and requirements</p>
        </div>
        <a href="{{ route('submenu.inspection-types.create') }}" class="btn btn-primary">+ Add New Inspection Type</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}"
                placeholder="Inspection name, code..." />
            <x-form-select name="inspection_authority" label="Authority" :options="$authorities->pluck('name', 'name')"
                value="{{ request('inspection_authority') }}" />
            <x-form-select name="is_mandatory" label="Mandatory" :options="[
                '1' => 'Yes - Mandatory',
                '0' => 'No - Optional',
            ]" value="{{ request('is_mandatory') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.inspection-types.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Inspection Types Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Inspection Type</th>
                    <th>Authority</th>
                    <th>Duration</th>
                    <th>Cost (USD)</th>
                    <th>Mandatory</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inspectionTypes as $inspection)
                    <tr>
                        <td><strong>{{ $inspection->code }}</strong></td>
                        <td>{{ $inspection->name }}</td>
                        <td>{{ $inspection->inspection_authority }}</td>
                        <td>{{ $inspection->duration_hours }} hours</td>
                        <td>${{ number_format($inspection->cost, 2) }}</td>
                        <td>
                            @if ($inspection->is_mandatory)
                                <span class="status-badge status-warning">Mandatory</span>
                            @else
                                <span class="status-badge status-active">Optional</span>
                            @endif
                        </td>
                        <td><span
                                class="status-badge status-{{ strtolower($inspection->status) }}">{{ $inspection->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.inspection-types.show', $inspection) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.inspection-types.edit', $inspection) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.inspection-types.destroy', $inspection) }}" method="POST"
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
                            No inspection types found. <a href="{{ route('submenu.inspection-types.create') }}"
                                style="color: var(--primary-color);">Create your first inspection type</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($inspectionTypes->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $inspectionTypes->links() }}
            </div>
        @endif
    </x-card>
@endsection
