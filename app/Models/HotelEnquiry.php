<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelEnquiry extends Model
{
    use HasFactory;

    protected $table = 'hotel_enquiries';

    protected $fillable = [
        'category',
        'hotel_name',
        'guest_name',
        'contact_number',
        'adults',
        'kids',
        'checkin_datetime',
        'checkout_datetime',
        'nights',
    ];

    protected $casts = [
        'checkin_datetime'  => 'datetime',
        'checkout_datetime' => 'datetime',
    ];
}
