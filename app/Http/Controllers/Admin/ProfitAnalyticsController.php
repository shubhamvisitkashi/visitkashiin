<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\CabBooking;
use App\Models\BoatBooking;
use App\Models\Booking;
use App\Models\LeadSource;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class ProfitAnalyticsController extends Controller
{
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

        $totalRevenue  = $tourRev + $cabRev + $boatRev + $pkgRev;
        $totalExpense  = $tourExp + $cabExp + $boatExp + $pkgExp;
        $totalProfit   = $totalRevenue - $totalExpense;
        $totalBookings = $tourCount + $cabCount + $boatCount + $pkgCount;
        $profitMargin  = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;
        $avgDeal       = $totalBookings > 0 ? round($totalRevenue / $totalBookings) : 0;

        $monthlyTrend   = $this->buildMonthlyTrend($anchor);
        $sourceBreakdown = $this->buildSourceBreakdown($start, $end, $totalRevenue);
        $staffBreakdown  = $this->buildStaffBreakdown($start, $end);
        $topBookings     = $this->buildTopBookings($start, $end);
        $recentBookings  = $this->buildRecentBookings($start, $end);

        return view('admin.profit-analytics.index', compact(
            'totalRevenue', 'totalExpense', 'totalProfit', 'profitMargin',
            'totalBookings', 'avgDeal',
            'monthlyTrend', 'sourceBreakdown', 'staffBreakdown',
            'topBookings', 'recentBookings',
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
        $rev = (float) Lead::where('booking_status', 'confirm')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->sum('total_amount');

        $cnt = (int) Lead::where('booking_status', 'confirm')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->count();

        $withExp = Lead::where('booking_status', 'confirm')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->where('total_expense', '>', 0)
            ->selectRaw('SUM(total_expense) as exp')
            ->value('exp');

        $noExpRev = Lead::where('booking_status', 'confirm')
            ->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
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
            ->selectRaw('COUNT(*) as cnt, SUM(total_amount) as rev, SUM(vendor_cost) as exp')
            ->first();

        $rev = (float)($data->rev ?? 0);
        $exp = (float)($data->exp ?? 0);
        if ($exp == 0 && $rev > 0) $exp = round($rev * 0.30);

        return [$rev, $exp, (int)($data->cnt ?? 0)];
    }

    private function boatMetrics($start, $end): array
    {
        $data = BoatBooking::where('booking_status', 'confirmed')
            ->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->selectRaw('COUNT(*) as cnt, SUM(final_amount) as rev, SUM(vendor_cost) as exp')
            ->first();

        $rev = (float)($data->rev ?? 0);
        $exp = (float)($data->exp ?? 0);
        if ($exp == 0 && $rev > 0) $exp = round($rev * 0.30);

        return [$rev, $exp, (int)($data->cnt ?? 0)];
    }

    private function packageMetrics($start, $end): array
    {
        $data = Booking::where('booking_status', 'confirmed')
            ->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->selectRaw('COUNT(*) as cnt, SUM(total_amount) as rev')
            ->first();

        $rev = (float)($data->rev ?? 0);
        $exp = round($rev * 0.30);

        return [$rev, $exp, (int)($data->cnt ?? 0)];
    }

    private function buildMonthlyTrend($anchor): array
    {
        $trend       = [];
        $anchorMonth = Carbon::parse($anchor)->startOfMonth();

        for ($i = 5; $i >= 0; $i--) {
            $m  = $anchorMonth->copy()->subMonths($i);
            $yr = $m->year;
            $mo = $m->month;

            // Tours
            $tourRev  = (float) Lead::where('booking_status', 'confirm')
                ->whereYear('booking_start_date', $yr)->whereMonth('booking_start_date', $mo)
                ->sum('total_amount');
            $tourExpW = (float) Lead::where('booking_status', 'confirm')
                ->where('total_expense', '>', 0)
                ->whereYear('booking_start_date', $yr)->whereMonth('booking_start_date', $mo)
                ->sum('total_expense');
            $tourExpN = (float) Lead::where('booking_status', 'confirm')
                ->where(function ($q) { $q->whereNull('total_expense')->orWhere('total_expense', 0); })
                ->whereYear('booking_start_date', $yr)->whereMonth('booking_start_date', $mo)
                ->sum('total_amount');
            $tourExp  = $tourExpW + round($tourExpN * 0.30);

            // Cabs
            $cab    = CabBooking::where('booking_status', 'confirmed')
                ->whereYear('pickup_date', $yr)->whereMonth('pickup_date', $mo)
                ->selectRaw('SUM(total_amount) as rev, SUM(vendor_cost) as exp')->first();
            $cabRev = (float)($cab->rev ?? 0);
            $cabExp = (float)($cab->exp ?? 0) ?: round($cabRev * 0.30);

            // Boats
            $boat    = BoatBooking::where('booking_status', 'confirmed')
                ->whereYear('booking_date', $yr)->whereMonth('booking_date', $mo)
                ->selectRaw('SUM(final_amount) as rev, SUM(vendor_cost) as exp')->first();
            $boatRev = (float)($boat->rev ?? 0);
            $boatExp = (float)($boat->exp ?? 0) ?: round($boatRev * 0.30);

            // Packages
            $pkg    = Booking::where('booking_status', 'confirmed')
                ->whereYear('booking_date', $yr)->whereMonth('booking_date', $mo)
                ->selectRaw('SUM(total_amount) as rev')->first();
            $pkgRev = (float)($pkg->rev ?? 0);
            $pkgExp = round($pkgRev * 0.30);

            $mRev = $tourRev + $cabRev + $boatRev + $pkgRev;
            $mExp = $tourExp + $cabExp + $boatExp + $pkgExp;

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

        $groups = [
            Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
                ->whereBetween('booking_start_date', [$start, $end])
                ->select('lead_source_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('lead_source_id')->get(),

            CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
                ->whereBetween('pickup_date', [$start, $end])
                ->select('lead_source_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('lead_source_id')->get(),

            BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
                ->whereBetween('booking_date', [$start, $end])
                ->select('lead_source_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(final_amount) as revenue'))
                ->groupBy('lead_source_id')->get(),
        ];

        foreach ($groups as $rows) {
            foreach ($rows as $r) {
                $key = $r->lead_source_id ?? 0;
                if (!isset($merged[$key])) {
                    $merged[$key] = ['lead_source_id' => $r->lead_source_id, 'cnt' => 0, 'revenue' => 0.0];
                }
                $merged[$key]['cnt']     += $r->cnt;
                $merged[$key]['revenue'] += (float)$r->revenue;
            }
        }

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

        $groups = [
            Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
                ->whereBetween('booking_start_date', [$start, $end])
                ->select('added_by as staff_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('added_by')->get(),

            CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
                ->whereBetween('pickup_date', [$start, $end])
                ->select('created_by as staff_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('created_by')->get(),

            BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
                ->whereBetween('booking_date', [$start, $end])
                ->select('created_by as staff_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(final_amount) as revenue'))
                ->groupBy('created_by')->get(),

            Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
                ->whereBetween('booking_date', [$start, $end])
                ->select('created_by as staff_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('created_by')->get(),
        ];

        foreach ($groups as $rows) {
            foreach ($rows as $r) {
                $key = $r->staff_id ?? 0;
                if (!isset($staffData[$key])) {
                    $staffData[$key] = ['staff_id' => $r->staff_id, 'cnt' => 0, 'revenue' => 0.0];
                }
                $staffData[$key]['cnt']     += $r->cnt;
                $staffData[$key]['revenue'] += (float)$r->revenue;
            }
        }

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

        Lead::where('booking_status', 'confirm')->whereNotNull('booking_start_date')
            ->whereBetween('booking_start_date', [$start, $end])
            ->orderByDesc('total_amount')->limit(10)
            ->get(['id','guest_name','contact','total_amount','total_expense','booking_start_date','short_plan'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->total_expense > 0) ? (float)$b->total_expense : round($b->total_amount * 0.3);
                $all->push(['type'=>'Tour','name'=>$b->guest_name,'contact'=>$b->contact,
                    'amount'=>(float)$b->total_amount,'expense'=>$exp,'profit'=>(float)$b->total_amount - $exp,
                    'date'=>$b->booking_start_date,'plan'=>$b->short_plan]);
            });

        CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->orderByDesc('total_amount')->limit(10)
            ->get(['id','customer_name','customer_phone','total_amount','vendor_cost','pickup_date','vehicle_name'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->total_amount * 0.3);
                $all->push(['type'=>'Cab','name'=>$b->customer_name,'contact'=>$b->customer_phone,
                    'amount'=>(float)$b->total_amount,'expense'=>$exp,'profit'=>(float)$b->total_amount - $exp,
                    'date'=>$b->pickup_date,'plan'=>$b->vehicle_name]);
            });

        BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('final_amount')->limit(10)
            ->get(['id','name','phone','final_amount','vendor_cost','booking_date','booking_type'])
            ->each(function ($b) use (&$all) {
                $exp = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->final_amount * 0.3);
                $all->push(['type'=>'Boat','name'=>$b->name,'contact'=>$b->phone,
                    'amount'=>(float)$b->final_amount,'expense'=>$exp,'profit'=>(float)$b->final_amount - $exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_type]);
            });

        Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('total_amount')->limit(10)
            ->get(['id','guest_name','total_amount','booking_date','booking_number'])
            ->each(function ($b) use (&$all) {
                $exp = round($b->total_amount * 0.3);
                $all->push(['type'=>'Package','name'=>$b->guest_name ?: 'Guest','contact'=>'',
                    'amount'=>(float)$b->total_amount,'expense'=>$exp,'profit'=>(float)$b->total_amount - $exp,
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
            ->get(['id','guest_name','contact','total_amount','total_expense','booking_start_date','short_plan','added_by'])
            ->each(function ($b) use (&$all) {
                $exp   = ($b->total_expense > 0) ? (float)$b->total_expense : round($b->total_amount * 0.3);
                $admin = Admin::find($b->added_by);
                $all->push(['type'=>'Tour','name'=>$b->guest_name,'contact'=>$b->contact,
                    'amount'=>(float)$b->total_amount,'expense'=>$exp,
                    'date'=>$b->booking_start_date,'plan'=>$b->short_plan,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        CabBooking::where('booking_status', 'confirmed')->whereNotNull('pickup_date')
            ->whereBetween('pickup_date', [$start, $end])
            ->orderByDesc('pickup_date')->limit(15)
            ->get(['id','customer_name','customer_phone','total_amount','vendor_cost','pickup_date','vehicle_name','created_by'])
            ->each(function ($b) use (&$all) {
                $exp   = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->total_amount * 0.3);
                $admin = Admin::find($b->created_by);
                $all->push(['type'=>'Cab','name'=>$b->customer_name,'contact'=>$b->customer_phone,
                    'amount'=>(float)$b->total_amount,'expense'=>$exp,
                    'date'=>$b->pickup_date,'plan'=>$b->vehicle_name,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        BoatBooking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('booking_date')->limit(15)
            ->get(['id','name','phone','final_amount','vendor_cost','booking_date','booking_type','created_by'])
            ->each(function ($b) use (&$all) {
                $exp   = ($b->vendor_cost > 0) ? (float)$b->vendor_cost : round($b->final_amount * 0.3);
                $admin = Admin::find($b->created_by);
                $all->push(['type'=>'Boat','name'=>$b->name,'contact'=>$b->phone,
                    'amount'=>(float)$b->final_amount,'expense'=>$exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_type,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        Booking::where('booking_status', 'confirmed')->whereNotNull('booking_date')
            ->whereBetween('booking_date', [$start, $end])
            ->orderByDesc('booking_date')->limit(15)
            ->get(['id','guest_name','total_amount','booking_date','booking_number','created_by'])
            ->each(function ($b) use (&$all) {
                $exp   = round($b->total_amount * 0.3);
                $admin = Admin::find($b->created_by);
                $all->push(['type'=>'Package','name'=>$b->guest_name ?: 'Guest','contact'=>'',
                    'amount'=>(float)$b->total_amount,'expense'=>$exp,
                    'date'=>$b->booking_date,'plan'=>$b->booking_number,
                    'staff'=>$admin ? $admin->name : '—']);
            });

        return $all->sortByDesc('date')->take(15)->values();
    }

    public function exportReport(Request $request)
    {
        return response()->json(['message' => 'Export coming soon']);
    }
}
