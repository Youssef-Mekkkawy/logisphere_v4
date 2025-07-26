@extends('layouts.app')

@section('title', 'Edit Shipment - LogiFlow')
@section('page-title', 'Edit Shipment: ' . $shipment->shipment_id)

@section('content')
    <div style="margin-bottom: 30px;">
        <a href="{{ route('management.shipments.index') }}" class="btn btn-secondary">← Back to Shipments</a>
        <a href="{{ route('management.shipments.show', $shipment) }}" class="btn btn-primary" style="margin-left: 10px;">View Shipment</a>
        @if ($shipment->status == 'Pending')
            <a href="{{ route('management.shipments.duplicate', $shipment) }}" class="btn btn-info"
                style="margin-left: 10px;">Duplicate</a>
        @endif
    </div>

    <form method="POST" action="{{ route('management.shipments.update', $shipment) }}"
        style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        @csrf
        @method('PUT')

        <!-- Header Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1e40af; margin-bottom: 5px;">Edit Shipment Information</h3>
            <p style="color: #6b7280;">Update the shipment details below</p>
            <div style="background: #f3f4f6; padding: 10px 15px; border-radius: 8px; margin-top: 10px;">
                <strong>Shipment ID:</strong> {{ $shipment->shipment_id }}
                <span style="margin-left: 20px;"><strong>Current Status:</strong>
                    <span
                        class="badge badge-{{ $shipment->status == 'Delivered' ? 'success' : ($shipment->status == 'In Transit' ? 'warning' : 'info') }}">
                        {{ $shipment->status }}
                    </span>
                </span>
            </div>
        </div>

        <!-- Status Update Section -->
        <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📋 Status Update</h4>
            <div class="form-group">
                <label class="form-label">Current Status *</label>
                <select name="status" class="form-input" required>
                    <option value="Pending" {{ old('status', $shipment->status) == 'Pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="In Transit" {{ old('status', $shipment->status) == 'In Transit' ? 'selected' : '' }}>In
                        Transit</option>
                    <option value="At Port" {{ old('status', $shipment->status) == 'At Port' ? 'selected' : '' }}>At Port
                    </option>
                    <option value="Customs Clearance"
                        {{ old('status', $shipment->status) == 'Customs Clearance' ? 'selected' : '' }}>Customs Clearance
                    </option>
                    <option value="Delivered" {{ old('status', $shipment->status) == 'Delivered' ? 'selected' : '' }}>
                        Delivered</option>
                    <option value="Cancelled" {{ old('status', $shipment->status) == 'Cancelled' ? 'selected' : '' }}>
                        Cancelled</option>
                </select>
                @error('status')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">🏢 Basic Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Client Company *</label>
                    <select name="company_id" class="form-input" required>
                        <option value="">Select Client Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ old('company_id', $shipment->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->name }} ({{ $company->company_code ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Assigned Employee *</label>
                    <select name="employee_id" class="form-input" required>
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('employee_id', $shipment->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} - {{ $employee->department }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Reference Number</label>
                    <input type="text" name="reference_number" class="form-input"
                        value="{{ old('reference_number', $shipment->reference_number) }}"
                        placeholder="Client reference number">
                    @error('reference_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Route Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">🌍 Route Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Origin Port *</label>
                    <select name="origin_port_id" class="form-input" required>
                        <option value="">Select Origin Port</option>
                        @foreach ($ports as $port)
                            <option value="{{ $port->id }}"
                                {{ old('origin_port_id', $shipment->origin_port_id) == $port->id ? 'selected' : '' }}>
                                {{ $port->name }} - {{ $port->country }}
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
                        <option value="">Select Destination Port</option>
                        @foreach ($ports as $port)
                            <option value="{{ $port->id }}"
                                {{ old('destination_port_id', $shipment->destination_port_id) == $port->id ? 'selected' : '' }}>
                                {{ $port->name }} - {{ $port->country }}
                            </option>
                        @endforeach
                    </select>
                    @error('destination_port_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Service Providers -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">🚢 Service Providers</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Shipping Agency</label>
                    <select name="shipping_agency_id" class="form-input">
                        <option value="">Select Shipping Agency</option>
                        @foreach ($agencies as $agency)
                            <option value="{{ $agency->id }}"
                                {{ old('shipping_agency_id', $shipment->shipping_agency_id) == $agency->id ? 'selected' : '' }}>
                                {{ $agency->name }} - {{ $agency->country }}
                            </option>
                        @endforeach
                    </select>
                    @error('shipping_agency_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Shipment Type</label>
                    <select name="shipment_type_id" class="form-input">
                        <option value="">Select Shipment Type</option>
                        @foreach ($shipmentTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ old('shipment_type_id', $shipment->shipment_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->category }})
                            </option>
                        @endforeach
                    </select>
                    @error('shipment_type_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Container Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📦 Container Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Container Type</label>
                    <select name="container_type" class="form-input">
                        <option value="">Select Container Type</option>
                        <option value="20ft Standard"
                            {{ old('container_type', $shipment->container_type) == '20ft Standard' ? 'selected' : '' }}>
                            20ft Standard</option>
                        <option value="40ft Standard"
                            {{ old('container_type', $shipment->container_type) == '40ft Standard' ? 'selected' : '' }}>
                            40ft Standard</option>
                        <option value="40ft High Cube"
                            {{ old('container_type', $shipment->container_type) == '40ft High Cube' ? 'selected' : '' }}>
                            40ft High Cube</option>
                        <option value="45ft High Cube"
                            {{ old('container_type', $shipment->container_type) == '45ft High Cube' ? 'selected' : '' }}>
                            45ft High Cube</option>
                        <option value="20ft Refrigerated"
                            {{ old('container_type', $shipment->container_type) == '20ft Refrigerated' ? 'selected' : '' }}>
                            20ft Refrigerated</option>
                        <option value="40ft Refrigerated"
                            {{ old('container_type', $shipment->container_type) == '40ft Refrigerated' ? 'selected' : '' }}>
                            40ft Refrigerated</option>
                        <option value="Open Top"
                            {{ old('container_type', $shipment->container_type) == 'Open Top' ? 'selected' : '' }}>Open Top
                        </option>
                        <option value="Flat Rack"
                            {{ old('container_type', $shipment->container_type) == 'Flat Rack' ? 'selected' : '' }}>Flat
                            Rack</option>
                    </select>
                    @error('container_type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Container Number</label>
                    <input type="text" name="container_number" class="form-input"
                        value="{{ old('container_number', $shipment->container_number) }}"
                        placeholder="e.g., MSCU1234567">
                    @error('container_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Schedule & Pricing -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📅 Schedule & Pricing</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Estimated Time of Departure (ETD)</label>
                    <input type="date" name="etd" class="form-input"
                        value="{{ old('etd', $shipment->etd?->format('Y-m-d')) }}">
                    @error('etd')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Estimated Time of Arrival (ETA)</label>
                    <input type="date" name="eta" class="form-input"
                        value="{{ old('eta', $shipment->eta?->format('Y-m-d')) }}">
                    @error('eta')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Shipment Value</label>
                    <input type="number" name="value" class="form-input" step="0.01"
                        value="{{ old('value', $shipment->value) }}" placeholder="0.00">
                    @error('value')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-input">
                        <option value="">Select Currency</option>
                        <option value="USD" {{ old('currency', $shipment->currency) == 'USD' ? 'selected' : '' }}>USD -
                            US Dollar</option>
                        <option value="EUR" {{ old('currency', $shipment->currency) == 'EUR' ? 'selected' : '' }}>EUR -
                            Euro</option>
                        <option value="AED" {{ old('currency', $shipment->currency) == 'AED' ? 'selected' : '' }}>AED -
                            UAE Dirham</option>
                        <option value="SAR" {{ old('currency', $shipment->currency) == 'SAR' ? 'selected' : '' }}>SAR -
                            Saudi Riyal</option>
                        <option value="GBP" {{ old('currency', $shipment->currency) == 'GBP' ? 'selected' : '' }}>GBP -
                            British Pound</option>
                    </select>
                    @error('currency')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Cargo Details -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📋 Cargo Details</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" name="weight" class="form-input" step="0.01"
                        value="{{ old('weight', $shipment->weight) }}" placeholder="0.00">
                    @error('weight')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Volume (m³)</label>
                    <input type="number" name="volume" class="form-input" step="0.01"
                        value="{{ old('volume', $shipment->volume) }}" placeholder="0.00">
                    @error('volume')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Cargo Description *</label>
                <textarea name="cargo_description" class="form-input" rows="3" required
                    placeholder="Describe the cargo contents...">{{ old('cargo_description', $shipment->cargo_description) }}</textarea>
                @error('cargo_description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Consignee Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">👤 Consignee Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Consignee Name *</label>
                    <input type="text" name="consignee_name" class="form-input" required
                        value="{{ old('consignee_name', $shipment->consignee_name) }}"
                        placeholder="Full name of consignee">
                    @error('consignee_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Notify Party</label>
                    <input type="text" name="notify_party" class="form-input"
                        value="{{ old('notify_party', $shipment->notify_party) }}" placeholder="Party to be notified">
                    @error('notify_party')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Consignee Address *</label>
                <textarea name="consignee_address" class="form-input" rows="3" required
                    placeholder="Complete address of consignee">{{ old('consignee_address', $shipment->consignee_address) }}</textarea>
                @error('consignee_address')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Special Instructions -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📝 Special Instructions</h4>
            <div class="form-group">
                <label class="form-label">Special Instructions</label>
                <textarea name="special_instructions" class="form-input" rows="4"
                    placeholder="Any special handling instructions, delivery requirements, or notes...">{{ old('special_instructions', $shipment->special_instructions) }}</textarea>
                @error('special_instructions')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Audit Trail -->
        <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📊 Audit Information</h4>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 14px;">
                <div>
                    <strong>Created:</strong><br>
                    <span style="color: #6b7280;">{{ $shipment->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div>
                    <strong>Last Updated:</strong><br>
                    <span style="color: #6b7280;">{{ $shipment->updated_at->format('M d, Y H:i') }}</span>
                </div>
                @if ($shipment->employee)
                    <div>
                        <strong>Assigned To:</strong><br>
                        <span style="color: #6b7280;">{{ $shipment->employee->name }}</span>
                    </div>
                @endif
                @if ($shipment->trackingEvents && $shipment->trackingEvents->count() > 0)
                    <div>
                        <strong>Tracking Events:</strong><br>
                        <span style="color: #6b7280;">{{ $shipment->trackingEvents->count() }} events</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Shipment
            </button>
            <a href="{{ route('management.shipments.show', $shipment) }}" class="btn btn-secondary" style="margin-left: 15px;">
                <i class="fas fa-times"></i> Cancel
            </a>

            @if (in_array($shipment->status, ['Pending', 'Cancelled']))
                <button type="button" class="btn btn-danger" style="margin-left: 15px; float: right;"
                    onclick="if(confirm('Are you sure you want to delete this shipment? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }">
                    <i class="fas fa-trash"></i> Delete Shipment
                </button>
            @endif
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    @if (in_array($shipment->status, ['Pending', 'Cancelled']))
        <form id="delete-form" method="POST" action="{{ route('management.shipments.destroy', $shipment) }}"
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif

    <!-- Quick Actions -->
    <div
        style="margin-top: 20px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
        <h5 style="color: #1e40af; margin-bottom: 15px;">Quick Actions</h5>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('shipments.track', $shipment->shipment_id) }}" class="btn btn-info btn-sm"
                target="_blank">
                <i class="fas fa-map-marker-alt"></i> Track Shipment
            </a>
            @if ($shipment->status != 'Cancelled')
                <button type="button" class="btn btn-warning btn-sm" onclick="updateStatus('{{ $shipment->id }}')">
                    <i class="fas fa-edit"></i> Quick Status Update
                </button>
            @endif
            <a href="{{ route('shipments.duplicate', $shipment) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-copy"></i> Duplicate
            </a>
        </div>
    </div>

    <script>
        function updateStatus(shipmentId) {
            // Quick status update functionality
            const newStatus = prompt(
                'Enter new status (Pending, In Transit, At Port, Customs Clearance, Delivered, Cancelled):');
            if (newStatus) {
                // You can implement AJAX call here to update status
                console.log('Updating status to:', newStatus);
            }
        }
    </script>
@endsection
