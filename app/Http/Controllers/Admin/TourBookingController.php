<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\PaymentAccount;
use App\Models\ServiceType;
use App\Models\ServiceTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TourBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:booking-create');
    }

    public function create()
    {
        $leadSources     = LeadSource::orderBy('name')->get();
        $paymentAccounts = PaymentAccount::where('is_active', 1)->orderBy('account_name')->get();

        // Load stay/hotel templates from DB grouped by city keyword
        $stayType = ServiceType::whereRaw("LOWER(name) LIKE '%stay%' OR LOWER(name) LIKE '%hotel%'")->first();
        $allStayTemplates = $stayType
            ? ServiceTemplate::where('service_type_id', $stayType->id)->where('is_active', 1)->orderBy('name')->pluck('name')->toArray()
            : [];

        $cities = ['Varanasi', 'Prayagraj', 'Ayodhya', 'Chitrakoot', 'Bodhgaya', 'Lucknow', 'Naimisharanya'];
        $cityKeywords = [
            'Varanasi'     => ['varanasi', 'kashi', 'banaras', 'benares', 'saket nagar', 'assi', 'dashashwamedh', 'ganga'],
            'Prayagraj'    => ['prayagraj', 'allahabad'],
            'Ayodhya'      => ['ayodhya', 'ayodh', 'ram ghat', 'ram mandir'],
            'Chitrakoot'   => ['chitrakoot'],
            'Bodhgaya'     => ['bodhgaya', 'bodh gaya', 'bodh'],
            'Lucknow'      => ['lucknow'],
            'Naimisharanya'=> ['naimisharanya', 'nimsar', 'naimisha'],
        ];

        $hotelsByCity = [];
        foreach ($cities as $city) {
            $keywords = $cityKeywords[$city];
            $matched = array_filter($allStayTemplates, function($name) use ($keywords) {
                $lower = strtolower($name);
                foreach ($keywords as $kw) {
                    if (str_contains($lower, $kw)) return true;
                }
                return false;
            });
            $hotelsByCity[$city] = array_values($matched);
        }

        // Hotels that didn't match any city go into every city's list
        $unmatched = array_filter($allStayTemplates, function($name) use ($cityKeywords) {
            $lower = strtolower($name);
            foreach ($cityKeywords as $kws) {
                foreach ($kws as $kw) {
                    if (str_contains($lower, $kw)) return false;
                }
            }
            return true;
        });
        foreach ($cities as $city) {
            $hotelsByCity[$city] = array_merge($hotelsByCity[$city], array_values($unmatched));
        }

        return view('admin.bookings.tour-booking', compact('leadSources', 'paymentAccounts', 'hotelsByCity'), [
            'page_title' => 'New Tour Package Booking',
        ]);
    }

    /** Add a payment entry to a tour booking */
    public function addPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'payment_date'   => 'required|date',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'notes'          => 'nullable|string|max:255',
        ]);

        $defaultAccount = PaymentAccount::first();

        BookingPayment::create([
            'booking_id'         => $booking->id,
            'payment_account_id' => $defaultAccount?->id,
            'payment_date'       => $request->payment_date,
            'amount'             => $request->amount,
            'payment_method'     => $request->payment_method,
            'notes'              => $request->notes,
            'received_by'        => auth('admin')->id(),
        ]);

        // Update booking paid/pending amounts
        $totalPaid = BookingPayment::where('booking_id', $booking->id)->sum('amount');
        $booking->update([
            'paid_amount'    => $totalPaid,
            'pending_amount' => max(0, $booking->total_amount - $totalPaid),
        ]);

        return redirect()->route('tour-booking.show', $id)
            ->with('success', '✅ Payment of ₹' . number_format($request->amount) . ' recorded on ' . \Carbon\Carbon::parse($request->payment_date)->format('d M Y'));
    }

    /** Read-only view of tour booking */
    public function view($id)
    {
        $booking = Booking::with(['lead', 'quotation.items', 'payments'])
            ->withSum('payments', 'amount')
            ->findOrFail($id);

        $serviceData   = $this->parseServiceData($booking);
        $expenses      = [
            'hotel' => (float)($serviceData['hotel']['b2b'] ?? 0),
            'cab'   => (float)($serviceData['cab']['b2b']   ?? 0),
            'boat'  => (float)($serviceData['boat']['b2b']  ?? 0),
            'guide' => (float)($serviceData['guide']['b2b'] ?? 0),
        ];
        $internalNotes = $serviceData['internal'] ?? ($booking->lead->notes ?? '');

        return view('admin.bookings.tour-booking-view',
            compact('booking','expenses','internalNotes','serviceData'), [
            'page_title' => 'Tour Booking — ' . $booking->booking_number,
        ]);
    }

    /** Parse booking notes into structured service data */
    private function parseServiceData(Booking $booking): array
    {
        // Prefer quotation.notes (where we now store JSON); fall back to booking.notes (old)
        $raw = $booking->quotation?->notes ?? $booking->notes ?? '';
        $data = null;

        // Try JSON first (new format)
        if ($raw && substr(trim($raw), 0, 1) === '{') {
            $data = json_decode($raw, true);
        }

        // Fallback: parse old "B2B: Hotel ₹X | Cab ₹X ..." text format
        if (!$data) {
            $expenses = ['hotel' => 0, 'cab' => 0, 'boat' => 0, 'guide' => 0];
            if (str_contains($raw, 'B2B:')) {
                preg_match('/Hotel ₹([\d,]+)/', $raw, $m); $expenses['hotel'] = (int)str_replace(',','',$m[1]??0);
                preg_match('/Cab ₹([\d,]+)/',   $raw, $m); $expenses['cab']   = (int)str_replace(',','',$m[1]??0);
                preg_match('/Boat ₹([\d,]+)/',  $raw, $m); $expenses['boat']  = (int)str_replace(',','',$m[1]??0);
                preg_match('/Guide ₹([\d,]+)/', $raw, $m); $expenses['guide'] = (int)str_replace(',','',$m[1]??0);
            }
            $data = [
                'hotel' => ['b2b' => $expenses['hotel']],
                'cab'   => ['b2b' => $expenses['cab']],
                'boat'  => ['b2b' => $expenses['boat']],
                'guide' => ['b2b' => $expenses['guide']],
                'internal' => trim(preg_replace('/B2B:.*$/m', '', $raw)),
            ];
        }

        return $data;
    }

    /** Show tour booking detail (same layout as create) */
    public function show($id)
    {
        $booking = Booking::with(['lead', 'quotation.items', 'payments'])
            ->withSum('payments', 'amount')
            ->findOrFail($id);

        $leadSources   = LeadSource::orderBy('name')->get();
        $serviceData   = $this->parseServiceData($booking);
        $expenses      = [
            'hotel' => (float)($serviceData['hotel']['b2b'] ?? 0),
            'cab'   => (float)($serviceData['cab']['b2b']   ?? 0),
            'boat'  => (float)($serviceData['boat']['b2b']  ?? 0),
            'guide' => (float)($serviceData['guide']['b2b'] ?? 0),
        ];
        $internalNotes = $serviceData['internal'] ?? ($booking->lead->notes ?? '');

        return view('admin.bookings.tour-booking-detail',
            compact('booking','leadSources','expenses','internalNotes','serviceData'), [
            'page_title' => 'Tour Booking — ' . $booking->booking_number,
        ]);
    }

    /** Update existing tour booking */
    public function update(Request $request, $id)
    {
        $booking = Booking::with(['lead','quotation.items','payments'])->findOrFail($id);

        $request->validate([
            'guest_name'     => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'package_name'   => 'required|string|max:255',
            'tour_start'     => 'required|date',
            'booking_amount' => 'required|numeric|min:0',
            'hotels'         => 'nullable|array',
            'hotels.*.city'      => 'nullable|string|max:100',
            'hotels.*.name'      => 'nullable|string|max:255',
            'hotels.*.room_type' => 'nullable|string|max:100',
            'hotels.*.checkin'   => 'nullable|date',
            'hotels.*.checkout'  => 'nullable|date',
            'hotels.*.b2b'       => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $bookingAmount = (float) $request->booking_amount;
            $discount      = (float) ($request->discount ?? 0);
            $advance       = (float) ($request->advance_paid ?? $booking->paid_amount ?? 0);
            $net           = max(0, $bookingAmount - $discount);
            $balance       = max(0, $net - $advance);

            // Process hotels array (same as store)
            $hotelsArr = $request->hotels ?? [];
            $hotelB2b  = 0;
            $hotelRows = [];
            foreach ($hotelsArr as $h) {
                if (empty($h['name']) && empty($h['city'])) continue;
                $nights = 0;
                if (!empty($h['checkin']) && !empty($h['checkout'])) {
                    $nights = max(0, (int)\Carbon\Carbon::parse($h['checkin'])->diffInDays(\Carbon\Carbon::parse($h['checkout'])));
                }
                $b2b = (float)($h['b2b'] ?? 0);
                $hotelB2b += $b2b;
                $hotelRows[] = [
                    'city'      => $h['city']      ?? '',
                    'name'      => $h['name']      ?? '',
                    'room_type' => $h['room_type'] ?? '',
                    'checkin'   => $h['checkin']   ?? '',
                    'checkout'  => $h['checkout']  ?? '',
                    'nights'    => $nights,
                    'b2b'       => $b2b,
                ];
            }
            if (empty($hotelRows)) {
                $hotelB2b = (float)($request->exp_hotel ?? 0);
            }

            $totalExpense = $hotelB2b
                          + (float)($request->exp_cab   ?? 0)
                          + (float)($request->exp_boat  ?? 0)
                          + (float)($request->exp_guide ?? 0);

            // Build full service details JSON
            $serviceData = json_encode([
                'hotels' => $hotelRows,
                'hotel'  => [
                    'hotel_name'     => $hotelRows[0]['name']     ?? '',
                    'hotel_checkin'  => $hotelRows[0]['checkin']  ?? '',
                    'hotel_checkout' => $hotelRows[0]['checkout'] ?? '',
                    'hotel_nights'   => $hotelRows[0]['nights']   ?? 1,
                    'b2b'            => $hotelB2b,
                ],
                'cab' => [
                    'cab_type'    => $request->cab_type,
                    'cab_route'   => $request->cab_route,
                    'cab_from'    => $request->cab_from,
                    'cab_to'      => $request->cab_to,
                    'driver_name' => $request->driver_name,
                    'pickup_time' => $request->cab_pickup_time,
                    'b2b'         => (float)($request->exp_cab ?? 0),
                ],
                'boat' => [
                    'boat_type' => $request->boat_type,
                    'boat_ride' => $request->boat_ride,
                    'boat_date' => $request->boat_date,
                    'boat_time' => $request->boat_time,
                    'b2b'       => (float)($request->exp_boat ?? 0),
                ],
                'guide' => [
                    'guide_name' => $request->guide_name,
                    'guide_from' => $request->guide_from,
                    'guide_to'   => $request->guide_to,
                    'guide_lang' => $request->guide_lang,
                    'b2b'        => (float)($request->exp_guide ?? 0),
                ],
                'inclusions'   => $request->inclusions ?? '',
                'internal'     => $request->internal_notes,
                'b2b_total'    => $totalExpense,
                'profit'       => $net - $totalExpense,
                'package_name' => $request->package_name,
                'pickup_point' => $request->pickup_point,
                'drop_point'   => $request->drop_point,
                'infants'      => (int)($request->infants ?? 0),
            ], JSON_UNESCAPED_UNICODE);

            // Update lead
            $booking->lead->update([
                'guest_name'         => $request->guest_name,
                'contact'            => $request->phone,
                'alt_phone'          => $request->alt_phone,
                'email'              => $request->email,
                'country'            => $request->country,
                'state'              => $request->state,
                'city'               => $request->city,
                'pax'                => ($request->adults ?? 1) + ($request->children ?? 0),
                'short_plan'         => $request->trip_summary ?: $request->package_name,
                'lead_source_id'     => $request->lead_source_id,
                'booking_start_date' => $request->tour_start,
                'booking_end_date'   => $request->tour_end,
                'booking_status'     => $request->booking_status ?? $booking->lead->booking_status,
                'plan_detail'        => $request->itinerary,
                'total_amount'       => $net,
                'notes'              => $request->internal_notes,
            ]);

            // Update booking
            $booking->update([
                'total_amount'   => $net,
                'paid_amount'    => $advance,
                'pending_amount' => $balance,
            ]);

            // Store service JSON in quotation.notes (bookings table has no notes column)
            if ($booking->quotation) {
                $booking->quotation->update(['notes' => $serviceData]);
            }

            // Update quotation item price
            $item = $booking->quotation?->items?->first();
            if ($item) {
                $item->update(['unit_price' => $bookingAmount, 'total_price' => $bookingAmount]);
            }

            DB::commit();

            return redirect()->route('tour-booking.show', $id)
                ->with('success', '✅ Booking updated — ' . $booking->booking_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /** Show printable confirmation page */
    public function confirmation($id)
    {
        $booking = Booking::with(['lead', 'quotation.items', 'payments'])
            ->withSum('payments', 'amount')
            ->findOrFail($id);

        return view('admin.bookings.tour-confirmation', compact('booking'), [
            'page_title' => 'Tour Booking Confirmation',
        ]);
    }

    /** Download PDF confirmation */
    public function downloadPdf($id)
    {
        $booking = Booking::with(['lead', 'quotation.items', 'payments'])
            ->withSum('payments', 'amount')
            ->findOrFail($id);

        $pdf = \PDF::loadView('admin.bookings.tour-confirmation-pdf', compact('booking'));
        $pdf->getDomPDF()->getOptions()->setIsRemoteEnabled(true);

        $filename = 'Tour-Booking-' . ($booking->booking_number ?? $id) . '.pdf';
        return $pdf->download($filename);
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'      => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'alt_phone'       => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'country'         => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'city'            => 'nullable|string|max:100',
            'lead_source_id'  => 'required|exists:lead_sources,id',
            'package_name'    => 'required|string|max:255',
            'tour_start'      => 'required|date',
            'tour_end'        => 'nullable|date',
            'adults'          => 'nullable|integer|min:1',
            'children'        => 'nullable|integer|min:0',
            'infants'         => 'nullable|integer|min:0',
            'pickup_point'    => 'nullable|string|max:255',
            'drop_point'      => 'nullable|string|max:255',
            'booking_amount'  => 'required|numeric|min:0',
            'advance_paid'    => 'nullable|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0',
            'payment_status'     => 'nullable|string',
            'payment_method'     => 'nullable|string',
            'payment_account_id' => 'nullable|exists:payment_accounts,id',
            'reference_number'   => 'nullable|string|max:100',
            'is_gst_invoice'     => 'nullable|boolean',
            'gst_rate'           => 'nullable|numeric|min:0|max:28',
            'gst_amount'         => 'nullable|numeric|min:0',
            'customer_gstin'     => 'nullable|string|max:20',
            'booking_status'     => 'nullable|string|in:confirm,pending,inquiry,cancelled',
            'inclusions'      => 'nullable|string|max:1000',
            'trip_summary'    => 'nullable|string',
            'itinerary'       => 'nullable|string',
            'internal_notes'  => 'nullable|string',
            // Expenses
            'hotels'          => 'nullable|array',
            'hotels.*.city'      => 'nullable|string|max:100',
            'hotels.*.name'      => 'nullable|string|max:255',
            'hotels.*.room_type' => 'nullable|string|max:100',
            'hotels.*.checkin'   => 'nullable|date',
            'hotels.*.checkout'  => 'nullable|date',
            'hotels.*.b2b'       => 'nullable|numeric|min:0',
            'exp_hotel'          => 'nullable|numeric|min:0',
            'exp_cab'            => 'nullable|numeric|min:0',
            'exp_boat'           => 'nullable|numeric|min:0',
            'exp_guide'          => 'nullable|numeric|min:0',
            // Cab / driver
            'driver_name'        => 'nullable|string|max:255',
            'cab_pickup_time'    => 'nullable|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            $bookingAmount = (float) $request->booking_amount;
            $discount      = (float) ($request->discount ?? 0);
            $advance       = (float) ($request->advance_paid ?? 0);
            $net           = max(0, $bookingAmount - $discount);
            $balance       = max(0, $net - $advance);

            // Build short plan
            $inclusions = trim($request->inclusions ?? '');
            $adults     = max(1, (int)($request->adults   ?? 1));
            $children   = max(0, (int)($request->children ?? 0));
            if ($request->trip_summary) {
                $shortPlan = $request->trip_summary;
            } else {
                $parts = [$request->package_name];
                $parts[] = 'Adults: ' . $adults . ($children ? ', Children: ' . $children : '');
                if ($inclusions) $parts[] = 'Incl: ' . $inclusions;
                $shortPlan = implode(' | ', $parts);
            }

            // Build hotel entries from multi-row array
            $hotelsArr = $request->hotels ?? [];
            $hotelB2b  = 0;
            $hotelRows = [];
            foreach ($hotelsArr as $h) {
                if (empty($h['name']) && empty($h['city'])) continue;
                $nights = 0;
                if (!empty($h['checkin']) && !empty($h['checkout'])) {
                    $nights = max(0, (int)\Carbon\Carbon::parse($h['checkin'])->diffInDays(\Carbon\Carbon::parse($h['checkout'])));
                }
                $b2b = (float)($h['b2b'] ?? 0);
                $hotelB2b += $b2b;
                $hotelRows[] = [
                    'city'      => $h['city']      ?? '',
                    'name'      => $h['name']      ?? '',
                    'room_type' => $h['room_type'] ?? '',
                    'checkin'   => $h['checkin']   ?? '',
                    'checkout'  => $h['checkout']  ?? '',
                    'nights'    => $nights,
                    'b2b'       => $b2b,
                ];
            }
            // Fallback: use flat exp_hotel if no array rows
            if (empty($hotelRows)) {
                $hotelB2b = (float)($request->exp_hotel ?? 0);
            }
            $totalExpense = $hotelB2b
                          + (float)($request->exp_cab   ?? 0)
                          + (float)($request->exp_boat  ?? 0)
                          + (float)($request->exp_guide ?? 0);

            // Build full service details JSON
            $serviceJson = json_encode([
                'hotels' => $hotelRows,
                'hotel' => ['hotel_name' => $hotelRows[0]['name'] ?? '', 'hotel_checkin' => $hotelRows[0]['checkin'] ?? '', 'hotel_checkout' => $hotelRows[0]['checkout'] ?? '', 'hotel_nights' => $hotelRows[0]['nights'] ?? 1, 'b2b' => $hotelB2b],
                'cab'   => ['cab_type' => $request->cab_type, 'cab_route' => $request->cab_route, 'cab_from' => $request->cab_from, 'cab_to' => $request->cab_to, 'driver_name' => $request->driver_name, 'pickup_time' => $request->cab_pickup_time, 'b2b' => (float)($request->exp_cab ?? 0)],
                'boat'  => ['boat_type' => $request->boat_type, 'boat_ride' => $request->boat_ride, 'boat_date' => $request->boat_date, 'boat_time' => $request->boat_time, 'b2b' => (float)($request->exp_boat ?? 0)],
                'guide' => ['guide_name' => $request->guide_name, 'guide_from' => $request->guide_from, 'guide_to' => $request->guide_to, 'guide_lang' => $request->guide_lang, 'b2b' => (float)($request->exp_guide ?? 0)],
                'inclusions'    => $inclusions,
                'internal'      => $request->internal_notes,
                'b2b_total'     => $totalExpense,
                'profit'        => $net - $totalExpense,
                'package_name'  => $request->package_name,
                'pickup_point'  => $request->pickup_point,
                'drop_point'    => $request->drop_point,
                'infants'       => (int)($request->infants ?? 0),
            ], JSON_UNESCAPED_UNICODE);

            // 1. Lead
            $lead = Lead::create([
                'guest_name'         => $request->guest_name,
                'contact'            => $request->phone,
                'alt_phone'          => $request->alt_phone,
                'email'              => $request->email,
                'country'            => $request->country,
                'state'              => $request->state,
                'city'               => $request->city,
                'pax'                => $adults + $children,
                'short_plan'         => $shortPlan,
                'lead_source_id'     => $request->lead_source_id,
                'booking_start_date' => $request->tour_start,
                'booking_end_date'   => $request->tour_end,
                'booking_status'     => $request->booking_status ?? 'confirm',
                'total_amount'       => $net,
                'plan_detail'        => $request->itinerary,
                'notes'              => $request->internal_notes,
                'added_by'           => auth('admin')->id(),
            ]);

            // 2. Quotation
            $quotation = Quotation::create([
                'lead_id'          => $lead->id,
                'quotation_number' => 'QT-' . time() . '-' . $lead->id,
                'quotation_date'   => now()->format('Y-m-d'),
                'valid_until'      => now()->addDays(7),
                'total_amount'     => $net,
                'discount_amount'  => $discount,
                'discount_type'    => 'fixed',
                'subtotal'         => $bookingAmount,
                'tax_amount'       => 0,
                'tax_rate'         => 0,
                'status'           => 'accepted',
                'notes'            => 'Tour Package — ' . $request->package_name,
                'itinerary_html'   => $request->itinerary,
                'created_by'       => auth('admin')->id(),
            ]);

            // 3. Quotation item — always use Tour Package service type (id=13)
            // Find the Tour Package service type by name (robust, not hardcoded ID)
            $tourServiceType = \App\Models\ServiceType::where('name', 'like', '%Tour%')->first();
            $tourTemplate    = $tourServiceType
                ? \App\Models\ServiceTemplate::where('service_type_id', $tourServiceType->id)->first()
                : \App\Models\ServiceTemplate::find(65);

            QuotationItem::create([
                'quotation_id'        => $quotation->id,
                'service_type_id'     => $tourServiceType?->id ?? 13,
                'service_template_id' => $tourTemplate?->id,
                'quantity'            => 1,
                'unit_price'          => $bookingAmount,
                'total_price'         => $bookingAmount,
                'service_date'        => $request->tour_start,
                'notes'               => $request->package_name,
            ]);

            // 4. Booking
            $isGst       = $request->boolean('is_gst_invoice');
            $gstRate     = $isGst ? (float)($request->gst_rate ?? 0) : 0;
            $gstAmount   = $isGst ? (float)($request->gst_amount ?? round($net * $gstRate / 100)) : 0;
            $taxableAmt  = $isGst ? $net : 0;

            $booking = Booking::create([
                'quotation_id'       => $quotation->id,
                'lead_id'            => $lead->id,
                'booking_number'     => 'BK-' . date('Ymd') . '-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT),
                'booking_date'       => now()->format('Y-m-d'),
                'booking_status'     => 'confirmed',
                'total_amount'       => $net,
                'paid_amount'        => $advance,
                'pending_amount'     => $balance,
                'discount_amount'    => $discount,
                'is_gst_invoice'     => $isGst ? 1 : 0,
                'gst_rate'           => $gstRate,
                'gst_amount'         => $gstAmount,
                'taxable_amount'     => $taxableAmt,
                'customer_gstin'     => $request->customer_gstin,
                'tax_amount'         => $gstAmount,
                'notes'              => $serviceJson,
                'created_by'         => auth('admin')->id(),
            ]);

            // Store service JSON in quotation.notes (bookings table has no notes column)
            $quotation->update(['is_converted' => true, 'booking_id' => $booking->id, 'notes' => $serviceJson]);

            // 5. Payment record
            if ($advance > 0) {
                BookingPayment::create([
                    'booking_id'         => $booking->id,
                    'payment_account_id' => $request->payment_account_id ?? PaymentAccount::first()?->id,
                    'payment_date'       => now()->format('Y-m-d'),
                    'amount'             => $advance,
                    'payment_method'     => $request->payment_method ?? 'cash',
                    'reference_number'   => $request->reference_number,
                    'received_by'        => auth('admin')->id(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('bookings.index', ['service_type' => 'tour', 'status' => 'confirmed'])
                ->with('success', '✅ Tour booking confirmed! ' . $booking->booking_number . ' — Use the 📄 or ⬇ buttons to print/download confirmation.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tour booking failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}
