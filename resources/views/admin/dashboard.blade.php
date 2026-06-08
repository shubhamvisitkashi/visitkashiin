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
body.dark-mode .ql-btn            { background:#263348 !important; border-color:#334155 !important; color:#E2E8F0 !important; }
body.dark-mode .ql-btn:hover      { background:#2D3F5A !important; border-color:#4F46E5 !important; color:#A5B4FC !important; }
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
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
@media(max-width:1200px){.kpi-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px) {.kpi-grid{grid-template-columns:1fr;}}

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
.dk-cols { display:grid; grid-template-columns:1fr 370px; gap:20px; margin-bottom:20px; }
@media(max-width:1100px){.dk-cols{grid-template-columns:1fr;}}

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

/* ── Quick Create ─────────────────────────── */
.ql-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; padding:16px; box-sizing:border-box; }
.ql-btn {
  display:flex; align-items:center; gap:9px; padding:11px 12px;
  background:#FAFBFF; border:1.5px solid var(--border); border-radius:12px;
  text-decoration:none; transition:all var(--t); font-size:.8rem; font-weight:600; color:var(--text);
  min-width:0; overflow:hidden;
}
.ql-btn span { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.ql-btn:hover { background:#EEF2FF; border-color:#A5B4FC; color:var(--indigo); transform:translateY(-2px); box-shadow:0 4px 14px rgba(79,70,229,.12); }
.ql-btn-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }

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

  /* Quick Create — full single column on mobile */
  .ql-grid        { grid-template-columns:1fr; padding:12px; gap:8px; }
  .ql-btn         { padding:12px 14px; font-size:.85rem; }
  .ql-btn span    { white-space:normal; }

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
</style>

<div class="dk-page">

{{-- ══ HERO ══ --}}
<div class="dk-hero">
  <div class="dk-hero-left">
    <h1>🏛️ VisitKashi CRM</h1>
    <p>Welcome back, {{ auth('admin')->user()->name }} &nbsp;·&nbsp; {{ now()->format('l, d F Y') }}</p>
  </div>
  <div class="dk-hero-actions">
    <a href="{{ route('bookings.create-direct') }}" class="dk-hero-btn primary">＋ New Booking</a>
    <a href="{{ route('bookings.index') }}" class="dk-hero-btn">📋 All Bookings</a>
    <a href="{{ route('cab-bookings.create') }}" class="dk-hero-btn">🚗 Cab</a>
    <a href="{{ route('bookings.calendar') }}" class="dk-hero-btn">📅 Calendar</a>
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
      <div class="kpi-label">This Month Bookings</div>
      <div class="kpi-value">{{ $totalBookingsMonth }}</div>
      <div class="kpi-sub">All-time: <strong>{{ number_format($allTimeBookings) }}</strong> · ₹{{ number_format($allTimeRevenue) }}</div>
    </div>
  </div>
</div>

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
<div class="dk-cols">

  {{-- LEFT --}}
  <div>

    {{-- Revenue Trend Chart --}}
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
            <td class="mob-hide" style="color:var(--muted);font-size:.77rem;">{{ $b['date'] ? \Carbon\Carbon::parse($b['date'])->format('d M') : '—' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:28px;color:var(--muted);">
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
          <div class="chart-title">📊 Daily Revenue — Last 7 Days</div>
          <div class="chart-sub">All booking types combined</div>
        </div>
      </div>
      <div class="chart-body">
        <canvas id="dailyChart" height="75"></canvas>
      </div>
    </div>

  </div>{{-- /left --}}

  {{-- RIGHT --}}
  <div>

    {{-- Quick Create --}}
    <div class="chart-card">
      <div class="chart-head">
        <div class="chart-title">⚡ Quick Create</div>
      </div>
      <div class="ql-grid">
        <a href="{{ route('bookings.create-stay') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#EEF2FF;">🏨</div><span>Stay Booking</span>
        </a>
        <a href="{{ route('cab-bookings.create') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#FEF3C7;">🚗</div><span>Cab Booking</span>
        </a>
        <a href="{{ route('tour-booking.create') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#EDE9FE;">🗺️</div><span>Tour Package</span>
        </a>
        <a href="{{ route('boat-booking.create') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#E0F2FE;">⛵</div><span>Boat Ride</span>
        </a>
        <a href="{{ route('bookings.calendar') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#D1FAE5;">📅</div><span>Calendar</span>
        </a>
        <a href="{{ route('bookings.index') }}?has_due=1" class="ql-btn">
          <div class="ql-btn-icon" style="background:#FEE2E2;">⚠️</div><span>Due Payments</span>
        </a>
      </div>
    </div>

    {{-- Booking Mix --}}
    <div class="chart-card">
      <div class="chart-head">
        <div class="chart-title">🥧 Booking Mix</div>
        <span style="font-size:.7rem;color:var(--muted);">All time</span>
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

    {{-- Upcoming Check-ins --}}
    <div class="chart-card">
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

    {{-- Upcoming Cab Pickups --}}
    @if($upcomingCabs->count())
    <div class="chart-card">
      <div class="chart-head">
        <div class="chart-title">🚗 Upcoming Pickups</div>
        <span style="font-size:.7rem;color:var(--muted);">Next 7 days</span>
      </div>
      @foreach($upcomingCabs as $c)
      <div class="upcoming-item">
        <div class="upcoming-date">
          <div class="ud-day">{{ \Carbon\Carbon::parse($c->pickup_date)->format('d') }}</div>
          <div class="ud-mon">{{ \Carbon\Carbon::parse($c->pickup_date)->format('M') }}</div>
        </div>
        <div class="upcoming-info">
          <div class="upcoming-name">{{ $c->customer_name }}</div>
          <div class="upcoming-sub" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($c->pickup_address,30) }} → {{ Str::limit($c->drop_address,20) }}</div>
        </div>
        <div class="upcoming-amt" style="font-size:.72rem;font-weight:600;">
          {{ $c->pickup_time ? \Carbon\Carbon::parse($c->pickup_time)->format('h:i A') : '—' }}
        </div>
      </div>
      @endforeach
    </div>
    @endif

    {{-- Stay Booking Status Breakdown --}}
    <div class="chart-card">
      <div class="chart-head">
        <div class="chart-title">📊 Stay Booking Status</div>
      </div>
      <div style="padding:16px 20px;">
        @php
          $statuses = [
            'confirmed'   => ['label'=>'Confirmed',   'bg'=>'#DBEAFE','c'=>'#1E40AF'],
            'in_progress' => ['label'=>'In Progress', 'bg'=>'#FEF3C7','c'=>'#92400E'],
            'completed'   => ['label'=>'Completed',   'bg'=>'#D1FAE5','c'=>'#065F46'],
            'cancelled'   => ['label'=>'Cancelled',   'bg'=>'#FEE2E2','c'=>'#991B1B'],
            'not_started' => ['label'=>'Not Started', 'bg'=>'#F1F5F9','c'=>'#475569'],
          ];
          $total = $stayStatuses->sum();
        @endphp
        @foreach($statuses as $key => $s)
        @php $cnt = $stayStatuses[$key] ?? 0; $pct = $total > 0 ? round($cnt/$total*100) : 0; @endphp
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:11px;">
          <span style="font-size:.7rem;font-weight:700;min-width:90px;padding:3px 9px;border-radius:20px;background:{{ $s['bg'] }};color:{{ $s['c'] }};text-align:center;">{{ $s['label'] }}</span>
          <div class="prog-bar" style="flex:1;">
            <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $s['c'] }};"></div>
          </div>
          <span style="font-size:.72rem;font-weight:800;color:var(--text);min-width:24px;text-align:right;">{{ $cnt }}</span>
        </div>
        @endforeach
      </div>
    </div>

  </div>{{-- /right --}}
</div>{{-- /dk-cols --}}

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

// ── Revenue trend ────────────────────────────────────────
new Chart(document.getElementById('revenueChart'), {
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

@endsection
