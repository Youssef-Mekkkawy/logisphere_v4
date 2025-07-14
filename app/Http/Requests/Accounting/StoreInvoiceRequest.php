<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'type' => 'required|in:receivable,payable',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'line_items' => 'required|array|min:1',
            'line_items.*.description' => 'required|string|max:255',
            'line_items.*.quantity' => 'required|numeric|min:0.01',
            'line_items.*.rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please select a company.',
            'due_date.after_or_equal' => 'Due date must be on or after the invoice date.',
            'line_items.required' => 'At least one line item is required.',
            'line_items.min' => 'At least one line item is required.',
        ];
    }
}
