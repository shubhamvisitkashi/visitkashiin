<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quotation - {{ $quotation->quotation_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #2c3e50;
            line-height: 1.6;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }

        /* Letterhead */
        .letterhead {
            text-align: center;
            padding: 30px 0;
            border-bottom: 4px double #2c3e50;
            margin-bottom: 30px;
        }

        .company-name {
            font-size: 32pt;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 10pt;
            color: #7f8c8d;
            font-style: italic;
            margin-bottom: 15px;
        }

        .company-contact {
            font-size: 9pt;
            color: #7f8c8d;
            line-height: 1.4;
        }

        /* Document Header */
        .doc-header {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #2c3e50;
        }

        .doc-title {
            font-size: 18pt;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        .doc-number {
            font-size: 11pt;
            color: #7f8c8d;
            font-weight: 600;
        }

        /* Info Grid */
        .info-section {
            margin-bottom: 35px;
        }

        .info-grid {
            display: table;
            width: 100%;
            border: 1px solid #dee2e6;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            width: 50%;
        }

        .info-row:last-child .info-cell {
            border-bottom: none;
        }

        .info-cell:first-child {
            border-right: 1px solid #dee2e6;
            background: #f8f9fa;
        }

        .info-label {
            font-size: 8pt;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 10pt;
            color: #2c3e50;
            font-weight: 600;
        }

        /* Section Headers */
        .section-header {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 8px;
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 20px;
            margin-top: 35px;
        }

        /* Plan Box */
        .plan-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-left: 4px solid #2c3e50;
            padding: 20px;
            margin-bottom: 30px;
        }

        .plan-box p {
            font-size: 10pt;
            line-height: 1.8;
            color: #2c3e50;
            margin: 0;
        }

        /* Quote Box */
        .quote-box {
            background: #ffffff;
            border: 2px solid #2c3e50;
            padding: 25px;
            margin: 35px 0;
        }

        .quote-header {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .amount-table {
            width: 100%;
            border-collapse: collapse;
        }

        .amount-table tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .amount-table tr:last-child {
            border-bottom: none;
        }

        .amount-table td {
            padding: 12px 0;
            font-size: 11pt;
        }

        .amount-label {
            color: #7f8c8d;
            font-weight: 500;
        }

        .amount-value {
            text-align: right;
            color: #2c3e50;
            font-weight: 600;
        }

        .discount-row td {
            color: #e74c3c;
            font-weight: 600;
        }

        .total-row {
            border-top: 2px solid #2c3e50 !important;
            background: #f8f9fa;
        }

        .total-row td {
            padding: 15px 0;
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Itinerary */
        .itinerary-box {
            background: #ffffff;
            border: 1px solid #dee2e6;
            padding: 25px;
            margin-bottom: 30px;
        }

        .itinerary-content {
            font-size: 10pt;
            line-height: 1.9;
            color: #2c3e50;
        }

        .itinerary-content p {
            margin-bottom: 12px;
        }

        .itinerary-content ul,
        .itinerary-content ol {
            margin-left: 25px;
            margin-bottom: 12px;
        }

        .itinerary-content li {
            margin-bottom: 8px;
        }

        .itinerary-content h1,
        .itinerary-content h2,
        .itinerary-content h3,
        .itinerary-content h4 {
            color: #2c3e50;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 10px;
        }

        .itinerary-content h1 {
            font-size: 14pt;
        }

        .itinerary-content h2 {
            font-size: 12pt;
        }

        .itinerary-content h3 {
            font-size: 11pt;
        }

        .itinerary-content h4 {
            font-size: 10pt;
        }

        .itinerary-content strong {
            color: #2c3e50;
            font-weight: 600;
        }

        /* Notes */
        .notes-box {
            background: #fffbf0;
            border: 1px solid #f0e68c;
            border-left: 4px solid #f39c12;
            padding: 20px;
            margin-bottom: 30px;
        }

        .notes-box p {
            font-size: 10pt;
            line-height: 1.8;
            color: #2c3e50;
            margin: 0;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 25px;
            border-top: 2px solid #dee2e6;
            text-align: center;
        }

        .footer-company {
            font-size: 11pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .footer-message {
            font-size: 10pt;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .footer-disclaimer {
            font-size: 8pt;
            color: #95a5a6;
            font-style: italic;
            line-height: 1.5;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 60px;
            padding-top: 30px;
        }

        .signature-box {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }

        .signature-box.right {
            float: right;
            text-align: right;
        }

        .signature-line {
            border-top: 1px solid #2c3e50;
            margin-top: 50px;
            padding-top: 8px;
            font-size: 9pt;
            color: #7f8c8d;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        @media print {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Letterhead -->
        <div class="letterhead">
            <div class="company-name">VISIT KASHI</div>
            <div class="company-tagline">Your Gateway to Spiritual Tourism</div>
            <div class="company-contact">
                Phone: +91-XXXXXXXXXX | Email: info@visitkashi.com | Web: www.visitkashi.com
            </div>
        </div>

        <!-- Document Header -->
        <div class="doc-header">
            <div class="doc-title">Quotation</div>
            <div class="doc-number">Reference: {{ $quotation->quotation_number }}</div>
        </div>

        <!-- Customer & Quotation Information -->
        <div class="info-section">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Customer Name</span>
                        <span class="info-value">{{ $quotation->lead->guest_name }}</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Quotation Date</span>
                        <span class="info-value">{{ $quotation->quotation_date->format('d F Y') }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Contact Number</span>
                        <span class="info-value">{{ $quotation->lead->contact }}</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Valid Until</span>
                        <span
                            class="info-value">{{ $quotation->valid_until ? $quotation->valid_until->format('d F Y') : 'N/A' }}</span>
                    </div>
                </div>
                @if ($quotation->lead->email)
                    <div class="info-row">
                        <div class="info-cell">
                            <span class="info-label">Email Address</span>
                            <span class="info-value">{{ $quotation->lead->email }}</span>
                        </div>
                        <div class="info-cell">
                            @if ($quotation->lead->number_of_guests)
                                <span class="info-label">Number of Guests</span>
                                <span class="info-value">{{ $quotation->lead->number_of_guests }} Person(s)</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tour Plan Summary -->
        @if ($quotation->lead->short_plan)
            <div class="section-header">Tour Package Overview</div>
            <div class="plan-box">
                {!! $quotation->lead->short_plan !!}
            </div>
        @endif

        <!-- Detailed Itinerary -->
        @if ($quotation->itinerary_html)
            <div class="section-header">Detailed Tour Itinerary</div>
            <div class="itinerary-box">
                <div class="itinerary-content">
                    {!! \Illuminate\Support\Str::markdown($quotation->itinerary_html) !!}
                </div>
            </div>
        @endif

        <!-- Quote Amount (After Itinerary, Right Corner) -->
        @php
            $calculatedTotal =
                $quotation->gst_type === 'include'
                    ? $quotation->subtotal
                    : $quotation->subtotal + $quotation->gst_amount;
            $finalAmount = $quotation->custom_total ?? $quotation->total_amount;
            $discount =
                $quotation->custom_total && $quotation->custom_total < $calculatedTotal
                    ? $calculatedTotal - $quotation->custom_total
                    : 0;
        @endphp

        <div style="width: 50%; float: right; margin-top: 30px;">
            <div class="quote-box">
                <div class="quote-header">Investment Details</div>

                <table class="amount-table">
                    @if ($discount > 0)
                        <tr>
                            <td class="amount-label">Package Amount</td>
                            <td class="amount-value">₹ {{ number_format($calculatedTotal, 2) }}</td>
                        </tr>
                        <tr class="discount-row">
                            <td class="amount-label">Special Discount</td>
                            <td class="amount-value">- ₹ {{ number_format($discount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td class="amount-label">Total Investment</td>
                        <td class="amount-value">₹ {{ number_format($finalAmount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="clear: both;"></div>

        <!-- Additional Notes -->
        @if ($quotation->notes)
            <div class="section-header">Important Information</div>
            <div class="notes-box">
                <p>{{ $quotation->notes }}</p>
            </div>
        @endif

        <!-- Terms & Conditions -->
        <div class="section-header">Terms & Conditions</div>
        <div class="itinerary-content" style="font-size: 9pt; color: #7f8c8d;">
            <ul>
                <li>This quotation is valid until
                    {{ $quotation->valid_until ? $quotation->valid_until->format('d F Y') : 'the specified date' }}.
                </li>
                <li>Prices are subject to change without prior notice after the validity period.</li>
                <li>Payment terms and cancellation policy will be shared upon confirmation.</li>
                <li>All services are subject to availability at the time of booking.</li>
            </ul>
        </div>

        <!-- Signature Section -->
        <div class="signature-section clearfix">
            <div class="signature-box">
                <div class="signature-line">
                    Customer Signature
                </div>
            </div>
            <div class="signature-box right">
                <div class="signature-line">
                    Authorized Signatory<br>
                    <strong>Visit Kashi</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-company">VISIT KASHI</div>
            <div class="footer-message">Thank you for choosing us for your spiritual journey!</div>
            <div class="footer-disclaimer">
                This is a computer-generated quotation and is valid subject to the terms mentioned above.<br>
                For any queries or clarifications, please feel free to contact us.
            </div>
        </div>
    </div>
</body>

</html>
