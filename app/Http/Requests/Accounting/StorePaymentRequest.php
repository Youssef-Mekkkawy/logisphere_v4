<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,check,bank_transfer,credit_card,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->invoice_id && $this->amount) {
                $invoice = \App\Models\Invoice::find($this->invoice_id);
                if ($invoice && $this->amount > $invoice->balance_due) {
                    $validator->errors()->add('amount', 'Payment amount cannot exceed the outstanding balance.');
                }
            }
        });
    }
}
