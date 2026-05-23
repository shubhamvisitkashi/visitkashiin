<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'images'   => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class, 'subcategory_id')->withTrashed();
    }
}
