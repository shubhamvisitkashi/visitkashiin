@extends('admin.layouts.app')
@section('content')
<style>
.cat-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* Header */
.cat-header{background:linear-gradient(135deg,#0f172a,#1e3a5f,#4F46E5);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(79,70,229,.28);}
.cat-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;}
.cat-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.cat-header-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.cat-header-icon i[data-feather]{width:20px;height:20px;stroke:#fff;}
.cat-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.cat-header-text p{color:rgba(255,255,255,.65);font-size:.76rem;margin:0;}
.cat-header-right{position:relative;z-index:1;}

/* Search */
.cat-search{display:flex;align-items:center;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);border-radius:10px;overflow:hidden;height:36px;}
.cat-search input{background:transparent;border:none;outline:none;color:#fff;padding:0 12px;font-size:.82rem;width:180px;}
.cat-search input::placeholder{color:rgba(255,255,255,.55);}
.cat-search button{background:rgba(255,255,255,.15);border:none;border-left:1px solid rgba(255,255,255,.2);height:100%;padding:0 12px;cursor:pointer;display:flex;align-items:center;}
.cat-search button i[data-feather]{width:14px;height:14px;stroke:#fff;}

/* Layout */
.cat-layout{display:grid;grid-template-columns:1fr 400px;gap:20px;align-items:start;}
@media(max-width:1024px){.cat-layout{grid-template-columns:1fr;}}

/* Table card */
.cat-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.cat-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#FAFBFF;}
.cat-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.cat-card-title i[data-feather]{width:15px;height:15px;stroke:#4F46E5;}

/* Table */
.cat-table{width:100%;border-collapse:collapse;}
.cat-table th{font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.cat-table td{padding:12px 14px;border-bottom:1px solid #F8FAFC;font-size:.84rem;color:#0F172A;vertical-align:middle;}
.cat-table tbody tr:last-child td{border-bottom:none;}
.cat-table tbody tr:hover{background:#FAFBFF;}
.cat-name{font-weight:700;font-size:.85rem;color:#0F172A;}
/* Toggle switch custom */
.cat-switch{position:relative;display:inline-block;width:36px;height:20px;}
.cat-switch input{opacity:0;width:0;height:0;position:absolute;}
.cat-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:20px;cursor:pointer;transition:background .2s;}
.cat-switch-slider::after{content:'';position:absolute;top:3px;left:3px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.cat-switch input:checked+.cat-switch-slider{background:#4F46E5;}
.cat-switch input:checked+.cat-switch-slider::after{transform:translateX(16px);}
/* Actions */
.cat-actions{display:flex;gap:5px;}
.cat-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.cat-act i[data-feather]{width:13px;height:13px;}
.cat-act-edit{background:#EEF2FF;color:#4F46E5;}.cat-act-edit:hover{background:#ddd6fe;}
.cat-act-del {background:#FEF2F2;color:#DC2626;}.cat-act-del:hover{background:#FECACA;}
/* Empty */
.cat-empty{text-align:center;padding:44px;color:#94A3B8;}
.cat-empty i[data-feather]{width:48px;height:48px;opacity:.2;display:block;margin:0 auto 12px;}

/* Form card */
.cat-form-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.cat-form-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;background:linear-gradient(135deg,#EEF2FF,#F5F3FF);display:flex;align-items:center;gap:8px;}
.cat-form-head-icon{width:28px;height:28px;border-radius:7px;background:#4F46E5;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.cat-form-head-icon i[data-feather]{width:13px;height:13px;stroke:#fff;}
.cat-form-head-title{font-size:.87rem;font-weight:700;color:#0F172A;}
.cat-form-body{padding:18px;}
.cat-label{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.cat-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:8px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;resize:vertical;}
.cat-input:focus{border-color:#4F46E5;box-shadow:0 0 0 3px rgba(79,70,229,.1);}
.cat-field{margin-bottom:14px;}
.cat-seo-divider{display:flex;align-items:center;gap:10px;margin:16px 0 14px;}
.cat-seo-divider span{font-size:.72rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;background:#EEF2FF;color:#4F46E5;padding:3px 10px;border-radius:20px;}
.cat-seo-divider::before,.cat-seo-divider::after{content:'';flex:1;height:1px;background:#E2E8F0;}
.cat-btn-row{display:flex;gap:8px;margin-top:6px;}
.cat-submit{flex:1;height:38px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:9px;font-size:.84rem;font-weight:700;cursor:pointer;transition:opacity .2s;}
.cat-submit:hover{opacity:.88;}
.cat-cancel{height:38px;padding:0 16px;background:#F1F5F9;color:#64748B;border:none;border-radius:9px;font-size:.84rem;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;}
.cat-cancel:hover{background:#E2E8F0;color:#374151;text-decoration:none;}

/* Alert */
.cat-alert{display:flex;align-items:center;gap:10px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;color:#065F46;}

@media(max-width:640px){.cat-page{padding:12px;}.cat-layout{grid-template-columns:1fr;}}
</style>

<div class="cat-page">

@if(session('success'))
<div class="cat-alert">
    <i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i>
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="cat-header">
    <div class="cat-header-left">
        <div class="cat-header-icon"><i data-feather="grid"></i></div>
        <div class="cat-header-text">
            <h1>{{ $page_title ?? 'Category' }} Management</h1>
            <p>Manage categories, SEO settings and visibility</p>
        </div>
    </div>
    <div class="cat-header-right">
        <form action="{{ route('category.index') }}" method="GET" class="cat-search">
            <input type="text" name="search" placeholder="Search categories…" value="{{ $search ?? '' }}">
            <button type="submit"><i data-feather="search"></i></button>
        </form>
    </div>
</div>

<div class="cat-layout">

    {{-- Categories Table --}}
    <div>
        <div class="cat-card">
            <div class="cat-card-head">
                <div class="cat-card-title"><i data-feather="list"></i> All Categories</div>
                <span style="font-size:.73rem;color:#64748B;">{{ $list->count() }} found</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="cat-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-center">Show Price</th>
                            <th class="text-center">On Home</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $i => $data)
                        <tr>
                            <td style="color:#94A3B8;font-size:.78rem;">{{ $i+1 }}</td>
                            <td>
                                <div class="cat-name">{{ $data->name }}</div>
                                @if($data->meta_title)
                                <div style="font-size:.7rem;color:#94A3B8;margin-top:2px;">{{ Str::limit($data->meta_title,40) }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <label class="cat-switch">
                                    <input type="checkbox" class="show_price" value="{{ $data->id }}" {{ $data->show_price=='1'?'checked':'' }}>
                                    <span class="cat-switch-slider"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <label class="cat-switch">
                                    <input type="checkbox" class="on_home" value="{{ $data->id }}" {{ $data->on_home=='1'?'checked':'' }}>
                                    <span class="cat-switch-slider"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <label class="cat-switch">
                                    <input type="checkbox" class="status_update" value="{{ $data->id }}" {{ $data->is_active==='active'?'checked':'' }}>
                                    <span class="cat-switch-slider"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <div class="cat-actions" style="justify-content:center;">
                                    <a href="{{ route('category.edit', $data->id) }}" class="cat-act cat-act-edit" title="Edit">
                                        <i data-feather="edit-2"></i>
                                    </a>
                                    @can('category-delete')
                                    <form action="{{ route('category.destroy', $data->id) }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete {{ addslashes($data->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="cat-act cat-act-del" title="Delete">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6">
                            <div class="cat-empty">
                                <i data-feather="grid"></i>
                                <p style="font-weight:700;color:#374151;margin:0 0 4px;">No categories found</p>
                                <p style="font-size:.82rem;margin:0;">Create your first category using the form →</p>
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create / Edit Form --}}
    <div>
        <div class="cat-form-card">
            <div class="cat-form-head">
                <div class="cat-form-head-icon">
                    <i data-feather="{{ isset($edit_data) ? 'edit-2' : 'plus' }}"></i>
                </div>
                <div class="cat-form-head-title">
                    {{ isset($edit_data) ? 'Update Category' : 'Create Category' }}
                </div>
            </div>
            <div class="cat-form-body">
                @isset($edit_data)
                <form method="POST" action="{{ route('category.update', $edit_data->id) }}" enctype="multipart/form-data">
                    @method('PUT')
                @else
                <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                @endisset
                @csrf

                <div class="cat-field">
                    <label class="cat-label">Category Name <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" class="cat-input" required placeholder="e.g. Boat Rides"
                           value="{{ $edit_data->name ?? old('name') }}">
                </div>

                <div class="cat-field">
                    <label class="cat-label">Terms & Conditions</label>
                    <textarea name="term_and_condition" id="editor" class="cat-input" rows="3"
                              placeholder="Enter any terms…">{{ $edit_data->term_and_condition ?? '' }}</textarea>
                </div>

                {{-- SEO Section --}}
                <div class="cat-seo-divider"><span>🔍 SEO Settings</span></div>

                <div class="cat-field">
                    <label class="cat-label">Meta Title</label>
                    <input type="text" name="meta_title" class="cat-input" placeholder="60 characters recommended"
                           value="{{ $edit_data->meta_title ?? old('meta_title') }}">
                </div>
                <div class="cat-field">
                    <label class="cat-label">Meta Keywords</label>
                    <input type="text" name="meta_keyword" class="cat-input" placeholder="keyword1, keyword2…"
                           value="{{ $edit_data->meta_keyword ?? old('meta_keyword') }}">
                </div>
                <div class="cat-field">
                    <label class="cat-label">Meta Description</label>
                    <textarea name="meta_description" class="cat-input" rows="2" placeholder="155 characters recommended">{{ $edit_data->meta_description ?? old('meta_description') }}</textarea>
                </div>

                <div class="cat-btn-row">
                    <button type="submit" class="cat-submit">
                        {{ isset($edit_data) ? 'Update Category' : 'Save Category' }}
                    </button>
                    @isset($edit_data)
                    <a href="{{ route('category.index') }}" class="cat-cancel">Cancel</a>
                    @endisset
                </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>

<script>
function showToast(icon, title) {
    Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true})
        .fire({icon:icon,title:title});
}
$(".status_update").change(function(){
    $.get("{{ route('category.show','') }}/"+$(this).val(), function(d){ showToast(d.res,d.message); });
});
$(".on_home").change(function(){
    $.get("{{ route('admin.update.on.home.status','') }}/"+$(this).val(), function(d){ showToast(d.res,d.message); });
});
$(".show_price").change(function(){
    $.get("{{ route('admin.update.show.price.status','') }}/"+$(this).val(), function(d){ showToast(d.res,d.message); });
});
document.addEventListener('DOMContentLoaded',function(){ feather.replace(); });
</script>
@endsection
