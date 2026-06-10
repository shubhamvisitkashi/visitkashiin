<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramReel;
use App\Models\Admin\Product;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstagramReelController extends Controller
{
    public function index()
    {
        $reels    = InstagramReel::orderBy('sort_order')->orderByDesc('id')->paginate(20);
        $products = Product::where('is_active', 'active')->orderBy('name')->get();
        return view('admin.instagram-reels.index', compact('reels', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'nullable|integer',
            'title'      => 'nullable|string|max:255',
            'reel_url'   => 'required|url|max:500',
            'sort_order' => 'nullable|integer',
            'status'     => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('instagram_thumbs', 'public');
            ImageCompressor::compress(storage_path('app/public/' . $path));
            $data['thumbnail'] = $path;
        }

        InstagramReel::create($data);
        return back()->with('success', 'Reel added successfully.');
    }

    public function update(Request $request, InstagramReel $instagramReel)
    {
        $data = $request->validate([
            'product_id' => 'nullable|integer',
            'title'      => 'nullable|string|max:255',
            'reel_url'   => 'required|url|max:500',
            'sort_order' => 'nullable|integer',
            'status'     => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($instagramReel->thumbnail) Storage::disk('public')->delete($instagramReel->thumbnail);
            $path = $request->file('thumbnail')->store('instagram_thumbs', 'public');
            ImageCompressor::compress(storage_path('app/public/' . $path));
            $data['thumbnail'] = $path;
        }

        $instagramReel->update($data);
        return back()->with('success', 'Reel updated.');
    }

    public function destroy(InstagramReel $instagramReel)
    {
        if ($instagramReel->thumbnail) Storage::disk('public')->delete($instagramReel->thumbnail);
        $instagramReel->delete();
        return back()->with('success', 'Reel deleted.');
    }

    public function toggleStatus(InstagramReel $instagramReel)
    {
        $instagramReel->update(['status' => $instagramReel->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Status updated.');
    }
}
