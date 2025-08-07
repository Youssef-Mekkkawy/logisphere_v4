<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Management\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            // Client Companies
            [
                'name' => 'Global Electronics Ltd',
                'type' => 'Client',
                'contact_person' => 'Ahmed Hassan',
                'email' => 'ahmed@globalelectronics.com',
                'phone' => '+20-100-123-4567',
                'country' => 'Egypt',
                'address' => '123 Industrial Zone, New Cairo, Egypt',
                'service_type' => 'Import/Export',
                'status' => 'Active',
            ],
            [
                'name' => 'Middle East Trading Co',
                'type' => 'Client',
                'contact_person' => 'Sarah Al-Rashid',
                'email' => 'sarah@metrading.com',
                'phone' => '+971-50-123-4567',
                'country' => 'UAE',
                'address' => 'Dubai International City, UAE',
                'service_type' => 'Re-export',
                'status' => 'Active',
            ],
            [
                'name' => 'European Imports SARL',
                'type' => 'Client',
                'contact_person' => 'Jean-Pierre Dubois',
                'email' => 'jp@euroimports.fr',
                'phone' => '+33-1-23-45-67-89',
                'country' => 'France',
                'address' => '45 Rue de Commerce, Paris, France',
                'service_type' => 'Import',
                'status' => 'Active',
            ],
            [
                'name' => 'Asia Pacific Logistics',
                'type' => 'Client',
                'contact_person' => 'Raj Patel',
                'email' => 'raj@aplogistics.in',
                'phone' => '+91-22-1234-5678',
                'country' => 'India',
                'address' => 'Mumbai Port Area, Maharashtra, India',
                'service_type' => 'FCL/LCL',
                'status' => 'Active',
            ],

            // Supplier Companies
            [
                'name' => 'Mediterranean Shipping Agency',
                'type' => 'Supplier',
                'contact_person' => 'Marco Rossi',
                'email' => 'marco@medshipping.it',
                'phone' => '+39-010-123-4567',
                'country' => 'Italy',
                'address' => 'Via Porto 15, Genoa, Italy',
                'service_type' => 'Ocean Freight',
                'status' => 'Active',
            ],
            [
                'name' => 'Red Sea Logistics Services',
                'type' => 'Supplier',
                'contact_person' => 'Omar Abdullah',
                'email' => 'omar@redsealogistics.sa',
                'phone' => '+966-12-345-6789',
                'country' => 'Saudi Arabia',
                'address' => 'Jeddah Port Complex, Saudi Arabia',
                'service_type' => 'Port Services',
                'status' => 'Active',
            ],
            [
                'name' => 'Trans-Gulf Carriers',
                'type' => 'Supplier',
                'contact_person' => 'Mohammed Al-Zaabi',
                'email' => 'mohammed@transgulf.ae',
                'phone' => '+971-4-123-4567',
                'country' => 'UAE',
                'address' => 'Jebel Ali Free Zone, Dubai, UAE',
                'service_type' => 'Road Transport',
                'status' => 'Active',
            ],
        ];

        foreach ($companies as $companyData) {
            Company::firstOrCreate(
                [
                    'name' => $companyData['name'],
                    'type' => $companyData['type']
                ],
                $companyData
            );
        }

        $this->command->info('Companies seeded successfully!');
    }
}
