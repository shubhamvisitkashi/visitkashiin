{{--
  Footer — Visit Kashi  (Dark navy theme)
--}}

{{-- ── SEO product link cloud ── --}}
@php
    // Shared on every page via app.blade.php — cache it and eager-load
    // product.category/product.subCategory so the link-building below
    // doesn't fire a per-product N+1 query.
    $footer_categories = cache()->remember('footer_explore_categories', 1800, function () {
        return App\Models\Admin\Category::where('is_active','active')
            ->with(['subCategory', 'product.category', 'product.subCategory'])
            ->get();
    });
@endphp
<section class="vkf-explore" aria-label="Explore Varanasi experiences">
    <div class="container">
        <h2 class="vkf-explore__title">Explore Varanasi</h2>
        <div class="vkf-explore__grid">
            @foreach ($footer_categories as $footer_category)
                <div class="vkf-explore__group">
                    <strong class="vkf-explore__cat">{{ $footer_category->name }}</strong>
                    <div class="vkf-explore__links">
                        @foreach ($footer_category->product as $footer_product)
                            <a @if ($footer_product->subCategory)
                                   href="{{ route('product.detail', [$footer_product->category->slug, $footer_product->subCategory->slug, $footer_product->slug]) }}"
                               @else
                                   href="{{ route('product.detail', [$footer_product->category->slug, 'varanasi', $footer_product->slug]) }}"
                               @endif>{{ $footer_product->name }}</a>@if(!$loop->last)<span class="vkf-sep">·</span>@endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<footer class="vkf-footer" itemscope itemtype="https://schema.org/TravelAgency">

    <div class="vkf-main">
        <div class="container">
            <div class="row">

                {{-- Col 1: Brand --}}
                <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                    <div class="vkf-brand">
                        <a href="{{ route('index') }}" aria-label="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}">
                            <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                                 alt="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}"
                                 class="vkf-logo" itemprop="image" />
                        </a>
                        <p class="vkf-tagline" itemprop="description">
                            Your trusted guide to the spiritual city of Varanasi — Boat rides, Tour packages, Hotels &amp; more.
                        </p>
                        <div class="vkf-trust">
                            <span><i class="fa fa-star"></i> 4.9 Rating</span>
                            <span><i class="fa fa-users"></i> 10,000+ Guests</span>
                            <span><i class="fa fa-shield"></i> 100% Safe</span>
                        </div>
                        <nav class="vkf-social" aria-label="Social media">
                            <a href="https://www.facebook.com/visitkashiofficial/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                            <a href="https://www.instagram.com/visitkashi/?hl=en" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
                            <a href="https://twitter.com/visit_kashi?lang=en" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.youtube.com/@visitkashi" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fa fa-youtube-play"></i></a>
                        </nav>
                    </div>
                </div>

                {{-- Col 2: Quick Links --}}
                <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                    <div class="vkf-col">
                        <h3 class="vkf-col__title">Quick Links</h3>
                        <nav aria-label="Quick links">
                            <ul class="vkf-links">
                                <li><a href="{{ route('product.list', 'packages') }}">Tour Packages</a></li>
                                <li><a href="{{ route('product.list', 'boat') }}">Boat Rides</a></li>
                                <li><a href="{{ route('product.list', 'hotels') }}">Hotels</a></li>
                                <li><a href="{{ route('product.list', 'cab') }}">Cab Booking</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>

                {{-- Col 3: Policies --}}
                <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                    <div class="vkf-col">
                        <h3 class="vkf-col__title">Company</h3>
                        <nav aria-label="Policies">
                            <ul class="vkf-links">
                                <li><a href="{{ route('page.about') }}">About Us</a></li>
                                <li><a href="{{ route('contact.show') }}">Contact Us</a></li>
                                <li><a href="{{ route('page.privacy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('page.cancellation') }}">Cancellation &amp; Refund</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>

                {{-- Col 4: Contact --}}
                <div class="col-lg-4 col-md-4 col-md-12">
                    <div class="vkf-col">
                        <h3 class="vkf-col__title">Get In Touch</h3>
                        <address class="vkf-address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                            <div class="vkf-address__row" itemprop="streetAddress">
                                <i class="flaticon-maps-and-flags vkf-icon"></i>
                                <span>{{ websiteSetupValue('address') }}</span>
                            </div>
                            <div class="vkf-address__row">
                                <i class="flaticon-phone-call vkf-icon"></i>
                                <span itemprop="telephone">+91-{{ websiteSetupValue('contact_number') }}, 7080109918, 7080109919</span>
                            </div>
                            <div class="vkf-address__row">
                                <i class="flaticon-mail vkf-icon"></i>
                                <a href="mailto:{{ websiteSetupValue('email') }}" itemprop="email">
                                    {{ websiteSetupValue('email') }}
                                </a>
                            </div>
                            <div class="vkf-address__row">
                                <i class="fa fa-map-marker vkf-icon"></i>
                                <span itemprop="addressLocality">Varanasi, <span itemprop="addressRegion">Uttar Pradesh</span>, <span itemprop="addressCountry">India</span></span>
                            </div>
                        </address>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="vkf-bottom">
        <div class="container">
            <div class="vkf-bottom__inner">
                <p itemprop="name">© 2018 – {{ date('Y') }} Visit Kashi. All Rights Reserved.</p>
                <div class="vkf-payments">
                    <span><i class="fa fa-lock"></i> Secure Payments</span>
                    <span><i class="fa fa-cc-visa fa-lg"></i></span>
                    <span><i class="fa fa-cc-mastercard fa-lg"></i></span>
                    <span><i class="fa fa-google-wallet fa-lg" title="UPI / GPay"></i></span>
                </div>
            </div>
        </div>
    </div>
</footer>


<style>
/* ══ FOOTER — Clean Light Theme ══════════════════════════════════ */

/* ── SEO Explore strip ── */
.vkf-explore {
    background: #F8F9FA;
    border-top: 1px solid #E9ECEF;
    padding: 36px 0 28px;
}
.vkf-explore__title {
    font-size: 11px; font-weight: 800; letter-spacing: .1em;
    text-transform: uppercase; color: #8900a0;
    margin-bottom: 18px;
}
.vkf-explore__grid  { display: flex; flex-direction: column; gap: 12px; }
.vkf-explore__group { display: flex; flex-wrap: wrap; align-items: baseline; gap: 6px; }
.vkf-explore__cat   { font-size: 11.5px; font-weight: 700; color: #374151; white-space: nowrap; }
.vkf-explore__links { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
.vkf-explore__links a { font-size: 12px; color: #6B7280; text-decoration: none; transition: color .2s; }
.vkf-explore__links a:hover { color: #8900a0; }
.vkf-sep { color: #D1D5DB; font-size: 11px; }

/* ── Outer footer ── */
.vkf-footer {
    background: linear-gradient(165deg, #370640 0%, #5c1169 40%, #701a75 70%, #8900a0 100%);
    border-top: 3px solid transparent;
    border-image: linear-gradient(90deg, #F5A623, #FFD166, #F5A623) 1;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* ── Main section ── */
.vkf-main { padding: 56px 0 44px; }

/* Brand column */
.vkf-logo {
    height: 52px; width: auto;
    margin-bottom: 18px; display: block;
}
.vkf-tagline {
    font-size: 13.5px; color: rgba(255,255,255,.66);
    line-height: 1.75; margin-bottom: 20px;
    max-width: 300px;
}

/* Trust pills — glass chips (matches .vkbd-trust-pills on dark surfaces) */
.vkf-trust { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 22px; }
.vkf-trust span {
    font-size: 11.5px; font-weight: 600; color: rgba(255,255,255,.9);
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.16);
    border-radius: 20px; padding: 5px 13px;
    display: inline-flex; align-items: center; gap: 5px;
}
.vkf-trust .fa { color: #F5A623; font-size: 11px; }

/* Social icons — glass chips */
.vkf-social { display: flex; gap: 9px; margin-top: 2px; }
.vkf-social a {
    width: 36px; height: 36px; border-radius: 10px;
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.85); font-size: 14px; text-decoration: none;
    transition: background .2s, color .2s, border-color .2s, transform .2s;
}
.vkf-social a:hover {
    background: #F5A623; color: #3a0a45;
    border-color: #F5A623;
    transform: translateY(-2px);
}

/* Column titles */
.vkf-col__title {
    font-size: 12px; font-weight: 800;
    letter-spacing: .08em; text-transform: uppercase;
    color: rgba(255,255,255,.5); margin-bottom: 20px;
    position: relative; padding-bottom: 10px;
}
.vkf-col__title::after {
    content: '';
    position: absolute; bottom: 0; left: 0;
    width: 28px; height: 2.5px;
    background: #F5A623; border-radius: 2px;
}

/* Nav links */
.vkf-links { list-style: none; padding: 0; margin: 0; }
.vkf-links li { margin-bottom: 10px; }
.vkf-links li a {
    font-size: 13.5px; color: rgba(255,255,255,.78);
    text-decoration: none;
    display: flex; align-items: center; gap: 6px;
    transition: color .18s, gap .18s;
}
.vkf-links li a::before {
    content: '›'; color: rgba(255,255,255,.32);
    font-size: 16px; line-height: 1;
    transition: color .18s;
    flex-shrink: 0;
}
.vkf-links li a:hover { color: #F5A623; gap: 9px; }
.vkf-links li a:hover::before { color: #F5A623; }

/* Address / Contact */
.vkf-address { font-style: normal; }
.vkf-address__row {
    display: flex; align-items: flex-start; gap: 11px;
    margin-bottom: 13px; font-size: 13px; color: rgba(255,255,255,.78);
    line-height: 1.6;
}
.vkf-address__row a { color: rgba(255,255,255,.78); text-decoration: none; transition: color .18s; }
.vkf-address__row a:hover { color: #F5A623; }
.vkf-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: rgba(245,166,35,.16); border: 1px solid rgba(245,166,35,.35);
    display: flex; align-items: center; justify-content: center;
    color: #F5A623; font-size: 13px;
    flex-shrink: 0; margin-top: 1px;
}

/* Divider */
.vkf-divider {
    border: none; border-top: 1px solid rgba(255,255,255,.1);
    margin: 0;
}

/* ── Bottom bar ── */
.vkf-bottom {
    background: #2c0834;
    border-top: 1px solid rgba(255,255,255,.08);
    padding: 16px 0;
}
.vkf-bottom__inner {
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
}
.vkf-bottom__inner p {
    margin: 0; font-size: 12.5px;
    color: rgba(255,255,255,.45); font-weight: 500;
}
.vkf-payments {
    display: flex; align-items: center;
    gap: 10px; font-size: 13px; color: rgba(255,255,255,.45);
}
.vkf-payments span {
    display: flex; align-items: center; gap: 4px;
    font-size: 12px; font-weight: 600;
}
.vkf-payments .fa { font-size: 18px; color: rgba(255,255,255,.3); }
.vkf-payments .fa-lock { font-size: 12px; }

/* ── Responsive ── */
@media(max-width: 767px) {
    .vkf-main { padding: 36px 0 28px; }
    .vkf-bottom__inner { flex-direction: column; text-align: center; gap: 8px; }
    .vkf-explore__group { flex-direction: column; gap: 4px; }
    .vkf-bottom { padding: 14px 0; margin-bottom: 0 !important; }
}
</style>
