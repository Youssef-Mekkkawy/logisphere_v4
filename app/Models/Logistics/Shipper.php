<?php

namespace App\Models\Logistics;

use App\Models\Country;
use App\Models\Management\Shipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipper_code',
        'shipper_name',
        'company_name',
        'shipper_type',
        'business_license',
        'tax_id',
        'address',
        'city',
        'state_province',
        'country',
        'country_id',
        'postal_code',
        'latitude',
        'longitude',
        'contact_person',
        'contact_phone',
        'contact_email',
        'website',
        'alternative_phone',
        'emergency_contact',
        'industry_type',
        'specialization',
        'cargo_types',
        'trade_routes',
        'certifications',
        'established_year',
        'annual_volume',
        'services_offered',
        'equipment_owned',
        'preferred_ports',
        'operating_regions',
        'operating_hours',
        'time_zone',
        'credit_rating',
        'payment_terms',
        'credit_limit',
        'currency_preference',
        'insurance_coverage',
        'bank_name',
        'bank_account',
        'required_documents',
        'customs_broker',
        'freight_forwarder',
        'dangerous_goods_certified',
        'customs_code',
        'regulatory_compliance',
        'on_time_delivery_rate',
        'damage_rate',
        'total_shipments',
        'customer_satisfaction',
        'last_audit_date',
        'audit_result',
        'preferred_carriers',
        'preferred_incoterms',
        'track_and_trace_required',
        'email_notifications',
        'sms_notifications',
        'notification_language',
        'status',
        'notes',
        'internal_notes',
        'sales_representative',
        'account_manager',
        'contract_start_date',
        'contract_end_date'
    ];

    protected $casts = [
        'cargo_types' => 'array',
        'trade_routes' => 'array',
        'certifications' => 'array',
        'services_offered' => 'array',
        'equipment_owned' => 'array',
        'preferred_ports' => 'array',
        'operating_regions' => 'array',
        'required_documents' => 'array',
        'regulatory_compliance' => 'array',
        'preferred_carriers' => 'array',
        'preferred_incoterms' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'credit_limit' => 'decimal:2',
        'on_time_delivery_rate' => 'decimal:2',
        'damage_rate' => 'decimal:4',
        'customer_satisfaction' => 'decimal:2',
        'customs_broker' => 'boolean',
        'freight_forwarder' => 'boolean',
        'dangerous_goods_certified' => 'boolean',
        'insurance_coverage' => 'boolean',
        'track_and_trace_required' => 'boolean',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'last_audit_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'established_year' => 'integer'
    ];

    // Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'shipper_id');
    }

    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'shipper_id')->where('direction', 'export');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'consignee_id');
    }

    // Accessors
    public function getTypeDisplayAttribute()
    {
        $icons = [
            'Manufacturer' => '🏭',
            'Exporter' => '📦',
            'Trading Company' => '🏢',
            'Freight Forwarder' => '🚛',
            'Agent' => '👔',
            'Importer' => '📥',
            'Distributor' => '🏪',
            'Retailer' => '🛒'
        ];

        return ($icons[$this->shipper_type] ?? '🏢') . ' ' . $this->shipper_type;
    }

    public function getFormattedAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->state_province) $address .= ', ' . $this->state_province;
        if ($this->postal_code) $address .= ' ' . $this->postal_code;
        if ($this->country) $address .= ', ' . $this->country;

        return $address;
    }

    public function getVolumeDisplayAttribute()
    {
        if (!$this->annual_volume) return 'N/A';

        if ($this->annual_volume >= 1000000) {
            return number_format($this->annual_volume / 1000000, 1) . 'M TEU/year';
        } elseif ($this->annual_volume >= 1000) {
            return number_format($this->annual_volume / 1000, 1) . 'K TEU/year';
        }

        return number_format($this->annual_volume) . ' TEU/year';
    }

    public function getCreditDisplayAttribute()
    {
        if (!$this->credit_rating) return 'Not Rated';

        $colors = [
            'A+' => '#10b981',
            'A' => '#059669',
            'A-' => '#047857',
            'B+' => '#f59e0b',
            'B' => '#d97706',
            'B-' => '#b45309',
            'C+' => '#ef4444',
            'C' => '#dc2626',
            'C-' => '#b91c1c'
        ];

        $color = $colors[$this->credit_rating] ?? '#64748b';
        return '<span style="color: ' . $color . '; font-weight: 600;">' . $this->credit_rating . '</span>';
    }

    public function getPerformanceScoreAttribute()
    {
        $score = 0;
        $factors = 0;

        if ($this->on_time_delivery_rate !== null) {
            $score += $this->on_time_delivery_rate;
            $factors++;
        }

        if ($this->damage_rate !== null) {
            $score += (100 - $this->damage_rate * 100); // Invert damage rate
            $factors++;
        }

        if ($this->customer_satisfaction !== null) {
            $score += ($this->customer_satisfaction * 20); // Convert 5-point to 100-point scale
            $factors++;
        }

        return $factors > 0 ? round($score / $factors, 1) : null;
    }

    // Utility Methods
    public function hasCoordinates()
    {
        return $this->latitude && $this->longitude;
    }

    public function isActive()
    {
        return $this->status === 'Active';
    }

    public function canShipDangerousGoods()
    {
        return $this->dangerous_goods_certified;
    }

    public function getActiveShipmentsCount()
    {
        return $this->shipments()->whereIn('status', ['In Transit', 'Processing', 'Customs Clearance'])->count();
    }

    public function getMonthlyVolume()
    {
        return $this->shipments()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function getUtilizationRate()
    {
        if (!$this->annual_volume) return 0;

        $monthlyTarget = $this->annual_volume / 12;
        $currentMonthVolume = $this->getMonthlyVolume();

        return $monthlyTarget > 0 ? round(($currentMonthVolume / $monthlyTarget) * 100, 1) : 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('shipper_type', $type);
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeWithGoodCredit($query)
    {
        return $query->whereIn('credit_rating', ['A+', 'A', 'A-', 'B+']);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('shipper_name', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('shipper_code', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('contact_email', 'like', "%{$search}%");
        });
    }
}
