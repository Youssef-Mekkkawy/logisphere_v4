@extends('layouts.app')

@section('title', 'Track Shipment - LogiFlow')
@section('page-title', 'Track Shipment: ' . $shipment->shipment_id)

@section('content')
    <div style="margin-bottom: 30px;">
        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">← Back to Shipments</a>
        <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-primary" style="margin-left: 10px;">View Details</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 30px;">
        <!-- Main Tracking Info -->
        <div>
            <!-- Shipment Header -->
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div>
                        <h2 style="color: #1e40af; margin: 0; font-family: monospace;">{{ $shipment->shipment_id }}</h2>
                        <p style="color: #6b7280; margin: 5px 0 0 0;">{{ $shipment->company->name }}</p>
                    </div>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}"
                        style="font-size: 16px; padding: 10px 20px;">
                        {{ $shipment->status }}
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Container
                            Type</label>
                        <p style="margin: 0;">{{ $shipment->container_type }}</p>
                    </div>

                    @if ($shipment->freight_cost)
                        <div>
                            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 5px;">Freight
                                Cost</label>
                            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #059669;">
                                ${{ number_format($shipment->freight_cost, 2) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Route Tracking -->
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <h3 style="color: #1e40af; margin-bottom: 30px;">Route Tracking</h3>

                <div style="position: relative; padding: 20px 0;">
                    <!-- Progress Line -->
                    <div
                        style="position: absolute; top: 50%; left: 0; right: 0; height: 4px; background: #e5e7eb; border-radius: 2px; transform: translateY(-50%);">
                    </div>

                    @php
                        $progress = 0;
                        switch ($shipment->status) {
                            case 'Pending':
                                $progress = 10;
                                break;
                            case 'In Transit':
                                $progress = 50;
                                break;
                            case 'At Port':
                                $progress = 80;
                                break;
                            case 'Delivered':
                                $progress = 100;
                                break;
                            case 'Cancelled':
                                $progress = 0;
                                break;
                        }
                    @endphp

                    <div
                        style="position: absolute; top: 50%; left: 0; height: 4px; background: {{ $shipment->status == 'Cancelled' ? '#dc2626' : '#059669' }}; border-radius: 2px; transform: translateY(-50%); width: {{ $progress }}%; transition: width 0.5s ease;">
                    </div>

                    <!-- Route Points -->
                    <div style="display: flex; justify-content: space-between; position: relative;">
                        <!-- Origin -->
                        <div style="text-align: center; flex: 1; position: relative;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 50%; background: #059669; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; position: relative; z-index: 2;">
                                🚢
                            </div>
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">
                                {{ $shipment->originPort->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $shipment->originPort->country }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">Origin Port</div>
                        </div>

                        <!-- Current Status -->
                        <div style="text-align: center; flex: 1; position: relative;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 50%; background: {{ in_array($shipment->status, ['In Transit', 'At Port', 'Delivered']) ? '#059669' : '#e5e7eb' }}; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; position: relative; z-index: 2;">
                                @if ($shipment->status == 'In Transit')
                                    🚛
                                @elseif($shipment->status == 'At Port')
                                    🏗️
                                @elseif($shipment->status == 'Delivered')
                                    ✅
                                @else
                                    📦
                                @endif
                            </div>
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">{{ $shipment->status }}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">Current Status</div>
                        </div>

                        <!-- Destination -->
                        <div style="text-align: center; flex: 1; position: relative;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 50%; background: {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }}; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; position: relative; z-index: 2;">
                                🏗️
                            </div>
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">
                                {{ $shipment->destinationPort->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $shipment->destinationPort->country }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">Destination Port</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Timeline -->
            <div
                style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 30px;">
                <h3 style="color: #1e40af; margin-bottom: 20px;">Detailed Timeline</h3>

                <div style="position: relative; padding-left: 30px;">
                    <!-- Timeline line -->
                    <div style="position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background: #e5e7eb;"></div>

                    <!-- Timeline events -->
                    <div style="position: relative; margin-bottom: 25px;">
                        <div
                            style="position: absolute; left: -23px; width: 16px; height: 16px; border-radius: 50%; background: #059669; border: 3px solid white; box-shadow: 0 0 0 2px #059669;">
                        </div>
                        <div
                            style="background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid #059669;">
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">Shipment Created</div>
                            <div style="font-size: 14px; color: #6b7280;">{{ $shipment->created_at->format('M d, Y H:i') }}
                            </div>
                            <div style="font-size: 12px; color: #9ca3af; margin-top: 5px;">Shipment record created in the
                                system</div>
                        </div>
                    </div>

                    @if ($shipment->shipping_date)
                        <div style="position: relative; margin-bottom: 25px;">
                            <div
                                style="position: absolute; left: -23px; width: 16px; height: 16px; border-radius: 50%; background: {{ $shipment->shipping_date->isPast() ? '#059669' : '#e5e7eb' }}; border: 3px solid white; box-shadow: 0 0 0 2px {{ $shipment->shipping_date->isPast() ? '#059669' : '#e5e7eb' }};">
                            </div>
                            <div
                                style="background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid {{ $shipment->shipping_date->isPast() ? '#059669' : '#e5e7eb' }};">
                                <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">Shipped from Origin</div>
                                <div style="font-size: 14px; color: #6b7280;">
                                    {{ $shipment->shipping_date->format('M d, Y') }}</div>
                                <div style="font-size: 12px; color: #9ca3af; margin-top: 5px;">Departed from
                                    {{ $shipment->originPort->name }}</div>
                            </div>
                        </div>
                    @endif

                    <div style="position: relative; margin-bottom: 25px;">
                        <div
                            style="position: absolute; left: -23px; width: 16px; height: 16px; border-radius: 50%; background: {{ in_array($shipment->status, ['In Transit', 'At Port', 'Delivered']) ? '#059669' : '#e5e7eb' }}; border: 3px solid white; box-shadow: 0 0 0 2px {{ in_array($shipment->status, ['In Transit', 'At Port', 'Delivered']) ? '#059669' : '#e5e7eb' }};">
                        </div>
                        <div
                            style="background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid {{ in_array($shipment->status, ['In Transit', 'At Port', 'Delivered']) ? '#059669' : '#e5e7eb' }};">
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">In Transit</div>
                            <div style="font-size: 14px; color: #6b7280;">
                                {{ $shipment->status == 'In Transit' ? 'Current Status' : ($shipment->status == 'Pending' ? 'Pending' : 'Completed') }}
                            </div>
                            <div style="font-size: 12px; color: #9ca3af; margin-top: 5px;">Cargo is being transported</div>
                        </div>
                    </div>

                    @if ($shipment->eta)
                        <div style="position: relative; margin-bottom: 25px;">
                            <div
                                style="position: absolute; left: -23px; width: 16px; height: 16px; border-radius: 50%; background: {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }}; border: 3px solid white; box-shadow: 0 0 0 2px {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }};">
                            </div>
                            <div
                                style="background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }};">
                                <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">Expected Arrival</div>
                                <div style="font-size: 14px; color: #6b7280;">{{ $shipment->eta->format('M d, Y') }}</div>
                                <div style="font-size: 12px; color: #9ca3af; margin-top: 5px;">Expected arrival at
                                    {{ $shipment->destinationPort->name }}</div>
                            </div>
                        </div>
                    @endif

                    <div style="position: relative;">
                        <div
                            style="position: absolute; left: -23px; width: 16px; height: 16px; border-radius: 50%; background: {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }}; border: 3px solid white; box-shadow: 0 0 0 2px {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }};">
                        </div>
                        <div
                            style="background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid {{ $shipment->status == 'Delivered' ? '#059669' : '#e5e7eb' }};">
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">Delivered</div>
                            <div style="font-size: 14px; color: #6b7280;">
                                {{ $shipment->status == 'Delivered' ? 'Completed' : 'Pending' }}</div>
                            <div style="font-size: 12px; color: #9ca3af; margin-top: 5px;">Cargo delivered to destination
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Status Summary -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Status Summary</h4>

                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 48px; margin-bottom: 10px;">
                        @if ($shipment->status == 'Pending')
                            ⏳
                        @elseif($shipment->status == 'In Transit')
                            🚛
                        @elseif($shipment->status == 'At Port')
                            🏗️
                        @elseif($shipment->status == 'Delivered')
                            ✅
                        @else
                            ❌
                        @endif
                    </div>
                    <div style="font-size: 18px; font-weight: 600; color: #1e40af; margin-bottom: 5px;">
                        {{ $shipment->status }}</div>
                    <div style="font-size: 14px; color: #6b7280;">Current Status</div>
                </div>

                @if ($shipment->shipping_date && $shipment->eta)
                    <div
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 12px; margin-bottom: 15px;">
                        <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 6px;">
                            <div style="font-weight: 600; color: #1e40af;">{{ $shipment->shipping_date->format('M d') }}
                            </div>
                            <div style="color: #6b7280;">Shipped</div>
                        </div>
                        <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 6px;">
                            <div style="font-weight: 600; color: #1e40af;">{{ $shipment->eta->format('M d') }}</div>
                            <div style="color: #6b7280;">ETA</div>
                        </div>
                    </div>
                @endif

                @if ($shipment->eta)
                    <div style="font-size: 14px; color: #6b7280; text-align: center;">
                        @if ($shipment->eta->isFuture())
                            <strong>{{ $shipment->eta->diffInDays() }} days</strong> remaining
                        @elseif($shipment->eta->isToday())
                            <strong>Arriving today</strong>
                        @else
                            <strong>{{ $shipment->eta->diffInDays() }} days</strong> overdue
                        @endif
                    </div>
                @endif
            </div>

            <!-- Contact Information -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Contact Information</h4>

                <div style="margin-bottom: 15px;">
                    <strong>{{ $shipment->company->name }}</strong>
                    <br><small style="color: #6b7280;">{{ $shipment->company->type }}</small>
                </div>

                @if ($shipment->company->contact_person)
                    <div style="margin-bottom: 10px;">
                        <strong style="font-size: 14px;">Contact:</strong><br>
                        <span style="color: #6b7280;">{{ $shipment->company->contact_person }}</span>
                    </div>
                @endif

                @if ($shipment->company->email)
                    <div style="margin-bottom: 10px;">
                        <a href="mailto:{{ $shipment->company->email }}" class="btn btn-primary btn-sm"
                            style="width: 100%; justify-content: center;">
                            ✉️ Send Email
                        </a>
                    </div>
                @endif

                @if ($shipment->company->phone)
                    <div>
                        <a href="tel:{{ $shipment->company->phone }}" class="btn btn-secondary btn-sm"
                            style="width: 100%; justify-content: center;">
                            📞 Call Company
                        </a>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div
                style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <h4 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h4>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-primary"
                        style="justify-content: center;">
                        👁️ View Details
                    </a>

                    @if (auth()->user()->hasAnyRole(['admin', 'manager']))
                        <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-secondary"
                            style="justify-content: center;">
                            ✏️ Edit Shipment
                        </a>
                    @endif

                    <button class="btn btn-secondary" style="justify-content: center;" onclick="window.print()">
                        🖨️ Print Tracking
                    </button>

                    <button class="btn btn-secondary" style="justify-content: center;"
                        onclick="navigator.share ? navigator.share({title: 'Shipment Tracking', text: 'Track shipment {{ $shipment->shipment_id }}', url: window.location.href}) : copyToClipboard(window.location.href)">
                        📤 Share Tracking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Tracking link copied to clipboard!');
            });
        }

        // Auto-refresh every 30 seconds if shipment is in transit
        @if (in_array($shipment->status, ['In Transit', 'At Port']))
            setInterval(function() {
                location.reload();
            }, 30000);
        @endif
    </script>
@endsection
