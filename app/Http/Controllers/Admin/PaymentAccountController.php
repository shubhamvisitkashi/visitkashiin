<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentAccount;
use Illuminate\Support\Facades\Auth;

class PaymentAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentAccount::query();

        if ($request->filled('type')) {
            $query->where('account_type', $request->type);
        }

        if ($request->filled('status')) {
            $status = $request->status === 'active' ? true : false;
            $query->where('is_active', $status);
        }

        $accounts = $query->latest()->get();
        
        // Calculate totals
        $totalBalance = $accounts->sum('current_balance');
        $activeAccounts = $accounts->where('is_active', true)->count();

        return view('admin.payment-accounts.index', compact('accounts', 'totalBalance', 'activeAccounts'), 
            ['page_title' => 'Payment Accounts']);
    }

    public function create()
    {
        return view('admin.payment-accounts.create', ['page_title' => 'Add Payment Account']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'initial_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['current_balance'] = $validated['initial_balance'];
        $validated['is_active'] = true;

        PaymentAccount::create($validated);

        return redirect()->route('payment-accounts.index')
            ->with('success', 'Payment account created successfully.');
    }

    public function show($id)
    {
        $account = PaymentAccount::with(['payments.booking.lead', 'vendorPayments.serviceProvider'])->findOrFail($id);
        $transactions = $account->getAllTransactions();
        
        return view('admin.payment-accounts.show', compact('account', 'transactions'), [
            'page_title' => 'Account Details'
        ]);
    }

    public function edit($id)
    {
        $account = PaymentAccount::findOrFail($id);
        
        return view('admin.payment-accounts.edit', compact('account'), 
            ['page_title' => 'Edit Payment Account']);
    }

    public function update(Request $request, $id)
    {
        $account = PaymentAccount::findOrFail($id);

        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'initial_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Recalculate current balance if initial balance changed
        if ($account->initial_balance != $validated['initial_balance']) {
            $totalPayments = $account->payments()->sum('amount');
            $validated['current_balance'] = $validated['initial_balance'] + $totalPayments;
        }

        $account->update($validated);

        return redirect()->route('payment-accounts.index')
            ->with('success', 'Payment account updated successfully.');
    }

    public function toggleStatus($id)
    {
        $account = PaymentAccount::findOrFail($id);
        $account->is_active = !$account->is_active;
        $account->save();

        $status = $account->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Account {$status} successfully.");
    }

    public function destroy($id)
    {
        $account = PaymentAccount::findOrFail($id);
        
        if ($account->payments()->count() > 0) {
            return back()->with('error', 'Cannot delete account with existing transactions.');
        }

        $account->delete();

        return redirect()->route('payment-accounts.index')
            ->with('success', 'Payment account deleted successfully.');
    }

    /**
     * Get payment accounts by payment type (for AJAX)
     */
    public function getByType($type)
    {
        $accounts = PaymentAccount::where('account_type', $type)
            ->where('is_active', true)
            ->get(['id', 'account_name', 'current_balance as balance']);

        return response()->json($accounts);
    }
}
