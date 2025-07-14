@extends('layouts.app')

@section('title', 'Edit Shipment - LogiFlow')
@section('page-title', 'Edit Shipment: ' . $shipment->shipment_id)

@section('content')
    <div style="margin-bottom: 30px;">
        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">← Back to Shipments</a>
        <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-primary" style="margin-left: 10px;">View Shipment</a>
    </div>

    <form method="POST" action="{{ route('shipments.update', $shipment) }}"
        style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 30px;">
            <h3 style="color: #1e40af; margin-bottom: 5px;">Edit Shipment Information</h3>
            <p style="color: #6b7280;">Update the shipment details below</p>
            <div style="background: #f3f4f6; padding: 10px 15px; border-radius: 8px; margin-top: 10px;">
                <strong>Shipment ID:</strong> {{ $shipment->shipment_id }}
            </div>
        </div>

        <!-- Status Update -->
        <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Status Update</h4>
            <div class="form-group">
                <label class="form-label">Current Status</label>
                <select name="status" class="form-input" required>
                    <option value="Pending" {{ old('status', $shipment->status) == 'Pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="In Transit" {{ old('status', $shipment->status) == 'In Transit' ? 'selected' : '' }}>In
                        Transit</option>
                    <option value="At Port" {{ old('status', $shipment->status) == 'At Port' ? 'selected' : '' }}>At Port
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
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Client Company *</label>
                <select name="company_id" class="form-input" required>
                    <option value="">Select Client Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id', $shipment->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Container Type *</label>
                <select name="container_type" class="form-input" required>
                    <option value="">Select Container Type</option>
                    <option value="20ft Standard"
                        {{ old('container_type', $shipment->container_type) == '20ft Standard' ? 'selected' : '' }}>20ft
                        Standard</option>
                    <option value="40ft Standard"
                        {{ old('container_type', $shipment->container_type) == '40ft Standard' ? 'selected' : '' }}>40ft
                        Standard</option>
                    <option value="40ft High Cube"
                        {{ old('container_type', $shipment->container_type) == '40ft High Cube' ? 'selected' : '' }}>40ft
                        High Cube</option>
                    <option value="45ft High Cube"
                        {{ old('container_type', $shipment->container_type) == '45ft High Cube' ? 'selected' : '' }}>45ft
                        High Cube</option>
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
                        {{ old('container_type', $shipment->container_type) == 'Flat Rack' ? 'selected' : '' }}>Flat Rack
                    </option>
                </select>
                @error('container_type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Route Information -->
        <div style="margin: 30px 0;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Route Information</h4>
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

        <!-- Dates and Costs -->
        <div style="margin: 30px 0;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Schedule & Pricing</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Shipping Date</label>
                    <input type="date" name="shipping_date" class="form-input"
                        value="{{ old('shipping_date', $shipment->shipping_date?->format('Y-m-d')) }}">
                    @error('shipping_date')
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

                <div class="form-group">
                    <label class="form-label">Freight Cost ($)</label>
                    <input type="number" name="freight_cost" class="form-input" step="0.01"
                        value="{{ old('freight_cost', $shipment->freight_cost) }}" placeholder="0.00">
                    @error('freight_cost')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Cargo Details -->
        <div style="margin: 30px 0;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Cargo Details</h4>
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
                <label class="form-label">Cargo Description</label>
                <textarea name="cargo_description" class="form-input" rows="3" placeholder="Describe the cargo contents...">{{ old('cargo_description', $shipment->cargo_description) }}</textarea>
                @error('cargo_description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Special Instructions</label>
                <textarea name="special_instructions" class="form-input" rows="3"
                    placeholder="Any special handling instructions...">{{ old('special_instructions', $shipment->special_instructions) }}</textarea>
                @error('special_instructions')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Audit Trail -->
        <div style="margin: 30px 0; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">Audit Information</h4>
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
            </div>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">Update Shipment</button>
            <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-secondary"
                style="margin-left: 15px;">Cancel</a>

            @if (auth()->user()->isAdmin())
                <button type="button" class="btn btn-danger" style="margin-left: 15px; float: right;"
                    onclick="if(confirm('Are you sure you want to delete this shipment?')) { document.getElementById('delete-form').submit(); }">
                    Delete Shipment
                </button>
            @endif
        </div>
    </form>

    @if (auth()->user()->isAdmin())
        <form id="delete-form" method="POST" action="{{ route('shipments.destroy', $shipment) }}"
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection Port' ? 'selected' : '' }}>At Port</option>
<option value="Delivered" {{ old('status', $shipment->status) == 'Delivered' ? 'selected' : '' }}>Delivered</option>
<option value="Cancelled" {{ old('status', $shipment->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
</select>
@error('status')
    <span class="error-message">{{ $message }}</span>
@enderror
</div>
</div>

<!-- Basic Information -->
<div class="form-grid">
    <div class="form-group">
        <label class="form-label">Company *</label>
        <select name="company_id" class="form-input" required>
            <option value="">Select Company</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}"
                    {{ old('company_id', $shipment->company_id) == $company->id ? 'selected' : '' }}>
                    {{ $company->name }} ({{ $company->type }})
                </option>
            @endforeach
        </select>
        @error('company_id')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Container Type *</label>
        <select name="container_type" class="form-input" required>
            <option value="">Select Container Type</option>
            <option value="20ft Standard"
                {{ old('container_type', $shipment->container_type) == '20ft Standard' ? 'selected' : '' }}>20ft
                Standard</option>
            <option value="40ft Standard"
                {{ old('container_type', $shipment->container_type) == '40ft Standard' ? 'selected' : '' }}>40ft
                Standard</option>
            <option value="40ft High Cube"
                {{ old('container_type', $shipment->container_type) == '40ft High Cube' ? 'selected' : '' }}>40ft High
                Cube</option>
            <option value="45ft High Cube"
                {{ old('container_type', $shipment->container_type) == '45ft High Cube' ? 'selected' : '' }}>45ft High
                Cube</option>
            <option value="20ft Refrigerated"
                {{ old('container_type', $shipment->container_type) == '20ft Refrigerated' ? 'selected' : '' }}>20ft
                Refrigerated</option>
            <option value="40ft Refrigerated"
                {{ old('container_type', $shipment->container_type) == '40ft Refrigerated' ? 'selected' : '' }}>40ft
                Refrigerated</option>
            <option value="Open Top"
                {{ old('container_type', $shipment->container_type) == 'Open Top' ? 'selected' : '' }}>Open Top
            </option>
            <option value="Flat Rack"
                {{ old('container_type', $shipment->container_type) == 'Flat Rack' ? 'selected' : '' }}>Flat Rack
            </option>
        </select>
        @error('container_type')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Route Information -->
<div style="margin: 30px 0;">
    <h4 style="color: #1e40af; margin-bottom: 15px;">Route Information</h4>
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

<!-- Dates and Costs -->
<div style="margin: 30px 0;">
    <h4 style="color: #1e40af; margin-bottom: 15px;">Schedule & Pricing</h4>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Shipping Date</label>
            <input type="date" name="shipping_date" class="form-input"
                value="{{ old('shipping_date', $shipment->shipping_date ? $shipment->shipping_date->format('Y-m-d') : '') }}">
            @error('shipping_date')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Estimated Time of Arrival (ETA)</label>
            <input type="date" name="eta" class="form-input"
                value="{{ old('eta', $shipment->eta ? $shipment->eta->format('Y-m-d') : '') }}">
            @error('eta')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Freight Cost ($)</label>
            <input type="number" name="freight_cost" class="form-input" step="0.01"
                value="{{ old('freight_cost', $shipment->freight_cost) }}" placeholder="0.00">
            @error('freight_cost')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<!-- Cargo Details -->
<div style="margin: 30px 0;">
    <h4 style="color: #1e40af; margin-bottom: 15px;">Cargo Details</h4>
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
        <label class="form-label">Cargo Description</label>
        <textarea name="cargo_description" class="form-input" rows="3" placeholder="Describe the cargo contents...">{{ old('cargo_description', $shipment->cargo_description) }}</textarea>
        @error('cargo_description')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Special Instructions</label>
        <textarea name="special_instructions" class="form-input" rows="3"
            placeholder="Any special handling instructions...">{{ old('special_instructions', $shipment->special_instructions) }}</textarea>
        @error('special_instructions')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Audit Trail -->
<div style="margin: 30px 0; padding: 20px; background: #f8fafc; border-radius: 12px;">
    <h4 style="color: #1e40af; margin-bottom: 15px;">Audit Information</h4>
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
    </div>
</div>

<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
    <button type="submit" class="btn btn-primary">Update Shipment</button>
    <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-secondary"
        style="margin-left: 15px;">Cancel</a>

    @if (auth()->user()->isAdmin())
        <button type="button" class="btn btn-danger" style="margin-left: 15px; float: right;"
            onclick="if(confirm('Are you sure you want to delete this shipment?')) { document.getElementById('delete-form').submit(); }">
            Delete Shipment
        </button>
    @endif
</div>
</form>

@if (auth()->user()->isAdmin())
    <form id="delete-form" method="POST" action="{{ route('shipments.destroy', $shipment) }}"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endif
@endsection
