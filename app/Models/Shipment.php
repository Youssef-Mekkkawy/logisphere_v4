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
    protected $fillable = [
        'shipment_id',
        'company_id',
        'origin_port_id',
        'destination_port_id',
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
    
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
