<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Logistics\InspectionType;

class InspectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inspectionTypes = [
            [
                'inspection_code' => 'INSP001',
                'inspection_name' => 'Customs Clearance Inspection',
                'inspection_category' => 'Customs',
                'description' => 'Standard customs inspection for import/export clearance including document verification and physical examination of goods.',
                'required_documents' => [
                    'Bill of Lading',
                    'Commercial Invoice',
                    'Packing List',
                    'Certificate of Origin',
                    'Import License'
                ],
                'estimated_duration' => 4.0,
                'cost_estimate' => 150.00,
                'regulatory_authority' => 'Egypt Customs Authority',
                'mandatory' => true,
                'applies_to' => ['FCL', 'LCL', 'Break Bulk'],
                'prerequisites' => 'All shipping documents must be available and properly filled.',
                'validity_period' => 30,
                'renewal_required' => false,
                'compliance_standards' => 'WCO Framework, Egyptian Customs Law',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP002',
                'inspection_name' => 'Quality Control Inspection',
                'inspection_category' => 'Quality',
                'description' => 'Comprehensive quality inspection to ensure goods meet specified standards and customer requirements.',
                'required_documents' => [
                    'Quality Specifications',
                    'Test Reports',
                    'Compliance Certificate',
                    'Manufacturing Certificate'
                ],
                'estimated_duration' => 8.0,
                'cost_estimate' => 300.00,
                'regulatory_authority' => 'Egyptian Organization for Standardization',
                'mandatory' => false,
                'applies_to' => ['FCL', 'LCL', 'High Value'],
                'prerequisites' => 'Product specifications and quality standards must be defined.',
                'validity_period' => 180,
                'renewal_required' => true,
                'compliance_standards' => 'ISO 9001, Egyptian Standards',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP003',
                'inspection_name' => 'Safety Inspection',
                'inspection_category' => 'Safety',
                'description' => 'Safety inspection for potentially hazardous goods and dangerous materials.',
                'required_documents' => [
                    'Safety Data Sheet',
                    'Dangerous Goods Declaration',
                    'Safety Certificate',
                    'Handling Instructions'
                ],
                'estimated_duration' => 6.0,
                'cost_estimate' => 250.00,
                'regulatory_authority' => 'Egyptian Maritime Safety Authority',
                'mandatory' => true,
                'applies_to' => ['Dangerous Goods'],
                'prerequisites' => 'Proper classification and packaging of dangerous goods.',
                'validity_period' => 90,
                'renewal_required' => false,
                'compliance_standards' => 'IMDG Code, ADR, Egyptian Safety Regulations',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP004',
                'inspection_name' => 'Environmental Compliance Check',
                'inspection_category' => 'Environmental',
                'description' => 'Environmental impact assessment and compliance verification for goods that may affect the environment.',
                'required_documents' => [
                    'Environmental Impact Assessment',
                    'Environmental Permit',
                    'Compliance Certificate',
                    'Material Safety Data'
                ],
                'estimated_duration' => 12.0,
                'cost_estimate' => 400.00,
                'regulatory_authority' => 'Egyptian Environmental Affairs Agency',
                'mandatory' => false,
                'applies_to' => ['Dangerous Goods', 'Project Cargo'],
                'prerequisites' => 'Environmental impact assessment completed.',
                'validity_period' => 365,
                'renewal_required' => true,
                'compliance_standards' => 'ISO 14001, Egyptian Environmental Law',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP005',
                'inspection_name' => 'Security Screening',
                'inspection_category' => 'Security',
                'description' => 'Security inspection and screening for high-risk shipments and sensitive cargo.',
                'required_documents' => [
                    'Security Declaration',
                    'Chain of Custody Form',
                    'Shipper Security Endorsement',
                    'Photo Documentation'
                ],
                'estimated_duration' => 3.0,
                'cost_estimate' => 100.00,
                'regulatory_authority' => 'Egyptian Port Security Authority',
                'mandatory' => true,
                'applies_to' => ['High Value', 'Project Cargo'],
                'prerequisites' => 'Proper sealing and documentation of cargo.',
                'validity_period' => 7,
                'renewal_required' => false,
                'compliance_standards' => 'ISPS Code, Egyptian Port Security Regulations',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP006',
                'inspection_name' => 'Health & Sanitary Inspection',
                'inspection_category' => 'Health',
                'description' => 'Health and sanitary inspection for food products, pharmaceuticals, and medical equipment.',
                'required_documents' => [
                    'Health Certificate',
                    'Sanitary Certificate',
                    'Test Reports',
                    'Manufacturing License'
                ],
                'estimated_duration' => 6.0,
                'cost_estimate' => 200.00,
                'regulatory_authority' => 'Egyptian Ministry of Health',
                'mandatory' => true,
                'applies_to' => ['Food Products', 'Pharmaceuticals'],
                'prerequisites' => 'Valid health certificates from country of origin.',
                'validity_period' => 60,
                'renewal_required' => false,
                'compliance_standards' => 'WHO Guidelines, Egyptian Health Regulations',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP007',
                'inspection_name' => 'Technical Equipment Inspection',
                'inspection_category' => 'Technical',
                'description' => 'Technical inspection for machinery, equipment, and technical components.',
                'required_documents' => [
                    'Technical Specifications',
                    'Calibration Certificate',
                    'Compliance Certificate',
                    'User Manual'
                ],
                'estimated_duration' => 10.0,
                'cost_estimate' => 350.00,
                'regulatory_authority' => 'Egyptian Technical Authority',
                'mandatory' => false,
                'applies_to' => ['Project Cargo'],
                'prerequisites' => 'Technical documentation and specifications available.',
                'validity_period' => 365,
                'renewal_required' => true,
                'compliance_standards' => 'IEC Standards, Egyptian Technical Regulations',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP008',
                'inspection_name' => 'Documentation Review',
                'inspection_category' => 'Documentation',
                'description' => 'Comprehensive review of all shipping and commercial documents for completeness and accuracy.',
                'required_documents' => [
                    'Bill of Lading',
                    'Commercial Invoice',
                    'Packing List',
                    'Insurance Policy',
                    'Customs Declaration'
                ],
                'estimated_duration' => 2.0,
                'cost_estimate' => 75.00,
                'regulatory_authority' => 'Shipping Documentation Authority',
                'mandatory' => true,
                'applies_to' => ['FCL', 'LCL', 'Break Bulk', 'Project Cargo'],
                'prerequisites' => 'All required documents must be submitted.',
                'validity_period' => 30,
                'renewal_required' => false,
                'compliance_standards' => 'UCP 600, Egyptian Commercial Law',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP009',
                'inspection_name' => 'Physical Cargo Examination',
                'inspection_category' => 'Physical',
                'description' => 'Physical examination of cargo to verify quantity, condition, and conformity with documentation.',
                'required_documents' => [
                    'Packing List',
                    'Commercial Invoice',
                    'Photo Documentation',
                    'Condition Report'
                ],
                'estimated_duration' => 5.0,
                'cost_estimate' => 180.00,
                'regulatory_authority' => 'Independent Survey Company',
                'mandatory' => false,
                'applies_to' => ['High Value', 'Project Cargo', 'Break Bulk'],
                'prerequisites' => 'Cargo must be accessible for physical inspection.',
                'validity_period' => 15,
                'renewal_required' => false,
                'compliance_standards' => 'ISM Code, Survey Standards',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP010',
                'inspection_name' => 'Laboratory Testing',
                'inspection_category' => 'Laboratory',
                'description' => 'Laboratory analysis and testing for chemical composition, contamination, and quality parameters.',
                'required_documents' => [
                    'Test Request Form',
                    'Material Safety Data Sheet',
                    'Sampling Protocol',
                    'Chain of Custody Form'
                ],
                'estimated_duration' => 24.0,
                'cost_estimate' => 500.00,
                'regulatory_authority' => 'Certified Laboratory',
                'mandatory' => false,
                'applies_to' => ['Food Products', 'Pharmaceuticals', 'Dangerous Goods'],
                'prerequisites' => 'Proper sampling and handling procedures followed.',
                'validity_period' => 90,
                'renewal_required' => false,
                'compliance_standards' => 'ISO 17025, Laboratory Accreditation Standards',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP011',
                'inspection_name' => 'Live Animal Welfare Check',
                'inspection_category' => 'Health',
                'description' => 'Welfare inspection for live animals during transport including health status and care conditions.',
                'required_documents' => [
                    'Veterinary Health Certificate',
                    'Transport Permit',
                    'Animal Welfare Plan',
                    'Vaccination Records'
                ],
                'estimated_duration' => 8.0,
                'cost_estimate' => 300.00,
                'regulatory_authority' => 'Veterinary Services Authority',
                'mandatory' => true,
                'applies_to' => ['Live Animals'],
                'prerequisites' => 'Valid veterinary certificates and proper transport conditions.',
                'validity_period' => 7,
                'renewal_required' => false,
                'compliance_standards' => 'OIE Standards, Animal Welfare Regulations',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP012',
                'inspection_name' => 'Perishable Goods Cold Chain Verification',
                'inspection_category' => 'Quality',
                'description' => 'Cold chain verification for perishable goods including temperature monitoring and storage conditions.',
                'required_documents' => [
                    'Temperature Log',
                    'Cold Chain Certificate',
                    'Storage Instructions',
                    'Quality Certificate'
                ],
                'estimated_duration' => 4.0,
                'cost_estimate' => 220.00,
                'regulatory_authority' => 'Food Safety Authority',
                'mandatory' => true,
                'applies_to' => ['Perishable'],
                'prerequisites' => 'Continuous temperature monitoring during transport.',
                'validity_period' => 5,
                'renewal_required' => false,
                'compliance_standards' => 'HACCP, Cold Chain Standards',
                'status' => 'Active'
            ],
            [
                'inspection_code' => 'INSP013',
                'inspection_name' => 'Inactive Test Inspection',
                'inspection_category' => 'Quality',
                'description' => 'This is an inactive inspection type for testing purposes.',
                'required_documents' => ['Test Document'],
                'estimated_duration' => 1.0,
                'cost_estimate' => 50.00,
                'regulatory_authority' => 'Test Authority',
                'mandatory' => false,
                'applies_to' => ['FCL'],
                'prerequisites' => 'Test prerequisites.',
                'validity_period' => 30,
                'renewal_required' => false,
                'compliance_standards' => 'Test Standards',
                'status' => 'Inactive'
            ]
        ];

        foreach ($inspectionTypes as $inspectionType) {
            InspectionType::create($inspectionType);
        }

        $this->command->info('Inspection Type seeder completed successfully!');
    }
}
