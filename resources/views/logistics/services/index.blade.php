@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">🛠️ Services
                    Management
                </h1>
                <p style="color: #64748b;">Manage logistics services and pricing</p>
            </div>
            <a href="{{ route('logistics.services.create') }}" class="btn btn-primary">+ Add New Service</a>
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
                        placeholder="Service name, code, description...">
                </div>

                <div class="form-group">
                    <label class="form-label">Service Category</label>
                    <select name="service_category" class="form-input">
                        <option value="">All Categories</option>
                        <option value="Customs Clearance"
                            {{ request('service_category') == 'Customs Clearance' ? 'selected' : '' }}>🛃 Customs Clearance
                        </option>
                        <option value="Transportation"
                            {{ request('service_category') == 'Transportation' ? 'selected' : '' }}>🚛 Transportation
                        </option>
                        <option value="Warehousing" {{ request('service_category') == 'Warehousing' ? 'selected' : '' }}>🏭
                            Warehousing</option>
                        <option value="Documentation"
                            {{ request('service_category') == 'Documentation' ? 'selected' : '' }}>📄 Documentation</option>
                        <option value="Insurance" {{ request('service_category') == 'Insurance' ? 'selected' : '' }}>🛡️
                            Insurance</option>
                        <option value="Inspection" {{ request('service_category') == 'Inspection' ? 'selected' : '' }}>🔍
                            Inspection</option>
                        <option value="Cargo Handling"
                            {{ request('service_category') == 'Cargo Handling' ? 'selected' : '' }}>📦 Cargo Handling
                        </option>
                        <option value="Port Services"
                            {{ request('service_category') == 'Port Services' ? 'selected' : '' }}>⚓ Port Services</option>
                        <option value="Freight Forwarding"
                            {{ request('service_category') == 'Freight Forwarding' ? 'selected' : '' }}>🚢 Freight
                            Forwarding</option>
                        <option value="Consulting" {{ request('service_category') == 'Consulting' ? 'selected' : '' }}>💼
                            Consulting</option>
                        <option value="Other" {{ request('service_category') == 'Other' ? 'selected' : '' }}>🔧 Other
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Billing Type</label>
                    <select name="billing_type" class="form-input">
                        <option value="">All Types</option>
                        <option value="Fixed" {{ request('billing_type') == 'Fixed' ? 'selected' : '' }}>💰 Fixed Rate
                        </option>
                        <option value="Variable" {{ request('billing_type') == 'Variable' ? 'selected' : '' }}>📊 Variable
                            Rate</option>
                        <option value="Percentage" {{ request('billing_type') == 'Percentage' ? 'selected' : '' }}>📈
                            Percentage Based</option>
                        <option value="Hourly" {{ request('billing_type') == 'Hourly' ? 'selected' : '' }}>⏰ Hourly Rate
                        </option>
                        <option value="Per Unit" {{ request('billing_type') == 'Per Unit' ? 'selected' : '' }}>📦 Per Unit
                        </option>
                        <option value="Tiered" {{ request('billing_type') == 'Tiered' ? 'selected' : '' }}>📶 Tiered
                            Pricing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Service Provider</label>
                    <select name="service_provider" class="form-input">
                        <option value="">All Providers</option>
                        <option value="Internal" {{ request('service_provider') == 'Internal' ? 'selected' : '' }}>🏢
                            Internal</option>
                        <option value="External" {{ request('service_provider') == 'External' ? 'selected' : '' }}>🤝
                            External Partner</option>
                        <option value="Both" {{ request('service_provider') == 'Both' ? 'selected' : '' }}>🔄 Internal &
                            External</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Mandatory</label>
                    <select name="is_mandatory" class="form-input">
                        <option value="">All Services</option>
                        <option value="1" {{ request('is_mandatory') == '1' ? 'selected' : '' }}>Mandatory Only
                        </option>
                        <option value="0" {{ request('is_mandatory') == '0' ? 'selected' : '' }}>Optional Only
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
                    <a href="{{ route('logistics.services.index') }}" class="btn btn-secondary"
                        style="margin-left: 0.5rem;">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Services Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Billing Type</th>
                        <th>Rate</th>
                        <th>Provider</th>
                        <th>Mandatory</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td><strong>{{ $service->service_code }}</strong></td>
                            <td>
                                <div>{{ $service->service_name }}</div>
                                @if ($service->description)
                                    <div style="font-size: 0.75rem; color: #64748b;">
                                        {{ Str::limit($service->description, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="service-category-badge service-category-{{ strtolower(str_replace(' ', '-', $service->service_category)) }}">
                                    {{ $service->service_category_display }}
                                </span>
                            </td>
                            <td>
                                <span class="billing-type-badge billing-type-{{ strtolower($service->billing_type) }}">
                                    {{ $service->billing_type_display }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $service->formatted_rate }}</div>
                                @if ($service->minimum_charge)
                                    <div style="font-size: 0.75rem; color: #64748b;">Min: {{ $service->rate_currency }}
                                        {{ number_format($service->minimum_charge, 2) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="provider-badge provider-{{ strtolower($service->service_provider) }}">
                                    {{ $service->service_provider_display }}
                                </span>
                            </td>
                            <td>
                                @if ($service->is_mandatory)
                                    <span class="status-badge status-danger">Required</span>
                                @else
                                    <span class="status-badge status-secondary">Optional</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $service->status_badge }}">{{ $service->status }}</span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('logistics.services.show', $service) }}" class="btn btn-outline"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <a href="{{ route('logistics.services.edit', $service) }}" class="btn btn-success"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('logistics.services.destroy', $service) }}" method="POST"
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
                            <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                                No services found. <a href="{{ route('logistics.services.create') }}"
                                    style="color: var(--primary-color);">Create your first service</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($services) && $services->hasPages())
                <div style="margin-top: 1.5rem; padding: 0 1.5rem;">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .service-category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .service-category-customs-clearance {
            background: #dbeafe;
            color: #1e40af;
        }

        .service-category-transportation {
            background: #fef3c7;
            color: #92400e;
        }

        .service-category-warehousing {
            background: #e0e7ff;
            color: #3730a3;
        }

        .service-category-documentation {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .service-category-insurance {
            background: #ecfdf5;
            color: #047857;
        }

        .service-category-inspection {
            background: #fce7f3;
            color: #be185d;
        }

        .service-category-cargo-handling {
            background: #d1fae5;
            color: #065f46;
        }

        .service-category-port-services {
            background: #f0f9ff;
            color: #0369a1;
        }

        .service-category-freight-forwarding {
            background: #fee2e2;
            color: #991b1b;
        }

        .service-category-consulting {
            background: #f8fafc;
            color: #475569;
        }

        .service-category-other {
            background: #f1f5f9;
            color: #64748b;
        }

        .billing-type-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .billing-type-fixed {
            background: #dcfce7;
            color: #166534;
        }

        .billing-type-variable {
            background: #dbeafe;
            color: #1e40af;
        }

        .billing-type-percentage {
            background: #fef3c7;
            color: #92400e;
        }

        .billing-type-hourly {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .billing-type-per.unit {
            background: #e0e7ff;
            color: #3730a3;
        }

        .billing-type-tiered {
            background: #fce7f3;
            color: #be185d;
        }

        .provider-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .provider-internal {
            background: #dcfce7;
            color: #166534;
        }

        .provider-external {
            background: #dbeafe;
            color: #1e40af;
        }

        .provider-both {
            background: #fef3c7;
            color: #92400e;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
@endsection
