@extends('layouts.app')

@section('title', 'Shipper Details - ' . $shipper->shipper_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $shipper->shipper_name }}
                </h1>
                <p style="color: #64748b;">Shipper Details - {{ $shipper->shipper_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('logistics.shippers.edit', $shipper) }}" class="btn btn-primary">Edit Shipper</a>
                <a href="{{ route('logistics.shippers.index') }}" class="btn btn-secondary">Back to List</a>
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
                        <strong>Shipper Code:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->shipper_code }}</span>
                    </div>
                    <div>
                        <strong>Shipper Name:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->shipper_name }}</span>
                    </div>
                    <div>
                        <strong>Company Name:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->company_name }}</span>
                    </div>
                    <div>
                        <strong>Type:</strong><br>
                        <span
                            class="shipper-type-badge shipper-type-{{ strtolower(str_replace(' ', '-', $shipper->shipper_type)) }}">
                            {{ $shipper->type_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipper->status)) }}">{{ $shipper->status }}</span>
                    </div>
                    @if ($shipper->established_year)
                        <div>
                            <strong>Established:</strong><br>
                            <span style="color: #64748b;">{{ $shipper->established_year }}</span>
                        </div>
                    @endif
                    @if ($shipper->business_license)
                        <div>
                            <strong>Business License:</strong><br>
                            <span style="color: #64748b;">{{ $shipper->business_license }}</span>
                        </div>
                    @endif
                    @if ($shipper->tax_id)
                        <div>
                            <strong>Tax ID:</strong><br>
                            <span style="color: #64748b;">{{ $shipper->tax_id }}</span>
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
                        <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">
                            {{ $statistics['active_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Active Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #0ea5e9;">
                            {{ $statistics['monthly_volume'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">This Month</div>
                    </div>
                    @if ($statistics['performance_score'])
                        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                            <div style="font-size: 1.5rem; font-weight: bold; color: #059669;">
                                {{ $statistics['performance_score'] }}%
                            </div>
                            <div style="font-size: 0.875rem; color: #64748b;">Performance Score</div>
                        </div>
                    @endif
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
                    <span style="color: #64748b;">{{ $shipper->formatted_address }}</span>
                </div>
                <div>
                    @if ($shipper->hasCoordinates())
                        <strong>Coordinates:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->latitude }}, {{ $shipper->longitude }}</span>
                        <br>
                        <a href="https://maps.google.com/?q={{ $shipper->latitude }},{{ $shipper->longitude }}"
                            target="_blank" style="color: var(--primary-color); font-size: 0.875rem;">View on Google
                            Maps</a>
                    @endif
                </div>
            </div>

            @if ($shipper->time_zone)
                <div style="margin-top: 1rem;">
                    <strong>Time Zone:</strong>
                    <span style="color: #64748b;">{{ $shipper->time_zone }}</span>
                </div>
            @endif
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
                    <span style="color: #64748b;">{{ $shipper->contact_person }}</span>
                </div>
                <div>
                    <strong>Phone:</strong><br>
                    <a href="tel:{{ $shipper->contact_phone }}"
                        style="color: var(--primary-color);">{{ $shipper->contact_phone }}</a>
                </div>
                <div>
                    <strong>Email:</strong><br>
                    <a href="mailto:{{ $shipper->contact_email }}"
                        style="color: var(--primary-color);">{{ $shipper->contact_email }}</a>
                </div>
                @if ($shipper->website)
                    <div>
                        <strong>Website:</strong><br>
                        <a href="{{ $shipper->website }}" target="_blank"
                            style="color: var(--primary-color);">{{ $shipper->website }}</a>
                    </div>
                @endif
                @if ($shipper->alternative_phone)
                    <div>
                        <strong>Alternative Phone:</strong><br>
                        <a href="tel:{{ $shipper->alternative_phone }}"
                            style="color: var(--primary-color);">{{ $shipper->alternative_phone }}</a>
                    </div>
                @endif
                @if ($shipper->emergency_contact)
                    <div>
                        <strong>Emergency Contact:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->emergency_contact }}</span>
                    </div>
                @endif
            </div>

            @if ($shipper->operating_hours)
                <div style="margin-top: 1rem;">
                    <strong>Operating Hours:</strong>
                    <span style="color: #64748b;">{{ $shipper->operating_hours }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Business Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>🏭 Business Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @if ($shipper->industry_type)
                    <div>
                        <strong>Industry Type:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->industry_type }}</span>
                    </div>
                @endif
                @if ($shipper->annual_volume)
                    <div>
                        <strong>Annual Volume:</strong><br>
                        <span style="color: #059669; font-weight: 600;">{{ $shipper->volume_display }}</span>
                    </div>
                @endif
                @if ($shipper->getUtilizationRate() > 0)
                    <div>
                        <strong>Current Utilization:</strong><br>
                        <span style="color: #f59e0b; font-weight: 600;">{{ $shipper->getUtilizationRate() }}%</span>
                    </div>
                @endif
            </div>

            @if ($shipper->specialization)
                <div style="margin-top: 1rem;">
                    <strong>Specialization:</strong><br>
                    <span style="color: #64748b;">{{ $shipper->specialization }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Financial Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>💰 Financial Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @if ($shipper->credit_rating)
                    <div>
                        <strong>Credit Rating:</strong><br>
                        <span
                            class="credit-badge credit-{{ strtolower(str_replace(['+', '-'], ['plus', 'minus'], $shipper->credit_rating)) }}">
                            {{ $shipper->credit_rating }}
                        </span>
                    </div>
                @endif
                @if ($shipper->payment_terms)
                    <div>
                        <strong>Payment Terms:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->payment_terms }}</span>
                    </div>
                @endif
                @if ($shipper->credit_limit)
                    <div>
                        <strong>Credit Limit:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->currency_preference }}
                            {{ number_format($shipper->credit_limit, 2) }}</span>
                    </div>
                @endif
                @if ($shipper->currency_preference)
                    <div>
                        <strong>Currency Preference:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->currency_preference }}</span>
                    </div>
                @endif
                @if ($shipper->bank_name)
                    <div>
                        <strong>Bank:</strong><br>
                        <span style="color: #64748b;">{{ $shipper->bank_name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Capabilities & Features -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>🏢 Capabilities & Features</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Insurance Coverage:</strong><br>
                    @if ($shipper->insurance_coverage)
                        <span class="status-badge status-success">Available</span>
                    @else
                        <span class="status-badge status-secondary">Not Available</span>
                    @endif
                </div>
                <div>
                    <strong>Customs Broker:</strong><br>
                    @if ($shipper->customs_broker)
                        <span class="status-badge status-success">Licensed</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Freight Forwarder:</strong><br>
                    @if ($shipper->freight_forwarder)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Dangerous Goods:</strong><br>
                    @if ($shipper->dangerous_goods_certified)
                        <span class="status-badge status-warning">Certified</span>
                    @else
                        <span class="status-badge status-secondary">Not Certified</span>
                    @endif
                </div>
                <div>
                    <strong>Track & Trace:</strong><br>
                    @if ($shipper->track_and_trace_required)
                        <span class="status-badge status-info">Required</span>
                    @else
                        <span class="status-badge status-secondary">Optional</span>
                    @endif
                </div>
                <div>
                    <strong>Notifications:</strong><br>
                    @if ($shipper->email_notifications && $shipper->sms_notifications)
                        <span class="status-badge status-success">Email & SMS</span>
                    @elseif ($shipper->email_notifications)
                        <span class="status-badge status-info">Email Only</span>
                    @elseif ($shipper->sms_notifications)
                        <span class="status-badge status-info">SMS Only</span>
                    @else
                        <span class="status-badge status-secondary">None</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cargo Types -->
    @if ($shipper->cargo_types && count($shipper->cargo_types) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Cargo Types Handled</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipper->cargo_types as $cargoType)
                        <span class="cargo-badge">{{ $cargoType }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Services Offered -->
    @if ($shipper->services_offered && count($shipper->services_offered) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🛠️ Services Offered</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipper->services_offered as $service)
                        <span class="service-badge">{{ $service }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Trade Routes -->
    @if ($shipper->trade_routes && count($shipper->trade_routes) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🌍 Trade Routes</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipper->trade_routes as $route)
                        <span class="route-badge">{{ $route }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Certifications -->
    @if ($shipper->certifications && count($shipper->certifications) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🏆 Certifications</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipper->certifications as $certification)
                        <span class="certification-badge">{{ $certification }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Shipments -->
    @if ($recentShipments && $recentShipments->count() > 0)
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
                                <th>Destination</th>
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
                                    <td>{{ $shipment->originPort->port_name ?? 'N/A' }}</td>
                                    <td>{{ $shipment->destinationPort->port_name ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                    <td>{{ $shipment->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('management.shipments.show', $shipment) }}"
                                            class="btn btn-outline"
                                            style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🚢 Recent Shipments</h3>
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 2rem; color: #64748b;">
                    <p>No shipments found for this shipper.</p>
                    <small style="color: #9ca3af;">Shipment data will be available once the shipper-shipment relationship
                        is configured.</small>
                </div>
            </div>
        </div>
    @endif

    <!-- Account Management -->
    @if ($shipper->sales_representative || $shipper->account_manager || $shipper->contract_start_date)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>👥 Account Management</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    @if ($shipper->sales_representative)
                        <div>
                            <strong>Sales Representative:</strong><br>
                            <span style="color: #64748b;">{{ $shipper->sales_representative }}</span>
                        </div>
                    @endif
                    @if ($shipper->account_manager)
                        <div>
                            <strong>Account Manager:</strong><br>
                            <span style="color: #64748b;">{{ $shipper->account_manager }}</span>
                        </div>
                    @endif
                    @if ($shipper->contract_start_date)
                        <div>
                            <strong>Contract Period:</strong><br>
                            <span style="color: #64748b;">
                                {{ $shipper->contract_start_date->format('M j, Y') }}
                                @if ($shipper->contract_end_date)
                                    - {{ $shipper->contract_end_date->format('M j, Y') }}
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Notes -->
    @if ($shipper->notes)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📝 Notes</h3>
            </div>
            <div class="card-body">
                <p style="color: #64748b; line-height: 1.6;">{{ $shipper->notes }}</p>
            </div>
        </div>
    @endif

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

        .cargo-badge {
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

        .route-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #f59e0b;
        }

        .certification-badge {
            background: #f3e8ff;
            color: #6b21a8;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #a855f7;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-info {
            background: #e0f2fe;
            color: #0277bd;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
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
