@extends('admin.layouts.app')
@section('content')
<style>
.tcf-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.tcf-actions{display:flex;gap:10px;margin-bottom:18px;margin-top:50px;flex-wrap:wrap;align-items:center;}
.tcf-actions a,.tcf-actions button{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;text-decoration:none;border:1px solid #000;transition:.2s;}
.tcf-btn-pdf{background:#000;color:#fff;}
.tcf-btn-pdf:hover{opacity:.85;color:#fff;}
.tcf-btn-print{background:#000;color:#fff;}
.tcf-btn-print:hover{opacity:.85;color:#fff;}
.tcf-btn-back{background:#fff;color:#000;}
.tcf-btn-back:hover{background:#F1F5F9;}

* { box-sizing:border-box; }

/* ── Confirmation document — minimal black & white bordered style ── */
.invoice-box{background:#fff;max-width:850px;margin:0 auto;border:1px solid #000;font-size:13px;color:#000;}

.center{text-align:center;}
.bold{font-weight:bold;}

/* Company header */
.co-header{padding:16px 20px;text-align:center;border-bottom:1px solid #000;}
.co-header h1{font-size:1.6rem;font-weight:800;margin-bottom:6px;}
.co-header p{font-weight:bold;font-size:.85rem;margin-bottom:2px;}
.co-header .co-addr{max-width:620px;margin:0 auto 2px;}

/* Title row */
.title-row{display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-bottom:1px solid #000;gap:10px;}
.title-row .title{flex:1;text-align:center;font-weight:bold;font-size:.95rem;}
.title-row .side{font-size:.82rem;font-weight:bold;white-space:nowrap;}

/* Status row */
.status-row{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:center;padding:10px 20px;border-bottom:1px solid #000;font-size:.82rem;font-weight:bold;}
.status-pill{border:1px solid #000;border-radius:14px;padding:3px 12px;}

/* Two column info grid */
.info-grid{display:grid;grid-template-columns:1fr 1fr;border-bottom:1px solid #000;}
.info-col{padding:12px 20px;}
.info-col+.info-col{border-left:1px solid #000;}
.info-line{margin-bottom:5px;font-size:.85rem;}
.info-line .lbl{font-weight:bold;}

/* Generic bordered section */
.tcf2-section{padding:12px 20px;border-bottom:1px solid #000;}
.tcf2-section .lbl{font-weight:bold;font-size:.85rem;display:block;margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em;}
.tcf2-section .pkg-text{font-size:.85rem;line-height:1.6;margin-bottom:8px;}
.tcf2-section .pkg-chips{display:flex;flex-wrap:wrap;gap:6px;}
.pkg-chip{border:1px solid #000;border-radius:14px;padding:3px 12px;font-size:.78rem;font-weight:bold;}

/* Itinerary */
.itin-text{font-size:.83rem;line-height:1.7;}
.itin-text h4{font-size:.9rem;font-weight:bold;margin:8px 0 4px;}
.itin-text h4:first-child{margin-top:0;}
.itin-text p{margin:4px 0;}
.itin-text ul{margin:2px 0 8px;padding-left:18px;}
.itin-text li{margin-bottom:2px;}

/* Services / description tables */
table.desc-table{width:100%;border-collapse:collapse;}
table.desc-table th,table.desc-table td{border-bottom:1px solid #000;padding:8px 14px;font-size:.85rem;text-align:left;vertical-align:top;}
table.desc-table thead th{font-weight:bold;}
table.desc-table tbody tr:last-child td{border-bottom:none;}
table.desc-table .row-sub{font-size:.78rem;color:#333;margin-top:2px;}

/* Payment totals */
table.totals-table{width:100%;border-collapse:collapse;}
table.totals-table td{padding:8px 20px;font-size:.85rem;border-bottom:1px solid #000;}
table.totals-table tr:last-child td{border-bottom:none;}
table.totals-table tr td:first-child{font-weight:bold;}
table.totals-table tr td:last-child{text-align:right;}
table.totals-table tr.grand td{font-size:1rem;}

/* Plain bordered list section */
.tcf2-list{margin:0;padding:0;list-style:none;font-size:.82rem;line-height:1.8;}
.tcf2-list li{padding:2px 0;}
.tcf2-list li::before{content:'• ';font-weight:bold;}

.thanks{text-align:center;font-weight:bold;padding:14px 20px;border-bottom:1px solid #000;}
.powered{text-align:center;font-weight:bold;padding:10px 20px;}

@media print{
  .tcf-page{background:#fff;padding:0;}
  .tcf-actions{display:none !important;}
  .invoice-box{border:1px solid #000;max-width:100%;}
  .tcf-print-hide{display:none !important;}
}
</style>

<div class="tcf-page">

  {{-- Action buttons --}}
  <div class="tcf-actions">
    <a href="{{ route('tour-booking.view', $booking->id) }}" class="tcf-btn-back">
      ← Booking Details
    </a>
    <a href="{{ route('tour-booking.pdf', $booking->id) }}" class="tcf-btn-pdf" target="_blank">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Download PDF
    </a>
    <button onclick="window.print()" class="tcf-btn-print">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Print
    </button>
    @if(session('success'))
    <span style="background:#fff;color:#000;border:1px solid #000;border-radius:8px;padding:7px 14px;font-size:.8rem;font-weight:700;">
      ✅ {{ session('success') }}
    </span>
    @endif
  </div>

  {{-- Confirmation Document --}}
  <div class="invoice-box">

    @php
      // Extract package name: first segment of short_plan before ' | '
      $pkgRaw  = $booking->lead->short_plan ?? '';
      $pkgName = trim(explode('|', $pkgRaw)[0]);
      if (!$pkgName) {
          $pkgName = $booking->quotation?->items?->first()?->notes ?? 'Tour Package';
      }
    @endphp
    @php
      // Parse service data for heading summary
      $hdRaw  = $booking->quotation?->notes ?? $booking->lead?->notes ?? '';
      $hdData = ($hdRaw && substr(trim($hdRaw), 0, 1) === '{') ? (json_decode($hdRaw, true) ?? []) : [];
      // Hotels: new format has hotels[], old format has hotel{hotel_name}
      if (!empty($hdData['hotels']) && is_array($hdData['hotels'])) {
          $hdHotels = $hdData['hotels'];
      } elseif (!empty($hdData['hotel'])) {
          $oldH = $hdData['hotel'];
          $n = trim($oldH['name'] ?? $oldH['hotel_name'] ?? '');
          $hdHotels = $n ? [[
              'name'    => $n,
              'city'    => $oldH['city'] ?? '',
              'checkin' => $oldH['checkin']  ?? $oldH['hotel_checkin']  ?? '',
              'checkout'=> $oldH['checkout'] ?? $oldH['hotel_checkout'] ?? '',
              'nights'  => $oldH['nights']   ?? $oldH['hotel_nights']   ?? '',
          ]] : [];
      } else {
          $hdHotels = [];
      }

      $hdCab  = $hdData['cab']  ?? null;
      $hdBoat = $hdData['boat'] ?? null;

      // Build chips from JSON service data (new bookings)
      $hdChips = [];
      foreach ($hdHotels as $hh) {
          $hName = trim($hh['name'] ?? $hh['hotel_name'] ?? '');
          $hCity = trim($hh['city'] ?? '');
          if ($hName) $hdChips[] = ['icon'=>'🏨','label'=> $hName.($hCity?' ('.$hCity.')':'')];
      }
      if (!empty($hdCab['cab_type'])) {
          $cLabel = $hdCab['cab_type'].(!empty($hdCab['cab_route'])?' · '.$hdCab['cab_route']:'');
          $hdChips[] = ['icon'=>'🚕','label'=> $cLabel];
      }
      if (!empty($hdBoat['boat_type'])) {
          $bLabel = $hdBoat['boat_type'].(!empty($hdBoat['boat_ride'])?' · '.$hdBoat['boat_ride']:'');
          $hdChips[] = ['icon'=>'⛵','label'=> $bLabel];
      }

      // Fallback: parse inclusions from short_plan for older bookings with no JSON
      if (empty($hdChips)) {
          $sp = $booking->lead?->short_plan ?? '';
          $inclStr = '';
          if (preg_match('/Incl:\s*(.+?)(\s*\||$)/iu', $sp, $m)) {
              $inclStr = trim($m[1]);
          } elseif (!empty($hdData['inclusions'])) {
              $inclStr = $hdData['inclusions'];
          }
          if ($inclStr) {
              foreach (array_filter(array_map('trim', explode(',', $inclStr))) as $inc) {
                  $hdChips[] = ['icon'=>'','label'=>trim($inc)];
              }
          }
      }

      $startDate = $booking->lead->booking_start_date ? \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M Y') : null;
      $endDate   = $booking->lead->booking_end_date   ? \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M Y')   : null;
      $pax       = $booking->lead->pax ?? null;
    @endphp

    {{-- Company header --}}
    <div class="co-header">
      <h1>{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }} - {{ $pkgName }}</h1>
      <p class="co-addr">Address: {{ websiteSetupValue('address') ?? 'Varanasi, Uttar Pradesh, India' }}</p>
      <p>Support: 7080109917, 7080109918, 7080109919</p>
      <p>Email: {{ websiteSetupValue('email') ?? 'info@visitkashi.in' }}</p>
    </div>

    {{-- Title row --}}
    <div class="title-row">
      <div class="side">{{ $booking->booking_number }}</div>
      <div class="title">Tour Booking Confirmation</div>
      <div class="side">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</div>
    </div>

    {{-- Status row --}}
    <div class="status-row">
      <span class="status-pill">✓ Confirmed</span>
      @if($startDate)
      <span class="status-pill">📅 {{ $startDate }}{{ $endDate ? ' → '.$endDate : '' }}</span>
      @endif
      @if($pax)
      <span class="status-pill">👥 {{ $pax }} Guest{{ $pax != 1 ? 's' : '' }}</span>
      @endif
    </div>

    {{-- Guest Details --}}
    <div class="info-grid">
      <div class="info-col">
        <div class="info-line"><span class="lbl">Guest Name:</span> {{ $booking->lead->guest_name ?? '—' }}</div>
        <div class="info-line"><span class="lbl">Mobile Number:</span> +91 {{ $booking->lead->contact ?? '—' }}</div>
        @if(!empty($booking->lead->email))
        <div class="info-line"><span class="lbl">Email Address:</span> {{ $booking->lead->email }}</div>
        @endif
      </div>
      <div class="info-col">
        @if(!empty($booking->lead->pax))
        <div class="info-line"><span class="lbl">Number of Guests:</span> {{ $booking->lead->pax }} Person(s)</div>
        @endif
        @if(!empty($booking->lead->booking_start_date))
        <div class="info-line"><span class="lbl">Tour Start Date:</span> {{ \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M, Y') }}</div>
        @endif
        @if(!empty($booking->lead->booking_end_date))
        <div class="info-line"><span class="lbl">Tour End Date:</span> {{ \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M, Y') }}</div>
        @endif
      </div>
    </div>

    {{-- Tour Package Details --}}
    <div class="tcf2-section">
      <span class="lbl">Tour Package Details</span>
      <div class="pkg-text">{{ $booking->lead->short_plan ?? '—' }}</div>
      @if(count($hdChips) > 0)
      <div class="pkg-chips">
        @foreach($hdChips as $chip)
        <span class="pkg-chip">{{ $chip['icon'] }} {{ $chip['label'] }}</span>
        @endforeach
      </div>
      @endif
    </div>

    {{-- Itinerary --}}
    @if(!empty($booking->lead->plan_detail))
    <div class="tcf2-section">
      <span class="lbl">Itinerary</span>
      <div class="itin-text">{!! safe_html($booking->lead->plan_detail) !!}</div>
    </div>
    @endif

    {{-- Services Included — reuse already-parsed $hdData/$hdHotels/$hdCab/$hdBoat --}}
    @php
      $hotels     = $hdHotels;
      $cab        = $hdCab;
      $boat       = $hdBoat;
      $guide      = $hdData['guide'] ?? null;
      $hasServices = !empty($hotels) || !empty($cab['cab_type']) || !empty($boat['boat_type']) || !empty($guide['guide_name']);
    @endphp

    @if($hasServices)
    <div class="tcf2-section" style="padding:0;">
      <table class="desc-table">
        <thead>
          <tr>
            <th style="border-top:none;">Service</th>
            <th style="border-top:none;">Details</th>
            <th style="border-top:none;">Date(s)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($hotels as $h)
          @php
            $hName   = $h['name']      ?? $h['hotel_name']     ?? '';
            $hCity   = $h['city']      ?? '';
            $hRoom   = $h['room_type'] ?? '';
            $hCi     = !empty($h['checkin'])       ? \Carbon\Carbon::parse($h['checkin'])->format('d M Y')       : (!empty($h['hotel_checkin'])  ? \Carbon\Carbon::parse($h['hotel_checkin'])->format('d M Y') : '');
            $hCo     = !empty($h['checkout'])      ? \Carbon\Carbon::parse($h['checkout'])->format('d M Y')      : (!empty($h['hotel_checkout']) ? \Carbon\Carbon::parse($h['hotel_checkout'])->format('d M Y') : '');
            $hNights = $h['nights'] ?? $h['hotel_nights'] ?? '';
          @endphp
          @if($hName || $hCity)
          <tr>
            <td>🏨 Hotel / Stay{{ $hCity ? ' ('.$hCity.')' : '' }}</td>
            <td>
              {{ $hName ?: '—' }}
              @if($hRoom)<div class="row-sub">{{ $hRoom }}</div>@endif
            </td>
            <td>{{ $hCi ?: '—' }} → {{ $hCo ?: '—' }}{{ $hNights ? ' ('.$hNights.' night'.($hNights!=1?'s':'').')' : '' }}</td>
          </tr>
          @endif
          @endforeach

          @if(!empty($cab['cab_type']) || !empty($cab['cab_route']))
          <tr>
            <td>🚕 Cab / Transport</td>
            <td>
              {{ $cab['cab_type'] ?? '—' }}
              @if(!empty($cab['cab_route']))<div class="row-sub">{{ $cab['cab_route'] }}</div>@endif
            </td>
            <td>
              @if(!empty($cab['cab_from'])) {{ \Carbon\Carbon::parse($cab['cab_from'])->format('d M Y') }} @endif
              @if(!empty($cab['cab_from']) && !empty($cab['cab_to'])) → @endif
              @if(!empty($cab['cab_to'])) {{ \Carbon\Carbon::parse($cab['cab_to'])->format('d M Y') }} @endif
              @if(empty($cab['cab_from']) && empty($cab['cab_to'])) — @endif
            </td>
          </tr>
          @endif

          @if(!empty($boat['boat_type']) || !empty($boat['boat_ride']))
          <tr>
            <td>⛵ Boat / River Ride</td>
            <td>
              {{ $boat['boat_type'] ?? '—' }}
              @if(!empty($boat['boat_ride']))<div class="row-sub">{{ $boat['boat_ride'] }}</div>@endif
            </td>
            <td>
              @if(!empty($boat['boat_date'])) {{ \Carbon\Carbon::parse($boat['boat_date'])->format('d M Y') }} @endif
              @if(!empty($boat['boat_time'])) &nbsp;·&nbsp; {{ date('g:i A', strtotime($boat['boat_time'])) }} @endif
              @if(empty($boat['boat_date']) && empty($boat['boat_time'])) — @endif
            </td>
          </tr>
          @endif

          @if(!empty($guide['guide_name']))
          <tr>
            <td>🧭 Guide</td>
            <td>
              {{ $guide['guide_name'] }}
              @if(!empty($guide['guide_lang']))<div class="row-sub">{{ $guide['guide_lang'] }}</div>@endif
            </td>
            <td>
              @if(!empty($guide['guide_from'])) {{ \Carbon\Carbon::parse($guide['guide_from'])->format('d M Y') }} @endif
              @if(!empty($guide['guide_from']) && !empty($guide['guide_to'])) → @endif
              @if(!empty($guide['guide_to'])) {{ \Carbon\Carbon::parse($guide['guide_to'])->format('d M Y') }} @endif
              @if(empty($guide['guide_from']) && empty($guide['guide_to'])) — @endif
            </td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
    @endif

    {{-- Payment Summary --}}
    <table class="totals-table">
      <tr>
        <td>Total Booking Amount</td>
        <td>₹{{ number_format($booking->total_amount, 0) }}</td>
      </tr>
      @if($booking->discount_amount > 0)
      <tr>
        <td>Discount</td>
        <td>− ₹{{ number_format($booking->discount_amount, 0) }}</td>
      </tr>
      @endif
      <tr>
        <td>Amount Paid</td>
        <td>₹{{ number_format($booking->paid_amount ?? $booking->payments_sum_amount ?? 0, 0) }}</td>
      </tr>
      @php $due = max(0, $booking->total_amount - ($booking->paid_amount ?? $booking->payments_sum_amount ?? 0)); @endphp
      <tr class="grand">
        <td>Balance Due</td>
        <td>{{ $due > 0 ? '₹'.number_format($due,0) : '✅ Fully Paid' }}</td>
      </tr>
      @if($booking->payments->isNotEmpty())
      <tr>
        <td>Payment Method</td>
        <td>{{ ucfirst(str_replace('_',' ',$booking->payments->first()->payment_method ?? 'Cash')) }}</td>
      </tr>
      @endif
    </table>

    {{-- Cancellation & Refund Policy --}}
    <div class="tcf2-section">
      <span class="lbl">Cancellation &amp; Refund Policy</span>
      <ul class="tcf2-list">
        <li>Cancellation <strong>30+ days</strong> before departure — <strong>Full refund</strong> (minus processing fee of ₹500)</li>
        <li>Cancellation <strong>15–29 days</strong> before departure — <strong>75% refund</strong> of total booking amount</li>
        <li>Cancellation <strong>7–14 days</strong> before departure — <strong>50% refund</strong> of total booking amount</li>
        <li>Cancellation <strong>3–6 days</strong> before departure — <strong>25% refund</strong> of total booking amount</li>
        <li>Cancellation <strong>within 48 hours</strong> or No-show — <strong>No refund</strong></li>
        <li>Refunds are processed within <strong>7–10 working days</strong> via the original payment method</li>
        <li>In case of natural calamities, strikes or Government orders — full credit note will be issued for future travel</li>
        <li>Date changes (reschedule) are allowed <strong>once</strong> free of charge if requested <strong>7+ days</strong> before departure</li>
      </ul>
    </div>

    {{-- Important Notes --}}
    <div class="tcf2-section">
      <span class="lbl">Important Information</span>
      <ul class="tcf2-list">
        <li>Carry a valid government-issued ID proof during travel</li>
        <li>Booking is subject to availability and confirmed only after payment</li>
        <li>Contact us at <strong>7080109917 | 7080109918 | 7080109919</strong> for any changes or assistance</li>
        <li>This is a computer-generated confirmation — no signature required</li>
      </ul>
    </div>

    <div class="thanks">Thank you for choosing {{ websiteSetupValue('site_name') ?? 'Visit Kashi' }} — We look forward to serving you!</div>
    <div class="powered">Powered By Visit Kashi</div>

  </div>{{-- /invoice-box --}}
</div>
@endsection
