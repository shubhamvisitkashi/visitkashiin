<?php

namespace App\Http\Controllers\Admin;

use App\Models\WebsiteSetup;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class WebsiteSetupController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:web_setup', ['only' => ['index', 'store']]);
    }

    public function index()
    {
        try {
            $heroSlides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();
        } catch (\Exception $e) {
            $heroSlides = collect();
        }
        return view('admin.web_site_setup.index', ['page_title' => 'Website Setup', 'heroSlides' => $heroSlides]);
    }

    public function create() {}

    public function store(Request $request)
    {
        $request->validate([
            'logo'           => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg|max:2048',
            'favicon'        => 'nullable|file|mimes:ico,png,jpg,jpeg|max:512',
            'banner'         => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'footer_logo'    => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg|max:2048',
            'promo_banner'   => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'promo_banner_2' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'promo_banner_3' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $imageTypes = ['logo', 'favicon', 'banner', 'footer_logo', 'promo_banner', 'promo_banner_2', 'promo_banner_3'];
        $input      = $request->all();

        foreach ($input['type'] as $types) {
            if (isset($input[$types])) {
                if (in_array($types, $imageTypes)) {
                    $file      = $request->file($types);
                    $ext       = $file->extension();
                    $filename  = Str::random(40) . '.' . $ext;
                    $dest      = public_path('backend/admin/website_setup');

                    if (!file_exists($dest)) {
                        mkdir($dest, 0755, true);
                    }

                    $file->move($dest, $filename);
                    $input[$types] = $filename;
                }

                WebsiteSetup::updateOrCreate(
                    ['name' => $types],
                    ['name' => $types, 'value' => $input[$types]]
                );
            } else {
                if (!in_array($types, $imageTypes)) {
                    WebsiteSetup::updateOrCreate(
                        ['name' => $types],
                        ['name' => $types, 'value' => null]
                    );
                }
            }
        }

        cache()->forget('website_setup_all');

        return redirect()->back()->with('success', 'Website setup updated successfully.');
    }

    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
