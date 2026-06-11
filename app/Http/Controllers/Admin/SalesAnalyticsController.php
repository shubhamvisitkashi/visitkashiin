<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Booking;
use App\Models\LeadSource;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class SalesAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view|analytics-customer');
    }

    public function index(Request $request)
    {
        // Date range filter
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) $startDate = Carbon::parse($startDate);
        if (is_string($endDate)) $endDate = Carbon::parse($endDate);

        // === KEY METRICS ===
        
        // Total leads generated
        $totalLeads = Lead::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Total quotations sent
        $totalQuotations = Quotation::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Total bookings
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Conversion rates
        $leadToQuotationRate = $totalLeads > 0 ? round(($totalQuotations / $totalLeads) * 100, 1) : 0;
        $quotationToBookingRate = $totalQuotations > 0 ? round(($totalBookings / $totalQuotations) * 100, 1) : 0;
        $overallConversionRate = $totalLeads > 0 ? round(($totalBookings / $totalLeads) * 100, 1) : 0;
        
        // Total revenue
        $totalRevenue = Booking::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        
        // Average deal size
        $avgDealSize = $totalBookings > 0 ? round($totalRevenue / $totalBookings, 2) : 0;
        
        // Average sales cycle (days from lead to booking)
        $salesCycleDays = Lead::whereBetween('leads.created_at', [$startDate, $endDate])
            ->join('bookings', 'leads.id', '=', 'bookings.lead_id')
            ->selectRaw('AVG(DATEDIFF(bookings.created_at, leads.created_at)) as avg_days')
            ->value('avg_days');
        $salesCycleDays = round($salesCycleDays ?? 0, 1);

        // === SALES FUNNEL ===
        $funnelData = [
            'labels' => ['Leads', 'Quotations', 'Bookings', 'Completed'],
            'data' => [
                $totalLeads,
                $totalQuotations,
                $totalBookings,
                Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->where('booking_status', 'completed')->count()
            ]
        ];

        // === SALESPERSON PERFORMANCE ===
        $salespeople = Lead::whereBetween('leads.created_at', [$startDate, $endDate])
            ->join('admins', 'leads.added_by', '=', 'admins.id')
            ->select(
                'admins.id',
                'admins.name',
                DB::raw('COUNT(DISTINCT leads.id) as total_leads'),
                DB::raw('COUNT(DISTINCT CASE WHEN leads.booking_status = "complete" THEN leads.id END) as converted_leads'),
                DB::raw('SUM(CASE WHEN leads.booking_status = "complete" THEN leads.total_amount ELSE 0 END) as total_revenue')
            )
            ->groupBy('admins.id', 'admins.name')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function($person) {
                $person->conversion_rate = $person->total_leads > 0 
                    ? round(($person->converted_leads / $person->total_leads) * 100, 1) 
                    : 0;
                $person->avg_deal_size = $person->converted_leads > 0 
                    ? round($person->total_revenue / $person->converted_leads, 2) 
                    : 0;
                return $person;
            });

        // === LEAD SOURCE PERFORMANCE ===
        $leadSources = Lead::whereBetween('leads.created_at', [$startDate, $endDate])
            ->join('lead_sources', 'leads.lead_source_id', '=', 'lead_sources.id')
            ->select(
                'lead_sources.name',
                DB::raw('COUNT(leads.id) as total_leads'),
                DB::raw('COUNT(CASE WHEN leads.booking_status = "complete" THEN 1 END) as converted'),
                DB::raw('SUM(CASE WHEN leads.booking_status = "complete" THEN leads.total_amount ELSE 0 END) as revenue')
            )
            ->groupBy('lead_sources.id', 'lead_sources.name')
            ->orderByDesc('total_leads')
            ->get()
            ->map(function($source) {
                $source->conversion_rate = $source->total_leads > 0 
                    ? round(($source->converted / $source->total_leads) * 100, 1) 
                    : 0;
                return $source;
            });

        // === MONTHLY SALES TREND ===
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'leads' => Lead::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'quotations' => Quotation::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'bookings' => Booking::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'revenue' => Booking::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount')
            ];
        }

        // === DEAL SIZE DISTRIBUTION ===
        $dealSizes = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->select('total_amount')
            ->get()
            ->groupBy(function($booking) {
                $amount = $booking->total_amount;
                if ($amount < 10000) return '< 10K';
                if ($amount < 25000) return '10K - 25K';
                if ($amount < 50000) return '25K - 50K';
                if ($amount < 100000) return '50K - 100K';
                return '100K+';
            })
            ->map->count();

        $dealSizeLabels = ['< 10K', '10K - 25K', '25K - 50K', '50K - 100K', '100K+'];
        $dealSizeData = array_map(fn($label) => $dealSizes->get($label, 0), $dealSizeLabels);

        // === RECENT CONVERSIONS ===
        $recentConversions = Booking::with(['lead', 'createdBy'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.sales-analytics.index', compact(
            'totalLeads',
            'totalQuotations',
            'totalBookings',
            'leadToQuotationRate',
            'quotationToBookingRate',
            'overallConversionRate',
            'totalRevenue',
            'avgDealSize',
            'salesCycleDays',
            'funnelData',
            'salespeople',
            'leadSources',
            'monthlyTrend',
            'dealSizeLabels',
            'dealSizeData',
            'recentConversions',
            'startDate',
            'endDate'
        ), ['page_title' => 'Sales Performance Analytics']);
    }
}
