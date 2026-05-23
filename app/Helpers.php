<?php

use App\Models\Serivces;
use App\Models\BoatBooking;
use App\Models\WebsiteSetup;
use App\Models\BoatBookingRequest;

if (!function_exists('websiteSetupValue')) {
    function websiteSetupValue($name) {
        return WebsiteSetup::where('name', $name)->first() ? WebsiteSetup::where('name', $name)->first()->value : "";
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
