<?php

namespace App\Models\Management\Account;

use App\Models\Management\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeCovenant extends Model
{
    use HasFactory;

    protected $table = 'employee_covenants'; // This table doesn't exist yet

    protected $fillable = [
        'employee_id',
        'equipment_type',
        'equipment_name'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
