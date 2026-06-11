{{--
  Header — Visit Kashi
  JS hooks preserved:
    • .navigation         → navbar-sticky class added by scroll in main.js
    • #navbar             → mouseenter/mouseleave dropdown in main.js
    • .navbar-arrow       → angle-right indicators injected by main.js
    • .navbar-default, .navbar-sticky-function → existing CSS classes kept
--}}

<div class="navigation">
    <div class="container">
        <div class="navigation-content">
            <div class="header_menu">
                <nav class="navbar navbar-default navbar-sticky-function navbar-arrow">

                    {{-- Logo --}}
                    <div class="logo pull-left">
                        <a href="{{ route('index') }}">
                            @if(websiteSetupValue('logo'))
                            <img alt="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}"
                                 src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                                 fetchpriority="high"
                                 width="160" height="48"
                                 decoding="async"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
                            @endif
                            <span class="vk-logo-text" style="display:{{ websiteSetupValue('logo') ? 'none' : 'flex' }};align-items:center;color:#fff;font-weight:800;font-size:1.25rem;letter-spacing:-.02em;font-family:'Plus Jakarta Sans',sans-serif;">
                                Visit Kashi
                            </span>
                        </a>
                    </div>

                    {{-- Desktop nav (hidden <992px via style.css) --}}
                    <div id="navbar" class="navbar-nav-wrapper">
                        <ul class="nav navbar-nav" id="responsive-menu">
                            @php
                                $homestayCategory = App\CPU\CategoryManager::active()->where('slug', 'homestay')->first();
                                $navCategories    = App\CPU\CategoryManager::active()->with(['subCategory'])->get();
                            @endphp
                            @foreach ($navCategories as $category)
                                @php
                                    $catSlug   = strtolower($category->slug ?? '');
                                    $isHotels  = str_contains($catSlug, 'hotel');
                                    $subCats   = $category->subCategory;
                                    $hasAnySub = count($subCats) > 0 || ($isHotels && $homestayCategory);
                                @endphp
                                @if(str_contains($catSlug, 'festival') || str_contains($catSlug, 'sight') || $catSlug === 'homestay')
                                    @continue
                                @endif
                                <li>
                                    <a @if(!$hasAnySub) href="{{ route('product.list', $category->slug) }}" @endif>
                                        {{ $category->name }}
                                        @if($hasAnySub)<i class="fa fa-angle-down"></i>@endif
                                    </a>
                                    @if($hasAnySub)
                                        <ul>
                                            @foreach ($subCats as $sub_category)
                                                <li>
                                                    <a href="{{ route('product.sub.list', [$category->slug, $sub_category->slug]) }}">
                                                        {{ $sub_category->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                            @if($isHotels && $homestayCategory)
                                                <li>
                                                    <a href="{{ route('product.list', $homestayCategory->slug) }}">
                                                        {{ $homestayCategory->name }}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Book Now CTA (desktop only, styled per page via .vk-nav__cta) --}}
                    <a href="{{ route('product.list', 'packages') }}" class="vk-nav__cta">
                        Book Now
                    </a>

                    {{-- Hamburger button (visible <992px) --}}
                    <button class="vk-hamburger" id="vkHamburger"
                            aria-label="Open navigation menu"
                            aria-expanded="false"
                            aria-controls="vkDrawer">
                        <span class="vk-hamburger__bar"></span>
                        <span class="vk-hamburger__bar"></span>
                        <span class="vk-hamburger__bar"></span>
                    </button>

                </nav>
            </div>
        </div>
    </div>
</div>

{{-- ══ Mobile Drawer ════════════════════════════════════════════════ --}}
<div class="vk-drawer-overlay" id="vkDrawerOverlay" aria-hidden="true"></div>

<aside class="vk-drawer" id="vkDrawer" aria-label="Mobile navigation" aria-hidden="true" role="dialog">

    {{-- Drawer header --}}
    <div class="vk-drawer__head">
        <a href="{{ route('index') }}" class="vk-drawer__logo">
            @if(websiteSetupValue('logo'))
            <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                 alt="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}"
                 height="38"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block';" />
            @endif
            <span style="display:{{ websiteSetupValue('logo') ? 'none' : 'block' }};color:#fff;font-weight:800;font-size:1.1rem;font-family:'Plus Jakarta Sans',sans-serif;">
                Visit Kashi
            </span>
        </a>
        <button class="vk-drawer__close" id="vkDrawerClose" aria-label="Close navigation">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <line x1="1" y1="1" x2="17" y2="17" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <line x1="17" y1="1" x2="1" y2="17" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    {{-- Nav links --}}
    <nav class="vk-drawer__nav">
        @foreach ($navCategories as $category)
            @php
                $catSlug   = strtolower($category->slug ?? '');
                $isHotels  = str_contains($catSlug, 'hotel');
                $subCats   = $category->subCategory;
                $hasAnySub = count($subCats) > 0 || ($isHotels && $homestayCategory);
            @endphp
            @if(str_contains($catSlug, 'festival') || str_contains($catSlug, 'sight') || $catSlug === 'homestay')
                @continue
            @endif

            @if(!$hasAnySub)
                <a href="{{ route('product.list', $category->slug) }}" class="vk-drawer__link">
                    {{ $category->name }}
                </a>
            @else
                <div class="vk-drawer__group">
                    <button class="vk-drawer__group-btn" aria-expanded="false">
                        <span>{{ $category->name }}</span>
                        <svg class="vk-drawer__chevron" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <polyline points="2 5 7 10 12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <ul class="vk-drawer__sub">
                        <li>
                            <a href="{{ route('product.list', $category->slug) }}" class="vk-drawer__sub-link vk-drawer__sub-link--all">
                                All {{ $category->name }}
                            </a>
                        </li>
                        @foreach ($subCats as $sub_category)
                            <li>
                                <a href="{{ route('product.sub.list', [$category->slug, $sub_category->slug]) }}" class="vk-drawer__sub-link">
                                    {{ $sub_category->name }}
                                </a>
                            </li>
                        @endforeach
                        @if($isHotels && $homestayCategory)
                            <li>
                                <a href="{{ route('product.list', $homestayCategory->slug) }}" class="vk-drawer__sub-link">
                                    {{ $homestayCategory->name }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

        @endforeach
    </nav>

    {{-- Drawer footer --}}
    <div class="vk-drawer__foot">
        <a href="https://wa.me/+917080109919"
           target="_blank" rel="noopener noreferrer"
           class="vk-drawer__whatsapp">
            <img src="{{ asset('frontend/images/whatsapp.png') }}" alt="" width="20" height="20" />
            Chat on WhatsApp
        </a>
        <p class="vk-drawer__tagline">Varanasi's #1 Travel Platform</p>
    </div>

</aside>

{{-- Vanilla JS — works immediately, no jQuery dependency --}}
<script>
(function () {
    var hamburger = document.getElementById('vkHamburger');
    var drawer    = document.getElementById('vkDrawer');
    var overlay   = document.getElementById('vkDrawerOverlay');
    var closeBtn  = document.getElementById('vkDrawerClose');
    if (!hamburger || !drawer) return;

    function openDrawer() {
        drawer.classList.add('is-open');
        overlay.classList.add('is-open');
        hamburger.classList.add('is-active');
        hamburger.setAttribute('aria-expanded', 'true');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        drawer.classList.remove('is-open');
        overlay.classList.remove('is-open');
        hamburger.classList.remove('is-active');
        hamburger.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', function () {
        drawer.classList.contains('is-open') ? closeDrawer() : openDrawer();
    });
    overlay.addEventListener('click', closeDrawer);
    closeBtn.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeDrawer();
    });

    /* Sub-menu accordion */
    document.querySelectorAll('.vk-drawer__group-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var isOpen = this.getAttribute('aria-expanded') === 'true';
            /* Close all others */
            document.querySelectorAll('.vk-drawer__group-btn').forEach(function (b) {
                b.setAttribute('aria-expanded', 'false');
                b.closest('.vk-drawer__group').classList.remove('is-open');
            });
            if (!isOpen) {
                this.setAttribute('aria-expanded', 'true');
                this.closest('.vk-drawer__group').classList.add('is-open');
            }
        });
    });
})();
</script>
