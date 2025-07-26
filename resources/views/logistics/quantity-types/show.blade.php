@extends('layouts.app')

@section('title', 'Quantity Type Details - ' . $quantityType->quantity_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $quantityType->quantity_name }}
                </h1>
                <p style="color: #64748b;">Quantity Type Details - {{ $quantityType->quantity_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('logistics.quantity-types.edit', $quantityType) }}" class="btn btn-primary">Edit Quantity
                    Type</a>
                <a href="{{ route('logistics.quantity-types.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Basic Information -->
        <div class="card">
            <div class="card-header">
                <h3>📋 Basic Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>Quantity Code:</strong><br>
                        <span
                            style="color: #64748b; font-family: monospace; font-size: 1.1rem;">{{ $quantityType->quantity_code }}</span>
                    </div>
                    <div>
                        <strong>Quantity Name:</strong><br>
                        <span style="color: #64748b;">{{ $quantityType->quantity_name }}</span>
                    </div>
                    <div>
                        <strong>Category:</strong><br>
                        <span
                            class="category-badge category-{{ strtolower(str_replace(' ', '-', $quantityType->quantity_category)) }}">
                            {{ $quantityType->category_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Unit Symbol:</strong><br>
                        <span
                            style="color: #64748b; font-weight: 600; font-size: 1.1rem;">{{ $quantityType->unit_symbol }}</span>
                    </div>
                    <div>
                        <strong>Unit of Measure:</strong><br>
                        <span style="color: #64748b;">{{ $quantityType->unit_of_measure }}</span>
                    </div>
                    <div>
                        <strong>Sort Order:</strong><br>
                        <span style="color: #64748b;">{{ $quantityType->sort_order }}</span>
                    </div>
                </div>

                @if ($quantityType->description)
                    <div style="margin-top: 1.5rem;">
                        <strong>Description:</strong><br>
                        <span style="color: #64748b;">{{ $quantityType->description }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h3>📊 Usage Statistics</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 1rem;">
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                            {{ $statistics['total_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Total Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #059669;">
                            {{ $statistics['active_shipments'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Active Shipments</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #0ea5e9;">
                            {{ $statistics['monthly_usage'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">This Month</div>
                    </div>
                    @if ($statistics['total_quantity'] > 0)
                        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                            <div style="font-size: 1.5rem; font-weight: bold; color: #7c3aed;">
                                {{ number_format($statistics['total_quantity'], $quantityType->decimal_places) }}
                            </div>
                            <div style="font-size: 0.875rem; color: #64748b;">Total Quantity</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion & Calculation -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>🔄 Conversion & Calculation</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @if ($quantityType->base_unit)
                    <div>
                        <strong>Base Unit:</strong><br>
                        <span style="color: #64748b;">{{ $quantityType->base_unit }}</span>
                    </div>
                @endif
                <div>
                    <strong>Conversion Factor:</strong><br>
                    <span style="color: #059669; font-weight: 600;">{{ $quantityType->conversion_factor }}x</span>
                </div>
                <div>
                    <strong>Decimal Places:</strong><br>
                    <span style="color: #64748b;">{{ $quantityType->decimal_places }}</span>
                </div>
                <div>
                    <strong>Rounding Method:</strong><br>
                    <span style="color: #64748b;">{{ $quantityType->rounding_display }}</span>
                </div>
                @if ($quantityType->display_format)
                    <div>
                        <strong>Display Format:</strong><br>
                        <span style="color: #64748b; font-family: monospace;">{{ $quantityType->display_format }}</span>
                    </div>
                @endif
                @if ($quantityType->reporting_category)
                    <div>
                        <strong>Reporting Category:</strong><br>
                        <span style="color: #64748b;">{{ $quantityType->reporting_category }}</span>
                    </div>
                @endif
            </div>

            @if ($quantityType->calculation_method)
                <div style="margin-top: 1.5rem;">
                    <strong>Calculation Method:</strong><br>
                    <span style="color: #64748b;">{{ $quantityType->calculation_method }}</span>
                </div>
            @endif

            @if ($quantityType->common_ranges)
                <div style="margin-top: 1.5rem;">
                    <strong>Valid Range:</strong><br>
                    <span style="color: #64748b;">{{ $quantityType->formatted_range }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Type Classifications -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>🏷️ Type Classifications</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Weight Based:</strong><br>
                    @if ($quantityType->is_weight_based)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Volume Based:</strong><br>
                    @if ($quantityType->is_volume_based)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Count Based:</strong><br>
                    @if ($quantityType->is_count_based)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Dimension Based:</strong><br>
                    @if ($quantityType->is_dimension_based)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Allows Fractions:</strong><br>
                    @if ($quantityType->allows_fractions)
                        <span class="status-badge status-success">Yes</span>
                    @else
                        <span class="status-badge status-warning">No</span>
                    @endif
                </div>
                <div>
                    <strong>Requires Dimensions:</strong><br>
                    @if ($quantityType->requires_dimensions)
                        <span class="status-badge status-warning">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
                <div>
                    <strong>Auto Calculate:</strong><br>
                    @if ($quantityType->auto_calculate)
                        <span class="status-badge status-info">Yes</span>
                    @else
                        <span class="status-badge status-secondary">No</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Billing & Commercial -->
    @if ($quantityType->is_billable)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>💰 Billing & Commercial</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>Billable:</strong><br>
                        <span class="status-badge status-success">Yes</span>
                    </div>
                    <div>
                        <strong>Billing Multiplier:</strong><br>
                        <span style="color: #059669; font-weight: 600;">{{ $quantityType->billing_multiplier }}x</span>
                    </div>
                    <div>
                        <strong>Minimum Chargeable:</strong><br>
                        <span style="color: #64748b;">{{ $quantityType->minimum_chargeable }}
                            {{ $quantityType->unit_symbol }}</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>💰 Billing & Commercial</h3>
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 2rem; color: #64748b;">
                    <span class="status-badge status-secondary">Not Billable</span>
                    <p style="margin-top: 0.5rem;">This quantity type is used for tracking purposes only</p>
                </div>
            </div>
        </div>
    @endif

    <!-- System Properties -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>⚙️ System Properties</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <strong>Standard Type:</strong><br>
                    @if ($quantityType->is_standard)
                        <span class="status-badge status-standard">Standard</span>
                    @else
                        <span class="status-badge status-custom">Custom</span>
                    @endif
                </div>
                <div>
                    <strong>Status:</strong><br>
                    @if ($quantityType->is_active)
                        <span class="status-badge status-active">Active</span>
                    @else
                        <span class="status-badge status-inactive">Inactive</span>
                    @endif
                </div>
                <div>
                    <strong>Created:</strong><br>
                    <span style="color: #64748b;">{{ $quantityType->created_at->format('M j, Y') }}</span>
                </div>
                <div>
                    <strong>Last Updated:</strong><br>
                    <span style="color: #64748b;">{{ $quantityType->updated_at->format('M j, Y') }}</span>
                </div>
            </div>

            @if ($quantityType->notes)
                <div style="margin-top: 1.5rem;">
                    <strong>Notes:</strong><br>
                    <span style="color: #64748b;">{{ $quantityType->notes }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Applicable Cargo Types -->
    @if ($quantityType->applicable_cargo_types && count($quantityType->applicable_cargo_types) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Applicable Cargo Types</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($quantityType->applicable_cargo_types as $cargoType)
                        <span class="cargo-badge">{{ $cargoType }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Industry Standards -->
    @if ($quantityType->industry_standards && count($quantityType->industry_standards) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📜 Industry Standards</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($quantityType->industry_standards as $standard)
                        <span class="standard-badge">{{ $standard }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Validation Rules -->
    @if ($quantityType->validation_rules && count($quantityType->validation_rules) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>✅ Validation Rules</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($quantityType->validation_rules as $rule)
                        <span class="validation-badge">{{ $rule }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Calculation Instructions -->
    @php
        $instructions = $quantityType->getCalculationInstructions();
    @endphp
    @if (!empty($instructions))
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📋 Calculation Instructions</h3>
            </div>
            <div class="card-body">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($instructions as $instruction)
                        <li style="margin-bottom: 0.5rem; color: #64748b;">{{ $instruction }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <style>
        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .category-container {
            background: #dbeafe;
            color: #1e40af;
        }

        .category-weight {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .category-volume {
            background: #dcfce7;
            color: #166534;
        }

        .category-count {
            background: #fef3c7;
            color: #92400e;
        }

        .category-area {
            background: #fce7f3;
            color: #be185d;
        }

        .category-liquid {
            background: #e0f2fe;
            color: #0369a1;
        }

        .category-length {
            background: #f0fdf4;
            color: #15803d;
        }

        .category-time {
            background: #fdf4ff;
            color: #a21caf;
        }

        .cargo-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #0ea5e9;
        }

        .standard-badge {
            background: #ecfdf5;
            color: #047857;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #10b981;
        }

        .validation-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #f59e0b;
        }

        .status-standard {
            background: #dcfce7;
            color: #166534;
        }

        .status-custom {
            background: #e0f2fe;
            color: #0369a1;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-info {
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
@endsection
