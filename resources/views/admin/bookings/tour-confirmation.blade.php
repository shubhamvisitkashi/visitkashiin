@extends('admin.layouts.app')
@section('content')
<style>
.tcf-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.tcf-actions{display:flex;gap:10px;margin-bottom:18px;margin-top:50px;flex-wrap:wrap;align-items:center;}
.tcf-actions a,.tcf-actions button{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-size:.82rem;font-weight:700;cursor:pointer;text-decoration:none;border:none;transition:.2s;}
.tcf-btn-pdf{background:linear-gradient(135deg,#DC2626,#EF4444);color:#fff;box-shadow:0 4px 12px rgba(220,38,38,.3);}
.tcf-btn-pdf:hover{opacity:.9;color:#fff;}
.tcf-btn-print{background:linear-gradient(135deg,#1D4ED8,#3B82F6);color:#fff;box-shadow:0 4px 12px rgba(29,78,216,.3);}
.tcf-btn-print:hover{opacity:.9;color:#fff;}
.tcf-btn-back{background:#fff;color:#475569;border:1.5px solid #E2E8F0;}
.tcf-btn-back:hover{background:#F8FAFC;}

/* ── Confirmation card ── */
.tcf-doc{background:#fff;max-width:820px;margin:0 auto;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.10);overflow:hidden;}

/* Header */
.tcf-header{background:linear-gradient(135deg,#1E1B4B,#4338CA,#6366F1);padding:28px 36px;display:flex;align-items:center;justify-content:space-between;gap:20px;}
.tcf-logo-area{display:flex;align-items:center;gap:14px;}
.tcf-logo-img{height:52px;width:auto;object-fit:contain;filter:brightness(0) invert(1);}
.tcf-company{color:#fff;}
.tcf-company-name{font-size:1.2rem;font-weight:800;letter-spacing:-.01em;margin:0 0 2px;}
.tcf-company-addr{font-size:.74rem;color:rgba(255,255,255,.78);line-height:1.6;margin:0;}
.tcf-conf-badge{text-align:right;}
.tcf-conf-title{font-size:.72rem;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;}
.tcf-conf-num{font-size:1rem;font-weight:800;color:#A5F3FC;letter-spacing:.02em;}
.tcf-conf-date{font-size:.72rem;color:rgba(255,255,255,.65);margin-top:4px;}

/* Status banner */
.tcf-status-bar{background:linear-gradient(90deg,#065F46,#059669);padding:10px 36px;display:flex;align-items:center;justify-content:space-between;}
.tcf-status-text{color:#fff;font-size:.82rem;font-weight:700;display:flex;align-items:center;gap:8px;}
.tcf-status-dot{width:8px;height:8px;background:#4ADE80;border-radius:50%;animation:pulse 2s infinite;}
@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(74,222,128,.5);}50%{box-shadow:0 0 0 6px rgba(74,222,128,0);}}

/* Body */
.tcf-body{padding:32px 36px;}

/* Section heading */
.tcf-section{margin-bottom:26px;}
.tcf-section-title{font-size:.72rem;font-weight:800;color:#4338CA;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;display:flex;align-items:center;gap:8px;}
.tcf-section-title::after{content:'';flex:1;height:1px;background:linear-gradient(to right,#C7D2FE,transparent);}

/* Info grid */
.tcf-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.tcf-info-item{background:#F8FAFC;border-radius:10px;padding:12px 16px;}
.tcf-info-lbl{font-size:.67rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.tcf-info-val{font-size:.9rem;font-weight:700;color:#0F172A;}

/* Services table */
.tcf-table{width:100%;border-collapse:collapse;border-radius:10px;overflow:hidden;border:1px solid #E2E8F0;}
.tcf-table th{background:#F8FAFC;padding:10px 14px;text-align:left;font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #E2E8F0;}
.tcf-table td{padding:11px 14px;font-size:.84rem;color:#0F172A;border-bottom:1px solid #F1F5F9;}
.tcf-table tr:last-child td{border-bottom:none;}
.tcf-table tr:nth-child(even) td{background:#FAFBFF;}

/* Amount summary */
.tcf-amt-box{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid #BFDBFE;border-radius:12px;padding:18px 20px;}
.tcf-amt-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.84rem;}
.tcf-amt-row+.tcf-amt-row{border-top:1px dashed #BFDBFE;}
.tcf-amt-lbl{color:#1E40AF;font-weight:500;}
.tcf-amt-val{font-weight:700;color:#1E3A8A;}
.tcf-amt-total{font-size:1rem !important;font-weight:800 !important;}
.tcf-amt-paid{color:#059669 !important;}
.tcf-amt-due{color:#DC2626 !important;}
.tcf-amt-divider{height:1.5px;background:#BFDBFE;margin:8px 0;}

/* Payment method */
.tcf-pay-pill{display:inline-flex;align-items:center;gap:5px;background:#EDE9FE;color:#5B21B6;font-size:.72rem;font-weight:700;padding:3px 11px;border-radius:20px;}

/* Cancellation policy */
.tcf-policy{background:#FFF7ED;border:1.5px solid #FED7AA;border-radius:12px;padding:18px 20px;}
.tcf-policy-title{font-size:.78rem;font-weight:800;color:#C2410C;margin-bottom:12px;display:flex;align-items:center;gap:7px;}
.tcf-policy-list{margin:0;padding:0;list-style:none;}
.tcf-policy-list li{font-size:.8rem;color:#7C2D12;padding:5px 0;display:flex;align-items:flex-start;gap:8px;border-bottom:1px dashed #FED7AA;}
.tcf-policy-list li:last-child{border-bottom:none;}
.tcf-policy-list li::before{content:'•';color:#EA580C;font-weight:700;flex-shrink:0;margin-top:1px;}

/* Footer */
.tcf-footer{background:#F8FAFC;border-top:1px solid #E2E8F0;padding:16px 36px;text-align:center;font-size:.74rem;color:#94A3B8;}
.tcf-footer strong{color:#475569;}

@media print{
  .tcf-page{background:#fff;padding:0;}
  .tcf-actions{display:none !important;}
  .tcf-doc{box-shadow:none;border-radius:0;}
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
    <span style="background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;border-radius:8px;padding:7px 14px;font-size:.8rem;font-weight:700;">
      ✅ {{ session('success') }}
    </span>
    @endif
  </div>

  {{-- Confirmation Document --}}
  <div class="tcf-doc">

    {{-- Header --}}
    <div class="tcf-header">
      <div class="tcf-logo-area">
        @if(websiteSetupValue('logo'))
        <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('logo')) }}"
             class="tcf-logo-img" alt="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}">
        @endif
        <div class="tcf-company">
          <div class="tcf-company-name">{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}</div>
          <div class="tcf-company-addr">
            {{ websiteSetupValue('address') ?? 'Varanasi, Uttar Pradesh, India' }}<br>
            📞 7080109917 &nbsp;|&nbsp; 7080109918 &nbsp;|&nbsp; 7080109919
            &nbsp;|&nbsp;
            ✉️ {{ websiteSetupValue('email') ?? 'info@visitkashi.in' }}
          </div>
        </div>
      </div>
      <div class="tcf-conf-badge">
        <div class="tcf-conf-title">Booking Confirmation</div>
        <div class="tcf-conf-num">{{ $booking->booking_number }}</div>
        <div class="tcf-conf-date">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</div>
      </div>
    </div>

    {{-- Status bar --}}
    <div class="tcf-status-bar">
      <div class="tcf-status-text">
        <span class="tcf-status-dot"></span>
        Booking Confirmed — Tour Package
      </div>
      <div style="color:rgba(255,255,255,.8);font-size:.76rem;">
        Ref: {{ $booking->booking_number }}
      </div>
    </div>

    <div class="tcf-body">

      {{-- Package Name Heading --}}
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
            if ($hName) $hdChips[] = ['icon'=>'🏨','label'=> $hName.($hCity?' ('.$hCity.')':''),'bg'=>'#F0FDF4','color'=>'#065F46','border'=>'#BBF7D0'];
        }
        if (!empty($hdCab['cab_type'])) {
            $cLabel = $hdCab['cab_type'].(!empty($hdCab['cab_route'])?' · '.$hdCab['cab_route']:'');
            $hdChips[] = ['icon'=>'🚕','label'=> $cLabel,'bg'=>'#FFFBEB','color'=>'#92400E','border'=>'#FDE68A'];
        }
        if (!empty($hdBoat['boat_type'])) {
            $bLabel = $hdBoat['boat_type'].(!empty($hdBoat['boat_ride'])?' · '.$hdBoat['boat_ride']:'');
            $hdChips[] = ['icon'=>'⛵','label'=> $bLabel,'bg'=>'#F0F9FF','color'=>'#0369A1','border'=>'#BAE6FD'];
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
                $inclMap = [
                    'hotel'=>['🏨','#F0FDF4','#065F46','#BBF7D0'],
                    'stay' =>['🏨','#F0FDF4','#065F46','#BBF7D0'],
                    'cab'  =>['🚕','#FFFBEB','#92400E','#FDE68A'],
                    'transport'=>['🚕','#FFFBEB','#92400E','#FDE68A'],
                    'boat' =>['⛵','#F0F9FF','#0369A1','#BAE6FD'],
                    'river'=>['⛵','#F0F9FF','#0369A1','#BAE6FD'],
                    'guide'=>['🧭','#F5F3FF','#5B21B6','#C4B5FD'],
                    'breakfast'=>['🍳','#FEF9C3','#713F12','#FDE68A'],
                    'lunch'=>['🍱','#FEF9C3','#713F12','#FDE68A'],
                    'dinner'=>['🍽','#FEF9C3','#713F12','#FDE68A'],
                    'puja'=>['🪔','#FFF7ED','#9A3412','#FED7AA'],
                    'aarti'=>['🪔','#FFF7ED','#9A3412','#FED7AA'],
                    'photo'=>['📸','#EFF6FF','#1D4ED8','#BFDBFE'],
                    'air'=>['✈️','#EEF2FF','#3730A3','#C7D2FE'],
                    'train'=>['🚂','#EEF2FF','#3730A3','#C7D2FE'],
                    'sightseeing'=>['🎯','#F5F3FF','#5B21B6','#C4B5FD'],
                ];
                foreach (array_filter(array_map('trim', explode(',', $inclStr))) as $inc) {
                    $lc = strtolower(preg_replace('/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27BF}]/u','', $inc));
                    $style = ['','#F8FAFC','#475569','#E2E8F0'];
                    foreach ($inclMap as $kw => $v) { if (str_contains($lc, $kw)) { $style=$v; break; } }
                    // Label already contains the emoji — set icon to '' to avoid duplication
                    $hdChips[] = ['icon'=>'','label'=>trim($inc),'bg'=>$style[1],'color'=>$style[2],'border'=>$style[3]];
                }
            }
        }

        $startDate = $booking->lead->booking_start_date ? \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M Y') : null;
        $endDate   = $booking->lead->booking_end_date   ? \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M Y')   : null;
        $pax       = $booking->lead->pax ?? null;
      @endphp
      @if($pkgName)
      <div style="text-align:center;padding:22px 28px 18px;border-bottom:1px solid #E2E8F0;margin-bottom:24px;">

        <div style="font-size:.65rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.12em;margin-bottom:8px;">Tour Package</div>
        <div style="font-size:1.55rem;font-weight:900;color:#1E1B4B;letter-spacing:-.02em;line-height:1.2;margin-bottom:12px;">{{ $pkgName }}</div>

        {{-- Date / Guests / Status pills --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:10px;flex-wrap:wrap;margin-bottom:14px;">
          @if($startDate)
          <span style="display:inline-flex;align-items:center;gap:5px;background:#EEF2FF;color:#3730A3;border-radius:20px;padding:4px 13px;font-size:.76rem;font-weight:700;">
            📅 {{ $startDate }}{{ $endDate ? ' → '.$endDate : '' }}
          </span>
          @endif
          @if($pax)
          <span style="display:inline-flex;align-items:center;gap:5px;background:#F0FDF4;color:#065F46;border-radius:20px;padding:4px 13px;font-size:.76rem;font-weight:700;">
            👥 {{ $pax }} Guest{{ $pax != 1 ? 's' : '' }}
          </span>
          @endif
          <span style="display:inline-flex;align-items:center;gap:5px;background:#DCFCE7;color:#166534;border-radius:20px;padding:4px 13px;font-size:.76rem;font-weight:700;">
            ✓ Confirmed
          </span>
        </div>

        {{-- Service chips --}}
        @if(count($hdChips) > 0)
        <div style="display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap;">
          @foreach($hdChips as $chip)
          <span style="display:inline-flex;align-items:center;gap:5px;background:{{ $chip['bg'] }};color:{{ $chip['color'] }};border:1px solid {{ $chip['border'] }};border-radius:20px;padding:5px 14px;font-size:.78rem;font-weight:700;">
            {{ $chip['icon'] }} {{ $chip['label'] }}
          </span>
          @endforeach
        </div>
        @endif

      </div>
      @endif

      {{-- Guest Details --}}
      <div class="tcf-section">
        <div class="tcf-section-title">👤 Guest Details</div>
        <div class="tcf-info-grid">
          <div class="tcf-info-item">
            <div class="tcf-info-lbl">Guest Name</div>
            <div class="tcf-info-val" style="font-size:1rem;">{{ $booking->lead->guest_name ?? '—' }}</div>
          </div>
          <div class="tcf-info-item">
            <div class="tcf-info-lbl">Mobile Number</div>
            <div class="tcf-info-val">+91 {{ $booking->lead->contact ?? '—' }}</div>
          </div>
          @if(!empty($booking->lead->email))
          <div class="tcf-info-item">
            <div class="tcf-info-lbl">Email Address</div>
            <div class="tcf-info-val">{{ $booking->lead->email }}</div>
          </div>
          @endif
          @if(!empty($booking->lead->pax))
          <div class="tcf-info-item">
            <div class="tcf-info-lbl">Number of Guests</div>
            <div class="tcf-info-val">{{ $booking->lead->pax }} Person(s)</div>
          </div>
          @endif
          @if(!empty($booking->lead->booking_start_date))
          <div class="tcf-info-item">
            <div class="tcf-info-lbl">Tour Start Date</div>
            <div class="tcf-info-val">{{ \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M, Y') }}</div>
          </div>
          @endif
          @if(!empty($booking->lead->booking_end_date))
          <div class="tcf-info-item">
            <div class="tcf-info-lbl">Tour End Date</div>
            <div class="tcf-info-val">{{ \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M, Y') }}</div>
          </div>
          @endif
        </div>
      </div>

      {{-- Tour Package --}}
      <div class="tcf-section">
        <div class="tcf-section-title">🗺️ Tour Package Details</div>

        {{-- Service chips: Hotels + Cab + Boat --}}
        @if(count($hdChips) > 0)
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;">
          @foreach($hdChips as $chip)
          <span style="display:inline-flex;align-items:center;gap:6px;background:{{ $chip['bg'] }};color:{{ $chip['color'] }};border:1.5px solid {{ $chip['border'] }};border-radius:10px;padding:7px 14px;font-size:.82rem;font-weight:700;">
            {{ $chip['icon'] }} {{ $chip['label'] }}
          </span>
          @endforeach
        </div>
        @endif

        <div class="tcf-info-item" style="background:#F5F3FF;border:1px solid #C4B5FD;margin-bottom:12px;">
          <div class="tcf-info-lbl" style="color:#7C3AED;">Package Summary</div>
          <div class="tcf-info-val" style="font-size:.88rem;line-height:1.6;color:#3730A3;">
            {{ $booking->lead->short_plan ?? '—' }}
          </div>
        </div>
        @if(!empty($booking->lead->plan_detail))
        <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:14px 16px;">
          <div style="font-size:.67rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Itinerary</div>
          <div style="font-size:.82rem;color:#334155;line-height:1.7;">{!! $booking->lead->plan_detail !!}</div>
        </div>
        @endif
      </div>

      {{-- Services Included — reuse already-parsed $hdData/$hdHotels/$hdCab/$hdBoat --}}
      @php
        $hotels     = $hdHotels;
        $cab        = $hdCab;
        $boat       = $hdBoat;
        $guide      = $hdData['guide'] ?? null;
        $inclusions = $hdData['inclusions'] ?? null;
        $hasServices = !empty($hotels) || !empty($cab['cab_type']) || !empty($boat['boat_type'])
                    || !empty($guide['guide_name']) || count($hdChips) > 0;
      @endphp

      @if($hasServices)
      <div class="tcf-section">
        <div class="tcf-section-title">🛎 Services Included</div>

        {{-- Inclusions pills --}}
        @if($inclusions)
        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;">
          @foreach(array_filter(array_map('trim', explode(',', $inclusions))) as $inc)
          <span style="background:#EEF2FF;color:#3730A3;border:1px solid #C7D2FE;border-radius:20px;padding:3px 11px;font-size:.74rem;font-weight:700;">{{ $inc }}</span>
          @endforeach
        </div>
        @endif

        {{-- Hotels --}}
        @if(!empty($hotels))
        <div style="margin-bottom:12px;">
          <div style="font-size:.68rem;font-weight:800;color:#059669;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">🏨 Hotel / Stay</div>
          @foreach($hotels as $i => $h)
          @php
            $hName   = $h['name']      ?? $h['hotel_name']     ?? '';
            $hCity   = $h['city']      ?? '';
            $hRoom   = $h['room_type'] ?? '';
            $hCi     = !empty($h['checkin'])       ? \Carbon\Carbon::parse($h['checkin'])->format('d M Y')       : (!empty($h['hotel_checkin'])  ? \Carbon\Carbon::parse($h['hotel_checkin'])->format('d M Y') : '');
            $hCo     = !empty($h['checkout'])      ? \Carbon\Carbon::parse($h['checkout'])->format('d M Y')      : (!empty($h['hotel_checkout']) ? \Carbon\Carbon::parse($h['hotel_checkout'])->format('d M Y') : '');
            $hNights = $h['nights'] ?? $h['hotel_nights'] ?? '';
          @endphp
          @if($hName || $hCity)
          <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:12px 16px;margin-bottom:7px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">{{ $hCity ? 'Hotel ('.$hCity.')' : 'Hotel' }}</div>
              <div style="font-size:.9rem;font-weight:800;color:#065F46;">{{ $hName ?: '—' }}</div>
              @if($hRoom)<div style="font-size:.74rem;color:#16A34A;margin-top:2px;">{{ $hRoom }}</div>@endif
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Check-in</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">{{ $hCi ?: '—' }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Check-out{{ $hNights ? ' ('.$hNights.' night'.($hNights!=1?'s':'').')' : '' }}</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">{{ $hCo ?: '—' }}</div>
            </div>
          </div>
          @endif
          @endforeach
        </div>
        @endif

        {{-- Cab --}}
        @if(!empty($cab['cab_type']) || !empty($cab['cab_route']))
        <div style="margin-bottom:12px;">
          <div style="font-size:.68rem;font-weight:800;color:#D97706;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">🚕 Cab / Transport</div>
          <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:12px 16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Vehicle</div>
              <div style="font-size:.9rem;font-weight:800;color:#92400E;">{{ $cab['cab_type'] ?? '—' }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Route</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">{{ $cab['cab_route'] ?? '—' }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Duration</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">
                @if(!empty($cab['cab_from'])) {{ \Carbon\Carbon::parse($cab['cab_from'])->format('d M') }} @endif
                @if(!empty($cab['cab_from']) && !empty($cab['cab_to'])) → @endif
                @if(!empty($cab['cab_to'])) {{ \Carbon\Carbon::parse($cab['cab_to'])->format('d M Y') }} @endif
                @if(empty($cab['cab_from']) && empty($cab['cab_to'])) — @endif
              </div>
            </div>
          </div>
        </div>
        @endif

        {{-- Boat --}}
        @if(!empty($boat['boat_type']) || !empty($boat['boat_ride']))
        <div style="margin-bottom:12px;">
          <div style="font-size:.68rem;font-weight:800;color:#0EA5E9;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">⛵ Boat / River Ride</div>
          <div style="background:#F0F9FF;border:1px solid #BAE6FD;border-radius:10px;padding:12px 16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Boat Type</div>
              <div style="font-size:.9rem;font-weight:800;color:#0369A1;">{{ $boat['boat_type'] ?? '—' }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Ride</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">{{ $boat['boat_ride'] ?? '—' }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Date &amp; Time</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">
                @if(!empty($boat['boat_date'])) {{ \Carbon\Carbon::parse($boat['boat_date'])->format('d M Y') }} @endif
                @if(!empty($boat['boat_time'])) &nbsp;·&nbsp; {{ date('g:i A', strtotime($boat['boat_time'])) }} @endif
                @if(empty($boat['boat_date']) && empty($boat['boat_time'])) — @endif
              </div>
            </div>
          </div>
        </div>
        @endif

        {{-- Guide --}}
        @if(!empty($guide['guide_name']))
        <div>
          <div style="font-size:.68rem;font-weight:800;color:#7C3AED;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">🧭 Guide</div>
          <div style="background:#F5F3FF;border:1px solid #C4B5FD;border-radius:10px;padding:12px 16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Guide Name</div>
              <div style="font-size:.9rem;font-weight:800;color:#5B21B6;">{{ $guide['guide_name'] }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Language</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">{{ $guide['guide_lang'] ?? '—' }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">Duration</div>
              <div style="font-size:.84rem;font-weight:700;color:#0F172A;">
                @if(!empty($guide['guide_from'])) {{ \Carbon\Carbon::parse($guide['guide_from'])->format('d M') }} @endif
                @if(!empty($guide['guide_from']) && !empty($guide['guide_to'])) → @endif
                @if(!empty($guide['guide_to'])) {{ \Carbon\Carbon::parse($guide['guide_to'])->format('d M Y') }} @endif
                @if(empty($guide['guide_from']) && empty($guide['guide_to'])) — @endif
              </div>
            </div>
          </div>
        </div>
        @endif

      </div>
      @endif

      {{-- Payment Summary --}}
      <div class="tcf-section">
        <div class="tcf-section-title">💳 Payment Summary</div>
        <div class="tcf-amt-box">
          <div class="tcf-amt-row">
            <span class="tcf-amt-lbl">Total Booking Amount</span>
            <span class="tcf-amt-val tcf-amt-total">₹{{ number_format($booking->total_amount, 0) }}</span>
          </div>
          @if($booking->discount_amount > 0)
          <div class="tcf-amt-row">
            <span class="tcf-amt-lbl">Discount</span>
            <span class="tcf-amt-val" style="color:#059669;">− ₹{{ number_format($booking->discount_amount, 0) }}</span>
          </div>
          @endif
          <div class="tcf-amt-divider"></div>
          <div class="tcf-amt-row">
            <span class="tcf-amt-lbl">Amount Paid</span>
            <span class="tcf-amt-val tcf-amt-paid">₹{{ number_format($booking->paid_amount ?? $booking->payments_sum_amount ?? 0, 0) }}</span>
          </div>
          <div class="tcf-amt-row">
            <span class="tcf-amt-lbl">Balance Due</span>
            @php $due = max(0, $booking->total_amount - ($booking->paid_amount ?? $booking->payments_sum_amount ?? 0)); @endphp
            <span class="tcf-amt-val {{ $due > 0 ? 'tcf-amt-due' : 'tcf-amt-paid' }}">
              {{ $due > 0 ? '₹'.number_format($due,0) : '✅ Fully Paid' }}
            </span>
          </div>
          @if($booking->payments->isNotEmpty())
          <div class="tcf-amt-divider"></div>
          <div class="tcf-amt-row">
            <span class="tcf-amt-lbl">Payment Method</span>
            <span class="tcf-pay-pill">{{ ucfirst(str_replace('_',' ',$booking->payments->first()->payment_method ?? 'Cash')) }}</span>
          </div>
          @endif
        </div>
      </div>

      {{-- Cancellation & Refund Policy --}}
      <div class="tcf-section">
        <div class="tcf-section-title">📋 Cancellation &amp; Refund Policy</div>
        <div class="tcf-policy">
          <div class="tcf-policy-title">
            ⚠️ Please read cancellation terms carefully
          </div>
          <ul class="tcf-policy-list">
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
      </div>

      {{-- Important Notes --}}
      <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:14px 18px;margin-top:-10px;">
        <div style="font-size:.72rem;font-weight:800;color:#065F46;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">✅ Important Information</div>
        <ul style="margin:0;padding:0;list-style:none;font-size:.79rem;color:#14532D;line-height:1.8;">
          <li>• Carry a valid government-issued ID proof during travel</li>
          <li>• Booking is subject to availability and confirmed only after payment</li>
          <li>• Contact us at <strong>7080109917 | 7080109918 | 7080109919</strong> for any changes or assistance</li>
          <li>• This is a computer-generated confirmation — no signature required</li>
        </ul>
      </div>

    </div>

    {{-- Footer --}}
    <div class="tcf-footer">
      <strong>{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}</strong>
      &nbsp;|&nbsp; {{ websiteSetupValue('address') ?? 'Varanasi, Uttar Pradesh' }}
      &nbsp;|&nbsp; 📞 7080109917 | 7080109918 | 7080109919
      &nbsp;|&nbsp; ✉️ {{ websiteSetupValue('email') ?? 'info@visitkashi.in' }}
      <div style="margin-top:6px;font-size:.7rem;">
        Thank you for choosing {{ websiteSetupValue('site_name') ?? 'Visit Kashi' }} — We look forward to serving you!
      </div>
    </div>

  </div>{{-- /tcf-doc --}}
</div>
@endsection
