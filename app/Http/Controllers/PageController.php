<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function privacyPolicy()
    {
        return view('frontend.pages.privacy-policy');
    }

    public function cancellationRefund()
    {
        return view('frontend.pages.cancellation-refund');
    }

    public function aboutUs()
    {
        return view('frontend.pages.about-us');
    }
}
