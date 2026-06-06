<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Booking Confirmation — {{ $booking->booking_number }}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:-apple-system,'Segoe UI',Roboto,Arial,sans-serif;background:#EAECF0;color:#1a1a1a;font-size:14px;line-height:1.5;}

/* ── Screen Bar ── */
.screen-bar{background:#1C1C1E;padding:10px 24px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
.screen-bar-title{color:#fff;font-size:.85rem;font-weight:600;}
.s-btn{display:inline-flex;align-items:center;gap:5px;padding:7px 16px;border-radius:6px;font-size:.78rem;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:opacity .15s;white-space:nowrap;}
.s-btn:hover{opacity:.82;}
.s-btn-back{background:rgba(255,255,255,.12);color:#fff;}
.s-btn-wa{background:#25D366;color:#fff;}
.s-btn-print{background:#fff;color:#1C1C1E;}

/* ── Wrapper ── */
.wrap{max-width:800px;margin:24px auto 40px;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.08),0 8px 32px rgba(0,0,0,.07);display:flex;flex-direction:column;}
.body{flex:1;}

/* ── Brand Header ── */
.bh{padding:24px 28px;display:flex;align-items:center;justify-content:space-between;gap:20px;background:linear-gradient(135deg,#1A2B4C 0%,#2D3A8C 100%);border-bottom:none;}
.bh-left{display:flex;flex-direction:column;gap:0;}
.bh-logo{height:56px;max-width:180px;object-fit:contain;display:block;filter:brightness(0) invert(1);}
.bh-unit{font-size:.75rem;font-weight:600;color:rgba(255,255,255,.65);margin-top:6px;letter-spacing:.01em;}
.bh-right{text-align:right;}
.bh-brand-name{font-size:1rem;font-weight:800;color:#fff;margin-bottom:4px;letter-spacing:-.01em;}
.bh-contact-line{font-size:.8rem;color:rgba(255,255,255,.85);margin-bottom:4px;line-height:1.6;font-weight:500;}
.bh-contact-line:last-child{margin-bottom:0;}
.bh-addr{font-size:.76rem;color:rgba(255,255,255,.55);margin-bottom:6px;line-height:1.6;max-width:280px;}

/* ── Confirmation Strip ── */
.confirm{background:#F0FDF4;border-bottom:1px solid #BBF7D0;padding:18px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;}
.confirm-left{display:flex;align-items:center;gap:14px;}
.confirm-icon{width:40px;height:40px;background:#16A34A;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0;}
.confirm-title{font-size:1.05rem;font-weight:700;color:#14532D;}
.confirm-sub{font-size:.74rem;color:#166534;margin-top:2px;}
.confirm-right{text-align:right;}
.confirm-ref-lbl{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#4B5563;margin-bottom:3px;}
.confirm-ref-val{font-size:1.15rem;font-weight:800;color:#111;font-family:'Courier New',monospace;letter-spacing:.05em;}
.confirm-meta{font-size:.7rem;color:#6B7280;margin-top:4px;}
.status-pill{display:inline-block;padding:3px 12px;border-radius:20px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;}
.st-confirmed{background:#DCFCE7;color:#166534;}
.st-pending  {background:#FEF9C3;color:#854D0E;}
.st-completed{background:#DBEAFE;color:#1E40AF;}
.st-cancelled{background:#FEE2E2;color:#991B1B;}
.st-assigned {background:#EDE9FE;color:#5B21B6;}

/* ── Journey Section ── */
.journey{padding:22px 28px;border-bottom:1px solid #EBEBEB;}
.journey-label{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9CA3AF;margin-bottom:14px;}
.journey-row{display:flex;align-items:flex-start;gap:0;}
.jp{flex:1;min-width:0;}
.jp-tag{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9CA3AF;margin-bottom:5px;display:flex;align-items:center;gap:5px;}
.jp-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.jp-dot-pick{background:#4F46E5;}
.jp-dot-drop{background:#DC2626;}
.jp-city{font-size:.98rem;font-weight:700;color:#111;margin-bottom:3px;}
.jp-addr{font-size:.76rem;color:#6B7280;line-height:1.4;}
.jp-dt{font-size:.75rem;font-weight:600;color:#4F46E5;margin-top:6px;}
.jp-return{font-size:.74rem;font-weight:600;color:#7C3AED;margin-top:4px;}
.jm{display:flex;flex-direction:column;align-items:center;padding:6px 22px;gap:3px;flex-shrink:0;}
.jm-line{width:1px;height:18px;background:repeating-linear-gradient(180deg,#CBD5E1 0,#CBD5E1 4px,transparent 4px,transparent 8px);}
.jm-arr{color:#94A3B8;font-size:.9rem;transform:rotate(90deg);display:block;}
.jm-badge{font-size:.64rem;font-weight:700;color:#4F46E5;background:#EEF2FF;padding:3px 10px;border-radius:20px;white-space:nowrap;margin:4px 0;text-align:center;}
.jm-km{font-size:.64rem;color:#9CA3AF;text-align:center;}

/* ── Trip Info Bar ── */
.trip-bar{background:#F9FAFB;padding:12px 28px;border-bottom:1px solid #EBEBEB;display:flex;gap:28px;flex-wrap:wrap;}
.tb-item{}
.tb-lbl{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9CA3AF;margin-bottom:2px;}
.tb-val{font-size:.82rem;font-weight:600;color:#111;}

/* ── Body ── */
.body{display:block;background:#F5F5F7;}

/* ── Cards ── */
.card{background:#fff;border:1px solid #E5E7EB;border-radius:10px;margin-bottom:12px;overflow:hidden;}
.card-head{padding:11px 16px;border-bottom:1px solid #F3F4F6;display:flex;align-items:center;gap:8px;background:#FAFAFA;}
.card-head-ico{font-size:.9rem;}
.card-head-title{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#374151;}
.card-body{padding:16px;}

.main{padding:16px 28px 16px 28px;}

/* ── Detail Grid ── */
.dg{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.di-lbl{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#9CA3AF;margin-bottom:3px;}
.di-val{font-size:.84rem;font-weight:600;color:#1F2937;}
.di-val.accent{color:#2563EB;}

/* ── Fare Table ── */
.ft{width:100%;border-collapse:collapse;}
.ft td{padding:7px 0;font-size:.82rem;border-bottom:1px solid #F3F4F6;color:#374151;vertical-align:top;}
.ft tr:last-child td{border-bottom:none;}
.ft .fa{text-align:right;font-weight:600;}
.ft .fd td{color:#D97706;}
.ft .ftot td{font-weight:800;font-size:.92rem;color:#1a1a1a;padding-top:11px;border-top:2px solid #E5E7EB;border-bottom:none;}
.ft .ftot .fa{color:#16A34A;font-size:1.05rem;}

/* ── Payment Cards ── */
.pay-boxes{display:flex;gap:8px;margin-bottom:10px;}
.pb{flex:1;border-radius:8px;padding:11px 12px;text-align:center;}
.pb.tot {background:#EEF2FF;border:1px solid #C7D2FE;}
.pb.paid{background:#F0FDF4;border:1px solid #BBF7D0;}
.pb.bal {background:#FEF2F2;border:1px solid #FECACA;}
.pb-lbl{font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6B7280;margin-bottom:5px;}
.pb-val{font-size:1rem;font-weight:800;}
.pb.tot  .pb-val{color:#4338CA;}
.pb.paid .pb-val{color:#15803D;}
.pb.bal  .pb-val{color:#DC2626;}
.paid-full{background:#F0FDF4;border:1px solid #86EFAC;border-radius:8px;padding:9px 12px;text-align:center;font-size:.78rem;font-weight:700;color:#166534;margin-top:6px;}
.pmt-hist-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px dashed #F3F4F6;font-size:.76rem;}
.pmt-hist-row:last-child{border-bottom:none;}
.pmt-dt{color:#6B7280;}
.pmt-am{font-weight:700;color:#15803D;}

/* ── Preference Pills ── */
.pref-grid{display:flex;flex-wrap:wrap;gap:7px;margin-top:4px;}
.pref-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:20px;font-size:.73rem;font-weight:700;border:1px solid;}
.pref-on {background:#F0FDF4;color:#166534;border-color:#86EFAC;}
.pref-off{background:#F9FAFB;color:#9CA3AF;border-color:#E5E7EB;text-decoration:line-through;opacity:.6;}
.pref-detail{font-size:.75rem;color:#374151;background:#F3F4F6;border-radius:8px;padding:6px 11px;display:flex;align-items:center;gap:6px;margin-top:6px;}

/* ── Terms ── */
.terms{background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:13px 16px;margin-bottom:12px;}
.terms-ttl{font-size:.68rem;font-weight:700;color:#92400E;margin-bottom:7px;display:flex;align-items:center;gap:5px;}
.terms ul{margin-left:14px;}
.terms li{font-size:.73rem;color:#78350F;margin-bottom:3px;line-height:1.5;}

/* ── Footer ── */
.inv-footer{background:#fff;border-top:1px solid #EBEBEB;padding:14px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
.footer-left{display:flex;align-items:center;gap:10px;}
.footer-logo{height:28px;max-width:90px;object-fit:contain;opacity:.6;}
.footer-brand{font-size:.75rem;font-weight:600;color:#374151;}
.footer-right{font-size:.68rem;color:#9CA3AF;text-align:right;line-height:1.7;}

.watermark{text-align:center;padding:10px;font-size:.65rem;color:#C4C4C4;background:#F9FAFB;}

@media print{

  /* ══ PAGE SETUP — A4 with balanced margins ══ */
  @page {
    size: A4 portrait;
    margin: 10mm 12mm 12mm 12mm;   /* top right bottom left */
  }

  /* ── Base reset ── */
  *  { -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; }
  html,body { background:#fff !important; font-size:11.5px; }

  /* ── Hide screen chrome ── */
  .screen-bar { display:none !important; }

  /* ── Wrapper fills page content area ── */
  .wrap {
    width:100%;
    max-width:100%;
    margin:0;
    border-radius:0;
    box-shadow:none;
    background:#fff;
  }

  /* ── Brand header ── */
  .bh { padding:12px 16px; }
  .bh-logo { height:36px; }
  .bh-unit { font-size:.68rem; margin-top:4px; }
  .bh-brand-name { font-size:.86rem; }
  .bh-contact-line { font-size:.68rem; margin-bottom:2px; }
  .bh-addr { font-size:.64rem; margin-bottom:4px; }

  /* ── Confirmation strip ── */
  .confirm { padding:9px 16px; gap:10px; }
  .confirm-icon { width:28px; height:28px; font-size:.85rem; }
  .confirm-title { font-size:.88rem; }
  .confirm-sub { font-size:.64rem; }
  .confirm-ref-lbl { font-size:.56rem; }
  .confirm-ref-val { font-size:.9rem; }
  .confirm-meta { font-size:.62rem; margin-top:3px; }
  .status-pill { font-size:.6rem; padding:2px 8px; }

  /* ── Journey section ── */
  .journey { padding:10px 16px; }
  .journey-label { font-size:.58rem; margin-bottom:10px; }
  .jp-city { font-size:.84rem; }
  .jp-addr { font-size:.66rem; }
  .jp-dt,.jp-return { font-size:.66rem; margin-top:4px; }
  .jm { padding:4px 14px; }
  .jm-badge { font-size:.6rem; padding:2px 7px; }
  .jm-km { font-size:.6rem; }
  .jm-line { height:14px; }

  /* ── Trip info bar ── */
  .trip-bar { padding:7px 16px; gap:16px; }
  .tb-lbl { font-size:.56rem; margin-bottom:1px; }
  .tb-val { font-size:.74rem; }

  /* ── Body / main ── */
  .body { background:#F5F5F7 !important; padding:0; }
  .main { padding:10px 14px; }

  /* ── Cards ── */
  .card { margin-bottom:7px; break-inside:avoid; page-break-inside:avoid; border-radius:7px; }
  .card-head { padding:6px 12px; }
  .card-head-ico { font-size:.82rem; }
  .card-head-title { font-size:.62rem; letter-spacing:.04em; }
  .card-body { padding:9px 12px; }

  /* ── Detail grid ── */
  .dg { gap:7px 10px; }
  .di-lbl { font-size:.56rem; margin-bottom:2px; }
  .di-val { font-size:.74rem; }

  /* ── Fare table ── */
  .ft td { padding:4px 0; font-size:.72rem; }
  .ft .ftot td { font-size:.8rem; padding-top:7px; }

  /* ── Payment boxes ── */
  .pay-boxes { gap:6px; margin-bottom:7px; }
  .pb { padding:8px 10px; border-radius:6px; }
  .pb-lbl { font-size:.54rem; margin-bottom:3px; }
  .pb-val { font-size:.9rem; }

  /* ── Preferences ── */
  .pref-grid { gap:5px; }
  .pref-pill { font-size:.62rem; padding:3px 8px; }
  .pref-detail { font-size:.66rem; padding:5px 9px; margin-top:5px; }

  /* ── Terms ── */
  .terms { padding:9px 12px; margin-bottom:7px; break-inside:avoid; }
  .terms-ttl { font-size:.64rem; margin-bottom:5px; }
  .terms li { font-size:.64rem; margin-bottom:2px; line-height:1.45; }

  /* ── Footer ── */
  .inv-footer { padding:9px 16px; }
  .footer-logo { height:20px; }
  .footer-brand { font-size:.68rem; }
  .footer-right { font-size:.6rem; line-height:1.6; }

  /* ── Watermark ── */
  .watermark { font-size:.56rem; padding:5px; }

}
@media(max-width:620px){
  .main{padding:12px 16px;}
  .bh,.journey,.trip-bar{padding:14px 16px;}
  .confirm{padding:14px 16px;}
  .journey-row{flex-direction:column;gap:12px;}
  .jm{flex-direction:row;padding:0;gap:8px;}
  .jm-line{width:18px;height:1px;}
  .jm-arr{transform:none;}
  .dg{grid-template-columns:1fr;}
  .pay-boxes{flex-direction:column;}
}
</style>
</head>
<body>

{{-- Screen Actions --}}
<div class="screen-bar">
  <div class="screen-bar-title">Cab Booking — {{ $booking->booking_number }}</div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="{{ route('cab-bookings.show', $booking->id) }}" class="s-btn s-btn-back">← Back</a>
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
    @endphp
    <a href="https://wa.me/91{{ preg_replace('/\D/','',$booking->customer_phone) }}?text={{ $waMsg }}"
       class="s-btn s-btn-wa" target="_blank">📱 WhatsApp Guest</a>
    <button onclick="window.print()" class="s-btn s-btn-print">🖨️ Print / PDF</button>
  </div>
</div>

<div class="wrap">

  {{-- Brand Header --}}
  <div class="bh">
    <div class="bh-left">
      <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('logo')) }}"
           alt="Visit Kashi" class="bh-logo"
           onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
      <div style="display:none;font-size:1.4rem;font-weight:900;color:#1a1a1a;letter-spacing:-.02em;">Visit Kashi</div>
      <div class="bh-unit">A Unit of Albino Stay Pvt Ltd</div>
    </div>
    <div class="bh-right">
      <div class="bh-brand-name">Visit Kashi</div>
      @if(websiteSetupValue('address'))
      <div class="bh-addr">{{ websiteSetupValue('address') }}</div>
      @endif
      <div class="bh-contact-line">📞 {{ websiteSetupValue('contact_number') }} &nbsp;·&nbsp; {{ websiteSetupValue('whats_app_number') }}</div>
      <div class="bh-contact-line">✉️ {{ websiteSetupValue('email') }}</div>
    </div>
  </div>

  {{-- Confirmation Strip --}}
  <div class="confirm">
    <div class="confirm-left">
      <div class="confirm-icon">✓</div>
      <div>
        <div class="confirm-title">Booking Confirmed!</div>
        <div class="confirm-sub">Your cab has been successfully booked · {{ $booking->pickup_date->format('d M Y') }}</div>
      </div>
    </div>
    <div class="confirm-right">
      <div class="confirm-ref-lbl">Booking Reference</div>
      <div class="confirm-ref-val">{{ $booking->booking_number }}</div>
      <div class="confirm-meta">
        <span class="status-pill st-{{ $booking->booking_status }}">{{ ucfirst($booking->booking_status) }}</span>
        &nbsp; Issued {{ $booking->created_at->format('d M Y') }}
      </div>
    </div>
  </div>

  {{-- Journey --}}
  <div class="journey">
    {{-- Trip Name --}}
    @if($booking->trip_type)
    <div style="font-size:1.05rem;font-weight:800;color:#1a1a1a;margin-bottom:4px;letter-spacing:-.01em;">{{ $booking->trip_type }}</div>
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
    <div class="tb-item">
      <div class="tb-lbl">Vehicle</div>
      <div class="tb-val">{{ $booking->vehicle_name }}</div>
    </div>
    @if($booking->seating_capacity)
    <div class="tb-item">
      <div class="tb-lbl">Capacity</div>
      <div class="tb-val">{{ $booking->seating_capacity }} Seats</div>
    </div>
    @endif
    @php
      $adults   = $booking->no_of_adults   ?? 0;
      $children = $booking->no_of_children ?? 0;
    @endphp
    @if($adults || $children)
    <div class="tb-item">
      <div class="tb-lbl">Passengers</div>
      <div class="tb-val">
        {{ $adults ? $adults . ' Adult' . ($adults != 1 ? 's' : '') : '' }}
        {{ ($adults && $children) ? ' + ' : '' }}
        {{ $children ? $children . ' Child' . ($children != 1 ? 'ren' : '') : '' }}
      </div>
    </div>
    @endif
    <div class="tb-item">
      <div class="tb-lbl">Total Days</div>
      <div class="tb-val">{{ $booking->total_days }} Day{{ $booking->total_days > 1 ? 's' : '' }}</div>
    </div>
    @if($booking->total_km)
    <div class="tb-item">
      <div class="tb-lbl">Distance</div>
      <div class="tb-val">{{ number_format($booking->total_km, 0) }} km</div>
    </div>
    @endif
  </div>

  {{-- Body --}}
  <div class="body">

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
  </div>{{-- /body --}}

  {{-- Footer --}}
  <div class="inv-footer">
    <div class="footer-left">
      <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('logo')) }}"
           alt="Visit Kashi" class="footer-logo">
    </div>
    <div class="footer-right">
      <div>Created By: {{ optional($booking->createdBy)->name ?? 'Admin' }}</div>
      <div>Booking ID: {{ $booking->booking_number }}</div>
    </div>
  </div>

  <div class="watermark">Computer-generated booking confirmation. No signature required. &nbsp;·&nbsp; Visit Kashi © {{ date('Y') }}</div>

</div>{{-- /wrap --}}
</body>
</html>
