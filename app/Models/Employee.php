<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'department',
        'position',
        'email',
        'phone',
        'emergency_contact',
        'address',
        'hire_date',
        'salary',
        'status',
        'date_of_birth',
        'bank_account',
        'nationality',
        // Advanced fields from nationality migration
        'nationality_id',
        'passport_number',
        'passport_expiry',
        'visa_status',
        'visa_expiry',
        'employment_type',
        'contract_start',
        'contract_end',
        'manager_id',
        'skills',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'date_of_birth' => 'date',
        'salary' => 'decimal:2',
        'passport_expiry' => 'date',
        'visa_expiry' => 'date',
        'contract_start' => 'date',
        'contract_end' => 'date',
        'skills' => 'array'
    ];

    // ===== RELATIONSHIPS =====
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function jobAssignments()
    {
        return $this->hasMany(JobAssignment::class);
    }

    public function advances()
    {
        return $this->hasMany(EmployeeAdvance::class);
    }

    public function covenants()
    {
        return $this->hasMany(EmployeeCovenant::class);
    }

    // Add nationality relationship if using nationality_id
    public function nationalityRecord()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    // ===== SCOPES =====

    public function scopeDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ===== METHODS =====

    public static function generateEmployeeId()
    {
        $lastEmployee = static::orderBy('employee_id', 'desc')->first();

        if ($lastEmployee && $lastEmployee->employee_id) {
            // Extract number from EMP-001 format
            $lastNumber = intval(substr($lastEmployee->employee_id, 4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'EMP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function getFullContactAttribute(): string
    {
        $contact = $this->name;
        if ($this->email) {
            $contact .= " ({$this->email})";
        }
        if ($this->phone) {
            $contact .= " - {$this->phone}";
        }
        return $contact;
    }

    public function getEmploymentDurationAttribute(): string
    {
        if (!$this->hire_date) {
            return 'N/A';
        }

        return $this->hire_date->diffForHumans();
    }

    public function getAnnualSalaryAttribute(): ?float
    {
        return $this->salary ? $this->salary * 12 : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->employee_id)) {
                $employee->employee_id = static::generateEmployeeId();
            }
        });
    }
}
