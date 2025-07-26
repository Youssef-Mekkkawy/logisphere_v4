@extends('layouts.app')

@section('title', 'Quantity Types')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">📏 Quantity Types
                    Management</h1>
                <p style="color: #64748b;">Manage measurement units and quantity types for logistics operations</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" class="btn btn-outline" onclick="debugUnitConverter()" id="unitConverterBtn">
                    🔄 Unit Converter
                </button>
                {{-- <button type="button" class="btn btn-outline" onclick="exportQuantityTypes()">
                    📊 Export
                </button> --}}
                <a href="{{ route('logistics.quantity-types.create') }}" class="btn btn-primary">+ Add New Quantity Type</a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6, #1e40af); color: white;">
            <div class="stat-value" id="total-quantity-types">{{ $quantityTypes->total() }}</div>
            <div class="stat-label">Total Quantity Types</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #059669, #047857); color: white;">
            <div class="stat-value" id="active-quantity-types">{{ $quantityTypes->where('is_active', true)->count() }}</div>
            <div class="stat-label">Active Types</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #7c3aed, #6b21a8); color: white;">
            <div class="stat-value" id="standard-quantity-types">{{ $quantityTypes->where('is_standard', true)->count() }}
            </div>
            <div class="stat-label">Standard Types</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
            <div class="stat-value" id="categories-count">{{ $categories->count() }}</div>
            <div class="stat-label">Categories</div>
        </div>
    </div>

    <!-- Filters with Real-time Search -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>🔍 Filters</h3>
            <div>
                <button type="button" class="btn btn-outline btn-sm" onclick="clearAllFilters()">Clear All</button>
                <button type="button" class="btn btn-outline btn-sm" onclick="toggleAdvancedFilters()">Advanced</button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" id="filterForm"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" id="searchInput" class="form-input" value="{{ request('search') }}"
                        placeholder="Name, code, unit..." onkeyup="debounceSearch()">
                    <div class="search-suggestions" id="searchSuggestions"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="quantity_category" class="form-input" onchange="handleCategoryFilter(this)">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}"
                                {{ request('quantity_category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Measurement Type</label>
                    <select name="measurement_type" class="form-input" onchange="handleMeasurementFilter(this)">
                        <option value="">All Types</option>
                        <option value="weight" {{ request('measurement_type') == 'weight' ? 'selected' : '' }}>⚖️ Weight
                            Based</option>
                        <option value="volume" {{ request('measurement_type') == 'volume' ? 'selected' : '' }}>📏 Volume
                            Based</option>
                        <option value="count" {{ request('measurement_type') == 'count' ? 'selected' : '' }}>🔢 Count
                            Based</option>
                        <option value="dimension" {{ request('measurement_type') == 'dimension' ? 'selected' : '' }}>📐
                            Dimension Based</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Standard Units</label>
                    <select name="is_standard" class="form-input">
                        <option value="">All</option>
                        <option value="1" {{ request('is_standard') === '1' ? 'selected' : '' }}>Standard Only
                        </option>
                        <option value="0" {{ request('is_standard') === '0' ? 'selected' : '' }}>Custom Only</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-input">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Billable</label>
                    <select name="is_billable" class="form-input">
                        <option value="">All</option>
                        <option value="1" {{ request('is_billable') === '1' ? 'selected' : '' }}>Billable Only
                        </option>
                        <option value="0" {{ request('is_billable') === '0' ? 'selected' : '' }}>Non-Billable</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('logistics.quantity-types.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>

            <!-- Advanced Filters (Hidden by default) -->
            <div id="advancedFilters" class="advanced-filters"
                style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Conversion Factor Range</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="number" name="conversion_min" class="form-input" placeholder="Min"
                                step="0.001">
                            <input type="number" name="conversion_max" class="form-input" placeholder="Max"
                                step="0.001">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Decimal Places</label>
                        <select name="decimal_places" class="form-input">
                            <option value="">Any</option>
                            <option value="0">0 (Whole numbers)</option>
                            <option value="2">2 (Standard)</option>
                            <option value="3">3 (Precise)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Allows Fractions</label>
                        <select name="allows_fractions" class="form-input">
                            <option value="">Any</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="bulk-actions-bar" style="display: none; margin-bottom: 1rem;">
        <div
            style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <div>
                <span id="selectedCount">0</span> quantity types selected
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-success btn-sm bulk-action-btn" onclick="bulkActivate()" disabled>
                    ✅ Activate
                </button>
                <button type="button" class="btn btn-warning btn-sm bulk-action-btn" onclick="bulkDeactivate()"
                    disabled>
                    ⏸️ Deactivate
                </button>
                <button type="button" class="btn btn-info btn-sm bulk-action-btn" onclick="bulkExport()" disabled>
                    📊 Export Selected
                </button>
                <button type="button" class="btn btn-outline btn-sm" onclick="clearBulkSelection()">
                    ❌ Clear Selection
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Quantity Types Table -->
    <div class="card" id="quantityTypesTable">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>📊 Quantity Types</h3>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="table-controls">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                        Select All
                    </label>
                </div>
                <div class="view-controls">
                    <button type="button" class="btn btn-outline btn-sm" onclick="toggleTableView('grid')"
                        title="Grid View">
                        ⊞
                    </button>
                    <button type="button" class="btn btn-outline btn-sm active" onclick="toggleTableView('table')"
                        title="Table View">
                        ☰
                    </button>
                </div>
                <div class="sort-controls">
                    <select onchange="handleSort(this)" class="form-input" style="width: auto; font-size: 0.875rem;">
                        <option value="">Sort by...</option>
                        <option value="sort_order,asc">Sort Order ↑</option>
                        <option value="quantity_name,asc">Name A-Z</option>
                        <option value="quantity_name,desc">Name Z-A</option>
                        <option value="quantity_category,asc">Category ↑</option>
                        <option value="created_at,desc">Newest First</option>
                        <option value="usage_count,desc">Most Used</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="data-table" id="mainTable">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="headerSelectAll" onchange="toggleSelectAll(this)">
                            </th>
                            <th onclick="sortTable('quantity_code')" style="cursor: pointer;">
                                Code <span class="sort-indicator">⇅</span>
                            </th>
                            <th onclick="sortTable('quantity_name')" style="cursor: pointer;">
                                Name <span class="sort-indicator">⇅</span>
                            </th>
                            <th onclick="sortTable('quantity_category')" style="cursor: pointer;">
                                Category <span class="sort-indicator">⇅</span>
                            </th>
                            <th>Unit</th>
                            <th>Type Indicators</th>
                            <th onclick="sortTable('conversion_factor')" style="cursor: pointer;">
                                Conversion <span class="sort-indicator">⇅</span>
                            </th>
                            <th>Standard</th>
                            <th>Status</th>
                            <th onclick="sortTable('usage_count')" style="cursor: pointer;">
                                Usage <span class="sort-indicator">⇅</span>
                            </th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quantityTypes as $quantityType)
                            <tr data-quantity-type-id="{{ $quantityType->id }}" class="table-row">
                                <td>
                                    <input type="checkbox" class="bulk-select-checkbox" value="{{ $quantityType->id }}"
                                        onchange="updateBulkActions()">
                                </td>
                                <td>
                                    <strong
                                        style="font-family: monospace; font-size: 1.1rem;">{{ $quantityType->quantity_code }}</strong>
                                    <div class="quick-copy"
                                        onclick="copyToClipboard('{{ $quantityType->quantity_code }}')"
                                        title="Click to copy"
                                        style="cursor: pointer; color: #64748b; font-size: 0.75rem;">
                                        📋 Copy
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $quantityType->quantity_name }}</div>
                                    @if ($quantityType->description)
                                        <div
                                            style="font-size: 0.75rem; color: #64748b; max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                            {{ Str::limit($quantityType->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="category-badge category-{{ strtolower(str_replace(' ', '-', $quantityType->quantity_category)) }}">
                                        {{ $quantityType->category_display }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong style="font-size: 1.1rem;">{{ $quantityType->unit_symbol }}</strong>
                                        <button type="button" class="btn-link btn-sm"
                                            onclick="showUnitDetails({{ $quantityType->id }})"
                                            title="Show unit details">ℹ️</button>
                                    </div>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $quantityType->unit_of_measure }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                        @foreach ($quantityType->type_indicators as $indicator)
                                            <span class="indicator-badge indicator-{{ strtolower($indicator) }}"
                                                title="{{ $indicator }}-based measurement">{{ substr($indicator, 0, 1) }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">
                                        @if ($quantityType->conversion_factor != 1.0)
                                            <span
                                                style="color: #059669; font-weight: 600;">{{ $quantityType->conversion_factor }}x</span>
                                            <button type="button" class="btn-link btn-sm"
                                                onclick="showConversionCalculator({{ $quantityType->id }})"
                                                title="Convert values">🔄</button>
                                        @else
                                            <span style="color: #64748b;">1:1</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.75rem; color: #64748b;">
                                        {{ $quantityType->base_unit ?? 'Base' }}</div>
                                </td>
                                <td>
                                    @if ($quantityType->is_standard)
                                        <span class="status-badge status-standard" title="Industry standard unit">⭐
                                            Standard</span>
                                    @else
                                        <span class="status-badge status-custom" title="Custom unit">🔧 Custom</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        @if ($quantityType->is_active)
                                            <span class="status-badge status-active">✅ Active</span>
                                        @else
                                            <span class="status-badge status-inactive">⏸️ Inactive</span>
                                        @endif
                                        <button type="button" class="btn-link btn-sm"
                                            onclick="quickToggleStatus({{ $quantityType->id }}, {{ $quantityType->is_active ? 'true' : 'false' }})"
                                            title="Toggle status">
                                            {{ $quantityType->is_active ? '⏸️' : '▶️' }}
                                        </button>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <div style="font-weight: 600; font-size: 1.1rem;">{{ $quantityType->usage_count }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #64748b;">uses</div>
                                    @if ($quantityType->usage_count > 0)
                                        <button type="button" class="btn-link btn-sm"
                                            onclick="showUsageDetails({{ $quantityType->id }})"
                                            title="View usage details">📊</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <a href="{{ route('logistics.quantity-types.show', $quantityType) }}"
                                            class="btn btn-outline btn-sm" title="View Details">👁️</a>
                                        <a href="{{ route('logistics.quantity-types.edit', $quantityType) }}"
                                            class="btn btn-success btn-sm" title="Edit">✏️</a>
                                        <button type="button" class="btn btn-info btn-sm"
                                            onclick="quickCopy({{ $quantityType->id }})" title="Duplicate">📄</button>
                                        @if (auth()->user()->isAdmin() && $quantityType->usage_count == 0)
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $quantityType->id }}, '{{ $quantityType->quantity_name }}')"
                                                title="Delete">🗑️</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" style="text-align: center; padding: 3rem; color: #64748b;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">📏</div>
                                    <div style="font-size: 1.2rem; margin-bottom: 0.5rem;">No quantity types found</div>
                                    <div>
                                        <a href="{{ route('logistics.quantity-types.create') }}"
                                            style="color: var(--primary-color);">
                                            Create your first quantity type
                                        </a>
                                        or adjust your filters above
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination with Info -->
            @if (isset($quantityTypes) && $quantityTypes->hasPages())
                <div
                    style="display: flex; justify-content: between; align-items: center; padding: 1.5rem; border-top: 1px solid #e5e7eb;">
                    <div style="color: #64748b; font-size: 0.875rem;">
                        Showing {{ $quantityTypes->firstItem() }} to {{ $quantityTypes->lastItem() }}
                        of {{ $quantityTypes->total() }} quantity types
                    </div>
                    <div>
                        {{ $quantityTypes->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Unit Conversion Modal -->
    <div class="modal" id="conversionModal" style="display: none; z-index: 10000;">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h5 class="modal-title">🔄 Unit Converter (Debug Version)</h5>
                <button type="button" class="close" onclick="debugCloseModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="padding: 1rem; background: #f0f9ff; border-radius: 0.5rem; margin-bottom: 1rem;">
                    <strong>Debug Info:</strong>
                    <div id="debugInfo">Modal loaded successfully</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 1rem; align-items: end;">
                    <div class="form-group">
                        <label class="form-label">From</label>
                        <select id="conversion-from-type" class="form-input" onchange="debugLog('From type changed')">
                            <option value="">Select quantity type...</option>
                            @foreach ($quantityTypes as $qt)
                                @if ($qt->is_active)
                                    <option value="{{ $qt->id }}" data-symbol="{{ $qt->unit_symbol }}"
                                        data-conversion="{{ $qt->conversion_factor }}" data-base="{{ $qt->base_unit }}">
                                        {{ $qt->quantity_code }} - {{ $qt->quantity_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <input type="number" id="conversion-value" class="form-input" placeholder="Enter value..."
                            style="margin-top: 0.5rem;" step="0.000001" onchange="debugLog('Value changed')">
                    </div>
                    <div style="font-size: 1.5rem; color: #3b82f6;">→</div>
                    <div class="form-group">
                        <label class="form-label">To</label>
                        <select id="conversion-to-type" class="form-input" onchange="debugLog('To type changed')">
                            <option value="">Select quantity type...</option>
                            @foreach ($quantityTypes as $qt)
                                @if ($qt->is_active)
                                    <option value="{{ $qt->id }}" data-symbol="{{ $qt->unit_symbol }}"
                                        data-conversion="{{ $qt->conversion_factor }}" data-base="{{ $qt->base_unit }}">
                                        {{ $qt->quantity_code }} - {{ $qt->quantity_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div id="conversion-result"
                            style="margin-top: 0.5rem; min-height: 2.5rem; padding: 0.5rem; background: #f8fafc; border-radius: 0.375rem; font-weight: 600;">
                            Enter values to see conversion
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <h6>Quick Conversions</h6>
                    <div id="quickConversions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <button type="button" class="btn btn-outline btn-sm"
                            onclick="debugQuickConvert('TEU', 'FEU')">TEU to FEU</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="debugQuickConvert('KG', 'MT')">KG
                            to MT</button>
                        <button type="button" class="btn btn-outline btn-sm"
                            onclick="debugQuickConvert('CBM', 'CFT')">CBM to CFT</button>
                        <button type="button" class="btn btn-outline btn-sm"
                            onclick="debugLog('Quick conversion clicked')">Debug Log</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Details Modal -->
    <div class="modal fade" id="unitDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📏 Unit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="unitDetailsContent">
                    <!-- Content loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Details Modal -->
    <div class="modal fade" id="usageDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📊 Usage Statistics</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="usageDetailsContent">
                    <!-- Content loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <!-- Enhanced JavaScript - FIXED VERSION -->
    <script>
        // Global variables
        let searchTimeout;
        let selectedQuantityTypes = new Set();

        // Page initialization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Quantity Types page loaded');
            initializeQuantityTypesPage();
            setupEventListeners();
            updateBulkActions();
        });

        function initializeQuantityTypesPage() {
            console.log('🔧 Initializing Quantity Types page');

            // Initialize tooltips
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(el => {
                el.style.cursor = 'help';
            });

            // Setup conversion modal event listeners
            setupConversionModal();

            // Load saved preferences (now defined)
            loadTablePreferences();
        }

        function setupEventListeners() {
            console.log('📡 Setting up event listeners');

            // Real-time search
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', debounceSearch);
            }

            // Filter changes
            document.querySelectorAll('select[name]').forEach(select => {
                select.addEventListener('change', function() {
                    if (this.name !== 'sort') {
                        handleFilterChange();
                    }
                });
            });

            // Conversion modal inputs
            const fromTypeSelect = document.getElementById('conversion-from-type');
            const toTypeSelect = document.getElementById('conversion-to-type');
            const valueInput = document.getElementById('conversion-value');

            if (fromTypeSelect) fromTypeSelect.addEventListener('change', performConversion);
            if (toTypeSelect) toTypeSelect.addEventListener('change', performConversion);
            if (valueInput) valueInput.addEventListener('input', debounceConversion);
        }

        // Modal functions - FIXED VERSION
        function openUnitConverter() {
            console.log('🔄 Opening unit converter');
            openModal('conversionModal');
        }

        function showUnitDetails(quantityTypeId) {
            console.log('ℹ️ Showing unit details for:', quantityTypeId);
            // Load unit details content
            document.getElementById('unitDetailsContent').innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">📏</div>
                    <div>Loading unit details for ID: ${quantityTypeId}...</div>
                    <div style="margin-top: 1rem; color: #64748b; font-size: 0.875rem;">
                        This would typically load detailed information about the quantity type.
                    </div>
                </div>
            `;
            openModal('unitDetailsModal');
        }

        function debugUnitConverter() {
            console.log('🛠️ Running debugUnitConverter()');
            performConversion(); // optional
        }

        function showUsageDetails(quantityTypeId) {
            console.log('📊 Showing usage details for:', quantityTypeId);
            // Load usage statistics content
            document.getElementById('usageDetailsContent').innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">📊</div>
                    <div>Loading usage statistics for ID: ${quantityTypeId}...</div>
                    <div style="margin-top: 1rem; color: #64748b; font-size: 0.875rem;">
                        This would show shipment usage, trends, and analytics.
                    </div>
                </div>
            `;
            openModal('usageDetailsModal');
        }

        function showConversionCalculator(quantityTypeId) {
            console.log('🔄 Opening conversion calculator for:', quantityTypeId);
            const fromSelect = document.getElementById('conversion-from-type');
            if (fromSelect) {
                fromSelect.value = quantityTypeId;
                performConversion(); // Trigger conversion if both fields are filled
            }
            openModal('conversionModal');
        }

        // Modal utility functions
        function openModal(modalId) {
            console.log('📂 Opening modal:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                // Focus first input if available
                const firstInput = modal.querySelector('input, select');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            } else {
                console.error('❌ Modal not found:', modalId);
            }
        }

        function closeModal(modalId) {
            console.log('📂 Closing modal:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function closeConversionModal() {
            closeModal('conversionModal');
            // Reset form
            const fromSelect = document.getElementById('conversion-from-type');
            const toSelect = document.getElementById('conversion-to-type');
            const valueInput = document.getElementById('conversion-value');
            const resultDiv = document.getElementById('conversion-result');

            if (fromSelect) fromSelect.value = '';
            if (toSelect) toSelect.value = '';
            if (valueInput) valueInput.value = '';
            if (resultDiv) resultDiv.innerHTML = 'Enter values to see conversion';
        }

        // Conversion functionality - FIXED VERSION
        function setupConversionModal() {
            console.log('🔧 Setting up conversion modal');
            // Event listeners are now set up in setupEventListeners()
        }

        let conversionTimeout;

        function debounceConversion() {
            clearTimeout(conversionTimeout);
            conversionTimeout = setTimeout(performConversion, 300);
        }

        function performConversion() {
            const fromSelect = document.getElementById('conversion-from-type');
            const toSelect = document.getElementById('conversion-to-type');
            const valueInput = document.getElementById('conversion-value');
            const resultDiv = document.getElementById('conversion-result');

            if (!fromSelect || !toSelect || !valueInput || !resultDiv) {
                console.error('❌ Conversion elements not found');
                return;
            }

            const fromTypeId = fromSelect.value;
            const toTypeId = toSelect.value;
            const value = parseFloat(valueInput.value);

            if (!fromTypeId || !toTypeId || isNaN(value) || value <= 0) {
                resultDiv.innerHTML = '<span style="color: #64748b;">Enter values to see conversion</span>';
                return;
            }

            // Get conversion data from options
            const fromOption = fromSelect.selectedOptions[0];
            const toOption = toSelect.selectedOptions[0];

            if (!fromOption || !toOption) {
                resultDiv.innerHTML = '<span style="color: #dc2626;">Invalid selection</span>';
                return;
            }

            const fromConversion = parseFloat(fromOption.dataset.conversion || 1);
            const toConversion = parseFloat(toOption.dataset.conversion || 1);
            const fromBase = fromOption.dataset.base;
            const toBase = toOption.dataset.base;
            const fromSymbol = fromOption.dataset.symbol;
            const toSymbol = toOption.dataset.symbol;

            // Check if same base unit (allow conversion between same types)
            if (fromBase && toBase && fromBase !== toBase) {
                resultDiv.innerHTML =
                    '<span style="color: #dc2626;">Cannot convert between different measurement types</span>';
                return;
            }

            try {
                // Convert to base unit, then to target unit
                const baseValue = value * fromConversion;
                const convertedValue = baseValue / toConversion;
                const roundedValue = Math.round(convertedValue * 1000000) / 1000000; // 6 decimal places

                resultDiv.innerHTML = `
                    <div style="color: #059669; font-size: 1.2rem; font-weight: bold;">${roundedValue} ${toSymbol}</div>
                    <div style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">
                        ${value} ${fromSymbol} = ${roundedValue} ${toSymbol}
                    </div>
                `;

                console.log(`✅ Conversion: ${value} ${fromSymbol} = ${roundedValue} ${toSymbol}`);
            } catch (error) {
                console.error('❌ Conversion error:', error);
                resultDiv.innerHTML = '<span style="color: #dc2626;">Conversion failed</span>';
            }
        }

        function quickConvert(fromCode, toCode) {
            console.log(`🚀 Quick convert: ${fromCode} to ${toCode}`);

            // Set the dropdowns
            const fromSelect = document.getElementById('conversion-from-type');
            const toSelect = document.getElementById('conversion-to-type');

            if (!fromSelect || !toSelect) {
                console.error('❌ Conversion selects not found');
                return;
            }

            // Find options by code
            Array.from(fromSelect.options).forEach(option => {
                if (option.text.includes(fromCode)) {
                    fromSelect.value = option.value;
                }
            });

            Array.from(toSelect.options).forEach(option => {
                if (option.text.includes(toCode)) {
                    toSelect.value = option.value;
                }
            });

            // Set a default value
            const valueInput = document.getElementById('conversion-value');
            if (valueInput) {
                valueInput.value = '1';
            }

            // Perform conversion
            performConversion();
        }

        // Search functionality
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300);
        }

        function performSearch() {
            const searchInput = document.getElementById('searchInput');
            if (!searchInput) return;

            const searchValue = searchInput.value;
            const currentUrl = new URL(window.location);

            if (searchValue) {
                currentUrl.searchParams.set('search', searchValue);
            } else {
                currentUrl.searchParams.delete('search');
            }

            // Update URL without reload for better UX
            window.history.pushState({}, '', currentUrl);

            // Show search suggestions
            if (searchValue.length > 1) {
                showSearchSuggestions(searchValue);
            } else {
                hideSearchSuggestions();
            }
        }

        function showSearchSuggestions(query) {
            const suggestionsDiv = document.getElementById('searchSuggestions');
            if (suggestionsDiv) {
                suggestionsDiv.innerHTML =
                    '<div style="padding: 0.5rem; color: #64748b; font-size: 0.875rem;">Searching...</div>';
                suggestionsDiv.style.display = 'block';
            }
        }

        function hideSearchSuggestions() {
            const suggestionsDiv = document.getElementById('searchSuggestions');
            if (suggestionsDiv) {
                suggestionsDiv.style.display = 'none';
            }
        }

        // Bulk selection functionality
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.bulk-select-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
                if (checkbox.checked) {
                    selectedQuantityTypes.add(cb.value);
                } else {
                    selectedQuantityTypes.delete(cb.value);
                }
            });
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.bulk-select-checkbox:checked');
            const bulkBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const bulkButtons = document.querySelectorAll('.bulk-action-btn');

            if (bulkBar && selectedCount) {
                if (checkboxes.length > 0) {
                    bulkBar.style.display = 'block';
                    selectedCount.textContent = checkboxes.length;
                    bulkButtons.forEach(btn => btn.disabled = false);
                } else {
                    bulkBar.style.display = 'none';
                    bulkButtons.forEach(btn => btn.disabled = true);
                }
            }

            // Update header checkbox state
            const allCheckboxes = document.querySelectorAll('.bulk-select-checkbox');
            const headerCheckbox = document.getElementById('headerSelectAll');
            if (headerCheckbox && allCheckboxes.length > 0) {
                headerCheckbox.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
                headerCheckbox.checked = checkboxes.length === allCheckboxes.length;
            }
        }

        function clearBulkSelection() {
            const checkboxes = document.querySelectorAll('.bulk-select-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            selectedQuantityTypes.clear();
            updateBulkActions();
        }

        // Status toggle functionality
        async function quickToggleStatus(quantityTypeId, currentStatus) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                const response = await fetch(`/logistics.quantity-types/${quantityTypeId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update the status badge and button
                    const row = document.querySelector(`tr[data-quantity-type-id="${quantityTypeId}"]`);
                    if (row) {
                        const statusBadge = row.querySelector('.status-badge');
                        const toggleButton = row.querySelector(`[onclick*="quickToggleStatus(${quantityTypeId}"]`);

                        if (statusBadge && toggleButton) {
                            if (currentStatus) {
                                statusBadge.className = 'status-badge status-inactive';
                                statusBadge.innerHTML = '⏸️ Inactive';
                                toggleButton.innerHTML = '▶️';
                                toggleButton.setAttribute('onclick', `quickToggleStatus(${quantityTypeId}, false)`);
                                toggleButton.title = 'Activate';
                            } else {
                                statusBadge.className = 'status-badge status-active';
                                statusBadge.innerHTML = '✅ Active';
                                toggleButton.innerHTML = '⏸️';
                                toggleButton.setAttribute('onclick', `quickToggleStatus(${quantityTypeId}, true)`);
                                toggleButton.title = 'Deactivate';
                            }
                        }
                    }

                    showNotification('Status updated successfully', 'success');
                } else {
                    throw new Error(data.message || 'Failed to toggle status');
                }
            } catch (error) {
                console.error('Toggle status error:', error);
                showNotification('Failed to update status: ' + error.message, 'error');
            }
        }

        // Utility functions
        function copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    showNotification(`Copied "${text}" to clipboard`, 'success');
                }).catch(() => {
                    showNotification('Failed to copy to clipboard', 'error');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showNotification(`Copied "${text}" to clipboard`, 'success');
                } catch (err) {
                    showNotification('Failed to copy to clipboard', 'error');
                }
                document.body.removeChild(textArea);
            }
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} notification-toast`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                padding: 1rem;
                border-radius: 0.5rem;
                animation: slideInRight 0.3s ease-out;
                background: ${type === 'success' ? '#059669' : type === 'error' ? '#dc2626' : '#3b82f6'};
                color: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;

            notification.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>${message}</span>
                    <button onclick="this.parentNode.parentNode.remove()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: white; margin-left: 1rem;">×</button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Advanced filter functions
        function toggleAdvancedFilters() {
            const advancedDiv = document.getElementById('advancedFilters');
            if (advancedDiv) {
                const isVisible = advancedDiv.style.display !== 'none';
                advancedDiv.style.display = isVisible ? 'none' : 'block';
            }
        }

        function clearAllFilters() {
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.reset();
            }
            window.location.href = window.location.pathname;
        }

        // Export functionality
        function exportQuantityTypes() {
            const selectedIds = Array.from(document.querySelectorAll('.bulk-select-checkbox:checked')).map(cb => cb.value);
            let url = '/logistics.quantity-types/export';

            if (selectedIds.length > 0) {
                url += '?ids=' + selectedIds.join(',');
            }

            window.open(url, '_blank');
        }

        // Table view functions
        function toggleTableView(view) {
            const buttons = document.querySelectorAll('.view-controls button');
            buttons.forEach(btn => btn.classList.remove('active'));
            if (event && event.target) {
                event.target.classList.add('active');
            }

            if (view === 'grid') {
                showNotification('Grid view coming soon!', 'info');
            }
        }

        // Additional utility functions
        function confirmDelete(quantityTypeId, quantityTypeName) {
            if (confirm(`Are you sure you want to delete "${quantityTypeName}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/logistics.quantity-types/${quantityTypeId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function quickCopy(quantityTypeId) {
            showNotification('Copy functionality coming soon!', 'info');
        }

        function handleCategoryFilter(select) {
            const form = select.closest('form');
            if (form) {
                form.submit();
            }
        }

        function handleMeasurementFilter(select) {
            const form = select.closest('form');
            if (form) {
                form.submit();
            }
        }

        function handleSort(select) {
            if (select.value) {
                const [field, direction] = select.value.split(',');
                const url = new URL(window.location);
                url.searchParams.set('sort', field);
                url.searchParams.set('direction', direction);
                window.location.href = url.toString();
            }
        }

        function bulkActivate() {
            const selectedIds = Array.from(document.querySelectorAll('.bulk-select-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length > 0) {
                showNotification(`Activating ${selectedIds.length} quantity types...`, 'info');
                // TODO: Implement bulk activation
            }
        }

        function bulkDeactivate() {
            const selectedIds = Array.from(document.querySelectorAll('.bulk-select-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length > 0) {
                showNotification(`Deactivating ${selectedIds.length} quantity types...`, 'info');
                // TODO: Implement bulk deactivation
            }
        }

        function bulkExport() {
            const selectedIds = Array.from(document.querySelectorAll('.bulk-select-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length > 0) {
                exportQuantityTypes();
            }
        }

        // MISSING FUNCTIONS - NOW DEFINED
        function loadTablePreferences() {
            console.log('📋 Loading table preferences');
            // Load user preferences for table view from localStorage if available
            try {
                const preferences = localStorage.getItem('quantityTypesTablePreferences');
                if (preferences) {
                    const prefs = JSON.parse(preferences);
                    console.log('✅ Loaded preferences:', prefs);

                    // Apply saved preferences
                    if (prefs.view && prefs.view === 'grid') {
                        // Apply grid view if saved
                        const gridButton = document.querySelector('.view-controls button[onclick*="grid"]');
                        if (gridButton) {
                            gridButton.classList.add('active');
                            document.querySelector('.view-controls button[onclick*="table"]')?.classList.remove('active');
                        }
                    }

                    if (prefs.sortBy) {
                        const sortSelect = document.querySelector('.sort-controls select');
                        if (sortSelect) {
                            sortSelect.value = prefs.sortBy;
                        }
                    }
                }
            } catch (error) {
                console.log('📋 No saved preferences or error loading:', error.message);
            }
        }

        function handleFilterChange() {
            console.log('🔄 Filter changed');
            // Auto-submit form when filters change
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.submit();
            }
        }

        function sortTable(column) {
            console.log('📊 Sorting by:', column);
            // Handle table sorting
            const url = new URL(window.location);
            const currentSort = url.searchParams.get('sort');
            const currentDirection = url.searchParams.get('direction');

            let newDirection = 'asc';
            if (currentSort === column && currentDirection === 'asc') {
                newDirection = 'desc';
            }

            url.searchParams.set('sort', column);
            url.searchParams.set('direction', newDirection);
            window.location.href = url.toString();
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Handle escape key for modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal[style*="flex"]');
                openModals.forEach(modal => {
                    modal.style.display = 'none';
                });
            }
        });
        // Debugging helpers (to prevent JS errors)
        function debugQuickConvert(from, to) {
            console.log(`🧪 debugQuickConvert('${from}', '${to}')`);
            quickConvert(from, to); // Calls the real conversion logic
        }

        function debugLog(message) {
            console.log(`🪵 debugLog: ${message}`);
            const debugDiv = document.getElementById('debugInfo');
            if (debugDiv) {
                const time = new Date().toLocaleTimeString();
                debugDiv.innerHTML += `<div style="color:#64748b">${time} - ${message}</div>`;
            }
        }

        function debugCloseModal() {
            closeModal('conversionModal');
            debugLog('Conversion modal closed');
        }


        console.log('✅ Quantity Types JavaScript loaded successfully!');
    </script>

    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .stat-card {
            padding: 1.5rem;
            border-radius: 0.75rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            z-index: 10;
            display: none;
        }

        .bulk-actions-bar {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .table-row:hover {
            background-color: #f8fafc;
        }

        .btn-link {
            color: #3b82f6;
            text-decoration: none;
            background: none;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            border-radius: 0.25rem;
        }

        .btn-link:hover {
            background-color: #f0f9ff;
            color: #1e40af;
        }

        .quick-copy {
            transition: all 0.2s ease;
        }

        .quick-copy:hover {
            color: #3b82f6 !important;
            transform: scale(1.05);
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .sort-indicator {
            color: #94a3b8;
            margin-left: 0.25rem;
        }

        .advanced-filters {
            animation: slideDown 0.3s ease-out;
        }

        .view-controls button.active {
            background-color: #3b82f6;
            color: white;
        }
    </style>

    <!-- Include category styles from previous version -->
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

        .indicator-badge {
            padding: 0.125rem 0.375rem;
            border-radius: 12px;
            font-size: 0.625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .indicator-weight {
            background: #fef3c7;
            color: #92400e;
        }

        .indicator-volume {
            background: #dcfce7;
            color: #166534;
        }

        .indicator-count {
            background: #dbeafe;
            color: #1e40af;
        }

        .indicator-dimension {
            background: #fce7f3;
            color: #be185d;
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
    </style>
@endsection
