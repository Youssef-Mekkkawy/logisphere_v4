@extends('layouts.app')

@section('title', 'Shipments - LogiFlow')
@section('page-title', 'Shipments')

@section('content')
    <div class="tabs">
        <div class="tab active" data-tab="all-shipments">All Shipments</div>
        <div class="tab" data-tab="new-shipment">New Shipment</div>
        <div class="tab" data-tab="tracking">Tracking</div>
    </div>

    <div id="all-shipments-tab" class="tab-content active">
        <input type="text" class="search-bar" placeholder="Search shipments..." id="shipment-search">
        <a href="{{ route('shipments.create') }}" class="btn btn-primary">Create New Shipment (Ctrl+F1)</a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Shipment ID</th>
                    <th>Client</th>
                    <th>Origin Port</th>
                    <th>Destination Port</th>
                    <th>Container Type</th>
                    <th>Status</th>
                    <th>ETA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipments as $shipment)
                    <tr>
                        <td>{{ $shipment->shipment_id }}</td>
                        <td>{{ $shipment->company->name }}</td>
                        <td>{{ $shipment->originPort->name }}</td>
                        <td>{{ $shipment->destinationPort->name }}</td>
                        <td>{{ $shipment->container_type ?: 'N/A' }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->status)) }}">
                                {{ $shipment->status }}
                            </span>
                        </td>
                        <td>{{ $shipment->eta ? $shipment->eta->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-secondary">Edit</a>
                            <button onclick="trackShipment('{{ $shipment->shipment_id }}')"
                                class="btn btn-primary">Track</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            <p style="color: #6b7280;">No shipments found. <a href="{{ route('shipments.create') }}">Create
                                    your first shipment</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($shipments instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div style="margin-top: 20px;">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

    <div id="new-shipment-tab" class="tab-content">
        <h3>Create New Shipment</h3>
        <form method="POST" action="{{ route('shipments.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Client Company *</label>
                    <select name="company_id" class="form-input" required>
                        <option value="">Select Client</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Origin Port *</label>
                    <select name="origin_port_id" class="form-input" required>
                        <option value="">Select Origin</option>
                        @foreach ($ports as $port)
                            <option value="{{ $port->id }}"
                                {{ old('origin_port_id') == $port->id ? 'selected' : '' }}>
                                {{ $port->name }} ({{ $port->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('origin_port_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Destination Port *</label>
                    <select name="destination_port_id" class="form-input" required>
                        <option value="">Select Destination</option>
                        @foreach ($ports as $port)
                            <option value="{{ $port->id }}"
                                {{ old('destination_port_id') == $port->id ? 'selected' : '' }}>
                                {{ $port->name }} ({{ $port->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('destination_port_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Container Type</label>
                    <select name="container_type" class="form-input">
                        <option value="">Select Container Type</option>
                        <option value="20ft Container" {{ old('container_type') == '20ft Container' ? 'selected' : '' }}>
                            20ft Container</option>
                        <option value="40ft Container"
                            {{ old('container_type', '40ft Container') == '40ft Container' ? 'selected' : '' }}>40ft
                            Container</option>
                        <option value="40ft High Cube" {{ old('container_type') == '40ft High Cube' ? 'selected' : '' }}>
                            40ft High Cube</option>
                        <option value="45ft Container" {{ old('container_type') == '45ft Container' ? 'selected' : '' }}>
                            45ft Container</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Shipping Date</label>
                    <input type="date" name="shipping_date" class="form-input" value="{{ old('shipping_date') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Estimated Arrival</label>
                    <input type="date" name="eta" class="form-input" value="{{ old('eta') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Freight Cost (USD)</label>
                    <input type="number" name="freight_cost" class="form-input" step="0.01" placeholder="0.00"
                        value="{{ old('freight_cost') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Cargo Description</label>
                    <input type="text" name="cargo_description" class="form-input"
                        value="{{ old('cargo_description') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" name="weight" class="form-input" step="0.01" value="{{ old('weight') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Volume (m³)</label>
                    <input type="number" name="volume" class="form-input" step="0.01" value="{{ old('volume') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Special Instructions</label>
                    <textarea name="special_instructions" class="form-input" rows="3">{{ old('special_instructions') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Shipment</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
    </div>

    <div id="tracking-tab" class="tab-content">
        <h3>Shipment Tracking</h3>
        <div class="form-group">
            <label class="form-label">Enter Shipment ID or Tracking Number</label>
            <input type="text" class="form-input" placeholder="SH-2025-001" id="tracking-input">
            <button class="btn btn-primary" style="margin-top: 10px;" onclick="trackShipment()">Track Shipment</button>
        </div>

        <div id="tracking-results"
            style="display: none; margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 10px;">
            <!-- Tracking results will be populated here via JavaScript -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function trackShipment(shipmentId = null) {
            const trackingId = shipmentId || document.getElementById('tracking-input').value;
            if (trackingId) {
                fetch(`/shipments/${trackingId}/track`)
                    .then(response => response.json())
                    .then(data => {
                        const resultsDiv = document.getElementById('tracking-results');
                        resultsDiv.innerHTML = `
                    <h4>Tracking Results: ${data.shipment_id}</h4>
                    <div style="margin-top: 15px;">
                        ${data.tracking_events.map(event => `
                                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <div style="width: 20px; height: 20px; background: ${event.color}; border-radius: 50%; margin-right: 15px;"></div>
                                    <div>
                                        <strong>${event.status}</strong><br>
                                        <small>${event.location} - ${event.timestamp}</small>
                                    </div>
                                </div>
                            `).join('')}
                    </div>
                `;
                        resultsDiv.style.display = 'block';

                        // Switch to tracking tab if not already there
                        const trackingTab = document.querySelector('[data-tab="tracking"]');
                        if (trackingTab && !trackingTab.classList.contains('active')) {
                            trackingTab.click();
                        }
                    })
                    .catch(error => {
                        alert('Shipment not found or tracking information unavailable');
                        console.error('Tracking error:', error);
                    });
            }
        }
    </script>
@endpush

