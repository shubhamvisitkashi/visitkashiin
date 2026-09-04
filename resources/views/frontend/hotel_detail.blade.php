@extends('frontend.layouts.app')

@section('meta')
    @isset($product)
        @php $hotelImg = !empty($product->images) ? asset('backend/admin/product_images/'.$product->images[0]) : asset('frontend/images/logo1.png'); @endphp
        <link rel="canonical" href="{{ url()->current() }}">
        <title>{{ optional($product)->meta_title ?? $product->name }} | Hotel in Varanasi - {{ env('APP_DOMAIN') }}</title>
        <meta name="description" content="{{ optional($product)->meta_description }}">
        <meta name="keywords"    content="{{ optional($product)->meta_keyword }}">
        <meta name="robots" content="index, follow">
        <meta property="og:type" content="hotel" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:title"       content="{{ optional($product)->meta_title ?? $product->name }}">
        <meta property="og:description" content="{{ optional($product)->meta_description }}">
        <meta property="og:image"       content="{{ $hotelImg }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ optional($product)->meta_title ?? $product->name }}">
        <meta name="twitter:description" content="{{ optional($product)->meta_description }}">
        <meta name="twitter:image" content="{{ $hotelImg }}">
    @endisset
@endsection

@push('styles')
<link rel="preload" href="{{ asset('frontend/css/hotel-detail.min.css') }}?v={{ filemtime(public_path('frontend/css/hotel-detail.min.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/hotel-detail.min.css') }}?v={{ filemtime(public_path('frontend/css/hotel-detail.min.css')) }}"></noscript>
@endpush

@section('content')

{{-- ─── Sticky Navigation ─────────────────────────────────────── --}}
<div class="hd-nav">
    <div class="hd-nav-inner">
        <a href="#overview"   class="active">Overview</a>
        <a href="#amenities">Amenities</a>
        <a href="#rules">House Rules</a>
        @if($product->map_location)
        <a href="#location">Location</a>
        @endif
        @if($product->youtube_link)
        <a href="#video">Video</a>
        @endif
        <a href="#enquiry" onclick="if(window.innerWidth<992){event.preventDefault();hdOpenPopup();}">Book Now</a>
    </div>
</div>

{{-- ─── Main Wrapper ─────────────────────────────────────────── --}}
<div class="hd-wrap">

    {{-- ─── Hotel Header ──────────────────────────────────────── --}}
    <div class="hd-header" id="overview">
        <div class="hd-header-top">
            <div class="hd-header-info">
                <div class="hd-type-badge">
                    &#127968;
                    {{ optional($product->category)->name ?? 'Hotel' }}
                    @if(optional($product->subCategory)->name)
                        &bull; {{ $product->subCategory->name }}
                    @endif
                </div>
                <h1 class="hd-name">{{ $product->name }}</h1>
                <div class="hd-stars">
                    &#9733;&#9733;&#9733;&#9733;<span class="hd-star-empty">&#9733;</span>
                    <span class="hd-star-label">4-Star Hotel</span>
                </div>
                @if(isset($product->address) && $product->address)
                <p class="hd-address">
                    <span>&#128205;</span>
                    {{ $product->address }}
                    @if($product->map_location)
                        &nbsp;— <a href="#location">Show on map</a>
                    @endif
                </p>
                @elseif(optional($product->subCategory)->name)
                <p class="hd-address">
                    <span>&#128205;</span>
                    {{ $product->subCategory->name }}, Varanasi, Uttar Pradesh
                    @if($product->map_location)
                        &nbsp;— <a href="#location">Show on map</a>
                    @endif
                </p>
                @endif
            </div>
            <div class="hd-score-box">
                <div class="hd-score">8.5</div>
                <div class="hd-score-label">Excellent</div>
                <div class="hd-score-count">Based on guest reviews</div>
            </div>
        </div>
        <div class="hd-badges">
            <span class="hd-badge">&#10003; Free Cancellation</span>
            <span class="hd-badge">&#10003; No Prepayment</span>
            <span class="hd-badge">&#10003; Book Now, Pay Later</span>
            <span class="hd-badge">&#10003; Instant Confirmation</span>
        </div>
    </div>

    {{-- ─── Photo Gallery (grid on desktop, carousel on mobile) ─ --}}
    @php
        $images = (!empty($product->images) && is_array($product->images)) ? $product->images : [];
        $imgBase = asset('backend/admin/product_images/');
        $placeholder = asset('backend/assets/images/placeholder.jpg');
    @endphp

    <div class="hd-gallery" id="gallery">

        {{-- Desktop grid --}}
        <div class="hd-grid">
            <div class="hd-grid-main">
                <img fetchpriority="high" src="{{ count($images) > 0 ? $imgBase.'/'.$images[0] : $placeholder }}"
                     alt="{{ $product->name }}" data-lb="0" loading="eager">
            </div>
            @if(count($images) > 1)
            <div class="hd-grid-tr">
                <img loading="lazy" src="{{ $imgBase.'/'.$images[1] }}" alt="{{ $product->name }}" data-lb="1">
            </div>
            @endif
            @if(count($images) > 2)
            <div class="hd-grid-mr">
                <img loading="lazy" src="{{ $imgBase.'/'.$images[2] }}" alt="{{ $product->name }}" data-lb="2">
            </div>
            @endif
            @if(count($images) > 3)
            <div class="hd-grid-br">
                <img loading="lazy" src="{{ $imgBase.'/'.$images[3] }}" alt="{{ $product->name }}" data-lb="3">
            </div>
            @endif
            @if(count($images) > 4)
            <div class="hd-grid-bfr">
                <img loading="lazy" src="{{ $imgBase.'/'.$images[4] }}" alt="{{ $product->name }}" data-lb="4">
                @if(count($images) > 5)
                <span class="more-overlay" data-lb="4">+{{ count($images) - 5 }} more</span>
                @endif
            </div>
            @endif
        </div>

        {{-- Mobile carousel --}}
        <div class="hd-carousel">
            <div id="hotelCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @forelse($images as $k => $img)
                    <div class="carousel-item @if($k===0) active @endif">
                        <img loading="lazy" src="{{ $imgBase.'/'.$img }}" alt="{{ $product->name }}" class="d-block w-100">
                    </div>
                    @empty
                    <div class="carousel-item active">
                        <img loading="lazy" src="{{ $placeholder }}" alt="No image" class="d-block w-100">
                    </div>
                    @endforelse
                </div>
                @if(count($images) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── Two-column body ──────────────────────────────────── --}}
    <div class="hd-body">

        {{-- LEFT CONTENT ──────────────────────────────────────── --}}
        <div class="hd-content">

            {{-- About ──────────────────────────────────────────── --}}
            <div class="hd-section">
                <div class="hd-section-title">About this property</div>
                <div class="hd-desc">
                    {!! safe_html($product->description) !!}
                </div>
            </div>

            {{-- Amenities ───────────────────────────────────────── --}}
            <div class="hd-section" id="amenities">
                <div class="hd-section-title">Most popular amenities</div>
                <div class="amenities-grid">
                    @php
                        $amenities = [
                            ['icon'=>'📶','label'=>'Free WiFi'],
                            ['icon'=>'❄️','label'=>'Air Conditioning'],
                            ['icon'=>'🍽️','label'=>'Restaurant'],
                            ['icon'=>'🅿️','label'=>'Free Parking'],
                            ['icon'=>'📺','label'=>'Flat-screen TV'],
                            ['icon'=>'🛁','label'=>'Private Bathroom'],
                            ['icon'=>'🧴','label'=>'Toiletries'],
                            ['icon'=>'☎️','label'=>'24-hr Front Desk'],
                            ['icon'=>'🧹','label'=>'Daily Housekeeping'],
                            ['icon'=>'🔒','label'=>'Safe Locker'],
                            ['icon'=>'🚿','label'=>'Hot Water'],
                            ['icon'=>'🚖','label'=>'Airport Transfer'],
                        ];
                    @endphp
                    @foreach($amenities as $a)
                    <div class="amenity-item">
                        <span class="am-icon">{{ $a['icon'] }}</span>
                        <span>{{ $a['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- House Rules ─────────────────────────────────────── --}}
            <div class="hd-section" id="rules">
                <div class="hd-section-title">House Rules</div>
                <div class="rules-grid">
                    <div class="rule-item">
                        <span class="rule-label">Check-in</span>
                        <span class="rule-value">From 12:00 PM</span>
                    </div>
                    <div class="rule-item">
                        <span class="rule-label">Check-out</span>
                        <span class="rule-value">Until 11:00 AM</span>
                    </div>
                    <div class="rule-item">
                        <span class="rule-label">Cancellation</span>
                        <span class="rule-value">Free up to 24 hrs</span>
                    </div>
                    <div class="rule-item">
                        <span class="rule-label">Pets</span>
                        <span class="rule-value">Not allowed</span>
                    </div>
                    <div class="rule-item">
                        <span class="rule-label">Age restriction</span>
                        <span class="rule-value">18+ to book</span>
                    </div>
                    <div class="rule-item">
                        <span class="rule-label">Payment</span>
                        <span class="rule-value">Cash / UPI</span>
                    </div>
                </div>
                <div class="hd-notice">
                    &#9888; Guests are required to show a valid photo ID at check-in. All bookings are subject to availability. Contact us for group bookings or special requests.
                </div>
            </div>

            {{-- Video ───────────────────────────────────────────── --}}
            @if($product->youtube_link)
            <div class="hd-section hd-video" id="video">
                <div class="hd-section-title">Property Video Tour</div>
                <iframe src="{{ $product->youtube_link }}" allowfullscreen loading="lazy"></iframe>
            </div>
            @endif

            {{-- Location Map ─────────────────────────────────────── --}}
            @if($product->map_location)
            <div class="hd-section hd-map" id="location">
                <div class="hd-section-title">Location</div>
                @if(optional($product->subCategory)->name)
                <p class="hd-map-subtitle">
                    &#128205; {{ $product->subCategory->name }}, Varanasi, Uttar Pradesh, India
                </p>
                @endif
                <iframe src="{{ $product->map_location }}" allowfullscreen loading="lazy"></iframe>
            </div>
            @endif

        </div>{{-- /hd-content --}}

        {{-- RIGHT SIDEBAR (desktop) / BOOKING POPUP (mobile) ──── --}}
        {{-- Desktop: overlay is position:static → acts as grid column  --}}
        {{-- Mobile:  overlay is position:fixed  → bottom-sheet popup   --}}
        <div class="hd-popup-overlay" id="hdBookingPopup">
            <div class="hd-popup-sheet">
                <div class="hd-popup-handle">
                    <div class="hd-popup-handle-bar"></div>
                    <button class="hd-popup-close" onclick="hdClosePopup()" aria-label="Close">&#10005;</button>
                </div>
                <div class="hd-popup-body">
        <div class="hd-sidebar">
            <div class="hd-sidebar-inner" id="enquiry">

                {{-- Price Box ─────────────────────────────────── --}}
                @if(($product->discounted_price > 0 || $product->base_price > 0) && !session('hotel_success'))
                <div class="price-box">
                    <div class="price-from">Starting from</div>
                    <div class="price-tag">
                        @if($product->discounted_price > 0)
                            @if($product->base_price > 0 && $product->base_price > $product->discounted_price)
                            <span class="price-original">₹{{ number_format($product->base_price) }}</span>
                            @php $pct = round((($product->base_price - $product->discounted_price) / $product->base_price) * 100); @endphp
                            <span class="discount-pill">-{{ $pct }}%</span>
                            @endif
                            <span class="price-discounted">₹{{ number_format($product->discounted_price) }}</span>
                        @else
                            <span class="price-discounted">₹{{ number_format($product->base_price) }}</span>
                        @endif
                    </div>
                    <div class="price-night">per night · incl. taxes &amp; fees</div>
                    <div class="price-free-cancel">&#10003; Free cancellation available</div>
                </div>
                @endif

                {{-- Enquiry Form Card ─────────────────────────── --}}
                <div class="hotel-card">
                    <div class="hotel-card-header">
                        <p class="hc-sub">&#128203; Reserve Your Stay</p>
                        <p class="hc-title">{{ $product->name }}</p>
                    </div>
                    <div class="hotel-card-body">

                        @if(session('hotel_success'))
                        <div class="hc-success-card">
                            <div class="hc-success-icon">&#10003;</div>
                            <h4 class="hc-success-title">Enquiry Request Sent Successfully!</h4>
                            <p class="hc-success-msg">Our team will contact you soon to confirm your booking.</p>
                            <a href="tel:+917080109919" class="hc-success-call">&#128222; Call Us Now</a>
                        </div>
                        @else

                        @if($errors->any())
                        <div class="hc-errors">
                            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                        @endif

                        <form action="{{ route('hotel-enquiry.store') }}" method="POST" novalidate>
                            @csrf

                            <div class="hc-field">
                                <label class="hc-label">Hotel Name</label>
                                <input type="text" class="hc-input" name="hotel_name" value="{{ $product->name }}" readonly>
                            </div>

                            <div class="hc-field">
                                <label class="hc-label">Your Name *</label>
                                <input type="text" class="hc-input {{ $errors->has('guest_name') ? 'is-invalid' : '' }}"
                                       name="guest_name" placeholder="Full name"
                                       value="{{ old('guest_name') }}" required>
                                @error('guest_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="hc-field">
                                <label class="hc-label">Contact Number *</label>
                                <input type="tel" class="hc-input {{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                                       name="contact_number" placeholder="10-digit mobile"
                                       maxlength="10" value="{{ old('contact_number') }}" required>
                                @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="hc-row2">
                                <div class="hc-field">
                                    <label class="hc-label">Adults <span>(18+ yrs)</span> *</label>
                                    <div class="hc-stepper" id="adultsStepperWrap">
                                        <button type="button" class="hc-stepper-btn" id="adultsMinus" aria-label="Decrease adults">&#8722;</button>
                                        <span class="hc-stepper-val" id="adultsVal">{{ old('adults',1) }}</span>
                                        <button type="button" class="hc-stepper-btn" id="adultsPlus" aria-label="Increase adults">&#43;</button>
                                    </div>
                                    <input type="hidden" name="adults" id="adultsInput" value="{{ old('adults',1) }}">
                                </div>
                                <div class="hc-field">
                                    <label class="hc-label">Children <span>(5+ yrs)</span> *</label>
                                    <div class="hc-stepper" id="kidsStepperWrap">
                                        <button type="button" class="hc-stepper-btn" id="kidsMinus" aria-label="Decrease kids">&#8722;</button>
                                        <span class="hc-stepper-val" id="kidsVal">{{ old('kids',0) }}</span>
                                        <button type="button" class="hc-stepper-btn" id="kidsPlus" aria-label="Increase kids">&#43;</button>
                                    </div>
                                    <input type="hidden" name="kids" id="kidsInput" value="{{ old('kids',0) }}">
                                </div>
                            </div>

                            <div class="hc-field">
                                <label class="hc-label">Check-In <span>(12:00 PM)</span> *</label>
                                <input type="datetime-local" class="hc-input {{ $errors->has('checkin_datetime') ? 'is-invalid' : '' }}"
                                       name="checkin_datetime" id="hd_checkin"
                                       value="{{ old('checkin_datetime') }}"
                                       min="{{ date('Y-m-d') }}T00:00" required>
                                @error('checkin_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="hc-field">
                                <label class="hc-label">Check-Out <span>(11:00 AM)</span> *</label>
                                <input type="datetime-local" class="hc-input {{ $errors->has('checkout_datetime') ? 'is-invalid' : '' }}"
                                       name="checkout_datetime" id="hd_checkout"
                                       value="{{ old('checkout_datetime') }}"
                                       min="{{ date('Y-m-d') }}T00:00" required>
                                @error('checkout_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <input type="hidden" name="nights" id="hd_nights" value="{{ old('nights',1) }}">
                            <div class="nights-display">
                                <span class="nd-label">&#127769; Total Nights</span>
                                <span class="nd-value" id="hd_nights_display">1 Night</span>
                            </div>

                            <button type="submit" class="hc-submit">Reserve Now &rarr;</button>
                        </form>

                        <div class="hc-support">
                            <a href="tel:+917080109919" class="btn-call">&#128222; Call Us</a>
                            <a href="https://wa.me/917080109919" target="_blank" rel="noopener" class="btn-wa">&#128172; WhatsApp</a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Trust badges ─────────────────────────────── --}}
                <div class="hd-trust-box">
                    <div class="hd-trust-heading">Why book with us?</div>
                    @foreach(['No booking fees','Free cancellation','24/7 support','Secure payment'] as $trust)
                    <div class="hd-trust-item">
                        <span class="hd-trust-check">&#10003;</span> {{ $trust }}
                    </div>
                    @endforeach
                </div>

            </div>{{-- /hd-sidebar-inner --}}
        </div>{{-- /hd-sidebar --}}
                </div>{{-- /.hd-popup-body --}}
            </div>{{-- /.hd-popup-sheet --}}
        </div>{{-- /.hd-popup-overlay --}}

    </div>{{-- /hd-body --}}
</div>{{-- /hd-wrap --}}

{{-- ─── Other Hotels ──────────────────────────────────────────── --}}
@if(isset($relatedHotels) && $relatedHotels->count() > 0)
<div class="oh-section">
    <div class="oh-inner">
        <div class="oh-header">
            <h2 class="oh-heading">Other Hotels &amp; Stays in Varanasi</h2>
            <div class="oh-header-actions">
                <a href="{{ route('product.list', ['hotels']) }}" class="oh-see-all" aria-label="See all hotels">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <div class="oh-arrows">
                    <button class="oh-arrow" id="ohPrev" aria-label="Previous">&#8592;</button>
                    <button class="oh-arrow" id="ohNext" aria-label="Next">&#8594;</button>
                </div>
            </div>
        </div>
        <div class="oh-track-wrap">
        <div class="oh-track" id="ohTrack">
            @foreach($relatedHotels as $rh)
            @php
                $rhImgs   = (!empty($rh->images) && is_array($rh->images)) ? $rh->images : [];
                $rhThumb  = count($rhImgs) > 0
                    ? asset('backend/admin/product_images/').'/'.$rhImgs[0]
                    : asset('backend/assets/images/placeholder.jpg');
                $rhUrl    = route('product.detail', [
                    optional($rh->category)->slug ?? 'hotels',
                    optional($rh->subCategory)->slug ?? 'varanasi',
                    $rh->slug
                ]);
                $rhPrice  = ($rh->discounted_price ?? 0) > 0 ? $rh->discounted_price : ($rh->base_price ?? 0);
            @endphp
            <a href="{{ $rhUrl }}" class="oh-card">
                <div class="oh-img">
                    <span class="oh-badge">Guest favourite</span>
                    <button class="oh-heart" onclick="event.preventDefault();" aria-label="Save">
                        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M16 28C16 28 4 20.5 4 12a7 7 0 0 1 12-4.9A7 7 0 0 1 28 12c0 8.5-12 16-12 16z"/></svg>
                    </button>
                    <img loading="lazy" src="{{ $rhThumb }}" alt="{{ $rh->name }}">
                </div>
                <div class="oh-info">
                    <div class="oh-name">{{ optional($rh->category)->name ?? 'Stay' }} in {{ optional($rh->subCategory)->name ?? 'Varanasi' }}</div>
                    <div class="oh-loc">{{ $rh->name }}</div>
                    <div class="oh-price-row">
                        @if($rhPrice > 0)
                        <span class="oh-price">₹{{ number_format($rhPrice) }} <span>/ night</span></span>
                        @endif
                        <span class="oh-rating">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            4.8
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>{{-- /oh-track --}}
        </div>{{-- /oh-track-wrap --}}
    </div>{{-- /oh-inner --}}
</div>{{-- /oh-section --}}
@endif

{{-- Lightbox overlay --}}
<div class="lb-overlay" id="lbOverlay">
    <button class="lb-close" id="lbClose">&#215;</button>
    <button class="lb-prev"  id="lbPrev">&#8249;</button>
    <img loading="lazy" src="" id="lbImg" alt="Gallery">
    <button class="lb-next"  id="lbNext">&#8250;</button>
    <div class="lb-counter" id="lbCounter"></div>
</div>

@endsection

@push('scripts')
<script>
(function () {

    /* ── Date / nights logic ── */
    var ci = document.getElementById('hd_checkin');
    var co = document.getElementById('hd_checkout');
    var ni = document.getElementById('hd_nights');
    var nd = document.getElementById('hd_nights_display');

    function fmtIn(d)  { return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0')+'T12:00'; }
    function fmtOut(d) { return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0')+'T11:00'; }

    var today    = new Date();
    var tomorrow = new Date(today); tomorrow.setDate(today.getDate()+1);

    if (!ci.value) ci.value = fmtIn(today);
    if (!co.value) co.value = fmtOut(tomorrow);

    function calcNights() {
        if (!ci.value || !co.value) return;
        var diff = (new Date(co.value) - new Date(ci.value)) / 86400000;
        var n = Math.max(1, Math.ceil(diff));
        ni.value = n;
        nd.textContent = n + ' Night' + (n !== 1 ? 's' : '');
    }
    ci.addEventListener('change', function () {
        co.min = ci.value;
        if (co.value <= ci.value) { var d = new Date(ci.value); d.setDate(d.getDate()+1); co.value = fmtOut(d); }
        calcNights();
    });
    co.addEventListener('change', calcNights);
    calcNights();

    document.querySelector('input[name="contact_number"]').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g,'').slice(0,10);
    });

    /* ── Lightbox ── */
    var allImgs = [];
    document.querySelectorAll('[data-lb]').forEach(function(el) {
        var src = el.tagName === 'IMG' ? el.src : el.previousElementSibling ? el.previousElementSibling.src : '';
        if (src) allImgs.push(src);
        el.addEventListener('click', function () { openLb(parseInt(el.dataset.lb || 0)); });
    });
    var curIdx = 0;
    var overlay = document.getElementById('lbOverlay');
    var lbImg   = document.getElementById('lbImg');
    var lbCnt   = document.getElementById('lbCounter');

    function openLb(i) {
        curIdx = i; lbImg.src = allImgs[curIdx];
        lbCnt.textContent = (curIdx+1) + ' / ' + allImgs.length;
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeLb() { overlay.classList.remove('open'); document.body.style.overflow = ''; }
    function prevImg() { curIdx = (curIdx - 1 + allImgs.length) % allImgs.length; lbImg.src = allImgs[curIdx]; lbCnt.textContent = (curIdx+1)+' / '+allImgs.length; }
    function nextImg() { curIdx = (curIdx + 1) % allImgs.length; lbImg.src = allImgs[curIdx]; lbCnt.textContent = (curIdx+1)+' / '+allImgs.length; }

    document.getElementById('lbClose').addEventListener('click', closeLb);
    document.getElementById('lbPrev').addEventListener('click', prevImg);
    document.getElementById('lbNext').addEventListener('click', nextImg);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closeLb(); });
    document.addEventListener('keydown', function(e) {
        if (!overlay.classList.contains('open')) return;
        if (e.key === 'Escape')     closeLb();
        if (e.key === 'ArrowLeft')  prevImg();
        if (e.key === 'ArrowRight') nextImg();
    });

    /* ── Sticky nav active highlight on scroll ── */
    var sections = document.querySelectorAll('[id]');
    var navLinks = document.querySelectorAll('.hd-nav a');
    window.addEventListener('scroll', function () {
        var scrollY = window.scrollY + 80;
        sections.forEach(function (s) {
            if (s.offsetTop <= scrollY && s.offsetTop + s.offsetHeight > scrollY) {
                navLinks.forEach(function(a) { a.classList.remove('active'); });
                var active = document.querySelector('.hd-nav a[href="#' + s.id + '"]');
                if (active) active.classList.add('active');
            }
        });
    });

    /* ── Other Hotels scroller ── */
    (function () {
        var track = document.getElementById('ohTrack');
        var prev  = document.getElementById('ohPrev');
        var next  = document.getElementById('ohNext');
        if (!track || !prev || !next) return;

        var cardW = function () {
            var c = track.querySelector('.oh-card');
            return c ? c.offsetWidth + 16 : 240;
        };
        var scrollBy = function (dir) { track.scrollBy({ left: dir * cardW() * 2, behavior: 'smooth' }); };

        prev.addEventListener('click', function () { scrollBy(-1); });
        next.addEventListener('click', function () { scrollBy(1); });

        function updateArrows() {
            prev.disabled = track.scrollLeft < 8;
            next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 8;
        }
        track.addEventListener('scroll', updateArrows);
        updateArrows();
    })();

    /* ── Guest steppers (Adults / Kids) ── */
    function initStepper(minusId, plusId, valId, inputId, min, max) {
        var minusBtn = document.getElementById(minusId);
        var plusBtn  = document.getElementById(plusId);
        var valEl    = document.getElementById(valId);
        var inputEl  = document.getElementById(inputId);
        var current  = parseInt(inputEl.value) || min;

        function render() {
            valEl.textContent = current;
            inputEl.value = current;
            minusBtn.disabled = current <= min;
            plusBtn.disabled  = current >= max;
        }
        minusBtn.addEventListener('click', function () {
            if (current > min) { current--; render(); }
        });
        plusBtn.addEventListener('click', function () {
            if (current < max) { current++; render(); }
        });
        render();
    }
    initStepper('adultsMinus','adultsPlus','adultsVal','adultsInput', 1, 20);
    initStepper('kidsMinus',  'kidsPlus',  'kidsVal',  'kidsInput',  0, 10);

    /* ── Smooth scroll for nav links ── */
    navLinks.forEach(function(a) {
        a.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                window.scrollTo({ top: target.offsetTop - 60, behavior: 'smooth' });
            }
        });
    });

})();
</script>

<script>
/* ── Hotel Booking Popup (mobile) ── */
function hdOpenPopup() {
    var popup = document.getElementById('hdBookingPopup');
    if (popup) { popup.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function hdClosePopup() {
    var popup = document.getElementById('hdBookingPopup');
    if (popup) { popup.classList.remove('open'); document.body.style.overflow = ''; }
}
document.getElementById('hdBookingPopup').addEventListener('click', function(e) {
    if (e.target === this) hdClosePopup();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') hdClosePopup();
});
/* Auto-open popup on mobile after successful enquiry submission */
@if(session('hotel_success'))
if (window.innerWidth < 992) { hdOpenPopup(); }
@endif
</script>

{{-- ── Hotel Sticky Mobile Bar ── --}}
<div class="hkbd-sticky-bar" id="hkbdStickyBar">
    <div class="hkbd-sticky-info">
        <div class="hkbd-sticky-name">{{ Str::limit($product->name, 26) }}</div>
        @php
            $stickyPrice = $product->discounted_price > 0 ? $product->discounted_price : $product->base_price;
        @endphp
        @if($stickyPrice > 0)
        <div class="hkbd-sticky-price">From ₹{{ number_format($stickyPrice) }}/night</div>
        @endif
    </div>
    <div class="hkbd-sticky-btns">
        <button class="hkbd-sticky-enq" onclick="hdOpenPopup()">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Enquiry Now
        </button>
        <a href="https://wa.me/917080109919?text={{ urlencode('Hi VisitKashi, I want to enquire about hotel '.$product->name.'. Please share availability and pricing.') }}"
           target="_blank" rel="noopener" class="hkbd-sticky-wa">
            <svg width="20" height="20" viewBox="0 0 32 32" fill="currentColor"><path d="M16 3C8.82 3 3 8.82 3 16c0 2.43.65 4.7 1.78 6.67L3 29l6.55-1.72A13 13 0 0 0 16 29c7.18 0 13-5.82 13-13S23.18 3 16 3zm6.4 17.72c-.27.76-1.58 1.45-2.16 1.54-.56.09-1.26.13-2.04-.13a18.7 18.7 0 0 1-1.85-.68C13.6 20.3 11.6 17.9 11.45 17.7c-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.2 1.05-2.5.27-.3.6-.37.8-.37l.57.01c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.14.17-.3.38-.43.51-.14.14-.29.3-.12.58.17.28.74 1.22 1.59 1.97 1.09.97 2 1.27 2.29 1.41.28.14.45.12.61-.07.17-.2.72-.84.91-1.13.2-.28.39-.23.66-.14.27.09 1.71.8 2 .95.29.14.48.21.55.33.07.12.07.7-.2 1.46z"/></svg>
        </a>
    </div>
</div>

<style>
.hkbd-sticky-bar { display:none; }
@media(max-width:991px){
    .hkbd-sticky-bar {
        display:flex; align-items:center; justify-content:space-between; gap:10px;
        position:fixed; bottom:0; left:0; right:0; z-index:1000;
        background:#fff; border-top:1px solid #e5e7eb;
        padding:12px 16px; box-shadow:0 -4px 16px rgba(0,0,0,.08);
        margin-bottom:65px;
    }
    .hkbd-sticky-info { flex:1; min-width:0; overflow:hidden; }
    .hkbd-sticky-name { font-size:.85rem; font-weight:800; color:#111; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .hkbd-sticky-price { font-size:.8rem; color:#0f3460; font-weight:600; white-space:nowrap; }
    .hkbd-sticky-btns { display:flex; gap:8px; align-items:center; flex-shrink:0; }
    .hkbd-sticky-enq {
        display:inline-flex; align-items:center; gap:6px;
        background:#0f3460; color:#fff;
        font-size:.85rem; font-weight:700; line-height:1.2;
        padding:12px 16px; border-radius:9px; border:none; cursor:pointer;
        white-space:nowrap; flex-shrink:0; transition:opacity .2s;
    }
    .hkbd-sticky-enq:hover { opacity:.88; }
    .hkbd-sticky-wa {
        display:inline-flex; align-items:center; justify-content:center;
        background:#25d366; color:#fff!important;
        padding:12px 14px; border-radius:9px;
        text-decoration:none!important; flex-shrink:0; transition:opacity .2s;
    }
    .hkbd-sticky-wa:hover { opacity:.88; color:#fff!important; }
}
</style>
@endpush
