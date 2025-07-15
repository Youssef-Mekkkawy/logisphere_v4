<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Port;

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
                'origin_port_id' => $ports->where('name', 'like', '%Alexandria%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('name', 'like', '%Jeddah%')->first()?->id ?? 2,
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
                'origin_port_id' => $ports->where('name', 'like', '%Dubai%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('name', 'like', '%Mumbai%')->first()?->id ?? 2,
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
                'origin_port_id' => $ports->where('name', 'like', '%Shanghai%')->first()?->id ?? 1,
                'destination_port_id' => $ports->where('name', 'like', '%Rotterdam%')->first()?->id ?? 2,
                'service_type' => 'Ocean',
                'transport_mode' => 'Container',
                'transit_days' => 35,
                'frequency_days' => 7,
                'distance_km' => 19500.00,
                'distance_nm' => 10529.16,
                'is_direct' => false,
                'intermediate_ports' => null, // No intermediate ports defined yet
                'base_rate' => 2800.00,
                'rate_currency' => 'USD',
                'rate_unit' => 'per container',
                'status' => 'Active',
                'effective_from' => now()->subMonths(12),
            ]
        ];

        foreach ($routes as $routeData) {
            Route::firstOrCreate(
                ['route_code' => $routeData['route_code']], // Find by route_code
                $routeData // Create with this data if not found
            );
        }

        $this->command->info('Routes seeded successfully!');
    }
}