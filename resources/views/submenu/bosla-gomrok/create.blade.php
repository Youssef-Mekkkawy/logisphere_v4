@extends('layouts.app')

@section('title', 'Create Bosla from Gomrok')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Create New Bosla from
            Gomrok</h1>
        <p style="color: #64748b;">Add a new customs box/paperwork documentation to the system</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Create Bosla from Gomrok</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('submenu.bosla-gomrok.store')  ?? '' }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="code">Bosla Code <span class="required">*</span></label>
                        <input type="text" id="code" name="code"
                            class="form-input @error('code') error @enderror" value="{{ old('code') }}" required
                            placeholder="e.g., BGK-001, EXP-DOC">
                        @error('code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="name">Bosla Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-input @error('name') error @enderror" value="{{ old('name') }}" required
                            placeholder="e.g., Export Declaration Form">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="document_type">Document Type <span class="required">*</span></label>
                        <select id="document_type" name="document_type"
                            class="form-input @error('document_type') error @enderror" required>
                            <option value="">Select Document Type</option>
                            <option value="Export" {{ old('document_type') == 'Export' ? 'selected' : '' }}>Export
                                Declaration</option>
                            <option value="Import" {{ old('document_type') == 'Import' ? 'selected' : '' }}>Import
                                Declaration</option>
                            <option value="Transit" {{ old('document_type') == 'Transit' ? 'selected' : '' }}>Transit
                                Declaration</option>
                            <option value="Temporary" {{ old('document_type') == 'Temporary' ? 'selected' : '' }}>Temporary
                                Admission</option>
                        </select>
                        @error('document_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="customs_office">Customs Office <span
                                class="required">*</span></label>
                        <input type="text" id="customs_office" name="customs_office"
                            class="form-input @error('customs_office') error @enderror" value="{{ old('customs_office') }}"
                            required placeholder="e.g., Cairo Main Customs Office" list="customs_offices">
                        <datalist id="customs_offices">
                            @foreach ($customsOffices as $office)
                                <option value="{{ $office }}">{{ $office }}</option>
                            @endforeach
                            <option value="Cairo Main Customs Office">Cairo Main Customs Office</option>
                            <option value="Alexandria Port Customs">Alexandria Port Customs</option>
                            <option value="Suez Canal Customs">Suez Canal Customs</option>
                            <option value="Damietta Port Customs">Damietta Port Customs</option>
                            <option value="Safaga Port Customs">Safaga Port Customs</option>
                            <option value="Nuweiba Port Customs">Nuweiba Port Customs</option>
                            <option value="Sharm El Sheikh Airport Customs">Sharm El Sheikh Airport Customs</option>
                            <option value="Hurghada Airport Customs">Hurghada Airport Customs</option>
                        </datalist>
                        @error('customs_office')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="processing_hours">Processing Time (Hours) <span
                                class="required">*</span></label>
                        <input type="number" id="processing_hours" name="processing_hours"
                            class="form-input @error('processing_hours') error @enderror"
                            value="{{ old('processing_hours') }}" min="1" required placeholder="e.g., 24">
                        <small style="color: #64748b; font-size: 0.75rem;">Time required to process this document</small>
                        @error('processing_hours')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cost">Cost (USD) <span class="required">*</span></label>
                        <input type="number" id="cost" name="cost"
                            class="form-input @error('cost') error @enderror" value="{{ old('cost') }}" step="0.01"
                            min="0" required placeholder="0.00">
                        @error('cost')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="validity_days">Validity Period (Days)</label>
                        <input type="number" id="validity_days" name="validity_days"
                            class="form-input @error('validity_days') error @enderror" value="{{ old('validity_days') }}"
                            min="0" placeholder="Leave empty for no expiry">
                        <small style="color: #64748b; font-size: 0.75rem;">Leave empty for documents that don't
                            expire</small>
                        @error('validity_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="is_mandatory">Is Mandatory <span class="required">*</span></label>
                        <select id="is_mandatory" name="is_mandatory"
                            class="form-input @error('is_mandatory') error @enderror" required>
                            <option value="">Select Option</option>
                            <option value="1" {{ old('is_mandatory') == '1' ? 'selected' : '' }}>Yes - Mandatory
                            </option>
                            <option value="0" {{ old('is_mandatory') == '0' ? 'selected' : '' }}>No - Optional
                            </option>
                        </select>
                        @error('is_mandatory')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-input @error('status') error @enderror" required>
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-input @error('description') error @enderror"
                        rows="4" placeholder="Describe this bosla and when it's used...">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="required_documents">Required Documents</label>
                    <textarea id="required_documents" name="required_documents"
                        class="form-input @error('required_documents') error @enderror" rows="4"
                        placeholder="List the documents required for this bosla (separated by commas)...">{{ old('required_documents') }}</textarea>
                    <small style="color: #64748b; font-size: 0.75rem;">Separate multiple documents with commas (e.g.,
                        Invoice, Packing List, Bill of Lading)</small>
                    @error('required_documents')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Create Bosla</button>
                    <a href="{{ route('submenu.bosla-gomrok.index')  ?? '' }}" class="btn btn-secondary">Cancel</a>
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
