<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zxx">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @yield('meta')

        <link rel="shortcut icon" type="image/x-icon" href="{{asset('backend/admin/website_setup/'.websiteSetupValue('favicon'))}}" />
        <link href="{{asset('frontend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('frontend/css/style.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('frontend/font/flaticon.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('frontend/css/plugin.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

        <!-- Start Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-QQ2QQ2EB99"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-QQ2QQ2EB99');
        </script>
        <!-- End Google tag (gtag.js) -->
    </head>

    <body>
        @include('frontend.layouts.header')

        @yield('content')

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
                    {!!websiteSetupValue('notice')!!}
                </div>
            </div>
        </div>
        </div>
    </div>

  <!--- modal script is here ---->
<script type="text/javascript">
@if(websiteSetupValue('notice'))
 $(document).ready(function(){
             $("#staticBackdrop").modal('show');
         },5000);
@endif
</script>

        <script src="{{asset('frontend/js/jquery-3.2.1.min.js')}}"></script>
        <script src="{{asset('frontend/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('frontend/js/plugin.js')}}"></script>
        <script src="{{asset('frontend/js/main.js')}}"></script>
        <script src="{{asset('frontend/js/main-1.js')}}"></script>
        <script src="{{asset('frontend/js/preloader.js')}}"></script>
        <script src="{{asset('frontend/js/custom-swiper2.js')}}"></script>
        <script src="{{asset('frontend/js/custom-countdown.js')}}"></script>
    </body>

</html>
