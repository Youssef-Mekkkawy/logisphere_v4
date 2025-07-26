@extends('layouts.app')

@section('title', $shipment->shipment_id . ' - LogiFlow')
@section('page-title', 'Shipment Details')

@section('content')
    <div style="margin-bottom: 30px;">
        <a href="{{ route('management.shipments.index') }}" class="btn btn-secondary">← Back to Shipments</a>

        @if (auth()->user()->hasAnyRole(['admin', 'manager']))
            <a href="{{ route('management.shipments.edit', $shipment) }}" class="btn btn-primary"
                style="margin-left: 10px;">Edit Shipment</a>
        @endif

        <a href="{{ route('management.api.shipments.tracking', $shipment->shipment_id) }}" class="btn btn-primary"
            style="margin-left: 10px;">Track Shipment</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main Shipment Info -->
        <div>
            <!-- Header Card -->
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <h2 style="color: #1e40af; margin: 0; font-family: monospace;">{{ $shipment->shipment_id }}</h2>
                        <p style="color: #6b7280; margin: 5px 0 0 0;">Created {{ $shipment->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}"
                        style="font-size: 14px; padding: 8px 16px;">
                        {{ $shipment->status }}
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Company</label>
                        <p style="margin: 0; color: #1e40af; font-weight: 500;">{{ $shipment->company->name }}</p>
                        <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $shipment->company->type }}</p>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Container
                            Type</label>
                        <p style="margin: 0;">{{ $shipment->container_type ?: 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Route Information -->
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <h3 style="color: #1e40af; margin-bottom: 20px;">Route Information</h3>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div style="text-align: center; flex: 1;">
                        <div style="font-size: 24px; margin-bottom: 10px;">🚢</div>
                        <h4 style="color: #1e40af; margin: 0;">{{ $shipment->originPort->name }}</h4>
                        <p style="color: #6b7280; margin: 5px 0;">{{ $shipment->originPort->city }},
                            {{ $shipment->originPort->country }}</p>
                        <small style="color: #9ca3af;">Origin Port</small>
                    </div>

                    <div style="flex: 0 0 100px; text-align: center;">
                        <div style="height: 2px; background: #e5e7eb; position: relative; margin: 20px 0;">
                            <div
                                style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: white; padding: 0 10px; color: #6b7280;">
                                →</div>
                        </div>
                    </div>

                    <div style="text-align: center; flex: 1;">
                        <div style="font-size: 24px; margin-bottom: 10px;">🏗️</div>
                        <h4 style="color: #1e40af; margin: 0;">{{ $shipment->destinationPort->name }}</h4>
                        <p style="color: #6b7280; margin: 5px 0;">{{ $shipment->destinationPort->city }},
                            {{ $shipment->destinationPort->country }}</p>
                        <small style="color: #9ca3af;">Destination Port</small>
                    </div>
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #f3f4f6;">
                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Shipping
                            Date</label>
                        <p style="margin: 0;">
                            {{ $shipment->shipping_date ? $shipment->shipping_date->format('M d, Y') : 'Not set' }}</p>
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Estimated
                            Arrival</label>
                        <p style="margin: 0;">{{ $shipment->eta ? $shipment->eta->format('M d, Y') : 'Not set' }}</p>
                    </div>

                    @if ($shipment->shipping_date && $shipment->eta)
                        <div>
                            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Transit
                                Time</label>
                            <p style="margin: 0;">{{ $shipment->shipping_date->diffInDays($shipment->eta) }} days</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cargo Details -->
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <h3 style="color: #1e40af; margin-bottom: 20px;">Cargo Details</h3>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    @if ($shipment->weight)
                        <div>
                            <label
                                style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Weight</label>
                            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #1e40af;">
                                {{ number_format($shipment->weight, 2) }} kg</p>
                        </div>
                    @endif

                    @if ($shipment->volume)
                        <div>
                            <label
                                style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Volume</label>
                            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #1e40af;">
                                {{ number_format($shipment->volume, 2) }} m³</p>
                        </div>
                    @endif

                    @if ($shipment->freight_cost)
                        <div>
                            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Freight
                                Cost</label>
                            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #059669;">
                                ${{ number_format($shipment->freight_cost, 2) }}</p>
                        </div>
                    @endif
                </div>

                @if ($shipment->cargo_description)
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 8px;">Cargo
                            Description</label>
                        <p style="margin: 0; padding: 15px; background: #f8fafc; border-radius: 8px; line-height: 1.6;">
                            {{ $shipment->cargo_description }}</p>
                    </div>
                @endif

                @if ($shipment->special_instructions)
                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 8px;">Special
                            Instructions</label>
                        <p
                            style="margin: 0; padding: 15px; background: #fef3c7; border-radius: 8px; line-height: 1.6; border: 1px solid #fbbf24;">
                            {{ $shipment->special_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Quick Actions -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('management.api.shipments.tracking', $shipment->shipment_id) }}" class="btn btn-primary"
                        style="justify-content: center;">
                        📍 Track Shipment
                    </a>

                    @if (auth()->user()->hasAnyRole(['admin', 'manager']))
                        <a href="{{ route('management.shipments.edit', $shipment) }}" class="btn btn-secondary"
                            style="justify-content: center;">
                            ✏️ Edit Details
                        </a>
                    @endif

                    <button class="btn btn-secondary" style="justify-content: center;" onclick="window.print()">
                        🖨️ Print Details
                    </button>
                </div>
            </div>

            <!-- Company Info -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Company Information</h4>

                <div style="margin-bottom: 15px;">
                    <strong>{{ $shipment->company->name }}</strong>
                    <br><small style="color: #6b7280;">{{ $shipment->company->type }}</small>
                </div>

                @if ($shipment->company->contact_person)
                    <div style="margin-bottom: 10px;">
                        <strong style="font-size: 14px;">Contact Person:</strong><br>
                        <span style="color: #6b7280;">{{ $shipment->company->contact_person }}</span>
                    </div>
                @endif

                @if ($shipment->company->email)
                    <div style="margin-bottom: 10px;">
                        <strong style="font-size: 14px;">Email:</strong><br>
                        <a href="mailto:{{ $shipment->company->email }}"
                            style="color: #1e40af;">{{ $shipment->company->email }}</a>
                    </div>
                @endif

                @if ($shipment->company->phone)
                    <div style="margin-bottom: 10px;">
                        <strong style="font-size: 14px;">Phone:</strong><br>
                        <a href="tel:{{ $shipment->company->phone }}"
                            style="color: #1e40af;">{{ $shipment->company->phone }}</a>
                    </div>
                @endif

                <a href="{{ route('management.companies.show', $shipment->company) }}" class="btn btn-secondary btn-sm"
                    style="width: 100%; justify-content: center; margin-top: 15px;">
                    View Company Details
                </a>
            </div>

            <!-- Timeline -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Shipment Timeline</h4>

                <div style="position: relative;">
                    <!-- Timeline items -->
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; background: #059669; margin-right: 15px; flex-shrink: 0;">
                        </div>
                        <div>
                            <strong style="font-size: 14px;">Shipment Created</strong><br>
                            <small style="color: #6b7280;">{{ $shipment->created_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>

                    @if ($shipment->shipping_date)
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div
                                style="width: 12px; height: 12px; border-radius: 50%; background: {{ $shipment->shipping_date->isPast() ? '#059669' : '#e5e7eb' }}; margin-right: 15px; flex-shrink: 0;">
                            </div>
                            <div>
                                <strong style="font-size: 14px;">Shipping Date</strong><br>
                                <small style="color: #6b7280;">{{ $shipment->shipping_date->format('M d, Y') }}</small>
                            </div>
                        </div>
                    @endif

                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; background: {{ in_array($shipment->status, ['In Transit', 'At Port', 'Delivered']) ? '#059669' : '#e5e7eb' }}; margin-right: 15px; flex-shrink: 0;">
                        </div>
                        <div>
                            <strong style="font-size: 14px;">In Transit</strong><br>
                            <small
                                style="color: #6b7280;">{{ $shipment->status == 'In Transit' ? 'Current Status' : 'Pending' }}</small>
                        </div>
                    </div>

                    @if ($shipment->eta)
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div
                                style="width: 12px; height: 12px; border-radius: 50%; background: {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }}; margin-right: 15px; flex-shrink: 0;">
                            </div>
                            <div>
                                <strong style="font-size: 14px;">Expected Arrival</strong><br>
                                <small style="color: #6b7280;">{{ $shipment->eta->format('M d, Y') }}</small>
                            </div>
                        </div>
                    @endif

                    <div style="display: flex; align-items: center;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; background: {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }}; margin-right: 15px; flex-shrink: 0;">
                        </div>
                        <div>
                            <strong style="font-size: 14px;">Delivered</strong><br>
                            <small
                                style="color: #6b7280;">{{ $shipment->status == 'Delivered' ? 'Completed' : 'Pending' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Information -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Audit Information</h4>

                <div style="font-size: 14px;">
                    <div style="margin-bottom: 10px;">
                        <strong>Created:</strong><br>
                        <span style="color: #6b7280;">{{ $shipment->created_at->format('M d, Y H:i') }}</span>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <strong>Last Updated:</strong><br>
                        <span style="color: #6b7280;">{{ $shipment->updated_at->format('M d, Y H:i') }}</span>
                        @if ($shipment->updated_at != $shipment->created_at)
                            <br><small style="color: #9ca3af;">({{ $shipment->updated_at->diffForHumans() }})</small>
                        @endif
                    </div>

                    <div>
                        <strong>Record ID:</strong><br>
                        <span style="color: #6b7280; font-family: monospace;">#{{ $shipment->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {

            .sidebar,
            .header,
            .btn,
            nav {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .content {
                padding: 0 !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
@endsection
