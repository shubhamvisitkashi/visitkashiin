<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    use HasFactory;

    protected $fillable = [
        'boat_type_id', 'event_type',
        'total_available_boat', 'no_of_seat',
        'base_pax', 'max_capacity', 'extra_per_person_rate',
        'price', 'discounted_price', 'is_active',
    ];

    protected $casts = [
        'base_pax'             => 'integer',
        'max_capacity'         => 'integer',
        'extra_per_person_rate'=> 'decimal:2',
        'price'                => 'decimal:2',
    ];

    public function boatType() {
        return $this->belongsTo(BoatType::class, 'boat_type_id');
    }

}
