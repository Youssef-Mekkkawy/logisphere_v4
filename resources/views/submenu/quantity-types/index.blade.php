@extends('layouts.app')

@section('title', 'Quantity Types Management')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 text-gray-800 mb-0">
                    <i class="fas fa-balance-scale text-primary me-2"></i>Quantity Types Management
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('submenu.index') }}">Submenu</a></li>
                        <li class="breadcrumb-item active">Quantity Types</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('submenu.quantity-types.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Quantity Type
                </a>
                <button class="btn btn-outline-secondary dropdown-toggle ms-2" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportData('csv')">Export CSV</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportData('excel')">Export Excel</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportData('pdf')">Export PDF</a></li>
                </ul>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="fas fa-filter me-2"></i>Search & Filter Quantity Types
                    <button class="btn btn-sm btn-outline-secondary float-end" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filtersCollapse">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </h6>
            </div>
            <div class="collapse show" id="filtersCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('submenu.quantity-types.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Name, code, unit..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Category</label>
                            <select name="quantity_category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}"
                                        {{ request('quantity_category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Measurement Type</label>
                            <select name="measurement_type" class="form-select">
                                <option value="">All Types</option>
                                <option value="weight" {{ request('measurement_type') == 'weight' ? 'selected' : '' }}>
                                    Weight Based</option>
                                <option value="volume" {{ request('measurement_type') == 'volume' ? 'selected' : '' }}>
                                    Volume Based</option>
                                <option value="count" {{ request('measurement_type') == 'count' ? 'selected' : '' }}>Count
                                    Based</option>
                                <option value="dimension"
                                    {{ request('measurement_type') == 'dimension' ? 'selected' : '' }}>Dimension Based
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Standard Units</label>
                            <select name="is_standard" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('is_standard') === '1' ? 'selected' : '' }}>Standard Only
                                </option>
                                <option value="0" {{ request('is_standard') === '0' ? 'selected' : '' }}>Custom Only
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Billable</label>
                            <select name="is_billable" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('is_billable') === '1' ? 'selected' : '' }}>Billable
                                </option>
                                <option value="0" {{ request('is_billable') === '0' ? 'selected' : '' }}>Non-billable
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('submenu.quantity-types.index') }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Quantity Types</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $quantityTypes->total() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
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
                                    Standard Units</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $quantityTypes->where('is_standard', true)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-award fa-2x text-gray-300"></i>
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
                                    Billable Units</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $quantityTypes->where('is_billable', true)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                    Categories</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quantity Types Table -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Quantity Types Directory</h6>
                <div class="card-tools">
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleView('grid')">
                        <i class="fas fa-th-large"></i> Grid
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="toggleView('table')">
                        <i class="fas fa-table"></i> Table
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Table View -->
                <div id="tableView" class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Quantity Information</th>
                                <th>Unit & Symbol</th>
                                <th>Category & Type</th>
                                <th>Conversion</th>
                                <th>Billing</th>
                                <th>Status</th>
                                <th width="12%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quantityTypes as $quantityType)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input quantity-checkbox"
                                            value="{{ $quantityType->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="quantity-icon me-3">
                                                @if ($quantityType->is_standard)
                                                    <span class="badge bg-success text-white">⭐</span>
                                                @endif
                                                @if ($quantityType->is_billable)
                                                    <span class="badge bg-info">💰</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('submenu.quantity-types.show', $quantityType) }}"
                                                        class="text-decoration-none">
                                                        {{ $quantityType->quantity_name }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">{{ $quantityType->quantity_code }}</small>
                                                @if ($quantityType->description)
                                                    <br><small
                                                        class="text-muted">{{ Str::limit($quantityType->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $quantityType->unit_of_measure }}</strong>
                                            @if ($quantityType->unit_symbol)
                                                <br><span
                                                    class="badge bg-secondary">{{ $quantityType->unit_symbol }}</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            {{ $quantityType->decimal_places }} decimal places
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $quantityType->quantity_category }}</span>
                                        <br>
                                        <small
                                            class="text-muted mt-1 d-block">{{ $quantityType->type_indicators }}</small>
                                    </td>
                                    <td>
                                        @if ($quantityType->base_unit && $quantityType->conversion_factor)
                                            <small>{{ $quantityType->conversion_display }}</small>
                                        @else
                                            <small class="text-muted">Base unit</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $quantityType->is_billable ? 'success' : 'secondary' }}">
                                            {{ $quantityType->is_billable ? 'Billable' : 'Non-billable' }}
                                        </span>
                                        @if ($quantityType->is_billable && $quantityType->billing_multiplier && $quantityType->billing_multiplier != 1)
                                            <br><small class="text-muted">x{{ $quantityType->billing_multiplier }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $quantityType->is_active ? 'success' : 'secondary' }}">
                                            {{ $quantityType->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if ($quantityType->is_standard)
                                            <br><span class="badge bg-warning text-dark mt-1">Standard</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('submenu.quantity-types.show', $quantityType) }}"
                                                class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('submenu.quantity-types.edit', $quantityType) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit Type">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                onclick="toggleStatus({{ $quantityType->id }})" title="Toggle Status">
                                                <i
                                                    class="fas fa-toggle-{{ $quantityType->is_active ? 'on' : 'off' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                onclick="testConversion({{ $quantityType->id }})"
                                                title="Test Conversion">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deleteQuantityType({{ $quantityType->id }})"
                                                title="Delete Type">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-balance-scale fa-3x mb-3"></i>
                                            <h5>No quantity types found</h5>
                                            <p>Try adjusting your search criteria or <a
                                                    href="{{ route('submenu.quantity-types.create') }}">add a new quantity
                                                    type</a>.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($quantityTypes->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $quantityTypes->firstItem() ?? 0 }} to {{ $quantityTypes->lastItem() ?? 0 }} of
                            {{ $quantityTypes->total() }} quantity types
                        </div>
                        {{ $quantityTypes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Conversion Test Modal -->
    <div class="modal fade" id="conversionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Test Unit Conversion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="conversionForm">
                        <div class="mb-3">
                            <label class="form-label">From Quantity Type:</label>
                            <select id="fromQuantityType" class="form-select">
                                @foreach ($quantityTypes as $qt)
                                    <option value="{{ $qt->id }}">{{ $qt->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value:</label>
                            <input type="number" id="conversionValue" class="form-control" step="0.001"
                                placeholder="Enter value">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">To Quantity Type:</label>
                            <select id="toQuantityType" class="form-select">
                                @foreach ($quantityTypes as $qt)
                                    <option value="{{ $qt->id }}">{{ $qt->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="conversionResult" class="alert alert-info d-none"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="performConversion()">Convert</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.quantity-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

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
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating status');
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

        // Test conversion
        function testConversion(quantityTypeId) {
            document.getElementById('fromQuantityType').value = quantityTypeId;
            const modal = new bootstrap.Modal(document.getElementById('conversionModal'));
            modal.show();
        }

        // Perform conversion calculation
        function performConversion() {
            const fromId = document.getElementById('fromQuantityType').value;
            const toId = document.getElementById('toQuantityType').value;
            const value = document.getElementById('conversionValue').value;

            if (!fromId || !toId || !value) {
                alert('Please fill all fields');
                return;
            }

            fetch('/submenu/quantity-types/convert', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        from_quantity_type_id: fromId,
                        to_quantity_type_id: toId,
                        value: parseFloat(value)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('conversionResult');
                    resultDiv.classList.remove('d-none');

                    if (data.success) {
                        resultDiv.className = 'alert alert-success';
                        resultDiv.innerHTML = `
                <strong>Conversion Result:</strong><br>
                ${data.original_value} converts to ${data.converted_value}<br>
                <small class="text-muted">${data.from_type} → ${data.to_type}</small>
            `;
                    } else {
                        resultDiv.className = 'alert alert-danger';
                        resultDiv.innerHTML = `<strong>Error:</strong> ${data.message}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const resultDiv = document.getElementById('conversionResult');
                    resultDiv.classList.remove('d-none');
                    resultDiv.className = 'alert alert-danger';
                    resultDiv.innerHTML = '<strong>Error:</strong> Conversion failed';
                });
        }

        // Export functionality
        function exportData(format) {
            const searchParams = new URLSearchParams(window.location.search);
            searchParams.set('format', format);
            window.open(`/submenu/quantity-types/export?${searchParams.toString()}`, '_blank');
        }

        // View toggle functionality
        function toggleView(viewType) {
            // This would implement grid/table view switching
            console.log('Switching to', viewType, 'view');
        }

        // Auto-refresh compatible units when base unit changes
        document.addEventListener('DOMContentLoaded', function() {
            // Add any initialization code here
            console.log('Quantity Types management loaded');
        });
    </script>
@endpush
