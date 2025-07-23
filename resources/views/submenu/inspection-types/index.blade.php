@extends('layouts.app')

@section('title', 'Inspection Types')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">🔍 Inspection Types
                </h1>
                <p style="color: #64748b;">Manage customs clearance and inspection requirements</p>
            </div>
            <a href="{{ route('submenu.inspection-types.create') }}" class="btn btn-primary">+ Add New Inspection Type</a>
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
                        placeholder="Inspection name, code, authority...">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="inspection_category" class="form-input">
                        <option value="">All Categories</option>
                        <option value="Customs" {{ request('inspection_category') == 'Customs' ? 'selected' : '' }}>🛃
                            Customs Clearance</option>
                        <option value="Quality" {{ request('inspection_category') == 'Quality' ? 'selected' : '' }}>✅
                            Quality Control</option>
                        <option value="Safety" {{ request('inspection_category') == 'Safety' ? 'selected' : '' }}>🛡️ Safety
                            Inspection</option>
                        <option value="Environmental"
                            {{ request('inspection_category') == 'Environmental' ? 'selected' : '' }}>🌿 Environmental
                        </option>
                        <option value="Security" {{ request('inspection_category') == 'Security' ? 'selected' : '' }}>🔒
                            Security Check</option>
                        <option value="Health" {{ request('inspection_category') == 'Health' ? 'selected' : '' }}>🏥 Health
                            & Sanitary</option>
                        <option value="Technical" {{ request('inspection_category') == 'Technical' ? 'selected' : '' }}>🔧
                            Technical Inspection</option>
                        <option value="Documentation"
                            {{ request('inspection_category') == 'Documentation' ? 'selected' : '' }}>📋 Documentation
                            Review</option>
                        <option value="Physical" {{ request('inspection_category') == 'Physical' ? 'selected' : '' }}>📦
                            Physical Examination</option>
                        <option value="Laboratory" {{ request('inspection_category') == 'Laboratory' ? 'selected' : '' }}>
                            🧪 Laboratory Testing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Requirement</label>
                    <select name="mandatory" class="form-input">
                        <option value="">All</option>
                        <option value="1" {{ request('mandatory') == '1' ? 'selected' : '' }}>Mandatory</option>
                        <option value="0" {{ request('mandatory') == '0' ? 'selected' : '' }}>Optional</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Authority</label>
                    <input type="text" name="regulatory_authority" class="form-input"
                        value="{{ request('regulatory_authority') }}" placeholder="Regulatory authority...">
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('submenu.inspection-types.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Inspection Types Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Inspection Name</th>
                        <th>Category</th>
                        <th>Authority</th>
                        <th>Duration</th>
                        <th>Cost</th>
                        <th>Requirement</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inspectionTypes as $inspectionType)
                        <tr>
                            <td><strong>{{ $inspectionType->inspection_code }}</strong></td>
                            <td>{{ $inspectionType->inspection_name }}</td>
                            <td>
                                <span
                                    class="category-badge category-{{ strtolower($inspectionType->inspection_category) }}">
                                    {{ $inspectionType->category_display }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">{{ $inspectionType->regulatory_authority ?: 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #64748b;">{{ $inspectionType->duration_display }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #059669;">{{ $inspectionType->cost_display }}</div>
                            </td>
                            <td>
                                @if ($inspectionType->mandatory)
                                    <span class="status-badge status-error">Mandatory</span>
                                @else
                                    <span class="status-badge status-success">Optional</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="status-badge status-{{ strtolower($inspectionType->status) }}">{{ $inspectionType->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('submenu.inspection-types.show', $inspectionType) }}"
                                    class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('submenu.inspection-types.edit', $inspectionType) }}"
                                    class="btn btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('submenu.inspection-types.destroy', $inspectionType) }}"
                                        method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                                No inspection types found. <a href="{{ route('submenu.inspection-types.create') }}"
                                    style="color: var(--primary-color);">Create your first inspection type</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($inspectionTypes) && $inspectionTypes->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $inspectionTypes->links() }}
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

        .category-customs {
            background: #dbeafe;
            color: #1e40af;
        }

        .category-quality {
            background: #dcfce7;
            color: #166534;
        }

        .category-safety {
            background: #fef3c7;
            color: #92400e;
        }

        .category-environmental {
            background: #d1fae5;
            color: #065f46;
        }

        .category-security {
            background: #fee2e2;
            color: #991b1b;
        }

        .category-health {
            background: #fce7f3;
            color: #be185d;
        }

        .category-technical {
            background: #e0e7ff;
            color: #3730a3;
        }

        .category-documentation {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .category-physical {
            background: #ecfdf5;
            color: #047857;
        }

        .category-laboratory {
            background: #f0f9ff;
            color: #0369a1;
        }

        .status-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }
    </style>
@endsection
