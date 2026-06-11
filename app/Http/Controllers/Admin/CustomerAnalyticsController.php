<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view|analytics-customer');
    }

    public function index(Request $request)
    {
        // Whitelist-validate date_range to prevent parameter injection
        $allowed = ['all', 'today', '7days', '30days', '6months', 'year', 'custom'];
        $dateRange = in_array($request->get('date_range'), $allowed, true)
            ? $request->get('date_range')
            : 'all';

        $startDate = null;
        $endDate   = Carbon::now();

        switch ($dateRange) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case '7days':
                $startDate = Carbon::now()->subDays(7);
                break;
            case '30days':
                $startDate = Carbon::now()->subDays(30);
                break;
            case '6months':
                $startDate = Carbon::now()->subMonths(6);
                break;
            case 'year':
                $startDate = Carbon::now()->subYear();
                break;
            case 'custom':
                $request->validate([
                    'start_date' => 'required|date|before_or_equal:today',
                    'end_date'   => 'required|date|after_or_equal:start_date|before_or_equal:today',
                ]);
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate   = Carbon::parse($request->end_date)->endOfDay();
                break;
            default:
                $startDate = null;
                break;
        }

        // Base query for user-specific filtering
        $userFilter = function($query) {
            if (auth()->id() != 1) {
                $query->where('added_by', auth()->id());
            }
        };

        // Date filter closure
        $dateFilter = function($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        };

        // === KEY METRICS ===
        
        // Total Unique Customers (based on unique guest names with contact)
        $total_customers = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('guest_name')
            ->where('guest_name', '!=', '')
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->distinct('contact')
            ->count('contact');

        // Repeat Customers (customers with 2+ bookings)
        $customer_bookings = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->select('contact', DB::raw('count(*) as booking_count'))
            ->groupBy('contact')
            ->get();

        $repeat_customers = $customer_bookings->where('booking_count', '>=', 2)->count();
        $repeat_customer_rate = $total_customers > 0 ? round(($repeat_customers / $total_customers) * 100, 1) : 0;

        // Average Customer Lifetime Value
        $customer_revenues = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->where('booking_status', 'complete')
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->select('contact', DB::raw('sum(total_amount) as total_revenue'))
            ->groupBy('contact')
            ->get();

        $avg_clv = $customer_revenues->count() > 0 ? round($customer_revenues->avg('total_revenue'), 2) : 0;

        // Customer Growth Rate (comparing current period vs previous period of same length)
        if ($startDate) {
            // Calculate period length
            $periodDays = $startDate->diffInDays($endDate);
            
            // Get customers in current period
            $current_period_customers = Lead::when(auth()->id() != 1, $userFilter)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('contact')
                ->where('contact', '!=', '')
                ->distinct('contact')
                ->count('contact');

            // Get customers in previous period (same length)
            $previous_start = $startDate->copy()->subDays($periodDays);
            $previous_end = $startDate->copy()->subDay();
            
            $previous_period_customers = Lead::when(auth()->id() != 1, $userFilter)
                ->whereBetween('created_at', [$previous_start, $previous_end])
                ->whereNotNull('contact')
                ->where('contact', '!=', '')
                ->distinct('contact')
                ->count('contact');

            $customer_growth = $previous_period_customers > 0 
                ? round((($current_period_customers - $previous_period_customers) / $previous_period_customers) * 100, 1) 
                : 0;
        } else {
            // Default: this month vs last month
            $current_month_customers = Lead::when(auth()->id() != 1, $userFilter)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereNotNull('contact')
                ->where('contact', '!=', '')
                ->distinct('contact')
                ->count('contact');

            $last_month_customers = Lead::when(auth()->id() != 1, $userFilter)
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->whereNotNull('contact')
                ->where('contact', '!=', '')
                ->distinct('contact')
                ->count('contact');

            $customer_growth = $last_month_customers > 0 
                ? round((($current_month_customers - $last_month_customers) / $last_month_customers) * 100, 1) 
                : 0;
        }

        // === CUSTOMER ACQUISITION TREND ===
        $months = [];
        $new_customers_trend = [];
        
        if ($startDate) {
            // Dynamic trend based on selected date range
            $periodDays = $startDate->diffInDays($endDate);
            
            if ($periodDays <= 7) {
                // Show daily trend for short periods
                for ($i = 0; $i <= $periodDays; $i++) {
                    $date = $startDate->copy()->addDays($i);
                    $months[] = $date->format('M d');
                    
                    $new_customers_trend[] = Lead::when(auth()->id() != 1, $userFilter)
                        ->whereDate('created_at', $date)
                        ->whereNotNull('contact')
                        ->where('contact', '!=', '')
                        ->distinct('contact')
                        ->count('contact');
                }
            } elseif ($periodDays <= 60) {
                // Show weekly trend for medium periods
                $weeks = ceil($periodDays / 7);
                for ($i = 0; $i < $weeks; $i++) {
                    $weekStart = $startDate->copy()->addWeeks($i);
                    $weekEnd = $weekStart->copy()->addDays(6)->min($endDate);
                    $months[] = $weekStart->format('M d') . '-' . $weekEnd->format('d');
                    
                    $new_customers_trend[] = Lead::when(auth()->id() != 1, $userFilter)
                        ->whereBetween('created_at', [$weekStart, $weekEnd])
                        ->whereNotNull('contact')
                        ->where('contact', '!=', '')
                        ->distinct('contact')
                        ->count('contact');
                }
            } else {
                // Show monthly trend for long periods
                $monthsDiff = $startDate->diffInMonths($endDate);
                $monthsToShow = min($monthsDiff + 1, 12); // Max 12 months
                
                for ($i = 0; $i < $monthsToShow; $i++) {
                    $date = $startDate->copy()->addMonths($i);
                    $months[] = $date->format('M Y');
                    
                    $new_customers_trend[] = Lead::when(auth()->id() != 1, $userFilter)
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->whereNotNull('contact')
                        ->where('contact', '!=', '')
                        ->distinct('contact')
                        ->count('contact');
                }
            }
        } else {
            // Default: Last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[] = $date->format('M Y');
                
                $new_customers_trend[] = Lead::when(auth()->id() != 1, $userFilter)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->distinct('contact')
                    ->count('contact');
            }
        }

        // === GEOGRAPHIC DISTRIBUTION ===
        $top_cities = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->select('city', DB::raw('count(distinct contact) as customer_count'))
            ->groupBy('city')
            ->orderByDesc('customer_count')
            ->limit(10)
            ->get();

        $city_labels = $top_cities->pluck('city')->toArray();
        $city_data = $top_cities->pluck('customer_count')->toArray();

        // === CUSTOMER SEGMENTATION ===
        $new_customers = $customer_bookings->where('booking_count', '=', 1)->count();
        $repeat_customers_count = $customer_bookings->where('booking_count', '>=', 2)->count();
        
        // High-value customers (top 20% by revenue)
        $revenue_threshold = $customer_revenues->count() > 0 
            ? $customer_revenues->sortByDesc('total_revenue')->take(ceil($customer_revenues->count() * 0.2))->min('total_revenue')
            : 0;
        
        $high_value_customers = $customer_revenues->where('total_revenue', '>=', $revenue_threshold)->count();

        // Dormant customers (no booking in last 6 months)
        $six_months_ago = Carbon::now()->subMonths(6);
        $recent_contacts = Lead::when(auth()->id() != 1, $userFilter)
            ->where('created_at', '>=', $six_months_ago)
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->distinct()
            ->pluck('contact');

        $all_contacts = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->distinct()
            ->pluck('contact');

        $dormant_customers = $all_contacts->diff($recent_contacts)->count();

        $segment_labels = ['New Customers', 'Repeat Customers', 'High-Value', 'Dormant'];
        $segment_data = [$new_customers, $repeat_customers_count, $high_value_customers, $dormant_customers];

        // === REVENUE BY CUSTOMER TYPE ===
        $new_customer_revenue = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->where('booking_status', 'complete')
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->whereIn('contact', function($query) use ($userFilter, $startDate, $dateFilter) {
                $query->select('contact')
                    ->from('leads')
                    ->when(auth()->id() != 1, $userFilter)
                    ->when($startDate, $dateFilter)
                    ->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->groupBy('contact')
                    ->havingRaw('count(*) = 1');
            })
            ->sum('total_amount');

        $repeat_customer_revenue = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->where('booking_status', 'complete')
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->whereIn('contact', function($query) use ($userFilter, $startDate, $dateFilter) {
                $query->select('contact')
                    ->from('leads')
                    ->when(auth()->id() != 1, $userFilter)
                    ->when($startDate, $dateFilter)
                    ->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->groupBy('contact')
                    ->havingRaw('count(*) >= 2');
            })
            ->sum('total_amount');

        $revenue_by_type_labels = ['New Customers', 'Repeat Customers'];
        $revenue_by_type_data = [$new_customer_revenue, $repeat_customer_revenue];

        // === BOOKING FREQUENCY DISTRIBUTION ===
        $frequency_distribution = $customer_bookings->groupBy('booking_count')->map(function($group) {
            return $group->count();
        })->sortKeys();

        $frequency_labels = $frequency_distribution->keys()->map(function($count) {
            return $count . ' Booking' . ($count > 1 ? 's' : '');
        })->toArray();
        $frequency_data = $frequency_distribution->values()->toArray();

        // === TOP 10 CUSTOMERS BY REVENUE ===
        $top_customers = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->where('booking_status', 'complete')
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->select('guest_name', 'contact', DB::raw('sum(total_amount) as total_revenue'), DB::raw('count(*) as booking_count'))
            ->groupBy('contact', 'guest_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $top_customer_names = $top_customers->pluck('guest_name')->toArray();
        $top_customer_revenue = $top_customers->pluck('total_revenue')->toArray();

        // === PAX DISTRIBUTION ===
        $pax_distribution = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('pax')
            ->where('pax', '!=', '')
            ->where('pax', '>', 0)
            ->select('pax', DB::raw('count(*) as count'))
            ->groupBy('pax')
            ->orderBy('pax')
            ->limit(10)
            ->get();

        $pax_labels = $pax_distribution->pluck('pax')->map(function($pax) {
            return $pax . ' PAX';
        })->toArray();
        $pax_data = $pax_distribution->pluck('count')->toArray();

        // === BOOKING LEAD TIME ANALYSIS (Days between enquiry and booking) ===
        $lead_times = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('enquiry_date')
            ->whereNotNull('booking_start_date')
            ->get()
            ->map(function($lead) {
                $enquiry = Carbon::parse($lead->enquiry_date);
                $booking = Carbon::parse($lead->booking_start_date);
                return $enquiry->diffInDays($booking);
            })
            ->filter(function($days) {
                return $days >= 0 && $days <= 90; // Filter outliers
            });

        // Group lead times into ranges
        $lead_time_ranges = [
            '0-7 days' => $lead_times->filter(fn($d) => $d <= 7)->count(),
            '8-14 days' => $lead_times->filter(fn($d) => $d > 7 && $d <= 14)->count(),
            '15-30 days' => $lead_times->filter(fn($d) => $d > 14 && $d <= 30)->count(),
            '31-60 days' => $lead_times->filter(fn($d) => $d > 30 && $d <= 60)->count(),
            '60+ days' => $lead_times->filter(fn($d) => $d > 60)->count(),
        ];

        $lead_time_labels = array_keys($lead_time_ranges);
        $lead_time_data = array_values($lead_time_ranges);

        // === CUSTOMER TABLES DATA ===
        
        // Top Customers Table
        $top_customers_table = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->where('booking_status', 'complete')
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->select(
                'guest_name',
                'contact',
                'city',
                DB::raw('sum(total_amount) as total_revenue'),
                DB::raw('count(*) as total_bookings'),
                DB::raw('sum(pax) as total_pax'),
                DB::raw('max(booking_start_date) as last_booking_date')
            )
            ->groupBy('contact', 'guest_name', 'city')
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get();

        // Recent Customers (first booking in last 30 days)
        $recent_customers_table = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->whereIn('id', function($query) use ($userFilter, $startDate, $dateFilter) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('leads')
                    ->when(auth()->id() != 1, $userFilter)
                    ->when($startDate, $dateFilter)
                    ->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->groupBy('contact');
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Dormant Customers Table
        $dormant_customers_table = Lead::when(auth()->id() != 1, $userFilter)
            ->when($startDate, $dateFilter)
            ->whereNotNull('contact')
            ->where('contact', '!=', '')
            ->whereIn('id', function($query) use ($userFilter, $six_months_ago, $startDate, $dateFilter) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('leads')
                    ->when(auth()->id() != 1, $userFilter)
                    ->when($startDate, $dateFilter)
                    ->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->groupBy('contact')
                    ->having(DB::raw('MAX(created_at)'), '<', $six_months_ago);
            })
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return view('admin.customer-analytics', compact(
            // Key Metrics
            'total_customers',
            'repeat_customer_rate',
            'avg_clv',
            'customer_growth',
            // Charts Data
            'months',
            'new_customers_trend',
            'city_labels',
            'city_data',
            'segment_labels',
            'segment_data',
            'revenue_by_type_labels',
            'revenue_by_type_data',
            'frequency_labels',
            'frequency_data',
            'top_customer_names',
            'top_customer_revenue',
            'pax_labels',
            'pax_data',
            'lead_time_labels',
            'lead_time_data',
            // Tables
            'top_customers_table',
            'recent_customers_table',
            'dormant_customers_table'
        ), ['page_title' => 'Customer Analytics']);
    }
}
