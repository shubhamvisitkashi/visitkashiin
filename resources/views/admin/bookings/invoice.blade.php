<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation #{{ $booking->booking_number }} | {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #000;
            background: #fff;
            padding: 10px;
            font-size: 12px;
            line-height: 1.3;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #000;
        }

        /* Header Section */
        .invoice-header {
            padding: 15px;
            border-bottom: 2px solid #000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 8px;
        }

        .company-details {
            font-size: 11px;
            line-height: 1.4;
        }

        .company-details p {
            margin: 2px 0;
        }

        .invoice-title-section {
            text-align: right;
            flex: 1;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .invoice-number {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .invoice-date {
            font-size: 11px;
        }

        .tour-package-box {
            margin-top: 8px;
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }

        .tour-package-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .tour-package-name {
            font-size: 13px;
            font-weight: bold;
        }

        /* Customer & Booking Info */
        .info-section {
            padding: 12px 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            border-bottom: 1px solid #000;
        }

        .info-card h3 {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 6px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        .info-item {
            margin-bottom: 4px;
            font-size: 11px;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            min-width: 110px;
        }

        .info-value {
            display: inline;
        }

        /* Services Table */
        .services-section {
            padding: 12px 15px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #000;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .services-table thead {
            background: #000;
            color: #fff;
        }

        .services-table thead th {
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #000;
        }

        .services-table thead th:last-child {
            text-align: right;
        }

        .services-table tbody td {
            padding: 6px 8px;
            border: 1px solid #000;
            font-size: 11px;
        }

        .service-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .service-type {
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        /* Totals Section */
        .totals-section {
            padding: 10px 15px;
            border-top: 1px solid #000;
        }

        .totals-table {
            width: 100%;
            max-width: 350px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 4px 0;
            font-size: 11px;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .total-row {
            font-size: 13px !important;
            font-weight: bold !important;
            border-top: 2px solid #000;
            padding-top: 6px !important;
        }

        .paid-row {
            font-weight: bold;
        }

        .due-row {
            font-size: 12px !important;
            font-weight: bold !important;
            border-top: 1px solid #000;
            padding-top: 6px !important;
        }

        /* Terms & Conditions */
        .terms-section {
            padding: 12px 15px;
            border-top: 1px solid #000;
        }

        .terms-section h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 6px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        .terms-content {
            font-size: 10px;
            line-height: 1.4;
        }

        .terms-content ul {
            list-style: none;
            padding: 0;
        }

        .terms-content li {
            padding: 3px 0;
            padding-left: 15px;
            position: relative;
        }

        .terms-content li::before {
            content: '•';
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* Footer */
        .invoice-footer {
            background: #000;
            padding: 10px 15px;
            color: #fff;
            text-align: center;
            border-top: 2px solid #000;
        }

        .footer-content {
            font-size: 10px;
        }

        .footer-content p {
            margin: 2px 0;
        }

        .footer-website {
            font-size: 11px;
            font-weight: bold;
            margin-top: 4px;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #000;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            border: none;
            cursor: pointer;
        }

        .print-button:hover {
            background: #333;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
            }

            .invoice-container {
                border: none;
            }

            .print-button {
                display: none;
            }
        }

        /* Day-wise Itinerary Styling */
        .tour-plan-content h3 {
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #000;
        }

        .tour-plan-content h3:first-child {
            margin-top: 0;
        }

        .tour-plan-content p {
            font-size: 11px;
            line-height: 1.4;
            margin-bottom: 6px;
        }

        .tour-plan-content ul,
        .tour-plan-content ol {
            margin-left: 15px;
            margin-bottom: 6px;
        }

        .tour-plan-content li {
            font-size: 11px;
            line-height: 1.3;
            margin-bottom: 3px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-content">
                <div class="company-info">
                    @if (websiteSetupValue('logo'))
                        <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                            alt="Company Logo" class="company-logo">
                    @else
                        <img src="{{ asset('backend/logo.jpeg') }}" alt="Company Logo" class="company-logo">
                    @endif
                    <div class="company-details">
                        <p><strong>{{ websiteSetupValue('site_title') ?? config('app.name') }}</strong></p>
                        <p>{{ websiteSetupValue('address') }}</p>
                        <p>Phone: {{ websiteSetupValue('contact_number') }}</p>
                        <p>WhatsApp: {{ websiteSetupValue('whats_app_number') }}</p>
                        <p>Email: {{ websiteSetupValue('email') }}</p>
                    </div>
                </div>
                <div class="invoice-title-section">
                    <h1 class="invoice-title">BOOKING CONFIRMATION</h1>
                    <div class="invoice-number">#{{ $booking->booking_number }}</div>
                    <div class="invoice-date">Date: {{ $booking->booking_date->format('d M, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Customer & Booking Info -->
        <div class="info-section">
            <div class="info-card">
                <h3>Guest Information</h3>
                <div class="info-item">
                    <span class="info-label">Guest Name:</span>
                    <span class="info-value">{{ $booking->lead->guest_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact Number:</span>
                    <span class="info-value">{{ $booking->lead->contact }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Number of Persons:</span>
                    <span class="info-value">{{ $booking->lead->pax ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="info-card">
                <h3>Booking Details</h3>
                <div class="info-item">
                    <span class="info-label">Booking ID:</span>
                    <span class="info-value"><strong>{{ $booking->booking_number }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Booking Date:</span>
                    <span class="info-value">
                        @if ($booking->lead && $booking->lead->booking_start_date)
                            <strong>
                                {{ \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M, Y') }}
                                @if ($booking->lead->booking_end_date && $booking->lead->booking_start_date != $booking->lead->booking_end_date)
                                    - {{ \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M, Y') }}
                                @endif
                            </strong>
                        @else
                            {{ $booking->booking_date->format('d M, Y') }}
                        @endif
                    </span>
                </div>
                @if ($booking->lead && $booking->lead->short_plan)
                    <div class="info-item" style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #ddd;">
                        <span class="info-label" style="display: block; margin-bottom: 4px;">Tour Package:</span>
                        <span class="info-value"
                            style="display: block; font-weight: bold;">{!! $booking->lead->short_plan !!}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Package/Service Details -->
        <div class="services-section">
            <h2 class="section-title">Package/Service Details</h2>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Tour Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="tour-plan-content">
                                @if ($booking->lead && $booking->lead->plan_detail)
                                    {!! $booking->lead->plan_detail !!}
                                @elseif($booking->quotation && $booking->quotation->itinerary)
                                    {!! $booking->quotation->itinerary !!}
                                @elseif($booking->lead && $booking->lead->short_plan)
                                    <div><strong>{!! $booking->lead->short_plan !!}</strong></div>
                                @else
                                    <p>Complete tour package as per booking</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Services Breakdown (hidden as per requirement) -->
        {{-- @if ($booking->quotation && $booking->quotation->items && $booking->quotation->items->count() > 0)
            <div class="services-section">
                <h2 class="section-title">Services Included</h2>
                <table class="services-table">
                    <thead>
                        <tr>
                            <th style="text-align: left;">Service</th>
                            <th style="text-align: center; width: 80px;">Quantity</th>
                            <th style="text-align: right; width: 100px;">Price</th>
                            <th style="text-align: right; width: 100px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking->quotation->items as $item)
                            <tr>
                                <td>{{ $item->serviceTemplate->name ?? 'Service' }}</td>
                                <td style="text-align: center;">{{ $item->quantity }}</td>
                                <td style="text-align: right;">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td style="text-align: right;">
                                    <strong>₹{{ number_format($item->total_price, 2) }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif --}}

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr class="total-row">
                    <td><strong>Total Amount:</strong></td>
                    <td><strong>₹{{ number_format($booking->total_amount, 2) }}</strong></td>
                </tr>
                @if (($booking->payments_sum_amount ?? 0) > 0)
                    <tr class="paid-row">
                        <td>Amount Paid:</td>
                        <td>₹{{ number_format($booking->payments_sum_amount ?? 0, 2) }}</td>
                    </tr>
                    <tr class="due-row">
                        <td><strong>Balance Due:</strong></td>
                        <td><strong>₹{{ number_format($booking->total_amount - ($booking->payments_sum_amount ?? 0), 2) }}</strong>
                        </td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Terms & Conditions -->
        <div class="terms-section">
            <h3>Terms & Conditions</h3>
            <div class="terms-content">
                @php
                    // Collect unique service type terms from booking
                    $serviceTypeTerms = collect();
                    if ($booking->quotation && $booking->quotation->items) {
                        $serviceTypeIds = $booking->quotation->items
                            ->pluck('serviceTemplate.service_type_id')
                            ->filter()
                            ->unique();

                        $serviceTypeTerms = \App\Models\ServiceType::whereIn('id', $serviceTypeIds)
                            ->whereNotNull('terms_conditions')
                            ->where('terms_conditions', '!=', '')
                            ->get();
                    }
                @endphp

                @if ($serviceTypeTerms->count() > 0)
                    {{-- Display category-specific terms --}}
                    @foreach ($serviceTypeTerms as $serviceType)
                        <div style="margin-bottom: 15px;">
                            <h4
                                style="font-size: 11px; font-weight: bold; margin-bottom: 6px; border-bottom: 1px solid #000; padding-bottom: 3px;">
                                {{ $serviceType->name }} - Terms & Conditions
                            </h4>
                            <div style="font-size: 10px;">
                                {!! $serviceType->terms_conditions !!}
                            </div>
                        </div>
                    @endforeach

                    {{-- Also show quotation terms if available --}}
                    @if ($booking->quotation && $booking->quotation->terms_conditions)
                        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #000;">
                            <h4 style="font-size: 11px; font-weight: bold; margin-bottom: 6px;">General Terms &
                                Conditions</h4>
                            <div style="font-size: 10px;">
                                {!! $booking->quotation->terms_conditions !!}
                            </div>
                        </div>
                    @endif
                @elseif ($booking->quotation && $booking->quotation->terms_conditions)
                    {{-- Show quotation terms if no category-specific terms --}}
                    {!! $booking->quotation->terms_conditions !!}
                @else
                    {{-- Default terms --}}
                    <ul>
                        <li><strong>Payment Terms:</strong> Full payment or an agreed deposit must be made at the time
                            of booking. The remaining balance must be settled before the start of the journey.</li>
                        <li><strong>Cancellation Policy:</strong> Cancellations made 7 days or more before the scheduled
                            date are eligible for a refund as per our cancellation policy. Cancellations made within 48
                            hours are non-refundable.</li>
                        <li><strong>Service Delivery:</strong> All services will be provided as per the agreed schedule.
                            Any changes must be communicated at least 24 hours in advance.</li>
                        <li><strong>Liability:</strong> {{ config('app.name') }} will not be liable for any loss or
                            damage to personal property during the journey. Travel insurance is recommended.</li>
                        <li><strong>Force Majeure:</strong> We are not responsible for delays or cancellations due to
                            circumstances beyond our control, including but not limited to natural disasters, strikes,
                            or government restrictions.</li>
                        <li><strong>Modifications:</strong> We reserve the right to modify these terms and conditions at
                            any time. Customers will be notified of any significant changes.</li>
                        <li><strong>Dispute Resolution:</strong> Any disputes arising from this booking will be subject
                            to the jurisdiction of local courts.</li>
                    </ul>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-content">
                <p>Thank you for choosing {{ config('app.name') }} for your travel needs!</p>
                <p>For any queries, please contact us at {{ websiteSetupValue('email') }} or
                    {{ websiteSetupValue('contact_number') }}</p>
            </div>
            <div class="footer-website">www.visitkashi.com</div>
        </div>
    </div>

    <!-- Print Button -->
    <button class="print-button" onclick="window.print()">
        Print / Download PDF
    </button>
</body>

</html>
