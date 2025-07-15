<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachments;

class Company extends Model
{
    //
    use HasFactory;
    // use HasAttachments;
    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'email',
        'phone',
        'country',
        'address',
        'service_type',
        'status'
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function scopeClients($query)
    {
        return $query->where('type', 'Client');
    }

    public function scopeSuppliers($query)
    {
        return $query->where('type', 'Supplier');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get invoices for this company
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // ADD THESE NEW METHODS:

    /**
     * Get company status info
     */
    public function getStatusInfo()
    {
        return AllStatus::where('module', 'company')
            ->where('status_code', strtoupper($this->status))
            ->first();
    }

    /**
     * Get total outstanding invoices
     */
    public function getOutstandingAmountAttribute()
    {
        return $this->invoices()
            ->whereIn('status', ['Sent', 'Overdue'])
            ->sum('balance_amount');
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaidAttribute()
    {
        return $this->invoices()
            ->where('status', 'Paid')
            ->sum('total_amount');
    }

    /**
     * Check if company has overdue invoices
     */
    public function hasOverdueInvoices(): bool
    {
        return $this->invoices()
            ->where('status', 'Overdue')
            ->exists();
    }

    /**
     * Get company metrics
     */
    public function getMetrics()
    {
        return [
            'total_shipments' => $this->shipments()->count(),
            'active_shipments' => $this->shipments()->whereNotIn('status', ['Delivered', 'Cancelled'])->count(),
            'total_bookings' => $this->bookings()->count(),
            'confirmed_bookings' => $this->bookings()->where('is_confirmed', true)->count(),
            'total_invoiced' => $this->invoices()->sum('total_amount'),
            'outstanding_amount' => $this->outstanding_amount,
            'attachment_count' => $this->attachments()->count()
        ];
    }
}
