<?php

use App\Models\Serivces;
use App\Models\BoatBooking;
use App\Models\WebsiteSetup;
use App\Models\BoatBookingRequest;

if (!function_exists('websiteSetupValue')) {
    function websiteSetupValue($name) {
        $settings = cache()->remember('website_setup_all', 3600, function () {
            return WebsiteSetup::all()->pluck('value', 'name')->toArray();
        });
        return $settings[$name] ?? '';
    }
}

if (!function_exists('whatsAppNumber')) {
    /**
     * The 'whats_app_number' setting sometimes holds multiple comma-separated
     * numbers (e.g. "7080109918, 7080109919") for display purposes. Callers
     * that build a wa.me link need a single digits-only number — take the
     * first one, not the whole string (a blind preg_replace('/\D/','',...)
     * over the raw value concatenates every number in it into one).
     */
    function whatsAppNumber() {
        $raw = websiteSetupValue('whats_app_number') ?: '917080109919';
        $first = trim(explode(',', $raw)[0]);
        return preg_replace('/\D/', '', $first) ?: '917080109919';
    }
}

if (!function_exists('invoiceBrand')) {
    /**
     * Resolve invoice branding (name + logo) based on the lead source.
     * Lead source "Kashi Yatra" gets the Kashi Yatra branding; everything
     * else (including no lead source) falls back to Visit Kashi.
     */
    function invoiceBrand($leadSourceName = null) {
        $isKashiYatra = $leadSourceName && stripos($leadSourceName, 'kashi yatra') !== false;

        if ($isKashiYatra) {
            return [
                'name' => websiteSetupValue('kashiyatra_site_name') ?: 'Kashi Yatra',
                'logo' => websiteSetupValue('kashiyatra_logo'),
                'is_kashiyatra' => true,
            ];
        }

        return [
            'name' => websiteSetupValue('site_name') ?: 'Visit Kashi',
            'logo' => websiteSetupValue('logo'),
            'is_kashiyatra' => false,
        ];
    }
}

if(!function_exists('dateTimeFormat')){
    function dateTimeFormat($datesTime){
        return date('d-M-Y h:i A', strtotime($datesTime));
    }
}

if(!function_exists('dateFormat')){
    function dateFormat($dates){
        return date('d-F-Y', strtotime($dates));
    }
}

if(!function_exists('timeFormat')){
    function timeFormat($time){
        return date('h:i A', strtotime($time));
    }
}

if(!function_exists('moneyFormatIndia')){
    function moneyFormatIndia($num) {
        $explrestunits = "" ;
        if(strlen($num)>3) {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if($i==0) {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash; // writes the final format where $currency is the currency symbol.
    }
}

/**
 * Strip dangerous tags/attributes from admin-entered rich HTML.
 * Keeps safe formatting tags (p, b, i, ul, li, a, img, table, etc.)
 * but removes <script>, inline event handlers (onclick, onerror, …),
 * and javascript: hrefs.
 */
if (!function_exists('safe_html')) {
    function safe_html(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Remove <script> and <iframe> blocks entirely
        $html = preg_replace('@<(script|iframe|object|embed|form)[^>]*?>.*?</\1>@si', '', $html);

        // Remove on* event handler attributes (onclick, onerror, onload …)
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*[^\s>]+/', '', $html);

        // Remove javascript: and vbscript: protocol in href/src
        $html = preg_replace('/\b(href|src|action)\s*=\s*["\']?\s*(javascript|vbscript):[^"\'>\s]*/i', '$1="#"', $html);

        return $html;
    }
}

if (!function_exists('generateBookingId')) {
    function generateBookingId() {
        $first_booking = BoatBooking::withTrashed()->oldest('id')->first();

        if(!$first_booking) {
            return 'VKDD2500001';
        }else{
            $latest_booking = BoatBooking::withTrashed()->latest('id')->first();
            $order_id = 'VKDD'.str_pad($latest_booking->id + 1, 7, '0', STR_PAD_LEFT);
            return $order_id;
        }
    }
}

if (!function_exists('generateBookingRequestId')) {
    function generateBookingRequestId() {
        $first_booking_request = BoatBookingRequest::withTrashed()->oldest('id')->first();

        if(!$first_booking_request) {
            return 'VKDDR2500001';
        }else{
            $latest_booking_request = BoatBookingRequest::withTrashed()->latest('id')->first();
            $order_id = 'VKDDR'.str_pad($latest_booking_request->id + 1, 7, '0', STR_PAD_LEFT);
            return $order_id;
        }
    }
}

?>
