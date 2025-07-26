<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShipmentType extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'type_code',
        'type_name',
        'category',
        'subcategory',
        'description',
        'detailed_description',
        'cargo_type',
        'container_types',
        'transit_mode',
        'handling_requirements',
        'documentation_required',
        'special_instructions',
        'estimated_transit_days',
        'min_transit_days',
        'max_transit_days',
        'weight_restrictions',
        'volume_restrictions',
        'dimension_restrictions',
        'temperature_controlled',
        'hazardous_material',
        'high_value_cargo',
        'fragile_cargo',
        'oversized_cargo',
        'requires_escort',
        'customs_complexity',
        'insurance_required',
        'tracking_level',
        'cost_factor',
        'base_rate_multiplier',
        'priority_level',
        'service_level',
        'applicable_routes',
        'seasonal_restrictions',
        'equipment_needed',
        'loading_instructions',
        'unloading_instructions',
        'storage_requirements',
        'packaging_requirements',
        'labeling_requirements',
        'certification_needed',
        'inspection_required',
        'quarantine_required',
        'permit_required',
        'booking_lead_time',
        'cutoff_requirements',
        'consolidation_allowed',
        'partial_loads_allowed',
        'return_loads_allowed',
        'transshipment_allowed',
        'door_to_door_available',
        'port_to_port_only',
        'express_service_available',
        'economy_service_available',
        'standard_service_available',
        'status',
        'effective_from',
        'effective_to',
        'notes'
    ];

    protected $casts = [
        'container_types' => 'array',
        'handling_requirements' => 'array',
        'documentation_required' => 'array',
        'weight_restrictions' => 'array',
        'volume_restrictions' => 'array',
        'dimension_restrictions' => 'array',
        'applicable_routes' => 'array',
        'seasonal_restrictions' => 'array',
        'equipment_needed' => 'array',
        'packaging_requirements' => 'array',
        'labeling_requirements' => 'array',
        'certification_needed' => 'array',
        'temperature_controlled' => 'boolean',
        'hazardous_material' => 'boolean',
        'high_value_cargo' => 'boolean',
        'fragile_cargo' => 'boolean',
        'oversized_cargo' => 'boolean',
        'requires_escort' => 'boolean',
        'insurance_required' => 'boolean',
        'inspection_required' => 'boolean',
        'quarantine_required' => 'boolean',
        'permit_required' => 'boolean',
        'consolidation_allowed' => 'boolean',
        'partial_loads_allowed' => 'boolean',
        'return_loads_allowed' => 'boolean',
        'transshipment_allowed' => 'boolean',
        'door_to_door_available' => 'boolean',
        'port_to_port_only' => 'boolean',
        'express_service_available' => 'boolean',
        'economy_service_available' => 'boolean',
        'standard_service_available' => 'boolean',
        'cost_factor' => 'decimal:2',
        'base_rate_multiplier' => 'decimal:3',
        'effective_from' => 'date',
        'effective_to' => 'date'
    ];

    // Relationships
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'shipment_type_id');
    }

    // TODO: Uncomment when ShipmentQuote model exists
    // public function shipmentQuotes()
    // {
    //     return $this->hasMany(ShipmentQuote::class);
    // }

    // Many-to-many with ports for route-specific configurations
    public function ports()
    {
        return $this->belongsToMany(Port::class, 'port_shipment_types')
            ->withPivot(['additional_requirements', 'handling_surcharge', 'transit_adjustment'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByCargoType($query, $cargoType)
    {
        return $query->where('cargo_type', $cargoType);
    }

    public function scopeByTransitMode($query, $mode)
    {
        return $query->where('transit_mode', $mode);
    }

    public function scopeTemperatureControlled($query)
    {
        return $query->where('temperature_controlled', true);
    }

    public function scopeHazardous($query)
    {
        return $query->where('hazardous_material', true);
    }

    public function scopeHighValue($query)
    {
        return $query->where('high_value_cargo', true);
    }

    public function scopeFragile($query)
    {
        return $query->where('fragile_cargo', true);
    }

    public function scopeOversized($query)
    {
        return $query->where('oversized_cargo', true);
    }

    public function scopeExpressService($query)
    {
        return $query->where('express_service_available', true);
    }

    public function scopeEconomyService($query)
    {
        return $query->where('economy_service_available', true);
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

    // Accessors
    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'Ocean Freight' => '🚢 Ocean Freight',
            'Air Freight' => '✈️ Air Freight',
            'Land Transport' => '🚛 Land Transport',
            'Rail Transport' => '🚂 Rail Transport',
            'Multimodal' => '🔄 Multimodal',
            'Express' => '⚡ Express',
            'Economy' => '💰 Economy',
            'Special Handling' => '⚠️ Special Handling',
            'Project Cargo' => '🏗️ Project Cargo',
            'Bulk Cargo' => '⚖️ Bulk Cargo',
            'Container' => '📦 Container',
            'Break Bulk' => '📋 Break Bulk'
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getCargoTypeDisplayAttribute()
    {
        $types = [
            'General Cargo' => '📦 General Cargo',
            'Dangerous Goods' => '☢️ Dangerous Goods',
            'Refrigerated' => '❄️ Refrigerated',
            'Liquid Bulk' => '🌊 Liquid Bulk',
            'Dry Bulk' => '⚖️ Dry Bulk',
            'Vehicles' => '🚗 Vehicles',
            'Heavy Machinery' => '🏗️ Heavy Machinery',
            'Electronics' => '💻 Electronics',
            'Pharmaceuticals' => '💊 Pharmaceuticals',
            'Food Products' => '🍎 Food Products',
            'Textiles' => '🧵 Textiles',
            'Chemicals' => '🧪 Chemicals',
            'Raw Materials' => '🪨 Raw Materials',
            'Finished Goods' => '📦 Finished Goods',
            'Perishables' => '🥬 Perishables'
        ];

        return $types[$this->cargo_type] ?? $this->cargo_type;
    }

    public function getTransitModeDisplayAttribute()
    {
        $modes = [
            'Sea' => '🚢 Sea Freight',
            'Air' => '✈️ Air Freight',
            'Road' => '🚛 Road Transport',
            'Rail' => '🚂 Rail Transport',
            'Barge' => '🚤 Barge Transport',
            'Pipeline' => '🔧 Pipeline',
            'Multimodal' => '🔄 Multimodal'
        ];

        return $modes[$this->transit_mode] ?? $this->transit_mode;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Active' => 'status-success',
            'Inactive' => 'status-secondary',
            'Suspended' => 'status-warning',
            'Discontinued' => 'status-danger',
            'Under Review' => 'status-info'
        ];

        return $badges[$this->status] ?? 'status-secondary';
    }

    public function getPriorityLevelDisplayAttribute()
    {
        $levels = [
            'Low' => '🟢 Low Priority',
            'Standard' => '🟡 Standard Priority',
            'High' => '🟠 High Priority',
            'Urgent' => '🔴 Urgent Priority',
            'Critical' => '⚫ Critical Priority'
        ];

        return $levels[$this->priority_level] ?? $this->priority_level;
    }

    public function getServiceLevelDisplayAttribute()
    {
        $levels = [
            'Basic' => '📦 Basic Service',
            'Standard' => '⭐ Standard Service',
            'Premium' => '👑 Premium Service',
            'Express' => '⚡ Express Service',
            'Economy' => '💰 Economy Service'
        ];

        return $levels[$this->service_level] ?? $this->service_level;
    }

    public function getEstimatedTransitDisplayAttribute()
    {
        if ($this->min_transit_days && $this->max_transit_days) {
            return $this->min_transit_days . '-' . $this->max_transit_days . ' days';
        } elseif ($this->estimated_transit_days) {
            return $this->estimated_transit_days . ' days';
        }
        return 'Transit time varies';
    }

    public function getHandlingRequirementsListAttribute()
    {
        if (!$this->handling_requirements || !is_array($this->handling_requirements)) {
            return 'Standard handling';
        }

        return implode(', ', $this->handling_requirements);
    }

    public function getDocumentationRequiredListAttribute()
    {
        if (!$this->documentation_required || !is_array($this->documentation_required)) {
            return 'Standard documentation';
        }

        return implode(', ', $this->documentation_required);
    }

    public function getContainerTypesListAttribute()
    {
        if (!$this->container_types || !is_array($this->container_types)) {
            return 'All container types';
        }

        return implode(', ', $this->container_types);
    }

    public function getEquipmentNeededListAttribute()
    {
        if (!$this->equipment_needed || !is_array($this->equipment_needed)) {
            return 'Standard equipment';
        }

        return implode(', ', $this->equipment_needed);
    }

    public function getSpecialFeaturesAttribute()
    {
        $features = [];

        if ($this->temperature_controlled) $features[] = '❄️ Temperature Controlled';
        if ($this->hazardous_material) $features[] = '☢️ Hazardous Material';
        if ($this->high_value_cargo) $features[] = '💎 High Value';
        if ($this->fragile_cargo) $features[] = '🔸 Fragile';
        if ($this->oversized_cargo) $features[] = '📏 Oversized';
        if ($this->requires_escort) $features[] = '🚔 Requires Escort';
        if ($this->insurance_required) $features[] = '🛡️ Insurance Required';
        if ($this->inspection_required) $features[] = '🔍 Inspection Required';

        return $features;
    }

    public function getServiceOptionsAttribute()
    {
        $options = [];

        if ($this->express_service_available) $options[] = '⚡ Express Available';
        if ($this->economy_service_available) $options[] = '💰 Economy Available';
        if ($this->standard_service_available) $options[] = '⭐ Standard Available';
        if ($this->door_to_door_available) $options[] = '🚪 Door-to-Door';
        if ($this->consolidation_allowed) $options[] = '📦 Consolidation';
        if ($this->partial_loads_allowed) $options[] = '📋 Partial Loads';

        return $options;
    }

    public function getTotalShipmentsAttribute()
    {
        return $this->shipments()->count();
    }

    public function getMonthlyShipmentsAttribute()
    {
        return $this->shipments()
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    // Methods
    public function isEffectiveOn($date = null)
    {
        $date = $date ?: now();

        $afterStart = !$this->effective_from || $date >= $this->effective_from;
        $beforeEnd = !$this->effective_to || $date <= $this->effective_to;

        return $afterStart && $beforeEnd;
    }

    public function calculateCostMultiplier()
    {
        $multiplier = $this->base_rate_multiplier ?? 1.0;

        // Add surcharges for special handling
        if ($this->temperature_controlled) $multiplier += 0.15;
        if ($this->hazardous_material) $multiplier += 0.25;
        if ($this->high_value_cargo) $multiplier += 0.10;
        if ($this->fragile_cargo) $multiplier += 0.08;
        if ($this->oversized_cargo) $multiplier += 0.20;
        if ($this->requires_escort) $multiplier += 0.30;

        return round($multiplier, 3);
    }

    public function getApplicableContainerTypes()
    {
        if (!$this->container_types) {
            return self::getAllContainerTypes();
        }

        return $this->container_types;
    }

    public function canHandleCargoType($cargoType)
    {
        if (!$this->cargo_type) {
            return true; // No restrictions
        }

        return $this->cargo_type === $cargoType;
    }

    public function isAvailableForRoute($originPort, $destinationPort)
    {
        if (!$this->applicable_routes) {
            return true; // No route restrictions
        }

        foreach ($this->applicable_routes as $route) {
            if (($route['origin'] === $originPort || $route['origin'] === 'Any') &&
                ($route['destination'] === $destinationPort || $route['destination'] === 'Any')
            ) {
                return true;
            }
        }

        return false;
    }

    public function getStatistics()
    {
        return [
            'total_shipments' => $this->total_shipments,
            'monthly_shipments' => $this->monthly_shipments,
            'active_shipments' => $this->getActiveShipments(),
            'avg_transit_time' => $this->getAverageTransitTime(),
            'cost_effectiveness' => $this->getCostEffectiveness(),
            'customer_satisfaction' => $this->getCustomerSatisfaction(),
            'performance_rating' => $this->getPerformanceRating()
        ];
    }

    public function getActiveShipments()
    {
        return $this->shipments()
            ->whereIn('status', ['Pending', 'In Transit', 'At Port'])
            ->count();
    }

    public function getAverageTransitTime()
    {
        // TODO: Implement when shipment tracking is available
        return $this->estimated_transit_days ?? 0;
    }

    public function getCostEffectiveness()
    {
        // Calculate based on usage vs cost factor
        $usage = $this->monthly_shipments;
        $costFactor = $this->cost_factor ?? 1.0;

        if ($costFactor == 0) return 100;

        return round(($usage / $costFactor) * 10, 1);
    }

    public function getCustomerSatisfaction()
    {
        // TODO: Implement when customer feedback is available
        return rand(75, 95); // Placeholder
    }

    public function getPerformanceRating()
    {
        $usage = $this->monthly_shipments;
        $rating = 0;

        if ($usage >= 100) $rating = 5;
        elseif ($usage >= 50) $rating = 4;
        elseif ($usage >= 20) $rating = 3;
        elseif ($usage >= 5) $rating = 2;
        elseif ($usage > 0) $rating = 1;

        return $rating;
    }

    public function getShipmentsByMonth($startDate, $endDate)
    {
        return $this->shipments()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    public function getTopClients($limit = 5)
    {
        return $this->shipments()
            ->join('companies', 'shipments.company_id', '=', 'companies.id')
            ->selectRaw('companies.name, COUNT(*) as shipment_count')
            ->groupBy('companies.id', 'companies.name')
            ->orderByDesc('shipment_count')
            ->limit($limit)
            ->get();
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'type_code' => 'required|string|max:20|unique:shipment_types,type_code,' . $id,
            'type_name' => 'required|string|max:255',
            'category' => 'required|string|in:Ocean Freight,Air Freight,Land Transport,Rail Transport,Multimodal,Express,Economy,Special Handling,Project Cargo,Bulk Cargo,Container,Break Bulk',
            'subcategory' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'detailed_description' => 'nullable|string',
            'cargo_type' => 'required|string|in:General Cargo,Dangerous Goods,Refrigerated,Liquid Bulk,Dry Bulk,Vehicles,Heavy Machinery,Electronics,Pharmaceuticals,Food Products,Textiles,Chemicals,Raw Materials,Finished Goods,Perishables',
            'transit_mode' => 'required|string|in:Sea,Air,Road,Rail,Barge,Pipeline,Multimodal',
            'estimated_transit_days' => 'nullable|integer|min:0|max:365',
            'min_transit_days' => 'nullable|integer|min:0|max:365',
            'max_transit_days' => 'nullable|integer|min:0|max:365',
            'cost_factor' => 'nullable|numeric|min:0|max:10',
            'base_rate_multiplier' => 'nullable|numeric|min:0|max:10',
            'priority_level' => 'nullable|string|in:Low,Standard,High,Urgent,Critical',
            'service_level' => 'nullable|string|in:Basic,Standard,Premium,Express,Economy',
            'customs_complexity' => 'nullable|string|in:Simple,Standard,Complex,Very Complex',
            'tracking_level' => 'nullable|string|in:Basic,Standard,Advanced,Real-time',
            'booking_lead_time' => 'nullable|integer|min:0|max:30',
            'status' => 'required|string|in:Active,Inactive,Suspended,Discontinued,Under Review',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after:effective_from'
        ];
    }

    public static function getCategories()
    {
        return [
            'Ocean Freight' => 'Ocean Freight',
            'Air Freight' => 'Air Freight',
            'Land Transport' => 'Land Transport',
            'Rail Transport' => 'Rail Transport',
            'Multimodal' => 'Multimodal',
            'Express' => 'Express',
            'Economy' => 'Economy',
            'Special Handling' => 'Special Handling',
            'Project Cargo' => 'Project Cargo',
            'Bulk Cargo' => 'Bulk Cargo',
            'Container' => 'Container',
            'Break Bulk' => 'Break Bulk'
        ];
    }

    public static function getCargoTypes()
    {
        return [
            'General Cargo' => 'General Cargo',
            'Dangerous Goods' => 'Dangerous Goods',
            'Refrigerated' => 'Refrigerated',
            'Liquid Bulk' => 'Liquid Bulk',
            'Dry Bulk' => 'Dry Bulk',
            'Vehicles' => 'Vehicles',
            'Heavy Machinery' => 'Heavy Machinery',
            'Electronics' => 'Electronics',
            'Pharmaceuticals' => 'Pharmaceuticals',
            'Food Products' => 'Food Products',
            'Textiles' => 'Textiles',
            'Chemicals' => 'Chemicals',
            'Raw Materials' => 'Raw Materials',
            'Finished Goods' => 'Finished Goods',
            'Perishables' => 'Perishables'
        ];
    }

    public static function getTransitModes()
    {
        return [
            'Sea' => 'Sea Freight',
            'Air' => 'Air Freight',
            'Road' => 'Road Transport',
            'Rail' => 'Rail Transport',
            'Barge' => 'Barge Transport',
            'Pipeline' => 'Pipeline',
            'Multimodal' => 'Multimodal'
        ];
    }

    public static function getAllContainerTypes()
    {
        return [
            '20GP' => '20ft General Purpose',
            '40GP' => '40ft General Purpose',
            '40HC' => '40ft High Cube',
            '45HC' => '45ft High Cube',
            '20RF' => '20ft Refrigerated',
            '40RF' => '40ft Refrigerated',
            '20OT' => '20ft Open Top',
            '40OT' => '40ft Open Top',
            '20FR' => '20ft Flat Rack',
            '40FR' => '40ft Flat Rack',
            '20TK' => '20ft Tank Container',
            '40TK' => '40ft Tank Container'
        ];
    }

    public static function getHandlingRequirements()
    {
        return [
            'Standard Loading',
            'Careful Handling',
            'Temperature Control',
            'Hazmat Procedures',
            'Security Escort',
            'Special Equipment',
            'Crane Required',
            'Forklift Access',
            'Side Loading',
            'Top Loading',
            'Fragile Item Care',
            'Heavy Lift',
            'Oversized Handling',
            'Clean Environment',
            'Dry Storage',
            'Ventilation Required'
        ];
    }

    public static function getDocumentationList()
    {
        return [
            'Commercial Invoice',
            'Packing List',
            'Bill of Lading',
            'Certificate of Origin',
            'Export License',
            'Import Permit',
            'Dangerous Goods Declaration',
            'Temperature Certificate',
            'Phytosanitary Certificate',
            'Health Certificate',
            'Insurance Certificate',
            'Inspection Certificate',
            'Quality Certificate',
            'Weight Certificate',
            'Customs Declaration',
            'Transit Documents'
        ];
    }

    public static function getEquipmentList()
    {
        return [
            'Standard Container',
            'Refrigerated Container',
            'Open Top Container',
            'Flat Rack Container',
            'Tank Container',
            'Specialized Trailer',
            'Temperature Monitoring',
            'GPS Tracking',
            'Security Seals',
            'Crane Equipment',
            'Heavy Lift Gear',
            'Ventilation System',
            'Heating System',
            'Shock Absorbers',
            'Moisture Control',
            'Gas Monitoring'
        ];
    }
}
