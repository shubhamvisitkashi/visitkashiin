@extends('admin.layouts.app')
@section('content')

@php
    $lead      = $booking->lead;
    $quotation = $booking->quotation;
    $items     = $quotation ? $quotation->items : collect();
    $payments  = $booking->payments ?? collect();
    $paidAmt   = $payments->sum('amount') ?: (float)$booking->paid_amount;
    $netTotal  = (float)$booking->total_amount;
    $pendAmt   = max(0, $netTotal - $paidAmt);
    $paidPct   = $netTotal > 0 ? min(100, ($paidAmt / $netTotal) * 100) : 0;
    $status    = $booking->booking_status;

    // ── Parse short_plan ─────────────────────────────────────────
    $shortPlan = optional($lead)->short_plan ?? '';
    $segs      = array_map('trim', explode('|', $shortPlan));

    $editProperty    = '';
    $editCheckinTime = '14:00';
    $editCheckoutTime= '11:00';
    $editNights      = 0;
    $editRoomsCount  = 1;
    $editRoomType    = '';
    $editAdults      = 1;
    $editChildren    = 0;
    $editMeal        = '';
    $editExtraBed    = '';
    $editRequests    = '';

    foreach ($segs as $seg) {
        if (preg_match('/^Property:\s*(.+)$/i', $seg, $m)) {
            $editProperty = trim($m[1]);
        } elseif (preg_match('/^Check-in:\s*(.+?)\s*→\s*Check-out:\s*(.+)$/i', $seg, $m)) {
            if (preg_match('/(\d+:\d+\s*[APap][Mm])/i', $m[1], $tm)) {
                try { $editCheckinTime = \Carbon\Carbon::createFromFormat('g:i A', strtoupper(trim($tm[1])))->format('H:i'); } catch(\Exception $e) {}
            }
            if (preg_match('/(\d+:\d+\s*[APap][Mm])/i', $m[2], $tm)) {
                try { $editCheckoutTime = \Carbon\Carbon::createFromFormat('g:i A', strtoupper(trim($tm[1])))->format('H:i'); } catch(\Exception $e) {}
            }
        } elseif (preg_match('/^Duration:\s*(\d+)/i', $seg, $m)) {
            $editNights = (int)$m[1];
        } elseif (preg_match('/^Rooms:\s*(\d+)(?:\s*\(([^)]+)\))?/i', $seg, $m)) {
            $editRoomsCount = (int)$m[1];
            $editRoomType   = $m[2] ?? '';
        } elseif (preg_match('/^Guests:\s*(\d+)\s*Adult/i', $seg, $m)) {
            $editAdults   = (int)$m[1];
            if (preg_match('/(\d+)\s*Child/i', $seg, $mc)) $editChildren = (int)$mc[1];
        } elseif (preg_match('/^Meal Plan:\s*(.+)$/i', $seg, $m)) {
            $editMeal = trim($m[1]);
        } elseif (preg_match('/^Extra Bed:\s*[₹]?([\d,]+)/i', $seg, $m)) {
            $editExtraBed = str_replace(',', '', $m[1]);
        } elseif (preg_match('/^Requests:\s*(.+)$/i', $seg, $m)) {
            $editRequests = trim($m[1]);
        }
    }

    $editCheckin  = optional($lead)->booking_start_date ? $lead->booking_start_date->format('Y-m-d') : '';
    $editCheckout = optional($lead)->booking_end_date   ? $lead->booking_end_date->format('Y-m-d')   : '';

    // Derive nights if not parsed
    if (!$editNights && $editCheckin && $editCheckout) {
        $editNights = (int)\Carbon\Carbon::parse($editCheckin)->diffInDays(\Carbon\Carbon::parse($editCheckout));
    }

    // Stay service templates — match by the actual service type of the first booking item
    $selectedTplId       = optional($items->first())->service_template_id;
    $firstItemStypeId    = optional($items->first()?->serviceTemplate)->service_type_id
                        ?? optional($items->first())->service_type_id;
    $stayServiceType     = $firstItemStypeId
        ? $serviceTypes->firstWhere('id', $firstItemStypeId)
        : $serviceTypes->first(fn($t) => str_contains(strtolower($t->name), 'hotel') || str_contains(strtolower($t->name), 'stay'));
    $stayTemplates       = $stayServiceType?->serviceTemplates ?? collect();
    $discountStored   = max(0, ($quotation->subtotal ?? $netTotal) - $netTotal);
@endphp

<style>
:root{
  --h-bg:#EEF2FF;--h-card:#fff;--h-border:#E2E8F0;
  --h-shadow:0 1px 3px rgba(15,23,42,.06),0 4px 16px rgba(15,23,42,.04);
  --h-text:#0F172A;--h-sub:#475569;--h-muted:#94A3B8;
  --h-indigo:#4F46E5;--h-emerald:#10B981;--h-amber:#F59E0B;
  --h-rose:#EF4444;--h-sky:#0EA5E9;--h-violet:#7C3AED;
  --h-r:14px;--h-t:0.2s ease;
}
.cb-page{background:var(--h-bg);min-height:100vh;padding:24px;}
.cb-header{background:linear-gradient(135deg,#059669 0%,#047857 100%);
  border-radius:var(--h-r);padding:20px 26px;
  display:flex;align-items:center;justify-content:space-between;
  margin-top:50px;margin-bottom:24px;overflow:hidden;position:relative;}
.cb-header::before{content:'';position:absolute;top:-30px;right:-30px;width:150px;height:150px;background:rgba(255,255,255,.07);border-radius:50%;}
.cb-header h1{color:#fff;font-size:1.25rem;font-weight:700;margin:0;position:relative;z-index:1;}
.cb-header p{color:rgba(255,255,255,.75);font-size:.8rem;margin:.2rem 0 0;position:relative;z-index:1;}
.cb-back{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:8px 14px;font-size:.8rem;font-weight:600;text-decoration:none;transition:background var(--h-t);position:relative;z-index:1;}
.cb-back:hover{background:rgba(255,255,255,.28);color:#fff;}
.hdr-actions{display:flex;gap:8px;position:relative;z-index:1;}

.nb-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:1140px){.nb-layout{grid-template-columns:1fr;}}

.nb-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05),0 2px 8px rgba(0,0,0,.04);margin-bottom:16px;overflow:hidden;}
.nb-card-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:12px;background:#FAFBFF;}
.nb-card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.nb-card-title{font-size:.87rem;font-weight:700;color:#0F172A;}
.nb-card-body{padding:20px;}

.nb-label{font-size:.72rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px;display:block;}
.nb-req{color:#EF4444;margin-left:2px;}
.nb-input{border:1.5px solid #E2E8F0 !important;border-radius:10px !important;padding:9px 13px !important;font-size:.875rem !important;color:#0F172A !important;background:#FAFBFF !important;transition:all .2s ease !important;width:100%;}
.nb-input:focus{border-color:#0EA5E9 !important;box-shadow:0 0 0 3px rgba(14,165,233,.12) !important;background:#fff !important;outline:none !important;}

.nb-phone-wrap{position:relative;}
.nb-phone-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;font-weight:600;color:#64748B;z-index:2;pointer-events:none;}
.nb-input-phone{padding-left:40px !important;}

/* Hotel cards */
.hotel-card{border:2px solid #E8ECF4;border-radius:14px;padding:14px 12px;cursor:pointer;text-align:center;transition:all .22s ease;background:#fff;position:relative;user-select:none;}
.hotel-card:hover{border-color:#10B981;background:#F0FDF4;transform:translateY(-3px);box-shadow:0 6px 20px rgba(16,185,129,.15);}
.hotel-card.selected{border-color:#10B981;background:#F0FDF4;box-shadow:0 0 0 3px rgba(16,185,129,.22);}
.hotel-card-check{position:absolute;top:8px;right:8px;width:20px;height:20px;background:#10B981;border-radius:50%;font-size:.68rem;color:#fff;display:none;align-items:center;justify-content:center;font-weight:700;}
.hotel-card.selected .hotel-card-check{display:flex;}
.hotel-card-icon{font-size:1.8rem;margin-bottom:6px;line-height:1;}
.hotel-card-name{font-size:.76rem;font-weight:700;color:#0F172A;line-height:1.3;margin-bottom:4px;}
.hotel-card-price{font-size:.92rem;font-weight:800;color:#10B981;}
.hotel-card-per{font-size:.65rem;font-weight:500;color:#94A3B8;}
.hotel-card-custom{border-style:dashed;border-color:#C4B5FD;}
.hotel-card-custom:hover{border-color:#7C3AED;background:#EDE9FE;}
.hotel-card-custom.selected{border-color:#7C3AED;background:#EDE9FE;box-shadow:0 0 0 3px rgba(124,58,237,.18);}
.hotel-card-custom.selected .hotel-card-check{background:#7C3AED;}
.hotel-card-del{position:absolute;top:7px;left:7px;width:18px;height:18px;border-radius:50%;background:#FEE2E2;color:#DC2626;border:none;font-size:.7rem;font-weight:800;line-height:1;display:none;align-items:center;justify-content:center;cursor:pointer;z-index:5;padding:0;transition:background .12s;}
.hotel-card.selected .hotel-card-del{display:flex;}
.hotel-card-del:hover{background:#DC2626;color:#fff;}

/* Stay counters */
.stay-counter-wrap{display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:6px 8px;margin-top:2px;}
.stay-counter-btn{width:30px;height:30px;border-radius:8px;border:1.5px solid #CBD5E1;background:#fff;color:#475569;font-size:1.1rem;font-weight:700;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;}
.stay-counter-btn:hover{border-color:#0EA5E9;color:#0EA5E9;background:#F0F9FF;}
.stay-counter-val{font-size:1.25rem;font-weight:800;min-width:28px;text-align:center;color:#0F172A;}

/* Sidebar */
.nb-sidebar{position:sticky;top:20px;}
.nb-sidebar-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05),0 2px 8px rgba(0,0,0,.04);padding:20px;margin-bottom:16px;}
.nb-sidebar-head{display:flex;align-items:center;gap:10px;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #F1F5F9;}
.nb-amount-row{margin-bottom:14px;}
.nb-rupee-wrap{position:relative;}
.nb-rupee{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.85rem;font-weight:600;color:#475569;z-index:2;pointer-events:none;}
.nb-rupee-input{padding-left:28px !important;}
.nb-balance-badge{font-size:.62rem;font-weight:800;letter-spacing:.4px;padding:3px 8px;border-radius:20px;text-transform:uppercase;}
.nb-balance-badge.paid{background:#D1FAE5;color:#065F46;}
.nb-balance-badge.due{background:#FEE2E2;color:#DC2626;}
.nb-balance-badge.partial{background:#DBEAFE;color:#1D4ED8;}

.nb-pay-sum{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;margin:14px 0;overflow:hidden;}
.nb-pay-sum-head{background:linear-gradient(135deg,#0F172A,#1E293B);padding:10px 14px;display:flex;align-items:center;gap:8px;color:#fff;font-size:.78rem;font-weight:700;letter-spacing:.02em;}
.nb-pay-sum-body{padding:12px 14px;}
.nb-pay-sum-row{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;padding:5px 0;}
.nb-pay-sum-row+.nb-pay-sum-row{border-top:1px dashed #E8ECF4;}
.nb-pay-sum-lbl{color:#64748B;font-weight:500;}
.nb-pay-sum-val{font-weight:700;color:#0F172A;}
.nb-pay-sum-formula{font-size:.68rem;color:#94A3B8;text-align:right;margin:-3px 0 5px;padding-bottom:7px;border-bottom:1px dashed #E8ECF4;}
.nb-pay-sum-balance{font-weight:800;font-size:.95rem;color:#EF4444;}
.nb-pay-sum-advance{color:#10B981;}
.nb-pay-sum-bar-wrap{margin-top:10px;padding-top:10px;border-top:1px solid #F1F5F9;}
.nb-pay-sum-bar-lbl{display:flex;justify-content:space-between;font-size:.68rem;color:#94A3B8;margin-bottom:4px;}
.nb-pay-sum-bar-track{height:6px;background:#E2E8F0;border-radius:99px;overflow:hidden;}
.nb-pay-sum-bar-fill{height:100%;background:linear-gradient(90deg,#10B981,#059669);border-radius:99px;transition:width .4s ease;}

.pmt-item{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #F1F5F9;font-size:.76rem;}
.pmt-item:last-child{border-bottom:none;}
.pmt-date{color:#64748B;}
.pmt-method{color:#94A3B8;font-size:.7rem;}
.pmt-amt{font-weight:700;color:#059669;}

.btn-save{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#059669,#047857);color:#fff;border:none;border-radius:10px;padding:12px 28px;font-size:.92rem;font-weight:700;cursor:pointer;transition:opacity .2s,transform .2s;width:100%;justify-content:center;margin-top:6px;}
.btn-save:hover{opacity:.88;transform:translateY(-1px);color:#fff;}
.btn-save svg{width:17px;height:17px;stroke:#fff;}

@media(max-width:768px){
  .cb-page{padding:10px;}
  .cb-header{padding:14px 16px;margin-bottom:14px;flex-wrap:wrap;gap:10px;margin-top:60px;}
  .cb-header h1{font-size:1.05rem;}
  .cb-header p{font-size:.72rem;}
  .cb-back{padding:6px 11px;font-size:.74rem;}
  .hdr-actions{flex-wrap:wrap;gap:6px;}

  /* nb-card-body improvements */
  .nb-card-body{padding:14px 12px;}
  .nb-card-head{padding:12px 14px;gap:8px;}
  .nb-card-icon{width:28px;height:28px;border-radius:8px;}
  .nb-card-title{font-size:.82rem;}
  .nb-card{border-radius:12px;margin-bottom:12px;}

  /* form fields inside nb-card-body */
  .nb-label{font-size:.68rem;margin-bottom:4px;}
  .nb-input{padding:8px 11px !important;font-size:.82rem !important;border-radius:8px !important;}
  /* restore phone-prefix padding overridden by nb-input reset above */
  .nb-input-phone{padding-left:40px !important;}
  .row.g-3{--bs-gutter-x:10px;--bs-gutter-y:10px;}
  .row.g-3>[class*=col-md]{padding-left:5px;padding-right:5px;}

  /* sidebar */
  .nb-sidebar-card{padding:14px 12px;border-radius:12px;}
  .nb-sidebar-head{gap:8px;margin-bottom:12px;padding-bottom:10px;}
  .nb-pay-sum-body{padding:10px 12px;}
  .nb-pay-sum-row{font-size:.75rem;}
  .nb-balance-badge{font-size:.6rem;padding:2px 6px;}

  /* hotel cards: compact horizontal list on mobile */
  #hotel-cards-grid{grid-template-columns:1fr !important;gap:6px !important;}
  .hotel-card{
    display:grid;grid-template-columns:36px 1fr;grid-template-rows:auto auto;
    align-items:center;column-gap:10px;text-align:left;
    padding:10px 12px;border-radius:10px;transform:none !important;
  }
  .hotel-card:hover{transform:none !important;}
  .hotel-card-icon{grid-column:1;grid-row:1/3;font-size:1.4rem;margin-bottom:0;line-height:1;text-align:center;}
  .hotel-card-name{grid-column:2;grid-row:1;font-size:.82rem;margin-bottom:1px;}
  .hotel-card-price{grid-column:2;grid-row:2;font-size:.85rem;}
  .hotel-card-check{top:50%;transform:translateY(-50%);}
  .hotel-card-del{top:50%;transform:translateY(-50%);}

  .stay-counter-wrap{padding:5px 7px;}
  .stay-counter-btn{width:28px;height:28px;font-size:1rem;}
  .stay-counter-val{font-size:1.1rem;min-width:24px;}

  .pmt-item{font-size:.72rem;padding:5px 0;}
  .btn-save{padding:10px 20px;font-size:.85rem;}
}

@media(max-width:480px){
  .cb-page{padding:8px;}
  .nb-card-body{padding:10px;}
  .nb-card-head{padding:10px 12px;}
  .nb-card{border-radius:10px;margin-bottom:10px;}
  .nb-input{padding:7px 10px !important;font-size:.8rem !important;}
  .nb-input-phone{padding-left:40px !important;}
  .nb-label{font-size:.65rem;}
  .nb-sidebar-card{padding:10px;}
  .hotel-card{padding:9px 10px;}
  .hotel-card-icon{font-size:1.3rem;}
  .stay-counter-btn{width:26px;height:26px;}
  .btn-save{padding:9px 16px;font-size:.82rem;border-radius:8px;}
}

@media(max-width:360px){
  .nb-card-body{padding:8px;}
  .nb-card-head{padding:8px 10px;}
  .nb-input{padding:6px 9px !important;font-size:.78rem !important;}
  .nb-input-phone{padding-left:40px !important;}
  .nb-label{font-size:.63rem;}
  .hotel-card{padding:8px 10px;}
  .stay-counter-btn{width:24px;height:24px;font-size:.9rem;}
  .btn-save{font-size:.8rem;}
}
</style>

<div class="cb-page">

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
      <strong>Please fix:</strong>
      <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3">
      {{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Header --}}
  <div class="cb-header">
    <div>
      <h1>✏️ Modify Booking — {{ $booking->booking_number }}</h1>
      <p>{{ optional($lead)->guest_name }} &nbsp;·&nbsp; Stay Booking &nbsp;·&nbsp; {{ ucwords(str_replace('_',' ',$status)) }}</p>
    </div>
    <div class="hdr-actions">
      <a href="{{ route('bookings.show', $booking->id) }}" class="cb-back">
        <i data-feather="eye" style="width:13px;height:13px;stroke:#fff;"></i> View
      </a>
      <a href="{{ route('bookings.index') }}" class="cb-back">
        <i data-feather="arrow-left" style="width:13px;height:13px;stroke:#fff;"></i> Back
      </a>
    </div>
  </div>

  <form action="{{ route('bookings.update', $booking->id) }}" method="POST" id="editForm">
    @csrf @method('PUT')
    {{-- Rebuilt short_plan injected by JS before submit --}}
    <input type="hidden" name="short_plan" id="edit-short-plan" value="{{ old('short_plan', $shortPlan) }}">
    <input type="hidden" name="booking_date" value="{{ $booking->booking_date->format('Y-m-d') }}">

  <div class="nb-layout">

    {{-- ═══ LEFT ═══ --}}
    <div>

      {{-- Booking Info --}}
      <div class="nb-card">
        <div class="nb-card-head">
          <div class="nb-card-icon" style="background:#EFF6FF;color:#3B82F6;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <div class="nb-card-title">Booking Information</div>
        </div>
        <div class="nb-card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="nb-label">Booking Date</label>
              <input type="text" class="form-control nb-input" readonly
                     value="{{ $booking->booking_date->format('d M, Y') }}"
                     style="background:#F8FAFC !important;color:#94A3B8 !important;">
            </div>
            <div class="col-md-4">
              <label class="nb-label">Booking Status</label>
              <select name="booking_status" class="form-select nb-input">
                @foreach(['confirmed'=>'✅ Confirmed','in_progress'=>'🔄 In Progress','completed'=>'✔️ Completed','cancelled'=>'❌ Cancelled'] as $val=>$lbl)
                  <option value="{{ $val }}" {{ $status == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="nb-label">Booking Number</label>
              <input type="text" class="form-control nb-input" readonly
                     value="{{ $booking->booking_number }}"
                     style="background:#F8FAFC !important;color:#64748B !important;font-weight:600;">
            </div>
          </div>
        </div>
      </div>

      {{-- Guest Details --}}
      <div class="nb-card">
        <div class="nb-card-head">
          <div class="nb-card-icon" style="background:#F0FDF4;color:#10B981;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <div class="nb-card-title">Guest Details</div>
        </div>
        <div class="nb-card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="nb-label">Guest Name <span class="nb-req">*</span></label>
              <input type="text" name="guest_name" class="form-control nb-input" required
                     value="{{ old('guest_name', optional($lead)->guest_name ?? '') }}" placeholder="Full name">
            </div>
            <div class="col-md-4">
              <label class="nb-label">Mobile <span class="nb-req">*</span></label>
              <div class="nb-phone-wrap">
                <span class="nb-phone-prefix">+91</span>
                <input type="tel" name="contact" class="form-control nb-input nb-input-phone" required
                       placeholder="XXXXX XXXXX" maxlength="15"
                       value="{{ old('contact', optional($lead)->contact ?? '') }}">
              </div>
            </div>
            <div class="col-md-4">
              <label class="nb-label">Alt. Mobile</label>
              <div class="nb-phone-wrap">
                <span class="nb-phone-prefix">+91</span>
                <input type="tel" name="alt_phone" class="form-control nb-input nb-input-phone"
                       placeholder="Optional" maxlength="15"
                       value="{{ old('alt_phone', optional($lead)->alt_phone ?? '') }}">
              </div>
            </div>
            <div class="col-md-4">
              <label class="nb-label">Email</label>
              <input type="email" name="email" class="form-control nb-input"
                     placeholder="guest@email.com"
                     value="{{ old('email', optional($lead)->email ?? '') }}">
            </div>
            <div class="col-md-4">
              <label class="nb-label">Country</label>
              <select name="country" class="form-select nb-input">
                @php $editCountry = old('country', optional($lead)->country ?? 'India'); @endphp
                @foreach(['India'=>'🇮🇳 India','USA'=>'🇺🇸 USA','UK'=>'🇬🇧 UK','UAE'=>'🇦🇪 UAE','Australia'=>'🇦🇺 Australia','Canada'=>'🇨🇦 Canada','Singapore'=>'🇸🇬 Singapore','Other'=>'🌍 Other'] as $val=>$lbl)
                  <option value="{{ $val }}" {{ $editCountry === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="nb-label">Trip Summary / Notes</label>
              <input type="text" name="notes" class="form-control nb-input"
                     placeholder="Special notes for staff…"
                     value="{{ old('notes', optional($lead)->notes ?? '') }}">
            </div>
          </div>
        </div>
      </div>

      {{-- Property / Hotel --}}
      <div class="nb-card">
        <div class="nb-card-head">
          <div class="nb-card-icon" style="background:#D1FAE5;color:#059669;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          </div>
          <div class="nb-card-title">Property / Hotel &nbsp;<span style="font-size:.72rem;font-weight:400;color:#EF4444;">* required</span></div>
        </div>
        <div class="nb-card-body">

          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:10px;margin-bottom:12px;" id="hotel-cards-grid">
            @foreach($stayTemplates as $t)
            <div class="hotel-card {{ $selectedTplId == $t->id ? 'selected' : '' }}"
                 data-id="{{ $t->id }}"
                 data-name="{{ $t->name }}"
                 data-price="{{ (float)$t->default_selling_price }}"
                 onclick="selectHotel(this)">
              <button type="button" class="hotel-card-del" onclick="event.stopPropagation();clearHotel()" title="Remove">✕</button>
              <div class="hotel-card-check">✓</div>
              <div class="hotel-card-icon">🏨</div>
              <div class="hotel-card-name">{{ $t->name }}</div>
              <div class="hotel-card-price">₹{{ number_format((float)$t->default_selling_price, 0) }}<span class="hotel-card-per">/night</span></div>
            </div>
            @endforeach
            <div class="hotel-card hotel-card-custom {{ !$selectedTplId ? 'selected' : '' }}" id="hotel-card-custom" onclick="selectHotelCustom()">
              <button type="button" class="hotel-card-del" onclick="event.stopPropagation();clearHotel()" title="Remove">✕</button>
              <div class="hotel-card-check">✓</div>
              <div class="hotel-card-icon">✏️</div>
              <div class="hotel-card-name" style="color:#4F46E5;">Not listed?</div>
              <div class="hotel-card-price" style="color:#6B7280;font-size:.7rem;">Add custom</div>
            </div>
          </div>

          {{-- Custom property form --}}
          <div id="stay-custom-hotel-wrap" style="display:{{ !$selectedTplId ? 'block' : 'none' }};margin-top:14px;background:#F5F3FF;border:1.5px solid #C4B5FD;border-radius:14px;padding:16px 18px;">
            <div style="font-size:.72rem;font-weight:800;color:#7C3AED;text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px;">✏️ Custom Property</div>
            <div class="row g-3 align-items-end">
              <div class="col-md-5">
                <label class="nb-label" style="color:#6D28D9;">Property Name <span class="nb-req">*</span></label>
                <input type="text" id="stay_hotel" class="form-control nb-input"
                       placeholder="e.g. Ganges View Hotel, Varanasi"
                       style="border-color:#C4B5FD !important;"
                       value="{{ !$selectedTplId ? $editProperty : '' }}"
                       oninput="buildEditShortPlan()">
              </div>
              <div class="col-md-3">
                <label class="nb-label" style="color:#6D28D9;">Room Type</label>
                <select id="stay_custom_room_type" class="form-select nb-input" style="border-color:#C4B5FD !important;" onchange="onCustomRoomTypeChange()">
                  <option value="">Select type</option>
                  @foreach(['Deluxe Room'=>2500,'Executive Room'=>3500,'Premium Room'=>5000,'Homestay Flats'=>1500] as $rt=>$rp)
                    <option value="{{ $rt }}" data-price="{{ $rp }}" {{ $editRoomType === $rt ? 'selected' : '' }}>{{ $rt }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="nb-label" style="color:#6D28D9;">Price / Night (₹) <span class="nb-req">*</span></label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee" style="color:#7C3AED;">₹</span>
                  <input type="number" id="stay_custom_price" class="form-control nb-input nb-rupee-input"
                         placeholder="0" min="0" step="1" style="border-color:#C4B5FD !important;">
                </div>
              </div>
              <div class="col-md-1 d-flex align-items-end">
                <button type="button" onclick="saveCustomHotel()"
                        style="width:100%;height:40px;background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#fff;border:none;border-radius:10px;font-size:.82rem;font-weight:700;cursor:pointer;">
                  Save
                </button>
              </div>
            </div>
          </div>

          <input type="hidden" name="services[0][service_template_id]" id="stay-svc-tmpl-id" value="{{ $selectedTplId ?? '' }}">
        </div>
      </div>

      {{-- Stay Details --}}
      <div class="nb-card">
        <div class="nb-card-head">
          <div class="nb-card-icon" style="background:#FEF3C7;color:#D97706;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <div class="nb-card-title">Stay Details</div>
        </div>
        <div class="nb-card-body">
          <div class="row g-3">
            <div class="col-6 col-md-3">
              <label class="nb-label">Check-in <span class="nb-req">*</span></label>
              <input type="date" id="stay_checkin" name="booking_start_date"
                     class="form-control nb-input"
                     value="{{ old('booking_start_date', $editCheckin) }}"
                     onchange="buildEditShortPlan();calcNights()">
              <input type="time" id="stay_checkin_time"
                     class="form-control nb-input mt-1"
                     value="{{ $editCheckinTime }}"
                     onchange="buildEditShortPlan()"
                     style="font-size:.8rem !important;color:#0891b2 !important;font-weight:600 !important;">
            </div>
            <div class="col-6 col-md-3">
              <label class="nb-label">Check-out <span class="nb-req">*</span></label>
              <input type="date" id="stay_checkout" name="booking_end_date"
                     class="form-control nb-input"
                     value="{{ old('booking_end_date', $editCheckout) }}"
                     onchange="buildEditShortPlan();calcNights()">
              <input type="time" id="stay_checkout_time"
                     class="form-control nb-input mt-1"
                     value="{{ $editCheckoutTime }}"
                     onchange="buildEditShortPlan()"
                     style="font-size:.8rem !important;color:#dc2626 !important;font-weight:600 !important;">
            </div>
            <div class="col-4 col-md-2">
              <label class="nb-label">Nights</label>
              <input type="text" id="stay_nights" class="form-control nb-input" readonly
                     value="{{ $editNights ?: '' }}"
                     placeholder="Auto" style="background:#F1F5F9 !important;text-align:center;font-weight:700;">
            </div>
            <div class="col-4 col-md-2">
              <label class="nb-label">Adults <span style="font-weight:500;color:#94A3B8;text-transform:none;font-size:.67rem;">(18+)</span></label>
              <div class="stay-counter-wrap">
                <button type="button" class="stay-counter-btn" onclick="changeStayAdults(-1)">−</button>
                <span class="stay-counter-val" id="stay-adults-display">{{ $editAdults }}</span>
                <button type="button" class="stay-counter-btn" onclick="changeStayAdults(1)">+</button>
              </div>
              <input type="hidden" id="stay_adults_val" value="{{ $editAdults }}">
            </div>
            <div class="col-4 col-md-2">
              <label class="nb-label">Children <span style="font-weight:500;color:#94A3B8;text-transform:none;font-size:.67rem;">(5+)</span></label>
              <div class="stay-counter-wrap">
                <button type="button" class="stay-counter-btn" onclick="changeStayKids(-1)">−</button>
                <span class="stay-counter-val" id="stay-kids-display">{{ $editChildren }}</span>
                <button type="button" class="stay-counter-btn" onclick="changeStayKids(1)">+</button>
              </div>
              <input type="hidden" id="stay_kids_val" value="{{ $editChildren }}">
            </div>
            <div class="col-md-3">
              <label class="nb-label">Room Type</label>
              <select id="stay_room_type" class="form-select nb-input" onchange="buildEditShortPlan()">
                <option value="">Select room type</option>
                @foreach(['Deluxe Room','Executive Room','Premium Room','Homestay Flats'] as $rt)
                  <option {{ $editRoomType === $rt ? 'selected' : '' }}>{{ $rt }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6 col-md-2">
              <label class="nb-label">No. of Rooms <span class="nb-req">*</span></label>
              <input type="number" id="stay_rooms" class="form-control nb-input"
                     min="1" value="{{ $editRoomsCount }}" required
                     onchange="recalcStayTotal();buildEditShortPlan()">
            </div>
            <div class="col-md-3">
              <label class="nb-label">Meal Plan</label>
              <select id="stay_meal" class="form-select nb-input" onchange="buildEditShortPlan()">
                <option value="">No meals included</option>
                @foreach(['CP'=>'CP – Breakfast only','MAP'=>'MAP – Breakfast + Dinner','AP'=>'AP – All meals','EP'=>'EP – Room only'] as $mv=>$ml)
                  <option value="{{ $mv }}" {{ $editMeal === $mv ? 'selected' : '' }}>{{ $ml }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6 col-md-2">
              <label class="nb-label">Extra Bed (₹)</label>
              <div class="nb-rupee-wrap" style="max-width:130px;">
                <span class="nb-rupee" style="color:#8B5CF6;">₹</span>
                <input type="number" id="stay_extra_bed_amt" class="form-control nb-input nb-rupee-input"
                       placeholder="0" min="0" step="1"
                       value="{{ $editExtraBed }}"
                       oninput="recalcStayTotal();buildEditShortPlan()">
              </div>
            </div>
            <div class="col-md-4">
              <label class="nb-label">Special Requests</label>
              <input type="text" id="stay_special" class="form-control nb-input"
                     placeholder="e.g. early check-in, Ganga view"
                     value="{{ $editRequests }}"
                     oninput="buildEditShortPlan()">
            </div>
          </div>

          {{-- Preview strip --}}
          <div id="stay-plan-preview" style="margin-top:16px;background:#F0FDF4;border:1.5px solid #BBF7D0;border-radius:10px;padding:12px 16px;">
            <div style="font-size:.65rem;font-weight:800;color:#065F46;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Voucher Summary Preview</div>
            <div id="stay-plan-text" style="font-size:.82rem;color:#14532D;font-weight:600;line-height:1.6;">{{ $shortPlan }}</div>
          </div>

          {{-- Hidden service fields --}}
          @if($items->first())
          <input type="hidden" name="services[0][id]"          value="{{ $items->first()->id }}">
          @endif
          <input type="hidden" name="services[0][quantity]"    value="1">
          <input type="hidden" name="services[0][service_date]" id="stay-svc-date"
                 value="{{ optional($items->first())->service_date ? optional($items->first())->service_date->format('Y-m-d') : $editCheckin }}">
        </div>
      </div>

    </div>{{-- /left --}}

    {{-- ═══ SIDEBAR ═══ --}}
    <div class="nb-sidebar">
      <div class="nb-sidebar-card">
        <div class="nb-sidebar-head">
          <div class="nb-card-icon" style="background:#F0FDF4;color:#10B981;width:28px;height:28px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <div class="nb-card-title" style="font-size:.9rem;">Booking Amount</div>
        </div>

        <div class="nb-amount-row">
          <label class="nb-label" style="display:flex;align-items:center;justify-content:space-between;">
            Total Amount (₹) <span class="nb-req">*</span>
            <span id="stay-price-auto-tag" style="{{ $selectedTplId ? 'display:inline-flex' : 'display:none' }};font-size:.63rem;font-weight:700;color:#10B981;background:#D1FAE5;padding:2px 7px;border-radius:20px;text-transform:uppercase;letter-spacing:.03em;">Auto</span>
          </label>
          <div class="nb-rupee-wrap">
            <span class="nb-rupee">₹</span>
            <input type="number" name="services[0][unit_price]" id="stay-price-field"
                   class="form-control nb-input nb-rupee-input"
                   placeholder="Total amount" min="0" step="0.01"
                   value="{{ $netTotal > 0 ? $netTotal : 0 }}"
                   oninput="calcStayBalance()">
          </div>
          <input type="hidden" name="services[0][total_price]" id="stay-total-price-hidden" value="{{ $netTotal }}">
          <div id="stay-per-night-note" style="font-size:.7rem;color:#6B7280;margin-top:5px;display:none;font-weight:600;"></div>
        </div>

        <div class="nb-amount-row">
          <label class="nb-label">Discount (₹)</label>
          <div class="nb-rupee-wrap">
            <span class="nb-rupee">₹</span>
            <input type="number" name="discount" id="stay-discount"
                   class="form-control nb-input nb-rupee-input"
                   value="{{ number_format($discountStored, 2, '.', '') }}" min="0"
                   oninput="calcStayBalance()">
          </div>
        </div>

        {{-- Live Payment Summary --}}
        <div class="nb-pay-sum">
          <div class="nb-pay-sum-head">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Payment Summary
            <span id="stay-pay-badge" class="nb-balance-badge {{ $pendAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'due') }}" style="margin-left:auto;font-size:.58rem;">
              {{ $pendAmt <= 0 ? 'PAID' : ($paidAmt > 0 ? 'PARTIAL' : 'DUE') }}
            </span>
          </div>
          <div class="nb-pay-sum-body">
            <div class="nb-pay-sum-row">
              <span class="nb-pay-sum-lbl">Total Amount</span>
              <span class="nb-pay-sum-val" id="stay-sum-total">₹{{ number_format($netTotal, 2) }}</span>
            </div>
            <div class="nb-pay-sum-formula" id="stay-sum-formula" style="display:none;"></div>
            <div class="nb-pay-sum-row" id="stay-sum-discount-row" style="{{ $discountStored > 0 ? '' : 'display:none;' }}">
              <span class="nb-pay-sum-lbl">Discount</span>
              <span class="nb-pay-sum-val" id="stay-sum-discount" style="color:#F59E0B;">-₹{{ number_format($discountStored, 2) }}</span>
            </div>
            <div class="nb-pay-sum-row">
              <span class="nb-pay-sum-lbl">Amount Paid</span>
              <span class="nb-pay-sum-val nb-pay-sum-advance">₹{{ number_format($paidAmt, 2) }}</span>
            </div>
            <div class="nb-pay-sum-row">
              <span class="nb-pay-sum-lbl" style="font-weight:700;color:#0F172A;">Balance Due</span>
              <span class="nb-pay-sum-balance" id="stay-balance">₹{{ number_format($pendAmt, 2) }}</span>
            </div>
            <div class="nb-pay-sum-bar-wrap">
              <div class="nb-pay-sum-bar-lbl">
                <span>Payment Progress</span>
                <span id="stay-sum-pct" style="color:#10B981;font-weight:700;">{{ round($paidPct, 1) }}%</span>
              </div>
              <div class="nb-pay-sum-bar-track">
                <div class="nb-pay-sum-bar-fill" id="stay-sum-bar" style="width:{{ $paidPct }}%;"></div>
              </div>
            </div>
          </div>
        </div>

        {{-- ── PAYMENTS SECTION ── --}}
        <div style="margin-top:10px;border-top:1px solid #F1F5F9;padding-top:12px;">

          {{-- Section header --}}
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <div style="font-size:.78rem;font-weight:700;color:#0F172A;">💳 Payments</div>
            <button type="button" onclick="toggleAddPayment()"
                    style="background:#EFF6FF;color:#2563eb;border:1px solid #BFDBFE;border-radius:7px;padding:4px 10px;font-size:.72rem;font-weight:700;cursor:pointer;">
              + Add Payment
            </button>
          </div>

          {{-- Add Payment Form --}}
          <div id="add-payment-wrap" style="display:none;background:#F8FAFF;border:1.5px solid #BFDBFE;border-radius:12px;padding:14px;margin-bottom:10px;">
            <form action="{{ route('bookings.add-payment', $booking->id) }}" method="POST" id="addPaymentForm">
              @csrf
              <div style="font-size:.68rem;font-weight:800;color:#1e40af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">New Payment</div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div>
                  <label class="nb-label">Amount (₹) <span class="nb-req">*</span></label>
                  <div class="nb-rupee-wrap">
                    <span class="nb-rupee">₹</span>
                    <input type="number" name="amount" class="form-control nb-input nb-rupee-input"
                           placeholder="0.00" min="0.01" step="0.01">
                  </div>
                </div>
                <div>
                  <label class="nb-label">Date <span class="nb-req">*</span></label>
                  <input type="date" name="payment_date" class="form-control nb-input"
                         value="{{ now()->format('Y-m-d') }}">
                </div>
                <div>
                  <label class="nb-label">Method <span class="nb-req">*</span></label>
                  <select name="payment_method" class="form-select nb-input">
                    @foreach(['cash'=>'Cash','upi'=>'UPI','bank_transfer'=>'Bank Transfer','card'=>'Card','cheque'=>'Cheque','other'=>'Other'] as $v=>$l)
                    <option value="{{ $v }}">{{ $l }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label class="nb-label">Account <span class="nb-req">*</span></label>
                  <select name="payment_account_id" class="form-select nb-input">
                    <option value="">Select…</option>
                    @foreach($paymentAccounts as $acc)
                    <option value="{{ $acc->id }}">{{ $acc->account_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div style="margin-top:8px;">
                <label class="nb-label">Reference No.</label>
                <input type="text" name="reference_number" class="form-control nb-input"
                       placeholder="UPI ID / Txn ref / Cheque no.">
              </div>
              <div style="display:flex;gap:6px;margin-top:10px;">
                <button type="submit"
                        style="flex:1;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border:none;border-radius:8px;padding:8px;font-size:.78rem;font-weight:700;cursor:pointer;">
                  ✓ Save Payment
                </button>
                <button type="button" onclick="toggleAddPayment()"
                        style="background:#F1F5F9;color:#64748b;border:1px solid #E2E8F0;border-radius:8px;padding:8px 12px;font-size:.78rem;font-weight:600;cursor:pointer;">
                  Cancel
                </button>
              </div>
            </form>
          </div>

          {{-- Existing payments list --}}
          @if($payments->count() > 0)
          <div id="payments-list">
            @foreach($payments as $pmt)
            <div class="pmt-item" id="pmt-row-{{ $pmt->id }}">
              {{-- Display view --}}
              <div id="pmt-view-{{ $pmt->id }}" style="width:100%;">
                <div style="display:flex;align-items:center;justify-content:space-between;width:100%;">
                  <div>
                    <div class="pmt-date">{{ \Carbon\Carbon::parse($pmt->payment_date)->format('d M Y') }}</div>
                    <div class="pmt-method">{{ ucwords(str_replace('_',' ',$pmt->payment_method ?? 'cash')) }}
                      @if($pmt->reference_number) · <span style="color:#94a3b8;">{{ $pmt->reference_number }}</span>@endif
                    </div>
                  </div>
                  <div style="display:flex;align-items:center;gap:6px;">
                    <div class="pmt-amt">+₹{{ number_format($pmt->amount, 2) }}</div>
                    <button type="button" onclick="toggleEditPayment({{ $pmt->id }})"
                            style="background:#EFF6FF;color:#2563eb;border:1px solid #BFDBFE;border-radius:5px;padding:2px 7px;font-size:.65rem;font-weight:700;cursor:pointer;">Edit</button>
                    <form action="{{ route('bookings.delete-payment', [$booking->id, $pmt->id]) }}" method="POST"
                          onsubmit="return confirm('Delete this payment?')" style="margin:0;">
                      @csrf @method('DELETE')
                      <button type="submit"
                              style="background:#FEE2E2;color:#DC2626;border:1px solid #FECACA;border-radius:5px;padding:2px 7px;font-size:.65rem;font-weight:700;cursor:pointer;">✕</button>
                    </form>
                  </div>
                </div>
              </div>

              {{-- Inline edit form --}}
              <div id="pmt-edit-{{ $pmt->id }}" style="display:none;width:100%;background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:10px;padding:12px;margin-top:6px;">
                <form action="{{ route('bookings.update-payment', [$booking->id, $pmt->id]) }}" method="POST">
                  @csrf @method('PUT')
                  <div style="font-size:.65rem;font-weight:800;color:#92400e;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Edit Payment</div>
                  <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                    <div>
                      <label class="nb-label">Amount (₹)</label>
                      <div class="nb-rupee-wrap">
                        <span class="nb-rupee">₹</span>
                        <input type="number" name="amount" class="form-control nb-input nb-rupee-input"
                               value="{{ $pmt->amount }}" min="0.01" step="0.01">
                      </div>
                    </div>
                    <div>
                      <label class="nb-label">Date</label>
                      <input type="date" name="payment_date" class="form-control nb-input"
                             value="{{ \Carbon\Carbon::parse($pmt->payment_date)->format('Y-m-d') }}">
                    </div>
                    <div>
                      <label class="nb-label">Method</label>
                      <select name="payment_method" class="form-select nb-input">
                        @foreach(['cash'=>'Cash','upi'=>'UPI','bank_transfer'=>'Bank Transfer','card'=>'Card','cheque'=>'Cheque','other'=>'Other'] as $v=>$l)
                        <option value="{{ $v }}" {{ $pmt->payment_method == $v ? 'selected':'' }}>{{ $l }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div>
                      <label class="nb-label">Account</label>
                      <select name="payment_account_id" class="form-select nb-input">
                        <option value="">Select…</option>
                        @foreach($paymentAccounts as $acc)
                        <option value="{{ $acc->id }}" {{ $pmt->payment_account_id == $acc->id ? 'selected':'' }}>
                          {{ $acc->account_name }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div style="margin-top:7px;">
                    <label class="nb-label">Reference No.</label>
                    <input type="text" name="reference_number" class="form-control nb-input"
                           value="{{ $pmt->reference_number }}" placeholder="Optional">
                  </div>
                  <div style="display:flex;gap:6px;margin-top:8px;">
                    <button type="submit"
                            style="flex:1;background:linear-gradient(135deg,#d97706,#b45309);color:#fff;border:none;border-radius:7px;padding:7px;font-size:.76rem;font-weight:700;cursor:pointer;">
                      ✓ Update
                    </button>
                    <button type="button" onclick="toggleEditPayment({{ $pmt->id }})"
                            style="background:#F1F5F9;color:#64748b;border:1px solid #E2E8F0;border-radius:7px;padding:7px 10px;font-size:.76rem;font-weight:600;cursor:pointer;">
                      Cancel
                    </button>
                  </div>
                </form>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <div style="font-size:.76rem;color:#94a3b8;text-align:center;padding:10px 0;">No payments recorded yet.</div>
          @endif

        </div>{{-- /payments section --}}

        <button type="submit" class="btn-save">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          Save Changes
        </button>
      </div>
    </div>{{-- /sidebar --}}

  </div>{{-- /nb-layout --}}
  </form>

</div>{{-- /cb-page --}}

<script>
// ── State ─────────────────────────────────────────────────────────
let selectedHotelPrice = 0;
let selectedHotelName  = '';
let isCustomHotel      = false;

// ── Init on load ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  feather.replace();

  // Pre-select the existing hotel
  @if($selectedTplId)
    const existingCard = document.querySelector('.hotel-card[data-id="{{ $selectedTplId }}"]');
    if (existingCard) {
      selectedHotelPrice = parseFloat(existingCard.dataset.price) || 0;
      selectedHotelName  = existingCard.dataset.name || '';
      isCustomHotel      = false;
    }
  @else
    isCustomHotel     = true;
    selectedHotelName = '{{ addslashes($editProperty) }}';
  @endif

  updatePerNightNote();
  calcStayBalance();
});

// ── Hotel selection ───────────────────────────────────────────────
function selectHotel(card) {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  selectedHotelPrice = parseFloat(card.dataset.price) || 0;
  selectedHotelName  = card.dataset.name || '';
  isCustomHotel      = false;
  document.getElementById('stay-svc-tmpl-id').value = card.dataset.id;
  document.getElementById('stay-custom-hotel-wrap').style.display = 'none';
  recalcStayTotal();
  buildEditShortPlan();
}

function selectHotelCustom() {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('hotel-card-custom').classList.add('selected');
  selectedHotelPrice = 0;
  isCustomHotel      = true;
  document.getElementById('stay-svc-tmpl-id').value = '';
  document.getElementById('stay-custom-hotel-wrap').style.display = 'block';
}

function clearHotel() {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  selectedHotelPrice = 0; selectedHotelName = ''; isCustomHotel = false;
  document.getElementById('stay-svc-tmpl-id').value = '';
  document.getElementById('stay-custom-hotel-wrap').style.display = 'none';
  const pf = document.getElementById('stay-price-field');
  if (pf) { pf.value = ''; pf.style.background = ''; }
  document.getElementById('stay-price-auto-tag').style.display = 'none';
  updatePerNightNote();
  calcStayBalance();
}

function saveCustomHotel() {
  const name  = document.getElementById('stay_hotel').value.trim();
  const price = parseFloat(document.getElementById('stay_custom_price').value) || 0;
  if (!name) { alert('Please enter property name'); return; }
  selectedHotelName  = name;
  selectedHotelPrice = price;
  isCustomHotel      = true;
  document.getElementById('stay-price-auto-tag').style.display = price > 0 ? 'inline-flex' : 'none';
  recalcStayTotal();
  buildEditShortPlan();
}

function onCustomRoomTypeChange() {
  const sel   = document.getElementById('stay_custom_room_type');
  const price = parseFloat(sel.options[sel.selectedIndex]?.dataset.price) || 0;
  if (price > 0) {
    document.getElementById('stay_custom_price').value = price;
  }
}

// ── Nights calculation ────────────────────────────────────────────
function calcNights() {
  const ci = document.getElementById('stay_checkin').value;
  const co = document.getElementById('stay_checkout').value;
  const ni = document.getElementById('stay_nights');
  const sd = document.getElementById('stay-svc-date');
  if (ci && co) {
    const d = (new Date(co) - new Date(ci)) / 86400000;
    ni.value = d > 0 ? d : '';
    if (sd && ci) sd.value = ci;
  } else {
    ni.value = '';
  }
  recalcStayTotal();
}

// ── Adults / Kids counters ────────────────────────────────────────
function changeStayAdults(delta) {
  const h = document.getElementById('stay_adults_val');
  const v = Math.max(1, (parseInt(h.value) || 1) + delta);
  h.value = v;
  document.getElementById('stay-adults-display').textContent = v;
  buildEditShortPlan();
}
function changeStayKids(delta) {
  const h = document.getElementById('stay_kids_val');
  const v = Math.max(0, (parseInt(h.value) || 0) + delta);
  h.value = v;
  document.getElementById('stay-kids-display').textContent = v;
  buildEditShortPlan();
}

// ── Recalc total from rate × rooms × nights + extra bed ──────────
function recalcStayTotal() {
  if (selectedHotelPrice <= 0) return;
  const nights   = parseInt(document.getElementById('stay_nights').value) || 0;
  const rooms    = parseInt(document.getElementById('stay_rooms').value)   || 1;
  const extraBed = parseFloat(document.getElementById('stay_extra_bed_amt').value || 0);
  const total    = selectedHotelPrice * rooms * nights + extraBed * nights;
  const pf       = document.getElementById('stay-price-field');
  const ph       = document.getElementById('stay-total-price-hidden');
  if (pf) { pf.value = total > 0 ? total.toFixed(2) : ''; pf.style.background = total > 0 ? '#F0FDF4 !important' : ''; }
  if (ph) ph.value = total;
  document.getElementById('stay-price-auto-tag').style.display = total > 0 ? 'inline-flex' : 'none';
  updatePerNightNote();
  calcStayBalance();
}

function updatePerNightNote() {
  const nights = parseInt(document.getElementById('stay_nights').value) || 0;
  const rooms  = parseInt(document.getElementById('stay_rooms').value)  || 1;
  const note   = document.getElementById('stay-per-night-note');
  if (!note) return;
  if (selectedHotelPrice > 0 && nights > 0) {
    note.textContent = '₹' + selectedHotelPrice.toLocaleString('en-IN') + '/room × ' + nights + 'N × ' + rooms + 'R';
    note.style.display = 'block';
  } else {
    note.style.display = 'none';
  }
}

// ── Balance calculation ───────────────────────────────────────────
function calcStayBalance() {
  const total    = parseFloat(document.getElementById('stay-price-field')?.value) || 0;
  const discount = parseFloat(document.getElementById('stay-discount')?.value)    || 0;
  const paid     = {{ $paidAmt }};
  const net      = Math.max(0, total - discount);
  const bal      = Math.max(0, net - paid);
  const pct      = net > 0 ? Math.min(100, (paid / net) * 100) : 0;

  const fmt = v => '₹' + v.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});

  const st = document.getElementById('stay-sum-total');   if (st) st.textContent = fmt(total);
  const sb = document.getElementById('stay-balance');     if (sb) sb.textContent = fmt(bal);
  const sp = document.getElementById('stay-sum-pct');     if (sp) sp.textContent = pct.toFixed(1) + '%';
  const sf = document.getElementById('stay-sum-bar');     if (sf) sf.style.width = pct.toFixed(1) + '%';
  const dh = document.getElementById('stay-total-price-hidden'); if (dh) dh.value = total;

  const dr = document.getElementById('stay-sum-discount-row');
  const dd = document.getElementById('stay-sum-discount');
  if (discount > 0) {
    if (dd) dd.textContent = '-' + fmt(discount);
    if (dr) dr.style.display = '';
  } else {
    if (dr) dr.style.display = 'none';
  }

  const badge = document.getElementById('stay-pay-badge');
  if (badge) {
    badge.className = 'nb-balance-badge ' + (bal <= 0 ? 'paid' : (paid > 0 ? 'partial' : 'due'));
    badge.textContent = bal <= 0 ? 'PAID' : (paid > 0 ? 'PARTIAL' : 'DUE');
  }
}

// ── Build short_plan string before submit ─────────────────────────
function fmtDate(d) {
  if (!d) return '';
  const dt = new Date(d + 'T00:00:00');
  return dt.toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' });
}
function fmtTime(t) {
  if (!t) return '';
  const [h, m] = t.split(':').map(Number);
  const ampm = h >= 12 ? 'PM' : 'AM';
  return ((h % 12) || 12) + ':' + String(m).padStart(2,'0') + ' ' + ampm;
}

function buildEditShortPlan() {
  const ci  = document.getElementById('stay_checkin')?.value;
  const co  = document.getElementById('stay_checkout')?.value;
  const cit = document.getElementById('stay_checkin_time')?.value;
  const cot = document.getElementById('stay_checkout_time')?.value;
  const ni  = parseInt(document.getElementById('stay_nights')?.value) || 0;
  const rooms = parseInt(document.getElementById('stay_rooms')?.value) || 1;
  const rtype = document.getElementById('stay_room_type')?.value;
  const adults= parseInt(document.getElementById('stay_adults_val')?.value) || 1;
  const kids  = parseInt(document.getElementById('stay_kids_val')?.value)   || 0;
  const meal  = document.getElementById('stay_meal')?.value;
  const extra = parseFloat(document.getElementById('stay_extra_bed_amt')?.value) || 0;
  const req   = document.getElementById('stay_special')?.value?.trim();

  const prop = isCustomHotel
    ? (document.getElementById('stay_hotel')?.value?.trim() || '')
    : selectedHotelName;

  const parts = [];
  if (prop)  parts.push('Property: ' + prop);
  if (ci && co) parts.push('Check-in: ' + fmtDate(ci) + ' ' + fmtTime(cit) + '  →  Check-out: ' + fmtDate(co) + ' ' + fmtTime(cot));
  if (ni > 0) parts.push('Duration: ' + ni + ' night(s)');
  parts.push('Rooms: ' + rooms + (rtype ? ' (' + rtype + ')' : ''));
  parts.push('Guests: ' + adults + ' Adult' + (adults !== 1 ? 's' : '') + (kids > 0 ? ', ' + kids + ' Child' + (kids !== 1 ? 'ren' : '') : ''));
  if (meal) parts.push('Meal Plan: ' + meal);
  if (extra > 0) parts.push('Extra Bed: ₹' + extra.toLocaleString('en-IN'));
  if (req)  parts.push('Requests: ' + req);

  const sp = parts.join(' | ');
  document.getElementById('edit-short-plan').value = sp;

  // Update preview
  const pt = document.getElementById('stay-plan-text');
  if (pt) pt.textContent = sp || '—';
}

// ── Payment panel toggles ─────────────────────────────────────────
function toggleAddPayment() {
  const wrap = document.getElementById('add-payment-wrap');
  if (!wrap) return;
  const open = wrap.style.display === 'none' || wrap.style.display === '';
  wrap.style.display = open ? 'block' : 'none';
  if (open) wrap.querySelector('input[name="amount"]')?.focus();
}

function toggleEditPayment(id) {
  const editDiv = document.getElementById('pmt-edit-' + id);
  const viewDiv = document.getElementById('pmt-view-' + id);
  if (!editDiv) return;
  const open = editDiv.style.display === 'none' || editDiv.style.display === '';
  editDiv.style.display = open ? 'block' : 'none';
  if (viewDiv) viewDiv.style.opacity = open ? '.4' : '1';
}

// ── Pre-submit: ensure hidden price field is in sync ──────────────
document.getElementById('editForm')?.addEventListener('submit', function() {
  buildEditShortPlan();
  const pf = document.getElementById('stay-price-field');
  const ph = document.getElementById('stay-total-price-hidden');
  if (pf && ph) ph.value = pf.value;
});
</script>

@push('scripts')
<script>$(document).ready(function(){ feather.replace(); });</script>
@endpush
@endsection
