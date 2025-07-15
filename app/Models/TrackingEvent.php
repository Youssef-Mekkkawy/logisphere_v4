<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'description',
        'event_date',
        'event_time',
        'is_milestone',
        'is_public',
        'vessel_name',
        'container_number',
        'port_id',
        'created_by'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'is_milestone' => 'boolean',
        'is_public' => 'boolean'
    ];

    /**
     * Get the shipment this tracking event belongs to
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the port for this tracking event
     */
    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    /**
     * Get the user who created this tracking event
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for public tracking events
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for milestone events
     */
    public function scopeMilestones($query)
    {
        return $query->where('is_milestone', true);
    }
}
