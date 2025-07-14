<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Port extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'country',
        'city',
        'type',
        'status'
    ];

    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_port_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_port_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
