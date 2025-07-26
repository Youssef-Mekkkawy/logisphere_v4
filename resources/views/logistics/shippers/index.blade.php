@extends('layouts.app')

@section('title', 'Shippers')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">🏢 Shippers
                    Management</h1>
                <p style="color: #64748b;">Manage shipping companies and cargo shippers</p>
            </div>
            <a href="{{ route('logistics.shippers.create') }}" class="btn btn-primary">+ Add New Shipper</a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>🔍 Filters</h3>
        </div>
        <div class="card-body">
            <form method="GET"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Shipper name, code, contact...">
                </div>

                <div class="form-group">
                    <label class="form-label">Shipper Type</label>
                    <select name="shipper_type" class="form-input">
                        <option value="">All Types</option>
                        <option value="Manufacturer" {{ request('shipper_type') == 'Manufacturer' ? 'selected' : '' }}>🏭
                            Manufacturer</option>
                        <option value="Exporter" {{ request('shipper_type') == 'Exporter' ? 'selected' : '' }}>📦 Exporter
                        </option>
                        <option value="Trading Company"
                            {{ request('shipper_type') == 'Trading Company' ? 'selected' : '' }}>🏢 Trading Company</option>
                        <option value="Freight Forwarder"
                            {{ request('shipper_type') == 'Freight Forwarder' ? 'selected' : '' }}>🚛 Freight Forwarder
                        </option>
                        <option value="Agent" {{ request('shipper_type') == 'Agent' ? 'selected' : '' }}>👔 Agent</option>
                        <option value="Importer" {{ request('shipper_type') == 'Importer' ? 'selected' : '' }}>📥 Importer
                        </option>
                        <option value="Distributor" {{ request('shipper_type') == 'Distributor' ? 'selected' : '' }}>🏪
                            Distributor</option>
                        <option value="Retailer" {{ request('shipper_type') == 'Retailer' ? 'selected' : '' }}>🛒 Retailer
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country_id" class="form-input">
                        <option value="">All Countries</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Credit Rating</label>
                    <select name="credit_rating" class="form-input">
                        <option value="">All Ratings</option>
                        <option value="A+" {{ request('credit_rating') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A" {{ request('credit_rating') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="A-" {{ request('credit_rating') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ request('credit_rating') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B" {{ request('credit_rating') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="B-" {{ request('credit_rating') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="C+" {{ request('credit_rating') == 'C+' ? 'selected' : '' }}>C+</option>
                        <option value="C" {{ request('credit_rating') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="C-" {{ request('credit_rating') == 'C-' ? 'selected' : '' }}>C-</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Suspended" {{ request('status') == 'Suspended' ? 'selected' : '' }}>Suspended
                        </option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('logistics.shippers.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Shippers Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Shipper Name</th>
                        <th>Type</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Credit Rating</th>
                        <th>Volume</th>
                        <th>Shipments</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shippers as $shipper)
                        <tr>
                            <td><strong>{{ $shipper->shipper_code }}</strong></td>
                            <td>
                                <div>{{ $shipper->shipper_name }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $shipper->company_name }}</div>
                            </td>
                            <td>
                                <span
                                    class="shipper-type-badge shipper-type-{{ strtolower(str_replace(' ', '-', $shipper->shipper_type)) }}">
                                    {{ $shipper->type_display }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $shipper->contact_person }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $shipper->contact_email }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $shipper->contact_phone }}</div>
                            </td>
                            <td>
                                <div>{{ $shipper->city }}, {{ $shipper->country->name ?? $shipper->country }}</div>
                                @if ($shipper->state_province)
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $shipper->state_province }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($shipper->credit_rating)
                                    <span
                                        class="credit-badge credit-{{ strtolower(str_replace(['+', '-'], ['plus', 'minus'], $shipper->credit_rating)) }}">
                                        {{ $shipper->credit_rating }}
                                    </span>
                                @else
                                    <span class="credit-badge credit-unrated">Not Rated</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #64748b;">{{ $shipper->volume_display }}</div>
                            </td>
                            <td>
                                <div style="text-align: center; font-weight: 600;">
                                    N/A
                                </div>
                            </td>
                            <td>
                                <span
                                    class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipper->status)) }}">
                                    {{ $shipper->status }}
                                </span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('logistics.shippers.show', $shipper) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('logistics.shippers.edit', $shipper) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('logistics.shippers.destroy', $shipper) }}" method="POST"
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
                            <td colspan="10" style="text-align: center; padding: 2rem; color: #64748b;">
                                No shippers found. <a href="{{ route('logistics.shippers.create') }}"
                                    style="color: var(--primary-color);">Create your first shipper</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($shippers) && $shippers->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $shippers->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .shipper-type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .shipper-type-manufacturer {
            background: #f0f9ff;
            color: #0369a1;
        }

        .shipper-type-exporter {
            background: #d1fae5;
            color: #065f46;
        }

        .shipper-type-trading-company {
            background: #fef3c7;
            color: #92400e;
        }

        .shipper-type-freight-forwarder {
            background: #e0e7ff;
            color: #3730a3;
        }

        .shipper-type-agent {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .shipper-type-importer {
            background: #fce7f3;
            color: #be185d;
        }

        .shipper-type-distributor {
            background: #ecfdf5;
            color: #047857;
        }

        .shipper-type-retailer {
            background: #fee2e2;
            color: #991b1b;
        }

        .credit-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .credit-aplus {
            background: #dcfce7;
            color: #166534;
        }

        .credit-a {
            background: #dcfce7;
            color: #166534;
        }

        .credit-aminus {
            background: #f0fdf4;
            color: #15803d;
        }

        .credit-bplus {
            background: #fef3c7;
            color: #92400e;
        }

        .credit-b {
            background: #fef3c7;
            color: #92400e;
        }

        .credit-bminus {
            background: #fefce8;
            color: #a16207;
        }

        .credit-cplus {
            background: #fee2e2;
            color: #991b1b;
        }

        .credit-c {
            background: #fee2e2;
            color: #991b1b;
        }

        .credit-cminus {
            background: #fef2f2;
            color: #b91c1c;
        }

        .credit-unrated {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-suspended {
            background: #fef3c7;
            color: #92400e;
        }

        .status-pending {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }
    </style>
@endsection
