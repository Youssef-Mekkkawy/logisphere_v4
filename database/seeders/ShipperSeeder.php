<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shipper;
use App\Models\Country;

class ShipperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some countries for foreign key (with fallback)
        $egypt = Country::where('name', 'Egypt')->first();
        $usa = Country::where('name', 'United States')->orWhere('name', 'USA')->first();
        $germany = Country::where('name', 'Germany')->first();
        $uae = Country::where('name', 'United Arab Emirates')->orWhere('name', 'UAE')->first();
        $china = Country::where('name', 'China')->first();
        $uk = Country::where('name', 'United Kingdom')->orWhere('name', 'UK')->first();
        $singapore = Country::where('name', 'Singapore')->first();
        $netherlands = Country::where('name', 'Netherlands')->first();

        // Create missing countries if they don't exist
        if (!$egypt) {
            $egypt = Country::create(['name' => 'Egypt', 'code' => 'EG', 'status' => 'Active']);
        }
        if (!$usa) {
            $usa = Country::create(['name' => 'United States', 'code' => 'US', 'status' => 'Active']);
        }
        if (!$germany) {
            $germany = Country::create(['name' => 'Germany', 'code' => 'DE', 'status' => 'Active']);
        }
        if (!$uae) {
            $uae = Country::create(['name' => 'United Arab Emirates', 'code' => 'AE', 'status' => 'Active']);
        }
        if (!$china) {
            $china = Country::create(['name' => 'China', 'code' => 'CN', 'status' => 'Active']);
        }
        if (!$uk) {
            $uk = Country::create(['name' => 'United Kingdom', 'code' => 'GB', 'status' => 'Active']);
        }
        if (!$singapore) {
            $singapore = Country::create(['name' => 'Singapore', 'code' => 'SG', 'status' => 'Active']);
        }
        if (!$netherlands) {
            $netherlands = Country::create(['name' => 'Netherlands', 'code' => 'NL', 'status' => 'Active']);
        }

        $shippers = [
            [
                'shipper_code' => 'EG001',
                'shipper_name' => 'Alexandria Export Company',
                'company_name' => 'Alexandria Export Company Ltd.',
                'shipper_type' => 'Exporter',
                'business_license' => 'EG-EXP-2020-001',
                'tax_id' => 'EG-TAX-123456789',
                'address' => '15 El-Hurriya Avenue, Raml Station',
                'city' => 'Alexandria',
                'state_province' => 'Alexandria Governorate',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'postal_code' => '21111',
                'latitude' => 31.2001,
                'longitude' => 29.9187,
                'contact_person' => 'Ahmed Mahmoud',
                'contact_phone' => '+20-3-487-2000',
                'contact_email' => 'ahmed.mahmoud@alexexport.com',
                'website' => 'https://www.alexandriaexport.com',
                'alternative_phone' => '+20-12-345-6789',
                'emergency_contact' => 'Mohamed Ali - +20-10-987-6543',
                'industry_type' => 'Agricultural Products',
                'specialization' => 'Cotton, Rice, and Citrus fruits export',
                'cargo_types' => [
                    'Agricultural Products',
                    'Textiles',
                    'Food Products',
                    'Raw Materials'
                ],
                'trade_routes' => [
                    'Egypt to Europe',
                    'Egypt to Middle East',
                    'Egypt to Asia'
                ],
                'certifications' => [
                    'ISO 9001:2015',
                    'HACCP',
                    'Organic Certification',
                    'Fair Trade Certified'
                ],
                'established_year' => 1985,
                'annual_volume' => 25000,
                'services_offered' => [
                    'Export Documentation',
                    'Quality Control',
                    'Packaging Services',
                    'Logistics Coordination',
                    'Customs Clearance'
                ],
                'equipment_owned' => [
                    'Refrigerated Containers',
                    'Standard Containers',
                    'Packaging Equipment',
                    'Quality Testing Lab'
                ],
                'preferred_ports' => [1, 2], // Alexandria and Suez ports
                'operating_regions' => [
                    'Mediterranean',
                    'Middle East',
                    'Europe',
                    'Asia'
                ],
                'operating_hours' => 'Sun-Thu 8AM-6PM',
                'time_zone' => 'Africa/Cairo',
                'credit_rating' => 'A',
                'payment_terms' => 'Net 30',
                'credit_limit' => 500000.00,
                'currency_preference' => 'USD',
                'insurance_coverage' => true,
                'bank_name' => 'National Bank of Egypt',
                'bank_account' => 'NBE-12345678',
                'required_documents' => [
                    'Certificate of Origin',
                    'Phytosanitary Certificate',
                    'Quality Certificate',
                    'Packing List',
                    'Commercial Invoice'
                ],
                'customs_broker' => false,
                'freight_forwarder' => false,
                'dangerous_goods_certified' => false,
                'customs_code' => 'EG-CUSTOM-001',
                'regulatory_compliance' => [
                    'Egyptian Export Council',
                    'Ministry of Agriculture',
                    'Chamber of Commerce'
                ],
                'on_time_delivery_rate' => 95.5,
                'damage_rate' => 0.02,
                'total_shipments' => 450,
                'customer_satisfaction' => 4.7,
                'last_audit_date' => '2024-03-15',
                'audit_result' => 'Passed',
                'preferred_carriers' => [
                    'Maersk',
                    'MSC',
                    'CMA CGM'
                ],
                'preferred_incoterms' => [
                    'FOB',
                    'CIF',
                    'CFR'
                ],
                'track_and_trace_required' => true,
                'email_notifications' => true,
                'sms_notifications' => false,
                'notification_language' => 'en',
                'status' => 'Active',
                'notes' => 'Reliable exporter with excellent track record in agricultural products.',
                'sales_representative' => 'Sarah Ahmed',
                'account_manager' => 'Omar Hassan',
                'contract_start_date' => '2024-01-01',
                'contract_end_date' => '2024-12-31'
            ],
            [
                'shipper_code' => 'US002',
                'shipper_name' => 'Pacific Trade Solutions',
                'company_name' => 'Pacific Trade Solutions Inc.',
                'shipper_type' => 'Trading Company',
                'business_license' => 'US-CA-2019-5566',
                'tax_id' => 'US-EIN-98-7654321',
                'address' => '1250 Harbor Blvd, Suite 300',
                'city' => 'Los Angeles',
                'state_province' => 'California',
                'country' => 'United States',
                'country_id' => $usa?->id,
                'postal_code' => '90731',
                'latitude' => 33.7701,
                'longitude' => -118.2920,
                'contact_person' => 'Jennifer Williams',
                'contact_phone' => '+1-310-555-8800',
                'contact_email' => 'jennifer.williams@pacifictrade.com',
                'website' => 'https://www.pacifictrade.com',
                'alternative_phone' => '+1-310-555-8801',
                'emergency_contact' => 'Robert Chen - +1-310-555-9999',
                'industry_type' => 'General Trading',
                'specialization' => 'Electronics, Machinery, and Consumer Goods',
                'cargo_types' => [
                    'Electronics',
                    'Machinery',
                    'Consumer Goods',
                    'Automotive Parts',
                    'Textiles'
                ],
                'trade_routes' => [
                    'USA to Asia',
                    'USA to Europe',
                    'Trans-Pacific',
                    'Americas'
                ],
                'certifications' => [
                    'ISO 9001:2015',
                    'C-TPAT Certified',
                    'FMC Licensed',
                    'CBP Trusted Trader'
                ],
                'established_year' => 2005,
                'annual_volume' => 85000,
                'services_offered' => [
                    'Import/Export Services',
                    'Freight Forwarding',
                    'Customs Brokerage',
                    'Supply Chain Management',
                    'Trade Finance'
                ],
                'equipment_owned' => [
                    'Container Fleet',
                    'Warehouse Facilities',
                    'Handling Equipment',
                    'IT Systems'
                ],
                'preferred_ports' => [4], // Los Angeles port
                'operating_regions' => [
                    'Pacific',
                    'Americas',
                    'Asia',
                    'Europe'
                ],
                'operating_hours' => '24/7',
                'time_zone' => 'America/Los_Angeles',
                'credit_rating' => 'A+',
                'payment_terms' => 'Net 15',
                'credit_limit' => 2000000.00,
                'currency_preference' => 'USD',
                'insurance_coverage' => true,
                'bank_name' => 'Wells Fargo Bank',
                'bank_account' => 'WF-987654321',
                'required_documents' => [
                    'Bill of Lading',
                    'Commercial Invoice',
                    'Packing List',
                    'Insurance Certificate',
                    'Certificate of Origin'
                ],
                'customs_broker' => true,
                'freight_forwarder' => true,
                'dangerous_goods_certified' => true,
                'customs_code' => 'US-CBP-002',
                'regulatory_compliance' => [
                    'US Customs and Border Protection',
                    'Federal Maritime Commission',
                    'Department of Commerce'
                ],
                'on_time_delivery_rate' => 98.2,
                'damage_rate' => 0.01,
                'total_shipments' => 1250,
                'customer_satisfaction' => 4.9,
                'last_audit_date' => '2024-02-20',
                'audit_result' => 'Excellent',
                'preferred_carriers' => [
                    'COSCO',
                    'Evergreen',
                    'Yang Ming',
                    'Hapag-Lloyd'
                ],
                'preferred_incoterms' => [
                    'EXW',
                    'FOB',
                    'CIF',
                    'DDP'
                ],
                'track_and_trace_required' => true,
                'email_notifications' => true,
                'sms_notifications' => true,
                'notification_language' => 'en',
                'status' => 'Active',
                'notes' => 'Premium trading company with extensive Pacific routes and excellent service.',
                'sales_representative' => 'Michael Johnson',
                'account_manager' => 'Lisa Chen',
                'contract_start_date' => '2024-01-01',
                'contract_end_date' => '2025-12-31'
            ],
            [
                'shipper_code' => 'DE003',
                'shipper_name' => 'Hamburg Industrial Export',
                'company_name' => 'Hamburg Industrial Export GmbH',
                'shipper_type' => 'Manufacturer',
                'business_license' => 'DE-HH-2018-789',
                'tax_id' => 'DE-123456789',
                'address' => 'Industriestraße 45, HafenCity',
                'city' => 'Hamburg',
                'state_province' => 'Hamburg',
                'country' => 'Germany',
                'country_id' => $germany?->id,
                'postal_code' => '20457',
                'latitude' => 53.5511,
                'longitude' => 9.9937,
                'contact_person' => 'Klaus Mueller',
                'contact_phone' => '+49-40-2234-5600',
                'contact_email' => 'klaus.mueller@hamburgexport.de',
                'website' => 'https://www.hamburgexport.de',
                'alternative_phone' => '+49-40-2234-5601',
                'emergency_contact' => 'Hans Weber - +49-40-2234-5650',
                'industry_type' => 'Industrial Manufacturing',
                'specialization' => 'Heavy Machinery, Chemical Equipment, and Industrial Components',
                'cargo_types' => [
                    'Heavy Machinery',
                    'Chemical Equipment',
                    'Industrial Components',
                    'Steel Products',
                    'Automotive Parts'
                ],
                'trade_routes' => [
                    'Europe to Global',
                    'Germany to Americas',
                    'Germany to Asia',
                    'Intra-Europe'
                ],
                'certifications' => [
                    'ISO 9001:2015',
                    'ISO 14001:2015',
                    'CE Marking',
                    'TÜV Certified',
                    'AEO Status'
                ],
                'established_year' => 1975,
                'annual_volume' => 35000,
                'services_offered' => [
                    'Manufacturing',
                    'Quality Control',
                    'Technical Support',
                    'Installation Services',
                    'Maintenance'
                ],
                'equipment_owned' => [
                    'Heavy Lift Equipment',
                    'Specialized Containers',
                    'Testing Facilities',
                    'Manufacturing Plants'
                ],
                'preferred_ports' => [5], // Hamburg port
                'operating_regions' => [
                    'Europe',
                    'Americas',
                    'Asia',
                    'Africa'
                ],
                'operating_hours' => 'Mon-Fri 7AM-6PM',
                'time_zone' => 'Europe/Berlin',
                'credit_rating' => 'A+',
                'payment_terms' => 'Net 45',
                'credit_limit' => 1500000.00,
                'currency_preference' => 'EUR',
                'insurance_coverage' => true,
                'bank_name' => 'Deutsche Bank',
                'bank_account' => 'DB-567890123',
                'required_documents' => [
                    'Technical Specifications',
                    'Quality Certificates',
                    'CE Marking Documents',
                    'Export License',
                    'Insurance Certificate'
                ],
                'customs_broker' => false,
                'freight_forwarder' => false,
                'dangerous_goods_certified' => true,
                'customs_code' => 'DE-ZOLL-003',
                'regulatory_compliance' => [
                    'German Customs',
                    'EU Regulations',
                    'TÜV Standards',
                    'CE Compliance'
                ],
                'on_time_delivery_rate' => 96.8,
                'damage_rate' => 0.005,
                'total_shipments' => 850,
                'customer_satisfaction' => 4.8,
                'last_audit_date' => '2024-01-10',
                'audit_result' => 'Excellent',
                'preferred_carriers' => [
                    'Hapag-Lloyd',
                    'Hamburg Süd',
                    'Maersk',
                    'MSC'
                ],
                'preferred_incoterms' => [
                    'EXW',
                    'FCA',
                    'CIP',
                    'DAP'
                ],
                'track_and_trace_required' => true,
                'email_notifications' => true,
                'sms_notifications' => false,
                'notification_language' => 'de',
                'status' => 'Active',
                'notes' => 'Leading German manufacturer with high-quality industrial equipment.',
                'sales_representative' => 'Anna Schmidt',
                'account_manager' => 'Thomas Becker',
                'contract_start_date' => '2023-07-01',
                'contract_end_date' => '2025-06-30'
            ],
            [
                'shipper_code' => 'CN004',
                'shipper_name' => 'Shanghai Global Trading',
                'company_name' => 'Shanghai Global Trading Co., Ltd.',
                'shipper_type' => 'Trading Company',
                'business_license' => 'CN-SH-2017-8888',
                'tax_id' => 'CN-TAX-310000567',
                'address' => '888 Pudong South Road, Lujiazui',
                'city' => 'Shanghai',
                'state_province' => 'Shanghai',
                'country' => 'China',
                'country_id' => $china?->id,
                'postal_code' => '200120',
                'latitude' => 31.2304,
                'longitude' => 121.4737,
                'contact_person' => 'Li Wei Ming',
                'contact_phone' => '+86-21-6888-9000',
                'contact_email' => 'li.weiming@shanghaiglobal.com',
                'website' => 'https://www.shanghaiglobal.com',
                'alternative_phone' => '+86-21-6888-9001',
                'emergency_contact' => 'Zhang Hui - +86-21-6888-9100',
                'industry_type' => 'General Trading',
                'specialization' => 'Electronics, Textiles, and Consumer Products',
                'cargo_types' => [
                    'Electronics',
                    'Textiles',
                    'Consumer Products',
                    'Toys',
                    'Home Appliances',
                    'Furniture'
                ],
                'trade_routes' => [
                    'China to Global',
                    'Asia-Pacific',
                    'China to Americas',
                    'China to Europe'
                ],
                'certifications' => [
                    'ISO 9001:2015',
                    'China Export License',
                    'Quality Management System',
                    'Environmental Certification'
                ],
                'established_year' => 2002,
                'annual_volume' => 120000,
                'services_offered' => [
                    'Export Services',
                    'Quality Inspection',
                    'Consolidation',
                    'Documentation',
                    'Sourcing Services'
                ],
                'equipment_owned' => [
                    'Container Depot',
                    'Warehouse Facilities',
                    'Quality Testing Lab',
                    'Packaging Equipment'
                ],
                'preferred_ports' => [7], // Shanghai port
                'operating_regions' => [
                    'Asia-Pacific',
                    'Americas',
                    'Europe',
                    'Africa'
                ],
                'operating_hours' => 'Mon-Sat 8AM-7PM',
                'time_zone' => 'Asia/Shanghai',
                'credit_rating' => 'A-',
                'payment_terms' => 'Net 30',
                'credit_limit' => 800000.00,
                'currency_preference' => 'USD',
                'insurance_coverage' => true,
                'bank_name' => 'Bank of China',
                'bank_account' => 'BOC-888999000',
                'required_documents' => [
                    'Export License',
                    'Quality Certificate',
                    'Packing List',
                    'Commercial Invoice',
                    'Certificate of Origin'
                ],
                'customs_broker' => true,
                'freight_forwarder' => false,
                'dangerous_goods_certified' => false,
                'customs_code' => 'CN-CUSTOMS-004',
                'regulatory_compliance' => [
                    'China Customs',
                    'Ministry of Commerce',
                    'Quality Supervision Bureau'
                ],
                'on_time_delivery_rate' => 92.5,
                'damage_rate' => 0.03,
                'total_shipments' => 2200,
                'customer_satisfaction' => 4.5,
                'last_audit_date' => '2024-04-05',
                'audit_result' => 'Good',
                'preferred_carriers' => [
                    'COSCO',
                    'OOCL',
                    'Yang Ming',
                    'Evergreen'
                ],
                'preferred_incoterms' => [
                    'FOB',
                    'CFR',
                    'CIF',
                    'EXW'
                ],
                'track_and_trace_required' => true,
                'email_notifications' => true,
                'sms_notifications' => true,
                'notification_language' => 'zh',
                'status' => 'Active',
                'notes' => 'Large volume trader with strong presence in consumer goods.',
                'sales_representative' => 'Wang Mei',
                'account_manager' => 'Chen Jun',
                'contract_start_date' => '2024-03-01',
                'contract_end_date' => '2025-02-28'
            ],
            [
                'shipper_code' => 'AE005',
                'shipper_name' => 'Dubai Trade Hub',
                'company_name' => 'Dubai Trade Hub LLC',
                'shipper_type' => 'Freight Forwarder',
                'business_license' => 'AE-DU-2020-1234',
                'tax_id' => 'AE-VAT-100123456789',
                'address' => 'Jebel Ali Free Zone, Building A1-101',
                'city' => 'Dubai',
                'state_province' => 'Dubai',
                'country' => 'United Arab Emirates',
                'country_id' => $uae?->id,
                'postal_code' => '17000',
                'latitude' => 25.0124,
                'longitude' => 55.1003,
                'contact_person' => 'Omar Al-Mansouri',
                'contact_phone' => '+971-4-881-7000',
                'contact_email' => 'omar.almansouri@dubaitradehub.ae',
                'website' => 'https://www.dubaitradehub.ae',
                'alternative_phone' => '+971-4-881-7001',
                'emergency_contact' => 'Ahmed Al-Rashid - +971-50-123-4567',
                'industry_type' => 'Logistics & Freight',
                'specialization' => 'Regional hub for Middle East, Africa, and Asia trade',
                'cargo_types' => [
                    'General Cargo',
                    'Containerized Goods',
                    'Break Bulk',
                    'Automotive',
                    'Oil & Gas Equipment',
                    'Project Cargo'
                ],
                'trade_routes' => [
                    'Middle East Hub',
                    'Asia to Africa',
                    'Europe to Asia',
                    'Regional Distribution'
                ],
                'certifications' => [
                    'ISO 9001:2015',
                    'IATA Certified',
                    'FIATA Member',
                    'AEO Certified',
                    'JAFZA Approved'
                ],
                'established_year' => 2010,
                'annual_volume' => 65000,
                'services_offered' => [
                    'Freight Forwarding',
                    'Customs Clearance',
                    'Warehousing',
                    'Distribution',
                    'Project Logistics',
                    'Supply Chain Management'
                ],
                'equipment_owned' => [
                    'Warehouse Network',
                    'Transport Fleet',
                    'Handling Equipment',
                    'IT Infrastructure'
                ],
                'preferred_ports' => [6], // Jebel Ali port
                'operating_regions' => [
                    'Middle East',
                    'Africa',
                    'Asia',
                    'Europe'
                ],
                'operating_hours' => '24/7',
                'time_zone' => 'Asia/Dubai',
                'credit_rating' => 'A',
                'payment_terms' => 'Net 30',
                'credit_limit' => 1200000.00,
                'currency_preference' => 'USD',
                'insurance_coverage' => true,
                'bank_name' => 'Emirates NBD',
                'bank_account' => 'ENBD-456789012',
                'required_documents' => [
                    'Airway Bill/Bill of Lading',
                    'Commercial Invoice',
                    'Packing List',
                    'Certificate of Origin',
                    'Import/Export License'
                ],
                'customs_broker' => true,
                'freight_forwarder' => true,
                'dangerous_goods_certified' => true,
                'customs_code' => 'AE-CUSTOMS-005',
                'regulatory_compliance' => [
                    'UAE Customs',
                    'JAFZA Regulations',
                    'IATA Standards',
                    'International Standards'
                ],
                'on_time_delivery_rate' => 94.2,
                'damage_rate' => 0.015,
                'total_shipments' => 1800,
                'customer_satisfaction' => 4.6,
                'last_audit_date' => '2024-02-15',
                'audit_result' => 'Good',
                'preferred_carriers' => [
                    'MSC',
                    'Maersk',
                    'CMA CGM',
                    'Emirates Airlines'
                ],
                'preferred_incoterms' => [
                    'FOB',
                    'CIF',
                    'DDP',
                    'DAP'
                ],
                'track_and_trace_required' => true,
                'email_notifications' => true,
                'sms_notifications' => true,
                'notification_language' => 'en',
                'status' => 'Active',
                'notes' => 'Strategic logistics partner in Middle East region with excellent connectivity.',
                'sales_representative' => 'Fatima Al-Zahra',
                'account_manager' => 'Khalid Ibrahim',
                'contract_start_date' => '2024-01-15',
                'contract_end_date' => '2024-12-31'
            ],
            [
                'shipper_code' => 'TEST001',
                'shipper_name' => 'Test Inactive Shipper',
                'company_name' => 'Test Inactive Company',
                'shipper_type' => 'Agent',
                'address' => 'Test Address',
                'city' => 'Test City',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'contact_person' => 'Test Person',
                'contact_phone' => '+20-2-000-0000',
                'contact_email' => 'test@inactive.com',
                'established_year' => 2020,
                'annual_volume' => 100,
                'cargo_types' => ['Test Cargo'],
                'services_offered' => ['Test Service'],
                'operating_hours' => 'Closed',
                'credit_rating' => 'C',
                'payment_terms' => 'Prepaid Only',
                'currency_preference' => 'USD',
                'insurance_coverage' => false,
                'customs_broker' => false,
                'freight_forwarder' => false,
                'dangerous_goods_certified' => false,
                'track_and_trace_required' => false,
                'email_notifications' => false,
                'sms_notifications' => false,
                'status' => 'Inactive',
                'notes' => 'Test shipper for system testing - Inactive status'
            ]
        ];

        foreach ($shippers as $shipper) {
            Shipper::create($shipper);
        }

        $this->command->info('Shipper seeder completed successfully!');
    }
}
