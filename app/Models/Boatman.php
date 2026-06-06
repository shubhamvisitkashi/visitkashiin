<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boatman extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'alt_phone', 'ghat', 'is_active', 'notes'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
