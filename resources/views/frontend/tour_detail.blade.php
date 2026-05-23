@extends('frontend.layouts.app')

@section('meta')
    @isset($product)
        <link rel="canonical" href="{{ url()->full() }}">
        <title>{{ optional($product)->meta_title }} - {{ env('APP_DOMAIN') }}</title>
        <meta name="description" content="{{ optional($product)->meta_description }}">
        <meta name="keywords" content="{{ optional($product)->meta_keyword }}">
        <meta property="og:title" content="{{ optional($product)->meta_title }}">
        <meta property="og:site_name" content="{{ env('APP_URL') }}">
        <meta property="og:description" content="{{ optional($product)->meta_description }}">
        <meta property="og:keywords" content="{{ optional($product)->meta_keyword }}">
    @endisset
@endsection

@section('content')
    <section class="breadcrumb-outer text-center">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>{{ optional($product)->meta_title ?? (optional($product)->name ?? 'Product Details') }}</h2>
                <nav aria-label="breadcrumb">
                    {{-- <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Destinations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">EBC Trek</li>
                </ul> --}}
                </nav>
            </div>
        </div>
        <div class="section-overlay"></div>
    </section>

    <section class="main-content detail">
        <div class="container">
            <div class="row">
                <div id="content" class="col-lg-8">
                    <div class="detail-content content-wrapper">
                        {{-- <div class="detail-info">
                        <div class="detail-info-content clearfix">
                            @if ($product->discounted_price != 0)
                                <p class="detail-info-price"><span class="bold">₹ {{$product->discounted_price}}</span></p>
                            @endif
                            <div class="deal-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star-o"></span>
                                <span class="fa fa-star-o"></span>
                            </div>
                        </div>
                    </div> --}}
                        <div class="gallery detail-box">

                            <div id="in_th_030"
                                class="carousel slide in_th_brdr_img_030 thumb_scroll_x swipe_x ps_easeOutQuint"
                                data-bs-ride="carousel" data-pause="hover" data-interval="4000" data-duration="2000">
                                <div class="sale-tag">
                                    @if (optional($product)->discounted_price != 0)
                                        <span class="old-price">₹ <del>{{ optional($product)->base_price }}</del></span>
                                        <span class="new-price"> {{ optional($product)->discounted_price }}/-*</span>
                                    @endif
                                </div>
                                <ol class="carousel-indicators">
                                    @if (!empty($product->images) && is_array($product->images))
                                        @foreach ($product->images as $key => $image)
                                            <li data-bs-target="#in_th_030" data-bs-slide-to="{{ $key }}"
                                                @if ($key == 0) class="active" @endif>
                                                <img src="{{ asset('backend/admin/product_images/' . $image) }}"
                                                    alt="in_th_030_01_sm" />
                                            </li>
                                        @endforeach
                                    @endif

                                </ol>


                                <div class="carousel-inner" role="listbox">
                                    @if (!empty($product->images) && is_array($product->images))
                                        @foreach ($product->images as $key => $image)
                                            <div class="carousel-item item @if ($key == 0) active @endif">
                                                <img src="{{ asset('backend/admin/product_images/' . $image) }}"
                                                    alt="in_th_030_01" />
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item item active">
                                            <img src="{{ asset('backend/assets/images/placeholder.jpg') }}"
                                                alt="No image available" />
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>
                        <div class="description detail-box">
                            <div class="detail-title">
                                <h3>{{ $product->name }}</h3>
                                @if ($product->address)
                                    <p><i class="fa fa-map-marker"></i> {{ $product->address }}</p>
                                @endif
                            </div>
                        </div>
                        @if ($product->youtube_link)
                            <div class="comments-form detail-box">
                                <div class="detail-title">
                                    <h3>For More Details Watch this Video</h3>
                                </div>
                                <iframe src="{{ $product->youtube_link }}" frameborder="1" width="100%"
                                    height="500px"></iframe>
                            </div>
                        @endif
                        <div class="description detail-box">
                            <div class="description-content">
                                {!! $product->description !!}
                            </div>
                        </div>
                        @if ($product->map_location)
                            <div class="location-map detail-box">
                                <div class="detail-title">
                                    <h3>Location Map</h3>
                                </div>
                                <div class="map-frame">
                                    <iframe src="{{ $product->map_location }}" style="border: 0" allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                        {{-- <div class="detail-timeline detail-box">
                        <div class="detail-title">
                            <h3>Tour Timeline</h3>
                        </div>
                        <div class="timeline-content">
                            <ul class="timeline">

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>1</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 1 - 2 : Flights to Kathmandu.</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Passenger flights to Lukla. Begin the trek through the Khumbu to
                                                Base Camp.Tourist attractions people foreign sleep overnight
                                                housing. Gerimrany group discount tour operator. Airplane
                                                couchsurfing Moi scow ma ps uncharted luxury train guest tour
                                                operator
                                                German y busre laxation. Paris overnight Japan Tripit territory
                                                international carren tal Pacific outdoor Turkey. Country
                                                international to urist attractions mil es train Moscow guide. Japan
                                                horse riding money Bacel ona Buda pest yach.
                                            </p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>3</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 3 : Arrive Kathmandu</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Arrive in Kathmandu and relax while enjoying the color and energy of
                                                Nepal’s capital city. Duffels of personal climbing gear and
                                                high-altitude clothing will be collected for the cargo flights to
                                                Lukla and will be waiting for you at Base Camp.
                                            </p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>4</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 4 - 5 : Enjoy Kathmandu</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Enjoy Kathmandu with a city tour and attend any governmental and
                                                media affairs involving team members.Tourist attractions people
                                                foreign sleep overnight housing. Gerimrany group discount tour
                                                operator. Airplane couchsurfing Moi scow ma ps uncharted luxury
                                                train
                                                guest tour operator German y busre laxation. Paris overnight Japan
                                                Tripit territory international carren tal Pacific outdoor Turkey.
                                                Country international to urist attractions mil es train Moscow
                                                guide. Japan horse riding money Bacel ona Buda pest yach.
                                            </p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>6</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 6 : Fly to Lukla</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Passenger flights to Lukla. Begin the trek through the Khumbu to
                                                Base Camp.Tourist attractions people foreign sleep overnight
                                                housing. Gerimrany group discount tour operator. Airplane
                                                couchsurfing Moi scow ma ps uncharted luxury train guest tour
                                                operator
                                                German y busre laxation. Paris overnight Japan Tripit territory
                                                international carren tal Pacific outdoor Turkey. Country
                                                international to urist attractions mil es train Moscow guide. Japan
                                                horse riding money Bacel ona Buda pest yach.
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="direction-r">
                                        <div class="day-wrapper">
                                            <span>7</span>
                                        </div>
                                        <div class="flag-wrapper">
                                            <span class="flag">Day 7 - 15 : Trek to Base Camp</span>
                                        </div>
                                        <div class="desc">
                                            <p>
                                                Trek to Base Camp, taking plenty of time to acclimatize and to visit
                                                the Sherpa families and support facilities that will become
                                                increasingly important during our expedition. We will spend several
                                                days in Namche ahead of most trekkers, and will visit the
                                                monasteries in Tengboche and Pangboche. Additional acclimatization
                                                days are scheduled at Namche (11,400ft/3,475m) and Pheriche
                                                (14,000ft/4,267m).
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                        {{-- <div class="top-attractions detail-box">
                        <div class="detail-title">
                            <h3>Hotels and Availabilities</h3>
                        </div>
                        <div class="top-attraction-content">
                            <div class="att-item clearfix">
                                <div class="att-image">
                                    <img src="{{asset('frontend/images/bucket1.jpg')}}" alt="Images" />
                                </div>
                                <div class="att-content">
                                    <div class="att-content-left">
                                        <h4>Phulay Bay Resort</h4>
                                        <ul>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Wifi</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Parking</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Swimming Pool</li>
                                        </ul>
                                    </div>
                                    <div class="att-content-right">
                                        <p>Starting from <span class="bold">Rs. 1500</span></p>
                                        <p>1 night / 3 person</p>
                                    </div>
                                </div>
                            </div>
                            <div class="att-item clearfix">
                                <div class="att-image">
                                    <img src="{{asset('frontend/images/bucket2.jpg')}}" alt="Images" />
                                </div>
                                <div class="att-content">
                                    <div class="att-content-left">
                                        <h4>Phulay Bay Resort</h4>
                                        <ul>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Wifi</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Parking</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Swimming Pool</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Daily Housekeeping
                                            </li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Restaurant Bar and
                                                Lounge</li>
                                        </ul>
                                    </div>
                                    <div class="att-content-right">
                                        <p>Starting from <span class="bold">Rs. 1500</span></p>
                                        <p>1 night / 3 person</p>
                                    </div>
                                </div>
                            </div>
                            <div class="att-item clearfix">
                                <div class="att-image">
                                    <img src="{{asset('frontend/images/bucket3.jpg')}}" alt="Images" />
                                </div>
                                <div class="att-content">
                                    <div class="att-content-left">
                                        <h4>Phulay Bay Resort</h4>
                                        <ul>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Wifi</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Free Parking</li>
                                            <li><i class="fa fa-check" aria-hidden="true"></i> Swimming Pool</li>
                                        </ul>
                                    </div>
                                    <div class="att-content-right">
                                        <p>Starting from <span class="bold">Rs. 1500</span></p>
                                        <p>1 night / 3 person</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        {{-- <div class="comments detail-box">
                        <div class="detail-title">
                            <h3>Comments</h3>
                        </div>
                        <div class="comment-content">
                            <div class="comment-item">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4">
                                        <div class="comment-image">
                                            <img src="{{asset('frontend/images/comment.jpg')}}" alt="Images" />
                                            <h4><a href="#">Peter Parker</a></h4>
                                            <span class="comment-date">(18 Dec 2018)</span>
                                            <a class="btn-blue btn-red" href="#">Reply</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-8">
                                        <div class="comment-desc">
                                            <span class="travel-date"> Travelled On : 25 March 2018</span>
                                            <div class="deal-rating">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                            </div>
                                            <p>
                                                Trek to Base Camp, taking plenty of time to acclimatize and to visit
                                                the Sherpa families and support facilities that will become
                                                increasingly important during our expedition. We will spend several
                                                days in Namche ahead of most trekkers, and will visit the
                                                monasteries in Tengboche and Pangboche.
                                            </p>
                                        </div>
                                        <div class="comment-item comment-reply">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="comment-image">
                                                        <img src="{{asset('frontend/images/comment.jpg')}}" alt="Images" />
                                                        <h4><a href="#">Peter Parker</a></h4>
                                                        <span class="comment-date">(18 Dec 2018)</span>
                                                        <a class="btn-blue btn-red" href="#">Reply</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-8">
                                                    <div class="comment-desc">
                                                        <span class="travel-date"> Travelled On : 25 March
                                                            2018</span>
                                                        <div class="deal-rating">
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star-o"></span>
                                                            <span class="fa fa-star-o"></span>
                                                        </div>
                                                        <p>
                                                            Trek to Base Camp, taking plenty of time to acclimatize
                                                            and to visit the Sherpa families and support facilities
                                                            that will
                                                            become increasingly important during our expedition. We
                                                            will spend several days in Namche ahead of most
                                                            trekkers, and will
                                                            visit the monasteries in Tengboche and Pangboche.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment-item comment-reply">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="comment-image">
                                                        <img src="{{asset('frontend/images/comment.jpg')}}" alt="Images" />
                                                        <h4><a href="#">Peter Parker</a></h4>
                                                        <span class="comment-date">(18 Dec 2018)</span>
                                                        <a class="btn-blue btn-red" href="#">Reply</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-8">
                                                    <div class="comment-desc">
                                                        <p>
                                                            Trek to Base Camp, taking plenty of time to acclimatize
                                                            and to visit the Sherpa families and support facilities
                                                            that will
                                                            become increasingly important during our expedition. We
                                                            will spend several days in Namche ahead of most
                                                            trekkers, and will
                                                            visit the monasteries in Tengboche and Pangboche.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    </div>
                </div>
                <div id="sidebar-sticky" class="col-lg-4">
                    <aside class="detail-sidebar sidebar-wrapper">
                        <div class="sidebar-item sidebar-item-dark">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif

                            <div class="detail-title">
                                <h3>Book {{ $product->name }}</h3>
                            </div>
                            <form
                                action="{{ route('enquiry.store') }}?package_id={{ $product->id }}&package_name={{ $product->name }}"
                                method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group mb-3 col-lg-12">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Name" required>
                                    </div>
                                    <div class="form-group mb-3 col-lg-6 col-md-6">
                                        <input type="number" class="form-control" id="phone" name="phone"
                                            placeholder="Contact Number"
                                            onkeypress="if(this.value.length==10) return false;" required>
                                    </div>
                                    @if (optional($product->category)->slug == 'hotels' || optional($product->category)->slug == 'homestay')
                                        <div class="form-group mb-3 col-lg-6 col-md-6">
                                            <input type="text" class="form-control" id="no_of_person" name="no_of_person"
                                                placeholder="Adult + Kids" required>
                                        </div>
                                        <div class="form-group mb-3 col-lg-6 col-md-6">
                                            <label style="color:#ffffff">Check-In</label>
                                            <input type="datetime-local" class="form-control" id="checkin_time"
                                                name="checkin_time" min="{{ date('Y-m-d h:i') }}" required>
                                        </div>
                                        <div class="form-group mb-3 col-lg-6 col-md-6">
                                            <label style="color:#ffffff">Check-Out</label>
                                            <input type="datetime-local" class="form-control" id="checkout_time"
                                                name="checkout_time" min="{{ date('Y-m-d h:i') }}" required>
                                        </div>
                                    @else
                                        <div class="form-group mb-3 col-lg-6 col-md-6">
                                            <input type="datetime-local" class="form-control" id="arrival_date"
                                                name="arrival_date" min="{{ date('Y-m-d h:i') }}" required>
                                        </div>
                                    @endif
                                    <div class="textarea mb-3 col-lg-12">
                                        <textarea placeholder="Message" name="message"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="comment-btn mt-0">
                                            <button class="btn-blue btn-red">Request Call back</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- <div class="sidebar-item">
                        <div class="detail-title">
                            <h3>Popular Packages</h3>
                        </div>
                        <div class="sidebar-content sidebar-slider">
                            <div class="sidebar-package">
                                <div class="sidebar-package-image">
                                    <img src="{{asset('frontend/images/detailslider1.jpg')}}" alt="Images" />
                                </div>
                                <div class="destination-content sidebar-package-content">
                                    <h4><a href="#">Royal Caribbean Cruises</a></h4>
                                    <div class="deal-rating">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star-o"></span>
                                        <span class="fa fa-star-o"></span>
                                    </div>
                                    <p><i class="flaticon-time"></i> 5 days starts from <span
                                            class="bold">$659</span></p>
                                    <a href="#" class="btn-blue btn-red">Book Now</a>
                                </div>
                            </div>
                            <div class="sidebar-package">
                                <div class="sidebar-package-image">
                                    <img src="{{asset('frontend/images/detailslider2.jpg')}}" alt="Images" />
                                </div>
                                <div class="destination-content sidebar-package-content">
                                    <h4><a href="#">Bahamas Royal Cruises</a></h4>
                                    <div class="deal-rating">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star-o"></span>
                                        <span class="fa fa-star-o"></span>
                                    </div>
                                    <p><i class="flaticon-time"></i> 5 days starts from <span
                                            class="bold">$659</span></p>
                                    <a href="#" class="btn-blue btn-red">Book Now</a>
                                </div>
                            </div>
                            <div class="sidebar-package">
                                <div class="sidebar-package-image">
                                    <img src="{{asset('frontend/images/detailslider3.jpg')}}" alt="Images" />
                                </div>
                                <div class="destination-content sidebar-package-content">
                                    <h4><a href="#">Royal Caribbean Cruises</a></h4>
                                    <div class="deal-rating">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star-o"></span>
                                        <span class="fa fa-star-o"></span>
                                    </div>
                                    <p><i class="flaticon-time"></i> 5 days starts from <span
                                            class="bold">$659</span></p>
                                    <a href="#" class="btn-blue btn-red">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        <div class="sidebar-item sidebar-helpline">
                            <div class="sidebar-helpline-content">
                                <h3>Any Questions?</h3>
                                {{-- <p>Lorem ipsum dolor sit amet, consectet ur adipiscing elit, sedpr do eiusmod tempor
                                incididunt ut.</p> --}}
                                <p><i class="flaticon-phone-call"></i> <a
                                        href="tel:{{ websiteSetupValue('whats_app_number') }}">{{ websiteSetupValue('whats_app_number') }}</a>
                                </p>
                                <p><i class="flaticon-mail"></i> <a
                                        href="mailto:{{ websiteSetupValue('email') }}">{{ websiteSetupValue('email') }}</a>
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
