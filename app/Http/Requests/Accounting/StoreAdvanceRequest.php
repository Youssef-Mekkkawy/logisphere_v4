<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvanceRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:salary,travel,emergency,project,other',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'nullable|date|after:today',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->employee_id && $this->amount) {
                $employee = \App\Models\Employee::find($this->employee_id);
                if ($employee) {
                    $maxPercentage = config('accounting.advance.max_percentage', 75);
                    $maxAmount = ($employee->salary * $maxPercentage) / 100;

                    if ($this->amount > $maxAmount) {
                        $validator->errors()->add('amount', "Advance cannot exceed {$maxPercentage}% of employee salary (\${$maxAmount}).");
                    }
                }
            }
        });
    }
}
