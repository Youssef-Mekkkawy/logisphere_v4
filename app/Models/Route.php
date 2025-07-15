<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'route_code',
        'route_name',
        'origin_port_id',
        'destination_port_id',
        'service_type',
        'transport_mode',
        'transit_days',
        'frequency_days',
        'distance_km',
        'distance_nm',
        'is_direct',
        'intermediate_ports',
        'route_schedule',
        'carrier_preference',
        'base_rate',
        'rate_currency',
        'rate_unit',
        'route_notes',
        'status',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'intermediate_ports' => 'array',  // ← Critical for JSON handling
        'route_schedule' => 'array',      // ← Also JSON field
        'distance_km' => 'decimal:2',
        'distance_nm' => 'decimal:2',
        'base_rate' => 'decimal:2',
        'is_direct' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    // Relationships
    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}