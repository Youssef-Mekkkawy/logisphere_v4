<?php

namespace App\Models\Logistics;

use App\Models\Management\Shipment;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = [
        'container_number',
        'container_type',
        'booking_id',
        'shipment_id',
        'seal_number',
        'tare_weight',
        'gross_weight',
        'net_weight',
        'volume_used',
        'loading_status',
        'container_condition',
        'current_location',
        'current_port_id',
        'stuffing_date',
        'destuffing_date',
        'temperature_setting',
        'damage_description',
        'cargo_manifest',
        'status',
        'is_active',
    ];

    protected $casts = [
        'cargo_manifest' => 'array', // ← This is critical for JSON handling
        'stuffing_date' => 'date',
        'destuffing_date' => 'date',
        'gross_weight' => 'decimal:2',
        'net_weight' => 'decimal:2',
        'volume_used' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function currentPort()
    {
        return $this->belongsTo(Port::class, 'current_port_id');
    }
}
