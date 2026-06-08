<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Voucher — {{ $booking->booking_id }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
html, body {
  width: 210mm;
  height: 297mm;
  font-family: DejaVu Sans, Arial, sans-serif;
  font-size: 10px;
  color: #1e293b;
  background: #ffffff;
}

/* ── Full-page wrapper ── */
.page {
  width: 210mm;
  min-height: 297mm;
  display: table;
  background: #ffffff;
}

/* ══ HEADER ══ */
.hdr {
  background-color: #0c4a6e;
  width: 100%;
  display: block;
}
.hdr-main {
  padding: 20px 28px 14px;
  background-color: #0c4a6e;
  position: relative;
}
.hdr-circle-1 {
  position: absolute; top: -30px; right: -30px;
  width: 140px; height: 140px; border-radius: 50%;
  background: rgba(255,255,255,.06);
}
.hdr-circle-2 {
  position: absolute; bottom: -20px; left: 60px;
  width: 90px; height: 90px; border-radius: 50%;
  background: rgba(255,255,255,.04);
}
.hdr-logo-row { width: 100%; }
.hdr-logo-row td { vertical-align: middle; }
.logo-name { color: #ffffff; font-size: 18px; font-weight: bold; letter-spacing: -.5px; }
.logo-tag  { color: rgba(255,255,255,.7); font-size: 8.5px; margin-top: 2px; }
.logo-addr { color: rgba(255,255,255,.4); font-size: 7.5px; margin-top: 2px; }
.logo-img  { height: 46px; border-radius: 8px; }

.hdr-badge-paid    { background:#059669; color:#fff; padding:5px 16px; border-radius:20px; font-size:9px; font-weight:bold; letter-spacing:.5px; display:inline-block; }
.hdr-badge-partial { background:#2563eb; color:#fff; padding:5px 16px; border-radius:20px; font-size:9px; font-weight:bold; letter-spacing:.5px; display:inline-block; }
.hdr-badge-unpaid  { background:#dc2626; color:#fff; padding:5px 16px; border-radius:20px; font-size:9px; font-weight:bold; letter-spacing:.5px; display:inline-block; }

.hdr-band {
  background: rgba(0,0,0,.28);
  border-top: 1px solid rgba(255,255,255,.1);
  padding: 10px 28px;
  width: 100%;
}
.hdr-band td { vertical-align: middle; color: #ffffff; }
.hdr-type { color: rgba(255,255,255,.55); font-size: 7.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 3px; }
.hdr-id   { font-size: 20px; font-weight: bold; letter-spacing: 1px; font-family: monospace; }
.hdr-dlbl { color: rgba(255,255,255,.5); font-size: 7px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
.hdr-dval { color: #bae6fd; font-size: 11px; font-weight: bold; }

/* ══ BODY ══ */
.body { padding: 14px 24px 10px; }

/* ══ SECTION ══ */
.sec {
  border: 1px solid #dde8f0;
  border-radius: 8px;
  margin-bottom: 10px;
  overflow: hidden;
}
.sec-head {
  background: linear-gradient(90deg, #e8f4fb, #f0f7ff);
  border-bottom: 1px solid #dde8f0;
  padding: 7px 14px;
}
.sec-title {
  font-size: 8.5px; font-weight: bold;
  color: #0c4a6e; text-transform: uppercase; letter-spacing: 1.5px;
}
.sec-body { padding: 12px 14px; }

/* ══ GUEST ══ */
.guest-card {
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  border: 1.5px solid #93c5fd;
  border-radius: 8px;
  padding: 12px 14px;
}
.guest-name { font-size: 16px; font-weight: bold; color: #0c4a6e; margin-bottom: 5px; }
.guest-phone { color: #0369a1; font-size: 11px; font-weight: bold; margin-bottom: 6px; }
.guest-pill {
  display: inline-block;
  background: #0369a1; color: #fff;
  border-radius: 20px; padding: 3px 10px;
  font-size: 8.5px; font-weight: bold;
  margin-right: 5px; margin-top: 2px;
}

/* ══ TWO-COL ══ */
.two-col { width: 100%; }
.two-col td { width: 50%; vertical-align: top; padding: 5px 0; }
.fl { font-size: 7px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px; }
.fv { font-size: 12px; font-weight: 600; color: #0f172a; }
.fv-blu { color: #0369a1; font-weight: bold; }
.fv-grn { color: #059669; font-weight: bold; }

/* ══ BOAT ══ */
.boat-card {
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  border: 1.5px solid #93c5fd;
  border-radius: 8px;
  padding: 10px 14px;
}
.boat-name { font-size: 15px; font-weight: bold; color: #0c4a6e; margin-bottom: 6px; }
.boat-tag {
  display: inline-block;
  background: rgba(14,165,233,.12); border: 1px solid #93c5fd;
  border-radius: 20px; padding: 3px 10px;
  font-size: 8.5px; font-weight: bold; color: #0369a1;
  margin-right: 5px; margin-bottom: 3px;
}

/* ══ ROUTE ══ */
.route-table { width: 100%; }
.route-ghat {
  background: #eff6ff;
  border: 1.5px solid #93c5fd;
  border-radius: 8px;
  padding: 12px 10px;
  text-align: center;
  width: 40%;
}
.route-lbl { font-size: 7px; font-weight: bold; color: #0369a1; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
.route-name { font-size: 13px; font-weight: bold; color: #0c4a6e; margin-bottom: 5px; }
.time-pill {
  display: inline-block;
  background: #0369a1; color: #fff;
  border-radius: 20px; padding: 3px 10px;
  font-size: 9px; font-weight: bold;
}
.route-mid { text-align: center; padding: 0 12px; vertical-align: middle; }
.dur-pill {
  display: inline-block;
  background: rgba(14,165,233,.15); color: #0369a1;
  border-radius: 20px; padding: 3px 10px;
  font-size: 8px; font-weight: bold; margin-bottom: 6px;
}
.route-arrow { font-size: 22px; color: #0ea5e9; }

/* ══ ADDONS ══ */
.addon-pill {
  display: inline-block;
  background: #f0f7ff; border: 1px solid #93c5fd;
  border-radius: 20px; padding: 3px 10px;
  font-size: 8.5px; font-weight: bold; color: #0369a1;
  margin: 2px 3px 2px 0;
}

/* ══ PAYMENT ══ */
.pay-head {
  background: #0f172a;
  padding: 10px 16px;
}
.pay-title-row { width: 100%; }
.pay-title-row td { vertical-align: middle; }
.pay-title { color: rgba(255,255,255,.65); font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
.pay-rows { width: 100%; border-collapse: collapse; }
.pay-rows td { padding: 8px 16px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
.pay-rows tr:last-child td { border-bottom: none; }
.pay-lbl { color: #64748b; }
.pay-val { text-align: right; font-weight: bold; color: #0f172a; }
.pay-val-grn { color: #059669; text-align: right; font-weight: bold; font-size: 13px; }
.pay-val-amb { color: #d97706; text-align: right; font-weight: bold; }
.pay-total-row td { background: #f8fafc; padding: 8px 16px; font-size: 12px; border-bottom: 1px solid #e2e8f0; }
.pay-final-row td { padding: 10px 16px; font-size: 13px; font-weight: bold; border-bottom: 1px solid #e2e8f0; }

/* ══ NOTES / TERMS ══ */
.terms-list { padding-left: 14px; margin: 0; }
.terms-list li { font-size: 8px; color: #64748b; padding: 2px 0; line-height: 1.5; }

/* ══ FOOTER ══ */
.footer {
  background: #0c4a6e;
  padding: 14px 28px;
  margin-top: 14px;
  text-align: center;
}
.footer-name { color: #ffffff; font-size: 11px; font-weight: bold; margin-bottom: 4px; }
.footer-txt  { color: rgba(255,255,255,.55); font-size: 7.5px; line-height: 1.7; }

/* ══ DIVIDER ══ */
.divider { border: none; border-top: 1.5px dashed #e2e8f0; margin: 0; }

/* ══ WATERMARK STRIP ══ */
.wm-strip {
  background: linear-gradient(90deg, #0c4a6e 0%, #075985 50%, #0369a1 100%);
  height: 5px;
  width: 100%;
  display: block;
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

  $boatName  = optional(optional($booking->boat)->boatType)->name ?? 'Boat';

  $adults   = (int)($booking->adults ?? $booking->no_of_person ?? 1);
  $children = (int)($booking->children ?? 0);
  $total_persons = $adults + $children;

  $addonMap = ['decoration'=>'Decoration','photographer'=>'Photographer','live_music'=>'Live Music','priest'=>'Priest / Puja','flowers'=>'Flowers','fireworks'=>'Fireworks'];
  $activeAddons = [];
  foreach (array_keys($addonMap) as $k) {
      if (!empty($booking->$k)) $activeAddons[] = $addonMap[$k];
  }

  $logoFile = websiteSetupValue('logo');
  $logoPath = $logoFile ? public_path('backend/admin/website_setup/'.$logoFile) : null;
  $logoSrc  = ($logoPath && file_exists($logoPath))
              ? 'data:image/'.pathinfo($logoPath, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($logoPath))
              : null;

  $bkDate     = $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') : now()->format('d M, Y');
  $pickupTime = $booking->pickup_time  ? \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A')  : '—';
  $dropTime   = $booking->drop_time    ? \Carbon\Carbon::parse($booking->drop_time)->format('h:i A')    : '—';

  $duration = '—';
  if ($booking->pickup_time && $booking->drop_time) {
      $start = \Carbon\Carbon::parse($booking->pickup_time);
      $end   = \Carbon\Carbon::parse($booking->drop_time);
      if ($end->lt($start)) $end->addDay();
      $mins = $start->diffInMinutes($end);
      $hrs  = intdiv($mins, 60); $rem = $mins % 60;
      $duration = $hrs > 0 && $rem > 0 ? "{$hrs}h {$rem}min" : ($hrs > 0 ? "{$hrs} hr" : "{$rem} min");
  }

  $paymentCount = $booking->relationLoaded('payments') ? $booking->payments->count() : 0;
@endphp

<div class="page">

{{-- Top accent strip --}}
<div class="wm-strip"></div>

{{-- ══ HEADER ══ --}}
<div class="hdr">
  <div class="hdr-main">
    <div class="hdr-circle-1"></div>
    <div class="hdr-circle-2"></div>
    <table class="hdr-logo-row" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td width="60%">
          @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="VisitKashi" class="logo-img">
          @else
            <div class="logo-name">visitKashi</div>
          @endif
          <div class="logo-tag">Varanasi's Most Trusted Travel Company</div>
          <div class="logo-addr">B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010</div>
        </td>
        <td width="40%" style="text-align:right; vertical-align:top; padding-top:4px;">
          <div class="hdr-badge-{{ $payStatus }}">
            {{ strtoupper($booking->booking_status ?? 'CONFIRMED') }} &mdash; {{ strtoupper($payStatus) }}
          </div>
        </td>
      </tr>
    </table>
  </div>

  <div class="hdr-band">
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td width="45%">
          <div class="hdr-type">Boat Booking Confirmation Voucher</div>
          <div class="hdr-id">{{ $booking->booking_id }}</div>
        </td>
        <td width="30%" style="text-align:center;">
          <div class="hdr-dlbl">Booking Date</div>
          <div class="hdr-dval">{{ $bkDate }}</div>
        </td>
        <td width="25%" style="text-align:right;">
          <div class="hdr-dlbl">Contact</div>
          <div class="hdr-dval">+91 9335079798</div>
        </td>
      </tr>
    </table>
  </div>
</div>

{{-- ══ BODY ══ --}}
<div class="body">

  {{-- Row 1: Guest + Booking Details --}}
  <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
    <tr>
      <td width="52%" style="vertical-align:top; padding-right:10px;">
        <div class="sec">
          <div class="sec-head"><div class="sec-title">&#128100; Guest Information</div></div>
          <div class="sec-body">
            <div class="guest-card">
              <div class="guest-name">{{ $booking->name }}</div>
              @if($booking->phone)
              <div class="guest-phone">&#9990; +91 {{ $booking->phone }}</div>
              @endif
              <div>
                <span class="guest-pill">{{ $total_persons }} Total Persons</span>
                <span class="guest-pill">{{ $adults }} Adults</span>
                @if($children > 0)<span class="guest-pill">{{ $children }} Children (Free)</span>@endif
              </div>
            </div>
          </div>
        </div>
      </td>
      <td width="48%" style="vertical-align:top;">
        <div class="sec">
          <div class="sec-head"><div class="sec-title">&#128203; Booking Details</div></div>
          <div class="sec-body">
            <table class="two-col" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <div class="fl">Booking Type</div>
                  <div class="fv fv-blu">{{ $booking->booking_type ?? '—' }}</div>
                </td>
                <td>
                  <div class="fl">Event on Boat</div>
                  <div class="fv">{{ $booking->event_on_boat ?? 'Regular Ride' }}</div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="fl">Booking Date</div>
                  <div class="fv">{{ $bkDate }}</div>
                </td>
                <td>
                  <div class="fl">Lead Source</div>
                  <div class="fv">{{ optional($booking->leadSource)->name ?? '—' }}</div>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </td>
    </tr>
  </table>

  {{-- Row 2: Boat Details --}}
  <div class="sec" style="margin-bottom:10px;">
    <div class="sec-head"><div class="sec-title">&#9975; Boat Details</div></div>
    <div class="sec-body">
      <div class="boat-card">
        <div class="boat-name">{{ $boatName }}</div>
        <div>
          @if($booking->boarding_ghat)
          <span class="boat-tag">&#128205; {{ $booking->boarding_ghat }}</span>
          @endif
          @if($booking->booking_type)
          <span class="boat-tag">&#9200; {{ $booking->booking_type }}</span>
          @endif
          @if($booking->event_on_boat)
          <span class="boat-tag">&#127973; {{ $booking->event_on_boat }}</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Row 3: Route & Timing --}}
  <div class="sec" style="margin-bottom:10px;">
    <div class="sec-head"><div class="sec-title">&#128508; Route &amp; Timing</div></div>
    <div class="sec-body">
      <table class="route-table" cellpadding="0" cellspacing="0">
        <tr>
          <td class="route-ghat">
            <div class="route-lbl">&#128205; Boarding Ghat</div>
            <div class="route-name">{{ $booking->boarding_ghat ?? '—' }}</div>
            <span class="time-pill">&#9200; {{ $pickupTime }}</span>
          </td>
          <td class="route-mid">
            <div class="dur-pill">&#9203; {{ $duration }}</div><br>
            <span class="route-arrow">&#10230;</span>
          </td>
          <td class="route-ghat">
            <div class="route-lbl">&#128205; Drop Ghat</div>
            <div class="route-name">{{ $booking->drop_ghat ?? '—' }}</div>
            <span class="time-pill">&#9200; {{ $dropTime }}</span>
          </td>
        </tr>
      </table>
    </div>
  </div>

  {{-- Row 4: Add-ons --}}
  @if(count($activeAddons) > 0)
  <div class="sec" style="margin-bottom:10px;">
    <div class="sec-head"><div class="sec-title">&#10024; Add-on Services</div></div>
    <div class="sec-body">
      @foreach($activeAddons as $ao)
        <span class="addon-pill">&#10003; {{ $ao }}</span>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Guest Notes --}}
  @if($booking->guest_notes)
  <div class="sec" style="margin-bottom:10px;">
    <div class="sec-head"><div class="sec-title">&#128221; Guest Notes</div></div>
    <div class="sec-body" style="font-size:10px; color:#475569; line-height:1.6;">{{ $booking->guest_notes }}</div>
  </div>
  @endif

  {{-- Row 5: Payment Summary --}}
  <div class="sec" style="margin-bottom:10px;">
    <div class="pay-head">
      <table class="pay-title-row" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td><span class="pay-title">&#128179; Payment Summary</span></td>
          <td style="text-align:right;">
            <span class="hdr-badge-{{ $payStatus }}">{{ strtoupper($payStatus) }}</span>
          </td>
        </tr>
      </table>
    </div>

    <table class="pay-rows" cellpadding="0" cellspacing="0">
      {{-- Gross total --}}
      <tr>
        <td class="pay-lbl" style="font-size:11px;">Total Booking Amount</td>
        <td class="pay-val" style="font-size:13px;">&#8377;{{ number_format($totalAmt, 2) }}</td>
      </tr>
      {{-- Discount --}}
      @if($discAmt > 0)
      <tr style="background:#fffbeb;">
        <td class="pay-lbl" style="color:#d97706;">&#127991; Discount Applied</td>
        <td class="pay-val-amb">- &#8377;{{ number_format($discAmt, 2) }}</td>
      </tr>
      {{-- Final after discount --}}
      <tr style="background:#f8fafc;">
        <td style="padding:8px 16px; font-weight:bold; color:#0f172a; font-size:12px;">Final Booking Amount</td>
        <td style="text-align:right; padding:8px 16px; font-weight:bold; font-size:13px; color:#0369a1; border-bottom:1px solid #e2e8f0;">&#8377;{{ number_format($finalAmt, 2) }}</td>
      </tr>
      @endif
      {{-- Paid --}}
      <tr style="background:#f0fdf4;">
        <td style="padding:9px 16px; color:#059669; font-weight:bold; font-size:11px;">
          &#10003; Amount Paid
          @if($paymentCount > 0)
          <span style="font-size:8.5px; font-weight:normal; color:#16a34a;">&nbsp;({{ $paymentCount }} payment{{ $paymentCount > 1 ? 's' : '' }} received)</span>
          @endif
        </td>
        <td class="pay-val-grn" style="padding:9px 16px; border-bottom:1px solid #e2e8f0;">&#8377;{{ number_format($paidAmt, 2) }}</td>
      </tr>
      {{-- Balance Due (only if > 0) --}}
      @if($dueAmt > 0)
      <tr style="background:#fff8f0;">
        <td style="padding:9px 16px; color:#dc2626; font-weight:bold; font-size:11px;">&#9888; Balance Due</td>
        <td style="text-align:right; padding:9px 16px; color:#dc2626; font-weight:bold; font-size:13px;">&#8377;{{ number_format($dueAmt, 2) }}</td>
      </tr>
      @endif
    </table>
  </div>

  {{-- Row 6: Terms & Conditions --}}
  <div class="sec">
    <div class="sec-head"><div class="sec-title">&#128220; Terms &amp; Conditions</div></div>
    <div class="sec-body">
      <ul class="terms-list">
        <li>Please report at the boarding ghat at least <strong>15 minutes</strong> before departure.</li>
        <li>Life jackets will be provided. Passengers must wear them throughout the journey.</li>
        <li>Booking is non-refundable once the boat has departed.</li>
        <li>The company is not responsible for loss of personal belongings.</li>
        <li>Passengers must follow all safety instructions given by the boatman.</li>
        <li>This voucher must be presented at the time of boarding.</li>
      </ul>
    </div>
  </div>

</div>

{{-- ══ FOOTER ══ --}}
<div class="footer">
  <div class="footer-name">VisitKashi &mdash; Varanasi's Most Trusted Travel Company</div>
  <div class="footer-txt">
    B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010
    &nbsp;|&nbsp; +91 9335079798 &nbsp;|&nbsp; info@visitkashi.in
  </div>
  <div class="footer-txt" style="margin-top:3px;">
    Generated: {{ now()->format('d M Y, h:i A') }}
    &nbsp;|&nbsp; Powered by VisitKashi CRM
  </div>
</div>

{{-- Bottom accent strip --}}
<div class="wm-strip"></div>

</div>{{-- /page --}}

</body>
</html>
