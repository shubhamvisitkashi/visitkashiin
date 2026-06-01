<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $file     = $request->file('file');
        $filename = Str::random(40) . '.' . $file->extension();
        $dest     = public_path('backend/admin/product_images');

        if (!file_exists($dest)) {
            mkdir($dest, 0755, true);
        }

        $file->move($dest, $filename);

        return response()->json(['name' => $filename]);
    }
}
