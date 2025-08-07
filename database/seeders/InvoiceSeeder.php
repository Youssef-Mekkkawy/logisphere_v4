<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Management\Account\Invoice;
use App\Models\Management\Account\InvoiceDetail;
use App\Models\Management\Company;
use App\Models\Management\Shipment;
use App\Models\Management\Account\Account;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        $companies = Company::where('type', 'Client')->get();
        $shipments = Shipment::all();
        $accounts = Account::all();

        if ($companies->isEmpty() || $accounts->isEmpty()) {
            $this->command->warn('Missing required data. Please seed companies and accounts first.');
            return;
        }

        // Create Sales Invoices
        $invoices = [
            [
                'invoice_number' => Invoice::generateInvoiceNumber('sales'),
                'invoice_type' => 'sales',
                'company_id' => $companies->first()->id,
                'shipment_id' => $shipments->first()?->id,
                'invoice_date' => now()->subDays(15),
                'due_date' => now()->addDays(15),
                'payment_terms' => '30 days',
                'currency' => 'USD',
                'exchange_rate' => 1.0000,
                'status' => 'Sent',
                'notes' => 'Thank you for your business',
                'created_by' => 1,
                'sent_at' => now()->subDays(14),
            ],
            [
                'invoice_number' => Invoice::generateInvoiceNumber('sales'),
                'invoice_type' => 'sales',
                'company_id' => $companies->skip(1)->first()?->id ?? $companies->first()->id,
                'shipment_id' => $shipments->skip(1)->first()?->id,
                'invoice_date' => now()->subDays(30),
                'due_date' => now()->subDays(15),
                'payment_terms' => '15 days',
                'currency' => 'USD',
                'exchange_rate' => 1.0000,
                'status' => 'Overdue',
                'notes' => 'Payment overdue - please remit immediately',
                'created_by' => 1,
                'sent_at' => now()->subDays(29),
            ]
        ];

        foreach ($invoices as $invoiceData) {
            $invoice = Invoice::create($invoiceData);

            // Create invoice details
            $details = [
                [
                    'invoice_id' => $invoice->id,
                    'line_number' => 1,
                    'item_type' => 'service',
                    'item_code' => 'FREIGHT',
                    'description' => 'Ocean Freight Charges',
                    'detailed_description' => 'Container freight from origin to destination port',
                    'account_id' => $accounts->where('account_code', '4000')->first()?->id ?? 1,
                    'quantity' => 1.00,
                    'unit_of_measure' => 'per container',
                    'unit_price' => 1200.00,
                    'line_total' => 1200.00,
                    'tax_type' => 'VAT',
                    'tax_percentage' => 14.00,
                    'tax_amount' => 168.00,
                    'final_amount' => 1368.00,
                    'currency' => 'USD',
                    'exchange_rate' => 1.0000,
                    'shipment_id' => $invoice->shipment_id,
                ],
                [
                    'invoice_id' => $invoice->id,
                    'line_number' => 2,
                    'item_type' => 'service',
                    'item_code' => 'CUSTOMS',
                    'description' => 'Customs Clearance',
                    'detailed_description' => 'Import customs clearance and documentation',
                    'account_id' => $accounts->where('account_code', '4100')->first()?->id ?? 1,
                    'quantity' => 1.00,
                    'unit_of_measure' => 'per shipment',
                    'unit_price' => 350.00,
                    'line_total' => 350.00,
                    'tax_type' => 'VAT',
                    'tax_percentage' => 14.00,
                    'tax_amount' => 49.00,
                    'final_amount' => 399.00,
                    'currency' => 'USD',
                    'exchange_rate' => 1.0000,
                    'shipment_id' => $invoice->shipment_id,
                ],
                [
                    'invoice_id' => $invoice->id,
                    'line_number' => 3,
                    'item_type' => 'charge',
                    'item_code' => 'DOC',
                    'description' => 'Documentation Fees',
                    'detailed_description' => 'Bill of lading and shipping documents',
                    'account_id' => $accounts->where('account_code', '4200')->first()?->id ?? 1,
                    'quantity' => 1.00,
                    'unit_of_measure' => 'per shipment',
                    'unit_price' => 75.00,
                    'line_total' => 75.00,
                    'tax_type' => 'VAT',
                    'tax_percentage' => 14.00,
                    'tax_amount' => 10.50,
                    'final_amount' => 85.50,
                    'currency' => 'USD',
                    'exchange_rate' => 1.0000,
                    'shipment_id' => $invoice->shipment_id,
                ]
            ];

            foreach ($details as $detailData) {
                InvoiceDetail::create($detailData);
            }

            // Calculate totals
            $invoice->calculateTotals();
        }

        $this->command->info('Invoices and invoice details seeded successfully!');
    }
}
