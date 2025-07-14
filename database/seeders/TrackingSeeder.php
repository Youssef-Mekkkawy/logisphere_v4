<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tracking;
use App\Models\Shipment;
use App\Models\Container;
use App\Models\Booking;
use App\Models\Port;

class TrackingSeeder extends Seeder
{
    public function run()
    {
        $shipments = Shipment::all();
        $containers = Container::all();
        $bookings = Booking::all();
        $ports = Port::all();

        if ($shipments->isEmpty()) {
            $this->command->warn('No shipments found. Please run ShipmentSeeder first.');
            return;
        }

        $trackingEvents = [
            // Shipment tracking events
            [
                'tracking_number' => Tracking::generateTrackingNumber(),
                'trackable_type' => Shipment::class,
                'trackable_id' => $shipments->first()->id,
                'event_type' => 'status_change',
                'event_code' => 'BOOKING_CREATED',
                'event_description' => 'Booking created and confirmed',
                'status' => 'Confirmed',
                'location' => 'Alexandria Office',
                'port_id' => $ports->first()?->id,
                'event_datetime' => now()->subDays(10),
                'reported_by' => 'System',
                'employee_id' => 1,
                'is_milestone' => true,
                'is_public' => true,
                'send_notification' => true,
            ],
            [
                'tracking_number' => Tracking::generateTrackingNumber(),
                'trackable_type' => Shipment::class,
                'trackable_id' => $shipments->first()->id,
                'event_type' => 'milestone',
                'event_code' => 'CONTAINER_LOADED',
                'event_description' => 'Container loading completed',
                'status' => 'In Transit',
                'location' => 'Port of Alexandria',
                'port_id' => $ports->first()?->id,
                'facility' => 'Container Terminal A',
                'event_datetime' => now()->subDays(8),
                'vessel_name' => 'MAERSK OSLO',
                'voyage_number' => 'V2024-001',
                'container_number' => 'MSKU7654321',
                'reported_by' => 'Terminal',
                'employee_id' => 1,
                'is_milestone' => true,
                'is_public' => true,
                'send_notification' => true,
            ],
            [
                'tracking_number' => Tracking::generateTrackingNumber(),
                'trackable_type' => Shipment::class,
                'trackable_id' => $shipments->first()->id,
                'event_type' => 'location_update',
                'event_code' => 'VESSEL_DEPARTURE',
                'event_description' => 'Vessel departed from origin port',
                'status' => 'In Transit',
                'location' => 'Mediterranean Sea',
                'latitude' => 31.2001,
                'longitude' => 29.9187,
                'event_datetime' => now()->subDays(7),
                'vessel_name' => 'MAERSK OSLO',
                'voyage_number' => 'V2024-001',
                'reported_by' => 'AIS System',
                'is_milestone' => true,
                'is_public' => true,
                'send_notification' => true,
            ],
            [
                'tracking_number' => Tracking::generateTrackingNumber(),
                'trackable_type' => Shipment::class,
                'trackable_id' => $shipments->first()->id,
                'event_type' => 'location_update',
                'event_code' => 'TRANSIT_UPDATE',
                'event_description' => 'Vessel in transit - normal schedule',
                'status' => 'In Transit',
                'location' => 'Red Sea',
                'latitude' => 27.2579,
                'longitude' => 33.8116,
                'event_datetime' => now()->subDays(5),
                'estimated_datetime' => now()->addDays(8),
                'vessel_name' => 'MAERSK OSLO',
                'voyage_number' => 'V2024-001',
                'reported_by' => 'AIS System',
                'is_milestone' => false,
                'is_public' => true,
                'send_notification' => false,
            ],
            // Container specific tracking
            [
                'tracking_number' => Tracking::generateTrackingNumber(),
                'trackable_type' => Container::class,
                'trackable_id' => $containers->first()?->id ?? 1,
                'event_type' => 'status_change',
                'event_code' => 'CONTAINER_STUFFED',
                'event_description' => 'Container stuffing completed and sealed',
                'status' => 'Sealed',
                'location' => 'Container Freight Station',
                'port_id' => $ports->first()?->id,
                'event_datetime' => now()->subDays(9),
                'container_number' => 'MSKU7654321',
                'reported_by' => 'CFS Operator',
                'employee_id' => 2,
                'remarks' => 'Container sealed with MSK123456',
                'is_milestone' => true,
                'is_public' => true,
                'send_notification' => false,
            ],
            // Booking tracking
            [
                'tracking_number' => Tracking::generateTrackingNumber(),
                'trackable_type' => Booking::class,
                'trackable_id' => $bookings->first()?->id ?? 1,
                'event_type' => 'status_change',
                'event_code' => 'BOOKING_CONFIRMED',
                'event_description' => 'Booking space confirmed with shipping line',
                'status' => 'Confirmed',
                'location' => 'Shipping Line Office',
                'event_datetime' => now()->subDays(8),
                'vessel_name' => 'MAERSK OSLO',
                'voyage_number' => 'V2024-001',
                'reported_by' => 'Agent',
                'employee_id' => 1,
                'is_milestone' => true,
                'is_public' => false,
                'send_notification' => false,
            ]
        ];

        foreach ($trackingEvents as $eventData) {
            Tracking::create($eventData);
        }

        $this->command->info('Tracking events seeded successfully!');
    }
}
