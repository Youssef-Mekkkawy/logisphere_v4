@extends('layouts.app')

@section('title', 'Create New Quantity Type')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 text-gray-800 mb-0">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Create New Quantity Type
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('submenu.index') }}">Submenu</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('submenu.quantity-types.index') }}">Quantity Types</a>
                        </li>
                        <li class="breadcrumb-item active">Create New</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('submenu.quantity-types.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
            </div>
        </div>

        <form action="{{ route('submenu.quantity-types.store') }}" method="POST" id="quantityTypeForm">
            @csrf

            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity_code" class="form-label">Quantity Code <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('quantity_code') is-invalid @enderror"
                                            id="quantity_code" name="quantity_code" value="{{ old('quantity_code') }}"
                                            maxlength="20" style="text-transform: uppercase" required>
                                        @error('quantity_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Unique quantity type identifier (auto-generated
                                            if empty)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity_name" class="form-label">Quantity Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('quantity_name') is-invalid @enderror"
                                            id="quantity_name" name="quantity_name" value="{{ old('quantity_name') }}"
                                            required>
                                        @error('quantity_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="quantity_category" class="form-label">Category <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('quantity_category') is-invalid @enderror"
                                            id="quantity_category" name="quantity_category" required>
                                            <option value="">Select Category</option>
                                            <option value="Weight"
                                                {{ old('quantity_category') == 'Weight' ? 'selected' : '' }}>⚖️ Weight
                                            </option>
                                            <option value="Volume"
                                                {{ old('quantity_category') == 'Volume' ? 'selected' : '' }}>📦 Volume
                                            </option>
                                            <option value="Count"
                                                {{ old('quantity_category') == 'Count' ? 'selected' : '' }}>🔢 Count
                                            </option>
                                            <option value="Dimension"
                                                {{ old('quantity_category') == 'Dimension' ? 'selected' : '' }}>📏
                                                Dimension</option>
                                            <option value="Container"
                                                {{ old('quantity_category') == 'Container' ? 'selected' : '' }}>🚛
                                                Container</option>
                                            <option value="Liquid"
                                                {{ old('quantity_category') == 'Liquid' ? 'selected' : '' }}>💧 Liquid
                                            </option>
                                            <option value="Area"
                                                {{ old('quantity_category') == 'Area' ? 'selected' : '' }}>📐 Area</option>
                                            <option value="Time"
                                                {{ old('quantity_category') == 'Time' ? 'selected' : '' }}>⏰ Time</option>
                                            <option value="Custom"
                                                {{ old('quantity_category') == 'Custom' ? 'selected' : '' }}>⚙️ Custom
                                            </option>
                                        </select>
                                        @error('quantity_category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="unit_of_measure" class="form-label">Unit of Measure <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('unit_of_measure') is-invalid @enderror"
                                            id="unit_of_measure" name="unit_of_measure"
                                            value="{{ old('unit_of_measure') }}" required
                                            placeholder="e.g., Kilogram, Cubic Meter">
                                        @error('unit_of_measure')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="unit_symbol" class="form-label">Unit Symbol</label>
                                        <input type="text"
                                            class="form-control @error('unit_symbol') is-invalid @enderror" id="unit_symbol"
                                            name="unit_symbol" value="{{ old('unit_symbol') }}" maxlength="20"
                                            placeholder="e.g., kg, m³, TEU">
                                        @error('unit_symbol')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Detailed description of this quantity type">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Conversion & Calculation -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Conversion & Calculation</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="base_unit" class="form-label">Base Unit</label>
                                        <input type="text"
                                            class="form-control @error('base_unit') is-invalid @enderror" id="base_unit"
                                            name="base_unit" value="{{ old('base_unit') }}" list="baseUnitsList"
                                            placeholder="e.g., kg, liters, pieces">
                                        <datalist id="baseUnitsList">
                                            @foreach ($baseUnits as $baseUnit)
                                                <option value="{{ $baseUnit }}">
                                            @endforeach
                                            <option value="kg">
                                            <option value="liters">
                                            <option value="pieces">
                                            <option value="cbm">
                                            <option value="TEU">
                                            <option value="sqm">
                                            <option value="days">
                                        </datalist>
                                        @error('base_unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">For unit conversions (leave empty if base
                                            unit)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="conversion_factor" class="form-label">Conversion Factor</label>
                                        <input type="number"
                                            class="form-control @error('conversion_factor') is-invalid @enderror"
                                            id="conversion_factor" name="conversion_factor"
                                            value="{{ old('conversion_factor', 1.0) }}" step="0.000001" min="0"
                                            placeholder="1.0">
                                        @error('conversion_factor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Multiplier to convert to base unit</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="decimal_places" class="form-label">Decimal Places</label>
                                        <input type="number"
                                            class="form-control @error('decimal_places') is-invalid @enderror"
                                            id="decimal_places" name="decimal_places"
                                            value="{{ old('decimal_places', 2) }}" min="0" max="10"
                                            placeholder="2">
                                        @error('decimal_places')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Display precision (auto-set based on
                                            category)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="calculation_method" class="form-label">Calculation Method</label>
                                        <textarea class="form-control @error('calculation_method') is-invalid @enderror" id="calculation_method"
                                            name="calculation_method" rows="2"
                                            placeholder="How is this quantity calculated? e.g., Length × Width × Height">{{ old('calculation_method') }}</textarea>
                                        @error('calculation_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="display_format" class="form-label">Display Format</label>
                                        <input type="text"
                                            class="form-control @error('display_format') is-invalid @enderror"
                                            id="display_format" name="display_format"
                                            value="{{ old('display_format') }}" placeholder="%.2f kg">
                                        @error('display_format')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">printf format string (optional)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applicable Cargo Types -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Applicable Cargo Types</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Cargo Types</label>
                                <div class="cargo-types-container" id="cargoTypesContainer">
                                    <div class="input-group mb-2">
                                        <input type="text" name="applicable_cargo_types[]" class="form-control"
                                            value="{{ old('applicable_cargo_types.0', 'General Cargo') }}"
                                            placeholder="e.g., Containers, General Cargo">
                                        <button type="button" class="btn btn-outline-danger"
                                            onclick="removeCargoType(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCargoType()">
                                    <i class="fas fa-plus me-1"></i>Add Cargo Type
                                </button>
                                <div class="form-text">
                                    <small>Leave empty to allow all cargo types. Suggested: Containers, General Cargo, Bulk,
                                        Break Bulk, LCL, Project Cargo</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Industry Standards -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Industry Standards & Validation</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Industry Standards</label>
                                        <div class="standards-container" id="standardsContainer">
                                            <div class="input-group mb-2">
                                                <input type="text" name="industry_standards[]" class="form-control"
                                                    value="{{ old('industry_standards.0', 'SI Units') }}"
                                                    placeholder="e.g., ISO 668, SI Units">
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="removeStandard(this)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="addStandard()">
                                            <i class="fas fa-plus me-1"></i>Add Standard
                                        </button>
                                        <div class="form-text">
                                            <small>Suggested: SI Units, ISO standards, IMDG, etc.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Validation Rules</label>
                                        <div class="validation-container" id="validationContainer">
                                            <div class="input-group mb-2">
                                                <input type="text" name="validation_rules[]" class="form-control"
                                                    value="{{ old('validation_rules.0', 'positive_number') }}"
                                                    placeholder="e.g., positive_number, max:1000">
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="removeValidation(this)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="addValidation()">
                                            <i class="fas fa-plus me-1"></i>Add Rule
                                        </button>
                                        <div class="form-text">
                                            <small>Custom validation rules for values</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Common Ranges -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="range_min" class="form-label">Minimum Value</label>
                                        <input type="number" class="form-control" id="range_min"
                                            name="common_ranges[min]" value="{{ old('common_ranges.min', 0) }}"
                                            step="0.001" placeholder="0">
                                        <small class="form-text text-muted">Typical minimum</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="range_max" class="form-label">Maximum Value</label>
                                        <input type="number" class="form-control" id="range_max"
                                            name="common_ranges[max]" value="{{ old('common_ranges.max', 1000000) }}"
                                            step="0.001" placeholder="1000000">
                                        <small class="form-text text-muted">Typical maximum</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="reporting_category" class="form-label">Reporting Category</label>
                                        <input type="text"
                                            class="form-control @error('reporting_category') is-invalid @enderror"
                                            id="reporting_category" name="reporting_category"
                                            value="{{ old('reporting_category') }}" list="reportingCategoriesList"
                                            placeholder="e.g., Gross Weight">
                                        <datalist id="reportingCategoriesList">
                                            @foreach ($reportingCategories as $category)
                                                <option value="{{ $category }}">
                                            @endforeach
                                            <option value="Gross Weight">
                                            <option value="Volume">
                                            <option value="Container Volume">
                                            <option value="Item Count">
                                            <option value="Liquid Volume">
                                            <option value="Area">
                                            <option value="Time Duration">
                                            <option value="Billing Weight">
                                        </datalist>
                                        @error('reporting_category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="customs_code" class="form-label">Customs Code</label>
                                        <input type="text"
                                            class="form-control @error('customs_code') is-invalid @enderror"
                                            id="customs_code" name="customs_code" value="{{ old('customs_code') }}"
                                            maxlength="50" placeholder="HS/customs code">
                                        @error('customs_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4"
                                    placeholder="Additional information about this quantity type">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                    id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                                    min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Lower numbers appear first in listings</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Type Characteristics -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Type Characteristics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_weight_based"
                                            name="is_weight_based" value="1"
                                            {{ old('is_weight_based') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_weight_based">⚖️ Weight Based</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_volume_based"
                                            name="is_volume_based" value="1"
                                            {{ old('is_volume_based') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_volume_based">📦 Volume Based</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_count_based"
                                            name="is_count_based" value="1"
                                            {{ old('is_count_based') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_count_based">🔢 Count Based</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_dimension_based"
                                            name="is_dimension_based" value="1"
                                            {{ old('is_dimension_based') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_dimension_based">📏 Dimension
                                            Based</label>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">These will be auto-selected based on category</small>
                        </div>
                    </div>

                    <!-- Behavior Flags -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Behavior Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="allows_fractions"
                                    name="allows_fractions" value="1"
                                    {{ old('allows_fractions', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="allows_fractions">Allow Fractional Values</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="requires_dimensions"
                                    name="requires_dimensions" value="1"
                                    {{ old('requires_dimensions') ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_dimensions">Requires Dimensions
                                    (L×W×H)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="auto_calculate"
                                    name="auto_calculate" value="1" {{ old('auto_calculate') ? 'checked' : '' }}>
                                <label class="form-check-label" for="auto_calculate">Auto Calculate from Other
                                    Fields</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Quantity Type is Active</label>
                            </div>
                        </div>
                    </div>

                    <!-- System Flags -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">System Flags</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_standard" name="is_standard"
                                    value="1" {{ old('is_standard') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_standard">⭐ Industry Standard Unit</label>
                                <small class="form-text text-muted d-block">Mark as widely accepted standard</small>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Configuration -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Billing Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_billable" name="is_billable"
                                    value="1" {{ old('is_billable', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_billable">💰 Billable Unit</label>
                            </div>

                            <div id="billingOptions" class="{{ old('is_billable', true) ? '' : 'd-none' }}">
                                <div class="mb-3">
                                    <label for="billing_multiplier" class="form-label">Billing Multiplier</label>
                                    <input type="number"
                                        class="form-control @error('billing_multiplier') is-invalid @enderror"
                                        id="billing_multiplier" name="billing_multiplier"
                                        value="{{ old('billing_multiplier', 1.0) }}" step="0.0001" min="0"
                                        placeholder="1.0">
                                    @error('billing_multiplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Rate multiplier for billing</small>
                                </div>

                                <div class="mb-3">
                                    <label for="minimum_chargeable" class="form-label">Minimum Chargeable</label>
                                    <input type="number"
                                        class="form-control @error('minimum_chargeable') is-invalid @enderror"
                                        id="minimum_chargeable" name="minimum_chargeable"
                                        value="{{ old('minimum_chargeable', 0) }}" step="0.01" min="0"
                                        placeholder="0">
                                    @error('minimum_chargeable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum quantity for billing</small>
                                </div>

                                <div class="mb-3">
                                    <label for="rounding_method" class="form-label">Rounding Method</label>
                                    <select class="form-select" id="rounding_method" name="rounding_method">
                                        <option value="nearest"
                                            {{ old('rounding_method', 'nearest') == 'nearest' ? 'selected' : '' }}>Round to
                                            Nearest</option>
                                        <option value="up" {{ old('rounding_method') == 'up' ? 'selected' : '' }}>
                                            Round Up (Ceiling)</option>
                                        <option value="down" {{ old('rounding_method') == 'down' ? 'selected' : '' }}>
                                            Round Down (Floor)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Create Quantity Type
                                </button>
                                <a href="{{ route('submenu.quantity-types.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                            <hr>
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Fields marked with <span class="text-danger">*</span> are required
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        // Dynamic array field management
        function addCargoType() {
            const container = document.getElementById('cargoTypesContainer');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
        <input type="text" name="applicable_cargo_types[]" class="form-control" placeholder="e.g., Containers, General Cargo">
        <button type="button" class="btn btn-outline-danger" onclick="removeCargoType(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
            container.appendChild(div);
        }

        function removeCargoType(button) {
            if (document.getElementById('cargoTypesContainer').children.length > 1) {
                button.parentElement.remove();
            }
        }

        function addStandard() {
            const container = document.getElementById('standardsContainer');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
        <input type="text" name="industry_standards[]" class="form-control" placeholder="e.g., ISO 668, SI Units">
        <button type="button" class="btn btn-outline-danger" onclick="removeStandard(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
            container.appendChild(div);
        }

        function removeStandard(button) {
            if (document.getElementById('standardsContainer').children.length > 1) {
                button.parentElement.remove();
            }
        }

        function addValidation() {
            const container = document.getElementById('validationContainer');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
        <input type="text" name="validation_rules[]" class="form-control" placeholder="e.g., positive_number, max:1000">
        <button type="button" class="btn btn-outline-danger" onclick="removeValidation(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
            container.appendChild(div);
        }

        function removeValidation(button) {
            if (document.getElementById('validationContainer').children.length > 1) {
                button.parentElement.remove();
            }
        }

        // Show/hide billing options
        document.getElementById('is_billable').addEventListener('change', function() {
            const billingOptions = document.getElementById('billingOptions');
            if (this.checked) {
                billingOptions.classList.remove('d-none');
            } else {
                billingOptions.classList.add('d-none');
            }
        });

        // Auto-uppercase quantity code
        document.getElementById('quantity_code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });

        // Auto-generate quantity code based on name and category
        document.getElementById('quantity_name').addEventListener('blur', generateQuantityCodeSuggestion);
        document.getElementById('quantity_category').addEventListener('change', function() {
            generateQuantityCodeSuggestion();
            updateMeasurementTypes();
            updateDecimalPlaces();
        });

        function generateQuantityCodeSuggestion() {
            const codeField = document.getElementById('quantity_code');
            if (codeField.value) return; // Don't overwrite existing code

            const name = document.getElementById('quantity_name').value;
            const category = document.getElementById('quantity_category').value;

            if (name && category) {
                const categoryCode = category.substring(0, 2).toUpperCase();
                const nameCode = name.replace(/[^A-Za-z]/g, '').substring(0, 3).toUpperCase();
                const suggestedCode = categoryCode + nameCode;

                codeField.value = suggestedCode;
                codeField.focus();
            }
        }

        // Auto-populate measurement type checkboxes based on category
        function updateMeasurementTypes() {
            const category = document.getElementById('quantity_category').value;

            // Reset all checkboxes first
            document.getElementById('is_weight_based').checked = false;
            document.getElementById('is_volume_based').checked = false;
            document.getElementById('is_count_based').checked = false;
            document.getElementById('is_dimension_based').checked = false;

            // Set appropriate checkboxes based on category
            switch (category) {
                case 'Weight':
                    document.getElementById('is_weight_based').checked = true;
                    break;
                case 'Volume':
                    document.getElementById('is_volume_based').checked = true;
                    document.getElementById('is_dimension_based').checked = true;
                    break;
                case 'Count':
                    document.getElementById('is_count_based').checked = true;
                    break;
                case 'Dimension':
                    document.getElementById('is_dimension_based').checked = true;
                    break;
                case 'Container':
                    document.getElementById('is_count_based').checked = true;
                    document.getElementById('is_volume_based').checked = true;
                    break;
                case 'Liquid':
                    document.getElementById('is_volume_based').checked = true;
                    break;
                case 'Area':
                    document.getElementById('is_dimension_based').checked = true;
                    break;
            }
        }

        // Auto-set decimal places based on category
        function updateDecimalPlaces() {
            const category = document.getElementById('quantity_category').value;
            const decimalField = document.getElementById('decimal_places');

            if (decimalField.value && decimalField.value !== '2') return; // Don't overwrite user input

            const defaults = {
                'Weight': 3,
                'Volume': 2,
                'Count': 0,
                'Dimension': 2,
                'Container': 0,
                'Liquid': 2,
                'Area': 2,
                'Time': 1,
                'Custom': 2
            };

            decimalField.value = defaults[category] || 2;
        }

        // Form validation
        document.getElementById('quantityTypeForm').addEventListener('submit', function(e) {
            const requiredFields = ['quantity_name', 'quantity_category', 'unit_of_measure'];
            let isValid = true;

            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    element.classList.add('is-invalid');
                    isValid = false;
                } else {
                    element.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                window.scrollTo(0, 0);
            }
        });

        // Real-time validation
        document.querySelectorAll('input[required], select[required]').forEach(element => {
            element.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set default decimal places if category is already selected
            const category = document.getElementById('quantity_category').value;
            if (category) {
                updateMeasurementTypes();
                updateDecimalPlaces();
            }
        });
    </script>
@endpush
