<style>
    /* Premium Sidebar Styles */
    .sidebar {
        background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        box-shadow: 4px 0 30px rgba(0, 0, 0, 0.15);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 260px;
        z-index: 999;
        display: flex;
        flex-direction: column;
    }

    .sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23667eea" fill-opacity="0.03" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>') no-repeat top;
        background-size: cover;
        pointer-events: none;
    }

    /* Sidebar Header */
    .sidebar-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 18px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 65px;
    }

    .sidebar-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
        animation: pulse 6s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.4;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.6;
        }
    }

    .sidebar-brand {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 1;
        color: white;
    }

    .sidebar-brand img {
        height: 32px;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.2));
    }

    .sidebar-brand strong {
        background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 17px;
    }

    .sidebar-brand i {
        width: 24px;
        height: 24px;
        stroke-width: 2.5;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    /* Sidebar Body */
    .sidebar-body {
        padding: 12px 0;
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
        position: relative;
        z-index: 1;
    }

    .sidebar-body::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-body::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 10px;
        margin: 10px 0;
    }

    .sidebar-body::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
    }

    .sidebar-body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #764ba2 0%, #667eea 100%);
    }

    /* Navigation Category */
    .nav-category {
        color: #8b92a8;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        padding: 18px 18px 8px 18px;
        margin-top: 6px;
        position: relative;
        display: flex;
        align-items: center;
    }

    .nav-category::before {
        content: '';
        width: 16px;
        height: 2px;
        background: linear-gradient(90deg, #667eea 0%, transparent 100%);
        margin-right: 7px;
        border-radius: 2px;
    }

    /* Navigation Items */
    .nav-item {
        margin: 1px 8px;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 9px 12px;
        color: #c5ccd8;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        font-size: 13.5px;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
        border-radius: 0 3px 3px 0;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.08) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #ffffff;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .nav-link:hover::before {
        transform: scaleY(1);
    }

    .nav-link:hover::after {
        opacity: 1;
    }

    .nav-link:hover .icon-wrapper {
        transform: scale(1.08);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
    }

    .nav-item.active>.nav-link {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.18) 0%, rgba(118, 75, 162, 0.18) 100%);
        color: #ffffff;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
        border: 1px solid rgba(102, 126, 234, 0.25);
    }

    .nav-item.active>.nav-link::before {
        transform: scaleY(1);
    }

    .nav-item.active>.nav-link .icon-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
    }

    .nav-item.active>.nav-link .icon-wrapper i {
        color: white;
    }

    /* Icon Wrapper - Made Smaller */
    .icon-wrapper {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        flex-shrink: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .icon-wrapper i {
        width: 16px;
        height: 16px;
        stroke-width: 2.3;
        position: relative;
        z-index: 1;
    }

    .link-arrow {
        width: 14px;
        height: 14px;
        margin-left: auto;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0.6;
        stroke-width: 2.5;
    }

    .nav-link[aria-expanded="true"] .link-arrow {
        transform: rotate(180deg);
        opacity: 1;
    }

    .link-title {
        font-size: 13px;
        font-weight: 500;
        margin-left: 10px !important;
        flex: 1;
        letter-spacing: 0.2px;
    }

    /* Submenu */
    .sub-menu {
        padding: 5px 0;
        margin: 0;
        list-style: none;
        background: rgba(0, 0, 0, 0.15);
        border-radius: 0 0 10px 10px;
    }

    .sub-menu .nav-item {
        margin: 1px 6px;
    }

    .sub-menu .nav-link {
        padding: 8px 12px 8px 46px;
        font-size: 12.5px;
        color: #adb5c7;
        background: transparent;
    }

    .sub-menu .nav-link:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.04);
        transform: translateX(3px);
    }

    .sub-menu .nav-link::before {
        width: 2px;
        left: 38px;
    }

    .sub-menu .nav-item.active .nav-link,
    .sub-menu .nav-link.active {
        color: #7c8cfa;
        background: rgba(102, 126, 234, 0.1);
        font-weight: 600;
    }

    .sub-menu .nav-link.active::before {
        transform: scaleY(1);
    }

    /* Badge Styles */
    .nav-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 6px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 6px;
        margin-left: auto;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
    }

    /* Sidebar Toggler */
    .sidebar-toggler {
        cursor: pointer;
        padding: 5px;
        border-radius: 6px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        display: none;
    }

    .sidebar-toggler:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .sidebar-toggler span {
        display: block;
        width: 18px;
        height: 2px;
        background: white;
        margin: 3px 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 2px;
    }

    /* Collapse Animation */
    .collapse {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Page Wrapper */
    .page-wrapper {
        margin-left: 260px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    /* Mobile Overlay */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(3px);
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.active {
            transform: translateX(0);
            box-shadow: 4px 0 40px rgba(0, 0, 0, 0.5);
        }

        .page-wrapper {
            margin-left: 0 !important;
        }

        .sidebar-toggler {
            display: block;
        }
    }

    /* Improved Spacing for Better Readability */
    .nav-item+.nav-category {
        margin-top: 12px;
    }

    /* Better Visual Hierarchy */
    .nav-link {
        font-weight: 500;
    }

    .nav-item.active>.nav-link {
        font-weight: 600;
    }

    /* Smooth Transitions */
    * {
        -webkit-tap-highlight-color: transparent;
    }

    /* Better Hover States */
    .nav-link:active {
        transform: translateX(2px) scale(0.98);
    }

    /* Improved Icon Colors */
    .icon-wrapper i {
        color: #c5ccd8;
    }

    .nav-link:hover .icon-wrapper i {
        color: #ffffff;
    }

    .nav-item.active>.nav-link .icon-wrapper i {
        color: #ffffff;
    }

    /* Better Submenu Indication */
    .sub-menu .nav-link::after {
        content: '';
        position: absolute;
        left: 32px;
        top: 50%;
        width: 4px;
        height: 4px;
        background: #667eea;
        border-radius: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sub-menu .nav-link.active::after {
        opacity: 1;
    }

    /* Improved Focus States */
    .nav-link:focus {
        outline: 2px solid rgba(102, 126, 234, 0.4);
        outline-offset: 2px;
    }

    /* Better Animation Performance */
    .nav-link,
    .icon-wrapper,
    .link-arrow {
        will-change: transform;
    }
</style>

<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            @if (websiteSetupValue('logo'))
                <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}" alt="Logo">
            @else
                <i data-feather="compass"></i>
                <strong>Visit Kashi</strong>
            @endif
        </a>
        <div class="sidebar-toggler">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <!-- Dashboard -->
            <li class="nav-item @if (Route::currentRouteName() == 'admin.dashboard') active @endif">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <div class="icon-wrapper">
                        <i data-feather="home"></i>
                    </div>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <!-- Daily Activities Section -->
            <li class="nav-item nav-category">Daily Activities</li>

            @canany(['agent-list', 'lead-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'lead.index',
                        'lead.create',
                        'lead.edit',
                        'lead.show',
                        'lead-source.index',
                        'lead-source.edit',
                    ])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageLeads" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), [
                                'lead.index',
                                'lead.create',
                                'lead.edit',
                                'lead.show',
                                'lead-source.index',
                                'lead-source.edit',
                            ])) true @else false @endif"
                        aria-controls="manageLeads">
                        <div class="icon-wrapper">
                            <i data-feather="users"></i>
                        </div>
                        <span class="link-title">Leads & Customers</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), [
                            'lead.index',
                            'lead.create',
                            'lead.edit',
                            'lead.show',
                            'lead-source.index',
                            'lead-source.edit',
                        ])) show @endif" id="manageLeads">
                        <ul class="nav sub-menu">
                            @can('lead-list')
                                <li class="nav-item">
                                    <a href="{{ route('lead.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['lead.index', 'lead.edit', 'lead.show'])) active @endif">
                                        All Leads
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('lead-source.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['lead-source.index', 'lead-source.edit'])) active @endif">Lead Sources</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany(['quotation-list', 'quotation-view', 'quotation-create'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'quotations.index',
                        'quotations.create',
                        'quotations.show',
                        'quotations.edit',
                    ])) active @endif">
                    <a href="{{ route('quotations.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="file-text"></i>
                        </div>
                        <span class="link-title">Quotations</span>
                    </a>
                </li>
            @endcanany

            @canany(['booking-list', 'booking-view', 'booking-create'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'bookings.index',
                        'bookings.show',
                        'bookings.create-direct',
                        'bookings.calendar',
                    ])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageBookings" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), [
                                'bookings.index',
                                'bookings.show',
                                'bookings.create-direct',
                                'bookings.calendar',
                            ])) true @else false @endif"
                        aria-controls="manageBookings">
                        <div class="icon-wrapper">
                            <i data-feather="calendar"></i>
                        </div>
                        <span class="link-title">Bookings</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), [
                            'bookings.index',
                            'bookings.show',
                            'bookings.create-direct',
                            'bookings.calendar',
                        ])) show @endif" id="manageBookings">
                        <ul class="nav sub-menu">
                            @can('booking-create')
                                <li class="nav-item">
                                    <a href="{{ route('bookings.create-direct') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'bookings.create-direct') active @endif">
                                        <i data-feather="plus-circle" style="width: 14px; height: 14px; margin-right: 6px;"></i>
                                        Create Booking
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('bookings.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['bookings.index', 'bookings.show'])) active @endif">
                                    Confirmed Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('bookings.calendar') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'bookings.calendar') active @endif">
                                    Booking Calendar
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcanany

            @can('enquiry-list')
                <li class="nav-item @if (Route::currentRouteName() == 'enquiry.index') active @endif">
                    <a href="{{ route('enquiry.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="message-circle"></i>
                        </div>
                        <span class="link-title">Enquiries</span>
                    </a>
                </li>
            @endcan

            <!-- Dev Diwali Section -->
            <li class="nav-item nav-category">Dev Diwali</li>

            <li class="nav-item @if (in_array(Route::currentRouteName(), [
                    'boat-booking.index',
                    'boat-booking.create',
                    'boat-booking.edit',
                    'boat-booking.payment',
                    'boat-booking.requests',
                ])) active @endif">
                <a class="nav-link" data-bs-toggle="collapse" href="#devDiwaliBookings" role="button"
                    aria-expanded="@if (in_array(Route::currentRouteName(), [
                            'boat-booking.index',
                            'boat-booking.create',
                            'boat-booking.edit',
                            'boat-booking.payment',
                            'boat-booking.requests',
                        ])) true @else false @endif"
                    aria-controls="devDiwaliBookings">
                    <div class="icon-wrapper">
                        <i data-feather="anchor"></i>
                    </div>
                    <span class="link-title">Dev Diwali Boats</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse @if (in_array(Route::currentRouteName(), [
                        'boat-booking.index',
                        'boat-booking.create',
                        'boat-booking.edit',
                        'boat-booking.payment',
                        'boat-booking.requests',
                    ])) show @endif" id="devDiwaliBookings">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('boat-booking.index') }}"
                                class="nav-link @if (in_array(Route::currentRouteName(), [
                                        'boat-booking.index',
                                        'boat-booking.create',
                                        'boat-booking.edit',
                                        'boat-booking.payment',
                                    ])) active @endif">Boat Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('boat-booking.requests') }}"
                                class="nav-link @if (Route::currentRouteName() == 'boat-booking.requests') active @endif">Booking Requests</a>
                        </li>
                    </ul>
                </div>
            </li>

            @canany(['payment-list', 'payment-view', 'payment-account-view'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'payment-accounts.index',
                        'payment-accounts.create',
                        'payment-accounts.show',
                        'payment-accounts.edit',
                    ])) active @endif">
                    <a href="{{ route('payment-accounts.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="credit-card"></i>
                        </div>
                        <span class="link-title">Payment Accounts</span>
                    </a>
                </li>
            @endcanany

            <!-- Analytics & Reports Section -->
            <li class="nav-item nav-category">Analytics & Reports</li>

            @canany(['dashboard-view', 'analytics-customer'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['executive-dashboard.index'])) active @endif">
                    <a href="{{ route('executive-dashboard.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="bar-chart"></i>
                        </div>
                        <span class="link-title">Executive Dashboard</span>
                    </a>
                </li>
            @endcanany

            @canany(['dashboard-view', 'analytics-customer'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['sales-analytics.index'])) active @endif">
                    <a href="{{ route('sales-analytics.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="trending-up"></i>
                        </div>
                        <span class="link-title">Sales Performance</span>
                    </a>
                </li>
            @endcanany

            @canany(['dashboard-view', 'analytics-profit', 'payment-view'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['payment-analytics.index'])) active @endif">
                    <a href="{{ route('payment-analytics.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="dollar-sign"></i>
                        </div>
                        <span class="link-title">Payment & Cash Flow</span>
                    </a>
                </li>
            @endcanany

            @canany(['dashboard-view', 'analytics-customer'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['customer.analytics'])) active @endif">
                    <a href="{{ route('customer.analytics') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="user-check"></i>
                        </div>
                        <span class="link-title">Customer Analytics</span>
                    </a>
                </li>
            @endcanany

            @canany(['dashboard-view', 'analytics-profit'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['profit-analytics.index'])) active @endif">
                    <a href="{{ route('profit-analytics.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="pie-chart"></i>
                        </div>
                        <span class="link-title">Profit Analytics</span>
                    </a>
                </li>
            @endcanany

            @canany(['activity-log-view', 'dashboard-view'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['activity-logs.index'])) active @endif">
                    <a href="{{ route('activity-logs.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="activity"></i>
                        </div>
                        <span class="link-title">Activity Logs</span>
                    </a>
                </li>
            @endcanany

            {{-- Target Management --}}
            @canany(['dashboard-view', 'staff-list'])
                <li class="nav-item @if (Route::currentRouteName() == 'targets.index') active @endif">
                    <a href="{{ route('targets.index') }}" class="nav-link">
                        <div class="icon-wrapper">
                            <i data-feather="target"></i>
                        </div>
                        <span class="link-title">Target Management</span>
                        <span class="nav-badge">NEW</span>
                    </a>
                </li>
            @endcanany

            <!-- Configuration Section -->
            <li class="nav-item nav-category">Configuration</li>

            @canany(['category-list', 'sub_category-list', 'package-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'category.index',
                        'category.edit',
                        'sub-category.index',
                        'sub-category.edit',
                        'product.index',
                        'product.create',
                        'product.edit',
                    ])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageTourPackages" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), [
                                'category.index',
                                'category.edit',
                                'sub-category.index',
                                'sub-category.edit',
                                'product.index',
                                'product.create',
                                'product.edit',
                            ])) true @else false @endif"
                        aria-controls="manageTourPackages">
                        <div class="icon-wrapper">
                            <i data-feather="package"></i>
                        </div>
                        <span class="link-title">Tour Packages</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), [
                            'category.index',
                            'category.edit',
                            'sub-category.index',
                            'sub-category.edit',
                            'product.index',
                            'product.create',
                            'product.edit',
                        ])) show @endif" id="manageTourPackages">
                        <ul class="nav sub-menu">
                            @can('category-list')
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['category.index', 'category.edit'])) active @endif">Categories</a>
                                </li>
                            @endcan
                            @can('sub_category-list')
                                <li class="nav-item">
                                    <a href="{{ route('sub-category.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['sub-category.index', 'sub-category.edit'])) active @endif">Sub Categories</a>
                                </li>
                            @endcan
                            @can('package-list')
                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['product.index', 'product.create', 'product.edit'])) active @endif">All Packages</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany(['service-template-list', 'service-type-list', 'service-item-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'service-types.index',
                        'service-items.index',
                        'service-items.create',
                        'service-items.edit',
                        'service-templates.index',
                        'service-templates.create',
                        'service-templates.edit',
                    ])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#serviceCatalog" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), [
                                'service-types.index',
                                'service-items.index',
                                'service-items.create',
                                'service-items.edit',
                                'service-templates.index',
                                'service-templates.create',
                                'service-templates.edit',
                            ])) true @else false @endif"
                        aria-controls="serviceCatalog">
                        <div class="icon-wrapper">
                            <i data-feather="grid"></i>
                        </div>
                        <span class="link-title">Service Catalog</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), [
                            'service-types.index',
                            'service-items.index',
                            'service-items.create',
                            'service-items.edit',
                            'service-templates.index',
                            'service-templates.create',
                            'service-templates.edit',
                        ])) show @endif" id="serviceCatalog">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('service-types.index') }}"
                                    class="nav-link @if (Route::currentRouteName() == 'service-types.index') active @endif">
                                    Service Types
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('service-templates.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), [
                                            'service-templates.index',
                                            'service-templates.create',
                                            'service-templates.edit',
                                        ])) active @endif">
                                    Service Templates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('service-items.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['service-items.index', 'service-items.create', 'service-items.edit'])) active @endif">
                                    Vendor Service Rates
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany(['service-provider-list', 'vendor-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'service-providers.index',
                        'service-providers.create',
                        'service-providers.edit',
                        'vendor.index',
                        'vendor.create',
                        'vendor.edit',
                        'boat-type.index',
                        'boat-type.edit',
                        'boat.index',
                        'boat.create',
                        'boat.edit',
                    ])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#serviceProviders" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), [
                                'service-providers.index',
                                'service-providers.create',
                                'service-providers.edit',
                                'vendor-settlements.index',
                                'vendor-settlements.show',
                                'vendor.index',
                                'vendor.create',
                                'vendor.edit',
                                'boat-type.index',
                                'boat-type.edit',
                                'boat.index',
                                'boat.create',
                                'boat.edit',
                            ])) true @else false @endif"
                        aria-controls="serviceProviders">
                        <div class="icon-wrapper">
                            <i data-feather="truck"></i>
                        </div>
                        <span class="link-title">Vendors</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), [
                            'service-providers.index',
                            'service-providers.create',
                            'service-providers.edit',
                            'vendor-settlements.index',
                            'vendor-settlements.show',
                            'vendor.index',
                            'vendor.create',
                            'vendor.edit',
                            'boat-type.index',
                            'boat-type.edit',
                            'boat.index',
                            'boat.create',
                            'boat.edit',
                        ])) show @endif" id="serviceProviders">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('service-providers.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), [
                                            'service-providers.index',
                                            'service-providers.create',
                                            'service-providers.edit',
                                        ])) active @endif">
                                    All Providers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor-settlements.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['vendor-settlements.index', 'vendor-settlements.show'])) active @endif">
                                    Vendor Settlements
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany(['agent-list', 'lead-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['agent-service.index', 'agent-service.edit', 'agent.index', 'agent.edit'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageagent" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['agent-service.index', 'agent-service.edit', 'agent.index', 'agent.edit'])) true @else false @endif"
                        aria-controls="manageagent">
                        <div class="icon-wrapper">
                            <i data-feather="briefcase"></i>
                        </div>
                        <span class="link-title">Agents</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['agent-service.index', 'agent-service.edit', 'agent.index', 'agent.edit'])) show @endif" id="manageagent">
                        <ul class="nav sub-menu">
                            @can('lead-list')
                                <li class="nav-item">
                                    <a href="{{ route('agent.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['agent.index', 'agent.edit'])) active @endif">All Agents</a>
                                </li>
                            @endcan
                            @can('agent-list')
                                <li class="nav-item">
                                    <a href="{{ route('agent-service.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['agent-service.index', 'agent-service.edit'])) active @endif">Agent
                                        Commissions</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany(['staff-list', 'role-list'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), ['staffs.index', 'staffs.edit', 'roles.index', 'roles.edit'])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#manageStaff" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), ['staffs.index', 'staffs.edit', 'roles.index', 'roles.edit'])) true @else false @endif"
                        aria-controls="manageStaff">
                        <div class="icon-wrapper">
                            <i data-feather="users"></i>
                        </div>
                        <span class="link-title">Staff Management</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), ['staffs.index', 'staffs.edit', 'roles.index', 'roles.edit'])) show @endif" id="manageStaff">
                        <ul class="nav sub-menu">
                            @can('staff-list')
                                <li class="nav-item">
                                    <a href="{{ route('staffs.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['staffs.index', 'staffs.edit'])) active @endif">All Staff</a>
                                </li>
                            @endcan
                            @can('role-list')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link @if (in_array(Route::currentRouteName(), ['roles.index', 'roles.edit'])) active @endif">Roles &
                                        Permissions</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany(['web_setup'])
                <li class="nav-item @if (in_array(Route::currentRouteName(), [
                        'web_setup.index',
                        'vendor.index',
                        'vendor.create',
                        'vendor.edit',
                        'boat-type.index',
                        'boat-type.edit',
                        'boat.index',
                        'boat.create',
                        'boat.edit',
                    ])) active @endif">
                    <a class="nav-link" data-bs-toggle="collapse" href="#webisiteSetting" role="button"
                        aria-expanded="@if (in_array(Route::currentRouteName(), [
                                'web_setup.index',
                                'vendor.index',
                                'vendor.create',
                                'vendor.edit',
                                'boat-type.index',
                                'boat-type.edit',
                                'boat.index',
                                'boat.create',
                                'boat.edit',
                            ])) true @else false @endif"
                        aria-controls="webisiteSetting">
                        <div class="icon-wrapper">
                            <i data-feather="settings"></i>
                        </div>
                        <span class="link-title">Website Settings</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if (in_array(Route::currentRouteName(), [
                            'web_setup.index',
                            'vendor.index',
                            'vendor.create',
                            'vendor.edit',
                            'boat-type.index',
                            'boat-type.edit',
                            'boat.index',
                            'boat.create',
                            'boat.edit',
                        ])) show @endif" id="webisiteSetting">
                        <ul class="nav sub-menu">
                            @can('web_setup')
                                <li class="nav-item">
                                    <a href="{{ route('web_setup.index') }}"
                                        class="nav-link @if (Route::currentRouteName() == 'web_setup.index') active @endif">General Setup</a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('vendor.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['vendor.index', 'vendor.create', 'vendor.edit'])) active @endif">Legacy Vendors</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('boat-type.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['boat-type.index', 'boat-type.edit'])) active @endif">Boat Types</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('boat.index') }}"
                                    class="nav-link @if (in_array(Route::currentRouteName(), ['boat.index', 'boat.create', 'boat.edit'])) active @endif">Boats</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany
        </ul>
    </div>
</nav>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        const sidebarTogglers = document.querySelectorAll('.sidebar-toggler');

        // Toggle sidebar on mobile
        sidebarTogglers.forEach(toggler => {
            toggler.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' :
                    '';
            });
        });

        // Close sidebar when clicking overlay
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        // Close sidebar when clicking a link on mobile
        if (window.innerWidth <= 991) {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Only close if it's not a parent menu item
                    if (!this.hasAttribute('data-bs-toggle')) {
                        setTimeout(() => {
                            sidebar.classList.remove('active');
                            overlay.classList.remove('active');
                            document.body.style.overflow = '';
                        }, 200);
                    }
                });
            });
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
</script>
