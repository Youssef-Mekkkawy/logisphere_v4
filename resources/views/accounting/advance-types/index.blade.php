@extends('layouts.app')

@section('title', 'Advance Types')
@section('page_title', 'Employee Advance Types')
@section('breadcrumb', 'Home > Accounting > Advance Types')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">💰 Employee Advance Types</h2>
            <p style="color: #64748b;">Manage payroll advance categories and limits</p>
        </div>
        <a href="{{ route('accounting.advance-types.create') }}" class="btn btn-primary">+ Add New Advance Type</a>
    </div>

    <!-- Filters -->
    <x-card title="Filters">
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <x-form-input name="search" label="Search" value="{{ request('search') }}" placeholder="Advance type name..." />
            <x-form-select name="approval_required" label="Approval Required" :options="[
                '1' => 'Yes - Requires Approval',
                '0' => 'No - Auto Approve',
            ]"
                value="{{ request('approval_required') }}" />
            <x-form-select name="status" label="Status" :options="['Active' => 'Active', 'Inactive' => 'Inactive']" value="{{ request('status') }}" />
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('accounting.advance-types.index') }}" class="btn btn-secondary"
                    style="margin-left: 0.5rem;">Clear</a>
            </div>
        </form>
    </x-card>

    <!-- Advance Types Table -->
    <x-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Type Code</th>
                    <th>Advance Type Name</th>
                    <th>Max Amount (USD)</th>
                    <th>Max % of Salary</th>
                    <th>Repayment Terms</th>
                    <th>Approval Required</th>
                    <th>Status</th>
                    <th>Usage Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advanceTypes as $type)
                    <tr>
                        <td><strong>{{ $type->code }}</strong></td>
                        <td>{{ $type->name }}</td>
                        <td>${{ number_format($type->max_amount, 0) }}</td>
                        <td>{{ $type->max_percentage_of_salary ?? 'N/A' }}%</td>
                        <td>{{ $type->max_repayment_months }} months</td>
                        <td>
                            @if ($type->approval_required)
                                <span class="status-badge status-warning">Required</span>
                            @else
                                <span class="status-badge status-success">Not Required</span>
                            @endif
                        </td>
                        <td><span class="status-badge status-{{ strtolower($type->status) }}">{{ $type->status }}</span>
                        </td>
                        <td>{{ $type->usage_count ?? 0 }}</td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('accounting.advance-types.show', $type) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            <a href="{{ route('accounting.advance-types.edit', $type) }}" class="btn btn-success"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('accounting.advance-types.destroy', $type) }}" method="POST"
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
                        <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                            No advance types found. <a href="{{ route('accounting.advance-types.create') }}"
                                style="color: var(--primary-color);">Create your first advance type</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($advanceTypes->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $advanceTypes->links() }}
            </div>
        @endif
    </x-card>
@endsection
