<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\SubCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductApiController extends Controller
{
    public function productList()
    {
        return response()->json([
            'products'   => ProductResource::collection(Product::where('is_active', 'active')->with(['category', 'subCategory'])->get())
        ], 200);
    }

    public function productListbySlug($slug)
    {
        $subcategory = SubCategory::where('slug', $slug)->first();
        return response()->json([
            'subcategory'     => $subcategory,
            'products_list'   => ProductResource::collection(Product::where('subcategory_id', $subcategory->id)->where('is_active', 'active')->with(['category', 'subCategory'])->get())
        ], 200);
    }

    public function productDetail($slug)
    {
        return response()->json([
            'products_detail'   => new ProductResource(Product::where('slug', $slug)->where('is_active', 'active')->with(['category', 'subCategory'])->first())
        ], 200);
    }
}
