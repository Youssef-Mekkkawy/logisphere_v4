@extends('layouts.app')

@section('title', $quantityType->quantity_name . ' - Quantity Type Details')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 text-gray-800 mb-0">
                    <i class="fas fa-balance-scale text-primary me-2"></i>{{ $quantityType->quantity_name }}
                    <span class="badge bg-{{ $quantityType->is_active ? 'success' : 'secondary' }} ms-2">
                        {{ $quantityType->status }}
                    </span>
                    @if ($quantityType->is_standard)
                        <span class="badge bg-warning text-dark ms-1">Standard</span>
                    @endif
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('submenu.index') }}">Submenu</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('submenu.quantity-types.index') }}">Quantity Types</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $quantityType->quantity_code }}</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group">
                <a href="{{ route('submenu.quantity-types.edit', $quantityType) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Edit Type
                </a>
                <button class="btn btn-outline-success" onclick="toggleStatus({{ $quantityType->id }})">
                    <i class="fas fa-toggle-{{ $quantityType->is_active ? 'on' : 'off' }} me-1"></i>
                    {{ $quantityType->is_active ? 'Deactivate' : 'Activate' }}
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="testConversion()">
                                <i class="fas fa-exchange-alt me-2"></i>Test Conversion
                            </a></li>
                        <li><a class="dropdown-item" href="#" onclick="validateQuantity()">
                                <i class="fas fa-check me-2"></i>Validate Quantity
                            </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportQuantityData()">
                                <i class="fas fa-download me-2"></i>Export Data
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#"
                                onclick="deleteQuantityType({{ $quantityType->id }})">
                                <i class="fas fa-trash me-2"></i>Delete Type
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Shipments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statistics['total_shipments']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ship fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    This Month</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statistics['monthly_usage']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Invoice Lines</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statistics['total_invoice_lines']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Quantity</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $statistics['total_quantity_handled'] ? $quantityType->formatValue($statistics['total_quantity_handled'], false) : '0' }}
                                    <small>{{ $quantityType->unit_symbol }}</small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Quantity Type Information -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                        <span
                            class="badge bg-{{ $quantityType->quantity_category == 'Weight' ? 'success' : ($quantityType->quantity_category == 'Volume' ? 'info' : ($quantityType->quantity_category == 'Count' ? 'warning' : 'secondary')) }}">
                            {{ $quantityType->category_display }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Quantity Code:</label>
                                    <div>{{ $quantityType->quantity_code }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Quantity Name:</label>
                                    <div>{{ $quantityType->quantity_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Category:</label>
                                    <div>{{ $quantityType->category_display }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Unit of Measure:</label>
                                    <div>{{ $quantityType->unit_display }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Measurement Types:</label>
                                    <div>{{ $quantityType->type_indicators }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Decimal Places:</label>
                                    <div>{{ $quantityType->decimal_places }}</div>
                                </div>
                                @if ($quantityType->reporting_category)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Reporting Category:</label>
                                        <div>{{ $quantityType->reporting_category }}</div>
                                    </div>
                                @endif
                                @if ($quantityType->customs_code)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Customs Code:</label>
                                        <div>{{ $quantityType->customs_code }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($quantityType->description)
                            <div class="mt-3">
                                <label class="form-label fw-bold">Description:</label>
                                <p>{{ $quantityType->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Conversion & Calculation -->
                @if ($quantityType->base_unit || $quantityType->calculation_method)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Conversion & Calculation</h6>
                        </div>
                        <div class="card-body">
                            @if ($quantityType->base_unit)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Base Unit:</label>
                                            <div>{{ $quantityType->base_unit }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Conversion Factor:</label>
                                            <div>{{ $quantityType->conversion_display }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($quantityType->calculation_method)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Calculation Method:</label>
                                    <div>{{ $quantityType->calculation_method }}</div>
                                </div>
                            @endif

                            @if ($quantityType->display_format)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Display Format:</label>
                                    <div><code>{{ $quantityType->display_format }}</code></div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Billing Configuration -->
                @if ($quantityType->is_billable)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Billing Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Billing Status:</label>
                                        <div>
                                            <span class="badge bg-success">Billable</span>
                                        </div>
                                    </div>
                                </div>
                                @if ($quantityType->billing_multiplier && $quantityType->billing_multiplier != 1)
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Billing Multiplier:</label>
                                            <div>{{ $quantityType->billing_multiplier }}x</div>
                                        </div>
                                    </div>
                                @endif
                                @if ($quantityType->minimum_chargeable)
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Minimum Chargeable:</label>
                                            <div>{{ $quantityType->formatValue($quantityType->minimum_chargeable) }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Rounding Method:</label>
                                        <div class="text-capitalize">{{ $quantityType->rounding_method ?? 'Nearest' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Allows Fractions:</label>
                                        <div>
                                            <span
                                                class="badge bg-{{ $quantityType->allows_fractions ? 'success' : 'secondary' }}">
                                                {{ $quantityType->allows_fractions ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Compatible Units for Conversion -->
                @if ($compatibleUnits->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Compatible Units (Same Base Unit)</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($compatibleUnits as $compatibleUnit)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-1">{{ $compatibleUnit->quantity_name }}</h6>
                                                <p class="card-text mb-2">
                                                    <small class="text-muted">{{ $compatibleUnit->unit_symbol }}</small>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small
                                                        class="text-muted">{{ $compatibleUnit->conversion_display }}</small>
                                                    <button class="btn btn-sm btn-outline-primary"
                                                        onclick="quickConvert({{ $quantityType->id }}, {{ $compatibleUnit->id }})">
                                                        Convert
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Usage -->
                @if ($recentShipments->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Shipments Using This Unit</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Shipment ID</th>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Route</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentShipments as $shipment)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('shipments.show', $shipment) }}"
                                                        class="text-decoration-none">
                                                        {{ $shipment->shipment_id }}
                                                    </a>
                                                </td>
                                                <td>{{ $shipment->company->company_name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($shipment->quantity)
                                                        {{ $quantityType->formatValue($shipment->quantity) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($shipment->originPort && $shipment->destinationPort)
                                                        {{ $shipment->originPort->port_code }} →
                                                        {{ $shipment->destinationPort->port_code }}
                                                    @else
                                                        TBD
                                                    @endif
                                                </td>
                                                <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Characteristics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Characteristics</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Behavior Flags:</label>
                            <div>
                                @if ($quantityType->allows_fractions)
                                    <span class="badge bg-success mb-1">Allows Fractions</span><br>
                                @endif
                                @if ($quantityType->requires_dimensions)
                                    <span class="badge bg-info mb-1">Requires Dimensions</span><br>
                                @endif
                                @if ($quantityType->auto_calculate)
                                    <span class="badge bg-warning text-dark mb-1">Auto Calculate</span><br>
                                @endif
                                @if ($quantityType->is_billable)
                                    <span class="badge bg-success mb-1">Billable</span><br>
                                @endif
                                @if ($quantityType->is_standard)
                                    <span class="badge bg-primary mb-1">Industry Standard</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applicable Cargo Types -->
                @if ($quantityType->applicable_cargo_types)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Applicable Cargo Types</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($quantityType->applicable_cargo_types as $cargoType)
                                <span class="badge bg-secondary me-1 mb-1">{{ $cargoType }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Industry Standards -->
                @if ($quantityType->industry_standards)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Industry Standards</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($quantityType->industry_standards as $standard)
                                <span class="badge bg-info me-1 mb-1">{{ $standard }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Validation Rules -->
                @if ($quantityType->common_ranges || $quantityType->validation_rules)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Validation & Ranges</h6>
                        </div>
                        <div class="card-body">
                            @if ($quantityType->common_ranges)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Common Range:</label>
                                    @if (isset($quantityType->common_ranges['min']) || isset($quantityType->common_ranges['max']))
                                        <div>
                                            @if (isset($quantityType->common_ranges['min']))
                                                Min: {{ $quantityType->formatValue($quantityType->common_ranges['min']) }}
                                            @endif
                                            @if (isset($quantityType->common_ranges['min']) && isset($quantityType->common_ranges['max']))
                                                —
                                            @endif
                                            @if (isset($quantityType->common_ranges['max']))
                                                Max: {{ $quantityType->formatValue($quantityType->common_ranges['max']) }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($statistics['average_quantity'])
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Average Usage:</label>
                                    <div>{{ $quantityType->formatValue($statistics['average_quantity']) }}</div>
                                </div>
                            @endif

                            @if ($quantityType->validation_rules)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Validation Rules:</label>
                                    <div>
                                        @foreach ($quantityType->validation_rules as $rule)
                                            <small class="d-block text-muted">• {{ $rule }}</small>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Additional Information -->
                @if ($quantityType->notes)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Additional Notes</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $quantityType->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Quick Tools -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Tools</h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('submenu.quantity-types.edit', $quantityType) }}"
                            class="btn btn-primary btn-sm btn-block mb-2">
                            <i class="fas fa-edit me-2"></i>Edit Quantity Type
                        </a>
                        <button class="btn btn-success btn-sm btn-block mb-2" onclick="testConversion()">
                            <i class="fas fa-exchange-alt me-2"></i>Test Conversion
                        </button>
                        <button class="btn btn-info btn-sm btn-block mb-2" onclick="validateQuantity()">
                            <i class="fas fa-check me-2"></i>Validate Quantity
                        </button>
                        <button class="btn btn-secondary btn-sm btn-block" onclick="calculateBillable()">
                            <i class="fas fa-calculator me-2"></i>Calculate Billing
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Conversion Modal -->
    <div class="modal fade" id="quickConversionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quick Conversion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quickConversionForm">
                        <div class="mb-3">
                            <label class="form-label">Value to Convert:</label>
                            <input type="number" id="quickConversionValue" class="form-control" step="0.001"
                                placeholder="Enter value">
                        </div>
                        <div id="quickConversionResult" class="alert d-none"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="performQuickConversion()">Convert</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quantity Validation Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validate Quantity Value</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="validationForm">
                        <div class="mb-3">
                            <label class="form-label">Quantity Value:</label>
                            <input type="number" id="validationValue" class="form-control" step="0.001"
                                placeholder="Enter value to validate">
                        </div>
                        <div id="validationResult" class="alert d-none"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="performValidation()">Validate</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let quickConversionFromId = null;
        let quickConversionToId = null;

        // Toggle quantity type status
        function toggleStatus(quantityTypeId) {
            if (confirm('Are you sure you want to change the status of this quantity type?')) {
                fetch(`/submenu/quantity-types/${quantityTypeId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error updating status');
                        }
                    });
            }
        }

        // Quick conversion between compatible units
        function quickConvert(fromId, toId) {
            quickConversionFromId = fromId;
            quickConversionToId = toId;

            const modal = new bootstrap.Modal(document.getElementById('quickConversionModal'));
            modal.show();
        }

        function performQuickConversion() {
            const value = document.getElementById('quickConversionValue').value;

            if (!value || !quickConversionFromId || !quickConversionToId) {
                alert('Please enter a value');
                return;
            }

            fetch('/submenu/quantity-types/convert', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        from_quantity_type_id: quickConversionFromId,
                        to_quantity_type_id: quickConversionToId,
                        value: parseFloat(value)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('quickConversionResult');
                    resultDiv.classList.remove('d-none');

                    if (data.success) {
                        resultDiv.className = 'alert alert-success';
                        resultDiv.innerHTML = `
                <strong>Conversion Result:</strong><br>
                ${data.original_value} converts to ${data.converted_value}
            `;
                    } else {
                        resultDiv.className = 'alert alert-danger';
                        resultDiv.innerHTML = `<strong>Error:</strong> ${data.message}`;
                    }
                });
        }

        // Test general conversion
        function testConversion() {
            window.location.href = '{{ route('submenu.quantity-types.index') }}';
            // This would open the conversion modal from the index page
        }

        // Validate quantity
        function validateQuantity() {
            const modal = new bootstrap.Modal(document.getElementById('validationModal'));
            modal.show();
        }

        function performValidation() {
            const value = document.getElementById('validationValue').value;

            if (!value) {
                alert('Please enter a value to validate');
                return;
            }

            fetch('/submenu/quantity-types/validate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity_type_id: {{ $quantityType->id }},
                        value: parseFloat(value)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('validationResult');
                    resultDiv.classList.remove('d-none');

                    if (data.valid) {
                        resultDiv.className = 'alert alert-success';
                        resultDiv.innerHTML = `
                <strong>✅ Valid Value</strong><br>
                Formatted: ${data.formatted_value}<br>
                ${data.billable_quantity ? 'Billable Quantity: ' + data.billable_quantity : ''}
            `;
                    } else {
                        resultDiv.className = 'alert alert-danger';
                        resultDiv.innerHTML = `
                <strong>❌ Invalid Value</strong><br>
                ${data.errors.join('<br>')}
            `;
                    }
                });
        }

        // Calculate billable quantity
        function calculateBillable() {
            const value = prompt('Enter quantity to calculate billing:');
            if (value) {
                fetch('/submenu/quantity-types/calculate-billable', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity_type_id: {{ $quantityType->id }},
                            actual_quantity: parseFloat(value)
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(
                            `Actual Quantity: ${data.actual_quantity}\nBillable Quantity: ${data.billable_quantity}\nBilling Method: ${data.rounding_method}`);
                    });
            }
        }

        // Delete quantity type
        function deleteQuantityType(quantityTypeId) {
            if (confirm('Are you sure you want to delete this quantity type? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/submenu/quantity-types/${quantityTypeId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Export quantity type data
        function exportQuantityData() {
            window.open(`/submenu/quantity-types/{{ $quantityType->id }}/export`, '_blank');
        }
    </script>
@endpush
