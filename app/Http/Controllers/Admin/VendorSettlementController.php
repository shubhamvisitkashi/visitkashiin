<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Models\VendorPayment;
use App\Models\BookingServiceAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorSettlementController extends Controller
{
    /**
     * Display vendor settlement dashboard
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::vendors()
            ->with(['serviceAssignments', 'vendorPayments'])
            ->withCount('serviceAssignments');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereHas('serviceAssignments');
            } elseif ($request->status === 'settled') {
                $query->whereDoesntHave('serviceAssignments')
                      ->whereHas('vendorPayments');
            }
        }

        $vendors = $query->orderBy('name')->paginate(15);

        // Calculate summary stats
        $totalVendors = ServiceProvider::vendors()->count();
        $totalOutstanding = ServiceProvider::vendors()->get()->sum('outstanding_balance');
        $totalPaid = VendorPayment::sum('amount');

        return view('admin.vendor-settlements.index', compact('vendors', 'totalVendors', 'totalOutstanding', 'totalPaid'), [
            'page_title' => 'Vendor Settlements'
        ]);
    }

    /**
     * Display vendor details with assignments and payments
     */
    public function show($id)
    {
        $vendor = ServiceProvider::with([
            'serviceAssignments.booking.lead',
            'serviceAssignments.quotationItem.serviceTemplate',
            'vendorPayments.recordedBy'
        ])->findOrFail($id);

        return view('admin.vendor-settlements.show', compact('vendor'), [
            'page_title' => 'Vendor Settlement - ' . $vendor->name
        ]);
    }

    /**
     * Add payment to vendor
     */
    public function addPayment(Request $request, $id)
    {
        $vendor = ServiceProvider::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_time' => 'nullable|date_format:H:i',
            'payment_method' => 'required|in:cash,bank_transfer,upi,cheque',
            'payment_account_id' => 'required|exists:payment_accounts,id',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        VendorPayment::create([
            'service_provider_id' => $vendor->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_time' => $validated['payment_time'] ?? null,
            'payment_method' => $validated['payment_method'],
            'payment_account_id' => $validated['payment_account_id'],
            'payment_type' => 'settlement',
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
            'recorded_by' => auth('admin')->id(),
        ]);

        return back()->with('success', 'Payment recorded successfully. Account balance updated.');
    }

    /**
     * Get payment accounts filtered by payment method
     */
    public function getAccountsByMethod(Request $request)
    {
        $method = $request->get('method');
        $accounts = \App\Models\PaymentAccount::where('payment_type', $method)
            ->where('is_active', true)
            ->get(['id', 'account_name', 'balance']);

        return response()->json($accounts);
    }
}
