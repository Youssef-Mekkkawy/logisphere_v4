<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Port extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'port_code',
        'port_name',
        'port_type',
        'country',
        'city',
        'state_province',
        'latitude',
        'longitude',
        'time_zone',
        'port_authority',
        'contact_email',
        'contact_phone',
        'website',
        'facilities',
        'services',
        'terminal_operators',
        'max_vessel_size',
        'max_draft_meters',
        'berth_count',
        'storage_capacity',
        'crane_capacity',
        'working_hours',
        'operational_status',
        'customs_available',
        'quarantine_available',
        'bunker_available',
        'fresh_water_available',
        'rail_connection',
        'road_connection',
        'airport_distance_km',
        'port_charges',
        'pilot_required',
        'tugs_available',
        'anchorage_available',
        'security_level',
        'is_major_port',
        'is_container_port',
        'is_bulk_port',
        'is_cruise_port',
        'handling_equipment',
        'cargo_types_handled',
        'restrictions',
        'weather_conditions',
        'annual_throughput',
        'established_year',
        'is_active',
        'sort_order',
        'notes',
        'code'
    ];

    protected $casts = [
        'facilities' => 'array',
        'services' => 'array',
        'terminal_operators' => 'array',
        'handling_equipment' => 'array',
        'cargo_types_handled' => 'array',
        'restrictions' => 'array',
        'port_charges' => 'array',
        'working_hours' => 'array',
        'weather_conditions' => 'array',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'max_draft_meters' => 'decimal:2',
        'storage_capacity' => 'decimal:2',
        'crane_capacity' => 'decimal:2',
        'airport_distance_km' => 'decimal:2',
        'annual_throughput' => 'decimal:2',
        'customs_available' => 'boolean',
        'quarantine_available' => 'boolean',
        'bunker_available' => 'boolean',
        'fresh_water_available' => 'boolean',
        'rail_connection' => 'boolean',
        'road_connection' => 'boolean',
        'pilot_required' => 'boolean',
        'tugs_available' => 'boolean',
        'anchorage_available' => 'boolean',
        'is_major_port' => 'boolean',
        'is_container_port' => 'boolean',
        'is_bulk_port' => 'boolean',
        'is_cruise_port' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_port_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_port_id');
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'origin_port_id')
            ->orWhere('destination_port_id', $this->id);
    }

    public function trackingEvents()
    {
        return $this->hasMany(TrackingEvent::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByPortType($query, $type)
    {
        return $query->where('port_type', $type);
    }

    public function scopeMajorPorts($query)
    {
        return $query->where('is_major_port', true);
    }

    public function scopeContainerPorts($query)
    {
        return $query->where('is_container_port', true);
    }

    public function scopeBulkPorts($query)
    {
        return $query->where('is_bulk_port', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('port_name');
    }

    public function scopeNearLocation($query, $latitude, $longitude, $radiusKm = 50)
    {
        return $query->selectRaw(
            '*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
            [$latitude, $longitude, $latitude]
        )
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance');
    }

    // Accessors
    public function getStatusAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getFullNameAttribute()
    {
        return "{$this->port_name} ({$this->port_code})";
    }

    public function getLocationAttribute()
    {
        $location = $this->city;
        if ($this->state_province) {
            $location .= ', ' . $this->state_province;
        }
        $location .= ', ' . $this->country;
        return $location;
    }

    public function getPortTypeDisplayAttribute()
    {
        $types = [
            'Seaport' => '🚢 Seaport',
            'River Port' => '🏞️ River Port',
            'Lake Port' => '🏔️ Lake Port',
            'Inland Port' => '🏭 Inland Port',
            'Container Terminal' => '📦 Container Terminal',
            'Bulk Terminal' => '⚡ Bulk Terminal',
            'Multi-Purpose' => '🔄 Multi-Purpose',
            'Fishing Port' => '🎣 Fishing Port',
            'Naval Base' => '⚓ Naval Base',
            'Ferry Terminal' => '🚤 Ferry Terminal'
        ];

        return $types[$this->port_type] ?? $this->port_type;
    }

    public function getOperationalStatusDisplayAttribute()
    {
        $statuses = [
            'Fully Operational' => '✅ Fully Operational',
            'Limited Operations' => '⚠️ Limited Operations',
            'Maintenance' => '🔧 Under Maintenance',
            'Weather Restriction' => '🌊 Weather Restricted',
            'Strike' => '✋ Strike/Industrial Action',
            'Emergency Closure' => '🚫 Emergency Closure',
            'Seasonal Closure' => '❄️ Seasonal Closure'
        ];

        return $statuses[$this->operational_status] ?? $this->operational_status;
    }

    public function getSecurityLevelDisplayAttribute()
    {
        $levels = [
            'ISPS Level 1' => '🟢 ISPS Level 1 (Normal)',
            'ISPS Level 2' => '🟡 ISPS Level 2 (Heightened)',
            'ISPS Level 3' => '🔴 ISPS Level 3 (Exceptional)',
            'Custom Security' => '🔒 Custom Security Protocol'
        ];

        return $levels[$this->security_level] ?? $this->security_level;
    }

    public function getFacilitiesListAttribute()
    {
        if (!$this->facilities || !is_array($this->facilities)) {
            return 'No facilities listed';
        }

        return implode(', ', $this->facilities);
    }

    public function getServicesListAttribute()
    {
        if (!$this->services || !is_array($this->services)) {
            return 'No services listed';
        }

        return implode(', ', $this->services);
    }

    public function getCargoTypesListAttribute()
    {
        if (!$this->cargo_types_handled || !is_array($this->cargo_types_handled)) {
            return 'All cargo types';
        }

        return implode(', ', $this->cargo_types_handled);
    }

    public function getConnectionsAttribute()
    {
        $connections = [];
        if ($this->rail_connection) $connections[] = '🚆 Rail';
        if ($this->road_connection) $connections[] = '🚛 Road';
        if ($this->airport_distance_km && $this->airport_distance_km <= 50) {
            $connections[] = "✈️ Airport ({$this->airport_distance_km}km)";
        }

        return $connections ? implode(', ', $connections) : 'No connections listed';
    }

    public function getAvailableServicesAttribute()
    {
        $services = [];
        if ($this->customs_available) $services[] = '🛃 Customs';
        if ($this->quarantine_available) $services[] = '🏥 Quarantine';
        if ($this->bunker_available) $services[] = '⛽ Bunker';
        if ($this->fresh_water_available) $services[] = '💧 Fresh Water';
        if ($this->pilot_required) $services[] = '👨‍✈️ Pilot Required';
        if ($this->tugs_available) $services[] = '🚢 Tugboats';
        if ($this->anchorage_available) $services[] = '⚓ Anchorage';

        return $services ? implode(', ', $services) : 'Basic services only';
    }

    // Methods
    public function getStatistics()
    {
        return [
            'total_shipments' => $this->originShipments()->count() + $this->destinationShipments()->count(),
            'origin_shipments' => $this->originShipments()->count(),
            'destination_shipments' => $this->destinationShipments()->count(),
            'monthly_shipments' => $this->originShipments()->whereMonth('created_at', now()->month)->count() +
                $this->destinationShipments()->whereMonth('created_at', now()->month)->count(),
            'active_routes' => $this->routes()->count(),
            'vessel_calls_this_month' => $this->trackingEvents()
                ->where('event_type', 'vessel_arrival')
                ->whereMonth('created_at', now()->month)
                ->count()
        ];
    }

    public function calculateDistance($otherPort)
    {
        if (!$this->latitude || !$this->longitude || !$otherPort->latitude || !$otherPort->longitude) {
            return null;
        }

        $earthRadius = 6371; // km

        $latDelta = deg2rad($otherPort->latitude - $this->latitude);
        $lonDelta = deg2rad($otherPort->longitude - $this->longitude);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($this->latitude)) * cos(deg2rad($otherPort->latitude)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    public function canHandleCargoType($cargoType)
    {
        if (!$this->cargo_types_handled || empty($this->cargo_types_handled)) {
            return true; // If no restrictions, assume all types allowed
        }

        return in_array($cargoType, $this->cargo_types_handled);
    }

    public function hasService($service)
    {
        if (!$this->services) return false;
        return in_array($service, $this->services);
    }

    public function hasFacility($facility)
    {
        if (!$this->facilities) return false;
        return in_array($facility, $this->facilities);
    }

    public function isOperational()
    {
        return $this->is_active &&
            in_array($this->operational_status, ['Fully Operational', 'Limited Operations']);
    }

    public function getWeatherStatus()
    {
        if (!$this->weather_conditions) {
            return 'Weather information not available';
        }

        // This would integrate with weather API in production
        return 'Current weather conditions favorable for operations';
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'port_code' => 'required|string|max:10|unique:ports,port_code,' . $id,
            'port_name' => 'required|string|max:255',
            'port_type' => 'required|string|in:Seaport,River Port,Lake Port,Inland Port,Container Terminal,Bulk Terminal,Multi-Purpose,Fishing Port,Naval Base,Ferry Terminal',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state_province' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'time_zone' => 'nullable|string|max:50',
            'port_authority' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'facilities' => 'nullable|array',
            'services' => 'nullable|array',
            'terminal_operators' => 'nullable|array',
            'max_vessel_size' => 'nullable|string|max:50',
            'max_draft_meters' => 'nullable|numeric|min:0|max:50',
            'berth_count' => 'nullable|integer|min:0|max:100',
            'storage_capacity' => 'nullable|numeric|min:0',
            'crane_capacity' => 'nullable|numeric|min:0',
            'working_hours' => 'nullable|array',
            'operational_status' => 'required|string|in:Fully Operational,Limited Operations,Maintenance,Weather Restriction,Strike,Emergency Closure,Seasonal Closure',
            'customs_available' => 'required|boolean',
            'quarantine_available' => 'required|boolean',
            'bunker_available' => 'required|boolean',
            'fresh_water_available' => 'required|boolean',
            'rail_connection' => 'required|boolean',
            'road_connection' => 'required|boolean',
            'airport_distance_km' => 'nullable|numeric|min:0|max:1000',
            'port_charges' => 'nullable|array',
            'pilot_required' => 'required|boolean',
            'tugs_available' => 'required|boolean',
            'anchorage_available' => 'required|boolean',
            'security_level' => 'required|string|in:ISPS Level 1,ISPS Level 2,ISPS Level 3,Custom Security',
            'is_major_port' => 'required|boolean',
            'is_container_port' => 'required|boolean',
            'is_bulk_port' => 'required|boolean',
            'is_cruise_port' => 'required|boolean',
            'handling_equipment' => 'nullable|array',
            'cargo_types_handled' => 'nullable|array',
            'restrictions' => 'nullable|array',
            'weather_conditions' => 'nullable|array',
            'annual_throughput' => 'nullable|numeric|min:0',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ];
    }
}
