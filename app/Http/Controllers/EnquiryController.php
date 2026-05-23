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
        $enquiry->package_id = $request->package_id;
        $enquiry->package_name = $request->package_name;
        $enquiry->save();

        try {
            Mail::send('email.enquiry_mail', ['name'=>$request->name,'phone'=>$request->phone,'arrival_date'=>date('d-m-Y h:i A', strtotime($request->arrival_date)),'messages'=>$request->message,'package_name'=>$request->package_name,'no_of_person'=>$request->no_of_person,'checkin_time'=>date('d-m-Y h:i A', strtotime($request->checkin_time)),'checkout_time'=>date('d-m-Y h:i A', strtotime($request->checkout_time)),'enquiry'=>$enquiry], function($message){
                $message->to('info.visitkashi@gmail.com');
                $message->subject('Booking Notification');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        return back()->with('success','Enquiry Submitted Successfully!');
    }

}
