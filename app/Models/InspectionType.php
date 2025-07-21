<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class InspectionType extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'inspection_code',
        'inspection_name',
        'inspection_category',
        'description',
        'inspection_authority',
        'certificate_type',
        'inspection_fee',
        'inspection_duration_hours',
        'requires_advance_notice',
        'notice_period_hours',
        'required_documents',
        'inspection_criteria',
        'special_requirements',
        'is_mandatory',
        'applicable_cargo_types',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'required_documents' => 'array',
        'inspection_criteria' => 'array',
        'requires_advance_notice' => 'boolean',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'inspection_fee' => 'decimal:2'
    ];

    // Relationships
    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'shipment_inspections')
            ->withPivot('inspection_status', 'inspection_date', 'inspector_name', 'certificate_number', 'notes')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('inspection_category', $category);
    }

    public function scopeByAuthority($query, $authority)
    {
        return $query->where('inspection_authority', $authority);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('inspection_name');
    }

    // Accessors
    public function getStatusAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'Pre-shipment' => '📋 Pre-shipment',
            'Quality' => '✅ Quality Control',
            'Quantity' => '📊 Quantity Verification',
            'Loading' => '📦 Loading Inspection',
            'Security' => '🔒 Security Check',
            'Customs' => '🛃 Customs Inspection',
            'Safety' => '⚠️ Safety Inspection',
            'Environmental' => '🌱 Environmental Check'
        ];

        return $categories[$this->inspection_category] ?? $this->inspection_category;
    }

    public function getDurationDisplayAttribute()
    {
        if ($this->inspection_duration_hours < 1) {
            return ($this->inspection_duration_hours * 60) . ' minutes';
        } elseif ($this->inspection_duration_hours == 1) {
            return '1 hour';
        } else {
            return $this->inspection_duration_hours . ' hours';
        }
    }

    public function getNoticeDisplayAttribute()
    {
        if (!$this->requires_advance_notice) {
            return 'No advance notice required';
        }

        if ($this->notice_period_hours < 24) {
            return $this->notice_period_hours . ' hours notice required';
        } else {
            $days = $this->notice_period_hours / 24;
            return $days . ' day' . ($days > 1 ? 's' : '') . ' notice required';
        }
    }

    public function getRequiredDocumentsListAttribute()
    {
        if (!$this->required_documents || !is_array($this->required_documents)) {
            return 'No specific documents required';
        }

        return implode(', ', $this->required_documents);
    }

    public function getInspectionCriteriaListAttribute()
    {
        if (!$this->inspection_criteria || !is_array($this->inspection_criteria)) {
            return 'Standard inspection criteria';
        }

        return implode(', ', $this->inspection_criteria);
    }

    public function getFeeDisplayAttribute()
    {
        if (!$this->inspection_fee || $this->inspection_fee == 0) {
            return 'No fee';
        }

        return '$' . number_format($this->inspection_fee, 2);
    }

    // Methods
    public function getStatistics()
    {
        return [
            'total_inspections' => $this->shipments()->count(),
            'pending_inspections' => $this->shipments()->wherePivot('inspection_status', 'Pending')->count(),
            'completed_inspections' => $this->shipments()->wherePivot('inspection_status', 'Completed')->count(),
            'failed_inspections' => $this->shipments()->wherePivot('inspection_status', 'Failed')->count(),
            'monthly_inspections' => $this->shipments()->whereMonth('shipment_inspections.created_at', now()->month)->count()
        ];
    }

    public function isApplicableToCargoType($cargoType)
    {
        if (!$this->applicable_cargo_types) return true;

        $applicableTypes = explode(',', $this->applicable_cargo_types);
        return in_array($cargoType, array_map('trim', $applicableTypes));
    }

    public function canBeScheduled($requestedDate = null)
    {
        if (!$this->is_active) return false;

        if ($this->requires_advance_notice && $requestedDate) {
            $minimumTime = now()->addHours($this->notice_period_hours);
            return $requestedDate >= $minimumTime;
        }

        return true;
    }

    public function hasDocument($document)
    {
        if (!$this->required_documents) return false;
        return in_array($document, $this->required_documents);
    }

    public function hasCriteria($criteria)
    {
        if (!$this->inspection_criteria) return false;
        return in_array($criteria, $this->inspection_criteria);
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'inspection_code' => 'required|string|max:20|unique:inspection_types,inspection_code,' . $id,
            'inspection_name' => 'required|string|max:255',
            'inspection_category' => 'required|string|in:Pre-shipment,Quality,Quantity,Loading,Security,Customs,Safety,Environmental',
            'description' => 'nullable|string',
            'inspection_authority' => 'nullable|string|max:255',
            'certificate_type' => 'nullable|string|max:255',
            'inspection_fee' => 'nullable|numeric|min:0',
            'inspection_duration_hours' => 'required|numeric|min:0.5|max:168',
            'requires_advance_notice' => 'required|boolean',
            'notice_period_hours' => 'nullable|integer|min:1|max:720',
            'required_documents' => 'nullable|array',
            'inspection_criteria' => 'nullable|array',
            'special_requirements' => 'nullable|string',
            'is_mandatory' => 'required|boolean',
            'applicable_cargo_types' => 'nullable|string',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0'
        ];
    }
}
