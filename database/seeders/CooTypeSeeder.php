<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Logistics\COOType;


class CooTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏛️ Creating Certificate of Origin Types...');

        $cooTypes = [
            // Commercial Certificate of Origin
            [
                'code' => 'COO-COMM',
                'name' => 'Commercial Certificate of Origin',
                'issuing_authority' => 'Chamber of Commerce',
                'is_mandatory' => true,
                'processing_days' => 3,
                'cost' => 25.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Standard commercial certificate of origin for general trade goods. Required for most international shipments.',
                'required_documents' => 'Commercial Invoice, Packing List, Export Declaration, Manufacturing Certificate'
            ],

            // Preferential Certificate of Origin
            [
                'code' => 'COO-PREF',
                'name' => 'Preferential Certificate of Origin',
                'issuing_authority' => 'Customs Authority',
                'is_mandatory' => false,
                'processing_days' => 5,
                'cost' => 50.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Preferential certificate for reduced tariff rates under trade agreements (FTA, GSP).',
                'required_documents' => 'Commercial Invoice, Bill of Materials, Production Certificate, FTA Declaration'
            ],

            // GSP Certificate of Origin
            [
                'code' => 'COO-GSP',
                'name' => 'GSP Certificate of Origin (Form A)',
                'issuing_authority' => 'Authorized GSP Body',
                'is_mandatory' => false,
                'processing_days' => 4,
                'cost' => 35.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Generalized System of Preferences certificate for developing countries to access preferential tariff rates.',
                'required_documents' => 'Commercial Invoice, Production Declaration, GSP Eligibility Certificate, Export License'
            ],

            // EUR.1 Movement Certificate
            [
                'code' => 'COO-EUR1',
                'name' => 'EUR.1 Movement Certificate',
                'issuing_authority' => 'EU Customs Authority',
                'is_mandatory' => false,
                'processing_days' => 2,
                'cost' => 40.00,
                'validity_months' => 4,
                'status' => 'Active',
                'description' => 'European Union movement certificate for preferential tariff treatment within EU trade agreements.',
                'required_documents' => 'Commercial Invoice, EU Production Certificate, Movement Declaration, Customs Declaration'
            ],

            // NAFTA/USMCA Certificate
            [
                'code' => 'COO-USMCA',
                'name' => 'USMCA Certificate of Origin',
                'issuing_authority' => 'USMCA Certified Body',
                'is_mandatory' => false,
                'processing_days' => 3,
                'cost' => 45.00,
                'validity_months' => 48,
                'status' => 'Active',
                'description' => 'United States-Mexico-Canada Agreement certificate for preferential treatment in North American trade.',
                'required_documents' => 'Commercial Invoice, USMCA Declaration, Production Records, Tariff Classification'
            ],

            // Arab League Certificate
            [
                'code' => 'COO-ARAB',
                'name' => 'Arab League Certificate of Origin',
                'issuing_authority' => 'Arab Chamber of Commerce',
                'is_mandatory' => false,
                'processing_days' => 4,
                'cost' => 30.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Certificate for Arab League preferential trade agreement benefits.',
                'required_documents' => 'Commercial Invoice, Arab Production Certificate, Export Declaration, Chamber Membership'
            ],

            // Re-Export Certificate
            [
                'code' => 'COO-REEXP',
                'name' => 'Re-Export Certificate of Origin',
                'issuing_authority' => 'Port Authority',
                'is_mandatory' => true,
                'processing_days' => 2,
                'cost' => 20.00,
                'validity_months' => 6,
                'status' => 'Active',
                'description' => 'Certificate for goods being re-exported without substantial transformation.',
                'required_documents' => 'Original COO, Import Documents, Re-Export Declaration, Warehouse Receipt'
            ],

            // Textile Certificate
            [
                'code' => 'COO-TEX',
                'name' => 'Textile Certificate of Origin',
                'issuing_authority' => 'Textile Authority',
                'is_mandatory' => true,
                'processing_days' => 7,
                'cost' => 60.00,
                'validity_months' => 6,
                'status' => 'Active',
                'description' => 'Specialized certificate for textile and apparel products under quota agreements.',
                'required_documents' => 'Commercial Invoice, Fabric Origin Declaration, Manufacturing Certificate, Quota License'
            ],

            // Agricultural Certificate
            [
                'code' => 'COO-AGRI',
                'name' => 'Agricultural Certificate of Origin',
                'issuing_authority' => 'Ministry of Agriculture',
                'is_mandatory' => true,
                'processing_days' => 5,
                'cost' => 40.00,
                'validity_months' => 3,
                'status' => 'Active',
                'description' => 'Certificate for agricultural products requiring origin verification for health and safety compliance.',
                'required_documents' => 'Commercial Invoice, Phytosanitary Certificate, Farm Registration, Quality Certificate'
            ],

            // Electronic Certificate
            [
                'code' => 'COO-ELEC',
                'name' => 'Electronic Certificate of Origin',
                'issuing_authority' => 'Digital Trade Platform',
                'is_mandatory' => false,
                'processing_days' => 1,
                'cost' => 15.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Digital certificate processed through electronic trade platforms for faster processing.',
                'required_documents' => 'Digital Invoice, Electronic Production Record, Digital Signature, Platform Registration'
            ],

            // Halal Certificate of Origin
            [
                'code' => 'COO-HALAL',
                'name' => 'Halal Certificate of Origin',
                'issuing_authority' => 'Islamic Certification Body',
                'is_mandatory' => false,
                'processing_days' => 10,
                'cost' => 80.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Certificate confirming halal compliance and origin for Islamic markets.',
                'required_documents' => 'Commercial Invoice, Halal Compliance Certificate, Islamic Authority Approval, Production Records'
            ],

            // Kosher Certificate of Origin
            [
                'code' => 'COO-KOSHER',
                'name' => 'Kosher Certificate of Origin',
                'issuing_authority' => 'Kosher Certification Agency',
                'is_mandatory' => false,
                'processing_days' => 8,
                'cost' => 75.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Certificate verifying kosher compliance and origin for Jewish dietary law requirements.',
                'required_documents' => 'Commercial Invoice, Kosher Certification, Rabbi Supervision Certificate, Production Records'
            ],

            // Organic Certificate of Origin
            [
                'code' => 'COO-ORG',
                'name' => 'Organic Certificate of Origin',
                'issuing_authority' => 'Organic Certification Body',
                'is_mandatory' => false,
                'processing_days' => 14,
                'cost' => 100.00,
                'validity_months' => 12,
                'status' => 'Active',
                'description' => 'Certificate for organic products verifying origin and organic production methods.',
                'required_documents' => 'Commercial Invoice, Organic Certification, Farm Inspection Report, Chain of Custody'
            ],

            // Dangerous Goods Certificate
            [
                'code' => 'COO-DG',
                'name' => 'Dangerous Goods Certificate of Origin',
                'issuing_authority' => 'IMDG Certified Authority',
                'is_mandatory' => true,
                'processing_days' => 7,
                'cost' => 120.00,
                'validity_months' => 6,
                'status' => 'Active',
                'description' => 'Certificate for dangerous goods shipments requiring special origin verification and handling.',
                'required_documents' => 'Commercial Invoice, MSDS, Dangerous Goods Declaration, Manufacturing License'
            ],

            // Pharmaceutical Certificate
            [
                'code' => 'COO-PHARM',
                'name' => 'Pharmaceutical Certificate of Origin',
                'issuing_authority' => 'FDA/Health Authority',
                'is_mandatory' => true,
                'processing_days' => 21,
                'cost' => 200.00,
                'validity_months' => 6,
                'status' => 'Active',
                'description' => 'Certificate for pharmaceutical products requiring strict origin verification and quality compliance.',
                'required_documents' => 'Commercial Invoice, GMP Certificate, FDA Registration, Quality Assurance Certificate'
            ],

            // Free Trade Zone Certificate
            [
                'code' => 'COO-FTZ',
                'name' => 'Free Trade Zone Certificate',
                'issuing_authority' => 'Free Zone Authority',
                'is_mandatory' => false,
                'processing_days' => 2,
                'cost' => 18.00,
                'validity_months' => 24,
                'status' => 'Active',
                'description' => 'Certificate for goods processed or stored in free trade zones.',
                'required_documents' => 'Commercial Invoice, FTZ Entry Certificate, Processing Record, Zone Authority Approval'
            ],

            // Transit Certificate
            [
                'code' => 'COO-TRANS',
                'name' => 'Transit Certificate of Origin',
                'issuing_authority' => 'Transit Authority',
                'is_mandatory' => false,
                'processing_days' => 1,
                'cost' => 10.00,
                'validity_months' => 1,
                'status' => 'Active',
                'description' => 'Temporary certificate for goods in transit through multiple countries.',
                'required_documents' => 'Commercial Invoice, Transit Declaration, Customs Bond, Route Manifest'
            ],

            // Digital Blockchain Certificate
            [
                'code' => 'COO-BLOCK',
                'name' => 'Blockchain Certificate of Origin',
                'issuing_authority' => 'Blockchain Verification Network',
                'is_mandatory' => false,
                'processing_days' => 1,
                'cost' => 12.00,
                'validity_months' => 0, // No expiry for blockchain
                'status' => 'Active',
                'description' => 'Immutable blockchain-based certificate providing transparent supply chain verification.',
                'required_documents' => 'Digital Invoice, Smart Contract, Blockchain Hash, Network Verification'
            ],

            // Legacy Certificate (Inactive Example)
            [
                'code' => 'COO-LEG',
                'name' => 'Legacy Paper Certificate',
                'issuing_authority' => 'Legacy Certification Office',
                'is_mandatory' => false,
                'processing_days' => 14,
                'cost' => 5.00,
                'validity_months' => 6,
                'status' => 'Inactive',
                'description' => 'Legacy paper-based certificate system. No longer in active use.',
                'required_documents' => 'Paper Invoice, Manual Forms, Physical Stamps, Courier Delivery'
            ],

            // Emergency Certificate
            [
                'code' => 'COO-EMRG',
                'name' => 'Emergency Certificate of Origin',
                'issuing_authority' => 'Emergency Trade Office',
                'is_mandatory' => false,
                'processing_days' => 1,
                'cost' => 150.00,
                'validity_months' => 1,
                'status' => 'Active',
                'description' => 'Expedited certificate for urgent shipments requiring same-day processing.',
                'required_documents' => 'Commercial Invoice, Emergency Declaration, Expedite Fee Payment, Authority Approval'
            ]
        ];

        foreach ($cooTypes as $cooTypeData) {
            COOType::firstOrCreate(
                ['code' => $cooTypeData['code']],
                $cooTypeData
            );
        }

        $this->command->info('📜 COO Types Seeder completed!');
        $this->command->info('✅ Created ' . count($cooTypes) . ' Certificate of Origin types');
        $this->command->info('🔹 Commercial, Preferential, GSP, EUR.1, USMCA certificates');
        $this->command->info('🔹 Specialized: Textile, Agricultural, Pharmaceutical, Halal, Kosher');
        $this->command->info('🔹 Modern: Electronic, Blockchain, Emergency processing');
        $this->command->info('🔹 Various authorities: Chambers, Customs, Trade bodies, Certification agencies');
        $this->command->info('🔹 Processing times: 1-21 days, Costs: $5-$200, Various validity periods');
    }
}
