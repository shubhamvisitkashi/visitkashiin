@extends('frontend.layouts.app')

@section('meta')
    @isset($product)
        @php $pkgImg = !empty($product->images) ? asset('backend/admin/product_images/'.$product->images[0]) : asset('frontend/images/logo1.png'); @endphp
        <link rel="canonical" href="{{ url()->current() }}">
        <title>{{ optional($product)->meta_title ?? $product->name }} | Tour Package Varanasi - {{ env('APP_DOMAIN') }}</title>
        <meta name="description" content="{{ optional($product)->meta_description }}">
        <meta name="keywords"    content="{{ optional($product)->meta_keyword }}">
        <meta name="robots" content="index, follow">
        <meta property="og:type" content="product" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:title"       content="{{ optional($product)->meta_title ?? $product->name }}">
        <meta property="og:description" content="{{ optional($product)->meta_description }}">
        <meta property="og:image"       content="{{ $pkgImg }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ optional($product)->meta_title ?? $product->name }}">
        <meta name="twitter:description" content="{{ optional($product)->meta_description }}">
        <meta name="twitter:image" content="{{ $pkgImg }}">
    @endisset
@endsection

@push('styles')
<link rel="preload" href="{{ asset('frontend/css/package-detail.min.css') }}?v={{ filemtime(public_path('frontend/css/package-detail.min.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/package-detail.min.css') }}?v={{ filemtime(public_path('frontend/css/package-detail.min.css')) }}"></noscript>
@endpush

@section('content')
@php
    $images = (!empty($product->images) && is_array($product->images)) ? $product->images : [];
    $imgCount = count($images);
    $basePrice = $product->base_price ?? 0;
    $discPrice  = $product->discounted_price ?? 0;
    $displayPrice = $discPrice > 0 ? $discPrice : $basePrice;
    $catSlug = optional($product->category)->slug ?? '';
    $subCatSlug = optional($product->subCategory)->slug ?? '';
    $waNumber = '917080109919';
@endphp

<div class="pkd-wrap">

    {{-- ── Breadcrumb ────────────────────────────────────────── --}}
    <nav class="pkd-bc">
        <div class="pkd-bc-inner">
            <a href="{{ url('/') }}">Home</a>
            <span class="pkd-bc-sep">›</span>
            @if($product->category)
                <a href="{{ route('product.list', $catSlug) }}">{{ $product->category->name }}</a>
                <span class="pkd-bc-sep">›</span>
            @endif
            @if($product->subCategory)
                <a href="{{ route('product.sub.list', [$catSlug, $subCatSlug]) }}">{{ $product->subCategory->name }}</a>
                <span class="pkd-bc-sep">›</span>
            @endif
            <span>{{ $product->name }}</span>
        </div>
    </nav>

    {{-- ── Title row ─────────────────────────────────────────── --}}
    <div class="pkd-hero">
        <h1 class="pkd-title">{{ $product->name }}</h1>
        <div class="pkd-meta">
            <span class="pkd-stars">★★★★★</span>
            <span class="pkd-rating-num">5.0</span>
            <span class="pkd-reviews">318 Reviews</span>
            @if($product->category)
                <a href="{{ route('product.list', $catSlug) }}" class="pkd-cat-tag">{{ strtoupper($product->category->name) }}</a>
            @endif
            @if($product->address)
                <span class="pkd-location">📍 {{ $product->address }}</span>
            @else
                <span class="pkd-location">📍 Varanasi, Uttar Pradesh</span>
            @endif
        </div>
    </div>

    {{-- ── Gallery ───────────────────────────────────────────── --}}
    <div class="pkd-gallery">
        <div class="pkd-gallery-grid">
            {{-- Hero image --}}
            <div class="pg-main">
                @if($imgCount > 0)
                    <img src="{{ asset('backend/admin/product_images/'.$images[0]) }}" alt="{{ $product->name }}" data-lb="0">
                @endif
            </div>
            {{-- Side images --}}
            @foreach(array_slice($images, 1, 4) as $i => $img)
                <div class="pg-side">
                    <img src="{{ asset('backend/admin/product_images/'.$img) }}" alt="{{ $product->name }} {{ $i+2 }}" data-lb="{{ $i+1 }}">
                </div>
            @endforeach
            @if($imgCount > 5)
                <button class="pg-show-all" onclick="pkdLbOpen(0)">🖼️ Show all {{ $imgCount }} photos</button>
            @endif
        </div>
    </div>

    {{-- ── Lightbox ──────────────────────────────────────────── --}}
    <div class="pkd-lb" id="pkdLb" role="dialog" aria-modal="true">
        <button class="pkd-lb-close" onclick="pkdLbClose()">✕</button>
        <button class="pkd-lb-arrow prev" id="pkdLbPrev" onclick="pkdLbNav(-1)">‹</button>
        <img class="pkd-lb-img" id="pkdLbImg" src="" alt="">
        <button class="pkd-lb-arrow next" id="pkdLbNext" onclick="pkdLbNav(1)">›</button>
        <span class="pkd-lb-counter" id="pkdLbCounter"></span>
    </div>

    {{-- ── Body ─────────────────────────────────────────────── --}}
    <div class="pkd-body">

        {{-- ══ Main content ════════════════════════════════════ --}}
        <main>

            {{-- Provider bar --}}
            <div class="pkd-provider">
                <div class="pkd-provider-logo">
                    <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                         alt="Visit Kashi">
                </div>
                <div>
                    <div class="pkd-provider-name">VisitKashi</div>
                    <div class="pkd-provider-tag">Verified Travel Partner · Varanasi</div>
                </div>
                <span class="pkd-provider-badge">✓ VERIFIED</span>
            </div>

            {{-- Highlights --}}
            <div class="pkd-highlights">
                <div class="pkd-hl">
                    <div class="pkd-hl-icon">🏨</div>
                    <div class="pkd-hl-label">Hotel</div>
                    <div class="pkd-hl-val">Multiple options</div>
                </div>
                <div class="pkd-hl">
                    <div class="pkd-hl-icon">🚖</div>
                    <div class="pkd-hl-label">Cab</div>
                    <div class="pkd-hl-val">AC cab included</div>
                </div>
                <div class="pkd-hl">
                    <div class="pkd-hl-icon">⛵</div>
                    <div class="pkd-hl-label">Boat Ride</div>
                    <div class="pkd-hl-val">Ganga Aarti</div>
                </div>
                <div class="pkd-hl">
                    <div class="pkd-hl-icon">💬</div>
                    <div class="pkd-hl-label">24/7 Support</div>
                    <div class="pkd-hl-val">WhatsApp help</div>
                </div>
            </div>

            {{-- Description --}}
            <div class="pkd-section-sm">
                <h2 class="pkd-section-title">📋 About This Package</h2>
                <div class="pkd-desc">
                    {!! safe_html($product->description) !!}
                </div>
            </div>

            {{-- Package Details --}}
            <div class="pkd-section">
                <h2 class="pkd-section-title">📊 Package Details</h2>
                <table class="pkd-specs">
                    <tr><td>Duration</td><td>{{ $product->duration ?: '1 Night / 2 Days' }}</td></tr>
                    <tr><td>Destination</td><td>{{ $product->address ?: 'Varanasi, Uttar Pradesh' }}</td></tr>
                    @if($product->max_capacity)
                    <tr><td>Group Size</td><td>Up to {{ $product->max_capacity }} Persons</td></tr>
                    @endif
                    <tr><td>Starting Price</td><td>
                        @if($discPrice > 0)
                            <del class="pkd-price-old">₹{{ number_format($basePrice) }}</del>
                            &nbsp;<strong class="pkd-price-new">₹{{ number_format($discPrice) }}</strong> per person
                        @else
                            <strong>₹{{ number_format($basePrice) }}</strong> per person
                        @endif
                    </td></tr>
                    <tr><td>Pickup Point</td><td>Varanasi (Hotel / Airport / Railway Station)</td></tr>
                    <tr><td>Language</td><td>Hindi, English</td></tr>
                    <tr><td>Cancellation</td><td>Free cancellation up to 24 hours before</td></tr>
                </table>
            </div>

            {{-- What's Included / Excluded --}}
            <div class="pkd-section">
                <h2 class="pkd-section-title">✅ Inclusions &amp; Exclusions</h2>
                <div class="pkd-ie-grid">
                    <div class="pkd-ie-box inc">
                        <h4>✅ What's Included</h4>
                        <ul class="pkd-ie-list">
                            <li><span class="ie-dot">✓</span> Hotel accommodation (as per chosen category)</li>
                            <li><span class="ie-dot">✓</span> AC cab for local sightseeing</li>
                            <li><span class="ie-dot">✓</span> Morning/Evening boat ride on Ganga</li>
                            <li><span class="ie-dot">✓</span> Ganga Aarti at Dashashwamedh Ghat</li>
                            <li><span class="ie-dot">✓</span> All transfers (pickup &amp; drop)</li>
                            <li><span class="ie-dot">✓</span> Local guide assistance</li>
                            <li><span class="ie-dot">✓</span> 24/7 WhatsApp support</li>
                        </ul>
                    </div>
                    <div class="pkd-ie-box exc">
                        <h4>❌ What's NOT Included</h4>
                        <ul class="pkd-ie-list">
                            <li><span class="ie-dot">✗</span> Airfare / train tickets</li>
                            <li><span class="ie-dot">✗</span> Personal expenses &amp; tips</li>
                            <li><span class="ie-dot">✗</span> Entry fees to monuments</li>
                            <li><span class="ie-dot">✗</span> Meals (unless chosen in Food Plan)</li>
                            <li><span class="ie-dot">✗</span> Travel insurance</li>
                            <li><span class="ie-dot">✗</span> Anything not mentioned above</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Day-by-Day Itinerary --}}
            <div class="pkd-itinerary">
                <h2 class="pkd-section-title">🗓️ Day-by-Day Itinerary</h2>

                <div class="pkd-day">
                    <div class="pkd-day-header" onclick="toggleDay(this)">
                        <span class="pkd-day-badge">DAY 01</span>
                        <span class="pkd-day-name">Arrival in Varanasi · Ganga Aarti &amp; Ghat Walk</span>
                        <span class="pkd-day-arrow">▼</span>
                    </div>
                    <div class="pkd-day-body open">
                        <ul class="pkd-day-timeline">
                            <li>
                                <span class="pkd-tl-dot">1</span>
                                <div class="pkd-tl-content">
                                    <div class="pkd-tl-time">Morning / Afternoon</div>
                                    <div class="pkd-tl-desc">Arrive at Varanasi (airport / railway station). Our representative will receive you and transfer to your hotel. Check-in, freshen up and relax.</div>
                                </div>
                            </li>
                            <li>
                                <span class="pkd-tl-dot">2</span>
                                <div class="pkd-tl-content">
                                    <div class="pkd-tl-time">Evening 5:30 PM</div>
                                    <div class="pkd-tl-desc">Head to Dashashwamedh Ghat for the spectacular <strong>Ganga Aarti</strong>. Watch the rhythmic fire ceremony on the banks of the holy Ganga.</div>
                                </div>
                            </li>
                            <li>
                                <span class="pkd-tl-dot">3</span>
                                <div class="pkd-tl-content">
                                    <div class="pkd-tl-time">Evening</div>
                                    <div class="pkd-tl-desc">Stroll through the narrow lanes of Kashi. Experience local street food — Banarasi Paan, Kachori Sabji, Chaat. Overnight stay at hotel.</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pkd-day">
                    <div class="pkd-day-header" onclick="toggleDay(this)">
                        <span class="pkd-day-badge">DAY 02</span>
                        <span class="pkd-day-name">Morning Boat Ride · Temple Tour · Departure</span>
                        <span class="pkd-day-arrow">▼</span>
                    </div>
                    <div class="pkd-day-body">
                        <ul class="pkd-day-timeline">
                            <li>
                                <span class="pkd-tl-dot">1</span>
                                <div class="pkd-tl-content">
                                    <div class="pkd-tl-time">Early Morning 5:00 AM</div>
                                    <div class="pkd-tl-desc">Wake up for the magical <strong>sunrise boat ride</strong> on the Ganga. Watch the city come alive — morning rituals, yoga, prayers and bathing pilgrims on the ghats.</div>
                                </div>
                            </li>
                            <li>
                                <span class="pkd-tl-dot">2</span>
                                <div class="pkd-tl-content">
                                    <div class="pkd-tl-time">Morning 9:00 AM</div>
                                    <div class="pkd-tl-desc">Return to hotel for breakfast. Then visit <strong>Kashi Vishwanath Temple</strong>, Sankat Mochan, Tulsi Manas Temple and Durga Mandir by AC cab.</div>
                                </div>
                            </li>
                            <li>
                                <span class="pkd-tl-dot">3</span>
                                <div class="pkd-tl-content">
                                    <div class="pkd-tl-time">Afternoon</div>
                                    <div class="pkd-tl-desc">Check-out from hotel. Sarnath excursion (optional) and transfer to airport / railway station for onward journey. Tour ends.</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Places Covered --}}
            <div class="pkd-section">
                <h2 class="pkd-section-title">📍 Places Covered</h2>
                <div class="pkd-places">
                    <span class="pkd-place-tag">Dashashwamedh Ghat</span>
                    <span class="pkd-place-tag">Kashi Vishwanath Temple</span>
                    <span class="pkd-place-tag">Manikarnika Ghat</span>
                    <span class="pkd-place-tag">Assi Ghat</span>
                    <span class="pkd-place-tag">Sankat Mochan Temple</span>
                    <span class="pkd-place-tag">Tulsi Manas Temple</span>
                    <span class="pkd-place-tag">Durga Mandir</span>
                    <span class="pkd-place-tag">Sarnath</span>
                    <span class="pkd-place-tag">Banaras Hindu University</span>
                    <span class="pkd-place-tag">Ganga Aarti</span>
                </div>
            </div>

            {{-- YouTube --}}
            @if($product->youtube_link)
            <div class="pkd-section">
                <h2 class="pkd-section-title">🎬 Watch Highlights</h2>
                <div class="pkd-video-wrap">
                    <iframe src="{{ $product->youtube_link }}" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            @endif

            {{-- Map --}}
            @if($product->map_location)
            <div class="pkd-section">
                <h2 class="pkd-section-title">🗺️ Location</h2>
                <div class="pkd-map-wrap">
                    <iframe src="{{ $product->map_location }}" width="100%" height="280" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            @endif

            {{-- FAQ --}}
            <div class="pkd-faq">
                <h2 class="pkd-section-title">❓ Frequently Asked Questions</h2>

                <div class="pkd-faq-item">
                    <div class="pkd-faq-q" onclick="toggleFaq(this)">
                        What is included in this Varanasi tour package?
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="pkd-faq-a open">This package includes hotel accommodation, AC cab for local sightseeing and transfers, morning boat ride on the Ganga, Ganga Aarti experience, and 24/7 WhatsApp support. Meals are available as an add-on based on your chosen food plan.</div>
                </div>

                <div class="pkd-faq-item">
                    <div class="pkd-faq-q" onclick="toggleFaq(this)">
                        Can I customise the number of nights?
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="pkd-faq-a">Yes! Use the "No. of Days" selector in the enquiry form to choose anywhere from 1D/0N to 10D/9N. Our team will customise the itinerary accordingly.</div>
                </div>

                <div class="pkd-faq-item">
                    <div class="pkd-faq-q" onclick="toggleFaq(this)">
                        What hotel categories are available?
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="pkd-faq-a">We offer Homestay, Budget 2★, 3 Star, 4 Star, and 5 Star hotel options. The package price varies based on the category selected. Contact us for exact pricing for each category.</div>
                </div>

                <div class="pkd-faq-item">
                    <div class="pkd-faq-q" onclick="toggleFaq(this)">
                        Is Sarnath covered in this package?
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="pkd-faq-a">Sarnath can be added as an optional excursion. It is approximately 12 km from Varanasi and can be visited on Day 2 before departure. Please mention it in your notes while booking.</div>
                </div>

                <div class="pkd-faq-item">
                    <div class="pkd-faq-q" onclick="toggleFaq(this)">
                        What is the cancellation policy?
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="pkd-faq-a">Free cancellation up to 24 hours before the tour start date. Cancellations within 24 hours may be subject to a nominal fee. For full policy details, please WhatsApp us.</div>
                </div>

                <div class="pkd-faq-item">
                    <div class="pkd-faq-q" onclick="toggleFaq(this)">
                        How do I confirm my booking?
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="pkd-faq-a">Fill in the enquiry form and click "Submit Enquiry". Our team will respond within minutes with availability, pricing and booking confirmation. No advance payment required to hold a tentative booking.</div>
                </div>
            </div>

        </main>

        {{-- ══ Sidebar / Enquiry Form ══════════════════════════ --}}
        {{-- Desktop: overlay is position:static → acts as grid column  --}}
        {{-- Mobile:  overlay is position:fixed  → bottom-sheet popup   --}}
        <div class="pkg-popup-overlay" id="pkgBookingPopup">
            <div class="pkg-popup-sheet">
                <div class="pkg-popup-handle">
                    <div class="pkg-popup-handle-bar"></div>
                    <button class="pkg-popup-close" onclick="pkgClosePopup()" aria-label="Close">&#10005;</button>
                </div>
                <div class="pkg-popup-body">
        <aside>
            <div class="vk-pkg-card">

                {{-- Header --}}
                <div class="vpc-header">
                    <p class="vpc-tagline">📦 Tour Package Enquiry</p>
                    <p class="vpc-title">{{ $product->name }}</p>
                    <div class="vpc-price-row">
                        @if($discPrice > 0 && $basePrice > $discPrice)
                            <span class="vpc-price-old">₹{{ number_format($basePrice) }}</span>
                        @endif
                        <span class="vpc-price-new">₹{{ number_format($displayPrice) }}</span>
                        <span class="vpc-price-note">per person<br>onwards</span>
                    </div>
                    <div class="vpc-badges">
                        <span class="vpc-badge">👥 1000+ Travelers</span>
                        <span class="vpc-badge">💰 Best Price</span>
                        <span class="vpc-badge">🕐 24/7 Support</span>
                    </div>
                </div>

                <div class="vpc-body">
                    <style>
                    .vpc-success{text-align:center;padding:28px 20px;}
                    .vpc-success-icon{width:56px;height:56px;border-radius:50%;background:#DCFCE7;color:#16A34A;font-size:1.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
                    .vpc-success h4{font-size:1rem;font-weight:800;color:#111;margin:0 0 7px;}
                    .vpc-success p{font-size:.83rem;color:#4B5563;margin:0 0 14px;line-height:1.6;}
                    .vpc-success-call{display:inline-flex;align-items:center;gap:6px;background:#0f3460;color:#fff;font-size:.82rem;font-weight:700;padding:9px 20px;border-radius:8px;text-decoration:none;}
                    .vpc-errors{background:#fef2f2;border:1.5px solid #fca5a5;border-radius:9px;padding:11px 14px;margin-bottom:13px;font-size:.79rem;color:#991b1b;}
                    .vpc-errors ul{margin:4px 0 0;padding-left:16px;}
                    </style>

                    @if(session('success'))
                    <div class="vpc-success">
                        <div class="vpc-success-icon">✓</div>
                        <h4>Enquiry Sent Successfully!</h4>
                        <p>Our team will contact you on WhatsApp within 15 minutes to confirm your booking.</p>
                        <a href="tel:+91{{ $waNumber }}" class="vpc-success-call">📞 Call us now</a>
                    </div>
                    @else

                    @if($errors->any())
                    <div class="vpc-errors">
                        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form action="{{ route('enquiry.store') }}" method="POST" id="pkgEnqForm" novalidate>
                        @csrf
                        <input type="hidden" name="package_id"   value="{{ $product->id }}">
                        <input type="hidden" name="package_name" value="{{ $product->name }}">
                        <input type="hidden" name="no_of_person"   id="pkg_persons_hidden" value="2">
                        <input type="hidden" name="children_count" id="pkg_children_hidden" value="0">
                        <input type="hidden" name="message"        id="pkg_message_hidden">

                        {{-- Guest Info --}}
                        <div class="vpc-section">
                            <div class="vpc-section-label">Guest Info</div>
                            <div class="vpc-row2">
                                <div class="vpc-field">
                                    <label class="vpc-label">Full Name *</label>
                                    <input type="text" class="vpc-input" id="pkg_name" name="name" placeholder="Your name" required>
                                </div>
                                <div class="vpc-field">
                                    <label class="vpc-label">Phone *</label>
                                    <input type="tel" class="vpc-input" id="pkg_phone" name="phone" placeholder="10-digit" maxlength="10" required>
                                </div>
                            </div>
                        </div>

                        {{-- Travellers --}}
                        <div class="vpc-section">
                            <div class="vpc-section-label">Travellers</div>
                            <div class="vpc-stepper-wrap">
                                {{-- Adults --}}
                                <div class="vpc-stepper-item">
                                    <button type="button" class="vpc-stp-btn" id="pAdultMinus" aria-label="Decrease adults">−</button>
                                    <span class="vpc-stp-val" id="pAdultVal">2</span>
                                    <button type="button" class="vpc-stp-btn" id="pAdultPlus"  aria-label="Increase adults">+</button>
                                </div>
                                {{-- Children --}}
                                <div class="vpc-stepper-item">
                                    <button type="button" class="vpc-stp-btn" id="pChildMinus" aria-label="Decrease children">−</button>
                                    <span class="vpc-stp-val" id="pChildVal">0</span>
                                    <button type="button" class="vpc-stp-btn" id="pChildPlus"  aria-label="Increase children">+</button>
                                </div>
                                {{-- Infants --}}
                                <div class="vpc-stepper-item">
                                    <button type="button" class="vpc-stp-btn" id="pInfantMinus" aria-label="Decrease infants">−</button>
                                    <span class="vpc-stp-val" id="pInfantVal">0</span>
                                    <button type="button" class="vpc-stp-btn" id="pInfantPlus"  aria-label="Increase infants">+</button>
                                </div>
                            </div>
                            <div class="vpc-stepper-labels">
                                <span>Adults (12+)</span>
                                <span>Children (2-12)</span>
                                <span>Infants (&lt;2)</span>
                            </div>
                        </div>

                        {{-- Travel Details --}}
                        <div class="vpc-section">
                            <div class="vpc-section-label">Travel Details</div>
                            <div class="vpc-row2">
                                <div class="vpc-field">
                                    <label class="vpc-label">Start Date *</label>
                                    <input type="date" class="vpc-input" id="pkg_date" name="arrival_date" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="vpc-field">
                                    <label class="vpc-label">No. of Days</label>
                                    <select class="vpc-input" id="pkg_days">
                                        <option value="1D/0N">1D / 0N</option>
                                        <option value="2D/1N" selected>2D / 1N</option>
                                        <option value="3D/2N">3D / 2N</option>
                                        <option value="4D/3N">4D / 3N</option>
                                        <option value="5D/4N">5D / 4N</option>
                                        <option value="6D/5N">6D / 5N</option>
                                        <option value="7D/6N">7D / 6N</option>
                                        <option value="10D/9N">10D / 9N</option>
                                    </select>
                                </div>
                            </div>
                            <div class="vpc-row2">
                                <div class="vpc-field">
                                    <label class="vpc-label">Pickup Point</label>
                                    <input type="text" class="vpc-input" id="pkg_pickup" placeholder="Airport / Station / Hotel">
                                </div>
                                <div class="vpc-field">
                                    <label class="vpc-label">Drop Point</label>
                                    <input type="text" class="vpc-input" id="pkg_drop" placeholder="Same as pickup">
                                </div>
                            </div>
                        </div>

                        {{-- Hotel Category --}}
                        <div class="vpc-section">
                            <div class="vpc-section-label">Hotel Category</div>
                            <div class="vpc-pills">
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_hotel" value="Homestay"> <span class="vpc-pill-label">🏡 Homestay</span>
                                </label>
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_hotel" value="Budget 2★" checked> <span class="vpc-pill-label">⭐⭐ Budget 2★</span>
                                </label>
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_hotel" value="3 Star"> <span class="vpc-pill-label">⭐⭐⭐ 3 Star</span>
                                </label>
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_hotel" value="4 Star"> <span class="vpc-pill-label">⭐⭐⭐⭐ 4 Star</span>
                                </label>
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_hotel" value="5 Star"> <span class="vpc-pill-label">🌟 5 Star</span>
                                </label>
                            </div>
                        </div>

                        {{-- Food Plan --}}
                        <div class="vpc-section">
                            <div class="vpc-section-label">Food Plan</div>
                            <div class="vpc-pills">
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_food" value="No Meal" checked> <span class="vpc-pill-label">🚫 No Meal</span>
                                </label>
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_food" value="Breakfast Only"> <span class="vpc-pill-label">🍳 Breakfast</span>
                                </label>
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_food" value="Breakfast + Dinner"> <span class="vpc-pill-label">🍽️ B+Dinner</span>
                                </label>
                                <label class="vpc-pill">
                                    <input type="radio" name="pkg_food" value="All Meals"> <span class="vpc-pill-label">🥘 All Meals</span>
                                </label>
                            </div>
                        </div>

                        {{-- Cab Type --}}
                        <div class="vpc-section">
                            <div class="vpc-section-label">Cab Type</div>
                            <div class="vpc-cab-grid">
                                <label class="vpc-cab-card">
                                    <input type="radio" name="pkg_cab" value="Swift Dzire" checked>
                                    <div class="vpc-cab-inner">
                                        <div class="cab-icon">🚗</div>
                                        <div class="cab-name">Swift Dzire</div>
                                        <div class="cab-cap">Up to 4 persons</div>
                                    </div>
                                </label>
                                <label class="vpc-cab-card">
                                    <input type="radio" name="pkg_cab" value="Innova Crysta">
                                    <div class="vpc-cab-inner">
                                        <div class="cab-icon">🚙</div>
                                        <div class="cab-name">Innova Crysta</div>
                                        <div class="cab-cap">Up to 7 persons</div>
                                    </div>
                                </label>
                                <label class="vpc-cab-card">
                                    <input type="radio" name="pkg_cab" value="Ertiga">
                                    <div class="vpc-cab-inner">
                                        <div class="cab-icon">🚐</div>
                                        <div class="cab-name">Ertiga</div>
                                        <div class="cab-cap">Up to 7 persons</div>
                                    </div>
                                </label>
                                <label class="vpc-cab-card">
                                    <input type="radio" name="pkg_cab" value="Tempo Traveller">
                                    <div class="vpc-cab-inner">
                                        <div class="cab-icon">🚌</div>
                                        <div class="cab-name">Tempo Traveller</div>
                                        <div class="cab-cap">Up to 12 persons</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="vpc-section">
                            <div class="vpc-field">
                                <label class="vpc-label">Special Requests <small>(optional)</small></label>
                                <textarea class="vpc-input" id="pkg_notes" rows="2" placeholder="Any special requirements or preferences…"></textarea>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="vpc-submit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                            Submit Enquiry
                        </button>
                        <p class="vpc-submit-note">We'll respond within minutes ⚡</p>
                    </form>

                    <div class="vpc-support">
                        <a href="tel:+91{{ $waNumber }}" class="btn-call">📞 Call Us</a>
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="btn-wa">💬 WhatsApp</a>
                    </div>
                    @endif
                </div>
            </div>
        </aside>
                </div>{{-- /.pkg-popup-body --}}
            </div>{{-- /.pkg-popup-sheet --}}
        </div>{{-- /.pkg-popup-overlay --}}

    </div>{{-- /.pkd-body --}}

</div>{{-- /.pkd-wrap --}}

{{-- ── Mobile sticky bar ─────────────────────────────────── --}}
<div class="pkd-sticky-mobile" id="pkdStickyBar">
    <div class="sm-price">
        @if($discPrice > 0 && $basePrice > $discPrice)
            <div class="sm-old">₹{{ number_format($basePrice) }}</div>
        @endif
        <div class="sm-new">₹{{ number_format($displayPrice) }}</div>
        <div class="sm-note">per person onwards</div>
    </div>
    <div class="pkd-sticky-btns">
        <button class="pkd-sticky-btn pkd-sticky-book" onclick="pkgOpenPopup()">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Enquiry Now
        </button>
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="pkd-sticky-btn pkd-sticky-wa">
            <svg width="20" height="20" viewBox="0 0 32 32" fill="currentColor"><path d="M16 3C8.82 3 3 8.82 3 16c0 2.43.65 4.7 1.78 6.67L3 29l6.55-1.72A13 13 0 0 0 16 29c7.18 0 13-5.82 13-13S23.18 3 16 3zm6.4 17.72c-.27.76-1.58 1.45-2.16 1.54-.56.09-1.26.13-2.04-.13a18.7 18.7 0 0 1-1.85-.68C13.6 20.3 11.6 17.9 11.45 17.7c-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.2 1.05-2.5.27-.3.6-.37.8-.37l.57.01c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.14.17-.3.38-.43.51-.14.14-.29.3-.12.58.17.28.74 1.22 1.59 1.97 1.09.97 2 1.27 2.29 1.41.28.14.45.12.61-.07.17-.2.72-.84.91-1.13.2-.28.39-.23.66-.14.27.09 1.71.8 2 .95.29.14.48.21.55.33.07.12.07.7-.2 1.46z"/></svg>
        </a>
    </div>
</div>

{{-- ============================================================
     POPULAR PACKAGES — slider, shown right before the shared
     "Why Travelers Choose Visit Kashi" section (app.blade.php
     yields this content, then includes _before_footer next).
============================================================ --}}
@if($popularPackages->count())
<section class="vk-section td-pkg-section">
    <div class="container">
        <div class="vk-section__header">
            <h2 class="vk-section__title">Popular Packages</h2>
            <a class="vk-btn vk-btn--outline" href="{{ route('product.list', 'packages') }}" aria-label="View all packages">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
        <div class="row package-slider slider-button">
            @foreach($popularPackages as $pkg)
            <div class="col-lg-4">
                <a @if($pkg->subCategory)
                       href="{{ route('product.detail', [$pkg->category->slug, $pkg->subCategory->slug, $pkg->slug]) }}"
                   @else
                       href="{{ route('product.detail', [$pkg->category->slug, 'varanasi', $pkg->slug]) }}"
                   @endif
                   class="td-pkg-card">
                    <div class="td-pkg-card__img-wrap">
                        <img src="{{ asset(!empty($pkg->images) ? 'backend/admin/product_images/' . array_values($pkg->images)[0] : 'backend/assets/images/placeholder.jpg') }}"
                             alt="{{ $pkg->name }}"
                             class="td-pkg-card__img"
                             loading="lazy"
                             decoding="async"
                             width="600" height="400" />
                        <div class="td-pkg-card__overlay" aria-hidden="true"></div>
                    </div>
                    <div class="td-pkg-card__body">
                        <h3 class="td-pkg-card__title">{{ $pkg->name }}</h3>
                        @if($pkg->discounted_price)
                        <p class="td-pkg-card__price">
                            from <strong><span class="vk-rupee">₹</span>{{ number_format($pkg->discounted_price) }}</strong>
                            <span class="td-pkg-card__per">/ person</span>
                        </p>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
(function () {
    /* ── Lightbox ── */
    var pkdImgs = @json($images);
    var pkdBase = '{{ asset("backend/admin/product_images/") }}/';
    var pkdIdx  = 0;

    window.pkdLbOpen = function (idx) {
        pkdIdx = idx;
        pkdLbRender();
        document.getElementById('pkdLb').classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.pkdLbClose = function () {
        document.getElementById('pkdLb').classList.remove('open');
        document.body.style.overflow = '';
    };
    window.pkdLbNav = function (dir) {
        pkdIdx = (pkdIdx + dir + pkdImgs.length) % pkdImgs.length;
        pkdLbRender();
    };
    function pkdLbRender() {
        document.getElementById('pkdLbImg').src = pkdBase + pkdImgs[pkdIdx];
        document.getElementById('pkdLbCounter').textContent = (pkdIdx+1) + ' / ' + pkdImgs.length;
    }

    document.querySelectorAll('[data-lb]').forEach(function (el) {
        el.addEventListener('click', function () { pkdLbOpen(parseInt(this.dataset.lb)); });
    });
    document.getElementById('pkdLb').addEventListener('click', function (e) {
        if (e.target === this) pkdLbClose();
    });
    document.addEventListener('keydown', function (e) {
        if (!document.getElementById('pkdLb').classList.contains('open')) return;
        if (e.key === 'Escape')     pkdLbClose();
        if (e.key === 'ArrowLeft')  pkdLbNav(-1);
        if (e.key === 'ArrowRight') pkdLbNav(1);
    });

    /* ── FAQ toggle ── */
    window.toggleFaq = function (el) {
        var ans = el.nextElementSibling;
        var icon = el.querySelector('.faq-icon');
        ans.classList.toggle('open');
        icon.style.transform = ans.classList.contains('open') ? 'rotate(180deg)' : '';
    };

    /* ── Day itinerary toggle ── */
    window.toggleDay = function (el) {
        var body  = el.nextElementSibling;
        var arrow = el.querySelector('.pkd-day-arrow');
        body.classList.toggle('open');
        arrow.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : '';
    };

    /* ── Steppers ── */
    function initStepper(minusId, plusId, valId, min, max, startVal) {
        var minus = document.getElementById(minusId);
        var plus  = document.getElementById(plusId);
        var val   = document.getElementById(valId);
        var n = startVal;
        function render() {
            val.textContent = n;
            minus.disabled = n <= min;
            plus.disabled  = n >= max;
        }
        minus.addEventListener('click', function () { if (n > min) { n--; render(); } });
        plus.addEventListener('click',  function () { if (n < max) { n++; render(); } });
        render();
    }
    initStepper('pAdultMinus',  'pAdultPlus',  'pAdultVal',  1, 20, 2);
    initStepper('pChildMinus',  'pChildPlus',  'pChildVal',  0, 20, 0);
    initStepper('pInfantMinus', 'pInfantPlus', 'pInfantVal', 0, 10, 0);

    /* ── Phone digits only ── */
    document.getElementById('pkg_phone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g,'').slice(0,10);
    });

    /* ── Set default date ── */
    var dateEl = document.getElementById('pkg_date');
    if (dateEl && !dateEl.value) {
        var d = new Date(); d.setDate(d.getDate() + 1);
        dateEl.value = d.toISOString().slice(0,10);
    }

    /* ── Form submit → save enquiry on dashboard + email ── */
    document.getElementById('pkgEnqForm').addEventListener('submit', function (e) {
        var name   = document.getElementById('pkg_name').value.trim();
        var phone  = document.getElementById('pkg_phone').value.trim();
        var date   = document.getElementById('pkg_date').value;
        var days   = document.getElementById('pkg_days').value;
        var pickup = document.getElementById('pkg_pickup').value.trim();
        var drop   = document.getElementById('pkg_drop').value.trim();
        var notes  = document.getElementById('pkg_notes').value.trim();

        var adults  = document.getElementById('pAdultVal').textContent;
        var children= document.getElementById('pChildVal').textContent;
        var infants = document.getElementById('pInfantVal').textContent;

        var hotel = document.querySelector('input[name="pkg_hotel"]:checked');
        var food  = document.querySelector('input[name="pkg_food"]:checked');
        var cab   = document.querySelector('input[name="pkg_cab"]:checked');

        if (!name || !phone || phone.length < 10) {
            e.preventDefault();
            alert('Please enter your name and a valid 10-digit phone number.');
            return;
        }
        if (!date) { e.preventDefault(); alert('Please select a travel start date.'); return; }

        document.getElementById('pkg_persons_hidden').value  = adults;
        document.getElementById('pkg_children_hidden').value = children;
        document.getElementById('pkg_message_hidden').value =
              'Duration: ' + days + '\n'
            + 'Infants: ' + infants + '\n'
            + 'Pickup: ' + pickup + '\n'
            + 'Drop: ' + drop + '\n'
            + 'Hotel Category: ' + (hotel ? hotel.value : '') + '\n'
            + 'Food Plan: ' + (food ? food.value : '') + '\n'
            + 'Cab Type: ' + (cab ? cab.value : '') + '\n'
            + (notes ? 'Notes: ' + notes : '');
    });

})();
</script>

<script>
/* ── Package Enquiry Popup (mobile) ── */
function pkgOpenPopup() {
    var popup = document.getElementById('pkgBookingPopup');
    if (popup) { popup.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function pkgClosePopup() {
    var popup = document.getElementById('pkgBookingPopup');
    if (popup) { popup.classList.remove('open'); document.body.style.overflow = ''; }
}
document.getElementById('pkgBookingPopup').addEventListener('click', function(e) {
    if (e.target === this) pkgClosePopup();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') pkgClosePopup();
});
/* Auto-open popup on mobile after successful enquiry submission */
@if(session('success'))
if (window.innerWidth < 992) { pkgOpenPopup(); }
@endif
</script>
@endpush
