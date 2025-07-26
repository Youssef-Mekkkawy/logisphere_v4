@extends('layouts.app')

@section('title', 'Edit Port')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Edit Port</h1>
        <p style="color: #64748b;">Update port information - {{ $port->port_name }}</p>
    </div>

    <form action="{{ route('logistics.ports.update', $port) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Port Code *</label>
                        <input type="text" name="port_code" class="form-input" required
                            value="{{ old('port_code', $port->port_code) }}" placeholder="e.g., EGALY, USLAX">
                        @error('port_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Port Name *</label>
                        <input type="text" name="port_name" class="form-input" required
                            value="{{ old('port_name', $port->port_name) }}" placeholder="Port name">
                        @error('port_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Port Type *</label>
                        <select name="port_type" class="form-input" required>
                            <option value="">Select Type</option>
                            <option value="Seaport" {{ old('port_type', $port->port_type) == 'Seaport' ? 'selected' : '' }}>
                                🚢 Seaport</option>
                            <option value="Airport" {{ old('port_type', $port->port_type) == 'Airport' ? 'selected' : '' }}>
                                ✈️ Airport</option>
                            <option value="Dry Port"
                                {{ old('port_type', $port->port_type) == 'Dry Port' ? 'selected' : '' }}>🏭 Dry Port
                            </option>
                            <option value="Container Terminal"
                                {{ old('port_type', $port->port_type) == 'Container Terminal' ? 'selected' : '' }}>📦
                                Container Terminal</option>
                            <option value="Bulk Terminal"
                                {{ old('port_type', $port->port_type) == 'Bulk Terminal' ? 'selected' : '' }}>⚖️ Bulk
                                Terminal</option>
                            <option value="Oil Terminal"
                                {{ old('port_type', $port->port_type) == 'Oil Terminal' ? 'selected' : '' }}>🛢️ Oil
                                Terminal</option>
                            <option value="Ferry Terminal"
                                {{ old('port_type', $port->port_type) == 'Ferry Terminal' ? 'selected' : '' }}>⛴️ Ferry
                                Terminal</option>
                            <option value="Fishing Port"
                                {{ old('port_type', $port->port_type) == 'Fishing Port' ? 'selected' : '' }}>🎣 Fishing
                                Port</option>
                            <option value="Marina" {{ old('port_type', $port->port_type) == 'Marina' ? 'selected' : '' }}>⛵
                                Marina</option>
                        </select>
                        @error('port_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active" {{ old('status', $port->status) == 'Active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="Inactive" {{ old('status', $port->status) == 'Inactive' ? 'selected' : '' }}>
                                Inactive</option>
                            <option value="Under Construction"
                                {{ old('status', $port->status) == 'Under Construction' ? 'selected' : '' }}>Under
                                Construction</option>
                            <option value="Closed" {{ old('status', $port->status) == 'Closed' ? 'selected' : '' }}>Closed
                            </option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📍 Location Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" class="form-input" required
                            value="{{ old('city', $port->city) }}" placeholder="City name">
                        @error('city')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">State/Province</label>
                        <input type="text" name="state_province" class="form-input"
                            value="{{ old('state_province', $port->state_province) }}" placeholder="State or province">
                        @error('state_province')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Country *</label>
                        <select name="country_id" class="form-input" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id', $port->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-input"
                            value="{{ old('postal_code', $port->postal_code) }}" placeholder="Postal/ZIP code">
                        @error('postal_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Time Zone</label>
                        <input type="text" name="time_zone" class="form-input"
                            value="{{ old('time_zone', $port->time_zone) }}" placeholder="e.g., UTC+2, America/New_York">
                        @error('time_zone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-input" rows="3" placeholder="Complete address with details">{{ old('address', $port->address) }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" class="form-input" step="0.00000001"
                            value="{{ old('latitude', $port->latitude) }}" placeholder="30.0444">
                        @error('latitude')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" class="form-input" step="0.00000001"
                            value="{{ old('longitude', $port->longitude) }}" placeholder="31.2357">
                        @error('longitude')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📞 Contact Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-input"
                            value="{{ old('contact_person', $port->contact_person) }}"
                            placeholder="Primary contact name">
                        @error('contact_person')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-input"
                            value="{{ old('contact_phone', $port->contact_phone) }}" placeholder="+20 1234 567890">
                        @error('contact_phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-input"
                            value="{{ old('contact_email', $port->contact_email) }}" placeholder="contact@port.com">
                        @error('contact_email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-input"
                            value="{{ old('website', $port->website) }}" placeholder="https://www.port.com">
                        @error('website')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Operating Hours</label>
                    <input type="text" name="operating_hours" class="form-input"
                        value="{{ old('operating_hours', $port->operating_hours) }}"
                        placeholder="e.g., 24/7, Mon-Fri 8AM-6PM">
                    @error('operating_hours')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Port Specifications -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>⚓ Port Specifications</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Max Capacity (TEU)</label>
                        <input type="number" name="max_capacity" class="form-input" min="0"
                            value="{{ old('max_capacity', $port->max_capacity) }}" placeholder="e.g., 1000000">
                        @error('max_capacity')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total Berths</label>
                        <input type="number" name="total_berths" class="form-input" min="0"
                            value="{{ old('total_berths', $port->total_berths) }}" placeholder="e.g., 25">
                        @error('total_berths')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Max Vessel Size (DWT)</label>
                        <input type="number" name="max_vessel_size" class="form-input" step="0.01" min="0"
                            value="{{ old('max_vessel_size', $port->max_vessel_size) }}" placeholder="e.g., 400000">
                        @error('max_vessel_size')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Draft Depth (meters)</label>
                        <input type="number" name="draft_depth" class="form-input" step="0.01" min="0"
                            value="{{ old('draft_depth', $port->draft_depth) }}" placeholder="e.g., 16.5">
                        @error('draft_depth')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Storage Capacity (m²)</label>
                        <input type="number" name="storage_capacity" class="form-input" min="0"
                            value="{{ old('storage_capacity', $port->storage_capacity) }}" placeholder="e.g., 500000">
                        @error('storage_capacity')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Port Authority</label>
                        <input type="text" name="port_authority" class="form-input"
                            value="{{ old('port_authority', $port->port_authority) }}"
                            placeholder="e.g., Alexandria Port Authority">
                        @error('port_authority')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Port Features -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏢 Port Features</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="major_port" value="1"
                            {{ old('major_port', $port->major_port) ? 'checked' : '' }}>
                        This is a major port
                    </label>
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="customs_available" value="1"
                            {{ old('customs_available', $port->customs_available) ? 'checked' : '' }}>
                        Customs Services Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="quarantine_available" value="1"
                            {{ old('quarantine_available', $port->quarantine_available) ? 'checked' : '' }}>
                        Quarantine Facilities Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="pilotage_compulsory" value="1"
                            {{ old('pilotage_compulsory', $port->pilotage_compulsory) ? 'checked' : '' }}>
                        Pilotage Compulsory
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="rail_connection" value="1"
                            {{ old('rail_connection', $port->rail_connection) ? 'checked' : '' }}>
                        Rail Connection Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="road_connection" value="1"
                            {{ old('road_connection', $port->road_connection) ? 'checked' : '' }}>
                        Road Connection Available
                    </label>
                </div>
            </div>
        </div>

        <!-- Facilities -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏗️ Available Facilities</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Port Facilities</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $facilitiesList = [
                                'Container Handling',
                                'Bulk Cargo Handling',
                                'Break Bulk Handling',
                                'Liquid Bulk Handling',
                                'Ro-Ro Facilities',
                                'Cold Storage',
                                'Dry Storage',
                                'Customs Office',
                                'Port Security',
                                'Pilotage Service',
                                'Tugboat Service',
                                'Bunkering',
                                'Fresh Water Supply',
                                'Waste Disposal',
                                'Ship Repair',
                                'Cargo Inspection',
                                'Quarantine Station',
                                'Free Trade Zone',
                            ];
                            $selectedFacilities = old('facilities', $port->facilities ?? []);
                        @endphp
                        @foreach ($facilitiesList as $facility)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="facilities[]" value="{{ $facility }}"
                                    {{ in_array($facility, $selectedFacilities) ? 'checked' : '' }}>
                                {{ $facility }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🛠️ Available Services</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Port Services</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $servicesList = [
                                'Stevedoring',
                                'Cargo Handling',
                                'Storage & Warehousing',
                                'Container Services',
                                'Customs Clearance',
                                'Port Agency',
                                'Ship Chandling',
                                'Bunker Supply',
                                'Technical Services',
                                'Logistics Services',
                                'Transportation',
                                'Documentation',
                                'Insurance Services',
                                'Banking Services',
                                'Communication Services',
                                'Emergency Services',
                            ];
                            $selectedServices = old('services', $port->services ?? []);
                        @endphp
                        @foreach ($servicesList as $service)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="services[]" value="{{ $service }}"
                                    {{ in_array($service, $selectedServices) ? 'checked' : '' }}>
                                {{ $service }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Handling Equipment -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏗️ Handling Equipment</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Available Equipment</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $equipmentList = [
                                'Container Cranes',
                                'Mobile Cranes',
                                'Reach Stackers',
                                'Forklifts',
                                'Terminal Tractors',
                                'Conveyor Systems',
                                'Bulk Loaders',
                                'Ship Loaders',
                                'Pipelines',
                                'Storage Tanks',
                                'Weighbridges',
                                'Rail Cranes',
                                'Floating Cranes',
                            ];
                            $selectedEquipment = old('handling_equipment', $port->handling_equipment ?? []);
                        @endphp
                        @foreach ($equipmentList as $equipment)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="handling_equipment[]" value="{{ $equipment }}"
                                    {{ in_array($equipment, $selectedEquipment) ? 'checked' : '' }}>
                                {{ $equipment }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('logistics.ports.show', $port) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Port</button>
        </div>
    </form>
@endsection
