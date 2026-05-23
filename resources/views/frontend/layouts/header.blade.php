<header>
    <div class="upper-head clearfix">
        <div class="container">
            <div class="contact-info">
                <p>One Stop Solution for Your Trip</p>
            </div>
            <div class="contact-info pull-right">
                <p><i class="flaticon-phone-call"></i><a href="tel:{{websiteSetupValue('whats_app_number')}}" class="text-white">{{websiteSetupValue('whats_app_number')}}</a></p>
                <p class="mail"><i class="flaticon-mail"></i><a href="mailto:{{websiteSetupValue('email')}}" class="text-white">{{websiteSetupValue('email')}}</a></p>
            </div>

        </div>
    </div>
</header>
<div class="navigation">
    <div class="container">
        <div class="navigation-content">
            <div class="header_menu">
                <nav class="navbar navbar-default navbar-sticky-function navbar-arrow">
                    <div class="logo pull-left">
                        <a href="{{route('index')}}"><img alt="Image" src="{{asset('backend/admin/website_setup/'.websiteSetupValue('logo'))}}" /></a>
                    </div>
                    <div id="navbar" class="navbar-nav-wrapper">
                        <ul class="nav navbar-nav" id="responsive-menu">
                            @foreach (App\CPU\CategoryManager::active()->with(['subCategory'])->get() as $category)
                                <li>
                                    <a @if(count($category->subCategory) == 0) href="{{route('product.list',$category->slug)}}" @endif>{{$category->name}} @if(count($category->subCategory) != 0)<i class="fa fa-angle-down"></i>@endif</a>
                                    @if(count($category->subCategory) != 0)
                                    <ul>
                                        @foreach ($category->subCategory as $sub_category)
                                            <li> <a href="{{route('product.sub.list',[$category->slug,$sub_category->slug])}}">{{$sub_category->name}}</a></li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                            @endforeach

                            {{-- <li>
                                <a href="#">Cab <i class="fa fa-angle-down"></i></a>
                                <ul>
                                    <li> <a href="#">Airport Pickup/Drop</a></li>
                                    <li> <a href="#">Station Pickup/Drop</a></li>
                                    <li> <a href="#">Cab for Full Day in Varanasi</a></li>
                                    <li> <a href="#">Cab for Varanasi to Vindhyachal</a></li>
                                    <li> <a href="#">Cab for Varanasi to Prayagraj</a></li>
                                    <li> <a href="#">Cab for Varanasi to Ayodhya</a></li>
                                    <li> <a href="#">Cab for Varanasi to Bodhgaya</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Boat Ride<i class="fa fa-angle-down"></i></a>
                                <ul>
                                    <li> <a href="#">Hand Boat Booking in Varanasi</a></li>
                                    <li> <a href="#">Motor Boat Booking in Varanasi</a></li>
                                    <li> <a href="#">Lighting Boat Booking in Varanasi</a></li>
                                    <li> <a href="#">Bajra Boat Booking in Varanasi</a></li>
                                    <li> <a href="#">Musical Boat Ride in Varanasi</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-star"></i> Experiences<i class="fa fa-angle-down"></i></a>
                                <ul>
                                    <li> <a href="#">Food Tour in Varanasi</a></li>
                                    <li> <a href="#">Shopping in Varanasi</a></li>
                                    <li> <a href="#">Boat Tour in Varanasi</a></li>
                                    <li> <a href="#">Ganga Aarti</a></li>
                                    <li> <a href="#">Dev Diwali 2022</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Packages <i class="fa fa-angle-down"></i></a>
                                <ul>
                                    <li><a href="#">Dashboard</a></li>
                                    <li><a href="#">Dashboard Profile</a></li>
                                    <li><a href="#">Dashboard Bookings</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Ghats </a>
                            </li>
                            <li>
                                <a href="#">Temples</a>
                            </li>
                            <li>
                                <a href="#">Shop</a>
                            </li> --}}

                        </ul>
                    </div>
                    <div id="slicknav-mobile"></div>
                </nav>
            </div>
        </div>
    </div>
</div>
