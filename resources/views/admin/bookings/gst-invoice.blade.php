<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice #{{ $booking->gst_invoice_number }} | {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #fff5f0 0%, #ffe8db 100%);
            padding: 20px;
            color: #2d3748;
            line-height: 1.5;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 20px 60px rgba(255, 102, 0, 0.15);
            border-radius: 16px;
            overflow: hidden;
        }

        /* Header Section - Ultra compact for A4 */
        .invoice-header {
            background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%);
            padding: 15px 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .invoice-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
            gap: 20px;
        }

        .company-info {
            flex: 1;
            min-width: 200px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .company-logo {
            max-width: 80px;
            height: auto;
            flex-shrink: 0;
        }

        .company-details {
            font-size: 9px;
            line-height: 1.4;
            opacity: 0.95;
        }

        .company-details p {
            margin: 1px 0;
        }

        .invoice-title-section {
            text-align: right;
            flex-shrink: 0;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tax-invoice-label {
            font-size: 10px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.3);
            padding: 2px 8px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .invoice-number {
            font-size: 12px;
            font-weight: 600;
            opacity: 0.9;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .invoice-date {
            font-size: 10px;
            opacity: 0.85;
        }

        /* Customer & Booking Info - Reduced padding */
        .info-section {
            padding: 20px 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            background: #fff9f5;
            border-bottom: 2px solid #FF6600;
        }

        .info-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(255, 102, 0, 0.08);
            border-left: 3px solid #FF6600;
        }

        .info-card h3 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #FF6600;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .info-item {
            margin-bottom: 8px;
            display: flex;
            align-items: start;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 100px;
            font-size: 11px;
        }

        .info-value {
            color: #2d3748;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-confirmed {
            background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%);
            color: white;
        }

        .status-in_progress {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        /* Services Table - Compact */
        .services-section {
            padding: 20px 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .services-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        .services-table thead {
            background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%);
            color: white;
        }

        .services-table thead th {
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .services-table thead th:first-child {
            border-radius: 6px 0 0 0;
        }

        .services-table thead th:last-child {
            border-radius: 0 6px 0 0;
            text-align: right;
        }

        .services-table tbody td {
            padding: 8px 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .services-table tbody tr:hover {
            background: #fff9f5;
        }

        .services-table tbody tr:last-child td {
            border-bottom: none;
        }

        .service-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 2px;
        }

        .service-type {
            font-size: 10px;
            color: #FF6600;
            font-weight: 600;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* GST Breakdown - Compact */
        .gst-breakdown {
            background: #fff9f5;
            padding: 15px 30px;
            border-top: 2px solid #e2e8f0;
        }

        .gst-table {
            width: 100%;
            max-width: 450px;
            margin-left: auto;
        }

        .gst-table tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .gst-table tr:last-child {
            border-bottom: none;
        }

        .gst-table td {
            padding: 6px 0;
            font-size: 12px;
        }

        .gst-table td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .gst-row {
            color: #FF6600 !important;
        }

        .total-row {
            font-size: 15px !important;
            font-weight: 700 !important;
            color: #FF6600 !important;
            padding-top: 10px !important;
        }

        .paid-row {
            color: #10b981 !important;
        }

        .due-row {
            color: #ef4444 !important;
            font-size: 14px !important;
        }

        /* Terms & Conditions - Compact */
        .terms-section {
            padding: 20px 30px;
            background: white;
        }

        .terms-section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .terms-content {
            font-size: 10px;
            line-height: 1.6;
            color: #4a5568;
        }

        .terms-content ul {
            list-style: none;
            padding: 0;
        }

        .terms-content li {
            padding: 4px 0;
            padding-left: 18px;
            position: relative;
        }

        .terms-content li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #FF6600;
            font-weight: 700;
        }

        /* Footer - Compact */
        .invoice-footer {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            padding: 15px 30px;
            color: white;
            text-align: center;
        }

        .footer-content {
            font-size: 10px;
            opacity: 0.9;
        }

        .footer-website {
            font-size: 13px;
            font-weight: 700;
            margin-top: 8px;
            color: #FF6600;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(255, 102, 0, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(255, 102, 0, 0.5);
        }

        /* Print Styles - Optimized for B&W */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .print-button {
                display: none;
            }

            .invoice-header::before,
            .invoice-header::after {
                display: none;
            }

            /* Black & White optimization */
            .invoice-header {
                background: white !important;
                color: black !important;
                border: 3px solid #000;
                border-bottom: 2px solid #000;
                padding: 12px 20px !important;
            }

            .company-logo {
                max-width: 70px !important;
                filter: brightness(0) saturate(100%) !important;
                opacity: 1 !important;
            }

            .company-details {
                font-size: 9px !important;
                line-height: 1.3 !important;
            }

            .company-details p {
                margin: 0.5px 0 !important;
            }

            .company-details,
            .invoice-title,
            .tax-invoice-label,
            .invoice-number,
            .invoice-date {
                color: black !important;
                opacity: 1 !important;
            }

            .invoice-title {
                font-size: 22px !important;
                margin-bottom: 3px !important;
            }

            .tax-invoice-label,
            .invoice-number {
                background: white !important;
                border: 2px solid #000;
                font-size: 9px !important;
                padding: 3px 8px !important;
                font-weight: 700 !important;
            }

            .invoice-date {
                font-size: 9px !important;
            }

            .info-section {
                background: white !important;
                border-bottom: 2px solid #000;
                padding: 12px 20px !important;
                gap: 15px !important;
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
            }

            .info-card {
                border: 2px solid #000 !important;
                border-left: 4px solid #000 !important;
                box-shadow: none;
                padding: 10px !important;
                border-radius: 4px !important;
                flex: 1 1 48% !important;
                max-width: 48% !important;
                min-width: 48% !important;
            }

            .info-card h3 {
                color: #000 !important;
                font-size: 10px !important;
                margin-bottom: 8px !important;
                font-weight: 700 !important;
                text-transform: uppercase;
                border-bottom: 1px solid #000;
                padding-bottom: 4px;
            }

            .info-item {
                margin-bottom: 5px !important;
            }

            .info-label {
                font-size: 9px !important;
                min-width: 90px !important;
                font-weight: 600 !important;
            }

            .info-value {
                font-size: 10px !important;
            }

            .status-badge {
                background: white !important;
                color: black !important;
                border: 2px solid #000;
                font-size: 9px !important;
                padding: 3px 8px !important;
                font-weight: 700 !important;
            }

            .services-section {
                padding: 12px 20px !important;
            }

            .section-title {
                font-size: 14px !important;
                margin-bottom: 10px !important;
                padding-bottom: 6px !important;
                font-weight: 700 !important;
                border-bottom: 2px solid #000 !important;
            }

            .services-table {
                margin-bottom: 12px !important;
                border: 2px solid #000 !important;
            }

            .services-table thead {
                background: #e8e8e8 !important;
                color: black !important;
                border-bottom: 2px solid #000 !important;
            }

            .services-table thead th {
                border-right: 1px solid #000;
                border-bottom: 2px solid #000 !important;
                padding: 8px 6px !important;
                font-size: 9px !important;
                font-weight: 700 !important;
                text-transform: uppercase;
            }

            .services-table thead th:last-child {
                border-right: none;
            }

            .services-table tbody td {
                border-bottom: 1px solid #999;
                border-right: 1px solid #ddd;
                padding: 8px 6px !important;
                font-size: 10px !important;
            }

            .services-table tbody td:last-child {
                border-right: none;
            }

            .services-table tbody tr:last-child td {
                border-bottom: none;
            }

            .service-name {
                font-size: 10px !important;
                font-weight: 600 !important;
            }

            .service-type {
                color: #000 !important;
                font-size: 9px !important;
                font-weight: 600 !important;
            }

            .gst-breakdown {
                background: #f5f5f5 !important;
                padding: 12px 20px !important;
                border: 2px solid #000;
                border-top: 3px solid #000;
            }

            .gst-table {
                border: 1px solid #999;
            }

            .gst-table tr {
                border-bottom: 1px solid #ccc;
            }

            .gst-table td {
                padding: 6px 8px !important;
                font-size: 11px !important;
            }

            .gst-table td:first-child {
                font-weight: 600;
            }

            .gst-row,
            .total-row,
            .paid-row,
            .due-row {
                color: #000 !important;
            }

            .total-row {
                font-size: 14px !important;
                font-weight: 700 !important;
                border-top: 2px solid #000 !important;
            }

            .due-row {
                font-weight: 700 !important;
                font-size: 13px !important;
            }

            .terms-section {
                padding: 12px 20px !important;
                border-top: 2px solid #000;
            }

            .terms-section h3 {
                font-size: 13px !important;
                margin-bottom: 10px !important;
                font-weight: 700 !important;
                border-bottom: 2px solid #000;
                padding-bottom: 6px;
            }

            .terms-content {
                font-size: 9px !important;
                line-height: 1.5 !important;
            }

            .terms-content li {
                padding: 3px 0 !important;
                padding-left: 16px !important;
            }

            .terms-content li::before {
                color: #000 !important;
                font-weight: 700 !important;
            }

            .invoice-footer {
                background: #e8e8e8 !important;
                color: black !important;
                padding: 10px 20px !important;
                border-top: 3px solid #000;
                text-align: center;
            }

            .footer-content {
                font-size: 9px !important;
            }

            .footer-content p {
                margin: 2px 0;
            }

            .footer-website {
                color: #000 !important;
                font-size: 12px !important;
                font-weight: 700 !important;
                margin-top: 6px !important;
            }

            /* Reduce margins for compact printing */
            @page {
                margin: 0.4cm;
            }

            /* Prevent page breaks in critical sections */
            .info-section {
                page-break-inside: avoid;
            }

            .info-card {
                page-break-inside: avoid;
            }

            .services-table {
                page-break-inside: auto;
            }

            .services-table thead {
                display: table-header-group;
            }

            .services-table tbody tr {
                page-break-inside: avoid;
            }

            .gst-breakdown {
                page-break-inside: avoid;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .invoice-header {
                padding: 15px;
            }

            .invoice-title {
                font-size: 24px;
            }

            .header-content {
                flex-direction: column;
            }

            .invoice-title-section {
                text-align: left;
            }

            .info-section {
                padding: 15px;
                grid-template-columns: 1fr;
            }

            .services-section {
                padding: 15px;
                overflow-x: auto;
            }

            .gst-breakdown {
                padding: 15px;
            }

            .terms-section {
                padding: 15px;
            }

            .print-button {
                bottom: 20px;
                right: 20px;
                padding: 10px 20px;
                font-size: 12px;
            }
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
                        @if (websiteSetupValue('company_legal_name'))
                            <p><strong>{{ websiteSetupValue('company_legal_name') }}</strong></p>
                        @else
                            <p><strong>{{ websiteSetupValue('site_title') ?? config('app.name') }}</strong></p>
                        @endif
                        <p>{{ websiteSetupValue('address') }}</p>
                        <p>Phone: {{ websiteSetupValue('contact_number') }}</p>
                        <p>WhatsApp: {{ websiteSetupValue('whats_app_number') }}</p>
                        <p>Email: {{ websiteSetupValue('email') }}</p>
                        @if (websiteSetupValue('company_gstin'))
                            <p style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.3);">
                                <strong>GSTIN: {{ websiteSetupValue('company_gstin') }}</strong>
                            </p>
                        @else
                            <p
                                style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.3); font-size: 11px; opacity: 0.8;">
                                <em>Note: Configure Company GSTIN in Website Setup</em>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="invoice-title-section">
                    <div class="tax-invoice-label">TAX INVOICE</div>
                    <h1 class="invoice-title">INVOICE</h1>
                    <div class="invoice-number">#{{ $booking->gst_invoice_number }}</div>
                    <div class="invoice-date">Date: {{ $booking->booking_date->format('d M, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Customer & Booking Info -->
        <div class="info-section">
            <div class="info-card">
                <h3>Bill To</h3>
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $booking->lead->guest_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact:</span>
                    <span class="info-value">{{ $booking->lead->contact }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Persons:</span>
                    <span class="info-value">{{ $booking->lead->pax ?? 'N/A' }}</span>
                </div>
                @if ($booking->lead->city)
                    <div class="info-item">
                        <span class="info-label">City:</span>
                        <span class="info-value">{{ $booking->lead->city }}</span>
                    </div>
                @endif
                @if ($booking->customer_gstin)
                    <div class="info-item">
                        <span class="info-label">GSTIN:</span>
                        <span class="info-value"><strong>{{ $booking->customer_gstin }}</strong></span>
                    </div>
                @endif
            </div>

            <div class="info-card">
                <h3>Booking Information</h3>
                @if ($booking->lead->short_plan)
                    <div class="info-item">
                        <span class="info-label">Package:</span>
                        <span class="info-value">{!! $booking->lead->short_plan !!}</span>
                    </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Booking Date:</span>
                    <span class="info-value">{{ $booking->booking_date->format('d M, Y') }}</span>
                </div>
                @if ($booking->lead->booking_start_date)
                    <div class="info-item">
                        <span class="info-label">Travel Date:</span>
                        <span class="info-value">
                            {{ \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M, Y') }}
                            @if ($booking->lead->booking_end_date && $booking->lead->booking_start_date != $booking->lead->booking_end_date)
                                - {{ \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M, Y') }}
                            @endif
                        </span>
                    </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $booking->booking_status }}">
                            {{ str_replace('_', ' ', $booking->booking_status) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="services-section">
            <h2 class="section-title">Services & Items</h2>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service Description</th>
                        <th class="text-center">HSN/SAC</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Rate</th>
                        <th class="text-right">Taxable Amt</th>
                        <th class="text-right">CGST<br>({{ $booking->gst_rate / 2 }}%)</th>
                        <th class="text-right">SGST<br>({{ $booking->gst_rate / 2 }}%)</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cgstRate = $booking->gst_rate / 2;
                        $sgstRate = $booking->gst_rate / 2;
                    @endphp
                    @if ($booking->quotation && $booking->quotation->items->count() > 0)
                        @foreach ($booking->quotation->items as $item)
                            @php
                                $itemTaxable = $item->total_price;
                                $itemCgst = ($itemTaxable * $cgstRate) / 100;
                                $itemSgst = ($itemTaxable * $sgstRate) / 100;
                                $itemTotal = $itemTaxable + $itemCgst + $itemSgst;
                            @endphp
                            <tr>
                                <td>
                                    <div class="service-type">
                                        {{ $item->serviceTemplate->serviceType->name ?? 'Service' }}</div>
                                    <div class="service-name">{{ $item->serviceTemplate->name }}</div>
                                    @if ($item->description)
                                        <div style="font-size: 11px; color: #718096; margin-top: 4px;">
                                            {{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="text-center">996511</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right">₹{{ number_format($itemTaxable, 2) }}</td>
                                <td class="text-right">₹{{ number_format($itemCgst, 2) }}</td>
                                <td class="text-right">₹{{ number_format($itemSgst, 2) }}</td>
                                <td class="text-right"><strong>₹{{ number_format($itemTotal, 2) }}</strong></td>
                            </tr>
                        @endforeach
                    @else
                        @php
                            $taxable = $booking->taxable_amount ?? $booking->total_amount;
                            $cgst = ($taxable * $cgstRate) / 100;
                            $sgst = ($taxable * $sgstRate) / 100;
                            $total = $taxable + $cgst + $sgst;
                        @endphp
                        <tr>
                            <td>
                                <div class="service-name">Tour Package</div>
                                <div style="font-size: 12px; color: #718096; margin-top: 8px;">
                                    {!! $booking->lead->plan_detail ?? 'Complete tour package as per booking' !!}
                                </div>
                            </td>
                            <td class="text-center">996511</td>
                            <td class="text-center">1</td>
                            <td class="text-right">₹{{ number_format($taxable, 2) }}</td>
                            <td class="text-right">₹{{ number_format($taxable, 2) }}</td>
                            <td class="text-right">₹{{ number_format($cgst, 2) }}</td>
                            <td class="text-right">₹{{ number_format($sgst, 2) }}</td>
                            <td class="text-right"><strong>₹{{ number_format($total, 2) }}</strong></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- GST Breakdown -->
        <div class="gst-breakdown">
            <table class="gst-table">
                <tr>
                    <td>Taxable Amount:</td>
                    <td>₹{{ number_format($booking->taxable_amount ?? $booking->total_amount, 2) }}</td>
                </tr>
                <tr class="gst-row">
                    <td>CGST ({{ $booking->gst_rate / 2 }}%):</td>
                    <td>₹{{ number_format(($booking->gst_amount ?? 0) / 2, 2) }}</td>
                </tr>
                <tr class="gst-row">
                    <td>SGST ({{ $booking->gst_rate / 2 }}%):</td>
                    <td>₹{{ number_format(($booking->gst_amount ?? 0) / 2, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Amount (Inc. GST):</td>
                    <td>₹{{ number_format(($booking->taxable_amount ?? $booking->total_amount) + ($booking->gst_amount ?? 0), 2) }}
                    </td>
                </tr>
                <tr class="paid-row">
                    <td>Amount Paid:</td>
                    <td>₹{{ number_format($booking->payments_sum_amount ?? 0, 2) }}</td>
                </tr>
                <tr class="due-row">
                    <td><strong>Balance Due:</strong></td>
                    <td><strong>₹{{ number_format(($booking->taxable_amount ?? $booking->total_amount) + ($booking->gst_amount ?? 0) - ($booking->payments_sum_amount ?? 0), 2) }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Terms & Conditions -->
        <div class="terms-section">
            <h3>Terms & Conditions</h3>
            <div class="terms-content">
                @if ($booking->quotation && $booking->quotation->terms_conditions)
                    {!! $booking->quotation->terms_conditions !!}
                @else
                    <ul>
                        <li><strong>Payment Terms:</strong> Full payment or an agreed deposit must be made at the time
                            of booking. The remaining balance must be settled before the start of the journey.</li>
                        <li><strong>Cancellation Policy:</strong> Cancellations made 7 days or more before the scheduled
                            date are eligible for a refund as per our cancellation policy. Cancellations made within 48
                            hours are non-refundable.</li>
                        <li><strong>Service Delivery:</strong> All services will be provided as per the agreed schedule.
                            Any changes must be communicated at least 24 hours in advance.</li>
                        <li><strong>GST Compliance:</strong> This is a GST-compliant tax invoice. Please retain this for
                            your tax records.</li>
                        <li><strong>Liability:</strong> {{ config('app.name') }} will not be liable for any loss or
                            damage to personal property during the journey. Travel insurance is recommended.</li>
                        <li><strong>Force Majeure:</strong> We are not responsible for delays or cancellations due to
                            circumstances beyond our control, including but not limited to natural disasters, strikes,
                            or government restrictions.</li>
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
                <p style="margin-top: 12px; font-size: 11px;">This is a computer-generated GST invoice and does not
                    require a signature.</p>
            </div>
            <div class="footer-website">www.visitkashi.com</div>
        </div>
    </div>

    <!-- Print Button -->
    <button class="print-button" onclick="window.print()">
        🖨️ Print / Download PDF
    </button>
</body>

</html>
