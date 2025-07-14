<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    use HasFactory;

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
}
