@extends('admin.layouts.app')
@section('content')
<style>
.sf-page{padding:24px;background:#F1F5F9;min-height:100vh;}
/* Header */
.sf-header{background:linear-gradient(135deg,#312E81,#4F46E5,#7C3AED);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:22px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(79,70,229,.28);}
.sf-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.sf-header::after{content:'👥';position:absolute;bottom:-10px;right:18px;font-size:84px;opacity:.07;line-height:1;pointer-events:none;}
.sf-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:13px;}
.sf-header-icon{width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sf-header-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.sf-header-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.sf-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.sf-header-right{position:relative;z-index:1;}
.sf-add-btn{display:inline-flex;align-items:center;gap:7px;background:#fff;color:#4F46E5;border:none;border-radius:10px;padding:9px 18px;font-size:.83rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:all .2s;white-space:nowrap;}
.sf-add-btn:hover{background:#EEF2FF;color:#4338CA;text-decoration:none;transform:translateY(-1px);}
.sf-add-btn i[data-feather]{width:14px;height:14px;}
/* Stats */
.sf-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:22px;}
.sf-stat{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 16px;display:flex;align-items:center;gap:12px;}
.sf-stat-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sf-stat-icon i[data-feather]{width:18px;height:18px;stroke:#fff;}
.sf-stat-val{font-size:1.35rem;font-weight:900;color:#0F172A;line-height:1;}
.sf-stat-lbl{font-size:.67rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-top:2px;}
/* Grid */
.sf-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:18px;}
/* ID Card */
.sf-card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;box-shadow:0 2px 12px rgba(0,0,0,.07);overflow:hidden;transition:transform .2s,box-shadow .2s;position:relative;}
.sf-card:hover{transform:translateY(-3px);box-shadow:0 8px 28px rgba(0,0,0,.12);}
/* Card header strip */
.sf-card-strip{height:72px;position:relative;}
.sf-card-strip::after{content:'';position:absolute;bottom:0;left:0;right:0;height:20px;background:inherit;clip-path:ellipse(55% 100% at 50% 100%);}
/* Avatar overlapping strip */
.sf-card-av-wrap{display:flex;justify-content:center;margin-top:-32px;position:relative;z-index:2;}
.sf-card-av{width:66px;height:66px;border-radius:50%;border:3px solid #fff;box-shadow:0 3px 12px rgba(0,0,0,.15);overflow:hidden;background:linear-gradient(135deg,#6366F1,#8B5CF6);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:900;color:#fff;flex-shrink:0;}
.sf-card-av img{width:100%;height:100%;object-fit:cover;display:block;}
/* Card body */
.sf-card-body{padding:10px 16px 16px;text-align:center;}
.sf-card-name{font-size:.95rem;font-weight:800;color:#0F172A;margin:0 0 3px;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.sf-card-email{font-size:.72rem;color:#64748B;margin:0 0 10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
/* Roles */
.sf-card-roles{display:flex;flex-wrap:wrap;gap:4px;justify-content:center;margin-bottom:12px;min-height:22px;}
.sf-role-badge{font-size:.65rem;font-weight:700;padding:2px 9px;border-radius:20px;white-space:nowrap;}
/* Divider */
.sf-card-divider{height:1px;background:#F1F5F9;margin:0 0 12px;}
/* ID number strip */
.sf-card-id{font-size:.68rem;color:#94A3B8;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:12px;}
/* Actions */
.sf-card-actions{display:flex;gap:7px;justify-content:center;}
.sf-card-btn{flex:1;height:34px;border-radius:9px;border:none;font-size:.78rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:5px;text-decoration:none;transition:all .15s;max-width:90px;}
.sf-card-btn i[data-feather]{width:13px;height:13px;}
.sf-card-btn-edit{background:#EEF2FF;color:#4F46E5;}.sf-card-btn-edit:hover{background:#DDD6FE;color:#4338CA;text-decoration:none;}
.sf-card-btn-del {background:#FEF2F2;color:#DC2626;}.sf-card-btn-del:hover{background:#FECACA;text-decoration:none;}
/* Company watermark on card */
.sf-card-watermark{font-size:.6rem;color:#CBD5E1;text-transform:uppercase;letter-spacing:.1em;font-weight:700;margin-top:4px;}
/* Empty */
.sf-empty{text-align:center;padding:60px 24px;color:#94A3B8;background:#fff;border-radius:14px;border:1px solid #E2E8F0;}
.sf-empty i[data-feather]{width:56px;height:56px;opacity:.2;display:block;margin:0 auto 12px;stroke:#94A3B8;}
/* Alert */
.sf-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:18px;font-size:.83rem;font-weight:600;}
.sf-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.sf-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
/* Pagination */
.sf-pag{margin-top:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
.sf-pag-info{font-size:.75rem;color:#64748B;font-weight:600;background:#fff;border:1px solid #E2E8F0;border-radius:9px;padding:6px 12px;}
@media(max-width:900px){.sf-stats{grid-template-columns:1fr 1fr;}}
@media(max-width:640px){.sf-page{padding:12px;}.sf-stats{grid-template-columns:1fr 1fr;}.sf-grid{grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;}}
</style>

{{-- Role → colour map helper --}}
@php
$roleColors = [
    'admin'       => ['bg'=>'#312E81','light'=>'#EEF2FF','text'=>'#4F46E5'],
    'manager'     => ['bg'=>'#065F46','light'=>'#ECFDF5','text'=>'#059669'],
    'sales'       => ['bg'=>'#92400E','light'=>'#FFFBEB','text'=>'#D97706'],
    'accountant'  => ['bg'=>'#1E3A5F','light'=>'#EFF6FF','text'=>'#2563EB'],
    'staff'       => ['bg'=>'#3F3F46','light'=>'#F4F4F5','text'=>'#52525B'],
    'superadmin'  => ['bg'=>'#4C1D95','light'=>'#F5F3FF','text'=>'#7C3AED'],
];
$stripGradients = [
    '#4F46E5,#7C3AED',
    '#0369A1,#0891B2',
    '#059669,#10B981',
    '#C2410C,#F97316',
    '#B45309,#F59E0B',
    '#1D4ED8,#3B82F6',
];
@endphp

<div class="sf-page">

@if(session('success'))
<div class="sf-alert sf-alert-ok">
    <i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="sf-alert sf-alert-err">
    <i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#EF4444;"></i>
    {{ session('error') }}
</div>
@endif

{{-- Header --}}
<div class="sf-header">
    <div class="sf-header-left">
        <div class="sf-header-icon"><i data-feather="users"></i></div>
        <div class="sf-header-text">
            <h1>Staff Management</h1>
            <p>Manage team members, roles &amp; access</p>
        </div>
    </div>
    <div class="sf-header-right">
        @can('staff-create')
        <a href="{{ route('staffs.create') }}" class="sf-add-btn">
            <i data-feather="user-plus"></i> Add Staff
        </a>
        @endcan
    </div>
</div>

{{-- Stats --}}
<div class="sf-stats">
    <div class="sf-stat">
        <div class="sf-stat-icon" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);"><i data-feather="users"></i></div>
        <div><div class="sf-stat-val">{{ $list->total() }}</div><div class="sf-stat-lbl">Total Staff</div></div>
    </div>
    <div class="sf-stat">
        <div class="sf-stat-icon" style="background:linear-gradient(135deg,#059669,#10B981);"><i data-feather="user-check"></i></div>
        <div><div class="sf-stat-val">{{ $list->count() }}</div><div class="sf-stat-lbl">This Page</div></div>
    </div>
    <div class="sf-stat">
        <div class="sf-stat-icon" style="background:linear-gradient(135deg,#0369A1,#0891B2);"><i data-feather="shield"></i></div>
        <div><div class="sf-stat-val">{{ $list->total() }}</div><div class="sf-stat-lbl">Active Members</div></div>
    </div>
</div>

{{-- ID Card Grid --}}
@if($list->isEmpty())
<div class="sf-empty">
    <i data-feather="users"></i>
    <p style="font-weight:700;color:#374151;margin:0 0 5px;font-size:1rem;">No staff members yet</p>
    <p style="font-size:.84rem;margin:0 0 16px;">Add your first team member to get started.</p>
    @can('staff-create')
    <a href="{{ route('staffs.create') }}" class="sf-add-btn" style="margin:0 auto;display:inline-flex;">
        <i data-feather="user-plus"></i> Add Staff
    </a>
    @endcan
</div>
@else
<div class="sf-grid">
    @foreach($list as $idx => $staff)
    @php
        $initial   = strtoupper(substr($staff->name, 0, 1));
        $grad      = $stripGradients[$idx % count($stripGradients)];
        $staffId   = str_pad($staff->id, 4, '0', STR_PAD_LEFT);
        $roles     = $staff->getRoleNames();
        $firstRole = strtolower($roles->first() ?? 'staff');
        $rc        = $roleColors[$firstRole] ?? $roleColors['staff'];
    @endphp
    <div class="sf-card">
        {{-- Coloured top strip --}}
        <div class="sf-card-strip" style="background:linear-gradient(135deg,{{ $grad }});"></div>

        {{-- Avatar --}}
        <div class="sf-card-av-wrap">
            <div class="sf-card-av" style="background:linear-gradient(135deg,{{ $grad }});">
                @if($staff->avatar_url)
                    <img src="{{ $staff->avatar_url }}" alt="{{ $staff->name }}">
                @else
                    {{ $initial }}
                @endif
            </div>
        </div>

        {{-- Body --}}
        <div class="sf-card-body">
            <div class="sf-card-name" title="{{ $staff->name }}">{{ $staff->name }}</div>
            <div class="sf-card-email" title="{{ $staff->email }}">{{ $staff->email }}</div>

            {{-- Role badges --}}
            <div class="sf-card-roles">
                @foreach($roles as $role)
                @php
                    $r  = strtolower($role);
                    $rc2= $roleColors[$r] ?? $roleColors['staff'];
                @endphp
                <span class="sf-role-badge"
                    style="background:{{ $rc2['light'] }};color:{{ $rc2['text'] }};border:1px solid {{ $rc2['text'] }}33;">
                    {{ $role }}
                </span>
                @endforeach
            </div>

            <div class="sf-card-divider"></div>
            <div class="sf-card-id">ID #{{ $staffId }}</div>

            {{-- Actions --}}
            <div class="sf-card-actions">
                @can('staff-edit')
                <a href="{{ route('staffs.edit', $staff->id) }}" class="sf-card-btn sf-card-btn-edit">
                    <i data-feather="edit-2"></i> Edit
                </a>
                @endcan
                @can('staff-delete')
                <form action="{{ route('staffs.destroy', $staff->id) }}" method="POST" id="del_{{ $staff->id }}" style="display:none;">
                    @csrf @method('DELETE')
                </form>
                <button type="button" class="sf-card-btn sf-card-btn-del"
                    onclick="delStaff('{{ $staff->id }}','{{ addslashes($staff->name) }}')">
                    <i data-feather="trash-2"></i> Del
                </button>
                @endcan
            </div>

            <div class="sf-card-watermark">Visit Kashi · Staff</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($list->hasPages())
<div class="sf-pag">
    <div class="sf-pag-info">
        Showing {{ $list->firstItem() }}–{{ $list->lastItem() }} of {{ $list->total() }} staff
    </div>
    {{ $list->links() }}
</div>
@endif
@endif

</div>

<script>
function delStaff(id, name){
    Swal.fire({
        title: 'Delete Staff?',
        html: 'Remove <strong>' + name + '</strong> from the team? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6C757D',
        confirmButtonText: 'Yes, remove!'
    }).then(function(r){ if(r.isConfirmed) document.getElementById('del_' + id).submit(); });
}
document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });
</script>
@endsection
