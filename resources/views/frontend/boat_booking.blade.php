@extends('frontend.layouts.app')
@section('meta')
    @isset($sub_category)
        <link rel="canonical" href="{{url()->full()}}">
        <title>{{optional($sub_category)->meta_title}} - {{env('APP_DOMAIN')}}</title>
        <meta name="description" content="{{optional($sub_category)->meta_description}}">
        <meta name="keywords" content="{{optional($sub_category)->meta_keyword}}">
        <meta property="og:title" content="{{optional($sub_category)->meta_title}}">
        <meta property="og:site_name" content="{{env('APP_URL')}}">
        <meta property="og:description" content="{{optional($sub_category)->meta_description}}">
        <meta property="og:keywords" content="{{optional($sub_category)->meta_keyword}}">

    @elseif($category)

        <link rel="canonical" href="{{url()->full()}}">
        <title>{{optional($category)->meta_title}} - {{env('APP_DOMAIN')}}</title>
        <meta name="description" content="{{optional($category)->meta_description}}">
        <meta name="keywords" content="{{optional($category)->meta_keyword}}">
        <meta property="og:title" content="{{optional($category)->meta_title}}">
        <meta property="og:site_name" content="{{env('APP_URL')}}">
        <meta property="og:description" content="{{optional($category)->meta_description}}">
        <meta property="og:keywords" content="{{optional($category)->meta_keyword}}">
    @endisset

@endsection
@section('content')
    <section class="breadcrumb-outer text-center">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>@isset($sub_category) {{optional($sub_category)->meta_title}} @elseif($category) {{optional($category)->meta_title}} @endisset</h2>
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
                        @foreach ($products as $product)
                            <div class="col-sm-3 col-xs-6">
                                <div class="package-item">
                                    <div class="package-image">
                                        <img src="{{ $product->boatType->image }}">
                                    </div>
                                    <div class="package-content">
                                        <h3>{{ $product->boatType->name }}</h3>
                                        <p>from <b><span style="font-family: system-ui;padding-right: 2px;">₹</span><del>{{ number_format($product->price) }}</del> ₹{{ number_format($product->discounted_price) }}</b>/- Person</p>
                                        <a href="{{ route('festival.boat.booking', $product->boatType->slug) }}" class="btn btn-primary btn-block mt-2">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
