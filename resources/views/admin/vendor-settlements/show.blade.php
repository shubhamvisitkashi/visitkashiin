@extends('admin.layouts.app')

@section('content')
    <style>
        /* Fix for content going behind header */
        .page-content {
            position: relative;
            z-index: 1;
            padding: 24px;
        }

        /* Modern Card Styles */
        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 24px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 24px;
            border-radius: 16px;
            color: white;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .page-header h2 {
            margin: 0 0 4px 0;
            font-size: 24px;
            font-weight: 700;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        /* Summary Cards */
        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #667eea;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .summary-card.success {
            border-left-color: #10b981;
        }

        .summary-card.danger {
            border-left-color: #ef4444;
        }

        .summary-card.info {
            border-left-color: #3b82f6;
        }

        .summary-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .summary-meta {
            font-size: 13px;
            color: #9ca3af;
        }

        /* Table Styles */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead {
            background: #f8f9fc;
        }

        .modern-table thead th {
            padding: 14px 16px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a5568;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }

        .modern-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f9fafb;
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        .modern-table tfoot {
            background: #f8f9fc;
            font-weight: 600;
        }

        .modern-table tfoot td,
        .modern-table tfoot th {
            padding: 14px 16px;
            border-top: 2px solid #e5e7eb;
        }

        /* Mobile Card View */
        .mobile-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #667eea;
        }

        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .mobile-card-label {
            color: #6b7280;
            font-weight: 500;
        }

        .mobile-card-value {
            font-weight: 600;
            text-align: right;
        }

        /* Badges */
        .badge-modern {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state-icon i {
            width: 32px;
            height: 32px;
            color: #667eea;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-content {
                padding: 16px;
            }

            .page-header {
                padding: 16px;
                margin-bottom: 16px;
            }

            .page-header h2 {
                font-size: 20px;
                margin-bottom: 2px;
            }

            .page-header p {
                font-size: 13px;
            }

            .summary-card {
                padding: 16px;
                margin-bottom: 12px;
            }

            .summary-value {
                font-size: 22px;
            }

            .summary-label {
                font-size: 11px;
            }

            .summary-meta {
                font-size: 12px;
            }

            .desktop-table {
                display: none;
            }

            .mobile-view {
                display: block;
            }

            .summary-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 16px;
            }

            .modern-card {
                margin-bottom: 16px;
            }

            .mobile-card {
                padding: 14px;
                margin-bottom: 10px;
            }

            .mobile-card-header {
                margin-bottom: 10px;
                padding-bottom: 10px;
            }

            .mobile-card-row {
                padding: 6px 0;
                font-size: 13px;
            }

            /* Adjust button sizes for mobile */
            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }

            .btn-sm {
                font-size: 12px;
                padding: 6px 12px;
            }
        }

        @media (min-width: 769px) {
            .mobile-view {
                display: none;
            }

            .desktop-table {
                display: block;
            }
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header animate-fade-in">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <a href="{{ route('vendor-settlements.index') }}" class="btn btn-light btn-sm mb-2">
                            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
                        </a>
                        <h2>{{ $vendor->name }}</h2>
                        <p>Vendor Settlement Details</p>
                    </div>
                    @if ($vendor->outstanding_balance > 0)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#addPaymentModal">
                            <i data-feather="dollar-sign" style="width: 16px; height: 16px;"></i> Add Payment
                        </button>
                    @endif
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-grid animate-fade-in" style="animation-delay: 0.1s">
                <div class="summary-card">
                    <div class="summary-label">Total Assigned</div>
                    <div class="summary-value text-primary">₹{{ number_format($vendor->total_assigned, 2) }}</div>
                    <div class="summary-meta">{{ $vendor->assignments_count }} assignments</div>
                </div>

                <div class="summary-card success">
                    <div class="summary-label">Total Paid</div>
                    <div class="summary-value text-success">₹{{ number_format($vendor->total_paid, 2) }}</div>
                    <div class="summary-meta">{{ $vendor->vendorPayments->count() }} payments</div>
                </div>

                <div class="summary-card {{ $vendor->outstanding_balance > 0 ? 'danger' : 'success' }}">
                    <div class="summary-label">Outstanding Balance</div>
                    <div class="summary-value {{ $vendor->outstanding_balance > 0 ? 'text-danger' : 'text-success' }}">
                        ₹{{ number_format($vendor->outstanding_balance, 2) }}
                    </div>
                    <div class="summary-meta">
                        @if ($vendor->outstanding_balance > 0)
                            <span class="text-danger">Payment Due</span>
                        @else
                            <span class="text-success">Fully Settled</span>
                        @endif
                    </div>
                </div>

                <div class="summary-card info">
                    <div class="summary-label">Last Payment</div>
                    <div class="summary-value" style="font-size: 18px;">
                        @if ($vendor->last_payment_date)
                            {{ \Carbon\Carbon::parse($vendor->last_payment_date)->format('d M Y') }}
                        @else
                            <span class="text-muted">No payments</span>
                        @endif
                    </div>
                    <div class="summary-meta">
                        @if ($vendor->last_payment_date)
                            {{ \Carbon\Carbon::parse($vendor->last_payment_date)->diffForHumans() }}
                        @else
                            Not yet recorded
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Service Assignments -->
                <div class="col-lg-7 mb-4">
                    <div class="modern-card animate-fade-in" style="animation-delay: 0.2s">
                        <div class="card-header bg-white border-0" style="padding: 20px;">
                            <h5 class="mb-0">
                                <i data-feather="list" style="width: 20px; height: 20px;"></i> Service Assignments
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <!-- Desktop Table View -->
                            <div class="desktop-table">
                                <div class="table-responsive">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Booking</th>
                                                <th>Service</th>
                                                <th class="text-end">Cost</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($vendor->serviceAssignments as $assignment)
                                                <tr>
                                                    <td>
                                                        <small>{{ \Carbon\Carbon::parse($assignment->assignment_date)->format('d M Y') }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($assignment->booking)
                                                            <a href="{{ route('bookings.show', $assignment->booking_id) }}"
                                                                class="text-decoration-none">
                                                                <strong>#{{ $assignment->booking->booking_number }}</strong>
                                                            </a>
                                                            <br><small
                                                                class="text-muted">{{ $assignment->booking->lead->guest_name ?? 'N/A' }}</small>
                                                        @else
                                                            <span class="text-muted">Booking not found</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $assignment->quotationItem->serviceTemplate->name ?? 'N/A' }}</small>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong>₹{{ number_format($assignment->assigned_cost, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success badge-modern">Assigned</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon">
                                                                <i data-feather="inbox"></i>
                                                            </div>
                                                            <h6>No Assignments Found</h6>
                                                            <p class="text-muted mb-0">No services have been assigned to
                                                                this
                                                                vendor
                                                                yet.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        @if ($vendor->serviceAssignments->count() > 0)
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                    <td class="text-end text-primary">
                                                        <strong>₹{{ number_format($vendor->total_assigned, 2) }}</strong>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="mobile-view" style="padding: 16px;">
                                @forelse($vendor->serviceAssignments as $assignment)
                                    <div class="mobile-card">
                                        <div class="mobile-card-header">
                                            <div>
                                                @if ($assignment->booking)
                                                    <a href="{{ route('bookings.show', $assignment->booking_id) }}"
                                                        class="text-decoration-none">
                                                        <strong>#{{ $assignment->booking->booking_number }}</strong>
                                                    </a>
                                                    <br><small
                                                        class="text-muted">{{ $assignment->booking->lead->guest_name ?? 'N/A' }}</small>
                                                @else
                                                    <span class="text-muted">Booking not found</span>
                                                @endif
                                            </div>
                                            <span class="badge bg-success badge-modern">Assigned</span>
                                        </div>
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Date:</span>
                                            <span
                                                class="mobile-card-value">{{ \Carbon\Carbon::parse($assignment->assignment_date)->format('d M Y') }}</span>
                                        </div>
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Service:</span>
                                            <span
                                                class="mobile-card-value">{{ $assignment->quotationItem->serviceTemplate->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Cost:</span>
                                            <span
                                                class="mobile-card-value text-primary"><strong>₹{{ number_format($assignment->assigned_cost, 2) }}</strong></span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i data-feather="inbox"></i>
                                        </div>
                                        <h6>No Assignments Found</h6>
                                        <p class="text-muted mb-0">No services assigned yet.</p>
                                    </div>
                                @endforelse

                                @if ($vendor->serviceAssignments->count() > 0)
                                    <div class="mobile-card" style="background: #f8f9fc; border-left-color: #667eea;">
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label"><strong>Total:</strong></span>
                                            <span class="mobile-card-value text-primary">
                                                <strong>₹{{ number_format($vendor->total_assigned, 2) }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="col-lg-5 mb-4">
                    <div class="modern-card animate-fade-in" style="animation-delay: 0.3s">
                        <div class="card-header bg-white border-0" style="padding: 20px;">
                            <h5 class="mb-0">
                                <i data-feather="credit-card" style="width: 20px; height: 20px;"></i> Payment History
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <!-- Desktop Table View -->
                            <div class="desktop-table">
                                <div class="table-responsive">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Method</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($vendor->vendorPayments()->latest('payment_date')->get() as $payment)
                                                <tr>
                                                    <td>
                                                        <small>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info badge-modern">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                                        @if ($payment->reference_number)
                                                            <br><small
                                                                class="text-muted">{{ $payment->reference_number }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <strong
                                                            class="text-success">₹{{ number_format($payment->amount, 2) }}</strong>
                                                    </td>
                                                </tr>
                                                @if ($payment->notes)
                                                    <tr>
                                                        <td colspan="3"
                                                            style="background: #f9fafb; padding: 12px 16px;">
                                                            <small class="text-muted">
                                                                <i data-feather="message-circle"
                                                                    style="width: 12px; height: 12px;"></i>
                                                                {{ $payment->notes }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="3">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon">
                                                                <i data-feather="credit-card"></i>
                                                            </div>
                                                            <h6>No Payments Recorded</h6>
                                                            <p class="text-muted mb-0">No payments have been made yet.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        @if ($vendor->vendorPayments->count() > 0)
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" class="text-end"><strong>Total Paid:</strong></td>
                                                    <td class="text-end text-success">
                                                        <strong>₹{{ number_format($vendor->total_paid, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="mobile-view" style="padding: 16px;">
                                @forelse($vendor->vendorPayments()->latest('payment_date')->get() as $payment)
                                    <div class="mobile-card" style="border-left-color: #10b981;">
                                        <div class="mobile-card-header">
                                            <div>
                                                <strong>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</strong>
                                                <br><span
                                                    class="badge bg-info badge-modern">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                            </div>
                                            <div class="text-end">
                                                <strong
                                                    class="text-success">₹{{ number_format($payment->amount, 2) }}</strong>
                                            </div>
                                        </div>
                                        @if ($payment->reference_number)
                                            <div class="mobile-card-row">
                                                <span class="mobile-card-label">Reference:</span>
                                                <span class="mobile-card-value">{{ $payment->reference_number }}</span>
                                            </div>
                                        @endif
                                        @if ($payment->notes)
                                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #f3f4f6;">
                                                <small class="text-muted">
                                                    <i data-feather="message-circle"
                                                        style="width: 12px; height: 12px;"></i>
                                                    {{ $payment->notes }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i data-feather="credit-card"></i>
                                        </div>
                                        <h6>No Payments Recorded</h6>
                                        <p class="text-muted mb-0">No payments made yet.</p>
                                    </div>
                                @endforelse

                                @if ($vendor->vendorPayments->count() > 0)
                                    <div class="mobile-card" style="background: #f0fdf4; border-left-color: #10b981;">
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label"><strong>Total Paid:</strong></span>
                                            <span class="mobile-card-value text-success">
                                                <strong>₹{{ number_format($vendor->total_paid, 2) }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i data-feather="dollar-sign"></i> Add Payment to {{ $vendor->name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('vendor-settlements.add-payment', $vendor->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Outstanding Balance:</strong> ₹{{ number_format($vendor->outstanding_balance, 2) }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control"
                                value="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                value="{{ $vendor->outstanding_balance }}" placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Method <span
                                    class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method_detail" class="form-select" required
                                onchange="loadPaymentAccountsDetail()">
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="upi">UPI</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Account <span
                                    class="text-danger">*</span></label>
                            <select name="payment_account_id" id="payment_account_id_detail" class="form-select"
                                required>
                                <option value="">Select payment method first</option>
                            </select>
                            <small class="text-muted">Balance: ₹<span id="account_balance_detail">0.00</span></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference Number</label>
                            <input type="text" name="reference_number" class="form-control"
                                placeholder="Transaction ID, Cheque No., etc.">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any additional notes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i data-feather="check"></i> Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function loadPaymentAccountsDetail() {
            const method = document.getElementById('payment_method_detail').value;
            const accountSelect = document.getElementById('payment_account_id_detail');
            const balanceSpan = document.getElementById('account_balance_detail');

            if (!method) {
                accountSelect.innerHTML = '<option value="">Select payment method first</option>';
                balanceSpan.textContent = '0.00';
                return;
            }

            accountSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/admin/payment-accounts/by-type/${method}`)
                .then(response => response.json())
                .then(accounts => {
                    accountSelect.innerHTML = '<option value="">Select Account</option>';

                    if (accounts.length === 0) {
                        accountSelect.innerHTML = '<option value="">No accounts available</option>';
                        return;
                    }

                    accounts.forEach(account => {
                        const option = document.createElement('option');
                        option.value = account.id;
                        option.textContent =
                            `${account.account_name} (₹${parseFloat(account.balance).toFixed(2)})`;
                        option.dataset.balance = account.balance;
                        accountSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading accounts:', error);
                    accountSelect.innerHTML = '<option value="">Error loading accounts</option>';
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const accountSelect = document.getElementById('payment_account_id_detail');
            const balanceSpan = document.getElementById('account_balance_detail');

            accountSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.balance) {
                    balanceSpan.textContent = parseFloat(selectedOption.dataset.balance).toFixed(2);
                } else {
                    balanceSpan.textContent = '0.00';
                }
            });

            feather.replace();
        });
    </script>
@endsection
