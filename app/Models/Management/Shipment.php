<?php

namespace App\Models\Management;

use App\Models\Logistics\Booking;
use App\Models\Logistics\BoslaGomrok;
use App\Models\Logistics\Container;
use App\Models\Logistics\ContainerLoading;
use App\Models\Logistics\COOType;
use App\Models\Logistics\CustomsClearance;
use App\Models\Logistics\Destination;
use App\Models\Logistics\InspectionType;
use App\Models\Logistics\Invoice;
use App\Models\Logistics\InvoiceDetail;
use App\Models\Logistics\Port;
use App\Models\Logistics\Service;
use App\Models\Logistics\ShipmentType;
use App\Models\Logistics\ShippingAgency;
use App\Models\Logistics\TrackingEvent;
use App\Models\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'shipment_id',
        'company_id',
        'employee_id',  // Add this
        'destination_port_id',
        'origin_port_id',
        'shipping_agency_id',  // Add this
        'shipment_type_id',    // Add this
        'route_id',
        'container_type',
        'container_number',    // Add this
        'reference_number',    // Add this
        'shipping_date',
        'etd',                // Add this
        'eta',
        'freight_cost',
        'cargo_description',
        'weight',
        'volume',
        'value',              // Add this
        'currency',           // Add this
        'consignee_name',     // Add this
        'consignee_address',  // Add this
        'notify_party',       // Add this
        'special_instructions',
        'bosla_gomrok_id',
        'destination_id',
        'coo_type_id',
        'inspection_type_id',
        'service_id',
        'container_load_id',
        'status'
    ];


    protected $casts = [
        'shipping_date' => 'date',
        'eta' => 'date',
        'etd' => 'date',
        'freight_cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'volume' => 'decimal:2',
        'value' => 'decimal:2'
    ];

    // ===== RELATIONSHIPS =====
    /**
     * Get the bosla gomrok for this shipment
     */
    public function loadingPoint()
    {
        return $this->belongsTo(ContainerLoading::class, 'loading_point_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function cooType()
    {
        return $this->belongsTo(COOType::class);
    }

    public function inspectionType()
    {
        return $this->belongsTo(InspectionType::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function boslaGomrok()
    {
        return $this->belongsTo(BoslaGomrok::class);
    }

    /**
     * Get customs clearances for this shipment
     */
    public function customsClearances()
    {
        return $this->hasMany(CustomsClearance::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }


    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    /**
     * Get all tracking events for this shipment
     */
    public function trackingEvents()
    {
        return $this->hasMany(TrackingEvent::class)->orderBy('event_date', 'desc')->orderBy('event_time', 'desc');
    }

    /**
     * Get public tracking events (for customer tracking)
     */
    public function publicTrackingEvents()
    {
        return $this->trackingEvents()->where('is_public', true);
    }

    /**
     * Get milestone tracking events
     */
    public function milestoneEvents()
    {
        return $this->trackingEvents()->where('is_milestone', true);
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['Delivered', 'Cancelled']);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ===== METHODS =====

    /**
     * Add a tracking event to this shipment
     */
    public function addTrackingEvent(array $data)
    {
        return $this->trackingEvents()->create(array_merge($data, [
            'event_date' => $data['event_date'] ?? now()->toDateString(),
            'event_time' => $data['event_time'] ?? now(),
            'created_by' => auth()->id()
        ]));
    }

    /**
     * Get the latest tracking event
     */
    public function latestTrackingEvent()
    {
        return $this->trackingEvents()->first();
    }

    /**
     * Get current location from latest tracking event
     */
    public function getCurrentLocation()
    {
        $latestEvent = $this->latestTrackingEvent();
        return $latestEvent ? $latestEvent->location : null;
    }

    /**
     * Check if shipment has tracking events
     */
    public function hasTrackingEvents()
    {
        return $this->trackingEvents()->exists();
    }

    /**
     * Generate unique shipment ID
     */
    public static function generateShipmentId()
    {
        $year = now()->year;
        $lastShipment = static::whereYear('created_at', $year)
            ->where('shipment_id', 'like', "SHP-{$year}-%")
            ->orderBy('shipment_id', 'desc')
            ->first();

        if ($lastShipment) {
            $lastNumber = (int) substr($lastShipment->shipment_id, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "SHP-{$year}-{$newNumber}";
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the shipping agency for this shipment
     */
    public function shippingAgency()
    {
        return $this->belongsTo(ShippingAgency::class);
    }

    /**
     * Get the shipment type for this shipment
     */
    public function shipmentType()
    {
        return $this->belongsTo(ShipmentType::class);
    }
}
