<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Destination;
use App\Models\Country;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some countries for foreign key
        $egypt = Country::where('name', 'Egypt')->first();
        $usa = Country::where('name', 'United States')->first();
        $germany = Country::where('name', 'Germany')->first();
        $uae = Country::where('name', 'United Arab Emirates')->first();
        $china = Country::where('name', 'China')->first();
        $uk = Country::where('name', 'United Kingdom')->first();

        $destinations = [
            [
                'destination_code' => 'DST001',
                'destination_name' => 'Cairo International Airport',
                'city' => 'Cairo',
                'state_province' => 'Cairo Governorate',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'postal_code' => '11776',
                'address' => 'Cairo International Airport, Airport Road, Heliopolis',
                'destination_type' => 'Airport',
                'latitude' => 30.1219,
                'longitude' => 31.4056,
                'contact_person' => 'Ahmed Saleh',
                'contact_phone' => '+20-2-2265-5000',
                'contact_email' => 'cargo@cairo-airport.com',
                'delivery_instructions' => 'Cargo deliveries accepted 24/7. Follow airport security protocols.',
                'access_restrictions' => 'Airport security clearance required. No unauthorized vehicles.',
                'facilities' => ['Loading Dock', 'Storage Area', 'Customs Office', 'Security Gate', 'Office Building'],
                'requires_appointment' => true,
                'timezone' => 'Africa/Cairo',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST002',
                'destination_name' => 'Alexandria Port Terminal',
                'city' => 'Alexandria',
                'state_province' => 'Alexandria Governorate',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'postal_code' => '21500',
                'address' => 'Eastern Harbor, Alexandria Port Authority',
                'destination_type' => 'Port',
                'latitude' => 31.2156,
                'longitude' => 29.9553,
                'contact_person' => 'Mohamed El-Rashid',
                'contact_phone' => '+20-3-4807-6000',
                'contact_email' => 'operations@alexport.gov.eg',
                'delivery_instructions' => 'Port operations 24/7. Container delivery through Gate 3.',
                'access_restrictions' => 'Port security badge required. Heavy vehicles only during designated hours.',
                'facilities' => ['Loading Dock', 'Unloading Dock', 'Container Yard', 'Customs Office', 'Weighbridge'],
                'requires_appointment' => false,
                'timezone' => 'Africa/Cairo',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST003',
                'destination_name' => 'Downtown Cairo Warehouse',
                'city' => 'Cairo',
                'state_province' => 'Cairo Governorate',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'postal_code' => '11511',
                'address' => '45 Industrial Street, Shubra El-Kheima',
                'destination_type' => 'Warehouse',
                'latitude' => 30.1285,
                'longitude' => 31.2441,
                'contact_person' => 'Fatma Hassan',
                'contact_phone' => '+20-2-2233-4455',
                'contact_email' => 'warehouse@cairo-logistics.com',
                'delivery_instructions' => 'Operating hours: 8 AM - 6 PM, Sunday to Thursday. Use loading dock B for containers.',
                'access_restrictions' => 'No weekend deliveries. Maximum vehicle height 4.5m.',
                'facilities' => ['Loading Dock', 'Storage Area', 'Office Building', 'Parking Area'],
                'requires_appointment' => true,
                'timezone' => 'Africa/Cairo',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST004',
                'destination_name' => 'Los Angeles Port Terminal',
                'city' => 'Los Angeles',
                'state_province' => 'California',
                'country' => 'United States',
                'country_id' => $usa?->id,
                'postal_code' => '90731',
                'address' => 'Port of Los Angeles, San Pedro Bay',
                'destination_type' => 'Port',
                'latitude' => 33.7367,
                'longitude' => -118.2922,
                'contact_person' => 'Robert Johnson',
                'contact_phone' => '+1-310-732-3508',
                'contact_email' => 'terminal@portla.org',
                'delivery_instructions' => '24/7 operations. RFID tracking required for all vehicles.',
                'access_restrictions' => 'TWIC card mandatory. Environmental restrictions for older trucks.',
                'facilities' => ['Loading Dock', 'Unloading Dock', 'Container Yard', 'Customs Office', 'Weighbridge', 'Security Gate'],
                'requires_appointment' => false,
                'timezone' => 'America/Los_Angeles',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST005',
                'destination_name' => 'Hamburg Container Terminal',
                'city' => 'Hamburg',
                'state_province' => 'Hamburg',
                'country' => 'Germany',
                'country_id' => $germany?->id,
                'postal_code' => '20457',
                'address' => 'Burchardkai, Container Terminal Tollerort',
                'destination_type' => 'Terminal',
                'latitude' => 53.5439,
                'longitude' => 9.9678,
                'contact_person' => 'Klaus Weber',
                'contact_phone' => '+49-40-3703-0',
                'contact_email' => 'info@hhla.de',
                'delivery_instructions' => 'EU customs procedures apply. German and English spoken.',
                'access_restrictions' => 'EU driver certification required. Environmental zone regulations.',
                'facilities' => ['Loading Dock', 'Unloading Dock', 'Container Yard', 'Customs Office', 'Storage Area'],
                'requires_appointment' => true,
                'timezone' => 'Europe/Berlin',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST006',
                'destination_name' => 'Dubai Jebel Ali Free Zone',
                'city' => 'Dubai',
                'state_province' => 'Dubai',
                'country' => 'United Arab Emirates',
                'country_id' => $uae?->id,
                'postal_code' => '17000',
                'address' => 'Jebel Ali Free Zone Authority, South Zone',
                'destination_type' => 'Port',
                'latitude' => 25.0124,
                'longitude' => 55.1003,
                'contact_person' => 'Omar Al-Rashid',
                'contact_phone' => '+971-4-881-5555',
                'contact_email' => 'logistics@jafza.ae',
                'delivery_instructions' => 'Free zone procedures. 24/7 operations with advance notice.',
                'access_restrictions' => 'JAFZA permit required. Security screening for all vehicles.',
                'facilities' => ['Loading Dock', 'Unloading Dock', 'Container Yard', 'Storage Area', 'Customs Office'],
                'requires_appointment' => false,
                'timezone' => 'Asia/Dubai',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST007',
                'destination_name' => 'Shanghai Electronics Factory',
                'city' => 'Shanghai',
                'state_province' => 'Shanghai',
                'country' => 'China',
                'country_id' => $china?->id,
                'postal_code' => '201203',
                'address' => '888 Pudong Industrial Park, Zhangjiang Hi-Tech',
                'destination_type' => 'Factory',
                'latitude' => 31.2304,
                'longitude' => 121.4737,
                'contact_person' => 'Li Wei',
                'contact_phone' => '+86-21-5080-5555',
                'contact_email' => 'receiving@shanghai-electronics.com',
                'delivery_instructions' => 'Factory hours: 8 AM - 6 PM, Monday to Saturday. Use Gate 2 for deliveries.',
                'access_restrictions' => 'Chinese customs clearance required. No photography inside factory.',
                'facilities' => ['Loading Dock', 'Storage Area', 'Security Gate', 'Office Building'],
                'requires_appointment' => true,
                'timezone' => 'Asia/Shanghai',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST008',
                'destination_name' => 'London Heathrow Cargo Village',
                'city' => 'London',
                'state_province' => 'England',
                'country' => 'United Kingdom',
                'country_id' => $uk?->id,
                'postal_code' => 'TW6 3AF',
                'address' => 'Heathrow Airport, Cargo Village, Southern Perimeter Road',
                'destination_type' => 'Airport',
                'latitude' => 51.4700,
                'longitude' => -0.4543,
                'contact_person' => 'James Smith',
                'contact_phone' => '+44-20-8745-7000',
                'contact_email' => 'cargo@heathrow.com',
                'delivery_instructions' => 'Airside delivery requires security clearance. Operating 24/7.',
                'access_restrictions' => 'UK security vetting required. Low emission zone compliance.',
                'facilities' => ['Loading Dock', 'Cold Storage', 'Customs Office', 'Security Gate', 'Storage Area'],
                'requires_appointment' => true,
                'timezone' => 'Europe/London',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST009',
                'destination_name' => 'Cairo Distribution Center',
                'city' => 'Giza',
                'state_province' => 'Giza Governorate',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'postal_code' => '12345',
                'address' => '123 6th of October City, Industrial Zone',
                'destination_type' => 'Warehouse',
                'latitude' => 29.9669,
                'longitude' => 31.0132,
                'contact_person' => 'Sarah Ahmed',
                'contact_phone' => '+20-2-3835-7777',
                'contact_email' => 'distribution@cairo-dc.com',
                'delivery_instructions' => 'Large distribution center with multiple docks. Preferred delivery time: 9 AM - 4 PM.',
                'access_restrictions' => 'Appointment required for container deliveries. Weight limit 40 tons.',
                'facilities' => ['Loading Dock', 'Unloading Dock', 'Storage Area', 'Weighbridge', 'Parking Area'],
                'requires_appointment' => true,
                'timezone' => 'Africa/Cairo',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST010',
                'destination_name' => 'Suez City Center',
                'city' => 'Suez',
                'state_province' => 'Suez Governorate',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'postal_code' => '43511',
                'address' => 'Downtown Suez, Commercial District',
                'destination_type' => 'City',
                'latitude' => 29.9668,
                'longitude' => 32.5498,
                'contact_person' => 'Mahmoud Hassan',
                'contact_phone' => '+20-62-319-8888',
                'contact_email' => 'delivery@suez-center.com',
                'delivery_instructions' => 'Urban delivery area. Small vehicles preferred due to narrow streets.',
                'access_restrictions' => 'No large trucks in city center. Delivery hours: 8 AM - 8 PM.',
                'facilities' => ['Parking Area', 'Office Building'],
                'requires_appointment' => false,
                'timezone' => 'Africa/Cairo',
                'status' => 'Active'
            ],
            [
                'destination_code' => 'DST011',
                'destination_name' => 'Inactive Test Destination',
                'city' => 'Test City',
                'country' => 'Egypt',
                'country_id' => $egypt?->id,
                'address' => 'Test Address for Inactive Location',
                'destination_type' => 'Warehouse',
                'contact_person' => 'Test Person',
                'contact_phone' => '+20-2-000-0000',
                'contact_email' => 'test@inactive.com',
                'delivery_instructions' => 'This is an inactive destination for testing purposes.',
                'facilities' => ['Storage Area'],
                'requires_appointment' => true,
                'timezone' => 'Africa/Cairo',
                'status' => 'Inactive'
            ]
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }

        $this->command->info('Destination seeder completed successfully!');
    }
}
