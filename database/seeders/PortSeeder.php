<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
    public function run()
    {
        $ports = [
            [
                'code' => 'EGALY',
                'name' => 'Port of Alexandria',
                'country' => 'Egypt',
                'city' => 'Alexandria',
                'type' => 'Container',
                'status' => 'Active',
            ],
            [
                'code' => 'SAJED',
                'name' => 'Port of Jeddah',
                'country' => 'Saudi Arabia',
                'city' => 'Jeddah',
                'type' => 'Container',
                'status' => 'Active',
            ],
            [
                'code' => 'AEDXB',
                'name' => 'Port of Dubai',
                'country' => 'UAE',
                'city' => 'Dubai',
                'type' => 'Container',
                'status' => 'Active',
            ],
            [
                'code' => 'INMUN',
                'name' => 'Port of Mumbai',
                'country' => 'India',
                'city' => 'Mumbai',
                'type' => 'Container',
                'status' => 'Active',
            ],
            [
                'code' => 'CNSHA',
                'name' => 'Port of Shanghai',
                'country' => 'China',
                'city' => 'Shanghai',
                'type' => 'Container',
                'status' => 'Active',
            ],
            [
                'code' => 'NLRTM',
                'name' => 'Port of Rotterdam',
                'country' => 'Netherlands',
                'city' => 'Rotterdam',
                'type' => 'Container',
                'status' => 'Active',
            ],
        ];

        foreach ($ports as $portData) {
            Port::firstOrCreate(
                ['code' => $portData['code']],
                $portData
            );
        }

        $this->command->info('Ports seeded successfully!');
    }
}
