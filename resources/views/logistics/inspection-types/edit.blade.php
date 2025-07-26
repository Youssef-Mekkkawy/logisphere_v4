@extends('layouts.app')

@section('title', 'Edit Inspection Type')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Edit Inspection Type</h1>
        <p style="color: #64748b;">Update inspection type information - {{ $inspectionType->inspection_name }}</p>
    </div>

    <form action="{{ route('logistics.inspection-types.update', $inspectionType) }}" method="POST">
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
                        <label class="form-label">Inspection Code *</label>
                        <input type="text" name="inspection_code" class="form-input" required
                            value="{{ old('inspection_code', $inspectionType->inspection_code) }}"
                            placeholder="e.g., INSP001">
                        @error('inspection_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Inspection Name *</label>
                        <input type="text" name="inspection_name" class="form-input" required
                            value="{{ old('inspection_name', $inspectionType->inspection_name) }}"
                            placeholder="Inspection name">
                        @error('inspection_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="inspection_category" class="form-input" required>
                            <option value="">Select Category</option>
                            <option value="Customs"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Customs' ? 'selected' : '' }}>
                                🛃 Customs Clearance</option>
                            <option value="Quality"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Quality' ? 'selected' : '' }}>
                                ✅ Quality Control</option>
                            <option value="Safety"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Safety' ? 'selected' : '' }}>
                                🛡️ Safety Inspection</option>
                            <option value="Environmental"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Environmental' ? 'selected' : '' }}>
                                🌿 Environmental</option>
                            <option value="Security"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Security' ? 'selected' : '' }}>
                                🔒 Security Check</option>
                            <option value="Health"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Health' ? 'selected' : '' }}>
                                🏥 Health & Sanitary</option>
                            <option value="Technical"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Technical' ? 'selected' : '' }}>
                                🔧 Technical Inspection</option>
                            <option value="Documentation"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Documentation' ? 'selected' : '' }}>
                                📋 Documentation Review</option>
                            <option value="Physical"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Physical' ? 'selected' : '' }}>
                                📦 Physical Examination</option>
                            <option value="Laboratory"
                                {{ old('inspection_category', $inspectionType->inspection_category) == 'Laboratory' ? 'selected' : '' }}>
                                🧪 Laboratory Testing</option>
                        </select>
                        @error('inspection_category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active"
                                {{ old('status', $inspectionType->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive"
                                {{ old('status', $inspectionType->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Detailed description of the inspection...">{{ old('description', $inspectionType->description) }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Authority & Requirements -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏛️ Authority & Requirements</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Regulatory Authority</label>
                        <input type="text" name="regulatory_authority" class="form-input"
                            value="{{ old('regulatory_authority', $inspectionType->regulatory_authority) }}"
                            placeholder="e.g., Egypt Customs Authority">
                        @error('regulatory_authority')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Compliance Standards</label>
                        <input type="text" name="compliance_standards" class="form-input"
                            value="{{ old('compliance_standards', $inspectionType->compliance_standards) }}"
                            placeholder="e.g., ISO 9001, WCO Framework">
                        @error('compliance_standards')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="mandatory" value="1"
                            {{ old('mandatory', $inspectionType->mandatory) ? 'checked' : '' }}>
                        This inspection is mandatory
                    </label>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="renewal_required" value="1"
                            {{ old('renewal_required', $inspectionType->renewal_required) ? 'checked' : '' }}>
                        Renewal required after expiry
                    </label>
                </div>
            </div>
        </div>

        <!-- Time & Cost -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>⏱️ Time & Cost Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Estimated Duration (Hours)</label>
                        <input type="number" name="estimated_duration" class="form-input" step="0.5" min="0"
                            value="{{ old('estimated_duration', $inspectionType->estimated_duration) }}"
                            placeholder="e.g., 4.5">
                        @error('estimated_duration')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cost Estimate (USD)</label>
                        <input type="number" name="cost_estimate" class="form-input" step="0.01" min="0"
                            value="{{ old('cost_estimate', $inspectionType->cost_estimate) }}" placeholder="0.00">
                        @error('cost_estimate')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Validity Period (Days)</label>
                        <input type="number" name="validity_period" class="form-input" min="1"
                            value="{{ old('validity_period', $inspectionType->validity_period) }}"
                            placeholder="e.g., 30">
                        <small style="color: #64748b;">Leave empty if inspection doesn't expire</small>
                        @error('validity_period')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Required Documents -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📄 Required Documents</h3>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Required Documents</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $documentsList = [
                                'Bill of Lading',
                                'Commercial Invoice',
                                'Packing List',
                                'Certificate of Origin',
                                'Import License',
                                'Health Certificate',
                                'Quality Certificate',
                                'Safety Data Sheet',
                                'Insurance Policy',
                                'Customs Declaration',
                                'Technical Specifications',
                                'Test Reports',
                                'Compliance Certificate',
                                'Environmental Permit',
                            ];
                            $selectedDocuments = old('required_documents', $inspectionType->required_documents ?? []);
                        @endphp
                        @foreach ($documentsList as $document)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="required_documents[]" value="{{ $document }}"
                                    {{ in_array($document, $selectedDocuments) ? 'checked' : '' }}>
                                {{ $document }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Applies To -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📦 Applies To Shipment Types</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Shipment Types</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $shipmentTypesList = [
                                'FCL' => 'Full Container Load',
                                'LCL' => 'Less Container Load',
                                'Break Bulk' => 'Break Bulk',
                                'Dangerous Goods' => 'Dangerous Goods',
                                'Perishable' => 'Perishable Goods',
                                'Live Animals' => 'Live Animals',
                                'High Value' => 'High Value Cargo',
                                'Project Cargo' => 'Project Cargo',
                                'Pharmaceuticals' => 'Pharmaceuticals',
                                'Food Products' => 'Food Products',
                            ];
                            $selectedTypes = old('applies_to', $inspectionType->applies_to ?? []);
                        @endphp
                        @foreach ($shipmentTypesList as $key => $label)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="applies_to[]" value="{{ $key }}"
                                    {{ in_array($key, $selectedTypes) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <small style="color: #64748b;">Leave unchecked if this inspection applies to all shipment types</small>
                </div>
            </div>
        </div>

        <!-- Prerequisites -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📝 Prerequisites & Notes</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Prerequisites</label>
                    <textarea name="prerequisites" class="form-input" rows="3"
                        placeholder="What needs to be completed before this inspection can be performed...">{{ old('prerequisites', $inspectionType->prerequisites) }}</textarea>
                    @error('prerequisites')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('logistics.inspection-types.show', $inspectionType) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Inspection Type</button>
        </div>
    </form>
@endsection
