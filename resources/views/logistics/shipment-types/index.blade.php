@extends('layouts.app')

@section('title', 'Shipment Types')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">📦 Shipment Types
                    Management
                </h1>
                <p style="color: #64748b;">Manage shipment types and transportation modes</p>
            </div>
            <a href="{{ route('logistics.shipment-types.create') }}" class="btn btn-primary">+ Add New Shipment Type</a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>🔍 Filters</h3>
        </div>
        <div class="card-body">
            <form method="GET"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Type name, code, description...">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-input">
                        <option value="">All Categories</option>
                        <option value="Ocean Freight" {{ request('category') == 'Ocean Freight' ? 'selected' : '' }}>🚢
                            Ocean Freight</option>
                        <option value="Air Freight" {{ request('category') == 'Air Freight' ? 'selected' : '' }}>✈️ Air
                            Freight</option>
                        <option value="Land Transport" {{ request('category') == 'Land Transport' ? 'selected' : '' }}>🚛
                            Land Transport</option>
                        <option value="Rail Transport" {{ request('category') == 'Rail Transport' ? 'selected' : '' }}>🚂
                            Rail Transport</option>
                        <option value="Multimodal" {{ request('category') == 'Multimodal' ? 'selected' : '' }}>🔄 Multimodal
                        </option>
                        <option value="Express" {{ request('category') == 'Express' ? 'selected' : '' }}>⚡ Express</option>
                        <option value="Economy" {{ request('category') == 'Economy' ? 'selected' : '' }}>💰 Economy</option>
                        <option value="Special Handling" {{ request('category') == 'Special Handling' ? 'selected' : '' }}>
                            ⚠️ Special Handling</option>
                        <option value="Project Cargo" {{ request('category') == 'Project Cargo' ? 'selected' : '' }}>🏗️
                            Project Cargo</option>
                        <option value="Bulk Cargo" {{ request('category') == 'Bulk Cargo' ? 'selected' : '' }}>⚖️ Bulk
                            Cargo</option>
                        <option value="Container" {{ request('category') == 'Container' ? 'selected' : '' }}>📦 Container
                        </option>
                        <option value="Break Bulk" {{ request('category') == 'Break Bulk' ? 'selected' : '' }}>📋 Break
                            Bulk</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Cargo Type</label>
                    <select name="cargo_type" class="form-input">
                        <option value="">All Cargo Types</option>
                        <option value="General Cargo" {{ request('cargo_type') == 'General Cargo' ? 'selected' : '' }}>📦
                            General Cargo</option>
                        <option value="Dangerous Goods" {{ request('cargo_type') == 'Dangerous Goods' ? 'selected' : '' }}>
                            ☢️ Dangerous Goods</option>
                        <option value="Refrigerated" {{ request('cargo_type') == 'Refrigerated' ? 'selected' : '' }}>❄️
                            Refrigerated</option>
                        <option value="Liquid Bulk" {{ request('cargo_type') == 'Liquid Bulk' ? 'selected' : '' }}>🌊
                            Liquid Bulk</option>
                        <option value="Dry Bulk" {{ request('cargo_type') == 'Dry Bulk' ? 'selected' : '' }}>⚖️ Dry Bulk
                        </option>
                        <option value="Vehicles" {{ request('cargo_type') == 'Vehicles' ? 'selected' : '' }}>🚗 Vehicles
                        </option>
                        <option value="Heavy Machinery" {{ request('cargo_type') == 'Heavy Machinery' ? 'selected' : '' }}>
                            🏗️ Heavy Machinery</option>
                        <option value="Electronics" {{ request('cargo_type') == 'Electronics' ? 'selected' : '' }}>💻
                            Electronics</option>
                        <option value="Pharmaceuticals" {{ request('cargo_type') == 'Pharmaceuticals' ? 'selected' : '' }}>
                            💊 Pharmaceuticals</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Transit Mode</label>
                    <select name="transit_mode" class="form-input">
                        <option value="">All Modes</option>
                        <option value="Sea" {{ request('transit_mode') == 'Sea' ? 'selected' : '' }}>🚢 Sea Freight
                        </option>
                        <option value="Air" {{ request('transit_mode') == 'Air' ? 'selected' : '' }}>✈️ Air Freight
                        </option>
                        <option value="Road" {{ request('transit_mode') == 'Road' ? 'selected' : '' }}>🚛 Road Transport
                        </option>
                        <option value="Rail" {{ request('transit_mode') == 'Rail' ? 'selected' : '' }}>🚂 Rail Transport
                        </option>
                        <option value="Multimodal" {{ request('transit_mode') == 'Multimodal' ? 'selected' : '' }}>🔄
                            Multimodal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Special Features</label>
                    <select name="temperature_controlled" class="form-input">
                        <option value="">All Types</option>
                        <option value="1" {{ request('temperature_controlled') == '1' ? 'selected' : '' }}>❄️
                            Temperature Controlled</option>
                        <option value="1" {{ request('hazardous_material') == '1' ? 'selected' : '' }}>☢️ Hazardous
                            Material</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Priority Level</label>
                    <select name="priority_level" class="form-input">
                        <option value="">All Priorities</option>
                        <option value="Low" {{ request('priority_level') == 'Low' ? 'selected' : '' }}>🟢 Low</option>
                        <option value="Standard" {{ request('priority_level') == 'Standard' ? 'selected' : '' }}>🟡
                            Standard</option>
                        <option value="High" {{ request('priority_level') == 'High' ? 'selected' : '' }}>🟠 High</option>
                        <option value="Urgent" {{ request('priority_level') == 'Urgent' ? 'selected' : '' }}>🔴 Urgent
                        </option>
                        <option value="Critical" {{ request('priority_level') == 'Critical' ? 'selected' : '' }}>⚫ Critical
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Suspended" {{ request('status') == 'Suspended' ? 'selected' : '' }}>Suspended
                        </option>
                        <option value="Discontinued" {{ request('status') == 'Discontinued' ? 'selected' : '' }}>
                            Discontinued</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('logistics.shipment-types.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Shipment Types Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type Name</th>
                        <th>Category</th>
                        <th>Cargo Type</th>
                        <th>Transit Mode</th>
                        <th>Transit Time</th>
                        <th>Cost Factor</th>
                        <th>Features</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipmentTypes as $type)
                        <tr>
                            <td><strong>{{ $type->type_code }}</strong></td>
                            <td>
                                <div>{{ $type->type_name }}</div>
                                @if ($type->subcategory)
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $type->subcategory }}</div>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="category-badge category-{{ strtolower(str_replace(' ', '-', $type->category)) }}">
                                    {{ $type->category_display }}
                                </span>
                            </td>
                            <td>
                                <span class="cargo-badge cargo-{{ strtolower(str_replace(' ', '-', $type->cargo_type)) }}">
                                    {{ $type->cargo_type_display }}
                                </span>
                            </td>
                            <td>
                                <span class="transit-badge transit-{{ strtolower($type->transit_mode) }}">
                                    {{ $type->transit_mode_display }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $type->estimated_transit_display }}</div>
                                @if ($type->priority_level)
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $type->priority_level }} Priority
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $type->cost_factor ?? 'N/A' }}</div>
                                @if ($type->base_rate_multiplier != 1.0)
                                    <div style="font-size: 0.75rem; color: #64748b;">×{{ $type->base_rate_multiplier }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                    @if ($type->temperature_controlled)
                                        <span class="feature-badge">❄️</span>
                                    @endif
                                    @if ($type->hazardous_material)
                                        <span class="feature-badge">☢️</span>
                                    @endif
                                    @if ($type->high_value_cargo)
                                        <span class="feature-badge">💎</span>
                                    @endif
                                    @if ($type->fragile_cargo)
                                        <span class="feature-badge">🔸</span>
                                    @endif
                                    @if ($type->oversized_cargo)
                                        <span class="feature-badge">📏</span>
                                    @endif
                                    @if ($type->express_service_available)
                                        <span class="feature-badge">⚡</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $type->status_badge }}">{{ $type->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('logistics.shipment-types.show', $type) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('logistics.shipment-types.edit', $type) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('logistics.shipment-types.destroy', $type) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 2rem; color: #64748b;">
                                No shipment types found. <a href="{{ route('logistics.shipment-types.create') }}"
                                    style="color: var(--primary-color);">Create your first shipment type</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($shipmentTypes) && $shipmentTypes->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $shipmentTypes->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .category-ocean-freight {
            background: #dbeafe;
            color: #1e40af;
        }

        .category-air-freight {
            background: #f0f9ff;
            color: #0369a1;
        }

        .category-land-transport {
            background: #fef3c7;
            color: #92400e;
        }

        .category-rail-transport {
            background: #e0e7ff;
            color: #3730a3;
        }

        .category-multimodal {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .category-express {
            background: #fee2e2;
            color: #991b1b;
        }

        .category-economy {
            background: #dcfce7;
            color: #166534;
        }

        .category-special-handling {
            background: #fce7f3;
            color: #be185d;
        }

        .category-project-cargo {
            background: #f8fafc;
            color: #475569;
        }

        .category-bulk-cargo {
            background: #ecfdf5;
            color: #047857;
        }

        .category-container {
            background: #d1fae5;
            color: #065f46;
        }

        .category-break-bulk {
            background: #f1f5f9;
            color: #64748b;
        }

        .cargo-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .cargo-general-cargo {
            background: #f8fafc;
            color: #475569;
        }

        .cargo-dangerous-goods {
            background: #fee2e2;
            color: #991b1b;
        }

        .cargo-refrigerated {
            background: #dbeafe;
            color: #1e40af;
        }

        .cargo-liquid-bulk {
            background: #dcfce7;
            color: #166534;
        }

        .cargo-dry-bulk {
            background: #fef3c7;
            color: #92400e;
        }

        .cargo-vehicles {
            background: #e0e7ff;
            color: #3730a3;
        }

        .cargo-heavy-machinery {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .cargo-electronics {
            background: #ecfdf5;
            color: #047857;
        }

        .cargo-pharmaceuticals {
            background: #fce7f3;
            color: #be185d;
        }

        .transit-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .transit-sea {
            background: #dbeafe;
            color: #1e40af;
        }

        .transit-air {
            background: #f0f9ff;
            color: #0369a1;
        }

        .transit-road {
            background: #fef3c7;
            color: #92400e;
        }

        .transit-rail {
            background: #e0e7ff;
            color: #3730a3;
        }

        .transit-multimodal {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .feature-badge {
            padding: 0.125rem 0.25rem;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 0.75rem;
            border: 1px solid #e2e8f0;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-info {
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
@endsection
