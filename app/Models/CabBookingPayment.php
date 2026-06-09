<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

class CabBookingPayment extends Model
{
    protected $fillable = [
        'cab_booking_id', 'amount', 'payment_method', 'cash_receiver_name',
        'payment_date', 'payment_account_id', 'received_by', 'notes',
    ];

    protected $casts = ['payment_date' => 'date'];

    public function cabBooking()     { return $this->belongsTo(CabBooking::class); }
    public function paymentAccount() { return $this->belongsTo(PaymentAccount::class); }
    public function receivedBy()     { return $this->belongsTo(Admin::class, 'received_by'); }
}
