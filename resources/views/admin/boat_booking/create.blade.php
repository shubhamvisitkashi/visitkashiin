@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">
                    @if(isset($boat_booking_request))
                        Convert Booking Request to Booking
                    @else
                        Create New Boat Booking
                    @endif
                </h2>
                <p class="text-muted mb-0">
                    @if(isset($boat_booking_request))
                        Convert booking request ID: {{ $boat_booking_request->booking_request_id }} to confirmed booking
                    @else
                        Add a new boat booking to the system
                    @endif
                </p>
            </div>
            <div>
                <a href="{{route('boat-booking.index')}}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
            </div>
        </div>

        @if(isset($boat_booking_request))
            <!-- Booking Request Information Card -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="alert alert-info border-0 shadow-sm">
                        <h6 class="alert-heading fw-bold"><i class="fas fa-info-circle me-2"></i>Converting Booking Request</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Request ID:</strong> {{ $boat_booking_request->booking_request_id }}
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $boat_booking_request->booking_status == 'confirmed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($boat_booking_request->booking_status) }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Payment Status:</strong>
                                <span class="badge bg-{{ $boat_booking_request->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($boat_booking_request->payment_status) }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Created:</strong> {{ $boat_booking_request->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-ship me-2"></i>Boat Booking Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="boat_booking_form" method="POST" action="{{route('boat-booking.store')}}" enctype="multipart/form-data">
                            @csrf

                            @if(isset($boat_booking_request))
                                <input type="hidden" name="booking_request_id" value="{{ $boat_booking_request->booking_request_id }}">
                            @endif

                            <div class="border rounded p-3 mb-4 bg-light">
                                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-ship me-1"></i>Boat Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="boat_type" class="form-label fw-semibold"><i class="fas fa-ship text-primary me-1"></i>Boat Type <span class="text-danger">*</span></label>
                                        <select id="boat_type" class="form-select form-select-lg border-2" name="boat_type" required onchange="checkAvailability()">
                                            <option value="">Select Boat Type</option>
                                            @foreach($boat_types as $boat_type)
                                                <option value="{{$boat_type->id}}"
                                                    @if(isset($boat_booking_request) && $boat_booking_request->boat && $boat_booking_request->boat->boat_type_id == $boat_type->id)
                                                        selected
                                                    @elseif(old('boat_type') == $boat_type->id)
                                                        selected
                                                    @endif>
                                                    {{$boat_type->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger lbl_msg" id="error_boat_type"></small>
                                        @error('boat_type')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="event_type" class="form-label fw-semibold"><i class="fas fa-calendar text-primary me-1"></i>Event Type <span class="text-danger">*</span></label>
                                        <select id="event_type" class="form-select form-select-lg border-2" name="event_type" required onchange="checkAvailability()">
                                            <option value="">Select Event Type</option>
                                            <option value="Regular"
                                                @if(old('event_type') == 'Regular')
                                                    selected
                                                @endif>Regular</option>
                                            <option value="Festival"
                                                @if(isset($boat_booking_request))
                                                    selected
                                                @elseif(old('event_type') == 'Festival')
                                                    selected
                                                @endif>Festival</option>
                                        </select>
                                        <small class="text-danger lbl_msg" id="error_event_type"></small>
                                        @error('event_type')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Information Section -->
                            <div class="border rounded p-3 mb-4 bg-light">
                                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-user me-1"></i>Customer Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label fw-semibold"><i class="fas fa-user text-primary me-1"></i>Full Name <span class="text-danger">*</span></label>
                                        <input id="name" class="form-control form-control-lg border-2" type="text" name="name"
                                            value="{{ isset($boat_booking_request) ? $boat_booking_request->name : old('name') }}"
                                            placeholder="Enter customer name" required>
                                        <small class="text-danger lbl_msg" id="error_name"></small>
                                        @error('name')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="email" class="form-label fw-semibold"><i class="fas fa-envelope text-primary me-1"></i>Email Address <span class="text-danger">*</span></label>
                                        <input id="email" class="form-control form-control-lg border-2" type="email" name="email"
                                            value="{{ isset($boat_booking_request) ? $boat_booking_request->email : old('email') }}"
                                            placeholder="Enter email address" required>
                                        <small class="text-danger lbl_msg" id="error_email"></small>
                                        @error('email')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="phone" class="form-label fw-semibold"><i class="fas fa-phone text-primary me-1"></i>Phone Number <span class="text-danger">*</span></label>
                                        <input id="phone" class="form-control form-control-lg border-2" type="tel" name="phone"
                                            value="{{ isset($boat_booking_request) ? $boat_booking_request->phone : old('phone') }}"
                                            placeholder="Enter phone number" required>
                                        <small class="text-danger lbl_msg" id="error_phone"></small>
                                        @error('phone')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details Section -->
                            <div class="border rounded p-3 mb-4 bg-light">
                                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-users me-1"></i>Booking Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="no_of_person" class="form-label fw-semibold"><i class="fas fa-users text-primary me-1"></i>Number of Persons <span class="text-danger">*</span></label>
                                        <input id="no_of_person" class="form-control form-control-lg border-2" type="number" name="no_of_person"
                                            value="{{ isset($boat_booking_request) ? $boat_booking_request->no_of_person : old('no_of_person') }}"
                                            placeholder="Enter number of persons" min="1" required onkeyup="checkAvailability()">
                                        <small class="text-danger lbl_msg" id="error_no_of_person"></small>
                                        @error('no_of_person')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="seat_number" class="form-label fw-semibold"><i class="fas fa-chair text-primary me-1"></i>Seat Number <span class="text-danger">*</span></label>
                                        <div class="form-control form-control-lg border-2 d-flex align-items-center" style="height: auto; min-height: 48px;">
                                            <div class="form-check">
                                                <input id="seat_number" class="form-check-input" type="checkbox" name="seat_number" value="1"
                                                    @if(isset($boat_booking_request) && $boat_booking_request->seat_number) checked @endif>
                                                <label class="form-check-label fw-semibold" for="seat_number">
                                                    Assign Seat Number
                                                </label>
                                            </div>
                                        </div>
                                        <small class="text-muted">Check to automatically assign seat number</small>
                                        <small class="text-danger lbl_msg" id="error_seat_number"></small>
                                        @error('seat_number')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="event_date" class="form-label fw-semibold"><i class="fas fa-calendar-alt text-primary me-1"></i>Event Date <span class="text-danger">*</span></label>
                                        <input id="event_date" class="form-control form-control-lg border-2" type="datetime-local" name="event_date"
                                            value="{{ isset($boat_booking_request) ? date('Y-m-d\TH:i', strtotime($boat_booking_request->booking_date)) : old('event_date', '2025-11-05T16:00') }}"
                                            required onchange="checkAvailability()">
                                        <small class="text-danger lbl_msg" id="error_event_date"></small>
                                        @error('event_date')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Information Section -->
                            <div class="border rounded p-3 mb-4 bg-light">
                                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-rupee-sign me-1"></i>Payment Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="total_amount" class="form-label fw-semibold"><i class="fas fa-calculator text-primary me-1"></i>Total Amount <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light">₹</span>
                                            <input id="total_amount" class="form-control border-2 bg-light" type="number" name="total_amount"
                                                value="{{ isset($boat_booking_request) ? $boat_booking_request->total_amount : old('total_amount') }}"
                                                placeholder="Enter total amount" min="0" step="0.01" required readonly>
                                        </div>
                                        <small class="text-danger lbl_msg" id="error_total_amount"></small>
                                        @error('total_amount')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="discount_amount" class="form-label fw-semibold"><i class="fas fa-tag text-success me-1"></i>Discount Amount <small class="text-muted">(Optional)</small></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light">₹</span>
                                            <input id="discount_amount" class="form-control border-2" type="number" name="discount_amount"
                                                value="{{ isset($boat_booking_request) ? $boat_booking_request->total_discount : old('discount_amount') }}"
                                                placeholder="Enter discount amount" min="0" step="0.01" onkeyup="calculateAmount()">
                                        </div>
                                        <small class="text-danger lbl_msg" id="error_discount_amount"></small>
                                        @error('discount_amount')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="final_amount" class="form-label fw-semibold"><i class="fas fa-money-bill text-success me-1"></i>Final Amount <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light">₹</span>
                                            <input id="final_amount" class="form-control border-2 bg-light" type="number" name="final_amount"
                                                value="{{ isset($boat_booking_request) ? $boat_booking_request->final_amount : old('final_amount') }}"
                                                placeholder="Final amount" min="0" step="0.01" readonly>
                                        </div>
                                        <small class="text-danger lbl_msg" id="error_final_amount"></small>
                                        @error('final_amount')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="paid_amount" class="form-label fw-semibold"><i class="fas fa-money-bill text-success me-1"></i>Paid Amount <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light">₹</span>
                                            <input id="paid_amount" class="form-control border-2" type="number" name="paid_amount"
                                                value="{{ isset($boat_booking_request) && $boat_booking_request->payment_status == 'paid' ? $boat_booking_request->final_amount : old('paid_amount') }}"
                                                placeholder="Paid amount" min="0" step="0.01" onkeyup="calculateAmount()">
                                        </div>
                                        <small class="text-danger lbl_msg" id="error_paid_amount"></small>
                                        @error('paid_amount')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if(isset($boat_booking_request) && isset($boat_booking_request->payment_detail['utr_number']))
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="alert alert-success">
                                                <h6 class="alert-heading"><i class="fas fa-credit-card me-2"></i>Payment Information</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <strong>UTR Number:</strong> {{ $boat_booking_request->payment_detail['utr_number'] }}
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong>Payment Method:</strong> {{ $boat_booking_request->payment_detail['payment_method'] ?? 'UPI' }}
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong>Transaction Date:</strong> {{ isset($boat_booking_request->payment_detail['transaction_date']) ? date('d M Y, h:i A', strtotime($boat_booking_request->payment_detail['transaction_date'])) : 'N/A' }}
                                                    </div>
                                                </div>
                                                @if(isset($boat_booking_request->payment_detail['screenshot']))
                                                    <div class="mt-2">
                                                        <strong>Payment Screenshot:</strong>
                                                        <a href="{{ asset('storage/' . $boat_booking_request->payment_detail['screenshot']) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                                            <i class="fas fa-image me-1"></i>View Screenshot
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-2">
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Final amount will be calculated automatically (Total Amount - Discount Amount)</small>
                                </div>
                            </div>

                            <!-- Summary Card -->
                            <div class="card bg-primary bg-opacity-10 border-primary">
                                <div class="card-body">
                                    <h6 class="fw-semibold text-primary mb-3 text-center"><i class="fas fa-receipt me-1"></i>Booking Summary</h6>
                                    <div class="row text-center">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-primary mb-1" id="summary-total">
                                                    ₹{{ isset($boat_booking_request) ? number_format($boat_booking_request->total_amount, 2) : '0.00' }}
                                                </div>
                                                <small class="text-muted">Total Amount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-warning mb-1" id="summary-discount">
                                                    ₹{{ isset($boat_booking_request) ? number_format($boat_booking_request->total_discount, 2) : '0.00' }}
                                                </div>
                                                <small class="text-muted">Discount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-success mb-1" id="summary-final">
                                                    ₹{{ isset($boat_booking_request) ? number_format($boat_booking_request->final_amount, 2) : '0.00' }}
                                                </div>
                                                <small class="text-muted">Final Amount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-success mb-1" id="summary-due-amount">
                                                    @php
                                                        $dueAmount = 0;
                                                        if(isset($boat_booking_request)) {
                                                            $paidAmount = ($boat_booking_request->payment_status == 'paid') ? $boat_booking_request->final_amount : 0;
                                                            $dueAmount = $boat_booking_request->final_amount - $paidAmount;
                                                        }
                                                    @endphp
                                                    ₹{{ number_format($dueAmount, 2) }}
                                                </div>
                                                <small class="text-muted">Due Amount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-info mb-1" id="summary-persons">
                                                    {{ isset($boat_booking_request) ? $boat_booking_request->no_of_person : '0' }}
                                                </div>
                                                <small class="text-muted">Persons</small>
                                            </div>
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-top">
                                <div class="d-flex gap-3 justify-content-center">
                                    <button type="button" class="btn btn-primary btn-lg px-5" onclick="submitForm('no_mail')"><i class="fas fa-save me-2"></i>Save Booking</button>
                                    <button type="button" class="btn btn-primary btn-lg px-5" onclick="submitForm('send_mail')"><i class="fas fa-save me-2"></i>Send Booking Confirmation</button>
                                    <a href="{{route('boat-booking.index')}}" class="btn btn-outline-danger btn-lg px-4"><i class="fas fa-times me-2"></i>Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set initial values when page loads if boat_booking_request exists
        document.addEventListener('DOMContentLoaded', function() {
            @if(isset($boat_booking_request))
                // Trigger calculation on page load to set summary values
                calculateAmount();
            @endif
        });

        function checkAvailability() {
            var boatType = $('#boat_type').val();
            var eventType = $('#event_type').val();
            var noOfPersons = $('#no_of_person').val();
            var eventDate = $('#event_date').val();

            if(boatType && eventType && noOfPersons && noOfPersons > 0 && eventDate) {
                $.ajax({
                    url: '{{ route("boat-booking.check.availability") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        boat_type: boatType,
                        event_type: eventType,
                        no_of_person: noOfPersons,
                        event_date: eventDate
                    },
                    success: function(response) {
                        if(eventType == "Festival") {
                            var TotalAmount = response.price * noOfPersons;
                            var DiscountAmount = (response.price - response.discounted_price) * noOfPersons;
                            var FinalAmount = TotalAmount - DiscountAmount;

                            $('#total_amount').val(TotalAmount.toFixed(2));
                            $('#summary-total').text('₹ ' + TotalAmount.toFixed(2));
                            $('#discount_amount').val(DiscountAmount.toFixed(2));
                            $('#summary-discount').text('₹ ' + DiscountAmount.toFixed(2));
                            $('#final_amount').val(FinalAmount.toFixed(2));
                            $('#summary-final').text('₹ ' + FinalAmount.toFixed(2));
                            $('#summary-persons').text(noOfPersons);

                            calculateAmount();
                        }
                    },
                    error: function(xhr) {
                        var response = JSON.parse(xhr.responseText);
                        var errorMessage = response.message || 'An error occurred while checking availability. Please try again.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });

                        // Clear the amount fields when no boats are available
                        $('#total_amount').val('');
                        $('#summary-total').text('₹ 0.00');
                        $('#discount_amount').val('');
                        $('#summary-discount').text('₹ 0.00');
                        $('#final_amount').val('');
                        $('#summary-final').text('₹ 0.00');
                        $('#summary-persons').text('0');
                    }
                });
            } else {
                @if(!isset($boat_booking_request))
                    $('#total_amount').val('');
                    $('#summary-total').text('₹ 0.00');
                    $('#discount_amount').val('');
                    $('#summary-discount').text('₹ 0.00');
                    $('#final_amount').val('');
                    $('#summary-final').text('₹ 0.00');
                    $('#summary-persons').text('0');
                @endif
            }
        }

        function calculateAmount() {
            var TotalAmount = parseFloat($('#total_amount').val()) || 0;
            var DiscountAmount = parseFloat($('#discount_amount').val()) || 0;
            var PaidAmount = parseFloat($('#paid_amount').val()) || 0;
            var noOfPersons = $('#no_of_person').val() || 0;
            var FinalAmount = TotalAmount - DiscountAmount;
            var DueAmount = FinalAmount - PaidAmount;

            $('#final_amount').val(FinalAmount.toFixed(2));
            $('#summary-total').text('₹ ' + TotalAmount.toFixed(2));
            $('#summary-discount').text('₹ ' + DiscountAmount.toFixed(2));
            $('#summary-final').text('₹ ' + FinalAmount.toFixed(2));
            $('#summary-due-amount').text('₹ ' + DueAmount.toFixed(2));
            $('#summary-persons').text(noOfPersons);
        }

        function submitForm(is_mail_send) {
            $.ajax({
                url: '{{ route("boat-booking.store") }}',
                type: 'POST',
                data: $('#boat_booking_form').serialize() + '&is_mail_send=' + is_mail_send,
                success: function(response) {
                    window.location.href = '{{ route("boat-booking.index") }}';
                },
                error: function(request, status, error) {
                    $('.lbl_msg').text('');
                    if (request.status === 422) {
                        var errors = request.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                $('#error_' + field).text(message);
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: request.responseJSON.message || 'An error occurred while submitting the form. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    </script>

    <style>
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .card { transition: all 0.3s ease; }
        .is-invalid { border-color: #dc3545; }
    </style>
@endsection
