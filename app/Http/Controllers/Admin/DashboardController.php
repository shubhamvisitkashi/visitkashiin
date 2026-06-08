<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Booking;
use App\Models\CabBooking;
use App\Models\BoatBooking;
use App\Models\Enquiry;
use App\Models\UserTarget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        $userId  = auth('admin')->id();

        // ── Stay Bookings ─────────────────────────────────────────
        $stayQ = Booking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $stayToday     = (clone $stayQ)->whereDate('booking_date', $today)->count();
        $stayMonth     = (clone $stayQ)->whereMonth('booking_date', $thisMonth->month)->whereYear('booking_date', $thisMonth->year)->count();
        $stayRevMonth  = (clone $stayQ)->whereMonth('booking_date', $thisMonth->month)->whereYear('booking_date', $thisMonth->year)->sum('total_amount');
        $stayPending   = (clone $stayQ)->where('pending_amount', '>', 0)->sum('pending_amount');
        $stayConfirmed = (clone $stayQ)->where('booking_status', 'confirmed')->count();
        $stayCompleted = (clone $stayQ)->where('booking_status', 'completed')->count();

        // ── Cab Bookings ──────────────────────────────────────────
        $cabQ = CabBooking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $cabToday    = (clone $cabQ)->whereDate('created_at', $today)->count();
        $cabMonth    = (clone $cabQ)->whereMonth('created_at', $thisMonth->month)->whereYear('created_at', $thisMonth->year)->count();
        $cabRevMonth = (clone $cabQ)->whereMonth('created_at', $thisMonth->month)->whereYear('created_at', $thisMonth->year)->sum('total_amount');
        $cabPending  = (clone $cabQ)->where('pending_amount', '>', 0)->sum('pending_amount');

        // ── Boat Bookings ─────────────────────────────────────────
        $boatQ = BoatBooking::query();

        $boatToday    = (clone $boatQ)->whereDate('booking_date', $today)->count();
        $boatMonth    = (clone $boatQ)->whereMonth('booking_date', $thisMonth->month)->whereYear('booking_date', $thisMonth->year)->count();
        $boatRevMonth = (clone $boatQ)->whereMonth('booking_date', $thisMonth->month)->whereYear('booking_date', $thisMonth->year)->sum('final_amount');
        $boatPending  = (clone $boatQ)->where('payment_status', '!=', 'paid')->count();

        // ── Grand totals ──────────────────────────────────────────
        $totalBookingsToday = $stayToday + $cabToday + $boatToday;
        $totalBookingsMonth = $stayMonth + $cabMonth + $boatMonth;
        $totalRevenueMonth  = $stayRevMonth + $cabRevMonth + $boatRevMonth;
        $totalPending       = $stayPending + $cabPending;
        $pendingPaymentsCount = (clone $stayQ)->where('pending_amount', '>', 0)->count()
            + (clone $cabQ)->where('pending_amount', '>', 0)->count();

        // ── Revenue growth vs last month ──────────────────────────
        $stayRevLast  = (clone $stayQ)->whereMonth('booking_date', $lastMonth->month)->whereYear('booking_date', $lastMonth->year)->sum('total_amount');
        $cabRevLast   = (clone $cabQ)->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->sum('total_amount');
        $boatRevLast  = (clone $boatQ)->whereMonth('booking_date', $lastMonth->month)->whereYear('booking_date', $lastMonth->year)->sum('final_amount');
        $totalRevLast = $stayRevLast + $cabRevLast + $boatRevLast;
        $revenueGrowth = $totalRevLast > 0
            ? round((($totalRevenueMonth - $totalRevLast) / $totalRevLast) * 100, 1)
            : ($totalRevenueMonth > 0 ? 100 : 0);

        // ── 3-month revenue trend ─────────────────────────────────
        $monthLabels   = [];
        $stayRevTrend  = [];
        $cabRevTrend   = [];
        $boatRevTrend  = [];
        $totalRevTrend = [];
        $bookingsTrend = [];

        for ($i = 2; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $monthLabels[] = $m->format('M');

            $sr = (clone $stayQ)->whereMonth('booking_date', $m->month)->whereYear('booking_date', $m->year)->sum('total_amount');
            $cr = (clone $cabQ)->whereMonth('created_at', $m->month)->whereYear('created_at', $m->year)->sum('total_amount');
            $br = (clone $boatQ)->whereMonth('booking_date', $m->month)->whereYear('booking_date', $m->year)->sum('final_amount');
            $bc = (clone $stayQ)->whereMonth('booking_date', $m->month)->whereYear('booking_date', $m->year)->count()
               + (clone $cabQ)->whereMonth('created_at', $m->month)->whereYear('created_at', $m->year)->count()
               + (clone $boatQ)->whereMonth('booking_date', $m->month)->whereYear('booking_date', $m->year)->count();

            $stayRevTrend[]  = round($sr);
            $cabRevTrend[]   = round($cr);
            $boatRevTrend[]  = round($br);
            $totalRevTrend[] = round($sr + $cr + $br);
            $bookingsTrend[] = $bc;
        }

        // ── All-time stats ────────────────────────────────────────
        $allTimeRevenue  = (clone $stayQ)->sum('total_amount') + (clone $cabQ)->sum('total_amount') + (clone $boatQ)->sum('final_amount');
        $allTimeBookings = (clone $stayQ)->count() + (clone $cabQ)->count() + (clone $boatQ)->count();

        // ── Booking type distribution (donut) ─────────────────────
        $typeLabels = ['Stay/Hotel', 'Cab', 'Boat'];
        $typeData   = [(clone $stayQ)->count(), (clone $cabQ)->count(), (clone $boatQ)->count()];

        // ── Daily revenue last 7 days ─────────────────────────────
        $dailyLabels  = [];
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $dailyLabels[]  = $d->format('D');
            $dailyRevenue[] = round(
                (clone $stayQ)->whereDate('booking_date', $d)->sum('total_amount') +
                (clone $cabQ)->whereDate('created_at', $d)->sum('total_amount') +
                (clone $boatQ)->whereDate('booking_date', $d)->sum('final_amount')
            );
        }

        // ── Recent bookings (all types merged) ────────────────────
        $recentStay = (clone $stayQ)->with('lead')->latest()->limit(5)->get()->map(fn($b) => [
            'type'   => 'stay', 'icon' => '🏨',
            'number' => $b->booking_number,
            'guest'  => $b->lead?->guest_name ?? '—',
            'amount' => $b->total_amount,
            'status' => $b->booking_status,
            'date'   => $b->booking_date,
            'url'    => route('bookings.show', $b->id),
        ]);

        $recentCab = (clone $cabQ)->latest()->limit(5)->get()->map(fn($b) => [
            'type'   => 'cab', 'icon' => '🚗',
            'number' => $b->booking_number,
            'guest'  => $b->customer_name ?? '—',
            'amount' => $b->total_amount,
            'status' => $b->booking_status,
            'date'   => $b->created_at,
            'url'    => route('cab-bookings.show', $b->id),
        ]);

        $recentBoat = (clone $boatQ)->latest()->limit(5)->get()->map(fn($b) => [
            'type'   => 'boat', 'icon' => '⛵',
            'number' => 'BT-' . str_pad($b->id, 4, '0', STR_PAD_LEFT),
            'guest'  => $b->name ?? '—',
            'amount' => $b->final_amount,
            'status' => $b->booking_status,
            'date'   => $b->created_at,
            'url'    => '#',
        ]);

        $recentBookings = $recentStay->concat($recentCab)->concat($recentBoat)
            ->sortByDesc('date')->take(8)->values();

        // ── Upcoming check-ins next 7 days ────────────────────────
        $upcomingCheckins = Booking::with('lead')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId))
            ->whereHas('lead', fn($q) => $q->whereBetween('booking_start_date', [$today, $today->copy()->addDays(7)]))
            ->where('booking_status', '!=', 'cancelled')
            ->orderBy('id')
            ->limit(6)
            ->get();

        // ── Upcoming cab pickups ───────────────────────────────────
        $upcomingCabs = (clone $cabQ)
            ->whereBetween('pickup_date', [$today, $today->copy()->addDays(7)])
            ->where('booking_status', '!=', 'cancelled')
            ->orderBy('pickup_date')
            ->limit(6)
            ->get();

        // ── Enquiries ─────────────────────────────────────────────
        $enquiryCount = Enquiry::whereDate('created_at', $today)->count();

        // ── Stay status breakdown ─────────────────────────────────
        $stayStatuses = (clone $stayQ)
            ->select('booking_status', DB::raw('count(*) as cnt'))
            ->groupBy('booking_status')
            ->pluck('cnt', 'booking_status');

        // ── Staff Targets (current month) ─────────────────────────
        $staffTargets = collect();
        if ($isAdmin) {
            // Super Admin / Admin / Manager sees all staff targets
            $staffTargets = UserTarget::with('user')
                ->currentMonth()
                ->orderBy('target_margin', 'desc')
                ->get();
        } else {
            // Staff sees only their own target
            $myTarget = UserTarget::with('user')
                ->currentMonth()
                ->where('user_id', $userId)
                ->first();
            if ($myTarget) {
                $staffTargets = collect([$myTarget]);
            }
        }

        return view('admin.dashboard', compact(
            'totalBookingsToday', 'totalBookingsMonth', 'totalRevenueMonth',
            'totalPending', 'revenueGrowth', 'allTimeRevenue', 'allTimeBookings',
            'pendingPaymentsCount', 'enquiryCount',
            'stayToday', 'cabToday', 'boatToday',
            'stayMonth', 'cabMonth', 'boatMonth',
            'stayRevMonth', 'cabRevMonth', 'boatRevMonth',
            'stayConfirmed', 'stayCompleted', 'boatPending',
            'monthLabels', 'stayRevTrend', 'cabRevTrend', 'boatRevTrend',
            'totalRevTrend', 'bookingsTrend',
            'typeLabels', 'typeData',
            'dailyLabels', 'dailyRevenue',
            'stayStatuses', 'staffTargets',
            'recentBookings', 'upcomingCheckins', 'upcomingCabs'
        ), ['page_title' => 'Dashboard']);
    }

    public function changeTheme(Request $request)
    {
        if (isset($request->theme_change)) {
            session()->put('selected_theme', 'Dark');
            $message = 'Dark Mode Applied!';
        } else {
            session()->put('selected_theme', 'Light');
            $message = 'Light Mode Applied!';
        }
        return back()->with('success', $message);
    }
}
