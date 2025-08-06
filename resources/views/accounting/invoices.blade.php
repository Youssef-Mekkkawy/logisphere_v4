@extends('layouts.app')

@section('title', 'Invoice Management - logisphere')
@section('page-title', 'Invoice Management')

@section('content')
    <div class="invoice-management">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-actions">
                <a href="{{ route('accounting.invoices.create') }}" class="btn btn-primary">
                    <span class="btn-icon">➕</span>
                    Create New Invoice
                </a>
                <div class="filter-controls">
                    <select class="form-input" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                    </select>
                    <select class="form-input" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="receivable">Receivable</option>
                        <option value="payable">Payable</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Invoice Statistics -->
        <div class="invoice-stats">
            <div class="stat-item">
                <span class="stat-label">Total Invoices</span>
                <span class="stat-value">{{ $invoices->total() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Outstanding Amount</span>
                <span class="stat-value">${{ number_format($invoices->sum('balance_due'), 2) }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Paid This Month</span>
                <span
                    class="stat-value">${{ number_format($invoices->where('status', 'paid')->sum('total_amount'), 2) }}</span>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr class="invoice-row" data-status="{{ $invoice->status }}">
                            <td>
                                <strong>{{ $invoice->invoice_number }}</strong>
                                @if ($invoice->shipment)
                                    <br><small>Shipment: {{ $invoice->shipment->shipment_id }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="company-info">
                                    <strong>{{ $invoice->company->name }}</strong>
                                    <small>{{ $invoice->company->type }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="invoice-type type-{{ $invoice->type }}">
                                    {{ ucfirst($invoice->type) }}
                                </span>
                            </td>
                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                            <td>
                                {{ $invoice->due_date->format('M d, Y') }}
                                @if ($invoice->isOverdue())
                                    <br><small class="overdue-text">{{ $invoice->due_date->diffForHumans() }}</small>
                                @endif
                            </td>
                            <td class="amount">${{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="amount">${{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="amount">
                                <strong class="{{ $invoice->balance_due > 0 ? 'balance-due' : 'balance-paid' }}">
                                    ${{ number_format($invoice->balance_due, 2) }}
                                </strong>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $invoice->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('accounting.invoices.show', $invoice) }}" class="btn-action view"
                                        title="View">👁️</a>
                                    <a href="{{ route('accounting.invoices.edit', $invoice) }}" class="btn-action edit"
                                        title="Edit">✏️</a>
                                    @if ($invoice->balance_due > 0)
                                        <a href="{{ route('accounting.payments.create', ['invoice' => $invoice->id]) }}"
                                            class="btn-action pay" title="Record Payment">💳</a>
                                    @endif
                                    <a href="{{ route('accounting.invoices.pdf', $invoice) }}" class="btn-action download"
                                        title="Download PDF">📄</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">
                                No invoices found. <a href="{{ route('accounting.invoices.create') }}">Create your first
                                    invoice</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $invoices->links() }}
        </div>
    </div>

    <style>
        .invoice-management {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-controls {
            display: flex;
            gap: 10px;
        }

        .filter-controls .form-input {
            min-width: 150px;
        }

        .invoice-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .stat-label {
            display: block;
            color: #6b7280;
            font-size: 0.9em;
            margin-bottom: 8px;
        }

        .stat-value {
            display: block;
            font-size: 1.5em;
            font-weight: bold;
            color: #1f2937;
        }

        .data-table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8fafc;
            padding: 15px 10px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        .invoice-row:hover {
            background: #f8fafc;
        }

        .company-info strong {
            display: block;
            color: #1f2937;
        }

        .company-info small {
            color: #6b7280;
            text-transform: capitalize;
        }

        .invoice-type {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8em;
            font-weight: 500;
            text-transform: uppercase;
        }

        .type-receivable {
            background: #dcfce7;
            color: #16a34a;
        }

        .type-payable {
            background: #fef3c7;
            color: #d97706;
        }

        .amount {
            text-align: right;
            font-weight: 500;
        }

        .balance-due {
            color: #dc2626;
        }

        .balance-paid {
            color: #16a34a;
        }

        .overdue-text {
            color: #dc2626;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-action.view {
            background: #eff6ff;
            color: #3b82f6;
        }

        .btn-action.edit {
            background: #fef3c7;
            color: #d97706;
        }

        .btn-action.pay {
            background: #dcfce7;
            color: #16a34a;
        }

        .btn-action.download {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        .no-data a {
            color: #3b82f6;
            text-decoration: none;
        }

        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const statusFilter = document.getElementById('statusFilter');
            const typeFilter = document.getElementById('typeFilter');
            const rows = document.querySelectorAll('.invoice-row');

            function filterTable() {
                const statusValue = statusFilter.value;
                const typeValue = typeFilter.value;

                rows.forEach(row => {
                    const status = row.dataset.status;
                    const type = row.querySelector('.invoice-type').textContent.toLowerCase();

                    const statusMatch = !statusValue || status === statusValue;
                    const typeMatch = !typeValue || type === typeValue;

                    row.style.display = statusMatch && typeMatch ? '' : 'none';
                });
            }

            statusFilter.addEventListener('change', filterTable);
            typeFilter.addEventListener('change', filterTable);
        });
    </script>
@endsection
