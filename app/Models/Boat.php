<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    use HasFactory;

    protected $fillable = [
        'boat_type_id',
        'event_type',
        'total_available_boat',
        'no_of_seat',
        'price',
        'discounted_price',
        'is_active',
    ];

    public function boatType() {
        return $this->belongsTo(BoatType::class, 'boat_type_id');
    }

}
