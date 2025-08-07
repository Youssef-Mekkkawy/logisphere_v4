<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Service extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'service_code',
        'service_name',
        'service_category',
        'description',
        'detailed_description',
        'billing_type',
        'base_rate',
        'rate_currency',
        'rate_unit',
        'minimum_charge',
        'maximum_charge',
        'rate_tiers',
        'account_id',
        'tax_type',
        'tax_percentage',
        'is_mandatory',
        'is_billable',
        'requires_approval',
        'required_documents',
        'estimated_duration_hours',
        'service_conditions',
        'applicable_cargo_types',
        'service_provider',
        'status',
        'effective_from',
        'effective_to',
        'notes'
    ];

    protected $casts = [
        'rate_tiers' => 'array',
        'required_documents' => 'array',
        'applicable_cargo_types' => 'array',
        'is_mandatory' => 'boolean',
        'is_billable' => 'boolean',
        'requires_approval' => 'boolean',
        'base_rate' => 'decimal:2',
        'minimum_charge' => 'decimal:2',
        'maximum_charge' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date'
    ];

    // Relationships
    // TODO: Uncomment when Account model exists
    // public function account()
    // {
    //     return $this->belongsTo(Account::class);
    // }

    // TODO: Add these relationships when ShipmentService and ServiceQuote models are created
    // public function shipmentServices()
    // {
    //     return $this->hasMany(ShipmentService::class);
    // }

    // public function serviceQuotes()
    // {
    //     return $this->hasMany(ServiceQuote::class);
    // }

    // Many-to-many with ports for location-specific services
    public function ports()
    {
        return $this->belongsToMany(Port::class, 'port_services')
            ->withPivot(['local_rate', 'availability_status', 'lead_time_hours'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('service_category', $category);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeByProvider($query, $provider)
    {
        return $query->where('service_provider', $provider);
    }

    public function scopeEffective($query, $date = null)
    {
        $date = $date ?: now();
        return $query->where(function ($q) use ($date) {
            $q->whereNull('effective_from')
                ->orWhere('effective_from', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('effective_to')
                ->orWhere('effective_to', '>=', $date);
        });
    }

    public function scopeForCargoType($query, $cargoType)
    {
        return $query->where(function ($q) use ($cargoType) {
            $q->whereNull('applicable_cargo_types')
                ->orWhereJsonContains('applicable_cargo_types', $cargoType);
        });
    }

    // Accessors
    public function getServiceCategoryDisplayAttribute()
    {
        $categories = [
            'Customs Clearance' => '🛃 Customs Clearance',
            'Transportation' => '🚛 Transportation',
            'Warehousing' => '🏭 Warehousing',
            'Documentation' => '📄 Documentation',
            'Insurance' => '🛡️ Insurance',
            'Inspection' => '🔍 Inspection',
            'Cargo Handling' => '📦 Cargo Handling',
            'Port Services' => '⚓ Port Services',
            'Freight Forwarding' => '🚢 Freight Forwarding',
            'Consulting' => '💼 Consulting',
            'Other' => '🔧 Other Services'
        ];

        return $categories[$this->service_category] ?? $this->service_category;
    }

    public function getBillingTypeDisplayAttribute()
    {
        $types = [
            'Fixed' => '💰 Fixed Rate',
            'Variable' => '📊 Variable Rate',
            'Percentage' => '📈 Percentage Based',
            'Hourly' => '⏰ Hourly Rate',
            'Per Unit' => '📦 Per Unit',
            'Tiered' => '📶 Tiered Pricing'
        ];

        return $types[$this->billing_type] ?? $this->billing_type;
    }

    public function getServiceProviderDisplayAttribute()
    {
        $providers = [
            'Internal' => '🏢 Internal',
            'External' => '🤝 External Partner',
            'Both' => '🔄 Internal & External'
        ];

        return $providers[$this->service_provider] ?? $this->service_provider;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Active' => 'status-success',
            'Inactive' => 'status-secondary',
            'Suspended' => 'status-warning',
            'Discontinued' => 'status-danger'
        ];

        return $badges[$this->status] ?? 'status-secondary';
    }

    public function getFormattedRateAttribute()
    {
        if (!$this->base_rate) return 'Rate not set';

        $rate = $this->rate_currency . ' ' . number_format($this->base_rate, 2);

        if ($this->rate_unit) {
            $rate .= ' ' . $this->rate_unit;
        }

        return $rate;
    }

    public function getRequiredDocumentsListAttribute()
    {
        if (!$this->required_documents || !is_array($this->required_documents)) {
            return 'No specific documents required';
        }

        return implode(', ', $this->required_documents);
    }

    public function getApplicableCargoTypesListAttribute()
    {
        if (!$this->applicable_cargo_types || !is_array($this->applicable_cargo_types)) {
            return 'All cargo types';
        }

        return implode(', ', $this->applicable_cargo_types);
    }

    public function getEstimatedDurationDisplayAttribute()
    {
        if (!$this->estimated_duration_hours) {
            return 'Duration varies';
        }

        if ($this->estimated_duration_hours < 1) {
            return ($this->estimated_duration_hours * 60) . ' minutes';
        }

        if ($this->estimated_duration_hours >= 24) {
            $days = floor($this->estimated_duration_hours / 24);
            $hours = $this->estimated_duration_hours % 24;
            return $days . ' days' . ($hours > 0 ? ', ' . $hours . ' hours' : '');
        }

        return $this->estimated_duration_hours . ' hours';
    }

    public function getTotalUsageAttribute()
    {
        // TODO: Implement when ShipmentService model exists
        return 0; // $this->shipmentServices()->count();
    }

    public function getMonthlyUsageAttribute()
    {
        // TODO: Implement when ShipmentService model exists  
        return 0; // $this->shipmentServices()->whereMonth('created_at', now()->month)->count();
    }

    // Methods
    public function calculateRate($quantity = 1, $shipmentValue = 0, $additionalParams = [])
    {
        $rate = $this->base_rate;

        switch ($this->billing_type) {
            case 'Fixed':
                // Fixed rate regardless of quantity
                break;

            case 'Variable':
            case 'Per Unit':
                $rate = $rate * $quantity;
                break;

            case 'Percentage':
                $rate = ($shipmentValue * $rate) / 100;
                break;

            case 'Hourly':
                $hours = $additionalParams['hours'] ?? $this->estimated_duration_hours ?? 1;
                $rate = $rate * $hours;
                break;

            case 'Tiered':
                $rate = $this->calculateTieredRate($quantity);
                break;
        }

        // Apply minimum and maximum limits
        if ($this->minimum_charge && $rate < $this->minimum_charge) {
            $rate = $this->minimum_charge;
        }

        if ($this->maximum_charge && $rate > $this->maximum_charge) {
            $rate = $this->maximum_charge;
        }

        return round($rate, 2);
    }

    public function calculateTieredRate($quantity)
    {
        if (!$this->rate_tiers || !is_array($this->rate_tiers)) {
            return $this->base_rate * $quantity;
        }

        $totalRate = 0;
        $remainingQuantity = $quantity;

        foreach ($this->rate_tiers as $tier) {
            $tierMin = $tier['min'] ?? 0;
            $tierMax = $tier['max'] ?? PHP_INT_MAX;
            $tierRate = $tier['rate'] ?? $this->base_rate;

            if ($remainingQuantity <= 0) break;

            $tierQuantity = min($remainingQuantity, $tierMax - $tierMin + 1);
            $totalRate += $tierQuantity * $tierRate;
            $remainingQuantity -= $tierQuantity;
        }

        return $totalRate;
    }

    public function calculateTax($baseAmount)
    {
        if (!$this->tax_percentage || $this->tax_type === 'Exempt') {
            return 0;
        }

        return round(($baseAmount * $this->tax_percentage) / 100, 2);
    }

    public function isAvailableForCargo($cargoType)
    {
        if (!$this->applicable_cargo_types) {
            return true; // Available for all cargo types
        }

        return in_array($cargoType, $this->applicable_cargo_types);
    }

    public function isEffectiveOn($date = null)
    {
        $date = $date ?: now();

        $afterStart = !$this->effective_from || $date >= $this->effective_from;
        $beforeEnd = !$this->effective_to || $date <= $this->effective_to;

        return $afterStart && $beforeEnd;
    }

    public function getStatistics()
    {
        return [
            'total_usage' => 0, // $this->total_usage,
            'monthly_usage' => 0, // $this->monthly_usage,
            'active_quotes' => 0, // $this->serviceQuotes()->active()->count(),
            'total_revenue' => 0, // $this->getTotalRevenue(),
            'average_rate' => 0, // $this->getAverageRate(),
            'last_used' => null, // $this->getLastUsed(),
            'performance_rating' => 0 // $this->getPerformanceRating()
        ];
    }

    public function getTotalRevenue()
    {
        // TODO: Implement when ShipmentService model exists
        return 0;
        // return $this->shipmentServices()
        //     ->whereNotNull('charged_amount')
        //     ->sum('charged_amount');
    }

    public function getAverageRate()
    {
        // TODO: Implement when ShipmentService model exists
        return 0;
        // return $this->shipmentServices()
        //     ->whereNotNull('charged_amount')
        //     ->avg('charged_amount') ?? 0;
    }

    public function getLastUsed()
    {
        // TODO: Implement when ShipmentService model exists
        return null;
        // $lastService = $this->shipmentServices()
        //     ->latest()
        //     ->first();
        // return $lastService ? $lastService->created_at : null;
    }

    public function getPerformanceRating()
    {
        // TODO: Implement when ShipmentService model exists
        return 0;
        // Calculate performance based on usage frequency and customer satisfaction
        // $usage = $this->monthly_usage;
        // $rating = 0;

        // if ($usage >= 50) $rating = 5;
        // elseif ($usage >= 30) $rating = 4;
        // elseif ($usage >= 15) $rating = 3;
        // elseif ($usage >= 5) $rating = 2;
        // elseif ($usage > 0) $rating = 1;

        // return $rating;
    }

    public function getUsageByMonth($startDate, $endDate)
    {
        // TODO: Implement when ShipmentService model exists
        return collect();
        // return $this->shipmentServices()
        //     ->whereBetween('created_at', [$startDate, $endDate])
        //     ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
        //     ->groupBy('year', 'month')
        //     ->orderBy('year')
        //     ->orderBy('month')
        //     ->get();
    }

    public function getRevenueByMonth($startDate, $endDate)
    {
        // TODO: Implement when ShipmentService model exists
        return collect();
        // return $this->shipmentServices()
        //     ->whereBetween('created_at', [$startDate, $endDate])
        //     ->whereNotNull('charged_amount')
        //     ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(charged_amount) as revenue')
        //     ->groupBy('year', 'month')
        //     ->orderBy('year')
        //     ->orderBy('month')
        //     ->get();
    }

    public function getTopClients($limit = 5)
    {
        // TODO: Implement when ShipmentService model exists
        return collect();
        // return $this->shipmentServices()
        //     ->join('shipments', 'shipment_services.shipment_id', '=', 'shipments.id')
        //     ->join('companies', 'shipments.company_id', '=', 'companies.id')
        //     ->selectRaw('companies.name, COUNT(*) as usage_count, SUM(shipment_services.charged_amount) as total_revenue')
        //     ->groupBy('companies.id', 'companies.name')
        //     ->orderByDesc('usage_count')
        //     ->limit($limit)
        //     ->get();
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'service_code' => 'required|string|max:50|unique:services,service_code,' . $id,
            'service_name' => 'required|string|max:255',
            'service_category' => 'required|string|in:Customs Clearance,Transportation,Warehousing,Documentation,Insurance,Inspection,Cargo Handling,Port Services,Freight Forwarding,Consulting,Other',
            'description' => 'nullable|string|max:500',
            'detailed_description' => 'nullable|string',
            'billing_type' => 'required|string|in:Fixed,Variable,Percentage,Hourly,Per Unit,Tiered',
            'base_rate' => 'required|numeric|min:0|max:999999.99',
            'rate_currency' => 'required|string|size:3',
            'rate_unit' => 'nullable|string|max:100',
            'minimum_charge' => 'nullable|numeric|min:0|max:999999.99',
            'maximum_charge' => 'nullable|numeric|min:0|max:999999.99',
            'tax_type' => 'nullable|string|in:VAT,Service Tax,Sales Tax,Exempt',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'is_mandatory' => 'required|boolean',
            'is_billable' => 'required|boolean',
            'requires_approval' => 'required|boolean',
            'estimated_duration_hours' => 'nullable|integer|min:0|max:9999',
            'service_conditions' => 'nullable|string',
            'service_provider' => 'required|string|in:Internal,External,Both',
            'status' => 'required|string|in:Active,Inactive,Suspended,Discontinued',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string'
        ];
    }

    public static function getServiceCategories()
    {
        return [
            'Customs Clearance' => 'Customs Clearance',
            'Transportation' => 'Transportation',
            'Warehousing' => 'Warehousing',
            'Documentation' => 'Documentation',
            'Insurance' => 'Insurance',
            'Inspection' => 'Inspection',
            'Cargo Handling' => 'Cargo Handling',
            'Port Services' => 'Port Services',
            'Freight Forwarding' => 'Freight Forwarding',
            'Consulting' => 'Consulting',
            'Other' => 'Other'
        ];
    }

    public static function getBillingTypes()
    {
        return [
            'Fixed' => 'Fixed Rate',
            'Variable' => 'Variable Rate',
            'Percentage' => 'Percentage Based',
            'Hourly' => 'Hourly Rate',
            'Per Unit' => 'Per Unit',
            'Tiered' => 'Tiered Pricing'
        ];
    }

    public static function getTaxTypes()
    {
        return [
            'VAT' => 'VAT',
            'Service Tax' => 'Service Tax',
            'Sales Tax' => 'Sales Tax',
            'Exempt' => 'Tax Exempt'
        ];
    }

    public static function getServiceProviders()
    {
        return [
            'Internal' => 'Internal',
            'External' => 'External Partner',
            'Both' => 'Internal & External'
        ];
    }

    public static function getRequiredDocumentsList()
    {
        return [
            'Commercial Invoice',
            'Packing List',
            'Bill of Lading',
            'Certificate of Origin',
            'Import License',
            'Export License',
            'Insurance Certificate',
            'Inspection Certificate',
            'Customs Declaration',
            'Delivery Order',
            'Payment Receipt',
            'Bank Guarantee',
            'Quality Certificate',
            'Phytosanitary Certificate',
            'Health Certificate'
        ];
    }

    public static function getCargoTypes()
    {
        return [
            'General Cargo',
            'Container (FCL)',
            'Container (LCL)',
            'Bulk Cargo',
            'Liquid Bulk',
            'Break Bulk',
            'Ro-Ro Cargo',
            'Dangerous Goods',
            'Refrigerated Cargo',
            'Oversized Cargo',
            'High Value Cargo',
            'Livestock',
            'Vehicles',
            'Machinery',
            'Electronics'
        ];
    }
}
