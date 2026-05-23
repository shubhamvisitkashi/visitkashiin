@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i data-feather="edit" class="me-2"></i>
                                {{ $page_title }}
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('payment-accounts.index') }}" class="btn btn-light btn-sm">
                                <i data-feather="arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payment-accounts.update', $account->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Account Name -->
                            <div class="col-md-6">
                                <label for="account_name" class="form-label fw-semibold">
                                    Account Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="account_name" 
                                       class="form-control @error('account_name') is-invalid @enderror" 
                                       name="account_name" 
                                       value="{{ old('account_name', $account->account_name) }}" 
                                       required>
                                @error('account_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Account Type -->
                            <div class="col-md-6">
                                <label for="account_type" class="form-label fw-semibold">
                                    Account Type <span class="text-danger">*</span>
                                </label>
                                <select id="account_type" 
                                        class="form-select @error('account_type') is-invalid @enderror" 
                                        name="account_type" 
                                        onchange="toggleBankFields()" 
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="cash" {{ old('account_type', $account->account_type) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('account_type', $account->account_type) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="upi" {{ old('account_type', $account->account_type) == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="card" {{ old('account_type', $account->account_type) == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="cheque" {{ old('account_type', $account->account_type) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="other" {{ old('account_type', $account->account_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('account_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Account Number -->
                            <div class="col-md-6">
                                <label for="account_number" class="form-label fw-semibold">
                                    Account Number / UPI ID
                                </label>
                                <input type="text" id="account_number" 
                                       class="form-control @error('account_number') is-invalid @enderror" 
                                       name="account_number" 
                                       value="{{ old('account_number', $account->account_number) }}">
                                @error('account_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Initial Balance -->
                            <div class="col-md-6">
                                <label for="initial_balance" class="form-label fw-semibold">
                                    Initial Balance <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" id="initial_balance" 
                                       class="form-control @error('initial_balance') is-invalid @enderror" 
                                       name="initial_balance" 
                                       value="{{ old('initial_balance', $account->initial_balance) }}" 
                                       required>
                                <small class="text-muted">Current balance will be recalculated if changed</small>
                                @error('initial_balance')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Bank Details Section -->
                            <div class="col-12" id="bank-details-section">
                                <hr>
                                <h6 class="text-primary mb-3">
                                    <i data-feather="building"></i> Bank Details
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="bank_name" class="form-label fw-semibold">Bank Name</label>
                                        <input type="text" id="bank_name" 
                                               class="form-control @error('bank_name') is-invalid @enderror" 
                                               name="bank_name" 
                                               value="{{ old('bank_name', $account->bank_name) }}">
                                        @error('bank_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="branch_name" class="form-label fw-semibold">Branch Name</label>
                                        <input type="text" id="branch_name" 
                                               class="form-control @error('branch_name') is-invalid @enderror" 
                                               name="branch_name" 
                                               value="{{ old('branch_name', $account->branch_name) }}">
                                        @error('branch_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ifsc_code" class="form-label fw-semibold">IFSC Code</label>
                                        <input type="text" id="ifsc_code" 
                                               class="form-control @error('ifsc_code') is-invalid @enderror" 
                                               name="ifsc_code" 
                                               value="{{ old('ifsc_code', $account->ifsc_code) }}">
                                        @error('ifsc_code')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold">Notes</label>
                                <textarea id="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          name="notes" 
                                          rows="3">{{ old('notes', $account->notes) }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> Update Account
                            </button>
                            <a href="{{ route('payment-accounts.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleBankFields() {
        const accountType = document.getElementById('account_type').value;
        const bankSection = document.getElementById('bank-details-section');
        
        if (accountType === 'bank_transfer' || accountType === 'cheque') {
            bankSection.style.display = 'block';
        } else {
            bankSection.style.display = 'none';
        }
        
        feather.replace();
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleBankFields();
        feather.replace();
    });
</script>
@endsection
