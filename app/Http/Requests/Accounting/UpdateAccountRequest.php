<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-accounts');
    }

    public function rules()
    {
        $account = $this->route('account');

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9A-Z\-]+$/',
                Rule::unique('accounts', 'code')->ignore($account)
            ],
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $account = $this->route('account');

            // Prevent changing account type if it has transactions
            if (
                $account && $account->journalEntryLines()->exists() &&
                $this->type !== $account->type
            ) {
                $validator->errors()->add(
                    'type',
                    'Cannot change account type for accounts with transaction history.'
                );
            }

            // Validate account code format based on type
            $codePrefix = substr($this->code, 0, 1);
            $expectedPrefixes = [
                'asset' => ['1'],
                'liability' => ['2'],
                'equity' => ['3'],
                'revenue' => ['4'],
                'expense' => ['5', '6', '7', '8', '9']
            ];

            if (
                isset($expectedPrefixes[$this->type]) &&
                !in_array($codePrefix, $expectedPrefixes[$this->type])
            ) {
                $validator->errors()->add(
                    'code',
                    "Account code should start with " .
                        implode(' or ', $expectedPrefixes[$this->type]) .
                        " for {$this->type} accounts."
                );
            }
        });
    }

    public function messages()
    {
        return [
            'code.required' => 'Account code is required.',
            'code.unique' => 'This account code is already in use.',
            'code.regex' => 'Account code can only contain numbers, letters, and hyphens.',
            'name.required' => 'Account name is required.',
            'type.required' => 'Account type is required.',
            'type.in' => 'Invalid account type selected.',
        ];
    }
}
