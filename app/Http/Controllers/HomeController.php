<?php

namespace App\Http\Controllers;

use App\CPU\CategoryManager;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\HeroSlide;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(){

        $on_home_categories = cache()->remember('home_categories', 1800, function () {
            return CategoryManager::active()->where('on_home','1')->with(['product.category','product.subCategory'])->get();
        });

        $on_home_products = cache()->remember('home_products', 1800, function () {
            return Product::where('is_active','active')->where('on_home','1')->with(['category','subCategory'])->get();
        });

        $search_list = cache()->remember('home_search_list', 1800, function () {
            return Product::where('is_active', 'active')->with(['category', 'subCategory'])->take(10)->get();
        });

        $service_images = cache()->remember('home_service_images', 1800, function () {
            $slugs = ['hotels', 'cab', 'boat', 'packages'];
            $result = [];
            foreach ($slugs as $slug) {
                $product = Product::whereHas('category', fn($q) => $q->where('slug', $slug))
                    ->where('is_active', 'active')
                    ->whereNotNull('images')
                    ->first();
                if ($product && !empty($product->images)) {
                    $result[$slug] = reset($product->images);
                }
            }
            return $result;
        });

        $weather = cache()->remember('varanasi_weather', 1800, function () {
            try {
                $res = Http::timeout(4)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude'       => 25.3176,
                    'longitude'      => 82.9739,
                    'current'        => 'temperature_2m,weather_code,wind_speed_10m,relative_humidity_2m',
                    'daily'          => 'temperature_2m_max,temperature_2m_min,sunrise,sunset',
                    'timezone'       => 'Asia/Kolkata',
                    'forecast_days'  => 1,
                ]);
                return $res->ok() ? $res->json() : null;
            } catch (\Exception $e) {
                return null;
            }
        });

        try {
            $hero_slides = HeroSlide::active()->get();
        } catch (\Exception $e) {
            $hero_slides = collect();
        }

        return view('frontend.index', compact('on_home_categories','on_home_products','search_list','weather','service_images','hero_slides'));
    }
}
