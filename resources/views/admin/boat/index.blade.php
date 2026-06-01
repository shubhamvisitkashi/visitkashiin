@extends('admin.layouts.app')
@section('content')
<style>
.bo-page{padding:24px;background:#F1F5F9;min-height:100vh;}
/* Header */
.bo-header{background:linear-gradient(135deg,#164E63,#0369A1,#0891B2);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(8,145,178,.28);}
.bo-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.bo-header::after{content:'⛵';position:absolute;bottom:-10px;right:18px;font-size:90px;opacity:.07;line-height:1;pointer-events:none;}
.bo-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:13px;}
.bo-header-icon{width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bo-header-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.bo-header-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.bo-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.bo-header-right{position:relative;z-index:1;display:flex;align-items:center;gap:8px;}
.bo-add-btn{display:inline-flex;align-items:center;gap:6px;background:#fff;color:#0369A1;border:none;border-radius:10px;padding:9px 16px;font-size:.82rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:all .2s;white-space:nowrap;}
.bo-add-btn:hover{background:#ECFEFF;color:#0369A1;text-decoration:none;transform:translateY(-1px);}
.bo-add-btn i[data-feather]{width:14px;height:14px;}
/* Stats */
.bo-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;}
.bo-stat{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 16px;display:flex;align-items:center;gap:12px;}
.bo-stat-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bo-stat-icon i[data-feather]{width:18px;height:18px;stroke:#fff;}
.bo-stat-val{font-size:1.35rem;font-weight:900;color:#0F172A;line-height:1;}
.bo-stat-lbl{font-size:.67rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-top:2px;}
/* Filter */
.bo-filter{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:13px 16px;margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
.bo-filter select{border:1.5px solid #E2E8F0;border-radius:9px;padding:7px 11px;font-size:.82rem;color:#0F172A;background:#FAFBFF;height:36px;outline:none;transition:border-color .15s;flex:1;min-width:160px;}
.bo-filter select:focus{border-color:#0891B2;}
.bo-filter-btn{height:36px;padding:0 16px;border-radius:9px;border:none;font-size:.81rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;flex-shrink:0;text-decoration:none;transition:all .15s;}
.bo-filter-btn i[data-feather]{width:13px;height:13px;}
.bo-filter-btn.primary{background:#0891B2;color:#fff;}.bo-filter-btn.primary:hover{background:#0369A1;}
.bo-filter-btn.ghost{background:#F1F5F9;color:#64748B;}.bo-filter-btn.ghost:hover{background:#E2E8F0;color:#374151;text-decoration:none;}
/* Card */
.bo-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.bo-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#ECFEFF;}
.bo-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.bo-card-title i[data-feather]{width:15px;height:15px;stroke:#0891B2;}
/* Table */
.bo-table{width:100%;border-collapse:collapse;}
.bo-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.bo-table td{padding:12px 14px;border-bottom:1px solid #F8FAFC;font-size:.83rem;color:#0F172A;vertical-align:middle;}
.bo-table tbody tr:last-child td{border-bottom:none;}
.bo-table tbody tr:hover{background:#ECFEFF;}
/* Badges */
.bo-badge{display:inline-flex;align-items:center;padding:2px 10px;border-radius:20px;font-size:.7rem;font-weight:700;}
.bo-badge-type{background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;}
.bo-badge-regular{background:#F0FDF4;color:#16A34A;border:1px solid #BBF7D0;}
.bo-badge-festival{background:#FFF7ED;color:#C2410C;border:1px solid #FED7AA;}
.bo-badge-on {background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0;}
.bo-badge-off{background:#F1F5F9;color:#64748B;border:1px solid #E2E8F0;}
/* Price */
.bo-price{font-weight:800;color:#0369A1;font-size:.88rem;}
.bo-price-original{color:#94A3B8;font-size:.75rem;font-weight:500;text-decoration:line-through;display:block;line-height:1.2;}
/* Capacity */
.bo-cap{display:flex;align-items:center;gap:5px;}
.bo-cap-fleet{font-weight:700;font-size:.84rem;color:#0F172A;}
.bo-cap-seat{font-size:.72rem;color:#64748B;background:#F1F5F9;padding:2px 7px;border-radius:6px;font-weight:600;}
/* Toggle */
.bo-switch{position:relative;display:inline-block;width:38px;height:21px;}
.bo-switch input{opacity:0;width:0;height:0;position:absolute;}
.bo-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:21px;cursor:pointer;transition:background .2s;}
.bo-switch-slider::after{content:'';position:absolute;top:3.5px;left:3.5px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.bo-switch input:checked+.bo-switch-slider{background:#0891B2;}
.bo-switch input:checked+.bo-switch-slider::after{transform:translateX(17px);}
/* Actions */
.bo-actions{display:flex;gap:5px;justify-content:center;}
.bo-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.bo-act i[data-feather]{width:13px;height:13px;}
.bo-act-edit{background:#EEF2FF;color:#4F46E5;}.bo-act-edit:hover{background:#DDD6FE;}
.bo-act-del {background:#FEF2F2;color:#DC2626;}.bo-act-del:hover{background:#FECACA;}
/* Empty */
.bo-empty{text-align:center;padding:52px;color:#94A3B8;}
.bo-empty i[data-feather]{width:52px;height:52px;opacity:.2;display:block;margin:0 auto 12px;stroke:#94A3B8;}
/* Alerts */
.bo-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.bo-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.bo-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
@media(max-width:900px){.bo-stats{grid-template-columns:repeat(2,1fr);}}
@media(max-width:640px){.bo-page{padding:12px;}.bo-stats{grid-template-columns:1fr 1fr;}}
</style>

<div class="bo-page">

@if(session('success'))
<div class="bo-alert bo-alert-ok">
    <i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bo-alert bo-alert-err">
    <i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#EF4444;"></i>
    {{ session('error') }}
</div>
@endif

{{-- Header --}}
<div class="bo-header">
    <div class="bo-header-left">
        <div class="bo-header-icon"><i data-feather="anchor"></i></div>
        <div class="bo-header-text">
            <h1>Boat Management</h1>
            <p>Manage boats, pricing &amp; availability for Ganga rides</p>
        </div>
    </div>
    <div class="bo-header-right">
        <a href="{{ route('boat.create') }}" class="bo-add-btn">
            <i data-feather="plus"></i> Add Boat
        </a>
    </div>
</div>

{{-- Stats --}}
@php
$totalBoats   = $boats->count();
$activeBoats  = $boats->where('is_active','1')->count();
$regularBoats = $boats->where('event_type','Regular')->count();
$festivalBoats= $boats->where('event_type','Festival')->count();
@endphp
<div class="bo-stats">
    <div class="bo-stat">
        <div class="bo-stat-icon" style="background:linear-gradient(135deg,#0369A1,#0891B2);"><i data-feather="anchor"></i></div>
        <div><div class="bo-stat-val">{{ $totalBoats }}</div><div class="bo-stat-lbl">Total Boats</div></div>
    </div>
    <div class="bo-stat">
        <div class="bo-stat-icon" style="background:linear-gradient(135deg,#059669,#10B981);"><i data-feather="check-circle"></i></div>
        <div><div class="bo-stat-val">{{ $activeBoats }}</div><div class="bo-stat-lbl">Active</div></div>
    </div>
    <div class="bo-stat">
        <div class="bo-stat-icon" style="background:linear-gradient(135deg,#1D4ED8,#3B82F6);"><i data-feather="sun"></i></div>
        <div><div class="bo-stat-val">{{ $regularBoats }}</div><div class="bo-stat-lbl">Regular</div></div>
    </div>
    <div class="bo-stat">
        <div class="bo-stat-icon" style="background:linear-gradient(135deg,#C2410C,#F97316);"><i data-feather="star"></i></div>
        <div><div class="bo-stat-val">{{ $festivalBoats }}</div><div class="bo-stat-lbl">Festival</div></div>
    </div>
</div>

{{-- Filters --}}
<form action="{{ route('boat.index') }}" method="GET" class="bo-filter">
    <select name="search_boat_type">
        <option value="">All Boat Types</option>
        @foreach($boat_types as $bt)
        <option value="{{ $bt->id }}" {{ $search_boat_type == $bt->id ? 'selected' : '' }}>{{ $bt->name }}</option>
        @endforeach
    </select>
    <select name="search_event_type">
        <option value="">All Event Types</option>
        <option value="Regular"  {{ $search_event_type == 'Regular'  ? 'selected' : '' }}>Regular</option>
        <option value="Festival" {{ $search_event_type == 'Festival' ? 'selected' : '' }}>Festival</option>
    </select>
    <button type="submit" class="bo-filter-btn primary"><i data-feather="filter"></i> Filter</button>
    <a href="{{ route('boat.index') }}" class="bo-filter-btn ghost"><i data-feather="x"></i> Reset</a>
</form>

{{-- Table card --}}
<div class="bo-card">
    <div class="bo-card-head">
        <div class="bo-card-title">
            <i data-feather="anchor"></i> All Boats
        </div>
        <span style="font-size:.73rem;color:#64748B;font-weight:600;">{{ $totalBoats }} boats</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="bo-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Boat Type</th>
                    <th>Event</th>
                    <th class="text-center">Fleet &amp; Seats</th>
                    <th class="text-end">Price</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($boats as $i => $boat)
                <tr>
                    <td style="color:#94A3B8;font-size:.78rem;font-weight:600;">{{ $i + 1 }}</td>
                    <td>
                        <span class="bo-badge bo-badge-type">{{ $boat->boatType?->name ?? '—' }}</span>
                    </td>
                    <td>
                        @if($boat->event_type == 'Festival')
                        <span class="bo-badge bo-badge-festival">🎊 Festival</span>
                        @else
                        <span class="bo-badge bo-badge-regular">☀️ Regular</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="bo-cap">
                            <div style="margin:0 auto;text-align:center;">
                                <div class="bo-cap-fleet">{{ $boat->total_available_boat }} boats</div>
                                <span class="bo-cap-seat">{{ $boat->no_of_seat }} seats each</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-end">
                        @if($boat->price != $boat->discounted_price)
                        <span class="bo-price-original">₹{{ number_format($boat->price) }}</span>
                        @endif
                        <span class="bo-price">₹{{ number_format($boat->discounted_price) }}</span>
                    </td>
                    <td class="text-center">
                        <label class="bo-switch">
                            <input type="checkbox" class="status_update" value="{{ $boat->id }}" {{ $boat->is_active == '1' ? 'checked' : '' }}>
                            <span class="bo-switch-slider"></span>
                        </label>
                    </td>
                    <td>
                        <div class="bo-actions">
                            <a href="{{ route('boat.edit', $boat->id) }}" class="bo-act bo-act-edit" title="Edit">
                                <i data-feather="edit-2"></i>
                            </a>
                            <form action="{{ route('boat.destroy', $boat->id) }}" method="POST" id="del_{{ $boat->id }}" style="display:contents;">
                                @csrf @method('DELETE')
                                <button type="button" class="bo-act bo-act-del" title="Delete"
                                    onclick="delBoat('{{ $boat->id }}','{{ addslashes($boat->boatType?->name ?? 'Boat') }}')">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="bo-empty">
                            <i data-feather="anchor"></i>
                            <p style="font-weight:700;color:#374151;margin:0 0 5px;">No boats found</p>
                            <p style="font-size:.82rem;margin:0 0 14px;">Try adjusting your filters or add a new boat</p>
                            <a href="{{ route('boat.create') }}" class="bo-add-btn" style="display:inline-flex;">
                                <i data-feather="plus"></i> Add First Boat
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

<script>
// Status toggle
$('.status_update').change(function(){
    var id = $(this), val = $(this).val();
    id.prop('disabled', true);
    $.get('{{ route('boat.show','') }}/' + val, function(data){
        id.prop('disabled', false);
        Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true})
            .fire({icon:data.res, title:data.message});
    }).fail(function(){
        id.prop('disabled', false);
        id.prop('checked', !id.prop('checked'));
        Swal.fire({icon:'error',title:'Error',text:'Failed to update status.',timer:2500,showConfirmButton:false});
    });
});

function delBoat(id, name){
    Swal.fire({
        title:'Delete Boat?',
        html:'Delete <strong>' + name + '</strong>? This cannot be undone.',
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#DC2626',
        cancelButtonColor:'#6C757D',
        confirmButtonText:'Yes, delete it!'
    }).then(r => { if(r.isConfirmed) document.getElementById('del_' + id).submit(); });
}

document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });
</script>
@endsection
