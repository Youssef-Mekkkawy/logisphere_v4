@extends('layouts.app')

@section('title', 'Accounting Dashboard - LogiFlow')
@section('page-title', 'Accounting Dashboard')

@section('content')
    <div class="accounting-dashboard">
        <!-- Financial Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card revenue">
                <div class="stat-icon">💰</div>
                <div class="stat-details">
                    <h3>${{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p>Total Revenue</p>
                    <span class="stat-change positive">+12% this month</span>
                </div>
            </div>

            <div class="stat-card receivables">
                <div class="stat-icon">📊</div>
                <div class="stat-details">
                    <h3>${{ number_format($stats['outstanding_receivables'], 2) }}</h3>
                    <p>Outstanding Receivables</p>
                    <span class="stat-change neutral">{{ count($recentInvoices) }} invoices</span>
                </div>
            </div>

            <div class="stat-card expenses">
                <div class="stat-icon">📉</div>
                <div class="stat-details">
                    <h3>${{ number_format($stats['total_expenses'], 2) }}</h3>
                    <p>Total Expenses</p>
                    <span class="stat-change negative">Monthly: ${{ number_format($stats['monthly_expenses'], 2) }}</span>
                </div>
            </div>

            <div class="stat-card advances">
                <div class="stat-icon">🏦</div>
                <div class="stat-details">
                    <h3>${{ number_format($stats['pending_advances'], 2) }}</h3>
                    <p>Pending Advances</p>
                    <span class="stat-change neutral">Employee advances</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
                <a href="{{ route('accounting.invoices.create') }}" class="btn btn-primary">
                    <span class="btn-icon">📄</span>
                    Create Invoice
                </a>
                <a href="{{ route('accounting.payments.create') }}" class="btn btn-success">
                    <span class="btn-icon">💳</span>
                    Record Payment
                </a>
                <a href="{{ route('accounting.expenses.create') }}" class="btn btn-warning">
                    <span class="btn-icon">🧾</span>
                    Add Expense
                </a>
                <a href="{{ route('accounting.reports.index') }}" class="btn btn-info">
                    <span class="btn-icon">📈</span>
                    View Reports
                </a>
            </div>
        </div>

        <!-- Recent Activity Sections -->
        <div class="activity-grid">
            <!-- Recent Invoices -->
            <div class="activity-section">
                <div class="section-header">
                    <h3>Recent Invoices</h3>
                    <a href="{{ route('accounting.invoices') }}" class="view-all">View All</a>
                </div>
                <div class="activity-list">
                    @forelse($recentInvoices as $invoice)
                        <div class="activity-item">
                            <div class="item-info">
                                <strong>{{ $invoice->invoice_number }}</strong>
                                <p>{{ $invoice->company->name }}</p>
                                <small>{{ $invoice->invoice_date->format('M d, Y') }}</small>
                            </div>
                            <div class="item-amount">
                                <span class="amount">${{ number_format($invoice->total_amount, 2) }}</span>
                                <span class="status status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="no-data">No recent invoices</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="activity-section">
                <div class="section-header">
                    <h3>Recent Payments</h3>
                    <a href="{{ route('accounting.payments') }}" class="view-all">View All</a>
                </div>
                <div class="activity-list">
                    @forelse($recentPayments as $payment)
                        <div class="activity-item">
                            <div class="item-info">
                                <strong>{{ $payment->payment_number }}</strong>
                                <p>{{ $payment->company->name }}</p>
                                <small>{{ $payment->payment_date->format('M d, Y') }}</small>
                            </div>
                            <div class="item-amount">
                                <span class="amount">${{ number_format($payment->amount, 2) }}</span>
                                <span class="method">{{ ucfirst($payment->method) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="no-data">No recent payments</p>
                    @endforelse
                </div>
            </div>

            <!-- Pending Expenses -->
            <div class="activity-section">
                <div class="section-header">
                    <h3>Pending Expenses</h3>
                    <a href="{{ route('accounting.expenses') }}" class="view-all">View All</a>
                </div>
                <div class="activity-list">
                    @forelse($pendingExpenses as $expense)
                        <div class="activity-item">
                            <div class="item-info">
                                <strong>{{ $expense->expense_number }}</strong>
                                <p>{{ $expense->employee->name ?? 'System' }} - {{ $expense->category }}</p>
                                <small>{{ $expense->expense_date->format('M d, Y') }}</small>
                            </div>
                            <div class="item-amount">
                                <span class="amount">${{ number_format($expense->amount, 2) }}</span>
                                <span class="status status-pending">Pending</span>
                            </div>
                        </div>
                    @empty
                        <p class="no-data">No pending expenses</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .accounting-dashboard {
            max-width: 1400px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.revenue {
            border-left: 5px solid #10b981;
        }

        .stat-card.receivables {
            border-left: 5px solid #3b82f6;
        }

        .stat-card.expenses {
            border-left: 5px solid #f59e0b;
        }

        .stat-card.advances {
            border-left: 5px solid #8b5cf6;
        }

        .stat-icon {
            font-size: 3em;
            margin-right: 20px;
            opacity: 0.8;
        }

        .stat-details h3 {
            font-size: 2em;
            margin: 0;
            color: #1f2937;
        }

        .stat-details p {
            margin: 5px 0;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-change {
            font-size: 0.9em;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .stat-change.positive {
            background: #dcfce7;
            color: #16a34a;
        }

        .stat-change.negative {
            background: #fef3c7;
            color: #d97706;
        }

        .stat-change.neutral {
            background: #f3f4f6;
            color: #6b7280;
        }

        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .quick-actions h3 {
            margin-bottom: 20px;
            color: #1f2937;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-icon {
            margin-right: 8px;
            font-size: 1.2em;
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        .activity-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
        }

        .section-header h3 {
            margin: 0;
            color: #1f2937;
        }

        .view-all {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .item-info strong {
            display: block;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .item-info p {
            margin: 0;
            color: #6b7280;
            font-size: 0.9em;
        }

        .item-info small {
            color: #9ca3af;
            font-size: 0.8em;
        }

        .item-amount {
            text-align: right;
        }

        .amount {
            display: block;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .status {
            font-size: 0.8em;
            padding: 4px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            font-weight: 500;
        }

        .status-paid {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-overdue {
            background: #fecaca;
            color: #dc2626;
        }

        .status-draft {
            background: #f3f4f6;
            color: #6b7280;
        }

        .method {
            font-size: 0.8em;
            color: #6b7280;
            text-transform: capitalize;
        }

        .no-data {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 20px;
        }
    </style>
@endsection
