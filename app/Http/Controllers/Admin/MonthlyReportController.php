<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Booking;
use App\Models\CabBooking;
use App\Models\BoatBooking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MonthlyMetricsService;

class MonthlyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view');
    }

    public function index(Request $request)
    {
        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        $userId  = auth('admin')->id();

        $selectedMonth = Carbon::createFromFormat('Y-m', $request->input('month', now()->format('Y-m')))->startOfMonth();
        $prevMonth     = $selectedMonth->copy()->subMonth();
        $nextMonth     = $selectedMonth->copy()->addMonth();

        $metricsService = app(MonthlyMetricsService::class);
        $metrics      = $metricsService->compute($selectedMonth, $isAdmin, $userId);
        $lastMetrics  = $metricsService->compute($prevMonth, $isAdmin, $userId);

        $totalRevLast  = $lastMetrics['totalRevenueMonth'];
        $revenueGrowth = $totalRevLast > 0
            ? round((($metrics['totalRevenueMonth'] - $totalRevLast) / $totalRevLast) * 100, 1)
            : ($metrics['totalRevenueMonth'] > 0 ? 100 : 0);

        $monthOptions = $this->buildMonthOptions();

        return view('admin.monthly-report.index', array_merge($metrics, [
            'selectedMonth' => $selectedMonth,
            'prevMonth'     => $prevMonth,
            'nextMonth'     => $nextMonth,
            'monthOptions'  => $monthOptions,
            'revenueGrowth' => $revenueGrowth,
        ]), ['page_title' => 'Monthly Report']);
    }

    private function buildMonthOptions(): array
    {
        $dates = array_filter([
            Lead::whereNotNull('booking_start_date')->min('booking_start_date'),
            Lead::whereNotNull('booking_start_date')->max('booking_start_date'),
            CabBooking::whereNotNull('pickup_date')->min('pickup_date'),
            CabBooking::whereNotNull('pickup_date')->max('pickup_date'),
            BoatBooking::whereNotNull('booking_date')->min('booking_date'),
            BoatBooking::whereNotNull('booking_date')->max('booking_date'),
        ]);

        $earliest = $dates ? Carbon::parse(min($dates))->startOfMonth() : now()->startOfMonth();
        $latest   = $dates ? Carbon::parse(max($dates))->startOfMonth() : now()->startOfMonth();

        // Always include the current month, even if it has no data yet.
        if ($latest->lt(now()->startOfMonth())) {
            $latest = now()->startOfMonth();
        }

        $options = [];
        $cursor  = $latest->copy();
        while ($cursor->gte($earliest)) {
            $options[] = ['value' => $cursor->format('Y-m'), 'label' => $cursor->format('F Y')];
            $cursor->subMonth();
        }

        return $options;
    }
}
