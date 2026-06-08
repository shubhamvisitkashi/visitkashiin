<!DOCTYPE html>
<html lang="en-IN">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#D94F2B" />
        <meta name="format-detection" content="telephone=no" />

        @yield('meta')

        {{-- Global fallback OG / Twitter tags (pages override in @section('meta')) --}}
        <meta property="og:type" content="website" />
        <meta property="og:locale" content="en_IN" />
        <meta property="og:site_name" content="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="@visit_kashi" />
        <meta name="geo.region" content="IN-UP" />
        <meta name="geo.placename" content="Varanasi, Uttar Pradesh, India" />

        {{-- Global Organization schema (on every page) --}}
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "TravelAgency",
          "name": "{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}",
          "url": "{{ env('APP_URL') }}",
          "logo": "{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}",
          "description": "Visit Kashi is Varanasi's #1 travel platform for boat rides, hotel bookings, tour packages, and cab services.",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Varanasi",
            "addressRegion": "Uttar Pradesh",
            "addressCountry": "IN"
          },
          "telephone": "+91-{{ websiteSetupValue('contact_number') }}",
          "email": "{{ optional(App\Models\WebsiteSetup::where('name','email')->first())->value }}",
          "sameAs": [
            "https://www.facebook.com/visitkashiofficial/",
            "https://www.instagram.com/visitkashi/",
            "https://twitter.com/visit_kashi",
            "https://www.youtube.com/@visitkashi"
          ],
          "areaServed": {
            "@type": "City",
            "name": "Varanasi"
          },
          "priceRange": "₹₹"
        }
        </script>

        {{-- Favicon --}}
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('backend/admin/website_setup/'.websiteSetupValue('favicon'))}}" />

        {{-- DNS prefetch / preconnect for external origins --}}
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="dns-prefetch" href="https://www.googletagmanager.com" />
        <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com" />
        <link rel="dns-prefetch" href="https://api.open-meteo.com" />
        <link rel="dns-prefetch" href="https://wa.me" />

        {{-- Preload jQuery so it's downloaded early, before it's encountered at end of body --}}
        <link rel="preload" href="{{asset('frontend/js/jquery-3.2.1.min.js')}}" as="script" />

        {{-- Plus Jakarta Sans — loaded on every page for shared sections --}}
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"></noscript>

        {{-- ══ CRITICAL CSS (inlined) ══════════════════════════════════════
             Covers: tokens, reset, Bootstrap container, navigation.
             Allows ALL external CSS to load asynchronously without FOUC
             on the above-fold navigation and hero area.
        ════════════════════════════════════════════════════════════════ --}}
        <style>
        :root{--vk-primary:#D94F2B;--vk-secondary:#1A2B4C;--vk-accent:#F5A623;--vk-white:#fff;--vk-transition:0.28s cubic-bezier(.4,0,.2,1)}
        *,*::before,*::after{box-sizing:border-box}
        html{font-size:16px;line-height:1.5;-webkit-text-size-adjust:100%;overflow-x:hidden;width:100%}
        body{margin:0;padding:0;background:#fff;color:#333;font-size:15px;font-family:'Plus Jakarta Sans',Poppins,sans-serif;line-height:1.5;font-weight:300;overflow-x:hidden;width:100%;position:relative}
        img{max-width:100%;height:auto}
        a{text-decoration:none!important}
        ul,ol{list-style:none;margin:0;padding:0}
        /* Bootstrap container */
        .container{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}
        @media(min-width:576px){.container{max-width:540px}}
        @media(min-width:768px){.container{max-width:720px}}
        @media(min-width:992px){.container{max-width:960px}}
        @media(min-width:1200px){.container{max-width:1140px}}
        .row{display:flex;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}
        .pull-left{float:left!important}
        .clearfix::after{display:block;clear:both;content:''}
        /* Navigation — pure flexbox, !important overrides style.css floats */
        .navigation{position:sticky;top:0;z-index:1000;background:rgba(26,43,76,0.98)!important;backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);box-shadow:0 2px 20px rgba(0,0,0,.18);padding:0!important;border-bottom:none!important}
        .navigation .navbar,.navigation .navbar.navbar-default{display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:center!important;justify-content:space-between!important;min-height:66px;padding:0!important;margin:0!important;border:none!important;background:transparent!important;float:none!important;width:100%!important;position:static!important}
        .navigation .logo,.navigation .logo.pull-left{float:none!important;width:auto!important;flex-shrink:0;padding:12px 32px 12px 0;display:flex!important;align-items:center}
        .navigation .logo a{display:flex;align-items:center}
        .navigation .logo img{height:42px;max-height:42px;width:auto;display:block}
        .navigation .navbar-nav-wrapper{float:none!important;width:auto!important;flex:1;display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:center!important}
        .navigation .nav.navbar-nav{display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:center!important;float:none!important;margin:0!important;padding:0!important;gap:2px;list-style:none}
        .navigation .nav.navbar-nav>li{float:none!important;display:flex!important;align-items:center;position:relative;list-style:none}
        .navigation .nav.navbar-nav>li>a{display:flex!important;flex-direction:row!important;align-items:center;gap:5px;padding:8px 15px!important;color:rgba(255,255,255,.88)!important;font-weight:500;font-size:14px;text-decoration:none;border-radius:8px;white-space:nowrap;line-height:1}
        .vk-hamburger{display:none!important}
        @media(max-width:991px){.navigation .navbar-nav-wrapper{display:none!important}.vk-hamburger{display:flex!important;flex-direction:column;justify-content:center;align-items:center;gap:5px;width:42px;height:42px;background:rgba(255,255,255,.10);border:1.5px solid rgba(255,255,255,.22);border-radius:10px;cursor:pointer;padding:0;flex-shrink:0;margin-left:auto}.vk-hamburger__bar{display:block;width:20px;height:2px;background:#fff;border-radius:2px}.navigation .navbar,.navigation .navbar.navbar-default{min-height:58px!important}}
        /* Hero above-fold (homepage) — premium split layout */
        .vk-hero{position:relative;min-height:100vh;display:flex;flex-direction:column;overflow:hidden;background:#0d1420}
        .vk-hero__bg{position:absolute;inset:0;z-index:0}
        .vk-hero__bg img{width:100%;height:100%;object-fit:cover;object-position:center 30%;display:block}
        .vk-hero__overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(8,15,32,.88) 0%,rgba(8,15,32,.62) 50%,rgba(8,15,32,.82) 100%)}
        .vk-hero__inner{position:relative;z-index:2;flex:1;display:flex;align-items:center;padding:120px 0 72px}
        .vk-hero__layout{display:grid;grid-template-columns:1.15fr 1fr;gap:56px;align-items:center}
        .vk-hero__title{font-size:clamp(30px,3.8vw,58px);font-weight:800;line-height:1.08;letter-spacing:-.025em;color:#fff;margin:0 0 14px}
        .vk-hero__tagline{font-size:clamp(14px,1.3vw,17px);color:rgba(255,255,255,.68);margin:0 0 28px;line-height:1.6}
        .vk-booking-card{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.17);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border-radius:20px;overflow:hidden}
        @media(max-width:991px){.vk-hero__layout{grid-template-columns:1fr;gap:36px}.vk-hero__inner{padding:80px 0 50px}.vk-hero__left{text-align:center}.vk-hero__ctas{justify-content:center}.vk-hero__trust{justify-content:center}.vk-hero__right{align-items:center}}
        /* ── Slider show/hide — MUST be inline to prevent double LCP image loads & CLS ── */
        .vkp-desktop-only{display:block}.vkp-mobile-only{display:none}
        @media(max-width:767px){.vkp-desktop-only{display:none!important}.vkp-mobile-only{display:block!important}}
        /* ── Mobile slider CLS: reserve height before images load ── */
        .vkm-hero{min-height:420px;background:#0d1420}
        .vkm-slide{min-height:380px}
        .vkm-img-wrap{min-height:280px;background:#0d1420}
        /* ── Desktop slider CLS: contain layout to prevent reflow ── */
        .vkp-hero{contain:layout;height:85vh;max-height:680px;min-height:320px}
        /* ── content-visibility for below-fold sections (paint performance) ── */
        .vk-why,.vkf-explore,.vk-db-promo{content-visibility:auto;contain-intrinsic-size:0 300px}
        </style>

        {{-- LCP image preload (pushed from individual pages via @push('preloads')) --}}
        @stack('preloads')

        {{-- All CSS loaded asynchronously — no render-blocking — minified files served --}}
        <link rel="preload" href="{{asset('frontend/css/bootstrap.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
        <noscript><link href="{{asset('frontend/css/bootstrap.min.css')}}" rel="stylesheet" /></noscript>

        <link rel="preload" href="{{asset('frontend/css/style.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
        <noscript><link href="{{asset('frontend/css/style.min.css')}}" rel="stylesheet" /></noscript>

        <link rel="preload" href="{{asset('frontend/css/global-layout.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
        <noscript><link href="{{asset('frontend/css/global-layout.min.css')}}" rel="stylesheet" /></noscript>

        <link rel="preload" href="{{asset('frontend/css/mobile-app.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
        <noscript><link href="{{asset('frontend/css/mobile-app.min.css')}}" rel="stylesheet" /></noscript>

        <link rel="preload" href="{{asset('frontend/font/flaticon.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
        <noscript><link href="{{asset('frontend/font/flaticon.css')}}" rel="stylesheet" /></noscript>

        <link rel="preload" href="{{asset('frontend/css/plugin.min.css')}}" as="style" fetchpriority="low" onload="this.onload=null;this.rel='stylesheet'" />
        <noscript><link href="{{asset('frontend/css/plugin.min.css')}}" rel="stylesheet" /></noscript>

        <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" as="style" fetchpriority="low" onload="this.onload=null;this.rel='stylesheet'" crossorigin />
        <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" /></noscript>

        @stack('styles')

        {{-- Google Analytics — injected after load, zero TBT impact --}}
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        window.addEventListener('load', function(){
            var s = document.createElement('script');
            s.src = 'https://www.googletagmanager.com/gtag/js?id=G-QQ2QQ2EB99';
            s.async = true;
            document.head.appendChild(s);
            gtag('js', new Date());
            gtag('config', 'G-QQ2QQ2EB99', {'send_page_view': true});
        });
        </script>
    </head>

    <body class="@yield('body-class')">
        @include('frontend.layouts.header')

        @yield('content')

        @include('frontend._before_footer')

        @include('frontend.layouts.footer')



      <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header text-center">
            <h5 class="modal-title" id="staticBackdropLabel">🔔 Notice 🔔</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div class="wht text-center">
                    {!! safe_html(websiteSetupValue('notice')) !!}
                </div>
            </div>
        </div>
        </div>
    </div>

        {{-- jQuery + Bootstrap: deferred — small files, needed early for interactivity --}}
        <script src="{{asset('frontend/js/jquery-3.2.1.min.js')}}" defer></script>
        <script src="{{asset('frontend/js/bootstrap.min.js')}}" defer></script>

        {{-- plugin.js (445KB) + main.js loaded after window.load so they don't
             contribute to Total Blocking Time (TBT). Slick/slicknav initialize
             as an enhancement after the page is fully interactive. --}}
        <script>
        (function(){
            var jqSrc   = '{{asset("frontend/js/jquery-3.2.1.min.js")}}';
            var plugSrc = '{{asset("frontend/js/plugin.js")}}';
            var mainSrc = '{{asset("frontend/js/main.min.js")}}';
            function loadScript(src, cb){
                var s = document.createElement('script');
                s.src = src;
                if (cb) s.onload = cb;
                document.body.appendChild(s);
            }
            function initPlugins(){
                if (typeof jQuery !== 'undefined') {
                    loadScript(plugSrc, function(){ loadScript(mainSrc); });
                } else {
                    loadScript(jqSrc, function(){
                        loadScript(plugSrc, function(){ loadScript(mainSrc); });
                    });
                }
            }
            if (document.readyState === 'complete') {
                initPlugins();
            } else {
                window.addEventListener('load', initPlugins);
            }
        })();
        </script>

        {{-- Notice modal (depends on jQuery + Bootstrap) --}}
        @if(websiteSetupValue('notice'))
        <script>
        window.addEventListener('load', function() {
            if (typeof $ !== 'undefined') { $("#staticBackdrop").modal('show'); }
        });
        </script>
        @endif

        @stack('scripts')

        {{-- ── MOBILE APP BOTTOM NAV ── --}}
        @php $currentUrl = url()->current(); @endphp
        <nav class="vk-mob-nav" aria-label="Mobile navigation">
            <a href="{{ url('/') }}" class="vk-mob-nav-item {{ request()->is('/') || request()->is('') ? 'active' : '' }}">
                <div class="vk-mob-nav-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <span class="vk-mob-nav-label">Home</span>
            </a>
            <a href="{{ route('product.list','boat') }}" class="vk-mob-nav-item {{ request()->is('boat*') ? 'active' : '' }}">
                <div class="vk-mob-nav-icon">
                    <svg viewBox="0 0 24 24"><path d="M2 20h20M5 20V10l7-5 7 5v10"/></svg>
                </div>
                <span class="vk-mob-nav-label">Boat</span>
            </a>
            <a href="{{ route('product.list','cab') }}" class="vk-mob-nav-item {{ request()->is('cab*') ? 'active' : '' }}">
                <div class="vk-mob-nav-icon">
                    <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <span class="vk-mob-nav-label">Cab</span>
            </a>
            <a href="{{ route('product.list','hotels') }}" class="vk-mob-nav-item {{ request()->is('hotels*') ? 'active' : '' }}">
                <div class="vk-mob-nav-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                </div>
                <span class="vk-mob-nav-label">Stay</span>
            </a>
            <a href="{{ route('product.list','packages') }}" class="vk-mob-nav-item {{ request()->is('packages*') ? 'active' : '' }}">
                <div class="vk-mob-nav-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <span class="vk-mob-nav-label">Tours</span>
            </a>
        </nav>
    </body>

</html>
