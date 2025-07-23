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

        'code',
        'port_name',
        'city',
        'state_province',
        'country',
        'country_id',
        'postal_code',
        'address',
        'port_type',
        'latitude',
        'longitude',
        'contact_person',
        'contact_phone',
        'contact_email',
        'website',
        'operating_hours',
        'time_zone',
        'facilities',
        'services',
        'max_capacity',
        'total_berths',
        'max_vessel_size',
        'draft_depth',
        'major_port',
        'customs_available',
        'quarantine_available',
        'pilotage_compulsory',
        'port_authority',
        'handling_equipment',
        'storage_capacity',
        'rail_connection',
        'road_connection',
        'status'
    ];

    protected $casts = [
        'facilities' => 'array',
        'services' => 'array',
        'handling_equipment' => 'array',
        'major_port' => 'boolean',
        'customs_available' => 'boolean',
        'quarantine_available' => 'boolean',
        'pilotage_compulsory' => 'boolean',
        'rail_connection' => 'boolean',
        'road_connection' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'max_capacity' => 'integer',
        'total_berths' => 'integer',
        'max_vessel_size' => 'decimal:2',
        'draft_depth' => 'decimal:2',
        'storage_capacity' => 'integer'
    ];

    // 🔥 ADD: Accessor to support legacy 'code' queries
    public function getCodeAttribute()
    {
        return $this->port_code;
    }


    // 🔥 ADD: Mutator to keep code and port_code in sync
    public function setCodeAttribute($value)
    {

        $this->attributes['port_code'] = $value;
    }

    public function setPortCodeAttribute($value)
    {
        $this->attributes['port_code'] = $value;
    }

    // 🔥 ADD: Support for finding by 'code' 
    public function scopeWhereCode($query, $code)
    {
        return $query->where('port_code', $code);
    }

    // Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_port_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_port_id');
    }

    public function shippingAgencies()
    {
        return $this->belongsToMany(ShippingAgency::class, 'port_agencies')
            ->withPivot(['services_offered', 'contact_person', 'local_phone'])
            ->withTimestamps();
    }



    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('port_type', $type);
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeMajorPorts($query)
    {
        return $query->where('major_port', true);
    }

    public function scopeWithCustoms($query)
    {
        return $query->where('customs_available', true);
    }

    public function scopeWithQuarantine($query)
    {
        return $query->where('quarantine_available', true);
    }

    // Accessors
    public function getFormattedAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->state_province) $address .= ', ' . $this->state_province;
        if ($this->country) $address .= ', ' . $this->country;
        if ($this->postal_code) $address .= ' ' . $this->postal_code;

        return $address ?: $this->city . ', ' . $this->country;
    }

    public function getContactInfoAttribute()
    {
        $contact = [];
        if ($this->contact_phone) $contact[] = "Phone: {$this->contact_phone}";
        if ($this->contact_email) $contact[] = "Email: {$this->contact_email}";
        if ($this->website) $contact[] = "Web: {$this->website}";

        return implode(' | ', $contact);
    }

    public function getTypeDisplayAttribute()
    {
        $types = [
            'Seaport' => '🚢 Seaport',
            'Airport' => '✈️ Airport',
            'Dry Port' => '🏭 Dry Port',
            'Container Terminal' => '📦 Container Terminal',
            'Bulk Terminal' => '⚖️ Bulk Terminal',
            'Oil Terminal' => '🛢️ Oil Terminal',
            'Ferry Terminal' => '⛴️ Ferry Terminal',
            'Fishing Port' => '🎣 Fishing Port',
            'Marina' => '⛵ Marina'
        ];

        return $types[$this->port_type] ?? $this->port_type;
    }

    public function getFacilitiesListAttribute()
    {
        if (!$this->facilities || !is_array($this->facilities)) {
            return 'Basic port facilities';
        }

        return implode(', ', $this->facilities);
    }

    public function getServicesListAttribute()
    {
        if (!$this->services || !is_array($this->services)) {
            return 'Standard port services';
        }

        return implode(', ', $this->services);
    }

    public function getTotalShipmentsAttribute()
    {
        return $this->originShipments()->count() + $this->destinationShipments()->count();
    }

    public function getCapacityDisplayAttribute()
    {
        if (!$this->max_capacity) {
            return 'Capacity not specified';
        }

        return number_format($this->max_capacity) . ' TEU';
    }

    public function getDraftDisplayAttribute()
    {
        if (!$this->draft_depth) {
            return 'Draft not specified';
        }

        return $this->draft_depth . ' meters';
    }

    public function portOperations()
    {
        return $this->hasMany(PortOperation::class);
    }

    // Methods
    public function getStatistics()
    {
        return [
            'total_shipments' => $this->total_shipments,
            'origin_shipments' => $this->originShipments()->count(),
            'destination_shipments' => $this->destinationShipments()->count(),
            'active_shipments' => $this->getActiveShipments(),
            'monthly_volume' => $this->getMonthlyVolume(),
            'utilization_rate' => $this->getUtilizationRate(),
            'avg_handling_time' => $this->getAverageHandlingTime(),
            'active_operations' => $this->portOperations()->active()->count(),
            'completed_operations_today' => $this->portOperations()->completed()->whereDate('operation_end_time', today())->count(),
            'scheduled_operations' => $this->portOperations()->where('status', 'Scheduled')->count()
        ];
    }

    public function getActiveShipments()
    {
        return $this->originShipments()
            ->whereIn('status', ['Pending', 'In Transit', 'At Port'])
            ->count() +
            $this->destinationShipments()
            ->whereIn('status', ['Pending', 'In Transit', 'At Port'])
            ->count();
    }

    public function getMonthlyVolume()
    {
        return $this->originShipments()
            ->whereMonth('created_at', now()->month)
            ->count() +
            $this->destinationShipments()
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function hasCoordinates()
    {
        return $this->latitude && $this->longitude;
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->hasCoordinates()) return null;

        // Haversine formula for distance calculation
        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function hasFacility($facility)
    {
        if (!$this->facilities) return false;
        return in_array($facility, $this->facilities);
    }

    public function hasService($service)
    {
        if (!$this->services) return false;
        return in_array($service, $this->services);
    }

    public function isOperational()
    {
        return $this->status === 'Active';
    }

    public function getCurrentCapacity()
    {
        // Calculate current capacity based on active operations and shipments
        $activeOperations = $this->portOperations()->active()->count();
        $activeShipments = $this->getActiveShipments();
        $totalActive = $activeOperations + $activeShipments;

        return $this->max_capacity ? max(0, $this->max_capacity - $totalActive) : null;
    }

    public function getUtilizationRate()
    {
        if (!$this->max_capacity) return 0;

        $activeOperations = $this->portOperations()->active()->count();
        $activeShipments = $this->getActiveShipments();
        $totalActive = $activeOperations + $activeShipments;

        return round(($totalActive / $this->max_capacity) * 100, 2);
    }

    public function getAvailableBerths()
    {
        if (!$this->total_berths) return null;

        // Count operations currently using berths
        $occupiedBerths = $this->portOperations()
            ->active()
            ->whereNotNull('berth_number')
            ->distinct('berth_number')
            ->count();

        return max(0, $this->total_berths - $occupiedBerths);
    }

    public function getTodaysOperations()
    {
        return $this->portOperations()
            ->whereDate('scheduled_arrival', today())
            ->orderBy('scheduled_arrival')
            ->get();
    }

    public function getUpcomingOperations($days = 7)
    {
        return $this->portOperations()
            ->whereBetween('scheduled_arrival', [now(), now()->addDays($days)])
            ->orderBy('scheduled_arrival')
            ->get();
    }

    public function getOperationsByStatus($status)
    {
        return $this->portOperations()->where('status', $status)->get();
    }

    public function hasAvailableBerth()
    {
        return $this->getAvailableBerths() > 0;
    }

    public function getNextAvailableBerthTime()
    {
        if ($this->hasAvailableBerth()) {
            return now()->addHours(1); // Standard arrival processing time
        }

        // Find the earliest departure time of current operations
        $nextDeparture = $this->portOperations()
            ->active()
            ->whereNotNull('scheduled_departure')
            ->orderBy('scheduled_departure')
            ->first();

        if ($nextDeparture) {
            return $nextDeparture->scheduled_departure->addHours(1);
        }

        // Fallback based on congestion level
        $congestion = $this->getCongestionLevel();

        switch ($congestion) {
            case 'High':
                return now()->addHours(48);
            case 'Medium':
                return now()->addHours(12);
            default:
                return now()->addHours(2);
        }
    }

    public function getCongestionLevel()
    {
        $utilizationRate = $this->getUtilizationRate();

        if ($utilizationRate >= 90) return 'High';
        if ($utilizationRate >= 70) return 'Medium';
        return 'Low';
    }

    public function getOperationalEfficiency()
    {
        $completedOperations = $this->portOperations()
            ->completed()
            ->whereMonth('operation_end_time', now()->month)
            ->get();

        if ($completedOperations->isEmpty()) {
            return [
                'avg_turnaround_time' => 0,
                'on_time_percentage' => 0,
                'delayed_operations' => 0,
                'total_operations' => 0
            ];
        }

        $totalTurnaroundTime = 0;
        $onTimeOperations = 0;
        $delayedOperations = 0;

        foreach ($completedOperations as $operation) {
            // Calculate turnaround time
            if ($operation->actual_arrival && $operation->actual_departure) {
                $turnaroundHours = $operation->actual_arrival->diffInHours($operation->actual_departure);
                $totalTurnaroundTime += $turnaroundHours;
            }

            // Check if on time
            if ($operation->scheduled_departure && $operation->actual_departure) {
                if ($operation->actual_departure <= $operation->scheduled_departure) {
                    $onTimeOperations++;
                } else {
                    $delayedOperations++;
                }
            }
        }

        $totalOperations = $completedOperations->count();

        return [
            'avg_turnaround_time' => round($totalTurnaroundTime / $totalOperations, 2),
            'on_time_percentage' => round(($onTimeOperations / $totalOperations) * 100, 2),
            'delayed_operations' => $delayedOperations,
            'total_operations' => $totalOperations
        ];
    }

    public function getAverageHandlingTime()
    {
        // Calculate average handling time from completed shipments
        $completedShipments = $this->originShipments()
            ->where('status', 'Delivered')
            ->whereNotNull('departure_date')
            ->whereNotNull('arrival_date')
            ->get();

        if ($completedShipments->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($completedShipments as $shipment) {
            if ($shipment->departure_date && $shipment->arrival_date) {
                $totalHours += \Carbon\Carbon::parse($shipment->arrival_date)
                    ->diffInHours(\Carbon\Carbon::parse($shipment->departure_date));
            }
        }

        return round($totalHours / $completedShipments->count(), 2);
    }

    public function getWeatherStatus()
    {
        // This would integrate with weather API in real implementation
        return 'Normal'; // Placeholder
    }

    public function getSpecialNotices()
    {
        $notices = [];

        if ($this->pilotage_compulsory) {
            $notices[] = 'Pilotage is compulsory';
        }

        if ($this->getCongestionLevel() === 'High') {
            $notices[] = 'High congestion - expect delays';
        }

        return $notices;
    }

    public function getPerformanceStatistics($startDate, $endDate)
    {
        return [
            'total_vessels' => $this->getVesselCount($startDate, $endDate),
            'total_cargo' => $this->getCargoVolume($startDate, $endDate),
            'average_turnaround' => $this->getAverageTurnaroundTime($startDate, $endDate),
            'berth_productivity' => $this->getBerthProductivity($startDate, $endDate)
        ];
    }

    public function getCapacityUtilization($startDate, $endDate)
    {
        // Implementation would depend on detailed operational data
        return [
            'average_utilization' => 75.5,
            'peak_utilization' => 95.2,
            'low_utilization' => 45.8
        ];
    }

    public function getTopShippingLines($startDate, $endDate, $limit = 5)
    {
        // This would aggregate from shipments data
        return collect([
            ['name' => 'Maersk Line', 'shipments' => 45, 'cargo_volume' => 12500],
            ['name' => 'MSC', 'shipments' => 38, 'cargo_volume' => 11200],
            ['name' => 'CMA CGM', 'shipments' => 32, 'cargo_volume' => 9800],
            ['name' => 'COSCO', 'shipments' => 28, 'cargo_volume' => 8500],
            ['name' => 'Hapag-Lloyd', 'shipments' => 25, 'cargo_volume' => 7200]
        ])->take($limit);
    }

    public function getCargoBreakdown($startDate, $endDate)
    {
        return [
            'containers' => ['count' => 1250, 'percentage' => 65],
            'bulk_cargo' => ['count' => 380, 'percentage' => 20],
            'break_bulk' => ['count' => 190, 'percentage' => 10],
            'liquid_bulk' => ['count' => 95, 'percentage' => 5]
        ];
    }

    public function getEfficiencyMetrics($startDate, $endDate)
    {
        return [
            'berth_occupancy_rate' => 78.5,
            'cargo_handling_rate' => 125.8, // TEU per hour
            'vessel_turnaround_time' => 18.5, // hours
            'waiting_time' => 2.3 // hours
        ];
    }

    private function getVesselCount($startDate, $endDate)
    {
        return $this->originShipments()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count() +
            $this->destinationShipments()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    private function getCargoVolume($startDate, $endDate)
    {
        // This would sum actual cargo volumes from shipments
        return 25000; // Placeholder in TEU
    }

    private function getAverageTurnaroundTime($startDate, $endDate)
    {
        return 18.5; // Placeholder in hours
    }

    private function getBerthProductivity($startDate, $endDate)
    {
        return 125.8; // Placeholder in TEU per hour
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'port_code' => 'required|string|max:10|unique:ports,port_code,' . $id,
            'port_name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state_province' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'country_id' => 'nullable|exists:countries,id',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'port_type' => 'required|string|in:Seaport,Airport,Dry Port,Container Terminal,Bulk Terminal,Oil Terminal,Ferry Terminal,Fishing Port,Marina',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'time_zone' => 'nullable|string|max:50',
            'facilities' => 'nullable|array',
            'services' => 'nullable|array',
            'max_capacity' => 'nullable|integer|min:0|max:999999',
            'total_berths' => 'nullable|integer|min:0|max:999',
            'max_vessel_size' => 'nullable|numeric|min:0|max:999999.99',
            'draft_depth' => 'nullable|numeric|min:0|max:999.99',
            'major_port' => 'required|boolean',
            'customs_available' => 'required|boolean',
            'quarantine_available' => 'required|boolean',
            'pilotage_compulsory' => 'required|boolean',
            'port_authority' => 'nullable|string|max:255',
            'handling_equipment' => 'nullable|array',
            'storage_capacity' => 'nullable|integer|min:0|max:999999',
            'rail_connection' => 'required|boolean',
            'road_connection' => 'required|boolean',
            'status' => 'required|string|in:Active,Inactive,Under Construction,Closed'
        ];
    }

    public static function getPortTypes()
    {
        return [
            'Seaport' => 'Seaport',
            'Airport' => 'Airport',
            'Dry Port' => 'Dry Port',
            'Container Terminal' => 'Container Terminal',
            'Bulk Terminal' => 'Bulk Terminal',
            'Oil Terminal' => 'Oil Terminal',
            'Ferry Terminal' => 'Ferry Terminal',
            'Fishing Port' => 'Fishing Port',
            'Marina' => 'Marina'
        ];
    }

    public static function getFacilitiesList()
    {
        return [
            'Container Handling',
            'Bulk Cargo Handling',
            'Break Bulk Handling',
            'Liquid Bulk Handling',
            'Ro-Ro Facilities',
            'Cold Storage',
            'Dry Storage',
            'Customs Office',
            'Port Security',
            'Pilotage Service',
            'Tugboat Service',
            'Bunkering',
            'Fresh Water Supply',
            'Waste Disposal',
            'Ship Repair',
            'Cargo Inspection',
            'Quarantine Station',
            'Free Trade Zone'
        ];
    }

    public static function getServicesList()
    {
        return [
            'Stevedoring',
            'Cargo Handling',
            'Storage & Warehousing',
            'Container Services',
            'Customs Clearance',
            'Port Agency',
            'Ship Chandling',
            'Bunker Supply',
            'Technical Services',
            'Logistics Services',
            'Transportation',
            'Documentation',
            'Insurance Services',
            'Banking Services',
            'Communication Services',
            'Emergency Services'
        ];
    }

    public static function getHandlingEquipment()
    {
        return [
            'Container Cranes',
            'Mobile Cranes',
            'Reach Stackers',
            'Forklifts',
            'Terminal Tractors',
            'Conveyor Systems',
            'Bulk Loaders',
            'Ship Loaders',
            'Pipelines',
            'Storage Tanks',
            'Weighbridges',
            'Rail Cranes',
            'Floating Cranes'
        ];
    }
}
