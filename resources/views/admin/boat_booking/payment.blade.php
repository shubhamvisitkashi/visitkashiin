@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Booking Payment Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('boat-booking.index') }}">Boat Bookings</a></li>
                                <li class="breadcrumb-item active">Payment</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Booking Details Card -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-ship me-2"></i>Booking Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Booking ID:</td>
                                            <td>{{ $boat_booking->booking_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Guest Name:</td>
                                            <td>{{ $boat_booking->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Phone:</td>
                                            <td>{{ $boat_booking->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Email:</td>
                                            <td>{{ $boat_booking->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Event Type:</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $boat_booking->boat?->event_type }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Boat Type:</td>
                                            <td>{{ $boat_booking->boat?->boatType?->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">No. of Persons:</td>
                                            <td>{{ $boat_booking->no_of_person }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Seat Number:</td>
                                            <td>{{ $boat_booking->seat_number ?? 'Not Assigned' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Booking Date:</td>
                                            <td>{{ \Carbon\Carbon::parse($boat_booking->booking_date)->format('d M Y, h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $boat_booking->booking_status == 'confirmed' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($boat_booking->booking_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Transactions -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history me-2"></i>Payment History
                                </h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge bg-light text-dark fs-6">
                                        {{ $boat_booking->payments->count() }} Payments
                                    </span>
                                    <span class="badge bg-success fs-6">
                                        ₹{{ number_format($boat_booking->payments->sum('amount'), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($boat_booking->payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($boat_booking->payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>
                                                        <span class="fw-bold text-success">₹{{ number_format($payment->amount, 2) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="payment-details">
                                                            @if($payment->payment_details)
                                                                <div class="mb-1">
                                                                    <strong>Transaction ID:</strong>
                                                                    @if(isset($payment->payment_details['transaction_id']) && $payment->payment_details['transaction_id'])
                                                                        <span class="badge bg-primary">{{ $payment->payment_details['transaction_id'] }}</span>
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </div>

                                                                <div class="mb-1">
                                                                    <strong>Payment Mode:</strong>
                                                                    @if(isset($payment->payment_details['payment_mode']) && $payment->payment_details['payment_mode'])
                                                                        <span class="badge bg-info">{{ ucfirst($payment->payment_details['payment_mode']) }}</span>
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </div>

                                                                @if(isset($payment->payment_details['notes']) && $payment->payment_details['notes'])
                                                                    <div class="mb-1">
                                                                        <strong>Notes:</strong>
                                                                        <small class="text-muted">{{ $payment->payment_details['notes'] }}</small>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">No payment details</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No payment transactions found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Summary & New Payment -->
                <div class="col-lg-4">
                    <!-- Payment Summary -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-calculator me-2"></i>Payment Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Total Amount:</span>
                                <span class="fw-bold">₹{{ number_format($boat_booking->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Discount:</span>
                                <span class="text-warning fw-bold">-₹{{ number_format($boat_booking->total_discount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Final Amount:</span>
                                <span class="fw-bold">₹{{ number_format($boat_booking->final_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Paid Amount:</span>
                                <span class="text-success fw-bold">₹{{ number_format($boat_booking->payments->sum('amount'), 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Due Amount:</span>
                                <span class="fw-bold {{ $boat_booking->final_amount - $boat_booking->payments->sum('amount') <= 0 ? 'text-success' : 'text-danger' }}">
                                    ₹{{ number_format($boat_booking->final_amount - $boat_booking->payments->sum('amount'), 2) }}
                                </span>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-{{ $boat_booking->payment_status == 'paid' ? 'success' : ($boat_booking->payment_status == 'partial' ? 'danger' : 'danger') }} w-100 py-2">
                                    {{ ucfirst($boat_booking->payment_status == 'partial' ? 'due' : $boat_booking->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Take New Payment -->
                    @if($boat_booking->final_amount > $boat_booking->payments->sum('amount'))
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0"><i class="fas fa-plus me-2"></i>Add Payment</h5>
                            </div>
                            <div class="card-body">
                                <form id="paymentForm" action="{{route('boat-booking.payment.store', $boat_booking->booking_id)}}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                   max="{{ $boat_booking->final_amount - $boat_booking->payments->sum('amount') }}"
                                                   min="1" step="0.01" required>
                                        </div>
                                        <small class="text-muted">
                                            Max: ₹{{ number_format($boat_booking->final_amount - $boat_booking->payments->sum('amount'), 2) }}
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select" id="payment_method" name="payment_method" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="card">Credit/Debit Card</option>
                                            <option value="upi">UPI</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="online">Online Payment</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="transaction_id" class="form-label">Transaction/Reference ID</label>
                                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" placeholder="Enter transaction ID (optional)">
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Payment notes (optional)"></textarea>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-money-bill-wave me-2"></i>Add Payment
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="fillFullAmount()">
                                            <i class="fas fa-hand-holding-usd me-2"></i>Pay Full Due
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function fillFullAmount() {
        const maxAmount = {{ $boat_booking->final_amount - $boat_booking->payments->sum('amount') }};
        document.getElementById('amount').value = maxAmount.toFixed(2);
    }

    // Form submission with validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const amount = parseFloat(document.getElementById('amount').value);
        const maxAmount = {{ $boat_booking->final_amount - $boat_booking->payments->sum('amount') }};

        if (amount > maxAmount) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Amount',
                text: 'Amount cannot exceed the due amount of ₹' + maxAmount.toFixed(2),
            });
            return;
        }

        Swal.fire({
            title: 'Confirm Payment',
            text: 'Are you sure you want to add payment of ₹' + amount.toFixed(2) + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Add Payment'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // console.log(response);

                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Added',
                            text: 'Payment has been successfully added!',
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add payment. Please try again.',
                        });
                    }
                });
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    .table-borderless td {
        border: none;
        padding: 0.5rem 0;
    }
    .badge {
        font-size: 0.75em;
    }
</style>
@endpush
