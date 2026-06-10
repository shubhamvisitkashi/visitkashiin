@extends('frontend.layouts.app')

@section('meta')
@isset($product)
@php
    $isFestival = str_contains(strtolower(optional($product->category)->slug ?? ''), 'festival');
    $tourImg    = (!empty($product->images) && is_array($product->images))
                    ? asset('backend/admin/product_images/' . $product->images[0])
                    : asset('frontend/images/logo1.png');
    $pageTitle  = (optional($product)->meta_title ?: optional($product)->name) . ' in Varanasi | Visit Kashi';
    $pageDesc   = optional($product)->meta_description
                    ?: 'Explore ' . optional($product)->name . ' in Varanasi — one of India\'s most sacred cities. Book experiences with Visit Kashi, the city\'s most trusted travel platform.';
    $pageKw     = optional($product)->meta_keyword ?? '';
    $canonUrl   = url()->current();
    $catName    = optional($product->category)->name ?? 'Festivals';
    $catSlugLD  = optional($product->category)->slug ?? 'festivals';
@endphp

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
        { "@type": "ListItem", "position": 2, "name": "{{ addslashes($catName) }}", "item": "{{ route('product.list', $catSlugLD) }}" },
        { "@type": "ListItem", "position": 3, "name": "{{ addslashes(optional($product)->name ?? '') }}", "item": "{{ $canonUrl }}" }
      ]
    },
    @if($isFestival)
    {
      "@type": "Event",
      "name": "{{ addslashes(optional($product)->name ?? '') }}",
      "description": "{{ addslashes(Str::limit(strip_tags($pageDesc), 200)) }}",
      "image": "{{ $tourImg }}",
      "url": "{{ $canonUrl }}",
      "eventStatus": "https://schema.org/EventScheduled",
      "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
      "location": {
        "@type": "Place",
        "name": "Varanasi, Uttar Pradesh",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Varanasi",
          "addressRegion": "Uttar Pradesh",
          "addressCountry": "IN"
        }
      },
      "organizer": {
        "@type": "Organization",
        "name": "Visit Kashi",
        "url": "{{ url('/') }}"
      }
    }
    @else
    {
      "@type": "TouristAttraction",
      "name": "{{ addslashes(optional($product)->name ?? '') }}",
      "description": "{{ addslashes(Str::limit(strip_tags($pageDesc), 200)) }}",
      "image": "{{ $tourImg }}",
      "url": "{{ $canonUrl }}",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Varanasi",
        "addressRegion": "Uttar Pradesh",
        "addressCountry": "IN"
      }
    }
    @endif
  ]
}
</script>

<link rel="canonical" href="{{ $canonUrl }}">
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ Str::limit(strip_tags($pageDesc), 160) }}">
@if($pageKw)<meta name="keywords" content="{{ $pageKw }}">@endif
<meta name="robots" content="index, follow">
<meta property="og:type" content="{{ $isFestival ? 'event' : 'article' }}" />
<meta property="og:url" content="{{ $canonUrl }}" />
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($pageDesc), 200) }}">
<meta property="og:image" content="{{ $tourImg }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($pageDesc), 200) }}">
<meta name="twitter:image" content="{{ $tourImg }}">
@endisset
@endsection

@section('content')
@php
    $isFestival   = $isFestival ?? str_contains(strtolower(optional($product->category)->slug ?? ''), 'festival');
    $catName      = $catName    ?? (optional($product->category)->name ?? 'Festivals');
    $catSlugLD    = $catSlugLD  ?? (optional($product->category)->slug ?? 'festivals');
@endphp

@push('styles')
<style>
/* ── Tour / Festival Detail — SEO heading styles ── */
.td-breadcrumb { display:flex; align-items:center; gap:6px; font-size:13px; color:#666; justify-content:center; flex-wrap:wrap; margin-bottom:10px; }
.td-breadcrumb a { color:#444; text-decoration:none; }
.td-breadcrumb a:hover { color:#D94F2B; text-decoration:underline; }
.td-breadcrumb span { color:#bbb; }
.td-hero-title { font-size:clamp(22px,3.5vw,40px); font-weight:800; color:#1a1a1a; margin:0 0 8px; line-height:1.15; letter-spacing:-.02em; }
.td-hero-sub { font-size:14px; color:#555; margin:0; }
/* Content headings */
.detail-title h2 { font-size:1.15rem; font-weight:700; color:#1a1a1a; margin:0 0 8px; }
.detail-title h2.td-section { font-size:1.05rem; font-weight:700; color:#333; border-left:3px solid #D94F2B; padding-left:10px; margin:0 0 10px; }
/* Festival sidebar */
.fest-sidebar { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.10); overflow:hidden; }
.fest-sidebar__head { background:linear-gradient(135deg,#D94F2B 0%,#e8622e 100%); padding:16px 18px; }
.fest-sidebar__head h3 { font-size:.95rem; font-weight:700; color:#fff; margin:0 0 2px; }
.fest-sidebar__head p { font-size:.75rem; color:rgba(255,255,255,.8); margin:0; }
.fest-sidebar__list { padding:0 12px 4px; }
.fest-card { display:flex; gap:12px; align-items:flex-start; padding:12px 6px; border-bottom:1px solid #f0f0f0; text-decoration:none !important; }
.fest-card:last-child { border-bottom:none; }
.fest-card__img { width:72px; height:54px; border-radius:8px; object-fit:cover; flex-shrink:0; }
.fest-card__body { flex:1; min-width:0; }
.fest-card__name { font-size:.83rem; font-weight:700; color:#1a1a1a; line-height:1.3; margin:0 0 3px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.fest-card__sub { font-size:.72rem; color:#888; }
.fest-card:hover .fest-card__name { color:#D94F2B; }
.fest-sidebar__divider { font-size:.7rem; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:.05em; padding:8px 18px 6px; margin:0; display:block; }
.fest-sidebar__cta { padding:0 18px 18px; }
.fest-sidebar__wa { display:flex; align-items:center; justify-content:center; gap:8px; background:#25D366; color:#fff; padding:12px; border-radius:10px; font-size:.88rem; font-weight:700; text-decoration:none !important; }
.fest-sidebar__wa:hover { opacity:.9; }
@media(max-width:991px){ .fest-sidebar { position:relative; top:auto; margin-bottom:24px; } }
</style>
@endpush

    <section class="breadcrumb-outer text-center">
        <div class="container">
            <div class="breadcrumb-content">
                <nav aria-label="breadcrumb" class="td-breadcrumb">
                    <a href="{{ route('index') }}">Home</a>
                    <span>›</span>
                    <a href="{{ route('product.list', $catSlugLD) }}">{{ $catName }}</a>
                    <span>›</span>
                    <span>{{ optional($product)->name }}</span>
                </nav>
                <h1 class="td-hero-title">{{ optional($product)->name ?? 'Festival Details' }}</h1>
                @if(optional($product)->address)
                <p class="td-hero-sub"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $product->address }}</p>
                @endif
            </div>
        </div>
        <div class="section-overlay"></div>
    </section>

    <section class="main-content detail">
        <div class="container">
            <div class="row">
                @php $hideSidebar = in_array(optional($product->category)->slug, ['things-to-do', 'sights']); @endphp
                <div id="content" class="{{ $hideSidebar ? 'col-lg-12' : 'col-lg-8' }}">
                    <div class="detail-content content-wrapper">
                        {{-- <div class="detail-info">
                        <div class="detail-info-content clearfix">
                            @if ($product->discounted_price != 0)
                                <p class="detail-info-price"><span class="bold">₹ {{$product->discounted_price}}</span></p>
                            @endif
                            <div class="deal-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star-o"></span>
                                <span class="fa fa-star-o"></span>
                            </div>
                        </div>
                    </div> --}}
                        {{-- ── Airbnb-style gallery ── --}}
                        @php
                            $galleryImgs = (!empty($product->images) && is_array($product->images))
                                ? $product->images : [];
                            $galleryUrls = array_map(function($img){
                                return asset('backend/admin/product_images/'.$img);
                            }, $galleryImgs);
                            $total = count($galleryImgs);
                            $placeholder = asset('backend/assets/images/placeholder.jpg');
                        @endphp

                        <style>
                        /* ── Gallery grid ── */
                        .ab-wrap{position:relative;margin-bottom:28px;}
                        .ab-grid{
                            display:grid;
                            gap:2px;
                            height:530px;
                            overflow:hidden;
                        }
                        /* 1 image */
                        .ab-grid.g1{grid-template-columns:1fr;grid-template-rows:1fr;}
                        /* 2 images */
                        .ab-grid.g2{grid-template-columns:5fr 3fr;grid-template-rows:1fr;}

                        .ab-cell{
                            overflow:hidden;
                            background:#ddd;
                            cursor:pointer;
                            position:relative;
                        }
                        .ab-cell img{
                            width:100%;height:100%;
                            object-fit:cover;
                            display:block;
                            transition:transform .4s ease,filter .3s;
                        }
                        .ab-cell:hover img{transform:scale(1.04);filter:brightness(.92);}

                        /* 2:1:1 — big left + 2 stacked right */
                        .ab-grid.ab-lr{
                            display:grid;
                            grid-template-columns:5fr 3fr;
                            grid-template-rows:530px;
                            gap:2px;
                            height:530px;
                            overflow:hidden;
                        }
                        .ab-grid.ab-lr .ab-cell-left{
                            overflow:hidden;cursor:pointer;position:relative;
                            height:100%;
                        }
                        .ab-grid.ab-lr .ab-cell-left img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease,filter .3s;}
                        .ab-grid.ab-lr .ab-cell-left:hover img{transform:scale(1.04);filter:brightness(.92);}
                        /* right side: 2 rows stacked, fills full height */
                        .ab-right{
                            display:grid;
                            grid-template-columns:1fr;
                            grid-template-rows:1fr 1fr;
                            gap:2px;
                            height:100%;
                        }

                        /* "Show all photos" button */
                        .ab-btn{
                            position:absolute;
                            bottom:16px;right:16px;
                            background:#fff;
                            border:1.5px solid #222;
                            border-radius:8px;
                            padding:8px 16px;
                            font-size:13px;font-weight:600;
                            cursor:pointer;
                            display:flex;align-items:center;gap:7px;
                            box-shadow:0 1px 4px rgba(0,0,0,.18);
                            z-index:10;
                            transition:background .15s;
                            line-height:1;
                        }
                        .ab-btn:hover{background:#f7f7f7;}
                        .ab-btn svg{flex-shrink:0;}

                        /* ── Lightbox ── */
                        .ab-lb{
                            display:none;position:fixed;inset:0;
                            background:#000;
                            z-index:9999;
                            flex-direction:column;
                        }
                        .ab-lb.open{display:flex;}
                        /* top bar */
                        .ab-lb-bar{
                            display:flex;align-items:center;justify-content:space-between;
                            padding:14px 20px;
                            flex-shrink:0;
                        }
                        .ab-lb-bar-left{color:#fff;font-size:14px;font-weight:500;}
                        .ab-lb-bar-right{display:flex;gap:10px;}
                        .ab-lb-close-btn{
                            background:rgba(255,255,255,.12);border:none;
                            color:#fff;border-radius:50%;width:36px;height:36px;
                            font-size:18px;cursor:pointer;
                            display:flex;align-items:center;justify-content:center;
                            transition:background .2s;
                        }
                        .ab-lb-close-btn:hover{background:rgba(255,255,255,.25);}
                        /* main image area */
                        .ab-lb-stage{
                            flex:1;display:flex;align-items:center;justify-content:center;
                            position:relative;overflow:hidden;padding:0 70px;
                        }
                        .ab-lb-img{
                            max-width:100%;max-height:100%;
                            object-fit:contain;
                            border-radius:4px;
                            display:block;
                        }
                        .ab-lb-arrow{
                            position:absolute;top:50%;transform:translateY(-50%);
                            background:#fff;border:none;border-radius:50%;
                            width:44px;height:44px;font-size:18px;
                            cursor:pointer;
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 2px 12px rgba(0,0,0,.4);
                            transition:transform .15s,box-shadow .15s;
                            z-index:2;
                        }
                        .ab-lb-arrow:hover{transform:translateY(-50%) scale(1.08);}
                        .ab-lb-prev{left:16px;} .ab-lb-next{right:16px;}
                        /* thumbnail strip */
                        .ab-lb-strip{
                            flex-shrink:0;
                            display:flex;gap:6px;
                            padding:12px 20px 16px;
                            overflow-x:auto;
                            justify-content:center;
                        }
                        .ab-lb-strip::-webkit-scrollbar{height:4px;}
                        .ab-lb-strip::-webkit-scrollbar-thumb{background:rgba(255,255,255,.3);border-radius:2px;}
                        .ab-lb-strip img{
                            height:64px;width:86px;object-fit:cover;
                            border-radius:6px;cursor:pointer;flex-shrink:0;
                            opacity:.5;border:2.5px solid transparent;
                            transition:opacity .2s,border-color .2s;
                        }
                        .ab-lb-strip img.ab-active{opacity:1;border-color:#fff;}

                        @media(max-width:767px){
                            .ab-grid,.ab-grid.ab-lr{height:260px;grid-template-rows:260px;}
                            .ab-lb-stage{padding:0 52px;}
                            .ab-lb-prev{left:6px;} .ab-lb-next{right:6px;}
                        }
                        </style>

                        <div class="ab-wrap">

                        @if($total === 0)
                            {{-- no images --}}
                            <div class="ab-grid g1">
                                <div class="ab-cell"><img src="{{ $placeholder }}" alt="{{ $product->name }}"></div>
                            </div>

                        @elseif($total === 1)
                            <div class="ab-grid g1">
                                <div class="ab-cell" onclick="abOpen(0)">
                                    <img src="{{ $galleryUrls[0] }}" alt="{{ $product->name }}">
                                </div>
                            </div>

                        @elseif($total === 2)
                            <div class="ab-grid g2">
                                @foreach($galleryUrls as $i => $url)
                                <div class="ab-cell" onclick="abOpen({{ $i }})">
                                    <img src="{{ $url }}" alt="{{ $product->name }}">
                                </div>
                                @endforeach
                            </div>

                        @else
                            {{-- 3+ images: 2:1:1 — big left + 2 stacked right --}}
                            <div class="ab-grid ab-lr">
                                <div class="ab-cell-left" onclick="abOpen(0)">
                                    <img src="{{ $galleryUrls[0] }}" alt="{{ $product->name }}">
                                </div>
                                <div class="ab-right">
                                    <div class="ab-cell" onclick="abOpen(1)">
                                        <img src="{{ $galleryUrls[1] }}" alt="{{ $product->name }}">
                                    </div>
                                    @if(!empty($galleryUrls[2]))
                                    <div class="ab-cell" onclick="abOpen(2)">
                                        <img src="{{ $galleryUrls[2] }}" alt="{{ $product->name }}">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($total > 1)
                        <button class="ab-btn" onclick="abOpen(0)">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="1" width="6" height="6" rx="1.2" stroke="#222" stroke-width="1.5"/>
                                <rect x="9" y="1" width="6" height="6" rx="1.2" stroke="#222" stroke-width="1.5"/>
                                <rect x="1" y="9" width="6" height="6" rx="1.2" stroke="#222" stroke-width="1.5"/>
                                <rect x="9" y="9" width="6" height="6" rx="1.2" stroke="#222" stroke-width="1.5"/>
                            </svg>
                            Show all photos
                        </button>
                        @endif
                        </div>{{-- .ab-wrap --}}

                        {{-- ── Lightbox ── --}}
                        <div class="ab-lb" id="abLightbox" role="dialog" aria-modal="true">
                            <div class="ab-lb-bar">
                                <span class="ab-lb-bar-left" id="abLbCounter"></span>
                                <div class="ab-lb-bar-right">
                                    <button class="ab-lb-close-btn" onclick="abClose()" title="Close">&#x2715;</button>
                                </div>
                            </div>
                            <div class="ab-lb-stage">
                                <button class="ab-lb-arrow ab-lb-prev" onclick="abNav(-1)" title="Previous">&#8592;</button>
                                <img class="ab-lb-img" id="abLbImg" src="" alt="">
                                <button class="ab-lb-arrow ab-lb-next" onclick="abNav(1)" title="Next">&#8594;</button>
                            </div>
                            <div class="ab-lb-strip" id="abLbStrip"></div>
                        </div>

                        <script>
                        (function(){
                            var imgs = @json($galleryUrls);
                            var cur  = 0;
                            var lb      = document.getElementById('abLightbox');
                            var lbImg   = document.getElementById('abLbImg');
                            var lbCtr   = document.getElementById('abLbCounter');
                            var lbStrip = document.getElementById('abLbStrip');

                            imgs.forEach(function(src, i){
                                var t = document.createElement('img');
                                t.src = src; t.alt = '';
                                t.addEventListener('click', function(){ abGo(i); });
                                lbStrip.appendChild(t);
                            });

                            window.abOpen = function(i){ cur = i; lb.classList.add('open'); document.body.style.overflow = 'hidden'; render(); };
                            window.abClose = function(){ lb.classList.remove('open'); document.body.style.overflow = ''; };
                            window.abNav   = function(d){ abGo((cur + d + imgs.length) % imgs.length); };
                            function abGo(i){ cur = i; render(); }
                            function render(){
                                lbImg.src = imgs[cur] || '';
                                lbCtr.textContent = (cur + 1) + ' / ' + imgs.length;
                                lbStrip.querySelectorAll('img').forEach(function(t, i){ t.classList.toggle('ab-active', i === cur); });
                                var active = lbStrip.querySelectorAll('img')[cur];
                                if(active) active.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
                            }
                            lb.addEventListener('click', function(e){ if(e.target === lb || e.target === lbImg.parentElement) abClose(); });
                            document.addEventListener('keydown', function(e){
                                if(!lb.classList.contains('open')) return;
                                if(e.key === 'ArrowRight') abNav(1);
                                if(e.key === 'ArrowLeft')  abNav(-1);
                                if(e.key === 'Escape')     abClose();
                            });
                        })();
                        </script>
                        <div class="description detail-box">
                            <div class="detail-title">
                                <h2>About {{ $product->name }}</h2>
                                @if ($product->address)
                                    <p><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $product->address }}</p>
                                @endif
                            </div>
                        </div>
                        @if ($product->youtube_link)
                            <div class="comments-form detail-box">
                                <div class="detail-title">
                                    <h2 class="td-section">Watch: {{ $product->name }}</h2>
                                </div>
                                <iframe src="{{ $product->youtube_link }}" frameborder="1" width="100%"
                                    height="500px"></iframe>
                            </div>
                        @endif
                        <div class="description detail-box">
                            <div class="description-content">
                                {!! safe_html($product->description) !!}
                            </div>
                        </div>
                        @if ($product->map_location)
                            <div class="location-map detail-box">
                                <div class="detail-title">
                                    <h2 class="td-section">Location &amp; Map</h2>
                                </div>
                                <div class="map-frame">
                                    <iframe src="{{ $product->map_location }}" style="border: 0" allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                        {{-- <div class="detail-timeline detail-box">
                        <div class="detail-title">
                            <h3>Tour Timeline</h3>
                        </div>
                        <div class="timeline-content">
                            <ul class="timeline">

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>1</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 1 - 2 : Flights to Kathmandu.</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Passenger flights to Lukla. Begin the trek through the Khumbu to
                                                Base Camp.Tourist attractions people foreign sleep overnight
                                                housing. Gerimrany group discount tour operator. Airplane
                                                couchsurfing Moi scow ma ps uncharted luxury train guest tour
                                                operator
                                                German y busre laxation. Paris overnight Japan Tripit territory
                                                international carren tal Pacific outdoor Turkey. Country
                                                international to urist attractions mil es train Moscow guide. Japan
                                                horse riding money Bacel ona Buda pest yach.
                                            </p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>3</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 3 : Arrive Kathmandu</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Arrive in Kathmandu and relax while enjoying the color and energy of
                                                Nepal’s capital city. Duffels of personal climbing gear and
                                                high-altitude clothing will be collected for the cargo flights to
                                                Lukla and will be waiting for you at Base Camp.
                                            </p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>4</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 4 - 5 : Enjoy Kathmandu</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Enjoy Kathmandu with a city tour and attend any governmental and
                                                media affairs involving team members.Tourist attractions people
                                                foreign sleep overnight housing. Gerimrany group discount tour
                                                operator. Airplane couchsurfing Moi scow ma ps uncharted luxury
                                                train
                                                guest tour operator German y busre laxation. Paris overnight Japan
                                                Tripit territory international carren tal Pacific outdoor Turkey.
                                                Country international to urist attractions mil es train Moscow
                                                guide. Japan horse riding money Bacel ona Buda pest yach.
                                            </p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>6</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 6 : Fly to Lukla</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Passenger flights to Lukla. Begin the trek through the Khumbu to
                                                Base Camp.Tourist attractions people foreign sleep overnight
                                                housing. Gerimrany group discount tour operator. Airplane
                                                couchsurfing Moi scow ma ps uncharted luxury train guest tour
                                                operator
                                                German y busre laxation. Paris overnight Japan Tripit territory
                                                international carren tal Pacific outdoor Turkey. Country
                                                international to urist attractions mil es train Moscow guide. Japan
                                                horse riding money Bacel ona Buda pest yach.
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>7</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 7 - 15 : Trek to Base Camp</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Trek to Base Camp, taking plenty of time to acclimatize and to visit
                                                the Sherpa families and support facilities that will become
                                                increasingly important during our expedition. We will spend several
                                                days in Namche ahead of most trekkers, and will visit the
                                                monasteries in Tengboche and Pangboche. Additional acclimatization
                                                days are scheduled at Namche (11,400ft/3,475m) and Pheriche
                                                (14,000ft/4,267m).
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                        {{-- <div class="top-attractions detail-box">
                        <div class="detail-title">
                            <h3>Hotels and Availabilities</h3>
                        </div>
                        <div class="top-attraction-content">
                            <div class="att-item clearfix">
                                <div class="att-image">
                                    <img loading="lazy" src="{{asset('frontend/images/bucket1.jpg')}}" alt="Images" />
                                </div>
                                <div class="att-content">
                                    <div class="att-content-left">
                                        <h4>Phulay Bay Resort</h4>
                                        <ul>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Wifi</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Parking</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Swimming Pool</li>
                                        </ul>
                                    </div>
                                    <div class="att-content-right">
                                        <p>Starting from <span class="bold">Rs. 1500</span></p>
                                        <p>1 night / 3 person</p>
                                    </div>
                                </div>
                            </div>
                            <div class="att-item clearfix">
                                <div class="att-image">
                                    <img loading="lazy" src="{{asset('frontend/images/bucket2.jpg')}}" alt="Images" />
                                </div>
                                <div class="att-content">
                                    <div class="att-content-left">
                                        <h4>Phulay Bay Resort</h4>
                                        <ul>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Wifi</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Parking</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Swimming Pool</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Daily Housekeeping
                                            </li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Restaurant Bar and
                                                Lounge</li>
                                        </ul>
                                    </div>
                                    <div class="att-content-right">
                                        <p>Starting from <span class="bold">Rs. 1500</span></p>
                                        <p>1 night / 3 person</p>
                                    </div>
                                </div>
                            </div>
                            <div class="att-item clearfix">
                                <div class="att-image">
                                    <img loading="lazy" src="{{asset('frontend/images/bucket3.jpg')}}" alt="Images" />
                                </div>
                                <div class="att-content">
                                    <div class="att-content-left">
                                        <h4>Phulay Bay Resort</h4>
                                        <ul>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Wifi</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Parking</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Swimming Pool</li>
                                        </ul>
                                    </div>
                                    <div class="att-content-right">
                                        <p>Starting from <span class="bold">Rs. 1500</span></p>
                                        <p>1 night / 3 person</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        {{-- <div class="comments detail-box">
                        <div class="detail-title">
                            <h3>Comments</h3>
                        </div>
                        <div class="comment-content">
                            <div class="comment-item">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4">
                                        <div class="comment-image">
                                            <img loading="lazy" src="{{asset('frontend/images/comment.jpg')}}" alt="Images" />
                                            <h4><a href="#">Peter Parker</a></h4>
                                            <span class="comment-date">(18 Dec 2018)</span>
                                            <a class="btn-blue btn-red" href="#">Reply</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-8">
                                        <div class="comment-desc">
                                            <span class="travel-date"> Travelled On : 25 March 2018</span>
                                            <div class="deal-rating">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                            </div>
                                            <p>
                                                Trek to Base Camp, taking plenty of time to acclimatize and to visit
                                                the Sherpa families and support facilities that will become
                                                increasingly important during our expedition. We will spend several
                                                days in Namche ahead of most trekkers, and will visit the
                                                monasteries in Tengboche and Pangboche.
                                            </p>
                                        </div>
                                        <div class="comment-item comment-reply">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="comment-image">
                                                        <img loading="lazy" src="{{asset('frontend/images/comment.jpg')}}" alt="Images" />
                                                        <h4><a href="#">Peter Parker</a></h4>
                                                        <span class="comment-date">(18 Dec 2018)</span>
                                                        <a class="btn-blue btn-red" href="#">Reply</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-8">
                                                    <div class="comment-desc">
                                                        <span class="travel-date"> Travelled On : 25 March
                                                            2018</span>
                                                        <div class="deal-rating">
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star-o"></span>
                                                            <span class="fa fa-star-o"></span>
                                                        </div>
                                                        <p>
                                                            Trek to Base Camp, taking plenty of time to acclimatize
                                                            and to visit the Sherpa families and support facilities
                                                            that will
                                                            become increasingly important during our expedition. We
                                                            will spend several days in Namche ahead of most
                                                            trekkers, and will
                                                            visit the monasteries in Tengboche and Pangboche.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment-item comment-reply">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="comment-image">
                                                        <img loading="lazy" src="{{asset('frontend/images/comment.jpg')}}" alt="Images" />
                                                        <h4><a href="#">Peter Parker</a></h4>
                                                        <span class="comment-date">(18 Dec 2018)</span>
                                                        <a class="btn-blue btn-red" href="#">Reply</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-8">
                                                    <div class="comment-desc">
                                                        <p>
                                                            Trek to Base Camp, taking plenty of time to acclimatize
                                                            and to visit the Sherpa families and support facilities
                                                            that will
                                                            become increasingly important during our expedition. We
                                                            will spend several days in Namche ahead of most
                                                            trekkers, and will
                                                            visit the monasteries in Tengboche and Pangboche.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    </div>
                </div>
                @if(!$hideSidebar)
                <div class="col-lg-4">
                    <aside class="detail-sidebar sidebar-wrapper">

                        @if (optional($product->category)->slug == 'hotels' || optional($product->category)->slug == 'homestay')
                        {{-- ============================================================
                             HOTEL / HOMESTAY — Premium Enquiry Card
                        ============================================================ --}}
                        @push('styles')
                        <style>
                            .hotel-card {
                                background: #ffffff;
                                border-radius: 16px;
                                box-shadow: 0 8px 40px rgba(0,0,0,.14);
                                overflow: hidden;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                            }
                            .hotel-card-header {
                                background: linear-gradient(135deg, #1d4ed8 0%, #0ea5e9 100%);
                                padding: 20px 22px 16px;
                                color: #fff;
                            }
                            .hotel-card-header .hc-title {
                                font-size: 1rem; font-weight: 700; margin: 0 0 2px;
                                white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                            }
                            .hotel-card-header .hc-sub {
                                font-size: .78rem; opacity: .85; margin: 0;
                            }
                            .hotel-card-body { padding: 20px 22px; }

                            .hc-field { margin-bottom: 14px; }
                            .hc-label {
                                font-size: .7rem; font-weight: 700; text-transform: uppercase;
                                letter-spacing: .5px; color: #64748b; margin-bottom: 5px;
                                display: block;
                            }
                            .hc-input {
                                width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px;
                                padding: 10px 14px; font-size: .88rem; color: #1e293b;
                                background: #f8fafc; transition: border-color .2s, box-shadow .2s;
                                outline: none; -webkit-appearance: none;
                            }
                            .hc-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); background: #fff; }
                            .hc-input[readonly] { background: #f1f5f9; color: #64748b; cursor: not-allowed; }
                            .hc-input.is-invalid { border-color: #ef4444; }
                            .invalid-feedback { font-size: .75rem; color: #ef4444; margin-top: 3px; }

                            .hc-row { display: flex; gap: 12px; }
                            .hc-row .hc-field { flex: 1; }

                            /* Nights pill */
                            .nights-display {
                                display: flex; align-items: center; justify-content: space-between;
                                background: #eff6ff; border-radius: 10px; padding: 10px 14px;
                                margin-bottom: 14px;
                            }
                            .nights-display .nd-label { font-size: .78rem; color: #1d4ed8; font-weight: 600; }
                            .nights-display .nd-value { font-size: 1.1rem; font-weight: 800; color: #1d4ed8; }

                            /* Submit */
                            .hc-submit {
                                width: 100%; padding: 13px;
                                background: linear-gradient(135deg,#1d4ed8,#0ea5e9);
                                color: #fff; border: none; border-radius: 12px;
                                font-size: .95rem; font-weight: 700; cursor: pointer;
                                transition: opacity .2s, transform .15s;
                                letter-spacing: .3px;
                            }
                            .hc-submit:hover { opacity: .92; transform: translateY(-1px); }

                            /* Support buttons */
                            .hc-support { display: flex; gap: 10px; margin-top: 12px; }
                            .hc-support a {
                                flex: 1; padding: 10px 6px; border-radius: 10px;
                                font-size: .8rem; font-weight: 700; text-align: center;
                                text-decoration: none; transition: opacity .2s;
                                display: flex; align-items: center; justify-content: center; gap: 5px;
                            }
                            .hc-support .btn-call { background: #f1f5f9; color: #334155; border: 1.5px solid #e2e8f0; }
                            .hc-support .btn-wa   { background: #dcfce7; color: #15803d; border: 1.5px solid #bbf7d0; }
                            .hc-support a:hover { opacity: .85; }

                            /* Success alert */
                            .hc-success {
                                background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 10px;
                                padding: 12px 16px; margin-bottom: 14px;
                                font-size: .85rem; color: #166534; font-weight: 500;
                            }

                            /* Error alert */
                            .hc-errors {
                                background: #fef2f2; border: 1.5px solid #fca5a5; border-radius: 10px;
                                padding: 12px 16px; margin-bottom: 14px;
                                font-size: .82rem; color: #991b1b;
                            }
                            .hc-errors ul { margin: 0; padding-left: 16px; }

                            /* Adjust for mobile */
                            @media (max-width: 991px) {
                                .hotel-card { position: relative; top: auto; margin-bottom: 24px; }
                            }
                        </style>
                        @endpush

                        <div class="hotel-card">
                            <div class="hotel-card-header">
                                <p class="hc-sub">&#127968; Hotel &amp; Homestay Enquiry</p>
                                <p class="hc-title">{{ $product->name }}</p>
                            </div>
                            <div class="hotel-card-body">

                                {{-- Success message --}}
                                @if(session('hotel_success'))
                                    <div class="hc-success">
                                        &#10003; {{ session('hotel_success') }}
                                    </div>
                                @endif

                                {{-- Validation errors --}}
                                @if($errors->any())
                                    <div class="hc-errors">
                                        <ul>
                                            @foreach($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('hotel-enquiry.store') }}" method="POST" id="hotelEnqForm" novalidate>
                                    @csrf

                                    {{-- Hotel Name (readonly, auto-filled) --}}
                                    <div class="hc-field">
                                        <label class="hc-label">Hotel / Homestay Name</label>
                                        <input type="text" class="hc-input" name="hotel_name"
                                               id="hotel_name" value="{{ $product->name }}" readonly>
                                    </div>

                                    {{-- Guest Name --}}
                                    <div class="hc-field">
                                        <label class="hc-label">Guest Name *</label>
                                        <input type="text" class="hc-input {{ $errors->has('guest_name') ? 'is-invalid' : '' }}"
                                               name="guest_name" id="guest_name"
                                               placeholder="Enter your full name"
                                               value="{{ old('guest_name') }}" required>
                                        @error('guest_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Contact Number --}}
                                    <div class="hc-field">
                                        <label class="hc-label">Contact Number *</label>
                                        <input type="tel" class="hc-input {{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                                               name="contact_number" id="contact_number"
                                               placeholder="10-digit mobile number"
                                               maxlength="10"
                                               value="{{ old('contact_number') }}" required>
                                        @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Adults & Kids --}}
                                    <div class="hc-row">
                                        <div class="hc-field">
                                            <label class="hc-label">Adults (18+) *</label>
                                            <input type="number" class="hc-input {{ $errors->has('adults') ? 'is-invalid' : '' }}"
                                                   name="adults" id="adults"
                                                   value="{{ old('adults', 1) }}" min="1" max="50" required>
                                            @error('adults')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="hc-field">
                                            <label class="hc-label">Kids (&lt;5 yrs)</label>
                                            <input type="number" class="hc-input {{ $errors->has('kids') ? 'is-invalid' : '' }}"
                                                   name="kids" id="kids"
                                                   value="{{ old('kids', 0) }}" min="0" max="50">
                                            @error('kids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    {{-- Check-In --}}
                                    <div class="hc-field">
                                        <label class="hc-label">Check-In Date &amp; Time *</label>
                                        <input type="datetime-local" class="hc-input {{ $errors->has('checkin_datetime') ? 'is-invalid' : '' }}"
                                               name="checkin_datetime" id="checkin_datetime"
                                               value="{{ old('checkin_datetime') }}"
                                               min="{{ date('Y-m-d') }}T00:00" required>
                                        @error('checkin_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Check-Out --}}
                                    <div class="hc-field">
                                        <label class="hc-label">Check-Out Date &amp; Time *</label>
                                        <input type="datetime-local" class="hc-input {{ $errors->has('checkout_datetime') ? 'is-invalid' : '' }}"
                                               name="checkout_datetime" id="checkout_datetime"
                                               value="{{ old('checkout_datetime') }}"
                                               min="{{ date('Y-m-d') }}T00:00" required>
                                        @error('checkout_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Nights (auto calculated, hidden) --}}
                                    <input type="hidden" name="nights" id="nights" value="{{ old('nights', 1) }}">
                                    <div class="nights-display">
                                        <span class="nd-label">&#127769; Total Nights</span>
                                        <span class="nd-value" id="nights_display">— nights</span>
                                    </div>

                                    <button type="submit" class="hc-submit">
                                        &#128222; Send Enquiry
                                    </button>
                                </form>

                                {{-- Support Buttons --}}
                                <div class="hc-support">
                                    <a href="tel:+917080109919" class="btn-call">
                                        &#128222; Call Us
                                    </a>
                                    <a href="https://wa.me/917080109919" target="_blank" rel="noopener" class="btn-wa">
                                        &#128172; WhatsApp
                                    </a>
                                </div>

                            </div>
                        </div>

                        @push('scripts')
                        <script>
                        (function () {
                            var today    = new Date();
                            var tomorrow = new Date(today);
                            tomorrow.setDate(today.getDate() + 1);

                            // Format as YYYY-MM-DDTHH:MM
                            function fmt(d) {
                                var mm = String(d.getMonth()+1).padStart(2,'0');
                                var dd = String(d.getDate()).padStart(2,'0');
                                return d.getFullYear()+'-'+mm+'-'+dd+'T12:00';
                            }

                            var ci = document.getElementById('checkin_datetime');
                            var co = document.getElementById('checkout_datetime');
                            var ni = document.getElementById('nights');
                            var nd = document.getElementById('nights_display');

                            // Set defaults only if not already filled (old values after validation error)
                            if (!ci.value) ci.value = fmt(today);
                            if (!co.value) co.value = fmt(tomorrow);

                            function calcNights() {
                                if (!ci.value || !co.value) return;
                                var diff = (new Date(co.value) - new Date(ci.value)) / 86400000;
                                var n = Math.max(1, Math.ceil(diff));
                                ni.value = n;
                                nd.textContent = n + ' Night' + (n !== 1 ? 's' : '');
                            }

                            // Keep checkout min always after checkin
                            ci.addEventListener('change', function () {
                                co.min = ci.value;
                                // Bump checkout if it's now <= checkin
                                if (co.value && co.value <= ci.value) {
                                    var d = new Date(ci.value);
                                    d.setDate(d.getDate() + 1);
                                    co.value = fmt(d);
                                }
                                calcNights();
                            });

                            co.addEventListener('change', calcNights);

                            // Run on load
                            calcNights();

                            // Only allow digits in contact field
                            document.getElementById('contact_number').addEventListener('input', function () {
                                this.value = this.value.replace(/\D/g,'').slice(0,10);
                            });
                        })();
                        </script>
                        @endpush

                        @elseif (optional($product->category)->slug == 'cab')
                        {{-- ============================================================
                             CAB — Premium Booking Enquiry Card
                        ============================================================ --}}
                        @push('styles')
                        <style>
                        /* ── Cab Booking Card — matches boat sidebar style ── */
                        .cab-card { background:#fff;border-radius:18px;box-shadow:0 6px 32px rgba(0,0,0,.13);overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
                        .cab-card-header { background:linear-gradient(135deg,#0C1F3F 0%,#0f3460 55%,#1a5276 100%);padding:20px 22px 16px;color:#fff; }
                        .cab-card-header-top { display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:12px; }
                        .cab-card-header h3 { font-size:1rem;font-weight:800;margin:0 0 3px;color:#fff; }
                        .cab-card-header p  { font-size:.77rem;color:rgba(255,255,255,.72);margin:0; }
                        .cab-price-wrap { text-align:right;flex-shrink:0; }
                        .cab-price-val  { font-size:1.4rem;font-weight:900;color:#fbbf24;display:block;line-height:1; }
                        .cab-price-original { font-size:.78rem;text-decoration:line-through;color:rgba(255,255,255,.5); }
                        .cab-price-sub  { font-size:.7rem;color:rgba(255,255,255,.6); }
                        .cab-trust-pills { display:flex;gap:5px;flex-wrap:wrap; }
                        .cab-trust-pills span { background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.88);font-size:.66rem;font-weight:600;padding:3px 9px;border-radius:20px;white-space:nowrap; }
                        .cab-card-body { padding:18px 20px; }

                        /* Fields */
                        .cc-field { margin-bottom:12px; }
                        .cc-label { font-size:.69rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;margin-bottom:5px;display:block; }
                        .cc-req   { color:#ef4444; }
                        .cc-input { width:100%;border:1.5px solid #e2e8f0;border-radius:9px;padding:10px 13px;font-size:.86rem;color:#111;background:#f9fafb;outline:none;transition:border-color .2s,box-shadow .2s,background .2s;-webkit-appearance:none;font-family:inherit; }
                        .cc-input:focus { border-color:#0f3460;box-shadow:0 0 0 3px rgba(15,52,96,.1);background:#fff; }
                        .cc-input.is-invalid { border-color:#ef4444; }

                        /* Phone prefix */
                        .cc-phone-wrap { position:relative; }
                        .cc-phone-prefix { position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.84rem;font-weight:600;color:#64748b;pointer-events:none;z-index:1; }
                        .cc-phone-input  { padding-left:38px!important; }

                        /* Row 2 */
                        .cc-row2 { display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px; }

                        /* Stepper */
                        .cc-stepper { display:flex;align-items:center;border:1.5px solid #e2e8f0;border-radius:9px;background:#f9fafb;height:42px;overflow:hidden;transition:border-color .2s; }
                        .cc-stepper:focus-within { border-color:#0f3460;box-shadow:0 0 0 3px rgba(15,52,96,.1); }
                        .cc-stepper-btn { flex-shrink:0;width:38px;height:100%;background:none;border:none;font-size:1.1rem;font-weight:700;color:#0f3460;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s;user-select:none; }
                        .cc-stepper-btn:hover:not(:disabled) { background:#EFF6FF; }
                        .cc-stepper-btn:disabled { color:#d1d5db;cursor:default; }
                        .cc-stepper-val { flex:1;text-align:center;font-size:.92rem;font-weight:800;color:#111;pointer-events:none; }

                        /* Roof carrier checkbox */
                        .cc-check-row { display:flex;align-items:flex-start;gap:10px;background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:9px;padding:10px 13px;margin-bottom:12px;cursor:pointer; }
                        .cc-check-row input { width:16px;height:16px;margin-top:2px;flex-shrink:0;accent-color:#0f3460;cursor:pointer; }
                        .cc-check-text { font-size:.81rem;color:#92400E;line-height:1.4; }
                        .cc-check-text strong { display:block;font-weight:700; }

                        /* Error box */
                        .cc-errors { background:#fef2f2;border:1.5px solid #fca5a5;border-radius:9px;padding:11px 14px;margin-bottom:13px;font-size:.79rem;color:#991b1b; }
                        .cc-errors strong { display:block;margin-bottom:5px; }
                        .cc-errors ul { margin:0;padding-left:16px; }

                        /* Submit */
                        .cc-submit { width:100%;padding:13px 16px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:11px;font-size:.92rem;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:9px;transition:opacity .18s,transform .15s;letter-spacing:.2px;margin-bottom:9px;box-shadow:0 4px 14px rgba(22,163,74,.3); }
                        .cc-submit:hover { opacity:.92;transform:translateY(-1px); }
                        .cc-callback { width:100%;padding:11px 16px;background:#F8FAFC;color:#334155;border:1.5px solid #E2E8F0;border-radius:11px;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:background .15s;text-decoration:none; }
                        .cc-callback:hover { background:#E2E8F0;text-decoration:none;color:#1e293b; }

                        /* Success state */
                        .cc-success-state { text-align:center;padding:28px 20px; }
                        .cc-success-icon { width:56px;height:56px;border-radius:50%;background:#DCFCE7;color:#16A34A;font-size:1.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 14px; }
                        .cc-success-state h4 { font-size:1rem;font-weight:800;color:#111;margin:0 0 7px; }
                        .cc-success-state p  { font-size:.83rem;color:#4B5563;margin:0 0 14px;line-height:1.6; }
                        .cc-success-call { display:inline-flex;align-items:center;gap:6px;background:#0f3460;color:#fff;font-size:.82rem;font-weight:700;padding:9px 20px;border-radius:8px;text-decoration:none; }
                        .cc-success-call:hover { opacity:.9;color:#fff;text-decoration:none; }

                        @media(max-width:991px){ .cab-card{ margin-bottom:24px; } }
                        </style>
                        @endpush

                        @php $cPhone = preg_replace('/\D/', '', websiteSetupValue('contact_number') ?? '7080109917'); @endphp

                        <div class="cab-card">
                            {{-- Header --}}
                            <div class="cab-card-header">
                                <div class="cab-card-header-top">
                                    <div>
                                        <h3>🚕 Cab Booking Enquiry</h3>
                                        <p>{{ Str::limit($product->name, 40) }}</p>
                                    </div>
                                    @php $cabPrice = ($product->discounted_price ?? 0) > 0 ? $product->discounted_price : ($product->base_price ?? 0); @endphp
                                    @if($cabPrice > 0)
                                    <div class="cab-price-wrap">
                                        @if(($product->base_price ?? 0) > ($product->discounted_price ?? 0) && ($product->discounted_price ?? 0) > 0)
                                        <span class="cab-price-original">₹{{ number_format($product->base_price) }}</span>
                                        @endif
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

                            {{-- Body --}}
                            <div class="cab-card-body">

                                @if(session('success'))
                                {{-- ── SUCCESS STATE — hide form ── --}}
                                <div class="cc-success-state">
                                    <div class="cc-success-icon">✓</div>
                                    <h4>Enquiry Submitted!</h4>
                                    <p>Our team will contact you on WhatsApp within 15 minutes to confirm your cab booking.</p>
                                    <a href="tel:+91{{ $cPhone }}" class="cc-success-call">📞 Call Us Now</a>
                                </div>

                                @else

                                @if($errors->any())
                                <div class="cc-errors">
                                    <strong>Please fix the following:</strong>
                                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                                </div>
                                @endif

                                <form action="{{ route('enquiry.store') }}" method="POST" id="cabEnqForm" novalidate>
                                    @csrf
                                    <input type="hidden" name="package_id"     value="{{ $product->id }}">
                                    <input type="hidden" name="package_name"   value="{{ $product->name }}">
                                    <input type="hidden" name="no_of_person"   id="cab_persons_val" value="1">
                                    <input type="hidden" name="booking_amount" value="{{ $cabPrice }}">
                                    <input type="hidden" name="message"        id="cab_message_hidden">

                                    {{-- Pickup Date & Time --}}
                                    <div class="cc-field">
                                        <label class="cc-label">Pickup Date &amp; Time <span class="cc-req">*</span></label>
                                        <input type="datetime-local" class="cc-input" name="arrival_date" id="cab_pickup_dt"
                                               min="{{ date('Y-m-d') }}T00:00"
                                               value="{{ old('arrival_date') }}" required>
                                    </div>

                                    {{-- Full Name --}}
                                    <div class="cc-field">
                                        <label class="cc-label">Full Name <span class="cc-req">*</span></label>
                                        <input type="text" class="cc-input" name="name"
                                               value="{{ old('name') }}" required autocomplete="name">
                                    </div>

                                    {{-- Mobile --}}
                                    <div class="cc-field">
                                        <label class="cc-label">Mobile <span class="cc-req">*</span></label>
                                        <div class="cc-phone-wrap">
                                            <span class="cc-phone-prefix">+91</span>
                                            <input type="tel" class="cc-input cc-phone-input" name="phone" id="cab_phone"
                                                   inputmode="numeric" maxlength="10"
                                                   value="{{ old('phone') }}" required autocomplete="tel">
                                        </div>
                                    </div>

                                    {{-- Persons + Luggage --}}
                                    @php
                                        // Extract seat count from product name
                                        $cabSeats = 7; // safe default
                                        $nameForSeats = strtolower($product->name ?? '');
                                        if (preg_match('/(\d+)\s*seater/i', $product->name ?? '', $sm)) {
                                            $cabSeats = (int)$sm[1];
                                        } elseif (str_contains($nameForSeats, 'sedan')  || str_contains($nameForSeats, 'swift')  || str_contains($nameForSeats, 'etios')) { $cabSeats = 4; }
                                        elseif (str_contains($nameForSeats, 'innova')  || str_contains($nameForSeats, 'crysta')  || str_contains($nameForSeats, 'ertiga')) { $cabSeats = 7; }
                                        elseif (str_contains($nameForSeats, 'fortuner') || str_contains($nameForSeats, 'scorpio') || str_contains($nameForSeats, 'xuv')) { $cabSeats = 7; }
                                        elseif (str_contains($nameForSeats, 'traveller') || str_contains($nameForSeats, 'tempo'))  { $cabSeats = 20; }
                                        elseif (str_contains($nameForSeats, 'bus') || str_contains($nameForSeats, 'coach'))         { $cabSeats = 40; }
                                    @endphp
                                    <div class="cc-row2">
                                        <div class="cc-field" style="margin-bottom:0;">
                                            <label class="cc-label">Persons <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">(max {{ $cabSeats }})</small></label>
                                            <div class="cc-stepper" data-max="{{ $cabSeats }}">
                                                <button type="button" class="cc-stepper-btn" id="cabPMinus" aria-label="Decrease">−</button>
                                                <span class="cc-stepper-val" id="cabPVal">1</span>
                                                <button type="button" class="cc-stepper-btn" id="cabPPlus"  aria-label="Increase">+</button>
                                            </div>
                                        </div>
                                        <div class="cc-field" style="margin-bottom:0;">
                                            <label class="cc-label">Luggage <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">(bags)</small></label>
                                            <div class="cc-stepper">
                                                <button type="button" class="cc-stepper-btn" id="cabLMinus" aria-label="Decrease">−</button>
                                                <span class="cc-stepper-val" id="cabLVal">0</span>
                                                <button type="button" class="cc-stepper-btn" id="cabLPlus"  aria-label="Increase">+</button>
                                            </div>
                                            <input type="hidden" name="luggage_bags" id="cab_luggage_val" value="0">
                                        </div>
                                    </div>

                                    {{-- Roof Carrier --}}
                                    <label class="cc-check-row">
                                        <input type="checkbox" name="roof_carrier" id="cab_roof_carrier" value="Yes">
                                        <span class="cc-check-text">
                                            <strong>📦 Need Roof Carrier?</strong>
                                            Extra luggage rack on top of cab
                                        </span>
                                    </label>

                                    {{-- Pickup Location --}}
                                    <div class="cc-field">
                                        <label class="cc-label">Pickup Location <span class="cc-req">*</span></label>
                                        <input type="text" class="cc-input" id="cab_pickup_loc"
                                               value="{{ old('cab_pickup_loc') }}" required>
                                    </div>

                                    {{-- Trip Type — auto-selected from package title --}}
                                    @php
                                        // Auto-detect trip type from product name (for message only)
                                        $pn = strtolower($product->name ?? '');
                                        $autoTrip = 'Full Day (8 Hr / 80 Km)';
                                        if      (str_contains($pn, 'half day') || str_contains($pn, 'half-day'))           $autoTrip = 'Half Day (4 Hr / 40 Km)';
                                        elseif  (str_contains($pn, 'airport pickup') || str_contains($pn, 'airport pick'))  $autoTrip = 'Airport Pickup';
                                        elseif  (str_contains($pn, 'airport drop'))                                         $autoTrip = 'Airport Drop';
                                        elseif  (str_contains($pn, 'airport'))                                               $autoTrip = 'Airport Pickup';
                                        elseif  (str_contains($pn, 'railway') && str_contains($pn, 'drop'))                  $autoTrip = 'Railway Station Drop';
                                        elseif  (str_contains($pn, 'railway') || str_contains($pn, 'station'))               $autoTrip = 'Railway Station Pickup';
                                        elseif  (str_contains($pn, 'round trip') || str_contains($pn, 'roundtrip'))          $autoTrip = 'Round Trip Outstation';
                                        elseif  (str_contains($pn, 'one way') || str_contains($pn, 'outstation'))            $autoTrip = 'One Way Outstation';
                                        elseif  (str_contains($pn, 'full day') || str_contains($pn, 'full-day'))             $autoTrip = 'Full Day (8 Hr / 80 Km)';
                                    @endphp
                                    {{-- Hidden: Trip Type auto-detected, not shown in form --}}
                                    <input type="hidden" id="cab_trip_type" value="{{ $autoTrip }}">

                                    {{-- Special Instructions --}}
                                    <div class="cc-field">
                                        <label class="cc-label">Special Instructions</label>
                                        <textarea class="cc-input" id="cab_message_visible" rows="2"
                                                  style="height:auto;resize:vertical;">{{ old('message') }}</textarea>
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

                        @push('scripts')
                        <script>

                        (function () {
                            function initStepper(minusId, plusId, valId, hiddenId, min, max) {
                                var minus  = document.getElementById(minusId);
                                var plus   = document.getElementById(plusId);
                                var valEl  = document.getElementById(valId);
                                var hidden = hiddenId ? document.getElementById(hiddenId) : null;
                                var n = min;
                                function render() {
                                    valEl.textContent = n;
                                    if (hidden) hidden.value = n;
                                    minus.disabled = n <= min;
                                    plus.disabled  = n >= max;
                                }
                                minus.addEventListener('click', function () { if (n > min) { n--; render(); } });
                                plus.addEventListener('click',  function () { if (n < max) { n++; render(); } });
                                render();
                            }
                            if (document.getElementById('cabPMinus')) {
                                // Read max seats from data-max attribute set by PHP
                                var personsStepper = document.querySelector('#cabPMinus')?.closest('.cc-stepper');
                                var maxSeats = personsStepper ? parseInt(personsStepper.dataset.max || '7') : 7;
                                initStepper('cabPMinus','cabPPlus','cabPVal','cab_persons_val', 1, maxSeats);
                                initStepper('cabLMinus','cabLPlus','cabLVal','cab_luggage_val', 0, 8);
                            }

                            /* Phone digits only */
                            var ph = document.getElementById('cab_phone');
                            if (ph) ph.addEventListener('input', function(){ this.value = this.value.replace(/\D/g,'').slice(0,10); });

                            /* Default pickup datetime = now + 2h */
                            var dt = document.getElementById('cab_pickup_dt');
                            if (dt && !dt.value) {
                                var now = new Date(); now.setHours(now.getHours()+2,0,0,0);
                                var p = function(v){ return String(v).padStart(2,'0'); };
                                dt.value = now.getFullYear()+'-'+p(now.getMonth()+1)+'-'+p(now.getDate())+'T'+p(now.getHours())+':00';
                            }

                            /* Bundle extras into hidden message before submit */
                            var form = document.getElementById('cabEnqForm');
                            if (form) form.addEventListener('submit', function(){
                                var luggage   = document.getElementById('cab_luggage_val') ? document.getElementById('cab_luggage_val').value : '0';
                                var roof      = document.getElementById('cab_roof_carrier') ? document.getElementById('cab_roof_carrier').checked : false;
                                var loc       = document.getElementById('cab_pickup_loc')   ? document.getElementById('cab_pickup_loc').value.trim() : '';
                                var tripType  = document.getElementById('cab_trip_type')    ? document.getElementById('cab_trip_type').value : '';
                                var note      = document.getElementById('cab_message_visible') ? document.getElementById('cab_message_visible').value.trim() : '';
                                var persons   = document.getElementById('cab_persons_val')  ? document.getElementById('cab_persons_val').value : '1';
                                var msg = 'Trip Type: '+tripType+'\nPickup Location: '+loc+'\nPersons: '+persons+'\nLuggage Bags: '+luggage+'\nRoof Carrier: '+(roof?'Yes':'No');
                                if (note) msg += '\nNotes: '+note;
                                document.getElementById('cab_message_hidden').value = msg.trim();
                            });
                        })();
                        </script>
                        @endpush

                        @elseif($isFestival)
                        {{-- ============================================================
                             FESTIVAL — Other Festivals Sidebar (no enquiry form)
                        ============================================================ --}}
                        <div class="fest-sidebar">
                            <div class="fest-sidebar__head">
                                <h3>Other Varanasi Festivals</h3>
                                <p>Discover more sacred celebrations</p>
                            </div>
                            <div class="fest-sidebar__list">
                                @forelse($relatedFestivals as $rf)
                                @php
                                    $rfSubSlug = optional($rf->subCategory)->slug;
                                    $rfUrl = $rfSubSlug
                                        ? route('product.detail', [optional($rf->category)->slug ?? 'festivals', $rfSubSlug, $rf->slug])
                                        : route('product.detail', [optional($rf->category)->slug ?? 'festivals', 'varanasi', $rf->slug]);
                                    $rfImg = (!empty($rf->images) && is_array($rf->images))
                                        ? asset('backend/admin/product_images/' . $rf->images[0])
                                        : asset('backend/assets/images/placeholder.jpg');
                                @endphp
                                <a href="{{ $rfUrl }}" class="fest-card">
                                    <img src="{{ $rfImg }}" alt="{{ $rf->name }}" class="fest-card__img" loading="lazy" />
                                    <div class="fest-card__body">
                                        <div class="fest-card__name">{{ $rf->name }}</div>
                                        <div class="fest-card__sub">Varanasi Festival</div>
                                    </div>
                                </a>
                                @empty
                                <p style="padding:16px 6px;font-size:.85rem;color:#888;text-align:center;">More festivals coming soon.</p>
                                @endforelse
                            </div>
                            <span class="fest-sidebar__divider">Plan your visit</span>
                            <div class="fest-sidebar__cta">
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', websiteSetupValue('whats_app_number') ?? '917080109919') }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="fest-sidebar__wa">
                                    <img src="{{ asset('frontend/images/whatsapp.png') }}" alt="" width="20" height="20" loading="lazy" />
                                    Chat on WhatsApp
                                </a>
                            </div>
                        </div>
                        @elseif(optional($product->category)->slug === 'things-to-do')
                        {{-- No enquiry form for things-to-do category --}}
                        @else
                        {{-- ============================================================
                             ALL OTHER CATEGORIES — existing form (unchanged)
                        ============================================================ --}}
                        <div class="sidebar-item sidebar-item-dark">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif
                            <div class="detail-title">
                                <h3>Book {{ $product->name }}</h3>
                            </div>
                            <form action="{{ route('enquiry.store') }}?package_id={{ $product->id }}&package_name={{ $product->name }}"
                                  method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group mb-3 col-lg-12">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Name" required>
                                    </div>
                                    <div class="form-group mb-3 col-lg-6 col-md-6">
                                        <input type="number" class="form-control" id="phone" name="phone"
                                            placeholder="Contact Number"
                                            onkeypress="if(this.value.length==10) return false;" required>
                                    </div>
                                    <div class="form-group mb-3 col-lg-6 col-md-6">
                                        <input type="datetime-local" class="form-control" id="arrival_date"
                                            name="arrival_date" min="{{ date('Y-m-d h:i') }}" required>
                                    </div>
                                    <div class="form-group mb-3 col-lg-6 col-md-6">
                                        <input type="number" class="form-control" id="no_of_person" name="no_of_person"
                                            placeholder="No. of Persons" min="1">
                                    </div>
                                    <div class="textarea mb-3 col-lg-12">
                                        <textarea placeholder="Message" name="message"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="comment-btn mt-0">
                                            <button class="btn-blue btn-red">Request Call back</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                        {{-- <div class="sidebar-item">
                        <div class="detail-title">
                            <h3>Popular Packages</h3>
                        </div>
                        <div class="sidebar-content sidebar-slider">
                            <div class="sidebar-package">
                                <div class="sidebar-package-image">
                                    <img loading="lazy" src="{{asset('frontend/images/detailslider1.jpg')}}" alt="Images" />
                                </div>
                                <div class="destination-content sidebar-package-content">
                                    <h4><a href="#">Royal Caribbean Cruises</a></h4>
                                    <div class="deal-rating">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star-o"></span>
                                        <span class="fa fa-star-o"></span>
                                    </div>
                                    <p><i class="flaticon-time"></i> 5 days starts from <span
                                            class="bold">$659</span></p>
                                    <a href="#" class="btn-blue btn-red">Book Now</a>
                                </div>
                            </div>
                            <div class="sidebar-package">
                                <div class="sidebar-package-image">
                                    <img loading="lazy" src="{{asset('frontend/images/detailslider2.jpg')}}" alt="Images" />
                                </div>
                                <div class="destination-content sidebar-package-content">
                                    <h4><a href="#">Bahamas Royal Cruises</a></h4>
                                    <div class="deal-rating">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star-o"></span>
                                        <span class="fa fa-star-o"></span>
                                    </div>
                                    <p><i class="flaticon-time"></i> 5 days starts from <span
                                            class="bold">$659</span></p>
                                    <a href="#" class="btn-blue btn-red">Book Now</a>
                                </div>
                            </div>
                            <div class="sidebar-package">
                                <div class="sidebar-package-image">
                                    <img loading="lazy" src="{{asset('frontend/images/detailslider3.jpg')}}" alt="Images" />
                                </div>
                                <div class="destination-content sidebar-package-content">
                                    <h4><a href="#">Royal Caribbean Cruises</a></h4>
                                    <div class="deal-rating">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star-o"></span>
                                        <span class="fa fa-star-o"></span>
                                    </div>
                                    <p><i class="flaticon-time"></i> 5 days starts from <span
                                            class="bold">$659</span></p>
                                    <a href="#" class="btn-blue btn-red">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        @if(!in_array(optional($product->category)->slug, ['hotels','homestay','cab']) && !$isFestival)
                        <div class="sidebar-item sidebar-helpline">
                            <div class="sidebar-helpline-content">
                                <h3>Any Questions?</h3>
                                <p><i class="flaticon-phone-call"></i> <a
                                        href="tel:{{ websiteSetupValue('whats_app_number') }}">{{ websiteSetupValue('whats_app_number') }}</a>
                                </p>
                                <p><i class="flaticon-mail"></i> <a
                                        href="mailto:{{ websiteSetupValue('email') }}">{{ websiteSetupValue('email') }}</a>
                                </p>
                            </div>
                        </div>
                        @endif
                    </aside>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
