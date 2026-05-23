<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Admin\Package;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;

class PackageApiController extends Controller
{
    public function packageList()
    {
        return response()->json([
            'packages'   => PackageResource::collection(Package::where('is_active', 'active')->with(['category', 'subCategory'])->get())
        ], 200);
    }

    public function getPackage($slug)
    {
        return response()->json([
            'packages'   => new PackageResource(Package::where('slug', $slug)->where('is_active', 'active')->with(['category', 'subCategory'])->first())
        ], 200);
    }
}
