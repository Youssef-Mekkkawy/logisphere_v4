@extends('layouts.app')

@section('title', 'Financial Reports - LogiFlow')
@section('page-title', 'Financial Reports')

@section('content')
    <div class="financial-reports">
        <!-- Report Controls -->
        <div class="report-controls">
            <div class="control-section">
                <h3>Generate Report</h3>
                <form method="GET" action="{{ route('accounting.reports') }}" class="report-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Report Type</label>
                            <select name="report_type" class="form-input" onchange="this.form.submit()">
                                <option value="profit_loss" {{ $reportType === 'profit_loss' ? 'selected' : '' }}>Profit &
                                    Loss</option>
                                <option value="balance_sheet" {{ $reportType === 'balance_sheet' ? 'selected' : '' }}>
                                    Balance Sheet</option>
                                <option value="cash_flow" {{ $reportType === 'cash_flow' ? 'selected' : '' }}>Cash Flow
                                </option>
                                <option value="aging_report" {{ $reportType === 'aging_report' ? 'selected' : '' }}>Aging
                                    Report</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Period</label>
                            <select name="period" class="form-input" onchange="this.form.submit()">
                                <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>This Month
                                </option>
                                <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Last Month
                                </option>
                                <option value="this_quarter" {{ $period === 'this_quarter' ? 'selected' : '' }}>This Quarter
                                </option>
                                <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>This Year</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div class="report-actions">
                                <button type="button" class="btn btn-primary" onclick="window.print()">
                                    <span class="btn-icon">🖨️</span>
                                    Print Report
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="exportReport()">
                                    <span class="btn-icon">📊</span>
                                    Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Display -->
        <div class="report-content">
            @if ($reportType === 'profit_loss')
                @include('accounting.reports.profit-loss', ['data' => $reportData])
            @elseif($reportType === 'balance_sheet')
                @include('accounting.reports.balance-sheet', ['data' => $reportData])
            @elseif($reportType === 'cash_flow')
                @include('accounting.reports.cash-flow', ['data' => $reportData])
            @elseif($reportType === 'aging_report')
                @include('accounting.reports.aging-report', ['data' => $reportData])
            @endif
        </div>
    </div>

    <style>
        .financial-reports {
            max-width: 1200px;
            margin: 0 auto;
        }

        .report-controls {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .control-section h3 {
            margin-bottom: 20px;
            color: #1f2937;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
        }

        .report-form .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .report-actions {
            display: flex;
            gap: 10px;
        }

        .report-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        @media print {
            .report-controls {
                display: none;
            }

            .report-content {
                box-shadow: none;
                border-radius: 0;
                padding: 20px;
            }
        }
    </style>

    <script>
        function exportReport() {
            // Add export functionality here
            alert('Export functionality would be implemented here');
        }
    </script>
@endsection
