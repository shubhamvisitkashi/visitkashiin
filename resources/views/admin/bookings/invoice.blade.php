<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation #{{ $booking->booking_number }} | Visit Kashi</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',Arial,sans-serif; color:#0f172a; background:#f1f5f9; font-size:13px; line-height:1.5; }

        .page-wrap { max-width:820px; margin:24px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 32px rgba(0,0,0,.12); }

        /* ── Header ── */
        .inv-header { background:linear-gradient(135deg,#0f3460 0%,#1a5276 60%,#2980b9 100%); padding:28px 32px; position:relative; overflow:hidden; }
        .inv-header::after { content:''; position:absolute; top:-60px; right:-60px; width:200px; height:200px; background:rgba(255,255,255,.06); border-radius:50%; }
        .inv-header::before { content:''; position:absolute; bottom:-40px; left:40%; width:150px; height:150px; background:rgba(255,255,255,.04); border-radius:50%; }
        .inv-header-inner { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; position:relative; z-index:1; }
        .inv-logo img { height:52px; width:auto; object-fit:contain; filter:brightness(0) invert(1); }
        .inv-logo-fallback { font-size:1.4rem; font-weight:800; color:#fff; letter-spacing:-.02em; }
        .inv-title-block { text-align:right; }
        .inv-title { font-size:1.6rem; font-weight:800; color:#fff; letter-spacing:-.02em; line-height:1; }
        .inv-subtitle { font-size:.72rem; font-weight:600; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.1em; margin-top:3px; }
        .inv-badge { display:inline-block; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); color:#fff; font-size:.72rem; font-weight:700; padding:4px 12px; border-radius:20px; margin-top:8px; }
        .inv-num { font-size:1rem; font-weight:700; color:rgba(255,255,255,.9); margin-top:4px; }

        /* ── Confirmed banner ── */
        .inv-confirmed { background:#059669; padding:10px 32px; display:flex; align-items:center; gap:8px; }
        .inv-confirmed-icon { width:20px; height:20px; background:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.7rem; color:#059669; font-weight:800; flex-shrink:0; }
        .inv-confirmed-text { font-size:.82rem; font-weight:700; color:#fff; }
        .inv-confirmed-date { margin-left:auto; font-size:.75rem; color:rgba(255,255,255,.8); }

        /* ── Info grid ── */
        .inv-info { display:grid; grid-template-columns:1fr 1fr; gap:0; border-bottom:1px solid #e2e8f0; }
        .inv-info-col { padding:20px 32px; }
        .inv-info-col:first-child { border-right:1px solid #e2e8f0; }
        .inv-info-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:10px; }
        .inv-info-row { display:flex; justify-content:space-between; align-items:baseline; padding:4px 0; border-bottom:1px dashed #f1f5f9; }
        .inv-info-row:last-child { border-bottom:none; }
        .inv-info-key { font-size:.78rem; color:#64748b; font-weight:500; }
        .inv-info-val { font-size:.82rem; color:#0f172a; font-weight:600; text-align:right; }
        .inv-info-val.highlight { color:#0f3460; font-weight:700; }

        /* ── Package section ── */
        .inv-section { padding:20px 32px; border-bottom:1px solid #e2e8f0; }
        .inv-section-title { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:12px; display:flex; align-items:center; gap:6px; }
        .inv-section-title::before { content:''; display:inline-block; width:3px; height:14px; background:linear-gradient(to bottom,#0f3460,#2980b9); border-radius:2px; }

        .inv-pkg-card { background:linear-gradient(135deg,#EFF6FF,#DBEAFE); border:1px solid #BFDBFE; border-radius:12px; padding:16px 20px; }
        .inv-pkg-name { font-size:1rem; font-weight:800; color:#1e3a8a; margin-bottom:4px; }
        .inv-pkg-sub { font-size:.78rem; color:#3b82f6; font-weight:500; }
        .inv-pkg-meta { display:flex; flex-wrap:wrap; gap:8px; margin-top:12px; }
        .inv-pkg-tag { display:inline-flex; align-items:center; gap:4px; background:#fff; border:1px solid #BFDBFE; border-radius:6px; padding:3px 10px; font-size:.72rem; color:#1e40af; font-weight:600; }

        /* Services table */
        .inv-table { width:100%; border-collapse:collapse; margin-top:12px; }
        .inv-table thead th { background:#f8fafc; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:8px 12px; text-align:left; border-bottom:2px solid #e2e8f0; }
        .inv-table thead th:last-child { text-align:right; }
        .inv-table tbody td { padding:10px 12px; border-bottom:1px solid #f1f5f9; font-size:.82rem; color:#334155; vertical-align:top; }
        .inv-table tbody td:last-child { text-align:right; font-weight:600; color:#0f172a; }
        .inv-table tbody tr:last-child td { border-bottom:none; }
        .inv-svc-name { font-weight:600; color:#0f172a; }
        .inv-svc-sub { font-size:.72rem; color:#94a3b8; margin-top:1px; }

        /* Tour plan */
        .inv-tour-plan { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:16px 18px; font-size:.82rem; line-height:1.6; color:#334155; }
        .inv-tour-plan h3 { font-size:.84rem; font-weight:700; color:#0f172a; margin-top:10px; margin-bottom:4px; padding-bottom:3px; border-bottom:1px solid #e2e8f0; }
        .inv-tour-plan h3:first-child { margin-top:0; }
        .inv-tour-plan p { margin-bottom:6px; }
        .inv-tour-plan ul,.inv-tour-plan ol { margin-left:16px; margin-bottom:6px; }
        .inv-tour-plan li { margin-bottom:3px; }

        /* ── PAYMENT SUMMARY — Hero section ── */
        .inv-payment { background:#0f172a; padding:24px 32px; }
        .inv-payment-title { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.5); margin-bottom:16px; }
        .inv-pay-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
        .inv-pay-card { border-radius:12px; padding:16px 18px; }
        .inv-pay-card.total { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); }
        .inv-pay-card.paid  { background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.3); }
        .inv-pay-card.due   { background:rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.3); }
        .inv-pay-card.due.zero { background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.3); }
        .inv-pay-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; margin-bottom:6px; }
        .inv-pay-card.total .inv-pay-label { color:rgba(255,255,255,.55); }
        .inv-pay-card.paid  .inv-pay-label { color:#6ee7b7; }
        .inv-pay-card.due   .inv-pay-label { color:#fca5a5; }
        .inv-pay-card.due.zero .inv-pay-label { color:#6ee7b7; }
        .inv-pay-amount { font-size:1.3rem; font-weight:800; line-height:1; }
        .inv-pay-card.total .inv-pay-amount { color:#fff; }
        .inv-pay-card.paid  .inv-pay-amount { color:#10b981; }
        .inv-pay-card.due   .inv-pay-amount { color:#ef4444; }
        .inv-pay-card.due.zero .inv-pay-amount { color:#10b981; }
        .inv-pay-sub { font-size:.68rem; margin-top:4px; }
        .inv-pay-card.total .inv-pay-sub { color:rgba(255,255,255,.4); }
        .inv-pay-card.paid  .inv-pay-sub { color:rgba(110,231,183,.7); }
        .inv-pay-card.due   .inv-pay-sub { color:rgba(252,165,165,.7); }
        .inv-pay-card.due.zero .inv-pay-sub { color:rgba(110,231,183,.7); }

        /* Payment history */
        .inv-pay-history { margin-top:16px; }
        .inv-pay-history-title { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.4); margin-bottom:8px; }
        .inv-pay-row { display:flex; justify-content:space-between; align-items:center; padding:7px 12px; background:rgba(255,255,255,.05); border-radius:8px; margin-bottom:4px; }
        .inv-pay-row-left { display:flex; flex-direction:column; gap:1px; }
        .inv-pay-row-date { font-size:.72rem; color:rgba(255,255,255,.6); }
        .inv-pay-row-method { font-size:.68rem; color:rgba(255,255,255,.35); }
        .inv-pay-row-amt { font-size:.88rem; font-weight:700; color:#10b981; }

        /* ── Terms ── */
        .inv-terms { padding:20px 32px; border-bottom:1px solid #e2e8f0; }
        .inv-terms-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .inv-term-item { display:flex; gap:8px; }
        .inv-term-icon { font-size:.9rem; flex-shrink:0; margin-top:1px; }
        .inv-term-text strong { font-size:.78rem; font-weight:700; color:#0f172a; display:block; margin-bottom:2px; }
        .inv-term-text span { font-size:.73rem; color:#64748b; line-height:1.4; }

        /* ── Footer ── */
        .inv-footer { background:#f8fafc; padding:18px 32px; display:flex; align-items:center; justify-content:space-between; gap:16px; border-top:1px solid #e2e8f0; }
        .inv-footer-left { font-size:.75rem; color:#64748b; line-height:1.5; }
        .inv-footer-left strong { color:#0f172a; font-weight:700; display:block; margin-bottom:2px; }
        .inv-footer-right { text-align:right; }
        .inv-footer-site { font-size:.85rem; font-weight:800; color:#0f3460; }
        .inv-footer-tag { font-size:.7rem; color:#94a3b8; margin-top:2px; }
        .inv-seal { display:inline-flex; align-items:center; gap:5px; background:#DCFCE7; border:1px solid #BBF7D0; border-radius:20px; padding:4px 12px; font-size:.7rem; font-weight:700; color:#166534; margin-top:6px; }

        /* ── Print button ── */
        .print-bar { position:fixed; bottom:20px; right:20px; display:flex; gap:8px; z-index:999; }
        .btn-print { background:#0f3460; color:#fff; border:none; padding:12px 24px; border-radius:10px; font-weight:700; font-size:.85rem; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 4px 14px rgba(15,52,96,.35); transition:opacity .2s; }
        .btn-print:hover { opacity:.88; }
        .btn-wa { background:#25d366; color:#fff; border:none; padding:12px 20px; border-radius:10px; font-weight:700; font-size:.85rem; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 4px 14px rgba(37,211,102,.35); transition:opacity .2s; text-decoration:none; }
        .btn-wa:hover { opacity:.88; color:#fff; }

        @media print {
            body { background:#fff; }
            .page-wrap { box-shadow:none; border-radius:0; margin:0; max-width:100%; }
            .print-bar { display:none; }
        }
    </style>
</head>
<body>

@php
    $paidAmt = $booking->payments_sum_amount ?? $booking->paid_amount ?? 0;
    $totalAmt = $booking->total_amount ?? 0;
    $dueAmt = max(0, $totalAmt - $paidAmt);
    $isFullyPaid = $dueAmt <= 0;

    // Get package/service name from quotation items or short_plan
    $packageName = null;
    $serviceItems = collect();
    if ($booking->quotation && $booking->quotation->items && $booking->quotation->items->count() > 0) {
        $serviceItems = $booking->quotation->items;
        $packageName = $serviceItems->first()->serviceTemplate->name ?? null;
    }
    if (!$packageName && $booking->lead && $booking->lead->short_plan) {
        $packageName = strip_tags($booking->lead->short_plan);
        $packageName = Str::limit($packageName, 80);
    }
    if (!$packageName) $packageName = 'Tour Package';

    // Guest details
    $guestName = $booking->lead->guest_name ?? 'Guest';
    $contact   = $booking->lead->contact ?? $booking->lead->phone ?? '—';
    $pax       = $booking->lead->pax ?? $booking->lead->no_of_person ?? '—';
    $startDate = $booking->lead->booking_start_date ? \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M Y') : $booking->booking_date->format('d M Y');
    $endDate   = ($booking->lead->booking_end_date && $booking->lead->booking_end_date != $booking->lead->booking_start_date)
                    ? \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M Y') : null;

    $cPhone = preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917');
@endphp

<div class="page-wrap">

    {{-- ── Header ── --}}
    <div class="inv-header">
        <div class="inv-header-inner">
            <div class="inv-logo">
                @if(websiteSetupValue('logo'))
                    <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('logo')) }}" alt="Visit Kashi">
                @else
                    <div class="inv-logo-fallback">visitKashi</div>
                @endif
            </div>
            <div class="inv-title-block">
                <div class="inv-title">Booking Confirmation</div>
                <div class="inv-subtitle">Official Document</div>
                <div class="inv-num">#{{ $booking->booking_number }}</div>
                <div class="inv-badge">🗺️ Tour Package</div>
            </div>
        </div>
    </div>

    {{-- ── Confirmed banner ── --}}
    <div class="inv-confirmed">
        <span class="inv-confirmed-icon">✓</span>
        <span class="inv-confirmed-text">Booking Confirmed — Thank you for choosing Visit Kashi!</span>
        <span class="inv-confirmed-date">Issued: {{ $booking->booking_date->format('d M Y') }}</span>
    </div>

    {{-- ── Guest & Booking Info ── --}}
    <div class="inv-info">
        <div class="inv-info-col">
            <div class="inv-info-label">Guest Information</div>
            <div class="inv-info-row">
                <span class="inv-info-key">Full Name</span>
                <span class="inv-info-val highlight">{{ $guestName }}</span>
            </div>
            <div class="inv-info-row">
                <span class="inv-info-key">Contact</span>
                <span class="inv-info-val">{{ $contact }}</span>
            </div>
            @if($booking->lead && $booking->lead->email)
            <div class="inv-info-row">
                <span class="inv-info-key">Email</span>
                <span class="inv-info-val">{{ $booking->lead->email }}</span>
            </div>
            @endif
            <div class="inv-info-row">
                <span class="inv-info-key">No. of Persons</span>
                <span class="inv-info-val">{{ $pax }}</span>
            </div>
        </div>
        <div class="inv-info-col">
            <div class="inv-info-label">Booking Details</div>
            <div class="inv-info-row">
                <span class="inv-info-key">Booking ID</span>
                <span class="inv-info-val highlight">#{{ $booking->booking_number }}</span>
            </div>
            <div class="inv-info-row">
                <span class="inv-info-key">Travel Date</span>
                <span class="inv-info-val">{{ $startDate }}{{ $endDate ? ' – '.$endDate : '' }}</span>
            </div>
            <div class="inv-info-row">
                <span class="inv-info-key">Booked On</span>
                <span class="inv-info-val">{{ $booking->booking_date->format('d M Y') }}</span>
            </div>
            <div class="inv-info-row">
                <span class="inv-info-key">Status</span>
                <span class="inv-info-val" style="color:#059669;">✓ Confirmed</span>
            </div>
        </div>
    </div>

    {{-- ── Package Details ── --}}
    <div class="inv-section">
        <div class="inv-section-title">Package Details</div>
        <div class="inv-pkg-card">
            <div class="inv-pkg-name">{{ $packageName }}</div>
            <div class="inv-pkg-sub">Tour Package · Visit Kashi</div>
            <div class="inv-pkg-meta">
                @if($startDate)
                <span class="inv-pkg-tag">📅 {{ $startDate }}{{ $endDate ? ' – '.$endDate : '' }}</span>
                @endif
                @if($pax && $pax !== '—')
                <span class="inv-pkg-tag">👥 {{ $pax }} Persons</span>
                @endif
                @if($booking->lead && $booking->lead->source)
                <span class="inv-pkg-tag">📌 {{ $booking->lead->source }}</span>
                @endif
                <span class="inv-pkg-tag">✓ Confirmed</span>
            </div>
        </div>
    </div>

    {{-- ── Services (if available) ── --}}
    @if($serviceItems->count() > 0)
    <div class="inv-section">
        <div class="inv-section-title">Services Included</div>
        <table class="inv-table">
            <thead>
                <tr>
                    <th style="width:50%">Service / Item</th>
                    <th style="text-align:center;">Type</th>
                    <th style="text-align:center;">Qty</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviceItems as $item)
                <tr>
                    <td>
                        <div class="inv-svc-name">{{ $item->serviceTemplate->name ?? 'Service' }}</div>
                        @if($item->notes)
                        <div class="inv-svc-sub">{{ $item->notes }}</div>
                        @endif
                    </td>
                    <td style="text-align:center;font-size:.74rem;color:#64748b;">{{ $item->serviceTemplate->serviceType->name ?? '—' }}</td>
                    <td style="text-align:center;">{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->selling_price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── Tour Plan / Itinerary ── --}}
    @if($booking->lead && ($booking->lead->plan_detail || $booking->lead->short_plan))
    <div class="inv-section">
        <div class="inv-section-title">Tour Plan / Itinerary</div>
        <div class="inv-tour-plan">
            @if($booking->lead->plan_detail)
                {!! $booking->lead->plan_detail !!}
            @elseif($booking->lead->short_plan)
                {!! $booking->lead->short_plan !!}
            @endif
        </div>
    </div>
    @elseif($booking->quotation && $booking->quotation->itinerary)
    <div class="inv-section">
        <div class="inv-section-title">Tour Plan / Itinerary</div>
        <div class="inv-tour-plan">{!! $booking->quotation->itinerary !!}</div>
    </div>
    @endif

    {{-- ── PAYMENT SUMMARY ── --}}
    <div class="inv-payment">
        <div class="inv-payment-title">Payment Summary</div>
        <div class="inv-pay-grid">
            <div class="inv-pay-card total">
                <div class="inv-pay-label">Package Amount</div>
                <div class="inv-pay-amount">₹{{ number_format($totalAmt, 2) }}</div>
                <div class="inv-pay-sub">Total booking value</div>
            </div>
            <div class="inv-pay-card paid">
                <div class="inv-pay-label">Amount Paid</div>
                <div class="inv-pay-amount">₹{{ number_format($paidAmt, 2) }}</div>
                <div class="inv-pay-sub">Received from guest</div>
            </div>
            <div class="inv-pay-card due {{ $isFullyPaid ? 'zero' : '' }}">
                <div class="inv-pay-label">{{ $isFullyPaid ? 'Balance' : 'Due Amount' }}</div>
                <div class="inv-pay-amount">₹{{ number_format($dueAmt, 2) }}</div>
                <div class="inv-pay-sub">{{ $isFullyPaid ? '✓ Fully Paid' : 'Pending payment' }}</div>
            </div>
        </div>

        {{-- Payment history --}}
        @if($booking->payments && $booking->payments->count() > 0)
        <div class="inv-pay-history">
            <div class="inv-pay-history-title">Payment History</div>
            @foreach($booking->payments as $pay)
            <div class="inv-pay-row">
                <div class="inv-pay-row-left">
                    <span class="inv-pay-row-date">{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}</span>
                    <span class="inv-pay-row-method">{{ $pay->payment_method ?? $pay->paymentAccount->name ?? 'Cash' }}</span>
                </div>
                <span class="inv-pay-row-amt">+ ₹{{ number_format($pay->amount, 2) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── Terms & Conditions ── --}}
    <div class="inv-terms">
        <div class="inv-section-title" style="margin-bottom:14px;">Terms & Conditions</div>
        @php
            $serviceTypeTerms = collect();
            if ($booking->quotation && $booking->quotation->items) {
                $stIds = $booking->quotation->items->pluck('serviceTemplate.service_type_id')->filter()->unique();
                $serviceTypeTerms = \App\Models\ServiceType::whereIn('id',$stIds)->whereNotNull('terms_conditions')->where('terms_conditions','!=','')->get();
            }
        @endphp
        @if($serviceTypeTerms->count() > 0)
            @foreach($serviceTypeTerms as $st)
            <div style="margin-bottom:12px;font-size:.78rem;color:#334155;">{!! $st->terms_conditions !!}</div>
            @endforeach
        @elseif($booking->quotation && $booking->quotation->terms_conditions)
            <div style="font-size:.78rem;color:#334155;">{!! $booking->quotation->terms_conditions !!}</div>
        @else
        <div class="inv-terms-grid">
            <div class="inv-term-item">
                <span class="inv-term-icon">💳</span>
                <div class="inv-term-text">
                    <strong>Payment Terms</strong>
                    <span>Balance must be settled before journey begins. Advance confirms reservation.</span>
                </div>
            </div>
            <div class="inv-term-item">
                <span class="inv-term-icon">🔄</span>
                <div class="inv-term-text">
                    <strong>Cancellation Policy</strong>
                    <span>7+ days: refundable. Within 48 hours: non-refundable as per policy.</span>
                </div>
            </div>
            <div class="inv-term-item">
                <span class="inv-term-icon">🛡️</span>
                <div class="inv-term-text">
                    <strong>Liability</strong>
                    <span>Visit Kashi is not liable for loss of personal property. Travel insurance recommended.</span>
                </div>
            </div>
            <div class="inv-term-item">
                <span class="inv-term-icon">⚠️</span>
                <div class="inv-term-text">
                    <strong>Force Majeure</strong>
                    <span>Not responsible for delays due to natural disasters, govt. restrictions, or strikes.</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Footer ── --}}
    <div class="inv-footer">
        <div class="inv-footer-left">
            <strong>{{ websiteSetupValue('site_title') ?? 'Visit Kashi' }}</strong>
            {{ websiteSetupValue('address') ?? 'Varanasi, Uttar Pradesh' }}<br>
            📞 {{ websiteSetupValue('contact_number') ?? '7080109917' }} &nbsp;|&nbsp;
            ✉️ {{ websiteSetupValue('email') ?? 'info@visitkashi.in' }}
        </div>
        <div class="inv-footer-right">
            <div class="inv-footer-site">www.visitkashi.in</div>
            <div class="inv-footer-tag">Most Trusted Travel Company Since 2018</div>
            <div class="inv-seal">✓ Verified Booking</div>
        </div>
    </div>

</div>

{{-- ── Action Buttons ── --}}
<div class="print-bar">
    <a href="https://wa.me/91{{ $cPhone }}?text={{ urlencode('Hi, sharing Booking Confirmation #'.$booking->booking_number.' for '.$guestName.'. Total: ₹'.number_format($totalAmt).' | Paid: ₹'.number_format($paidAmt).' | Due: ₹'.number_format($dueAmt)) }}"
       target="_blank" class="btn-wa">
        💬 WhatsApp
    </a>
    <button class="btn-print" onclick="window.print()">
        🖨️ Print / PDF
    </button>
</div>

</body>
</html>
