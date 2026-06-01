@extends('admin.layouts.app')
@section('content')
<style>
/* ─── Page ───────────────────────────────────────────────────────── */
.vn-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ─── Hero ───────────────────────────────────────────────────────── */
.vn-hero{background:linear-gradient(135deg,#1E1B4B,#4338CA,#6366F1);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(67,56,202,.28);}
.vn-hero::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.vn-hero::after{content:'🤝';position:absolute;bottom:-10px;right:18px;font-size:88px;opacity:.07;line-height:1;pointer-events:none;}
.vn-hero-left{position:relative;z-index:1;display:flex;align-items:center;gap:13px;}
.vn-hero-icon{width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.vn-hero-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.vn-hero-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.vn-hero-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.vn-hero-right{position:relative;z-index:1;display:flex;align-items:center;gap:8px;}
.vn-add-btn{display:inline-flex;align-items:center;gap:7px;background:#fff;color:#4338CA;border:none;border-radius:10px;padding:10px 18px;font-size:.83rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:all .2s;white-space:nowrap;}
.vn-add-btn i[data-feather]{width:14px;height:14px;}
.vn-add-btn:hover{background:#EEF2FF;color:#4338CA;text-decoration:none;transform:translateY(-1px);}

/* ─── Stats grid ─────────────────────────────────────────────────── */
.vn-stats{display:flex;gap:12px;margin-bottom:18px;flex-wrap:wrap;}
.vn-stat{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:13px 16px;display:flex;align-items:center;gap:11px;flex:1;min-width:140px;}
.vn-stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.vn-stat-icon i[data-feather]{width:17px;height:17px;stroke:#fff;}
.vn-stat-val{font-size:1.3rem;font-weight:900;color:#0F172A;line-height:1;}
.vn-stat-lbl{font-size:.66rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-top:2px;}

/* ─── Filter card ────────────────────────────────────────────────── */
.vn-filter{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:13px 16px;margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;}
.vn-filter select,.vn-filter input[type=text]{border:1.5px solid #E2E8F0;border-radius:9px;padding:7px 11px;font-size:.82rem;color:#0F172A;background:#FAFBFF;height:36px;outline:none;transition:border-color .15s;flex:1;min-width:150px;}
.vn-filter select:focus,.vn-filter input[type=text]:focus{border-color:#4338CA;}
.vn-filter-btn{height:36px;padding:0 15px;border-radius:9px;border:none;font-size:.8rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;flex-shrink:0;text-decoration:none;transition:all .15s;}
.vn-filter-btn i[data-feather]{width:13px;height:13px;}
.vn-filter-btn.primary{background:#4338CA;color:#fff;}.vn-filter-btn.primary:hover{background:#3730A3;}
.vn-filter-btn.ghost{background:#F1F5F9;color:#64748B;}.vn-filter-btn.ghost:hover{background:#E2E8F0;color:#374151;text-decoration:none;}

/* ─── Table card ─────────────────────────────────────────────────── */
.vn-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.vn-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#EEF2FF;}
.vn-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.vn-card-title i[data-feather]{width:15px;height:15px;stroke:#4338CA;}

/* ─── Table ──────────────────────────────────────────────────────── */
.vn-table{width:100%;border-collapse:collapse;}
.vn-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.vn-table td{padding:12px 14px;border-bottom:1px solid #F8FAFC;font-size:.83rem;color:#0F172A;vertical-align:middle;}
.vn-table tbody tr:last-child td{border-bottom:none;}
.vn-table tbody tr:hover{background:#EEF2FF;}

/* ─── Vendor name cell ───────────────────────────────────────────── */
.vn-name-cell{display:flex;align-items:center;gap:10px;}
.vn-av{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4338CA,#6366F1);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:#fff;flex-shrink:0;}
.vn-name{font-weight:700;font-size:.85rem;color:#0F172A;}
.vn-phone{font-size:.72rem;color:#64748B;margin-top:1px;}

/* ─── Location cell ──────────────────────────────────────────────── */
.vn-loc{font-size:.8rem;color:#374151;}
.vn-city{font-size:.7rem;color:#94A3B8;margin-top:1px;}

/* ─── Added-by cell ──────────────────────────────────────────────── */
.vn-addedby{display:inline-flex;align-items:center;gap:5px;background:#F5F3FF;color:#6D28D9;font-size:.71rem;font-weight:700;padding:3px 9px;border-radius:20px;border:1px solid #DDD6FE;}

/* ─── Service badges ─────────────────────────────────────────────── */
.vn-svc{display:flex;flex-wrap:wrap;gap:4px;}
.vn-svc-badge{font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;white-space:nowrap;}

/* ─── Status toggle ──────────────────────────────────────────────── */
.vn-status-on {display:inline-flex;align-items:center;gap:4px;background:#ECFDF5;color:#065F46;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #A7F3D0;text-decoration:none;}
.vn-status-off{display:inline-flex;align-items:center;gap:4px;background:#FEF2F2;color:#DC2626;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #FECACA;text-decoration:none;}
.vn-status-on:hover,.vn-status-off:hover{text-decoration:none;opacity:.85;}

/* ─── Actions ────────────────────────────────────────────────────── */
.vn-actions{display:flex;gap:5px;justify-content:center;}
.vn-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.vn-act i[data-feather]{width:13px;height:13px;}
.vn-act-edit{background:#EEF2FF;color:#4F46E5;}.vn-act-edit:hover{background:#DDD6FE;text-decoration:none;}
.vn-act-del {background:#FEF2F2;color:#DC2626;}.vn-act-del:hover{background:#FECACA;text-decoration:none;}

/* ─── Empty ──────────────────────────────────────────────────────── */
.vn-empty{text-align:center;padding:52px;color:#94A3B8;}
.vn-empty i[data-feather]{width:52px;height:52px;opacity:.2;display:block;margin:0 auto 12px;stroke:#94A3B8;}

/* ─── Pagination ─────────────────────────────────────────────────── */
.vn-pag{padding:12px 18px;border-top:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
.vn-pag-info{font-size:.74rem;color:#64748B;font-weight:600;}

/* ─── Alert ──────────────────────────────────────────────────────── */
.vn-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.vn-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.vn-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
.vn-alert i[data-feather]{width:18px;height:18px;flex-shrink:0;}

@media(max-width:900px){.vn-stats{flex-wrap:wrap;}.vn-stat{min-width:calc(50% - 6px);}}
@media(max-width:640px){.vn-page{padding:12px;}.vn-stat{min-width:calc(50% - 6px);}}
</style>

<div class="vn-page">

{{-- Flash --}}
@if(session('success'))
<div class="vn-alert vn-alert-ok"><i data-feather="check-circle" style="stroke:#10B981;"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="vn-alert vn-alert-err"><i data-feather="alert-circle" style="stroke:#EF4444;"></i> {{ session('error') }}</div>
@endif

{{-- Hero --}}
<div class="vn-hero">
    <div class="vn-hero-left">
        <div class="vn-hero-icon"><i data-feather="briefcase"></i></div>
        <div class="vn-hero-text">
            <h1>Vendor Management</h1>
            <p>Manage vendors, service partners &amp; their details</p>
        </div>
    </div>
    <div class="vn-hero-right">
        <a href="{{ route('vendor.create') }}" class="vn-add-btn">
            <i data-feather="plus"></i> Add Vendor
        </a>
    </div>
</div>

{{-- Stats --}}
@php
$statColors = [
    'linear-gradient(135deg,#4338CA,#6366F1)',
    'linear-gradient(135deg,#059669,#10B981)',
    'linear-gradient(135deg,#0369A1,#0891B2)',
    'linear-gradient(135deg,#C2410C,#F97316)',
    'linear-gradient(135deg,#B45309,#F59E0B)',
    'linear-gradient(135deg,#7C3AED,#A78BFA)',
];
@endphp
<div class="vn-stats">
    <div class="vn-stat">
        <div class="vn-stat-icon" style="background:linear-gradient(135deg,#4338CA,#6366F1);"><i data-feather="users"></i></div>
        <div><div class="vn-stat-val">{{ $total_vendor }}</div><div class="vn-stat-lbl">Total Vendors</div></div>
    </div>
    @foreach($vendor_services as $idx => $vs)
    @php
        $vsCount = App\Models\Vendor::whereJsonContains('service_ids',''.$vs->id)
            ->when(Auth::guard('admin')->user()->id != '1', fn($q) => $q->where('admin_id', Auth::guard('admin')->user()->id))
            ->when($search_staff, fn($q) => $q->where('admin_id', $search_staff))
            ->when($search_state, fn($q) => $q->where('state', $search_state))
            ->when($search_key, fn($q) => $q->where(fn($qu) => $qu->where('name','LIKE','%'.$search_key.'%')->orWhere('contact_number',$search_key)->orWhere('city',$search_key)))
            ->count();
        $color = $statColors[($idx + 1) % count($statColors)];
    @endphp
    <div class="vn-stat">
        <div class="vn-stat-icon" style="background:{{ $color }};"><i data-feather="tag"></i></div>
        <div><div class="vn-stat-val">{{ $vsCount }}</div><div class="vn-stat-lbl">{{ $vs->name }}</div></div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form action="{{ route('vendor.index') }}" method="GET" id="filterForm" class="vn-filter">
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
        @foreach($vendor_services as $vs)
        <option value="{{ $vs->id }}" {{ $vs->id == $search_service ? 'selected' : '' }}>{{ $vs->name }}</option>
        @endforeach
    </select>
    <input type="text" name="search_key" placeholder="🔍 Search name, phone…" value="{{ $search_key }}">
    <button type="submit" class="vn-filter-btn primary"><i data-feather="search"></i> Filter</button>
    @if($search_staff || $search_service || $search_key || $search_state)
    <a href="{{ route('vendor.index') }}" class="vn-filter-btn ghost"><i data-feather="x"></i> Clear</a>
    @endif
</form>

{{-- Table card --}}
<div class="vn-card">
    <div class="vn-card-head">
        <div class="vn-card-title">
            <i data-feather="briefcase"></i> All Vendors
        </div>
        <span style="font-size:.73rem;color:#64748B;font-weight:600;">{{ $total_vendor }} vendors</span>
    </div>
    <div style="overflow-x:auto;" id="table">
        @include('admin.crm.vendor.table')
    </div>
</div>

</div>{{-- /.vn-page --}}

<script>
document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });

function delVendor(id, name) {
    Swal.fire({
        title: 'Delete Vendor?',
        html: 'Remove <strong>' + name + '</strong>? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6C757D',
        confirmButtonText: 'Yes, delete!'
    }).then(function(r){ if(r.isConfirmed) document.getElementById('vdel_'+id).submit(); });
}
</script>
@endsection
