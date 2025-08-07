<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Logistics\QuantityType>
 */
class QuantityTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Container', 'Weight', 'Volume', 'Count', 'Area', 'Liquid', 'Length', 'Time'];
        $category = $this->faker->randomElement($categories);

        // Category-specific configurations
        $configs = [
            'Container' => [
                'units' => ['TEU', 'FEU', 'CONT'],
                'measures' => ['Container', 'Container Unit'],
                'symbols' => ['TEU', 'FEU', 'cont'],
                'base_units' => ['TEU', 'container'],
                'conversion_factors' => [1.0, 2.0, 1.0],
                'decimal_places' => [0, 0, 0],
                'is_weight_based' => false,
                'is_volume_based' => true,
                'is_count_based' => true,
                'is_dimension_based' => false
            ],
            'Weight' => [
                'units' => ['KG', 'MT', 'LB', 'TON'],
                'measures' => ['Kilogram', 'Metric Ton', 'Pound', 'Ton'],
                'symbols' => ['kg', 'MT', 'lb', 'ton'],
                'base_units' => ['kg', 'kg', 'kg', 'kg'],
                'conversion_factors' => [1.0, 1000.0, 0.453592, 1000.0],
                'decimal_places' => [3, 3, 2, 3],
                'is_weight_based' => true,
                'is_volume_based' => false,
                'is_count_based' => false,
                'is_dimension_based' => false
            ],
            'Volume' => [
                'units' => ['CBM', 'CFT', 'LTR', 'GAL'],
                'measures' => ['Cubic Meter', 'Cubic Feet', 'Liter', 'Gallon'],
                'symbols' => ['m³', 'ft³', 'L', 'gal'],
                'base_units' => ['cbm', 'cbm', 'liters', 'liters'],
                'conversion_factors' => [1.0, 0.0283168, 1.0, 3.78541],
                'decimal_places' => [3, 2, 2, 2],
                'is_weight_based' => false,
                'is_volume_based' => true,
                'is_count_based' => false,
                'is_dimension_based' => true
            ],
            'Count' => [
                'units' => ['PCS', 'PKG', 'BOX', 'PAL'],
                'measures' => ['Pieces', 'Packages', 'Boxes', 'Pallets'],
                'symbols' => ['pcs', 'pkgs', 'box', 'pal'],
                'base_units' => ['pieces', 'pieces', 'pieces', 'pieces'],
                'conversion_factors' => [1.0, 1.0, 1.0, 1.0],
                'decimal_places' => [0, 0, 0, 0],
                'is_weight_based' => false,
                'is_volume_based' => false,
                'is_count_based' => true,
                'is_dimension_based' => false
            ],
            'Area' => [
                'units' => ['SQM', 'SQFT', 'SQI'],
                'measures' => ['Square Meter', 'Square Feet', 'Square Inch'],
                'symbols' => ['m²', 'ft²', 'in²'],
                'base_units' => ['sqm', 'sqm', 'sqm'],
                'conversion_factors' => [1.0, 0.092903, 0.00064516],
                'decimal_places' => [2, 2, 4],
                'is_weight_based' => false,
                'is_volume_based' => false,
                'is_count_based' => false,
                'is_dimension_based' => true
            ],
            'Liquid' => [
                'units' => ['LTR', 'GAL', 'ML'],
                'measures' => ['Liter', 'Gallon', 'Milliliter'],
                'symbols' => ['L', 'gal', 'ml'],
                'base_units' => ['liters', 'liters', 'liters'],
                'conversion_factors' => [1.0, 3.78541, 0.001],
                'decimal_places' => [2, 2, 0],
                'is_weight_based' => false,
                'is_volume_based' => true,
                'is_count_based' => false,
                'is_dimension_based' => false
            ],
            'Length' => [
                'units' => ['M', 'FT', 'IN', 'CM'],
                'measures' => ['Meter', 'Feet', 'Inch', 'Centimeter'],
                'symbols' => ['m', 'ft', 'in', 'cm'],
                'base_units' => ['meters', 'meters', 'meters', 'meters'],
                'conversion_factors' => [1.0, 0.3048, 0.0254, 0.01],
                'decimal_places' => [2, 2, 3, 1],
                'is_weight_based' => false,
                'is_volume_based' => false,
                'is_count_based' => false,
                'is_dimension_based' => true
            ],
            'Time' => [
                'units' => ['HR', 'DAY', 'MIN'],
                'measures' => ['Hour', 'Day', 'Minute'],
                'symbols' => ['hr', 'day', 'min'],
                'base_units' => ['hours', 'hours', 'hours'],
                'conversion_factors' => [1.0, 24.0, 0.0166667],
                'decimal_places' => [2, 1, 0],
                'is_weight_based' => false,
                'is_volume_based' => false,
                'is_count_based' => false,
                'is_dimension_based' => false
            ]
        ];

        $config = $configs[$category];
        $index = $this->faker->numberBetween(0, count($config['units']) - 1);

        $quantityCode = $config['units'][$index];
        $unitOfMeasure = $config['measures'][$index];
        $unitSymbol = $config['symbols'][$index];
        $baseUnit = $config['base_units'][$index];
        $conversionFactor = $config['conversion_factors'][$index];
        $decimalPlaces = $config['decimal_places'][$index];

        return [
            'quantity_code' => $quantityCode,
            'quantity_name' => $this->faker->words(3, true) . ' ' . $unitOfMeasure,
            'quantity_category' => $category,
            'unit_of_measure' => $unitOfMeasure,
            'unit_symbol' => $unitSymbol,
            'base_unit' => $baseUnit,
            'conversion_factor' => $conversionFactor,
            'decimal_places' => $decimalPlaces,
            'description' => $this->faker->sentence(),
            'calculation_method' => $this->faker->optional()->sentence(),
            'applicable_cargo_types' => $this->faker->randomElements([
                'General Cargo',
                'Containers',
                'Bulk Cargo',
                'Break Bulk',
                'Liquid Bulk',
                'Project Cargo',
                'Heavy Cargo'
            ], $this->faker->numberBetween(1, 3)),
            'industry_standards' => $this->faker->randomElements([
                'ISO 668',
                'SI Units',
                'Imperial Units',
                'Freight Industry'
            ], $this->faker->numberBetween(1, 2)),
            'common_ranges' => [
                'min' => $this->faker->randomFloat(3, 0.001, 1),
                'max' => $this->faker->randomFloat(0, 1000, 999999)
            ],
            'validation_rules' => $this->faker->randomElements([
                'positive_number',
                'required',
                'min_value'
            ], $this->faker->numberBetween(1, 2)),
            'display_format' => sprintf('%%.%df %s', $decimalPlaces, $unitSymbol),
            'reporting_category' => $this->faker->randomElement([
                'Gross Weight',
                'Volume',
                'Item Count',
                'Container Volume',
                'Area'
            ]),
            'is_weight_based' => $config['is_weight_based'],
            'is_volume_based' => $config['is_volume_based'],
            'is_count_based' => $config['is_count_based'],
            'is_dimension_based' => $config['is_dimension_based'],
            'allows_fractions' => $decimalPlaces > 0,
            'requires_dimensions' => $config['is_dimension_based'],
            'auto_calculate' => $this->faker->boolean(20),
            'is_billable' => $this->faker->boolean(80),
            'billing_multiplier' => $this->faker->randomFloat(4, 0.5, 2.0),
            'minimum_chargeable' => $this->faker->randomFloat(6, 0.001, 1.0),
            'rounding_method' => $this->faker->randomElement(['up', 'down', 'nearest']),
            'is_standard' => $this->faker->boolean(70),
            'is_active' => $this->faker->boolean(95),
            'sort_order' => $this->faker->numberBetween(1, 999),
            'notes' => $this->faker->optional()->sentence()
        ];
    }

    /**
     * Indicate that the quantity type is a standard type.
     */
    public function standard(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_standard' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the quantity type is weight-based.
     */
    public function weightBased(): static
    {
        return $this->state(fn(array $attributes) => [
            'quantity_category' => 'Weight',
            'is_weight_based' => true,
            'is_volume_based' => false,
            'is_count_based' => false,
            'is_dimension_based' => false,
            'base_unit' => 'kg',
            'unit_symbol' => 'kg',
            'unit_of_measure' => 'Kilogram'
        ]);
    }

    /**
     * Indicate that the quantity type is volume-based.
     */
    public function volumeBased(): static
    {
        return $this->state(fn(array $attributes) => [
            'quantity_category' => 'Volume',
            'is_weight_based' => false,
            'is_volume_based' => true,
            'is_count_based' => false,
            'is_dimension_based' => true,
            'base_unit' => 'cbm',
            'unit_symbol' => 'm³',
            'unit_of_measure' => 'Cubic Meter'
        ]);
    }

    /**
     * Indicate that the quantity type is container-based.
     */
    public function containerBased(): static
    {
        return $this->state(fn(array $attributes) => [
            'quantity_category' => 'Container',
            'is_weight_based' => false,
            'is_volume_based' => true,
            'is_count_based' => true,
            'is_dimension_based' => false,
            'base_unit' => 'TEU',
            'unit_symbol' => 'TEU',
            'unit_of_measure' => 'Container',
            'allows_fractions' => false,
            'decimal_places' => 0
        ]);
    }

    /**
     * Indicate that the quantity type is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the quantity type is not billable.
     */
    public function notBillable(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_billable' => false,
            'billing_multiplier' => 0,
            'minimum_chargeable' => 0
        ]);
    }
}
