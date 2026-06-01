<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'seating_capacity',
        'vehicle_number', 'base_rate', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function cabBookings()
    {
        return $this->hasMany(CabBooking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'sedan' => 'Sedan',
            'mpv'   => 'MPV',
            'suv'   => 'SUV',
            'tempo' => 'Tempo Traveller',
            default => ucfirst($this->category),
        };
    }

    public function getEmojiAttribute()
    {
        return match($this->category) {
            'sedan' => '🚗',
            'mpv'   => '🚙',
            'suv'   => '🚕',
            'tempo' => '🚐',
            default => '🚗',
        };
    }
}
