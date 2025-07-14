<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\Booking;
use App\Models\Container;
use App\Models\AllStatus;
use App\Models\Route;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EnhancedShipmentService extends ShipmentService
{
    /**
     * Create shipment with booking and containers
     */
    public function createShipmentWithBooking(array $shipmentData, array $bookingData = [], array $containerData = []): Shipment
    {
        DB::beginTransaction();

        try {
            // Create shipment
            $shipment = $this->createShipment($shipmentData);

            // Create booking if data provided
            if (!empty($bookingData)) {
                $booking = $this->createBookingForShipment($shipment, $bookingData);

                // Create containers if data provided
                if (!empty($containerData)) {
                    $this->createContainersForBooking($booking, $containerData);
                }
            }

            // Set initial status with tracking
            $defaultStatus = AllStatus::getDefault('shipment');
            if ($defaultStatus) {
                $shipment->updateStatusWithTracking($defaultStatus->status_name, [
                    'location' => 'Origin Office',
                    'event_description' => 'Shipment created and ready for processing'
                ]);
            }

            DB::commit();
            return $shipment;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Create booking for shipment
     */
    public function createBookingForShipment(Shipment $shipment, array $bookingData): Booking
    {
        $bookingData = array_merge($bookingData, [
            'booking_number' => Booking::generateBookingNumber(),
            'shipment_id' => $shipment->id,
            'company_id' => $shipment->company_id,
            'created_by' => Auth::id(),
            'booking_date' => $bookingData['booking_date'] ?? now()
        ]);

        $booking = Booking::create($bookingData);

        // Add booking tracking
        $booking->addTrackingEvent([
            'event_type' => 'status_change',
            'event_code' => 'BOOKING_CREATED',
            'event_description' => 'Booking created for shipment',
            'status' => 'Pending',
            'location' => 'Booking Office'
        ]);

        return $booking;
    }

    /**
     * Create containers for booking
     */
    public function createContainersForBooking(Booking $booking, array $containersData): array
    {
        $containers = [];

        foreach ($containersData as $containerData) {
            $containerData = array_merge($containerData, [
                'booking_id' => $booking->id,
                'shipment_id' => $booking->shipment_id,
                'status' => 'Available'
            ]);

            $container = Container::create($containerData);

            // Add container tracking
            $container->addTrackingEvent([
                'event_type' => 'status_change',
                'event_code' => 'CONTAINER_ASSIGNED',
                'event_description' => 'Container assigned to booking',
                'status' => 'Assigned',
                'container_number' => $container->container_number
            ]);

            $containers[] = $container;
        }

        return $containers;
    }

    /**
     * Confirm booking with vessel details
     */
    public function confirmBooking(Booking $booking, array $vesselData): Booking
    {
        $booking->update(array_merge($vesselData, [
            'is_confirmed' => true,
            'confirmed_at' => now(),
            'confirmed_by' => Auth::id(),
            'status' => 'Confirmed'
        ]));

        // Update shipment status
        $booking->shipment->updateStatusWithTracking('Confirmed', [
            'location' => 'Shipping Line Office',
            'event_description' => "Booking confirmed on vessel {$booking->vessel_name}",
            'vessel_name' => $booking->vessel_name,
            'voyage_number' => $booking->voyage_number
        ]);

        return $booking;
    }

    /**
     * Load containers with cargo
     */
    public function loadContainers(array $containerIds, array $cargoData): array
    {
        $loadedContainers = [];

        foreach ($containerIds as $containerId) {
            $container = Container::find($containerId);
            if (!$container) continue;

            $container->update([
                'loading_status' => 'Loaded',
                'stuffing_date' => now(),
                'cargo_manifest' => $cargoData[$containerId] ?? [],
                'gross_weight' => $cargoData[$containerId]['gross_weight'] ?? null,
                'net_weight' => $cargoData[$containerId]['net_weight'] ?? null,
                'seal_number' => $cargoData[$containerId]['seal_number'] ?? null
            ]);

            // Add tracking
            $container->addTrackingEvent([
                'event_type' => 'milestone',
                'event_code' => 'CONTAINER_LOADED',
                'event_description' => 'Container loading completed and sealed',
                'status' => 'Loaded',
                'container_number' => $container->container_number,
                'is_milestone' => true
            ]);

            $loadedContainers[] = $container;
        }

        return $loadedContainers;
    }

    /**
     * Generate comprehensive shipment invoice
     */
    public function generateShipmentInvoice(Shipment $shipment, array $serviceCharges = []): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber('sales'),
            'invoice_type' => 'sales',
            'company_id' => $shipment->company_id,
            'shipment_id' => $shipment->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'payment_terms' => '30 days',
            'currency' => 'USD',
            'exchange_rate' => 1.0000,
            'status' => 'Draft',
            'created_by' => Auth::id()
        ]);

        // Add standard freight charges
        $this->addInvoiceLineItems($invoice, $shipment, $serviceCharges);

        // Calculate totals
        $invoice->calculateTotals();

        return $invoice;
    }

    /**
     * Add line items to invoice
     */
    private function addInvoiceLineItems(Invoice $invoice, Shipment $shipment, array $serviceCharges): void
    {
        $lineNumber = 1;

        // Ocean freight charge
        $this->addInvoiceDetail($invoice, [
            'line_number' => $lineNumber++,
            'item_type' => 'service',
            'item_code' => 'FREIGHT',
            'description' => 'Ocean Freight Charges',
            'quantity' => $shipment->container_count ?? 1,
            'unit_price' => $serviceCharges['freight'] ?? 1200.00,
            'shipment_id' => $shipment->id
        ]);

        // Documentation fees
        $this->addInvoiceDetail($invoice, [
            'line_number' => $lineNumber++,
            'item_type' => 'service',
            'item_code' => 'DOC',
            'description' => 'Documentation Fees',
            'quantity' => 1,
            'unit_price' => $serviceCharges['documentation'] ?? 150.00,
            'shipment_id' => $shipment->id
        ]);

        // Add custom charges
        foreach ($serviceCharges['custom'] ?? [] as $charge) {
            $this->addInvoiceDetail($invoice, array_merge($charge, [
                'line_number' => $lineNumber++,
                'shipment_id' => $shipment->id
            ]));
        }
    }

    /**
     * Add invoice detail with calculations
     */
    private function addInvoiceDetail(Invoice $invoice, array $detailData): void
    {
        $detail = $invoice->details()->create(array_merge([
            'unit_of_measure' => 'per shipment',
            'tax_type' => 'VAT',
            'tax_percentage' => 14.00,
            'currency' => 'USD',
            'exchange_rate' => 1.0000
        ], $detailData));

        $detail->calculateTotals();
    }

    /**
     * Get comprehensive shipment analytics
     */
    public function getShipmentAnalytics(array $filters = []): array
    {
        $query = Shipment::with(['company', 'bookings', 'containers', 'invoices']);

        // Apply filters
        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $shipments = $query->get();

        return [
            'total_shipments' => $shipments->count(),
            'by_status' => $shipments->groupBy('status')->map->count(),
            'container_types' => $shipments->flatMap->containers->groupBy('container_type')->map->count(),
            'total_revenue' => $shipments->flatMap->invoices->sum('total_amount'),
            'average_containers_per_shipment' => $shipments->avg(fn($s) => $s->containers->count()),
            'top_routes' => $this->getTopRoutes($shipments),
            'performance_metrics' => $this->calculatePerformanceMetrics($shipments)
        ];
    }

    /**
     * Calculate performance metrics
     */
    private function calculatePerformanceMetrics($shipments): array
    {
        $delivered = $shipments->where('status', 'Delivered');

        return [
            'on_time_delivery_rate' => $delivered->count() > 0 ?
                ($delivered->filter(fn($s) => $s->actual_delivery_date <= $s->eta)->count() / $delivered->count()) * 100 : 0,
            'average_transit_days' => $delivered->avg(
                fn($s) =>
                $s->actual_delivery_date ? $s->created_at->diffInDays($s->actual_delivery_date) : null
            ),
            'cancellation_rate' => $shipments->count() > 0 ?
                ($shipments->where('status', 'Cancelled')->count() / $shipments->count()) * 100 : 0
        ];
    }

    /**
     * Get top routes by volume
     */
    private function getTopRoutes($shipments): array
    {
        return $shipments->filter(fn($s) => $s->route_id)
            ->groupBy('route_id')
            ->map(fn($group) => [
                'route' => $group->first()->route,
                'shipment_count' => $group->count(),
                'container_count' => $group->sum(fn($s) => $s->containers->count())
            ])
            ->sortByDesc('shipment_count')
            ->take(10)
            ->values()
            ->toArray();
    }
}
