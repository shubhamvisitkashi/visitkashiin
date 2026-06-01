@extends('admin.layouts.app')
@section('content')
<style>
.st-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.st-header{background:linear-gradient(135deg,#4A1D96,#7C3AED,#8B5CF6);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(124,58,237,.28);}
.st-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.st-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.st-header-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.st-header-icon i[data-feather]{width:20px;height:20px;stroke:#fff;}
.st-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.st-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.st-add-btn{position:relative;z-index:1;display:inline-flex;align-items:center;gap:6px;background:#fff;color:#7C3AED;border:none;border-radius:10px;padding:9px 16px;font-size:.82rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:all .2s;white-space:nowrap;}
.st-add-btn:hover{background:#faf5ff;color:#6D28D9;text-decoration:none;transform:translateY(-1px);}
.st-add-btn i[data-feather]{width:14px;height:14px;}
.st-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.st-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#FAF5FF;}
.st-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.st-card-title i[data-feather]{width:15px;height:15px;stroke:#7C3AED;}
.st-table{width:100%;border-collapse:collapse;}
.st-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.st-table td{padding:11px 14px;border-bottom:1px solid #F8FAFC;font-size:.82rem;color:#0F172A;vertical-align:middle;}
.st-table tbody tr:last-child td{border-bottom:none;}
.st-table tbody tr:hover{background:#FAF5FF;}
.st-type-badge{display:inline-flex;background:#FAF5FF;color:#6D28D9;font-size:.7rem;font-weight:700;padding:2px 9px;border-radius:20px;border:1px solid #DDD6FE;}
.st-status-on {display:inline-flex;background:#ECFDF5;color:#065F46;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:20px;}
.st-status-off{display:inline-flex;background:#F1F5F9;color:#64748B;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:20px;}
.st-profit{display:inline-flex;align-items:center;gap:3px;background:#ECFDF5;color:#059669;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:20px;}
.st-actions{display:flex;gap:5px;justify-content:center;}
.st-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.st-act i[data-feather]{width:13px;height:13px;}
.st-act-edit{background:#EEF2FF;color:#4F46E5;}.st-act-edit:hover{background:#ddd6fe;}
.st-act-del {background:#FEF2F2;color:#DC2626;}.st-act-del:hover{background:#FECACA;}
.st-empty{text-align:center;padding:44px;color:#94A3B8;}
.st-pag{padding:12px 18px;border-top:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
.st-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.st-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.st-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
@media(max-width:640px){.st-page{padding:12px;}}
</style>

<div class="st-page">

@if(session('success'))<div class="st-alert st-alert-ok"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="st-alert st-alert-err"><i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#EF4444;"></i> {{ session('error') }}</div>@endif

<div class="st-header">
    <div class="st-header-left">
        <div class="st-header-icon"><i data-feather="copy"></i></div>
        <div class="st-header-text">
            <h1>Service Templates</h1>
            <p>Manage templates for quotations (Innova, Sedan, etc.)</p>
        </div>
    </div>
    <a href="{{ route('service-templates.create') }}" class="st-add-btn">
        <i data-feather="plus"></i> Add Template
    </a>
</div>

<div class="st-card">
    <div class="st-card-head">
        <div class="st-card-title"><i data-feather="copy"></i> All Templates</div>
        <span style="font-size:.73rem;color:#64748B;">{{ $templates->total() }} templates</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="st-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Template Name</th>
                    <th class="text-end">Selling ₹</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $i => $t)
                <tr>
                    <td style="color:#94A3B8;font-size:.78rem;">{{ $templates->firstItem()+$i }}</td>
                    <td><span class="st-type-badge">{{ $t->serviceType->name }}</span></td>
                    <td style="font-weight:700;">{{ $t->name }}</td>
                    <td class="text-end" style="font-weight:700;color:#4F46E5;">₹{{ number_format($t->default_selling_price,0) }}</td>
                    <td class="text-center">
                        <span class="{{ $t->is_active?'st-status-on':'st-status-off' }}">
                            {{ $t->is_active?'Active':'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="st-actions">
                            <a href="{{ route('service-templates.edit', $t->id) }}" class="st-act st-act-edit" title="Edit">
                                <i data-feather="edit-2"></i>
                            </a>
                            <form action="{{ route('service-templates.destroy', $t->id) }}" method="POST" id="delete_form_{{ $t->id }}" style="display:contents;">
                                @csrf @method('DELETE')
                                <button type="button" class="st-act st-act-del" onclick="delTemplate({{ $t->id }},'{{ addslashes($t->name) }}')" title="Delete">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">
                    <div class="st-empty"><i data-feather="copy" style="width:48px;height:48px;opacity:.2;display:block;margin:0 auto 12px;stroke:#94A3B8;"></i>
                        <p style="font-weight:700;color:#374151;margin:0 0 4px;">No templates found</p>
                        <p style="font-size:.82rem;margin:0;">Add your first service template</p></div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($templates->hasPages())
    <div class="st-pag">
        <span style="font-size:.75rem;color:#64748B;font-weight:600;">{{ $templates->total() }} total</span>
        {{ $templates->links() }}
    </div>
    @endif
</div>

</div>
<script>
function delTemplate(id,name){
    Swal.fire({title:'Delete Template?',html:'Delete <strong>'+name+'</strong>?',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',cancelButtonColor:'#6c757d',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(r.isConfirmed) document.getElementById('delete_form_'+id).submit(); });
}
document.addEventListener('DOMContentLoaded',function(){feather.replace();});
</script>
@endsection
