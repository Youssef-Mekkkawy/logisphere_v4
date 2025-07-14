<div class="profit-loss-report">
    <div class="report-header">
        <h2>LogiFlow Logistics</h2>
        <h3>Profit & Loss Statement</h3>
        <p class="report-period">For the period: {{ ucfirst(str_replace('_', ' ', $data['period'])) }}</p>
        <p class="report-date">Generated on: {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="report-body">
        <table class="financial-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Account</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Revenue Section -->
                <tr class="section-header">
                    <td><strong>REVENUE</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="account-name">Service Revenue</td>
                    <td class="amount positive">${{ number_format($data['revenue'], 2) }}</td>
                </tr>
                <tr class="subtotal">
                    <td><strong>Total Revenue</strong></td>
                    <td class="amount"><strong>${{ number_format($data['revenue'], 2) }}</strong></td>
                </tr>

                <!-- Expenses Section -->
                <tr class="section-header">
                    <td><strong>EXPENSES</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="account-name">Operating Expenses</td>
                    <td class="amount negative">${{ number_format($data['expenses'], 2) }}</td>
                </tr>
                <tr class="subtotal">
                    <td><strong>Total Expenses</strong></td>
                    <td class="amount"><strong>${{ number_format($data['expenses'], 2) }}</strong></td>
                </tr>

                <!-- Net Profit -->
                <tr class="total-line">
                    <td><strong>NET PROFIT</strong></td>
                    <td class="amount {{ $data['net_profit'] >= 0 ? 'positive' : 'negative' }}">
                        <strong>${{ number_format($data['net_profit'], 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Key Metrics -->
        <div class="key-metrics">
            <h4>Key Performance Indicators</h4>
            <div class="metrics-grid">
                <div class="metric-item">
                    <span class="metric-label">Profit Margin</span>
                    <span class="metric-value {{ $data['profit_margin'] >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($data['profit_margin'], 1) }}%
                    </span>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Total Revenue</span>
                    <span class="metric-value">${{ number_format($data['revenue'], 2) }}</span>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Total Expenses</span>
                    <span class="metric-value">${{ number_format($data['expenses'], 2) }}</span>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Net Profit</span>
                    <span class="metric-value {{ $data['net_profit'] >= 0 ? 'positive' : 'negative' }}">
                        ${{ number_format($data['net_profit'], 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profit-loss-report {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .report-header {
        text-align: center;
        margin-bottom: 40px;
        border-bottom: 3px solid #3b82f6;
        padding-bottom: 20px;
    }

    .report-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 2em;
    }

    .report-header h3 {
        margin: 10px 0 5px 0;
        color: #3b82f6;
        font-size: 1.5em;
    }

    .report-period,
    .report-date {
        margin: 5px 0;
        color: #6b7280;
        font-size: 0.9em;
    }

    .financial-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .financial-table th {
        background: #f8fafc;
        padding: 15px;
        border: 1px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
    }

    .financial-table td {
        padding: 12px 15px;
        border: 1px solid #f3f4f6;
    }

    .section-header td {
        background: #f8fafc;
        font-weight: 600;
        color: #374151;
        border-top: 2px solid #e5e7eb;
    }

    .account-name {
        padding-left: 30px;
        color: #4b5563;
    }

    .subtotal {
        border-top: 1px solid #d1d5db;
        background: #fafafa;
    }

    .total-line {
        border-top: 3px double #3b82f6;
        background: #eff6ff;
    }

    .amount {
        text-align: right;
        font-family: 'Courier New', monospace;
    }

    .amount.positive {
        color: #16a34a;
    }

    .amount.negative {
        color: #dc2626;
    }

    .key-metrics {
        margin-top: 30px;
        padding: 25px;
        background: #f8fafc;
        border-radius: 10px;
        border-left: 4px solid #3b82f6;
    }

    .key-metrics h4 {
        margin-bottom: 20px;
        color: #1f2937;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .metric-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .metric-label {
        font-weight: 500;
        color: #6b7280;
    }

    .metric-value {
        font-weight: bold;
        font-size: 1.1em;
    }

    .metric-value.positive {
        color: #16a34a;
    }

    .metric-value.negative {
        color: #dc2626;
    }
</style>
