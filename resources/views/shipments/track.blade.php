@extends('layouts.app')

@section('title', 'Track Shipment - ' . $shipment->shipment_id)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-ship"></i>
                            Track Shipment: {{ $shipment->shipment_id }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Shipment Info -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <h6>Origin</h6>
                                <p class="mb-0">{{ $shipment->originPort->name ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $shipment->originPort->country ?? '' }}</small>
                            </div>
                            <div class="col-md-3">
                                <h6>Destination</h6>
                                <p class="mb-0">{{ $shipment->destinationPort->name ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $shipment->destinationPort->country ?? '' }}</small>
                            </div>
                            <div class="col-md-3">
                                <h6>Status</h6>
                                <span
                                    class="badge badge-{{ $shipment->status == 'Delivered' ? 'success' : ($shipment->status == 'In Transit' ? 'warning' : 'info') }}">
                                    {{ $shipment->status }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <h6>Expected Arrival</h6>
                                <p class="mb-0">{{ $shipment->eta ? $shipment->eta->format('M d, Y') : 'TBD' }}</p>
                            </div>
                        </div>

                        <!-- Company Info -->
                        @if ($shipment->company)
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Company</h6>
                                    <p class="mb-0">{{ $shipment->company->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Reference Number</h6>
                                    <p class="mb-0">{{ $shipment->reference_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Tracking Timeline -->
                        <h5 class="mb-3">
                            <i class="fas fa-timeline"></i>
                            Tracking Events
                        </h5>

                        @if ($shipment->trackingEvents && $shipment->trackingEvents->count() > 0)
                            <div class="timeline">
                                @foreach ($shipment->publicTrackingEvents as $event)
                                    <div class="timeline-item {{ $event->is_milestone ? 'milestone' : '' }}">
                                        <div
                                            class="timeline-marker {{ $event->is_milestone ? 'bg-primary' : 'bg-secondary' }}">
                                            @if ($event->is_milestone)
                                                <i class="fas fa-star text-white"></i>
                                            @else
                                                <i class="fas fa-circle text-white"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">{{ $event->status }}</h6>
                                            <p class="mb-1">{{ $event->description }}</p>
                                            @if ($event->location)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $event->location }}
                                                </small>
                                            @endif
                                            <small class="text-muted d-block">
                                                <i class="fas fa-clock"></i>
                                                {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '' }}
                                                {{ $event->event_time ? \Carbon\Carbon::parse($event->event_time)->format('g:i A') : '' }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No tracking events available for this shipment yet.
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 5px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
        }

        .timeline-item.milestone .timeline-marker {
            width: 20px;
            height: 20px;
            left: -25px;
            font-size: 10px;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #007bff;
        }

        .timeline-item.milestone .timeline-content {
            border-left-color: #28a745;
            background: #f0fff4;
        }
    </style>
@endsection
