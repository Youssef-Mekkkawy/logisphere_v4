@extends('layouts.app')

@section('title', 'Add New Service')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Add New Service</h1>
        <p style="color: #64748b;">Create a new logistics service</p>
    </div>

    <form action="{{ route('logistics.services.store') }}" method="POST">
        @csrf

        <!-- Basic Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Service Code *</label>
                        <input type="text" name="service_code" class="form-input" required
                            value="{{ old('service_code') }}" placeholder="e.g., CUS001, TRN002">
                        @error('service_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Service Name *</label>
                        <input type="text" name="service_name" class="form-input" required
                            value="{{ old('service_name') }}" placeholder="Service name">
                        @error('service_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Service Category *</label>
                        <select name="service_category" class="form-input" required>
                            <option value="">Select Category</option>
                            <option value="Customs Clearance"
                                {{ old('service_category') == 'Customs Clearance' ? 'selected' : '' }}>🛃 Customs Clearance
                            </option>
                            <option value="Transportation"
                                {{ old('service_category') == 'Transportation' ? 'selected' : '' }}>🚛 Transportation
                            </option>
                            <option value="Warehousing" {{ old('service_category') == 'Warehousing' ? 'selected' : '' }}>🏭
                                Warehousing</option>
                            <option value="Documentation"
                                {{ old('service_category') == 'Documentation' ? 'selected' : '' }}>📄 Documentation</option>
                            <option value="Insurance" {{ old('service_category') == 'Insurance' ? 'selected' : '' }}>🛡️
                                Insurance</option>
                            <option value="Inspection" {{ old('service_category') == 'Inspection' ? 'selected' : '' }}>🔍
                                Inspection</option>
                            <option value="Cargo Handling"
                                {{ old('service_category') == 'Cargo Handling' ? 'selected' : '' }}>📦 Cargo Handling
                            </option>
                            <option value="Port Services"
                                {{ old('service_category') == 'Port Services' ? 'selected' : '' }}>⚓ Port Services</option>
                            <option value="Freight Forwarding"
                                {{ old('service_category') == 'Freight Forwarding' ? 'selected' : '' }}>🚢 Freight
                                Forwarding</option>
                            <option value="Consulting" {{ old('service_category') == 'Consulting' ? 'selected' : '' }}>💼
                                Consulting</option>
                            <option value="Other" {{ old('service_category') == 'Other' ? 'selected' : '' }}>🔧 Other
                            </option>
                        </select>
                        @error('service_category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Service Provider *</label>
                        <select name="service_provider" class="form-input" required>
                            <option value="">Select Provider</option>
                            <option value="Internal" {{ old('service_provider') == 'Internal' ? 'selected' : '' }}>🏢
                                Internal</option>
                            <option value="External" {{ old('service_provider') == 'External' ? 'selected' : '' }}>🤝
                                External Partner</option>
                            <option value="Both" {{ old('service_provider') == 'Both' ? 'selected' : '' }}>🔄 Internal &
                                External</option>
                        </select>
                        @error('service_provider')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : '' }}>Suspended
                            </option>
                            <option value="Discontinued" {{ old('status') == 'Discontinued' ? 'selected' : '' }}>
                                Discontinued</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="3" placeholder="Brief service description">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Detailed Description</label>
                        <textarea name="detailed_description" class="form-input" rows="3" placeholder="Detailed service description">{{ old('detailed_description') }}</textarea>
                        @error('detailed_description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>💰 Billing Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Billing Type *</label>
                        <select name="billing_type" class="form-input" required>
                            <option value="">Select Billing Type</option>
                            <option value="Fixed" {{ old('billing_type') == 'Fixed' ? 'selected' : '' }}>💰 Fixed Rate
                            </option>
                            <option value="Variable" {{ old('billing_type') == 'Variable' ? 'selected' : '' }}>📊 Variable
                                Rate</option>
                            <option value="Percentage" {{ old('billing_type') == 'Percentage' ? 'selected' : '' }}>📈
                                Percentage Based</option>
                            <option value="Hourly" {{ old('billing_type') == 'Hourly' ? 'selected' : '' }}>⏰ Hourly Rate
                            </option>
                            <option value="Per Unit" {{ old('billing_type') == 'Per Unit' ? 'selected' : '' }}>📦 Per Unit
                            </option>
                            <option value="Tiered" {{ old('billing_type') == 'Tiered' ? 'selected' : '' }}>📶 Tiered
                                Pricing</option>
                        </select>
                        @error('billing_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Base Rate *</label>
                        <input type="number" name="base_rate" class="form-input" step="0.01" min="0" required
                            value="{{ old('base_rate') }}" placeholder="0.00">
                        @error('base_rate')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Currency *</label>
                        <select name="rate_currency" class="form-input" required>
                            <option value="USD" {{ old('rate_currency', 'USD') == 'USD' ? 'selected' : '' }}>USD
                            </option>
                            <option value="EUR" {{ old('rate_currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="EGP" {{ old('rate_currency') == 'EGP' ? 'selected' : '' }}>EGP</option>
                            <option value="GBP" {{ old('rate_currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                        </select>
                        @error('rate_currency')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rate Unit</label>
                        <input type="text" name="rate_unit" class="form-input" value="{{ old('rate_unit') }}"
                            placeholder="e.g., per shipment, per container, per hour">
                        @error('rate_unit')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Minimum Charge</label>
                        <input type="number" name="minimum_charge" class="form-input" step="0.01" min="0"
                            value="{{ old('minimum_charge') }}" placeholder="0.00">
                        @error('minimum_charge')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Maximum Charge</label>
                        <input type="number" name="maximum_charge" class="form-input" step="0.01" min="0"
                            value="{{ old('maximum_charge') }}" placeholder="0.00">
                        @error('maximum_charge')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📊 Tax Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Tax Type</label>
                        <select name="tax_type" class="form-input">
                            <option value="">Select Tax Type</option>
                            <option value="VAT" {{ old('tax_type') == 'VAT' ? 'selected' : '' }}>VAT</option>
                            <option value="Service Tax" {{ old('tax_type') == 'Service Tax' ? 'selected' : '' }}>Service
                                Tax</option>
                            <option value="Sales Tax" {{ old('tax_type') == 'Sales Tax' ? 'selected' : '' }}>Sales Tax
                            </option>
                            <option value="Exempt" {{ old('tax_type') == 'Exempt' ? 'selected' : '' }}>Tax Exempt</option>
                        </select>
                        @error('tax_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tax Percentage *</label>
                        <input type="number" name="tax_percentage" class="form-input" step="0.01" min="0"
                            max="100" required value="{{ old('tax_percentage', 0) }}" placeholder="0.00">
                        @error('tax_percentage')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">GL Account</label>
                        <select name="account_id" class="form-input">
                            <option value="">Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('account_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Configuration -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>⚙️ Service Configuration</h3>
            </div>
            <div class="card-body">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_mandatory" value="1"
                            {{ old('is_mandatory') ? 'checked' : '' }}>
                        Mandatory Service
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_billable" value="1"
                            {{ old('is_billable', true) ? 'checked' : '' }}>
                        Billable to Client
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="requires_approval" value="1"
                            {{ old('requires_approval') ? 'checked' : '' }}>
                        Requires Approval
                    </label>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Estimated Duration (Hours)</label>
                        <input type="number" name="estimated_duration_hours" class="form-input" min="0"
                            value="{{ old('estimated_duration_hours') }}" placeholder="e.g., 24">
                        @error('estimated_duration_hours')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Effective From</label>
                        <input type="date" name="effective_from" class="form-input"
                            value="{{ old('effective_from') }}">
                        @error('effective_from')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Effective To</label>
                        <input type="date" name="effective_to" class="form-input" value="{{ old('effective_to') }}">
                        @error('effective_to')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Service Conditions</label>
                    <textarea name="service_conditions" class="form-input" rows="3"
                        placeholder="Terms and conditions for this service">{{ old('service_conditions') }}</textarea>
                    @error('service_conditions')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Required Documents -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📄 Required Documents</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Documents Required for Service</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $documentsList = [
                                'Commercial Invoice',
                                'Packing List',
                                'Bill of Lading',
                                'Certificate of Origin',
                                'Import License',
                                'Export License',
                                'Insurance Certificate',
                                'Inspection Certificate',
                                'Customs Declaration',
                                'Delivery Order',
                                'Payment Receipt',
                                'Bank Guarantee',
                                'Quality Certificate',
                                'Phytosanitary Certificate',
                                'Health Certificate',
                            ];
                            $selectedDocs = old('required_documents', []);
                        @endphp
                        @foreach ($documentsList as $document)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="required_documents[]" value="{{ $document }}"
                                    {{ in_array($document, $selectedDocs) ? 'checked' : '' }}>
                                {{ $document }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Applicable Cargo Types -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📦 Applicable Cargo Types</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Cargo Types (Leave empty for all types)</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $cargoTypes = [
                                'General Cargo',
                                'Container (FCL)',
                                'Container (LCL)',
                                'Bulk Cargo',
                                'Liquid Bulk',
                                'Break Bulk',
                                'Ro-Ro Cargo',
                                'Dangerous Goods',
                                'Refrigerated Cargo',
                                'Oversized Cargo',
                                'High Value Cargo',
                                'Livestock',
                                'Vehicles',
                                'Machinery',
                                'Electronics',
                            ];
                            $selectedCargo = old('applicable_cargo_types', []);
                        @endphp
                        @foreach ($cargoTypes as $cargoType)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="applicable_cargo_types[]" value="{{ $cargoType }}"
                                    {{ in_array($cargoType, $selectedCargo) ? 'checked' : '' }}>
                                {{ $cargoType }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📝 Additional Notes</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="4" placeholder="Additional notes about this service">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('logistics.services.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Service</button>
        </div>
    </form>
@endsection
