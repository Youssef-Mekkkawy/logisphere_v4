<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
    {
        $accounts = [
            // Assets
            ['account_code' => '1000', 'account_name' => 'Cash and Bank', 'account_type' => 'asset', 'account_category' => 'cash', 'is_system' => true],
            ['account_code' => '1100', 'account_name' => 'Accounts Receivable', 'account_type' => 'asset', 'account_category' => 'receivables', 'is_system' => true],
            ['account_code' => '1200', 'account_name' => 'Inventory', 'account_type' => 'asset', 'account_category' => 'inventory'],
            ['account_code' => '1300', 'account_name' => 'Fixed Assets', 'account_type' => 'asset', 'account_category' => 'fixed_assets'],

            // Liabilities
            ['account_code' => '2000', 'account_name' => 'Accounts Payable', 'account_type' => 'liability', 'account_category' => 'payables', 'is_system' => true],
            ['account_code' => '2100', 'account_name' => 'Accrued Expenses', 'account_type' => 'liability', 'account_category' => 'accruals'],
            ['account_code' => '2200', 'account_name' => 'Long-term Debt', 'account_type' => 'liability', 'account_category' => 'debt'],

            // Equity
            ['account_code' => '3000', 'account_name' => 'Owner\'s Equity', 'account_type' => 'equity', 'account_category' => 'capital', 'is_system' => true],
            ['account_code' => '3100', 'account_name' => 'Retained Earnings', 'account_type' => 'equity', 'account_category' => 'retained_earnings', 'is_system' => true],

            // Revenue
            ['account_code' => '4000', 'account_name' => 'Freight Revenue', 'account_type' => 'revenue', 'account_category' => 'freight'],
            ['account_code' => '4100', 'account_name' => 'Customs Clearance Revenue', 'account_type' => 'revenue', 'account_category' => 'customs'],
            ['account_code' => '4200', 'account_name' => 'Documentation Fees', 'account_type' => 'revenue', 'account_category' => 'documentation'],

            // Expenses
            ['account_code' => '5000', 'account_name' => 'Salaries and Wages', 'account_type' => 'expense', 'account_category' => 'payroll'],
            ['account_code' => '5100', 'account_name' => 'Freight Expenses', 'account_type' => 'expense', 'account_category' => 'freight'],
            ['account_code' => '5200', 'account_name' => 'Port Charges', 'account_type' => 'expense', 'account_category' => 'port_charges'],
            ['account_code' => '5300', 'account_name' => 'Office Expenses', 'account_type' => 'expense', 'account_category' => 'office'],
        ];

        foreach ($accounts as $account) {
            DB::table('accounts')->insert(array_merge($account, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
