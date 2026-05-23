@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">🚢 {{$page_title}} Management</h2>
                <p class="text-muted mb-0">Manage your boat booking requests efficiently</p>
                <div class="mt-2">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-list me-1"></i>
                        Total Requests: {{ $total_requests ?? $boat_bookings->total() }}
                    </span>
                    <span class="badge bg-warning fs-6 px-3 py-2">
                        <i class="fas fa-clock me-1"></i>
                        Pending: {{ $pending_requests ?? 0 }}
                    </span>
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-check me-1"></i>
                        Confirmed: {{ $confirmed_requests ?? 0 }}
                    </span>
                    <span class="badge bg-info fs-6 px-3 py-2">
                        <i class="fas fa-rupee-sign me-1"></i>
                        Total Amount: ₹{{ number_format($total_amount ?? 0, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-ship fa-2x"></i>
                        </div>
                        <h4 class="text-primary mb-0">{{ $total_requests ?? $boat_bookings->total() }}</h4>
                        <small class="text-muted" style="font-size: 20px;">Total Requests</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h4 class="text-warning mb-0">{{ $pending_requests ?? 0 }}</h4>
                        <small class="text-muted" style="font-size: 20px;">Pending Requests</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h4 class="text-success mb-0">{{ $confirmed_requests ?? 0 }}</h4>
                        <small class="text-muted" style="font-size: 20px;">Confirmed Requests</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-rupee-sign fa-2x"></i>
                        </div>
                        <h4 class="text-info mb-0">₹{{ number_format($total_amount ?? 0) }}</h4>
                        <small class="text-muted" style="font-size: 20px;">Total Value</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow">
                    <!-- Search Header -->
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <h6 class="mb-3 text-white"><i class="fas fa-filter me-2"></i>Search & Filter</h6>
                        <form action="{{route('boat-booking.requests')}}" method="GET">
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <input type="text" name="search_date" placeholder="Select Date..." value="{{ $search_date }}" class="form-control form-control-sm daterange" id="daterangePicker">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="search_user" class="form-control form-control-sm" placeholder="Name, phone, email..." value="{{ $search_user }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="search_booking_id" class="form-control form-control-sm" placeholder="Booking Request ID..." value="{{ $search_booking_id }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="search_status" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <option value="pending" @if($search_status === 'pending') selected @endif>Pending</option>
                                        <option value="confirmed" @if($search_status === 'confirmed') selected @endif>Confirmed</option>
                                        <option value="cancelled" @if($search_status === 'cancelled') selected @endif>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="search_payment_status" class="form-select form-select-sm">
                                        <option value="">All Payment Status</option>
                                        <option value="pending" @if($search_payment_status === 'pending') selected @endif>Pending</option>
                                        <option value="paid" @if($search_payment_status === 'paid') selected @endif>Paid</option>
                                        <option value="failed" @if($search_payment_status === 'failed') selected @endif>Failed</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-light btn-sm">
                                            <i data-feather="search"></i>
                                        </button>
                                        <a href="{{route('boat-booking.requests')}}" class="btn btn-outline-light btn-sm">
                                            <i data-feather="x-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Table Content -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 modern-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center fw-semibold border-0">#</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-id-card text-primary me-1"></i>Request Details</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-ship text-success me-1"></i>Boat Info</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-user text-info me-1"></i>Customer Info</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-rupee-sign text-warning me-1"></i>Payment Details</th>
                                        <th class="text-center fw-semibold border-0"><i class="fas fa-info-circle text-secondary me-1"></i>Status</th>
                                        <th class="text-center fw-semibold border-0"><i class="fas fa-cogs text-dark me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($boat_bookings as $boat_booking)
                                        <tr class="border-bottom-light">
                                            <td class="text-center">
                                                <span class="badge bg-primary text-white rounded-pill">{{ $boat_bookings->firstItem() + $loop->index }}</span>
                                            </td>

                                            <!-- Request Details -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-bold text-primary mb-1">{{$boat_booking->booking_request_id}}</div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-calendar-plus me-1"></i>
                                                        Created: {{$boat_booking->created_at->format('d M, Y')}}
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-calendar-check me-1"></i>
                                                        Booking: {{date('d M, Y', strtotime($boat_booking->booking_date))}}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Boat Info -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-semibold text-success mb-1">
                                                        <i class="fas fa-ship me-1"></i>{{$boat_booking->boat?->boatType?->name ?? 'N/A'}}
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-users me-1"></i>{{$boat_booking->no_of_person}} person(s)
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Customer Info -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                        {{ substr($boat_booking->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark mb-1">{{$boat_booking->name}}</div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-phone me-1"></i>{{$boat_booking->phone}}
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-envelope me-1"></i>{{$boat_booking->email}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Payment Details -->
                                            <td>
                                                <div class="payment-info">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-primary small">Total:</span>
                                                        <span class="fw-bold text-primary">₹{{number_format($boat_booking->total_amount, 2)}}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-info small">Discount:</span>
                                                        <span class="fw-bold text-info">₹{{number_format($boat_booking->total_discount, 2)}}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-success small">Final:</span>
                                                        <span class="fw-bold text-success">₹{{number_format($boat_booking->final_amount, 2)}}</span>
                                                    </div>

                                                    @php
                                                        $paymentDetails = $boat_booking->payment_detail ?? [];
                                                    @endphp

                                                    @if(isset($paymentDetails['utr_number']))
                                                        <div class="mt-2 p-2 bg-light rounded">
                                                            <small class="text-muted">UTR: {{$paymentDetails['utr_number']}}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Status -->
                                            <td class="text-center">
                                                <div class="mb-2">
                                                    <span class="badge bg-{{$boat_booking->booking_status == 'confirmed' ? 'success' : ($boat_booking->booking_status == 'pending' ? 'warning' : 'danger')}} status-badge">
                                                        {{ucfirst($boat_booking->booking_status)}}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="badge bg-{{$boat_booking->payment_status == 'paid' ? 'success' : ($boat_booking->payment_status == 'pending' ? 'warning' : 'danger')}} status-badge">
                                                        {{ucfirst($boat_booking->payment_status)}}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if($boat_booking->booking_status == 'pending')
                                                        <a class="btn btn-outline-success btn-sm" href="{{route('boat-booking.create')}}?booking_request_id={{$boat_booking->booking_request_id}}" title="Confirm Booking">
                                                            <i data-feather="check"></i>
                                                        </a>
                                                        <button class="btn btn-outline-danger btn-sm" onclick="cancelBooking('{{$boat_booking->booking_request_id}}')" title="Cancel Booking">
                                                            <i data-feather="x"></i>
                                                        </button>
                                                    @endif

                                                    @if(isset($paymentDetails['payment_screenshot']))
                                                        <button class="btn btn-outline-primary btn-sm" onclick="viewScreenshot('{{asset('storage/' . $paymentDetails['payment_screenshot'])}}')" title="View Payment Screenshot">
                                                            <i data-feather="image"></i>
                                                        </button>
                                                    @endif

                                                    {{-- <form action="{{ route('boat-booking-request.destroy', $boat_booking->booking_request_id) }}" method="POST" style="display:inline">
                                                        @method('Delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm deleteBtn" title="Delete"><i data-feather="trash-2"></i></button>
                                                    </form> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-ship text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                                    <h5 class="text-muted mb-2">No Booking Requests Found</h5>
                                                    <p class="text-muted mb-3">There are no boat booking requests matching your criteria.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($boat_bookings->hasPages())
                            <div class="card-footer bg-light border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Showing {{ $boat_bookings->firstItem() }} to {{ $boat_bookings->lastItem() }} of {{ $boat_bookings->total() }} results
                                    </div>
                                    <div>
                                        {{ $boat_bookings->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Screenshot Modal -->
    <div class="modal fade" id="screenshotModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Screenshot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="screenshotImage" src="" class="img-fluid" alt="Payment Screenshot">
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }

        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .payment-info {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            border-left: 3px solid #28a745;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .border-bottom-light {
            border-bottom: 1px solid #e9ecef !important;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-group .btn {
            margin-right: 2px;
            border-radius: 6px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            padding: 15px 12px;
        }

        .table td {
            padding: 15px 12px;
            vertical-align: middle;
        }
    </style>

    <script>

        function viewScreenshot(imageSrc) {
            $('#screenshotImage').attr('src', imageSrc);
            $('#screenshotModal').modal('show');
        }

        function cancelBooking(bookingRequestId) {
            Swal.fire({
                title: 'Cancel Booking Request?',
                text: 'Are you sure you want to cancel this booking request? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Cancel It!',
                cancelButtonText: 'No, Keep It'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we cancel the booking request.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send AJAX request
                    $.ajax({
                        url: `/admin/boat-booking-request/${bookingRequestId}/cancel`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Cancelled!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Reload the page to reflect changes
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred while cancelling the booking request.';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
