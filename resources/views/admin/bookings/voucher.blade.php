<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Voucher – {{ $booking->booking_number }}</title>
<style>
/* ══════════════════════════════════════
   VISITKASHI HOTEL BOOKING VOUCHER
   A4 · Blue Design (matches Boat Voucher)
══════════════════════════════════════ */
*{margin:0;padding:0;box-sizing:border-box;}

body{
  font-family:'Segoe UI',Arial,sans-serif;
  background:#b8ccd8;
  color:#0f172a;
  font-size:13px;
  line-height:1.5;
}

.voucher-wrap{
  width:210mm;
  min-height:297mm;
  margin:0 auto;
  background:#fff;
  box-shadow:0 6px 40px rgba(0,0,0,.22);
  position:relative;
  overflow:hidden;
  display:flex;
  flex-direction:column;
}

/* Watermark */
.watermark{
  position:absolute;
  top:50%;left:50%;
  transform:translate(-50%,-50%) rotate(-30deg);
  font-size:90px;font-weight:900;
  color:rgba(3,105,161,.04);
  letter-spacing:-2px;white-space:nowrap;
  pointer-events:none;z-index:0;text-transform:uppercase;
}

/* ── Header ──────────────────────────── */
.vh-header{
  background:linear-gradient(135deg,#0c4a6e 0%,#075985 45%,#0369a1 100%);
  padding:26px 32px;
  position:relative;overflow:hidden;flex-shrink:0;
}
.vh-header::before{
  content:'';position:absolute;top:-40px;right:-40px;
  width:160px;height:160px;border-radius:50%;
  background:rgba(255,255,255,.06);
}
.vh-header::after{
  content:'';position:absolute;bottom:-50px;left:80px;
  width:130px;height:130px;border-radius:50%;
  background:rgba(14,165,233,.08);
}

.vh-header-inner{
  display:flex;align-items:center;
  justify-content:space-between;
  position:relative;z-index:1;
}

.vh-logo-area{display:flex;align-items:center;gap:14px;}
.vh-logo-circle{
  width:54px;height:54px;border-radius:12px;
  background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);
  display:flex;align-items:center;justify-content:center;
  font-size:24px;font-weight:900;color:#fff;
}
.vh-company-name{color:#fff;font-size:20px;font-weight:900;letter-spacing:.01em;}
.vh-tagline{color:rgba(255,255,255,.7);font-size:10px;margin-top:2px;font-weight:600;}
.vh-contact-block{text-align:right;}
.vh-contact-block p{color:rgba(255,255,255,.7);font-size:10px;line-height:1.75;}
.vh-contact-block .vh-phone{color:#bae6fd;font-weight:700;font-size:11px;}
.vh-contact-block .vh-web{color:#7dd3fc;font-weight:600;}

/* ── Voucher title bar ────────────────── */
.vh-title-bar{
  background:rgba(0,0,0,.22);
  border-top:1px solid rgba(255,255,255,.1);
  padding:9px 28px;
  display:flex;align-items:center;justify-content:space-between;
  position:relative;z-index:1;flex-shrink:0;
}
.vt-label{color:rgba(255,255,255,.6);font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;}
.vt-number{color:#fff;font-size:17px;font-weight:900;letter-spacing:.05em;font-family:monospace;}
.vt-date-lbl{color:rgba(255,255,255,.5);font-size:7.5px;text-transform:uppercase;letter-spacing:.06em;text-align:center;}
.vt-date-val{color:#bae6fd;font-size:11px;font-weight:700;text-align:center;margin-top:1px;}
.vt-badge{
  padding:5px 16px;border-radius:20px;
  font-size:9px;font-weight:800;
  text-transform:uppercase;letter-spacing:.06em;border:2px solid;
  background:transparent;border-color:rgba(255,255,255,.4);color:#fff;
}

/* ── Body ────────────────────────────── */
.vh-body{
  flex:1;padding:18px 28px 10px;
  position:relative;z-index:1;
}

/* ── Section ─────────────────────────── */
.vh-section{
  margin-bottom:14px;
  border:1px solid #dde8f0;
  border-radius:10px;overflow:hidden;
}
.vh-section-head{
  background:linear-gradient(90deg,#f0f7ff,#e8f4fd);
  padding:8px 16px;border-bottom:1px solid #dde8f0;
  display:flex;align-items:center;gap:8px;
}
.vh-section-icon{
  width:24px;height:24px;border-radius:6px;
  background:#0369a1;color:#fff;
  display:flex;align-items:center;justify-content:center;font-size:11px;
}
.vh-section-title{
  font-size:10px;font-weight:800;color:#0c4a6e;
  text-transform:uppercase;letter-spacing:.07em;
}
.vh-section-body{padding:12px 16px;}

/* ── Grid layout for fields ──────────── */
.vf-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;}
.vf-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.vf-grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
.vf-full{grid-column:1 / -1;}

.vf-label{font-size:8.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px;}
.vf-value{font-size:12px;font-weight:600;color:#0f172a;}
.vf-value.big{font-size:14px;font-weight:800;}
.vf-value.muted{color:#64748b;font-weight:400;}

/* ── Service detail card ─────────────── */
.svc-card{
  background:linear-gradient(135deg,#eff6ff,#dbeafe);
  border:1.5px solid #93c5fd;border-radius:8px;
  padding:10px 14px;margin-bottom:8px;
}
.svc-type-badge{
  background:#0369a1;color:#fff;border-radius:20px;
  padding:3px 10px;font-size:9px;font-weight:700;
  text-transform:uppercase;letter-spacing:.05em;
}
.svc-name{font-size:12px;font-weight:700;color:#0f172a;}

/* ── Payment table ───────────────────── */
.pay-table{width:100%;border-collapse:collapse;}
.pay-table th{
  background:#f0f7ff;font-size:9px;font-weight:700;
  color:#0369a1;text-transform:uppercase;letter-spacing:.05em;
  padding:7px 10px;text-align:left;border-bottom:1px solid #dde8f0;
}
.pay-table td{padding:8px 10px;border-bottom:1px solid #f1f5f9;font-size:11.5px;}
.pay-table tr:last-child td{border-bottom:none;}
.pay-total-row td{background:#f0fdf4;font-weight:700;font-size:12px;}

/* ── Payment summary box ─────────────── */
.pay-summary{
  display:grid;grid-template-columns:1fr 1fr 1fr;gap:0;
  border:1px solid #dde8f0;border-radius:10px;overflow:hidden;
}
.pay-sum-item{padding:12px 14px;text-align:center;border-right:1px solid #dde8f0;}
.pay-sum-item:last-child{border-right:none;}
.pay-sum-label{font-size:9px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;}
.pay-sum-value{font-size:16px;font-weight:800;}
.pay-sum-value.green{color:#059669;}
.pay-sum-value.blue{color:#2563eb;}
.pay-sum-value.orange{color:#d97706;}
.pay-status-badge{
  display:inline-block;padding:3px 10px;border-radius:20px;
  font-size:9px;font-weight:700;text-transform:uppercase;
  letter-spacing:.04em;margin-top:4px;
}

/* ── Terms ───────────────────────────── */
.terms-list{padding:0;margin:0;display:grid;grid-template-columns:1fr 1fr;gap:1px 10px;}
.terms-list li{
  font-size:9.5px;color:#475569;
  padding:2px 0 2px 12px;position:relative;line-height:1.5;list-style:none;
}
.terms-list li::before{content:'✓';position:absolute;left:0;color:#0ea5e9;font-weight:800;}

/* ── Footer ──────────────────────────── */
.vh-footer{
  background:linear-gradient(135deg,#0c4a6e 0%,#075985 100%);
  padding:12px 28px;
  display:flex;align-items:center;justify-content:space-between;
  gap:16px;flex-shrink:0;
}
.vh-footer-left p{color:rgba(255,255,255,.6);font-size:9.5px;line-height:1.75;}
.vh-footer-left .vfl-name{color:#fff;font-weight:800;font-size:12px;margin-bottom:2px;}
.vh-footer-right{text-align:right;}
.vh-footer-right .sig-line{width:100px;border-top:1.5px solid rgba(255,255,255,.3);margin:0 0 4px auto;}
.vh-footer-right p{color:rgba(255,255,255,.5);font-size:9px;}
.vh-footer-right .sig-label{color:rgba(255,255,255,.8);font-weight:700;font-size:10px;}

.vh-stamp-area{
  width:60px;height:60px;border:2px dashed rgba(255,255,255,.2);border-radius:50%;
  display:flex;align-items:center;justify-content:center;margin:0 auto;
}
.vh-stamp-area p{color:rgba(255,255,255,.3);font-size:7px;text-align:center;}

.powered-by{
  background:#0a3d5c;text-align:center;padding:4px 0;
  color:rgba(255,255,255,.25);font-size:7.5px;letter-spacing:.06em;flex-shrink:0;
}

/* ── QR placeholder ──────────────────── */
.qr-box{
  width:62px;height:62px;background:#fff;border-radius:8px;
  padding:3px;display:flex;align-items:center;justify-content:center;overflow:hidden;
}
.qr-box img{width:100%;height:100%;}

/* ══ MOBILE RESPONSIVE ══ */
@media(max-width:700px){
  body{background:#1e293b;}
  .voucher-wrap{
    width:100% !important;
    min-height:unset !important;
    box-shadow:none;
    transform-origin:top left;
  }
  .vh-header{padding:16px 16px;}
  .vh-title-bar{padding:8px 16px;}
  .vh-body{padding:12px 14px 8px;}
  .vh-footer{padding:12px 16px;flex-wrap:wrap;gap:10px;}
  .vf-grid{grid-template-columns:1fr 1fr;}
  .vf-grid-4{grid-template-columns:1fr 1fr;}
  .pay-summary{grid-template-columns:1fr;}
  .pay-sum-item{border-right:none;border-bottom:1px solid #dde8f0;}
  .pay-sum-item:last-child{border-bottom:none;}
  .terms-list{grid-template-columns:1fr;}
  .vh-header-inner{flex-wrap:wrap;gap:10px;}
  .vh-contact-block{text-align:left;}
}

/* ══ PRINT — MUST come after mobile CSS so it wins on mobile print ══ */
@media print{
  *{
    -webkit-print-color-adjust:exact !important;
    print-color-adjust:exact !important;
    color-adjust:exact !important;
  }
  html,body{background:#fff !important;margin:0 !important;padding:0 !important;}
  .no-print{display:none !important;}
  @page{margin:0;size:A4 portrait;}

  /* Override mobile layout for print */
  .voucher-wrap{
    width:210mm !important;
    min-height:297mm !important;
    box-shadow:none !important;
    margin:0 auto !important;
    page-break-inside:avoid;
  }

  /* Explicitly re-declare every gradient/background so mobile CSS can't strip them */
  .vh-header{
    background:linear-gradient(135deg,#0c4a6e 0%,#075985 45%,#0369a1 100%) !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
  .vh-title-bar{
    background:rgba(0,0,0,.22) !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
  .vh-footer{
    background:linear-gradient(135deg,#0c4a6e 0%,#075985 100%) !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
  .vh-section-head{
    background:linear-gradient(90deg,#f0f7ff,#e8f4fd) !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
  .pay-table th{
    background:#f0f7ff !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
  .pay-total-row td{
    background:#f0fdf4 !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
  .svc-card{
    background:linear-gradient(135deg,#eff6ff,#dbeafe) !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
  .powered-by{
    background:#0a3d5c !important;
    -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;
  }
}
</style>
</head>
<body>

{{-- Print / Download Toolbar --}}
<div class="no-print" style="background:#0c4a6e;padding:10px 16px;display:flex;align-items:center;gap:10px;position:sticky;top:0;z-index:999;flex-wrap:wrap;">
  <a href="{{ route('bookings.show', $booking->id) }}"
     style="color:rgba(255,255,255,.65);font-size:12px;text-decoration:none;display:flex;align-items:center;gap:5px;white-space:nowrap;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back
  </a>
  <span style="color:rgba(255,255,255,.2);font-size:11px;font-family:monospace;flex:1;text-align:center;letter-spacing:.05em;">{{ $booking->booking_number }}</span>
  <button onclick="window.print()"
     style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;border-radius:9px;padding:10px 20px;font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;white-space:nowrap;box-shadow:0 3px 14px rgba(14,165,233,.45);">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
    Print / Save PDF
  </button>
</div>

<div class="voucher-wrap">
  <div class="watermark">VISITKASHI</div>

  {{-- ── HEADER ──────────────────────────── --}}
  <div class="vh-header">
    <div class="vh-header-inner">
      <div class="vh-logo-area">
        @if(websiteSetupValue('logo'))
          <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
               style="height:54px;border-radius:10px;background:#fff;padding:4px;" alt="Logo">
        @else
          <div class="vh-logo-circle">VK</div>
        @endif
        <div>
          <div class="vh-company-name">VISIT KASHI</div>
          <div class="vh-tagline">Varanasi's Most Trusted Travel Company</div>
          <div style="color:rgba(255,255,255,.45);font-size:9.5px;margin-top:3px;">
            B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010
          </div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:16px;">
        <div class="vh-contact-block">
          <p class="vh-phone">📞 7080109917 | 7080109918</p>
          <p class="vh-web">🌐 www.visitkashi.in</p>
          <p>✉️ info@visitkashi.in</p>
        </div>
        {{-- QR Code --}}
        @php
          $qrData = 'https://visitkashi.in/verify/' . $booking->booking_number;
        @endphp
        @if(class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode'))
        <div class="qr-box">
          {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(62)->generate($qrData) !!}
        </div>
        @else
        <div class="qr-box" style="background:#e0f2fe;border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <span style="font-size:8px;color:#0369a1;text-align:center;font-weight:700;padding:4px;">QR<br>CODE</span>
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- ── TITLE BAR ────────────────────────── --}}
  @php
    $statusColors = [
      'confirmed'   => '#22c55e',
      'completed'   => '#06b6d4',
      'in_progress' => '#f59e0b',
      'cancelled'   => '#ef4444',
      'not_started' => '#94a3b8',
    ];
    $sc = $statusColors[$booking->booking_status] ?? '#94a3b8';
  @endphp
  <div class="vh-title-bar">
    <div>
      <div class="vt-label">🏨 Hotel Booking Confirmation Voucher</div>
      <div class="vt-number">{{ $booking->booking_number }}</div>
    </div>
    <div>
      <div class="vt-date-lbl">Booking Date</div>
      <div class="vt-date-val">{{ $booking->booking_date->format('d M, Y') }}</div>
    </div>
    <div>
      <span class="vt-badge">{{ ucwords(str_replace('_',' ',$booking->booking_status)) }}</span>
    </div>
  </div>

  {{-- ── BODY ─────────────────────────────── --}}
  <div class="vh-body">

    {{-- ── BOOKING INFORMATION ─────────────── --}}
    <div class="vh-section">
      <div class="vh-section-head">
        <div class="vh-section-icon">📋</div>
        <div class="vh-section-title">Booking Information</div>
      </div>
      <div class="vh-section-body">
        <div class="vf-grid-4">
          <div class="vf-item">
            <div class="vf-label">Voucher Number</div>
            <div class="vf-value big" style="color:#0369a1;">{{ $booking->booking_number }}</div>
          </div>
          <div class="vf-item">
            <div class="vf-label">Booking Date</div>
            <div class="vf-value">{{ $booking->booking_date->format('d M, Y') }}</div>
          </div>
          <div class="vf-item">
            <div class="vf-label">Service Type</div>
            <div class="vf-value">
              @php
                $firstItem = $booking->quotation?->items?->first();
                $stName = optional($firstItem?->serviceTemplate?->serviceType)->name
                       ?? optional($firstItem?->serviceType)->name ?? 'Travel Service';
              @endphp
              {{ $stName }}
            </div>
          </div>
          <div class="vf-item">
            <div class="vf-label">Status</div>
            <div class="vf-value">
              <span style="background:{{ $sc }}20;color:{{ $sc }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;border:1px solid {{ $sc }}40;">
                {{ ucwords(str_replace('_',' ',$booking->booking_status)) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── GUEST INFORMATION ────────────────── --}}
    @php $lead = $booking->lead; @endphp
    @if($lead)
    <div class="vh-section">
      <div class="vh-section-head">
        <div class="vh-section-icon">👤</div>
        <div class="vh-section-title">Guest Information</div>
      </div>
      <div class="vh-section-body">
        <div class="vf-grid">
          <div class="vf-item">
            <div class="vf-label">Guest Name</div>
            <div class="vf-value big">{{ $lead->guest_name }}</div>
          </div>
          <div class="vf-item">
            <div class="vf-label">Mobile Number</div>
            <div class="vf-value">{{ $lead->contact ?? '—' }}</div>
          </div>
          <div class="vf-item">
            <div class="vf-label">Email Address</div>
            <div class="vf-value muted">{{ $lead->email ?? '—' }}</div>
          </div>
          <div class="vf-item">
            <div class="vf-label">Total Guests (PAX)</div>
            <div class="vf-value">{{ $lead->pax ?? '—' }} Person(s)</div>
          </div>
          <div class="vf-item">
            <div class="vf-label">Country</div>
            <div class="vf-value">{{ $lead->country ?? 'India' }}</div>
          </div>
          @if($lead->booking_start_date)
          <div class="vf-item">
            <div class="vf-label">Check-in Date</div>
            <div class="vf-value">{{ \Carbon\Carbon::parse($lead->booking_start_date)->format('d M, Y') }}</div>
          </div>
          @endif
          @if($lead->booking_end_date)
          <div class="vf-item">
            <div class="vf-label">Check-out Date</div>
            <div class="vf-value">{{ \Carbon\Carbon::parse($lead->booking_end_date)->format('d M, Y') }}</div>
          </div>
          @endif
        </div>
        @if($lead->short_plan)
        <div style="margin-top:12px;background:#F8FAFC;border-radius:8px;padding:10px 14px;border-left:3px solid #0369a1;">
          <div class="vf-label" style="margin-bottom:4px;">Booking Summary</div>
          <div style="font-size:11.5px;color:#334155;line-height:1.6;">{{ $lead->short_plan }}</div>
        </div>
        @endif
      </div>
    </div>
    @endif

    {{-- ── SERVICE DETAILS ──────────────────── --}}
    @if($booking->quotation && $booking->quotation->items->count())
    <div class="vh-section">
      <div class="vh-section-head">
        <div class="vh-section-icon">🛎</div>
        <div class="vh-section-title">Service Details</div>
      </div>
      <div class="vh-section-body">
        <table class="pay-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Service</th>
              <th>Type</th>
              <th>Date</th>
              <th>Qty</th>
              <th style="text-align:right;">Rate</th>
              <th style="text-align:right;">Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($booking->quotation->items as $i => $item)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td style="font-weight:600;">{{ $item->serviceTemplate?->name ?? 'Custom Service' }}</td>
              <td>
                <span style="background:#dbeafe;color:#0369a1;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;">
                  {{ $item->serviceTemplate?->serviceType?->name ?? $item->serviceType?->name ?? '—' }}
                </span>
              </td>
              <td>{{ $item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d M, Y') : '—' }}</td>
              <td>{{ $item->quantity }}</td>
              <td style="text-align:right;">₹{{ number_format($item->unit_price, 2) }}</td>
              <td style="text-align:right;font-weight:700;">₹{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr class="pay-total-row">
              <td colspan="5"></td>
              <td style="font-size:11px;color:#064e3b;">Subtotal</td>
              <td style="text-align:right;color:#065F46;">₹{{ number_format($booking->quotation->subtotal ?? $booking->total_amount, 2) }}</td>
            </tr>
            @if(($booking->quotation->discount_amount ?? 0) > 0)
            <tr style="background:#FFFBEB;">
              <td colspan="5"></td>
              <td style="font-size:11px;color:#92400e;">Discount</td>
              <td style="text-align:right;color:#d97706;">- ₹{{ number_format($booking->quotation->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr style="background:#f0fdf4;border-top:2px solid #059669;">
              <td colspan="5"></td>
              <td style="font-weight:800;color:#064e3b;font-size:13px;">TOTAL</td>
              <td style="text-align:right;font-weight:800;color:#059669;font-size:15px;">₹{{ number_format($booking->total_amount, 2) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    @endif

    {{-- ── PAYMENT SUMMARY ──────────────────── --}}
    <div class="vh-section">
      <div class="vh-section-head">
        <div class="vh-section-icon">💳</div>
        <div class="vh-section-title">Payment Summary</div>
      </div>
      <div class="vh-section-body">
        <div class="pay-summary">
          <div class="pay-sum-item">
            <div class="pay-sum-label">Total Amount</div>
            <div class="pay-sum-value blue">₹{{ number_format($booking->total_amount, 2) }}</div>
          </div>
          <div class="pay-sum-item">
            <div class="pay-sum-label">Amount Paid</div>
            <div class="pay-sum-value green">₹{{ number_format($booking->paid_amount, 2) }}</div>
            @php
              $payPct = $booking->total_amount > 0 ? round($booking->paid_amount / $booking->total_amount * 100) : 0;
              $payLabel = $booking->pending_amount <= 0 ? 'Fully Paid' : ($booking->paid_amount > 0 ? 'Partially Paid' : 'Unpaid');
              $payBg    = $booking->pending_amount <= 0 ? '#d1fae5' : ($booking->paid_amount > 0 ? '#fef3c7' : '#fee2e2');
              $payC     = $booking->pending_amount <= 0 ? '#065f46' : ($booking->paid_amount > 0 ? '#92400e' : '#991b1b');
            @endphp
            <span class="pay-status-badge" style="background:{{ $payBg }};color:{{ $payC }};">{{ $payLabel }}</span>
          </div>
          <div class="pay-sum-item">
            <div class="pay-sum-label">Balance Due</div>
            <div class="pay-sum-value orange">₹{{ number_format($booking->pending_amount, 2) }}</div>
          </div>
        </div>

        {{-- Payment receipts --}}
        @if($booking->payments && $booking->payments->count())
        <div style="margin-top:14px;">
          <div style="font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Payment History</div>
          <table class="pay-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Method</th>
                <th>Reference</th>
                <th style="text-align:right;">Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach($booking->payments as $pmt)
              <tr>
                <td>{{ \Carbon\Carbon::parse($pmt->payment_date)->format('d M, Y') }}</td>
                <td>{{ ucwords(str_replace('_',' ',$pmt->payment_method ?? 'Cash')) }}</td>
                <td style="color:#94a3b8;">{{ $pmt->reference_number ?? '—' }}</td>
                <td style="text-align:right;font-weight:700;color:#059669;">₹{{ number_format($pmt->amount, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>

    {{-- ── TERMS ────────────────────────────── --}}
    <div class="vh-section">
      <div class="vh-section-head">
        <div class="vh-section-icon">📜</div>
        <div class="vh-section-title">Terms & Conditions</div>
      </div>
      <div class="vh-section-body">
        <ul class="terms-list">
          <li>Please carry a valid government-issued photo ID (Aadhaar, Passport, Voter ID) at the time of service.</li>
          <li>Guests are requested to report <strong>15 minutes before</strong> the scheduled service time.</li>
          <li>Balance payment must be cleared before the commencement of service.</li>
          <li>Management reserves the right to modify schedule due to weather conditions or government restrictions.</li>
          <li>No refund for no-shows. Cancellation policy applies as per booking terms.</li>
          <li>This voucher is non-transferable and valid only for the named guest(s).</li>
          <li>For support, contact us at <strong>7080109917</strong> or <strong>info@visitkashi.in</strong></li>
        </ul>
      </div>
    </div>

  </div>{{-- /vh-body --}}

  {{-- ── FOOTER ───────────────────────────── --}}
  <div class="vh-footer">
    <div class="vh-footer-left">
      <div class="vfl-name">Thank you for choosing Visit Kashi!</div>
      <p>Customer Support: 7080109917 &nbsp;|&nbsp; 7080109918</p>
      <p>info@visitkashi.in &nbsp;|&nbsp; www.visitkashi.in</p>
      <p style="margin-top:4px;">B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010</p>
    </div>
    <div style="display:flex;align-items:center;gap:24px;">
      <div style="text-align:center;">
        <div class="vh-stamp-area">
          <p>OFFICIAL<br>STAMP</p>
        </div>
      </div>
      <div class="vh-footer-right">
        <div style="height:36px;"></div>
        <div class="sig-line"></div>
        <div class="sig-label">Authorized Signatory</div>
        <p>Visit Kashi Travel Services</p>
      </div>
    </div>
  </div>
  <div class="powered-by">
    Powered by VisitKashi CRM &nbsp;·&nbsp; Generated on {{ now()->format('d M Y, h:i A') }}
  </div>

</div>{{-- /voucher-wrap --}}

</body>
</html>
