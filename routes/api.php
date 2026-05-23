<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('website-setup', [App\Http\Controllers\Api\WebsiteSetupApiController::class, 'websiteStup']);

Route::get('categories', [App\Http\Controllers\Api\CategoryApiController::class, 'categoriesList']);

Route::get('product-list', [App\Http\Controllers\Api\ProductApiController::class, 'productList']);
Route::get('get-product-list/{slug}', [App\Http\Controllers\Api\ProductApiController::class, 'productListbySlug']);
Route::get('product-detail/{slug}', [App\Http\Controllers\Api\ProductApiController::class, 'productDetail']);

Route::get('package-list', [App\Http\Controllers\Api\PackageApiController::class, 'packageList']);
Route::get('package-detail/{slug}', [App\Http\Controllers\Api\PackageApiController::class, 'getPackage']);
