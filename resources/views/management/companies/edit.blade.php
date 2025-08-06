@extends('layouts.app')

@section('title', 'Edit Company - logisphere')
@section('page-title', 'Edit Company: ' . $company->name)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('management.companies.index') }}" class="btn btn-secondary">← Back to Companies</a>
    <a href="{{ route('management.companies.show', $company) }}" class="btn btn-primary">View Company</a>
</div>

<form method="POST" action="{{ route('management.companies.update', $company) }}"
    style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
    @csrf
    @method('PUT')
    
    <!-- Company Info Header -->
    <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
        <h4 style="color: #1e40af; margin-bottom: 10px;">Edit Company Information</h4>
        <p style="color: #6b7280; margin: 0;">
            <strong>Company Code:</strong> {{ $company->company_code ?? 'Will be generated' }}
            <span style="margin-left: 20px;"><strong>Created:</strong> {{ $company->created_at->format('M d, Y') }}</span>
        </p>
    </div>

    <!-- Basic Information -->
    <div style="margin-bottom: 30px;">
        <h4 style="color: #1e40af; margin-bottom: 15px;">🏢 Basic Information</h4>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Company Name *</label>
                <input type="text" name="name" class="form-input" 
                       value="{{ old('name', $company->name) }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Company Type *</label>
                <select name="type" class="form-input" required>
                    <option value="">Select Type</option>
                    <option value="Client" {{ old('type', $company->type) == 'Client' ? 'selected' : '' }}>Client</option>
                    <option value="Supplier" {{ old('type', $company->type) == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                </select>
                @error('type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-input" required>
                    <option value="Active" {{ old('status', $company->status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('status', $company->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                <input type="text" name="contact_person" class="form-input" 
                       value="{{ old('contact_person', $company->contact_person) }}" required>
                @error('contact_person')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-input" 
                       value="{{ old('email', $company->email) }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-input" 
                       value="{{ old('phone', $company->phone) }}">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Website</label>
                <input type="url" name="website" class="form-input" 
                       value="{{ old('website', $company->website) }}">
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
                    @foreach($countries as $key => $countryName)
                        <option value="{{ $key }}" {{ old('country', $company->country) == $key ? 'selected' : '' }}>
                            {{ $countryName }}
                        </option>
                    @endforeach
                </select>
                @error('country')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-input" 
                       value="{{ old('city', $company->city) }}">
                @error('city')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Postal Code</label>
                <input type="text" name="postal_code" class="form-input" 
                       value="{{ old('postal_code', $company->postal_code) }}">
                @error('postal_code')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tax Number</label>
                <input type="text" name="tax_number" class="form-input" 
                       value="{{ old('tax_number', $company->tax_number) }}">
                @error('tax_number')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Address *</label>
            <textarea name="address" class="form-input" rows="3" required>{{ old('address', $company->address) }}</textarea>
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
                    @foreach($serviceTypes as $key => $serviceName)
                        <option value="{{ $key }}" {{ old('service_type', $company->service_type) == $key ? 'selected' : '' }}>
                            {{ $serviceName }}
                        </option>
                    @endforeach
                </select>
                @error('service_type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Credit Limit</label>
                <input type="number" name="credit_limit" class="form-input" step="0.01" min="0"
                       value="{{ old('credit_limit', $company->credit_limit) }}">
                @error('credit_limit')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Payment Terms (Days)</label>
                <input type="number" name="payment_terms" class="form-input" min="0" max="365"
                       value="{{ old('payment_terms', $company->payment_terms) }}">
                @error('payment_terms')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-input" rows="3">{{ old('notes', $company->notes) }}</textarea>
            @error('notes')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Audit Information -->
    <div style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
        <h4 style="color: #1e40af; margin-bottom: 15px;">📊 Audit Information</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 14px;">
            <div>
                <strong>Created:</strong><br>
                <span style="color: #6b7280;">{{ $company->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div>
                <strong>Last Updated:</strong><br>
                <span style="color: #6b7280;">{{ $company->updated_at->format('M d, Y H:i') }}</span>
            </div>
            <div>
                <strong>Total Shipments:</strong><br>
                <span style="color: #6b7280;">{{ $company->shipments->count() }}</span>
            </div>
            @if($company->company_code)
            <div>
                <strong>Company Code:</strong><br>
                <span style="color: #6b7280;">{{ $company->company_code }}</span>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Company
        </button>
        <a href="{{ route('management.companies.show', $company) }}" class="btn btn-secondary" style="margin-left: 15px;">
            <i class="fas fa-times"></i> Cancel
        </a>
        
        @if(auth()->user()->isAdmin() && in_array($company->status, ['Inactive']))
        <button type="button" class="btn btn-danger" style="margin-left: 15px; float: right;"
                onclick="if(confirm('Are you sure you want to delete this company? This action cannot be undone and will affect all related shipments.')) { document.getElementById('delete-form').submit(); }">
            <i class="fas fa-trash"></i> Delete Company
        </button>
        @endif
    </div>
</form>

<!-- Hidden Delete Form -->
@if(auth()->user()->isAdmin() && in_array($company->status, ['Inactive']))
<form id="delete-form" method="POST" action="{{ route('management.companies.destroy', $company) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endif

@endsection