<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'no_of_person',
        'arrival_date',
        'checkin_time',
        'checkout_time',
        'message',
        'package_id',
        'package_name',
        'booking_amount',
        'time_slot',
        'pickup_ghat',
        'children_count',
        'special_notes',
        'luggage_bags',
        'roof_carrier',
    ];
}
