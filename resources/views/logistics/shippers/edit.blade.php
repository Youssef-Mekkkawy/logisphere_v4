@extends('layouts.app')

@section('title', 'Edit Shipper')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Edit Shipper</h1>
        <p style="color: #64748b;">Update shipper information - {{ $shipper->shipper_name }}</p>
    </div>

    <form action="{{ route('logistics.shippers.update', $shipper) }}" method="POST">
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
                        <label class="form-label">Shipper Code *</label>
                        <input type="text" name="shipper_code" class="form-input" required
                            value="{{ old('shipper_code', $shipper->shipper_code) }}" placeholder="e.g., EG001, US002">
                        @error('shipper_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Shipper Name *</label>
                        <input type="text" name="shipper_name" class="form-input" required
                            value="{{ old('shipper_name', $shipper->shipper_name) }}" placeholder="Shipper business name">
                        @error('shipper_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Company Name *</label>
                        <input type="text" name="company_name" class="form-input" required
                            value="{{ old('company_name', $shipper->company_name) }}" placeholder="Legal company name">
                        @error('company_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Shipper Type *</label>
                        <select name="shipper_type" class="form-input" required>
                            <option value="">Select Type</option>
                            <option value="Manufacturer"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Manufacturer' ? 'selected' : '' }}>🏭
                                Manufacturer</option>
                            <option value="Exporter"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Exporter' ? 'selected' : '' }}>📦
                                Exporter</option>
                            <option value="Trading Company"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Trading Company' ? 'selected' : '' }}>🏢
                                Trading Company</option>
                            <option value="Freight Forwarder"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Freight Forwarder' ? 'selected' : '' }}>
                                🚛 Freight Forwarder</option>
                            <option value="Agent"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Agent' ? 'selected' : '' }}>👔 Agent
                            </option>
                            <option value="Importer"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Importer' ? 'selected' : '' }}>📥
                                Importer</option>
                            <option value="Distributor"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Distributor' ? 'selected' : '' }}>🏪
                                Distributor</option>
                            <option value="Retailer"
                                {{ old('shipper_type', $shipper->shipper_type) == 'Retailer' ? 'selected' : '' }}>🛒
                                Retailer</option>
                        </select>
                        @error('shipper_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Business License</label>
                        <input type="text" name="business_license" class="form-input"
                            value="{{ old('business_license', $shipper->business_license) }}"
                            placeholder="Business license number">
                        @error('business_license')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tax ID</label>
                        <input type="text" name="tax_id" class="form-input"
                            value="{{ old('tax_id', $shipper->tax_id) }}" placeholder="Tax identification number">
                        @error('tax_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active" {{ old('status', $shipper->status) == 'Active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="Inactive" {{ old('status', $shipper->status) == 'Inactive' ? 'selected' : '' }}>
                                Inactive</option>
                            <option value="Suspended"
                                {{ old('status', $shipper->status) == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="Pending" {{ old('status', $shipper->status) == 'Pending' ? 'selected' : '' }}>
                                Pending</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Established Year</label>
                        <input type="number" name="established_year" class="form-input" min="1800"
                            max="{{ date('Y') }}" value="{{ old('established_year', $shipper->established_year) }}"
                            placeholder="e.g., 2005">
                        @error('established_year')
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
                    <textarea name="address" class="form-input" rows="3" required placeholder="Complete business address">{{ old('address', $shipper->address) }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" class="form-input" required
                            value="{{ old('city', $shipper->city) }}" placeholder="City name">
                        @error('city')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">State/Province</label>
                        <input type="text" name="state_province" class="form-input"
                            value="{{ old('state_province', $shipper->state_province) }}"
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
                                    {{ old('country_id', $shipper->country_id) == $country->id ? 'selected' : '' }}>
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
                            value="{{ old('postal_code', $shipper->postal_code) }}" placeholder="Postal/ZIP code">
                        @error('postal_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" class="form-input" step="0.00000001"
                            value="{{ old('latitude', $shipper->latitude) }}" placeholder="30.0444">
                        @error('latitude')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" class="form-input" step="0.00000001"
                            value="{{ old('longitude', $shipper->longitude) }}" placeholder="31.2357">
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
                        <label class="form-label">Contact Person *</label>
                        <input type="text" name="contact_person" class="form-input" required
                            value="{{ old('contact_person', $shipper->contact_person) }}"
                            placeholder="Primary contact name">
                        @error('contact_person')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Phone *</label>
                        <input type="text" name="contact_phone" class="form-input" required
                            value="{{ old('contact_phone', $shipper->contact_phone) }}" placeholder="+20 1234 567890">
                        @error('contact_phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Email *</label>
                        <input type="email" name="contact_email" class="form-input" required
                            value="{{ old('contact_email', $shipper->contact_email) }}"
                            placeholder="contact@company.com">
                        @error('contact_email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-input"
                            value="{{ old('website', $shipper->website) }}" placeholder="https://www.company.com">
                        @error('website')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alternative Phone</label>
                        <input type="text" name="alternative_phone" class="form-input"
                            value="{{ old('alternative_phone', $shipper->alternative_phone) }}"
                            placeholder="+20 1234 567891">
                        @error('alternative_phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact" class="form-input"
                            value="{{ old('emergency_contact', $shipper->emergency_contact) }}"
                            placeholder="Name - Phone">
                        @error('emergency_contact')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏭 Business Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Industry Type</label>
                        <input type="text" name="industry_type" class="form-input"
                            value="{{ old('industry_type', $shipper->industry_type) }}"
                            placeholder="e.g., Manufacturing, Agriculture">
                        @error('industry_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Annual Volume (TEU)</label>
                        <input type="number" name="annual_volume" class="form-input" min="0"
                            value="{{ old('annual_volume', $shipper->annual_volume) }}" placeholder="e.g., 5000">
                        @error('annual_volume')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Operating Hours</label>
                        <input type="text" name="operating_hours" class="form-input"
                            value="{{ old('operating_hours', $shipper->operating_hours) }}"
                            placeholder="e.g., Mon-Fri 8AM-6PM">
                        @error('operating_hours')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Time Zone</label>
                        <input type="text" name="time_zone" class="form-input"
                            value="{{ old('time_zone', $shipper->time_zone) }}" placeholder="e.g., Africa/Cairo">
                        @error('time_zone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Specialization</label>
                    <textarea name="specialization" class="form-input" rows="3"
                        placeholder="Describe what this shipper specializes in">{{ old('specialization', $shipper->specialization) }}</textarea>
                    @error('specialization')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Financial Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>💰 Financial Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Credit Rating</label>
                        <select name="credit_rating" class="form-input">
                            <option value="">Select Rating</option>
                            <option value="A+"
                                {{ old('credit_rating', $shipper->credit_rating) == 'A+' ? 'selected' : '' }}>A+
                                (Excellent)</option>
                            <option value="A"
                                {{ old('credit_rating', $shipper->credit_rating) == 'A' ? 'selected' : '' }}>A (Very Good)
                            </option>
                            <option value="A-"
                                {{ old('credit_rating', $shipper->credit_rating) == 'A-' ? 'selected' : '' }}>A- (Good)
                            </option>
                            <option value="B+"
                                {{ old('credit_rating', $shipper->credit_rating) == 'B+' ? 'selected' : '' }}>B+ (Fair)
                            </option>
                            <option value="B"
                                {{ old('credit_rating', $shipper->credit_rating) == 'B' ? 'selected' : '' }}>B (Fair)
                            </option>
                            <option value="B-"
                                {{ old('credit_rating', $shipper->credit_rating) == 'B-' ? 'selected' : '' }}>B- (Poor)
                            </option>
                            <option value="C+"
                                {{ old('credit_rating', $shipper->credit_rating) == 'C+' ? 'selected' : '' }}>C+ (Poor)
                            </option>
                            <option value="C"
                                {{ old('credit_rating', $shipper->credit_rating) == 'C' ? 'selected' : '' }}>C (Very Poor)
                            </option>
                            <option value="C-"
                                {{ old('credit_rating', $shipper->credit_rating) == 'C-' ? 'selected' : '' }}>C-
                                (Unacceptable)</option>
                        </select>
                        @error('credit_rating')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Terms</label>
                        <input type="text" name="payment_terms" class="form-input"
                            value="{{ old('payment_terms', $shipper->payment_terms) }}"
                            placeholder="e.g., Net 30, Net 15">
                        @error('payment_terms')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Credit Limit</label>
                        <input type="number" name="credit_limit" class="form-input" step="0.01" min="0"
                            value="{{ old('credit_limit', $shipper->credit_limit) }}" placeholder="e.g., 100000">
                        @error('credit_limit')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Currency Preference</label>
                        <select name="currency_preference" class="form-input">
                            <option value="USD"
                                {{ old('currency_preference', $shipper->currency_preference) == 'USD' ? 'selected' : '' }}>
                                USD</option>
                            <option value="EUR"
                                {{ old('currency_preference', $shipper->currency_preference) == 'EUR' ? 'selected' : '' }}>
                                EUR</option>
                            <option value="GBP"
                                {{ old('currency_preference', $shipper->currency_preference) == 'GBP' ? 'selected' : '' }}>
                                GBP</option>
                            <option value="EGP"
                                {{ old('currency_preference', $shipper->currency_preference) == 'EGP' ? 'selected' : '' }}>
                                EGP</option>
                            <option value="AED"
                                {{ old('currency_preference', $shipper->currency_preference) == 'AED' ? 'selected' : '' }}>
                                AED</option>
                            <option value="CNY"
                                {{ old('currency_preference', $shipper->currency_preference) == 'CNY' ? 'selected' : '' }}>
                                CNY</option>
                        </select>
                        @error('currency_preference')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-input"
                            value="{{ old('bank_name', $shipper->bank_name) }}" placeholder="Primary bank name">
                        @error('bank_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bank Account</label>
                        <input type="text" name="bank_account" class="form-input"
                            value="{{ old('bank_account', $shipper->bank_account) }}"
                            placeholder="Account number or reference">
                        @error('bank_account')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Capabilities & Features -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏢 Capabilities & Features</h3>
            </div>
            <div class="card-body">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="insurance_coverage" value="1"
                            {{ old('insurance_coverage', $shipper->insurance_coverage) ? 'checked' : '' }}>
                        Insurance Coverage Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="customs_broker" value="1"
                            {{ old('customs_broker', $shipper->customs_broker) ? 'checked' : '' }}>
                        Licensed Customs Broker
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="freight_forwarder" value="1"
                            {{ old('freight_forwarder', $shipper->freight_forwarder) ? 'checked' : '' }}>
                        Freight Forwarding Services
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="dangerous_goods_certified" value="1"
                            {{ old('dangerous_goods_certified', $shipper->dangerous_goods_certified) ? 'checked' : '' }}>
                        Dangerous Goods Certified
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="track_and_trace_required" value="1"
                            {{ old('track_and_trace_required', $shipper->track_and_trace_required) ? 'checked' : '' }}>
                        Track & Trace Required
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="email_notifications" value="1"
                            {{ old('email_notifications', $shipper->email_notifications) ? 'checked' : '' }}>
                        Email Notifications
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="sms_notifications" value="1"
                            {{ old('sms_notifications', $shipper->sms_notifications) ? 'checked' : '' }}>
                        SMS Notifications
                    </label>
                </div>
            </div>
        </div>

        <!-- Cargo Types -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📦 Cargo Types</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Cargo Types Handled</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $cargoTypesList = [
                                'General Cargo',
                                'Containerized Goods',
                                'Break Bulk',
                                'Bulk Cargo',
                                'Liquid Bulk',
                                'Dry Bulk',
                                'Refrigerated Cargo',
                                'Frozen Goods',
                                'Electronics',
                                'Automotive',
                                'Machinery',
                                'Textiles',
                                'Agricultural Products',
                                'Food Products',
                                'Chemical Products',
                                'Pharmaceutical',
                                'Dangerous Goods',
                                'Oversized Cargo',
                                'Project Cargo',
                                'High Value Cargo',
                            ];
                            $selectedCargoTypes = old('cargo_types', $shipper->cargo_types ?? []);
                        @endphp
                        @foreach ($cargoTypesList as $cargoType)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="cargo_types[]" value="{{ $cargoType }}"
                                    {{ in_array($cargoType, $selectedCargoTypes) ? 'checked' : '' }}>
                                {{ $cargoType }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Offered -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🛠️ Services Offered</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Services Provided</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $servicesList = [
                                'Export Services',
                                'Import Services',
                                'Freight Forwarding',
                                'Customs Clearance',
                                'Documentation',
                                'Quality Control',
                                'Packaging Services',
                                'Labeling Services',
                                'Consolidation',
                                'Warehousing',
                                'Distribution',
                                'Transportation',
                                'Insurance Services',
                                'Tracking Services',
                                'Logistics Coordination',
                                'Supply Chain Management',
                            ];
                            $selectedServices = old('services_offered', $shipper->services_offered ?? []);
                        @endphp
                        @foreach ($servicesList as $service)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="services_offered[]" value="{{ $service }}"
                                    {{ in_array($service, $selectedServices) ? 'checked' : '' }}>
                                {{ $service }}
                            </label>
                        @endforeach
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
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Sales Representative</label>
                        <input type="text" name="sales_representative" class="form-input"
                            value="{{ old('sales_representative', $shipper->sales_representative) }}"
                            placeholder="Assigned sales rep">
                        @error('sales_representative')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Account Manager</label>
                        <input type="text" name="account_manager" class="form-input"
                            value="{{ old('account_manager', $shipper->account_manager) }}"
                            placeholder="Assigned account manager">
                        @error('account_manager')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contract Start Date</label>
                        <input type="date" name="contract_start_date" class="form-input"
                            value="{{ old('contract_start_date', $shipper->contract_start_date?->format('Y-m-d')) }}">
                        @error('contract_start_date')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contract End Date</label>
                        <input type="date" name="contract_end_date" class="form-input"
                            value="{{ old('contract_end_date', $shipper->contract_end_date?->format('Y-m-d')) }}">
                        @error('contract_end_date')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="4" placeholder="Additional notes about this shipper">{{ old('notes', $shipper->notes) }}</textarea>
                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('logistics.shippers.show', $shipper) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Shipper</button>
        </div>
    </form>
@endsection
