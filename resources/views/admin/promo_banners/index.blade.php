@extends('admin.layouts.app')
@section('content')
<style>
.pb-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.pb-header{background:linear-gradient(135deg,#BE185D,#DB2777,#EC4899);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(219,39,119,.28);}
.pb-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.pb-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:13px;}
.pb-header-icon{width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pb-header-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.pb-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.pb-header-text p{color:rgba(255,255,255,.7);font-size:.75rem;margin:0;}
.pb-layout{display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;}
@media(max-width:1024px){.pb-layout{grid-template-columns:1fr;}}

/* Position groups */
.pb-pos-group{margin-bottom:20px;}
.pb-pos-label{font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748B;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:6px 14px;margin-bottom:10px;display:inline-block;}
.pb-card{background:#fff;border-radius:12px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;display:flex;gap:0;margin-bottom:8px;}
.pb-thumb{width:120px;height:72px;flex-shrink:0;background:#F1F5F9;overflow:hidden;}
.pb-thumb img{width:100%;height:100%;object-fit:cover;display:block;}
.pb-thumb-ph{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#CBD5E1;}
.pb-info{padding:10px 14px;flex:1;display:flex;flex-direction:column;justify-content:space-between;min-width:0;}
.pb-title{font-size:.84rem;font-weight:700;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.pb-sub{font-size:.72rem;color:#64748B;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.pb-actions{display:flex;gap:5px;margin-top:8px;flex-wrap:wrap;align-items:center;}
.pb-toggle-on {background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0;border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:700;cursor:pointer;}
.pb-toggle-off{background:#F1F5F9;color:#64748B;border:1px solid #E2E8F0;border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:700;cursor:pointer;}
.pb-del{background:#FEF2F2;border:none;color:#DC2626;border-radius:7px;width:26px;height:26px;cursor:pointer;display:flex;align-items:center;justify-content:center;}
.pb-del i[data-feather]{width:11px;height:11px;}
.pb-empty{text-align:center;padding:32px;background:#fff;border-radius:12px;border:1px solid #E2E8F0;color:#94A3B8;font-size:.82rem;}

/* Edit button */
.pb-edit{background:#EEF2FF;border:none;color:#4F46E5;border-radius:7px;width:26px;height:26px;cursor:pointer;display:flex;align-items:center;justify-content:center;}
.pb-edit i[data-feather]{width:11px;height:11px;}
/* Form card */
.pb-form-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.pb-form-head{padding:14px 18px;border-bottom:1px solid #F1F5F9;background:linear-gradient(135deg,#FDF2F8,#FCE7F3);display:flex;align-items:center;gap:9px;}
.pb-form-head-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#BE185D,#EC4899);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pb-form-head-icon i[data-feather]{width:14px;height:14px;stroke:#fff;}
.pb-form-head-title{font-size:.88rem;font-weight:800;color:#0F172A;}
.pb-form-body{padding:18px;}
.pb-label{font-size:.69rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;display:block;}
.pb-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:9px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.pb-input:focus{border-color:#DB2777;box-shadow:0 0 0 3px rgba(219,39,119,.1);}
.pb-field{margin-bottom:12px;}
.pb-submit{width:100%;height:40px;background:linear-gradient(135deg,#BE185D,#EC4899);color:#fff;border:none;border-radius:9px;font-size:.85rem;font-weight:700;cursor:pointer;transition:opacity .2s;}
.pb-submit:hover{opacity:.88;}
.pb-cancel{width:100%;height:36px;background:#F1F5F9;color:#64748B;border:1.5px solid #E2E8F0;border-radius:9px;font-size:.82rem;font-weight:700;cursor:pointer;margin-top:6px;}
.pb-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.pb-alert-ok{background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
/* Edit mode indicator */
.pb-editing-badge{background:#EEF2FF;color:#4F46E5;border:1px solid #C7D2FE;border-radius:20px;padding:3px 10px;font-size:.66rem;font-weight:700;margin-left:6px;}
.pb-cur-img{display:flex;align-items:center;gap:8px;background:#F0F9FF;border:1px solid #BAE6FD;border-radius:8px;padding:7px 10px;margin-top:6px;}
.pb-cur-img img{width:48px;height:32px;object-fit:cover;border-radius:5px;border:1px solid #E2E8F0;}
.pb-cur-img span{font-size:.7rem;color:#0369A1;font-weight:600;}
</style>

<div class="pb-page">

@if(session('success'))
<div class="pb-alert pb-alert-ok"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i> {{ session('success') }}</div>
@endif

<div class="pb-header">
    <div class="pb-header-left">
        <div class="pb-header-icon"><i data-feather="image"></i></div>
        <div class="pb-header-text">
            <h1>Promo Banners</h1>
            <p>Add promotional banners at different positions on the website</p>
        </div>
    </div>
    <span style="position:relative;z-index:1;background:rgba(255,255,255,.15);color:#fff;font-size:.75rem;font-weight:700;padding:5px 14px;border-radius:20px;border:1px solid rgba(255,255,255,.25);">{{ $banners->count() }} banners</span>
</div>

<div class="pb-layout">

    {{-- Banners grouped by position --}}
    <div>
        @if($banners->isEmpty())
        <div class="pb-empty">
            <div style="font-size:2rem;opacity:.3;margin-bottom:8px;">🎯</div>
            <p style="font-weight:700;color:#374151;margin:0 0 4px;">No promo banners yet</p>
            <p style="margin:0;">Add your first banner using the form →</p>
        </div>
        @else
        @foreach(\App\Models\PromoBanner::$positions as $posKey => $posLabel)
        @php $group = $banners->where('position', $posKey); @endphp
        @if($group->isNotEmpty())
        <div class="pb-pos-group">
            <div class="pb-pos-label">📍 {{ $posLabel }}</div>
            @foreach($group as $b)
            <div class="pb-card">
                <div class="pb-thumb">
                    @if($b->image)
                    <img src="{{ $b->image_url }}" alt="">
                    @else
                    <div class="pb-thumb-ph">🖼</div>
                    @endif
                </div>
                <div class="pb-info">
                    <div>
                        <div class="pb-title">{{ $b->title ?: '(No title)' }}</div>
                        @if($b->subtitle)<div class="pb-sub">{{ Str::limit($b->subtitle,60) }}</div>@endif
                        @if($b->link)<div style="font-size:.68rem;color:#0369A1;margin-top:2px;">🔗 {{ Str::limit($b->link,40) }}</div>@endif
                    </div>
                    <div class="pb-actions">
                        <form action="{{ route('promo-banners.toggle', $b->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="{{ $b->is_active ? 'pb-toggle-on' : 'pb-toggle-off' }}">
                                {{ $b->is_active ? '● Active' : '○ Off' }}
                            </button>
                        </form>
                        <button type="button" class="pb-edit" title="Edit"
                            onclick="pbEdit({{ $b->id }},'{{ addslashes($b->title) }}','{{ addslashes($b->subtitle) }}','{{ addslashes($b->link) }}','{{ $b->position }}','{{ $b->sort_order }}',{{ $b->is_active ? 'true' : 'false' }},'{{ $b->image_url }}')">
                            <i data-feather="edit-2"></i>
                        </button>
                        <form action="{{ route('promo-banners.destroy', $b->id) }}" method="POST"
                              onsubmit="return confirm('Delete this banner?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="pb-del"><i data-feather="trash-2"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endforeach
        @endif
    </div>

    {{-- Add / Edit Banner Form --}}
    <div>
        <div class="pb-form-card">
            <div class="pb-form-head">
                <div class="pb-form-head-icon" id="pbFormIcon"><i data-feather="plus"></i></div>
                <div class="pb-form-head-title" id="pbFormTitle">Add Promo Banner</div>
            </div>
            <div class="pb-form-body">
                <form id="pbForm" action="{{ route('promo-banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="pbMethod" value="POST">

                    <div class="pb-field">
                        <label class="pb-label">Banner Image <span id="pbImgReq" style="color:#EF4444;">*</span></label>
                        <input type="file" name="image" id="pbImageInput" class="pb-input" accept="image/*" style="padding:5px 10px;cursor:pointer;">
                        <div style="font-size:.68rem;color:#94A3B8;margin-top:4px;">JPG/PNG/WebP · Recommended: 1200×300px</div>
                        {{-- Current image preview (edit mode) --}}
                        <div id="pbCurImg" class="pb-cur-img" style="display:none;">
                            <img id="pbCurImgEl" src="" alt="">
                            <span>Current image — upload new to replace</span>
                        </div>
                    </div>

                    <div class="pb-field">
                        <label class="pb-label">Title</label>
                        <input type="text" name="title" id="pbTitle" class="pb-input" placeholder="e.g. Special Summer Offer!">
                    </div>

                    <div class="pb-field">
                        <label class="pb-label">Subtitle</label>
                        <input type="text" name="subtitle" id="pbSubtitle" class="pb-input" placeholder="e.g. Book before June 30 and save 20%">
                    </div>

                    <div class="pb-field">
                        <label class="pb-label">Link URL</label>
                        <input type="text" name="link" id="pbLink" class="pb-input" placeholder="https://... or /packages">
                    </div>

                    <div class="pb-field">
                        <label class="pb-label">Position on Website <span style="color:#EF4444;">*</span></label>
                        <select name="position" id="pbPosition" class="pb-input" required>
                            @foreach(\App\Models\PromoBanner::$positions as $key => $lbl)
                            <option value="{{ $key }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                        <div class="pb-field" style="margin-bottom:0;">
                            <label class="pb-label">Sort Order</label>
                            <input type="number" name="sort_order" id="pbOrder" class="pb-input" value="0" min="0">
                        </div>
                        <div class="pb-field" style="margin-bottom:0;">
                            <label class="pb-label">Active</label>
                            <div style="margin-top:8px;display:flex;align-items:center;gap:8px;">
                                <input type="checkbox" name="is_active" id="pbActive" value="1" checked style="width:16px;height:16px;accent-color:#DB2777;cursor:pointer;">
                                <span style="font-size:.82rem;color:#374151;font-weight:600;">Visible</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="pb-submit" id="pbSubmitBtn">Add Promo Banner</button>
                    <button type="button" class="pb-cancel" id="pbCancelBtn" style="display:none;" onclick="pbReset()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });

var baseUrl = '{{ url("admin/promo-banners") }}';

function pbEdit(id, title, subtitle, link, position, order, isActive, imgUrl) {
    var form = document.getElementById('pbForm');
    form.action = baseUrl + '/' + id;
    document.getElementById('pbMethod').value  = 'PUT';
    document.getElementById('pbTitle').value   = title;
    document.getElementById('pbSubtitle').value= subtitle;
    document.getElementById('pbLink').value    = link;
    document.getElementById('pbOrder').value   = order;
    document.getElementById('pbActive').checked= isActive;
    document.getElementById('pbImageInput').required = false;
    document.getElementById('pbImgReq').style.display = 'none';

    // Position
    var sel = document.getElementById('pbPosition');
    for (var i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value === position) { sel.selectedIndex = i; break; }
    }

    // Show current image
    if (imgUrl) {
        document.getElementById('pbCurImgEl').src = imgUrl;
        document.getElementById('pbCurImg').style.display = 'flex';
    }

    // UI
    document.getElementById('pbFormTitle').innerHTML = 'Edit Promo Banner <span class="pb-editing-badge">Editing</span>';
    document.getElementById('pbSubmitBtn').textContent = 'Update Banner';
    document.getElementById('pbCancelBtn').style.display = '';

    // Scroll to form
    document.querySelector('.pb-form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    feather.replace();
}

function pbReset() {
    var form = document.getElementById('pbForm');
    form.action = baseUrl;
    form.reset();
    document.getElementById('pbMethod').value = 'POST';
    document.getElementById('pbImageInput').required = true;
    document.getElementById('pbImgReq').style.display = '';
    document.getElementById('pbCurImg').style.display = 'none';
    document.getElementById('pbFormTitle').textContent = 'Add Promo Banner';
    document.getElementById('pbSubmitBtn').textContent = 'Add Promo Banner';
    document.getElementById('pbCancelBtn').style.display = 'none';
}
</script>
@endsection
