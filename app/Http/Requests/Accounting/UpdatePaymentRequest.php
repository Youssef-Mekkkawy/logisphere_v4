<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->can('update-payments');
    }

    public function rules()
    {
        return [
            'method' => 'required|in:cash,check,bank_transfer,credit_card,other',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $payment = $this->route('payment');
            $invoice = $payment->invoice;

            // Calculate available balance (add back current payment amount)
            $availableBalance = $invoice->balance_due + $payment->amount;

            if ($this->amount > $availableBalance) {
                $validator->errors()->add(
                    'amount',
                    'Payment amount cannot exceed available balance of $' . number_format($availableBalance, 2)
                );
            }
        });
    }

    public function messages()
    {
        return [
            'method.required' => 'Payment method is required.',
            'method.in' => 'Invalid payment method selected.',
            'amount.required' => 'Payment amount is required.',
            'amount.min' => 'Payment amount must be greater than 0.',
            'payment_date.required' => 'Payment date is required.',
            'payment_date.before_or_equal' => 'Payment date cannot be in the future.',
            'reference_number.max' => 'Reference number cannot exceed 100 characters.',
        ];
    }
}
