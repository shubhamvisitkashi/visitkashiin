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

        /* Description table */
        table.desc-table { width:100%; border-collapse:collapse; }
        table.desc-table th, table.desc-table td { border-bottom:1px solid #000; padding:8px 14px; font-size:.85rem; text-align:left; vertical-align:top; }
        table.desc-table th { font-weight:bold; border-top:none; }
        table.desc-table td.num, table.desc-table th.num { text-align:right; }
        table.desc-table .room-sub { font-size:.78rem; color:#333; margin-top:2px; }

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

    // ── Service / room items ──
    $serviceItems = collect();
    if ($booking->quotation && $booking->quotation->items && $booking->quotation->items->count()) {
        $serviceItems = $booking->quotation->items;
    }

    // ── Guest & pax info ──
    $guestName = $booking->lead->guest_name ?? 'Guest';
    $contact   = $booking->lead->contact ?? '';
    $email     = $booking->lead->email ?? '';
    $address   = $booking->lead->address ?? '';

    $adults = null; $children = null;
    if ($booking->lead?->short_plan) {
        if (preg_match('/(\d+)\s*Adult/i', $booking->lead->short_plan, $m)) $adults = (int)$m[1];
        if (preg_match('/(\d+)\s*Child/i', $booking->lead->short_plan, $m)) $children = (int)$m[1];
    }
    if ($adults === null && $booking->lead?->pax) $adults = (int)$booking->lead->pax;
    $totalGuests = ($adults ?? 0) + ($children ?? 0);

    // ── Special requests (parsed from short_plan) ──
    $specialRequests = null;
    if ($booking->lead?->short_plan) {
        foreach (array_map('trim', explode('|', $booking->lead->short_plan)) as $seg) {
            if (preg_match('/^Requests:\s*(.+)$/i', $seg, $m)) {
                $specialRequests = trim($m[1]);
                break;
            }
        }
    }

    // ── Stay dates ──
    $checkIn  = $booking->lead?->booking_start_date ? \Carbon\Carbon::parse($booking->lead->booking_start_date) : null;
    $checkOut = $booking->lead?->booking_end_date ? \Carbon\Carbon::parse($booking->lead->booking_end_date) : null;
    $nights = ($checkIn && $checkOut) ? max(1, $checkIn->diffInDays($checkOut)) : 1;

    // ── Check-in / Check-out times (parsed from short_plan) ──
    $checkInTime  = '12:00 PM';
    $checkOutTime = '11:00 AM';
    if ($booking->lead?->short_plan) {
        foreach (array_map('trim', explode('|', $booking->lead->short_plan)) as $seg) {
            if (preg_match('/^Check-in:.*?(\d{1,2}:\d{2}\s*[APap][Mm]).*?Check-out:.*?(\d{1,2}:\d{2}\s*[APap][Mm])/u', $seg, $m)) {
                try { $checkInTime  = \Carbon\Carbon::createFromFormat('g:i A', strtoupper(preg_replace('/\s+/', ' ', trim($m[1]))))->format('h:i A'); } catch (\Exception $e) {}
                try { $checkOutTime = \Carbon\Carbon::createFromFormat('g:i A', strtoupper(preg_replace('/\s+/', ' ', trim($m[2]))))->format('h:i A'); } catch (\Exception $e) {}
                break;
            }
        }
    }

    // ── Total rooms (qty sum of stay items, fallback to item count) ──
    $totalRooms = $serviceItems->count() ? $serviceItems->sum('quantity') : 1;

    // ── Rooms / Flats line (parsed from short_plan, e.g. "Flats: 3 (2x2BHK +1 BHK...)") ──
    $roomsLabel  = 'Total Rooms';
    $roomsCount  = $totalRooms;
    $flatDetails = null;
    if ($booking->lead?->short_plan) {
        foreach (array_map('trim', explode('|', $booking->lead->short_plan)) as $seg) {
            if (preg_match('/^(Flats|Rooms):\s*(\d+)(?:\s*\(([^)]*)\))?/i', $seg, $m)) {
                $roomsCount = (int)$m[2];
                if (strcasecmp($m[1], 'Flats') === 0) {
                    $roomsLabel  = 'Total Flats';
                    $flatDetails = trim($m[3] ?? '') ?: null;
                }
                break;
            }
        }
    }

    // ── Payment collection type ──
    $payCollectionType = $paidAmt >= $netAmt && $netAmt > 0 ? 'Paid Online' : 'Pay at Hotel';

    $cPhone = preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917');

    // ── Property name (the hotel/stay being booked) ──
    $propertyName = $serviceItems->first()->serviceTemplate->name ?? $brand['name'];
    $propertyAddress = $serviceItems->first()->serviceTemplate->address ?? null;
    $propertyType = $serviceItems->first()->serviceTemplate->property_type ?? null;
    $bhkType = $serviceItems->first()->serviceTemplate->bhk_type ?? null;
    $siteName = $brand['name'];
    $companyName = websiteSetupValue('company_legal_name') ?: $siteName;

    // ── Number to words (Indian system) ──
    function numberToWordsIndian($number) {
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
        <h1>{{ $siteName }} - {{ $propertyName }}</h1>
        @if($propertyAddress)
        <p class="co-addr" style="white-space:pre-line;">Address: {{ $propertyAddress }}</p>
        @else
        <p class="co-addr" style="white-space:pre-line;">Address: {{ websiteSetupValue('address') ?: 'B-21/19, Rathyatra Kamachha Road, Bhelupur, Varanasi, Uttar Pradesh' }}</p>
        <p>221010, Varanasi</p>
        @endif
        <p>Support: 7080109917, 7080109918, 7080109919</p>
        <p>Email: {{ websiteSetupValue('email') ?: 'help.visitkashi@gmail.com' }}</p>
    </div>

    {{-- Title row --}}
    <div class="title-row">
        <div></div>
        <div class="title">Booking Confirmation</div>
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
            <div class="info-line"><span class="lbl">Reference :</span> {{ $booking->lead?->leadSource?->name ?? 'Direct (walk-In)' }}</div>
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
            <div class="info-line"><span class="lbl">{{ $roomsLabel }}:</span> {{ $roomsCount }}@if($flatDetails) ({{ $flatDetails }})@endif</div>
            @if($propertyType === 'homestay' && $bhkType)
            <div class="info-line"><span class="lbl">Property Type:</span> {{ $bhkType }}</div>
            @endif
        </div>
    </div>

    {{-- Stay duration / pax info --}}
    <div class="info-grid">
        <div class="info-col">
            <div class="info-line"><span class="lbl">Stay Duration :</span> {{ $nights }}-Night</div>
            @if($checkIn)
            <div class="info-line"><span class="lbl">Check-in:</span> {{ $checkIn->format('D, j M Y') }} ({{ $checkInTime }})</div>
            @endif
            @if($checkOut)
            <div class="info-line"><span class="lbl">Check-out:</span> {{ $checkOut->format('D, j M Y') }} ({{ $checkOutTime }})</div>
            @endif
        </div>
        <div class="info-col">
            <div class="info-line"><span class="lbl">Total Guests:</span> {{ $totalGuests }}</div>
            <div class="info-line"><span class="lbl">Total Adults:</span> {{ $adults ?? 0 }}</div>
            <div class="info-line"><span class="lbl">Total Childs:</span> {{ $children ?? 0 }}</div>
        </div>
    </div>

    @if($specialRequests)
    <div class="info-grid">
        <div class="info-col" style="grid-column: 1 / -1;">
            <div class="info-line"><span class="lbl">Special Requests:</span> {{ $specialRequests }}</div>
        </div>
    </div>
    @endif

    {{-- Description / room table --}}
    <table class="desc-table">
        <thead>
            <tr>
                <th style="width:50%;">Description</th>
                <th>HSN/SAC</th>
                <th class="num">Price (INR)</th>
                <th class="num">Tax/VAT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($serviceItems as $item)
                @php
                    $itemPrice = (float)($item->unit_price ?? $item->selling_price ?? 0) * $item->quantity;
                    $itemTax   = $taxableAmt > 0 ? round($taxAmt * ($itemPrice / $taxableAmt), 2) : 0;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $item->serviceTemplate->name ?? 'Room' }}</strong> (Accomodation Only)
                        <div class="room-sub">{{ $adults ?? 0 }} Adults and {{ $children ?? 0 }} Childs</div>
                    </td>
                    <td>{{ $item->serviceTemplate?->hsn_sac ?? '996311' }}</td>
                    <td class="num">{{ number_format($itemPrice, 0) }}</td>
                    <td class="num">{{ number_format($itemTax, 0) }} ({{ rtrim(rtrim(number_format($gstRate,2),'0'),'.') }}%)</td>
                </tr>
            @empty
                <tr>
                    <td>
                        <strong>Accommodation</strong> (Accomodation Only)
                        <div class="room-sub">{{ $adults ?? 0 }} Adults and {{ $children ?? 0 }} Childs</div>
                    </td>
                    <td>996311</td>
                    <td class="num">{{ number_format($taxableAmt, 0) }}</td>
                    <td class="num">{{ number_format($taxAmt, 0) }} ({{ rtrim(rtrim(number_format($gstRate,2),'0'),'.') }}%)</td>
                </tr>
            @endforelse
        </tbody>
    </table>

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
                    <td>Room Price</td>
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
                    <td colspan="2">AMOUNT IN WORDS: INR {{ strtoupper(numberToWordsIndian($netAmt)) }} ONLY</td>
                </tr>
                <tr>
                    <td>PAID AMOUNT:</td>
                    <td>INR {{ number_format($paidAmt, 0) }}</td>
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

    <div class="thanks">Thank you for your stay in our accommodation.</div>
    <div class="powered">Powered By <span class="brand">{{ $brand['name'] }}</span></div>

</div>

{{-- Action buttons --}}
<div class="print-bar">
    <a href="{{ route('bookings.show', $booking->id) }}" class="btn-back">← Back</a>
    <a href="https://wa.me/91{{ $cPhone }}?text={{ urlencode('Hi, sharing Tax Invoice #'.$booking->booking_number.' for '.$guestName.'. Total: ₹'.number_format($netAmt).' | Paid: ₹'.number_format($paidAmt).' | Due: ₹'.number_format($dueAmt)) }}"
       target="_blank" class="btn-wa">💬 WhatsApp</a>
    <button class="btn-print" onclick="window.print()">🖨️ Print / PDF</button>
</div>

</body>
</html>
