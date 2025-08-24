<?php

namespace App\Services;

use App\Models\Logistics\Booking;
use App\Models\Logistics\Container;
use App\Models\Route;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    /**
     * Create booking with automatic route selection
     */
    public function createBookingWithRoute(array $bookingData): Booking
    {
        // Find optimal route
        $route = $this->findOptimalRoute(
            $bookingData['origin_port_id'],
            $bookingData['destination_port_id'],
            $bookingData['service_type'] ?? 'Ocean'
        );

        if ($route) {
            $bookingData['estimated_departure'] = $this->calculateDeparture($route);
            $bookingData['estimated_arrival'] = $this->calculateArrival($route, $bookingData['estimated_departure']);
        }

        $booking = Booking::create(array_merge($bookingData, [
            'booking_number' => Booking::generateBookingNumber(),
            'created_by' => Auth::id()
        ]));

        return $booking;
    }

    /**
     * Find optimal route between ports
     */
    private function findOptimalRoute($originPortId, $destinationPortId, $serviceType): ?Route
    {
        return Route::where('origin_port_id', $originPortId)
            ->where('destination_port_id', $destinationPortId)
            ->where('service_type', $serviceType)
            ->where('status', 'Active')
            ->orderBy('transit_days')
            ->first();
    }

    /**
     * Calculate departure date based on route schedule
     */
    private function calculateDeparture(Route $route): \Carbon\Carbon
    {
        // Simple logic - can be enhanced with actual schedule
        return now()->addDays($route->frequency_days ?? 7);
    }

    /**
     * Calculate arrival date
     */
    private function calculateArrival(Route $route, $departureDate): \Carbon\Carbon
    {
        return $departureDate->copy()->addDays($route->transit_days);
    }

    /**
     * Get booking statistics
     */
    public function getBookingStatistics(): array
    {
        return [
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('is_confirmed', true)->count(),
            'pending_bookings' => Booking::where('is_confirmed', false)->count(),
            'by_service_type' => Booking::groupBy('service_type')->selectRaw('service_type, count(*) as count')->pluck('count', 'service_type'),
            'container_utilization' => $this->calculateContainerUtilization(),
            'average_booking_value' => $this->calculateAverageBookingValue()
        ];
    }

    /**
     * Calculate container utilization
     */
    private function calculateContainerUtilization(): array
    {
        $containers = Container::all();

        return [
            'total_containers' => $containers->count(),
            'loaded_containers' => $containers->where('loading_status', 'Loaded')->count(),
            'empty_containers' => $containers->where('loading_status', 'Empty')->count(),
            'utilization_rate' => $containers->count() > 0 ?
                ($containers->where('loading_status', '!=', 'Empty')->count() / $containers->count()) * 100 : 0
        ];
    }

    /**
     * Calculate average booking value
     */
    private function calculateAverageBookingValue(): float
    {
        return Booking::whereHas('shipment.invoices')
            ->get()
            ->avg(fn($booking) => $booking->shipment->invoices->sum('total_amount'));
    }
}
