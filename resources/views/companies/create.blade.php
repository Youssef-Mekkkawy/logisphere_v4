@extends('layouts.app')

@section('title', 'Create Company - LogiFlow')
@section('page-title', 'Create New Company')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('companies.index') }}" class="btn btn-secondary">← Back to Companies</a>
</div>

<form method="POST" action="{{ route('companies.store') }}">
    @csrf
    
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Company Name *</label>
            <input type="text" name="name" class="form-input" 
                   value="{{ old('name') }}" required>
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
            <label class="form-label">Contact Person *</label>
            <input type="text" name="contact_person" class="form-input" 
                   value="{{ old('contact_person') }}" required>
            @error('contact_person')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" 
                   value="{{ old('email') }}">
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="tel" name="phone" class="form-input" 
                   value="{{ old('phone') }}">
            @error('phone')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-input" 
                   value="{{ old('country') }}">
            @error('country')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group" style="grid-column: 1 / -1;">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-input" rows="3">{{ old('address') }}</textarea>
            @error('address')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Service Type</label>
            <select name="service_type" class="form-input">
                <option value="">Select Service Type</option>
                <option value="Shipping" {{ old('service_type') == 'Shipping' ? 'selected' : '' }}>Shipping</option>
                <option value="Customs Clearance" {{ old('service_type') == 'Customs Clearance' ? 'selected' : '' }}>Customs Clearance</option>
                <option value="Trucking" {{ old('service_type') == 'Trucking' ? 'selected' : '' }}>Trucking</option>
                <option value="Warehousing" {{ old('service_type') == 'Warehousing' ? 'selected' : '' }}>Warehousing</option>
            </select>
            @error('service_type')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <button type="submit" class="btn btn-primary">Create Company</button>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection