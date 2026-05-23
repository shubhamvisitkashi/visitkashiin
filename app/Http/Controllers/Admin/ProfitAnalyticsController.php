<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookingService;
use App\Models\ServiceType;
use App\Models\ServiceProvider;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class ProfitAnalyticsController extends Controller
{
    /**
     * Display profit analytics dashboard
     */
    public function index(Request $request)
    {
        // Date range filter
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Service type filter
        $serviceTypeId = $request->input('service_type_id');
        
        // Provider type filter (vendor/own)
        $providerType = $request->input('provider_type');

        // Build base query
        $query = BookingService::with(['lead', 'serviceItem.serviceProvider', 'serviceType'])
            ->whereBetween('service_date', [$startDate, $endDate]);

        if ($serviceTypeId) {
            $query->where('service_type_id', $serviceTypeId);
        }

        if ($providerType) {
            $query->whereHas('serviceItem.serviceProvider', function($q) use ($providerType) {
                $q->where('type', $providerType);
            });
        }

        // Key Metrics
        $totalRevenue = $query->sum(DB::raw('quantity * selling_price'));
        $totalCost = $query->sum(DB::raw('quantity * cost_price'));
        $totalProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 2) : 0;

        // Service-wise profit breakdown
        $serviceWiseProfit = BookingService::select(
                'service_type_id',
                DB::raw('SUM(quantity * selling_price) as total_revenue'),
                DB::raw('SUM(quantity * cost_price) as total_cost'),
                DB::raw('SUM(quantity * profit_amount) as total_profit')
            )
            ->with('serviceType')
            ->whereBetween('service_date', [$startDate, $endDate])
            ->groupBy('service_type_id')
            ->get();

        // Vendor vs Own profit
        $vendorOwnProfit = BookingService::select(
                'service_items.service_provider_id',
                'service_providers.type',
                DB::raw('SUM(booking_services.quantity * booking_services.selling_price) as total_revenue'),
                DB::raw('SUM(booking_services.quantity * booking_services.cost_price) as total_cost'),
                DB::raw('SUM(booking_services.quantity * booking_services.profit_amount) as total_profit')
            )
            ->join('service_items', 'booking_services.service_item_id', '=', 'service_items.id')
            ->join('service_providers', 'service_items.service_provider_id', '=', 'service_providers.id')
            ->whereBetween('booking_services.service_date', [$startDate, $endDate])
            ->groupBy('service_providers.type', 'service_items.service_provider_id')
            ->get()
            ->groupBy('type');

        // Monthly profit trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthProfit = BookingService::whereBetween('service_date', [$monthStart, $monthEnd])
                ->sum(DB::raw('quantity * profit_amount'));
            
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'profit' => $monthProfit
            ];
        }

        // Top profitable services
        $topServices = BookingService::select(
                'service_item_id',
                DB::raw('SUM(quantity * profit_amount) as total_profit'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->with('serviceItem')
            ->whereBetween('service_date', [$startDate, $endDate])
            ->groupBy('service_item_id')
            ->orderByDesc('total_profit')
            ->limit(10)
            ->get();

        // Recent bookings with profit details
        $recentBookings = BookingService::with(['lead', 'serviceItem.serviceProvider', 'serviceType'])
            ->whereBetween('service_date', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        // Get filters data
        $serviceTypes = ServiceType::active()->orderBy('name')->get();

        return view('admin.profit-analytics.index', compact(
            'totalRevenue',
            'totalCost',
            'totalProfit',
            'profitMargin',
            'serviceWiseProfit',
            'vendorOwnProfit',
            'monthlyTrend',
            'topServices',
            'recentBookings',
            'serviceTypes',
            'startDate',
            'endDate',
            'serviceTypeId',
            'providerType'
        ));
    }

    /**
     * Export profit report (future implementation)
     */
    public function exportReport(Request $request)
    {
        // TODO: Implement Excel/PDF export
        return response()->json(['message' => 'Export functionality coming soon']);
    }
}
