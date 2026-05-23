<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('product')->with('subCategory',function($query){
            $query->withCount('product');
        })->get();

        // Base query for user-specific filtering
        $userFilter = function($query) {
            if (auth()->id() != 1) {
                $query->where('added_by', auth()->id());
            }
        };

        // Today's lead statistics
        $total_complete = Lead::where('booking_status', 'complete')->when(auth()->id() != 1, $userFilter)->whereDate('booking_start_date',today())->count();
        $total_confirm  = Lead::where('booking_status', 'confirm')->when(auth()->id() != 1, $userFilter)->whereDate('booking_start_date',today())->count();
        $total_follow_up  = Lead::where('booking_status', 'follow up')->when(auth()->id() != 1, $userFilter)->whereDate('booking_start_date',today())->count();
        $total_cancel   = Lead::where('booking_status', 'cancel')->when(auth()->id() != 1, $userFilter)->whereDate('booking_start_date',today())->count();
        $total_lead     = Lead::when(auth()->id() != 1, $userFilter)->whereDate('booking_start_date',today())->count();
        $total_enquiry = Enquiry::count();

        // Calculate conversion rate (completed leads / total leads this month)
        $monthly_total_leads = Lead::when(auth()->id() != 1, $userFilter)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $monthly_completed_leads = Lead::where('booking_status', 'complete')
            ->when(auth()->id() != 1, $userFilter)
            ->whereMonth('booking_start_date', Carbon::now()->month)
            ->whereYear('booking_start_date', Carbon::now()->year)
            ->count();
        
        $conversion_rate = $monthly_total_leads > 0 ? round(($monthly_completed_leads / $monthly_total_leads) * 100, 1) : 0;

        // Calculate average deal value
        $total_revenue = Lead::where('booking_status', 'complete')
            ->when(auth()->id() != 1, $userFilter)
            ->whereMonth('booking_start_date', Carbon::now()->month)
            ->whereYear('booking_start_date', Carbon::now()->year)
            ->sum('total_amount');
        
        $avg_deal_value = $monthly_completed_leads > 0 ? round($total_revenue / $monthly_completed_leads, 2) : 0;

        // Calculate monthly growth percentage
        $current_month_revenue = Lead::where('booking_status', 'complete')
            ->when(auth()->id() != 1, $userFilter)
            ->whereMonth('booking_start_date', Carbon::now()->month)
            ->whereYear('booking_start_date', Carbon::now()->year)
            ->sum('total_amount');
        
        $last_month_revenue = Lead::where('booking_status', 'complete')
            ->when(auth()->id() != 1, $userFilter)
            ->whereMonth('booking_start_date', Carbon::now()->subMonth()->month)
            ->whereYear('booking_start_date', Carbon::now()->subMonth()->year)
            ->sum('total_amount');
        
        $monthly_growth = $last_month_revenue > 0 ? round((($current_month_revenue - $last_month_revenue) / $last_month_revenue) * 100, 1) : 0;

        // Generate a range of the last 6 months
        $startOfMonth = Carbon::now()->subMonths(5)->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $months = [];
        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addMonth()) {
            $months[] = $date->format('Y-M');
        }

        $totalBusiness = [];
        $totalExpense = [];

        foreach ($months as $key => $month) {
            $totalBusiness[] = Lead::where('booking_status', 'complete')->when(auth()->id() != 1, $userFilter)
                ->whereMonth('booking_start_date', Carbon::parse($month)->month)
                ->whereYear('booking_start_date', Carbon::parse($month)->year)
                ->sum('total_amount');

            $totalExpense[] = Lead::where('booking_status', 'complete')->when(auth()->id() != 1, $userFilter)
                ->whereMonth('booking_start_date', Carbon::parse($month)->month)
                ->whereYear('booking_start_date', Carbon::parse($month)->year)
                ->sum('total_expense');
        }

        // Get today's enquiries and leads
        $today_enquiries = Enquiry::whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $today_leads = Lead::whereDate('booking_start_date', Carbon::today())
            ->when(auth()->id() != 1, $userFilter)
            ->orderBy('created_at', 'desc')
            ->get();

        // Recent activity - combine leads and enquiries
        $recent_leads = Lead::when(auth()->id() != 1, $userFilter)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($lead) {
                return [
                    'type' => 'lead',
                    'title' => 'New Lead: ' . $lead->guest_name,
                    'description' => 'Booking for ' . $lead->pax . ' pax',
                    'time' => $lead->created_at,
                    'status' => $lead->booking_status,
                    'icon' => 'calendar'
                ];
            });

        $recent_enquiries = Enquiry::orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($enquiry) {
                return [
                    'type' => 'enquiry',
                    'title' => 'New Enquiry: ' . $enquiry->name,
                    'description' => \Str::limit($enquiry->message ?? 'No message', 50),
                    'time' => $enquiry->created_at,
                    'status' => 'new',
                    'icon' => 'message-circle'
                ];
            });

        $recent_activity = $recent_leads->concat($recent_enquiries)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        // === COMPREHENSIVE ANALYTICS ===
        
        // Lead Source Analysis
        $lead_sources = Lead::when(auth()->id() != 1, $userFilter)
            ->select('lead_source_id', DB::raw('count(*) as count'))
            ->whereNotNull('lead_source_id')
            ->where('lead_source_id', '!=', '')
            ->groupBy('lead_source_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $lead_source_labels = $lead_sources->pluck('lead_source_id')->map(function($id) {
            return 'Source ' . $id;
        })->toArray();
        $lead_source_data = $lead_sources->pluck('count')->toArray();

        // Booking Status Distribution (All Time)
        $status_distribution = Lead::when(auth()->id() != 1, $userFilter)
            ->select('booking_status', DB::raw('count(*) as count'))
            ->groupBy('booking_status')
            ->get();

        $status_labels = $status_distribution->pluck('booking_status')->map(function($status) {
            return ucfirst($status);
        })->toArray();
        $status_data = $status_distribution->pluck('count')->toArray();

        // Monthly Booking Trends (Last 6 months)
        $booking_trends = [];
        foreach ($months as $month) {
            $booking_trends[] = Lead::when(auth()->id() != 1, $userFilter)
                ->whereMonth('booking_start_date', Carbon::parse($month)->month)
                ->whereYear('booking_start_date', Carbon::parse($month)->year)
                ->count();
        }

        // Top Cities by Bookings
        $top_cities = Lead::when(auth()->id() != 1, $userFilter)
            ->select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $city_labels = $top_cities->pluck('city')->toArray();
        $city_data = $top_cities->pluck('count')->toArray();

        // Payment Status Analysis
        $payment_status = Lead::when(auth()->id() != 1, $userFilter)
            ->select('payment_status', DB::raw('count(*) as count'))
            ->whereNotNull('payment_status')
            ->where('payment_status', '!=', '')
            ->groupBy('payment_status')
            ->get();

        $payment_labels = $payment_status->pluck('payment_status')->map(function($status) {
            return ucfirst($status);
        })->toArray();
        $payment_data = $payment_status->pluck('count')->toArray();

        // Average PAX per booking
        $avg_pax = Lead::when(auth()->id() != 1, $userFilter)
            ->where('booking_status', 'complete')
            ->whereNotNull('pax')
            ->avg('pax');
        $avg_pax = round($avg_pax ?? 0, 1);

        // Total Revenue (All Time)
        $total_revenue_all = Lead::when(auth()->id() != 1, $userFilter)
            ->where('booking_status', 'complete')
            ->sum('total_amount');

        // Daily Booking Pattern (Last 7 days)
        $daily_labels = [];
        $daily_bookings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $daily_labels[] = $date->format('D');
            $daily_bookings[] = Lead::when(auth()->id() != 1, $userFilter)
                ->whereDate('created_at', $date)
                ->count();
        }

        return view('admin.dashboard',compact(
            'categories', 
            'total_enquiry', 
            'total_complete', 
            'total_confirm', 
            'total_follow_up', 
            'total_cancel', 
            'total_lead',
            'months', 
            'totalBusiness', 
            'totalExpense', 
            'today_enquiries', 
            'today_leads',
            'conversion_rate',
            'avg_deal_value',
            'monthly_growth',
            'recent_activity',
            // Analytics data
            'lead_source_labels',
            'lead_source_data',
            'status_labels',
            'status_data',
            'booking_trends',
            'city_labels',
            'city_data',
            'payment_labels',
            'payment_data',
            'avg_pax',
            'total_revenue_all',
            'daily_labels',
            'daily_bookings'
        ), ['page_title' => 'Admin Dashboard']);
    }

    public function changeTheme(Request $request)
    {
        if(isset($request->theme_change)){
            session()->put('selected_theme', 'Dark');
            $message = 'Dark Mode Apply !!';
        }else{
            session()->put('selected_theme', 'Light');
            $message = 'Light Mode Apply !!';
        }
        return back()->with('success', $message);
    }

}
