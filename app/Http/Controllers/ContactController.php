<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:15',
            'message' => 'required|string|max:1000',
        ]);

        $subject = $request->subject ?: 'General Enquiry';

        $enquiry = new Enquiry;
        $enquiry->name         = $request->name;
        $enquiry->phone        = preg_replace('/\D/', '', $request->phone);
        $enquiry->message      = "[{$subject}] " . $request->message;
        $enquiry->package_id   = 0;
        $enquiry->package_name = 'Contact Us Form';
        $enquiry->save();

        try {
            Mail::send('email.enquiry_mail', [
                'name'         => $request->name,
                'phone'        => $request->phone,
                'arrival_date' => 'N/A',
                'messages'     => "[{$subject}] " . $request->message,
                'package_name' => 'Contact Us — ' . $subject,
                'no_of_person' => 'N/A',
                'checkin_time' => 'N/A',
                'checkout_time'=> 'N/A',
                'enquiry'      => $enquiry,
            ], function ($message) {
                $message->to('help.visitkashi@gmail.com');
                $message->cc('info.visitkashi@gmail.com');
                $message->subject('New Contact Us Message — Visit Kashi');
            });
        } catch (\Throwable $th) {
            // mail failure is non-fatal; enquiry is already saved
        }

        return redirect()->route('contact.show')
                         ->with('contact_success', 'Thank you! We have received your message and will get back to you shortly.');
    }
}
