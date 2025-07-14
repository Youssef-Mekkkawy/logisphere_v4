<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachments;
class Company extends Model
{
    //
    use HasFactory;
    // use HasAttachments;
    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'email',
        'phone',
        'country',
        'address',
        'service_type',
        'status'
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function scopeClients($query)
    {
        return $query->where('type', 'Client');
    }

    public function scopeSuppliers($query)
    {
        return $query->where('type', 'Supplier');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
