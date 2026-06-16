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
use App\Services\TargetCalculationService;

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
        $boatQ = BoatBooking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

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

        // ── Booking type distribution (donut, current month) ───────
        $typeLabels = ['Stay/Hotel', 'Cab', 'Boat'];
        $typeData   = [
            (clone $stayQ)->whereMonth('booking_date', $thisMonth->month)->whereYear('booking_date', $thisMonth->year)->count(),
            (clone $cabQ)->whereMonth('created_at', $thisMonth->month)->whereYear('created_at', $thisMonth->year)->count(),
            (clone $boatQ)->whereMonth('booking_date', $thisMonth->month)->whereYear('booking_date', $thisMonth->year)->count(),
        ];

        // ── Daily revenue — current month ──────────────────────────
        $dailyLabels  = [];
        $dailyRevenue = [];
        for ($d = Carbon::today()->startOfMonth(); $d->lte(Carbon::today()); $d->addDay()) {
            $dailyLabels[]  = $d->format('d M');
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

        // ── Stay status breakdown (current month) ──────────────────
        $stayStatuses = (clone $stayQ)
            ->whereMonth('booking_date', $thisMonth->month)
            ->whereYear('booking_date', $thisMonth->year)
            ->select('booking_status', DB::raw('count(*) as cnt'))
            ->groupBy('booking_status')
            ->pluck('cnt', 'booking_status');

        // ── Staff Targets (current month) ─────────────────────────
        $targetService  = app(TargetCalculationService::class);
        $staffTargets   = collect();
        $myTargetDetail = null;   // detailed breakdown for the logged-in staff member

        if ($isAdmin) {
            $staffTargets = UserTarget::with('user')
                ->currentMonth()
                ->orderBy('target_margin', 'desc')
                ->get();
            $staffTargets = $targetService->calculateMultipleTargets($staffTargets);
        } else {
            $myTarget = UserTarget::with('user')
                ->currentMonth()
                ->where('user_id', $userId)
                ->first();
            if ($myTarget) {
                $myTarget->achieved_margin = $targetService->calculateAchievedMargin($myTarget);
                $staffTargets   = collect([$myTarget]);
                $myTargetDetail = $targetService->calculateDetailedMargin($userId, now()->month, now()->year);
            }
        }

        // ── Team target aggregates ────────────────────────────────
        $teamTotalTarget   = $staffTargets->sum('target_margin');
        $teamTotalAchieved = $staffTargets->sum('achieved_margin');
        $teamTargetPct     = $teamTotalTarget > 0
            ? min(100, round($teamTotalAchieved / $teamTotalTarget * 100))
            : 0;

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
            'teamTotalTarget', 'teamTotalAchieved', 'teamTargetPct',
            'isAdmin', 'myTargetDetail',
            'recentBookings', 'upcomingCheckins', 'upcomingCabs'
        ), ['page_title' => 'Dashboard']);
    }

    public function staffBookings(int $userId)
    {
        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        if (!$isAdmin) abort(403);

        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $stayBookings = Booking::with('lead')
            ->where('created_by', $userId)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->orderByDesc('booking_date')
            ->get()
            ->map(fn($b) => [
                'type'           => 'Stay',
                'icon'           => '🏨',
                'number'         => $b->booking_number,
                'guest'          => $b->lead?->guest_name ?? '—',
                'total_amount'   => (int) $b->total_amount,
                'paid_amount'    => (int) ($b->total_amount - $b->pending_amount),
                'pending_amount' => (int) $b->pending_amount,
                'status'         => $b->booking_status,
                'date'           => $b->booking_date ? Carbon::parse($b->booking_date)->format('d M Y') : '—',
                'url'            => route('bookings.show', $b->id),
            ]);

        $cabBookings = CabBooking::where('created_by', $userId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($b) => [
                'type'           => 'Cab',
                'icon'           => '🚗',
                'number'         => $b->booking_number,
                'guest'          => $b->customer_name ?? '—',
                'total_amount'   => (int) $b->total_amount,
                'paid_amount'    => (int) ($b->total_amount - $b->pending_amount),
                'pending_amount' => (int) $b->pending_amount,
                'status'         => $b->booking_status,
                'date'           => $b->created_at ? Carbon::parse($b->created_at)->format('d M Y') : '—',
                'url'            => route('cab-bookings.show', $b->id),
            ]);

        $boatBookings = BoatBooking::where('created_by', $userId)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->orderByDesc('booking_date')
            ->get()
            ->map(fn($b) => [
                'type'           => 'Boat',
                'icon'           => '⛵',
                'number'         => 'BT-' . str_pad($b->id, 4, '0', STR_PAD_LEFT),
                'guest'          => $b->name ?? '—',
                'total_amount'   => (int) $b->final_amount,
                'paid_amount'    => (int) ($b->payment_status === 'paid' ? $b->final_amount : 0),
                'pending_amount' => (int) ($b->payment_status !== 'paid' ? $b->final_amount : 0),
                'status'         => $b->booking_status,
                'date'           => $b->booking_date ? Carbon::parse($b->booking_date)->format('d M Y') : '—',
                'url'            => route('boat-booking.show', $b->id),
            ]);

        $bookings = $stayBookings->concat($cabBookings)->concat($boatBookings)
            ->sortByDesc('date')
            ->values();

        return response()->json([
            'bookings' => $bookings,
            'summary'  => [
                'stay'  => $stayBookings->count(),
                'cab'   => $cabBookings->count(),
                'boat'  => $boatBookings->count(),
                'total' => $stayBookings->count() + $cabBookings->count() + $boatBookings->count(),
                'revenue' => $stayBookings->sum('total_amount') + $cabBookings->sum('total_amount') + $boatBookings->sum('total_amount'),
            ],
        ]);
    }

    public function pendingBookings()
    {
        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        $userId  = auth('admin')->id();

        $stayQ = Booking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $cabQ  = CabBooking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $stayPending = (clone $stayQ)->with(['lead', 'createdBy'])
            ->where('pending_amount', '>', 0)
            ->orderByDesc('booking_date')
            ->get()
            ->map(fn($b) => [
                'type'           => 'Stay',
                'icon'           => '🏨',
                'number'         => $b->booking_number,
                'guest'          => $b->lead?->guest_name ?? '—',
                'added_by'       => $b->createdBy?->name ?? '—',
                'total_amount'   => (int) $b->total_amount,
                'paid_amount'    => (int) ($b->total_amount - $b->pending_amount),
                'pending_amount' => (int) $b->pending_amount,
                'status'         => $b->booking_status,
                'date'           => $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d M Y') : '—',
                'url'            => route('bookings.show', $b->id),
            ]);

        $cabPending = (clone $cabQ)->with('createdBy')
            ->where('pending_amount', '>', 0)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($b) => [
                'type'           => 'Cab',
                'icon'           => '🚗',
                'number'         => $b->booking_number,
                'guest'          => $b->customer_name ?? '—',
                'added_by'       => $b->createdBy?->name ?? '—',
                'total_amount'   => (int) $b->total_amount,
                'paid_amount'    => (int) ($b->total_amount - $b->pending_amount),
                'pending_amount' => (int) $b->pending_amount,
                'status'         => $b->booking_status,
                'date'           => $b->created_at ? \Carbon\Carbon::parse($b->created_at)->format('d M Y') : '—',
                'url'            => route('cab-bookings.show', $b->id),
            ]);

        $bookings = $stayPending->concat($cabPending)
            ->sortByDesc('pending_amount')
            ->values();

        return response()->json($bookings);
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
