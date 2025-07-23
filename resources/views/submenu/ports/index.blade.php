@extends('layouts.app')

@section('title', 'Ports Management')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 text-gray-800 mb-0">
                    <i class="fas fa-anchor text-primary me-2"></i>Ports Management
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('submenu.index') }}">Submenu</a></li>
                        <li class="breadcrumb-item active">Ports</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('submenu.ports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Port
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
                    <i class="fas fa-filter me-2"></i>Search & Filter Ports
                    <button class="btn btn-sm btn-outline-secondary float-end" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filtersCollapse">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </h6>
            </div>
            <div class="collapse show" id="filtersCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('submenu.ports.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Port name, code, city..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Country</label>
                            <select name="country" class="form-select">
                                <option value="">All Countries</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country }}"
                                        {{ request('country') == $country ? 'selected' : '' }}>
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Port Type</label>
                            <select name="port_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach ($portTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ request('port_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="operational_status" class="form-select">
                                <option value="">All Status</option>
                                @foreach ($operationalStatuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('operational_status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Active</label>
                            <select name="is_active" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('submenu.ports.index') }}" class="btn btn-outline-secondary btn-sm">
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
                                    Total Ports</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ports->total() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-anchor fa-2x text-gray-300"></i>
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
                                    Major Ports</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $ports->where('is_major_port', true)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star fa-2x text-gray-300"></i>
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
                                    Container Ports</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $ports->where('is_container_port', true)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                                    Countries</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countries->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-globe fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ports Table -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Ports Directory</h6>
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
                                <th>Port Information</th>
                                <th>Location</th>
                                <th>Type & Category</th>
                                <th>Specifications</th>
                                <th>Status</th>
                                <th width="12%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ports as $port)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input port-checkbox"
                                            value="{{ $port->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="port-icon me-3">
                                                @if ($port->is_major_port)
                                                    <span class="badge bg-warning text-dark">⭐</span>
                                                @endif
                                                @if ($port->is_container_port)
                                                    <span class="badge bg-info">📦</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('submenu.ports.show', $port) }}"
                                                        class="text-decoration-none">
                                                        {{ $port->port_name }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">{{ $port->port_code }}</small>
                                                @if ($port->port_authority)
                                                    <br><small class="text-muted">{{ $port->port_authority }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $port->city }}</strong>
                                            @if ($port->state_province)
                                                , {{ $port->state_province }}
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $port->country }}</small>
                                        @if ($port->latitude && $port->longitude)
                                            <br><small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ number_format($port->latitude, 4) }},
                                                {{ number_format($port->longitude, 4) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $port->port_type }}</span>
                                        <br>
                                        @if ($port->is_major_port)
                                            <span class="badge bg-warning text-dark mt-1">Major Port</span>
                                        @endif
                                        @if ($port->is_container_port)
                                            <span class="badge bg-info mt-1">Container</span>
                                        @endif
                                        @if ($port->is_bulk_port)
                                            <span class="badge bg-success mt-1">Bulk</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($port->berth_count)
                                            <small><i class="fas fa-ship"></i> {{ $port->berth_count }} Berths</small><br>
                                        @endif
                                        @if ($port->max_draft_meters)
                                            <small><i class="fas fa-water"></i> {{ $port->max_draft_meters }}m
                                                Draft</small><br>
                                        @endif
                                        @if ($port->crane_capacity)
                                            <small><i class="fas fa-truck-loading"></i> {{ $port->crane_capacity }}t
                                                Crane</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $port->operational_status == 'Fully Operational' ? 'success' : ($port->operational_status == 'Limited Operations' ? 'warning' : 'danger') }}">
                                            {{ $port->operational_status }}
                                        </span>
                                        <br>
                                        <span class="badge bg-{{ $port->is_active ? 'success' : 'secondary' }} mt-1">
                                            {{ $port->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if ($port->security_level)
                                            <br><small class="text-muted">{{ $port->security_level }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('submenu.ports.show', $port) }}"
                                                class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('submenu.ports.edit', $port) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit Port">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                onclick="toggleStatus({{ $port->id }})" title="Toggle Status">
                                                <i class="fas fa-toggle-{{ $port->is_active ? 'on' : 'off' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deletePort({{ $port->id }})" title="Delete Port">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-anchor fa-3x mb-3"></i>
                                            <h5>No ports found</h5>
                                            <p>Try adjusting your search criteria or <a
                                                    href="{{ route('submenu.ports.create') }}">add a new port</a>.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($ports->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $ports->firstItem() ?? 0 }} to {{ $ports->lastItem() ?? 0 }} of
                            {{ $ports->total() }} ports
                        </div>
                        {{ $ports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.port-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Toggle port status
        function toggleStatus(portId) {
            if (confirm('Are you sure you want to change the status of this port?')) {
                fetch(`/submenu/ports/${portId}/toggle-status`, {
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

        // Delete port
        function deletePort(portId) {
            if (confirm('Are you sure you want to delete this port? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/submenu/ports/${portId}`;

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

        // Export functionality
        function exportData(format) {
            const searchParams = new URLSearchParams(window.location.search);
            searchParams.set('format', format);
            window.open(`/submenu/ports/export?${searchParams.toString()}`, '_blank');
        }

        // View toggle functionality
        function toggleView(viewType) {
            // This would implement grid/table view switching
            console.log('Switching to', viewType, 'view');
        }

        // Auto-refresh operational status every 5 minutes
        setInterval(function() {
            const portIds = [];
            document.querySelectorAll('.port-checkbox').forEach(checkbox => {
                portIds.push(checkbox.value);
            });

            if (portIds.length > 0) {
                // This would check operational status of all visible ports
                console.log('Checking operational status for ports:', portIds);
            }
        }, 300000);
    </script>
@endpush
