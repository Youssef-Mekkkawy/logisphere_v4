<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Port extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'country',
        'city',
        'type',
        'status'
    ];

    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_port_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_port_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
    public function originRoutes()
    {
        return $this->hasMany(Route::class, 'origin_port_id');
    }

    /**
     * Get routes ending at this port
     */
    public function destinationRoutes()
    {
        return $this->hasMany(Route::class, 'destination_port_id');
    }

    /**
     * Get all routes (origin or destination)
     */
    public function allRoutes()
    {
        return Route::where('origin_port_id', $this->id)
            ->orWhere('destination_port_id', $this->id);
    }

    /**
     * Get containers currently at this port
     */
    public function currentContainers()
    {
        return $this->hasMany(Container::class, 'current_port_id');
    }

    /**
     * Get tracking events at this port
     */
    public function trackingEvents()
    {
        return $this->hasMany(Tracking::class, 'port_id');
    }

    // ADD THESE NEW METHODS:

    /**
     * Get port statistics
     */
    public function getStatistics()
    {
        return [
            'total_routes' => $this->allRoutes()->count(),
            'origin_routes' => $this->originRoutes()->count(),
            'destination_routes' => $this->destinationRoutes()->count(),
            'current_containers' => $this->currentContainers()->count(),
            'recent_activities' => $this->trackingEvents()->where('event_datetime', '>=', now()->subDays(7))->count()
        ];
    }

    /**
     * Check if port handles specific container type
     */
    public function handlesContainerType(string $containerType): bool
    {
        // This would be based on port facilities
        return $this->currentContainers()
            ->where('container_type', $containerType)
            ->exists();
    }
}
