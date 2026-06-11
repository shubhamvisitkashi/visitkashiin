@extends('admin.layouts.app')
@section('content')

<style>
:root {
  --bt-bg:#EFF6FF; --bt-card:#fff; --bt-border:#BFDBFE;
  --bt-blue:#0EA5E9; --bt-dkblue:#0284C7; --bt-navy:#0369A1;
  --bt-text:#0F172A; --bt-sub:#475569; --bt-muted:#94A3B8;
}
.bt-page{background:var(--bt-bg);min-height:100vh;padding:20px 24px 60px;}

/* ── Header ───────── */
.bt-header {
  background: linear-gradient(135deg, #0C4A6E 0%, #075985 40%, #0EA5E9 100%);
  border-radius: 18px;
  padding: 22px 28px;
  margin-bottom: 22px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  margin-top: 50px;
  position: relative;
  overflow: hidden;
}
.bt-header::before{content:'';position:absolute;top:-50px;right:-50px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.06);}
.bt-header-left{position:relative;z-index:1;}
.bt-header h1{color:#fff;font-size:1.2rem;font-weight:800;margin:0 0 4px;}
.bt-header p{color:rgba(255,255,255,.7);font-size:.78rem;margin:0;}
.bt-header-acts{position:relative;z-index:1;display:flex;gap:8px;}
.bt-ghost{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);color:#fff;border-radius:9px;padding:8px 14px;font-size:.78rem;font-weight:700;text-decoration:none;transition:.18s;}
.bt-ghost:hover{background:rgba(255,255,255,.25);color:#fff;}

/* ── Layout ───────── */
.bt-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:1100px){.bt-layout{grid-template-columns:1fr;}}

/* ── Card ─────────── */
.bt-card{background:#fff;border-radius:16px;border:1px solid #BFDBFE;box-shadow:0 1px 4px rgba(0,0,0,.04),0 2px 12px rgba(14,165,233,.06);margin-bottom:16px;overflow:hidden;}
.bt-card-head{padding:13px 20px;border-bottom:1px solid #EFF6FF;display:flex;align-items:center;gap:10px;background:#F0F9FF;}
.bt-card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem;}
.bt-card-title{font-size:.87rem;font-weight:700;color:#0F172A;}
.bt-card-body{padding:20px;}

/* ── Inputs ───────── */
.bt-label{font-size:.7rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.bt-req{color:#EF4444;margin-left:2px;}
.bt-input{border:1.5px solid #BAE6FD !important;border-radius:10px !important;padding:9px 13px !important;font-size:.875rem !important;color:#0F172A !important;background:#F0F9FF !important;transition:all .2s !important;width:100%;}
.bt-input:focus{border-color:#0EA5E9 !important;box-shadow:0 0 0 3px rgba(14,165,233,.15) !important;background:#fff !important;outline:none !important;}
.bt-wrap{position:relative;}
.bt-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;font-weight:600;color:#64748B;z-index:2;}
.bt-input-ph{padding-left:40px !important;}

/* ── Boat cards ────── */
.boat-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;}
.boat-card{border:2.5px solid #BFDBFE;border-radius:16px;padding:20px 14px 16px;cursor:pointer;text-align:center;transition:.22s;background:#fff;position:relative;user-select:none;}
.boat-card:hover{border-color:#0EA5E9;background:#EFF6FF;transform:translateY(-3px);box-shadow:0 8px 24px rgba(14,165,233,.18);}
.boat-card.selected{border-color:#0EA5E9;background:#EFF6FF;box-shadow:0 0 0 3px rgba(14,165,233,.22);}
.bc-chk{position:absolute;top:10px;right:10px;width:20px;height:20px;background:#0EA5E9;border-radius:50%;font-size:.65rem;color:#fff;display:none;align-items:center;justify-content:center;font-weight:900;}
.boat-card.selected .bc-chk{display:flex;}
.bc-emoji{font-size:2.2rem;margin-bottom:8px;line-height:1;}
.bc-name{font-size:.82rem;font-weight:700;color:#0F172A;line-height:1.35;margin-bottom:4px;}
.bc-cap{font-size:.68rem;color:#94A3B8;margin-bottom:6px;}
.bc-price{font-size:1.05rem;font-weight:800;color:#0EA5E9;}
.bc-extra{font-size:.62rem;color:#64748B;margin-top:2px;font-weight:500;}

/* ── Counters ──────── */
.ctr-wrap{display:flex;align-items:center;gap:0;border:1.5px solid #BAE6FD;border-radius:10px;overflow:hidden;background:#fff;}
.ctr-btn{width:40px;height:42px;border:none;background:#F0F9FF;font-size:1.3rem;font-weight:700;cursor:pointer;color:#0369A1;transition:.15s;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
.ctr-btn:hover{background:#BFDBFE;color:#0C4A6E;}
.ctr-val{flex:1;text-align:center;font-size:1.3rem;font-weight:800;color:#0F172A;font-variant-numeric:tabular-nums;}
.ctr-sub{font-size:.65rem;color:#94A3B8;margin-top:4px;text-align:center;font-weight:500;}

/* ── Booking type pills ── */
.btype-pills{display:flex;gap:8px;flex-wrap:wrap;}
.btype-pill{border:2px solid #BFDBFE;border-radius:9px;padding:8px 14px;font-size:.76rem;font-weight:700;cursor:pointer;background:#fff;transition:.18s;color:#0369A1;display:flex;align-items:center;gap:6px;}
.btype-pill:hover{border-color:#0EA5E9;background:#EFF6FF;}
.btype-pill.active{border-color:#0EA5E9;background:#0EA5E9;color:#fff;}
.btype-pill input{display:none;}

/* ── Sidebar ───────── */
.bt-sb{position:sticky;top:20px;}
.bt-sb-card{background:#fff;border-radius:16px;border:1px solid #BFDBFE;box-shadow:0 1px 4px rgba(0,0,0,.04);padding:20px;margin-bottom:16px;}
.bt-sb-title{font-size:.85rem;font-weight:700;color:#0F172A;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #EFF6FF;display:flex;align-items:center;gap:8px;}
.bt-rupee-wrap{position:relative;}
.bt-rupee{position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:.9rem;font-weight:700;color:#475569;pointer-events:none;z-index:2;}
.bt-rupee-input{padding-left:32px !important;}

/* ── Balance display ── */
.bt-balance{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1.5px solid #BFDBFE;border-radius:12px;padding:14px 16px;margin:12px 0;}
.bt-balance-lbl{font-size:.65rem;font-weight:700;color:#0369A1;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;}
.bt-balance-amt{font-size:1.8rem;font-weight:900;color:#0C4A6E;}
.pay-badge{display:inline-block;font-size:.62rem;font-weight:700;padding:2px 10px;border-radius:20px;margin-top:4px;}
.pay-badge.paid{background:#D1FAE5;color:#065F46;}
.pay-badge.partial{background:#DBEAFE;color:#1E40AF;}
.pay-badge.pending{background:#FEE2E2;color:#DC2626;}

/* ── Pay summary rows ── */
.ps-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.78rem;border-bottom:1px dashed #EFF6FF;}
.ps-row:last-child{border-bottom:none;}
.ps-lbl{color:#64748B;}
.ps-val{font-weight:700;color:#0F172A;}

/* ── Staff section ─── */
.staff-card{background:linear-gradient(135deg,#FFF7ED,#FEF3C7);border:1.5px solid #FDE68A;border-radius:14px;padding:16px;margin-top:16px;}
.staff-card-head{display:flex;align-items:center;gap:8px;margin-bottom:12px;}
.staff-badge{background:#F59E0B;color:#fff;font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:4px;text-transform:uppercase;letter-spacing:.04em;}
.not-conf{background:#EF4444;color:#fff;font-size:.6rem;font-weight:700;padding:2px 7px;border-radius:4px;text-transform:uppercase;margin-left:4px;}
.staff-label{font-size:.7rem;font-weight:700;color:#92400E;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.staff-input{border:1.5px solid #FDE68A !important;border-radius:9px !important;padding:9px 12px !important;font-size:.85rem !important;color:#0F172A !important;background:#FFFBEB !important;width:100%;}
.staff-input.bt-rupee-input{padding-left:32px !important;}
.staff-input:focus{border-color:#F59E0B !important;outline:none !important;}
.margin-display{background:#FFFBEB;border:1px solid #FDE68A;border-radius:9px;padding:10px 14px;margin-top:8px;}
.margin-row{display:flex;justify-content:space-between;font-size:.75rem;padding:3px 0;}
.margin-label{color:#92400E;}
.margin-value{font-weight:700;color:#0F172A;}
.margin-profit{color:#059669;font-size:.85rem;font-weight:800;}

/* ── Submit ─────────── */
.bt-submit{display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#0EA5E9,#0284C7);color:#fff;border:none;border-radius:11px;padding:14px;font-size:.92rem;font-weight:800;cursor:pointer;width:100%;margin-top:10px;transition:opacity .2s,transform .2s;}
.bt-submit:hover{opacity:.9;transform:translateY(-1px);}
</style>

<div class="bt-page">

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-3">
  <strong>Errors:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header --}}
<div class="bt-header">
  <div class="bt-header-left">
    <h1>⛵ New Boat Booking</h1>
    <p>Ganga Aarti · Sunrise Rides · Celebrations · Private Events</p>
  </div>
  <div class="bt-header-acts">
    <a href="{{ route('bookings.create-direct') }}" class="bt-ghost">← Change Type</a>
    <a href="{{ route('boat-booking.index') }}" class="bt-ghost">All Boat Bookings</a>
  </div>
</div>

<form action="{{ route('boat-booking.store') }}" method="POST" id="boatForm">
@csrf

<div class="bt-layout">

{{-- ══════════ LEFT ══════════ --}}
<div>

  {{-- 1. Booking Information --}}
  <div class="bt-card">
    <div class="bt-card-head">
      <div class="bt-card-icon" style="background:#DBEAFE;">📋</div>
      <div class="bt-card-title">Booking Information</div>
    </div>
    <div class="bt-card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="bt-label">Booking ID</label>
          <input type="text" class="form-control bt-input" readonly placeholder="Auto-generated"
                 style="background:#F8FAFC !important;color:#94A3B8 !important;">
        </div>
        <div class="col-md-4">
          <label class="bt-label">Booking Date <span class="bt-req">*</span></label>
          <input type="date" name="event_date" class="form-control bt-input" required
                 value="{{ old('event_date', now()->format('Y-m-d')) }}">
        </div>
        <div class="col-md-4">
          <label class="bt-label">Lead Source <span class="bt-req">*</span></label>
          <select name="lead_source_id" class="form-select bt-input" required>
            <option value="">Select…</option>
            @foreach($leadSources as $src)
              <option value="{{ $src->id }}" {{ old('lead_source_id')==$src->id?'selected':'' }}>{{ $src->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  {{-- 2. Guest Details --}}
  <div class="bt-card">
    <div class="bt-card-head">
      <div class="bt-card-icon" style="background:#DBEAFE;">👤</div>
      <div class="bt-card-title">Guest Details</div>
    </div>
    <div class="bt-card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="bt-label">Guest Name <span class="bt-req">*</span></label>
          <input type="text" name="name" class="form-control bt-input" required
                 placeholder="Full name" value="{{ old('name') }}">
        </div>
        <div class="col-md-4">
          <label class="bt-label">Mobile <span class="bt-req">*</span></label>
          @include('admin.partials.phone-input', [
              'name'     => 'phone',
              'value'    => old('phone'),
              'required' => true,
          ])
        </div>
        <div class="col-md-4">
          <label class="bt-label">Alt. Mobile</label>
          @include('admin.partials.phone-input', [
              'name'     => 'alt_phone',
              'codeName' => 'alt_phone_country_code',
              'value'    => old('alt_phone'),
              'required' => false,
              'placeholder' => 'Alternate',
          ])
        </div>
        <div class="col-md-6">
          <label class="bt-label">Email Address</label>
          <input type="email" name="email" class="form-control bt-input"
                 placeholder="guest@email.com" value="{{ old('email') }}">
        </div>
        <div class="col-md-6">
          <label class="bt-label">Country</label>
          <select name="country" class="form-select bt-input">
            @foreach(['India'=>'🇮🇳 India','USA'=>'🇺🇸 USA','UK'=>'🇬🇧 UK','UAE'=>'🇦🇪 UAE','Australia'=>'🇦🇺 Australia','Canada'=>'🇨🇦 Canada','Singapore'=>'🇸🇬 Singapore','Other'=>'🌍 Other'] as $v=>$l)
              <option value="{{ $v }}" {{ old('country','India')==$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  {{-- 3. Select Boat --}}
  <div class="bt-card">
    <div class="bt-card-head">
      <div class="bt-card-icon" style="background:#DBEAFE;">⛵</div>
      <div class="bt-card-title">Select Boat <span style="font-size:.7rem;color:#EF4444;font-weight:400;margin-left:4px;">* required</span></div>
    </div>
    <div class="bt-card-body">
      <div class="boat-grid" id="boatGrid">
        @php
          $boatEmojis = [
            'Normal Motor Boat'        => '🚤',
            'Light Motor Boat'         => '⛵',
            'Premium Light Motor Boat' => '⚡',
            'Luxury Mini Yacht'        => '🛥',
            'Bajra Boat'               => '🛶',
            'Cruise'                   => '🚢',
          ];
        @endphp

        @forelse($boat_types as $bt)
        @php
          // Prefer Regular-event boat for display, fallback to any boat
          $regularBoat  = $bt->boats->where('event_type','Regular')->first();
          $anyBoat      = $regularBoat ?? $bt->boats->first();

          // All pricing comes from DB columns
          $displayPrice = (float)($anyBoat?->price ?? 0);
          $basePax      = (int)($anyBoat?->base_pax ?? $anyBoat?->no_of_seat ?? 0);
          $maxPax       = (int)($anyBoat?->max_capacity ?? ($anyBoat ? $anyBoat->total_available_boat * $anyBoat->no_of_seat : 0));
          $extraRate    = (float)($anyBoat?->extra_per_person_rate ?? 0);
          $emoji        = $boatEmojis[$bt->name] ?? '⛵';
        @endphp
        <div class="boat-card"
             data-id="{{ $bt->id }}"
             data-name="{{ $bt->name }}"
             data-price="{{ $displayPrice }}"
             data-base="{{ $basePax }}"
             data-max="{{ $maxPax }}"
             data-extra="{{ $extraRate }}"
             onclick="selectBoat(this)">
          <div class="bc-chk">✓</div>
          <div class="bc-emoji">{{ $emoji }}</div>
          <div class="bc-name">{{ $bt->name }}</div>
          <div class="bc-cap">Base {{ $basePax }} | Max {{ $maxPax ?: '∞' }}</div>
          <div class="bc-price">₹{{ number_format($displayPrice) }}</div>
          @if($extraRate > 0)
          <div class="bc-extra">+₹{{ number_format($extraRate) }}/extra person</div>
          @endif
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:30px;color:var(--bt-muted);">
          No boats configured.
        </div>
        @endforelse
      </div>

      <input type="hidden" name="boat_type" id="boatTypeVal">
      <input type="hidden" name="event_type" id="eventTypeVal" value="Regular">
      <input type="hidden" name="base_pax" id="basePaxVal" value="0">
      <input type="hidden" name="extra_per_person_rate" id="extraRateVal" value="0">
      <div id="boatErr" style="display:none;color:#EF4444;font-size:.75rem;margin-top:8px;font-weight:600;">⚠ Please select a boat</div>
    </div>
  </div>

  {{-- 4. Number of Persons --}}
  <div class="bt-card">
    <div class="bt-card-head">
      <div class="bt-card-icon" style="background:#DBEAFE;">👥</div>
      <div class="bt-card-title">Number of Persons</div>
    </div>
    <div class="bt-card-body">
      <div class="row g-4">
        <div class="col-6 col-md-4">
          <label class="bt-label">Persons (Adults)</label>
          <div class="ctr-wrap">
            <button type="button" class="ctr-btn" onclick="adjPax('adults',-1)">−</button>
            <div class="ctr-val" id="adultsDis">1</div>
            <button type="button" class="ctr-btn" onclick="adjPax('adults',1)">+</button>
          </div>
          <div class="ctr-sub" id="capacityNote">Select a boat to see capacity</div>
          <input type="hidden" name="adults" id="adultsVal" value="1">
        </div>
        <div class="col-6 col-md-4">
          <label class="bt-label">Children <span style="font-weight:400;text-transform:none;font-size:.65rem;color:#059669;">(under 10 — Free)</span></label>
          <div class="ctr-wrap">
            <button type="button" class="ctr-btn" onclick="adjPax('kids',-1)">−</button>
            <div class="ctr-val" id="kidsDis">0</div>
            <button type="button" class="ctr-btn" onclick="adjPax('kids',1)">+</button>
          </div>
          <div class="ctr-sub" style="color:#059669;font-weight:600;">✓ No charge</div>
          <input type="hidden" name="children" id="kidsVal" value="0">
          <input type="hidden" name="no_of_person" id="noPerson" value="1">
        </div>
        <div class="col-md-4" style="display:flex;align-items:flex-end;">
          <div style="background:#F0F9FF;border:1.5px solid #BFDBFE;border-radius:10px;padding:10px 16px;width:100%;text-align:center;">
            <div style="font-size:.65rem;font-weight:700;color:#0369A1;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Total Pax</div>
            <div style="font-size:1.5rem;font-weight:900;color:#0C4A6E;" id="totalPaxDis">1</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- 5. Boat Booking Details --}}
  <div class="bt-card">
    <div class="bt-card-head">
      <div class="bt-card-icon" style="background:#DBEAFE;">🗓</div>
      <div class="bt-card-title">Boat Booking Details</div>
    </div>
    <div class="bt-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="bt-label">Booking Type <span class="bt-req">*</span></label>
          <select name="booking_type" id="bookingTypeSelect" class="form-select bt-input" required
                  onchange="onBookingTypeChange(this)">
            <option value="">Select booking type…</option>
            <option value="Morning Boat Ride"                {{ old('booking_type')=='Morning Boat Ride'                ?'selected':'' }}>🌅 Morning Boat Ride</option>
            <option value="Evening Boat Ride"                {{ old('booking_type')=='Evening Boat Ride'                ?'selected':'' }}>🌇 Evening Boat Ride</option>
            <option value="Evening Boat Ride with Ganga Aarti" {{ old('booking_type')=='Evening Boat Ride with Ganga Aarti'?'selected':'' }}>🪔 Evening Boat Ride with Ganga Aarti</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="bt-label">Event on Boat</label>
          <select name="event_on_boat" class="form-select bt-input">
            <option value="">None / Regular Ride</option>
            @foreach([
              'Birthday Celebration','Anniversary Celebration',
              'Marriage Proposal','Corporate Event',
              'Other'
            ] as $ev)
            <option value="{{ $ev }}" {{ old('event_on_boat')==$ev?'selected':'' }}>{{ $ev }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Add-on toggles --}}
      <div style="margin-top:16px;">
        <label class="bt-label" style="margin-bottom:10px;">Add-on Services</label>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;" id="addonGrid">
          @foreach(['decoration'=>'🌸 Decoration','photographer'=>'📸 Photographer','live_music'=>'🎵 Live Music','priest'=>'🪔 Priest / Puja','flowers'=>'💐 Flower Shower','fireworks'=>'🎆 Fireworks'] as $k=>$l)
          <label style="display:flex;align-items:center;justify-content:space-between;background:#F0F9FF;border:1.5px solid #BFDBFE;border-radius:10px;padding:9px 13px;cursor:pointer;transition:.18s;">
            <span style="font-size:.78rem;font-weight:600;color:#0F172A;">{{ $l }}</span>
            <div style="position:relative;width:38px;height:20px;flex-shrink:0;">
              <input type="checkbox" name="{{ $k }}" value="1" {{ old($k)?'checked':'' }}
                     style="opacity:0;width:0;height:0;position:absolute;"
                     onchange="this.closest('label').querySelector('.sw-sl').style.background=this.checked?'#0EA5E9':'#CBD5E1';this.closest('label').querySelector('.sw-th').style.transform=this.checked?'translateX(18px)':'translateX(0)';checkB2BVisibility()">
              <div class="sw-sl" style="position:absolute;top:0;left:0;right:0;bottom:0;background:#CBD5E1;border-radius:20px;transition:.25s;{{ old($k) ? 'background:#0EA5E9;' : '' }}"></div>
              <div class="sw-th" style="position:absolute;top:2px;left:2px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.25s;box-shadow:0 1px 3px rgba(0,0,0,.2);{{ old($k) ? 'transform:translateX(18px);' : '' }}"></div>
            </div>
          </label>
          @endforeach
        </div>
      </div>

      {{-- B2B Vendor Cost (shown when event or add-on selected) --}}
      <div id="b2bVendorWrap" style="display:none;margin-top:14px;background:#FFF7ED;border:1.5px solid #FED7AA;border-radius:12px;padding:14px 16px;">
        <label style="font-size:.75rem;font-weight:700;color:#92400E;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
          🏷 B2B Vendor Cost
          <span style="font-size:.7rem;font-weight:500;color:#B45309;text-transform:none;letter-spacing:0;">(Event / Add-on supplier cost)</span>
        </label>
        <div style="position:relative;">
          <span style="position:absolute;left:11px;top:50%;transform:translateY(-50%);font-weight:700;color:#92400E;font-size:.9rem;">₹</span>
          <input type="number" name="b2b_vendor_cost" id="b2bVendorCost" min="0" step="0.01"
                 value="{{ old('b2b_vendor_cost', '') }}"
                 class="form-control"
                 style="padding-left:28px;border:1.5px solid #FED7AA;border-radius:9px;font-size:.88rem;background:#fff;"
                 placeholder="0.00" oninput="recalcMargin()">
        </div>
      </div>

    </div>
  </div>

  {{-- 6. Route & Timing --}}
  <div class="bt-card">
    <div class="bt-card-head">
      <div class="bt-card-icon" style="background:#DBEAFE;">🗺</div>
      <div class="bt-card-title">Route & Timing</div>
    </div>
    <div class="bt-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="bt-label">Pickup Ghat <span class="bt-req">*</span></label>
          <select name="pickup_ghat" class="form-select bt-input" required id="pickupGhatSelect" onchange="toggleManualGhat('pickup',this.value)">
            <option value="">Select ghat…</option>
            @foreach(['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat'] as $g)
            <option value="{{ $g }}" {{ old('pickup_ghat')==$g?'selected':'' }}>{{ $g }}</option>
            @endforeach
            <option value="__manual__" {{ old('pickup_ghat')&&!in_array(old('pickup_ghat'),['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat',''])?'selected':'' }}>✏️ Other (Enter manually)</option>
          </select>
          <input type="text" id="pickupGhatManual" name="pickup_ghat_manual"
                 class="form-control bt-input mt-2"
                 placeholder="Enter ghat name…"
                 style="display:{{ old('pickup_ghat')&&!in_array(old('pickup_ghat'),['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat',''])?'block':'none' }};"
                 value="{{ old('pickup_ghat')&&!in_array(old('pickup_ghat'),['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat',''])?old('pickup_ghat'):'' }}">
        </div>
        <div class="col-md-6">
          <label class="bt-label">Pickup Time <span class="bt-req">*</span></label>
          <input type="time" name="pickup_time" class="form-control bt-input" required
                 value="{{ old('pickup_time') }}">
        </div>
        <div class="col-md-6">
          <label class="bt-label">Drop Ghat <span class="bt-req">*</span></label>
          <select name="drop_ghat" class="form-select bt-input" required id="dropGhatSelect" onchange="toggleManualGhat('drop',this.value)">
            <option value="">Select drop ghat…</option>
            @foreach(['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat'] as $g)
            <option value="{{ $g }}" {{ old('drop_ghat')==$g?'selected':'' }}>{{ $g }}</option>
            @endforeach
            <option value="__manual__" {{ old('drop_ghat')&&!in_array(old('drop_ghat'),['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat',''])?'selected':'' }}>✏️ Other (Enter manually)</option>
          </select>
          <input type="text" id="dropGhatManual" name="drop_ghat_manual"
                 class="form-control bt-input mt-2"
                 placeholder="Enter ghat name…"
                 style="display:{{ old('drop_ghat')&&!in_array(old('drop_ghat'),['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat',''])?'block':'none' }};"
                 value="{{ old('drop_ghat')&&!in_array(old('drop_ghat'),['Darbhanga (near Dashaswamedh) Ghat','Ravidas Ghat (Nagwa)','NAMO Ghat','Aadikeshav Ghat','Scindia Ghat','Tulsi Ghat','Lalita Ghat',''])?old('drop_ghat'):'' }}">
        </div>
        <div class="col-md-6">
          <label class="bt-label">Drop / End Time</label>
          <input type="time" name="drop_time" class="form-control bt-input"
                 value="{{ old('drop_time') }}">
        </div>
      </div>
    </div>
  </div>

  {{-- 7. Notes --}}
  <div class="bt-card">
    <div class="bt-card-head">
      <div class="bt-card-icon" style="background:#DBEAFE;">📝</div>
      <div class="bt-card-title">Notes</div>
    </div>
    <div class="bt-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="bt-label">Guest Notes <span style="font-weight:400;text-transform:none;font-size:.65rem;color:#94A3B8;">(shown on voucher)</span></label>
          <textarea name="guest_notes" class="form-control bt-input" rows="3"
                    placeholder="Special requests, preferences…">{{ old('guest_notes') }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="bt-label">Internal Notes <span style="font-weight:400;text-transform:none;font-size:.65rem;color:#94A3B8;">(staff only)</span></label>
          <textarea name="internal_notes" class="form-control bt-input" rows="3"
                    placeholder="Staff instructions, follow-up…">{{ old('internal_notes') }}</textarea>
        </div>
      </div>
    </div>
  </div>

</div>{{-- /left --}}

{{-- ══════════ SIDEBAR ══════════ --}}
<div class="bt-sb">

  {{-- Pricing --}}
  <div class="bt-sb-card">
    <div class="bt-sb-title">
      💰 Booking Amount
    </div>

    <div style="margin-bottom:12px;">
      <label class="bt-label">Total Booking Amount (₹) <span class="bt-req">*</span>
        <span id="autoTag" style="display:none;font-size:.6rem;background:#DBEAFE;color:#1E40AF;padding:1px 7px;border-radius:20px;font-weight:800;margin-left:6px;">AUTO</span>
      </label>
      <div class="bt-rupee-wrap">
        <span class="bt-rupee">₹</span>
        <input type="number" name="total_amount" id="boatTotal"
               class="form-control bt-input bt-rupee-input"
               placeholder="Auto from boat pricing" min="0" step="0.01"
               value="{{ old('total_amount') }}" oninput="recalc()">
      </div>
      <div id="priceBreakdown" style="font-size:.68rem;color:#0369A1;margin-top:4px;font-weight:600;display:none;"></div>
    </div>

    <div style="margin-bottom:12px;">
      <label class="bt-label">Discount (₹) — auto</label>
      <div class="bt-rupee-wrap">
        <span class="bt-rupee">₹</span>
        <input type="number" name="discount_amount" id="boatDisc"
               class="form-control bt-input bt-rupee-input"
               value="{{ old('discount_amount',0) }}" min="0" oninput="recalc()">
      </div>
    </div>

    <div style="margin-bottom:12px;">
      <label class="bt-label">Advance Paid (₹) <span class="bt-req">*</span></label>
      <div class="bt-rupee-wrap">
        <span class="bt-rupee">₹</span>
        <input type="number" name="paid_amount" id="boatPaid"
               class="form-control bt-input bt-rupee-input"
               value="{{ old('paid_amount',0) }}" min="0" required oninput="recalc()">
      </div>
    </div>

    {{-- Balance --}}
    <div class="bt-balance">
      <div class="bt-balance-lbl">Balance Due</div>
      <div class="bt-balance-amt" id="balDue">₹0</div>
      <span id="payBadge" class="pay-badge pending">PENDING</span>
    </div>

    {{-- Summary rows --}}
    <div style="margin:10px 0;">
      <div class="ps-row">
        <span class="ps-lbl">Payment Status</span>
        <span class="ps-val" id="payStatusText">Unpaid</span>
      </div>
    </div>

    <div style="margin-bottom:12px;">
      <label class="bt-label">Payment Method <span class="bt-req">*</span></label>
      <select name="payment_method" class="form-select bt-input" required>
        <option value="">Select…</option>
        @foreach(['cash'=>'💵 Cash','upi'=>'📱 UPI','bank_transfer'=>'🏦 Bank Transfer'] as $v=>$l)
        <option value="{{ $v }}" {{ old('payment_method')==$v?'selected':'' }}>{{ $l }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:12px;">
      <label class="bt-label">Bank Account <span class="bt-req">*</span></label>
      <select name="payment_account_id" class="form-select bt-input" required>
        <option value="">Select account…</option>
        @foreach($paymentAccounts as $acc)
        <option value="{{ $acc->id }}" {{ old('payment_account_id')==$acc->id?'selected':'' }}>
          {{ $acc->account_name }}@if($acc->bank_name) — {{ $acc->bank_name }}@endif
        </option>
        @endforeach
      </select>
    </div>

    {{-- Hidden fields --}}
    <input type="hidden" name="final_amount" id="finalAmtHidden">

    {{-- Staff Only Section --}}
    <div class="staff-card">
      <div class="staff-card-head">
        <span>⚙️</span>
        <span style="font-size:.78rem;font-weight:700;color:#92400E;">Internal — Staff Only</span>
        <span class="not-conf">NOT ON CONFIRMATION</span>
      </div>

      <div style="margin-bottom:10px;">
        <label class="staff-label">Assign Boatman <span style="color:#EF4444;">*</span></label>
        <select name="boatman_id" class="form-select staff-input" required>
          <option value="">Select boatman…</option>
          @foreach($boatmen as $s)
          <option value="{{ $s->id }}" {{ old('boatman_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
          @endforeach
        </select>
      </div>

      <div style="margin-bottom:10px;">
        <label class="staff-label">Vendor / Supplier Cost (₹) <span style="color:#EF4444;">*</span></label>
        <div class="bt-rupee-wrap">
          <span class="bt-rupee" style="color:#92400E;">₹</span>
          <input type="number" name="vendor_cost" id="vendorCost"
                 class="form-control staff-input bt-rupee-input"
                 value="{{ old('vendor_cost',0) }}" min="0" required oninput="recalcMargin()">
        </div>
      </div>

      <div class="margin-display">
        <div class="margin-row">
          <span class="margin-label">Margin on Booking</span>
          <span class="margin-value margin-profit" id="marginAmt">₹0</span>
        </div>
        <div class="margin-row">
          <span class="margin-label">Profit Margin %</span>
          <span class="margin-value" id="marginPct" style="color:#0369A1;">0%</span>
        </div>
      </div>
    </div>

    <button type="submit" class="bt-submit" onclick="return validateBoat()">
      ✓ Confirm Boat Booking
    </button>
  </div>

</div>{{-- /sidebar --}}

</div>{{-- /layout --}}
</form>

</div>{{-- /bt-page --}}

<script>
// ── B2B Vendor Cost visibility ────────────────────
function checkB2BVisibility() {
    const eventSel   = document.querySelector('select[name="event_on_boat"]');
    const hasEvent   = eventSel && eventSel.value !== '';
    const hasAddon   = Array.from(document.querySelectorAll('#addonGrid input[type="checkbox"]')).some(cb => cb.checked);
    const wrap       = document.getElementById('b2bVendorWrap');
    const input      = document.getElementById('b2bVendorCost');
    if (hasEvent || hasAddon) {
        wrap.style.display = '';
    } else {
        wrap.style.display = 'none';
        if (input) input.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const eventSel = document.querySelector('select[name="event_on_boat"]');
    if (eventSel) eventSel.addEventListener('change', checkB2BVisibility);
    checkB2BVisibility(); // run on load in case of old() values
});

// ── State ─────────────────────────────────────────
let selBoat      = null;  // selected boat card element
let boatPrice    = 0;
let boatBasePax  = 0;
let boatMaxPax   = 0;
let boatExtra    = 0;

// ── Boat selection ────────────────────────────────
function selectBoat(card) {
  document.querySelectorAll('.boat-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  selBoat       = card;
  boatPrice     = parseFloat(card.dataset.price) || 0;
  boatBasePax   = parseInt(card.dataset.base)    || 0;
  boatMaxPax    = parseInt(card.dataset.max)     || 0;
  boatExtra     = parseFloat(card.dataset.extra) || 0;

  document.getElementById('boatTypeVal').value  = card.dataset.id;
  document.getElementById('basePaxVal').value   = boatBasePax;
  document.getElementById('extraRateVal').value = boatExtra;
  document.getElementById('boatErr').style.display = 'none';

  // Update capacity note
  const note = document.getElementById('capacityNote');
  if (boatMaxPax > 0) {
    note.textContent = 'Max capacity: ' + boatMaxPax + ' persons';
    note.style.color = '#0369A1';
  } else {
    note.textContent = '';
  }

  // Auto-calc price for current pax
  calcBoatPrice();
}

// ── Pax counters ──────────────────────────────────
function adjPax(type, delta) {
  const hid  = document.getElementById(type==='adults' ? 'adultsVal' : 'kidsVal');
  const disp = document.getElementById(type==='adults' ? 'adultsDis' : 'kidsDis');
  const min  = type==='adults' ? 1 : 0;
  let v = Math.max(min, (parseInt(hid.value)||0) + delta);
  if (boatMaxPax > 0) {
    const total = (type==='adults' ? v : parseInt(document.getElementById('adultsVal').value)||1)
                + (type==='kids'   ? v : parseInt(document.getElementById('kidsVal').value)||0);
    if (total > boatMaxPax) { alert('Exceeds boat capacity of ' + boatMaxPax + ' persons!'); return; }
  }
  hid.value = v; disp.textContent = v;
  updateTotalPax();
  calcBoatPrice();
}

function updateTotalPax() {
  const a = parseInt(document.getElementById('adultsVal').value)||0;
  const k = parseInt(document.getElementById('kidsVal').value)||0;
  const total = a + k;
  document.getElementById('totalPaxDis').textContent = total;
  document.getElementById('noPerson').value = total;
}

// ── Auto-price calculation ────────────────────────
function calcBoatPrice() {
  if (!selBoat || boatPrice <= 0) { recalc(); return; }

  const adults  = parseInt(document.getElementById('adultsVal').value) || 1;
  // Children are FREE — only adults count toward pricing
  const billablePax = adults;

  let total = boatPrice; // base price covers basePax adults
  if (boatBasePax > 0 && billablePax > boatBasePax && boatExtra > 0) {
    total += (billablePax - boatBasePax) * boatExtra;
  }

  document.getElementById('boatTotal').value = total.toFixed(2);
  document.getElementById('autoTag').style.display = 'inline';

  // Show breakdown
  const bd = document.getElementById('priceBreakdown');
  if (boatBasePax > 0 && billablePax > boatBasePax && boatExtra > 0) {
    bd.textContent = '₹' + boatPrice.toLocaleString('en-IN') + ' base + ' + (billablePax - boatBasePax) + ' extra adult(s) × ₹' + boatExtra + ' | Children free';
    bd.style.display = 'block';
  } else {
    bd.textContent = 'Base price for up to ' + boatBasePax + ' adults | Children free';
    bd.style.display = 'block';
  }

  recalc();
}

// ── Booking type change ───────────────────────────
function onBookingTypeChange(sel) {
  // Auto-set pickup time suggestions
  const timeSuggestions = {
    'Morning Boat Ride':                  '07:00',
    'Evening Boat Ride':                  '17:00',
    'Evening Boat Ride with Ganga Aarti': '18:00',
  };
  const val = sel.value;
  const suggestedTime = timeSuggestions[val];
  if (suggestedTime) {
    const pt = document.querySelector('input[name="pickup_time"]');
    if (pt && !pt.value) pt.value = suggestedTime;
  }
}

// ── Balance recalc ────────────────────────────────
function recalc() {
  const total  = parseFloat(document.getElementById('boatTotal').value) || 0;
  const disc   = parseFloat(document.getElementById('boatDisc').value)  || 0;
  const paid   = parseFloat(document.getElementById('boatPaid').value)  || 0;
  const final_ = Math.max(0, total - disc);
  const bal    = Math.max(0, final_ - paid);

  document.getElementById('finalAmtHidden').value = final_.toFixed(2);
  document.getElementById('balDue').textContent = '₹' + bal.toLocaleString('en-IN', {minimumFractionDigits:0});

  const badge = document.getElementById('payBadge');
  const status = document.getElementById('payStatusText');
  if (bal <= 0 && final_ > 0) {
    badge.className = 'pay-badge paid';  badge.textContent = 'PAID';  status.textContent = 'Paid';
  } else if (paid > 0) {
    badge.className = 'pay-badge partial'; badge.textContent = 'PARTIAL'; status.textContent = 'Partial';
  } else {
    badge.className = 'pay-badge pending'; badge.textContent = 'PENDING'; status.textContent = 'Unpaid';
  }

  recalcMargin();
}

function recalcMargin() {
  const final_  = parseFloat(document.getElementById('finalAmtHidden').value) || 0;
  const vendor  = parseFloat(document.getElementById('vendorCost').value) || 0;
  const b2bEl   = document.getElementById('b2bVendorCost');
  const b2b     = b2bEl ? (parseFloat(b2bEl.value) || 0) : 0;
  const margin  = final_ - vendor - b2b;
  const pct     = final_ > 0 ? (margin / final_ * 100) : 0;

  document.getElementById('marginAmt').textContent = (margin >= 0 ? '₹' : '-₹') + Math.abs(margin).toLocaleString('en-IN');
  document.getElementById('marginAmt').style.color = margin >= 0 ? '#059669' : '#EF4444';
  document.getElementById('marginPct').textContent = pct.toFixed(1) + '%';
}

// ── Validation ────────────────────────────────────
function validateBoat() {
  if (!document.getElementById('boatTypeVal').value) {
    document.getElementById('boatErr').style.display = 'block';
    document.getElementById('boatGrid').scrollIntoView({behavior:'smooth', block:'center'});
    return false;
  }
  return true;
}

// Init
document.addEventListener('DOMContentLoaded', () => { recalc(); updateTotalPax(); });

// ── Manual Ghat toggle ───────────────────────────────────
function toggleManualGhat(type, val) {
  var manualEl = document.getElementById(type + 'GhatManual');
  if (!manualEl) return;
  if (val === '__manual__') {
    manualEl.style.display = 'block';
    manualEl.required = true;
    manualEl.focus();
  } else {
    manualEl.style.display = 'none';
    manualEl.required = false;
    manualEl.value = '';
  }
}

// Before submit: if manual selected, write value back into select or hidden field
document.getElementById('boatForm').addEventListener('submit', function() {
  ['pickup','drop'].forEach(function(type) {
    var sel    = document.getElementById(type + 'GhatSelect');
    var manual = document.getElementById(type + 'GhatManual');
    if (sel && manual && sel.value === '__manual__' && manual.value.trim()) {
      // Inject a new option with the manual value and select it
      var opt = document.createElement('option');
      opt.value    = manual.value.trim();
      opt.text     = manual.value.trim();
      opt.selected = true;
      sel.appendChild(opt);
      sel.value = manual.value.trim();
      // Remove "required" from manual input so form submits cleanly via select
      manual.name = ''; // detach from POST
    }
  });
});
</script>

@endsection
