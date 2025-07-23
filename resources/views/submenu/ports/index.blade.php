@extends('layouts.app')

@section('title', 'Ports')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">🚢 Ports Management
                </h1>
                <p style="color: #64748b;">Manage ports, terminals, and maritime facilities</p>
            </div>
            <a href="{{ route('submenu.ports.create') }}" class="btn btn-primary">+ Add New Port</a>
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
                        placeholder="Port name, code, city...">
                </div>

                <div class="form-group">
                    <label class="form-label">Port Type</label>
                    <select name="port_type" class="form-input">
                        <option value="">All Types</option>
                        <option value="Seaport" {{ request('port_type') == 'Seaport' ? 'selected' : '' }}>🚢 Seaport
                        </option>
                        <option value="Airport" {{ request('port_type') == 'Airport' ? 'selected' : '' }}>✈️ Airport
                        </option>
                        <option value="Dry Port" {{ request('port_type') == 'Dry Port' ? 'selected' : '' }}>🏭 Dry Port
                        </option>
                        <option value="Container Terminal"
                            {{ request('port_type') == 'Container Terminal' ? 'selected' : '' }}>📦 Container Terminal
                        </option>
                        <option value="Bulk Terminal" {{ request('port_type') == 'Bulk Terminal' ? 'selected' : '' }}>⚖️
                            Bulk Terminal</option>
                        <option value="Oil Terminal" {{ request('port_type') == 'Oil Terminal' ? 'selected' : '' }}>🛢️ Oil
                            Terminal</option>
                        <option value="Ferry Terminal" {{ request('port_type') == 'Ferry Terminal' ? 'selected' : '' }}>⛴️
                            Ferry Terminal</option>
                        <option value="Fishing Port" {{ request('port_type') == 'Fishing Port' ? 'selected' : '' }}>🎣
                            Fishing Port</option>
                        <option value="Marina" {{ request('port_type') == 'Marina' ? 'selected' : '' }}>⛵ Marina</option>
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
                    <label class="form-label">Major Port</label>
                    <select name="major_port" class="form-input">
                        <option value="">All Ports</option>
                        <option value="1" {{ request('major_port') == '1' ? 'selected' : '' }}>Major Ports Only
                        </option>
                        <option value="0" {{ request('major_port') == '0' ? 'selected' : '' }}>Minor Ports Only
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Under Construction"
                            {{ request('status') == 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('submenu.ports.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ports Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Port Name</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Berths</th>
                        <th>Major Port</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ports as $port)
                        <tr>
                            <td><strong>{{ $port->port_code }}</strong></td>
                            <td>{{ $port->port_name }}</td>
                            <td>
                                <span
                                    class="port-type-badge port-type-{{ strtolower(str_replace(' ', '-', $port->port_type)) }}">
                                    {{ $port->type_display }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $port->city }}, {{ $port->country->name ?? $port->country }}</div>
                                @if ($port->state_province)
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $port->state_province }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #64748b;">{{ $port->capacity_display }}</div>
                            </td>
                            <td>
                                <div style="text-align: center; font-weight: 600;">
                                    {{ $port->total_berths ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                @if ($port->major_port)
                                    <span class="status-badge status-success">Major</span>
                                @else
                                    <span class="status-badge status-secondary">Minor</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="status-badge status-{{ strtolower(str_replace(' ', '-', $port->status)) }}">{{ $port->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('submenu.ports.show', $port) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('submenu.ports.edit', $port) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('submenu.ports.destroy', $port) }}" method="POST"
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
                                No ports found. <a href="{{ route('submenu.ports.create') }}"
                                    style="color: var(--primary-color);">Create your first port</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($ports) && $ports->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $ports->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .port-type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .port-type-seaport {
            background: #dbeafe;
            color: #1e40af;
        }

        .port-type-airport {
            background: #f0f9ff;
            color: #0369a1;
        }

        .port-type-dry-port {
            background: #fef3c7;
            color: #92400e;
        }

        .port-type-container-terminal {
            background: #d1fae5;
            color: #065f46;
        }

        .port-type-bulk-terminal {
            background: #e0e7ff;
            color: #3730a3;
        }

        .port-type-oil-terminal {
            background: #fee2e2;
            color: #991b1b;
        }

        .port-type-ferry-terminal {
            background: #fce7f3;
            color: #be185d;
        }

        .port-type-fishing-port {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .port-type-marina {
            background: #ecfdf5;
            color: #047857;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-under-construction {
            background: #fef3c7;
            color: #92400e;
        }

        .status-closed {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
@endsection
