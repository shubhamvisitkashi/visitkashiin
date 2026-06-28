<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view|analytics-profit');
    }

    public function store(Request $request)
    {
        $this->validateExpense($request);

        Expense::create([
            'type'         => $request->type,
            'category'     => $request->category,
            'staff_id'     => $request->staff_id,
            'party_name'   => $request->party_name,
            'amount'       => $request->amount,
            'expense_date' => $request->expense_date,
            'notes'        => $request->notes,
            'created_by'   => auth('admin')->id(),
        ]);

        return back()->with('success', 'Expense recorded.');
    }

    public function update(Request $request, Expense $expense)
    {
        $this->validateExpense($request);

        $expense->update([
            'type'         => $request->type,
            'category'     => $request->category,
            'staff_id'     => $request->staff_id,
            'party_name'   => $request->party_name,
            'amount'       => $request->amount,
            'expense_date' => $request->expense_date,
            'notes'        => $request->notes,
        ]);

        return back()->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted.');
    }

    private function validateExpense(Request $request): void
    {
        $category   = strtolower($request->category ?? '');
        $isSalary   = str_contains($category, 'salary');
        $needsParty = str_contains($category, 'hotel');

        $request->validate([
            'type'         => 'required|in:' . implode(',', array_keys(Expense::TYPES)),
            'category'     => 'required|string|max:100',
            'staff_id'     => ($isSalary ? 'required' : 'nullable') . '|exists:admins,id',
            'party_name'   => ($needsParty ? 'required' : 'nullable') . '|string|max:150',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string|max:1000',
        ]);
    }
}
