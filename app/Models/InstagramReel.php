<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramReel extends Model
{
    protected $fillable = ['product_id', 'title', 'reel_url', 'thumbnail', 'sort_order', 'status'];

    public function getReelIdAttribute(): string
    {
        preg_match('/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)\/?/', $this->reel_url, $m);
        return $m[1] ?? '';
    }

    public function scopeActive($q) { return $q->where('status', 'active'); }
}
