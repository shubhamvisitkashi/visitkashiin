@extends('admin.layouts.app')
@section('content')
<style>
:root{--tg:#4F46E5;--tg-lt:#EEF2FF;--tgr:#10B981;--tgw:#F59E0B;--tgr2:#EF4444;--tt:#0F172A;--tm:#64748B;--tb:#E2E8F0;}
.tg-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* Header */
.tg-header{background:linear-gradient(135deg,#0f172a,#1e3a5f,#4F46E5);border-radius:16px;padding:22px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 28px rgba(79,70,229,.28);}
.tg-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.06);border-radius:50%;}
.tg-header-left{position:relative;z-index:1;}
.tg-header-left h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 3px;}
.tg-header-left p{color:rgba(255,255,255,.65);font-size:.78rem;margin:0;}
.tg-add-btn{position:relative;z-index:1;display:inline-flex;align-items:center;gap:7px;background:#fff;color:var(--tg);border:none;border-radius:10px;padding:10px 18px;font-size:.82rem;font-weight:700;cursor:pointer;box-shadow:0 2px 10px rgba(0,0,0,.15);transition:all .2s;white-space:nowrap;flex-shrink:0;}
.tg-add-btn:hover{background:#f0f0ff;color:var(--tg);transform:translateY(-1px);}
.tg-add-btn i[data-feather]{width:15px;height:15px;}

/* Filter bar */
.tg-filter{background:#fff;border-radius:14px;border:1px solid var(--tb);box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 20px;margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;}
.tg-filter-group{display:flex;flex-direction:column;gap:5px;flex:1;min-width:120px;}
.tg-filter-group label{font-size:.7rem;font-weight:700;color:var(--tm);text-transform:uppercase;letter-spacing:.04em;}
.tg-filter-group select{border:1.5px solid var(--tb);border-radius:9px;padding:7px 11px;font-size:.82rem;color:var(--tt);background:#FAFBFF;height:36px;outline:none;transition:border-color .15s;cursor:pointer;}
.tg-filter-group select:focus{border-color:var(--tg);}
.tg-filter-btn{height:36px;padding:0 16px;border-radius:9px;border:none;font-size:.8rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;align-self:flex-end;transition:all .15s;}
.tg-filter-btn.primary{background:var(--tg);color:#fff;}
.tg-filter-btn.primary:hover{background:#4338CA;}
.tg-filter-btn.ghost{background:#F1F5F9;color:var(--tm);text-decoration:none;}
.tg-filter-btn.ghost:hover{background:#E2E8F0;color:var(--tt);}
.tg-filter-btn.success{background:#10B981;color:#fff;}
.tg-filter-btn.success:hover{background:#059669;}
.tg-filter-btn i[data-feather]{width:13px;height:13px;}

/* Section header */
.tg-section-head{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#fff;border-radius:14px 14px 0 0;border:1px solid var(--tb);border-bottom:1.5px solid #F0F0F5;}
.tg-section-title{font-size:.9rem;font-weight:700;color:var(--tt);display:flex;align-items:center;gap:8px;}
.tg-section-title i[data-feather]{width:16px;height:16px;stroke:var(--tg);}
.tg-period{font-size:.78rem;font-weight:700;color:var(--tg);background:var(--tg-lt);padding:4px 12px;border-radius:20px;}

/* Target card */
.tg-card{background:#fff;border:1px solid var(--tb);border-top:none;padding:16px 20px;border-bottom:1px solid #F8FAFC;display:flex;align-items:center;gap:16px;transition:background .15s;flex-wrap:wrap;}
.tg-card:last-child{border-radius:0 0 14px 14px;border-bottom:1px solid var(--tb);}
.tg-card:hover{background:#FAFBFF;}
.tg-card.tg-achieved{border-left:4px solid #10B981;}
.tg-card.tg-pending {border-left:4px solid #F59E0B;}
.tg-card.tg-behind  {border-left:4px solid #EF4444;}

/* Avatar */
.tg-av{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#4F46E5,#7C3AED);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:#fff;flex-shrink:0;}

/* Staff info */
.tg-staff{min-width:140px;}
.tg-staff-name{font-size:.88rem;font-weight:700;color:var(--tt);}
.tg-staff-email{font-size:.72rem;color:var(--tm);margin-top:2px;}

/* Stat boxes */
.tg-stats{display:flex;gap:10px;flex-wrap:wrap;}
.tg-stat{background:#F8FAFC;border:1px solid var(--tb);border-radius:10px;padding:8px 14px;text-align:center;min-width:90px;}
.tg-stat-lbl{font-size:.65rem;font-weight:700;color:var(--tm);text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.tg-stat-val{font-size:.9rem;font-weight:800;color:var(--tt);}
.tg-stat-val.green{color:#059669;}
.tg-stat-val.amber{color:#B45309;}
.tg-stat-val.red  {color:#DC2626;}
.tg-stat-val.blue {color:var(--tg);}

/* Progress */
.tg-progress-wrap{flex:1;min-width:160px;}
.tg-progress-row{display:flex;justify-content:space-between;font-size:.72rem;font-weight:700;color:var(--tm);margin-bottom:5px;}
.tg-progress-pct{font-weight:800;}
.tg-pbar{height:8px;background:#E2E8F0;border-radius:99px;overflow:hidden;}
.tg-pbar-fill{height:100%;border-radius:99px;transition:width .4s ease;}
.pbar-green{background:linear-gradient(90deg,#10B981,#059669);}
.pbar-amber{background:linear-gradient(90deg,#F59E0B,#D97706);}
.pbar-red  {background:linear-gradient(90deg,#EF4444,#DC2626);}

/* Status badge */
.tg-badge{display:inline-flex;align-items:center;gap:4px;font-size:.68rem;font-weight:700;padding:4px 10px;border-radius:20px;white-space:nowrap;}
.tg-badge-achieved{background:#ECFDF5;color:#065F46;}
.tg-badge-pending {background:#FFFBEB;color:#92400E;}
.tg-badge-behind  {background:#FEF2F2;color:#991B1B;}

/* Actions */
.tg-actions{display:flex;gap:5px;align-items:center;flex-shrink:0;}
.tg-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;transition:all .15s;background:transparent;text-decoration:none;}
.tg-act i[data-feather]{width:13px;height:13px;}
.tg-act-view{background:#EEF2FF;color:var(--tg);}.tg-act-view:hover{background:#ddd6fe;}
.tg-act-edit{background:#FEF3C7;color:#92400E;}.tg-act-edit:hover{background:#fde68a;}
.tg-act-del {background:#FEF2F2;color:#DC2626;}.tg-act-del:hover{background:#FECACA;}

/* Notes */
.tg-notes{width:100%;font-size:.74rem;color:var(--tm);background:#F8FAFC;border-radius:8px;padding:7px 12px;display:flex;align-items:center;gap:6px;margin-top:8px;}
.tg-notes i[data-feather]{width:12px;height:12px;flex-shrink:0;}

/* Empty */
.tg-empty{background:#fff;border:1px solid var(--tb);border-top:none;border-radius:0 0 14px 14px;text-align:center;padding:52px 24px;}
.tg-empty i[data-feather]{width:52px;height:52px;opacity:.2;display:block;margin:0 auto 14px;}

/* Alert */
.tg-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:.85rem;font-weight:600;}
.tg-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.tg-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}

/* Modal */
.tg-modal .modal-content{border:none;border-radius:18px;overflow:hidden;}
.tg-modal .modal-header{padding:18px 24px;border:none;}
.tg-modal .modal-header h5{font-size:1rem;font-weight:800;display:flex;align-items:center;gap:8px;}
.tg-modal .modal-body{padding:20px 24px;}
.tg-modal .modal-footer{padding:14px 24px;background:#F8FAFC;border-top:1px solid var(--tb);}
.tg-label{font-size:.72rem;font-weight:700;color:var(--tm);text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.tg-input{border:1.5px solid var(--tb)!important;border-radius:10px!important;padding:9px 13px!important;font-size:.87rem!important;color:var(--tt)!important;background:#FAFBFF!important;box-shadow:none!important;}
.tg-input:focus{border-color:var(--tg)!important;box-shadow:0 0 0 3px rgba(79,70,229,.1)!important;}
.tg-modal-submit{background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:.88rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;}
.tg-modal-submit:hover{opacity:.88;color:#fff;}
.tg-modal-cancel{background:#F1F5F9;color:var(--tm);border:none;border-radius:10px;padding:10px 20px;font-size:.88rem;font-weight:700;cursor:pointer;}

/* ── Target → Progress → Achieved strip ── */
.tg-tpa{display:flex;align-items:center;gap:10px;flex:2;min-width:0;}
.tg-tpa-box{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:8px 14px;text-align:center;min-width:100px;}
.tg-tpa-target{border-color:#C7D2FE;background:#EEF2FF;}
.tg-tpa-achieved{border-color:#A7F3D0;background:#ECFDF5;}
.tg-tpa-lbl{font-size:.64rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.tg-tpa-val{font-size:.95rem;font-weight:900;color:#0F172A;}
.tg-tpa-val.green{color:#059669;}
.tg-tpa-val.amber{color:#B45309;}
.tg-tpa-val.red  {color:#DC2626;}
.tg-tpa-val.blue {color:#4F46E5;}
.tg-tpa-mid{flex:1;min-width:100px;}
.tg-tpa-pct{font-size:.82rem;font-weight:800;text-align:center;}

/* Responsive */
@media(max-width:768px){
    .tg-page{padding:12px;}
    .tg-header{flex-direction:column;align-items:flex-start;padding:16px 18px;}
    .tg-card{flex-direction:column;align-items:flex-start;}
    .tg-progress-wrap{width:100%;}
}
</style>

<div class="tg-page">

@if(session('success'))
<div class="tg-alert tg-alert-ok"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="tg-alert tg-alert-err"><i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i> {{ session('error') }}</div>
@endif

{{-- Header --}}
<div class="tg-header">
    <div class="tg-header-left">
        <h1>🎯 Target Management</h1>
        <p>Set and monitor monthly margin targets for each staff member</p>
    </div>
    <button type="button" class="tg-add-btn" data-bs-toggle="modal" data-bs-target="#setTargetModal">
        <i data-feather="plus"></i> Set New Target
    </button>
</div>

{{-- Filter + Recalculate --}}
@php
    $prevDate = \Carbon\Carbon::create($year, $month, 1)->subMonth();
    $nextDate = \Carbon\Carbon::create($year, $month, 1)->addMonth();
@endphp
<form method="GET" action="{{ route('targets.index') }}" class="tg-filter" id="tgFilterForm">

    {{-- ← Prev month --}}
    <a href="{{ route('targets.index', ['month'=>$prevDate->month,'year'=>$prevDate->year]) }}"
       class="tg-filter-btn ghost" style="padding:0 10px;font-size:1rem;" title="{{ $prevDate->format('F Y') }}">&#8249;</a>

    {{-- Current period display --}}
    <div style="display:flex;align-items:center;background:#EEF2FF;border:1.5px solid #C7D2FE;border-radius:9px;padding:0 14px;height:36px;font-weight:800;font-size:.85rem;color:#4F46E5;white-space:nowrap;flex-shrink:0;">
        {{ date('F',mktime(0,0,0,$month,1)) }} {{ $year }}
    </div>

    {{-- → Next month --}}
    <a href="{{ route('targets.index', ['month'=>$nextDate->month,'year'=>$nextDate->year]) }}"
       class="tg-filter-btn ghost" style="padding:0 10px;font-size:1rem;" title="{{ $nextDate->format('F Y') }}">&#8250;</a>

    <div class="tg-filter-group">
        <label>Month</label>
        <select name="month">
            @for($m=1;$m<=12;$m++)
            <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>
            @endfor
        </select>
    </div>
    <div class="tg-filter-group">
        <label>Year</label>
        <select name="year">
            @for($y=date('Y')-2;$y<=date('Y')+2;$y++)
            <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <button type="submit" class="tg-filter-btn primary"><i data-feather="filter"></i> Filter</button>
    <a href="{{ route('targets.index') }}" class="tg-filter-btn ghost"><i data-feather="x"></i> Clear</a>
    <form method="POST" action="{{ route('targets.recalculate') }}" style="display:contents;">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">
        <button type="submit" class="tg-filter-btn success"><i data-feather="refresh-cw"></i> Recalculate</button>
    </form>
</form>

{{-- Targets List --}}
<div class="tg-section-head">
    <div class="tg-section-title"><i data-feather="target"></i> Targets Overview</div>
    <span class="tg-period">{{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</span>
</div>

@if($targets->count() > 0)
    @foreach($targets as $target)
    @php
        $pct  = min(100, $target->achievement_percentage);
        $over = $pct >= 100;
        $warn = $pct >= 50;
        $cls  = $over ? 'tg-achieved' : ($warn ? 'tg-pending' : 'tg-behind');
        $pbarCls = $over ? 'pbar-green' : ($warn ? 'pbar-amber' : 'pbar-red');
        $valCls  = $over ? 'green'      : ($warn ? 'amber'      : 'red');
        $badgeCls = $over ? 'tg-badge-achieved' : ($warn ? 'tg-badge-pending' : 'tg-badge-behind');
        $badgeTxt = $over ? '✓ Achieved' : ($warn ? '⏱ In Progress' : '⚠ Behind');
        $initial  = strtoupper(substr($target->user->name,0,1));
    @endphp
    <div class="tg-card {{ $cls }}">
        {{-- Avatar + Name --}}
        <div class="tg-av">{{ $initial }}</div>
        <div class="tg-staff" style="flex:1;min-width:130px;">
            <div class="tg-staff-name">{{ $target->user->name }}</div>
            <div class="tg-staff-email">{{ $target->user->email }}</div>
        </div>

        {{-- Target → Progress → Achieved strip --}}
        <div class="tg-tpa">
            {{-- Target --}}
            <div class="tg-tpa-box tg-tpa-target">
                <div class="tg-tpa-lbl">🎯 Target</div>
                <div class="tg-tpa-val">₹{{ number_format($target->target_margin,0) }}</div>
            </div>

            {{-- Progress bar column --}}
            <div class="tg-tpa-mid">
                <div class="tg-tpa-pct" style="color:{{ $over?'#059669':($warn?'#B45309':'#DC2626') }};">{{ $pct }}%</div>
                <div class="tg-pbar" style="margin:4px 0;">
                    <div class="tg-pbar-fill {{ $pbarCls }}" style="width:{{ $pct }}%;"></div>
                </div>
                <span class="tg-badge {{ $badgeCls }}" style="font-size:.62rem;padding:2px 8px;">{{ $badgeTxt }}</span>
            </div>

            {{-- Achieved --}}
            <div class="tg-tpa-box tg-tpa-achieved">
                <div class="tg-tpa-lbl">✅ Achieved</div>
                <div class="tg-tpa-val {{ $valCls }}">₹{{ number_format($target->achieved_margin,0) }}</div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="tg-actions">
            <a href="{{ route('targets.breakdown', $target->id) }}" class="tg-act tg-act-view" title="View Breakdown">
                <i data-feather="list"></i>
            </a>
            <button type="button" class="tg-act tg-act-edit" title="Edit"
                onclick="editTarget({{ $target->id }}, {{ $target->target_margin }}, '{{ addslashes($target->notes ?? '') }}')">
                <i data-feather="edit-2"></i>
            </button>
            <button type="button" class="tg-act tg-act-del" title="Delete"
                onclick="deleteTarget({{ $target->id }}, '{{ addslashes($target->user->name) }}')">
                <i data-feather="trash-2"></i>
            </button>
        </div>

        {{-- Notes --}}
        @if($target->notes)
        <div class="tg-notes" style="flex-basis:100%;">
            <i data-feather="message-circle"></i> {{ $target->notes }}
        </div>
        @endif
    </div>
    @endforeach
@else
<div class="tg-empty">
    <i data-feather="target"></i>
    <p style="font-weight:700;color:#374151;margin:0 0 6px;font-size:1rem;">No Targets Set</p>
    <p style="font-size:.83rem;color:var(--tm);margin:0 0 16px;">Set targets for staff members to track their monthly performance</p>
    <button type="button" class="tg-add-btn" data-bs-toggle="modal" data-bs-target="#setTargetModal" style="margin:0 auto;">
        <i data-feather="plus"></i> Set First Target
    </button>
</div>
@endif

</div>

{{-- Set Target Modal --}}
<div class="modal fade tg-modal" id="setTargetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('targets.store') }}">
                @csrf
                <div class="modal-header" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);">
                    <h5 class="modal-title" style="color:#fff;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="2"/><circle cx="12" cy="12" r="6" stroke="#fff" stroke-width="2"/><circle cx="12" cy="12" r="2" fill="#fff"/></svg>
                        Set New Target
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="tg-label">Staff Member <span style="color:#EF4444;">*</span></label>
                        <select name="user_id" class="form-select tg-input" required>
                            <option value="">— Select Staff Member —</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="tg-label">Month <span style="color:#EF4444;">*</span></label>
                            <select name="month" class="form-select tg-input" required>
                                @for($m=1;$m<=12;$m++)
                                <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="tg-label">Year <span style="color:#EF4444;">*</span></label>
                            <select name="year" class="form-select tg-input" required>
                                @for($y=date('Y')-1;$y<=date('Y')+1;$y++)
                                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="tg-label">Target Margin ₹ <span style="color:#EF4444;">*</span></label>
                        <input type="number" name="target_margin" class="form-control tg-input" placeholder="e.g. 50000" min="0" step="100" required>
                        <div style="font-size:.72rem;color:var(--tm);margin-top:5px;">Monthly profit target for this staff member</div>
                    </div>
                    <div class="mb-1">
                        <label class="tg-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control tg-input" rows="2" placeholder="Any additional notes…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="tg-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tg-modal-submit"><i data-feather="save" style="width:14px;height:14px;stroke:#fff;"></i> Set Target</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Target Modal --}}
<div class="modal fade tg-modal" id="editTargetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editTargetForm">
                @csrf @method('PUT')
                <div class="modal-header" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
                    <h5 class="modal-title" style="color:#fff;">
                        <i data-feather="edit-2" style="width:17px;height:17px;stroke:#fff;"></i> Edit Target
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="tg-label">Target Margin ₹ <span style="color:#EF4444;">*</span></label>
                        <input type="number" name="target_margin" id="edit_target_margin" class="form-control tg-input" min="0" step="100" required>
                    </div>
                    <div class="mb-1">
                        <label class="tg-label">Notes (Optional)</label>
                        <textarea name="notes" id="edit_notes" class="form-control tg-input" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="tg-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tg-modal-submit" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
                        <i data-feather="save" style="width:14px;height:14px;stroke:#fff;"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var targetsBaseUrl = '{{ url('admin/targets') }}';
function editTarget(id, targetMargin, notes) {
    document.getElementById('editTargetForm').action = targetsBaseUrl + '/' + id;
    document.getElementById('edit_target_margin').value = targetMargin;
    document.getElementById('edit_notes').value = notes || '';
    new bootstrap.Modal(document.getElementById('editTargetModal')).show();
}
function deleteTarget(id, userName) {
    Swal.fire({
        title: 'Delete Target?',
        html: 'Delete target for <strong>' + userName + '</strong>? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = targetsBaseUrl + '/' + id;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });
</script>
@endsection
