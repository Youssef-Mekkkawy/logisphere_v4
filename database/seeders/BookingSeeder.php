<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Shipment;
use App\Models\Company;
use App\Models\ShippingAgency;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $shipments = Shipment::all();
        $companies = Company::where('type', 'Client')->get();
        $agencies = ShippingAgency::all();

        if ($shipments->isEmpty() || $companies->isEmpty()) {
            $this->command->warn('Missing required data. Please seed shipments and companies first.');
            return;
        }

        $bookings = [
            [
                'booking_number' => Booking::generateBookingNumber(),
                'booking_reference' => 'BKG-REF-001',
                'shipment_id' => $shipments->first()->id,
                'company_id' => $companies->first()->id,
                'shipping_agency_id' => $agencies->first()?->id,
                'vessel_name' => 'MAERSK OSLO',
                'voyage_number' => 'V2024-001',
                'booking_date' => now()->subDays(10),
                'estimated_departure' => now()->addDays(5),
                'estimated_arrival' => now()->addDays(15),
                'cut_off_date' => now()->addDays(3),
                'service_type' => 'FCL',
                'container_count' => 2,
                'container_type' => '40GP',
                'cargo_weight' => 25000.50,
                'cargo_volume' => 65.30,
                'commodity_description' => 'Electronics and Components',
                'incoterms' => 'FOB',
                'status' => 'Confirmed',
                'is_confirmed' => true,
                'confirmed_at' => now()->subDays(8),
                'confirmed_by' => 1,
                'created_by' => 1,
            ],
            [
                'booking_number' => Booking::generateBookingNumber(),
                'booking_reference' => 'BKG-REF-002',
                'shipment_id' => $shipments->skip(1)->first()?->id ?? $shipments->first()->id,
                'company_id' => $companies->skip(1)->first()?->id ?? $companies->first()->id,
                'shipping_agency_id' => $agencies->first()?->id,
                'vessel_name' => 'COSCO GUANGZHOU',
                'voyage_number' => 'V2024-002',
                'booking_date' => now()->subDays(5),
                'estimated_departure' => now()->addDays(10),
                'estimated_arrival' => now()->addDays(25),
                'cut_off_date' => now()->addDays(8),
                'service_type' => 'LCL',
                'container_count' => 1,
                'container_type' => '20GP',
                'cargo_weight' => 8500.25,
                'cargo_volume' => 28.75,
                'commodity_description' => 'Textile and Garments',
                'incoterms' => 'CIF',
                'status' => 'Pending',
                'is_confirmed' => false,
                'created_by' => 1,
            ]
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }

        $this->command->info('Bookings seeded successfully!');
    }
}
