@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <!-- Header Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i data-feather="credit-card" class="me-2"></i>
                                Payment Accounts
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('payment-accounts.create') }}" class="btn btn-light btn-sm">
                                <i data-feather="plus-circle"></i> Add Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card shadow-sm border-primary">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Total Balance</h6>
                            <h3 class="text-primary mb-0">₹{{ number_format($totalBalance, 2) }}</h3>
                            <small class="text-muted">Across all accounts</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-success">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Active Accounts</h6>
                            <h3 class="text-success mb-0">{{ $activeAccounts }}</h3>
                            <small class="text-muted">Currently in use</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Total Accounts</h6>
                            <h3 class="text-info mb-0">{{ $accounts->count() }}</h3>
                            <small class="text-muted">All payment methods</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounts Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i data-feather="list"></i> All Accounts</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Account Name</th>
                                    <th width="15%">Type</th>
                                    <th width="15%" class="text-end">Initial Balance</th>
                                    <th width="15%" class="text-end">Current Balance</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="15%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $index => $account)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $account->account_name }}</strong><br>
                                        @if($account->account_number)
                                            <small class="text-muted">
                                                <i data-feather="hash" style="width: 12px; height: 12px;"></i>
                                                {{ $account->account_number }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($account->account_type == 'cash') bg-success
                                            @elseif($account->account_type == 'bank_transfer') bg-primary
                                            @elseif($account->account_type == 'upi') bg-info
                                            @elseif($account->account_type == 'card') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ $account->formatted_type }}
                                        </span>
                                    </td>
                                    <td class="text-end">₹{{ number_format($account->initial_balance, 2) }}</td>
                                    <td class="text-end">
                                        <strong class="text-primary">₹{{ number_format($account->current_balance, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($account->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('payment-accounts.show', $account->id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           data-bs-toggle="tooltip" title="View Details">
                                            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <a href="{{ route('payment-accounts.edit', $account->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <form action="{{ route('payment-accounts.toggle-status', $account->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-{{ $account->is_active ? 'warning' : 'success' }}" 
                                                    data-bs-toggle="tooltip" 
                                                    title="{{ $account->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i data-feather="{{ $account->is_active ? 'toggle-right' : 'toggle-left' }}" 
                                                   style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i data-feather="inbox" class="mb-2"></i>
                                        <p class="mb-0">No payment accounts found</p>
                                        <a href="{{ route('payment-accounts.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i data-feather="plus-circle"></i> Create First Account
                                        </a>
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
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        feather.replace();
    });
</script>
@endsection
