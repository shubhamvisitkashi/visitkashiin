@extends('admin.layouts.app')

@section('content')
<style>
    .badge-in  { background: #d1fae5; color: #065f46; }
    .badge-out { background: #fee2e2; color: #991b1b; }
    .summary-card { border-radius: 10px; padding: 1.2rem 1.5rem; }
    .summary-card .value { font-size: 1.5rem; font-weight: 700; }
    .summary-card .label { font-size: 0.8rem; color: #6b7280; }
</style>

<div class="page-content">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">Ledger</h4>
                <small class="text-muted">All money in &amp; out across all booking types</small>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-3">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('ledger.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label mb-1">From</label>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                               value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">To</label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                               value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Account</label>
                        <select name="account_id" class="form-select form-select-sm">
                            <option value="">All Accounts</option>
                            @foreach ($accounts as $acc)
                                <option value="{{ $acc->id }}" @selected($accountId == $acc->id)>{{ $acc->account_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="in"  @selected($typeFilter === 'in')>IN only</option>
                            <option value="out" @selected($typeFilter === 'out')>OUT only</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Apply</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary --}}
        <div class="row mb-3 g-3">
            <div class="col-md-4">
                <div class="summary-card bg-success bg-opacity-10">
                    <div class="label">Total IN</div>
                    <div class="value text-success">₹{{ number_format($totalIn, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card bg-danger bg-opacity-10">
                    <div class="label">Total OUT</div>
                    <div class="value text-danger">₹{{ number_format($totalOut, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card {{ $net >= 0 ? 'bg-primary bg-opacity-10' : 'bg-warning bg-opacity-10' }}">
                    <div class="label">Net</div>
                    <div class="value {{ $net >= 0 ? 'text-primary' : 'text-warning' }}">
                        ₹{{ number_format(abs($net), 2) }}
                        <small class="fs-6 fw-normal">{{ $net >= 0 ? 'surplus' : 'deficit' }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Reference</th>
                                <th>Method</th>
                                <th>Account</th>
                                <th class="text-end">Amount (₹)</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $txn)
                                <tr>
                                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($txn['date'])->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $txn['direction'] === 'in' ? 'badge-in' : 'badge-out' }}">
                                            {{ strtoupper($txn['direction']) }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">{{ $txn['source_type'] }}</td>
                                    <td>{{ $txn['reference'] }}</td>
                                    <td>{{ $txn['method'] ?? '—' }}</td>
                                    <td>{{ $txn['account'] }}</td>
                                    <td class="text-end fw-semibold {{ $txn['direction'] === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $txn['direction'] === 'out' ? '−' : '+' }}{{ number_format($txn['amount'], 2) }}
                                    </td>
                                    <td class="text-muted small">{{ $txn['notes'] ?? '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No transactions found for the selected period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
