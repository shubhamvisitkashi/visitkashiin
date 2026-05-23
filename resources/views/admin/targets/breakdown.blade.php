@extends('admin.layouts.app')

@section('content')
    <style>
        .breakdown-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: white;
            padding: 1.25rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #667eea;
        }

        .summary-card.success {
            border-left-color: #10b981;
        }

        .summary-card.warning {
            border-left-color: #f59e0b;
        }

        .summary-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .breakdown-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .breakdown-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .breakdown-table thead {
            background: #f9fafb;
        }

        .breakdown-table th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .breakdown-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
        }

        .breakdown-table tbody tr:hover {
            background: #f9fafb;
        }

        .breakdown-table tbody tr:last-child td {
            border-bottom: none;
        }

        .amount-positive {
            color: #10b981;
            font-weight: 600;
        }

        .amount-negative {
            color: #ef4444;
            font-weight: 600;
        }

        .booking-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .booking-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .breakdown-table {
                overflow-x: auto;
            }

            .breakdown-table table {
                min-width: 600px;
            }

            .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-content">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('targets.index') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left"></i> Back to Targets
            </a>
        </div>

        <!-- Header -->
        <div class="breakdown-header">
            <h3 class="mb-2">Target Breakdown</h3>
            <p class="mb-0 opacity-90">
                <strong>{{ $target->user->name }}</strong> -
                {{ date('F Y', mktime(0, 0, 0, $target->month, 1, $target->year)) }}
            </p>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-label">Target Margin</div>
                <div class="summary-value">₹{{ number_format($target->target_margin, 0) }}</div>
            </div>
            <div class="summary-card {{ $target->is_achieved ? 'success' : 'warning' }}">
                <div class="summary-label">Achieved Margin</div>
                <div class="summary-value">₹{{ number_format($totalMargin, 0) }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Achievement</div>
                <div class="summary-value">{{ $target->achievement_percentage }}%</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Bookings</div>
                <div class="summary-value">{{ count($breakdown) }}</div>
            </div>
        </div>

        <!-- Margin Calculation Info -->
        <div class="alert alert-success" style="background: #d1fae5; border: 1px solid #10b981; border-radius: 10px;">
            <div class="d-flex align-items-start gap-2">
                <i data-feather="check-circle"
                    style="width: 20px; height: 20px; color: #10b981; flex-shrink: 0; margin-top: 2px;"></i>
                <div>
                    <strong style="color: #10b981;">Payment-Based Margin Calculation:</strong>
                    <p class="mb-0 mt-1" style="font-size: 0.875rem; color: #047857;">
                        Margin = <strong>Payments Received</strong> - <strong>Proportional Vendor Cost</strong>
                        <br>
                        <small class="text-muted">
                            Example: Booking = ₹1,00,000, Vendor Cost = ₹50,000, Payment Received = ₹20,000 (20%)<br>
                            → Proportional Vendor Cost = ₹50,000 × 20% = ₹10,000<br>
                            → <strong>Margin = ₹20,000 - ₹10,000 = ₹10,000</strong>
                        </small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Breakdown Table -->
        <div class="breakdown-table">
            <table>
                <thead>
                    <tr>
                        <th>Booking #</th>
                        <th>Customer</th>
                        <th>Booking Date</th>
                        <th>Service Start</th>
                        <th>Services</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-end">Payment Received</th>
                        <th class="text-center">Payment %</th>
                        <th class="text-end">Vendor Cost</th>
                        <th class="text-end">Prop. Cost</th>
                        <th class="text-end">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($breakdown as $item)
                        <tr>
                            <td>
                                <a href="{{ route('bookings.show', $item['booking_id']) }}" class="booking-link"
                                    target="_blank">
                                    {{ $item['booking_number'] }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $item['customer_name'] }}</strong>
                            </td>
                            <td>{{ $item['booking_date'] }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $item['service_start_date'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $item['services_count'] }} {{ Str::plural('service', $item['services_count']) }}
                                </span>
                            </td>
                            <td class="text-end">₹{{ number_format($item['total_amount'], 0) }}</td>
                            <td class="text-end">
                                <span
                                    class="fw-semibold text-primary">₹{{ number_format($item['payments_received'], 0) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge"
                                    style="background: {{ $item['payment_percentage'] >= 100 ? '#10b981' : ($item['payment_percentage'] >= 50 ? '#f59e0b' : '#ef4444') }}">
                                    {{ $item['payment_percentage'] }}%
                                </span>
                            </td>
                            <td class="text-end text-muted">₹{{ number_format($item['vendor_cost'], 0) }}</td>
                            <td class="text-end">₹{{ number_format($item['proportional_vendor_cost'], 0) }}</td>
                            <td class="text-end">
                                <span class="{{ $item['margin'] >= 0 ? 'amount-positive' : 'amount-negative' }}">
                                    ₹{{ number_format($item['margin'], 0) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i data-feather="inbox" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                                <p class="mt-3 text-muted">No bookings found for this period</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($breakdown) > 0)
                    <tfoot style="background: #f9fafb; font-weight: 600;">
                        <tr>
                            <td colspan="5" class="text-end"><strong>TOTAL:</strong></td>
                            <td class="text-end">
                                ₹{{ number_format(collect($breakdown)->sum('total_amount'), 0) }}
                            </td>
                            <td class="text-end text-primary">
                                ₹{{ number_format(collect($breakdown)->sum('payments_received'), 0) }}
                            </td>
                            <td class="text-center">
                                @php
                                    $totalAmount = collect($breakdown)->sum('total_amount');
                                    $totalPayments = collect($breakdown)->sum('payments_received');
                                    $overallPaymentPercentage =
                                        $totalAmount > 0 ? round(($totalPayments / $totalAmount) * 100, 1) : 0;
                                @endphp
                                <span class="badge bg-primary">{{ $overallPaymentPercentage }}%</span>
                            </td>
                            <td class="text-end text-muted">
                                ₹{{ number_format(collect($breakdown)->sum('vendor_cost'), 0) }}
                            </td>
                            <td class="text-end">
                                ₹{{ number_format(collect($breakdown)->sum('proportional_vendor_cost'), 0) }}
                            </td>
                            <td class="text-end">
                                <span class="amount-positive">
                                    ₹{{ number_format($totalMargin, 0) }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
