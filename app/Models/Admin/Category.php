<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'show_price',
        'on_home',
        'is_active',
        'term_and_condition'
    ];

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class, 'category_id')->where('is_active', 'active');
    }

    public function product()
    {
        return $this->hasMany(Product::class)->where('is_active', 'active');
    }
}
