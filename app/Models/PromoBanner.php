<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    use HasFactory;

    protected $fillable = ['image','title','subtitle','link','position','sort_order','is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public static $positions = [
        'after_hero'         => 'After Hero Banner',
        'after_trust'        => 'After Trust Bar',
        'after_our_services' => 'After Our Services',
        'after_categories'   => 'After Categories',
        'after_cab'          => 'After Cab Booking',
        'after_boat'         => 'After Boat Booking',
        'after_packages'     => 'After Popular Packages',
        'after_why'          => 'After Why Choose Us',
        'after_cta'          => 'After WhatsApp CTA (Bottom)',
    ];

    public function scopeActive($q)          { return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id'); }
    public function scopeAtPosition($q, $pos){ return $q->where('position', $pos); }
    public function getImageUrlAttribute()   { return $this->image ? asset('backend/admin/promo_banners/'.$this->image) : ''; }
}
