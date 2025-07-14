<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'task_description' => 'required|string|max:500',
            'hours_worked' => 'required|numeric|min:0|max:24',
            'hourly_rate' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_billable' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'task_description.required' => 'Task description is required.',
            'hours_worked.max' => 'Hours worked cannot exceed 24 hours per day.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
        ];
    }
}
