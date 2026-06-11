<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingPayment;
use App\Models\CabBookingPayment;
use App\Models\BoatBookingPayment;
use App\Models\VendorPayment;
use App\Models\PaymentAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view|analytics-profit|payment-view|payment-account-view');
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $accountId  = $request->input('account_id');
        $typeFilter = $request->input('type'); // 'in', 'out', or null

        $transactions = collect();

        // --- Tour/Stay booking payments (IN) ---
        if (!$typeFilter || $typeFilter === 'in') {
            $bookingQ = BookingPayment::with(['booking.lead', 'paymentAccount'])
                ->whereBetween('payment_date', [$startDate, $endDate]);
            if ($accountId) $bookingQ->where('payment_account_id', $accountId);

            $bookingQ->get()->each(function ($p) use (&$transactions) {
                $transactions->push([
                    'date'           => $p->payment_date,
                    'time'           => $p->payment_time,
                    'direction'      => 'in',
                    'source_type'    => 'Tour/Stay Booking',
                    'reference'      => $p->booking ? ('BK-' . $p->booking->id . ' — ' . optional($p->booking->lead)->name) : '—',
                    'method'         => $p->payment_method,
                    'account'        => optional($p->paymentAccount)->account_name ?? 'Unassigned',
                    'account_id'     => $p->payment_account_id,
                    'amount'         => (float) $p->amount,
                    'notes'          => $p->notes,
                ]);
            });
        }

        // --- Cab booking payments (IN) ---
        if (!$typeFilter || $typeFilter === 'in') {
            $cabQ = CabBookingPayment::with(['cabBooking', 'paymentAccount'])
                ->whereBetween('payment_date', [$startDate, $endDate]);
            if ($accountId) $cabQ->where('payment_account_id', $accountId);

            $cabQ->get()->each(function ($p) use (&$transactions) {
                $transactions->push([
                    'date'           => $p->payment_date,
                    'time'           => null,
                    'direction'      => 'in',
                    'source_type'    => 'Cab Booking',
                    'reference'      => $p->cabBooking ? ('CAB-' . $p->cabBooking->id . ' — ' . $p->cabBooking->customer_name) : '—',
                    'method'         => $p->payment_method,
                    'account'        => optional($p->paymentAccount)->account_name ?? 'Unassigned',
                    'account_id'     => $p->payment_account_id,
                    'amount'         => (float) $p->amount,
                    'notes'          => $p->notes,
                ]);
            });
        }

        // --- Boat booking payments (IN, no account link) ---
        if ((!$typeFilter || $typeFilter === 'in') && !$accountId) {
            BoatBookingPayment::with('boatBooking')
                ->whereHas('boatBooking', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->get()->each(function ($p) use (&$transactions) {
                    $transactions->push([
                        'date'        => optional($p->boatBooking)->created_at?->toDateString(),
                        'time'        => null,
                        'direction'   => 'in',
                        'source_type' => 'Boat Booking',
                        'reference'   => $p->boatBooking ? ('BOAT-' . $p->boatBooking->id . ' — ' . $p->boatBooking->name) : '—',
                        'method'      => '—',
                        'account'     => 'Unassigned',
                        'account_id'  => null,
                        'amount'      => (float) $p->amount,
                        'notes'       => null,
                    ]);
                });
        }

        // --- Vendor payments (OUT) ---
        if (!$typeFilter || $typeFilter === 'out') {
            $vendorQ = VendorPayment::with(['serviceProvider', 'paymentAccount'])
                ->whereBetween('payment_date', [$startDate, $endDate]);
            if ($accountId) $vendorQ->where('payment_account_id', $accountId);

            $vendorQ->get()->each(function ($p) use (&$transactions) {
                $transactions->push([
                    'date'           => $p->payment_date,
                    'time'           => $p->payment_time,
                    'direction'      => 'out',
                    'source_type'    => 'Vendor Payment',
                    'reference'      => optional($p->serviceProvider)->name ?? '—',
                    'method'         => $p->payment_method,
                    'account'        => optional($p->paymentAccount)->account_name ?? 'Unassigned',
                    'account_id'     => $p->payment_account_id,
                    'amount'         => (float) $p->amount,
                    'notes'          => $p->notes,
                ]);
            });
        }

        $transactions = $transactions->sortBy('date')->values();

        $totalIn  = $transactions->where('direction', 'in')->sum('amount');
        $totalOut = $transactions->where('direction', 'out')->sum('amount');
        $net      = $totalIn - $totalOut;

        $accounts = PaymentAccount::active()->orderBy('account_name')->get();

        return view('admin.ledger.index', compact(
            'transactions',
            'totalIn',
            'totalOut',
            'net',
            'accounts',
            'startDate',
            'endDate',
            'accountId',
            'typeFilter'
        ), ['page_title' => 'Ledger']);
    }
}
