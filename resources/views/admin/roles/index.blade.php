@extends('admin.layouts.app')
@section('content')
<style>
:root{--rp:#7C3AED;--rp-lt:#F5F3FF;--rg:#10B981;--rr:#EF4444;--rt:#0F172A;--rm:#64748B;--rb:#E2E8F0;--rs:14px;}
.rl-page{padding:24px;}
.rl-header{background:linear-gradient(135deg,#6D28D9,#7C3AED,#8B5CF6);border-radius:16px;padding:22px 28px;display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;position:relative;overflow:hidden;}
.rl-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.06);border-radius:50%;}
.rl-header::after{content:'';position:absolute;bottom:-50px;left:20px;width:120px;height:120px;background:rgba(255,255,255,.04);border-radius:50%;}
.rl-header-left{position:relative;z-index:1;}
.rl-header-left h1{color:#fff;font-size:1.2rem;font-weight:800;margin:0 0 3px;}
.rl-header-left p{color:rgba(255,255,255,.7);font-size:.8rem;margin:0;}
.rl-add-btn{position:relative;z-index:1;display:inline-flex;align-items:center;gap:7px;background:#fff;color:var(--rp);border:none;border-radius:10px;padding:10px 18px;font-size:.83rem;font-weight:700;text-decoration:none;box-shadow:0 4px 14px rgba(0,0,0,.15);transition:all .2s;}
.rl-add-btn:hover{background:#F5F3FF;color:var(--rp);transform:translateY(-1px);}
.rl-add-btn i[data-feather]{width:15px;height:15px;}

/* Role cards grid */
.rl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-bottom:24px;}
.rl-card{background:#fff;border-radius:var(--rs);border:1px solid var(--rb);box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;transition:box-shadow .2s,transform .2s;position:relative;}
.rl-card:hover{box-shadow:0 6px 24px rgba(124,58,237,.12);transform:translateY(-2px);}
.rl-card-bar{height:4px;background:linear-gradient(90deg,#6D28D9,#8B5CF6);}
.rl-card-body{padding:18px 20px;}
.rl-card-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;}
.rl-card-icon{width:42px;height:42px;border-radius:12px;background:var(--rp-lt);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.rl-card-icon i[data-feather]{width:20px;height:20px;stroke:var(--rp);}
.rl-card-actions{display:flex;gap:6px;}
.rl-btn{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .18s;flex-shrink:0;}
.rl-btn i[data-feather]{width:13px;height:13px;}
.rl-btn-edit{background:#EEF2FF;color:#4F46E5;}.rl-btn-edit:hover{background:#ddd6fe;}
.rl-btn-del{background:#FEF2F2;color:#EF4444;}.rl-btn-del:hover{background:#FECACA;}
.rl-card-name{font-size:1rem;font-weight:800;color:var(--rt);margin:0 0 4px;text-transform:capitalize;}
.rl-card-meta{font-size:.75rem;color:var(--rm);display:flex;align-items:center;gap:6px;}
.rl-perm-count{display:inline-flex;align-items:center;gap:5px;background:var(--rp-lt);color:var(--rp);font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:20px;margin-top:10px;}
.rl-perm-count i[data-feather]{width:11px;height:11px;}

/* Empty */
.rl-empty{text-align:center;padding:56px;color:var(--rm);}
.rl-empty i[data-feather]{width:52px;height:52px;opacity:.25;display:block;margin:0 auto 14px;}
.rl-empty h3{font-size:1rem;font-weight:700;color:#374151;margin:0 0 6px;}
.rl-empty p{font-size:.84rem;margin:0;}

/* Alert */
.rl-alert{display:flex;align-items:center;gap:10px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:.87rem;font-weight:600;color:#065F46;}
.rl-alert i[data-feather]{width:18px;height:18px;flex-shrink:0;}

/* Pagination */
.rl-pag{display:flex;flex-direction:column;align-items:center;gap:10px;margin-top:8px;}
.rl-pag-info{font-size:.75rem;color:var(--rm);}
.rl-pag .pagination{display:flex;gap:5px;list-style:none;padding:0;margin:0;flex-wrap:wrap;justify-content:center;}
.rl-pag .page-item .page-link{min-width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:9px;border:1.5px solid var(--rb);background:#fff;color:#374151;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .18s;}
.rl-pag .page-item .page-link:hover{background:var(--rp-lt);border-color:var(--rp);color:var(--rp);}
.rl-pag .page-item.active .page-link{background:linear-gradient(135deg,#6D28D9,#7C3AED);border-color:transparent;color:#fff;box-shadow:0 3px 10px rgba(109,40,217,.35);}
.rl-pag .page-item.disabled .page-link{background:#F9FAFB;color:#D1D5DB;border-color:#F3F4F6;}

@media(max-width:640px){
  .rl-page{padding:12px;}
  .rl-header{padding:16px 18px;border-radius:12px;flex-direction:column;align-items:flex-start;gap:14px;}
  .rl-grid{grid-template-columns:1fr 1fr;gap:10px;}
}
@media(max-width:400px){.rl-grid{grid-template-columns:1fr;}}
</style>

<div class="rl-page">

  @if(session('success'))
  <div class="rl-alert">
    <i data-feather="check-circle"></i> {{ session('success') }}
  </div>
  @endif

  <div class="rl-header">
    <div class="rl-header-left">
      <h1>🔐 Role Management</h1>
      <p>Define access levels and permissions for each team role</p>
    </div>
    @can('role-create')
    <a href="{{ route('roles.create') }}" class="rl-add-btn">
      <i data-feather="plus"></i> Create Role
    </a>
    @endcan
  </div>

  @if($list->isNotEmpty())
  <div class="rl-grid">
    @foreach($list as $role)
    @php $permCount = $role->permissions->count(); @endphp
    <div class="rl-card">
      <div class="rl-card-bar"></div>
      <div class="rl-card-body">
        <div class="rl-card-top">
          <div class="rl-card-icon"><i data-feather="shield"></i></div>
          <div class="rl-card-actions">
            @can('role-edit')
            <a href="{{ route('roles.edit', $role->id) }}" class="rl-btn rl-btn-edit" title="Edit">
              <i data-feather="edit-2"></i>
            </a>
            @endcan
            @can('role-delete')
            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" id="rdel_{{ $role->id }}" style="display:none;">@csrf @method('DELETE')</form>
            <button type="button" class="rl-btn rl-btn-del" title="Delete"
              onclick="if(confirm('Delete role: {{ addslashes($role->name) }}?')) document.getElementById('rdel_{{ $role->id }}').submit()">
              <i data-feather="trash-2"></i>
            </button>
            @endcan
          </div>
        </div>
        <h3 class="rl-card-name">{{ $role->name }}</h3>
        <div class="rl-card-meta">
          <i data-feather="users" style="width:12px;height:12px;"></i>
          {{ $role->users->count() ?? 0 }} member{{ ($role->users->count() ?? 0) != 1 ? 's' : '' }}
        </div>
        <div class="rl-perm-count">
          <i data-feather="key"></i>
          {{ $permCount }} permission{{ $permCount != 1 ? 's' : '' }}
        </div>
      </div>
    </div>
    @endforeach
  </div>

  @if($list->hasPages())
  <div class="rl-pag">
    <div class="rl-pag-info">{{ $list->total() }} roles total</div>
    {{ $list->links() }}
  </div>
  @endif

  @else
  <div class="rl-empty">
    <i data-feather="shield-off"></i>
    <h3>No roles created yet</h3>
    <p>Create your first role to start assigning permissions to team members.</p>
  </div>
  @endif

</div>
<script>document.addEventListener('DOMContentLoaded',function(){feather.replace();});</script>
@endsection
