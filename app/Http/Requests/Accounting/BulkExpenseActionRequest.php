<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class BulkExpenseActionRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('approve-expenses');
    }

    public function rules()
    {
        return [
            'action' => 'required|in:approve,reject,delete',
            'expense_ids' => 'required|array|min:1',
            'expense_ids.*' => 'required|exists:expenses,id',
            'reason' => 'required_if:action,reject|nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate that all expenses can be acted upon
            $expenses = \App\Models\Expense::whereIn('id', $this->expense_ids)->get();
            
            foreach ($expenses as $expense) {
                if ($this->action === 'approve' && $expense->status !== 'pending') {
                    $validator->errors()->add('expense_ids', 
                        "Expense {$expense->expense_number} is not pending approval."
                    );
                }
                
                if ($this->action === 'reject' && $expense->status !== 'pending') {
                    $validator->errors()->add('expense_ids', 
                        "Expense {$expense->expense_number} is not pending approval."
                    );
                }
                
                if ($this->action === 'delete' && $expense->status === 'paid') {
                    $validator->errors()->add('expense_ids', 
                        "Cannot delete paid expense {$expense->expense_number}."
                    );
                }
            }
        });
    }

    public function messages()
    {
        return [
            'action.required' => 'Action is required.',
            'action.in' => 'Invalid action selected.',
            'expense_ids.required' => 'At least one expense must be selected.',
            'expense_ids.min' => 'At least one expense must be selected.',
            'expense_ids.*.exists' => 'One or more selected expenses are invalid.',
            'reason.required_if' => 'Reason is required when rejecting expenses.',
        ];
    }
}
