<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\CabBooking;
use App\Models\BoatBooking;
use App\Models\Booking;
use App\Models\LeadSource;
use App\Models\Expense;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class ProfitAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view|analytics-profit');
    }

    public function index(Request $request)
    {
        $dates = $this->getAllBookingDateRange();

        $defaultStart = $dates['earliest'] ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $defaultEnd   = $dates['latest']   ?? Carbon::now()->format('Y-m-d');
        $anchor       = Carbon::parse($defaultEnd);

        $startDate = $request->input('start_date', $defaultStart);
        $endDate   = $request->input('end_date',   $defaultEnd);

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        [$tourRev, $tourExp, $tourCount] = $this->tourMetrics($start, $end);
        [$cabRev,  $cabExp,  $cabCount]  = $this->cabMetrics($start, $end);
        [$boatRev, $boatExp, $boatCount] = $this->boatMetrics($start, $end);
        [$pkgRev,  $pkgExp,  $pkgCount]  = $this->packageMetrics($start, $end);

        $manualExpenseTotal = (float) Expense::whereBetween('expense_date', [$start, $end])->sum('amount');

        $totalRevenue  = $tourRev + $cabRev + $boatRev + $pkgRev;
        $totalExpense  = $tourExp + $cabExp + $boatExp + $pkgExp + $manualExpenseTotal;
        $totalProfit   = $totalRevenue - $totalExpense;
        $totalBookings = $tourCount + $cabCount + $boatCount + $pkgCount;
        $profitMargin  = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;
        $avgDeal       = $totalBookings > 0 ? round($totalRevenue / $totalBookings) : 0;

        $monthlyTrend   = $this->buildMonthlyTrend($anchor);
        $sourceBreakdown = $this->buildSourceBreakdown($start, $end, $totalRevenue);
        $staffBreakdown  = $this->buildStaffBreakdown($start, $end);
        $topBookings     = $this->buildTopBookings($start, $end);
        $recentBookings  = $this->buildRecentBookings($start, $end);
        $allBookings     = $this->buildAllBookings($start, $end);

        $expenseEntries = Expense::with(['createdBy', 'staff'])
            ->whereBetween('expense_date', [$start, $end])
            ->orderByDesc('expense_date')
            ->limit(300)
            ->get();

        $categoryBreakdown = Expense::whereBetween('expense_date', [$start, $end])
            ->select('type', 'category', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('type', 'category')
            ->orderByDesc('total')
            ->get();

        $staffList = Admin::orderBy('name')->get(['id', 'name']);

        $expenseBreakdown = [
            ['label' => 'Tour Cost',    'val' => $tourExp],
            ['label' => 'Cab Cost',     'val' => $cabExp],
            ['label' => 'Boat Cost',    'val' => $boatExp],
            ['label' => 'Package Cost', 'val' => $pkgExp],
            ['label' => 'Manual Expenses', 'val' => $manualExpenseTotal],
        ];

        return view('admin.profit-analytics.index', compact(
            'totalRevenue', 'totalExpense', 'totalProfit', 'profitMargin',
            'totalBookings', 'avgDeal', 'expenseBreakdown',
            'monthlyTrend', 'sourceBreakdown', 'staffBreakdown',
            'topBookings', 'recentBookings', 'allBookings', 'expenseEntries', 'categoryBreakdown', 'staffList',
            'startDate', 'endDate', 'anchor'
        ));
    }

    private function getAllBookingDateRange(): array
    {
        $dates = array_filter([
            Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')->min('booking_start_date'),
            Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')->max('booking_start_date'),
            CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')->min('pickup_date'),
            CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')->max('pickup_date'),
            BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')->min('booking_date'),
            BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')->max('booking_date'),
            Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')->min('booking_date'),
            Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')->max('booking_date'),
        ]);

        return [
            'earliest' => $dates ? min($dates) : null,
            'latest'   => $dates ? max($dates) : null,
        ];
    }

    private function tourMetrics($start, $end): array
    {
        $leadsQ = Lead::where('booking_status', 'confirm')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end]);

        $cnt = (int) (clone $leadsQ)->count();

        // Revenue counts only what's been collected, not the full sale value.
        $rev = (clone $leadsQ)->withSum('leadPayments', 'paid_amount')
            ->get()
            ->sum(fn ($l) => min((float) $l->total_amount, (float) ($l->lead_payments_sum_paid_amount ?? 0)));

        $withExp = (clone $leadsQ)->where('total_expense', '>', 0)
            ->selectRaw('SUM(total_expense) as exp')
            ->value('exp');

        $noExpRev = (clone $leadsQ)
            ->where(function ($q) { $q->whereNull('total_expense')->orWhere('total_expense', 0); })
            ->sum('total_amount');

        $exp = (float)($withExp ?? 0) + round($noExpRev * 0.30);

        return [$rev, $exp, $cnt];
    }

    private function cabMetrics($start, $end): array
    {
        $data = CabBooking::where('booking_status', 'confirmed')
            ->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->selectRaw('COUNT(*) as cnt, SUM(total_amount) as sale, SUM(pending_amount) as pending, SUM(vendor_cost) as exp')
            ->first();

        $sale = (float)($data->sale ?? 0);
        $rev  = $sale - (float)($data->pending ?? 0);
        $exp  = (float)($data->exp ?? 0);
        if ($exp == 0 && $sale > 0) $exp = round($sale * 0.30);

        return [$rev, $exp, (int)($data->cnt ?? 0)];
    }

    private function boatMetrics($start, $end): array
    {
        $rows = BoatBooking::where('booking_status', 'confirmed')
            ->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->withSum('payments', 'amount')
            ->get();

        $sale = (float) $rows->sum('final_amount');
        $rev  = (float) $rows->sum(fn ($b) => min((float) $b->final_amount, (float) ($b->payments_sum_amount ?? 0)));
        $exp  = (float) $rows->sum('vendor_cost');
        if ($exp == 0 && $sale > 0) $exp = round($sale * 0.30);

        return [$rev, $exp, $rows->count()];
    }

    private function packageMetrics($start, $end): array
    {
        $data = Booking::where('booking_status', 'confirmed')
            ->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->selectRaw('COUNT(*) as cnt, SUM(total_amount) as sale, SUM(pending_amount) as pending')
            ->first();

        $sale = (float)($data->sale ?? 0);
        $rev  = $sale - (float)($data->pending ?? 0);
        $exp  = round($sale * 0.30);

        return [$rev, $exp, (int)($data->cnt ?? 0)];
    }

    private function buildMonthlyTrend($anchor): array
    {
        $trend       = [];
        $anchorMonth = Carbon::parse($anchor)->startOfMonth();

        for ($i = 5; $i >= 0; $i--) {
            $m     = $anchorMonth->copy()->subMonths($i);
            $start = $m->copy()->startOfMonth();
            $end   = $m->copy()->endOfMonth();

            [$tourRev, $tourExp] = $this->tourMetrics($start, $end);
            [$cabRev,  $cabExp]  = $this->cabMetrics($start, $end);
            [$boatRev, $boatExp] = $this->boatMetrics($start, $end);
            [$pkgRev,  $pkgExp]  = $this->packageMetrics($start, $end);

            $manualExp = (float) Expense::whereBetween('expense_date', [$start, $end])->sum('amount');

            $mRev = $tourRev + $cabRev + $boatRev + $pkgRev;
            $mExp = $tourExp + $cabExp + $boatExp + $pkgExp + $manualExp;

            $trend[] = [
                'month'   => $m->format('M Y'),
                'revenue' => $mRev,
                'expense' => $mExp,
                'profit'  => $mRev - $mExp,
            ];
        }

        return $trend;
    }

    private function buildSourceBreakdown($start, $end, float $totalRevenue): \Illuminate\Support\Collection
    {
        $merged = [];
        $add = function ($key, $rev) use (&$merged) {
            $key = $key ?? 0;
            if (!isset($merged[$key])) $merged[$key] = ['lead_source_id' => $key ?: null, 'cnt' => 0, 'revenue' => 0.0];
            $merged[$key]['cnt']++;
            $merged[$key]['revenue'] += $rev;
        };

        // Revenue here is collected amount, not gross sale value.
        Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->withSum('leadPayments', 'paid_amount')
            ->get(['id', 'lead_source_id', 'total_amount'])
            ->each(fn ($l) => $add($l->lead_source_id, min((float) $l->total_amount, (float) ($l->lead_payments_sum_paid_amount ?? 0))));

        CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->get(['id', 'lead_source_id', 'total_amount', 'pending_amount'])
            ->each(fn ($b) => $add($b->lead_source_id, (float) $b->total_amount - (float) $b->pending_amount));

        BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->withSum('payments', 'amount')
            ->get(['id', 'lead_source_id', 'final_amount'])
            ->each(fn ($b) => $add($b->lead_source_id, min((float) $b->final_amount, (float) ($b->payments_sum_amount ?? 0))));

        return collect(array_values($merged))
            ->sortByDesc('revenue')
            ->map(function ($r) use ($totalRevenue) {
                $source = $r['lead_source_id'] ? LeadSource::find($r['lead_source_id']) : null;
                return [
                    'label'   => $source ? $source->name : 'Direct',
                    'cnt'     => $r['cnt'],
                    'revenue' => $r['revenue'],
                    'pct'     => $totalRevenue > 0 ? round(($r['revenue'] / $totalRevenue) * 100, 1) : 0,
                ];
            })->values();
    }

    private function buildStaffBreakdown($start, $end): \Illuminate\Support\Collection
    {
        $staffData = [];
        $add = function ($key, $rev) use (&$staffData) {
            $key = $key ?? 0;
            if (!isset($staffData[$key])) $staffData[$key] = ['staff_id' => $key ?: null, 'cnt' => 0, 'revenue' => 0.0];
            $staffData[$key]['cnt']++;
            $staffData[$key]['revenue'] += $rev;
        };

        // Revenue here is collected amount, not gross sale value.
        Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->withSum('leadPayments', 'paid_amount')
            ->get(['id', 'added_by', 'total_amount'])
            ->each(fn ($l) => $add($l->added_by, min((float) $l->total_amount, (float) ($l->lead_payments_sum_paid_amount ?? 0))));

        CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->get(['id', 'created_by', 'total_amount', 'pending_amount'])
            ->each(fn ($b) => $add($b->created_by, (float) $b->total_amount - (float) $b->pending_amount));

        BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->withSum('payments', 'amount')
            ->get(['id', 'created_by', 'final_amount'])
            ->each(fn ($b) => $add($b->created_by, min((float) $b->final_amount, (float) ($b->payments_sum_amount ?? 0))));

        Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->get(['id', 'created_by', 'total_amount', 'pending_amount'])
            ->each(fn ($b) => $add($b->created_by, (float) $b->total_amount - (float) $b->pending_amount));

        return collect(array_values($staffData))
            ->sortByDesc('revenue')
            ->take(8)
            ->map(function ($r) {
                $admin = Admin::find($r['staff_id']);
                return [
                    'name'    => $admin ? $admin->name : 'Unknown',
                    'cnt'     => $r['cnt'],
                    'revenue' => $r['revenue'],
                ];
            })->values();
    }

    private function buildTopBookings($start, $end): \Illuminate\Support\Collection
    {
        $all = collect();

        // Ranked by gross sale value (the biggest deals); displayed amount/profit
        // are collected-basis.
        Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->orderByDesc('total_amount')->limit(10)
            ->withSum('leadPayments', 'paid_amount')
            ->get(['id','guest_name','contact','total_amount','total_expense','booking_start_date','short_plan'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->total_expense > 0) ? (float)$b->total_expense : round($b->total_amount * 0.3);
                $collected = min((float) $b->total_amount, (float) ($b->lead_payments_sum_paid_amount ?? 0));
                $all->push(['type'=>'Tour','name'=>$b->guest_name,'contact'=>$b->contact,
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->booking_start_date,'plan'=>$b->short_plan]);
            });

        CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->orderByDesc('total_amount')->limit(10)
            ->get(['id','customer_name','customer_phone','total_amount','pending_amount','vendor_cost','pickup_date','vehicle_name'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->total_amount * 0.3);
                $collected = (float) $b->total_amount - (float) $b->pending_amount;
                $all->push(['type'=>'Cab','name'=>$b->customer_name,'contact'=>$b->customer_phone,
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->pickup_date,'plan'=>$b->vehicle_name]);
            });

        BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('final_amount')->limit(10)
            ->withSum('payments', 'amount')
            ->get(['id','name','phone','final_amount','vendor_cost','booking_date','booking_type'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->final_amount * 0.3);
                $collected = min((float) $b->final_amount, (float) ($b->payments_sum_amount ?? 0));
                $all->push(['type'=>'Boat','name'=>$b->name,'contact'=>$b->phone,
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_type]);
            });

        Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('total_amount')->limit(10)
            ->with('lead')
            ->get(['id','lead_id','total_amount','pending_amount','booking_date','booking_number'])
            ->each(function ($b) use (&$all) {
                $exp = round($b->total_amount * 0.3);
                $collected = (float) $b->total_amount - (float) $b->pending_amount;
                $all->push(['type'=>'Package','name'=>$b->lead?->guest_name ?: 'Guest','contact'=>'',
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_number]);
            });

        return $all->sortByDesc('amount')->take(10)->values();
    }

    private function buildRecentBookings($start, $end): \Illuminate\Support\Collection
    {
        $all = collect();

        Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->orderByDesc('booking_start_date')->limit(15)
            ->withSum('leadPayments', 'paid_amount')
            ->get(['id','guest_name','contact','total_amount','total_expense','booking_start_date','short_plan','added_by'])
            ->each(function ($b) use (&$all) {
                $exp   = ($b->total_expense > 0) ? (float)$b->total_expense : round($b->total_amount * 0.3);
                $admin = Admin::find($b->added_by);
                $all->push(['type'=>'Tour','name'=>$b->guest_name,'contact'=>$b->contact,
                    'amount'=>min((float) $b->total_amount, (float) ($b->lead_payments_sum_paid_amount ?? 0)),'expense'=>$exp,
                    'date'=>$b->booking_start_date,'plan'=>$b->short_plan,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->orderByDesc('pickup_date')->limit(15)
            ->get(['id','customer_name','customer_phone','total_amount','pending_amount','vendor_cost','pickup_date','vehicle_name','created_by'])
            ->each(function ($b) use (&$all) {
                $exp   = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->total_amount * 0.3);
                $admin = Admin::find($b->created_by);
                $all->push(['type'=>'Cab','name'=>$b->customer_name,'contact'=>$b->customer_phone,
                    'amount'=>(float)$b->total_amount - (float)$b->pending_amount,'expense'=>$exp,
                    'date'=>$b->pickup_date,'plan'=>$b->vehicle_name,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('booking_date')->limit(15)
            ->withSum('payments', 'amount')
            ->get(['id','name','phone','final_amount','vendor_cost','booking_date','booking_type','created_by'])
            ->each(function ($b) use (&$all) {
                $exp   = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->final_amount * 0.3);
                $admin = Admin::find($b->created_by);
                $all->push(['type'=>'Boat','name'=>$b->name,'contact'=>$b->phone,
                    'amount'=>min((float) $b->final_amount, (float) ($b->payments_sum_amount ?? 0)),'expense'=>$exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_type,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('booking_date')->limit(15)
            ->with('lead')
            ->get(['id','lead_id','total_amount','pending_amount','booking_date','booking_number','created_by'])
            ->each(function ($b) use (&$all) {
                $exp   = round($b->total_amount * 0.3);
                $admin = Admin::find($b->created_by);
                $all->push(['type'=>'Package','name'=>$b->lead?->guest_name ?: 'Guest','contact'=>'',
                    'amount'=>(float)$b->total_amount - (float)$b->pending_amount,'expense'=>$exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_number,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        return $all->sortByDesc('date')->take(15)->values();
    }

    private function buildAllBookings($start, $end): \Illuminate\Support\Collection
    {
        $all = collect();

        Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->withSum('leadPayments', 'paid_amount')
            ->get(['id','guest_name','contact','total_amount','total_expense','booking_start_date','short_plan'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->total_expense > 0) ? (float)$b->total_expense : round($b->total_amount * 0.3);
                $collected = min((float) $b->total_amount, (float) ($b->lead_payments_sum_paid_amount ?? 0));
                $all->push(['type'=>'Tour','name'=>$b->guest_name,'contact'=>$b->contact,
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->booking_start_date,'plan'=>$b->short_plan]);
            });

        CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->get(['id','customer_name','customer_phone','total_amount','pending_amount','vendor_cost','pickup_date','vehicle_name'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->total_amount * 0.3);
                $collected = (float) $b->total_amount - (float) $b->pending_amount;
                $all->push(['type'=>'Cab','name'=>$b->customer_name,'contact'=>$b->customer_phone,
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->pickup_date,'plan'=>$b->vehicle_name]);
            });

        BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->withSum('payments', 'amount')
            ->get(['id','name','phone','final_amount','vendor_cost','booking_date','booking_type'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->final_amount * 0.3);
                $collected = min((float) $b->final_amount, (float) ($b->payments_sum_amount ?? 0));
                $all->push(['type'=>'Boat','name'=>$b->name,'contact'=>$b->phone,
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_type]);
            });

        Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->with('lead')
            ->get(['id','lead_id','total_amount','pending_amount','booking_date','booking_number'])
            ->each(function ($b) use (&$all) {
                $exp = round($b->total_amount * 0.3);
                $collected = (float) $b->total_amount - (float) $b->pending_amount;
                $all->push(['type'=>'Package','name'=>$b->lead?->guest_name ?: 'Guest','contact'=>'',
                    'amount'=>$collected,'expense'=>$exp,'profit'=>$collected - $exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_number]);
            });

        return $all->sortByDesc('date')->values();
    }

    public function exportReport(Request $request)
    {
        return response()->json(['message' => 'Export coming soon']);
    }
}
