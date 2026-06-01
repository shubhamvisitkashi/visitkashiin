<?php

namespace App\Http\Controllers;

use App\Models\HotelEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HotelEnquiryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'hotel_name'        => 'required|string|max:255',
            'guest_name'        => 'required|string|max:100',
            'contact_number'    => 'required|digits:10',
            'adults'            => 'required|integer|min:1|max:50',
            'kids'              => 'required|integer|min:0|max:50',
            'checkin_datetime'  => 'required|date|after_or_equal:today',
            'checkout_datetime' => 'required|date|after:checkin_datetime',
            'nights'            => 'required|integer|min:1',
        ], [
            'contact_number.digits'      => 'Contact number must be exactly 10 digits.',
            'checkout_datetime.after'    => 'Check-out must be after check-in.',
            'checkin_datetime.after_or_equal' => 'Check-in cannot be in the past.',
        ]);

        $enquiry = HotelEnquiry::create([
            'category'          => 'hotel',
            'hotel_name'        => $request->hotel_name,
            'guest_name'        => $request->guest_name,
            'contact_number'    => $request->contact_number,
            'adults'            => $request->adults,
            'kids'              => $request->kids,
            'checkin_datetime'  => $request->checkin_datetime,
            'checkout_datetime' => $request->checkout_datetime,
            'nights'            => $request->nights,
        ]);

        // Send email notification
        try {
            Mail::send('email.hotel_enquiry_mail', ['enquiry' => $enquiry], function ($message) {
                $message->to('help.visitkashi@gmail.com')
                        ->subject('New Hotel Enquiry — ' . request('hotel_name'));
            });
        } catch (\Throwable $th) {
            // Mail failure should not block the user
        }

        return redirect()->back()->with('hotel_success', true);
    }
}
