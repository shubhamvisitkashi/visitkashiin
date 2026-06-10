@extends('frontend.layouts.app')

{{-- ── SEO: All existing meta tags, canonical, OG tags preserved exactly ── --}}
@section('meta')
    <link rel="canonical" href="{{ url()->full() }}">
    <title>Varanasi #1 Travel Website | Book Stay, Boat, {{ env('APP_DOMAIN') }}</title>
    <meta name="description" content="Visit Kashi is a Varanasi local travel company operating an online marketplace and operates inbound tours of Varanasi, Prayagraj, Ayodhya, and Bodhgaya. We directly connect to a service provider for Hotel, Boat Booking, Cab Booking, and Local Guide.">
    <meta name="keywords" content="Varanasi tour packages booking, Book Varanasi tour packages online at best price, best travel company in varanasi, Day tour packages for varanasi, Things must to do in varanasi">
    <meta property="og:title" content="Varanasi #1 Travel Website | Book Stay, Boat, Cab-visitkashi.com">
    <meta property="og:site_name" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="Visit Kashi is a Varanasi local travel company operating an online marketplace and operates inbound tours of Varanasi, Prayagraj, Ayodhya, and Bodhgaya. We directly connect to a service provider for Hotel, Boat Booking, Cab Booking, and Local Guide.">
    <meta property="og:keywords" content="Varanasi tour packages booking, Book Varanasi tour packages online at best price, best travel company in varanasi, Day tour packages for varanasi, Things must to do in varanasi">
@endsection


{{-- ── Body class: activates homepage-specific CSS scoping ── --}}
@section('body-class', 'vk-homepage')

{{-- ── Homepage stylesheet ── --}}
@push('styles')
    {{-- homepage.css loaded async — critical above-fold styles are inlined in app.blade.php --}}
    <link rel="preload" href="{{ asset('frontend/css/homepage.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <noscript><link href="{{ asset('frontend/css/homepage.min.css') }}" rel="stylesheet" /></noscript>
@endpush

@section('content')

{{-- ════════════════════════════════════════════
     HERO — Premium Luxury Spiritual Tourism
     Dynamic: banner, banner_title, banner_description
     ════════════════════════════════════════════ --}}
@php
// Dynamic Hero Slider — pulls from DB (via controller), falls back to web setup banner
if ($hero_slides->isNotEmpty()) {
    $sliderSlides = $hero_slides->map(fn($s) => [
        'img'            => $s->image_url,
        'mobile_img'     => $s->mobile_image_url ?? $s->image_url,
        'has_mobile_img' => (bool) $s->mobile_image,
        'badge'      => $s->badge  ?: "Varanasi's #1 Spiritual Platform",
        'title'      => $s->title,
        'tagline'    => $s->tagline ?: 'Book trusted cabs, luxury boats, Ganga Aarti, hotels & spiritual tours.',
        'cta1'       => ['label' => $s->cta_label ?: 'Explore Tours', 'url' => $s->cta_url ?: route('product.list','packages')],
    ])->toArray();
} else {
    // Fallback: use banner images from web setup
    $bannerImg = asset('backend/admin/website_setup/' . websiteSetupValue('banner'));
    $sliderSlides = [[
        'img'            => $bannerImg,
        'mobile_img'     => $bannerImg,
        'has_mobile_img' => true,
        'badge'      => "Varanasi's #1 Spiritual Travel Platform",
        'title'      => websiteSetupValue('banner_description') ?: 'Most Trusted Travel Company',
        'tagline'    => websiteSetupValue('banner_title') ?: 'Book trusted cabs, luxury boats, Ganga Aarti, hotels & spiritual tours.',
        'cta1'       => ['label' => 'Explore Tours', 'url' => route('product.list','packages')],
    ]];
    foreach (['promo_banner','promo_banner_2','promo_banner_3'] as $idx => $key) {
        $img = websiteSetupValue($key);
        if ($img) {
            $imgUrl = asset('backend/admin/website_setup/'.$img);
            $extras = [
                ['badge'=>'Sacred Ganga Experience','title'=>'Ganga Aarti & Sunrise Boat Rides','tagline'=>'Witness the divine Ganga Aarti from a private boat on the sacred river.','cta1'=>['label'=>'Book Boat Ride','url'=>route('product.list','boat')]],
                ['badge'=>'Spiritual Varanasi Tours','title'=>'Explore Kashi with Local Experts','tagline'=>'Guided temple tours, cab services and luxury stays — all in one place.','cta1'=>['label'=>'View Packages','url'=>route('product.list','packages')]],
                ['badge'=>'Heritage Hotel Stays','title'=>'Stay Near the Holy Ganga Ghats','tagline'=>'Handpicked heritage hotels and homestays near the sacred Ganga Ghats.','cta1'=>['label'=>'Browse Hotels','url'=>route('product.list','hotels')]],
            ];
            $sliderSlides[] = array_merge(['img' => $imgUrl, 'mobile_img' => $imgUrl, 'has_mobile_img' => true], $extras[$idx] ?? $extras[0]);
        }
    }
}

// Mobile slider shows ONLY slides with a dedicated portrait Mobile Image.
// Falls back to the full slide list if none have one (so mobile is never empty).
$mobileSliderSlides = array_values(array_filter($sliderSlides, fn($s) => !empty($s['has_mobile_img'])));
if (empty($mobileSliderSlides)) {
    $mobileSliderSlides = $sliderSlides;
}
@endphp

{{-- Preload LCP hero images — device-specific so desktop/mobile don't load each other's images --}}
@push('preloads')
@if(!empty($sliderSlides[0]['img']))
<link rel="preload" as="image" href="{{ $sliderSlides[0]['img'] }}" fetchpriority="high" media="(min-width: 768px)">
@endif
@php $mobileHero = $mobileSliderSlides[0]['mobile_img'] ?? $mobileSliderSlides[0]['img'] ?? ''; @endphp
@if($mobileHero)
<link rel="preload" as="image" href="{{ $mobileHero }}" fetchpriority="high" media="(max-width: 767px)">
@endif
@endpush

{{-- ══ DESKTOP Slider (hidden on mobile) ══ --}}
<section id="home_banner_video" class="vkp-hero vkp-desktop-only">
    <div class="vkp-wrap">
        <div class="vkp-track" id="vkpSlides">
        @foreach($sliderSlides as $i => $slide)
        <div class="vkp-slide {{ $i===0 ? 'is-active' : '' }}" data-slide="{{ $i }}"
             aria-hidden="{{ $i!==0 ? 'true' : 'false' }}">

            {{-- Full-bleed background image --}}
            @if(!empty($slide['cta1']['url']))
            <a href="{{ $slide['cta1']['url'] }}" class="vkp-img-link">
                <img src="{{ $slide['img'] }}" alt="{{ $slide['title'] ?? 'Visit Kashi' }}"
                     class="vkp-img" width="1440" height="680"
                     loading="{{ $i===0 ? 'eager' : 'lazy' }}"
                     {{ $i===0 ? 'fetchpriority="high"' : '' }}
                     decoding="{{ $i===0 ? 'sync' : 'async' }}"
                     onerror="this.closest('.vkp-slide').style.background='linear-gradient(135deg,#0d1420,#0f3460)'">
            </a>
            @else
            <img src="{{ $slide['img'] }}" alt="{{ $slide['title'] ?? 'Visit Kashi' }}"
                 class="vkp-img" width="1440" height="680"
                 loading="{{ $i===0 ? 'eager' : 'lazy' }}"
                 {{ $i===0 ? 'fetchpriority="high"' : '' }}
                 decoding="{{ $i===0 ? 'sync' : 'async' }}"
                 onerror="this.closest('.vkp-slide').style.background='linear-gradient(135deg,#0d1420,#0f3460)'">
            @endif

            {{-- Text overlay removed (title, tagline, CTA hidden) --}}

        </div>
        @endforeach
        </div>

        @if(count($sliderSlides) > 1)
        {{-- Arrows --}}
        <button class="vkp-arrow vkp-prev" id="vkpPrev" aria-label="Previous">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="vkp-arrow vkp-next" id="vkpNext" aria-label="Next">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
        {{-- Dots --}}
        <div class="vkp-dots">
            @foreach($sliderSlides as $i => $s)
            <button class="vkp-dot {{ $i===0 ? 'active' : '' }}"
                    onclick="vkhsGoTo({{ $i }})" aria-label="Slide {{ $i+1 }}"></button>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ══ MOBILE Slider (shown only on mobile ≤767px) ══ --}}
<section class="vkm-hero vkp-mobile-only">
    <div class="vkm-track" id="vkmSlides">
        @foreach($mobileSliderSlides as $i => $slide)
        <div class="vkm-slide {{ $i===0 ? 'is-active' : '' }}" data-vkm="{{ $i }}">
            <a href="{{ $slide['cta1']['url'] ?? '#' }}" class="vkm-img-wrap">
                <img src="{{ $slide['mobile_img'] ?? $slide['img'] }}"
                     alt="{{ $slide['title'] ?? 'Visit Kashi' }}"
                     class="vkm-img"
                     loading="{{ $i===0 ? 'eager' : 'lazy' }}"
                     decoding="{{ $i===0 ? 'sync' : 'async' }}"
                     onerror="this.closest('.vkm-slide').style.background='linear-gradient(160deg,#0d1420,#0f3460)'">
            </a>
            <div class="vkm-content">
                @if(!empty($slide['badge']))
                <span class="vkm-badge">{{ $slide['badge'] }}</span>
                @endif
                <h2 class="vkm-title">{{ $slide['title'] ?? '' }}</h2>
                @if(!empty($slide['tagline']))
                <p class="vkm-tagline">{{ $slide['tagline'] }}</p>
                @endif
                <a href="{{ $slide['cta1']['url'] ?? '#' }}" class="vkm-cta">
                    {{ $slide['cta1']['label'] ?? 'Explore' }}
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if(count($mobileSliderSlides) > 1)
    <div class="vkm-dots">
        @foreach($mobileSliderSlides as $i => $s)
        <button class="vkm-dot {{ $i===0 ? 'active' : '' }}"
                onclick="vkmGoTo({{ $i }})" aria-label="Slide {{ $i+1 }}"></button>
        @endforeach
    </div>
    @endif
</section>

<style>
/* ══ MOBILE Hero Slider ════════════════════════════════════ */
.vkm-hero {
    width: 100%;
    margin: 0;
    padding: 0;
    background: #0d1420;
    overflow: hidden;
    display: block;
}
.vkm-track {
    width: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
}
.vkm-slide {
    display: none;
    width: 100%;
    animation: vkmFadeIn 0.5s ease forwards;
}
.vkm-slide.is-active { display: block; }
@keyframes vkmFadeIn { from{opacity:0;} to{opacity:1;} }

/* ── Full banner image — no crop, full width, natural height ── */
.vkm-img-wrap {
    display: block;
    width: 100%;
    margin: 0;
    padding: 0;
    line-height: 0;    /* removes inline whitespace gap below img */
}
.vkm-img {
    display: block;
    width: 100%;       /* spans full screen width */
    height: auto;      /* natural height — no crop */
    object-fit: unset; /* disable object-fit so full image shows */
    margin: 0;
    padding: 0;
}

/* Content below image */
.vkm-content {
    display: none;
}
.vkm-badge {
    display: inline-block;
    background: #FFF7ED;
    color: #C2410C;
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .07em;
    text-transform: uppercase;
    padding: 3px 9px;
    border-radius: 20px;
    width: fit-content;
    border: 1px solid #FED7AA;
}
.vkm-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.25;
    letter-spacing: -.02em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.vkm-tagline {
    font-size: .76rem;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.vkm-cta {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: linear-gradient(135deg, #D94F2B, #FF6B35);
    color: #fff !important;
    font-size: .78rem;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 50px;
    text-decoration: none !important;
    width: fit-content;
    margin-top: 4px;
    box-shadow: 0 3px 12px rgba(217,79,43,.35);
    transition: transform .2s, box-shadow .2s;
}
.vkm-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(217,79,43,.45); }

/* Dots */
.vkm-dots {
    display: flex;
    justify-content: center;
    gap: 6px;
    padding: 10px 0 14px;
    background: #fff;
}
.vkm-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #cbd5e1; border: none; cursor: pointer; padding: 0;
    transition: background .25s, width .25s, border-radius .25s;
}
.vkm-dot.active { background: #D94F2B; width: 22px; border-radius: 4px; }

/* ── Visit Dubai–style Hero ─────────────────────────────── */
.vkp-hero {
    padding: 12px 20px 0;
    background: #f7f7f7;
    box-sizing: border-box;
    width: 100%;
    overflow: hidden; /* prevent right-side bleed */
}
.vkp-wrap {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    background: #0d1420;
    height: 85vh;
    max-height: 680px;
    min-height: 320px;
    width: 100%;
    box-sizing: border-box;
}
.vkp-track {
    position: relative;
    width: 100%; height: 100%;
}

/* Slides */
.vkp-slide {
    display: none; opacity: 0;
    position: absolute; inset: 0;
}
.vkp-slide.is-active {
    display: block; z-index: 1;
    animation: vkpFadeIn 0.7s ease forwards;
}
.vkp-slide.is-leaving {
    display: block; z-index: 0; pointer-events: none;
    animation: vkpFadeOut 0.7s ease forwards;
}
@keyframes vkpFadeIn  { from{opacity:0;} to{opacity:1;} }
@keyframes vkpFadeOut { from{opacity:1;} to{opacity:0;} }

/* Full-bleed image */
.vkp-img-link { display:block; width:100%; height:100%; }
.vkp-img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: center;
    display: block;
}

/* Gradient overlay */
.vkp-text-overlay {
    position: absolute; inset: 0; z-index: 5;
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 0 7% 52px;
    background: linear-gradient(
        to top,
        rgba(0,0,0,0.75) 0%,
        rgba(0,0,0,0.30) 45%,
        transparent 100%
    );
    pointer-events: none;
}
.vkp-text-overlay a, .vkp-text-overlay .vkp-cta-btn { pointer-events: auto; }

/* Badge */
.vkp-badge {
    display: inline-block;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.30);
    color: rgba(255,255,255,.92);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .72rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; padding: 5px 14px;
    border-radius: 50px; margin-bottom: 14px;
    width: fit-content;
}

/* Title — Visit Dubai style: large, white, bold */
.vkp-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(1.8rem, 4.5vw, 3.4rem);
    font-weight: 800; color: #fff; margin: 0 0 12px;
    line-height: 1.1; letter-spacing: -.03em;
    text-shadow: 0 2px 16px rgba(0,0,0,.35);
    max-width: 620px;
}

/* Tagline */
.vkp-tagline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(.88rem, 1.6vw, 1.05rem);
    color: rgba(255,255,255,.80);
    margin: 0 0 24px; line-height: 1.6; max-width: 480px;
}

/* CTA button */
.vkp-cta-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #D94F2B, #FF6B35); color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .9rem; font-weight: 700; padding: 13px 26px;
    border-radius: 50px; text-decoration: none !important;
    box-shadow: 0 4px 20px rgba(217,79,43,.40);
    transition: transform .2s, box-shadow .2s;
    width: fit-content;
}
.vkp-cta-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(217,79,43,.55); color: #fff; }

/* Arrows — inside the rounded card */
.vkp-arrow {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 10;
    width: 40px; height: 40px; border-radius: 50%;
    background: rgba(255,255,255,.18);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    border: 1.5px solid rgba(255,255,255,.35);
    color: #fff; display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .2s;
}
.vkp-arrow:hover { background: rgba(255,255,255,.32); }
.vkp-prev { left: 16px; }
.vkp-next { right: 16px; }

/* Dots — bottom center */
.vkp-dots {
    position: absolute; bottom: 18px; left: 50%; transform: translateX(-50%);
    display: flex; gap: 6px; align-items: center; z-index: 10;
}
.vkp-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: rgba(255,255,255,.40); border: none; cursor: pointer; padding: 0;
    transition: background .25s, width .25s, border-radius .25s;
}
.vkp-dot.active { background: #fff; width: 22px; border-radius: 4px; }

/* Responsive */
@media (max-width: 991px) {
    .vkp-hero { padding: 8px 12px 0; }
    .vkp-wrap { height: 72vw; max-height: 520px; border-radius: 16px; }
    .vkp-text-overlay { padding: 0 6% 36px; }
}
@media (max-width: 600px) {
    .vkp-hero { padding: 6px 8px 0; }
    .vkp-wrap { height: 80vw; max-height: 440px; border-radius: 14px; }
    .vkp-text-overlay { padding: 0 5% 28px; }
    .vkp-title { font-size: clamp(1.3rem, 6vw, 1.9rem); margin-bottom: 8px; }
    .vkp-tagline { font-size: .82rem; margin-bottom: 16px; }
    .vkp-cta-btn { font-size: .82rem; padding: 10px 20px; }
    .vkp-arrow { width: 34px; height: 34px; }
    .vkp-prev { left: 10px; }
    .vkp-next { right: 10px; }
}
@media (max-width: 400px) {
    .vkp-wrap { height: 88vw; border-radius: 12px; }
}
</style>
<script>
// Defer slider JS to idle time — reduces TBT without affecting visible behaviour
(function(){
  var total={{ count($sliderSlides) }};
  if(total<=1)return;
  function initDesktopSlider(){
    var cur=0,timer=null,int=5500,dur=900;
    function goTo(n){
      var sl=document.querySelectorAll('.vkp-slide'),dt=document.querySelectorAll('.vkp-dot');
      var out=sl[cur];
      if(dt[cur]) dt[cur].classList.remove('active');
      out.classList.remove('is-active');out.classList.add('is-leaving');out.setAttribute('aria-hidden','true');
      var lv=out;setTimeout(function(){lv.classList.remove('is-leaving');},dur);
      cur=(n+total)%total;
      sl[cur].classList.add('is-active');sl[cur].setAttribute('aria-hidden','false');
      if(dt[cur]) dt[cur].classList.add('active');
    }
    window.vkhsGoTo=function(n){clearInterval(timer);goTo(n);timer=setInterval(function(){goTo(cur+1);},int);};
    var p=document.getElementById('vkpPrev'),nx=document.getElementById('vkpNext');
    if(p) p.addEventListener('click',function(){window.vkhsGoTo(cur-1);});
    if(nx) nx.addEventListener('click',function(){window.vkhsGoTo(cur+1);});
    var el=document.querySelector('.vkp-hero'),sx=0;
    if(el){
      el.addEventListener('touchstart',function(e){sx=e.touches[0].clientX;},{passive:true});
      el.addEventListener('touchend',function(e){var d=e.changedTouches[0].clientX-sx;if(Math.abs(d)>50)window.vkhsGoTo(d<0?cur+1:cur-1);});
    }
    timer=setInterval(function(){goTo(cur+1);},int);
  }
  // requestIdleCallback defers JS until browser is idle after first paint
  if('requestIdleCallback' in window){requestIdleCallback(initDesktopSlider,{timeout:2000});}
  else{setTimeout(initDesktopSlider,200);}
})();
</script>

{{-- ══ MOBILE SLIDER JS ══ --}}
@if(count($mobileSliderSlides) > 1)
<script>
(function(){
  var total={{ count($mobileSliderSlides) }};
  if(total<=1)return;
  function initMobileSlider(){
    var cur=0,timer=null,int=5000;
    function vkmGoTo(n){
      n=((n%total)+total)%total;
      var sl=document.querySelectorAll('.vkm-slide'),dt=document.querySelectorAll('.vkm-dot');
      sl[cur].classList.remove('is-active');if(dt[cur])dt[cur].classList.remove('active');
      cur=n;sl[cur].classList.add('is-active');if(dt[cur])dt[cur].classList.add('active');
    }
    window.vkmGoTo=vkmGoTo;
    timer=setInterval(function(){vkmGoTo(cur+1);},int);
    var mob=document.querySelector('.vkm-hero'),sx=0;
    if(mob){
      mob.addEventListener('touchstart',function(e){sx=e.touches[0].clientX;},{passive:true});
      mob.addEventListener('touchend',function(e){
        var d=e.changedTouches[0].clientX-sx;
        if(Math.abs(d)>45){clearInterval(timer);vkmGoTo(d<0?cur+1:cur-1);timer=setInterval(function(){vkmGoTo(cur+1);},int);}
      });
    }
  }
  if('requestIdleCallback' in window){requestIdleCallback(initMobileSlider,{timeout:2000});}
  else{setTimeout(initMobileSlider,200);}
})();
</script>
@endif

{{-- ── Mobile App: Quick-action chips (below hero, mobile only) ── --}}
<div class="vk-mob-chips" role="navigation" aria-label="Quick categories">
    <a href="{{ route('product.list','boat') }}" class="vk-mob-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20M5 20V10l7-5 7 5v10"/></svg>
        ⛵ Boat Rides
    </a>
    <a href="{{ route('product.list','cab') }}" class="vk-mob-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        🚕 Cabs
    </a>
    <a href="{{ route('product.list','hotels') }}" class="vk-mob-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        🏨 Hotels
    </a>
    <a href="{{ route('product.list','packages') }}" class="vk-mob-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
        🗺️ Packages
    </a>
    <a href="https://wa.me/91{{ preg_replace('/\D/','',websiteSetupValue('contact_number')?:'7080109917') }}?text=Hi+VisitKashi%2C+I+need+help+with+my+booking" target="_blank" class="vk-mob-chip" style="background:#dcfce7;color:#15803d;">
        <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075a8.167 8.167 0 0 1-2.385-1.475 8.166 8.166 0 0 1-1.653-2.059c-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.05 0A11.95 11.95 0 0 0 .057 11.893c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654A11.882 11.882 0 0 0 12.05 24c6.554 0 11.893-5.335 11.893-11.893A11.821 11.821 0 0 0 12.05 0z"/></svg>
        WhatsApp
    </a>
</div>


{{-- ════════════════════════════════════════════
     3. OUR SERVICES
     ════════════════════════════════════════════ --}}
@php
$svcData = [
  [
    'slug'     => 'hotels',
    'label'    => 'Stay',
    'title'    => 'Hotels & Homestays',
    'desc'     => 'Heritage hotels & cozy homestays near the Ganga Ghats',
    'icon'     => '🏨',
    'gradient' => 'linear-gradient(135deg,#0C4A6E,#0369A1)',
    'accent'   => '#38BDF8',
    'fallback' => 'frontend/images/destination-fw1.jpg',
  ],
  [
    'slug'     => 'cab',
    'label'    => 'Cab',
    'title'    => 'Cab Booking',
    'desc'     => 'Comfortable cabs for local rides & outstation trips',
    'icon'     => '🚕',
    'gradient' => 'linear-gradient(135deg,#92400E,#D97706)',
    'accent'   => '#FCD34D',
    'fallback' => 'frontend/images/deal1.jpg',
  ],
  [
    'slug'     => 'boat',
    'label'    => 'Boat',
    'title'    => 'Boat Rides',
    'desc'     => 'Sacred Ganga Aarti & sunrise boat rides on the holy river',
    'icon'     => '⛵',
    'gradient' => 'linear-gradient(135deg,#064E3B,#059669)',
    'accent'   => '#34D399',
    'fallback' => 'frontend/images/boat.png',
  ],
  [
    'slug'     => 'packages',
    'label'    => 'Tour Packages',
    'title'    => 'Tour Packages',
    'desc'     => 'Curated spiritual tours of Varanasi, Ayodhya & beyond',
    'icon'     => '🗺️',
    'gradient' => 'linear-gradient(135deg,#4C1D95,#7C3AED)',
    'accent'   => '#A78BFA',
    'fallback' => 'frontend/images/destination-fw2.jpg',
  ],
];
@endphp

<section class="vksvc-section" aria-label="Our Services" id="vk-services">
  <div class="container">

    {{-- Section Header --}}
    <div class="vksvc-header">
      <div class="vksvc-header-left">
        <div class="vksvc-badge">✦ What We Offer</div>
        <h2 class="vksvc-title">Our Services</h2>
      </div>
    </div>

    {{-- Services Grid --}}
    <div class="vksvc-grid">
      @foreach($svcData as $i => $s)
      @php
        $imgUrl = isset($service_images[$s['slug']]) && $service_images[$s['slug']]
          ? asset('backend/admin/product_images/' . $service_images[$s['slug']])
          : asset($s['fallback']);
      @endphp
      <a href="{{ route('product.list', $s['slug']) }}" class="vksvc-card vksvc-card-{{ $i + 1 }}" aria-label="{{ $s['title'] }}">
        {{-- Background image --}}
        <div class="vksvc-img" style="background-image:url('{{ $imgUrl }}');"></div>
        {{-- Bottom-only gradient overlay --}}
        <div class="vksvc-overlay"></div>
        {{-- Category label top-left --}}
        <span class="vksvc-cat-tag" style="color:{{ $s['accent'] }};">{{ $s['label'] }}</span>
        {{-- Content --}}
        <div class="vksvc-content">
          <h3 class="vksvc-name">{{ $s['title'] }}</h3>
        </div>
        {{-- Accent bar --}}
        <div class="vksvc-accent-bar" style="background:{{ $s['accent'] }};"></div>
      </a>
      @endforeach
    </div>

  </div>
</section>

<style>
/* ══ Our Services — Redesigned ════════════════════════════════════ */
.vksvc-section {
  padding: 11px 0 11px;
  background: #fff;
}

/* Header */
.vksvc-header { display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;flex-wrap:wrap;gap:14px; }
.vksvc-badge  { display:inline-block;background:#FFF1E6;color:#C2410C;font-size:11px;font-weight:800;letter-spacing:1.2px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:10px;border:1px solid #FED7AA; }
.vksvc-title  { font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(24px,3vw,34px);font-weight:900;color:#111;margin:0 0 6px;letter-spacing:-0.03em; }
.vksvc-subtitle { font-size:14.5px;color:#888;margin:0;line-height:1.6; }

/* Grid — 4 equal columns */
.vksvc-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
}

/* Card */
.vksvc-card {
  position: relative;
  display: block;
  border-radius: 16px;
  overflow: hidden;
  text-decoration: none !important;
  aspect-ratio: 3 / 2;
  transition: transform .28s ease, box-shadow .28s ease;
  background: #e8edf2;
}
.vksvc-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 44px rgba(0,0,0,.16);
  text-decoration: none !important;
}

/* Background image */
.vksvc-img {
  position: absolute; inset: 0;
  background-size: cover;
  background-position: center;
  transition: transform .55s ease;
}
.vksvc-card:hover .vksvc-img { transform: scale(1.07); }

/* Overlay — ONLY bottom gradient, subtle, so image shows clearly */
.vksvc-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(
    to top,
    rgba(0,0,0,0.72) 0%,
    rgba(0,0,0,0.22) 45%,
    rgba(0,0,0,0.04) 100%
  ) !important;
  transition: opacity .3s;
}
.vksvc-card:hover .vksvc-overlay { opacity: .88; }

/* Category label — top left */
.vksvc-cat-tag {
  position: absolute; top: 14px; left: 14px; z-index: 3;
  background: rgba(255,255,255,.90);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  border-radius: 20px;
  padding: 4px 12px;
  font-size: 11px; font-weight: 800;
  letter-spacing: .3px;
  transition: background .2s;
}
.vksvc-card:hover .vksvc-cat-tag { background: rgba(255,255,255,1); }

/* Content — bottom */
.vksvc-content {
  position: absolute; bottom: 0; left: 0; right: 0;
  padding: 0 18px 20px;
  z-index: 2;
}
.vksvc-name {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.05rem; font-weight: 800;
  color: #fff; margin: 0 0 4px;
  letter-spacing: -0.02em;
  line-height: 1.2;
}

/* Accent bar — bottom */
.vksvc-accent-bar {
  position: absolute; bottom: 0; left: 0; right: 0;
  height: 3px;
  transform: scaleX(0);
  transform-origin: left;
  transition: transform .32s ease;
}
.vksvc-card:hover .vksvc-accent-bar { transform: scaleX(1); }

/* Responsive */
@media(max-width:1024px) { .vksvc-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } }
@media(max-width:767px) {
  .vksvc-section { display: block !important; padding: 10px 0 14px; }
  .vksvc-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
  .vksvc-card { aspect-ratio: 3 / 2; border-radius: 12px; }
  .vksvc-header { margin-bottom: 14px; }
  .vksvc-title { font-size: 18px; }
  .vksvc-name { font-size: .82rem; }
}
</style>

{{-- ════════════════════════════════════════════
     4. ON-HOME CATEGORIES (dynamic loop)
     Preserved exactly:
     • $on_home_categories — controller variable
     • $on_home_category->meta_title
     • $on_home_category->show_price
     • $on_home_category->slug
     • $on_home_category->product — eager loaded
     • route('product.list', slug)
     • route('product.detail', [cat, sub, slug])
     • .sale-slider.slider-button — Slick JS hooks
     • .col-lg-12 inside slider — Slick slide items
     ════════════════════════════════════════════ --}}
<div id="vk-categories">
@if (count($on_home_categories) != 0)
    @foreach ($on_home_categories as $on_home_category)

    <section class="vk-section vk-section--alt deals-on-sale">
        <div class="container">

            <div class="vk-section__header vk-reveal">
                <div>
                    <h2 class="vk-section__title">{{ $on_home_category->meta_title }}</h2>
                </div>
                <a class="vk-btn vk-btn--outline"
                   href="{{ route('product.list', $on_home_category->slug) }}"
                   aria-label="View all {{ $on_home_category->meta_title }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

            {{-- .sale-slider and .slider-button are kept for Slick init --}}
            <div class="row sale-slider slider-button">
                @foreach ($on_home_category->product as $on_home_category_product)
                    <div class="col-lg-12">
                        <a @if ($on_home_category_product->subCategory)
                               href="{{ route('product.detail', [$on_home_category_product->category->slug, $on_home_category_product->subCategory->slug, $on_home_category_product->slug]) }}"
                           @else
                               href="{{ route('product.detail', [$on_home_category_product->category->slug, 'varanasi', $on_home_category_product->slug]) }}"
                           @endif
                           class="vk-card">

                            <div class="vk-card__img-wrap">
                                <img src="{{ asset(!empty($on_home_category_product->images) ? 'backend/admin/product_images/' . array_values($on_home_category_product->images)[0] : 'backend/assets/images/placeholder.jpg') }}"
                                     alt="{{ $on_home_category_product->name }}"
                                     class="vk-card__img"
                                     loading="lazy"
                                     decoding="async"
                                     width="400" height="300" />
                            </div>

                            <div class="vk-card__body">
                                <h3 class="vk-card__title">{{ $on_home_category_product->name }}</h3>
                                @if($on_home_category->show_price == '1' && !str_contains(strtolower($on_home_category->slug ?? ''), 'festival'))
                                    <p class="vk-card__price">
                                        from <strong><span class="vk-rupee">₹</span>{{ number_format($on_home_category_product->discounted_price) }}</strong>/-
                                        @if(in_array($on_home_category_product->category->slug, ['hotels','homestay']))
                                            <small>per night</small>
                                        @endif
                                    </p>
                                @endif
                            </div>

                        </a>
                    </div>
                @endforeach
            </div>{{-- /.sale-slider --}}

        </div>
    </section>


    @endforeach
@endif
</div>{{-- /#vk-categories --}}


{{-- Promo Banner: after_categories --}}

{{-- ════════════════════════════════════════════
     4. POPULAR PACKAGES (dynamic loop)
     Preserved exactly:
     • $on_home_products — controller variable
     • $on_home_product->name
     • $on_home_product->discounted_price
     • $on_home_product->images
     • $on_home_product->category, subCategory
     • route('product.list', 'packages')
     • route('product.detail', [cat, sub, slug])
     • .package-slider.slider-button — Slick JS hooks
     • .col-lg-4 inside slider — Slick slide items
     ════════════════════════════════════════════ --}}
@if (count($on_home_products) != 0)
<section class="vk-section popular-packages" id="vk-packages">
    <div class="container">

        <div class="vk-section__header vk-reveal">
            <div>
                <h2 class="vk-section__title">Popular Packages</h2>
            </div>
            <a class="vk-btn vk-btn--outline"
               href="{{ route('product.list', 'packages') }}"
               aria-label="View all packages">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- .package-slider and .slider-button are kept for Slick init --}}
        <div class="row package-slider slider-button">
            @foreach ($on_home_products as $on_home_product)
                <div class="col-lg-4">

                    <a @if ($on_home_product->subCategory)
                           href="{{ route('product.detail', [$on_home_product->category->slug, $on_home_product->subCategory->slug, $on_home_product->slug]) }}"
                       @else
                           href="{{ route('product.detail', [$on_home_product->category->slug, 'varanasi', $on_home_product->slug]) }}"
                       @endif
                       class="vk-pkg-card">

                        <div class="vk-pkg-card__img-wrap package-image">
                            <img src="{{ asset(!empty($on_home_product->images) ? 'backend/admin/product_images/' . array_values($on_home_product->images)[0] : 'backend/assets/images/placeholder.jpg') }}"
                                 alt="{{ $on_home_product->name }}"
                                 class="vk-pkg-card__img"
                                 loading="lazy"
                                 decoding="async"
                                 width="600" height="400" />
                            <div class="vk-pkg-card__overlay" aria-hidden="true"></div>
                        </div>

                        <div class="vk-pkg-card__body package-content">
                            <h3 class="vk-pkg-card__title">{{ $on_home_product->name }}</h3>
                            <p class="vk-pkg-card__price">
                                from <strong><span class="vk-rupee">₹</span>{{ number_format($on_home_product->discounted_price) }}</strong>
                                <span class="vk-pkg-card__per">/ person</span>
                            </p>
                        </div>

                    </a>

                </div>
            @endforeach
        </div>{{-- /.package-slider --}}

    </div>
</section>
@endif


{{-- Promo Banner: after_packages --}}

{{-- Why, CTA Strip, and Weather are now rendered globally via _before_footer.blade.php --}}

{{-- ════════════════════════════════════════════
     Search JS — preserved exactly
     Functions: openSearchDiv(), seacrh()
     Uses: #flip, .TopSearchesBox, route('product.search')
     ════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    /* ── Transparent nav → solid on scroll ── */
    (function () {
        var nav = document.querySelector('.navigation');
        if (!nav) return;
        function syncNav() {
            if (window.scrollY > 80) {
                nav.classList.add('vk-nav--scrolled');
            } else {
                nav.classList.remove('vk-nav--scrolled');
            }
        }
        window.addEventListener('scroll', syncNav, { passive: true });
        syncNav();
    })();

    /* ── Booking card tab switching ── */
    var tabs = document.querySelectorAll('.vk-booking-card__tab');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            /* Deactivate all tabs + hide all forms */
            tabs.forEach(function (t) { t.classList.remove('is-active'); });
            document.querySelectorAll('.vk-booking-form').forEach(function (f) {
                f.classList.add('is-hidden');
            });
            /* Activate clicked tab + show matching form */
            this.classList.add('is-active');
            var target = document.getElementById(this.getAttribute('data-tab'));
            if (target) { target.classList.remove('is-hidden'); }
        });
    });

    /* Smooth scroll for the scroll-line indicator */
    var scrollLink = document.querySelector('.vk-hero__scroll a');
    if (scrollLink) {
        scrollLink.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    /* Stat counter — counts up once section enters viewport */
    function animateCount(el, target, suffix) {
        var start = 0;
        var duration = 1400;
        var step = (target / duration) * 16;
        function tick() {
            start += step;
            if (start >= target) { el.textContent = target.toLocaleString() + suffix; return; }
            el.textContent = Math.floor(start).toLocaleString() + suffix;
            requestAnimationFrame(tick);
        }
        tick();
    }

    var stats = [
        { selector: '.vk-hero__stats-row .vk-hero__stat:nth-child(1) strong', val: 50000, suffix: '+' },
        { selector: '.vk-hero__stats-row .vk-hero__stat:nth-child(3) strong', val: 200,   suffix: '+' },
    ];

    var statsEl = document.querySelector('.vk-hero__stats');
    if (statsEl && 'IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) {
                stats.forEach(function (s) {
                    var el = document.querySelector(s.selector);
                    if (el) animateCount(el, s.val, s.suffix);
                });
                io.disconnect();
            }
        }, { threshold: 0.5 });
        io.observe(statsEl);
    }
})();
</script>
@endpush

@endsection
