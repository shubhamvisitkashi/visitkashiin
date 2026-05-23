<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Report - {{ $booking->booking_number }}</title>
    <style>
        @page {
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.6;
            color: #1f2937;
            background: #ffffff;
        }

        /* Header Section */
        .report-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 25px 30px;
            margin: -15mm -15mm 20px -15mm;
            border-radius: 0 0 8px 8px;
        }

        .report-header .company-name {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            opacity: 0.95;
            margin-bottom: 8px;
        }

        .report-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .report-header .booking-meta {
            font-size: 12px;
            opacity: 0.9;
            display: table;
            width: 100%;
        }

        .report-header .booking-meta>div {
            display: table-cell;
            padding: 5px 0;
        }

        .report-header .booking-meta .right {
            text-align: right;
        }

        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .column:first-child {
            padding-right: 15px;
        }

        .column:last-child {
            padding-left: 15px;
        }

        /* Section Styles */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info Card */
        .info-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            display: table-cell;
            width: 40%;
            font-weight: 600;
            color: #6b7280;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            vertical-align: top;
            padding-right: 10px;
        }

        .info-value {
            display: table-cell;
            width: 60%;
            color: #1f2937;
            font-size: 11px;
            font-weight: 500;
        }

        /* Plan Box */
        .plan-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            font-size: 10px;
            line-height: 1.7;
            color: #78350f;
        }

        /* Service Period Card */
        .period-card {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            border-radius: 6px;
            padding: 15px;
            text-align: center;
        }

        .period-card .date-item {
            display: inline-block;
            margin: 0 15px;
            text-align: center;
        }

        .period-card .date-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .period-card .date-value {
            font-size: 13px;
            font-weight: 700;
            color: #4f46e5;
        }

        .period-card .duration-badge {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            margin-top: 8px;
        }

        /* Services Table */
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .services-table thead {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
        }

        .services-table th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .services-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .services-table tbody tr {
            background: white;
            transition: background 0.2s;
        }

        .services-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .services-table .service-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .services-table .service-type {
            font-size: 8px;
            color: #6b7280;
            background: #e5e7eb;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }

        /* Financial Summary */
        .financial-summary {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .financial-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            padding: 10px 0;
        }

        .financial-row.total {
            border-top: 2px solid #10b981;
            padding-top: 15px;
            margin-top: 15px;
        }

        .financial-label {
            display: table-cell;
            width: 60%;
            font-weight: 600;
            font-size: 11px;
            color: #065f46;
        }

        .financial-value {
            display: table-cell;
            width: 40%;
            text-align: right;
            font-weight: 700;
            font-size: 14px;
        }

        .financial-row.total .financial-value {
            font-size: 18px;
        }

        /* Payment Status Grid */
        .payment-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .payment-item {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border-right: 1px solid #e5e7eb;
        }

        .payment-item:last-child {
            border-right: none;
        }

        .payment-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .payment-value {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Status Card */
        .status-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
        }

        .status-card .status-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }

        .report-footer p {
            margin: 5px 0;
        }

        .report-footer .timestamp {
            font-weight: 600;
            color: #6b7280;
        }

        /* Utility Classes */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-success {
            color: #10b981;
        }

        .text-danger {
            color: #ef4444;
        }

        .text-primary {
            color: #4f46e5;
        }

        .text-warning {
            color: #f59e0b;
        }

        .font-bold {
            font-weight: 700;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-15 {
            margin-bottom: 15px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Report Header -->
    <div class="report-header">
        <div class="company-name">VISITKASHI CRM</div>
        <h1>BOOKING REPORT</h1>
        <div class="booking-meta">
            <div>
                <strong>Booking #:</strong> {{ $booking->booking_number }}
            </div>
            <div class="right">
                <strong>Date:</strong> {{ $booking->booking_date->format('d M Y') }}
            </div>
        </div>
    </div>

    <!-- Customer & Service Period - Two Column -->
    <div class="two-column mb-20">
        <div class="column">
            <div class="section">
                <div class="section-title">Customer Information</div>
                <div class="info-card">
                    <div class="info-row">
                        <div class="info-label">Guest Name</div>
                        <div class="info-value">{{ $booking->lead->guest_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value">{{ $booking->lead->contact ?? 'N/A' }}</div>
                    </div>
                    @if ($booking->lead->email)
                        <div class="info-row">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $booking->lead->email }}</div>
                        </div>
                    @endif
                    @if ($booking->lead->pax)
                        <div class="info-row">
                            <div class="info-label">Group Size</div>
                            <div class="info-value">{{ $booking->lead->pax }} Person(s)</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="column">
            @if ($startDate && $endDate)
                <div class="section">
                    <div class="section-title">Service Period</div>
                    <div class="period-card">
                        <div class="date-item">
                            <div class="date-label">Check-In</div>
                            <div class="date-value">{{ $startDate->format('d M Y') }}</div>
                        </div>
                        <div class="date-item">
                            <div class="date-label">Check-Out</div>
                            <div class="date-value">{{ $endDate->format('d M Y') }}</div>
                        </div>
                        <div>
                            <span class="duration-badge">{{ $startDate->diffInDays($endDate) + 1 }} Day(s)</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tour Plan -->
    @if ($booking->lead && $booking->lead->short_plan)
        <div class="section">
            <div class="section-title">Tour Plan Overview</div>
            <div class="plan-box">
                {!! strip_tags($booking->lead->short_plan, '<p><br><strong><em><ul><ol><li>') !!}
            </div>
        </div>
    @endif

    <!-- Services Breakdown -->
    @if ($booking->quotation && $booking->quotation->items->count() > 0)
        <div class="section">
            <div class="section-title">Services Breakdown</div>
            <table class="services-table">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="28%">Service Details</th>
                        <th width="12%">Date</th>
                        <th width="6%" class="text-center">Qty</th>
                        <th width="12%" class="text-right">Unit Price</th>
                        <th width="12%" class="text-right">Total</th>
                        <th width="12%" class="text-right">Vendor Cost</th>
                        <th width="14%" class="text-right">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking->quotation->items as $index => $item)
                        @php
                            $assignment = $booking->serviceAssignments->where('quotation_item_id', $item->id)->first();
                            $vendorCost = $assignment ? $assignment->assigned_cost : 0;
                            $totalPrice = $item->total_price;
                            $margin = $totalPrice - $vendorCost;
                            $marginPercent = $totalPrice > 0 ? ($margin / $totalPrice) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="service-name">{{ $item->serviceTemplate->name }}</div>
                                <span class="service-type">{{ $item->serviceType->name }}</span>
                            </td>
                            <td>
                                @if ($item->service_date)
                                    {{ $item->service_date->format('d M Y') }}
                                @else
                                    <span style="color: #9ca3af;">Not set</span>
                                @endif
                            </td>
                            <td class="text-center font-bold">{{ $item->quantity }}</td>
                            <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right text-primary font-bold">₹{{ number_format($totalPrice, 2) }}</td>
                            <td class="text-right text-danger">
                                @if ($assignment)
                                    ₹{{ number_format($vendorCost, 2) }}
                                @else
                                    <span style="color: #d1d5db;">—</span>
                                @endif
                            </td>
                            <td class="text-right text-success font-bold">
                                @if ($assignment)
                                    ₹{{ number_format($margin, 2) }}
                                    <div><span
                                            class="badge badge-success">{{ number_format($marginPercent, 1) }}%</span>
                                    </div>
                                @else
                                    <span style="color: #d1d5db;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Financial Summary -->
    <div class="section">
        <div class="section-title">Financial Summary</div>
        <div class="financial-summary">
            <div class="financial-row">
                <div class="financial-label">Customer Payment (Total)</div>
                <div class="financial-value text-primary">₹{{ number_format($booking->total_amount, 2) }}</div>
            </div>
            <div class="financial-row">
                <div class="financial-label">Vendor Payment (Total)</div>
                <div class="financial-value text-danger">₹{{ number_format($totalVendorCost, 2) }}</div>
            </div>
            <div class="financial-row total">
                <div class="financial-label">Net Profit Margin</div>
                <div class="financial-value text-success">
                    ₹{{ number_format($totalMargin, 2) }}
                    <span class="badge badge-success"
                        style="font-size: 10px; margin-left: 8px;">{{ number_format($totalMarginPercent, 1) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="section">
        <div class="section-title">Payment Status</div>
        <div class="info-card">
            <div class="payment-grid">
                <div class="payment-item">
                    <div class="payment-label">Paid Amount</div>
                    <div class="payment-value text-success">₹{{ number_format($booking->paid_amount, 2) }}</div>
                    <span class="badge badge-success">Received</span>
                </div>
                <div class="payment-item">
                    <div class="payment-label">Pending Amount</div>
                    <div class="payment-value text-warning">₹{{ number_format($booking->pending_amount, 2) }}</div>
                    <span class="badge badge-warning">Due</span>
                </div>
                <div class="payment-item">
                    <div class="payment-label">Status</div>
                    <div class="payment-value" style="font-size: 14px;">
                        @if ($booking->pending_amount <= 0)
                            <span class="badge badge-success">Fully Paid</span>
                        @elseif($booking->paid_amount > 0)
                            <span class="badge badge-info">Partially Paid</span>
                        @else
                            <span class="badge badge-danger">Unpaid</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Information -->
    <div class="section">
        <div class="section-title">Booking Information</div>
        <div class="info-card">
            <div class="info-row">
                <div class="info-label">Booking Status</div>
                <div class="info-value">
                    @if ($booking->booking_status == 'confirmed')
                        <span class="badge badge-success">✓ Confirmed</span>
                    @elseif($booking->booking_status == 'in_progress')
                        <span class="badge badge-info">⟳ In Progress</span>
                    @elseif($booking->booking_status == 'completed')
                        <span class="badge badge-success">✓ Completed</span>
                    @else
                        <span class="badge badge-danger">✗ Cancelled</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Created By</div>
                <div class="info-value">{{ $booking->createdBy->name ?? 'System' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Booking Date</div>
                <div class="info-value">{{ $booking->booking_date->format('d M Y, h:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="report-footer">
        <p class="timestamp">Generated on {{ now()->format('d M Y, h:i A') }}</p>
        <p>This is a computer-generated document and does not require a signature.</p>
        <p style="margin-top: 10px; font-size: 7px;">© {{ now()->format('Y') }} VisitKashi CRM. All rights reserved.
        </p>
    </div>
</body>

</html>
