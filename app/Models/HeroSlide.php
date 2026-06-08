<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'image','mobile_image','badge','title','tagline',
        'cta_label','cta_url','sort_order','is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) {
        return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): string {
        if (!$this->image) {
            return asset('backend/assets/images/placeholder.jpg');
        }
        $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $this->image);
        if ($webp !== $this->image && file_exists(public_path('backend/admin/hero_slides/' . $webp))) {
            return asset('backend/admin/hero_slides/' . $webp);
        }
        return asset('backend/admin/hero_slides/' . $this->image);
    }

    public function getMobileImageUrlAttribute(): string {
        // Return mobile image if set, otherwise fall back to desktop image
        if (!$this->mobile_image) {
            return $this->image_url;
        }
        return asset('backend/admin/hero_slides/' . $this->mobile_image);
    }

    public function getImageFallbackUrlAttribute(): string {
        return $this->image
            ? asset('backend/admin/hero_slides/' . $this->image)
            : asset('backend/assets/images/placeholder.jpg');
    }
}
