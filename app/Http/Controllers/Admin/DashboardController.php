<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Booking;
use App\Models\CabBooking;
use App\Models\BoatBooking;
use App\Models\Enquiry;
use App\Models\Expense;
use App\Models\UserTarget;
use App\Models\BookingPayment;
use App\Models\CabBookingPayment;
use App\Models\BoatBookingPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TargetCalculationService;
use App\Services\MonthlyMetricsService;

class DashboardController extends Controller
{
    public function index()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        $userId  = auth('admin')->id();

        $metricsService = app(MonthlyMetricsService::class);
        $thisMonthMetrics = $metricsService->compute($thisMonth, $isAdmin, $userId);
        extract($thisMonthMetrics);

        // ── Stay Bookings ─────────────────────────────────────────
        $stayQ = Booking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $stayToday     = (clone $stayQ)->whereDate('booking_date', $today)->count();
        $stayConfirmed = (clone $stayQ)->where('booking_status', 'confirmed')->count();
        $stayCompleted = (clone $stayQ)->where('booking_status', 'completed')->count();

        // ── Cab Bookings ──────────────────────────────────────────
        $cabQ = CabBooking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $cabToday = (clone $cabQ)->whereDate('created_at', $today)->count();

        // ── Boat Bookings ─────────────────────────────────────────
        $boatQ = BoatBooking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $boatToday   = (clone $boatQ)->whereDate('booking_date', $today)->count();
        $boatPending = (clone $boatQ)->where('payment_status', '!=', 'paid')->count();

        // ── Grand totals ──────────────────────────────────────────
        $totalBookingsToday = $stayToday + $cabToday + $boatToday;

        // ── Revenue growth vs last month ──────────────────────────
        $lastMonthMetrics = $metricsService->compute($lastMonth, $isAdmin, $userId);
        $totalRevLast  = $lastMonthMetrics['totalRevenueMonth'];
        $revenueGrowth = $totalRevLast > 0
            ? round((($totalRevenueMonth - $totalRevLast) / $totalRevLast) * 100, 1)
            : ($totalRevenueMonth > 0 ? 100 : 0);

        // ── 3-month revenue trend ─────────────────────────────────
        $monthLabels    = [];
        $stayRevTrend   = [];
        $cabRevTrend    = [];
        $boatRevTrend   = [];
        $totalRevTrend  = [];
        $bookingsTrend  = [];
        $totalExpTrend  = [];
        $totalProfitTrend = [];

        for ($i = 2; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $monthLabels[] = $m->format('M');

            $mMetrics = $i === 0 ? $thisMonthMetrics : $metricsService->compute($m, $isAdmin, $userId);

            $stayRevTrend[]     = round($mMetrics['stayRevMonth']);
            $cabRevTrend[]      = round($mMetrics['cabRevMonth']);
            $boatRevTrend[]     = round($mMetrics['boatRevMonth']);
            $totalRevTrend[]    = round($mMetrics['totalRevenueMonth']);
            $bookingsTrend[]    = $mMetrics['totalBookingsMonth'];
            $totalExpTrend[]    = round($mMetrics['totalExpenseMonth']);
            $totalProfitTrend[] = round($mMetrics['totalProfitMonth']);
        }

        // ── All-time stats ────────────────────────────────────────
        $allTimeRevenue  = (clone $stayQ)->sum('total_amount') + (clone $cabQ)->sum('total_amount') + (clone $boatQ)->sum('final_amount');
        $allTimeBookings = (clone $stayQ)->count() + (clone $cabQ)->count() + (clone $boatQ)->count();

        // ── Daily revenue — current month ──────────────────────────
        $dailyLabels  = [];
        $dailyRevenue = [];
        for ($d = Carbon::today()->startOfMonth(); $d->lte(Carbon::today()); $d->addDay()) {
            $dailyLabels[] = $d->format('d M');

            $dayStay = (clone $stayQ)->where('booking_status', '!=', 'cancelled')
                ->whereHas('lead', fn($q) => $q->whereDate('booking_start_date', $d))
                ->selectRaw('SUM(total_amount) as sale, SUM(pending_amount) as pending')->first();
            $dayCab = (clone $cabQ)->where('booking_status', '!=', 'cancelled')
                ->whereDate('pickup_date', $d)
                ->selectRaw('SUM(total_amount) as sale, SUM(pending_amount) as pending')->first();
            $dayBoatRows = (clone $boatQ)->where('booking_status', '!=', 'cancelled')
                ->whereDate('booking_date', $d)->withSum('payments', 'amount')->get();
            $dayBoatCollected = $dayBoatRows->sum(fn($b) => min($b->final_amount, $b->payments_sum_amount ?? 0));

            $dailyRevenue[] = round(
                ((float)($dayStay->sale ?? 0) - (float)($dayStay->pending ?? 0)) +
                ((float)($dayCab->sale ?? 0) - (float)($dayCab->pending ?? 0)) +
                $dayBoatCollected
            );
        }

        // ── Daily collection — last 30 days (actual payments received) ──
        $collectionLabels = [];
        $dailyCollection  = [];
        for ($d = Carbon::today()->copy()->subDays(29); $d->lte(Carbon::today()); $d->addDay()) {
            $collectionLabels[] = $d->format('d M');

            $stayPaid = BookingPayment::whereDate('payment_date', $d)
                ->whereHas('booking', fn($q) => $q->when(!$isAdmin, fn($q2) => $q2->where('created_by', $userId)))
                ->sum('amount');
            $cabPaid = CabBookingPayment::whereDate('payment_date', $d)
                ->whereHas('cabBooking', fn($q) => $q->when(!$isAdmin, fn($q2) => $q2->where('created_by', $userId)))
                ->sum('amount');
            $boatPaid = BoatBookingPayment::whereDate('created_at', $d)
                ->whereHas('boatBooking', fn($q) => $q->when(!$isAdmin, fn($q2) => $q2->where('created_by', $userId)))
                ->sum('amount');

            $dailyCollection[] = round($stayPaid + $cabPaid + $boatPaid);
        }

        // ── Recent bookings (all types merged) ────────────────────
        $recentStay = (clone $stayQ)->with(['lead', 'createdBy:id,name'])->latest()->limit(5)->get()->map(fn($b) => [
            'type'     => 'stay', 'icon' => '🏨',
            'number'   => $b->booking_number,
            'guest'    => $b->lead?->guest_name ?? '—',
            'amount'   => $b->total_amount,
            'status'   => $b->booking_status,
            'date'     => $b->booking_date,
            'added_by' => $b->createdBy?->name ?? '—',
            'url'      => route('bookings.show', $b->id),
        ]);

        $recentCab = (clone $cabQ)->with('createdBy:id,name')->latest()->limit(5)->get()->map(fn($b) => [
            'type'     => 'cab', 'icon' => '🚗',
            'number'   => $b->booking_number,
            'guest'    => $b->customer_name ?? '—',
            'amount'   => $b->total_amount,
            'status'   => $b->booking_status,
            'date'     => $b->created_at,
            'added_by' => $b->createdBy?->name ?? '—',
            'url'      => route('cab-bookings.show', $b->id),
        ]);

        $recentBoat = (clone $boatQ)->with('createdBy:id,name')->latest()->limit(5)->get()->map(fn($b) => [
            'type'     => 'boat', 'icon' => '⛵',
            'number'   => 'BT-' . str_pad($b->id, 4, '0', STR_PAD_LEFT),
            'guest'    => $b->name ?? '—',
            'amount'   => $b->final_amount,
            'status'   => $b->booking_status,
            'date'     => $b->created_at,
            'added_by' => $b->createdBy?->name ?? '—',
            'url'      => '#',
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

        // ── Today's bookings (running on today's date, not created today) ──
        // Stay/Package: guest is currently checked in (lead.booking_start_date ≤ today ≤ lead.booking_end_date)
        $todayStay = (clone $stayQ)->with(['lead', 'quotation', 'createdBy:id,name'])
            ->whereHas('lead', fn($q) =>
                $q->whereDate('booking_start_date', '<=', $today)
                  ->whereDate('booking_end_date',   '>=', $today)
            )
            ->where('booking_status', '!=', 'cancelled')
            ->get()
            ->map(function ($b) {
                $isPackage = false;
                if ($b->quotation && $b->quotation->notes) {
                    $decoded   = json_decode($b->quotation->notes, true);
                    $isPackage = is_array($decoded) && !empty($decoded['package_name']);
                }
                return [
                    'type'      => $isPackage ? 'Package' : 'Stay',
                    'icon'      => $isPackage ? '📦' : '🏨',
                    'color'     => $isPackage ? '#7C3AED' : '#4F46E5',
                    'bg'        => $isPackage ? '#EDE9FE' : '#EEF2FF',
                    'number'    => $b->booking_number,
                    'guest'     => $b->lead?->guest_name ?? '—',
                    'phone'     => $b->lead?->phone ?? '—',
                    'date_from' => $b->lead?->booking_start_date ? \Carbon\Carbon::parse($b->lead->booking_start_date)->format('d M Y') : '—',
                    'date_to'   => $b->lead?->booking_end_date   ? \Carbon\Carbon::parse($b->lead->booking_end_date)->format('d M Y')   : '—',
                    'amount'    => (int) $b->total_amount,
                    'paid'      => (int) $b->paid_amount,
                    'pending'   => (int) $b->pending_amount,
                    'status'    => $b->booking_status,
                    'added_by'  => $b->createdBy?->name ?? '—',
                    'url'       => route('bookings.show', $b->id),
                ];
            });

        // Cab: pickup is today OR multi-day trip spans today (pickup_date ≤ today ≤ return_date)
        $todayCab = (clone $cabQ)->with('createdBy:id,name')
            ->where(function ($q) use ($today) {
                $q->whereDate('pickup_date', $today)
                  ->orWhere(function ($q2) use ($today) {
                      $q2->whereDate('pickup_date', '<=', $today)
                         ->whereDate('return_date',  '>=', $today);
                  });
            })
            ->where('booking_status', '!=', 'cancelled')
            ->get()
            ->map(fn($b) => [
                'type'      => 'Cab',
                'icon'      => '🚗',
                'color'     => '#F59E0B',
                'bg'        => '#FEF3C7',
                'number'    => $b->booking_number,
                'guest'     => $b->customer_name ?? '—',
                'phone'     => $b->customer_phone ?? '—',
                'date_from' => $b->pickup_date  ? \Carbon\Carbon::parse($b->pickup_date)->format('d M Y')  : '—',
                'date_to'   => $b->return_date  ? \Carbon\Carbon::parse($b->return_date)->format('d M Y')  : '—',
                'amount'    => (int) $b->total_amount,
                'paid'      => (int) ($b->total_amount - $b->pending_amount),
                'pending'   => (int) $b->pending_amount,
                'status'    => $b->booking_status,
                'added_by'  => $b->createdBy?->name ?? '—',
                'url'       => route('cab-bookings.show', $b->id),
            ]);

        // Boat: single-day event — show rides scheduled for today
        $todayBoat = (clone $boatQ)->with('createdBy:id,name')
            ->whereDate('booking_date', $today)
            ->where('booking_status', '!=', 'cancelled')
            ->get()
            ->map(fn($b) => [
                'type'      => 'Boat',
                'icon'      => '⛵',
                'color'     => '#0EA5E9',
                'bg'        => '#E0F2FE',
                'number'    => 'BT-' . str_pad($b->id, 4, '0', STR_PAD_LEFT),
                'guest'     => $b->name ?? '—',
                'phone'     => $b->phone ?? '—',
                'date_from' => $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d M Y') : '—',
                'date_to'   => '—',
                'amount'    => (int) $b->final_amount,
                'paid'      => (int) $b->final_amount,
                'pending'   => 0,
                'status'    => $b->booking_status,
                'added_by'  => $b->createdBy?->name ?? '—',
                'url'       => route('boat-booking.show', $b->id),
            ]);

        $todayAllBookings = $todayStay->concat($todayCab)->concat($todayBoat)->values();

        // ── Enquiries ─────────────────────────────────────────────
        $enquiryCount = Enquiry::whereDate('created_at', $today)->count();

        // ── Staff Targets (current month) ─────────────────────────
        $targetService  = app(TargetCalculationService::class);
        $staffTargets   = collect();
        $myTargetDetail = null;   // detailed breakdown for the logged-in staff member

        if ($isAdmin) {
            $staffTargets = UserTarget::with('user')
                ->currentMonth()
                ->get();
            $staffTargets = $targetService->calculateMultipleTargets($staffTargets);
            $staffTargets = $staffTargets->sortByDesc('achieved_margin')->values();
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
            'totalRevTrend', 'bookingsTrend', 'totalExpTrend', 'totalProfitTrend',
            'totalExpenseMonth', 'totalProfitMonth', 'expenseSplit',
            'typeLabels', 'typeData',
            'dailyLabels', 'dailyRevenue',
            'collectionLabels', 'dailyCollection',
            'staffTargets',
            'teamTotalTarget', 'teamTotalAchieved', 'teamTargetPct',
            'isAdmin', 'myTargetDetail',
            'recentBookings', 'upcomingCheckins', 'upcomingCabs', 'todayAllBookings'
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

        $thisMonth = Carbon::now();

        $stayQ = Booking::query()->where('booking_status', '!=', 'cancelled')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $cabQ  = CabBooking::query()->where('booking_status', '!=', 'cancelled')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $boatQ = BoatBooking::query()->where('booking_status', '!=', 'cancelled')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $stayPending = (clone $stayQ)->with(['lead', 'createdBy'])
            ->where('pending_amount', '>', 0)
            ->whereHas('lead', fn($q) => $q->whereMonth('booking_start_date', $thisMonth->month)->whereYear('booking_start_date', $thisMonth->year))
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
            ->whereMonth('pickup_date', $thisMonth->month)->whereYear('pickup_date', $thisMonth->year)
            ->orderByDesc('pickup_date')
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
                'date'           => $b->pickup_date ? \Carbon\Carbon::parse($b->pickup_date)->format('d M Y') : '—',
                'url'            => route('cab-bookings.show', $b->id),
            ]);

        $boatPending = (clone $boatQ)->with('createdBy')
            ->where('payment_status', '!=', 'paid')
            ->whereMonth('booking_date', $thisMonth->month)->whereYear('booking_date', $thisMonth->year)
            ->withSum('payments', 'amount')
            ->orderByDesc('booking_date')
            ->get()
            ->map(function ($b) {
                $paid    = (float) ($b->payments_sum_amount ?? 0);
                $pending = max($b->final_amount - $paid, 0);
                return [
                    'type'           => 'Boat',
                    'icon'           => '🚤',
                    'number'         => $b->booking_id,
                    'guest'          => $b->name ?? '—',
                    'added_by'       => $b->createdBy?->name ?? '—',
                    'total_amount'   => (int) $b->final_amount,
                    'paid_amount'    => (int) $paid,
                    'pending_amount' => (int) $pending,
                    'status'         => $b->booking_status,
                    'date'           => $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d M Y') : '—',
                    'url'            => route('boat-booking.show', $b->booking_id),
                ];
            })
            ->filter(fn($b) => $b['pending_amount'] > 0)
            ->values();

        $bookings = $stayPending->concat($cabPending)->concat($boatPending)
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
