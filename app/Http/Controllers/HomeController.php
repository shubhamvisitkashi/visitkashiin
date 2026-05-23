<?php

namespace App\Http\Controllers;

use App\CPU\CategoryManager;
use Illuminate\Http\Request;
use App\Models\Admin\Product;

class HomeController extends Controller
{
    public function index(){

        $on_home_categories = CategoryManager::active()->where('on_home','1')->with(['product.category','product.subCategory'])->get();
        $on_home_products = Product::where('is_active','active')->where('on_home','1')->with(['category','subCategory'])->get();
        $search_list = Product::where('is_active', 'active')->with(['category', 'subCategory'])->take(10)->get()->shuffle();
        return view('frontend.index',compact('on_home_categories','on_home_products','search_list'));
    }
}
