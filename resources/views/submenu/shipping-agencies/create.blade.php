@extends('layouts.app')

@section('title', 'Create Shipping Agency')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Create New Shipping Agency
        </h1>
        <p style="color: #64748b;">Add a new shipping agency partner to the system</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Create Shipping Agency</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('submenu.shipping-agencies.store') ?? '' }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="code">Agency Code <span class="required">*</span></label>
                        <input type="text" id="code" name="code"
                            class="form-input @error('code') error @enderror" value="{{ old('code') }}" required
                            placeholder="e.g., MSK, COSCO, EVER">
                        @error('code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="name">Agency Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-input @error('name') error @enderror" value="{{ old('name') }}" required
                            placeholder="e.g., Maersk Line, COSCO Shipping">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="country_id">Country <span class="required">*</span></label>
                        <select id="country_id" name="country_id" class="form-input @error('country_id') error @enderror"
                            required>
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
                        <label class="form-label" for="service_type">Service Type <span class="required">*</span></label>
                        <select id="service_type" name="service_type"
                            class="form-input @error('service_type') error @enderror" required>
                            <option value="">Select Service Type</option>
                            <option value="Ocean Freight" {{ old('service_type') == 'Ocean Freight' ? 'selected' : '' }}>
                                Ocean Freight</option>
                            <option value="Air Freight" {{ old('service_type') == 'Air Freight' ? 'selected' : '' }}>Air
                                Freight</option>
                            <option value="Land Transport" {{ old('service_type') == 'Land Transport' ? 'selected' : '' }}>
                                Land Transport</option>
                            <option value="Full Service" {{ old('service_type') == 'Full Service' ? 'selected' : '' }}>Full
                                Service</option>
                        </select>
                        @error('service_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="contact_person">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person"
                            class="form-input @error('contact_person') error @enderror" value="{{ old('contact_person') }}"
                            placeholder="Primary contact name">
                        @error('contact_person')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="form-input @error('email') error @enderror" value="{{ old('email') }}"
                            placeholder="agency@example.com">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone"
                            class="form-input @error('phone') error @enderror" value="{{ old('phone') }}"
                            placeholder="+1234567890">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status <span class="required">*</span></label>
                        <select id="status" name="status" class="form-input @error('status') error @enderror" required>
                            <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">Address</label>
                    <textarea id="address" name="address" class="form-input @error('address') error @enderror" rows="3"
                        placeholder="Enter agency address">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="services_offered">Services Offered</label>
                    <textarea id="services_offered" name="services_offered" class="form-input @error('services_offered') error @enderror"
                        rows="4" placeholder="Describe the services this agency provides...">{{ old('services_offered') }}</textarea>
                    @error('services_offered')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Create Shipping Agency</button>
                    <a href="{{ route('shipping-agencies.index') ?? '' }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .required {
            color: #ef4444;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color, #3b82f6);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color, #3b82f6), var(--primary-dark, #1e40af));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            color: white;
        }
    </style>
@endsection
