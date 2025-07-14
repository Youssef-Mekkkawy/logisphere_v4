<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('view-financial-reports');
    }

    public function rules()
    {
        return [
            'report_type' => 'required|in:profit_loss,balance_sheet,cash_flow,aging,trial_balance,general_ledger',
            'period' => 'nullable|in:today,this_week,this_month,this_quarter,this_year,last_month,last_quarter,last_year,custom',
            'start_date' => 'required_if:period,custom|nullable|date',
            'end_date' => 'required_if:period,custom|nullable|date|after_or_equal:start_date',
            'format' => 'nullable|in:html,pdf,excel,csv',
            'include_zero_balances' => 'boolean',
            'comparison' => 'boolean',
            'account_id' => 'nullable|exists:accounts,id',
            'company_id' => 'nullable|exists:companies,id',
            'employee_id' => 'nullable|exists:employees,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate date range for custom period
            if ($this->period === 'custom') {
                if (!$this->start_date || !$this->end_date) {
                    $validator->errors()->add(
                        'period',
                        'Start date and end date are required for custom period.'
                    );
                }

                if ($this->start_date && $this->end_date) {
                    $start = \Carbon\Carbon::parse($this->start_date);
                    $end = \Carbon\Carbon::parse($this->end_date);

                    if ($start->diffInDays($end) > 365) {
                        $validator->errors()->add(
                            'end_date',
                            'Date range cannot exceed 365 days.'
                        );
                    }
                }
            }

            // Validate account for general ledger
            if ($this->report_type === 'general_ledger' && !$this->account_id) {
                $validator->errors()->add(
                    'account_id',
                    'Account selection is required for general ledger report.'
                );
            }
        });
    }

    public function messages()
    {
        return [
            'report_type.required' => 'Report type is required.',
            'report_type.in' => 'Invalid report type selected.',
            'start_date.required_if' => 'Start date is required for custom period.',
            'end_date.required_if' => 'End date is required for custom period.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
            'format.in' => 'Invalid export format selected.',
            'account_id.exists' => 'Selected account is invalid.',
            'company_id.exists' => 'Selected company is invalid.',
            'employee_id.exists' => 'Selected employee is invalid.',
        ];
    }
}
