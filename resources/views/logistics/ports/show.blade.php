@extends('layouts.app')

@section('title', 'Port Details - ' . $port->port_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $port->port_name }}
                </h1>
                <p style="color: #64748b;">Port Details - {{ $port->port_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('logistics.ports.edit', $port) }}" class="btn btn-primary">Edit Port</a>
                <a href="{{ route('logistics.ports.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Basic Information -->
        <div class="card">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>Port Code:</strong><br>
                        <span style="color: #64748b;">{{ $port->port_code }}</span>
                    </div>
                    <div>
                        <strong>Port Name:</strong><br>
                        <span style="color: #64748b;">{{ $port->port_name }}</span>
                    </div>
                    <div>
                        <strong>Type:</strong><br>
                        <span class="port-type-badge port-type-{{ strtolower(str_replace(' ', '-', $port->port_type)) }}">
                            {{ $port->type_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $port->status)) }}">{{ $port->status }}</span>
                    </div>
                    <div>
                        <strong>Major Port:</strong><br>
                        @if ($port->major_port)
                            <span class="status-badge status-success">Yes</span>
                        @else
                            <span class="status-badge status-secondary">No</span>
                        @endif
                    </div>
                    @if ($port->port_authority)
                        <div>
                            <strong>Port Authority:</strong><br>
                            <span style="color: #64748b;">{{ $port->port_authority }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h3>📊 Statistics</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 1rem;">
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                            {{ $statistics['total_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Total Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #059669;">
                            {{ $statistics['origin_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Origin Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #0ea5e9;">
                            {{ $statistics['destination_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Destination Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">
                            {{ $statistics['active_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Active Operations</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #7c3aed;">
                            {{ $statistics['monthly_volume'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">This Month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>📍 Location Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <div>
                    <strong>Full Address:</strong><br>
                    <span style="color: #64748b;">{{ $port->formatted_address }}</span>
                </div>
                <div>
                    @if ($port->hasCoordinates())
                        <strong>Coordinates:</strong><br>
                        <span style="color: #64748b;">{{ $port->latitude }}, {{ $port->longitude }}</span>
                        <br>
                        <a href="https://maps.google.com/?q={{ $port->latitude }},{{ $port->longitude }}" target="_blank"
                            style="color: var(--primary-color); font-size: 0.875rem;">View on Google Maps</a>
                    @endif
                </div>
            </div>

            @if ($port->time_zone)
                <div style="margin-top: 1rem;">
                    <strong>Time Zone:</strong>
                    <span style="color: #64748b;">{{ $port->time_zone }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Contact Information -->
    @if ($port->contact_person || $port->contact_phone || $port->contact_email || $port->website)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📞 Contact Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    @if ($port->contact_person)
                        <div>
                            <strong>Contact Person:</strong><br>
                            <span style="color: #64748b;">{{ $port->contact_person }}</span>
                        </div>
                    @endif
                    @if ($port->contact_phone)
                        <div>
                            <strong>Phone:</strong><br>
                            <a href="tel:{{ $port->contact_phone }}"
                                style="color: var(--primary-color);">{{ $port->contact_phone }}</a>
                        </div>
                    @endif
                    @if ($port->contact_email)
                        <div>
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $port->contact_email }}"
                                style="color: var(--primary-color);">{{ $port->contact_email }}</a>
                        </div>
                    @endif
                    @if ($port->website)
                        <div>
                            <strong>Website:</strong><br>
                            <a href="{{ $port->website }}" target="_blank"
                                style="color: var(--primary-color);">{{ $port->website }}</a>
                        </div>
                    @endif
                </div>

                @if ($port->operating_hours)
                    <div style="margin-top: 1rem;">
                        <strong>Operating Hours:</strong>
                        <span style="color: #64748b;">{{ $port->operating_hours }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Port Specifications -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>⚓ Port Specifications</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @if ($port->max_capacity)
                    <div>
                        <strong>Max Capacity:</strong><br>
                        <span style="color: #059669; font-weight: 600;">{{ $port->capacity_display }}</span>
                    </div>
                @endif
                @if ($port->total_berths)
                    <div>
                        <strong>Total Berths:</strong><br>
                        <span style="color: #64748b;">{{ $port->total_berths }}</span>
                    </div>
                @endif
                @if ($port->max_vessel_size)
                    <div>
                        <strong>Max Vessel Size:</strong><br>
                        <span style="color: #64748b;">{{ number_format($port->max_vessel_size) }} DWT</span>
                    </div>
                @endif
                @if ($port->draft_depth)
                    <div>
                        <strong>Draft Depth:</strong><br>
                        <span style="color: #64748b;">{{ $port->draft_display }}</span>
                    </div>
                @endif
                @if ($port->storage_capacity)
                    <div>
                        <strong>Storage Capacity:</strong><br>
                        <span style="color: #64748b;">{{ number_format($port->storage_capacity) }} m²</span>
                    </div>
                @endif
                @if ($port->getUtilizationRate() > 0)
                    <div>
                        <strong>Current Utilization:</strong><br>
                        <span style="color: #f59e0b; font-weight: 600;">{{ $port->getUtilizationRate() }}%</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Port Features -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>🏢 Port Features</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Customs Available:</strong><br>
                    @if ($port->customs_available)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-error">No</span>
                    @endif
                </div>
                <div>
                    <strong>Quarantine Available:</strong><br>
                    @if ($port->quarantine_available)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Pilotage Compulsory:</strong><br>
                    @if ($port->pilotage_compulsory)
                        <span class="status-badge status-warning">Yes</span>
                    @else
                        <span class="status-badge status-success">No</span>
                    @endif
                </div>
                <div>
                    <strong>Rail Connection:</strong><br>
                    @if ($port->rail_connection)
                        <span class="status-badge status-success">Available</span>
                    @else
                        <span class="status-badge status-secondary">Not Available</span>
                    @endif
                </div>
                <div>
                    <strong>Road Connection:</strong><br>
                    @if ($port->road_connection)
                        <span class="status-badge status-success">Available</span>
                    @else
                        <span class="status-badge status-error">Not Available</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Facilities -->
    @if ($port->facilities && count($port->facilities) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🏗️ Available Facilities</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($port->facilities as $facility)
                        <span class="facility-badge">{{ $facility }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Services -->
    @if ($port->services && count($port->services) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🛠️ Available Services</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($port->services as $service)
                        <span class="service-badge">{{ $service }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Handling Equipment -->
    @if ($port->handling_equipment && count($port->handling_equipment) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🏗️ Handling Equipment</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($port->handling_equipment as $equipment)
                        <span class="equipment-badge">{{ $equipment }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Shipments -->
    @if ($recentShipments->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🚢 Recent Shipments</h3>
            </div>
            <div class="card-body">
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Shipment ID</th>
                                <th>Company</th>
                                <th>Direction</th>
                                <th>Destination/Origin</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentShipments as $shipment)
                                <tr>
                                    <td><strong>{{ $shipment->shipment_id }}</strong></td>
                                    <td>{{ $shipment->company->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($shipment->origin_port_id == $port->id)
                                            <span class="direction-badge outbound">Outbound</span>
                                        @else
                                            <span class="direction-badge inbound">Inbound</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($shipment->origin_port_id == $port->id)
                                            {{ $shipment->destinationPort->port_name ?? 'N/A' }}
                                        @else
                                            {{ $shipment->originPort->port_name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                    <td>{{ $shipment->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('management.shipments.show', $shipment) }}" class="btn btn-outline"
                                            style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

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

        .facility-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #0ea5e9;
        }

        .service-badge {
            background: #ecfdf5;
            color: #047857;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #10b981;
        }

        .equipment-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #f59e0b;
        }

        .direction-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .direction-badge.outbound {
            background: #fee2e2;
            color: #991b1b;
        }

        .direction-badge.inbound {
            background: #dcfce7;
            color: #166534;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
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
