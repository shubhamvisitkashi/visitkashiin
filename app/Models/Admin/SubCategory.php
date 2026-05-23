<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'meta_title',
        'meta_keyword',
        'meta_description'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    public function product(){
        return $this->hasMany(Product::class,'subcategory_id');
    }
}
