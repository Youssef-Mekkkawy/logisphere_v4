@extends('layouts.app')

@section('title', 'Shipping Agency Details')
@section('page_title', $agency->name)
@section('breadcrumb', 'Home > Submenu > Shipping Agencies > ' . $agency->name)

@section('content')
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Agency Information -->
        <x-card title="Agency Information">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Agency Code:</strong><br>
                    <span style="color: #64748b;">{{ $agency->code }}</span>
                </div>
                <div>
                    <strong>Agency Name:</strong><br>
                    <span style="color: #64748b;">{{ $agency->name }}</span>
                </div>
                <div>
                    <strong>Country:</strong><br>
                    <span style="color: #64748b;">{{ $agency->country->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <strong>Service Type:</strong><br>
                    <span style="color: #64748b;">{{ $agency->service_type }}</span>
                </div>
                <div>
                    <strong>Status:</strong><br>
                    <span class="status-badge status-{{ strtolower($agency->status) }}">{{ $agency->status }}</span>
                </div>
                <div>
                    <strong>Contact Person:</strong><br>
                    <span style="color: #64748b;">{{ $agency->contact_person ?? 'N/A' }}</span>
                </div>
            </div>

            <div
                style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Email:</strong><br>
                    <span style="color: #64748b;">
                        @if ($agency->email)
                            <a href="mailto:{{ $agency->email }}"
                                style="color: var(--primary-color);">{{ $agency->email }}</a>
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div>
                    <strong>Phone:</strong><br>
                    <span style="color: #64748b;">
                        @if ($agency->phone)
                            <a href="tel:{{ $agency->phone }}"
                                style="color: var(--primary-color);">{{ $agency->phone }}</a>
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>

            @if ($agency->address)
                <div style="margin-top: 1.5rem;">
                    <strong>Address:</strong><br>
                    <p style="color: #64748b; margin-top: 0.5rem;">{{ $agency->address }}</p>
                </div>
            @endif

            @if ($agency->services_offered)
                <div style="margin-top: 1.5rem;">
                    <strong>Services Offered:</strong><br>
                    <p style="color: #64748b; margin-top: 0.5rem;">{{ $agency->services_offered }}</p>
                </div>
            @endif
        </x-card>

        <!-- Agency Statistics -->
        <x-card title="Agency Statistics">
            <div style="space-y: 1rem;">
                <div
                    style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                    <span>Total Shipments</span>
                    <span style="font-weight: 600;">{{ $agency->shipments_count ?? 0 }}</span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                    <span>Active Shipments</span>
                    <span style="font-weight: 600;">{{ $activeShipments ?? 0 }}</span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                    <span>Last Shipment</span>
                    <span
                        style="font-weight: 600;">{{ $agency->last_shipment_date ? $agency->last_shipment_date->format('M d, Y') : 'Never' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                    <span>Partner Since</span>
                    <span style="font-weight: 600;">{{ $agency->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Recent Shipments -->
    <x-card title="Recent Shipments">
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
                @forelse($recentShipments ?? [] as $shipment)
                    <tr>
                        <td><a href="{{ route('shipments.show', $shipment) }}"
                                style="color: var(--primary-color); text-decoration: none;"><strong>{{ $shipment->shipment_id }}</strong></a>
                        </td>
                        <td>{{ $shipment->company->name }}</td>
                        <td>{{ $shipment->originPort->name ?? 'N/A' }} → {{ $shipment->destinationPort->name ?? 'N/A' }}
                        </td>
                        <td>{{ $shipment->shipping_date ? $shipment->shipping_date->format('M d, Y') : 'N/A' }}</td>
                        <td><span
                                class="status-badge status-{{ strtolower(str_replace(' ', '', $shipment->status)) }}">{{ $shipment->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-outline"
                                style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b; padding: 2rem;">
                            No shipments found for this agency
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <!-- Actions -->
    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
        <a href="{{ route('submenu.shipping-agencies.edit', $agency) }}" class="btn btn-primary">Edit Agency</a>
        <a href="{{ route('submenu.shipping-agencies.index') }}" class="btn btn-secondary">Back to Agencies</a>
        @if (auth()->user()->isAdmin())
            <form action="{{ route('submenu.shipping-agencies.destroy', $agency) }}" method="POST"
                style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this agency?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Agency</button>
            </form>
        @endif
    </div>
@endsection
