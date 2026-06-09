@extends('admin.layouts.app')
@section('content')

@php
  // WhatsApp confirmation message
  $guestPhone = preg_replace('/\D/', '', $booking->phone ?? '');
  $boardingGhat = $booking->boarding_ghat ?? '—';
  $dropGhat     = $booking->drop_ghat ?? '—';
  $boatTimingRaw = $booking->pickup_time ?? $booking->boat_timing ?? null;
  $boatTimingFmt = $boatTimingRaw ? \Carbon\Carbon::parse($boatTimingRaw)->format('g:i A') : '—';
  $bookingDateFmt = $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : '—';

  $waLines = [
    "⛵ *Visit Kashi – Boat Booking Confirmed!*",
    "",
    "Namaste *{$booking->name}* ji 🙏",
    "",
    "📋 *Booking ID:* {$booking->booking_id}",
    "🚢 *Boat:* " . (optional(optional($booking->boat)->boatType)->name ?? '—'),
    "📅 *Date:* {$bookingDateFmt}",
    "⏰ *Time:* {$boatTimingFmt}",
    "📍 *Boarding Ghat:* {$boardingGhat}",
    "📍 *Drop Ghat:* {$dropGhat}",
    "👥 *Persons:* " . ($booking->no_of_person ?? 1),
    "",
    "💰 *Total Amount:* ₹" . number_format((float)$booking->final_amount, 2),
  ];

  // Add add-ons if any
  $addonsList = [];
  if (!empty($booking->decoration))   $addonsList[] = '🌸 Decoration';
  if (!empty($booking->photographer)) $addonsList[] = '📸 Photographer';
  if (!empty($booking->live_music))   $addonsList[] = '🎵 Live Music';
  if (!empty($booking->priest))       $addonsList[] = '🪔 Priest / Puja';
  if (!empty($booking->flowers))      $addonsList[] = '💐 Flowers';
  if (!empty($booking->fireworks))    $addonsList[] = '🎆 Fireworks';
  if ($addonsList) {
    $waLines[] = "✨ *Add-ons:* " . implode(', ', $addonsList);
  }

  $waLines[] = "";
  $waLines[] = "📞 Support: 7080109917 | 7080109918";
  $waLines[] = "";
  $waLines[] = "Thank you for choosing *Visit Kashi*! 🙏";

  $waText = urlencode(implode("\n", $waLines));
@endphp

@php
  // Pre-compute payment/amount variables
  $paidAmt   = (float)($booking->payments_sum_amount ?? 0);
  if (!$paidAmt && $booking->relationLoaded('payments')) {
      $paidAmt = (float)$booking->payments->sum('amount');
  }
  $finalAmt  = (float)$booking->final_amount;
  $totalAmt  = (float)$booking->total_amount;
  $discAmt   = (float)($booking->total_discount ?? 0);
  $dueAmt    = max(0, $finalAmt - $paidAmt);

  $payStatus  = $booking->payment_status ?? ($dueAmt <= 0 ? 'paid' : ($paidAmt > 0 ? 'partial' : 'unpaid'));
  $bkStatus   = $booking->booking_status ?? 'confirmed';

  $boatEmojis = [
    'Normal Motor Boat'        => '🚤',
    'Light Motor Boat'         => '⛵',
    'Premium Light Motor Boat' => '⚡',
    'Luxury Mini Yacht'        => '🛥',
    'Bajra Boat'               => '🛶',
    'Cruise'                   => '🚢',
  ];
  $boatName  = optional(optional($booking->boat)->boatType)->name ?? '—';
  $boatEmoji = $boatEmojis[$boatName] ?? '⛵';

  $adults   = (int)($booking->adults ?? $booking->no_of_person ?? 1);
  $children = (int)($booking->children ?? 0);

  // Active add-ons
  $addonKeys = ['decoration','photographer','live_music','priest','flowers','fireworks'];
  $addonLabels = [
    'decoration'   => '🌸 Decoration',
    'photographer' => '📸 Photographer',
    'live_music'   => '🎵 Live Music',
    'priest'       => '🪔 Priest / Puja',
    'flowers'      => '💐 Flowers',
    'fireworks'    => '🎆 Fireworks',
  ];
  $activeAddons = [];
  foreach ($addonKeys as $key) {
    if (!empty($booking->$key)) {
      $activeAddons[$key] = $addonLabels[$key];
    }
  }
@endphp

<style>
:root{--bg:#EFF6FF;--card:#fff;--border:#BFDBFE;--shadow:0 1px 4px rgba(0,0,0,.04),0 2px 12px rgba(14,165,233,.06);--text:#0F172A;--sub:#475569;--muted:#94A3B8;--blue:#0EA5E9;--dk:#0C4A6E;}
.sv-page{background:var(--bg);min-height:100vh;padding:20px 22px 50px;}
.sv-header {
  background: linear-gradient(135deg, #0C4A6E 0%, #075985 40%, #0EA5E9 100%);
  border-radius: 18px;
  padding: 20px 26px;
  margin-bottom: 20px;
  margin-top: 50px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
  position: relative;
  overflow: hidden;
}
.sv-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.06);}
.sv-header-left{position:relative;z-index:1;}
.sv-header h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 3px;}
.sv-header p{color:rgba(255,255,255,.65);font-size:.76rem;margin:0;}
.sv-header-acts{position:relative;z-index:1;display:flex;gap:8px;flex-wrap:wrap;}
.sv-ghost{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);color:#fff;border-radius:9px;padding:8px 14px;font-size:.78rem;font-weight:700;text-decoration:none;transition:.18s;}
.sv-ghost:hover{background:rgba(255,255,255,.25);color:#fff;}
.sv-edit-btn{background:rgba(255,255,255,.92);color:#0C4A6E;border:none;border-radius:9px;padding:9px 18px;font-size:.8rem;font-weight:800;text-decoration:none;transition:.18s;display:inline-flex;align-items:center;gap:6px;}
.sv-edit-btn:hover{background:#fff;color:#0C4A6E;}
.sv-layout{display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;}
@media(max-width:1100px){.sv-layout{grid-template-columns:1fr;}}
.sv-card{background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow);margin-bottom:16px;overflow:hidden;}
.sv-card-head{padding:12px 18px;border-bottom:1px solid #EFF6FF;display:flex;align-items:center;gap:9px;background:#F0F9FF;}
.sv-card-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;}
.sv-card-title{font-size:.85rem;font-weight:700;color:var(--text);}
.sv-card-body{padding:18px;}
.sv-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.sv-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
@media(max-width:640px){.sv-grid,.sv-grid-3{grid-template-columns:1fr 1fr;}}
.sv-lbl{font-size:.63rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.sv-val{font-size:.84rem;font-weight:600;color:var(--text);}
.sv-val.big{font-size:1rem;font-weight:800;}
.bs{display:inline-flex;align-items:center;font-size:.65rem;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.03em;}
.bs-confirmed{background:#DBEAFE;color:#1E40AF;}
.bs-completed{background:#D1FAE5;color:#065F46;}
.bs-cancelled{background:#FEE2E2;color:#991B1B;}
.bs-pending  {background:#FEF3C7;color:#92400E;}
.bs-paid     {background:#D1FAE5;color:#065F46;}
.bs-partial  {background:#DBEAFE;color:#1E40AF;}
.bs-unpaid   {background:#FEE2E2;color:#DC2626;}
.boat-chip{display:inline-flex;align-items:center;gap:6px;background:#EFF6FF;border:1.5px solid #BAE6FD;border-radius:20px;padding:5px 14px;font-size:.78rem;font-weight:700;color:#0369A1;}
.addon-list{display:flex;flex-wrap:wrap;gap:6px;}
.addon-pill{background:#EFF6FF;border:1px solid #BAE6FD;border-radius:20px;padding:3px 10px;font-size:.7rem;font-weight:700;color:#0369A1;}
.sv-sb{position:sticky;top:20px;}
.sv-sb-card{background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow);padding:18px;margin-bottom:14px;}
.sv-sb-title{font-size:.82rem;font-weight:700;color:var(--text);margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid #EFF6FF;}
.ps-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px dashed #EFF6FF;font-size:.78rem;}
.ps-row:last-child{border-bottom:none;}
.ps-lbl{color:var(--sub);}
.ps-val{font-weight:700;color:var(--text);}
.pay-item{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #EFF6FF;font-size:.76rem;}
.pay-item:last-child{border-bottom:none;}
.bal-box{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1.5px solid #BAE6FD;border-radius:12px;padding:14px 16px;text-align:center;margin-bottom:12px;}
.bal-lbl{font-size:.63rem;font-weight:700;color:#0369A1;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.bal-amt{font-size:1.8rem;font-weight:900;color:#0C4A6E;}
.sv-pay-label{font-size:.68rem;font-weight:700;color:#0369A1;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;display:block;}
.sv-pay-input{border:1.5px solid #BAE6FD !important;border-radius:8px !important;padding:8px 11px !important;font-size:.82rem !important;background:#fff !important;width:100%;}
.sv-pay-input:focus{border-color:#0EA5E9 !important;outline:none !important;}
.sv-pay-btn{background:linear-gradient(135deg,#0EA5E9,#0284C7);color:#fff;border:none;border-radius:9px;padding:10px;font-size:.8rem;font-weight:800;cursor:pointer;width:100%;margin-top:10px;}
.qact{display:flex;align-items:center;gap:8px;border-radius:9px;padding:10px 14px;text-decoration:none;font-size:.8rem;font-weight:600;transition:.18s;width:100%;border:1px solid;margin-bottom:8px;}
</style>

<div class="sv-page">

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:12px;background:#D1FAE5;color:#065F46;border:none;font-size:.82rem;font-weight:600;">
  ✓ {{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header --}}
<div class="sv-header">
  <div class="sv-header-left">
    <h1>⛵ {{ $booking->booking_id }}</h1>
    <p>{{ $booking->name }} &nbsp;·&nbsp; {{ $booking->booking_type ?? 'Boat Ride' }} &nbsp;·&nbsp;
      {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : '—' }}
    </p>
  </div>
  <div class="sv-header-acts">
    <a href="{{ route('boat-booking.edit', $booking->booking_id) }}" class="sv-edit-btn">✏️ Edit</a>
    <a href="{{ route('boat-booking.payment', $booking->booking_id) }}" class="sv-ghost">💳 Add Payment</a>
    @if($guestPhone)
    <a href="https://wa.me/91{{ $guestPhone }}?text={{ $waText }}" target="_blank"
       class="sv-ghost" style="background:rgba(37,211,102,.25);border-color:rgba(37,211,102,.5);">
      <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075a8.167 8.167 0 01-2.385-1.475 8.166 8.166 0 01-1.653-2.059c-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
      WhatsApp Guest
    </a>
    @endif
    <a href="{{ route('boat-booking.index') }}" class="sv-ghost">← All Boats</a>
  </div>
</div>

<div class="sv-layout">

  {{-- ════ LEFT ════ --}}
  <div>

    {{-- Booking Info --}}
    <div class="sv-card">
      <div class="sv-card-head">
        <div class="sv-card-icon" style="background:#DBEAFE;">📋</div>
        <div class="sv-card-title">Booking Information</div>
      </div>
      <div class="sv-card-body">
        <div class="sv-grid-3" style="margin-bottom:14px;">
          <div>
            <div class="sv-lbl">Booking ID</div>
            <div class="sv-val big" style="color:#0369A1;font-family:monospace;">{{ $booking->booking_id }}</div>
          </div>
          <div>
            <div class="sv-lbl">Booking Date</div>
            <div class="sv-val">{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') : '—' }}</div>
          </div>
          <div>
            <div class="sv-lbl">Status</div>
            <span class="bs bs-{{ $bkStatus }}">{{ ucfirst($bkStatus) }}</span>
          </div>
        </div>
        <div class="sv-grid-3">
          <div>
            <div class="sv-lbl">Booking Type</div>
            <div class="sv-val">{{ $booking->booking_type ?? '—' }}</div>
          </div>
          <div>
            <div class="sv-lbl">Event on Boat</div>
            <div class="sv-val">{{ $booking->event_on_boat ?? 'Regular Ride' }}</div>
          </div>
          <div>
            <div class="sv-lbl">Boat</div>
            <span class="boat-chip">{{ $boatEmoji }} {{ $boatName }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Guest Details --}}
    <div class="sv-card">
      <div class="sv-card-head">
        <div class="sv-card-icon" style="background:#DBEAFE;">👤</div>
        <div class="sv-card-title">Guest Details</div>
      </div>
      <div class="sv-card-body">
        <div class="sv-grid">
          <div>
            <div class="sv-lbl">Guest Name</div>
            <div class="sv-val big">{{ $booking->name }}</div>
          </div>
          <div>
            <div class="sv-lbl">Mobile</div>
            <div class="sv-val">{{ $booking->phone }}</div>
          </div>
          <div>
            <div class="sv-lbl">Email</div>
            <div class="sv-val">{{ $booking->email ?? '—' }}</div>
          </div>
          <div>
            <div class="sv-lbl">Persons</div>
            <div class="sv-val">
              {{ $booking->no_of_person }} total
              <span style="font-size:.72rem;color:var(--muted);font-weight:400;">
                ({{ $adults }} adults
                @if($children > 0)
                  , {{ $children }} children — free
                @endif
                )
              </span>
            </div>
          </div>
        </div>
        @if($booking->guest_notes)
        <div style="margin-top:12px;background:#F0F9FF;border:1px solid #BAE6FD;border-radius:8px;padding:10px 14px;">
          <div class="sv-lbl" style="margin-bottom:3px;">Guest Notes</div>
          <div style="font-size:.8rem;color:var(--sub);">{{ $booking->guest_notes }}</div>
        </div>
        @endif
      </div>
    </div>

    {{-- Route & Timing --}}
    <div class="sv-card">
      <div class="sv-card-head">
        <div class="sv-card-icon" style="background:#DBEAFE;">🗺</div>
        <div class="sv-card-title">Route & Timing</div>
      </div>
      <div class="sv-card-body">
        <div class="sv-grid">
          <div>
            <div class="sv-lbl">Pickup Ghat</div>
            <div class="sv-val">📍 {{ $booking->boarding_ghat ?? '—' }}</div>
          </div>
          <div>
            <div class="sv-lbl">Drop Ghat</div>
            <div class="sv-val">📍 {{ $booking->drop_ghat ?? '—' }}</div>
          </div>
          <div>
            <div class="sv-lbl">Pickup Time</div>
            <div class="sv-val">
              @if($booking->pickup_time)
                🕐 {{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}
              @else —
              @endif
            </div>
          </div>
          <div>
            <div class="sv-lbl">End Time</div>
            <div class="sv-val">
              @if($booking->drop_time)
                🕐 {{ \Carbon\Carbon::parse($booking->drop_time)->format('h:i A') }}
              @else —
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Add-ons --}}
    @if(count($activeAddons) > 0)
    <div class="sv-card">
      <div class="sv-card-head">
        <div class="sv-card-icon" style="background:#DBEAFE;">✨</div>
        <div class="sv-card-title">Add-on Services</div>
      </div>
      <div class="sv-card-body">
        <div class="addon-list">
          @foreach($activeAddons as $key => $label)
          <span class="addon-pill">{{ $label }}</span>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    {{-- Staff & Operations --}}
    @if($boatman || $booking->vendor_cost > 0)
    <div class="sv-card">
      <div class="sv-card-head">
        <div class="sv-card-icon" style="background:#FEF3C7;">⚙️</div>
        <div class="sv-card-title">Staff & Operations</div>
      </div>
      <div class="sv-card-body">
        <div class="sv-grid">
          @if($boatman)
          <div>
            <div class="sv-lbl">Assigned Boatman</div>
            <div class="sv-val">⛵ {{ $boatman->name }}</div>
          </div>
          @endif
          @if($booking->vendor_cost > 0)
          <div>
            <div class="sv-lbl">Vendor Cost</div>
            <div class="sv-val">₹{{ number_format($booking->vendor_cost) }}</div>
          </div>
          <div>
            <div class="sv-lbl">Margin</div>
            <div class="sv-val" style="color:#059669;">₹{{ number_format($booking->margin_amount ?? 0) }}</div>
          </div>
          @endif
        </div>
        @if($booking->special_requests)
        <div style="margin-top:10px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;padding:10px 14px;">
          <div class="sv-lbl" style="color:#92400E;margin-bottom:3px;">Internal Notes</div>
          <div style="font-size:.8rem;color:#78350F;">{{ $booking->special_requests }}</div>
        </div>
        @endif
      </div>
    </div>
    @endif

  </div>{{-- /left --}}

  {{-- ════ SIDEBAR ════ --}}
  <div class="sv-sb">

    {{-- Payment Summary --}}
    <div class="sv-sb-card">
      <div class="sv-sb-title">💰 Payment Summary</div>

      <div class="bal-box">
        <div class="bal-lbl">Balance Due</div>
        <div class="bal-amt">₹{{ number_format($dueAmt) }}</div>
        <span class="bs bs-{{ $payStatus }}" style="margin-top:6px;display:inline-flex;">{{ ucfirst($payStatus) }}</span>
      </div>

      <div class="ps-row">
        <span class="ps-lbl">Total Amount</span>
        <span class="ps-val">₹{{ number_format($totalAmt) }}</span>
      </div>
      @if($discAmt > 0)
      <div class="ps-row">
        <span class="ps-lbl">Discount</span>
        <span class="ps-val" style="color:#F59E0B;">-₹{{ number_format($discAmt) }}</span>
      </div>
      @endif
      <div class="ps-row">
        <span class="ps-lbl">Final Amount</span>
        <span class="ps-val">₹{{ number_format($finalAmt) }}</span>
      </div>
      <div class="ps-row">
        <span class="ps-lbl">Amount Paid</span>
        <span class="ps-val" style="color:#059669;">₹{{ number_format($paidAmt) }}</span>
      </div>
      <div class="ps-row">
        <span class="ps-lbl" style="font-weight:700;color:var(--text);">Balance Due</span>
        <span class="ps-val" style="color:{{ $dueAmt > 0 ? '#EF4444' : '#059669' }};font-size:.95rem;font-weight:900;">
          ₹{{ number_format($dueAmt) }}
        </span>
      </div>
    </div>

    {{-- Payment History --}}
    @if($booking->relationLoaded('payments') && $booking->payments->count() > 0)
    <div class="sv-sb-card">
      <div class="sv-sb-title">📄 Payment History</div>
      @foreach($booking->payments as $pmt)
      <div class="pay-item">
        <div>
          <div style="font-weight:600;color:var(--text);font-size:.78rem;">
            {{ \Carbon\Carbon::parse($pmt->created_at)->format('d M Y') }}
          </div>
          <div style="font-size:.68rem;color:var(--muted);">
            {{ ucwords(str_replace('_', ' ', $pmt->payment_mode ?? 'Cash')) }}
          </div>
        </div>
        <div style="font-weight:700;color:#059669;font-size:.78rem;">₹{{ number_format($pmt->amount) }}</div>
      </div>
      @endforeach
    </div>
    @endif

    {{-- Quick Add Payment --}}
    @if($dueAmt > 0)
    <div class="sv-sb-card" style="background:#F0F9FF;">
      <div class="sv-sb-title" style="color:#0369A1;">⚡ Quick Add Payment</div>
      <form action="{{ route('boat-booking.payment.store', $booking->booking_id) }}" method="POST">
        @csrf
        <div style="margin-bottom:10px;">
          <label class="sv-pay-label">Amount (₹)</label>
          <input type="number" name="amount" class="sv-pay-input"
                 placeholder="{{ number_format($dueAmt, 0) }}" min="1" required>
        </div>
        <div>
          <label class="sv-pay-label">Payment Mode</label>
          <select name="payment_mode" class="sv-pay-input form-select" required>
            <option value="">Select…</option>
            <option value="cash">💵 Cash</option>
            <option value="upi">📱 UPI</option>
            <option value="bank_transfer">🏦 Bank Transfer</option>
            <option value="card">💳 Card</option>
          </select>
        </div>
        <button type="submit" class="sv-pay-btn">✓ Add Payment</button>
      </form>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="sv-sb-card">
      <div class="sv-sb-title">⚡ Quick Actions</div>
      <a href="{{ route('boat-booking.voucher', $booking->booking_id) }}" target="_blank"
         class="qact" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);border-color:transparent;color:#fff;font-weight:800;">
        🎫 Print Voucher
      </a>
      @if($guestPhone)
      <a href="https://wa.me/91{{ $guestPhone }}?text={{ $waText }}" target="_blank"
         class="qact" style="background:#25D366;border-color:transparent;color:#fff;font-weight:800;">
        <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075a8.167 8.167 0 01-2.385-1.475 8.166 8.166 0 01-1.653-2.059c-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        Send on WhatsApp
      </a>
      @endif
      <a href="{{ route('boat-booking.edit', $booking->booking_id) }}"
         class="qact" style="background:#EFF6FF;border-color:#BAE6FD;color:#0369A1;">
        ✏️ Edit Booking
      </a>
      <a href="{{ route('boat-booking.index') }}"
         class="qact" style="background:#F8FAFC;border-color:#E2E8F0;color:#475569;">
        📋 All Bookings
      </a>
      <a href="{{ route('boat-booking.create') }}"
         class="qact" style="background:#F0FDF4;border-color:#BBF7D0;color:#065F46;">
        ＋ New Boat Booking
      </a>
      <form id="deleteBoatBookingForm" action="{{ route('boat-booking.destroy', $booking->booking_id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" onclick="confirmDeleteBoatBooking()"
                class="qact" style="background:#FEF2F2;border-color:#FCA5A5;color:#DC2626;cursor:pointer;">
          🗑 Delete Booking
        </button>
      </form>
      <script>
      function confirmDeleteBoatBooking() {
          Swal.fire({
              title: 'Delete Booking?',
              html: '<p style="margin:0;font-size:.9rem;color:#6B7280;">Booking <strong style="color:#DC2626;">{{ $booking->booking_id }}</strong> will be permanently deleted.</p>',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#DC2626',
              cancelButtonColor: '#6B7280',
              confirmButtonText: 'Yes, Delete',
              cancelButtonText: 'Cancel',
          }).then(result => {
              if (result.isConfirmed) {
                  Swal.fire({
                      title: 'Are you absolutely sure?',
                      html: '<p style="margin:0;font-size:.9rem;color:#6B7280;">This action <strong>cannot be undone</strong>. The booking will be deleted permanently.</p>',
                      icon: 'error',
                      showCancelButton: true,
                      confirmButtonColor: '#DC2626',
                      cancelButtonColor: '#6B7280',
                      confirmButtonText: '🗑 Delete Permanently',
                      cancelButtonText: 'Cancel',
                  }).then(result2 => {
                      if (result2.isConfirmed) {
                          document.getElementById('deleteBoatBookingForm').submit();
                      }
                  });
              }
          });
      }
      </script>
    </div>

  </div>{{-- /sidebar --}}
</div>{{-- /layout --}}
</div>{{-- /sv-page --}}

@endsection
