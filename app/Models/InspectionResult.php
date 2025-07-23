<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_type_id',
        'shipment_id',
        'inspector_name',
        'inspector_authority',
        'inspection_date',
        'completion_date',
        'status',
        'passed',
        'certificate_number',
        'findings',
        'recommendations',
        'failure_reason',
        'actual_cost',
        'actual_duration',
        'expiry_date',
        'documents_submitted',
        'notes'
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
        'completion_date' => 'datetime',
        'expiry_date' => 'datetime',
        'passed' => 'boolean',
        'actual_cost' => 'decimal:2',
        'actual_duration' => 'decimal:2',
        'documents_submitted' => 'array'
    ];

    // Relationships
    public function inspectionType()
    {
        return $this->belongsTo(InspectionType::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Scopes
    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }

    public function scopePending($query)
    {
        return $query->whereNull('passed');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringWithin($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Pending' => 'status-warning',
            'In Progress' => 'status-info',
            'Completed' => $this->passed ? 'status-success' : 'status-error',
            'Failed' => 'status-error',
            'Cancelled' => 'status-secondary'
        ];

        return $badges[$this->status] ?? 'status-secondary';
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && now()->isAfter($this->expiry_date);
    }

    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) return null;

        return now()->diffInDays($this->expiry_date, false);
    }

    // Methods
    public function markAsCompleted($passed = true, $findings = null, $certificateNumber = null)
    {
        $this->update([
            'status' => 'Completed',
            'completion_date' => now(),
            'passed' => $passed,
            'findings' => $findings,
            'certificate_number' => $certificateNumber,
            'expiry_date' => $this->inspectionType->validity_period
                ? now()->addDays($this->inspectionType->validity_period)
                : null
        ]);

        return $this;
    }

    public function markAsFailed($failureReason, $recommendations = null)
    {
        $this->update([
            'status' => 'Failed',
            'completion_date' => now(),
            'passed' => false,
            'failure_reason' => $failureReason,
            'recommendations' => $recommendations
        ]);

        return $this;
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'Cancelled',
            'notes' => $reason ? "Cancelled: {$reason}" : 'Cancelled'
        ]);

        return $this;
    }
}
