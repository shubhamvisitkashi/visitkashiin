<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',
        'booking_service_id',
        'lead_id',
        'amount',
        'payment_type',
        'payment_method',
        'payment_account_id',
        'reference_number',
        'payment_date',
        'payment_time',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'payment_time' => 'datetime:H:i',
    ];

    /**
     * Get the service provider that received this payment
     */
    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    /**
     * Get the payment account used for this payment
     */
    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class);
    }

    /**
     * Get the admin who recorded this payment
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the booking service this payment is for
     */
    public function bookingService()
    {
        return $this->belongsTo(BookingService::class);
    }

    /**
     * Get the lead this payment is associated with
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
