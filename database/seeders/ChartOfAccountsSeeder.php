<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;


class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Cash', 'type' => 'asset', 'category' => 'Current Assets'],
            ['code' => '1100', 'name' => 'Bank Account', 'type' => 'asset', 'category' => 'Current Assets'],
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'category' => 'Current Assets'],
            ['code' => '1300', 'name' => 'Inventory', 'type' => 'asset', 'category' => 'Current Assets'],
            ['code' => '1500', 'name' => 'Equipment', 'type' => 'asset', 'category' => 'Fixed Assets'],
            ['code' => '1600', 'name' => 'Vehicles', 'type' => 'asset', 'category' => 'Fixed Assets'],

            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'category' => 'Current Liabilities'],
            ['code' => '2100', 'name' => 'Employee Advances Payable', 'type' => 'liability', 'category' => 'Current Liabilities'],
            ['code' => '2200', 'name' => 'Tax Payable', 'type' => 'liability', 'category' => 'Current Liabilities'],
            ['code' => '2500', 'name' => 'Long-term Debt', 'type' => 'liability', 'category' => 'Long-term Liabilities'],

            // Equity
            ['code' => '3000', 'name' => 'Owner Equity', 'type' => 'equity', 'category' => 'Equity'],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity', 'category' => 'Equity'],

            // Revenue
            ['code' => '4000', 'name' => 'Service Revenue', 'type' => 'revenue', 'category' => 'Operating Revenue'],
            ['code' => '4100', 'name' => 'Shipping Revenue', 'type' => 'revenue', 'category' => 'Operating Revenue'],
            ['code' => '4200', 'name' => 'Customs Clearance Revenue', 'type' => 'revenue', 'category' => 'Operating Revenue'],

            // Expenses
            ['code' => '5000', 'name' => 'Fuel Expenses', 'type' => 'expense', 'category' => 'Operating Expenses'],
            ['code' => '5100', 'name' => 'Port Charges', 'type' => 'expense', 'category' => 'Operating Expenses'],
            ['code' => '5200', 'name' => 'Employee Salaries', 'type' => 'expense', 'category' => 'Operating Expenses'],
            ['code' => '5300', 'name' => 'Office Supplies', 'type' => 'expense', 'category' => 'Operating Expenses'],
            ['code' => '5400', 'name' => 'Travel Expenses', 'type' => 'expense', 'category' => 'Operating Expenses'],
            ['code' => '5500', 'name' => 'Insurance', 'type' => 'expense', 'category' => 'Operating Expenses'],
            ['code' => '5600', 'name' => 'Utilities', 'type' => 'expense', 'category' => 'Operating Expenses']
        ];

        foreach ($accounts as $account) {
            Account::firstOrCreate(
                ['code' => $account['code']],
                $account
            );
        }
    }
}
