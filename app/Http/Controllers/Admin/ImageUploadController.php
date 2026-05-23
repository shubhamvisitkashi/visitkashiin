<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function upload(Request $request){
        $image = $request->file('file');
        $filename = time().rand(10, 99).'.'.$image->extension();
        $image->move(public_path('backend/admin/product_images'), $filename);
        $imagesNames[] = $filename;

        return response()->json([
            'name'          => $filename,
        ]);
    }

}
