@extends('admin.layouts.app')
@section('content')
<style>
.hs-page{padding:24px;background:#F1F5F9;min-height:100vh;}
.hs-header{background:linear-gradient(135deg,#1E1B4B,#4338CA,#6366F1);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:22px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(67,56,202,.28);}
.hs-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.hs-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:13px;}
.hs-header-icon{width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.hs-header-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.hs-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.hs-header-text p{color:rgba(255,255,255,.7);font-size:.75rem;margin:0;}

/* Layout */
.hs-layout{display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;}
@media(max-width:1024px){.hs-layout{grid-template-columns:1fr;}}

/* Cards grid */
.hs-grid{display:flex;flex-direction:column;gap:14px;}
.hs-slide-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;display:flex;gap:0;}
.hs-slide-thumb{width:160px;flex-shrink:0;background:#F1F5F9;position:relative;overflow:hidden;}
.hs-slide-thumb img{width:100%;height:100%;object-fit:cover;display:block;}
.hs-slide-thumb-placeholder{width:100%;height:110px;background:linear-gradient(135deg,#E0E7FF,#C7D2FE);display:flex;align-items:center;justify-content:center;font-size:2rem;}
.hs-slide-body{padding:14px 16px;flex:1;display:flex;flex-direction:column;justify-content:space-between;}
.hs-slide-badge{font-size:.65rem;font-weight:700;background:#EEF2FF;color:#4338CA;padding:2px 9px;border-radius:20px;display:inline-block;margin-bottom:6px;}
.hs-slide-title{font-size:.9rem;font-weight:800;color:#0F172A;margin:0 0 4px;}
.hs-slide-tagline{font-size:.75rem;color:#64748B;margin:0 0 10px;}
.hs-slide-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.hs-slide-cta-badge{font-size:.65rem;background:#F0FDF4;color:#15803D;border:1px solid #BBF7D0;border-radius:20px;padding:2px 9px;}
.hs-order-badge{font-size:.65rem;background:#F8FAFC;color:#64748B;border:1px solid #E2E8F0;border-radius:20px;padding:2px 9px;}
.hs-slide-actions{display:flex;gap:6px;align-items:center;margin-top:10px;}
.hs-act{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;text-decoration:none;transition:all .15s;}
.hs-act i[data-feather]{width:13px;height:13px;}
.hs-act-edit{background:#EEF2FF;color:#4F46E5;}.hs-act-edit:hover{background:#DDD6FE;}
.hs-act-del{background:#FEF2F2;color:#DC2626;}.hs-act-del:hover{background:#FECACA;}
.hs-cancel{width:100%;height:36px;background:#F1F5F9;color:#64748B;border:1.5px solid #E2E8F0;border-radius:9px;font-size:.82rem;font-weight:700;cursor:pointer;margin-top:6px;}
.hs-editing-tag{background:#EEF2FF;color:#4F46E5;border:1px solid #C7D2FE;border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:700;margin-left:6px;}
.hs-cur-img{display:flex;align-items:center;gap:8px;background:#F0F9FF;border:1px solid #BAE6FD;border-radius:8px;padding:7px 10px;margin-top:6px;}
.hs-cur-img img{width:48px;height:32px;object-fit:cover;border-radius:5px;border:1px solid #E2E8F0;}
.hs-cur-img span{font-size:.7rem;color:#0369A1;font-weight:600;}
.hs-status-on {display:inline-flex;align-items:center;gap:4px;background:#ECFDF5;color:#065F46;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;border:1px solid #A7F3D0;text-decoration:none;}
.hs-status-off{display:inline-flex;align-items:center;gap:4px;background:#F1F5F9;color:#64748B;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;border:1px solid #E2E8F0;text-decoration:none;}
.hs-status-on:hover,.hs-status-off:hover{opacity:.85;text-decoration:none;}
.hs-empty{text-align:center;padding:48px;background:#fff;border-radius:14px;border:1px solid #E2E8F0;color:#94A3B8;}

/* Form card */
.hs-form-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.hs-form-head{padding:14px 18px;border-bottom:1px solid #F1F5F9;background:linear-gradient(135deg,#EEF2FF,#E0E7FF);display:flex;align-items:center;gap:9px;}
.hs-form-head-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#4338CA,#6366F1);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.hs-form-head-icon i[data-feather]{width:14px;height:14px;stroke:#fff;}
.hs-form-head-title{font-size:.88rem;font-weight:800;color:#0F172A;}
.hs-form-body{padding:18px;}
.hs-label{font-size:.69rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;display:block;}
.hs-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:9px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.hs-input:focus{border-color:#4338CA;box-shadow:0 0 0 3px rgba(67,56,202,.1);}
.hs-field{margin-bottom:13px;}
.hs-thumb-preview{width:100%;height:80px;border-radius:9px;object-fit:cover;margin-top:7px;border:1px solid #E2E8F0;}
.hs-switch{position:relative;display:inline-block;width:38px;height:21px;}
.hs-switch input{opacity:0;width:0;height:0;position:absolute;}
.hs-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:21px;cursor:pointer;transition:background .2s;}
.hs-switch-slider::after{content:'';position:absolute;top:3.5px;left:3.5px;width:14px;height:14px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.hs-switch input:checked+.hs-switch-slider{background:#4338CA;}
.hs-switch input:checked+.hs-switch-slider::after{transform:translateX(17px);}
.hs-submit{width:100%;height:40px;background:linear-gradient(135deg,#4338CA,#6366F1);color:#fff;border:none;border-radius:9px;font-size:.85rem;font-weight:700;cursor:pointer;transition:opacity .2s;}
.hs-submit:hover{opacity:.88;}
.hs-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;}
.hs-alert-ok{background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
</style>

<div class="hs-page">

@if(session('success'))
<div class="hs-alert hs-alert-ok"><i data-feather="check-circle" style="width:18px;height:18px;flex-shrink:0;stroke:#10B981;"></i> {{ session('success') }}</div>
@endif

{{-- Header --}}
<div class="hs-header">
    <div class="hs-header-left">
        <div class="hs-header-icon"><i data-feather="sliders"></i></div>
        <div class="hs-header-text">
            <h1>Hero Slider Management</h1>
            <p>Manage homepage hero slider slides — images, text & CTAs</p>
        </div>
    </div>
    <span style="position:relative;z-index:1;background:rgba(255,255,255,.15);color:#fff;font-size:.75rem;font-weight:700;padding:5px 14px;border-radius:20px;border:1px solid rgba(255,255,255,.25);">{{ $slides->count() }} slides</span>
</div>

<div class="hs-layout">

    {{-- Slides list --}}
    <div>
        @if($slides->isEmpty())
        <div class="hs-empty">
            <div style="font-size:3rem;opacity:.3;margin-bottom:12px;">🖼</div>
            <p style="font-weight:700;color:#374151;margin:0 0 4px;">No slides yet</p>
            <p style="font-size:.82rem;margin:0;">Add your first hero slider slide using the form →</p>
        </div>
        @else
        <div class="hs-grid">
            @foreach($slides as $slide)
            <div class="hs-slide-card">
                <div class="hs-slide-thumb" style="min-height:110px;">
                    @if($slide->image)
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}">
                    @elseif($slide->mobile_image)
                    <img src="{{ $slide->mobile_image_url }}" alt="{{ $slide->title }}">
                    @else
                    <div class="hs-slide-thumb-placeholder">🖼</div>
                    @endif
                </div>
                <div class="hs-slide-body">
                    <div>
                        @if($slide->badge)<span class="hs-slide-badge">{{ $slide->badge }}</span>@endif
                        <div class="hs-slide-title">{{ $slide->title }}</div>
                        @if($slide->tagline)<div class="hs-slide-tagline">{{ Str::limit($slide->tagline, 80) }}</div>@endif
                        <div class="hs-slide-meta">
                            <span class="hs-slide-cta-badge">{{ $slide->cta_label }}</span>
                            <span class="hs-order-badge">Order: {{ $slide->sort_order }}</span>
                        </div>
                    </div>
                    <div class="hs-slide-actions">
                        <form action="{{ route('hero-slides.toggle', $slide->id) }}" method="POST" style="display:contents;">
                            @csrf
                            <button type="submit" class="{{ $slide->is_active ? 'hs-status-on' : 'hs-status-off' }}">
                                {{ $slide->is_active ? '● Active' : '○ Inactive' }}
                            </button>
                        </form>
                        <button type="button" class="hs-act hs-act-edit" title="Edit"
                            onclick="hsEdit({{ $slide->id }},'{{ addslashes($slide->title) }}','{{ addslashes($slide->badge ?? '') }}','{{ addslashes($slide->tagline ?? '') }}','{{ addslashes($slide->cta_label ?? '') }}','{{ addslashes($slide->cta_url ?? '') }}','{{ $slide->sort_order }}',{{ $slide->is_active ? 'true' : 'false' }},'{{ $slide->image_url }}','{{ $slide->mobile_image_url ?? '' }}')">
                            <i data-feather="edit-2"></i>
                        </button>
                        <form action="{{ route('hero-slides.destroy', $slide->id) }}" method="POST" id="hsdel_{{ $slide->id }}" style="display:contents;">
                            @csrf @method('DELETE')
                            <button type="button" class="hs-act hs-act-del" title="Delete"
                                onclick="if(confirm('Delete slide \'{{ addslashes($slide->title) }}\'?')) document.getElementById('hsdel_{{ $slide->id }}').submit()">
                                <i data-feather="trash-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Add / Edit slide form --}}
    <div>
        <div class="hs-form-card">
            <div class="hs-form-head">
                <div class="hs-form-head-icon" id="hsFormIcon"><i data-feather="plus-square"></i></div>
                <div class="hs-form-head-title" id="hsFormTitle">Add New Slide</div>
            </div>
            <div class="hs-form-body">
                <form id="hsForm" action="{{ route('hero-slides.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="hsMethod" value="POST">

                    <div class="hs-field">
                        <label class="hs-label">Desktop Image <span id="hsImgReq" style="color:#EF4444;">*</span></label>
                        <input type="file" name="image" id="hsImageInput" class="hs-input" accept="image/*" style="padding:5px 10px;cursor:pointer;">
                        <div style="font-size:.68rem;color:#94A3B8;margin-top:4px;">JPG/PNG/WebP · Max 4 MB · 1920×1080px recommended (landscape)</div>
                        <div id="hsCurImg" class="hs-cur-img" style="display:none;">
                            <img id="hsCurImgEl" src="" alt="">
                            <span>Current image — upload new to replace</span>
                        </div>
                    </div>

                    <div class="hs-field">
                        <label class="hs-label">
                            📱 Mobile Image
                            <span style="font-size:.68rem;font-weight:400;color:#6B7280;margin-left:6px;">(optional — portrait optimized)</span>
                        </label>
                        <input type="file" name="mobile_image" id="hsMobImageInput" class="hs-input" accept="image/*" style="padding:5px 10px;cursor:pointer;">
                        <div style="font-size:.68rem;color:#94A3B8;margin-top:4px;">JPG/PNG/WebP · Max 4 MB · 750×1200px recommended (portrait) · Falls back to desktop image if not set</div>
                        <div id="hsCurMobImg" class="hs-cur-img" style="display:none;">
                            <img id="hsCurMobImgEl" src="" alt="" style="max-height:80px;">
                            <span>Current mobile image — upload new to replace</span>
                            <label style="display:flex;align-items:center;gap:5px;margin-top:6px;font-size:.72rem;color:#EF4444;cursor:pointer;">
                                <input type="checkbox" name="clear_mobile_image" value="1"> Remove mobile image
                            </label>
                        </div>
                    </div>

                    <div class="hs-field">
                        <label class="hs-label">Badge Text</label>
                        <input type="text" name="badge" id="hsBadge" class="hs-input" placeholder="e.g. Varanasi's #1 Platform">
                    </div>

                    <div class="hs-field">
                        <label class="hs-label">Title <span style="color:#EF4444;">*</span></label>
                        <input type="text" name="title" id="hsTitle" class="hs-input" required placeholder="e.g. Most Trusted Travel Company">
                    </div>

                    <div class="hs-field">
                        <label class="hs-label">Tagline / Subtitle</label>
                        <input type="text" name="tagline" id="hsTagline" class="hs-input" placeholder="e.g. Book boats, cabs & tours">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div class="hs-field">
                            <label class="hs-label">Button Label</label>
                            <input type="text" name="cta_label" id="hsCtaLabel" class="hs-input" placeholder="Explore Tours">
                        </div>
                        <div class="hs-field">
                            <label class="hs-label">Button URL</label>
                            <input type="text" name="cta_url" id="hsCtaUrl" class="hs-input" placeholder="/packages">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                        <div class="hs-field" style="margin-bottom:0;">
                            <label class="hs-label">Sort Order</label>
                            <input type="number" name="sort_order" id="hsSortOrder" class="hs-input" min="0" value="0">
                        </div>
                        <div class="hs-field" style="margin-bottom:0;">
                            <label class="hs-label">Active</label>
                            <div style="margin-top:8px;display:flex;align-items:center;gap:8px;">
                                <label class="hs-switch">
                                    <input type="checkbox" name="is_active" id="hsActive" value="1" checked>
                                    <span class="hs-switch-slider"></span>
                                </label>
                                <span style="font-size:.82rem;color:#374151;font-weight:600;">Visible</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="hs-submit" id="hsSubmitBtn">
                        <i data-feather="plus" style="width:15px;height:15px;vertical-align:middle;margin-right:5px;stroke:#fff;"></i>
                        Add Slide
                    </button>
                    <button type="button" class="hs-cancel" id="hsCancelBtn" style="display:none;" onclick="hsReset()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });

var baseUrl = '{{ url("admin/hero-slides") }}';

function hsEdit(id, title, badge, tagline, ctaLabel, ctaUrl, order, isActive, imgUrl, mobImgUrl) {
    var form = document.getElementById('hsForm');
    form.action = baseUrl + '/' + id;
    document.getElementById('hsMethod').value     = 'PUT';
    document.getElementById('hsTitle').value      = title;
    document.getElementById('hsBadge').value      = badge;
    document.getElementById('hsTagline').value    = tagline;
    document.getElementById('hsCtaLabel').value   = ctaLabel;
    document.getElementById('hsCtaUrl').value     = ctaUrl;
    document.getElementById('hsSortOrder').value  = order;
    document.getElementById('hsActive').checked   = isActive;
    document.getElementById('hsImageInput').required = false;
    document.getElementById('hsImgReq').style.display = 'none';
    if (imgUrl) {
        document.getElementById('hsCurImgEl').src = imgUrl;
        document.getElementById('hsCurImg').style.display = 'flex';
    }
    // Mobile image preview
    if (mobImgUrl) {
        document.getElementById('hsCurMobImgEl').src = mobImgUrl;
        document.getElementById('hsCurMobImg').style.display = 'flex';
    } else {
        document.getElementById('hsCurMobImg').style.display = 'none';
    }
    document.getElementById('hsFormTitle').textContent = 'Edit Slide';
    document.getElementById('hsSubmitBtn').textContent = 'Update Slide';
    document.getElementById('hsCancelBtn').style.display = '';
    document.querySelector('.hs-form-card').scrollIntoView({ behavior:'smooth', block:'start' });
    feather.replace();
}

function hsReset() {
    var form = document.getElementById('hsForm');
    form.action = baseUrl;
    form.reset();
    document.getElementById('hsMethod').value = 'POST';
    document.getElementById('hsImageInput').required = true;
    document.getElementById('hsImgReq').style.display = '';
    document.getElementById('hsCurImg').style.display = 'none';
    document.getElementById('hsCurMobImg').style.display = 'none';
    document.getElementById('hsFormTitle').textContent = 'Add New Slide';
    document.getElementById('hsSubmitBtn').textContent = 'Add Slide';
    document.getElementById('hsCancelBtn').style.display = 'none';
}
</script>
@endsection
