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
        'destination_id',
        'coo_type_id',
        'container_load_id',
        'bosla_gomrok_id',
        'destination_id',
        'coo_type_id',
        'inspection_type_id',
        'service_id',
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
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function cooType()
    {
        return $this->belongsTo(CooType::class);
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
