<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize()
    {
        $job = $this->route('job');
        return auth()->check() && $job->status !== 'billed';
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'task_description' => 'required|string|max:500',
            'hours_worked' => 'required|numeric|min:0|max:24',
            'hourly_rate' => 'required|numeric|min:0|max:999.99',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_billable' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $job = $this->route('job');

            // Check if job can be updated
            if ($job && $job->status === 'billed') {
                $validator->errors()->add('status', 'Billed jobs cannot be updated.');
            }

            // Validate hours against date range
            if ($this->start_date && $this->end_date) {
                $startDate = \Carbon\Carbon::parse($this->start_date);
                $endDate = \Carbon\Carbon::parse($this->end_date);
                $daysDiff = $startDate->diffInDays($endDate) + 1;
                $maxHours = $daysDiff * 24;

                if ($this->hours_worked > $maxHours) {
                    $validator->errors()->add(
                        'hours_worked',
                        "Hours worked cannot exceed {$maxHours} for the given date range."
                    );
                }
            }
        });
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'task_description.required' => 'Task description is required.',
            'task_description.max' => 'Description cannot exceed 500 characters.',
            'hours_worked.required' => 'Hours worked is required.',
            'hours_worked.max' => 'Hours worked cannot exceed 24 hours per day.',
            'hourly_rate.required' => 'Hourly rate is required.',
            'hourly_rate.max' => 'Hourly rate cannot exceed 999.99.',
            'start_date.required' => 'Start date is required.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
        ];
    }
}
