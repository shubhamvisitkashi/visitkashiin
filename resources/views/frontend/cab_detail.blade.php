@extends('frontend.layouts.app')

@section('meta')
@isset($product)
@php
    $metaTitle = $product->meta_title ?? $product->name;
    $metaDesc  = $product->meta_description ?? 'Book ' . $product->name . ' in Varanasi at best price. Comfortable, safe & reliable cab service with Visit Kashi.';
    $metaImg   = (!empty($product->images) && is_array($product->images))
        ? asset('backend/admin/product_images/'.$product->images[0])
        : asset('backend/assets/images/placeholder.jpg');
    $pageUrl   = url()->current();
    $displayP  = ($product->discounted_price ?? 0) > 0 ? $product->discounted_price : ($product->base_price ?? 0);
    $hasDiscount = ($product->base_price??0)>0 && ($product->discounted_price??0)>0 && $product->base_price > $product->discounted_price;
    $catSlug   = optional($product->category)->slug ?? 'cab';
    $subSlug   = optional($product->subCategory)->slug ?? 'innova-crysta';
    $subName   = optional($product->subCategory)->name ?? 'Cab';
    $catName   = optional($product->category)->name ?? 'Cab';
@endphp
<link rel="canonical" href="{{ $pageUrl }}">
<title>{{ $metaTitle }} | Varanasi Cab Booking – Visit Kashi</title>
<meta name="description" content="{{ Str::limit(strip_tags($metaDesc),160) }}">
<meta name="keywords" content="{{ $product->meta_keyword ?? 'cab varanasi, innova crysta varanasi, varanasi taxi' }}">
<meta name="robots" content="index, follow">
<meta property="og:type" content="product">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($metaDesc),200) }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:image" content="{{ $metaImg }}">
<meta property="og:site_name" content="Visit Kashi">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($metaDesc),200) }}">
<meta name="twitter:image" content="{{ $metaImg }}">
<script type="application/ld+json">{"@context":"https://schema.org","@type":"TaxiService","name":"{{ addslashes($product->name) }}","description":"{{ addslashes(strip_tags($metaDesc)) }}","image":"{{ $metaImg }}","url":"{{ $pageUrl }}","provider":{"@type":"LocalBusiness","name":"Visit Kashi","url":"{{ url('/') }}","telephone":"+91{{ preg_replace('/\D/','',websiteSetupValue('contact_number')) }}"},"areaServed":{"@type":"City","name":"Varanasi"},"offers":{"@type":"Offer","priceCurrency":"INR","price":"{{ $displayP }}","availability":"https://schema.org/InStock"},"aggregateRating":{"@type":"AggregateRating","ratingValue":"4.9","reviewCount":"186","bestRating":"5"}}</script>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},{"@type":"ListItem","position":2,"name":"{{ addslashes($catName) }}","item":"{{ route('product.list',$catSlug) }}"},{"@type":"ListItem","position":3,"name":"{{ addslashes($subName) }}","item":"{{ route('product.sub.list',[$catSlug,$subSlug]) }}"},{"@type":"ListItem","position":4,"name":"{{ addslashes($product->name) }}","item":"{{ $pageUrl }}"}]}</script>
@endisset
@endsection

@push('styles')
<link rel="preload" href="{{ asset('frontend/css/cab-detail.css') }}?v={{ filemtime(public_path('frontend/css/cab-detail.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/cab-detail.css') }}?v={{ filemtime(public_path('frontend/css/cab-detail.css')) }}"></noscript>
@endpush

@section('content')
@php
    $imgs    = (!empty($product->images)&&is_array($product->images)) ? $product->images : ((!empty($product->images)&&is_string($product->images)) ? json_decode($product->images,true)??[] : []);
    $imgUrls = array_map(fn($f)=>asset('backend/admin/product_images/'.$f), $imgs);
    $fallback= asset('backend/assets/images/placeholder.jpg');
    if(empty($imgUrls)) $imgUrls=[$fallback];
    $cPhone  = preg_replace('/\D/','',websiteSetupValue('contact_number')?:'7080109917');
@endphp

{{-- Breadcrumb --}}
<div class="ckbd-breadcrumb">
    <div class="container">
        <a href="{{ url('/') }}">Home</a><span>›</span>
        <a href="{{ route('product.list',$catSlug) }}">{{ $catName }}</a><span>›</span>
        <a href="{{ route('product.sub.list',[$catSlug,$subSlug]) }}">{{ $subName }}</a><span>›</span>
        <span>{{ $product->name }}</span>
    </div>
</div>

<div class="container" style="max-width:1200px;padding-top:12px;padding-bottom:48px;">

    {{-- Title --}}
    <div class="ckbd-title-row">
        <h1>{{ $product->name }}</h1>
        <div class="ckbd-meta">
            <span class="ckbd-stars">★★★★★</span>
            <span class="ckbd-rating">4.9</span>
            <span class="ckbd-meta-dot">·</span><span>186 Reviews</span>
            <span class="ckbd-meta-dot">·</span>
            <a href="{{ route('product.sub.list',[$catSlug,$subSlug]) }}" class="ckbd-cat-link">{{ $subName }}</a>
            <span class="ckbd-meta-dot">·</span><span>Varanasi, Uttar Pradesh</span>
        </div>
    </div>

    {{-- Gallery --}}
    <div class="ckbd-gallery" id="ckbdGallery">
        <div class="ckbd-gallery-item ckbd-gallery-hero" onclick="ckLbOpen(0)">
            <img src="{{ $imgUrls[0] }}" alt="{{ $product->name }} – Photo 1" loading="eager" onerror="this.src='{{ $fallback }}'">
        </div>
        @if(isset($imgUrls[1]))<div class="ckbd-gallery-item" onclick="ckLbOpen(1)"><img src="{{ $imgUrls[1] }}" alt="{{ $product->name }} – Photo 2" loading="lazy" onerror="this.src='{{ $fallback }}'"></div>@endif
        @if(isset($imgUrls[2]))<div class="ckbd-gallery-item" onclick="ckLbOpen(2)"><img src="{{ $imgUrls[2] }}" alt="{{ $product->name }} – Photo 3" loading="lazy" onerror="this.src='{{ $fallback }}'"></div>@endif
        <button class="ckbd-gallery-btn" onclick="ckLbOpen(0)"><i class="fa fa-th"></i> All {{ count($imgUrls) }} photos</button>
    </div>

    {{-- Lightbox --}}
    <div class="ckbd-lb-overlay" id="ckLb">
        <button class="ckbd-lb-close" onclick="ckLbClose()">✕</button>
        <button class="ckbd-lb-prev" onclick="ckLbNav(-1)">‹</button>
        <img id="ckLbImg" src="" alt="">
        <button class="ckbd-lb-next" onclick="ckLbNav(1)">›</button>
        <div class="ckbd-lb-caption" id="ckLbCap"></div>
    </div>

    {{-- 2-col layout --}}
    <div class="ckbd-layout">

        {{-- ── MAIN ── --}}
        <div>

            {{-- Provider bar --}}
            <div class="ckbd-provider-bar">
                <div class="ckbd-provider-avatar">
                    <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('logo')) }}" alt="Visit Kashi">
                </div>
                <div class="ckbd-provider-info">
                    <h4>VisitKashi</h4>
                    <p>Verified Travel Partner &nbsp;·&nbsp; Varanasi &nbsp;·&nbsp; Best Price Guaranteed</p>
                </div>
            </div>
            <div class="ckbd-divider"></div>

            {{-- Highlights --}}
            <div class="ckbd-highlights">
                <div class="ckbd-highlight-item">
                    <div class="ckbd-highlight-icon"><i class="fa fa-car"></i></div>
                    <div class="ckbd-highlight-text">
                        <h5>Premium {{ $subName }} – Comfort &amp; Style</h5>
                        <p>Travel in a well-maintained, air-conditioned {{ $subName }} with premium leather seats and experienced professional driver.</p>
                    </div>
                </div>
                <div class="ckbd-highlight-item">
                    <div class="ckbd-highlight-icon"><i class="fa fa-shield"></i></div>
                    <div class="ckbd-highlight-text">
                        <h5>Safe, Licensed &amp; Verified Driver</h5>
                        <p>All Visit Kashi drivers are police-verified, licensed, and experienced with Varanasi routes including airports, railway stations, and outstation trips.</p>
                    </div>
                </div>
                <div class="ckbd-highlight-item">
                    <div class="ckbd-highlight-icon"><i class="fa fa-clock-o"></i></div>
                    <div class="ckbd-highlight-text">
                        <h5>On-Time Pickup – 24/7 Available</h5>
                        <p>Punctual and reliable cab service available round the clock. Early morning airport pickups to late-night drop-offs — we're always on time.</p>
                    </div>
                </div>
                <div class="ckbd-highlight-item">
                    <div class="ckbd-highlight-icon"><i class="fa fa-tag"></i></div>
                    <div class="ckbd-highlight-text">
                        <h5>Best Price – No Hidden Charges
                            @if($displayP>0), Starting ₹{{ number_format($displayP) }}/-@endif
                        </h5>
                        <p>Transparent pricing with no hidden charges. What you see is what you pay — toll, parking, driver allowance included.</p>
                    </div>
                </div>
            </div>
            <div class="ckbd-divider"></div>

            {{-- Description --}}
            <h2 class="ckbd-section-title">About {{ $product->name }}</h2>
            <div class="ckbd-description">
                @if($product->description)
                    {!! safe_html($product->description) !!}
                @else
                    <p>Book <strong>{{ $product->name }}</strong> with Visit Kashi — Varanasi's most trusted local travel company. Whether you need a comfortable cab for airport transfer, railway station pickup, local sightseeing, or outstation travel, our {{ $subName }} fleet is ready to serve you.</p>
                    <p>Our {{ $subName }} is perfect for families and groups up to 7 persons. The vehicle is well-maintained, air-conditioned, and driven by an experienced, licensed driver who knows every corner of Varanasi and beyond.</p>
                @endif
            </div>
            <div class="ckbd-divider"></div>

            {{-- Specs --}}
            <h2 class="ckbd-section-title">Cab Details – Pricing &amp; Booking Info</h2>
            <table class="ckbd-specs">
                @if($displayP > 0)
                <tr>
                    <td class="ckbd-spec-label">Price</td>
                    <td class="ckbd-spec-val">
                        <i class="fa fa-inr"></i>
                        <strong style="color:#0f3460;font-size:16px;">₹{{ number_format($displayP) }}</strong>
                        @if($hasDiscount)<span style="font-size:12px;color:#6b7280;text-decoration:line-through;margin-left:6px;">₹{{ number_format($product->base_price) }}</span>@endif
                    </td>
                </tr>
                @endif
                <tr><td class="ckbd-spec-label">Vehicle</td><td class="ckbd-spec-val"><i class="fa fa-car"></i>{{ $subName }} (7 Seater)</td></tr>
                <tr><td class="ckbd-spec-label">AC</td><td class="ckbd-spec-val"><i class="fa fa-snowflake-o"></i>Full Air-Conditioned</td></tr>
                <tr><td class="ckbd-spec-label">Driver</td><td class="ckbd-spec-val"><i class="fa fa-user"></i>Licensed, Verified, Experienced</td></tr>
                <tr><td class="ckbd-spec-label">Pickup</td><td class="ckbd-spec-val"><i class="fa fa-map-marker"></i>Hotel / Ghat / Airport / Station – Varanasi</td></tr>
                <tr><td class="ckbd-spec-label">Fuel</td><td class="ckbd-spec-val"><i class="fa fa-tint"></i>Diesel (Included in price)</td></tr>
                <tr><td class="ckbd-spec-label">Payment</td><td class="ckbd-spec-val"><i class="fa fa-credit-card"></i>UPI, GPay, PhonePe, Cash, Bank Transfer</td></tr>
                <tr><td class="ckbd-spec-label">Available</td><td class="ckbd-spec-val"><i class="fa fa-clock-o"></i>24/7 – All days including holidays</td></tr>
            </table>
            <div class="ckbd-divider"></div>

            {{-- Includes --}}
            <h2 class="ckbd-section-title">What's Included</h2>
            <ul class="ckbd-includes">
                <li><i class="fa fa-check-circle"></i>Fuel charges</li>
                <li><i class="fa fa-check-circle"></i>Driver allowance</li>
                <li><i class="fa fa-check-circle"></i>Toll charges (one-way)</li>
                <li><i class="fa fa-check-circle"></i>State tax</li>
                <li><i class="fa fa-check-circle"></i>Parking (at pickup point)</li>
                <li><i class="fa fa-check-circle"></i>24/7 customer support</li>
            </ul>
            <div class="ckbd-divider"></div>

            {{-- Map --}}
            @if($product->map_location)
            <h2 class="ckbd-section-title">Location / Pickup Area</h2>
            <div class="ckbd-map-wrap">
                <iframe src="{{ $product->map_location }}" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" title="{{ $product->name }} location"></iframe>
            </div>
            <div class="ckbd-divider"></div>
            @endif

            {{-- More Cab Options — before reviews ── --}}
            @if($relatedCabs->isNotEmpty())
            <h2 class="ckbd-section-title">More Cab Options</h2>
            <div class="ckbd-related-grid">
                @foreach($relatedCabs->take(4) as $rc)
                @php
                    $rcImg = (!empty($rc->images)&&is_array($rc->images)) ? asset('backend/admin/product_images/'.$rc->images[0]) : $fallback;
                    $rcUrl = route('product.detail',[optional($rc->category)->slug??'cab',optional($rc->subCategory)->slug??'cab',$rc->slug]);
                    $rcP   = ($rc->discounted_price??0)>0?$rc->discounted_price:($rc->base_price??0);
                @endphp
                <a href="{{ $rcUrl }}" style="display:block;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;text-decoration:none;transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow='none'">
                    <img src="{{ $rcImg }}" alt="{{ $rc->name }}" style="width:100%;height:120px;object-fit:cover;display:block;" loading="lazy" onerror="this.src='{{ $fallback }}'">
                    <div style="padding:10px 12px;">
                        <div style="font-size:.82rem;font-weight:700;color:#111;margin-bottom:3px;line-height:1.3;">{{ Str::limit($rc->name,35) }}</div>
                        @if($rcP>0)<div style="font-size:.78rem;font-weight:700;color:#0f3460;">₹{{ number_format($rcP) }}</div>@endif
                    </div>
                </a>
                @endforeach
            </div>
            <div class="ckbd-divider"></div>
            @endif

            {{-- Guest Reviews (Why Travelers Choose Visit Kashi) --}}
            <h2 class="ckbd-section-title">Why Travelers Choose Visit Kashi</h2>
            <div class="ckbd-reviews-grid">
                @foreach([['Anil Kumar','Great cab service! Driver was on time, very polite and the Innova was super clean. Highly recommend Visit Kashi.'],['Sunita Sharma','Booked airport pickup. Driver arrived 15 min early and helped with luggage. Will definitely book again!'],['Rajesh Mishra','Best price I found for Varanasi to Ayodhya trip. Smooth ride, professional driver, no extra charges.'],['Priya Gupta','The interior was spotless and driver was very knowledgeable about Varanasi. Perfect family trip experience.']] as [$name,$text])
                <div class="ckbd-review-card">
                    <div class="ckbd-review-header">
                        <div class="ckbd-review-avatar">{{ strtoupper(substr($name,0,1)) }}</div>
                        <div><div class="ckbd-review-name">{{ $name }}</div><div class="ckbd-review-date">Varanasi, India</div></div>
                    </div>
                    <div class="ckbd-review-stars">★★★★★</div>
                    <p class="ckbd-review-text">{{ $text }}</p>
                </div>
                @endforeach
            </div>

        </div>{{-- /.main --}}

        {{-- ── SIDEBAR ── --}}
        <div class="ckbd-sidebar">
            @php $cabPrice = ($product->discounted_price??0)>0?$product->discounted_price:($product->base_price??0); @endphp

            {{-- Cab Booking Card --}}
            @push('styles')
            <style>
            .cab-card{background:#fff;border-radius:18px;box-shadow:0 6px 32px rgba(0,0,0,.13);overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;}
            .cab-card-header{background:linear-gradient(135deg,#0C1F3F 0%,#0f3460 55%,#1a5276 100%);padding:20px 22px 16px;color:#fff;}
            .cab-card-header-top{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:12px;}
            .cab-card-header h3{font-size:1rem;font-weight:800;margin:0 0 3px;color:#fff;}
            .cab-card-header p{font-size:.77rem;color:rgba(255,255,255,.72);margin:0;}
            .cab-price-wrap{text-align:right;flex-shrink:0;}
            .cab-price-val{font-size:1.4rem;font-weight:900;color:#fbbf24;display:block;line-height:1;}
            .cab-price-original{font-size:.78rem;text-decoration:line-through;color:rgba(255,255,255,.5);}
            .cab-price-sub{font-size:.7rem;color:rgba(255,255,255,.6);}
            .cab-trust-pills{display:flex;gap:5px;flex-wrap:wrap;}
            .cab-trust-pills span{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.88);font-size:.66rem;font-weight:600;padding:3px 9px;border-radius:20px;white-space:nowrap;}
            .cab-card-body{padding:18px 20px;}
            .cc-field{margin-bottom:12px;}
            .cc-label{font-size:.69rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;margin-bottom:5px;display:block;}
            .cc-req{color:#ef4444;}
            .cc-input{width:100%;border:1.5px solid #e2e8f0;border-radius:9px;padding:10px 13px;font-size:.86rem;color:#111;background:#f9fafb;outline:none;transition:border-color .2s;font-family:inherit;}
            .cc-input:focus{border-color:#0f3460;box-shadow:0 0 0 3px rgba(15,52,96,.1);}
            .cc-phone-wrap{position:relative;}
            .cc-phone-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.84rem;font-weight:600;color:#64748b;pointer-events:none;z-index:1;}
            .cc-phone-input{padding-left:38px!important;}
            .cc-row2{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;}
            .cc-stepper{display:flex;align-items:center;border:1.5px solid #e2e8f0;border-radius:9px;background:#f9fafb;height:42px;overflow:hidden;transition:border-color .2s;}
            .cc-stepper:focus-within{border-color:#0f3460;box-shadow:0 0 0 3px rgba(15,52,96,.1);}
            .cc-stepper-btn{flex-shrink:0;width:38px;height:100%;background:none;border:none;font-size:1.1rem;font-weight:700;color:#0f3460;cursor:pointer;display:flex;align-items:center;justify-content:center;user-select:none;}
            .cc-stepper-btn:hover:not(:disabled){background:#EFF6FF;}
            .cc-stepper-btn:disabled{color:#d1d5db;cursor:default;}
            .cc-stepper-val{flex:1;text-align:center;font-size:.92rem;font-weight:800;color:#111;pointer-events:none;}
            .cc-submit{width:100%;padding:13px 16px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:11px;font-size:.92rem;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:9px;letter-spacing:.2px;margin-bottom:9px;box-shadow:0 4px 14px rgba(22,163,74,.3);}
            .cc-submit:hover{opacity:.92;}
            .cc-callback{width:100%;padding:11px 16px;background:#F8FAFC;color:#334155;border:1.5px solid #E2E8F0;border-radius:11px;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;}
            .cc-callback:hover{background:#E2E8F0;color:#1e293b;text-decoration:none;}
            .cc-success-state{text-align:center;padding:28px 20px;}
            .cc-success-icon{width:56px;height:56px;border-radius:50%;background:#DCFCE7;color:#16A34A;font-size:1.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
            .cc-success-state h4{font-size:1rem;font-weight:800;color:#111;margin:0 0 7px;}
            .cc-success-state p{font-size:.83rem;color:#4B5563;margin:0 0 14px;line-height:1.6;}
            .cc-success-call{display:inline-flex;align-items:center;gap:6px;background:#0f3460;color:#fff;font-size:.82rem;font-weight:700;padding:9px 20px;border-radius:8px;text-decoration:none;}
            .cc-check-row{display:flex;align-items:flex-start;gap:10px;background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:9px;padding:10px 13px;margin-bottom:12px;cursor:pointer;}
            .cc-check-row input{width:16px;height:16px;margin-top:2px;flex-shrink:0;accent-color:#0f3460;}
            .cc-check-text{font-size:.81rem;color:#92400E;line-height:1.4;}
            .cc-check-text strong{display:block;font-weight:700;}
            .ckbd-related-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;}
            @media(max-width:600px){.ckbd-related-grid{grid-template-columns:repeat(2,1fr);gap:10px;}}
            @media(max-width:360px){.ckbd-related-grid{grid-template-columns:1fr;gap:8px;}}
            </style>
            @endpush

            <div class="cab-card">
                <div class="cab-card-header">
                    <div class="cab-card-header-top">
                        <div>
                            <h3>🚕 Cab Booking Enquiry</h3>
                            <p>{{ Str::limit($product->name,40) }}</p>
                        </div>
                        @if($cabPrice>0)
                        <div class="cab-price-wrap">
                            @if($hasDiscount)<span class="cab-price-original">₹{{ number_format($product->base_price) }}</span>@endif
                            <span class="cab-price-val">₹{{ number_format($cabPrice) }}</span>
                            <span class="cab-price-sub">/ trip</span>
                        </div>
                        @endif
                    </div>
                    <div class="cab-trust-pills">
                        <span>🔒 Secure</span>
                        <span>📱 WhatsApp Confirm</span>
                    </div>
                </div>
                <div class="cab-card-body">
                    @if(session('success'))
                    <div class="cc-success-state">
                        <div class="cc-success-icon">✓</div>
                        <h4>Enquiry Submitted!</h4>
                        <p>Our team will contact you on WhatsApp within 15 minutes.</p>
                        <a href="tel:+91{{ $cPhone }}" class="cc-success-call">📞 Call Us Now</a>
                    </div>
                    @else
                    @if(!empty($errors) && $errors->any())
                    <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-radius:9px;padding:11px 14px;margin-bottom:13px;font-size:.79rem;color:#991b1b;">
                        <strong>Please fix:</strong><ul style="margin:4px 0 0;padding-left:16px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif
                    <form action="{{ route('enquiry.store') }}" method="POST" id="cabEnqForm" novalidate>
                        @csrf
                        <input type="hidden" name="package_id"    value="{{ $product->id }}">
                        <input type="hidden" name="package_name"  value="{{ $product->name }}">
                        <input type="hidden" name="no_of_person"  id="cab_persons_val" value="1">
                        <input type="hidden" name="luggage_bags"  id="cab_luggage_val" value="0">
                        <input type="hidden" name="booking_amount" value="{{ $cabPrice }}">
                        <input type="hidden" name="message" id="cab_message_hidden">

                        {{-- Date/Time --}}
                        <div class="cc-field">
                            <label class="cc-label">Pickup Date &amp; Time <span class="cc-req">*</span></label>
                            <input type="datetime-local" class="cc-input" name="arrival_date" id="cab_pickup_dt" min="{{ date('Y-m-d') }}T00:00" value="{{ old('arrival_date') }}" required>
                        </div>
                        {{-- Name --}}
                        <div class="cc-field">
                            <label class="cc-label">Full Name <span class="cc-req">*</span></label>
                            <input type="text" class="cc-input" name="name" value="{{ old('name') }}" required autocomplete="name">
                        </div>
                        {{-- Phone --}}
                        <div class="cc-field">
                            <label class="cc-label">Mobile <span class="cc-req">*</span></label>
                            <div class="cc-phone-wrap">
                                <span class="cc-phone-prefix">+91</span>
                                <input type="tel" class="cc-input cc-phone-input" name="phone" id="cab_phone" inputmode="numeric" maxlength="10" value="{{ old('phone') }}" required autocomplete="tel">
                            </div>
                        </div>
                        {{-- Persons + Luggage --}}
                        @php
                            $nf=strtolower($product->name??'');
                            if(preg_match('/(\d+)\s*seater/i',$product->name??'',$sm)){$maxS=(int)$sm[1];}
                            elseif(str_contains($nf,'innova')||str_contains($nf,'crysta')){$maxS=7;}
                            elseif(str_contains($nf,'sedan')||str_contains($nf,'etios')){$maxS=4;}
                            elseif(str_contains($nf,'traveller')||str_contains($nf,'tempo')){$maxS=20;}
                            else{$maxS=7;}
                        @endphp
                        <div class="cc-row2">
                            <div class="cc-field" style="margin-bottom:0;">
                                <label class="cc-label">Persons <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">(max {{ $maxS }})</small></label>
                                <div class="cc-stepper" data-max="{{ $maxS }}">
                                    <button type="button" class="cc-stepper-btn" id="cabPMinus" aria-label="−">−</button>
                                    <span class="cc-stepper-val" id="cabPVal">1</span>
                                    <button type="button" class="cc-stepper-btn" id="cabPPlus"  aria-label="+">+</button>
                                </div>
                            </div>
                            <div class="cc-field" style="margin-bottom:0;">
                                <label class="cc-label">Luggage <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">(bags)</small></label>
                                <div class="cc-stepper">
                                    <button type="button" class="cc-stepper-btn" id="cabLMinus" aria-label="−">−</button>
                                    <span class="cc-stepper-val" id="cabLVal">0</span>
                                    <button type="button" class="cc-stepper-btn" id="cabLPlus"  aria-label="+">+</button>
                                </div>
                            </div>
                        </div>
                        {{-- Roof Carrier --}}
                        <label class="cc-check-row">
                            <input type="checkbox" name="roof_carrier" id="cab_roof_carrier" value="Yes">
                            <span class="cc-check-text"><strong>📦 Need Roof Carrier?</strong>Extra luggage rack on top of cab</span>
                        </label>
                        {{-- Pickup Location --}}
                        <div class="cc-field">
                            <label class="cc-label">Pickup Location <span class="cc-req">*</span></label>
                            <input type="text" class="cc-input" id="cab_pickup_loc" value="{{ old('cab_pickup_loc') }}" required>
                        </div>
                        @php
                            $pn2=strtolower($product->name??'');
                            if(str_contains($pn2,'half day')){$autoTrip='Half Day (4 Hr / 40 Km)';}
                            elseif(str_contains($pn2,'airport pickup')||str_contains($pn2,'airport pick')){$autoTrip='Airport Pickup';}
                            elseif(str_contains($pn2,'airport drop')){$autoTrip='Airport Drop';}
                            elseif(str_contains($pn2,'airport')){$autoTrip='Airport Pickup';}
                            elseif(str_contains($pn2,'railway')&&str_contains($pn2,'drop')){$autoTrip='Railway Station Drop';}
                            elseif(str_contains($pn2,'railway')||str_contains($pn2,'station')){$autoTrip='Railway Station Pickup';}
                            elseif(str_contains($pn2,'round trip')||str_contains($pn2,'roundtrip')){$autoTrip='Round Trip Outstation';}
                            elseif(str_contains($pn2,'one way')||str_contains($pn2,'outstation')){$autoTrip='One Way Outstation';}
                            else{$autoTrip='Full Day (8 Hr / 80 Km)';}
                        @endphp
                        {{-- Trip Type auto-sent via hidden field --}}
                        <input type="hidden" name="trip_type_auto" value="{{ $autoTrip }}">
                        {{-- Notes --}}
                        <div class="cc-field">
                            <label class="cc-label">Special Instructions</label>
                            <textarea class="cc-input" id="cab_message_visible" rows="2" style="height:auto;resize:vertical;">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="cc-submit">
                            <svg width="18" height="18" viewBox="0 0 32 32" fill="currentColor"><path d="M16 3C8.82 3 3 8.82 3 16c0 2.43.65 4.7 1.78 6.67L3 29l6.55-1.72A13 13 0 0 0 16 29c7.18 0 13-5.82 13-13S23.18 3 16 3zm6.4 17.72c-.27.76-1.58 1.45-2.16 1.54-.56.09-1.26.13-2.04-.13a18.7 18.7 0 0 1-1.85-.68C13.6 20.3 11.6 17.9 11.45 17.7c-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.2 1.05-2.5.27-.3.6-.37.8-.37l.57.01c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.14.17-.3.38-.43.51-.14.14-.29.3-.12.58.17.28.74 1.22 1.59 1.97 1.09.97 2 1.27 2.29 1.41.28.14.45.12.61-.07.17-.2.72-.84.91-1.13.2-.28.39-.23.66-.14.27.09 1.71.8 2 .95.29.14.48.21.55.33.07.12.07.7-.2 1.46z"/></svg>
                            Send Enquiry on WhatsApp
                        </button>
                        <a href="tel:+91{{ $cPhone }}" class="cc-callback">📞 Request a Call Back</a>
                    </form>
                    @endif
                </div>
            </div>

        </div>{{-- /.sidebar --}}

    </div>{{-- /.layout --}}
</div>

{{-- Lightbox + Form JS --}}
<script>
var ckImgs = @json($imgUrls);
var ckCur  = 0;
function ckLbOpen(i) { ckCur=i; document.getElementById('ckLbImg').src=ckImgs[i]; document.getElementById('ckLbCap').textContent=(i+1)+' / '+ckImgs.length; document.getElementById('ckLb').classList.add('open'); document.body.style.overflow='hidden'; }
function ckLbClose() { document.getElementById('ckLb').classList.remove('open'); document.body.style.overflow=''; }
function ckLbNav(d)  { ckLbOpen((ckCur+d+ckImgs.length)%ckImgs.length); }
document.getElementById('ckLb').addEventListener('click', function(e){ if(e.target===this)ckLbClose(); });
document.addEventListener('keydown', function(e){ if(e.key==='Escape')ckLbClose(); if(e.key==='ArrowLeft')ckLbNav(-1); if(e.key==='ArrowRight')ckLbNav(1); });

// Steppers
function initStepper(mId,pId,vId,hId,min,max){
    var m=document.getElementById(mId),p=document.getElementById(pId),v=document.getElementById(vId),h=document.getElementById(hId),n=min;
    function r(){v.textContent=n;if(h)h.value=n;m.disabled=n<=min;p.disabled=n>=max;}
    m.addEventListener('click',function(){if(n>min){n--;r();}});
    p.addEventListener('click',function(){if(n<max){n++;r();}});
    r();
}
var ps=document.querySelector('#cabPMinus')?.closest('.cc-stepper');
var maxS=ps?parseInt(ps.dataset.max||'7'):7;
initStepper('cabPMinus','cabPPlus','cabPVal','cab_persons_val',1,maxS);
initStepper('cabLMinus','cabLPlus','cabLVal','cab_luggage_val',0,8);

// Phone digits only
var ph=document.getElementById('cab_phone');
if(ph)ph.addEventListener('input',function(){this.value=this.value.replace(/\D/g,'').slice(0,10);});

// Default datetime
var dt=document.getElementById('cab_pickup_dt');
if(dt&&!dt.value){var n2=new Date();n2.setHours(n2.getHours()+2,0,0,0);var p2=function(v){return String(v).padStart(2,'0');};dt.value=n2.getFullYear()+'-'+p2(n2.getMonth()+1)+'-'+p2(n2.getDate())+'T'+p2(n2.getHours())+':00';}


// Form submit
document.getElementById('cabEnqForm').addEventListener('submit',function(){
    var lu=document.getElementById('cab_luggage_val')?document.getElementById('cab_luggage_val').value:'0';
    var ro=document.getElementById('cab_roof_carrier')?document.getElementById('cab_roof_carrier').checked:false;
    var lo=document.getElementById('cab_pickup_loc')?document.getElementById('cab_pickup_loc').value.trim():'';
    var nt=document.getElementById('cab_message_visible')?document.getElementById('cab_message_visible').value.trim():'';
    var ps=document.getElementById('cab_persons_val')?document.getElementById('cab_persons_val').value:'1';
    var msg='Pickup Location: '+lo+'\nPersons: '+ps+'\nLuggage Bags: '+lu+'\nRoof Carrier: '+(ro?'Yes':'No');
    if(nt)msg+='\nNotes: '+nt;
    document.getElementById('cab_message_hidden').value=msg.trim();
});
</script>

{{-- ── Sticky Mobile Enquiry Bar ── --}}
<div class="ckbd-sticky-bar" id="ckbdStickyBar">
    @if($displayP > 0)
    <div class="ckbd-sticky-price">
        <span class="ckbd-sticky-price-val">₹{{ number_format($displayP) }}</span>
        <span class="ckbd-sticky-price-sub">/ trip</span>
    </div>
    @endif
    <div class="ckbd-sticky-btns">
        <button class="ckbd-sticky-enq" onclick="ckbdScrollToForm()">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Enquiry Now
        </button>
        <a href="https://wa.me/91{{ $cPhone }}?text={{ urlencode('Hi VisitKashi, I want to book '.$product->name.'. Please share details and pricing.') }}"
           target="_blank" rel="noopener" class="ckbd-sticky-wa">
            <svg width="22" height="22" viewBox="0 0 32 32" fill="currentColor"><path d="M16 3C8.82 3 3 8.82 3 16c0 2.43.65 4.7 1.78 6.67L3 29l6.55-1.72A13 13 0 0 0 16 29c7.18 0 13-5.82 13-13S23.18 3 16 3zm6.4 17.72c-.27.76-1.58 1.45-2.16 1.54-.56.09-1.26.13-2.04-.13a18.7 18.7 0 0 1-1.85-.68C13.6 20.3 11.6 17.9 11.45 17.7c-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.2 1.05-2.5.27-.3.6-.37.8-.37l.57.01c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.14.17-.3.38-.43.51-.14.14-.29.3-.12.58.17.28.74 1.22 1.59 1.97 1.09.97 2 1.27 2.29 1.41.28.14.45.12.61-.07.17-.2.72-.84.91-1.13.2-.28.39-.23.66-.14.27.09 1.71.8 2 .95.29.14.48.21.55.33.07.12.07.7-.2 1.46z"/></svg>
        </a>
    </div>
</div>

<style>
.ckbd-sticky-bar { display:none; }
@media(max-width:1100px){
    .ckbd-sticky-bar {
        display:flex; align-items:center; justify-content:space-between; gap:10px;
        position:fixed; bottom:0; left:0; right:0; z-index:1000;
        background:#fff; border-top:1px solid #e5e7eb;
        padding:12px 16px; box-shadow:0 -4px 16px rgba(0,0,0,.08);
        margin-bottom:65px;
    }
    .ckbd-layout { padding-bottom:80px; }

    /* price info — left side, shrinks with ellipsis */
    .ckbd-sticky-price { flex:1; min-width:0; overflow:hidden; display:flex; flex-direction:column; }
    .ckbd-sticky-price-val { font-size:1.1rem; font-weight:900; color:#0f3460; line-height:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .ckbd-sticky-price-sub { font-size:.72rem; color:#9CA3AF; white-space:nowrap; }

    /* buttons — right side, never shrink */
    .ckbd-sticky-btns { display:flex; gap:8px; align-items:center; flex-shrink:0; }

    /* Enquiry Now — matches .vkbd-mob-btn.book */
    .ckbd-sticky-enq {
        display:inline-flex; align-items:center; gap:6px;
        background:#0f3460; color:#fff;
        font-size:.85rem; font-weight:700; line-height:1.2;
        padding:12px 16px; border-radius:9px; border:none; cursor:pointer;
        white-space:nowrap; flex-shrink:0;
        transition:opacity .2s;
    }
    .ckbd-sticky-enq:hover { opacity:.88; }

    /* WhatsApp icon — matches .vkbd-mob-btn.wa */
    .ckbd-sticky-wa {
        display:inline-flex; align-items:center; justify-content:center;
        background:#25d366; color:#fff!important;
        padding:12px 14px; border-radius:9px;
        text-decoration:none!important; flex-shrink:0;
        transition:opacity .2s;
    }
    .ckbd-sticky-wa:hover { opacity:.88; color:#fff!important; }
}
@media(max-width:767px){
    .ckbd-sticky-bar { bottom:0; }
}
</style>
<script>
function ckbdScrollToForm(){
    var form=document.getElementById('cabEnqForm');
    if(form){ form.scrollIntoView({behavior:'smooth',block:'start'}); setTimeout(function(){ var f=form.querySelector('input:not([type=hidden]),select,textarea'); if(f)f.focus(); },600); }
}
</script>
@endsection
