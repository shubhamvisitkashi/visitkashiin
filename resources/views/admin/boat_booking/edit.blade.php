@extends('admin.layouts.app')
@section('content')

@php
  $paidAmt  = (float)($booking->payments_sum_amount ?? 0);
  $finalAmt = (float)$booking->final_amount;
  $totalAmt = (float)$booking->total_amount;
  $discAmt  = (float)($booking->total_discount ?? 0);
  $dueAmt   = max(0, $finalAmt - $paidAmt);
  $payStatus = $booking->payment_status ?? ($dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'unpaid'));
  $bkStatus  = $booking->booking_status ?? 'confirmed';

  $payBadge = ['paid' => ['bg-success','Paid'], 'partial' => ['bg-warning text-dark','Partial'], 'unpaid' => ['bg-danger','Unpaid']];
  $bkBadge  = ['confirmed' => ['bg-primary','Confirmed'], 'completed' => ['bg-success','Completed'], 'cancelled' => ['bg-danger','Cancelled'], 'in_progress' => ['bg-info','In Progress']];

  $bkDate    = $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') : '—';
  $boatName  = optional(optional($booking->boat)->boatType)->name ?? '—';
  $boatEmojis = ['Normal Motor Boat'=>'🚤','Light Motor Boat'=>'⛵','Premium Light Motor Boat'=>'⚡','Luxury Mini Yacht'=>'🛥','Bajra Boat'=>'🛶','Cruise'=>'🚢'];
  $boatEmoji  = $boatEmojis[$boatName] ?? '⛵';

  $pickupTime = $booking->pickup_time  ? \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A')  : '—';
  $dropTime   = $booking->drop_time    ? \Carbon\Carbon::parse($booking->drop_time)->format('h:i A')    : '—';
@endphp

<style>
:root {
  --eb-primary: #0C4A6E;
  --eb-accent:  #0EA5E9;
  --eb-bg:      #EFF6FF;
  --eb-card:    #fff;
  --eb-border:  #BFDBFE;
}
.eb-page { background: var(--eb-bg); min-height: 100vh; padding: 24px 26px 60px; }

/* ── Header card ── */
.eb-header {
  background: linear-gradient(135deg, #0C4A6E 0%, #075985 45%, #0369a1 100%);
  border-radius: 15px;
  padding: 10px 10px;
  margin-bottom: 22px;
  margin-top: 50px;
  display: flex; align-items: center; justify-content: space-between; gap: 10px;
}
.eb-header-left { display: flex; align-items: center; gap: 16px; }
.eb-header-icon {
  width: 52px; height: 52px; border-radius: 14px;
  background: rgba(255,255,255,.15); border: 2px solid rgba(255,255,255,.25);
  display: flex; align-items: center; justify-content: center; font-size: 1.6rem;
}
.eb-header-title { color: #fff; font-size: 20px; font-weight: 800; line-height: 1.2; }
.eb-header-sub   { color: rgba(255,255,255,.6); font-size: 12px; margin-top: 3px; }
.eb-header-id    { color: #bae6fd; font-size: 13px; font-weight: 700; font-family: monospace; margin-top: 2px; }
.eb-badge {
  display: inline-block; padding: 4px 14px; border-radius: 20px;
  font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}

/* ── Stats strip ── */
.eb-stats {
  display: grid; grid-template-columns: repeat(5,1fr); gap: 12px; margin-bottom: 22px;
}
.eb-stat-card {
  background: #fff; border: 1px solid #BFDBFE; border-radius: 12px;
  padding: 14px 16px; text-align: center;
}
.eb-stat-val   { font-size: 18px; font-weight: 800; line-height: 1.1; }
.eb-stat-label { font-size: 10px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .06em; margin-top: 4px; }

/* ── Section card ── */
.eb-card {
  background: #fff; border: 1px solid var(--eb-border); border-radius: 14px;
  overflow: hidden; margin-bottom: 18px;
  box-shadow: 0 1px 4px rgba(0,0,0,.04), 0 2px 12px rgba(14,165,233,.05);
}
.eb-card-head {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 20px; border-bottom: 1px solid #BFDBFE;
}
.eb-card-head-icon {
  width: 32px; height: 32px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; flex-shrink: 0;
}
.eb-card-head-title { font-size: 12px; font-weight: 800; color: #0C4A6E; text-transform: uppercase; letter-spacing: .08em; }
.eb-card-head-sub   { font-size: 10px; color: #64748b; margin-left: auto; }
.eb-card-body { padding: 18px 20px; }

/* ── Info fields (readonly display) ── */
.eb-info-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
.eb-info-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; }
.eb-info-grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; }
.eb-info-item {}
.eb-info-label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 5px; }
.eb-info-value {
  background: #F8FAFF; border: 1px solid #BFDBFE; border-radius: 8px;
  padding: 9px 13px; font-size: 13px; font-weight: 600; color: #0F172A;
  min-height: 40px; display: flex; align-items: center;
}
.eb-info-value.mono { font-family: monospace; color: #0C4A6E; font-weight: 700; }
.eb-info-value.muted { color: #94a3b8; font-style: italic; }

/* ── Editable fields ── */
.eb-edit-card {
  background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
  border: 2px solid #93C5FD; border-radius: 14px; overflow: hidden; margin-bottom: 18px;
  box-shadow: 0 2px 16px rgba(14,165,233,.12);
}
.eb-edit-card-head {
  background: linear-gradient(135deg, #0C4A6E, #075985);
  padding: 13px 20px; display: flex; align-items: center; gap: 10px;
}
.eb-edit-card-head-title { color: #fff; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; }
.eb-edit-card-head-note  { color: rgba(255,255,255,.55); font-size: 10.5px; margin-left: auto; }
.eb-edit-card-body { padding: 20px; }

.eb-field label { font-size: 11px; font-weight: 700; color: #0C4A6E; margin-bottom: 6px; display: block; }
.eb-field .form-control {
  border: 2px solid #93C5FD; border-radius: 9px; padding: 10px 14px;
  font-size: 13px; font-weight: 500; background: #fff;
  transition: border-color .2s, box-shadow .2s;
}
.eb-field .form-control:focus {
  border-color: #0EA5E9; box-shadow: 0 0 0 3px rgba(14,165,233,.15); outline: none;
}

/* ── Payment receipt rows ── */
.eb-pay-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 14px; border-bottom: 1px solid #EFF6FF;
}
.eb-pay-row:last-child { border-bottom: none; }
.eb-pay-row-label { font-size: 12px; color: #475569; display: flex; align-items: center; gap: 6px; }
.eb-pay-row-val   { font-size: 13px; font-weight: 700; }

/* ── Action bar ── */
.eb-actions {
  background: #fff; border: 1px solid #BFDBFE; border-radius: 14px;
  padding: 18px 22px; display: flex; align-items: center; gap: 12px;
  box-shadow: 0 1px 4px rgba(0,0,0,.04);
}

@media (max-width:768px) {
  .eb-stats { grid-template-columns: repeat(2,1fr); }
  .eb-info-grid, .eb-info-grid-4 { grid-template-columns: 1fr 1fr; }
  .eb-info-grid-2 { grid-template-columns: 1fr; }
  .eb-header { flex-direction: column; align-items: flex-start; }
}
</style>

<div class="eb-page">

  {{-- ── HEADER ── --}}
  <div class="eb-header">
    <div class="eb-header-left">
      <div class="eb-header-icon">⛵</div>
      <div>
        <div class="eb-header-title">Edit Boat Booking</div>
        <div class="eb-header-sub">Update customer details, persons, ghats &amp; notes</div>
        <div class="eb-header-id">{{ $booking->booking_id }}</div>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
      @php
        [$payBg, $payTxt]  = $payBadge[$payStatus]  ?? ['bg-secondary','—'];
        [$bkBg,  $bkTxt]   = $bkBadge[$bkStatus]    ?? ['bg-secondary','—'];
      @endphp
      <span class="eb-badge {{ $bkBg  }} text-white">{{ $bkTxt }}</span>
      <span class="eb-badge {{ $payBg }}">{{ $payTxt }}</span>
      <a href="{{ route('boat-booking.show', $booking->booking_id) }}"
         class="btn btn-sm btn-light fw-semibold" style="border-radius:8px;">
        <i class="fas fa-eye me-1"></i> View
      </a>
      <a href="{{ route('boat-booking.voucher', $booking->booking_id) }}"
         class="btn btn-sm" target="_blank"
         style="background:#0ea5e9;color:#fff;border-radius:8px;font-weight:600;">
        <i class="fas fa-print me-1"></i> Voucher
      </a>
      <a href="{{ route('boat-booking.payment', $booking->booking_id) }}"
         class="btn btn-sm btn-success fw-semibold" style="border-radius:8px;">
        <i class="fas fa-rupee-sign me-1"></i> Payment
      </a>
    </div>
  </div>

  {{-- ── STATS STRIP ── --}}
  <div class="eb-stats">
    <div class="eb-stat-card">
      <div class="eb-stat-val" style="color:#0369a1;">₹{{ number_format($totalAmt, 0) }}</div>
      <div class="eb-stat-label">Total Amount</div>
    </div>
    <div class="eb-stat-card">
      <div class="eb-stat-val" style="color:#d97706;">₹{{ number_format($discAmt, 0) }}</div>
      <div class="eb-stat-label">Discount</div>
    </div>
    <div class="eb-stat-card">
      <div class="eb-stat-val" style="color:#0C4A6E;">₹{{ number_format($finalAmt, 0) }}</div>
      <div class="eb-stat-label">Final Amount</div>
    </div>
    <div class="eb-stat-card">
      <div class="eb-stat-val" style="color:#059669;">₹{{ number_format($paidAmt, 0) }}</div>
      <div class="eb-stat-label">Paid</div>
    </div>
    <div class="eb-stat-card">
      <div class="eb-stat-val" style="color:{{ $dueAmt > 0 ? '#dc2626' : '#059669' }};">₹{{ number_format($dueAmt, 0) }}</div>
      <div class="eb-stat-label">{{ $dueAmt > 0 ? 'Balance Due' : 'Fully Paid' }}</div>
    </div>
  </div>

  <form id="boat_booking_form" method="POST" action="{{ route('boat-booking.update', $booking->booking_id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="row g-4">

      {{-- LEFT COLUMN --}}
      <div class="col-lg-8">

        {{-- ── EDITABLE: Customer Info ── --}}
        <div class="eb-edit-card">
          <div class="eb-edit-card-head">
            <i class="fas fa-user-edit" style="color:#7dd3fc;font-size:14px;"></i>
            <div class="eb-edit-card-head-title">Edit Customer Information</div>
            <div class="eb-edit-card-head-note"><i class="fas fa-pencil-alt me-1"></i>These fields are editable</div>
          </div>
          <div class="eb-edit-card-body">
            <div class="row g-3">
              {{-- Row 1: Name / Email / Phone --}}
              <div class="col-md-4 eb-field">
                <label for="name"><i class="fas fa-user text-primary me-1"></i>Full Name <span class="text-danger">*</span></label>
                <input id="name" class="form-control" type="text" name="name"
                       value="{{ old('name', $booking->name) }}" placeholder="Customer name" required>
                @error('name')<div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4 eb-field">
                <label for="email"><i class="fas fa-envelope text-primary me-1"></i>Email Address <span class="text-danger">*</span></label>
                <input id="email" class="form-control" type="email" name="email"
                       value="{{ old('email', $booking->email) }}" placeholder="Email address" required>
                @error('email')<div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4 eb-field">
                <label for="phone"><i class="fas fa-phone text-primary me-1"></i>Phone Number <span class="text-danger">*</span></label>
                <input id="phone" class="form-control" type="tel" name="phone"
                       value="{{ old('phone', $booking->phone) }}" placeholder="Phone number" required>
                @error('phone')<div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
              </div>

              {{-- Row 2: No. of Persons / Boarding Ghat / Drop Ghat --}}
              <div class="col-md-4 eb-field">
                <label for="no_of_person"><i class="fas fa-users text-primary me-1"></i>No. of Persons <span class="text-danger">*</span></label>
                <input id="no_of_person" class="form-control" type="number" name="no_of_person" min="1"
                       value="{{ old('no_of_person', $booking->no_of_person) }}" placeholder="Number of persons" required>
                @error('no_of_person')<div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4 eb-field">
                <label for="boarding_ghat"><i class="fas fa-map-marker-alt text-primary me-1"></i>Boarding Ghat</label>
                <input id="boarding_ghat" class="form-control" type="text" name="boarding_ghat"
                       value="{{ old('boarding_ghat', $booking->boarding_ghat) }}" placeholder="e.g. Dashashwamedh Ghat">
                @error('boarding_ghat')<div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4 eb-field">
                <label for="drop_ghat"><i class="fas fa-map-marker-alt text-danger me-1"></i>Drop Ghat</label>
                <input id="drop_ghat" class="form-control" type="text" name="drop_ghat"
                       value="{{ old('drop_ghat', $booking->drop_ghat) }}" placeholder="e.g. Ravidas Ghat">
                @error('drop_ghat')<div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
              </div>

              {{-- Row 3: Guest Notes --}}
              <div class="col-12 eb-field">
                <label for="guest_notes"><i class="fas fa-sticky-note text-primary me-1"></i>Guest Notes</label>
                <textarea id="guest_notes" class="form-control" name="guest_notes" rows="2"
                          placeholder="Any special requests or notes for this booking...">{{ old('guest_notes', $booking->guest_notes) }}</textarea>
                @error('guest_notes')<div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        {{-- ── READ-ONLY: Booking Info ── --}}
        <div class="eb-card">
          <div class="eb-card-head">
            <div class="eb-card-head-icon" style="background:#EFF6FF;color:#0C4A6E;">📋</div>
            <div class="eb-card-head-title">Booking Information</div>
            <span class="eb-card-head-sub"><i class="fas fa-edit me-1"></i>Editable</span>
          </div>
          <div class="eb-card-body">
            <div class="eb-info-grid-4">
              {{-- Booking ID – read-only system field --}}
              <div class="eb-info-item">
                <div class="eb-info-label">Booking ID</div>
                <div class="eb-info-value mono">{{ $booking->booking_id }}</div>
              </div>

              {{-- Booking Date --}}
              <div class="eb-info-item">
                <label class="eb-info-label" for="booking_date">Booking Date</label>
                <input type="date" id="booking_date" name="booking_date"
                       class="form-control"
                       style="border:1.5px solid #BFDBFE;border-radius:8px;padding:9px 13px;font-size:13px;font-weight:600;color:#0F172A;background:#F8FAFF;"
                       value="{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') : '' }}">
              </div>

              {{-- Booking Type --}}
              <div class="eb-info-item">
                <label class="eb-info-label" for="booking_type">Booking Type</label>
                <select id="booking_type" name="booking_type"
                        class="form-select"
                        style="border:1.5px solid #BFDBFE;border-radius:8px;padding:9px 13px;font-size:13px;font-weight:600;color:#0F172A;background:#F8FAFF;">
                  <option value="">— Select —</option>
                  @foreach(['Morning Boat Ride','Evening Boat Ride','Evening Boat Ride with Ganga Aarti'] as $bt)
                    <option value="{{ $bt }}" {{ ($booking->booking_type ?? '') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Event on Boat --}}
              <div class="eb-info-item">
                <label class="eb-info-label" for="event_on_boat">Event on Boat</label>
                <select id="event_on_boat" name="event_on_boat"
                        class="form-select"
                        style="border:1.5px solid #BFDBFE;border-radius:8px;padding:9px 13px;font-size:13px;font-weight:600;color:#0F172A;background:#F8FAFF;">
                  <option value="">None / Regular Ride</option>
                  @foreach(['Birthday Celebration','Anniversary Celebration','Marriage Proposal','Corporate Event','Other'] as $ev)
                    <option value="{{ $ev }}" {{ ($booking->event_on_boat ?? '') === $ev ? 'selected' : '' }}>{{ $ev }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Pickup Time --}}
              <div class="eb-info-item">
                <label class="eb-info-label" for="pickup_time">Pickup Time</label>
                <input type="time" id="pickup_time" name="pickup_time"
                       class="form-control"
                       style="border:1.5px solid #BFDBFE;border-radius:8px;padding:9px 13px;font-size:13px;font-weight:600;color:#0F172A;background:#F8FAFF;"
                       value="{{ $booking->pickup_time ? \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') : '' }}">
              </div>

              {{-- Drop Time --}}
              <div class="eb-info-item">
                <label class="eb-info-label" for="drop_time">Drop Time</label>
                <input type="time" id="drop_time" name="drop_time"
                       class="form-control"
                       style="border:1.5px solid #BFDBFE;border-radius:8px;padding:9px 13px;font-size:13px;font-weight:600;color:#0F172A;background:#F8FAFF;"
                       value="{{ $booking->drop_time ? \Carbon\Carbon::parse($booking->drop_time)->format('H:i') : '' }}">
              </div>
            </div>
          </div>
        </div>

        {{-- ── READ-ONLY: Boat Details ── --}}
        <div class="eb-card">
          <div class="eb-card-head">
            <div class="eb-card-head-icon" style="background:#EFF6FF;color:#0C4A6E;">⛵</div>
            <div class="eb-card-head-title">Boat Details</div>
            <span class="eb-card-head-sub"><i class="fas fa-lock me-1"></i>Read-only</span>
          </div>
          <div class="eb-card-body">
            <div style="display:flex;align-items:center;gap:16px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1.5px solid #93C5FD;border-radius:10px;padding:14px 16px;">
              <div style="font-size:2.4rem;line-height:1;">{{ $boatEmoji }}</div>
              <div>
                <div style="font-size:16px;font-weight:900;color:#0C4A6E;margin-bottom:5px;">{{ $boatName }}</div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                  <span style="background:#0369a1;color:#fff;border-radius:20px;padding:3px 12px;font-size:10px;font-weight:700;">⛵ 84 Ghat Boat Ride</span>
                  @if($booking->boat?->event_type)
                  <span style="background:#EDE9FE;color:#6D28D9;border-radius:20px;padding:3px 12px;font-size:10px;font-weight:700;">{{ $booking->boat->event_type }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>{{-- /col-lg-8 --}}

      {{-- RIGHT COLUMN --}}
      <div class="col-lg-4">

        {{-- ── Payment Summary ── --}}
        <div class="eb-card">
          <div class="eb-card-head">
            <div class="eb-card-head-icon" style="background:#F0FDF4;color:#059669;">💳</div>
            <div class="eb-card-head-title">Payment Summary</div>
          </div>
          <div>
            <div class="eb-pay-row">
              <div class="eb-pay-row-label"><span style="width:18px;height:18px;background:#DBEAFE;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;font-size:9px;">💰</span> Total Amount</div>
              <div class="eb-pay-row-val" style="color:#0369a1;">₹{{ number_format($totalAmt, 2) }}</div>
            </div>
            @if($discAmt > 0)
            <div class="eb-pay-row" style="background:#FFFBEB;">
              <div class="eb-pay-row-label"><span style="width:18px;height:18px;background:#FEF3C7;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;font-size:9px;">🏷</span> Discount</div>
              <div class="eb-pay-row-val" style="color:#d97706;">− ₹{{ number_format($discAmt, 2) }}</div>
            </div>
            @endif
            <div class="eb-pay-row" style="background:#F8FAFC;border-top:1.5px solid #BFDBFE;">
              <div class="eb-pay-row-label" style="font-weight:700;color:#0f172a;"><span style="width:18px;height:18px;background:#DBEAFE;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;font-size:9px;">📊</span> Final Amount</div>
              <div class="eb-pay-row-val" style="color:#2563eb;font-size:14px;">₹{{ number_format($finalAmt, 2) }}</div>
            </div>
            <div class="eb-pay-row" style="background:#F0FDF4;">
              <div class="eb-pay-row-label" style="color:#065f46;"><span style="width:18px;height:18px;background:#D1FAE5;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;font-size:9px;">✓</span> Amount Paid</div>
              <div class="eb-pay-row-val" style="color:#059669;font-size:14px;">₹{{ number_format($paidAmt, 2) }}</div>
            </div>
            <div class="eb-pay-row" style="background:{{ $dueAmt > 0 ? '#FFF7ED' : '#F0FDF4' }};border-bottom:none;">
              <div class="eb-pay-row-label" style="color:{{ $dueAmt > 0 ? '#92400e' : '#065f46' }};">
                <span style="width:18px;height:18px;background:{{ $dueAmt > 0 ? '#FEF3C7' : '#D1FAE5' }};border-radius:4px;display:inline-flex;align-items:center;justify-content:center;font-size:9px;">{{ $dueAmt > 0 ? '⚠' : '✅' }}</span>
                Balance Due
              </div>
              <div class="eb-pay-row-val" style="color:{{ $dueAmt > 0 ? '#d97706' : '#059669' }};font-size:14px;">₹{{ number_format($dueAmt, 2) }}</div>
            </div>
          </div>
          @if($dueAmt > 0)
          <div style="padding:12px 16px;border-top:1px solid #BFDBFE;">
            <a href="{{ route('boat-booking.payment', $booking->booking_id) }}"
               class="btn btn-success btn-sm w-100 fw-semibold">
              <i class="fas fa-rupee-sign me-1"></i> Collect Payment
            </a>
          </div>
          @endif
        </div>

        {{-- ── Quick Actions ── --}}
        <div class="eb-card">
          <div class="eb-card-head">
            <div class="eb-card-head-icon" style="background:#F5F3FF;color:#7C3AED;">⚡</div>
            <div class="eb-card-head-title">Quick Actions</div>
          </div>
          <div class="eb-card-body" style="display:flex;flex-direction:column;gap:8px;">
            <a href="{{ route('boat-booking.show', $booking->booking_id) }}"
               class="btn btn-outline-primary btn-sm fw-semibold" style="border-radius:8px;text-align:left;">
              <i class="fas fa-eye me-2"></i>View Full Booking
            </a>
            <a href="{{ route('boat-booking.voucher', $booking->booking_id) }}" target="_blank"
               class="btn btn-sm fw-semibold" style="background:#0C4A6E;color:#fff;border-radius:8px;text-align:left;">
              <i class="fas fa-print me-2"></i>Print Voucher
            </a>
            <a href="{{ route('boat-booking.payment', $booking->booking_id) }}"
               class="btn btn-outline-success btn-sm fw-semibold" style="border-radius:8px;text-align:left;">
              <i class="fas fa-rupee-sign me-2"></i>Manage Payments
            </a>
            <a href="{{ route('boat-booking.index') }}"
               class="btn btn-outline-secondary btn-sm fw-semibold" style="border-radius:8px;text-align:left;">
              <i class="fas fa-list me-2"></i>All Bookings
            </a>
          </div>
        </div>

      </div>{{-- /col-lg-4 --}}
    </div>{{-- /row --}}

    {{-- ── ACTION BAR ── --}}
    <div class="eb-actions mt-2">
      <button type="submit" class="btn btn-primary fw-bold px-5" style="border-radius:10px;padding:10px 28px;">
        <i class="fas fa-save me-2"></i>Save Changes
      </button>
      <a href="{{ route('boat-booking.show', $booking->booking_id) }}"
         class="btn btn-outline-secondary fw-semibold" style="border-radius:10px;padding:10px 20px;">
        <i class="fas fa-times me-2"></i>Cancel
      </a>
      <div class="ms-auto" style="font-size:11.5px;color:#64748b;">
        <i class="fas fa-info-circle me-1 text-primary"></i>
        Editable: <strong>Name, Email, Phone, Persons, Ghats &amp; Notes</strong>
      </div>
    </div>

  </form>

</div>
@endsection
