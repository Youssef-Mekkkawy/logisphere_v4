<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAgency extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'contact_person',
        'email',
        'phone',
        'country',
        'address',
        'status'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
