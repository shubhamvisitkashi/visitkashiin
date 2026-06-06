<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\CabBooking;
use App\Models\BoatBooking;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:booking-list');
    }

    public function index(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to   = $request->to   ? Carbon::parse($request->to)->endOfDay()     : Carbon::now()->endOfDay();
        $type = $request->type ?? 'all';

        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        $userId  = auth('admin')->id();

        // ── Stay bookings in range ────────────────────────────────
        $stayQ = Booking::with(['lead', 'payments'])
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId))
            ->whereBetween('booking_date', [$from, $to]);

        $stayRevenue  = (clone $stayQ)->sum('total_amount');
        $stayPaid     = (clone $stayQ)->sum('paid_amount');
        $stayPending  = (clone $stayQ)->sum('pending_amount');
        $stayCount    = (clone $stayQ)->count();

        // ── Cab bookings in range ─────────────────────────────────
        $cabQ = CabBooking::when(!$isAdmin, fn($q) => $q->where('created_by', $userId))
            ->whereBetween('created_at', [$from, $to]);

        $cabRevenue  = (clone $cabQ)->sum('total_amount');
        $cabPaid     = (clone $cabQ)->sum('advance_paid');
        $cabPending  = (clone $cabQ)->sum('pending_amount');
        $cabCount    = (clone $cabQ)->count();

        // ── Boat bookings in range ────────────────────────────────
        $boatQ = BoatBooking::whereBetween('created_at', [$from, $to]);
        $boatRevenue = (clone $boatQ)->sum('final_amount');
        $boatCount   = (clone $boatQ)->count();

        // ── Totals ────────────────────────────────────────────────
        $totalRevenue = $stayRevenue + $cabRevenue + $boatRevenue;
        $totalPaid    = $stayPaid + $cabPaid;
        $totalPending = $stayPending + $cabPending;
        $totalCount   = $stayCount + $cabCount + $boatCount;

        // ── Daily revenue breakdown for chart ─────────────────────
        $days = [];
        $dayRevenue = [];
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $days[] = $d->format('d M');
            $dayRevenue[] = round(
                (clone $stayQ)->whereDate('booking_date', $d)->sum('total_amount') +
                (clone $cabQ)->whereDate('created_at', $d)->sum('total_amount') +
                (clone $boatQ)->whereDate('booking_date', $d)->sum('final_amount')
            );
        }
        // Cap to 60 points for readability
        if (count($days) > 60) { $days = []; $dayRevenue = []; }

        // ── Booking list for table (latest 100) ───────────────────
        $stayBookings = (clone $stayQ)->with('lead')->orderByDesc('booking_date')->limit(50)->get()->map(fn($b) => [
            'type'    => 'Stay',
            'number'  => $b->booking_number,
            'guest'   => $b->lead?->guest_name ?? '—',
            'phone'   => $b->lead?->contact ?? '—',
            'date'    => $b->booking_date?->format('d M Y'),
            'amount'  => $b->total_amount,
            'paid'    => $b->paid_amount,
            'pending' => $b->pending_amount,
            'status'  => $b->booking_status,
            'url'     => route('bookings.show', $b->id),
        ]);

        $cabBookings = (clone $cabQ)->orderByDesc('created_at')->limit(50)->get()->map(fn($b) => [
            'type'    => 'Cab',
            'number'  => $b->booking_number,
            'guest'   => $b->customer_name ?? '—',
            'phone'   => $b->customer_phone ?? '—',
            'date'    => $b->created_at?->format('d M Y'),
            'amount'  => $b->total_amount,
            'paid'    => $b->advance_paid,
            'pending' => $b->pending_amount,
            'status'  => $b->booking_status,
            'url'     => route('cab-bookings.show', $b->id),
        ]);

        $allBookings = $stayBookings->concat($cabBookings)->sortByDesc('date')->values();

        // ── Payment method breakdown ──────────────────────────────
        $payMethods = BookingPayment::select('payment_method', DB::raw('sum(amount) as total'), DB::raw('count(*) as cnt'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.index', compact(
            'from', 'to', 'type',
            'totalRevenue', 'totalPaid', 'totalPending', 'totalCount',
            'stayRevenue', 'stayCount', 'cabRevenue', 'cabCount', 'boatRevenue', 'boatCount',
            'days', 'dayRevenue',
            'allBookings',
            'payMethods'
        ), ['page_title' => 'Reports & Analytics']);
    }
}
