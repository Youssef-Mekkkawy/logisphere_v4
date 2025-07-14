<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'category',
        'balance',
        'is_active',
        'description'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function updateBalance()
    {
        $totalDebits = $this->journalEntryLines()->sum('debit_amount');
        $totalCredits = $this->journalEntryLines()->sum('credit_amount');

        // Asset and Expense accounts increase with debits
        if (in_array($this->type, ['asset', 'expense'])) {
            $this->balance = $totalDebits - $totalCredits;
        } else {
            // Liability, Equity, and Revenue accounts increase with credits
            $this->balance = $totalCredits - $totalDebits;
        }

        $this->save();
    }
    public function scopeExpenses($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
