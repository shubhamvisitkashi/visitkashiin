<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoatBookingPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'boat_booking_id',
        'amount',
        'payment_details',
    ];

    protected $casts = [
        'payment_details' => 'array',
    ];

    public function boatBooking() {
        return $this->belongsTo(BoatBooking::class);
    }

}
