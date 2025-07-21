@extends('layouts.app')

@section('title', 'Party Details - ' . $consigneeNotify->party_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $consigneeNotify->party_name }}
                </h1>
                <p style="color: #64748b;">Party Details - {{ $consigneeNotify->party_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('submenu.consignee-notify.edit', $consigneeNotify) }}" class="btn btn-primary">Edit
                    Party</a>
                <a href="{{ route('submenu.consignee-notify.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Party Information -->
        <div class="card">
            <div class="card-header">
                <h3>📋 Party Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>Party Code:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->party_code }}</span>
                    </div>
                    <div>
                        <strong>Party Name:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->party_name }}</span>
                    </div>
                    <div>
                        <strong>Party Type:</strong><br>
                        <span
                            class="party-badge party-{{ strtolower(str_replace(' ', '-', $consigneeNotify->party_type)) }}">
                            {{ $consigneeNotify->party_type }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower($consigneeNotify->status) }}">{{ $consigneeNotify->status }}</span>
                    </div>
                    @if ($consigneeNotify->company_registration)
                        <div>
                            <strong>Company Registration:</strong><br>
                            <span style="color: #64748b;">{{ $consigneeNotify->company_registration }}</span>
                        </div>
                    @endif
                    @if ($consigneeNotify->tax_id)
                        <div>
                            <strong>Tax ID:</strong><br>
                            <span style="color: #64748b;">{{ $consigneeNotify->tax_id }}</span>
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
                            ${{ number_format($statistics['total_value'], 2) }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Total Value</div>
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
                    <span style="color: #64748b;">{{ $consigneeNotify->contact_person }}</span>
                </div>
                <div>
                    <strong>Email:</strong><br>
                    <a href="mailto:{{ $consigneeNotify->email }}"
                        style="color: var(--primary-color);">{{ $consigneeNotify->email }}</a>
                </div>
                <div>
                    <strong>Phone:</strong><br>
                    <a href="tel:{{ $consigneeNotify->phone }}"
                        style="color: var(--primary-color);">{{ $consigneeNotify->phone }}</a>
                </div>
                @if ($consigneeNotify->fax)
                    <div>
                        <strong>Fax:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->fax }}</span>
                    </div>
                @endif
                <div>
                    <strong>Preferred Language:</strong><br>
                    <span style="color: #64748b;">{{ strtoupper($consigneeNotify->preferred_language) }}</span>
                </div>
                @if ($consigneeNotify->notification_preferences)
                    <div>
                        <strong>Notification Preferences:</strong><br>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                            @foreach ($consigneeNotify->notification_preferences as $pref)
                                <span class="notification-badge">{{ ucfirst($pref) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>📍 Address Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <div>
                    <strong>Full Address:</strong><br>
                    <span style="color: #64748b;">{{ $consigneeNotify->formatted_address }}</span>
                </div>
                <div>
                    @if ($consigneeNotify->country)
                        <strong>Country:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->country->name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Business Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>💼 Business Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                @if ($consigneeNotify->credit_rating)
                    <div>
                        <strong>Credit Rating:</strong><br>
                        <span class="credit-badge credit-{{ strtolower($consigneeNotify->credit_rating) }}">
                            {{ $consigneeNotify->credit_rating }} - {{ $consigneeNotify->credit_status }}
                        </span>
                    </div>
                @endif
                @if ($consigneeNotify->credit_limit)
                    <div>
                        <strong>Credit Limit:</strong><br>
                        <span style="color: #64748b;">${{ number_format($consigneeNotify->credit_limit, 2) }}</span>
                    </div>
                @endif
                @if ($consigneeNotify->payment_terms)
                    <div>
                        <strong>Payment Terms:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->payment_terms }}</span>
                    </div>
                @endif
                <div>
                    <strong>Requires Original Docs:</strong><br>
                    @if ($consigneeNotify->requires_original_docs)
                        <span class="status-badge status-warning">Yes</span>
                    @else
                        <span class="status-badge status-info">No</span>
                    @endif
                </div>
                <div>
                    <strong>Freight Forwarder:</strong><br>
                    @if ($consigneeNotify->is_freight_forwarder)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-info">No</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Special Instructions -->
    @if ($consigneeNotify->delivery_instructions || $consigneeNotify->special_requirements || $consigneeNotify->notes)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📝 Special Instructions & Notes</h3>
            </div>
            <div class="card-body">
                @if ($consigneeNotify->delivery_instructions)
                    <div style="margin-bottom: 1.5rem;">
                        <strong>Delivery Instructions:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->delivery_instructions }}</span>
                    </div>
                @endif
                @if ($consigneeNotify->special_requirements)
                    <div style="margin-bottom: 1.5rem;">
                        <strong>Special Requirements:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->special_requirements }}</span>
                    </div>
                @endif
                @if ($consigneeNotify->notes)
                    <div>
                        <strong>Notes:</strong><br>
                        <span style="color: #64748b;">{{ $consigneeNotify->notes }}</span>
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
                                <th>Route</th>
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
                                    <td>{{ $shipment->originPort->name ?? 'N/A' }} →
                                        {{ $shipment->destinationPort->name ?? 'N/A' }}</td>
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
        .party-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .party-consignee {
            background: #dbeafe;
            color: #1e40af;
        }

        .party-notify-party {
            background: #fef3c7;
            color: #92400e;
        }

        .party-both {
            background: #d1fae5;
            color: #065f46;
        }

        .credit-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .credit-a {
            background: #dcfce7;
            color: #166534;
        }

        .credit-b {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .credit-c {
            background: #fef3c7;
            color: #92400e;
        }

        .credit-d {
            background: #fee2e2;
            color: #991b1b;
        }

        .notification-badge {
            background: #e0f2fe;
            color: #0277bd;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-info {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }
    </style>
@endsection
