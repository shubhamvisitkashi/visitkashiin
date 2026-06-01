<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'image','badge','title','tagline',
        'cta_label','cta_url','sort_order','is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) {
        return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): string {
        return $this->image
            ? asset('backend/admin/hero_slides/' . $this->image)
            : asset('backend/assets/images/placeholder.jpg');
    }
}
