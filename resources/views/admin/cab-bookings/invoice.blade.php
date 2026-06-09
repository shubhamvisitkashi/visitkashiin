<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Booking Confirmation — {{ $booking->booking_number }}</title>
<style>
/* ══════════════════════════════════════
   VISITKASHI CAB BOOKING VOUCHER
   A4 · Blue Design (matches Boat Voucher)
══════════════════════════════════════ */
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Segoe UI',-apple-system,Roboto,Arial,sans-serif;background:#b8ccd8;color:#0f172a;font-size:13px;line-height:1.5;}

/* ── Screen Bar ── */
.screen-bar{background:#0c4a6e;padding:10px 16px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;position:sticky;top:0;z-index:999;}
.screen-bar-title{color:rgba(255,255,255,.5);font-size:.78rem;font-family:monospace;letter-spacing:.05em;flex:1;text-align:center;}
.s-btn{display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;font-size:.78rem;font-weight:700;text-decoration:none;cursor:pointer;border:none;transition:opacity .15s;white-space:nowrap;}
.s-btn:hover{opacity:.85;}
.s-btn-back{background:rgba(255,255,255,.15);color:rgba(255,255,255,.8);border:1.5px solid rgba(255,255,255,.2);}
.s-btn-wa{background:#25D366;color:#fff;}
.s-btn-print{background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;box-shadow:0 3px 14px rgba(14,165,233,.4);}

/* ── Wrapper ── */
.wrap{width:210mm;margin:0 auto;background:#fff;box-shadow:0 6px 40px rgba(0,0,0,.22);display:flex;flex-direction:column;min-height:297mm;}
.body-content{flex:1;}

/* ── Brand Header ── */
.bh{padding:22px 28px;display:flex;align-items:center;justify-content:space-between;gap:20px;
    background:linear-gradient(135deg,#0c4a6e 0%,#075985 45%,#0369a1 100%);
    position:relative;overflow:hidden;flex-shrink:0;}
.bh::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.06);}
.bh::after{content:'';position:absolute;bottom:-50px;left:80px;width:130px;height:130px;border-radius:50%;background:rgba(14,165,233,.08);}
.bh-left{display:flex;flex-direction:column;gap:0;position:relative;z-index:1;}
.bh-logo{height:48px;max-width:160px;object-fit:contain;display:block;}
.bh-unit{font-size:.72rem;font-weight:600;color:rgba(255,255,255,.6);margin-top:5px;letter-spacing:.01em;}
.bh-right{text-align:right;position:relative;z-index:1;}
.bh-brand-name{font-size:.95rem;font-weight:900;color:#fff;margin-bottom:3px;letter-spacing:.01em;}
.bh-contact-line{font-size:.76rem;color:rgba(255,255,255,.8);margin-bottom:2px;line-height:1.65;font-weight:500;}
.bh-contact-line:last-child{margin-bottom:0;}
.bh-addr{font-size:.7rem;color:rgba(255,255,255,.5);margin-bottom:5px;line-height:1.55;max-width:260px;}

/* ── Title Band (below header) ── */
.title-band{background:rgba(0,0,0,.22);border-top:1px solid rgba(255,255,255,.1);padding:8px 24px;
    display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
.tb-label{color:rgba(255,255,255,.6);font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;}
.tb-number{color:#fff;font-size:1rem;font-weight:900;letter-spacing:.05em;font-family:monospace;}
.tb-date-lbl{color:rgba(255,255,255,.5);font-size:.6rem;text-transform:uppercase;letter-spacing:.06em;text-align:center;}
.tb-date-val{color:#bae6fd;font-size:.8rem;font-weight:700;text-align:center;margin-top:1px;}
.tb-badge{padding:4px 14px;border-radius:20px;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;border:1.5px solid rgba(255,255,255,.4);color:#fff;}
.tb-badge.confirmed{background:rgba(5,150,105,.35);border-color:rgba(5,150,105,.5);}
.tb-badge.pending  {background:rgba(217,119,6,.35); border-color:rgba(217,119,6,.5);}
.tb-badge.completed{background:rgba(14,165,233,.35);border-color:rgba(14,165,233,.5);}
.tb-badge.cancelled{background:rgba(220,38,38,.35); border-color:rgba(220,38,38,.5);}
.tb-badge.assigned {background:rgba(124,58,237,.35);border-color:rgba(124,58,237,.5);}

/* ── Journey Section ── */
.journey{padding:16px 24px;border-bottom:1px solid #dde8f0;background:#fff;}
.journey-title{font-size:.9rem;font-weight:800;color:#0c4a6e;margin-bottom:3px;letter-spacing:-.01em;}
.journey-label{font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:12px;}
.journey-row{display:flex;align-items:flex-start;gap:0;}
.jp{flex:1;min-width:0;}
.jp-tag{font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;margin-bottom:4px;display:flex;align-items:center;gap:5px;}
.jp-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0;}
.jp-dot-pick{background:#0369a1;}
.jp-dot-drop{background:#dc2626;}
.jp-city{font-size:.92rem;font-weight:700;color:#0c4a6e;margin-bottom:2px;}
.jp-addr{font-size:.72rem;color:#64748b;line-height:1.4;}
.jp-dt{font-size:.72rem;font-weight:600;color:#0369a1;margin-top:5px;}
.jp-return{font-size:.7rem;font-weight:600;color:#0284c7;margin-top:3px;}
.jm{display:flex;flex-direction:column;align-items:center;padding:5px 18px;gap:3px;flex-shrink:0;}
.jm-line{width:1px;height:16px;background:repeating-linear-gradient(180deg,#bae6fd 0,#bae6fd 4px,transparent 4px,transparent 8px);}
.jm-arr{color:#0ea5e9;font-size:.85rem;transform:rotate(90deg);display:block;}
.jm-badge{font-size:.6rem;font-weight:700;color:#0369a1;background:#eff6ff;padding:3px 9px;border-radius:20px;white-space:nowrap;margin:3px 0;text-align:center;border:1px solid #bae6fd;}
.jm-km{font-size:.6rem;color:#94a3b8;text-align:center;}

/* ── Trip Info Bar ── */
.trip-bar{background:#f0f7ff;padding:10px 24px;border-bottom:1px solid #dde8f0;display:flex;gap:24px;flex-wrap:wrap;}
.tbi-lbl{font-size:.57rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#0369a1;margin-bottom:2px;}
.tbi-val{font-size:.8rem;font-weight:600;color:#0c4a6e;}

/* ── Body ── */
.body-content{display:block;background:#f0f7ff;}
.main{padding:14px 24px;}

/* ── Cards ── */
.card{background:#fff;border:1px solid #dde8f0;border-radius:10px;margin-bottom:10px;overflow:hidden;break-inside:avoid;page-break-inside:avoid;}
.card-head{padding:9px 14px;border-bottom:1px solid #e8f4fd;display:flex;align-items:center;gap:7px;background:linear-gradient(90deg,#f0f7ff,#e8f4fd);}
.card-head-ico{font-size:.85rem;}
.card-head-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#0c4a6e;}
.card-body{padding:14px;}

/* ── Detail Grid ── */
.dg{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.di-lbl{font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#64748b;margin-bottom:2px;}
.di-val{font-size:.8rem;font-weight:600;color:#0f172a;}
.di-val.accent{color:#0369a1;}

/* ── Fare Table ── */
.ft{width:100%;border-collapse:collapse;}
.ft td{padding:6px 0;font-size:.8rem;border-bottom:1px solid #f1f5f9;color:#374151;vertical-align:top;}
.ft tr:last-child td{border-bottom:none;}
.ft .fa{text-align:right;font-weight:600;}
.ft .fd td{color:#d97706;}
.ft .ftot td{font-weight:800;font-size:.88rem;color:#0c4a6e;padding-top:10px;border-top:2px solid #dde8f0;border-bottom:none;}
.ft .ftot .fa{color:#059669;font-size:1rem;}

/* ── Preference Pills ── */
.pref-grid{display:flex;flex-wrap:wrap;gap:6px;margin-top:4px;}
.pref-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:.68rem;font-weight:700;border:1px solid;}
.pref-on{background:#eff6ff;color:#0369a1;border-color:#bae6fd;}
.pref-detail{font-size:.72rem;color:#374151;background:#f0f7ff;border-radius:8px;padding:5px 10px;display:flex;align-items:center;gap:6px;margin-top:6px;}

/* ── Terms ── */
.terms{background:#f0f7ff;border:1px solid #bae6fd;border-radius:10px;padding:12px 14px;margin-bottom:10px;}
.terms-ttl{font-size:.65rem;font-weight:800;color:#0c4a6e;margin-bottom:6px;display:flex;align-items:center;gap:5px;text-transform:uppercase;letter-spacing:.06em;}
.terms ul{margin-left:14px;}
.terms li{font-size:.7rem;color:#334155;margin-bottom:3px;line-height:1.5;}

/* ── Footer ── */
.inv-footer{background:linear-gradient(135deg,#0c4a6e 0%,#075985 100%);padding:12px 24px;
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;flex-shrink:0;}
.footer-left{display:flex;align-items:center;gap:10px;}
.footer-logo{height:26px;max-width:90px;object-fit:contain;}
.footer-brand{font-size:.72rem;font-weight:600;color:rgba(255,255,255,.8);}
.footer-right{font-size:.65rem;color:rgba(255,255,255,.55);text-align:right;line-height:1.7;}

.watermark{background:#0a3d5c;text-align:center;padding:4px;font-size:.6rem;color:rgba(255,255,255,.25);letter-spacing:.06em;flex-shrink:0;}

/* ══ PRINT — force all backgrounds/gradients ══ */
@media print{
  *{-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;color-adjust:exact !important;}
  html,body{background:#fff !important;margin:0 !important;padding:0 !important;}
  .screen-bar{display:none !important;}
  @page{size:A4 portrait;margin:0;}

  .wrap{
    width:210mm !important;min-height:297mm !important;
    margin:0 auto !important;box-shadow:none !important;border-radius:0 !important;
  }

  /* Preserve gradient backgrounds */
  .bh          {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .title-band  {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .card-head   {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .trip-bar    {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .terms       {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .inv-footer  {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .watermark   {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .body-content{-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;background:#f0f7ff !important;}
  .pref-pill   {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .pref-detail {-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}

  .bh{padding:14px 18px;}
  .bh-logo{height:36px;}
  .main{padding:10px 16px;}
  .card{margin-bottom:7px;}
  .card-head{padding:6px 12px;}
  .card-body{padding:9px 12px;}
  .journey{padding:10px 16px;}
  .trip-bar{padding:7px 16px;gap:16px;}
  .inv-footer{padding:9px 16px;}
}

/* ══ MOBILE RESPONSIVE ══ */
@media(max-width:700px){
  body{background:#1e293b;}
  .wrap{width:100% !important;min-height:unset !important;box-shadow:none;}
  .bh{padding:14px 16px;flex-wrap:wrap;gap:10px;}
  .bh-right{text-align:left;}
  .title-band{padding:8px 16px;flex-wrap:wrap;gap:8px;}
  .journey{padding:12px 16px;}
  .trip-bar{padding:10px 16px;gap:14px;}
  .main{padding:10px 14px;}
  .journey-row{flex-direction:column;gap:10px;}
  .jm{flex-direction:row;padding:0;gap:8px;}
  .jm-line{width:18px;height:1px;}
  .jm-arr{transform:none;}
  .dg{grid-template-columns:1fr;}
  .inv-footer{padding:12px 16px;}
}
</style>
</head>
<body>

{{-- Screen Actions --}}
@php
  $waMsg = urlencode(
    "🚗 *Cab Booking Confirmed — Visit Kashi*\n\n" .
    "📋 ID: *{$booking->booking_number}*\n" .
    "👤 {$booking->customer_name}\n" .
    "📍 {$booking->pickup_address}\n" .
    "🏁 {$booking->drop_address}\n" .
    "📅 " . $booking->pickup_date->format('d M Y') . " at " . \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') . "\n" .
    "🚗 {$booking->vehicle_name}\n" .
    "💰 Total: ₹" . number_format($booking->total_amount,2) . "\n\n" .
    "Thank you for choosing Visit Kashi! 🙏"
  );
  $rawPhone = preg_replace('/\D/', '', $booking->customer_phone ?? '');
  $waPhone  = strlen($rawPhone) === 10 ? '91'.$rawPhone : (strlen($rawPhone) === 12 && str_starts_with($rawPhone,'91') ? $rawPhone : $rawPhone);
@endphp
<div class="screen-bar">
  <a href="{{ route('cab-bookings.show', $booking->id) }}" class="s-btn s-btn-back">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back
  </a>
  <div class="screen-bar-title">{{ $booking->booking_number }}</div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" class="s-btn s-btn-wa" target="_blank">
      <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075a8.167 8.167 0 01-2.385-1.475 8.166 8.166 0 01-1.653-2.059c-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
      WhatsApp Guest
    </a>
    <button onclick="window.print()" class="s-btn s-btn-print">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Print / Save PDF
    </button>
  </div>
</div>

<div class="wrap">

  {{-- Brand Header --}}
  <div class="bh">
    <div class="bh-left">
      @if(websiteSetupValue('logo'))
        <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('logo')) }}"
             alt="Visit Kashi" class="bh-logo">
      @else
        <div style="font-size:1.3rem;font-weight:900;color:#fff;letter-spacing:-.02em;">Visit Kashi</div>
      @endif
      <div class="bh-unit">A Unit of Albino Stay Pvt Ltd</div>
    </div>
    <div class="bh-right">
      <div class="bh-brand-name">VISIT KASHI</div>
      @if(websiteSetupValue('address'))
      <div class="bh-addr">{{ websiteSetupValue('address') }}</div>
      @endif
      <div class="bh-contact-line">📞 {{ websiteSetupValue('contact_number') ?? '7080109917' }} &nbsp;·&nbsp; {{ websiteSetupValue('whats_app_number') ?? '7080109918' }}</div>
      <div class="bh-contact-line">✉️ {{ websiteSetupValue('email') ?? 'info@visitkashi.in' }}</div>
    </div>
  </div>

  {{-- Title Band --}}
  <div class="title-band">
    <div>
      <div class="tb-label">🚗 Cab Booking Confirmation Voucher</div>
      <div class="tb-number">{{ $booking->booking_number }}</div>
    </div>
    <div>
      <div class="tb-date-lbl">Pickup Date</div>
      <div class="tb-date-val">{{ $booking->pickup_date->format('d M, Y') }}</div>
    </div>
    <div>
      <span class="tb-badge {{ $booking->booking_status }}">{{ ucfirst($booking->booking_status) }}</span>
    </div>
  </div>

  {{-- Journey --}}
  <div class="journey">
    {{-- Trip Name --}}
    @if($booking->trip_type)
    <div class="journey-title">{{ $booking->trip_type }}</div>
    @endif
    <div class="journey-label">Trip Route</div>
    <div class="journey-row">
      <div class="jp">
        <div class="jp-tag"><span class="jp-dot jp-dot-pick"></span> Pickup</div>
        <div class="jp-city">{{ Str::words($booking->pickup_address, 4, '') }}</div>
        <div class="jp-addr">{{ $booking->pickup_address }}</div>
        <div class="jp-dt">{{ $booking->pickup_date->format('D, d M Y') }} &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') }}</div>
      </div>
      <div class="jm">
        <div class="jm-line"></div>
        <span class="jm-arr">→</span>
        <div class="jm-badge">{{ $booking->trip_type }}</div>
        @if($booking->total_km)<div class="jm-km">~{{ number_format($booking->total_km,0) }} km</div>@endif
        <div class="jm-line"></div>
      </div>
      <div class="jp" style="text-align:right;">
        <div class="jp-tag" style="justify-content:flex-end;"><span class="jp-dot jp-dot-drop"></span> Drop</div>
        <div class="jp-city">{{ Str::words($booking->drop_address, 4, '') }}</div>
        <div class="jp-addr">{{ $booking->drop_address }}</div>
        @if($booking->return_date)
        <div class="jp-return">Return: {{ $booking->return_date->format('D, d M Y') }}</div>
        @endif
      </div>
    </div>
  </div>

  {{-- Trip Info Bar --}}
  <div class="trip-bar">
    <div>
      <div class="tbi-lbl">Vehicle</div>
      <div class="tbi-val">{{ $booking->vehicle_name }}</div>
    </div>
    @if($booking->seating_capacity)
    <div>
      <div class="tbi-lbl">Capacity</div>
      <div class="tbi-val">{{ $booking->seating_capacity }} Seats</div>
    </div>
    @endif
    @php
      $adults   = $booking->no_of_adults   ?? 0;
      $children = $booking->no_of_children ?? 0;
    @endphp
    @if($adults || $children)
    <div>
      <div class="tbi-lbl">Passengers</div>
      <div class="tbi-val">
        {{ $adults ? $adults . ' Adult' . ($adults != 1 ? 's' : '') : '' }}
        {{ ($adults && $children) ? ' + ' : '' }}
        {{ $children ? $children . ' Child' . ($children != 1 ? 'ren' : '') : '' }}
      </div>
    </div>
    @endif
    <div>
      <div class="tbi-lbl">Total Days</div>
      <div class="tbi-val">{{ $booking->total_days }} Day{{ $booking->total_days > 1 ? 's' : '' }}</div>
    </div>
    @if($booking->total_km)
    <div>
      <div class="tbi-lbl">Distance</div>
      <div class="tbi-val">{{ number_format($booking->total_km, 0) }} km</div>
    </div>
    @endif
  </div>

  {{-- Body --}}
  <div class="body-content">

    {{-- Main Column --}}
    <div class="main">

      {{-- Passenger Details --}}
      <div class="card">
        <div class="card-head"><span class="card-head-ico">👤</span><span class="card-head-title">Passenger Details</span></div>
        <div class="card-body">
          <div class="dg">
            <div>
              <div class="di-lbl">Full Name</div>
              <div class="di-val">{{ $booking->customer_name }}</div>
            </div>
            <div>
              <div class="di-lbl">Mobile</div>
              <div class="di-val accent">+91 {{ $booking->customer_phone }}</div>
            </div>
            @if($booking->customer_alt_phone)
            <div>
              <div class="di-lbl">Alt. Mobile</div>
              <div class="di-val">+91 {{ $booking->customer_alt_phone }}</div>
            </div>
            @endif
            @if($booking->customer_email)
            <div>
              <div class="di-lbl">Email</div>
              <div class="di-val">{{ $booking->customer_email }}</div>
            </div>
            @endif
            @if($booking->flight_train_number)
            <div>
              <div class="di-lbl">Flight / Train No.</div>
              <div class="di-val accent">{{ $booking->flight_train_number }}</div>
            </div>
            @endif
            @if($booking->gst_number)
            <div>
              <div class="di-lbl">GST Number</div>
              <div class="di-val">{{ $booking->gst_number }}</div>
            </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Preferences & Extras --}}
      @php
        $prefs = [
          ['🚗', 'Carrier on Roof',  $booking->carrier_on_roof       ?? false],
          ['👶', 'Child Seat',        $booking->child_seat             ?? false],
          ['♿', 'Wheelchair Access', $booking->wheelchair_accessible  ?? false],
          ['❄️', 'AC Required',       $booking->ac_required            ?? true],
        ];
        $activePrefs = array_filter($prefs, fn($p) => $p[2]);
        $hasExtras   = count($activePrefs) > 0
                    || !empty($booking->luggage_details)
                    || !empty($booking->notes);
      @endphp
      @if($hasExtras)
      <div class="card">
        <div class="card-head"><span class="card-head-ico">⚙️</span><span class="card-head-title">Preferences & Special Requirements</span></div>
        <div class="card-body">
          @if(count($activePrefs) > 0)
          <div class="pref-grid">
            @foreach($activePrefs as [$icon, $label, $active])
            <span class="pref-pill pref-on">{{ $icon }} {{ $label }}</span>
            @endforeach
          </div>
          @endif
          @if(!empty($booking->luggage_details))
          <div class="pref-detail" style="margin-top:{{ count($activePrefs) > 0 ? '8px' : '0' }};">
            <span>🧳</span> <strong>Luggage:</strong> {{ $booking->luggage_details }}
          </div>
          @endif
          @if(!empty($booking->notes))
          <div class="pref-detail" style="margin-top:6px;">
            <span>📝</span> <strong>Notes:</strong> {{ $booking->notes }}
          </div>
          @endif
        </div>
      </div>
      @endif

      {{-- Fare Breakdown --}}
      <div class="card">
        <div class="card-head"><span class="card-head-ico">💰</span><span class="card-head-title">Fare Breakdown</span></div>
        <div class="card-body">
          <table class="ft">
            <tbody>
              @foreach([
                'Base Fare'        => $booking->base_fare,
                'Driver Allowance' => $booking->driver_allowance,
                'Toll Tax'         => $booking->toll_tax,
                'Parking Charges'  => $booking->parking,
                'State Tax'        => $booking->state_tax,
                'Night Charges'    => $booking->night_charges,
                'Extra KM Charges' => $booking->extra_km_charges,
              ] as $lbl => $val)
              @if($val > 0)
              <tr>
                <td>{{ $lbl }}</td>
                <td class="fa">₹{{ number_format($val,2) }}</td>
              </tr>
              @endif
              @endforeach
              @if($booking->discount > 0)
              <tr class="fd">
                <td>Discount</td>
                <td class="fa">− ₹{{ number_format($booking->discount,2) }}</td>
              </tr>
              @endif
            </tbody>
            <tfoot>
              @php $paid = $booking->payments->sum('amount'); $balance = max(0,$booking->total_amount - $paid); @endphp
              <tr class="ftot">
                <td>Total Fare</td>
                <td class="fa">₹{{ number_format($booking->total_amount,2) }}</td>
              </tr>
              <tr><td colspan="2" style="padding:0;border-bottom:1px solid #374151;"></td></tr>
              <tr style="background:#F0FDF4;">
                <td style="color:#15803D;font-weight:600;padding:7px 0;">Amount Paid</td>
                <td class="fa" style="color:#15803D;font-weight:700;">₹{{ number_format($paid,2) }}</td>
              </tr>
              @if($balance > 0)
              <tr style="background:#FEF2F2;">
                <td style="color:#DC2626;font-weight:700;padding:7px 0;">Balance Due</td>
                <td class="fa" style="color:#DC2626;font-weight:800;">₹{{ number_format($balance,2) }}</td>
              </tr>
              @else
              <tr style="background:#F0FDF4;">
                <td colspan="2" style="color:#15803D;font-weight:700;text-align:center;padding:8px 0;font-size:.8rem;letter-spacing:.02em;">✅ FULLY PAID</td>
              </tr>
              @endif
            </tfoot>
          </table>
        </div>
      </div>

      {{-- Terms --}}
      <div class="terms">
        <div class="terms-ttl">📋 Terms & Conditions</div>
        <ul>
          <li>Please be ready 10 minutes before the scheduled pickup time.</li>
          <li>This voucher must be presented to the driver at the time of pickup.</li>
          <li>Toll taxes, parking, and state entry fees are included as per the fare breakdown.</li>
          <li>Cancellations within <strong>24 hours</strong> of pickup are <strong>Non-Refundable</strong>.</li>
          <li>Visit Kashi is not responsible for delays due to traffic, weather, or unforeseen circumstances.</li>
        </ul>
      </div>

    </div>{{-- /main --}}
  </div>{{-- /body-content --}}

  {{-- Footer --}}
  <div class="inv-footer">
    <div class="footer-left">
      <div>
        <div style="color:#fff;font-weight:800;font-size:.9rem;margin-bottom:2px;">Thank you for choosing Visit Kashi! 🙏</div>
        <div class="footer-brand">📞 7080109917 &nbsp;|&nbsp; 7080109918 &nbsp;|&nbsp; 7080109919</div>
        <div class="footer-brand">✉️ info@visitkashi.in &nbsp;·&nbsp; 🌐 www.visitkashi.in</div>
      </div>
    </div>
    <div class="footer-right">
      <div>Created By: {{ optional($booking->createdBy)->name ?? 'Admin' }}</div>
      <div>Booking ID: {{ $booking->booking_number }}</div>
      <div>Generated: {{ now()->format('d M Y, h:i A') }}</div>
    </div>
  </div>

  <div class="watermark">Powered by VisitKashi CRM &nbsp;·&nbsp; Computer-generated confirmation · No signature required</div>

</div>{{-- /wrap --}}
</body>
</html>
