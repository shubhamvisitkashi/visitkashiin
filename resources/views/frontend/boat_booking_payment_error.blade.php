@extends('frontend.layouts.app')
@section('meta')
    <link rel="canonical" href="{{url()->full()}}">
    <title>Payment Error - {{env('APP_NAME')}}</title>
    <meta name="description" content="Payment failed for your boat booking">
    <meta name="keywords" content="payment error, boat booking, failed transaction">
@endsection
@section('content')
    <section class="destinations py-5" style="background: linear-gradient(135deg, #fff5f5, #fee2e2);">
        <div class="container">
            <!-- Error Message -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="text-center">
                        <div class="error-icon mb-4">
                            <i class="fa fa-times-circle text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h1 class="text-danger mb-3">Payment Failed!</h1>
                        <p class="lead text-muted">Unfortunately, we couldn't process your payment. Please try again or contact support.</p>
                    </div>
                </div>
            </div>

            <!-- Error Details -->
            @if(session('error'))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger text-center" style="border-radius: 15px;">
                            <h5 class="alert-heading"><i class="fa fa-exclamation-triangle me-2"></i>Error Details</h5>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Error Information Card -->
                    <div class="card shadow-lg border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545, #c82333); padding: 1.5rem; border-radius: 15px 15px 0 0;">
                            <div class="text-center">
                                <h3 class="mb-0"><i class="fa fa-exclamation-circle me-2"></i>Transaction Failed</h3>
                                @if(isset($booking_request_id))
                                    <p class="mb-0 mt-2">Booking Reference: <strong>{{ $booking_request_id }}</strong></p>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <!-- Common Error Reasons -->
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-danger"><i class="fa fa-question-circle me-2"></i>Why did this happen?</h5>
                                    <div class="error-reasons">
                                        <div class="reason-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <i class="fa fa-clock text-warning me-3 mt-1"></i>
                                                <div>
                                                    <strong>Session Expired</strong>
                                                    <p class="mb-0 text-muted small">Payment session timeout (5 minutes exceeded)</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="reason-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <i class="fa fa-wifi text-warning me-3 mt-1"></i>
                                                <div>
                                                    <strong>Network Issues</strong>
                                                    <p class="mb-0 text-muted small">Poor internet connection or server timeout</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="reason-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <i class="fa fa-credit-card text-warning me-3 mt-1"></i>
                                                <div>
                                                    <strong>Payment Gateway Error</strong>
                                                    <p class="mb-0 text-muted small">Bank server issues or incorrect payment details</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="reason-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <i class="fa fa-ban text-warning me-3 mt-1"></i>
                                                <div>
                                                    <strong>Insufficient Balance</strong>
                                                    <p class="mb-0 text-muted small">Not enough funds in your account</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- What to do next -->
                                <div class="col-md-6">
                                    <h5 class="mb-3 text-primary"><i class="fa fa-lightbulb me-2"></i>What should you do?</h5>
                                    <div class="solutions">
                                        <div class="solution-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <span class="badge bg-primary me-3 mt-1">1</span>
                                                <div>
                                                    <strong>Check Your Internet</strong>
                                                    <p class="mb-0 text-muted small">Ensure you have a stable internet connection</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="solution-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <span class="badge bg-primary me-3 mt-1">2</span>
                                                <div>
                                                    <strong>Verify Account Balance</strong>
                                                    <p class="mb-0 text-muted small">Make sure you have sufficient funds</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="solution-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <span class="badge bg-primary me-3 mt-1">3</span>
                                                <div>
                                                    <strong>Try Again</strong>
                                                    <p class="mb-0 text-muted small">Start a new booking process</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="solution-item mb-3 p-3 bg-light rounded">
                                            <div class="d-flex">
                                                <span class="badge bg-primary me-3 mt-1">4</span>
                                                <div>
                                                    <strong>Contact Support</strong>
                                                    <p class="mb-0 text-muted small">If problem persists, reach out to us</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Details (if available) -->
                            @if(isset($error_details))
                                <hr class="my-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted mb-3"><i class="fa fa-info-circle me-2"></i>Technical Details</h6>
                                        <div class="bg-light p-3 rounded">
                                            <small class="text-muted">
                                                <strong>Error Code:</strong> {{ $error_details['code'] ?? 'N/A' }}<br>
                                                <strong>Error Message:</strong> {{ $error_details['message'] ?? 'Unknown error occurred' }}<br>
                                                <strong>Timestamp:</strong> {{ now()->format('d M Y, h:i A') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body text-center p-4">
                            <h5 class="mb-4 text-primary">Choose Your Next Step</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('product.sub.list', ['festivals', 'dev-diwali-boat-booking']) }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #28a745, #20c997); border: none; color: white; border-radius: 25px;">
                                        <i class="fa fa-redo me-2"></i>Try Again
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-lg w-100" style="border-radius: 25px;">
                                        <i class="fa fa-home me-2"></i>Back to Home
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support Information -->
                    <div class="card shadow border-0" style="border-radius: 15px;">
                        <div class="card-header" style="background: linear-gradient(135deg, #acbbcb, #6c757d); border-radius: 15px 15px 0 0;">
                            <h5 class="text-white mb-0"><i class="fa fa-phone me-2"></i>Need Immediate Help?</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="contact-method p-3">
                                        <i class="fa fa-phone-alt text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6>Call Us</h6>
                                        <p class="text-muted mb-2">+91 7080109917/18/19</p>
                                        <small class="text-muted">24/7 Support Available</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="contact-method p-3">
                                        <i class="fa fa-envelope text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6>Email Us</h6>
                                        <p class="text-muted mb-2">support@visitkashi.com</p>
                                        <small class="text-muted">Response within 2 hours</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="contact-method p-3">
                                        <i class="fa fa-comments text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6>Live Chat</h6>
                                        <p class="text-muted mb-2">Available on website</p>
                                        <small class="text-muted">9 AM - 6 PM (Mon-Sun)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="alert alert-info mt-4" style="border-radius: 15px;">
                        <h6 class="alert-heading"><i class="fa fa-question-circle me-2"></i>Frequently Asked Questions</h6>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item border-0 mb-2">
                                <h6 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Will I be charged for this failed transaction?
                                    </button>
                                </h6>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body bg-white">
                                        No, failed transactions are automatically cancelled and no amount is deducted from your account.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-2">
                                <h6 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        How long does it take for refund if amount was deducted?
                                    </button>
                                </h6>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body bg-white">
                                        If any amount was deducted, it will be automatically refunded within 5-7 business days.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0">
                                <h6 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Can I try booking again immediately?
                                    </button>
                                </h6>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body bg-white">
                                        Yes, you can start a new booking process immediately. Make sure you have a stable internet connection.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .error-icon {
            animation: shake 1s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .reason-item, .solution-item {
            transition: all 0.3s ease;
            border-left: 4px solid #dc3545;
        }

        .reason-item:hover, .solution-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .solution-item {
            border-left-color: #007bff;
        }

        .contact-method {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .contact-method:hover {
            background-color: #f8f9fa;
            transform: translateY(-5px);
        }

        .card {
            transition: all 0.3s ease;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .accordion-button {
            border-radius: 8px !important;
        }

        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #acbbcb, #6c757d);
            color: white;
        }
    </style>

    <script>
        // Contact support function
        function contactSupport() {
            const phone = '+919876543210';
            const message = `Hello, I faced a payment error while booking. Booking Reference: {{ $booking_request_id ?? 'N/A' }}`;
            const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }

        // Auto-scroll to top on page load
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo(0, 0);

            // Auto-hide session messages after 10 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-danger');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('fade')) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    }
                });
            }, 10000);
        });

        // Add error reporting functionality
        function reportError() {
            const errorData = {
                booking_id: '{{ $booking_request_id ?? "N/A" }}',
                timestamp: new Date().toISOString(),
                user_agent: navigator.userAgent,
                url: window.location.href
            };

            // Send error report to server (implement as needed)
            console.log('Error reported:', errorData);
            alert('Error report sent. Our team will investigate this issue.');
        }
    </script>
@endsection
