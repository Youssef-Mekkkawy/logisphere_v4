<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Management\Shipment;
use App\Models\Management\Company;
use App\Models\Logistics\Port;

class ShipmentSeeder extends Seeder
{
    public function run()
    {
        $companies = Company::where('type', 'Client')->get();
        $ports = Port::all();

        if ($companies->isEmpty() || $ports->count() < 2) {
            $this->command->warn('Missing required data. Please seed companies and ports first.');
            return;
        }

        $shipments = [
            [
                'shipment_id' => 'SHP-2024-001',
                'company_id' => $companies->first()->id,
                'origin_port_id' => $ports->where('name', 'like', '%Alexandria%')->first()?->id ?? $ports->first()->id,
                'destination_port_id' => $ports->where('name', 'like', '%Jeddah%')->first()?->id ?? $ports->skip(1)->first()->id,
                'container_type' => '40GP',
                'shipping_date' => now()->addDays(7),
                'eta' => now()->addDays(12),
                'freight_cost' => 850.00,
                'cargo_description' => 'Electronics and Consumer Goods',
                'weight' => 25000.50,
                'volume' => 65.30,
                'special_instructions' => 'Handle with care - fragile items included',
                'status' => 'Pending',
            ],
            [
                'shipment_id' => 'SHP-2024-002',
                'company_id' => $companies->skip(1)->first()?->id ?? $companies->first()->id,
                'origin_port_id' => $ports->where('name', 'like', '%Dubai%')->first()?->id ?? $ports->first()->id,
                'destination_port_id' => $ports->where('name', 'like', '%Mumbai%')->first()?->id ?? $ports->skip(1)->first()->id,
                'container_type' => '20GP',
                'shipping_date' => now()->addDays(14),
                'eta' => now()->addDays(22),
                'freight_cost' => 1200.00,
                'cargo_description' => 'Textiles and Garments',
                'weight' => 8500.25,
                'volume' => 28.75,
                'special_instructions' => 'Temperature controlled environment required',
                'status' => 'In Transit',
            ],
            [
                'shipment_id' => 'SHP-2024-003',
                'company_id' => $companies->skip(2)->first()?->id ?? $companies->first()->id,
                'origin_port_id' => $ports->where('name', 'like', '%Shanghai%')->first()?->id ?? $ports->first()->id,
                'destination_port_id' => $ports->where('name', 'like', '%Rotterdam%')->first()?->id ?? $ports->skip(1)->first()->id,
                'container_type' => '40HC',
                'shipping_date' => now()->subDays(5),
                'eta' => now()->addDays(30),
                'freight_cost' => 2800.00,
                'cargo_description' => 'Machinery and Industrial Equipment',
                'weight' => 35000.00,
                'volume' => 72.50,
                'special_instructions' => 'Heavy lift equipment required at destination',
                'status' => 'In Transit',
            ],
            [
                'shipment_id' => 'SHP-2024-004',
                'company_id' => $companies->first()->id,
                'origin_port_id' => $ports->skip(2)->first()?->id ?? $ports->first()->id,
                'destination_port_id' => $ports->first()->id,
                'container_type' => '20RF',
                'shipping_date' => now()->subDays(10),
                'eta' => now()->subDays(2),
                'freight_cost' => 1500.00,
                'cargo_description' => 'Frozen Food Products',
                'weight' => 18000.00,
                'volume' => 30.00,
                'special_instructions' => 'Maintain temperature at -18°C',
                'status' => 'At Port',
            ],
            [
                'shipment_id' => 'SHP-2024-005',
                'company_id' => $companies->skip(1)->first()?->id ?? $companies->first()->id,
                'origin_port_id' => $ports->first()->id,
                'destination_port_id' => $ports->skip(3)->first()?->id ?? $ports->skip(1)->first()->id,
                'container_type' => '40GP',
                'shipping_date' => now()->subDays(20),
                'eta' => now()->subDays(8),
                'freight_cost' => 950.00,
                'cargo_description' => 'Automotive Parts and Accessories',
                'weight' => 22000.00,
                'volume' => 58.00,
                'special_instructions' => 'Secure packaging for automotive components',
                'status' => 'Delivered',
            ],
        ];

        foreach ($shipments as $shipmentData) {
            Shipment::firstOrCreate(
                ['shipment_id' => $shipmentData['shipment_id']],
                $shipmentData
            );
        }

        $this->command->info('Shipments seeded successfully!');
    }
}