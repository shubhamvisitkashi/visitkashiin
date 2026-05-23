@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">Edit Boat Booking</h2>
                <p class="text-muted mb-0">Edit boat booking to the system</p>
            </div>
            <div>
                <a href="{{route('boat-booking.index')}}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-ship me-2"></i>Boat Booking Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="boat_booking_form" method="POST" action="{{route('boat-booking.update', $booking->booking_id)}}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="border rounded p-3 mb-4 bg-light">
                                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-ship me-1"></i>Boat Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="boat_type" class="form-label fw-semibold"><i class="fas fa-ship text-primary me-1"></i>Boat Type <span class="text-danger">*</span></label>
                                        <select id="boat_type" class="form-select form-select-lg border-2" name="boat_type" disabled>
                                            <option value="">Select Boat Type</option>
                                            @foreach($boat_types as $boat_type)
                                                <option value="{{$boat_type->id}}" @if(old('boat_type', $booking->boat?->boatType?->id) == $boat_type->id) selected @endif>{{$boat_type->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('boat_type')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="event_type" class="form-label fw-semibold"><i class="fas fa-calendar text-primary me-1"></i>Event Type <span class="text-danger">*</span></label>
                                        <select id="event_type" class="form-select form-select-lg border-2" name="event_type" disabled>
                                            <option value="">Select Event Type</option>
                                            <option value="Regular" @if(old('event_type', $booking->boat?->event_type) == 'Regular') selected @endif>Regular</option>
                                            <option value="Festival" @if(old('event_type', $booking->boat?->event_type) == 'Festival') selected @endif>Festival</option>
                                        </select>
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
                                        <input id="name" class="form-control form-control-lg border-2" type="text" name="name" value="{{old('name', $booking->name)}}" placeholder="Enter customer name">
                                        <small class="text-danger lbl_msg" id="error_name"></small>
                                        @error('name')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="email" class="form-label fw-semibold"><i class="fas fa-envelope text-primary me-1"></i>Email Address <span class="text-danger">*</span></label>
                                        <input id="email" class="form-control form-control-lg border-2" type="email" name="email" value="{{old('email', $booking->email)}}" placeholder="Enter email address">
                                        @error('email')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="phone" class="form-label fw-semibold"><i class="fas fa-phone text-primary me-1"></i>Phone Number <span class="text-danger">*</span></label>
                                        <input id="phone" class="form-control form-control-lg border-2" type="tel" name="phone" value="{{old('phone', $booking->phone)}}" placeholder="Enter phone number">
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
                                        <input id="no_of_person" class="form-control form-control-lg border-2" type="number" name="no_of_person" value="{{old('no_of_person', $booking->no_of_person)}}" placeholder="Enter number of persons" min="1" readonly>
                                        @error('no_of_person')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="seat_number" class="form-label fw-semibold"><i class="fas fa-chair text-primary me-1"></i>Seat Number <span class="text-danger">*</span></label>
                                        <div class="form-control form-control-lg border-2 d-flex align-items-center" style="height: auto; min-height: 48px;">
                                            <div class="form-check">
                                                {{$booking->seat_number ?? 'N/A'}}
                                            </div>
                                        </div>
                                        @error('seat_number')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="event_date" class="form-label fw-semibold"><i class="fas fa-calendar-alt text-primary me-1"></i>Event Date <span class="text-danger">*</span></label>
                                        <input id="event_date" class="form-control form-control-lg border-2" type="datetime-local" name="event_date" value="{{old('event_date', '2025-11-05T16:00')}}" readonly>
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
                                            <input id="total_amount" class="form-control border-2 bg-light" type="number" name="total_amount" value="{{old('total_amount', $booking->total_amount)}}" placeholder="Enter total amount" min="0" step="0.01" readonly>
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
                                            <input id="discount_amount" class="form-control border-2" type="number" name="discount_amount" value="{{old('discount_amount', $booking->total_discount)}}" placeholder="Enter discount amount" min="0" step="0.01" readonly>
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
                                            <input id="final_amount" class="form-control border-2 bg-light" type="number" name="final_amount" value="{{old('final_amount', $booking->final_amount)}}" placeholder="Final amount" min="0" step="0.01" readonly>
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
                                            <input id="paid_amount" class="form-control border-2" type="number" name="paid_amount" value="{{old('paid_amount', $booking->payments_sum_amount)}}" placeholder="Paid amount" min="0" step="0.01" readonly>
                                        </div>
                                        <small class="text-danger lbl_msg" id="error_paid_amount"></small>
                                        @error('paid_amount')
                                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
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
                                                <div class="h5 fw-bold text-primary mb-1" id="summary-total">₹{{$booking->total_amount}}</div>
                                                <small class="text-muted">Total Amount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-warning mb-1" id="summary-discount">₹{{$booking->total_discount}}</div>
                                                <small class="text-muted">Discount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-success mb-1" id="summary-final">₹{{$booking->final_amount}}</div>
                                                <small class="text-muted">Final Amount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-success mb-1" id="summary-due-amount">₹{{$booking->final_amount - $booking->payments_sum_amount}}</div>
                                                <small class="text-muted">Due Amount</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="border rounded p-2 bg-white">
                                                <div class="h5 fw-bold text-info mb-1" id="summary-persons">{{$booking->no_of_person}}</div>
                                                <small class="text-muted">Persons</small>
                                            </div>
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-top">
                                <div class="d-flex gap-3 justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fas fa-save me-2"></i>Save Booking</button>
                                    <a href="{{route('boat-booking.index')}}" class="btn btn-outline-danger btn-lg px-4"><i class="fas fa-times me-2"></i>Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .card { transition: all 0.3s ease; }
        .is-invalid { border-color: #dc3545; }
    </style>
@endsection
