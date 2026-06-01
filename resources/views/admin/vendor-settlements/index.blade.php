@extends('admin.layouts.app')
@section('content')
<style>
:root{--vs:#10B981;--vs-lt:#ECFDF5;--vp:#4F46E5;--vr:#EF4444;--vt:#0F172A;--vm:#64748B;--vb:#E2E8F0;--vc:#fff;--vr2:14px;}
.vs-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ── Header ── */
.vs-header {
    background: linear-gradient(135deg, #064E3B, #10B981);
    border-radius: 16px;
    padding: 22px 28px;
    display: flex;
    margin-top: 50px;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(16, 185, 129, .25);
}
.vs-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;pointer-events:none;}
.vs-header-left{display:flex;align-items:center;gap:14px;position:relative;z-index:1;}
.vs-header-icon{width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.vs-header-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.vs-header-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 3px;}
.vs-header-text p{color:rgba(255,255,255,.7);font-size:.78rem;margin:0;}
.vs-date{position:relative;z-index:1;background:rgba(255,255,255,.15);color:#fff;border-radius:10px;padding:8px 14px;font-size:.78rem;font-weight:600;white-space:nowrap;flex-shrink:0;}

/* ── Stats ── */
.vs-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;}
.vs-stat{background:#fff;border-radius:var(--vr2);border:1px solid var(--vb);box-shadow:0 1px 4px rgba(0,0,0,.05);padding:18px 20px;display:flex;align-items:center;gap:14px;position:relative;overflow:hidden;}
.vs-stat::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.vs-stat.stat-tot::after{background:linear-gradient(90deg,#4F46E5,#7C3AED);}
.vs-stat.stat-out::after{background:linear-gradient(90deg,#EF4444,#DC2626);}
.vs-stat.stat-paid::after{background:linear-gradient(90deg,#10B981,#059669);}
.vs-stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.vs-stat-icon i[data-feather]{width:22px;height:22px;}
.vs-stat-val{font-size:1.3rem;font-weight:900;color:var(--vt);line-height:1;}
.vs-stat-lbl{font-size:.71rem;font-weight:600;color:var(--vm);text-transform:uppercase;letter-spacing:.04em;margin-top:3px;}

/* ── Filter bar ── */
.vs-filter{background:#fff;border-radius:var(--vr2);border:1px solid var(--vb);box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 18px;margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
.vs-filter input,.vs-filter select{border:1.5px solid var(--vb);border-radius:9px;padding:8px 12px;font-size:.82rem;color:var(--vt);background:#FAFBFF;height:38px;outline:none;transition:border-color .15s;flex:1;min-width:160px;}
.vs-filter input:focus,.vs-filter select:focus{border-color:var(--vs);}
.vs-filter-btn{height:38px;padding:0 16px;border-radius:9px;border:none;font-size:.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;transition:all .15s;text-decoration:none;}
.vs-filter-btn.primary{background:var(--vs);color:#fff;}.vs-filter-btn.primary:hover{background:#059669;color:#fff;}
.vs-filter-btn.ghost{background:#F1F5F9;color:var(--vm);}.vs-filter-btn.ghost:hover{background:#E2E8F0;color:var(--vt);text-decoration:none;}
.vs-filter-btn i[data-feather]{width:13px;height:13px;}

/* ── Table card ── */
.vs-card{background:#fff;border-radius:var(--vr2);border:1px solid var(--vb);box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.vs-card-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;gap:12px;background:#FAFBFF;}
.vs-card-title{font-size:.9rem;font-weight:700;color:var(--vt);display:flex;align-items:center;gap:8px;}
.vs-card-title i[data-feather]{width:16px;height:16px;stroke:var(--vs);}
.vs-table{width:100%;border-collapse:collapse;}
.vs-table th{font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--vm);padding:11px 16px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.vs-table td{padding:14px 16px;border-bottom:1px solid #F8FAFC;vertical-align:middle;font-size:.84rem;color:var(--vt);}
.vs-table tbody tr:last-child td{border-bottom:none;}
.vs-table tbody tr:hover{background:#FAFBFF;}

/* Vendor cell */
.vs-vendor{display:flex;align-items:center;gap:11px;}
.vs-av{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#10B981,#059669);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:#fff;flex-shrink:0;}
.vs-av.inactive{background:linear-gradient(135deg,#9CA3AF,#6B7280);}
.vs-vendor-name{font-size:.87rem;font-weight:700;color:var(--vt);}
.vs-vendor-sub{font-size:.72rem;color:var(--vm);margin-top:2px;display:flex;align-items:center;gap:4px;}
.vs-vendor-sub i[data-feather]{width:11px;height:11px;}

/* Assignment badge */
.vs-asgn{display:inline-flex;align-items:center;gap:4px;background:#EEF2FF;color:#4338CA;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;}

/* Amount */
.vs-amt{font-size:.88rem;font-weight:800;}
.vs-amt-pos{color:#065F46;}
.vs-amt-neg{color:#991B1B;}
.vs-amt-paid{color:#059669;}
.vs-amt-zero{color:var(--vm);}

/* Settled badge */
.vs-settled{display:inline-flex;align-items:center;gap:4px;background:#ECFDF5;color:#065F46;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;}
.vs-settled i[data-feather]{width:11px;height:11px;}

/* Actions */
.vs-actions{display:flex;gap:5px;align-items:center;justify-content:center;}
.vs-btn{height:32px;padding:0 12px;border-radius:8px;display:inline-flex;align-items:center;gap:5px;border:none;cursor:pointer;font-size:.75rem;font-weight:700;transition:all .15s;text-decoration:none;}
.vs-btn i[data-feather]{width:13px;height:13px;}
.vs-btn-view{background:#EEF2FF;color:var(--vp);}.vs-btn-view:hover{background:#ddd6fe;color:var(--vp);}
.vs-btn-pay{background:#ECFDF5;color:#065F46;}.vs-btn-pay:hover{background:#d1fae5;color:#065F46;}

/* Empty */
.vs-empty{text-align:center;padding:48px;color:var(--vm);}
.vs-empty i[data-feather]{width:48px;height:48px;opacity:.25;display:block;margin:0 auto 12px;}

/* Pagination */
.vs-pag{padding:14px 20px;border-top:1px solid var(--vb);}
.vs-pag .pagination{margin:0;}
.vs-pag .page-item .page-link{border-radius:8px;border:1.5px solid var(--vb);font-size:.8rem;font-weight:600;color:var(--vm);margin:0 2px;}
.vs-pag .page-item.active .page-link{background:var(--vs);border-color:var(--vs);color:#fff;}

/* Alert */
.vs-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:.85rem;font-weight:600;}
.vs-alert-success{background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.vs-alert-error  {background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}

/* Modal */
.vs-modal .modal-content{border:none;border-radius:18px;overflow:hidden;}
.vs-modal .modal-header{background:linear-gradient(135deg,#064E3B,#10B981);padding:18px 24px;}
.vs-modal .modal-title{color:#fff;font-size:1rem;font-weight:800;display:flex;align-items:center;gap:8px;}
.vs-modal .modal-title i[data-feather]{width:18px;height:18px;stroke:#fff;}
.vs-modal .modal-body{padding:22px 24px;}
.vs-modal .modal-footer{padding:14px 24px;background:#F8FAFC;border-top:1px solid var(--vb);}
.vs-input{border:1.5px solid var(--vb)!important;border-radius:10px!important;padding:9px 13px!important;font-size:.87rem!important;color:var(--vt)!important;background:#FAFBFF!important;box-shadow:none!important;}
.vs-input:focus{border-color:var(--vs)!important;box-shadow:0 0 0 3px rgba(16,185,129,.12)!important;}
.vs-label{font-size:.72rem;font-weight:700;color:var(--vm);text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px;display:block;}
.vs-vendor-info-box{background:#F0FDF4;border:1.5px solid #86EFAC;border-radius:12px;padding:12px 16px;margin-bottom:18px;display:flex;align-items:center;gap:12px;}
.vs-modal-submit{background:linear-gradient(135deg,#10B981,#059669);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:.88rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:opacity .2s;}
.vs-modal-submit:hover{opacity:.88;color:#fff;}
.vs-modal-cancel{background:#F1F5F9;color:var(--vm);border:none;border-radius:10px;padding:10px 22px;font-size:.88rem;font-weight:700;cursor:pointer;}
.vs-modal-cancel:hover{background:#E2E8F0;color:var(--vt);}

/* Balance display */
.vs-balance-tag{display:inline-flex;align-items:center;gap:5px;background:#EEF2FF;color:var(--vp);font-size:.73rem;font-weight:700;padding:3px 10px;border-radius:6px;margin-top:6px;}

/* Responsive */
@media(max-width:768px){
    .vs-page{padding:12px;}
    .vs-stats{grid-template-columns:1fr;}
    .vs-header{flex-direction:column;align-items:flex-start;padding:18px;}
    .vs-table thead{display:none;}
    .vs-table tbody tr{display:flex;flex-direction:column;background:#fff;border-radius:12px;border:1px solid var(--vb);margin-bottom:10px;overflow:hidden;}
    .vs-table tbody td{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border:none;border-bottom:1px solid #F3F4F6;font-size:.81rem;}
    .vs-table tbody td:last-child{border-bottom:none;}
    .vs-table tbody td[data-label]::before{content:attr(data-label);font-size:.65rem;font-weight:700;color:var(--vm);text-transform:uppercase;}
}
@media(max-width:900px){.vs-stats{grid-template-columns:1fr 1fr;}}
</style>

<div class="vs-page">

@if(session('success'))
<div class="vs-alert vs-alert-success"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="vs-alert vs-alert-error"><i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i> {{ session('error') }}</div>
@endif

{{-- Header --}}
<div class="vs-header">
    <div class="vs-header-left">
        <div class="vs-header-icon"><i data-feather="users"></i></div>
        <div class="vs-header-text">
            <h1>Vendor Settlements</h1>
            <p>Track and manage vendor payment settlements</p>
        </div>
    </div>
    <div class="vs-date">📅 {{ now()->format('d M Y') }}</div>
</div>

{{-- Stats --}}
<div class="vs-stats">
    <div class="vs-stat stat-tot">
        <div class="vs-stat-icon" style="background:#EEF2FF;"><i data-feather="users" style="stroke:#4F46E5;"></i></div>
        <div>
            <div class="vs-stat-val">{{ $totalVendors }}</div>
            <div class="vs-stat-lbl">Total Vendors</div>
        </div>
    </div>
    <div class="vs-stat stat-out">
        <div class="vs-stat-icon" style="background:#FEF2F2;"><i data-feather="alert-circle" style="stroke:#EF4444;"></i></div>
        <div>
            <div class="vs-stat-val" style="color:#991B1B;font-size:1.1rem;">₹{{ number_format($totalOutstanding,0) }}</div>
            <div class="vs-stat-lbl">Outstanding</div>
        </div>
    </div>
    <div class="vs-stat stat-paid">
        <div class="vs-stat-icon" style="background:#ECFDF5;"><i data-feather="check-circle" style="stroke:#10B981;"></i></div>
        <div>
            <div class="vs-stat-val" style="color:#065F46;font-size:1.1rem;">₹{{ number_format($totalPaid,0) }}</div>
            <div class="vs-stat-lbl">Total Paid</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('vendor-settlements.index') }}" class="vs-filter">
    <input type="text" name="search" placeholder="🔍 Search by name, contact or phone…" value="{{ request('search') }}">
    <select name="status" style="flex:0 0 180px;">
        <option value="">All Vendors</option>
        <option value="pending"  {{ request('status')=='pending'  ? 'selected':'' }}>Pending Payments</option>
        <option value="settled"  {{ request('status')=='settled'  ? 'selected':'' }}>Fully Settled</option>
    </select>
    <button type="submit" class="vs-filter-btn primary"><i data-feather="search"></i> Search</button>
    <a href="{{ route('vendor-settlements.index') }}" class="vs-filter-btn ghost"><i data-feather="x"></i> Clear</a>
</form>

{{-- Table --}}
<div class="vs-card">
    <div class="vs-card-head">
        <div class="vs-card-title"><i data-feather="list"></i> Vendor Directory</div>
        <span style="font-size:.75rem;color:var(--vm);">{{ $vendors->total() }} vendors</span>
    </div>
    <div class="table-responsive">
        <table class="vs-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vendor</th>
                    <th class="text-center">Jobs</th>
                    <th class="text-end">Assigned</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Outstanding</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $i => $vendor)
                @php $outstanding = $vendor->outstanding_balance; @endphp
                <tr>
                    <td data-label="#">{{ $vendors->firstItem() + $i }}</td>
                    <td data-label="Vendor">
                        <div class="vs-vendor">
                            <div class="vs-av {{ !$vendor->is_active ? 'inactive' : '' }}">
                                {{ strtoupper(substr($vendor->name,0,1)) }}
                            </div>
                            <div>
                                <div class="vs-vendor-name">{{ $vendor->name }}</div>
                                @if($vendor->contact_person)
                                <div class="vs-vendor-sub"><i data-feather="user"></i> {{ $vendor->contact_person }}</div>
                                @endif
                                @if($vendor->contact_number)
                                <div class="vs-vendor-sub"><i data-feather="phone"></i> {{ $vendor->contact_number }}</div>
                                @endif
                                @if(!$vendor->is_active)
                                <span style="font-size:.65rem;background:#F3F4F6;color:#6B7280;padding:1px 7px;border-radius:20px;font-weight:700;">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td data-label="Jobs" class="text-center">
                        <span class="vs-asgn"><i data-feather="briefcase"></i> {{ $vendor->service_assignments_count }}</span>
                    </td>
                    <td data-label="Assigned" class="text-end">
                        <span class="vs-amt vs-amt-pos">₹{{ number_format($vendor->total_assigned,0) }}</span>
                    </td>
                    <td data-label="Paid" class="text-end">
                        <span class="vs-amt vs-amt-paid">₹{{ number_format($vendor->total_paid,0) }}</span>
                    </td>
                    <td data-label="Outstanding" class="text-end">
                        <span class="vs-amt {{ $outstanding > 0 ? 'vs-amt-neg' : 'vs-amt-zero' }}">
                            ₹{{ number_format($outstanding,0) }}
                        </span>
                    </td>
                    <td data-label="Actions">
                        <div class="vs-actions">
                            <a href="{{ route('vendor-settlements.show', $vendor->id) }}" class="vs-btn vs-btn-view">
                                <i data-feather="eye"></i> View
                            </a>
                            @if($outstanding > 0)
                            <button type="button" class="vs-btn vs-btn-pay"
                                data-bs-toggle="modal" data-bs-target="#payModal"
                                onclick="setVendor({{ $vendor->id }}, '{{ addslashes($vendor->name) }}', {{ $outstanding }})">
                                <i data-feather="dollar-sign"></i> Pay
                            </button>
                            @else
                            <span class="vs-settled"><i data-feather="check"></i> Settled</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <div class="vs-empty">
                        <i data-feather="users"></i>
                        <p style="font-weight:700;color:#374151;margin:0 0 4px;">No vendors found</p>
                        <p style="font-size:.82rem;margin:0;">Try adjusting your search or filter criteria</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($vendors->hasPages())
    <div class="vs-pag">{{ $vendors->appends(request()->query())->links() }}</div>
    @endif
</div>

</div>

{{-- Payment Modal --}}
<div class="modal fade vs-modal" id="payModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i data-feather="dollar-sign"></i> Record Vendor Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="vs-vendor-info-box">
                        <div class="vs-av" id="payVendorAv" style="flex-shrink:0;">V</div>
                        <div>
                            <div style="font-weight:800;color:#065F46;font-size:.92rem;" id="vendor_name">—</div>
                            <div style="font-size:.78rem;color:#059669;margin-top:2px;">
                                Outstanding: <strong>₹<span id="vendor_outstanding">0.00</span></strong>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="vs-label">Payment Date <span style="color:#EF4444;">*</span></label>
                            <input type="date" name="payment_date" class="form-control vs-input" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="vs-label">Time</label>
                            <input type="time" name="payment_time" class="form-control vs-input" value="{{ now()->format('H:i') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="vs-label">Amount <span style="color:#EF4444;">*</span></label>
                            <input type="number" step="0.01" name="amount" id="payment_amount" class="form-control vs-input" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="vs-label">Payment Method <span style="color:#EF4444;">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select vs-input" required onchange="loadPaymentAccounts()">
                                <option value="">— Select Method —</option>
                                <option value="cash">💵 Cash</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                                <option value="upi">📱 UPI</option>
                                <option value="cheque">📝 Cheque</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="vs-label">Payment Account <span style="color:#EF4444;">*</span></label>
                            <select name="payment_account_id" id="payment_account_id" class="form-select vs-input" required>
                                <option value="">Select method first</option>
                            </select>
                            <div class="vs-balance-tag"><i data-feather="credit-card"></i> Balance: ₹<span id="account_balance">0.00</span></div>
                        </div>
                        <div class="col-12">
                            <label class="vs-label">Reference Number</label>
                            <input type="text" name="reference_number" class="form-control vs-input" placeholder="Transaction ID, Cheque No., etc.">
                        </div>
                        <div class="col-12">
                            <label class="vs-label">Notes</label>
                            <textarea name="notes" class="form-control vs-input" rows="2" placeholder="Additional notes…"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="vs-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="vs-modal-submit"><i data-feather="check"></i> Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setVendor(id, name, outstanding) {
    document.getElementById('vendor_name').textContent = name;
    document.getElementById('vendor_outstanding').textContent = parseFloat(outstanding).toFixed(2);
    document.getElementById('payment_amount').value = parseFloat(outstanding).toFixed(2);
    document.getElementById('paymentForm').action = '/admin/vendor-settlements/' + id + '/add-payment';
    document.getElementById('payVendorAv').textContent = name.charAt(0).toUpperCase();
    document.getElementById('payment_method').value = '';
    document.getElementById('payment_account_id').innerHTML = '<option value="">Select method first</option>';
    document.getElementById('account_balance').textContent = '0.00';
}
function loadPaymentAccounts() {
    var method = document.getElementById('payment_method').value;
    var sel = document.getElementById('payment_account_id');
    if (!method) { sel.innerHTML = '<option value="">Select method first</option>'; return; }
    sel.innerHTML = '<option value="">Loading…</option>';
    fetch('/admin/payment-accounts/by-type/' + method)
        .then(r => r.json())
        .then(accounts => {
            sel.innerHTML = '<option value="">Select Account</option>';
            accounts.forEach(a => {
                var opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = a.account_name + ' (₹' + parseFloat(a.balance).toFixed(2) + ')';
                opt.dataset.balance = a.balance;
                sel.appendChild(opt);
            });
        })
        .catch(() => { sel.innerHTML = '<option value="">Error loading</option>'; });
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('payment_account_id').addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        document.getElementById('account_balance').textContent = opt.dataset.balance ? parseFloat(opt.dataset.balance).toFixed(2) : '0.00';
    });
    feather.replace();
});
</script>
@endsection
