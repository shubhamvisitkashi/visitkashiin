<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\LeadPayment;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class PaymentAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view|analytics-profit|payment-view');
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) $startDate = Carbon::parse($startDate);
        if (is_string($endDate)) $endDate = Carbon::parse($endDate);

        // === KEY METRICS ===
        
        // Total revenue collected
        $totalCollected = BookingPayment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');
        
        // Total outstanding
        $totalOutstanding = Booking::where('pending_amount', '>', 0)->sum('pending_amount');
        
        // Collection rate
        $totalBookingValue = Booking::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $collectionRate = $totalBookingValue > 0 ? round(($totalCollected / $totalBookingValue) * 100, 1) : 0;
        
        // Average payment time (days from booking to full payment)
        $avgPaymentTime = Booking::whereBetween('bookings.created_at', [$startDate, $endDate])
            ->where('pending_amount', 0)
            ->join('booking_payments', 'bookings.id', '=', 'booking_payments.booking_id')
            ->selectRaw('AVG(DATEDIFF(booking_payments.payment_date, bookings.created_at)) as avg_days')
            ->value('avg_days');
        $avgPaymentTime = round($avgPaymentTime ?? 0, 1);

        // === CASH FLOW TIMELINE ===
        $cashFlowData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $cashFlowData[] = [
                'date' => $date->format('M d'),
                'amount' => BookingPayment::whereDate('payment_date', $date)->sum('amount')
            ];
        }

        // === PAYMENT STATUS OVERVIEW ===
        $fullyPaid = Booking::where('pending_amount', 0)->count();
        $partiallyPaid = Booking::where('paid_amount', '>', 0)->where('pending_amount', '>', 0)->count();
        $unpaid = Booking::where('paid_amount', 0)->count();
        
        $paymentStatusLabels = ['Fully Paid', 'Partially Paid', 'Unpaid'];
        $paymentStatusData = [$fullyPaid, $partiallyPaid, $unpaid];

        // === PAYMENT METHOD DISTRIBUTION ===
        $paymentMethods = BookingPayment::whereBetween('payment_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // === OUTSTANDING BY AGE ===
        $now = Carbon::now();
        $outstanding = [
            '0-30 days' => Booking::where('pending_amount', '>', 0)
                ->whereBetween('created_at', [$now->copy()->subDays(30), $now])
                ->sum('pending_amount'),
            '31-60 days' => Booking::where('pending_amount', '>', 0)
                ->whereBetween('created_at', [$now->copy()->subDays(60), $now->copy()->subDays(31)])
                ->sum('pending_amount'),
            '61-90 days' => Booking::where('pending_amount', '>', 0)
                ->whereBetween('created_at', [$now->copy()->subDays(90), $now->copy()->subDays(61)])
                ->sum('pending_amount'),
            '90+ days' => Booking::where('pending_amount', '>', 0)
                ->where('created_at', '<', $now->copy()->subDays(90))
                ->sum('pending_amount'),
        ];

        // === OVERDUE PAYMENTS ===
        $overduePayments = Booking::with(['lead'])
            ->where('pending_amount', '>', 0)
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->orderBy('created_at')
            ->limit(15)
            ->get();

        // === RECENT PAYMENTS ===
        $recentPayments = BookingPayment::with(['booking.lead', 'paymentAccount'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->latest('payment_date')
            ->limit(15)
            ->get();

        // === MONTHLY COLLECTION TREND ===
        $monthlyCollection = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthlyCollection[] = [
                'month' => $month->format('M Y'),
                'collected' => BookingPayment::whereBetween('payment_date', [$monthStart, $monthEnd])->sum('amount')
            ];
        }

        return view('admin.payment-analytics.index', compact(
            'totalCollected',
            'totalOutstanding',
            'collectionRate',
            'avgPaymentTime',
            'cashFlowData',
            'paymentStatusLabels',
            'paymentStatusData',
            'paymentMethods',
            'outstanding',
            'overduePayments',
            'recentPayments',
            'monthlyCollection',
            'startDate',
            'endDate'
        ), ['page_title' => 'Payment & Cash Flow Analytics']);
    }
}
