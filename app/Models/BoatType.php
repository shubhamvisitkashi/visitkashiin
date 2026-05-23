<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoatType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'image',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'is_active',
    ];

    public function getImageAttribute($value) {
        return $value ? asset('storage/'.$value) : asset('forefront/assets/images/no-image.png');
    }

}
