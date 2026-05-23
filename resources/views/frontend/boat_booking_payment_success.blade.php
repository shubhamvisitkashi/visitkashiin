@extends('frontend.layouts.app')
@section('meta')
    <link rel="canonical" href="{{url()->full()}}">
    <title>Payment Successful - {{env('APP_NAME')}}</title>
    <meta name="description" content="Your boat booking payment has been completed successfully">
    <meta name="keywords" content="payment success, boat booking, confirmation">
@endsection
@section('content')
    <section class="destinations py-5" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
        <div class="container">
            <!-- Success Message -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="text-center">
                        <div class="success-icon mb-4">
                            <i class="fa fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h1 class="text-success mb-3">Payment Successful!</h1>
                        <p class="lead text-muted">Your boat booking has been confirmed. You will receive a confirmation email shortly.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Booking Confirmation Card -->
                    <div class="card shadow-lg border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-header text-white" style="background: linear-gradient(135deg, #28a745, #20c997); padding: 1.5rem; border-radius: 15px 15px 0 0;">
                            <div class="text-center">
                                <h3 class="mb-0"><i class="fa fa-ticket-alt me-2"></i>Booking Confirmation</h3>
                                <p class="mb-0 mt-2">Booking ID: <strong>{{ $boat_booking_request->booking_request_id }}</strong></p>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <!-- Customer Details -->
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-primary"><i class="fa fa-user me-2"></i>Customer Details</h5>
                                    <div class="info-group mb-3">
                                        <div class="info-item mb-2">
                                            <strong>Name:</strong> {{ $boat_booking_request->name }}
                                        </div>
                                        <div class="info-item mb-2">
                                            <strong>Email:</strong> {{ $boat_booking_request->email }}
                                        </div>
                                        <div class="info-item mb-2">
                                            <strong>Phone:</strong> {{ $boat_booking_request->phone }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-primary"><i class="fa fa-ship me-2"></i>Booking Details</h5>
                                    <div class="info-group mb-3">
                                        <div class="info-item mb-2">
                                            <strong>Boat Type:</strong> {{ $boat_booking_request->boat->boatType->name }}
                                        </div>
                                        <div class="info-item mb-2">
                                            <strong>Date & Time:</strong> {{ date('d M Y, h:i A', strtotime($boat_booking_request->booking_date)) }}
                                        </div>
                                        <div class="info-item mb-2">
                                            <strong>Persons:</strong> {{ $boat_booking_request->no_of_person }} person(s)
                                        </div>
                                        <div class="info-item mb-2">
                                            <strong>Status:</strong>
                                            <span class="badge bg-success">{{ ucfirst($boat_booking_request->booking_status) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Payment Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-success"><i class="fa fa-credit-card me-2"></i>Payment Details</h5>
                                    <div class="payment-info bg-light p-3 rounded">
                                        @php
                                            $paymentDetails = $boat_booking_request->payment_detail ?? [];
                                        @endphp

                                        <div class="info-item mb-2">
                                            <strong>Payment Status:</strong>
                                            <span class="badge bg-success">{{ ucfirst($boat_booking_request->payment_status) }}</span>
                                        </div>

                                        @if(isset($paymentDetails['utr_number']))
                                            <div class="info-item mb-2">
                                                <strong>UTR Number:</strong>
                                                <span class="text-primary fw-bold">{{ $paymentDetails['utr_number'] }}</span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyUTR()">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if(isset($paymentDetails['payment_method']))
                                            <div class="info-item mb-2">
                                                <strong>Payment Method:</strong> {{ ucfirst($paymentDetails['payment_method']) }}
                                            </div>
                                        @endif

                                        @if(isset($paymentDetails['transaction_date']))
                                            <div class="info-item mb-2">
                                                <strong>Transaction Date:</strong> {{ date('d M Y, h:i A', strtotime($paymentDetails['transaction_date'])) }}
                                            </div>
                                        @endif

                                        @if(isset($paymentDetails['remarks']))
                                            <div class="info-item mb-2">
                                                <strong>Remarks:</strong> {{ $paymentDetails['remarks'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Amount Breakdown -->
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-info"><i class="fa fa-calculator me-2"></i>Amount Breakdown</h5>
                                    <div class="amount-breakdown bg-light p-3 rounded">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Original Amount:</span>
                                            <span>₹{{ number_format($boat_booking_request->total_amount) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Discount:</span>
                                            <span>-₹{{ number_format($boat_booking_request->total_discount) }}</span>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between">
                                            <strong>Amount Paid:</strong>
                                            <strong class="text-success">₹{{ number_format($boat_booking_request->final_amount) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Screenshot Display -->
                            @if(isset($paymentDetails['screenshot']))
                                <hr class="my-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="mb-3 text-info"><i class="fa fa-image me-2"></i>Payment Screenshot</h5>
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $paymentDetails['screenshot']) }}" alt="Payment Screenshot" class="img-fluid rounded border" style="max-height: 300px;">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow border-0" style="border-radius: 15px;">
                        <div class="card-body text-center p-4">
                            <h5 class="mb-4">What's Next?</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3 m-auto">
                                    <a href="{{ route('index') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #acbbcb, #6c757d); border: none; color: white;">
                                        <i class="fa fa-home me-2"></i>Back to Home
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="alert alert-info mt-4" style="border-radius: 15px;">
                        <h6 class="alert-heading"><i class="fa fa-info-circle me-2"></i>Important Information</h6>
                        <ul class="mb-0">
                            <li>Reporting Time: 4:00 PM (Tentative)</li>
                            <li>Boarding & Deboarding Point: Sant Ravidas Ghat (way to Assi Ghat), Nagwa, near Lanka, Varanasi <a href="https://maps.app.goo.gl/LWThuFZY3nkr5Q1G6">https://maps.app.goo.gl/LWThuFZY3nkr5Q1G6</a></li>
                            <li>Children up to 5 years – Free Entry (without seat allocation)</li>
                            <li>Guests will need to walk down at the ghat to reach the Boat.</li>
                            <li>Please arrive on time – latecomers may miss the cruise.</li>
                            <li>For any queries, contact us at +91 7080109917/18/19</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .success-icon {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .info-item {
            padding: 8px 0;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .payment-info, .amount-breakdown {
            border-left: 4px solid #28a745;
        }

        @media print {
            .btn, .alert {
                display: none !important;
            }
        }
    </style>

    <script>
        // Copy UTR number function
        function copyUTR() {
            @if(isset($paymentDetails['utr_number']))
                const utrNumber = '{{ $paymentDetails["utr_number"] }}';
                navigator.clipboard.writeText(utrNumber).then(function() {
                    alert('UTR number copied to clipboard!');
                });
            @endif
        }

        // Download receipt function
        function downloadReceipt() {
            // Create a printable version
            const printContent = document.querySelector('.container').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

        // Print booking function
        function printBooking() {
            window.print();
        }

        // Auto-scroll to top on page load
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo(0, 0);

            // Add confetti effect (optional)
            if (typeof confetti !== 'undefined') {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }
        });
    </script>
@endsection
