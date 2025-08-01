@extends('layouts.app')

@section('title', 'Shipping Agency Details')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $shippingAgency->name }}
        </h1>
        <p style="color: #64748b;">Shipping Agency Details - {{ $shippingAgency->code }}</p>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Agency Information -->
        <div class="card">
            <div class="card-header">
                <h3>Agency Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>Agency Code:</strong><br>
                        <span style="color: #64748b;">{{ $shippingAgency->code }}</span>
                    </div>
                    <div>
                        <strong>Agency Name:</strong><br>
                        <span style="color: #64748b;">{{ $shippingAgency->name }}</span>
                    </div>
                    <div>
                        <strong>Country:</strong><br>
                        <span style="color: #64748b;">{{ $shippingAgency->country->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <strong>Service Type:</strong><br>
                        <span
                            class="service-badge service-{{ strtolower(str_replace(' ', '-', $shippingAgency->service_type)) }}">
                            {{ $shippingAgency->service_type }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower($shippingAgency->status) }}">{{ $shippingAgency->status }}</span>
                    </div>
                    <div>
                        <strong>Contact Person:</strong><br>
                        <span style="color: #64748b;">{{ $shippingAgency->contact_person ?? 'N/A' }}</span>
                    </div>
                </div>

                <div
                    style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>Email:</strong><br>
                        <span style="color: #64748b;">
                            @if ($shippingAgency->email)
                                <a href="mailto:{{ $shippingAgency->email }}"
                                    style="color: var(--primary-color);">{{ $shippingAgency->email }}</a>
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div>
                        <strong>Phone:</strong><br>
                        <span style="color: #64748b;">
                            @if ($shippingAgency->phone)
                                <a href="tel:{{ $shippingAgency->phone }}"
                                    style="color: var(--primary-color);">{{ $shippingAgency->phone }}</a>
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>

                @if ($shippingAgency->address)
                    <div style="margin-top: 1.5rem;">
                        <strong>Address:</strong><br>
                        <p style="color: #64748b; margin-top: 0.5rem;">{{ $shippingAgency->address }}</p>
                    </div>
                @endif

                @if ($shippingAgency->services_offered)
                    <div style="margin-top: 1.5rem;">
                        <strong>Services Offered:</strong><br>
                        <p style="color: #64748b; margin-top: 0.5rem;">{{ $shippingAgency->services_offered }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Agency Statistics -->
        <div class="card">
            <div class="card-header">
                <h3>Agency Statistics</h3>
            </div>
            <div class="card-body">
                <div style="space-y: 1rem;">
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Total Shipments</span>
                        <span style="font-weight: 600;">{{ $statistics['total_shipments'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Active Shipments</span>
                        <span style="font-weight: 600;">{{ $activeShipments ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Completed Shipments</span>
                        <span style="font-weight: 600;">{{ $statistics['completed_shipments'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>This Month</span>
                        <span style="font-weight: 600;">{{ $statistics['this_month_shipments'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Last Shipment</span>
                        <span style="font-weight: 600;">
                            {{ $statistics['last_shipment_date'] ? $statistics['last_shipment_date']->format('M d, Y') : 'Never' }}
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                        <span>Partner Since</span>
                        <span style="font-weight: 600;">{{ $shippingAgency->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments -->
    @if (isset($recentShipments) && $recentShipments->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>Recent Shipments</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Shipment ID</th>
                            <th>Company</th>
                            <th>Route</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentShipments as $shipment)
                            <tr>
                                <td>
                                    <a href="{{ route('management.shipments.show', $shipment) }}"
                                        style="color: var(--primary-color); text-decoration: none;">
                                        <strong>{{ $shipment->shipment_id }}</strong>
                                    </a>
                                </td>
                                <td>{{ $shipment->company->name ?? 'N/A' }}</td>
                                <td>{{ $shipment->originPort->name ?? 'N/A' }} →
                                    {{ $shipment->destinationPort->name ?? 'N/A' }}</td>
                                <td>{{ $shipment->shipping_date ? $shipment->shipping_date->format('M d, Y') : $shipment->created_at->format('M d, Y') }}
                                </td>
                                <td><span
                                        class="status-badge status-{{ strtolower(str_replace(' ', '', $shipment->status)) }}">{{ $shipment->status }}</span>
                                </td>
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
    @endif

    <!-- Actions -->
    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
        <a href="{{ route('logistics.shipping-agencies.edit' ?? '', $shippingAgency) }}" class="btn btn-primary">Edit
            Agency</a>
        <a href="{{ route('logistics.shipping-agencies.index') ?? '' }}" class="btn btn-secondary">Back to Agencies</a>
        @if (auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
            <form action="{{ route('logistics.shipping-agencies.destroy' ?? '', $shippingAgency) }}" method="POST"
                style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this agency?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Agency</button>
            </form>
        @endif
    </div>

    <style>
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color, #3b82f6), var(--primary-dark, #1e40af));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            color: white;
        }

        .btn-outline {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-outline:hover {
            background: var(--primary-color, #3b82f6);
            color: white;
            border-color: var(--primary-color, #3b82f6);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-intransit {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .service-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .service-ocean-freight {
            background: #dbeafe;
            color: #1e40af;
        }

        .service-air-freight {
            background: #fef3c7;
            color: #92400e;
        }

        .service-land-transport {
            background: #d1fae5;
            color: #065f46;
        }

        .service-full-service {
            background: #ede9fe;
            color: #5b21b6;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: #f8fafc;
        }
    </style>
@endsection
