@extends('frontend.layouts.app')
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
@section('content')
<section id="home_banner_video" class="banner-style-1 p-0">
    <div class="video-banner">
    <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('banner')) }}" alt="image">
    </div>
    <div class="video-banner-content">
    <div class="js_frm_subscribe">
    <div class="kenburns_061_slide slider-content">
        <h2>{{ websiteSetupValue('banner_title') }}</h2>
        <h1>{{ websiteSetupValue('banner_description') }}</h1>
    </div>
    </div>
    </div>
    </section>

    <div class="search-box clearfix">
        <div class="container">
            <div class="search-outer">
                <div class="search-content">
                    <div class="align-self-center ec-header-search">
                        <div class="header-search">
                            <form class="ec-search-group-form" action="#" method="GET" autocomplete="off">
                                <input class="form-control flip" name="search" value="" placeholder="I’m searching for..." type="text" id="flip" onkeyup="seacrh()" onclick="openSearchDiv()">
                                <button class="search_submit" type="button">
                                    <img src="{{asset('frontend/images/search.png')}}" class="svg_img search_svg" alt="" />
                                </button>
                            </form>
                            <div class="TopSearchesBox searchRes" style="display:none">
                                @include('frontend.search')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($on_home_categories) != 0)
        @foreach ($on_home_categories as $on_home_category)
            <section class="deals-on-sale mrt">
                <div class="container">
                    <div class="section-title d-flex">
                        <h2>{{ $on_home_category->meta_title }}</h2><a class="btn-blue btn-red ml-auto wd100"
                            href="{{ route('product.list', $on_home_category->slug) }}">All <i class="fa fa-angle-right"
                                aria-hidden="true"></i></a>
                    </div>
                    <div class="row sale-slider slider-button">
                        @foreach ($on_home_category->product as $on_home_category_product)
                            <div class="col-lg-12">
                                <div class="package-item">
                                    <div class="package-image"><img
                                            src="{{ asset('backend/admin/product_images/' . $on_home_category_product->images[0]) }}"
                                            alt="Image"></div>
                                    <div class="package-content">
                                        <a
                                            @if ($on_home_category_product->subCategory) href="{{ route('product.detail', [$on_home_category_product->category->slug, $on_home_category_product->subCategory->slug, $on_home_category_product->slug]) }}"
                                            @else
                                                href="{{ route('product.detail', [$on_home_category_product->category->slug, 'varanasi', $on_home_category_product->slug]) }}" @endif>
                                            <h3>{{ $on_home_category_product->name }}</h3>
                                            @if($on_home_category->show_price == '1')
                                                <p>from <b><span style="font-family: system-ui;padding-right: 2px;">₹</span>{{ number_format($on_home_category_product->discounted_price) }}</b>/- @if($on_home_category_product->category->slug == 'hotels' || $on_home_category_product->category->slug == 'homestay')Night @endif</p>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    @if (count($on_home_products) != 0)
        <section class="popular-packages">
            <div class="container">
                <div class="section-title d-flex">
                    <h2>Popular Packages</h2><a class="btn-blue btn-red ml-auto" href="{{route('product.list','packages')}}">All <i
                            class="fa fa-angle-right" aria-hidden="true"></i></a>
                </div>
                <div class="row package-slider slider-button">
                    @foreach ($on_home_products as $on_home_product)
                        <div class="col-lg-4">
                            <a
                                @if ($on_home_product->subCategory) href="{{ route('product.detail', [$on_home_product->category->slug, $on_home_product->subCategory->slug, $on_home_product->slug]) }}"
                                @else
                                    href="{{ route('product.detail', [$on_home_product->category->slug, 'varanasi', $on_home_product->slug]) }}" @endif>
                                <div class="package-item">
                                    <div class="package-image">
                                        <img src="{{ asset('backend/admin/product_images/' . $on_home_product->images[0]) }}"
                                            alt="Image" />
                                    </div>
                                    <div class="package-content">
                                        <h3>{{ $on_home_product->name }}</h3>
                                        <p>from <b><span style="font-family: system-ui;padding-right: 2px;">₹</span>{{ number_format($on_home_product->discounted_price) }}</b>/-</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- <section class="amazing-tours">
        <div class="container">
            <div class="section-title d-flex">
                <h2>Popular Packages</h2><a class="btn-blue btn-red ml-auto" href="#">All <i
                        class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div>
            <div class="row">
                <div class="col-lg-6 col-xs-6">
                    <div class="at-item box-item">
                        <div class="at-image">
                            <img src="{{ asset('frontend/images/at2.jpg') }}" alt="Image" />
                            <div class="at-overlay"></div>
                        </div>
                        <div class="at-content">
                            <h3><a href="#">Italy</a></h3>
                            <span>The colosseum</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-xs-6">
                    <div class="at-item box-item">
                        <div class="at-image">
                            <img src="{{ asset('frontend/images/at1.jpg') }}" alt="Image" />
                            <div class="at-overlay"></div>
                        </div>
                        <div class="at-content">
                            <h3><a href="#">Brazil</a></h3>
                            <span>The colosseum</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-xs-6">
                    <div class="at-item box-item">
                        <div class="at-image">
                            <img src="{{ asset('frontend/images/at3.jpg') }}" alt="Image" />
                            <div class="at-overlay"></div>
                        </div>
                        <div class="at-content">
                            <h3><a href="#">Venezuela</a></h3>
                            <span>The colosseum</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-xs-6">
                    <div class="at-item box-item">
                        <div class="at-image">
                            <img src="{{ asset('frontend/images/at1.jpg') }}" alt="Image" />
                            <div class="at-overlay"></div>
                        </div>
                        <div class="at-content">
                            <h3><a href="#">Kenya</a></h3>
                            <span>The colosseum</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-xs-6">
                    <div class="at-item box-item">
                        <div class="at-image">
                            <img src="{{ asset('frontend/images/at3.jpg') }}" alt="Image" />
                            <div class="at-overlay"></div>
                        </div>
                        <div class="at-content">
                            <h3><a href="#">Greece</a></h3>
                            <span>The colosseum</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6">
                    <div class="at-item box-item">
                        <div class="at-image">
                            <img src="{{ asset('frontend/images/at2.jpg') }}" alt="Image" />
                            <div class="at-overlay"></div>
                        </div>
                        <div class="at-content">
                            <h3><a href="#">Iceland</a></h3>
                            <span>The colosseum</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <script>

        function openSearchDiv() {
            $('.TopSearchesBox').slideToggle()
        }

        function seacrh()
        {
            $('.TopSearchesBox').slideDown()
            var search_key = $('#flip').val();
            $.get("{{route('product.search')}}?search_key="+search_key,function(data){
                $('.TopSearchesBox').html(data);
            });
        }

    </script>


@endsection
