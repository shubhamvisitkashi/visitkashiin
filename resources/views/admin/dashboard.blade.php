@extends('admin.layouts.app')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
/* ═══════════════════════════════════════════════
   VISITKASHI CRM — Premium Dashboard v2
   Light + Dark Mode · Mobile-first
═══════════════════════════════════════════════ */
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
  --rose:   #EF4444;
  --sky:    #0EA5E9;
  --teal:   #14B8A6;
  --r:      16px;
  --t:      .2s ease;
}

/* ── DARK MODE ─────────────────────────────── */
body.dark-mode {
  --bg:     #0F172A;
  --card:   #1E293B;
  --border: #334155;
  --shadow: 0 1px 3px rgba(0,0,0,.3), 0 4px 16px rgba(0,0,0,.25);
  --text:   #F1F5F9;
  --sub:    #CBD5E1;
  --muted:  #64748B;
}
body.dark-mode .dk-page           { background: #0F172A !important; }
body.dark-mode .kpi-card,
body.dark-mode .chart-card,
body.dark-mode .table-card,
body.dark-mode .type-card         { background: #1E293B !important; border-color:#334155 !important; }
body.dark-mode .chart-head,
body.dark-mode .table-head        { border-color:#334155 !important; background:#1E293B !important; }
body.dark-mode .dk-table th       { background:#0F172A !important; color:#64748B !important; }
body.dark-mode .dk-table td       { color:#F1F5F9 !important; border-color:#334155 !important; }
body.dark-mode .dk-table tr:hover td { background:#263348 !important; }
body.dark-mode .upcoming-item     { border-color:#334155 !important; }
body.dark-mode .chart-title,
body.dark-mode .table-title       { color:#F1F5F9 !important; }
body.dark-mode .chart-sub         { color:#64748B !important; }
body.dark-mode .kpi-label,
body.dark-mode .kpi-sub           { color:#64748B !important; }
body.dark-mode .kpi-value         { color:#F1F5F9 !important; }
body.dark-mode .type-card-label   { color:#64748B !important; }
body.dark-mode .type-card-count   { color:#F1F5F9 !important; }
body.dark-mode .type-card-rev     { color:#94A3B8 !important; }
body.dark-mode .upcoming-name     { color:#E2E8F0 !important; }
body.dark-mode .upcoming-sub      { color:#64748B !important; }
body.dark-mode .upcoming-amt      { color:#F1F5F9 !important; }
body.dark-mode .ud-day            { color:#818CF8 !important; }
body.dark-mode .prog-bar          { background:#334155 !important; }
body.dark-mode .legend-dot        { color:#94A3B8 !important; }
body.dark-mode .af-title          { color:#E2E8F0 !important; }
body.dark-mode .af-meta           { color:#64748B !important; }
body.dark-mode .kpi-icon          { opacity:.85; }

/* ── Page ─────────────────────────────────── */
.dk-page { background:var(--bg); min-height:100vh; padding:18px 22px 60px; transition:background .3s; }

/* ── Hero ─────────────────────────────────── */
.dk-hero {
  background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 45%, #0EA5E9 100%);
  border-radius:22px; padding:26px 30px; margin-bottom:22px; margin-top:52px;
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px;
  position:relative; overflow:hidden; box-shadow:0 12px 40px rgba(79,70,229,.3);
}
.dk-hero::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M0 0h30v30H0V0zm30 30h30v30H30V30z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  pointer-events:none;
}
.dk-hero::after {
  content:''; position:absolute; top:-40px; right:-40px;
  width:200px; height:200px; border-radius:50%;
  background:rgba(255,255,255,.06); pointer-events:none;
}
.dk-hero-left { position:relative; z-index:1; }
.dk-hero h1   { color:#fff; font-size:1.55rem; font-weight:900; margin:0; letter-spacing:-.02em; }
.dk-hero p    { color:rgba(255,255,255,.75); font-size:.84rem; margin:.4rem 0 0; }
.dk-hero-actions { position:relative; z-index:1; display:flex; gap:8px; flex-wrap:wrap; }
.dk-hero-btn {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3);
  color:#fff; border-radius:11px; padding:9px 16px; font-size:.8rem; font-weight:700;
  text-decoration:none; transition:.18s; white-space:nowrap; backdrop-filter:blur(8px);
}
.dk-hero-btn:hover { background:rgba(255,255,255,.28); color:#fff; transform:translateY(-1px); }
.dk-hero-btn.primary { background:rgba(255,255,255,.95); color:#4F46E5; font-weight:800; }
.dk-hero-btn.primary:hover { background:#fff; box-shadow:0 4px 16px rgba(255,255,255,.3); }

/* ── KPI grid ─────────────────────────────── */
.kpi-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; margin-bottom:20px; }
@media(max-width:1400px){.kpi-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:900px) {.kpi-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px) {.kpi-grid{grid-template-columns:1fr;}}

.kpi-card {
  background:var(--card); border-radius:var(--r); border:1px solid var(--border);
  box-shadow:var(--shadow); padding:20px 22px; display:flex; align-items:flex-start;
  gap:15px; position:relative; overflow:hidden; transition:all var(--t);
}
.kpi-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(0,0,0,.12); }
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

/* ── Type stats ───────────────────────────── */
.type-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:22px; }
@media(max-width:768px){.type-row{grid-template-columns:1fr;}}

.type-card {
  background:var(--card); border-radius:var(--r); border:1px solid var(--border);
  box-shadow:var(--shadow); padding:18px 20px; display:flex; align-items:flex-start; gap:14px;
  transition:all var(--t);
}
.type-card:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(0,0,0,.10); }
.type-card-icon { width:46px; height:46px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.type-card-info { flex:1; }
.type-card-label { font-size:.7rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:3px; }
.type-card-count { font-size:1.6rem; font-weight:900; color:var(--text); line-height:1; }
.type-card-rev   { font-size:.76rem; color:var(--sub); font-weight:600; margin-top:4px; }
.type-stat-row   { display:flex; gap:8px; margin-top:8px; flex-wrap:wrap; }
.type-stat { font-size:.68rem; font-weight:600; padding:3px 9px; border-radius:20px; }

/* ── Two-column layout ────────────────────── */
/* ── Pie chart row (one line) ────────────── */
.dk-pie-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:20px; }
@media(max-width:1300px){.dk-pie-row{grid-template-columns:1fr 1fr;}}
@media(max-width:700px){.dk-pie-row{grid-template-columns:1fr;}}

/* ── Two-column chart row ─────────────────── */
.dk-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
@media(max-width:1100px){.dk-row-2{grid-template-columns:1fr;}}

/* ── Chart card ───────────────────────────── */
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
.chart-sub   { font-size:.72rem; color:var(--muted); margin-top:1px; }
.chart-body  { padding:18px 16px; }
.chart-legend { display:flex; gap:14px; flex-wrap:wrap; }
.legend-dot   { display:inline-flex; align-items:center; gap:5px; font-size:.72rem; font-weight:600; color:var(--sub); }
.legend-dot::before { content:''; width:10px; height:10px; border-radius:3px; background:var(--lc,#ccc); flex-shrink:0; }

/* ── Table card ───────────────────────────── */
.table-card {
  background:var(--card); border-radius:var(--r); border:1px solid var(--border);
  box-shadow:var(--shadow); overflow:hidden; margin-bottom:20px; transition:background .3s;
}
.table-head {
  padding:14px 20px; border-bottom:1px solid var(--border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--card);
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
.bk-link { color:var(--indigo); font-weight:700; text-decoration:none; font-size:.8rem; font-family:monospace; }
.bk-link:hover { text-decoration:underline; }

/* ── Badges ───────────────────────────────── */
.sb { display:inline-flex; align-items:center; gap:4px; font-size:.63rem; font-weight:700; padding:3px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em; white-space:nowrap; }
.sb-confirmed   { background:#DBEAFE; color:#1E40AF; }
.sb-completed   { background:#D1FAE5; color:#065F46; }
.sb-cancelled   { background:#FEE2E2; color:#991B1B; }
.sb-in_progress { background:#FEF3C7; color:#92400E; }
.sb-not_started { background:#F1F5F9; color:#475569; }
.sb-pending     { background:#FEF9C3; color:#854D0E; }
.sb-stay  { background:#EDE9FE; color:#6D28D9; }
.sb-cab   { background:#FEF3C7; color:#B45309; }
.sb-boat  { background:#E0F2FE; color:#0369A1; }

/* ── Upcoming ─────────────────────────────── */
.upcoming-item {
  display:flex; align-items:center; gap:12px; padding:11px 20px;
  border-bottom:1px solid var(--border); transition:.15s;
}
.upcoming-item:hover { background:rgba(79,70,229,.04); }
.upcoming-item:last-child { border-bottom:none; }
.upcoming-date { text-align:center; flex-shrink:0; width:44px; }
.ud-day { font-size:1.3rem; font-weight:900; color:var(--indigo); line-height:1; }
.ud-mon { font-size:.6rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; }
.upcoming-info { flex:1; min-width:0; }
.upcoming-name { font-size:.83rem; font-weight:700; color:var(--text); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.upcoming-sub  { font-size:.7rem; color:var(--muted); margin-top:2px; }
.upcoming-amt  { font-size:.83rem; font-weight:700; color:var(--text); flex-shrink:0; }

/* ── Progress bar ─────────────────────────── */
.prog-bar  { height:6px; background:#E2E8F0; border-radius:99px; overflow:hidden; margin-top:4px; }
.prog-fill { height:100%; border-radius:99px; transition:width .5s ease; }

/* ── Mobile ───────────────────────────────── */
@media(max-width:767px){
  .dk-page        { padding:10px 10px 70px; }
  .dk-hero        { padding:16px 16px; margin-top:58px; border-radius:16px; }
  .dk-hero h1     { font-size:1.15rem; }
  .dk-hero p      { font-size:.78rem; }
  .dk-hero-actions{ gap:6px; }
  .dk-hero-btn    { padding:7px 12px; font-size:.74rem; }
  .kpi-value      { font-size:1.4rem; }
  .kpi-icon       { width:42px; height:42px; font-size:1.2rem; border-radius:11px; }
  .kpi-card       { padding:14px 14px; gap:12px; }
  .chart-body     { padding:10px 8px; }

  /* Recent Bookings — hide less critical columns on mobile */
  .mob-hide       { display:none !important; }
  .dk-table th, .dk-table td { padding:9px 10px; font-size:.78rem; }
  .bk-link        { font-size:.76rem; }

  /* Type cards — remove side-by-side stats, stack them */
  .type-stat-row  { flex-wrap:wrap; gap:6px; }
  .type-card      { padding:14px 14px; }

  /* Booking mix — stack vertically on very small screens */
  .mix-wrap       { flex-direction:column; align-items:center; gap:12px; }
  .mix-donut      { width:100px; }

  /* Upcoming items */
  .upcoming-item  { padding:10px 14px; gap:10px; }
  .upcoming-amt   { font-size:.76rem; }

  /* Chart — reduce canvas height */
  #revenueChart   { max-height:180px; }
  #dailyChart     { max-height:150px; }
}

@media(max-width:400px){
  .dk-hero h1     { font-size:1rem; }
  .kpi-value      { font-size:1.2rem; }
}
@media(max-width:768px){
  .upcoming-row-grid { grid-template-columns:1fr !important; }
}
</style>

<div class="dk-page">

{{-- ══ HERO ══ --}}
<div class="dk-hero">
  <div class="dk-hero-left">
    <h1>🏛️ VisitKashi CRM</h1>
    <p>Welcome back, {{ auth('admin')->user()->name }} &nbsp;·&nbsp; {{ now()->format('l, d F Y') }}</p>
  </div>
  <div class="dk-hero-actions">
    <a href="{{ route('bookings.index') }}" class="dk-hero-btn primary">📋 All Bookings</a>
    <a href="{{ route('cab-bookings.create') }}" class="dk-hero-btn">🚗 Cab</a>
    <a href="{{ route('bookings.calendar') }}" class="dk-hero-btn">📅 Calendar</a>
  </div>
</div>

{{-- ══ REVENUE / EXPENSE PIES ══ --}}
<div class="dk-pie-row">
  {{-- Booking Mix --}}
  <div class="chart-card">
    <div class="chart-head">
      <div class="chart-title">🥧 Booking Mix</div>
      <span style="font-size:.7rem;color:var(--muted);">This Month</span>
    </div>
    <div class="chart-body mix-wrap" style="display:flex;align-items:center;gap:18px;">
      <div class="mix-donut" style="width:120px;flex-shrink:0;">
        <canvas id="donutChart"></canvas>
      </div>
      <div style="flex:1;min-width:0;">
        @php $typeTotal = array_sum($typeData); @endphp
        @foreach($typeLabels as $i => $lbl)
        @php
          $pct    = $typeTotal > 0 ? round($typeData[$i] / $typeTotal * 100) : 0;
          $colors = ['#4F46E5','#F59E0B','#0EA5E9'];
          $bgs    = ['#EEF2FF','#FEF3C7','#E0F2FE'];
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
      <span style="font-size:.7rem;color:var(--muted);">This Month</span>
    </div>
    <div class="chart-body mix-wrap" style="display:flex;align-items:center;gap:18px;">
      <div class="mix-donut" style="width:120px;flex-shrink:0;position:relative;">
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
      <span style="font-size:.7rem;color:var(--muted);">This Month</span>
    </div>
    <div class="chart-body mix-wrap" style="display:flex;align-items:center;gap:18px;">
      <div class="mix-donut" style="width:120px;flex-shrink:0;position:relative;">
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
  <div class="kpi-card" style="--kpi-color:var(--indigo);--kpi-bg:#EEF2FF;">
    <div class="kpi-icon">📋</div>
    <div class="kpi-body">
      <div class="kpi-label">Today's Bookings</div>
      <div class="kpi-value">{{ $totalBookingsToday }}</div>
      <div class="kpi-sub">
        🏨 {{ $stayToday }} Stay &nbsp;·&nbsp; 🚗 {{ $cabToday }} Cab &nbsp;·&nbsp; ⛵ {{ $boatToday }} Boat
      </div>
    </div>
  </div>

  <div class="kpi-card" style="--kpi-color:var(--emerald);--kpi-bg:#D1FAE5;">
    <div class="kpi-icon">💰</div>
    <div class="kpi-body">
      <div class="kpi-label">This Month Revenue</div>
      <div class="kpi-value">₹{{ number_format($totalRevenueMonth) }}</div>
      <div class="kpi-sub">
        @if($revenueGrowth > 0)
          <span class="kpi-badge up">↑ {{ abs($revenueGrowth) }}% vs last month</span>
        @elseif($revenueGrowth < 0)
          <span class="kpi-badge down">↓ {{ abs($revenueGrowth) }}% vs last month</span>
        @else
          <span class="kpi-badge neu">Same as last month</span>
        @endif
      </div>
    </div>
  </div>

  <div class="kpi-card" style="--kpi-color:var(--amber);--kpi-bg:#FEF3C7;cursor:pointer;" onclick="openPendingModal()" title="Click to view pending bookings">
    <div class="kpi-icon">⚠️</div>
    <div class="kpi-body">
      <div class="kpi-label">Pending Amount</div>
      <div class="kpi-value">₹{{ number_format($totalPending) }}</div>
      <div class="kpi-sub">{{ $pendingPaymentsCount }} bookings have due balance <span style="color:var(--amber);font-weight:700;margin-left:4px;">→</span></div>
    </div>
  </div>

  <div class="kpi-card" style="--kpi-color:var(--sky);--kpi-bg:#E0F2FE;">
    <div class="kpi-icon">📅</div>
    <div class="kpi-body">
      <div class="kpi-label">This Month Bookings</div>
      <div class="kpi-value">{{ $totalBookingsMonth }}</div>
      <div class="kpi-sub">All-time: <strong>{{ number_format($allTimeBookings) }}</strong> · ₹{{ number_format($allTimeRevenue) }}</div>
    </div>
  </div>

  {{-- 5th card: Team Monthly Target --}}
  @if($teamTotalTarget > 0)
  @php
    $tkColor = $teamTargetPct >= 100 ? '#10B981' : ($teamTargetPct >= 60 ? '#4F46E5' : ($teamTargetPct >= 30 ? '#F59E0B' : '#EF4444'));
    $tkBg    = $teamTargetPct >= 100 ? '#D1FAE5' : ($teamTargetPct >= 60 ? '#EEF2FF' : ($teamTargetPct >= 30 ? '#FEF3C7' : '#FEE2E2'));
  @endphp
  <div class="kpi-card" style="--kpi-color:{{ $tkColor }};--kpi-bg:{{ $tkBg }};">
    <div class="kpi-icon">🎯</div>
    <div class="kpi-body">
      <div class="kpi-label">Team Target — {{ now()->format('M Y') }}</div>
      <div class="kpi-value" style="color:{{ $tkColor }};font-size:1.5rem;">{{ $teamTargetPct }}%</div>
      <div style="height:5px;background:#E2E8F0;border-radius:99px;overflow:hidden;margin:6px 0 4px;">
        <div style="height:100%;width:{{ $teamTargetPct }}%;background:{{ $tkColor }};border-radius:99px;transition:width .5s;"></div>
      </div>
      <div class="kpi-sub" style="flex-direction:column;gap:1px;">
        <span>Achieved: <strong style="color:{{ $tkColor }};">₹{{ number_format($teamTotalAchieved) }}</strong></span>
        <span>Target: <strong>₹{{ number_format($teamTotalTarget) }}</strong></span>
      </div>
    </div>
  </div>
  @endif
</div>

{{-- ══ UPCOMING BOOKINGS ══ --}}
<div class="upcoming-row-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

  {{-- Upcoming Check-ins --}}
  <div class="chart-card" style="margin-bottom:0;">
    <div class="chart-head">
      <div class="chart-title">🏨 Upcoming Check-ins</div>
      <span style="font-size:.7rem;color:var(--muted);">Next 7 days</span>
    </div>
    @if($upcomingCheckins->count())
      @foreach($upcomingCheckins as $b)
      @php $checkin = $b->lead?->booking_start_date; @endphp
      <div class="upcoming-item">
        <div class="upcoming-date">
          <div class="ud-day">{{ $checkin ? \Carbon\Carbon::parse($checkin)->format('d') : '—' }}</div>
          <div class="ud-mon">{{ $checkin ? \Carbon\Carbon::parse($checkin)->format('M') : '' }}</div>
        </div>
        <div class="upcoming-info">
          <div class="upcoming-name">{{ $b->lead?->guest_name ?? '—' }}</div>
          <div class="upcoming-sub">{{ $b->booking_number }} · {{ $b->lead?->pax ?? 1 }} pax</div>
        </div>
        <div class="upcoming-amt">₹{{ number_format($b->total_amount) }}</div>
      </div>
      @endforeach
    @else
      <div style="padding:26px;text-align:center;color:var(--muted);font-size:.82rem;">No upcoming check-ins in next 7 days</div>
    @endif
  </div>

  {{-- Today's Bookings --}}
  <div class="chart-card" style="margin-bottom:0;">
    <div class="chart-head">
      <div class="chart-title">📋 Today's Bookings</div>
      <div style="display:flex;align-items:center;gap:8px;">
        @php
          $tStay = $todayAllBookings->where('type','Stay')->count();
          $tPkg  = $todayAllBookings->where('type','Package')->count();
          $tCab  = $todayAllBookings->where('type','Cab')->count();
          $tBoat = $todayAllBookings->where('type','Boat')->count();
        @endphp
        @if($tStay)  <span style="font-size:.63rem;font-weight:700;padding:2px 7px;border-radius:20px;background:#EEF2FF;color:#3730A3;">🏨 {{ $tStay }}</span> @endif
        @if($tPkg)   <span style="font-size:.63rem;font-weight:700;padding:2px 7px;border-radius:20px;background:#EDE9FE;color:#5B21B6;">📦 {{ $tPkg }}</span> @endif
        @if($tCab)   <span style="font-size:.63rem;font-weight:700;padding:2px 7px;border-radius:20px;background:#FEF3C7;color:#92400E;">🚗 {{ $tCab }}</span> @endif
        @if($tBoat)  <span style="font-size:.63rem;font-weight:700;padding:2px 7px;border-radius:20px;background:#E0F2FE;color:#0C4A6E;">⛵ {{ $tBoat }}</span> @endif
      </div>
    </div>
    @forelse($todayAllBookings as $tb)
    <div class="upcoming-item" onclick="openTodayBookingModal({{ json_encode($tb) }})"
         style="cursor:pointer;transition:background .15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background=''">
      <div style="width:36px;height:36px;border-radius:10px;background:{{ $tb['bg'] }};display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
        {{ $tb['icon'] }}
      </div>
      <div class="upcoming-info">
        <div class="upcoming-name">{{ $tb['guest'] }}</div>
        <div class="upcoming-sub">
          <span style="color:var(--indigo);font-weight:700;font-family:monospace;font-size:.7rem;">{{ $tb['number'] }}</span>
          &nbsp;·&nbsp;
          <span style="font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:20px;background:{{ $tb['bg'] }};color:{{ $tb['color'] }};">{{ $tb['type'] }}</span>
          &nbsp;·&nbsp;
          <span class="sb sb-{{ $tb['status'] }}" style="font-size:.62rem;">{{ ucwords(str_replace('_',' ',$tb['status'])) }}</span>
        </div>
      </div>
      <div class="upcoming-amt">₹{{ number_format($tb['amount']) }}</div>
    </div>
    @empty
    <div style="padding:28px;text-align:center;color:var(--muted);font-size:.82rem;">No bookings today</div>
    @endforelse
  </div>

  {{-- Today's Booking Detail Modal --}}
  <div id="todayBkModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:1100;align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,.18);overflow:hidden;">
      {{-- Header --}}
      <div id="tdBkHead" style="padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div id="tdBkIcon" style="width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;"></div>
          <div>
            <div id="tdBkNumber" style="font-size:.72rem;font-weight:800;color:#6366F1;font-family:monospace;"></div>
            <div id="tdBkType" style="font-size:.65rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;"></div>
          </div>
        </div>
        <button onclick="closeTodayBkModal()" style="background:#F1F5F9;border:none;border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:1rem;color:#64748B;display:flex;align-items:center;justify-content:center;">✕</button>
      </div>
      {{-- Body --}}
      <div style="padding:0 20px 20px;">
        {{-- Guest row --}}
        <div style="background:#F8FAFC;border-radius:10px;padding:12px 14px;margin-bottom:12px;">
          <div style="font-size:.62rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Guest</div>
          <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px;">
            <div id="tdBkGuest" style="font-size:.95rem;font-weight:800;color:#0F172A;"></div>
            <a id="tdBkPhone" href="#" style="font-size:.78rem;font-weight:700;color:#4F46E5;text-decoration:none;background:#EEF2FF;padding:3px 10px;border-radius:20px;"></a>
          </div>
        </div>
        {{-- Dates row --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
          <div style="background:#F0FDF4;border-radius:10px;padding:10px 12px;">
            <div style="font-size:.6rem;font-weight:700;color:#16A34A;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">From</div>
            <div id="tdBkFrom" style="font-size:.85rem;font-weight:700;color:#0F172A;"></div>
          </div>
          <div style="background:#FEF2F2;border-radius:10px;padding:10px 12px;">
            <div style="font-size:.6rem;font-weight:700;color:#DC2626;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">To</div>
            <div id="tdBkTo" style="font-size:.85rem;font-weight:700;color:#0F172A;"></div>
          </div>
        </div>
        {{-- Payment row --}}
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:12px;">
          <div style="text-align:center;background:#F8FAFC;border-radius:10px;padding:10px 8px;">
            <div style="font-size:.58rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">Total</div>
            <div id="tdBkTotal" style="font-size:.88rem;font-weight:800;color:#0F172A;"></div>
          </div>
          <div style="text-align:center;background:#F0FDF4;border-radius:10px;padding:10px 8px;">
            <div style="font-size:.58rem;font-weight:700;color:#16A34A;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">Paid</div>
            <div id="tdBkPaid" style="font-size:.88rem;font-weight:800;color:#16A34A;"></div>
          </div>
          <div style="text-align:center;background:#FEF2F2;border-radius:10px;padding:10px 8px;">
            <div style="font-size:.58rem;font-weight:700;color:#DC2626;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">Due</div>
            <div id="tdBkDue" style="font-size:.88rem;font-weight:800;color:#DC2626;"></div>
          </div>
        </div>
        {{-- Status + Added By --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
          <div><span style="font-size:.62rem;color:#94A3B8;font-weight:600;">Status: </span><span id="tdBkStatus" style="font-weight:700;font-size:.78rem;"></span></div>
          <div><span style="font-size:.62rem;color:#94A3B8;font-weight:600;">Added by: </span><span id="tdBkAdded" style="font-weight:700;font-size:.78rem;color:#4F46E5;"></span></div>
        </div>
        {{-- CTA --}}
        <a id="tdBkUrl" href="#" target="_blank"
           style="display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border-radius:10px;padding:11px;font-size:.85rem;font-weight:700;text-decoration:none;">
          View Full Booking Details →
        </a>
      </div>
    </div>
  </div>

</div>

{{-- ══ STAFF TARGETS ══ --}}
@if($isAdmin)

  {{-- Admin: team grid --}}
  @if($staffTargets->count())
  <div class="chart-card" style="margin-bottom:20px;">
    <div class="chart-head">
      <div>
        <div class="chart-title">🎯 Staff Targets — {{ now()->format('F Y') }}</div>
        <div class="chart-sub">Monthly margin targets vs achieved</div>
      </div>
      @can('target-manage')
      <a href="{{ route('targets.index') }}" style="font-size:.72rem;color:var(--indigo);font-weight:700;text-decoration:none;">Manage →</a>
      @endcan
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:0;padding:0;">
      @foreach($staffTargets as $rank => $t)
      @php
        $pct      = $t->target_margin > 0 ? min(100, round($t->achieved_margin / $t->target_margin * 100)) : 0;
        $remain   = max(0, $t->target_margin - $t->achieved_margin);
        $color    = $pct >= 100 ? '#10B981' : ($pct >= 60 ? '#4F46E5' : ($pct >= 30 ? '#F59E0B' : '#EF4444'));
        $bgColor  = $pct >= 100 ? '#D1FAE5' : ($pct >= 60 ? '#EEF2FF' : ($pct >= 30 ? '#FEF3C7' : '#FEE2E2'));
        $txtColor = $pct >= 100 ? '#065F46' : ($pct >= 60 ? '#3730A3' : ($pct >= 30 ? '#92400E' : '#991B1B'));
        $rankNum  = $rank + 1;
        $rankLabel = $rankNum === 1 ? '🥇' : ($rankNum === 2 ? '🥈' : ($rankNum === 3 ? '🥉' : '#'.$rankNum));
      @endphp
      <div style="padding:16px 20px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
          <div style="display:flex;align-items:center;gap:8px;cursor:pointer;" onclick="openStaffModal({{ $t->user_id }}, '{{ addslashes($t->user->name ?? 'Staff') }}')" title="View {{ $t->user->name ?? 'Staff' }}'s bookings this month">
            <div style="position:relative;flex-shrink:0;">
              <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800;">
                {{ strtoupper(substr($t->user->name ?? 'S', 0, 1)) }}
              </div>
              <span style="position:absolute;top:-6px;left:-6px;font-size:.7rem;line-height:1;">{{ $rankLabel }}</span>
            </div>
            <div style="font-size:.82rem;font-weight:700;color:var(--indigo);text-decoration:underline;text-underline-offset:2px;">{{ $t->user->name ?? 'Staff' }}</div>
          </div>
          <span style="font-size:.64rem;font-weight:800;padding:3px 9px;border-radius:20px;background:{{ $bgColor }};color:{{ $txtColor }};">
            {{ $pct >= 100 ? '🏆 Done' : $pct.'%' }}
          </span>
        </div>
        <div style="height:7px;background:#E2E8F0;border-radius:99px;overflow:hidden;margin-bottom:8px;">
          <div style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:99px;transition:width .5s;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.72rem;">
          <span style="color:var(--muted);">Achieved: <strong style="color:{{ $color }};">₹{{ number_format($t->achieved_margin) }}</strong></span>
          <span style="color:var(--muted);">Target: <strong style="color:var(--text);">₹{{ number_format($t->target_margin) }}</strong></span>
        </div>
        @if($remain > 0)
        <div style="font-size:.68rem;color:var(--muted);margin-top:3px;text-align:right;">₹{{ number_format($remain) }} remaining</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
  @else
  @can('target-manage')
  <div class="chart-card" style="margin-bottom:20px;">
    <div class="chart-head"><div class="chart-title">🎯 Staff Targets — {{ now()->format('F Y') }}</div></div>
    <div style="padding:20px 24px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
      <div style="font-size:.84rem;color:var(--muted);">No targets set for this month yet.</div>
      <a href="{{ route('targets.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:var(--indigo);color:#fff;border-radius:10px;font-size:.8rem;font-weight:700;text-decoration:none;">＋ Set Staff Targets</a>
    </div>
  </div>
  @endcan
  @endif

@else

  {{-- Staff: personal target with full formula breakdown --}}
  @if($staffTargets->count() && $myTargetDetail)
  @php
    $mt      = $staffTargets->first();
    $pct     = $mt->target_margin > 0 ? min(100, round($mt->achieved_margin / $mt->target_margin * 100)) : 0;
    $remain  = max(0, $mt->target_margin - $mt->achieved_margin);
    $color   = $pct >= 100 ? '#10B981' : ($pct >= 60 ? '#4F46E5' : ($pct >= 30 ? '#F59E0B' : '#EF4444'));
    $bgColor = $pct >= 100 ? '#D1FAE5' : ($pct >= 60 ? '#EEF2FF' : ($pct >= 30 ? '#FEF3C7' : '#FEE2E2'));
    $txtColor= $pct >= 100 ? '#065F46' : ($pct >= 60 ? '#3730A3' : ($pct >= 30 ? '#92400E' : '#991B1B'));
  @endphp
  <div class="chart-card" style="margin-bottom:20px;">
    <div class="chart-head">
      <div>
        <div class="chart-title">🎯 My Monthly Target — {{ now()->format('F Y') }}</div>
        <div class="chart-sub">Total Booking Amount − Vendor Cost = Margin</div>
      </div>
      <span style="font-size:.72rem;font-weight:800;padding:4px 12px;border-radius:20px;background:{{ $bgColor }};color:{{ $txtColor }};">
        {{ $pct >= 100 ? '🏆 Target Achieved!' : $pct.'% of target' }}
      </span>
    </div>

    <div style="padding:20px 24px;">

      {{-- Progress bar --}}
      <div style="margin-bottom:18px;">
        <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--muted);margin-bottom:6px;">
          <span>Margin Achieved: <strong style="color:{{ $color }};font-size:.85rem;">₹{{ number_format($mt->achieved_margin) }}</strong></span>
          <span>Target: <strong style="color:var(--text);font-size:.85rem;">₹{{ number_format($mt->target_margin) }}</strong></span>
        </div>
        <div style="height:10px;background:#E2E8F0;border-radius:99px;overflow:hidden;">
          <div style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:99px;transition:width .6s;"></div>
        </div>
        @if($remain > 0)
        <div style="font-size:.7rem;color:var(--muted);margin-top:5px;text-align:right;">
          ₹{{ number_format($remain) }} more to hit target
        </div>
        @endif
      </div>

      {{-- Formula breakdown --}}
      <div style="background:#F8FAFC;border:1px solid var(--border);border-radius:12px;padding:16px 18px;margin-bottom:16px;">
        <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px;">This Month Breakdown</div>

        {{-- Header row --}}
        <div style="display:grid;grid-template-columns:1fr repeat(3,auto);gap:6px 16px;font-size:.68rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;padding-bottom:8px;border-bottom:1px solid var(--border);margin-bottom:8px;">
          <span>Type</span>
          <span style="text-align:right;">Booking Amt</span>
          <span style="text-align:right;">Vendor Cost</span>
          <span style="text-align:right;">Margin</span>
        </div>

        {{-- Stay row --}}
        <div style="display:grid;grid-template-columns:1fr repeat(3,auto);gap:4px 16px;font-size:.78rem;padding:5px 0;border-bottom:1px dashed #E2E8F0;">
          <span style="color:var(--text);font-weight:600;">🏨 Stay <span style="font-weight:400;color:var(--muted);">({{ $myTargetDetail['stay']['count'] }})</span></span>
          <span style="text-align:right;color:var(--text);">₹{{ number_format($myTargetDetail['stay']['amount']) }}</span>
          <span style="text-align:right;color:#EF4444;">₹{{ number_format($myTargetDetail['stay']['vendor']) }}</span>
          <span style="text-align:right;font-weight:700;color:{{ $myTargetDetail['stay']['margin'] >= 0 ? '#10B981' : '#EF4444' }};">₹{{ number_format($myTargetDetail['stay']['margin']) }}</span>
        </div>

        {{-- Package row --}}
        <div style="display:grid;grid-template-columns:1fr repeat(3,auto);gap:4px 16px;font-size:.78rem;padding:5px 0;border-bottom:1px dashed #E2E8F0;">
          <span style="color:var(--text);font-weight:600;">🗺️ Package <span style="font-weight:400;color:var(--muted);">({{ $myTargetDetail['package']['count'] }})</span></span>
          <span style="text-align:right;color:var(--text);">₹{{ number_format($myTargetDetail['package']['amount']) }}</span>
          <span style="text-align:right;color:#EF4444;">₹{{ number_format($myTargetDetail['package']['vendor']) }}</span>
          <span style="text-align:right;font-weight:700;color:{{ $myTargetDetail['package']['margin'] >= 0 ? '#10B981' : '#EF4444' }};">₹{{ number_format($myTargetDetail['package']['margin']) }}</span>
        </div>

        {{-- Cab row --}}
        <div style="display:grid;grid-template-columns:1fr repeat(3,auto);gap:4px 16px;font-size:.78rem;padding:5px 0;border-bottom:1px dashed #E2E8F0;">
          <span style="color:var(--text);font-weight:600;">🚗 Cab <span style="font-weight:400;color:var(--muted);">({{ $myTargetDetail['cab']['count'] }})</span></span>
          <span style="text-align:right;color:var(--text);">₹{{ number_format($myTargetDetail['cab']['amount']) }}</span>
          <span style="text-align:right;color:#EF4444;">₹{{ number_format($myTargetDetail['cab']['vendor']) }}</span>
          <span style="text-align:right;font-weight:700;color:{{ $myTargetDetail['cab']['margin'] >= 0 ? '#10B981' : '#EF4444' }};">₹{{ number_format($myTargetDetail['cab']['margin']) }}</span>
        </div>

        {{-- Boat row --}}
        <div style="display:grid;grid-template-columns:1fr repeat(3,auto);gap:4px 16px;font-size:.78rem;padding:5px 0;border-bottom:1px dashed #E2E8F0;">
          <span style="color:var(--text);font-weight:600;">⛵ Boat <span style="font-weight:400;color:var(--muted);">({{ $myTargetDetail['boat']['count'] }})</span></span>
          <span style="text-align:right;color:var(--text);">₹{{ number_format($myTargetDetail['boat']['amount']) }}</span>
          <span style="text-align:right;color:#EF4444;">₹{{ number_format($myTargetDetail['boat']['vendor']) }}</span>
          <span style="text-align:right;font-weight:700;color:{{ $myTargetDetail['boat']['margin'] >= 0 ? '#10B981' : '#EF4444' }};">₹{{ number_format($myTargetDetail['boat']['margin']) }}</span>
        </div>

        {{-- Guide row --}}
        <div style="display:grid;grid-template-columns:1fr repeat(3,auto);gap:4px 16px;font-size:.78rem;padding:5px 0;border-bottom:1px solid var(--border);margin-bottom:8px;opacity:{{ $myTargetDetail['guide']['count'] > 0 ? '1' : '.45' }};">
          <span style="color:var(--text);font-weight:600;">🧭 Guide <span style="font-weight:400;color:var(--muted);">({{ $myTargetDetail['guide']['count'] }})</span></span>
          <span style="text-align:right;color:var(--text);">₹{{ number_format($myTargetDetail['guide']['amount']) }}</span>
          <span style="text-align:right;color:#EF4444;">₹{{ number_format($myTargetDetail['guide']['vendor']) }}</span>
          <span style="text-align:right;font-weight:700;color:#10B981;">₹{{ number_format($myTargetDetail['guide']['margin']) }}</span>
        </div>

        {{-- Totals row --}}
        <div style="display:grid;grid-template-columns:1fr repeat(3,auto);gap:4px 16px;font-size:.82rem;padding:4px 0;">
          <span style="font-weight:800;color:var(--text);">Total</span>
          <span style="text-align:right;font-weight:800;color:var(--text);">₹{{ number_format($myTargetDetail['total_amount']) }}</span>
          <span style="text-align:right;font-weight:800;color:#EF4444;">₹{{ number_format($myTargetDetail['vendor_cost']) }}</span>
          <span style="text-align:right;font-weight:900;color:{{ $color }};">₹{{ number_format($myTargetDetail['margin']) }}</span>
        </div>

        {{-- Formula label --}}
        <div style="margin-top:10px;padding:8px 12px;background:{{ $bgColor }};border-radius:8px;font-size:.72rem;font-weight:700;color:{{ $txtColor }};text-align:center;">
          ₹{{ number_format($myTargetDetail['total_amount']) }} − ₹{{ number_format($myTargetDetail['vendor_cost']) }} = ₹{{ number_format($myTargetDetail['margin']) }} Margin
        </div>
      </div>

    </div>
  </div>
  @endif

@endif

{{-- ══ TYPE BREAKDOWN ══ --}}
<div class="type-row">
  <div class="type-card">
    <div class="type-card-icon" style="background:#EDE9FE;">🏨</div>
    <div class="type-card-info">
      <div class="type-card-label">Stay / Hotel</div>
      <div class="type-card-count">{{ $stayMonth }} <span style="font-size:.8rem;font-weight:500;color:var(--muted)">this month</span></div>
      <div class="type-card-rev">₹{{ number_format($stayRevMonth) }} revenue</div>
      <div class="type-stat-row">
        <span class="type-stat" style="background:#DBEAFE;color:#1E40AF;">✓ {{ $stayConfirmed }} Confirmed</span>
        <span class="type-stat" style="background:#D1FAE5;color:#065F46;">✔ {{ $stayCompleted }} Done</span>
      </div>
    </div>
  </div>

  <div class="type-card">
    <div class="type-card-icon" style="background:#FEF3C7;">🚗</div>
    <div class="type-card-info">
      <div class="type-card-label">Cab / Transport</div>
      <div class="type-card-count">{{ $cabMonth }} <span style="font-size:.8rem;font-weight:500;color:var(--muted)">this month</span></div>
      <div class="type-card-rev">₹{{ number_format($cabRevMonth) }} revenue</div>
      <div class="type-stat-row">
        <a href="{{ route('cab-bookings.index') }}" class="type-stat" style="background:#FEF3C7;color:#B45309;text-decoration:none;">View All Cabs →</a>
      </div>
    </div>
  </div>

  <div class="type-card">
    <div class="type-card-icon" style="background:#E0F2FE;">⛵</div>
    <div class="type-card-info">
      <div class="type-card-label">Boat / Ghat</div>
      <div class="type-card-count">{{ $boatMonth }} <span style="font-size:.8rem;font-weight:500;color:var(--muted)">this month</span></div>
      <div class="type-card-rev">₹{{ number_format($boatRevMonth) }} revenue</div>
      <div class="type-stat-row">
        <span class="type-stat" style="background:#FEE2E2;color:#991B1B;">{{ $boatPending }} pending payment</span>
      </div>
    </div>
  </div>
</div>

{{-- ══ MAIN CONTENT ══ --}}
<div>

    {{-- Revenue Trend + 3-Month Trend (admin only) --}}
    @if($isAdmin)
    <div class="dk-row-2">
      <div class="chart-card">
        <div class="chart-head">
          <div>
            <div class="chart-title">📈 Revenue Trend — Last 3 Months</div>
            <div class="chart-sub">Stay · Cab · Boat breakdown</div>
          </div>
          <div class="chart-legend">
            <span class="legend-dot" style="--lc:#4F46E5;">Stay</span>
            <span class="legend-dot" style="--lc:#F59E0B;">Cab</span>
            <span class="legend-dot" style="--lc:#0EA5E9;">Boat</span>
          </div>
        </div>
        <div class="chart-body">
          <canvas id="revenueChart" height="95"></canvas>
        </div>
      </div>

      <div class="chart-card">
        <div class="chart-head">
          <div>
            <div class="chart-title">📊 3-Month Trend</div>
            <div class="chart-sub">Revenue · Expense · Net Profit</div>
          </div>
        </div>
        <div class="chart-body">
          <canvas id="dashTrendChart" height="95"></canvas>
        </div>
      </div>
    </div>
    @endif

    {{-- Recent Bookings --}}
    <div class="table-card">
      <div class="table-head">
        <div class="table-title">🕐 Recent Bookings</div>
        <a href="{{ route('bookings.index') }}" style="font-size:.75rem;color:var(--indigo);font-weight:700;text-decoration:none;">View All →</a>
      </div>
      <table class="dk-table" style="width:100%;">
        <thead>
          <tr>
            <th>Booking #</th>
            <th>Guest</th>
            <th class="mob-hide">Type</th>
            <th>Amount</th>
            <th class="mob-hide">Status</th>
            <th class="mob-hide">Added By</th>
            <th class="mob-hide">Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentBookings as $b)
          <tr>
            <td><a href="{{ $b['url'] }}" class="bk-link">{{ $b['number'] }}</a></td>
            <td style="font-weight:600;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b['guest'] }}</td>
            <td class="mob-hide"><span class="sb sb-{{ $b['type'] }}">{{ $b['icon'] }} {{ ucfirst($b['type']) }}</span></td>
            <td style="font-weight:700;color:var(--emerald);white-space:nowrap;">₹{{ number_format($b['amount']) }}</td>
            <td class="mob-hide"><span class="sb sb-{{ $b['status'] }}">{{ ucwords(str_replace('_',' ',$b['status'] ?? 'pending')) }}</span></td>
            <td class="mob-hide" style="color:var(--sub);font-size:.77rem;font-weight:600;max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b['added_by'] }}</td>
            <td class="mob-hide" style="color:var(--muted);font-size:.77rem;">{{ $b['date'] ? \Carbon\Carbon::parse($b['date'])->format('d M') : '—' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:28px;color:var(--muted);">
              No bookings — <a href="{{ route('bookings.create-direct') }}" style="color:var(--indigo);font-weight:600;">create one</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Daily Revenue --}}
    <div class="chart-card">
      <div class="chart-head">
        <div>
          <div class="chart-title">📊 Daily Revenue — {{ now()->format('F Y') }}</div>
          <div class="chart-sub">All booking types combined</div>
        </div>
      </div>
      <div class="chart-body">
        <canvas id="dailyChart" height="75"></canvas>
      </div>
    </div>

  </div>{{-- /left --}}
</div>{{-- /main content --}}

</div>{{-- /dk-page --}}

<script>
// ── Detect dark mode ─────────────────────────────────────
const isDark = () => document.body.classList.contains('dark-mode');
const chartTextColor   = () => isDark() ? '#94A3B8' : '#64748B';
const chartGridColor   = () => isDark() ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.04)';
const chartBgColor     = () => isDark() ? '#1E293B' : '#FFFFFF';

const isMob = window.innerWidth < 640;
const axisDefaults = () => ({
  grid:  { color: chartGridColor() },
  ticks: { font:{ size: isMob ? 9 : 11 }, color: chartTextColor(), maxTicksLimit: isMob ? 5 : 8 }
});

// ── Revenue trend (admin only) ───────────────────────────
if (document.getElementById('revenueChart')) new Chart(document.getElementById('revenueChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($monthLabels) !!},
    datasets: [
      { label:'Stay', data:{!! json_encode($stayRevTrend) !!}, backgroundColor:'rgba(79,70,229,.85)', borderRadius:7, borderSkipped:false },
      { label:'Cab',  data:{!! json_encode($cabRevTrend) !!},  backgroundColor:'rgba(245,158,11,.85)',borderRadius:7, borderSkipped:false },
      { label:'Boat', data:{!! json_encode($boatRevTrend) !!}, backgroundColor:'rgba(14,165,233,.85)', borderRadius:7, borderSkipped:false }
    ]
  },
  options: {
    responsive:true, maintainAspectRatio:true,
    plugins: { legend:{ display:false }, tooltip:{ callbacks:{ label: ctx => ' ₹' + ctx.parsed.y.toLocaleString('en-IN') }}},
    scales: {
      x: { ...axisDefaults(), grid:{ display:false } },
      y: { ...axisDefaults(), ticks:{ ...axisDefaults().ticks, callback: v => v>=1000 ? '₹'+(v/1000).toFixed(0)+'K' : '₹'+v }}
    }
  }
});

// ── Revenue / Expense / Profit trend ─────────────────────
if (document.getElementById('dashTrendChart')) new Chart(document.getElementById('dashTrendChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($monthLabels) !!},
    datasets: [
      { label:'Revenue', data:{!! json_encode($totalRevTrend) !!}, backgroundColor:'rgba(79,70,229,.8)', borderRadius:7, order:2 },
      { label:'Expense', data:{!! json_encode($totalExpTrend) !!}, backgroundColor:'rgba(239,68,68,.65)', borderRadius:7, order:3 },
      { label:'Net Profit', data:{!! json_encode($totalProfitTrend) !!}, type:'line', borderColor:'#10B981',
        backgroundColor:'rgba(16,185,129,.12)', borderWidth:2.5, fill:true, tension:.4,
        pointRadius:4, pointBackgroundColor:'#10B981', pointBorderColor:'#fff', pointBorderWidth:2, order:1 }
    ]
  },
  options: {
    responsive:true, maintainAspectRatio:true,
    plugins: {
      legend: { position:'top', labels:{ usePointStyle:true, padding:14, font:{ size:11 }, color: chartTextColor() } },
      tooltip:{ callbacks:{ label: ctx => ' ' + ctx.dataset.label + ': ₹' + Math.abs(ctx.parsed.y).toLocaleString('en-IN') }}
    },
    scales: {
      x: { ...axisDefaults(), grid:{ display:false } },
      y: { ...axisDefaults(), beginAtZero:true, ticks:{ ...axisDefaults().ticks, callback: v => v>=100000 ? '₹'+(v/100000).toFixed(1)+'L' : '₹'+(v/1000).toFixed(0)+'K' }}
    }
  }
});

// ── Revenue vs Expense donut ──────────────────────────────
if (document.getElementById('revExpPieChart')) new Chart(document.getElementById('revExpPieChart'), {
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

// ── Expense cost split donut ──────────────────────────────
if (document.getElementById('expenseSplitPieChart')) {
  var dashExpSplit = {!! json_encode($expenseSplit) !!}.filter(r => r.val > 0);
  new Chart(document.getElementById('expenseSplitPieChart'), {
    type: 'doughnut',
    data: {
      labels: dashExpSplit.map(r => r.label),
      datasets: [{
        data: dashExpSplit.map(r => r.val),
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
}

// ── Donut chart ──────────────────────────────────────────
new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($typeLabels) !!},
    datasets: [{
      data: {!! json_encode($typeData) !!},
      backgroundColor: ['#4F46E5','#F59E0B','#0EA5E9'],
      borderWidth: 3,
      borderColor: chartBgColor(),
      hoverOffset: 8,
    }]
  },
  options: {
    responsive:true, cutout:'72%',
    plugins: {
      legend:{ display:false },
      tooltip:{ callbacks:{ label: ctx => ' ' + ctx.label + ': ' + ctx.parsed }}
    }
  }
});

// ── Daily revenue ────────────────────────────────────────
new Chart(document.getElementById('dailyChart'), {
  type: 'line',
  data: {
    labels: {!! json_encode($dailyLabels) !!},
    datasets: [{
      label: 'Revenue',
      data: {!! json_encode($dailyRevenue) !!},
      borderColor: '#4F46E5',
      backgroundColor: 'rgba(79,70,229,.09)',
      borderWidth: 2.5,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#4F46E5',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointRadius: 5,
      pointHoverRadius: 7,
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:true,
    plugins: { legend:{ display:false }, tooltip:{ callbacks:{ label: ctx => ' ₹' + ctx.parsed.y.toLocaleString('en-IN') }}},
    scales: {
      x: { ...axisDefaults(), grid:{ display:false } },
      y: { ...axisDefaults(), ticks:{ ...axisDefaults().ticks, callback: v => v>=1000 ? '₹'+(v/1000).toFixed(1)+'K' : '₹'+v }}
    }
  }
});
</script>

{{-- ── Staff Bookings Modal ────────────────────────────────────── --}}
<div id="staffModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);overflow-y:auto;padding:20px;">
  <div style="max-width:980px;margin:40px auto;background:var(--card);border-radius:20px;box-shadow:0 24px 80px rgba(0,0,0,.2);overflow:hidden;">
    <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:var(--card);">
      <div>
        <div id="staffModalTitle" style="font-size:1rem;font-weight:800;color:var(--text);">Staff Bookings</div>
        <div id="staffModalSub" style="font-size:.75rem;color:var(--muted);margin-top:2px;">{{ now()->format('F Y') }}</div>
      </div>
      <button onclick="closeStaffModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--muted);line-height:1;padding:4px 10px;">&times;</button>
    </div>
    <div id="staffModalSummary" style="display:none;padding:12px 24px;border-bottom:1px solid var(--border);display:flex;gap:20px;flex-wrap:wrap;background:var(--card);"></div>
    <div id="staffModalBody" style="padding:20px 0;">
      <div style="text-align:center;padding:48px;color:var(--muted);">Loading…</div>
    </div>
  </div>
</div>

<script>
const staffBookingsUrl = '{{ url("admin/dashboard/staff-bookings") }}';

function openStaffModal(userId, name) {
  const modal = document.getElementById('staffModal');
  modal.style.display = 'block';
  document.body.style.overflow = 'hidden';
  document.getElementById('staffModalTitle').textContent = '📋 ' + name + ' — Bookings';
  document.getElementById('staffModalSub').textContent = '{{ now()->format("F Y") }}';
  document.getElementById('staffModalSummary').style.display = 'none';
  document.getElementById('staffModalBody').innerHTML = '<div style="text-align:center;padding:48px;color:var(--muted);">Loading…</div>';

  fetch(staffBookingsUrl + '/' + userId)
    .then(r => r.json())
    .then(res => {
      const { bookings, summary } = res;

      // Summary bar
      const sumEl = document.getElementById('staffModalSummary');
      sumEl.style.display = 'flex';
      sumEl.innerHTML =
        `<span style="font-size:.75rem;color:var(--muted);">Total: <strong style="color:var(--text);">${summary.total}</strong></span>` +
        (summary.stay  ? `<span style="font-size:.75rem;color:var(--muted);">🏨 Stay: <strong style="color:var(--text);">${summary.stay}</strong></span>` : '') +
        (summary.cab   ? `<span style="font-size:.75rem;color:var(--muted);">🚗 Cab: <strong style="color:var(--text);">${summary.cab}</strong></span>` : '') +
        (summary.boat  ? `<span style="font-size:.75rem;color:var(--muted);">⛵ Boat: <strong style="color:var(--text);">${summary.boat}</strong></span>` : '') +
        `<span style="font-size:.75rem;color:var(--muted);margin-left:auto;">Revenue: <strong style="color:var(--emerald);">₹${summary.revenue.toLocaleString('en-IN')}</strong></span>`;

      if (!bookings.length) {
        document.getElementById('staffModalBody').innerHTML = '<div style="text-align:center;padding:48px;color:var(--muted);">No bookings this month.</div>';
        return;
      }

      let html = '<div style="overflow-x:auto;"><table class="dk-table" style="width:100%;min-width:680px;">'
        + '<thead><tr>'
        + '<th>Booking #</th><th>Guest</th><th>Type</th>'
        + '<th>Total</th><th>Paid</th><th style="color:#EF4444;">Due</th>'
        + '<th>Status</th><th>Date</th>'
        + '</tr></thead><tbody>';

      bookings.forEach(b => {
        const statusCls = {'confirmed':'confirmed','completed':'completed','cancelled':'cancelled'}[b.status] || 'in_progress';
        const pendingStyle = b.pending_amount > 0 ? 'color:#EF4444;font-weight:800;' : 'color:var(--emerald);font-weight:600;';
        html += `<tr>
          <td><a href="${b.url}" class="bk-link" target="_blank">${b.number}</a></td>
          <td style="font-weight:600;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${b.guest}</td>
          <td><span class="sb" style="background:#EEF2FF;color:#3730A3;">${b.icon} ${b.type}</span></td>
          <td style="font-weight:600;white-space:nowrap;">₹${b.total_amount.toLocaleString('en-IN')}</td>
          <td style="color:var(--emerald);font-weight:600;white-space:nowrap;">₹${b.paid_amount.toLocaleString('en-IN')}</td>
          <td style="${pendingStyle}white-space:nowrap;">${b.pending_amount > 0 ? '₹' + b.pending_amount.toLocaleString('en-IN') : '—'}</td>
          <td><span class="sb sb-${statusCls}">${b.status.replace(/_/g,' ')}</span></td>
          <td style="color:var(--muted);font-size:.77rem;white-space:nowrap;">${b.date}</td>
        </tr>`;
      });

      html += '</tbody></table></div>';
      document.getElementById('staffModalBody').innerHTML = html;
    })
    .catch(() => {
      document.getElementById('staffModalBody').innerHTML = '<div style="text-align:center;padding:48px;color:#EF4444;">Failed to load. Please try again.</div>';
    });
}

function closeStaffModal() {
  document.getElementById('staffModal').style.display = 'none';
  document.body.style.overflow = '';
}

document.getElementById('staffModal').addEventListener('click', function(e) {
  if (e.target === this) closeStaffModal();
});
</script>

{{-- ── Pending Bookings Modal ──────────────────────────────────── --}}
<div id="pendingModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);overflow-y:auto;padding:20px;">
  <div style="max-width:960px;margin:40px auto;background:var(--card);border-radius:20px;box-shadow:0 24px 80px rgba(0,0,0,.2);overflow:hidden;">
    <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:var(--card);">
      <div>
        <div style="font-size:1rem;font-weight:800;color:var(--text);">⚠️ Pending Payments</div>
        <div id="pendingModalSub" style="font-size:.75rem;color:var(--muted);margin-top:2px;">Bookings with due balance</div>
      </div>
      <button onclick="closePendingModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--muted);line-height:1;padding:4px 10px;">&times;</button>
    </div>
    <div id="pendingModalBody" style="padding:20px 0;">
      <div style="text-align:center;padding:48px;color:var(--muted);">Loading…</div>
    </div>
  </div>
</div>

<script>
function openPendingModal() {
  const modal = document.getElementById('pendingModal');
  modal.style.display = 'block';
  document.body.style.overflow = 'hidden';
  document.getElementById('pendingModalBody').innerHTML = '<div style="text-align:center;padding:48px;color:var(--muted);">Loading…</div>';

  fetch('{{ route("admin.dashboard.pending-bookings") }}')
    .then(r => r.json())
    .then(data => {
      const sub = document.getElementById('pendingModalSub');
      if (!data.length) {
        sub.textContent = 'No pending payments';
        document.getElementById('pendingModalBody').innerHTML = '<div style="text-align:center;padding:48px;color:var(--emerald);font-size:1.1rem;font-weight:700;">🎉 All clear — no pending payments!</div>';
        return;
      }
      const totalPending = data.reduce((s, b) => s + b.pending_amount, 0);
      sub.textContent = data.length + ' booking' + (data.length > 1 ? 's' : '') + ' · ₹' + totalPending.toLocaleString('en-IN') + ' total due';

      let html = '<div style="overflow-x:auto;"><table class="dk-table" style="width:100%;min-width:760px;">'
        + '<thead><tr>'
        + '<th>Booking #</th><th>Guest</th><th>Type</th>'
        + '<th>Total</th><th>Paid</th>'
        + '<th style="color:#EF4444;">Due</th>'
        + '<th>Status</th><th>Added By</th><th>Date</th>'
        + '</tr></thead><tbody>';

      data.forEach(b => {
        const statusCls = {'confirmed':'confirmed','completed':'completed','cancelled':'cancelled'}[b.status] || 'in_progress';
        html += `<tr>
          <td><a href="${b.url}" class="bk-link" target="_blank">${b.number}</a></td>
          <td style="font-weight:600;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${b.guest}</td>
          <td><span class="sb" style="background:#FEF3C7;color:#92400E;">${b.icon} ${b.type}</span></td>
          <td style="font-weight:600;white-space:nowrap;">₹${b.total_amount.toLocaleString('en-IN')}</td>
          <td style="color:var(--emerald);font-weight:600;white-space:nowrap;">₹${b.paid_amount.toLocaleString('en-IN')}</td>
          <td style="color:#EF4444;font-weight:800;white-space:nowrap;">₹${b.pending_amount.toLocaleString('en-IN')}</td>
          <td><span class="sb sb-${statusCls}">${b.status.replace(/_/g,' ')}</span></td>
          <td style="font-size:.77rem;color:var(--sub);font-weight:600;white-space:nowrap;">${b.added_by}</td>
          <td style="color:var(--muted);font-size:.77rem;white-space:nowrap;">${b.date}</td>
        </tr>`;
      });

      html += '</tbody></table></div>';
      document.getElementById('pendingModalBody').innerHTML = html;
    })
    .catch(() => {
      document.getElementById('pendingModalBody').innerHTML = '<div style="text-align:center;padding:48px;color:#EF4444;">Failed to load. Please try again.</div>';
    });
}

function closePendingModal() {
  document.getElementById('pendingModal').style.display = 'none';
  document.body.style.overflow = '';
}

document.getElementById('pendingModal').addEventListener('click', function(e) {
  if (e.target === this) closePendingModal();
});

/* ── Today's Booking Detail Modal ── */
function openTodayBookingModal(b) {
  var fmt = function(n) { return '₹' + Number(n).toLocaleString('en-IN'); };
  document.getElementById('tdBkIcon').textContent    = b.icon;
  document.getElementById('tdBkIcon').style.background = b.bg;
  document.getElementById('tdBkNumber').textContent  = b.number;
  document.getElementById('tdBkType').textContent    = b.type;
  document.getElementById('tdBkHead').style.background = b.bg;
  document.getElementById('tdBkGuest').textContent   = b.guest;
  var phoneEl = document.getElementById('tdBkPhone');
  if (b.phone && b.phone !== '—') {
    phoneEl.textContent = '📞 ' + b.phone;
    phoneEl.href = 'tel:' + b.phone;
    phoneEl.style.display = '';
  } else {
    phoneEl.style.display = 'none';
  }
  document.getElementById('tdBkFrom').textContent    = b.date_from;
  document.getElementById('tdBkTo').textContent      = b.date_to !== '—' ? b.date_to : 'Same Day';
  document.getElementById('tdBkTotal').textContent   = fmt(b.amount);
  document.getElementById('tdBkPaid').textContent    = fmt(b.paid);
  document.getElementById('tdBkDue').textContent     = fmt(b.pending);
  document.getElementById('tdBkStatus').textContent  = b.status.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase());
  document.getElementById('tdBkAdded').textContent   = b.added_by;
  document.getElementById('tdBkUrl').href            = b.url;
  var modal = document.getElementById('todayBkModal');
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
function closeTodayBkModal() {
  document.getElementById('todayBkModal').style.display = 'none';
  document.body.style.overflow = '';
}
document.getElementById('todayBkModal').addEventListener('click', function(e) {
  if (e.target === this) closeTodayBkModal();
});
</script>

@endsection
