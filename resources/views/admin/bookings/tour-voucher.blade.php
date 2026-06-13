@php
    $brand = invoiceBrand($booking->lead?->leadSource?->name);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Booking Voucher #{{ $booking->booking_number }} | {{ $brand['name'] }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial, Helvetica, sans-serif; background:#1a1a1a; padding:24px; font-size:13px; color:#000; }

        .invoice-box { max-width:850px; margin:0 auto; background:#fff; border:1px solid #000; }

        .center { text-align:center; }
        .right  { text-align:right; }
        .bold   { font-weight:bold; }

        /* Company header */
        .co-header { padding:16px 20px; text-align:center; border-bottom:1px solid #000; }
        .co-header h1 { font-size:1.6rem; font-weight:800; margin-bottom:6px; }
        .co-header p { font-weight:bold; font-size:.85rem; margin-bottom:2px; }
        .co-header .co-addr { max-width:620px; margin:0 auto 2px; }

        /* Title row */
        .title-row { display:flex; align-items:center; justify-content:space-between; padding:10px 20px; border-bottom:1px solid #000; }
        .title-row .title { flex:1; text-align:center; font-weight:bold; font-size:.95rem; }
        .title-row .recipient { font-size:.82rem; }

        .gstin-row { padding:8px 20px; font-weight:bold; border-bottom:1px solid #000; }

        /* Two column info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; border-bottom:1px solid #000; }
        .info-col { padding:12px 20px; }
        .info-line { margin-bottom:5px; font-size:.85rem; }
        .info-line .lbl { font-weight:bold; }

        /* Tour package / services box */
        .pkg-section { padding:12px 20px; border-bottom:1px solid #000; }
        .pkg-section .lbl { font-weight:bold; font-size:.85rem; display:block; margin-bottom:4px; }
        .pkg-section .pkg-text { font-size:.85rem; line-height:1.6; }
        .pkg-chips { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
        .pkg-chip { border:1px solid #000; border-radius:14px; padding:3px 12px; font-size:.78rem; font-weight:bold; }

        /* Description table */
        table.desc-table { width:100%; border-collapse:collapse; }
        table.desc-table th, table.desc-table td { border-bottom:1px solid #000; padding:8px 14px; font-size:.85rem; text-align:left; vertical-align:top; }
        table.desc-table th { font-weight:bold; border-top:none; }
        table.desc-table td.num, table.desc-table th.num { text-align:right; }
        table.desc-table .room-sub { font-size:.78rem; color:#333; margin-top:2px; }

        /* Itinerary */
        .itin-section { padding:12px 20px; border-bottom:1px solid #000; }
        .itin-section .lbl { font-weight:bold; font-size:.85rem; display:block; margin-bottom:6px; }
        .itin-section .itin-text { font-size:.83rem; line-height:1.7; }
        .itin-text h4 { font-size:.9rem; font-weight:bold; margin:8px 0 4px; }
        .itin-text h4:first-child { margin-top:0; }
        .itin-text p { margin:4px 0; }
        .itin-text ul { margin:2px 0 8px; padding-left:18px; }
        .itin-text li { margin-bottom:2px; }

        /* Bottom totals area */
        .bottom-grid { display:flex; }
        .bottom-left { flex:1; border-right:1px solid #000; }
        .bottom-right { flex:1; }

        table.gst-table, table.totals-table { width:100%; border-collapse:collapse; }
        table.gst-table th, table.gst-table td { border-bottom:1px solid #000; border-right:1px solid #000; padding:8px 12px; font-size:.85rem; text-align:left; }
        table.gst-table th:last-child, table.gst-table td:last-child { border-right:none; }
        table.gst-table thead th { font-weight:bold; }

        table.totals-table td { padding:8px 12px; font-size:.85rem; border-bottom:1px solid #000; border-left:1px solid #000; }
        table.totals-table tr td:first-child { font-weight:bold; }
        table.totals-table tr td:last-child { text-align:right; }
        table.totals-table tr:last-child td { border-bottom:none; }

        /* Signature */
        .sig-for { padding:30px 20px 50px; text-align:right; font-weight:bold; border-bottom:1px solid #000; }
        .sig-row { display:flex; justify-content:space-between; padding:14px 20px; border-bottom:1px solid #000; font-weight:bold; }

        .thanks { text-align:center; font-weight:bold; padding:14px 20px; border-bottom:1px solid #000; }
        .powered { text-align:center; font-weight:bold; padding:10px 20px; }
        .powered .brand { color:#0f3460; }

        .print-bar { position:fixed; bottom:24px; right:24px; display:flex; gap:8px; z-index:999; }
        .btn-print { background:#0f3460; color:#fff; border:none; padding:12px 22px; border-radius:10px; font-weight:700; font-size:.84rem; cursor:pointer; box-shadow:0 4px 18px rgba(15,52,96,.35); }
        .btn-back { background:#475569; color:#fff; border:none; padding:12px 18px; border-radius:10px; font-weight:700; font-size:.84rem; text-decoration:none; }
        .btn-wa { background:#25d366; color:#fff; border:none; padding:12px 20px; border-radius:10px; font-weight:700; font-size:.84rem; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 4px 18px rgba(37,211,102,.35); text-decoration:none; }
        .btn-wa:hover { color:#fff; }
        .btn-back:hover, .btn-print:hover { opacity:.88; }

        @media print {
            body { background:#fff; padding:0; }
            .invoice-box { border:1px solid #000; max-width:100%; }
            .print-bar { display:none; }
        }
    </style>
</head>
<body>

@php
    // ── Amounts ──
    $paidAmt   = (float)($booking->payments_sum_amount ?? $booking->paid_amount ?? 0);
    $totalAmt  = (float)($booking->total_amount ?? 0);
    $discAmt   = (float)($booking->discount_amount ?? 0);
    $taxAmt    = (float)($booking->gst_amount ?? 0);
    $gstRate   = (float)($booking->gst_rate ?? 0);
    $sgst      = round($taxAmt / 2, 2);
    $cgst      = round($taxAmt - $sgst, 2);
    // total_amount is already net of discount, so don't subtract it again
    $netAmt     = $totalAmt;
    $taxableAmt = (float)($booking->taxable_amount ?? max(0, $netAmt - $taxAmt));
    $grossTaxableAmt = $taxableAmt + $discAmt;
    $dueAmt     = max(0, $netAmt - $paidAmt);

    // ── Service items ──
    $serviceItems = collect();
    if ($booking->quotation && $booking->quotation->items && $booking->quotation->items->count()) {
        $serviceItems = $booking->quotation->items;
    }

    // ── Guest & lead info ──
    $lead      = $booking->lead;
    $guestName = $lead->guest_name ?? 'Guest';
    $contact   = $lead->contact ?? '';
    $email     = $lead->email ?? '';
    $address   = $lead->address ?? '';
    $pax       = $lead->pax ?? null;

    // ── Tour package name (first segment of short_plan) ──
    $pkgRaw  = $lead->short_plan ?? '';
    $pkgName = trim(explode('|', $pkgRaw)[0]);
    if (!$pkgName) {
        $pkgName = $serviceItems->first()->serviceTemplate->name ?? 'Tour Package';
    }

    // ── Tour dates / duration ──
    $startDate = $lead->booking_start_date ? \Carbon\Carbon::parse($lead->booking_start_date) : null;
    $endDate   = $lead->booking_end_date   ? \Carbon\Carbon::parse($lead->booking_end_date)   : null;
    $tourDays  = ($startDate && $endDate) ? max(1, $startDate->diffInDays($endDate) + 1) : null;
    $tourNights = ($startDate && $endDate) ? max(0, $startDate->diffInDays($endDate)) : null;

    // ── Parse hotel / cab / boat / guide / inclusions from quotation notes ──
    $hdRaw  = $booking->quotation?->notes ?? $lead?->notes ?? '';
    $hdData = ($hdRaw && substr(trim($hdRaw), 0, 1) === '{') ? (json_decode($hdRaw, true) ?? []) : [];

    if (!empty($hdData['hotels']) && is_array($hdData['hotels'])) {
        $hdHotels = $hdData['hotels'];
    } elseif (!empty($hdData['hotel'])) {
        $oh = $hdData['hotel'];
        $hn = trim($oh['name'] ?? $oh['hotel_name'] ?? '');
        $hdHotels = $hn ? [['name' => $hn, 'city' => $oh['city'] ?? '']] : [];
    } else {
        $hdHotels = [];
    }
    $hdCab   = $hdData['cab']   ?? null;
    $hdBoat  = $hdData['boat']  ?? null;
    $hdGuide = $hdData['guide'] ?? null;
    $hdIncl  = $hdData['inclusions'] ?? null;

    $pkgChips = [];
    foreach ($hdHotels as $hh) {
        $hName = trim($hh['name'] ?? $hh['hotel_name'] ?? '');
        $hCity = trim($hh['city'] ?? '');
        if ($hName) $pkgChips[] = '🏨 ' . $hName . ($hCity ? ' (' . $hCity . ')' : '');
    }
    if (!empty($hdCab['cab_type'])) {
        $pkgChips[] = '🚕 ' . $hdCab['cab_type'] . (!empty($hdCab['cab_route']) ? ' · ' . $hdCab['cab_route'] : '');
    }
    if (!empty($hdBoat['boat_type'])) {
        $pkgChips[] = '⛵ ' . $hdBoat['boat_type'] . (!empty($hdBoat['boat_ride']) ? ' · ' . $hdBoat['boat_ride'] : '');
    }
    if (!empty($hdGuide['guide_name'])) {
        $pkgChips[] = '🧭 ' . $hdGuide['guide_name'];
    }

    // ── Payment collection type ──
    $payCollectionType = $paidAmt >= $netAmt && $netAmt > 0 ? 'Paid Online' : 'Pay at Office';

    $cPhone = preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917');

    $siteName    = $brand['name'];
    $companyName = websiteSetupValue('company_legal_name') ?: $siteName;

    // ── Number to words (Indian system) ──
    function numberToWordsIndianTour($number) {
        $number = (int) round($number);
        if ($number == 0) return 'Zero';
        $ones = ['', 'One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
        $tens = ['', '', 'Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];

        $convert = function($num) use ($ones, $tens, &$convert) {
            if ($num == 0) return '';
            if ($num < 20) return $ones[$num];
            if ($num < 100) return trim($tens[intdiv($num,10)] . ' ' . $ones[$num % 10]);
            return trim($ones[intdiv($num,100)] . ' Hundred ' . $convert($num % 100));
        };

        $crore = intdiv($number, 10000000); $number %= 10000000;
        $lakh  = intdiv($number, 100000);   $number %= 100000;
        $thousand = intdiv($number, 1000);  $number %= 1000;
        $hundred = $number;

        $parts = [];
        if ($crore)    $parts[] = $convert($crore) . ' Crore';
        if ($lakh)     $parts[] = $convert($lakh) . ' Lakh';
        if ($thousand) $parts[] = $convert($thousand) . ' Thousand';
        if ($hundred)  $parts[] = $convert($hundred);

        return trim(implode(' ', $parts));
    }
@endphp

<div class="invoice-box">

    {{-- Company header --}}
    <div class="co-header">
        <h1>{{ $siteName }} - {{ $pkgName }}</h1>
        <p class="co-addr" style="white-space:pre-line;">Address: {{ websiteSetupValue('address') ?: 'B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi, Uttar Pradesh' }}</p>
        <p>221010, Varanasi</p>
        <p>Support: 7080109917, 7080109918, 7080109919</p>
        <p>Email: {{ websiteSetupValue('email') ?: 'help.visitkashi@gmail.com' }}</p>
    </div>

    {{-- Title row --}}
    <div class="title-row">
        <div></div>
        <div class="title">Tour Booking Voucher</div>
        <div class="recipient">ORIGINAL FOR RECIPIENT</div>
    </div>

    {{-- GSTIN --}}
    @if($booking->is_gst_invoice)
    <div class="gstin-row">GSTIN/VAT: {{ websiteSetupValue('company_gstin') ?: '—' }}</div>
    @endif

    {{-- Invoice / Booking info --}}
    <div class="info-grid">
        <div class="info-col">
            <div class="info-line"><span class="lbl">Invoice No :</span> {{ $booking->gst_invoice_number ?? 'INV:'.$booking->booking_number }}</div>
            <div class="info-line"><span class="lbl">Reference :</span> {{ $lead?->leadSource?->name ?? 'Direct (walk-In)' }}</div>
            <div class="info-line"><span class="lbl">Guest Name :</span> {{ $guestName }}</div>
            <div class="info-line"><span class="lbl">Guest Phone :</span> {{ $contact }}</div>
            <div class="info-line"><span class="lbl">Guest Email :</span> {{ $email }}</div>
            <div class="info-line"><span class="lbl">Guest Address :</span> {{ $address }}</div>
            @if($booking->is_gst_invoice)
            <div class="info-line"><span class="lbl">Company Name :</span> {{ $booking->company_name }}</div>
            <div class="info-line"><span class="lbl">Company GST/VAT :</span> {{ $booking->customer_gstin }}</div>
            @endif
        </div>
        <div class="info-col">
            <div class="info-line"><span class="lbl">Booking ID:</span> {{ $booking->booking_number }}</div>
            <div class="info-line"><span class="lbl">Invoice Date:</span> {{ $booking->booking_date->format('Y-m-d') }} 11:00 (IST)</div>
            <div class="info-line"><span class="lbl">Payment Collection Type:</span> {{ $payCollectionType }}</div>
            @if($tourDays)
            <div class="info-line"><span class="lbl">Tour Duration :</span> {{ $tourDays }} Days{{ $tourNights ? ' / ' . $tourNights . ' Nights' : '' }}</div>
            @endif
        </div>
    </div>

    {{-- Tour dates / pax info --}}
    <div class="info-grid">
        <div class="info-col">
            @if($startDate)
            <div class="info-line"><span class="lbl">Tour Start Date:</span> {{ $startDate->format('D, j M Y') }}</div>
            @endif
            @if($endDate)
            <div class="info-line"><span class="lbl">Tour End Date:</span> {{ $endDate->format('D, j M Y') }}</div>
            @endif
        </div>
        <div class="info-col">
            <div class="info-line"><span class="lbl">Total Guests:</span> {{ $pax ?? '—' }}</div>
        </div>
    </div>

    {{-- Tour Package Details --}}
    <div class="pkg-section">
        <span class="lbl">Package Summary</span>
        <div class="pkg-text">{{ $lead->short_plan ?? '—' }}</div>
        @if(count($pkgChips) > 0)
        <div class="pkg-chips">
            @foreach($pkgChips as $chip)
            <span class="pkg-chip">{{ $chip }}</span>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Description / services table ──────────────────────── --}}
    @php
        $hasItemized = !empty($hdHotels) || !empty($hdCab['cab_type']) || !empty($hdBoat['boat_type']) || !empty($hdGuide['guide_name']);
    @endphp
    <table class="desc-table">
        <thead>
            <tr>
                <th style="width:50%;">Description</th>
                <th>Date(s)</th>
                <th class="num">Qty</th>
                <th class="num">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            @if($hasItemized)
                {{-- Hotel / Stay rows --}}
                @foreach($hdHotels as $h)
                    @php
                        $hName   = trim($h['name'] ?? $h['hotel_name'] ?? '');
                        $hCity   = trim($h['city'] ?? '');
                        $hRoom   = $h['room_type'] ?? '';
                        $hFlat   = trim($h['flat_name'] ?? '');
                        $hCi     = !empty($h['checkin'])  ? \Carbon\Carbon::parse($h['checkin'])->format('d M Y')  : (!empty($h['hotel_checkin'])  ? \Carbon\Carbon::parse($h['hotel_checkin'])->format('d M Y')  : '');
                        $hCo     = !empty($h['checkout']) ? \Carbon\Carbon::parse($h['checkout'])->format('d M Y') : (!empty($h['hotel_checkout']) ? \Carbon\Carbon::parse($h['hotel_checkout'])->format('d M Y') : '');
                        $hNights = $h['nights'] ?? $h['hotel_nights'] ?? '';
                    @endphp
                    @if($hName || $hCity)
                    <tr>
                        <td>
                            <strong>🏨 Hotel Stay — {{ $hName ?: '—' }}</strong>
                            <div class="room-sub">{{ $hCity ? $hCity . ($hRoom ? ' · ' . $hRoom : '') : $hRoom }}</div>
                            @if($hFlat)
                            <div class="room-sub">Flat Type: {{ $hFlat }}</div>
                            @endif
                        </td>
                        <td>{{ $hCi ?: '—' }} → {{ $hCo ?: '—' }}</td>
                        <td class="num">{{ $hNights ?: '—' }} {{ $hNights == 1 ? 'Night' : 'Nights' }}</td>
                        <td class="num"><em>Included</em></td>
                    </tr>
                    @endif
                @endforeach

                {{-- Cab / Transport --}}
                @if(!empty($hdCab['cab_type']))
                <tr>
                    <td>
                        <strong>🚕 Cab / Transport — {{ $hdCab['cab_type'] }}</strong>
                        @if(!empty($hdCab['cab_route']))<div class="room-sub">{{ $hdCab['cab_route'] }}</div>@endif
                    </td>
                    <td>
                        @if(!empty($hdCab['cab_from'])) {{ \Carbon\Carbon::parse($hdCab['cab_from'])->format('d M Y') }} @endif
                        @if(!empty($hdCab['cab_from']) && !empty($hdCab['cab_to'])) → @endif
                        @if(!empty($hdCab['cab_to'])) {{ \Carbon\Carbon::parse($hdCab['cab_to'])->format('d M Y') }} @endif
                        @if(empty($hdCab['cab_from']) && empty($hdCab['cab_to'])) — @endif
                    </td>
                    <td class="num">1</td>
                    <td class="num"><em>Included</em></td>
                </tr>
                @endif

                {{-- Boat / River Ride --}}
                @if(!empty($hdBoat['boat_type']))
                <tr>
                    <td>
                        <strong>⛵ Boat / River Ride — {{ $hdBoat['boat_type'] }}</strong>
                        @if(!empty($hdBoat['boat_ride']))<div class="room-sub">{{ $hdBoat['boat_ride'] }}</div>@endif
                    </td>
                    <td>
                        @if(!empty($hdBoat['boat_date'])) {{ \Carbon\Carbon::parse($hdBoat['boat_date'])->format('d M Y') }} @endif
                        @if(!empty($hdBoat['boat_time'])) &nbsp;·&nbsp; {{ date('g:i A', strtotime($hdBoat['boat_time'])) }} @endif
                        @if(empty($hdBoat['boat_date']) && empty($hdBoat['boat_time'])) — @endif
                    </td>
                    <td class="num">1</td>
                    <td class="num"><em>Included</em></td>
                </tr>
                @endif

                {{-- Guide --}}
                @if(!empty($hdGuide['guide_name']))
                <tr>
                    <td>
                        <strong>🧭 Guide — {{ $hdGuide['guide_name'] }}</strong>
                        @if(!empty($hdGuide['guide_lang']))<div class="room-sub">{{ $hdGuide['guide_lang'] }}</div>@endif
                    </td>
                    <td>
                        @if(!empty($hdGuide['guide_from'])) {{ \Carbon\Carbon::parse($hdGuide['guide_from'])->format('d M Y') }} @endif
                        @if(!empty($hdGuide['guide_from']) && !empty($hdGuide['guide_to'])) → @endif
                        @if(!empty($hdGuide['guide_to'])) {{ \Carbon\Carbon::parse($hdGuide['guide_to'])->format('d M Y') }} @endif
                        @if(empty($hdGuide['guide_from']) && empty($hdGuide['guide_to'])) — @endif
                    </td>
                    <td class="num">1</td>
                    <td class="num"><em>Included</em></td>
                </tr>
                @endif

                {{-- Package total row --}}
                <tr>
                    <td>
                        <strong>{{ $pkgName }}</strong>
                        <div class="room-sub">Tour Package{{ $tourDays ? ' — ' . $tourDays . ' Days' . ($tourNights ? ' / ' . $tourNights . ' Nights' : '') : '' }}</div>
                    </td>
                    <td>{{ $startDate ? $startDate->format('d M Y') : '—' }} → {{ $endDate ? $endDate->format('d M Y') : '—' }}</td>
                    <td class="num">{{ $pax ?? 1 }} Pax</td>
                    <td class="num">{{ number_format($grossTaxableAmt, 0) }}</td>
                </tr>
            @elseif($serviceItems->count())
                @foreach($serviceItems as $item)
                    @php
                        $itemAmount = (float)($item->total_price ?? (($item->unit_price ?? 0) * $item->quantity));
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $item->serviceTemplate?->name ?? 'Tour Service' }}</strong>
                            <div class="room-sub">{{ $item->serviceTemplate?->serviceType?->name ?? $item->serviceType?->name ?? '' }}</div>
                        </td>
                        <td>{{ $item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d M, Y') : '—' }}</td>
                        <td class="num">{{ $item->quantity }}</td>
                        <td class="num">{{ number_format($itemAmount, 0) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td><strong>{{ $pkgName }}</strong></td>
                    <td>{{ $startDate ? $startDate->format('d M, Y') : '—' }}</td>
                    <td class="num">{{ $pax ?? 1 }}</td>
                    <td class="num">{{ number_format($grossTaxableAmt, 0) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Itinerary --}}
    @if(!empty($lead->plan_detail))
    <div class="itin-section">
        <span class="lbl">Itinerary</span>
        <div class="itin-text">{!! safe_html($lead->plan_detail) !!}</div>
    </div>
    @endif

    {{-- Bottom: GST breakdown + totals --}}
    <div class="bottom-grid">
        <div class="bottom-left">
            <table class="gst-table">
                <thead>
                    <tr>
                        <th>SGST (INR)</th>
                        <th>CGST (INR)</th>
                        <th>Taxable Amount (INR)</th>
                        <th>Tax/VAT (INR)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ number_format($sgst, 0) }}</td>
                        <td>{{ number_format($cgst, 0) }}</td>
                        <td>{{ number_format($taxableAmt, 0) }}</td>
                        <td>{{ number_format($taxAmt, 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bottom-right">
            <table class="totals-table">
                <tr>
                    <td>Package Price</td>
                    <td>INR {{ number_format($grossTaxableAmt, 0) }}</td>
                </tr>
                <tr>
                    <td>(+) Tax/VAT</td>
                    <td>INR {{ number_format($taxAmt, 0) }}</td>
                </tr>
                <tr>
                    <td>(-) Discount</td>
                    <td>INR {{ number_format($discAmt, 0) }}</td>
                </tr>
                <tr>
                    <td>TOTAL AMOUNT:</td>
                    <td>INR {{ number_format($netAmt, 0) }}</td>
                </tr>
                <tr>
                    <td colspan="2">AMOUNT IN WORDS: INR {{ strtoupper(numberToWordsIndianTour($netAmt)) }} ONLY</td>
                </tr>
                <tr>
                    <td>TOTAL DUE AMOUNT:</td>
                    <td>INR {{ number_format($dueAmt, 0) }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Signature --}}
    @if($booking->is_gst_invoice)
    <div class="sig-for">FOR {{ strtoupper($companyName) }}</div>
    @endif
    <div class="sig-row">
        <div>Guest Signature</div>
        <div>Authorised Signatory</div>
    </div>

    <div class="thanks">Thank you for booking your tour with us.</div>
    <div class="powered">Powered By <span class="brand">{{ $brand['name'] }}</span></div>

</div>

{{-- Action buttons --}}
<div class="print-bar">
    <a href="{{ route('tour-booking.view', $booking->id) }}" class="btn-back">← Back</a>
    <a href="https://wa.me/91{{ $cPhone }}?text={{ urlencode('Hi, sharing your Tour Booking Voucher #'.$booking->booking_number.' for '.$guestName.'. Total: ₹'.number_format($netAmt).' | Paid: ₹'.number_format($paidAmt).' | Due: ₹'.number_format($dueAmt)) }}"
       target="_blank" class="btn-wa">💬 WhatsApp</a>
    <button class="btn-print" onclick="window.print()">🖨️ Print / PDF</button>
</div>

</body>
</html>
