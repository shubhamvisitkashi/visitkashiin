<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{

    public function store(Request $request){
        $enquiry = new Enquiry;
        $enquiry->name = $request->name;
        $enquiry->phone = $request->phone;
        $enquiry->no_of_person = $request->no_of_person;
        $enquiry->arrival_date = $request->arrival_date;
        $enquiry->checkin_time = $request->checkin_time;
        $enquiry->checkout_time = $request->checkout_time;
        $enquiry->message = $request->message;
        $enquiry->package_id     = $request->package_id;
        $enquiry->package_name   = $request->package_name;
        $enquiry->booking_amount = $request->booking_amount;
        $enquiry->time_slot      = $request->time_slot;
        $enquiry->pickup_ghat    = $request->pickup_ghat;
        $enquiry->children_count = (int)($request->children_count ?? 0);
        $enquiry->special_notes  = $request->special_notes;
        $enquiry->luggage_bags   = (int)($request->luggage_bags ?? 0);
        $enquiry->roof_carrier   = $request->roof_carrier;   // 'Yes' or null
        $enquiry->save();

        // Detect category from package name
        $pn = strtolower($request->package_name ?? '');
        if (str_contains($pn, 'boat') || str_contains($pn, 'ganga') || str_contains($pn, 'aarti') || str_contains($pn, 'cruise')) {
            $enqCategory = 'Boat Ride';     $enqIcon = '⛵'; $enqSubject = 'New Boat Enquiry';
        } elseif (str_contains($pn, 'cab') || str_contains($pn, 'car') || str_contains($pn, 'taxi') || str_contains($pn, 'traveller') || str_contains($pn, 'seater') || str_contains($pn, 'airport') || str_contains($pn, 'railway')) {
            $enqCategory = 'Cab Booking';   $enqIcon = '🚕'; $enqSubject = 'New Cab Enquiry';
        } elseif (str_contains($pn, 'hotel') || str_contains($pn, 'homestay') || str_contains($pn, 'stay') || str_contains($pn, 'room')) {
            $enqCategory = 'Stay / Hotel';  $enqIcon = '🏨'; $enqSubject = 'New Hotel Enquiry';
        } elseif (str_contains($pn, 'package') || str_contains($pn, 'tour') || str_contains($pn, 'varanasi to') || str_contains($pn, 'sightseeing')) {
            $enqCategory = 'Tour Package';  $enqIcon = '🗺️'; $enqSubject = 'New Tour Package Enquiry';
        } elseif (str_contains($pn, 'contact')) {
            $enqCategory = 'Contact';       $enqIcon = '✉️'; $enqSubject = 'New Contact Enquiry';
        } else {
            $enqCategory = 'General';       $enqIcon = '📋'; $enqSubject = 'New Enquiry';
        }

        try {
            Mail::send('email.enquiry_mail', [
                'name'           => $request->name,
                'phone'          => $request->phone,
                'arrival_date'   => $request->arrival_date ? date('d M Y', strtotime($request->arrival_date)) : null,
                'messages'       => $request->message,
                'package_name'   => $request->package_name,
                'no_of_person'   => $request->no_of_person,
                'children_count' => (int)($request->children_count ?? 0),
                'time_slot'      => $request->time_slot,
                'pickup_ghat'    => $request->pickup_ghat,
                'special_notes'  => $request->special_notes,
                'booking_amount' => $request->booking_amount,
                'luggage_bags'   => (int)($request->luggage_bags ?? 0),
                'roof_carrier'   => $request->roof_carrier,
                'checkin_time'   => $request->checkin_time  ? date('d M Y, h:i A', strtotime($request->checkin_time))  : null,
                'checkout_time'  => $request->checkout_time ? date('d M Y, h:i A', strtotime($request->checkout_time)) : null,
                'enquiry'        => $enquiry,
                'enq_category'   => $enqCategory,
                'enq_icon'       => $enqIcon,
            ], function($message) use ($request, $enqSubject){
                $message->to('help.visitkashi@gmail.com');
                $message->subject($enqSubject . ' — ' . $request->package_name . ' | Visit Kashi');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        return back()->with('success','Enquiry Submitted Successfully!');
    }

}
