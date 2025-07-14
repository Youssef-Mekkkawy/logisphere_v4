<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\{Invoice, Payment, Expense, EmployeeAdvance, Account};

class AccountingSettingsService
{
    public function getAllSettings()
    {
        return [
            'currency' => [
                'code' => config('accounting.currency.code'),
                'symbol' => config('accounting.currency.symbol'),
                'position' => config('accounting.currency.position'),
            ],
            'tax' => [
                'default_rate' => config('accounting.tax.default_rate'),
                'inclusive' => config('accounting.tax.inclusive'),
            ],
            'invoice' => [
                'prefix' => config('accounting.invoice.prefix'),
                'due_days' => config('accounting.invoice.due_days'),
            ],
            'expense' => [
                'require_receipt' => config('accounting.expense.require_receipt'),
                'max_file_size' => config('accounting.expense.max_file_size'),
            ],
            'advance' => [
                'max_percentage' => config('accounting.advance.max_percentage'),
                'require_approval' => config('accounting.advance.require_approval'),
            ],
        ];
    }

    public function updateSettings(array $data)
    {
        // In a real application, you'd update these in a settings table or config file
        // For now, we'll just cache them or store in a settings model

        // You could implement a Settings model or use Laravel's config caching
        return true;
    }

    public function createBackup()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "accounting_backup_{$timestamp}.json";

        $data = [
            'timestamp' => now()->toISOString(),
            'accounts' => Account::all(),
            'invoices' => Invoice::with(['company', 'payments'])->get(),
            'payments' => Payment::with('invoice')->get(),
            'expenses' => Expense::with(['employee', 'account'])->get(),
            'advances' => EmployeeAdvance::with('employee')->get(),
        ];

        $path = storage_path("app/backups/{$filename}");

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));

        return [
            'path' => $path,
            'filename' => $filename,
            'size' => filesize($path)
        ];
    }
}
