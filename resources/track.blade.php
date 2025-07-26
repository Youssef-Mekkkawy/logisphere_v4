@extends('layouts.app')

@section('title', 'Track Shipment - LogiFlow')
@section('page-title', 'Shipment Tracking')

@section('content')
    <div class="tracking-container">
        {{-- Search Section --}}
        <div class="tracking-search">
            <h2>🔍 Track Your Shipment</h2>
            <p>Enter your shipment ID to get real-time updates</p>

            <form method="GET" action="{{ route('management.api.shipments.tracking', 'search') }}" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="id" placeholder="Enter Shipment ID (e.g., SHP-2024-001)"
                        value="{{ request('id') }}" class="tracking-input" required>
                    <button type="submit" class="tracking-btn">Track Shipment</button>
                </div>
            </form>
        </div>

        @if (isset($shipment))
            {{-- Shipment Found --}}
            <div class="tracking-result">
                <div class="shipment-header">
                    <h3>📦 {{ $shipment->shipment_id }}</h3>
                    <div class="shipment-meta">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}">
                            {{ $shipment->status }}
                        </span>
                        <span class="priority-badge priority-{{ strtolower($shipment->priority) }}">
                            {{ $shipment->priority }} Priority
                        </span>
                    </div>
                </div>

                {{-- Route Visualization --}}
                <div class="route-tracking">
                    <div class="route-header">
                        <h4>🗺️ Route Information</h4>
                        <div class="progress-percentage">{{ $shipment->getProgressPercentage() }}% Complete</div>
                    </div>

                    <div class="route-visual">
                        <div class="route-point origin">
                            <div class="port-info">
                                <div class="port-code">{{ $shipment->originPort->code }}</div>
                                <div class="port-name">{{ $shipment->originPort->name }}</div>
                                <div class="port-country">{{ $shipment->originPort->country->name }}</div>
                            </div>
                            @if ($shipment->etd)
                                <div class="date-info">
                                    <strong>ETD:</strong> {{ $shipment->etd->format('M d, Y') }}
                                </div>
                            @endif
                        </div>

                        <div class="route-line">
                            <div class="route-progress" style="width: {{ $shipment->getProgressPercentage() }}%"></div>
                            <div class="route-ship" style="left: {{ $shipment->getProgressPercentage() }}%">🚢</div>
                        </div>

                        <div class="route-point destination">
                            <div class="port-info">
                                <div class="port-code">{{ $shipment->destinationPort->code }}</div>
                                <div class="port-name">{{ $shipment->destinationPort->name }}</div>
                                <div class="port-country">{{ $shipment->destinationPort->country->name }}</div>
                            </div>
                            @if ($shipment->eta)
                                <div class="date-info">
                                    <strong>ETA:</strong> {{ $shipment->eta->format('M d, Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tracking Timeline --}}
                <div class="tracking-timeline">
                    <h4>📈 Shipment Timeline</h4>
                    <div class="timeline">
                        <div class="timeline-item {{ $shipment->status === 'Pending' ? 'active' : 'completed' }}">
                            <div class="timeline-dot">📋</div>
                            <div class="timeline-content">
                                <h5>Order Confirmed</h5>
                                <p>Shipment created and confirmed</p>
                                <small>{{ $shipment->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>

                        <div
                            class="timeline-item {{ in_array($shipment->status, ['In Transit', 'Out for Delivery', 'Delivered']) ? 'completed' : ($shipment->status === 'In Progress' ? 'active' : '') }}">
                            <div class="timeline-dot">🚢</div>
                            <div class="timeline-content">
                                <h5>In Transit</h5>
                                <p>Shipment departed from origin port</p>
                                <small>{{ $shipment->etd ? $shipment->etd->format('M d, Y') : 'Scheduled' }}</small>
                            </div>
                        </div>

                        <div
                            class="timeline-item {{ in_array($shipment->status, ['Out for Delivery', 'Delivered']) ? 'completed' : ($shipment->status === 'Arrived at Port' ? 'active' : '') }}">
                            <div class="timeline-dot">🏢</div>
                            <div class="timeline-content">
                                <h5>Arrived at Destination Port</h5>
                                <p>Shipment cleared customs and ready for delivery</p>
                                <small>{{ $shipment->eta ? $shipment->eta->format('M d, Y') : 'Estimated' }}</small>
                            </div>
                        </div>

                        <div
                            class="timeline-item {{ $shipment->status === 'Delivered' ? 'completed' : ($shipment->status === 'Out for Delivery' ? 'active' : '') }}">
                            <div class="timeline-dot">🚚</div>
                            <div class="timeline-content">
                                <h5>Out for Delivery</h5>
                                <p>Last mile delivery in progress</p>
                                <small>Pending</small>
                            </div>
                        </div>

                        <div class="timeline-item {{ $shipment->status === 'Delivered' ? 'completed' : '' }}">
                            <div class="timeline-dot">✅</div>
                            <div class="timeline-content">
                                <h5>Delivered</h5>
                                <p>Shipment successfully delivered</p>
                                <small>{{ $shipment->delivered_at ? $shipment->delivered_at->format('M d, Y H:i') : 'Pending' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipment Details --}}
                <div class="tracking-details">
                    <div class="detail-section">
                        <h4>📦 Cargo Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Description:</span>
                                <span class="value">{{ $shipment->cargo_description }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Service Type:</span>
                                <span class="value">{{ $shipment->service_type }}</span>
                            </div>
                            @if ($shipment->total_weight)
                                <div class="detail-item">
                                    <span class="label">Weight:</span>
                                    <span class="value">{{ number_format($shipment->total_weight, 2) }} KG</span>
                                </div>
                            @endif
                            @if ($shipment->number_of_packages)
                                <div class="detail-item">
                                    <span class="label">Packages:</span>
                                    <span class="value">{{ $shipment->number_of_packages }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4>🏢 Company Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Company:</span>
                                <span class="value">{{ $shipment->company->name }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Contact:</span>
                                <span class="value">{{ $shipment->company->contact_person }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Email:</span>
                                <span class="value">{{ $shipment->company->email }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Phone:</span>
                                <span class="value">{{ $shipment->company->phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="tracking-actions">
                    @can('view', $shipment)
                        <a href="{{ route('management.shipments.show', $shipment) }}" class="btn btn-primary">
                            <span class="btn-icon">👁️</span>
                            View Full Details
                        </a>
                    @endcan

                    <button onclick="printTracking()" class="btn btn-secondary">
                        <span class="btn-icon">🖨️</span>
                        Print Tracking
                    </button>

                    <button onclick="shareTracking()" class="btn btn-outline">
                        <span class="btn-icon">📤</span>
                        Share Tracking
                    </button>
                </div>
            </div>
        @elseif(request('id'))
            {{-- Shipment Not Found --}}
            <div class="tracking-error">
                <div class="error-icon">❌</div>
                <h3>Shipment Not Found</h3>
                <p>We couldn't find a shipment with ID: <strong>{{ request('id') }}</strong></p>
                <div class="error-suggestions">
                    <h4>Please check:</h4>
                    <ul>
                        <li>The shipment ID is correct</li>
                        <li>The shipment exists in our system</li>
                        <li>You have access to this shipment</li>
                    </ul>
                </div>
                <a href="{{ route('management.api.shipments.tracking', 'search') }}" class="btn btn-primary">Try Again</a>
            </div>
        @endif

        {{-- Recent Tracking Searches --}}
        @if (auth()->check())
            <div class="recent-searches">
                <h4>🕒 Recent Searches</h4>
                <div class="search-history">
                    {{-- This would be populated from user's search history --}}
                    <button class="search-item" onclick="searchShipment('SHP-2024-001')">
                        <span class="search-id">SHP-2024-001</span>
                        <span class="search-status">In Transit</span>
                    </button>
                    <button class="search-item" onclick="searchShipment('SHP-2024-002')">
                        <span class="search-id">SHP-2024-002</span>
                        <span class="search-status">Delivered</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

@section('styles')
    <style>
        .tracking-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .tracking-search {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .tracking-search h2 {
            color: #1e40af;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .tracking-search p {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .search-input-group {
            display: flex;
            max-width: 600px;
            margin: 0 auto;
            gap: 15px;
        }

        .tracking-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .tracking-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .tracking-btn {
            padding: 15px 30px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tracking-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .tracking-result {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .shipment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .shipment-header h3 {
            color: #1e40af;
            font-size: 24px;
            margin: 0;
        }

        .shipment-meta {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .route-tracking {
            margin-bottom: 40px;
        }

        .route-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .route-header h4 {
            color: #1e40af;
            margin: 0;
        }

        .progress-percentage {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .route-visual {
            display: flex;
            align-items: center;
            padding: 30px;
            background: #f8fafc;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .route-point {
            text-align: center;
            flex: 0 0 auto;
        }

        .port-info {
            margin-bottom: 15px;
        }

        .port-code {
            background: #3b82f6;
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .port-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .port-country {
            color: #6b7280;
            font-size: 14px;
        }

        .date-info {
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .route-line {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            margin: 0 40px;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }

        .route-progress {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 4px;
            transition: width 1s ease;
        }

        .route-ship {
            position: absolute;
            top: -20px;
            transform: translateX(-50%);
            font-size: 32px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            animation: shipMove 3s ease-in-out infinite;
        }

        @keyframes shipMove {

            0%,
            100% {
                transform: translateX(-50%) translateY(0px);
            }

            50% {
                transform: translateX(-50%) translateY(-8px);
            }
        }

        .tracking-timeline {
            margin-bottom: 40px;
        }

        .tracking-timeline h4 {
            color: #1e40af;
            margin-bottom: 25px;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            padding-left: 80px;
            margin-bottom: 30px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: 10px;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .timeline-item.active .timeline-dot {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            animation: pulse 2s infinite;
        }

        .timeline-item.completed .timeline-dot {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .timeline-content h5 {
            color: #1f2937;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .timeline-content p {
            color: #6b7280;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .timeline-content small {
            color: #9ca3af;
            font-size: 12px;
        }

        .tracking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .detail-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            border-left: 4px solid #3b82f6;
        }

        .detail-section h4 {
            color: #1e40af;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .detail-grid {
            display: grid;
            gap: 15px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item .label {
            color: #6b7280;
            font-weight: 500;
            font-size: 14px;
        }

        .detail-item .value {
            color: #1f2937;
            font-weight: 500;
            text-align: right;
        }

        .tracking-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding-top: 30px;
            border-top: 2px solid #f1f5f9;
        }

        .tracking-error {
            background: white;
            border-radius: 15px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .error-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .tracking-error h3 {
            color: #dc2626;
            margin-bottom: 15px;
        }

        .tracking-error p {
            color: #6b7280;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .error-suggestions {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: left;
        }

        .error-suggestions h4 {
            color: #991b1b;
            margin-bottom: 10px;
        }

        .error-suggestions ul {
            color: #7f1d1d;
            margin: 0;
            padding-left: 20px;
        }

        .recent-searches {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .recent-searches h4 {
            color: #1e40af;
            margin-bottom: 20px;
        }

        .search-history {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-item {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .search-item:hover {
            background: #e2e8f0;
            border-color: #3b82f6;
        }

        .search-id {
            font-weight: 600;
            color: #1f2937;
            font-size: 12px;
        }

        .search-status {
            font-size: 10px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .search-input-group {
                flex-direction: column;
            }

            .shipment-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .route-visual {
                flex-direction: column;
                gap: 30px;
            }

            .route-line {
                width: 8px;
                height: 60px;
                margin: 0;
            }

            .route-ship {
                left: -20px !important;
                top: 50%;
            }

            .tracking-details {
                grid-template-columns: 1fr;
            }

            .tracking-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus search input
            const searchInput = document.querySelector('.tracking-input');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }

            // Print tracking functionality
            window.printTracking = function() {
                window.print();
            };

            // Share tracking functionality
            window.shareTracking = function() {
                const shipmentId = '{{ $shipment->shipment_id ?? '' }}';
                const url = window.location.href;

                if (navigator.share) {
                    navigator.share({
                        title: `Track Shipment ${shipmentId}`,
                        text: `Track shipment ${shipmentId} on LogiFlow`,
                        url: url
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Tracking link copied to clipboard!');
                    });
                }
            };

            // Search from recent history
            window.searchShipment = function(shipmentId) {
                const input = document.querySelector('.tracking-input');
                input.value = shipmentId;
                input.form.submit();
            };

            // Auto-refresh for active shipments
            @if (isset($shipment) && in_array($shipment->status, ['In Transit', 'In Progress']))
                setInterval(function() {
                    fetch(window.location.href, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            const newDoc = new DOMParser().parseFromString(html, 'text/html');
                            const currentStatus = document.querySelector('.status-badge');
                            const newStatus = newDoc.querySelector('.status-badge');

                            if (currentStatus && newStatus && currentStatus.textContent.trim() !==
                                newStatus.textContent.trim()) {
                                location.reload(); // Reload to show updated status
                            }
                        })
                        .catch(error => console.log('Auto-refresh failed:', error));
                }, 60000); // Check every minute
            @endif

            console.log('Shipment tracking initialized');
            @if (isset($shipment))
                console.log('Tracking shipment: {{ $shipment->shipment_id }}');
                console.log('Current status: {{ $shipment->status }}');
            @endif
        });
    </script>
@endsection
@endsection
