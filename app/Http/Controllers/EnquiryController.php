<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnquiryController extends Controller
{

    public function store(Request $request){
        // Normalize package name — some product names are stored with HTML-encoded
        // ampersands (e.g. "&amp;"), which would otherwise show up literally in the
        // enquiry email and admin list instead of "&".
        $packageName = $request->package_name;
        while ($packageName && str_contains($packageName, '&amp;')) {
            $packageName = html_entity_decode($packageName, ENT_QUOTES);
        }

        $enquiry = new Enquiry;
        $enquiry->name = $request->name;
        $enquiry->phone = $request->phone;
        $enquiry->no_of_person = $request->no_of_person;
        $enquiry->arrival_date = $request->arrival_date;
        $enquiry->checkin_time = $request->checkin_time;
        $enquiry->checkout_time = $request->checkout_time;
        $enquiry->message = $request->message;
        $enquiry->package_id     = $request->package_id;
        $enquiry->package_name   = $packageName;
        $enquiry->booking_amount = $request->booking_amount;
        $enquiry->time_slot      = $request->time_slot;
        $enquiry->pickup_ghat    = $request->pickup_ghat;
        $enquiry->children_count = (int)($request->children_count ?? 0);
        $enquiry->special_notes  = $request->special_notes;
        $enquiry->luggage_bags   = (int)($request->luggage_bags ?? 0);
        $enquiry->roof_carrier   = $request->roof_carrier;   // 'Yes' or null
        $enquiry->save();

        // Send the lead-notification email, retrying once on transient SMTP
        // failures so a single dropped connection doesn't silently lose a lead.
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                $enquiry->sendLeadMail();
                break;
            } catch (\Throwable $th) {
                Log::error('Enquiry mail failed (attempt ' . $attempt . '): ' . $th->getMessage());
                if ($attempt === 2) {
                    break;
                }
                sleep(2);
            }
        }

        return back()->with('success','Enquiry Submitted Successfully!');
    }

}
