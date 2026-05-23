@extends('admin.layouts.app')

@section('content')
    <style>
        .legacy-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .legacy-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 24px;
            position: relative;
        }

        .legacy-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="%23ffffff"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.1;
        }

        .legacy-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .legacy-title {
            font-size: 28px;
            font-weight: 700;
            margin: 16px 0 8px 0;
        }

        .legacy-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .legacy-body {
            padding: 32px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #f59e0b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            width: 24px;
            height: 24px;
            color: #f59e0b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .info-card {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 16px;
            color: #111827;
            font-weight: 600;
        }

        .payment-summary {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 24px;
            border-radius: 12px;
            border: 2px solid #fbbf24;
            margin-bottom: 32px;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 16px;
        }

        .payment-card {
            background: white;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #fbbf24;
        }

        .payment-card-label {
            font-size: 12px;
            color: #92400e;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .payment-card-value {
            font-size: 24px;
            font-weight: 700;
        }

        .payment-card.total .payment-card-value {
            color: #667eea;
        }

        .payment-card.paid .payment-card-value {
            color: #10b981;
        }

        .payment-card.due .payment-card-value {
            color: #ef4444;
        }

        .payment-history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .payment-history-table th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }

        .payment-history-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            color: #111827;
        }

        .payment-history-table tr:hover {
            background: #f9fafb;
        }

        .tour-package-box {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #c4b5fd;
            margin-bottom: 24px;
        }

        .tour-package-content {
            background: white;
            padding: 16px;
            border-radius: 8px;
            margin-top: 12px;
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
        }

        .services-list {
            display: grid;
            gap: 12px;
        }

        .service-item {
            background: white;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .service-info {
            flex: 1;
        }

        .service-name {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .service-details {
            font-size: 13px;
            color: #6b7280;
        }

        .service-price {
            text-align: right;
        }

        .service-price-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .service-price-value {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: #f59e0b;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #f59e0b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        .empty-state i {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }
    </style>

    <div class="page-content">
        <div class="mb-3">
            <a href="{{ route('lead.index') }}" class="back-btn">
                <i data-feather="arrow-left"></i>
                Back to Leads
            </a>
        </div>

        <div class="legacy-container">
            <div class="legacy-header">
                <div class="legacy-badge">
                    <i data-feather="database"></i>
                    Old System Data
                </div>
                <h1 class="legacy-title">{{ $lead->guest_name }}</h1>
                <p class="legacy-subtitle">
                    <i data-feather="calendar"></i>
                    Booking ID: {{ $lead->booking_id ?? 'N/A' }} |
                    Enquiry Date: {{ $lead->enquiry_date ? dateFormat($lead->enquiry_date) : 'N/A' }}
                </p>
            </div>

            <div class="legacy-body">
                {{-- Guest Information --}}
                <h3 class="section-title">
                    <i data-feather="user"></i>
                    Guest Information
                </h3>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Guest Name</div>
                        <div class="info-value">{{ $lead->guest_name }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value">{{ $lead->contact ?? 'N/A' }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $lead->email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Number of Pax</div>
                        <div class="info-value">
                            <i data-feather="users" style="width: 16px; height: 16px;"></i>
                            {{ $lead->pax ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Booking Status</div>
                        <div class="info-value">
                            <span
                                class="badge bg-{{ $lead->booking_status == 'complete' ? 'success' : ($lead->booking_status == 'confirm' ? 'primary' : ($lead->booking_status == 'cancel' ? 'danger' : 'warning')) }}">
                                {{ ucfirst($lead->booking_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Lead Source</div>
                        <div class="info-value">{{ $lead->leadSource->name ?? 'N/A' }}</div>
                    </div>
                    @if ($lead->booking_start_date)
                        <div class="info-card">
                            <div class="info-label">Travel Dates</div>
                            <div class="info-value">
                                {{ dateFormat($lead->booking_start_date) }}
                                @if ($lead->booking_end_date && $lead->booking_start_date != $lead->booking_end_date)
                                    - {{ dateFormat($lead->booking_end_date) }}
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="info-card">
                        <div class="info-label">Added By</div>
                        <div class="info-value">{{ $lead->getAddedBy->name ?? 'N/A' }}</div>
                    </div>
                </div>

                {{-- Payment Summary --}}
                <h3 class="section-title">
                    <i data-feather="credit-card"></i>
                    Payment Summary
                </h3>
                <div class="payment-summary">
                    <div class="payment-grid">
                        <div class="payment-card total">
                            <div class="payment-card-label">Total Amount</div>
                            <div class="payment-card-value">₹{{ number_format($lead->total_amount ?? 0, 0) }}</div>
                        </div>
                        <div class="payment-card paid">
                            <div class="payment-card-label">Paid Amount</div>
                            <div class="payment-card-value">₹{{ number_format($lead->paid_amount ?? 0, 0) }}</div>
                        </div>
                        <div class="payment-card due">
                            <div class="payment-card-label">Due Amount</div>
                            <div class="payment-card-value">₹{{ number_format($lead->pending_amount ?? 0, 0) }}</div>
                        </div>
                    </div>

                    @if ($lead->leadPayments && $lead->leadPayments->count() > 0)
                        <h4
                            style="margin-top: 24px; margin-bottom: 12px; font-size: 16px; font-weight: 700; color: #92400e;">
                            Payment History
                        </h4>
                        <table class="payment-history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Mode</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                    <th>Added By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lead->leadPayments as $payment)
                                    <tr>
                                        <td>{{ dateFormat($payment->payment_date) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($payment->payment_mode) }}</span>
                                        </td>
                                        <td><strong>₹{{ number_format($payment->paid_amount, 0) }}</strong></td>
                                        <td>{{ $payment->remark ?? '-' }}</td>
                                        <td>{{ $payment->addedBy->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Tour Package / Plan --}}
                @if ($lead->short_plan || $lead->plan_detail)
                    <h3 class="section-title">
                        <i data-feather="map"></i>
                        Tour Package Details
                    </h3>

                    @if ($lead->short_plan)
                        <div class="tour-package-box">
                            <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5b21b6;">
                                <i data-feather="file-text" style="width: 16px; height: 16px;"></i>
                                Short Plan / Summary
                            </h4>
                            <div class="tour-package-content">{!! strip_tags($lead->short_plan) !!}</div>
                        </div>
                    @endif

                    @if ($lead->plan_detail)
                        <div class="tour-package-box">
                            <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #5b21b6;">
                                <i data-feather="map-pin" style="width: 16px; height: 16px;"></i>
                                Detailed Itinerary
                            </h4>
                            <div class="tour-package-content">{!! $lead->plan_detail !!}</div>
                        </div>
                    @endif
                @endif

                {{-- Booking Services --}}
                @if ($lead->bookingServices && $lead->bookingServices->count() > 0)
                    <h3 class="section-title">
                        <i data-feather="package"></i>
                        Services Booked
                    </h3>
                    <div class="services-list">
                        @foreach ($lead->bookingServices as $service)
                            <div class="service-item">
                                <div class="service-info">
                                    <div class="service-name">
                                        {{ $service->serviceItem->serviceProvider->name ?? 'Service' }}
                                        - {{ $service->serviceItem->name ?? 'N/A' }}
                                    </div>
                                    <div class="service-details">
                                        <span><i data-feather="calendar" style="width: 12px; height: 12px;"></i>
                                            {{ dateFormat($service->service_date) }}</span>
                                        <span style="margin-left: 16px;"><i data-feather="hash"
                                                style="width: 12px; height: 12px;"></i> Qty:
                                            {{ $service->quantity }}</span>
                                        @if ($service->notes)
                                            <span style="margin-left: 16px;"><i data-feather="info"
                                                    style="width: 12px; height: 12px;"></i> {{ $service->notes }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="service-price">
                                    <div class="service-price-label">Selling Price</div>
                                    <div class="service-price-value">
                                        ₹{{ number_format($service->total_selling_price ?? 0, 0) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Additional Notes --}}
                @if ($lead->remark)
                    <h3 class="section-title">
                        <i data-feather="message-square"></i>
                        Additional Notes
                    </h3>
                    <div class="info-card">
                        <div class="info-value" style="white-space: pre-wrap;">{{ $lead->remark }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
