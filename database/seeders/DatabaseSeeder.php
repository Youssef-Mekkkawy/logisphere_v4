<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Logistics\Port;
use App\Models\Logistics\ShipmentType;
use App\Models\Logistics\ShippingAgency;
use App\Models\Management\Company;
use App\Models\Management\Employee;
use Database\Seeders\RouteSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AllStatusSeeder::class,
            NationalitiesSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            PortSeeder::class, // This already handles all the ports properly
            ShippingAgencySeeder::class,
            CompanySeeder::class,
            ShipmentSeeder::class,
            RouteSeeder::class,
            BookingSeeder::class,
            ContainerSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
 
            ConsigneeNotifySeeder::class,
            ContainerLoadingSeeder::class,
            InspectionTypeSeeder::class,
            DestinationSeeder::class,
            PortOperationSeeder::class,
            BoslaGomrokSeeder::class,
            CooTypeSeeder::class,
            QuantityTypeSeeder::class,
            ServiceSeeder::class,
            ShipmentTypeSeeder::class, 
            ShipperSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');

        // Create default users
        $users = [
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'email' => 'admin@logisphere.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Operations Manager',
                'username' => 'manager',
                'email' => 'manager@logisphere.com',
                'password' => Hash::make('manager123'),
                'role' => 'manager'
            ],
            [
                'name' => 'Standard User',
                'username' => 'user',
                'email' => 'user@logisphere.com',
                'password' => Hash::make('user123'),
                'role' => 'user'
            ]
        ];

        $results = [];
        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
            $results[] = "✅ {$userData['name']} ({$userData['username']}) - " . ($user->wasRecentlyCreated ? 'Created' : 'Updated');
        }

        // Create sample ports
        $ports = [
            ['port_code' => 'CNSHA', 'port_name' => 'Shanghai Port', 'country' => 'China', 'city' => 'Shanghai', 'port_type' => 'Container', 'status' => 'Active'],
            ['port_code' => 'USLAX', 'port_name' => 'Los Angeles Port', 'country' => 'USA', 'city' => 'Los Angeles', 'port_type' => 'Container', 'status' => 'Active'],
            ['port_code' => 'DEHAM', 'port_name' => 'Hamburg Port', 'country' => 'Germany', 'city' => 'Hamburg', 'port_type' => 'Container', 'status' => 'Active'],
            ['port_code' => 'JPNGO', 'port_name' => 'Tokyo Port', 'country' => 'Japan', 'city' => 'Tokyo', 'port_type' => 'Container', 'status' => 'Active'],
            ['port_code' => 'NLRTM', 'port_name' => 'Rotterdam Port', 'country' => 'Netherlands', 'city' => 'Rotterdam', 'port_type' => 'Container', 'status' => 'Active'],
        ];

        foreach ($ports as $portData) {
            Port::firstOrCreate(
                ['port_code' => $portData['port_code']],
                $portData
            );
        }

        // Create sample companies
        $companies = [
            [
                'name' => 'Global Trading Co.',
                'type' => 'Client',
                'contact_person' => 'John Smith',
                'email' => 'john@globaltrading.com',
                'phone' => '+1-555-0123',
                'country' => 'USA',
                'status' => 'Active'
            ],
            [
                'name' => 'Euro Imports Ltd.',
                'type' => 'Client',
                'contact_person' => 'Maria Schmidt',
                'email' => 'maria@euroimports.com',
                'phone' => '+49-123-456789',
                'country' => 'Germany',
                'status' => 'Active'
            ],
            [
                'name' => 'Asia Pacific Corp.',
                'type' => 'Client',
                'contact_person' => 'Chen Wei',
                'email' => 'chen@asiapacific.com',
                'phone' => '+86-21-1234-5678',
                'country' => 'China',
                'status' => 'Active'
            ],
            [
                'name' => 'Ocean Freight Solutions',
                'type' => 'Supplier',
                'contact_person' => 'Maria Garcia',
                'email' => 'maria@oceanfreight.com',
                'phone' => '+34-987-654321',
                'country' => 'Spain',
                'service_type' => 'Shipping',
                'status' => 'Active'
            ]
        ];

        foreach ($companies as $companyData) {
            Company::firstOrCreate(
                ['name' => $companyData['name']],
                $companyData
            );
        }

        // Create sample employees
        $employees = [
            [
                'employee_id' => 'EMP-001',
                'name' => 'Ahmed Hassan',
                'department' => 'Operations',
                'position' => 'Logistics Coordinator',
                'email' => 'ahmed@logisphere.com',
                'phone' => '+20-100-123-4567',
                'hire_date' => '2024-01-15',
                'salary' => 3000.00,
                'status' => 'Active'
            ],
            [
                'employee_id' => 'EMP-002',
                'name' => 'Fatima Ali',
                'department' => 'Customs',
                'position' => 'Customs Specialist',
                'email' => 'fatima@logisphere.com',
                'phone' => '+20-100-234-5678',
                'hire_date' => '2024-02-01',
                'salary' => 2800.00,
                'status' => 'Active'
            ]
        ];

        foreach ($employees as $employeeData) {
            Employee::firstOrCreate(
                ['employee_id' => $employeeData['employee_id']],
                $employeeData
            );
        }

        // Create sample shipping agencies
        $agencies = [
            [
                'code' => 'MAR001',
                'name' => 'Maersk Line Agency',
                'contact_person' => 'David Johnson',
                'email' => 'david@maersk.com',
                'phone' => '+1-555-0101',
                'country' => 'Denmark',
                'status' => 'Active'
            ],
            [
                'code' => 'COS002',
                'name' => 'COSCO Shipping Agency',
                'contact_person' => 'Li Wei',
                'email' => 'li.wei@cosco.com',
                'phone' => '+86-21-5555-0102',
                'country' => 'China',
                'status' => 'Active'
            ]
        ];

        foreach ($agencies as $agencyData) {
            ShippingAgency::firstOrCreate(
                ['code' => $agencyData['code']],
                $agencyData
            );
        }

        // Create sample shipment types
        $types = [
            [
                'type_code' => 'FCL',
                'type_name' => 'Full Container Load',
                'subcategory' => 'Standard FCL',
                'description' => 'Complete container for single consignee',
                'category' => 'Container',
                'status' => 'Active'
            ],
            [
                'type_code' => 'LCL',
                'type_name' => 'Less Container Load',
                'subcategory' => 'Consolidated LCL',
                'description' => 'Shared container space',
                'category' => 'Container',
                'status' => 'Active'
            ]
        ];

        foreach ($types as $typeData) {
            ShipmentType::firstOrCreate(
                ['type_code' => $typeData['type_code']],
                $typeData
            );
        }

        $this->command->info('🎯 Sample data created successfully!');
        $this->command->info('📦 Quantity Types: 16 professional measurement units created');
        $this->command->info('🏢 Companies: ' . count($companies) . ' sample companies created');
        $this->command->info('👥 Users: ' . count($users) . ' default users created');
        $this->command->info('🚢 Ports: ' . count($ports) . ' major ports created');
    }
}
