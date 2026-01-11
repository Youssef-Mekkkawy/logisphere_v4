@extends('layouts.app')

@section('title', 'Create Company - logistics')
@section('page-title', 'Create New Company')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('management.companies.index') }}" class="btn btn-secondary">← Back to Companies</a>
    </div>

    <form method="POST" action="{{ route('management.companies.store') }}"
        style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        @csrf

        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">🏢 Basic Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Company Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required
                        placeholder="Enter company name">
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Company Type *</label>
                    <select name="type" class="form-input" required>
                        <option value="">Select Type</option>
                        <option value="Client" {{ old('type') == 'Client' ? 'selected' : '' }}>Client</option>
                        <option value="Supplier" {{ old('type') == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                    </select>
                    @error('type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">👤 Contact Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Contact Person *</label>
                    <input type="text" name="contact_person" class="form-input" value="{{ old('contact_person') }}"
                        required placeholder="Full name">
                    @error('contact_person')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required
                        placeholder="company@example.com">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}"
                        placeholder="+1-234-567-8900">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-input" value="{{ old('website') }}"
                        placeholder="https://company.com">
                    @error('website')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">📍 Address Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country" class="form-input">
                        <option value="">Select Country</option>
                        @foreach ($countries as $key => $country)
                            <option value="{{ $key }}" {{ old('country') == $key ? 'selected' : '' }}>
                                {{ $country }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-input" value="{{ old('city') }}"
                        placeholder="Enter city">
                    @error('city')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code') }}"
                        placeholder="12345">
                    @error('postal_code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tax Number</label>
                    <input type="text" name="tax_number" class="form-input" value="{{ old('tax_number') }}"
                        placeholder="Tax registration number">
                    @error('tax_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Address *</label>
                <textarea name="address" class="form-input" rows="3" required placeholder="Enter complete address">{{ old('address') }}</textarea>
                @error('address')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Business Information -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 15px;">💼 Business Information</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Service Type</label>
                    <select name="service_type" class="form-input">
                        <option value="">Select Service Type</option>
                        @foreach ($serviceTypes as $key => $service)
                            <option value="{{ $key }}" {{ old('service_type') == $key ? 'selected' : '' }}>
                                {{ $service }}</option>
                        @endforeach
                    </select>
                    @error('service_type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Credit Limit</label>
                    <input type="number" name="credit_limit" class="form-input" step="0.01" min="0"
                        value="{{ old('credit_limit', 0) }}" placeholder="0.00">
                    @error('credit_limit')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Payment Terms (Days)</label>
                    <input type="number" name="payment_terms" class="form-input" min="0" max="365"
                        value="{{ old('payment_terms', 30) }}" placeholder="30">
                    @error('payment_terms')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-input" rows="3" placeholder="Additional notes about this company...">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Company
            </button>
            <a href="{{ route('management.companies.index') }}" class="btn btn-secondary" style="margin-left: 15px;">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
@endsection
