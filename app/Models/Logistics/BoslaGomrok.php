<?php

namespace App\Models\Logistics;

use App\Models\Management\Shipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoslaGomrok extends Model
{
    use HasFactory;

    protected $table = 'bosla_gomrok';

    protected $fillable = [
        'code',
        'name',
        'document_type',
        'customs_office',
        'processing_hours',
        'cost',
        'description',
        'required_documents',
        'validity_days',
        'is_mandatory',
        'status'
    ];

    protected $casts = [
        'processing_hours' => 'integer',
        'cost' => 'decimal:2',
        'validity_days' => 'integer',
        'is_mandatory' => 'boolean'
    ];

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByDocumentType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeByCustomsOffice($query, $office)
    {
        return $query->where('customs_office', $office);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('customs_office', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // ===== RELATIONSHIPS =====

    /**
     * Get shipments that use this bosla gomrok
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'bosla_gomrok_id');
    }

    /**
     * Get customs clearances that use this bosla gomrok
     */
    public function customsClearances()
    {
        return $this->hasMany(CustomsClearance::class, 'bosla_gomrok_id');
    }

    // ===== ACCESSORS =====

    public function getFormattedCostAttribute()
    {
        return '$' . number_format($this->cost, 2);
    }

    public function getProcessingTimeDisplayAttribute()
    {
        if ($this->processing_hours < 24) {
            return $this->processing_hours . ' hours';
        } else {
            $days = floor($this->processing_hours / 24);
            $hours = $this->processing_hours % 24;
            return $days . ' days' . ($hours > 0 ? ' ' . $hours . ' hours' : '');
        }
    }

    public function getValidityDisplayAttribute()
    {
        if (!$this->validity_days) {
            return 'No expiry';
        } elseif ($this->validity_days == 1) {
            return '1 day';
        } elseif ($this->validity_days < 30) {
            return $this->validity_days . ' days';
        } elseif ($this->validity_days < 365) {
            return round($this->validity_days / 30) . ' months';
        } else {
            return round($this->validity_days / 365) . ' years';
        }
    }

    public function getDocumentTypeColorAttribute()
    {
        return match ($this->document_type) {
            'Export' => 'success',
            'Import' => 'primary',
            'Transit' => 'warning',
            'Temporary' => 'info',
            default => 'secondary'
        };
    }

    // ===== METHODS =====

    /**
     * Check if this bosla gomrok is being used
     */
    public function isInUse()
    {
        return $this->shipments()->exists() || $this->customsClearances()->exists();
    }

    /**
     * Get usage count
     */
    public function getUsageCount()
    {
        return $this->shipments()->count() + $this->customsClearances()->count();
    }

    /**
     * Get usage this month
     */
    public function getThisMonthUsageCount()
    {
        return $this->shipments()->whereMonth('created_at', now()->month)->count() +
            $this->customsClearances()->whereMonth('created_at', now()->month)->count();
    }

    /**
     * Calculate total processing cost including base cost
     */
    public function calculateTotalCost($additionalFees = 0)
    {
        return $this->cost + $additionalFees;
    }

    /**
     * Check if document is valid based on creation date
     */
    public function isValidDocument($createdDate = null)
    {
        if (!$this->validity_days) {
            return true; // No expiry
        }

        $createdDate = $createdDate ?: now();
        $expiryDate = $createdDate->addDays($this->validity_days);

        return now()->lte($expiryDate);
    }

    /**
     * Get estimated completion datetime
     */
    public function getEstimatedCompletion($startTime = null)
    {
        $startTime = $startTime ?: now();
        return $startTime->addHours($this->processing_hours);
    }

    /**
     * Get document requirements as array
     */
    public function getRequiredDocumentsArray()
    {
        if (!$this->required_documents) {
            return [];
        }

        return array_map('trim', explode(',', $this->required_documents));
    }

    /**
     * Get bosla gomrok statistics
     */
    public function getStatistics()
    {
        return [
            'total_usage' => $this->getUsageCount(),
            'this_month_usage' => $this->getThisMonthUsageCount(),
            'shipments_count' => $this->shipments()->count(),
            'clearances_count' => $this->customsClearances()->count(),
            'last_used' => $this->getLastUsedDate(),
            'average_processing_time' => $this->processing_hours,
            'total_revenue' => $this->cost * $this->getUsageCount(),
        ];
    }

    /**
     * Get last used date
     */
    public function getLastUsedDate()
    {
        $lastShipment = $this->shipments()->latest()->first();
        $lastClearance = $this->customsClearances()->latest()->first();

        if (!$lastShipment && !$lastClearance) {
            return null;
        }

        if (!$lastShipment) {
            return $lastClearance->created_at;
        }

        if (!$lastClearance) {
            return $lastShipment->created_at;
        }

        return $lastShipment->created_at->gt($lastClearance->created_at)
            ? $lastShipment->created_at
            : $lastClearance->created_at;
    }
}
