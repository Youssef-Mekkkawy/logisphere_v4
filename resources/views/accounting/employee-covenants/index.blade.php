@extends('layouts.app')

@section('title', 'Employee Covenants')
@section('page_title', 'Employee Covenants (Equipment)')
@section('breadcrumb', 'Home > Accounting > Employee Covenants')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">🛡️ Employee Covenants</h2>
            <p style="color: #64748b;">Manage equipment and assets given to employees</p>
        </div>
        <a href="{{ route('accounting.employee-covenants.create') }}" class="btn btn-primary">+ Add New Covenant</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}"
                placeholder="Employee name, equipment..." />
            <x-form-select name="employee_id" label="Employee" :options="$employees->pluck('name', 'id')" value="{{ request('employee_id') }}" />
            <x-form-select name="equipment_type" label="Equipment Type" :options="[
                'Laptop' => 'Laptop',
                'Phone' => 'Phone',
                'Vehicle' => 'Vehicle',
                'Tools' => 'Tools',
                'Safety Equipment' => 'Safety Equipment',
                'Other' => 'Other',
            ]"
                value="{{ request('equipment_type') }}" />
            <x-form-select name="status" label="Status" :options="[
                'Active' => 'Active',
                'Returned' => 'Returned',
                'Lost' => 'Lost',
                'Damaged' => 'Damaged',
            ]" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('accounting.employee-covenants.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Covenants Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Covenant ID</th>
                    <th>Employee</th>
                    <th>Equipment Type</th>
                    <th>Item Description</th>
                    <th>Serial Number</th>
                    <th>Issue Date</th>
                    <th>Value (USD)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($covenants as $covenant)
                    <tr>
                        <td><strong>{{ $covenant->covenant_id }}</strong></td>
                        <td>{{ $covenant->employee->name ?? 'N/A' }}</td>
                        <td>{{ $covenant->equipment_type }}</td>
                        <td>{{ $covenant->item_description }}</td>
                        <td>{{ $covenant->serial_number ?? 'N/A' }}</td>
                        <td>{{ $covenant->issue_date ? $covenant->issue_date->format('M d, Y') : 'N/A' }}</td>
                        <td>${{ number_format($covenant->item_value, 2) }}</td>
                        <td><span
                                class="status-badge status-{{ strtolower($covenant->status) }}">{{ $covenant->status }}</span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('accounting.employee-covenants.show', $covenant) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('accounting.employee-covenants.edit', $covenant) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('accounting.employee-covenants.destroy', $covenant) }}"
                                    method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                            No covenants found. <a href="{{ route('accounting.employee-covenants.create') }}"
                                style="color: var(--primary-color);">Create your first covenant</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($covenants->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $covenants->links() }}
            </div>
        @endif
    </x-card>

    <!-- Summary Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">{{ $totalActive ?? 0 }}</div>
            <div style="font-size: 0.875rem; color: #64748b;">Active Covenants</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--success-color);">
                ${{ number_format($totalValue ?? 0, 0) }}</div>
            <div style="font-size: 0.875rem; color: #64748b;">Total Equipment Value</div>
        </div>
        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--warning-color);">{{ $pendingReturns ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: #64748b;">Pending Returns</div>
        </div>
    </div>
@endsection
