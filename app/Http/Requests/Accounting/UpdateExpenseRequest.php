<?php

namespace App\Http\Requests\Accounting;

class UpdateExpenseRequest extends StoreExpenseRequest
{
    public function authorize()
    {
        $expense = $this->route('expense');
        return auth()->check() && in_array($expense->status, ['pending', 'rejected']);
    }

    public function rules()
    {
        $rules = parent::rules();

        // Make receipt optional for updates (might already have one)
        $rules['receipt'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $expense = $this->route('expense');

            // Check if expense can be updated
            if ($expense && !in_array($expense->status, ['pending', 'rejected'])) {
                $validator->errors()->add('status', 'Only pending or rejected expenses can be updated.');
            }

            // Check receipt requirement (allow update if receipt already exists)
            $requireReceiptAmount = config('accounting.expense.require_receipt_amount', 100);

            if (
                $this->amount >= $requireReceiptAmount &&
                !$this->hasFile('receipt') &&
                !$expense->receipt_path
            ) {
                $validator->errors()->add('receipt', "Receipt is required for expenses over {$requireReceiptAmount}.");
            }
        });
    }
}
