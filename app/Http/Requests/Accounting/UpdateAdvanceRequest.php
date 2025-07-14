<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvanceRequest extends FormRequest
{
    public function authorize()
    {
        $advance = $this->route('advance');
        return auth()->check() && in_array($advance->status, ['issued', 'partially_repaid']);
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
            $advance = $this->route('advance');

            // Check if new amount is valid (cannot be less than already repaid)
            if ($this->amount < $advance->repaid_amount) {
                $validator->errors()->add(
                    'amount',
                    'Advance amount cannot be less than already repaid amount ($' .
                        number_format($advance->repaid_amount, 2) . ')'
                );
            }

            // Validate against employee salary if different employee
            if ($this->employee_id && $this->employee_id != $advance->employee_id) {
                $employee = \App\Models\Employee::find($this->employee_id);
                if ($employee) {
                    $maxPercentage = config('accounting.advance.max_percentage', 75);
                    $maxAmount = ($employee->salary * $maxPercentage) / 100;

                    if ($this->amount > $maxAmount) {
                        $validator->errors()->add(
                            'amount',
                            "Advance cannot exceed {$maxPercentage}% of employee salary ({$maxAmount})"
                        );
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'type.required' => 'Advance type is required.',
            'type.in' => 'Invalid advance type selected.',
            'amount.required' => 'Advance amount is required.',
            'amount.min' => 'Amount must be greater than 0.',
            'due_date.after' => 'Due date must be in the future.',
            'reason.required' => 'Reason for advance is required.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
        ];
    }
}
