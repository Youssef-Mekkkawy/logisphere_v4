<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class RepayAdvanceRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'repayment_date' => 'required|date',
            'method' => 'required|in:salary_deduction,cash,bank_transfer,other',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->route('advance') && $this->amount) {
                $advance = $this->route('advance');
                if ($this->amount > $advance->balance) {
                    $validator->errors()->add('amount', 'Repayment amount cannot exceed the outstanding balance.');
                }
            }
        });
    }
}
