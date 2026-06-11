<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Booking;
use App\Models\BookingPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class ExecutiveDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view|analytics-customer');
    }

    public function index(Request $request)
    {
        $now = Carbon::now();
        
        // === KEY METRICS (MTD & YTD) ===
        
        // Month to Date
        $mtdStart = $now->copy()->startOfMonth();
        $mtdRevenue = Booking::whereBetween('created_at', [$mtdStart, $now])->sum('total_amount');
        $mtdBookings = Booking::whereBetween('created_at', [$mtdStart, $now])->count();
        $mtdLeads = Lead::whereBetween('created_at', [$mtdStart, $now])->count();
        
        // Year to Date
        $ytdStart = $now->copy()->startOfYear();
        $ytdRevenue = Booking::whereBetween('created_at', [$ytdStart, $now])->sum('total_amount');
        $ytdBookings = Booking::whereBetween('created_at', [$ytdStart, $now])->count();
        
        // Last Month (for comparison)
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $lastMonthRevenue = Booking::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('total_amount');
        $lastMonthBookings = Booking::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        
        // Growth rates
        $revenueGrowth = $lastMonthRevenue > 0 ? round((($mtdRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;
        $bookingsGrowth = $lastMonthBookings > 0 ? round((($mtdBookings - $lastMonthBookings) / $lastMonthBookings) * 100, 1) : 0;
        
        // Active customers (unique contacts with bookings in last 6 months)
        $activeCustomers = Lead::where('created_at', '>=', $now->copy()->subMonths(6))
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->distinct('contact')
            ->count('contact');
        
        // Profit margin (from booking services if available)
        $totalCost = \App\Models\BookingService::whereBetween('created_at', [$mtdStart, $now])
            ->sum(DB::raw('quantity * cost_price'));
        $profitMargin = $mtdRevenue > 0 ? round((($mtdRevenue - $totalCost) / $mtdRevenue) * 100, 1) : 0;
        
        // Cash position (total collected - pending)
        $cashCollected = BookingPayment::sum('amount');
        $cashPending = Booking::sum('pending_amount');
        $cashPosition = $cashCollected - $cashPending;
        
        // === BUSINESS HEALTH SCORE ===
        $conversionRate = $mtdLeads > 0 ? ($mtdBookings / $mtdLeads) * 100 : 0;
        $healthScore = round((
            ($conversionRate > 20 ? 25 : ($conversionRate / 20) * 25) +
            ($revenueGrowth > 0 ? 25 : 0) +
            ($profitMargin > 20 ? 25 : ($profitMargin / 20) * 25) +
            ($cashPosition > 0 ? 25 : 0)
        ), 0);

        // === 12-MONTH TRENDS ===
        $trends = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $trends[] = [
                'month' => $month->format('M Y'),
                'revenue' => Booking::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount'),
                'bookings' => Booking::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'customers' => Lead::whereBetween('created_at', [$monthStart, $monthEnd])
                    ->whereNotNull('contact')->where('contact', '!=', '')->distinct('contact')->count('contact')
            ];
        }

        // === AUTO-GENERATED INSIGHTS ===
        $insights = [];
        
        if ($revenueGrowth > 10) {
            $insights[] = ['type' => 'success', 'message' => "Revenue up {$revenueGrowth}% this month! 🎉"];
        } elseif ($revenueGrowth < -10) {
            $insights[] = ['type' => 'warning', 'message' => "Revenue down {$revenueGrowth}% this month ⚠️"];
        }
        
        if ($conversionRate > 25) {
            $insights[] = ['type' => 'success', 'message' => "Excellent conversion rate: {$conversionRate}%"];
        } elseif ($conversionRate < 10) {
            $insights[] = ['type' => 'warning', 'message' => "Low conversion rate: {$conversionRate}% - needs attention"];
        }
        
        $overdueCount = Booking::where('pending_amount', '>', 0)
            ->where('created_at', '<', $now->copy()->subDays(30))->count();
        if ($overdueCount > 0) {
            $insights[] = ['type' => 'danger', 'message' => "{$overdueCount} overdue payments need collection"];
        }
        
        $stuckQuotations = Quotation::where('status', 'sent')
            ->where('created_at', '<', $now->copy()->subDays(7))->count();
        if ($stuckQuotations > 0) {
            $insights[] = ['type' => 'info', 'message' => "{$stuckQuotations} quotations sent but no response"];
        }

        // === ALERTS & WARNINGS ===
        $alerts = [
            'overdue_payments' => $overdueCount,
            'stuck_quotations' => $stuckQuotations,
            'low_cash' => $cashPosition < 0 ? 1 : 0
        ];

        // === RECENT ACTIVITY ===
        $recentBookings = Booking::with(['lead'])->latest()->limit(5)->get();
        $recentPayments = BookingPayment::with(['booking.lead'])->latest('payment_date')->limit(5)->get();

        return view('admin.executive-dashboard.index', compact(
            'mtdRevenue',
            'mtdBookings',
            'mtdLeads',
            'ytdRevenue',
            'ytdBookings',
            'revenueGrowth',
            'bookingsGrowth',
            'activeCustomers',
            'profitMargin',
            'cashPosition',
            'healthScore',
            'trends',
            'insights',
            'alerts',
            'recentBookings',
            'recentPayments'
        ), ['page_title' => 'Executive Dashboard']);
    }
}
