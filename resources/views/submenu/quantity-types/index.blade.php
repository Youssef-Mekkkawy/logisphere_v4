@extends('layouts.app')

@section('title', 'Quantity Types')
@section('page_title', 'Quantity Types Management')
@section('breadcrumb', 'Home > Submenu > Quantity Types')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">📊 Quantity Types</h2>
            <p style="color: #64748b;">Manage cargo quantity classifications and units</p>
        </div>
        <a href="{{ route('submenu.quantity-types.create') ?? '' }}" class="btn btn-primary">+ Add New Quantity Type</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Type name, unit..." />
            <x-form-select name="measurement_type" label="Measurement Type" :options="[
                'Weight' => 'Weight',
                'Volume' => 'Volume',
                'Count' => 'Count/Pieces',
                'Area' => 'Area',
                'Length' => 'Length',
            ]"
                value="{{ request('measurement_type') }}" />
            <x-form-select name="cargo_category" label="Cargo Category" :options="[
                'General' => 'General Cargo',
                'Bulk' => 'Bulk Cargo',
                'Container' => 'Container Cargo',
                'Liquid' => 'Liquid Cargo',
                'Hazardous' => 'Hazardous Materials',
            ]"
                value="{{ request('cargo_category') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submenu.quantity-types.index') ?? '' }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Quantity Types Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Quantity Type</th>
                    <th>Unit</th>
                    <th>Measurement Type</th>
                    <th>Cargo Category</th>
                    <th>Conversion Factor</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quantityTypes as $type)
                    <tr>
                        <td><strong>{{ $type->code }}</strong></td>
                        <td>{{ $type->name }}</td>
                        <td>{{ $type->unit }}</td>
                        <td>{{ $type->measurement_type }}</td>
                        <td>{{ $type->cargo_category }}</td>
                        <td>{{ $type->conversion_factor ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ strtolower($type->status) }}">{{ $type->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('submenu.quantity-types.show' ?? '', $type) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('submenu.quantity-types.edit' ?? '', $type) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('submenu.quantity-types.destroy' ?? '', $type) }}" method="POST"
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
                            No quantity types found. <a href="{{ route('submenu.quantity-types.create') ?? '' }}"
                                style="color: var(--primary-color);">Create your first quantity type</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($quantityTypes->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $quantityTypes->links() }}
            </div>
        @endif
    </x-card>
@endsection
