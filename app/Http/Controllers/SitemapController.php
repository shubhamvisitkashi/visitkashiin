<?php

namespace App\Http\Controllers;

use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\SubCategory;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = config('app.url');
        $now     = now()->toAtomString();

        // Static pages
        $static = [
            ['loc' => $baseUrl,                   'priority' => '1.0', 'freq' => 'daily'],
            ['loc' => $baseUrl . '/login',        'priority' => '0.3', 'freq' => 'yearly'],
        ];

        // Category listing pages
        $categories = Category::where('is_active', 'active')->get();
        $catUrls = $categories->map(fn($c) => [
            'loc'      => $baseUrl . '/' . $c->slug,
            'priority' => '0.8',
            'freq'     => 'weekly',
        ]);

        // Sub-category listing pages
        $subCategories = SubCategory::with('category')->get();
        $subUrls = $subCategories->map(fn($s) => [
            'loc'      => $baseUrl . '/' . optional($s->category)->slug . '/' . $s->slug,
            'priority' => '0.7',
            'freq'     => 'weekly',
        ]);

        // Product detail pages
        $products = Product::where('is_active', 'active')
            ->with(['category', 'subCategory'])
            ->get();

        $productUrls = $products->map(function ($p) use ($baseUrl) {
            $cat = optional($p->category)->slug    ?? 'uncategorised';
            $sub = optional($p->subCategory)->slug ?? 'varanasi';
            return [
                'loc'      => $baseUrl . '/' . $cat . '/' . $sub . '/' . $p->slug,
                'priority' => '0.9',
                'freq'     => 'weekly',
                'image'    => !empty($p->images)
                    ? asset('backend/admin/product_images/' . $p->images[0])
                    : null,
                'title'    => $p->meta_title ?? $p->name,
            ];
        });

        $urls = collect($static)
            ->concat($catUrls)
            ->concat($subUrls)
            ->concat($productUrls);

        $xml = view('sitemap', compact('urls', 'now'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
