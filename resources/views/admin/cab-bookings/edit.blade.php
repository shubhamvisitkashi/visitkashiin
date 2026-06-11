@extends('admin.layouts.app')
@section('content')

@php
  $paidTotal = $booking->payments->sum('amount');
@endphp

<style>
/* Re-use create page styles with edit-specific tweaks */
:root{
  --n-bg:#F0F4FF;--n-border:#E2E8F0;--n-indigo:#4F46E5;--n-r:14px;--n-t:.2s ease;
}
.cn-page{background:var(--n-bg);min-height:100vh;padding:24px;}
.cn-header{background:linear-gradient(135deg,#0891b2 0%,#0f766e 100%);border-radius:var(--n-r);padding:20px 26px;display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;overflow:hidden;position:relative;}
.cn-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.cn-header h1{color:#fff;font-size:1.2rem;font-weight:800;margin:0;position:relative;z-index:1;}
.cn-header p{color:rgba(255,255,255,.75);font-size:.78rem;margin:.2rem 0 0;position:relative;z-index:1;}
.cn-back{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:8px 14px;font-size:.8rem;font-weight:600;text-decoration:none;transition:background var(--n-t);position:relative;z-index:1;}
.cn-back:hover{background:rgba(255,255,255,.28);color:#fff;}
.cn-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:1100px){.cn-layout{grid-template-columns:1fr;}}
.cn-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05);margin-bottom:16px;overflow:hidden;}
.cn-card-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:12px;background:#FAFBFF;}
.cn-card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.cn-card-title{font-size:.87rem;font-weight:700;color:#0F172A;}
.cn-card-body{padding:20px;}
.cn-label{font-size:.72rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px;display:block;}
.cn-req{color:#EF4444;margin-left:2px;}
.cn-input{border:1.5px solid #E2E8F0 !important;border-radius:10px !important;padding:9px 13px !important;font-size:.875rem !important;color:#0F172A !important;background:#FAFBFF !important;transition:all .2s ease !important;width:100%;}
.cn-input:focus{border-color:#0891b2 !important;box-shadow:0 0 0 3px rgba(8,145,178,.12) !important;background:#fff !important;outline:none !important;}
.cn-phone-wrap{position:relative;}
.cn-phone-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;font-weight:600;color:#64748B;z-index:2;pointer-events:none;}
.cn-input-phone{padding-left:40px !important;}
.trip-tabs{display:flex;gap:8px;flex-wrap:wrap;}
.trip-tab{border:2px solid #E2E8F0;border-radius:10px;padding:8px 14px;cursor:pointer;font-size:.78rem;font-weight:700;color:#64748B;background:#fff;transition:all .18s;user-select:none;display:flex;align-items:center;gap:6px;}
.trip-tab:hover,.trip-tab.active{border-color:#0891b2;color:#fff;background:#0891b2;}
.trip-tab input{display:none;}
.veh-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;margin-bottom:12px;}
.veh-card{border:2px solid #E8ECF4;border-radius:14px;padding:12px 10px;cursor:pointer;text-align:center;transition:all .22s ease;background:#fff;position:relative;user-select:none;}
.veh-card:hover{border-color:#0891b2;background:#F0F9FF;transform:translateY(-2px);}
.veh-card.selected{border-color:#0891b2;background:#F0F9FF;box-shadow:0 0 0 3px rgba(8,145,178,.2);}
.veh-card-check{position:absolute;top:7px;right:7px;width:18px;height:18px;background:#0891b2;border-radius:50%;font-size:.62rem;color:#fff;display:none;align-items:center;justify-content:center;font-weight:800;}
.veh-card.selected .veh-card-check{display:flex;}
.veh-emoji{font-size:1.7rem;margin-bottom:5px;line-height:1;}
.veh-name{font-size:.72rem;font-weight:700;color:#0F172A;}
.veh-seats{font-size:.65rem;color:#94A3B8;}
.price-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:12px;}
.cn-rupee-wrap{position:relative;}
.cn-rupee{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.82rem;font-weight:600;color:#475569;z-index:2;pointer-events:none;}
.cn-rupee-input{padding-left:26px !important;}
.cn-sidebar{position:sticky;top:20px;}
.cn-sidebar-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05);padding:20px;margin-bottom:16px;}
.cn-sidebar-head{display:flex;align-items:center;gap:10px;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #F1F5F9;}
.fare-row{display:flex;justify-content:space-between;padding:5px 0;font-size:.8rem;border-bottom:1px dashed #F1F5F9;}
.fare-row:last-child{border-bottom:none;}
.fare-lbl{color:#64748B;}
.fare-val{font-weight:700;color:#0F172A;}
.fare-total{font-weight:800;font-size:.95rem;color:#0891b2;padding:10px 0;border-top:2px solid #E8ECF4;margin-top:6px;}
.fare-balance{font-weight:800;color:#EF4444;}
.btn-save{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#0891b2,#0f766e);color:#fff;border:none;border-radius:10px;padding:12px 28px;font-size:.92rem;font-weight:700;cursor:pointer;transition:opacity .2s,transform .2s;width:100%;justify-content:center;margin-top:6px;}
.btn-save:hover{opacity:.88;transform:translateY(-1px);color:#fff;}
.pmt-item{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid #F1F5F9;font-size:.76rem;}
.pmt-item:last-child{border-bottom:none;}
.pmt-amt{font-weight:700;color:#059669;}
@media(max-width:768px){.cn-page{padding:10px;}.cn-card-body{padding:14px;}}
</style>

<div class="cn-page">

  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show mb-3">
    <strong>Please fix:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="cn-header">
    <div>
      <h1>✏️ Edit — {{ $booking->booking_number }}</h1>
      <p>{{ $booking->customer_name }} &nbsp;·&nbsp; {{ $booking->trip_type_label }}</p>
    </div>
    <div style="display:flex;gap:8px;position:relative;z-index:1;">
      <a href="{{ route('cab-bookings.show', $booking->id) }}" class="cn-back">
        <i data-feather="eye" style="width:13px;height:13px;stroke:#fff;"></i> View
      </a>
      <a href="{{ route('cab-bookings.index') }}" class="cn-back">
        <i data-feather="arrow-left" style="width:13px;height:13px;stroke:#fff;"></i> Back
      </a>
    </div>
  </div>

  <form action="{{ route('cab-bookings.update', $booking->id) }}" method="POST" id="editCabForm">
    @csrf @method('PUT')

  <div class="cn-layout">
  <div>

    {{-- Customer --}}
    <div class="cn-card">
      <div class="cn-card-head">
        <div class="cn-card-icon" style="background:#EEF2FF;color:#4F46E5;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="cn-card-title">Customer Details</div>
      </div>
      <div class="cn-card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="cn-label">Full Name <span class="cn-req">*</span></label>
            <input type="text" name="customer_name" class="form-control cn-input" required value="{{ old('customer_name', $booking->customer_name) }}">
          </div>
          <div class="col-md-4">
            <label class="cn-label">Mobile <span class="cn-req">*</span></label>
            <div class="cn-phone-wrap">
              <span class="cn-phone-prefix">+91</span>
              <input type="tel" name="customer_phone" class="form-control cn-input cn-input-phone" required maxlength="15" value="{{ old('customer_phone', $booking->customer_phone) }}">
            </div>
          </div>
          <div class="col-md-4">
            <label class="cn-label">Alt. Mobile</label>
            <div class="cn-phone-wrap">
              <span class="cn-phone-prefix">+91</span>
              <input type="tel" name="customer_alt_phone" class="form-control cn-input cn-input-phone" maxlength="15" value="{{ old('customer_alt_phone', $booking->customer_alt_phone) }}">
            </div>
          </div>
          <div class="col-md-4">
            <label class="cn-label">Email</label>
            <input type="email" name="customer_email" class="form-control cn-input" value="{{ old('customer_email', $booking->customer_email) }}">
          </div>
          <div class="col-md-4">
            <label class="cn-label">GST Number</label>
            <input type="text" name="gst_number" class="form-control cn-input" value="{{ old('gst_number', $booking->gst_number) }}" maxlength="15">
          </div>
          <div class="col-md-4">
            <label class="cn-label">Lead Source</label>
            <select name="lead_source_id" class="form-select cn-input">
              <option value="">— Select —</option>
              @foreach($leadSources as $ls)
                <option value="{{ $ls->id }}" {{ old('lead_source_id', $booking->lead_source_id) == $ls->id ? 'selected' : '' }}>{{ $ls->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="cn-label">Pickup Address <span class="cn-req">*</span></label>
            <input type="text" name="pickup_address" class="form-control cn-input" required value="{{ old('pickup_address', $booking->pickup_address) }}">
          </div>
          <div class="col-md-6">
            <label class="cn-label">Drop Address <span class="cn-req">*</span></label>
            <input type="text" name="drop_address" class="form-control cn-input" required value="{{ old('drop_address', $booking->drop_address) }}">
          </div>
        </div>
      </div>
    </div>

    {{-- Journey --}}
    <div class="cn-card">
      <div class="cn-card-head">
        <div class="cn-card-icon" style="background:#FEF3C7;color:#D97706;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="cn-card-title">Journey Details</div>
      </div>
      <div class="cn-card-body">
        <div style="margin-bottom:16px;">
          <label class="cn-label">Booking For <span class="cn-req">*</span></label>
          <input type="text" name="trip_type" list="trip-suggestions" class="form-control cn-input" required
                 autocomplete="off" placeholder="e.g. Varanasi Local, Varanasi to Ayodhya…"
                 value="{{ old('trip_type', $booking->trip_type) }}">
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
        </div>
        <div class="row g-3">
          <div class="col-6 col-md-3">
            <label class="cn-label">Pickup Date <span class="cn-req">*</span></label>
            <input type="date" name="pickup_date" id="pickup_date" class="form-control cn-input" required
                   value="{{ old('pickup_date', $booking->pickup_date->format('Y-m-d')) }}" onchange="calcDays()">
          </div>
          <div class="col-6 col-md-3">
            <label class="cn-label">Pickup Time <span class="cn-req">*</span></label>
            <input type="time" name="pickup_time" class="form-control cn-input" required
                   value="{{ old('pickup_time', \Carbon\Carbon::parse($booking->pickup_time)->format('H:i')) }}">
          </div>
          <div class="col-6 col-md-3" id="return-date-wrap">
            <label class="cn-label">Return Date</label>
            <input type="date" name="return_date" id="return_date" class="form-control cn-input"
                   value="{{ old('return_date', optional($booking->return_date)?->format('Y-m-d')) }}" onchange="calcDays()">
          </div>
          <div class="col-6 col-md-3">
            <label class="cn-label">Total Days <span class="cn-req">*</span> <span id="days-auto-badge" style="display:none;font-size:.6rem;background:#D1FAE5;color:#059669;padding:1px 6px;border-radius:6px;font-weight:700;text-transform:none;letter-spacing:0;">Auto</span></label>
            <input type="number" name="total_days" id="total_days" class="form-control cn-input" min="1" required
                   value="{{ old('total_days', $booking->total_days) }}" oninput="recalcFare()">
          </div>
          <div class="col-6 col-md-3">
            <label class="cn-label">Total KM</label>
            <input type="number" name="total_km" class="form-control cn-input" min="0" step="0.1"
                   value="{{ old('total_km', $booking->total_km) }}">
          </div>
        </div>
      </div>
    </div>

    {{-- Vehicle --}}
    <div class="cn-card">
      <div class="cn-card-head">
        <div class="cn-card-icon" style="background:#D1FAE5;color:#059669;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <div class="cn-card-title">Vehicle Selection</div>
      </div>
      <div class="cn-card-body">
        <div class="veh-grid">
          @foreach($vehicles as $v)
          <div class="veh-card {{ old('vehicle_id',$booking->vehicle_id) == $v->id ? 'selected' : '' }}"
               data-id="{{ $v->id }}" data-name="{{ $v->name }}" data-seats="{{ $v->seating_capacity }}"
               onclick="selectVehicle(this)">
            <div class="veh-card-check">✓</div>
            <div class="veh-emoji">{{ $v->emoji }}</div>
            <div class="veh-name">{{ $v->name }}</div>
            <div class="veh-seats">{{ $v->seating_capacity }} seats</div>
          </div>
          @endforeach
          <div class="veh-card {{ !old('vehicle_id',$booking->vehicle_id) ? 'selected' : '' }}" id="veh-card-custom" onclick="selectVehicleCustom()">
            <div class="veh-card-check">✓</div>
            <div class="veh-emoji">✏️</div>
            <div class="veh-name" style="color:#7C3AED;">Other</div>
            <div class="veh-seats">Custom</div>
          </div>
        </div>

        <input type="hidden" name="vehicle_id"       id="f_vehicle_id"     value="{{ old('vehicle_id',$booking->vehicle_id) }}">
        <input type="hidden" name="vehicle_name"     id="f_vehicle_name"   value="{{ old('vehicle_name',$booking->vehicle_name) }}">
        <input type="hidden" name="vehicle_number"   id="f_vehicle_number" value="{{ old('vehicle_number',$booking->vehicle_number) }}">
        <input type="hidden" name="seating_capacity" id="f_veh_seats"      value="{{ old('seating_capacity',$booking->seating_capacity) }}">

        <div class="row g-3 mt-1">
          <div class="col-md-4">
            <label class="cn-label">Vehicle Name <span class="cn-req">*</span></label>
            <input type="text" id="veh_name_display" class="form-control cn-input"
                   value="{{ old('vehicle_name',$booking->vehicle_name) }}"
                   oninput="document.getElementById('f_vehicle_name').value=this.value">
          </div>
          <div class="col-md-4">
            <label class="cn-label">Vehicle Number</label>
            <input type="text" id="veh_number_display" class="form-control cn-input"
                   value="{{ old('vehicle_number',$booking->vehicle_number) }}"
                   oninput="document.getElementById('f_vehicle_number').value=this.value">
          </div>
          <div class="col-md-4">
            <label class="cn-label">Seating Capacity</label>
            <input type="number" id="veh_seats_display" class="form-control cn-input" min="1"
                   value="{{ old('seating_capacity',$booking->seating_capacity) }}"
                   oninput="document.getElementById('f_veh_seats').value=this.value">
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
      </div>
      <div class="cn-card-body">

        {{-- Persons row --}}
        <div class="row g-3" style="margin-bottom:16px;">
          <div class="col-6 col-md-3">
            <label class="cn-label">Adults <span class="cn-req">*</span></label>
            <div style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:6px 10px;">
              <button type="button" onclick="adjCount('no_of_adults',-1)"
                      style="width:30px;height:30px;border-radius:8px;border:1.5px solid #CBD5E1;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
              <span id="no_of_adults_dis" style="font-size:1.3rem;font-weight:800;color:#4F46E5;min-width:28px;text-align:center;">{{ old('no_of_adults', $booking->no_of_adults ?? 1) }}</span>
              <button type="button" onclick="adjCount('no_of_adults',1)"
                      style="width:30px;height:30px;border-radius:8px;border:1.5px solid #4F46E5;background:#4F46E5;font-size:1.1rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
            </div>
            <input type="hidden" name="no_of_adults" id="no_of_adults" value="{{ old('no_of_adults', $booking->no_of_adults ?? 1) }}">
          </div>
          <div class="col-6 col-md-3">
            <label class="cn-label">Children</label>
            <div style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:6px 10px;">
              <button type="button" onclick="adjCount('no_of_children',-1)"
                      style="width:30px;height:30px;border-radius:8px;border:1.5px solid #CBD5E1;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
              <span id="no_of_children_dis" style="font-size:1.3rem;font-weight:800;color:#7C3AED;min-width:28px;text-align:center;">{{ old('no_of_children', $booking->no_of_children ?? 0) }}</span>
              <button type="button" onclick="adjCount('no_of_children',1)"
                      style="width:30px;height:30px;border-radius:8px;border:1.5px solid #7C3AED;background:#7C3AED;font-size:1.1rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
            </div>
            <input type="hidden" name="no_of_children" id="no_of_children" value="{{ old('no_of_children', $booking->no_of_children ?? 0) }}">
          </div>
          <div class="col-md-3">
            <label class="cn-label">Flight / Train No.</label>
            <input type="text" name="flight_train_number" class="form-control cn-input"
                   placeholder="e.g. AI-202, 12345" value="{{ old('flight_train_number', $booking->flight_train_number) }}">
            <div class="cn-hint">For airport / station transfers</div>
          </div>
          <div class="col-md-3">
            <label class="cn-label">Luggage Details</label>
            <input type="text" name="luggage_details" class="form-control cn-input"
                   placeholder="e.g. 2 large bags, 1 stroller" value="{{ old('luggage_details', $booking->luggage_details) }}">
          </div>
        </div>

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
                   value="{{ old($field, $booking->{$field} ?? ($field === 'ac_required' ? '1' : '0')) }}">
          </label>
          @endforeach

        </div>

      </div>
    </div>

    {{-- Pricing --}}
    <div class="cn-card">
      <div class="cn-card-head">
        <div class="cn-card-icon" style="background:#EFF6FF;color:#3B82F6;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="cn-card-title">Pricing Breakdown</div>
      </div>
      <div class="cn-card-body">

        {{-- Vehicle count counter --}}
        <div style="display:flex;align-items:center;gap:14px;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:12px;padding:12px 16px;margin-bottom:16px;">
          <div style="flex:1;">
            <div style="font-size:.65rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">No. of Vehicles</div>
            <div style="font-size:.75rem;color:#94A3B8;">Fare fields below are per vehicle &mdash; total fare = fare × vehicle count.</div>
          </div>
          <div style="display:flex;align-items:center;gap:10px;">
            <button type="button" onclick="changeVehCount(-1)"
                    style="width:34px;height:34px;border-radius:50%;border:1.5px solid #CBD5E1;background:#fff;font-size:1.2rem;font-weight:700;color:#475569;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
            <span id="veh-count-val" style="font-size:1.6rem;font-weight:800;color:#4F46E5;min-width:36px;text-align:center;line-height:1;">{{ old('vehicle_count', $booking->vehicle_count ?? 1) }}</span>
            <button type="button" onclick="changeVehCount(1)"
                    style="width:34px;height:34px;border-radius:50%;border:1.5px solid #4F46E5;background:#4F46E5;font-size:1.2rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
          </div>
          <input type="hidden" name="vehicle_count" id="f_vehicle_count" value="{{ old('vehicle_count', $booking->vehicle_count ?? 1) }}">
        </div>

        <div class="price-grid">
          @foreach([
            'base_fare'        => ['Base Fare',         $booking->base_fare,         true ],
            'driver_allowance' => ['Driver Allowance',  $booking->driver_allowance,  false],
            'toll_tax'         => ['Toll Tax',          $booking->toll_tax,          false],
            'parking'          => ['Parking Charges',   $booking->parking,           false],
            'state_tax'        => ['State Tax',         $booking->state_tax,         false],
            'night_charges'    => ['Night Charges',     $booking->night_charges,     false],
            'extra_km_charges' => ['Extra KM Charges',  $booking->extra_km_charges,  false],
          ] as $field => [$label, $cur, $req])
          <div>
            <label class="cn-label">{{ $label }}@if($req)<span class="cn-req">*</span>@endif</label>
            <div class="cn-rupee-wrap">
              <span class="cn-rupee">₹</span>
              <input type="number" name="{{ $field }}" id="{{ $field }}"
                     class="form-control cn-input cn-rupee-input"
                     min="0" step="0.01" placeholder="0"
                     value="{{ old($field, $cur) }}"
                     oninput="recalcFare()"
                     {{ $req ? 'required' : '' }}>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>{{-- /left --}}

  {{-- Sidebar --}}
  <div class="cn-sidebar">
    <div class="cn-sidebar-card">
      <div class="cn-sidebar-head">
        <div class="cn-card-icon" style="background:#F0F9FF;color:#0891b2;width:28px;height:28px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="cn-card-title" style="font-size:.9rem;">Booking Amount</div>
      </div>

      <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:12px 14px;margin-bottom:14px;">
        <div class="fare-row"><span class="fare-lbl">Subtotal</span><span class="fare-val" id="s-subtotal">₹{{ number_format($booking->subtotal, 2) }}</span></div>
        <div class="fare-row" id="s-disc-row" style="{{ $booking->discount > 0 ? '' : 'display:none;' }}">
          <span class="fare-lbl">Discount</span><span class="fare-val" id="s-discount" style="color:#F59E0B;">-₹{{ number_format($booking->discount, 2) }}</span>
        </div>
        <div class="fare-row fare-total"><span>Total</span><span id="s-total">₹{{ number_format($booking->total_amount, 2) }}</span></div>
        <div class="fare-row"><span class="fare-lbl">Paid</span><span class="fare-val" id="s-paid" style="color:#10B981;">₹{{ number_format($paidTotal, 2) }}</span></div>
        <div class="fare-row fare-balance"><span>Balance</span><span id="s-balance">₹{{ number_format(max(0,$booking->total_amount-$paidTotal), 2) }}</span></div>
      </div>

      <div style="margin-bottom:12px;">
        <label class="cn-label">Discount (₹)</label>
        <div class="cn-rupee-wrap">
          <span class="cn-rupee">₹</span>
          <input type="number" name="discount" id="discount" class="form-control cn-input cn-rupee-input"
                 min="0" step="0.01" value="{{ old('discount', $booking->discount) }}" oninput="recalcFare()">
        </div>
      </div>

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
                 min="0" step="0.01" placeholder="0.00"
                 value="{{ old('vendor_cost', $booking->vendor_cost) }}"
                 style="border-color:#FDE68A !important;background:#FFFBEB !important;"
                 oninput="recalcFare()">
        </div>

        {{-- Live Profit Display --}}
        <div id="profit-box" style="{{ $booking->vendor_cost > 0 || $booking->total_amount > 0 ? '' : 'display:none;' }};margin-top:10px;border-top:1px dashed #FDE68A;padding-top:10px;">
          <div style="display:flex;justify-content:space-between;font-size:.74rem;color:#92400E;margin-bottom:4px;">
            <span>Total Fare</span>
            <span id="profit-fare" style="font-weight:700;">₹{{ number_format($booking->total_amount, 2) }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:.74rem;color:#B45309;margin-bottom:6px;">
            <span>B2B Vendor Cost</span>
            <span id="profit-cost" style="font-weight:700;">− ₹{{ number_format($booking->vendor_cost ?? 0, 2) }}</span>
          </div>
          @php
            $profit    = $booking->total_amount - ($booking->vendor_cost ?? 0);
            $profitPct = $booking->total_amount > 0 ? round(($profit / $booking->total_amount) * 100, 1) : 0;
          @endphp
          <div style="display:flex;justify-content:space-between;align-items:center;background:#fff;border-radius:8px;padding:7px 10px;border:1px solid #FDE68A;">
            <span style="font-size:.78rem;font-weight:700;color:#065F46;">💰 Profit</span>
            <div style="text-align:right;">
              <div id="profit-amt" style="font-size:1rem;font-weight:900;color:{{ $profit >= 0 ? '#059669' : '#DC2626' }};">₹{{ number_format($profit, 2) }}</div>
              <div id="profit-pct" style="font-size:.65rem;font-weight:700;color:{{ $profit >= 0 ? '#6B7280' : '#DC2626' }};">{{ $profit >= 0 ? '▲' : '▼' }} {{ abs($profitPct) }}% margin</div>
            </div>
          </div>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label class="cn-label">Booking Status</label>
        <select name="booking_status" class="form-select cn-input">
          @foreach(['confirmed'=>'✅ Confirmed','pending'=>'⏳ Pending','assigned'=>'🔧 Assigned','completed'=>'✔️ Completed','cancelled'=>'❌ Cancelled'] as $v=>$l)
            <option value="{{ $v }}" {{ old('booking_status',$booking->booking_status)==$v ? 'selected' : '' }}>{{ $l }}</option>
          @endforeach
        </select>
      </div>

      {{-- Existing Payments --}}
      @if($booking->payments->count())
      <div style="margin-bottom:14px;padding:12px;background:#F0FDF4;border-radius:10px;border:1px solid #BBF7D0;">
        <div style="font-size:.72rem;font-weight:700;color:#065F46;margin-bottom:8px;">Payments Recorded</div>
        @foreach($booking->payments as $pmt)
        <div class="pmt-item">
          <div>
            <div style="font-size:.78rem;color:#065F46;font-weight:700;">₹{{ number_format($pmt->amount, 2) }}</div>
            <div style="font-size:.68rem;color:#94A3B8;">{{ \Carbon\Carbon::parse($pmt->payment_date)->format('d M Y') }} · {{ ucwords(str_replace('_',' ',$pmt->payment_method)) }}</div>
          </div>
          <span class="pmt-amt">✓</span>
        </div>
        @endforeach
      </div>
      @endif

      <div style="margin-bottom:12px;">
        <label class="cn-label">Notes</label>
        <textarea name="notes" class="form-control cn-input" rows="3" style="height:auto !important;">{{ old('notes', $booking->notes) }}</textarea>
      </div>

      <button type="submit" class="btn-save">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="17" height="17"><polyline points="20 6 9 17 4 12"/></svg>
        Save Changes
      </button>
    </div>
  </div>

  </div>{{-- /layout --}}
  </form>
</div>

<script>
let isCustomVehicle = {{ $booking->vehicle_id ? 'false' : 'true' }};

document.addEventListener('DOMContentLoaded', () => {
  feather.replace();

  // Init: highlight active add-ons on load
  ['carrier_on_roof','child_seat','wheelchair_accessible','ac_required'].forEach(field => {
    const lbl = document.getElementById('addon-lbl-' + field);
    const inp = document.getElementById('addon-' + field);
    if (lbl && inp && inp.value === '1') {
      lbl.style.borderColor = '#4F46E5';
      lbl.style.background  = '#EEF2FF';
      lbl.style.boxShadow   = '0 0 0 3px rgba(79,70,229,.15)';
      lbl.querySelector('span:nth-child(2)').style.color = '#4F46E5';
    }
  });
});

// ── Passenger count steppers ─────────────────────────────────────
function adjCount(field, delta) {
  const hidden = document.getElementById(field);
  const disp   = document.getElementById(field + '_dis');
  const min    = field === 'no_of_adults' ? 1 : 0;
  const val    = Math.max(min, (parseInt(hidden.value) || 0) + delta);
  hidden.value = val;
  if (disp) disp.textContent = val;
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

function calcDays() {
  const ci    = document.getElementById('pickup_date')?.value;
  const co    = document.getElementById('return_date')?.value;
  const el    = document.getElementById('total_days');
  const badge = document.getElementById('days-auto-badge');
  if (ci && co && co >= ci) {
    el.value    = Math.max(1, Math.ceil((new Date(co) - new Date(ci)) / 86400000) + 1);
    el.readOnly = true;
    el.style.cssText = 'background:#F0FDF4 !important;border-color:#10B981 !important;font-weight:700 !important;color:#059669 !important;';
    if (badge) badge.style.display = 'inline';
  } else {
    el.readOnly = false;
    el.style.cssText = '';
    if (badge) badge.style.display = 'none';
  }
  recalcFare();
}

function selectVehicle(card) {
  document.querySelectorAll('.veh-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  isCustomVehicle = false;
  document.getElementById('f_vehicle_id').value   = card.dataset.id;
  document.getElementById('f_vehicle_name').value = card.dataset.name;
  document.getElementById('f_veh_seats').value    = card.dataset.seats;
  document.getElementById('veh_name_display').value  = card.dataset.name;
  document.getElementById('veh_seats_display').value = card.dataset.seats;
}

function selectVehicleCustom() {
  document.querySelectorAll('.veh-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('veh-card-custom').classList.add('selected');
  isCustomVehicle = true;
  document.getElementById('f_vehicle_id').value = '';
}

function changeVehCount(delta) {
  const el = document.getElementById('veh-count-val');
  const val = Math.max(1, (parseInt(el.textContent) || 1) + delta);
  el.textContent = val;
  document.getElementById('f_vehicle_count').value = val;
  recalcFare();
}

function recalcFare() {
  const flds = ['base_fare','driver_allowance','toll_tax','parking','state_tax','night_charges','extra_km_charges'];
  const vehCount = parseInt(document.getElementById('f_vehicle_count')?.value) || 1;
  let sub = flds.reduce((s,f) => s + (parseFloat(document.getElementById(f)?.value)||0), 0) * vehCount;
  const disc  = parseFloat(document.getElementById('discount')?.value) || 0;
  const total = Math.max(0, sub - disc);
  const paid  = {{ $paidTotal }};
  const bal   = Math.max(0, total - paid);
  const fmt   = v => '₹' + v.toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2});
  document.getElementById('s-subtotal').textContent = fmt(sub);
  document.getElementById('s-total').textContent    = fmt(total);
  document.getElementById('s-paid').textContent     = fmt(paid);
  document.getElementById('s-balance').textContent  = fmt(bal);
  const dr = document.getElementById('s-disc-row');
  const dd = document.getElementById('s-discount');
  if (disc > 0) { if(dd) dd.textContent='-'+fmt(disc); if(dr) dr.style.display=''; }
  else { if(dr) dr.style.display='none'; }

  // ── Profit calculation ───────────────────────────────────────────
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
</script>
@endsection
