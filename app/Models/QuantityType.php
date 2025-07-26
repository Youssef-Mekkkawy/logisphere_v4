<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class QuantityType extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'quantity_code',
        'quantity_name',
        'quantity_category',
        'unit_of_measure',
        'unit_symbol',
        'base_unit',
        'conversion_factor',
        'decimal_places',
        'description',
        'calculation_method',
        'applicable_cargo_types',
        'industry_standards',
        'common_ranges',
        'validation_rules',
        'display_format',
        'reporting_category',
        'is_weight_based',
        'is_volume_based',
        'is_count_based',
        'is_dimension_based',
        'allows_fractions',
        'requires_dimensions',
        'auto_calculate',
        'is_billable',
        'billing_multiplier',
        'minimum_chargeable',
        'rounding_method',
        'is_standard',
        'is_active',
        'sort_order',
        'notes'
    ];

    protected $casts = [
        'applicable_cargo_types' => 'array',
        'industry_standards' => 'array',
        'common_ranges' => 'array',
        'validation_rules' => 'array',
        'conversion_factor' => 'decimal:6',
        'billing_multiplier' => 'decimal:4',
        'minimum_chargeable' => 'decimal:6',
        'is_weight_based' => 'boolean',
        'is_volume_based' => 'boolean',
        'is_count_based' => 'boolean',
        'is_dimension_based' => 'boolean',
        'allows_fractions' => 'boolean',
        'requires_dimensions' => 'boolean',
        'auto_calculate' => 'boolean',
        'is_billable' => 'boolean',
        'is_standard' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'quantity_type_id');
    }

    public function shipmentItems()
    {
        return $this->hasMany(Shipment::class, 'quantity_type_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStandard($query)
    {
        return $query->where('is_standard', true);
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('quantity_category', $category);
    }

    public function scopeWeightBased($query)
    {
        return $query->where('is_weight_based', true);
    }

    public function scopeVolumeBased($query)
    {
        return $query->where('is_volume_based', true);
    }

    public function scopeCountBased($query)
    {
        return $query->where('is_count_based', true);
    }

    public function scopeDimensionBased($query)
    {
        return $query->where('is_dimension_based', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('quantity_name');
    }

    // Accessors
    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'Container' => '📦 Container',
            'Weight' => '⚖️ Weight',
            'Volume' => '📏 Volume',
            'Count' => '🔢 Count',
            'Area' => '📐 Area',
            'Liquid' => '🫗 Liquid',
            'Length' => '📏 Length',
            'Time' => '⏰ Time'
        ];

        return $categories[$this->quantity_category] ?? $this->quantity_category;
    }

    public function getTypeIndicatorsAttribute()
    {
        $indicators = [];

        if ($this->is_weight_based) $indicators[] = 'Weight';
        if ($this->is_volume_based) $indicators[] = 'Volume';
        if ($this->is_count_based) $indicators[] = 'Count';
        if ($this->is_dimension_based) $indicators[] = 'Dimension';

        return $indicators;
    }

    public function getFormattedRangeAttribute()
    {
        if (!$this->common_ranges) return 'No range specified';

        $min = $this->common_ranges['min'] ?? 0;
        $max = $this->common_ranges['max'] ?? 'unlimited';

        return "Min: {$min}, Max: {$max}";
    }

    public function getUsageCountAttribute()
    {
        return $this->shipments()->count() + $this->shipmentItems()->count();
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) return 'status-inactive';
        if ($this->is_standard) return 'status-standard';
        return 'status-custom';
    }

    public function getRoundingDisplayAttribute()
    {
        $methods = [
            'up' => '⬆️ Round Up',
            'down' => '⬇️ Round Down',
            'nearest' => '🎯 Round Nearest'
        ];

        return $methods[$this->rounding_method] ?? $this->rounding_method;
    }

    // Methods
    public function formatValue($value)
    {
        if (!$this->display_format) {
            return number_format($value, $this->decimal_places) . ' ' . $this->unit_symbol;
        }

        return sprintf($this->display_format, $value);
    }

    public function roundValue($value)
    {
        $factor = pow(10, $this->decimal_places);

        switch ($this->rounding_method) {
            case 'up':
                return ceil($value * $factor) / $factor;
            case 'down':
                return floor($value * $factor) / $factor;
            case 'nearest':
            default:
                return round($value, $this->decimal_places);
        }
    }

    public function convertToBaseUnit($value)
    {
        return $value * $this->conversion_factor;
    }

    public function convertFromBaseUnit($value)
    {
        return $value / $this->conversion_factor;
    }

    public function validateValue($value)
    {
        $errors = [];

        // Check if value is positive
        if (in_array('positive_number', $this->validation_rules ?? []) && $value <= 0) {
            $errors[] = 'Value must be positive';
        }

        if (in_array('positive_integer', $this->validation_rules ?? []) && (!is_int($value) || $value <= 0)) {
            $errors[] = 'Value must be a positive integer';
        }

        // Check fractions
        if (!$this->allows_fractions && $value != floor($value)) {
            $errors[] = 'Fractional values are not allowed';
        }

        // Check range
        if ($this->common_ranges) {
            $min = $this->common_ranges['min'] ?? null;
            $max = $this->common_ranges['max'] ?? null;

            if ($min !== null && $value < $min) {
                $errors[] = "Value must be at least {$min}";
            }

            if ($max !== null && $value > $max) {
                $errors[] = "Value must not exceed {$max}";
            }
        }

        // Check minimum chargeable
        if ($this->is_billable && $value < $this->minimum_chargeable) {
            $errors[] = "Minimum chargeable amount is {$this->minimum_chargeable}";
        }

        return $errors;
    }

    public function calculateBillableAmount($quantity)
    {
        if (!$this->is_billable) return 0;

        $amount = max($quantity, $this->minimum_chargeable);
        $amount = $this->roundValue($amount * $this->billing_multiplier);

        return $amount;
    }

    public function isCompatibleWith($cargoType)
    {
        if (!$this->applicable_cargo_types) return true;

        return in_array($cargoType, $this->applicable_cargo_types);
    }

    public function getCalculationInstructions()
    {
        $instructions = [];

        if ($this->requires_dimensions) {
            $instructions[] = 'Requires dimensional measurements';
        }

        if ($this->auto_calculate) {
            $instructions[] = 'Value is automatically calculated';
        }

        if (!$this->allows_fractions) {
            $instructions[] = 'Only whole numbers allowed';
        }

        if ($this->calculation_method) {
            $instructions[] = $this->calculation_method;
        }

        return $instructions;
    }

    public function getStandardsDisplay()
    {
        if (!$this->industry_standards) return 'No specific standards';

        return implode(', ', $this->industry_standards);
    }

    public function getApplicableCargoDisplay()
    {
        if (!$this->applicable_cargo_types) return 'All cargo types';

        return implode(', ', $this->applicable_cargo_types);
    }

    // Statistics
    public function getUsageStatistics()
    {
        return [
            'total_shipments' => $this->shipments()->count(),
            'active_shipments' => $this->shipments()->whereIn('status', ['Pending', 'In Transit', 'At Port'])->count(),
            'monthly_usage' => $this->shipments()->whereMonth('created_at', now()->month)->count(),
            'total_quantity' => $this->shipments()->sum('total_quantity'),
            'average_quantity' => $this->shipments()->avg('total_quantity')
        ];
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'quantity_code' => 'required|string|max:10|unique:quantity_types,quantity_code,' . $id,
            'quantity_name' => 'required|string|max:255',
            'quantity_category' => 'required|string|in:Container,Weight,Volume,Count,Area,Liquid,Length,Time',
            'unit_of_measure' => 'required|string|max:255',
            'unit_symbol' => 'required|string|max:10',
            'base_unit' => 'nullable|string|max:50',
            'conversion_factor' => 'required|numeric|min:0.000001|max:999999.999999',
            'decimal_places' => 'required|integer|min:0|max:6',
            'description' => 'nullable|string',
            'calculation_method' => 'nullable|string',
            'display_format' => 'nullable|string|max:50',
            'reporting_category' => 'nullable|string|max:100',
            'billing_multiplier' => 'required|numeric|min:0.0001|max:9999.9999',
            'minimum_chargeable' => 'required|numeric|min:0.000001|max:999999.999999',
            'rounding_method' => 'required|string|in:up,down,nearest',
            'sort_order' => 'required|integer|min:1|max:9999',
            'notes' => 'nullable|string'
        ];
    }

    public static function getCategories()
    {
        return [
            'Container' => 'Container Units (TEU, FEU)',
            'Weight' => 'Weight Measurements (KG, MT, LB)',
            'Volume' => 'Volume Measurements (CBM, CFT)',
            'Count' => 'Count/Pieces (PCS, PKG)',
            'Area' => 'Area Measurements (SQM, SQFT)',
            'Liquid' => 'Liquid Measurements (LTR, GAL)',
            'Length' => 'Linear Measurements (M, FT)',
            'Time' => 'Time-based Units (HR, DAY)'
        ];
    }

    public static function getRoundingMethods()
    {
        return [
            'up' => 'Round Up (Ceiling)',
            'down' => 'Round Down (Floor)',
            'nearest' => 'Round to Nearest'
        ];
    }

    public static function getDefaultValidationRules()
    {
        return [
            'positive_number' => 'Must be positive number',
            'positive_integer' => 'Must be positive integer',
            'required' => 'Value is required',
            'min_value' => 'Must meet minimum value',
            'max_value' => 'Must not exceed maximum'
        ];
    }

    public static function getCargoTypes()
    {
        return [
            'General Cargo',
            'Containers',
            'Bulk Cargo',
            'Break Bulk',
            'Liquid Bulk',
            'Project Cargo',
            'Heavy Cargo',
            'Hazardous Cargo',
            'Refrigerated Cargo',
            'Air Cargo',
            'LCL (Less Container Load)',
            'FCL (Full Container Load)',
            'Ro-Ro Cargo'
        ];
    }
}
