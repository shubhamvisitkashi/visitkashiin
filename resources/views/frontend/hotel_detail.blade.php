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
<link rel="preload" href="{{ asset('frontend/css/hotel-detail.css') }}?v={{ filemtime(public_path('frontend/css/hotel-detail.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/hotel-detail.css') }}?v={{ filemtime(public_path('frontend/css/hotel-detail.css')) }}"></noscript>
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
        <a href="#enquiry">Book Now</a>
    </div>
</div>

{{-- ─── Main Wrapper ─────────────────────────────────────────── --}}
<div class="hd-wrap">

    {{-- ─── Hotel Header ──────────────────────────────────────── --}}
    <div class="hd-header" id="overview">
        <div class="hd-header-top">
            <div style="flex:1;min-width:0;">
                <div class="hd-type-badge">
                    &#127968;
                    {{ optional($product->category)->name ?? 'Hotel' }}
                    @if(optional($product->subCategory)->name)
                        &bull; {{ $product->subCategory->name }}
                    @endif
                </div>
                <h1 class="hd-name">{{ $product->name }}</h1>
                <div class="hd-stars">
                    &#9733;&#9733;&#9733;&#9733;<span style="color:#ccc;">&#9733;</span>
                    <span style="font-size:.78rem;color:#555;font-weight:600;margin-left:4px;">4-Star Hotel</span>
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
                        <img loading="lazy" src="{{ $imgBase.'/'.$img }}" alt="{{ $product->name }}" class="d-block w-100" style="height:260px;object-fit:cover;">
                    </div>
                    @empty
                    <div class="carousel-item active">
                        <img loading="lazy" src="{{ $placeholder }}" alt="No image" class="d-block w-100" style="height:260px;object-fit:cover;">
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
                <div style="margin-top:14px;padding:12px 16px;background:#fff8e1;border-radius:8px;border-left:3px solid #f5a623;font-size:.83rem;color:#5a3e00;">
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
                <p style="font-size:.85rem;color:#555;margin-bottom:12px;">
                    &#128205; {{ $product->subCategory->name }}, Varanasi, Uttar Pradesh, India
                </p>
                @endif
                <iframe src="{{ $product->map_location }}" allowfullscreen loading="lazy"></iframe>
            </div>
            @endif

        </div>{{-- /hd-content --}}

        {{-- RIGHT SIDEBAR ─────────────────────────────────────── --}}
        <div class="hd-sidebar">
            <div class="hd-sidebar-inner" id="enquiry">

                {{-- Price Box ─────────────────────────────────── --}}
                @if($product->discounted_price > 0 || $product->base_price > 0)
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
                        <div class="hc-success">&#10003; {{ session('hotel_success') }}</div>
                        @endif

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
                                    <label class="hc-label">Adults <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#888;">(18+ yrs)</span> *</label>
                                    <div class="hc-stepper" id="adultsStepperWrap">
                                        <button type="button" class="hc-stepper-btn" id="adultsMinus" aria-label="Decrease adults">&#8722;</button>
                                        <span class="hc-stepper-val" id="adultsVal">{{ old('adults',1) }}</span>
                                        <button type="button" class="hc-stepper-btn" id="adultsPlus" aria-label="Increase adults">&#43;</button>
                                    </div>
                                    <input type="hidden" name="adults" id="adultsInput" value="{{ old('adults',1) }}">
                                </div>
                                <div class="hc-field">
                                    <label class="hc-label">Children <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#888;">(5+ yrs)</span> *</label>
                                    <div class="hc-stepper" id="kidsStepperWrap">
                                        <button type="button" class="hc-stepper-btn" id="kidsMinus" aria-label="Decrease kids">&#8722;</button>
                                        <span class="hc-stepper-val" id="kidsVal">{{ old('kids',0) }}</span>
                                        <button type="button" class="hc-stepper-btn" id="kidsPlus" aria-label="Increase kids">&#43;</button>
                                    </div>
                                    <input type="hidden" name="kids" id="kidsInput" value="{{ old('kids',0) }}">
                                </div>
                            </div>

                            <div class="hc-field">
                                <label class="hc-label">Check-In <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#888;">(12:00 PM)</span> *</label>
                                <input type="datetime-local" class="hc-input {{ $errors->has('checkin_datetime') ? 'is-invalid' : '' }}"
                                       name="checkin_datetime" id="hd_checkin"
                                       value="{{ old('checkin_datetime') }}"
                                       min="{{ date('Y-m-d') }}T00:00" required>
                                @error('checkin_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="hc-field">
                                <label class="hc-label">Check-Out <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#888;">(11:00 AM)</span> *</label>
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
                    </div>
                </div>

                {{-- Trust badges ─────────────────────────────── --}}
                <div style="background:#fff;border-radius:10px;padding:14px 18px;margin-top:12px;box-shadow:0 1px 4px rgba(0,0,0,.07);">
                    <div style="font-size:.75rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;margin-bottom:10px;">Why book with us?</div>
                    @foreach(['No booking fees','Free cancellation','24/7 support','Secure payment'] as $trust)
                    <div style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:#222;margin-bottom:6px;">
                        <span style="color:#008009;font-weight:700;">&#10003;</span> {{ $trust }}
                    </div>
                    @endforeach
                </div>

            </div>{{-- /hd-sidebar-inner --}}
        </div>{{-- /hd-sidebar --}}

    </div>{{-- /hd-body --}}
</div>{{-- /hd-wrap --}}

{{-- ─── Other Hotels ──────────────────────────────────────────── --}}
@if(isset($relatedHotels) && $relatedHotels->count() > 0)
<div class="oh-section">
    <div class="oh-inner">
        <div class="oh-header">
            <h2 class="oh-heading">Other Hotels &amp; Stays in Varanasi</h2>
            <div class="oh-arrows">
                <button class="oh-arrow" id="ohPrev" aria-label="Previous">&#8592;</button>
                <button class="oh-arrow" id="ohNext" aria-label="Next">&#8594;</button>
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
            @endphp
            <a href="{{ $rhUrl }}" class="oh-card">
                <div class="oh-img">
                    <img loading="lazy" src="{{ $rhThumb }}" alt="{{ $rh->name }}" loading="lazy">
                </div>
                <div class="oh-info">
                    <div class="oh-name">{{ $rh->name }}</div>
                    @if(optional($rh->subCategory)->name)
                    <div class="oh-loc">&#128205; {{ $rh->subCategory->name }}</div>
                    @endif
                    @if(($rh->discounted_price ?? 0) > 0)
                    <div class="oh-price">₹{{ number_format($rh->discounted_price) }} <span>/ night</span></div>
                    @elseif(($rh->base_price ?? 0) > 0)
                    <div class="oh-price">₹{{ number_format($rh->base_price) }} <span>/ night</span></div>
                    @endif
                    <span class="oh-book-btn">View Details &rarr;</span>
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
@endpush
