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
        $bookingsList = $this->buildBookingsList($selectedMonth, $isAdmin, $userId);

        $staffBreakdown = $bookingsList
            ->groupBy('added_by')
            ->map(fn ($rows, $name) => [
                'name'    => $name,
                'cnt'     => $rows->count(),
                'revenue' => $rows->sum('collected'),
            ])
            ->sortByDesc('revenue')
            ->values();

        return view('admin.monthly-report.index', array_merge($metrics, [
            'selectedMonth'   => $selectedMonth,
            'prevMonth'       => $prevMonth,
            'nextMonth'       => $nextMonth,
            'monthOptions'    => $monthOptions,
            'revenueGrowth'   => $revenueGrowth,
            'bookingsList'    => $bookingsList,
            'staffBreakdown'  => $staffBreakdown,
        ]), ['page_title' => 'Monthly Report']);
    }

    /**
     * Every individual stay/cab/boat booking whose service date falls in the
     * given month — lets you eyeball that the KPI totals above add up.
     */
    private function buildBookingsList(Carbon $month, bool $isAdmin, ?int $userId): \Illuminate\Support\Collection
    {
        $stayQ = Booking::query()->where('booking_status', '!=', 'cancelled')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $cabQ  = CabBooking::query()->where('booking_status', '!=', 'cancelled')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $boatQ = BoatBooking::query()->where('booking_status', '!=', 'cancelled')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $all = collect();

        (clone $stayQ)->with(['lead', 'createdBy:id,name'])
            ->whereHas('lead', fn($q) => $q->whereMonth('booking_start_date', $month->month)->whereYear('booking_start_date', $month->year))
            ->get()
            ->each(function ($b) use (&$all) {
                $all->push([
                    'type'      => 'Stay', 'icon' => '🏨',
                    'number'    => $b->booking_number,
                    'guest'     => $b->lead?->guest_name ?? '—',
                    'amount'    => (float) $b->total_amount,
                    'pending'   => (float) $b->pending_amount,
                    'collected' => (float) $b->total_amount - (float) $b->pending_amount,
                    'status'    => $b->booking_status,
                    'date'      => $b->lead?->booking_start_date,
                    'added_by'  => $b->createdBy?->name ?? '—',
                    'url'       => route('bookings.show', $b->id),
                ]);
            });

        (clone $cabQ)->with('createdBy:id,name')
            ->whereMonth('pickup_date', $month->month)->whereYear('pickup_date', $month->year)
            ->get()
            ->each(function ($b) use (&$all) {
                $all->push([
                    'type'      => 'Cab', 'icon' => '🚗',
                    'number'    => $b->booking_number,
                    'guest'     => $b->customer_name ?? '—',
                    'amount'    => (float) $b->total_amount,
                    'pending'   => (float) $b->pending_amount,
                    'collected' => (float) $b->total_amount - (float) $b->pending_amount,
                    'status'    => $b->booking_status,
                    'date'      => $b->pickup_date,
                    'added_by'  => $b->createdBy?->name ?? '—',
                    'url'       => route('cab-bookings.show', $b->id),
                ]);
            });

        (clone $boatQ)->with('createdBy:id,name')
            ->whereMonth('booking_date', $month->month)->whereYear('booking_date', $month->year)
            ->withSum('payments', 'amount')
            ->get()
            ->each(function ($b) use (&$all) {
                $paid = (float) ($b->payments_sum_amount ?? 0);
                $all->push([
                    'type'      => 'Boat', 'icon' => '⛵',
                    'number'    => 'BT-' . str_pad($b->id, 4, '0', STR_PAD_LEFT),
                    'guest'     => $b->name ?? '—',
                    'amount'    => (float) $b->final_amount,
                    'pending'   => max($b->final_amount - $paid, 0),
                    'collected' => min($paid, $b->final_amount),
                    'status'    => $b->booking_status,
                    'date'      => $b->booking_date,
                    'added_by'  => $b->createdBy?->name ?? '—',
                    'url'       => route('boat-booking.show', $b->id),
                ]);
            });

        return $all->sortByDesc('date')->values();
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
