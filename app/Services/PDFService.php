<?php

// ================================================================================================
// 5. PDF SERVICE - COMPLETE IMPLEMENTATION
// ================================================================================================

// File: app/Services/PDFService.php
namespace App\Services;

use App\Models\{Invoice, Payment, Expense, EmployeeAdvance, Company};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\{Storage, View};
use Exception;

class PDFService extends BaseAccountingService
{
    protected $defaultOptions = [
        'format' => 'A4',
        'orientation' => 'portrait',
        'margin_top' => 10,
        'margin_right' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
    ];

    /**
     * Generate invoice PDF
     */
    public function generateInvoicePDF(Invoice $invoice, array $options = [])
    {
        $invoice->load(['company', 'shipment', 'payments']);
        
        $data = [
            'invoice' => $invoice,
            'company_info' => $this->getCompanyInfo(),
            'payment_terms' => $this->getPaymentTerms($invoice),
            'total_in_words' => $this->numberToWords($invoice->total_amount),
            'qr_code' => $this->generateInvoiceQRCode($invoice),
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? 'default';
        $view = "pdfs.invoices.{$template}";
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generateInvoiceFilename($invoice);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Generate payment receipt PDF
     */
    public function generatePaymentReceiptPDF(Payment $payment, array $options = [])
    {
        $payment->load(['invoice.company', 'company']);
        
        $data = [
            'payment' => $payment,
            'invoice' => $payment->invoice,
            'company_info' => $this->getCompanyInfo(),
            'amount_in_words' => $this->numberToWords($payment->amount),
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? 'default';
        $view = "pdfs.receipts.{$template}";
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generatePaymentReceiptFilename($payment);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Generate financial report PDF
     */
    public function generateReportPDF(array $reportData, array $options = [])
    {
        $reportType = strtolower(str_replace([' ', '&'], ['_', 'and'], $reportData['report_name']));
        
        $data = [
            'report' => $reportData,
            'company_info' => $this->getCompanyInfo(),
            'generated_by' => auth()->user()->name ?? 'System',
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? $reportType;
        $view = "pdfs.reports.{$template}";
        
        // Fallback to generic report template if specific template doesn't exist
        if (!View::exists($view)) {
            $view = 'pdfs.reports.generic';
        }
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generateReportFilename($reportData);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Generate expense report PDF
     */
    public function generateExpenseReportPDF(array $expenses, array $options = [])
    {
        $totalAmount = collect($expenses)->sum('amount');
        
        $data = [
            'expenses' => $expenses,
            'total_amount' => $totalAmount,
            'amount_in_words' => $this->numberToWords($totalAmount),
            'company_info' => $this->getCompanyInfo(),
            'period' => $options['period'] ?? 'Custom Period',
            'employee' => $options['employee'] ?? null,
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? 'default';
        $view = "pdfs.expenses.{$template}";
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generateExpenseReportFilename($options);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Generate employee advance statement PDF
     */
    public function generateAdvanceStatementPDF(EmployeeAdvance $advance, array $options = [])
    {
        $advance->load('employee');
        
        $data = [
            'advance' => $advance,
            'employee' => $advance->employee,
            'company_info' => $this->getCompanyInfo(),
            'amount_in_words' => $this->numberToWords($advance->amount),
            'balance_in_words' => $this->numberToWords($advance->balance),
            'repayment_schedule' => $this->calculateRepaymentSchedule($advance),
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? 'default';
        $view = "pdfs.advances.{$template}";
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generateAdvanceStatementFilename($advance);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Generate company statement PDF
     */
    public function generateCompanyStatementPDF(Company $company, array $options = [])
    {
        $dateRange = isset($options['period']) ? 
            $this->getDateRange($options['period']) : 
            $this->parseCustomDateRange($options['start_date'], $options['end_date']);
        
        $invoices = Invoice::where('company_id', $company->id)
            ->whereBetween('invoice_date', $dateRange)
            ->with('payments')
            ->get();
            
        $totalInvoiced = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum('paid_amount');
        $balance = $totalInvoiced - $totalPaid;
        
        $data = [
            'company' => $company,
            'invoices' => $invoices,
            'period' => $options['period'] ?? 'Custom Period',
            'date_range' => $dateRange,
            'summary' => [
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'outstanding_balance' => $balance
            ],
            'balance_in_words' => $this->numberToWords($balance),
            'company_info' => $this->getCompanyInfo(),
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? 'default';
        $view = "pdfs.statements.{$template}";
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generateCompanyStatementFilename($company, $options);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Generate aging report PDF
     */
    public function generateAgingReportPDF(array $agingData, array $options = [])
    {
        $data = [
            'aging_report' => $agingData,
            'company_info' => $this->getCompanyInfo(),
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? 'default';
        $view = "pdfs.aging.{$template}";
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generateAgingReportFilename($agingData);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Generate bulk invoices PDF (multiple invoices in one document)
     */
    public function generateBulkInvoicesPDF(array $invoiceIds, array $options = [])
    {
        $invoices = Invoice::whereIn('id', $invoiceIds)
            ->with(['company', 'shipment', 'payments'])
            ->get();
            
        $data = [
            'invoices' => $invoices,
            'company_info' => $this->getCompanyInfo(),
            'template_options' => array_merge($this->defaultOptions, $options)
        ];
        
        $template = $options['template'] ?? 'bulk';
        $view = "pdfs.invoices.{$template}";
        
        $pdf = $this->createPDF($view, $data, $options);
        
        $filename = $this->generateBulkInvoicesFilename($invoices);
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Create PDF with common settings
     */
    private function createPDF(string $view, array $data, array $options = [])
    {
        $mergedOptions = array_merge($this->defaultOptions, $options);
        
        try {
            $pdf = Pdf::loadView($view, $data);
            
            // Set paper format and orientation
            $pdf->setPaper($mergedOptions['format'], $mergedOptions['orientation']);
            
            // Set margins if specified
            if (isset($mergedOptions['margin_top'])) {
                $pdf->setOption('margin-top', $mergedOptions['margin_top']);
                $pdf->setOption('margin-right', $mergedOptions['margin_right']);
                $pdf->setOption('margin-bottom', $mergedOptions['margin_bottom']);
                $pdf->setOption('margin-left', $mergedOptions['margin_left']);
            }
            
            // Additional PDF options
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setOption('enable-javascript', true);
            $pdf->setOption('javascript-delay', 1000);
            $pdf->setOption('enable-smart-shrinking', true);
            $pdf->setOption('no-stop-slow-scripts', true);
            
            return $pdf;
            
        } catch (Exception $e) {
            throw new Exception("PDF generation failed: " . $e->getMessage());
        }
    }

    /**
     * Save PDF to storage
     */
    public function savePDF($pdf, string $filename, string $disk = 'public')
    {
        $path = "pdfs/" . date('Y/m/') . $filename;
        
        Storage::disk($disk)->put($path, $pdf->output());
        
        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'filename' => $filename
        ];
    }

    /**
     * Email PDF as attachment
     */
    public function emailPDF($pdf, string $filename, array $emailData)
    {
        // This would integrate with your mail system
        // Implementation depends on your email setup
        
        return [
            'status' => 'queued',
            'pdf' => $pdf,
            'filename' => $filename,
            'email_data' => $emailData
        ];
    }

    /**
     * Get company information for PDFs
     */
    private function getCompanyInfo()
    {
        return [
            'name' => config('app.name', 'LogiFlow Logistics'),
            'address' => config('accounting.company.address', '123 Business Street'),
            'city' => config('accounting.company.city', 'Business City'),
            'postal_code' => config('accounting.company.postal_code', '12345'),
            'country' => config('accounting.company.country', 'Egypt'),
            'phone' => config('accounting.company.phone', '+20-xxx-xxx-xxxx'),
            'email' => config('accounting.company.email', 'info@logiflow.com'),
            'website' => config('accounting.company.website', 'www.logiflow.com'),
            'tax_number' => config('accounting.company.tax_number', 'TAX123456789'),
            'registration_number' => config('accounting.company.registration_number', 'REG123456789'),
            'logo_path' => config('accounting.company.logo_path', 'images/logo.png'),
        ];
    }

    /**
     * Get payment terms for invoice
     */
    private function getPaymentTerms(Invoice $invoice)
    {
        $dueDays = $invoice->due_date->diffInDays($invoice->invoice_date);
        
        return [
            'due_days' => $dueDays,
            'terms' => "Payment due within {$dueDays} days",
            'late_fee' => 'Late payment may incur additional charges',
            'bank_details' => [
                'bank_name' => config('accounting.bank.name', 'National Bank of Egypt'),
                'account_name' => config('accounting.bank.account_name', 'LogiFlow Logistics'),
                'account_number' => config('accounting.bank.account_number', '1234567890'),
                'swift_code' => config('accounting.bank.swift_code', 'NBEXXXX'),
            ]
        ];
    }

    /**
     * Generate QR code for invoice
     */
    private function generateInvoiceQRCode(Invoice $invoice)
    {
        // QR code data (you can customize this)
        $qrData = [
            'invoice' => $invoice->invoice_number,
            'amount' => $invoice->total_amount,
            'company' => $invoice->company->name,
            'due_date' => $invoice->due_date->format('Y-m-d')
        ];
        
        // You would use a QR code library here
        // For now, return the data that would be encoded
        return base64_encode(json_encode($qrData));
    }

    /**
     * Calculate repayment schedule for advance
     */
    private function calculateRepaymentSchedule(EmployeeAdvance $advance)
    {
        if (!$advance->due_date || $advance->balance <= 0) {
            return [];
        }
        
        $monthsToRepay = max(1, $advance->issued_date->diffInMonths($advance->due_date));
        $monthlyAmount = $advance->balance / $monthsToRepay;
        
        $schedule = [];
        $currentDate = now()->startOfMonth();
        $remainingBalance = $advance->balance;
        
        for ($i = 0; $i < $monthsToRepay; $i++) {
            $paymentAmount = min($monthlyAmount, $remainingBalance);
            $remainingBalance -= $paymentAmount;
            
            $schedule[] = [
                'date' => $currentDate->copy()->addMonths($i)->format('Y-m-d'),
                'amount' => $paymentAmount,
                'remaining_balance' => $remainingBalance
            ];
            
            if ($remainingBalance <= 0) break;
        }
        
        return $schedule;
    }

    /**
     * Convert number to words
     */
    private function numberToWords($number)
    {
        // Simple implementation - you can enhance this
        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        $words = $formatter->format($number);
        
        // Handle currency
        $currency = config('accounting.currency.code', 'USD');
        $wholePart = floor($number);
        $decimalPart = round(($number - $wholePart) * 100);
        
        $result = ucfirst($words);
        
        if ($decimalPart > 0) {
            $decimalWords = $formatter->format($decimalPart);
            $result .= " and {$decimalWords} cents";
        }
        
        return "{$result} {$currency} only";
    }

    /**
     * Generate filename for invoice PDF
     */
    private function generateInvoiceFilename(Invoice $invoice)
    {
        $safeCompanyName = preg_replace('/[^A-Za-z0-9\-]/', '', $invoice->company->name);
        return "Invoice_{$invoice->invoice_number}_{$safeCompanyName}_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate filename for payment receipt PDF
     */
    private function generatePaymentReceiptFilename(Payment $payment)
    {
        $safeCompanyName = preg_replace('/[^A-Za-z0-9\-]/', '', $payment->company->name);
        return "Receipt_{$payment->payment_number}_{$safeCompanyName}_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate filename for report PDF
     */
    private function generateReportFilename(array $reportData)
    {
        $reportName = str_replace([' ', '&'], ['_', 'and'], $reportData['report_name']);
        $period = isset($reportData['period']) ? "_{$reportData['period']}" : '';
        return "{$reportName}{$period}_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate filename for expense report PDF
     */
    private function generateExpenseReportFilename(array $options)
    {
        $period = $options['period'] ?? 'Custom';
        $employee = isset($options['employee']) ? "_{$options['employee']->name}" : '';
        return "Expense_Report_{$period}{$employee}_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate filename for advance statement PDF
     */
    private function generateAdvanceStatementFilename(EmployeeAdvance $advance)
    {
        $employeeName = preg_replace('/[^A-Za-z0-9\-]/', '', $advance->employee->name);
        return "Advance_Statement_{$advance->advance_number}_{$employeeName}_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate filename for company statement PDF
     */
    private function generateCompanyStatementFilename(Company $company, array $options)
    {
        $safeCompanyName = preg_replace('/[^A-Za-z0-9\-]/', '', $company->name);
        $period = $options['period'] ?? 'Custom';
        return "Statement_{$safeCompanyName}_{$period}_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate filename for aging report PDF
     */
    private function generateAgingReportFilename(array $agingData)
    {
        $type = ucfirst($agingData['type']);
        return "Aging_Report_{$type}_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate filename for bulk invoices PDF
     */
    private function generateBulkInvoicesFilename($invoices)
    {
        $count = $invoices->count();
        return "Bulk_Invoices_{$count}_invoices_" . now()->format('Y-m-d') . ".pdf";
    }

    /**
     * Generate custom PDF with template
     */
    public function generateCustomPDF(string $template, array $data, array $options = [])
    {
        $data['company_info'] = $this->getCompanyInfo();
        $data['template_options'] = array_merge($this->defaultOptions, $options);
        
        $pdf = $this->createPDF($template, $data, $options);
        
        $filename = $options['filename'] ?? 'Custom_Document_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return [
            'pdf' => $pdf,
            'filename' => $filename,
            'data' => $data
        ];
    }

    /**
     * Get PDF templates list
     */
    public function getAvailableTemplates()
    {
        return [
            'invoices' => [
                'default' => 'Standard Invoice Template',
                'modern' => 'Modern Invoice Template',
                'classic' => 'Classic Invoice Template',
                'minimal' => 'Minimal Invoice Template'
            ],
            'receipts' => [
                'default' => 'Standard Receipt Template',
                'compact' => 'Compact Receipt Template'
            ],
            'reports' => [
                'profit_loss' => 'Profit & Loss Template',
                'balance_sheet' => 'Balance Sheet Template',
                'cash_flow' => 'Cash Flow Template',
                'generic' => 'Generic Report Template'
            ],
            'statements' => [
                'default' => 'Standard Statement Template',
                'detailed' => 'Detailed Statement Template'
            ],
            'advances' => [
                'default' => 'Standard Advance Template',
                'formal' => 'Formal Advance Template'
            ]
        ];
    }

    /**
     * Validate PDF options
     */
    public function validatePDFOptions(array $options)
    {
        $validFormats = ['A4', 'A3', 'A5', 'Letter', 'Legal'];
        $validOrientations = ['portrait', 'landscape'];
        
        if (isset($options['format']) && !in_array($options['format'], $validFormats)) {
            throw new Exception('Invalid PDF format. Valid formats: ' . implode(', ', $validFormats));
        }
        
        if (isset($options['orientation']) && !in_array($options['orientation'], $validOrientations)) {
            throw new Exception('Invalid PDF orientation. Valid orientations: ' . implode(', ', $validOrientations));
        }
        
        return true;
    }

    /**
     * Get PDF generation statistics
     */
    public function getPDFStats($period = 'this_month')
    {
        // This would track PDF generation statistics
        // For now, return a placeholder structure
        
        return [
            'period' => $period,
            'total_pdfs_generated' => 0,
            'invoices_generated' => 0,
            'reports_generated' => 0,
            'receipts_generated' => 0,
            'most_used_template' => 'default',
            'average_generation_time' => 0,
        ];
    }
}
