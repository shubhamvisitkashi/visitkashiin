<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryApiController extends Controller
{
    public function categoriesList()
    {
        return response()->json([
            'categories'   => CategoryResource::collection(Category::where('is_active', 'active')->with('subCategory')->get())
        ], 200);
    }
}
