<?php

namespace App\Services;

use App\Models\UserTarget;
use App\Models\Booking;
use App\Models\CabBooking;
use App\Models\BoatBooking;

class TargetCalculationService
{
    /**
     * Calculate achieved margin for all booking types:
     * Stay: total_amount - proportional vendor cost (service assignments)
     * Cab:  total_amount - vendor_cost
     * Boat: final_amount - vendor_cost
     */
    public function calculateAchievedMargin(UserTarget $target): float
    {
        return $this->calculateDetailedMargin($target->user_id, $target->month, $target->year)['margin'];
    }

    /**
     * Returns total_amount, vendor_cost, margin broken down by booking type.
     */
    public function calculateDetailedMargin(int $userId, int $month, int $year): array
    {
        // ── Stay Bookings (service-assignment vendor costs) ────────
        $stayBookings = Booking::where('created_by', $userId)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->with(['serviceAssignments', 'payments'])
            ->get();

        $stayAmount = 0;
        $stayVendor = 0;
        foreach ($stayBookings as $b) {
            $totalAmount      = (float)($b->total_amount ?? 0);
            $paymentsReceived = (float)($b->payments->sum('amount') ?? 0);
            $vendorCost       = (float)($b->serviceAssignments->sum('assigned_cost') ?? 0);

            if ($totalAmount > 0) {
                $payPct       = $paymentsReceived / $totalAmount;
                $propVendor   = $vendorCost * $payPct;
            } else {
                $payPct     = 0;
                $propVendor = 0;
            }
            $stayAmount += $paymentsReceived;
            $stayVendor += $propVendor;
        }

        // ── Cab Bookings ───────────────────────────────────────────
        $cabBookings = CabBooking::where('created_by', $userId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $cabAmount = (float)$cabBookings->sum('total_amount');
        $cabVendor = (float)$cabBookings->sum('vendor_cost');

        // ── Boat Bookings ──────────────────────────────────────────
        $boatBookings = BoatBooking::where('created_by', $userId)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->get();

        $boatAmount = (float)$boatBookings->sum('final_amount');
        $boatVendor = (float)$boatBookings->sum('vendor_cost');

        // ── Totals ─────────────────────────────────────────────────
        $totalAmount = $stayAmount + $cabAmount + $boatAmount;
        $totalVendor = $stayVendor + $cabVendor + $boatVendor;
        $margin      = $totalAmount - $totalVendor;

        return [
            'total_amount' => $totalAmount,
            'vendor_cost'  => $totalVendor,
            'margin'       => $margin,
            'stay' => [
                'amount' => $stayAmount,
                'vendor' => $stayVendor,
                'margin' => $stayAmount - $stayVendor,
                'count'  => $stayBookings->count(),
            ],
            'cab' => [
                'amount' => $cabAmount,
                'vendor' => $cabVendor,
                'margin' => $cabAmount - $cabVendor,
                'count'  => $cabBookings->count(),
            ],
            'boat' => [
                'amount' => $boatAmount,
                'vendor' => $boatVendor,
                'margin' => $boatAmount - $boatVendor,
                'count'  => $boatBookings->count(),
            ],
        ];
    }

    public function updateAchievedMargin(UserTarget $target): UserTarget
    {
        $achievedMargin = $this->calculateAchievedMargin($target);
        $target->update(['achieved_margin' => $achievedMargin]);
        return $target->fresh();
    }

    public function calculateMultipleTargets($targets)
    {
        foreach ($targets as $target) {
            $target->achieved_margin = $this->calculateAchievedMargin($target);
        }
        return $targets;
    }

    public function getCurrentMonthTarget(int $userId): ?UserTarget
    {
        $target = UserTarget::forUser($userId)->currentMonth()->first();
        if ($target) {
            $target->achieved_margin = $this->calculateAchievedMargin($target);
        }
        return $target;
    }

    public function getLastMonthTarget(int $userId): ?UserTarget
    {
        $target = UserTarget::forUser($userId)->lastMonth()->first();
        if ($target) {
            $target->achieved_margin = $this->calculateAchievedMargin($target);
        }
        return $target;
    }

    public function getTeamCurrentMonthTargets()
    {
        $targets = UserTarget::with('user')->currentMonth()->get();
        return $this->calculateMultipleTargets($targets);
    }

    public function recalculateAllForPeriod(int $month, int $year): int
    {
        $targets = UserTarget::forPeriod($month, $year)->get();
        $count   = 0;
        foreach ($targets as $target) {
            $this->updateAchievedMargin($target);
            $count++;
        }
        return $count;
    }
}
