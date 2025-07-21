<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Port;
use App\Models\Employee;
use App\Models\ShippingAgency;
use App\Models\ShipmentType;
use Database\Seeders\RouteSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Phase 1: Lookup/Reference Tables (No Dependencies)
            AllStatusSeeder::class,
            NationalitiesSeeder::class,
            UserSeeder::class,              // ✅ Add users first

            // Phase 2: Core Infrastructure 
            PortSeeder::class,              // ✅ Working
            CountrySeeder::class,           // Must run first
            ShippingAgencySeeder::class,
            CompanySeeder::class,           // ✅ Created

            // Phase 3: Business Logic
            ShipmentSeeder::class,          // ✅ Created (needs companies + ports)
            RouteSeeder::class,             // ✅ Working (now has ports)

            // Phase 4: Dependent Tables
            BookingSeeder::class,           // ✅ Now has shipments/companies/users
            ContainerSeeder::class,         // ✅ Working (has bookings/shipments/ports)
            PermissionSeeder::class,
            RoleSeeder::class,
            UpdatedUserSeeder::class,

        ]);

        $this->command->info('🎉 All seeders completed successfully!');
        // $users = [
        //     [
        //         'name' => 'System Administrator',
        //         'username' => 'admin',
        //         'email' => 'admin@logiflow.com',
        //         'password' => Hash::make('admin123'),
        //         'role' => 'admin'
        //     ],
        //     [
        //         'name' => 'Operations Manager',
        //         'username' => 'manager',
        //         'email' => 'manager@logiflow.com',
        //         'password' => Hash::make('manager123'),
        //         'role' => 'manager'
        //     ],
        //     [
        //         'name' => 'Standard User',
        //         'username' => 'user',
        //         'email' => 'user@logiflow.com',
        //         'password' => Hash::make('user123'),
        //         'role' => 'user'
        //     ]
        // ];

        // foreach ($users as $userData) {
        //     User::firstOrCreate(
        //         ['username' => $userData['username']],
        //         $userData
        //     );
        // }
        $users = [
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'email' => 'admin@logiflow.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Operations Manager',
                'username' => 'manager',
                'email' => 'manager@logiflow.com',
                'password' => Hash::make('manager123'),
                'role' => 'manager'
            ],
            [
                'name' => 'Standard User',
                'username' => 'user',
                'email' => 'user@logiflow.com',
                'password' => Hash::make('user123'),
                'role' => 'user'
            ]
        ];

        $results = [];
        foreach ($users as $userData) {
            $user = \App\Models\User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
            $results[] = "✅ {$userData['name']} ({$userData['username']}) - " . ($user->wasRecentlyCreated ? 'Created' : 'Updated');
        }



        // Create sample ports
        $ports = [
            ['code' => 'CNSHA', 'name' => 'Shanghai Port', 'country' => 'China', 'city' => 'Shanghai', 'type' => 'Container', 'status' => 'Active'],
            ['code' => 'USLAX', 'name' => 'Los Angeles Port', 'country' => 'USA', 'city' => 'Los Angeles', 'type' => 'Container', 'status' => 'Active'],
            ['code' => 'DEHAM', 'name' => 'Hamburg Port', 'country' => 'Germany', 'city' => 'Hamburg', 'type' => 'Container', 'status' => 'Active'],
            ['code' => 'JPNGO', 'name' => 'Tokyo Port', 'country' => 'Japan', 'city' => 'Tokyo', 'type' => 'Container', 'status' => 'Active'],
            ['code' => 'NLRTM', 'name' => 'Rotterdam Port', 'country' => 'Netherlands', 'city' => 'Rotterdam', 'type' => 'Container', 'status' => 'Active'],
        ];

        foreach ($ports as $portData) {
            Port::firstOrCreate(
                ['code' => $portData['code']],
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
                'email' => 'ahmed@logiflow.com',
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
                'email' => 'fatima@logiflow.com',
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
                'code' => 'FCL',
                'name' => 'Full Container Load',
                'sub_type' => 'Standard FCL',
                'description' => 'Complete container for single consignee',
                'category' => 'Container',
                'status' => 'Active'
            ],
            [
                'code' => 'LCL',
                'name' => 'Less Container Load',
                'sub_type' => 'Consolidated LCL',
                'description' => 'Shared container space',
                'category' => 'Container',
                'status' => 'Active'
            ]
        ];

        foreach ($types as $typeData) {
            ShipmentType::firstOrCreate(
                ['code' => $typeData['code']],
                $typeData
            );
        }
    }
}
