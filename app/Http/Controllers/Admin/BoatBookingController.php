<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Boat;
use App\Models\BoatType;
use App\Models\BoatBooking;
use Illuminate\Http\Request;
use App\Models\BoatBookingPayment;
use App\Models\BoatBookingRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class BoatBookingController extends Controller
{

    public function index(Request $request) {
        $search_boat_type = $request->search_boat_type;
        $search_event_type = $request->search_event_type;
        $search_booking_id = $request->search_booking_id;
        $search_user = $request->search_user;
        $search_date = $request->search_date;
        $search_payment_status = $request->search_payment_status;

        $boat_types = BoatType::oldest('name')->get();
        $event_types = ['Festival', 'Regular'];

        $boat_bookings = BoatBooking::when($search_boat_type, function($query) use ($search_boat_type) {
                                $query->whereHas('boat', function($quer) use ($search_boat_type) {
                                    $quer->where('boat_type_id', ($search_boat_type));
                                });
                            })->when($search_event_type, function($query) use ($search_event_type) {
                                $query->where('event_type', $search_event_type);
                            })->when($search_booking_id, function($query) use ($search_booking_id) {
                                $query->where('booking_id', $search_booking_id);
                            })->when($search_user, function($query) use ($search_user) {
                                $query->where(function($q) use ($search_user) {
                                    $q->where('name', 'like', '%' . $search_user . '%')
                                      ->orWhere('phone', $search_user)
                                      ->orWhere('email', $search_user);
                                });
                            })->when($search_payment_status, function($query) use ($search_payment_status) {
                                $query->where('payment_status', $search_payment_status);
                            })->when($search_date,function($query) use ($search_date){
                                $dates=explode('-',$search_date);
                                $d1=strtotime($dates[0]);
                                $d2=strtotime($dates[1]);
                                $da1=date('Y-m-d',$d1);
                                $da2=date('Y-m-d',$d2);
                                $startDate = Carbon::createFromFormat('Y-m-d', $da1)->startOfDay();
                                $endDate = Carbon::createFromFormat('Y-m-d', $da2)->endOfDay();

                                $query->where(function($qu) use ($startDate,$endDate){
                                    $qu->whereBetween('created_at', [$startDate, $endDate]);
                                });
                            });

        $total_persons = $boat_bookings->sum('no_of_person');
        $total_final_amount = $boat_bookings->sum('final_amount');
        $total_amount = $boat_bookings->sum('total_amount');
        $total_discount_amount = $boat_bookings->sum('total_discount');
        $total_payments = BoatBookingPayment::whereHas('boatBooking')->when($search_boat_type, function($query) use ($search_boat_type) {
                                                $query->whereHas('boatBooking', function($quer) use ($search_boat_type) {
                                                    $quer->whereHas('boat', function($que) use ($search_boat_type) {
                                                    $que->where('boat_type_id', ($search_boat_type));
                                                });
                                            });
                                        })->when($search_event_type, function($query) use ($search_event_type) {
                                            $query->whereHas('boatBooking', function($quer) use ($search_event_type) {
                                                $quer->where('event_type', $search_event_type);
                                            });
                                        })->when($search_booking_id, function($query) use ($search_booking_id) {
                                            $query->whereHas('boatBooking', function($quer) use ($search_booking_id) {
                                                $quer->where('booking_id', $search_booking_id);
                                            });
                                        })->when($search_payment_status, function($query) use ($search_payment_status) {
                                            $query->whereHas('boatBooking', function($quer) use ($search_payment_status) {
                                                $quer->where('payment_status', $search_payment_status);
                                            });
                                        })->when($search_user, function($query) use ($search_user) {
                                            $query->whereHas('boatBooking', function($quer) use ($search_user) {
                                                $quer->where(function($q) use ($search_user) {
                                                    $q->where('name', 'like', '%' . $search_user . '%')
                                                    ->orWhere('phone', $search_user)
                                                    ->orWhere('email', $search_user);
                                                });
                                            });
                                        })->when($search_date,function($query) use ($search_date){
                                            $dates=explode('-',$search_date);
                                            $d1=strtotime($dates[0]);
                                            $d2=strtotime($dates[1]);
                                            $da1=date('Y-m-d',$d1);
                                            $da2=date('Y-m-d',$d2);
                                            $startDate = Carbon::createFromFormat('Y-m-d', $da1)->startOfDay();
                                            $endDate = Carbon::createFromFormat('Y-m-d', $da2)->endOfDay();

                                            $query->whereHas('boatBooking', function($quer) use ($search_user) {
                                                $quer->where(function($qu) use ($startDate,$endDate){
                                                    $qu->whereBetween('created_at', [$startDate, $endDate]);
                                                });
                                            });
                                        })->sum('amount');

        // Check if the request is for PDF export
        if ($request->has('export') && $request->export === 'pdf') {
            // Get bookings based on selection
            if ($request->has('selected_bookings') && !empty($request->selected_bookings)) {
                // Export only selected bookings
                $selected_bookings = $request->selected_bookings;
                $boat_bookings_for_export = BoatBooking::whereIn('booking_id', $selected_bookings)
                    ->with('boat.boatType')
                    ->orderBy('id')
                    ->get();
            } else {
                // Export all filtered bookings (without pagination)
                $boat_bookings_for_export = $boat_bookings->with('boat.boatType')->get();
            }

            $data = $boat_bookings_for_export->map(function ($booking) use ($search_boat_type) {
                return [
                    'booking_id' => $booking->booking_id,
                    'customer_name' => $booking->name,
                    'number_of_person' => $booking->no_of_person,
                    'boat_type' => $search_boat_type ? null : $booking->boat?->boatType?->name,
                    'seat_number' => $booking->seat_number,
                ];
            });

            $pdf = Pdf::loadView('admin.boat_booking.pdf', compact('data', 'search_boat_type'));

            $filename = 'boat_bookings_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            if ($search_boat_type) {
                $boat_type = BoatType::find($search_boat_type);
                $filename = ($boat_type?->name ?? 'Selected') . '_bookings_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            }

            return $pdf->download($filename);
        }

        // Apply pagination for regular view
        $boat_bookings = $boat_bookings->with('boat.boatType')->withSum('payments', 'amount')->latest()->paginate(10);

        $boat_type_stats = BoatBooking::when($search_boat_type, function($query) use ($search_boat_type) {
            $query->whereHas('boat', function($quer) use ($search_boat_type) {
                $quer->where('boat_type_id', ($search_boat_type));
            });
        })->when($search_event_type, function($query) use ($search_event_type) {
            $query->where('event_type', $search_event_type);
        })->when($search_booking_id, function($query) use ($search_booking_id) {
            $query->where('booking_id', $search_booking_id);
        })->when($search_payment_status, function($query) use ($search_payment_status) {
            $query->where('payment_status', $search_payment_status);
        })->when($search_user, function($query) use ($search_user) {
            $query->where(function($q) use ($search_user) {
                $q->where('name', 'like', '%' . $search_user . '%')
                ->orWhere('phone', $search_user)
                ->orWhere('email', $search_user);
            });
        })->when($search_date, function($query) use ($search_date) {
            $dates=explode('-',$search_date);
            $d1=strtotime($dates[0]);
            $d2=strtotime($dates[1]);
            $da1=date('Y-m-d',$d1);
            $da2=date('Y-m-d',$d2);
            $startDate = Carbon::createFromFormat('Y-m-d', $da1)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $da2)->endOfDay();

            $query->whereHas('boatBooking', function($quer) use ($search_user) {
                $quer->where(function($qu) use ($startDate,$endDate){
                    $qu->whereBetween('created_at', [$startDate, $endDate]);
                });
            });
        })
        ->select('boat_id')
        ->selectRaw('SUM(no_of_person) as total_persons')
        ->with('boat.boatType')
        ->groupBy('boat_id')
        ->get()
        ->groupBy('boat.boat_type_id');


        return view('admin.boat_booking.index', compact('search_boat_type', 'search_event_type', 'search_booking_id', 'search_user', 'search_date', 'search_payment_status', 'boat_types', 'event_types', 'boat_bookings', 'total_payments', 'total_final_amount', 'total_amount', 'total_discount_amount', 'total_persons', 'boat_type_stats'), ['page_title' => 'Boat Bookings']);
    }

    public function create(Request $request) {
        $booking_request_id = $request->booking_request_id;
        $boat_booking_request = BoatBookingRequest::where('booking_request_id', $booking_request_id)->first();
        $boat_types = BoatType::oldest('name')->get();

        return view('admin.boat_booking.create', compact('boat_types', 'boat_booking_request'), ['page_title' => 'Add Boat Booking']);
    }

    public function edit($booking_id) {
        $boat_types = BoatType::oldest('name')->get();
        $booking = BoatBooking::where('booking_id', $booking_id)->with('boat.boatType')->withSum('payments', 'amount')->first();

        return view('admin.boat_booking.edit', compact('boat_types', 'booking'), ['page_title' => 'Edit Boat Booking']);
    }

    public function update(Request $request, $booking_id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $boat_booking = BoatBooking::where('booking_id', $booking_id)->first();
        if(!$boat_booking) {
            return redirect()->back()->with('error', 'No booking found.');

        }

        $boat_booking->name = $request->name;
        $boat_booking->email = $request->email;
        $boat_booking->phone = $request->phone;
        $boat_booking->save();

        return redirect()->route('boat-booking.index')->with('success', 'Booking updated successfully.');
    }

    public function store(Request $request) {
        // return $request->seat_number;
        $request->validate([
            'boat_type' => 'required|exists:boat_types,id',
            'event_type' => 'required|in:Festival,Regular',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'no_of_person' => 'required|integer|min:1',
            'event_date' => 'required|date|after_or_equal:today',
            'discount_amount'   => 'required|numeric|min:0|max:' .$request->total_amount,
            'paid_amount'   => 'required|numeric|min:0|max:' .$request->final_amount,
        ]);

        $boat = Boat::where('boat_type_id', $request->boat_type)->where('event_type', $request->event_type)->first();
        if(!$boat) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'boat_type' => ['No boat available for the selected type and event.']
                ]
            ], 422);
        }

        $total_boat_booking = BoatBooking::where('boat_id', $boat->id)->whereDate('booking_date', Carbon::parse($request->event_date)->format('Y-m-d'))->sum('no_of_person');

        $total_seat = $boat->total_available_boat * $boat->no_of_seat;
        $total_available_seat = $total_seat - $total_boat_booking;

        if($request->no_of_person > $total_available_seat) {
            return response()->json(['status' => 'error', 'message' => 'No seat available on this boat for the selected date.'], 404);
        }

        $booking_request = BoatBookingRequest::where('booking_request_id', $request->booking_request_id)->first();

        $boat_booking = new BoatBooking;
        $boat_booking->booking_id = generateBookingId();
        $boat_booking->booked_by = auth()->guard('admin')->user()->id;
        $boat_booking->boat_id = $boat->id;
        $boat_booking->name = $request->name;
        $boat_booking->email = $request->email;
        $boat_booking->phone = $request->phone;
        $boat_booking->no_of_person = $request->no_of_person;
        if($request->event_type === 'Festival') {
            $boat_booking->total_amount = $boat->price * $request->no_of_person;
            $boat_booking->total_discount = $request->discount_amount;
            $boat_booking->final_amount = $boat_booking->total_amount - $request->discount_amount;
        }
        $boat_booking->booking_date = $request->event_date;
        if($request->seat_number) {
            $boat_booking->seat_number = implode(', ', range(($total_boat_booking + 1), (($total_boat_booking ? $total_boat_booking + 1 : $total_boat_booking) + ($total_boat_booking ? $request->no_of_person - 1 : $request->no_of_person))));
        }
        $boat_booking->booking_status = 'confirmed';
        if($boat_booking->final_amount != $request->paid_amount) {
            $boat_booking->payment_status = 'partial';
        }else{
            $boat_booking->payment_status = 'paid';
        }
        $boat_booking->save();

        $boat_booking_payment = new BoatBookingPayment;
        $boat_booking_payment->boat_booking_id = $boat_booking->id;
        $boat_booking_payment->amount = $request->paid_amount;
        if($booking_request && isset($booking_request->payment_detail)) {
            $paymentDetail = $booking_request->payment_detail;
            $boat_booking_payment->payment_details = [
                'transaction_id' => $paymentDetail['utr_number'] ?? null,
                'payment_mode' => $paymentDetail['payment_method'] ?? 'upi',
                'notes' => 'Converted from booking request',
            ];
        }
        $boat_booking_payment->save();


        if($booking_request) {
            $booking_request->booking_status = 'confirmed';
            if($boat_booking->final_amount == $request->paid_amount) {
                $booking_request->payment_status = 'paid';
            }
            $booking_request->save();
        }

        if($request->is_mail_send === 'send_mail') {
            try{
                Mail::send('email.booking_confiramtion', ['boat_booking'=>$boat_booking, 'boat_booking_payment'=>$boat_booking_payment], function($message) use ($boat_booking){
                    $message->to($boat_booking->email);
                    $message->subject('Dev Diwali Boat Booking Confirmation');
                });
            }catch (\Throwable $th) {
                //throw $th;
            }
        }

    }

    public function destroy($booking_id) {
        $boat_booking = BoatBooking::where('booking_id', $booking_id)->first();
        if(!$boat_booking) {
            return redirect()->back()->with('error', 'No booking found.');
        }

        $boat_booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }

    public function checkAvailability(Request $request) {
        $boat = Boat::where('boat_type_id', $request->boat_type)->where('event_type', $request->event_type)->first();
        if(!$boat) {
            return response()->json(['status' => 'error', 'message' => 'No boat available for the selected type and event.'], 404);
        }

        $total_boat_booking = BoatBooking::where('boat_id', $boat->id)->whereDate('booking_date', Carbon::parse($request->event_date)->format('Y-m-d'))->sum('no_of_person');

        $total_seat = $boat->total_available_boat * $boat->no_of_seat;
        $total_available_seat = $total_seat - $total_boat_booking;

        if($request->no_of_person > $total_available_seat) {
            return response()->json(['status' => 'error', 'message' => 'No seat available on this boat for the selected date.'], 404);
        }

        return $boat;
    }

    public function sendBookingMail($booking_id) {
        $boat_booking = BoatBooking::where('booking_id', $booking_id)->with('boat.boatType')->first();
        if(!$boat_booking) {
            return redirect()->back()->with('error', 'No booking found.');
        }

        try{
            Mail::send('email.booking_confiramtion', ['boat_booking'=>$boat_booking], function($message) use ($boat_booking){
                $message->to($boat_booking->email);
                $message->subject('Dev Diwali Boat Booking Confirmation');
            });

            return redirect()->back()->with('success', 'Booking confirmation mail sent successfully.');
        }catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function payment($booking_id) {
        $boat_booking = BoatBooking::where('booking_id', $booking_id)->with('boat.boatType')->with('payments')->first();
        if(!$boat_booking) {
            return redirect()->back()->with('error', 'No booking found.');
        }

        return view('admin.boat_booking.payment', compact('boat_booking'), ['page_title' => 'Boat Booking Payment']);
    }

    public function paymentStore(Request $request, $booking_id) {
        $boat_booking = BoatBooking::where('booking_id', $booking_id)->withSum('payments', 'amount')->first();
        if(!$boat_booking) {
            return redirect()->back()->with('error', 'No booking found.');
        }

        $request->validate([
            'amount'            => 'required|numeric|min:1|max:' .($boat_booking->final_amount - $boat_booking->payments_sum_amount),
            'payment_method'    => 'required|in:cash,upi,card,bank_transfer,online',
            'transaction_id'    => 'nullable|string|max:255',
            'notes'             => 'nullable|string|max:500',
        ]);

        $boat_booking_payment = new BoatBookingPayment;
        $boat_booking_payment->boat_booking_id = $boat_booking->id;
        $boat_booking_payment->amount = $request->amount;
        $boat_booking_payment->payment_details = ['transaction_id' => $request->transaction_id, 'payment_mode' => $request->payment_method, 'notes' => $request->notes];
        $boat_booking_payment->save();

        $total_paid_amount = BoatBookingPayment::where('boat_booking_id', $boat_booking->id)->sum('amount');
        if($total_paid_amount >= $boat_booking->final_amount) {
            $boat_booking->payment_status = 'paid';
        }else{
            $boat_booking->payment_status = 'partial';
        }
        $boat_booking->save();

        return ;
    }

    public function bookingRequest(Request $request) {
        $search_booking_id = $request->search_booking_id;
        $search_user = $request->search_user;
        $search_date = $request->search_date;
        $search_status = $request->search_status;
        $search_payment_status = $request->search_payment_status;

        // Build and execute the main query
        $boat_bookings = BoatBookingRequest::when($search_booking_id, function($query) use ($search_booking_id) {
                            $query->where('booking_request_id', 'like', '%' . $search_booking_id . '%');
                        })->when($search_status, function($query) use ($search_status) {
                            $query->where('booking_status', $search_status);
                        })->when($search_payment_status, function($query) use ($search_payment_status) {
                            $query->where('payment_status', $search_payment_status);
                        })->when($search_user, function($query) use ($search_user) {
                            $query->where(function($q) use ($search_user) {
                                $q->where('name', 'like', '%' . $search_user . '%')
                                  ->orWhere('phone', 'like', '%' . $search_user . '%')
                                  ->orWhere('email', 'like', '%' . $search_user . '%');
                            });
                        })->when($search_date, function($query) use ($search_date) {
                            if (strpos($search_date, '-') !== false) {
                                $dates = explode('-', $search_date);
                                $d1 = strtotime(trim($dates[0]));
                                $d2 = strtotime(trim($dates[1]));
                                $da1 = date('Y-m-d', $d1);
                                $da2 = date('Y-m-d', $d2);
                                $startDate = Carbon::createFromFormat('Y-m-d', $da1)->startOfDay();
                                $endDate = Carbon::createFromFormat('Y-m-d', $da2)->endOfDay();

                                $query->whereBetween('created_at', [$startDate, $endDate]);
                            }
                        })
                        ->with('boat.boatType')
                        ->latest()
                        ->paginate(10);

        // Calculate statistics from total results (not just current page)
        $total_requests = $boat_bookings->total();

        // For more accurate statistics, you might want to run separate queries
        $pending_requests = BoatBookingRequest::where('booking_status', 'pending')->count();
        $confirmed_requests = BoatBookingRequest::where('booking_status', 'confirmed')->count();
        $total_amount = BoatBookingRequest::sum('final_amount');

        return view('admin.boat_booking.booking_request', compact(
            'search_booking_id',
            'search_user',
            'search_date',
            'search_status',
            'search_payment_status',
            'boat_bookings',
            'total_requests',
            'pending_requests',
            'confirmed_requests',
            'total_amount'
        ), ['page_title' => 'Boat Booking Requests']);
    }

    public function cancelBookingRequest($booking_request_id){
        try {
            $boat_booking_request = BoatBookingRequest::where('booking_request_id', $booking_request_id)->first();

            if (!$boat_booking_request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking request not found.'
                ], 404);
            }

            // Check if status is pending
            if ($boat_booking_request->booking_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending booking requests can be cancelled. Current status: ' . ucfirst($boat_booking_request->booking_status)
                ], 400);
            }

            // Update status to cancelled
            $boat_booking_request->booking_status = 'cancelled';
            $boat_booking_request->payment_status = 'cancelled';
            $boat_booking_request->save();

            // Optional: Send cancellation email to customer
            // You can implement this based on your requirements

            return response()->json([
                'success' => true,
                'message' => 'Booking request has been cancelled successfully.',
                'data' => [
                    'booking_request_id' => $boat_booking_request->booking_request_id,
                    'status' => $boat_booking_request->booking_status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while cancelling the booking request. Please try again.'
            ], 500);
        }
    }

}
