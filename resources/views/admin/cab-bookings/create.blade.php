@extends('admin.layouts.app')
@section('content')

<style>
:root{
  --n-bg:#EEF2FF;--n-card:#fff;--n-border:#E2E8F0;
  --n-indigo:#4F46E5;--n-emerald:#10B981;--n-amber:#F59E0B;
  --n-rose:#EF4444;--n-sky:#0EA5E9;--n-violet:#7C3AED;
  --n-r:14px;--n-t:.2s ease;
}
.cn-page{background:var(--n-bg);min-height:calc(100vh - var(--sa-navbar-h,64px));padding:20px 24px 32px;}

/* ── Header ── */
.cn-header {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 60%, #6D28D9 100%);
    border-radius: 16px;
    margin-top: 50px;
    padding: 22px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(79, 70, 229, .28);
}
.cn-header::before{
    content:'';position:absolute;top:-50px;right:-50px;
    width:200px;height:200px;background:rgba(255,255,255,.07);border-radius:50%;
    pointer-events:none;
}
.cn-header::after{
    content:'';position:absolute;bottom:-60px;left:20px;
    width:140px;height:140px;background:rgba(255,255,255,.04);border-radius:50%;
    pointer-events:none;
}
.cn-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:14px;}
.cn-header-icon{
    width:46px;height:46px;border-radius:13px;
    background:rgba(255,255,255,.18);
    display:flex;align-items:center;justify-content:center;
    font-size:22px;flex-shrink:0;
}
.cn-header-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 3px;letter-spacing:-.01em;line-height:1.2;}
.cn-header-text p{color:rgba(255,255,255,.7);font-size:.78rem;margin:0;line-height:1.4;}
.cn-back{
    position:relative;z-index:1;
    display:inline-flex;align-items:center;gap:6px;
    background:rgba(255,255,255,.15);
    color:#fff;
    border:1.5px solid rgba(255,255,255,.25);
    border-radius:10px;
    padding:9px 16px;
    font-size:.8rem;font-weight:700;
    text-decoration:none;
    transition:background .18s;
    white-space:nowrap;flex-shrink:0;
}
.cn-back:hover{background:rgba(255,255,255,.28);color:#fff;}

.cn-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:1100px){.cn-layout{grid-template-columns:1fr;}}

.cn-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05),0 2px 8px rgba(0,0,0,.04);margin-bottom:16px;overflow:hidden;}
.cn-card-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:12px;background:#FAFBFF;}
.cn-card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.cn-card-title{font-size:.87rem;font-weight:700;color:#0F172A;}
.cn-card-body{padding:20px;}

.cn-label{font-size:.72rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px;display:block;}
.cn-req{color:#EF4444;margin-left:2px;}
.cn-input{border:1.5px solid #E2E8F0 !important;border-radius:10px !important;padding:9px 13px !important;font-size:.875rem !important;color:#0F172A !important;background:#FAFBFF !important;transition:all .2s ease !important;width:100%;}
.cn-input:focus{border-color:#4F46E5 !important;box-shadow:0 0 0 3px rgba(79,70,229,.12) !important;background:#fff !important;outline:none !important;}
.cn-phone-wrap{position:relative;}
.cn-phone-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;font-weight:600;color:#64748B;z-index:2;pointer-events:none;white-space:nowrap;}
/* Higher-specificity rule beats the .cn-page .form-control shorthand padding */
.cn-page .form-control.cn-input-phone,
.cn-page .cn-input-phone{padding-left:40px !important;}

/* Trip Type Tabs */
.trip-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:4px;}
.trip-tab{border:2px solid #E2E8F0;border-radius:10px;padding:8px 14px;cursor:pointer;font-size:.78rem;font-weight:700;color:#64748B;background:#fff;transition:all .18s;user-select:none;display:flex;align-items:center;gap:6px;}
.trip-tab:hover{border-color:#4F46E5;color:#4F46E5;background:#EEF2FF;}
.trip-tab.active{border-color:#4F46E5;color:#fff;background:#4F46E5;}
.trip-tab input{display:none;}

/* Vehicle Cards */
.veh-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;margin-bottom:12px;}
.veh-card{border:2px solid #E8ECF4;border-radius:14px;padding:12px 10px;cursor:pointer;text-align:center;transition:all .22s ease;background:#fff;position:relative;user-select:none;}
.veh-card:hover{border-color:#4F46E5;background:#EEF2FF;transform:translateY(-2px);box-shadow:0 6px 18px rgba(79,70,229,.15);}
.veh-card.selected{border-color:#4F46E5;background:#EEF2FF;box-shadow:0 0 0 3px rgba(79,70,229,.2);}
.veh-card-check{position:absolute;top:7px;right:7px;width:18px;height:18px;background:#4F46E5;border-radius:50%;font-size:.62rem;color:#fff;display:none;align-items:center;justify-content:center;font-weight:800;}
.veh-card.selected .veh-card-check{display:flex;}
.veh-card-del{position:absolute;top:6px;left:6px;width:16px;height:16px;border-radius:50%;background:#FEE2E2;color:#DC2626;border:none;font-size:.65rem;font-weight:800;line-height:1;display:none;align-items:center;justify-content:center;cursor:pointer;z-index:5;padding:0;}
.veh-card.selected .veh-card-del{display:flex;}
.veh-card-del:hover{background:#DC2626;color:#fff;}
.veh-emoji{font-size:1.8rem;margin-bottom:6px;line-height:1;}
.veh-name{font-size:.74rem;font-weight:700;color:#0F172A;line-height:1.3;margin-bottom:4px;}
.veh-seats{font-size:.67rem;color:#94A3B8;font-weight:500;}
.veh-cat{font-size:.62rem;color:#7C3AED;background:#EDE9FE;padding:1px 6px;border-radius:8px;font-weight:700;text-transform:uppercase;}
.veh-custom{border-style:dashed;border-color:#C4B5FD;}
.veh-custom:hover{border-color:#7C3AED;background:#EDE9FE;}
.veh-custom.selected{border-color:#7C3AED;background:#EDE9FE;box-shadow:0 0 0 3px rgba(124,58,237,.18);}
.veh-custom.selected .veh-card-check{background:#7C3AED;}

/* Pricing inputs */
.price-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;}
.cn-rupee-wrap{position:relative;}
.cn-rupee{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.82rem;font-weight:600;color:#475569;z-index:2;pointer-events:none;}
.cn-rupee-input{padding-left:26px !important;}

/* Sidebar */
.cn-sidebar{position:sticky;top:20px;}
.cn-sidebar-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05);padding:20px;margin-bottom:16px;}
.cn-sidebar-head{display:flex;align-items:center;gap:10px;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #F1F5F9;}

.fare-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:.8rem;}
.fare-row+.fare-row{border-top:1px dashed #F1F5F9;}
.fare-lbl{color:#64748B;}
.fare-val{font-weight:700;color:#0F172A;}
.fare-total{font-size:1rem;font-weight:800;color:#4F46E5;padding:10px 0;border-top:2px solid #E8ECF4;margin-top:6px;}
.fare-balance{font-size:.95rem;font-weight:800;color:#EF4444;}

.btn-save{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:10px;padding:12px 28px;font-size:.92rem;font-weight:700;cursor:pointer;transition:opacity .2s,transform .2s;width:100%;justify-content:center;margin-top:6px;}
.btn-save:hover{opacity:.88;transform:translateY(-1px);color:#fff;}

.pay-summary{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;margin:12px 0;overflow:hidden;}
.pay-sum-head{background:linear-gradient(135deg,#0F172A,#1E293B);padding:10px 14px;display:flex;align-items:center;gap:8px;color:#fff;font-size:.76rem;font-weight:700;}
.pay-sum-body{padding:12px 14px;}
.pay-sum-bar-track{height:6px;background:#E2E8F0;border-radius:99px;overflow:hidden;margin-top:8px;}
.pay-sum-bar-fill{height:100%;background:linear-gradient(90deg,#4F46E5,#7C3AED);border-radius:99px;transition:width .4s;}

/* ── Fix form-control to match cn-input style ── */
.cn-page .form-control,
.cn-page select.form-control,
.cn-page textarea.form-control {
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 10px !important;
    padding: 9px 13px !important;
    font-size: .875rem !important;
    color: #0F172A !important;
    background: #FAFBFF !important;
    box-shadow: none !important;
    transition: border-color .2s, box-shadow .2s !important;
}
/* ── Fix rupee prefix padding — must beat .cn-page .form-control specificity ── */
.cn-page .form-control.cn-rupee-input,
.cn-page input.cn-rupee-input {
    padding-left: 26px !important;
}
.cn-page .form-control:focus,
.cn-page select.form-control:focus,
.cn-page textarea.form-control:focus {
    border-color: #4F46E5 !important;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important;
    background: #fff !important;
    outline: none !important;
}
.cn-page select.form-control {
    appearance: auto;
    cursor: pointer;
}
/* Fix select2 inside cn-page */
.cn-page .select2-container--default .select2-selection--single {
    height: 42px !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 10px !important;
    background: #FAFBFF !important;
    display: flex; align-items: center;
}
.cn-page .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #0F172A !important;
    line-height: 40px !important;
    padding-left: 12px !important;
}
.cn-page .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #4F46E5 !important;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important;
}
.cn-page .select2-container { width: 100% !important; }

/* ── Step badge on card head ── */
.cn-step{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;color:#fff;flex-shrink:0;}

/* ── Pricing table ── */
.price-table{width:100%;border-collapse:collapse;border-radius:12px;overflow:hidden;border:1.5px solid #E2E8F0;}
.price-table thead th{background:linear-gradient(135deg,#EEF2FF,#E0E7FF);color:#4338CA;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;padding:9px 14px;text-align:left;border-bottom:1.5px solid #C7D2FE;}
.price-table thead th:last-child{text-align:right;}
.price-table tbody tr{border-bottom:1px solid #F1F5F9;transition:background .12s;}
.price-table tbody tr:last-child{border-bottom:none;}
.price-table tbody tr:hover{background:#F8FAFF;}
.price-table td{padding:10px 14px;vertical-align:middle;}
.price-table td:last-child{text-align:right;width:160px;}
.price-table .td-lbl{font-size:.8rem;font-weight:600;color:#374151;display:flex;align-items:center;gap:6px;}
.price-table .td-lbl span{font-size:.65rem;color:#94A3B8;font-weight:400;}
.price-table .req-dot{width:6px;height:6px;border-radius:50%;background:#EF4444;flex-shrink:0;display:inline-block;}
.price-table .opt-dot{width:6px;height:6px;border-radius:50%;background:#D1D5DB;flex-shrink:0;display:inline-block;}

/* Fare preview strip */
.fare-strip{display:flex;align-items:center;gap:6px;flex-wrap:wrap;background:#F0FDF4;border:1px solid #A7F3D0;border-radius:10px;padding:10px 14px;margin-top:14px;}
.fare-strip-item{font-size:.78rem;font-weight:700;color:#065F46;}
.fare-strip-item.total{font-size:.9rem;color:#059669;}
.fare-strip-sep{color:#A7F3D0;font-weight:300;}

/* Sidebar section title */
.cn-scard-title{font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:#64748B;margin-bottom:12px;display:flex;align-items:center;gap:7px;}
.cn-scard-title i[data-feather]{width:14px;height:14px;}

/* Field hint */
.cn-hint{font-size:.68rem;color:#94A3B8;margin-top:3px;line-height:1.4;}

/* ── Pricing 2-column grid ── */
.price-22-grid{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:#E2E8F0;border-radius:12px;overflow:hidden;}
.price-22-item{background:#fff;padding:14px 16px;transition:background .12s;}
.price-22-item:hover{background:#F8FAFF;}
.price-22-req{background:#FAFBFF;}
.price-22-req:hover{background:#EEF2FF;}
.price-22-lbl{font-size:.8rem;font-weight:700;color:#374151;display:flex;align-items:center;gap:5px;margin-bottom:2px;}

/* ── Responsive ── */
@media(max-width:768px){
    .cn-page { padding: 12px; }
    .cn-card-body { padding: 14px; }
    .cn-layout { grid-template-columns: 1fr; }
    .cn-sidebar { position: static; top: auto; }
    .veh-grid { grid-template-columns: repeat(2, 1fr); }
    .price-grid { grid-template-columns: 1fr 1fr; }
    .price-22-grid { grid-template-columns: 1fr; }
}
@media(max-width:480px){
    .veh-grid { grid-template-columns: 1fr 1fr; }
    .trip-tabs { gap: 5px; }
    .trip-tab { padding: 7px 10px; font-size: .72rem; }
}
</style>

<style>
/* Remove page-content padding since cn-page handles its own */
.page-content { padding: 0 !important; }
</style>
<div class="cn-page">

  @if($errors->any())
  <div style="display:flex;align-items:flex-start;gap:10px;background:#FEF2F2;border:1.5px solid #FECACA;border-radius:12px;padding:13px 16px;margin-bottom:16px;font-size:.83rem;color:#991B1B;">
    <svg viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" width="18" height="18" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
      <div style="font-weight:700;margin-bottom:5px;">Please fix the following errors before saving:</div>
      <ul style="margin:0;padding-left:16px;">@foreach($errors->all() as $e)<li style="margin-bottom:2px;">{{ $e }}</li>@endforeach</ul>
    </div>
  </div>
  @endif

  {{-- Header --}}
  <div class="cn-header">
    <div class="cn-header-left">
      <div class="cn-header-icon">🚗</div>
      <div class="cn-header-text">
        <h1>New Cab Booking</h1>
        <p>Fill in the trip details to create a new booking</p>
      </div>
    </div>
    <a href="{{ route('cab-bookings.index') }}" class="cn-back">
      <i data-feather="arrow-left" style="width:13px;height:13px;stroke:#fff;"></i> Back
    </a>
  </div>

  <form action="{{ route('cab-bookings.store') }}" method="POST" id="cabForm">
    @csrf

  <div class="cn-layout">

    {{-- ══ LEFT ══ --}}
    <div>

      {{-- Customer Details --}}
      <div class="cn-card">
        <div class="cn-card-head">
          <div class="cn-card-icon" style="background:#EEF2FF;color:#4F46E5;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <div class="cn-card-title">Customer Details</div>
          <span class="cn-step ms-auto" style="background:#4F46E5;">1</span>
        </div>
        <div class="cn-card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="cn-label">Full Name <span class="cn-req">*</span></label>
              <input type="text" name="customer_name" class="form-control cn-input" required
                     value="{{ old('customer_name') }}" placeholder="e.g. Rahul Sharma">
              <div class="cn-hint">Guest's full name as per booking</div>
            </div>
            <div class="col-md-4">
              <label class="cn-label">Primary Mobile <span class="cn-req">*</span></label>
              @include('admin.partials.phone-input', [
                  'name'     => 'customer_phone',
                  'codeName' => 'customer_phone_cc',
                  'value'    => old('customer_phone'),
                  'required' => true,
              ])
            </div>
            <div class="col-md-4">
              <label class="cn-label">Alternate Mobile</label>
              @include('admin.partials.phone-input', [
                  'name'     => 'customer_alt_phone',
                  'codeName' => 'customer_alt_phone_cc',
                  'value'    => old('customer_alt_phone'),
                  'required' => false,
                  'placeholder' => 'Optional second number',
              ])
            </div>
            <div class="col-md-4">
              <label class="cn-label">Email Address</label>
              <input type="email" name="customer_email" class="form-control cn-input"
                     placeholder="rahul@example.com" value="{{ old('customer_email') }}">
              <div class="cn-hint">For booking confirmation email</div>
            </div>
            <div class="col-md-4">
              <label class="cn-label">GST Billing?</label>
              <div style="display:flex;align-items:center;gap:10px;margin:6px 0 8px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:.83rem;color:#475569;font-weight:600;cursor:pointer;background:#F1F5F9;border:1.5px solid #E2E8F0;border-radius:8px;padding:6px 12px;">
                  <input type="radio" name="has_gst" value="no" onchange="toggleGst(false)"
                         {{ old('gst_number') ? '' : 'checked' }}> No GST
                </label>
                <label style="display:flex;align-items:center;gap:6px;font-size:.83rem;color:#4F46E5;font-weight:600;cursor:pointer;background:#EEF2FF;border:1.5px solid #C7D2FE;border-radius:8px;padding:6px 12px;">
                  <input type="radio" name="has_gst" value="yes" onchange="toggleGst(true)"
                         {{ old('gst_number') ? 'checked' : '' }}> With GST
                </label>
              </div>
              <div id="gst-input-wrap" style="display:{{ old('gst_number') ? '' : 'none' }};">
                <input type="text" name="gst_number" class="form-control cn-input"
                       placeholder="GSTIN — e.g. 09ABCDE1234F1Z5" value="{{ old('gst_number') }}" maxlength="15">
              </div>
            </div>
            <div class="col-md-4">
              <label class="cn-label">Lead Source</label>
              <select name="lead_source_id" class="form-select cn-input">
                <option value="">— How did they find us? —</option>
                @foreach($leadSources as $ls)
                  <option value="{{ $ls->id }}" {{ old('lead_source_id') == $ls->id ? 'selected' : '' }}>{{ $ls->name }}</option>
                @endforeach
              </select>
              <div class="cn-hint">Where this booking came from</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Journey Details --}}
      <div class="cn-card">
        <div class="cn-card-head">
          <div class="cn-card-icon" style="background:#FEF3C7;color:#D97706;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div class="cn-card-title">Journey Details</div>
          <span class="cn-step ms-auto" style="background:#D97706;">2</span>
        </div>
        <div class="cn-card-body">

          {{-- Trip Type (Booking For) --}}
          <div class="row g-3" style="margin-bottom:4px;">
            <div class="col-12">
              <label class="cn-label">Trip / Booking For <span class="cn-req">*</span></label>
              <input type="text" name="trip_type" id="trip_type" list="trip-suggestions"
                     class="form-control cn-input" required autocomplete="off"
                     placeholder="e.g. Varanasi Local, Varanasi to Ayodhya, Airport Transfer…"
                     value="{{ old('trip_type') }}">
              <datalist id="trip-suggestions">
                <option value="Varanasi Local">
                <option value="Varanasi to Ayodhya">
                <option value="Varanasi to Prayagraj">
                <option value="Varanasi to Lucknow">
                <option value="Varanasi to Agra">
                <option value="Varanasi to Delhi">
                <option value="Airport Transfer">
                <option value="Railway Station Transfer">
                <option value="One Way">
                <option value="Round Trip">
                <option value="Multi City">
                <option value="Local Half Day">
                <option value="Local Full Day">
              </datalist>
              <div class="cn-hint">Type or pick a suggestion — this appears on the invoice</div>
            </div>
          </div>

          {{-- Route --}}
          <div class="row g-3" style="margin-bottom:4px;margin-top:4px;">
            <div class="col-md-6">
              <label class="cn-label">📍 Pickup Location <span class="cn-req">*</span></label>
              <input type="text" name="pickup_address" class="form-control cn-input" required
                     placeholder="e.g. Varanasi Cantt Railway Station, Nadesar" value="{{ old('pickup_address') }}">
              <div class="cn-hint">Full pickup address or landmark</div>
            </div>
            <div class="col-md-6">
              <label class="cn-label">🏁 Drop Location <span class="cn-req">*</span></label>
              <input type="text" name="drop_address" class="form-control cn-input" required
                     placeholder="e.g. Lucknow Chaudhary Charan Singh Airport" value="{{ old('drop_address') }}">
              <div class="cn-hint">Final destination address</div>
            </div>
          </div>

          {{-- Date / Time / Days --}}
          <div class="row g-3" style="margin-top:4px;">
            <div class="col-6 col-md-3">
              <label class="cn-label">Pickup Date <span class="cn-req">*</span></label>
              <input type="date" name="pickup_date" id="pickup_date" class="form-control cn-input" required
                     value="{{ old('pickup_date', date('Y-m-d')) }}" onchange="calcDays()">
            </div>
            <div class="col-6 col-md-3">
              <label class="cn-label">Pickup Time <span class="cn-req">*</span></label>
              <input type="time" name="pickup_time" id="pickup_time" class="form-control cn-input" required
                     value="{{ old('pickup_time', '08:00') }}">
            </div>
            <div class="col-6 col-md-3" id="return-date-wrap">
              <label class="cn-label">Return Date <span style="font-size:.65rem;color:#94A3B8;font-weight:400;">(round trip)</span></label>
              <input type="date" name="return_date" id="return_date" class="form-control cn-input"
                     value="{{ old('return_date') }}" onchange="calcDays()">
            </div>
            <div class="col-6 col-md-2">
              <label class="cn-label">
                Days <span class="cn-req">*</span>
                <span id="days-auto-badge" style="display:none;font-size:.6rem;background:#D1FAE5;color:#059669;padding:1px 6px;border-radius:6px;font-weight:700;text-transform:none;letter-spacing:0;margin-left:4px;">Auto</span>
              </label>
              <input type="number" name="total_days" id="total_days" class="form-control cn-input"
                     min="1" value="{{ old('total_days', 1) }}" required placeholder="1" oninput="recalcFare()">
            </div>
            <div class="col-6 col-md-2">
              <label class="cn-label">Est. KM</label>
              <input type="number" name="total_km" id="total_km" class="form-control cn-input"
                     min="0" step="0.1" placeholder="e.g. 320" value="{{ old('total_km') }}">
              <div class="cn-hint">Approx. distance</div>
            </div>
          </div>

          {{-- Trip Legs (date-wise) --}}
          <div style="margin-top:18px;padding-top:16px;border-top:1px dashed #E2E8F0;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2px;">
              <label class="cn-label" style="margin-bottom:0;">Additional Trip Legs <span style="font-size:.65rem;color:#94A3B8;font-weight:400;text-transform:none;">(optional — for multi-day / multi-stop trips)</span></label>
            </div>
            <div class="cn-hint" style="margin-top:0;margin-bottom:10px;">Add a row for each extra date with its own pickup / drop and fare</div>

            <div id="legs_container"></div>

            <button type="button" class="cn-back" style="background:#EEF2FF;border-color:#C7D2FE;color:#4F46E5;padding:8px 14px;font-size:.78rem;" onclick="addLeg()">
              <i data-feather="plus" style="width:13px;height:13px;stroke:#4F46E5;"></i> Add More
            </button>
          </div>

        </div>
      </div>

      {{-- Vehicle Selection --}}
      <div class="cn-card">
        <div class="cn-card-head">
          <div class="cn-card-icon" style="background:#D1FAE5;color:#059669;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <div class="cn-card-title">Vehicle Selection <span style="font-size:.72rem;font-weight:400;color:#EF4444;">* required</span></div>
          <span class="cn-step ms-auto" style="background:#059669;">3</span>
        </div>
        <div class="cn-card-body">
          <div class="veh-grid" id="veh-cards-grid">
            @foreach($vehicles as $v)
            <div class="veh-card"
                 data-id="{{ $v->id }}"
                 data-name="{{ $v->name }}"
                 data-seats="{{ $v->seating_capacity }}"
                 data-category="{{ $v->category }}"
                 onclick="selectVehicle(this)">
              <button type="button" class="veh-card-del" onclick="event.stopPropagation();removeVehicle(this.closest('.veh-card'))" title="Remove">✕</button>
              <div class="veh-card-check">✓</div>
              <div class="veh-emoji">{{ $v->emoji }}</div>
              <div class="veh-name">{{ $v->name }}</div>
              <div class="veh-seats">{{ $v->seating_capacity }} Seats</div>
              <div class="veh-cat" style="margin-top:4px;">{{ $v->category_label }}</div>
            </div>
            @endforeach
            {{-- Custom Vehicle --}}
            <div class="veh-card veh-custom" id="veh-card-custom" onclick="selectVehicleCustom()">
              <button type="button" class="veh-card-del" onclick="event.stopPropagation();removeVehicle(this.closest('.veh-card'))" title="Remove">✕</button>
              <div class="veh-card-check">✓</div>
              <div class="veh-emoji">✏️</div>
              <div class="veh-name" style="color:#7C3AED;">Other Vehicle</div>
              <div class="veh-seats">Custom entry</div>
            </div>
          </div>

          {{-- Custom Vehicle Form --}}
          <div id="veh-custom-wrap" style="display:none;margin-top:12px;background:#F5F3FF;border:1.5px solid #C4B5FD;border-radius:12px;padding:14px 16px;">
            <div style="font-size:.7rem;font-weight:800;color:#7C3AED;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">✏️ Custom Vehicle</div>
            <div class="row g-2">
              <div class="col-md-8">
                <label class="cn-label" style="color:#6D28D9;">Vehicle Name <span class="cn-req">*</span></label>
                <input type="text" id="custom_veh_name" class="form-control cn-input"
                       placeholder="e.g. Toyota Fortuner" style="border-color:#C4B5FD !important;"
                       oninput="onCustomVehName()">
              </div>
              <div class="col-md-4">
                <label class="cn-label" style="color:#6D28D9;">Seats</label>
                <input type="number" id="custom_veh_seats" class="form-control cn-input"
                       placeholder="4" min="1" style="border-color:#C4B5FD !important;"
                       oninput="onCustomVehName()">
              </div>
            </div>
          </div>

          {{-- Hidden fields --}}
          <input type="hidden" name="vehicle_id"       id="f_vehicle_id"   value="{{ old('vehicle_id') }}">
          <input type="hidden" name="vehicle_name"     id="f_vehicle_name" value="{{ old('vehicle_name') }}">
          <input type="hidden" name="seating_capacity" id="f_veh_seats"    value="{{ old('seating_capacity') }}">

          {{-- Selection summary row (shown after selection) --}}
          <div id="veh-details-row" style="display:none;margin-top:14px;">

            {{-- Vehicle count counter --}}
            <div style="display:flex;align-items:center;gap:14px;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:12px;padding:12px 16px;margin-bottom:12px;">
              <div style="flex:1;">
                <div style="font-size:.65rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">No. of Vehicles</div>
                <div style="font-size:.75rem;color:#94A3B8;">Guests booking same cab type. Fare fields below are per vehicle &mdash; total fare = fare × vehicle count.</div>
              </div>
              <div style="display:flex;align-items:center;gap:10px;">
                <button type="button" onclick="changeVehCount(-1)"
                        style="width:34px;height:34px;border-radius:50%;border:1.5px solid #CBD5E1;background:#fff;font-size:1.2rem;font-weight:700;color:#475569;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;"
                        onmouseover="this.style.borderColor='#4F46E5';this.style.color='#4F46E5';"
                        onmouseout="this.style.borderColor='#CBD5E1';this.style.color='#475569';">−</button>
                <span id="veh-count-val" style="font-size:1.6rem;font-weight:800;color:#4F46E5;min-width:36px;text-align:center;line-height:1;">1</span>
                <button type="button" onclick="changeVehCount(1)"
                        style="width:34px;height:34px;border-radius:50%;border:1.5px solid #4F46E5;background:#4F46E5;font-size:1.2rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;"
                        onmouseover="this.style.opacity='.85';"
                        onmouseout="this.style.opacity='1';">+</button>
              </div>
              <input type="hidden" name="vehicle_count" id="f_vehicle_count" value="1">
            </div>

            <div class="row g-3">
              <div class="col-md-8">
                <label class="cn-label">Selected Vehicle(s)</label>
                <input type="text" id="veh_name_display" class="form-control cn-input" readonly
                       style="background:#F0FDF4 !important;font-weight:700;color:#059669 !important;">
              </div>
              <div class="col-md-4">
                <label class="cn-label">Total Seats</label>
                <input type="number" id="veh_seats_display" class="form-control cn-input" readonly
                       style="background:#F0FDF4 !important;font-weight:700;color:#059669 !important;">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Passengers & Extras --}}
      <div class="cn-card">
        <div class="cn-card-head">
          <div class="cn-card-icon" style="background:#FEF3C7;color:#B45309;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
          </div>
          <div class="cn-card-title">Passengers & Extras</div>
          <span class="cn-step ms-auto" style="background:#B45309;">4</span>
        </div>
        <div class="cn-card-body">

          {{-- Persons row --}}
          <div class="row g-3" style="margin-bottom:6px;">
            <div class="col-6 col-md-3">
              <label class="cn-label">Adults <span class="cn-req">*</span></label>
              <div style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:6px 10px;">
                <button type="button" onclick="adjCount('no_of_adults',-1)"
                        style="width:30px;height:30px;border-radius:8px;border:1.5px solid #CBD5E1;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
                <span id="no_of_adults_dis" style="font-size:1.3rem;font-weight:800;color:#4F46E5;min-width:28px;text-align:center;">1</span>
                <button type="button" id="btn-adults-plus" onclick="adjCount('no_of_adults',1)"
                        style="width:30px;height:30px;border-radius:8px;border:1.5px solid #4F46E5;background:#4F46E5;font-size:1.1rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
              </div>
              <input type="hidden" name="no_of_adults" id="no_of_adults" value="{{ old('no_of_adults', 1) }}">
            </div>
            <div class="col-6 col-md-3">
              <label class="cn-label">Children</label>
              <div style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:6px 10px;">
                <button type="button" onclick="adjCount('no_of_children',-1)"
                        style="width:30px;height:30px;border-radius:8px;border:1.5px solid #CBD5E1;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
                <span id="no_of_children_dis" style="font-size:1.3rem;font-weight:800;color:#7C3AED;min-width:28px;text-align:center;">0</span>
                <button type="button" id="btn-children-plus" onclick="adjCount('no_of_children',1)"
                        style="width:30px;height:30px;border-radius:8px;border:1.5px solid #7C3AED;background:#7C3AED;font-size:1.1rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
              </div>
              <input type="hidden" name="no_of_children" id="no_of_children" value="{{ old('no_of_children', 0) }}">
            </div>
            <div class="col-md-3">
              <label class="cn-label">Flight / Train No.</label>
              <input type="text" name="flight_train_number" class="form-control cn-input"
                     placeholder="e.g. AI-202, 12345" value="{{ old('flight_train_number') }}">
              <div class="cn-hint">For airport / station transfers</div>
            </div>
            <div class="col-md-3">
              <label class="cn-label">Luggage Details</label>
              <input type="text" name="luggage_details" class="form-control cn-input"
                     placeholder="e.g. 2 large bags, 1 stroller" value="{{ old('luggage_details') }}">
            </div>
          </div>

          <div class="cn-hint" id="pax-seat-hint" style="margin-bottom:16px;"></div>

          {{-- Add-on toggles --}}
          <label class="cn-label" style="margin-bottom:10px;">Add-ons & Special Requirements</label>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;">

            @foreach([
              'carrier_on_roof'       => ['🚗', 'Carrier on Roof',    'Roof luggage carrier needed'],
              'child_seat'            => ['👶', 'Child Seat',          'Baby / child safety seat'],
              'wheelchair_accessible' => ['♿', 'Wheelchair Access',   'Accessible vehicle needed'],
              'ac_required'           => ['❄️', 'AC Required',         'Air-conditioned cab'],
            ] as $field => [$icon, $label, $hint])
            <label style="display:flex;flex-direction:column;align-items:center;gap:6px;border:2px solid #E2E8F0;border-radius:12px;padding:12px 10px;cursor:pointer;text-align:center;transition:all .18s;background:#fff;user-select:none;"
                   id="addon-lbl-{{ $field }}"
                   onclick="toggleAddon('{{ $field }}', this)">
              <span style="font-size:1.5rem;line-height:1;">{{ $icon }}</span>
              <span style="font-size:.76rem;font-weight:700;color:#374151;">{{ $label }}</span>
              <span style="font-size:.65rem;color:#94A3B8;line-height:1.3;">{{ $hint }}</span>
              <input type="hidden" name="{{ $field }}" id="addon-{{ $field }}"
                     value="{{ old($field, $field === 'ac_required' ? '1' : '0') }}">
            </label>
            @endforeach

          </div>

        </div>
      </div>

      {{-- Pricing Details --}}
      <div class="cn-card">
        <div class="cn-card-head">
          <div class="cn-card-icon" style="background:#EFF6FF;color:#3B82F6;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <div class="cn-card-title">Pricing Breakdown</div>
          <span class="cn-step ms-auto" style="background:#3B82F6;">5</span>
        </div>
        <div class="cn-card-body">
          <div class="price-22-grid">
            @foreach([
              'base_fare'        => ['Base Fare',         true,  'Core trip fare'],
              'driver_allowance' => ['Driver Allowance',  false, 'Driver stay/food'],
              'toll_tax'         => ['Toll Tax',          false, 'Highway tolls'],
              'parking'          => ['Parking Charges',   false, 'Parking fees'],
              'state_tax'        => ['State Tax',         false, 'Inter-state tax'],
              'night_charges'    => ['Night Charges',     false, 'Night surcharge'],
              'extra_km_charges' => ['Extra KM Charges',  false, 'Beyond included km'],
            ] as $field => [$label, $req, $hint])
            <div class="price-22-item {{ $req ? 'price-22-req' : '' }}">
              <div class="price-22-lbl">
                <span class="{{ $req ? 'req-dot' : 'opt-dot' }}"></span>
                {{ $label }}
                @if($req)<span class="cn-req" style="margin-left:1px;">*</span>@endif
              </div>
              <div class="cn-hint" style="margin-bottom:6px;">{{ $hint }}</div>
              <div class="cn-rupee-wrap">
                <span class="cn-rupee">₹</span>
                <input type="number" name="{{ $field }}" id="{{ $field }}"
                       class="form-control cn-input cn-rupee-input"
                       min="0" step="0.01" placeholder="0.00"
                       value="{{ old($field, 0) }}"
                       oninput="recalcFare()"
                       {{ $req ? 'required' : '' }}>
              </div>
            </div>
            @endforeach
          </div>

          <div class="fare-strip" style="margin-top:14px;">
            <span class="fare-strip-item total" id="fare-preview-text">Enter fare details above</span>
          </div>
        </div>
      </div>

    </div>{{-- /left --}}

    {{-- ══ SIDEBAR ══ --}}
    <div class="cn-sidebar">

      {{-- Booking Amount --}}
      <div class="cn-sidebar-card">
        <div class="cn-scard-title"><i data-feather="credit-card"></i> Booking Amount</div>

        <div class="pay-summary">
          <div class="pay-sum-head">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Fare Breakdown
          </div>
          <div class="pay-sum-body">
            <div class="fare-row"><span class="fare-lbl">Subtotal</span><span class="fare-val" id="s-subtotal">₹0.00</span></div>
            <div class="fare-row" id="s-disc-row" style="display:none;">
              <span class="fare-lbl">Discount</span>
              <span class="fare-val" id="s-discount" style="color:#F59E0B;">-₹0.00</span>
            </div>
            <div class="fare-row fare-total"><span>Total Fare</span><span id="s-total">₹0.00</span></div>
            <div class="fare-row"><span class="fare-lbl">Advance Paid</span><span class="fare-val" id="s-advance" style="color:#10B981;">₹0.00</span></div>
            <div class="fare-row fare-balance"><span>Balance Due</span><span id="s-balance">₹0.00</span></div>
            <div class="pay-sum-bar-track">
              <div class="pay-sum-bar-fill" id="s-bar" style="width:0%;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.68rem;color:#94A3B8;margin-top:4px;">
              <span>Payment progress</span><span id="s-pct">0%</span>
            </div>
          </div>
        </div>

        {{-- Discount --}}
        <div style="margin-bottom:12px;">
          <label class="cn-label">Discount Amount</label>
          <div class="cn-rupee-wrap">
            <span class="cn-rupee">₹</span>
            <input type="number" name="discount" id="discount" class="form-control cn-input cn-rupee-input"
                   min="0" step="0.01" placeholder="0.00" value="{{ old('discount', 0) }}"
                   oninput="recalcFare()">
          </div>
          <div class="cn-hint">Deducted from total fare</div>
        </div>

        {{-- Advance Paid --}}
        <div style="margin-bottom:12px;">
          <label class="cn-label">Advance Received</label>
          <div class="cn-rupee-wrap">
            <span class="cn-rupee">₹</span>
            <input type="number" name="advance_paid" id="advance_paid" class="form-control cn-input cn-rupee-input"
                   min="0" step="0.01" placeholder="0.00" value="{{ old('advance_paid', 0) }}"
                   oninput="recalcFare()">
          </div>
          <div class="cn-hint">Amount already collected from guest</div>
        </div>

        {{-- Payment Method --}}
        <div style="margin-bottom:12px;">
          <label class="cn-label">Payment Method <span style="color:#EF4444;">*</span></label>
          <select name="payment_method" id="cabPaymentMethod" class="form-select cn-input" required onchange="toggleCabPaymentFields()">
            @foreach(['cash'=>'💵 Cash','upi'=>'📱 UPI','bank_transfer'=>'🏦 Bank Transfer','card'=>'💳 Card','cheque'=>'📝 Cheque'] as $v=>$l)
              <option value="{{ $v }}" {{ old('payment_method','cash')==$v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>

        {{-- Cash Receiver Name (shown when Cash selected) --}}
        <div id="cabCashReceiverWrap" style="margin-bottom:12px;display:{{ old('payment_method','cash')==='cash' ? '' : 'none' }};">
          <label class="cn-label">Receiver Name <span style="color:#EF4444;">*</span></label>
          <input type="text" name="cash_receiver_name" id="cabCashReceiverName"
                 class="form-control cn-input"
                 placeholder="Name of person receiving cash"
                 value="{{ old('cash_receiver_name') }}"
                 {{ old('payment_method','cash')==='cash' ? 'required' : '' }}>
          <div class="cn-hint">Who physically received the cash payment</div>
        </div>

        {{-- Bank Account (hidden when Cash selected) --}}
        <div id="cabBankAccountWrap" style="margin-bottom:4px;display:{{ old('payment_method','cash')==='cash' ? 'none' : '' }};">
          <label class="cn-label">Bank Account <span style="font-size:.65rem;color:#94A3B8;font-weight:400;text-transform:none;">(where advance received)</span></label>
          <select name="payment_account_id" id="cabPaymentAccount" class="form-select cn-input">
            <option value="">— Select account —</option>
            @foreach($paymentAccounts as $acc)
              <option value="{{ $acc->id }}" {{ old('payment_account_id') == $acc->id ? 'selected' : '' }}>
                {{ $acc->account_name }}@if($acc->bank_name) — {{ $acc->bank_name }}@endif
              </option>
            @endforeach
          </select>
          <div class="cn-hint">Required only if advance payment is collected</div>
        </div>
      </div>

      {{-- Booking Status & Notes --}}
      <div class="cn-sidebar-card">
        <div class="cn-scard-title"><i data-feather="sliders"></i> Booking Options</div>

        {{-- B2B Vendor Cost --}}
        <div style="margin-bottom:14px;background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:10px;padding:12px 14px;">
          <label class="cn-label" style="color:#92400E;">
            🏷 B2B Vendor Cost
            <span style="font-size:.6rem;font-weight:600;color:#B45309;text-transform:none;letter-spacing:0;margin-left:4px;">(Internal — not on invoice)</span>
          </label>
          <div class="cn-rupee-wrap">
            <span class="cn-rupee" style="color:#B45309;">₹</span>
            <input type="number" name="vendor_cost" id="vendor_cost"
                   class="form-control cn-input cn-rupee-input"
                   min="0" step="0.01" placeholder="0.00" value="{{ old('vendor_cost') }}"
                   style="border-color:#FDE68A !important;background:#FFFBEB !important;"
                   oninput="recalcFare()">
          </div>
          <div class="cn-hint" style="color:#B45309;">What it costs Visit Kashi to provide this cab.</div>

          {{-- Live Profit Display --}}
          <div id="profit-box" style="display:none;margin-top:10px;border-top:1px dashed #FDE68A;padding-top:10px;">
            <div style="display:flex;justify-content:space-between;font-size:.74rem;color:#92400E;margin-bottom:4px;">
              <span>Total Fare</span>
              <span id="profit-fare" style="font-weight:700;">₹0.00</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.74rem;color:#B45309;margin-bottom:6px;">
              <span>B2B Vendor Cost</span>
              <span id="profit-cost" style="font-weight:700;">− ₹0.00</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;background:#fff;border-radius:8px;padding:7px 10px;border:1px solid #FDE68A;">
              <span style="font-size:.78rem;font-weight:700;color:#065F46;">💰 Profit</span>
              <div style="text-align:right;">
                <div id="profit-amt" style="font-size:1rem;font-weight:900;color:#059669;">₹0.00</div>
                <div id="profit-pct" style="font-size:.65rem;font-weight:700;color:#6B7280;"></div>
              </div>
            </div>
          </div>
        </div>

        <div style="margin-bottom:14px;">
          <label class="cn-label">Booking Status</label>
          <select name="booking_status" class="form-select cn-input">
            @foreach(['confirmed'=>'✅ Confirmed','pending'=>'⏳ Pending','assigned'=>'🔧 Assigned','completed'=>'✔️ Completed','cancelled'=>'❌ Cancelled'] as $v=>$l)
              <option value="{{ $v }}" {{ old('booking_status','confirmed')==$v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
          </select>
          <div class="cn-hint">Default is Confirmed — change if needed</div>
        </div>
        <div>
          <label class="cn-label">Internal Notes</label>
          <textarea name="notes" class="form-control cn-input" rows="3"
                    placeholder="e.g. Guest needs child seat, AC preferred, late night pickup…"
                    style="height:auto !important;">{{ old('notes') }}</textarea>
          <div class="cn-hint">Not shown to guest — staff reference only</div>
        </div>
      </div>

      <button type="submit" class="btn-save" style="font-size:.95rem;letter-spacing:.01em;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="17" height="17"><polyline points="20 6 9 17 4 12"/></svg>
        Create Cab Booking
      </button>
      <p style="text-align:center;font-size:.71rem;color:#94A3B8;margin-top:8px;">
        All fields marked <span style="color:#EF4444;font-weight:700;">*</span> are required
      </p>

    </div>{{-- /sidebar --}}

  </div>{{-- /layout --}}
  </form>

  <template id="leg_template">
    <div class="row g-2 align-items-end leg-row" data-index="INDEX" style="margin-bottom:10px;">
      <div class="col-6 col-md-2">
        <label class="cn-label" style="font-size:.65rem;">Date <span class="cn-req">*</span></label>
        <input type="date" name="legs[INDEX][leg_date]" class="form-control cn-input" required>
      </div>
      <div class="col-6 col-md-3">
        <label class="cn-label" style="font-size:.65rem;">Pickup</label>
        <input type="text" name="legs[INDEX][pickup_address]" class="form-control cn-input" placeholder="Pickup location">
      </div>
      <div class="col-6 col-md-3">
        <label class="cn-label" style="font-size:.65rem;">Drop</label>
        <input type="text" name="legs[INDEX][drop_address]" class="form-control cn-input" placeholder="Drop location">
      </div>
      <div class="col-6 col-md-3">
        <label class="cn-label" style="font-size:.65rem;">Fare</label>
        <div class="cn-rupee-wrap">
          <span class="cn-rupee">₹</span>
          <input type="number" name="legs[INDEX][fare]" class="form-control cn-input cn-rupee-input" min="0" step="0.01" placeholder="0.00">
        </div>
      </div>
      <div class="col-12 col-md-1">
        <button type="button" class="veh-card-del" style="position:static;display:flex;width:28px;height:28px;" onclick="removeLeg(INDEX)" title="Remove">
          <i data-feather="trash-2" style="width:13px;height:13px;"></i>
        </button>
      </div>
    </div>
  </template>

</div>

<script>
// ── Cash / Bank Account toggle ─────────────────────────────────────
function toggleCabPaymentFields() {
    const method       = document.getElementById('cabPaymentMethod').value;
    const isCash       = method === 'cash';
    const receiverWrap = document.getElementById('cabCashReceiverWrap');
    const receiverInput= document.getElementById('cabCashReceiverName');
    const bankWrap     = document.getElementById('cabBankAccountWrap');

    if (isCash) {
        receiverWrap.style.display = '';
        receiverInput.setAttribute('required', 'required');
        bankWrap.style.display = 'none';
    } else {
        receiverWrap.style.display = 'none';
        receiverInput.removeAttribute('required');
        receiverInput.value = '';
        bankWrap.style.display = '';
    }
}

// ── State ─────────────────────────────────────────────────────────
let isCustomVehicle = false;

// ── Init ──────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  feather.replace();
  recalcFare();
  updatePaxSeatCap();

  @if(old('vehicle_id'))
  const prevCard = document.querySelector('.veh-card[data-id="{{ old("vehicle_id") }}"]');
  if (prevCard) { prevCard.classList.add('selected'); updateVehicleSelection(); }
  @endif

  @if(old('legs'))
  @foreach(old('legs') as $leg)
  addLeg();
  (() => {
    const row = document.querySelector(`.leg-row[data-index="${legIndex - 1}"]`);
    row.querySelector('[name$="[leg_date]"]').value       = @json($leg['leg_date'] ?? '');
    row.querySelector('[name$="[pickup_address]"]').value = @json($leg['pickup_address'] ?? '');
    row.querySelector('[name$="[drop_address]"]').value   = @json($leg['drop_address'] ?? '');
    row.querySelector('[name$="[fare]"]').value           = @json($leg['fare'] ?? '');
  })();
  @endforeach
  @endif
});

// ── GST Toggle ────────────────────────────────────────────────────
function toggleGst(show) {
  document.getElementById('gst-input-wrap').style.display = show ? '' : 'none';
  if (!show) { const inp = document.querySelector('[name="gst_number"]'); if (inp) inp.value = ''; }
}

// ── Days Calc ─────────────────────────────────────────────────────
function calcDays() {
  const ci  = document.getElementById('pickup_date').value;
  const co  = document.getElementById('return_date').value;
  const el  = document.getElementById('total_days');
  const badge = document.getElementById('days-auto-badge');
  if (ci && co && co >= ci) {
    const d = Math.max(1, Math.ceil((new Date(co) - new Date(ci)) / 86400000) + 1);
    el.value = d;
    el.readOnly = true;
    el.style.cssText = 'background:#F0FDF4 !important;border-color:#10B981 !important;font-weight:700 !important;color:#059669 !important;';
    if (badge) badge.style.display = 'inline';
  } else {
    el.readOnly = false;
    el.style.cssText = '';
    if (badge) badge.style.display = 'none';
    if (!co && !el.value) el.value = 1;
  }
  recalcFare();
}

// ── Trip Legs (date-wise) ────────────────────────────────────────
let legIndex = 0;

function addLeg() {
  const tpl  = document.getElementById('leg_template').content.cloneNode(true);
  let html = tpl.querySelector('.leg-row').outerHTML.replace(/INDEX/g, legIndex);
  document.getElementById('legs_container').insertAdjacentHTML('beforeend', html);
  legIndex++;
  feather.replace();
}

function removeLeg(index) {
  const row = document.querySelector(`.leg-row[data-index="${index}"]`);
  if (row) row.remove();
}

// ── Vehicle Selection (multi) ─────────────────────────────────────
function selectVehicle(card) {
  // Deselect custom if switching to a preset card
  document.getElementById('veh-card-custom').classList.remove('selected');
  document.getElementById('veh-custom-wrap').style.display = 'none';
  isCustomVehicle = false;

  card.classList.toggle('selected');
  updateVehicleSelection();
}

function selectVehicleCustom() {
  // Deselect all preset cards
  document.querySelectorAll('.veh-card:not(.veh-custom)').forEach(c => c.classList.remove('selected'));
  const customCard = document.getElementById('veh-card-custom');

  if (customCard.classList.contains('selected')) {
    customCard.classList.remove('selected');
    document.getElementById('veh-custom-wrap').style.display = 'none';
    isCustomVehicle = false;
    updateVehicleSelection();
    return;
  }
  customCard.classList.add('selected');
  isCustomVehicle = true;
  document.getElementById('f_vehicle_id').value = '';
  document.getElementById('veh-custom-wrap').style.display = 'block';
  document.getElementById('veh-details-row').style.display = 'none';
}

function removeVehicle(card) {
  if (card.classList.contains('veh-custom')) {
    card.classList.remove('selected');
    document.getElementById('veh-custom-wrap').style.display = 'none';
    isCustomVehicle = false;
  } else {
    card.classList.remove('selected');
  }
  updateVehicleSelection();
}

function updateVehicleSelection() {
  const selected = document.querySelectorAll('.veh-card:not(.veh-custom).selected');
  const count = parseInt(document.getElementById('veh-count-val')?.textContent) || 1;
  document.getElementById('f_vehicle_count').value = count;
  if (selected.length === 0) {
    document.getElementById('f_vehicle_id').value   = '';
    document.getElementById('f_vehicle_name').value = '';
    document.getElementById('f_veh_seats').value    = '';
    document.getElementById('veh-details-row').style.display = 'none';
    updatePaxSeatCap();
    return;
  }
  const ids = [], names = [];
  let baseSeats = 0;
  selected.forEach(c => {
    ids.push(c.dataset.id);
    names.push(c.dataset.name);
    baseSeats += parseInt(c.dataset.seats) || 0;
  });
  const totalSeats  = baseSeats * count;
  const displayName = count > 1 ? names.join(', ') + ' ×' + count : names.join(', ');
  document.getElementById('f_vehicle_id').value    = ids[0];
  document.getElementById('f_vehicle_name').value  = displayName;
  document.getElementById('f_veh_seats').value     = totalSeats;
  document.getElementById('veh_name_display').value  = displayName;
  document.getElementById('veh_seats_display').value = totalSeats;
  document.getElementById('veh-details-row').style.display = '';
  clampPaxToSeats(totalSeats);
  updatePaxSeatCap();
}

function changeVehCount(delta) {
  const el = document.getElementById('veh-count-val');
  el.textContent = Math.max(1, (parseInt(el.textContent) || 1) + delta);
  updateVehicleSelection();
  recalcFare();
}

function onCustomVehName() {
  document.getElementById('f_vehicle_name').value = document.getElementById('custom_veh_name').value.trim();
  const seats = document.getElementById('custom_veh_seats').value;
  document.getElementById('f_veh_seats').value = seats;
  clampPaxToSeats(parseInt(seats) || 0);
  updatePaxSeatCap();
}

// Trim adults/children down (adults first floor 1) if they now exceed the vehicle's seats
function clampPaxToSeats(seats) {
  if (!seats || seats <= 0) return;
  const adultsEl   = document.getElementById('no_of_adults');
  const childrenEl = document.getElementById('no_of_children');
  let adults   = parseInt(adultsEl.value)   || 0;
  let children = parseInt(childrenEl.value) || 0;
  while (adults + children > seats && children > 0) children--;
  while (adults + children > seats && adults > 1) adults--;
  adultsEl.value = adults;
  childrenEl.value = children;
  document.getElementById('no_of_adults_dis').textContent   = adults;
  document.getElementById('no_of_children_dis').textContent = children;
}

// ── Fare Recalc ───────────────────────────────────────────────────
function recalcFare() {
  const flds = ['base_fare','driver_allowance','toll_tax','parking','state_tax','night_charges','extra_km_charges'];
  const vehCount = parseInt(document.getElementById('f_vehicle_count')?.value) || 1;
  let subtotal = flds.reduce((s,f) => s + (parseFloat(document.getElementById(f)?.value) || 0), 0) * vehCount;
  const discount = parseFloat(document.getElementById('discount')?.value)     || 0;
  const advance  = parseFloat(document.getElementById('advance_paid')?.value) || 0;
  const total    = Math.max(0, subtotal - discount);
  const balance  = Math.max(0, total - advance);
  const pct      = total > 0 ? Math.min(100, (advance / total) * 100) : 0;
  const fmt = v => '₹' + v.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});

  document.getElementById('s-subtotal').textContent = fmt(subtotal);
  document.getElementById('s-total').textContent    = fmt(total);
  document.getElementById('s-advance').textContent  = fmt(advance);
  document.getElementById('s-balance').textContent  = fmt(balance);
  document.getElementById('s-bar').style.width      = pct.toFixed(1) + '%';
  document.getElementById('s-pct').textContent      = pct.toFixed(1) + '%';

  const dr = document.getElementById('s-disc-row');
  const dd = document.getElementById('s-discount');
  if (discount > 0) { dd.textContent = '-' + fmt(discount); dr.style.display = ''; }
  else { dr.style.display = 'none'; }

  const days = parseInt(document.getElementById('total_days')?.value) || 1;
  const km   = document.getElementById('total_km')?.value;
  let parts  = [`Total: ${fmt(total)}`];
  if (days > 1) parts.push(`${days} days`);
  if (km)       parts.push(`${km} km`);
  if (discount) parts.push(`Discount: ${fmt(discount)}`);
  if (advance)  parts.push(`Advance: ${fmt(advance)}`);
  parts.push(`Due: ${fmt(balance)}`);
  document.getElementById('fare-preview-text').textContent = parts.join(' · ');

  // ── Profit calculation ──────────────────────────────────────────
  const vendorCost = parseFloat(document.getElementById('vendor_cost')?.value) || 0;
  const profitBox  = document.getElementById('profit-box');
  if (profitBox) {
    if (vendorCost > 0 || total > 0) {
      const profit    = total - vendorCost;
      const profitPct = total > 0 ? ((profit / total) * 100).toFixed(1) : 0;
      profitBox.style.display = '';
      document.getElementById('profit-fare').textContent = fmt(total);
      document.getElementById('profit-cost').textContent = '− ' + fmt(vendorCost);
      const amtEl = document.getElementById('profit-amt');
      const pctEl = document.getElementById('profit-pct');
      amtEl.textContent = fmt(profit);
      amtEl.style.color = profit >= 0 ? '#059669' : '#DC2626';
      pctEl.textContent = (profit >= 0 ? '▲ ' : '▼ ') + Math.abs(profitPct) + '% margin';
      pctEl.style.color = profit >= 0 ? '#6B7280' : '#DC2626';
    } else {
      profitBox.style.display = 'none';
    }
  }
}

// ── Passenger counters ────────────────────────────────────────────
function adjCount(field, delta) {
  const hidden = document.getElementById(field);
  const disp   = document.getElementById(field + '_dis');
  const min    = field === 'no_of_adults' ? 1 : 0;
  let   val    = Math.max(min, (parseInt(hidden.value) || 0) + delta);

  const seats = parseInt(document.getElementById('f_veh_seats')?.value) || 0;
  if (seats > 0 && delta > 0) {
    const other = field === 'no_of_adults'
      ? (parseInt(document.getElementById('no_of_children').value) || 0)
      : (parseInt(document.getElementById('no_of_adults').value) || 0);
    val = Math.min(val, Math.max(min, seats - other));
  }

  hidden.value = val;
  if (disp) disp.textContent = val;
  updatePaxSeatCap();
}

// ── Cap passengers to selected vehicle's seating capacity ─────────
function updatePaxSeatCap() {
  const seats = parseInt(document.getElementById('f_veh_seats')?.value) || 0;
  const hint  = document.getElementById('pax-seat-hint');
  const adultsPlus   = document.getElementById('btn-adults-plus');
  const childrenPlus = document.getElementById('btn-children-plus');
  if (!hint) return;

  if (seats <= 0) {
    hint.textContent = '';
    if (adultsPlus)   adultsPlus.disabled   = false;
    if (childrenPlus) childrenPlus.disabled = false;
    return;
  }

  const adults   = parseInt(document.getElementById('no_of_adults').value)   || 0;
  const children = parseInt(document.getElementById('no_of_children').value) || 0;
  const total    = adults + children;

  hint.textContent = `Selected vehicle(s) seat ${seats} — currently ${total} passenger${total === 1 ? '' : 's'} (adults + children)`;
  hint.style.color = total >= seats ? '#DC2626' : '#94A3B8';

  if (adultsPlus)   adultsPlus.disabled   = total >= seats;
  if (childrenPlus) childrenPlus.disabled = total >= seats;
}

// ── Add-on toggles ────────────────────────────────────────────────
function toggleAddon(field, lbl) {
  const inp = document.getElementById('addon-' + field);
  const active = inp.value === '1';
  inp.value = active ? '0' : '1';
  lbl.style.borderColor  = active ? '#E2E8F0'  : '#4F46E5';
  lbl.style.background   = active ? '#fff'     : '#EEF2FF';
  lbl.style.boxShadow    = active ? 'none'     : '0 0 0 3px rgba(79,70,229,.15)';
  lbl.querySelector('span:nth-child(2)').style.color = active ? '#374151' : '#4F46E5';
}

// Init: highlight AC (default on) on load
document.addEventListener('DOMContentLoaded', () => {
  const acLbl = document.getElementById('addon-lbl-ac_required');
  if (acLbl && document.getElementById('addon-ac_required').value === '1') {
    acLbl.style.borderColor = '#4F46E5';
    acLbl.style.background  = '#EEF2FF';
    acLbl.style.boxShadow   = '0 0 0 3px rgba(79,70,229,.15)';
    acLbl.querySelector('span:nth-child(2)').style.color = '#4F46E5';
  }
  // Init adult counter display
  const adultHidden = document.getElementById('no_of_adults');
  const adultDis    = document.getElementById('no_of_adults_dis');
  if (adultHidden && adultDis) adultDis.textContent = adultHidden.value || '1';
  const kidHidden = document.getElementById('no_of_children');
  const kidDis    = document.getElementById('no_of_children_dis');
  if (kidHidden && kidDis) kidDis.textContent = kidHidden.value || '0';
});

// ── Form submit validation ────────────────────────────────────────
document.getElementById('cabForm').addEventListener('submit', function(e) {
  if (isCustomVehicle) {
    const name = document.getElementById('custom_veh_name').value.trim();
    if (!name) { e.preventDefault(); alert('Please enter a vehicle name.'); return; }
    document.getElementById('f_vehicle_name').value = name;
    document.getElementById('f_veh_seats').value    = document.getElementById('custom_veh_seats').value;
  }
  if (!document.getElementById('f_vehicle_name').value.trim()) {
    e.preventDefault(); alert('Please select at least one vehicle.'); return;
  }
});
</script>

@endsection
