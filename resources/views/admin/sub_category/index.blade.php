@extends('admin.layouts.app')
@section('content')
<style>
.sc-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.sc-header{background:linear-gradient(135deg,#064E3B,#10B981,#34D399);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(16,185,129,.25);}
.sc-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.sc-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.sc-header-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sc-header-icon i[data-feather]{width:20px;height:20px;stroke:#fff;}
.sc-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.sc-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.sc-search{display:flex;align-items:center;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);border-radius:10px;overflow:hidden;height:36px;position:relative;z-index:1;}
.sc-search input{background:transparent;border:none;outline:none;color:#fff;padding:0 12px;font-size:.82rem;width:180px;}
.sc-search input::placeholder{color:rgba(255,255,255,.55);}
.sc-search button{background:rgba(255,255,255,.15);border:none;border-left:1px solid rgba(255,255,255,.2);height:100%;padding:0 12px;cursor:pointer;display:flex;align-items:center;}
.sc-search button i[data-feather]{width:14px;height:14px;stroke:#fff;}
.sc-layout{display:grid;grid-template-columns:1fr 400px;gap:20px;align-items:start;}
@media(max-width:1024px){.sc-layout{grid-template-columns:1fr;}}
.sc-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.sc-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#F0FDF4;}
.sc-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.sc-card-title i[data-feather]{width:15px;height:15px;stroke:#10B981;}
.sc-table{width:100%;border-collapse:collapse;}
.sc-table th{font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 14px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.sc-table td{padding:12px 14px;border-bottom:1px solid #F8FAFC;font-size:.84rem;color:#0F172A;vertical-align:middle;}
.sc-table tbody tr:last-child td{border-bottom:none;}
.sc-table tbody tr:hover{background:#F0FDF4;}
.sc-cat-badge{display:inline-flex;align-items:center;background:#F0FDF4;color:#065F46;font-size:.7rem;font-weight:700;padding:2px 9px;border-radius:20px;border:1px solid #A7F3D0;}
.sc-switch{position:relative;display:inline-block;width:36px;height:20px;}
.sc-switch input{opacity:0;width:0;height:0;position:absolute;}
.sc-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:20px;cursor:pointer;transition:background .2s;}
.sc-switch-slider::after{content:'';position:absolute;top:3px;left:3px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.sc-switch input:checked+.sc-switch-slider{background:#10B981;}
.sc-switch input:checked+.sc-switch-slider::after{transform:translateX(16px);}
.sc-actions{display:flex;gap:5px;justify-content:center;}
.sc-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.sc-act i[data-feather]{width:13px;height:13px;}
.sc-act-edit{background:#EEF2FF;color:#4F46E5;}.sc-act-edit:hover{background:#ddd6fe;}
.sc-act-del {background:#FEF2F2;color:#DC2626;}.sc-act-del:hover{background:#FECACA;}
.sc-empty{text-align:center;padding:44px;color:#94A3B8;}
.sc-empty i[data-feather]{width:48px;height:48px;opacity:.2;display:block;margin:0 auto 12px;}
.sc-form-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.sc-form-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;background:linear-gradient(135deg,#F0FDF4,#ECFDF5);display:flex;align-items:center;gap:8px;}
.sc-form-head-icon{width:28px;height:28px;border-radius:7px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sc-form-head-icon i[data-feather]{width:13px;height:13px;stroke:#fff;}
.sc-form-head-title{font-size:.87rem;font-weight:700;color:#0F172A;}
.sc-form-body{padding:18px;}
.sc-label{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.sc-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:8px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.sc-input:focus{border-color:#10B981;box-shadow:0 0 0 3px rgba(16,185,129,.1);}
.sc-field{margin-bottom:14px;}
.sc-seo{display:flex;align-items:center;gap:10px;margin:16px 0 14px;}
.sc-seo span{font-size:.72rem;font-weight:700;background:#F0FDF4;color:#059669;padding:3px 10px;border-radius:20px;white-space:nowrap;}
.sc-seo::before,.sc-seo::after{content:'';flex:1;height:1px;background:#E2E8F0;}
.sc-btn-row{display:flex;gap:8px;margin-top:6px;}
.sc-submit{flex:1;height:38px;background:linear-gradient(135deg,#10B981,#059669);color:#fff;border:none;border-radius:9px;font-size:.84rem;font-weight:700;cursor:pointer;transition:opacity .2s;}
.sc-submit:hover{opacity:.88;}
.sc-cancel{height:38px;padding:0 16px;background:#F1F5F9;color:#64748B;border:none;border-radius:9px;font-size:.84rem;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;}
.sc-cancel:hover{background:#E2E8F0;color:#374151;text-decoration:none;}
.sc-alert{display:flex;align-items:center;gap:10px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;color:#065F46;}
@media(max-width:640px){.sc-page{padding:12px;}.sc-layout{grid-template-columns:1fr;}}
</style>

<div class="sc-page">

@if(session('success'))
<div class="sc-alert"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i> {{ session('success') }}</div>
@endif

<div class="sc-header">
    <div class="sc-header-left">
        <div class="sc-header-icon"><i data-feather="tag"></i></div>
        <div class="sc-header-text">
            <h1>{{ $page_title ?? 'Sub Category' }} Management</h1>
            <p>Manage sub-categories and their SEO settings</p>
        </div>
    </div>
    <form action="{{ route('sub-category.index') }}" method="GET" class="sc-search">
        <input type="text" name="search" placeholder="Search…" value="{{ $search ?? '' }}">
        <button type="submit"><i data-feather="search"></i></button>
    </form>
</div>

<div class="sc-layout">
    {{-- Table --}}
    <div>
        <div class="sc-card">
            <div class="sc-card-head">
                <div class="sc-card-title"><i data-feather="list"></i> All Sub Categories</div>
                <span style="font-size:.73rem;color:#64748B;">{{ $list->count() }} found</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="sc-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $i => $data)
                        <tr>
                            <td style="color:#94A3B8;font-size:.78rem;">{{ $i+1 }}</td>
                            <td><span class="sc-cat-badge">{{ $data->category->name }}</span></td>
                            <td style="font-weight:700;">{{ $data->name }}</td>
                            <td class="text-center">
                                <label class="sc-switch">
                                    <input type="checkbox" class="status_update" value="{{ $data->id }}" {{ $data->is_active==='active'?'checked':'' }}>
                                    <span class="sc-switch-slider"></span>
                                </label>
                            </td>
                            <td>
                                <div class="sc-actions">
                                    @can('sub_category-edit')
                                    <a href="{{ route('sub-category.edit', $data->id) }}" class="sc-act sc-act-edit" title="Edit">
                                        <i data-feather="edit-2"></i>
                                    </a>
                                    @endcan
                                    @can('sub_category-delete')
                                    <form action="{{ route('sub-category.destroy', $data->id) }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete {{ addslashes($data->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="sc-act sc-act-del" title="Delete"><i data-feather="trash-2"></i></button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5">
                            <div class="sc-empty">
                                <i data-feather="tag"></i>
                                <p style="font-weight:700;color:#374151;margin:0 0 4px;">No sub-categories found</p>
                                <p style="font-size:.82rem;margin:0;">Create your first sub-category using the form →</p>
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div>
        <div class="sc-form-card">
            <div class="sc-form-head">
                <div class="sc-form-head-icon"><i data-feather="{{ isset($edit_data)?'edit-2':'plus' }}"></i></div>
                <div class="sc-form-head-title">{{ isset($edit_data)?'Update':'Create' }} Sub Category</div>
            </div>
            <div class="sc-form-body">
                @isset($edit_data)
                <form method="POST" action="{{ route('sub-category.update', $edit_data->id) }}" enctype="multipart/form-data">
                    @method('PUT')
                @else
                <form method="POST" action="{{ route('sub-category.store') }}" enctype="multipart/form-data">
                @endisset
                @csrf
                <div class="sc-field">
                    <label class="sc-label">Category <span style="color:#EF4444;">*</span></label>
                    <select name="category_id" class="sc-input js-example-basic-single" required>
                        <option value="" disabled selected>— Select Category —</option>
                        @foreach($category_list as $cat)
                        <option value="{{ $cat->id }}" {{ isset($edit_data)&&$edit_data->category_id==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sc-field">
                    <label class="sc-label">Sub Category Name <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" class="sc-input" required placeholder="e.g. Motor Boat" value="{{ $edit_data->name ?? '' }}">
                </div>
                <div class="sc-seo"><span>🔍 SEO</span></div>
                <div class="sc-field">
                    <label class="sc-label">Meta Title</label>
                    <input type="text" name="meta_title" class="sc-input" placeholder="60 chars" value="{{ $edit_data->meta_title ?? '' }}">
                </div>
                <div class="sc-field">
                    <label class="sc-label">Meta Keywords</label>
                    <input type="text" name="meta_keyword" class="sc-input" placeholder="keyword1, keyword2" value="{{ $edit_data->meta_keyword ?? '' }}">
                </div>
                <div class="sc-field">
                    <label class="sc-label">Meta Description</label>
                    <textarea name="meta_description" class="sc-input" rows="2" placeholder="155 chars">{{ $edit_data->meta_description ?? '' }}</textarea>
                </div>
                <div class="sc-btn-row">
                    <button type="submit" class="sc-submit">{{ isset($edit_data)?'Update':'Save' }} Sub Category</button>
                    @isset($edit_data)
                    <a href="{{ route('sub-category.index') }}" class="sc-cancel">Cancel</a>
                    @endisset
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
$(".status_update").change(function(){
    $.get("{{ route('sub-category.show','') }}/"+$(this).val(),function(d){
        Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true}).fire({icon:d.res,title:d.message});
    });
});
document.addEventListener('DOMContentLoaded',function(){feather.replace();});
</script>
@endsection
