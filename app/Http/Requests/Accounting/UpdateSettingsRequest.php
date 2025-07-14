<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-accounting-settings');
    }

    public function rules()
    {
        return [
            'currency_code' => 'required|string|size:3',
            'currency_symbol' => 'required|string|max:5',
            'currency_position' => 'required|in:before,after',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_inclusive' => 'boolean',
            'invoice_prefix' => 'required|string|max:10|regex:/^[A-Z0-9\-]+$/',
            'invoice_due_days' => 'required|integer|min:1|max:365',
            'expense_require_receipt' => 'boolean',
            'expense_auto_approval_limit' => 'nullable|numeric|min:0|max:999999',
            'advance_max_percentage' => 'required|integer|min:0|max:100',
            'advance_require_approval' => 'boolean',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_tax_number' => 'nullable|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'currency_code.required' => 'Currency code is required.',
            'currency_code.size' => 'Currency code must be exactly 3 characters.',
            'currency_symbol.required' => 'Currency symbol is required.',
            'tax_rate.required' => 'Tax rate is required.',
            'tax_rate.min' => 'Tax rate cannot be negative.',
            'tax_rate.max' => 'Tax rate cannot exceed 100%.',
            'invoice_prefix.required' => 'Invoice prefix is required.',
            'invoice_prefix.regex' => 'Invoice prefix can only contain uppercase letters, numbers, and hyphens.',
            'invoice_due_days.required' => 'Default due days is required.',
            'invoice_due_days.min' => 'Due days must be at least 1.',
            'invoice_due_days.max' => 'Due days cannot exceed 365.',
            'advance_max_percentage.required' => 'Maximum advance percentage is required.',
            'advance_max_percentage.max' => 'Maximum advance percentage cannot exceed 100%.',
            'company_name.required' => 'Company name is required.',
            'company_email.email' => 'Company email must be a valid email address.',
        ];
    }
}
