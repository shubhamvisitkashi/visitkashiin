<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoatBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'booked_by',
        'boat_id',
        'name',
        'email',
        'phone',
        'no_of_person',
        'total_amount',
        'total_discount',
        'final_amount',
        'booking_date',
        'booking_status',
        'payment_status',
    ];

    public function boat() {
        return $this->belongsTo(Boat::class, 'boat_id');
    }

    public function payments() {
        return $this->hasMany(BoatBookingPayment::class, 'boat_booking_id');
    }

}
