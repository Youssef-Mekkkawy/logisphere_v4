<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_number',
        'employee_id',
        'shipment_id',
        'task_description',
        'hours_worked',
        'hourly_rate',
        'total_amount',
        'start_date',
        'end_date',
        'status',
        'is_billable',
        'notes'
    ];

    protected $casts = [
        'hours_worked' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_billable' => 'boolean'
    ];
    public function updateTotalAmount()
    {
        $this->total_amount = $this->hours_worked * $this->hourly_rate;
        $this->save();
        return $this;
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

}
