@extends('layouts.app')

@section('title', 'Add New Container Loading Point')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Add New Container Loading
            Point</h1>
        <p style="color: #64748b;">Create a new container loading facility</p>
    </div>

    <form action="{{ route('submenu.container-loading.store') }}" method="POST">
        @csrf

        <!-- Basic Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Loading Point Code *</label>
                        <input type="text" name="loading_point_code" class="form-input" required
                            value="{{ old('loading_point_code') }}" placeholder="e.g., LP001">
                        @error('loading_point_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Loading Point Name *</label>
                        <input type="text" name="loading_point_name" class="form-input" required
                            value="{{ old('loading_point_name') }}" placeholder="Loading facility name">
                        @error('loading_point_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Facility Type *</label>
                        <select name="facility_type" class="form-input" required>
                            <option value="">Select Facility Type</option>
                            <option value="CFS" {{ old('facility_type') == 'CFS' ? 'selected' : '' }}>Container Freight
                                Station (CFS)</option>
                            <option value="Warehouse" {{ old('facility_type') == 'Warehouse' ? 'selected' : '' }}>Warehouse
                            </option>
                            <option value="Factory" {{ old('facility_type') == 'Factory' ? 'selected' : '' }}>Factory
                            </option>
                            <option value="Port Terminal" {{ old('facility_type') == 'Port Terminal' ? 'selected' : '' }}>
                                Port Terminal</option>
                            <option value="Depot" {{ old('facility_type') == 'Depot' ? 'selected' : '' }}>Container Depot
                            </option>
                            <option value="Container Yard"
                                {{ old('facility_type') == 'Container Yard' ? 'selected' : '' }}>Container Yard</option>
                            <option value="Inland Terminal"
                                {{ old('facility_type') == 'Inland Terminal' ? 'selected' : '' }}>Inland Terminal</option>
                        </select>
                        @error('facility_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Operator Name *</label>
                        <input type="text" name="operator_name" class="form-input" required
                            value="{{ old('operator_name') }}" placeholder="Operating company name">
                        @error('operator_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Under
                                Maintenance</option>
                        </select>
                        @error('status')
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
                        <label class="form-label">Contact Person *</label>
                        <input type="text" name="contact_person" class="form-input" required
                            value="{{ old('contact_person') }}" placeholder="Primary contact name">
                        @error('contact_person')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone *</label>
                        <input type="text" name="phone" class="form-input" required value="{{ old('phone') }}"
                            placeholder="+20 1234 567890">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}"
                            placeholder="contact@facility.com">
                        @error('email')
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
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-input" required rows="3" placeholder="Complete facility address">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" class="form-input" required value="{{ old('city') }}"
                            placeholder="City name">
                        @error('city')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Country *</label>
                        <select name="country_id" class="form-input" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id') == $country->id ? 'selected' : '' }}>
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
                        <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code') }}"
                            placeholder="Postal/ZIP code">
                        @error('postal_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" class="form-input" step="0.00000001"
                            value="{{ old('latitude') }}" placeholder="30.0444">
                        @error('latitude')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" class="form-input" step="0.00000001"
                            value="{{ old('longitude') }}" placeholder="31.2357">
                        @error('longitude')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Details -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>⚙️ Operational Details</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Max Containers Per Day</label>
                        <input type="number" name="max_containers_per_day" class="form-input" min="1"
                            value="{{ old('max_containers_per_day') }}" placeholder="100">
                        @error('max_containers_per_day')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Advance Booking Hours</label>
                        <input type="number" name="advance_booking_hours" class="form-input" min="1"
                            max="168" value="{{ old('advance_booking_hours', 24) }}" placeholder="24">
                        @error('advance_booking_hours')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Container Types Handled</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                        @php
                            $containerTypes = [
                                '20GP',
                                '40GP',
                                '40HC',
                                '45HC',
                                '20RF',
                                '40RF',
                                '20OT',
                                '40OT',
                                '20FR',
                                '40FR',
                            ];
                            $oldTypes = old('container_types_handled', []);
                        @endphp
                        @foreach ($containerTypes as $type)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="container_types_handled[]" value="{{ $type }}"
                                    {{ in_array($type, $oldTypes) ? 'checked' : '' }}>
                                {{ $type }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Equipment Available</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $equipment = [
                                'Mobile Crane',
                                'Reach Stacker',
                                'Forklift',
                                'Side Loader',
                                'Top Loader',
                                'Weighbridge',
                                'Scanner',
                            ];
                            $oldEquipment = old('equipment_available', []);
                        @endphp
                        @foreach ($equipment as $item)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="equipment_available[]" value="{{ $item }}"
                                    {{ in_array($item, $oldEquipment) ? 'checked' : '' }}>
                                {{ $item }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Services Offered</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $services = [
                                'Container Stuffing',
                                'Container Destuffing',
                                'Storage',
                                'Customs Inspection',
                                'Documentation',
                                'Cargo Consolidation',
                                'Trans-loading',
                            ];
                            $oldServices = old('services_offered', []);
                        @endphp
                        @foreach ($services as $service)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="services_offered[]" value="{{ $service }}"
                                    {{ in_array($service, $oldServices) ? 'checked' : '' }}>
                                {{ $service }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Security -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>💰 Pricing & Security</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Storage Rate Per Day (USD)</label>
                        <input type="number" name="storage_rate_per_day" class="form-input" step="0.01"
                            min="0" value="{{ old('storage_rate_per_day') }}" placeholder="50.00">
                        @error('storage_rate_per_day')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stuffing Rate (USD)</label>
                        <input type="number" name="stuffing_rate" class="form-input" step="0.01" min="0"
                            value="{{ old('stuffing_rate') }}" placeholder="200.00">
                        @error('stuffing_rate')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Destuffing Rate (USD)</label>
                        <input type="number" name="destuffing_rate" class="form-input" step="0.01" min="0"
                            value="{{ old('destuffing_rate') }}" placeholder="150.00">
                        @error('destuffing_rate')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="has_security" value="1"
                                {{ old('has_security', '1') ? 'checked' : '' }}>
                            Has Security Personnel
                        </label>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="has_cctv" value="1"
                                {{ old('has_cctv') ? 'checked' : '' }}>
                            Has CCTV Surveillance
                        </label>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="requires_appointment" value="1"
                                {{ old('requires_appointment', '1') ? 'checked' : '' }}>
                            Requires Appointment
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📝 Additional Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Access Instructions</label>
                    <textarea name="access_instructions" class="form-input" rows="3"
                        placeholder="Instructions for accessing the facility">{{ old('access_instructions') }}</textarea>
                    @error('access_instructions')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Safety Requirements</label>
                    <textarea name="safety_requirements" class="form-input" rows="3"
                        placeholder="Safety protocols and requirements">{{ old('safety_requirements') }}</textarea>
                    @error('safety_requirements')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="Additional notes about the facility">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('submenu.container-loading.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Loading Point</button>
        </div>
    </form>
@endsection
