@extends('layouts.app')

@section('title', 'Add New Quantity Type')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Add New Quantity Type</h1>
        <p style="color: #64748b;">Create a new measurement unit for logistics operations</p>
    </div>

    <form action="{{ route('logistics.quantity-types.store') }}" method="POST">
        @csrf

        <!-- Basic Information -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Quantity Code *</label>
                        <input type="text" name="quantity_code" class="form-input" required
                            value="{{ old('quantity_code') }}" placeholder="e.g., TEU, KG, CBM" maxlength="10"
                            style="text-transform: uppercase;">
                        @error('quantity_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quantity Name *</label>
                        <input type="text" name="quantity_name" class="form-input" required
                            value="{{ old('quantity_name') }}" placeholder="e.g., Twenty-foot Equivalent Unit">
                        @error('quantity_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="quantity_category" class="form-input" required>
                            <option value="">Select Category</option>
                            @foreach (\App\Models\Logistics\QuantityType::getCategories() as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('quantity_category') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('quantity_category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit of Measure *</label>
                        <input type="text" name="unit_of_measure" class="form-input" required
                            value="{{ old('unit_of_measure') }}" placeholder="e.g., Container, Kilogram">
                        @error('unit_of_measure')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Symbol *</label>
                        <input type="text" name="unit_symbol" class="form-input" required
                            value="{{ old('unit_symbol') }}" placeholder="e.g., TEU, kg, m³" maxlength="10">
                        @error('unit_symbol')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order *</label>
                        <input type="number" name="sort_order" class="form-input" required
                            value="{{ old('sort_order', 999) }}" min="1" max="9999">
                        @error('sort_order')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Detailed description of the quantity type">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Conversion & Calculation -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🔄 Conversion & Calculation</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Base Unit</label>
                        <input type="text" name="base_unit" class="form-input" value="{{ old('base_unit') }}"
                            placeholder="e.g., kg, cbm, pieces">
                        @error('base_unit')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Conversion Factor *</label>
                        <input type="number" name="conversion_factor" class="form-input" required
                            value="{{ old('conversion_factor', 1.0) }}" step="0.000001" min="0.000001">
                        <small style="color: #64748b;">Multiplier to convert to base unit</small>
                        @error('conversion_factor')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Decimal Places *</label>
                        <input type="number" name="decimal_places" class="form-input" required
                            value="{{ old('decimal_places', 2) }}" min="0" max="6">
                        @error('decimal_places')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Display Format</label>
                        <input type="text" name="display_format" class="form-input"
                            value="{{ old('display_format') }}" placeholder="e.g., %.2f kg, %.0f TEU">
                        <small style="color: #64748b;">Printf-style format string</small>
                        @error('display_format')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rounding Method *</label>
                        <select name="rounding_method" class="form-input" required>
                            @foreach (\App\Models\Logistics\QuantityType::getRoundingMethods() as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('rounding_method', 'nearest') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('rounding_method')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Reporting Category</label>
                        <input type="text" name="reporting_category" class="form-input"
                            value="{{ old('reporting_category') }}" placeholder="e.g., Container Volume, Gross Weight">
                        @error('reporting_category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Calculation Method</label>
                    <textarea name="calculation_method" class="form-input" rows="2"
                        placeholder="How this quantity is calculated or measured">{{ old('calculation_method') }}</textarea>
                    @error('calculation_method')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Range & Validation -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📊 Range & Validation</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Minimum Value</label>
                        <input type="number" name="range_min" class="form-input" value="{{ old('range_min') }}"
                            step="0.000001" placeholder="0.001">
                        @error('range_min')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Maximum Value</label>
                        <input type="number" name="range_max" class="form-input" value="{{ old('range_max') }}"
                            step="0.000001" placeholder="999999">
                        @error('range_max')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Validation Rules</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @foreach (\App\Models\Logistics\QuantityType::getDefaultValidationRules() as $rule => $description)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="validation_rules[]" value="{{ $rule }}"
                                    {{ is_array(old('validation_rules')) && in_array($rule, old('validation_rules')) ? 'checked' : '' }}>
                                {{ $description }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Type Classifications -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏷️ Type Classifications</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_weight_based" value="1"
                            {{ old('is_weight_based') ? 'checked' : '' }}>
                        Weight-based measurement
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_volume_based" value="1"
                            {{ old('is_volume_based') ? 'checked' : '' }}>
                        Volume-based measurement
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_count_based" value="1"
                            {{ old('is_count_based') ? 'checked' : '' }}>
                        Count-based measurement
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_dimension_based" value="1"
                            {{ old('is_dimension_based') ? 'checked' : '' }}>
                        Dimension-based measurement
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="allows_fractions" value="1"
                            {{ old('allows_fractions', true) ? 'checked' : '' }}>
                        Allows fractional values
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="requires_dimensions" value="1"
                            {{ old('requires_dimensions') ? 'checked' : '' }}>
                        Requires dimensional input
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="auto_calculate" value="1"
                            {{ old('auto_calculate') ? 'checked' : '' }}>
                        Auto-calculate value
                    </label>
                </div>
            </div>
        </div>

        <!-- Billing & Commercial -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>💰 Billing & Commercial</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <input type="checkbox" name="is_billable" value="1"
                                {{ old('is_billable', true) ? 'checked' : '' }}>
                            This quantity type is billable
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Billing Multiplier *</label>
                        <input type="number" name="billing_multiplier" class="form-input" required
                            value="{{ old('billing_multiplier', 1.0) }}" step="0.0001" min="0.0001">
                        <small style="color: #64748b;">Factor for billing calculation</small>
                        @error('billing_multiplier')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Minimum Chargeable *</label>
                        <input type="number" name="minimum_chargeable" class="form-input" required
                            value="{{ old('minimum_chargeable', 0.001) }}" step="0.000001" min="0.000001">
                        <small style="color: #64748b;">Minimum amount for billing</small>
                        @error('minimum_chargeable')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
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
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    @foreach (\App\Models\Logistics\QuantityType::getCargoTypes() as $cargoType)
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="applicable_cargo_types[]" value="{{ $cargoType }}"
                                {{ is_array(old('applicable_cargo_types')) && in_array($cargoType, old('applicable_cargo_types')) ? 'checked' : '' }}>
                            {{ $cargoType }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Industry Standards -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📜 Industry Standards</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    @php
                        $standards = [
                            'ISO 668' => 'ISO 668 - Container Standards',
                            'CSC Convention' => 'CSC Convention',
                            'SI Units' => 'International System of Units',
                            'Imperial Units' => 'Imperial Measurement System',
                            'ISO 80000-3' => 'ISO 80000-3 - Space and Time',
                            'ISO 80000-4' => 'ISO 80000-4 - Mechanics',
                            'Freight Industry' => 'Freight Industry Standards',
                            'IATA' => 'International Air Transport Association',
                            'IMO' => 'International Maritime Organization',
                        ];
                    @endphp
                    @foreach ($standards as $code => $name)
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="industry_standards[]" value="{{ $code }}"
                                {{ is_array(old('industry_standards')) && in_array($code, old('industry_standards')) ? 'checked' : '' }}>
                            {{ $name }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- System Properties -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>⚙️ System Properties</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_standard" value="1"
                            {{ old('is_standard', true) ? 'checked' : '' }}>
                        Standard quantity type
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        Active and available for use
                    </label>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="Additional notes or special instructions">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('logistics.quantity-types.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Quantity Type</button>
        </div>
    </form>
@endsection
