@extends('admin.layouts.app')

@section('content')
    <style>
        .detail-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        .detail-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.25rem 1.5rem;
        }

        .detail-card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .info-row {
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #2d3748;
            font-size: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-block;
        }

        .status-follow-up {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirm {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-complete {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancel {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid" style="max-width: 1400px;">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h4 class="mb-1">Lead Details</h4>
                            <p class="text-muted mb-0">Complete information about this lead</p>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('quick-booking.form', $data->id) }}" class="btn btn-success btn-action">
                                <i data-feather="zap" style="width: 18px; height: 18px;"></i>
                                Quick Booking
                            </a>
                            <a href="{{ route('quotations.create', ['lead_id' => $data->id]) }}"
                                class="btn btn-primary btn-action">
                                <i data-feather="file-text" style="width: 18px; height: 18px;"></i>
                                Create Quotation
                            </a>
                            <a href="{{ route('lead.edit', $data->id) }}" class="btn btn-warning btn-action">
                                <i data-feather="edit" style="width: 18px; height: 18px;"></i>
                                Edit
                            </a>
                            <a href="{{ route('lead.index', $searchForm ?? []) }}" class="btn btn-light btn-action">
                                <i data-feather="arrow-left" style="width: 18px; height: 18px;"></i>
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Customer Information -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h5><i data-feather="user"></i> Customer Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Guest Name</div>
                                        <div class="info-value">{{ $data->guest_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Contact Number</div>
                                        <div class="info-value">
                                            <i data-feather="phone" style="width: 16px; height: 16px;"></i>
                                            {{ $data->contact ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">
                                            @if ($data->email)
                                                <i data-feather="mail" style="width: 16px; height: 16px;"></i>
                                                {{ $data->email }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Number of Pax</div>
                                        <div class="info-value">
                                            <i data-feather="users" style="width: 16px; height: 16px;"></i>
                                            {{ $data->pax ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                @if ($data->address)
                                    <div class="col-12">
                                        <div class="info-row">
                                            <div class="info-label">Address</div>
                                            <div class="info-value">
                                                <i data-feather="map-pin" style="width: 16px; height: 16px;"></i>
                                                {{ $data->address }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Booking Information -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h5><i data-feather="calendar"></i> Booking Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Enquiry Date</div>
                                        <div class="info-value">
                                            {{ $data->enquiry_date ? \Carbon\Carbon::parse($data->enquiry_date)->format('d M Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Booking Date Range</div>
                                        <div class="info-value">
                                            @if ($data->booking_start_date && $data->booking_end_date)
                                                {{ \Carbon\Carbon::parse($data->booking_start_date)->format('d M Y') }} -
                                                {{ \Carbon\Carbon::parse($data->booking_end_date)->format('d M Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Lead Source</div>
                                        <div class="info-value">{{ $data->leadSource->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Added By</div>
                                        <div class="info-value">{{ $data->getAddedBy->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                @if ($data->short_plan)
                                    <div class="col-12">
                                        <div class="info-row">
                                            <div class="info-label">Requirements / Plan</div>
                                            <div class="info-value">{{ $data->short_plan }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Activity Timeline -->
                    @if (isset($activities) && $activities->count() > 0)
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <h5><i data-feather="activity"></i> Activity History</h5>
                            </div>
                            <div class="card-body p-4">
                                <x-activity-timeline :activities="$activities" />
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Status Card -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h5><i data-feather="info"></i> Status</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="info-row">
                                <div class="info-label">Booking Status</div>
                                <div class="info-value mt-2">
                                    @php
                                        $statusClass = 'status-follow-up';
                                        if ($data->booking_status == 'confirm') {
                                            $statusClass = 'status-confirm';
                                        } elseif ($data->booking_status == 'complete') {
                                            $statusClass = 'status-complete';
                                        } elseif ($data->booking_status == 'cancel') {
                                            $statusClass = 'status-cancel';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst($data->booking_status ?? 'Follow Up') }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Payment Status</div>
                                <div class="info-value mt-2">
                                    @if ($data->payment_status == 'paid')
                                        <span class="status-badge status-complete">Paid</span>
                                    @elseif($data->payment_status == 'due')
                                        <span class="status-badge status-follow-up">Due</span>
                                    @else
                                        <span class="status-badge status-cancel">Not Paid</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h5><i data-feather="dollar-sign"></i> Financial Summary</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="info-row">
                                <div class="info-label">Total Amount</div>
                                <div class="info-value">
                                    <strong
                                        class="text-primary">₹{{ number_format($data->total_amount ?? 0, 2) }}</strong>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Paid Amount</div>
                                <div class="info-value">
                                    <strong
                                        class="text-success">₹{{ number_format($data->lead_payments_sum_paid_amount ?? 0, 2) }}</strong>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Due Amount</div>
                                <div class="info-value">
                                    <strong
                                        class="text-warning">₹{{ number_format(($data->total_amount ?? 0) - ($data->lead_payments_sum_paid_amount ?? 0), 2) }}</strong>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Total Expense</div>
                                <div class="info-value">
                                    <strong
                                        class="text-danger">₹{{ number_format($data->total_expense ?? 0, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h5><i data-feather="zap"></i> Quick Actions</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-grid gap-2">
                                <a href="{{ route('lead.show', $data->id) }}" class="btn btn-outline-primary">
                                    <i data-feather="edit-3" style="width: 16px; height: 16px;"></i>
                                    Manage Payments
                                </a>
                                <a href="{{ route('lead.invoice', $data->id) }}" class="btn btn-outline-success"
                                    target="_blank">
                                    <i data-feather="file-text" style="width: 16px; height: 16px;"></i>
                                    View Invoice
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
