@extends('admin.layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
:root {
  --rp-bg:#F1F5F9; --rp-card:#fff; --rp-border:#E2E8F0;
  --rp-shadow:0 1px 3px rgba(0,0,0,.05),0 4px 16px rgba(0,0,0,.06);
  --rp-text:#0F172A; --rp-sub:#475569; --rp-muted:#94A3B8;
  --indigo:#4F46E5; --emerald:#10B981; --amber:#F59E0B; --rose:#EF4444; --sky:#0EA5E9;
}
.rp-page { background:var(--rp-bg); min-height:100vh; padding:20px 22px; }
.rp-header { background:linear-gradient(135deg,#0F172A,#1E3A5F); border-radius:16px; padding:22px 28px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.rp-header h1 { color:#fff; font-size:1.4rem; font-weight:800; margin:0; }
.rp-header p { color:rgba(255,255,255,.6); font-size:.8rem; margin:.2rem 0 0; }

/* Filter bar */
.filter-bar { background:var(--rp-card); border:1px solid var(--rp-border); border-radius:14px; padding:16px 20px; margin-bottom:20px; display:flex; align-items:center; gap:14px; flex-wrap:wrap; box-shadow:var(--rp-shadow); }
.filter-bar label { font-size:.72rem; font-weight:700; color:var(--rp-muted); text-transform:uppercase; letter-spacing:.04em; display:block; margin-bottom:3px; }
.filter-bar input, .filter-bar select { border:1.5px solid var(--rp-border); border-radius:9px; padding:7px 12px; font-size:.82rem; color:var(--rp-text); background:#FAFBFF; }
.filter-btn { background:var(--indigo); color:#fff; border:none; border-radius:9px; padding:9px 20px; font-size:.82rem; font-weight:700; cursor:pointer; }
.filter-btn:hover { background:#4338CA; }

/* KPI row */
.rp-kpi { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
@media(max-width:900px){.rp-kpi{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px){.rp-kpi{grid-template-columns:1fr;}}

.rp-kpi-card { background:var(--rp-card); border-radius:14px; border:1px solid var(--rp-border); box-shadow:var(--rp-shadow); padding:18px 20px; border-left:4px solid var(--kc,var(--indigo)); }
.rp-kc-label { font-size:.68rem; font-weight:700; color:var(--rp-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
.rp-kc-value { font-size:1.6rem; font-weight:800; color:var(--rp-text); line-height:1; }
.rp-kc-sub   { font-size:.72rem; color:var(--rp-muted); margin-top:4px; }

/* Charts */
.rp-cols { display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:20px; }
@media(max-width:900px){.rp-cols{grid-template-columns:1fr;}}
.rp-chart-card { background:var(--rp-card); border-radius:14px; border:1px solid var(--rp-border); box-shadow:var(--rp-shadow); overflow:hidden; }
.rp-chart-head { padding:14px 20px; border-bottom:1px solid var(--rp-border); display:flex; align-items:center; justify-content:space-between; }
.rp-chart-title { font-size:.88rem; font-weight:700; color:var(--rp-text); }
.rp-chart-body  { padding:16px; }

/* Table */
.rp-table-card { background:var(--rp-card); border-radius:14px; border:1px solid var(--rp-border); box-shadow:var(--rp-shadow); overflow:hidden; }
.rp-table-head { padding:14px 20px; border-bottom:1px solid var(--rp-border); display:flex; align-items:center; justify-content:space-between; }
.rp-table { width:100%; border-collapse:collapse; }
.rp-table th { background:#F8FAFC; color:var(--rp-muted); font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; padding:10px 14px; text-align:left; }
.rp-table td { padding:10px 14px; border-bottom:1px solid #F1F5F9; font-size:.8rem; color:var(--rp-text); }
.rp-table tr:last-child td { border-bottom:none; }
.rp-table tr:hover td { background:#FAFBFF; }
.sb { display:inline-flex; align-items:center; font-size:.63rem; font-weight:700; padding:2px 8px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em; }
.sb-confirmed  { background:#DBEAFE;color:#1E40AF; }
.sb-completed  { background:#D1FAE5;color:#065F46; }
.sb-cancelled  { background:#FEE2E2;color:#991B1B; }
.sb-in_progress{ background:#FEF3C7;color:#92400E; }
.sb-Stay  { background:#EDE9FE;color:#6D28D9; }
.sb-Cab   { background:#FEF3C7;color:#B45309; }
.sb-Boat  { background:#E0F2FE;color:#0369A1; }
</style>

<div class="rp-page">

  {{-- Header --}}
  <div class="rp-header">
    <div>
      <h1>📊 Reports & Analytics</h1>
      <p>Revenue · Bookings · Payments across all service types</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" style="color:rgba(255,255,255,.6);font-size:.8rem;text-decoration:none;">← Dashboard</a>
  </div>

  {{-- Filter bar --}}
  <form method="GET" action="{{ route('reports.index') }}">
  <div class="filter-bar">
    <div>
      <label>From Date</label>
      <input type="date" name="from" value="{{ $from->format('Y-m-d') }}">
    </div>
    <div>
      <label>To Date</label>
      <input type="date" name="to" value="{{ $to->format('Y-m-d') }}">
    </div>
    <div>
      <label>Type</label>
      <select name="type">
        <option value="all" {{ $type=='all'?'selected':'' }}>All Types</option>
        <option value="stay" {{ $type=='stay'?'selected':'' }}>Stay</option>
        <option value="cab"  {{ $type=='cab'?'selected':'' }}>Cab</option>
        <option value="boat" {{ $type=='boat'?'selected':'' }}>Boat</option>
      </select>
    </div>
    <div style="margin-top:18px;">
      <button type="submit" class="filter-btn">Generate Report</button>
    </div>
    <div style="margin-top:18px;margin-left:auto;">
      <span style="font-size:.75rem;color:var(--rp-muted);">{{ $from->format('d M Y') }} — {{ $to->format('d M Y') }}</span>
    </div>
  </div>
  </form>

  {{-- KPI cards --}}
  <div class="rp-kpi">
    <div class="rp-kpi-card" style="--kc:var(--indigo);">
      <div class="rp-kc-label">Total Revenue</div>
      <div class="rp-kc-value">₹{{ number_format($totalRevenue) }}</div>
      <div class="rp-kc-sub">{{ $totalCount }} bookings in period</div>
    </div>
    <div class="rp-kpi-card" style="--kc:var(--emerald);">
      <div class="rp-kc-label">Amount Collected</div>
      <div class="rp-kc-value">₹{{ number_format($totalPaid) }}</div>
      <div class="rp-kc-sub">Stay + Cab payments received</div>
    </div>
    <div class="rp-kpi-card" style="--kc:var(--amber);">
      <div class="rp-kc-label">Balance Due</div>
      <div class="rp-kc-value">₹{{ number_format($totalPending) }}</div>
      <div class="rp-kc-sub">Outstanding across all bookings</div>
    </div>
    <div class="rp-kpi-card" style="--kc:var(--sky);">
      <div class="rp-kc-label">Total Bookings</div>
      <div class="rp-kc-value">{{ $totalCount }}</div>
      <div class="rp-kc-sub">Stay {{ $stayCount }} · Cab {{ $cabCount }} · Boat {{ $boatCount }}</div>
    </div>
  </div>

  {{-- Charts + Type breakdown --}}
  <div class="rp-cols">
    {{-- Daily revenue chart --}}
    <div class="rp-chart-card">
      <div class="rp-chart-head">
        <div class="rp-chart-title">Daily Revenue Trend</div>
        <span style="font-size:.7rem;color:var(--rp-muted);">{{ $from->format('d M') }} – {{ $to->format('d M Y') }}</span>
      </div>
      <div class="rp-chart-body">
        @if(count($days))
          <canvas id="dailyChart" height="100"></canvas>
        @else
          <div style="text-align:center;padding:40px;color:var(--rp-muted);">
            <div style="font-size:2rem;margin-bottom:8px;">📈</div>
            Chart available for ranges up to 60 days
          </div>
        @endif
      </div>
    </div>

    {{-- Type breakdown --}}
    <div class="rp-chart-card">
      <div class="rp-chart-head">
        <div class="rp-chart-title">Revenue by Type</div>
      </div>
      <div class="rp-chart-body">
        <canvas id="typeChart" height="160"></canvas>
        <div style="margin-top:16px;">
          @php $typeRevs = ['Stay' => $stayRevenue, 'Cab' => $cabRevenue, 'Boat' => $boatRevenue]; $trtotal = max(1, $totalRevenue); @endphp
          @foreach($typeRevs as $lbl => $rev)
          <div style="margin-bottom:10px;">
            <div style="display:flex;justify-content:space-between;font-size:.75rem;margin-bottom:3px;">
              <span style="font-weight:600;">{{ $lbl }}</span>
              <span style="color:var(--rp-muted);">₹{{ number_format($rev) }} ({{ round($rev/$trtotal*100) }}%)</span>
            </div>
            <div style="height:5px;background:#E2E8F0;border-radius:99px;overflow:hidden;">
              <div style="height:100%;width:{{ round($rev/$trtotal*100) }}%;background:{{ ['Stay'=>'#4F46E5','Cab'=>'#F59E0B','Boat'=>'#0EA5E9'][$lbl] }};border-radius:99px;"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- Payment methods --}}
  @if($payMethods->count())
  <div class="rp-chart-card" style="margin-bottom:20px;">
    <div class="rp-chart-head">
      <div class="rp-chart-title">💳 Payment Methods Breakdown</div>
    </div>
    <div class="rp-chart-body">
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">
        @foreach($payMethods as $pm)
        <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:12px;text-align:center;">
          <div style="font-size:.7rem;font-weight:700;color:var(--rp-muted);text-transform:uppercase;margin-bottom:4px;">{{ ucwords(str_replace('_',' ',$pm->payment_method ?? 'Cash')) }}</div>
          <div style="font-size:1.2rem;font-weight:800;color:#0F172A;">₹{{ number_format($pm->total) }}</div>
          <div style="font-size:.68rem;color:var(--rp-muted);">{{ $pm->cnt }} transactions</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  {{-- Bookings table --}}
  <div class="rp-table-card">
    <div class="rp-table-head">
      <div class="rp-chart-title">Booking Details — {{ $from->format('d M Y') }} to {{ $to->format('d M Y') }}</div>
      <span style="font-size:.72rem;color:var(--rp-muted);">{{ $allBookings->count() }} records shown</span>
    </div>
    <div style="overflow-x:auto;">
      <table class="rp-table">
        <thead>
          <tr>
            <th>Booking #</th>
            <th>Type</th>
            <th>Guest</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($allBookings as $b)
          <tr>
            <td><a href="{{ $b['url'] }}" style="color:var(--indigo);font-weight:600;text-decoration:none;">{{ $b['number'] }}</a></td>
            <td><span class="sb sb-{{ $b['type'] }}">{{ $b['type'] }}</span></td>
            <td style="font-weight:500;">{{ $b['guest'] }}</td>
            <td style="color:var(--rp-muted);">{{ $b['phone'] }}</td>
            <td>{{ $b['date'] }}</td>
            <td style="font-weight:700;">₹{{ number_format($b['amount']) }}</td>
            <td style="color:#059669;font-weight:600;">₹{{ number_format($b['paid']) }}</td>
            <td style="color:{{ $b['pending'] > 0 ? '#d97706' : '#94a3b8' }};font-weight:600;">₹{{ number_format($b['pending']) }}</td>
            <td><span class="sb sb-{{ $b['status'] }}">{{ ucfirst(str_replace('_',' ',$b['status'])) }}</span></td>
          </tr>
          @empty
          <tr><td colspan="9" style="text-align:center;padding:30px;color:var(--rp-muted);">No bookings found for this period</td></tr>
          @endforelse
        </tbody>
        @if($allBookings->count())
        <tfoot>
          <tr style="background:#F8FAFC;font-weight:700;">
            <td colspan="5" style="text-align:right;font-size:.8rem;color:var(--rp-muted);">TOTALS</td>
            <td>₹{{ number_format($allBookings->sum('amount')) }}</td>
            <td style="color:#059669;">₹{{ number_format($allBookings->sum('paid')) }}</td>
            <td style="color:#d97706;">₹{{ number_format($allBookings->sum('pending')) }}</td>
            <td></td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

</div>

<script>
@if(count($days))
new Chart(document.getElementById('dailyChart'), {
  type: 'line',
  data: {
    labels: {!! json_encode($days) !!},
    datasets: [{
      label: 'Revenue',
      data: {!! json_encode($dayRevenue) !!},
      borderColor: '#4F46E5',
      backgroundColor: 'rgba(79,70,229,.07)',
      borderWidth: 2.5,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#4F46E5',
      pointRadius: 3,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend:{ display:false } },
    scales: {
      x: { grid:{ display:false }, ticks:{ font:{size:10}, maxTicksLimit:12 } },
      y: {
        grid:{ color:'rgba(0,0,0,.04)' },
        ticks:{ font:{size:10}, callback: v => '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'K' : v) }
      }
    }
  }
});
@endif

new Chart(document.getElementById('typeChart'), {
  type: 'doughnut',
  data: {
    labels: ['Stay', 'Cab', 'Boat'],
    datasets: [{
      data: [{{ $stayRevenue }}, {{ $cabRevenue }}, {{ $boatRevenue }}],
      backgroundColor: ['#4F46E5','#F59E0B','#0EA5E9'],
      borderWidth: 0,
      hoverOffset: 6,
    }]
  },
  options: {
    responsive: true,
    cutout: '65%',
    plugins: {
      legend:{ position:'bottom', labels:{ font:{size:11}, padding:12 } },
      tooltip:{ callbacks:{ label: ctx => ' ₹' + ctx.parsed.toLocaleString('en-IN') }}
    }
  }
});
</script>
@endsection
