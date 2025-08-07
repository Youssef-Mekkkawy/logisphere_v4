<?php

// ======================================================================================
// PHASE 4: BUSINESS LOGIC SERVICES - LOGISTICS MANAGEMENT SYSTEM
// ======================================================================================

namespace App\Services;

use App\Models\Management\Shipment;
use App\Models\Logistics\TrackingEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Shipment Service - Core Business Logic for Shipment Management
 */
class ShipmentService
{
    /**
     * Create a new shipment with auto-generated ID and tracking
     */
    public function createShipment(array $data): Shipment
    {
        $data['shipment_id'] = $this->generateShipmentId();
        $data['status'] = 'Pending';
        $data['created_by'] = Auth::id();

        $shipment = Shipment::create($data);

        // Create initial tracking event
        $this->createTrackingEvent($shipment, [
            'status' => 'Pending',
            'location' => 'Origin',
            'description' => 'Shipment created and pending processing',
            'event_date' => now()
        ]);

        return $shipment;
    }

    /**
     * Update shipment status with tracking
     */
    public function updateStatus(Shipment $shipment, array $data): void
    {
        $oldStatus = $shipment->status;

        $shipment->update([
            'status' => $data['status'],
            'updated_by' => Auth::id()
        ]);

        // Create tracking event
        $this->createTrackingEvent($shipment, [
            'status' => $data['status'],
            'location' => $data['location'] ?? null,
            'description' => $data['notes'] ?? "Status changed from {$oldStatus} to {$data['status']}",
            'event_date' => now()
        ]);

        // Send notifications if needed
        $this->handleStatusChangeNotifications($shipment, $oldStatus, $data['status']);
    }

    /**
     * Generate unique shipment ID
     */
    private function generateShipmentId(): string
    {
        $prefix = 'LGF';
        $year = date('Y');
        $month = date('m');

        // Get last shipment of current month
        $lastShipment = Shipment::where('shipment_id', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('shipment_id', 'desc')
            ->first();

        if ($lastShipment) {
            $lastNumber = intval(substr($lastShipment->shipment_id, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create tracking event
     */
    private function createTrackingEvent(Shipment $shipment, array $eventData): TrackingEvent
    {
        return TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'status' => $eventData['status'],
            'location' => $eventData['location'],
            'description' => $eventData['description'],
            'event_date' => $eventData['event_date'],
            'created_by' => Auth::id()
        ]);
    }

    /**
     * Handle status change notifications
     */
    private function handleStatusChangeNotifications(Shipment $shipment, string $oldStatus, string $newStatus): void
    {
        // Key status changes that require notifications
        $notifyStatuses = ['In Transit', 'At Port', 'Delivered', 'Cancelled'];

        if (in_array($newStatus, $notifyStatuses)) {
            // Queue notification job
            Log::info("Status change notification queued", [
                'shipment_id' => $shipment->shipment_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'company' => $shipment->company->name
            ]);

            // Here you would dispatch a job to send emails/SMS
            // dispatch(new SendStatusNotification($shipment, $oldStatus, $newStatus));
        }
    }

    /**
     * Generate shipment documents
     */
    public function generateDocument(Shipment $shipment, string $documentType): mixed
    {
        switch ($documentType) {
            case 'bill_of_lading':
                return $this->generateBillOfLading($shipment);
            case 'commercial_invoice':
                return $this->generateCommercialInvoice($shipment);
            case 'packing_list':
                return $this->generatePackingList($shipment);
            default:
                throw new \InvalidArgumentException("Unknown document type: {$documentType}");
        }
    }

    /**
     * Generate Bill of Lading PDF
     */
    private function generateBillOfLading(Shipment $shipment)
    {
        $data = [
            'shipment' => $shipment->load(['company', 'originPort', 'destinationPort', 'shippingAgency']),
            'document_title' => 'Bill of Lading',
            'document_number' => 'BOL-' . $shipment->shipment_id,
            'issue_date' => now()->format('Y-m-d'),
            'company_info' => $this->getCompanyInfo()
        ];

        // Using a PDF service (you'd inject this)
        return app(PDFService::class)->generatePDF('documents.bill_of_lading', $data);
    }

    /**
     * Get company information for documents
     */
    private function getCompanyInfo(): array
    {
        return [
            'name' => config('app.name', 'logisphere Logistics'),
            'address' => config('company.address', 'Your Company Address'),
            'phone' => config('company.phone', '+20-xxx-xxx-xxxx'),
            'email' => config('company.email', 'info@logisphere.com')
        ];
    }

    /**
     * Calculate shipment metrics for dashboard
     */
    public function getShipmentMetrics(): array
    {
        return [
            'total_shipments' => Shipment::count(),
            'pending_shipments' => Shipment::where('status', 'Pending')->count(),
            'in_transit_shipments' => Shipment::where('status', 'In Transit')->count(),
            'delivered_shipments' => Shipment::where('status', 'Delivered')->count(),
            'cancelled_shipments' => Shipment::where('status', 'Cancelled')->count(),
            'avg_delivery_time' => $this->calculateAverageDeliveryTime(),
            'on_time_delivery_rate' => $this->calculateOnTimeDeliveryRate()
        ];
    }

    /**
     * Calculate average delivery time in days
     */
    private function calculateAverageDeliveryTime(): float
    {
        $deliveredShipments = Shipment::where('status', 'Delivered')
            ->whereNotNull('eta')
            ->whereNotNull('created_at')
            ->get();

        if ($deliveredShipments->isEmpty()) {
            return 0;
        }

        $totalDays = $deliveredShipments->sum(function ($shipment) {
            return $shipment->created_at->diffInDays($shipment->eta);
        });

        return round($totalDays / $deliveredShipments->count(), 1);
    }

    /**
     * Calculate on-time delivery rate
     */
    private function calculateOnTimeDeliveryRate(): float
    {
        $deliveredShipments = Shipment::where('status', 'Delivered')->count();

        if ($deliveredShipments === 0) {
            return 0;
        }

        $onTimeShipments = Shipment::where('status', 'Delivered')
            ->whereColumn('actual_delivery_date', '<=', 'eta')
            ->count();

        return round(($onTimeShipments / $deliveredShipments) * 100, 1);
    }
}
