<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | {{ $page_title ?? 'Admin Login' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Core vendor CSS (kept intact) -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/core/core.css') }}">
    @if (session()->has('selected_theme') && session()->get('selected_theme') == 'Dark')
        <link rel="stylesheet" href="{{ asset('backend/assets/css/demo2/style.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('backend/assets/css/demo1/style.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @if (websiteSetupValue('favicon'))
        <link rel="shortcut icon" href="{{ asset('backend/admin/website_setup/' . websiteSetupValue('favicon')) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}">
    @endif

    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand:        #D94F2B;
            --brand-h:      #B83D1F;
            --navy:         #0F1B30;
            --navy-mid:     #162238;
            --navy-lite:    #1E3050;
            --gold:         #F5A623;
            --white:        #FFFFFF;
            --surface:      #FAFAF9;
            --border:       #E5E7EB;
            --text:         #111827;
            --text-mid:     #374151;
            --text-muted:   #6B7280;
            --text-pale:    #9CA3AF;
            --error:        #EF4444;
            --success:      #10B981;
            --ease:         cubic-bezier(0.4, 0, 0.2, 1);
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Layout: Full-height split ── */
        .al-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ════════════════════════════
           LEFT PANEL — Brand identity
           ════════════════════════════ */
        .al-left {
            background: var(--navy);
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 48px 52px;
            overflow: hidden;
        }

        /* Decorative blobs */
        .al-blob {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
            opacity: 0.18;
        }
        .al-blob--1 {
            width: 380px; height: 380px;
            background: var(--brand);
            top: -100px; right: -80px;
        }
        .al-blob--2 {
            width: 280px; height: 280px;
            background: var(--gold);
            bottom: 40px; left: -60px;
        }
        .al-blob--3 {
            width: 200px; height: 200px;
            background: #5B8DEF;
            top: 50%; left: 55%;
            transform: translate(-50%, -50%);
        }

        /* Subtle grid pattern */
        .al-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* Left top: logo + nav link */
        .al-left__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }
        .al-left__logo img {
            height: 38px;
            width: auto;
            filter: brightness(0) invert(1);
            opacity: 0.92;
        }
        .al-left__logo-text {
            font-size: 20px;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.02em;
        }
        .al-left__back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 500;
            color: rgba(255,255,255,0.50);
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 50px;
            transition: all 0.25s var(--ease);
        }
        .al-left__back:hover {
            color: rgba(255,255,255,0.90);
            border-color: rgba(255,255,255,0.30);
            background: rgba(255,255,255,0.06);
        }

        /* Left middle: headline */
        .al-left__body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 2;
            padding: 48px 0 32px;
        }
        .al-left__chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(245,166,35,0.14);
            border: 1px solid rgba(245,166,35,0.35);
            color: var(--gold);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 50px;
            width: fit-content;
            margin-bottom: 24px;
        }
        .al-left__chip span { font-size: 14px; }
        .al-left__headline {
            font-size: clamp(30px, 3.5vw, 44px);
            font-weight: 800;
            color: var(--white);
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
        }
        .al-left__headline em {
            font-style: normal;
            color: var(--brand);
            position: relative;
        }
        .al-left__headline em::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--brand);
            border-radius: 2px;
            opacity: 0.5;
        }
        .al-left__sub {
            font-size: 15px;
            color: rgba(255,255,255,0.52);
            font-weight: 400;
            line-height: 1.65;
            max-width: 380px;
            margin-bottom: 40px;
        }

        /* Stats grid */
        .al-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .al-stat {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 18px 20px;
            transition: background 0.25s var(--ease);
        }
        .al-stat:hover { background: rgba(255,255,255,0.08); }
        .al-stat__num {
            font-size: 26px;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.04em;
            line-height: 1;
            margin-bottom: 4px;
        }
        .al-stat__num sup {
            font-size: 14px;
            font-weight: 600;
            vertical-align: super;
            margin-left: 1px;
        }
        .al-stat__label {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            font-weight: 500;
        }
        .al-stat--accent .al-stat__num { color: var(--gold); }
        .al-stat--brand  .al-stat__num { color: #7DD3FC; }

        /* Left bottom: trust badges */
        .al-left__bottom {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 20px;
            padding-top: 28px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .al-trust {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: rgba(255,255,255,0.40);
            font-weight: 500;
        }
        .al-trust i { color: var(--success); font-size: 12px; }

        /* ════════════════════════════
           RIGHT PANEL — Login form
           ════════════════════════════ */
        .al-right {
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
        }
        .al-right::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand) 0%, var(--gold) 100%);
        }
        .al-form-wrap {
            width: 100%;
            max-width: 400px;
        }

        /* Form header */
        .al-form-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 36px;
        }
        .al-form-logo img {
            max-height: 44px;
            width: auto;
        }
        .al-form-logo-text {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .al-form-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(217,79,43,0.08);
            border: 1px solid rgba(217,79,43,0.20);
            color: var(--brand);
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 50px;
            margin-bottom: 14px;
        }
        .al-form-heading {
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.03em;
            margin-bottom: 6px;
            line-height: 1.2;
        }
        .al-form-sub {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 400;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        /* Field groups */
        .al-field {
            margin-bottom: 20px;
        }
        .al-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }
        .al-field__inner {
            position: relative;
            display: flex;
            align-items: center;
        }
        .al-field__icon {
            position: absolute;
            left: 14px;
            color: var(--text-pale);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.22s var(--ease);
            z-index: 1;
        }
        .al-field__inner:focus-within .al-field__icon {
            color: var(--brand);
        }
        .al-field input {
            width: 100%;
            padding: 12px 42px;
            font-size: 14.5px;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: var(--text);
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            outline: none;
            transition: border-color 0.22s var(--ease), box-shadow 0.22s var(--ease);
            -webkit-appearance: none;
        }
        .al-field input::placeholder { color: var(--text-pale); }
        .al-field input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3.5px rgba(217,79,43,0.10);
        }
        .al-field input.is-invalid {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(239,68,68,0.10);
        }
        .al-field input.is-invalid:focus {
            border-color: var(--error);
            box-shadow: 0 0 0 3.5px rgba(239,68,68,0.12);
        }
        /* Password eye toggle */
        .al-field__toggle {
            position: absolute;
            right: 13px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-pale);
            font-size: 14px;
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color 0.2s var(--ease);
        }
        .al-field__toggle:hover { color: var(--text-mid); }
        /* Validation message */
        .al-field__error {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--error);
            font-weight: 500;
            margin-top: 6px;
        }
        .al-field__error::before {
            content: '\f071';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 10px;
        }

        /* Submit button */
        .al-btn-submit {
            width: 100%;
            padding: 13.5px 24px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--white);
            background: var(--brand);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: 0.01em;
            position: relative;
            overflow: hidden;
            transition: background 0.25s var(--ease), transform 0.18s var(--ease), box-shadow 0.25s var(--ease);
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
        }
        .al-btn-submit:hover {
            background: var(--brand-h);
            transform: translateY(-1.5px);
            box-shadow: 0 8px 28px rgba(217,79,43,0.30);
        }
        .al-btn-submit:active { transform: translateY(0); box-shadow: none; }

        /* Loading spinner (shown via JS) */
        .al-btn-submit .al-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: var(--white);
            border-radius: 50%;
            animation: al-spin 0.7s linear infinite;
        }
        .al-btn-submit.al-loading .al-btn-text { display: none; }
        .al-btn-submit.al-loading .al-spinner { display: block; }
        @keyframes al-spin {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .al-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0 20px;
        }
        .al-divider::before,
        .al-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .al-divider span {
            font-size: 11.5px;
            color: var(--text-pale);
            font-weight: 500;
            white-space: nowrap;
        }

        /* Footer note */
        .al-form-note {
            text-align: center;
            font-size: 12px;
            color: var(--text-pale);
            margin-top: 28px;
            line-height: 1.6;
        }
        .al-form-note a {
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
        }
        .al-form-note a:hover { text-decoration: underline; }

        /* ── Fade-in animation ── */
        .al-fade {
            animation: alFadeUp 0.55s var(--ease) both;
        }
        .al-fade--1 { animation-delay: 0.06s; }
        .al-fade--2 { animation-delay: 0.12s; }
        .al-fade--3 { animation-delay: 0.18s; }
        .al-fade--4 { animation-delay: 0.24s; }
        .al-fade--5 { animation-delay: 0.30s; }
        @keyframes alFadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .al-wrap { grid-template-columns: 1fr; }
            .al-left  { display: none; }
            .al-right { min-height: 100vh; padding: 40px 24px; background: var(--white); }
            .al-right::before { height: 4px; }
        }
        @media (max-width: 480px) {
            .al-right  { padding: 32px 20px; }
            .al-form-wrap { max-width: 100%; }
            .al-form-heading { font-size: 22px; }
        }
    </style>
</head>

<body>
<div class="al-wrap">

    {{-- ══════════════════════════════════════
         LEFT PANEL — Brand + Stats
         ══════════════════════════════════════ --}}
    <div class="al-left">
        {{-- Decorative blobs --}}
        <div class="al-blob al-blob--1" aria-hidden="true"></div>
        <div class="al-blob al-blob--2" aria-hidden="true"></div>
        <div class="al-blob al-blob--3" aria-hidden="true"></div>

        {{-- Top bar --}}
        <div class="al-left__top">
            <div class="al-left__logo">
                @if(websiteSetupValue('logo'))
                    <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                         alt="{{ config('app.name') }}">
                @else
                    <span class="al-left__logo-text">{{ config('app.name') }}</span>
                @endif
            </div>
            <a href="{{ route('index') }}" class="al-left__back">
                <i class="fas fa-arrow-left"></i> Website
            </a>
        </div>

        {{-- Middle body --}}
        <div class="al-left__body">
            <div class="al-left__chip">
                <span>⚙️</span> Admin Control Panel
            </div>
            <h2 class="al-left__headline">
                Manage your<br>
                <em>Kashi business</em><br>
                with clarity.
            </h2>
            <p class="al-left__sub">
                Bookings, analytics, services, and team management — all from one powerful dashboard built for Varanasi's #1 travel platform.
            </p>

            {{-- Stats --}}
            <div class="al-stats">
                <div class="al-stat al-stat--accent">
                    <div class="al-stat__num">5K<sup>+</sup></div>
                    <div class="al-stat__label">Happy Travelers</div>
                </div>
                <div class="al-stat">
                    <div class="al-stat__num">500<sup>+</sup></div>
                    <div class="al-stat__label">Bookings / Month</div>
                </div>
                <div class="al-stat al-stat--brand">
                    <div class="al-stat__num">98<sup>%</sup></div>
                    <div class="al-stat__label">Satisfaction Rate</div>
                </div>
                <div class="al-stat">
                    <div class="al-stat__num">24<sup>/7</sup></div>
                    <div class="al-stat__label">Live Support</div>
                </div>
            </div>
        </div>

        {{-- Bottom trust strip --}}
        <div class="al-left__bottom">
            <div class="al-trust">
                <i class="fas fa-shield-alt"></i> Secure Login
            </div>
            <div class="al-trust">
                <i class="fas fa-lock"></i> Encrypted
            </div>
            <div class="al-trust">
                <i class="fas fa-check-circle"></i> Verified Access
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         RIGHT PANEL — Login form
         All form fields, CSRF, validation preserved
         ══════════════════════════════════════ --}}
    <div class="al-right">
        <div class="al-form-wrap">

            {{-- Logo on mobile / right panel --}}
            <div class="al-form-logo al-fade">
                @if(websiteSetupValue('logo'))
                    <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                         alt="{{ config('app.name') }}">
                @else
                    <span class="al-form-logo-text">{{ config('app.name') }}</span>
                @endif
            </div>

            <div class="al-fade al-fade--1">
                <div class="al-form-tag">
                    <i class="fas fa-user-shield"></i> Admin Portal
                </div>
                <h1 class="al-form-heading">Welcome back</h1>
                <p class="al-form-sub">Sign in to your admin dashboard to manage bookings, services &amp; analytics.</p>
            </div>

            {{-- ── Login Form
                 Preserved: action, @csrf, name attrs, @error, old(), autofocus
            ── --}}
            <form method="POST"
                  action="{{ route('admin.login.submit') }}"
                  id="al-login-form"
                  novalidate>
                @csrf

                {{-- Email field --}}
                <div class="al-field al-fade al-fade--2">
                    <label for="email">Email Address</label>
                    <div class="al-field__inner">
                        <i class="fas fa-envelope al-field__icon"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="admin@visitkashi.in"
                               required
                               autofocus
                               autocomplete="email"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </div>
                    @error('email')
                        <div class="al-field__error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password field --}}
                <div class="al-field al-fade al-fade--3">
                    <label for="password">Password</label>
                    <div class="al-field__inner">
                        <i class="fas fa-lock al-field__icon"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Enter your password"
                               required
                               autocomplete="current-password"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <button type="button"
                                class="al-field__toggle"
                                id="al-toggle-pw"
                                aria-label="Toggle password visibility"
                                tabindex="-1">
                            <i class="fas fa-eye" id="al-eye-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="al-field__error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="al-fade al-fade--4">
                    <button type="submit" class="al-btn-submit" id="al-submit-btn">
                        <span class="al-btn-text">
                            <i class="fas fa-sign-in-alt"></i>&nbsp; Sign In to Dashboard
                        </span>
                        <span class="al-spinner" aria-hidden="true"></span>
                    </button>
                </div>

            </form>

            {{-- Footer note --}}
            <p class="al-form-note al-fade al-fade--5">
                Having trouble logging in?<br>
                Contact your system administrator or&nbsp;<a href="mailto:{{ websiteSetupValue('email') }}">{{ websiteSetupValue('email') }}</a>
            </p>

        </div>
    </div>

</div>

{{-- Vendor JS (kept intact) --}}
<script src="{{ asset('backend/assets/vendors/core/core.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
(function () {
    /* ── SweetAlert2 session toasts (preserved exactly) ── */
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true
    });

    $(document).ready(function () {
        var successMsg = "{{ Session::get('success') }}";
        var errorMsg   = "{{ Session::get('error') }}";
        if (successMsg) Toast.fire({ icon: 'success', title: successMsg });
        if (errorMsg)   Toast.fire({ icon: 'error',   title: errorMsg   });
    });

    /* ── Password visibility toggle (preserved + improved) ── */
    document.getElementById('al-toggle-pw').addEventListener('click', function () {
        var pw  = document.getElementById('password');
        var ico = document.getElementById('al-eye-icon');
        var showing = pw.type === 'text';
        pw.type = showing ? 'password' : 'text';
        ico.classList.toggle('fa-eye',       showing);
        ico.classList.toggle('fa-eye-slash', !showing);
        this.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
    });

    /* ── Submit loading state ── */
    document.getElementById('al-login-form').addEventListener('submit', function () {
        var btn = document.getElementById('al-submit-btn');
        btn.classList.add('al-loading');
        btn.disabled = true;
        /* re-enable after 8s as a safety fallback */
        setTimeout(function () {
            btn.classList.remove('al-loading');
            btn.disabled = false;
        }, 8000);
    });
})();
</script>
</body>
</html>
