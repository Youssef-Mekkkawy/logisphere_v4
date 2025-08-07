<?php

namespace App\Models\Logistics;

use App\Models\Country;
use App\Models\Management\Shipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingAgency extends Model
{
    use HasFactory;

    protected $table = 'shipping_agencies';

    protected $fillable = [
        'code',
        'name',
        'contact_person',
        'email',
        'phone',
        'country_id',
        'address',
        'service_type',
        'services_offered',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the country for this shipping agency
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get shipments handled by this agency
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Get bookings with this agency
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // ===== ACCESSORS =====

    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;

        // Basic phone formatting - adjust as needed
        return $this->phone;
    }

    public function getCountryNameAttribute()
    {
        return $this->country ? $this->country->name : 'N/A';
    }

    // ===== METHODS =====

    /**
     * Check if this agency is being used
     */
    public function isInUse()
    {
        return $this->shipments()->exists() || $this->bookings()->exists();
    }

    /**
     * Get total shipments count
     */
    public function getTotalShipmentsCount()
    {
        return $this->shipments()->count();
    }

    /**
     * Get active shipments count
     */
    public function getActiveShipmentsCount()
    {
        return $this->shipments()->whereNotIn('status', ['Delivered', 'Cancelled'])->count();
    }

    /**
     * Get shipments this month
     */
    public function getThisMonthShipmentsCount()
    {
        return $this->shipments()->whereMonth('created_at', now()->month)->count();
    }

    /**
     * Get last shipment date
     */
    public function getLastShipmentDate()
    {
        $lastShipment = $this->shipments()->latest()->first();
        return $lastShipment ? $lastShipment->created_at : null;
    }

    /**
     * Get recent shipments
     */
    public function getRecentShipments($limit = 5)
    {
        return $this->shipments()
            ->with(['company', 'originPort', 'destinationPort'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get agency statistics
     */
    public function getStatistics()
    {
        return [
            'total_shipments' => $this->getTotalShipmentsCount(),
            'active_shipments' => $this->getActiveShipmentsCount(),
            'completed_shipments' => $this->shipments()->where('status', 'Delivered')->count(),
            'this_month_shipments' => $this->getThisMonthShipmentsCount(),
            'last_shipment_date' => $this->getLastShipmentDate(),
            'total_bookings' => $this->bookings()->count(),
            'active_bookings' => $this->bookings()->where('status', 'Active')->count(),
        ];
    }

    /**
     * Check if agency provides specific service type
     */
    public function providesService($serviceType)
    {
        return $this->service_type === $serviceType || $this->service_type === 'Full Service';
    }

    /**
     * Get display name with code
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->code} - {$this->name}";
    }
}
