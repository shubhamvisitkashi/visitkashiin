<style>
    /* ── Scrollbar gutter: reserves scrollbar space so hiding it never shifts content ── */
    html {
        scrollbar-gutter: stable;
    }

    /* ═══════════════════════════════════════
       Classic Sidebar Design
    ═══════════════════════════════════════ */

    /* ── Shell ── */
    .sidebar {
        background: #2c2c54;
        border-right: 1px solid rgba(255, 255, 255, .08);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 240px;
        z-index: 999;
        display: flex;
        flex-direction: column;
        will-change: transform;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        transform: translateZ(0);
    }

    /* ── Header ── */
    .sidebar .sidebar-header,
    .sidebar-header {
        flex-shrink: 0;
        background: #1c97e9 !important;
        height: 60px !important;
        border-bottom: 1px solid #e9ecef !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 0 25px !important;
        border-right: 1px solid #e9ecef !important;
        z-index: 999 !important;
        width: 240px !important;
        -webkit-transition: width 0.1s ease !important;
        transition: width 0.1s ease !important;
        gap: 10px;
    }

    /* ── Brand ── */
    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }
    .sidebar-brand img {
        height: 34px;
        width: auto;
        object-fit: contain;
        flex-shrink: 0;
    }
    .sidebar-brand-text { min-width: 0; }
    .sidebar-brand-name {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: .2px;
    }
    .sidebar-brand-tag { display: none; }

    /* ── Toggler ── */
    .sidebar-toggler {
        display: none;
        flex-shrink: 0;
        width: 32px; height: 32px;
        border-radius: 6px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .15s;
        padding: 0;
    }
    .sidebar-toggler:hover { background: rgba(255,255,255,.2); }
    .sidebar-toggler span {
        display: block;
        width: 14px; height: 1.5px;
        background: #fff;
        margin: 2.5px auto;
        border-radius: 1px;
    }

    /* ── Body ── */
    .sidebar-body {
        padding: 6px 0 4px;
        overflow-y: auto;
        overflow-x: hidden !important;
        flex: 1;
        min-height: 0;
        max-width: 100%;
    }
    .sidebar-body.ps,
    .sidebar-body.ps--active-x { overflow-x: hidden !important; }
    .sidebar .ps__rail-x,
    .sidebar .ps__thumb-x { display:none !important; height:0 !important; opacity:0 !important; }
    .sidebar .ps__rail-y { right: 0 !important; width: 4px !important; }
    .sidebar .ps__thumb-y { width: 4px !important; background: rgba(255,255,255,.15) !important; border-radius: 0 !important; }
    .sidebar .nav-item,
    .sidebar .nav-link,
    .sidebar .sub-menu { max-width:100%; overflow-x:hidden; }
    .sidebar .link-title { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; min-width:0; }

    .sidebar-body::-webkit-scrollbar { width: 4px; }
    .sidebar-body::-webkit-scrollbar-track { background: rgba(0,0,0,.1); }
    .sidebar-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); }
    .sidebar-body::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.25); }

    /* ── Category labels ── */
    /* ── Category labels ── */
    .sidebar .sidebar-body .nav li.nav-item.nav-category,
    .sidebar .sidebar-body .nav .nav-item.nav-category {
        color: rgba(255,255,255,.45) !important;
        font-size: 9.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.3px !important;
        padding: 18px 18px 6px 18px !important;
        margin: 0 !important;
        height: auto !important;
        display: block !important;
        user-select: none;
        border-top: 1px solid rgba(255,255,255,.08) !important;
        border-radius: 0 !important;
        background: transparent !important;
        line-height: 1.2 !important;
        cursor: default !important;
        pointer-events: none;
    }
    /* First category: no top border */
    .sidebar .sidebar-body .nav li.nav-item.nav-category:first-child,
    .sidebar .sidebar-body .nav .nav-item.nav-category:first-child {
        border-top: none !important;
        padding-top: 10px !important;
    }
    /* Kill the 20px margin-top the template forces on :not(:first-child) */
    .sidebar .sidebar-body .nav li.nav-item.nav-category:not(:first-child),
    .sidebar .sidebar-body .nav .nav-item.nav-category:not(:first-child) {
        margin-top: 0 !important;
        padding-left: 18px !important;
    }

    /* ── Nav items ── */
    .nav-item {
        margin: 0;
        border-radius: 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 18px;
        color: rgba(255,255,255,.68);
        text-decoration: none;
        border-radius: 0;
        transition: background .15s, color .15s, border-color .15s;
        position: relative;
        font-size: 13.5px;
        border-left: 3px solid transparent;
        gap: 0;
    }
    .nav-link:hover {
        background: rgba(255,255,255,.06);
        color: #fff;
        border-left-color: rgba(255,255,255,.25);
    }
    .nav-link:hover .icon-wrapper { background: rgba(255,255,255,.08); }
    .nav-link:hover .icon-wrapper i { color: #fff; }

    /* ── Active ── */
    .nav-item.active > .nav-link {
        background: #7344ed;
        color: #fff !important;
        border-left: 3px solid #f9c74f;
        border-radius: 0;
        font-weight: 600;
    }
    .nav-item.active > .nav-link .icon-wrapper { background: rgba(249,199,79,.15); }
    .nav-item.active > .nav-link .icon-wrapper i { color: #f9c74f; }

    /* ── Icon wrapper ── */
    .icon-wrapper {
        width: 28px; height: 28px;
        border-radius: 5px;
        background: rgba(255,255,255,.06);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 11px;
        flex-shrink: 0;
        transition: background .15s;
    }
    .icon-wrapper i {
        width: 15px; height: 15px;
        stroke-width: 2;
        color: rgba(255,255,255,.55);
        transition: color .15s;
    }

    /* ── Arrow ── */
    .link-arrow {
        width: 13px; height: 13px;
        margin-left: auto;
        opacity: .45;
        stroke-width: 2.5;
        transition: transform .22s ease, opacity .15s;
        flex-shrink: 0;
    }
    .nav-link[aria-expanded="true"] .link-arrow {
        transform: rotate(180deg);
        opacity: .85;
    }

    /* ── Link title ── */
    .link-title {
        font-size: 13.5px;
        font-weight: 400;
        flex: 1;
        letter-spacing: 0;
        margin-left: 0 !important;
    }

    /* ── Submenu ── */
    .sub-menu {
        padding: 0;
        margin: 0;
        list-style: none;
        background: rgba(0,0,0,.15);
        border-left: 3px solid rgba(255,255,255,.08);
    }
    .sub-menu .nav-item { margin: 0; }
    .sub-menu .nav-link {
        padding: 8px 18px 8px 52px;
        font-size: 13px;
        color: rgba(255,255,255,.5);
        border-left: none;
        background: transparent;
        position: relative;
    }
    .sub-menu .nav-link::before {
        content: '–';
        position: absolute;
        left: 32px;
        color: rgba(255,255,255,.25);
        font-size: 12px;
    }
    .sub-menu .nav-link:hover {
        color: #fff;
        background: rgba(255,255,255,.05);
    }
    .sub-menu .nav-item.active .nav-link,
    .sub-menu .nav-link.active {
        color: #f9c74f;
        font-weight: 600;
        background: rgba(249,199,79,.06);
    }
    .sub-menu .nav-link.active::before { color: #f9c74f; }

    /* ── Badge ── */
    .nav-badge {
        display: inline-flex;
        align-items: center;
        padding: 1px 7px;
        font-size: 9.5px;
        font-weight: 700;
        background: #f9c74f;
        color: #1a1a2e;
        border-radius: 3px;
        margin-left: auto;
        letter-spacing: .3px;
    }

    /* ── Collapse ── */
    .collapse { transition: all .22s ease; }

    /* ── Page Wrapper ── */
    .page-wrapper {
        margin-left: 260px;
        min-height: 100vh;
    }

    /* ── Sidebar footer CSS (kept but unused) ── */
    .sidebar-footer { display: none; }

    /* ══ Mobile Overlay ══════════════════════════════════════════ */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(10, 14, 30, 0.6);
        /* backdrop-filter removed — causes page blur when dropdown opens on mobile */
        z-index: 998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        will-change: opacity;
    }
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* ══ Mobile Sidebar ═══════════════════════════════════════════ */
    @media (max-width: 991px) {

        /* Slide in from left */
        .sidebar {
            width: 270px;
            background: #2c2c54;
            transform: translateX(-100%) translateZ(0);
            transition: transform 0.28s ease;
            border-radius: 0;
            box-shadow: none;
        }
        .sidebar.active {
            transform: translateX(0) translateZ(0);
            box-shadow: 4px 0 24px rgba(0,0,0,.4);
        }

        /* Header on mobile */
        .sidebar-header { border-radius: 0; }

        /* Show ✕ close button */
        .sidebar .sidebar-header .sidebar-toggler {
            display: flex !important;
            width: 30px; height: 30px;
            border-radius: 6px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }
        .sidebar .sidebar-header .sidebar-toggler span { display: none; }
        .sidebar .sidebar-header .sidebar-toggler::before {
            content: '✕';
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
        }

        /* Classic touch targets on mobile */
        .sidebar-body { padding-top: 4px; }
        .nav-item { margin: 0; }
        .nav-link { padding: 12px 18px; min-height: 46px; font-size: 14px; border-radius: 0; }
        .nav-link .link-title { font-size: 14px; }
        .icon-wrapper { width: 28px; height: 28px; border-radius: 4px; }
        .icon-wrapper i[data-feather], .icon-wrapper svg { width: 16px; height: 16px; }
        .nav-item.active > .nav-link { background: #7344ed; color: #fff !important; border-left: 3px solid #f9c74f; border-radius: 0; font-weight: 600; }
        .nav-item.active > .nav-link .icon-wrapper { background: rgba(249,199,79,.15); }
        .nav-item.active > .nav-link .icon-wrapper i { color: #f9c74f; }
        .nav-category { font-size: 10px; padding: 16px 18px 5px; letter-spacing: 1.2px; }
        .sub-menu .nav-link { padding: 10px 18px 10px 52px; min-height: 42px; font-size: 13px; }

        /* Page wrapper */
        .page-wrapper { margin-left: 0 !important; }
        .sidebar-toggler {
            display: block;
        }
    }

    /* ══ Bottom Nav Bar ═══════════════════════════════════════════ */
    @media (max-width: 991px) {
        .sa-mob-nav {
            background: #ffffff;
            /* backdrop-filter removed — prevents blur bleed onto dropdowns */
            border-top: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 -4px 24px rgba(0,0,0,0.1);
            height: 68px;
            padding: 0 4px;
        }
        .sa-mob-nav__item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            padding: 8px 4px 10px;
            color: #888;
            text-decoration: none;
            border-radius: 14px;
            transition: color 0.2s, transform 0.2s;
            position: relative;
        }
        .sa-mob-nav__item i[data-feather], .sa-mob-nav__item svg {
            width: 22px;
            height: 22px;
            stroke-width: 1.8;
            transition: transform 0.2s;
        }
        .sa-mob-nav__item span {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .sa-mob-nav__item.active {
            color: #a78bfa;
        }
        .sa-mob-nav__item.active i[data-feather],
        .sa-mob-nav__item.active svg {
            transform: scale(1.15);
        }
        /* Active pill indicator */
        .sa-mob-nav__item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: linear-gradient(90deg, #8b5cf6, #7c3aed);
            border-radius: 0 0 3px 3px;
        }
        .sa-mob-nav__item:active {
            transform: scale(0.92);
        }
        /* FAB button */
        .sa-mob-nav__fab {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            box-shadow: 0 6px 20px rgba(139,92,246,0.45);
            margin-bottom: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .sa-mob-nav__fab:hover,
        .sa-mob-nav__fab:active {
            transform: scale(0.94);
            box-shadow: 0 4px 14px rgba(139,92,246,0.5);
        }
        .sa-mob-nav__fab i[data-feather], .sa-mob-nav__fab svg {
            width: 22px;
            height: 22px;
        }
    }

    /* ── Misc ── */
    * { -webkit-tap-highlight-color: transparent; }
    .nav-link:focus { outline: none; }
    .nav-item + .nav-category { margin-top: 4px; }
</style>

<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            @if (websiteSetupValue('logo'))
                <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}" alt="{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}">
            @else
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-feather="compass" style="width:16px;height:16px;stroke:#fff;"></i>
                </div>
            @endif
            <div class="sidebar-brand-text">
                <span class="sidebar-brand-name">{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}</span>
                
            </div>
        </a>
        <div class="sidebar-toggler">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">

            {{-- ── Dashboard ── --}}
            <li class="nav-item @if (Route::currentRouteName() == 'admin.dashboard') active @endif">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <div class="icon-wrapper"><i data-feather="home"></i></div>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            {{-- ══════════════ OPERATIONS ══════════════ --}}
            <li class="nav-item nav-category">Operations</li>

            {{-- Enquiries (priority #1 daily action) --}}
            @can('enquiry-list')
                <li class="nav-item @if (Route::currentRouteName() == 'enquiry.index') active @endif">
                    <a href="{{ route('enquiry.index') }}" class="nav-link">
                        <div class="icon-wrapper"><i data-feather="message-circle"></i></div>
                        <span class="link-title">Enquiries</span>
                    </a>
                </li>
            @endcan

            {{-- Bookings --}}
            @canany(['booking-list', 'booking-view', 'booking-create'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['bookings.index','bookings.show','bookings.create-direct','bookings.calendar'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageBookings" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['bookings.index','bookings.show','bookings.create-direct','bookings.calendar'])) true @else false @endif"
                        aria-controls="manageBookings">
                        <div class="icon-wrapper"><i data-feather="calendar"></i></div>
                        <span class="link-title">Bookings</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['bookings.index','bookings.show','bookings.create-direct','bookings.calendar'])) show @endif" id="manageBookings">
                        <ul class="nav sub-menu">
                            @can('booking-create')
                                <li class="nav-item">
                                    <a href="{{ route('bookings.create-direct') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'bookings.create-direct') active @endif">
                                        <i data-feather="plus-circle" style="width:14px;height:14px;margin-right:6px;"></i>Create Booking
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('bookings.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['bookings.index','bookings.show'])) active @endif">All Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('bookings.calendar') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'bookings.calendar') active @endif">Booking Calendar</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Cab Bookings --}}
            <li class="nav-item @if (in_array(Route::currentRouteName(), ['cab-bookings.index','cab-bookings.show','cab-bookings.create','cab-bookings.edit'])) active @endif">
                <a class="nav-link" data-bs-toggle="collapse" href="#manageCabBookings" role="button"
                    aria-expanded="@if (in_array(Route::currentRouteName(), ['cab-bookings.index','cab-bookings.show','cab-bookings.create','cab-bookings.edit'])) true @else false @endif"
                    aria-controls="manageCabBookings">
                    <div class="icon-wrapper"><i data-feather="truck"></i></div>
                    <span class="link-title">Cab Bookings</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse @if (in_array(Route::currentRouteName(), ['cab-bookings.index','cab-bookings.show','cab-bookings.create','cab-bookings.edit'])) show @endif" id="manageCabBookings">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('cab-bookings.create') }}"
                                class="nav-link @if (Route::currentRouteName() == 'cab-bookings.create') active @endif">
                                <i data-feather="plus-circle" style="width:14px;height:14px;margin-right:6px;"></i>New Booking
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cab-bookings.index') }}"
                                class="nav-link @if (in_array(Route::currentRouteName(), ['cab-bookings.index','cab-bookings.show','cab-bookings.edit'])) active @endif">All Bookings</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ══════════════ ANALYTICS & REPORTS (hidden for Staff) ══════════════ --}}
            @unless(auth('admin')->user()?->hasRole('Staff'))
            <li class="nav-item nav-category">Analytics & Reports</li>

            {{-- All analytics collapsed into one parent --}}
            @canany(['dashboard-view', 'analytics-profit', 'analytics-customer', 'payment-view', 'activity-log-view', 'staff-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['executive-dashboard.index','sales-analytics.index','payment-analytics.index','customer.analytics','profit-analytics.index','monthly-report.index','activity-logs.index','targets.index','payment-accounts.index','payment-accounts.create','payment-accounts.show','payment-accounts.edit','vendor-settlements.index','vendor-settlements.show','ledger.index'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageReports" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['executive-dashboard.index','sales-analytics.index','payment-analytics.index','customer.analytics','profit-analytics.index','activity-logs.index','targets.index'])) true @else false @endif"
                        aria-controls="manageReports">
                        <div class="icon-wrapper"><i data-feather="bar-chart-2"></i></div>
                        <span class="link-title">Reports & Analytics</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['executive-dashboard.index','sales-analytics.index','payment-analytics.index','customer.analytics','profit-analytics.index','monthly-report.index','activity-logs.index','targets.index','payment-accounts.index','payment-accounts.create','payment-accounts.show','payment-accounts.edit','vendor-settlements.index','vendor-settlements.show','ledger.index'])) show @endif" id="manageReports">
                        <ul class="nav sub-menu">
                            {{-- Payments moved here from Finance --}}
                            @canany(['payment-list', 'payment-view', 'payment-account-view'])
                                <li class="nav-item">
                                    <a href="{{ route('payment-accounts.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['payment-accounts.index','payment-accounts.create','payment-accounts.show','payment-accounts.edit'])) active @endif">Payment Accounts</a>
                                </li>
                            @endcanany
                            @canany(['service-provider-list', 'vendor-list'])
                                <li class="nav-item">
                                    <a href="{{ route('vendor-settlements.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['vendor-settlements.index','vendor-settlements.show'])) active @endif">Vendor Settlements</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view', 'analytics-customer'])
                                <li class="nav-item">
                                    <a href="{{ route('executive-dashboard.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'executive-dashboard.index') active @endif">Executive Dashboard</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view', 'analytics-customer'])
                                <li class="nav-item">
                                    <a href="{{ route('sales-analytics.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'sales-analytics.index') active @endif">Sales Performance</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view', 'analytics-profit', 'payment-view'])
                                <li class="nav-item">
                                    <a href="{{ route('payment-analytics.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'payment-analytics.index') active @endif">Payment & Cash Flow</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view', 'analytics-profit', 'payment-view', 'payment-account-view'])
                                <li class="nav-item">
                                    <a href="{{ route('ledger.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'ledger.index') active @endif">Ledger</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view', 'analytics-customer'])
                                <li class="nav-item">
                                    <a href="{{ route('customer.analytics') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'customer.analytics') active @endif">Customer Analytics</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view', 'analytics-profit'])
                                <li class="nav-item">
                                    <a href="{{ route('profit-analytics.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'profit-analytics.index') active @endif">Profit Analytics</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view'])
                                <li class="nav-item">
                                    <a href="{{ route('monthly-report.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'monthly-report.index') active @endif">Monthly Report</a>
                                </li>
                            @endcanany
                            @canany(['dashboard-view', 'staff-list'])
                                <li class="nav-item">
                                    <a href="{{ route('targets.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'targets.index') active @endif">Target Management</a>
                                </li>
                            @endcanany
                            @canany(['activity-log-view', 'dashboard-view'])
                                <li class="nav-item">
                                    <a href="{{ route('activity-logs.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'activity-logs.index') active @endif">Activity Logs</a>
                                </li>
                            @endcanany
                            <li class="nav-item">
                                <a href="{{ route('youtube-videos.index') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'youtube-videos.index') active @endif">
                                    <i class="fa fa-youtube-play text-danger" style="width:16px;"></i> YouTube Videos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('instagram-reels.index') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'instagram-reels.index') active @endif">
                                    <i class="fa fa-instagram text-danger" style="width:16px;"></i> Instagram Reels
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany
            @endunless

            {{-- ══════════════ CONFIGURATION (Super Admin only) ══════════════ --}}
            @if(auth('admin')->user()?->hasRole('Super Admin'))
            <li class="nav-item nav-category">Configuration</li>

            {{-- Tour Packages --}}
            @canany(['category-list', 'sub_category-list', 'package-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['category.index','category.edit','sub-category.index','sub-category.edit','product.index','product.create','product.edit'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageTourPackages" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['category.index','category.edit','sub-category.index','sub-category.edit','product.index','product.create','product.edit'])) true @else false @endif"
                        aria-controls="manageTourPackages">
                        <div class="icon-wrapper"><i data-feather="package"></i></div>
                        <span class="link-title">Tour Packages</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['category.index','category.edit','sub-category.index','sub-category.edit','product.index','product.create','product.edit'])) show @endif" id="manageTourPackages">
                        <ul class="nav sub-menu">
                            @can('category-list')
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['category.index','category.edit'])) active @endif">Categories</a>
                                </li>
                            @endcan
                            @can('sub_category-list')
                                <li class="nav-item">
                                    <a href="{{ route('sub-category.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['sub-category.index','sub-category.edit'])) active @endif">Sub Categories</a>
                                </li>
                            @endcan
                            @can('package-list')
                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['product.index','product.create','product.edit'])) active @endif">All Packages</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Service Catalog --}}
            @canany(['service-template-list', 'service-type-list', 'service-item-list', 'service-provider-list', 'vendor-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['service-types.index','service-items.index','service-items.create','service-items.edit','service-templates.index','service-templates.create','service-templates.edit','service-providers.index','service-providers.create','service-providers.edit'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#serviceCatalog" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['service-types.index','service-items.index','service-items.create','service-items.edit','service-templates.index','service-templates.create','service-templates.edit','service-providers.index','service-providers.create','service-providers.edit'])) true @else false @endif"
                        aria-controls="serviceCatalog">
                        <div class="icon-wrapper"><i data-feather="grid"></i></div>
                        <span class="link-title">Service Catalog</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['service-types.index','service-items.index','service-items.create','service-items.edit','service-templates.index','service-templates.create','service-templates.edit','service-providers.index','service-providers.create','service-providers.edit'])) show @endif" id="serviceCatalog">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('service-types.index') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'service-types.index') active @endif">Service Types</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('service-templates.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['service-templates.index','service-templates.create','service-templates.edit'])) active @endif">Service Templates</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('service-items.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['service-items.index','service-items.create','service-items.edit'])) active @endif">Vendor Service Rates</a>
                            </li>
                            @canany(['service-provider-list', 'vendor-list'])
                            <li class="nav-item">
                                <a href="{{ route('service-providers.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['service-providers.index','service-providers.create','service-providers.edit'])) active @endif">Service Providers</a>
                            </li>
                            @endcanany
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Boats (moved out of Website Settings into own section) --}}
            @canany(['web_setup'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['boat-type.index','boat-type.edit','boat.index','boat.create','boat.edit','boatmen.index'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageBoats" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['boat-type.index','boat-type.edit','boat.index','boat.create','boat.edit','boatmen.index'])) true @else false @endif"
                        aria-controls="manageBoats">
                        <div class="icon-wrapper"><i data-feather="anchor"></i></div>
                        <span class="link-title">Boats</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['boat-type.index','boat-type.edit','boat.index','boat.create','boat.edit','boatmen.index'])) show @endif" id="manageBoats">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('boat-type.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['boat-type.index','boat-type.edit'])) active @endif">Boat Types</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('boat.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['boat.index','boat.create','boat.edit'])) active @endif">All Boats</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('boatmen.index') }}"
                                    class="nav-link @if (Route::currentRouteName() === 'boatmen.index') active @endif">Boatmen</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany


            {{-- Agents --}}
            @canany(['agent-list', 'lead-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['agent-service.index','agent-service.edit','agent.index','agent.edit'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageagent" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['agent-service.index','agent-service.edit','agent.index','agent.edit'])) true @else false @endif"
                        aria-controls="manageagent">
                        <div class="icon-wrapper"><i data-feather="briefcase"></i></div>
                        <span class="link-title">Agents</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['agent-service.index','agent-service.edit','agent.index','agent.edit'])) show @endif" id="manageagent">
                        <ul class="nav sub-menu">
                            @can('lead-list')
                                <li class="nav-item">
                                    <a href="{{ route('agent.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['agent.index','agent.edit'])) active @endif">All Agents</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Staff Management --}}
            @canany(['staff-list', 'role-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['staffs.index','staffs.edit','roles.index','roles.edit'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageStaff" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['staffs.index','staffs.edit','roles.index','roles.edit'])) true @else false @endif"
                        aria-controls="manageStaff">
                        <div class="icon-wrapper"><i data-feather="users"></i></div>
                        <span class="link-title">Staff Management</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['staffs.index','staffs.edit','roles.index','roles.edit'])) show @endif" id="manageStaff">
                        <ul class="nav sub-menu">
                            @can('staff-list')
                                <li class="nav-item">
                                    <a href="{{ route('staffs.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['staffs.index','staffs.edit'])) active @endif">All Staff</a>
                                </li>
                            @endcan
                            @can('role-list')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['roles.index','roles.edit'])) active @endif">Roles & Permissions</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Website Settings (dropdown with Promo Banners) --}}
            @can('web_setup')
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['web_setup.index','promo-banners.index'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#websiteSettingsMenu" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['web_setup.index','promo-banners.index'])) true @else false @endif"
                        aria-controls="websiteSettingsMenu">
                        <div class="icon-wrapper"><i data-feather="settings"></i></div>
                        <span class="link-title">Website Settings</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['web_setup.index','promo-banners.index'])) show @endif" id="websiteSettingsMenu">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('web_setup.index') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'web_setup.index') active @endif">General Setup</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('promo-banners.index') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'promo-banners.index') active @endif">Promo Banners</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            @endif {{-- end Super Admin Configuration block --}}

        </ul>
    </div>

</nav>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar  = document.querySelector('.sidebar');
        const overlay  = document.querySelector('.sidebar-overlay');
        const sidebarTogglers = document.querySelectorAll('.sidebar-toggler');

        /* ── Scrollbar-width compensation ──────────────────────────────
           Root cause of the #1 shake: hiding body overflow removes the
           scrollbar (~17px), shifting content left visibly.
           Fix: pad body by exactly that width before hiding overflow.
        ────────────────────────────────────────────────────────────── */
        function getScrollbarWidth() {
            return window.innerWidth - document.documentElement.clientWidth;
        }

        function openSidebar() {
            const sbw = getScrollbarWidth();
            if (sbw > 0) document.body.style.paddingRight = sbw + 'px';
            document.body.style.overflow = 'hidden';
            sidebar.classList.add('active');
            overlay.classList.add('active');
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            // Remove padding AFTER transition ends to prevent visual jump
            setTimeout(function() {
                document.body.style.overflow   = '';
                document.body.style.paddingRight = '';
            }, 300);
        }

        /* Expose globally so the mobile nav Menu button can call them */
        window.toggleAdminSidebar = function() {
            sidebar.classList.contains('active') ? closeSidebar() : openSidebar();
        };

        // Toggle on hamburger click
        sidebarTogglers.forEach(function(toggler) {
            toggler.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.contains('active') ? closeSidebar() : openSidebar();
            });
        });

        // Close on overlay click
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        // Close on nav-link click (non-collapse items only)
        if (window.innerWidth <= 991) {
            document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (!this.hasAttribute('data-bs-toggle')) {
                        setTimeout(closeSidebar, 180);
                    }
                });
            });
        }

        // Reset on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow    = '';
                document.body.style.paddingRight = '';
            }
        });

        // Re-init PerfectScrollbar — suppress X scroll, use native Y where PS unavailable
        var sbBody = document.querySelector('.sidebar-body');
        if (sbBody) {
            if (typeof PerfectScrollbar !== 'undefined') {
                try {
                    if (sbBody._ps) { sbBody._ps.destroy(); sbBody._ps = null; }
                    var ps = new PerfectScrollbar(sbBody, {
                        suppressScrollX: true,
                        wheelPropagation: false,
                        swipeEasing: true
                    });
                    sbBody._ps = ps;
                } catch(e) {}
            } else {
                // Fallback: ensure native scroll works
                sbBody.style.overflowY = 'auto';
                sbBody.style.overflowX = 'hidden';
            }
        }

        /* ── Swipe left to close sidebar on mobile ────────────────── */
        var touchStartX = 0;
        var touchStartY = 0;
        if (sidebar) {
            sidebar.addEventListener('touchstart', function(e) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }, { passive: true });

            sidebar.addEventListener('touchend', function(e) {
                var dx = e.changedTouches[0].clientX - touchStartX;
                var dy = Math.abs(e.changedTouches[0].clientY - touchStartY);
                // Swipe left ≥ 60px, not a vertical scroll
                if (dx < -60 && dy < 60 && window.innerWidth <= 991) {
                    closeSidebar();
                }
            }, { passive: true });
        }

        /* ── Swipe right from left edge to open sidebar ───────────── */
        document.addEventListener('touchstart', function(e) {
            if (e.touches[0].clientX < 20) touchStartX = e.touches[0].clientX;
        }, { passive: true });
        document.addEventListener('touchend', function(e) {
            var dx = e.changedTouches[0].clientX - touchStartX;
            var dy = Math.abs(e.changedTouches[0].clientY - touchStartY);
            if (touchStartX < 20 && dx > 60 && dy < 80 && window.innerWidth <= 991) {
                openSidebar();
            }
        }, { passive: true });

        /* ── Inject user profile strip into sidebar body (mobile only) */
        function injectProfileStrip() {
            if (window.innerWidth > 991) return;
            if (document.getElementById('sb-profile-strip')) return;
            var name  = '{{ optional(auth()->user())->name ?? "Admin" }}';
            var email = '{{ optional(auth()->user())->email ?? "" }}';
            var initial = name.charAt(0).toUpperCase();
            var strip = document.createElement('div');
            strip.id = 'sb-profile-strip';
            strip.innerHTML =
                '<div style="display:flex;align-items:center;gap:12px;padding:14px 18px 14px;background:rgba(139,92,246,0.1);border-bottom:1px solid rgba(255,255,255,0.07);margin-bottom:4px;">' +
                    '<div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:#fff;flex-shrink:0;">' + initial + '</div>' +
                    '<div style="min-width:0;">' +
                        '<div style="font-size:13px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + name + '</div>' +
                        '<div style="font-size:11px;color:rgba(255,255,255,0.5);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + email + '</div>' +
                    '</div>' +
                '</div>';
            sbBody.insertBefore(strip, sbBody.firstChild);
        }

        // Inject on first open
        sidebar.addEventListener('transitionend', function() {
            if (sidebar.classList.contains('active')) injectProfileStrip();
        });
        // Also inject immediately if sidebar is somehow already open
        if (sidebar.classList.contains('active')) injectProfileStrip();
    });
</script>
