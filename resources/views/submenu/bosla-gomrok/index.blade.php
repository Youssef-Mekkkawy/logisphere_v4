@extends('layouts.app')

@section('title', 'Bosla From Gomrok')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b;">📋 Bosla From Gomrok</h2>
            <p style="color: #64748b;">Manage customs box/paperwork documentation</p>
        </div>
        <a href="{{ route('submenu.bosla-gomrok.create')  ?? '' }}" class="btn btn-primary">+ Add New Bosla</a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>Filters</h3>
        </div>
        <div class="card-body">
            <form method="GET"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Bosla name, code...">
                </div>

                <div class="form-group">
                    <label class="form-label">Document Type</label>
                    <select name="document_type" class="form-input">
                        <option value="">All Document Types</option>
                        <option value="Export" {{ request('document_type') == 'Export' ? 'selected' : '' }}>Export
                            Declaration</option>
                        <option value="Import" {{ request('document_type') == 'Import' ? 'selected' : '' }}>Import
                            Declaration</option>
                        <option value="Transit" {{ request('document_type') == 'Transit' ? 'selected' : '' }}>Transit
                            Declaration</option>
                        <option value="Temporary" {{ request('document_type') == 'Temporary' ? 'selected' : '' }}>Temporary
                            Admission</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Customs Office</label>
                    <select name="customs_office" class="form-input">
                        <option value="">All Customs Offices</option>
                        @foreach ($customsOffices as $office)
                            <option value="{{ $office->name }}"
                                {{ request('customs_office') == $office->name ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('submenu.bosla-gomrok.index') ?? ''  }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bosla Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bosla Code</th>
                        <th>Bosla Name</th>
                        <th>Document Type</th>
                        <th>Customs Office</th>
                        <th>Processing Time</th>
                        <th>Cost (USD)</th>
                        <th>Mandatory</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boslaItems as $bosla)
                        <tr>
                            <td><strong>{{ $bosla->code }}</strong></td>
                            <td>{{ $bosla->name }}</td>
                            <td>
                                <span class="document-badge document-{{ strtolower($bosla->document_type) }}">
                                    {{ $bosla->document_type }}
                                </span>
                            </td>
                            <td>{{ $bosla->customs_office }}</td>
                            <td>{{ $bosla->processing_time_display }}</td>
                            <td>${{ number_format($bosla->cost, 2) }}</td>
                            <td>
                                @if ($bosla->is_mandatory)
                                    <span class="status-badge status-warning">Mandatory</span>
                                @else
                                    <span class="status-badge status-info">Optional</span>
                                @endif
                            </td>
                            <td><span
                                    class="status-badge status-{{ strtolower($bosla->status) }}">{{ $bosla->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('submenu.bosla-gomrok.show' ?? '' , $bosla) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('submenu.bosla-gomrok.edit' ?? '' , $bosla) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
                                    <form action="{{ route('submenu.bosla-gomrok.destroy' ?? '' , $bosla) }}" method="POST"
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
                                No bosla items found. <a href="{{ route('submenu.bosla-gomrok.create') ?? ''  }}"
                                    style="color: var(--primary-color);">Create your first bosla</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($boslaItems->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $boslaItems->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color, #3b82f6);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color, #3b82f6), var(--primary-dark, #1e40af));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            color: white;
        }

        .btn-outline {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-outline:hover {
            background: var(--primary-color, #3b82f6);
            color: white;
            border-color: var(--primary-color, #3b82f6);
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .document-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .document-export {
            background: #d1fae5;
            color: #065f46;
        }

        .document-import {
            background: #dbeafe;
            color: #1e40af;
        }

        .document-transit {
            background: #fef3c7;
            color: #92400e;
        }

        .document-temporary {
            background: #ede9fe;
            color: #5b21b6;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: #f8fafc;
        }
    </style>
@endsection
