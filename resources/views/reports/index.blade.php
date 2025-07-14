@extends('layouts.app')

@section('title', 'Reports & Analytics - LogiFlow')
@section('page-title', 'Reports & Analytics')

@section('content')
    <div class="reports-container">
        {{-- Reports Header --}}
        <div class="reports-header">
            <div class="header-content">
                <h3>📊 Reports & Analytics</h3>
                <p>Generate comprehensive reports and analyze your business performance</p>
            </div>
            <div class="header-actions">
                <button onclick="exportAllReports()" class="btn btn-primary">
                    <span class="btn-icon">📥</span>
                    Export All
                </button>
                <button onclick="scheduleReport()" class="btn btn-secondary">
                    <span class="btn-icon">⏰</span>
                    Schedule Report
                </button>
            </div>
        </div>

        {{-- Quick Stats Dashboard --}}
        <div class="quick-stats">
            <div class="stat-group">
                <h4>📈 This Month</h4>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">${{ number_format($monthlyStats['revenue'] ?? 0) }}</div>
                        <div class="stat-label">Revenue</div>
                        <div class="stat-change positive">+12.5%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $monthlyStats['shipments'] ?? 0 }}</div>
                        <div class="stat-label">Shipments</div>
                        <div class="stat-change positive">+8.3%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $monthlyStats['clients'] ?? 0 }}</div>
                        <div class="stat-label">Active Clients</div>
                        <div class="stat-change neutral">+2.1%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($monthlyStats['avg_delivery_time'] ?? 0, 1) }}</div>
                        <div class="stat-label">Avg Delivery (Days)</div>
                        <div class="stat-change negative">-1.2</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Report Categories --}}
        <div class="report-categories">
            {{-- Financial Reports --}}
            <div class="report-category">
                <div class="category-header">
                    <h4>💰 Financial Reports</h4>
                    <span class="category-icon">💰</span>
                </div>

                <div class="reports-grid">
                    <div class="report-card">
                        <div class="report-info">
                            <h5>Revenue Analysis</h5>
                            <p>Monthly and yearly revenue breakdown by service type and client</p>
                            <div class="report-meta">
                                <span class="report-type">📊 Chart & Table</span>
                                <span class="report-update">Updated daily</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('revenue')" class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('revenue')" class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-info">
                            <h5>Profit & Loss Statement</h5>
                            <p>Comprehensive P&L with expenses, revenue, and net profit calculations</p>
                            <div class="report-meta">
                                <span class="report-type">📋 Financial Statement</span>
                                <span class="report-update">Updated weekly</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('pl')" class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('pl')" class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-info">
                            <h5>Client Payment Summary</h5>
                            <p>Outstanding invoices, payment history, and aging analysis</p>
                            <div class="report-meta">
                                <span class="report-type">💳 Payment Report</span>
                                <span class="report-update">Real-time</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('payments')" class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('payments')" class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operational Reports --}}
            <div class="report-category">
                <div class="category-header">
                    <h4>🚢 Operational Reports</h4>
                    <span class="category-icon">🚢</span>
                </div>

                <div class="reports-grid">
                    <div class="report-card">
                        <div class="report-info">
                            <h5>Shipment Performance</h5>
                            <p>Delivery times, delays, and performance metrics by route and carrier</p>
                            <div class="report-meta">
                                <span class="report-type">⏱️ Performance Metrics</span>
                                <span class="report-update">Updated hourly</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('shipment-performance')"
                                class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('shipment-performance')"
                                class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-info">
                            <h5>Route Efficiency</h5>
                            <p>Most profitable routes, capacity utilization, and cost analysis</p>
                            <div class="report-meta">
                                <span class="report-type">🗺️ Route Analysis</span>
                                <span class="report-update">Updated daily</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('route-efficiency')"
                                class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('route-efficiency')"
                                class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-info">
                            <h5>Client Activity</h5>
                            <p>Client shipping patterns, frequency, and business volume analysis</p>
                            <div class="report-meta">
                                <span class="report-type">👥 Client Analytics</span>
                                <span class="report-update">Updated daily</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('client-activity')"
                                class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('client-activity')"
                                class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HR Reports --}}
            <div class="report-category">
                <div class="category-header">
                    <h4>👥 Human Resources Reports</h4>
                    <span class="category-icon">👥</span>
                </div>

                <div class="reports-grid">
                    <div class="report-card">
                        <div class="report-info">
                            <h5>Payroll Summary</h5>
                            <p>Monthly payroll breakdown, overtime, and benefit calculations</p>
                            <div class="report-meta">
                                <span class="report-type">💵 Payroll Report</span>
                                <span class="report-update">Updated monthly</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('payroll')" class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('payroll')" class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-info">
                            <h5>Employee Performance</h5>
                            <p>Productivity metrics, attendance, and performance evaluations</p>
                            <div class="report-meta">
                                <span class="report-type">⭐ Performance Review</span>
                                <span class="report-update">Updated quarterly</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('employee-performance')"
                                class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('employee-performance')"
                                class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-info">
                            <h5>Leave & Attendance</h5>
                            <p>Leave balances, attendance patterns, and absence analysis</p>
                            <div class="report-meta">
                                <span class="report-type">📅 Attendance Report</span>
                                <span class="report-update">Updated daily</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button onclick="generateReport('attendance')"
                                class="btn btn-sm btn-primary">Generate</button>
                            <button onclick="previewReport('attendance')" class="btn btn-sm btn-outline">Preview</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Custom Report Builder --}}
        <div class="custom-report-builder">
            <div class="builder-header">
                <h4>🔧 Custom Report Builder</h4>
                <p>Create personalized reports with specific data points and filters</p>
            </div>

            <div class="builder-form">
                <div class="builder-grid">
                    <div class="builder-section">
                        <h5>Data Source</h5>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="checkbox" name="data_source[]" value="shipments">
                                <span>Shipments</span>
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="data_source[]" value="companies">
                                <span>Companies</span>
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="data_source[]" value="employees">
                                <span>Employees</span>
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="data_source[]" value="financial">
                                <span>Financial Data</span>
                            </label>
                        </div>
                    </div>

                    <div class="builder-section">
                        <h5>Time Period</h5>
                        <select class="form-input">
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_quarter">This Quarter</option>
                            <option value="this_year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>

                    <div class="builder-section">
                        <h5>Format</h5>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="format" value="pdf" checked>
                                <span>PDF Document</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="format" value="excel">
                                <span>Excel Spreadsheet</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="format" value="csv">
                                <span>CSV File</span>
                            </label>
                        </div>
                    </div>

                    <div class="builder-section">
                        <h5>Delivery</h5>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="delivery" value="download" checked>
                                <span>Download Now</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="delivery" value="email">
                                <span>Email Report</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="delivery" value="schedule">
                                <span>Schedule Recurring</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="builder-actions">
                    <button onclick="generateCustomReport()" class="btn btn-primary">
                        <span class="btn-icon">🔧</span>
                        Generate Custom Report
                    </button>
                    <button onclick="saveReportTemplate()" class="btn btn-secondary">
                        <span class="btn-icon">💾</span>
                        Save as Template
                    </button>
                </div>
            </div>
        </div>

        {{-- Recent Reports --}}
        <div class="recent-reports">
            <div class="section-header">
                <h4>📋 Recent Reports</h4>
                <button onclick="clearReportHistory()" class="btn btn-sm btn-outline">Clear History</button>
            </div>

            <div class="reports-list">
                <div class="report-item">
                    <div class="report-details">
                        <h5>Monthly Revenue Analysis</h5>
                        <p>Generated on {{ date('M d, Y H:i') }}</p>
                        <span class="report-size">2.3 MB</span>
                    </div>
                    <div class="report-actions">
                        <button class="btn btn-sm btn-primary">Download</button>
                        <button class="btn btn-sm btn-secondary">View</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </div>
                </div>

                <div class="report-item">
                    <div class="report-details">
                        <h5>Shipment Performance Q4</h5>
                        <p>Generated on {{ date('M d, Y H:i', strtotime('-1 day')) }}</p>
                        <span class="report-size">1.8 MB</span>
                    </div>
                    <div class="report-actions">
                        <button class="btn btn-sm btn-primary">Download</button>
                        <button class="btn btn-sm btn-secondary">View</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('styles')
    <style>
        .reports-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .reports-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding: 25px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .header-content h3 {
            color: #1e40af;
            margin-bottom: 8px;
            font-size: 24px;
        }

        .header-content p {
            color: #6b7280;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .quick-stats {
            margin-bottom: 40px;
        }

        .stat-group h4 {
            color: #1e40af;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-left: 4px solid #3b82f6;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .stat-change.positive {
            background: #d1fae5;
            color: #065f46;
        }

        .stat-change.negative {
            background: #fee2e2;
            color: #991b1b;
        }

        .stat-change.neutral {
            background: #f3f4f6;
            color: #374151;
        }

        .report-categories {
            display: grid;
            gap: 30px;
            margin-bottom: 40px;
        }

        .report-category {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }

        .category-header h4 {
            color: #1e40af;
            margin: 0;
            font-size: 18px;
        }

        .category-icon {
            font-size: 24px;
            opacity: 0.7;
        }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }

        .report-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .report-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .report-info h5 {
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .report-info p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .report-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .report-type {
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .report-update {
            background: #f0fdf4;
            color: #15803d;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .report-actions {
            display: flex;
            gap: 8px;
        }

        .custom-report-builder {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #f59e0b;
        }

        .builder-header h4 {
            color: #f59e0b;
            margin-bottom: 8px;
            font-size: 18px;
        }

        .builder-header p {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .builder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
        }

        .builder-section h5 {
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .checkbox-group,
        .radio-group {
            display: grid;
            gap: 10px;
        }

        .checkbox-item,
        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .checkbox-item:hover,
        .radio-item:hover {
            background: #f3f4f6;
        }

        .checkbox-item input,
        .radio-item input {
            margin: 0;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
        }

        .builder-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .recent-reports {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h4 {
            color: #1e40af;
            margin: 0;
            font-size: 18px;
        }

        .reports-list {
            display: grid;
            gap: 15px;
        }

        .report-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .report-details h5 {
            color: #1f2937;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .report-details p {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .report-size {
            background: #e5e7eb;
            color: #374151;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .report-item .report-actions {
            display: flex;
            gap: 6px;
        }

        @media (max-width: 768px) {
            .reports-header {
                flex-direction: column;
                gap: 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .reports-grid {
                grid-template-columns: 1fr;
            }

            .builder-grid {
                grid-template-columns: 1fr;
            }

            .builder-actions {
                flex-direction: column;
            }

            .report-item {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Report generation functions
            window.generateReport = function(type) {
                showLoadingDialog(`Generating ${type} report...`);

                // Simulate report generation
                setTimeout(() => {
                    hideLoadingDialog();
                    showSuccessDialog(`${type} report generated successfully!`);
                }, 2000);
            };

            window.previewReport = function(type) {
                alert(`Preview for ${type} report - Feature coming soon!`);
            };

            window.exportAllReports = function() {
                if (confirm('Export all available reports? This may take a few minutes.')) {
                    showLoadingDialog('Preparing reports for export...');

                    setTimeout(() => {
                        hideLoadingDialog();
                        showSuccessDialog('All reports exported successfully!');
                    }, 3000);
                }
            };

            window.scheduleReport = function() {
                alert('Scheduled reports feature coming soon!');
            };

            window.generateCustomReport = function() {
                const dataSources = Array.from(document.querySelectorAll('input[name="data_source[]"]:checked'))
                    .map(cb => cb.value);

                if (dataSources.length === 0) {
                    alert('Please select at least one data source.');
                    return;
                }

                showLoadingDialog('Generating custom report...');

                setTimeout(() => {
                    hideLoadingDialog();
                    showSuccessDialog('Custom report generated successfully!');
                }, 2500);
            };

            window.saveReportTemplate = function() {
                const templateName = prompt('Enter template name:');
                if (templateName) {
                    alert(`Template "${templateName}" saved successfully!`);
                }
            };

            window.clearReportHistory = function() {
                if (confirm('Clear all report history? This action cannot be undone.')) {
                    document.querySelector('.reports-list').innerHTML =
                        '<p style="text-align: center; color: #6b7280; padding: 20px;">No recent reports</p>';
                }
            };

            // Utility functions
            function showLoadingDialog(message) {
                const dialog = document.createElement('div');
                dialog.className = 'loading-dialog';
                dialog.innerHTML = `
            <div class="loading-content">
                <div class="loading-spinner">⏳</div>
                <p>${message}</p>
            </div>
        `;
                document.body.appendChild(dialog);
            }

            function hideLoadingDialog() {
                const dialog = document.querySelector('.loading-dialog');
                if (dialog) {
                    dialog.remove();
                }
            }

            function showSuccessDialog(message) {
                const dialog = document.createElement('div');
                dialog.className = 'success-dialog';
                dialog.innerHTML = `
            <div class="success-content">
                <div class="success-icon">✅</div>
                <p>${message}</p>
                <button onclick="this.parentElement.parentElement.remove()" class="btn btn-primary">OK</button>
            </div>
        `;
                document.body.appendChild(dialog);
            }

            console.log('Reports & Analytics page initialized');
        });

        // Add CSS for dialogs
        const style = document.createElement('style');
        style.textContent = `
    .loading-dialog, .success-dialog {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .loading-content, .success-content {
        background: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        max-width: 400px;
    }

    .loading-spinner, .success-icon {
        font-size: 48px;
        margin-bottom: 20px;
        animation: pulse 2s infinite;
    }

    .loading-content p, .success-content p {
        color: #374151;
        font-size: 16px;
        margin-bottom: 20px;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
`;
        document.head.appendChild(style);
    </script>
@endsection
@endsection
