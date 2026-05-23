<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_type',
        'account_number',
        'bank_name',
        'branch_name',
        'ifsc_code',
        'initial_balance',
        'current_balance',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get all payments for this account
     */
    public function payments()
    {
        return $this->hasMany(BookingPayment::class, 'payment_account_id');
    }

    /**
     * Get all vendor payments for this account
     */
    public function vendorPayments()
    {
        return $this->hasMany(VendorPayment::class, 'payment_account_id');
    }

    /**
     * Get all transactions (both incoming and outgoing)
     */
    public function getAllTransactions()
    {
        $bookingPayments = $this->payments()->with('booking.lead')->get()->map(function($payment) {
            return [
                'id' => $payment->id,
                'date' => $payment->payment_date,
                'time' => $payment->payment_time,
                'type' => 'Incoming',
                'reference' => $payment->booking ? 'Booking #' . $payment->booking->id . ' - ' . $payment->booking->lead->name : 'N/A',
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'model' => 'BookingPayment'
            ];
        });

        $vendorPayments = $this->vendorPayments()->with('serviceProvider')->get()->map(function($payment) {
            return [
                'id' => $payment->id,
                'date' => $payment->payment_date,
                'time' => $payment->payment_time,
                'type' => 'Outgoing',
                'reference' => $payment->serviceProvider ? 'Vendor: ' . $payment->serviceProvider->name : 'N/A',
                'amount' => -$payment->amount, // Negative for outgoing
                'payment_method' => $payment->payment_method,
                'model' => 'VendorPayment'
            ];
        });

        return $bookingPayments->concat($vendorPayments)->sortByDesc('date');
    }

    /**
     * Scope to get only active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by account type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    /**
     * Calculate and update current balance
     */
    public function updateBalance()
    {
        $totalPayments = $this->payments()->sum('amount');
        $this->current_balance = $this->initial_balance + $totalPayments;
        $this->save();
    }

    /**
     * Get formatted account type
     */
    public function getFormattedTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->account_type));
    }

    /**
     * Get account display name with type
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->account_name} ({$this->formatted_type})";
    }
}
