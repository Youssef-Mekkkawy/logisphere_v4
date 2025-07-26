<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShipmentType;

class ShipmentTypeSeeder extends Seeder
{
    public function run()
    {
        $shipmentTypes = [
            // Ocean Freight - FCL Container
            [
                'type_code' => 'OCN-FCL-STD',
                'type_name' => 'Ocean Freight - FCL Standard',
                'category' => 'Ocean Freight',
                'subcategory' => 'Full Container Load',
                'description' => 'Standard ocean freight service for full container loads',
                'detailed_description' => 'Full container load ocean freight service with standard transit times and competitive rates for general cargo.',
                'cargo_type' => 'General Cargo',
                'container_types' => ['20GP', '40GP', '40HC'],
                'transit_mode' => 'Sea',
                'handling_requirements' => ['Standard Loading', 'Container Stuffing'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Bill of Lading', 'Certificate of Origin'],
                'estimated_transit_days' => 25,
                'min_transit_days' => 20,
                'max_transit_days' => 35,
                'cost_factor' => 1.0,
                'base_rate_multiplier' => 1.000,
                'priority_level' => 'Standard',
                'service_level' => 'Standard',
                'customs_complexity' => 'Standard',
                'tracking_level' => 'Standard',
                'consolidation_allowed' => false,
                'door_to_door_available' => true,
                'standard_service_available' => true,
                'status' => 'Active'
            ],

            // Ocean Freight - LCL
            [
                'type_code' => 'OCN-LCL-STD',
                'type_name' => 'Ocean Freight - LCL Standard',
                'category' => 'Ocean Freight',
                'subcategory' => 'Less Container Load',
                'description' => 'Standard ocean freight service for consolidated cargo',
                'detailed_description' => 'Less than container load service ideal for smaller shipments that can be consolidated with other cargo.',
                'cargo_type' => 'General Cargo',
                'container_types' => ['20GP', '40GP'],
                'transit_mode' => 'Sea',
                'handling_requirements' => ['Careful Handling', 'Consolidation'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Bill of Lading'],
                'estimated_transit_days' => 30,
                'min_transit_days' => 25,
                'max_transit_days' => 40,
                'cost_factor' => 1.2,
                'base_rate_multiplier' => 1.200,
                'priority_level' => 'Standard',
                'service_level' => 'Standard',
                'customs_complexity' => 'Standard',
                'tracking_level' => 'Standard',
                'consolidation_allowed' => true,
                'partial_loads_allowed' => true,
                'door_to_door_available' => true,
                'standard_service_available' => true,
                'status' => 'Active'
            ],

            // Air Freight - Express
            [
                'type_code' => 'AIR-EXP-PRI',
                'type_name' => 'Air Freight - Express Priority',
                'category' => 'Air Freight',
                'subcategory' => 'Express Service',
                'description' => 'High-priority air freight for urgent shipments',
                'detailed_description' => 'Premium air freight service with express handling and priority booking for time-sensitive cargo.',
                'cargo_type' => 'General Cargo',
                'transit_mode' => 'Air',
                'handling_requirements' => ['Priority Handling', 'Express Processing'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Air Waybill', 'Export License'],
                'estimated_transit_days' => 3,
                'min_transit_days' => 1,
                'max_transit_days' => 5,
                'cost_factor' => 3.5,
                'base_rate_multiplier' => 3.500,
                'priority_level' => 'High',
                'service_level' => 'Express',
                'customs_complexity' => 'Standard',
                'tracking_level' => 'Real-time',
                'door_to_door_available' => true,
                'express_service_available' => true,
                'status' => 'Active'
            ],

            // Air Freight - Economy
            [
                'type_code' => 'AIR-ECO-STD',
                'type_name' => 'Air Freight - Economy Standard',
                'category' => 'Air Freight',
                'subcategory' => 'Economy Service',
                'description' => 'Cost-effective air freight for non-urgent shipments',
                'detailed_description' => 'Economy air freight service offering competitive rates with slightly longer transit times.',
                'cargo_type' => 'General Cargo',
                'transit_mode' => 'Air',
                'handling_requirements' => ['Standard Loading'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Air Waybill'],
                'estimated_transit_days' => 7,
                'min_transit_days' => 5,
                'max_transit_days' => 10,
                'cost_factor' => 2.0,
                'base_rate_multiplier' => 2.000,
                'priority_level' => 'Standard',
                'service_level' => 'Economy',
                'customs_complexity' => 'Standard',
                'tracking_level' => 'Standard',
                'consolidation_allowed' => true,
                'economy_service_available' => true,
                'status' => 'Active'
            ],

            // Refrigerated Cargo
            [
                'type_code' => 'OCN-REF-TEMP',
                'type_name' => 'Ocean Freight - Refrigerated Cargo',
                'category' => 'Ocean Freight',
                'subcategory' => 'Temperature Controlled',
                'description' => 'Temperature-controlled ocean freight for perishables',
                'detailed_description' => 'Specialized refrigerated container service for temperature-sensitive cargo with continuous monitoring.',
                'cargo_type' => 'Refrigerated',
                'container_types' => ['20RF', '40RF'],
                'transit_mode' => 'Sea',
                'handling_requirements' => ['Temperature Control', 'Continuous Monitoring', 'Special Equipment'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Bill of Lading', 'Temperature Certificate', 'Health Certificate'],
                'estimated_transit_days' => 28,
                'min_transit_days' => 22,
                'max_transit_days' => 35,
                'temperature_controlled' => true,
                'cost_factor' => 1.8,
                'base_rate_multiplier' => 1.800,
                'priority_level' => 'High',
                'service_level' => 'Premium',
                'customs_complexity' => 'Complex',
                'tracking_level' => 'Advanced',
                'equipment_needed' => ['Refrigerated Container', 'Temperature Monitoring', 'Power Supply'],
                'inspection_required' => true,
                'status' => 'Active'
            ],

            // Dangerous Goods
            [
                'type_code' => 'OCN-DG-HAZ',
                'type_name' => 'Ocean Freight - Dangerous Goods',
                'category' => 'Special Handling',
                'subcategory' => 'Hazardous Materials',
                'description' => 'Specialized handling for dangerous goods and chemicals',
                'detailed_description' => 'Expert handling of dangerous goods with full compliance to IMDG regulations and safety protocols.',
                'cargo_type' => 'Dangerous Goods',
                'container_types' => ['20GP', '40GP'],
                'transit_mode' => 'Sea',
                'handling_requirements' => ['Hazmat Procedures', 'Special Equipment', 'Certified Personnel'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Bill of Lading', 'Dangerous Goods Declaration', 'Safety Data Sheets'],
                'estimated_transit_days' => 30,
                'min_transit_days' => 25,
                'max_transit_days' => 40,
                'hazardous_material' => true,
                'cost_factor' => 2.2,
                'base_rate_multiplier' => 2.200,
                'priority_level' => 'High',
                'service_level' => 'Premium',
                'customs_complexity' => 'Very Complex',
                'tracking_level' => 'Advanced',
                'insurance_required' => true,
                'inspection_required' => true,
                'permit_required' => true,
                'certification_needed' => ['IMDG Certificate', 'Hazmat License'],
                'status' => 'Active'
            ],

            // Project Cargo
            [
                'type_code' => 'OCN-PRJ-OOG',
                'type_name' => 'Ocean Freight - Project Cargo (OOG)',
                'category' => 'Project Cargo',
                'subcategory' => 'Out of Gauge',
                'description' => 'Specialized handling for oversized and heavy machinery',
                'detailed_description' => 'Expert project cargo service for oversized, overweight, and out-of-gauge shipments requiring special handling.',
                'cargo_type' => 'Heavy Machinery',
                'container_types' => ['20FR', '40FR', '20OT', '40OT'],
                'transit_mode' => 'Sea',
                'handling_requirements' => ['Heavy Lift', 'Special Equipment', 'Crane Required', 'Oversized Handling'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Bill of Lading', 'Weight Certificate', 'Dimension Certificate'],
                'estimated_transit_days' => 35,
                'min_transit_days' => 30,
                'max_transit_days' => 45,
                'oversized_cargo' => true,
                'cost_factor' => 2.8,
                'base_rate_multiplier' => 2.800,
                'priority_level' => 'High',
                'service_level' => 'Premium',
                'customs_complexity' => 'Complex',
                'tracking_level' => 'Advanced',
                'equipment_needed' => ['Heavy Lift Equipment', 'Specialized Trailers', 'Cranes'],
                'booking_lead_time' => 14,
                'consolidation_allowed' => false,
                'status' => 'Active'
            ],

            // Land Transport - Trucking
            [
                'type_code' => 'LND-TRK-STD',
                'type_name' => 'Land Transport - Standard Trucking',
                'category' => 'Land Transport',
                'subcategory' => 'Road Freight',
                'description' => 'Standard overland trucking service',
                'detailed_description' => 'Reliable overland trucking service for domestic and cross-border transportation with flexible scheduling.',
                'cargo_type' => 'General Cargo',
                'transit_mode' => 'Road',
                'handling_requirements' => ['Standard Loading', 'Secure Tie-down'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Waybill', 'Transit Documents'],
                'estimated_transit_days' => 5,
                'min_transit_days' => 3,
                'max_transit_days' => 10,
                'cost_factor' => 1.5,
                'base_rate_multiplier' => 1.500,
                'priority_level' => 'Standard',
                'service_level' => 'Standard',
                'customs_complexity' => 'Simple',
                'tracking_level' => 'Standard',
                'door_to_door_available' => true,
                'partial_loads_allowed' => true,
                'return_loads_allowed' => true,
                'status' => 'Active'
            ],

            // Rail Transport
            [
                'type_code' => 'RAL-FRT-STD',
                'type_name' => 'Rail Freight - Standard Service',
                'category' => 'Rail Transport',
                'subcategory' => 'Rail Freight',
                'description' => 'Eco-friendly rail freight for bulk cargo',
                'detailed_description' => 'Environmentally friendly rail freight service ideal for bulk commodities and long-distance transport.',
                'cargo_type' => 'Bulk Cargo',
                'transit_mode' => 'Rail',
                'handling_requirements' => ['Bulk Loading', 'Rail Car Preparation'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Rail Waybill'],
                'estimated_transit_days' => 12,
                'min_transit_days' => 8,
                'max_transit_days' => 18,
                'cost_factor' => 0.8,
                'base_rate_multiplier' => 0.800,
                'priority_level' => 'Standard',
                'service_level' => 'Economy',
                'customs_complexity' => 'Standard',
                'tracking_level' => 'Basic',
                'consolidation_allowed' => true,
                'economy_service_available' => true,
                'status' => 'Active'
            ],

            // Electronics - High Value
            [
                'type_code' => 'AIR-ELE-HVL',
                'type_name' => 'Air Freight - Electronics (High Value)',
                'category' => 'Air Freight',
                'subcategory' => 'High Value Cargo',
                'description' => 'Secure air freight for high-value electronics',
                'detailed_description' => 'Premium air freight service with enhanced security and handling for high-value electronic equipment.',
                'cargo_type' => 'Electronics',
                'transit_mode' => 'Air',
                'handling_requirements' => ['Careful Handling', 'Security Escort', 'Anti-static Procedures'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Air Waybill', 'Insurance Certificate', 'Quality Certificate'],
                'estimated_transit_days' => 4,
                'min_transit_days' => 2,
                'max_transit_days' => 6,
                'high_value_cargo' => true,
                'fragile_cargo' => true,
                'cost_factor' => 4.0,
                'base_rate_multiplier' => 4.000,
                'priority_level' => 'High',
                'service_level' => 'Premium',
                'customs_complexity' => 'Complex',
                'tracking_level' => 'Real-time',
                'insurance_required' => true,
                'requires_escort' => true,
                'packaging_requirements' => ['Anti-static packaging', 'Shock-proof containers'],
                'status' => 'Active'
            ],

            // Pharmaceuticals
            [
                'type_code' => 'AIR-PHA-GMP',
                'type_name' => 'Air Freight - Pharmaceuticals (GDP)',
                'category' => 'Air Freight',
                'subcategory' => 'Good Distribution Practice',
                'description' => 'GDP-compliant air freight for pharmaceutical products',
                'detailed_description' => 'Specialized pharmaceutical logistics with GDP compliance, temperature monitoring, and chain of custody.',
                'cargo_type' => 'Pharmaceuticals',
                'transit_mode' => 'Air',
                'handling_requirements' => ['Temperature Control', 'GDP Compliance', 'Chain of Custody'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Air Waybill', 'GDP Certificate', 'Temperature Log'],
                'estimated_transit_days' => 3,
                'min_transit_days' => 1,
                'max_transit_days' => 5,
                'temperature_controlled' => true,
                'cost_factor' => 5.0,
                'base_rate_multiplier' => 5.000,
                'priority_level' => 'Critical',
                'service_level' => 'Premium',
                'customs_complexity' => 'Very Complex',
                'tracking_level' => 'Real-time',
                'insurance_required' => true,
                'inspection_required' => true,
                'quarantine_required' => true,
                'certification_needed' => ['GDP Certificate', 'Cold Chain Certificate'],
                'equipment_needed' => ['Temperature Monitoring', 'Validation Equipment'],
                'status' => 'Active'
            ],

            // Multimodal Service
            [
                'type_code' => 'MUL-STD-ECO',
                'type_name' => 'Multimodal - Standard Economy',
                'category' => 'Multimodal',
                'subcategory' => 'Sea-Land Combination',
                'description' => 'Cost-effective multimodal sea-land service',
                'detailed_description' => 'Efficient multimodal service combining sea and land transport for optimal cost and transit time balance.',
                'cargo_type' => 'General Cargo',
                'container_types' => ['20GP', '40GP', '40HC'],
                'transit_mode' => 'Multimodal',
                'handling_requirements' => ['Standard Loading', 'Intermodal Handling'],
                'documentation_required' => ['Commercial Invoice', 'Packing List', 'Multimodal Bill of Lading', 'Transit Documents'],
                'estimated_transit_days' => 18,
                'min_transit_days' => 15,
                'max_transit_days' => 25,
                'cost_factor' => 1.3,
                'base_rate_multiplier' => 1.300,
                'priority_level' => 'Standard',
                'service_level' => 'Economy',
                'customs_complexity' => 'Standard',
                'tracking_level' => 'Standard',
                'door_to_door_available' => true,
                'transshipment_allowed' => true,
                'economy_service_available' => true,
                'status' => 'Active'
            ],

            // Inactive Test Type
            [
                'type_code' => 'TST-INA-XXX',
                'type_name' => 'Test Inactive Shipment Type',
                'category' => 'Other',
                'description' => 'Test shipment type for system testing',
                'cargo_type' => 'General Cargo',
                'transit_mode' => 'Sea',
                'estimated_transit_days' => 30,
                'cost_factor' => 1.0,
                'base_rate_multiplier' => 1.000,
                'priority_level' => 'Low',
                'service_level' => 'Basic',
                'status' => 'Inactive',
                'notes' => 'This is a test shipment type for development purposes'
            ]
        ];

        foreach ($shipmentTypes as $typeData) {
            ShipmentType::create($typeData);
            $this->command->info("Created shipment type: {$typeData['type_name']}");
        }

        $this->command->info('Shipment types seeded successfully!');
    }
}
