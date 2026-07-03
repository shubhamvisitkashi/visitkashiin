<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabBookingLeg extends Model
{
    protected $fillable = [
        'cab_booking_id', 'sequence', 'leg_date', 'pickup_address', 'drop_address', 'fare',
    ];

    protected $casts = ['leg_date' => 'date'];

    public function cabBooking() { return $this->belongsTo(CabBooking::class); }
}
