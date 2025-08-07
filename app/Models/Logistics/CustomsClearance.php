<?php

namespace App\Models\Logistics;

use App\Models\Management\Company;
use App\Models\Management\Shipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomsClearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'clearance_number',
        'shipment_id',
        'bosla_gomrok_id',
        'company_id',
        'clearance_type',
        'customs_office',
        'declaration_number',
        'declaration_date',
        'clearance_date',
        'status',
        'remarks',
        'customs_value',
        'currency',
        'duties_paid',
        'taxes_paid',
        'fees_paid',
        'required_documents',
        'submitted_documents',
        'special_instructions',
        'submitted_at',
        'processed_at',
        'completed_at'
    ];

    protected $casts = [
        'declaration_date' => 'date',
        'clearance_date' => 'date',
        'customs_value' => 'decimal:2',
        'duties_paid' => 'decimal:2',
        'taxes_paid' => 'decimal:2',
        'fees_paid' => 'decimal:2',
        'required_documents' => 'array',
        'submitted_documents' => 'array',
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the shipment for this customs clearance
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the bosla gomrok for this customs clearance
     */
    public function boslaGomrok()
    {
        return $this->belongsTo(BoslaGomrok::class);
    }

    /**
     * Get the company for this customs clearance
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // ===== SCOPES =====

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeInProcess($query)
    {
        return $query->where('status', 'In Process');
    }

    public function scopeCleared($query)
    {
        return $query->where('status', 'Cleared');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('clearance_type', $type);
    }

    // ===== ACCESSORS =====

    public function getFormattedCustomsValueAttribute()
    {
        return $this->currency . ' ' . number_format($this->customs_value, 2);
    }

    public function getTotalFeesAttribute()
    {
        return $this->duties_paid + $this->taxes_paid + $this->fees_paid;
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'Pending' => 'warning',
            'In Process' => 'info',
            'Documents Submitted' => 'primary',
            'Under Review' => 'secondary',
            'Cleared' => 'success',
            'On Hold' => 'warning',
            'Rejected' => 'danger',
            default => 'secondary'
        };
    }

    // ===== METHODS =====

    /**
     * Generate unique clearance number
     */
    public static function generateClearanceNumber($type = 'Import')
    {
        $prefix = match ($type) {
            'Import' => 'IMP',
            'Export' => 'EXP',
            'Transit' => 'TRA',
            'Temporary' => 'TMP',
            default => 'CLR'
        };

        $year = now()->year;
        $month = now()->format('m');

        // Get the last clearance number for this type and month
        $lastClearance = static::where('clearance_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->orderBy('clearance_number', 'desc')
            ->first();

        if ($lastClearance) {
            $lastNumber = (int) substr($lastClearance->clearance_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$year}{$month}-{$newNumber}";
    }

    /**
     * Check if clearance is complete
     */
    public function isComplete()
    {
        return $this->status === 'Cleared' && $this->completed_at !== null;
    }

    /**
     * Mark clearance as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'Cleared',
            'completed_at' => now(),
            'clearance_date' => now()->toDateString()
        ]);
    }

    /**
     * Calculate processing time in days
     */
    public function getProcessingTimeInDays()
    {
        if (!$this->submitted_at || !$this->completed_at) {
            return null;
        }

        return $this->submitted_at->diffInDays($this->completed_at);
    }
}
