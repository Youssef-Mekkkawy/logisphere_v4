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
        'gender',
        'user_id', // 🔥 NEW: Link to user account

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

    /**
     * Employee has one user account
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Employee can have many shipments
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Employee can have many job assignments
     */
    public function jobAssignments()
    {
        return $this->hasMany(JobAssignment::class);
    }

    /**
     * Employee can have many advances
     */
    public function advances()
    {
        return $this->hasMany(EmployeeAdvance::class);
    }

    /**
     * Employee can have many covenants
     */
    public function covenants()
    {
        return $this->hasMany(EmployeeCovenant::class);
    }

    /**
     * Employee nationality relationship
     */
    public function nationalityRecord()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    /**
     * Employee reports to manager
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Employee manages other employees
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
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

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope employees with user accounts
     */
    public function scopeWithUserAccount($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope employees without user accounts
     */
    public function scopeWithoutUserAccount($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope employees with active user accounts
     */
    public function scopeWithActiveUserAccount($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('is_active', true);
        });
    }

    // ===== USER ACCOUNT METHODS =====

    /**
     * Check if employee has a user account
     */
    public function hasUserAccount(): bool
    {
        return $this->user !== null;
    }

    /**
     * Check if employee has an active user account
     */
    public function hasActiveUserAccount(): bool
    {
        return $this->user && $this->user->isActive();
    }

    /**
     * Check if employee user account is blocked
     */
    public function isUserAccountBlocked(): bool
    {
        return $this->user && $this->user->isBlocked();
    }

    /**
     * Get user account status text
     */
    public function getUserAccountStatusAttribute(): string
    {
        if (!$this->user) {
            return 'No Account';
        }

        return $this->user->statusText;
    }

    /**
     * Get user roles for display
     */
    public function getUserRolesAttribute(): string
    {
        if (!$this->user) {
            return 'N/A';
        }

        return $this->user->roles->pluck('name')->join(', ') ?: 'No roles';
    }

    // ===== EXISTING METHODS =====

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

    /**
     * Get display name with employee ID
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->employee_id})";
    }

    /**
     * Get employee status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Active' => '#10b981',
            'Inactive' => '#ef4444',
            default => '#6b7280'
        };
    }

    /**
     * Get department color for display
     */
    public function getDepartmentColorAttribute(): string
    {
        $colors = [
            'Management' => '#dc2626',
            'Finance & Accounting' => '#059669',
            'Human Resources' => '#7c3aed',
            'IT & Technology' => '#2563eb',
            'Operations' => '#ea580c',
            'Customer Service' => '#0891b2',
            'Sales' => '#16a34a',
            'Customs Clearance' => '#ca8a04',
            'Warehousing' => '#9333ea',
            'Transportation' => '#0d9488',
            'Administration' => '#6b7280'
        ];

        return $colors[$this->department] ?? '#6b7280';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->employee_id)) {
                $employee->employee_id = static::generateEmployeeId();
            }
        });

        static::deleting(function ($employee) {
            // Handle user account when deleting employee
            if ($employee->user) {
                // Option 1: Delete the user account
                // $employee->user->delete();

                // Option 2: Disconnect user from employee (safer)
                $employee->user->update(['employee_id' => null]);
            }
        });
    }
}
