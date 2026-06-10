<?php
namespace App\Http\Controllers\Admin;
use App\Models\PromoBanner;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PromoBannerController extends Controller
{
    public function index()
    {
        $banners = PromoBanner::orderBy('position')->orderBy('sort_order')->get();
        return view('admin.promo_banners.index', compact('banners'), ['page_title' => 'Promo Banners']);
    }

    public function store(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096']);
        $data = $request->only(['title','subtitle','link','position','sort_order']);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = (int)($data['sort_order'] ?? 0);
        $data['position']    = $data['position'] ?: 'after_hero';
        $file = $request->file('image');
        $name = Str::random(32).'.'.$file->extension();
        $file->move(public_path('backend/admin/promo_banners'), $name);
        $data['image'] = $name;
        PromoBanner::create($data);
        return redirect()->route('promo-banners.index')->with('success','Promo banner added.');
    }

    public function update(Request $request, PromoBanner $promoBanner)
    {
        $data = $request->only(['title','subtitle','link','position','sort_order']);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        if ($request->hasFile('image')) {
            if ($promoBanner->image && file_exists(public_path('backend/admin/promo_banners/'.$promoBanner->image)))
                @unlink(public_path('backend/admin/promo_banners/'.$promoBanner->image));
            $file = $request->file('image');
            $name = Str::random(32).'.'.$file->extension();
            $file->move(public_path('backend/admin/promo_banners'), $name);
            ImageCompressor::compress(public_path('backend/admin/promo_banners/' . $name));
            $data['image'] = $name;
        }
        $promoBanner->update($data);
        return redirect()->route('promo-banners.index')->with('success','Updated.');
    }

    public function destroy(PromoBanner $promoBanner)
    {
        if ($promoBanner->image && file_exists(public_path('backend/admin/promo_banners/'.$promoBanner->image)))
            @unlink(public_path('backend/admin/promo_banners/'.$promoBanner->image));
        $promoBanner->delete();
        return back()->with('success','Deleted.');
    }

    public function toggleStatus(PromoBanner $promoBanner)
    {
        $promoBanner->update(['is_active' => !$promoBanner->is_active]);
        return back()->with('success','Status updated.');
    }
}
