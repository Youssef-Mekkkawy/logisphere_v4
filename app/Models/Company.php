<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // ✅ COMPLETE fillable array - this is usually the issue!
    protected $fillable = [
        'name',
        'company_code',
        'type',
        'contact_person',
        'email',
        'phone',
        'tax_number',
        'country',
        'city',
        'postal_code',
        'website',
        'address',
        'service_type',
        'credit_limit',
        'payment_terms',
        'notes',
        'status',
        'created_by'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'payment_terms' => 'integer'
    ];

    // Rest of your model code...
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
