@extends('admin.layouts.app')
@section('content')
@php
  $lead    = $booking->lead;
  $paidAmt = $booking->paid_amount ?? $booking->payments_sum_amount ?? 0;
  $dueAmt  = max(0, $booking->total_amount - $paidAmt);
  $payStatus = $dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'due');
@endphp
<style>
:root{
  --t-indigo:#4F46E5;--t-violet:#7C3AED;--t-amber:#F59E0B;
  --t-green:#10B981;--t-sky:#0EA5E9;--t-rose:#EF4444;
  --t-border:#E2E8F0;--t-bg:#F8FAFC;--t-text:#0F172A;--t-sub:#475569;--t-muted:#94A3B8;
  --t-r:14px;--t-s:0 1px 4px rgba(15,23,42,.06),0 4px 16px rgba(15,23,42,.04);
}
.tb-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.tb-header{background:linear-gradient(135deg,#4F46E5,#7C3AED);border-radius:var(--t-r);padding:18px 24px;display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(79,70,229,.25);gap:12px;flex-wrap:wrap;}
.tb-header::before{content:'🗺️';position:absolute;right:20px;bottom:-10px;font-size:80px;opacity:.08;pointer-events:none;}
.tb-header h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0;}
.tb-header p{color:rgba(255,255,255,.7);font-size:.75rem;margin:.2rem 0 0;}
.tb-header-btns{display:flex;gap:8px;flex-wrap:wrap;position:relative;z-index:1;}
.tb-hbtn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-size:.78rem;font-weight:700;text-decoration:none !important;border:none;cursor:pointer;transition:.18s;}
.tb-hbtn-back{background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);}
.tb-hbtn-back:hover{background:rgba(255,255,255,.28);color:#fff;}
.tb-hbtn-conf{background:#fff;color:#7C3AED;}
.tb-hbtn-conf:hover{background:#F5F3FF;color:#7C3AED;}
.tb-hbtn-pdf{background:#EF4444;color:#fff;}
.tb-hbtn-pdf:hover{opacity:.88;color:#fff;}
.tb-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:1100px){.tb-layout{grid-template-columns:1fr;}}
.tb-card{background:#fff;border-radius:var(--t-r);border:1px solid var(--t-border);box-shadow:var(--t-s);margin-bottom:16px;overflow:hidden;}
.tb-card-head{padding:13px 20px;border-bottom:1px solid var(--t-border);display:flex;align-items:center;gap:10px;background:#FAFBFF;}
.tb-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;}
.tb-card-title{font-size:.88rem;font-weight:700;color:var(--t-text);}
.tb-card-body{padding:18px 20px;}
.tb-label{font-size:.69rem;font-weight:700;color:var(--t-sub);text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.tb-label .req{color:var(--t-rose);margin-left:2px;}
.tb-input,.tb-select,.tb-textarea{width:100%;border:1.5px solid var(--t-border);border-radius:9px;padding:9px 13px;font-size:.86rem;color:var(--t-text);background:#FAFBFF;outline:none;transition:.15s;font-family:inherit;}
.tb-input:focus,.tb-select:focus,.tb-textarea:focus{border-color:var(--t-indigo);box-shadow:0 0 0 3px rgba(79,70,229,.1);background:#fff;}
.tb-textarea{resize:vertical;min-height:72px;}
.tb-prefix-wrap{position:relative;}
.tb-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;font-weight:600;color:var(--t-sub);pointer-events:none;}
.tb-input-pl{padding-left:40px !important;}
.tb-rupee-wrap{position:relative;}
.tb-rupee{position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:.82rem;font-weight:600;color:var(--t-sub);pointer-events:none;}
.tb-input-rs{padding-left:26px !important;}
.tb-svc{border:1.5px solid var(--t-border);border-radius:11px;margin-bottom:10px;overflow:hidden;}
.tb-svc-head{display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--t-bg);border-bottom:1px solid var(--t-border);}
.tb-svc-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.tb-svc-label{font-size:.82rem;font-weight:700;color:var(--t-text);flex:1;}
.tb-svc-badge{font-size:.75rem;font-weight:800;color:var(--t-amber);background:#FEF3C7;padding:2px 10px;border-radius:20px;border:1px solid #FDE68A;}
.tb-svc-body{padding:14px 16px;}
.tb-exp-bar{display:grid;grid-template-columns:repeat(4,1fr) auto;gap:8px;background:linear-gradient(135deg,#FFFBEB,#FEF3C7);border:1px solid #FDE68A;border-radius:11px;padding:12px 16px;margin-top:14px;align-items:center;}
@media(max-width:600px){.tb-exp-bar{grid-template-columns:1fr 1fr;}}
.tb-exp-item{text-align:center;}
.tb-exp-item-icon{font-size:.9rem;margin-bottom:2px;}
.tb-exp-item-lbl{font-size:.62rem;color:#92400E;font-weight:600;margin-bottom:3px;}
.tb-exp-item-val{font-size:.95rem;font-weight:800;color:#78350F;}
.tb-exp-total{background:#fff;border-radius:8px;padding:8px 14px;text-align:center;border:1.5px solid #FDE68A;}
.tb-exp-total-lbl{font-size:.62rem;color:#1E40AF;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;}
.tb-exp-total-val{font-size:1.1rem;font-weight:800;color:#1E3A8A;}
.tb-sidebar{position:sticky;top:80px;}
.tb-sidebar-card{background:#fff;border-radius:var(--t-r);border:1px solid var(--t-border);box-shadow:var(--t-s);padding:18px;margin-bottom:14px;}
.tb-sidebar-title{font-size:.84rem;font-weight:800;color:var(--t-text);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--t-border);}
.tb-amt-row{margin-bottom:12px;}
.tb-balance-box{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid #BFDBFE;border-radius:11px;padding:12px 16px;margin:12px 0;}
.tb-balance-lbl{font-size:.62rem;font-weight:800;color:#1E40AF;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;}
.tb-balance-row{display:flex;align-items:center;justify-content:space-between;}
.tb-balance-amt{font-size:1.8rem;font-weight:800;color:#1E3A8A;}
.tb-badge{font-size:.62rem;font-weight:800;letter-spacing:.04em;padding:3px 9px;border-radius:20px;text-transform:uppercase;}
.tb-badge-due{background:#EF4444;color:#fff;}
.tb-badge-paid{background:#10B981;color:#fff;}
.tb-badge-partial{background:#3B82F6;color:#fff;}
.tb-profit-box{background:linear-gradient(135deg,#F0FDF4,#DCFCE7);border:1px solid #BBF7D0;border-radius:11px;padding:14px 16px;margin-top:14px;}
.tb-profit-row{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;padding:5px 0;}
.tb-profit-row+.tb-profit-row{border-top:1px dashed #BBF7D0;}
.tb-profit-lbl{color:#065F46;}
.tb-profit-val{font-weight:700;color:#14532D;}
.tb-profit-final{font-size:1.1rem !important;font-weight:800 !important;}
.tb-profit-pct{font-size:.72rem;background:#D1FAE5;color:#065F46;font-weight:700;padding:2px 8px;border-radius:20px;}
.tb-submit{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:10px;padding:13px 20px;font-size:.92rem;font-weight:700;cursor:pointer;transition:.2s;}
.tb-submit:hover{opacity:.9;transform:translateY(-1px);}
.tb-submit svg{width:17px;height:17px;stroke:#fff;}
.tb-alert-ok{background:#D1FAE5;border:1.5px solid #6EE7B7;color:#065F46;display:flex;align-items:center;gap:10px;border-radius:11px;padding:11px 16px;margin-bottom:18px;font-size:.83rem;font-weight:600;}
.tb-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;display:flex;align-items:center;gap:10px;border-radius:11px;padding:11px 16px;margin-bottom:18px;font-size:.83rem;font-weight:600;}
.tb-bk-id{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.2);color:#fff;font-size:.78rem;font-weight:700;padding:4px 12px;border-radius:20px;margin-top:4px;}
.incl-grid{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:10px;}
.incl-pill{display:inline-flex;align-items:center;gap:5px;padding:6px 13px;border-radius:20px;border:1.5px solid var(--t-border);background:#fff;font-size:.78rem;font-weight:600;color:#475569;cursor:pointer;user-select:none;transition:all .18s;}
.incl-pill:hover{border-color:var(--t-indigo);color:var(--t-indigo);background:#EEF2FF;}
.incl-pill.active{border-color:var(--t-indigo);background:var(--t-indigo);color:#fff;}
</style>

<div class="tb-page">

@if(session('success'))
<div class="tb-alert-ok">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="tb-alert-err">❌ {{ session('error') }}</div>
@endif
{{-- ── Sticky top bar ── --}}
<div style="background:#fff;border-bottom:1px solid #E5E7EB;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:54px;position:sticky;top:64px;z-index:99;gap:12px;margin-top:50px;">
  <div style="display:flex;align-items:center;gap:8px;font-size:.8rem;color:#6B7280;">
    <a href="{{ route('bookings.index', ['service_type'=>'tour','status'=>'confirmed']) }}" style="color:#4F46E5;font-weight:600;text-decoration:none;">Tour Bookings</a>
    <span>/</span>
    <span style="font-weight:600;color:#111827;">{{ $booking->booking_number }}</span>
    <span style="background:#EDE9FE;color:#6D28D9;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:12px;margin-left:4px;">Edit Mode</span>
  </div>
  <div style="display:flex;gap:8px;align-items:center;">
    <a href="{{ route('tour-booking.view', $booking->id) }}" style="display:inline-flex;align-items:center;gap:5px;padding:7px 13px;border-radius:7px;font-size:.79rem;font-weight:600;background:transparent;color:#6B7280;border:1px solid #E5E7EB;text-decoration:none;">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      View
    </a>
    <a href="{{ route('tour-booking.confirmation', $booking->id) }}" target="_blank" style="display:inline-flex;align-items:center;gap:5px;padding:7px 13px;border-radius:7px;font-size:.79rem;font-weight:600;background:#059669;color:#fff;text-decoration:none;">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Confirmation
    </a>
    <button form="tb-form" type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:7px;font-size:.82rem;font-weight:700;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;cursor:pointer;">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      Save Changes
    </button>
  </div>
</div>

@if(session('success'))
<div style="background:#D1FAE5;border:1.5px solid #6EE7B7;color:#065F46;display:flex;align-items:center;gap:8px;padding:10px 24px;font-size:.82rem;font-weight:600;">
  ✅ {{ session('success') }}
</div>
@endif
@if($errors->any())
<div style="background:#FEF2F2;border-left:4px solid #EF4444;color:#991B1B;padding:10px 24px;font-size:.82rem;">
  <strong>Please fix:</strong>
  @foreach($errors->all() as $e) <span style="margin-left:8px;">· {{ $e }}</span> @endforeach
</div>
@endif

{{-- Header --}}
<div class="tb-header" style="margin-top:0;border-radius:0;box-shadow:none;border-bottom:1px solid rgba(255,255,255,.15);">
  <div style="position:relative;z-index:1;">
    <h1>🗺️ Edit Tour Booking</h1>
    <p>
      <span class="tb-bk-id">{{ $booking->booking_number }}</span>
      &nbsp; Booked: {{ $booking->booking_date->format('d M Y') }}
      @php $payStatus2 = $dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'due'); @endphp
      &nbsp;
      <span style="background:rgba(255,255,255,.2);padding:2px 9px;border-radius:12px;font-size:.72rem;font-weight:700;">
        {{ ['paid'=>'✅ Fully Paid','partial'=>'⚡ Partial','due'=>'⏳ Due'][$payStatus2] }}
      </span>
    </p>
  </div>
  <div class="tb-header-btns">
</div>

<form action="{{ route('tour-booking.update', $booking->id) }}" method="POST" id="tb-form">
@csrf @method('PUT')
<div class="tb-layout">

{{-- ════ LEFT ════ --}}
<div>

  {{-- 1. Guest Info --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#EDE9FE;">👤</div>
      <div class="tb-card-title">Guest Information</div>
    </div>
    <div class="tb-card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="tb-label">Guest Name <span class="req">*</span></label>
          <input type="text" name="guest_name" class="tb-input" required value="{{ old('guest_name') ?: $lead->guest_name }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Mobile <span class="req">*</span></label>
          @include('admin.partials.phone-input', [
              'name'     => 'phone',
              'value'    => old('phone', $lead->contact ?? ''),
              'required' => true,
              'placeholder' => '9876543210',
          ])
          </div>
        </div>
        <div class="col-md-3">
          <label class="tb-label">Email</label>
          <input type="email" name="email" class="tb-input" value="{{ old('email') ?: $lead->email }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Lead Source <span class="req">*</span></label>
          <select name="lead_source_id" class="tb-select" required>
            <option value="">Select…</option>
            @foreach($leadSources as $s)
            <option value="{{ $s->id }}" {{ (old('lead_source_id') ?: $lead->lead_source_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  {{-- 2. Tour Info --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#EDE9FE;">📅</div>
      <div class="tb-card-title">Tour Package Info</div>
    </div>
    <div class="tb-card-body">
      <div class="row g-3">
        <div class="col-md-5">
          <label class="tb-label">Package Name <span class="req">*</span></label>
@php
  // Safely extract package name (Str::after returns full string when needle missing — DON'T use it)
  $qNotes  = $booking->quotation?->notes ?? '';
  $pkgName = '';
  if ($qNotes && str_contains($qNotes, 'Tour Package — ')) {
      // Old plain-text format: "Tour Package — My Package Name"
      $pkgName = trim(substr($qNotes, strpos($qNotes, 'Tour Package — ') + strlen('Tour Package — ')));
  }
  if (!$pkgName && !empty($serviceData['package_name'])) {
      $pkgName = $serviceData['package_name'];
  }
  if (!$pkgName && $lead->short_plan) {
      $pkgName = trim(explode(' | ', $lead->short_plan)[0]);
  }
  if (!$pkgName) $pkgName = $booking->quotation?->items?->first()?->notes ?? '';
@endphp
          <input type="text" name="package_name" id="tb-pkg-name" class="tb-input" required oninput="tbBuildSummary()"
                 value="{{ old('package_name') ?: $pkgName }}">
        </div>
        <div class="col-md-2">
          <label class="tb-label">Adults</label>
          <input type="number" name="adults" id="tb-adults" class="tb-input" min="1" oninput="tbBuildSummary()"
                 value="{{ old('adults') ?: ($lead->pax ?? 1) }}">
        </div>
        <div class="col-md-2">
          <label class="tb-label">Children</label>
          <input type="number" name="children" id="tb-children" class="tb-input" min="0" value="{{ old('children', 0) }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Booking Date <span class="req">*</span></label>
@php
  $tourStartVal = optional($lead->booking_start_date)->format('Y-m-d')
               ?? optional($booking->booking_date)->format('Y-m-d')
               ?? date('Y-m-d');
@endphp
          <input type="date" name="tour_start" id="tb-tour-start" class="tb-input" required oninput="tbBuildSummary()"
                 value="{{ old('tour_start') ?: $tourStartVal }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Return Date</label>
          <input type="date" name="tour_end" id="tb-tour-end" class="tb-input" oninput="tbBuildSummary()"
                 value="{{ old('tour_end') ?: optional($lead->booking_end_date)->format('Y-m-d') }}">
        </div>
        <div class="col-12">
          <label class="tb-label">Inclusions</label>
          @php
            // Pre-fill inclusions from stored JSON or parse from short_plan
            $storedIncl = $serviceData['inclusions'] ?? '';
            if (!$storedIncl && $lead->short_plan) {
                if (preg_match('/Incl:\s*(.+?)(\s*\||$)/iu', $lead->short_plan, $m)) {
                    $storedIncl = trim($m[1]);
                }
            }
            $storedInclArr = array_filter(array_map('trim', explode(',', $storedIncl)));
          @endphp
          <input type="hidden" name="inclusions" id="tb-inclusions-hidden" value="{{ old('inclusions', $storedIncl) }}">
          <div class="incl-grid" id="incl-pills">
            @foreach([
              '🏨 Hotel / Stay','🚕 Cab / Transport','⛵ Boat Ride',
              '🧭 Guide','🍳 Breakfast','🍱 Lunch','🍽 Dinner',
              '🪔 Puja / Aarti','📸 Photography','✈️ Air Tickets',
              '🚂 Train Tickets','🎯 Sightseeing','💐 Flowers / Garland',
            ] as $incl)
            <label class="incl-pill {{ collect($storedInclArr)->contains($incl) ? 'active' : '' }}"
                   onclick="toggleIncl('{{ addslashes($incl) }}', this)">{{ $incl }}</label>
            @endforeach
          </div>
          <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
            <input type="text" id="tb-incl-custom" class="tb-input" style="flex:1;" placeholder="Add custom inclusion…">
            <button type="button" onclick="addCustomIncl()"
                    style="background:var(--t-indigo);color:#fff;border:none;border-radius:8px;padding:8px 14px;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;">+ Add</button>
          </div>
          <div id="incl-preview" style="{{ $storedIncl ? '' : 'display:none;' }}margin-top:8px;background:#EEF2FF;border-radius:8px;padding:7px 12px;font-size:.76rem;color:#4338CA;font-weight:600;">{{ $storedIncl ? '✓ '.$storedIncl : '' }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- 3. B2B Expense --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#FEF3C7;">💰</div>
      <div class="tb-card-title">B2B Expense Breakdown <span style="font-size:.7rem;font-weight:400;color:var(--t-muted);">— Vendor / Actual Cost</span></div>
    </div>
    <div class="tb-card-body">

@php
  $sd = $serviceData ?? [];
  $h  = $sd['hotel'] ?? []; $c = $sd['cab'] ?? []; $bt = $sd['boat'] ?? []; $g = $sd['guide'] ?? [];
  function sdVal($arr,$key,$fallback=''){ return old($key) ?: ($arr[$key] ?? $fallback); }
  function sdSel($arr,$key,$option){ $saved = old($key) ?: ($arr[$key] ?? ''); return $saved === $option ? 'selected' : ''; }
@endphp

      {{-- Hotel / Stay — Multi-row (same as create) --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#10B981;"></span>
          <span class="tb-svc-label">🏨 Hotel / Stay</span>
          <span class="tb-svc-badge" id="tb-hotel-badge">₹{{ number_format($expenses['hotel']) }}</span>
        </div>
        <div class="tb-svc-body" style="padding-bottom:6px;">
          <div id="hotel-rows"></div>
          <input type="hidden" name="exp_hotel" id="tb-exp-hotel" value="{{ old('exp_hotel') ?: $expenses['hotel'] }}">
          <button type="button" onclick="tbAddHotelRow()"
                  style="display:inline-flex;align-items:center;gap:6px;background:#F0FDF4;border:1.5px dashed #10B981;color:#065F46;border-radius:9px;padding:7px 14px;font-size:.78rem;font-weight:700;cursor:pointer;margin-top:6px;width:100%;justify-content:center;transition:.18s;"
                  onmouseover="this.style.background='#DCFCE7'" onmouseout="this.style.background='#F0FDF4'">
            ＋ Add Another Hotel / Destination
          </button>
        </div>
      </div>

      {{-- Cab --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#F59E0B;"></span>
          <span class="tb-svc-label">🚕 Cab / Transport</span>
          <span class="tb-svc-badge" id="tb-cab-badge">₹{{ number_format($expenses['cab']) }}</span>
        </div>
        <div class="tb-svc-body">
          <div class="row g-2">
            <div class="col-md-3"><label class="tb-label">Vehicle Type</label>
              <select name="cab_type" class="tb-select">
                @foreach(['Sedan / Swift Dzire','SUV / Innova Crysta','Tempo Traveller 12 Seat','Tempo Traveller 20 Seat','Bus 26+ Seat','Other'] as $opt)
                <option {{ sdSel($c,'cab_type',$opt) }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3"><label class="tb-label">Route</label>
              <input type="text" name="cab_route" class="tb-input" placeholder="e.g. Airport → Hotel"
                     value="{{ sdVal($c,'cab_route') }}">
            </div>
            <div class="col-md-2"><label class="tb-label">From Date</label>
              <input type="date" name="cab_from" class="tb-input" value="{{ sdVal($c,'cab_from') }}">
            </div>
            <div class="col-md-2"><label class="tb-label">To Date</label>
              <input type="date" name="cab_to" class="tb-input" value="{{ sdVal($c,'cab_to') }}">
            </div>
            <div class="col-md-2">
              <label class="tb-label" style="color:#D97706;font-weight:800;">B2B Expense (₹)</label>
              <div class="tb-rupee-wrap"><span class="tb-rupee">₹</span>
                <input type="number" name="exp_cab" id="tb-exp-cab" class="tb-input tb-input-rs" min="0" oninput="tbCalcExpense()"
                       value="{{ old('exp_cab') ?: $expenses['cab'] }}" style="border-color:#FDE68A !important;background:#FFFBEB !important;">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Boat --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#0EA5E9;"></span>
          <span class="tb-svc-label">⛵ Boat / River Ride</span>
          <span class="tb-svc-badge" id="tb-boat-badge">₹{{ number_format($expenses['boat']) }}</span>
        </div>
        <div class="tb-svc-body">
          <div class="row g-2">
            <div class="col-md-3"><label class="tb-label">Boat Type</label>
              <select name="boat_type" class="tb-select">
                @foreach(['Normal Motor Boat','Light Motor Boat','Premium Motor Boat','Luxury Yacht','Bajra Boat','Cruise'] as $opt)
                <option {{ sdSel($bt,'boat_type',$opt) }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3"><label class="tb-label">Ride Type</label>
              <select name="boat_ride" class="tb-select">
                @foreach(['Morning Boat Ride','Evening Boat Ride','Ganga Aarti Evening','Sunrise Ride','Full Day'] as $opt)
                <option {{ sdSel($bt,'boat_ride',$opt) }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2"><label class="tb-label">Date</label>
              <input type="date" name="boat_date" class="tb-input" value="{{ sdVal($bt,'boat_date') }}">
            </div>
            <div class="col-md-2"><label class="tb-label">Time</label>
              <input type="time" name="boat_time" class="tb-input" value="{{ sdVal($bt,'boat_time') }}">
            </div>
            <div class="col-md-2">
              <label class="tb-label" style="color:#D97706;font-weight:800;">B2B Expense (₹)</label>
              <div class="tb-rupee-wrap"><span class="tb-rupee">₹</span>
                <input type="number" name="exp_boat" id="tb-exp-boat" class="tb-input tb-input-rs" min="0" oninput="tbCalcExpense()"
                       value="{{ old('exp_boat') ?: $expenses['boat'] }}" style="border-color:#FDE68A !important;background:#FFFBEB !important;">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Guide --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#8B5CF6;"></span>
          <span class="tb-svc-label">🧭 Guide</span>
          <span class="tb-svc-badge" id="tb-guide-badge">₹{{ number_format($expenses['guide']) }}</span>
        </div>
        <div class="tb-svc-body">
          <div class="row g-2">
            <div class="col-md-4"><label class="tb-label">Guide Name</label>
              <input type="text" name="guide_name" class="tb-input" placeholder="e.g. Ramesh Kumar"
                     value="{{ sdVal($g,'guide_name') }}">
            </div>
            <div class="col-md-2"><label class="tb-label">From Date</label>
              <input type="date" name="guide_from" class="tb-input" value="{{ sdVal($g,'guide_from') }}">
            </div>
            <div class="col-md-2"><label class="tb-label">To Date</label>
              <input type="date" name="guide_to" class="tb-input" value="{{ sdVal($g,'guide_to') }}">
            </div>
            <div class="col-md-2"><label class="tb-label">Language</label>
              <select name="guide_lang" class="tb-select">
                @foreach(['Hindi','English','Hindi + English','Foreign Language'] as $opt)
                <option {{ sdSel($g,'guide_lang',$opt) }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label class="tb-label" style="color:#D97706;font-weight:800;">B2B Expense (₹)</label>
              <div class="tb-rupee-wrap"><span class="tb-rupee">₹</span>
                <input type="number" name="exp_guide" id="tb-exp-guide" class="tb-input tb-input-rs" min="0" oninput="tbCalcExpense()"
                       value="{{ old('exp_guide') ?: $expenses['guide'] }}" style="border-color:#FDE68A !important;background:#FFFBEB !important;">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Expense bar --}}
      <div class="tb-exp-bar">
        <div class="tb-exp-item"><div class="tb-exp-item-icon">🏨</div><div class="tb-exp-item-lbl">Hotel</div><div class="tb-exp-item-val" id="tb-exp-hotel-show">₹{{ number_format($expenses['hotel']) }}</div></div>
        <div class="tb-exp-item"><div class="tb-exp-item-icon">🚕</div><div class="tb-exp-item-lbl">Cab</div><div class="tb-exp-item-val" id="tb-exp-cab-show">₹{{ number_format($expenses['cab']) }}</div></div>
        <div class="tb-exp-item"><div class="tb-exp-item-icon">⛵</div><div class="tb-exp-item-lbl">Boat</div><div class="tb-exp-item-val" id="tb-exp-boat-show">₹{{ number_format($expenses['boat']) }}</div></div>
        <div class="tb-exp-item"><div class="tb-exp-item-icon">🧭</div><div class="tb-exp-item-lbl">Guide</div><div class="tb-exp-item-val" id="tb-exp-guide-show">₹{{ number_format($expenses['guide']) }}</div></div>
        <div class="tb-exp-total">
          <div class="tb-exp-total-lbl">Total Expense</div>
          <div class="tb-exp-total-val" id="tb-total-exp">₹{{ number_format(array_sum($expenses)) }}</div>
        </div>
      </div>

    </div>
  </div>

  {{-- 4. Trip Summary --}}
  <div class="tb-card">
    <div class="tb-card-head"><div class="tb-icon" style="background:#EDE9FE;">📝</div><div class="tb-card-title">Trip Summary</div></div>
    <div class="tb-card-body">
      <textarea name="trip_summary" id="tb-summary" class="tb-textarea tb-input" rows="3" data-edited="1">{{ old('trip_summary') ?: $lead->short_plan }}</textarea>
    </div>
  </div>

  {{-- 5. Itinerary --}}
  <div class="tb-card">
    <div class="tb-card-head"><div class="tb-icon" style="background:#EDE9FE;">🗓️</div><div class="tb-card-title">Itinerary <span style="font-size:.7rem;font-weight:400;color:var(--t-muted);">(optional)</span></div></div>
    <div class="tb-card-body">
      <textarea name="itinerary" id="tb-itinerary" style="display:none;">{{ old('itinerary') ?: $lead->plan_detail }}</textarea>
      <div id="tb-itinerary-editor" style="border:1.5px solid var(--t-border);border-radius:10px;min-height:180px;"></div>
    </div>
  </div>

  {{-- 6. Internal Notes --}}
  <div class="tb-card">
    <div class="tb-card-body" style="padding:14px 18px;">
      <label class="tb-label">Internal Notes <span style="font-weight:400;color:var(--t-muted);text-transform:none;">(staff only)</span></label>
      <textarea name="internal_notes" class="tb-textarea tb-input" rows="2">{{ old('internal_notes') ?: $internalNotes }}</textarea>
    </div>
  </div>

</div>{{-- /left --}}

{{-- ════ RIGHT SIDEBAR ════ --}}
<div class="tb-sidebar">

  {{-- Booking Amount --}}
  <div class="tb-sidebar-card">
    <div class="tb-sidebar-title">💳 Booking Amount</div>

    <div class="tb-amt-row">
      <label class="tb-label">Total Booking Amount (₹) <span class="req">*</span></label>
      <div class="tb-rupee-wrap">
        <span class="tb-rupee">₹</span>
        <input type="number" name="booking_amount" id="tb-booking-amt" class="tb-input tb-input-rs"
               min="0" step="1" required style="font-size:1.05rem;font-weight:700;"
               value="{{ old('booking_amount') ?: ($booking->total_amount + ($booking->discount_amount ?? 0)) }}"
               oninput="tbCalcBalance();tbCalcExpense()">
      </div>
    </div>

    <div class="tb-amt-row">
      <label class="tb-label">Discount (₹)</label>
      <div class="tb-rupee-wrap"><span class="tb-rupee">₹</span>
        <input type="number" name="discount" id="tb-discount" class="tb-input tb-input-rs" min="0"
               value="{{ old('discount') !== null && old('discount') !== '' ? old('discount') : ($booking->discount_amount ?? 0) }}" oninput="tbCalcBalance();tbCalcExpense()">
      </div>
    </div>

    <div class="tb-amt-row">
      <label class="tb-label">Advance Paid (₹)</label>
      <div class="tb-rupee-wrap"><span class="tb-rupee">₹</span>
        <input type="number" name="advance_paid" id="tb-advance" class="tb-input tb-input-rs" min="0"
               value="{{ old('advance_paid') ?: $paidAmt }}" oninput="tbCalcBalance()">
      </div>
    </div>

    <div class="tb-balance-box">
      <div class="tb-balance-lbl">Balance Due</div>
      <div class="tb-balance-row">
        <div class="tb-balance-amt" id="tb-balance">₹{{ number_format($dueAmt) }}</div>
        <span class="tb-badge tb-badge-{{ $payStatus }}" id="tb-pay-badge">{{ strtoupper($payStatus) }}</span>
      </div>
    </div>

    <div class="tb-amt-row">
      <label class="tb-label">Payment Status</label>
      <select name="payment_status" id="tb-pay-status" class="tb-select" onchange="tbUpdateBadge()">
        <option value="due"     {{ $payStatus === 'due'     ? 'selected' : '' }}>Due</option>
        <option value="paid"    {{ $payStatus === 'paid'    ? 'selected' : '' }}>Paid</option>
        <option value="partial" {{ $payStatus === 'partial' ? 'selected' : '' }}>Partial</option>
      </select>
    </div>
    <div class="tb-amt-row" style="margin-bottom:0;">
      <label class="tb-label">Payment Method</label>
      <select name="payment_method" class="tb-select">
        <option value="">Select…</option>
        @php $pm = $booking->payments->first()?->payment_method ?? ''; @endphp
        <option value="cash"          {{ $pm==='cash'          ? 'selected':'' }}>Cash</option>
        <option value="upi"           {{ $pm==='upi'           ? 'selected':'' }}>UPI / GPay / PhonePe</option>
        <option value="bank_transfer" {{ $pm==='bank_transfer' ? 'selected':'' }}>Bank Transfer</option>
        <option value="cheque"        {{ $pm==='cheque'        ? 'selected':'' }}>Cheque</option>
        <option value="online"        {{ $pm==='online'        ? 'selected':'' }}>Online Payment</option>
      </select>
    </div>
  </div>

  {{-- Marginal Profit --}}
  <div class="tb-sidebar-card">
    <div class="tb-sidebar-title">📊 Marginal Profit</div>
    @php
      $totalExp    = array_sum($expenses);
      $net         = max(0, $booking->total_amount);
      $profit      = $net - $totalExp;
      $profitPct   = $net > 0 ? round(($profit / $net) * 100) : 0;
    @endphp
    <div class="tb-profit-box">
      <div class="tb-profit-row">
        <span class="tb-profit-lbl">Booking Amount</span>
        <span class="tb-profit-val" id="tb-p-booking">₹{{ number_format($net) }}</span>
      </div>
      <div class="tb-profit-row">
        <span class="tb-profit-lbl">Total B2B Expense</span>
        <span class="tb-profit-val" style="color:#EF4444;" id="tb-p-expense">₹{{ number_format($totalExp) }}</span>
      </div>
      <div class="tb-profit-row" style="border-top:2px solid #A7F3D0;padding-top:8px;margin-top:4px;">
        <span class="tb-profit-lbl" style="font-weight:800;">Marginal Profit</span>
        <span class="tb-profit-val tb-profit-final" id="tb-p-profit" style="color:{{ $profit >= 0 ? '#14532D' : '#EF4444' }};">₹{{ number_format(max(0,$profit)) }}</span>
      </div>
      <div style="text-align:right;margin-top:6px;">
        <span class="tb-profit-pct" id="tb-p-pct" style="background:{{ $profitPct >= 0 ? '#D1FAE5' : '#FEE2E2' }};color:{{ $profitPct >= 0 ? '#065F46' : '#DC2626' }};">{{ max(0,$profitPct) }}%</span>
      </div>
      <div style="font-size:.67rem;color:#94A3B8;text-align:center;margin-top:8px;">Booking − Expenses = Profit</div>
    </div>
  </div>

  {{-- Status --}}
  <div class="tb-sidebar-card" style="padding:14px 16px;">
    <label class="tb-label">Booking Status</label>
    <input type="text" class="tb-input" value="✅ Confirmed — {{ $booking->booking_number }}" readonly style="background:#F0FDF4;color:#15803D;font-weight:600;font-size:.8rem;">
  </div>

  {{-- Save (also in sticky top bar) --}}
  <button type="submit" class="tb-submit">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    Save Changes
  </button>
  <a href="{{ route('tour-booking.view', $booking->id) }}" style="display:flex;align-items:center;justify-content:center;gap:6px;width:100%;background:#F9FAFB;color:#6B7280;border:1.5px solid #E5E7EB;border-radius:10px;padding:11px;font-size:.84rem;font-weight:600;text-decoration:none;margin-top:8px;">
    Cancel &amp; View
  </a>

</div>{{-- /sidebar --}}
</div>{{-- /layout --}}
</form>

{{-- ══ Payment History — outside form, full width ══ --}}
<div class="tb-card" style="margin-top:20px;">
  <div class="tb-card-head">
    <div class="tb-icon" style="background:#DCFCE7;">💸</div>
    <div class="tb-card-title">Payment History — Date Wise</div>
    <button type="button" onclick="document.getElementById('add-payment-form').style.display=document.getElementById('add-payment-form').style.display==='none'?'block':'none'"
            style="margin-left:auto;background:linear-gradient(135deg,#059669,#10B981);color:#fff;border:none;border-radius:8px;padding:6px 14px;font-size:.74rem;font-weight:700;cursor:pointer;">
      + Add Payment
    </button>
  </div>
  <div class="tb-card-body">

    {{-- Existing payments table --}}
    @if($booking->payments->isNotEmpty())
    <table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
      <thead>
        <tr style="background:#F8FAFC;border-bottom:2px solid #E2E8F0;">
          <th style="padding:8px 12px;text-align:left;font-size:.68rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;">Date</th>
          <th style="padding:8px 12px;text-align:left;font-size:.68rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;">Amount</th>
          <th style="padding:8px 12px;text-align:left;font-size:.68rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;">Method</th>
          <th style="padding:8px 12px;text-align:left;font-size:.68rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;">Note</th>
          <th style="padding:8px 12px;text-align:left;font-size:.68rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;">Type</th>
        </tr>
      </thead>
      <tbody>
        @php $runningTotal = 0; @endphp
        @foreach($booking->payments->sortBy('payment_date') as $payment)
        @php $runningTotal += $payment->amount; @endphp
        <tr style="border-bottom:1px solid #F1F5F9;">
          <td style="padding:10px 12px;font-size:.82rem;font-weight:600;color:#0F172A;">
            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
          </td>
          <td style="padding:10px 12px;font-size:.88rem;font-weight:800;color:#059669;">
            ₹{{ number_format($payment->amount) }}
          </td>
          <td style="padding:10px 12px;font-size:.8rem;color:#475569;">
            {{ ucfirst(str_replace('_',' ',$payment->payment_method ?? 'Cash')) }}
          </td>
          <td style="padding:10px 12px;font-size:.78rem;color:#94A3B8;">
            {{ $payment->notes ?? '—' }}
          </td>
          <td style="padding:10px 12px;">
            @if($loop->first)
              <span style="background:#DBEAFE;color:#1E40AF;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:12px;">Advance</span>
            @else
              <span style="background:#D1FAE5;color:#065F46;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:12px;">Part Payment</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="background:#F0FDF4;border-top:2px solid #BBF7D0;">
          <td style="padding:10px 12px;font-size:.8rem;font-weight:700;color:#065F46;">Total Paid</td>
          <td style="padding:10px 12px;font-size:.92rem;font-weight:800;color:#059669;">₹{{ number_format($runningTotal) }}</td>
          <td colspan="3" style="padding:10px 12px;">
            @php $remaining = max(0,$booking->total_amount - $runningTotal); @endphp
            @if($remaining > 0)
              <span style="font-size:.8rem;color:#DC2626;font-weight:700;">Due: ₹{{ number_format($remaining) }}</span>
            @else
              <span style="font-size:.8rem;color:#059669;font-weight:700;">✅ Fully Paid</span>
            @endif
          </td>
        </tr>
      </tfoot>
    </table>
    @else
    <div style="text-align:center;padding:20px;color:#94A3B8;background:#F8FAFC;border-radius:10px;margin-bottom:14px;">
      No payments recorded yet. Add the first payment below.
    </div>
    @endif

    {{-- Add payment form --}}
    <div id="add-payment-form" style="display:none;background:#F0FDF4;border:1.5px solid #BBF7D0;border-radius:12px;padding:16px;">
      <div style="font-size:.72rem;font-weight:800;color:#065F46;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">💸 Add New Payment</div>
      <form action="{{ route('tour-booking.add-payment', $booking->id) }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-3">
            <label class="tb-label">Payment Date <span style="color:#EF4444;">*</span></label>
            <input type="date" name="payment_date" class="tb-input" required value="{{ date('Y-m-d') }}">
          </div>
          <div class="col-md-2">
            <label class="tb-label">Amount (₹) <span style="color:#EF4444;">*</span></label>
            <div class="tb-rupee-wrap"><span class="tb-rupee">₹</span>
              <input type="number" name="amount" class="tb-input tb-input-rs" required min="1" placeholder="0">
            </div>
          </div>
          <div class="col-md-3">
            <label class="tb-label">Payment Method</label>
            <select name="payment_method" class="tb-select">
              <option value="cash">Cash</option>
              <option value="upi">UPI / GPay / PhonePe</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="cheque">Cheque</option>
              <option value="online">Online Payment</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="tb-label">Note / Reference</label>
            <input type="text" name="notes" class="tb-input" placeholder="e.g. Advance, Final payment, UPI ref…">
          </div>
        </div>
        <div style="margin-top:12px;display:flex;gap:10px;">
          <button type="submit" style="background:linear-gradient(135deg,#059669,#10B981);color:#fff;border:none;border-radius:9px;padding:10px 22px;font-size:.84rem;font-weight:700;cursor:pointer;">
            Save Payment
          </button>
          <button type="button" onclick="document.getElementById('add-payment-form').style.display='none'"
                  style="background:#F1F5F9;color:#475569;border:1.5px solid #E2E8F0;border-radius:9px;padding:10px 16px;font-size:.84rem;font-weight:600;cursor:pointer;">
            Cancel
          </button>
        </div>
      </form>
    </div>

  </div>
</div>

<div style="height:30px;"></div>
</div>

<script>
/* ── Helpers ─────────────────────────────────── */
function fmt(v){return'₹'+Math.max(0,v).toLocaleString('en-IN');}
function v(id){return parseFloat(document.getElementById(id)?.value||0);}

/* ── Expense / profit calc ───────────────────── */
function tbCalcExpense(){
  var hotel=v('tb-exp-hotel'),cab=v('tb-exp-cab'),boat=v('tb-exp-boat'),guide=v('tb-exp-guide'),total=hotel+cab+boat+guide;
  document.getElementById('tb-hotel-badge').textContent=fmt(hotel);
  document.getElementById('tb-cab-badge').textContent=fmt(cab);
  document.getElementById('tb-boat-badge').textContent=fmt(boat);
  document.getElementById('tb-guide-badge').textContent=fmt(guide);
  document.getElementById('tb-exp-hotel-show').textContent=fmt(hotel);
  document.getElementById('tb-exp-cab-show').textContent=fmt(cab);
  document.getElementById('tb-exp-boat-show').textContent=fmt(boat);
  document.getElementById('tb-exp-guide-show').textContent=fmt(guide);
  document.getElementById('tb-total-exp').textContent=fmt(total);
  var booking=v('tb-booking-amt'),discount=v('tb-discount'),net=Math.max(0,booking-discount),profit=net-total,pct=net>0?Math.round((profit/net)*100):0;
  document.getElementById('tb-p-booking').textContent=fmt(net);
  document.getElementById('tb-p-expense').textContent=fmt(total);
  var pe=document.getElementById('tb-p-profit');pe.textContent=fmt(profit);pe.style.color=profit>=0?'#14532D':'#EF4444';
  var pp=document.getElementById('tb-p-pct');pp.textContent=Math.max(0,pct)+'%';pp.style.background=pct>=0?'#D1FAE5':'#FEE2E2';pp.style.color=pct>=0?'#065F46':'#DC2626';
}
function tbCalcBalance(){
  var booking=v('tb-booking-amt'),discount=v('tb-discount'),advance=v('tb-advance'),balance=Math.max(0,booking-discount-advance);
  document.getElementById('tb-balance').textContent=fmt(balance);
  tbUpdateBadge();tbCalcExpense();
}
function tbUpdateBadge(){
  var s=document.getElementById('tb-pay-status').value,b=document.getElementById('tb-pay-badge');
  b.textContent={'paid':'PAID','due':'DUE','partial':'PARTIAL'}[s]||'DUE';
  b.className='tb-badge '+({'paid':'tb-badge-paid','due':'tb-badge-due','partial':'tb-badge-partial'}[s]||'tb-badge-due');
}
function tbBuildSummary(){
  var name=document.getElementById('tb-pkg-name')?.value||'',
      start=document.getElementById('tb-tour-start')?.value||'',
      end=document.getElementById('tb-tour-end')?.value||'',
      adult=document.getElementById('tb-adults')?.value||'',
      incl=document.getElementById('tb-inclusions-hidden')?.value||'';
  var parts=[];
  if(name)parts.push(name);
  if(start)parts.push('From: '+new Date(start+'T00:00:00').toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}));
  if(end)parts.push('To: '+new Date(end+'T00:00:00').toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}));
  if(adult)parts.push('Adults: '+adult);
  if(incl)parts.push('Incl: '+incl);
  var el=document.getElementById('tb-summary');
  if(el&&!el.dataset.edited)el.value=parts.join(' | ');
}
document.getElementById('tb-summary')?.addEventListener('input',function(){this.dataset.edited='1';});

/* ── Hotel city / name data ──────────────────── */
var _hotelData={
  'Varanasi':['Kashi Paradise Stay (Saket Nagar)','Hotel Surya (Varuna Bridge)','Alka Hotel (Dashashwamedh)','Hotel Ganges View (Assi Ghat)','Ramada by Wyndham Varanasi','Radisson Hotel Varanasi','The Mango Hotels Varanasi','Zostel Varanasi','Hotel Rashmi (Lanka)','BrijRama Palace (Darbhanga Ghat)','Hotel Ideal Tops (Cantonment)','Rivatas by Ideal (Nadesar)','Gateway Hotel Ganges (Nadesar)','Lemon Tree Hotel Varanasi','FabHotel Prime Star Varanasi','OYO Rooms Varanasi'],
  'Prayagraj':['Hotel Kanha Shyam (Civil Lines)','Hotel Harsh Ananda (Civil Lines)','Hotel Prayag (Allahapur)','Ramada Encore Prayagraj','Hotel Milan Palace (Civil Lines)','Hotel Yatrik (Civil Lines)','Hotel Bella Vista (Civil Lines)','Hotel President (Civil Lines)','OYO Rooms Prayagraj','FabHotel Prayagraj','Hotel Samrat (Colonelganj)'],
  'Ayodhya':['Hotel Saket (Ram Ghat)','Hotel Ram Prastha (Nayaghat)','Treebo Trend Ayodhya','Hotel Divine Ayodhya','Ramada by Wyndham Ayodhya','Hotel Shri Ram International','The Ayodhya Hotel (Main Market)','OYO Rooms Ayodhya','Hotel Sita Ram (Near Ram Mandir)','Haveli Heritage Hotel Ayodhya'],
  'Lucknow':['Taj Mahal Hotel Lucknow','Hyatt Regency Lucknow','Piccadily Hotel Lucknow','Radisson Blu Hotel Lucknow','Vivanta Lucknow','Hotel Clarks Avadh','Lemon Tree Hotel Lucknow','Hotel Lebua Lucknow','OYO Rooms Lucknow'],
  'Agra':['The Oberoi Amarvilas Agra','ITC Mughal Agra','Taj Hotel & Convention Centre','Radisson Hotel Agra','Double Tree by Hilton Agra','Crystal Sarovar Premiere Agra','Hotel Amar Vilas (Near Taj)','OYO Rooms Agra'],
  'Delhi':['The Imperial New Delhi','Taj Palace New Delhi','ITC Maurya New Delhi','Hyatt Regency Delhi','Radisson Blu Plaza Delhi Airport','Lemon Tree Hotel Delhi','OYO Rooms Delhi'],
};

/* ── Multi-hotel rows ────────────────────────── */
var _hotelRowCount=0;
var _today='{{ date("Y-m-d") }}';

function tbAddHotelRow(prefill){
  var idx=_hotelRowCount++;
  var wrap=document.getElementById('hotel-rows');
  var div=document.createElement('div');
  div.id='hotel-row-'+idx;
  div.style.cssText='border:1px solid #E2E8F0;border-radius:10px;padding:12px 14px;margin-bottom:10px;background:#FAFBFF;position:relative;';
  div.innerHTML=`
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
      <span style="font-size:.72rem;font-weight:800;color:#059669;text-transform:uppercase;letter-spacing:.05em;">🏨 Hotel ${idx+1}</span>
      ${idx>0?`<button type="button" onclick="tbRemoveHotelRow(${idx})" style="background:#FEE2E2;color:#DC2626;border:none;border-radius:6px;padding:3px 10px;font-size:.72rem;font-weight:700;cursor:pointer;">✕ Remove</button>`:''}
    </div>
    <div style="display:grid;grid-template-columns:160px 1fr 140px;gap:8px;margin-bottom:8px;">
      <div>
        <label class="tb-label">Destination</label>
        <select name="hotels[${idx}][city]" class="tb-select" onchange="tbLoadHotelOptions(${idx})">
          <option value="">Select city…</option>
          <option value="Varanasi">🕌 Varanasi</option>
          <option value="Prayagraj">🏛 Prayagraj</option>
          <option value="Ayodhya">🛕 Ayodhya</option>
          <option value="Lucknow">🏙 Lucknow</option>
          <option value="Agra">🕌 Agra</option>
          <option value="Delhi">🌆 Delhi</option>
          <option value="Other">📍 Other</option>
        </select>
      </div>
      <div>
        <label class="tb-label">Hotel Name</label>
        <select id="hotel-sel-${idx}" class="tb-select" onchange="tbPickHotel(${idx})" style="display:none;"><option value="">Select hotel…</option></select>
        <input type="text" name="hotels[${idx}][name]" id="hotel-inp-${idx}" class="tb-input" placeholder="Type or select hotel name…">
      </div>
      <div>
        <label class="tb-label">Room Type</label>
        <select name="hotels[${idx}][room_type]" class="tb-select">
          <option value="">Select…</option>
          <option>Deluxe Room</option><option>Executive Room</option>
          <option>Premium Room</option><option>Suite</option>
          <option>Homestay Flat</option><option>Dormitory</option>
        </select>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr 70px 1fr;gap:8px;align-items:end;">
      <div><label class="tb-label">Check-in</label>
        <input type="date" name="hotels[${idx}][checkin]" id="hotel-ci-${idx}" class="tb-input" oninput="tbCalcRowNights(${idx})"></div>
      <div><label class="tb-label">Check-out</label>
        <input type="date" name="hotels[${idx}][checkout]" id="hotel-co-${idx}" class="tb-input" oninput="tbCalcRowNights(${idx})"></div>
      <div><label class="tb-label">Nights</label>
        <input type="text" id="hotel-nights-${idx}" class="tb-input" readonly style="background:#F1F5F9;color:#059669;font-weight:800;text-align:center;" placeholder="—"></div>
      <div><label class="tb-label" style="color:#D97706;font-weight:800;">B2B Cost (₹)</label>
        <div class="tb-rupee-wrap"><span class="tb-rupee">₹</span>
          <input type="number" name="hotels[${idx}][b2b]" id="hotel-b2b-${idx}" class="tb-input tb-input-rs"
                 min="0" value="0" oninput="tbSumHotels()"
                 style="border-color:#FDE68A !important;background:#FFFBEB !important;"></div></div>
    </div>`;
  wrap.appendChild(div);

  // Pre-fill values if provided
  if(prefill){
    // 1. Set city and load the hotel dropdown for that city
    var cityEl=div.querySelector('[name="hotels['+idx+'][city]"]');
    if(cityEl&&prefill.city){ cityEl.value=prefill.city; tbLoadHotelOptions(idx); }

    // 2. Restore hotel name — always write the text input (it's the form field that submits)
    //    Then try to also select it in the dropdown if visible
    var sel=document.getElementById('hotel-sel-'+idx);
    var inp=document.getElementById('hotel-inp-'+idx);
    if(prefill.name && inp){
      inp.value=prefill.name;                     // always set hidden text input (submitted)
      if(sel && sel.style.display!=='none'){       // dropdown is visible for this city
        // Try to find the name in the select options
        var matched=false;
        for(var i=0;i<sel.options.length;i++){
          if(sel.options[i].value===prefill.name){ sel.value=prefill.name; matched=true; break; }
        }
        if(!matched){                              // custom hotel not in list — show text input
          inp.style.display=''; sel.style.display='none';
        }
      }
    }

    // 3. Room type, dates, B2B cost
    var rtEl=div.querySelector('[name="hotels['+idx+'][room_type]"]');
    if(rtEl&&prefill.room_type)rtEl.value=prefill.room_type;
    var ciEl=document.getElementById('hotel-ci-'+idx);
    if(ciEl&&prefill.checkin)ciEl.value=prefill.checkin;
    var coEl=document.getElementById('hotel-co-'+idx);
    if(coEl&&prefill.checkout)coEl.value=prefill.checkout;
    var b2bEl=document.getElementById('hotel-b2b-'+idx);
    if(b2bEl&&(prefill.b2b||prefill.b2b===0))b2bEl.value=prefill.b2b;
    tbCalcRowNights(idx);
  }
  tbSumHotels();
}

function tbRemoveHotelRow(idx){
  var row=document.getElementById('hotel-row-'+idx);
  if(row)row.remove();
  tbSumHotels();
}

function tbCalcRowNights(idx){
  var ci=new Date(document.getElementById('hotel-ci-'+idx)?.value);
  var co=new Date(document.getElementById('hotel-co-'+idx)?.value);
  var ni=document.getElementById('hotel-nights-'+idx);
  if(ni&&!isNaN(ci)&&!isNaN(co)&&co>ci)ni.value=Math.round((co-ci)/86400000);
}

function tbLoadHotelOptions(idx){
  var cityEl=document.querySelector('[name="hotels['+idx+'][city]"]');
  var sel=document.getElementById('hotel-sel-'+idx);
  var inp=document.getElementById('hotel-inp-'+idx);
  var city=cityEl?.value||'';
  if(!city||city==='Other'||!_hotelData[city]){sel.style.display='none';inp.style.display='';return;}
  sel.innerHTML='<option value="">— Select hotel —</option>';
  _hotelData[city].forEach(function(h){var o=document.createElement('option');o.value=h;o.textContent=h;sel.appendChild(o);});
  var c=document.createElement('option');c.value='__custom__';c.textContent='✏️ Type custom name…';sel.appendChild(c);
  sel.style.display='';inp.style.display='none';
}

function tbPickHotel(idx){
  var sel=document.getElementById('hotel-sel-'+idx);
  var inp=document.getElementById('hotel-inp-'+idx);
  if(sel.value==='__custom__'){inp.style.display='';inp.value='';inp.focus();}
  else if(sel.value){inp.value=sel.value;inp.style.display='none';}
}

function tbSumHotels(){
  var total=0;
  document.querySelectorAll('[id^="hotel-b2b-"]').forEach(function(el){total+=parseFloat(el.value)||0;});
  var hidden=document.getElementById('tb-exp-hotel');
  if(hidden)hidden.value=total;
  tbCalcExpense();
}

/* ── Inclusions ──────────────────────────────── */
var _inclSet=new Set(
  (document.getElementById('tb-inclusions-hidden')?.value||'')
    .split(',').map(function(s){return s.trim();}).filter(Boolean)
);

function toggleIncl(label,pill){
  if(_inclSet.has(label)){_inclSet.delete(label);pill.classList.remove('active');}
  else{_inclSet.add(label);pill.classList.add('active');}
  _syncIncl();
}

function addCustomIncl(){
  var inp=document.getElementById('tb-incl-custom');
  var val=inp.value.trim();
  if(!val)return;
  if(!_inclSet.has(val)){
    _inclSet.add(val);
    var lbl=document.createElement('label');
    lbl.className='incl-pill active';
    lbl.textContent=val;
    lbl.setAttribute('onclick',"toggleIncl('"+val.replace(/'/g,"\\'")+"',this)");
    document.getElementById('incl-pills').appendChild(lbl);
  }
  inp.value='';
  _syncIncl();
}

function _syncIncl(){
  var csv=Array.from(_inclSet).join(', ');
  document.getElementById('tb-inclusions-hidden').value=csv;
  var prev=document.getElementById('incl-preview');
  if(csv){prev.style.display='';prev.textContent='✓ '+csv;}
  else{prev.style.display='none';}
  tbBuildSummary();
}

document.getElementById('tb-incl-custom')?.addEventListener('keydown',function(e){
  if(e.key==='Enter'){e.preventDefault();addCustomIncl();}
});

/* ── On load: pre-populate hotel rows from saved data ── */
@php
  if (!empty($serviceData['hotels'])) {
      $existingHotels = $serviceData['hotels'];
  } elseif (!empty($serviceData['hotel']['hotel_name'])) {
      $existingHotels = [[
          'city'      => '',
          'name'      => $serviceData['hotel']['hotel_name']     ?? '',
          'room_type' => '',
          'checkin'   => $serviceData['hotel']['hotel_checkin']  ?? '',
          'checkout'  => $serviceData['hotel']['hotel_checkout'] ?? '',
          'b2b'       => $serviceData['hotel']['b2b']            ?? 0,
      ]];
  } else {
      $existingHotels = [];
  }
@endphp
document.addEventListener('DOMContentLoaded',function(){
  var existingHotels=@json($existingHotels);
  if(existingHotels&&existingHotels.length>0){
    existingHotels.forEach(function(h){tbAddHotelRow(h);});
  } else {
    tbAddHotelRow();
  }
  _syncIncl();
});
</script>

{{-- CKEditor 5 for Itinerary --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  ClassicEditor
    .create(document.getElementById('tb-itinerary-editor'), {
      toolbar: [
        'heading','|','bold','italic','underline','|',
        'bulletedList','numberedList','|',
        'blockQuote','horizontalLine','|',
        'undo','redo'
      ],
      placeholder: 'Day 1: Arrival at Varanasi...\nDay 2: Kashi Vishwanath & Ganga Aarti...',
    })
    .then(function(editor) {
      // Inject color-fix AFTER CKEditor's own dynamic CSS loads
      var s = document.createElement('style');
      s.textContent = '.ck.ck-editor__editable,.ck.ck-editor__editable_inline,.ck.ck-content{color:#0F172A!important;background:#fff!important;}.ck.ck-editor__editable p,.ck.ck-editor__editable li,.ck.ck-editor__editable h1,.ck.ck-editor__editable h2,.ck.ck-editor__editable h3{color:#0F172A!important;}';
      document.head.appendChild(s);

      // Pre-fill with existing content
      var existing = document.getElementById('tb-itinerary').value;
      if (existing) editor.setData(existing);

      // Sync editor content back to hidden textarea before form submit
      var form = document.getElementById('tb-form') || document.querySelector('form');
      if (form) {
        form.addEventListener('submit', function() {
          document.getElementById('tb-itinerary').value = editor.getData();
        });
      }
    })
    .catch(function(err) { console.error('CKEditor error:', err); });
});
</script>
@endsection
