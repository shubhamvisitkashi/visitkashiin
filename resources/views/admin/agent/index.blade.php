@extends('admin.layouts.app')
@section('content')
<style>
/* ─── Page ───────────────────────────────────────────────────────── */
.ag-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ─── Hero ───────────────────────────────────────────────────────── */
.ag-hero{background:linear-gradient(135deg,#064E3B,#065F46,#059669);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(5,150,105,.28);}
.ag-hero::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.ag-hero::after{content:'🧑‍💼';position:absolute;bottom:-8px;right:18px;font-size:88px;opacity:.07;line-height:1;pointer-events:none;}
.ag-hero-left{position:relative;z-index:1;display:flex;align-items:center;gap:13px;}
.ag-hero-icon{width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ag-hero-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.ag-hero-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.ag-hero-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.ag-add-btn{position:relative;z-index:1;display:inline-flex;align-items:center;gap:7px;background:#fff;color:#059669;border:none;border-radius:10px;padding:10px 18px;font-size:.83rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:all .2s;white-space:nowrap;}
.ag-add-btn i[data-feather]{width:14px;height:14px;}
.ag-add-btn:hover{background:#ECFDF5;color:#047857;text-decoration:none;transform:translateY(-1px);}

/* ─── Stats ──────────────────────────────────────────────────────── */
.ag-stats{display:flex;gap:12px;margin-bottom:18px;flex-wrap:wrap;}
.ag-stat{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:13px 16px;display:flex;align-items:center;gap:11px;flex:1;min-width:140px;}
.ag-stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ag-stat-icon i[data-feather]{width:17px;height:17px;stroke:#fff;}
.ag-stat-val{font-size:1.3rem;font-weight:900;color:#0F172A;line-height:1;}
.ag-stat-lbl{font-size:.66rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-top:2px;}

/* ─── Filter ─────────────────────────────────────────────────────── */
.ag-filter{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:13px 16px;margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;}
.ag-filter select,.ag-filter input[type=text]{border:1.5px solid #E2E8F0;border-radius:9px;padding:7px 11px;font-size:.82rem;color:#0F172A;background:#FAFBFF;height:36px;outline:none;transition:border-color .15s;flex:1;min-width:150px;}
.ag-filter select:focus,.ag-filter input[type=text]:focus{border-color:#059669;}
.ag-filter-btn{height:36px;padding:0 15px;border-radius:9px;border:none;font-size:.8rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;flex-shrink:0;text-decoration:none;transition:all .15s;}
.ag-filter-btn i[data-feather]{width:13px;height:13px;}
.ag-filter-btn.primary{background:#059669;color:#fff;}.ag-filter-btn.primary:hover{background:#047857;}
.ag-filter-btn.ghost{background:#F1F5F9;color:#64748B;}.ag-filter-btn.ghost:hover{background:#E2E8F0;color:#374151;text-decoration:none;}

/* ─── Table card ─────────────────────────────────────────────────── */
.ag-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.ag-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#ECFDF5;}
.ag-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.ag-card-title i[data-feather]{width:15px;height:15px;stroke:#059669;}

/* ─── Table ──────────────────────────────────────────────────────── */
.ag-table{width:100%;border-collapse:collapse;}
.ag-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.ag-table td{padding:12px 14px;border-bottom:1px solid #F8FAFC;font-size:.83rem;color:#0F172A;vertical-align:middle;}
.ag-table tbody tr:last-child td{border-bottom:none;}
.ag-table tbody tr:hover{background:#F0FDF4;}

/* ─── Name cell ──────────────────────────────────────────────────── */
.ag-name-cell{display:flex;align-items:center;gap:10px;}
.ag-av{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#065F46,#059669);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:#fff;flex-shrink:0;}
.ag-name{font-weight:700;font-size:.85rem;color:#0F172A;}
.ag-phone{font-size:.72rem;color:#64748B;margin-top:1px;}

/* ─── Location ───────────────────────────────────────────────────── */
.ag-loc{font-size:.8rem;color:#374151;font-weight:600;}
.ag-city{font-size:.7rem;color:#94A3B8;margin-top:1px;}

/* ─── Added-by ───────────────────────────────────────────────────── */
.ag-by{display:inline-flex;align-items:center;gap:5px;background:#F0FDF4;color:#065F46;font-size:.71rem;font-weight:700;padding:3px 9px;border-radius:20px;border:1px solid #A7F3D0;}

/* ─── Service badges ─────────────────────────────────────────────── */
.ag-svc{display:flex;flex-wrap:wrap;gap:4px;}
.ag-svc-badge{font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;white-space:nowrap;}

/* ─── Status pills ───────────────────────────────────────────────── */
.ag-on {display:inline-flex;align-items:center;gap:4px;background:#ECFDF5;color:#065F46;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #A7F3D0;text-decoration:none;}
.ag-off{display:inline-flex;align-items:center;gap:4px;background:#FEF2F2;color:#DC2626;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #FECACA;text-decoration:none;}
.ag-on:hover,.ag-off:hover{opacity:.82;text-decoration:none;}

/* ─── Actions ────────────────────────────────────────────────────── */
.ag-actions{display:flex;gap:5px;justify-content:center;}
.ag-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.ag-act i[data-feather]{width:13px;height:13px;}
.ag-act-edit{background:#EEF2FF;color:#4F46E5;}.ag-act-edit:hover{background:#DDD6FE;text-decoration:none;}
.ag-act-del {background:#FEF2F2;color:#DC2626;}.ag-act-del:hover{background:#FECACA;text-decoration:none;}

/* ─── Empty ──────────────────────────────────────────────────────── */
.ag-empty{text-align:center;padding:52px;color:#94A3B8;}
.ag-empty i[data-feather]{width:52px;height:52px;opacity:.18;display:block;margin:0 auto 12px;stroke:#94A3B8;}

/* ─── Pagination ─────────────────────────────────────────────────── */
.ag-pag{padding:12px 18px;border-top:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
.ag-pag-info{font-size:.74rem;color:#64748B;font-weight:600;}

/* ─── Alerts ─────────────────────────────────────────────────────── */
.ag-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.ag-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.ag-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
.ag-alert i[data-feather]{width:18px;height:18px;flex-shrink:0;}

@media(max-width:900px){.ag-stats{flex-wrap:wrap;}.ag-stat{min-width:calc(50% - 6px);}}
@media(max-width:640px){.ag-page{padding:12px;}.ag-stat{min-width:calc(50% - 6px);}}
</style>

<div class="ag-page">

{{-- Flash --}}
@if(session('success'))
<div class="ag-alert ag-alert-ok"><i data-feather="check-circle" style="stroke:#10B981;"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="ag-alert ag-alert-err"><i data-feather="alert-circle" style="stroke:#EF4444;"></i> {{ session('error') }}</div>
@endif

{{-- Hero --}}
<div class="ag-hero">
    <div class="ag-hero-left">
        <div class="ag-hero-icon"><i data-feather="user-check"></i></div>
        <div class="ag-hero-text">
            <h1>Agent Management</h1>
            <p>Manage travel agents, partners &amp; their service assignments</p>
        </div>
    </div>
    <a href="{{ route('agent.create') }}" class="ag-add-btn">
        <i data-feather="user-plus"></i> Add Agent
    </a>
</div>

{{-- Stats --}}
@php
$statGradients = [
    'linear-gradient(135deg,#065F46,#059669)',
    'linear-gradient(135deg,#0369A1,#0891B2)',
    'linear-gradient(135deg,#6D28D9,#8B5CF6)',
    'linear-gradient(135deg,#C2410C,#F97316)',
    'linear-gradient(135deg,#B45309,#F59E0B)',
    'linear-gradient(135deg,#BE185D,#EC4899)',
];
@endphp
<div class="ag-stats">
    {{-- Total --}}
    <div class="ag-stat">
        <div class="ag-stat-icon" style="background:linear-gradient(135deg,#065F46,#059669);"><i data-feather="users"></i></div>
        <div><div class="ag-stat-val">{{ $total_agent }}</div><div class="ag-stat-lbl">Total Agents</div></div>
    </div>
    {{-- Per service --}}
    @foreach($agent_services as $idx => $svc)
    @php
        $cnt = App\Models\Agent::whereJsonContains('service_ids', '' . $svc->id)
            ->when(Auth::guard('admin')->user()->id != '1', fn($q) => $q->where('admin_id', Auth::guard('admin')->user()->id))
            ->when($search_staff,   fn($q) => $q->where('admin_id', $search_staff))
            ->when($search_state,   fn($q) => $q->where('state', $search_state))
            ->when($search_service, fn($q) => $q->whereJsonContains('service_ids', '' . $search_service))
            ->when($search_key,     fn($q) => $q->where(fn($qu) =>
                $qu->where('name','LIKE','%'.$search_key.'%')
                   ->orWhere('contact_number', $search_key)
                   ->orWhere('city', $search_key)
            ))->count();
        $grad = $statGradients[($idx + 1) % count($statGradients)];
    @endphp
    <div class="ag-stat">
        <div class="ag-stat-icon" style="background:{{ $grad }};"><i data-feather="tag"></i></div>
        <div><div class="ag-stat-val">{{ $cnt }}</div><div class="ag-stat-lbl">{{ $svc->name }}</div></div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form action="{{ route('agent.index') }}" method="GET" class="ag-filter">
    @if(Auth::guard('admin')->user()->id == 1)
    <select name="search_staff">
        <option value="">All Staff</option>
        @foreach($staffs as $s)
        <option value="{{ $s->id }}" {{ $s->id == $search_staff ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
    </select>
    @endif

    <select name="search_state">
        <option value="">All States</option>
        @foreach($states as $st)
        <option value="{{ $st->state }}" {{ $st->state == $search_state ? 'selected' : '' }}>{{ $st->state }}</option>
        @endforeach
    </select>

    <select name="search_service">
        <option value="">All Services</option>
        @foreach($agent_services as $svc)
        <option value="{{ $svc->id }}" {{ $svc->id == $search_service ? 'selected' : '' }}>{{ $svc->name }}</option>
        @endforeach
    </select>

    <input type="text" name="search_key" placeholder="🔍 Search name, phone, city…" value="{{ $search_key }}">

    <button type="submit" class="ag-filter-btn primary"><i data-feather="search"></i> Filter</button>

    @if($search_staff || $search_service || $search_key || $search_state)
    <a href="{{ route('agent.index') }}" class="ag-filter-btn ghost"><i data-feather="x"></i> Clear</a>
    @endif
</form>

{{-- Table card --}}
<div class="ag-card">
    <div class="ag-card-head">
        <div class="ag-card-title">
            <i data-feather="user-check"></i> All Agents
        </div>
        <span style="font-size:.73rem;color:#64748B;font-weight:600;">{{ $total_agent }} agents</span>
    </div>
    <div style="overflow-x:auto;" id="table">
        @include('admin.agent.table')
    </div>
</div>

</div>{{-- /.ag-page --}}

<script>
document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });

function delAgent(id, name) {
    Swal.fire({
        title: 'Delete Agent?',
        html: 'Remove <strong>' + name + '</strong>? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6C757D',
        confirmButtonText: 'Yes, delete!'
    }).then(function(r){ if(r.isConfirmed) document.getElementById('adel_'+id).submit(); });
}
</script>
@endsection
