<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CabBooking;
use App\Models\CabBookingPayment;
use App\Models\Vehicle;
use App\Models\LeadSource;
use App\Models\PaymentAccount;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CabBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // ── INDEX ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']);
        $userId  = auth('admin')->id();

        $query = CabBooking::with(['vehicle', 'createdBy'])
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $userId))
            ->search($request->search)
            ->when($request->status,         fn($q, $s) => $q->byStatus($s))
            ->when($request->payment_status, fn($q, $s) => $q->byPayment($s))
            ->when($request->trip_type,      fn($q, $t) => $q->where('trip_type', $t))
            ->when($request->vehicle_name,   fn($q, $v) => $q->where('vehicle_name', 'like', "%$v%"))
            ->when($request->date_from,      fn($q, $d) => $q->where('pickup_date', '>=', $d))
            ->when($request->date_to,        fn($q, $d) => $q->where('pickup_date', '<=', $d))
            ->when($isAdmin && $request->staff_id, fn($q, $s) => $q->where('created_by', $s))
            ->latest('pickup_date')
            ->paginate(15)
            ->withQueryString();

        $statsBase = CabBooking::when(!$isAdmin, fn($q) => $q->where('created_by', $userId));
        $stats = [
            'total'           => (clone $statsBase)->count(),
            'today'           => (clone $statsBase)->whereDate('pickup_date', today())->count(),
            'pending'         => (clone $statsBase)->where('booking_status', 'pending')->count(),
            'confirmed'       => (clone $statsBase)->where('booking_status', 'confirmed')->count(),
            'completed'       => (clone $statsBase)->where('booking_status', 'completed')->count(),
            'cancelled'       => (clone $statsBase)->where('booking_status', 'cancelled')->count(),
            'revenue'         => (clone $statsBase)->where('booking_status', '!=', 'cancelled')->sum('total_amount'),
            'pending_payment' => (clone $statsBase)->where('payment_status', '!=', 'paid')->where('booking_status', '!=', 'cancelled')->sum('pending_amount'),
        ];

        $staffList = $isAdmin ? Admin::select('id', 'name')->orderBy('name')->get() : collect();

        return view('admin.cab-bookings.index', compact('query', 'stats', 'staffList', 'isAdmin'), [
            'page_title' => 'Cab Bookings',
        ]);
    }

    // ── CREATE ────────────────────────────────────────────────────
    public function create()
    {
        $vehicles        = Vehicle::active()->orderBy('category')->orderBy('seating_capacity')->get();
        $leadSources     = LeadSource::orderBy('name')->get();
        $paymentAccounts = PaymentAccount::where('is_active', 1)->orderBy('account_name')->get();

        return view('admin.cab-bookings.create', compact('vehicles', 'leadSources', 'paymentAccounts'), [
            'page_title' => 'New Cab Booking',
        ]);
    }

    // ── STORE ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'      => 'required|string|max:255',
            'customer_phone'     => 'required|string|max:20',
            'customer_alt_phone' => 'nullable|string|max:20',
            'customer_email'     => 'nullable|email|max:255',
            'gst_number'         => 'nullable|string|max:20',
            'pickup_address'     => 'required|string',
            'drop_address'       => 'required|string',
            'trip_type'          => 'required|string|max:255',
            'pickup_date'        => 'required|date',
            'pickup_time'        => 'required|string',
            'return_date'        => 'nullable|date',
            'total_days'         => 'required|integer|min:1',
            'total_km'              => 'nullable|numeric|min:0',
            'vehicle_id'            => 'nullable|exists:vehicles,id',
            'vehicle_name'          => 'required|string|max:255',
            'seating_capacity'      => 'nullable|integer|min:1',
            'vehicle_count'         => 'nullable|integer|min:1',
            'no_of_adults'          => 'nullable|integer|min:0',
            'no_of_children'        => 'nullable|integer|min:0',
            'carrier_on_roof'       => 'nullable|boolean',
            'child_seat'            => 'nullable|boolean',
            'wheelchair_accessible' => 'nullable|boolean',
            'ac_required'           => 'nullable|boolean',
            'luggage_details'       => 'nullable|string|max:255',
            'flight_train_number'   => 'nullable|string|max:50',
            'base_fare'             => 'required|numeric|min:0',
            'driver_allowance'      => 'nullable|numeric|min:0',
            'toll_tax'              => 'nullable|numeric|min:0',
            'parking'               => 'nullable|numeric|min:0',
            'state_tax'             => 'nullable|numeric|min:0',
            'night_charges'         => 'nullable|numeric|min:0',
            'extra_km_charges'      => 'nullable|numeric|min:0',
            'vendor_cost'           => 'nullable|numeric|min:0',
            'discount'              => 'nullable|numeric|min:0',
            'advance_paid'          => 'nullable|numeric|min:0',
            'payment_method'        => 'required|string|in:cash,upi,bank_transfer,card,cheque',
            'payment_account_id'    => 'nullable|exists:payment_accounts,id',
            'cash_receiver_name'    => 'nullable|string|max:150',
            'booking_status'        => 'nullable|in:pending,confirmed,assigned,completed,cancelled',
            'lead_source_id'        => 'nullable|exists:lead_sources,id',
            'notes'                 => 'nullable|string',
            'legs'                    => 'nullable|array',
            'legs.*.leg_date'         => 'required_with:legs|date',
            'legs.*.pickup_address'   => 'nullable|string',
            'legs.*.drop_address'     => 'nullable|string',
            'legs.*.fare'             => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $vehicleCount = max(1, (int)($validated['vehicle_count'] ?? 1));

            $subtotal = array_sum(array_map(fn($k) => (float)($validated[$k] ?? 0), [
                'base_fare', 'driver_allowance', 'toll_tax', 'parking',
                'state_tax', 'night_charges', 'extra_km_charges',
            ])) * $vehicleCount;

            $discount      = (float)($validated['discount']     ?? 0);
            $totalAmount   = max(0, $subtotal - $discount);
            $advancePaid   = (float)($validated['advance_paid'] ?? 0);
            $pendingAmount = max(0, $totalAmount - $advancePaid);

            $paymentStatus = 'unpaid';
            if ($advancePaid > 0 && $advancePaid >= $totalAmount) $paymentStatus = 'paid';
            elseif ($advancePaid > 0) $paymentStatus = 'partial';

            $booking = CabBooking::create([
                'booking_number'        => CabBooking::generateNumber(),
                'customer_name'         => $validated['customer_name'],
                'customer_phone'        => $validated['customer_phone'],
                'customer_alt_phone'    => $validated['customer_alt_phone']    ?? null,
                'customer_email'        => $validated['customer_email']        ?? null,
                'gst_number'            => $validated['gst_number']            ?? null,
                'pickup_address'        => $validated['pickup_address'],
                'drop_address'          => $validated['drop_address'],
                'trip_type'             => $validated['trip_type'],
                'pickup_date'           => $validated['pickup_date'],
                'pickup_time'           => $validated['pickup_time'],
                'return_date'           => $validated['return_date']           ?? null,
                'total_days'            => $validated['total_days'],
                'total_km'              => $validated['total_km']              ?? null,
                'vehicle_id'            => $validated['vehicle_id']            ?? null,
                'vehicle_name'          => $validated['vehicle_name'],
                'seating_capacity'      => $validated['seating_capacity']      ?? null,
                'vehicle_count'         => $vehicleCount,
                'no_of_adults'          => $validated['no_of_adults']          ?? 1,
                'no_of_children'        => $validated['no_of_children']        ?? 0,
                'carrier_on_roof'       => !empty($validated['carrier_on_roof']),
                'child_seat'            => !empty($validated['child_seat']),
                'wheelchair_accessible' => !empty($validated['wheelchair_accessible']),
                'ac_required'           => isset($validated['ac_required']) ? (bool)$validated['ac_required'] : true,
                'luggage_details'       => $validated['luggage_details']       ?? null,
                'flight_train_number'   => $validated['flight_train_number']   ?? null,
                'base_fare'             => $validated['base_fare'],
                'driver_allowance'      => $validated['driver_allowance']      ?? 0,
                'toll_tax'              => $validated['toll_tax']              ?? 0,
                'parking'               => $validated['parking']               ?? 0,
                'state_tax'             => $validated['state_tax']             ?? 0,
                'night_charges'         => $validated['night_charges']         ?? 0,
                'extra_km_charges'      => $validated['extra_km_charges']      ?? 0,
                'vendor_cost'           => !empty($validated['vendor_cost']) ? (float)$validated['vendor_cost'] : null,
                'discount'              => $discount,
                'total_amount'          => $totalAmount,
                'advance_paid'          => $advancePaid,
                'pending_amount'        => $pendingAmount,
                'booking_status'        => $validated['booking_status']        ?? 'confirmed',
                'payment_status'        => $paymentStatus,
                'lead_source_id'        => $validated['lead_source_id']        ?? null,
                'created_by'            => auth('admin')->id(),
                'notes'                 => $validated['notes']                 ?? null,
            ]);

            if ($advancePaid > 0) {
                $isCash = ($validated['payment_method'] ?? 'cash') === 'cash';
                CabBookingPayment::create([
                    'cab_booking_id'     => $booking->id,
                    'amount'             => $advancePaid,
                    'payment_method'     => $validated['payment_method'] ?? 'cash',
                    'cash_receiver_name' => $isCash ? ($validated['cash_receiver_name'] ?? null) : null,
                    'payment_date'       => now()->format('Y-m-d'),
                    'payment_account_id' => $isCash ? null : ($validated['payment_account_id'] ?? null),
                    'received_by'        => auth('admin')->id(),
                ]);
            }

            foreach ($validated['legs'] ?? [] as $i => $leg) {
                $booking->legs()->create([
                    'sequence'       => $i,
                    'leg_date'       => $leg['leg_date'],
                    'pickup_address' => $leg['pickup_address'] ?? null,
                    'drop_address'   => $leg['drop_address']   ?? null,
                    'fare'           => $leg['fare']           ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('cab-bookings.show', $booking->id)
                ->with('success', 'Cab booking created! ID: ' . $booking->booking_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cab booking store failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    // ── SHOW ──────────────────────────────────────────────────────
    public function show($id)
    {
        $booking = CabBooking::with([
            'vehicle', 'payments.paymentAccount', 'createdBy', 'leadSource',
        ])->findOrFail($id);

        $paymentAccounts = PaymentAccount::where('is_active', 1)->orderBy('account_name')->get();

        return view('admin.cab-bookings.show', compact('booking', 'paymentAccounts'), [
            'page_title' => 'Cab Booking — ' . $booking->booking_number,
        ]);
    }

    // ── EDIT ──────────────────────────────────────────────────────
    public function edit($id)
    {
        $booking         = CabBooking::with(['vehicle', 'payments'])->findOrFail($id);
        $vehicles        = Vehicle::active()->orderBy('category')->orderBy('seating_capacity')->get();
        $leadSources     = LeadSource::orderBy('name')->get();
        $paymentAccounts = PaymentAccount::where('is_active', 1)->orderBy('account_name')->get();

        return view('admin.cab-bookings.edit', compact('booking', 'vehicles', 'leadSources', 'paymentAccounts'), [
            'page_title' => 'Edit Cab Booking',
        ]);
    }

    // ── UPDATE ────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $booking = CabBooking::findOrFail($id);

        $validated = $request->validate([
            'customer_name'      => 'required|string|max:255',
            'customer_phone'     => 'required|string|max:20',
            'customer_alt_phone' => 'nullable|string|max:20',
            'customer_email'     => 'nullable|email|max:255',
            'gst_number'         => 'nullable|string|max:20',
            'pickup_address'     => 'required|string',
            'drop_address'       => 'required|string',
            'trip_type'          => 'required|string|max:255',
            'pickup_date'        => 'required|date',
            'pickup_time'        => 'required|string',
            'return_date'        => 'nullable|date',
            'total_days'         => 'required|integer|min:1',
            'total_km'           => 'nullable|numeric|min:0',
            'vehicle_id'         => 'nullable|exists:vehicles,id',
            'vehicle_name'       => 'required|string|max:255',
            'vehicle_number'     => 'nullable|string|max:20',
            'seating_capacity'   => 'nullable|integer|min:1',
            'vehicle_count'      => 'nullable|integer|min:1',
            'no_of_adults'          => 'nullable|integer|min:0',
            'no_of_children'        => 'nullable|integer|min:0',
            'carrier_on_roof'       => 'nullable|boolean',
            'child_seat'            => 'nullable|boolean',
            'wheelchair_accessible' => 'nullable|boolean',
            'ac_required'           => 'nullable|boolean',
            'luggage_details'       => 'nullable|string|max:255',
            'flight_train_number'   => 'nullable|string|max:50',
            'base_fare'          => 'required|numeric|min:0',
            'driver_allowance'   => 'nullable|numeric|min:0',
            'toll_tax'           => 'nullable|numeric|min:0',
            'parking'            => 'nullable|numeric|min:0',
            'state_tax'          => 'nullable|numeric|min:0',
            'night_charges'      => 'nullable|numeric|min:0',
            'extra_km_charges'   => 'nullable|numeric|min:0',
            'vendor_cost'        => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'booking_status'     => 'nullable|in:pending,confirmed,assigned,completed,cancelled',
            'lead_source_id'     => 'nullable|exists:lead_sources,id',
            'notes'              => 'nullable|string',
        ]);

        $vehicleCount = max(1, (int)($validated['vehicle_count'] ?? 1));

        $subtotal = array_sum(array_map(fn($k) => (float)($validated[$k] ?? 0), [
            'base_fare', 'driver_allowance', 'toll_tax', 'parking',
            'state_tax', 'night_charges', 'extra_km_charges',
        ])) * $vehicleCount;

        $discount      = (float)($validated['discount'] ?? 0);
        $totalAmount   = max(0, $subtotal - $discount);
        $paidTotal     = $booking->payments()->sum('amount');
        $pendingAmount = max(0, $totalAmount - $paidTotal);

        $paymentStatus = 'unpaid';
        if ($paidTotal > 0 && $paidTotal >= $totalAmount) $paymentStatus = 'paid';
        elseif ($paidTotal > 0) $paymentStatus = 'partial';

        $booking->update(array_merge($validated, [
            'vendor_cost'           => !empty($validated['vendor_cost']) ? (float)$validated['vendor_cost'] : null,
            'discount'              => $discount,
            'vehicle_count'         => $vehicleCount,
            'total_amount'          => $totalAmount,
            'advance_paid'          => $paidTotal,
            'pending_amount'        => $pendingAmount,
            'payment_status'        => $paymentStatus,
            'no_of_adults'          => $validated['no_of_adults']          ?? 1,
            'no_of_children'        => $validated['no_of_children']        ?? 0,
            'carrier_on_roof'       => !empty($validated['carrier_on_roof']),
            'child_seat'            => !empty($validated['child_seat']),
            'wheelchair_accessible' => !empty($validated['wheelchair_accessible']),
            'ac_required'           => isset($validated['ac_required']) ? (bool)$validated['ac_required'] : true,
        ]));

        return redirect()->route('cab-bookings.show', $booking->id)
            ->with('success', 'Booking updated successfully!');
    }

    // ── ADD PAYMENT ───────────────────────────────────────────────
    public function addPayment(Request $request, $id)
    {
        $booking = CabBooking::findOrFail($id);

        $validated = $request->validate([
            'amount'             => 'required|numeric|min:1',
            'payment_method'     => 'required|string',
            'payment_date'       => 'required|date',
            'payment_account_id' => 'nullable|exists:payment_accounts,id',
            'cash_receiver_name' => 'nullable|string|max:150',
            'notes'              => 'nullable|string',
        ]);

        $isCash = $validated['payment_method'] === 'cash';

        CabBookingPayment::create([
            'cab_booking_id'     => $booking->id,
            'amount'             => $validated['amount'],
            'payment_method'     => $validated['payment_method'],
            'payment_date'       => $validated['payment_date'],
            'payment_account_id' => $validated['payment_account_id'] ?? null,
            'cash_receiver_name' => $isCash ? ($validated['cash_receiver_name'] ?? null) : null,
            'received_by'        => auth('admin')->id(),
            'notes'              => $validated['notes'] ?? null,
        ]);

        $booking->recalcPaymentStatus();

        return back()->with('success', '₹' . number_format($validated['amount'], 2) . ' payment recorded.');
    }

    // ── DELETE PAYMENT ────────────────────────────────────────────
    public function deletePayment($bookingId, $paymentId)
    {
        $booking = CabBooking::findOrFail($bookingId);
        CabBookingPayment::where('id', $paymentId)
            ->where('cab_booking_id', $bookingId)
            ->delete();
        $booking->recalcPaymentStatus();
        return back()->with('success', 'Payment removed.');
    }

    // ── UPDATE STATUS ─────────────────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        $booking = CabBooking::findOrFail($id);
        $validated = $request->validate([
            'booking_status'    => 'required|in:pending,confirmed,assigned,completed,cancelled',
            'cancel_reason'     => 'nullable|string|max:1000',
            'refund_amount'     => 'nullable|numeric|min:0',
            'non_refund_amount' => 'nullable|numeric|min:0',
        ]);

        $updateData = ['booking_status' => $validated['booking_status']];

        if ($validated['booking_status'] === 'cancelled') {
            $updateData['cancel_reason']     = $validated['cancel_reason'] ?? null;
            $updateData['refund_amount']     = $validated['refund_amount'] ?? 0;
            $updateData['non_refund_amount'] = $validated['non_refund_amount'] ?? 0;
            $updateData['cancelled_at']      = now();
        }

        $booking->update($updateData);
        return back()->with('success', 'Booking status updated to ' . ucfirst($validated['booking_status']) . '.');
    }

    // ── INVOICE ───────────────────────────────────────────────────
    public function invoice($id)
    {
        $booking = CabBooking::with([
            'vehicle', 'payments.paymentAccount', 'createdBy', 'leadSource',
        ])->findOrFail($id);

        $setup = \App\Models\WebsiteSetup::first();

        return view('admin.cab-bookings.invoice', compact('booking', 'setup'));
    }

    // ── DESTROY ───────────────────────────────────────────────────
    public function destroy($id)
    {
        $booking = CabBooking::findOrFail($id);

        // Staff can only delete their own bookings
        if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            if ($booking->created_by !== auth('admin')->id()) {
                abort(403, 'Unauthorized. You can only delete bookings you created.');
            }
        }

        $booking->delete();
        return redirect()->route('cab-bookings.index')->with('success', 'Booking deleted.');
    }
}
