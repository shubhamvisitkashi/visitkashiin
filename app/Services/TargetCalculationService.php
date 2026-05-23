<?php

namespace App\Services;

use App\Models\UserTarget;
use App\Models\Booking;

class TargetCalculationService
{
    /**
     * Calculate achieved margin for a target based on payments received and service dates
     * 
     * @param UserTarget $target
     * @return float
     */
    public function calculateAchievedMargin(UserTarget $target): float
    {
        // Get all bookings where service date falls in the target month/year (not booking creation date)
        $bookings = Booking::where('created_by', $target->user_id)
            ->whereHas('quotation.items', function($query) use ($target) {
                $query->whereYear('service_date', $target->year)
                      ->whereMonth('service_date', $target->month);
            })
            ->with(['quotation.items', 'serviceAssignments', 'payments'])
            ->get();

        $totalMargin = 0;

        foreach ($bookings as $booking) {
            if (!$booking->quotation) continue;

            // Get total amount and payments received
            $totalAmount = $booking->total_amount ?? 0;
            $paymentsReceived = $booking->payments->sum('amount') ?? 0;
            
            // Calculate vendor costs
            $vendorCost = 0;
            foreach ($booking->serviceAssignments as $assignment) {
                $vendorCost += $assignment->assigned_cost ?? 0;
            }

            // Calculate margin based on payment percentage
            // If total amount is 100K and 20K received (20%), and vendor cost is 50K
            // Then margin = 20K - (50K × 20%) = 20K - 10K = 10K
            if ($totalAmount > 0) {
                $paymentPercentage = $paymentsReceived / $totalAmount;
                $proportionalVendorCost = $vendorCost * $paymentPercentage;
                $margin = $paymentsReceived - $proportionalVendorCost;
            } else {
                $margin = 0;
            }

            $totalMargin += $margin;
        }

        return $totalMargin;
    }

    /**
     * Calculate and update achieved margin for a target
     * 
     * @param UserTarget $target
     * @return UserTarget
     */
    public function updateAchievedMargin(UserTarget $target): UserTarget
    {
        $achievedMargin = $this->calculateAchievedMargin($target);
        $target->update(['achieved_margin' => $achievedMargin]);
        
        return $target->fresh();
    }

    /**
     * Calculate achieved margin for multiple targets
     * 
     * @param \Illuminate\Support\Collection $targets
     * @return \Illuminate\Support\Collection
     */
    public function calculateMultipleTargets($targets)
    {
        foreach ($targets as $target) {
            $achievedMargin = $this->calculateAchievedMargin($target);
            $target->achieved_margin = $achievedMargin;
        }

        return $targets;
    }

    /**
     * Get current month target for a user with calculated margin
     * 
     * @param int $userId
     * @return UserTarget|null
     */
    public function getCurrentMonthTarget(int $userId): ?UserTarget
    {
        $target = UserTarget::forUser($userId)
            ->currentMonth()
            ->first();

        if ($target) {
            $achievedMargin = $this->calculateAchievedMargin($target);
            $target->achieved_margin = $achievedMargin;
        }

        return $target;
    }

    /**
     * Get last month target for a user with calculated margin
     * 
     * @param int $userId
     * @return UserTarget|null
     */
    public function getLastMonthTarget(int $userId): ?UserTarget
    {
        $target = UserTarget::forUser($userId)
            ->lastMonth()
            ->first();

        if ($target) {
            $achievedMargin = $this->calculateAchievedMargin($target);
            $target->achieved_margin = $achievedMargin;
        }

        return $target;
    }

    /**
     * Get all team targets for current month with calculated margins
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getTeamCurrentMonthTargets()
    {
        $targets = UserTarget::with('user')->currentMonth()->get();
        
        return $this->calculateMultipleTargets($targets);
    }

    /**
     * Recalculate all targets for a specific period
     * 
     * @param int $month
     * @param int $year
     * @return int Number of targets recalculated
     */
    public function recalculateAllForPeriod(int $month, int $year): int
    {
        $targets = UserTarget::forPeriod($month, $year)->get();
        $count = 0;

        foreach ($targets as $target) {
            $this->updateAchievedMargin($target);
            $count++;
        }

        return $count;
    }
}
