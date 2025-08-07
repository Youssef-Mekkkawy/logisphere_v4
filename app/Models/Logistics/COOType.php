<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class COOType extends Model
{
    use HasFactory;

    protected $table = 'coo_types';

    protected $fillable = [
        'code',
        'name',
        'issuing_authority',
        'is_mandatory',
        'processing_days',
        'cost',
        'validity_months',
        'status',
        'description',
        'required_documents'
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'processing_days' => 'integer',
        'cost' => 'decimal:2',
        'validity_months' => 'integer'
    ];

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeByAuthority($query, $authority)
    {
        return $query->where('issuing_authority', $authority);
    }

    // ===== ACCESSORS =====

    public function getValidityDisplayAttribute()
    {
        if ($this->validity_months == 0) {
            return 'No Expiry';
        } elseif ($this->validity_months == 1) {
            return '1 Month';
        } elseif ($this->validity_months < 12) {
            return $this->validity_months . ' Months';
        } else {
            return ($this->validity_months / 12) . ' Year(s)';
        }
    }

    public function getFormattedCostAttribute()
    {
        return '$' . number_format($this->cost, 2);
    }

    // ===== RELATIONSHIPS =====

    /**
     * Get shipments that use this COO type
     * (Uncomment when you have the relationship set up)
     */
    // public function shipments()
    // {
    //     return $this->hasMany(Shipment::class, 'coo_type_id');
    // }

    /**
     * Get customs clearances that use this COO type
     * (Uncomment when you have the relationship set up)
     */
    // public function customsClearances()
    // {
    //     return $this->hasMany(CustomsClearance::class, 'coo_type_id');
    // }

    // ===== METHODS =====

    /**
     * Check if this COO type is being used
     */
    public function isInUse()
    {
        // Adjust based on your actual relationships
        return false;
        // return $this->shipments()->exists() || $this->customsClearances()->exists();
    }

    /**
     * Get usage count
     */
    public function getUsageCount()
    {
        // Adjust based on your actual relationships
        return 0;
        // return $this->shipments()->count() + $this->customsClearances()->count();
    }

    /**
     * Calculate estimated completion time based on processing days
     */
    public function getEstimatedCompletionDate($startDate = null)
    {
        $startDate = $startDate ?: now();
        return $startDate->addDays($this->processing_days);
    }

    /**
     * Check if COO is expired based on issue date
     */
    public function isExpired($issueDate)
    {
        if ($this->validity_months == 0) {
            return false; // No expiry
        }

        $expiryDate = $issueDate->addMonths($this->validity_months);
        return now()->gt($expiryDate);
    }
}
