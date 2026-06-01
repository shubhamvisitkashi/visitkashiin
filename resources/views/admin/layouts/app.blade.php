<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <title>{{ config('app.name') }} | {{ isset($page_title) ? $page_title : '' }}</title>
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

            <footer
                class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
                <p class="text-muted mb-1 mb-md-0">Copyright © {{ date('Y') }} <a
                        href="https://www.techuptechnologies.com/" target="_blank">Techup Technologies</a>.</p>
            </footer>

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
        <a href="#" class="sa-mob-nav__item" onclick="event.preventDefault(); document.querySelector('.sidebar').classList.toggle('active'); document.querySelector('.sidebar-overlay').classList.toggle('active');">
            <i data-feather="grid"></i>
            <span>Menu</span>
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
</body>

</html>
