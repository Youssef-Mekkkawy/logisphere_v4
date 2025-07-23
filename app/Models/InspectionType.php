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
        'required_documents',
        'estimated_duration',
        'cost_estimate',
        'regulatory_authority',
        'mandatory',
        'applies_to',
        'prerequisites',
        'validity_period',
        'renewal_required',
        'compliance_standards',
        'status'
    ];

    protected $casts = [
        'required_documents' => 'array',
        'applies_to' => 'array',
        'mandatory' => 'boolean',
        'renewal_required' => 'boolean',
        'estimated_duration' => 'decimal:2',
        'cost_estimate' => 'decimal:2',
        'validity_period' => 'integer'
    ];

    // Relationships
    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'shipment_inspections')
            ->withPivot(['inspection_date', 'completion_date', 'status', 'certificate_number', 'notes'])
            ->withTimestamps();
    }

    public function inspectionResults()
    {
        return $this->hasMany(InspectionResult::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('inspection_category', $category);
    }

    public function scopeMandatory($query)
    {
        return $query->where('mandatory', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('mandatory', false);
    }

    public function scopeAppliesTo($query, $shipmentType)
    {
        return $query->whereJsonContains('applies_to', $shipmentType);
    }

    // Accessors
    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'Customs' => '🛃 Customs Clearance',
            'Quality' => '✅ Quality Control',
            'Safety' => '🛡️ Safety Inspection',
            'Environmental' => '🌿 Environmental',
            'Security' => '🔒 Security Check',
            'Health' => '🏥 Health & Sanitary',
            'Technical' => '🔧 Technical Inspection',
            'Documentation' => '📋 Documentation Review',
            'Physical' => '📦 Physical Examination',
            'Laboratory' => '🧪 Laboratory Testing'
        ];

        return $categories[$this->inspection_category] ?? $this->inspection_category;
    }

    public function getDocumentsListAttribute()
    {
        if (!$this->required_documents || !is_array($this->required_documents)) {
            return 'Standard documentation';
        }

        return implode(', ', $this->required_documents);
    }

    public function getAppliesToListAttribute()
    {
        if (!$this->applies_to || !is_array($this->applies_to)) {
            return 'All shipment types';
        }

        return implode(', ', $this->applies_to);
    }

    public function getDurationDisplayAttribute()
    {
        if (!$this->estimated_duration) {
            return 'Not specified';
        }

        $hours = $this->estimated_duration;

        if ($hours < 1) {
            return ($hours * 60) . ' minutes';
        } elseif ($hours < 24) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        } else {
            $days = round($hours / 24, 1);
            return $days . ' day' . ($days > 1 ? 's' : '');
        }
    }

    public function getCostDisplayAttribute()
    {
        if (!$this->cost_estimate) {
            return 'Contact for pricing';
        }

        return '$' . number_format($this->cost_estimate, 2) . ' USD';
    }

    public function getMandatoryDisplayAttribute()
    {
        return $this->mandatory ? 'Mandatory' : 'Optional';
    }

    public function getValidityDisplayAttribute()
    {
        if (!$this->validity_period) {
            return 'No expiration';
        }

        $days = $this->validity_period;

        if ($days < 30) {
            return $days . ' days';
        } elseif ($days < 365) {
            $months = round($days / 30);
            return $months . ' month' . ($months > 1 ? 's' : '');
        } else {
            $years = round($days / 365, 1);
            return $years . ' year' . ($years > 1 ? 's' : '');
        }
    }

    // Methods
    public function getStatistics()
    {
        return [
            'total_inspections' => $this->shipments()->count(),
            'pending_inspections' => $this->shipments()->wherePivot('status', 'Pending')->count(),
            'completed_inspections' => $this->shipments()->wherePivot('status', 'Completed')->count(),
            'failed_inspections' => $this->shipments()->wherePivot('status', 'Failed')->count(),
            'monthly_volume' => $this->shipments()->whereMonth('shipment_inspections.created_at', now()->month)->count(),
            'average_duration' => $this->getAverageDuration()
        ];
    }

    public function getAverageDuration()
    {
        $completedInspections = $this->shipments()
            ->wherePivot('status', 'Completed')
            ->whereNotNull('shipment_inspections.inspection_date')
            ->whereNotNull('shipment_inspections.completion_date')
            ->get();

        if ($completedInspections->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($completedInspections as $shipment) {
            $start = $shipment->pivot->inspection_date;
            $end = $shipment->pivot->completion_date;
            if ($start && $end) {
                $totalHours += \Carbon\Carbon::parse($start)->diffInHours(\Carbon\Carbon::parse($end));
            }
        }

        return round($totalHours / $completedInspections->count(), 2);
    }

    public function hasDocument($document)
    {
        if (!$this->required_documents) return false;
        return in_array($document, $this->required_documents);
    }

    public function appliesTo($shipmentType)
    {
        if (!$this->applies_to) return true; // If no restrictions, applies to all
        return in_array($shipmentType, $this->applies_to);
    }

    public function isExpired($completionDate)
    {
        if (!$this->validity_period || !$completionDate) {
            return false;
        }

        $expiryDate = \Carbon\Carbon::parse($completionDate)->addDays($this->validity_period);
        return now()->isAfter($expiryDate);
    }

    public function getExpiryDate($completionDate)
    {
        if (!$this->validity_period || !$completionDate) {
            return null;
        }

        return \Carbon\Carbon::parse($completionDate)->addDays($this->validity_period);
    }

    public function calculateCost($shipmentValue = null, $customMultiplier = 1)
    {
        $baseCost = $this->cost_estimate ?? 0;

        // Apply custom multiplier (for complex or rush inspections)
        $cost = $baseCost * $customMultiplier;

        // Some inspection types might have percentage-based pricing
        if ($shipmentValue && $this->inspection_category === 'Customs') {
            $percentageCost = $shipmentValue * 0.001; // 0.1% of shipment value
            $cost = max($cost, $percentageCost);
        }

        return round($cost, 2);
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'inspection_code' => 'required|string|max:20|unique:inspection_types,inspection_code,' . $id,
            'inspection_name' => 'required|string|max:255',
            'inspection_category' => 'required|string|in:Customs,Quality,Safety,Environmental,Security,Health,Technical,Documentation,Physical,Laboratory',
            'description' => 'nullable|string',
            'required_documents' => 'nullable|array',
            'estimated_duration' => 'nullable|numeric|min:0|max:999.99',
            'cost_estimate' => 'nullable|numeric|min:0|max:999999.99',
            'regulatory_authority' => 'nullable|string|max:255',
            'mandatory' => 'required|boolean',
            'applies_to' => 'nullable|array',
            'prerequisites' => 'nullable|string',
            'validity_period' => 'nullable|integer|min:1|max:9999',
            'renewal_required' => 'required|boolean',
            'compliance_standards' => 'nullable|string',
            'status' => 'required|string|in:Active,Inactive'
        ];
    }

    public static function getCategories()
    {
        return [
            'Customs' => 'Customs Clearance',
            'Quality' => 'Quality Control',
            'Safety' => 'Safety Inspection',
            'Environmental' => 'Environmental',
            'Security' => 'Security Check',
            'Health' => 'Health & Sanitary',
            'Technical' => 'Technical Inspection',
            'Documentation' => 'Documentation Review',
            'Physical' => 'Physical Examination',
            'Laboratory' => 'Laboratory Testing'
        ];
    }

    public static function getDocumentTypes()
    {
        return [
            'Bill of Lading',
            'Commercial Invoice',
            'Packing List',
            'Certificate of Origin',
            'Import License',
            'Health Certificate',
            'Quality Certificate',
            'Safety Data Sheet',
            'Insurance Policy',
            'Customs Declaration',
            'Technical Specifications',
            'Test Reports',
            'Compliance Certificate',
            'Environmental Permit'
        ];
    }

    public static function getShipmentTypes()
    {
        return [
            'FCL' => 'Full Container Load',
            'LCL' => 'Less Container Load',
            'Break Bulk' => 'Break Bulk',
            'Dangerous Goods' => 'Dangerous Goods',
            'Perishable' => 'Perishable Goods',
            'Live Animals' => 'Live Animals',
            'High Value' => 'High Value Cargo',
            'Project Cargo' => 'Project Cargo',
            'Pharmaceuticals' => 'Pharmaceuticals',
            'Food Products' => 'Food Products'
        ];
    }
}
