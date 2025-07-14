<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'employee_id',
        'shipment_id',
        'account_id',
        'category',
        'description',
        'amount',
        'expense_date',
        'status',
        'receipt_path',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('expense_date', now()->month);
    }
}
