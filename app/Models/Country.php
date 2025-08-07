<?php

namespace App\Models;

use App\Models\Logistics\Port;
use App\Models\Logistics\ShippingAgency;
use App\Models\Management\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'iso_code',
        'phone_code',
        'currency',
        'status'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get shipping agencies in this country
     */
    public function shippingAgencies()
    {
        return $this->hasMany(ShippingAgency::class);
    }

    /**
     * Get ports in this country
     */
    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    /**
     * Get companies in this country
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    // ===== ACCESSORS =====

    public function getDisplayNameAttribute()
    {
        return $this->code ? "{$this->name} ({$this->code})" : $this->name;
    }
}
