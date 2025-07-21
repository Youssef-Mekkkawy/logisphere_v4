@extends('layouts.app')

@section('title', 'Destinations')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">📍 Destinations
                </h1>
                <p style="color: #64748b;">Manage delivery destinations and endpoints</p>
            </div>
            <a href="{{ route('submenu.destinations.create') }}" class="btn btn-primary">+ Add New Destination</a>
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
                        placeholder="Destination name, code, city...">
                </div>

                <div class="form-group">
                    <label class="form-label">Destination Type</label>
                    <select name="destination_type" class="form-input">
                        <option value="">All Types</option>
                        <option value="Port" {{ request('destination_type') == 'Port' ? 'selected' : '' }}>🚢 Port
                        </option>
                        <option value="Airport" {{ request('destination_type') == 'Airport' ? 'selected' : '' }}>✈️ Airport
                        </option>
                        <option value="Warehouse" {{ request('destination_type') == 'Warehouse' ? 'selected' : '' }}>🏪
                            Warehouse</option>
                        <option value="Factory" {{ request('destination_type') == 'Factory' ? 'selected' : '' }}>🏭 Factory
                        </option>
                        <option value="City" {{ request('destination_type') == 'City' ? 'selected' : '' }}>🏙️ City Center
                        </option>
                        <option value="Terminal" {{ request('destination_type') == 'Terminal' ? 'selected' : '' }}>📦
                            Terminal</option>
                        <option value="Depot" {{ request('destination_type') == 'Depot' ? 'selected' : '' }}>🚛 Depot
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
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('submenu.destinations.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Destinations Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Destination Name</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Contact</th>
                        <th>Appointment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $destination)
                        <tr>
                            <td><strong>{{ $destination->destination_code }}</strong></td>
                            <td>{{ $destination->destination_name }}</td>
                            <td>
                                <span
                                    class="destination-badge destination-{{ strtolower($destination->destination_type) }}">
                                    {{ $destination->type_display }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $destination->city }}, {{ $destination->country->name ?? $destination->country }}
                                </div>
                                @if ($destination->state_province)
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $destination->state_province }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($destination->contact_person)
                                    <div>{{ $destination->contact_person }}</div>
                                @endif
                                @if ($destination->contact_phone)
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $destination->contact_phone }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($destination->requires_appointment)
                                    <span class="status-badge status-warning">Required</span>
                                @else
                                    <span class="status-badge status-success">Not Required</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="status-badge status-{{ strtolower($destination->status) }}">{{ $destination->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('submenu.destinations.show', $destination) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('submenu.destinations.edit', $destination) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('submenu.destinations.destroy', $destination) }}" method="POST"
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
                                No destinations found. <a href="{{ route('submenu.destinations.create') }}"
                                    style="color: var(--primary-color);">Create your first destination</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($destinations) && $destinations->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $destinations->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .destination-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .destination-port {
            background: #dbeafe;
            color: #1e40af;
        }

        .destination-airport {
            background: #f0f9ff;
            color: #0369a1;
        }

        .destination-warehouse {
            background: #fef3c7;
            color: #92400e;
        }

        .destination-factory {
            background: #d1fae5;
            color: #065f46;
        }

        .destination-city {
            background: #e0e7ff;
            color: #3730a3;
        }

        .destination-terminal {
            background: #fce7f3;
            color: #be185d;
        }

        .destination-depot {
            background: #ecfdf5;
            color: #047857;
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
