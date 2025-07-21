@extends('layouts.app')

@section('title', 'Bosla from Gomrok Details')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $boslaGomrok->name }}
        </h1>
        <p style="color: #64748b;">Bosla from Gomrok Details - {{ $boslaGomrok->code }}</p>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Bosla Information -->
        <div class="card">
            <div class="card-header">
                <h3>Bosla Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>Bosla Code:</strong><br>
                        <span style="color: #64748b;">{{ $boslaGomrok->code }}</span>
                    </div>
                    <div>
                        <strong>Bosla Name:</strong><br>
                        <span style="color: #64748b;">{{ $boslaGomrok->name }}</span>
                    </div>
                    <div>
                        <strong>Document Type:</strong><br>
                        <span class="document-badge document-{{ strtolower($boslaGomrok->document_type) }}">
                            {{ $boslaGomrok->document_type }}
                        </span>
                    </div>
                    <div>
                        <strong>Customs Office:</strong><br>
                        <span style="color: #64748b;">{{ $boslaGomrok->customs_office }}</span>
                    </div>
                    <div>
                        <strong>Processing Time:</strong><br>
                        <span style="color: #64748b;">{{ $boslaGomrok->processing_time_display }}</span>
                    </div>
                    <div>
                        <strong>Cost:</strong><br>
                        <span style="color: #64748b;">${{ number_format($boslaGomrok->cost, 2) }} USD</span>
                    </div>
                    <div>
                        <strong>Validity Period:</strong><br>
                        <span style="color: #64748b;">{{ $boslaGomrok->validity_display }}</span>
                    </div>
                    <div>
                        <strong>Mandatory:</strong><br>
                        @if ($boslaGomrok->is_mandatory)
                            <span class="status-badge status-warning">Yes - Mandatory</span>
                        @else
                            <span class="status-badge status-info">No - Optional</span>
                        @endif
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span
                            class="status-badge status-{{ strtolower($boslaGomrok->status) }}">{{ $boslaGomrok->status }}</span>
                    </div>
                </div>

                @if ($boslaGomrok->description)
                    <div style="margin-top: 1.5rem;">
                        <strong>Description:</strong><br>
                        <p style="color: #64748b; margin-top: 0.5rem;">{{ $boslaGomrok->description }}</p>
                    </div>
                @endif

                @if ($boslaGomrok->required_documents)
                    <div style="margin-top: 1.5rem;">
                        <strong>Required Documents:</strong><br>
                        <div style="margin-top: 0.5rem;">
                            @foreach ($boslaGomrok->getRequiredDocumentsArray() as $document)
                                <span class="document-requirement">{{ trim($document) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h3>Usage Statistics</h3>
            </div>
            <div class="card-body">
                <div style="space-y: 1rem;">
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Total Usage</span>
                        <span style="font-weight: 600;">{{ $statistics['total_usage'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>This Month</span>
                        <span style="font-weight: 600;">{{ $statistics['this_month_usage'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Shipments</span>
                        <span style="font-weight: 600;">{{ $statistics['shipments_count'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Clearances</span>
                        <span style="font-weight: 600;">{{ $statistics['clearances_count'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Last Used</span>
                        <span style="font-weight: 600;">
                            {{ $statistics['last_used'] ? $statistics['last_used']->format('M d, Y') : 'Never' }}
                        </span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Total Revenue</span>
                        <span style="font-weight: 600;">${{ number_format($statistics['total_revenue'] ?? 0, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                        <span>Created</span>
                        <span style="font-weight: 600;">{{ $boslaGomrok->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Information -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>Processing Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div class="info-box">
                    <div class="info-icon" style="background: #dbeafe; color: #1e40af;">⏱️</div>
                    <div>
                        <div class="info-title">Processing Time</div>
                        <div class="info-value">{{ $boslaGomrok->processing_hours }} hours</div>
                        <div class="info-subtitle">{{ $boslaGomrok->processing_time_display }}</div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon" style="background: #d1fae5; color: #065f46;">💰</div>
                    <div>
                        <div class="info-title">Processing Cost</div>
                        <div class="info-value">${{ number_format($boslaGomrok->cost, 2) }}</div>
                        <div class="info-subtitle">Per application</div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon" style="background: #fef3c7; color: #92400e;">📅</div>
                    <div>
                        <div class="info-title">Validity Period</div>
                        <div class="info-value">{{ $boslaGomrok->validity_days ?: '∞' }}</div>
                        <div class="info-subtitle">{{ $boslaGomrok->validity_display }}</div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon" style="background: #ede9fe; color: #5b21b6;">📄</div>
                    <div>
                        <div class="info-title">Document Type</div>
                        <div class="info-value">{{ $boslaGomrok->document_type }}</div>
                        <div class="info-subtitle">{{ $boslaGomrok->customs_office }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Usage -->
    @if (isset($recentUsage) && $recentUsage->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>Recent Usage</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Company</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentUsage as $usage)
                            <tr>
                                <td><strong>{{ $usage->reference_number ?? $usage->id }}</strong></td>
                                <td>{{ $usage->company->name ?? 'N/A' }}</td>
                                <td>{{ $usage->created_at->format('M d, Y') }}</td>
                                <td><span
                                        class="status-badge status-{{ strtolower(str_replace(' ', '', $usage->status)) }}">{{ $usage->status }}</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-outline"
                                        style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
        <a href="{{ route('submenu.bosla-gomrok.edit' ?? '' , $boslaGomrok) }}" class="btn btn-primary">Edit Bosla</a>
        <a href="{{ route('submenu.bosla-gomrok.index') ?? ''  }}" class="btn btn-secondary">Back to Bosla List</a>
        @if (auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
            <form action="{{ route('submenu.bosla-gomrok.destroy' ?? '' , $boslaGomrok) }}" method="POST"
                style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this bosla?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Bosla</button>
            </form>
        @endif
    </div>

    <style>
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color, #3b82f6), var(--primary-dark, #1e40af));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            color: white;
        }

        .btn-outline {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-outline:hover {
            background: var(--primary-color, #3b82f6);
            color: white;
            border-color: var(--primary-color, #3b82f6);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .document-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .document-export {
            background: #d1fae5;
            color: #065f46;
        }

        .document-import {
            background: #dbeafe;
            color: #1e40af;
        }

        .document-transit {
            background: #fef3c7;
            color: #92400e;
        }

        .document-temporary {
            background: #ede9fe;
            color: #5b21b6;
        }

        .document-requirement {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin: 0.25rem 0.25rem 0.25rem 0;
        }

        .info-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .info-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .info-title {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .info-subtitle {
            font-size: 0.75rem;
            color: #64748b;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: #f8fafc;
        }
    </style>
@endsection
