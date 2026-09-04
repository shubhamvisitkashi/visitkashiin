@extends('frontend.layouts.app')
@section('meta')
    <link rel="canonical" href="{{url()->full()}}">
    <title>Payment - {{env('APP_NAME')}}</title>
    <meta name="description" content="Complete your boat booking payment">
    <meta name="keywords" content="payment, boat booking, QR code">
@endsection
@push('styles')
<link rel="preload" href="{{ asset('frontend/css/boat-booking-payment.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-booking-payment.min.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/boat-booking-payment.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-booking-payment.min.css')) }}"></noscript>
@endpush
@section('content')
    <section class="breadcrumb-outer text-center">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Payment</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Booking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="section-overlay"></div>
    </section>

    <section class="destinations py-5">
        <div class="container">
            <!-- Timer and Status -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fa fa-clock me-2"></i>
                        <strong>Complete your payment within: <span id="countdown-timer" class="text-danger"></span></strong>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if (session('error'))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <!-- Payment Instructions -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bbp-gray-gradient-bg">
                            <h4 class="text-white mb-0"><i class="fa fa-credit-card me-2"></i>Payment Instructions</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- QR Code Section -->
                                <div class="col-md-6 text-center">
                                    <h5 class="mb-3">Scan QR Code to Pay</h5>
                                    <div class="qr-code-container mb-3">
                                        @php
                                            $upiId = 'visitkashi271@iob';
                                            $payeeName = 'Visit Kashi';
                                            $amount = $boat_booking_request->final_amount;
                                            $currency = 'INR';
                                            $upiLink = "upi://pay?pa={$upiId}&pn=" . urlencode($payeeName) . "&am={$amount}&cu={$currency}";
                                        @endphp
                                        {!! QrCode::size(250)->generate($upiLink) !!}
                                        {{-- <img src="{{ asset('assets/images/payment-qr.png') }}" alt="Payment QR Code" class="img-fluid border rounded" style="max-width: 250px; border: 3px solid #acbbcb !important;"> --}}
                                    </div>
                                    <p class="text-muted small">Scan this QR code with any UPI app to make payment</p>
                                </div>

                                <!-- Payment Details -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Payment Details</h5>
                                    <div class="payment-info">
                                        <div class="info-item mb-3 p-3 bg-light rounded">
                                            <strong>UPI ID:</strong><br>
                                            <span class="text-primary">visitkashi271@iob</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyUPI()">
                                                <i class="fa fa-copy"></i> Copy
                                            </button>
                                        </div>
                                        <div class="info-item mb-3 p-3 bg-light rounded">
                                            <strong>Account Name:</strong><br>
                                            <span>Visit Kashi</span>
                                        </div>
                                        <div class="info-item mb-3 p-3 bg-light rounded">
                                            <strong>Amount to Pay:</strong><br>
                                            <span class="text-success h5">₹{{ number_format($boat_booking_request->final_amount) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Steps -->
                            <div class="mt-4">
                                <h6 class="mb-3">How to Pay:</h6>
                                <ol class="payment-steps">
                                    <li>Scan the QR code using any UPI app (PhonePe, Google Pay, Paytm, etc.)</li>
                                    <li>Enter the exact amount: <strong>₹{{ number_format($boat_booking_request->final_amount) }}</strong></li>
                                    <li>Complete the payment and take a screenshot</li>
                                    <li>Enter the UTR number and upload screenshot below</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Confirmation Form -->
                    <div class="card">
                        <div class="card-header bbp-gray-gradient-bg">
                            <h4 class="text-white mb-0"><i class="fa fa-check-circle me-2"></i>Payment Confirmation</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('festival.boat.booking.payment.store', $boat_booking_request->booking_request_id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="utr_number" class="form-label fw-bold">
                                                <i class="fa fa-hashtag me-2"></i>UTR/Transaction Number *
                                            </label>
                                            <input type="text" class="form-control @error('utr_number') is-invalid @enderror" id="utr_number" name="utr_number" placeholder="Enter 12-digit UTR number" value="{{ old('utr_number') }}" required>
                                            @error('utr_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">UTR number can be found in your payment confirmation message</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="payment_screenshot" class="form-label fw-bold">
                                                <i class="fa fa-camera me-2"></i>Payment Screenshot *
                                            </label>
                                            <input type="file" class="form-control @error('payment_screenshot') is-invalid @enderror" id="payment_screenshot" name="payment_screenshot" accept="image/*" required>
                                            @error('payment_screenshot')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Upload screenshot of payment confirmation</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="payment_remarks" class="form-label fw-bold">
                                                <i class="fa fa-comment me-2"></i>Additional Remarks (Optional)
                                            </label>
                                            <textarea class="form-control" id="payment_remarks" name="payment_remarks" rows="3" placeholder="Any additional notes about your payment...">{{ old('payment_remarks') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-lg px-5 bbp-gray-gradient-bg bbp-btn-gray-gradient bbp-radius-25">
                                        <i class="fa fa-check-circle me-2"></i>Confirm Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bbp-gray-gradient-bg">
                            <h4 class="text-white mb-0"><i class="fa fa-receipt me-2"></i>Booking Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="booking-details">
                                <div class="detail-item mb-3 pb-3 border-bottom">
                                    <strong>Booking ID:</strong><br>
                                    <span class="text-primary">{{ $boat_booking_request->booking_request_id }}</span>
                                </div>

                                <div class="detail-item mb-3 pb-3 border-bottom">
                                    <strong>Customer:</strong><br>
                                    <span>{{ $boat_booking_request->name }}</span><br>
                                    <small class="text-muted">{{ $boat_booking_request->email }}</small><br>
                                    <small class="text-muted">{{ $boat_booking_request->phone }}</small>
                                </div>

                                <div class="detail-item mb-3 pb-3 border-bottom">
                                    <strong>Boat Type:</strong><br>
                                    <span>{{ $boat_booking_request->boat->boatType->name }}</span>
                                </div>

                                <div class="detail-item mb-3 pb-3 border-bottom">
                                    <strong>Booking Date:</strong><br>
                                    <span>{{ date('d M Y, h:i A', strtotime($boat_booking_request->booking_date)) }}</span>
                                </div>

                                <div class="detail-item mb-3 pb-3 border-bottom">
                                    <strong>Number of Persons:</strong><br>
                                    <span>{{ $boat_booking_request->no_of_person }} person(s)</span>
                                </div>

                                <div class="pricing-breakdown">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Amount:</span>
                                        <span>₹{{ number_format($boat_booking_request->total_amount) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Discount:</span>
                                        <span>-₹{{ number_format($boat_booking_request->total_discount) }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Final Amount:</strong>
                                        <strong class="text-primary">₹{{ number_format($boat_booking_request->final_amount) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="card mt-4">
                        <div class="card-header bbp-gray-gradient-bg">
                            <h5 class="text-white mb-0"><i class="fa fa-question-circle me-2"></i>Need Help?</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">If you face any issues with payment, contact us:</p>
                            <div class="contact-info">
                                <p class="mb-2"><i class="fa fa-phone me-2"></i> +91 7080109971</p>
                                <p class="mb-2"><i class="fa fa-envelope me-2"></i> info.visitkashi@gmail.com</p>
                                <p class="mb-0"><i class="fa fa-clock-o me-2"></i> 9 AM - 6 PM (Mon-Sun)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .qr-code-container {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px;
            border-radius: 15px;
        }

        .payment-steps li {
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .info-item {
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .detail-item {
            transition: all 0.3s ease;
        }

        .countdown-timer {
            font-size: 1.2em;
            font-weight: bold;
        }

        #countdown-timer {
            color: #dc3545 !important;
        }
    </style>

    <script>
        // Countdown timer (5 minutes)
        function startCountdown() {
            const createdAt = new Date('{{ $boat_booking_request->created_at }}');
            const expiryTime = new Date(createdAt.getTime() + 5 * 60 * 1000); // 5 minutes from creation

            const timer = setInterval(function() {
                const now = new Date().getTime();
                const timeLeft = expiryTime.getTime() - now;

                if (timeLeft > 0) {
                    const minutes = Math.floor(timeLeft / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    document.getElementById('countdown-timer').innerHTML =
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    clearInterval(timer);
                    document.getElementById('countdown-timer').innerHTML = 'EXPIRED';

                    // Redirect to home page
                    setTimeout(function() {
                        window.location.href = '{{ route("festival.boat.booking.payment.error", $boat_booking_request->booking_request_id) }}';
                    }, 2000);
                }
            }, 1000);
        }

        // Copy UPI ID function
        function copyUPI() {
            const upiId = 'visitkashi271@iob';
            navigator.clipboard.writeText(upiId).then(function() {
                alert('UPI ID copied to clipboard!');
            });
        }

        // Start countdown when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();
        });
    </script>
@endsection
