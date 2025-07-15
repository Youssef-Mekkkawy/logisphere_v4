<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasAttachments;
use App\Traits\HasTracking;

class Shipment extends Model
{
    use HasFactory;
    // use HasAttachments, HasTracking;
    // use HasFactory, HasAttachments, HasTracking;
    protected $fillable = [
        'shipment_id',
        'company_id',
        'destination_port_id',
        'route_id', // Add this new field
        'employee_id',
        'origin_port_id',
        'container_type',
        'shipping_date',
        'eta',
        'freight_cost',
        'cargo_description',
        'weight',
        'volume',
        'special_instructions',
        'status'
    ];

    protected $casts = [
        'shipping_date' => 'date',
        'eta' => 'date',
        'freight_cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'volume' => 'decimal:2'
    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['Delivered', 'Cancelled']);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }


    // Add these relationships to existing Shipment model:


    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get invoices for this shipment
     */


    /**
     * Get invoice details related to this shipment
     */
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    // ADD THESE NEW METHODS:

    /**
     * Get shipment status from status table
     */
    public function getStatusInfo()
    {
        return AllStatus::where('module', 'shipment')
            ->where('status_code', strtoupper(str_replace(' ', '_', $this->status)))
            ->first();
    }

    /**
     * Update status with tracking
     */
    public function updateStatusWithTracking(string $newStatus, array $trackingData = [])
    {
        $oldStatus = $this->status;

        // Update shipment status
        $this->update(['status' => $newStatus]);

        // Add tracking event
        $this->addTrackingEvent(array_merge([
            'event_type' => 'status_change',
            'event_code' => strtoupper(str_replace(' ', '_', $newStatus)),
            'event_description' => "Status changed from {$oldStatus} to {$newStatus}",
            'status' => $newStatus,
            'is_milestone' => true,
            'is_public' => true
        ], $trackingData));

        return $this;
    }

    /**
     * Get container count
     */
    public function getContainerCountAttribute()
    {
        return $this->containers()->count();
    }

    /**
     * Get total cargo weight
     */
    public function getTotalWeightAttribute()
    {
        return $this->containers()->sum('net_weight');
    }

    /**
     * Check if shipment has active booking
     */
    public function hasActiveBooking(): bool
    {
        return $this->bookings()->where('is_confirmed', true)->exists();
    }

    /**
     * Get estimated delivery date
     */
    public function getEstimatedDeliveryAttribute()
    {
        $booking = $this->bookings()->where('is_confirmed', true)->first();
        return $booking ? $booking->estimated_arrival : null;
    }
}
