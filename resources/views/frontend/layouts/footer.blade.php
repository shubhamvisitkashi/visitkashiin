<section>
    <div class="container">
        <div class="col-lg-12">
            <div class="footer-about footer-margin">
                @foreach (App\Models\Admin\Category::where('is_active','active')->with(['subCategory','product'])->get() as $footer_category)
                    <b>{{$footer_category->name}}</b> <br>
                    @foreach ($footer_category->product as $footer_product)
                        <a @if ($footer_product->subCategory)
                            href="{{ route('product.detail', [$footer_product->category->slug, $footer_product->subCategory->slug, $footer_product->slug]) }}"
                        @else
                            href="{{ route('product.detail', [$footer_product->category->slug, 'varanasi', $footer_product->slug]) }}" @endif
                        >
                            {{$footer_product->name}}</a>  @if (!$loop->last) , @endif
                        </a>
                    @endforeach
                    <br><br>
                @endforeach
            </div>
        </div>
    </div>
</section>
<footer>
    <div class="footer-upper">
        <div class="container">
            <div class="footer-links">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <div class="footer-about footer-margin">
                                    <h3>About</h3>
                                    <ul>
                                        <li>
                                            <a href="#">Contact Us <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">About Us<i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">Career <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">Sitemap <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 mob_hide">
                                <div class="footer-about footer-margin">
                                    <h3>Help</h3>
                                    <ul>
                                        <li>
                                            <a href="#">Payments <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">FAQ <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 mob_hide">
                                <div class="footer-about footer-margin">
                                    <h3>Guest Policy</h3>
                                    <ul>
                                        <li>
                                            <a href="#">Privacy Policy <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">Cancellation & Returns <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">User Agreement <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="footer-about footer-margin">
                                    <h3>Registered Office Address:</h3>
                                    <div class="about-location">
                                        <ul>
                                            <li><i class="flaticon-maps-and-flags" aria-hidden="true"></i>
                                                {{ websiteSetupValue('address') }}</li>
                                            <li><i class="flaticon-phone-call"></i>+91-{{ websiteSetupValue('contact_number') }} , 7080109918, 7080109919</li>
                                            <li><i class="flaticon-mail"></i>
                                                <a href="#">{{ optional(App\Models\WebsiteSetup::where('name', 'email')->first())->value }}</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="footer-social-links">
                                        <ul>
                                            <li class="social-icon">
                                                <a href="https://www.facebook.com/visitkashiofficial/" target="_blank"><i class="fa fa-facebook"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                            <li class="social-icon">
                                                <a href="https://www.instagram.com/visitkashi/?hl=en" target="_blank"><i class="fa fa-instagram"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                            <li class="social-icon">
                                                <a href="https://twitter.com/visit_kashi?lang=en" target="_blank"><i class="fa fa-twitter"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                            <li class="social-icon">
                                                <a href="https://www.youtube.com/@visitkashi" target="_blank"><i class="fa fa-youtube"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="copyright-content">
                        <p>
                            Visit Kashi <i class="fa fa-copyright" aria-hidden="true"></i> 2018 - <script type="text/javascript">var year = new Date();document.write(year.getFullYear());</script> | All Rights Reserved.
                        </p>
                    </div>
                </div>
                {{-- <div class="col-lg-6">
                    <div class="payment-content">
                        <ul>
                            <li>We Accept</li>
                            <li>
                                <img src="{{asset('frontend/images/payment1.png')}}" alt="Image" />
                            </li>
                            <li>
                                <img src="{{asset('frontend/images/payment2.png')}}" alt="Image" />
                            </li>
                            <li>
                                <img src="{{asset('frontend/images/payment3.png')}}" alt="Image" />
                            </li>
                            <li>
                                <img src="{{asset('frontend/images/payment4.png')}}" alt="Image" />
                            </li>
                        </ul>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</footer>
<div class="whatsapp">
    <a href="https://wa.me/+919260912327" target="_blank">
        <img src="{{ asset('frontend/images/whatsapp.png') }}" alt="whatsapp">
    </a>
</div>
<div id="back-to-top">
    <a href="#"></a>
</div>
