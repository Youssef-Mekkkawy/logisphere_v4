@extends('layouts.app')

@section('title', 'COO Type Details')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $cooType->name }}</h1>
        <p style="color: #64748b;">Certificate of Origin Type Details</p>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- COO Type Information -->
        <div class="card">
            <div class="card-header">
                <h3>COO Type Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <div>
                        <strong>COO Code:</strong><br>
                        <span style="color: #64748b;">{{ $cooType->code }}</span>
                    </div>
                    <div>
                        <strong>COO Name:</strong><br>
                        <span style="color: #64748b;">{{ $cooType->name }}</span>
                    </div>
                    <div>
                        <strong>Issuing Authority:</strong><br>
                        <span style="color: #64748b;">{{ $cooType->issuing_authority }}</span>
                    </div>
                    <div>
                        <strong>Processing Time:</strong><br>
                        <span style="color: #64748b;">{{ $cooType->processing_days }} days</span>
                    </div>
                    <div>
                        <strong>Cost:</strong><br>
                        <span style="color: #64748b;">${{ number_format($cooType->cost, 2) }} USD</span>
                    </div>
                    <div>
                        <strong>Validity Period:</strong><br>
                        <span style="color: #64748b;">
                            @if ($cooType->validity_months == 0)
                                No Expiry
                            @elseif($cooType->validity_months == 1)
                                1 Month
                            @elseif($cooType->validity_months < 12)
                                {{ $cooType->validity_months }} Months
                            @else
                                {{ $cooType->validity_months / 12 }} Year(s)
                            @endif
                        </span>
                    </div>
                    <div>
                        <strong>Mandatory:</strong><br>
                        @if ($cooType->is_mandatory)
                            <span class="status-badge status-warning">Yes - Mandatory</span>
                        @else
                            <span class="status-badge status-active">No - Optional</span>
                        @endif
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span class="status-badge status-{{ strtolower($cooType->status) }}">{{ $cooType->status }}</span>
                    </div>
                </div>

                @if ($cooType->description)
                    <div style="margin-top: 1.5rem;">
                        <strong>Description:</strong><br>
                        <p style="color: #64748b; margin-top: 0.5rem;">{{ $cooType->description }}</p>
                    </div>
                @endif

                @if ($cooType->required_documents)
                    <div style="margin-top: 1.5rem;">
                        <strong>Required Documents:</strong><br>
                        <p style="color: #64748b; margin-top: 0.5rem;">{{ $cooType->required_documents }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- COO Type Statistics -->
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
                        <span style="font-weight: 600;">{{ $statistics['this_month'] ?? 0 }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>Last Usage</span>
                        <span style="font-weight: 600;">
                            {{ $statistics['last_used'] ? $statistics['last_used']->format('M d, Y') : 'Never' }}
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                        <span>Created</span>
                        <span style="font-weight: 600;">{{ $cooType->created_at->format('M d, Y') }}</span>
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
                            <th>Shipment ID</th>
                            <th>Company</th>
                            <th>Date Used</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentUsage as $usage)
                            <tr>
                                <td><strong>{{ $usage->shipment_id }}</strong></td>
                                <td>{{ $usage->company->name ?? 'N/A' }}</td>
                                <td>{{ $usage->created_at->format('M d, Y') }}</td>
                                <td><span
                                        class="status-badge status-{{ strtolower(str_replace(' ', '', $usage->status)) }}">{{ $usage->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('management.shipments.show', $usage) }}" class="btn btn-outline"
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
        <a href="{{ route('coo-types.edit' ?? '', $cooType) }}" class="btn btn-primary">Edit COO Type</a>
        <a href="{{ route('logistics.coo-types.index') }}  ?? '' " class="btn btn-secondary">Back to COO Types</a>
        @if (auth()->user()->isAdmin())
            <form action="{{ route('coo-types.destroy' ?? '', $cooType) }}" method="POST" style="display: inline;"
                onsubmit="return confirm('Are you sure you want to delete this COO type?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete COO Type</button>
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
