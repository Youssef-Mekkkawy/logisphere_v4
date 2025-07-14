<?php

// ================================================================================================
// 6. MISSING FORM REQUEST CLASSES - COMPLETE VALIDATION
// ================================================================================================

// ================================================================================================
// INVOICE FORM REQUESTS
// ================================================================================================

// File: app/Http/Requests/Accounting/UpdateInvoiceRequest.php
namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        $invoice = $this->route('invoice');
        return auth()->check() && $invoice->status === 'draft';
    }

    public function rules()
    {
        $invoice = $this->route('invoice');

        return [
            'company_id' => 'required|exists:companies,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'type' => [
                'required',
                'in:receivable,payable',
                Rule::in(['receivable', 'payable'])
            ],
            'invoice_date' => 'required|date|before_or_equal:today',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'line_items' => 'required|array|min:1',
            'line_items.*.description' => 'required|string|max:255',
            'line_items.*.quantity' => 'required|numeric|min:0.01|max:99999',
            'line_items.*.rate' => 'required|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please select a company.',
            'company_id.exists' => 'Selected company is invalid.',
            'due_date.after_or_equal' => 'Due date must be on or after the invoice date.',
            'line_items.required' => 'At least one line item is required.',
            'line_items.min' => 'At least one line item is required.',
            'line_items.*.description.required' => 'Item description is required.',
            'line_items.*.quantity.required' => 'Item quantity is required.',
            'line_items.*.quantity.min' => 'Quantity must be greater than 0.',
            'line_items.*.rate.required' => 'Item rate is required.',
            'line_items.*.rate.min' => 'Rate must be 0 or greater.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if invoice has payments when trying to change amount
            $invoice = $this->route('invoice');

            if ($invoice && $invoice->paid_amount > 0) {
                $currentTotal = collect($this->line_items)->sum(function ($item) {
                    return $item['quantity'] * $item['rate'];
                });

                if ($currentTotal < $invoice->paid_amount) {
                    $validator->errors()->add('line_items', 'Invoice total cannot be less than already paid amount.');
                }
            }
        });
    }
}
