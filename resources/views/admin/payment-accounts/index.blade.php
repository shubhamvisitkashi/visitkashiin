@extends('admin.layouts.app')
@section('content')
<style>
/* ── Page ── */
.pa-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ── Header ── */
.pa-header{background:linear-gradient(135deg,#4F46E5,#7C3AED);border-radius:16px;padding:22px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 28px rgba(79,70,229,.28);}
.pa-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;pointer-events:none;}
.pa-header-left{display:flex;align-items:center;gap:14px;position:relative;z-index:1;}
.pa-header-icon{width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pa-header-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.pa-header-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 3px;}
.pa-header-text p{color:rgba(255,255,255,.7);font-size:.78rem;margin:0;}
.pa-add-btn{position:relative;z-index:1;display:inline-flex;align-items:center;gap:7px;background:#fff;color:#4F46E5;border:none;border-radius:10px;padding:10px 18px;font-size:.82rem;font-weight:700;text-decoration:none;box-shadow:0 2px 10px rgba(0,0,0,.15);transition:all .2s;white-space:nowrap;flex-shrink:0;}
.pa-add-btn:hover{background:#f0f0ff;color:#4F46E5;transform:translateY(-1px);}
.pa-add-btn i[data-feather]{width:15px;height:15px;}

/* ── Stats ── */
.pa-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;}
.pa-stat{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:16px 20px;display:flex;align-items:center;gap:14px;}
.pa-stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pa-stat-icon i[data-feather]{width:20px;height:20px;}
.pa-stat-val{font-size:1.35rem;font-weight:900;color:#0F172A;line-height:1;}
.pa-stat-lbl{font-size:.7rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-top:3px;}

/* ── ATM Card Grid ── */
.atm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;}

/* ── ATM Card ── */
.atm-card{
    width:100%;
    aspect-ratio:1.586/1;      /* standard card ratio */
    border-radius:20px;
    position:relative;
    overflow:hidden;
    box-shadow:0 16px 40px rgba(0,0,0,.18),0 4px 12px rgba(0,0,0,.12);
    cursor:default;
    transition:transform .25s, box-shadow .25s;
    min-height:180px;
}
.atm-card:hover{transform:translateY(-6px) scale(1.01);box-shadow:0 24px 52px rgba(0,0,0,.22),0 8px 20px rgba(0,0,0,.14);}
.atm-card.inactive{filter:grayscale(.6);opacity:.75;}

/* ── Gradients per type ── */
.atm-cash    {background:linear-gradient(135deg,#1a472a 0%,#2d6a4f 40%,#40916c 100%);}
.atm-bank    {background:linear-gradient(135deg,#0f2d5e 0%,#1a4fa0 45%,#2563eb 100%);}
.atm-upi     {background:linear-gradient(135deg,#6b21a8 0%,#7c3aed 45%,#8b5cf6 100%);}
.atm-card-t  {background:linear-gradient(135deg,#7f1d1d 0%,#b91c1c 45%,#ef4444 100%);}
.atm-default {background:linear-gradient(135deg,#1e293b 0%,#334155 45%,#475569 100%);}

/* Shine overlay */
.atm-card::before{
    content:'';
    position:absolute;inset:0;
    background:linear-gradient(135deg,rgba(255,255,255,.18) 0%,transparent 50%,rgba(0,0,0,.08) 100%);
    pointer-events:none;z-index:1;
}
/* Orb decorations */
.atm-orb1{position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.07);pointer-events:none;}
.atm-orb2{position:absolute;bottom:-50px;left:-30px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none;}

/* Card content */
.atm-inner{position:relative;z-index:2;height:100%;display:flex;flex-direction:column;padding:18px 22px;}

/* Top row */
.atm-top{display:flex;align-items:flex-start;justify-content:space-between;}
.atm-bank-logo{font-size:.72rem;font-weight:800;color:rgba(255,255,255,.9);letter-spacing:.08em;text-transform:uppercase;}
.atm-type-icon{font-size:1.6rem;line-height:1;}
.atm-status-dot{width:8px;height:8px;border-radius:50%;background:#4ade80;box-shadow:0 0 6px #4ade80;margin-top:4px;}
.atm-status-dot.off{background:#9ca3af;box-shadow:none;}

/* Chip */
.atm-chip{width:36px;height:27px;border-radius:5px;background:linear-gradient(135deg,#ffd700,#b8860b);margin:10px 0 6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.atm-chip-lines{display:flex;flex-direction:column;gap:2px;width:70%;opacity:.6;}
.atm-chip-line{height:2px;background:#8B6914;border-radius:1px;}
.atm-chip-line:nth-child(2){width:80%;}

/* Account number */
.atm-number{font-size:.82rem;font-weight:700;color:rgba(255,255,255,.7);letter-spacing:.18em;font-family:'Courier New',monospace;margin-bottom:auto;}

/* Bottom row */
.atm-bottom{display:flex;align-items:flex-end;justify-content:space-between;margin-top:auto;}
.atm-name{font-size:.88rem;font-weight:800;color:#fff;text-shadow:0 1px 3px rgba(0,0,0,.2);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px;}
.atm-label{font-size:.6rem;font-weight:600;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px;}
.atm-actions{display:flex;gap:6px;align-items:center;}
.atm-act-btn{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;transition:all .18s;text-decoration:none;background:rgba(255,255,255,.2);backdrop-filter:blur(4px);}
.atm-act-btn:hover{background:rgba(255,255,255,.35);}
.atm-act-btn i[data-feather]{width:13px;height:13px;stroke:#fff;}
.atm-act-del{background:rgba(239,68,68,.3);}.atm-act-del:hover{background:rgba(239,68,68,.6);}
.atm-act-tog{background:rgba(251,191,36,.25);}.atm-act-tog:hover{background:rgba(251,191,36,.45);}
.atm-act-edit{background:rgba(255,255,255,.18);}.atm-act-edit:hover{background:rgba(255,255,255,.35);}

/* Inactive badge */
.atm-inactive-badge{position:absolute;top:14px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.5);color:rgba(255,255,255,.8);font-size:.65rem;font-weight:700;padding:2px 10px;border-radius:20px;letter-spacing:.06em;z-index:3;white-space:nowrap;}

/* Empty */
.pa-empty{text-align:center;padding:56px 24px;background:#fff;border-radius:16px;border:1px solid #E2E8F0;}
.pa-empty i[data-feather]{width:52px;height:52px;opacity:.2;display:block;margin:0 auto 14px;}

/* Alert */
.pa-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:.85rem;font-weight:600;}
.pa-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.pa-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}

/* ── Responsive ── */
@media(max-width:900px){.pa-stats{grid-template-columns:1fr 1fr;}.atm-grid{grid-template-columns:repeat(auto-fill,minmax(260px,1fr));}}
@media(max-width:600px){.pa-page{padding:12px;}.pa-header{flex-direction:column;align-items:flex-start;padding:16px 18px;}.pa-stats{grid-template-columns:1fr;}.atm-grid{grid-template-columns:1fr;}}
</style>

<div class="pa-page">

@if(session('success'))
<div class="pa-alert pa-alert-ok"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="pa-alert pa-alert-err"><i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i> {{ session('error') }}</div>
@endif

{{-- Header --}}
<div class="pa-header">
    <div class="pa-header-left">
        <div class="pa-header-icon"><i data-feather="credit-card"></i></div>
        <div class="pa-header-text">
            <h1>Payment Accounts</h1>
            <p>Manage all payment methods and accounts</p>
        </div>
    </div>
    <a href="{{ route('payment-accounts.create') }}" class="pa-add-btn">
        <i data-feather="plus"></i> Add Account
    </a>
</div>

{{-- Stats --}}
<div class="pa-stats">
    <div class="pa-stat">
        <div class="pa-stat-icon" style="background:#EEF2FF;"><i data-feather="layers" style="stroke:#4F46E5;"></i></div>
        <div><div class="pa-stat-val">{{ $accounts->count() }}</div><div class="pa-stat-lbl">Total Accounts</div></div>
    </div>
    <div class="pa-stat">
        <div class="pa-stat-icon" style="background:#ECFDF5;"><i data-feather="check-circle" style="stroke:#10B981;"></i></div>
        <div><div class="pa-stat-val">{{ $activeAccounts }}</div><div class="pa-stat-lbl">Active</div></div>
    </div>
    <div class="pa-stat">
        <div class="pa-stat-icon" style="background:#F0F9FF;"><i data-feather="dollar-sign" style="stroke:#0EA5E9;"></i></div>
        <div><div class="pa-stat-val" style="font-size:1.1rem;">₹{{ number_format($totalBalance,0) }}</div><div class="pa-stat-lbl">Total Balance</div></div>
    </div>
</div>

{{-- ATM Card Grid --}}
@if($accounts->isNotEmpty())
<div class="atm-grid">
    @foreach($accounts as $acc)
    @php
        $typeClass = match($acc->account_type) {
            'cash'          => 'atm-cash',
            'bank_transfer' => 'atm-bank',
            'upi'           => 'atm-upi',
            'card'          => 'atm-card-t',
            default         => 'atm-default',
        };
        $typeIcon = match($acc->account_type) {
            'cash'          => '💵',
            'bank_transfer' => '🏦',
            'upi'           => '📱',
            'card'          => '💳',
            default         => '💰',
        };
        $typeLabel = match($acc->account_type) {
            'cash'          => 'Cash Account',
            'bank_transfer' => 'Bank Transfer',
            'upi'           => 'UPI Account',
            'card'          => 'Card Account',
            default         => 'Payment Account',
        };
        $maskedNum = $acc->account_number
            ? '•••• •••• ' . substr($acc->account_number, -4)
            : '•••• •••• ••••';
        // Bank name: use bank_name column if set, else account_name as fallback
        $bankDisplay = $acc->bank_name ?: $acc->account_name;
    @endphp
    <div class="atm-card {{ $typeClass }} {{ !$acc->is_active ? 'inactive' : '' }}">
        <div class="atm-orb1"></div>
        <div class="atm-orb2"></div>

        @if(!$acc->is_active)
        <div class="atm-inactive-badge">INACTIVE</div>
        @endif

        <div class="atm-inner">
            {{-- Top --}}
            <div class="atm-top">
                <div style="min-width:0;flex:1;margin-right:8px;">
                    <div class="atm-bank-logo" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;" title="{{ $bankDisplay }}">{{ $bankDisplay }}</div>
                    <div style="font-size:.6rem;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:.04em;">{{ $typeLabel }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div class="atm-status-dot {{ !$acc->is_active ? 'off' : '' }}"></div>
                    <div class="atm-type-icon">{{ $typeIcon }}</div>
                </div>
            </div>

            {{-- Chip --}}
            <div class="atm-chip">
                <div class="atm-chip-lines">
                    <div class="atm-chip-line"></div>
                    <div class="atm-chip-line"></div>
                    <div class="atm-chip-line"></div>
                </div>
            </div>

            {{-- Account Number --}}
            <div class="atm-number">{{ $maskedNum }}</div>

            {{-- Bottom --}}
            <div class="atm-bottom">
                <div style="min-width:0;">
                    <div class="atm-label">Account Name</div>
                    <div class="atm-name" title="{{ $acc->account_name }}">{{ $acc->account_name }}</div>
                </div>
                <div class="atm-actions">
                    <a href="{{ route('payment-accounts.edit', $acc->id) }}" class="atm-act-btn atm-act-edit" title="Edit">
                        <i data-feather="edit-2"></i>
                    </a>
                    <form action="{{ route('payment-accounts.toggle-status', $acc->id) }}" method="POST" style="display:contents;">
                        @csrf
                        <button type="submit" class="atm-act-btn atm-act-tog" title="{{ $acc->is_active ? 'Deactivate' : 'Activate' }}">
                            <i data-feather="{{ $acc->is_active ? 'toggle-right' : 'toggle-left' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('payment-accounts.destroy', $acc->id) }}" method="POST" style="display:contents;"
                          onsubmit="return confirm('Delete {{ addslashes($acc->account_name) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="atm-act-btn atm-act-del" title="Delete">
                            <i data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="pa-empty">
    <i data-feather="credit-card"></i>
    <p style="font-weight:700;color:#374151;margin:0 0 6px;font-size:1rem;">No payment accounts yet</p>
    <p style="font-size:.84rem;color:#64748B;margin:0 0 16px;">Add your first payment account to get started</p>
    <a href="{{ route('payment-accounts.create') }}" class="pa-add-btn" style="display:inline-flex;background:#4F46E5;color:#fff;">
        <i data-feather="plus"></i> Add First Account
    </a>
</div>
@endif

</div>
<script>document.addEventListener('DOMContentLoaded',function(){feather.replace();});</script>
@endsection
