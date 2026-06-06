<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Tour Booking Confirmation — {{ $booking->booking_number }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a1a; background: #fff; }

/* ── Header ── */
.header { background: #1E1B4B; padding: 22px 30px; }

.company-name { color: #fff; font-size: 18px; font-weight: bold; margin-bottom: 4px; }
.company-addr { color: rgba(255,255,255,0.8); font-size: 10px; line-height: 1.7; }

.conf-label { color: rgba(255,255,255,0.65); font-size: 9px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
.conf-number { color: #A5F3FC; font-size: 13px; font-weight: bold; }
.conf-date   { color: rgba(255,255,255,0.65); font-size: 10px; margin-top: 3px; }

/* ── Status bar ── */
.status-bar { background: #059669; padding: 8px 30px; }
.status-text { color: #fff; font-size: 11px; font-weight: bold; }

/* ── Body ── */
.body { padding: 24px 30px; }

/* Section title */
.section-title { font-size: 10px; font-weight: bold; color: #4338CA; text-transform: uppercase;
  letter-spacing: 1px; border-bottom: 1.5px solid #C7D2FE; padding-bottom: 5px; margin-bottom: 12px; margin-top: 20px; }

/* Info grid — 2 columns table */
.info-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
.info-table td { padding: 8px 12px; width: 50%; vertical-align: top; }
.info-cell { background: #F8FAFC; border-radius: 6px; padding: 9px 12px; }
.info-lbl { font-size: 9px; font-weight: bold; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
.info-val { font-size: 12px; font-weight: bold; color: #0F172A; }

/* Package summary */
.pkg-box { background: #F5F3FF; border: 1px solid #C4B5FD; border-radius: 8px; padding: 12px 14px; margin-bottom: 12px; }
.pkg-label { font-size: 9px; font-weight: bold; color: #7C3AED; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
.pkg-text { font-size: 11px; color: #3730A3; line-height: 1.7; }

/* Itinerary */
.itin-box { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px; margin-bottom: 14px; }
.itin-label { font-size: 9px; font-weight: bold; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.itin-text { font-size: 11px; color: #334155; line-height: 1.8; }

/* Payment box */
.amt-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 14px 16px; margin-bottom: 14px; }
.amt-table { width: 100%; border-collapse: collapse; }
.amt-table tr td { padding: 6px 0; font-size: 12px; border-bottom: 1px dashed #BFDBFE; }
.amt-table tr:last-child td { border-bottom: none; }
.amt-lbl { color: #1E40AF; }
.amt-val { text-align: right; font-weight: bold; color: #1E3A8A; }
.amt-total { font-size: 14px !important; }
.amt-paid  { color: #059669 !important; }
.amt-due   { color: #DC2626 !important; }
.amt-divider { height: 0; border: none; border-bottom: 2px solid #BFDBFE; padding: 0 !important; }

/* Cancellation policy */
.policy-box { background: #FFF7ED; border: 1.5px solid #FED7AA; border-radius: 8px; padding: 14px 16px; margin-bottom: 14px; }
.policy-title { font-size: 11px; font-weight: bold; color: #C2410C; margin-bottom: 10px; }
.policy-table { width: 100%; border-collapse: collapse; }
.policy-table tr td { padding: 5px 0; font-size: 11px; color: #7C2D12; border-bottom: 1px dashed #FED7AA; vertical-align: top; }
.policy-table tr:last-child td { border-bottom: none; }
.policy-dot { width: 12px; color: #EA580C; font-weight: bold; }

/* Notes */
.notes-box { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 8px; padding: 12px 16px; margin-bottom: 0; }
.notes-title { font-size: 10px; font-weight: bold; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
.notes-list { margin: 0; padding: 0; list-style: none; }
.notes-list li { font-size: 10.5px; color: #14532D; padding: 3px 0; border-bottom: 1px dashed #BBF7D0; }
.notes-list li:last-child { border-bottom: none; }

/* Footer */
.footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 12px 30px; text-align: center; }
.footer-text { font-size: 10px; color: #64748B; line-height: 1.8; }
.footer-thanks { font-size: 10.5px; color: #475569; font-weight: bold; margin-top: 4px; }
</style>
</head>
<body>

{{-- ── Header ── --}}
@php
  $logoFile = websiteSetupValue('logo');
  $logoPath = $logoFile ? public_path('backend/admin/website_setup/' . $logoFile) : null;
  $logoExists = $logoPath && file_exists($logoPath);
@endphp
<div class="header">
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td width="60%" valign="middle">
        @if($logoExists)
        <img src="{{ $logoPath }}" alt="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}"
             style="height:48px;width:auto;max-width:220px;object-fit:contain;
                    filter:brightness(0) invert(1);margin-bottom:6px;display:block;">
        @endif
        <div class="company-name">{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}</div>
        <div class="company-addr">
          {{ websiteSetupValue('address') ?? 'Varanasi, Uttar Pradesh, India' }}<br>
          Ph: 7080109917 | 7080109918 | 7080109919
          &nbsp;|&nbsp;
          Email: {{ websiteSetupValue('email') ?? 'info@visitkashi.in' }}
        </div>
      </td>
      <td width="40%" valign="middle" align="right">
        <div class="conf-label">Booking Confirmation</div>
        <div class="conf-number">{{ $booking->booking_number }}</div>
        <div class="conf-date">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</div>
      </td>
    </tr>
  </table>
</div>

{{-- ── Status bar ── --}}
<div class="status-bar">
  <div class="status-text">&#9679; BOOKING CONFIRMED — TOUR PACKAGE</div>
</div>

<div class="body">

  {{-- Guest Details ── --}}
  <div class="section-title">Guest Details</div>
  <table class="info-table">
    <tr>
      <td>
        <div class="info-cell">
          <div class="info-lbl">Guest Name</div>
          <div class="info-val" style="font-size:14px;">{{ $booking->lead->guest_name ?? '—' }}</div>
        </div>
      </td>
      <td>
        <div class="info-cell">
          <div class="info-lbl">Mobile Number</div>
          <div class="info-val">+91 {{ $booking->lead->contact ?? '—' }}</div>
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div class="info-cell">
          <div class="info-lbl">Email Address</div>
          <div class="info-val">{{ $booking->lead->email ?? '—' }}</div>
        </div>
      </td>
      <td>
        <div class="info-cell">
          <div class="info-lbl">Number of Guests</div>
          <div class="info-val">{{ $booking->lead->pax ?? 1 }} Person(s)</div>
        </div>
      </td>
    </tr>
    @if(!empty($booking->lead->booking_start_date) || !empty($booking->lead->booking_end_date))
    <tr>
      <td>
        <div class="info-cell">
          <div class="info-lbl">Tour Start Date</div>
          <div class="info-val">{{ !empty($booking->lead->booking_start_date) ? \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M, Y') : '—' }}</div>
        </div>
      </td>
      <td>
        <div class="info-cell">
          <div class="info-lbl">Tour End Date</div>
          <div class="info-val">{{ $pdfEndDate ?: '—' }}</div>
        </div>
      </td>
    </tr>
    @endif
  </table>

  {{-- Tour Package ── --}}
  @php
    $pdfNotes = $booking->quotation?->notes ?? $booking->lead?->notes ?? '';
    $pdfData  = ($pdfNotes && substr(trim($pdfNotes), 0, 1) === '{') ? (json_decode($pdfNotes, true) ?? []) : [];

    // Hotels (new array + old flat)
    if (!empty($pdfData['hotels']) && is_array($pdfData['hotels'])) {
        $pdfHotels = $pdfData['hotels'];
    } elseif (!empty($pdfData['hotel'])) {
        $oh = $pdfData['hotel'];
        $hn = trim($oh['name'] ?? $oh['hotel_name'] ?? '');
        $pdfHotels = $hn ? [['name'=>$hn,'city'=>$oh['city']??'','room_type'=>$oh['room_type']??'','checkin'=>$oh['checkin']??$oh['hotel_checkin']??'','checkout'=>$oh['checkout']??$oh['hotel_checkout']??'']] : [];
    } else { $pdfHotels = []; }

    $pdfCab   = $pdfData['cab']        ?? null;
    $pdfBoat  = $pdfData['boat']       ?? null;
    $pdfGuide = $pdfData['guide']      ?? null;
    $pdfIncl  = $pdfData['inclusions'] ?? '';

    // Tour end date: use booking_end_date or fall back to last hotel checkout
    $pdfEndDate = '';
    if (!empty($booking->lead->booking_end_date)) {
        $pdfEndDate = \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M, Y');
    } elseif (!empty($pdfHotels)) {
        $lastCheckout = $pdfHotels[count($pdfHotels)-1]['checkout']
                     ?? $pdfHotels[count($pdfHotels)-1]['hotel_checkout']
                     ?? '';
        if ($lastCheckout) $pdfEndDate = \Carbon\Carbon::parse($lastCheckout)->format('d M, Y');
    }
  @endphp

  <div class="section-title">Tour Package Details</div>

  {{-- Inclusions --}}
  @if($pdfIncl)
  <div style="margin-bottom:10px;">
    @foreach(array_filter(array_map('trim', explode(',', $pdfIncl))) as $inc)
    <span style="display:inline-block;background:#EEF2FF;color:#3730A3;border:1px solid #C7D2FE;border-radius:12px;padding:2px 9px;font-size:10px;font-weight:bold;margin:2px 2px 2px 0;">{{ $inc }}</span>
    @endforeach
  </div>
  @endif

  {{-- Service rows --}}
  @if(!empty($pdfHotels) || !empty($pdfCab['cab_type']) || !empty($pdfBoat['boat_type']) || !empty($pdfGuide['guide_name']))
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
    {{-- Hotels --}}
    {{-- Hotels --}}
    @foreach($pdfHotels as $ph)
    @php $phName = trim($ph['name'] ?? $ph['hotel_name'] ?? ''); $phCity = trim($ph['city'] ?? ''); @endphp
    @if($phName)
    <tr>
      <td width="52" valign="top" style="padding:4px 4px 0 0;">
        <span style="display:inline-block;background:#059669;color:#fff;font-size:8px;font-weight:bold;padding:3px 6px;border-radius:4px;letter-spacing:.5px;">HOTEL</span>
      </td>
      <td style="padding:4px 0 4px 4px;background:#F0FDF4;border-radius:6px;border:1px solid #BBF7D0;">
        <span style="font-size:11px;font-weight:bold;color:#065F46;">{{ $phName }}{{ $phCity ? ' ('.$phCity.')' : '' }}</span>
        @if(!empty($ph['room_type']))<span style="font-size:10px;color:#16A34A;"> &mdash; {{ $ph['room_type'] }}</span>@endif
        @if(!empty($ph['checkin']) || !empty($ph['checkout']))
        <span style="font-size:10px;color:#64748B;">
          &nbsp;&middot;&nbsp;
          @if(!empty($ph['checkin'])) {{ \Carbon\Carbon::parse($ph['checkin'])->format('d M Y') }} @endif
          @if(!empty($ph['checkin']) && !empty($ph['checkout'])) &rarr; @endif
          @if(!empty($ph['checkout'])) {{ \Carbon\Carbon::parse($ph['checkout'])->format('d M Y') }} @endif
          @if(!empty($ph['nights'])) &nbsp;({{ $ph['nights'] }} night{{ $ph['nights']!=1?'s':'' }}) @endif
        </span>
        @endif
      </td>
    </tr>
    <tr><td colspan="2" style="height:5px;"></td></tr>
    @endif
    @endforeach

    {{-- Cab --}}
    @if(!empty($pdfCab['cab_type']))
    <tr>
      <td width="52" valign="top" style="padding:4px 4px 0 0;">
        <span style="display:inline-block;background:#D97706;color:#fff;font-size:8px;font-weight:bold;padding:3px 6px;border-radius:4px;letter-spacing:.5px;">CAB</span>
      </td>
      <td style="padding:4px 0 4px 4px;background:#FFFBEB;border-radius:6px;border:1px solid #FDE68A;">
        <span style="font-size:11px;font-weight:bold;color:#92400E;">{{ $pdfCab['cab_type'] }}</span>
        @if(!empty($pdfCab['cab_route']))<span style="font-size:10px;color:#B45309;"> &mdash; {{ $pdfCab['cab_route'] }}</span>@endif
        @if(!empty($pdfCab['cab_from']) || !empty($pdfCab['cab_to']))
        <span style="font-size:10px;color:#64748B;">&nbsp;&middot;&nbsp;
          @if(!empty($pdfCab['cab_from'])) {{ \Carbon\Carbon::parse($pdfCab['cab_from'])->format('d M Y') }} @endif
          @if(!empty($pdfCab['cab_from']) && !empty($pdfCab['cab_to'])) &rarr; @endif
          @if(!empty($pdfCab['cab_to'])) {{ \Carbon\Carbon::parse($pdfCab['cab_to'])->format('d M Y') }} @endif
        </span>
        @endif
      </td>
    </tr>
    <tr><td colspan="2" style="height:5px;"></td></tr>
    @endif

    {{-- Boat --}}
    @if(!empty($pdfBoat['boat_type']))
    <tr>
      <td width="52" valign="top" style="padding:4px 4px 0 0;">
        <span style="display:inline-block;background:#0284C7;color:#fff;font-size:8px;font-weight:bold;padding:3px 6px;border-radius:4px;letter-spacing:.5px;">BOAT</span>
      </td>
      <td style="padding:4px 0 4px 4px;background:#F0F9FF;border-radius:6px;border:1px solid #BAE6FD;">
        <span style="font-size:11px;font-weight:bold;color:#0369A1;">{{ $pdfBoat['boat_type'] }}</span>
        @if(!empty($pdfBoat['boat_ride']))<span style="font-size:10px;color:#0369A1;"> &middot; {{ $pdfBoat['boat_ride'] }}</span>@endif
        @if(!empty($pdfBoat['boat_date']))<span style="font-size:10px;color:#64748B;"> &nbsp;&middot;&nbsp; {{ \Carbon\Carbon::parse($pdfBoat['boat_date'])->format('d M Y') }}</span>@endif
        @if(!empty($pdfBoat['boat_time']))<span style="font-size:10px;color:#64748B;"> &nbsp;&middot;&nbsp; {{ date('g:i A', strtotime($pdfBoat['boat_time'])) }}</span>@endif
      </td>
    </tr>
    <tr><td colspan="2" style="height:5px;"></td></tr>
    @endif

    {{-- Guide --}}
    @if(!empty($pdfGuide['guide_name']))
    <tr>
      <td width="52" valign="top" style="padding:4px 4px 0 0;">
        <span style="display:inline-block;background:#7C3AED;color:#fff;font-size:8px;font-weight:bold;padding:3px 6px;border-radius:4px;letter-spacing:.5px;">GUIDE</span>
      </td>
      <td style="padding:4px 0 4px 4px;background:#F5F3FF;border-radius:6px;border:1px solid #C4B5FD;">
        <span style="font-size:11px;font-weight:bold;color:#5B21B6;">{{ $pdfGuide['guide_name'] }}</span>
        @if(!empty($pdfGuide['guide_lang']))<span style="font-size:10px;color:#6D28D9;"> &mdash; {{ $pdfGuide['guide_lang'] }}</span>@endif
        @if(!empty($pdfGuide['guide_from']) || !empty($pdfGuide['guide_to']))
        <span style="font-size:10px;color:#64748B;">&nbsp;&middot;&nbsp;
          @if(!empty($pdfGuide['guide_from'])) {{ \Carbon\Carbon::parse($pdfGuide['guide_from'])->format('d M Y') }} @endif
          @if(!empty($pdfGuide['guide_from']) && !empty($pdfGuide['guide_to'])) &rarr; @endif
          @if(!empty($pdfGuide['guide_to'])) {{ \Carbon\Carbon::parse($pdfGuide['guide_to'])->format('d M Y') }} @endif
        </span>
        @endif
      </td>
    </tr>
    @endif
  </table>
  @endif

  <div class="pkg-box">
    <div class="pkg-label">Package Summary</div>
    <div class="pkg-text">{{ $booking->lead->short_plan ?? '—' }}</div>
  </div>
  @if(!empty($booking->lead->plan_detail))
  <div class="itin-box">
    <div class="itin-label">Itinerary</div>
    <div class="itin-text">{{ strip_tags($booking->lead->plan_detail) }}</div>
  </div>
  @endif

  {{-- Payment Summary ── --}}
  <div class="section-title">Payment Summary</div>
  @php
    $paid = $booking->paid_amount ?? $booking->payments_sum_amount ?? 0;
    $due  = max(0, $booking->total_amount - $paid);
  @endphp
  <div class="amt-box">
    <table class="amt-table">
      <tr>
        <td class="amt-lbl">Total Booking Amount</td>
        <td class="amt-val amt-total">&#8377;{{ number_format($booking->total_amount, 0) }}</td>
      </tr>
      @if($booking->discount_amount > 0)
      <tr>
        <td class="amt-lbl">Discount</td>
        <td class="amt-val amt-paid">- &#8377;{{ number_format($booking->discount_amount, 0) }}</td>
      </tr>
      @endif
      <tr class="amt-divider"><td colspan="2"></td></tr>
      <tr>
        <td class="amt-lbl">Amount Paid</td>
        <td class="amt-val amt-paid">&#8377;{{ number_format($paid, 0) }}</td>
      </tr>
      <tr>
        <td class="amt-lbl">Balance Due</td>
        <td class="amt-val {{ $due > 0 ? 'amt-due' : 'amt-paid' }}">
          @if($due > 0)&#8377;{{ number_format($due, 0) }}@else Fully Paid @endif
        </td>
      </tr>
      @if($booking->payments->isNotEmpty())
      <tr>
        <td class="amt-lbl">Payment Method</td>
        <td class="amt-val">{{ ucfirst(str_replace('_', ' ', $booking->payments->first()->payment_method ?? 'Cash')) }}</td>
      </tr>
      @endif
    </table>
  </div>

  {{-- Cancellation Policy ── --}}
  <div class="section-title">Cancellation &amp; Refund Policy</div>
  <div class="policy-box">
    <div class="policy-title">Please read cancellation terms carefully before travel</div>
    <table class="policy-table">
      <tr><td class="policy-dot">&#8226;</td><td>Cancellation <strong>30+ days</strong> before — Full refund (minus &#8377;500 processing fee)</td></tr>
      <tr><td class="policy-dot">&#8226;</td><td>Cancellation <strong>15–29 days</strong> before — <strong>75% refund</strong></td></tr>
      <tr><td class="policy-dot">&#8226;</td><td>Cancellation <strong>7–14 days</strong> before — <strong>50% refund</strong></td></tr>
      <tr><td class="policy-dot">&#8226;</td><td>Cancellation <strong>3–6 days</strong> before — <strong>25% refund</strong></td></tr>
      <tr><td class="policy-dot">&#8226;</td><td>Cancellation <strong>within 48 hours</strong> or No-show — <strong>No refund</strong></td></tr>
      <tr><td class="policy-dot">&#8226;</td><td>Refunds processed within <strong>7–10 working days</strong> via original payment method</td></tr>
      <tr><td class="policy-dot">&#8226;</td><td>Natural calamity / Govt order — full credit note issued for future travel</td></tr>
      <tr><td class="policy-dot">&#8226;</td><td>Date change allowed <strong>once free</strong> if requested 7+ days before departure</td></tr>
    </table>
  </div>

  {{-- Important Notes ── --}}
  <div class="notes-box">
    <div class="notes-title">Important Information</div>
    <table style="width:100%;border-collapse:collapse;">
      <tr><td style="padding:3px 0;font-size:10.5px;color:#14532D;border-bottom:1px dashed #BBF7D0;">&#8226; Carry a valid government-issued photo ID proof during travel</td></tr>
      <tr><td style="padding:3px 0;font-size:10.5px;color:#14532D;border-bottom:1px dashed #BBF7D0;">&#8226; Booking confirmed after full/advance payment receipt</td></tr>
      <tr><td style="padding:3px 0;font-size:10.5px;color:#14532D;border-bottom:1px dashed #BBF7D0;">&#8226; Contact 7080109917 | 7080109918 | 7080109919 for changes or assistance</td></tr>
      <tr><td style="padding:3px 0;font-size:10.5px;color:#14532D;">&#8226; This is a computer-generated confirmation — no signature required</td></tr>
    </table>
  </div>

</div>

{{-- Footer ── --}}
<div class="footer">
  <div class="footer-text">
    {{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}
    &nbsp;|&nbsp; {{ websiteSetupValue('address') ?? 'Varanasi, Uttar Pradesh' }}
    &nbsp;|&nbsp; Ph: 7080109917 | 7080109918 | 7080109919
    &nbsp;|&nbsp; {{ websiteSetupValue('email') ?? 'info@visitkashi.in' }}
  </div>
  <div class="footer-thanks">Thank you for choosing {{ websiteSetupValue('site_name') ?? 'Visit Kashi' }} — We look forward to serving you!</div>
</div>

</body>
</html>
