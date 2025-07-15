<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingAgency;

class ShippingAgencySeeder extends Seeder
{
    public function run()
    {
        $agencies = [
            [
                'code' => 'MAR001',
                'name' => 'Maersk Line Agency',
                'contact_person' => 'David Johnson',
                'email' => 'david@maersk.com',
                'phone' => '+45-33-63-33-63',
                'country' => 'Denmark',
                'address' => 'Esplanaden 50, Copenhagen, Denmark',
                'status' => 'Active',
            ],
            [
                'code' => 'COS002',
                'name' => 'COSCO Shipping Agency',
                'contact_person' => 'Li Wei',
                'email' => 'li.wei@cosco.com',
                'phone' => '+86-21-5555-0102',
                'country' => 'China',
                'address' => 'Shanghai International Port, China',
                'status' => 'Active',
            ],
            [
                'code' => 'MSC003',
                'name' => 'Mediterranean Shipping Company',
                'contact_person' => 'Marco Aponte',
                'email' => 'marco@msc.com',
                'phone' => '+39-010-559-1',
                'country' => 'Italy',
                'address' => 'Via Fieschi 17, Genoa, Italy',
                'status' => 'Active',
            ],
            [
                'code' => 'CMA004',
                'name' => 'CMA CGM Agency',
                'contact_person' => 'Pierre Dubois',
                'email' => 'pierre@cma-cgm.com',
                'phone' => '+33-4-88-91-90-00',
                'country' => 'France',
                'address' => '4 Quai d\'Arenc, Marseille, France',
                'status' => 'Active',
            ],
            [
                'code' => 'EVG005',
                'name' => 'Evergreen Marine Corp',
                'contact_person' => 'Chen Ming-Hsiung',
                'email' => 'chen@evergreen-marine.com',
                'phone' => '+886-2-2505-6633',
                'country' => 'Taiwan',
                'address' => 'No. 166, Minsheng E. Rd., Taipei, Taiwan',
                'status' => 'Active',
            ],
            [
                'code' => 'ONE006',
                'name' => 'Ocean Network Express',
                'contact_person' => 'Takeshi Yamamoto',
                'email' => 'takeshi@one-line.com',
                'phone' => '+81-3-6220-8000',
                'country' => 'Japan',
                'address' => 'Tokyo International Trade Center, Japan',
                'status' => 'Active',
            ],
        ];

        foreach ($agencies as $agencyData) {
            ShippingAgency::firstOrCreate(
                ['code' => $agencyData['code']],
                $agencyData
            );
        }

        $this->command->info('Shipping Agencies seeded successfully!');
    }
}