<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\CabBooking;
use App\Models\BoatBooking;
use App\Models\Expense;

class MonthlyMetricsService
{
    /**
     * Booking/Cab/Boat figures for a single calendar month, scoped by the date
     * the service is actually delivered (stay check-in, cab pickup, boat ride),
     * not the date the sale was recorded.
     */
    public function compute(Carbon $month, bool $isAdmin, ?int $userId): array
    {
        $stayQ = Booking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $cabQ  = CabBooking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $boatQ = BoatBooking::query()->when(!$isAdmin, fn($q) => $q->where('created_by', $userId));

        $stayServiceMonth = fn($q) => $q->whereHas('lead', fn($q2) =>
            $q2->whereMonth('booking_start_date', $month->month)->whereYear('booking_start_date', $month->year)
        );
        $cabServiceMonth = fn($q) => $q->whereMonth('pickup_date', $month->month)->whereYear('pickup_date', $month->year);
        $boatServiceMonth = fn($q) => $q->whereMonth('booking_date', $month->month)->whereYear('booking_date', $month->year);

        $stayMonth    = $stayServiceMonth((clone $stayQ))->count();
        $stayRevMonth = $stayServiceMonth((clone $stayQ))->sum('total_amount');
        $stayPending  = $stayServiceMonth((clone $stayQ)->where('pending_amount', '>', 0))->sum('pending_amount');

        $cabMonth    = $cabServiceMonth((clone $cabQ))->count();
        $cabRevMonth = $cabServiceMonth((clone $cabQ))->sum('total_amount');
        $cabPending  = $cabServiceMonth((clone $cabQ)->where('pending_amount', '>', 0))->sum('pending_amount');

        $boatMonth    = $boatServiceMonth((clone $boatQ))->count();
        $boatRevMonth = $boatServiceMonth((clone $boatQ))->sum('final_amount');

        $boatPendingRows = $boatServiceMonth((clone $boatQ)->where('payment_status', '!=', 'paid'))
            ->withSum('payments', 'amount')
            ->get();
        $boatPendingAmount = $boatPendingRows->sum(fn($b) => max($b->final_amount - ($b->payments_sum_amount ?? 0), 0));
        $boatPendingCount  = $boatPendingRows->count();

        $totalBookingsMonth = $stayMonth + $cabMonth + $boatMonth;
        $totalRevenueMonth  = $stayRevMonth + $cabRevMonth + $boatRevMonth;
        $totalPending       = $stayPending + $cabPending + $boatPendingAmount;
        $pendingPaymentsCount = $stayServiceMonth((clone $stayQ)->where('pending_amount', '>', 0))->count()
            + $cabServiceMonth((clone $cabQ)->where('pending_amount', '>', 0))->count()
            + $boatPendingCount;

        $manualExpenseMonth = (float) Expense::whereMonth('expense_date', $month->month)->whereYear('expense_date', $month->year)->sum('amount');

        $stayExpData = $stayServiceMonth((clone $stayQ))->selectRaw('SUM(vendor_cost) as exp')->first();
        $cabExpData  = $cabServiceMonth((clone $cabQ))->selectRaw('SUM(vendor_cost) as exp')->first();
        $boatExpData = $boatServiceMonth((clone $boatQ))->selectRaw('SUM(vendor_cost) as exp')->first();

        $stayExpMonth = (float)($stayExpData->exp ?? 0) ?: round($stayRevMonth * 0.30);
        $cabExpMonth  = (float)($cabExpData->exp ?? 0) ?: round($cabRevMonth * 0.30);
        $boatExpMonth = (float)($boatExpData->exp ?? 0) ?: round($boatRevMonth * 0.30);

        $totalExpenseMonth = $stayExpMonth + $cabExpMonth + $boatExpMonth + $manualExpenseMonth;
        $totalProfitMonth  = $totalRevenueMonth - $totalExpenseMonth;

        $expenseSplit = [
            ['label' => 'Stay Cost',       'val' => $stayExpMonth],
            ['label' => 'Cab Cost',        'val' => $cabExpMonth],
            ['label' => 'Boat Cost',       'val' => $boatExpMonth],
            ['label' => 'Manual Expenses', 'val' => $manualExpenseMonth],
        ];

        $typeLabels = ['Stay/Hotel', 'Cab', 'Boat'];
        $typeData   = [$stayMonth, $cabMonth, $boatMonth];

        return compact(
            'stayMonth', 'cabMonth', 'boatMonth', 'totalBookingsMonth',
            'stayRevMonth', 'cabRevMonth', 'boatRevMonth', 'totalRevenueMonth',
            'stayPending', 'cabPending', 'boatPendingAmount', 'totalPending', 'pendingPaymentsCount',
            'stayExpMonth', 'cabExpMonth', 'boatExpMonth', 'manualExpenseMonth', 'totalExpenseMonth', 'totalProfitMonth',
            'expenseSplit', 'typeLabels', 'typeData'
        );
    }
}
