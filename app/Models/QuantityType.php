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
        'customs_code',
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
        'minimum_chargeable' => 'decimal:2',
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

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'quantity_type_id');
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('quantity_name');
    }

    // Accessors
    public function getStatusAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getFullNameAttribute()
    {
        return "{$this->quantity_name} ({$this->unit_symbol})";
    }

    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'Weight' => '⚖️ Weight Measurements',
            'Volume' => '📦 Volume Measurements',
            'Count' => '🔢 Count/Pieces',
            'Dimension' => '📏 Dimensional',
            'Container' => '🚛 Container Units',
            'Liquid' => '💧 Liquid Measurements',
            'Area' => '📐 Area Measurements',
            'Time' => '⏰ Time Based',
            'Custom' => '⚙️ Custom Units'
        ];

        return $categories[$this->quantity_category] ?? $this->quantity_category;
    }

    public function getUnitDisplayAttribute()
    {
        return $this->unit_symbol ? "{$this->unit_of_measure} ({$this->unit_symbol})" : $this->unit_of_measure;
    }

    public function getTypeIndicatorsAttribute()
    {
        $indicators = [];
        if ($this->is_weight_based) $indicators[] = '⚖️ Weight';
        if ($this->is_volume_based) $indicators[] = '📦 Volume';
        if ($this->is_count_based) $indicators[] = '🔢 Count';
        if ($this->is_dimension_based) $indicators[] = '📏 Dimension';

        return $indicators ? implode(', ', $indicators) : 'Standard';
    }

    public function getBillingDisplayAttribute()
    {
        if (!$this->is_billable) {
            return 'Non-billable';
        }

        $display = 'Billable';
        if ($this->billing_multiplier && $this->billing_multiplier != 1) {
            $display .= " (x{$this->billing_multiplier})";
        }
        if ($this->minimum_chargeable && $this->minimum_chargeable > 0) {
            $display .= " (Min: {$this->minimum_chargeable})";
        }

        return $display;
    }

    public function getConversionDisplayAttribute()
    {
        if (!$this->base_unit || !$this->conversion_factor) {
            return 'Base unit';
        }

        return "1 {$this->unit_symbol} = {$this->conversion_factor} {$this->base_unit}";
    }

    public function getApplicableCargoListAttribute()
    {
        if (!$this->applicable_cargo_types || !is_array($this->applicable_cargo_types)) {
            return 'All cargo types';
        }

        return implode(', ', $this->applicable_cargo_types);
    }

    public function getIndustryStandardsListAttribute()
    {
        if (!$this->industry_standards || !is_array($this->industry_standards)) {
            return 'No specific standards';
        }

        return implode(', ', $this->industry_standards);
    }

    // Methods
    public function convertTo($value, $targetQuantityType)
    {
        if (!$this->base_unit || !$targetQuantityType->base_unit) {
            return null; // Cannot convert without base units
        }

        if ($this->base_unit !== $targetQuantityType->base_unit) {
            return null; // Cannot convert between different base units
        }

        // Convert to base unit first, then to target unit
        $baseValue = $value * $this->conversion_factor;
        $targetValue = $baseValue / $targetQuantityType->conversion_factor;

        return round($targetValue, $targetQuantityType->decimal_places ?? 2);
    }

    public function formatValue($value, $showSymbol = true)
    {
        $decimalPlaces = $this->decimal_places ?? 2;

        if (!$this->allows_fractions) {
            $decimalPlaces = 0;
        }

        $formatted = number_format($value, $decimalPlaces);

        if ($this->display_format) {
            // Custom formatting logic could be implemented here
            $formatted = sprintf($this->display_format, $value);
        }

        if ($showSymbol && $this->unit_symbol) {
            $formatted .= ' ' . $this->unit_symbol;
        }

        return $formatted;
    }

    public function validateValue($value)
    {
        $errors = [];

        // Check if fractions are allowed
        if (!$this->allows_fractions && $value != intval($value)) {
            $errors[] = 'Fractional values not allowed for this quantity type';
        }

        // Check common ranges
        if ($this->common_ranges && is_array($this->common_ranges)) {
            if (isset($this->common_ranges['min']) && $value < $this->common_ranges['min']) {
                $errors[] = "Value below minimum range ({$this->common_ranges['min']})";
            }
            if (isset($this->common_ranges['max']) && $value > $this->common_ranges['max']) {
                $errors[] = "Value above maximum range ({$this->common_ranges['max']})";
            }
        }

        // Apply custom validation rules
        if ($this->validation_rules && is_array($this->validation_rules)) {
            foreach ($this->validation_rules as $rule) {
                // Custom validation logic would be implemented here
                // This could include regex patterns, custom functions, etc.
            }
        }

        return empty($errors) ? true : $errors;
    }

    public function calculateBillableQuantity($actualQuantity)
    {
        if (!$this->is_billable) {
            return 0;
        }

        $billableQuantity = $actualQuantity;

        // Apply billing multiplier
        if ($this->billing_multiplier) {
            $billableQuantity *= $this->billing_multiplier;
        }

        // Apply minimum chargeable
        if ($this->minimum_chargeable && $billableQuantity < $this->minimum_chargeable) {
            $billableQuantity = $this->minimum_chargeable;
        }

        // Apply rounding method
        switch ($this->rounding_method) {
            case 'up':
                $billableQuantity = ceil($billableQuantity);
                break;
            case 'down':
                $billableQuantity = floor($billableQuantity);
                break;
            case 'nearest':
            default:
                $billableQuantity = round($billableQuantity, $this->decimal_places ?? 2);
                break;
        }

        return $billableQuantity;
    }

    public function isApplicableToCargoType($cargoType)
    {
        if (!$this->applicable_cargo_types || empty($this->applicable_cargo_types)) {
            return true; // If no restrictions, applicable to all
        }

        return in_array($cargoType, $this->applicable_cargo_types);
    }

    public function getStatistics()
    {
        return [
            'total_shipments' => $this->shipments()->count(),
            'total_invoice_lines' => $this->invoiceDetails()->count(),
            'monthly_usage' => $this->shipments()->whereMonth('created_at', now()->month)->count(),
            'average_quantity' => $this->shipments()
                ->whereNotNull('quantity')
                ->avg('quantity'),
            'total_quantity_handled' => $this->shipments()
                ->whereNotNull('quantity')
                ->sum('quantity')
        ];
    }

    public function getCompatibleUnits()
    {
        if (!$this->base_unit) {
            return collect();
        }

        return static::where('base_unit', $this->base_unit)
            ->where('id', '!=', $this->id)
            ->where('is_active', true)
            ->get();
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'quantity_code' => 'required|string|max:20|unique:quantity_types,quantity_code,' . $id,
            'quantity_name' => 'required|string|max:255',
            'quantity_category' => 'required|string|in:Weight,Volume,Count,Dimension,Container,Liquid,Area,Time,Custom',
            'unit_of_measure' => 'required|string|max:100',
            'unit_symbol' => 'nullable|string|max:20',
            'base_unit' => 'nullable|string|max:50',
            'conversion_factor' => 'nullable|numeric|min:0',
            'decimal_places' => 'nullable|integer|min:0|max:10',
            'description' => 'nullable|string',
            'calculation_method' => 'nullable|string',
            'applicable_cargo_types' => 'nullable|array',
            'industry_standards' => 'nullable|array',
            'common_ranges' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'display_format' => 'nullable|string|max:100',
            'reporting_category' => 'nullable|string|max:100',
            'customs_code' => 'nullable|string|max:50',
            'is_weight_based' => 'required|boolean',
            'is_volume_based' => 'required|boolean',
            'is_count_based' => 'required|boolean',
            'is_dimension_based' => 'required|boolean',
            'allows_fractions' => 'required|boolean',
            'requires_dimensions' => 'required|boolean',
            'auto_calculate' => 'required|boolean',
            'is_billable' => 'required|boolean',
            'billing_multiplier' => 'nullable|numeric|min:0',
            'minimum_chargeable' => 'nullable|numeric|min:0',
            'rounding_method' => 'nullable|string|in:up,down,nearest',
            'is_standard' => 'required|boolean',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ];
    }
}
