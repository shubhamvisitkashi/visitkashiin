@php
    $brand = invoiceBrand($booking->lead?->leadSource?->name);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation #{{ $booking->booking_number }} | {{ $brand['name'] }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',Arial,sans-serif; color:#0f172a; background:#e2e8f0; font-size:13px; line-height:1.5; }

        .page-wrap { max-width:840px; margin:28px auto; background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 8px 48px rgba(0,0,0,.16); }

        /* ══ HEADER ══ */
        .inv-header {
            background: linear-gradient(135deg, #0a2342 0%, #0f3460 45%, #1a5276 100%);
            padding: 30px 36px 26px;
            position: relative; overflow: hidden;
        }
        .inv-header::after  { content:''; position:absolute; top:-70px; right:-70px; width:240px; height:240px; background:rgba(255,255,255,.05); border-radius:50%; }
        .inv-header::before { content:''; position:absolute; bottom:-50px; left:30%; width:180px; height:180px; background:rgba(41,128,185,.12); border-radius:50%; }
        .inv-header-inner { display:flex; justify-content:space-between; align-items:center; gap:20px; position:relative; z-index:1; }

        .inv-logo-area { display:flex; align-items:center; gap:14px; }
        .inv-logo-area img { height:54px; width:auto; object-fit:contain; }
        .inv-logo-fallback { font-size:1.6rem; font-weight:900; color:#fff; letter-spacing:-.03em; }
        .inv-company-name { color:#fff; font-size:1rem; font-weight:800; line-height:1.2; }
        .inv-company-sub  { color:rgba(255,255,255,.55); font-size:.72rem; margin-top:2px; }
        .inv-company-addr { color:rgba(255,255,255,.38); font-size:.68rem; margin-top:2px; }

        .inv-title-block { text-align:right; }
        .inv-doc-label { font-size:.65rem; font-weight:700; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:.14em; }
        .inv-title { font-size:1.8rem; font-weight:900; color:#fff; letter-spacing:-.03em; line-height:1; margin-top:2px; }
        .inv-num { font-size:.95rem; font-weight:700; color:#7dd3fc; margin-top:6px; font-family:monospace; }
        .inv-type-badge {
            display:inline-flex; align-items:center; gap:5px;
            background:rgba(255,255,255,.13); border:1px solid rgba(255,255,255,.22);
            color:#fff; font-size:.7rem; font-weight:700; padding:4px 13px; border-radius:20px; margin-top:8px;
        }

        /* ══ STATUS BANNER ══ */
        .inv-banner {
            background: linear-gradient(90deg, #059669, #047857);
            padding: 11px 36px; display:flex; align-items:center; gap:10px;
        }
        .inv-banner-icon { width:22px; height:22px; background:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.7rem; color:#059669; font-weight:900; flex-shrink:0; }
        .inv-banner-text { font-size:.82rem; font-weight:700; color:#fff; }
        .inv-banner-date { margin-left:auto; font-size:.74rem; color:rgba(255,255,255,.75); white-space:nowrap; }

        /* ══ GUEST + BOOKING INFO ══ */
        .inv-info { display:grid; grid-template-columns:1fr 1fr; border-bottom:1px solid #e2e8f0; }
        .inv-info-col { padding:22px 36px; }
        .inv-info-col:first-child { border-right:1px solid #e2e8f0; }
        .inv-col-label { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; margin-bottom:12px; display:flex; align-items:center; gap:6px; }
        .inv-col-label::before { content:''; display:inline-block; width:3px; height:12px; border-radius:2px; background:linear-gradient(to bottom,#0f3460,#2980b9); }

        .inv-row { display:flex; justify-content:space-between; align-items:center; padding:6px 0; border-bottom:1px dashed #f1f5f9; gap:12px; }
        .inv-row:last-child { border-bottom:none; }
        .inv-row-key { font-size:.76rem; color:#64748b; font-weight:500; white-space:nowrap; }
        .inv-row-val { font-size:.82rem; color:#0f172a; font-weight:600; text-align:right; }
        .inv-row-val.accent { color:#0f3460; font-weight:800; font-size:.88rem; }
        .inv-row-val.green  { color:#059669; font-weight:700; }
        .inv-row-val.mono   { font-family:monospace; color:#1e40af; font-weight:700; }
        .inv-pax-pills { display:flex; gap:5px; flex-wrap:wrap; justify-content:flex-end; }
        .inv-pax-pill { display:inline-flex; align-items:center; gap:3px; background:#EFF6FF; border:1px solid #BFDBFE; color:#1e40af; border-radius:20px; padding:2px 9px; font-size:.7rem; font-weight:700; }

        /* ══ SECTION ══ */
        .inv-section { padding:22px 36px; border-bottom:1px solid #e2e8f0; }
        .inv-sec-title { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; margin-bottom:14px; display:flex; align-items:center; gap:6px; }
        .inv-sec-title::before { content:''; display:inline-block; width:3px; height:12px; border-radius:2px; background:linear-gradient(to bottom,#0f3460,#2980b9); }

        /* Package card */
        .inv-pkg-card { background:linear-gradient(135deg,#EFF6FF,#DBEAFE); border:1.5px solid #BFDBFE; border-radius:14px; padding:18px 22px; }
        .inv-pkg-name { font-size:1.05rem; font-weight:800; color:#1e3a8a; margin-bottom:5px; }
        .inv-pkg-sub  { font-size:.76rem; color:#3b82f6; font-weight:600; }
        .inv-pkg-meta { display:flex; flex-wrap:wrap; gap:7px; margin-top:12px; }
        .inv-pkg-tag  { display:inline-flex; align-items:center; gap:4px; background:#fff; border:1px solid #BFDBFE; border-radius:7px; padding:3px 11px; font-size:.71rem; color:#1e40af; font-weight:700; }

        /* Services table */
        .inv-table { width:100%; border-collapse:collapse; margin-top:14px; border-radius:10px; overflow:hidden; }
        .inv-table thead th { background:#0f3460; color:rgba(255,255,255,.8); font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:9px 14px; text-align:left; }
        .inv-table thead th:last-child { text-align:right; }
        .inv-table tbody td { padding:11px 14px; border-bottom:1px solid #f1f5f9; font-size:.82rem; color:#334155; }
        .inv-table tbody td:last-child { text-align:right; font-weight:700; color:#0f172a; }
        .inv-table tbody tr:last-child td { border-bottom:none; }
        .inv-table tbody tr:hover td { background:#fafbff; }
        .inv-svc-name { font-weight:700; color:#0f172a; }
        .inv-svc-sub  { font-size:.7rem; color:#94a3b8; margin-top:2px; }
        .inv-type-chip { display:inline-block; background:#EDE9FE; color:#6d28d9; border-radius:20px; padding:2px 9px; font-size:.68rem; font-weight:700; }

        /* Tour plan */
        .inv-tour-plan { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px; font-size:.82rem; line-height:1.7; color:#334155; }
        .inv-tour-plan h3 { font-size:.84rem; font-weight:700; color:#0f172a; margin-top:10px; margin-bottom:5px; }
        .inv-tour-plan h3:first-child { margin-top:0; }
        .inv-tour-plan p  { margin-bottom:6px; }
        .inv-tour-plan ul,.inv-tour-plan ol { margin-left:18px; margin-bottom:6px; }
        .inv-tour-plan li { margin-bottom:3px; }

        /* ══ PAYMENT ══ */
        .inv-payment { background:#0a2342; padding:26px 36px; }
        .inv-pay-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
        .inv-pay-title { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:rgba(255,255,255,.45); }
        .inv-pay-status { display:inline-flex; align-items:center; gap:5px; padding:4px 13px; border-radius:20px; font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
        .inv-pay-status.paid    { background:rgba(16,185,129,.2); color:#6ee7b7; border:1px solid rgba(16,185,129,.3); }
        .inv-pay-status.partial { background:rgba(59,130,246,.2); color:#93c5fd; border:1px solid rgba(59,130,246,.3); }
        .inv-pay-status.due     { background:rgba(239,68,68,.2); color:#fca5a5; border:1px solid rgba(239,68,68,.3); }

        .inv-pay-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:18px; }
        .inv-pay-card { border-radius:13px; padding:16px 18px; }
        .inv-pay-card.total   { background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); }
        .inv-pay-card.paid    { background:rgba(16,185,129,.12); border:1px solid rgba(16,185,129,.25); }
        .inv-pay-card.due     { background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.25); }
        .inv-pay-card.settled { background:rgba(16,185,129,.12); border:1px solid rgba(16,185,129,.25); }
        .inv-pay-icon { font-size:1.2rem; margin-bottom:8px; }
        .inv-pay-label { font-size:.63rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; margin-bottom:5px; }
        .inv-pay-card.total   .inv-pay-label { color:rgba(255,255,255,.45); }
        .inv-pay-card.paid    .inv-pay-label { color:#6ee7b7; }
        .inv-pay-card.due     .inv-pay-label { color:#fca5a5; }
        .inv-pay-card.settled .inv-pay-label { color:#6ee7b7; }
        .inv-pay-amount { font-size:1.4rem; font-weight:900; line-height:1; }
        .inv-pay-card.total   .inv-pay-amount { color:#fff; }
        .inv-pay-card.paid    .inv-pay-amount { color:#10b981; }
        .inv-pay-card.due     .inv-pay-amount { color:#ef4444; }
        .inv-pay-card.settled .inv-pay-amount { color:#10b981; }
        .inv-pay-sub { font-size:.67rem; margin-top:5px; }
        .inv-pay-card.total   .inv-pay-sub { color:rgba(255,255,255,.35); }
        .inv-pay-card.paid    .inv-pay-sub { color:rgba(110,231,183,.6); }
        .inv-pay-card.due     .inv-pay-sub { color:rgba(252,165,165,.6); }
        .inv-pay-card.settled .inv-pay-sub { color:rgba(110,231,183,.6); }

        /* Progress bar */
        .inv-pay-progress { margin-bottom:16px; }
        .inv-pay-progress-top { display:flex; justify-content:space-between; font-size:.68rem; margin-bottom:5px; }
        .inv-pay-progress-lbl { color:rgba(255,255,255,.4); }
        .inv-pay-progress-pct { color:#10b981; font-weight:700; }
        .inv-pay-bar-bg { height:6px; background:rgba(255,255,255,.1); border-radius:99px; overflow:hidden; }
        .inv-pay-bar-fill { height:100%; background:linear-gradient(90deg,#10b981,#059669); border-radius:99px; transition:width .4s; }

        /* Payment history */
        .inv-pay-hist-title { font-size:.63rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.35); margin-bottom:8px; }
        .inv-pay-hist-row { display:flex; align-items:center; justify-content:space-between; padding:8px 14px; background:rgba(255,255,255,.05); border-radius:9px; margin-bottom:5px; border:1px solid rgba(255,255,255,.06); }
        .inv-pay-hist-left { display:flex; align-items:center; gap:10px; }
        .inv-pay-hist-dot { width:8px; height:8px; border-radius:50%; background:#10b981; flex-shrink:0; }
        .inv-pay-hist-date   { font-size:.74rem; color:rgba(255,255,255,.65); font-weight:600; }
        .inv-pay-hist-method { font-size:.67rem; color:rgba(255,255,255,.3); margin-top:1px; }
        .inv-pay-hist-amt { font-size:.9rem; font-weight:800; color:#10b981; }

        /* ══ TERMS ══ */
        .inv-terms { padding:22px 36px; border-bottom:1px solid #e2e8f0; background:#fafbff; }
        .inv-terms-body { font-size:.78rem; color:#334155; line-height:1.65; }
        .inv-terms-body h2 { font-size:.95rem; font-weight:800; color:#0f172a; margin-bottom:14px; }
        .inv-terms-body h3 { font-size:.8rem; font-weight:700; color:#0f3460; margin-top:12px; margin-bottom:4px; }
        .inv-terms-body h3:first-child { margin-top:0; }
        .inv-terms-body ul { margin-left:16px; margin-bottom:4px; }
        .inv-terms-body li { margin-bottom:3px; }
        .inv-terms-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .inv-term-item { display:flex; gap:10px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; }
        .inv-term-icon { font-size:.95rem; flex-shrink:0; }
        .inv-term-title { font-size:.76rem; font-weight:700; color:#0f172a; margin-bottom:3px; }
        .inv-term-desc  { font-size:.71rem; color:#64748b; line-height:1.45; }

        /* ══ FOOTER ══ */
        .inv-footer { background:#fff; padding:20px 36px; display:flex; align-items:center; justify-content:space-between; gap:20px; border-top:2px solid #e2e8f0; }
        .inv-footer-left .inv-footer-co { font-size:.88rem; font-weight:800; color:#0f3460; margin-bottom:4px; }
        .inv-footer-left .inv-footer-addr { font-size:.72rem; color:#64748b; line-height:1.6; }
        .inv-footer-right { text-align:right; }
        .inv-footer-site { font-size:.9rem; font-weight:900; color:#0f3460; }
        .inv-footer-tag  { font-size:.68rem; color:#94a3b8; margin-top:2px; }
        .inv-seal { display:inline-flex; align-items:center; gap:5px; background:#DCFCE7; border:1px solid #BBF7D0; border-radius:20px; padding:4px 12px; font-size:.68rem; font-weight:700; color:#166534; margin-top:6px; }

        /* ══ PRINT BAR ══ */
        .print-bar { position:fixed; bottom:24px; right:24px; display:flex; gap:8px; z-index:999; }
        .btn-print { background:#0f3460; color:#fff; border:none; padding:12px 22px; border-radius:10px; font-weight:700; font-size:.84rem; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 4px 18px rgba(15,52,96,.35); transition:opacity .2s,transform .2s; }
        .btn-print:hover { opacity:.88; transform:translateY(-1px); }
        .btn-wa { background:#25d366; color:#fff; border:none; padding:12px 20px; border-radius:10px; font-weight:700; font-size:.84rem; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 4px 18px rgba(37,211,102,.35); transition:opacity .2s,transform .2s; text-decoration:none; }
        .btn-wa:hover { opacity:.88; transform:translateY(-1px); color:#fff; }
        .btn-back { background:#475569; color:#fff; border:none; padding:12px 18px; border-radius:10px; font-weight:700; font-size:.84rem; cursor:pointer; display:flex; align-items:center; gap:6px; text-decoration:none; transition:opacity .2s; }
        .btn-back:hover { opacity:.8; color:#fff; }

        @media print {
            body { background:#fff; }
            .page-wrap { box-shadow:none; border-radius:0; margin:0; max-width:100%; }
            .print-bar { display:none; }
        }
    </style>
</head>
<body>

@php
    $paidAmt  = (float)($booking->payments_sum_amount ?? $booking->paid_amount ?? 0);
    $totalAmt = (float)($booking->total_amount ?? 0);
    $discAmt  = (float)($booking->discount_amount ?? 0);
    $netAmt   = max(0, $totalAmt - $discAmt);
    $dueAmt   = max(0, $netAmt - $paidAmt);
    $isFullyPaid = $dueAmt <= 0;
    $pct = $netAmt > 0 ? min(100, round($paidAmt / $netAmt * 100)) : 0;

    $payStatusLabel = $isFullyPaid ? 'Fully Paid' : ($paidAmt > 0 ? 'Partially Paid' : 'Payment Due');
    $payStatusClass = $isFullyPaid ? 'paid' : ($paidAmt > 0 ? 'partial' : 'due');

    // Service items
    $serviceItems = collect();
    $packageName  = null;
    if ($booking->quotation && $booking->quotation->items && $booking->quotation->items->count()) {
        $serviceItems = $booking->quotation->items;
        $packageName  = $serviceItems->first()?->serviceTemplate?->name ?? null;
    }
    if (!$packageName && $booking->lead?->short_plan) {
        $packageName = Str::limit(strip_tags($booking->lead->short_plan), 80);
    }
    if (!$packageName) $packageName = 'Tour / Stay Package';

    // Detect service type label
    $stLabel = 'Tour Package';
    $stEmoji = '🗺️';
    if ($serviceItems->count()) {
        $stName = strtolower($serviceItems->first()->serviceTemplate?->serviceType?->name ?? '');
        if (str_contains($stName,'stay')||str_contains($stName,'hotel')) { $stLabel='Stay / Hotel'; $stEmoji='🏨'; }
        elseif (str_contains($stName,'cab'))  { $stLabel='Cab Booking';   $stEmoji='🚗'; }
        elseif (str_contains($stName,'boat')) { $stLabel='Boat Booking';  $stEmoji='⛵'; }
    }

    // Guest info
    $guestName = $booking->lead->guest_name ?? 'Guest';
    $contact   = $booking->lead->contact ?? $booking->lead->phone ?? '—';
    $altPhone  = $booking->lead->alt_phone ?? null;
    $email     = $booking->lead->email ?? null;
    $pax       = $booking->lead->pax ?? $booking->lead->no_of_person ?? null;

    // Parse adults + children from short_plan
    $adults = null; $children = null;
    if ($booking->lead?->short_plan) {
        if (preg_match('/(\d+)\s*Adult/i', $booking->lead->short_plan, $m)) $adults = (int)$m[1];
        if (preg_match('/(\d+)\s*Child/i', $booking->lead->short_plan, $m)) $children = (int)$m[1];
    }
    // Fallback: pax as adults if no parse
    if ($adults === null && $pax) $adults = (int)$pax;

    $startDate = $booking->lead?->booking_start_date
        ? \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M Y')
        : $booking->booking_date->format('d M Y');
    $endDate = ($booking->lead?->booking_end_date && $booking->lead->booking_end_date != $booking->lead->booking_start_date)
        ? \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M Y')
        : null;

    $bkStatus  = ucwords(str_replace('_', ' ', $booking->booking_status ?? 'confirmed'));
    $cPhone    = preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917');
@endphp

<div class="page-wrap">

    {{-- ══ HEADER ══ --}}
    <div class="inv-header">
        <div class="inv-header-inner">
            <div class="inv-logo-area">
                @if($brand['logo'])
                    <img src="{{ asset('backend/admin/website_setup/'.$brand['logo']) }}" alt="{{ $brand['name'] }}">
                @else
                    <div class="inv-logo-fallback">{{ $brand['name'] }}</div>
                @endif
                <div>
                    <div class="inv-company-name">{{ $brand['name'] }}</div>
                    <div class="inv-company-sub">Varanasi's Most Trusted Travel Company</div>
                    <div class="inv-company-addr">B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi – 221010</div>
                </div>
            </div>
            <div class="inv-title-block">
                <div class="inv-doc-label">Official Document</div>
                <div class="inv-title">Booking Confirmation</div>
                <div class="inv-num">#{{ $booking->booking_number }}</div>
                <div class="inv-type-badge">{{ $stEmoji }} {{ $stLabel }}</div>
            </div>
        </div>
    </div>

    {{-- ══ STATUS BANNER ══ --}}
    <div class="inv-banner">
        <span class="inv-banner-icon">✓</span>
        <span class="inv-banner-text">Booking Confirmed — Thank you for choosing {{ $brand['name'] }}!</span>
        <span class="inv-banner-date">Issued: {{ $booking->booking_date->format('d M Y') }}</span>
    </div>

    {{-- ══ GUEST + BOOKING INFO ══ --}}
    <div class="inv-info">
        {{-- LEFT: Guest --}}
        <div class="inv-info-col">
            <div class="inv-col-label">Guest Information</div>

            <div class="inv-row">
                <span class="inv-row-key">Full Name</span>
                <span class="inv-row-val accent">{{ $guestName }}</span>
            </div>
            <div class="inv-row">
                <span class="inv-row-key">Contact</span>
                <span class="inv-row-val">{{ $contact }}</span>
            </div>
            @if($altPhone)
            <div class="inv-row">
                <span class="inv-row-key">Alt. Number</span>
                <span class="inv-row-val">{{ $altPhone }}</span>
            </div>
            @endif
            @if($email)
            <div class="inv-row">
                <span class="inv-row-key">Email</span>
                <span class="inv-row-val" style="font-size:.76rem;">{{ $email }}</span>
            </div>
            @endif
            <div class="inv-row">
                <span class="inv-row-key">No. of Persons</span>
                <div class="inv-pax-pills">
                    @if($adults !== null)
                        <span class="inv-pax-pill">🧑 {{ $adults }} Adult{{ $adults != 1 ? 's' : '' }}</span>
                    @endif
                    @if($children !== null && $children > 0)
                        <span class="inv-pax-pill" style="background:#FEF9C3;border-color:#FDE68A;color:#92400e;">👦 {{ $children }} Child{{ $children != 1 ? 'ren' : '' }}</span>
                    @endif
                    @if($adults === null && !$pax)
                        <span class="inv-row-val">—</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Booking --}}
        <div class="inv-info-col">
            <div class="inv-col-label">Booking Details</div>

            <div class="inv-row">
                <span class="inv-row-key">Booking ID</span>
                <span class="inv-row-val mono">#{{ $booking->booking_number }}</span>
            </div>
            <div class="inv-row">
                <span class="inv-row-key">Travel Date</span>
                <span class="inv-row-val">{{ $startDate }}{{ $endDate ? ' – '.$endDate : '' }}</span>
            </div>
            <div class="inv-row">
                <span class="inv-row-key">Booked On</span>
                <span class="inv-row-val">{{ $booking->booking_date->format('d M Y') }}</span>
            </div>
            @if($booking->createdBy)
            <div class="inv-row">
                <span class="inv-row-key">Booked By</span>
                <span class="inv-row-val">{{ $booking->createdBy->name }}</span>
            </div>
            @endif
            <div class="inv-row">
                <span class="inv-row-key">Status</span>
                <span class="inv-row-val green">✓ {{ $bkStatus }}</span>
            </div>
            <div class="inv-row">
                <span class="inv-row-key">Payment</span>
                <span class="inv-row-val" style="color:{{ $isFullyPaid ? '#059669' : ($paidAmt > 0 ? '#2563eb' : '#dc2626') }};font-weight:700;">{{ $payStatusLabel }}</span>
            </div>
        </div>
    </div>

    {{-- ══ PACKAGE DETAILS ══ --}}
    <div class="inv-section">
        <div class="inv-sec-title">Package Details</div>
        <div class="inv-pkg-card">
            <div class="inv-pkg-name">{{ $packageName }}</div>
            <div class="inv-pkg-sub">{{ $stLabel }} · {{ $brand['name'] }}</div>
            <div class="inv-pkg-meta">
                @if($startDate)
                <span class="inv-pkg-tag">📅 {{ $startDate }}{{ $endDate ? ' – '.$endDate : '' }}</span>
                @endif
                @if($adults !== null)
                <span class="inv-pkg-tag">🧑 {{ $adults }} Adult{{ $adults != 1 ? 's' : '' }}{{ ($children ?? 0) > 0 ? ' + '.$children.' Child'.($children != 1 ? 'ren':'') : '' }}</span>
                @elseif($pax)
                <span class="inv-pkg-tag">👥 {{ $pax }} Person{{ $pax != 1 ? 's':'' }}</span>
                @endif
                @if($booking->lead?->leadSource?->name ?? $booking->lead?->source ?? null)
                <span class="inv-pkg-tag">📌 {{ $booking->lead->leadSource?->name ?? $booking->lead->source }}</span>
                @endif
                <span class="inv-pkg-tag" style="background:#DCFCE7;border-color:#BBF7D0;color:#166534;">✓ Confirmed</span>
            </div>
        </div>
    </div>

    {{-- ══ SERVICES INCLUDED ══ --}}
    @if($serviceItems->count() > 0)
    <div class="inv-section">
        <div class="inv-sec-title">Services Included</div>
        <table class="inv-table">
            <thead>
                <tr>
                    <th style="width:45%;">Service / Item</th>
                    <th>Type</th>
                    <th style="text-align:center;">Qty</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviceItems as $item)
                @php $unitPrice = $item->unit_price ?? $item->selling_price ?? 0; @endphp
                <tr>
                    <td>
                        <div class="inv-svc-name">{{ $item->custom_name ?? $item->serviceTemplate?->name ?? 'Service' }}</div>
                        @if($item->notes)<div class="inv-svc-sub">{{ $item->notes }}</div>@endif
                    </td>
                    <td>
                        <span class="inv-type-chip">{{ $item->serviceTemplate?->serviceType?->name ?? '—' }}</span>
                    </td>
                    <td style="text-align:center;font-weight:600;">{{ $item->quantity }}</td>
                    <td>₹{{ number_format($unitPrice * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
                @if($discAmt > 0)
                <tr>
                    <td colspan="3" style="text-align:right;font-size:.76rem;color:#d97706;font-weight:600;">Discount Applied</td>
                    <td style="color:#d97706;">− ₹{{ number_format($discAmt, 2) }}</td>
                </tr>
                @endif
                <tr style="background:#f8fafc;">
                    <td colspan="3" style="text-align:right;font-weight:700;color:#0f172a;font-size:.84rem;">Net Payable</td>
                    <td style="font-weight:800;color:#0f3460;font-size:.9rem;">₹{{ number_format($netAmt, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- ══ TOUR PLAN ══ --}}
    @if($booking->lead && ($booking->lead->plan_detail || $booking->lead->short_plan))
    <div class="inv-section">
        <div class="inv-sec-title">Tour Plan / Itinerary</div>
        <div class="inv-tour-plan">
            @if($booking->lead->plan_detail)
                {!! safe_html($booking->lead->plan_detail) !!}
            @else
                {!! safe_html($booking->lead->short_plan) !!}
            @endif
        </div>
    </div>
    @elseif($booking->quotation?->itinerary)
    <div class="inv-section">
        <div class="inv-sec-title">Tour Plan / Itinerary</div>
        <div class="inv-tour-plan">{!! safe_html($booking->quotation->itinerary) !!}</div>
    </div>
    @endif

    {{-- ══ PAYMENT SUMMARY ══ --}}
    <div class="inv-payment">
        <div class="inv-pay-header">
            <div class="inv-pay-title">Payment Summary</div>
            <span class="inv-pay-status {{ $payStatusClass }}">● {{ $payStatusLabel }}</span>
        </div>

        <div class="inv-pay-grid">
            <div class="inv-pay-card total">
                <div class="inv-pay-icon">💰</div>
                <div class="inv-pay-label">Package Amount</div>
                <div class="inv-pay-amount">₹{{ number_format($netAmt, 2) }}</div>
                <div class="inv-pay-sub">Total booking value</div>
            </div>
            <div class="inv-pay-card paid">
                <div class="inv-pay-icon">✅</div>
                <div class="inv-pay-label">Amount Paid</div>
                <div class="inv-pay-amount">₹{{ number_format($paidAmt, 2) }}</div>
                <div class="inv-pay-sub">Received from guest</div>
            </div>
            <div class="inv-pay-card {{ $isFullyPaid ? 'settled' : 'due' }}">
                <div class="inv-pay-icon">{{ $isFullyPaid ? '🎉' : '⚠️' }}</div>
                <div class="inv-pay-label">{{ $isFullyPaid ? 'Balance' : 'Due Amount' }}</div>
                <div class="inv-pay-amount">₹{{ number_format($dueAmt, 2) }}</div>
                <div class="inv-pay-sub">{{ $isFullyPaid ? '✓ Fully Settled' : 'Clear before journey' }}</div>
            </div>
        </div>

    </div>

    {{-- ══ TERMS & CONDITIONS ══ --}}
    <div class="inv-terms">
        <div class="inv-sec-title" style="margin-bottom:16px;">Terms & Conditions</div>
        @php
            $stTerms = collect();
            if ($booking->quotation?->items) {
                $stIds = $booking->quotation->items->pluck('serviceTemplate.service_type_id')->filter()->unique();
                $stTerms = \App\Models\ServiceType::whereIn('id',$stIds)->whereNotNull('terms_conditions')->where('terms_conditions','!=','')->get();
            }
        @endphp
        @php
            // Strip unwanted blocks from DB-sourced terms (HTML has data-start/data-end attrs on every tag)
            function cleanTerms(string $html): string {
                // 1. Remove entire <li>...</li> block that contains "GST Bill" anywhere inside
                $html = preg_replace('/<li[^>]*>[\s\S]*?GST Bill[\s\S]*?<\/li>/i', '', $html);

                // 2. Remove <h3> heading containing "Force Majeure"
                $html = preg_replace('/<h3[^>]*>[\s\S]*?Force Majeure[\s\S]*?<\/h3>/i', '', $html);

                // 3. Remove <ul>...</ul> block that contains "natural disasters" (the Force Majeure body)
                $html = preg_replace('/<ul[^>]*>[\s\S]*?natural disasters[\s\S]*?<\/ul>/i', '', $html);

                // 4. Clean up any resulting empty <ul> or <li> or <p> tags
                $html = preg_replace('/<ul[^>]*>\s*<\/ul>/i', '', $html);
                $html = preg_replace('/<li[^>]*>\s*<\/li>/i', '', $html);
                $html = preg_replace('/<p[^>]*>\s*<\/p>/i',   '', $html);
                return $html;
            }
        @endphp
        @if($stTerms->count() > 0)
            <div class="inv-terms-body">
                @foreach($stTerms as $st)
                <div style="margin-bottom:10px;">{!! cleanTerms($st->terms_conditions) !!}</div>
                @endforeach
            </div>
        @elseif($booking->quotation?->terms_conditions)
            <div class="inv-terms-body">{!! cleanTerms($booking->quotation->terms_conditions) !!}</div>
        @else
        <div class="inv-terms-grid">
            <div class="inv-term-item">
                <span class="inv-term-icon">💳</span>
                <div>
                    <div class="inv-term-title">Payment Terms</div>
                    <div class="inv-term-desc">Balance must be settled before journey begins. Advance confirms reservation.</div>
                </div>
            </div>
            <div class="inv-term-item">
                <span class="inv-term-icon">🔄</span>
                <div>
                    <div class="inv-term-title">Cancellation Policy</div>
                    <div class="inv-term-desc">7+ days before: refundable. Within 48 hours: non-refundable as per policy.</div>
                </div>
            </div>
            <div class="inv-term-item">
                <span class="inv-term-icon">🛡️</span>
                <div>
                    <div class="inv-term-title">Liability</div>
                    <div class="inv-term-desc">{{ $brand['name'] }} is not liable for loss of personal belongings. Travel insurance recommended.</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ══ FOOTER ══ --}}
    <div class="inv-footer">
        <div class="inv-footer-left">
            <div class="inv-footer-co">{{ $brand['name'] }}</div>
            <div class="inv-footer-addr">
                {{ websiteSetupValue('address') ?? 'Plot No. B-21, 19, Rathyatra Kamachha Rd, Bhelupur, Varanasi' }}<br>
                📞 7080109917 &nbsp;|&nbsp; 7080109918 &nbsp;|&nbsp; 7080109919
                &nbsp;|&nbsp; ✉️ {{ websiteSetupValue('email') ?? 'help.visitkashi@gmail.com' }}
            </div>
        </div>
        <div class="inv-footer-right">
            <div class="inv-footer-site">www.visitkashi.in</div>
            <div class="inv-footer-tag">Most Trusted Travel Company Since 2018</div>
            <div class="inv-seal">✓ Verified Booking</div>
        </div>
    </div>

</div>

{{-- ══ ACTION BUTTONS ══ --}}
<div class="print-bar">
    <a href="{{ route('bookings.show', $booking->id) }}" class="btn-back">← Back</a>
    <a href="https://wa.me/91{{ $cPhone }}?text={{ urlencode('Hi, sharing Booking Confirmation #'.$booking->booking_number.' for '.$guestName.'. Total: ₹'.number_format($netAmt).' | Paid: ₹'.number_format($paidAmt).' | Due: ₹'.number_format($dueAmt)) }}"
       target="_blank" class="btn-wa">💬 WhatsApp</a>
    <button class="btn-print" onclick="window.print()">🖨️ Print / PDF</button>
</div>

</body>
</html>
