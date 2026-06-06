@extends('admin.layouts.app')
@section('content')
@php
  $lead      = $booking->lead;
  $paidAmt   = $booking->paid_amount ?? $booking->payments_sum_amount ?? 0;
  $dueAmt    = max(0, $booking->total_amount - $paidAmt);
  $payStatus = $dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'due');
  $totalExp  = array_sum($expenses);
  $net       = $booking->total_amount;
  $profit    = $net - $totalExp;
  $profitPct = $net > 0 ? round(($profit/$net)*100) : 0;
@endphp
<style>
*{box-sizing:border-box;}
.sv{--c-bg:#F7F8FA;--c-card:#fff;--c-border:#E5E7EB;--c-text:#111827;--c-sub:#6B7280;--c-muted:#9CA3AF;
   --c-purple:#7C3AED;--c-indigo:#4F46E5;--c-green:#059669;--c-red:#DC2626;--c-amber:#D97706;
   --c-sky:#0284C7;--r:10px;--sh:0 1px 3px rgba(0,0,0,.06),0 2px 8px rgba(0,0,0,.04);
   background:var(--c-bg);min-height:100vh;padding:0;font-family:'Plus Jakarta Sans',-apple-system,sans-serif;}

/* ── Top nav strip ── */
.sv-topbar{background:#fff;border-bottom:1px solid var(--c-border);padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:54px;position:sticky;top:64px;z-index:99;gap:12px;}
.sv-breadcrumb{display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--c-sub);}
.sv-breadcrumb a{color:var(--c-indigo);text-decoration:none;font-weight:600;}
.sv-breadcrumb a:hover{text-decoration:underline;}
.sv-breadcrumb span{color:var(--c-muted);}
.sv-topbar-actions{display:flex;gap:8px;align-items:center;}
.sv-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:7px;font-size:.8rem;font-weight:600;text-decoration:none !important;border:none;cursor:pointer;transition:.15s;white-space:nowrap;}
.sv-btn-ghost{background:transparent;color:var(--c-sub);border:1px solid var(--c-border);}
.sv-btn-ghost:hover{background:#F3F4F6;color:var(--c-text);}
.sv-btn-primary{background:var(--c-indigo);color:#fff;}
.sv-btn-primary:hover{background:#4338CA;color:#fff;}
.sv-btn-success{background:#059669;color:#fff;}
.sv-btn-success:hover{background:#047857;color:#fff;}

/* ── Hero strip ── */
.sv-hero{background:#fff;border-bottom:1px solid var(--c-border);padding:20px 24px;}
.sv-hero-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;}
.sv-hero-id{font-size:1.3rem;font-weight:800;color:var(--c-text);letter-spacing:-.02em;}
.sv-hero-sub{font-size:.8rem;color:var(--c-sub);margin-top:3px;}
.sv-status-chip{display:inline-flex;align-items:center;gap:5px;font-size:.73rem;font-weight:700;padding:4px 12px;border-radius:20px;letter-spacing:.01em;}
.sv-chip-confirmed{background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;}
.sv-chip-paid{background:#D1FAE5;color:#065F46;}
.sv-chip-partial{background:#DBEAFE;color:#1E40AF;}
.sv-chip-due{background:#FEE2E2;color:#991B1B;}

/* ── Stats bar ── */
.sv-stats{display:grid;grid-template-columns:repeat(5,1fr);gap:0;border-top:1px solid var(--c-border);margin-top:16px;}
@media(max-width:900px){.sv-stats{grid-template-columns:repeat(3,1fr);}}
@media(max-width:600px){.sv-stats{grid-template-columns:1fr 1fr;}}
.sv-stat{padding:14px 18px;border-right:1px solid var(--c-border);text-align:center;}
.sv-stat:last-child{border-right:none;}
.sv-stat-lbl{font-size:.65rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;}
.sv-stat-val{font-size:1.1rem;font-weight:800;color:var(--c-text);}
.sv-stat-val.green{color:var(--c-green);}
.sv-stat-val.red{color:var(--c-red);}
.sv-stat-val.amber{color:var(--c-amber);}
.sv-stat-val.purple{color:var(--c-purple);}

/* ── Layout ── */
.sv-body{padding:20px 24px;display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;}
@media(max-width:1080px){.sv-body{grid-template-columns:1fr;}}

/* ── Section card ── */
.sv-card{background:var(--c-card);border:1px solid var(--c-border);border-radius:var(--r);box-shadow:var(--sh);margin-bottom:14px;overflow:hidden;}
.sv-card-head{padding:14px 18px;border-bottom:1px solid var(--c-border);display:flex;align-items:center;gap:10px;}
.sv-card-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;}
.sv-card-title{font-size:.88rem;font-weight:700;color:var(--c-text);}
.sv-card-body{padding:18px;}

/* ── Field display ── */
.sv-fields{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
@media(max-width:600px){.sv-fields{grid-template-columns:1fr;}}
.sv-field{}
.sv-field-lbl{font-size:.67rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;}
.sv-field-val{font-size:.88rem;font-weight:600;color:var(--c-text);line-height:1.4;}
.sv-field-val.large{font-size:1.05rem;}
.sv-field-full{grid-column:1/-1;}

/* ── Expense services ── */
.sv-svc-list{display:flex;flex-direction:column;gap:8px;}
.sv-svc-item{display:flex;align-items:center;gap:12px;padding:11px 14px;background:#FAFAFA;border:1px solid var(--c-border);border-radius:8px;}
.sv-svc-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0;}
.sv-svc-info{flex:1;}
.sv-svc-name{font-size:.83rem;font-weight:700;color:var(--c-text);}
.sv-svc-detail{font-size:.73rem;color:var(--c-sub);margin-top:1px;}
.sv-svc-amount{font-size:.9rem;font-weight:800;color:var(--c-amber);}
.sv-svc-zero{color:var(--c-muted);font-size:.82rem;}

/* ── Expense totals ── */
.sv-exp-summary{display:grid;grid-template-columns:repeat(4,1fr) 1.2fr;gap:10px;margin-top:14px;background:linear-gradient(135deg,#FFFBEB,#FEF3C7);border:1px solid #FDE68A;border-radius:9px;padding:12px 14px;}
@media(max-width:600px){.sv-exp-summary{grid-template-columns:1fr 1fr;}}
.sv-exp-col{text-align:center;}
.sv-exp-col-lbl{font-size:.6rem;font-weight:700;color:#92400E;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;}
.sv-exp-col-val{font-size:.9rem;font-weight:800;color:#78350F;}
.sv-exp-total{background:#fff;border-radius:7px;padding:6px 12px;border:1.5px solid #FDE68A;}
.sv-exp-total .sv-exp-col-lbl{color:#1D4ED8;}
.sv-exp-total .sv-exp-col-val{font-size:1.05rem;color:#1E3A8A;}

/* ── Text display ── */
.sv-text-display{background:#F9FAFB;border:1px solid var(--c-border);border-radius:8px;padding:12px 14px;font-size:.84rem;color:var(--c-text);line-height:1.7;white-space:pre-wrap;}
.sv-summary-display{background:#EEF2FF;border:1px solid #C7D2FE;border-radius:8px;padding:12px 14px;font-size:.85rem;color:#3730A3;line-height:1.65;font-weight:500;}

/* ── Sidebar ── */
.sv-sidebar{position:sticky;top:120px;}
.sv-sidebar-card{background:var(--c-card);border:1px solid var(--c-border);border-radius:var(--r);box-shadow:var(--sh);margin-bottom:14px;overflow:hidden;}
.sv-sidebar-head{padding:12px 16px;border-bottom:1px solid var(--c-border);font-size:.82rem;font-weight:700;color:var(--c-text);background:#FAFAFA;}

/* ── Payment rows ── */
.sv-pay-table{width:100%;}
.sv-pay-row{display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-bottom:1px solid #F3F4F6;font-size:.82rem;}
.sv-pay-row:last-child{border-bottom:none;}
.sv-pay-lbl{color:var(--c-sub);}
.sv-pay-val{font-weight:700;color:var(--c-text);}
.sv-pay-total-row{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#F9FAFB;font-size:.88rem;}
.sv-pay-balance{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;}
.sv-balance-num{font-size:1.6rem;font-weight:800;}

/* ── Profit ── */
.sv-profit-card{background:linear-gradient(135deg,#F0FDF4,#DCFCE7);border:1px solid #BBF7D0;border-radius:var(--r);padding:16px;margin-bottom:14px;}
.sv-profit-row{display:flex;justify-content:space-between;align-items:center;font-size:.82rem;padding:5px 0;border-bottom:1px dashed #BBF7D0;}
.sv-profit-row:last-child{border-bottom:none;padding-top:9px;margin-top:4px;border-top:2px solid #A7F3D0;}
.sv-profit-lbl{color:#065F46;}
.sv-profit-val{font-weight:700;color:#14532D;}
.sv-profit-big{font-size:1rem !important;font-weight:800 !important;}

/* ── Payment history ── */
.sv-timeline{padding:4px 0;}
.sv-timeline-item{display:flex;gap:12px;padding:10px 16px;border-bottom:1px solid #F3F4F6;align-items:flex-start;}
.sv-timeline-item:last-child{border-bottom:none;}
.sv-timeline-dot{width:8px;height:8px;border-radius:50%;background:var(--c-green);flex-shrink:0;margin-top:5px;}
.sv-timeline-content{flex:1;}
.sv-timeline-amt{font-size:.88rem;font-weight:800;color:var(--c-green);}
.sv-timeline-meta{font-size:.74rem;color:var(--c-sub);margin-top:2px;}
.sv-timeline-badge{display:inline-block;font-size:.63rem;font-weight:700;padding:1px 7px;border-radius:10px;margin-left:6px;}
.sv-timeline-advance{background:#DBEAFE;color:#1E40AF;}
.sv-timeline-part{background:#D1FAE5;color:#065F46;}

/* ── Quick actions ── */
.sv-quick-actions{display:flex;flex-direction:column;gap:8px;}
.sv-quick-btn{display:flex;align-items:center;justify-content:center;gap:8px;padding:11px 16px;border-radius:9px;font-size:.84rem;font-weight:700;text-decoration:none !important;border:none;cursor:pointer;transition:.18s;}
.sv-quick-btn:hover{opacity:.88;transform:translateY(-1px);}
.sv-qb-edit{background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;}
.sv-qb-conf{background:#F0FDF4;color:#15803D;border:1.5px solid #BBF7D0;}

@media(max-width:768px){
  .sv-topbar{top:56px;padding:0 14px;}
  .sv-hero{padding:14px 16px;}
  .sv-body{padding:14px 16px;}
  .sv-stat{padding:10px 12px;}
  .sv-stat-val{font-size:.95rem;}
}
</style>

<div class="sv">

  {{-- ── Top action bar ── --}}
  <div class="sv-topbar">
    <div class="sv-breadcrumb">
      <a href="{{ route('bookings.index', ['service_type'=>'tour','status'=>'confirmed']) }}">Tour Bookings</a>
      <span>/</span>
      <span style="color:var(--c-text);font-weight:600;">{{ $booking->booking_number }}</span>
    </div>
    <div class="sv-topbar-actions">
      <a href="{{ route('tour-booking.show', $booking->id) }}" class="sv-btn sv-btn-ghost">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit
      </a>
      <a href="{{ route('tour-booking.confirmation', $booking->id) }}" target="_blank" class="sv-btn sv-btn-success">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Confirmation
      </a>
      <a href="{{ route('tour-booking.pdf', $booking->id) }}" target="_blank" class="sv-btn sv-btn-primary">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        PDF
      </a>
    </div>
  </div>

  {{-- ── Hero summary ── --}}
  <div class="sv-hero">
    <div class="sv-hero-top">
      <div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
          <div class="sv-hero-id">🗺️ {{ $booking->booking_number }}</div>
          <span class="sv-status-chip sv-chip-confirmed">● Confirmed</span>
          <span class="sv-status-chip sv-chip-{{ $payStatus }}">
            {{ ['paid'=>'Fully Paid','partial'=>'Partially Paid','due'=>'Payment Due'][$payStatus] }}
          </span>
        </div>
        <div class="sv-hero-sub">
          Tour Package &nbsp;·&nbsp; Created {{ $booking->booking_date->format('d M Y') }}
          @if($lead->booking_start_date) &nbsp;·&nbsp; Travel: {{ \Carbon\Carbon::parse($lead->booking_start_date)->format('d M') }}{{ $lead->booking_end_date ? ' – '.\Carbon\Carbon::parse($lead->booking_end_date)->format('d M Y') : '' }} @endif
        </div>
      </div>
    </div>

    {{-- Stats strip --}}
    <div class="sv-stats">
      <div class="sv-stat">
        <div class="sv-stat-lbl">Guest</div>
        <div class="sv-stat-val" style="font-size:.9rem;">{{ $lead->guest_name ?? '—' }}</div>
      </div>
      <div class="sv-stat">
        <div class="sv-stat-lbl">Booking Amount</div>
        <div class="sv-stat-val">₹{{ number_format($booking->total_amount) }}</div>
      </div>
      <div class="sv-stat">
        <div class="sv-stat-lbl">Amount Paid</div>
        <div class="sv-stat-val green">₹{{ number_format($paidAmt) }}</div>
      </div>
      <div class="sv-stat">
        <div class="sv-stat-lbl">Balance Due</div>
        <div class="sv-stat-val {{ $dueAmt > 0 ? 'red' : 'green' }}">
          {{ $dueAmt > 0 ? '₹'.number_format($dueAmt) : '✓ Nil' }}
        </div>
      </div>
      <div class="sv-stat">
        <div class="sv-stat-lbl">Profit</div>
        <div class="sv-stat-val {{ $profit >= 0 ? 'purple' : 'red' }}">
          ₹{{ number_format(max(0,$profit)) }}
          <span style="font-size:.7rem;font-weight:600;">({{ max(0,$profitPct) }}%)</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Body ── --}}
  <div class="sv-body">

    {{-- LEFT --}}
    <div>

      {{-- Guest --}}
      <div class="sv-card">
        <div class="sv-card-head">
          <div class="sv-card-icon" style="background:#EDE9FE;">👤</div>
          <div class="sv-card-title">Guest Information</div>
        </div>
        <div class="sv-card-body">
          <div class="sv-fields">
            <div class="sv-field">
              <div class="sv-field-lbl">Full Name</div>
              <div class="sv-field-val large">{{ $lead->guest_name ?? '—' }}</div>
            </div>
            <div class="sv-field">
              <div class="sv-field-lbl">Mobile</div>
              <div class="sv-field-val">+91 {{ $lead->contact ?? '—' }}</div>
            </div>
            @if($lead->email)
            <div class="sv-field">
              <div class="sv-field-lbl">Email</div>
              <div class="sv-field-val">{{ $lead->email }}</div>
            </div>
            @endif
            <div class="sv-field">
              <div class="sv-field-lbl">No. of Guests</div>
              <div class="sv-field-val">{{ $lead->pax ?? 1 }} person(s)</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Tour Package --}}
      <div class="sv-card">
        <div class="sv-card-head">
          <div class="sv-card-icon" style="background:#EDE9FE;">📅</div>
          <div class="sv-card-title">Tour Package</div>
        </div>
        <div class="sv-card-body">
          @php
            $pkgName = $serviceData['package_name']
                    ?? $booking->quotation?->items?->first()?->notes
                    ?? trim(explode(' | ', $lead->short_plan ?? '')[0])
                    ?? '—';
            $inclusions = $serviceData['inclusions'] ?? '';
            if (!$inclusions && $lead->short_plan && preg_match('/Incl:\s*(.+?)(\s*\||$)/iu', $lead->short_plan, $m)) {
                $inclusions = trim($m[1]);
            }
          @endphp
          <div class="sv-fields">
            <div class="sv-field sv-field-full">
              <div class="sv-field-lbl">Package Name</div>
              <div class="sv-field-val large">{{ $pkgName }}</div>
            </div>
            <div class="sv-field">
              <div class="sv-field-lbl">Start Date</div>
              <div class="sv-field-val">{{ $lead->booking_start_date ? \Carbon\Carbon::parse($lead->booking_start_date)->format('d M, Y') : '—' }}</div>
            </div>
            <div class="sv-field">
              <div class="sv-field-lbl">Return Date</div>
              <div class="sv-field-val">{{ $lead->booking_end_date ? \Carbon\Carbon::parse($lead->booking_end_date)->format('d M, Y') : '—' }}</div>
            </div>
            @if($inclusions)
            <div class="sv-field sv-field-full">
              <div class="sv-field-lbl">Inclusions</div>
              <div class="sv-field-val" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:2px;">
                @foreach(array_filter(array_map('trim', explode(',', $inclusions))) as $incl)
                  <span style="background:#EEF2FF;color:#4338CA;border:1px solid #C7D2FE;border-radius:20px;padding:3px 11px;font-size:.76rem;font-weight:600;">{{ $incl }}</span>
                @endforeach
              </div>
            </div>
            @endif
          </div>
          @if($lead->short_plan)
          <div style="margin-top:14px;">
            <div class="sv-field-lbl" style="margin-bottom:6px;">Trip Summary</div>
            <div class="sv-summary-display">{{ $lead->short_plan }}</div>
          </div>
          @endif
        </div>
      </div>

      {{-- B2B Expenses --}}
      <div class="sv-card">
        <div class="sv-card-head">
          <div class="sv-card-icon" style="background:#FEF3C7;">💰</div>
          <div class="sv-card-title">B2B Expense Breakdown</div>
        </div>
        <div class="sv-card-body">
          <div class="sv-svc-list">

            {{-- Hotel / Stay --}}
            @php
              $hotelRows = $serviceData['hotels'] ?? [];
              $h = $serviceData['hotel'] ?? [];
              if (empty($hotelRows) && !empty($h['hotel_name'])) {
                $hotelRows = [['name'=>$h['hotel_name'],'city'=>'','room_type'=>'','checkin'=>$h['hotel_checkin']??'','checkout'=>$h['hotel_checkout']??'','nights'=>$h['hotel_nights']??'','b2b'=>$h['b2b']??0]];
              }
            @endphp
            <div class="sv-svc-item" style="flex-direction:column;align-items:stretch;">
              <div style="display:flex;align-items:center;gap:12px;">
                <span class="sv-svc-dot" style="background:#10B981;"></span>
                <div class="sv-svc-info"><div class="sv-svc-name">🏨 Hotel / Stay</div></div>
                @if($expenses['hotel'] > 0)
                  <div class="sv-svc-amount">₹{{ number_format($expenses['hotel']) }}</div>
                @else
                  <div class="sv-svc-zero">Not included</div>
                @endif
              </div>
              @foreach($hotelRows as $hr)
              @if(!empty($hr['name']))
              <div style="margin-top:8px;padding:8px 12px;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:7px;font-size:.8rem;">
                <div style="font-weight:700;color:#065F46;">{{ $hr['city'] ? $hr['city'].' — ' : '' }}{{ $hr['name'] }}</div>
                <div style="color:#6B7280;margin-top:2px;display:flex;gap:14px;flex-wrap:wrap;">
                  @if(!empty($hr['room_type']))<span>🛏 {{ $hr['room_type'] }}</span>@endif
                  @if(!empty($hr['checkin']))<span>📅 {{ \Carbon\Carbon::parse($hr['checkin'])->format('d M') }}@if(!empty($hr['checkout'])) → {{ \Carbon\Carbon::parse($hr['checkout'])->format('d M Y') }}@endif</span>@endif
                  @if(!empty($hr['nights']))<span>🌙 {{ $hr['nights'] }} night(s)</span>@endif
                  @if(!empty($hr['b2b']))<span style="color:#D97706;font-weight:700;">₹{{ number_format($hr['b2b']) }}</span>@endif
                </div>
              </div>
              @endif
              @endforeach
            </div>

            {{-- Cab / Transport --}}
            @php $c = $serviceData['cab'] ?? []; @endphp
            <div class="sv-svc-item" style="flex-direction:column;align-items:stretch;">
              <div style="display:flex;align-items:center;gap:12px;">
                <span class="sv-svc-dot" style="background:#F59E0B;"></span>
                <div class="sv-svc-info">
                  <div class="sv-svc-name">🚕 Cab / Transport</div>
                  @if(!empty($c['cab_type']))<div class="sv-svc-detail">{{ $c['cab_type'] }}@if(!empty($c['cab_route'])) &nbsp;·&nbsp; {{ $c['cab_route'] }}@endif</div>@endif
                </div>
                @if($expenses['cab'] > 0)
                  <div class="sv-svc-amount">₹{{ number_format($expenses['cab']) }}</div>
                @else
                  <div class="sv-svc-zero">Not included</div>
                @endif
              </div>
              @if(!empty($c['cab_from']) || !empty($c['cab_to']))
              <div style="margin-top:6px;padding:6px 12px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:7px;font-size:.78rem;color:#92400E;">
                📅 @if(!empty($c['cab_from'])){{ \Carbon\Carbon::parse($c['cab_from'])->format('d M') }}@endif
                   @if(!empty($c['cab_to'])) → {{ \Carbon\Carbon::parse($c['cab_to'])->format('d M Y') }}@endif
              </div>
              @endif
            </div>

            {{-- Boat / River Ride --}}
            @php $bt = $serviceData['boat'] ?? []; @endphp
            <div class="sv-svc-item" style="flex-direction:column;align-items:stretch;">
              <div style="display:flex;align-items:center;gap:12px;">
                <span class="sv-svc-dot" style="background:#0EA5E9;"></span>
                <div class="sv-svc-info">
                  <div class="sv-svc-name">⛵ Boat / River Ride</div>
                  @if(!empty($bt['boat_type']))<div class="sv-svc-detail">{{ $bt['boat_type'] }}@if(!empty($bt['boat_ride'])) &nbsp;·&nbsp; {{ $bt['boat_ride'] }}@endif</div>@endif
                </div>
                @if($expenses['boat'] > 0)
                  <div class="sv-svc-amount">₹{{ number_format($expenses['boat']) }}</div>
                @else
                  <div class="sv-svc-zero">Not included</div>
                @endif
              </div>
              @if(!empty($bt['boat_date']))
              <div style="margin-top:6px;padding:6px 12px;background:#F0F9FF;border:1px solid #BAE6FD;border-radius:7px;font-size:.78rem;color:#0369A1;">
                📅 {{ \Carbon\Carbon::parse($bt['boat_date'])->format('d M Y') }}
                @if(!empty($bt['boat_time'])) &nbsp;·&nbsp; 🕐 {{ \Carbon\Carbon::createFromFormat('H:i', $bt['boat_time'])->format('h:i A') }}@endif
              </div>
              @endif
            </div>

            {{-- Guide --}}
            @php $g = $serviceData['guide'] ?? []; @endphp
            <div class="sv-svc-item">
              <span class="sv-svc-dot" style="background:#8B5CF6;"></span>
              <div class="sv-svc-info">
                <div class="sv-svc-name">🧭 Guide</div>
                @if(!empty($g['guide_name']))<div class="sv-svc-detail">{{ $g['guide_name'] }}@if(!empty($g['guide_lang'])) &nbsp;·&nbsp; {{ $g['guide_lang'] }}@endif</div>@endif
                @if(!empty($g['guide_from']) || !empty($g['guide_to']))
                  <div class="sv-svc-detail">
                    @if(!empty($g['guide_from'])){{ \Carbon\Carbon::parse($g['guide_from'])->format('d M') }}@endif
                    @if(!empty($g['guide_to'])) → {{ \Carbon\Carbon::parse($g['guide_to'])->format('d M Y') }}@endif
                  </div>
                @endif
              </div>
              @if($expenses['guide'] > 0)
                <div class="sv-svc-amount">₹{{ number_format($expenses['guide']) }}</div>
              @else
                <div class="sv-svc-zero">Not included</div>
              @endif
            </div>

          </div>

          <div class="sv-exp-summary" style="margin-top:14px;">
            <div class="sv-exp-col"><div class="sv-exp-col-lbl">🏨 Hotel</div><div class="sv-exp-col-val">₹{{ number_format($expenses['hotel']) }}</div></div>
            <div class="sv-exp-col"><div class="sv-exp-col-lbl">🚕 Cab</div><div class="sv-exp-col-val">₹{{ number_format($expenses['cab']) }}</div></div>
            <div class="sv-exp-col"><div class="sv-exp-col-lbl">⛵ Boat</div><div class="sv-exp-col-val">₹{{ number_format($expenses['boat']) }}</div></div>
            <div class="sv-exp-col"><div class="sv-exp-col-lbl">🧭 Guide</div><div class="sv-exp-col-val">₹{{ number_format($expenses['guide']) }}</div></div>
            <div class="sv-exp-col sv-exp-total"><div class="sv-exp-col-lbl">Total Expense</div><div class="sv-exp-col-val">₹{{ number_format($totalExp) }}</div></div>
          </div>
        </div>
      </div>

      {{-- Itinerary --}}
      @if($lead->plan_detail)
      <div class="sv-card">
        <div class="sv-card-head">
          <div class="sv-card-icon" style="background:#EDE9FE;">🗓️</div>
          <div class="sv-card-title">Itinerary</div>
        </div>
        <div class="sv-card-body">
          <div class="sv-text-display" style="white-space:normal;">{!! $lead->plan_detail !!}</div>
        </div>
      </div>
      @endif

      {{-- Notes --}}
      @if($internalNotes)
      <div class="sv-card">
        <div class="sv-card-head">
          <div class="sv-card-icon" style="background:#F3F4F6;">📌</div>
          <div class="sv-card-title">Internal Notes <span style="font-size:.72rem;font-weight:400;color:var(--c-muted);">(staff only)</span></div>
        </div>
        <div class="sv-card-body">
          <div class="sv-text-display" style="background:#FFFBEB;border-color:#FDE68A;color:#78350F;">{{ $internalNotes }}</div>
        </div>
      </div>
      @endif

    </div>{{-- /left --}}

    {{-- RIGHT SIDEBAR --}}
    <div class="sv-sidebar">

      {{-- Payment Summary --}}
      <div class="sv-sidebar-card">
        <div class="sv-sidebar-head">💳 Payment Summary</div>
        <div class="sv-pay-table">
          <div class="sv-pay-row">
            <span class="sv-pay-lbl">Booking Amount</span>
            <span class="sv-pay-val">₹{{ number_format($booking->total_amount + $booking->discount_amount) }}</span>
          </div>
          @if($booking->discount_amount > 0)
          <div class="sv-pay-row">
            <span class="sv-pay-lbl">Discount</span>
            <span class="sv-pay-val" style="color:var(--c-green);">−₹{{ number_format($booking->discount_amount) }}</span>
          </div>
          @endif
          <div class="sv-pay-row" style="border-top:2px solid #F3F4F6;">
            <span class="sv-pay-lbl" style="font-weight:700;">Net Payable</span>
            <span class="sv-pay-val" style="font-size:.95rem;">₹{{ number_format($booking->total_amount) }}</span>
          </div>
          <div class="sv-pay-row">
            <span class="sv-pay-lbl">Amount Paid</span>
            <span class="sv-pay-val" style="color:var(--c-green);">₹{{ number_format($paidAmt) }}</span>
          </div>
        </div>
        <div class="sv-pay-balance">
          <div>
            <div style="font-size:.63rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Balance Due</div>
            <div class="sv-balance-num" style="color:{{ $dueAmt > 0 ? 'var(--c-red)' : 'var(--c-green)' }};">
              {{ $dueAmt > 0 ? '₹'.number_format($dueAmt) : '✅ Paid' }}
            </div>
          </div>
          <span class="sv-status-chip sv-chip-{{ $payStatus }}" style="align-self:flex-end;">
            {{ strtoupper($payStatus) }}
          </span>
        </div>
        @if($booking->payments->isNotEmpty())
        <div style="padding:0 16px 12px;font-size:.74rem;color:var(--c-sub);">
          Method: <strong>{{ ucfirst(str_replace('_',' ',$booking->payments->first()->payment_method ?? 'Cash')) }}</strong>
        </div>
        @endif
      </div>

      {{-- Marginal Profit --}}
      <div class="sv-profit-card">
        <div style="font-size:.72rem;font-weight:800;color:#065F46;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">📊 Marginal Profit</div>
        <div class="sv-profit-row">
          <span class="sv-profit-lbl">Booking Amount</span>
          <span class="sv-profit-val">₹{{ number_format($net) }}</span>
        </div>
        <div class="sv-profit-row">
          <span class="sv-profit-lbl">Total B2B Expense</span>
          <span class="sv-profit-val" style="color:var(--c-red);">₹{{ number_format($totalExp) }}</span>
        </div>
        <div class="sv-profit-row">
          <span class="sv-profit-lbl" style="font-weight:800;">Marginal Profit</span>
          <div style="text-align:right;">
            <span class="sv-profit-val sv-profit-big" style="color:{{ $profit >= 0 ? '#14532D' : 'var(--c-red)' }};">
              ₹{{ number_format(max(0,$profit)) }}
            </span>
            <div style="font-size:.7rem;font-weight:700;color:{{ $profitPct >= 0 ? '#059669' : 'var(--c-red)' }};">{{ max(0,$profitPct) }}% margin</div>
          </div>
        </div>
      </div>

      {{-- Payment History --}}
      <div class="sv-sidebar-card">
        <div class="sv-sidebar-head">🕐 Payment History</div>
        @if($booking->payments->isNotEmpty())
        <div class="sv-timeline">
          @foreach($booking->payments->sortBy('payment_date') as $pay)
          <div class="sv-timeline-item">
            <div class="sv-timeline-dot" style="{{ $loop->first ? 'background:#3B82F6;' : '' }}"></div>
            <div class="sv-timeline-content">
              <div class="sv-timeline-amt">
                ₹{{ number_format($pay->amount) }}
                <span class="sv-timeline-badge {{ $loop->first ? 'sv-timeline-advance' : 'sv-timeline-part' }}">
                  {{ $loop->first ? 'Advance' : 'Part' }}
                </span>
              </div>
              <div class="sv-timeline-meta">
                {{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}
                &nbsp;·&nbsp; {{ ucfirst(str_replace('_',' ',$pay->payment_method ?? 'Cash')) }}
                @if($pay->notes) &nbsp;·&nbsp; {{ $pay->notes }} @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div style="padding:16px;text-align:center;color:var(--c-muted);font-size:.82rem;">No payments recorded</div>
        @endif
      </div>

      {{-- Quick Actions --}}
      <div class="sv-quick-actions">
        <a href="{{ route('tour-booking.show', $booking->id) }}" class="sv-quick-btn sv-qb-edit">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit Booking
        </a>
        <a href="{{ route('tour-booking.confirmation', $booking->id) }}" target="_blank" class="sv-quick-btn sv-qb-conf">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Download Confirmation
        </a>
      </div>

    </div>{{-- /sidebar --}}
  </div>{{-- /body --}}
</div>
@endsection
