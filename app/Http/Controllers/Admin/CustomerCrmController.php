<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Booking;
use App\Models\CabBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCrmController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:booking-list');
    }

    public function index(Request $request)
    {
        $search = $request->search;

        // Group bookings by phone to build customer profiles
        $customers = Lead::select(
                'contact',
                DB::raw('MAX(guest_name) as guest_name'),
                DB::raw('MAX(email) as email'),
                DB::raw('MAX(country) as country'),
                DB::raw('MAX(alt_phone) as alt_phone'),
                DB::raw('COUNT(*) as booking_count'),
                DB::raw('MAX(created_at) as last_booking'),
                DB::raw('MAX(total_amount) as last_amount')
            )
            ->when($search, fn($q) => $q->where(function($sq) use ($search) {
                $sq->where('guest_name', 'LIKE', "%{$search}%")
                   ->orWhere('contact', 'LIKE', "%{$search}%")
                   ->orWhere('email', 'LIKE', "%{$search}%");
            }))
            ->groupBy('contact')
            ->orderByDesc('last_booking')
            ->paginate(20);

        // Total stats
        $totalCustomers  = Lead::distinct('contact')->count('contact');
        $repeatCustomers = Lead::select('contact')->groupBy('contact')->havingRaw('COUNT(*) > 1')->count();
        $totalRevenue    = Booking::sum('total_amount');

        return view('admin.crm.index', compact(
            'customers', 'search',
            'totalCustomers', 'repeatCustomers', 'totalRevenue'
        ), ['page_title' => 'Customer CRM']);
    }

    public function show($phone)
    {
        $customer = Lead::where('contact', $phone)->orderByDesc('created_at')->first();
        if (!$customer) abort(404);

        $allLeads    = Lead::where('contact', $phone)->orderByDesc('created_at')->get();
        $allBookings = Booking::whereHas('lead', fn($q) => $q->where('contact', $phone))
            ->with(['lead', 'payments'])
            ->orderByDesc('booking_date')
            ->get();

        $cabBookings = CabBooking::where('customer_phone', $phone)->orderByDesc('created_at')->get();

        $totalSpent  = $allBookings->sum('total_amount') + $cabBookings->sum('total_amount');
        $totalPaid   = $allBookings->sum('paid_amount') + $cabBookings->sum('advance_paid');
        $totalPending= $allBookings->sum('pending_amount') + $cabBookings->sum('pending_amount');

        return view('admin.crm.show', compact(
            'customer', 'allLeads', 'allBookings', 'cabBookings',
            'totalSpent', 'totalPaid', 'totalPending'
        ), ['page_title' => 'Customer: ' . $customer->guest_name]);
    }
}
