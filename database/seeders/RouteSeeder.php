<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Logistics\Port;

class RouteSeeder extends Seeder
{
    public function run()
    {
        $ports = Port::all();

        if ($ports->count() < 2) {
            $this->command->warn('Not enough ports to create routes. Please run PortSeeder first.');
            return;
        }

        $routes = [
            [
                'route_code' => 'RTE-001',
                'route_name' => 'Alexandria to Jeddah Express',
                'origin_port_id' => $ports->where('port_name', 'like', '%Alexandria%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('port_name', 'like', '%Jeddah%')->first()?->id ?? 2,
                'service_type' => 'Ocean',
                'transport_mode' => 'Container',
                'transit_days' => 5,
                'frequency_days' => 7,
                'distance_km' => 1200.50,
                'distance_nm' => 648.11,
                'is_direct' => true,
                'intermediate_ports' => null, // Direct route - no stops
                'base_rate' => 850.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'status' => 'Active',
                'effective_from' => now()->subMonths(6),
            ],
            [
                'route_code' => 'RTE-002',
                'route_name' => 'Dubai to Mumbai Gateway',
                'origin_port_id' => $ports->where('port_name', 'like', '%Dubai%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('port_name', 'like', '%Mumbai%')->first()?->id ?? 2,
                'service_type' => 'Ocean',
                'transport_mode' => 'Container',
                'transit_days' => 8,
                'frequency_days' => 14,
                'distance_km' => 1950.25,
                'distance_nm' => 1052.83,
                'is_direct' => false,
                // Convert array to JSON explicitly
                'intermediate_ports' => json_encode([3, 4]), // Port IDs for stops
                'base_rate' => 1200.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'status' => 'Active',
                'effective_from' => now()->subMonths(3),
            ],
            [
                'route_code' => 'RTE-003',
                'route_name' => 'Shanghai to Rotterdam Main Line',
                'origin_port_id' => $ports->where('port_name', 'like', '%Shanghai%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('port_name', 'like', '%Rotterdam%')->first()?->id ?? 2,
                'service_type' => 'Ocean',
                'transport_mode' => 'Container',
                'transit_days' => 25,
                'frequency_days' => 7,
                'distance_km' => 19500.75,
                'distance_nm' => 10530.50,
                'is_direct' => false,
                'intermediate_ports' => json_encode([2, 5]), // Via Suez Canal
                'base_rate' => 2800.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'status' => 'Active',
                'effective_from' => now()->subMonths(12),
            ],
            [
                'route_code' => 'RTE-004',
                'route_name' => 'Asia Europe Express',
                'origin_port_id' => $ports->where('port_name', 'like', '%Singapore%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('port_name', 'like', '%Hamburg%')->first()?->id ?? 2,
                'service_type' => 'Ocean',
                'transport_mode' => 'Container',
                'transit_days' => 30,
                'frequency_days' => 14,
                'distance_km' => 20800.00,
                'distance_nm' => 11232.43,
                'is_direct' => false,
                'intermediate_ports' => json_encode([1, 3]), // Via Alexandria
                'base_rate' => 3200.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'status' => 'Active',
                'effective_from' => now()->subMonths(9),
            ],
            [
                'route_code' => 'RTE-005',
                'route_name' => 'Red Sea Express',
                'origin_port_id' => $ports->where('port_name', 'like', '%Suez%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('port_name', 'like', '%Jebel Ali%')->first()?->id ?? 2,
                'service_type' => 'Ocean',
                'transport_mode' => 'Container',
                'transit_days' => 6,
                'frequency_days' => 5,
                'distance_km' => 1800.25,
                'distance_nm' => 972.19,
                'is_direct' => true,
                'intermediate_ports' => null,
                'base_rate' => 900.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'status' => 'Active',
                'effective_from' => now()->subMonths(4),
            ]
        ];

        foreach ($routes as $routeData) {
            Route::create($routeData);
        }

        $this->command->info('Routes seeded successfully!');
    }
}
