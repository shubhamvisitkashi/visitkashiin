<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Boat Booking Voucher — {{ $booking->booking_id }}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Segoe UI',Arial,sans-serif;background:#b8ccd8;color:#0f172a;font-size:11.5px;line-height:1.4;}

/* ── A4 Wrapper — exact page dimensions ── */
.voucher{
  width:210mm;
  height:297mm;
  margin:0 auto;
  background:#fff;
  box-shadow:0 6px 40px rgba(0,0,0,.22);
  display:flex;
  flex-direction:column;
  overflow:hidden;
}

/* ══ HEADER ══ */
.hdr{background:linear-gradient(135deg,#0c4a6e 0%,#075985 45%,#0369a1 100%);position:relative;overflow:hidden;flex-shrink:0;}
.hdr::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.06);}

.hdr-top{display:flex;align-items:center;padding:26px 32px;position:relative;z-index:1;}
.hdr-logo{display:flex;align-items:center;gap:12px;}
.hdr-logo img{height:44px;border-radius:8px;object-fit:contain;}
.hdr-logo-fb{width:44px;height:44px;border-radius:10px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
.hdr-co-name{color:#fff;font-size:15px;font-weight:900;letter-spacing:.01em;line-height:1.2;}
.hdr-co-tagline{color:rgba(255,255,255,.75);font-size:9.5px;font-weight:600;margin-top:2px;}
.hdr-co-addr{color:rgba(255,255,255,.4);font-size:8px;line-height:1.5;margin-top:1px;}

.hdr-band{background:rgba(0,0,0,.22);border-top:1px solid rgba(255,255,255,.1);padding:8px 28px;display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1;}
.hdr-band-type{color:rgba(255,255,255,.6);font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;}
.hdr-band-id{color:#fff;font-size:17px;font-weight:900;letter-spacing:.05em;font-family:monospace;}
.hdr-band-date-lbl{color:rgba(255,255,255,.5);font-size:7.5px;text-transform:uppercase;letter-spacing:.06em;text-align:center;}
.hdr-band-date-val{color:#bae6fd;font-size:11px;font-weight:700;text-align:center;margin-top:1px;}
.hdr-badge{padding:5px 16px;border-radius:20px;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;border:2px solid;}
.hdr-badge.paid    {background:#059669;border-color:#059669;color:#fff;}
.hdr-badge.partial {background:#2563eb;border-color:#2563eb;color:#fff;}
.hdr-badge.unpaid  {background:#dc2626;border-color:#dc2626;color:#fff;}
.hdr-badge.confirmed{background:transparent;border-color:rgba(255,255,255,.4);color:#fff;}

/* ══ BODY — fills remaining height between header & footer ══ */
.body{flex:1;overflow:hidden;padding:10px 24px 6px;display:flex;flex-direction:column;}

/* ── Section ── */
.sec{border:1px solid #dde8f0;border-radius:8px;overflow:hidden;margin-bottom:7px;}
.sec-head{display:flex;align-items:center;gap:7px;padding:5px 13px;background:linear-gradient(90deg,#f0f7ff,#e8f4fd);border-bottom:1px solid #dde8f0;}
.sec-icon{width:20px;height:20px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;}
.sec-title{font-size:9px;font-weight:800;color:#0c4a6e;text-transform:uppercase;letter-spacing:.08em;}
.sec-body{padding:8px 13px;}

/* ── Fields ── */
.fl{font-size:7px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px;}
.fv{font-size:10.5px;font-weight:600;color:#0f172a;}
.fv.lg{font-size:13px;font-weight:800;}
.fv.blu{color:#0369a1;font-weight:800;}
.fv.grn{color:#059669;font-weight:800;}

/* ── Guest card ── */
.guest-card{background:linear-gradient(135deg,#f0f7ff,#e8f4fd);border:1.5px solid #93c5fd;border-radius:8px;padding:9px 13px;}
.guest-name{font-size:13px;font-weight:900;color:#0c4a6e;margin-bottom:3px;}
.guest-meta{display:flex;flex-wrap:wrap;gap:6px;}
.guest-meta-item{display:flex;align-items:center;gap:3px;font-size:9.5px;color:#0369a1;font-weight:600;}
.guest-pax{display:inline-flex;align-items:center;gap:3px;background:#0369a1;color:#fff;border-radius:20px;padding:2px 8px;font-size:8.5px;font-weight:700;margin-top:4px;}

/* ── Boat card ── */
.boat-card{background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #93c5fd;border-radius:8px;padding:8px 12px;display:flex;align-items:center;gap:10px;}
.boat-emoji{font-size:2rem;line-height:1;flex-shrink:0;}
.boat-details{flex:1;}
.boat-name{font-size:13px;font-weight:900;color:#0c4a6e;margin-bottom:3px;}
.boat-meta{display:flex;gap:6px;flex-wrap:wrap;}
.boat-tag{display:inline-flex;align-items:center;gap:3px;background:rgba(14,165,233,.12);border:1px solid #93c5fd;border-radius:20px;padding:2px 8px;font-size:8.5px;font-weight:700;color:#0369a1;}

/* ── Route ── */
.route-vis{display:flex;align-items:stretch;gap:0;margin:4px 0 0;}
.route-ghat{flex:1;background:#eff6ff;border:1.5px solid #93c5fd;border-radius:7px;padding:7px 10px;text-align:center;}
.route-ghat-lbl{font-size:7px;font-weight:700;color:#0369a1;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px;}
.route-ghat-name{font-size:11px;font-weight:800;color:#0c4a6e;}
.route-mid{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:0 8px;flex-shrink:0;}
.route-arrow{font-size:1.1rem;color:#0ea5e9;line-height:1;}

/* ── Add-ons ── */
.addon-grid{display:flex;flex-wrap:wrap;gap:3px;}
.addon-pill{display:inline-flex;align-items:center;gap:3px;background:#eff6ff;border:1px solid #bae6fd;border-radius:20px;padding:2px 8px;font-size:8.5px;font-weight:700;color:#0369a1;}

/* ══ PAYMENT ══ */
.pay-receipt{border:1.5px solid #dde8f0;border-radius:8px;overflow:hidden;margin-bottom:7px;}
.pay-receipt-head{background:linear-gradient(135deg,#0c4a6e,#075985);padding:6px 15px;display:flex;align-items:center;justify-content:space-between;}
.pay-receipt-title{color:#fff;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;}
.pr-row{display:flex;align-items:center;justify-content:space-between;padding:6px 15px;border-bottom:1px solid #f1f5f9;}
.pr-row:last-child{border-bottom:none;}
.pr-row.total-row{background:#f8fafc;border-top:1.5px solid #dde8f0;}
.pr-row.paid-row {background:#f0fdf4;}
.pr-row.due-row  {background:#fff7ed;}
.pr-lbl{font-size:9.5px;font-weight:600;color:#475569;display:flex;align-items:center;gap:5px;}
.pr-lbl-icon{width:16px;height:16px;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:8px;flex-shrink:0;}
.pr-val{font-size:11px;font-weight:800;color:#0f172a;}
.pr-val.disc {color:#f59e0b;}
.pr-val.final{color:#2563eb;font-size:13px;}
.pr-val.paid {color:#059669;font-size:13px;}
.pr-val.due  {color:#d97706;font-size:13px;}
.pr-val.zero {color:#059669;font-size:13px;}
.pr-sub{font-size:7.5px;color:#94a3b8;font-weight:400;display:block;margin-top:1px;}

/* ══ TERMS ══ */
.terms-grid{display:grid;grid-template-columns:1fr 1fr;gap:2px 12px;}
.terms-grid li{font-size:8px;color:#475569;padding:2px 0 2px 11px;position:relative;line-height:1.4;list-style:none;}
.terms-grid li::before{content:'✓';position:absolute;left:0;color:#0ea5e9;font-weight:800;font-size:8.5px;}

/* ══ FOOTER — always at bottom via flex ══ */
.ftr{background:linear-gradient(135deg,#0c4a6e 0%,#075985 100%);padding:10px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-shrink:0;}
.ftr-thanks{color:#fff;font-size:10.5px;font-weight:800;margin-bottom:2px;}
.ftr-line{color:rgba(255,255,255,.5);font-size:8px;line-height:1.75;}
.ftr-sig{text-align:right;}
.ftr-sig-line{width:90px;border-top:1.5px solid rgba(255,255,255,.3);margin:0 0 4px auto;}
.ftr-sig-name{color:rgba(255,255,255,.85);font-size:9px;font-weight:700;}
.ftr-sig-sub{color:rgba(255,255,255,.4);font-size:7.5px;}
.powered{background:#0a3d5c;text-align:center;padding:3px 0;color:rgba(255,255,255,.25);font-size:7.5px;letter-spacing:.06em;flex-shrink:0;}

/* ══ PRINT ══ */
@media print{
  body{background:#fff;}
  .no-print{display:none!important;}
  @page{margin:0;size:A4 portrait;}
  .voucher{box-shadow:none;width:210mm;height:297mm;}
}
@media(max-width:700px){
  body{background:#1e293b;}
  .voucher{
    width:100% !important;
    height:auto !important;
    min-height:unset !important;
    transform-origin:top left;
  }
  .powered{font-size:8px;padding:8px 12px;}
}
</style>
</head>
<body>

@php
  $paidAmt  = (float)($booking->payments_sum_amount ?? 0);
  if (!$paidAmt && $booking->relationLoaded('payments')) {
      $paidAmt = (float)$booking->payments->sum('amount');
  }
  $finalAmt  = (float)$booking->final_amount;
  $totalAmt  = (float)$booking->total_amount;
  $discAmt   = (float)($booking->total_discount ?? 0);
  $dueAmt    = max(0, $finalAmt - $paidAmt);
  $payStatus = $booking->payment_status ?? ($dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'unpaid'));
  $bkStatus  = $booking->booking_status ?? 'confirmed';

  $boatEmojis = ['Normal Motor Boat'=>'🚤','Light Motor Boat'=>'⛵','Premium Light Motor Boat'=>'⚡','Luxury Mini Yacht'=>'🛥','Bajra Boat'=>'🛶','Cruise'=>'🚢'];
  $boatName   = optional(optional($booking->boat)->boatType)->name ?? 'Boat';
  $boatEmoji  = $boatEmojis[$boatName] ?? '⛵';

  $adults   = (int)($booking->adults ?? $booking->no_of_person ?? 1);
  $children = (int)($booking->children ?? 0);

  $addonMap = ['decoration'=>'🌸 Decoration','photographer'=>'📸 Photographer','live_music'=>'🎵 Live Music','priest'=>'🪔 Priest/Puja','flowers'=>'💐 Flowers','fireworks'=>'🎆 Fireworks'];
  $activeAddons = [];
  foreach (array_keys($addonMap) as $k) {
      if (!empty($booking->$k)) { $activeAddons[] = $addonMap[$k]; }
  }

  $logoFile = websiteSetupValue('logo');
  $logoUrl  = $logoFile ? url('backend/admin/website_setup/' . $logoFile) : null;

  $bkDate     = $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') : now()->format('d M, Y');
  $pickupTime = $booking->pickup_time  ? \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A')   : '—';
  $dropTime   = $booking->drop_time    ? \Carbon\Carbon::parse($booking->drop_time)->format('h:i A')     : '—';

  $duration = '—';
  if ($booking->pickup_time && $booking->drop_time) {
      $start = \Carbon\Carbon::parse($booking->pickup_time);
      $end   = \Carbon\Carbon::parse($booking->drop_time);
      if ($end->lt($start)) { $end->addDay(); }
      $mins  = $start->diffInMinutes($end);
      $hrs   = intdiv($mins, 60);
      $rem   = $mins % 60;
      if ($hrs > 0 && $rem > 0)       { $duration = $hrs . 'h ' . $rem . 'min'; }
      elseif ($hrs > 0)               { $duration = $hrs . ' hr' . ($hrs > 1 ? 's' : ''); }
      else                            { $duration = $rem . ' min'; }
  }

  $payColors = ['paid'=>'#059669','partial'=>'#2563eb','unpaid'=>'#dc2626'];
  $payColor  = $payColors[$payStatus] ?? '#94a3b8';
@endphp

{{-- Toolbar --}}
<div class="no-print" style="background:#0c4a6e;padding:10px 16px;display:flex;align-items:center;gap:10px;position:sticky;top:0;z-index:999;flex-wrap:wrap;">
  <a href="{{ route('boat-booking.show', $booking->booking_id) }}" style="color:rgba(255,255,255,.6);font-size:12px;text-decoration:none;display:flex;align-items:center;gap:4px;white-space:nowrap;">← Back</a>
  <span style="color:rgba(255,255,255,.25);font-size:11px;font-family:monospace;flex:1;text-align:center;">{{ $booking->booking_id }}</span>
  <button onclick="window.print()" style="background:rgba(255,255,255,.12);color:#fff;border:1.5px solid rgba(255,255,255,.25);border-radius:8px;padding:8px 16px;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap;">🖨 Print</button>
  <a href="{{ route('boat-booking.voucher.pdf', $booking->booking_id) }}"
     style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;white-space:nowrap;box-shadow:0 3px 12px rgba(14,165,233,.4);text-decoration:none;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    Download Voucher PDF
  </a>
</div>

<div class="voucher">

{{-- ══ HEADER ══ --}}
<div class="hdr">
  <div class="hdr-top">
    <div class="hdr-logo">
      @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="Visit Kashi">
      @else
        <div class="hdr-logo-fb">⛵</div>
      @endif
      <div>
        <div class="hdr-co-tagline">Varanasi's Most Trusted Travel Company</div>
        <div class="hdr-co-addr">B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010</div>
      </div>
    </div>
  </div>

  <div class="hdr-band">
    <div>
      <div class="hdr-band-type">⛵ Boat Booking Confirmation Voucher</div>
      <div class="hdr-band-id">{{ $booking->booking_id }}</div>
    </div>
    <div>
      <div class="hdr-band-date-lbl">Booking Date</div>
      <div class="hdr-band-date-val">{{ $bkDate }}</div>
    </div>
    <div>
      <span class="hdr-badge {{ $payStatus }}">
        {{ ucfirst($bkStatus) }} &nbsp;—&nbsp; {{ ucfirst($payStatus) }}
      </span>
    </div>
  </div>
</div>

{{-- ══ BODY ══ --}}
<div class="body">

  {{-- Guest & Booking Info --}}
  <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:10px;margin-bottom:7px;">

    <div class="guest-card">
      <div style="font-size:8px;font-weight:700;color:#0369a1;text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px;">👤 Guest Information</div>
      <div class="guest-name">{{ $booking->name }}</div>
      <div class="guest-meta">
        <span class="guest-meta-item">📞 {{ $booking->phone }}</span>
        @if($booking->email)
        <span class="guest-meta-item">✉️ {{ $booking->email }}</span>
        @endif
      </div>
      <div style="margin-top:5px;display:flex;gap:5px;flex-wrap:wrap;">
        <span class="guest-pax">👥 {{ $booking->no_of_person }} Total Persons</span>
        <span class="guest-pax" style="background:#0284c7;">🧑 {{ $adults }} Adult{{ $adults != 1 ? 's' : '' }}</span>
        <span class="guest-pax" style="background:{{ $children > 0 ? '#059669' : '#94a3b8' }};">👦 {{ $children }} {{ $children != 1 ? 'Children' : 'Child' }} (Free)</span>
      </div>
      @if($booking->guest_notes)
      <div style="margin-top:6px;background:rgba(255,255,255,.6);border-radius:5px;padding:5px 8px;font-size:9.5px;color:#0369a1;">
        📝 {{ $booking->guest_notes }}
      </div>
      @endif
    </div>

    <div class="sec" style="margin-bottom:0;">
      <div class="sec-head">
        <div class="sec-icon" style="background:#0ea5e9;">📋</div>
        <div class="sec-title">Booking Details</div>
      </div>
      <div class="sec-body">
        <div style="margin-bottom:8px;">
          <div class="fl">Booking Type</div>
          <div class="fv">{{ $booking->booking_type ?? '—' }}</div>
        </div>
        <div>
          <div class="fl">Event on Boat</div>
          <div class="fv">{{ $booking->event_on_boat ?? 'Regular Ride' }}</div>
        </div>
      </div>
    </div>

  </div>

  {{-- Boat Details --}}
  <div class="sec">
    <div class="sec-head">
      <div class="sec-icon" style="background:#0ea5e9;">⛵</div>
      <div class="sec-title">Boat Details</div>
    </div>
    <div class="sec-body">
      <div class="boat-card">
        <div class="boat-emoji">{{ $boatEmoji }}</div>
        <div class="boat-details">
          <div class="boat-name">{{ $boatName }}</div>
          <div style="margin-bottom:5px;">
            <span style="background:#0369a1;color:#fff;border-radius:20px;padding:2px 9px;font-size:9px;font-weight:700;">⛵ 84 Ghat Boat Ride</span>
          </div>
          <div class="boat-meta">
            @if($booking->booking_type)
            <span class="boat-tag">🕐 {{ $booking->booking_type }}</span>
            @endif
            @if($booking->event_on_boat && $booking->event_on_boat !== 'Regular Ride')
            <span class="boat-tag">🎉 {{ $booking->event_on_boat }}</span>
            @endif
          </div>
          @if(count($activeAddons) > 0)
          <div style="margin-top:6px;" class="addon-grid">
            @foreach($activeAddons as $addon)
            <span class="addon-pill">{{ $addon }}</span>
            @endforeach
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Route & Timing --}}
  <div class="sec">
    <div class="sec-head">
      <div class="sec-icon" style="background:#0ea5e9;">🗺</div>
      <div class="sec-title">Route & Timing</div>
    </div>
    <div class="sec-body">
      <div class="route-vis">
        <div class="route-ghat">
          <div class="route-ghat-lbl">📍 Boarding Ghat</div>
          <div class="route-ghat-name">{{ $booking->boarding_ghat ?? '—' }}</div>
          <div style="margin-top:5px;display:inline-flex;align-items:center;gap:4px;background:#0369a1;color:#fff;border-radius:20px;padding:3px 10px;font-size:9px;font-weight:700;">
            🕐 {{ $pickupTime }}
          </div>
        </div>
        <div class="route-mid">
          <div class="route-arrow">⟶</div>
          <div style="font-size:8.5px;font-weight:700;color:#059669;margin-top:3px;background:#d1fae5;border-radius:20px;padding:2px 7px;">⏱ {{ $duration }}</div>
        </div>
        <div class="route-ghat">
          <div class="route-ghat-lbl">📍 Drop Ghat</div>
          <div class="route-ghat-name">{{ $booking->drop_ghat ?? '—' }}</div>
          <div style="margin-top:5px;display:inline-flex;align-items:center;gap:4px;background:#0369a1;color:#fff;border-radius:20px;padding:3px 10px;font-size:9px;font-weight:700;">
            🕐 {{ $dropTime }}
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Payment Summary --}}
  @php $pc = ['paid'=>'#059669','partial'=>'#2563eb','unpaid'=>'#dc2626']; $payColor = $pc[$payStatus] ?? '#94a3b8'; @endphp
  <div class="pay-receipt">
    <div class="pay-receipt-head">
      <div class="pay-receipt-title">💳 Payment Summary</div>
      <span style="background:{{ $payColor }};color:#fff;padding:3px 12px;border-radius:20px;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.05em;">{{ ucfirst($payStatus) }}</span>
    </div>
    <div>
      <div class="pr-row">
        <div class="pr-lbl">
          <div class="pr-lbl-icon" style="background:#dbeafe;">💰</div>
          Total Booking Amount
          @if($discAmt > 0)<span class="pr-sub" style="color:#94a3b8;display:inline;margin-top:0;margin-left:3px;">(before discount)</span>@endif
        </div>
        <div class="pr-val">₹{{ number_format($totalAmt, 2) }}</div>
      </div>
      @if($discAmt > 0)
      <div class="pr-row">
        <div class="pr-lbl">
          <div class="pr-lbl-icon" style="background:#fef3c7;">🏷</div>
          Discount Applied
        </div>
        <div class="pr-val disc">− ₹{{ number_format($discAmt, 2) }}</div>
      </div>
      <div class="pr-row total-row">
        <div class="pr-lbl" style="font-weight:700;color:#0f172a;">
          <div class="pr-lbl-icon" style="background:#dbeafe;">📊</div>
          <strong>Final Booking Amount</strong>
          <span class="pr-sub" style="color:#0369a1;display:inline;margin-top:0;margin-left:3px;">(after discount)</span>
        </div>
        <div class="pr-val final">₹{{ number_format($finalAmt, 2) }}</div>
      </div>
      @endif
      <div class="pr-row paid-row">
        <div class="pr-lbl" style="color:#065f46;">
          <div class="pr-lbl-icon" style="background:#d1fae5;">✓</div>
          <strong>Amount Paid</strong>
          @if($booking->relationLoaded('payments') && $booking->payments->count() > 0)
          <span class="pr-sub" style="color:#059669;display:inline;margin-top:0;margin-left:3px;">{{ $booking->payments->count() }} payment(s) received</span>
          @endif
        </div>
        <div class="pr-val paid">₹{{ number_format($paidAmt, 2) }}</div>
      </div>
      @if($dueAmt > 0)
      <div class="pr-row due-row" style="border-bottom:none;">
        <div class="pr-lbl" style="color:#92400e;">
          <div class="pr-lbl-icon" style="background:#fef3c7;">⚠</div>
          <strong>Balance Due</strong>
          <span class="pr-sub" style="color:#d97706;display:inline;margin-top:0;margin-left:3px;">Please clear before boarding</span>
        </div>
        <div class="pr-val due">₹{{ number_format($dueAmt, 2) }}</div>
      </div>
      @endif
    </div>
  </div>

  {{-- Terms & Conditions --}}
  <div class="sec" style="margin-top:auto;margin-bottom:0;">
    <div class="sec-head">
      <div class="sec-icon" style="background:#64748b;">📜</div>
      <div class="sec-title">Terms & Conditions</div>
    </div>
    <div class="sec-body">
      <ul class="terms-grid">
        <li><strong>Wear Life Jackets</strong> before start of boat ride. Follow <strong>Jal Police Safety Guidelines</strong>.</li>
        <li>Report at boarding ghat <strong>15 minutes before</strong> pickup time.</li>
        <li>Balance payment must be cleared before boarding the boat.</li>
        <li>No refunds for no-shows. Cancellation charges apply.</li>
        <li>Schedule may change due to weather or government orders.</li>
        <li>Children under 10 are free; must be accompanied by adult.</li>
        <li>Voucher is non-transferable; valid only for named guest(s).</li>
        <li>Support: <strong>7080109917 | 7080109918 | 7080109919</strong></li>
      </ul>
    </div>
  </div>

</div>{{-- /body --}}

{{-- ══ FOOTER — pinned to bottom via flex ══ --}}
<div class="ftr">
  <div>
    <div class="ftr-thanks">Thank you for choosing Visit Kashi! 🙏</div>
    <div class="ftr-line">📞 7080109917 &nbsp;|&nbsp; 7080109918 &nbsp;|&nbsp; 7080109919</div>
    <div class="ftr-line">✉️ help.visitkashi@gmail.com &nbsp;·&nbsp; 🌐 www.visitkashi.in</div>
    <div class="ftr-line">B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010</div>
  </div>
  <div class="ftr-sig">
    <div style="height:28px;"></div>
    <div class="ftr-sig-line"></div>
    <div class="ftr-sig-name">Authorized Signatory</div>
    <div class="ftr-sig-sub">Visit Kashi Travel Services</div>
    @if($createdBy)
    <div style="color:rgba(255,255,255,.5);font-size:8px;margin-top:2px;text-align:right;">
      Created by: <strong style="color:rgba(255,255,255,.7);">{{ $createdBy->name }}</strong>
    </div>
    @endif
  </div>
</div>
<div class="powered">
  Powered by VisitKashi CRM &nbsp;·&nbsp; Generated on {{ now()->format('d M Y, h:i A') }}
</div>

</div>{{-- /voucher --}}

</body>
</html>
