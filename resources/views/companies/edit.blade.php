@extends('layouts.app')

@section('title', 'Edit Company - LogiFlow')
@section('page-title', 'Edit Company: ' . $company->name)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('companies.index') }}" class="btn btn-secondary">← Back to Companies</a>
    <a href="{{ route('companies.show', $company) }}" class="btn btn-primary">View Company</a>
</div>

<form method="POST" action="{{ route('companies.update', $company) }}">
    @csrf
    @method('PUT')
    
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
            <label class="form-label">Contact Person *</label>
            <input type="text" name="contact_person" class="form-input" 
                   value="{{ old('contact_person', $company->contact_person) }}" required>
            @error('contact_person')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" 
                   value="{{ old('email', $company->email) }}">
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
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-input" 
                   value="{{ old('country', $company->country) }}">
            @error('country')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group" style="grid-column: 1 / -1;">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-input" rows="3">{{ old('address', $company->address) }}</textarea>
            @error('address')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Service Type</label>
            <select name="service_type" class="form-input">
                <option value="">Select Service Type</option>
                <option value="Shipping" {{ old('service_type', $company->service_type) == 'Shipping' ? 'selected' : '' }}>Shipping</option>
                <option value="Customs Clearance" {{ old('service_type', $company->service_type) == 'Customs Clearance' ? 'selected' : '' }}>Customs Clearance</option>
                <option value="Trucking" {{ old('service_type', $company->service_type) == 'Trucking' ? 'selected' : '' }}>Trucking</option>
                <option value="Warehousing" {{ old('service_type', $company->service_type) == 'Warehousing' ? 'selected' : '' }}>Warehousing</option>
            </select>
            @error('service_type')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="Active" {{ old('status', $company->status) == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ old('status', $company->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <button type="submit" class="btn btn-primary">Update Company</button>
        <a href="{{ route('companies.show', $company) }}" class="btn btn-secondary">Cancel</a>
        
        @if(auth()->user()->isAdmin())
        <form method="POST" action="{{ route('companies.destroy', $company) }}" style="display: inline; margin-left: 10px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" style="background: #dc2626; color: white;" 
                    onclick="return confirm('Are you sure you want to delete this company? This action cannot be undone.')">
                Delete Company
            </button>
        </form>
        @endif
    </div>
</form>

<div style="margin-top: 40px; padding: 20px; background: #f8fafc; border-radius: 10px;">
    <h4 style="margin-bottom: 15px;">Company Information</h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <strong>Created:</strong> {{ $company->created_at->format('M d, Y') }}
        </div>
        <div>
            <strong>Last Updated:</strong> {{ $company->updated_at->format('M d, Y') }}
        </div>
        <div>
            <strong>Total Shipments:</strong> {{ $company->shipments->count() }}
        </div>
    </div>
</div>
@endsection