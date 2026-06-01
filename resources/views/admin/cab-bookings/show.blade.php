@extends('admin.layouts.app')
@section('content')

@php
  $paid    = $booking->payments->sum('amount');
  $paidPct = $booking->total_amount > 0 ? min(100, ($paid / $booking->total_amount) * 100) : 0;
  $due     = max(0, $booking->total_amount - $paid);
@endphp

<style>
.cs-page{background:#F0F4FF;min-height:100vh;padding:20px 24px;}
.cs-header{background:linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%);border-radius:16px;padding:20px 26px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;}
.cs-header::before{content:'';position:absolute;top:-50px;right:-50px;width:200px;height:200px;background:rgba(255,255,255,.06);border-radius:50%;}
.cs-h-title{color:#fff;font-size:1.2rem;font-weight:800;margin:0;position:relative;z-index:1;}
.cs-h-sub{color:rgba(255,255,255,.75);font-size:.78rem;margin:.3rem 0 0;position:relative;z-index:1;}
.cs-h-acts{display:flex;gap:8px;position:relative;z-index:1;flex-wrap:wrap;}
.cs-btn{display:inline-flex;align-items:center;gap:6px;border-radius:8px;padding:8px 14px;font-size:.8rem;font-weight:600;text-decoration:none;transition:opacity .2s;border:none;cursor:pointer;}
.cs-btn.ghost{background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.3);}
.cs-btn.ghost:hover{background:rgba(255,255,255,.28);color:#fff;}
.cs-btn.indigo{background:#4F46E5;color:#fff;}
.cs-btn.emerald{background:#10B981;color:#fff;}
.cs-btn.amber{background:#F59E0B;color:#fff;}
.cs-btn.rose{background:#EF4444;color:#fff;}
.cs-btn.whatsapp{background:#25D366;color:#fff;}
.cs-btn:hover{opacity:.88;}

.cs-layout{display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;}
@media(max-width:1100px){.cs-layout{grid-template-columns:1fr;}}

.cs-card{background:#fff;border-radius:14px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05);margin-bottom:16px;overflow:hidden;}
.cs-card-head{padding:12px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:10px;background:#FAFBFF;}
.cs-card-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.cs-card-title{font-size:.84rem;font-weight:700;color:#0F172A;}
.cs-card-body{padding:18px;}

.cs-info-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;}
.cs-info-lbl{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:3px;}
.cs-info-val{font-size:.88rem;font-weight:600;color:#0F172A;}
.cs-info-val.muted{color:#64748B;font-weight:400;}

/* Route visual */
.cs-route-vis{display:flex;align-items:center;gap:0;margin:16px 0;background:#F8FAFC;border-radius:12px;padding:16px 20px;border:1px solid #E2E8F0;}
.cs-route-point{flex:1;}
.cs-route-label{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:4px;}
.cs-route-addr{font-size:.9rem;font-weight:700;color:#0F172A;}
.cs-route-arrow{display:flex;flex-direction:column;align-items:center;padding:0 16px;color:#CBD5E1;}
.cs-route-line{width:60px;height:2px;background:linear-gradient(90deg,#4F46E5,#7C3AED);border-radius:2px;margin:4px 0;}

/* Fare table */
.fare-tbl{width:100%;border-collapse:collapse;}
.fare-tbl tr td{padding:7px 0;font-size:.83rem;border-bottom:1px dashed #F1F5F9;}
.fare-tbl tr:last-child td{border-bottom:none;}
.fare-tbl .lbl{color:#64748B;}
.fare-tbl .val{text-align:right;font-weight:600;color:#0F172A;}
.fare-tbl .total-row td{font-weight:800;font-size:.95rem;color:#4F46E5;padding-top:12px;border-top:2px solid #E8ECF4;border-bottom:none;}
.fare-tbl .disc-row td{color:#F59E0B;}

/* Badge */
.cs-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;}
.cs-badge.pending  {background:#FEF3C7;color:#92400E;}
.cs-badge.confirmed{background:#DBEAFE;color:#1E40AF;}
.cs-badge.assigned {background:#E0F2FE;color:#075985;}
.cs-badge.completed{background:#D1FAE5;color:#065F46;}
.cs-badge.cancelled{background:#FEE2E2;color:#991B1B;}
.cs-badge.unpaid   {background:#FEE2E2;color:#991B1B;}
.cs-badge.partial  {background:#FEF3C7;color:#92400E;}
.cs-badge.paid     {background:#D1FAE5;color:#065F46;}

/* Sidebar */
.cs-sidebar{position:sticky;top:20px;}
.cs-scard{background:#fff;border-radius:14px;border:1px solid #E8ECF4;box-shadow:0 1px 3px rgba(0,0,0,.05);padding:18px;margin-bottom:16px;}

.pay-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.8rem;border-bottom:1px dashed #F1F5F9;}
.pay-row:last-child{border-bottom:none;}
.pay-lbl{color:#64748B;}
.pay-val{font-weight:700;}
.pay-total-row{font-weight:800;font-size:.95rem;color:#4F46E5;}
.pay-balance{font-weight:800;font-size:.95rem;color:#EF4444;}
.pay-bar-track{height:6px;background:#E2E8F0;border-radius:99px;overflow:hidden;margin-top:10px;}
.pay-bar-fill{height:100%;background:linear-gradient(90deg,#4F46E5,#7C3AED);border-radius:99px;}

.pmt-item{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #F1F5F9;}
.pmt-item:last-child{border-bottom:none;}
.pmt-date{font-size:.75rem;color:#64748B;}
.pmt-method{font-size:.68rem;color:#94A3B8;}
.pmt-amt{font-weight:700;color:#059669;font-size:.85rem;}
.pmt-del{border:none;background:#FEF2F2;color:#DC2626;border-radius:6px;padding:2px 8px;font-size:.7rem;cursor:pointer;}
.pmt-del:hover{background:#DC2626;color:#fff;}

/* Status update form */
.status-select{border:1.5px solid #E2E8F0;border-radius:9px;padding:7px 11px;font-size:.82rem;width:100%;margin-bottom:8px;}
.status-btn{width:100%;padding:8px;border:none;border-radius:9px;background:#4F46E5;color:#fff;font-size:.83rem;font-weight:700;cursor:pointer;}
.status-btn:hover{opacity:.88;}
</style>

<div class="cs-page">

  @foreach(['success','error','info'] as $t)
  @if(session($t))
  <div class="alert alert-{{ $t=='error'?'danger':$t }} alert-dismissible fade show mb-3" style="border-radius:10px;">
    {{ session($t) }}<button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @endforeach

  {{-- Header --}}
  <div class="cs-header">
    <div style="margin-bottom:12px;">
      <div class="cs-h-title">🚗 {{ $booking->booking_number }}</div>
      <div class="cs-h-sub">
        {{ $booking->customer_name }} &nbsp;·&nbsp;
        {{ $booking->trip_type_label }} &nbsp;·&nbsp;
        {{ $booking->pickup_date->format('d M Y') }}
        &nbsp;·&nbsp;
        <span class="cs-badge {{ $booking->booking_status }}" style="font-size:.68rem;">{{ ucfirst($booking->booking_status) }}</span>
      </div>
    </div>
    <div class="cs-h-acts">
      <a href="{{ route('cab-bookings.edit', $booking->id) }}" class="cs-btn ghost">
        <i data-feather="edit" style="width:13px;height:13px;stroke:#fff;"></i> Edit
      </a>
      <a href="{{ route('cab-bookings.invoice', $booking->id) }}" class="cs-btn ghost" target="_blank">
        <i data-feather="file-text" style="width:13px;height:13px;stroke:#fff;"></i> Invoice
      </a>
      @php
        $waText = urlencode(
          "🚗 *Visit Kashi – Cab Booking Confirmation*\n\n" .
          "Booking ID: *{$booking->booking_number}*\n" .
          "Name: {$booking->customer_name}\n" .
          "From: {$booking->pickup_address}\n" .
          "To: {$booking->drop_address}\n" .
          "Date: " . $booking->pickup_date->format('d M Y') . " at " . \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') . "\n" .
          "Vehicle: {$booking->vehicle_name}\n" .
          "Total: ₹" . number_format($booking->total_amount, 2) . "\n\n" .
          "Thank you for choosing Visit Kashi! 🙏"
        );
      @endphp
      <a href="https://wa.me/91{{ preg_replace('/\D/','',$booking->customer_phone) }}?text={{ $waText }}"
         class="cs-btn whatsapp" target="_blank">
        <svg viewBox="0 0 24 24" fill="currentColor" width="13" height="13"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        WhatsApp
      </a>
      <a href="{{ route('cab-bookings.index') }}" class="cs-btn ghost">← Back</a>
    </div>
  </div>

  <div class="cs-layout">

    {{-- ══ LEFT ══ --}}
    <div>

      {{-- Journey Summary --}}
      <div class="cs-card">
        <div class="cs-card-head">
          <div class="cs-card-icon" style="background:#EEF2FF;color:#4F46E5;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M3 12h18M3 12l4-4M3 12l4 4"/></svg>
          </div>
          <div class="cs-card-title">Journey Route</div>
          <span class="cs-badge {{ $booking->booking_status }}" style="margin-left:auto;">{{ ucfirst($booking->booking_status) }}</span>
        </div>
        <div class="cs-card-body">
          <div class="cs-route-vis">
            <div class="cs-route-point">
              <div class="cs-route-label">📍 Pickup</div>
              <div class="cs-route-addr">{{ $booking->pickup_address }}</div>
              <div style="font-size:.75rem;color:#4F46E5;margin-top:4px;">
                {{ $booking->pickup_date->format('d M Y') }}
                at {{ \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') }}
              </div>
            </div>
            <div class="cs-route-arrow">
              <div class="cs-route-line"></div>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><polyline points="9 18 15 12 9 6"/></svg>
              <div class="cs-route-line"></div>
            </div>
            <div class="cs-route-point" style="text-align:right;">
              <div class="cs-route-label">🏁 Drop</div>
              <div class="cs-route-addr">{{ $booking->drop_address }}</div>
              @if($booking->return_date)
              <div style="font-size:.75rem;color:#7C3AED;margin-top:4px;">Return: {{ $booking->return_date->format('d M Y') }}</div>
              @endif
            </div>
          </div>

          <div class="cs-info-grid" style="margin-top:6px;">
            <div>
              <div class="cs-info-lbl">Booking For</div>
              <div class="cs-info-val">
                <span style="background:#EDE9FE;color:#7C3AED;padding:2px 10px;border-radius:10px;font-size:.78rem;font-weight:700;">{{ $booking->trip_type_label }}</span>
              </div>
            </div>
            <div>
              <div class="cs-info-lbl">Total Days</div>
              <div class="cs-info-val">{{ $booking->total_days }} day(s)</div>
            </div>
            @if($booking->total_km)
            <div>
              <div class="cs-info-lbl">Total KM</div>
              <div class="cs-info-val">{{ number_format($booking->total_km, 1) }} km</div>
            </div>
            @endif
            <div>
              <div class="cs-info-lbl">Booked On</div>
              <div class="cs-info-val muted">{{ $booking->created_at->format('d M Y, g:i A') }}</div>
            </div>
            <div>
              <div class="cs-info-lbl">Created By</div>
              <div class="cs-info-val muted">{{ optional($booking->createdBy)->name ?? '—' }}</div>
            </div>
            @if($booking->leadSource)
            <div>
              <div class="cs-info-lbl">Lead Source</div>
              <div class="cs-info-val muted">{{ $booking->leadSource->name }}</div>
            </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Customer Details --}}
      <div class="cs-card">
        <div class="cs-card-head">
          <div class="cs-card-icon" style="background:#F0FDF4;color:#10B981;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <div class="cs-card-title">Customer Details</div>
        </div>
        <div class="cs-card-body">
          <div class="cs-info-grid">
            <div>
              <div class="cs-info-lbl">Name</div>
              <div class="cs-info-val">{{ $booking->customer_name }}</div>
            </div>
            <div>
              <div class="cs-info-lbl">Mobile</div>
              <div class="cs-info-val">
                <a href="tel:{{ $booking->customer_phone }}" style="color:#4F46E5;text-decoration:none;">
                  📞 +91 {{ $booking->customer_phone }}
                </a>
              </div>
            </div>
            @if($booking->customer_alt_phone)
            <div>
              <div class="cs-info-lbl">Alt. Mobile</div>
              <div class="cs-info-val muted">+91 {{ $booking->customer_alt_phone }}</div>
            </div>
            @endif
            @if($booking->customer_email)
            <div>
              <div class="cs-info-lbl">Email</div>
              <div class="cs-info-val muted">{{ $booking->customer_email }}</div>
            </div>
            @endif
            @if($booking->gst_number)
            <div>
              <div class="cs-info-lbl">GST Number</div>
              <div class="cs-info-val muted">{{ $booking->gst_number }}</div>
            </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Vehicle Details --}}
      <div class="cs-card">
        <div class="cs-card-head">
          <div class="cs-card-icon" style="background:#EDE9FE;color:#7C3AED;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <div class="cs-card-title">Vehicle Details</div>
        </div>
        <div class="cs-card-body">
          <div class="cs-info-grid">
            <div>
              <div class="cs-info-lbl">Vehicle</div>
              <div class="cs-info-val">🚗 {{ $booking->vehicle_name }}</div>
            </div>
            @if($booking->vehicle_number)
            <div>
              <div class="cs-info-lbl">Vehicle Number</div>
              <div class="cs-info-val" style="font-family:monospace;letter-spacing:.05em;">{{ $booking->vehicle_number }}</div>
            </div>
            @endif
            @if($booking->seating_capacity)
            <div>
              <div class="cs-info-lbl">Seating Capacity</div>
              <div class="cs-info-val">{{ $booking->seating_capacity }} seats</div>
            </div>
            @endif
            @if($booking->vehicle)
            <div>
              <div class="cs-info-lbl">Category</div>
              <div class="cs-info-val muted">{{ $booking->vehicle->category_label }}</div>
            </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Fare Breakdown --}}
      <div class="cs-card">
        <div class="cs-card-head">
          <div class="cs-card-icon" style="background:#EFF6FF;color:#3B82F6;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <div class="cs-card-title">Fare Breakdown</div>
        </div>
        <div class="cs-card-body">
          <table class="fare-tbl">
            @foreach([
              'Base Fare'         => $booking->base_fare,
              'Driver Allowance'  => $booking->driver_allowance,
              'Toll Tax'          => $booking->toll_tax,
              'Parking'           => $booking->parking,
              'State Tax'         => $booking->state_tax,
              'Night Charges'     => $booking->night_charges,
              'Extra KM Charges'  => $booking->extra_km_charges,
            ] as $lbl => $val)
            @if($val > 0)
            <tr><td class="lbl">{{ $lbl }}</td><td class="val">₹{{ number_format($val, 2) }}</td></tr>
            @endif
            @endforeach
            @if($booking->discount > 0)
            <tr class="disc-row"><td class="lbl">Discount</td><td class="val">- ₹{{ number_format($booking->discount, 2) }}</td></tr>
            @endif
            <tr class="total-row">
              <td>Total Fare</td>
              <td>₹{{ number_format($booking->total_amount, 2) }}</td>
            </tr>
          </table>
        </div>
      </div>

      {{-- Notes --}}
      @if($booking->notes)
      <div class="cs-card">
        <div class="cs-card-head">
          <div class="cs-card-icon" style="background:#F8FAFC;color:#64748B;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          </div>
          <div class="cs-card-title">Internal Notes</div>
        </div>
        <div class="cs-card-body">
          <p style="font-size:.85rem;color:#475569;margin:0;white-space:pre-wrap;">{{ $booking->notes }}</p>
        </div>
      </div>
      @endif

    </div>{{-- /left --}}

    {{-- ══ SIDEBAR ══ --}}
    <div class="cs-sidebar">

      {{-- Payment Summary --}}
      <div class="cs-scard">
        <div style="font-size:.84rem;font-weight:700;color:#0F172A;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;">
          <span>💳 Payment Summary</span>
          <span class="cs-badge {{ $booking->payment_status }}">{{ ucfirst($booking->payment_status) }}</span>
        </div>

        <table style="width:100%;">
          <tr class="pay-row pay-total-row"><td class="pay-lbl" style="color:#0F172A;">Total Fare</td><td class="pay-val" style="text-align:right;color:#4F46E5;">₹{{ number_format($booking->total_amount, 2) }}</td></tr>
          <tr class="pay-row"><td class="pay-lbl">Amount Paid</td><td class="pay-val" style="text-align:right;color:#10B981;">₹{{ number_format($paid, 2) }}</td></tr>
          <tr class="pay-row"><td class="pay-lbl" style="font-weight:700;color:#0F172A;">Balance Due</td><td class="pay-val pay-balance" style="text-align:right;">₹{{ number_format($due, 2) }}</td></tr>
        </table>

        <div class="pay-bar-track">
          <div class="pay-bar-fill" style="width:{{ $paidPct }}%;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.68rem;color:#94A3B8;margin-top:4px;">
          <span>Payment progress</span><span style="color:#4F46E5;font-weight:700;">{{ round($paidPct, 1) }}%</span>
        </div>

        {{-- Payments List --}}
        @if($booking->payments->count())
        <div style="margin-top:14px;">
          <div style="font-size:.72rem;font-weight:700;color:#374151;margin-bottom:8px;">Payments Received</div>
          @foreach($booking->payments as $pmt)
          <div class="pmt-item">
            <div>
              <div style="font-size:.8rem;font-weight:600;color:#0F172A;">₹{{ number_format($pmt->amount, 2) }}</div>
              <div class="pmt-date">{{ \Carbon\Carbon::parse($pmt->payment_date)->format('d M Y') }}</div>
              <div class="pmt-method">{{ ucwords(str_replace('_',' ',$pmt->payment_method)) }}</div>
            </div>
            <form action="{{ route('cab-bookings.delete-payment', [$booking->id, $pmt->id]) }}" method="POST"
                  onsubmit="return confirm('Remove this payment?')">
              @csrf @method('DELETE')
              <button type="submit" class="pmt-del">✕</button>
            </form>
          </div>
          @endforeach
        </div>
        @endif

        {{-- Add Payment --}}
        <div style="margin-top:16px;padding-top:14px;border-top:1px solid #F1F5F9;">
          <div style="font-size:.78rem;font-weight:700;color:#0F172A;margin-bottom:10px;">+ Add Payment</div>
          <form action="{{ route('cab-bookings.add-payment', $booking->id) }}" method="POST">
            @csrf
            <div style="margin-bottom:8px;">
              <input type="number" name="amount" class="form-control" style="border-radius:8px;font-size:.82rem;border:1.5px solid #E2E8F0;"
                     placeholder="₹ Amount" min="1" step="0.01" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">
              <select name="payment_method" class="form-select" style="border-radius:8px;font-size:.78rem;border:1.5px solid #E2E8F0;">
                <option value="cash">💵 Cash</option>
                <option value="upi">📱 UPI</option>
                <option value="bank_transfer">🏦 Bank Transfer</option>
                <option value="card">💳 Card</option>
              </select>
              <input type="date" name="payment_date" class="form-control" style="border-radius:8px;font-size:.78rem;border:1.5px solid #E2E8F0;"
                     value="{{ date('Y-m-d') }}" required>
            </div>
            @if($paymentAccounts->count())
            <div style="margin-bottom:8px;">
              <select name="payment_account_id" class="form-select" style="border-radius:8px;font-size:.78rem;border:1.5px solid #E2E8F0;">
                <option value="">— Account (optional) —</option>
                @foreach($paymentAccounts as $acc)
                  <option value="{{ $acc->id }}">{{ $acc->account_name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <button type="submit" class="status-btn" style="background:#10B981;">
              💰 Record Payment
            </button>
          </form>
        </div>
      </div>

      {{-- Update Status --}}
      <div class="cs-scard">
        <div style="font-size:.84rem;font-weight:700;color:#0F172A;margin-bottom:12px;">Update Status</div>
        <form action="{{ route('cab-bookings.update-status', $booking->id) }}" method="POST">
          @csrf
          <select name="booking_status" class="status-select">
            @foreach(['pending'=>'⏳ Pending','confirmed'=>'✅ Confirmed','assigned'=>'🔧 Assigned','completed'=>'✔️ Completed','cancelled'=>'❌ Cancelled'] as $v=>$l)
              <option value="{{ $v }}" {{ $booking->booking_status == $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
          </select>
          <button type="submit" class="status-btn">Update Status</button>
        </form>
      </div>

      {{-- Quick Actions --}}
      <div class="cs-scard">
        <div style="font-size:.84rem;font-weight:700;color:#0F172A;margin-bottom:12px;">Quick Actions</div>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <a href="{{ route('cab-bookings.edit', $booking->id) }}" class="cs-btn indigo" style="justify-content:center;">
            <i data-feather="edit" style="width:13px;height:13px;"></i> Edit Booking
          </a>
          <a href="{{ route('cab-bookings.invoice', $booking->id) }}" class="cs-btn emerald" target="_blank" style="justify-content:center;">
            <i data-feather="file-text" style="width:13px;height:13px;"></i> View Invoice
          </a>
          <a href="https://wa.me/91{{ preg_replace('/\D/','',$booking->customer_phone) }}?text={{ $waText }}"
             class="cs-btn whatsapp" target="_blank" style="justify-content:center;">
            <svg viewBox="0 0 24 24" fill="currentColor" width="13" height="13"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            Send on WhatsApp
          </a>
          <form action="{{ route('cab-bookings.destroy', $booking->id) }}" method="POST"
                onsubmit="return confirm('Delete this booking?')">
            @csrf @method('DELETE')
            <button type="submit" class="cs-btn rose" style="width:100%;justify-content:center;">
              <i data-feather="trash-2" style="width:13px;height:13px;"></i> Delete Booking
            </button>
          </form>
        </div>
      </div>

    </div>{{-- /sidebar --}}
  </div>
</div>

<script>document.addEventListener('DOMContentLoaded',()=>feather.replace());</script>
@endsection
