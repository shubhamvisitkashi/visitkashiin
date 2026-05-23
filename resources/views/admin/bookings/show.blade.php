@extends('admin.layouts.app')

@section('content')
    <style>
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .booking-number {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .info-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .payment-summary-card {
            border-radius: 16px;
            border: 2px solid;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .payment-summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .payment-summary-card.total::before {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .payment-summary-card.paid::before {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .payment-summary-card.pending::before {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }

        .payment-amount {
            font-size: 2.25rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #667eea;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .status-badge-large {
            padding: 0.5rem 1.25rem;
            font-size: 0.95rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .service-table {
            border-radius: 12px;
            overflow: hidden;
        }

        .service-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .service-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .service-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .modal-header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-action {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>

    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <!-- Header with Booking Number and Actions -->
                <div class="card shadow-sm mb-3">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h4 class="mb-0">
                                    <i data-feather="file-text" style="width: 24px; height: 24px;"></i>
                                    {{ $booking->booking_number }}
                                </h4>
                                <small class="text-muted">{{ $booking->booking_date->format('d M Y') }}</small>
                            </div>
                            <div class="col-md-8 text-end">
                                @if ($booking->booking_status == 'confirmed')
                                    <span class="badge bg-success me-2">✓ Confirmed</span>
                                @elseif($booking->booking_status == 'in_progress')
                                    <span class="badge bg-info me-2">⟳ In Progress</span>
                                @elseif($booking->booking_status == 'completed')
                                    <span class="badge bg-primary me-2">✓ Completed</span>
                                @else
                                    <span class="badge bg-warning me-2">✕ Cancelled</span>
                                @endif

                                @if ($booking->booking_status === 'confirmed')
                                    <form action="{{ route('bookings.update-status', $booking->id) }}" method="POST"
                                        class="d-inline me-2">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Mark this booking as completed?')">
                                            <i data-feather="check-circle" style="width: 14px; height: 14px;"></i> Mark as
                                            Completed
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                    data-bs-target="#addPaymentModal">
                                    <i data-feather="plus" style="width: 14px; height: 14px;"></i> Add Payment
                                </button>
                                <a href="{{ route('booking.report.view', $booking->id) }}" class="btn btn-info btn-sm me-2">
                                    <i data-feather="file-text" style="width: 14px; height: 14px;"></i> View Report
                                </a>
                                <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-sm me-2">
                                    <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back
                                </a>
                                @can('booking-delete')
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-inline"
                                        id="deleteBookingForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteBooking()">
                                            <i data-feather="trash-2" style="width: 14px; height: 14px;"></i> Delete Booking
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compact Payment Summary -->
                @php
                    $paidPercentage =
                        $booking->total_amount > 0
                            ? (($booking->payments_sum_amount ?? 0) / $booking->total_amount) * 100
                            : 0;
                    $pendingPercentage =
                        $booking->total_amount > 0 ? ($booking->pending_amount / $booking->total_amount) * 100 : 0;
                @endphp
                <div class="row mb-3 g-2">
                    <div class="col-md-4">
                        <div class="card border-primary shadow-sm">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Total Amount</small>
                                        <h5 class="mb-0 text-primary">₹{{ number_format($booking->total_amount, 2) }}</h5>
                                    </div>
                                    <i data-feather="dollar-sign" class="text-primary"
                                        style="width: 28px; height: 28px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success shadow-sm">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Paid Amount</small>
                                        <h5 class="mb-0 text-success">
                                            ₹{{ number_format($booking->payments_sum_amount ?? 0, 2) }}</h5>
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-success" style="width: {{ $paidPercentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <i data-feather="check-circle" class="text-success"
                                        style="width: 28px; height: 28px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning shadow-sm">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Pending Amount</small>
                                        <h5 class="mb-0 text-warning">₹{{ number_format($booking->pending_amount, 2) }}</h5>
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $pendingPercentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <i data-feather="clock" class="text-warning" style="width: 28px; height: 28px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i data-feather="user"></i> Customer Details</h6>
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i data-feather="user" class="text-muted me-2" style="width: 16px; height: 16px;"></i>
                                    <strong>{{ optional($booking->lead)->guest_name ?? 'N/A' }}</strong>
                                </p>
                                <p class="mb-2">
                                    <i data-feather="phone" class="text-muted me-2" style="width: 16px; height: 16px;"></i>
                                    {{ optional($booking->lead)->contact ?? 'N/A' }}
                                </p>
                                @if ($booking->lead->email)
                                    <p class="mb-0">
                                        <i data-feather="mail" class="text-muted me-2"
                                            style="width: 16px; height: 16px;"></i>
                                        {{ $booking->lead->email }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if ($booking->lead->pax)
                                    <p class="mb-2">
                                        <i data-feather="users" class="text-muted me-2"
                                            style="width: 16px; height: 16px;"></i>
                                        <strong>{{ $booking->lead->pax }}</strong> Person(s)
                                    </p>
                                @endif
                                @if ($booking->quotation)
                                    <p class="mb-0">
                                        <i data-feather="file" class="text-muted me-2"
                                            style="width: 16px; height: 16px;"></i>
                                        Quotation: <a href="{{ route('quotations.show', $booking->quotation->id) }}"
                                            class="text-primary">{{ $booking->quotation->quotation_number }}</a>
                                    </p>
                                @else
                                    <p class="mb-0">
                                        <i data-feather="zap" class="text-muted me-2"
                                            style="width: 16px; height: 16px;"></i>
                                        <span class="badge bg-secondary">Quick Booking</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Short Plan -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i data-feather="file-text"></i> Short Plan</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editShortPlanModal">
                            <i data-feather="edit" style="width: 14px; height: 14px;"></i> Edit Short Plan
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($booking->lead && $booking->lead->short_plan)
                            <div class="mb-0" style="font-size: 15px; line-height: 1.6;">
                                {!! $booking->lead->short_plan !!}
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                <i data-feather="info" style="width: 16px; height: 16px;"></i>
                                No short plan added yet. Click "Edit Short Plan" to add one.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Full Plan / Itinerary -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i data-feather="map"></i> Full Plan / Itinerary</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editFullPlanModal">
                            <i data-feather="edit" style="width: 14px; height: 14px;"></i> Edit Full Plan
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($booking->lead->plan_detail || ($booking->quotation && $booking->quotation->itinerary))
                            <div class="tour-plan-content" style="line-height: 1.8;">
                                {!! $booking->lead->plan_detail ?? $booking->quotation->itinerary !!}
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                <i data-feather="info" style="width: 16px; height: 16px;"></i>
                                No tour plan added yet. Click "Edit Full Plan" to add one.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Services Table -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i data-feather="list"></i> Services</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addServiceModal">
                            <i data-feather="plus" style="width: 14px; height: 14px;"></i> Add Service
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @if ($booking->quotation && $booking->quotation->items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="18%">Service</th>
                                            <th width="10%" class="text-center">Service Date</th>
                                            <th width="7%" class="text-center">Persons</th>
                                            <th width="9%" class="text-end">Unit Price</th>
                                            <th width="9%" class="text-end">Total</th>
                                            <th width="9%" class="text-end">Vendor Cost</th>
                                            <th width="9%" class="text-end">Margin</th>
                                            <th width="14%" class="text-center">Vendor</th>
                                            <th width="10%" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($booking->quotation->items as $index => $item)
                                            @php
                                                $isAssigned = $booking->serviceAssignments
                                                    ->where('quotation_item_id', $item->id)
                                                    ->first();
                                                $vendorCost = $isAssigned ? $isAssigned->assigned_cost : 0;
                                                $totalPrice = $item->total_price;
                                                $margin = $totalPrice - $vendorCost;
                                                $marginPercent = $totalPrice > 0 ? ($margin / $totalPrice) * 100 : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $item->serviceTemplate->name }}</strong><br>
                                                    <small class="text-muted">
                                                        <i data-feather="tag" style="width: 12px; height: 12px;"></i>
                                                        {{ $item->serviceType->name }}
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->service_date)
                                                        <span class="badge bg-info" style="font-size: 11px;">
                                                            <i data-feather="calendar"
                                                                style="width: 12px; height: 12px;"></i>
                                                            {{ $item->service_date->format('d M Y') }}
                                                        </span>
                                                        <br><small
                                                            class="text-muted">{{ $item->service_date->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="text-end"><strong
                                                        class="text-primary">₹{{ number_format($totalPrice, 2) }}</strong>
                                                </td>
                                                <td class="text-end">
                                                    @if ($isAssigned)
                                                        <strong
                                                            class="text-danger">₹{{ number_format($vendorCost, 2) }}</strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if ($isAssigned)
                                                        <strong
                                                            class="text-success">₹{{ number_format($margin, 2) }}</strong>
                                                        <br><small
                                                            class="badge bg-success">{{ number_format($marginPercent, 1) }}%</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($isAssigned)
                                                        <span class="badge bg-success">
                                                            <i data-feather="check-circle"
                                                                style="width: 12px; height: 12px;"></i>
                                                            {{ $isAssigned->serviceProvider->name }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">Not Assigned</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        @if (!$isAssigned)
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal" data-bs-target="#assignVendorModal"
                                                                onclick="setAssignmentData({{ $item->id }}, '{{ $item->serviceTemplate->name }}', {{ $item->service_template_id }})">
                                                                <i data-feather="user-plus"
                                                                    style="width: 14px; height: 14px;"></i>
                                                                Assign
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                data-bs-toggle="modal" data-bs-target="#assignVendorModal"
                                                                onclick="setAssignmentData({{ $item->id }}, '{{ $item->serviceTemplate->name }}', {{ $item->service_template_id }}, {{ $isAssigned->id }}, {{ $isAssigned->service_provider_id }}, {{ $isAssigned->service_item_id }}, {{ $isAssigned->assigned_cost }})">
                                                                <i data-feather="edit-2"
                                                                    style="width: 14px; height: 14px;"></i>
                                                                Change
                                                            </button>
                                                        @endif
                                                        @can('booking-edit')
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="confirmDeleteService({{ $item->id }}, '{{ $item->serviceTemplate->name }}')"
                                                                title="Delete Service">
                                                                <i data-feather="trash-2"
                                                                    style="width: 14px; height: 14px;"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total Amount:</th>
                                            <th class="text-end text-primary">
                                                ₹{{ number_format($booking->total_amount, 2) }}
                                            </th>
                                            @php
                                                $totalVendorCost = $booking->serviceAssignments->sum('assigned_cost');
                                                $totalMargin = $booking->total_amount - $totalVendorCost;
                                                $totalMarginPercent =
                                                    $booking->total_amount > 0
                                                        ? ($totalMargin / $booking->total_amount) * 100
                                                        : 0;
                                            @endphp
                                            <th class="text-end text-danger">₹{{ number_format($totalVendorCost, 2) }}
                                            </th>
                                            <th class="text-end text-success">
                                                ₹{{ number_format($totalMargin, 2) }}
                                                <br><small
                                                    class="badge bg-success">{{ number_format($totalMarginPercent, 1) }}%</small>
                                            </th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i data-feather="inbox" style="width: 48px; height: 48px;" class="mb-3"></i>
                                <p class="mb-0">This is a direct booking without a quotation.</p>
                                <small>Service details are not available for direct bookings.</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="row">
                    <!-- Payment History -->
                    <div class="col-md-7">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i data-feather="credit-card"></i> Payment History</h5>
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#addPaymentModal">
                                    <i data-feather="plus-circle"></i> Add Payment
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="15%">Date</th>
                                                <th width="20%" class="text-end">Amount</th>
                                                <th width="15%">Method</th>
                                                <th width="20%">Account</th>
                                                <th width="15%">Reference</th>
                                                <th width="10%" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($booking->payments as $index => $payment)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                                    <td class="text-end"><strong
                                                            class="text-success">₹{{ number_format($payment->amount, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($payment->paymentAccount)
                                                            <small>
                                                                <i data-feather="credit-card"
                                                                    style="width: 12px; height: 12px;"></i>
                                                                {{ $payment->paymentAccount->account_name }}
                                                            </small>
                                                        @else
                                                            <small class="text-muted">-</small>
                                                        @endif
                                                    </td>
                                                    <td><small>{{ $payment->reference_number ?? '-' }}</small></td>
                                                    <td class="text-center">
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                data-bs-toggle="modal" data-bs-target="#editPaymentModal"
                                                                onclick="editPayment({{ $payment->id }}, '{{ $payment->payment_date->format('Y-m-d') }}', {{ $payment->amount }}, '{{ $payment->payment_method }}', {{ $payment->payment_account_id }}, '{{ $payment->reference_number }}', '{{ addslashes($payment->notes ?? '') }}')"
                                                                title="Edit Payment">
                                                                <i data-feather="edit"
                                                                    style="width: 14px; height: 14px;"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('bookings.delete-payment', [$booking->id, $payment->id]) }}"
                                                                method="POST" class="d-inline"
                                                                id="delete_payment_form_{{ $payment->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                    title="Delete Payment"
                                                                    onclick="confirmDeletePayment({{ $payment->id }}, {{ $payment->amount }})">
                                                                    <i data-feather="trash-2"
                                                                        style="width: 14px; height: 14px;"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        <i data-feather="inbox" class="mb-2"></i>
                                                        <p class="mb-0">No payments recorded yet</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Payment Summary -->
                    <div class="col-md-5">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i data-feather="pie-chart"></i> Payment Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Booking Amount:</span>
                                        <strong>₹{{ number_format($booking->total_amount, 2) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Paid:</span>
                                        <strong
                                            class="text-success">₹{{ number_format($booking->paid_amount, 2) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Remaining Balance:</span>
                                        <strong
                                            class="text-warning">₹{{ number_format($booking->pending_amount, 2) }}</strong>
                                    </div>
                                </div>

                                @if ($booking->pending_amount > 0)
                                    <div class="alert alert-warning mb-0">
                                        <strong>Payment Pending!</strong><br>
                                        <small>₹{{ number_format($booking->pending_amount, 2) }} is still due for this
                                            booking.</small>
                                    </div>
                                @else
                                    <div class="alert alert-success mb-0">
                                        <strong>Fully Paid!</strong><br>
                                        <small>All payments have been received for this booking.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GST Invoice Management Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header"
                        style="background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%); color: white;">
                        <h5 class="mb-0">
                            <i data-feather="file-text" style="width: 20px; height: 20px;"></i>
                            GST Invoice Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i data-feather="check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i data-feather="alert-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('bookings.update-gst', $booking->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- GST Toggle -->
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <!-- Hidden input to ensure false value is sent when unchecked -->
                                            <input type="hidden" name="is_gst_invoice" value="0">
                                            <input type="checkbox" name="is_gst_invoice" value="1"
                                                class="form-check-input" id="gstToggle"
                                                {{ $booking->is_gst_invoice ? 'checked' : '' }}
                                                onchange="toggleGstDetails()">
                                            <label class="form-check-label fw-bold" for="gstToggle">
                                                This is a GST Invoice
                                            </label>
                                        </div>
                                        <small class="text-muted">Enable to generate GST-compliant tax invoice</small>
                                    </div>

                                    <!-- GST Details (shown when GST is enabled) -->
                                    <div id="gstDetails"
                                        style="display: {{ $booking->is_gst_invoice ? 'block' : 'none' }}">
                                        <!-- Customer GSTIN -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Customer GSTIN (Optional)</label>
                                            <input type="text" name="customer_gstin" class="form-control"
                                                value="{{ $booking->customer_gstin }}" placeholder="22AAAAA0000A1Z5"
                                                maxlength="15">
                                            <small class="text-muted">For B2B transactions, enter customer's GSTIN</small>
                                        </div>

                                        <!-- GST Rate -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">GST Rate <span
                                                    class="text-danger">*</span></label>
                                            <select name="gst_rate" id="gstRateSelect" class="form-select"
                                                onchange="calculateGst()">
                                                <option value="5"
                                                    {{ ($booking->gst_rate ?? 5) == 5 ? 'selected' : '' }}>
                                                    5% (Standard Services - Transport, Tour Packages)
                                                </option>
                                                <option value="12"
                                                    {{ ($booking->gst_rate ?? 5) == 12 ? 'selected' : '' }}>
                                                    12% (Hotel Services, Accommodation)
                                                </option>
                                            </select>
                                            <small class="text-muted">Select applicable GST rate for this booking</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- GST Breakdown (shown when GST is enabled) -->
                                    <div id="gstBreakdown"
                                        style="display: {{ $booking->is_gst_invoice ? 'block' : 'none' }}">
                                        <div class="alert alert-info">
                                            <h6 class="fw-bold mb-3">
                                                <i data-feather="info"></i> GST Breakdown
                                            </h6>
                                            @php
                                                $taxableAmount = $booking->taxable_amount ?? $booking->total_amount;
                                                $gstRate = $booking->gst_rate ?? 5;
                                                $gstAmount = ($taxableAmount * $gstRate) / 100;
                                                $cgst = $gstAmount / 2;
                                                $sgst = $gstAmount / 2;
                                                $totalWithGst = $taxableAmount + $gstAmount;
                                            @endphp
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Taxable Amount:</span>
                                                <strong
                                                    id="taxableAmountDisplay">₹{{ number_format($taxableAmount, 2) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>CGST (<span
                                                        id="cgstRateDisplay">{{ $gstRate / 2 }}</span>%):</span>
                                                <strong id="cgstDisplay">₹{{ number_format($cgst, 2) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>SGST (<span
                                                        id="sgstRateDisplay">{{ $gstRate / 2 }}</span>%):</span>
                                                <strong id="sgstDisplay">₹{{ number_format($sgst, 2) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Total GST:</span>
                                                <strong class="text-primary"
                                                    id="totalGstDisplay">₹{{ number_format($gstAmount, 2) }}</strong>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Grand Total (Inc. GST):</span>
                                                <strong class="text-success fs-5"
                                                    id="grandTotalDisplay">₹{{ number_format($totalWithGst, 2) }}</strong>
                                            </div>

                                            @if ($booking->is_gst_invoice && $booking->gst_invoice_number)
                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="text-muted">GST Invoice Number:</small><br>
                                                    <strong
                                                        class="text-primary">{{ $booking->gst_invoice_number }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    @if ($booking->is_gst_invoice)
                                        <a href="{{ route('booking.gst-invoice', $booking->id) }}"
                                            class="btn btn-outline-primary" target="_blank">
                                            <i data-feather="download"></i> Download GST Invoice
                                        </a>
                                    @else
                                        <a href="{{ route('booking.invoice', $booking->id) }}"
                                            class="btn btn-outline-success" target="_blank">
                                            <i data-feather="download"></i> Download Regular Invoice
                                        </a>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Update GST Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Timeline Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i data-feather="activity" style="width: 20px; height: 20px;"></i>
                            Activity History
                        </h5>
                    </div>
                    <div class="card-body">
                        <x-activity-timeline :activities="$activities" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleGstDetails() {
            const isChecked = document.getElementById('gstToggle').checked;
            document.getElementById('gstDetails').style.display = isChecked ? 'block' : 'none';
            document.getElementById('gstBreakdown').style.display = isChecked ? 'block' : 'none';

            if (isChecked) {
                calculateGst();
            }

            feather.replace();
        }

        function calculateGst() {
            const baseAmount = {{ $booking->total_amount }};
            const gstRate = parseFloat(document.getElementById('gstRateSelect').value);

            const taxableAmount = baseAmount;
            const gstAmount = (taxableAmount * gstRate) / 100;
            const cgst = gstAmount / 2;
            const sgst = gstAmount / 2;
            const grandTotal = taxableAmount + gstAmount;

            // Update displays
            document.getElementById('taxableAmountDisplay').textContent = '₹' + taxableAmount.toFixed(2);
            document.getElementById('cgstRateDisplay').textContent = (gstRate / 2).toFixed(2);
            document.getElementById('sgstRateDisplay').textContent = (gstRate / 2).toFixed(2);
            document.getElementById('cgstDisplay').textContent = '₹' + cgst.toFixed(2);
            document.getElementById('sgstDisplay').textContent = '₹' + sgst.toFixed(2);
            document.getElementById('totalGstDisplay').textContent = '₹' + gstAmount.toFixed(2);
            document.getElementById('grandTotalDisplay').textContent = '₹' + grandTotal.toFixed(2);
        }
    </script>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addPaymentModalLabel">
                        <i data-feather="plus-circle"></i> Add Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('bookings.add-payment', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control"
                                value="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <!-- Payment Method - FIRST -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Method <span
                                    class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select"
                                onchange="filterAccountsByType()" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                                <option value="cheque">Cheque</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Payment Account Selector - Filtered by Type -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Account <span
                                    class="text-danger">*</span></label>
                            <select name="payment_account_id" id="payment_account_id" class="form-select"
                                onchange="updateAccountInfo()" required>
                                <option value="">Select Payment Method First</option>
                                @foreach ($paymentAccounts as $account)
                                    <option value="{{ $account->id }}" data-type="{{ $account->account_type }}"
                                        data-balance="{{ $account->current_balance }}"
                                        data-formatted-type="{{ $account->formatted_type }}"
                                        data-number="{{ $account->account_number }}" style="display: none;">
                                        {{ $account->account_name }} ({{ $account->formatted_type }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Accounts are filtered based on payment method</small>
                            <div id="account-info" class="mt-2" style="display: none;">
                                <small class="text-muted">
                                    <i data-feather="info" style="width: 12px; height: 12px;"></i>
                                    <span id="account-details"></span>
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00"
                                required>
                            <small class="text-muted">Pending Amount: <strong
                                    class="text-warning">₹{{ number_format($booking->pending_amount, 2) }}</strong></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference Number</label>
                            <input type="text" name="reference_number" class="form-control"
                                placeholder="Transaction ID, Cheque No., etc.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Add any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i data-feather="check"></i> Add Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editPaymentModalLabel">
                        <i data-feather="edit"></i> Edit Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPaymentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" id="edit_payment_date" class="form-control"
                                required>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Method <span
                                    class="text-danger">*</span></label>
                            <select name="payment_method" id="edit_payment_method" class="form-select"
                                onchange="filterEditAccountsByType()" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                                <option value="cheque">Cheque</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Payment Account Selector -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Account <span
                                    class="text-danger">*</span></label>
                            <select name="payment_account_id" id="edit_payment_account_id" class="form-select" required>
                                <option value="">Select Payment Method First</option>
                                @foreach ($paymentAccounts as $account)
                                    <option value="{{ $account->id }}" data-type="{{ $account->account_type }}">
                                        {{ $account->account_name }} ({{ $account->formatted_type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control"
                                placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference Number</label>
                            <input type="text" name="reference_number" id="edit_reference_number"
                                class="form-control" placeholder="Transaction ID, Cheque No., etc.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="2"
                                placeholder="Add any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i data-feather="save"></i> Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="paymentDetailsModalLabel">
                        <i data-feather="info"></i> Payment Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted">Payment Date</small>
                            <p class="mb-0" id="detail-date"></p>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted">Amount</small>
                            <p class="mb-0 text-success fw-bold" id="detail-amount"></p>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted">Payment Method</small>
                            <p class="mb-0" id="detail-method"></p>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted">Reference Number</small>
                            <p class="mb-0" id="detail-reference"></p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Notes</small>
                            <p class="mb-0" id="detail-notes"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Vendor Modal -->
    <div class="modal fade" id="assignVendorModal" tabindex="-1" aria-labelledby="assignVendorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="assignVendorModalLabel">
                        <i data-feather="user-plus"></i> Assign Vendor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('bookings.assign-service', $booking->id) }}" method="POST"
                    id="assignVendorForm">
                    @csrf
                    <input type="hidden" name="quotation_item_id" id="assign_quotation_item_id">
                    <input type="hidden" name="service_template_id" id="assign_service_template_id">
                    <input type="hidden" name="service_item_id" id="assign_service_item_id">
                    <input type="hidden" name="assignment_id" id="assignment_id">

                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Service:</strong> <span id="assign_service_name"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Service Provider <span
                                    class="text-danger">*</span></label>
                            <select name="service_provider_id" id="service_provider_id" class="form-select"
                                onchange="autoSelectServiceItem()" required>
                                <option value="">Select Provider</option>
                            </select>
                            <small class="text-muted">Only providers offering this service are shown</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Assigned Cost <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="assigned_cost" id="assigned_cost"
                                class="form-control" placeholder="0.00" required>
                            <small class="text-muted">Cost to be paid to vendor</small>
                        </div>

                        <!-- Assignment Date - Hidden, auto-set to current date -->
                        <input type="hidden" name="assignment_date" value="{{ now()->format('Y-m-d') }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions or notes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="check"></i> Assign Vendor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function filterAccountsByType() {
            const paymentMethod = document.getElementById('payment_method').value;
            const accountSelect = document.getElementById('payment_account_id');
            const options = accountSelect.querySelectorAll('option');

            // Reset account selection
            accountSelect.value = '';
            document.getElementById('account-info').style.display = 'none';

            if (!paymentMethod) {
                // Hide all accounts if no payment method selected
                options.forEach(option => {
                    if (option.value) {
                        option.style.display = 'none';
                    }
                });
                accountSelect.options[0].text = 'Select Payment Method First';
                return;
            }

            // Show only matching accounts
            let hasMatchingAccounts = false;
            options.forEach(option => {
                if (option.value) {
                    const accountType = option.dataset.type;
                    if (accountType === paymentMethod) {
                        option.style.display = 'block';
                        hasMatchingAccounts = true;
                    } else {
                        option.style.display = 'none';
                    }
                }
            });

            // Update placeholder text
            if (hasMatchingAccounts) {
                accountSelect.options[0].text = 'Select Account';
            } else {
                accountSelect.options[0].text = 'No accounts available for this method';
            }

            feather.replace();
        }

        function updateAccountInfo() {
            const select = document.getElementById('payment_account_id');
            const selectedOption = select.options[select.selectedIndex];
            const accountInfo = document.getElementById('account-info');
            const accountDetails = document.getElementById('account-details');

            if (selectedOption.value) {
                const balance = parseFloat(selectedOption.dataset.balance);
                const type = selectedOption.dataset.type;
                const number = selectedOption.dataset.number;

                let details = `Current Balance: <strong class="text-primary">₹${balance.toFixed(2)}</strong>`;
                if (number) {
                    details += ` | ${number}`;
                }

                accountDetails.innerHTML = details;
                accountInfo.style.display = 'block';
            } else {
                accountInfo.style.display = 'none';
            }

            feather.replace();
        }

        function showPaymentDetails(id, date, amount, method, reference, notes) {
            document.getElementById('detail-date').textContent = date;
            document.getElementById('detail-amount').textContent = '₹' + parseFloat(amount).toFixed(2);
            document.getElementById('detail-method').textContent = method.replace('_', ' ').toUpperCase();
            document.getElementById('detail-reference').textContent = reference || '-';
            document.getElementById('detail-notes').textContent = notes || 'No notes';

            const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
            modal.show();

            // Reinitialize feather icons
            feather.replace();
        }

        // Edit Payment Functions
        function editPayment(paymentId, date, amount, method, accountId, reference, notes) {
            // Set form action URL
            const form = document.getElementById('editPaymentForm');
            form.action = `{{ route('bookings.update-payment', [$booking->id, ':paymentId']) }}`.replace(':paymentId',
                paymentId);

            // Populate form fields
            document.getElementById('edit_payment_date').value = date;
            document.getElementById('edit_amount').value = amount;
            document.getElementById('edit_payment_method').value = method;
            document.getElementById('edit_reference_number').value = reference || '';
            document.getElementById('edit_notes').value = notes || '';

            // Filter accounts and select the current one
            filterEditAccountsByType();
            document.getElementById('edit_payment_account_id').value = accountId;

            // Reinitialize feather icons
            feather.replace();
        }

        function filterEditAccountsByType() {
            const paymentMethod = document.getElementById('edit_payment_method').value;
            const accountSelect = document.getElementById('edit_payment_account_id');
            const options = accountSelect.querySelectorAll('option');

            if (!paymentMethod) {
                // Hide all accounts if no payment method selected
                options.forEach(option => {
                    if (option.value) {
                        option.style.display = 'none';
                    }
                });
                accountSelect.options[0].text = 'Select Payment Method First';
                return;
            }

            // Show only matching accounts
            let hasMatchingAccounts = false;
            options.forEach(option => {
                if (option.value) {
                    const accountType = option.dataset.type;
                    if (accountType === paymentMethod) {
                        option.style.display = 'block';
                        hasMatchingAccounts = true;
                    } else {
                        option.style.display = 'none';
                    }
                }
            });

            // Update placeholder text
            if (hasMatchingAccounts) {
                accountSelect.options[0].text = 'Select Account';
            } else {
                accountSelect.options[0].text = 'No accounts available for this method';
            }

            feather.replace();
        }


        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            feather.replace();
        });

        // Vendor Assignment Functions
        let providerServiceItems = {}; // Store service items for each provider

        function setAssignmentData(itemId, serviceName, templateId, assignmentId = null, providerId = null, serviceItemId =
            null, assignedCost = null) {
            document.getElementById('assign_quotation_item_id').value = itemId;
            document.getElementById('assign_service_name').textContent = serviceName;
            document.getElementById('assign_service_template_id').value = templateId;

            // Store assignment ID if this is a reassignment
            if (assignmentId) {
                document.getElementById('assignment_id').value = assignmentId;
                document.getElementById('assignVendorModalLabel').textContent = 'Change Vendor Assignment';
            } else {
                document.getElementById('assignment_id').value = '';
                document.getElementById('assignVendorModalLabel').textContent = 'Assign Vendor to Service';
            }

            // Load providers for this template
            loadProvidersByTemplate(templateId, providerId, serviceItemId, assignedCost);
        }

        function loadProvidersByTemplate(templateId, existingProviderId = null, existingServiceItemId = null, existingCost =
            null) {
            const providerSelect = document.getElementById('service_provider_id');
            providerSelect.innerHTML = '<option value="">Loading...</option>';

            // Fetch providers that have service items for this template
            fetch(`/admin/service-providers/by-template/${templateId}`)
                .then(response => response.json())
                .then(providers => {
                    console.log('Providers response:', providers); // Debug

                    providerSelect.innerHTML = '<option value="">Select Provider</option>';

                    if (providers.length === 0) {
                        providerSelect.innerHTML = '<option value="">No providers available for this service</option>';
                        return;
                    }

                    // Store service items for each provider
                    providerServiceItems = {};
                    providers.forEach(provider => {
                        console.log('Provider:', provider.name, 'Service Items:', provider
                            .service_items); // Debug

                        const option = document.createElement('option');
                        option.value = provider.id;
                        option.textContent = provider.name;
                        providerSelect.appendChild(option);

                        // Store the first service item for this provider
                        // service_items is an array, get the first one
                        if (provider.service_items && provider.service_items.length > 0) {
                            const firstItem = provider.service_items[0];
                            providerServiceItems[provider.id] = {
                                id: firstItem.id,
                                rate: firstItem.rate
                            };
                            console.log('Stored service item for provider', provider.id, ':',
                                providerServiceItems[provider.id]); // Debug
                        }
                    });

                    console.log('Final providerServiceItems:', providerServiceItems); // Debug

                    // If this is a reassignment, pre-populate the existing data
                    if (existingProviderId) {
                        providerSelect.value = existingProviderId;
                        // Trigger change to load service items
                        autoSelectServiceItem(); // This will set assign_service_item_id and initial assigned_cost
                        // Set the cost, overriding the auto-selected one if existingCost is provided
                        if (existingCost !== null) {
                            // Use setTimeout to ensure autoSelectServiceItem has completed its update
                            setTimeout(() => {
                                    document.getElementById('assigned_cost').value = parseFloat(existingCost)
                                        .toFixed(2);
                                },
                                50
                            ); // A small delay might be needed if autoSelectServiceItem has async parts or DOM updates
                        }
                        // If existingServiceItemId is provided, ensure it's set (though autoSelectServiceItem usually picks the first)
                        if (existingServiceItemId) {
                            document.getElementById('assign_service_item_id').value = existingServiceItemId;
                        }
                    }

                    feather.replace();
                })
                .catch(error => {
                    console.error('Error loading providers:', error);
                    providerSelect.innerHTML = '<option value="">Error loading providers</option>';
                });
        }

        function autoSelectServiceItem() {
            const providerId = document.getElementById('service_provider_id').value;

            console.log('Selected provider ID:', providerId); // Debug
            console.log('Available service items:', providerServiceItems); // Debug

            if (!providerId || !providerServiceItems[providerId]) {
                console.log('No service item found for provider'); // Debug
                document.getElementById('assign_service_item_id').value = '';
                document.getElementById('assigned_cost').value = '';
                return;
            }

            const serviceItem = providerServiceItems[providerId];
            console.log('Service item:', serviceItem); // Debug
            console.log('Service item rate:', serviceItem.rate); // Debug

            // Auto-fill the hidden service item ID
            document.getElementById('assign_service_item_id').value = serviceItem.id;

            // Auto-fill the cost - make sure rate exists and is a number
            if (serviceItem.rate !== undefined && serviceItem.rate !== null) {
                const cost = parseFloat(serviceItem.rate);
                console.log('Parsed cost:', cost); // Debug
                if (!isNaN(cost)) {
                    document.getElementById('assigned_cost').value = cost.toFixed(2);
                    console.log('Cost set successfully to:', cost.toFixed(2)); // Debug
                } else {
                    console.error('Rate is not a valid number:', serviceItem.rate);
                    document.getElementById('assigned_cost').value = '';
                }
            } else {
                console.error('Rate is undefined or null');
                document.getElementById('assigned_cost').value = '';
            }
        }
    </script>

    <script>
        // Initialize CKEditor when Full Plan modal is shown
        document.getElementById('editFullPlanModal').addEventListener('shown.bs.modal', function() {
            if (!CKEDITOR.instances.full_plan_editor) {
                CKEDITOR.replace('full_plan_editor', {
                    height: 300,
                    toolbar: [{
                            name: 'document',
                            items: ['Source']
                        },
                        {
                            name: 'clipboard',
                            items: ['Undo', 'Redo']
                        },
                        {
                            name: 'editing',
                            items: ['Find', 'Replace']
                        },
                        {
                            name: 'basicstyles',
                            items: ['Bold', 'Italic', 'Underline', 'Strike']
                        },
                        {
                            name: 'paragraph',
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-',
                                'Blockquote'
                            ]
                        },
                        {
                            name: 'links',
                            items: ['Link', 'Unlink']
                        },
                        {
                            name: 'insert',
                            items: ['Table', 'HorizontalRule']
                        },
                        {
                            name: 'styles',
                            items: ['Format', 'Font', 'FontSize']
                        },
                        {
                            name: 'colors',
                            items: ['TextColor', 'BGColor']
                        },
                        {
                            name: 'tools',
                            items: ['Maximize']
                        }
                    ]
                });
            }
        });

        // Destroy CKEditor when modal is hidden to prevent conflicts
        document.getElementById('editFullPlanModal').addEventListener('hidden.bs.modal', function() {
            if (CKEDITOR.instances.full_plan_editor) {
                CKEDITOR.instances.full_plan_editor.destroy();
            }
        });
    </script>

    <!-- Edit Short Plan Modal -->
    <div class="modal fade" id="editShortPlanModal" tabindex="-1" aria-labelledby="editShortPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editShortPlanModalLabel">
                        <i data-feather="file-text"></i> Edit Short Plan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('bookings.update-short-plan', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Short Plan / Package Name</label>
                            <textarea name="short_plan" id="short_plan_editor" class="form-control" rows="4"
                                placeholder="e.g., Varanasi Spiritual Tour - 3 Days / 2 Nights">{{ $booking->lead->short_plan ?? '' }}</textarea>
                            <small class="text-muted">Brief description of the tour package</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i> Save Short Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize Summernote for Short Plan modal
        $(document).ready(function() {
            $('#editShortPlanModal').on('shown.bs.modal', function() {
                if (!$('#short_plan_editor').data('summernote')) {
                    $('#short_plan_editor').summernote({
                        height: 200,
                        placeholder: 'Enter short plan description...',
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link']],
                            ['view', ['codeview']]
                        ]
                    });
                }
            });

            // Destroy Summernote when modal is hidden
            $('#editShortPlanModal').on('hidden.bs.modal', function() {
                if ($('#short_plan_editor').data('summernote')) {
                    $('#short_plan_editor').summernote('destroy');
                }
            });
        });
    </script>

    <!-- Edit Full Plan Modal with HTML Editor -->
    <div class="modal fade" id="editFullPlanModal" tabindex="-1" aria-labelledby="editFullPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editFullPlanModalLabel">
                        <i data-feather="map"></i> Edit Full Plan / Itinerary
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('bookings.update-tour-plan', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tour Plan / Itinerary</label>
                            <textarea name="tour_plan" id="tour_plan_editor" class="form-control" rows="10">{{ $booking->lead->plan_detail ?? '' }}</textarea>
                            <small class="text-muted">Use the editor toolbar to format your itinerary with headings, lists,
                                and formatting</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i> Save Tour Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Font Awesome for Summernote icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote when Full Plan modal is shown
            $('#editFullPlanModal').on('shown.bs.modal', function() {
                if (!$('#tour_plan_editor').data('summernote')) {
                    $('#tour_plan_editor').summernote({
                        height: 300,
                        placeholder: 'Enter tour itinerary here...',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ],
                        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
                        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                            'Helvetica',
                            'Impact', 'Tahoma', 'Times New Roman', 'Verdana'
                        ]
                    });
                }
            });

            // Destroy Summernote when modal is hidden to prevent conflicts
            $('#editFullPlanModal').on('hidden.bs.modal', function() {
                if ($('#tour_plan_editor').data('summernote')) {
                    $('#tour_plan_editor').summernote('destroy');
                }
            });
        });
    </script>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addServiceModalLabel">
                        <i data-feather="plus-circle"></i> Add Service to Booking
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('bookings.add-service', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i data-feather="info"></i>
                            <strong>Add Additional Service</strong><br>
                            <small>You can add services like Hotel, Cab, Guide, etc. to this existing booking. The total
                                amount will be updated automatically.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Service Type <span
                                            class="text-danger">*</span></label>
                                    <select name="service_type_id" id="service_type_id" class="form-select"
                                        onchange="filterServiceTemplates()" required>
                                        <option value="">Select Service Type</option>
                                        @foreach ($serviceTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Service Template <span
                                            class="text-danger">*</span></label>
                                    <select name="service_template_id" id="service_template_id" class="form-select"
                                        onchange="updateServicePrice()" required>
                                        <option value="">Select Service Type First</option>
                                        @foreach ($serviceTemplates as $template)
                                            <option value="{{ $template->id }}"
                                                data-type="{{ $template->service_type_id }}"
                                                data-price="{{ $template->default_selling_price }}"
                                                style="display: none;">
                                                {{ $template->name }}
                                                @if ($template->default_selling_price)
                                                    - ₹{{ number_format($template->default_selling_price, 2) }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Quantity/Persons <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control"
                                        min="1" value="1" required>
                                    <small class="text-muted">For reference only</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Unit Price <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="unit_price" id="unit_price" class="form-control"
                                        min="0" step="0.01" required>
                                    <small class="text-muted">Price per unit/person</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Total Price <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="total_price" id="total_price" class="form-control"
                                        min="0" step="0.01" required>
                                    <small class="text-muted">Actual total amount</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Service Date</label>
                            <input type="date" name="service_date" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"
                                placeholder="Any additional notes about this service..."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <i data-feather="alert-triangle"></i>
                            <strong>Note:</strong> The booking's total amount will be updated after adding this service.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="plus"></i> Add Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Filter service templates based on selected service type
        function filterServiceTemplates() {
            const typeId = document.getElementById('service_type_id').value;
            const templateSelect = document.getElementById('service_template_id');
            const options = templateSelect.querySelectorAll('option');

            // Reset template selection
            templateSelect.value = '';
            document.getElementById('unit_price').value = '';
            document.getElementById('total_price').value = '';

            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    option.textContent = typeId ? 'Select Service Template' : 'Select Service Type First';
                } else if (option.dataset.type === typeId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // Update price when template is selected
        function updateServicePrice() {
            const templateSelect = document.getElementById('service_template_id');
            const selectedOption = templateSelect.options[templateSelect.selectedIndex];
            const defaultPrice = selectedOption.dataset.price || 0;

            // Set unit price from template
            document.getElementById('unit_price').value = defaultPrice;
            // Set total price same as unit price initially (for quantity = 1)
            document.getElementById('total_price').value = defaultPrice;
        }

        // Confirm delete booking
        function confirmDeleteBooking() {
            Swal.fire({
                title: 'Delete Booking?',
                html: '<p>Are you sure you want to delete this booking?</p><p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteBookingForm').submit();
                }
            });
        }

        // Confirm delete service
        function confirmDeleteService(itemId, serviceName) {
            Swal.fire({
                title: 'Delete Service?',
                html: `<p>Are you sure you want to delete <strong>${serviceName}</strong> from this booking?</p><p class="text-warning"><small>This will update the booking total amount.</small></p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form to delete service
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/bookings/{{ $booking->id }}/delete-service/${itemId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Confirm delete payment
        function confirmDeletePayment(paymentId, amount) {
            Swal.fire({
                title: 'Delete Payment?',
                html: `<p>Are you sure you want to delete this payment of <strong>₹${parseFloat(amount).toFixed(2)}</strong>?</p><p class="text-danger"><strong>Warning:</strong> This action cannot be undone and will update the booking balance!</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_payment_form_' + paymentId).submit();
                }
            });
        }

        // AJAX form submission for vendor assignment
        document.getElementById('assignVendorForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i> Assigning...';

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('assignVendorModal'));
                        modal.hide();

                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'An error occurred while assigning the vendor.'
                        });

                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while assigning the vendor. Please try again.'
                    });

                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });
    </script>
@endsection
