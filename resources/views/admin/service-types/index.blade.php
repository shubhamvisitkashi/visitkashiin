@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
.sv-page{padding:24px;background:#F1F5F9;min-height:100vh;}
/* Shared header */
.sv-header{border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;}
.sv-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.sv-header.st{background:linear-gradient(135deg,#1e3a5f,#0369A1,#0EA5E9);box-shadow:0 8px 24px rgba(14,165,233,.28);}
.sv-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.sv-header-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sv-header-icon i[data-feather]{width:20px;height:20px;stroke:#fff;}
.sv-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.sv-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.sv-add-btn{position:relative;z-index:1;display:inline-flex;align-items:center;gap:6px;background:#fff;color:#0369A1;border:none;border-radius:10px;padding:9px 16px;font-size:.82rem;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:all .2s;white-space:nowrap;}
.sv-add-btn:hover{background:#f0f9ff;color:#0369A1;transform:translateY(-1px);}
.sv-add-btn i[data-feather]{width:14px;height:14px;}
/* Card */
.sv-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.sv-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#F0F9FF;}
.sv-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.sv-card-title i[data-feather]{width:15px;height:15px;stroke:#0EA5E9;}
/* Table */
.sv-table{width:100%;border-collapse:collapse;}
.sv-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.sv-table td{padding:12px 14px;border-bottom:1px solid #F8FAFC;font-size:.83rem;color:#0F172A;vertical-align:middle;}
.sv-table tbody tr:last-child td{border-bottom:none;}
.sv-table tbody tr:hover{background:#F0F9FF;}
.sv-slug{font-family:monospace;background:#F1F5F9;color:#475569;font-size:.75rem;padding:2px 8px;border-radius:5px;border:1px solid #E2E8F0;}
.sv-cnt{display:inline-flex;align-items:center;gap:4px;padding:2px 9px;border-radius:20px;font-size:.7rem;font-weight:700;}
.sv-cnt-blue{background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;}
.sv-cnt-sky{background:#F0F9FF;color:#0369A1;border:1px solid #BAE6FD;}
.sv-switch{position:relative;display:inline-block;width:36px;height:20px;}
.sv-switch input{opacity:0;width:0;height:0;position:absolute;}
.sv-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:20px;cursor:pointer;transition:background .2s;}
.sv-switch-slider::after{content:'';position:absolute;top:3px;left:3px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.sv-switch input:checked+.sv-switch-slider{background:#0EA5E9;}
.sv-switch input:checked+.sv-switch-slider::after{transform:translateX(16px);}
.sv-actions{display:flex;gap:5px;justify-content:center;}
.sv-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.sv-act i[data-feather]{width:13px;height:13px;}
.sv-act-edit{background:#EEF2FF;color:#4F46E5;}.sv-act-edit:hover{background:#ddd6fe;}
.sv-act-del {background:#FEF2F2;color:#DC2626;}.sv-act-del:hover{background:#FECACA;}
.sv-empty{text-align:center;padding:44px;color:#94A3B8;}
.sv-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.sv-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.sv-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
/* Modal */
.sv-modal .modal-content{border:none;border-radius:16px;overflow:hidden;}
.sv-modal .modal-header{background:linear-gradient(135deg,#0369A1,#0EA5E9);border:none;padding:14px 20px;}
.sv-modal .modal-title{color:#fff;font-size:.92rem;font-weight:800;}
.sv-modal .modal-body{padding:18px 20px;}
.sv-modal .modal-footer{padding:12px 20px;background:#F8FAFC;border-top:1px solid #E2E8F0;}
.sv-label{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.sv-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:8px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.sv-input:focus{border-color:#0EA5E9;box-shadow:0 0 0 3px rgba(14,165,233,.1);}
.sv-field{margin-bottom:14px;}
.sv-modal-submit{background:linear-gradient(135deg,#0369A1,#0EA5E9);color:#fff;border:none;border-radius:9px;padding:9px 20px;font-weight:700;font-size:.84rem;cursor:pointer;}
.sv-modal-cancel{background:#F1F5F9;color:#64748B;border:none;border-radius:9px;padding:9px 16px;font-weight:700;font-size:.84rem;cursor:pointer;}
@media(max-width:640px){.sv-page{padding:12px;}}
</style>

<div class="sv-page">

@if(session('success'))<div class="sv-alert sv-alert-ok"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="sv-alert sv-alert-err"><i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#EF4444;"></i> {{ session('error') }}</div>@endif

<div class="sv-header st">
    <div class="sv-header-left">
        <div class="sv-header-icon"><i data-feather="layers"></i></div>
        <div class="sv-header-text">
            <h1>Service Types</h1>
            <p>Manage service categories (Cab, Boat, Hotel, etc.)</p>
        </div>
    </div>
    <button type="button" class="sv-add-btn" data-bs-toggle="modal" data-bs-target="#serviceTypeModal" onclick="resetForm()">
        <i data-feather="plus"></i> Add Service Type
    </button>
</div>

<div class="sv-card">
    <div class="sv-card-head">
        <div class="sv-card-title"><i data-feather="layers"></i> All Service Types</div>
        <span style="font-size:.73rem;color:#64748B;">{{ $serviceTypes->count() }} types</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="sv-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th class="text-center">Providers</th>
                    <th class="text-center">Items</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($serviceTypes as $i => $type)
                <tr>
                    <td style="color:#94A3B8;font-size:.78rem;">{{ $i+1 }}</td>
                    <td style="font-weight:700;">{{ $type->name }}</td>
                    <td><span class="sv-slug">{{ $type->slug }}</span></td>
                    <td class="text-center"><span class="sv-cnt sv-cnt-blue">{{ $type->service_providers_count }}</span></td>
                    <td class="text-center"><span class="sv-cnt sv-cnt-sky">{{ $type->service_items_count }}</span></td>
                    <td class="text-center">
                        <label class="sv-switch">
                            <input type="checkbox" id="status{{ $type->id }}" {{ $type->is_active?'checked':'' }} onchange="toggleStatus({{ $type->id }})">
                            <span class="sv-switch-slider"></span>
                        </label>
                    </td>
                    <td>
                        <div class="sv-actions">
                            <button type="button" class="sv-act sv-act-edit" onclick="editServiceType({{ $type->id }})" title="Edit">
                                <i data-feather="edit-2"></i>
                            </button>
                            <form action="{{ route('service-types.destroy', $type->id) }}" method="POST" id="delete_form_{{ $type->id }}" style="display:contents;">
                                @csrf @method('DELETE')
                                <button type="button" class="sv-act sv-act-del" onclick="confirmDeleteServiceType({{ $type->id }},'{{ addslashes($type->name) }}')" title="Delete">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <div class="sv-empty"><i data-feather="layers"></i>
                        <p style="font-weight:700;color:#374151;margin:0 0 4px;">No service types yet</p></div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

{{-- Modal --}}
<div class="modal fade sv-modal" id="serviceTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Service Type</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="serviceTypeForm" method="POST" action="{{ route('service-types.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-body">
                    <div class="sv-field">
                        <label class="sv-label">Name <span style="color:#EF4444;">*</span></label>
                        <input type="text" class="sv-input" name="name" id="name" required placeholder="e.g. Cab, Boat">
                    </div>
                    <div class="sv-field">
                        <label class="sv-label">Slug <span style="color:#EF4444;">*</span></label>
                        <input type="text" class="sv-input" name="slug" id="slug" required placeholder="e.g. cab, boat">
                        <div style="font-size:.7rem;color:#94A3B8;margin-top:4px;">Lowercase, no spaces — auto-generated from name</div>
                    </div>
                    <div class="sv-field">
                        <label class="sv-label">Terms & Conditions</label>
                        <textarea class="sv-input" name="terms_conditions" id="terms_conditions" rows="5" placeholder="Terms for booking invoices…"></textarea>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <label class="sv-switch" style="margin:0;">
                            <input type="checkbox" name="is_active" id="is_active" checked>
                            <span class="sv-switch-slider" style="background:#0EA5E9;"></span>
                        </label>
                        <span style="font-size:.82rem;font-weight:600;color:#374151;">Active</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="sv-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="sv-modal-submit">Save Service Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function resetForm(){
    document.getElementById('serviceTypeForm').reset();
    document.getElementById('serviceTypeForm').action='{{ route('service-types.store') }}';
    document.getElementById('formMethod').value='POST';
    document.getElementById('modalTitle').textContent='Add Service Type';
}
function editServiceType(id){
    fetch('{{ url('admin/service-types') }}/'+id+'/edit')
        .then(r=>r.json()).then(d=>{
            document.getElementById('name').value=d.name;
            document.getElementById('slug').value=d.slug;
            document.getElementById('is_active').checked=d.is_active;
            document.getElementById('terms_conditions').value=d.terms_conditions||'';
            document.getElementById('serviceTypeForm').action='{{ url('admin/service-types') }}/'+id;
            document.getElementById('formMethod').value='PUT';
            document.getElementById('modalTitle').textContent='Edit Service Type';
            new bootstrap.Modal(document.getElementById('serviceTypeModal')).show();
        });
}
function toggleStatus(id){
    fetch('{{ url('admin/service-types') }}/'+id+'/toggle-status',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}})
        .then(r=>r.json());
}
document.getElementById('name').addEventListener('input',function(){
    if(document.getElementById('formMethod').value==='POST')
        document.getElementById('slug').value=this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');
});
function confirmDeleteServiceType(id,name){
    Swal.fire({title:'Delete Service Type?',html:'Delete <strong>'+name+'</strong>? This affects all related providers & items!',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',cancelButtonColor:'#6c757d',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(r.isConfirmed) document.getElementById('delete_form_'+id).submit(); });
}
document.addEventListener('DOMContentLoaded',function(){feather.replace();});
</script>
@endpush
@endsection
