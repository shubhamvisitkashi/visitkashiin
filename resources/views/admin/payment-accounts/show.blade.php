@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">
                        <i data-feather="credit-card"></i> {{ $account->account_name }}
                    </h3>
                    <p class="text-muted mb-0">Payment Account Details</p>
                </div>
                <div>
                    <a href="{{ route('payment-accounts.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="arrow-left"></i> Back to Accounts
                    </a>
                    <a href="{{ route('payment-accounts.edit', $account->id) }}" class="btn btn-primary">
                        <i data-feather="edit"></i> Edit Account
                    </a>
                </div>
            </div>

            <!-- Account Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i data-feather="info"></i> Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Account Name:</th>
                                    <td><strong>{{ $account->account_name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Account Type:</th>
                                    <td>
                                        <span class="badge 
                                            @if($account->account_type == 'cash') bg-success
                                            @elseif($account->account_type == 'bank_transfer') bg-primary
                                            @elseif($account->account_type == 'upi') bg-info
                                            @elseif($account->account_type == 'card') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $account->account_type)) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($account->account_number)
                                <tr>
                                    <th>Account Number:</th>
                                    <td>{{ $account->account_number }}</td>
                                </tr>
                                @endif
                                @if($account->bank_name)
                                <tr>
                                    <th>Bank Name:</th>
                                    <td>{{ $account->bank_name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($account->branch_name)
                                <tr>
                                    <th width="40%">Branch:</th>
                                    <td>{{ $account->branch_name }}</td>
                                </tr>
                                @endif
                                @if($account->ifsc_code)
                                <tr>
                                    <th>IFSC Code:</th>
                                    <td>{{ $account->ifsc_code }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Initial Balance:</th>
                                    <td><strong>₹{{ number_format($account->initial_balance, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Current Balance:</th>
                                    <td><h4 class="text-primary mb-0">₹{{ number_format($account->current_balance, 2) }}</h4></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($account->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if($account->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Notes:</h6>
                            <p class="text-muted">{{ $account->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i data-feather="activity"></i> Transaction History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Date</th>
                                    <th width="10%">Type</th>
                                    <th width="10%">Method</th>
                                    <th width="33%">Reference</th>
                                    <th width="15%" class="text-end">Amount</th>
                                    <th width="15%" class="text-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $runningBalance = $account->initial_balance;
                                @endphp
                                @forelse($transactions as $index => $transaction)
                                    @php
                                        $runningBalance += $transaction['amount'];
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}
                                            @if($transaction['time'])
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($transaction['time'])->format('h:i A') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $transaction['type'] == 'Incoming' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $transaction['type'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucwords(str_replace('_', ' ', $transaction['payment_method'])) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $transaction['reference'] }}</small>
                                        </td>
                                        <td class="text-end">
                                            <strong class="{{ $transaction['amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction['amount'] >= 0 ? '+' : '' }}₹{{ number_format(abs($transaction['amount']), 2) }}
                                            </strong>
                                        </td>
                                        <td class="text-end">
                                            <strong>₹{{ number_format($runningBalance, 2) }}</strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i data-feather="inbox" class="mb-2"></i>
                                            <p class="mb-0">No transactions found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
@endsection
