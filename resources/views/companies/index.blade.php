@extends('layouts.app')

@section('title', 'Companies - LogiFlow')
@section('page-title', 'Companies')

@section('content')
    <div class="page-header">
        <h3>Company Management</h3>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">Add New Company</a>
    </div>

    <div class="filters">
        <form method="GET" action="{{ route('companies.index') }}" class="filter-form">
            <input type="text" name="search" placeholder="Search companies..." value="{{ request('search') }}"
                class="search-input">
            <select name="type" class="filter-select">
                <option value="">All Types</option>
                <option value="Client" {{ request('type') === 'Client' ? 'selected' : '' }}>Clients</option>
                <option value="Supplier" {{ request('type') === 'Supplier' ? 'selected' : '' }}>Suppliers</option>
                <option value="Both" {{ request('type') === 'Both' ? 'selected' : '' }}>Both</option>
            </select>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Type</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Country</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->type }}</td>
                    <td>{{ $company->contact_person }}</td>
                    <td>{{ $company->email }}</td>
                    <td>{{ $company->phone }}</td>
                    <td>{{ $company->country->name ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($company->status) }}">
                            {{ $company->status }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('companies.show', $company) }}" class="btn btn-sm btn-primary">View</a>
                            @can('update', $company)
                                <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-secondary">Edit</a>
                            @endcan
                            @can('delete', $company)
                                <form method="POST" action="{{ route('companies.destroy', $company) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this company?')">
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No companies found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $companies->withQueryString()->links() }}
    </div>

    @section('styles')
        <style>
            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }

            .filters {
                margin-bottom: 20px;
            }

            .filter-form {
                display: flex;
                gap: 15px;
                align-items: center;
                flex-wrap: wrap;
            }

            .search-input,
            .filter-select {
                padding: 10px 15px;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                font-size: 14px;
            }

            .search-input {
                min-width: 250px;
            }

            .filter-select {
                min-width: 150px;
            }

            .data-table {
                width: 100%;
                border-collapse: collapse;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            }

            .data-table th {
                background: #1e40af;
                color: white;
                padding: 15px;
                text-align: left;
                font-weight: 600;
            }

            .data-table td {
                padding: 15px;
                border-bottom: 1px solid #e5e7eb;
                vertical-align: middle;
            }

            .data-table tr:hover {
                background: #f8fafc;
            }

            .status-badge {
                padding: 4px 12px;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
            }

            .status-active {
                background: #d1fae5;
                color: #065f46;
            }

            .status-inactive {
                background: #fee2e2;
                color: #991b1b;
            }

            .action-buttons {
                display: flex;
                gap: 8px;
                flex-wrap: wrap;
            }

            .btn {
                padding: 8px 16px;
                border: none;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                transition: all 0.2s ease;
            }

            .btn-sm {
                padding: 6px 12px;
                font-size: 11px;
            }

            .btn-primary {
                background: #3b82f6;
                color: white;
            }

            .btn-secondary {
                background: #6b7280;
                color: white;
            }

            .btn-danger {
                background: #dc2626;
                color: white;
            }

            .btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }

            .text-center {
                text-align: center;
                color: #6b7280;
                font-style: italic;
            }

            .pagination-wrapper {
                margin-top: 20px;
                display: flex;
                justify-content: center;
            }
        </style>
    @endsection
@endsection
