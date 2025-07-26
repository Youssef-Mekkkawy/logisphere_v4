@extends('layouts.app')

@section('title', 'Edit Shipment Type')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Edit Shipment Type</h1>
        <p style="color: #64748b;">Update shipment type information - {{ $shipmentType->type_name }}</p>
    </div>

    <form action="{{ route('logistics.shipment-types.update', $shipmentType) }}" method="POST">
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
                        <label class="form-label">Type Code *</label>
                        <input type="text" name="type_code" class="form-input" required
                            value="{{ old('type_code', $shipmentType->type_code) }}"
                            placeholder="e.g., OCN-FCL-STD, AIR-EXP-PRI">
                        @error('type_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Type Name *</label>
                        <input type="text" name="type_name" class="form-input" required
                            value="{{ old('type_name', $shipmentType->type_name) }}" placeholder="Shipment type name">
                        @error('type_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-input" required>
                            <option value="">Select Category</option>
                            <option value="Ocean Freight"
                                {{ old('category', $shipmentType->category) == 'Ocean Freight' ? 'selected' : '' }}>🚢 Ocean
                                Freight</option>
                            <option value="Air Freight"
                                {{ old('category', $shipmentType->category) == 'Air Freight' ? 'selected' : '' }}>✈️ Air
                                Freight</option>
                            <option value="Land Transport"
                                {{ old('category', $shipmentType->category) == 'Land Transport' ? 'selected' : '' }}>🚛 Land
                                Transport</option>
                            <option value="Rail Transport"
                                {{ old('category', $shipmentType->category) == 'Rail Transport' ? 'selected' : '' }}>🚂 Rail
                                Transport</option>
                            <option value="Multimodal"
                                {{ old('category', $shipmentType->category) == 'Multimodal' ? 'selected' : '' }}>🔄
                                Multimodal</option>
                            <option value="Express"
                                {{ old('category', $shipmentType->category) == 'Express' ? 'selected' : '' }}>⚡ Express
                            </option>
                            <option value="Economy"
                                {{ old('category', $shipmentType->category) == 'Economy' ? 'selected' : '' }}>💰 Economy
                            </option>
                            <option value="Special Handling"
                                {{ old('category', $shipmentType->category) == 'Special Handling' ? 'selected' : '' }}>⚠️
                                Special Handling</option>
                            <option value="Project Cargo"
                                {{ old('category', $shipmentType->category) == 'Project Cargo' ? 'selected' : '' }}>🏗️
                                Project Cargo</option>
                            <option value="Bulk Cargo"
                                {{ old('category', $shipmentType->category) == 'Bulk Cargo' ? 'selected' : '' }}>⚖️ Bulk
                                Cargo</option>
                            <option value="Container"
                                {{ old('category', $shipmentType->category) == 'Container' ? 'selected' : '' }}>📦
                                Container</option>
                            <option value="Break Bulk"
                                {{ old('category', $shipmentType->category) == 'Break Bulk' ? 'selected' : '' }}>📋 Break
                                Bulk</option>
                        </select>
                        @error('category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subcategory</label>
                        <input type="text" name="subcategory" class="form-input"
                            value="{{ old('subcategory', $shipmentType->subcategory) }}"
                            placeholder="e.g., Full Container Load, Express Service">
                        @error('subcategory')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cargo Type *</label>
                        <select name="cargo_type" class="form-input" required>
                            <option value="">Select Cargo Type</option>
                            <option value="General Cargo"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'General Cargo' ? 'selected' : '' }}>📦
                                General Cargo</option>
                            <option value="Dangerous Goods"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Dangerous Goods' ? 'selected' : '' }}>
                                ☢️ Dangerous Goods</option>
                            <option value="Refrigerated"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Refrigerated' ? 'selected' : '' }}>❄️
                                Refrigerated</option>
                            <option value="Liquid Bulk"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Liquid Bulk' ? 'selected' : '' }}>🌊
                                Liquid Bulk</option>
                            <option value="Dry Bulk"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Dry Bulk' ? 'selected' : '' }}>⚖️ Dry
                                Bulk</option>
                            <option value="Vehicles"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Vehicles' ? 'selected' : '' }}>🚗
                                Vehicles</option>
                            <option value="Heavy Machinery"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Heavy Machinery' ? 'selected' : '' }}>
                                🏗️ Heavy Machinery</option>
                            <option value="Electronics"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Electronics' ? 'selected' : '' }}>💻
                                Electronics</option>
                            <option value="Pharmaceuticals"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Pharmaceuticals' ? 'selected' : '' }}>
                                💊 Pharmaceuticals</option>
                            <option value="Food Products"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Food Products' ? 'selected' : '' }}>🍎
                                Food Products</option>
                            <option value="Textiles"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Textiles' ? 'selected' : '' }}>🧵
                                Textiles</option>
                            <option value="Chemicals"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Chemicals' ? 'selected' : '' }}>🧪
                                Chemicals</option>
                            <option value="Raw Materials"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Raw Materials' ? 'selected' : '' }}>🪨
                                Raw Materials</option>
                            <option value="Finished Goods"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Finished Goods' ? 'selected' : '' }}>📦
                                Finished Goods</option>
                            <option value="Perishables"
                                {{ old('cargo_type', $shipmentType->cargo_type) == 'Perishables' ? 'selected' : '' }}>🥬
                                Perishables</option>
                        </select>
                        @error('cargo_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Transit Mode *</label>
                        <select name="transit_mode" class="form-input" required>
                            <option value="">Select Transit Mode</option>
                            <option value="Sea"
                                {{ old('transit_mode', $shipmentType->transit_mode) == 'Sea' ? 'selected' : '' }}>🚢 Sea
                                Freight</option>
                            <option value="Air"
                                {{ old('transit_mode', $shipmentType->transit_mode) == 'Air' ? 'selected' : '' }}>✈️ Air
                                Freight</option>
                            <option value="Road"
                                {{ old('transit_mode', $shipmentType->transit_mode) == 'Road' ? 'selected' : '' }}>🚛 Road
                                Transport</option>
                            <option value="Rail"
                                {{ old('transit_mode', $shipmentType->transit_mode) == 'Rail' ? 'selected' : '' }}>🚂 Rail
                                Transport</option>
                            <option value="Barge"
                                {{ old('transit_mode', $shipmentType->transit_mode) == 'Barge' ? 'selected' : '' }}>🚤
                                Barge Transport</option>
                            <option value="Pipeline"
                                {{ old('transit_mode', $shipmentType->transit_mode) == 'Pipeline' ? 'selected' : '' }}>🔧
                                Pipeline</option>
                            <option value="Multimodal"
                                {{ old('transit_mode', $shipmentType->transit_mode) == 'Multimodal' ? 'selected' : '' }}>🔄
                                Multimodal</option>
                        </select>
                        @error('transit_mode')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="Active"
                                {{ old('status', $shipmentType->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive"
                                {{ old('status', $shipmentType->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="Suspended"
                                {{ old('status', $shipmentType->status) == 'Suspended' ? 'selected' : '' }}>Suspended
                            </option>
                            <option value="Discontinued"
                                {{ old('status', $shipmentType->status) == 'Discontinued' ? 'selected' : '' }}>Discontinued
                            </option>
                            <option value="Under Review"
                                {{ old('status', $shipmentType->status) == 'Under Review' ? 'selected' : '' }}>Under Review
                            </option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="3" placeholder="Brief description of the shipment type">{{ old('description', $shipmentType->description) }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Detailed Description</label>
                        <textarea name="detailed_description" class="form-input" rows="3"
                            placeholder="Detailed description with features and benefits">{{ old('detailed_description', $shipmentType->detailed_description) }}</textarea>
                        @error('detailed_description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Transit and Timing -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🕐 Transit and Timing</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Estimated Transit Days</label>
                        <input type="number" name="estimated_transit_days" class="form-input" min="0"
                            max="365"
                            value="{{ old('estimated_transit_days', $shipmentType->estimated_transit_days) }}"
                            placeholder="e.g., 25">
                        @error('estimated_transit_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Minimum Transit Days</label>
                        <input type="number" name="min_transit_days" class="form-input" min="0" max="365"
                            value="{{ old('min_transit_days', $shipmentType->min_transit_days) }}"
                            placeholder="e.g., 20">
                        @error('min_transit_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Maximum Transit Days</label>
                        <input type="number" name="max_transit_days" class="form-input" min="0" max="365"
                            value="{{ old('max_transit_days', $shipmentType->max_transit_days) }}"
                            placeholder="e.g., 35">
                        @error('max_transit_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Booking Lead Time (Days)</label>
                        <input type="number" name="booking_lead_time" class="form-input" min="0" max="30"
                            value="{{ old('booking_lead_time', $shipmentType->booking_lead_time) }}"
                            placeholder="e.g., 7">
                        @error('booking_lead_time')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Cost and Priority -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>💰 Cost and Priority</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Cost Factor</label>
                        <input type="number" name="cost_factor" class="form-input" step="0.01" min="0"
                            max="10" value="{{ old('cost_factor', $shipmentType->cost_factor) }}"
                            placeholder="e.g., 1.5">
                        @error('cost_factor')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Base Rate Multiplier</label>
                        <input type="number" name="base_rate_multiplier" class="form-input" step="0.001"
                            min="0" max="10"
                            value="{{ old('base_rate_multiplier', $shipmentType->base_rate_multiplier) }}"
                            placeholder="e.g., 1.250">
                        @error('base_rate_multiplier')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Priority Level</label>
                        <select name="priority_level" class="form-input">
                            <option value="">Select Priority</option>
                            <option value="Low"
                                {{ old('priority_level', $shipmentType->priority_level) == 'Low' ? 'selected' : '' }}>🟢
                                Low Priority</option>
                            <option value="Standard"
                                {{ old('priority_level', $shipmentType->priority_level) == 'Standard' ? 'selected' : '' }}>
                                🟡 Standard Priority</option>
                            <option value="High"
                                {{ old('priority_level', $shipmentType->priority_level) == 'High' ? 'selected' : '' }}>🟠
                                High Priority</option>
                            <option value="Urgent"
                                {{ old('priority_level', $shipmentType->priority_level) == 'Urgent' ? 'selected' : '' }}>🔴
                                Urgent Priority</option>
                            <option value="Critical"
                                {{ old('priority_level', $shipmentType->priority_level) == 'Critical' ? 'selected' : '' }}>
                                ⚫ Critical Priority</option>
                        </select>
                        @error('priority_level')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Service Level</label>
                        <select name="service_level" class="form-input">
                            <option value="">Select Service Level</option>
                            <option value="Basic"
                                {{ old('service_level', $shipmentType->service_level) == 'Basic' ? 'selected' : '' }}>📦
                                Basic Service</option>
                            <option value="Standard"
                                {{ old('service_level', $shipmentType->service_level) == 'Standard' ? 'selected' : '' }}>⭐
                                Standard Service</option>
                            <option value="Premium"
                                {{ old('service_level', $shipmentType->service_level) == 'Premium' ? 'selected' : '' }}>👑
                                Premium Service</option>
                            <option value="Express"
                                {{ old('service_level', $shipmentType->service_level) == 'Express' ? 'selected' : '' }}>⚡
                                Express Service</option>
                            <option value="Economy"
                                {{ old('service_level', $shipmentType->service_level) == 'Economy' ? 'selected' : '' }}>💰
                                Economy Service</option>
                        </select>
                        @error('service_level')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Customs Complexity</label>
                        <select name="customs_complexity" class="form-input">
                            <option value="">Select Complexity</option>
                            <option value="Simple"
                                {{ old('customs_complexity', $shipmentType->customs_complexity) == 'Simple' ? 'selected' : '' }}>
                                Simple</option>
                            <option value="Standard"
                                {{ old('customs_complexity', $shipmentType->customs_complexity) == 'Standard' ? 'selected' : '' }}>
                                Standard</option>
                            <option value="Complex"
                                {{ old('customs_complexity', $shipmentType->customs_complexity) == 'Complex' ? 'selected' : '' }}>
                                Complex</option>
                            <option value="Very Complex"
                                {{ old('customs_complexity', $shipmentType->customs_complexity) == 'Very Complex' ? 'selected' : '' }}>
                                Very Complex</option>
                        </select>
                        @error('customs_complexity')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tracking Level</label>
                        <select name="tracking_level" class="form-input">
                            <option value="">Select Tracking Level</option>
                            <option value="Basic"
                                {{ old('tracking_level', $shipmentType->tracking_level) == 'Basic' ? 'selected' : '' }}>
                                Basic</option>
                            <option value="Standard"
                                {{ old('tracking_level', $shipmentType->tracking_level) == 'Standard' ? 'selected' : '' }}>
                                Standard</option>
                            <option value="Advanced"
                                {{ old('tracking_level', $shipmentType->tracking_level) == 'Advanced' ? 'selected' : '' }}>
                                Advanced</option>
                            <option value="Real-time"
                                {{ old('tracking_level', $shipmentType->tracking_level) == 'Real-time' ? 'selected' : '' }}>
                                Real-time</option>
                        </select>
                        @error('tracking_level')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Characteristics -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>⚠️ Special Characteristics</h3>
            </div>
            <div class="card-body">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="temperature_controlled" value="1"
                            {{ old('temperature_controlled', $shipmentType->temperature_controlled) ? 'checked' : '' }}>
                        ❄️ Temperature Controlled
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="hazardous_material" value="1"
                            {{ old('hazardous_material', $shipmentType->hazardous_material) ? 'checked' : '' }}>
                        ☢️ Hazardous Material
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="high_value_cargo" value="1"
                            {{ old('high_value_cargo', $shipmentType->high_value_cargo) ? 'checked' : '' }}>
                        💎 High Value Cargo
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="fragile_cargo" value="1"
                            {{ old('fragile_cargo', $shipmentType->fragile_cargo) ? 'checked' : '' }}>
                        🔸 Fragile Cargo
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="oversized_cargo" value="1"
                            {{ old('oversized_cargo', $shipmentType->oversized_cargo) ? 'checked' : '' }}>
                        📏 Oversized Cargo
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="requires_escort" value="1"
                            {{ old('requires_escort', $shipmentType->requires_escort) ? 'checked' : '' }}>
                        🚔 Requires Escort
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="insurance_required" value="1"
                            {{ old('insurance_required', $shipmentType->insurance_required) ? 'checked' : '' }}>
                        🛡️ Insurance Required
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="inspection_required" value="1"
                            {{ old('inspection_required', $shipmentType->inspection_required) ? 'checked' : '' }}>
                        🔍 Inspection Required
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="quarantine_required" value="1"
                            {{ old('quarantine_required', $shipmentType->quarantine_required) ? 'checked' : '' }}>
                        🏥 Quarantine Required
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="permit_required" value="1"
                            {{ old('permit_required', $shipmentType->permit_required) ? 'checked' : '' }}>
                        📋 Permit Required
                    </label>
                </div>
            </div>
        </div>

        <!-- Service Options -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🚚 Service Options</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="door_to_door_available" value="1"
                            {{ old('door_to_door_available', $shipmentType->door_to_door_available) ? 'checked' : '' }}>
                        🚪 Door-to-Door Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="port_to_port_only" value="1"
                            {{ old('port_to_port_only', $shipmentType->port_to_port_only) ? 'checked' : '' }}>
                        ⚓ Port-to-Port Only
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="express_service_available" value="1"
                            {{ old('express_service_available', $shipmentType->express_service_available) ? 'checked' : '' }}>
                        ⚡ Express Service Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="economy_service_available" value="1"
                            {{ old('economy_service_available', $shipmentType->economy_service_available) ? 'checked' : '' }}>
                        💰 Economy Service Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="standard_service_available" value="1"
                            {{ old('standard_service_available', $shipmentType->standard_service_available) ? 'checked' : '' }}>
                        ⭐ Standard Service Available
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="consolidation_allowed" value="1"
                            {{ old('consolidation_allowed', $shipmentType->consolidation_allowed) ? 'checked' : '' }}>
                        📦 Consolidation Allowed
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="partial_loads_allowed" value="1"
                            {{ old('partial_loads_allowed', $shipmentType->partial_loads_allowed) ? 'checked' : '' }}>
                        📋 Partial Loads Allowed
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="return_loads_allowed" value="1"
                            {{ old('return_loads_allowed', $shipmentType->return_loads_allowed) ? 'checked' : '' }}>
                        🔄 Return Loads Allowed
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="transshipment_allowed" value="1"
                            {{ old('transshipment_allowed', $shipmentType->transshipment_allowed) ? 'checked' : '' }}>
                        🔄 Transshipment Allowed
                    </label>
                </div>
            </div>
        </div>

        <!-- Container Types -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📦 Container Types</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Applicable Container Types</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $containerTypes = [
                                '20GP' => '20ft General Purpose',
                                '40GP' => '40ft General Purpose',
                                '40HC' => '40ft High Cube',
                                '45HC' => '45ft High Cube',
                                '20RF' => '20ft Refrigerated',
                                '40RF' => '40ft Refrigerated',
                                '20OT' => '20ft Open Top',
                                '40OT' => '40ft Open Top',
                                '20FR' => '20ft Flat Rack',
                                '40FR' => '40ft Flat Rack',
                                '20TK' => '20ft Tank Container',
                                '40TK' => '40ft Tank Container',
                            ];
                            $selectedContainers = old('container_types', $shipmentType->container_types ?? []);
                        @endphp
                        @foreach ($containerTypes as $code => $name)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="container_types[]" value="{{ $code }}"
                                    {{ in_array($code, $selectedContainers) ? 'checked' : '' }}>
                                {{ $name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Handling Requirements -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🏗️ Handling Requirements</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Special Handling Requirements</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $handlingRequirements = [
                                'Standard Loading',
                                'Careful Handling',
                                'Temperature Control',
                                'Hazmat Procedures',
                                'Security Escort',
                                'Special Equipment',
                                'Crane Required',
                                'Forklift Access',
                                'Side Loading',
                                'Top Loading',
                                'Fragile Item Care',
                                'Heavy Lift',
                                'Oversized Handling',
                                'Clean Environment',
                                'Dry Storage',
                                'Ventilation Required',
                            ];
                            $selectedHandling = old(
                                'handling_requirements',
                                $shipmentType->handling_requirements ?? [],
                            );
                        @endphp
                        @foreach ($handlingRequirements as $requirement)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="handling_requirements[]" value="{{ $requirement }}"
                                    {{ in_array($requirement, $selectedHandling) ? 'checked' : '' }}>
                                {{ $requirement }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation Required -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📄 Documentation Required</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Required Documentation</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        @php
                            $documentationList = [
                                'Commercial Invoice',
                                'Packing List',
                                'Bill of Lading',
                                'Certificate of Origin',
                                'Export License',
                                'Import Permit',
                                'Dangerous Goods Declaration',
                                'Temperature Certificate',
                                'Phytosanitary Certificate',
                                'Health Certificate',
                                'Insurance Certificate',
                                'Inspection Certificate',
                                'Quality Certificate',
                                'Weight Certificate',
                                'Customs Declaration',
                                'Transit Documents',
                            ];
                            $selectedDocs = old('documentation_required', $shipmentType->documentation_required ?? []);
                        @endphp
                        @foreach ($documentationList as $document)
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="documentation_required[]" value="{{ $document }}"
                                    {{ in_array($document, $selectedDocs) ? 'checked' : '' }}>
                                {{ $document }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Effective Dates -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📅 Effective Dates</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Effective From</label>
                        <input type="date" name="effective_from" class="form-input"
                            value="{{ old('effective_from', $shipmentType->effective_from ? $shipmentType->effective_from->format('Y-m-d') : '') }}">
                        @error('effective_from')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Effective To</label>
                        <input type="date" name="effective_to" class="form-input"
                            value="{{ old('effective_to', $shipmentType->effective_to ? $shipmentType->effective_to->format('Y-m-d') : '') }}">
                        @error('effective_to')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📝 Additional Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Special Instructions</label>
                    <textarea name="special_instructions" class="form-input" rows="3"
                        placeholder="Special handling instructions or operational notes">{{ old('special_instructions', $shipmentType->special_instructions) }}</textarea>
                    @error('special_instructions')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="Additional notes about this shipment type">{{ old('notes', $shipmentType->notes) }}</textarea>
                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('logistics.shipment-types.show', $shipmentType) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Shipment Type</button>
        </div>
    </form>
@endsection
