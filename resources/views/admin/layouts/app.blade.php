<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <title>{{ config('app.name') }} | {{ isset($page_title) ? $page_title : '' }}</title>

    <!-- Home screen / app icon (for "Add to Home screen" on mobile) -->
    <link rel="manifest" href="{{ route('admin.manifest') }}">
    <meta name="theme-color" content="#0F172A">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ websiteSetupValue('site_name') ?: 'Visit Kashi' }}">
    @php
        $appIconSource = websiteSetupValue('app_logo') ?: websiteSetupValue('logo');
        $appIcon = $appIconSource ? asset('backend/admin/website_setup/' . $appIconSource) : asset('favicon.ico');
    @endphp
    <link rel="icon" href="{{ $appIcon }}">
    <link rel="apple-touch-icon" href="{{ $appIcon }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap"
        rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/core/core.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/jquery-tags-input/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.min.css') }}">

    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Layout styles -->

    @if (session()->has('selected_theme') && session()->get('selected_theme') == 'Dark')
        <link rel="stylesheet" href="{{ asset('backend/assets/css/demo2/style.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('backend/assets/css/demo1/style.min.css') }}">
    @endif

    <!-- End layout styles -->

    @if (websiteSetupValue('favicon'))
        <link rel="shortcut icon" href="{{ asset('backend/admin/website_setup/' . websiteSetupValue('favicon')) }}" />
    @else
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/easymde/easymde.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/dropzone/dropzone.min.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="{{ asset('backend/assets/vendors/dropzone/dropzone.min.js') }}"></script>
    <style>
        .ck-editor__editable {
            min-height: 200px;
            color: #0F172A !important;
            background: #fff !important;
        }
        /* Remove number input spinner arrows globally */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>

    <!-- SaaS Admin Design System -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/admin-saas.css') }}">

    @stack('styles')

</head>

<body class="{{ (session('selected_theme') === 'Dark') ? 'dark-mode' : '' }}">
    <div class="main-wrapper">

        @include('admin.layouts.sidebar')

        <div class="page-wrapper">

            @include('admin.layouts.navbar')

            @yield('content')

        </div>

    </div>
    {{-- <script>
            ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .then( editor => {
                // console.log( editor );
            } )
            .catch( error => {
                // console.error( error );
            } );
        </script> --}}
    {{-- Mobile Bottom Navigation (visible ≤991px) --}}
    <nav class="sa-mob-nav" aria-label="Mobile navigation">
        <a href="{{ route('admin.dashboard') }}"
           class="sa-mob-nav__item @if(Route::currentRouteName() == 'admin.dashboard') active @endif">
            <i data-feather="home"></i>
            <span>Home</span>
        </a>
        @canany(['lead-list','agent-list'])
        <a href="{{ route('lead.index') }}"
           class="sa-mob-nav__item @if(in_array(Route::currentRouteName(), ['lead.index','lead.show','lead.edit'])) active @endif">
            <i data-feather="users"></i>
            <span>Leads</span>
        </a>
        @endcanany
        @can('booking-create')
        <a href="{{ route('bookings.create-direct') }}" class="sa-mob-nav__fab" aria-label="New Booking">
            <i data-feather="plus"></i>
        </a>
        @endcan
        @canany(['booking-list','booking-view'])
        <a href="{{ route('bookings.index') }}"
           class="sa-mob-nav__item @if(in_array(Route::currentRouteName(), ['bookings.index','bookings.show'])) active @endif">
            <i data-feather="calendar"></i>
            <span>Bookings</span>
        </a>
        @endcanany
        <a href="{{ route('change.password') }}" class="sa-mob-nav__item @if(Route::currentRouteName() == 'change.password') active @endif">
            <i data-feather="user"></i>
            <span>Profile</span>
        </a>
    </nav>

    @include('admin.layouts.footer')

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '?',
                text: 'All related data to this will be deleted.',
                showCancelButton: true,
                confirmButtonColor: '#fd625e',
                cancelButtonColor: 'secondary',
                confirmButtonText: 'Yes, Go Ahead!'
            }).then((result) => {
                if (result.value) {
                    $('#delete_form_' + id).submit()
                }
            })
            return false;
        }
    </script>

    <script>
    /* Prevent mouse-wheel from changing number input values while scrolling the page */
    document.addEventListener('wheel', function () {
        if (document.activeElement && document.activeElement.type === 'number') {
            document.activeElement.blur();
        }
    }, { passive: true });
    </script>
</body>

</html>
