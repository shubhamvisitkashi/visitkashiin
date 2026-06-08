<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Voucher — {{ $booking->booking_id }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size:11px; color:#0f172a; background:#fff; }
page { page-break-after:always; }

/* ── Header ── */
.hdr {
  background: #0c4a6e;
  padding:0;
  position:relative;
}
.hdr-top {
  background: #0c4a6e;
  padding:22px 28px 16px;
}
.hdr-logo-row { display:table; width:100%; }
.hdr-logo-cell { display:table-cell; vertical-align:middle; }
.hdr-logo-name { color:#fff; font-size:16px; font-weight:bold; }
.hdr-logo-tag  { color:rgba(255,255,255,.7); font-size:9px; margin-top:2px; }
.hdr-logo-addr { color:rgba(255,255,255,.45); font-size:8px; margin-top:2px; }
.hdr-logo-img  { height:44px; border-radius:8px; }

.hdr-band {
  background: rgba(0,0,0,.3);
  border-top: 1px solid rgba(255,255,255,.12);
  padding:10px 28px;
  display:table; width:100%;
}
.hdr-band-cell { display:table-cell; vertical-align:middle; }
.hdr-band-type { color:rgba(255,255,255,.55); font-size:8px; font-weight:bold; text-transform:uppercase; letter-spacing:2px; }
.hdr-band-id   { color:#fff; font-size:18px; font-weight:bold; font-family: monospace; }
.hdr-band-right { text-align:right; }
.hdr-band-dlbl { color:rgba(255,255,255,.5); font-size:7.5px; text-transform:uppercase; }
.hdr-band-dval { color:#bae6fd; font-size:11px; font-weight:bold; }

.hdr-badge-paid    { background:#059669; color:#fff; padding:4px 14px; border-radius:20px; font-size:9px; font-weight:bold; }
.hdr-badge-partial { background:#2563eb; color:#fff; padding:4px 14px; border-radius:20px; font-size:9px; font-weight:bold; }
.hdr-badge-unpaid  { background:#dc2626; color:#fff; padding:4px 14px; border-radius:20px; font-size:9px; font-weight:bold; }

/* ── Body ── */
.body { padding:12px 24px 8px; }

/* ── Section ── */
.sec { border:1px solid #dde8f0; border-radius:6px; margin-bottom:9px; overflow:hidden; }
.sec-head { background:#eaf4fb; border-bottom:1px solid #dde8f0; padding:5px 13px; }
.sec-title { font-size:9px; font-weight:bold; color:#0c4a6e; text-transform:uppercase; letter-spacing:1.5px; }
.sec-body  { padding:10px 14px; }

/* ── Two-col table ── */
.two-col { width:100%; }
.two-col td { width:50%; vertical-align:top; padding:4px 0; }
.fl { font-size:7px; font-weight:bold; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:2px; }
.fv { font-size:11px; font-weight:600; color:#0f172a; }
.fv-lg { font-size:13px; font-weight:bold; }
.fv-blu { color:#0369a1; font-weight:bold; }
.fv-grn { color:#059669; font-weight:bold; }

/* ── Guest card ── */
.guest-card { background:#f0f7ff; border:1.5px solid #93c5fd; border-radius:6px; padding:10px 13px; }
.guest-name { font-size:14px; font-weight:bold; color:#0c4a6e; margin-bottom:4px; }
.pax-pill { background:#0369a1; color:#fff; border-radius:20px; padding:2px 9px; font-size:8.5px; font-weight:bold; display:inline-block; margin-right:5px; margin-top:3px; }

/* ── Boat card ── */
.boat-card { background:#eff6ff; border:1.5px solid #93c5fd; border-radius:6px; padding:9px 13px; }
.boat-name { font-size:13px; font-weight:bold; color:#0c4a6e; margin-bottom:5px; }
.boat-tag  { background:rgba(14,165,233,.12); border:1px solid #93c5fd; border-radius:20px; padding:2px 9px; font-size:8.5px; font-weight:bold; color:#0369a1; display:inline-block; margin-right:4px; margin-bottom:2px; }

/* ── Route ── */
.route-table { width:100%; border-collapse:collapse; }
.route-ghat { background:#eff6ff; border:1.5px solid #93c5fd; border-radius:6px; padding:8px 10px; text-align:center; width:42%; }
.route-arrow-cell { text-align:center; padding:0 8px; color:#0ea5e9; font-size:14px; }
.route-lbl { font-size:7px; font-weight:bold; color:#0369a1; text-transform:uppercase; letter-spacing:1px; margin-bottom:3px; }
.route-name { font-size:12px; font-weight:bold; color:#0c4a6e; }
.time-pill  { background:#0369a1; color:#fff; border-radius:20px; padding:2px 8px; font-size:8.5px; font-weight:bold; display:inline-block; margin-top:4px; }
.dur-pill   { background:rgba(14,165,233,.15); color:#0369a1; border-radius:20px; padding:2px 8px; font-size:8px; font-weight:bold; display:inline-block; }

/* ── Payment ── */
.pay-head { background:#0f172a; padding:9px 15px; }
.pay-head-title { color:rgba(255,255,255,.65); font-size:8px; font-weight:bold; text-transform:uppercase; letter-spacing:2px; }
.pay-row { width:100%; border-collapse:collapse; }
.pay-row td { padding:6px 15px; font-size:10.5px; border-bottom:1px solid #f1f5f9; }
.pay-row tr:last-child td { border-bottom:none; }
.pay-lbl { color:#64748b; }
.pay-val { text-align:right; font-weight:bold; color:#0f172a; }
.pay-val-grn { color:#059669; }
.pay-val-red { color:#dc2626; }
.pay-val-disc { color:#f59e0b; }

/* ── Add-ons ── */
.addon-pill { background:#f0f7ff; border:1px solid #93c5fd; border-radius:20px; padding:3px 10px; font-size:8.5px; font-weight:bold; color:#0369a1; display:inline-block; margin:2px 3px 2px 0; }

/* ── Footer ── */
.footer { background:#0c4a6e; padding:10px 28px; margin-top:10px; }
.footer-txt { color:rgba(255,255,255,.6); font-size:8px; text-align:center; }
.footer-name { color:#fff; font-size:10px; font-weight:bold; text-align:center; margin-bottom:3px; }

/* ── Decorative bg circles ── */
.bg-circle-1 { position:absolute; top:-50px; right:-50px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.06); }
.bg-circle-2 { position:absolute; bottom:-30px; left:40px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,.04); }

/* ── Terms ── */
.terms-list { padding-left:14px; margin:0; }
.terms-list li { font-size:8px; color:#64748b; padding:1.5px 0; }
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
      $duration = $hrs > 0 && $rem > 0 ? "{$hrs}h {$rem}min" : ($hrs > 0 ? "{$hrs}hr" : "{$rem}min");
  }

  $payColors = ['paid'=>'#059669','partial'=>'#2563eb','unpaid'=>'#dc2626'];
  $payColor  = $payColors[$payStatus] ?? '#94a3b8';
@endphp

{{-- ══ HEADER ══ --}}
<div class="hdr">
  {{-- Decorative circles --}}
  <div class="bg-circle-1"></div>
  <div class="bg-circle-2"></div>

  <div class="hdr-top">
    <table style="width:100%;" cellpadding="0" cellspacing="0">
      <tr>
        <td style="vertical-align:middle;">
          @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="Visit Kashi" class="hdr-logo-img">
          @else
            <div class="hdr-logo-name">VisitKashi</div>
          @endif
          <div class="hdr-logo-tag">Varanasi's Most Trusted Travel Company</div>
          <div class="hdr-logo-addr">B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010</div>
        </td>
        <td style="text-align:right; vertical-align:middle;">
          <span class="hdr-badge-{{ $payStatus }}">
            {{ strtoupper(($booking->booking_status ?? 'CONFIRMED')) }} &mdash; {{ strtoupper($payStatus) }}
          </span>
        </td>
      </tr>
    </table>
  </div>

  <div class="hdr-band">
    <table style="width:100%;" cellpadding="0" cellspacing="0">
      <tr>
        <td style="vertical-align:middle;">
          <div class="hdr-band-type">Boat Booking Confirmation Voucher</div>
          <div class="hdr-band-id">{{ $booking->booking_id }}</div>
        </td>
        <td style="text-align:center; vertical-align:middle;">
          <div class="hdr-band-dlbl">Booking Date</div>
          <div class="hdr-band-dval">{{ $bkDate }}</div>
        </td>
        <td style="text-align:right; vertical-align:middle;">
          <div class="hdr-band-dlbl">Contact</div>
          <div class="hdr-band-dval">+91 9335079798</div>
        </td>
      </tr>
    </table>
  </div>
</div>

{{-- ══ BODY ══ --}}
<div class="body">

  {{-- Guest + Booking side by side --}}
  <table style="width:100%; border-collapse:collapse; margin-bottom:9px;">
    <tr>
      <td style="width:55%; vertical-align:top; padding-right:8px;">
        <div class="sec">
          <div class="sec-head"><div class="sec-title">Guest Information</div></div>
          <div class="sec-body">
            <div class="guest-card">
              <div class="guest-name">{{ $booking->name }}</div>
              @if($booking->phone)
              <div style="color:#0369a1; font-size:10px; font-weight:600; margin-bottom:4px;">&#9990; {{ $booking->phone }}</div>
              @endif
              <span class="pax-pill">{{ $adults + $children }} Total Persons</span>
              <span class="pax-pill">{{ $adults }} Adults</span>
              @if($children > 0)<span class="pax-pill">{{ $children }} Children</span>@endif
            </div>
          </div>
        </div>
      </td>
      <td style="width:45%; vertical-align:top;">
        <div class="sec">
          <div class="sec-head"><div class="sec-title">Booking Details</div></div>
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

  {{-- Boat Details --}}
  <div class="sec">
    <div class="sec-head"><div class="sec-title">Boat Details</div></div>
    <div class="sec-body">
      <div class="boat-card">
        <div class="boat-name">{{ $boatName }}</div>
        <div>
          @if($booking->boarding_ghat)
          <span class="boat-tag">&#9975; {{ $booking->boarding_ghat }}</span>
          @endif
          @if($booking->booking_type)
          <span class="boat-tag">&#8987; {{ $booking->booking_type }}</span>
          @endif
          @if($booking->event_on_boat)
          <span class="boat-tag">&#127973; {{ $booking->event_on_boat }}</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Route & Timing --}}
  <div class="sec">
    <div class="sec-head"><div class="sec-title">Route &amp; Timing</div></div>
    <div class="sec-body">
      <table class="route-table" cellpadding="0" cellspacing="0">
        <tr>
          <td class="route-ghat">
            <div class="route-lbl">&#128205; Boarding Ghat</div>
            <div class="route-name">{{ $booking->boarding_ghat ?? '—' }}</div>
            <span class="time-pill">&#9200; {{ $pickupTime }}</span>
          </td>
          <td class="route-arrow-cell">
            <div class="dur-pill">&#11137; {{ $duration }}</div>
            <div style="font-size:18px; color:#0ea5e9; margin-top:4px;">&#10230;</div>
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

  {{-- Add-ons (if any) --}}
  @if(count($activeAddons) > 0)
  <div class="sec">
    <div class="sec-head"><div class="sec-title">Add-on Services</div></div>
    <div class="sec-body">
      @foreach($activeAddons as $ao)
        <span class="addon-pill">&#10003; {{ $ao }}</span>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Payment Summary --}}
  <div class="sec">
    <div class="pay-head">
      <table style="width:100%;" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="pay-head-title">Payment Summary</span></td>
          <td style="text-align:right;">
            <span class="hdr-badge-{{ $payStatus }}">{{ strtoupper($payStatus) }}</span>
          </td>
        </tr>
      </table>
    </div>
    <table class="pay-row" cellpadding="0" cellspacing="0">
      <tr>
        <td class="pay-lbl">Total Booking Amount</td>
        <td class="pay-val">&#8377;{{ number_format($totalAmt, 2) }}</td>
      </tr>
      @if($discAmt > 0)
      <tr>
        <td class="pay-lbl">Discount Applied</td>
        <td class="pay-val pay-val-disc">- &#8377;{{ number_format($discAmt, 2) }}</td>
      </tr>
      <tr>
        <td class="pay-lbl" style="font-weight:bold; color:#0f172a;">Final Booking Amount</td>
        <td class="pay-val" style="color:#0369a1; font-size:12px;">&#8377;{{ number_format($finalAmt, 2) }}</td>
      </tr>
      @endif
      <tr>
        <td class="pay-lbl pay-val-grn" style="font-weight:bold;">
          Amount Paid
          @if($booking->relationLoaded('payments') && $booking->payments->count() > 0)
          <span style="font-weight:400; font-size:9px;">&nbsp;({{ $booking->payments->count() }} payment(s))</span>
          @endif
        </td>
        <td class="pay-val pay-val-grn">&#8377;{{ number_format($paidAmt, 2) }}</td>
      </tr>
      @if($dueAmt > 0)
      <tr style="background:#fff8f0;">
        <td class="pay-lbl pay-val-red" style="font-weight:bold;">Balance Due</td>
        <td class="pay-val pay-val-red" style="font-size:12px;">&#8377;{{ number_format($dueAmt, 2) }}</td>
      </tr>
      @endif
    </table>
  </div>

  {{-- Notes (if any) --}}
  @if($booking->guest_notes)
  <div class="sec">
    <div class="sec-head"><div class="sec-title">Guest Notes</div></div>
    <div class="sec-body" style="font-size:10px; color:#475569;">{{ $booking->guest_notes }}</div>
  </div>
  @endif

  {{-- Terms --}}
  <div class="sec" style="margin-bottom:0;">
    <div class="sec-head"><div class="sec-title">Terms &amp; Conditions</div></div>
    <div class="sec-body">
      <ul class="terms-list">
        <li>Please report at the boarding ghat at least 15 minutes before departure.</li>
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
  <div class="footer-name">VisitKashi — Varanasi's Most Trusted Travel Company</div>
  <div class="footer-txt">
    B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010 &nbsp;|&nbsp;
    +91 9335079798 &nbsp;|&nbsp; info@visitkashi.in &nbsp;|&nbsp;
    Generated: {{ now()->format('d M Y, h:i A') }}
  </div>
</div>

</body>
</html>
