@extends('admin.layouts.app')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
:root {
  --bg:     #F1F5F9;
  --card:   #FFFFFF;
  --border: #E2E8F0;
  --shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.06);
  --text:   #0F172A;
  --sub:    #475569;
  --muted:  #94A3B8;
  --indigo: #4F46E5;
  --violet: #7C3AED;
  --emerald:#10B981;
  --amber:  #F59E0B;
  --sky:    #0EA5E9;
  --r:      16px;
  --t:      .2s ease;
}
body.dark-mode {
  --bg:     #0F172A;
  --card:   #1E293B;
  --border: #334155;
  --shadow: 0 1px 3px rgba(0,0,0,.3), 0 4px 16px rgba(0,0,0,.25);
  --text:   #F1F5F9;
  --sub:    #CBD5E1;
  --muted:  #64748B;
}
body.dark-mode .dk-page       { background:#0F172A !important; }
body.dark-mode .kpi-card,
body.dark-mode .chart-card    { background:#1E293B !important; border-color:#334155 !important; }
body.dark-mode .chart-head    { border-color:#334155 !important; background:#1E293B !important; }
body.dark-mode .chart-title   { color:#F1F5F9 !important; }
body.dark-mode .kpi-label     { color:#64748B !important; }
body.dark-mode .kpi-value     { color:#F1F5F9 !important; }
body.dark-mode .prog-bar      { background:#334155 !important; }
body.dark-mode .month-select  { background:#1E293B !important; color:#F1F5F9 !important; border-color:#334155 !important; }

.dk-page { background:var(--bg); min-height:100vh; padding:18px 22px 60px; transition:background .3s; }

.dk-hero {
  background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 45%, #0EA5E9 100%);
  border-radius:22px; padding:26px 30px; margin-bottom:22px; margin-top:52px;
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px;
  position:relative; overflow:hidden; box-shadow:0 12px 40px rgba(79,70,229,.3);
}
.dk-hero-left { position:relative; z-index:1; }
.dk-hero h1   { color:#fff; font-size:1.45rem; font-weight:900; margin:0; letter-spacing:-.02em; }
.dk-hero p    { color:rgba(255,255,255,.75); font-size:.84rem; margin:.4rem 0 0; }

.month-switcher { position:relative; z-index:1; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.month-arrow {
  width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center;
  background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3); color:#fff;
  text-decoration:none; font-weight:800; transition:.18s; flex-shrink:0;
}
.month-arrow:hover { background:rgba(255,255,255,.28); color:#fff; }
.month-select {
  background:rgba(255,255,255,.95); border:1.5px solid rgba(255,255,255,.3); border-radius:11px;
  padding:9px 14px; font-size:.85rem; font-weight:700; color:#4F46E5; min-width:160px;
}

.kpi-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
@media(max-width:900px) {.kpi-grid{grid-template-columns:1fr;}}

.kpi-card {
  background:var(--card); border-radius:var(--r); border:1px solid var(--border);
  box-shadow:var(--shadow); padding:20px 22px; display:flex; align-items:flex-start;
  gap:15px; position:relative; overflow:hidden; transition:all var(--t);
}
.kpi-card::after {
  content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
  background:var(--kpi-color, var(--indigo));
}
.kpi-icon {
  width:52px; height:52px; border-radius:14px; display:flex; align-items:center;
  justify-content:center; font-size:1.5rem; flex-shrink:0;
  background:var(--kpi-bg, #EEF2FF);
}
.kpi-body { flex:1; min-width:0; }
.kpi-label { font-size:.7rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:5px; }
.kpi-value { font-size:1.8rem; font-weight:900; color:var(--text); line-height:1; }
.kpi-sub   { font-size:.72rem; color:var(--muted); margin-top:5px; display:flex; align-items:center; gap:5px; flex-wrap:wrap; }
.kpi-badge { font-size:.64rem; font-weight:700; padding:2px 8px; border-radius:20px; }
.kpi-badge.up   { background:#D1FAE5; color:#065F46; }
.kpi-badge.down { background:#FEE2E2; color:#991B1B; }
.kpi-badge.neu  { background:#E0E7FF; color:#3730A3; }

.dk-pie-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:20px; }
@media(max-width:1300px){.dk-pie-row{grid-template-columns:1fr 1fr;}}
@media(max-width:700px){.dk-pie-row{grid-template-columns:1fr;}}

.chart-card {
  background:var(--card); border-radius:var(--r); border:1px solid var(--border);
  box-shadow:var(--shadow); overflow:hidden; margin-bottom:20px; transition:background .3s,border-color .3s;
}
.chart-head {
  padding:14px 20px; border-bottom:1px solid var(--border);
  display:flex; align-items:center; justify-content:space-between; gap:10px;
  background:var(--card);
}
.chart-title { font-size:.9rem; font-weight:800; color:var(--text); }
.chart-body  { padding:18px 16px; }

.prog-bar  { height:6px; background:#E2E8F0; border-radius:99px; overflow:hidden; margin-top:4px; }
.prog-fill { height:100%; border-radius:99px; transition:width .5s ease; }

.table-card {
  background:var(--card); border-radius:var(--r); border:1px solid var(--border);
  box-shadow:var(--shadow); overflow:hidden; margin-bottom:20px;
}
.table-head {
  padding:14px 20px; border-bottom:1px solid var(--border);
  display:flex; align-items:center; justify-content:space-between; background:var(--card);
}
.table-title { font-size:.9rem; font-weight:800; color:var(--text); }
.dk-table { width:100%; border-collapse:collapse; }
.dk-table th {
  background:#F8FAFC; color:var(--muted); font-size:.68rem; font-weight:700;
  text-transform:uppercase; letter-spacing:.05em; padding:10px 16px; text-align:left;
}
.dk-table td { padding:11px 16px; border-bottom:1px solid #F1F5F9; font-size:.82rem; color:var(--text); }
.dk-table tr:last-child td { border-bottom:none; }
.dk-table tr:hover td { background:#F8FAFF; }
.dk-table tfoot td { font-weight:800; background:#F8FAFC; border-top:2px solid var(--border); }
.bk-link { color:var(--indigo); font-weight:700; text-decoration:none; font-size:.8rem; font-family:monospace; }
.bk-link:hover { text-decoration:underline; }
.sb { display:inline-flex; align-items:center; font-size:.63rem; font-weight:700; padding:3px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em; white-space:nowrap; }
.sb-confirmed { background:#DBEAFE; color:#1E40AF; }
.sb-completed { background:#D1FAE5; color:#065F46; }
.sb-cancelled { background:#FEE2E2; color:#991B1B; }
.sb-pending   { background:#FEF9C3; color:#854D0E; }
body.dark-mode .table-card { background:#1E293B !important; border-color:#334155 !important; }
body.dark-mode .table-head { border-color:#334155 !important; background:#1E293B !important; }
body.dark-mode .table-title{ color:#F1F5F9 !important; }
body.dark-mode .dk-table th{ background:#0F172A !important; color:#64748B !important; }
body.dark-mode .dk-table td{ color:#F1F5F9 !important; border-color:#334155 !important; }
body.dark-mode .dk-table tfoot td { background:#0F172A !important; border-color:#334155 !important; }

@media(max-width:767px){
  .dk-page     { padding:10px 10px 70px; }
  .dk-hero     { padding:16px 16px; margin-top:58px; border-radius:16px; flex-direction:column; align-items:stretch; }
  .dk-hero h1  { font-size:1.1rem; }
  .kpi-value   { font-size:1.4rem; }
  .month-select{ min-width:0; flex:1; }
}
</style>

<div class="dk-page">

<div class="dk-hero">
  <div class="dk-hero-left">
    <h1>📅 Monthly Report</h1>
    <p>Figures scoped by service date (check-in / pickup / ride date), not the date the sale was made.</p>
  </div>
  <form class="month-switcher" method="GET" action="{{ route('monthly-report.index') }}">
    <a class="month-arrow" href="{{ route('monthly-report.index', ['month' => $prevMonth->format('Y-m')]) }}" title="Previous month">←</a>
    <select name="month" class="month-select" onchange="this.form.submit()">
      @foreach($monthOptions as $opt)
        <option value="{{ $opt['value'] }}" {{ $opt['value'] === $selectedMonth->format('Y-m') ? 'selected' : '' }}>{{ $opt['label'] }}</option>
      @endforeach
    </select>
    <a class="month-arrow" href="{{ route('monthly-report.index', ['month' => $nextMonth->format('Y-m')]) }}" title="Next month">→</a>
  </form>
</div>

{{-- ══ DONUTS ══ --}}
<div class="dk-pie-row">
  {{-- Booking Mix --}}
  <div class="chart-card">
    <div class="chart-head">
      <div class="chart-title">🥧 Booking Mix</div>
      <span style="font-size:.7rem;color:var(--muted);">{{ $selectedMonth->format('M Y') }}</span>
    </div>
    <div class="chart-body" style="display:flex;align-items:center;gap:18px;">
      <div style="width:120px;flex-shrink:0;">
        <canvas id="donutChart"></canvas>
      </div>
      <div style="flex:1;min-width:0;">
        @php $typeTotal = array_sum($typeData); @endphp
        @foreach($typeLabels as $i => $lbl)
        @php
          $pct    = $typeTotal > 0 ? round($typeData[$i] / $typeTotal * 100) : 0;
          $colors = ['#4F46E5','#F59E0B','#0EA5E9'];
          $icons  = ['🏨','🚗','⛵'];
        @endphp
        <div style="margin-bottom:12px;">
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:.76rem;margin-bottom:5px;">
            <span style="font-weight:700;color:var(--text);">{{ $icons[$i] ?? '' }} {{ $lbl }}</span>
            <span style="color:var(--muted);font-size:.7rem;">{{ $typeData[$i] }} <span style="font-weight:700;color:{{ $colors[$i] }};">({{ $pct }}%)</span></span>
          </div>
          <div class="prog-bar">
            <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $colors[$i] ?? '#94A3B8' }};"></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Revenue vs Expense --}}
  <div class="chart-card">
    <div class="chart-head">
      <div class="chart-title">📊 Revenue vs Expense</div>
      <span style="font-size:.7rem;color:var(--muted);">{{ $selectedMonth->format('M Y') }}</span>
    </div>
    <div class="chart-body" style="display:flex;align-items:center;gap:18px;">
      <div style="width:120px;flex-shrink:0;position:relative;">
        <canvas id="revExpPieChart"></canvas>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
          <div style="font-size:.85rem;font-weight:900;color:var(--text);line-height:1;">{{ $totalProfitMonth >= 0 ? '+' : '' }}{{ $totalRevenueMonth > 0 ? round(($totalProfitMonth/$totalRevenueMonth)*100) : 0 }}%</div>
          <div style="font-size:.58rem;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.03em;">margin</div>
        </div>
      </div>
      <div style="flex:1;min-width:0;">
        @php
          $revExpTotal = $totalRevenueMonth + $totalExpenseMonth;
          $revPct = $revExpTotal > 0 ? round($totalRevenueMonth / $revExpTotal * 100) : 0;
          $expPct = $revExpTotal > 0 ? round($totalExpenseMonth / $revExpTotal * 100) : 0;
        @endphp
        <div style="margin-bottom:12px;">
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:.76rem;margin-bottom:5px;">
            <span style="font-weight:700;color:var(--text);">💰 Revenue</span>
            <span style="color:var(--muted);font-size:.7rem;">₹{{ number_format($totalRevenueMonth,0) }} <span style="font-weight:700;color:#4F46E5;">({{ $revPct }}%)</span></span>
          </div>
          <div class="prog-bar"><div class="prog-fill" style="width:{{ $revPct }}%;background:#4F46E5;"></div></div>
        </div>
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:.76rem;margin-bottom:5px;">
            <span style="font-weight:700;color:var(--text);">📉 Expense</span>
            <span style="color:var(--muted);font-size:.7rem;">₹{{ number_format($totalExpenseMonth,0) }} <span style="font-weight:700;color:#F43F5E;">({{ $expPct }}%)</span></span>
          </div>
          <div class="prog-bar"><div class="prog-fill" style="width:{{ $expPct }}%;background:#F43F5E;"></div></div>
        </div>
      </div>
    </div>
  </div>

  {{-- Expense Cost Split --}}
  <div class="chart-card">
    <div class="chart-head">
      <div class="chart-title">📊 Expense Cost Split</div>
      <span style="font-size:.7rem;color:var(--muted);">{{ $selectedMonth->format('M Y') }}</span>
    </div>
    <div class="chart-body" style="display:flex;align-items:center;gap:18px;">
      <div style="width:120px;flex-shrink:0;position:relative;">
        <canvas id="expenseSplitPieChart"></canvas>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
          <div style="font-size:.78rem;font-weight:900;color:var(--text);line-height:1;">₹{{ $totalExpenseMonth >= 100000 ? round($totalExpenseMonth/100000,1).'L' : round($totalExpenseMonth/1000).'K' }}</div>
          <div style="font-size:.58rem;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.03em;">total</div>
        </div>
      </div>
      <div style="flex:1;min-width:0;">
        @php
          $expSplitTotal = max(array_sum(array_column($expenseSplit, 'val')), 1);
          $expColors = ['#4F46E5','#F59E0B','#0EA5E9','#B45309'];
          $expIcons  = ['🏨','🚗','⛵','🧾'];
        @endphp
        @foreach($expenseSplit as $i => $row)
        @if($row['val'] > 0)
        @php $pct = round($row['val'] / $expSplitTotal * 100); @endphp
        <div style="margin-bottom:12px;">
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:.76rem;margin-bottom:5px;">
            <span style="font-weight:700;color:var(--text);">{{ $expIcons[$i] ?? '' }} {{ $row['label'] }}</span>
            <span style="color:var(--muted);font-size:.7rem;">₹{{ number_format($row['val'],0) }} <span style="font-weight:700;color:{{ $expColors[$i] ?? '#94A3B8' }};">({{ $pct }}%)</span></span>
          </div>
          <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%;background:{{ $expColors[$i] ?? '#94A3B8' }};"></div></div>
        </div>
        @endif
        @endforeach
      </div>
    </div>
  </div>
</div>

{{-- ══ KPI CARDS ══ --}}
<div class="kpi-grid">
  <div class="kpi-card" style="--kpi-color:var(--emerald);--kpi-bg:#D1FAE5;">
    <div class="kpi-icon">💰</div>
    <div class="kpi-body">
      <div class="kpi-label">{{ $selectedMonth->format('M Y') }} Revenue</div>
      <div class="kpi-value">₹{{ number_format($totalRevenueMonth) }}</div>
      <div class="kpi-sub">
        @if($revenueGrowth > 0)
          <span class="kpi-badge up">↑ {{ abs($revenueGrowth) }}% vs previous month</span>
        @elseif($revenueGrowth < 0)
          <span class="kpi-badge down">↓ {{ abs($revenueGrowth) }}% vs previous month</span>
        @else
          <span class="kpi-badge neu">Same as previous month</span>
        @endif
      </div>
    </div>
  </div>

  <div class="kpi-card" style="--kpi-color:var(--amber);--kpi-bg:#FEF3C7;">
    <div class="kpi-icon">⚠️</div>
    <div class="kpi-body">
      <div class="kpi-label">Pending Amount</div>
      <div class="kpi-value">₹{{ number_format($totalPending) }}</div>
      <div class="kpi-sub">{{ $pendingPaymentsCount }} bookings have due balance</div>
    </div>
  </div>

  <div class="kpi-card" style="--kpi-color:var(--sky);--kpi-bg:#E0F2FE;">
    <div class="kpi-icon">📅</div>
    <div class="kpi-body">
      <div class="kpi-label">{{ $selectedMonth->format('M Y') }} Bookings</div>
      <div class="kpi-value">{{ $totalBookingsMonth }}</div>
      <div class="kpi-sub">
        🏨 {{ $stayMonth }} Stay &nbsp;·&nbsp; 🚗 {{ $cabMonth }} Cab &nbsp;·&nbsp; ⛵ {{ $boatMonth }} Boat
      </div>
    </div>
  </div>
</div>

{{-- ══ BOOKINGS LIST ══ --}}
<div class="table-card">
  <div class="table-head">
    <div class="table-title">📋 Bookings — {{ $selectedMonth->format('F Y') }} ({{ $bookingsList->count() }})</div>
    <span style="font-size:.7rem;color:var(--muted);">Sorted by service date</span>
  </div>
  <div style="overflow-x:auto;">
    <table class="dk-table">
      <thead>
        <tr>
          <th>Type</th>
          <th>Booking #</th>
          <th>Guest</th>
          <th>Service Date</th>
          <th>Added By</th>
          <th>Status</th>
          <th style="text-align:right;">Amount</th>
          <th style="text-align:right;">Pending</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bookingsList as $b)
        <tr>
          <td>{{ $b['icon'] }} {{ $b['type'] }}</td>
          <td><a href="{{ $b['url'] }}" class="bk-link">{{ $b['number'] }}</a></td>
          <td>{{ $b['guest'] }}</td>
          <td>{{ $b['date'] ? \Carbon\Carbon::parse($b['date'])->format('d M Y') : '—' }}</td>
          <td>{{ $b['added_by'] }}</td>
          <td><span class="sb sb-{{ $b['status'] }}">{{ str_replace('_',' ',$b['status']) }}</span></td>
          <td style="text-align:right;font-weight:700;">₹{{ number_format($b['amount']) }}</td>
          <td style="text-align:right;{{ $b['pending'] > 0 ? 'color:#B45309;font-weight:700;' : '' }}">₹{{ number_format($b['pending']) }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center;padding:28px;color:var(--muted);">No bookings with a service date in {{ $selectedMonth->format('F Y') }}.</td>
        </tr>
        @endforelse
      </tbody>
      @if($bookingsList->count())
      <tfoot>
        <tr>
          <td colspan="6" style="text-align:right;">Total</td>
          <td style="text-align:right;">₹{{ number_format($bookingsList->sum('amount')) }}</td>
          <td style="text-align:right;">₹{{ number_format($bookingsList->sum('pending')) }}</td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>

</div>{{-- /dk-page --}}

<script>
const isDark = () => document.body.classList.contains('dark-mode');
const chartBgColor = () => isDark() ? '#1E293B' : '#FFFFFF';

new Chart(document.getElementById('revExpPieChart'), {
  type: 'doughnut',
  data: {
    labels: ['Revenue', 'Expense'],
    datasets: [{
      data: [{{ $totalRevenueMonth }}, {{ $totalExpenseMonth }}],
      backgroundColor: ['#4F46E5', '#F43F5E'],
      borderWidth: 3,
      borderColor: chartBgColor(),
      hoverOffset: 8
    }]
  },
  options: {
    responsive:true, cutout:'72%',
    plugins: {
      legend:{ display:false },
      tooltip:{ callbacks:{ label: ctx => ' ' + ctx.label + ': ₹' + ctx.parsed.toLocaleString('en-IN') }}
    }
  }
});

var monthlyExpSplit = {!! json_encode($expenseSplit) !!}.filter(r => r.val > 0);
new Chart(document.getElementById('expenseSplitPieChart'), {
  type: 'doughnut',
  data: {
    labels: monthlyExpSplit.map(r => r.label),
    datasets: [{
      data: monthlyExpSplit.map(r => r.val),
      backgroundColor: ['#4F46E5','#F59E0B','#0EA5E9','#B45309'],
      borderWidth: 3,
      borderColor: chartBgColor(),
      hoverOffset: 8
    }]
  },
  options: {
    responsive:true, cutout:'72%',
    plugins: {
      legend:{ display:false },
      tooltip:{ callbacks:{ label: ctx => ' ' + ctx.label + ': ₹' + ctx.parsed.toLocaleString('en-IN') }}
    }
  }
});

new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($typeLabels) !!},
    datasets: [{
      data: {!! json_encode($typeData) !!},
      backgroundColor: ['#4F46E5','#F59E0B','#0EA5E9'],
      borderWidth: 3,
      borderColor: chartBgColor(),
      hoverOffset: 8
    }]
  },
  options: {
    responsive:true, cutout:'72%',
    plugins: { legend:{ display:false }, tooltip:{ callbacks:{ label: ctx => ' ' + ctx.label + ': ' + ctx.parsed }}}
  }
});
</script>

@endsection
