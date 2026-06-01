@extends('admin.layouts.app')
@section('content')
<style>
/* ─── Page shell ─────────────────────────────────────────────────── */
.re-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ─── Hero header ────────────────────────────────────────────────── */
.re-hero{background:linear-gradient(135deg,#0f766e,#0d9488);border-radius:16px;padding:22px 28px;margin-bottom:24px;margin-top:50px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(13,148,136,.28);}
.re-hero::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.re-hero::after{content:'🔐';position:absolute;bottom:-10px;right:18px;font-size:88px;opacity:.06;line-height:1;pointer-events:none;}
.re-hero-left{position:relative;z-index:1;display:flex;align-items:center;gap:14px;}
.re-hero-icon{width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.re-hero-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.re-hero-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.re-hero-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.re-hero-badge{position:relative;z-index:1;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:30px;padding:6px 14px;font-size:.78rem;font-weight:700;}
.re-hero-badge i[data-feather]{width:13px;height:13px;stroke:#fff;}

/* ─── Layout ─────────────────────────────────────────────────────── */
.re-layout{display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start;}
@media(max-width:960px){.re-layout{grid-template-columns:1fr;}}

/* ─── Left panel ─────────────────────────────────────────────────── */
.re-panel{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 6px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.re-panel-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:9px;background:linear-gradient(135deg,#F5F3FF,#EDE9FE);}
.re-panel-head-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#4F46E5,#7C3AED);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.re-panel-head-icon i[data-feather]{width:14px;height:14px;stroke:#fff;}
.re-panel-head-title{font-size:.88rem;font-weight:800;color:#0F172A;}
.re-panel-body{padding:18px;}
.re-label{font-size:.69rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;display:block;}
.re-input{width:100%;border:1.5px solid #E2E8F0;border-radius:10px;padding:10px 13px;font-size:.88rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s,box-shadow .15s;font-family:inherit;}
.re-input:focus{border-color:#7C3AED;box-shadow:0 0 0 3px rgba(124,58,237,.1);}
.re-err{font-size:.72rem;color:#DC2626;margin-top:4px;}

/* Stats in left panel */
.re-stats{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:16px;}
.re-stat{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:10px 12px;text-align:center;}
.re-stat-val{font-size:1.2rem;font-weight:900;color:#4F46E5;line-height:1;}
.re-stat-lbl{font-size:.62rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-top:2px;}

/* Action buttons (left panel) */
.re-btn-col{display:flex;flex-direction:column;gap:8px;margin-top:16px;}
.re-save-btn{width:100%;height:42px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:10px;font-size:.87rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all .2s;}
.re-save-btn i[data-feather]{width:15px;height:15px;stroke:#fff;}
.re-save-btn:hover{opacity:.9;transform:translateY(-1px);box-shadow:0 4px 14px rgba(79,70,229,.35);}
.re-back-btn{width:100%;height:40px;background:#F1F5F9;color:#64748B;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;text-decoration:none;transition:all .15s;}
.re-back-btn i[data-feather]{width:14px;height:14px;}
.re-back-btn:hover{background:#E2E8F0;color:#374151;text-decoration:none;}

/* ─── Permission panel (right) ───────────────────────────────────── */
.re-perm-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 6px rgba(0,0,0,.05);overflow:hidden;}
.re-perm-card-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;background:linear-gradient(135deg,#F5F3FF,#EDE9FE);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
.re-perm-card-title{display:flex;align-items:center;gap:8px;}
.re-perm-card-title-icon{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#4F46E5,#7C3AED);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.re-perm-card-title-icon i[data-feather]{width:15px;height:15px;stroke:#fff;}
.re-perm-card-title-text{font-size:.9rem;font-weight:800;color:#0F172A;}
.re-perm-card-title-sub{font-size:.71rem;color:#64748B;margin-top:1px;}

/* Toolbar */
.re-toolbar{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.re-search-wrap{position:relative;}
.re-search-wrap i[data-feather]{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;stroke:#94A3B8;}
.re-search{height:34px;border:1.5px solid #E2E8F0;border-radius:9px;padding:0 12px 0 32px;font-size:.8rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;width:200px;}
.re-search:focus{border-color:#7C3AED;background:#fff;}
.re-tb-btn{height:34px;padding:0 12px;border-radius:9px;font-size:.77rem;font-weight:700;border:1.5px solid;cursor:pointer;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;transition:all .15s;}
.re-tb-btn i[data-feather]{width:12px;height:12px;}
.re-tb-all {background:#EDE9FE;color:#6D28D9;border-color:#DDD6FE;}.re-tb-all:hover{background:#DDD6FE;}
.re-tb-none{background:#F1F5F9;color:#64748B;border-color:#E2E8F0;}.re-tb-none:hover{background:#E2E8F0;color:#374151;}
.re-perm-counter{display:inline-flex;align-items:center;gap:5px;background:#4F46E5;color:#fff;border-radius:20px;padding:3px 11px;font-size:.73rem;font-weight:700;}

/* Permission body & grid */
.re-perm-body{padding:16px;}
.re-perm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:12px;}

/* Group cards */
.re-grp{background:#FAFAFA;border-radius:12px;border:1px solid #E2E8F0;overflow:hidden;transition:border-color .2s,box-shadow .2s;}
.re-grp:hover{border-color:#C4B5FD;box-shadow:0 2px 12px rgba(124,58,237,.09);}
.re-grp.re-hidden{display:none;}
.re-grp-head{display:flex;align-items:center;justify-content:space-between;padding:9px 13px;background:linear-gradient(135deg,#F5F3FF,#EDE9FE);border-bottom:1px solid #EDE9FE;}
.re-grp-name{font-size:.78rem;font-weight:800;color:#4F46E5;display:flex;align-items:center;gap:6px;}
.re-grp-icon{width:22px;height:22px;border-radius:6px;background:linear-gradient(135deg,#6366F1,#8B5CF6);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.re-grp-icon i[data-feather]{width:11px;height:11px;stroke:#fff;}
.re-grp-right{display:flex;align-items:center;gap:6px;}
.re-grp-cnt{font-size:.63rem;font-weight:700;color:#7C3AED;background:#EDE9FE;border-radius:20px;padding:1px 7px;}
/* Group master toggle */
.re-master{position:relative;width:34px;height:18px;flex-shrink:0;}
.re-master input{opacity:0;width:0;height:0;position:absolute;}
.re-master-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:18px;cursor:pointer;transition:background .2s;}
.re-master-slider::after{content:'';position:absolute;top:2px;left:2px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 2px rgba(0,0,0,.18);}
.re-master input:checked+.re-master-slider{background:#10B981;}
.re-master input:checked+.re-master-slider::after{transform:translateX(16px);}
/* Items */
.re-grp-items{padding:5px 0;}
.re-item{display:flex;align-items:center;justify-content:space-between;padding:7px 13px;border-bottom:1px solid #F3F4F6;transition:background .12s;}
.re-item:last-child{border-bottom:none;}
.re-item:hover{background:#F5F3FF;}
.re-item-lbl{font-size:.78rem;font-weight:500;color:#374151;cursor:pointer;flex:1;margin:0;display:flex;align-items:center;gap:6px;}
.re-item-lbl::before{content:'·';color:#C4B5FD;font-weight:900;}
/* Item toggle */
.re-tog{position:relative;width:34px;height:18px;flex-shrink:0;}
.re-tog input{opacity:0;width:0;height:0;position:absolute;}
.re-tog-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:18px;cursor:pointer;transition:background .2s;}
.re-tog-slider::after{content:'';position:absolute;top:2px;left:2px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 2px rgba(0,0,0,.18);}
.re-tog input:checked+.re-tog-slider{background:#7C3AED;}
.re-tog input:checked+.re-tog-slider::after{transform:translateX(16px);}

/* ─── Module icon map ────────────────────────────────────────────── */
/* colours per module type for the group icon */
.re-grp[data-mod="booking"]   .re-grp-icon{background:linear-gradient(135deg,#0369A1,#0EA5E9);}
.re-grp[data-mod="staff"]     .re-grp-icon{background:linear-gradient(135deg,#059669,#10B981);}
.re-grp[data-mod="product"]   .re-grp-icon{background:linear-gradient(135deg,#C2410C,#F97316);}
.re-grp[data-mod="category"]  .re-grp-icon{background:linear-gradient(135deg,#0F766E,#14B8A6);}
.re-grp[data-mod="role"]      .re-grp-icon{background:linear-gradient(135deg,#4F46E5,#7C3AED);}
.re-grp[data-mod="lead"]      .re-grp-icon{background:linear-gradient(135deg,#B45309,#F59E0B);}
.re-grp[data-mod="report"]    .re-grp-icon{background:linear-gradient(135deg,#BE185D,#EC4899);}
.re-grp[data-mod="setting"]   .re-grp-icon{background:linear-gradient(135deg,#374151,#6B7280);}

/* Alert flash */
.re-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:18px;font-size:.83rem;font-weight:600;}
.re-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.re-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}

/* Sticky save bar (mobile) */
@media(max-width:960px){
  .re-sticky-bar{position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1px solid #E2E8F0;padding:12px 16px;display:flex;gap:10px;z-index:50;box-shadow:0 -4px 16px rgba(0,0,0,.08);}
  .re-sticky-bar .re-save-btn,.re-sticky-bar .re-back-btn{width:auto;flex:1;}
  .re-panel{position:static;}
  .re-page{padding-bottom:80px;}
}
@media(min-width:961px){.re-sticky-bar{display:none;}}

@media(max-width:640px){.re-page{padding:12px;}.re-perm-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:420px){.re-perm-grid{grid-template-columns:1fr;}}
</style>

<div class="re-page">

@if(session('success'))
<div class="re-alert re-alert-ok">
    <i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="re-alert re-alert-err">
    <i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#EF4444;"></i>
    {{ session('error') }}
</div>
@endif

{{-- Hero --}}
<div class="re-hero">
    <div class="re-hero-left">
        <div class="re-hero-icon"><i data-feather="shield"></i></div>
        <div class="re-hero-text">
            <h1>Edit Role</h1>
            <p>Update permissions for <strong>{{ $role->name }}</strong></p>
        </div>
    </div>
    <div class="re-hero-badge">
        <i data-feather="key"></i>
        <span id="heroBadge">{{ count($rolePermissions) }} permissions</span>
    </div>
</div>

<form action="{{ route('roles.update', $role->id) }}" method="POST" id="roleForm">
@csrf
@method('PATCH')

<div class="re-layout">

    {{-- ── Left panel ── --}}
    <div>
        <div class="re-panel">
            <div class="re-panel-head">
                <div class="re-panel-head-icon"><i data-feather="tag"></i></div>
                <div class="re-panel-head-title">Role Details</div>
            </div>
            <div class="re-panel-body">
                <label class="re-label">Role Name <span style="color:#EF4444;">*</span></label>
                <input type="text" name="name" class="re-input" required
                    value="{{ old('name', $role->name) }}"
                    placeholder="e.g. Sales Manager">
                @error('name')<div class="re-err">{{ $message }}</div>@enderror

                {{-- Live stats --}}
                <div class="re-stats">
                    <div class="re-stat">
                        <div class="re-stat-val" id="statSelected">{{ count($rolePermissions) }}</div>
                        <div class="re-stat-lbl">Selected</div>
                    </div>
                    <div class="re-stat">
                        <div class="re-stat-val" id="statTotal">—</div>
                        <div class="re-stat-lbl">Total Perms</div>
                    </div>
                </div>

                @error('permission')
                <div class="re-err" style="margin-top:10px;">{{ $message }}</div>
                @enderror

                {{-- Actions --}}
                <div class="re-btn-col">
                    <button type="submit" class="re-save-btn">
                        <i data-feather="save"></i> Save Changes
                    </button>
                    <a href="{{ route('roles.index') }}" class="re-back-btn">
                        <i data-feather="arrow-left"></i> Back to Roles
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right: Permission grid ── --}}
    <div>
        <div class="re-perm-card">
            <div class="re-perm-card-head">
                <div class="re-perm-card-title">
                    <div class="re-perm-card-title-icon"><i data-feather="key"></i></div>
                    <div>
                        <div class="re-perm-card-title-text">Permissions</div>
                        <div class="re-perm-card-title-sub" id="permSubtitle">Loading…</div>
                    </div>
                </div>
                <div class="re-toolbar">
                    <div class="re-search-wrap">
                        <i data-feather="search"></i>
                        <input type="text" class="re-search" id="reSearch" placeholder="Search module…" oninput="reSearchFn()">
                    </div>
                    <button type="button" class="re-tb-btn re-tb-all"  onclick="reSelectAll(true)">
                        <i data-feather="check-square"></i> All
                    </button>
                    <button type="button" class="re-tb-btn re-tb-none" onclick="reSelectAll(false)">
                        <i data-feather="square"></i> None
                    </button>
                    <span class="re-perm-counter" id="permCounter">0 / 0</span>
                </div>
            </div>
            <div class="re-perm-body">
                <div class="re-perm-grid" id="rePermGrid">
                @foreach($permissionParent as $parent)
                @php
                    $perms    = Spatie\Permission\Models\Permission::where('parent_name', $parent->parent_name)->get();
                    $gKey     = preg_replace('/[^a-z0-9]/i','_', $parent->parent_name);
                    $gLabel   = ucwords(str_replace(['-','_'],' ', $parent->parent_name));
                    // determine icon per module name
                    $mn = strtolower($parent->parent_name);
                    if (str_contains($mn,'booking'))       { $featherIcon='calendar';    $modKey='booking';  }
                    elseif (str_contains($mn,'staff'))     { $featherIcon='users';       $modKey='staff';    }
                    elseif (str_contains($mn,'product'))   { $featherIcon='package';     $modKey='product';  }
                    elseif (str_contains($mn,'category'))  { $featherIcon='tag';         $modKey='category'; }
                    elseif (str_contains($mn,'role'))      { $featherIcon='shield';      $modKey='role';     }
                    elseif (str_contains($mn,'lead'))      { $featherIcon='briefcase';   $modKey='lead';     }
                    elseif (str_contains($mn,'report'))    { $featherIcon='bar-chart-2'; $modKey='report';   }
                    elseif (str_contains($mn,'setting'))   { $featherIcon='settings';    $modKey='setting';  }
                    elseif (str_contains($mn,'boat'))      { $featherIcon='anchor';      $modKey='default';  }
                    elseif (str_contains($mn,'payment'))   { $featherIcon='credit-card'; $modKey='default';  }
                    elseif (str_contains($mn,'target'))    { $featherIcon='target';      $modKey='default';  }
                    elseif (str_contains($mn,'profit'))    { $featherIcon='trending-up'; $modKey='default';  }
                    else                                   { $featherIcon='layers';      $modKey='default';  }
                @endphp
                <div class="re-grp" data-group="{{ strtolower($gLabel) }}" data-mod="{{ $modKey }}">
                    <div class="re-grp-head">
                        <div class="re-grp-name">
                            <div class="re-grp-icon"><i data-feather="{{ $featherIcon }}"></i></div>
                            {{ $gLabel }}
                        </div>
                        <div class="re-grp-right">
                            <span class="re-grp-cnt grp-cnt-{{ $gKey }}">0</span>
                            <label style="display:flex;align-items:center;gap:4px;cursor:pointer;" title="Toggle all in {{ $gLabel }}">
                                <span style="font-size:.62rem;font-weight:600;color:#94A3B8;">All</span>
                                <label class="re-master">
                                    <input type="checkbox" class="grp-master-{{ $gKey }}" onchange="reGroupToggle('{{ $gKey }}', this.checked)">
                                    <span class="re-master-slider"></span>
                                </label>
                            </label>
                        </div>
                    </div>
                    <div class="re-grp-items">
                        @foreach($perms as $perm)
                        @php $pLabel = ucwords(str_replace(['-','_'],' ', explode('-', $perm->name)[1] ?? $perm->name)); @endphp
                        <div class="re-item">
                            <label class="re-item-lbl" for="p_{{ $perm->id }}">{{ $pLabel }}</label>
                            <label class="re-tog">
                                <input type="checkbox" name="permission[]"
                                    id="p_{{ $perm->id }}"
                                    class="perm-cb grp-cb-{{ $gKey }}"
                                    value="{{ $perm->id }}"
                                    {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}
                                    onchange="reUpdate()">
                                <span class="re-tog-slider"></span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

</div>{{-- /.re-layout --}}
</form>

{{-- Mobile sticky save bar --}}
<div class="re-sticky-bar">
    <a href="{{ route('roles.index') }}" class="re-back-btn">
        <i data-feather="arrow-left"></i> Back
    </a>
    <button type="submit" form="roleForm" class="re-save-btn">
        <i data-feather="save"></i> Save Changes
    </button>
</div>

</div>{{-- /.re-page --}}

<script>
document.addEventListener('DOMContentLoaded', function(){
    feather.replace();
    reUpdate();
});

function reUpdate() {
    var all     = document.querySelectorAll('.perm-cb');
    var checked = document.querySelectorAll('.perm-cb:checked');
    var cnt     = checked.length, tot = all.length;

    // Global counter
    document.getElementById('permCounter').textContent  = cnt + ' / ' + tot;
    document.getElementById('permSubtitle').textContent = cnt + ' of ' + tot + ' permissions selected';
    document.getElementById('statSelected').textContent = cnt;
    document.getElementById('statTotal').textContent    = tot;
    document.getElementById('heroBadge').textContent    = cnt + ' permissions';

    // Per-group counts & master toggle state
    document.querySelectorAll('.re-grp').forEach(function(grp){
        var cbs  = grp.querySelectorAll('.perm-cb');
        var chk  = grp.querySelectorAll('.perm-cb:checked');
        // Find group key from first cb class
        cbs.forEach(function(cb){
            cb.classList.forEach(function(c){
                if(c.startsWith('grp-cb-')){
                    var key = c.replace('grp-cb-','');
                    var badge  = document.querySelector('.grp-cnt-'+key);
                    var master = document.querySelector('.grp-master-'+key);
                    if(badge)  badge.textContent = chk.length + '/' + cbs.length;
                    if(master) master.checked    = cbs.length > 0 && chk.length === cbs.length;
                }
            });
        });
    });
}

function reGroupToggle(key, checked){
    document.querySelectorAll('.grp-cb-'+key).forEach(function(cb){ cb.checked = checked; });
    reUpdate();
}

function reSelectAll(checked){
    document.querySelectorAll('.perm-cb').forEach(function(cb){ cb.checked = checked; });
    document.querySelectorAll('[class*="grp-master-"]').forEach(function(cb){ cb.checked = checked; });
    reUpdate();
}

function reSearchFn(){
    var q = document.getElementById('reSearch').value.toLowerCase().trim();
    document.querySelectorAll('.re-grp').forEach(function(grp){
        var name  = (grp.dataset.group||'').toLowerCase();
        var items = grp.querySelectorAll('.re-item');
        var any   = false;
        items.forEach(function(item){
            var lbl  = (item.querySelector('.re-item-lbl')||{textContent:''}).textContent.toLowerCase();
            var show = !q || lbl.includes(q) || name.includes(q);
            item.style.display = show ? '' : 'none';
            if(show) any = true;
        });
        grp.classList.toggle('re-hidden', !any && q !== '');
    });
}
</script>
@endsection
