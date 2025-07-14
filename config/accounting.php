<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Accounting Configuration
    |--------------------------------------------------------------------------
    */

    'currency' => [
        'code' => env('ACCOUNTING_CURRENCY_CODE', 'USD'),
        'symbol' => env('ACCOUNTING_CURRENCY_SYMBOL', '$'),
        'position' => env('ACCOUNTING_CURRENCY_POSITION', 'before'), // before or after
    ],

    'tax' => [
        'default_rate' => env('ACCOUNTING_DEFAULT_TAX_RATE', 14), // 14%
        'inclusive' => env('ACCOUNTING_TAX_INCLUSIVE', false),
    ],

    'invoice' => [
        'prefix' => env('INVOICE_PREFIX', 'INV'),
        'start_number' => env('INVOICE_START_NUMBER', 1),
        'auto_number' => env('INVOICE_AUTO_NUMBER', true),
        'due_days' => env('INVOICE_DEFAULT_DUE_DAYS', 30),
    ],

    'expense' => [
        'prefix' => env('EXPENSE_PREFIX', 'EXP'),
        'require_receipt' => env('EXPENSE_REQUIRE_RECEIPT', false),
        'max_file_size' => env('EXPENSE_MAX_FILE_SIZE', 5120), // 5MB in KB
    ],

    'advance' => [
        'prefix' => env('ADVANCE_PREFIX', 'ADV'),
        'max_percentage' => env('ADVANCE_MAX_PERCENTAGE', 75), // % of salary
        'require_approval' => env('ADVANCE_REQUIRE_APPROVAL', true),
    ],

    'reports' => [
        'default_period' => env('REPORTS_DEFAULT_PERIOD', 'this_month'),
        'cache_duration' => env('REPORTS_CACHE_DURATION', 60), // minutes
    ],

    'notifications' => [
        'overdue_invoices' => env('NOTIFY_OVERDUE_INVOICES', true),
        'low_balance' => env('NOTIFY_LOW_BALANCE', true),
        'expense_approval' => env('NOTIFY_EXPENSE_APPROVAL', true),
    ],
];
