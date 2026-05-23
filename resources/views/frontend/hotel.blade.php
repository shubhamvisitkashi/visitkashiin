@extends('frontend.layouts.app')
@section('content')
    <section class="swiper-banner">
        <div class="slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="background-image: url({{asset('frontend/images/hotel/slider1.jpg')}})">
                        <div class="swiper-content" data-animation="animated fadeInDown">
                            <h2>Welcome To Yatra Hotel</h2>
                            <h1>Dream your Wonderful Hotel</h1>
                            <a href="#" class="btn-red btn-red">Explore Room</a>
                        </div>
                    </div>
                    <div class="swiper-slide" style="background-image: url({{asset('frontend/images/hotel/slider2.jpg')}})">
                        <div class="swiper-content" data-animation="animated fadeInRight">
                            <h2>exciting schemes just a click away</h2>
                            <h1>Quality Holidays With Us</h1>
                            <a href="#" class="btn-red btn-red">View More</a>
                        </div>
                    </div>
                    <div class="swiper-slide" style="background-image: url({{asset('frontend/images/hotel/slider3.jpg')}})">
                        <div class="swiper-content" data-animation="animated fadeInUp">
                            <h2>Cost friendly packages on your way</h2>
                            <h1>Everything is here right For u</h1>
                            <a href="#" class="btn-red btn-red">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="overlay"></div>
        </div>

        <div class="search-box clearfix">
            <div class="search-outer">
                <h3 class="text-center">Quick Booking</h3>
                <div class="search-content table_item">
                    <form>
                        <div class="form-group mb-3">
                            <div class="input-group date" id="datetimepicker1">
                                <input type="text" class="form-control" value="Check In" />
                                <i class="flaticon-calendar"></i>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group mb-3 form-icon">
                            <div class="input-group date" id="datetimepicker2">
                                <input type="text" class="form-control" value="Check Out" />
                                <i class="flaticon-calendar"></i>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div class="form-group form-icon">
                                    <select class="wide">
                                        <option value="0">Guest</option>
                                        <option value="1">0</option>
                                        <option value="2">1</option>
                                        <option value="3">2</option>
                                        <option value="4">3</option>
                                        <option value="5">4</option>
                                    </select>
                                    <i class="flaticon-box"></i>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group form-icon">
                                    <select class="wide">
                                        <option value="0">Room</option>
                                        <option value="1">0</option>
                                        <option value="2">1</option>
                                        <option value="3">2</option>
                                        <option value="4">3</option>
                                        <option value="5">4</option>
                                    </select>
                                    <i class="flaticon-box"></i>
                                </div>
                            </div>
                        </div>
                        <div class="search">
                            <a href="#" class="btn-red btn-red">CHECK AVAILABILITY</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

    <section id="mt_about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">

                    <div class="image-rev">
                        <div class="blur-img" style="background-image: url({{asset('frontend/images/list8.jpg')}})"></div>
                        <img src="{{asset('frontend/images/list8.jpg')}}" alt="" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="about_services text-center">
                        <h4>About Us</h4>
                        <h2 class="text-uppercase">Here is a tribute to <span>good life!</span></h2>
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and Lorem Ipsum has been the industry's
                            standard dummy when an unknown printer took a galley of
                            type andspecimen book eiusmod tempor incididunt ut labore.
                        </p>
                        <a href="about-us.html/index.html" class="btn-red">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="popular-packages">
        <div class="container">
            <div class="section-title">
                <h2>Popular <span>Rooms</span></h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt .</p>
            </div>
            <div class="row room-slider slider-button">
                <div class="col-lg-4">
                    <div class="package-item">
                        <img src="{{asset('frontend/images/hotel/room-1.jpg')}}" alt="Image" />
                        <div class="package-content">
                            <h5>Starting: <span>$659</span> / PER</h5>
                            <h3><a href="hotel-detail.html">Luxury Room</a></h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="package-item">
                        <img src="{{asset('frontend/images/hotel/room-2.jpg')}}" alt="Image" />
                        <div class="package-content">
                            <h5>Starting: <span>$459</span> / PER</h5>
                            <h3><a href="hotel-detail.html">Standard Room</a></h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="package-item">
                        <img src="{{asset('frontend/images/hotel/room-3.jpg')}}" alt="Image" />
                        <div class="package-content">
                            <h5>Starting: <span>$259</span> / PER</h5>
                            <h3><a href="hotel-detail.html">Double Room</a></h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="package-item">
                        <img src="{{asset('frontend/images/hotel/room-4.jpg')}}" alt="Image" />
                        <div class="package-content">
                            <h5>Starting: <span>$159</span> / PER</h5>
                            <h3><a href="hotel-detail.html">Single Room</a></h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="services pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mar-bottom-30">
                    <div class="package-item">
                        <div class="package-content">
                            <h3>Private <span>Pool Suite</span></h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                            <a href="hotel-detail.html" class="btn-red mar-top-20">check availability</a>
                        </div>
                        <img src="{{asset('frontend/images/hotel/services2.jpg')}}" alt="Image" />
                    </div>
                </div>
                <div class="col-lg-6 mar-bottom-30">
                    <div class="package-item package-item1">
                        <img src="{{asset('frontend/images/hotel/services1.jpg')}}" alt="Image" />
                        <div class="package-content">
                            <h3><span>Sea</span> View Suite</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                            <a href="hotel-detail.html" class="btn-red mar-top-20">check availability</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sml-services">
                <div class="row">
                    <div class="col-lg-4 col-md-12 mar-bottom-30">
                        <div class="package-item">
                            <img src="{{asset('frontend/images/hotel/services4.jpg')}}" alt="Image" />
                            <div class="package-position">
                                <h3 class="m-0">Spa</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mar-bottom-30">
                        <div class="package-item">
                            <img src="{{asset('frontend/images/hotel/services3.jpg')}}" alt="Image" />
                            <div class="package-position">
                                <h3 class="m-0">Restaurant</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mar-bottom-30">
                        <div class="package-item">
                            <img src="{{asset('frontend/images/hotel/services5.jpg')}}" alt="Image" />
                            <div class="package-position">
                                <h3 class="m-0">Activities</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="cta">
        <div class="container">
            <div class="cta-content text-center">
                <div class="cta-title">
                    <h2 class="white text-uppercase">Relax And Enjoy Your Holiday @Thailand Trip</h2>
                    <h3 class="white">Luxury Hotel & Best Resort</h3>
                </div>
                <div class="cta-btn">
                    <a href="hotel-detail.html" class="btn-red btn-red">BOOK NOW</a>
                </div>
            </div>
        </div>
    </section>


    <section class="deals-on-sale">
        <div class="container">
            <div class="section-title">
                <h2>Awesome Places</h2>
                <p>THE BEST VALUE UNDER THE SUN</p>
            </div>
            <div class="row sale-slider slider-button">
                <div class="col-lg-12">
                    <div class="sale-item">
                        <div class="sale-image">
                            <img src="{{asset('frontend/images/sale1.jpg')}}" alt="Image" />
                        </div>
                        <div class="sale-content">
                            <div class="deal-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <h3><a href="hotel-detail.html" class="white">Surfing Bahamas</a></h3>
                        </div>
                        <div class="sale-overlay"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="sale-item">
                        <div class="sale-image">
                            <img src="{{asset('frontend/images/sale2.jpg')}}" alt="Image" />
                        </div>
                        <div class="sale-content">
                            <div class="deal-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <h3><a href="hotel-detail.html" class="white">Mountain City</a></h3>
                        </div>
                        <div class="sale-overlay"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="sale-item">
                        <div class="sale-image">
                            <img src="{{asset('frontend/images/sale3.jpg')}}" alt="Image" />
                        </div>
                        <div class="sale-content">
                            <div class="deal-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <h3><a href="hotel-detail.html" class="white">Seneora Beach</a></h3>
                        </div>
                        <div class="sale-overlay"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="sale-item">
                        <div class="sale-image">
                            <img src="{{asset('frontend/images/sale4.jpg')}}" alt="Image" />
                        </div>
                        <div class="sale-content">
                            <div class="deal-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <h3><a href="hotel-detail.html" class="white">Beach Market</a></h3>
                        </div>
                        <div class="sale-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="testimonials">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="testimonial-inner">
                        <div class="testimonial-title text-center mar-bottom-35">
                            <h3>CUSTOMER <span>REVIEWS</span></h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                            </p>
                        </div>

                        <div id="testimonial_095"
                            class="carousel slide testimonial_095_indicators testimonial_095_control_button thumb_scroll_x swipe_x ps_easeOutSine"
                            data-bs-ride="carousel">

                            <div class="carousel-inner" role="listbox">

                                <div class="carousel-item active">

                                    <div class="testimonial_095_slide">
                                        <div class="testimonial-image">
                                            <img src="{{asset('frontend/images/testemonial2.jpg')}}" alt="Image" />
                                        </div>
                                        <div class="testi-heading text-center">
                                            <h4><a href="#">Susan Doe, Houston</a></h4>
                                            <h5><a href="#">Adventurer</a></h5>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetuer adipiscing elit am nibh unc varius
                                            facilisis eros ed erat in in velit quis arcu ornare laoreet
                                            urabitur adipiscing luctus massa nteger ut purus ac augue commodo commodo
                                            unc nec mi eu justo tempor consectetuer tiam.
                                        </p>
                                        <div class="deal-rating">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star-o"></span>
                                            <span class="fa fa-star-o"></span>
                                        </div>
                                    </div>

                                </div>



                                <div class="carousel-item">

                                    <div class="testimonial_095_slide">
                                        <div class="testimonial-image">
                                            <img src="{{asset('frontend/images/testemonial2.jpg')}}" alt="Image" />
                                        </div>
                                        <div class="testi-heading text-center">
                                            <h4><a href="#">Susan Doe, Houston</a></h4>
                                            <h5><a href="#">Adventurer</a></h5>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetuer adipiscing elit am nibh unc varius
                                            facilisis eros ed erat in in velit quis arcu ornare laoreet
                                            urabitur adipiscing luctus massa nteger ut purus ac augue commodo commodo
                                            unc nec mi eu justo tempor consectetuer tiam.
                                        </p>
                                        <div class="deal-rating">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star-o"></span>
                                            <span class="fa fa-star-o"></span>
                                        </div>
                                    </div>

                                </div>



                                <div class="carousel-item">

                                    <div class="testimonial_095_slide">
                                        <div class="testimonial-image">
                                            <img src="{{asset('frontend/images/testemonial2.jpg')}}" alt="Image" />
                                        </div>
                                        <div class="testi-heading text-center">
                                            <h4><a href="#">Susan Doe, Houston</a></h4>
                                            <h5><a href="#">Adventurer</a></h5>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetuer adipiscing elit am nibh unc varius
                                            facilisis eros ed erat in in velit quis arcu ornare laoreet
                                            urabitur adipiscing luctus massa nteger ut purus ac augue commodo commodo
                                            unc nec mi eu justo tempor consectetuer tiam.
                                        </p>
                                        <div class="deal-rating">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star-o"></span>
                                            <span class="fa fa-star-o"></span>
                                        </div>
                                    </div>

                                </div>


                            </div>


                            <a class="left carousel-control" data-bs-target="#testimonial_095" data-bs-slide="prev">
                                <span class="fa fa-chevron-left"></span>
                            </a>

                            <a class="right carousel-control" data-bs-target="#testimonial_095" role="button"
                                data-bs-slide="next">
                                <span class="fa fa-chevron-right"></span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="countdown-section p-0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="countdown-title">
                        <h3 class="white">Hot offer</h3>
                        <h2 class="white">GET <span>40% DISCOUNT</span> ONLY IN SUMMER VOCATIONS</h2>
                        <a href="#" class="btn-red mar-top-15">Book Now</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="countdown countdown-container">
                        <h3 class="white">Limited offer</h3>
                        <p id="demo"></p>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <section class="blog pb-5">
        <div class="container">
            <div class="section-title">
                <h2>Latest <span>News</span></h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt .</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-12 mar-bottom-30">
                    <div class="blog-item">
                        <div class="blog-image">
                            <img src="{{asset('frontend/images/blog1.jpg')}}" alt="Image" />
                        </div>
                        <div class="blog-content">
                            <h3><a href="blog-detail.html">Electric Feel And Of Other Things</a></h3>
                            <div class="blog-date">
                                <p><i class="fa fa-clock-o"></i> 12 May 2019</p>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                                ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mar-bottom-30">
                    <div class="blog-item">
                        <div class="blog-image">
                            <img src="{{asset('frontend/images/blog2.jpg')}}" alt="Image" />
                        </div>
                        <div class="blog-content">
                            <h3><a href="blog-detail.html">Electric Feel And Of Other Things</a></h3>
                            <div class="blog-date">
                                <p><i class="fa fa-clock-o"></i> 12 May 2019</p>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                                ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mar-bottom-30">
                    <div class="blog-item">
                        <div class="blog-image">
                            <img src="{{asset('frontend/images/blog3.jpg')}}" alt="Image" />
                        </div>
                        <div class="blog-content">
                            <h3><a href="blog-detail.html">Electric Feel And Of Other Things</a></h3>
                            <div class="blog-date">
                                <p><i class="fa fa-clock-o"></i> 12 May 2019</p>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                                ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
