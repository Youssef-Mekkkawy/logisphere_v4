@extends('layouts.app')

@section('title', 'Loading Point Details - ' . $containerLoading->loading_point_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $containerLoading->loading_point_name }}
                </h1>
                <p style="color: #64748b;">Loading Point Details - {{ $containerLoading->loading_point_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('submenu.container-loading.edit', $containerLoading) }}" class="btn btn-primary">Edit
                    Loading Point</a>
                <a href="{{ route('submenu.container-loading.index') }}" class="btn btn-secondary">Back to List</a>
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
                        <strong>Loading Point Code:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->loading_point_code }}</span>
                    </div>
                    <div>
                        <strong>Facility Name:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->loading_point_name }}</span>
                    </div>
                    <div>
                        <strong>Facility Type:</strong><br>
                        <span
                            class="facility-badge facility-{{ strtolower(str_replace(' ', '-', $containerLoading->facility_type)) }}">
                            {{ $containerLoading->facility_type }}
                        </span>
                    </div>
                    <div>
                        <strong>Operator:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->operator_name }}</span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower($containerLoading->status) }}">{{ $containerLoading->status }}</span>
                    </div>
                    <div>
                        <strong>Capacity:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->capacity_status }}</span>
                        @if ($containerLoading->max_containers_per_day)
                            <br><small>({{ $containerLoading->max_containers_per_day }} TEU/day)</small>
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
                            {{ $statistics['total_bookings'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Total Bookings</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #059669;">
                            {{ $statistics['active_bookings'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Active Bookings</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #dc2626;">
                            {{ $statistics['completed_bookings'] }}
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

    <!-- Contact Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>📞 Contact Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Contact Person:</strong><br>
                    <span style="color: #64748b;">{{ $containerLoading->contact_person }}</span>
                </div>
                <div>
                    <strong>Phone:</strong><br>
                    <a href="tel:{{ $containerLoading->phone }}"
                        style="color: var(--primary-color);">{{ $containerLoading->phone }}</a>
                </div>
                @if ($containerLoading->email)
                    <div>
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $containerLoading->email }}"
                            style="color: var(--primary-color);">{{ $containerLoading->email }}</a>
                    </div>
                @endif
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
                    <strong>Address:</strong><br>
                    <span style="color: #64748b;">{{ $containerLoading->formatted_address }}</span>
                </div>
                <div>
                    @if ($containerLoading->hasCoordinates())
                        <strong>Coordinates:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->latitude }},
                            {{ $containerLoading->longitude }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Operational Details -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>⚙️ Operational Details</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Operating Hours:</strong><br>
                    <span style="color: #64748b;">{{ $containerLoading->operating_hours_display }}</span>
                </div>
                <div>
                    <strong>Appointment Required:</strong><br>
                    @if ($containerLoading->requires_appointment)
                        <span class="status-badge status-warning">
                            Yes ({{ $containerLoading->advance_booking_hours }} hours advance)
                        </span>
                    @else
                        <span class="status-badge status-success">No - Walk-in allowed</span>
                    @endif
                </div>
                <div>
                    <strong>Security:</strong><br>
                    @if ($containerLoading->has_security)
                        <span class="security-badge security-yes">Security Personnel</span>
                    @endif
                    @if ($containerLoading->has_cctv)
                        <span class="security-badge security-yes">CCTV Monitoring</span>
                    @endif
                    @if (!$containerLoading->has_security && !$containerLoading->has_cctv)
                        <span class="security-badge security-no">Basic Security</span>
                    @endif
                </div>
            </div>

            @if ($containerLoading->container_types_handled && count($containerLoading->container_types_handled) > 0)
                <div style="margin-top: 1.5rem;">
                    <strong>Container Types Handled:</strong><br>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                        @foreach ($containerLoading->container_types_handled as $type)
                            <span class="container-type-badge">{{ $type }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($containerLoading->equipment_available && count($containerLoading->equipment_available) > 0)
                <div style="margin-top: 1.5rem;">
                    <strong>Equipment Available:</strong><br>
                    <span style="color: #64748b;">{{ $containerLoading->equipment_list }}</span>
                </div>
            @endif

            @if ($containerLoading->services_offered && count($containerLoading->services_offered) > 0)
                <div style="margin-top: 1.5rem;">
                    <strong>Services Offered:</strong><br>
                    <span style="color: #64748b;">{{ $containerLoading->services_list }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Pricing Information -->
    @if ($containerLoading->storage_rate_per_day || $containerLoading->stuffing_rate || $containerLoading->destuffing_rate)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>💰 Pricing Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    @if ($containerLoading->storage_rate_per_day)
                        <div>
                            <strong>Storage Rate:</strong><br>
                            <span style="color: #64748b;">${{ number_format($containerLoading->storage_rate_per_day, 2) }}
                                per day</span>
                        </div>
                    @endif
                    @if ($containerLoading->stuffing_rate)
                        <div>
                            <strong>Stuffing Rate:</strong><br>
                            <span style="color: #64748b;">${{ number_format($containerLoading->stuffing_rate, 2) }} per
                                container</span>
                        </div>
                    @endif
                    @if ($containerLoading->destuffing_rate)
                        <div>
                            <strong>Destuffing Rate:</strong><br>
                            <span style="color: #64748b;">${{ number_format($containerLoading->destuffing_rate, 2) }} per
                                container</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Additional Information -->
    @if ($containerLoading->access_instructions || $containerLoading->safety_requirements || $containerLoading->notes)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📝 Additional Information</h3>
            </div>
            <div class="card-body">
                @if ($containerLoading->access_instructions)
                    <div style="margin-bottom: 1.5rem;">
                        <strong>Access Instructions:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->access_instructions }}</span>
                    </div>
                @endif
                @if ($containerLoading->safety_requirements)
                    <div style="margin-bottom: 1.5rem;">
                        <strong>Safety Requirements:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->safety_requirements }}</span>
                    </div>
                @endif
                @if ($containerLoading->notes)
                    <div>
                        <strong>Notes:</strong><br>
                        <span style="color: #64748b;">{{ $containerLoading->notes }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Recent Bookings -->
    @if ($recentBookings->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🚢 Recent Bookings</h3>
            </div>
            <div class="card-body">
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Shipment ID</th>
                                <th>Company</th>
                                <th>Route</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentBookings as $booking)
                                <tr>
                                    <td><strong>{{ $booking->shipment_id }}</strong></td>
                                    <td>{{ $booking->company->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->originPort->name ?? 'N/A' }} →
                                        {{ $booking->destinationPort->name ?? 'N/A' }}</td>
                                    <td><span
                                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $booking->status)) }}">{{ $booking->status }}</span>
                                    </td>
                                    <td>{{ $booking->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('shipments.show', $booking) }}" class="btn btn-outline"
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

        .container-type-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid #0ea5e9;
        }

        .security-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .security-yes {
            background: #dcfce7;
            color: #166534;
        }

        .security-no {
            background: #fee2e2;
            color: #991b1b;
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
