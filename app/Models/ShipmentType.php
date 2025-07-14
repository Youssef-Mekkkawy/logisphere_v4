<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ShipmentType extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'sub_type',
        'description',
        'category',
        'status'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
