<?php

namespace App\Services;

use App\Models\Tracking;
use App\Models\Shipment;
use App\Models\Container;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TrackingService
{
    /**
     * Add tracking event to multiple entities
     */
    public function addBulkTrackingEvent(array $entities, array $eventData): array
    {
        $createdEvents = [];

        foreach ($entities as $entity) {
            $event = $entity->addTrackingEvent(array_merge($eventData, [
                'reported_by' => 'System',
                'employee_id' => Auth::user()->employee_id ?? null
            ]));
            
            $createdEvents[] = $event;
        }

        return $createdEvents;
    }

    /**
     * Get tracking timeline for shipment
     */
    public function getShipmentTimeline(Shipment $shipment): array
    {
        $events = collect();

        // Shipment events
        $events = $events->merge($shipment->trackingEvents);

        // Booking events
        foreach ($shipment->bookings as $booking) {
            $events = $events->merge($booking->trackingEvents);
        }

        // Container events
        foreach ($shipment->containers as $container) {
            $events = $events->merge($container->trackingEvents);
        }

        return $events->sortByDesc('event_datetime')
                     ->values()
                     ->map(function ($event) {
                         return [
                             'id' => $event->id,
                             'tracking_number' => $event->tracking_number,
                             'type' => $event->trackable_type,
                             'event_type' => $event->event_type,
                             'description' => $event->event_description,
                             'status' => $event->status,
                             'location' => $event->location,
                             'datetime' => $event->event_datetime,
                             'is_milestone' => $event->is_milestone,
                             'vessel_name' => $event->vessel_name,
                             'container_number' => $event->container_number
                         ];
                     })
                     ->toArray();
    }

    /**
     * Get public tracking information
     */
    public function getPublicTracking(string $trackingNumber): ?array
    {
        $shipment = Shipment::where('shipment_id', $trackingNumber)->first();
        
        if (!$shipment) {
            return null;
        }

        $timeline = $this->getShipmentTimeline($shipment);
        $publicEvents = array_filter($timeline, fn($event) => $event['is_public'] ?? true);

        return [
            'shipment' => [
                'shipment_id' => $shipment->shipment_id,
                'origin' => $shipment->originPort->name ?? 'Unknown',
                'destination' => $shipment->destinationPort->name ?? 'Unknown',
                'status' => $shipment->status,
                'eta' => $shipment->eta
            ],
            'timeline' => array_values($publicEvents),
            'milestones' => array_filter($publicEvents, fn($event) => $event['is_milestone']),
            'current_location' => $this->getCurrentLocation($publicEvents)
        ];
    }

    /**
     * Get current location from latest tracking event
     */
    private function getCurrentLocation(array $events): ?string
    {
        $latest = reset($events);
        return $latest ? $latest['location'] : null;
    }

    /**
     * Generate tracking analytics
     */
    public function getTrackingAnalytics(): array
    {
        return [
            'total_events' => Tracking::count(),
            'events_today' => Tracking::whereDate('event_datetime', today())->count(),
            'events_by_type' => Tracking::groupBy('event_type')->selectRaw('event_type, count(*) as count')->pluck('count', 'event_type'),
            'milestone_completion_rate' => $this->calculateMilestoneCompletionRate(),
            'average_transit_time' => $this->calculateAverageTransitTime(),
            'delay_analysis' => $this->analyzeDelays()
        ];
    }

    /**
     * Calculate milestone completion rate
     */
    private function calculateMilestoneCompletionRate(): float
    {
        $totalShipments = Shipment::count();
        $shipmentsWithMilestones = Shipment::whereHas('trackingEvents', function($query) {
            $query->where('is_milestone', true);
        })->count();

        return $totalShipments > 0 ? ($shipmentsWithMilestones / $totalShipments) * 100 : 0;
    }

    /**
     * Calculate average transit time
     */
    private function calculateAverageTransitTime(): ?float
    {
        $deliveredShipments = Shipment::where('status', 'Delivered')
                                    ->whereHas('trackingEvents', function($query) {
                                        $query->where('event_code', 'DELIVERED');
                                    })
                                    ->get();

        if ($deliveredShipments->isEmpty()) {
            return null;
        }

        $totalDays = $deliveredShipments->sum(function($shipment) {
            $start = $shipment->trackingEvents()->orderBy('event_datetime')->first();
            $end = $shipment->trackingEvents()->where('event_code', 'DELIVERED')->first();
            
            return $start && $end ? $start->event_datetime->diffInDays($end->event_datetime) : 0;
        });

        return $totalDays / $deliveredShipments->count();
    }

    /**
     * Analyze delays
     */
    private function analyzeDelays(): array
    {
        $shipments = Shipment::with('trackingEvents')->get();
        $delays = [];

        foreach ($shipments as $shipment) {
            $estimatedEvents = $shipment->trackingEvents()
                                      ->whereNotNull('estimated_datetime')
                                      ->whereNotNull('actual_datetime')
                                      ->get();

            foreach ($estimatedEvents as $event) {
                if ($event->actual_datetime > $event->estimated_datetime) {
                    $delays[] = [
                        'shipment_id' => $shipment->shipment_id,
                        'event_type' => $event->event_type,
                        'delay_hours' => $event->estimated_datetime->diffInHours($event->actual_datetime)
                    ];
                }
            }
        }

        return [
            'total_delays' => count($delays),
            'average_delay_hours' => count($delays) > 0 ? array_sum(array_column($delays, 'delay_hours')) / count($delays) : 0,
            'delays_by_event_type' => array_count_values(array_column($delays, 'event_type'))
        ];
    }
}
