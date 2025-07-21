<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingAgency;
use App\Models\Country;

class ShippingAgencySeeder extends Seeder
{
    public function run()
    {
        // First, ensure we have some countries in the database
        // Run CountrySeeder first if you haven't already

        $agencies = [
            [
                'code' => 'MAR001',
                'name' => 'Maersk Line Agency',
                'contact_person' => 'David Johnson',
                'email' => 'david@maersk.com',
                'phone' => '+45-33-63-33-63',
                'country_name' => 'Germany', // Using Germany since Denmark might not be in your countries list
                'address' => 'Esplanaden 50, Copenhagen, Denmark',
                'service_type' => 'Ocean Freight',
                'services_offered' => 'Container shipping, cargo handling, logistics solutions',
                'status' => 'Active',
            ],
            [
                'code' => 'COS002',
                'name' => 'COSCO Shipping Agency',
                'contact_person' => 'Li Wei',
                'email' => 'li.wei@cosco.com',
                'phone' => '+86-21-5555-0102',
                'country_name' => 'China',
                'address' => 'Shanghai International Port, China',
                'service_type' => 'Ocean Freight',
                'services_offered' => 'Container shipping, bulk cargo, international logistics',
                'status' => 'Active',
            ],
            [
                'code' => 'MSC003',
                'name' => 'Mediterranean Shipping Company',
                'contact_person' => 'Marco Aponte',
                'email' => 'marco@msc.com',
                'phone' => '+39-010-559-1',
                'country_name' => 'Italy',
                'address' => 'Via Fieschi 17, Genoa, Italy',
                'service_type' => 'Ocean Freight',
                'services_offered' => 'Container shipping, cruise operations, terminal services',
                'status' => 'Active',
            ],
            [
                'code' => 'CMA004',
                'name' => 'CMA CGM Agency',
                'contact_person' => 'Pierre Dubois',
                'email' => 'pierre@cma-cgm.com',
                'phone' => '+33-4-88-91-90-00',
                'country_name' => 'France',
                'address' => '4 Quai d\'Arenc, Marseille, France',
                'service_type' => 'Ocean Freight',
                'services_offered' => 'Container shipping, logistics, air cargo',
                'status' => 'Active',
            ],
            [
                'code' => 'EVG005',
                'name' => 'Evergreen Marine Corp',
                'contact_person' => 'Chen Ming-Hsiung',
                'email' => 'chen@evergreen-marine.com',
                'phone' => '+886-2-2505-6633',
                'country_name' => 'China', // Using China instead of Taiwan
                'address' => 'No. 166, Minsheng E. Rd., Taipei, Taiwan',
                'service_type' => 'Ocean Freight',
                'services_offered' => 'Container shipping, vessel operations, port services',
                'status' => 'Active',
            ],
            [
                'code' => 'ONE006',
                'name' => 'Ocean Network Express',
                'contact_person' => 'Takeshi Yamamoto',
                'email' => 'takeshi@one-line.com',
                'phone' => '+81-3-6220-8000',
                'country_name' => 'Japan',
                'address' => 'Tokyo International Trade Center, Japan',
                'service_type' => 'Ocean Freight',
                'services_offered' => 'Container shipping, integrated logistics solutions',
                'status' => 'Active',
            ],
            [
                'code' => 'DHL007',
                'name' => 'DHL Global Forwarding',
                'contact_person' => 'Sarah Miller',
                'email' => 'sarah@dhl.com',
                'phone' => '+49-228-182-0',
                'country_name' => 'Germany',
                'address' => 'Charles-de-Gaulle-Straße 20, Bonn, Germany',
                'service_type' => 'Air Freight',
                'services_offered' => 'Air freight, road transport, warehousing',
                'status' => 'Active',
            ],
            [
                'code' => 'FDX008',
                'name' => 'FedEx Trade Networks',
                'contact_person' => 'Robert Smith',
                'email' => 'robert@fedex.com',
                'phone' => '+1-901-818-7500',
                'country_name' => 'United States',
                'address' => '942 South Shady Grove Road, Memphis, USA',
                'service_type' => 'Air Freight',
                'services_offered' => 'Express delivery, customs brokerage, trade solutions',
                'status' => 'Active',
            ],
            [
                'code' => 'UPS009',
                'name' => 'UPS Supply Chain Solutions',
                'contact_person' => 'Jennifer Wilson',
                'email' => 'jennifer@ups.com',
                'phone' => '+1-404-828-6000',
                'country_name' => 'United States',
                'address' => '55 Glenlake Parkway NE, Atlanta, USA',
                'service_type' => 'Full Service',
                'services_offered' => 'Package delivery, supply chain management, logistics',
                'status' => 'Active',
            ],
            [
                'code' => 'DSV010',
                'name' => 'DSV Air & Sea',
                'contact_person' => 'Lars Nielsen',
                'email' => 'lars@dsv.com',
                'phone' => '+45-43-20-30-40',
                'country_name' => 'Germany', // Using Germany instead of Denmark
                'address' => 'Hovedgaden 630, Hedehusene, Denmark',
                'service_type' => 'Full Service',
                'services_offered' => 'Air & sea freight, road transport, contract logistics',
                'status' => 'Active',
            ],
        ];

        foreach ($agencies as $agencyData) {
            // Find the country by name
            $country = Country::where('name', $agencyData['country_name'])->first();

            if (!$country) {
                $this->command->warn("Country '{$agencyData['country_name']}' not found for agency '{$agencyData['name']}'. Skipping...");
                continue;
            }

            // Remove country_name and add country_id
            unset($agencyData['country_name']);
            $agencyData['country_id'] = $country->id;

            // Create or update the shipping agency
            ShippingAgency::firstOrCreate(
                ['code' => $agencyData['code']],
                $agencyData
            );

            $this->command->info("Created/Updated agency: {$agencyData['name']}");
        }

        $this->command->info('Shipping Agencies seeded successfully!');
    }
}
