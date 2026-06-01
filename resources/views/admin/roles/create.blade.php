@extends('admin.layouts.app')
@section('content')
<style>
:root{--rp:#7C3AED;--rp-lt:#F5F3FF;--rg:#10B981;--rr:#EF4444;--rt:#0F172A;--rm:#64748B;--rb:#E2E8F0;}
.rf-page{padding:24px;max-width:1100px;}
.rf-hero{background:linear-gradient(135deg,#6D28D9,#8B5CF6);border-radius:16px;padding:22px 28px;margin-bottom:24px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden;}
.rf-hero::before{content:'';position:absolute;top:-30px;right:-30px;width:150px;height:150px;background:rgba(255,255,255,.06);border-radius:50%;}
.rf-hero-icon{width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;}
.rf-hero-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.rf-hero-text{position:relative;z-index:1;}
.rf-hero-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.rf-hero-text p{color:rgba(255,255,255,.7);font-size:.8rem;margin:0;}
/* Name card */
.rf-card{background:#fff;border-radius:14px;border:1px solid var(--rb);box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;margin-bottom:20px;}
.rf-card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--rb);background:linear-gradient(135deg,var(--rp-lt),#faf5ff);}
.rf-card-head-icon{width:32px;height:32px;border-radius:8px;background:var(--rp);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.rf-card-head-icon i[data-feather]{width:15px;height:15px;stroke:#fff;}
.rf-card-head-title{font-size:.9rem;font-weight:700;color:var(--rt);margin:0;}
.rf-card-body{padding:20px 22px;}
.rf-input{width:100%;height:48px;background:#F8FAFC;border:1.5px solid var(--rb);border-radius:11px;padding:0 16px;font-size:.9rem;color:var(--rt);outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit;max-width:480px;}
.rf-input:focus{border-color:var(--rp);background:#fff;box-shadow:0 0 0 3px rgba(124,58,237,.1);}
.rf-label{display:block;font-size:.74rem;font-weight:700;color:var(--rm);margin-bottom:7px;text-transform:uppercase;letter-spacing:.05em;}
.rf-label span{color:var(--rr);}
/* Toolbar */
.rf-perm-toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px;}
.rf-perm-title{font-size:.95rem;font-weight:700;color:var(--rt);}
.rf-perm-meta{font-size:.78rem;color:var(--rm);}
.rf-toolbar-btns{display:flex;gap:8px;}
.rf-sel-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:.78rem;font-weight:700;border:1.5px solid;cursor:pointer;transition:all .18s;}
.rf-sel-all{background:var(--rp-lt);color:var(--rp);border-color:#DDD6FE;}.rf-sel-all:hover{background:#ede9fe;}
.rf-sel-none{background:#F9FAFB;color:var(--rm);border-color:var(--rb);}.rf-sel-none:hover{background:#F1F5F9;color:var(--rt);}
/* Search */
.rf-search{position:relative;max-width:260px;flex:1;}
.rf-search input{width:100%;height:36px;background:#F8FAFC;border:1.5px solid var(--rb);border-radius:9px;padding:0 12px 0 34px;font-size:.82rem;color:var(--rt);outline:none;transition:border-color .2s;}
.rf-search input:focus{border-color:var(--rp);background:#fff;}
.rf-search i[data-feather]{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;stroke:var(--rm);}
/* Permission grid */
.rf-perm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px;}
.rf-perm-group{background:#fff;border-radius:12px;border:1px solid var(--rb);overflow:hidden;transition:border-color .2s,box-shadow .2s;}
.rf-perm-group:hover{border-color:#DDD6FE;box-shadow:0 2px 12px rgba(124,58,237,.08);}
.rf-perm-group-head{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:linear-gradient(135deg,var(--rp-lt),#faf5ff);border-bottom:1px solid #EDE9FE;cursor:pointer;}
.rf-perm-group-name{font-size:.8rem;font-weight:700;color:#6D28D9;display:flex;align-items:center;gap:6px;}
.rf-perm-group-name i[data-feather]{width:13px;height:13px;}
.rf-perm-all{display:flex;align-items:center;gap:6px;font-size:.72rem;font-weight:600;color:var(--rm);cursor:pointer;}
/* Toggle switch */
.rf-toggle{position:relative;width:36px;height:20px;flex-shrink:0;}
.rf-toggle input{opacity:0;width:0;height:0;position:absolute;}
.rf-toggle-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:20px;cursor:pointer;transition:background .2s;}
.rf-toggle-slider::after{content:'';position:absolute;top:3px;left:3px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.rf-toggle input:checked+.rf-toggle-slider{background:var(--rp);}
.rf-toggle input:checked+.rf-toggle-slider::after{transform:translateX(16px);}
/* Group-level toggle */
.rf-all-toggle{position:relative;width:32px;height:18px;flex-shrink:0;}
.rf-all-toggle input{opacity:0;width:0;height:0;position:absolute;}
.rf-all-toggle-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:18px;cursor:pointer;transition:background .2s;}
.rf-all-toggle-slider::after{content:'';position:absolute;top:2px;left:2px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 2px rgba(0,0,0,.2);}
.rf-all-toggle input:checked+.rf-all-toggle-slider{background:var(--rg);}
.rf-all-toggle input:checked+.rf-all-toggle-slider::after{transform:translateX(14px);}
/* Permission items */
.rf-perm-items{padding:8px 0;}
.rf-perm-item{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;transition:background .15s;}
.rf-perm-item:hover{background:#FAFAFA;}
.rf-perm-item label{font-size:.8rem;font-weight:500;color:var(--rt);cursor:pointer;flex:1;margin:0;}
.rf-perm-count-badge{font-size:.65rem;font-weight:700;color:var(--rp);background:var(--rp-lt);border-radius:20px;padding:1px 7px;margin-left:6px;}
/* Submit */
.rf-submit-wrap{display:flex;align-items:center;gap:12px;padding:20px 0 4px;}
.rf-submit{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#6D28D9,#7C3AED);color:#fff;border:none;border-radius:12px;padding:12px 28px;font-size:.9rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(109,40,217,.35);transition:all .2s;}
.rf-submit:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(109,40,217,.4);}
.rf-submit i[data-feather]{width:16px;height:16px;stroke:#fff;}
.rf-back{display:inline-flex;align-items:center;gap:7px;background:transparent;color:var(--rm);border:1.5px solid var(--rb);border-radius:12px;padding:11px 20px;font-size:.88rem;font-weight:600;text-decoration:none;transition:all .2s;}
.rf-back:hover{background:#F1F5F9;color:var(--rt);}
.rf-back i[data-feather]{width:14px;height:14px;}
.rf-err{font-size:.73rem;color:var(--rr);margin-top:4px;}
/* Hidden groups */
.rf-perm-group.rf-hidden{display:none;}
@media(max-width:640px){.rf-page{padding:12px;}.rf-perm-grid{grid-template-columns:1fr 1fr;}.rf-hero{padding:16px 18px;}.rf-toolbar-btns{flex-wrap:wrap;}}
@media(max-width:400px){.rf-perm-grid{grid-template-columns:1fr;}}
</style>

<div class="rf-page">
  <div class="rf-hero">
    <div class="rf-hero-icon"><i data-feather="shield"></i></div>
    <div class="rf-hero-text">
      <h1>Create New Role</h1>
      <p>Define a role name and select which permissions it grants</p>
    </div>
  </div>

  <form action="{{ route('roles.store') }}" method="POST" id="roleForm">
    @csrf

    {{-- Role Name --}}
    <div class="rf-card">
      <div class="rf-card-head">
        <div class="rf-card-head-icon"><i data-feather="tag"></i></div>
        <p class="rf-card-head-title">Role Name</p>
      </div>
      <div class="rf-card-body">
        <label class="rf-label">Role Name <span>*</span></label>
        <input type="text" name="name" class="rf-input" value="{{ old('name') }}" required placeholder="e.g. Sales Manager">
        @error('name')<p class="rf-err">{{ $message }}</p>@enderror
      </div>
    </div>

    {{-- Permissions --}}
    <div class="rf-card">
      <div class="rf-card-head">
        <div class="rf-card-head-icon"><i data-feather="key"></i></div>
        <p class="rf-card-head-title">Permissions</p>
      </div>
      <div class="rf-card-body">

        {{-- Toolbar --}}
        <div class="rf-perm-toolbar">
          <div>
            <div class="rf-perm-title">Select Permissions</div>
            <div class="rf-perm-meta" id="rfSelCount">0 permissions selected</div>
          </div>
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <div class="rf-search">
              <i data-feather="search"></i>
              <input type="text" id="rfSearch" placeholder="Search permissions…" oninput="rfSearch()">
            </div>
            <div class="rf-toolbar-btns">
              <button type="button" class="rf-sel-btn rf-sel-all" onclick="rfSelectAll(true)">
                <i data-feather="check-square" style="width:13px;height:13px;"></i> All
              </button>
              <button type="button" class="rf-sel-btn rf-sel-none" onclick="rfSelectAll(false)">
                <i data-feather="square" style="width:13px;height:13px;"></i> None
              </button>
            </div>
          </div>
        </div>

        @error('permission')<p class="rf-err" style="margin-bottom:12px;">{{ $message }}</p>@enderror

        <div class="rf-perm-grid" id="rfPermGrid">
          @foreach($permissionParent as $parent)
          @php
            $perms = Spatie\Permission\Models\Permission::where('parent_name', $parent->parent_name)->get();
            $groupKey = preg_replace('/[^a-z0-9]/i','_', $parent->parent_name);
            $label = ucwords(str_replace(['-','_'],' ', $parent->parent_name));
          @endphp
          <div class="rf-perm-group" data-group="{{ strtolower($label) }}">
            <div class="rf-perm-group-head">
              <span class="rf-perm-group-name">
                <i data-feather="layers"></i>
                {{ $label }}
                <span class="rf-perm-count-badge group-count-{{ $groupKey }}">0</span>
              </span>
              <label class="rf-perm-all" title="Toggle all in {{ $label }}">
                <span style="font-size:.68rem;">All</span>
                <label class="rf-all-toggle">
                  <input type="checkbox" class="grp-all-{{ $groupKey }}" onchange="rfGroupToggle('{{ $groupKey }}', this.checked)">
                  <span class="rf-all-toggle-slider"></span>
                </label>
              </label>
            </div>
            <div class="rf-perm-items">
              @foreach($perms as $perm)
              @php $permLabel = ucwords(str_replace(['-','_'],' ', $perm->name)); @endphp
              <div class="rf-perm-item">
                <label for="p_{{ $perm->id }}">{{ $permLabel }}</label>
                <label class="rf-toggle">
                  <input type="checkbox" name="permission[]" id="p_{{ $perm->id }}"
                         class="perm-cb grp-cb-{{ $groupKey }}" value="{{ $perm->id }}"
                         onchange="rfUpdate()">
                  <span class="rf-toggle-slider"></span>
                </label>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>

      </div>
    </div>

    <div class="rf-submit-wrap">
      <button type="submit" class="rf-submit">
        <i data-feather="save"></i> Create Role
      </button>
      <a href="{{ route('roles.index') }}" class="rf-back">
        <i data-feather="arrow-left"></i> Back
      </a>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
    rfUpdate();
});

function rfUpdate() {
    var all = document.querySelectorAll('.perm-cb');
    var checked = document.querySelectorAll('.perm-cb:checked');
    document.getElementById('rfSelCount').textContent = checked.length + ' of ' + all.length + ' permissions selected';

    // Update per-group counts and group toggles
    document.querySelectorAll('.rf-perm-group').forEach(function(grp) {
        var classes = grp.querySelector('.rf-perm-items').querySelectorAll('.perm-cb');
        var checkedGrp = grp.querySelector('.rf-perm-items').querySelectorAll('.perm-cb:checked');
        // Find group key from class
        classes.forEach(function(cb) {
            cb.classList.forEach(function(c) {
                if (c.startsWith('grp-cb-')) {
                    var key = c.replace('grp-cb-','');
                    var badge = document.querySelector('.group-count-'+key);
                    if (badge) badge.textContent = checkedGrp.length;
                    var allChk = document.querySelector('.grp-all-'+key);
                    if (allChk) allChk.checked = checkedGrp.length === classes.length && classes.length > 0;
                }
            });
        });
    });
}

function rfGroupToggle(key, checked) {
    document.querySelectorAll('.grp-cb-'+key).forEach(function(cb) { cb.checked = checked; });
    rfUpdate();
}

function rfSelectAll(checked) {
    document.querySelectorAll('.perm-cb').forEach(function(cb) { cb.checked = checked; });
    document.querySelectorAll('[class*="grp-all-"]').forEach(function(cb) { cb.checked = checked; });
    rfUpdate();
}

function rfSearch() {
    var q = document.getElementById('rfSearch').value.toLowerCase().trim();
    document.querySelectorAll('.rf-perm-group').forEach(function(grp) {
        var name = (grp.dataset.group||'').toLowerCase();
        var items = grp.querySelectorAll('.rf-perm-item');
        var anyVisible = false;
        items.forEach(function(item) {
            var lbl = item.querySelector('label') ? item.querySelector('label').textContent.toLowerCase() : '';
            var show = !q || lbl.includes(q) || name.includes(q);
            item.style.display = show ? '' : 'none';
            if (show) anyVisible = true;
        });
        grp.classList.toggle('rf-hidden', !anyVisible && q !== '');
    });
}
</script>
@endsection
