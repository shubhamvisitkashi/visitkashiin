<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteSetupApiController extends Controller
{
    public function websiteStup()
    {
        $websiteSetup = [
            'logo'              => websiteSetupValue('logo')?asset('backend/admin/website_setup/'.websiteSetupValue('logo')):asset('backend/no-pictures.png'),
            'favicon'           => websiteSetupValue('favicon')?asset('backend/admin/website_setup/'.websiteSetupValue('favicon')):asset('backend/no-pictures.png'),
            'contact_number'    => websiteSetupValue('contact_number'),
            'whats_app_number'  => websiteSetupValue('whats_app_number'),
            'email'             => websiteSetupValue('email'),
            'address'           => websiteSetupValue('address'),
            'facebook'          => websiteSetupValue('facebook'),
            'twitter'           => websiteSetupValue('twitter'),
            'instagram'         => websiteSetupValue('instagram'),
            'youtube'           => websiteSetupValue('youtube'),
            'linkedin'          => websiteSetupValue('linkedin'),
        ];
        return response()->json([
            'website_setup'   => $websiteSetup
        ], 200);
    }
}
