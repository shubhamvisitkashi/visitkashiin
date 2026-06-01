@extends('admin.layouts.app')
@section('content')
<style>
:root{--tg:#4F46E5;--tg-lt:#EEF2FF;--tgr:#10B981;--tgw:#F59E0B;--tgr2:#EF4444;--tt:#0F172A;--tm:#64748B;--tb:#E2E8F0;}
.bd-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ── Header ── */
.bd-header{background:linear-gradient(135deg,#0f172a,#4F46E5 60%,#7C3AED);border-radius:16px;padding:22px 28px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 28px rgba(79,70,229,.28);}
.bd-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.06);border-radius:50%;pointer-events:none;}
.bd-header-inner{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
.bd-header-left{display:flex;align-items:center;gap:14px;}
.bd-av{width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#fff;flex-shrink:0;border:2.5px solid rgba(255,255,255,.35);}
.bd-header-name{font-size:1.1rem;font-weight:800;color:#fff;margin:0 0 3px;}
.bd-header-sub{font-size:.78rem;color:rgba(255,255,255,.7);margin:0;}
.bd-back{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);color:#fff;border-radius:10px;padding:8px 16px;font-size:.8rem;font-weight:700;text-decoration:none;transition:background .18s;white-space:nowrap;flex-shrink:0;}
.bd-back:hover{background:rgba(255,255,255,.28);color:#fff;}
.bd-back i[data-feather]{width:13px;height:13px;stroke:#fff;}

/* ── KPI strip ── */
.bd-kpi{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;}
.bd-kpi-card{background:#fff;border-radius:12px;border:1px solid var(--tb);box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 18px;position:relative;overflow:hidden;}
.bd-kpi-card::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.kpi-target::after  {background:linear-gradient(90deg,#4F46E5,#7C3AED);}
.kpi-achieved::after{background:linear-gradient(90deg,#10B981,#059669);}
.kpi-pct::after     {background:linear-gradient(90deg,#F59E0B,#D97706);}
.kpi-count::after   {background:linear-gradient(90deg,#0EA5E9,#0369A1);}
.bd-kpi-lbl{font-size:.67rem;font-weight:700;color:var(--tm);text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;}
.bd-kpi-val{font-size:1.35rem;font-weight:900;color:var(--tt);line-height:1;}
.bd-kpi-val.green{color:#059669;}
.bd-kpi-val.amber{color:#B45309;}
.bd-kpi-val.red  {color:#DC2626;}
.bd-kpi-val.blue {color:#4F46E5;}

/* ── Progress bar ── */
.bd-progress-card{background:#fff;border-radius:12px;border:1px solid var(--tb);box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 20px;margin-bottom:18px;display:flex;align-items:center;gap:16px;}
.bd-progress-label{font-size:.78rem;font-weight:700;color:var(--tm);white-space:nowrap;}
.bd-pbar-wrap{flex:1;}
.bd-pbar{height:10px;background:#E2E8F0;border-radius:99px;overflow:hidden;}
.bd-pbar-fill{height:100%;border-radius:99px;transition:width .6s ease;}
.bd-pct{font-size:1rem;font-weight:900;white-space:nowrap;}

/* ── Info box ── */
.bd-info{background:#F0FDF4;border:1px solid #86EFAC;border-radius:12px;padding:12px 16px;margin-bottom:18px;font-size:.78rem;color:#047857;display:flex;align-items:flex-start;gap:10px;}
.bd-info i[data-feather]{width:16px;height:16px;stroke:#10B981;flex-shrink:0;margin-top:1px;}

/* ── Table card ── */
.bd-card{background:#fff;border-radius:12px;border:1px solid var(--tb);box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.bd-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#FAFBFF;}
.bd-card-title{font-size:.88rem;font-weight:700;color:var(--tt);display:flex;align-items:center;gap:8px;}
.bd-card-title i[data-feather]{width:15px;height:15px;stroke:var(--tg);}
.bd-table{width:100%;border-collapse:collapse;}
.bd-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--tm);padding:9px 12px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.bd-table td{padding:11px 12px;border-bottom:1px solid #F8FAFC;font-size:.81rem;color:var(--tt);vertical-align:middle;}
.bd-table tbody tr:hover{background:#FAFBFF;}
.bd-table tbody tr:last-child td{border-bottom:none;}
.bd-table tfoot td{padding:11px 12px;background:#F8FAFC;font-weight:800;font-size:.82rem;border-top:2px solid #E2E8F0;}

/* Inline badges */
.bd-badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:20px;font-size:.68rem;font-weight:700;}
.bd-bk-link{color:var(--tg);font-weight:700;text-decoration:none;font-size:.81rem;}
.bd-bk-link:hover{text-decoration:underline;color:var(--tg);}
.bd-amt-pos{color:#059669;font-weight:800;}
.bd-amt-neg{color:#DC2626;font-weight:800;}
/* Highlighted margin cell */
.bd-margin-cell{font-weight:900;font-size:.88rem;padding:4px 10px;border-radius:8px;display:inline-block;white-space:nowrap;}
.bd-margin-pos{background:#ECFDF5;color:#059669;border:1px solid #6EE7B7;}
.bd-margin-neg{background:#FEF2F2;color:#DC2626;border:1px solid #FECACA;}
/* Margin column header highlight */
.bd-th-margin{background:#FFF7ED !important;color:#92400E !important;border-bottom-color:#FED7AA !important;}
.bd-amt-blue{color:#4F46E5;font-weight:800;}
.bd-amt-muted{color:var(--tm);}
/* Customer clickable button */
.bd-cust-btn{background:none;border:none;cursor:pointer;display:inline-flex;align-items:center;padding:3px 6px;border-radius:6px;transition:background .15s;}
.bd-cust-btn:hover{background:#EEF2FF;}
/* Customer detail modal */
.bd-cust-modal .modal-content{border:none;border-radius:18px;overflow:hidden;}
.bd-cust-modal .modal-header{background:linear-gradient(135deg,#0f172a,#4F46E5);padding:18px 22px;border:none;}
.bd-cust-modal .modal-title{color:#fff;font-size:.95rem;font-weight:800;display:flex;align-items:center;gap:8px;}
.bd-cust-modal .modal-body{padding:0;}
.bd-detail-row{display:flex;align-items:center;gap:12px;padding:13px 20px;border-bottom:1px solid #F1F5F9;}
.bd-detail-row:last-child{border-bottom:none;}
.bd-detail-icon{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bd-detail-icon i[data-feather]{width:15px;height:15px;}
.bd-detail-lbl{font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;}
.bd-detail-val{font-size:.86rem;font-weight:700;color:#0F172A;}
.bd-cust-view-btn{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:.85rem;font-weight:700;cursor:pointer;text-decoration:none;}
.bd-cust-view-btn:hover{opacity:.88;color:#fff;}

/* Empty */
.bd-empty{text-align:center;padding:48px;color:var(--tm);}
.bd-empty i[data-feather]{width:48px;height:48px;opacity:.2;display:block;margin:0 auto 12px;}

@media(max-width:900px){.bd-kpi{grid-template-columns:repeat(2,1fr);}}
@media(max-width:640px){
    .bd-page{padding:12px;}
    .bd-kpi{grid-template-columns:1fr 1fr;}
    .bd-card{overflow-x:auto;}
    .bd-table{min-width:700px;}
}
</style>

<div class="bd-page">

{{-- Header --}}
<div class="bd-header">
    <div class="bd-header-inner">
        <div class="bd-header-left">
            <div class="bd-av">{{ strtoupper(substr($target->user->name,0,1)) }}</div>
            <div>
                <div class="bd-header-name">{{ $target->user->name }}</div>
                <div class="bd-header-sub">Target Breakdown · {{ date('F Y',mktime(0,0,0,$target->month,1,$target->year)) }}</div>
            </div>
        </div>
        <a href="{{ route('targets.index') }}" class="bd-back">
            <i data-feather="arrow-left"></i> Back to Targets
        </a>
    </div>
</div>

{{-- KPI Cards --}}
@php
    $pct  = $target->achievement_percentage;
    $over = $pct >= 100;
    $warn = $pct >= 50;
    $valCls = $over ? 'green' : ($warn ? 'amber' : 'red');
    $pbarBg = $over ? 'linear-gradient(90deg,#10B981,#059669)' : ($warn ? 'linear-gradient(90deg,#F59E0B,#D97706)' : 'linear-gradient(90deg,#EF4444,#DC2626)');
    $pctColor = $over ? '#059669' : ($warn ? '#B45309' : '#DC2626');
@endphp
<div class="bd-kpi">
    <div class="bd-kpi-card kpi-target">
        <div class="bd-kpi-lbl">🎯 Target Margin</div>
        <div class="bd-kpi-val blue">₹{{ number_format($target->target_margin,0) }}</div>
    </div>
    <div class="bd-kpi-card kpi-achieved">
        <div class="bd-kpi-lbl">✅ Achieved</div>
        <div class="bd-kpi-val {{ $valCls }}">₹{{ number_format($totalMargin,0) }}</div>
    </div>
    <div class="bd-kpi-card kpi-pct">
        <div class="bd-kpi-lbl">📊 Achievement</div>
        <div class="bd-kpi-val {{ $valCls }}">{{ $pct }}%</div>
    </div>
    <div class="bd-kpi-card kpi-count">
        <div class="bd-kpi-lbl">📋 Bookings</div>
        <div class="bd-kpi-val">{{ count($breakdown) }}</div>
    </div>
</div>

{{-- Progress Strip --}}
<div class="bd-progress-card">
    <div class="bd-progress-label">Target · Progress</div>
    <div class="bd-pbar-wrap">
        <div class="bd-pbar">
            <div class="bd-pbar-fill" style="width:{{ min(100,$pct) }}%;background:{{ $pbarBg }};"></div>
        </div>
    </div>
    <div class="bd-pct" style="color:{{ $pctColor }};">{{ $pct }}%</div>
    <div class="bd-progress-label" style="color:{{ $pctColor }};">
        {{ $over ? '✓ Achieved' : ($warn ? '⏱ In Progress' : '⚠ Behind') }}
    </div>
</div>

{{-- Info box ── */
<div class="bd-info">
    <i data-feather="info"></i>
    <div>
        <strong>Margin = Payments Received − Proportional Vendor Cost</strong><br>
        <span style="color:#065F46;">
            Example: Booking ₹1,00,000 · Vendor Cost ₹50,000 · Payment ₹20,000 (20%)
            → Prop. Cost = ₹50,000 × 20% = ₹10,000 → <strong>Margin = ₹10,000</strong>
        </span>
    </div>
</div>

{{-- Breakdown Table --}}
<div class="bd-card">
    <div class="bd-card-head">
        <div class="bd-card-title"><i data-feather="list"></i> Booking Breakdown</div>
        <span style="font-size:.73rem;color:var(--tm);">{{ count($breakdown) }} bookings</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="bd-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Booking Date</th>
                    <th>Service Start</th>
                    <th class="text-center">Services</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-center">Pay %</th>
                    <th class="text-end">Vendor</th>
                    <th class="text-end">Prop. Cost</th>
                    <th class="text-end bd-th-margin">💰 Margin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breakdown as $item)
                <tr>
                    <td>
                        <button type="button" class="bd-cust-btn"
                            onclick="showCustomer({{ json_encode($item) }})"
                            title="View details">
                            <span style="font-weight:700;color:#4F46E5;">{{ $item['customer_name'] }}</span>
                            <i data-feather="chevron-right" style="width:12px;height:12px;stroke:#94A3B8;margin-left:4px;"></i>
                        </button>
                    </td>
                    <td style="font-size:.77rem;color:var(--tm);">{{ $item['booking_date'] }}</td>
                    <td>
                        <span class="bd-badge" style="background:#EEF2FF;color:#4F46E5;">{{ $item['service_start_date'] }}</span>
                    </td>
                    <td class="text-center">
                        <span class="bd-badge" style="background:#F1F5F9;color:var(--tm);">{{ $item['services_count'] }} svc</span>
                    </td>
                    <td class="text-end" style="font-size:.8rem;">₹{{ number_format($item['total_amount'],0) }}</td>
                    <td class="text-end bd-amt-blue">₹{{ number_format($item['payments_received'],0) }}</td>
                    <td class="text-center">
                        @php
                            $pp = $item['payment_percentage'];
                            $ppBg = $pp>=100?'#ECFDF5':($pp>=50?'#FFFBEB':'#FEF2F2');
                            $ppColor = $pp>=100?'#065F46':($pp>=50?'#92400E':'#991B1B');
                        @endphp
                        <span class="bd-badge" style="background:{{ $ppBg }};color:{{ $ppColor }};">{{ $pp }}%</span>
                    </td>
                    <td class="text-end bd-amt-muted">₹{{ number_format($item['vendor_cost'],0) }}</td>
                    <td class="text-end" style="font-size:.8rem;color:var(--tm);">₹{{ number_format($item['proportional_vendor_cost'],0) }}</td>
                    <td class="text-end" style="background:#FFFBF0;">
                        <span class="bd-margin-cell {{ $item['margin']>=0?'bd-margin-pos':'bd-margin-neg' }}">
                            {{ $item['margin']>=0?'+':'' }}₹{{ number_format($item['margin'],0) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10">
                    <div class="bd-empty">
                        <i data-feather="inbox"></i>
                        <p style="font-weight:700;color:#374151;margin:0 0 4px;">No bookings found</p>
                        <p style="font-size:.82rem;margin:0;">No bookings assigned for this period</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
            @if(count($breakdown) > 0)
            @php
                $sumTotal   = collect($breakdown)->sum('total_amount');
                $sumPaid    = collect($breakdown)->sum('payments_received');
                $sumVendor  = collect($breakdown)->sum('vendor_cost');
                $sumProp    = collect($breakdown)->sum('proportional_vendor_cost');
                $sumMargin  = $totalMargin;
                $overallPct = $sumTotal>0 ? round(($sumPaid/$sumTotal)*100,1) : 0;
            @endphp
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end" style="color:var(--tm);font-size:.75rem;letter-spacing:.04em;">TOTALS</td>
                    <td class="text-end">₹{{ number_format($sumTotal,0) }}</td>
                    <td class="text-end" style="color:#4F46E5;">₹{{ number_format($sumPaid,0) }}</td>
                    <td class="text-center">
                        <span class="bd-badge" style="background:#EEF2FF;color:#4F46E5;">{{ $overallPct }}%</span>
                    </td>
                    <td class="text-end" style="color:var(--tm);">₹{{ number_format($sumVendor,0) }}</td>
                    <td class="text-end" style="color:var(--tm);">₹{{ number_format($sumProp,0) }}</td>
                    <td class="text-end" style="background:#ECFDF5;">
                        <span class="bd-margin-cell bd-margin-pos" style="font-size:.88rem;">₹{{ number_format($sumMargin,0) }}</span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

</div>
{{-- Customer Detail Modal --}}
<div class="modal fade bd-cust-modal" id="custModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <svg width="17" height="17" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Customer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="custModalBody">
                {{-- Filled by JS --}}
            </div>
            <div class="modal-footer" style="padding:12px 20px;background:#F8FAFC;border-top:1px solid #E2E8F0;">
                <a href="#" id="custViewLink" class="bd-cust-view-btn" target="_blank">
                    <i data-feather="external-link" style="width:14px;height:14px;stroke:#fff;"></i> View Booking
                </a>
                <button type="button" style="background:#F1F5F9;color:#64748B;border:none;border-radius:10px;padding:10px 18px;font-weight:700;font-size:.85rem;cursor:pointer;" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showCustomer(item) {
    var fmtAmt = function(n){ return '₹' + parseFloat(n||0).toLocaleString('en-IN',{minimumFractionDigits:0}); };

    var ppBg    = item.payment_percentage>=100?'#ECFDF5':(item.payment_percentage>=50?'#FFFBEB':'#FEF2F2');
    var ppColor = item.payment_percentage>=100?'#065F46':(item.payment_percentage>=50?'#92400E':'#991B1B');
    var mgnBg   = item.margin>=0?'#ECFDF5':'#FEF2F2';
    var mgnColor= item.margin>=0?'#059669':'#DC2626';

    document.getElementById('custModalBody').innerHTML = `
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:#EEF2FF;"><i data-feather="user" style="stroke:#4F46E5;"></i></div>
            <div><div class="bd-detail-lbl">Customer Name</div><div class="bd-detail-val">${item.customer_name}</div></div>
        </div>
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:#FFF7ED;"><i data-feather="hash" style="stroke:#F59E0B;"></i></div>
            <div><div class="bd-detail-lbl">Booking Number</div><div class="bd-detail-val" style="color:#4F46E5;font-family:monospace;">${item.booking_number}</div></div>
        </div>
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:#F0FDF4;"><i data-feather="calendar" style="stroke:#10B981;"></i></div>
            <div><div class="bd-detail-lbl">Booking Date</div><div class="bd-detail-val">${item.booking_date}</div></div>
        </div>
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:#EEF2FF;"><i data-feather="play-circle" style="stroke:#4F46E5;"></i></div>
            <div><div class="bd-detail-lbl">Service Start</div><div class="bd-detail-val">${item.service_start_date}</div></div>
        </div>
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:#F0F9FF;"><i data-feather="briefcase" style="stroke:#0EA5E9;"></i></div>
            <div><div class="bd-detail-lbl">Services</div><div class="bd-detail-val">${item.services_count} service${item.services_count!=1?'s':''}</div></div>
        </div>
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:#EEF2FF;"><i data-feather="dollar-sign" style="stroke:#4F46E5;"></i></div>
            <div>
                <div class="bd-detail-lbl">Total Amount</div>
                <div class="bd-detail-val">${fmtAmt(item.total_amount)}</div>
            </div>
        </div>
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:#F0FDF4;"><i data-feather="credit-card" style="stroke:#10B981;"></i></div>
            <div>
                <div class="bd-detail-lbl">Payment Received</div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="bd-detail-val" style="color:#059669;">${fmtAmt(item.payments_received)}</div>
                    <span style="background:${ppBg};color:${ppColor};font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:20px;">${item.payment_percentage}%</span>
                </div>
            </div>
        </div>
        <div class="bd-detail-row">
            <div class="bd-detail-icon" style="background:${mgnBg};"><i data-feather="trending-up" style="stroke:${mgnColor};"></i></div>
            <div>
                <div class="bd-detail-lbl">Net Margin</div>
                <div class="bd-detail-val" style="font-size:1.05rem;color:${mgnColor};">${item.margin>=0?'+':''}${fmtAmt(item.margin)}</div>
            </div>
        </div>
    `;

    document.getElementById('custViewLink').href = '/visitkashiin/public/admin/bookings/' + item.booking_id;
    var modal = new bootstrap.Modal(document.getElementById('custModal'));
    modal.show();
    feather.replace();
}
document.addEventListener('DOMContentLoaded',function(){feather.replace();});
</script>
@endsection
