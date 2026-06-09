@extends('admin.layouts.app')
@section('content')

<style>
/* ── Page ─────────────────────────────────────── */
.sb-page { background:#F1F5F9; min-height:100vh; padding:20px 24px 48px; }

/* ── Header ───────────────────────────────────── */
.sb-header {
  background: linear-gradient(135deg,#0F766E 0%,#0D9488 50%,#14B8A6 100%);
  border-radius:20px; padding:24px 30px; margin-top:50px; margin-bottom:24px;
  display:flex; align-items:center; justify-content:space-between; gap:16px;
  position:relative; overflow:hidden; box-shadow:0 8px 32px rgba(15,118,110,.25);
}
.sb-header::before { content:''; position:absolute; top:-60px; right:-60px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.07); }
.sb-header::after  { content:''; position:absolute; bottom:-40px; left:100px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.05); }
.sb-header-left { position:relative; z-index:1; }
.sb-header h1 { color:#fff; font-size:1.35rem; font-weight:800; margin:0 0 5px; letter-spacing:-.02em; }
.sb-header p  { color:rgba(255,255,255,.75); font-size:.8rem; margin:0; }
.sb-header-actions { position:relative; z-index:1; display:flex; gap:8px; }
.sb-btn-ghost { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3); color:#fff; border-radius:10px; padding:8px 16px; font-size:.78rem; font-weight:700; text-decoration:none; transition:.18s; }
.sb-btn-ghost:hover { background:rgba(255,255,255,.28); color:#fff; }

/* ── Layout ───────────────────────────────────── */
.sb-layout { display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start; }
@media(max-width:1100px){ .sb-layout { grid-template-columns:1fr; } }

/* ── Card ─────────────────────────────────────── */
.sb-card { background:#fff; border-radius:18px; border:1px solid #E8EDF4; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:18px; overflow:hidden; }
.sb-card-head { padding:14px 22px; border-bottom:1.5px solid #F1F5F9; display:flex; align-items:center; gap:10px; background:#FAFBFF; }
.sb-card-icon { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1rem; }
.sb-card-title { font-size:.9rem; font-weight:800; color:#0F172A; }
.sb-card-body { padding:22px; }
.sb-req { color:#EF4444; }
.sb-label { font-size:.7rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; display:block; }
.sb-input { border:1.5px solid #E2E8F0 !important; border-radius:11px !important; padding:10px 14px !important; font-size:.875rem !important; color:#0F172A !important; background:#FAFBFF !important; transition:all .2s !important; width:100%; }
.sb-input:focus { border-color:#0D9488 !important; box-shadow:0 0 0 3px rgba(13,148,136,.12) !important; background:#fff !important; outline:none !important; }

/* ── Hotel grid ───────────────────────────────── */
.hotel-list { display:grid; grid-template-columns:repeat(auto-fill,minmax(155px,1fr)); gap:12px; }
.hotel-row {
  display:flex; flex-direction:column; align-items:center; text-align:center;
  border:2px solid #E8EDF4; border-radius:16px; padding:18px 12px 14px;
  cursor:pointer; background:#fff; transition:all .22s; position:relative;
  user-select:none;
}
.hotel-row:hover { border-color:#0D9488; background:#F0FDFA; box-shadow:0 6px 20px rgba(13,148,136,.15); transform:translateY(-3px); }
.hotel-row.selected { border-color:#0D9488; background:#F0FDFA; box-shadow:0 0 0 3px rgba(13,148,136,.2); }
.hotel-row-icon {
  width:52px; height:52px; border-radius:14px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  font-size:1.6rem; background:#F0FDFA; margin-bottom:10px;
}
.hotel-row-info { width:100%; }
.hotel-row-name { font-size:.78rem; font-weight:700; color:#0F172A; margin-bottom:5px; line-height:1.35; }
.hotel-row-meta { display:flex; align-items:center; justify-content:center; gap:5px; margin-bottom:6px; flex-wrap:wrap; }
.hotel-row-loc { font-size:.66rem; color:#64748B; font-weight:600; }
.hotel-row-stars { color:#F59E0B; font-size:.65rem; }
.hotel-row-price { text-align:center; margin-top:4px; }
.hotel-row-amt { font-size:1rem; font-weight:800; color:#0D9488; display:block; }
.hotel-row-per { font-size:.62rem; color:#94A3B8; font-weight:600; }
.hotel-row-chk {
  position:absolute; top:8px; right:8px;
  width:20px; height:20px; border-radius:50%; background:#0D9488;
  color:#fff; font-size:.6rem; font-weight:800;
  display:none; align-items:center; justify-content:center;
}
.hotel-row.selected .hotel-row-chk { display:flex; }

/* Custom hotel card */
.hotel-row-custom { border-style:dashed; border-color:#A5B4FC; }
.hotel-row-custom:hover, .hotel-row-custom.selected { border-color:#6366F1; background:#EEF2FF; box-shadow:0 0 0 3px rgba(99,102,241,.15); }
.hotel-row-custom .hotel-row-chk { background:#6366F1; }
.hotel-row-custom .hotel-row-icon { background:#EEF2FF; }

/* Custom form */
.custom-hotel-wrap {
  margin-top:14px; background:linear-gradient(135deg,#F5F3FF,#EEF2FF);
  border:1.5px solid #C4B5FD; border-radius:16px; padding:18px 20px;
  animation:fadeIn .2s ease;
}
@keyframes fadeIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

/* ── Date & time ──────────────────────────────── */
.date-card {
  border:2px solid #E2E8F0; border-radius:14px; padding:14px 16px;
  background:#FAFBFF; transition:border-color .2s;
}
.date-card.filled { border-color:#0D9488; background:#F0FDFA; }
.date-card-lbl { font-size:.65rem; font-weight:800; color:#64748B; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
.date-card input[type=date], .date-card input[type=time] {
  border:none !important; background:transparent !important;
  box-shadow:none !important; padding:0 !important;
  font-size:.92rem; font-weight:700; color:#0F172A; width:100%;
}
.date-card input[type=date]:focus, .date-card input[type=time]:focus { outline:none !important; }
.date-arrow {
  display:flex; align-items:center; justify-content:center;
  font-size:1.4rem; color:#CBD5E1; flex-shrink:0; padding:0 4px;
}

/* ── Nights badge ─────────────────────────────── */
.nights-badge {
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  min-width:72px; background:linear-gradient(135deg,#0F766E,#0D9488);
  border-radius:14px; padding:10px 14px; color:#fff;
}
.nights-badge-num { font-size:1.5rem; font-weight:900; line-height:1; }
.nights-badge-lbl { font-size:.58rem; font-weight:700; opacity:.8; text-transform:uppercase; letter-spacing:.08em; }

/* ── Counter ──────────────────────────────────── */
.counter-wrap { display:flex; align-items:center; justify-content:space-between; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:11px; padding:7px 10px; }
.counter-btn { width:30px; height:30px; border-radius:8px; border:1.5px solid #CBD5E1; background:#fff; font-size:1.1rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:.15s; }
.counter-btn:hover { border-color:#0D9488; color:#0D9488; background:#F0FDFA; }
.counter-val { font-size:1.1rem; font-weight:800; min-width:28px; text-align:center; color:#0F172A; }

/* ── Meal pills ───────────────────────────────── */
.meal-pills { display:flex; gap:8px; flex-wrap:wrap; margin-top:4px; }
.meal-pill {
  border:2px solid #E2E8F0; border-radius:9px; padding:7px 14px;
  font-size:.74rem; font-weight:700; cursor:pointer; background:#fff;
  transition:.18s; color:#475569; display:flex; flex-direction:column; align-items:center; gap:2px;
}
.meal-pill:hover { border-color:#0D9488; color:#0D9488; background:#F0FDFA; }
.meal-pill.active { border-color:#0D9488; background:#0D9488; color:#fff; }
.meal-pill-code { font-size:.65rem; opacity:.75; }
.meal-pill input { display:none; }

/* ── Sidebar ──────────────────────────────────── */
.sb-sidebar { position:sticky; top:20px; }
.sb-sidebar-card { background:#fff; border-radius:18px; border:1px solid #E8EDF4; box-shadow:0 2px 8px rgba(0,0,0,.06); padding:22px; margin-bottom:16px; }

/* ── Summary box ──────────────────────────────── */
.sb-hotel-summary {
  background:linear-gradient(135deg,#F0FDFA,#CCFBF1);
  border:1.5px solid #99F6E4; border-radius:14px; padding:14px 16px;
  margin-bottom:16px;
}
.sb-hotel-summary-lbl { font-size:.63rem; font-weight:800; color:#0F766E; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
.sb-hotel-summary-name { font-size:.92rem; font-weight:800; color:#0F172A; margin-bottom:3px; }
.sb-hotel-summary-dates { font-size:.75rem; color:#0F766E; font-weight:600; }

/* ── Amount rows ──────────────────────────────── */
.sb-amt-row { margin-bottom:14px; }
.sb-rupee-wrap {
  display:flex; align-items:stretch;
  border:1.5px solid #E2E8F0; border-radius:11px; overflow:hidden; background:#FAFBFF;
  transition:border-color .2s, box-shadow .2s;
}
.sb-rupee-wrap:focus-within { border-color:#0D9488; box-shadow:0 0 0 3px rgba(13,148,136,.12); }
.sb-rupee {
  display:flex; align-items:center; padding:0 10px 0 12px;
  font-size:.85rem; font-weight:700; color:#475569;
  background:#F1F5F9; border-right:1.5px solid #E2E8F0;
  white-space:nowrap; flex-shrink:0; pointer-events:none;
}
.sb-rupee-input {
  flex:1; min-width:0;
  border:none !important; border-radius:0 !important;
  box-shadow:none !important; outline:none !important;
  padding:9px 12px !important; background:transparent !important;
}

/* ── Pay summary ──────────────────────────────── */
.pay-box { background:#0F172A; border-radius:14px; overflow:hidden; margin:14px 0; }
.pay-box-head { padding:10px 16px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,.1); }
.pay-box-title { font-size:.72rem; font-weight:800; color:rgba(255,255,255,.7); text-transform:uppercase; letter-spacing:.08em; }
.pay-badge { font-size:.6rem; font-weight:800; padding:3px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:.04em; }
.pay-badge.paid    { background:#D1FAE5; color:#065F46; }
.pay-badge.partial { background:#DBEAFE; color:#1E40AF; }
.pay-badge.due     { background:#FEE2E2; color:#DC2626; }
.pay-box-body { padding:12px 16px; }
.pay-row { display:flex; justify-content:space-between; align-items:center; padding:6px 0; font-size:.8rem; }
.pay-row + .pay-row { border-top:1px solid rgba(255,255,255,.07); }
.pay-row-lbl { color:rgba(255,255,255,.55); }
.pay-row-val { font-weight:700; color:#fff; }
.pay-row-bal .pay-row-lbl { color:rgba(255,255,255,.8); font-weight:700; }
.pay-row-bal .pay-row-val { color:#F87171; font-size:.92rem; }
.pay-progress-wrap { padding:10px 16px 14px; border-top:1px solid rgba(255,255,255,.07); }
.pay-progress-info { display:flex; justify-content:space-between; font-size:.65rem; color:rgba(255,255,255,.4); margin-bottom:5px; }
.pay-progress-bar { height:5px; background:rgba(255,255,255,.12); border-radius:99px; overflow:hidden; }
.pay-progress-fill { height:100%; background:linear-gradient(90deg,#0D9488,#14B8A6); border-radius:99px; transition:width .4s; }

/* ── Submit ───────────────────────────────────── */
.sb-submit {
  display:flex; align-items:center; justify-content:center; gap:9px;
  background:linear-gradient(135deg,#0F766E,#0D9488); color:#fff;
  border:none; border-radius:13px; padding:15px; font-size:.95rem;
  font-weight:800; cursor:pointer; width:100%; margin-top:4px;
  transition:all .2s; box-shadow:0 4px 16px rgba(13,148,136,.3);
  letter-spacing:-.01em;
}
.sb-submit:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(13,148,136,.4); }
.sb-submit svg { width:18px; height:18px; }

/* ── Plan preview ─────────────────────────────── */
.plan-preview {
  background:linear-gradient(135deg,#F0FDFA,#CCFBF1);
  border:1.5px solid #99F6E4; border-radius:12px; padding:13px 16px; margin-top:16px;
}
.plan-preview-lbl { font-size:.62rem; font-weight:800; color:#0F766E; text-transform:uppercase; letter-spacing:.06em; margin-bottom:5px; }
.plan-preview-text { font-size:.8rem; color:#134E4A; font-weight:600; line-height:1.7; }

/* ── Mobile sticky bar ─────────────────────────── */
.sb-mob-bar {
  display:none;
  position:fixed; bottom:58px; left:0; right:0; z-index:999;
  background:#fff; border-top:2px solid #E2E8F0;
  padding:10px 16px; box-shadow:0 -4px 20px rgba(0,0,0,.12);
  align-items:center; gap:12px;
}
.sb-mob-bar-info { flex:1; min-width:0; }
.sb-mob-bar-lbl { font-size:.62rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.06em; }
.sb-mob-bar-amt { font-size:1.15rem; font-weight:900; color:#0F766E; line-height:1.2; }
.sb-mob-bar-sub { font-size:.65rem; color:#94A3B8; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sb-mob-submit {
  display:flex; align-items:center; gap:6px;
  background:linear-gradient(135deg,#0F766E,#0D9488); color:#fff;
  border:none; border-radius:12px; padding:10px 20px;
  font-size:.85rem; font-weight:800; cursor:pointer; flex-shrink:0;
  box-shadow:0 4px 14px rgba(13,148,136,.35); letter-spacing:-.01em;
}
.sb-mob-submit svg { width:16px; height:16px; }

@media(max-width:1100px){
  .sb-mob-bar { display:flex; }
  .sb-sidebar .sb-submit { display:none; }
}

@media(max-width:768px){
  .sb-page { padding:12px 12px 130px; }
  .sb-header { flex-direction:column; align-items:flex-start; gap:12px; margin-top:56px; }

  /* Hotel cards → single-column compact list */
  .hotel-list { grid-template-columns:1fr !important; gap:8px !important; }
  .hotel-row {
    flex-direction:row !important; text-align:left !important;
    padding:10px 14px !important; gap:12px; align-items:center;
  }
  .hotel-row:hover, .hotel-row.selected { transform:none !important; }
  .hotel-row-icon {
    width:40px !important; height:40px !important;
    font-size:1.2rem !important; margin-bottom:0 !important;
    border-radius:10px !important; flex-shrink:0;
  }
  .hotel-row-info { flex:1; min-width:0; display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
  .hotel-row-name { font-size:.8rem; margin-bottom:0; }
  .hotel-row-meta { margin-bottom:0; }
  .hotel-row-price { margin-top:0; margin-left:auto; text-align:right; flex-shrink:0; }
  .hotel-row-amt { font-size:.85rem; }

  /* Check-in/out → checkin+nights on row1, checkout full-width on row2 */
  .sb-dates-row { flex-wrap:wrap !important; gap:8px !important; }
  .sb-dates-row > div:first-child { flex:1 0 140px !important; }
  .sb-dates-row > .nights-badge { flex-shrink:0 !important; align-self:center; }
  .sb-dates-row > div:last-child { flex:0 0 100% !important; }

  /* Date card inputs a bit smaller */
  .date-card input[type=date], .date-card input[type=time] { font-size:.82rem !important; }

  /* Meal pills full-width on mobile */
  .meal-pills { gap:6px; }
  .meal-pill { flex:1; justify-content:center; padding:7px 8px; }
}
</style>

<div class="sb-page">

  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;">
    <strong>Please fix:</strong>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;">
    {{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Header --}}
  <div class="sb-header">
    <div class="sb-header-left">
      <h1>🏨 New Stay / Hotel Booking</h1>
      <p>Hotels &middot; Homestays &middot; Heritage &middot; Guesthouses</p>
    </div>
    <div class="sb-header-actions">
      <a href="{{ route('bookings.create-direct') }}" class="sb-btn-ghost">← Change Type</a>
      <a href="{{ route('bookings.index') }}" class="sb-btn-ghost">All Bookings</a>
    </div>
  </div>

  <form action="{{ route('bookings.store-direct') }}" method="POST" id="stayForm">
    @csrf
    <input type="hidden" name="short_plan" id="shortPlanHidden">
    <input type="hidden" name="booking_date" id="bookingDateHidden" value="{{ now()->format('Y-m-d') }}">

    <div class="sb-layout">

      {{-- ══════════ LEFT ══════════ --}}
      <div>

        {{-- 1. Guest Details --}}
        <div class="sb-card">
          <div class="sb-card-head">
            <div class="sb-card-icon" style="background:#CCFBF1;">👤</div>
            <div class="sb-card-title">Guest Details</div>
          </div>
          <div class="sb-card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="sb-label">Guest Name <span class="sb-req">*</span></label>
                <input type="text" name="guest_name" class="form-control sb-input" required
                       placeholder="Full name" value="{{ old('guest_name') }}" oninput="buildPlan()">
              </div>
              <div class="col-md-4">
                <label class="sb-label">Mobile <span class="sb-req">*</span></label>
                @include('admin.partials.phone-input', [
                    'name'     => 'phone',
                    'value'    => old('phone'),
                    'required' => true,
                    'placeholder' => '9876543210',
                ])
              </div>
              <div class="col-md-4">
                <label class="sb-label">Alt. Mobile</label>
                @include('admin.partials.phone-input', [
                    'name'        => 'alt_phone',
                    'codeName'    => 'alt_phone_country_code',
                    'value'       => old('alt_phone'),
                    'required'    => false,
                    'placeholder' => 'Optional',
                ])
              </div>
              <div class="col-md-4">
                <label class="sb-label">Email</label>
                <input type="email" name="email" class="form-control sb-input"
                       placeholder="guest@email.com" value="{{ old('email') }}">
              </div>
              <div class="col-md-4">
                <label class="sb-label">Country</label>
                <select name="country" class="form-select sb-input">
                  @foreach(['India'=>'🇮🇳 India','USA'=>'🇺🇸 USA','UK'=>'🇬🇧 UK','UAE'=>'🇦🇪 UAE','Australia'=>'🇦🇺 Australia','Canada'=>'🇨🇦 Canada','Singapore'=>'🇸🇬 Singapore','Other'=>'🌍 Other'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('country','India')===$v ? 'selected':'' }}>{{ $l }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="sb-label">Lead Source <span class="sb-req">*</span></label>
                <select name="lead_source_id" class="form-select sb-input" required>
                  <option value="">Select source…</option>
                  @foreach($leadSources as $src)
                    <option value="{{ $src->id }}" {{ old('lead_source_id')==$src->id ? 'selected':'' }}>{{ $src->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        {{-- 2. Property / Hotel --}}
        <div class="sb-card">
          <div class="sb-card-head">
            <div class="sb-card-icon" style="background:#CCFBF1;">🏨</div>
            <div class="sb-card-title">
              Select Property / Hotel
              <span style="font-size:.68rem;color:#EF4444;font-weight:600;margin-left:6px;">* required</span>
            </div>
          </div>
          <div class="sb-card-body">
            <div class="hotel-list" id="hotelList">

              @php
                $hotelMeta = [
                  '4 star' => ['⭐⭐⭐⭐', '#F59E0B'],
                  '3 star' => ['⭐⭐⭐',   '#F59E0B'],
                  'paradise'=> ['🌿',       '#059669'],
                  'namastubhyam'=> ['🛕',   '#DC2626'],
                  'heritage'=> ['🏛️',       '#7C3AED'],
                  'homestay'=> ['🏡',       '#0891B2'],
                ];
                function getHotelIcon($name) {
                  $n = strtolower($name);
                  if (str_contains($n,'4 star')) return ['🏨','#0D9488'];
                  if (str_contains($n,'3 star')) return ['🏩','#0891B2'];
                  if (str_contains($n,'paradise')) return ['🌿','#059669'];
                  if (str_contains($n,'heritage')) return ['🏛️','#7C3AED'];
                  if (str_contains($n,'homestay')) return ['🏡','#0891B2'];
                  if (str_contains($n,'namastubhyam')) return ['🛕','#DC2626'];
                  return ['🏨','#64748B'];
                }
                function getHotelLocation($name) {
                  $n = strtolower($name);
                  if (str_contains($n,'varanasi') || str_contains($n,'kashi') || str_contains($n,'saket') || str_contains($n,'mahmoorganj') || str_contains($n,'ganga')) return 'Varanasi';
                  if (str_contains($n,'ayodh')) return 'Ayodhya';
                  if (str_contains($n,'lucknow')) return 'Lucknow';
                  if (str_contains($n,'prayagraj')) return 'Prayagraj';
                  return 'Property';
                }
                function getStars($name) {
                  $n = strtolower($name);
                  if (str_contains($n,'4 star')) return '★★★★';
                  if (str_contains($n,'3 star')) return '★★★';
                  return '';
                }
              @endphp

              @forelse($stayTemplates as $t)
              @php [$icon,$color] = getHotelIcon($t->name); @endphp
              <div class="hotel-row" data-id="{{ $t->id }}" data-name="{{ $t->name }}"
                   data-price="{{ (float)$t->default_selling_price }}" onclick="selectHotel(this)">
                <div class="hotel-row-chk">✓</div>
                <div class="hotel-row-icon" style="background:{{ $color }}18;">
                  <span>{{ $icon }}</span>
                </div>
                <div class="hotel-row-info">
                  <div class="hotel-row-name">{{ $t->name }}</div>
                  <div class="hotel-row-meta">
                    <span class="hotel-row-loc">📍 {{ getHotelLocation($t->name) }}</span>
                    @if(getStars($t->name))
                    <span class="hotel-row-stars">{{ getStars($t->name) }}</span>
                    @endif
                  </div>
                  <div class="hotel-row-price">
                    <span class="hotel-row-amt">₹{{ number_format((float)$t->default_selling_price,0) }}</span>
                    <span class="hotel-row-per">/night</span>
                  </div>
                </div>
              </div>
              @empty
              <div style="grid-column:1/-1;text-align:center;padding:30px;color:#94A3B8;font-size:.85rem;">
                No hotels found. Add from Service Templates.
              </div>
              @endforelse

              {{-- Custom --}}
              <div class="hotel-row hotel-row-custom selected" id="hotelCustomCard" onclick="selectHotelCustom()">
                <div class="hotel-row-chk" style="background:#6366F1;">✓</div>
                <div class="hotel-row-icon">✏️</div>
                <div class="hotel-row-info">
                  <div class="hotel-row-name" style="color:#6366F1;">Custom Property</div>
                  <div class="hotel-row-meta">
                    <span style="font-size:.66rem;color:#818CF8;">Add manually</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Custom property form --}}
            <div id="customHotelWrap" class="custom-hotel-wrap">
              <div style="font-size:.7rem;font-weight:800;color:#7C3AED;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">✏️ Custom Property Details</div>
              <div class="row g-3 align-items-end">
                <div class="col-md-5">
                  <label class="sb-label" style="color:#6D28D9;">Property Name <span class="sb-req">*</span></label>
                  <input type="text" id="customHotelName" class="form-control sb-input"
                         placeholder="e.g. Kashi Heritage Hotel"
                         style="border-color:#C4B5FD !important;" oninput="buildPlan()">
                </div>
                <div class="col-md-3">
                  <label class="sb-label" style="color:#6D28D9;">Room Type</label>
                  <select id="customRoomType" class="form-select sb-input" style="border-color:#C4B5FD !important;" onchange="onCustomRoomChange()">
                    <option value="">Select…</option>
                    @foreach(['Deluxe Room'=>2500,'Executive Room'=>3500,'Premium Room'=>5000,'Suite'=>8000,'Homestay'=>1500] as $rt=>$rp)
                      <option value="{{ $rt }}" data-price="{{ $rp }}">{{ $rt }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="sb-label" style="color:#6D28D9;">Rate / Night (₹) <span class="sb-req">*</span></label>
                  <div class="sb-rupee-wrap">
                    <span class="sb-rupee" style="color:#7C3AED;">₹</span>
                    <input type="number" id="customHotelRate" class="form-control sb-input sb-rupee-input"
                           placeholder="0" min="0" step="1" style="border-color:#C4B5FD !important;"
                           oninput="recalcTotal()">
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="services[0][service_template_id]" id="hotelTplId">
          </div>
        </div>

        {{-- 3. Stay Details --}}
        <div class="sb-card">
          <div class="sb-card-head">
            <div class="sb-card-icon" style="background:#FEF3C7;">📅</div>
            <div class="sb-card-title">Stay Details</div>
          </div>
          <div class="sb-card-body">

            {{-- Check-in / Nights / Check-out --}}
            <div class="sb-dates-row" style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
              <div style="flex:1;">
                <div class="sb-label" style="margin-bottom:6px;">Check-in</div>
                <div class="date-card" id="checkinCard">
                  <div class="date-card-lbl">Date</div>
                  <input type="date" name="booking_start_date" id="checkin"
                         value="{{ old('booking_start_date') }}"
                         onchange="calcNights();buildPlan();this.closest('.date-card').classList.toggle('filled',!!this.value)">
                  <div class="date-card-lbl" style="margin-top:8px;margin-bottom:4px;">Time</div>
                  <input type="time" id="checkinTime" value="14:00" onchange="buildPlan()"
                         style="color:#0891B2 !important;font-weight:600 !important;">
                </div>
              </div>

              <div class="nights-badge" id="nightsBadge">
                <div class="nights-badge-num" id="nightsNum">—</div>
                <div class="nights-badge-lbl">Nights</div>
              </div>

              <div style="flex:1;">
                <div class="sb-label" style="margin-bottom:6px;">Check-out</div>
                <div class="date-card" id="checkoutCard">
                  <div class="date-card-lbl">Date</div>
                  <input type="date" name="booking_end_date" id="checkout"
                         value="{{ old('booking_end_date') }}"
                         onchange="calcNights();buildPlan();this.closest('.date-card').classList.toggle('filled',!!this.value)">
                  <div class="date-card-lbl" style="margin-top:8px;margin-bottom:4px;">Time</div>
                  <input type="time" id="checkoutTime" value="11:00" onchange="buildPlan()"
                         style="color:#DC2626 !important;font-weight:600 !important;">
                </div>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-6 col-md-3">
                <label class="sb-label">Adults</label>
                <div class="counter-wrap">
                  <button type="button" class="counter-btn" onclick="adj('adults',-1)">−</button>
                  <span class="counter-val" id="adultsDis">1</span>
                  <button type="button" class="counter-btn" onclick="adj('adults',1)">+</button>
                </div>
                <input type="hidden" id="adultsVal" value="1">
              </div>
              <div class="col-6 col-md-3">
                <label class="sb-label">Children</label>
                <div class="counter-wrap">
                  <button type="button" class="counter-btn" onclick="adj('kids',-1)">−</button>
                  <span class="counter-val" id="kidsDis">0</span>
                  <button type="button" class="counter-btn" onclick="adj('kids',1)">+</button>
                </div>
                <input type="hidden" id="kidsVal" value="0">
              </div>
              <div class="col-6 col-md-3">
                <label class="sb-label">No. of Rooms <span class="sb-req">*</span></label>
                <input type="number" id="rooms" class="form-control sb-input" min="1" value="1" required
                       oninput="recalcTotal();buildPlan()">
              </div>
              <div class="col-6 col-md-3">
                <label class="sb-label">Room Type</label>
                <select id="roomType" class="form-select sb-input" onchange="buildPlan()">
                  <option value="">Select…</option>
                  @foreach(['Deluxe Room','Executive Room','Premium Room','Suite','Homestay Flat'] as $rt)
                    <option>{{ $rt }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="sb-label">Extra Bed (₹ / night)</label>
                <div class="sb-rupee-wrap">
                  <span class="sb-rupee" style="color:#7C3AED;">₹</span>
                  <input type="number" id="extraBed" class="form-control sb-input sb-rupee-input"
                         placeholder="0" min="0" oninput="recalcTotal();buildPlan()">
                </div>
              </div>
              <div class="col-md-8">
                <label class="sb-label">Special Requests</label>
                <input type="text" id="specialReq" class="form-control sb-input"
                       placeholder="e.g. Ganga view room, early check-in, high floor…" oninput="buildPlan()">
              </div>
            </div>

            {{-- Meal plan --}}
            <div style="margin-top:18px;">
              <label class="sb-label">Meal Plan</label>
              <div class="meal-pills">
                @foreach(['EP'=>['Room Only','Bed only'],'CP'=>['Breakfast','Continental'],'MAP'=>['Half Board','B + Dinner'],'AP'=>['Full Board','All meals']] as $mv=>[$ml,$sub])
                <label class="meal-pill" onclick="selectMeal('{{ $mv }}', this)">
                  <input type="radio" name="_meal_ui" value="{{ $mv }}">
                  <span style="font-weight:800;font-size:.78rem;">{{ $mv }}</span>
                  <span class="meal-pill-code">{{ $ml }}</span>
                </label>
                @endforeach
              </div>
              <input type="hidden" id="mealVal" value="">
            </div>

            {{-- Plan preview --}}
            <div class="plan-preview">
              <div class="plan-preview-lbl">📋 Voucher Summary Preview</div>
              <div class="plan-preview-text" id="planPreviewText">Fill in the details above to generate a summary…</div>
            </div>
          </div>
        </div>

        {{-- 4. Additional Notes --}}
        <div class="sb-card">
          <div class="sb-card-head">
            <div class="sb-card-icon" style="background:#EEF2FF;">📝</div>
            <div class="sb-card-title">Additional Notes</div>
          </div>
          <div class="sb-card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="sb-label">Internal Notes <span style="font-weight:400;text-transform:none;font-size:.68rem;">(Staff only)</span></label>
                <textarea name="internal_notes" class="form-control sb-input" rows="3"
                          placeholder="Notes visible to staff only…">{{ old('internal_notes') }}</textarea>
              </div>
              <div class="col-md-6">
                <label class="sb-label">Tour Plan / Itinerary</label>
                <textarea name="tour_plan" class="form-control sb-input" rows="3"
                          placeholder="Day 1: Arrival | Day 2: Darshan…">{{ old('tour_plan') }}</textarea>
              </div>
            </div>
          </div>
        </div>

      </div>{{-- /left --}}

      {{-- ══════════ SIDEBAR ══════════ --}}
      <div class="sb-sidebar">
        <div class="sb-sidebar-card">

          {{-- Selected hotel summary --}}
          <div class="sb-hotel-summary" id="hotelSummaryBox">
            <div class="sb-hotel-summary-lbl">Selected Property</div>
            <div class="sb-hotel-summary-name" id="hotelSummaryName">No hotel selected</div>
            <div class="sb-hotel-summary-dates" id="hotelSummaryDates">—</div>
          </div>

          {{-- Total amount --}}
          <div class="sb-amt-row">
            <label class="sb-label">
              Total Amount (₹) <span class="sb-req">*</span>
              <span id="autoTag" style="display:none;font-size:.6rem;font-weight:700;color:#0D9488;background:#CCFBF1;padding:2px 8px;border-radius:20px;margin-left:6px;letter-spacing:.03em;">AUTO</span>
            </label>
            <div class="sb-rupee-wrap">
              <span class="sb-rupee">₹</span>
              <input type="number" name="services[0][unit_price]" id="totalAmt"
                     class="form-control sb-input sb-rupee-input"
                     placeholder="Enter total" min="0" step="0.01"
                     value="0"
                     oninput="recalcBalance()">
            </div>
            <input type="hidden" name="services[0][quantity]" value="1">
            <input type="hidden" name="services[0][service_date]" id="svcDate">
            <div id="perNightNote" style="font-size:.7rem;color:#0D9488;margin-top:5px;font-weight:700;display:none;"></div>
          </div>

          {{-- Discount --}}
          <div class="sb-amt-row">
            <label class="sb-label">Discount (₹)</label>
            <div class="sb-rupee-wrap">
              <span class="sb-rupee">₹</span>
              <input type="number" name="discount" id="discountAmt"
                     class="form-control sb-input sb-rupee-input"
                     value="0" min="0" oninput="recalcBalance()">
            </div>
          </div>

          {{-- Advance --}}
          <div class="sb-amt-row">
            <label class="sb-label">Advance Paid (₹)</label>
            <div class="sb-rupee-wrap">
              <span class="sb-rupee">₹</span>
              <input type="number" name="advance_paid" id="advancePaid"
                     class="form-control sb-input sb-rupee-input"
                     value="0" min="0" oninput="recalcBalance()">
            </div>
          </div>

          {{-- Method --}}
          <div class="sb-amt-row">
            <label class="sb-label">Payment Method</label>
            <select name="payment_method" class="form-select sb-input">
              <option value="">Select…</option>
              @foreach(['cash'=>'💵 Cash','upi'=>'📱 UPI','bank_transfer'=>'🏦 Bank Transfer','card'=>'💳 Card','cheque'=>'📄 Cheque'] as $v=>$l)
              <option value="{{ $v }}">{{ $l }}</option>
              @endforeach
            </select>
          </div>

          {{-- Account --}}
          <div class="sb-amt-row">
            <label class="sb-label">Bank Account</label>
            <select name="payment_account_id" class="form-select sb-input">
              <option value="">Select account…</option>
              @foreach($paymentAccounts as $acc)
              <option value="{{ $acc->id }}" {{ old('payment_account_id')==$acc->id ? 'selected':'' }}>
                {{ $acc->account_name }}@if($acc->bank_name) — {{ $acc->bank_name }}@endif
              </option>
              @endforeach
            </select>
          </div>

          {{-- Payment summary --}}
          <div class="pay-box">
            <div class="pay-box-head">
              <span class="pay-box-title">Payment Summary</span>
              <span id="payBadge" class="pay-badge due">DUE</span>
            </div>
            <div class="pay-box-body">
              <div class="pay-row">
                <span class="pay-row-lbl">Total Amount</span>
                <span class="pay-row-val" id="sumTotal">₹0.00</span>
              </div>
              <div class="pay-row" id="discRow" style="display:none;">
                <span class="pay-row-lbl">Discount</span>
                <span class="pay-row-val" style="color:#FCA5A5;" id="sumDisc">-₹0.00</span>
              </div>
              <div class="pay-row">
                <span class="pay-row-lbl">Advance Paid</span>
                <span class="pay-row-val" style="color:#6EE7B7;" id="sumPaid">₹0.00</span>
              </div>
              <div class="pay-row pay-row-bal">
                <span class="pay-row-lbl">Balance Due</span>
                <span class="pay-row-val" id="sumBal">₹0.00</span>
              </div>
            </div>
            <div class="pay-progress-wrap">
              <div class="pay-progress-info">
                <span>Payment Progress</span>
                <span id="payPct" style="color:#6EE7B7;font-weight:700;">0%</span>
              </div>
              <div class="pay-progress-bar">
                <div class="pay-progress-fill" id="payBar" style="width:0%;"></div>
              </div>
            </div>
          </div>

          <button type="submit" class="sb-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Confirm Stay Booking
          </button>

        </div>
      </div>{{-- /sidebar --}}

    </div>{{-- /layout --}}

    {{-- Mobile sticky submit bar --}}
    <div class="sb-mob-bar">
      <div class="sb-mob-bar-info">
        <div class="sb-mob-bar-lbl">Balance Due</div>
        <div class="sb-mob-bar-amt" id="sbMobBal">₹0.00</div>
        <div class="sb-mob-bar-sub" id="sbMobSub">Total: ₹0</div>
      </div>
      <button type="submit" class="sb-mob-submit" form="stayForm">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        Confirm
      </button>
    </div>

  </form>

</div>

<script>
let selectedHotelPrice = 0;
let selectedHotelName  = '';
let isCustomHotel      = true;

function selectHotel(card) {
  document.querySelectorAll('.hotel-row').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  selectedHotelPrice = parseFloat(card.dataset.price) || 0;
  selectedHotelName  = card.dataset.name || '';
  isCustomHotel      = false;
  document.getElementById('hotelTplId').value = card.dataset.id;
  document.getElementById('customHotelWrap').style.display = 'none';
  updateHotelSummary(selectedHotelName);
  recalcTotal(); buildPlan();
}
function selectHotelCustom() {
  document.querySelectorAll('.hotel-row').forEach(c => c.classList.remove('selected'));
  document.getElementById('hotelCustomCard').classList.add('selected');
  selectedHotelPrice = parseFloat(document.getElementById('customHotelRate')?.value) || 0;
  isCustomHotel = true;
  document.getElementById('hotelTplId').value = '';
  document.getElementById('customHotelWrap').style.display = 'block';
  updateHotelSummary('Custom Property');
}
function onCustomRoomChange() {
  const sel = document.getElementById('customRoomType');
  const price = parseFloat(sel.options[sel.selectedIndex]?.dataset.price) || 0;
  if (price > 0) document.getElementById('customHotelRate').value = price;
  recalcTotal();
}
function updateHotelSummary(name) {
  const el = document.getElementById('hotelSummaryName');
  if (el) el.textContent = name || 'No hotel selected';
}

function calcNights() {
  const ci = document.getElementById('checkin').value;
  const co = document.getElementById('checkout').value;
  const sd = document.getElementById('svcDate');
  const ni = document.getElementById('nightsNum');
  if (ci && co) {
    const d = Math.round((new Date(co) - new Date(ci)) / 86400000);
    if (ni) ni.textContent = d > 0 ? d : '—';
    if (sd && ci) sd.value = ci;
    updateDatesSummary(ci, co, d > 0 ? d : 0);
  } else {
    if (ni) ni.textContent = '—';
  }
  recalcTotal();
}

function updateDatesSummary(ci, co, nights) {
  const el = document.getElementById('hotelSummaryDates');
  if (!el) return;
  if (ci && co) {
    const fmt = d => new Date(d+'T00:00:00').toLocaleDateString('en-GB',{day:'numeric',month:'short'});
    el.textContent = fmt(ci) + ' → ' + fmt(co) + (nights > 0 ? ' · ' + nights + ' night'+(nights>1?'s':'') : '');
  } else { el.textContent = '—'; }
}

function adj(type, delta) {
  const h = document.getElementById(type==='adults'?'adultsVal':'kidsVal');
  const d = document.getElementById(type==='adults'?'adultsDis':'kidsDis');
  const v = Math.max(type==='adults'?1:0, (parseInt(h.value)||0)+delta);
  h.value = v; d.textContent = v; buildPlan();
}

function selectMeal(val, el) {
  document.querySelectorAll('.meal-pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('mealVal').value = val;
  buildPlan();
}

function recalcTotal() {
  const price = isCustomHotel
    ? (parseFloat(document.getElementById('customHotelRate')?.value) || 0)
    : selectedHotelPrice;
  if (price <= 0) { updatePerNightNote(0,0,0); recalcBalance(); return; }
  const nights = parseInt(document.getElementById('nightsNum')?.textContent) || 0;
  const rooms  = parseInt(document.getElementById('rooms')?.value) || 1;
  const extra  = parseFloat(document.getElementById('extraBed')?.value) || 0;
  const total  = price * rooms * nights + extra * nights;
  const tf = document.getElementById('totalAmt');
  if (tf && total > 0) tf.value = total.toFixed(2);
  document.getElementById('autoTag').style.display = total > 0 ? 'inline-flex' : 'none';
  updatePerNightNote(price, nights, rooms);
  recalcBalance();
}

function updatePerNightNote(rate, nights, rooms) {
  const note = document.getElementById('perNightNote');
  if (!note) return;
  if (rate > 0 && nights > 0) {
    note.textContent = '₹'+rate.toLocaleString('en-IN')+'/room × '+nights+'N × '+rooms+'R';
    note.style.display = 'block';
  } else { note.style.display = 'none'; }
}

function recalcBalance() {
  const total    = parseFloat(document.getElementById('totalAmt').value) || 0;
  const discount = parseFloat(document.getElementById('discountAmt').value) || 0;
  const paid     = parseFloat(document.getElementById('advancePaid').value) || 0;
  const net      = Math.max(0, total - discount);
  const bal      = Math.max(0, net - paid);
  const pct      = net > 0 ? Math.min(100, paid / net * 100) : 0;
  const fmt = v => '₹' + v.toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2});
  document.getElementById('sumTotal').textContent = fmt(total);
  document.getElementById('sumPaid').textContent  = fmt(paid);
  document.getElementById('sumBal').textContent   = fmt(bal);
  document.getElementById('payPct').textContent   = pct.toFixed(0) + '%';
  document.getElementById('payBar').style.width   = pct.toFixed(0) + '%';
  const discRow = document.getElementById('discRow');
  if (discount > 0) {
    document.getElementById('sumDisc').textContent = '-' + fmt(discount);
    discRow.style.display = '';
  } else { discRow.style.display = 'none'; }
  const badge = document.getElementById('payBadge');
  badge.className = 'pay-badge ' + (bal <= 0 ? 'paid' : (paid > 0 ? 'partial' : 'due'));
  badge.textContent = bal <= 0 ? 'PAID' : (paid > 0 ? 'PARTIAL' : 'DUE');

  // sync mobile sticky bar
  const mobBal = document.getElementById('sbMobBal');
  const mobSub = document.getElementById('sbMobSub');
  if (mobBal) mobBal.textContent = fmt(bal);
  if (mobSub) {
    const p = ['Total: ' + fmt(total)];
    if (discount > 0) p.push('Disc: ' + fmt(discount));
    if (paid > 0) p.push('Adv: ' + fmt(paid));
    mobSub.textContent = p.join(' · ');
  }
}

function fmtDate(d) {
  if (!d) return '';
  return new Date(d+'T00:00:00').toLocaleDateString('en-GB',{day:'numeric',month:'short',year:'numeric'});
}
function fmtTime(t) {
  if (!t) return '';
  const [h,m] = t.split(':').map(Number);
  return ((h%12)||12)+':'+String(m).padStart(2,'0')+(h>=12?' PM':' AM');
}

function buildPlan() {
  const ci    = document.getElementById('checkin').value;
  const co    = document.getElementById('checkout').value;
  const cit   = document.getElementById('checkinTime').value;
  const cot   = document.getElementById('checkoutTime').value;
  const ni    = document.getElementById('nightsNum').textContent;
  const rooms = document.getElementById('rooms').value;
  const rtype = document.getElementById('roomType').value;
  const adults= document.getElementById('adultsVal').value;
  const kids  = document.getElementById('kidsVal').value;
  const meal  = document.getElementById('mealVal').value;
  const extra = document.getElementById('extraBed').value;
  const req   = document.getElementById('specialReq').value.trim();
  const prop  = isCustomHotel
    ? (document.getElementById('customHotelName')?.value.trim() || '')
    : selectedHotelName;

  const parts = [];
  if (prop) parts.push('Property: '+prop);
  if (ci && co) parts.push('Check-in: '+fmtDate(ci)+' '+fmtTime(cit)+'  →  Check-out: '+fmtDate(co)+' '+fmtTime(cot));
  if (ni && ni !== '—' && parseInt(ni) > 0) parts.push('Duration: '+ni+' night(s)');
  parts.push('Rooms: '+rooms+(rtype?' ('+rtype+')':''));
  parts.push('Guests: '+adults+' Adult'+(adults!=1?'s':'')+(kids>0?', '+kids+' Child'+(kids!=1?'ren':''):''));
  if (meal) parts.push('Meal Plan: '+meal);
  if (extra > 0) parts.push('Extra Bed: ₹'+Number(extra).toLocaleString('en-IN'));
  if (req) parts.push('Requests: '+req);

  const plan = parts.join(' | ');
  document.getElementById('shortPlanHidden').value = plan;
  document.getElementById('planPreviewText').textContent = plan || 'Fill in the details above…';

  if (prop) updateHotelSummary(prop);
}

document.getElementById('stayForm').addEventListener('submit', function() {
  buildPlan();
  document.getElementById('bookingDateHidden').value = new Date().toISOString().slice(0,10);
});

document.addEventListener('DOMContentLoaded', () => { recalcBalance(); });
</script>

@endsection
