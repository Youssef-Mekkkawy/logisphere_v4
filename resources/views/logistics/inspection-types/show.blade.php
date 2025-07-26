@extends('layouts.app')

@section('title', 'Inspection Type Details - ' . $inspectionType->inspection_name)

@section('content')
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $inspectionType->inspection_name }}
                </h1>
                <p style="color: #64748b;">Inspection Type Details - {{ $inspectionType->inspection_code }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('logistics.inspection-types.edit', $inspectionType) }}" class="btn btn-primary">Edit
                    Inspection Type</a>
                <a href="{{ route('logistics.inspection-types.index') }}" class="btn btn-secondary">Back to List</a>
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
                        <strong>Inspection Code:</strong><br>
                        <span style="color: #64748b;">{{ $inspectionType->inspection_code }}</span>
                    </div>
                    <div>
                        <strong>Inspection Name:</strong><br>
                        <span style="color: #64748b;">{{ $inspectionType->inspection_name }}</span>
                    </div>
                    <div>
                        <strong>Category:</strong><br>
                        <span class="category-badge category-{{ strtolower($inspectionType->inspection_category) }}">
                            {{ $inspectionType->category_display }}
                        </span>
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower($inspectionType->status) }}">{{ $inspectionType->status }}</span>
                    </div>
                    <div>
                        <strong>Requirement:</strong><br>
                        @if ($inspectionType->mandatory)
                            <span class="status-badge status-error">Mandatory</span>
                        @else
                            <span class="status-badge status-success">Optional</span>
                        @endif
                    </div>
                    <div>
                        <strong>Renewal Required:</strong><br>
                        @if ($inspectionType->renewal_required)
                            <span class="status-badge status-warning">Yes</span>
                        @else
                            <span class="status-badge status-success">No</span>
                        @endif
                    </div>
                </div>

                @if ($inspectionType->description)
                    <div style="margin-top: 1.5rem;">
                        <strong>Description:</strong><br>
                        <span style="color: #64748b;">{{ $inspectionType->description }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h3>📊 Statistics</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 1rem;">
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                            {{ $statistics['total_inspections'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Total Inspections</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">
                            {{ $statistics['pending_inspections'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Pending</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #059669;">
                            {{ $statistics['completed_inspections'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Completed</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #dc2626;">
                            {{ $statistics['failed_inspections'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">Failed</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.375rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #7c3aed;">
                            {{ $statistics['monthly_volume'] }}
                        </div>
                        <div style="font-size: 0.875rem; color: #64748b;">This Month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Authority & Cost Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>🏛️ Authority & Cost Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @if ($inspectionType->regulatory_authority)
                    <div>
                        <strong>Regulatory Authority:</strong><br>
                        <span style="color: #64748b;">{{ $inspectionType->regulatory_authority }}</span>
                    </div>
                @endif
                <div>
                    <strong>Estimated Duration:</strong><br>
                    <span style="color: #64748b;">{{ $inspectionType->duration_display }}</span>
                </div>
                <div>
                    <strong>Cost Estimate:</strong><br>
                    <span style="color: #059669; font-weight: 600;">{{ $inspectionType->cost_display }}</span>
                </div>
                @if ($inspectionType->validity_period)
                    <div>
                        <strong>Validity Period:</strong><br>
                        <span style="color: #64748b;">{{ $inspectionType->validity_display }}</span>
                    </div>
                @endif
                @if ($inspectionType->compliance_standards)
                    <div>
                        <strong>Compliance Standards:</strong><br>
                        <span style="color: #64748b;">{{ $inspectionType->compliance_standards }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Required Documents -->
    @if ($inspectionType->required_documents && count($inspectionType->required_documents) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📄 Required Documents</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($inspectionType->required_documents as $document)
                        <span class="document-badge">{{ $document }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Applies To Shipment Types -->
    @if ($inspectionType->applies_to && count($inspectionType->applies_to) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Applies To Shipment Types</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach ($inspectionType->applies_to as $shipmentType)
                        <span class="shipment-type-badge">{{ $shipmentType }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📦 Applies To Shipment Types</h3>
            </div>
            <div class="card-body">
                <span style="color: #64748b;">This inspection applies to all shipment types</span>
            </div>
        </div>
    @endif

    <!-- Prerequisites -->
    @if ($inspectionType->prerequisites)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>📝 Prerequisites</h3>
            </div>
            <div class="card-body">
                <span style="color: #64748b;">{{ $inspectionType->prerequisites }}</span>
            </div>
        </div>
    @endif

    <!-- Recent Inspections -->
    @if ($recentShipments->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>🚢 Recent Shipments Using This Inspection</h3>
            </div>
            <div class="card-body">
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Shipment ID</th>
                                <th>Company</th>
                                <th>Origin</th>
                                <th>Inspection Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentShipments as $shipment)
                                <tr>
                                    <td><strong>{{ $shipment->shipment_id }}</strong></td>
                                    <td>{{ $shipment->company->name ?? 'N/A' }}</td>
                                    <td>{{ $shipment->originPort->name ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $shipment->pivot->status ?? 'pending')) }}">
                                            {{ $shipment->pivot->status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $shipment->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('management.shipments.show', $shipment) }}" class="btn btn-outline"
                                            style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

        .document-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #0ea5e9;
        }

        .shipment-type-badge {
            background: #ecfdf5;
            color: #047857;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #10b981;
        }

        .status-error {
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
