@extends('layouts.app')

@section('title', 'Create Shipment - logistics')
@section('page-title', 'Create New Shipment')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('management.shipments.index') }}" class="btn btn-secondary">← Back to Shipments</a>
    </div>

    <form method="POST" action="{{ route('management.shipments.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Client Company *</label>
                <select name="company_id" class="form-input" required>
                    <option value="">Select Client Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id', request('company_id')) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }} ({{ $company->type }})
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
                    <option value="">Select Origin Port</option>
                    @foreach ($ports as $port)
                        <option value="{{ $port->id }}" {{ old('origin_port_id') == $port->id ? 'selected' : '' }}>
                            {{ $port->name }} ({{ $port->code }}) - {{ $port->country }}
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
                            {{ old('destination_port_id') == $port->id ? 'selected' : '' }}>
                            {{ $port->name }} ({{ $port->code }}) - {{ $port->country }}
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
                    <option value="20ft Container" {{ old('container_type') == '20ft Container' ? 'selected' : '' }}>20ft
                        Container</option>
                    <option value="40ft Container"
                        {{ old('container_type', '40ft Container') == '40ft Container' ? 'selected' : '' }}>40ft Container
                    </option>
                    <option value="40ft High Cube" {{ old('container_type') == '40ft High Cube' ? 'selected' : '' }}>40ft
                        High Cube</option>
                    <option value="45ft Container" {{ old('container_type') == '45ft Container' ? 'selected' : '' }}>45ft
                        Container</option>
                </select>
                @error('container_type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Shipping Date</label>
                <input type="date" name="shipping_date" class="form-input" value="{{ old('shipping_date') }}">
                @error('shipping_date')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Estimated Arrival (ETA)</label>
                <input type="date" name="eta" class="form-input" value="{{ old('eta') }}">
                @error('eta')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Freight Cost (USD)</label>
                <input type="number" name="freight_cost" class="form-input" step="0.01" placeholder="0.00"
                    value="{{ old('freight_cost') }}">
                @error('freight_cost')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Weight (kg)</label>
                <input type="number" name="weight" class="form-input" step="0.01" value="{{ old('weight') }}">
                @error('weight')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Volume (m³)</label>
                <input type="number" name="volume" class="form-input" step="0.01" value="{{ old('volume') }}">
                @error('volume')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="Pending" {{ old('status', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Transit" {{ old('status') == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="At Port" {{ old('status') == 'At Port' ? 'selected' : '' }}>At Port</option>
                    <option value="Delivered" {{ old('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Cargo Description</label>
                <textarea name="cargo_description" class="form-input" rows="3" placeholder="Describe the cargo being shipped...">{{ old('cargo_description') }}</textarea>
                @error('cargo_description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Special Instructions</label>
                <textarea name="special_instructions" class="form-input" rows="3"
                    placeholder="Any special handling instructions...">{{ old('special_instructions') }}</textarea>
                @error('special_instructions')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

        </div>
        <div class="form-group mb-3">
            <label class="form-label">Assign to Employee</label>
            <select name="assigned_to" class="form-select">
                <option value="">Select Employee</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}"
                        data-avatar="{{ $employee->user ? $employee->user->getAvatarUrl(32) : '' }}"
                        {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} - {{ $employee->position }}
                        @if ($employee->user)
                            ({{ $employee->user->roles->first()->name ?? 'Employee' }})
                        @endif
                    </option>
                @endforeach
            </select>

            {{-- Preview selected user --}}
            <div id="selected-user-preview" class="mt-2" style="display: none;">
                <div class="d-flex align-items-center">
                    <div id="preview-avatar"></div>
                    <div class="ms-3">
                        <strong id="preview-name"></strong>
                        <br>
                        <small class="text-muted" id="preview-role"></small>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary">Create Shipment</button>
                <a href="{{ route('management.shipments.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
    </form>

    @if ($companies->isEmpty() || $ports->isEmpty())
        <div
            style="margin-top: 30px; padding: 20px; background: #fef3c7; border: 1px solid #f59e0b; border-radius: 10px; color: #92400e;">
            <h4 style="margin-bottom: 10px;">⚠️ Setup Required</h4>
            <p style="margin-bottom: 15px;">To create shipments, you need:</p>
            <ul style="margin: 0; padding-left: 20px;">
                @if ($companies->isEmpty())
                    <li>At least one company. <a href="{{ route('management.companies.create') }}"
                            style="color: #1e40af;">Create a company</a></li>
                @endif
                @if ($ports->isEmpty())
                    <li>At least two ports. <a href="{{ route('logistics.ports.index') }}" style="color: #1e40af;">Manage
                            ports</a></li>
                @endif
            </ul>
        </div>
    @endif
@endsection
