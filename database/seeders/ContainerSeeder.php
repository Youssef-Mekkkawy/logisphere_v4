<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logistics\Container;
use App\Models\Logistics\Booking;
use App\Models\Management\Shipment;
use App\Models\Logistics\Port;

class ContainerSeeder extends Seeder
{
    public function run()
    {
        $bookings = Booking::all();
        $shipments = Shipment::all();
        $ports = Port::all();

        $containers = [
            [
                'container_number' => 'MSKU7654321',
                'container_type' => '40GP',
                'booking_id' => $bookings->first()?->id,
                'shipment_id' => $shipments->first()?->id,
                'seal_number' => 'MSK123456',
                'tare_weight' => '3,900 kg',
                'gross_weight' => 28900.50,
                'net_weight' => 25000.50,
                'volume_used' => 65.30,
                'loading_status' => 'Loaded',
                'container_condition' => 'Good',
                'current_location' => 'Port of Alexandria',
                'current_port_id' => $ports->first()?->id,
                'stuffing_date' => now()->subDays(3),
                'cargo_manifest' => [
                    ['item' => 'Electronics', 'quantity' => 100, 'weight' => 15000],
                    ['item' => 'Components', 'quantity' => 200, 'weight' => 10000]
                ],
                'status' => 'In Transit',
                'is_active' => true,
            ],
            [
                'container_number' => 'CSCU8765432',
                'container_type' => '20GP',
                'booking_id' => $bookings->skip(1)->first()?->id ?? $bookings->first()?->id,
                'shipment_id' => $shipments->skip(1)->first()?->id ?? $shipments->first()?->id,
                'seal_number' => 'CSC789012',
                'tare_weight' => '2,300 kg',
                'gross_weight' => 10800.25,
                'net_weight' => 8500.25,
                'volume_used' => 28.75,
                'loading_status' => 'Loading',
                'container_condition' => 'Good',
                'current_location' => 'Container Terminal',
                'current_port_id' => $ports->skip(1)->first()?->id ?? $ports->first()?->id,
                'cargo_manifest' => [
                    ['item' => 'Textiles', 'quantity' => 500, 'weight' => 8500]
                ],
                'status' => 'At Terminal',
                'is_active' => true,
            ],
            [
                'container_number' => 'EGLV9876543',
                'container_type' => '40HC',
                'booking_id' => null,
                'shipment_id' => null,
                'seal_number' => null,
                'tare_weight' => '3,980 kg',
                'gross_weight' => null,
                'net_weight' => null,
                'volume_used' => null,
                'loading_status' => 'Empty',
                'container_condition' => 'Good',
                'current_location' => 'Container Yard',
                'current_port_id' => $ports->first()?->id,
                'status' => 'Available',
                'is_active' => true,
            ]
        ];

        foreach ($containers as $containerData) {
            Container::create($containerData);
        }

        $this->command->info('Containers seeded successfully!');
    }
}
