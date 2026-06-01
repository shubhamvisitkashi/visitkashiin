@extends('admin.layouts.app')
@section('content')
<style>
.si-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.si-header{background:linear-gradient(135deg,#064E3B,#065F46,#059669);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(5,150,105,.25);}
.si-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.si-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.si-header-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.si-header-icon i[data-feather]{width:20px;height:20px;stroke:#fff;}
.si-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.si-header-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.si-add-btn{position:relative;z-index:1;display:inline-flex;align-items:center;gap:6px;background:#fff;color:#059669;border:none;border-radius:10px;padding:9px 16px;font-size:.82rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:all .2s;white-space:nowrap;}
.si-add-btn:hover{background:#f0fdf4;color:#047857;text-decoration:none;transform:translateY(-1px);}
.si-add-btn i[data-feather]{width:14px;height:14px;}
/* Stats */
.si-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px;}
.si-stat{background:#fff;border-radius:12px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 16px;display:flex;align-items:center;gap:12px;}
.si-stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.si-stat-icon i[data-feather]{width:18px;height:18px;stroke:#fff;}
.si-stat-val{font-size:1.3rem;font-weight:900;color:#0F172A;line-height:1;}
.si-stat-lbl{font-size:.68rem;font-weight:600;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-top:2px;}
/* Filter */
.si-filter{background:#fff;border-radius:12px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:13px 16px;margin-bottom:14px;display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;}
.si-filter select,.si-filter input{border:1.5px solid #E2E8F0;border-radius:9px;padding:7px 11px;font-size:.82rem;color:#0F172A;background:#FAFBFF;height:36px;outline:none;transition:border-color .15s;flex:1;min-width:140px;}
.si-filter select:focus,.si-filter input:focus{border-color:#059669;}
.si-filter-btn{height:36px;padding:0 14px;border-radius:9px;border:none;font-size:.8rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;flex-shrink:0;}
.si-filter-btn.p{background:#059669;color:#fff;}.si-filter-btn.p:hover{background:#047857;}
.si-filter-btn.g{background:#F1F5F9;color:#64748B;text-decoration:none;}.si-filter-btn.g:hover{background:#E2E8F0;color:#374151;text-decoration:none;}
.si-filter-btn i[data-feather]{width:13px;height:13px;}
/* Card */
.si-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.si-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#F0FDF4;}
.si-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}
.si-card-title i[data-feather]{width:15px;height:15px;stroke:#059669;}
/* Table */
.si-table{width:100%;border-collapse:collapse;}
.si-table th{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748B;padding:10px 12px;border-bottom:1.5px solid #F0F0F5;background:#FAFAFA;white-space:nowrap;}
.si-table td{padding:11px 12px;border-bottom:1px solid #F8FAFC;font-size:.82rem;color:#0F172A;vertical-align:middle;}
.si-table tbody tr:last-child td{border-bottom:none;}
.si-table tbody tr:hover{background:#F0FDF4;}
/* Provider cell */
.si-prov{display:flex;align-items:center;gap:9px;}
.si-prov-av{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#059669,#047857);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0;}
.si-prov-name{font-weight:700;font-size:.83rem;}
.si-prov-type{font-size:.7rem;color:#64748B;}
/* Badges */
.si-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 9px;border-radius:20px;font-size:.68rem;font-weight:700;}
.si-badge-type  {background:#FAF5FF;color:#6D28D9;border:1px solid #DDD6FE;}
.si-badge-cap   {background:#F0F9FF;color:#0369A1;border:1px solid #BAE6FD;}
.si-badge-on    {background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0;}
.si-badge-off   {background:#F1F5F9;color:#64748B;border:1px solid #E2E8F0;}
.si-badge-bk    {background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}
/* Price */
.si-price{font-weight:800;font-size:.84rem;}
.si-price-vendor{color:#DC2626;}
.si-price-base  {color:#059669;}
/* Actions */
.si-actions{display:flex;gap:5px;}
.si-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.si-act i[data-feather]{width:13px;height:13px;}
.si-act-edit{background:#EEF2FF;color:#4F46E5;}.si-act-edit:hover{background:#ddd6fe;}
.si-act-del {background:#FEF2F2;color:#DC2626;}.si-act-del:hover{background:#FECACA;}
.si-empty{text-align:center;padding:44px;color:#94A3B8;}
.si-pag{padding:12px 18px;border-top:1px solid #F1F5F9;}
.si-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:14px;font-size:.83rem;font-weight:600;}
.si-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.si-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
@media(max-width:900px){.si-stats{grid-template-columns:repeat(2,1fr);}}
@media(max-width:640px){.si-page{padding:12px;}.si-stats{grid-template-columns:1fr 1fr;}.si-filter{gap:7px;}}
</style>

<div class="si-page">

@if(session('success'))<div class="si-alert si-alert-ok"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="si-alert si-alert-err"><i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#EF4444;"></i> {{ session('error') }}</div>@endif

<div class="si-header">
    <div class="si-header-left">
        <div class="si-header-icon"><i data-feather="grid"></i></div>
        <div class="si-header-text">
            <h1>Service Items</h1>
            <p>Manage all service items and inventory across providers</p>
        </div>
    </div>
    <a href="{{ route('service-items.create') }}" class="si-add-btn">
        <i data-feather="plus"></i> Add Service Item
    </a>
</div>

<!-- Stats -->
<div class="si-stats">
    <div class="si-stat">
        <div class="si-stat-icon" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);"><i data-feather="package"></i></div>
        <div><div class="si-stat-val">{{ $items->total() }}</div><div class="si-stat-lbl">Total Items</div></div>
    </div>
    <div class="si-stat">
        <div class="si-stat-icon" style="background:linear-gradient(135deg,#059669,#10B981);"><i data-feather="check-circle"></i></div>
        <div><div class="si-stat-val">{{ $items->where('is_active',true)->count() }}</div><div class="si-stat-lbl">Active</div></div>
    </div>
    <div class="si-stat">
        <div class="si-stat-icon" style="background:linear-gradient(135deg,#0369A1,#0EA5E9);"><i data-feather="users"></i></div>
        <div><div class="si-stat-val">{{ $providers->count() }}</div><div class="si-stat-lbl">Providers</div></div>
    </div>
    <div class="si-stat">
        <div class="si-stat-icon" style="background:linear-gradient(135deg,#B45309,#F59E0B);"><i data-feather="briefcase"></i></div>
        <div><div class="si-stat-val">{{ $items->sum('booking_services_count') }}</div><div class="si-stat-lbl">Bookings</div></div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('service-items.index') }}" class="si-filter">
    <select name="service_provider_id">
        <option value="">All Providers</option>
        @foreach($providers as $p)
        <option value="{{ $p->id }}" {{ request('service_provider_id')==$p->id?'selected':'' }}>{{ $p->name }}</option>
        @endforeach
    </select>
    <select name="service_template_id">
        <option value="">All Templates</option>
        @foreach($serviceTemplates as $t)
        <option value="{{ $t->id }}" {{ request('service_template_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>
        @endforeach
    </select>
    <input type="text" name="search" placeholder="🔍 Search items…" value="{{ request('search') }}">
    <button type="submit" class="si-filter-btn p"><i data-feather="search"></i> Filter</button>
    <a href="{{ route('service-items.index') }}" class="si-filter-btn g"><i data-feather="x"></i> Clear</a>
</form>

<!-- Table -->
<div class="si-card">
    <div class="si-card-head">
        <div class="si-card-title"><i data-feather="grid"></i> All Service Items</div>
        <span style="font-size:.73rem;color:#64748B;">{{ $items->total() }} items</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="si-table">
            <thead>
                <tr>
                    <th>Provider</th>
                    <th>Type</th>
                    <th>Template</th>
                    <th class="text-end">Vendor Cost</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        @if($item->serviceProvider)
                        <div class="si-prov">
                            <div class="si-prov-av">{{ strtoupper(substr($item->serviceProvider->name,0,2)) }}</div>
                            <div>
                                <div class="si-prov-name">{{ $item->serviceProvider->name }}</div>
                                <div class="si-prov-type">{{ ucfirst($item->serviceProvider->type) }}</div>
                            </div>
                        </div>
                        @else<span style="color:#94A3B8;">—</span>@endif
                    </td>
                    <td>
                        @if($item->serviceTemplate && $item->serviceTemplate->serviceType)
                        <span class="si-badge si-badge-type">{{ $item->serviceTemplate->serviceType->name }}</span>
                        @else<span style="color:#94A3B8;font-size:.75rem;">—</span>@endif
                    </td>
                    <td>
                        @if($item->serviceTemplate)
                        <div style="font-weight:700;font-size:.83rem;">{{ $item->serviceTemplate->name }}</div>
                        @if($item->serviceTemplate->description)<div style="font-size:.71rem;color:#64748B;">{{ Str::limit($item->serviceTemplate->description,35) }}</div>@endif
                        @else<span style="color:#94A3B8;">—</span>@endif
                    </td>
                    <td class="text-end"><span class="si-price si-price-vendor">₹{{ number_format($item->vendor_cost,0) }}</span></td>
                    <td class="text-center">
                        <span class="si-badge {{ $item->is_active?'si-badge-on':'si-badge-off' }}">
                            {{ $item->is_active?'Active':'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="si-actions" style="justify-content:center;">
                            <a href="{{ route('service-items.edit',$item->service_provider_id) }}" class="si-act si-act-edit" title="Edit">
                                <i data-feather="edit-2"></i>
                            </a>
                            <form action="{{ route('service-items.destroy',$item->id) }}" method="POST" id="delete_form_{{ $item->id }}" style="display:contents;">
                                @csrf @method('DELETE')
                                @php $delName = $item->serviceTemplate->name ?? 'this item'; @endphp
                                <button type="button" class="si-act si-act-del" onclick="confirmDeleteItem({{ $item->id }},'{{ addslashes($delName) }}')" title="Delete">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">
                    <div class="si-empty">
                        <i data-feather="inbox" style="width:48px;height:48px;opacity:.2;display:block;margin:0 auto 12px;stroke:#94A3B8;"></i>
                        <p style="font-weight:700;color:#374151;margin:0 0 5px;">No service items found</p>
                        <p style="font-size:.82rem;margin:0 0 14px;">Add your first service item to get started</p>
                        <a href="{{ route('service-items.create') }}" class="si-add-btn" style="margin:0 auto;">
                            <i data-feather="plus"></i> Add Service Item
                        </a>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="si-pag">{{ $items->links() }}</div>
    @endif
</div>

</div>

    @push('scripts')
        <script>
            // Initialize Feather Icons
            feather.replace();

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Confirm delete service item
            function confirmDeleteItem(id, name) {
                Swal.fire({
                    title: 'Delete Service Item?',
                    html: `<p>Are you sure you want to delete <strong>${name}</strong>?</p><p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete_form_' + id).submit();
                    }
                });
            }
        </script>
    @endpush
@endsection
