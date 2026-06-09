@extends('admin.layouts.app')
@section('content')
<style>
:root{--pa:#4F46E5;--pg:#10B981;--pr:#EF4444;--pa-am:#F59E0B;--pt:#0F172A;--pm:#64748B;--pb:#E2E8F0;}
.pan-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* Header */
.pan-header{background:linear-gradient(135deg,#0f172a,#1e3a5f,#4F46E5);border-radius:16px;padding:22px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 28px rgba(79,70,229,.3);}
.pan-header::before{content:'';position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(255,255,255,.06);border-radius:50%;}
.pan-header-left{position:relative;z-index:1;}
.pan-header-left h1{color:#fff;font-size:1.2rem;font-weight:800;margin:0 0 3px;}
.pan-header-left p{color:rgba(255,255,255,.65);font-size:.78rem;margin:0;}
.pan-data-note{position:relative;z-index:1;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:10px;padding:7px 14px;font-size:.75rem;color:rgba(255,255,255,.8);white-space:nowrap;}

/* Filter bar */
.pan-filter{background:#fff;border-radius:14px;border:1px solid var(--pb);box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 20px;margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;}
.pan-filter-group{display:flex;flex-direction:column;gap:5px;flex:1;min-width:130px;}
.pan-filter-group label{font-size:.7rem;font-weight:700;color:var(--pm);text-transform:uppercase;letter-spacing:.04em;}
.pan-filter-group input,.pan-filter-group select{border:1.5px solid var(--pb);border-radius:9px;padding:7px 11px;font-size:.82rem;color:var(--pt);background:#FAFBFF;height:36px;outline:none;transition:border-color .15s;}
.pan-filter-group input:focus,.pan-filter-group select:focus{border-color:var(--pa);}
.pan-filter-btn{height:36px;padding:0 18px;border-radius:9px;border:none;background:var(--pa);color:#fff;font-size:.82rem;font-weight:700;cursor:pointer;white-space:nowrap;align-self:flex-end;}
.pan-filter-clear{height:36px;padding:0 14px;border-radius:9px;border:1.5px solid var(--pb);background:#F1F5F9;color:var(--pm);font-size:.82rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;align-self:flex-end;}

/* KPI grid */
.pan-kpi{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:20px;}
.pan-kpi-card{border-radius:14px;padding:18px 20px;position:relative;overflow:hidden;}
.pan-kpi-card::before{content:'';position:absolute;top:-30px;right:-30px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.08);}
.pan-kpi-icon{width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-bottom:10px;flex-shrink:0;}
.pan-kpi-icon i[data-feather]{width:18px;height:18px;stroke:#fff;}
.pan-kpi-val{font-size:1.5rem;font-weight:900;color:#fff;line-height:1;margin-bottom:4px;}
.pan-kpi-lbl{font-size:.7rem;font-weight:600;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.05em;}
.kpi-revenue {background:linear-gradient(135deg,#4F46E5,#7C3AED);}
.kpi-expense {background:linear-gradient(135deg,#DC2626,#EF4444);}
.kpi-profit  {background:linear-gradient(135deg,#059669,#10B981);}
.kpi-margin  {background:linear-gradient(135deg,#B45309,#F59E0B);}
.kpi-bookings{background:linear-gradient(135deg,#0369A1,#0EA5E9);}

/* Section cards */
.pan-card{background:#fff;border-radius:14px;border:1px solid var(--pb);box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;margin-bottom:16px;}
.pan-card-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#FAFBFF;}
.pan-card-title{font-size:.88rem;font-weight:700;color:var(--pt);display:flex;align-items:center;gap:8px;}
.pan-card-title i[data-feather]{width:15px;height:15px;stroke:var(--pa);}
.pan-card-body{padding:18px 20px;}

/* Grid layout */
.pan-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}

/* Source/staff list */
.pan-list-item{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #F8FAFC;}
.pan-list-item:last-child{border-bottom:none;}
.pan-list-name{flex:1;font-size:.83rem;font-weight:600;color:var(--pt);min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.pan-list-cnt{font-size:.72rem;color:var(--pm);background:#F1F5F9;border-radius:20px;padding:1px 8px;flex-shrink:0;}
.pan-list-rev{font-size:.83rem;font-weight:800;color:var(--pa);white-space:nowrap;}
.pan-pbar-wrap{flex:1;min-width:60px;max-width:80px;}
.pan-pbar{height:6px;background:#E2E8F0;border-radius:3px;overflow:hidden;}
.pan-pbar-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,#4F46E5,#7C3AED);}

/* Table */
.pan-table{width:100%;border-collapse:collapse;font-size:.82rem;}
.pan-table th{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--pm);padding:9px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.pan-table td{padding:11px 14px;border-bottom:1px solid #F8FAFC;color:var(--pt);vertical-align:middle;}
.pan-table tbody tr:hover{background:#FAFBFF;}
.pan-table tbody tr:last-child td{border-bottom:none;}
.pan-amt-pos{font-weight:800;color:#065F46;}
.pan-amt-neg{font-weight:800;color:#991B1B;}
.pan-amt-blue{font-weight:800;color:var(--pa);}

/* Chart wrap */
.pan-chart-wrap{height:240px;position:relative;}

/* Responsive */
@media(max-width:1100px){.pan-kpi{grid-template-columns:repeat(3,1fr);}}
@media(max-width:768px){
    .pan-page{padding:12px;}
    .pan-kpi{grid-template-columns:repeat(2,1fr);}
    .pan-grid-2{grid-template-columns:1fr;}
    .pan-header{flex-direction:column;align-items:flex-start;padding:16px 18px;}
}
@media(max-width:480px){.pan-kpi{grid-template-columns:1fr 1fr;}}
</style>

<div class="pan-page">

{{-- Header --}}
<div class="pan-header">
    <div class="pan-header-left">
        <h1>📊 Profit Analytics</h1>
        <p>Real revenue, expense & profit from completed bookings</p>
    </div>
    <div class="pan-data-note">
        📅 {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} – {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('profit-analytics.index') }}" class="pan-filter">
    <div class="pan-filter-group">
        <label>Start Date</label>
        <input type="date" name="start_date" value="{{ $startDate }}">
    </div>
    <div class="pan-filter-group">
        <label>End Date</label>
        <input type="date" name="end_date" value="{{ $endDate }}">
    </div>
    <button type="submit" class="pan-filter-btn">Apply</button>
    <a href="{{ route('profit-analytics.index') }}" class="pan-filter-clear">Reset</a>
</form>

{{-- KPI Cards --}}
<div class="pan-kpi">
    <div class="pan-kpi-card kpi-revenue">
        <div class="pan-kpi-icon"><i data-feather="trending-up"></i></div>
        <div class="pan-kpi-val">₹{{ number_format($totalRevenue,0) }}</div>
        <div class="pan-kpi-lbl">Total Revenue</div>
    </div>
    <div class="pan-kpi-card kpi-expense">
        <div class="pan-kpi-icon"><i data-feather="trending-down"></i></div>
        <div class="pan-kpi-val">₹{{ number_format($totalExpense,0) }}</div>
        <div class="pan-kpi-lbl">Total Expenses</div>
    </div>
    <div class="pan-kpi-card kpi-profit">
        <div class="pan-kpi-icon"><i data-feather="dollar-sign"></i></div>
        <div class="pan-kpi-val">₹{{ number_format($totalProfit,0) }}</div>
        <div class="pan-kpi-lbl">Net Profit</div>
    </div>
    <div class="pan-kpi-card kpi-margin">
        <div class="pan-kpi-icon"><i data-feather="percent"></i></div>
        <div class="pan-kpi-val">{{ $profitMargin }}%</div>
        <div class="pan-kpi-lbl">Profit Margin</div>
    </div>
    <div class="pan-kpi-card kpi-bookings">
        <div class="pan-kpi-icon"><i data-feather="calendar"></i></div>
        <div class="pan-kpi-val">{{ $totalBookings }}</div>
        <div class="pan-kpi-lbl">Completed Bookings</div>
    </div>
</div>

{{-- Charts row --}}
<div class="pan-grid-2">
    {{-- Monthly Trend --}}
    <div class="pan-card">
        <div class="pan-card-head">
            <div class="pan-card-title"><i data-feather="bar-chart-2"></i> 6-Month Trend</div>
        </div>
        <div class="pan-card-body">
            <div class="pan-chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
    </div>

    {{-- Source Breakdown --}}
    <div class="pan-card">
        <div class="pan-card-head">
            <div class="pan-card-title"><i data-feather="pie-chart"></i> Revenue by Source</div>
        </div>
        <div class="pan-card-body">
            @forelse($sourceBreakdown as $src)
            <div class="pan-list-item">
                <div class="pan-list-name">{{ $src['label'] }}</div>
                <div class="pan-pbar-wrap">
                    <div class="pan-pbar"><div class="pan-pbar-fill" style="width:{{ $src['pct'] }}%;"></div></div>
                </div>
                <div class="pan-list-cnt">{{ $src['cnt'] }}</div>
                <div class="pan-list-rev">₹{{ number_format($src['revenue'],0) }}</div>
            </div>
            @empty
            <p style="color:var(--pm);font-size:.83rem;text-align:center;padding:20px 0;">No data for selected period</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Staff & Top Bookings --}}
<div class="pan-grid-2">
    {{-- Staff Breakdown --}}
    <div class="pan-card">
        <div class="pan-card-head">
            <div class="pan-card-title"><i data-feather="users"></i> Revenue by Staff</div>
        </div>
        <div class="pan-card-body">
            @php $maxStaff = $staffBreakdown->max('revenue') ?: 1; @endphp
            @forelse($staffBreakdown as $staff)
            <div class="pan-list-item">
                <div class="pan-list-name">{{ $staff['name'] }}</div>
                <div class="pan-pbar-wrap">
                    <div class="pan-pbar"><div class="pan-pbar-fill" style="width:{{ round(($staff['revenue']/$maxStaff)*100) }}%;background:linear-gradient(90deg,#059669,#10B981);"></div></div>
                </div>
                <div class="pan-list-cnt">{{ $staff['cnt'] }}</div>
                <div class="pan-list-rev" style="color:#059669;">₹{{ number_format($staff['revenue'],0) }}</div>
            </div>
            @empty
            <p style="color:var(--pm);font-size:.83rem;text-align:center;padding:20px 0;">No data for selected period</p>
            @endforelse
        </div>
    </div>

    {{-- Avg deal + margin summary --}}
    <div class="pan-card">
        <div class="pan-card-head">
            <div class="pan-card-title"><i data-feather="activity"></i> Summary Stats</div>
        </div>
        <div class="pan-card-body">
            @php
                $summaryItems = [
                    ['label'=>'Avg Deal Value',        'val'=>'₹'.number_format($avgDeal,0),        'color'=>'#4F46E5'],
                    ['label'=>'Total Revenue',         'val'=>'₹'.number_format($totalRevenue,0),   'color'=>'#4F46E5'],
                    ['label'=>'Total Expenses',        'val'=>'₹'.number_format($totalExpense,0),   'color'=>'#DC2626'],
                    ['label'=>'Net Profit',            'val'=>'₹'.number_format($totalProfit,0),    'color'=>'#059669'],
                    ['label'=>'Profit Margin',         'val'=>$profitMargin.'%',                    'color'=>'#B45309'],
                    ['label'=>'Completed Bookings',    'val'=>$totalBookings,                       'color'=>'#0369A1'],
                ];
            @endphp
            @foreach($summaryItems as $item)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px dashed #F1F5F9;">
                <span style="font-size:.82rem;color:var(--pm);font-weight:600;">{{ $item['label'] }}</span>
                <span style="font-size:.88rem;font-weight:800;color:{{ $item['color'] }};">{{ $item['val'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Bookings Table --}}
@php
$typeBadge = ['Tour'=>'#4F46E5','Cab'=>'#0369A1','Boat'=>'#0891B2','Package'=>'#059669'];
@endphp
<div class="pan-card">
    <div class="pan-card-head">
        <div class="pan-card-title"><i data-feather="star"></i> Top Bookings by Revenue</div>
        <span style="font-size:.73rem;color:var(--pm);">Top 10 · All Types</span>
    </div>
    <div class="table-responsive">
        <table class="pan-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Guest</th>
                    <th>Date</th>
                    <th>Plan / Vehicle</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Expense</th>
                    <th class="text-end">Profit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topBookings as $i => $b)
                <tr>
                    <td style="font-weight:700;color:var(--pm);">{{ $i+1 }}</td>
                    <td><span style="font-size:.68rem;font-weight:700;color:#fff;background:{{ $typeBadge[$b['type']] ?? '#64748B' }};border-radius:5px;padding:2px 7px;">{{ $b['type'] }}</span></td>
                    <td>
                        <div style="font-weight:700;font-size:.84rem;">{{ $b['name'] }}</div>
                        <div style="font-size:.72rem;color:var(--pm);">{{ $b['contact'] }}</div>
                    </td>
                    <td style="font-size:.78rem;white-space:nowrap;">{{ $b['date'] ? \Carbon\Carbon::parse($b['date'])->format('d M Y') : '—' }}</td>
                    <td style="font-size:.75rem;color:var(--pm);max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b['plan'] ?? '—' }}</td>
                    <td class="text-end pan-amt-blue">₹{{ number_format($b['amount'],0) }}</td>
                    <td class="text-end pan-amt-neg">₹{{ number_format($b['expense'],0) }}</td>
                    <td class="text-end pan-amt-pos">₹{{ number_format($b['profit'],0) }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:28px;color:var(--pm);">No data for selected period</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();

    // Monthly Trend Chart
    var trend = @json($monthlyTrend);
    var ctx = document.getElementById('trendChart');
    if (ctx && trend.length) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trend.map(t => t.month),
                datasets: [
                    {
                        label: 'Revenue',
                        data: trend.map(t => t.revenue),
                        backgroundColor: 'rgba(79,70,229,.8)',
                        borderRadius: 5,
                        order: 2
                    },
                    {
                        label: 'Expense',
                        data: trend.map(t => t.expense),
                        backgroundColor: 'rgba(239,68,68,.65)',
                        borderRadius: 5,
                        order: 3
                    },
                    {
                        label: 'Net Profit',
                        data: trend.map(t => t.profit),
                        type: 'line',
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16,185,129,.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, padding: 14, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: c => ' ' + c.dataset.label + ': ₹' + Math.abs(c.parsed.y).toLocaleString('en-IN')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,.04)' },
                        ticks: {
                            font: { size: 10 },
                            callback: v => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K')
                        }
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }
});
</script>
@endsection
