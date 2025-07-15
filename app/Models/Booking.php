<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'booking_reference',
        'shipment_id',
        'company_id',
        'shipping_agency_id',
        'vessel_name',
        'voyage_number',
        'booking_date',
        'estimated_departure',
        'estimated_arrival',
        'cut_off_date',
        'service_type',
        'container_count',
        'container_type',
        'cargo_weight',
        'cargo_volume',
        'commodity_description',
        'incoterms',
        'status',
        'special_instructions',
        'is_confirmed',
        'confirmed_at',
        'confirmed_by',
        'created_by',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'estimated_departure' => 'date',
        'estimated_arrival' => 'date',
        'cut_off_date' => 'date',
        'confirmed_at' => 'datetime',
        'cargo_weight' => 'decimal:2',
        'cargo_volume' => 'decimal:2',
        'container_count' => 'integer',
        'is_confirmed' => 'boolean',
    ];

    /**
     * Generate a unique booking number
     */
    public static function generateBookingNumber()
    {
        $year = now()->year;
        $lastBooking = static::whereYear('created_at', $year)
            ->where('booking_number', 'like', "BKG-{$year}-%")
            ->orderBy('booking_number', 'desc')
            ->first();

        if ($lastBooking) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastBooking->booking_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // First booking of the year
            $newNumber = '001';
        }

        return "BKG-{$year}-{$newNumber}";
    }

    // Relationships
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function shippingAgency()
    {
        return $this->belongsTo(ShippingAgency::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function containers()
    {
        return $this->hasMany(Container::class);
    }
}