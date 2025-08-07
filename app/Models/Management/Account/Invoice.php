<?php

namespace App\Models\Management\Account;

use App\Models\Management\Account\Payment;
use App\Models\Management\Company;
use App\Models\Management\Shipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'company_id',
        'shipment_id',
        'type',
        'status',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'balance_due',
        'notes',
        'line_items'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'line_items' => 'array'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateBalanceDue()
    {
        $this->paid_amount = $this->payments()->sum('amount');
        $this->balance_due = $this->total_amount - $this->paid_amount;

        if ($this->balance_due <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partially_paid';
        }

        $this->save();
    }

    public function isOverdue()
    {
        return $this->due_date->isPast() && $this->balance_due > 0;
    }
    public function scopeReceivable($query)
    {
        return $query->where('type', 'receivable');
    }

    public function scopePayable($query)
    {
        return $query->where('type', 'payable');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('balance_due', '>', 0);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('balance_due', '>', 0);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('invoice_date', now()->month);
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }
}
