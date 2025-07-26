@extends('layouts.app')

@section('title', 'Shipment Type Details - ' . $shipmentType->type_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $shipmentType->type_name }}
                </h1>
                <p style="color: #64748b;">Shipment Type Details - {{ $shipmentType->type_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('logistics.shipment-types.edit', $shipmentType) }}" class="btn btn-primary">Edit Shipment
                    Type</a>
                <a href="{{ route('logistics.shipment-types.index') }}" class="btn btn-secondary">Back to List</a>
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
                        <strong>Type Code:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->type_code }}</span>
                    </div>
                    <div>
                        <strong>Type Name:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->type_name }}</span>
                    </div>
                    <div>
                        <strong>Category:</strong><br>
                        <span
                            class="category-badge category-{{ strtolower(str_replace(' ', '-', $shipmentType->category)) }}">
                            {{ $shipmentType->category_display }}
                        </span>
                    </div>
                    @if ($shipmentType->subcategory)
                        <div>
                            <strong>Subcategory:</strong><br>
                            <span style="color: #64748b;">{{ $shipmentType->subcategory }}</span>
                        </div>
                    @endif
                    <div>
                        <strong>Cargo Type:</strong><br>
                        <span class="cargo-badge cargo-{{ strtolower(str_replace(' ', '-', $shipmentType->cargo_type)) }}">
                            {{ $shipmentType->cargo_type_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Transit Mode:</strong><br>
                        <span class="transit-badge transit-{{ strtolower($shipmentType->transit_mode) }}">
                            {{ $shipmentType->transit_mode_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span class="status-badge {{ $shipmentType->status_badge }}">{{ $shipmentType->status }}</span>
                    </div>
                    @if ($shipmentType->priority_level)
                        <div>
                            <strong>Priority Level:</strong><br>
                            <span class="priority-badge priority-{{ strtolower($shipmentType->priority_level) }}">
                                {{ $shipmentType->priority_level_display }}
                            </span>
                        </div>
                    @endif
                </div>

                @if ($shipmentType->description)
                    <div style="margin-top: 1.5rem;">
                        <strong>Description:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->description }}</span>
                    </div>
                @endif

                @if ($shipmentType->detailed_description)
                    <div style="margin-top: 1rem;">
                        <strong>Detailed Description:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->detailed_description }}</span>
                    </div>
                @endif
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
                            {{ $statistics['monthly_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">This Month</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #0ea5e9;">
                            {{ $statistics['active_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Active Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">
                            {{ $statistics['cost_effectiveness'] }}%
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Cost Effectiveness</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #7c3aed;">
                            {{ $statistics['performance_rating'] }}/5
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Performance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transit and Cost Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>🕐 Transit and Cost Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Estimated Transit Time:</strong><br>
                    <span style="color: #059669; font-weight: 600;">{{ $shipmentType->estimated_transit_display }}</span>
                </div>
                @if ($shipmentType->cost_factor)
                    <div>
                        <strong>Cost Factor:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->cost_factor }}</span>
                    </div>
                @endif
                @if ($shipmentType->base_rate_multiplier && $shipmentType->base_rate_multiplier != 1.0)
                    <div>
                        <strong>Rate Multiplier:</strong><br>
                        <span style="color: #64748b;">×{{ $shipmentType->base_rate_multiplier }}</span>
                    </div>
                @endif
                @if ($shipmentType->booking_lead_time)
                    <div>
                        <strong>Booking Lead Time:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->booking_lead_time }} days</span>
                    </div>
                @endif
                @if ($shipmentType->service_level)
                    <div>
                        <strong>Service Level:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->service_level_display }}</span>
                    </div>
                @endif
                @if ($shipmentType->customs_complexity)
                    <div>
                        <strong>Customs Complexity:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->customs_complexity }}</span>
                    </div>
                @endif
                @if ($shipmentType->tracking_level)
                    <div>
                        <strong>Tracking Level:</strong><br>
                        <span style="color: #64748b;">{{ $shipmentType->tracking_level }}</span>
                    </div>
                @endif
                <div>
                    <strong>Calculated Cost Multiplier:</strong><br>
                    <span style="color: #f59e0b; font-weight: 600;">×{{ $shipmentType->calculateCostMultiplier() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Features -->
    @if (count($shipmentType->special_features) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>⚠️ Special Features</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipmentType->special_features as $feature)
                        <span class="feature-badge">{{ $feature }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Service Options -->
    @if (count($shipmentType->service_options) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🚚 Service Options</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipmentType->service_options as $option)
                        <span class="service-badge">{{ $option }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Container Types -->
    @if ($shipmentType->container_types && count($shipmentType->container_types) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Container Types</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipmentType->container_types as $containerType)
                        <span class="container-badge">{{ $containerType }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Container Types</h3>
            </div>
            <div class="card-body">
                <span style="color: #64748b;">This shipment type is compatible with all container types.</span>
            </div>
        </div>
    @endif

    <!-- Handling Requirements -->
    @if ($shipmentType->handling_requirements && count($shipmentType->handling_requirements) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🏗️ Handling Requirements</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipmentType->handling_requirements as $requirement)
                        <span class="requirement-badge">{{ $requirement }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Documentation Required -->
    @if ($shipmentType->documentation_required && count($shipmentType->documentation_required) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📄 Documentation Required</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($shipmentType->documentation_required as $document)
                        <span class="document-badge">{{ $document }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Effective Dates -->
    @if ($shipmentType->effective_from || $shipmentType->effective_to)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📅 Effective Period</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    @if ($shipmentType->effective_from)
                        <div>
                            <strong>Effective From:</strong><br>
                            <span style="color: #64748b;">{{ $shipmentType->effective_from->format('M j, Y') }}</span>
                        </div>
                    @endif
                    @if ($shipmentType->effective_to)
                        <div>
                            <strong>Effective To:</strong><br>
                            <span style="color: #64748b;">{{ $shipmentType->effective_to->format('M j, Y') }}</span>
                        </div>
                    @endif
                    <div>
                        <strong>Currently Effective:</strong><br>
                        @if ($shipmentType->isEffectiveOn())
                            <span class="status-badge status-success">Yes</span>
                        @else
                            <span class="status-badge status-warning">No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Top Clients -->
    @if ($topClients->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🏆 Top Clients</h3>
            </div>
            <div class="card-body">
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Shipment Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topClients as $client)
                                <tr>
                                    <td><strong>{{ $client->name }}</strong></td>
                                    <td>{{ $client->shipment_count }}</td>
                                    <td>{{ round(($client->shipment_count / max($statistics['total_shipments'], 1)) * 100, 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                                <th>Route</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentShipments as $shipment)
                                <tr>
                                    <td><strong>{{ $shipment->shipment_id ?? 'N/A' }}</strong></td>
                                    <td>{{ $shipment->company->name ?? 'N/A' }}</td>
                                    <td>
                                        <div style="font-size: 0.875rem;">
                                            <div>{{ $shipment->originPort->port_name ?? 'N/A' }}</div>
                                            <div style="color: #64748b;">to
                                                {{ $shipment->destinationPort->port_name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status ?? 'pending')) }}">
                                            {{ $shipment->status ?? 'Pending' }}
                                        </span>
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

    <!-- Special Instructions -->
    @if ($shipmentType->special_instructions)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📋 Special Instructions</h3>
            </div>
            <div class="card-body">
                <span style="color: #64748b;">{{ $shipmentType->special_instructions }}</span>
            </div>
        </div>
    @endif

    <!-- Notes -->
    @if ($shipmentType->notes)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📝 Notes</h3>
            </div>
            <div class="card-body">
                <span style="color: #64748b;">{{ $shipmentType->notes }}</span>
            </div>
        </div>
    @endif

    <style>
        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .category-ocean-freight {
            background: #dbeafe;
            color: #1e40af;
        }

        .category-air-freight {
            background: #f0f9ff;
            color: #0369a1;
        }

        .category-land-transport {
            background: #fef3c7;
            color: #92400e;
        }

        .category-rail-transport {
            background: #e0e7ff;
            color: #3730a3;
        }

        .category-multimodal {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .category-express {
            background: #fee2e2;
            color: #991b1b;
        }

        .category-economy {
            background: #dcfce7;
            color: #166534;
        }

        .category-special-handling {
            background: #fce7f3;
            color: #be185d;
        }

        .category-project-cargo {
            background: #f8fafc;
            color: #475569;
        }

        .category-bulk-cargo {
            background: #ecfdf5;
            color: #047857;
        }

        .category-container {
            background: #d1fae5;
            color: #065f46;
        }

        .category-break-bulk {
            background: #f1f5f9;
            color: #64748b;
        }

        .cargo-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .cargo-general-cargo {
            background: #f8fafc;
            color: #475569;
        }

        .cargo-dangerous-goods {
            background: #fee2e2;
            color: #991b1b;
        }

        .cargo-refrigerated {
            background: #dbeafe;
            color: #1e40af;
        }

        .cargo-liquid-bulk {
            background: #dcfce7;
            color: #166534;
        }

        .cargo-dry-bulk {
            background: #fef3c7;
            color: #92400e;
        }

        .cargo-vehicles {
            background: #e0e7ff;
            color: #3730a3;
        }

        .cargo-heavy-machinery {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .cargo-electronics {
            background: #ecfdf5;
            color: #047857;
        }

        .cargo-pharmaceuticals {
            background: #fce7f3;
            color: #be185d;
        }

        .transit-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .transit-sea {
            background: #dbeafe;
            color: #1e40af;
        }

        .transit-air {
            background: #f0f9ff;
            color: #0369a1;
        }

        .transit-road {
            background: #fef3c7;
            color: #92400e;
        }

        .transit-rail {
            background: #e0e7ff;
            color: #3730a3;
        }

        .transit-multimodal {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .priority-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .priority-low {
            background: #dcfce7;
            color: #166534;
        }

        .priority-standard {
            background: #fef3c7;
            color: #92400e;
        }

        .priority-high {
            background: #fed7aa;
            color: #9a3412;
        }

        .priority-urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .priority-critical {
            background: #f3f4f6;
            color: #374151;
        }

        .feature-badge {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #fecaca;
        }

        .service-badge {
            background: #dcfce7;
            color: #166534;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #bbf7d0;
        }

        .container-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #0ea5e9;
        }

        .requirement-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #f59e0b;
        }

        .document-badge {
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

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
@endsection
