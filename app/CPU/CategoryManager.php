<?php

namespace App\CPU;

use Illuminate\Support\Str;
use App\Models\Admin\Category;

class CategoryManager
{

    public static function active()
    {
        return Category::where('is_active','active');
    }

}
