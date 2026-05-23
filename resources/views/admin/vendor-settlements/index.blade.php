@extends('admin.layouts.app')

@section('content')
<style>
    /* Enhanced Modern Design System */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --info-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .page-content {
        padding: 25px;
    }

    .page-title-section {
        margin-bottom: 25px;
    }

    .page-title-section h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 5px;
    }

    .page-title-section p {
        color: #6b7280;
        margin: 0;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow-hover);
    }

    .stat-card.danger::before {
        background: var(--danger-gradient);
    }

    .stat-card.success::before {
        background: var(--success-gradient);
    }

    .stat-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        position: relative;
    }

    .stat-icon-wrapper::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        background: inherit;
        filter: blur(10px);
        opacity: 0.5;
        z-index: -1;
    }

    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
    }

    .modern-input {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .modern-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .modern-btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .modern-btn-primary {
        background: var(--primary-gradient);
        color: white;
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .vendors-table-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px 25px;
        border-bottom: 2px solid #e5e7eb;
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: #f9fafb;
        border: none;
        padding: 18px 20px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
        transform: scale(1.01);
    }

    .modern-table tbody td {
        padding: 20px;
        vertical-align: middle;
    }

    .vendor-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .vendor-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .vendor-details h6 {
        margin: 0 0 4px 0;
        font-weight: 600;
        color: #111827;
    }

    .vendor-contact {
        font-size: 0.85rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 2px;
    }

    .modern-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-assignments {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .badge-settled {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .amount-display {
        font-size: 1.1rem;
        font-weight: 700;
        font-family: 'Courier New', monospace;
    }

    .amount-positive {
        color: #10b981;
    }

    .amount-negative {
        color: #ef4444;
    }

    .amount-neutral {
        color: #6b7280;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .action-btn-view {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .action-btn-pay {
        background: var(--success-gradient);
        color: white;
    }

    .action-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Enhanced Modal */
    .modern-modal .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modern-modal .modal-header {
        background: var(--success-gradient);
        padding: 25px 30px;
        border: none;
    }

    .modern-modal .modal-body {
        padding: 30px;
    }

    .info-alert {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: none;
        border-left: 4px solid #3b82f6;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .form-label-modern {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .balance-display {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 8px 12px;
        border-radius: 8px;
        display: inline-block;
        margin-top: 5px;
        font-weight: 600;
        color: #065f46;
    }

    /* Pagination */
    .pagination {
        gap: 5px;
    }

    .page-link {
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        color: #6b7280;
        font-weight: 600;
        margin: 0 2px;
    }

    .page-link:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
    }
</style>

<div class="page-content">
    <!-- Page Title -->
    <div class="page-title-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="d-flex align-items-center gap-2">
                    <i data-feather="users"></i> Vendor Settlements
                </h3>
                <p>Comprehensive vendor payment management and settlement tracking</p>
            </div>
            <div>
                <span class="badge bg-light text-dark px-3 py-2">
                    <i data-feather="calendar" style="width: 16px; height: 16px;"></i>
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Enhanced Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase mb-2" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1.5px; color: #6b7280;">
                            Total Vendors
                        </p>
                        <h2 class="mb-2 fw-bold" style="font-size: 2.5rem; color: #111827;">{{ $totalVendors }}</h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; font-size: 0.75rem;">
                                <i data-feather="trending-up" style="width: 12px; height: 12px;"></i> Active
                            </span>
                            <small class="text-muted">Partnerships</small>
                        </div>
                    </div>
                    <div class="stat-icon-wrapper">
                        <i data-feather="users" style="width: 32px; height: 32px; color: #667eea;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase mb-2" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1.5px; color: #6b7280;">
                            Outstanding
                        </p>
                        <h2 class="mb-2 fw-bold text-danger" style="font-size: 2.5rem;">₹{{ number_format($totalOutstanding, 2) }}</h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b; font-size: 0.75rem;">
                                <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> Pending
                            </span>
                            <small class="text-muted">Payments Due</small>
                        </div>
                    </div>
                    <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);">
                        <i data-feather="alert-triangle" style="width: 32px; height: 32px; color: #ef4444;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase mb-2" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1.5px; color: #6b7280;">
                            Total Paid
                        </p>
                        <h2 class="mb-2 fw-bold text-success" style="font-size: 2.5rem;">₹{{ number_format($totalPaid, 2) }}</h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; font-size: 0.75rem;">
                                <i data-feather="check-circle" style="width: 12px; height: 12px;"></i> Completed
                            </span>
                            <small class="text-muted">Settlements</small>
                        </div>
                    </div>
                    <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);">
                        <i data-feather="dollar-sign" style="width: 32px; height: 32px; color: #10b981;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="filter-section">
        <form method="GET" action="{{ route('vendor-settlements.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label-modern">
                        <i data-feather="search" style="width: 16px; height: 16px;"></i> Search Vendors
                    </label>
                    <input type="text" name="search" class="form-control modern-input" 
                           placeholder="Search by name, contact person, or phone..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">
                        <i data-feather="filter" style="width: 16px; height: 16px;"></i> Filter Status
                    </label>
                    <select name="status" class="form-select modern-input">
                        <option value="">All Vendors</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Payments</option>
                        <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Fully Settled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn modern-btn modern-btn-primary w-100">
                        <i data-feather="search" style="width: 18px; height: 18px;"></i> Search
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('vendor-settlements.index') }}" class="btn modern-btn w-100" style="background: #f3f4f6; color: #6b7280;">
                        <i data-feather="x" style="width: 18px; height: 18px;"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced Vendors Table -->
    <div class="vendors-table-card">
        <div class="table-header">
            <h5 class="mb-0 fw-bold" style="color: #111827;">
                <i data-feather="list" style="width: 20px; height: 20px;"></i> Vendor Directory
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Vendor</th>
                        <th width="12%" class="text-center">Assignments</th>
                        <th width="14%" class="text-end">Assigned</th>
                        <th width="14%" class="text-end">Paid</th>
                        <th width="14%" class="text-end">Outstanding</th>
                        <th width="16%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $index => $vendor)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark fw-bold">{{ $vendors->firstItem() + $index }}</span>
                        </td>
                        <td>
                            <div class="vendor-info">
                                <div class="vendor-avatar">
                                    {{ strtoupper(substr($vendor->name, 0, 1)) }}
                                </div>
                                <div class="vendor-details">
                                    <h6>{{ $vendor->name }}</h6>
                                    <div class="vendor-contact">
                                        <i data-feather="user" style="width: 14px; height: 14px;"></i>
                                        {{ $vendor->contact_person }}
                                    </div>
                                    <div class="vendor-contact">
                                        <i data-feather="phone" style="width: 14px; height: 14px;"></i>
                                        {{ $vendor->contact_number }}
                                    </div>
                                    @if(!$vendor->is_active)
                                        <span class="badge bg-secondary mt-1" style="font-size: 0.7rem;">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="modern-badge badge-assignments">
                                <i data-feather="briefcase" style="width: 14px; height: 14px;"></i>
                                {{ $vendor->service_assignments_count }}
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="amount-display amount-positive">₹{{ number_format($vendor->total_assigned, 2) }}</span>
                        </td>
                        <td class="text-end">
                            <span class="amount-display text-success">₹{{ number_format($vendor->total_paid, 2) }}</span>
                        </td>
                        <td class="text-end">
                            @php $outstanding = $vendor->outstanding_balance; @endphp
                            <span class="amount-display {{ $outstanding > 0 ? 'amount-negative' : 'amount-neutral' }}">
                                ₹{{ number_format($outstanding, 2) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('vendor-settlements.show', $vendor->id) }}" 
                                   class="action-btn action-btn-view" 
                                   data-bs-toggle="tooltip" 
                                   title="View Details">
                                    <i data-feather="eye" style="width: 18px; height: 18px;"></i>
                                </a>
                                @if($outstanding > 0)
                                    <button type="button" 
                                            class="action-btn action-btn-pay" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addPaymentModal" 
                                            onclick="setVendor({{ $vendor->id }}, '{{ $vendor->name }}', {{ $outstanding }})"
                                            title="Add Payment">
                                        <i data-feather="dollar-sign" style="width: 18px; height: 18px;"></i>
                                    </button>
                                @else
                                    <span class="modern-badge badge-settled">
                                        <i data-feather="check" style="width: 14px; height: 14px;"></i> Settled
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-feather="inbox" style="width: 40px; height: 40px; color: #9ca3af;"></i>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #374151;">No Vendors Found</h5>
                                <p class="text-muted mb-0">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vendors->hasPages())
        <div class="p-4 border-top">
            {{ $vendors->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Enhanced Payment Modal -->
<div class="modal fade modern-modal" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-white fw-bold mb-0">
                    <i data-feather="dollar-sign" style="width: 24px; height: 24px;"></i> Record Vendor Payment
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="info-alert">
                        <div class="d-flex align-items-start gap-3">
                            <i data-feather="info" style="width: 24px; height: 24px; color: #3b82f6; flex-shrink: 0;"></i>
                            <div>
                                <strong style="color: #1e40af;">Vendor:</strong> <span id="vendor_name" class="fw-bold"></span><br>
                                <strong style="color: #1e40af;">Outstanding Balance:</strong> 
                                <span class="fw-bold" style="color: #dc2626;">₹<span id="vendor_outstanding"></span></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label-modern">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control modern-input" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label-modern">Time <small class="text-muted">(optional)</small></label>
                            <input type="time" name="payment_time" class="form-control modern-input" value="{{ now()->format('H:i') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="payment_amount" class="form-control modern-input" placeholder="0.00" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select modern-input" required onchange="loadPaymentAccounts()">
                                <option value="">Select Method</option>
                                <option value="cash">💵 Cash</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                                <option value="upi">📱 UPI</option>
                                <option value="cheque">📝 Cheque</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-modern">Payment Account <span class="text-danger">*</span></label>
                            <select name="payment_account_id" id="payment_account_id" class="form-select modern-input" required>
                                <option value="">Select method first</option>
                            </select>
                            <div class="balance-display mt-2">
                                <i data-feather="credit-card" style="width: 14px; height: 14px;"></i>
                                Balance: ₹<span id="account_balance" class="fw-bold">0.00</span>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label-modern">Reference Number</label>
                            <input type="text" name="reference_number" class="form-control modern-input" placeholder="Transaction ID, Cheque No., etc.">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label-modern">Notes</label>
                            <textarea name="notes" class="form-control modern-input" rows="3" placeholder="Any additional notes or comments"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn modern-btn px-4" style="background: #f3f4f6; color: #6b7280;" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn modern-btn px-4" style="background: var(--success-gradient); color: white;">
                        <i data-feather="check" style="width: 18px; height: 18px;"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setVendor(vendorId, vendorName, outstanding) {
        document.getElementById('vendor_name').textContent = vendorName;
        document.getElementById('vendor_outstanding').textContent = parseFloat(outstanding).toFixed(2);
        document.getElementById('payment_amount').value = parseFloat(outstanding).toFixed(2);
        document.getElementById('paymentForm').action = `/admin/vendor-settlements/${vendorId}/add-payment`;
        
        document.getElementById('payment_method').value = '';
        document.getElementById('payment_account_id').innerHTML = '<option value="">Select method first</option>';
        document.getElementById('account_balance').textContent = '0.00';
    }

    function loadPaymentAccounts() {
        const method = document.getElementById('payment_method').value;
        const accountSelect = document.getElementById('payment_account_id');
        const balanceSpan = document.getElementById('account_balance');
        
        if (!method) {
            accountSelect.innerHTML = '<option value="">Select method first</option>';
            balanceSpan.textContent = '0.00';
            return;
        }
        
        accountSelect.innerHTML = '<option value="">Loading...</option>';
        
        fetch(`/admin/payment-accounts/by-type/${method}`)
            .then(response => response.json())
            .then(accounts => {
                accountSelect.innerHTML = '<option value="">Select Account</option>';
                
                if (accounts.length === 0) {
                    accountSelect.innerHTML = '<option value="">No accounts available</option>';
                    return;
                }
                
                accounts.forEach(account => {
                    const option = document.createElement('option');
                    option.value = account.id;
                    option.textContent = `${account.account_name} (₹${parseFloat(account.balance).toFixed(2)})`;
                    option.dataset.balance = account.balance;
                    accountSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                accountSelect.innerHTML = '<option value="">Error loading accounts</option>';
            });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const accountSelect = document.getElementById('payment_account_id');
        const balanceSpan = document.getElementById('account_balance');
        
        accountSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.balance) {
                balanceSpan.textContent = parseFloat(selectedOption.dataset.balance).toFixed(2);
            } else {
                balanceSpan.textContent = '0.00';
            }
        });
        
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        feather.replace();
    });
</script>
@endsection
