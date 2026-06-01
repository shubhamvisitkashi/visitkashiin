@extends('frontend.layouts.app')

@section('meta')
    @isset($product)
        @php
            $metaTitle = $product->meta_title ?? $product->name;
            $metaDesc  = $product->meta_description ?? 'Book private boat ride in Varanasi. Witness Ganga Aarti from the river. Best price guaranteed.';
            $metaImg   = (!empty($product->images) && is_array($product->images))
                ? asset('backend/admin/product_images/'.$product->images[0])
                : asset('backend/assets/images/placeholder.jpg');
            $pageUrl   = url()->current();
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
                "reviewCount": "248",
                "bestRating": "5"
            }
        }
        </script>

        {{-- JSON-LD: FAQPage Schema --}}
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
                "reviewCount": "248",
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
            "serviceType": "Event Boat Booking",
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

        {{-- JSON-LD: Breadcrumb Schema --}}
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {"@type": "ListItem","position": 1,"name": "Home","item": "{{ url('/') }}"},
                {"@type": "ListItem","position": 2,"name": "{{ addslashes(optional($product->category)->name ?? 'Boat') }}","item": "{{ url('/boat') }}"},
                {"@type": "ListItem","position": 3,"name": "{{ addslashes($product->name) }}","item": "{{ $pageUrl }}"}
            ]
        }
        </script>
    @endisset
@endsection

@push('styles')
<link rel="preload" href="{{ asset('frontend/css/boat-detail.css') }}?v={{ filemtime(public_path('frontend/css/boat-detail.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/boat-detail.css') }}?v={{ filemtime(public_path('frontend/css/boat-detail.css')) }}"></noscript>
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
@endphp

{{-- Breadcrumb --}}
<div class="vkbd-breadcrumb">
    <div class="container">
        <a href="{{ url('/') }}">Home</a>
        <span>/</span>
        <a href="{{ route('product.sub.list', [$catSlug, $subSlug]) }}">{{ $subName }}</a>
        <span>/</span>
        {{ $product->name }}
    </div>
</div>

<div class="container" style="max-width:1200px; padding-top: 8px;">

    {{-- Title row --}}
    <div class="vkbd-title-row">
        <h1>{{ $product->name }}</h1>
        <div class="vkbd-meta">
            <span class="vkbd-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <span class="vkbd-rating">4.9</span>
            <span class="vkbd-meta-dot">&middot;</span>
            <span>248 Reviews</span>
            <span class="vkbd-meta-dot">&middot;</span>
            <a href="{{ route('product.sub.list', [$catSlug, $subSlug]) }}" class="vkbd-cat-link">{{ $subName }}</a>
            <span class="vkbd-meta-dot">&middot;</span>
            <span>Varanasi, Uttar Pradesh</span>
        </div>
    </div>

    {{-- Gallery --}}
    <div class="vkbd-gallery" id="vkbdGallery">
        <div class="vkbd-gallery-item vkbd-gallery-hero" onclick="vkLbOpen(0)">
            <img src="{{ $imgUrls[0] }}" alt="{{ $product->name }} – Photo 1" loading="eager"
                 onerror="this.src='{{ $fallback }}'">
        </div>
        @if(isset($imgUrls[1]))
        <div class="vkbd-gallery-item" onclick="vkLbOpen(1)">
            <img src="{{ $imgUrls[1] }}" alt="{{ $product->name }} – Photo 2" loading="lazy"
                 onerror="this.src='{{ $fallback }}'">
        </div>
        @endif
        @if(isset($imgUrls[2]))
        <div class="vkbd-gallery-item" onclick="vkLbOpen(2)">
            <img src="{{ $imgUrls[2] }}" alt="{{ $product->name }} – Photo 3" loading="lazy"
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

            {{-- Provider bar --}}
            <div class="vkbd-provider-bar">
                <div class="vkbd-provider-avatar">
                    <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                         alt="Visit Kashi Logo" class="vkbd-provider-logo" />
                </div>
                <div class="vkbd-provider-info">
                    <h4>Provided by Visit Kashi</h4>
                    <p>Verified Boat Service &nbsp;&middot;&nbsp; Best Price Guaranteed &nbsp;&middot;&nbsp; Instant WhatsApp Booking</p>
                </div>
            </div>

            <div class="vkbd-divider"></div>

            {{-- Highlights --}}
            <div class="vkbd-highlights">
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-ship"></i></div>
                    <div class="vkbd-highlight-text">
                        <h5>Private Boat – No Sharing, Complete Privacy</h5>
                        <p>The entire boat is exclusively yours. Ideal for families, couples, and small groups seeking a peaceful and private ride on the sacred Ganga.</p>
                    </div>
                </div>
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-map-marker"></i></div>
                    <div class="vkbd-highlight-text">
                        <h5>Ride Covering All 84 Ghats of Varanasi</h5>
                        <p>Cruise the full 7 km stretch from Assi Ghat to NAMO Ghat, passing all 84 historic ghats of Kashi including Dashashwamedh Ghat, Manikarnika Ghat, and Harishchandra Ghat.</p>
                    </div>
                </div>
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-star"></i></div>
                    <div class="vkbd-highlight-text">
                        <h5>Witness Evening Ganga Aarti from the River</h5>
                        <p>Experience the world-famous Ganga Aarti at Dashashwamedh Ghat from your private boat — no crowd, no standing, just a divine front-row view.</p>
                    </div>
                </div>
                <div class="vkbd-highlight-item">
                    <div class="vkbd-highlight-icon"><i class="fa fa-tag"></i></div>
                    <div class="vkbd-highlight-text">
                        <h5>Best Price Guarantee
                            @if($displayPrice > 0)
                            – Starting ₹{{ number_format($displayPrice) }}/-
                            @endif
                        </h5>
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

            <div class="vkbd-divider"></div>

            {{-- Specs table --}}
            <h2 class="vkbd-section-title">Varanasi Boat Ride – Pricing, Timings &amp; Booking Details</h2>
            <table class="vkbd-specs" itemscope itemtype="https://schema.org/Service">
                <tr>
                    <td class="vkbd-spec-label">Reporting Point</td>
                    <td class="vkbd-spec-val"><i class="fa fa-map-marker"></i>NAMO Ghat / Dashashwamedh Ghat / Ravidas Ghat</td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Ganga Aarti</td>
                    <td class="vkbd-spec-val"><i class="fa fa-clock-o"></i>Evening – Dashashwamedh Ghat <span class="vkbd-spec-note">(5:00 PM – 7:30 PM)</span></td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Price</td>
                    <td class="vkbd-spec-val">
                        <i class="fa fa-inr"></i>
                        @if($displayPrice > 0)
                            <strong style="color:#d4850a;font-size:16px;">₹{{ number_format($displayPrice) }}</strong>
                            @if($hasDiscount)
                            &nbsp;<span style="font-size:12px;color:#6b7280;text-decoration:line-through;">₹{{ number_format($product->base_price) }}</span>
                            @endif
                            &nbsp;<span style="font-size:12px;color:#6b7280;">private boat · up to 10 persons</span>
                        @else
                            <span style="color:#6b7280;">Price on request</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Capacity</td>
                    <td class="vkbd-spec-val"><i class="fa fa-users"></i>Up to 30 persons (extra @ ₹300/person)</td>
                </tr>
                <tr>
                    <td class="vkbd-spec-label">Duration</td>
                    <td class="vkbd-spec-val"><i class="fa fa-clock-o"></i>Approx. 2 Hours 30 Minutes</td>
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

            <div class="vkbd-divider"></div>

            {{-- What's included --}}
            <h2 class="vkbd-section-title">What's Included</h2>
            <ul class="vkbd-includes">
                <li><i class="fa fa-check-circle"></i> Life Jackets for all passengers</li>
                <li><i class="fa fa-check-circle"></i> Licensed &amp; experienced boatman</li>
                <li><i class="fa fa-check-circle"></i> Private boat – no sharing</li>
                <li><i class="fa fa-check-circle"></i> Full 84-Ghat Varanasi tour (Assi to NAMO)</li>
                <li><i class="fa fa-check-circle"></i> Evening Ganga Aarti viewing from the Ganga</li>
                <li><i class="fa fa-check-circle"></i> Photo stops at key scenic ghats</li>
            </ul>

            @if($product->youtube_link)
            <div class="vkbd-divider"></div>
            <h2 class="vkbd-section-title">Watch the Experience</h2>
            <div class="vkbd-video-wrap">
                <iframe src="{{ $product->youtube_link }}" allowfullscreen loading="lazy"></iframe>
            </div>
            @endif

            @if($product->map_location)
            <div class="vkbd-divider"></div>
            <h2 class="vkbd-section-title">Location</h2>
            <div class="vkbd-video-wrap vkbd-map-wrap">
                <iframe src="{{ $product->map_location }}" allowfullscreen style="border:0;"></iframe>
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

            {{-- ── YouTube Video Gallery ── --}}
            @isset($youtubeVideos)
            @if($youtubeVideos->isNotEmpty() || $product->youtube_link)
            <div class="vkbd-divider"></div>
            <h2 class="vkbd-section-title">Watch the Experience</h2>
            <div class="vkbd-yt-grid">
                @foreach($youtubeVideos as $vid)
                @if($vid->video_id)
                <div class="vkbd-yt-card" onclick="vkYtOpen('{{ $vid->embed_url }}')">
                    <div class="vkbd-yt-thumb">
                        <img src="{{ $vid->thumbnail }}" alt="{{ e($vid->title ?? $product->name.' – Video') }}" loading="lazy">
                        <div class="vkbd-yt-play">&#9654;</div>
                    </div>
                    @if($vid->title)<p class="vkbd-yt-title">{{ $vid->title }}</p>@endif
                </div>
                @endif
                @endforeach

                @if($youtubeVideos->isEmpty() && $product->youtube_link)
                <div class="vkbd-video-wrap">
                    <iframe src="{{ $product->youtube_link }}" allowfullscreen loading="lazy" title="{{ $product->name }} Video"></iframe>
                </div>
                @endif
            </div>
            @endif
            @endisset

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
                <h2 class="vkbd-section-title">Why 10,000+ Guests Trust Visit Kashi</h2>
                <div class="vkbd-trust-grid">
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#9733;</div>
                        <h4>4.9 Google Rating</h4>
                        <p>Rated 4.9/5 by 248+ verified guests on Google. Consistently top-rated boat service in Varanasi.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#128274;</div>
                        <h4>100% Safe &amp; Certified</h4>
                        <p>Government-approved life jackets, licensed boatmen, and Varanasi Boat Association certified operations.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#9989;</div>
                        <h4>Instant Confirmation</h4>
                        <p>WhatsApp confirmation within 30 minutes of booking. No long waits, no uncertainty about your booking.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#128176;</div>
                        <h4>Best Price Guarantee</h4>
                        <p>We match or beat any price for the same boat experience. Transparent pricing with no hidden charges.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#127775;</div>
                        <h4>5+ Years Experience</h4>
                        <p>Operating since 2017 with 10,000+ happy guests from India and abroad. Local experts with deep Varanasi knowledge.</p>
                    </div>
                    <div class="vkbd-trust-card">
                        <div class="vkbd-trust-icon">&#128241;</div>
                        <h4>24/7 Support</h4>
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
                        <span>{{ $faq[0] }}</span>
                        <div class="vkbd-faq-icon">&#8964;</div>
                    </div>
                    <div class="vkbd-faq-a">{{ $faq[1] }}</div>
                </div>
                @endforeach
            </div>

            <div class="vkbd-divider"></div>

            {{-- ── Internal Linking / Related Services ── --}}
            <div class="vkbd-related-services">
                <h2 class="vkbd-section-title">Explore More Boat &amp; Travel Services in Varanasi</h2>
                <div class="vkbd-services-grid">
                    <a href="{{ url('/boat') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#9975;</div>
                        <h4>Ganga Boat Rides</h4>
                        <p>Private boat rides covering all 84 ghats of Varanasi</p>
                    </a>
                    <a href="{{ url('/packages') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#127758;</div>
                        <h4>Varanasi Tour Packages</h4>
                        <p>Curated 1–5 day Varanasi sightseeing packages</p>
                    </a>
                    <a href="{{ url('/hotels') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#127968;</div>
                        <h4>Hotels in Varanasi</h4>
                        <p>Verified budget to luxury hotels near the ghats</p>
                    </a>
                    <a href="{{ url('/cab') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#128663;</div>
                        <h4>Cab Booking Varanasi</h4>
                        <p>Reliable cab service for airport, station &amp; city tours</p>
                    </a>
                    <a href="{{ url('/things-to-do') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#10024;</div>
                        <h4>Things to Do in Varanasi</h4>
                        <p>Top experiences — ghats, temples, food &amp; culture</p>
                    </a>
                    <a href="{{ url('/boat') }}" class="vkbd-svc-card">
                        <div class="vkbd-svc-icon">&#127751;</div>
                        <h4>Ganga Aarti Boat Ride</h4>
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
                        @if($displayPrice > 0)
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
                        <input type="hidden" name="booking_amount" value="{{ $displayPrice ?? 0 }}">

                        {{-- 1. Travel Date --}}
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_date">Travel Date <span class="vkbs-req">*</span></label>
                            <input type="date" id="vkbs_date" name="arrival_date"
                                   class="vkbs-input" required
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('arrival_date', date('Y-m-d')) }}">
                        </div>

                        {{-- 2. Time Slot --}}
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

                        {{-- 5 & 6. Adults + Children --}}
                        <div class="vkbs-row2">
                            <div class="vkbs-group" style="margin-bottom:0;">
                                <span class="vkbs-label">Adults <span class="vkbs-req">*</span></span>
                                <div class="vkbs-counter" id="vkbs_persons_wrap">
                                    <button type="button" id="vkbs_pm" aria-label="Decrease">−</button>
                                    <input type="number" id="vkbs_persons_disp" value="2" readonly>
                                    <button type="button" id="vkbs_pp" aria-label="Increase">+</button>
                                </div>
                                <div class="vkbs-cap-hint" id="vkbs_cap_hint"></div>
                            </div>
                            <div class="vkbs-group" style="margin-bottom:0;">
                                <span class="vkbs-label">Children <span class="vkbs-cap-hint" style="font-size:.65rem;font-weight:400;color:#9ca3af;text-transform:none;letter-spacing:0;">&lt;10 yrs</span></span>
                                <div class="vkbs-counter">
                                    <button type="button" id="vkbs_cm" aria-label="Decrease">−</button>
                                    <input type="number" id="vkbs_children_disp" value="0" readonly>
                                    <button type="button" id="vkbs_cp" aria-label="Increase">+</button>
                                </div>
                            </div>
                        </div>

                        {{-- 7. Price Estimate --}}
                        @if($displayPrice > 0)
                        <div class="vkbs-price-box" id="vkbsPriceBox">
                            <div class="vkbs-price-box-header">
                                <span>💰 Price Estimate</span>
                                <span class="vkbs-price-note">All-inclusive</span>
                            </div>
                            <div class="vkbs-price-line">
                                <span id="vkbs_pb_caption">Base fare (up to 10 persons)</span>
                                <span id="vkbs_pb_base">₹{{ number_format($displayPrice) }}</span>
                            </div>
                            <div class="vkbs-price-line" id="vkbs_pb_extra_row" style="display:none;">
                                <span id="vkbs_pb_extra_label">Extra persons</span>
                                <span id="vkbs_pb_extra" style="color:#C2410C;">₹0</span>
                            </div>
                            <div class="vkbs-price-line vkbs-price-total">
                                <span>Estimated Total</span>
                                <span id="vkbs_pb_total">₹{{ number_format($displayPrice) }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- 8. Nearest Pickup Ghat --}}
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
                                <option {{ old('pickup_ghat')=='Dashaswamedh Ghat'?'selected':'' }}>Dashaswamedh Ghat</option>
                                <option {{ old('pickup_ghat')=='Lalita Ghat'?'selected':'' }}>Lalita Ghat</option>
                                <option {{ old('pickup_ghat')=='Manikarnika Ghat'?'selected':'' }}>Manikarnika Ghat</option>
                                <option {{ old('pickup_ghat')=='Scindia Ghat'?'selected':'' }}>Scindia Ghat</option>
                                <option {{ old('pickup_ghat')=='NAMO Ghat'?'selected':'' }}>NAMO Ghat</option>
                            </select>
                        </div>

                        {{-- 9. Special Requests --}}
                        <div class="vkbs-group">
                            <label class="vkbs-label" for="vkbs_notes">Special Requests</label>
                            <textarea id="vkbs_notes" name="special_notes" class="vkbs-input" rows="2"
                                      style="height:auto;resize:vertical;">{{ old('special_notes') }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="vkbs-btn-wa" id="vkbs_submit">
                            <svg width="18" height="18" viewBox="0 0 32 32" fill="currentColor"><path d="M16 3C8.82 3 3 8.82 3 16c0 2.43.65 4.7 1.78 6.67L3 29l6.55-1.72A13 13 0 0 0 16 29c7.18 0 13-5.82 13-13S23.18 3 16 3zm6.4 17.72c-.27.76-1.58 1.45-2.16 1.54-.56.09-1.26.13-2.04-.13a18.7 18.7 0 0 1-1.85-.68C13.6 20.3 11.6 17.9 11.45 17.7c-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.2 1.05-2.5.27-.3.6-.37.8-.37l.57.01c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.14.17-.3.38-.43.51-.14.14-.29.3-.12.58.17.28.74 1.22 1.59 1.97 1.09.97 2 1.27 2.29 1.41.28.14.45.12.61-.07.17-.2.72-.84.91-1.13.2-.28.39-.23.66-.14.27.09 1.71.8 2 .95.29.14.48.21.55.33.07.12.07.7-.2 1.46z"/></svg>
                            Send Enquiry on WhatsApp
                        </button>
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

    <div style="height: 48px;"></div>

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
            <article class="vkbd-rel-card" role="listitem" onclick="location.href='{{ route('product.detail', [$rbCat, $rbSlug, $rb->slug]) }}'" style="cursor:pointer;">
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

{{-- YouTube Video Popup Modal --}}
<div class="vkyt-modal" id="vkytModal">
    <div class="vkyt-modal-inner">
        <button class="vkyt-modal-close" onclick="vkYtClose()">&#x2715;</button>
        <div class="vkyt-iframe-wrap">
            <iframe id="vkytIframe" src="" allowfullscreen frameborder="0" title="YouTube Video"></iframe>
        </div>
    </div>
</div>

{{-- Mobile sticky bar --}}
<div class="vkbd-mob-bar">
    <div class="vkbd-mob-bar-info">
        <div class="vkbd-mob-bar-label">{{ Str::limit($product->name, 28) }}</div>
        @if($displayPrice > 0)
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
    var BASE_PRICE   = {{ $displayPrice > 0 ? $displayPrice : 0 }};
    var INCLUDED     = 10;
    var EXTRA_RATE   = 500;

    var persons  = 2;
    var children = 0;

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

    document.getElementById('vkbs_pm').addEventListener('click', function () { if (persons > 1)  { persons--;  renderPersons();  } });
    document.getElementById('vkbs_pp').addEventListener('click', function () { if (persons < 50) { persons++;  renderPersons();  } });
    document.getElementById('vkbs_cm').addEventListener('click', function () { if (children > 0) { children--; renderChildren(); document.getElementById('vkbs_children_hidden').value = children; } });
    document.getElementById('vkbs_cp').addEventListener('click', function () { if (children < 20){ children++; renderChildren(); document.getElementById('vkbs_children_hidden').value = children; } });

    renderPersons();
    renderChildren();

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

    /* ── Phone digits only (10 max) ── */
    document.getElementById('vkbs_phone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

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
        var slot   = document.querySelector('input[name="vkbs_slot"]:checked');
        var pickup = document.getElementById('vkbs_pickup').value;
        var notes  = document.getElementById('vkbs_notes').value.trim();

        // Validate ghat
        if (!pickup) {
            e.preventDefault();
            var sel     = document.getElementById('vkbs_pickup');
            var nsEl    = sel.nextElementSibling; // .nice-select div
            var target  = (nsEl && nsEl.classList.contains('nice-select')) ? nsEl : sel;
            target.style.borderColor = '#ef4444';
            target.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.15)';
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Set structured hidden fields
        document.getElementById('vkbs_slot_hidden').value    = slot ? slot.value : 'Not selected';
        document.getElementById('vkbs_children_hidden').value = children;

        // Include WhatsApp in message if provided
        var wa   = document.getElementById('vkbs_whatsapp') ? document.getElementById('vkbs_whatsapp').value.trim() : '';
        var slot = document.querySelector('input[name="vkbs_slot"]:checked');

        // Build human-readable message
        var msg = 'Pickup Ghat: '         + pickup
                + '\nAdults: '            + persons
                + '\nChildren (<10 yrs): '+ children;
        if (wa && wa !== document.getElementById('vkbs_phone').value) msg += '\nWhatsApp: ' + wa;
        if (notes) msg += '\nSpecial Notes: ' + notes;
        if (slot) { msg = 'Time Slot: ' + slot.value + '\n' + msg; }

        document.getElementById('vkbs_message_hidden').value = msg.trim();
        document.getElementById('vkbs_pickup').style.borderColor = '';
        document.getElementById('vkbs_pickup').style.boxShadow   = '';
    });

    /* ── Set default date to today ── */
    var dateEl = document.getElementById('vkbs_date');
    if (dateEl && !dateEl.value) {
        var d = new Date();
        var mm = String(d.getMonth()+1).padStart(2,'0');
        var dd = String(d.getDate()).padStart(2,'0');
        dateEl.value = d.getFullYear()+'-'+mm+'-'+dd;
    }

    /* ── YouTube popup ── */
    var ytModal   = document.getElementById('vkytModal');
    var ytIframe  = document.getElementById('vkytIframe');
    window.vkYtOpen = function(embedUrl) {
        ytIframe.src = embedUrl;
        ytModal.classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.vkYtClose = function() {
        ytIframe.src = '';
        ytModal.classList.remove('open');
        document.body.style.overflow = '';
    };
    if (ytModal) {
        ytModal.addEventListener('click', function(e) { if (e.target === ytModal) vkYtClose(); });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && ytModal && ytModal.classList.contains('open')) vkYtClose();
    });

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

{{-- VideoObject Schema for YouTube Videos --}}
@isset($youtubeVideos)
@if($youtubeVideos->isNotEmpty())
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "{{ addslashes($product->name) }} – Videos",
    "itemListElement": [
        @foreach($youtubeVideos->where('video_id','!=','') as $i => $vid)
        {
            "@type": "VideoObject",
            "position": {{ $i + 1 }},
            "name": "{{ addslashes($vid->title ?? $product->name) }}",
            "thumbnailUrl": "{{ $vid->thumbnail }}",
            "embedUrl": "https://www.youtube.com/embed/{{ $vid->video_id }}",
            "url": "{{ $vid->youtube_url }}",
            "uploadDate": "{{ $vid->created_at->toIso8601String() }}",
            "publisher": {"@type": "Organization","name": "Visit Kashi","url": "{{ url('/') }}"}
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endif
@endisset

{{-- Extended FAQ Schema with all 20 FAQs --}}
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
@endpush
