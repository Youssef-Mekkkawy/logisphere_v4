@extends('layouts.app')

@section('title', 'Destination Details - ' . $destination->destination_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $destination->destination_name }}
                </h1>
                <p style="color: #64748b;">Destination Details - {{ $destination->destination_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('submenu.destinations.edit', $destination) }}" class="btn btn-primary">Edit Destination</a>
                <a href="{{ route('submenu.destinations.index') }}" class="btn btn-secondary">Back to List</a>
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
                        <strong>Destination Code:</strong><br>
                        <span style="color: #64748b;">{{ $destination->destination_code }}</span>
                    </div>
                    <div>
                        <strong>Destination Name:</strong><br>
                        <span style="color: #64748b;">{{ $destination->destination_name }}</span>
                    </div>
                    <div>
                        <strong>Type:</strong><br>
                        <span class="destination-badge destination-{{ strtolower($destination->destination_type) }}">
                            {{ $destination->type_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower($destination->status) }}">{{ $destination->status }}</span>
                    </div>
                    @if ($destination->timezone)
                        <div>
                            <strong>Timezone:</strong><br>
                            <span style="color: #64748b;">{{ $destination->timezone_display }}</span>
                        </div>
                    @endif
                    <div>
                        <strong>Appointment Required:</strong><br>
                        @if ($destination->requires_appointment)
                            <span class="status-badge status-warning">Yes</span>
                        @else
                            <span class="status-badge status-success">No</span>
                        @endif
                    </div>
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
                            {{ $statistics['active_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Active Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #dc2626;">
                            {{ $statistics['completed_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Completed</div>
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
                    <span style="color: #64748b;">{{ $destination->formatted_address }}</span>
                </div>
                <div>
                    @if ($destination->hasCoordinates())
                        <strong>Coordinates:</strong><br>
                        <span style="color: #64748b;">{{ $destination->latitude }}, {{ $destination->longitude }}</span>
                        <br>
                        <a href="https://maps.google.com/?q={{ $destination->latitude }},{{ $destination->longitude }}"
                            target="_blank" style="color: var(--primary-color); font-size: 0.875rem;">View on Google
                            Maps</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    @if ($destination->contact_person || $destination->contact_phone || $destination->contact_email)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📞 Contact Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    @if ($destination->contact_person)
                        <div>
                            <strong>Contact Person:</strong><br>
                            <span style="color: #64748b;">{{ $destination->contact_person }}</span>
                        </div>
                    @endif
                    @if ($destination->contact_phone)
                        <div>
                            <strong>Phone:</strong><br>
                            <a href="tel:{{ $destination->contact_phone }}"
                                style="color: var(--primary-color);">{{ $destination->contact_phone }}</a>
                        </div>
                    @endif
                    @if ($destination->contact_email)
                        <div>
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $destination->contact_email }}"
                                style="color: var(--primary-color);">{{ $destination->contact_email }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Facilities & Services -->
    @if ($destination->facilities && count($destination->facilities) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🏢 Available Facilities</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($destination->facilities as $facility)
                        <span class="facility-badge">{{ $facility }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Delivery Instructions & Restrictions -->
    @if ($destination->delivery_instructions || $destination->access_restrictions)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📝 Delivery Instructions & Restrictions</h3>
            </div>
            <div class="card-body">
                @if ($destination->delivery_instructions)
                    <div style="margin-bottom: 1.5rem;">
                        <strong>Delivery Instructions:</strong><br>
                        <span style="color: #64748b;">{{ $destination->delivery_instructions }}</span>
                    </div>
                @endif
                @if ($destination->access_restrictions)
                    <div>
                        <strong>Access Restrictions:</strong><br>
                        <span style="color: #64748b;">{{ $destination->access_restrictions }}</span>
                    </div>
                @endif
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
                                <th>Origin</th>
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
                                    <td>{{ $shipment->originPort->name ?? 'N/A' }}</td>
                                    <td><span
                                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}">{{ $shipment->status }}</span>
                                    </td>
                                    <td>{{ $shipment->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-outline"
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

        .facility-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #0ea5e9;
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
