@extends('admin.layouts.app')
@section('content')

<style>
:root {
  --s-bg:#F0FDF4; --s-card:#fff; --s-border:#D1FAE5;
  --s-green:#059669; --s-dkgreen:#047857;
  --s-text:#0F172A; --s-sub:#475569; --s-muted:#94A3B8;
  --s-r:14px; --s-t:.2s ease;
}
.cs-page { background:#EEF2FF; min-height:100vh; padding:20px 24px 40px; }

/* ── Header ───────────────────────────────── */
.cs-header {
  background: linear-gradient(135deg, #059669 0%, #047857 50%, #065F46 100%);
  border-radius:18px; padding:22px 28px; margin-top:50px; margin-bottom:22px;
  display:flex; align-items:center; justify-content:space-between; gap:14px;
  position:relative; overflow:hidden;
}
.cs-header::before { content:''; position:absolute; top:-40px; right:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.07); }
.cs-header-left { position:relative; z-index:1; }
.cs-header h1 { color:#fff; font-size:1.2rem; font-weight:800; margin:0 0 4px; }
.cs-header p  { color:rgba(255,255,255,.7); font-size:.78rem; margin:0; }
.cs-header-actions { position:relative; z-index:1; display:flex; gap:8px; }
.cs-btn-ghost { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.25); color:#fff; border-radius:9px; padding:8px 14px; font-size:.78rem; font-weight:700; text-decoration:none; transition:.18s; }
.cs-btn-ghost:hover { background:rgba(255,255,255,.25); color:#fff; }

/* ── Layout ───────────────────────────────── */
.cs-layout { display:grid; grid-template-columns:1fr 350px; gap:20px; align-items:start; }
@media(max-width:1100px){ .cs-layout { grid-template-columns:1fr; } }

/* ── Card ─────────────────────────────────── */
.cs-card { background:#fff; border-radius:16px; border:1px solid #E2E8F0; box-shadow:0 1px 3px rgba(0,0,0,.05),0 2px 8px rgba(0,0,0,.04); margin-bottom:16px; overflow:hidden; }
.cs-card-head { padding:13px 20px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px; background:#FAFBFF; }
.cs-card-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1rem; }
.cs-card-title { font-size:.87rem; font-weight:700; color:#0F172A; }
.cs-card-body { padding:20px; }

/* ── Form fields ──────────────────────────── */
.cs-label { font-size:.7rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.04em; margin-bottom:5px; display:block; }
.cs-req { color:#EF4444; margin-left:2px; }
.cs-input { border:1.5px solid #E2E8F0 !important; border-radius:10px !important; padding:9px 13px !important; font-size:.875rem !important; color:#0F172A !important; background:#FAFBFF !important; transition:all .2s !important; width:100%; }
.cs-input:focus { border-color:#059669 !important; box-shadow:0 0 0 3px rgba(5,150,105,.12) !important; background:#fff !important; outline:none !important; }
.cs-phone-wrap { position:relative; }
.cs-prefix { position:absolute; left:12px; top:50%; transform:translateY(-50%); font-size:.8rem; font-weight:600; color:#64748B; z-index:2; }
.cs-input-ph { padding-left:40px !important; }

/* ── Hotel cards ──────────────────────────── */
.hotel-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:10px; }
.hotel-card { border:2px solid #E2E8F0; border-radius:13px; padding:14px 10px; cursor:pointer; text-align:center; transition:all .22s; background:#fff; position:relative; user-select:none; }
.hotel-card:hover { border-color:#059669; background:#F0FDF4; transform:translateY(-2px); box-shadow:0 6px 18px rgba(5,150,105,.15); }
.hotel-card.selected { border-color:#059669; background:#F0FDF4; box-shadow:0 0 0 3px rgba(5,150,105,.2); }
.hotel-card-chk { position:absolute; top:7px; right:7px; width:18px; height:18px; background:#059669; border-radius:50%; font-size:.62rem; color:#fff; display:none; align-items:center; justify-content:center; font-weight:800; }
.hotel-card.selected .hotel-card-chk { display:flex; }
.hotel-card-icon { font-size:1.8rem; margin-bottom:6px; line-height:1; }
.hotel-card-name { font-size:.74rem; font-weight:700; color:#0F172A; line-height:1.3; margin-bottom:3px; }
.hotel-card-price { font-size:.9rem; font-weight:800; color:#059669; }
.hotel-card-per { font-size:.63rem; color:#94A3B8; }
.hotel-card-custom { border-style:dashed; border-color:#A5B4FC; }
.hotel-card-custom:hover, .hotel-card-custom.selected { border-color:#6366F1; background:#EEF2FF; }
.hotel-card-custom.selected .hotel-card-chk { background:#6366F1; }

/* ── Counters ─────────────────────────────── */
.counter-wrap { display:flex; align-items:center; justify-content:space-between; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:10px; padding:6px 8px; }
.counter-btn { width:30px; height:30px; border-radius:8px; border:1.5px solid #CBD5E1; background:#fff; font-size:1.1rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:.15s; flex-shrink:0; }
.counter-btn:hover { border-color:#059669; color:#059669; background:#F0FDF4; }
.counter-val { font-size:1.2rem; font-weight:800; min-width:28px; text-align:center; }

/* ── Sidebar ──────────────────────────────── */
.cs-sidebar { position:sticky; top:20px; }
.cs-sidebar-card { background:#fff; border-radius:16px; border:1px solid #E2E8F0; box-shadow:0 1px 3px rgba(0,0,0,.05); padding:20px; margin-bottom:16px; }
.cs-sidebar-head { font-size:.85rem; font-weight:700; color:#0F172A; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid #F1F5F9; }
.cs-rupee-wrap { position:relative; }
.cs-rupee { position:absolute; left:12px; top:50%; transform:translateY(-50%); font-size:.85rem; font-weight:600; color:#475569; pointer-events:none; }
.cs-rupee-input { padding-left:28px !important; }

/* ── Pay summary ──────────────────────────── */
.pay-sum { background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; margin:12px 0; overflow:hidden; }
.pay-sum-head { background:linear-gradient(135deg,#0F172A,#1E293B); padding:9px 14px; font-size:.75rem; font-weight:700; color:#fff; display:flex; align-items:center; gap:8px; }
.pay-sum-body { padding:12px 14px; }
.pay-sum-row { display:flex; justify-content:space-between; font-size:.78rem; padding:5px 0; }
.pay-sum-row + .pay-sum-row { border-top:1px dashed #E8ECF4; }
.pay-sum-lbl { color:#64748B; }
.pay-sum-val { font-weight:700; color:#0F172A; }
.pay-sum-bal { font-weight:800; font-size:.9rem; color:#EF4444; }
.pay-badge { font-size:.62rem; font-weight:700; padding:2px 8px; border-radius:20px; }
.pay-badge.paid    { background:#D1FAE5; color:#065F46; }
.pay-badge.partial { background:#DBEAFE; color:#1E40AF; }
.pay-badge.due     { background:#FEE2E2; color:#DC2626; }

/* ── Submit btn ───────────────────────────── */
.cs-submit { display:flex; align-items:center; justify-content:center; gap:8px; background:linear-gradient(135deg,#059669,#047857); color:#fff; border:none; border-radius:11px; padding:13px 28px; font-size:.9rem; font-weight:800; cursor:pointer; width:100%; margin-top:6px; transition:opacity .2s,transform .2s; }
.cs-submit:hover { opacity:.88; transform:translateY(-1px); }

/* ── Meal plan pills ──────────────────────── */
.meal-pills { display:flex; gap:8px; flex-wrap:wrap; }
.meal-pill { border:2px solid #E2E8F0; border-radius:8px; padding:7px 13px; font-size:.76rem; font-weight:700; cursor:pointer; background:#fff; transition:.18s; color:#475569; }
.meal-pill:hover { border-color:#059669; color:#059669; background:#F0FDF4; }
.meal-pill.active { border-color:#059669; background:#059669; color:#fff; }
.meal-pill input { display:none; }

/* ── Plan preview ─────────────────────────── */
.plan-preview { background:#F0FDF4; border:1.5px solid #BBF7D0; border-radius:10px; padding:12px 16px; margin-top:14px; }
.plan-preview-lbl { font-size:.63rem; font-weight:800; color:#065F46; text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
.plan-preview-text { font-size:.8rem; color:#14532D; font-weight:600; line-height:1.6; }
</style>

<div class="cs-page">

  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show mb-3">
    <strong>Please fix:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show mb-3">
    {{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Header --}}
  <div class="cs-header">
    <div class="cs-header-left">
      <h1>🏨 New Stay / Hotel Booking</h1>
      <p>Hotels · Homestays · Heritage Accommodations · Guesthouses</p>
    </div>
    <div class="cs-header-actions">
      <a href="{{ route('bookings.create-direct') }}" class="cs-btn-ghost">← Change Type</a>
      <a href="{{ route('bookings.index') }}" class="cs-btn-ghost">All Bookings</a>
    </div>
  </div>

  <form action="{{ route('bookings.store-direct') }}" method="POST" id="stayForm">
    @csrf
    <input type="hidden" name="short_plan" id="shortPlanHidden">
    <input type="hidden" name="booking_date" id="bookingDateHidden" value="{{ now()->format('Y-m-d') }}">

    <div class="cs-layout">

      {{-- ══════════ LEFT ══════════ --}}
      <div>

        {{-- 1. Guest Details --}}
        <div class="cs-card">
          <div class="cs-card-head">
            <div class="cs-card-icon" style="background:#D1FAE5;">👤</div>
            <div class="cs-card-title">Guest Details</div>
          </div>
          <div class="cs-card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="cs-label">Guest Name <span class="cs-req">*</span></label>
                <input type="text" name="guest_name" class="form-control cs-input" required
                       placeholder="Full name" value="{{ old('guest_name') }}" oninput="buildPlan()">
              </div>
              <div class="col-md-4">
                <label class="cs-label">Mobile <span class="cs-req">*</span></label>
                @include('admin.partials.phone-input', [
                    'name'     => 'phone',
                    'value'    => old('phone'),
                    'required' => true,
                ])
              </div>
              <div class="col-md-4">
                <label class="cs-label">Alt. Mobile</label>
                @include('admin.partials.phone-input', [
                    'name'     => 'alt_phone',
                    'codeName' => 'alt_phone_country_code',
                    'value'    => old('alt_phone'),
                    'required' => false,
                    'placeholder' => 'Optional',
                ])
              </div>
              <div class="col-md-4">
                <label class="cs-label">Email</label>
                <input type="email" name="email" class="form-control cs-input"
                       placeholder="guest@email.com" value="{{ old('email') }}">
              </div>
              <div class="col-md-3">
                <label class="cs-label">Country</label>
                <select name="country" class="form-select cs-input">
                  @foreach(['India'=>'🇮🇳 India','USA'=>'🇺🇸 USA','UK'=>'🇬🇧 UK','UAE'=>'🇦🇪 UAE','Australia'=>'🇦🇺 Australia','Canada'=>'🇨🇦 Canada','Singapore'=>'🇸🇬 Singapore','Other'=>'🌍 Other'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('country','India')==$v ? 'selected':'' }}>{{ $l }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="cs-label">Lead Source <span class="cs-req">*</span></label>
                <select name="lead_source_id" class="form-select cs-input" required>
                  <option value="">Select source…</option>
                  @foreach($leadSources as $src)
                    <option value="{{ $src->id }}" {{ old('lead_source_id')==$src->id?'selected':'' }}>{{ $src->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        {{-- 2. Property / Hotel --}}
        <div class="cs-card">
          <div class="cs-card-head">
            <div class="cs-card-icon" style="background:#D1FAE5;">🏨</div>
            <div class="cs-card-title">Property / Hotel <span style="font-size:.7rem;color:#EF4444;font-weight:400;">* required</span></div>
          </div>
          <div class="cs-card-body">
            <div class="hotel-grid" id="hotelGrid">
              @forelse($stayTemplates as $t)
              <div class="hotel-card" data-id="{{ $t->id }}" data-name="{{ $t->name }}"
                   data-price="{{ (float)$t->default_selling_price }}" onclick="selectHotel(this)">
                <div class="hotel-card-chk">✓</div>
                <div class="hotel-card-icon">🏨</div>
                <div class="hotel-card-name">{{ $t->name }}</div>
                <div class="hotel-card-price">₹{{ number_format((float)$t->default_selling_price,0) }}</div>
                <div class="hotel-card-per">/night</div>
              </div>
              @empty
              @endforelse

              {{-- Custom hotel card --}}
              <div class="hotel-card hotel-card-custom selected" id="hotelCustomCard" onclick="selectHotelCustom()">
                <div class="hotel-card-chk">✓</div>
                <div class="hotel-card-icon">✏️</div>
                <div class="hotel-card-name" style="color:#6366F1;">Not listed?</div>
                <div class="hotel-card-price" style="color:#94A3B8;font-size:.7rem;">Add custom</div>
              </div>
            </div>

            {{-- Custom property form --}}
            <div id="customHotelWrap" style="margin-top:14px;background:#F5F3FF;border:1.5px solid #C4B5FD;border-radius:14px;padding:16px 18px;">
              <div style="font-size:.7rem;font-weight:800;color:#7C3AED;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">✏️ Custom Property</div>
              <div class="row g-3 align-items-end">
                <div class="col-md-5">
                  <label class="cs-label" style="color:#6D28D9;">Property Name <span class="cs-req">*</span></label>
                  <input type="text" id="customHotelName" class="form-control cs-input"
                         placeholder="e.g. Kashi Heritage Hotel"
                         style="border-color:#C4B5FD !important;" oninput="buildPlan()">
                </div>
                <div class="col-md-3">
                  <label class="cs-label" style="color:#6D28D9;">Room Type</label>
                  <select id="customRoomType" class="form-select cs-input" style="border-color:#C4B5FD !important;" onchange="onCustomRoomChange()">
                    <option value="">Select…</option>
                    @foreach(['Deluxe Room'=>2500,'Executive Room'=>3500,'Premium Room'=>5000,'Suite'=>8000,'Homestay'=>1500] as $rt=>$rp)
                      <option value="{{ $rt }}" data-price="{{ $rp }}">{{ $rt }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="cs-label" style="color:#6D28D9;">Rate / Night (₹) <span class="cs-req">*</span></label>
                  <input type="number" id="customHotelRate" class="form-control cs-input"
                         placeholder="0" min="0" step="1" style="border-color:#C4B5FD !important;"
                         oninput="recalcTotal()">
                </div>
              </div>
            </div>

            <input type="hidden" name="services[0][service_template_id]" id="hotelTplId">
          </div>
        </div>

        {{-- 3. Stay Details --}}
        <div class="cs-card">
          <div class="cs-card-head">
            <div class="cs-card-icon" style="background:#FEF3C7;">📅</div>
            <div class="cs-card-title">Stay Details</div>
          </div>
          <div class="cs-card-body">
            <div class="row g-3">
              <div class="col-6 col-md-3">
                <label class="cs-label">Check-in <span class="cs-req">*</span></label>
                <input type="date" name="booking_start_date" id="checkin" class="form-control cs-input" required
                       value="{{ old('booking_start_date') }}" onchange="calcNights();buildPlan()">
                <input type="time" id="checkinTime" class="form-control cs-input mt-1"
                       value="14:00" onchange="buildPlan()"
                       style="font-size:.78rem !important;color:#0891b2 !important;font-weight:600 !important;">
              </div>
              <div class="col-6 col-md-3">
                <label class="cs-label">Check-out <span class="cs-req">*</span></label>
                <input type="date" name="booking_end_date" id="checkout" class="form-control cs-input" required
                       value="{{ old('booking_end_date') }}" onchange="calcNights();buildPlan()">
                <input type="time" id="checkoutTime" class="form-control cs-input mt-1"
                       value="11:00" onchange="buildPlan()"
                       style="font-size:.78rem !important;color:#dc2626 !important;font-weight:600 !important;">
              </div>
              <div class="col-4 col-md-2">
                <label class="cs-label">Nights</label>
                <input type="text" id="nightsDisplay" class="form-control cs-input" readonly
                       placeholder="Auto" style="background:#F1F5F9 !important;text-align:center;font-weight:700;">
              </div>
              <div class="col-4 col-md-2">
                <label class="cs-label">Adults</label>
                <div class="counter-wrap">
                  <button type="button" class="counter-btn" onclick="adj('adults',-1)">−</button>
                  <span class="counter-val" id="adultsDis">1</span>
                  <button type="button" class="counter-btn" onclick="adj('adults',1)">+</button>
                </div>
                <input type="hidden" id="adultsVal" value="1">
              </div>
              <div class="col-4 col-md-2">
                <label class="cs-label">Children</label>
                <div class="counter-wrap">
                  <button type="button" class="counter-btn" onclick="adj('kids',-1)">−</button>
                  <span class="counter-val" id="kidsDis">0</span>
                  <button type="button" class="counter-btn" onclick="adj('kids',1)">+</button>
                </div>
                <input type="hidden" id="kidsVal" value="0">
              </div>
              <div class="col-md-3">
                <label class="cs-label">No. of Rooms <span class="cs-req">*</span></label>
                <input type="number" id="rooms" class="form-control cs-input" min="1" value="1" required
                       oninput="recalcTotal();buildPlan()">
              </div>
              <div class="col-md-3">
                <label class="cs-label">Room Type</label>
                <select id="roomType" class="form-select cs-input" onchange="buildPlan()">
                  <option value="">Select…</option>
                  @foreach(['Deluxe Room','Executive Room','Premium Room','Suite','Homestay Flat'] as $rt)
                    <option>{{ $rt }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="cs-label">Extra Bed (₹)</label>
                <div class="cs-rupee-wrap">
                  <span class="cs-rupee" style="color:#7C3AED;">₹</span>
                  <input type="number" id="extraBed" class="form-control cs-input cs-rupee-input"
                         placeholder="0" min="0" oninput="recalcTotal();buildPlan()">
                </div>
              </div>
              <div class="col-md-3">
                <label class="cs-label">Special Requests</label>
                <input type="text" id="specialReq" class="form-control cs-input"
                       placeholder="e.g. Ganga view, early check-in" oninput="buildPlan()">
              </div>
            </div>

            {{-- Meal plan pills --}}
            <div style="margin-top:16px;">
              <label class="cs-label">Meal Plan</label>
              <div class="meal-pills">
                @foreach(['EP'=>'EP – Room Only','CP'=>'CP – Breakfast','MAP'=>'MAP – B+D','AP'=>'AP – All Meals'] as $mv=>$ml)
                <label class="meal-pill" onclick="selectMeal('{{ $mv }}', this)">
                  <input type="radio" name="_meal_ui" value="{{ $mv }}">{{ $ml }}
                </label>
                @endforeach
              </div>
              <input type="hidden" id="mealVal" value="">
            </div>

            {{-- Plan preview --}}
            <div class="plan-preview" style="margin-top:14px;">
              <div class="plan-preview-lbl">Voucher Summary Preview</div>
              <div class="plan-preview-text" id="planPreviewText">—</div>
            </div>
          </div>
        </div>

        {{-- 4. Additional Notes --}}
        <div class="cs-card">
          <div class="cs-card-head">
            <div class="cs-card-icon" style="background:#EEF2FF;">📝</div>
            <div class="cs-card-title">Additional Notes</div>
          </div>
          <div class="cs-card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="cs-label">Internal Notes (Staff Only)</label>
                <textarea name="internal_notes" class="form-control cs-input" rows="3"
                          placeholder="Notes visible to staff only…">{{ old('internal_notes') }}</textarea>
              </div>
              <div class="col-md-6">
                <label class="cs-label">Tour Plan / Itinerary</label>
                <textarea name="tour_plan" class="form-control cs-input" rows="3"
                          placeholder="Day 1: Arrival…">{{ old('tour_plan') }}</textarea>
              </div>
            </div>
          </div>
        </div>

      </div>{{-- /left --}}

      {{-- ══════════ SIDEBAR ══════════ --}}
      <div class="cs-sidebar">

        {{-- Pricing --}}
        <div class="cs-sidebar-card">
          <div class="cs-sidebar-head">💰 Booking Amount</div>

          <div style="margin-bottom:12px;">
            <label class="cs-label">Total Amount (₹) <span class="cs-req">*</span>
              <span id="autoTag" style="display:none;font-size:.6rem;font-weight:700;color:#059669;background:#D1FAE5;padding:2px 7px;border-radius:20px;margin-left:6px;">AUTO</span>
            </label>
            <div class="cs-rupee-wrap">
              <span class="cs-rupee">₹</span>
              <input type="number" name="services[0][unit_price]" id="totalAmt"
                     class="form-control cs-input cs-rupee-input"
                     placeholder="Enter total" min="0" step="0.01" required
                     oninput="recalcBalance()">
            </div>
            <input type="hidden" name="services[0][quantity]" value="1">
            <input type="hidden" name="services[0][service_date]" id="svcDate">
            <div id="perNightNote" style="font-size:.68rem;color:#6B7280;margin-top:4px;display:none;font-weight:600;"></div>
          </div>

          <div style="margin-bottom:12px;">
            <label class="cs-label">Discount (₹)</label>
            <div class="cs-rupee-wrap">
              <span class="cs-rupee">₹</span>
              <input type="number" name="discount" id="discountAmt"
                     class="form-control cs-input cs-rupee-input"
                     value="0" min="0" oninput="recalcBalance()">
            </div>
          </div>

          <div style="margin-bottom:12px;">
            <label class="cs-label">Advance Paid (₹)</label>
            <div class="cs-rupee-wrap">
              <span class="cs-rupee">₹</span>
              <input type="number" name="advance_paid" id="advancePaid"
                     class="form-control cs-input cs-rupee-input"
                     value="0" min="0" oninput="recalcBalance()">
            </div>
          </div>

          <div style="margin-bottom:12px;">
            <label class="cs-label">Payment Method</label>
            <select name="payment_method" class="form-select cs-input">
              <option value="">Select…</option>
              @foreach(['cash'=>'Cash','upi'=>'UPI','bank_transfer'=>'Bank Transfer','card'=>'Card','cheque'=>'Cheque'] as $v=>$l)
              <option value="{{ $v }}">{{ $l }}</option>
              @endforeach
            </select>
          </div>

          <div style="margin-bottom:12px;">
            <label class="cs-label">Bank Account</label>
            <select name="payment_account_id" class="form-select cs-input">
              <option value="">Select account…</option>
              @foreach($paymentAccounts as $acc)
              <option value="{{ $acc->id }}" {{ old('payment_account_id') == $acc->id ? 'selected' : '' }}>
                {{ $acc->account_name }}@if($acc->bank_name) — {{ $acc->bank_name }}@endif
              </option>
              @endforeach
            </select>
          </div>

          {{-- Pay Summary --}}
          <div class="pay-sum">
            <div class="pay-sum-head">
              📊 Payment Summary
              <span id="payBadge" class="pay-badge due" style="margin-left:auto;">DUE</span>
            </div>
            <div class="pay-sum-body">
              <div class="pay-sum-row">
                <span class="pay-sum-lbl">Total Amount</span>
                <span class="pay-sum-val" id="sumTotal">₹0.00</span>
              </div>
              <div class="pay-sum-row" id="discRow" style="display:none;">
                <span class="pay-sum-lbl">Discount</span>
                <span class="pay-sum-val" id="sumDisc" style="color:#F59E0B;">-₹0.00</span>
              </div>
              <div class="pay-sum-row">
                <span class="pay-sum-lbl">Advance Paid</span>
                <span class="pay-sum-val" style="color:#059669;" id="sumPaid">₹0.00</span>
              </div>
              <div class="pay-sum-row">
                <span class="pay-sum-lbl" style="font-weight:700;color:#0F172A;">Balance Due</span>
                <span class="pay-sum-bal" id="sumBal">₹0.00</span>
              </div>
              <div style="margin-top:8px;padding-top:8px;border-top:1px solid #F1F5F9;">
                <div style="display:flex;justify-content:space-between;font-size:.68rem;color:#94A3B8;margin-bottom:3px;">
                  <span>Payment Progress</span>
                  <span id="payPct" style="color:#059669;font-weight:700;">0%</span>
                </div>
                <div style="height:5px;background:#E2E8F0;border-radius:99px;overflow:hidden;">
                  <div id="payBar" style="height:100%;width:0%;background:linear-gradient(90deg,#059669,#047857);border-radius:99px;transition:width .4s;"></div>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="cs-submit">
            ✓ Confirm Stay Booking
          </button>
        </div>

      </div>{{-- /sidebar --}}

    </div>{{-- /layout --}}
  </form>

</div>{{-- /cs-page --}}

<script>
// ── State ─────────────────────────────────────────
let selectedHotelPrice = 0;
let selectedHotelName  = '';
let isCustomHotel      = true;

// ── Hotel selection ───────────────────────────────
function selectHotel(card) {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  selectedHotelPrice = parseFloat(card.dataset.price) || 0;
  selectedHotelName  = card.dataset.name || '';
  isCustomHotel      = false;
  document.getElementById('hotelTplId').value = card.dataset.id;
  document.getElementById('customHotelWrap').style.display = 'none';
  recalcTotal();
  buildPlan();
}
function selectHotelCustom() {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('hotelCustomCard').classList.add('selected');
  selectedHotelPrice = parseFloat(document.getElementById('customHotelRate').value) || 0;
  isCustomHotel      = true;
  document.getElementById('hotelTplId').value = '';
  document.getElementById('customHotelWrap').style.display = 'block';
}
function onCustomRoomChange() {
  const sel = document.getElementById('customRoomType');
  const price = parseFloat(sel.options[sel.selectedIndex]?.dataset.price) || 0;
  if (price > 0) document.getElementById('customHotelRate').value = price;
  recalcTotal();
}

// ── Nights ────────────────────────────────────────
function calcNights() {
  const ci = document.getElementById('checkin').value;
  const co = document.getElementById('checkout').value;
  const ni = document.getElementById('nightsDisplay');
  const sd = document.getElementById('svcDate');
  if (ci && co) {
    const d = Math.round((new Date(co) - new Date(ci)) / 86400000);
    ni.value = d > 0 ? d : '';
    if (sd && ci) sd.value = ci;
  } else { ni.value = ''; }
  recalcTotal();
}

// ── Counters ──────────────────────────────────────
function adj(type, delta) {
  const h = document.getElementById(type === 'adults' ? 'adultsVal' : 'kidsVal');
  const d = document.getElementById(type === 'adults' ? 'adultsDis' : 'kidsDis');
  const min = type === 'adults' ? 1 : 0;
  const v = Math.max(min, (parseInt(h.value) || 0) + delta);
  h.value = v; d.textContent = v;
  buildPlan();
}

// ── Meal plan ─────────────────────────────────────
function selectMeal(val, el) {
  document.querySelectorAll('.meal-pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('mealVal').value = val;
  buildPlan();
}

// ── Recalc total (rate × rooms × nights + extra bed) ──
function recalcTotal() {
  const price = isCustomHotel
    ? parseFloat(document.getElementById('customHotelRate').value) || 0
    : selectedHotelPrice;
  if (price <= 0) { updatePerNightNote(0, 0, 0); return; }

  const nights = parseInt(document.getElementById('nightsDisplay').value) || 0;
  const rooms  = parseInt(document.getElementById('rooms').value) || 1;
  const extra  = parseFloat(document.getElementById('extraBed').value) || 0;
  const total  = price * rooms * nights + extra * nights;

  const tf = document.getElementById('totalAmt');
  if (tf && total > 0) { tf.value = total.toFixed(2); }
  document.getElementById('autoTag').style.display = total > 0 ? 'inline-flex' : 'none';
  updatePerNightNote(price, nights, rooms);
  recalcBalance();
}

function updatePerNightNote(rate, nights, rooms) {
  const note = document.getElementById('perNightNote');
  if (!note) return;
  if (rate > 0 && nights > 0) {
    note.textContent = '₹' + rate.toLocaleString('en-IN') + '/room × ' + nights + 'N × ' + rooms + 'R';
    note.style.display = 'block';
  } else { note.style.display = 'none'; }
}

// ── Balance ───────────────────────────────────────
function recalcBalance() {
  const total    = parseFloat(document.getElementById('totalAmt').value) || 0;
  const discount = parseFloat(document.getElementById('discountAmt').value) || 0;
  const paid     = parseFloat(document.getElementById('advancePaid').value) || 0;
  const net      = Math.max(0, total - discount);
  const bal      = Math.max(0, net - paid);
  const pct      = net > 0 ? Math.min(100, paid / net * 100) : 0;

  const fmt = v => '₹' + v.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
  document.getElementById('sumTotal').textContent = fmt(total);
  document.getElementById('sumPaid').textContent  = fmt(paid);
  document.getElementById('sumBal').textContent   = fmt(bal);
  document.getElementById('payPct').textContent   = pct.toFixed(0) + '%';
  document.getElementById('payBar').style.width   = pct.toFixed(0) + '%';

  const discRow = document.getElementById('discRow');
  if (discount > 0) {
    document.getElementById('sumDisc').textContent = '-' + fmt(discount);
    discRow.style.display = '';
  } else { discRow.style.display = 'none'; }

  const badge = document.getElementById('payBadge');
  badge.className = 'pay-badge ' + (bal <= 0 ? 'paid' : (paid > 0 ? 'partial' : 'due'));
  badge.textContent = bal <= 0 ? 'PAID' : (paid > 0 ? 'PARTIAL' : 'DUE');
}

// ── Build voucher short_plan ──────────────────────
function fmtDate(d) {
  if (!d) return '';
  return new Date(d + 'T00:00:00').toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'});
}
function fmtTime(t) {
  if (!t) return '';
  const [h, m] = t.split(':').map(Number);
  return ((h % 12) || 12) + ':' + String(m).padStart(2, '0') + (h >= 12 ? ' PM' : ' AM');
}
function buildPlan() {
  const ci    = document.getElementById('checkin').value;
  const co    = document.getElementById('checkout').value;
  const cit   = document.getElementById('checkinTime').value;
  const cot   = document.getElementById('checkoutTime').value;
  const ni    = document.getElementById('nightsDisplay').value;
  const rooms = document.getElementById('rooms').value;
  const rtype = document.getElementById('roomType').value;
  const adults= document.getElementById('adultsVal').value;
  const kids  = document.getElementById('kidsVal').value;
  const meal  = document.getElementById('mealVal').value;
  const extra = document.getElementById('extraBed').value;
  const req   = document.getElementById('specialReq').value.trim();
  const prop  = isCustomHotel
    ? (document.getElementById('customHotelName').value.trim() || '')
    : selectedHotelName;

  const parts = [];
  if (prop) parts.push('Property: ' + prop);
  if (ci && co) parts.push('Check-in: ' + fmtDate(ci) + ' ' + fmtTime(cit) + '  →  Check-out: ' + fmtDate(co) + ' ' + fmtTime(cot));
  if (ni > 0) parts.push('Duration: ' + ni + ' night(s)');
  parts.push('Rooms: ' + rooms + (rtype ? ' (' + rtype + ')' : ''));
  parts.push('Guests: ' + adults + ' Adult' + (adults != 1 ? 's' : '') + (kids > 0 ? ', ' + kids + ' Child' + (kids != 1 ? 'ren' : '') : ''));
  if (meal) parts.push('Meal Plan: ' + meal);
  if (extra > 0) parts.push('Extra Bed: ₹' + Number(extra).toLocaleString('en-IN'));
  if (req) parts.push('Requests: ' + req);

  const plan = parts.join(' | ');
  document.getElementById('shortPlanHidden').value = plan;
  document.getElementById('planPreviewText').textContent = plan || '—';
}

// ── Pre-submit ────────────────────────────────────
document.getElementById('stayForm').addEventListener('submit', function() {
  buildPlan();
  document.getElementById('bookingDateHidden').value = new Date().toISOString().slice(0, 10);
});

// Init
document.addEventListener('DOMContentLoaded', () => { recalcBalance(); });
</script>

@endsection
