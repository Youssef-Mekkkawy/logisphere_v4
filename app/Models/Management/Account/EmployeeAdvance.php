<?php

namespace App\Models\Management\Account;

use App\Models\Management\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_number',
        'employee_id',
        'type',
        'amount',
        'repaid_amount',
        'balance',
        'issued_date',
        'due_date',
        'status',
        'reason',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'repaid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'issued_date' => 'date',
        'due_date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function updateBalance()
    {
        $this->balance = $this->amount - $this->repaid_amount;

        if ($this->balance <= 0) {
            $this->status = 'fully_repaid';
        } elseif ($this->repaid_amount > 0) {
            $this->status = 'partially_repaid';
        }

        $this->save();
    }
    public function scopeOutstanding($query)
    {
        return $query->where('balance', '>', 0);
    }
}
