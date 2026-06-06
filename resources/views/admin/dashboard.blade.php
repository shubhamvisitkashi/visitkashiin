@extends('admin.layouts.app')
@section('content')

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
/* ══════════════════════════════════════════════════════
   VISITKASHI CRM — Premium SaaS Dashboard
   Design: Glass morphism · Gradient accents · Dark-ready
══════════════════════════════════════════════════════ */
:root {
  --dk-bg:     #F1F5F9;
  --dk-card:   #FFFFFF;
  --dk-border: #E2E8F0;
  --dk-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.06);
  --dk-text:   #0F172A;
  --dk-sub:    #475569;
  --dk-muted:  #94A3B8;
  --indigo:    #4F46E5;
  --violet:    #7C3AED;
  --emerald:   #10B981;
  --amber:     #F59E0B;
  --rose:      #EF4444;
  --sky:       #0EA5E9;
  --teal:      #14B8A6;
  --orange:    #F97316;
  --r:         16px;
}

.dk-page { background:var(--dk-bg); min-height:100vh; padding:20px 22px; }

/* ── Hero header ─────────────────────── */
.dk-hero {
  background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #0EA5E9 100%);
  border-radius:20px; padding:24px 30px; margin-bottom:22px;
  display:flex; align-items:center; justify-content:space-between;
  flex-wrap:wrap; gap:14px; position:relative; overflow:hidden;
}
.dk-hero::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M0 0h40v40H0V0zm40 40h40v40H40V40z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  pointer-events:none;
}
.dk-hero-left { position:relative; z-index:1; }
.dk-hero h1 { color:#fff; font-size:1.6rem; font-weight:800; margin:0; letter-spacing:-.01em; }
.dk-hero p  { color:rgba(255,255,255,.75); font-size:.85rem; margin:.3rem 0 0; }
.dk-hero-actions { position:relative; z-index:1; display:flex; gap:8px; flex-wrap:wrap; }
.dk-hero-btn {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,255,255,.18); border:1.5px solid rgba(255,255,255,.3);
  color:#fff; border-radius:10px; padding:9px 16px; font-size:.8rem; font-weight:600;
  text-decoration:none; transition:.18s; white-space:nowrap;
}
.dk-hero-btn:hover { background:rgba(255,255,255,.28); color:#fff; }
.dk-hero-btn.primary { background:rgba(255,255,255,.92); color:#4F46E5; }
.dk-hero-btn.primary:hover { background:#fff; }

/* ── KPI grid ────────────────────────── */
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
@media(max-width:1200px){.kpi-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px) {.kpi-grid{grid-template-columns:1fr;}}

.kpi-card {
  background:var(--dk-card); border-radius:var(--r); border:1px solid var(--dk-border);
  box-shadow:var(--dk-shadow); padding:20px 22px; display:flex; align-items:flex-start;
  gap:16px; position:relative; overflow:hidden; transition:.2s;
}
.kpi-card:hover { transform:translateY(-2px); box-shadow:0 8px 30px rgba(0,0,0,.10); }
.kpi-card::after {
  content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
  background:var(--kpi-color, var(--indigo));
}
.kpi-icon {
  width:50px; height:50px; border-radius:14px; display:flex; align-items:center;
  justify-content:center; font-size:1.5rem; flex-shrink:0;
  background:var(--kpi-bg, #EEF2FF);
}
.kpi-body { flex:1; min-width:0; }
.kpi-label { font-size:.72rem; font-weight:700; color:var(--dk-muted); text-transform:uppercase; letter-spacing:.04em; margin-bottom:4px; }
.kpi-value { font-size:1.75rem; font-weight:800; color:var(--dk-text); line-height:1.1; }
.kpi-sub { font-size:.73rem; color:var(--dk-muted); margin-top:4px; display:flex; align-items:center; gap:5px; }
.kpi-badge { font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:20px; }
.kpi-badge.up   { background:#D1FAE5; color:#065F46; }
.kpi-badge.down { background:#FEE2E2; color:#991B1B; }
.kpi-badge.neu  { background:#E0E7FF; color:#3730A3; }

/* ── Type stats row ──────────────────── */
.type-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
@media(max-width:768px){.type-row{grid-template-columns:1fr;}}

.type-card {
  background:var(--dk-card); border-radius:var(--r); border:1px solid var(--dk-border);
  box-shadow:var(--dk-shadow); padding:18px 20px; display:flex; align-items:center; gap:14px;
}
.type-card-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.type-card-info { flex:1; }
.type-card-label { font-size:.75rem; font-weight:700; color:var(--dk-muted); text-transform:uppercase; letter-spacing:.04em; }
.type-card-count { font-size:1.4rem; font-weight:800; color:var(--dk-text); }
.type-card-rev   { font-size:.8rem; color:var(--dk-sub); font-weight:600; margin-top:2px; }
.type-stat-row { display:flex; gap:12px; margin-top:8px; }
.type-stat { font-size:.7rem; font-weight:600; padding:3px 8px; border-radius:20px; }

/* ── Two-column layout ───────────────── */
.dk-cols { display:grid; grid-template-columns:1fr 380px; gap:20px; margin-bottom:20px; }
@media(max-width:1100px){.dk-cols{grid-template-columns:1fr;}}

/* ── Chart cards ─────────────────────── */
.chart-card {
  background:var(--dk-card); border-radius:var(--r); border:1px solid var(--dk-border);
  box-shadow:var(--dk-shadow); overflow:hidden; margin-bottom:20px;
}
.chart-head {
  padding:16px 20px; border-bottom:1px solid var(--dk-border);
  display:flex; align-items:center; justify-content:space-between; gap:10px;
}
.chart-title { font-size:.9rem; font-weight:700; color:var(--dk-text); }
.chart-sub   { font-size:.72rem; color:var(--dk-muted); }
.chart-body  { padding:18px 16px; }
.chart-legend { display:flex; gap:14px; flex-wrap:wrap; }
.legend-dot   { display:inline-flex; align-items:center; gap:5px; font-size:.72rem; font-weight:600; color:var(--dk-sub); }
.legend-dot::before { content:''; width:10px; height:10px; border-radius:3px; background:var(--lc, #ccc); flex-shrink:0; }

/* ── Table card ──────────────────────── */
.table-card {
  background:var(--dk-card); border-radius:var(--r); border:1px solid var(--dk-border);
  box-shadow:var(--dk-shadow); overflow:hidden;
}
.table-head {
  padding:15px 20px; border-bottom:1px solid var(--dk-border);
  display:flex; align-items:center; justify-content:space-between;
}
.table-title { font-size:.9rem; font-weight:700; color:var(--dk-text); }
.dk-table { width:100%; border-collapse:collapse; }
.dk-table th { background:#F8FAFC; color:var(--dk-muted); font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; padding:10px 16px; text-align:left; }
.dk-table td { padding:11px 16px; border-bottom:1px solid #F1F5F9; font-size:.82rem; color:var(--dk-text); }
.dk-table tr:last-child td { border-bottom:none; }
.dk-table tr:hover td { background:#FAFBFF; }

/* ── Status badges ───────────────────── */
.sb { display:inline-flex; align-items:center; gap:4px; font-size:.65rem; font-weight:700; padding:3px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em; }
.sb-confirmed  { background:#DBEAFE; color:#1E40AF; }
.sb-completed  { background:#D1FAE5; color:#065F46; }
.sb-cancelled  { background:#FEE2E2; color:#991B1B; }
.sb-in_progress{ background:#FEF3C7; color:#92400E; }
.sb-not_started{ background:#F1F5F9; color:#475569; }
.sb-pending    { background:#FEF9C3; color:#854D0E; }
.sb-stay  { background:#EDE9FE; color:#6D28D9; }
.sb-cab   { background:#FEF3C7; color:#B45309; }
.sb-boat  { background:#E0F2FE; color:#0369A1; }

/* ── Activity feed ───────────────────── */
.activity-feed { padding:4px 0; }
.af-item { display:flex; align-items:flex-start; gap:12px; padding:11px 20px; border-bottom:1px solid #F1F5F9; }
.af-item:last-child { border-bottom:none; }
.af-dot { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.af-body { flex:1; min-width:0; }
.af-title { font-size:.82rem; font-weight:600; color:var(--dk-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.af-meta  { font-size:.7rem; color:var(--dk-muted); margin-top:2px; }
.af-amount{ font-size:.82rem; font-weight:700; color:var(--emerald); flex-shrink:0; }

/* ── Quick links ─────────────────────── */
.ql-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; padding:16px; }
.ql-btn {
  display:flex; align-items:center; gap:10px; padding:12px 14px;
  background:#FAFBFF; border:1.5px solid var(--dk-border); border-radius:12px;
  text-decoration:none; transition:.18s; font-size:.82rem; font-weight:600; color:var(--dk-text);
}
.ql-btn:hover { background:#EEF2FF; border-color:#A5B4FC; color:var(--indigo); transform:translateY(-1px); }
.ql-btn-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }

/* ── Upcoming list ───────────────────── */
.upcoming-item {
  display:flex; align-items:center; gap:12px; padding:10px 20px;
  border-bottom:1px solid #F1F5F9;
}
.upcoming-item:last-child { border-bottom:none; }
.upcoming-date { text-align:center; flex-shrink:0; width:42px; }
.upcoming-date .ud-day { font-size:1.2rem; font-weight:800; color:var(--indigo); line-height:1; }
.upcoming-date .ud-mon { font-size:.62rem; font-weight:700; color:var(--dk-muted); text-transform:uppercase; }
.upcoming-info { flex:1; min-width:0; }
.upcoming-name { font-size:.82rem; font-weight:600; color:var(--dk-text); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.upcoming-sub  { font-size:.7rem; color:var(--dk-muted); margin-top:2px; }
.upcoming-amt  { font-size:.82rem; font-weight:700; color:var(--dk-text); flex-shrink:0; }

/* ── Progress bar ────────────────────── */
.prog-bar { height:6px; background:#E2E8F0; border-radius:99px; overflow:hidden; margin-top:4px; }
.prog-fill { height:100%; border-radius:99px; background:linear-gradient(90deg, var(--indigo), var(--sky)); transition:width .4s; }

/* ── Responsive ──────────────────────── */
@media(max-width:767px){
  .dk-page { padding:10px 12px; }
  .dk-hero { padding:16px 18px; margin-bottom:14px; }
  .dk-hero h1 { font-size:1.2rem; }
  .kpi-value { font-size:1.4rem; }
  .kpi-icon  { width:40px; height:40px; font-size:1.2rem; }
  .kpi-card  { padding:14px 16px; }
  .dk-cols   { gap:14px; }
  .chart-body{ padding:12px 10px; }
}
</style>

<div class="dk-page">

{{-- ── HERO ─────────────────────────────────────────────── --}}
<div class="dk-hero">
  <div class="dk-hero-left">
    <h1>🏛️ VisitKashi CRM</h1>
    <p>Welcome back, {{ auth('admin')->user()->name }} &nbsp;·&nbsp; {{ now()->format('l, d F Y') }}</p>
  </div>
  <div class="dk-hero-actions">
    <a href="{{ route('bookings.create-direct') }}" class="dk-hero-btn primary">
      ＋ New Booking
    </a>
    <a href="{{ route('bookings.index') }}" class="dk-hero-btn">📋 All Bookings</a>
    <a href="{{ route('cab-bookings.create') }}" class="dk-hero-btn">🚗 Cab</a>
    <a href="{{ route('bookings.calendar') }}" class="dk-hero-btn">📅 Calendar</a>
  </div>
</div>

{{-- ── KPI CARDS ────────────────────────────────────────── --}}
<div class="kpi-grid">

  {{-- Today's Bookings --}}
  <div class="kpi-card" style="--kpi-color:var(--indigo);--kpi-bg:#EEF2FF;">
    <div class="kpi-icon">📋</div>
    <div class="kpi-body">
      <div class="kpi-label">Today's Bookings</div>
      <div class="kpi-value">{{ $totalBookingsToday }}</div>
      <div class="kpi-sub">
        <span>🏨 {{ $stayToday }} Stay</span>&nbsp;·&nbsp;
        <span>🚗 {{ $cabToday }} Cab</span>&nbsp;·&nbsp;
        <span>⛵ {{ $boatToday }} Boat</span>
      </div>
    </div>
  </div>

  {{-- This Month Revenue --}}
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

  {{-- Pending Amount --}}
  <div class="kpi-card" style="--kpi-color:var(--amber);--kpi-bg:#FEF3C7;">
    <div class="kpi-icon">⚠️</div>
    <div class="kpi-body">
      <div class="kpi-label">Pending Amount</div>
      <div class="kpi-value">₹{{ number_format($totalPending) }}</div>
      <div class="kpi-sub">
        <span>{{ $pendingPaymentsCount }} bookings have due balance</span>
      </div>
    </div>
  </div>

  {{-- This Month Bookings --}}
  <div class="kpi-card" style="--kpi-color:var(--sky);--kpi-bg:#E0F2FE;">
    <div class="kpi-icon">📅</div>
    <div class="kpi-body">
      <div class="kpi-label">This Month Bookings</div>
      <div class="kpi-value">{{ $totalBookingsMonth }}</div>
      <div class="kpi-sub">
        All-time: <strong>{{ number_format($allTimeBookings) }}</strong> bookings &nbsp;·&nbsp; ₹{{ number_format($allTimeRevenue) }}
      </div>
    </div>
  </div>

</div>

{{-- ── BOOKING TYPE BREAKDOWN ───────────────────────────── --}}
<div class="type-row">

  {{-- Stay --}}
  <div class="type-card">
    <div class="type-card-icon" style="background:#EDE9FE;">🏨</div>
    <div class="type-card-info">
      <div class="type-card-label">Stay / Hotel</div>
      <div class="type-card-count">{{ $stayMonth }} <span style="font-size:.8rem;font-weight:500;color:var(--dk-muted);">this month</span></div>
      <div class="type-card-rev">₹{{ number_format($stayRevMonth) }} revenue</div>
      <div class="type-stat-row">
        <span class="type-stat" style="background:#DBEAFE;color:#1E40AF;">✓ {{ $stayConfirmed }} Confirmed</span>
        <span class="type-stat" style="background:#D1FAE5;color:#065F46;">✔ {{ $stayCompleted }} Done</span>
      </div>
    </div>
  </div>

  {{-- Cab --}}
  <div class="type-card">
    <div class="type-card-icon" style="background:#FEF3C7;">🚗</div>
    <div class="type-card-info">
      <div class="type-card-label">Cab / Transport</div>
      <div class="type-card-count">{{ $cabMonth }} <span style="font-size:.8rem;font-weight:500;color:var(--dk-muted);">this month</span></div>
      <div class="type-card-rev">₹{{ number_format($cabRevMonth) }} revenue</div>
      <div class="type-stat-row">
        <a href="{{ route('cab-bookings.index') }}" class="type-stat" style="background:#FEF3C7;color:#B45309;text-decoration:none;">View All Cabs →</a>
      </div>
    </div>
  </div>

  {{-- Boat --}}
  <div class="type-card">
    <div class="type-card-icon" style="background:#E0F2FE;">⛵</div>
    <div class="type-card-info">
      <div class="type-card-label">Boat / Ghat</div>
      <div class="type-card-count">{{ $boatMonth }} <span style="font-size:.8rem;font-weight:500;color:var(--dk-muted);">this month</span></div>
      <div class="type-card-rev">₹{{ number_format($boatRevMonth) }} revenue</div>
      <div class="type-stat-row">
        <span class="type-stat" style="background:#FEE2E2;color:#991B1B;">{{ $boatPending }} pending payment</span>
      </div>
    </div>
  </div>

</div>

{{-- ── MAIN CONTENT ─────────────────────────────────────── --}}
<div class="dk-cols">

  {{-- LEFT: Charts + Tables --}}
  <div>

    {{-- Revenue Trend Chart --}}
    <div class="chart-card">
      <div class="chart-head">
        <div>
          <div class="chart-title">Revenue Trend — Last 6 Months</div>
          <div class="chart-sub">Stay · Cab · Boat breakdown</div>
        </div>
        <div class="chart-legend">
          <span class="legend-dot" style="--lc:#4F46E5;">Stay</span>
          <span class="legend-dot" style="--lc:#F59E0B;">Cab</span>
          <span class="legend-dot" style="--lc:#0EA5E9;">Boat</span>
        </div>
      </div>
      <div class="chart-body">
        <canvas id="revenueChart" height="90"></canvas>
      </div>
    </div>

    {{-- Recent Bookings Table --}}
    <div class="table-card">
      <div class="table-head">
        <div class="table-title">Recent Bookings</div>
        <a href="{{ route('bookings.index') }}" style="font-size:.75rem;color:var(--indigo);font-weight:600;text-decoration:none;">View All →</a>
      </div>
      <div style="overflow-x:auto;">
        <table class="dk-table">
          <thead>
            <tr>
              <th>Booking #</th>
              <th>Guest</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBookings as $b)
            <tr>
              <td>
                <a href="{{ $b['url'] }}" style="color:var(--indigo);font-weight:600;text-decoration:none;font-size:.8rem;">
                  {{ $b['number'] }}
                </a>
              </td>
              <td style="font-weight:500;">{{ $b['guest'] }}</td>
              <td><span class="sb sb-{{ $b['type'] }}">{{ $b['icon'] }} {{ ucfirst($b['type']) }}</span></td>
              <td style="font-weight:700;">₹{{ number_format($b['amount']) }}</td>
              <td><span class="sb sb-{{ $b['status'] }}">{{ ucwords(str_replace('_',' ',$b['status'] ?? 'pending')) }}</span></td>
              <td style="color:var(--dk-muted);">{{ $b['date'] ? \Carbon\Carbon::parse($b['date'])->format('d M') : '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--dk-muted);">No bookings yet — <a href="{{ route('bookings.create-direct') }}" style="color:var(--indigo);">create one</a></td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Daily Revenue (7 days) --}}
    <div class="chart-card" style="margin-top:20px;">
      <div class="chart-head">
        <div>
          <div class="chart-title">Daily Revenue — Last 7 Days</div>
          <div class="chart-sub">All booking types combined</div>
        </div>
      </div>
      <div class="chart-body">
        <canvas id="dailyChart" height="70"></canvas>
      </div>
    </div>

  </div>{{-- /left --}}

  {{-- RIGHT: Sidebar --}}
  <div>

    {{-- Quick Create --}}
    <div class="chart-card" style="margin-bottom:20px;">
      <div class="chart-head">
        <div class="chart-title">⚡ Quick Create</div>
      </div>
      <div class="ql-grid">
        <a href="{{ route('bookings.create-direct') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#EEF2FF;">🏨</div>
          Stay Booking
        </a>
        <a href="{{ route('cab-bookings.create') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#FEF3C7;">🚗</div>
          Cab Booking
        </a>
        <a href="{{ route('tour-booking.create') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#EDE9FE;">🗺️</div>
          Tour Package
        </a>
        <a href="{{ route('bookings.create-direct') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#E0F2FE;">⛵</div>
          Boat Ride
        </a>
        <a href="{{ route('bookings.calendar') }}" class="ql-btn">
          <div class="ql-btn-icon" style="background:#D1FAE5;">📅</div>
          Calendar
        </a>
        <a href="{{ route('bookings.index') }}?has_due=1" class="ql-btn">
          <div class="ql-btn-icon" style="background:#FEE2E2;">⚠️</div>
          Due Payments
        </a>
      </div>
    </div>

    {{-- Booking Type Donut --}}
    <div class="chart-card" style="margin-bottom:20px;">
      <div class="chart-head">
        <div class="chart-title">Booking Mix</div>
      </div>
      <div class="chart-body" style="display:flex;align-items:center;gap:16px;">
        <div style="width:130px;flex-shrink:0;">
          <canvas id="donutChart"></canvas>
        </div>
        <div style="flex:1;">
          @php $typeTotal = array_sum($typeData); @endphp
          @foreach($typeLabels as $i => $lbl)
          @php $pct = $typeTotal > 0 ? round($typeData[$i] / $typeTotal * 100) : 0; @endphp
          <div style="margin-bottom:10px;">
            <div style="display:flex;justify-content:space-between;font-size:.75rem;margin-bottom:3px;">
              <span style="font-weight:600;">{{ $lbl }}</span>
              <span style="color:var(--dk-muted);">{{ $typeData[$i] }} ({{ $pct }}%)</span>
            </div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%;background:{{ ['#4F46E5','#F59E0B','#0EA5E9'][$i] ?? '#94A3B8' }};"></div></div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Upcoming Check-ins --}}
    <div class="chart-card" style="margin-bottom:20px;">
      <div class="chart-head">
        <div class="chart-title">🏨 Upcoming Check-ins</div>
        <span style="font-size:.7rem;color:var(--dk-muted);">Next 7 days</span>
      </div>
      @if($upcomingCheckins->count())
      <div>
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
      </div>
      @else
      <div style="padding:24px;text-align:center;color:var(--dk-muted);font-size:.82rem;">No upcoming check-ins</div>
      @endif
    </div>

    {{-- Upcoming Cab Pickups --}}
    @if($upcomingCabs->count())
    <div class="chart-card" style="margin-bottom:20px;">
      <div class="chart-head">
        <div class="chart-title">🚗 Upcoming Pickups</div>
        <span style="font-size:.7rem;color:var(--dk-muted);">Next 7 days</span>
      </div>
      @foreach($upcomingCabs as $c)
      <div class="upcoming-item">
        <div class="upcoming-date">
          <div class="ud-day">{{ \Carbon\Carbon::parse($c->pickup_date)->format('d') }}</div>
          <div class="ud-mon">{{ \Carbon\Carbon::parse($c->pickup_date)->format('M') }}</div>
        </div>
        <div class="upcoming-info">
          <div class="upcoming-name">{{ $c->customer_name }}</div>
          <div class="upcoming-sub">{{ $c->pickup_address }} → {{ $c->drop_address }}</div>
        </div>
        <div class="upcoming-amt" style="font-size:.7rem;">{{ $c->pickup_time ? \Carbon\Carbon::parse($c->pickup_time)->format('h:i A') : '—' }}</div>
      </div>
      @endforeach
    </div>
    @endif

    {{-- Booking Status Breakdown --}}
    <div class="chart-card">
      <div class="chart-head">
        <div class="chart-title">Stay Booking Status</div>
      </div>
      <div style="padding:16px;">
        @php
          $statuses = [
            'confirmed'   => ['label'=>'Confirmed',    'bg'=>'#DBEAFE','c'=>'#1E40AF'],
            'in_progress' => ['label'=>'In Progress',  'bg'=>'#FEF3C7','c'=>'#92400E'],
            'completed'   => ['label'=>'Completed',    'bg'=>'#D1FAE5','c'=>'#065F46'],
            'cancelled'   => ['label'=>'Cancelled',    'bg'=>'#FEE2E2','c'=>'#991B1B'],
            'not_started' => ['label'=>'Not Started',  'bg'=>'#F1F5F9','c'=>'#475569'],
          ];
          $total = $stayStatuses->sum();
        @endphp
        @foreach($statuses as $key => $s)
        @php $cnt = $stayStatuses[$key] ?? 0; $pct = $total > 0 ? round($cnt/$total*100) : 0; @endphp
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <span style="font-size:.72rem;font-weight:700;min-width:90px;padding:3px 8px;border-radius:20px;background:{{ $s['bg'] }};color:{{ $s['c'] }};">{{ $s['label'] }}</span>
          <div class="prog-bar" style="flex:1;"><div class="prog-fill" style="width:{{ $pct }}%;background:{{ $s['c'] }};"></div></div>
          <span style="font-size:.72rem;font-weight:700;color:var(--dk-text);min-width:30px;text-align:right;">{{ $cnt }}</span>
        </div>
        @endforeach
      </div>
    </div>

  </div>{{-- /right --}}
</div>{{-- /dk-cols --}}

</div>{{-- /dk-page --}}

<script>
// ── Revenue trend chart ───────────────────────────────────
new Chart(document.getElementById('revenueChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($monthLabels) !!},
    datasets: [
      {
        label: 'Stay',
        data: {!! json_encode($stayRevTrend) !!},
        backgroundColor: 'rgba(79,70,229,.8)',
        borderRadius: 6,
        borderSkipped: false,
      },
      {
        label: 'Cab',
        data: {!! json_encode($cabRevTrend) !!},
        backgroundColor: 'rgba(245,158,11,.8)',
        borderRadius: 6,
        borderSkipped: false,
      },
      {
        label: 'Boat',
        data: {!! json_encode($boatRevTrend) !!},
        backgroundColor: 'rgba(14,165,233,.8)',
        borderRadius: 6,
        borderSkipped: false,
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: { legend: { display:false } },
    scales: {
      x: { grid:{ display:false }, ticks:{ font:{size:11} } },
      y: {
        grid:{ color:'rgba(0,0,0,.04)' },
        ticks:{
          font:{size:11},
          callback: v => v >= 1000 ? '₹' + (v/1000).toFixed(0) + 'K' : '₹' + v
        }
      }
    }
  }
});

// ── Donut chart ───────────────────────────────────────────
new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($typeLabels) !!},
    datasets: [{
      data: {!! json_encode($typeData) !!},
      backgroundColor: ['#4F46E5','#F59E0B','#0EA5E9'],
      borderWidth: 0,
      hoverOffset: 6,
    }]
  },
  options: {
    responsive: true,
    cutout: '70%',
    plugins: { legend:{ display:false }, tooltip:{ callbacks:{
      label: ctx => ' ' + ctx.label + ': ' + ctx.parsed
    }}}
  }
});

// ── Daily revenue chart ───────────────────────────────────
new Chart(document.getElementById('dailyChart'), {
  type: 'line',
  data: {
    labels: {!! json_encode($dailyLabels) !!},
    datasets: [{
      label: 'Revenue',
      data: {!! json_encode($dailyRevenue) !!},
      borderColor: '#4F46E5',
      backgroundColor: 'rgba(79,70,229,.08)',
      borderWidth: 2.5,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#4F46E5',
      pointRadius: 4,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: { legend:{ display:false } },
    scales: {
      x: { grid:{ display:false }, ticks:{ font:{size:11} } },
      y: {
        grid:{ color:'rgba(0,0,0,.04)' },
        ticks:{
          font:{size:11},
          callback: v => v >= 1000 ? '₹' + (v/1000).toFixed(1) + 'K' : '₹' + v
        }
      }
    }
  }
});
</script>

@endsection
