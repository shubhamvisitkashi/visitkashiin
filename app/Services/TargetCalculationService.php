<?php

namespace App\Services;

use App\Models\UserTarget;
use App\Models\Booking;
use App\Models\CabBooking;
use App\Models\BoatBooking;

class TargetCalculationService
{
    public function calculateAchievedMargin(UserTarget $target): float
    {
        return $this->calculateDetailedMargin($target->user_id, $target->month, $target->year)['margin'];
    }

    /**
     * Breaks down margin by booking type:
     *   Stay    — Booking records where quotation.notes has no package_name
     *   Package — Booking records where quotation.notes has package_name (tour packages)
     *   Cab     — CabBooking: total_amount − vendor_cost
     *   Boat    — BoatBooking: final_amount − vendor_cost
     *   Guide   — reserved; returns 0 until a Guide model exists
     *
     * Stay & Package use proportional margin:
     *   payments_received − (vendor_cost × payments_received / total_amount)
     */
    public function calculateDetailedMargin(int $userId, int $month, int $year): array
    {
        // ── All Booking records for this staff member this month ───
        $allBookings = Booking::where('created_by', $userId)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->with(['quotation', 'serviceAssignments', 'payments'])
            ->get();

        $stayAmount = 0; $stayVendor = 0; $stayCount = 0;
        $pkgAmount  = 0; $pkgVendor  = 0; $pkgCount  = 0;

        foreach ($allBookings as $b) {
            $totalAmount  = (float)($b->total_amount ?? 0);
            $vendorCost   = (float)($b->serviceAssignments->sum('assigned_cost') ?? 0);
            $isCancelled  = $b->booking_status === 'cancelled';

            // For cancelled bookings: only the non-refund amount counts as revenue
            // (refund amount is returned to customer, so it reduces target revenue)
            if ($isCancelled) {
                $effectiveAmount = (float)($b->non_refund_amount ?? 0);
            } else {
                $effectiveAmount = (float)($b->payments->sum('amount') ?? 0);
            }

            $propVendor = $totalAmount > 0
                ? $vendorCost * ($effectiveAmount / $totalAmount)
                : 0;

            // Detect tour package by JSON package_name in quotation.notes
            $isPackage = false;
            if ($b->quotation && $b->quotation->notes) {
                $decoded   = json_decode($b->quotation->notes, true);
                $isPackage = is_array($decoded) && !empty($decoded['package_name']);
            }

            if ($isPackage) {
                $pkgAmount += $effectiveAmount;
                $pkgVendor += $propVendor;
                $pkgCount++;
            } else {
                $stayAmount += $effectiveAmount;
                $stayVendor += $propVendor;
                $stayCount++;
            }
        }

        // ── Cab Bookings ───────────────────────────────────────────
        $cabBookings = CabBooking::where('created_by', $userId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // For cancelled cab bookings: non_refund_amount counts (refund_amount is excluded)
        $cabAmount = (float)$cabBookings->sum(function ($b) {
            if ($b->booking_status === 'cancelled') {
                return (float)($b->non_refund_amount ?? 0);
            }
            return (float)($b->total_amount ?? 0);
        });
        $cabVendor = (float)$cabBookings->sum('vendor_cost');

        // ── Boat Bookings ──────────────────────────────────────────
        $boatBookings = BoatBooking::where('created_by', $userId)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->get();

        // For cancelled boat bookings: non_refund_amount counts (refund_amount is excluded)
        $boatAmount = (float)$boatBookings->sum(function ($b) {
            if ($b->booking_status === 'cancelled') {
                return (float)($b->non_refund_amount ?? 0);
            }
            return (float)($b->final_amount ?? 0);
        });
        $boatVendor = (float)$boatBookings->sum('vendor_cost');

        // ── Guide Bookings (reserved — no model yet) ───────────────
        $guideAmount = 0;
        $guideVendor = 0;
        $guideCount  = 0;

        // ── Grand totals ───────────────────────────────────────────
        $totalAmount = $stayAmount + $pkgAmount + $cabAmount + $boatAmount + $guideAmount;
        $totalVendor = $stayVendor + $pkgVendor + $cabVendor + $boatVendor + $guideVendor;
        $margin      = $totalAmount - $totalVendor;

        return [
            'total_amount' => $totalAmount,
            'vendor_cost'  => $totalVendor,
            'margin'       => $margin,
            'stay' => [
                'amount' => $stayAmount,
                'vendor' => $stayVendor,
                'margin' => $stayAmount - $stayVendor,
                'count'  => $stayCount,
            ],
            'package' => [
                'amount' => $pkgAmount,
                'vendor' => $pkgVendor,
                'margin' => $pkgAmount - $pkgVendor,
                'count'  => $pkgCount,
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
            'guide' => [
                'amount' => $guideAmount,
                'vendor' => $guideVendor,
                'margin' => 0,
                'count'  => $guideCount,
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
