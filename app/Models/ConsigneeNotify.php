<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Traits\BelongsToTenant;
use Illuminate\Notifications\Notifiable;

class ConsigneeNotify extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'party_code',
        'party_name',
        'party_type',
        'company_registration',
        'tax_id',
        'contact_person',
        'email',
        'phone',
        'fax',
        'address',
        'city',
        'state_province',
        'country',
        'postal_code',
        'country_id',
        'preferred_language',
        'notification_preferences',
        'delivery_instructions',
        'special_requirements',
        'requires_original_docs',
        'is_freight_forwarder',
        'credit_rating',
        'credit_limit',
        'payment_terms',
        'status',
        'notes'
    ];

    protected $casts = [
        'notification_preferences' => 'array',
        'requires_original_docs' => 'boolean',
        'is_freight_forwarder' => 'boolean',
        'credit_limit' => 'decimal:2'
    ];

    // Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'consignee_notify_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('party_type', $type);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    // Accessors
    public function getFormattedAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->state_province) $address .= ', ' . $this->state_province;
        if ($this->country) $address .= ', ' . $this->country;
        if ($this->postal_code) $address .= ' ' . $this->postal_code;

        return $address;
    }

    public function getContactInfoAttribute()
    {
        $contact = [];
        if ($this->phone) $contact[] = "Phone: {$this->phone}";
        if ($this->email) $contact[] = "Email: {$this->email}";
        if ($this->fax) $contact[] = "Fax: {$this->fax}";

        return implode(' | ', $contact);
    }

    public function getCreditStatusAttribute()
    {
        if (!$this->credit_rating) return 'Not Rated';

        $ratings = [
            'A' => 'Excellent',
            'B' => 'Good',
            'C' => 'Fair',
            'D' => 'Poor'
        ];

        return $ratings[$this->credit_rating] ?? $this->credit_rating;
    }

    // Methods
    public function getStatistics()
    {
        return [
            'total_shipments' => $this->shipments()->count(),
            'active_shipments' => $this->shipments()->where('status', '!=', 'Delivered')->count(),
            'completed_shipments' => $this->shipments()->where('status', 'Delivered')->count(),
            'total_value' => $this->shipments()->sum('value') ?? 0
        ];
    }

    public function canReceiveNotification($type)
    {
        $preferences = $this->notification_preferences ?? [];
        return in_array($type, $preferences);
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'party_code' => 'required|string|max:20|unique:consignee_notifies,party_code,' . $id,
            'party_name' => 'required|string|max:255',
            'party_type' => 'required|string|in:Consignee,Notify Party,Both',
            'company_registration' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'fax' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state_province' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country_id' => 'nullable|exists:countries,id',
            'preferred_language' => 'required|string|size:2',
            'notification_preferences' => 'nullable|array',
            'delivery_instructions' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'requires_original_docs' => 'required|boolean',
            'is_freight_forwarder' => 'required|boolean',
            'credit_rating' => 'nullable|string|in:A,B,C,D',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:100',
            'status' => 'required|string|in:Active,Inactive',
            'notes' => 'nullable|string'
        ];
    }
}
