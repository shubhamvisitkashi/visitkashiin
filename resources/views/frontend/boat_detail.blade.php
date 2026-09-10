@extends('frontend.layouts.app')

@php
    // This detail template is shared by every boat product. These flags scope
    // product-specific SEO content/schema (FAQ, headings) to one product each,
    // so other boat pages are unaffected.
    $isPrivateGangaAartiMotorBoat = isset($product) && ($product->slug ?? '') === 'private-motor-boat-for-evening-ganga-aarti';
    $isDashaswamedhGhatPickupBoat = isset($product) && ($product->slug ?? '') === 'pickup-dashaswamedh-ghat-private-boat-ride-for-evening-ganga-aarti';
    $isMorningBoatRide            = isset($product) && ($product->slug ?? '') === 'morning-boat-ride-in-varanasi';
    $isEveningBoatRide            = isset($product) && ($product->slug ?? '') === 'evening-boat-ride-in-varanasi';
@endphp

@section('meta')
    @isset($product)
        @php
            $metaTitle = $product->meta_title ?? $product->name;
            $metaDesc  = $product->meta_description ?? 'Book private boat ride in Varanasi. Witness Ganga Aarti from the river. Best price guaranteed.';
            $metaImg   = (!empty($product->images) && is_array($product->images))
                ? asset('backend/admin/product_images/'.$product->images[0])
                : asset('backend/assets/images/placeholder.jpg');
            // Canonical/OG url always points at the production domain (never /public/),
            // matching the pattern already used in frontend.boat_listing.
            $subSlugForUrl = optional($product->subCategory)->slug ?? 'motor-boat';
            $pageUrl   = 'https://visitkashi.in/boat/'.$subSlugForUrl.'/'.$product->slug;
            $displayP  = ($product->discounted_price ?? 0) > 0 ? $product->discounted_price : ($product->base_price ?? 0);
        @endphp
        <link rel="canonical" href="{{ $pageUrl }}">
        <title>{{ $metaTitle }} - {{ env('APP_DOMAIN') }}</title>
        <meta name="description" content="{{ $metaDesc }}">
        <meta name="keywords"    content="{{ $product->meta_keyword }}">
        <meta name="robots"      content="index, follow">

        {{-- Open Graph --}}
        <meta property="og:type"        content="product">
        <meta property="og:title"       content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDesc }}">
        <meta property="og:url"         content="{{ $pageUrl }}">
        <meta property="og:image"       content="{{ $metaImg }}">
        <meta property="og:site_name"   content="{{ env('APP_DOMAIN') }}">

        {{-- Twitter Card --}}
        <meta name="twitter:card"        content="summary_large_image">
        <meta name="twitter:title"       content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDesc }}">
        <meta name="twitter:image"       content="{{ $metaImg }}">

        {{-- JSON-LD: Product Schema --}}
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": "{{ addslashes($product->name) }}",
            "description": "{{ addslashes(strip_tags($metaDesc)) }}",
            "image": "{{ $metaImg }}",
            "url": "{{ $pageUrl }}",
            "brand": { "@type": "Brand", "name": "Visit Kashi" },
            "offers": {
                "@type": "Offer",
                "priceCurrency": "INR",
                "price": "{{ $displayP > 0 ? $displayP : '' }}",
                "availability": "https://schema.org/InStock",
                "url": "{{ $pageUrl }}",
                "seller": { "@type": "Organization", "name": "Visit Kashi" }
            },
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.9",
                "reviewCount": "600",
                "bestRating": "5"
            }
        }
        </script>

        {{-- JSON-LD: FAQPage Schema (generic) — suppressed for pages that ship their
             own FAQPage schema further down, matching the FAQ actually visible on
             that page (see $isPrivateGangaAartiMotorBoat / $isDashaswamedhGhatPickupBoat). --}}
        @unless($isPrivateGangaAartiMotorBoat || $isDashaswamedhGhatPickupBoat || $isMorningBoatRide || $isEveningBoatRide)
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [
                {
                    "@type": "Question",
                    "name": "What is the price of this boat ride in Varanasi?",
                    "acceptedAnswer": { "@type": "Answer", "text": "The price starts at ₹{{ $displayP > 0 ? number_format($displayP) : '3,499' }}/-. Additional passengers above capacity are charged ₹500/- per person." }
                },
                {
                    "@type": "Question",
                    "name": "Which ghats does the boat ride cover?",
                    "acceptedAnswer": { "@type": "Answer", "text": "The private boat covers all 84 Ghats of Varanasi — from Assi Ghat to NAMO Ghat (7 km stretch)." }
                },
                {
                    "@type": "Question",
                    "name": "Is the boat ride safe for families and children?",
                    "acceptedAnswer": { "@type": "Answer", "text": "Yes. All boats are equipped with life jackets for every passenger. Our boatmen are licensed and certified by the Varanasi Boat Association." }
                },
                {
                    "@type": "Question",
                    "name": "How long is the boat ride in Varanasi?",
                    "acceptedAnswer": { "@type": "Answer", "text": "The standard private boat ride is approximately 2 hours 30 minutes, covering all 84 Ghats from Assi Ghat to NAMO Ghat and back." }
                },
                {
                    "@type": "Question",
                    "name": "How do I book this boat ride?",
                    "acceptedAnswer": { "@type": "Answer", "text": "Fill in the booking form on this page and submit — our team will contact you on WhatsApp within minutes to confirm." }
                }
            ]
        }
        </script>
        @endunless

        {{-- JSON-LD: TouristAttraction + LocalBusiness --}}
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": ["TouristAttraction","LocalBusiness"],
            "name": "{{ addslashes($product->name) }}",
            "description": "{{ addslashes(strip_tags($metaDesc)) }}",
            "url": "{{ $pageUrl }}",
            "image": "{{ $metaImg }}",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Dashashwamedh Ghat",
                "addressLocality": "Varanasi",
                "addressRegion": "Uttar Pradesh",
                "postalCode": "221001",
                "addressCountry": "IN"
            },
            "telephone": "+917080109917",
            "priceRange": "₹{{ $displayP > 0 ? number_format($displayP) : '3,499' }}",
            "openingHours": "Mo-Su 05:00-21:00",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.9",
                "reviewCount": "600",
                "bestRating": "5"
            },
            "review": [
                {
                    "@type": "Review",
                    "author": {"@type": "Person", "name": "Rahul Sharma"},
                    "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5"},
                    "reviewBody": "Absolutely stunning event on the Ganga! The boat was beautifully decorated and the team was very professional."
                },
                {
                    "@type": "Review",
                    "author": {"@type": "Person", "name": "Priya Mehta"},
                    "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5"},
                    "reviewBody": "We celebrated our anniversary on this event boat. The Ganga Aarti view from the river was breathtaking!"
                }
            ]
        }
        </script>

        {{-- JSON-LD: Service Schema --}}
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Service",
            "name": "{{ addslashes($product->name) }}",
            "serviceType": "{{ $isMorningBoatRide ? 'Morning Boat Ride' : ($isEveningBoatRide ? 'Evening Boat Ride' : 'Event Boat Booking') }}",
            "provider": {
                "@type": "LocalBusiness",
                "name": "Visit Kashi",
                "url": "{{ url('/') }}",
                "telephone": "+917080109917"
            },
            "areaServed": {"@type": "City", "name": "Varanasi"},
            "offers": {
                "@type": "Offer",
                "priceCurrency": "INR",
                "price": "{{ $displayP > 0 ? $displayP : '5000' }}",
                "availability": "https://schema.org/InStock"
            }
        }
        </script>

        {{-- JSON-LD: Breadcrumb Schema — kept in sync with the visible breadcrumb below --}}
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {"@type": "ListItem","position": 1,"name": "Home","item": "{{ url('/') }}"},
                {"@type": "ListItem","position": 2,"name": "{{ addslashes(optional($product->category)->name ?? 'Boat') }}","item": "{{ url('/boat') }}"},
                {"@type": "ListItem","position": 3,"name": "{{ addslashes(optional($product->subCategory)->name ?? 'Motor Boat') }}","item": "{{ url('/boat/'.$subSlugForUrl) }}"},
                {"@type": "ListItem","position": 4,"name": "{{ addslashes($product->name) }}","item": "{{ $pageUrl }}"}
            ]
        }
        </script>
    @endisset
@endsection

@push('styles')
<link rel="preload" href="{{ asset('frontend/css/boat-detail.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-detail.min.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/boat-detail.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-detail.min.css')) }}"></noscript>
<style>
/* ── page-specific overrides only ── */
</style>
@endpush

@section('content')
@php
    $imgs = (!empty($product->images) && is_array($product->images))
        ? $product->images
        : ((!empty($product->images) && is_string($product->images))
            ? json_decode($product->images, true) ?? []
            : []);
    $imgUrls = array_map(fn($f) => asset('backend/admin/product_images/'.$f), $imgs);
    $fallback = asset('backend/assets/images/placeholder.jpg');
    if (empty($imgUrls)) $imgUrls = [$fallback];

    $hasDiscount = ($product->base_price ?? 0) > 0
        && ($product->discounted_price ?? 0) > 0
        && $product->base_price > $product->discounted_price;
    $displayPrice = ($product->discounted_price ?? 0) > 0
        ? $product->discounted_price
        : ($product->base_price ?? 0);

    $catSlug = optional($product->category)->slug ?? 'boat';
    $subSlug = optional($product->subCategory)->slug ?? 'motor-boat';
    $subName = optional($product->subCategory)->name ?? 'Boat Ride';
    $catName = optional($product->category)->name ?? 'Boat';

    $isDevDiwali = str_contains(strtolower($subSlug), 'dev-diwali')
                   && now()->lte(\Carbon\Carbon::parse('2026-11-24')->endOfDay());

    // Distinct, descriptive ALT text for this product's gallery photos (falls back
    // to the generic "<name> – Photo N" pattern used by every other boat product).
    $galleryAlts = $isPrivateGangaAartiMotorBoat ? [
        'Private motor boat for Evening Ganga Aarti in Varanasi',
        'Evening Ganga Aarti viewed from a private boat in Varanasi',
        'Private motor boat ride on the Ganga River, Varanasi',
    ] : ($isMorningBoatRide ? [
        'Morning boat ride in Varanasi before sunrise on the Ganga River',
        'Sunrise view over the Ganga during a morning boat ride in Varanasi',
        'Private morning boat ride passing Varanasi ghats at dawn',
    ] : ($isEveningBoatRide ? [
        'Evening boat ride on Ganga River in Varanasi at sunset',
        'Sunset boat ride Varanasi ghats seen from the river',
        'Ganga Aarti boat ride Varanasi near Dashashwamedh Ghat',
    ] : []));
    $galleryAlt = fn($i) => $galleryAlts[$i] ?? ($product->name.' – Photo '.($i + 1));
@endphp

{{-- Breadcrumb --}}
<div class="vkbd-breadcrumb">
    <div class="container">
        <a href="{{ url('/') }}">Home</a>
        <span>/</span>
        <a href="{{ url('/'.$catSlug) }}">{{ $catName }}</a>
        <span>/</span>
        <a href="{{ route('product.sub.list', [$catSlug, $subSlug]) }}">{{ $subName }}</a>
        <span>/</span>
        {{ $product->name }}
    </div>
</div>

<div class="container vkbd-container">

    {{-- Title row --}}
    <div class="vkbd-title-row">
        <h1>{{ $product->name }}</h1>
        <div class="vkbd-meta">
            <span class="vkbd-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <span class="vkbd-rating">4.9</span>
            <span>(600+ Reviews)</span>
            <span class="vkbd-meta-dot">&middot;</span>
            <a href="{{ route('product.sub.list', [$catSlug, $subSlug]) }}" class="vkbd-cat-link">{{ $subName }}</a>
            <span class="vkbd-meta-dot">&middot;</span>
            <span>Varanasi, Uttar Pradesh</span>
        </div>
    </div>

    @if($isPrivateGangaAartiMotorBoat)
    <p class="vkbd-intro-text">Book a <strong>private motor boat for Evening Ganga Aarti</strong> in Varanasi and watch the world-famous Ganga Aarti from the River Ganga — without sharing your boat with other travellers. The boat is positioned in front of the Ganga Aarti ceremony at Dashaswamedh / Lalita Ghat, giving you a peaceful river view of the diyas, temple bells and chants as the aarti unfolds. Ideal for couples, families and small groups, this private <a href="{{ route('product.detail', ['boat','motor-boat','evening-boat-ride-in-varanasi']) }}" class="vkbd-cat-link">evening boat ride</a> can be booked online or confirmed instantly on WhatsApp.</p>
    @elseif($isMorningBoatRide)
    <p class="vkbd-intro-text">A <strong>morning boat ride in Varanasi</strong> is one of the most peaceful ways to see the Ganga ghats come alive — the ride starts before sunrise and runs for about an hour or two, well ahead of the daytime crowds. As your boatman rows past Assi Ghat, Dashashwamedh Ghat, Manikarnika Ghat and Harishchandra Ghat, you'll see the city's early rituals up close: people bathing, praying and doing yoga on the ghat steps, along with the spectacular sunrise breaking over the river. This private boat ride is booked for your group only — no sharing with other travellers — and can be confirmed online or on WhatsApp. Prefer to watch the evening ceremony instead? See our <a href="{{ route('product.detail', ['boat','motor-boat','private-motor-boat-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">private Ganga Aarti boat ride</a>.</p>
    @elseif($isEveningBoatRide)
    <p class="vkbd-intro-text">An <strong>evening boat ride in Varanasi</strong> is one of the most atmospheric ways to experience the Ganga River — the ride sets out a few hours before sunset and continues into the evening, as the ghats light up with lamps and the sky turns gold over the water. Your boatman takes you along the riverfront past Dashashwamedh Ghat and Manikarnika Ghat, giving you a water-level view of the city's skyline, its temples, and the evening Aarti ceremony taking place on the steps. This ride works well as a <strong>sunset boat ride</strong>, an <strong>evening Ganga boat ride</strong>, or a way to watch <strong>Ganga Aarti</strong> from the water, and it's booked as a private boat for your group. Prefer to go before sunrise instead? See our <a href="{{ route('product.detail', ['boat','motor-boat','morning-boat-ride-in-varanasi']) }}" class="vkbd-cat-link">morning boat ride in Varanasi</a>.</p>
    @endif

    {{-- Gallery --}}
    <div class="vkbd-gallery" id="vkbdGallery">
        <div class="vkbd-gallery-item vkbd-gallery-hero" onclick="vkLbOpen(0)">
            <img src="{{ $imgUrls[0] }}" alt="{{ $galleryAlt(0) }}" loading="eager"
                 onerror="this.src='{{ $fallback }}'">
        </div>
        @if(isset($imgUrls[1]))
        <div class="vkbd-gallery-item" onclick="vkLbOpen(1)">
            <img src="{{ $imgUrls[1] }}" alt="{{ $galleryAlt(1) }}" loading="lazy"
                 onerror="this.src='{{ $fallback }}'">
        </div>
        @endif
        @if(isset($imgUrls[2]))
        <div class="vkbd-gallery-item" onclick="vkLbOpen(2)">
            <img src="{{ $imgUrls[2] }}" alt="{{ $galleryAlt(2) }}" loading="lazy"
                 onerror="this.src='{{ $fallback }}'">
        </div>
        @endif
        <button class="vkbd-gallery-btn" onclick="vkLbOpen(0)">
            <i class="fa fa-th"></i> Show all {{ count($imgUrls) }} photos
        </button>
    </div>

    {{-- Lightbox --}}
    <div class="vkbd-lb-overlay" id="vkLb">
        <button class="vkbd-lb-close" onclick="vkLbClose()">&#10005;</button>
        <button class="vkbd-lb-prev" onclick="vkLbNav(-1)">&#8249;</button>
        <img id="vkLbImg" src="" alt="">
        <button class="vkbd-lb-next" onclick="vkLbNav(1)">&#8250;</button>
        <div class="vkbd-lb-caption" id="vkLbCap"></div>
    </div>

    {{-- 2-col layout --}}
    <div class="vkbd-layout">

        {{-- ── MAIN CONTENT ──────────────────────────────────── --}}
        <div class="vkbd-main">

            @if($isEveningBoatRide)
            {{-- Quick Answer — direct, AI/AEO-friendly definition of the service --}}
            <div class="vkbd-quick-answer">
                <h2 class="vkbd-section-title">What Is an Evening Boat Ride in Varanasi?</h2>
                <p>An evening boat ride in Varanasi is a Ganga River boat ride that runs in the hours before and around sunset, covering ghats such as Dashashwamedh Ghat and Manikarnika Ghat, with views of the evening Aarti ceremony from the water.</p>
                <p class="vkbd-quick-answer-best"><strong>Best for:</strong> Sunset views &middot; Ganga Aarti viewing &middot; Evening sightseeing &middot; Photography &middot; Couples &middot; Families &middot; Private groups</p>
            </div>
            @endif

            {{-- Provider bar --}}
            <div class="vkbd-provider-bar">
                <div class="vkbd-provider-avatar">
                    <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                         alt="Visit Kashi Logo" class="vkbd-provider-logo" />
                </div>
                <div class="vkbd-provider-info">
                    <p class="vkbd-provider-name">Provided by Visit Kashi</p>
                    <p>Verified Boat Service &nbsp;&middot;&nbsp; Best Price Guaranteed &nbsp;&middot;&nbsp; Instant WhatsApp Booking</p>
                </div>
            </div>

            <div class="vkbd-divider"></div>

            {{-- Highlights --}}
            <div class="vkbd-highlights">
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-ship"></i></div>
                    <div class="vkbd-highlight-text">
                        <p class="vkbd-highlight-title">Private Boat – No Sharing, Complete Privacy</p>
                        <p>The entire boat is exclusively yours. Ideal for families, couples, and small groups seeking a peaceful and private ride on the sacred Ganga.</p>
                    </div>
                </div>
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-map-marker"></i></div>
                    <div class="vkbd-highlight-text">
                        <p class="vkbd-highlight-title">Ride Covering All 84 Ghats of Varanasi</p>
                        <p>Cruise the full 7 km stretch from Assi Ghat to NAMO Ghat, passing all 84 historic ghats of Kashi including Dashashwamedh Ghat, Manikarnika Ghat, and Harishchandra Ghat.</p>
                    </div>
                </div>
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-star"></i></div>
                    <div class="vkbd-highlight-text">
                        <p class="vkbd-highlight-title">Witness Evening Ganga Aarti from the River</p>
                        <p>Experience the world-famous Ganga Aarti at Dashashwamedh Ghat from your private boat — no crowd, no standing, just a divine front-row view.</p>
                    </div>
                </div>
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-tag"></i></div>
                    <div class="vkbd-highlight-text">
                        <p class="vkbd-highlight-title">Best Price Guarantee
                            @if($displayPrice > 0 && !$isDevDiwali)
                            – Starting ₹{{ number_format($displayPrice) }}/-
                            @endif
                        </p>
                        <p>Visit Kashi offers the most competitive price for boat rides in Varanasi. Trusted local partner with 5+ years of experience and 4.9-star Google rating.</p>
                    </div>
                </div>
            </div>

            <div class="vkbd-divider"></div>

            {{-- Description --}}
            <h2 class="vkbd-section-title">About {{ $product->name }}</h2>
            <div class="vkbd-description">
                @if($product->description)
                    {!! safe_html($product->description) !!}
                @else
                    <p>Experience the spiritual charm of Kashi with <strong>{{ $product->name }}</strong>. Enjoy a <strong>private boat ride covering all 84 Ghats of Varanasi</strong> at the best price. Perfect for families, couples, or small groups, this boat ride lets you witness the magical beauty of the Ganga river without any crowd.</p>
                    <p>Enjoy a peaceful ride across all 84 Ghats, from the grand <strong>Dashashwamedh Ghat</strong> with its world-famous Ganga Aarti to the serene <strong>Assi Ghat</strong>. Unlike crowded shared boats, our private boat ensures privacy, safety, and comfort — making it the highlight of your Varanasi trip.</p>
                @endif
            </div>

            @if(!$isDevDiwali)
            <div class="vkbd-divider"></div>

            {{-- Specs table --}}
            <h2 class="vkbd-section-title">{{ $isPrivateGangaAartiMotorBoat ? 'Private Motor Boat Capacity & Pricing' : ($isMorningBoatRide ? 'Morning Boat Ride – Price, Duration & Timing' : ($isEveningBoatRide ? 'Evening Boat Ride in Varanasi Price' : 'Varanasi Boat Ride – Pricing, Timings & Booking Details')) }}</h2>
            <table class="vkbd-specs" itemscope itemtype="https://schema.org/Service">
                <tr>
                    <td class="vkbd-spec-label">Reporting Point</td>
                    <td class="vkbd-spec-val"><i class="fa fa-map-marker"></i>{{ $isMorningBoatRide ? 'Assi Ghat / Dashashwamedh Ghat' : 'NAMO Ghat / Dashashwamedh Ghat / Ravidas Ghat' }}</td>
                </tr>
                @if($isMorningBoatRide)
                <tr>
                    <td class="vkbd-spec-label">Ride Timing</td>
                    <td class="vkbd-spec-val"><i class="fa fa-clock-o"></i>Starts before sunrise <span class="vkbd-spec-note">(exact time varies by season – confirmed on WhatsApp)</span></td>
                </tr>
                @else
                <tr>
                    <td class="vkbd-spec-label">Ganga Aarti</td>
                    <td class="vkbd-spec-val"><i class="fa fa-clock-o"></i>Evening – Dashashwamedh Ghat <span class="vkbd-spec-note">(7:00 PM – 7:45 PM)</span></td>
                </tr>
                @endif
                <tr>
                    <td class="vkbd-spec-label">Price</td>
                    <td class="vkbd-spec-val">
                        <i class="fa fa-inr"></i>
                        @if($displayPrice > 0)
                            <strong class="vkbd-price-amount">₹{{ number_format($displayPrice) }}</strong>
                            @if($hasDiscount)
                            &nbsp;<span class="vkbd-price-strike">₹{{ number_format($product->base_price) }}</span>
                            @endif
                            &nbsp;<span class="vkbd-spec-note">private boat · up to 10 persons</span>
                        @else
                            <span class="vkbd-text-muted">Price on request</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Capacity</td>
                    <td class="vkbd-spec-val"><i class="fa fa-users"></i>Up to 10 persons (extra @ ₹500/person)</td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Duration</td>
                    <td class="vkbd-spec-val"><i class="fa fa-clock-o"></i>{{ ($isMorningBoatRide || $isEveningBoatRide) ? 'Approx. 1 – 2 Hours' : 'Approx. 2 Hours 30 Minutes' }}</td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Languages</td>
                    <td class="vkbd-spec-val"><i class="fa fa-language"></i>English, Hindi</td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Payment</td>
                    <td class="vkbd-spec-val"><i class="fa fa-credit-card"></i>UPI, GPay, PhonePe, NEFT, RTGS</td>
                </tr>
            </table>
            @endif

            <div class="vkbd-divider"></div>

            {{-- What's included --}}
            <h2 class="vkbd-section-title">What's Included</h2>
            <ul class="vkbd-includes">
                <li><i class="fa fa-check-circle"></i> Life Jackets for all passengers</li>
                <li><i class="fa fa-check-circle"></i> Licensed &amp; experienced boatman</li>
                @if(!$isDevDiwali)
                <li><i class="fa fa-check-circle"></i> Private boat – no sharing</li>
                @endif
                <li><i class="fa fa-check-circle"></i> Full 84-Ghat Varanasi tour (Assi to NAMO)</li>
                <li><i class="fa fa-check-circle"></i> Evening Ganga Aarti viewing from the Ganga</li>
                <li><i class="fa fa-check-circle"></i> Photo stops at key scenic ghats</li>
            </ul>

            {{-- ── Evening Ganga Aarti Private Motor Boat — dedicated SEO content ── --}}
            @if($isPrivateGangaAartiMotorBoat)
            <div class="vkbd-divider"></div>
            <div class="vkbd-event-content">
                <h2 class="vkbd-section-title">Experience Evening Ganga Aarti from a Private Boat</h2>
                <p>Watching the <strong>evening Ganga Aarti from a private motor boat</strong> is one of the most memorable ways to see this centuries-old ritual. As the sun sets over the River Ganga, your boat is positioned on the river in front of the ceremony at Dashaswamedh / Lalita Ghat — close enough to see the priests, the flaming diyas, and hear the temple bells and chants, without needing to stand in the crowd gathered on the ghat steps. Because the boat is private, it's just you, your family or group, and the boatman — no sharing with strangers.</p>

                <h3 class="vkbd-sub-title">Private Boat Ride Along the Varanasi Ghats</h3>
                <p>Before the aarti begins, your private motor boat cruises along the Varanasi riverfront, giving you a water-level view of the historic ghats that line the Ganga — the same stretch of river that has been central to life in Kashi for centuries.</p>

                <h4 class="vkbd-h4-title">Assi Ghat to Namo Ghat Boat Ride</h4>
                <p>The full private boat route runs along the Varanasi riverfront from <strong>Assi Ghat to Namo Ghat</strong> — a stretch of roughly 7 km. Explore the historic ghats along the Assi Ghat to Namo Ghat riverfront route before your boat is positioned for the evening Ganga Aarti.</p>

                <h4 class="vkbd-h4-title">Important Ghats You Can See</h4>
                <p>Depending on your pickup point and route, ghats along the way include Assi Ghat, Tulsi Ghat, Shivala Ghat, Harishchandra Ghat, Kedar Ghat, <strong>Dashashwamedh Ghat</strong>, <strong>Manikarnika Ghat</strong>, Rajendra Prasad Ghat and on to <strong>Namo Ghat</strong>.</p>

                <h3 class="vkbd-sub-title">Evening Boat Ride – Timings &amp; Itinerary</h3>
                <ol class="vkbd-steps">
                    <li><strong>Reporting:</strong> Arrive at your selected pickup ghat around 5:30 PM.</li>
                    <li><strong>Boat Departure:</strong> Your private motor boat departs at approximately 5:45 PM.</li>
                    <li><strong>Ghat Sightseeing:</strong> Cruise along the Varanasi riverfront, viewing the ghats along the way.</li>
                    <li><strong>Ganga Aarti:</strong> The boat is positioned in front of the Ganga Aarti ceremony at Dashaswamedh / Lalita Ghat for the 7:00 PM – 7:45 PM aarti.</li>
                    <li><strong>Return:</strong> The boat returns to the pickup ghat once the ceremony ends, at approximately 7:45 PM.</li>
                </ol>
                <p class="vkbd-text-muted">Reporting time and route can vary slightly depending on your pickup ghat, river conditions and season — our team confirms the exact schedule on WhatsApp at the time of booking.</p>

                <h3 class="vkbd-sub-title">Best Pickup Points for Evening Ganga Aarti</h3>
                <p>This private motor boat can be picked up from <a href="{{ route('product.detail', ['boat','motor-boat','pickup-assi-ghat-private-boat-ride-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">Assi Ghat</a>, <a href="{{ route('product.detail', ['boat','motor-boat','pickup-dashaswamedh-ghat-private-boat-ride-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">Dashaswamedh Ghat</a>, <a href="{{ route('product.detail', ['boat','motor-boat','pickup-namo-ghat-private-boat-ride-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">Namo Ghat</a>, Ravidas Ghat and other nearby ghats — select your nearest pickup ghat in the booking form and our team will confirm the exact reporting point on WhatsApp.</p>

                <h3 class="vkbd-sub-title">How to Book This Private Motor Boat</h3>
                <ol class="vkbd-steps">
                    <li>Fill in the booking form on this page with your date, time slot, number of guests and pickup ghat.</li>
                    <li>Our team confirms availability and price on WhatsApp or by phone call.</li>
                    <li>Confirm your booking — no advance payment is required to enquire.</li>
                    <li>Arrive at your pickup ghat at the reporting time shared by our team and enjoy your private evening boat ride.</li>
                </ol>

                <p>Looking for other ways to see the Ganga Aarti? You can also check our <a href="{{ route('product.detail', ['boat','motor-boat','evening-boat-ride-in-varanasi']) }}" class="vkbd-cat-link">evening boat ride in Varanasi</a> (shared/group option) or the traditional <a href="{{ route('product.detail', ['boat','bajra-boat','private-bajra-boat-for-ganga-aarti']) }}" class="vkbd-cat-link">private Bajra boat for Ganga Aarti</a>.</p>
            </div>
            @endif

            {{-- ── Morning Boat Ride — dedicated SEO content ── --}}
            @if($isMorningBoatRide)
            <div class="vkbd-divider"></div>
            <div class="vkbd-event-content">
                <h2 class="vkbd-section-title">What You Can See on a Morning Boat Ride in Varanasi</h2>
                <p>A <strong>morning boat ride in Varanasi</strong> takes you past the ghats before the day's crowds arrive, while the city is still waking up. From the boat you'll see everyday life on the riverfront — people bathing, doing yoga and praying on the ghat steps — along with the cremation ghats, where the last rites of the deceased are performed. The ride is timed to end with the <strong>sunrise over the Ganga</strong>, one of the most photographed views in Varanasi.</p>

                <h3 class="vkbd-sub-title">Morning Boat Ride vs Sunrise Boat Ride</h3>
                <p>"Morning boat ride" and "sunrise boat ride" describe the same experience in Varanasi — the boat sets out before sunrise so that you're on the water as the sun comes up over the Ganga. There isn't a separate, later "morning" slot; booking this ride means you'll be on the river for the sunrise itself, not after it.</p>

                <h3 class="vkbd-sub-title">Morning Boat Ride Timing</h3>
                <p>The ride starts <strong>before sunrise</strong> and runs for about <strong>one to two hours</strong>. Because sunrise time in Varanasi shifts across the year, we don't fix a single departure time on this page — our team confirms the exact reporting time for your travel date on WhatsApp or by phone when you book.</p>

                <h3 class="vkbd-sub-title">Ghats You'll See on This Morning Boat Ride</h3>
                <p>The most popular ghats covered on a morning boat ride are <strong>Assi Ghat</strong>, <strong>Dashashwamedh Ghat</strong>, <strong>Manikarnika Ghat</strong> and <strong>Harishchandra Ghat</strong>. Exact ghats covered can vary slightly with your pickup point and river conditions on the day.</p>

                <h3 class="vkbd-sub-title">Private Morning Boat Ride</h3>
                <p>This morning boat ride is booked as a <strong>private boat for your group</strong> — it isn't shared with other travellers. The base price covers up to 10 persons, with additional guests charged ₹500 per person, so it works well for couples, families and small groups who want the ghats and sunrise to themselves.</p>

                <h3 class="vkbd-sub-title">How to Book This Morning Boat Ride</h3>
                <ol class="vkbd-steps">
                    <li>Fill in the booking form on this page with your travel date, number of guests and pickup ghat.</li>
                    <li>Our team confirms availability, exact reporting time and price on WhatsApp or by phone call.</li>
                    <li>Confirm your booking — no advance payment is required to enquire.</li>
                    <li>Arrive at your pickup ghat at the confirmed reporting time and enjoy your sunrise boat ride.</li>
                </ol>
                <p class="vkbd-text-muted">Due to high demand, same-day booking may be subject to availability — WhatsApp us to check before you plan your morning.</p>

                <p>Prefer to see the river in the evening instead? Check our <a href="{{ route('product.detail', ['boat','motor-boat','evening-boat-ride-in-varanasi']) }}" class="vkbd-cat-link">evening boat ride in Varanasi</a>, or browse all <a href="{{ route('product.sub.list', ['boat','motor-boat']) }}" class="vkbd-cat-link">motor boat rides in Varanasi</a>.</p>
            </div>
            @endif

            {{-- ── Evening Boat Ride — dedicated SEO content ── --}}
            @if($isEveningBoatRide)
            <div class="vkbd-divider"></div>
            <div class="vkbd-event-content">
                <h2 class="vkbd-section-title">What You Can Experience on an Evening Boat Ride</h2>
                <p>An evening boat ride in Varanasi runs in the hours before and during sunset, giving you a water-level view of the Ganga ghats as the city changes for the evening. As your boatman rows along the riverfront, you'll pass illuminated ghats and temples, watch devotees gather for the evening Aarti on the steps, and see the sky shift through shades of orange and gold over the river. It's a quieter, more atmospheric alternative to walking the ghats on foot — and a good option for photography.</p>

                <h2 class="vkbd-section-title">Ganga Aarti Boat Ride in Varanasi</h2>
                <p>This evening boat ride passes Dashashwamedh Ghat, where the main Ganga Aarti ceremony is held, so you can watch the diyas, temple bells and chanting from the river rather than from the crowd on the steps. The Aarti itself is generally held in the evening around 7:00 PM – 7:45 PM at Dashashwamedh Ghat; your own boat's schedule and viewing position on the day are confirmed by our team on WhatsApp or by phone when you book. If you want a boat positioned specifically in front of the Aarti for the full ceremony, our <a href="{{ route('product.detail', ['boat','motor-boat','private-motor-boat-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">private motor boat for Evening Ganga Aarti</a> is built around that exact experience, with dedicated pickup from <a href="{{ route('product.detail', ['boat','motor-boat','pickup-assi-ghat-private-boat-ride-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">Assi Ghat</a> or <a href="{{ route('product.detail', ['boat','motor-boat','pickup-dashaswamedh-ghat-private-boat-ride-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">Dashashwamedh Ghat</a>.</p>

                <h2 class="vkbd-section-title">Sunset Boat Ride in Varanasi</h2>
                <p>Timed a few hours before sunset, this ride is also a natural choice if sunset views over the Ganga are your main interest. As the sun goes down, the ghats and the water take on changing shades of orange and gold, and being on the river gives you an open view of the sky that's harder to get from the crowded ghat steps. Exact sunset time shifts through the year, so the ride's start time is confirmed for your travel date rather than fixed — and, as with any outdoor river experience, conditions like cloud cover can affect how the sunset itself looks on a given evening.</p>

                <h2 class="vkbd-section-title">Private Evening Boat Ride in Varanasi</h2>
                <p>This evening boat ride is booked as a private boat for your group — it isn't shared with other travellers. The base price covers up to 10 persons, with additional guests charged ₹500 per person, which makes it a practical option for couples, families and small groups who'd rather not share their sunset or Aarti view with strangers. It also works well for anniversaries, casual photography sessions and small celebrations, since you can move at your own pace along the river within the boat's route and timing.</p>

                <h2 class="vkbd-section-title">Where You Can Board This Evening Boat Ride</h2>
                <p>Boarding for this evening boat ride is generally arranged from ghats including NAMO Ghat, Dashashwamedh Ghat and Ravidas Ghat — our team confirms the exact pickup ghat closest to you when you book. Along the route, the ride passes Dashashwamedh Ghat and Manikarnika Ghat, two of the most recognisable ghats on the Varanasi riverfront. If you specifically want pickup from Assi Ghat or Dashashwamedh Ghat for a Ganga Aarti-focused private ride, see our dedicated <a href="{{ route('product.detail', ['boat','motor-boat','pickup-assi-ghat-private-boat-ride-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">Assi Ghat</a> and <a href="{{ route('product.detail', ['boat','motor-boat','pickup-dashaswamedh-ghat-private-boat-ride-for-evening-ganga-aarti']) }}" class="vkbd-cat-link">Dashashwamedh Ghat</a> pickup options.</p>

                <h2 class="vkbd-section-title">Evening Boat Ride Timing in Varanasi</h2>
                <p>This ride starts a few hours before sunset and runs for about one to two hours, so the exact departure time shifts with the season as sunset time changes across the year. Because timing also depends on Ganga Aarti scheduling and boat availability on the day, our team confirms the exact reporting time for your travel date on WhatsApp or by phone rather than fixing a single time on this page.</p>

                <h2 class="vkbd-section-title">How to Book an Evening Boat Ride in Varanasi?</h2>
                <ol class="vkbd-steps">
                    <li>Select your travel date and preferred time.</li>
                    <li>Choose your pickup ghat and number of passengers.</li>
                    <li>Submit the booking form on this page, or message us on WhatsApp.</li>
                    <li>Our team confirms availability, exact reporting time and price.</li>
                    <li>Arrive at the confirmed ghat and enjoy your evening boat ride.</li>
                </ol>
                <p class="vkbd-text-muted">Due to high demand, same-day booking may be subject to availability — WhatsApp us to check before you plan your evening.</p>

                <p>Looking for an evening boat ride in Varanasi? Book your evening Ganga boat ride, sunset boat ride, Ganga Aarti boat, or private boat with Visit Kashi — call or WhatsApp us at <a href="tel:+917080109917" class="vkbd-cat-link">+91-7080109917</a>, <a href="tel:+917080109918" class="vkbd-cat-link">7080109918</a> or <a href="tel:+917080109919" class="vkbd-cat-link">7080109919</a>. Prefer to go before sunrise? See our <a href="{{ route('product.detail', ['boat','motor-boat','morning-boat-ride-in-varanasi']) }}" class="vkbd-cat-link">morning boat ride in Varanasi</a>, or browse all <a href="{{ route('product.sub.list', ['boat','motor-boat']) }}" class="vkbd-cat-link">motor boat rides in Varanasi</a>.</p>
            </div>
            @endif

            @if($product->map_location)
            <div class="vkbd-divider"></div>
            <h2 class="vkbd-section-title">Location</h2>
            <div class="vkbd-video-wrap vkbd-map-wrap">
                <iframe src="{{ $product->map_location }}" allowfullscreen></iframe>
            </div>
            @endif

            <div class="vkbd-divider"></div>

            {{-- ── Event Boat SEO Content (shown for all boats, key for event-boat slug) ── --}}
            @if(str_contains(strtolower($product->slug ?? ''), 'event') || str_contains(strtolower($product->name ?? ''), 'event'))
            <div class="vkbd-divider"></div>
            <div class="vkbd-event-content">
                <h2 class="vkbd-section-title">Event Boat Booking in Varanasi — Complete Guide</h2>

                <h3 class="vkbd-sub-title">Why Choose an Event Boat on the Ganga?</h3>
                <p>Varanasi is one of the world's oldest living cities, and the sacred River Ganga is its beating heart. Hosting your private event — be it a birthday celebration, corporate gathering, anniversary dinner, or cultural programme — on a luxury event boat on the Ganga is an experience unlike any other in India.</p>
                <p>An <strong>event boat booking in Varanasi</strong> gives you a private floating venue in the middle of the holy Ganga, surrounded by the timeless ghats of Kashi. Unlike banquet halls or rooftop venues, an event boat offers a moving panorama of 84 historic ghats, the divine spectacle of Ganga Aarti, and the serene spiritual energy of one of the world's most sacred rivers.</p>
                <p>Visit Kashi is Varanasi's most trusted platform for <strong>luxury event boat bookings on the Ganga</strong>, with 5+ years of experience, 4.9-star Google ratings, and 10,000+ satisfied guests.</p>

                <h3 class="vkbd-sub-title">Types of Events We Organise on the Ganga</h3>

                <h4 class="vkbd-h4-title">🎂 Birthday Party Boat in Varanasi</h4>
                <p>Celebrate your birthday or your loved one's special day on the sacred Ganga. Our <strong>birthday party boat in Varanasi</strong> can be decorated with balloons, flowers, and LED lights. Arrange a custom cake, music system, photography, and catering — all while the boat glides past the iconic ghats of Kashi. A once-in-a-lifetime birthday experience that guests will never forget.</p>

                <h4 class="vkbd-h4-title">💑 Anniversary Celebration on the Ganga</h4>
                <p>Mark your wedding anniversary or a milestone celebration with a private <strong>anniversary boat ride in Varanasi</strong>. Watch the sun set over the Ganga as Dashashwamedh Ghat lights up with the evening Ganga Aarti — a magical romantic backdrop for couples. Customised decor, candle-lit dinners, and flower arrangements are available on request.</p>

                <h4 class="vkbd-h4-title">💼 Corporate Event Boat in Varanasi</h4>
                <p>Looking for a unique venue for your <strong>corporate event in Varanasi</strong>? Our event boats can accommodate corporate teams for team dinners, product launches, client entertainment, and off-site meetings. The combination of the Ganga's spiritual setting and the exclusivity of a private boat creates an unparalleled experience for corporate groups from across India and the world.</p>

                <h4 class="vkbd-h4-title">🌸 Wedding Functions &amp; Pre-Wedding Shoots</h4>
                <p>The Ganga ghats of Varanasi are among the most photographed backdrops in India. A <strong>wedding event boat in Varanasi</strong> offers an unmatched setting for pre-wedding photoshoots, sangeet nights, haldi ceremonies, and intimate wedding receptions on the river. The reflection of the ghats in the Ganga at golden hour creates stunning visuals for wedding photography and reels.</p>

                <h4 class="vkbd-h4-title">🙏 Spiritual &amp; Puja Events on the Ganga</h4>
                <p>Perform special pujas, havan ceremonies, and spiritual gatherings on the Ganga from your private event boat. Whether it's a Satyanarayan puja, a family religious gathering, or a spiritual retreat, the sanctity of the Ganga makes it the most auspicious venue in Varanasi. Our team can arrange pandit, flowers, and puja materials on request.</p>

                <h4 class="vkbd-h4-title">🎵 Music &amp; Cultural Events</h4>
                <p>Host a private classical music evening, baul performance, or cultural programme on the river as your floating stage. With the ghats as your backdrop and the Ganga as your audience, a <strong>music event boat in Varanasi</strong> creates an atmosphere that resonates with the soul of Banaras. We can arrange live tabla, sitar, and classical vocal performances on board.</p>

                <h4 class="vkbd-h4-title">📸 Photography &amp; Film Shoots</h4>
                <p>Varanasi from the river offers some of the most iconic photography locations in Asia. Our <strong>event boat for photography shoots in Varanasi</strong> gives photographers exclusive access to the 84 ghats from the water — the cremation ghats at Manikarnika, the golden ghats at sunrise, and the fire of Ganga Aarti at dusk. Popular with travel photographers, content creators, and film production crews.</p>

                <h4 class="vkbd-h4-title">🌅 Sunset Cruise Events on the Ganga</h4>
                <p>Nothing compares to watching the sun sink behind the ancient ghats of Varanasi from the middle of the Ganga. Our <strong>sunset cruise events</strong> are especially popular for couples, travel groups, and photography enthusiasts. The golden light reflecting off the ghats between 5:00 PM and 6:30 PM is widely considered the golden hour of Varanasi — best witnessed from the river.</p>

                <h3 class="vkbd-sub-title">Safety Standards &amp; Certifications</h3>
                <p>All Visit Kashi event boats are certified and maintained to the highest safety standards. Every boat carries <strong>government-approved life jackets</strong> for all passengers. Our boatmen are licensed by the <strong>Varanasi Boat Association</strong> and have an average of 10+ years of experience on the Ganga. We conduct pre-event safety checks on all equipment and follow all navigation guidelines set by the Uttar Pradesh Government.</p>

                <h3 class="vkbd-sub-title">Event Boat Booking Process</h3>
                <p>Booking your private event boat with Visit Kashi is simple and hassle-free:</p>
                <ol class="vkbd-steps">
                    <li><strong>Fill the form</strong> on this page with your event date, type, and guest count.</li>
                    <li>Our team <strong>contacts you on WhatsApp within 30 minutes</strong> to confirm availability and discuss requirements.</li>
                    <li>We share a <strong>customised quote</strong> based on your event type, duration, decor needs, and group size.</li>
                    <li>A <strong>small advance payment</strong> confirms your booking. Balance can be paid on the day of the event.</li>
                    <li>On the event day, <strong>our team meets you at the ghat</strong>, assists with boarding, and ensures your event runs perfectly.</li>
                </ol>
            </div>
            @endif

            {{-- ── Watch the Experience — reusable video slider component ──
                 Combines this product's related YoutubeVideo rows with the
                 legacy single youtube_link field (if set) into one list, so
                 the section never renders twice for the same product. ── --}}
            @php
                $experienceVideos = isset($youtubeVideos) ? $youtubeVideos : collect();
                if ($experienceVideos->isEmpty() && $product->youtube_link) {
                    $experienceVideos = collect([new \App\Models\YoutubeVideo(['youtube_url' => $product->youtube_link, 'title' => $product->name])]);
                }
            @endphp
            @if($experienceVideos->isNotEmpty())
            <div class="vkbd-divider"></div>
            <x-video-slider :videos="$experienceVideos" :item-name="$product->name" />
            @endif

            {{-- ── Instagram Reels ── --}}
            @isset($instagramReels)
            @if($instagramReels->isNotEmpty())
            <div class="vkbd-divider"></div>
            <h2 class="vkbd-section-title">Instagram Reels &amp; Event Highlights</h2>
            <div class="vkbd-ig-grid">
                @foreach($instagramReels as $reel)
                <a href="{{ $reel->reel_url }}" target="_blank" rel="noopener noreferrer" class="vkbd-ig-card" aria-label="{{ $reel->title ?? 'View Instagram Reel' }}">
                    @if($reel->thumbnail)
                        <img src="{{ Storage::url($reel->thumbnail) }}" alt="{{ e($reel->title ?? $product->name.' – Instagram Reel') }}" loading="lazy">
                    @else
                        <div class="vkbd-ig-placeholder"><i class="fa fa-instagram"></i></div>
                    @endif
                    <div class="vkbd-ig-overlay">
                        <i class="fa fa-instagram"></i>
                        @if($reel->title)<span>{{ $reel->title }}</span>@endif
                    </div>
                </a>
                @endforeach
            </div>
            @endif
            @endisset

            <div class="vkbd-divider"></div>

            {{-- ── Trust & Reviews Section ── --}}
            <div class="vkbd-trust-section">
                <h2 class="vkbd-section-title">{{ $isPrivateGangaAartiMotorBoat ? 'Why Book Your Private Ganga Aarti Boat with Visit Kashi?' : ($isMorningBoatRide ? 'Why Choose This Morning Boat Ride with Visit Kashi?' : ($isEveningBoatRide ? 'Why Book Your Evening Boat Ride with Visit Kashi?' : 'Why 10,000+ Guests Trust Visit Kashi')) }}</h2>
                @if($isEveningBoatRide)
                <p>If you're looking for an experienced local service for boat booking in Varanasi, Visit Kashi is a trusted choice for arranging evening boat rides, Ganga Aarti boat rides, private boats and other Ganga experiences — with around 10 years of experience in Varanasi travel services covering evening, sunset, morning and private boat rides, alongside wider Varanasi travel services like cab, hotel and tour package booking.</p>
                @endif
                <div class="vkbd-trust-grid">
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#9733;</div>
                        <h3>4.9 Google Rating</h3>
                        <p>Rated 4.9/5 by 248+ verified guests on Google. Consistently top-rated boat service in Varanasi.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#128274;</div>
                        <h3>100% Safe &amp; Certified</h3>
                        <p>Government-approved life jackets, licensed boatmen, and Varanasi Boat Association certified operations.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#9989;</div>
                        <h3>Instant Confirmation</h3>
                        <p>WhatsApp confirmation within 30 minutes of booking. No long waits, no uncertainty about your booking.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#128176;</div>
                        <h3>Best Price Guarantee</h3>
                        <p>We match or beat any price for the same boat experience. Transparent pricing with no hidden charges.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#127775;</div>
                        <h3>5+ Years Experience</h3>
                        <p>Operating since 2017 with 10,000+ happy guests from India and abroad. Local experts with deep Varanasi knowledge.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#128241;</div>
                        <h3>24/7 Support</h3>
                        <p>Our team is available from 5 AM to 10 PM, 7 days a week on WhatsApp, phone, and email for all queries.</p>
                    </div>
                </div>

                {{-- Review Cards --}}
                <div class="vkbd-reviews">
                    <div class="vkbd-review-card">
                        <div class="vkbd-review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"We booked the event boat for our anniversary and it was absolutely magical. The Ganga Aarti from the boat, the decorated setting, the professionalism — 10/10!"</p>
                        <div class="vkbd-review-author"><strong>Priya Mehta</strong> <span>· Delhi</span></div>
                    </div>
                    <div class="vkbd-review-card">
                        <div class="vkbd-review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"Organised a corporate team dinner on the event boat. The whole team loved it. Best venue decision we've ever made. Visit Kashi's team was extremely helpful throughout."</p>
                        <div class="vkbd-review-author"><strong>Rajesh Kumar</strong> <span>· Mumbai</span></div>
                    </div>
                    <div class="vkbd-review-card">
                        <div class="vkbd-review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"Birthday party on the Ganga — my daughter was speechless! Balloons, cake, music, and the ghats of Varanasi as backdrop. This is the only way to celebrate in Kashi!"</p>
                        <div class="vkbd-review-author"><strong>Sunita Agarwal</strong> <span>· Lucknow</span></div>
                    </div>
                </div>
            </div>

            <div class="vkbd-divider"></div>

            {{-- FAQ --}}
            @if($isPrivateGangaAartiMotorBoat)
            <h2 class="vkbd-section-title">Frequently Asked Questions — Private Motor Boat for Evening Ganga Aarti</h2>
            <div class="vkbd-faq">
                @php
                $faqs = [
                    ['How can I book a private motor boat for Evening Ganga Aarti in Varanasi?',
                     'Fill in the booking form on this page with your travel date, time slot, number of guests and pickup ghat, then submit — our team confirms your booking on WhatsApp or by phone call. You can also message or call us directly to book.'],
                    ['How much does a private motor boat for Ganga Aarti cost?',
                     'The price starts at ₹'.($displayPrice > 0 ? number_format($displayPrice) : '3,499').'/- for up to 10 persons on a private boat. Additional passengers beyond 10 are charged ₹500 per person. Final pricing can vary by date and availability — our team confirms the exact amount on WhatsApp before you book.'],
                    ['What time should I report for the Evening Ganga Aarti boat ride?',
                     'Reporting time is around 5:30 PM at your selected pickup ghat, with the boat departing at approximately 5:45 PM. Our team confirms the exact timing for your date at the time of booking.'],
                    ['Can I book a private boat for my family?',
                     'Yes. The boat is private for your group only — ideal for families, couples and small groups. Life jackets are provided for every passenger, including children.'],
                    ['How many people can travel in the private motor boat?',
                     'The base price includes up to 10 persons. Larger groups can be accommodated for an additional ₹500 per extra person — select your group size in the booking form for an instant price estimate.'],
                    ['Can I see Ganga Aarti from the boat?',
                     'Yes. During the ceremony, the boat is positioned on the river in front of the Ganga Aarti at Dashaswamedh / Lalita Ghat, giving you an open river view of the diyas, chants and temple bells.'],
                    ['Which ghat is best for watching Ganga Aarti from a boat?',
                     'The Ganga Aarti is performed at Dashashwamedh Ghat and Rajendra Prasad Ghat, and this is where your private boat is positioned during the 7:00 PM – 7:45 PM ceremony.'],
                    ['Can I book a private boat from Assi Ghat?',
                     'Yes, Assi Ghat is one of the supported pickup points for this private evening Ganga Aarti boat ride.'],
                    ['Can I book a private boat from Dashashwamedh Ghat?',
                     'Yes, Dashaswamedh Ghat is a supported pickup point, and it is also where the boat is positioned during the Aarti ceremony.'],
                    ['Can I book a private boat from Namo Ghat?',
                     'Yes, Namo Ghat is one of the supported pickup points for this private motor boat ride.'],
                    ['Does the private boat cover the 84 Ghats of Varanasi?',
                     'The full route runs along the Assi Ghat to Namo Ghat riverfront stretch, covering the historic ghats of Kashi along the way. Exact ghats seen depend on your pickup point and route on the day.'],
                    ['How long is the evening boat ride?',
                     'The ride runs for around two hours, from approximately 5:45 PM to 7:45 PM, including the Ganga Aarti viewing.'],
                    ['Is the boat private or shared?',
                     'This boat is completely private — it is not shared with other travellers or groups.'],
                    ['Are life jackets provided?',
                     'Yes, life jackets are provided for every passenger, including children.'],
                    ['Can I book the boat online?',
                     'Yes, you can book online using the form on this page. Our team confirms availability and pricing on WhatsApp or by phone.'],
                    ['How do I confirm my booking?',
                     'After you submit the enquiry form (or message us on WhatsApp), our team checks availability for your chosen date and time slot and confirms the booking with you directly.'],
                    ['Can I contact Visit Kashi on WhatsApp?',
                     'Yes, use the "Send Enquiry on WhatsApp" button on this page, or message/call us directly — our team is available to help with bookings and questions.'],
                    ['Is the price fixed for all dates?',
                     'The base price shown on this page applies under normal availability. On high-demand dates or festivals, pricing and boat availability may vary — our team confirms the exact price for your date on WhatsApp.'],
                    ['Can I book the boat on the same day?',
                     'Same-day booking may be possible subject to availability, especially in high season. Contact us on WhatsApp or by phone to check availability for today or tomorrow.'],
                    ['What happens if weather conditions affect the boat ride?',
                     'River and boat operations depend on safe weather and water conditions. If conditions are unsafe on your travel date, our team will advise on rescheduling — contact us on WhatsApp for the current status before you travel.'],
                ];
                @endphp
                @foreach($faqs as $i => $faq)
                <div class="vkbd-faq-item {{ $i === 0 ? 'open' : '' }}">
                    <div class="vkbd-faq-q" onclick="vkFaqToggle(this)">
                        <h3>{{ $faq[0] }}</h3>
                        <div class="vkbd-faq-icon">&#8964;</div>
                    </div>
                    <div class="vkbd-faq-a">{{ $faq[1] }}</div>
                </div>
                @endforeach
            </div>

            {{-- JSON-LD: FAQPage — mirrors the FAQ section directly above, exactly --}}
            <script type="application/ld+json">
            {!! json_encode([
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => array_map(fn($f) => [
                    '@type'          => 'Question',
                    'name'           => $f[0],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
                ], $faqs),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
            </script>
            @elseif($isDashaswamedhGhatPickupBoat)
            <h2 class="vkbd-section-title">Frequently Asked Questions About Private Motor Boat &amp; Ganga Aarti Boat Booking in Varanasi</h2>
            <div class="vkbd-faq">
                @php
                $faqs = [
                    ['How can I book a private motor boat for Evening Ganga Aarti in Varanasi?',
                     'Fill in the booking form on this page with your travel date, number of guests and pickup preference, then submit — our team confirms your booking on WhatsApp or by phone call. You can also message us directly on WhatsApp to book.'],
                    ['Can I book a private boat for Ganga Aarti from Dashashwamedh Ghat?',
                     'Yes. This private motor boat is set up for pickup from Dashaswamedh Ghat, which is also where the boat is positioned during the evening Ganga Aarti ceremony.'],
                    ['How much does Ganga Aarti boat booking cost in Varanasi?',
                     'Pricing depends on the route and group size — see the price shown above for the current starting rate on this boat. Our team confirms the exact amount for your date and group size on WhatsApp before you book.'],
                    ['What is the best boat for watching Ganga Aarti in Varanasi?',
                     'A private motor boat positioned near Dashashwamedh Ghat during the 7:00 PM – 7:45 PM ceremony gives you a clear river view of the Aarti without needing to stand in the crowd on the ghat steps.'],
                    ['What time should I book an evening boat ride for Ganga Aarti?',
                     'Reporting time is around 5:30 PM at Dashaswamedh Ghat, with the boat departing at approximately 5:45 PM and returning by around 7:45 PM, after the Ganga Aarti ceremony ends.'],
                    ['Is the motor boat private or shared for Ganga Aarti?',
                     'This boat is completely private — it is not shared with other travellers or groups.'],
                    ['Can I book a private motor boat online in Varanasi?',
                     'Yes, you can start your booking online using the form on this page. Our team follows up on WhatsApp or by phone to confirm availability and pricing.'],
                    ['How long is the Evening Ganga Aarti boat ride in Varanasi?',
                     'The ride runs for around two hours, from approximately 5:45 PM to 7:45 PM, including the time positioned for the Ganga Aarti.'],
                    ['Which ghat is best for a private Ganga Aarti boat ride?',
                     'Dashashwamedh Ghat is where the main evening Ganga Aarti is performed, which is why it is a popular pickup point and viewing position for this private boat ride.'],
                    ['Can I book a private motor boat from Dashashwamedh Ghat online?',
                     'Yes, this page is set up for private motor boat booking with pickup from Dashaswamedh Ghat — fill in the form or message us on WhatsApp to check availability for your date.'],
                    ['Which ghats can I see during this boat ride?',
                     'Depending on the route, this ride can cover ghats including Assi Ghat, Tulsi Ghat, Shivala Ghat, Harishchandra Ghat, Dashashwamedh Ghat, Manikarnika Ghat and on towards Namo Ghat.'],
                    ['Does this boat ride cover all 84 Ghats of Varanasi?',
                     'A longer route option covering the Assi Ghat to Namo Ghat stretch (the 84-ghats route) is available in addition to the shorter route — ask our team on WhatsApp for the current price difference between routes.'],
                    ['How many people can travel on this private motor boat?',
                     'This private motor boat is set up for small to mid-sized groups. Exact seating capacity and any charges for additional guests beyond the base group size are confirmed on WhatsApp at the time of booking.'],
                    ['Can I book this boat for a family or couple?',
                     'Yes, this private boat is suitable for families, couples and small groups travelling together, since the entire boat is booked for your group only.'],
                    ['Are life jackets provided on the boat?',
                     'Yes, life jackets are provided for passengers on board.'],
                    ['Can I book this boat on the same day?',
                     'Same-day booking may be possible depending on availability, especially on weekends and during high-demand periods. Contact us on WhatsApp or by phone to check availability for today or tomorrow.'],
                    ['How far in advance should I book this private boat?',
                     'Booking a few days ahead is recommended, particularly for weekends and festival dates when boats are in higher demand. Contact our team on WhatsApp to check current availability for your travel date.'],
                    ['What happens if weather conditions affect the boat ride?',
                     'River and boat operations depend on safe weather and water conditions. If conditions are unsafe on your travel date, our team will advise on the situation — contact us on WhatsApp before you travel to confirm.'],
                    ['How do I confirm my booking, and can I contact Visit Kashi on WhatsApp?',
                     'After you submit the enquiry form, our team checks availability for your date and confirms the booking with you directly. You can reach us on WhatsApp or by call at +91-7080109917, 7080109918 or 7080109919.'],
                    ['Why book a private Ganga Aarti boat with Visit Kashi?',
                     'For a private motor boat experience for Evening Ganga Aarti in Varanasi, travellers can book through Visit Kashi, a Varanasi-focused travel booking website offering boat, cab, hotel and tour services, with WhatsApp booking confirmation and a local team on call for your travel dates.'],
                ];
                @endphp
                @foreach($faqs as $i => $faq)
                <div class="vkbd-faq-item {{ $i === 0 ? 'open' : '' }}">
                    <div class="vkbd-faq-q" onclick="vkFaqToggle(this)">
                        <h3>{{ $faq[0] }}</h3>
                        <div class="vkbd-faq-icon">&#8964;</div>
                    </div>
                    <div class="vkbd-faq-a">{{ $faq[1] }}</div>
                </div>
                @endforeach
            </div>

            {{-- JSON-LD: FAQPage — mirrors the FAQ section directly above, exactly --}}
            <script type="application/ld+json">
            {!! json_encode([
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => array_map(fn($f) => [
                    '@type'          => 'Question',
                    'name'           => $f[0],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
                ], $faqs),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
            </script>
            @elseif($isMorningBoatRide)
            <h2 class="vkbd-section-title">Frequently Asked Questions — Morning Boat Ride in Varanasi</h2>
            <div class="vkbd-faq">
                @php
                $faqs = [
                    ['What time does the morning boat ride in Varanasi start?',
                     'The ride starts before sunrise and runs for about one to two hours. Since sunrise time changes through the year, we confirm the exact reporting time for your travel date on WhatsApp or by phone at the time of booking.'],
                    ['Is a morning boat ride the same as a sunrise boat ride?',
                     'Yes. A morning boat ride in Varanasi is timed to be on the water as the sun rises over the Ganga, so "morning" and "sunrise" boat ride describe the same experience.'],
                    ['How much does the morning boat ride cost?',
                     'The price starts at ₹'.($displayPrice > 0 ? number_format($displayPrice) : '3,499').'/- for up to 10 persons on a private boat. Additional passengers beyond 10 are charged ₹500 per person. Our team confirms the exact amount for your date on WhatsApp.'],
                    ['How long does the morning boat ride last?',
                     'The ride takes about one to two hours, covering the main ghats before returning to the pickup point.'],
                    ['Which ghats can I see on the morning boat ride?',
                     'The most popular ghats covered are Assi Ghat, Dashashwamedh Ghat, Manikarnika Ghat and Harishchandra Ghat. The exact ghats seen can vary slightly with your pickup point and conditions on the day.'],
                    ['Is the morning boat ride private or shared?',
                     'This boat is private for your group only — it is not shared with other travellers.'],
                    ['Can I see the sunrise from the boat?',
                     'Yes, the ride is timed so you are on the river as the sun rises over the Ganga.'],
                    ['Will I see cremation ghats during the ride?',
                     'Yes, the route passes Manikarnika Ghat and Harishchandra Ghat, which are active cremation ghats — please be respectful while observing from the boat.'],
                    ['How many people can travel on the morning boat ride?',
                     'The base price includes up to 10 persons. Larger groups can be accommodated for an additional ₹500 per extra person — select your group size in the booking form for an instant price estimate.'],
                    ['Can I book the morning boat ride on the same day?',
                     'Same-day booking may be possible depending on availability, especially in high season. Contact us on WhatsApp or by phone to check availability before you plan your morning.'],
                    ['How do I book this morning boat ride?',
                     'Fill in the booking form on this page with your date, number of guests and pickup ghat — our team confirms availability, exact reporting time and price on WhatsApp or by phone call. You can also reach Visit Kashi directly on WhatsApp or by phone at +91-7080109917, 7080109918 or 7080109919 to book.'],
                    ['Are life jackets provided on the morning boat ride?',
                     'Yes, life jackets are provided for every passenger, including children.'],
                    ['What happens if weather conditions affect the morning boat ride?',
                     'River and boat operations depend on safe weather and water conditions. If conditions are unsafe on your travel date, our team will advise on rescheduling — contact us on WhatsApp for the current status before you travel.'],
                ];
                @endphp
                @foreach($faqs as $i => $faq)
                <div class="vkbd-faq-item {{ $i === 0 ? 'open' : '' }}">
                    <div class="vkbd-faq-q" onclick="vkFaqToggle(this)">
                        <h3>{{ $faq[0] }}</h3>
                        <div class="vkbd-faq-icon">&#8964;</div>
                    </div>
                    <div class="vkbd-faq-a">{{ $faq[1] }}</div>
                </div>
                @endforeach
            </div>

            {{-- JSON-LD: FAQPage — mirrors the FAQ section directly above, exactly --}}
            <script type="application/ld+json">
            {!! json_encode([
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => array_map(fn($f) => [
                    '@type'          => 'Question',
                    'name'           => $f[0],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
                ], $faqs),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
            </script>
            @elseif($isEveningBoatRide)
            <h2 class="vkbd-section-title">Frequently Asked Questions — Evening Boat Ride in Varanasi</h2>
            <div class="vkbd-faq">
                @php
                $faqs = [
                    ['What is the best time for an evening boat ride in Varanasi?',
                     'The best time for an evening boat ride in Varanasi depends on whether you want sunset views, general evening sightseeing, or Ganga Aarti viewing. This ride starts a few hours before sunset and runs for about one to two hours, so the exact time shifts with the season — our team confirms the reporting time for your travel date on WhatsApp or by phone when you book.'],
                    ['Can I see Ganga Aarti from a boat in Varanasi?',
                     'This evening boat ride lets you see the Ganga Aarti from the river, since the route passes Dashashwamedh Ghat, where the main evening Aarti is held. The ceremony is generally held around 7:00 PM – 7:45 PM, and the boat\'s exact position and timing for viewing it are confirmed on WhatsApp when you book.'],
                    ['How much does an evening boat ride in Varanasi cost?',
                     'The evening boat ride in Varanasi starts at ₹'.($displayPrice > 0 ? number_format($displayPrice) : '3,499').'/- for a private boat covering up to 10 persons, with additional passengers charged ₹500 per person. Final pricing can vary slightly with date and availability, and our team confirms the exact amount on WhatsApp before you book.'],
                    ['How can I book a Ganga Aarti boat in Varanasi?',
                     'A Ganga Aarti boat in Varanasi can be booked through Visit Kashi by filling in the booking form on this page with your date, pickup ghat and number of guests, or by messaging us directly on WhatsApp. Our team confirms availability, exact timing and price before your booking is finalised.'],
                    ['Can I book a private evening boat in Varanasi?',
                     'This evening boat ride is already booked as a private boat for your group, not shared with other travellers. The base fare covers up to 10 persons, with extra guests charged ₹500 per person, which you can select in the booking form for an instant price estimate.'],
                    ['What is the difference between a sunset boat ride and a Ganga Aarti boat ride?',
                     'A sunset boat ride is timed around sunset and focuses on the changing light over the Ganga and the ghats, while a Ganga Aarti boat ride is timed so you\'re on the river during the evening Aarti ceremony at Dashashwamedh Ghat. This evening boat ride combines both, since it runs through the sunset hours and passes the Aarti location — for a boat positioned specifically for the full ceremony, our private motor boat for Evening Ganga Aarti is built around that exact timing.'],
                    ['Where can I board an evening boat in Varanasi?',
                     'Boarding for this evening boat ride is generally arranged from ghats including NAMO Ghat, Dashashwamedh Ghat and Ravidas Ghat. Our team confirms the exact pickup ghat closest to you when you book.'],
                    ['Can I book a boat near Dashashwamedh Ghat?',
                     'Dashashwamedh Ghat is one of the supported boarding points for this evening boat ride, and it\'s also the ghat where the main Ganga Aarti is held. For a private boat with dedicated pickup from Dashashwamedh Ghat, see the Dashashwamedh Ghat pickup option for the Evening Ganga Aarti boat on this site.'],
                    ['Can I book a boat near Assi Ghat?',
                     'Assi Ghat pickup is available through the dedicated private boat option for the Evening Ganga Aarti, which is set up specifically for pickup from Assi Ghat. Message our team on WhatsApp to confirm Assi Ghat pickup for your travel date.'],
                    ['How long is an evening Ganga boat ride?',
                     'An evening Ganga boat ride with Visit Kashi runs for about one to two hours, starting a few hours before sunset and continuing through the sunset and evening Aarti period. Exact duration on the day can vary slightly with river conditions and your chosen route.'],
                    ['Can I book an evening boat online in Varanasi?',
                     'You can book an evening boat online in Varanasi using the form on this page — enter your travel date, number of guests and pickup ghat, then submit. Our team follows up on WhatsApp or by phone to confirm availability and pricing; no advance payment is required to enquire.'],
                    ['Which boat is best for Ganga Aarti?',
                     'For watching the Ganga Aarti from the water, a boat positioned near Dashashwamedh Ghat during the ceremony gives the clearest view. This evening boat ride passes Dashashwamedh Ghat during its route, while the dedicated private motor boat for Evening Ganga Aarti is built specifically around the Aarti timing for travellers who want the boat positioned there for the full ceremony.'],
                    ['How many people can travel in a private boat?',
                     'This private evening boat ride includes up to 10 persons in the base price, with additional passengers charged ₹500 per person. Select your group size in the booking form for an instant price estimate.'],
                    ['Is advance booking recommended for an evening boat ride?',
                     'Advance booking is recommended for an evening boat ride, especially on weekends and during festival season when demand is higher. Same-day booking may be possible depending on availability — contact our team on WhatsApp or by phone to check before you travel.'],
                    ['How can I contact Visit Kashi for evening boat booking?',
                     'Visit Kashi can be reached for evening boat booking on WhatsApp or by phone at +91-7080109917, 7080109918 or 7080109919, or through the booking form on this page. Our team confirms availability, timing and price for your travel date.'],
                ];
                @endphp
                @foreach($faqs as $i => $faq)
                <div class="vkbd-faq-item {{ $i === 0 ? 'open' : '' }}">
                    <div class="vkbd-faq-q" onclick="vkFaqToggle(this)">
                        <h3>{{ $faq[0] }}</h3>
                        <div class="vkbd-faq-icon">&#8964;</div>
                    </div>
                    <div class="vkbd-faq-a">{{ $faq[1] }}</div>
                </div>
                @endforeach
            </div>

            {{-- JSON-LD: FAQPage — mirrors the FAQ section directly above, exactly --}}
            <script type="application/ld+json">
            {!! json_encode([
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => array_map(fn($f) => [
                    '@type'          => 'Question',
                    'name'           => $f[0],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
                ], $faqs),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
            </script>
            @else
            <h2 class="vkbd-section-title">Frequently Asked Questions — Event Boat Booking Varanasi</h2>
            <div class="vkbd-faq">
                @php
                $faqs = [
                    ['What is the cost of Event Boat Booking in Varanasi?',
                     'The cost of an event boat in Varanasi starts at ₹'.($displayPrice > 0 ? number_format($displayPrice) : '5,000').'/-. The final price depends on the duration, number of guests, decor requirements, catering, and type of event. Contact us for a custom quote for your event.'],
                    ['Can I organise a birthday party on a boat in Varanasi?',
                     'Yes! Birthday party boat bookings on the Ganga are one of our most popular events. We can arrange balloon decoration, flowers, birthday cake, music system, photography, and catering. Book at least 2–3 days in advance for decoration arrangements.'],
                    ['Is catering available on the event boat?',
                     'Yes, catering is available on request. We can arrange snacks, starter platters, full veg/non-veg meals, soft drinks, and special Banarasi food like kachori-sabzi, chaat, and paan. Catering must be pre-booked at least 24 hours in advance.'],
                    ['How many guests can be accommodated on an event boat?',
                     'Our event boats can comfortably accommodate 10 to 50 guests depending on the boat type selected. For larger groups (50+), we can arrange multiple boats sailing together. Contact us with your guest count for the right recommendation.'],
                    ['Is music and DJ allowed on event boats in Varanasi?',
                     'Yes, music systems are allowed on private event boats. You can bring your own Bluetooth speaker or request our music setup. For DJ and loud music events, prior permission from the Varanasi Boat Authority may be required — our team handles this for you.'],
                    ['Are event boats safe on the Ganga?',
                     'Absolutely. All Visit Kashi event boats carry government-approved life jackets for every passenger. Our boatmen are certified by the Varanasi Boat Association with 10+ years of river experience. Safety briefings are given before every event. We have a perfect safety record since 2017.'],
                    ['Can I book an event boat for a corporate event?',
                     'Yes, corporate event boat bookings are very popular with companies from across India. We handle team dinners, client entertainment, product launch events, and off-site team activities on the Ganga. We can provide basic audio-visual equipment, projector, and custom branding on request.'],
                    ['What is the best time for an event boat in Varanasi?',
                     'The two most popular slots are: Morning (5:30 AM – 8:00 AM) for the stunning Subah-e-Banaras sunrise experience, and Evening (4:30 PM – 7:30 PM) for the world-famous Ganga Aarti viewing from the river. Evening events with Aarti viewing are the most popular for celebrations.'],
                    ['Can I do a pre-wedding shoot on the event boat?',
                     'Yes! The Ganga ghats of Varanasi are one of the most sought-after pre-wedding shoot locations in India. Our event boat gives you exclusive water-level access to the 84 ghats — stunning for photos and reels. We can arrange floral decoration, props, and professional photography on board.'],
                    ['How far in advance should I book the event boat?',
                     'We recommend booking at least 3–5 days in advance for standard event bookings. For events requiring decoration, catering, music, or photography arrangements, please book 7–10 days ahead. During festival seasons (Diwali, Makar Sankranti, Holi, Dev Deepawali), book 2–3 weeks in advance.'],
                    ['Which ghats does the event boat depart from?',
                     'Event boats can depart from Dashashwamedh Ghat, Assi Ghat, Ravidas Ghat, or NAMO Ghat based on your preference and convenience. We recommend Dashashwamedh Ghat for evening events with Ganga Aarti viewing. Our team can assist with ghat transfer from your hotel.'],
                    ['Can I book an event boat for a wedding function?',
                     'Yes, we organise wedding functions, sangeet nights, ring ceremonies, and intimate wedding receptions on the Ganga. The Ganga boat provides an unforgettable and truly Banarasi wedding experience. Decoration, catering, pandit for rituals, and photography can all be arranged.'],
                    ['Is there a photographer available for the event boat?',
                     'Yes, we can arrange professional photographers and videographers for your event boat at an additional cost. Our photographers specialise in capturing the unique lighting conditions on the Ganga — the golden ghats, the Aarti flames, and the river reflections make for extraordinary shots.'],
                    ['What is the cancellation policy for event boat booking?',
                     'Cancellations made 48+ hours before the event receive a full refund. Cancellations within 24–48 hours attract a 25% cancellation fee. Same-day cancellations are non-refundable. In case of extreme weather or government restrictions, we reschedule at no charge.'],
                    ['Can I do a spiritual puja on the event boat?',
                     'Yes, spiritual pujas, havans, and religious gatherings on the Ganga are arranged regularly. We can provide a qualified pandit, puja materials, flowers, and all necessary items for the ritual. Performing puja on the Ganga from a private boat is considered especially auspicious in Hinduism.'],
                    ['Does the event boat cover all 84 ghats of Varanasi?',
                     'Yes, the standard event boat route covers the full 7 km stretch from Assi Ghat to NAMO Ghat, passing all 84 historic ghats of Varanasi. For events focused on a specific area (like Dashashwamedh Ghat for Aarti viewing), we can customise the route and anchoring position.'],
                    ['Are there any age restrictions for the event boat?',
                     'No, there are no age restrictions. Event boats are family-friendly and welcome guests of all ages from infants to senior citizens. Life jackets in all sizes (including children) are available. Children under 12 must be accompanied by an adult and must wear life jackets throughout.'],
                    ['Can the event boat be decorated for festivals like Diwali or Holi?',
                     'Yes! Festival-themed event boats are extremely popular. For Diwali, we decorate the boat with diyas and flower garlands — sailing during Dev Deepawali on the Ganga is a once-in-a-lifetime experience. For Holi, we arrange a safe, eco-friendly colour celebration on the river. Book months in advance for festival dates.'],
                    ['What payment methods are accepted for event boat booking?',
                     'We accept all digital payment methods: UPI (GPay, PhonePe, Paytm), Net Banking, NEFT/RTGS, and credit/debit cards. Cash payments are also accepted at the ghat. A 10–20% advance confirms your booking; the balance is collected on the event day.'],
                    ['What happens if it rains on the day of the event?',
                     'Light rain during monsoon (July–September) actually adds to the mystical experience of the Ganga and most guests enjoy it. We provide rain covers and umbrellas on board. For heavy rain or thunderstorms, we monitor weather forecasts and reschedule your event to the next available date at no extra charge.'],
                ];
                @endphp
                @foreach($faqs as $i => $faq)
                <div class="vkbd-faq-item {{ $i === 0 ? 'open' : '' }}">
                    <div class="vkbd-faq-q" onclick="vkFaqToggle(this)">
                        <h3>{{ $faq[0] }}</h3>
                        <div class="vkbd-faq-icon">&#8964;</div>
                    </div>
                    <div class="vkbd-faq-a">{{ $faq[1] }}</div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="vkbd-divider"></div>

            {{-- ── Internal Linking / Related Services ── --}}
            <div class="vkbd-related-services">
                <h2 class="vkbd-section-title">Explore More Boat &amp; Travel Services in Varanasi</h2>
                <div class="vkbd-services-grid">
                    <a href="{{ url('/boat') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#9975;</div>
                        <h3>Ganga Boat Rides</h3>
                        <p>Private boat rides covering all 84 ghats of Varanasi</p>
                    </a>
                    <a href="{{ url('/packages') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#127758;</div>
                        <h3>Varanasi Tour Packages</h3>
                        <p>Curated 1–5 day Varanasi sightseeing packages</p>
                    </a>
                    <a href="{{ url('/hotels') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#127968;</div>
                        <h3>Hotels in Varanasi</h3>
                        <p>Verified budget to luxury hotels near the ghats</p>
                    </a>
                    <a href="{{ url('/cab') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#128663;</div>
                        <h3>Cab Booking Varanasi</h3>
                        <p>Reliable cab service for airport, station &amp; city tours</p>
                    </a>
                    <a href="{{ url('/things-to-do') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#10024;</div>
                        <h3>Things to Do in Varanasi</h3>
                        <p>Top experiences — ghats, temples, food &amp; culture</p>
                    </a>
                    <a href="{{ url('/boat') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#127751;</div>
                        <h3>Ganga Aarti Boat Ride</h3>
                        <p>Watch the divine Ganga Aarti from the river</p>
                    </a>
                </div>
            </div>

        </div>{{-- /.vkbd-main --}}

        {{-- ── SIDEBAR (desktop) / BOOKING POPUP (mobile) ──────── --}}
        {{-- On desktop: overlay is position:static → acts as grid column  --}}
        {{-- On mobile:  overlay is position:fixed  → bottom-sheet popup   --}}
        <div class="vkbd-popup-overlay" id="vkbdBookingPopup">
            <div class="vkbd-popup-sheet">
                <div class="vkbd-popup-handle">
                    <div class="vkbd-popup-handle-bar"></div>
                    <button class="vkbd-popup-close" onclick="vkbdClosePopup()" aria-label="Close">✕</button>
                </div>
                <div class="vkbd-popup-body">
        <div class="vkbd-sidebar">

            {{-- ── Booking Card ── --}}
            <div class="vkbd-card">

                {{-- Header --}}
                <div class="vkbd-card-header">
                    <div class="vkbd-card-header-top">
                        <div>
                            <h3>Book This Boat Ride</h3>
                            <p>Fill the form — we'll confirm on WhatsApp</p>
                        </div>
                        @if($isDevDiwali)
                        <div class="vkbd-align-right">
                            <span class="vkbd-price-pill vkbd-amber-gradient-bg">🏷️ Best Price Guaranteed</span>
                        </div>
                        @elseif($displayPrice > 0)
                        <div class="vkbd-card-price-wrap">
                            @if($hasDiscount)
                            <span class="vkbd-card-price-old">₹{{ number_format($product->base_price) }}</span>
                            @endif
                            <span class="vkbd-card-price">₹{{ number_format($displayPrice) }}</span>
                            <span class="vkbd-card-price-sub">/ trip</span>
                        </div>
                        @endif
                    </div>
                    <div class="vkbd-trust-pills">
                        <span>🔒 Secure</span>
                        <span>📱 WhatsApp Confirm</span>
                    </div>
                </div>

                {{-- Body --}}
                <div class="vkbd-card-body">

                    @if(session('success'))
                    <div class="vkbs-success">
                        <div class="vkbs-success-icon">✓</div>
                        <h4>Enquiry Sent Successfully!</h4>
                        <p>Our team will contact you on WhatsApp within 15 minutes to confirm your booking.</p>
                        <a href="tel:+91{{ preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917') }}" class="vkbs-success-call">📞 Call us now</a>
                    </div>
                    @else

                    @if($errors->any())
                    <div class="vkbs-error-box">
                        <strong>Please fix the following:</strong>
                        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form action="{{ route('enquiry.store') }}" method="POST" id="vkbsForm" novalidate>
                        @csrf
                        <input type="hidden" name="package_id"     value="{{ $product->id }}">
                        <input type="hidden" name="package_name"   value="{{ $product->name }}">
                        <input type="hidden" name="no_of_person"   id="vkbs_persons_hidden" value="2">
                        <input type="hidden" name="children_count" id="vkbs_children_hidden" value="0">
                        <input type="hidden" name="time_slot"      id="vkbs_slot_hidden" value="">
                        <input type="hidden" name="message"        id="vkbs_message_hidden">
                        @if(!$isDevDiwali)<input type="hidden" name="booking_amount" value="{{ $displayPrice ?? 0 }}">@endif

                        {{-- 1. Travel Date --}}
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_date">Travel Date <span class="vkbs-req">*</span></label>
                            @if($isDevDiwali)
                            <input type="text" class="vkbs-input" value="24 November 2026" readonly>
                            <input type="hidden" name="arrival_date" value="2026-11-24">
                            @else
                            <input type="date" id="vkbs_date" name="arrival_date"
                                   class="vkbs-input" required
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('arrival_date', date('Y-m-d')) }}">
                            @endif
                        </div>

                        {{-- 2. Time Slot (hidden for Dev Diwali) --}}
                        @if(!$isDevDiwali)
                        <div class="vkbs-group">
                            <label class="vkbs-label">Time Slot <span class="vkbs-req">*</span></label>
                            <div class="vkbs-slots">
                                <div class="vkbs-slot-opt">
                                    <input type="radio" name="vkbs_slot" id="vkbs_slot_morning"
                                           value="Morning (06:00 AM – 08:00 AM)">
                                    <label for="vkbs_slot_morning">
                                        <span class="vkbs-slot-icon">🌅</span>
                                        <span class="vkbs-slot-name">Morning</span>
                                        <span class="vkbs-slot-time">06:00 AM – 08:00 AM</span>
                                    </label>
                                </div>
                                <div class="vkbs-slot-opt">
                                    <input type="radio" name="vkbs_slot" id="vkbs_slot_evening"
                                           value="Evening (05:30 PM – 07:30 PM)" checked>
                                    <label for="vkbs_slot_evening">
                                        <span class="vkbs-slot-icon">🪔</span>
                                        <span class="vkbs-slot-name">Evening</span>
                                        <span class="vkbs-slot-time">05:30 PM – 07:30 PM</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 3. Full Name --}}
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_name">Full Name <span class="vkbs-req">*</span></label>
                            <input type="text" id="vkbs_name" name="name"
                                   class="vkbs-input" required autocomplete="name"
                                   value="{{ old('name') }}">
                        </div>

                        {{-- 3. Mobile --}}
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_phone">Mobile <span class="vkbs-req">*</span></label>
                            <div class="vkbs-phone-wrap">
                                <span class="vkbs-phone-prefix">+91</span>
                                <input type="tel" id="vkbs_phone" name="phone"
                                       class="vkbs-input vkbs-input-phone" required autocomplete="tel"
                                       inputmode="numeric" maxlength="10"
                                       value="{{ old('phone') }}">
                            </div>
                        </div>

                        {{-- 4. WhatsApp Number (same as tick) --}}
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_whatsapp">WhatsApp Number</label>
                            <div class="vkbs-phone-wrap">
                                <span class="vkbs-phone-prefix">+91</span>
                                <input type="tel" id="vkbs_whatsapp"
                                       class="vkbs-input vkbs-input-phone" autocomplete="tel"
                                       inputmode="numeric" maxlength="10">
                            </div>
                            <label class="vkbs-same-check" for="vkbs_wa_same">
                                <input type="checkbox" id="vkbs_wa_same">
                                <span class="vkbs-same-box"></span>
                                Same as mobile number
                            </label>
                        </div>

                        {{-- 5 & 6. Persons counter (Dev Diwali: single Persons; standard: Adults + Children) --}}
                        @if($isDevDiwali)
                        <div class="vkbs-group">
                            <span class="vkbs-label">Persons <span class="vkbs-req">*</span></span>
                            <div class="vkbs-counter" id="vkbs_persons_wrap">
                                <button type="button" id="vkbs_pm" aria-label="Decrease">−</button>
                                <input type="number" id="vkbs_persons_disp" value="2" readonly>
                                <button type="button" id="vkbs_pp" aria-label="Increase">+</button>
                            </div>
                        </div>

                        {{-- Dev Diwali: Best Price badge --}}
                        <div class="vkbs-price-box vkbs-price-box--amber">
                            <span class="vkbs-best-price-text">🏷️ Best Price Guaranteed · Enquiry sent to our team</span>
                        </div>
                        @else
                        <div class="vkbs-row2">
                            <div class="vkbs-group">
                                <span class="vkbs-label">Adults <span class="vkbs-req">*</span></span>
                                <div class="vkbs-counter" id="vkbs_persons_wrap">
                                    <button type="button" id="vkbs_pm" aria-label="Decrease">−</button>
                                    <input type="number" id="vkbs_persons_disp" value="2" readonly>
                                    <button type="button" id="vkbs_pp" aria-label="Increase">+</button>
                                </div>
                                <div class="vkbs-cap-hint" id="vkbs_cap_hint"></div>
                            </div>
                            <div class="vkbs-group">
                                <span class="vkbs-label">Children <span class="vkbs-cap-hint">&lt;10 yrs</span></span>
                                <div class="vkbs-counter">
                                    <button type="button" id="vkbs_cm" aria-label="Decrease">−</button>
                                    <input type="number" id="vkbs_children_disp" value="0" readonly>
                                    <button type="button" id="vkbs_cp" aria-label="Increase">+</button>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 7. Price Estimate (hidden for Dev Diwali) --}}
                        @if($displayPrice > 0 && !$isDevDiwali)
                        <div class="vkbs-price-box" id="vkbsPriceBox">
                            <div class="vkbs-price-box-header">
                                <span>💰 Price Estimate</span>
                                <span class="vkbs-price-note">All-inclusive</span>
                            </div>
                            <div class="vkbs-price-line">
                                <span id="vkbs_pb_caption">Base fare (up to 10 persons)</span>
                                <span id="vkbs_pb_base">₹{{ number_format($displayPrice) }}</span>
                            </div>
                            <div class="vkbs-price-line vkbd-hidden" id="vkbs_pb_extra_row">
                                <span id="vkbs_pb_extra_label">Extra persons</span>
                                <span id="vkbs_pb_extra" class="vkbs-price-extra">₹0</span>
                            </div>
                            <div class="vkbs-price-line vkbs-price-total">
                                <span>Estimated Total</span>
                                <span id="vkbs_pb_total">₹{{ number_format($displayPrice) }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- 8. Nearest Pickup Ghat (fixed Ravidas Ghat for Dev Diwali) --}}
                        @if($isDevDiwali)
                        <input type="hidden" name="pickup_ghat" value="Ravidas Ghat">
                        @else
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_pickup">Nearest Pickup Ghat <span class="vkbs-req">*</span></label>
                            <select id="vkbs_pickup" name="pickup_ghat" class="vkbs-input" required>
                                <option value="">Select Ghat</option>
                                <option {{ old('pickup_ghat')=='Ravidas Ghat'?'selected':'' }}>Ravidas Ghat</option>
                                <option {{ old('pickup_ghat')=='Assi Ghat'?'selected':'' }}>Assi Ghat</option>
                                <option {{ old('pickup_ghat')=='Tulsi Ghat'?'selected':'' }}>Tulsi Ghat</option>
                                <option {{ old('pickup_ghat')=='Shivala Ghat'?'selected':'' }}>Shivala Ghat</option>
                                <option {{ old('pickup_ghat')=='Harishchandra Ghat'?'selected':'' }}>Harishchandra Ghat</option>
                                <option {{ old('pickup_ghat')=='Kedar Ghat'?'selected':'' }}>Kedar Ghat</option>
                                <option {{ (old('pickup_ghat')=='Dashaswamedh Ghat') || !old('pickup_ghat') ?'selected':'' }}>Dashaswamedh Ghat</option>
                                <option {{ old('pickup_ghat')=='Lalita Ghat'?'selected':'' }}>Lalita Ghat</option>
                                <option {{ old('pickup_ghat')=='Manikarnika Ghat'?'selected':'' }}>Manikarnika Ghat</option>
                                <option {{ old('pickup_ghat')=='Scindia Ghat'?'selected':'' }}>Scindia Ghat</option>
                                <option {{ old('pickup_ghat')=='NAMO Ghat'?'selected':'' }}>NAMO Ghat</option>
                            </select>
                        </div>
                        @endif

                        {{-- 9. Special Requests --}}
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_notes">Special Requests</label>
                            <textarea id="vkbs_notes" name="special_notes" class="vkbs-input" rows="2">{{ old('special_notes') }}</textarea>
                        </div>

                        {{-- Submit --}}
                        @if($isDevDiwali)
                        <button type="submit" class="vkbs-btn-wa vkbd-amber-gradient-bg" id="vkbs_submit">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                            Submit Enquiry
                        </button>
                        @else
                        <button type="submit" class="vkbs-btn-wa" id="vkbs_submit">
                            <svg width="18" height="18" viewBox="0 0 32 32" fill="currentColor"><path d="M16 3C8.82 3 3 8.82 3 16c0 2.43.65 4.7 1.78 6.67L3 29l6.55-1.72A13 13 0 0 0 16 29c7.18 0 13-5.82 13-13S23.18 3 16 3zm6.4 17.72c-.27.76-1.58 1.45-2.16 1.54-.56.09-1.26.13-2.04-.13a18.7 18.7 0 0 1-1.85-.68C13.6 20.3 11.6 17.9 11.45 17.7c-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.2 1.05-2.5.27-.3.6-.37.8-.37l.57.01c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.14.17-.3.38-.43.51-.14.14-.29.3-.12.58.17.28.74 1.22 1.59 1.97 1.09.97 2 1.27 2.29 1.41.28.14.45.12.61-.07.17-.2.72-.84.91-1.13.2-.28.39-.23.66-.14.27.09 1.71.8 2 .95.29.14.48.21.55.33.07.12.07.7-.2 1.46z"/></svg>
                            Send Enquiry on WhatsApp
                        </button>
                        @endif
                        {{-- FIX: use dynamic phone number --}}
                        <a href="tel:+91{{ preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917') }}" class="vkbs-btn-cb">
                            📞 Request a Call Back
                        </a>

                    </form>
                    @endif

                </div>{{-- /.vkbd-card-body --}}
            </div>{{-- /.vkbd-card --}}

            {{-- Best Deal CTA --}}
            @php $cNum = preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917'); @endphp
            <div class="vkbd-deal-card">
                <div class="vkbd-deal-card-title">🎉 Get the Best Deal</div>
                <div class="vkbd-deal-features">
                    <div class="vkbd-deal-feature"><i class="fa fa-check-circle"></i> Ideal for Groups &amp; Families</div>
                    <div class="vkbd-deal-feature"><i class="fa fa-check-circle"></i> Private Boat — No Sharing</div>
                    <div class="vkbd-deal-feature"><i class="fa fa-check-circle"></i> Advance Booking Discounts</div>
                    <div class="vkbd-deal-feature"><i class="fa fa-check-circle"></i> Free Pickup from Hotel / Ghat</div>
                </div>
                <div class="vkbd-deal-btns">
                    <a href="tel:+91{{ $cNum }}" class="vkbd-deal-btn call"><i class="fa fa-phone"></i> Call Us</a>
                    <a href="https://wa.me/91{{ $cNum }}?text={{ urlencode('Hi, I want to book a boat ride in Varanasi for '.$product->name.'. Please share details.') }}" target="_blank" rel="noopener" class="vkbd-deal-btn wa"><i class="fa fa-whatsapp"></i> WhatsApp</a>
                </div>
            </div>

        </div>{{-- /.vkbd-sidebar (popup inner) --}}
                </div>{{-- /.vkbd-popup-body --}}
            </div>{{-- /.vkbd-popup-sheet --}}
        </div>{{-- /.vkbd-popup-overlay --}}

    </div>{{-- /.vkbd-layout --}}

    <div class="vkbd-bottom-spacer"></div>

    {{-- ── Related Boats Scroll Section ──────────────────── --}}
    @isset($relatedBoats)
    @if($relatedBoats->isNotEmpty())
    <section class="vkbd-related" aria-label="Other Boat Rides in Varanasi">
        <div class="vkbd-related-head">
            <h2 class="vkbd-related-title">Other Boat Rides in Varanasi</h2>
            <a href="{{ route('product.list', optional($product->category)->slug ?? 'boat') }}" class="vkbd-related-link">View all</a>
        </div>
        <div class="vkbd-scroll-track" role="list">
            @foreach($relatedBoats as $rb)
            @php
                $rbImgs  = (!empty($rb->images) && is_array($rb->images)) ? $rb->images : [];
                $rbImg   = $rbImgs[0] ?? null;
                $rbBase  = $rb->base_price ?? 0;
                $rbDisc  = $rb->discounted_price ?? 0;
                $rbShow  = $rbDisc > 0 ? $rbDisc : $rbBase;
                $rbSlug  = optional($rb->subCategory)->slug ?? (optional($product->subCategory)->slug ?? 'motor-boat');
                $rbCat   = optional($product->category)->slug ?? 'boat';
            @endphp
            <article class="vkbd-rel-card" role="listitem" onclick="location.href='{{ route('product.detail', [$rbCat, $rbSlug, $rb->slug]) }}'">
                <div class="vkbd-rel-img">
                    @if($rbImg)
                    <img src="{{ asset('backend/admin/product_images/'.$rbImg) }}"
                         alt="{{ $rb->name }} – Boat Ride Varanasi"
                         loading="lazy"
                         onerror="this.src='{{ asset('backend/assets/images/placeholder.jpg') }}'">
                    @else
                    <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" alt="{{ $rb->name }}">
                    @endif
                    @if($rbShow > 0)
                    <span class="vkbd-rel-badge">₹{{ number_format($rbShow) }}</span>
                    @endif
                </div>
                <div class="vkbd-rel-body">
                    <h3 class="vkbd-rel-name">{{ $rb->name }}</h3>
                    @if($rbShow > 0)
                    <div class="vkbd-rel-price-row">
                        @if($rbDisc > 0 && $rbBase > $rbDisc)
                        <span class="vkbd-rel-old">₹{{ number_format($rbBase) }}</span>
                        @endif
                        <span class="vkbd-rel-price">₹{{ number_format($rbShow) }}</span>
                        <span class="vkbd-rel-sub">/ booking</span>
                    </div>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif
    @endisset

</div>{{-- /container --}}

{{-- Mobile sticky bar --}}
<div class="vkbd-mob-bar">
    <div class="vkbd-mob-bar-info">
        <div class="vkbd-mob-bar-label">{{ Str::limit($product->name, 28) }}</div>
        @if($isDevDiwali)
        <div class="vkbd-mob-bar-price vkbd-mob-bar-badge">Best Price Guaranteed</div>
        @elseif($displayPrice > 0)
        <div class="vkbd-mob-bar-price">From ₹{{ number_format($displayPrice) }}/-</div>
        @endif
    </div>
    <div class="vkbd-mob-btns">
        <button type="button" class="vkbd-mob-btn book" onclick="vkbdOpenPopup()">
            <i class="fa fa-calendar-check-o"></i> Enquiry Now
        </button>
        <a href="https://wa.me/917080109917?text=Hi%2C+I+want+to+book+{{ urlencode($product->name) }}" target="_blank" rel="noopener" class="vkbd-mob-btn wa">
            <i class="fa fa-whatsapp"></i>
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {

    /* ── Lightbox ── */
    var lbImgs = @json($imgUrls);
    var lbCur  = 0;
    window.vkLbOpen = function (i) {
        lbCur = i || 0;
        document.getElementById('vkLbImg').src = lbImgs[lbCur];
        document.getElementById('vkLbCap').textContent = (lbCur + 1) + ' / ' + lbImgs.length;
        document.getElementById('vkLb').classList.add('open');
    };
    window.vkLbClose = function () { document.getElementById('vkLb').classList.remove('open'); };
    window.vkLbNav   = function (d) {
        lbCur = (lbCur + d + lbImgs.length) % lbImgs.length;
        document.getElementById('vkLbImg').src = lbImgs[lbCur];
        document.getElementById('vkLbCap').textContent = (lbCur + 1) + ' / ' + lbImgs.length;
    };
    document.getElementById('vkLb').addEventListener('click', function (e) {
        if (e.target === this) vkLbClose();
    });
    document.addEventListener('keydown', function (e) {
        var lb = document.getElementById('vkLb');
        if (!lb.classList.contains('open')) return;
        if (e.key === 'ArrowLeft')  vkLbNav(-1);
        if (e.key === 'ArrowRight') vkLbNav(1);
        if (e.key === 'Escape')     vkLbClose();
    });

    /* ── FAQ accordion ── */
    window.vkFaqToggle = function (el) {
        var item = el.closest('.vkbd-faq-item');
        item.classList.toggle('open');
    };

    /* ── Persons / Children steppers ── */
    var persons  = 2;
    var children = 0;

    @if($isDevDiwali)
    function renderPersons() {
        document.getElementById('vkbs_persons_disp').value   = persons;
        document.getElementById('vkbs_persons_hidden').value = persons;
        document.getElementById('vkbs_pm').disabled = persons <= 1;
        document.getElementById('vkbs_pp').disabled = persons >= 200;
    }
    @else
    var BASE_PRICE   = {{ $displayPrice > 0 ? $displayPrice : 0 }};
    var INCLUDED     = 10;
    var EXTRA_RATE   = 500;

    function renderPersons() {
        document.getElementById('vkbs_persons_disp').value  = persons;
        document.getElementById('vkbs_persons_hidden').value = persons;
        document.getElementById('vkbs_pm').disabled = persons <= 1;
        document.getElementById('vkbs_pp').disabled = persons >= 50;

        if (BASE_PRICE > 0) {
            var extra     = Math.max(0, persons - INCLUDED);
            var extraAmt  = extra * EXTRA_RATE;
            var total     = BASE_PRICE + extraAmt;
            var pbExRow   = document.getElementById('vkbs_pb_extra_row');
            document.getElementById('vkbs_pb_total').textContent = '₹' + total.toLocaleString('en-IN');
            if (extra > 0) {
                pbExRow.style.display = 'flex';
                document.getElementById('vkbs_pb_extra_label').textContent = extra + ' extra person' + (extra > 1 ? 's' : '');
                document.getElementById('vkbs_pb_extra').textContent = '₹' + extraAmt.toLocaleString('en-IN');
            } else {
                pbExRow.style.display = 'none';
            }
            if (persons > INCLUDED) {
                document.getElementById('vkbs_cap_hint').textContent = 'Extra: +₹' + EXTRA_RATE + '/person beyond ' + INCLUDED;
            } else {
                document.getElementById('vkbs_cap_hint').textContent = INCLUDED + ' persons included in base fare';
            }
        }
    }

    function renderChildren() {
        document.getElementById('vkbs_children_disp').value = children;
        document.getElementById('vkbs_cm').disabled = children <= 0;
        document.getElementById('vkbs_cp').disabled = children >= 20;
    }

    document.getElementById('vkbs_cm').addEventListener('click', function () { if (children > 0) { children--; renderChildren(); document.getElementById('vkbs_children_hidden').value = children; } });
    document.getElementById('vkbs_cp').addEventListener('click', function () { if (children < 20){ children++; renderChildren(); document.getElementById('vkbs_children_hidden').value = children; } });
    renderChildren();
    @endif

    document.getElementById('vkbs_pm').addEventListener('click', function () { if (persons > 1)   { persons--; renderPersons(); } });
    document.getElementById('vkbs_pp').addEventListener('click', function () { if (persons < {{ $isDevDiwali ? 200 : 50 }}) { persons++; renderPersons(); } });

    renderPersons();

    @if(!$isDevDiwali)
    /* ── Initialise nice-select on ghat dropdown (if loaded) ── */
    if (typeof $ !== 'undefined' && $.fn.niceSelect) {
        $('#vkbs_pickup').niceSelect();

        /* Sync value change back to real select for validation */
        $('#vkbs_pickup').on('change', function () {
            var ns = $(this).next('.nice-select');
            if (this.value) {
                ns.css({ borderColor: '', boxShadow: '' });
            }
        });
    }

    /* ── Clear ghat error on change (native fallback) ── */
    document.getElementById('vkbs_pickup').addEventListener('change', function () {
        if (this.value) {
            this.style.borderColor = '';
            this.style.boxShadow   = '';
        }
    });
    @endif

    /* ── Phone digits only (10 max) ── */
    document.getElementById('vkbs_phone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

    @if(!$isDevDiwali)
    /* ── Sync slot hidden field (init + on change) ── */
    (function() {
        var checked = document.querySelector('input[name="vkbs_slot"]:checked');
        if (checked) document.getElementById('vkbs_slot_hidden').value = checked.value;
    })();
    document.querySelectorAll('input[name="vkbs_slot"]').forEach(function(r) {
        r.addEventListener('change', function() {
            document.getElementById('vkbs_slot_hidden').value = this.value;
        });
    });
    @endif

    /* ── WhatsApp "same as mobile" checkbox ── */
    var waSameChk = document.getElementById('vkbs_wa_same');
    var waInput   = document.getElementById('vkbs_whatsapp');
    var phoneInput= document.getElementById('vkbs_phone');
    if (waSameChk) {
        waSameChk.addEventListener('change', function () {
            if (this.checked) {
                waInput.value    = phoneInput.value;
                waInput.readOnly = true;
                waInput.style.background = '#f0fdf4';
                waInput.style.borderColor = '#10b981';
            } else {
                waInput.value    = '';
                waInput.readOnly = false;
                waInput.style.background = '';
                waInput.style.borderColor = '';
            }
        });
        // Keep WhatsApp in sync while typing phone (if same checked)
        phoneInput.addEventListener('input', function () {
            if (waSameChk.checked) waInput.value = this.value;
        });
    }

    /* ── Sync children hidden field ── */
    function syncChildren() {
        document.getElementById('vkbs_children_hidden').value = children;
    }

    /* ── Bundle all data into message + set hidden fields before submit ── */
    document.getElementById('vkbsForm').addEventListener('submit', function (e) {
        var notes = document.getElementById('vkbs_notes').value.trim();
        var wa    = document.getElementById('vkbs_whatsapp') ? document.getElementById('vkbs_whatsapp').value.trim() : '';

        @if($isDevDiwali)
        // Dev Diwali: no ghat validation, no slot, simple message
        document.getElementById('vkbs_persons_hidden').value = persons;
        var msg = 'Persons: ' + persons
                + '\nDate: 24 November 2026'
                + '\nPickup: Ravidas Ghat';
        if (wa && wa !== document.getElementById('vkbs_phone').value) msg += '\nWhatsApp: ' + wa;
        if (notes) msg += '\nSpecial Notes: ' + notes;
        document.getElementById('vkbs_message_hidden').value = msg.trim();
        @else
        var slot   = document.querySelector('input[name="vkbs_slot"]:checked');
        var pickup = document.getElementById('vkbs_pickup').value;

        // Validate ghat
        if (!pickup) {
            e.preventDefault();
            var sel    = document.getElementById('vkbs_pickup');
            var nsEl   = sel.nextElementSibling;
            var target = (nsEl && nsEl.classList.contains('nice-select')) ? nsEl : sel;
            target.style.borderColor = '#ef4444';
            target.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.15)';
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        document.getElementById('vkbs_slot_hidden').value     = slot ? slot.value : 'Not selected';
        document.getElementById('vkbs_children_hidden').value = children;

        var msg = 'Pickup Ghat: '          + pickup
                + '\nAdults: '             + persons
                + '\nChildren (<10 yrs): ' + children;
        if (wa && wa !== document.getElementById('vkbs_phone').value) msg += '\nWhatsApp: ' + wa;
        if (notes) msg += '\nSpecial Notes: ' + notes;
        if (slot) { msg = 'Time Slot: ' + slot.value + '\n' + msg; }

        document.getElementById('vkbs_message_hidden').value = msg.trim();
        document.getElementById('vkbs_pickup').style.borderColor = '';
        document.getElementById('vkbs_pickup').style.boxShadow   = '';
        @endif
    });

    @if(!$isDevDiwali)
    /* ── Set default date to today ── */
    var dateEl = document.getElementById('vkbs_date');
    if (dateEl && !dateEl.value) {
        var d = new Date();
        var mm = String(d.getMonth()+1).padStart(2,'0');
        var dd = String(d.getDate()).padStart(2,'0');
        dateEl.value = d.getFullYear()+'-'+mm+'-'+dd;
    }
    @endif

})();
</script>

<script>
/* ── Booking Popup (mobile Book Now) ── */
function vkbdOpenPopup() {
    var popup = document.getElementById('vkbdBookingPopup');
    if (popup) {
        popup.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
}
function vkbdClosePopup() {
    var popup = document.getElementById('vkbdBookingPopup');
    if (popup) {
        popup.classList.remove('open');
        document.body.style.overflow = '';
    }
}
/* Close on overlay backdrop click */
document.getElementById('vkbdBookingPopup').addEventListener('click', function(e) {
    if (e.target === this) vkbdClosePopup();
});
/* Close on Escape key */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') vkbdClosePopup();
});
</script>

{{-- Extended FAQ Schema with all 20 FAQs — suppressed for pages that already ship
     their own matching FAQPage schema right after their FAQ section above. --}}
@unless($isPrivateGangaAartiMotorBoat || $isDashaswamedhGhatPickupBoat || $isMorningBoatRide || $isEveningBoatRide)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @php
        $schemaFaqs = [
            ['What is the cost of Event Boat Booking in Varanasi?','The cost starts at ₹'.($displayPrice > 0 ? number_format($displayPrice) : '5,000').'/-. Final price depends on duration, guests, decor, and catering.'],
            ['Can I organise a birthday party on a boat in Varanasi?','Yes! We arrange balloon decoration, flowers, birthday cake, music, photography, and catering. Book 2–3 days in advance.'],
            ['Is catering available on the event boat?','Yes, snacks, full meals, and Banarasi food are available on request. Pre-book at least 24 hours in advance.'],
            ['How many guests can be accommodated?','10 to 50 guests per boat. For 50+ guests, multiple boats can sail together.'],
            ['Is music allowed on event boats?','Yes, music systems and Bluetooth speakers are allowed. DJ events may require prior permission which our team arranges.'],
            ['Are event boats safe on the Ganga?','Yes. Government-approved life jackets, certified boatmen, and pre-event safety checks ensure full safety.'],
            ['Can I book for a corporate event?','Yes, corporate dinners, client entertainment, and team activities are regularly organised on our event boats.'],
            ['What is the best time for an event boat?','Evening (4:30 PM – 7:30 PM) for Ganga Aarti, or Morning (5:30 AM – 8:00 AM) for sunrise experience.'],
            ['Can I do a pre-wedding shoot?','Yes, the Ganga ghats offer stunning backdrops. Decor, props, and photography can be arranged.'],
            ['How far in advance should I book?','3–5 days for standard bookings, 7–10 days for events with decoration and catering.'],
        ];
        @endphp
        @foreach($schemaFaqs as $i => $sfaq)
        {
            "@type": "Question",
            "name": "{{ addslashes($sfaq[0]) }}",
            "acceptedAnswer": {"@type": "Answer", "text": "{{ addslashes($sfaq[1]) }}"}
        }{{ $i < count($schemaFaqs)-1 ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endunless
@endpush
