<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class PortOperation extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'operation_id',
        'port_id',
        'shipment_id',
        'operation_type',
        'vessel_name',
        'vessel_imo',
        'vessel_type',
        'vessel_size',
        'vessel_flag',
        'berth_number',
        'berth_assignment_time',
        'scheduled_arrival',
        'actual_arrival',
        'scheduled_departure',
        'actual_departure',
        'operation_start_time',
        'operation_end_time',
        'cargo_type',
        'cargo_volume',
        'containers_count',
        'container_types',
        'handling_equipment_used',
        'operator_name',
        'pilot_required',
        'pilot_name',
        'tugboat_required',
        'tugboat_count',
        'handling_cost',
        'port_fees',
        'additional_charges',
        'total_cost',
        'status',
        'priority',
        'weather_conditions',
        'operation_notes',
        'special_requirements',
        'customs_cleared',
        'quarantine_cleared',
        'documents_complete',
        'delays_reason',
        'delay_duration'
    ];

    protected $casts = [
        'berth_assignment_time' => 'datetime',
        'scheduled_arrival' => 'datetime',
        'actual_arrival' => 'datetime',
        'scheduled_departure' => 'datetime',
        'actual_departure' => 'datetime',
        'operation_start_time' => 'datetime',
        'operation_end_time' => 'datetime',
        'container_types' => 'array',
        'handling_equipment_used' => 'array',
        'pilot_required' => 'boolean',
        'tugboat_required' => 'boolean',
        'customs_cleared' => 'boolean',
        'quarantine_cleared' => 'boolean',
        'documents_complete' => 'boolean',
        'vessel_size' => 'decimal:2',
        'cargo_volume' => 'decimal:2',
        'handling_cost' => 'decimal:2',
        'port_fees' => 'decimal:2',
        'additional_charges' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'delay_duration' => 'decimal:2'
    ];

    // Relationships
    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function company()
    {
        return $this->hasOneThrough(Company::class, Shipment::class, 'id', 'id', 'shipment_id', 'company_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Scheduled', 'In Progress', 'Waiting']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeByPort($query, $portId)
    {
        return $query->where('port_id', $portId);
    }

    public function scopeByOperationType($query, $type)
    {
        return $query->where('operation_type', $type);
    }

    public function scopeByVesselType($query, $type)
    {
        return $query->where('vessel_type', $type);
    }

    public function scopeDelayed($query)
    {
        return $query->whereNotNull('delays_reason');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_arrival', today());
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->whereBetween('scheduled_arrival', [now(), now()->addDays($days)]);
    }

    // Accessors
    public function getOperationTypeDisplayAttribute()
    {
        $types = [
            'Loading' => '📦 Cargo Loading',
            'Unloading' => '🚛 Cargo Unloading',
            'Transit' => '🔄 Transit',
            'Bunkering' => '⛽ Bunkering',
            'Maintenance' => '🔧 Maintenance',
            'Inspection' => '🔍 Inspection',
            'Quarantine' => '🏥 Quarantine',
            'Customs' => '🛃 Customs Check',
            'Layover' => '⏸️ Layover',
            'Emergency' => '🚨 Emergency'
        ];

        return $types[$this->operation_type] ?? $this->operation_type;
    }

    public function getVesselTypeDisplayAttribute()
    {
        $types = [
            'Container Ship' => '🚢 Container Ship',
            'Bulk Carrier' => '⚖️ Bulk Carrier',
            'Tanker' => '🛢️ Tanker',
            'General Cargo' => '📦 General Cargo',
            'RoRo' => '🚛 RoRo Ship',
            'Cruise Ship' => '🛳️ Cruise Ship',
            'Ferry' => '⛴️ Ferry',
            'Fishing Vessel' => '🎣 Fishing Vessel',
            'Yacht' => '⛵ Yacht',
            'Naval' => '⚓ Naval Vessel'
        ];

        return $types[$this->vessel_type] ?? $this->vessel_type;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Scheduled' => 'status-info',
            'Waiting' => 'status-warning',
            'In Progress' => 'status-primary',
            'Completed' => 'status-success',
            'Cancelled' => 'status-secondary',
            'Delayed' => 'status-danger'
        ];

        return $badges[$this->status] ?? 'status-secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'High' => 'status-danger',
            'Medium' => 'status-warning',
            'Low' => 'status-success'
        ];

        return $badges[$this->priority] ?? 'status-secondary';
    }

    public function getVesselSizeDisplayAttribute()
    {
        if (!$this->vessel_size) return 'Size not specified';

        return number_format($this->vessel_size) . ' DWT';
    }

    public function getCargoVolumeDisplayAttribute()
    {
        if (!$this->cargo_volume) return 'Volume not specified';

        if ($this->cargo_type === 'Container') {
            return number_format($this->cargo_volume) . ' TEU';
        }

        return number_format($this->cargo_volume) . ' tons';
    }

    public function getTotalCostDisplayAttribute()
    {
        if (!$this->total_cost) return 'Cost not calculated';

        return '$' . number_format($this->total_cost, 2);
    }

    public function getDurationDisplayAttribute()
    {
        if (!$this->operation_start_time || !$this->operation_end_time) {
            return 'Duration not available';
        }

        $duration = $this->operation_start_time->diffInHours($this->operation_end_time);

        if ($duration < 1) {
            return $this->operation_start_time->diffInMinutes($this->operation_end_time) . ' minutes';
        }

        return $duration . ' hours';
    }

    public function getIsDelayedAttribute()
    {
        return !empty($this->delays_reason);
    }

    public function getDelayDurationDisplayAttribute()
    {
        if (!$this->delay_duration) return null;

        if ($this->delay_duration < 1) {
            return ($this->delay_duration * 60) . ' minutes';
        }

        return $this->delay_duration . ' hours';
    }

    // Methods
    public function calculateDuration()
    {
        if (!$this->operation_start_time || !$this->operation_end_time) {
            return 0;
        }

        return $this->operation_start_time->diffInHours($this->operation_end_time);
    }

    public function calculateTotalCost()
    {
        $total = 0;

        if ($this->handling_cost) $total += $this->handling_cost;
        if ($this->port_fees) $total += $this->port_fees;
        if ($this->additional_charges) $total += $this->additional_charges;

        $this->update(['total_cost' => $total]);

        return $total;
    }

    public function markAsStarted()
    {
        $this->update([
            'status' => 'In Progress',
            'operation_start_time' => now()
        ]);

        return $this;
    }

    public function markAsCompleted($notes = null)
    {
        $this->update([
            'status' => 'Completed',
            'operation_end_time' => now(),
            'operation_notes' => $notes ?: $this->operation_notes
        ]);

        // Calculate final costs and duration
        $this->calculateTotalCost();

        return $this;
    }

    public function addDelay($reason, $durationHours)
    {
        $this->update([
            'status' => 'Delayed',
            'delays_reason' => $reason,
            'delay_duration' => $durationHours
        ]);

        return $this;
    }

    public function assignBerth($berthNumber)
    {
        $this->update([
            'berth_number' => $berthNumber,
            'berth_assignment_time' => now()
        ]);

        return $this;
    }

    public function recordArrival()
    {
        $this->update([
            'actual_arrival' => now(),
            'status' => 'Waiting'
        ]);

        return $this;
    }

    public function recordDeparture()
    {
        $this->update([
            'actual_departure' => now()
        ]);

        // If operation is completed, keep that status
        if ($this->status !== 'Completed') {
            $this->update(['status' => 'Completed']);
        }

        return $this;
    }

    public function clearCustoms($cleared = true)
    {
        $this->update(['customs_cleared' => $cleared]);
        return $this;
    }

    public function clearQuarantine($cleared = true)
    {
        $this->update(['quarantine_cleared' => $cleared]);
        return $this;
    }

    public function markDocumentsComplete($complete = true)
    {
        $this->update(['documents_complete' => $complete]);
        return $this;
    }

    public function isReadyToStart()
    {
        return $this->customs_cleared &&
            $this->quarantine_cleared &&
            $this->documents_complete &&
            $this->berth_number;
    }

    public function getReadinessChecklist()
    {
        return [
            'customs_cleared' => $this->customs_cleared,
            'quarantine_cleared' => $this->quarantine_cleared,
            'documents_complete' => $this->documents_complete,
            'berth_assigned' => !empty($this->berth_number),
            'pilot_arranged' => !$this->pilot_required || !empty($this->pilot_name),
            'tugboat_arranged' => !$this->tugboat_required || $this->tugboat_count > 0
        ];
    }

    public function getReadinessPercentage()
    {
        $checklist = $this->getReadinessChecklist();
        $completed = array_sum($checklist);
        $total = count($checklist);

        return round(($completed / $total) * 100);
    }

    public function getEstimatedCompletionTime()
    {
        if (!$this->operation_start_time) {
            return null;
        }

        // Base duration estimates by operation type
        $baseDurations = [
            'Loading' => 8,
            'Unloading' => 6,
            'Transit' => 2,
            'Bunkering' => 4,
            'Maintenance' => 12,
            'Inspection' => 3,
            'Quarantine' => 24,
            'Customs' => 2,
            'Layover' => 6,
            'Emergency' => 4
        ];

        $baseDuration = $baseDurations[$this->operation_type] ?? 6;

        // Adjust for cargo volume
        if ($this->cargo_volume && $this->cargo_volume > 1000) {
            $baseDuration += ($this->cargo_volume / 1000) * 0.5;
        }

        return $this->operation_start_time->addHours($baseDuration);
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'operation_id' => 'required|string|max:50|unique:port_operations,operation_id,' . $id,
            'port_id' => 'required|exists:ports,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'operation_type' => 'required|string|in:Loading,Unloading,Transit,Bunkering,Maintenance,Inspection,Quarantine,Customs,Layover,Emergency',
            'vessel_name' => 'required|string|max:255',
            'vessel_imo' => 'nullable|string|max:20',
            'vessel_type' => 'required|string|in:Container Ship,Bulk Carrier,Tanker,General Cargo,RoRo,Cruise Ship,Ferry,Fishing Vessel,Yacht,Naval',
            'vessel_size' => 'nullable|numeric|min:0|max:999999.99',
            'vessel_flag' => 'nullable|string|max:100',
            'berth_number' => 'nullable|string|max:20',
            'scheduled_arrival' => 'required|date',
            'scheduled_departure' => 'nullable|date|after:scheduled_arrival',
            'cargo_type' => 'nullable|string|max:100',
            'cargo_volume' => 'nullable|numeric|min:0|max:999999.99',
            'containers_count' => 'nullable|integer|min:0|max:99999',
            'operator_name' => 'nullable|string|max:255',
            'pilot_required' => 'required|boolean',
            'pilot_name' => 'nullable|string|max:255',
            'tugboat_required' => 'required|boolean',
            'tugboat_count' => 'nullable|integer|min:0|max:10',
            'status' => 'required|string|in:Scheduled,Waiting,In Progress,Completed,Cancelled,Delayed',
            'priority' => 'required|string|in:High,Medium,Low',
            'weather_conditions' => 'nullable|string|max:255',
            'operation_notes' => 'nullable|string'
        ];
    }

    public static function getOperationTypes()
    {
        return [
            'Loading' => 'Cargo Loading',
            'Unloading' => 'Cargo Unloading',
            'Transit' => 'Transit',
            'Bunkering' => 'Bunkering',
            'Maintenance' => 'Maintenance',
            'Inspection' => 'Inspection',
            'Quarantine' => 'Quarantine',
            'Customs' => 'Customs Check',
            'Layover' => 'Layover',
            'Emergency' => 'Emergency'
        ];
    }

    public static function getVesselTypes()
    {
        return [
            'Container Ship' => 'Container Ship',
            'Bulk Carrier' => 'Bulk Carrier',
            'Tanker' => 'Tanker',
            'General Cargo' => 'General Cargo',
            'RoRo' => 'RoRo Ship',
            'Cruise Ship' => 'Cruise Ship',
            'Ferry' => 'Ferry',
            'Fishing Vessel' => 'Fishing Vessel',
            'Yacht' => 'Yacht',
            'Naval' => 'Naval Vessel'
        ];
    }
}
