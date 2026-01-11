@extends('layouts.app')

@section('title', 'Invoices - logistics')
@section('page-title', 'Invoice Management')

@section('content')
    <div class="invoices-page">
        <!-- Header with Stats and Actions -->
        <div class="page-header">
            <div class="header-info">
                <h2>📄 All Invoices</h2>
                <p>Manage and track all your invoices</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('accounting.invoices.create') }}" class="btn btn-primary">
                    + Create Invoice
                </a>
                <a href="{{ route('accounting.invoices.overdue') }}" class="btn btn-danger">
                    ⚠️ Overdue ({{ $stats['overdue'] }})
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-number">{{ $stats['total'] }}</span>
                <span class="stat-label">Total Invoices</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['draft'] }}</span>
                <span class="stat-label">Draft</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['sent'] }}</span>
                <span class="stat-label">Sent</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['paid'] }}</span>
                <span class="stat-label">Paid</span>
            </div>
            <div class="stat-item outstanding">
                <span class="stat-number">${{ number_format($stats['total_outstanding'], 0) }}</span>
                <span class="stat-label">Outstanding</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" class="filters-form">
                <div class="filter-group">
                    <select name="status" class="form-input">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="company_id" class="form-input">
                        <option value="">All Companies</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <select name="type" class="form-input">
                        <option value="">All Types</option>
                        <option value="Service" {{ request('type') === 'Service' ? 'selected' : '' }}>Service</option>
                        <option value="Freight" {{ request('type') === 'Freight' ? 'selected' : '' }}>Freight</option>
                        <option value="Other" {{ request('type') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="filter-group">
                    <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}"
                        placeholder="From Date">
                </div>

                <div class="filter-group">
                    <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}"
                        placeholder="To Date">
                </div>

                <div class="filter-group">
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}"
                        placeholder="Search invoices...">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                    <a href="{{ route('accounting.invoices.index') }}" class="btn btn-outline">Clear</a>
                </div>
            </form>
        </div>

        <!-- Invoices Table -->
        <div class="table-container">
            <table class="invoices-table">
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
                                <div class="invoice-number">
                                    <strong>{{ $invoice->invoice_number }}</strong>
                                    @if ($invoice->shipment)
                                        <small>Shipment: {{ $invoice->shipment->shipment_id }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="company-info">
                                    <strong>{{ $invoice->company->name }}</strong>
                                    @if ($invoice->company->email)
                                        <small>{{ $invoice->company->email }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="type-badge type-{{ strtolower($invoice->type) }}">
                                    {{ $invoice->type }}
                                </span>
                            </td>
                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                            <td>
                                <span class="{{ $invoice->isOverdue() ? 'overdue-date' : '' }}">
                                    {{ $invoice->due_date->format('M d, Y') }}
                                </span>
                                @if ($invoice->isOverdue())
                                    <small class="overdue-text">
                                        ({{ $invoice->due_date->diffForHumans() }})
                                    </small>
                                @endif
                            </td>
                            <td class="amount">${{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="amount paid">${{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="amount balance {{ $invoice->balance > 0 ? 'balance-due' : 'balance-paid' }}">
                                ${{ number_format($invoice->balance, 2) }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ $invoice->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('accounting.invoices.show', $invoice) }}" class="btn-action view"
                                        title="View">
                                        👁️
                                    </a>
                                    @if (!$invoice->isPaid())
                                        <a href="{{ route('accounting.invoices.edit', $invoice) }}" class="btn-action edit"
                                            title="Edit">
                                            ✏️
                                        </a>
                                    @endif
                                    @if ($invoice->balance > 0)
                                        <a href="{{ route('accounting.payments.create', ['invoice' => $invoice->id]) }}"
                                            class="btn-action pay" title="Record Payment">
                                            💳
                                        </a>
                                    @endif
                                    <a href="{{ route('accounting.invoices.pdf', $invoice) }}" class="btn-action download"
                                        title="Download PDF">
                                        📄
                                    </a>
                                    @if ($invoice->isDraft())
                                        <form method="POST"
                                            action="{{ route('accounting.invoices.update-status', $invoice) }}"
                                            style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="sent">
                                            <button type="submit" class="btn-action send"
                                                title="Mark as Sent">📤</button>
                                        </form>
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn-action more" title="More actions">⋯</button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('accounting.invoices.duplicate', $invoice) }}">Duplicate</a>
                                            @if ($invoice->isDraft())
                                                <form method="POST"
                                                    action="{{ route('accounting.invoices.destroy', $invoice) }}"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">
                                <div class="empty-state">
                                    <div class="empty-icon">📄</div>
                                    <h3>No invoices found</h3>
                                    <p>Create your first invoice to get started</p>
                                    <a href="{{ route('accounting.invoices.create') }}" class="btn btn-primary">Create
                                        Invoice</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($invoices->hasPages())
            <div class="pagination-wrapper">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    <style>
        .invoices-page {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .header-info h2 {
            margin: 0;
            color: #1e293b;
            font-size: 1.5rem;
        }

        .header-info p {
            margin: 5px 0 0 0;
            color: #64748b;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .stats-row {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            min-width: 120px;
            flex: 1;
        }

        .stat-item.outstanding {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .invoices-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoices-table th {
            background: #f8fafc;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        .invoices-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .invoice-row:hover {
            background: #f8fafc;
        }

        .invoice-number strong {
            color: #1e293b;
            display: block;
        }

        .invoice-number small {
            color: #64748b;
            font-size: 0.75rem;
        }

        .company-info strong {
            color: #1e293b;
            display: block;
        }

        .company-info small {
            color: #64748b;
            font-size: 0.75rem;
        }

        .type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .type-service {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .type-freight {
            background: #fef3c7;
            color: #d97706;
        }

        .type-other {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .amount {
            text-align: right;
            font-weight: 500;
        }

        .amount.paid {
            color: #10b981;
        }

        .amount.balance-due {
            color: #ef4444;
            font-weight: 600;
        }

        .amount.balance-paid {
            color: #10b981;
        }

        .overdue-date {
            color: #ef4444;
            font-weight: 600;
        }

        .overdue-text {
            color: #ef4444;
            font-size: 0.75rem;
            display: block;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-draft {
            background: #f3f4f6;
            color: #6b7280;
        }

        .status-sent {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-overdue {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-cancelled {
            background: #f1f5f9;
            color: #64748b;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .btn-action {
            padding: 6px 8px;
            border: none;
            background: #f8fafc;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        .btn-action.view:hover {
            background: #dbeafe;
        }

        .btn-action.edit:hover {
            background: #fef3c7;
        }

        .btn-action.pay:hover {
            background: #dcfce7;
        }

        .btn-action.download:hover {
            background: #f3e8ff;
        }

        .btn-action.send:hover {
            background: #ddd6fe;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 120px;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            padding: 8px 12px;
            color: #374151;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background: #f9fafb;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #374151;
            margin: 0 0 10px 0;
        }

        .empty-state p {
            color: #64748b;
            margin: 0 0 20px 0;
        }

        .no-data {
            text-align: center;
        }

        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .stats-row {
                flex-direction: column;
            }

            .filters-form {
                grid-template-columns: 1fr;
            }

            .invoices-table {
                font-size: 0.875rem;
            }

            .invoices-table th,
            .invoices-table td {
                padding: 10px 8px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 2px;
            }
        }
    </style>

    @section('scripts')
        <script>
            // Auto-submit filters after a delay
            let filterTimeout;
            document.querySelectorAll('.filters-form input, .filters-form select').forEach(element => {
                element.addEventListener('input', function() {
                    clearTimeout(filterTimeout);
                    filterTimeout = setTimeout(() => {
                        this.form.submit();
                    }, 500);
                });
            });

            // Table row click to view invoice
            document.querySelectorAll('.invoice-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('.action-buttons')) {
                        const viewLink = this.querySelector('.btn-action.view');
                        if (viewLink) {
                            window.location.href = viewLink.href;
                        }
                    }
                });
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = '{{ route('accounting.invoices.create') }}';
                }
            });
        </script>
    @endsection
@endsection
