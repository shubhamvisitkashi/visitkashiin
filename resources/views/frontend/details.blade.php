@extends('frontend.layouts.app')
@section('meta')
    @isset($sub_category)
        <link rel="canonical" href="{{ url()->full() }}">
        <title>{{ optional($sub_category)->meta_title }} - {{ env('APP_DOMAIN') }}</title>
        <meta name="description" content="{{ optional($sub_category)->meta_description }}">
        <meta name="keywords" content="{{ optional($sub_category)->meta_keyword }}">
        <meta property="og:title" content="{{ optional($sub_category)->meta_title }}">
        <meta property="og:site_name" content="{{ env('APP_URL') }}">
        <meta property="og:description" content="{{ optional($sub_category)->meta_description }}">
        <meta property="og:keywords" content="{{ optional($sub_category)->meta_keyword }}">
    @elseif($category)
        <link rel="canonical" href="{{ url()->full() }}">
        <title>{{ optional($category)->meta_title }} - {{ env('APP_DOMAIN') }}</title>
        <meta name="description" content="{{ optional($category)->meta_description }}">
        <meta name="keywords" content="{{ optional($category)->meta_keyword }}">
        <meta property="og:title" content="{{ optional($category)->meta_title }}">
        <meta property="og:site_name" content="{{ env('APP_URL') }}">
        <meta property="og:description" content="{{ optional($category)->meta_description }}">
        <meta property="og:keywords" content="{{ optional($category)->meta_keyword }}">
    @endisset
@endsection
@section('content')
    <section class="breadcrumb-outer text-center">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>
                    @isset($sub_category)
                        {{ optional($sub_category)->meta_title }}
                    @elseif($category)
                        {{ optional($category)->meta_title }}
                    @endisset
                </h2>
                {{-- <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Destinations</li>
                    </ul>
                </nav> --}}
            </div>
        </div>
        <div class="section-overlay"></div>
    </section>


    <section class="destinations">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-sm-3 col-xs-6">
                                <div class="package-item">
                                    <div class="package-image">
                                        <a
                                            @if (optional($product->subCategory)->slug) href="{{ route('product.detail', [optional($product->category)->slug ?? 'default', $product->subCategory->slug, $product->slug ?? '#']) }}"
                                        @else
                                        href="{{ route('product.detail', [optional($product->category)->slug ?? 'default', 'varanasi', $product->slug ?? '#']) }}" @endif>
                                            @php
                                                // Safely get first image
                                                $firstImage = null;
                                                if (
                                                    !empty($product->images) &&
                                                    is_array($product->images) &&
                                                    count($product->images) > 0
                                                ) {
                                                    $firstImage = $product->images[0];
                                                }
                                            @endphp

                                            @if ($firstImage && file_exists(public_path('backend/admin/product_images/' . $firstImage)))
                                                <img src="{{ asset('backend/admin/product_images/' . $firstImage) }}"
                                                    alt="{{ $product->name ?? 'Product Image' }}"
                                                    onerror="this.src='{{ asset('backend/assets/images/placeholder.jpg') }}'">
                                            @else
                                                <img src="{{ asset('backend/assets/images/placeholder.jpg') }}"
                                                    alt="{{ $product->name ?? 'Product Image' }}">
                                            @endif
                                    </div>
                                    </a>
                                    <div class="package-content">
                                        <a
                                            @if (optional($product->subCategory)->slug) href="{{ route('product.detail', [optional($product->category)->slug ?? 'default', $product->subCategory->slug, $product->slug ?? '#']) }}"
                                        @else
                                        href="{{ route('product.detail', [optional($product->category)->slug ?? 'default', 'varanasi', $product->slug ?? '#']) }}" @endif>
                                            <h3>{{ $product->name ?? 'Untitled Product' }}</h3>
                                            @if (optional($product->category)->show_price == '1' && isset($product->discounted_price))
                                                <p>from <b><span
                                                            style="font-family: system-ui;padding-right: 2px;">₹</span>{{ number_format($product->discounted_price) }}</b>/-
                                                    @if (in_array(optional($product->category)->slug, ['hotels', 'homestay']))
                                                        Night
                                                    @endif
                                                </p>
                                            @elseif(optional($product->category)->show_price == '1')
                                                <p>Price on request</p>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center" role="alert">
                                    <i class="fa fa-info-circle"></i> No products found in this category.
                                </div>
                            </div>
                        @endforelse


                    </div>
                    {{-- <div class="row">
                        <div class="col-lg-12">
                            <div class="pagination-content">
                                <ul class="pagination">
                                    <li>
                                        <a href="#"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="active"><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li>
                                        <a href="#"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> --}}
                </div>
                {{-- <div id="sidebar-sticky" class="col-lg-4">
                    <aside class="detail-sidebar sidebar-wrapper">
                        <div class="sidebar-item sidebar-item-dark">
                            <div class="detail-title">
                                <h3>Search</h3>
                            </div>
                            <form>
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <input type="text" class="form-control" id="Name"
                                            placeholder="Enter the place you want to search" />
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="comment-btn">
                                            <a href="#" class="btn-blue btn-red">Search Now</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="sidebar-item">
                            <div class="detail-title">
                                <h3>Price Range</h3>
                            </div>
                            <div class="sidebar-content">
                                <div class="range-slider">
                                    <div data-min="0" data-max="2000" data-unit="₹" data-min-name="min_price"
                                        data-max-name="max_price"
                                        class="range-slider-ui ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"
                                        aria-disabled="false">
                                        <span class="min-value">0 ₹</span>
                                        <span class="max-value">2000 ₹</span>
                                        <div class="ui-slider-range ui-widget-header ui-corner-all whole"
                                            style="left: 0%; width: 100%"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-item">
                            <div class="detail-title">
                                <h3>Facilities</h3>
                            </div>
                            <div class="sidebar-content">
                                <form>
                                    <input type="checkbox" name="facility" value="wifi" /> Free Wifi<br />
                                    <input type="checkbox" name="facility" value="pool" /> Swimming Pool<br />
                                    <input type="checkbox" name="facility" value="housekeeping" /> Daily
                                    Housekeeping<br />
                                    <input type="checkbox" name="facility" value="bar" checked /> Restaurant Bar and
                                    Lounge<br />
                                    <div class="sidebar-btn">
                                        <a href="#" class="btn-blue btn-red">View More</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="sidebar-item">
                            <div class="detail-title">
                                <h3>Categories</h3>
                            </div>
                            <ul class="event-list">
                                <li><a href="#">Bahamas Carribean Cruises</a></li>
                                <li><a href="#">Oktoberfest - 5 Days Camping</a></li>
                                <li><a href="#">The Chelsea Flower Show</a></li>
                                <li><a href="#">Harbin Ice Festival</a></li>
                                <li><a href="#">Belgian Beer Tour</a></li>
                            </ul>
                        </div>
                    </aside>
                </div> --}}
            </div>
        </div>
    </section>
@endsection
