@extends('layouts.app')

@section('title', 'Service Details - ' . $service->service_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $service->service_name }}
                </h1>
                <p style="color: #64748b;">Service Details - {{ $service->service_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('logistics.services.edit', $service) }}" class="btn btn-primary">Edit Service</a>
                <a href="{{ route('logistics.services.index') }}" class="btn btn-secondary">Back to List</a>
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
                        <strong>Service Code:</strong><br>
                        <span style="color: #64748b;">{{ $service->service_code }}</span>
                    </div>
                    <div>
                        <strong>Service Name:</strong><br>
                        <span style="color: #64748b;">{{ $service->service_name }}</span>
                    </div>
                    <div>
                        <strong>Category:</strong><br>
                        <span
                            class="service-category-badge service-category-{{ strtolower(str_replace(' ', '-', $service->service_category)) }}">
                            {{ $service->service_category_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Provider:</strong><br>
                        <span class="provider-badge provider-{{ strtolower($service->service_provider) }}">
                            {{ $service->service_provider_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span class="status-badge {{ $service->status_badge }}">{{ $service->status }}</span>
                    </div>
                    <div>
                        <strong>Mandatory:</strong><br>
                        @if ($service->is_mandatory)
                            <span class="status-badge status-danger">Required</span>
                        @else
                            <span class="status-badge status-secondary">Optional</span>
                        @endif
                    </div>
                </div>

                @if ($service->description)
                    <div style="margin-top: 1.5rem;">
                        <strong>Description:</strong><br>
                        <span style="color: #64748b;">{{ $service->description }}</span>
                    </div>
                @endif

                @if ($service->detailed_description)
                    <div style="margin-top: 1rem;">
                        <strong>Detailed Description:</strong><br>
                        <span style="color: #64748b;">{{ $service->detailed_description }}</span>
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
                            {{ $statistics['total_usage'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Total Usage</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #059669;">
                            {{ $statistics['monthly_usage'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">This Month</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #0ea5e9;">
                            ${{ number_format($statistics['total_revenue'], 2) }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Total Revenue</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">
                            ${{ number_format($statistics['average_rate'], 2) }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Average Rate</div>
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

    <!-- Billing Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>💰 Billing Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Billing Type:</strong><br>
                    <span class="billing-type-badge billing-type-{{ strtolower($service->billing_type) }}">
                        {{ $service->billing_type_display }}
                    </span>
                </div>
                <div>
                    <strong>Base Rate:</strong><br>
                    <span style="color: #059669; font-weight: 600;">{{ $service->formatted_rate }}</span>
                </div>
                @if ($service->minimum_charge)
                    <div>
                        <strong>Minimum Charge:</strong><br>
                        <span style="color: #64748b;">{{ $service->rate_currency }}
                            {{ number_format($service->minimum_charge, 2) }}</span>
                    </div>
                @endif
                @if ($service->maximum_charge)
                    <div>
                        <strong>Maximum Charge:</strong><br>
                        <span style="color: #64748b;">{{ $service->rate_currency }}
                            {{ number_format($service->maximum_charge, 2) }}</span>
                    </div>
                @endif
                @if ($service->tax_type)
                    <div>
                        <strong>Tax:</strong><br>
                        <span style="color: #64748b;">{{ $service->tax_type }} ({{ $service->tax_percentage }}%)</span>
                    </div>
                @endif
                <div>
                    <strong>Billable:</strong><br>
                    @if ($service->is_billable)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Service Configuration -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>⚙️ Service Configuration</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                @if ($service->estimated_duration_hours)
                    <div>
                        <strong>Estimated Duration:</strong><br>
                        <span style="color: #64748b;">{{ $service->estimated_duration_display }}</span>
                    </div>
                @endif
                <div>
                    <strong>Requires Approval:</strong><br>
                    @if ($service->requires_approval)
                        <span class="status-badge status-warning">Yes</span>
                    @else
                        <span class="status-badge status-success">No</span>
                    @endif
                </div>
                @if ($service->effective_from || $service->effective_to)
                    <div>
                        <strong>Effective Period:</strong><br>
                        <span style="color: #64748b;">
                            @if ($service->effective_from && $service->effective_to)
                                {{ $service->effective_from->format('M j, Y') }} -
                                {{ $service->effective_to->format('M j, Y') }}
                            @elseif ($service->effective_from)
                                From {{ $service->effective_from->format('M j, Y') }}
                            @elseif ($service->effective_to)
                                Until {{ $service->effective_to->format('M j, Y') }}
                            @endif
                        </span>
                    </div>
                @endif
                @if ($service->account)
                    <div>
                        <strong>GL Account:</strong><br>
                        <span style="color: #64748b;">{{ $service->account->name }}</span>
                    </div>
                @endif
            </div>

            @if ($service->service_conditions)
                <div style="margin-top: 1.5rem;">
                    <strong>Service Conditions:</strong><br>
                    <span style="color: #64748b;">{{ $service->service_conditions }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Required Documents -->
    @if ($service->required_documents && count($service->required_documents) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📄 Required Documents</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($service->required_documents as $document)
                        <span class="document-badge">{{ $document }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Applicable Cargo Types -->
    @if ($service->applicable_cargo_types && count($service->applicable_cargo_types) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Applicable Cargo Types</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($service->applicable_cargo_types as $cargoType)
                        <span class="cargo-badge">{{ $cargoType }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Applicable Cargo Types</h3>
            </div>
            <div class="card-body">
                <span style="color: #64748b;">This service is applicable to all cargo types.</span>
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
                                <th>Usage Count</th>
                                <th>Total Revenue</th>
                                <th>Avg. per Use</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topClients as $client)
                                <tr>
                                    <td><strong>{{ $client->name }}</strong></td>
                                    <td>{{ $client->usage_count }}</td>
                                    <td>${{ number_format($client->total_revenue, 2) }}</td>
                                    <td>${{ number_format($client->total_revenue / $client->usage_count, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Usage -->
    @if ($recentUsage->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🕒 Recent Usage</h3>
            </div>
            <div class="card-body">
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Shipment ID</th>
                                <th>Company</th>
                                <th>Charged Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentUsage as $usage)
                                <tr>
                                    <td><strong>{{ $usage->shipment->shipment_id ?? 'N/A' }}</strong></td>
                                    <td>{{ $usage->shipment->company->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($usage->charged_amount)
                                            ${{ number_format($usage->charged_amount, 2) }}
                                        @else
                                            <span style="color: #64748b;">Not charged</span>
                                        @endif
                                    </td>
                                    <td>{{ $usage->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $usage->status ?? 'pending')) }}">
                                            {{ $usage->status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($usage->shipment)
                                            <a href="{{ route('shipments.show', $usage->shipment) }}"
                                                class="btn btn-outline"
                                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View Shipment</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($service->notes)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📝 Notes</h3>
            </div>
            <div class="card-body">
                <span style="color: #64748b;">{{ $service->notes }}</span>
            </div>
        </div>
    @endif

    <style>
        .service-category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .service-category-customs-clearance {
            background: #dbeafe;
            color: #1e40af;
        }

        .service-category-transportation {
            background: #fef3c7;
            color: #92400e;
        }

        .service-category-warehousing {
            background: #e0e7ff;
            color: #3730a3;
        }

        .service-category-documentation {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .service-category-insurance {
            background: #ecfdf5;
            color: #047857;
        }

        .service-category-inspection {
            background: #fce7f3;
            color: #be185d;
        }

        .service-category-cargo-handling {
            background: #d1fae5;
            color: #065f46;
        }

        .service-category-port-services {
            background: #f0f9ff;
            color: #0369a1;
        }

        .service-category-freight-forwarding {
            background: #fee2e2;
            color: #991b1b;
        }

        .service-category-consulting {
            background: #f8fafc;
            color: #475569;
        }

        .service-category-other {
            background: #f1f5f9;
            color: #64748b;
        }

        .billing-type-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .billing-type-fixed {
            background: #dcfce7;
            color: #166534;
        }

        .billing-type-variable {
            background: #dbeafe;
            color: #1e40af;
        }

        .billing-type-percentage {
            background: #fef3c7;
            color: #92400e;
        }

        .billing-type-hourly {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .billing-type-per.unit {
            background: #e0e7ff;
            color: #3730a3;
        }

        .billing-type-tiered {
            background: #fce7f3;
            color: #be185d;
        }

        .provider-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .provider-internal {
            background: #dcfce7;
            color: #166534;
        }

        .provider-external {
            background: #dbeafe;
            color: #1e40af;
        }

        .provider-both {
            background: #fef3c7;
            color: #92400e;
        }

        .document-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #0ea5e9;
        }

        .cargo-badge {
            background: #ecfdf5;
            color: #047857;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #10b981;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
@endsection
