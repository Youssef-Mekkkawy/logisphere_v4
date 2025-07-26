<?php
// 'account_id' => null, // TODO: Set when Account model exists

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            // Customs Clearance Services
            [
                'service_code' => 'CUS001',
                'service_name' => 'Import Customs Clearance',
                'service_category' => 'Customs Clearance',
                'description' => 'Complete import customs clearance processing',
                'detailed_description' => 'Full import customs clearance including document preparation, duty calculation, and customs office liaison',
                'billing_type' => 'Fixed',
                'base_rate' => 150.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per shipment',
                'minimum_charge' => 100.00,
                'maximum_charge' => 500.00,
                'tax_type' => 'VAT',
                'tax_percentage' => 14.00,
                'is_mandatory' => true,
                'is_billable' => true,
                'requires_approval' => false,
                'required_documents' => [
                    'Commercial Invoice',
                    'Packing List',
                    'Bill of Lading',
                    'Import License'
                ],
                'estimated_duration_hours' => 24,
                'service_conditions' => 'All documents must be original or certified copies',
                'applicable_cargo_types' => [
                    'General Cargo',
                    'Container (FCL)',
                    'Container (LCL)'
                ],
                'service_provider' => 'Internal',
                'status' => 'Active',
                'notes' => 'Standard import clearance service'
            ],
            [
                'service_code' => 'CUS002',
                'service_name' => 'Export Customs Clearance',
                'service_category' => 'Customs Clearance',
                'description' => 'Export customs clearance and documentation',
                'detailed_description' => 'Complete export customs clearance including export declaration, certificate of origin, and shipping permits',
                'billing_type' => 'Fixed',
                'base_rate' => 120.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per shipment',
                'minimum_charge' => 80.00,
                'maximum_charge' => 300.00,
                'tax_type' => 'VAT',
                'tax_percentage' => 14.00,
                'is_mandatory' => true,
                'is_billable' => true,
                'requires_approval' => false,
                'required_documents' => [
                    'Commercial Invoice',
                    'Packing List',
                    'Export License',
                    'Certificate of Origin'
                ],
                'estimated_duration_hours' => 12,
                'service_provider' => 'Internal',
                'status' => 'Active'
            ],

            // Transportation Services
            [
                'service_code' => 'TRN001',
                'service_name' => 'Port to Warehouse Transportation',
                'service_category' => 'Transportation',
                'description' => 'Container transportation from port to warehouse',
                'detailed_description' => 'Door-to-door container transportation service including loading, transit, and delivery',
                'billing_type' => 'Variable',
                'base_rate' => 2.50,
                'rate_currency' => 'USD',
                'rate_unit' => 'per kilometer',
                'minimum_charge' => 50.00,
                'maximum_charge' => 500.00,
                'tax_type' => 'Service Tax',
                'tax_percentage' => 10.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => false,
                'required_documents' => [
                    'Delivery Order',
                    'Container Release'
                ],
                'estimated_duration_hours' => 8,
                'applicable_cargo_types' => [
                    'Container (FCL)',
                    'Container (LCL)'
                ],
                'service_provider' => 'Both',
                'status' => 'Active'
            ],
            [
                'service_code' => 'TRN002',
                'service_name' => 'Warehouse to Port Transportation',
                'service_category' => 'Transportation',
                'description' => 'Container transportation from warehouse to port',
                'detailed_description' => 'Pick-up and delivery service from customer warehouse to port terminal',
                'billing_type' => 'Variable',
                'base_rate' => 2.50,
                'rate_currency' => 'USD',
                'rate_unit' => 'per kilometer',
                'minimum_charge' => 50.00,
                'maximum_charge' => 500.00,
                'tax_type' => 'Service Tax',
                'tax_percentage' => 10.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => false,
                'estimated_duration_hours' => 6,
                'service_provider' => 'Both',
                'status' => 'Active'
            ],

            // Warehousing Services
            [
                'service_code' => 'WHR001',
                'service_name' => 'General Cargo Storage',
                'service_category' => 'Warehousing',
                'description' => 'General cargo warehousing and storage',
                'detailed_description' => 'Secure warehousing facility with 24/7 security, climate control, and inventory management',
                'billing_type' => 'Variable',
                'base_rate' => 5.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per cubic meter per day',
                'minimum_charge' => 25.00,
                'tax_type' => 'VAT',
                'tax_percentage' => 14.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => false,
                'estimated_duration_hours' => 1,
                'applicable_cargo_types' => [
                    'General Cargo',
                    'Container (LCL)',
                    'Break Bulk'
                ],
                'service_provider' => 'Internal',
                'status' => 'Active'
            ],
            [
                'service_code' => 'WHR002',
                'service_name' => 'Cold Storage',
                'service_category' => 'Warehousing',
                'description' => 'Refrigerated cargo storage',
                'detailed_description' => 'Temperature-controlled storage facility for perishable goods with monitoring systems',
                'billing_type' => 'Variable',
                'base_rate' => 12.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per cubic meter per day',
                'minimum_charge' => 50.00,
                'tax_type' => 'VAT',
                'tax_percentage' => 14.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => true,
                'applicable_cargo_types' => [
                    'Refrigerated Cargo'
                ],
                'service_provider' => 'External',
                'status' => 'Active'
            ],

            // Documentation Services
            [
                'service_code' => 'DOC001',
                'service_name' => 'Bill of Lading Preparation',
                'service_category' => 'Documentation',
                'description' => 'Preparation and issuance of Bill of Lading',
                'detailed_description' => 'Professional preparation of sea waybill and bill of lading documents with accuracy verification',
                'billing_type' => 'Fixed',
                'base_rate' => 25.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per document',
                'tax_type' => 'Service Tax',
                'tax_percentage' => 5.00,
                'is_mandatory' => true,
                'is_billable' => true,
                'requires_approval' => false,
                'required_documents' => [
                    'Booking Confirmation',
                    'Shipper Instructions'
                ],
                'estimated_duration_hours' => 2,
                'service_provider' => 'Internal',
                'status' => 'Active'
            ],
            [
                'service_code' => 'DOC002',
                'service_name' => 'Certificate of Origin',
                'service_category' => 'Documentation',
                'description' => 'Certificate of Origin processing',
                'detailed_description' => 'Processing and authentication of certificate of origin from relevant chamber of commerce',
                'billing_type' => 'Fixed',
                'base_rate' => 35.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per certificate',
                'tax_type' => 'Service Tax',
                'tax_percentage' => 5.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => false,
                'required_documents' => [
                    'Commercial Invoice',
                    'Manufacturer Certificate'
                ],
                'estimated_duration_hours' => 48,
                'service_provider' => 'External',
                'status' => 'Active'
            ],

            // Insurance Services
            [
                'service_code' => 'INS001',
                'service_name' => 'Marine Cargo Insurance',
                'service_category' => 'Insurance',
                'description' => 'Marine cargo insurance coverage',
                'detailed_description' => 'Comprehensive marine insurance coverage against all risks during transit',
                'billing_type' => 'Percentage',
                'base_rate' => 0.15,
                'rate_currency' => 'USD',
                'rate_unit' => 'percentage of cargo value',
                'minimum_charge' => 50.00,
                'maximum_charge' => 5000.00,
                'tax_type' => 'Exempt',
                'tax_percentage' => 0.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => true,
                'required_documents' => [
                    'Commercial Invoice',
                    'Packing List',
                    'Bill of Lading'
                ],
                'estimated_duration_hours' => 24,
                'service_provider' => 'External',
                'status' => 'Active'
            ],

            // Inspection Services
            [
                'service_code' => 'INP001',
                'service_name' => 'Pre-shipment Inspection',
                'service_category' => 'Inspection',
                'description' => 'Pre-shipment cargo inspection',
                'detailed_description' => 'Quality and quantity inspection of cargo before shipment by certified inspectors',
                'billing_type' => 'Fixed',
                'base_rate' => 200.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per inspection',
                'minimum_charge' => 150.00,
                'maximum_charge' => 1000.00,
                'tax_type' => 'VAT',
                'tax_percentage' => 14.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => true,
                'estimated_duration_hours' => 8,
                'service_provider' => 'External',
                'status' => 'Active'
            ],

            // Cargo Handling Services
            [
                'service_code' => 'CGH001',
                'service_name' => 'Container Loading',
                'service_category' => 'Cargo Handling',
                'description' => 'Container stuffing and loading service',
                'detailed_description' => 'Professional container stuffing with proper securing and weight distribution',
                'billing_type' => 'Fixed',
                'base_rate' => 80.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'minimum_charge' => 60.00,
                'maximum_charge' => 200.00,
                'tax_type' => 'Service Tax',
                'tax_percentage' => 10.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => false,
                'estimated_duration_hours' => 4,
                'applicable_cargo_types' => [
                    'Container (FCL)',
                    'Container (LCL)'
                ],
                'service_provider' => 'Both',
                'status' => 'Active'
            ],
            [
                'service_code' => 'CGH002',
                'service_name' => 'Container Unloading',
                'service_category' => 'Cargo Handling',
                'description' => 'Container destuffing and unloading service',
                'detailed_description' => 'Professional container destuffing with inventory checking and damage assessment',
                'billing_type' => 'Fixed',
                'base_rate' => 70.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'minimum_charge' => 50.00,
                'maximum_charge' => 180.00,
                'tax_type' => 'Service Tax',
                'tax_percentage' => 10.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => false,
                'estimated_duration_hours' => 3,
                'service_provider' => 'Both',
                'status' => 'Active'
            ],

            // Port Services
            [
                'service_code' => 'PRT001',
                'service_name' => 'Port Handling Charges',
                'service_category' => 'Port Services',
                'description' => 'Port terminal handling charges',
                'detailed_description' => 'Standard port terminal handling and processing charges',
                'billing_type' => 'Fixed',
                'base_rate' => 120.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'tax_type' => 'VAT',
                'tax_percentage' => 14.00,
                'is_mandatory' => true,
                'is_billable' => true,
                'requires_approval' => false,
                'estimated_duration_hours' => 6,
                'service_provider' => 'External',
                'status' => 'Active'
            ],

            // Consulting Services
            [
                'service_code' => 'CON001',
                'service_name' => 'Logistics Consulting',
                'service_category' => 'Consulting',
                'description' => 'Professional logistics consulting',
                'detailed_description' => 'Expert logistics consulting for supply chain optimization and cost reduction',
                'billing_type' => 'Hourly',
                'base_rate' => 75.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per hour',
                'minimum_charge' => 200.00,
                'tax_type' => 'Service Tax',
                'tax_percentage' => 15.00,
                'is_mandatory' => false,
                'is_billable' => true,
                'requires_approval' => true,
                'estimated_duration_hours' => 8,
                'service_provider' => 'Internal',
                'status' => 'Active'
            ],

            // Test Inactive Service
            [
                'service_code' => 'TST001',
                'service_name' => 'Inactive Test Service',
                'service_category' => 'Other',
                'description' => 'Test service for system testing',
                'billing_type' => 'Fixed',
                'base_rate' => 10.00,
                'rate_currency' => 'USD',
                'tax_percentage' => 0.00,
                'is_mandatory' => false,
                'is_billable' => false,
                'requires_approval' => false,
                'service_provider' => 'Internal',
                'status' => 'Inactive',
                'notes' => 'This is a test service for development purposes'
            ]
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
            $this->command->info("Created service: {$serviceData['service_name']}");
        }

        $this->command->info('Services seeded successfully!');
    }
}
