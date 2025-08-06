@extends('layouts.app')

@section('title', 'Dashboard - logisphere')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['active_shipments'] }}</div>
        <div class="stat-label">Active Shipments</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['client_companies'] }}</div>
        <div class="stat-label">Client Companies</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['employees'] }}</div>
        <div class="stat-label">Employees</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">${{ number_format($stats['monthly_revenue'], 1) }}M</div>
        <div class="stat-label">Monthly Revenue</div>
    </div>
</div>

<h3 style="margin-bottom: 20px; color: #1e40af;">Recent Shipments</h3>
<table class="data-table">
    <thead>
        <tr>
            <th>Shipment ID</th>
            <th>Client</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Status</th>
            <th>ETA</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($recentShipments as $shipment)
        <tr>
            <td>{{ $shipment->shipment_id }}</td>
            <td>{{ $shipment->company->name }}</td>
            <td>{{ $shipment->originPort->name }}</td>
            <td>{{ $shipment->destinationPort->name }}</td>
            <td>
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}">
                    {{ $shipment->status }}
                </span>
            </td>
            <td>{{ $shipment->eta ? $shipment->eta->format('Y-m-d') : 'N/A' }}</td>
            <td>
                <a href="{{ route('management.shipments.edit', $shipment) }}" class="btn btn-secondary">Edit</a>
                <a href="{{ route('management.api.shipments.tracking', $shipment->shipment_id) }}" class="btn btn-primary">Track</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center; padding: 40px;">
                <p style="color: #6b7280;">No shipments found. <a href="{{ route('management.shipments.create') }}">Create your first shipment</a></p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
