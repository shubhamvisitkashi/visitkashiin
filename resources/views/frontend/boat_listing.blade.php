@extends('frontend.layouts.app')

{{-- ══ SEO META ══════════════════════════════════════════════════════════════ --}}
@section('meta')
@php
    $blTitle = optional($sub_category)->meta_title
        ?? (optional($sub_category)->name ? optional($sub_category)->name . ' in Varanasi' : 'Boat Rides in Varanasi');
    $blDesc  = optional($sub_category)->meta_description
        ?? 'Book private & group boat rides in Varanasi on the sacred Ganga River. Best prices on motor boats, row boats, Ganga Aarti rides & sunrise experiences. Instant confirmation.';
    $blKw    = optional($sub_category)->meta_keyword
        ?? 'boat ride varanasi, ganga aarti boat, varanasi boat booking, motor boat varanasi, sunrise boat ride, ganga river tour';
    $pageUrl = url()->current();
    $ogImage = asset('frontend/images/logo1.png');
@endphp

<link rel="canonical" href="{{ $pageUrl }}">
<title>{{ $blTitle }} | Visit Kashi – Varanasi Boat Rides</title>
<meta name="description" content="{{ $blDesc }}">
<meta name="keywords" content="{{ $blKw }}">
<meta name="robots" content="index, follow">

{{-- Open Graph --}}
<meta property="og:type"        content="website">
<meta property="og:url"         content="{{ $pageUrl }}">
<meta property="og:title"       content="{{ $blTitle }}">
<meta property="og:description" content="{{ $blDesc }}">
<meta property="og:image"       content="{{ $ogImage }}">
<meta property="og:site_name"   content="Visit Kashi">
<meta property="og:locale"      content="en_IN">

{{-- Twitter --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $blTitle }}">
<meta name="twitter:description" content="{{ $blDesc }}">
<meta name="twitter:image"       content="{{ $ogImage }}">

{{-- JSON-LD: Breadcrumb --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Boat","item":"{{ url('/boat') }}"},
    {"@type":"ListItem","position":3,"name":"{{ optional($sub_category)->name ?? 'Boat Rides' }}","item":"{{ $pageUrl }}"}
  ]
}
</script>

{{-- JSON-LD: ItemList (listing of boat tours) --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "{{ $blTitle }}",
  "description": "{{ $blDesc }}",
  "url": "{{ $pageUrl }}",
  "numberOfItems": {{ $products->count() }},
  "itemListElement": [
    @foreach($products as $idx => $p)
    {
      "@type": "ListItem",
      "position": {{ $idx + 1 }},
      "item": {
        "@type": "TouristAttraction",
        "name": "{{ addslashes($p->name) }}",
        "description": "{{ addslashes(Str::limit(strip_tags($p->description ?? ''), 150)) }}",
        "url": "{{ route('product.detail', [optional($p->category)->slug ?? 'boat', optional($p->subCategory)->slug ?? 'motor-boat', $p->slug]) }}",
        "image": "{{ !empty($p->images) ? asset('backend/admin/product_images/'.((is_array($p->images)?$p->images:json_decode($p->images,true)??[])[0]??'')) : asset('backend/assets/images/placeholder.jpg') }}",
        "touristType": "Family, Couple, Group",
        "availableLanguage": "Hindi, English",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Varanasi",
          "addressRegion": "Uttar Pradesh",
          "addressCountry": "IN"
        }
        @if(($p->discounted_price ?? 0) > 0)
        ,"offers": {
          "@type": "Offer",
          "price": "{{ $p->discounted_price }}",
          "priceCurrency": "INR",
          "availability": "https://schema.org/InStock"
        }
        @endif
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>

{{-- JSON-LD: LocalBusiness --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Visit Kashi",
  "description": "Varanasi's trusted travel company for boat rides, tours and spiritual experiences on the Ganga.",
  "url": "{{ url('/') }}",
  "telephone": "{{ websiteSetupValue('contact_number') }}",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Varanasi",
    "addressRegion": "Uttar Pradesh",
    "postalCode": "221001",
    "addressCountry": "IN"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "reviewCount": "500",
    "bestRating": "5"
  }
}
</script>
@endsection

@push('styles')
<link rel="preload" href="{{ asset('frontend/css/boat-listing.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-listing.min.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/boat-listing.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-listing.min.css')) }}"></noscript>
<style>
/* ── Breadcrumb ── */
.bl-breadcrumb{background:#f6f8fc;border-bottom:1px solid #ebebeb;padding:10px 0;}
.bl-breadcrumb nav{display:flex;align-items:center;gap:6px;font-size:.78rem;color:#888;flex-wrap:wrap;}
.bl-breadcrumb a{color:#555;text-decoration:none;transition:color .15s;}
.bl-breadcrumb a:hover{color:#0f3460;}
.bl-breadcrumb span{color:#bbb;}

/* ── Hero improvements ── */
.bl-hero{background:linear-gradient(150deg,#050e1f 0%,#0c2a50 50%,#0f3460 100%);padding:56px 0 0;position:relative;overflow:hidden;}
.bl-hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");}
.bl-hero-stats{display:flex;align-items:center;gap:20px;flex-wrap:wrap;margin-top:22px;}
.bl-hero-stat{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:30px;padding:6px 14px;}
.bl-hero-stat-val{color:#fff;font-size:.88rem;font-weight:800;}
.bl-hero-stat-lbl{color:rgba(255,255,255,.6);font-size:.72rem;font-weight:500;}

/* ── Improved card ── */
.bl-card{border-radius:16px;overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.07);transition:transform .25s,box-shadow .25s;display:flex;flex-direction:column;text-decoration:none;color:inherit;}
.bl-card:hover{transform:translateY(-5px);box-shadow:0 16px 40px rgba(0,0,0,.14);text-decoration:none;color:inherit;}
.bl-img-wrap{position:relative;aspect-ratio:4/3;overflow:hidden;background:#e0e8f0;}
.bl-img-wrap::after{content:'';position:absolute;bottom:0;left:0;right:0;height:50%;background:linear-gradient(to top,rgba(0,0,0,.55),transparent);pointer-events:none;z-index:2;}
.bl-img-slide img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s;}
.bl-card:hover .bl-img-slide img{transform:scale(1.06);}

/* Book Now button */
.bl-book-btn{display:flex;align-items:center;justify-content:center;gap:7px;margin-top:12px;padding:10px 0;background:linear-gradient(135deg,#0f3460,#1a5276);color:#fff;border-radius:10px;font-size:.82rem;font-weight:700;letter-spacing:.02em;transition:opacity .2s,transform .2s;text-decoration:none;}
.bl-book-btn:hover{opacity:.9;transform:translateY(-1px);color:#fff;text-decoration:none;}
.bl-book-btn svg{width:14px;height:14px;stroke:#fff;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;}

/* Instant badge on image */
.bl-instant-badge{position:absolute;bottom:12px;left:12px;z-index:4;background:linear-gradient(135deg,#16a085,#1abc9c);color:#fff;font-size:.62rem;font-weight:800;padding:3px 9px;border-radius:20px;letter-spacing:.03em;text-transform:uppercase;}

/* Card body — Airbnb style */
.bl-card-body{padding:10px 0 12px;display:flex;flex-direction:column;flex:1;}
.bl-title{font-size:.9rem;font-weight:700;color:#1a1a1a;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0 0 5px;}
.bl-price-rating-row{display:flex;align-items:center;justify-content:space-between;gap:6px;flex-wrap:nowrap;}
.bl-price-inline{display:flex;align-items:baseline;gap:4px;min-width:0;flex-wrap:wrap;}
.bl-price{font-size:.92rem;font-weight:800;color:#222;}
.bl-price-unit{font-size:.78rem;color:#717171;font-weight:400;}
.bl-price-old{font-size:.78rem;color:#e74c3c;text-decoration:line-through;font-weight:500;}
.bl-rating{display:flex;align-items:center;gap:3px;font-size:.78rem;font-weight:700;color:#1a1a1a;flex-shrink:0;white-space:nowrap;}
.bl-rating svg{fill:#222;}

/* Trust strip */
.bl-trust-strip{background:#f6f9fc;border-top:1px solid #e8eef4;border-bottom:1px solid #e8eef4;padding:16px 0;margin-bottom:32px;}
.bl-trust-strip-inner{display:flex;align-items:center;justify-content:center;gap:32px;flex-wrap:wrap;}
.bl-trust-item{display:flex;align-items:center;gap:8px;font-size:.8rem;color:#444;font-weight:600;}
.bl-trust-item svg{flex-shrink:0;}

/* Section headers */
.bl-section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;}
.bl-section-title{font-size:1.3rem;font-weight:800;color:#111;margin:0;letter-spacing:-.02em;}
.bl-section-sub{font-size:.83rem;color:#888;margin:4px 0 0;}

/* Responsive */
@media(max-width:767px){
  .bl-trust-strip-inner{gap:16px;}
  .bl-hero-stats{gap:10px;}
  .bl-hero-stat{padding:5px 10px;}
}
</style>
@endpush

@section('content')

{{-- ── Breadcrumb (SEO + UX) ── --}}
<div class="bl-breadcrumb">
    <div class="container">
        <nav aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>›</span>
            <a href="{{ route('product.list', 'boat') }}">Boat</a>
            <span>›</span>
            <span>{{ optional($sub_category)->name ?? 'Boat Rides' }}</span>
        </nav>
    </div>
</div>

{{-- ══ HERO ══════════════════════════════════════════════════════════════════ --}}
<div class="bl-hero">
    <div class="bl-hero-ripple"></div>
    <div class="container bl-hero-inner">

        <div class="bl-hero-badge">
            <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Ganga River · Varanasi
        </div>

        <h1>{{ optional($sub_category)->name ?? 'Motor Boat Rides in Varanasi' }}</h1>

        <div class="bl-hero-meta">
            <div class="bl-hero-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Varanasi Ghats, Uttar Pradesh
            </div>
            <div class="bl-hero-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Morning & Evening Rides Available
            </div>
            <div class="bl-hero-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Instant Booking Confirmation
            </div>
        </div>

        <div class="bl-hero-stats">
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">4.9★</span>
                <span class="bl-hero-stat-lbl">Avg Rating</span>
            </div>
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">10,000+</span>
                <span class="bl-hero-stat-lbl">Happy Travelers</span>
            </div>
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">{{ $products->count() }}</span>
                <span class="bl-hero-stat-lbl">Boat Options</span>
            </div>
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">5+ Yrs</span>
                <span class="bl-hero-stat-lbl">Experience</span>
            </div>
        </div>

    </div>
    <svg class="bl-hero-wave" viewBox="0 0 1440 52" fill="#fff" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,32 C240,52 480,10 720,32 C960,54 1200,12 1440,32 L1440,52 L0,52 Z"/>
    </svg>
</div>

{{-- ── Filter Bar ── --}}
<div class="bl-filterbar">
    <div class="container">
        <div class="bl-filterbar-inner">
            <button class="bl-filter-pill active" data-filter="all">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                All Rides
            </button>
            <button class="bl-filter-pill" data-filter="morning">🌅 Morning</button>
            <button class="bl-filter-pill" data-filter="evening">🌆 Evening Aarti</button>
            <button class="bl-filter-pill" data-filter="private">🔒 Private</button>
            <button class="bl-filter-pill" data-filter="group">👥 Group</button>
            <button class="bl-filter-pill" data-filter="event">🎉 Events</button>
            <div class="bl-filter-sep"></div>
            <span class="bl-result-count" id="blResultCount">{{ $products->count() }} ride{{ $products->count() != 1 ? 's' : '' }}</span>
        </div>
    </div>
</div>

@php
    $now            = now();
    $devDiwaliEnd   = \Carbon\Carbon::parse('2026-11-24')->endOfDay();
    $isDevDiwali    = optional($sub_category)->slug === 'dev-diwali-booking'
                      && $now->lte($devDiwaliEnd);
@endphp


{{-- ══ DEV DIWALI ENQUIRY FORM ════════════════════════════════════════════════ --}}
@if($isDevDiwali)
<style>
.dd-enq-section{background:linear-gradient(150deg,#1a0800 0%,#4a1800 50%,#7c2d12 100%);padding:52px 0;}
.dd-enq-row{display:grid;grid-template-columns:1fr 420px;gap:48px;align-items:center;}
.dd-enq-info-wrap{color:#fff;}
.dd-enq-flame{font-size:3.2rem;line-height:1;margin-bottom:16px;display:block;}
.dd-enq-info-title{font-size:1.95rem;font-weight:900;line-height:1.15;margin:0 0 14px;background:linear-gradient(90deg,#fbbf24,#f59e0b,#d97706);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.dd-enq-info-sub{font-size:.95rem;color:rgba(255,255,255,.82);line-height:1.7;margin:0 0 24px;}
.dd-enq-highlights{display:flex;flex-direction:column;gap:10px;}
.dd-enq-hl{display:flex;align-items:flex-start;gap:10px;font-size:.87rem;color:rgba(255,255,255,.85);}
.dd-enq-hl-icon{font-size:.95rem;flex-shrink:0;margin-top:2px;}
.dd-enq-form-card{background:#fff;border-radius:20px;padding:30px;box-shadow:0 20px 60px rgba(0,0,0,.4);}
.dd-enq-form-title{font-size:1.05rem;font-weight:800;color:#1a1a1a;margin:0 0 5px;}
.dd-enq-date-pill{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#7c2d12,#b45309);color:#fff;font-size:.68rem;font-weight:700;padding:4px 12px;border-radius:20px;margin-bottom:20px;letter-spacing:.04em;text-transform:uppercase;}
.dd-enq-fld{margin-bottom:14px;}
.dd-enq-fld label{display:block;font-size:.78rem;font-weight:700;color:#374151;margin-bottom:5px;}
.dd-enq-fld input{width:100%;box-sizing:border-box;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:10px;font-size:.92rem;color:#1a1a1a;outline:none;transition:border-color .2s;}
.dd-enq-fld input:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.12);}
.dd-enq-fld input[readonly]{background:#f9f5f0;color:#555;cursor:default;}
.dd-enq-counter{display:flex;align-items:center;border:1.5px solid #d1d5db;border-radius:10px;overflow:hidden;}
.dd-enq-counter button{background:#f3f4f6;border:none;width:44px;height:44px;font-size:1.3rem;font-weight:700;cursor:pointer;color:#374151;transition:background .15s;flex-shrink:0;line-height:1;}
.dd-enq-counter button:hover{background:#e5e7eb;}
.dd-enq-counter input{flex:1;border:none;outline:none;text-align:center;font-size:1rem;font-weight:700;color:#1a1a1a;background:#fff;}
.dd-enq-submit-btn{width:100%;padding:13px;border:none;border-radius:12px;background:linear-gradient(135deg,#7c2d12,#b45309,#d97706);color:#fff;font-size:.95rem;font-weight:800;cursor:pointer;letter-spacing:.02em;transition:opacity .2s,transform .2s;margin-top:8px;display:flex;align-items:center;justify-content:center;gap:8px;}
.dd-enq-submit-btn:hover{opacity:.9;transform:translateY(-1px);}
.dd-enq-wa-link{display:flex;align-items:center;justify-content:center;gap:8px;color:rgba(255,255,255,.7);font-size:.8rem;text-decoration:none;font-weight:600;margin-top:14px;transition:color .2s;}
.dd-enq-wa-link:hover{color:#fff;text-decoration:none;}
@media(max-width:920px){.dd-enq-row{grid-template-columns:1fr;gap:28px;}.dd-enq-info-wrap{text-align:center;}.dd-enq-highlights{align-items:center;}}
@media(max-width:480px){.dd-enq-section{padding:36px 0;}.dd-enq-form-card{padding:22px;}}
</style>

<section class="dd-enq-section">
    <div class="container">

        @if(session('success'))
        <div style="background:#f0fdf4;border:1.5px solid #86efac;border-radius:12px;padding:14px 20px;text-align:center;color:#166534;font-size:.93rem;font-weight:700;margin-bottom:24px;">
            ✓ {{ session('success') }}
        </div>
        @endif

        <div class="dd-enq-row">

            {{-- Left: Event highlights --}}
            <div class="dd-enq-info-wrap">
                <span class="dd-enq-flame">🪔</span>
                <h2 class="dd-enq-info-title">Dev Diwali Varanasi<br>24 November 2026</h2>
                <p class="dd-enq-info-sub">Watch 1 lakh+ earthen lamps illuminate the sacred Ghats of Varanasi on the most magical night of the year. Secure your exclusive Dev Diwali boat ride today.</p>
                <div class="dd-enq-highlights">
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">🚤</span><span>Exclusive boat ride on the sacred Ganga River</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">🪔</span><span>1 Lakh+ diyas lit on 84 Ghats of Varanasi</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">📅</span><span>Fixed date — 24 November 2026 only</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">📍</span><span>Ravidas Ghat pickup · Per person pricing</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">✅</span><span>Instant WhatsApp confirmation</span></div>
                </div>
            </div>

            {{-- Right: Enquiry form --}}
            <div>
                <div class="dd-enq-form-card">
                    <div class="dd-enq-form-title">Book Dev Diwali Boat Ride</div>
                    <div class="dd-enq-date-pill">🪔 24 November 2026 · Fixed Date</div>

                    <form action="{{ route('enquiry.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_name" value="Dev Diwali Boat Booking – Varanasi 2026">
                        <input type="hidden" name="arrival_date" value="2026-11-24">
                        <input type="hidden" name="pickup_ghat"  value="Ravidas Ghat">
                        <input type="hidden" name="time_slot"    value="">

                        <div class="dd-enq-fld">
                            <label>Full Name <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="name" required placeholder="Your name" value="{{ old('name') }}">
                        </div>
                        <div class="dd-enq-fld">
                            <label>Phone <span style="color:#dc2626;">*</span></label>
                            <input type="tel" name="phone" required placeholder="+91 XXXXX XXXXX" value="{{ old('phone') }}">
                        </div>
                        <div class="dd-enq-fld">
                            <label>Travel Date</label>
                            <input type="text" value="24 November 2026" readonly>
                        </div>
                        <div class="dd-enq-fld">
                            <label>Persons</label>
                            <div class="dd-enq-counter">
                                <button type="button" onclick="ddCount(-1)">&#8722;</button>
                                <input type="number" name="no_of_person" id="ddPersons" value="2" min="1" max="200" readonly>
                                <button type="button" onclick="ddCount(1)">&#43;</button>
                            </div>
                        </div>

                        <button type="submit" class="dd-enq-submit-btn">
                            <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Submit Enquiry
                        </button>
                    </form>
                </div>

                <a href="https://wa.me/917080109917?text=Hi%2C+I+want+to+book+Dev+Diwali+Boat+Ride+on+24+November+2026" target="_blank" rel="noopener noreferrer" class="dd-enq-wa-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2.003C6.477 2.003 2 6.479 2 12.007c0 1.763.463 3.418 1.26 4.861L2 22l5.278-1.243A9.963 9.963 0 0012.004 22c5.527 0 10.004-4.477 10.004-10.004S17.531 2.003 12.004 2.003z"/></svg>
                    WhatsApp: 7080109917
                </a>
            </div>

        </div>
    </div>
</section>
@endif

{{-- ══ BOAT CARDS GRID ════════════════════════════════════════════════════════ --}}
<div class="bl-grid-section">
    <div class="container">

        <div class="bl-section-head">
            <div>
                <h2 class="bl-section-title">Available Boat Rides</h2>
                <p class="bl-section-sub">All rides include life jackets • Professional boatmen • Ganga river experience</p>
            </div>
        </div>

        <div class="bl-grid" id="blGrid">
            @forelse($products as $product)
            @php
                $imgs = (!empty($product->images) && is_array($product->images))
                    ? $product->images
                    : ((!empty($product->images) && is_string($product->images))
                        ? json_decode($product->images, true) ?? []
                        : []);
                $imgUrls = array_map(fn($f) => asset('backend/admin/product_images/'.$f), $imgs);
                $fallback = asset('backend/assets/images/placeholder.jpg');
                if (empty($imgUrls)) $imgUrls = [$fallback];

                $detailUrl = route('product.detail', [
                    optional($product->category)->slug ?? 'boat',
                    optional($product->subCategory)->slug ?? 'motor-boat',
                    $product->slug
                ]);

                $hasDiscount = ($product->base_price ?? 0) > 0
                    && ($product->discounted_price ?? 0) > 0
                    && $product->base_price > $product->discounted_price;
                $pct = $hasDiscount
                    ? round((($product->base_price - $product->discounted_price) / $product->base_price) * 100)
                    : 0;

                $name = strtolower($product->name);
                $tags = [];
                if (str_contains($name, 'morning'))                                                $tags[] = ['🌅', 'Morning Ride'];
                if (str_contains($name, 'evening'))                                                 $tags[] = ['🌆', 'Evening'];
                if (str_contains($name, 'aarti'))                                                   $tags[] = ['🪔', 'Ganga Aarti'];
                if (str_contains($name, 'private'))                                                 $tags[] = ['🔒', 'Private'];
                if (str_contains($name, 'group'))                                                   $tags[] = ['👥', 'Group'];
                if (str_contains($name,'decoration')||str_contains($name,'birthday')||str_contains($name,'anniversary')) $tags[] = ['🎉', 'Event'];
                if (str_contains($name, 'assi'))                                                    $tags[] = ['📍', 'Assi Ghat'];
                if (str_contains($name, 'dashaswamedh'))                                            $tags[] = ['📍', 'Dashashwamedh'];
                if (str_contains($name, 'namo'))                                                    $tags[] = ['📍', 'Namo Ghat'];
                if (empty($tags))                                                                   $tags[] = ['⛵', 'Boat Ride'];
                $tags = array_slice($tags, 0, 3);

                // filter keywords for JS data attribute
                $filterKeys = [];
                if (str_contains($name, 'morning'))  $filterKeys[] = 'morning';
                if (str_contains($name, 'evening') || str_contains($name, 'aarti')) $filterKeys[] = 'evening';
                if (str_contains($name, 'private'))  $filterKeys[] = 'private';
                if (str_contains($name, 'group'))    $filterKeys[] = 'group';
                if (str_contains($name,'decoration')||str_contains($name,'birthday')||str_contains($name,'anniversary')) $filterKeys[] = 'event';
                if (empty($filterKeys)) $filterKeys[] = 'all';
            @endphp

            <article class="bl-card"
                     data-filter="{{ implode(' ', $filterKeys) }}"
                     itemscope itemtype="https://schema.org/Product">

                {{-- Image Slider --}}
                <a href="{{ $detailUrl }}" class="bl-img-wrap" data-cur="0" data-total="{{ count($imgUrls) }}" aria-label="{{ $product->name }} - boat ride photo" style="display:block;text-decoration:none;">
                    <meta itemprop="image" content="{{ $imgUrls[0] }}">

                    <div class="bl-img-track">
                        @foreach($imgUrls as $k => $url)
                        <div class="bl-img-slide">
                            <img src="{{ $url }}"
                                 alt="{{ $product->name }} – boat ride in Varanasi"
                                 loading="{{ $k === 0 ? 'eager' : 'lazy' }}"
                                 width="400" height="300"
                                 onerror="this.src='{{ $fallback }}'">
                        </div>
                        @endforeach
                    </div>

                    @if(count($imgUrls) > 1)
                    <button class="bl-img-arrow prev" aria-label="Previous photo" tabindex="-1">&#8249;</button>
                    <button class="bl-img-arrow next" aria-label="Next photo" tabindex="-1">&#8250;</button>
                    <div class="bl-img-counter">1 / {{ count($imgUrls) }}</div>
                    <div class="bl-dots">
                        @foreach(array_slice($imgUrls, 0, 5) as $k => $u)
                        <div class="bl-dot {{ $k === 0 ? 'active' : '' }}"></div>
                        @endforeach
                    </div>
                    @endif

                    <span class="bl-type-badge">Popular</span>
                    <button class="bl-heart" aria-label="Save to wishlist" tabindex="0">♡</button>
                </a>

                {{-- Card Body --}}
                <div class="bl-card-body" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <meta itemprop="priceCurrency" content="INR">

                    {{-- Title --}}
                    <h3 class="bl-title" itemprop="name">{{ $product->name }}</h3>

                    {{-- Price + Rating row --}}
                    <div class="bl-price-rating-row">
                        <div class="bl-price-inline">
                            @if(($product->discounted_price ?? 0) > 0)
                                @if($hasDiscount)
                                <span class="bl-price-old">₹{{ number_format($product->base_price) }}</span>
                                @endif
                                <span class="bl-price" itemprop="price" content="{{ $product->discounted_price }}">₹{{ number_format($product->discounted_price) }}</span>
                                @if($isDevDiwali)
                                <span class="bl-price-unit" style="color:#b45309;font-weight:700;">🪔 Dev Diwali / person</span>
                                @else
                                <span class="bl-price-unit">/ trip</span>
                                @endif
                            @elseif(($product->base_price ?? 0) > 0)
                                <span class="bl-price" itemprop="price" content="{{ $product->base_price }}">₹{{ number_format($product->base_price) }}</span>
                                @if($isDevDiwali)
                                <span class="bl-price-unit" style="color:#b45309;font-weight:700;">🪔 Dev Diwali / person</span>
                                @else
                                <span class="bl-price-unit">/ trip</span>
                                @endif
                            @else
                                <span class="bl-price-unit" style="font-style:italic;color:#aaa;">Price on request</span>
                            @endif
                        </div>
                        <div class="bl-rating" aria-label="Rating 4.8">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="#222"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            4.8
                        </div>
                    </div>

                </div>
            </article>

            @empty
            <div class="bl-empty">
                <div class="bl-empty-icon">⛵</div>
                <div class="bl-empty-title">No boat rides found</div>
                <div class="bl-empty-sub">Check back soon — more experiences are being added.</div>
            </div>
            @endforelse
        </div>

        <p id="blNoResults" style="display:none;text-align:center;padding:48px;color:#999;font-size:.95rem;">
            No rides match this filter. <button onclick="blSetFilter('all')" style="border:none;background:none;color:#0f3460;font-weight:700;cursor:pointer;font-size:.95rem;">Show all →</button>
        </p>

    </div>
</div>

{{-- ── YouTube Shorts Section ── --}}
@php
    use App\Models\YoutubeVideo;
    $shortVideos = YoutubeVideo::active()->orderBy('sort_order')->orderByDesc('id')->limit(8)->get();
@endphp
@if($shortVideos->isNotEmpty())
<section class="bl-yt-section" aria-label="Boat ride videos">
    <div class="container">
        <div class="bl-yt-header">
            <div class="bl-yt-header-left">
                <div class="bl-yt-logo" aria-hidden="true">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none"><rect width="28" height="20" rx="4" fill="#FF0000"/><path d="M11 6l8 4-8 4V6z" fill="#fff"/></svg>
                </div>
                <div>
                    <h2 class="bl-yt-title">Boat Ride Videos</h2>
                    <p class="bl-yt-sub">Real experiences captured on the sacred Ganga river</p>
                </div>
            </div>
            <a href="https://www.youtube.com/@visitkashi" target="_blank" rel="noopener noreferrer" class="bl-yt-subscribe" aria-label="Subscribe to Visit Kashi on YouTube">
                <svg width="16" height="12" viewBox="0 0 28 20" fill="none"><rect width="28" height="20" rx="4" fill="#fff" fill-opacity=".3"/><path d="M11 6l8 4-8 4V6z" fill="#fff"/></svg>
                Subscribe
            </a>
        </div>
        <div class="bl-yt-grid">
            @foreach($shortVideos as $vid)
            @if($vid->video_id)
            <div class="bl-yt-card" onclick="blYtOpen('{{ $vid->video_id }}', '{{ e($vid->title) }}')" role="button" tabindex="0" aria-label="Play {{ e($vid->title ?? 'boat ride video') }}">
                <div class="bl-yt-thumb">
                    <img src="https://img.youtube.com/vi/{{ $vid->video_id }}/hqdefault.jpg"
                         alt="{{ e($vid->title ?? 'Varanasi boat ride video') }}"
                         loading="lazy" width="300" height="533"
                         onerror="this.src='https://img.youtube.com/vi/{{ $vid->video_id }}/default.jpg'">
                    <div class="bl-yt-play-btn" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="bl-yt-shorts-badge" aria-hidden="true">
                        <svg width="10" height="14" viewBox="0 0 10 14" fill="none"><path d="M5.8 0L0 7.5h4.2L4.2 14 10 6.5H5.8L5.8 0z" fill="#fff"/></svg>
                        Shorts
                    </span>
                </div>
                @if($vid->title)
                <p class="bl-yt-caption">{{ $vid->title }}</p>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>

<div class="bl-yt-modal" id="blYtModal" role="dialog" aria-modal="true" aria-label="Video player">
    <div class="bl-yt-modal-box">
        <button class="bl-yt-modal-close" onclick="blYtClose()" aria-label="Close video">&#x2715;</button>
        <p class="bl-yt-modal-title" id="blYtModalTitle"></p>
        <div class="bl-yt-iframe-wrap">
            <iframe id="blYtIframe" src="" allowfullscreen frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture" title="YouTube video"></iframe>
        </div>
    </div>
</div>
@endif

{{-- ── Instagram Reels Section ── --}}
@php
    use App\Models\InstagramReel;
    $listingReels = InstagramReel::active()->orderBy('sort_order')->orderByDesc('id')->limit(5)->get();
    function isReelUrl($url) {
        return preg_match('#instagram\.com/(reel|p)/[a-zA-Z0-9_-]+#', $url);
    }
@endphp


<style>
.bl-yt-section{padding:52px 0 40px;background:#fff;border-top:1px solid #f0f0f0;}
.bl-yt-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;flex-wrap:wrap;gap:14px;}
.bl-yt-header-left{display:flex;align-items:center;gap:14px;}
.bl-yt-logo{width:48px;height:34px;display:flex;align-items:center;justify-content:center;background:#FF0000;border-radius:10px;flex-shrink:0;}
.bl-yt-title{font-size:1.28rem;font-weight:800;color:#111;margin:0 0 3px;}
.bl-yt-sub{font-size:.83rem;color:#888;margin:0;}
.bl-yt-subscribe{display:inline-flex;align-items:center;gap:7px;background:#FF0000;color:#fff;font-size:.83rem;font-weight:700;padding:9px 20px;border-radius:22px;text-decoration:none;transition:background .2s,transform .2s;white-space:nowrap;}
.bl-yt-subscribe:hover{background:#cc0000;transform:translateY(-2px);color:#fff;text-decoration:none;}
.bl-yt-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
.bl-yt-card{cursor:pointer;border-radius:14px;overflow:hidden;background:#000;box-shadow:0 3px 14px rgba(0,0,0,.12);transition:transform .3s,box-shadow .3s;}
.bl-yt-card:hover{transform:translateY(-5px) scale(1.02);box-shadow:0 12px 32px rgba(0,0,0,.22);}
.bl-yt-thumb{position:relative;aspect-ratio:9/16;overflow:hidden;}
.bl-yt-thumb img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s;}
.bl-yt-card:hover .bl-yt-thumb img{transform:scale(1.06);}
.bl-yt-play-btn{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:52px;height:52px;border-radius:50%;background:rgba(255,0,0,.85);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(255,0,0,.5);transition:transform .2s,background .2s;}
.bl-yt-card:hover .bl-yt-play-btn{transform:translate(-50%,-50%) scale(1.12);background:#ff0000;}
.bl-yt-shorts-badge{position:absolute;bottom:10px;left:10px;background:rgba(0,0,0,.7);color:#fff;font-size:.65rem;font-weight:700;padding:3px 8px;border-radius:4px;display:flex;align-items:center;gap:4px;letter-spacing:.04em;}
.bl-yt-caption{font-size:.78rem;font-weight:600;color:#222;padding:9px 10px 10px;margin:0;background:#fff;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.bl-yt-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:10000;align-items:center;justify-content:center;}
.bl-yt-modal.open{display:flex;}
.bl-yt-modal-box{position:relative;width:90vw;max-width:420px;}
.bl-yt-modal-close{position:absolute;top:-44px;right:0;background:rgba(255,255,255,.15);border:none;color:#fff;width:36px;height:36px;border-radius:50%;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;}
.bl-yt-modal-close:hover{background:rgba(255,255,255,.3);}
.bl-yt-modal-title{color:#fff;font-size:.88rem;font-weight:600;margin:0 0 10px;text-align:center;padding:0 36px;}
.bl-yt-iframe-wrap{position:relative;padding-bottom:177.78%;height:0;border-radius:12px;overflow:hidden;}
.bl-yt-iframe-wrap iframe{position:absolute;inset:0;width:100%;height:100%;}
@media(max-width:640px){.bl-yt-grid{grid-template-columns:repeat(2,1fr);gap:8px;}}

.bl-ig-section{padding:52px 0 44px;background:#fafafa;border-top:1px solid #efefef;}
.bl-ig-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:14px;}
.bl-ig-header-left{display:flex;align-items:center;gap:14px;}
.bl-ig-logo-wrap{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);flex-shrink:0;}
.bl-ig-title{font-size:1.3rem;font-weight:800;color:#111;margin:0 0 3px;}
.bl-ig-sub{font-size:.83rem;color:#888;margin:0;}
.bl-ig-handle{color:#dc2743;font-weight:600;text-decoration:none;}
.bl-ig-handle:hover{text-decoration:underline;}
.bl-ig-follow-btn{display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;font-size:.82rem;font-weight:700;padding:9px 20px;border-radius:22px;text-decoration:none;transition:transform .2s,opacity .2s;white-space:nowrap;}
.bl-ig-follow-btn:hover{transform:translateY(-2px);opacity:.92;color:#fff;text-decoration:none;}
.bl-ig-embeds{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;align-items:start;}
.bl-ig-embed-wrap{width:100%;}
.bl-ig-embed-wrap .instagram-media{min-width:0!important;width:100%!important;}
.bl-reel-thumb-card{display:block;text-decoration:none;border-radius:12px;overflow:hidden;box-shadow:0 3px 14px rgba(0,0,0,.12);transition:transform .25s;}
.bl-reel-thumb-card:hover{transform:translateY(-4px);text-decoration:none;}
.bl-reel-thumb-card img{width:100%;aspect-ratio:9/16;object-fit:cover;display:block;}
.bl-reel-thumb-placeholder{aspect-ratio:9/16;background:linear-gradient(135deg,#fff0f5,#ffecd2);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;}
.bl-reel-thumb-placeholder span{font-size:.78rem;font-weight:600;color:#dc2743;}
.bl-reel-thumb-title{font-size:.78rem;color:#555;padding:8px 10px;margin:0;background:#fff;}
.bl-ig-setup{text-align:center;padding:40px 20px;background:linear-gradient(135deg,#fff5f5,#fff0fa);border:1.5px dashed #f5b0c0;border-radius:16px;}
.bl-ig-setup-icon{margin:0 auto 14px;width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);display:flex;align-items:center;justify-content:center;}
.bl-ig-setup h3{font-size:1.1rem;font-weight:700;color:#1a1a1a;margin:0 0 8px;}
.bl-ig-setup p{font-size:.88rem;color:#666;margin:0;line-height:1.7;}
.bl-ig-footer{text-align:center;margin-top:24px;}
.bl-ig-view-all{display:inline-flex;align-items:center;gap:8px;font-size:.88rem;font-weight:700;color:#dc2743;text-decoration:none;border:1.5px solid #dc2743;border-radius:24px;padding:9px 24px;transition:background .2s,color .2s;}
.bl-ig-view-all:hover{background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);color:#fff;border-color:transparent;text-decoration:none;}
@media(max-width:1024px){.bl-ig-embeds{grid-template-columns:repeat(3,1fr);}}
@media(max-width:768px){.bl-ig-embeds{grid-template-columns:repeat(2,1fr);}.bl-ig-header{flex-direction:column;align-items:flex-start;}}
@media(max-width:480px){.bl-ig-embeds{grid-template-columns:1fr;}}
</style>
@endsection

@push('scripts')
<script>
@if($isDevDiwali)
window.ddCount = function(n) {
    var el = document.getElementById('ddPersons');
    if (!el) return;
    var v = Math.max(1, Math.min(200, (parseInt(el.value) || 1) + n));
    el.value = v;
};
@endif

(function () {

    /* ── YouTube popup ── */
    var ytModal  = document.getElementById('blYtModal');
    var ytIframe = document.getElementById('blYtIframe');
    var ytTitle  = document.getElementById('blYtModalTitle');
    window.blYtOpen = function(id, title) {
        if (!ytModal) return;
        ytIframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0&playsinline=1';
        if (ytTitle) ytTitle.textContent = title || '';
        ytModal.classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.blYtClose = function() {
        if (!ytModal) return;
        ytIframe.src = '';
        ytModal.classList.remove('open');
        document.body.style.overflow = '';
    };
    if (ytModal) {
        ytModal.addEventListener('click', function(e) { if (e.target === ytModal) blYtClose(); });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && ytModal && ytModal.classList.contains('open')) blYtClose();
    });

    /* ── Filter pills (functional) ── */
    var allCards   = document.querySelectorAll('.bl-card');
    var noResults  = document.getElementById('blNoResults');
    var countEl    = document.getElementById('blResultCount');

    window.blSetFilter = function(filter) {
        document.querySelectorAll('.bl-filter-pill').forEach(function(p){ p.classList.remove('active'); });
        var btn = document.querySelector('[data-filter="' + filter + '"]');
        if (btn) btn.classList.add('active');
        var visible = 0;
        allCards.forEach(function(card) {
            var keys = card.dataset.filter || 'all';
            var show = filter === 'all' || keys.includes(filter);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (noResults) noResults.style.display = visible === 0 ? '' : 'none';
        if (countEl) countEl.textContent = visible + ' ride' + (visible !== 1 ? 's' : '');
    };

    document.querySelectorAll('.bl-filter-pill').forEach(function(pill) {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            blSetFilter(this.dataset.filter || 'all');
        });
    });

    /* ── Heart toggle ── */
    document.querySelectorAll('.bl-heart').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            var saved = this.classList.toggle('saved');
            this.textContent = saved ? '♥' : '♡';
            this.style.color = saved ? '#e31c5f' : '';
        });
    });

    /* ── Image slider per card ── */
    document.querySelectorAll('.bl-img-wrap[data-total]').forEach(function(wrap) {
        var track   = wrap.querySelector('.bl-img-track');
        var prevBtn = wrap.querySelector('.bl-img-arrow.prev');
        var nextBtn = wrap.querySelector('.bl-img-arrow.next');
        var counter = wrap.querySelector('.bl-img-counter');
        var dots    = wrap.querySelectorAll('.bl-dot');
        var total   = parseInt(wrap.dataset.total) || 1;
        var cur     = 0;
        if (total <= 1 || !track) return;

        function go(idx) {
            cur = (idx + total) % total;
            track.style.transform = 'translateX(-' + (cur * 100) + '%)';
            if (counter) counter.textContent = (cur + 1) + ' / ' + total;
            dots.forEach(function(d, i) { d.classList.toggle('active', i === cur); });
        }

        if (prevBtn) prevBtn.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); go(cur - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); go(cur + 1); });

        var startX = 0;
        track.addEventListener('touchstart', function(e) { startX = e.touches[0].clientX; }, { passive: true });
        track.addEventListener('touchend', function(e) {
            var dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 40) go(dx < 0 ? cur + 1 : cur - 1);
        });
    });

})();
</script>
@endpush
