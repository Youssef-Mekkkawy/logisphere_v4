<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logistics\TrackingEvent;
use App\Models\Management\Shipment;
use Carbon\Carbon;

class TrackingEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the shipment with ID SHP-2024-001
        $shipment = Shipment::where('shipment_id', 'SHP-2024-001')->first();

        if ($shipment) {
            $trackingEvents = [
                [
                    'shipment_id' => $shipment->id,
                    'status' => 'Booking Confirmed',
                    'location' => 'Dubai, UAE',
                    'description' => 'Booking has been confirmed and documentation is being prepared.',
                    'event_date' => Carbon::now()->subDays(10),
                    'event_time' => Carbon::now()->subDays(10)->setTime(9, 0),
                    'is_milestone' => true,
                    'is_public' => true
                ],
                [
                    'shipment_id' => $shipment->id,
                    'status' => 'Cargo Picked Up',
                    'location' => 'Dubai, UAE',
                    'description' => 'Cargo has been picked up from shipper and is being transported to port.',
                    'event_date' => Carbon::now()->subDays(8),
                    'event_time' => Carbon::now()->subDays(8)->setTime(14, 30),
                    'is_milestone' => true,
                    'is_public' => true
                ],
                [
                    'shipment_id' => $shipment->id,
                    'status' => 'Arrived at Port',
                    'location' => 'Jebel Ali Port, Dubai',
                    'description' => 'Cargo has arrived at the port and is awaiting loading.',
                    'event_date' => Carbon::now()->subDays(7),
                    'event_time' => Carbon::now()->subDays(7)->setTime(10, 15),
                    'is_milestone' => false,
                    'is_public' => true
                ],
                [
                    'shipment_id' => $shipment->id,
                    'status' => 'Loading Started',
                    'location' => 'Jebel Ali Port, Dubai',
                    'description' => 'Container loading has started onto vessel MSC OSCAR.',
                    'event_date' => Carbon::now()->subDays(6),
                    'event_time' => Carbon::now()->subDays(6)->setTime(16, 45),
                    'is_milestone' => false,
                    'is_public' => true,
                    'vessel_name' => 'MSC OSCAR',
                    'container_number' => 'MSCU1234567'
                ],
                [
                    'shipment_id' => $shipment->id,
                    'status' => 'Departed from Origin',
                    'location' => 'Jebel Ali Port, Dubai',
                    'description' => 'Vessel has departed from origin port.',
                    'event_date' => Carbon::now()->subDays(5),
                    'event_time' => Carbon::now()->subDays(5)->setTime(8, 0),
                    'is_milestone' => true,
                    'is_public' => true,
                    'vessel_name' => 'MSC OSCAR'
                ],
                [
                    'shipment_id' => $shipment->id,
                    'status' => 'In Transit',
                    'location' => 'Arabian Sea',
                    'description' => 'Vessel is in transit to destination port.',
                    'event_date' => Carbon::now()->subDays(3),
                    'event_time' => Carbon::now()->subDays(3)->setTime(12, 0),
                    'is_milestone' => false,
                    'is_public' => true,
                    'vessel_name' => 'MSC OSCAR'
                ],
                [
                    'shipment_id' => $shipment->id,
                    'status' => 'Approaching Destination',
                    'location' => 'Red Sea',
                    'description' => 'Vessel is approaching destination port.',
                    'event_date' => Carbon::now()->subDays(1),
                    'event_time' => Carbon::now()->subDays(1)->setTime(6, 30),
                    'is_milestone' => false,
                    'is_public' => true,
                    'vessel_name' => 'MSC OSCAR'
                ]
            ];

            foreach ($trackingEvents as $event) {
                TrackingEvent::create($event);
            }

            $this->command->info('Sample tracking events created for shipment SHP-2024-001');
        } else {
            $this->command->error('Shipment SHP-2024-001 not found. Please create a shipment first.');
        }
    }
}
