@extends('admin.layouts.app')

@section('content')
    <!-- Enhanced Booking Report View -->
    <div class="container-fluid px-4 py-4" id="reportContent">
        <!-- Action Bar -->
        <div class="action-bar no-print">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 text-gray-800 font-weight-bold">📊 Booking Report</h1>
                    <p class="text-muted mb-0">{{ $booking->booking_number }} • {{ $booking->booking_date->format('d M Y') }}
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                        <i data-feather="arrow-left" class="feather-sm"></i> Back
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i data-feather="printer" class="feather-sm"></i> Print
                    </button>
                    <button onclick="shareReport()" class="btn btn-outline-info">
                        <i data-feather="share-2" class="feather-sm"></i> Share
                    </button>
                    <a href="{{ route('booking.report', $booking->id) }}" class="btn btn-primary">
                        <i data-feather="download" class="feather-sm"></i> Download PDF
                    </a>
                    <a href="{{ route('booking.report.export', $booking->id) }}" class="btn btn-success">
                        <i data-feather="file-text" class="feather-sm"></i> Download Excel
                    </a>
                </div>
            </div>
        </div>


        <!-- Summary Cards Row - Compact -->
        <div class="row g-2 mb-3">
            <!-- Total Revenue Card -->
            <div class="col-lg-3 col-md-6">
                <div class="summary-card-compact revenue">
                    <div class="card-icon-compact">
                        <i data-feather="dollar-sign"></i>
                    </div>
                    <div class="card-content-compact">
                        <div class="card-label-compact">Revenue</div>
                        <div class="card-value-compact">₹{{ number_format($booking->total_amount, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Vendor Cost Card -->
            <div class="col-lg-3 col-md-6">
                <div class="summary-card-compact cost">
                    <div class="card-icon-compact">
                        <i data-feather="trending-down"></i>
                    </div>
                    <div class="card-content-compact">
                        <div class="card-label-compact">Cost</div>
                        <div class="card-value-compact">₹{{ number_format($totalVendorCost, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="col-lg-3 col-md-6">
                <div class="summary-card-compact profit">
                    <div class="card-icon-compact">
                        <i data-feather="trending-up"></i>
                    </div>
                    <div class="card-content-compact">
                        <div class="card-label-compact">Profit</div>
                        <div class="card-value-compact">₹{{ number_format($totalMargin, 2) }}</div>
                        <span class="profit-badge-compact">{{ number_format($totalMarginPercent, 1) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status Card -->
            <div class="col-lg-3 col-md-6">
                <div class="summary-card-compact payment">
                    <div class="card-icon-compact">
                        <i data-feather="credit-card"></i>
                    </div>
                    <div class="card-content-compact">
                        <div class="card-label-compact">Payment</div>
                        <div class="card-value-compact">
                            @if ($booking->pending_amount <= 0)
                                <span class="status-badge-compact success">Paid</span>
                            @elseif($booking->paid_amount > 0)
                                <span class="status-badge-compact warning">Partial</span>
                            @else
                                <span class="status-badge-compact danger">Unpaid</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Report Card -->
        <div class="card shadow-sm border-0 report-card">
            <div class="card-body p-0">
                <!-- Professional Header -->
                <div class="report-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="company-info">
                                <div class="company-logo">
                                    <i data-feather="briefcase" style="width: 40px; height: 40px;"></i>
                                </div>
                                <div>
                                    <h6 class="company-name">VISITKASHI CRM</h6>
                                    <h2 class="report-title">BOOKING REPORT</h2>
                                    <p class="report-meta">
                                        {{ $booking->booking_number }} • {{ $booking->booking_date->format('d M Y') }}
                                        <span class="mx-2">•</span>
                                        <i data-feather="user-check" style="width: 14px; height: 14px;"></i>
                                        {{ $booking->createdBy->name ?? 'System' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="booking-status-badge">
                                @if ($booking->booking_status == 'confirmed')
                                    <span class="badge-xl badge-success">
                                        <i data-feather="check-circle"></i> Confirmed
                                    </span>
                                @elseif($booking->booking_status == 'in_progress')
                                    <span class="badge-xl badge-info">
                                        <i data-feather="clock"></i> In Progress
                                    </span>
                                @elseif($booking->booking_status == 'completed')
                                    <span class="badge-xl badge-success">
                                        <i data-feather="check-circle"></i> Completed
                                    </span>
                                @else
                                    <span class="badge-xl badge-danger">
                                        <i data-feather="x-circle"></i> Cancelled
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-3 pt-2 pb-1">
                    <!-- Customer & Service Period - Ultra Compact -->
                    <div class="info-row-compact mb-2">
                        <div class="info-group-compact">
                            <i data-feather="user" style="width: 13px; height: 13px;"></i>
                            <strong>{{ $booking->lead->guest_name ?? 'N/A' }}</strong>
                            <span class="text-muted mx-1">•</span>
                            <span>{{ $booking->lead->contact ?? 'N/A' }}</span>
                            @if ($booking->lead->email)
                                <span class="text-muted mx-1">•</span>
                                <span>{{ $booking->lead->email }}</span>
                            @endif
                            @if ($booking->lead->pax)
                                <span class="text-muted mx-1">•</span>
                                <span class="badge badge-soft-primary">{{ $booking->lead->pax }} Pax</span>
                            @endif
                        </div>
                        @if ($startDate && $endDate)
                            <div class="info-group-compact">
                                <i data-feather="calendar" style="width: 13px; height: 13px;"></i>
                                <strong>{{ $startDate->format('d M Y') }}</strong>
                                <i data-feather="arrow-right" style="width: 13px; height: 13px;" class="mx-1"></i>
                                <strong>{{ $endDate->format('d M Y') }}</strong>
                                <span class="text-muted mx-1">•</span>
                                <span class="badge badge-soft-info">{{ $startDate->diffInDays($endDate) + 1 }} Days</span>
                            </div>
                        @endif
                    </div>

                    <!-- Tour Plan -->
                    @if ($booking->lead && $booking->lead->short_plan)
                        <div class="collapsible-section mb-4">
                            <button class="section-toggle" onclick="toggleSection('tourPlan')">
                                <div class="d-flex align-items-center">
                                    <i data-feather="map" class="me-2"></i>
                                    <h5 class="mb-0">Tour Plan Overview</h5>
                                </div>
                                <i data-feather="chevron-down" class="toggle-icon"></i>
                            </button>
                            <div class="section-content show" id="tourPlan">
                                <div class="plan-content">
                                    {!! strip_tags($booking->lead->short_plan, '<p><br><strong><em><ul><ol><li>') !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Services Breakdown -->
                    @if ($booking->quotation && $booking->quotation->items->count() > 0)
                        <div class="collapsible-section mb-4">
                            <button class="section-toggle" onclick="toggleSection('services')">
                                <div class="d-flex align-items-center">
                                    <i data-feather="package" class="me-2"></i>
                                    <h5 class="mb-0">Services Breakdown</h5>
                                    <span class="badge badge-soft-primary ms-2">{{ $booking->quotation->items->count() }}
                                        Items</span>
                                </div>
                                <i data-feather="chevron-down" class="toggle-icon"></i>
                            </button>
                            <div class="section-content show" id="services">
                                <div class="table-responsive">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th width="4%">#</th>
                                                <th width="22%">Service Details</th>
                                                <th width="16%">Vendor</th>
                                                <th width="10%">Date</th>
                                                <th width="6%" class="text-center">Qty</th>
                                                <th width="10%" class="text-end">Unit Price</th>
                                                <th width="10%" class="text-end">Total</th>
                                                <th width="10%" class="text-end">Vendor Cost</th>
                                                <th width="12%" class="text-end">Margin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($booking->quotation->items as $index => $item)
                                                @php
                                                    $assignment = $booking->serviceAssignments
                                                        ->where('quotation_item_id', $item->id)
                                                        ->first();
                                                    $vendorCost = $assignment ? $assignment->assigned_cost : 0;
                                                    $totalPrice = $item->total_price;
                                                    $margin = $totalPrice - $vendorCost;
                                                    $marginPercent =
                                                        $totalPrice > 0 ? ($margin / $totalPrice) * 100 : 0;
                                                @endphp
                                                <tr>
                                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="service-name">{{ $item->serviceTemplate->name }}</div>
                                                        <span
                                                            class="service-type-badge">{{ $item->serviceType->name }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($assignment && $assignment->serviceProvider)
                                                            <span class="vendor-badge">
                                                                <i data-feather="briefcase"
                                                                    style="width: 12px; height: 12px;"></i>
                                                                {{ $assignment->serviceProvider->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">Not assigned</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->service_date)
                                                            <span class="date-badge">
                                                                <i data-feather="calendar"
                                                                    style="width: 12px; height: 12px;"></i>
                                                                {{ $item->service_date->format('d M Y') }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">Not set</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="qty-badge">{{ $item->quantity }}</span>
                                                    </td>
                                                    <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                                    <td class="text-end">
                                                        <strong
                                                            class="text-primary">₹{{ number_format($totalPrice, 2) }}</strong>
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($assignment)
                                                            <strong
                                                                class="text-danger">₹{{ number_format($vendorCost, 2) }}</strong>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($assignment)
                                                            <div class="margin-display">
                                                                <strong
                                                                    class="text-success">₹{{ number_format($margin, 2) }}</strong>
                                                                <div class="margin-bar-container">
                                                                    <div class="margin-bar"
                                                                        style="width: {{ min($marginPercent, 100) }}%">
                                                                    </div>
                                                                </div>
                                                                <span
                                                                    class="margin-percent">{{ number_format($marginPercent, 1) }}%</span>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                                <td class="text-end"><strong
                                                        class="text-primary">₹{{ number_format($booking->total_amount, 2) }}</strong>
                                                </td>
                                                <td class="text-end"><strong
                                                        class="text-danger">₹{{ number_format($totalVendorCost, 2) }}</strong>
                                                </td>
                                                <td class="text-end"><strong
                                                        class="text-success">₹{{ number_format($totalMargin, 2) }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Financial Summary with Visualization -->
                    <div class="collapsible-section mb-3">
                        <button class="section-toggle compact" onclick="toggleSection('financial')">
                            <div class="d-flex align-items-center">
                                <i data-feather="dollar-sign" class="me-2"></i>
                                <h5 class="mb-0">Financial Summary</h5>
                            </div>
                            <i data-feather="chevron-down" class="toggle-icon"></i>
                        </button>
                        <div class="section-content" id="financial">
                            <div class="financial-viz-compact">
                                <!-- Main Financial Overview -->
                                <div class="financial-breakdown-compact">
                                    <div class="breakdown-item-compact revenue">
                                        <div class="breakdown-label-compact">
                                            <i data-feather="arrow-up-circle"></i>
                                            Customer Payment
                                        </div>
                                        <div class="breakdown-value-compact">
                                            ₹{{ number_format($booking->total_amount, 2) }}</div>
                                    </div>
                                    <div class="breakdown-item-compact cost">
                                        <div class="breakdown-label-compact">
                                            <i data-feather="arrow-down-circle"></i>
                                            Vendor Payment
                                        </div>
                                        <div class="breakdown-value-compact">
                                            ₹{{ number_format($totalVendorCost, 2) }}</div>
                                    </div>
                                    <div class="breakdown-item-compact profit">
                                        <div class="breakdown-label-compact">
                                            <i data-feather="trending-up"></i>
                                            Net Profit ({{ number_format($totalMarginPercent, 1) }}%)
                                        </div>
                                        <div class="breakdown-value-compact">₹{{ number_format($totalMargin, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Service Type Breakdown Table -->
                                @if (count($serviceTypeBreakdown) > 0)
                                    <div class="breakdown-table-section mt-3">
                                        <h6 class="breakdown-table-title">
                                            <i data-feather="package" style="width: 14px; height: 14px;"></i>
                                            Spending by Service Type
                                        </h6>
                                        <table class="table table-sm table-bordered breakdown-table">
                                            <thead>
                                                <tr>
                                                    <th width="60%">Service Type</th>
                                                    <th width="40%" class="text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($serviceTypeBreakdown as $serviceType => $amount)
                                                    <tr>
                                                        <td>{{ $serviceType }}</td>
                                                        <td class="text-right font-weight-bold">
                                                            ₹{{ number_format($amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-active">
                                                    <td class="font-weight-bold">Total</td>
                                                    <td class="text-right font-weight-bold">
                                                        ₹{{ number_format($totalVendorCost, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endif

                                <!-- Vendor Payment Breakdown Table -->
                                @if (count($vendorBreakdown) > 0)
                                    <div class="breakdown-table-section mt-3">
                                        <h6 class="breakdown-table-title">
                                            <i data-feather="users" style="width: 14px; height: 14px;"></i>
                                            Vendor Payments
                                        </h6>
                                        <table class="table table-sm table-bordered breakdown-table">
                                            <thead>
                                                <tr>
                                                    <th width="60%">Vendor Name</th>
                                                    <th width="40%" class="text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vendorBreakdown as $vendor => $amount)
                                                    <tr>
                                                        <td>{{ $vendor }}</td>
                                                        <td class="text-right font-weight-bold">
                                                            ₹{{ number_format($amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-active">
                                                    <td class="font-weight-bold">Total</td>
                                                    <td class="text-right font-weight-bold">
                                                        ₹{{ number_format($totalVendorCost, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status with Progress -->
                    <div class="collapsible-section mb-3">
                        <button class="section-toggle compact" onclick="toggleSection('payment')">
                            <i data-feather="credit-card" class="me-2"></i>
                            <h5 class="mb-0">Payment Status</h5>
                    </div>
                    <i data-feather="chevron-down" class="toggle-icon"></i>
                    </button>
                    <div class="section-content" id="payment">
                        <div class="payment-progress-compact">
                            <div class="progress-header-compact">
                                <div class="progress-label-compact">
                                    <span>Payment Progress</span>
                                    <span class="progress-percentage-compact">
                                        {{ $booking->total_amount > 0 ? number_format(($booking->paid_amount / $booking->total_amount) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                            <div class="payment-progress-bar-compact">
                                <div class="progress-fill"
                                    style="width: {{ $booking->total_amount > 0 ? ($booking->paid_amount / $booking->total_amount) * 100 : 0 }}%">
                                </div>
                            </div>
                            <div class="row g-2 mt-2">
                                <div class="col-md-4">
                                    <div class="payment-stat-compact paid">
                                        <div class="stat-icon-compact">
                                            <i data-feather="check-circle"></i>
                                        </div>
                                        <div class="stat-content-compact">
                                            <div class="stat-label-compact">Paid</div>
                                            <div class="stat-value-compact">
                                                ₹{{ number_format($booking->paid_amount, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-stat-compact pending">
                                        <div class="stat-icon-compact">
                                            <i data-feather="clock"></i>
                                        </div>
                                        <div class="stat-content-compact">
                                            <div class="stat-label-compact">Pending</div>
                                            <div class="stat-value-compact">
                                                ₹{{ number_format($booking->pending_amount, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-stat-compact status">
                                        <div class="stat-icon-compact">
                                            <i data-feather="info"></i>
                                        </div>
                                        <div class="stat-content-compact">
                                            <div class="stat-label-compact">Status</div>
                                            <div class="stat-value-compact" style="font-size: 0.875rem;">
                                                @if ($booking->pending_amount <= 0)
                                                    <span class="stat-badge success">Paid</span>
                                                @elseif($booking->paid_amount > 0)
                                                    <span class="stat-badge info">Partial</span>
                                                @else
                                                    <span class="stat-badge danger">Unpaid</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Footer -->
                <div class="report-footer">
                    <p class="timestamp"><strong>Generated on:</strong> {{ now()->format('d M Y, h:i A') }}</p>
                    <p class="disclaimer">This is a computer-generated document and does not require a signature.</p>
                    <p class="copyright">© {{ now()->format('Y') }} VisitKashi CRM. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .action-bar,
            .collapsible-section button {
                display: none !important;
            }

            .section-content {
                display: block !important;
            }

            body {
                background: white !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }

            #reportContent {
                margin-top: 0 !important;
            }
        }

        /* General Styles */
        .feather-sm {
            width: 18px;
            height: 18px;
        }

        /* Top margin for fixed header */
        #reportContent {
            margin-top: 80px;
        }

        /* Summary Cards */
        .summary-card {

            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .summary-card.revenue {
            border-left-color: #4f46e5;
        }

        .summary-card.cost {
            border-left-color: #ef4444;
        }

        .summary-card.profit {
            border-left-color: #10b981;
        }

        .summary-card.payment {
            border-left-color: #f59e0b;
        }

        .summary-card .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .summary-card.revenue .card-icon {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            color: #4f46e5;
        }

        .summary-card.cost .card-icon {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #ef4444;
        }

        .summary-card.profit .card-icon {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #10b981;
        }

        .summary-card.payment .card-icon {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #f59e0b;
        }

        .summary-card .card-icon i {
            width: 28px;
            height: 28px;
        }

        .summary-card .card-content {
            flex: 1;
        }

        .summary-card .card-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .summary-card .card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .summary-card .card-subtitle {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        /* Compact Summary Cards */
        .summary-card-compact {
            position: relative;
            z-index: 5;
            background: white;
            border-radius: 8px;
            padding: 0.875rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border-left: 3px solid;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .summary-card-compact:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .summary-card-compact.revenue {
            border-left-color: #4f46e5;
        }

        .summary-card-compact.cost {
            border-left-color: #ef4444;
        }

        .summary-card-compact.profit {
            border-left-color: #10b981;
        }

        .summary-card-compact.payment {
            border-left-color: #f59e0b;
        }

        .summary-card-compact .card-icon-compact {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .summary-card-compact.revenue .card-icon-compact {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            color: #4f46e5;
        }

        .summary-card-compact.cost .card-icon-compact {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #ef4444;
        }

        .summary-card-compact.profit .card-icon-compact {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #10b981;
        }

        .summary-card-compact.payment .card-icon-compact {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #f59e0b;
        }

        .summary-card-compact .card-icon-compact i {
            width: 20px;
            height: 20px;
        }

        .summary-card-compact .card-content-compact {
            flex: 1;
            min-width: 0;
        }

        .summary-card-compact .card-label-compact {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .summary-card-compact .card-value-compact {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.2;
        }

        .profit-badge-compact {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            padding: 0.125rem 0.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.688rem;
            margin-top: 0.25rem;
        }

        .status-badge-compact {
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-block;
        }

        .status-badge-compact.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge-compact.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge-compact.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .profit-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-badge.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Report Card */
        .report-card {
            border-radius: 12px;
            overflow: hidden;
        }

        .report-header {
            position: relative;
            z-index: 1;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 2rem;
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .company-logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .company-name {
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .report-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .report-meta {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }

        .badge-xl {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-xl i {
            width: 20px;
            height: 20px;
        }

        /* Section Cards */
        .section-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            height: 100%;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;

            /* Compact Section Cards */
            .section-card-compact {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 0.75rem;
            }

            .section-header-compact {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 0.75rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .section-header-compact h6 {
                font-size: 0.875rem;
                font-weight: 600;
                color: #374151;
            }

            /* Inline Info List */
            .info-list-compact-inline {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .info-item-inline {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.813rem;
            }

            .label-inline {
                font-weight: 600;
                color: #6b7280;
                min-width: 60px;
            }

            .value-inline {
                color: #1f2937;
                font-weight: 500;
            }

            /* Compact Period Display */
            .period-display-compact {
                display: flex;
                align-items: center;
                justify-content: space-around;
                gap: 1rem;
                padding: 0.75rem 0;
            }

            .date-box-compact {
                text-align: center;
            }

            .date-label-compact {
                font-size: 0.688rem;
                color: #6b7280;
                font-weight: 600;
                text-transform: uppercase;
                margin-bottom: 0.25rem;
            }

            .date-value-compact {
                font-size: 1rem;
                font-weight: 700;
                color: #1f2937;
            }

            .date-arrow-compact {
                color: #9ca3af;
            }

            .duration-badge-compact {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                background: #dbeafe;
                color: #1e40af;
                padding: 0.25rem 0.75rem;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .section-header i {
            width: 20px;
            height: 20px;
            color: #4f46e5;
        }

        .section-header h5 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item .label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
        }

        .info-item .value {
            font-size: 1rem;
            font-weight: 500;
            color: #1f2937;
            text-align: right;
        }


        /* Ultra Compact Info Row */
        .info-row-compact {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.813rem;
        }

        .info-group-compact {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: #374151;
        }

        .info-group-compact i {
            color: #6b7280;
        }

        .info-group-compact strong {
            color: #1f2937;
        }

        @media (max-width: 768px) {
            .info-row-compact {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        /* Period Card */
        .period-card {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        }

        .period-display {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            padding: 1rem 0;
        }

        .date-box {
            text-align: center;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            min-width: 120px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .date-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .date-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4f46e5;
            line-height: 1;
        }

        .date-month {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .date-arrow {
            color: #4f46e5;
        }

        .date-arrow i {
            width: 32px;
            height: 32px;
        }

        .duration-badge-lg {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
        }

        .duration-badge-lg i {
            width: 18px;
            height: 18px;
        }

        /* Collapsible Sections */
        .collapsible-section {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-toggle {
            width: 100%;
            background: #f9fafb;
            border: none;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .section-toggle:hover {
            background: #f3f4f6;
        }

        .section-toggle h5 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
        }

        .section-toggle i {
            width: 20px;
            height: 20px;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .section-toggle.collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        .section-content {
            padding: 1.5rem;
            background: white;
            display: none;
        }

        .section-content.show {
            display: block;
        }

        .plan-content {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1.5rem;
            border-radius: 8px;
            line-height: 1.8;
            color: #78350f;
        }

        /* Modern Table */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.9rem;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
        }

        .modern-table th {
            padding: 1rem 0.75rem;
            font-weight: 600;

            .vendor-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                font-size: 0.813rem;
                color: #6366f1;
                background: #eef2ff;
                padding: 0.25rem 0.625rem;
                border-radius: 6px;
                font-weight: 500;
            }

            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .modern-table th:first-child {
            border-radius: 8px 0 0 0;
        }

        .modern-table th:last-child {
            border-radius: 0 8px 0 0;
        }

        .modern-table tbody tr {
            background: white;
            transition: background 0.2s ease;
        }

        .modern-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .modern-table tbody tr:hover {
            background: #f3f4f6;
        }

        .modern-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .modern-table tfoot {
            background: #f9fafb;
            font-weight: 700;
        }

        .modern-table tfoot td {
            padding: 1.25rem 0.75rem;
            border-top: 2px solid #4f46e5;
        }

        .service-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .service-type-badge {
            font-size: 0.75rem;
            color: #6b7280;
            background: #e5e7eb;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            display: inline-block;
        }

        .date-badge {
            font-size: 0.875rem;
            color: #4f46e5;
            background: #ede9fe;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .qty-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .margin-display {
            text-align: right;
        }

        .margin-bar-container {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin: 0.5rem 0;
            overflow: hidden;
        }

        .margin-bar {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 2px;
            transition: width 0.5s ease;
        }

        .margin-percent {
            font-size: 0.75rem;
            color: #10b981;
            font-weight: 600;
        }

        /* Financial Visualization */
        .financial-viz {
            padding: 1rem;
        }

        .financial-breakdown {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .breakdown-item {
            padding: 1.25rem;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .breakdown-item.revenue {
            background: #ede9fe;
            border-left-color: #4f46e5;
        }

        .breakdown-item.cost {
            background: #fee2e2;
            border-left-color: #ef4444;
        }

        .breakdown-item.profit {
            background: #d1fae5;
            border-left-color: #10b981;
        }

        .breakdown-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .breakdown-label i {
            width: 16px;
            height: 16px;
        }

        .breakdown-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .profit-gauge {
            text-align: center;
            padding: 1rem;
        }

        .gauge-label {
            font-size: 1rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .gauge-circle {
            max-width: 200px;
            margin: 0 auto;
        }

        .gauge-circle svg {
            width: 100%;
            height: auto;
        }

        /* Payment Progress */
        .payment-progress-section {
            padding: 1rem;
        }

        .progress-header {
            margin-bottom: 1rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .progress-percentage {
            font-size: 1.25rem;
            color: #4f46e5;
        }

        .payment-progress-bar {
            height: 24px;
            background: #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            transition: width 1s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .payment-stat {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .payment-stat .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .payment-stat.paid .stat-icon {
            background: #d1fae5;
            color: #10b981;
        }

        .payment-stat.pending .stat-icon {
            background: #fef3c7;
            color: #f59e0b;
        }

        .payment-stat.status .stat-icon {
            background: #dbeafe;
            color: #3b82f6;
        }

        .payment-stat .stat-icon i {
            width: 24px;
            height: 24px;
        }

        .payment-stat .stat-content {
            flex: 1;
        }

        .payment-stat .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .payment-stat .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .stat-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .stat-badge.success {
            background: #d1fae5;
            color: #065f46;
        }

        .stat-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .stat-badge.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .stat-badge.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Badges */
        .badge-soft-primary {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .badge-soft-success {
            background: #d1fae5;
            color: #065f46;
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .badge-soft-info {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .badge-soft-danger {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Footer */
        .report-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid #e5e7eb;
            text-align: center;
        }

        .report-footer .timestamp {
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .report-footer .disclaimer {
            color: #9ca3af;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .report-footer .copyright {
            color: #d1d5db;
            font-size: 0.75rem;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .summary-card,
            .summary-card-compact {
                position: relative;
                z-index: 5;
                flex-direction: column;
                text-align: center;
            }

            .period-display {
                flex-direction: column;
                gap: 1rem;
            }

            .date-arrow {
                transform: rotate(90deg);
            }

            .financial-viz .row {
                flex-direction: column-reverse;
            }

            .modern-table {
                font-size: 0.8rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.75rem 0.5rem;
            }
        }

        /* Compact Styles for Space Efficiency */
        .section-toggle.compact {
            padding: 0.75rem 1rem;
        }

        .financial-viz-compact {
            padding: 0.75rem;
        }

        .financial-breakdown-compact {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .breakdown-item-compact {
            padding: 0.75rem;
            border-radius: 6px;
            border-left: 3px solid;
        }

        .breakdown-item-compact.revenue {
            background: #ede9fe;
            border-left-color: #4f46e5;
        }

        .breakdown-item-compact.cost {
            background: #fee2e2;
            border-left-color: #ef4444;
        }

        .breakdown-item-compact.profit {
            background: #d1fae5;
            border-left-color: #10b981;
        }

        .breakdown-label-compact {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .breakdown-label-compact i {
            width: 14px;
            height: 14px;
        }

        .breakdown-value-compact {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
        }

        .profit-gauge-compact {
            text-align: center;
            padding: 0.5rem;
        }

        .gauge-label-compact {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .gauge-circle-compact {
            max-width: 160px;
            margin: 0 auto;
        }

        .gauge-circle-compact svg {
            width: 100%;
            height: auto;
        }

        .payment-progress-compact {
            padding: 0.75rem;
        }

        .progress-header-compact {
            margin-bottom: 0.5rem;
        }

        .progress-label-compact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.375rem;
            font-size: 0.875rem;
        }

        .progress-percentage-compact {
            font-size: 1rem;
            color: #4f46e5;
        }

        .payment-progress-bar-compact {
            height: 18px;
            background: #e5e7eb;
            border-radius: 9px;
            overflow: hidden;
            position: relative;
        }

        .payment-stat-compact {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .payment-stat-compact .stat-icon-compact {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .payment-stat-compact.paid .stat-icon-compact {
            background: #d1fae5;
            color: #10b981;
        }

        .payment-stat-compact.pending .stat-icon-compact {
            background: #fef3c7;
            color: #f59e0b;
        }

        .payment-stat-compact.status .stat-icon-compact {
            background: #dbeafe;
            color: #3b82f6;
        }

        .payment-stat-compact .stat-icon-compact i {
            width: 18px;
            height: 18px;
        }

        .payment-stat-compact .stat-content-compact {
            flex: 1;
        }

        .payment-stat-compact .stat-label-compact {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .payment-stat-compact .stat-value-compact {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
        }

        .info-list-compact {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0.75rem;
        }

        .info-item-compact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item-compact:last-child {
            border-bottom: none;
        }

        .info-item-compact .label-compact {}

        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        }

        .info-item-compact .value-compact {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1f2937;
            text-align: right;
        }

        /* Breakdown Sections */
        .breakdown-section {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 0.75rem;
        }

        .breakdown-section-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.813rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .breakdown-list {
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .breakdown-list-item {
            display: grid;
            grid-template-columns: 1fr auto;
            grid-template-rows: auto auto;
            gap: 0.375rem;
            align-items: center;
        }

        .breakdown-list-label {
            font-size: 0.75rem;
            color: #374151;
            font-weight: 500;
        }

        .breakdown-list-value {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1f2937;
            text-align: right;
        }

        .breakdown-list-bar {
            grid-column: 1 / -1;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;

            .breakdown-list-bar-fill {
                height: 100%;
                background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
                border-radius: 3px;
                transition: width 0.5s ease;
            }

            .breakdown-list-bar-fill.vendor {
                background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
            }

            /* Simple Breakdown List */
            .breakdown-simple-list {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .breakdown-simple-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #e5e7eb;
            }

            .breakdown-simple-item:last-child {
                border-bottom: none;
            }

            .breakdown-simple-label {
                font-size: 0.813rem;
                color: #374151;
                font-weight: 500;
            }

            .breakdown-simple-value {
                font-size: 0.875rem;
                font-weight: 700;

                /* Breakdown Tables */
                .breakdown-table-section {
                    margin-top: 1rem;
                }

                .breakdown-table-title {
                    font-size: 0.875rem;
                    font-weight: 600;
                    color: #374151;
                    margin-bottom: 0.5rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .breakdown-table {
                    margin-bottom: 0;
                    font-size: 0.875rem;
                }

                .breakdown-table thead th {
                    background: #f3f4f6;
                    color: #374151;
                    font-weight: 600;
                    font-size: 0.813rem;
                    padding: 0.5rem 0.75rem;
                    border-color: #d1d5db;
                }

                .breakdown-table tbody td {
                    padding: 0.5rem 0.75rem;
                    border-color: #e5e7eb;
                }

                .breakdown-table tfoot td {
                    background: #f9fafb;
                    font-weight: 600;
                    padding: 0.5rem 0.75rem;
                    border-color: #d1d5db;
                }

                color: #1f2937;
            }
    </style>

    <script>
        // Toggle collapsible sections
        function toggleSection(sectionId) {
            const content = document.getElementById(sectionId);
            const button = content.previousElementSibling;

            content.classList.toggle('show');
            button.classList.toggle('collapsed');

            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Share report function
        function shareReport() {
            if (navigator.share) {
                navigator.share({
                    title: 'Booking Report - {{ $booking->booking_number }}',
                    text: 'View booking report for {{ $booking->booking_number }}',
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // Fallback: Copy link to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-fill, .margin-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
@endsection

@push('scripts')
    <script>
        // Additional scripts if needed
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endpush
