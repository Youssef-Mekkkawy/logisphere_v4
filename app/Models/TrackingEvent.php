<?php

// ======================================================================================
// TRACKING EVENT MODEL
// ======================================================================================

// File: app/Models/TrackingEvent.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'description',
        'event_date',
        'created_by'
    ];

    protected $casts = [
        'event_date' => 'datetime'
    ];

    /**
     * Get the shipment this tracking event belongs to
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the user who created this tracking event
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
