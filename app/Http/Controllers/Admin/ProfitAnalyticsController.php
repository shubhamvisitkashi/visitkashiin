<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class ProfitAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // ── Anchor to actual data range ──────────────────────────────────
        $earliestBooking = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->min('booking_start_date');

        $latestBooking = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->max('booking_start_date');

        $anchor = $latestBooking ? Carbon::parse($latestBooking) : Carbon::now();

        // Default: full actual data range (not current month which may be empty)
        $defaultStart = $earliestBooking
            ? Carbon::parse($earliestBooking)->format('Y-m-d')
            : Carbon::now()->startOfYear()->format('Y-m-d');

        $defaultEnd = $latestBooking
            ? Carbon::parse($latestBooking)->format('Y-m-d')
            : Carbon::now()->format('Y-m-d');

        $startDate = $request->input('start_date', $defaultStart);
        $endDate   = $request->input('end_date',   $defaultEnd);

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        // ── Base query closure ────────────────────────────────────────────
        $baseFilters = function($q) use ($start, $end) {
            $q->where('booking_status', 'complete')
              ->whereNotNull('booking_start_date')
              ->whereBetween('booking_start_date', [$start, $end]);
        };

        // ── KPI Metrics ───────────────────────────────────────────────────
        $totalRevenue  = (float) Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->sum('total_amount');

        $totalBookings = (int) Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->count();

        // Sum actual expenses; for records with blank expense estimate 30% of their revenue
        $withExpense = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->where('total_expense', '>', 0)
            ->selectRaw('SUM(total_amount) as rev, SUM(total_expense) as exp')
            ->first();

        $withoutExpenseRev = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->where(function($q){ $q->whereNull('total_expense')->orWhere('total_expense', 0); })
            ->sum('total_amount');

        $totalExpense = (float)($withExpense->exp ?? 0) + round($withoutExpenseRev * 0.30);

        $totalProfit  = $totalRevenue - $totalExpense;
        $profitMargin = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;
        $avgDeal      = $totalBookings > 0 ? round($totalRevenue / $totalBookings) : 0;

        // ── Monthly trend — 6 months anchored to latest actual data ───────
        $monthlyTrend = [];
        $anchorMonth  = Carbon::parse($anchor)->startOfMonth();

        for ($i = 5; $i >= 0; $i--) {
            $m = $anchorMonth->copy()->subMonths($i);

            $mRev = (float) Lead::where('booking_status', 'complete')
                ->whereNotNull('booking_start_date')
                ->whereYear('booking_start_date',  $m->year)
                ->whereMonth('booking_start_date', $m->month)
                ->sum('total_amount');

            $mExp = (float) Lead::where('booking_status', 'complete')
                ->whereNotNull('booking_start_date')
                ->where('total_expense', '>', 0)
                ->whereYear('booking_start_date',  $m->year)
                ->whereMonth('booking_start_date', $m->month)
                ->sum('total_expense');

            if ($mExp == 0 && $mRev > 0) $mExp = round($mRev * 0.30);

            $monthlyTrend[] = [
                'month'   => $m->format('M Y'),
                'revenue' => $mRev,
                'expense' => $mExp,
                'profit'  => $mRev - $mExp,
            ];
        }

        // ── Lead source breakdown ─────────────────────────────────────────
        $sourceBreakdown = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->select('lead_source_id',
                DB::raw('COUNT(*) as cnt'),
                DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('lead_source_id')
            ->orderByDesc('revenue')
            ->with('leadSource')
            ->get()
            ->map(function ($r) use ($totalRevenue) {
                return [
                    'label'   => optional($r->leadSource)->name ?? 'Direct',
                    'cnt'     => $r->cnt,
                    'revenue' => (float) $r->revenue,
                    'pct'     => $totalRevenue > 0
                        ? round(($r->revenue / $totalRevenue) * 100, 1) : 0,
                ];
            });

        // ── Added-by staff breakdown ──────────────────────────────────────
        $staffBreakdown = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->select('added_by',
                DB::raw('COUNT(*) as cnt'),
                DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('added_by')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get()
            ->map(function ($r) {
                $admin = \App\Models\Admin\Admin::find($r->added_by);
                return [
                    'name'    => $admin ? $admin->name : 'Unknown',
                    'cnt'     => $r->cnt,
                    'revenue' => (float) $r->revenue,
                ];
            });

        // ── Top bookings by revenue ───────────────────────────────────────
        $topBookings = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get(['id', 'guest_name', 'contact', 'total_amount',
                   'total_expense', 'booking_start_date', 'pax', 'short_plan']);

        // ── Recent completed bookings ─────────────────────────────────────
        $recentBookings = Lead::where('booking_status', 'complete')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->orderByDesc('booking_start_date')
            ->limit(15)
            ->get(['id', 'guest_name', 'contact', 'total_amount',
                   'total_expense', 'booking_start_date', 'pax', 'short_plan', 'added_by']);

        return view('admin.profit-analytics.index', compact(
            'totalRevenue', 'totalExpense', 'totalProfit', 'profitMargin',
            'totalBookings', 'avgDeal',
            'monthlyTrend', 'sourceBreakdown', 'staffBreakdown',
            'topBookings', 'recentBookings',
            'startDate', 'endDate', 'anchor'
        ));
    }

    public function exportReport(Request $request)
    {
        return response()->json(['message' => 'Export coming soon']);
    }
}
