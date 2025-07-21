@extends('layouts.app')

@section('title', 'Add New Destination')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Add New Destination</h1>
        <p style="color: #64748b;">Create a new delivery destination</p>
    </div>

    <form action="{{ route('submenu.destinations.store') }}" method="POST">
        @csrf

        <!-- Basic Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Destination Code *</label>
                        <input type="text" name="destination_code" class="form-input" required
                            value="{{ old('destination_code') }}" placeholder="e.g., DST001">
                        @error('destination_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Destination Name *</label>
                        <input type="text" name="destination_name" class="form-input" required
                            value="{{ old('destination_name') }}" placeholder="Destination name">
                        @error('destination_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Destination Type *</label>
                        <select name="destination_type" class="form-input" required>
                            <option value="">Select Type</option>
                            <option value="Port" {{ old('destination_type') == 'Port' ? 'selected' : '' }}>🚢 Port
                            </option>
                            <option value="Airport" {{ old('destination_type') == 'Airport' ? 'selected' : '' }}>✈️ Airport
                            </option>
                            <option value="Warehouse" {{ old('destination_type') == 'Warehouse' ? 'selected' : '' }}>🏪
                                Warehouse</option>
                            <option value="Factory" {{ old('destination_type') == 'Factory' ? 'selected' : '' }}>🏭 Factory
                            </option>
                            <option value="City" {{ old('destination_type') == 'City' ? 'selected' : '' }}>🏙️ City Center
                            </option>
                            <option value="Terminal" {{ old('destination_type') == 'Terminal' ? 'selected' : '' }}>📦
                                Terminal</option>
                            <option value="Depot" {{ old('destination_type') == 'Depot' ? 'selected' : '' }}>🚛 Depot
                            </option>
                        </select>
                        @error('destination_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <input type="text" name="city" class="form-input" required value="{{ old('city') }}"
                            placeholder="City name">
                        @error('city')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">State/Province</label>
                        <input type="text" name="state_province" class="form-input" value="{{ old('state_province') }}"
                            placeholder="State or province">
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
                        <label class="form-label">Timezone</label>
                        <input type="text" name="timezone" class="form-input" value="{{ old('timezone') }}"
                            placeholder="e.g., UTC+2, America/New_York">
                        @error('timezone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-input" rows="3" placeholder="Complete address with details">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
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
                            value="{{ old('contact_person') }}" placeholder="Primary contact name">
                        @error('contact_person')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-input"
                            value="{{ old('contact_phone') }}" placeholder="+20 1234 567890">
                        @error('contact_phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-input"
                            value="{{ old('contact_email') }}" placeholder="contact@destination.com">
                        @error('contact_email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Facilities & Services -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏢 Facilities & Services</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Available Facilities</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $facilitiesList = [
                                'Loading Dock',
                                'Unloading Dock',
                                'Storage Area',
                                'Cold Storage',
                                'Customs Office',
                                'Security Gate',
                                'Weighbridge',
                                'Container Yard',
                                'Parking Area',
                                'Office Building',
                            ];
                            $selectedFacilities = old('facilities', []);
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

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="requires_appointment" value="1"
                            {{ old('requires_appointment') ? 'checked' : '' }}>
                        Requires Appointment for Delivery
                    </label>
                </div>
            </div>
        </div>

        <!-- Delivery Instructions -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📝 Delivery Instructions & Restrictions</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Delivery Instructions</label>
                    <textarea name="delivery_instructions" class="form-input" rows="3"
                        placeholder="Special delivery instructions, operating hours, contact procedures...">{{ old('delivery_instructions') }}</textarea>
                    @error('delivery_instructions')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Access Restrictions</label>
                    <textarea name="access_restrictions" class="form-input" rows="3"
                        placeholder="Time restrictions, security requirements, vehicle limitations...">{{ old('access_restrictions') }}</textarea>
                    @error('access_restrictions')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('submenu.destinations.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Destination</button>
        </div>
    </form>
@endsection
