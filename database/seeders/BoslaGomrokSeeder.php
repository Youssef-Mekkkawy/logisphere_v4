<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoslaGomrok;

class BoslaGomrokSeeder extends Seeder
{
    public function run()
    {
        $boslaItems = [
            [
                'code' => 'BGK-EXP-001',
                'name' => 'Export Declaration Form',
                'document_type' => 'Export',
                'customs_office' => 'Cairo Main Customs Office',
                'processing_hours' => 24,
                'cost' => 150.00,
                'description' => 'Standard export declaration form required for all goods leaving Egypt.',
                'required_documents' => 'Commercial Invoice, Packing List, Bill of Lading, Certificate of Origin',
                'validity_days' => 30,
                'is_mandatory' => true,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-IMP-001',
                'name' => 'Import Declaration Form',
                'document_type' => 'Import',
                'customs_office' => 'Alexandria Port Customs',
                'processing_hours' => 48,
                'cost' => 200.00,
                'description' => 'Standard import declaration form for goods entering Egypt through Alexandria Port.',
                'required_documents' => 'Commercial Invoice, Packing List, Bill of Lading, Import License',
                'validity_days' => 45,
                'is_mandatory' => true,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-TRA-001',
                'name' => 'Transit Declaration',
                'document_type' => 'Transit',
                'customs_office' => 'Suez Canal Customs',
                'processing_hours' => 12,
                'cost' => 75.00,
                'description' => 'Transit declaration for goods passing through Egypt to other destinations.',
                'required_documents' => 'Transit Bond, Through Bill of Lading, Manifest',
                'validity_days' => 15,
                'is_mandatory' => true,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-TMP-001',
                'name' => 'Temporary Admission',
                'document_type' => 'Temporary',
                'customs_office' => 'Cairo Main Customs Office',
                'processing_hours' => 36,
                'cost' => 100.00,
                'description' => 'Temporary admission for goods that will be re-exported within specified time.',
                'required_documents' => 'Guarantee Bond, Re-export Commitment, Equipment List',
                'validity_days' => 180,
                'is_mandatory' => false,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-EXP-002',
                'name' => 'Free Zone Export',
                'document_type' => 'Export',
                'customs_office' => 'Damietta Port Customs',
                'processing_hours' => 18,
                'cost' => 120.00,
                'description' => 'Export declaration for goods originating from free zones.',
                'required_documents' => 'Free Zone Certificate, Export Invoice, Zone Exit Permit',
                'validity_days' => 21,
                'is_mandatory' => true,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-IMP-002',
                'name' => 'Duty Free Import',
                'document_type' => 'Import',
                'customs_office' => 'Safaga Port Customs',
                'processing_hours' => 30,
                'cost' => 80.00,
                'description' => 'Special import declaration for duty-free goods.',
                'required_documents' => 'Duty Exemption Certificate, Project Documents, Ministry Approval',
                'validity_days' => 60,
                'is_mandatory' => false,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-EXP-003',
                'name' => 'Agricultural Export',
                'document_type' => 'Export',
                'customs_office' => 'Nuweiba Port Customs',
                'processing_hours' => 16,
                'cost' => 90.00,
                'description' => 'Specialized export declaration for agricultural products.',
                'required_documents' => 'Phytosanitary Certificate, Quality Certificate, Export License',
                'validity_days' => 14,
                'is_mandatory' => true,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-TMP-002',
                'name' => 'Exhibition Goods',
                'document_type' => 'Temporary',
                'customs_office' => 'Sharm El Sheikh Airport Customs',
                'processing_hours' => 8,
                'cost' => 50.00,
                'description' => 'Temporary admission for exhibition and display goods.',
                'required_documents' => 'Exhibition Certificate, Carnet Document, Return Guarantee',
                'validity_days' => 90,
                'is_mandatory' => false,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-IMP-003',
                'name' => 'Pharmaceutical Import',
                'document_type' => 'Import',
                'customs_office' => 'Cairo Main Customs Office',
                'processing_hours' => 72,
                'cost' => 300.00,
                'description' => 'Special import declaration for pharmaceutical products requiring health ministry approval.',
                'required_documents' => 'Health Ministry License, GMP Certificate, Analysis Certificate, Import Permit',
                'validity_days' => 30,
                'is_mandatory' => true,
                'status' => 'Active',
            ],
            [
                'code' => 'BGK-TRA-002',
                'name' => 'Regional Transit',
                'document_type' => 'Transit',
                'customs_office' => 'Hurghada Airport Customs',
                'processing_hours' => 6,
                'cost' => 40.00,
                'description' => 'Transit declaration for regional movements between Arab countries.',
                'required_documents' => 'Arab Transit Document, Regional Agreement Certificate',
                'validity_days' => 7,
                'is_mandatory' => true,
                'status' => 'Active',
            ],
        ];

        foreach ($boslaItems as $boslaData) {
            BoslaGomrok::firstOrCreate(
                ['code' => $boslaData['code']],
                $boslaData
            );
        }

        $this->command->info('Bosla from Gomrok items seeded successfully!');
    }
}
