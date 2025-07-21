<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Destination extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'destination_code',
        'destination_name',
        'city',
        'state_province',
        'country',
        'country_id',
        'postal_code',
        'address',
        'destination_type',
        'latitude',
        'longitude',
        'contact_person',
        'contact_phone',
        'contact_email',
        'delivery_instructions',
        'access_restrictions',
        'facilities',
        'requires_appointment',
        'timezone',
        'status'
    ];

    protected $casts = [
        'facilities' => 'array',
        'requires_appointment' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'destination_id');
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'destination_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('destination_type', $type);
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

        return implode(' | ', $contact);
    }

    public function getTypeDisplayAttribute()
    {
        $types = [
            'Port' => '🚢 Port',
            'Airport' => '✈️ Airport',
            'Warehouse' => '🏪 Warehouse',
            'Factory' => '🏭 Factory',
            'City' => '🏙️ City Center',
            'Terminal' => '📦 Terminal',
            'Depot' => '🚛 Depot'
        ];

        return $types[$this->destination_type] ?? $this->destination_type;
    }

    public function getFacilitiesListAttribute()
    {
        if (!$this->facilities || !is_array($this->facilities)) {
            return 'Standard facilities';
        }

        return implode(', ', $this->facilities);
    }

    public function getTimezoneDisplayAttribute()
    {
        return $this->timezone ?: 'UTC';
    }

    // Methods
    public function getStatistics()
    {
        return [
            'total_shipments' => $this->shipments()->count(),
            'active_shipments' => $this->shipments()->whereIn('status', ['Pending', 'In Transit', 'At Port'])->count(),
            'completed_shipments' => $this->shipments()->where('status', 'Delivered')->count(),
            'monthly_volume' => $this->shipments()->whereMonth('created_at', now()->month)->count(),
            'total_routes' => $this->routes()->count()
        ];
    }

    public function hasFacility($facility)
    {
        if (!$this->facilities) return false;
        return in_array($facility, $this->facilities);
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

    public function isAvailableForDelivery($requestedTime = null)
    {
        if ($this->status !== 'Active') return false;

        if ($this->requires_appointment && !$requestedTime) {
            return false;
        }

        // Add more complex availability logic here if needed
        return true;
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'destination_code' => 'required|string|max:20|unique:destinations,destination_code,' . $id,
            'destination_name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state_province' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'country_id' => 'nullable|exists:countries,id',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'destination_type' => 'required|string|in:Port,Airport,Warehouse,Factory,City,Terminal,Depot',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'delivery_instructions' => 'nullable|string',
            'access_restrictions' => 'nullable|string',
            'facilities' => 'nullable|array',
            'requires_appointment' => 'required|boolean',
            'timezone' => 'nullable|string|max:50',
            'status' => 'required|string|in:Active,Inactive'
        ];
    }
}
