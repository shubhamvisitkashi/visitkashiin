@extends('admin.layouts.app')
@section('content')
<style>
.pr-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.pr-header{background:linear-gradient(135deg,#7C2D12,#EA580C,#F97316);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(234,88,12,.28);}
.pr-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.pr-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.pr-header-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pr-header-icon i[data-feather]{width:20px;height:20px;stroke:#fff;}
.pr-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.pr-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.pr-header-right{position:relative;z-index:1;display:flex;align-items:center;gap:8px;}
.pr-add-btn{display:inline-flex;align-items:center;gap:6px;background:#fff;color:#EA580C;border:none;border-radius:10px;padding:9px 16px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,.12);}
.pr-add-btn:hover{background:#fff7ed;color:#C2410C;text-decoration:none;}
.pr-add-btn i[data-feather]{width:14px;height:14px;}
/* Filter bar */
.pr-filter{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:12px 16px;margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
.pr-filter select,.pr-filter input{border:1.5px solid #E2E8F0;border-radius:9px;padding:7px 11px;font-size:.82rem;color:#0F172A;background:#FAFBFF;height:36px;outline:none;transition:border-color .15s;flex:1;min-width:150px;}
.pr-filter select:focus,.pr-filter input:focus{border-color:#EA580C;}
.pr-filter-btn{height:36px;padding:0 16px;border-radius:9px;border:none;background:#EA580C;color:#fff;font-size:.82rem;font-weight:700;cursor:pointer;white-space:nowrap;display:inline-flex;align-items:center;gap:5px;flex-shrink:0;}
.pr-filter-btn i[data-feather]{width:13px;height:13px;}
/* Card */
.pr-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.pr-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#FFF7ED;}
.pr-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.pr-card-title i[data-feather]{width:15px;height:15px;stroke:#EA580C;}
/* Table */
.pr-table{width:100%;border-collapse:collapse;}
.pr-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 12px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.pr-table td{padding:11px 12px;border-bottom:1px solid #F8FAFC;font-size:.83rem;color:#0F172A;vertical-align:middle;}
.pr-table tbody tr:last-child td{border-bottom:none;}
.pr-table tbody tr:hover{background:#FFF7ED;}
.pr-cat-badge{display:inline-flex;background:#FFF7ED;color:#C2410C;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:20px;border:1px solid #FED7AA;}
.pr-sub-badge{display:inline-flex;background:#F0F9FF;color:#0369A1;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:20px;border:1px solid #BAE6FD;}
.pr-name{font-weight:700;font-size:.84rem;}
.pr-switch{position:relative;display:inline-block;width:36px;height:20px;}
.pr-switch input{opacity:0;width:0;height:0;position:absolute;}
.pr-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:20px;cursor:pointer;transition:background .2s;}
.pr-switch-slider::after{content:'';position:absolute;top:3px;left:3px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.pr-switch input:checked+.pr-switch-slider{background:#EA580C;}
.pr-switch input:checked+.pr-switch-slider::after{transform:translateX(16px);}
.pr-actions{display:flex;gap:5px;justify-content:center;}
.pr-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.pr-act i[data-feather]{width:13px;height:13px;}
.pr-act-edit{background:#EEF2FF;color:#4F46E5;}.pr-act-edit:hover{background:#ddd6fe;}
.pr-act-del {background:#FEF2F2;color:#DC2626;}.pr-act-del:hover{background:#FECACA;}
.pr-empty{text-align:center;padding:44px;color:#94A3B8;}
.pr-empty i[data-feather]{width:48px;height:48px;opacity:.2;display:block;margin:0 auto 12px;}
/* Pagination */
.pr-pag{padding:12px 18px;border-top:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
.pr-pag-info{font-size:.75rem;color:#64748B;font-weight:600;}
.pr-alert{display:flex;align-items:center;gap:10px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;color:#065F46;}
@media(max-width:640px){.pr-page{padding:12px;}.pr-filter{gap:7px;}.pr-filter select,.pr-filter input{min-width:120px;}}
</style>

<div class="pr-page">

@if(session('success'))
<div class="pr-alert"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i> {{ session('success') }}</div>
@endif

{{-- Header --}}
<div class="pr-header">
    <div class="pr-header-left">
        <div class="pr-header-icon"><i data-feather="package"></i></div>
        <div class="pr-header-text">
            <h1>{{ $page_title ?? 'Products' }}</h1>
            <p>Manage all products, packages & listings</p>
        </div>
    </div>
    <div class="pr-header-right">
        @can('package-create')
        <a href="{{ route('product.create') }}" class="pr-add-btn">
            <i data-feather="plus"></i> Add Product
        </a>
        @endcan
    </div>
</div>

{{-- Filter bar --}}
<form action="{{ route('product.index') }}" method="GET" class="pr-filter">
    <select name="search_category" class="js-example-basic-single">
        <option value="">All Categories</option>
        @foreach(App\Models\Admin\Category::all() as $cat)
        <option value="{{ $cat->id }}" {{ request('search_category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <select name="search_sub_category" class="js-example-basic-single">
        <option value="">All Sub Categories</option>
        @foreach(App\Models\Admin\SubCategory::all() as $sub)
        <option value="{{ $sub->id }}" {{ request('search_sub_category')==$sub->id?'selected':'' }}>{{ $sub->name }}</option>
        @endforeach
    </select>
    <input type="text" name="search_key" placeholder="🔍 Search products…" value="{{ $search_key }}">
    <button type="submit" class="pr-filter-btn"><i data-feather="search"></i> Filter</button>
</form>

{{-- Table --}}
<div class="pr-card">
    <div class="pr-card-head">
        <div class="pr-card-title"><i data-feather="package"></i> All Products</div>
        <span style="font-size:.73rem;color:#64748B;">{{ $list->total() }} products</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="pr-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Product Name</th>
                    <th class="text-center">On Home</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($list as $key => $data)
                <tr>
                    <td style="color:#94A3B8;font-size:.78rem;">{{ $key + 1 + ($list->currentPage()-1)*$list->perPage() }}</td>
                    <td><span class="pr-cat-badge">{{ $data->category->name }}</span></td>
                    <td>
                        @if(optional($data->subCategory)->name)
                        <span class="pr-sub-badge">{{ $data->subCategory->name }}</span>
                        @else
                        <span style="color:#94A3B8;font-size:.75rem;">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="pr-name">{{ $data->name }}</div>
                        @if($data->discounted_price > 0)
                        <div style="font-size:.71rem;color:#10B981;font-weight:600;margin-top:2px;">₹{{ number_format($data->discounted_price,0) }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <label class="pr-switch">
                            <input type="checkbox" class="on_home" value="{{ $data->id }}" {{ $data->on_home=='1'?'checked':'' }}>
                            <span class="pr-switch-slider"></span>
                        </label>
                    </td>
                    <td class="text-center">
                        <label class="pr-switch">
                            <input type="checkbox" class="status_update" value="{{ $data->id }}" {{ $data->is_active==='active'?'checked':'' }}>
                            <span class="pr-switch-slider"></span>
                        </label>
                    </td>
                    <td>
                        <div class="pr-actions">
                            @can('package-edit')
                            <a href="{{ route('product.edit', $data->id) }}" class="pr-act pr-act-edit" title="Edit">
                                <i data-feather="edit-2"></i>
                            </a>
                            @endcan
                            @can('package-delete')
                            <form action="{{ route('product.destroy', $data->id) }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete {{ addslashes($data->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="pr-act pr-act-del" title="Delete"><i data-feather="trash-2"></i></button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <div class="pr-empty">
                        <i data-feather="package"></i>
                        <p style="font-weight:700;color:#374151;margin:0 0 4px;">No products found</p>
                        <p style="font-size:.82rem;margin:0;">Try adjusting your filters or add a new product</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($list->hasPages())
    <div class="pr-pag">
        <div class="pr-pag-info">
            Showing {{ ($list->currentPage()-1)*$list->perPage()+1 }}–{{ ($list->currentPage()-1)*$list->perPage()+$list->count() }} of {{ $list->total() }}
        </div>
        {!! $list->appends(['search_key'=>$search_key])->links() !!}
    </div>
    @endif
</div>

</div>

<script>
function toast(d){ Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true}).fire({icon:d.res,title:d.message}); }
$(".status_update").change(function(){ $.get("{{ route('product.statusUpdate','') }}/"+$(this).val(),toast); });
$(".on_home").change(function(){ $.get("{{ route('admin.product.on.home.status.update','') }}/"+$(this).val(),toast); });
document.addEventListener('DOMContentLoaded',function(){feather.replace();});
</script>
@endsection
