<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoatBookingRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'boat_id',
        'name',
        'email',
        'phone',
        'no_of_person',
        'total_amount',
        'total_discount',
        'final_amount',
        'booking_date',
        'payment_detail',
        'booking_status',
        'payment_status',
    ];

    protected $casts = [
        'payment_detail' => 'array',
    ];

    public function boat() {
        return $this->belongsTo(Boat::class);
    }

}
