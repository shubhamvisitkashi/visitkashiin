@extends('admin.layouts.app')
@section('content')
<style>
.bt-page{padding:24px;background:#F1F5F9;min-height:100vh;}
/* Header */
.bt-header{background:linear-gradient(135deg,#0C4A6E,#0369A1,#0EA5E9);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(3,105,161,.28);}
.bt-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.bt-header::after{content:'⛵';position:absolute;bottom:-8px;right:20px;font-size:80px;opacity:.07;line-height:1;}
.bt-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.bt-header-icon{width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bt-header-icon svg{width:22px;height:22px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.bt-header-text h1{color:#fff;font-size:1.12rem;font-weight:800;margin:0 0 2px;}
.bt-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.bt-header-right{position:relative;z-index:1;}
/* Search in header */
.bt-search{display:flex;align-items:center;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);border-radius:10px;overflow:hidden;height:36px;}
.bt-search input{background:transparent;border:none;outline:none;color:#fff;padding:0 12px;font-size:.82rem;width:180px;}
.bt-search input::placeholder{color:rgba(255,255,255,.55);}
.bt-search button{background:rgba(255,255,255,.15);border:none;border-left:1px solid rgba(255,255,255,.2);height:100%;padding:0 12px;cursor:pointer;display:flex;align-items:center;}
.bt-search button svg{width:14px;height:14px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
/* Stats */
.bt-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px;}
.bt-stat{background:#fff;border-radius:13px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 16px;display:flex;align-items:center;gap:12px;}
.bt-stat-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bt-stat-icon svg{width:19px;height:19px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.bt-stat-val{font-size:1.35rem;font-weight:900;color:#0F172A;line-height:1;}
.bt-stat-lbl{font-size:.67rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-top:2px;}
/* Main layout */
.bt-layout{display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;}
@media(max-width:1024px){.bt-layout{grid-template-columns:1fr;}}
/* Table card */
.bt-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.bt-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#F0F9FF;}
.bt-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.bt-card-title svg{width:15px;height:15px;stroke:#0369A1;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
/* Table */
.bt-table{width:100%;border-collapse:collapse;}
.bt-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.bt-table td{padding:12px 14px;border-bottom:1px solid #F8FAFC;font-size:.83rem;color:#0F172A;vertical-align:middle;}
.bt-table tbody tr:last-child td{border-bottom:none;}
.bt-table tbody tr:hover{background:#F0F9FF;}
/* Image cell */
.bt-thumb{width:44px;height:44px;border-radius:10px;object-fit:cover;border:2px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.07);}
.bt-thumb-placeholder{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#0369A1,#0EA5E9);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bt-thumb-placeholder svg{width:20px;height:20px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
/* Toggle */
.bt-switch{position:relative;display:inline-block;width:38px;height:21px;}
.bt-switch input{opacity:0;width:0;height:0;position:absolute;}
.bt-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:21px;cursor:pointer;transition:background .2s;}
.bt-switch-slider::after{content:'';position:absolute;top:3.5px;left:3.5px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.bt-switch input:checked+.bt-switch-slider{background:#0EA5E9;}
.bt-switch input:checked+.bt-switch-slider::after{transform:translateX(17px);}
/* Actions */
.bt-actions{display:flex;gap:5px;justify-content:center;}
.bt-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.bt-act svg{width:13px;height:13px;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.bt-act-edit{background:#EEF2FF;}.bt-act-edit svg{stroke:#4F46E5;}.bt-act-edit:hover{background:#DDD6FE;}
.bt-act-del{background:#FEF2F2;}.bt-act-del svg{stroke:#DC2626;}.bt-act-del:hover{background:#FECACA;}
/* Empty */
.bt-empty{text-align:center;padding:48px;color:#94A3B8;}
.bt-empty svg{width:52px;height:52px;opacity:.18;display:block;margin:0 auto 12px;stroke:#94A3B8;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;}
/* Pagination */
.bt-pag{padding:12px 18px;border-top:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
/* Form card */
.bt-form-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.bt-form-head{padding:14px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:9px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);}
.bt-form-head-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#0369A1,#0EA5E9);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bt-form-head-icon svg{width:14px;height:14px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.bt-form-head-title{font-size:.88rem;font-weight:800;color:#0F172A;}
.bt-form-head-sub{font-size:.71rem;color:#64748B;margin-top:1px;}
.bt-form-body{padding:18px;}
.bt-label{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.bt-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:8px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.bt-input:focus{border-color:#0EA5E9;box-shadow:0 0 0 3px rgba(14,165,233,.1);}
.bt-field{margin-bottom:14px;}
.bt-seo-divider{display:flex;align-items:center;gap:10px;margin:16px 0 14px;}
.bt-seo-divider span{font-size:.72rem;font-weight:700;background:#EFF6FF;color:#0369A1;padding:3px 10px;border-radius:20px;white-space:nowrap;border:1px solid #BAE6FD;}
.bt-seo-divider::before,.bt-seo-divider::after{content:'';flex:1;height:1px;background:#E2E8F0;}
/* Preview image in form */
.bt-cur-img{display:flex;align-items:center;gap:10px;padding:8px 10px;background:#F0F9FF;border-radius:9px;margin-top:8px;border:1px solid #BAE6FD;}
.bt-cur-img img{width:48px;height:48px;border-radius:8px;object-fit:cover;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.1);}
.bt-cur-img span{font-size:.75rem;color:#0369A1;font-weight:600;}
/* Buttons */
.bt-btn-row{display:flex;gap:8px;margin-top:4px;}
.bt-submit{flex:1;height:38px;background:linear-gradient(135deg,#0369A1,#0EA5E9);color:#fff;border:none;border-radius:9px;font-size:.84rem;font-weight:700;cursor:pointer;transition:opacity .2s;}
.bt-submit:hover{opacity:.88;}
.bt-cancel{height:38px;padding:0 16px;background:#F1F5F9;color:#64748B;border:none;border-radius:9px;font-size:.84rem;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;}
.bt-cancel:hover{background:#E2E8F0;color:#374151;text-decoration:none;}
/* Alert flashes */
.bt-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.bt-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.bt-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
@media(max-width:900px){.bt-stats{grid-template-columns:1fr 1fr;}}
@media(max-width:640px){.bt-page{padding:12px;}.bt-stats{grid-template-columns:1fr 1fr;}}
</style>

<div class="bt-page">

{{-- Flash messages --}}
@if(session('success'))
<div class="bt-alert bt-alert-ok">
    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:#10B981;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bt-alert bt-alert-err">
    <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:#EF4444;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- Header --}}
<div class="bt-header">
    <div class="bt-header-left">
        <div class="bt-header-icon">
            <svg viewBox="0 0 24 24"><path d="M3 17l2-11 7 4 7-4 2 11H3z"/><line x1="12" y1="10" x2="12" y2="2"/></svg>
        </div>
        <div class="bt-header-text">
            <h1>Boat Type Management</h1>
            <p>Manage boat categories for tours &amp; rides on the Ganges</p>
        </div>
    </div>
    <div class="bt-header-right">
        <form action="{{ route('boat-type.index') }}" method="GET" class="bt-search">
            <input type="text" name="search_key" placeholder="Search boat types…" value="{{ $search_key ?? '' }}">
            <button type="submit">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="bt-stats">
    <div class="bt-stat">
        <div class="bt-stat-icon" style="background:linear-gradient(135deg,#0369A1,#0EA5E9);">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
        </div>
        <div>
            <div class="bt-stat-val">{{ $boat_types->total() }}</div>
            <div class="bt-stat-lbl">Total Types</div>
        </div>
    </div>
    <div class="bt-stat">
        <div class="bt-stat-icon" style="background:linear-gradient(135deg,#059669,#10B981);">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <div class="bt-stat-val">{{ $boat_types->where('is_active', 1)->count() }}</div>
            <div class="bt-stat-lbl">Active</div>
        </div>
    </div>
    <div class="bt-stat">
        <div class="bt-stat-icon" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg>
        </div>
        <div>
            <div class="bt-stat-val">{{ $boat_types->where('is_active', 0)->count() }}</div>
            <div class="bt-stat-lbl">Inactive</div>
        </div>
    </div>
</div>

{{-- Main layout --}}
<div class="bt-layout">

    {{-- Table --}}
    <div>
        <div class="bt-card">
            <div class="bt-card-head">
                <div class="bt-card-title">
                    <svg viewBox="0 0 24 24"><path d="M3 17l2-11 7 4 7-4 2 11H3z"/></svg>
                    All Boat Types
                </div>
                <span style="font-size:.73rem;color:#64748B;font-weight:600;">{{ $boat_types->total() }} types</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="bt-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Boat Type Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boat_types as $boat_type)
                        <tr>
                            <td style="color:#94A3B8;font-size:.78rem;font-weight:600;">{{ $boat_types->firstItem() + $loop->index }}</td>
                            <td>
                                @if($boat_type->image)
                                    <img src="{{ $boat_type->image }}" alt="{{ $boat_type->name }}" class="bt-thumb">
                                @else
                                    <div class="bt-thumb-placeholder">
                                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:700;font-size:.85rem;color:#0F172A;">{{ $boat_type->name }}</div>
                            </td>
                            <td class="text-center">
                                <label class="bt-switch">
                                    <input type="checkbox" class="status_update" value="{{ $boat_type->slug }}" {{ $boat_type->is_active == '1' ? 'checked' : '' }}>
                                    <span class="bt-switch-slider"></span>
                                </label>
                            </td>
                            <td>
                                <div class="bt-actions">
                                    <a href="{{ route('boat-type.edit', $boat_type->slug) }}" class="bt-act bt-act-edit" title="Edit">
                                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    <form action="{{ route('boat-type.destroy', $boat_type->slug) }}" method="POST" id="del_{{ $boat_type->id }}" style="display:contents;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="bt-act bt-act-del" onclick="confirmDelete('{{ $boat_type->id }}','{{ addslashes($boat_type->name) }}')" title="Delete">
                                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="bt-empty">
                                    <svg viewBox="0 0 24 24"><path d="M3 17l2-11 7 4 7-4 2 11H3z"/><line x1="12" y1="10" x2="12" y2="2"/></svg>
                                    <p style="font-weight:700;color:#374151;margin:0 0 5px;">No boat types found</p>
                                    <p style="font-size:.82rem;margin:0;">Add your first boat type using the form on the right →</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($boat_types->hasPages())
            <div class="bt-pag">
                <span style="font-size:.75rem;color:#64748B;font-weight:600;">{{ $boat_types->total() }} total</span>
                {{ $boat_types->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Form --}}
    <div>
        <div class="bt-form-card">
            <div class="bt-form-head">
                <div class="bt-form-head-icon">
                    @isset($edit_boat_type)
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    @else
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    @endisset
                </div>
                <div>
                    <div class="bt-form-head-title">
                        @isset($edit_boat_type) Update Boat Type @else Add New Boat Type @endisset
                    </div>
                    <div class="bt-form-head-sub">
                        @isset($edit_boat_type) Editing: <strong>{{ $edit_boat_type->name }}</strong> @else Fill in the details below @endisset
                    </div>
                </div>
            </div>

            <div class="bt-form-body">
                @isset($edit_boat_type)
                    <form id="bt_form" method="POST" action="{{ route('boat-type.update', $edit_boat_type->slug) }}" enctype="multipart/form-data">
                        @method('PUT')
                @else
                    <form id="bt_form" method="POST" action="{{ route('boat-type.store') }}" enctype="multipart/form-data">
                @endisset
                @csrf

                {{-- Name --}}
                <div class="bt-field">
                    <label class="bt-label">Boat Type Name <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" class="bt-input" required placeholder="e.g. Motor Boat, Row Boat, Shikara"
                        value="{{ old('name', $edit_boat_type->name ?? '') }}">
                    @error('name')
                    <div style="font-size:.73rem;color:#DC2626;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="bt-field">
                    <label class="bt-label">Image</label>
                    <input type="file" name="image" class="bt-input" accept="image/*" style="padding:5px 12px;cursor:pointer;">
                    @isset($edit_boat_type)
                        @if($edit_boat_type->image)
                        <div class="bt-cur-img">
                            <img src="{{ $edit_boat_type->image }}" alt="{{ $edit_boat_type->name }}">
                            <span>Current image</span>
                        </div>
                        @endif
                    @endisset
                    @error('image')
                    <div style="font-size:.73rem;color:#DC2626;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- SEO --}}
                <div class="bt-seo-divider"><span>🔍 SEO Settings</span></div>

                <div class="bt-field">
                    <label class="bt-label">SEO Title</label>
                    <input type="text" name="seo_title" class="bt-input" placeholder="Page title for search engines"
                        value="{{ old('seo_title', $edit_boat_type->seo_title ?? '') }}">
                    @error('seo_title')
                    <div style="font-size:.73rem;color:#DC2626;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bt-field">
                    <label class="bt-label">SEO Keywords</label>
                    <input type="text" name="seo_keywords" class="bt-input" placeholder="boat ride varanasi, ganga boat"
                        value="{{ old('seo_keywords', $edit_boat_type->seo_keywords ?? '') }}">
                    @error('seo_keywords')
                    <div style="font-size:.73rem;color:#DC2626;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bt-field">
                    <label class="bt-label">SEO Description</label>
                    <textarea name="seo_description" class="bt-input" rows="3" placeholder="Meta description for search engines…">{{ old('seo_description', $edit_boat_type->seo_description ?? '') }}</textarea>
                    @error('seo_description')
                    <div style="font-size:.73rem;color:#DC2626;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bt-btn-row">
                    <button type="submit" class="bt-submit">
                        @isset($edit_boat_type) Update Boat Type @else Save Boat Type @endisset
                    </button>
                    @isset($edit_boat_type)
                    <a href="{{ route('boat-type.index') }}" class="bt-cancel">Cancel</a>
                    @endisset
                </div>

                </form>
            </div>
        </div>
    </div>

</div>{{-- /.bt-layout --}}
</div>{{-- /.bt-page --}}

<script>
// Status toggle
document.querySelectorAll('.status_update').forEach(function(el){
    el.addEventListener('change', function(){
        var slug = this.value;
        var cb = this;
        cb.disabled = true;
        fetch('{{ route('boat-type.show', '') }}/' + slug)
            .then(r => r.json())
            .then(function(data){
                cb.disabled = false;
                Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true})
                    .fire({icon: data.res, title: data.message});
            })
            .catch(function(){
                cb.disabled = false;
                cb.checked = !cb.checked;
                Swal.fire({icon:'error',title:'Error',text:'Failed to update status.',timer:2500,showConfirmButton:false});
            });
    });
});

// Delete confirm
function confirmDelete(id, name){
    Swal.fire({
        title: 'Delete Boat Type?',
        html: 'Delete <strong>' + name + '</strong>? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6C757D',
        confirmButtonText: 'Yes, delete it!'
    }).then(function(r){ if(r.isConfirmed) document.getElementById('del_' + id).submit(); });
}
</script>
@endsection
