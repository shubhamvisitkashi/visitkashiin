<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserTarget;
use App\Models\Booking;
use App\Services\TargetCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTargetController extends Controller
{
    protected $targetService;

    public function __construct(TargetCalculationService $targetService)
    {
        $this->targetService = $targetService;
    }

    /**
     * Display a listing of targets
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $targets = UserTarget::with(['user', 'creator'])
            ->forPeriod($month, $year)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate achieved margins for all targets
        $targets = $this->targetService->calculateMultipleTargets($targets);

        // Get all admin users for the form
        $users = \App\Models\Admin\Admin::orderBy('name')->get();

        return view('admin.targets.index', compact('targets', 'users', 'month', 'year'), [
            'page_title' => 'Target Management'
        ]);
    }

    /**
     * Store a newly created target
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:admins,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'target_margin' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if target already exists
        $existing = UserTarget::where('user_id', $validated['user_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Target already exists for this user and period. Please edit the existing target.');
        }

        $target = UserTarget::create([
            'user_id' => $validated['user_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'target_margin' => $validated['target_margin'],
            'notes' => $validated['notes'],
            'created_by' => auth('admin')->id(),
        ]);

        // Calculate achieved margin using service
        $this->targetService->updateAchievedMargin($target);

        return back()->with('success', 'Target set successfully for ' . optional($target->user)->name . '!');
    }

    /**
     * Update the specified target
     */
    public function update(Request $request, $id)
    {
        $target = UserTarget::findOrFail($id);

        $validated = $request->validate([
            'target_margin' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $target->update($validated);

        // Recalculate achieved margin using service
        $this->targetService->updateAchievedMargin($target);

        return back()->with('success', 'Target updated successfully!');
    }

    /**
     * Remove the specified target
     */
    public function destroy($id)
    {
        $target = UserTarget::findOrFail($id);
        $target->delete();

        return back()->with('success', 'Target deleted successfully!');
    }

    /**
     * Calculate achieved margin for a target based on payments received
     * @deprecated Use TargetCalculationService instead
     */
    public function calculateAchievedMargin(UserTarget $target)
    {
        return $this->targetService->calculateAchievedMargin($target);
    }

    /**
     * Recalculate all targets for a specific period
     */
    public function recalculateAll(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $count = $this->targetService->recalculateAllForPeriod($month, $year);

        return back()->with('success', "All {$count} targets recalculated successfully!");
    }

    /**
     * Get target data for dashboard widget
     */
    public function myTargets()
    {
        $userId = auth('admin')->id();

        // Get targets with calculated margins using service
        $currentTarget = $this->targetService->getCurrentMonthTarget($userId);
        $lastTarget = $this->targetService->getLastMonthTarget($userId);

        return response()->json([
            'current' => $currentTarget,
            'last' => $lastTarget,
        ]);
    }

    /**
     * Get detailed breakdown of bookings for a target
     */
    public function breakdown($id)
    {
        $target = UserTarget::with('user')->findOrFail($id);

        // Get all bookings where service date falls in the target period (not booking creation date)
        $bookings = Booking::where('created_by', $target->user_id)
            ->whereHas('quotation.items', function($query) use ($target) {
                $query->whereYear('service_date', $target->year)
                      ->whereMonth('service_date', $target->month);
            })
            ->with(['lead', 'quotation.items', 'serviceAssignments', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        $breakdown = [];
        $totalMargin = 0;

        foreach ($bookings as $booking) {
            if (!$booking->quotation) continue;

            $totalAmount = $booking->total_amount ?? 0;
            $paymentsReceived = $booking->payments->sum('amount') ?? 0;
            $vendorCost = 0;

            // Calculate vendor costs
            foreach ($booking->serviceAssignments as $assignment) {
                $vendorCost += $assignment->assigned_cost ?? 0;
            }

            // Calculate margin based on payment percentage
            if ($totalAmount > 0) {
                $paymentPercentage = ($paymentsReceived / $totalAmount) * 100;
                $proportionalVendorCost = $vendorCost * ($paymentsReceived / $totalAmount);
                $margin = $paymentsReceived - $proportionalVendorCost;
            } else {
                $paymentPercentage = 0;
                $proportionalVendorCost = 0;
                $margin = 0;
            }

            $totalMargin += $margin;

            // Get customer name from lead relationship (use guest_name field)
            $customerName = 'N/A';
            if ($booking->lead) {
                $customerName = $booking->lead->guest_name ?? $booking->lead->company_name ?? 'N/A';
            }

            // Get service start date (earliest service date from quotation items)
            $serviceStartDate = null;
            if ($booking->quotation && $booking->quotation->items->count() > 0) {
                $serviceStartDate = $booking->quotation->items->min('service_date');
            }

            $breakdown[] = [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number ?? '#' . $booking->id,
                'customer_name' => $customerName,
                'booking_date' => $booking->created_at->format('d M Y, h:i A'),
                'service_start_date' => $serviceStartDate ? \Carbon\Carbon::parse($serviceStartDate)->format('d M Y') : 'N/A',
                'total_amount' => $totalAmount,
                'payments_received' => $paymentsReceived,
                'payment_percentage' => round($paymentPercentage, 1),
                'vendor_cost' => $vendorCost,
                'proportional_vendor_cost' => $proportionalVendorCost,
                'margin' => $margin,
                'services_count' => $booking->quotation->items->count(),
            ];
        }

        return view('admin.targets.breakdown', compact('target', 'breakdown', 'totalMargin'), [
            'page_title' => 'Target Breakdown'
        ]);
    }
}
