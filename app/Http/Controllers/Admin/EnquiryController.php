<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Enquiry;
use App\Models\HotelEnquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:enquiry-list', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $search_date_range = $request->daterange;
        $search_key        = $request->search_key;
        $category          = $request->get('category', 'all');

        // ── Hotel tab: query hotel_enquiries table ──────────────────────────────
        if ($category === 'hotel') {
            $query = HotelEnquiry::orderBy('id', 'desc');

            if ($search_date_range) {
                [$d1, $d2] = array_pad(explode('-', $search_date_range), 2, null);
                if ($d1 && $d2) {
                    $query->whereBetween('created_at', [
                        Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($d1)))->startOfDay(),
                        Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($d2)))->endOfDay(),
                    ]);
                }
            }

            if ($search_key) {
                $query->where(function ($q) use ($search_key) {
                    $q->where('guest_name', 'like', "%{$search_key}%")
                      ->orWhere('contact_number', 'like', "%{$search_key}%")
                      ->orWhere('hotel_name', 'like', "%{$search_key}%");
                });
            }

            $hotelEnquiries = $query->paginate(15);
            $enquires       = null; // not used in hotel view

        } else {
            // ── All other tabs: query enquiries table joined with categories ──
            $hotelEnquiries = null;

            $base = Enquiry::select(
                    'enquiries.*',
                    'categories.slug as cat_slug',
                    'categories.name as cat_name',
                    'products.base_price as product_base_price',
                    'products.discounted_price as product_discounted_price'
                )
                ->leftJoin('products', 'enquiries.package_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->orderBy('enquiries.id', 'desc');

            if ($category === 'cab') {
                $base->where('categories.slug', 'cab');
            } elseif ($category === 'boat') {
                $base->where('categories.slug', 'boat');
            } elseif ($category === 'tour') {
                $base->whereNotIn('categories.slug', ['hotels', 'homestay', 'cab', 'boat'])
                     ->whereNotNull('categories.slug');
            }

            if ($search_date_range) {
                [$d1, $d2] = array_pad(explode('-', $search_date_range), 2, null);
                if ($d1 && $d2) {
                    $base->whereBetween('enquiries.created_at', [
                        Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($d1)))->startOfDay(),
                        Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($d2)))->endOfDay(),
                    ]);
                }
            }

            if ($search_key) {
                $base->where(function ($q) use ($search_key) {
                    $q->where('enquiries.name', 'like', "%{$search_key}%")
                      ->orWhere('enquiries.phone', 'like', "%{$search_key}%");
                });
            }

            $enquires = $base->paginate(15);
        }

        // ── Tab counts ──────────────────────────────────────────────────────────
        $countBase = fn() => Enquiry::leftJoin('products', 'enquiries.package_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

        $counts = [
            'all'   => Enquiry::count(),
            'hotel' => HotelEnquiry::count(),
            'cab'   => $countBase()->where('categories.slug', 'cab')->count(),
            'boat'  => $countBase()->where('categories.slug', 'boat')->count(),
            'tour'  => $countBase()->whereNotIn('categories.slug', ['hotels', 'homestay', 'cab', 'boat'])
                                   ->whereNotNull('categories.slug')->count(),
        ];

        return view('admin.enquiry.index', compact(
            'enquires', 'hotelEnquiries', 'search_date_range', 'search_key', 'category', 'counts'
        ), ['page_title' => 'Enquiries']);
    }

    public function destroy($id)
    {
        $enquiry = Enquiry::find($id);
        if ($enquiry) {
            $enquiry->delete();
            return back()->with('success', 'Enquiry Deleted Successfully!');
        }
        return back()->with('error', 'No Enquiry Found!');
    }

    public function destroyHotel($id)
    {
        $enquiry = HotelEnquiry::find($id);
        if ($enquiry) {
            $enquiry->delete();
            return back()->with('success', 'Hotel Enquiry Deleted Successfully!');
        }
        return back()->with('error', 'No Enquiry Found!');
    }
}
