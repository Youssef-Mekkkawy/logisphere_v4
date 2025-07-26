@extends('layouts.app')

@section('title', 'Edit COO Type')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Edit COO Type</h1>
        <p style="color: #64748b;">Update Certificate of Origin type information</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Edit COO Type - {{ $cooType->name }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('logistics.coo-types.update' ?? '' , $cooType) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="code">COO Code <span class="required">*</span></label>
                        <input type="text" id="code" name="code"
                            class="form-input @error('code') error @enderror" value="{{ old('code', $cooType->code) }}"
                            required placeholder="e.g., COO-COMM, COO-PREF">
                        @error('code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="name">COO Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-input @error('name') error @enderror" value="{{ old('name', $cooType->name) }}"
                            required placeholder="e.g., Commercial Certificate of Origin">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="issuing_authority">Issuing Authority <span
                                class="required">*</span></label>
                        <input type="text" id="issuing_authority" name="issuing_authority"
                            class="form-input @error('issuing_authority') error @enderror"
                            value="{{ old('issuing_authority', $cooType->issuing_authority) }}" required
                            placeholder="e.g., Chamber of Commerce">
                        @error('issuing_authority')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="is_mandatory">Is Mandatory <span class="required">*</span></label>
                        <select id="is_mandatory" name="is_mandatory"
                            class="form-input @error('is_mandatory') error @enderror" required>
                            <option value="">Select Option</option>
                            <option value="1"
                                {{ old('is_mandatory', $cooType->is_mandatory) == '1' ? 'selected' : '' }}>Yes - Mandatory
                            </option>
                            <option value="0"
                                {{ old('is_mandatory', $cooType->is_mandatory) == '0' ? 'selected' : '' }}>No - Optional
                            </option>
                        </select>
                        @error('is_mandatory')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="processing_days">Processing Time (Days) <span
                                class="required">*</span></label>
                        <input type="number" id="processing_days" name="processing_days"
                            class="form-input @error('processing_days') error @enderror"
                            value="{{ old('processing_days', $cooType->processing_days) }}" min="1" required
                            placeholder="e.g., 3">
                        @error('processing_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cost">Cost (USD) <span class="required">*</span></label>
                        <input type="number" id="cost" name="cost"
                            class="form-input @error('cost') error @enderror" value="{{ old('cost', $cooType->cost) }}"
                            step="0.01" min="0" required placeholder="0.00">
                        @error('cost')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="validity_months">Validity Period <span
                                class="required">*</span></label>
                        <select id="validity_months" name="validity_months"
                            class="form-input @error('validity_months') error @enderror" required>
                            <option value="1"
                                {{ old('validity_months', $cooType->validity_months) == '1' ? 'selected' : '' }}>1 Month
                            </option>
                            <option value="3"
                                {{ old('validity_months', $cooType->validity_months) == '3' ? 'selected' : '' }}>3 Months
                            </option>
                            <option value="6"
                                {{ old('validity_months', $cooType->validity_months) == '6' ? 'selected' : '' }}>6 Months
                            </option>
                            <option value="12"
                                {{ old('validity_months', $cooType->validity_months) == '12' ? 'selected' : '' }}>1 Year
                            </option>
                            <option value="24"
                                {{ old('validity_months', $cooType->validity_months) == '24' ? 'selected' : '' }}>2 Years
                            </option>
                            <option value="0"
                                {{ old('validity_months', $cooType->validity_months) == '0' ? 'selected' : '' }}>No Expiry
                            </option>
                        </select>
                        @error('validity_months')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status <span class="required">*</span></label>
                        <select id="status" name="status" class="form-input @error('status') error @enderror" required>
                            <option value="Active" {{ old('status', $cooType->status) == 'Active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="Inactive"
                                {{ old('status', $cooType->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-input @error('description') error @enderror"
                        rows="4" placeholder="Describe this COO type and when it's used...">{{ old('description', $cooType->description) }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="required_documents">Required Documents</label>
                    <textarea id="required_documents" name="required_documents"
                        class="form-input @error('required_documents') error @enderror" rows="4"
                        placeholder="List the documents required for this COO type...">{{ old('required_documents', $cooType->required_documents) }}</textarea>
                    @error('required_documents')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Update COO Type</button>
                    <a href="{{ route('logistics.coo-types.show' ?? '' , $cooType) }}" class="btn btn-secondary">Cancel</a>
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
