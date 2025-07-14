<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'account_id' => 'required|exists:accounts,id',
            'employee_id' => 'nullable|exists:employees,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'category' => [
                'required',
                'string',
                'in:Fuel,Office Supplies,Travel,Port Charges,Insurance,Utilities,Maintenance,Professional Services,Marketing,Training,Other'
            ],
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'expense_date' => 'required|date|before_or_equal:today',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'notes' => 'nullable|string|max:1000',
            'is_reimbursable' => 'boolean',
            'approval_required' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'account_id.required' => 'Please select an expense account.',
            'account_id.exists' => 'Selected account is invalid.',
            'category.required' => 'Please select an expense category.',
            'category.in' => 'Invalid expense category selected.',
            'description.required' => 'Expense description is required.',
            'description.max' => 'Description cannot exceed 500 characters.',
            'amount.required' => 'Expense amount is required.',
            'amount.min' => 'Amount must be greater than 0.',
            'amount.max' => 'Amount cannot exceed 999,999.99.',
            'expense_date.required' => 'Expense date is required.',
            'expense_date.before_or_equal' => 'Expense date cannot be in the future.',
            'receipt.mimes' => 'Receipt must be a JPG, PNG, or PDF file.',
            'receipt.max' => 'Receipt file cannot exceed 5MB.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if receipt is required for this amount
            $requireReceiptAmount = config('accounting.expense.require_receipt_amount', 100);

            if ($this->amount >= $requireReceiptAmount && !$this->hasFile('receipt')) {
                $validator->errors()->add('receipt', "Receipt is required for expenses over {$requireReceiptAmount}.");
            }

            // Validate account type
            if ($this->account_id) {
                $account = \App\Models\Account::find($this->account_id);
                if ($account && $account->type !== 'expense') {
                    $validator->errors()->add('account_id', 'Selected account must be an expense account.');
                }
            }
        });
    }
}
