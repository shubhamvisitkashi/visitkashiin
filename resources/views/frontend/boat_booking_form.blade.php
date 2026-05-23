@extends('frontend.layouts.app')
@section('meta')
    <link rel="canonical" href="{{url()->full()}}">
    <title>{{$boat_type->seo_title}} - {{env('APP_NAME')}}</title>
    <meta name="description" content="{{optional($boat_type)->seo_description}}">
    <meta name="keywords" content="{{optional($boat_type)->seo_keyword}}">
    <meta property="og:title" content="{{optional($boat_type)->seo_title}}">
    <meta property="og:site_name" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{optional($boat_type)->seo_description}}">
    <meta property="og:keywords" content="{{optional($boat_type)->seo_keyword}}">
@endsection
@section('content')
    <section class="breadcrumb-outer text-center">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Boat Booking</h2>
            </div>
        </div>
        <div class="section-overlay"></div>
    </section>

    <section class="destinations">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #acbbcb, #6c757d);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="text-white mb-0">Booking Details</h3>
                                <span class="text-white"><i class="fa fa-calendar"></i> 05 Nov 2025</span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-circle me-2"></i>{{ session('success') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <form action="{{route('festival.boat.booking.store', $boat_type->slug)}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name..." value="{{ old('customer_name') }}">
                                            @error('customer_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_email">Email Address  <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Customer Email..." value="{{ old('customer_email') }}">
                                            @error('customer_email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_phone">Phone Number  <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" placeholder="Customer Phone..." value="{{ old('customer_phone') }}">
                                            @error('customer_phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="no_of_person">Number of Persons  <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="decreasePerson()" id="decrease-btn">-</button>
                                                <input type="number" class="form-control text-center" id="no_of_person" name="no_of_person" min="1" value="{{ old('no_of_person', 1) }}" onchange="calculatePrice()" readonly>
                                                <button type="button" class="btn btn-outline-secondary" onclick="increasePerson()">+</button>
                                            </div>
                                            @error('no_of_person')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Confirm Booking</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Boat Type Details -->
                    <div class="card mb-4">
                        <div class="card-header" style="background: linear-gradient(135deg, #acbbcb, #6c757d);">
                            <h4 class="text-white">Boat Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="{{ $product->boatType->image }}" alt="{{ $product->boatType->name }}" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            <h5>{{ $product->boatType->name }}</h5>
                            @if($product->boatType->description)
                                <p class="text-muted">{{ $product->boatType->description }}</p>
                            @endif
                            @if($product->no_of_seat)
                                <p><strong>Capacity:</strong> {{ $product->no_of_seat }} persons</p>
                            @endif
                            @if($product->boatType->duration)
                                <p><strong>Duration:</strong> {{ $product->boatType->duration }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #acbbcb, #6c757d);">
                            <h4 class="text-white">Pricing Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="pricing-info">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Price per Person:</span>
                                    <span>₹{{ number_format($product->discounted_price) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Number of Persons:</span>
                                    <span id="persons_count">1</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Original Total:</span>
                                    <span><del id="original_total">₹{{ number_format($product->price) }}</del></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discounted Total:</span>
                                    <span class="text-success"><strong id="discounted_total">₹{{ number_format($product->discounted_price) }}</strong></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-success">You Save:</span>
                                    <span class="text-success" id="savings">₹{{ number_format($product->price - $product->discounted_price) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span><strong>Total Amount:</strong></span>
                                    <span><strong id="final_total" class="text-primary">₹{{ number_format($product->discounted_price) }}</strong></span>
                                </div>
                            </div>

                            @if($product->description)
                                <div class="mt-3">
                                    <h6>Product Description:</h6>
                                    <p class="text-muted small">{{ $product->description }}</p>
                                </div>
                            @endif

                            @if($product->inclusions)
                                <div class="mt-3">
                                    <h6>Inclusions:</h6>
                                    <p class="text-muted small">{{ $product->inclusions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        const originalPrice = {{ $product->price }};
        const discountedPrice = {{ $product->discounted_price }};

        function calculatePrice() {
            const persons = document.getElementById('no_of_person').value || 1;
            const originalTotal = originalPrice * persons;
            const discountedTotal = discountedPrice * persons;
            const savings = originalTotal - discountedTotal;

            // Update display elements
            document.getElementById('persons_count').textContent = persons;
            document.getElementById('original_total').textContent = '₹' + originalTotal.toLocaleString('en-IN');
            document.getElementById('discounted_total').textContent = '₹' + discountedTotal.toLocaleString('en-IN');
            document.getElementById('savings').textContent = '₹' + savings.toLocaleString('en-IN');
            document.getElementById('final_total').textContent = '₹' + discountedTotal.toLocaleString('en-IN');
        }

        function increasePerson() {
            const input = document.getElementById('no_of_person');
            let currentValue = parseInt(input.value) || 1;
            input.value = currentValue + 1;
            calculatePrice();
            updateDecreaseButton();
        }

        function decreasePerson() {
            const input = document.getElementById('no_of_person');
            let currentValue = parseInt(input.value) || 1;
            if (currentValue > 1) {
                input.value = currentValue - 1;
                calculatePrice();
                updateDecreaseButton();
            }
        }

        function updateDecreaseButton() {
            const input = document.getElementById('no_of_person');
            const decreaseBtn = document.getElementById('decrease-btn');
            const currentValue = parseInt(input.value) || 1;

            if (currentValue <= 1) {
                decreaseBtn.disabled = true;
                decreaseBtn.classList.add('disabled');
            } else {
                decreaseBtn.disabled = false;
                decreaseBtn.classList.remove('disabled');
            }
        }

        // Update the DOMContentLoaded event
        document.addEventListener('DOMContentLoaded', function() {
            calculatePrice();
            updateDecreaseButton();
        });
    </script>
@endsection
