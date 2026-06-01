<?php

namespace App\Http\Controllers\Admin;

use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.hero_slides.index', compact('slides'), ['page_title' => 'Hero Slider']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'cta_url'   => 'nullable|string|max:255',
            'cta_label' => 'nullable|string|max:100',
        ]);

        $data = $request->only(['badge','title','tagline','cta_label','cta_url','sort_order']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(32) . '.' . $file->extension();
            $file->move(public_path('backend/admin/hero_slides'), $name);
            $data['image'] = $name;
        }

        HeroSlide::create($data);
        return redirect()->route('hero-slides.index')->with('success', 'Slide added successfully.');
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'cta_url' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['badge','title','tagline','cta_label','cta_url','sort_order']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            if ($heroSlide->image && file_exists(public_path('backend/admin/hero_slides/' . $heroSlide->image))) {
                @unlink(public_path('backend/admin/hero_slides/' . $heroSlide->image));
            }
            $file = $request->file('image');
            $name = Str::random(32) . '.' . $file->extension();
            $file->move(public_path('backend/admin/hero_slides'), $name);
            $data['image'] = $name;
        }

        $heroSlide->update($data);
        return redirect()->route('hero-slides.index')->with('success', 'Slide updated.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->image && file_exists(public_path('backend/admin/hero_slides/' . $heroSlide->image))) {
            @unlink(public_path('backend/admin/hero_slides/' . $heroSlide->image));
        }
        $heroSlide->delete();
        return back()->with('success', 'Slide deleted.');
    }

    public function toggleStatus(HeroSlide $heroSlide)
    {
        $heroSlide->update(['is_active' => !$heroSlide->is_active]);
        return back()->with('success', 'Status updated.');
    }
}
