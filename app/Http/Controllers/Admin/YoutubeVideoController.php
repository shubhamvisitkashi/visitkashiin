<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YoutubeVideo;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class YoutubeVideoController extends Controller
{
    public function index()
    {
        $videos   = YoutubeVideo::with('product')->orderBy('sort_order')->orderByDesc('id')->paginate(20);
        $products = Product::where('is_active', 'active')->orderBy('name')->get();
        return view('admin.youtube-videos.index', compact('videos', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'  => 'nullable|integer',
            'title'       => 'nullable|string|max:255',
            'youtube_url' => 'required|url|max:500',
            'sort_order'  => 'nullable|integer',
            'status'      => 'required|in:active,inactive',
        ]);
        YoutubeVideo::create($data);
        return back()->with('success', 'Video added successfully.');
    }

    public function update(Request $request, YoutubeVideo $youtubeVideo)
    {
        $data = $request->validate([
            'product_id'  => 'nullable|integer',
            'title'       => 'nullable|string|max:255',
            'youtube_url' => 'required|url|max:500',
            'sort_order'  => 'nullable|integer',
            'status'      => 'required|in:active,inactive',
        ]);
        $youtubeVideo->update($data);
        return back()->with('success', 'Video updated successfully.');
    }

    public function destroy(YoutubeVideo $youtubeVideo)
    {
        $youtubeVideo->delete();
        return back()->with('success', 'Video deleted.');
    }

    public function toggleStatus(YoutubeVideo $youtubeVideo)
    {
        $youtubeVideo->update(['status' => $youtubeVideo->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Status updated.');
    }
}
