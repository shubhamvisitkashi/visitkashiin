@extends('admin.layouts.app')
@section('content')

<style>
/* ══════════════════════════════════════════════
   VISITKASHI — Boat Bookings Index
   Premium SaaS Design
══════════════════════════════════════════════ */
:root {
  --bb-bg:     #EFF6FF;
  --bb-card:   #fff;
  --bb-border: #BFDBFE;
  --bb-shadow: 0 1px 3px rgba(0,0,0,.04), 0 2px 12px rgba(14,165,233,.06);
  --bb-text:   #0F172A;
  --bb-sub:    #475569;
  --bb-muted:  #94A3B8;
  --bb-blue:   #0EA5E9;
  --bb-dk:     #0C4A6E;
  --bb-navy:   #075985;
  --bb-green:  #10B981;
  --bb-amber:  #F59E0B;
  --bb-red:    #EF4444;
  --bb-indigo: #4F46E5;
}

.bb-page { background:var(--bb-bg); min-height:100vh; padding:20px 22px 50px; }

/* ── Header ───────────────────────────── */
.bb-hero {
  background: linear-gradient(135deg, #0C4A6E 0%, #075985 40%, #0EA5E9 100%);
  border-radius: 18px;
  padding: 22px 28px;
  margin-bottom: 22px;
  margin-top: 50px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  flex-wrap: wrap;
  position: relative;
  overflow: hidden;
}
.bb-hero::before {
  content:'';
  position:absolute; top:-60px; right:-60px;
  width:220px; height:220px; border-radius:50%;
  background:rgba(255,255,255,.05);
}
.bb-hero::after {
  content:'';
  position:absolute; bottom:-70px; left:40px;
  width:160px; height:160px; border-radius:50%;
  background:rgba(14,165,233,.1);
}
.bb-hero-left { position:relative; z-index:1; }
.bb-hero h1 { color:#fff; font-size:1.3rem; font-weight:800; margin:0 0 3px; letter-spacing:-.01em; }
.bb-hero p  { color:rgba(255,255,255,.65); font-size:.78rem; margin:0; }
.bb-hero-actions { position:relative; z-index:1; display:flex; gap:8px; flex-wrap:wrap; }
.bb-btn-new {
  display:inline-flex; align-items:center; gap:7px;
  background:rgba(255,255,255,.92); color:#0C4A6E;
  border:none; border-radius:11px;
  padding:10px 20px; font-size:.82rem; font-weight:800;
  text-decoration:none; transition:.18s; white-space:nowrap;
}
.bb-btn-new:hover { background:#fff; color:#0C4A6E; transform:translateY(-1px); box-shadow:0 4px 14px rgba(0,0,0,.15); }
.bb-btn-ghost {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,255,255,.14); border:1.5px solid rgba(255,255,255,.25);
  color:#fff; border-radius:10px; padding:9px 16px;
  font-size:.78rem; font-weight:700; text-decoration:none; transition:.18s;
}
.bb-btn-ghost:hover { background:rgba(255,255,255,.24); color:#fff; }

/* ── KPI cards ────────────────────────── */
.bb-kpi { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:20px; }
@media(max-width:1100px){ .bb-kpi{grid-template-columns:repeat(3,1fr);} }
@media(max-width:640px)  { .bb-kpi{grid-template-columns:repeat(2,1fr);} }

.bb-kpi-card {
  background:var(--bb-card); border-radius:14px;
  border:1px solid var(--bb-border); box-shadow:var(--bb-shadow);
  padding:16px 18px; border-left:4px solid var(--kc, var(--bb-blue));
}
.bb-kpi-lbl { font-size:.65rem; font-weight:700; color:var(--bb-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
.bb-kpi-val { font-size:1.45rem; font-weight:900; color:var(--bb-text); line-height:1.1; }
.bb-kpi-sub { font-size:.68rem; color:var(--bb-muted); margin-top:3px; }

/* ── Filters bar ──────────────────────── */
.bb-filters {
  background:var(--bb-card); border:1px solid var(--bb-border);
  border-radius:14px; padding:14px 18px; margin-bottom:18px;
  box-shadow:var(--bb-shadow);
  display:flex; align-items:center; gap:10px; flex-wrap:wrap;
}
.bb-search-wrap { position:relative; flex:1; min-width:200px; }
.bb-search-wrap svg { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:15px; height:15px; stroke:#94A3B8; }
.bb-search { border:1.5px solid #BAE6FD !important; border-radius:10px !important; padding:8px 12px 8px 36px !important; font-size:.82rem !important; color:#0F172A !important; background:#F0F9FF !important; width:100%; }
.bb-search:focus { border-color:#0EA5E9 !important; outline:none !important; background:#fff !important; }
.bb-filter-sel { border:1.5px solid #BAE6FD !important; border-radius:10px !important; padding:8px 12px !important; font-size:.82rem !important; color:#0F172A !important; background:#F0F9FF !important; }
.bb-filter-sel:focus { border-color:#0EA5E9 !important; outline:none !important; }
.bb-filter-btn { background:var(--bb-navy); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:.8rem; font-weight:700; cursor:pointer; white-space:nowrap; transition:.18s; }
.bb-filter-btn:hover { background:var(--bb-dk); }
.bb-filter-clear { color:var(--bb-muted); font-size:.78rem; font-weight:600; text-decoration:none; padding:9px 12px; border-radius:10px; border:1.5px solid var(--bb-border); background:transparent; transition:.18s; }
.bb-filter-clear:hover { background:#F1F5F9; color:var(--bb-sub); }

/* ── Quick filter tabs ────────────────── */
.bb-tabs { display:flex; gap:6px; margin-bottom:16px; flex-wrap:wrap; }
.bb-tab {
  display:inline-flex; align-items:center; gap:5px;
  padding:7px 16px; border-radius:20px; font-size:.75rem; font-weight:700;
  border:1.5px solid var(--bb-border); background:#fff; color:var(--bb-sub);
  text-decoration:none; transition:.18s; cursor:pointer; white-space:nowrap;
}
.bb-tab:hover { border-color:#0EA5E9; color:#0EA5E9; background:#EFF6FF; }
.bb-tab.active { background:#0EA5E9; color:#fff; border-color:#0EA5E9; }
.bb-tab-count { background:rgba(255,255,255,.25); padding:1px 7px; border-radius:20px; font-size:.65rem; }
.bb-tab.active .bb-tab-count { background:rgba(255,255,255,.3); }

/* ── Table card ───────────────────────── */
.bb-table-card {
  background:var(--bb-card); border-radius:16px;
  border:1px solid var(--bb-border); box-shadow:var(--bb-shadow);
  overflow:hidden;
}
.bb-table-head {
  padding:14px 20px; border-bottom:1px solid #EFF6FF;
  display:flex; align-items:center; justify-content:space-between;
  background:#F0F9FF; flex-wrap:wrap; gap:10px;
}
.bb-table-title { font-size:.88rem; font-weight:700; color:var(--bb-text); }
.bb-table-meta  { font-size:.72rem; color:var(--bb-muted); }

table.bb-tbl { width:100%; border-collapse:collapse; }
.bb-tbl th {
  background:#F8FBFF; color:var(--bb-muted); font-size:.67rem;
  font-weight:700; text-transform:uppercase; letter-spacing:.05em;
  padding:10px 16px; text-align:left; border-bottom:1.5px solid #DBEAFE;
  white-space:nowrap;
}
.bb-tbl td {
  padding:12px 16px; border-bottom:1px solid #F0F9FF;
  font-size:.81rem; color:var(--bb-text); vertical-align:middle;
}
.bb-tbl tr:last-child td { border-bottom:none; }
.bb-tbl tr:hover td { background:#F8FBFF; }

/* ── Guest cell ───────────────────────── */
.bb-guest { display:flex; align-items:center; gap:10px; }
.bb-avatar {
  width:36px; height:36px; border-radius:50%; flex-shrink:0;
  background:linear-gradient(135deg, #0EA5E9, #0284C7);
  color:#fff; display:flex; align-items:center; justify-content:center;
  font-size:.82rem; font-weight:800;
}
.bb-guest-name { font-weight:600; color:var(--bb-text); font-size:.82rem; line-height:1.2; }
.bb-guest-phone { font-size:.7rem; color:var(--bb-muted); margin-top:1px; }

/* ── Booking ID badge ─────────────────── */
.bb-bid { font-size:.72rem; font-weight:800; color:#0369A1; background:#E0F2FE; padding:3px 9px; border-radius:6px; font-family:monospace; }

/* ── Boat chip ────────────────────────── */
.bb-boat-chip { display:inline-flex; align-items:center; gap:5px; background:#EFF6FF; border:1px solid #BAE6FD; border-radius:20px; padding:3px 10px; font-size:.7rem; font-weight:700; color:#0369A1; white-space:nowrap; }

/* ── Booking type ─────────────────────── */
.bb-btype { font-size:.7rem; font-weight:700; color:#0C4A6E; }
.bb-ghat  { font-size:.67rem; color:var(--bb-muted); margin-top:2px; }

/* ── PAX ──────────────────────────────── */
.bb-pax { display:flex; align-items:center; gap:6px; }
.bb-pax-circle { width:28px; height:28px; border-radius:50%; background:#EFF6FF; color:#0369A1; font-size:.72rem; font-weight:800; display:flex; align-items:center; justify-content:center; border:1.5px solid #BAE6FD; }

/* ── Amount ───────────────────────────── */
.bb-amt-main { font-weight:800; color:var(--bb-text); font-size:.85rem; }
.bb-amt-paid { font-size:.68rem; color:var(--bb-green); font-weight:600; }
.bb-amt-due  { font-size:.68rem; color:var(--bb-red); font-weight:600; }

/* ── Status badges ────────────────────── */
.bs {
  display:inline-flex; align-items:center; gap:4px;
  font-size:.63rem; font-weight:700; padding:3px 9px;
  border-radius:20px; text-transform:uppercase; letter-spacing:.03em;
  white-space:nowrap;
}
.bs-confirmed  { background:#DBEAFE; color:#1E40AF; }
.bs-completed  { background:#D1FAE5; color:#065F46; }
.bs-cancelled  { background:#FEE2E2; color:#991B1B; }
.bs-pending    { background:#FEF3C7; color:#92400E; }
.bs-paid       { background:#D1FAE5; color:#065F46; }
.bs-partial    { background:#DBEAFE; color:#1E40AF; }
.bs-unpaid     { background:#FEE2E2; color:#DC2626; }

/* ── Action buttons ───────────────────── */
.bb-actions { display:flex; gap:6px; align-items:center; }
.bb-act {
  width:30px; height:30px; border-radius:8px; display:flex;
  align-items:center; justify-content:center; border:none; cursor:pointer;
  transition:.18s; text-decoration:none; flex-shrink:0;
}
.bb-act-view  { background:#EFF6FF; color:#0369A1; }
.bb-act-edit  { background:#FEF3C7; color:#92400E; }
.bb-act-pay   { background:#D1FAE5; color:#065F46; }
.bb-act-del   { background:#FEE2E2; color:#991B1B; }
.bb-act:hover { filter:brightness(.9); transform:scale(1.08); }
.bb-act svg   { width:13px; height:13px; stroke:currentColor; }

/* ── Empty state ──────────────────────── */
.bb-empty { padding:60px 20px; text-align:center; }
.bb-empty-icon { font-size:3.5rem; margin-bottom:14px; opacity:.4; }
.bb-empty-title { font-size:1rem; font-weight:700; color:var(--bb-sub); margin-bottom:6px; }
.bb-empty-sub   { font-size:.8rem; color:var(--bb-muted); }

/* ── Pagination ───────────────────────── */
.bb-pagination { padding:16px 20px; border-top:1px solid #EFF6FF; }
.bb-pagination .pagination { margin:0; }

/* ── Mobile card layout ───────────────────── */
.bb-mob-cards { display:none; }
.bb-mob-card {
  background:var(--bb-card); border:1px solid var(--bb-border);
  border-radius:14px; margin-bottom:10px; overflow:hidden;
  box-shadow:var(--bb-shadow);
}
.bb-mob-card-top {
  padding:12px 14px; display:flex; align-items:center;
  gap:10px; border-bottom:1px solid #EFF6FF; background:#F8FBFF;
}
.bb-mob-card-body { padding:12px 14px; }
.bb-mob-row {
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:7px; font-size:.78rem;
}
.bb-mob-row:last-child { margin-bottom:0; }
.bb-mob-lbl { color:var(--bb-muted); font-weight:600; font-size:.68rem; text-transform:uppercase; letter-spacing:.04em; }
.bb-mob-val { color:var(--bb-text); font-weight:600; text-align:right; }
.bb-mob-card-foot {
  padding:9px 14px; border-top:1px solid #EFF6FF;
  display:flex; gap:7px; background:#F8FBFF;
}
.bb-mob-act {
  flex:1; display:flex; align-items:center; justify-content:center; gap:5px;
  padding:8px 4px; border-radius:9px; font-size:.72rem; font-weight:700;
  text-decoration:none; border:1.5px solid; transition:.18s;
}
.bb-mob-act svg { width:13px; height:13px; stroke:currentColor; flex-shrink:0; }
.bb-mob-act-view { background:#EFF6FF; color:#0369A1; border-color:#BAE6FD; }
.bb-mob-act-edit { background:#FEF3C7; color:#92400E; border-color:#FDE68A; }
.bb-mob-act-pay  { background:#D1FAE5; color:#065F46; border-color:#A7F3D0; }
.bb-mob-act-del  { background:#FEE2E2; color:#991B1B; border-color:#FECACA; }

/* ── Responsive ───────────────────────── */
@media(max-width:768px){
  .bb-page        { padding:10px 10px 70px; }
  .bb-hero        { padding:13px 16px; margin-bottom:12px; margin-top:58px; border-radius:14px; }
  .bb-hero h1     { font-size:.98rem; }
  .bb-hero p      { font-size:.7rem; }
  .bb-hero-actions{ gap:6px; }
  .bb-btn-new, .bb-btn-ghost { padding:7px 11px; font-size:.72rem; }

  .bb-kpi         { grid-template-columns:repeat(2,1fr); gap:10px; margin-bottom:14px; }
  .bb-kpi-card    { padding:12px 13px; }
  .bb-kpi-val     { font-size:1.15rem; }
  .bb-kpi-sub     { font-size:.6rem; }

  .bb-filters     { flex-direction:column; padding:12px; gap:8px; }
  .bb-search-wrap { min-width:0; width:100%; }
  .bb-filter-sel  { width:100%; box-sizing:border-box; }
  .bb-filter-btn  { width:100%; padding:10px; }
  .bb-filter-clear{ width:100%; text-align:center; display:block; }

  .bb-tabs        { flex-wrap:nowrap; overflow-x:auto; padding-bottom:4px; gap:5px; -webkit-overflow-scrolling:touch; }
  .bb-tabs::-webkit-scrollbar { height:3px; }
  .bb-tabs::-webkit-scrollbar-thumb { background:#BAE6FD; border-radius:3px; }
  .bb-tab         { font-size:.7rem; padding:6px 12px; white-space:nowrap; }

  /* Switch to card layout on mobile */
  .bb-desktop-table { display:none !important; }
  .bb-mob-cards     { display:block; padding:10px; }
  .bb-table-head    { padding:12px 14px; }

  .hide-mobile { display:none !important; }
}
@media(max-width:400px){
  .bb-kpi { grid-template-columns:1fr; }
  .bb-hero h1 { font-size:.9rem; }
}
</style>

<div class="bb-page">

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:12px;border:none;background:#D1FAE5;color:#065F46;font-size:.82rem;font-weight:600;">
  ✓ {{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;border:none;background:#FEE2E2;color:#991B1B;font-size:.82rem;font-weight:600;">
  ✗ {{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── HERO ──────────────────────────────────────────── --}}
<div class="bb-hero">
  <div class="bb-hero-left">
    <h1>⛵ Boat Bookings</h1>
    <p>Manage all Ganga boat rides · Ganga Aarti · Celebrations · Private Events</p>
  </div>
  <div class="bb-hero-actions">
    <a href="{{ route('boat-booking.create') }}" class="bb-btn-new">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New Boat Booking
    </a>
    <a href="{{ route('admin.dashboard') }}" class="bb-btn-ghost">← Dashboard</a>
  </div>
</div>

{{-- ── KPI CARDS ──────────────────────────────────────── --}}
@php
  $totalBookings = $boat_bookings->total();
  $dueAmount     = $total_final_amount - $total_payments;
@endphp
<div class="bb-kpi">
  <div class="bb-kpi-card" style="--kc:#0EA5E9;">
    <div class="bb-kpi-lbl">Total Bookings</div>
    <div class="bb-kpi-val">{{ number_format($totalBookings) }}</div>
    <div class="bb-kpi-sub">All time</div>
  </div>
  <div class="bb-kpi-card" style="--kc:#10B981;">
    <div class="bb-kpi-lbl">Total Revenue</div>
    <div class="bb-kpi-val">₹{{ number_format($total_final_amount) }}</div>
    <div class="bb-kpi-sub">After discounts</div>
  </div>
  <div class="bb-kpi-card" style="--kc:#4F46E5;">
    <div class="bb-kpi-lbl">Collected</div>
    <div class="bb-kpi-val">₹{{ number_format($total_payments) }}</div>
    <div class="bb-kpi-sub">Payments received</div>
  </div>
  <div class="bb-kpi-card" style="--kc:#EF4444;">
    <div class="bb-kpi-lbl">Balance Due</div>
    <div class="bb-kpi-val">₹{{ number_format(max(0, $dueAmount)) }}</div>
    <div class="bb-kpi-sub">Pending collection</div>
  </div>
  <div class="bb-kpi-card" style="--kc:#F59E0B;">
    <div class="bb-kpi-lbl">Total Bookings</div>
    <div class="bb-kpi-val">{{ number_format($totalBookings) }}</div>
    <div class="bb-kpi-sub">No of bookings</div>
  </div>
</div>

{{-- ── FILTERS ─────────────────────────────────────────── --}}
<form method="GET" action="{{ route('boat-booking.index') }}" id="filterForm">
<div class="bb-filters">
  {{-- Search --}}
  <div class="bb-search-wrap">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="search_user" value="{{ $search_user }}"
           class="bb-search" placeholder="Search name, phone, booking ID…">
  </div>

  {{-- Booking type --}}
  <select name="search_booking_type" class="bb-filter-sel">
    <option value="">All Types</option>
    <option value="Morning Boat Ride"                {{ request('search_booking_type')=='Morning Boat Ride'                ?'selected':'' }}>🌅 Morning Ride</option>
    <option value="Evening Boat Ride"                {{ request('search_booking_type')=='Evening Boat Ride'                ?'selected':'' }}>🌇 Evening Ride</option>
    <option value="Evening Boat Ride with Ganga Aarti" {{ request('search_booking_type')=='Evening Boat Ride with Ganga Aarti'?'selected':'' }}>🪔 Ganga Aarti</option>
  </select>

  {{-- Boat type --}}
  <select name="search_boat_type" class="bb-filter-sel">
    <option value="">All Boats</option>
    @foreach($boat_types as $bt)
    <option value="{{ $bt->id }}" {{ $search_boat_type == $bt->id ? 'selected':'' }}>{{ $bt->name }}</option>
    @endforeach
  </select>

  {{-- Payment status --}}
  <select name="search_payment_status" class="bb-filter-sel">
    <option value="">All Payments</option>
    <option value="paid"    {{ $search_payment_status=='paid'    ?'selected':'' }}>✅ Paid</option>
    <option value="partial" {{ $search_payment_status=='partial' ?'selected':'' }}>🔵 Partial</option>
    <option value="unpaid"  {{ $search_payment_status=='unpaid'  ?'selected':'' }}>🔴 Unpaid</option>
  </select>

  {{-- Date --}}
  <input type="date" name="search_date_from" value="{{ request('search_date_from') }}"
         class="bb-filter-sel" placeholder="From date" style="width:140px;">
  <input type="date" name="search_date_to" value="{{ request('search_date_to') }}"
         class="bb-filter-sel" placeholder="To date" style="width:140px;">

  <button type="submit" class="bb-filter-btn">Filter</button>
  @if($search_user || $search_boat_type || $search_payment_status || request('search_booking_type') || request('search_date_from'))
  <a href="{{ route('boat-booking.index') }}" class="bb-filter-clear">✕ Clear</a>
  @endif
</div>
</form>

{{-- ── QUICK TABS ───────────────────────────────────────── --}}
@php
  $allCount     = \App\Models\BoatBooking::count();
  $morningCount = \App\Models\BoatBooking::where('booking_type','Morning Boat Ride')->count();
  $eveningCount = \App\Models\BoatBooking::where('booking_type','Evening Boat Ride')->count();
  $aaartiCount  = \App\Models\BoatBooking::where('booking_type','Evening Boat Ride with Ganga Aarti')->count();
  $todayCount   = \App\Models\BoatBooking::whereDate('booking_date', today())->count();
@endphp
<div class="bb-tabs">
  <a href="{{ route('boat-booking.index') }}"
     class="bb-tab {{ !request('search_booking_type') && !request()->anyFilled(['search_boat_type','search_payment_status','search_user']) ? 'active' : '' }}">
    ⛵ All Bookings <span class="bb-tab-count">{{ $allCount }}</span>
  </a>
  <a href="{{ route('boat-booking.index') }}?search_booking_type=Morning+Boat+Ride"
     class="bb-tab {{ request('search_booking_type')=='Morning Boat Ride' ? 'active':'' }}">
    🌅 Morning <span class="bb-tab-count">{{ $morningCount }}</span>
  </a>
  <a href="{{ route('boat-booking.index') }}?search_booking_type=Evening+Boat+Ride"
     class="bb-tab {{ request('search_booking_type')=='Evening Boat Ride' ? 'active':'' }}">
    🌇 Evening <span class="bb-tab-count">{{ $eveningCount }}</span>
  </a>
  <a href="{{ route('boat-booking.index') }}?search_booking_type=Evening+Boat+Ride+with+Ganga+Aarti"
     class="bb-tab {{ request('search_booking_type')=='Evening Boat Ride with Ganga Aarti' ? 'active':'' }}">
    🪔 Ganga Aarti <span class="bb-tab-count">{{ $aaartiCount }}</span>
  </a>
  <a href="{{ route('boat-booking.index') }}?search_date_from={{ today()->format('Y-m-d') }}&search_date_to={{ today()->format('Y-m-d') }}"
     class="bb-tab">
    📅 Today <span class="bb-tab-count">{{ $todayCount }}</span>
  </a>
  <a href="{{ route('boat-booking.index') }}?search_payment_status=unpaid"
     class="bb-tab {{ request('search_payment_status')=='unpaid' ? 'active':'' }}" style="color:#EF4444;">
    ⚠ Due Payment <span class="bb-tab-count">{{ \App\Models\BoatBooking::where('payment_status','unpaid')->count() }}</span>
  </a>
</div>

{{-- ── BOOKINGS TABLE ───────────────────────────────────── --}}
<div class="bb-table-card">
  <div class="bb-table-head">
    <div class="bb-table-title">Boat Bookings</div>
    <div class="bb-table-meta">
      Showing {{ $boat_bookings->firstItem() ?? 0 }}–{{ $boat_bookings->lastItem() ?? 0 }}
      of {{ $boat_bookings->total() }} bookings
    </div>
  </div>

  {{-- ── Desktop Table ── --}}
  <div class="bb-desktop-table" style="overflow-x:auto;">
    <table class="bb-tbl">
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Guest</th>
          <th>Boat</th>
          <th>Type / Ghat</th>
          <th class="hide-mobile">Date</th>
          <th>PAX</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($boat_bookings as $bk)
        @php
          $paidAmt = (float)($bk->payments_sum_amount ?? 0);
          $dueAmt  = max(0, (float)$bk->final_amount - $paidAmt);
          $payStatus = $bk->payment_status ?? ($dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'unpaid'));
          $bkStatus  = $bk->booking_status ?? 'confirmed';
          $initials  = strtoupper(substr($bk->name ?? 'G', 0, 1));
          $boatName  = $bk->boat?->boatType?->name ?? 'Boat';

          $boatEmojis = ['Normal Motor Boat'=>'🚤','Light Motor Boat'=>'⛵','Premium Light Motor Boat'=>'⚡','Luxury Mini Yacht'=>'🛥','Bajra Boat'=>'🛶','Cruise'=>'🚢'];
          $boatEmoji  = $boatEmojis[$boatName] ?? '⛵';
        @endphp
        <tr>
          {{-- Booking ID --}}
          <td>
            <span class="bb-bid">{{ $bk->booking_id }}</span>
            <div style="font-size:.62rem;color:var(--bb-muted);margin-top:3px;">
              {{ $bk->created_at ? \Carbon\Carbon::parse($bk->created_at)->format('d M') : '' }}
            </div>
          </td>

          {{-- Guest --}}
          <td>
            <div class="bb-guest">
              <div class="bb-avatar">{{ $initials }}</div>
              <div>
                <div class="bb-guest-name">{{ $bk->name }}</div>
                <div class="bb-guest-phone">📞 {{ $bk->phone }}</div>
              </div>
            </div>
          </td>

          {{-- Boat --}}
          <td>
            <span class="bb-boat-chip">{{ $boatEmoji }} {{ $boatName }}</span>
          </td>

          {{-- Booking Type / Ghat --}}
          <td>
            <div class="bb-btype">{{ $bk->booking_type ?? '—' }}</div>
            @if($bk->boarding_ghat)
            <div class="bb-ghat">📍 {{ $bk->boarding_ghat }}</div>
            @endif
            @if($bk->pickup_time)
            <div class="bb-ghat">🕐 {{ \Carbon\Carbon::parse($bk->pickup_time)->format('h:i A') }}</div>
            @endif
          </td>

          {{-- Date --}}
          <td class="hide-mobile">
            <div style="font-weight:600;color:var(--bb-text);font-size:.8rem;">
              {{ $bk->booking_date ? \Carbon\Carbon::parse($bk->booking_date)->format('d M Y') : '—' }}
            </div>
          </td>

          {{-- PAX --}}
          <td>
            <div class="bb-pax">
              <div class="bb-pax-circle">{{ $bk->no_of_person }}</div>
              <div>
                <div style="font-size:.68rem;color:var(--bb-muted);">{{ $bk->adults ?? $bk->no_of_person }} adults</div>
                @if(($bk->children ?? 0) > 0)
                <div style="font-size:.62rem;color:#059669;">{{ $bk->children }} child free</div>
                @endif
              </div>
            </div>
          </td>

          {{-- Amount --}}
          <td>
            <div class="bb-amt-main">₹{{ number_format($bk->final_amount) }}</div>
            <div class="bb-amt-paid">✓ ₹{{ number_format($paidAmt) }}</div>
            @if($dueAmt > 0)
            <div class="bb-amt-due">⚠ ₹{{ number_format($dueAmt) }} due</div>
            @endif
          </td>

          {{-- Status --}}
          <td>
            <div style="display:flex;flex-direction:column;gap:4px;">
              <span class="bs bs-{{ $bkStatus }}">{{ ucfirst($bkStatus) }}</span>
              <span class="bs bs-{{ $payStatus }}">{{ ucfirst($payStatus) }}</span>
            </div>
          </td>

          {{-- Actions --}}
          <td>
            <div class="bb-actions">
              <a href="{{ route('boat-booking.show', $bk->booking_id) }}"
                 class="bb-act bb-act-view" title="View">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('boat-booking.edit', $bk->booking_id) }}"
                 class="bb-act bb-act-edit" title="Edit">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="{{ route('boat-booking.payment', $bk->booking_id) }}"
                 class="bb-act bb-act-pay" title="Add Payment">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
              </a>
              <form action="{{ route('boat-booking.destroy', $bk->booking_id) }}" method="POST"
                    onsubmit="return confirm('Delete this booking?')" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="bb-act bb-act-del" title="Delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9">
            <div class="bb-empty">
              <div class="bb-empty-icon">⛵</div>
              <div class="bb-empty-title">No boat bookings found</div>
              <div class="bb-empty-sub">
                @if($search_user || $search_boat_type || $search_payment_status)
                  Try adjusting your filters or
                  <a href="{{ route('boat-booking.index') }}" style="color:#0EA5E9;">clear all filters</a>
                @else
                  Get started by creating your first boat booking
                @endif
              </div>
              <a href="{{ route('boat-booking.create') }}"
                 style="display:inline-flex;align-items:center;gap:6px;margin-top:14px;background:#0EA5E9;color:#fff;border-radius:10px;padding:10px 22px;font-size:.82rem;font-weight:700;text-decoration:none;">
                ＋ New Boat Booking
              </a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- ── Mobile Cards ── --}}
  <div class="bb-mob-cards">
    @forelse($boat_bookings as $bk)
    @php
      $paidAmt   = (float)($bk->payments_sum_amount ?? 0);
      $dueAmt    = max(0, (float)$bk->final_amount - $paidAmt);
      $payStatus = $bk->payment_status ?? ($dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'unpaid'));
      $bkStatus  = $bk->booking_status ?? 'confirmed';
      $initials  = strtoupper(substr($bk->name ?? 'G', 0, 1));
      $boatName  = $bk->boat?->boatType?->name ?? 'Boat';
      $boatEmojis= ['Normal Motor Boat'=>'🚤','Light Motor Boat'=>'⛵','Premium Light Motor Boat'=>'⚡','Luxury Mini Yacht'=>'🛥','Bajra Boat'=>'🛶','Cruise'=>'🚢'];
      $boatEmoji = $boatEmojis[$boatName] ?? '⛵';
    @endphp
    <div class="bb-mob-card">
      {{-- Card Top: ID + Guest + Status --}}
      <div class="bb-mob-card-top">
        <div class="bb-avatar" style="flex-shrink:0;">{{ $initials }}</div>
        <div style="flex:1;min-width:0;">
          <div style="font-weight:700;color:var(--bb-text);font-size:.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $bk->name }}</div>
          <div style="font-size:.7rem;color:var(--bb-muted);margin-top:1px;">📞 {{ $bk->phone }}</div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <span class="bb-bid" style="display:block;margin-bottom:4px;">{{ $bk->booking_id }}</span>
          <span class="bs bs-{{ $payStatus }}">{{ ucfirst($payStatus) }}</span>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="bb-mob-card-body">
        <div class="bb-mob-row">
          <span class="bb-mob-lbl">Boat</span>
          <span class="bb-mob-val"><span class="bb-boat-chip">{{ $boatEmoji }} {{ $boatName }}</span></span>
        </div>
        <div class="bb-mob-row">
          <span class="bb-mob-lbl">Booking Type</span>
          <span class="bb-mob-val" style="font-size:.76rem;">{{ $bk->booking_type ?? '—' }}</span>
        </div>
        <div class="bb-mob-row">
          <span class="bb-mob-lbl">Boarding Ghat</span>
          <span class="bb-mob-val">{{ $bk->boarding_ghat ?? '—' }}</span>
        </div>
        <div class="bb-mob-row">
          <span class="bb-mob-lbl">Date & Time</span>
          <span class="bb-mob-val">
            {{ $bk->booking_date ? \Carbon\Carbon::parse($bk->booking_date)->format('d M Y') : '—' }}
            @if($bk->pickup_time) · {{ \Carbon\Carbon::parse($bk->pickup_time)->format('h:i A') }} @endif
          </span>
        </div>
        <div class="bb-mob-row">
          <span class="bb-mob-lbl">Guests</span>
          <span class="bb-mob-val">
            {{ $bk->no_of_person }} persons &middot;
            {{ $bk->adults ?? $bk->no_of_person }} Adults
            {{ ($bk->children ?? 0) > 0 ? '· ' . $bk->children . ' Child Free' : '' }}
          </span>
        </div>
        <div class="bb-mob-row" style="border-top:1px dashed #EFF6FF;padding-top:8px;margin-top:4px;">
          <span class="bb-mob-lbl">Total Amount</span>
          <span class="bb-mob-val" style="font-size:.88rem;font-weight:800;">₹{{ number_format($bk->final_amount) }}</span>
        </div>
        <div class="bb-mob-row">
          <span class="bb-mob-lbl">Amount Paid</span>
          <span class="bb-mob-val" style="color:#059669;font-weight:700;">✓ ₹{{ number_format($paidAmt) }}</span>
        </div>
        @if($dueAmt > 0)
        <div class="bb-mob-row">
          <span class="bb-mob-lbl">Balance Due</span>
          <span class="bb-mob-val" style="color:#EF4444;font-weight:700;">⚠ ₹{{ number_format($dueAmt) }}</span>
        </div>
        @endif
      </div>

      {{-- Card Footer: Actions --}}
      <div class="bb-mob-card-foot">
        <a href="{{ route('boat-booking.show', $bk->booking_id) }}" class="bb-mob-act bb-mob-act-view">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>View
        </a>
        <a href="{{ route('boat-booking.edit', $bk->booking_id) }}" class="bb-mob-act bb-mob-act-edit">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit
        </a>
        <a href="{{ route('boat-booking.payment', $bk->booking_id) }}" class="bb-mob-act bb-mob-act-pay">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>Pay
        </a>
        <a href="{{ route('boat-booking.voucher', $bk->booking_id) }}" class="bb-mob-act" style="background:#F5F3FF;color:#7C3AED;border-color:#DDD6FE;">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>PDF
        </a>
      </div>
    </div>
    @empty
    <div class="bb-empty">
      <div class="bb-empty-icon">⛵</div>
      <div class="bb-empty-title">No boat bookings found</div>
      <div class="bb-empty-sub">
        @if($search_user || $search_boat_type || $search_payment_status)
          <a href="{{ route('boat-booking.index') }}" style="color:#0EA5E9;">Clear filters</a>
        @else
          Get started by <a href="{{ route('boat-booking.create') }}" style="color:#0EA5E9;">creating a booking</a>
        @endif
      </div>
    </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if($boat_bookings->hasPages())
  <div class="bb-pagination">
    {{ $boat_bookings->appends(request()->query())->links() }}
  </div>
  @endif
</div>

</div>{{-- /bb-page --}}

@endsection
