@extends('layouts.app')

@section('title', 'Container Loading Points')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">📦 Container
                    Loading Points</h1>
                <p style="color: #64748b;">Manage container loading locations and facilities</p>
            </div>
            <a href="{{ route('submenu.container-loading.create') }}" class="btn btn-primary">+ Add New Loading Point</a>
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
                        placeholder="Loading point name, operator...">
                </div>

                <div class="form-group">
                    <label class="form-label">Facility Type</label>
                    <select name="facility_type" class="form-input">
                        <option value="">All Types</option>
                        <option value="CFS" {{ request('facility_type') == 'CFS' ? 'selected' : '' }}>Container Freight
                            Station (CFS)</option>
                        <option value="Warehouse" {{ request('facility_type') == 'Warehouse' ? 'selected' : '' }}>Warehouse
                        </option>
                        <option value="Factory" {{ request('facility_type') == 'Factory' ? 'selected' : '' }}>Factory
                        </option>
                        <option value="Port Terminal" {{ request('facility_type') == 'Port Terminal' ? 'selected' : '' }}>
                            Port Terminal</option>
                        <option value="Depot" {{ request('facility_type') == 'Depot' ? 'selected' : '' }}>Container Depot
                        </option>
                        <option value="Container Yard" {{ request('facility_type') == 'Container Yard' ? 'selected' : '' }}>
                            Container Yard</option>
                        <option value="Inland Terminal"
                            {{ request('facility_type') == 'Inland Terminal' ? 'selected' : '' }}>Inland Terminal</option>
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
                    <label class="form-label">Appointment Required</label>
                    <select name="requires_appointment" class="form-input">
                        <option value="">All</option>
                        <option value="1" {{ request('requires_appointment') == '1' ? 'selected' : '' }}>Appointment
                            Required</option>
                        <option value="0" {{ request('requires_appointment') == '0' ? 'selected' : '' }}>Walk-in
                            Allowed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance
                        </option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('submenu.container-loading.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Points Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Loading Point</th>
                        <th>Facility Type</th>
                        <th>Operator</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Appointment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loadingPoints as $point)
                        <tr>
                            <td><strong>{{ $point->loading_point_code }}</strong></td>
                            <td>{{ $point->loading_point_name }}</td>
                            <td>
                                <span
                                    class="facility-badge facility-{{ strtolower(str_replace(' ', '-', $point->facility_type)) }}">
                                    {{ $point->facility_type }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $point->operator_name }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $point->contact_person }}</div>
                            </td>
                            <td>
                                <div>{{ $point->city }}, {{ $point->country->name ?? $point->country }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $point->phone }}</div>
                            </td>
                            <td>
                                @if ($point->max_containers_per_day)
                                    <span class="capacity-badge">{{ $point->max_containers_per_day }} TEU/day</span>
                                @else
                                    <span style="color: #9ca3af;">Unlimited</span>
                                @endif
                            </td>
                            <td>
                                @if ($point->requires_appointment)
                                    <span class="status-badge status-warning">
                                        Required ({{ $point->advance_booking_hours }}h)
                                    </span>
                                @else
                                    <span class="status-badge status-success">Walk-in OK</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="status-badge status-{{ strtolower($point->status) }}">{{ $point->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('submenu.container-loading.show', $point) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('submenu.container-loading.edit', $point) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('submenu.container-loading.destroy', $point) }}" method="POST"
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
                                No container loading points found. <a
                                    href="{{ route('submenu.container-loading.create') }}"
                                    style="color: var(--primary-color);">Create your first loading point</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($loadingPoints) && $loadingPoints->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $loadingPoints->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .facility-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .facility-cfs {
            background: #dbeafe;
            color: #1e40af;
        }

        .facility-warehouse {
            background: #fef3c7;
            color: #92400e;
        }

        .facility-factory {
            background: #d1fae5;
            color: #065f46;
        }

        .facility-port-terminal {
            background: #e0e7ff;
            color: #3730a3;
        }

        .facility-depot {
            background: #fce7f3;
            color: #be185d;
        }

        .facility-container-yard {
            background: #ecfdf5;
            color: #047857;
        }

        .facility-inland-terminal {
            background: #fff7ed;
            color: #c2410c;
        }

        .capacity-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }
    </style>
@endsection
