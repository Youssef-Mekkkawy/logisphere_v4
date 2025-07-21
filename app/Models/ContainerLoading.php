<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ContainerLoading extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'loading_point_code',
        'loading_point_name',
        'facility_type',
        'operator_name',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'country',
        'country_id',
        'postal_code',
        'latitude',
        'longitude',
        'operating_hours',
        'container_types_handled',
        'max_containers_per_day',
        'equipment_available',
        'services_offered',
        'storage_rate_per_day',
        'stuffing_rate',
        'destuffing_rate',
        'has_security',
        'has_cctv',
        'requires_appointment',
        'advance_booking_hours',
        'access_instructions',
        'safety_requirements',
        'status',
        'notes'
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'container_types_handled' => 'array',
        'equipment_available' => 'array',
        'services_offered' => 'array',
        'has_security' => 'boolean',
        'has_cctv' => 'boolean',
        'requires_appointment' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'storage_rate_per_day' => 'decimal:2',
        'stuffing_rate' => 'decimal:2',
        'destuffing_rate' => 'decimal:2'
    ];

    // Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'loading_point_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByFacilityType($query, $type)
    {
        return $query->where('facility_type', $type);
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeRequiresAppointment($query)
    {
        return $query->where('requires_appointment', true);
    }

    // Accessors
    public function getFormattedAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->country) $address .= ', ' . $this->country;
        if ($this->postal_code) $address .= ' ' . $this->postal_code;

        return $address;
    }

    public function getContactInfoAttribute()
    {
        $contact = [];
        if ($this->phone) $contact[] = "Phone: {$this->phone}";
        if ($this->email) $contact[] = "Email: {$this->email}";

        return implode(' | ', $contact);
    }

    public function getCapacityStatusAttribute()
    {
        $maxContainers = $this->max_containers_per_day;
        if (!$maxContainers) return 'Unlimited';

        if ($maxContainers >= 100) return 'High Capacity';
        if ($maxContainers >= 50) return 'Medium Capacity';
        return 'Small Capacity';
    }

    public function getOperatingHoursDisplayAttribute()
    {
        if (!$this->operating_hours) return '24/7';

        $hours = $this->operating_hours;
        if (is_array($hours) && isset($hours['monday'])) {
            return $hours['monday'] ?? '24/7';
        }

        return is_string($hours) ? $hours : '24/7';
    }

    public function getServicesListAttribute()
    {
        if (!$this->services_offered || !is_array($this->services_offered)) {
            return 'Basic services';
        }

        return implode(', ', $this->services_offered);
    }

    public function getEquipmentListAttribute()
    {
        if (!$this->equipment_available || !is_array($this->equipment_available)) {
            return 'Standard equipment';
        }

        return implode(', ', $this->equipment_available);
    }

    // Methods
    public function getStatistics()
    {
        return [
            'total_bookings' => $this->shipments()->count(),
            'active_bookings' => $this->shipments()->whereIn('status', ['Pending', 'In Transit'])->count(),
            'completed_bookings' => $this->shipments()->where('status', 'Delivered')->count(),
            'monthly_volume' => $this->shipments()->whereMonth('created_at', now()->month)->count()
        ];
    }

    public function canHandleContainerType($containerType)
    {
        if (!$this->container_types_handled) return true;
        return in_array($containerType, $this->container_types_handled);
    }

    public function isAvailableForBooking($requestedDate = null)
    {
        if ($this->status !== 'Active') return false;

        if ($this->requires_appointment && $requestedDate) {
            $minimumTime = now()->addHours($this->advance_booking_hours);
            return $requestedDate >= $minimumTime;
        }

        return true;
    }

    public function hasCoordinates()
    {
        return $this->latitude && $this->longitude;
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'loading_point_code' => 'required|string|max:20|unique:container_loadings,loading_point_code,' . $id,
            'loading_point_name' => 'required|string|max:255',
            'facility_type' => 'required|string|in:CFS,Warehouse,Factory,Port Terminal,Depot,Container Yard,Inland Terminal',
            'operator_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'country_id' => 'nullable|exists:countries,id',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'operating_hours' => 'nullable|array',
            'container_types_handled' => 'nullable|array',
            'max_containers_per_day' => 'nullable|integer|min:1',
            'equipment_available' => 'nullable|array',
            'services_offered' => 'nullable|array',
            'storage_rate_per_day' => 'nullable|numeric|min:0',
            'stuffing_rate' => 'nullable|numeric|min:0',
            'destuffing_rate' => 'nullable|numeric|min:0',
            'has_security' => 'required|boolean',
            'has_cctv' => 'required|boolean',
            'requires_appointment' => 'required|boolean',
            'advance_booking_hours' => 'nullable|integer|min:1|max:168',
            'access_instructions' => 'nullable|string',
            'safety_requirements' => 'nullable|string',
            'status' => 'required|string|in:Active,Inactive,Maintenance',
            'notes' => 'nullable|string'
        ];
    }
}
