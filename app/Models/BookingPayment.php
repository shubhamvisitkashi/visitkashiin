<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'payment_account_id',
        'payment_date',
        'payment_time',
        'amount',
        'payment_method',
        'cash_receiver_name',
        'reference_number',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_time' => 'datetime:H:i',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($payment) {
            $payment->booking->updatePaidAmount();
            
            // Update payment account balance
            if ($payment->payment_account_id) {
                $payment->paymentAccount->updateBalance();
            }
        });

        static::deleted(function ($payment) {
            $payment->booking->updatePaidAmount();
            
            // Update payment account balance
            if ($payment->payment_account_id) {
                $payment->paymentAccount->updateBalance();
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
