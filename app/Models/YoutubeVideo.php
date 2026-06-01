<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YoutubeVideo extends Model
{
    protected $fillable = ['product_id', 'title', 'youtube_url', 'sort_order', 'status'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Admin\Product::class, 'product_id');
    }

    public function getVideoIdAttribute(): string
    {
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|shorts\/))([a-zA-Z0-9_-]{11})/', $this->youtube_url, $m);
        return $m[1] ?? '';
    }

    public function getThumbnailAttribute(): string
    {
        $id = $this->video_id;
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : '';
    }

    public function getEmbedUrlAttribute(): string
    {
        $id = $this->video_id;
        return $id ? "https://www.youtube.com/embed/{$id}?autoplay=1&rel=0" : '';
    }

    public function scopeActive($q) { return $q->where('status', 'active'); }
}
