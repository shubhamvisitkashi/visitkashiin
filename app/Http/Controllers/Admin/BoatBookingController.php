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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class BoatBookingController extends Controller
{

    public function index(Request $request) {
        $search_boat_type      = $request->search_boat_type;
        $search_event_type     = $request->search_event_type;
        $search_booking_id     = $request->search_booking_id;
        $search_user           = $request->search_user;
        $search_date           = $request->search_date;
        $search_payment_status = $request->search_payment_status;
        $search_booking_type   = $request->search_booking_type;
        $search_date_from      = $request->search_date_from;
        $search_date_to        = $request->search_date_to;

        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        $userId  = auth('admin')->id();

        $boat_types  = BoatType::orderBy('sort_order')->get();
        $event_types = ['Festival', 'Regular'];

        $boat_bookings = BoatBooking::when(!$isAdmin, fn($q) => $q->where('created_by', $userId))
                            ->when($search_boat_type, function($query) use ($search_boat_type) {
                                $query->whereHas('boat', function($q) use ($search_boat_type) {
                                    $q->where('boat_type_id', $search_boat_type);
                                });
                            })
                            ->when($search_user, function($query) use ($search_user) {
                                $query->where(function($q) use ($search_user) {
                                    $q->where('name', 'like', '%' . $search_user . '%')
                                      ->orWhere('phone', 'like', '%' . $search_user . '%')
                                      ->orWhere('booking_id', 'like', '%' . $search_user . '%');
                                });
                            })
                            ->when($search_payment_status, fn($q) => $q->where('payment_status', $search_payment_status))
                            ->when($search_booking_type,   fn($q) => $q->where('booking_type', $search_booking_type))
                            ->when($search_date_from,      fn($q) => $q->whereDate('booking_date', '>=', $search_date_from))
                            ->when($search_date_to,        fn($q) => $q->whereDate('booking_date', '<=', $search_date_to))
                            ->when($search_date, function($query) use ($search_date) {
                                $dates = explode('-', $search_date);
                                if (count($dates) >= 2) {
                                    $query->whereBetween('created_at', [
                                        Carbon::parse(trim($dates[0]))->startOfDay(),
                                        Carbon::parse(trim($dates[1]))->endOfDay(),
                                    ]);
                                }
                            });

        $total_persons = $boat_bookings->sum('no_of_person');
        $total_final_amount = $boat_bookings->sum('final_amount');
        $total_amount = $boat_bookings->sum('total_amount');
        $total_discount_amount = $boat_bookings->sum('total_discount');
        $total_payments = BoatBookingPayment::whereHas('boatBooking', function($q) use ($isAdmin, $userId) {
                                                if (!$isAdmin) $q->where('created_by', $userId);
                                            })->when($search_boat_type, function($query) use ($search_boat_type) {
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

        $boat_type_stats = BoatBooking::when(!$isAdmin, fn($q) => $q->where('created_by', $userId))
        ->when($search_boat_type, function($query) use ($search_boat_type) {
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
        $booking_request_id   = $request->booking_request_id;
        $boat_booking_request = BoatBookingRequest::where('booking_request_id', $booking_request_id)->first();
        // Load boat types with their boats (pricing per event_type)
        $boat_types = BoatType::with(['boats' => function($q) {
            $q->where('is_active', 1);
        }])->where('is_active', 1)->orderBy('sort_order')->get();

        $leadSources     = \App\Models\LeadSource::orderBy('name')->get();
        $paymentAccounts = \App\Models\PaymentAccount::where('is_active', 1)->orderBy('account_name')->get();
        $boatmen         = \App\Models\Boatman::active()->orderBy('name')->get();

        return view('admin.boat_booking.create', compact(
            'boat_types', 'boat_booking_request', 'leadSources', 'paymentAccounts', 'boatmen'
        ), ['page_title' => 'New Boat Booking']);
    }

    public function voucher($booking_id) {
        $booking = BoatBooking::where('booking_id', $booking_id)
            ->with(['boat.boatType', 'payments'])
            ->withSum('payments', 'amount')
            ->firstOrFail();

        $boatman = $booking->boatman_id
            ? \App\Models\Boatman::find($booking->boatman_id)
            : null;

        $createdBy = $booking->created_by
            ? \App\Models\Admin\Admin::find($booking->created_by)
            : null;

        return view('admin.boat_booking.voucher', compact('booking', 'boatman', 'createdBy'), [
            'page_title' => 'Voucher — ' . $booking->booking_id,
        ]);
    }

    public function voucherPdf($booking_id) {
        $booking = BoatBooking::where('booking_id', $booking_id)
            ->with(['boat.boatType', 'payments'])
            ->withSum('payments', 'amount')
            ->firstOrFail();

        $boatman = $booking->boatman_id
            ? \App\Models\Boatman::find($booking->boatman_id)
            : null;

        $createdBy = $booking->created_by
            ? \App\Models\Admin\Admin::find($booking->created_by)
            : null;

        $pdf = Pdf::loadView('admin.boat_booking.voucher_pdf', compact('booking', 'boatman', 'createdBy'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'sans-serif',
                'dpi'                  => 150,
                'enable_css_float'     => true,
                'margin_top'           => 0,
                'margin_right'         => 0,
                'margin_bottom'        => 0,
                'margin_left'          => 0,
            ]);

        return $pdf->download('VK-Voucher-' . $booking->booking_id . '.pdf');
    }

    public function show($booking_id) {
        $booking = BoatBooking::where('booking_id', $booking_id)
            ->with(['boat.boatType', 'payments'])
            ->withSum('payments', 'amount')
            ->firstOrFail();

        $boatman = $booking->boatman_id
            ? \App\Models\Boatman::find($booking->boatman_id)
            : null;

        return view('admin.boat_booking.show', compact('booking', 'boatman'), [
            'page_title' => 'Boat Booking — ' . $booking->booking_id,
        ]);
    }

    public function edit($booking_id) {
        $boat_types = BoatType::orderBy('sort_order')->get();
        $booking = BoatBooking::where('booking_id', $booking_id)->with('boat.boatType')->withSum('payments', 'amount')->first();

        return view('admin.boat_booking.edit', compact('boat_types', 'booking'), ['page_title' => 'Edit Boat Booking']);
    }

    public function update(Request $request, $booking_id) {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:20',
            'no_of_person'  => 'required|integer|min:1',
            'boarding_ghat' => 'nullable|string|max:255',
            'drop_ghat'     => 'nullable|string|max:255',
            'guest_notes'   => 'nullable|string|max:1000',
        ]);

        $boat_booking = BoatBooking::where('booking_id', $booking_id)->first();
        if(!$boat_booking) {
            return redirect()->back()->with('error', 'No booking found.');
        }

        $boat_booking->name          = $request->name;
        $boat_booking->email         = $request->email;
        $boat_booking->phone         = $request->phone;
        $boat_booking->no_of_person  = $request->no_of_person;
        $boat_booking->boarding_ghat = $request->boarding_ghat;
        $boat_booking->drop_ghat     = $request->drop_ghat;
        $boat_booking->guest_notes   = $request->guest_notes;
        $boat_booking->save();

        return redirect()->route('boat-booking.index')->with('success', 'Booking updated successfully.');
    }

    public function store(Request $request) {
        $maxDisc = max(0, (float)$request->total_amount);
        $maxPaid = max(0, (float)$request->final_amount ?: (float)$request->total_amount);
        $request->validate([
            'boat_type'        => 'required|exists:boat_types,id',
            'event_type'       => 'required|in:Festival,Regular',
            'name'             => 'required|string|max:255',
            'email'            => 'nullable|email|max:255',
            'phone'            => 'required|string|max:20',
            'adults'           => 'required|integer|min:1',
            'no_of_person'     => 'nullable|integer|min:1',
            'event_date'       => 'required|date',
            'total_amount'     => 'required|numeric|min:0',
            'discount_amount'    => 'nullable|numeric|min:0|max:' . $maxDisc,
            'paid_amount'        => 'required|numeric|min:0|max:' . $maxPaid,
            'payment_method'     => 'required|string',
            'payment_account_id' => 'required|exists:payment_accounts,id',
            'boatman_id'         => 'required|exists:boatmen,id',
            'vendor_cost'        => 'required|numeric|min:0',
        ]);

        $boat = Boat::where('boat_type_id', $request->boat_type)->where('event_type', $request->event_type)->first();
        if (!$boat) {
            return response()->json([
                'status' => 'error',
                'errors' => ['boat_type' => ['No boat available for the selected type and event.']]
            ], 422);
        }

        $total_boat_booking   = BoatBooking::where('boat_id', $boat->id)->whereDate('booking_date', Carbon::parse($request->event_date)->format('Y-m-d'))->sum('no_of_person');
        $total_seat           = $boat->total_available_boat * $boat->no_of_seat;
        $total_available_seat = $total_seat - $total_boat_booking;

        if ($request->no_of_person > $total_available_seat) {
            return response()->json(['status' => 'error', 'message' => 'No seat available on this boat for the selected date.'], 404);
        }

        $booking_request = BoatBookingRequest::where('booking_request_id', $request->booking_request_id)->first();

        DB::beginTransaction();
        try {
            $paidAmt      = (float)($request->paid_amount ?? 0);
            $discountAmt  = (float)($request->discount_amount ?? 0);

            $boat_booking = new BoatBooking;
            $boat_booking->booking_id = generateBookingId();
            $boat_booking->booked_by  = auth()->guard('admin')->user()->id;
            $boat_booking->boat_id    = $boat->id;
            $boat_booking->name       = $request->name;
            $boat_booking->email      = $request->email ?? '';
            $boat_booking->phone      = $request->phone;
            $boat_booking->adults     = (int)($request->adults ?? 1);
            $boat_booking->children   = (int)($request->children ?? 0);
            $boat_booking->no_of_person = (int)($request->adults ?? 1) + (int)($request->children ?? 0);

            if ($request->event_type === 'Festival') {
                $boat_booking->total_amount   = $boat->price * $request->no_of_person;
                $boat_booking->total_discount = $discountAmt;
                $boat_booking->final_amount   = $boat_booking->total_amount - $discountAmt;
            } else {
                $boat_booking->total_amount   = $request->total_amount;
                $boat_booking->total_discount = $discountAmt;
                $boat_booking->final_amount   = $request->final_amount ?? max(0, $request->total_amount - $discountAmt);
            }

            $boat_booking->booking_date = $request->event_date;

            if ($request->seat_number) {
                $boat_booking->seat_number = implode(', ', range(
                    ($total_boat_booking + 1),
                    (($total_boat_booking ? $total_boat_booking + 1 : $total_boat_booking) + ($total_boat_booking ? $request->no_of_person - 1 : $request->no_of_person))
                ));
            }

            // Route & timing
            $boat_booking->boarding_ghat = $request->pickup_ghat ?? $request->boarding_ghat;
            $boat_booking->drop_ghat     = $request->drop_ghat;
            $boat_booking->boat_timing   = $request->booking_type;
            $boat_booking->pickup_time   = $request->pickup_time;
            $boat_booking->drop_time     = $request->drop_time;
            // Event type
            $boat_booking->booking_type     = $request->booking_type;
            $boat_booking->event_on_boat    = $request->event_on_boat;
            $boat_booking->celebration_type = $request->event_on_boat;
            // Add-ons
            $boat_booking->decoration   = $request->has('decoration')   ? 1 : 0;
            $boat_booking->photographer = $request->has('photographer') ? 1 : 0;
            $boat_booking->live_music   = $request->has('live_music')   ? 1 : 0;
            $boat_booking->priest       = $request->has('priest')       ? 1 : 0;
            $boat_booking->flowers      = $request->has('flowers')      ? 1 : 0;
            $boat_booking->fireworks    = $request->has('fireworks')    ? 1 : 0;
            // Notes & meta
            $boat_booking->guest_notes      = $request->guest_notes;
            $boat_booking->special_requests = $request->internal_notes;
            $boat_booking->lead_source_id   = $request->lead_source_id ?: null;
            $boat_booking->payment_method     = $request->payment_method;
            $boat_booking->payment_account_id = $request->payment_account_id ?: null;
            // B2B / staff
            $boat_booking->vendor_cost          = (float)($request->vendor_cost ?? 0);
            $boat_booking->boatman_id           = $request->boatman_id ?: null;
            $boat_booking->created_by           = auth()->guard('admin')->id();
            $boat_booking->extra_per_person_rate = (float)($request->extra_per_person_rate ?? 0);
            $boat_booking->base_pax             = (int)($request->base_pax ?? 0);
            $boat_booking->margin_amount        = (float)($request->final_amount ?? 0) - (float)($request->vendor_cost ?? 0);

            $boat_booking->booking_status = 'confirmed';
            if ($boat_booking->final_amount <= 0 || $paidAmt >= $boat_booking->final_amount) {
                $boat_booking->payment_status = 'paid';
            } elseif ($paidAmt > 0) {
                $boat_booking->payment_status = 'partial';
            } else {
                $boat_booking->payment_status = 'unpaid';
            }
            $boat_booking->save();

            // Only create payment record when advance is actually paid
            if ($paidAmt > 0) {
                $boat_booking_payment = new BoatBookingPayment;
                $boat_booking_payment->boat_booking_id = $boat_booking->id;
                $boat_booking_payment->amount          = $paidAmt;
                if ($booking_request && isset($booking_request->payment_detail)) {
                    $paymentDetail = $booking_request->payment_detail;
                    $boat_booking_payment->payment_details = [
                        'transaction_id' => $paymentDetail['utr_number'] ?? null,
                        'payment_mode'   => $paymentDetail['payment_method'] ?? 'upi',
                        'notes'          => 'Converted from booking request',
                    ];
                }
                $boat_booking_payment->save();
            }

            if ($booking_request) {
                $booking_request->booking_status = 'confirmed';
                if ($boat_booking->final_amount == $paidAmt) {
                    $booking_request->payment_status = 'paid';
                }
                $booking_request->save();
            }

            DB::commit();

            if ($request->is_mail_send === 'send_mail') {
                try {
                    Mail::send('email.booking_confiramtion', ['boat_booking' => $boat_booking], function ($message) use ($boat_booking) {
                        $message->to($boat_booking->email);
                        $message->subject('Boat Booking Confirmation');
                    });
                } catch (\Throwable $th) {
                    // Mail failure should not break the booking
                }
            }

            return redirect()->route('boat-booking.index')->with('success', 'Boat booking created successfully! Booking ID: ' . $boat_booking->booking_id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Boat booking store failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
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
