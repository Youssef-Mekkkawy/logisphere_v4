<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Services\AccountingSettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(AccountingSettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $settings = $this->settingsService->getAllSettings();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|size:3',
            'currency_symbol' => 'required|string|max:5',
            'currency_position' => 'required|in:before,after',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_inclusive' => 'boolean',
            'invoice_prefix' => 'required|string|max:10',
            'invoice_due_days' => 'required|integer|min:1|max:365',
            'expense_require_receipt' => 'boolean',
            'advance_max_percentage' => 'required|integer|min:0|max:100',
            'advance_require_approval' => 'boolean',
        ]);

        $this->settingsService->updateSettings($request->validated());

        return back()->with('success', 'Settings updated successfully!');
    }

    public function createBackup()
    {
        $backup = $this->settingsService->createBackup();

        return response()->download($backup['path'])
            ->deleteFileAfterSend(true);
    }
}
