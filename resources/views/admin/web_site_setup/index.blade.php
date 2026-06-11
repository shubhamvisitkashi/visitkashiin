@extends('admin.layouts.app')
@section('content')
<style>
/* ─── Page shell ─────────────────────────────────────────────────── */
.ws-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ─── Hero ───────────────────────────────────────────────────────── */
.ws-hero{background:linear-gradient(135deg,#7C2D12,#C2410C,#EA580C);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:22px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(194,65,12,.28);}
.ws-hero::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;}
.ws-hero::after{content:'⚙️';position:absolute;bottom:-10px;right:16px;font-size:86px;opacity:.07;line-height:1;pointer-events:none;}
.ws-hero-left{position:relative;z-index:1;display:flex;align-items:center;gap:13px;}
.ws-hero-icon{width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ws-hero-icon i[data-feather]{width:22px;height:22px;stroke:#fff;}
.ws-hero-text h1{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.ws-hero-text p{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
.ws-save-top{position:relative;z-index:1;display:inline-flex;align-items:center;gap:7px;background:#fff;color:#C2410C;border:none;border-radius:10px;padding:10px 20px;font-size:.84rem;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.14);transition:all .2s;white-space:nowrap;}
.ws-save-top i[data-feather]{width:15px;height:15px;}
.ws-save-top:hover{background:#FFF7ED;transform:translateY(-1px);}

/* ─── Tab nav ────────────────────────────────────────────────────── */
.ws-tabs{display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap;}
.ws-tab{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:10px;font-size:.8rem;font-weight:700;cursor:pointer;border:none;background:#fff;border:1.5px solid #E2E8F0;color:#64748B;transition:all .18s;}
.ws-tab i[data-feather]{width:14px;height:14px;}
.ws-tab:hover{border-color:#EA580C;color:#EA580C;}
.ws-tab.active{background:linear-gradient(135deg,#C2410C,#EA580C);color:#fff;border-color:transparent;box-shadow:0 3px 10px rgba(194,65,12,.3);}
.ws-tab.active i[data-feather]{stroke:#fff;}

/* ─── Section panels ─────────────────────────────────────────────── */
.ws-panel{display:none;}
.ws-panel.active{display:block;}

/* ─── Section card ───────────────────────────────────────────────── */
.ws-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 5px rgba(0,0,0,.05);overflow:hidden;margin-bottom:16px;}
.ws-card-head{padding:13px 20px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,#FFF7ED,#FFEDD5);}
.ws-card-head-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#C2410C,#EA580C);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ws-card-head-icon i[data-feather]{width:14px;height:14px;stroke:#fff;}
.ws-card-head-title{font-size:.88rem;font-weight:800;color:#0F172A;}
.ws-card-head-sub{font-size:.71rem;color:#64748B;margin-top:1px;}
.ws-card-body{padding:20px;}

/* ─── Field grid ─────────────────────────────────────────────────── */
.ws-grid{display:grid;gap:16px;}
.ws-grid-2{grid-template-columns:1fr 1fr;}
.ws-grid-3{grid-template-columns:1fr 1fr 1fr;}
.ws-grid-4{grid-template-columns:1fr 1fr 1fr 1fr;}
@media(max-width:900px){.ws-grid-3,.ws-grid-4{grid-template-columns:1fr 1fr;}}
@media(max-width:600px){.ws-grid-2,.ws-grid-3,.ws-grid-4{grid-template-columns:1fr;}}

/* ─── Field ──────────────────────────────────────────────────────── */
.ws-field{}
.ws-label{font-size:.69rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;display:block;}
.ws-label span{color:#EF4444;}
.ws-input,.ws-textarea,.ws-select{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:9px 13px;font-size:.85rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.ws-textarea{resize:vertical;min-height:70px;}
.ws-input:focus,.ws-textarea:focus,.ws-select:focus{border-color:#EA580C;box-shadow:0 0 0 3px rgba(234,88,12,.1);}
.ws-file{width:100%;border:1.5px dashed #E2E8F0;border-radius:9px;padding:8px 12px;font-size:.82rem;background:#FAFBFF;cursor:pointer;transition:border-color .15s;}
.ws-file:hover{border-color:#EA580C;}

/* ─── Image preview ──────────────────────────────────────────────── */
.ws-preview{display:flex;align-items:center;gap:10px;margin-top:8px;padding:8px 10px;background:#FFF7ED;border-radius:9px;border:1px solid #FED7AA;}
.ws-preview img{height:48px;max-width:120px;object-fit:contain;border-radius:6px;border:1px solid #FDE68A;}
.ws-preview span{font-size:.72rem;color:#C2410C;font-weight:600;}
.ws-preview-sq img{height:48px;width:48px;object-fit:cover;border-radius:8px;}

/* ─── Alert ──────────────────────────────────────────────────────── */
.ws-alert{display:flex;align-items:center;gap:10px;border-radius:12px;padding:12px 16px;margin-bottom:18px;font-size:.84rem;font-weight:600;}
.ws-alert-ok {background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;}
.ws-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
.ws-alert i[data-feather]{width:18px;height:18px;flex-shrink:0;}

/* ─── Hint text ──────────────────────────────────────────────────── */
.ws-hint{font-size:.71rem;color:#94A3B8;margin-top:4px;line-height:1.4;}

/* ─── Toggle ─────────────────────────────────────────────────────── */
.ws-toggle-row{display:flex;align-items:center;gap:10px;margin-top:4px;}
.ws-switch{position:relative;display:inline-block;width:40px;height:22px;flex-shrink:0;}
.ws-switch input{opacity:0;width:0;height:0;position:absolute;}
.ws-switch-slider{position:absolute;inset:0;background:#D1D5DB;border-radius:22px;cursor:pointer;transition:background .2s;}
.ws-switch-slider::after{content:'';position:absolute;top:3px;left:3px;width:16px;height:16px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.ws-switch input:checked+.ws-switch-slider{background:#EA580C;}
.ws-switch input:checked+.ws-switch-slider::after{transform:translateX(18px);}
.ws-switch-lbl{font-size:.83rem;font-weight:600;color:#374151;}

/* ─── Bottom save bar ────────────────────────────────────────────── */
.ws-save-bar{background:#fff;border-radius:12px;border:1px solid #E2E8F0;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;box-shadow:0 -2px 10px rgba(0,0,0,.04);margin-top:4px;}
.ws-save-btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#C2410C,#EA580C);color:#fff;border:none;border-radius:10px;padding:11px 28px;font-size:.88rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(194,65,12,.3);transition:all .2s;}
.ws-save-btn i[data-feather]{width:16px;height:16px;stroke:#fff;}
.ws-save-btn:hover{opacity:.9;transform:translateY(-1px);}
.ws-save-note{font-size:.76rem;color:#94A3B8;}

/* Divider inside card */
.ws-divider{height:1px;background:#F1F5F9;margin:16px 0;}

/* Per-section save row */
.ws-card-save-row{display:flex;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #F1F5F9;}

/* Bottom sticky save bar */
.ws-bottom-bar{
    position:sticky;bottom:0;left:0;right:0;z-index:100;
    display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;
    background:#fff;border-top:1px solid #E2E8F0;
    padding:14px 20px;margin-top:24px;
    box-shadow:0 -4px 16px rgba(0,0,0,.07);
    border-radius:12px 12px 0 0;
}
.ws-bottom-bar-info{display:flex;align-items:center;gap:6px;font-size:.78rem;color:#64748B;}
.ws-bottom-bar-info i[data-feather]{width:14px;height:14px;stroke:#94A3B8;flex-shrink:0;}
@media(max-width:640px){
    .ws-page{padding:12px;}
    .ws-tabs{gap:5px;}
    .ws-tab{padding:7px 11px;font-size:.74rem;}
    .ws-bottom-bar{flex-direction:column;align-items:stretch;padding:12px 16px;}
    .ws-bottom-bar .ws-save-btn{width:100%;justify-content:center;}
    .ws-bottom-bar-info{justify-content:center;}
}
</style>

<div class="ws-page">

{{-- Flash alerts --}}
@if(session('success'))
<div class="ws-alert ws-alert-ok">
    <i data-feather="check-circle" style="stroke:#10B981;"></i>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="ws-alert ws-alert-err">
    <i data-feather="alert-circle" style="stroke:#EF4444;"></i>
    {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="ws-alert ws-alert-err">
    <i data-feather="alert-triangle" style="stroke:#EF4444;flex-shrink:0;"></i>
    <div>
        <div style="font-weight:700;margin-bottom:4px;">Please fix the following errors:</div>
        <ul style="margin:0;padding-left:16px;font-size:.8rem;">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

{{-- Hero --}}
<div class="ws-hero">
    <div class="ws-hero-left">
        <div class="ws-hero-icon"><i data-feather="settings"></i></div>
        <div class="ws-hero-text">
            <h1>Website Setup</h1>
            <p>Manage branding, content, contact info &amp; promotional banners</p>
        </div>
    </div>
</div>

{{-- Tab nav --}}
<div class="ws-tabs">
    <button type="button" class="ws-tab active" onclick="wsTab('branding',this)">
        <i data-feather="image"></i> Branding
    </button>
    <button type="button" class="ws-tab" onclick="wsTab('hero',this)">
        <i data-feather="monitor"></i> Hero Banner
    </button>
    <button type="button" class="ws-tab" onclick="wsTab('contact',this)">
        <i data-feather="phone"></i> Contact
    </button>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{--  TAB 1 — BRANDING                                             --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<form id="form-branding" method="POST" action="{{ route('web_setup.store') }}" enctype="multipart/form-data">
@csrf
<div id="panel-branding" class="ws-panel active">

    <div class="ws-card">
        <div class="ws-card-head">
            <div class="ws-card-head-icon"><i data-feather="image"></i></div>
            <div>
                <div class="ws-card-head-title">Logos &amp; Identity</div>
                <div class="ws-card-head-sub">Upload site logo, favicon and footer logo</div>
            </div>
        </div>
        <div class="ws-card-body">
            <div class="ws-grid ws-grid-3">

                {{-- Logo --}}
                <div class="ws-field">
                    <label class="ws-label">Site Logo</label>
                    <input type="file" name="logo" class="ws-file" accept="image/*">
                    <input type="hidden" name="type[]" value="logo">
                    <p class="ws-hint">PNG/SVG with transparent bg recommended. Max 2 MB.</p>
                    @if(websiteSetupValue('logo'))
                    <div class="ws-preview">
                        <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('logo')) }}"
                             onerror="this.closest('.ws-preview').remove()">
                        <span>Current logo</span>
                    </div>
                    @endif
                </div>

                {{-- Favicon --}}
                <div class="ws-field">
                    <label class="ws-label">Favicon</label>
                    <input type="file" name="favicon" class="ws-file" accept="image/*">
                    <input type="hidden" name="type[]" value="favicon">
                    <p class="ws-hint">ICO or PNG, 32×32 px recommended. Max 512 KB.</p>
                    @if(websiteSetupValue('favicon'))
                    <div class="ws-preview ws-preview-sq">
                        <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('favicon')) }}"
                             onerror="this.closest('.ws-preview').remove()">
                        <span>Current favicon</span>
                    </div>
                    @endif
                </div>

                {{-- Footer Logo --}}
                <div class="ws-field">
                    <label class="ws-label">Footer Logo</label>
                    <input type="file" name="footer_logo" class="ws-file" accept="image/*">
                    <input type="hidden" name="type[]" value="footer_logo">
                    <p class="ws-hint">Shown in website footer. PNG with transparency preferred.</p>
                    @if(websiteSetupValue('footer_logo'))
                    <div class="ws-preview">
                        <img src="{{ asset('backend/admin/website_setup/'.websiteSetupValue('footer_logo')) }}"
                             onerror="this.closest('.ws-preview').remove()">
                        <span>Current footer logo</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="ws-divider"></div>

            {{-- Company GSTIN & Firm Name --}}
            <div class="ws-field">
                <label class="ws-label">Company GSTIN</label>
                <input type="text" name="company_gstin" class="ws-input"
                    placeholder="e.g. 09ABCDE1234F1Z5"
                    value="{{ websiteSetupValue('company_gstin') }}">
                <input type="hidden" name="type[]" value="company_gstin">
            </div>

            <div class="ws-field">
                <label class="ws-label">Firm Name</label>
                <input type="text" name="company_legal_name" class="ws-input"
                    placeholder="e.g. Visit Kashi Pvt Ltd"
                    value="{{ websiteSetupValue('company_legal_name') }}">
                <input type="hidden" name="type[]" value="company_legal_name">
                <p class="ws-hint">Shown as "GSTIN/VAT" and Company Name on the booking invoice header.</p>
            </div>

            <div class="ws-card-save-row">
                <button type="submit" class="ws-save-btn">
                    <i data-feather="save"></i> Save Logos &amp; Identity
                </button>
            </div>
        </div>
    </div>
</div>
</form>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{--  TAB 2 — HERO BANNER                                          --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div id="panel-hero" class="ws-panel">

    <div class="ws-card" style="margin-bottom:16px;">
        <div class="ws-card-head">
            <div class="ws-card-head-icon"><i data-feather="sliders"></i></div>
            <div style="flex:1;">
                <div class="ws-card-head-title">Hero Slider Slides (Desktop View)</div>
                <div class="ws-card-head-sub">Add multiple slides — each with its own desktop image, title &amp; button</div>
            </div>
            <a href="{{ route('hero-slides.index') }}" style="font-size:.72rem;font-weight:700;color:#EA580C;text-decoration:none;white-space:nowrap;">Manage All →</a>
        </div>
        <div class="ws-card-body" style="padding-bottom:0;">

            {{-- Existing slides list --}}
            @if($heroSlides->isNotEmpty())
            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:18px;">
                @foreach($heroSlides as $slide)

                {{-- Slide row --}}
                <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;overflow:hidden;">
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;">
                        {{-- Thumb --}}
                        <div style="width:52px;height:36px;border-radius:7px;overflow:hidden;flex-shrink:0;background:#E2E8F0;">
                            @if($slide->image)
                            <img src="{{ $slide->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                            @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:.9rem;">🖼</div>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.83rem;font-weight:700;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $slide->title }}</div>
                            <div style="font-size:.7rem;color:#94A3B8;">{{ $slide->cta_label }} · Order {{ $slide->sort_order }}</div>
                        </div>
                        {{-- Actions --}}
                        <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                            {{-- Toggle --}}
                            <form action="{{ route('hero-slides.toggle', $slide->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="border:none;background:{{ $slide->is_active ? '#ECFDF5' : '#F1F5F9' }};color:{{ $slide->is_active ? '#065F46' : '#64748B' }};border:1px solid {{ $slide->is_active ? '#A7F3D0' : '#E2E8F0' }};border-radius:20px;padding:3px 10px;font-size:.67rem;font-weight:700;cursor:pointer;">
                                    {{ $slide->is_active ? '● Active' : '○ Off' }}
                                </button>
                            </form>
                            {{-- Edit --}}
                            <button type="button"
                                onclick="wsSlideEdit(
                                    {{ $slide->id }},
                                    '{{ addslashes($slide->title) }}',
                                    '{{ addslashes($slide->badge ?? '') }}',
                                    '{{ addslashes($slide->tagline ?? '') }}',
                                    '{{ addslashes($slide->cta_label ?? '') }}',
                                    '{{ addslashes($slide->cta_url ?? '') }}',
                                    {{ $slide->sort_order }},
                                    '{{ $slide->image_url }}'
                                )"
                                style="border:none;background:#EEF2FF;color:#4338CA;border-radius:7px;width:28px;height:28px;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                                title="Edit slide">
                                <i data-feather="edit-2" style="width:12px;height:12px;stroke:#4338CA;"></i>
                            </button>
                            {{-- Delete --}}
                            <form action="{{ route('hero-slides.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Delete this slide?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="border:none;background:#FEF2F2;color:#DC2626;border-radius:7px;width:28px;height:28px;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Delete slide">
                                    <i data-feather="trash-2" style="width:12px;height:12px;stroke:#DC2626;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:20px;color:#94A3B8;background:#F8FAFC;border-radius:10px;margin-bottom:18px;">
                <div style="font-size:1.5rem;opacity:.4;margin-bottom:6px;">🖼</div>
                <p style="font-size:.82rem;margin:0;">No slides yet — add your first slide below</p>
            </div>
            @endif

            {{-- ── Inline Edit Form (hidden, shown when edit clicked) ── --}}
            <div id="ws-slide-edit-wrap" style="display:none;background:#EEF2FF;border:1.5px solid #C7D2FE;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div style="font-size:.72rem;font-weight:800;color:#4338CA;text-transform:uppercase;letter-spacing:.06em;">✏️ Edit Slide</div>
                    <button type="button" onclick="wsSlideEditCancel()" style="background:transparent;border:none;color:#94A3B8;cursor:pointer;font-size:.75rem;font-weight:600;">✕ Cancel</button>
                </div>
                <form id="form-hero-edit" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- Current image preview --}}
                    <div id="ws-edit-img-preview" style="display:none;margin-bottom:10px;background:#E0E7FF;border-radius:8px;padding:8px 12px;display:flex;align-items:center;gap:10px;">
                        <img id="ws-edit-img-el" src="" alt="" style="height:40px;width:60px;object-fit:cover;border-radius:6px;border:1px solid #C7D2FE;">
                        <span style="font-size:.72rem;color:#4338CA;font-weight:600;">Current image — upload new to replace</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                        <div>
                            <label class="ws-label">New Image <span style="font-weight:400;color:#94A3B8;text-transform:none;">(leave blank to keep)</span></label>
                            <input type="file" name="image" class="ws-file" accept="image/*" style="padding:4px 10px;cursor:pointer;font-size:.78rem;">
                        </div>
                        <div>
                            <label class="ws-label">Badge Text</label>
                            <input type="text" name="badge" id="ws-edit-badge" class="ws-input" style="font-size:.8rem;" placeholder="e.g. #1 Platform">
                        </div>
                    </div>
                    <div style="margin-bottom:10px;">
                        <label class="ws-label">Title <span style="color:#EF4444;">*</span></label>
                        <input type="text" name="title" id="ws-edit-title" class="ws-input" required style="font-size:.8rem;" placeholder="Slide title">
                    </div>
                    <div style="margin-bottom:10px;">
                        <label class="ws-label">Tagline</label>
                        <input type="text" name="tagline" id="ws-edit-tagline" class="ws-input" style="font-size:.8rem;" placeholder="Short subtitle">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 80px;gap:8px;margin-bottom:12px;">
                        <div>
                            <label class="ws-label">Button Label</label>
                            <input type="text" name="cta_label" id="ws-edit-cta-label" class="ws-input" style="font-size:.8rem;">
                        </div>
                        <div>
                            <label class="ws-label">Button URL</label>
                            <input type="text" name="cta_url" id="ws-edit-cta-url" class="ws-input" style="font-size:.8rem;">
                        </div>
                        <div>
                            <label class="ws-label">Order</label>
                            <input type="number" name="sort_order" id="ws-edit-order" class="ws-input" style="font-size:.8rem;" min="0">
                        </div>
                    </div>
                    <input type="hidden" name="is_active" value="1">
                    <div class="ws-card-save-row" style="border-top:none;padding-top:0;margin-top:0;">
                        <button type="submit" class="ws-save-btn" style="background:linear-gradient(135deg,#4338CA,#6366F1);">
                            <i data-feather="save"></i> Update Slide
                        </button>
                    </div>
                </form>
            </div>

            {{-- Quick Add Slide Form --}}
            <div style="background:#FFF7ED;border:1.5px solid #FED7AA;border-radius:12px;padding:16px;margin-bottom:20px;">
                <div style="font-size:.72rem;font-weight:800;color:#EA580C;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">➕ Add New Slide</div>
                <form id="form-hero-add" action="{{ route('hero-slides.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                        <div>
                            <label class="ws-label">Slide Image <span style="color:#EF4444;">*</span></label>
                            <input type="file" name="image" class="ws-file" accept="image/*" style="padding:4px 10px;cursor:pointer;font-size:.78rem;">
                        </div>
                        <div>
                            <label class="ws-label">Badge Text</label>
                            <input type="text" name="badge" class="ws-input" style="font-size:.8rem;" placeholder="e.g. #1 Platform">
                        </div>
                    </div>
                    <div style="margin-bottom:10px;">
                        <label class="ws-label">Title <span style="color:#EF4444;">*</span></label>
                        <input type="text" name="title" class="ws-input" required style="font-size:.8rem;" placeholder="e.g. Most Trusted Travel Company">
                    </div>
                    <div style="margin-bottom:10px;">
                        <label class="ws-label">Tagline</label>
                        <input type="text" name="tagline" class="ws-input" style="font-size:.8rem;" placeholder="e.g. Book boats, cabs, hotels & tours">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 80px;gap:8px;margin-bottom:12px;">
                        <div>
                            <label class="ws-label">Button Label</label>
                            <input type="text" name="cta_label" class="ws-input" style="font-size:.8rem;" placeholder="Explore Tours" value="Explore Tours">
                        </div>
                        <div>
                            <label class="ws-label">Button URL</label>
                            <input type="text" name="cta_url" class="ws-input" style="font-size:.8rem;" placeholder="/packages" value="/packages">
                        </div>
                        <div>
                            <label class="ws-label">Order</label>
                            <input type="number" name="sort_order" class="ws-input" style="font-size:.8rem;" value="0" min="0">
                        </div>
                    </div>
                    <input type="hidden" name="is_active" value="1">
                    <div class="ws-card-save-row" style="border-top:none;padding-top:0;margin-top:0;">
                        <button type="submit" class="ws-save-btn">
                            <i data-feather="save"></i> Save Slide
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="ws-card" style="margin-bottom:16px;">
        <div class="ws-card-head">
            <div class="ws-card-head-icon"><i data-feather="smartphone"></i></div>
            <div style="flex:1;">
                <div class="ws-card-head-title">Hero Slider Slides (Mobile View)</div>
                <div class="ws-card-head-sub">Upload a portrait Mobile Image (optional — falls back to desktop image if not set)</div>
            </div>
        </div>
        <div class="ws-card-body">
            @if($heroSlides->isNotEmpty())
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($heroSlides as $slide)
                <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:10px 14px;">
                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                        {{-- Mobile thumb --}}
                        <div style="width:40px;height:60px;border-radius:7px;overflow:hidden;flex-shrink:0;background:#E2E8F0;display:flex;align-items:center;justify-content:center;">
                            @if($slide->mobile_image)
                            <img src="{{ $slide->mobile_image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                            @else
                            <span style="font-size:.9rem;">📱</span>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div style="flex:1;min-width:160px;">
                            <div style="font-size:.83rem;font-weight:700;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $slide->title }}</div>
                            <div style="font-size:.7rem;color:{{ $slide->mobile_image ? '#15803D' : '#94A3B8' }};">
                                {{ $slide->mobile_image ? '✓ Custom mobile image set' : 'No mobile image — falls back to desktop image' }}
                            </div>
                        </div>
                        {{-- Upload / replace / remove form --}}
                        <form action="{{ route('hero-slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            @csrf @method('PUT')
                            <input type="hidden" name="badge" value="{{ $slide->badge }}">
                            <input type="hidden" name="title" value="{{ $slide->title }}">
                            <input type="hidden" name="tagline" value="{{ $slide->tagline }}">
                            <input type="hidden" name="cta_label" value="{{ $slide->cta_label }}">
                            <input type="hidden" name="cta_url" value="{{ $slide->cta_url }}">
                            <input type="hidden" name="sort_order" value="{{ $slide->sort_order }}">
                            <input type="hidden" name="is_active" value="{{ $slide->is_active ? 1 : 0 }}">
                            <input type="file" name="mobile_image" accept="image/*" class="ws-file" style="font-size:.72rem;padding:5px 10px;width:auto;">
                            @if($slide->mobile_image)
                            <label style="font-size:.68rem;color:#EF4444;display:flex;align-items:center;gap:4px;white-space:nowrap;cursor:pointer;">
                                <input type="checkbox" name="clear_mobile_image" value="1"> Remove
                            </label>
                            @endif
                            <button type="submit" style="border:none;background:linear-gradient(135deg,#4338CA,#6366F1);color:#fff;border-radius:7px;padding:7px 14px;font-size:.72rem;font-weight:700;cursor:pointer;white-space:nowrap;">Save</button>
                        </form>
                    </div>
                    <div style="font-size:.66rem;color:#94A3B8;margin-top:6px;">📱 Mobile Image (optional — portrait optimized) · JPG/PNG/WebP · Max 4MB · 750×1200px recommended</div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:20px;color:#94A3B8;background:#F8FAFC;border-radius:10px;">
                <div style="font-size:1.5rem;opacity:.4;margin-bottom:6px;">📱</div>
                <p style="font-size:.82rem;margin:0;">No slides yet — add a slide in Desktop View first</p>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{--  TAB 3 — CONTACT                                              --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<form id="form-contact" method="POST" action="{{ route('web_setup.store') }}" enctype="multipart/form-data">
@csrf
<div id="panel-contact" class="ws-panel">

    <div class="ws-card">
        <div class="ws-card-head">
            <div class="ws-card-head-icon"><i data-feather="phone"></i></div>
            <div>
                <div class="ws-card-head-title">Contact &amp; Support</div>
                <div class="ws-card-head-sub">Phone, WhatsApp, email and business address</div>
            </div>
        </div>
        <div class="ws-card-body">
            <div class="ws-grid ws-grid-3" style="margin-bottom:16px;">

                <div class="ws-field">
                    <label class="ws-label">Contact Number</label>
                    <input type="text" name="contact_number" class="ws-input"
                        placeholder="e.g. 7080109917"
                        value="{{ websiteSetupValue('contact_number') }}">
                    <input type="hidden" name="type[]" value="contact_number">
                </div>

                <div class="ws-field">
                    <label class="ws-label">WhatsApp Number</label>
                    <input type="text" name="whats_app_number" class="ws-input"
                        placeholder="e.g. 7080109918"
                        value="{{ websiteSetupValue('whats_app_number') }}">
                    <input type="hidden" name="type[]" value="whats_app_number">
                    <p class="ws-hint">Separate multiple numbers with commas.</p>
                </div>

                <div class="ws-field">
                    <label class="ws-label">Email Address</label>
                    <input type="email" name="email" class="ws-input"
                        placeholder="booking@visitkashi.com"
                        value="{{ websiteSetupValue('email') }}">
                    <input type="hidden" name="type[]" value="email">
                </div>
            </div>

            <div class="ws-field">
                <label class="ws-label">Business Address</label>
                <textarea name="address" class="ws-textarea"
                    placeholder="Full business address…">{{ websiteSetupValue('address') }}</textarea>
                <input type="hidden" name="type[]" value="address">
            </div>

            <div class="ws-card-save-row">
                <button type="submit" class="ws-save-btn">
                    <i data-feather="save"></i> Save Contact &amp; Support
                </button>
            </div>
        </div>
    </div>
</div>
</form>


{{-- Bottom sticky save bar --}}
<div class="ws-bottom-bar">
    <span class="ws-bottom-bar-info">
        <i data-feather="info"></i> Changes will be applied to the live website immediately.
    </span>
    <button type="button" class="ws-save-btn" onclick="wsSubmitActive()">
        <i data-feather="save"></i> Update Settings
    </button>
</div>

</div>{{-- /.ws-page --}}

<script>
// Map panel name → form id (hero has no web_setup form)
var wsFormMap = {
    branding : 'form-branding',
    hero     : 'form-hero-add',
    contact  : 'form-contact'
};
var wsActivePanel = 'branding';

function wsTab(name, el) {
    document.querySelectorAll('.ws-panel').forEach(function(p){ p.classList.remove('active'); });
    document.querySelectorAll('.ws-tab').forEach(function(t){ t.classList.remove('active'); });
    document.getElementById('panel-' + name).classList.add('active');
    el.classList.add('active');
    wsActivePanel = name;
}

function wsSubmitActive() {
    var formId = wsFormMap[wsActivePanel];
    if (formId) {
        var form = document.getElementById(formId);
        if (form) form.submit();
    }
}

// Promo toggle label
var promoToggle = document.getElementById('promoActiveToggle');
var promoLbl    = document.getElementById('promoActiveLbl');
if (promoToggle && promoLbl) {
    promoToggle.addEventListener('change', function(){
        promoLbl.textContent = this.checked ? 'Visible on home page' : 'Hidden from home page';
    });
}

document.addEventListener('DOMContentLoaded', function(){ feather.replace(); });

/* ── Hero Slide Inline Edit ── */
function wsSlideEdit(id, title, badge, tagline, ctaLabel, ctaUrl, order, imgUrl) {
    var wrap = document.getElementById('ws-slide-edit-wrap');
    var form = document.getElementById('form-hero-edit');

    // Set form action to the update route
    form.action = '{{ url("admin/hero-slides") }}/' + id;

    // Populate fields
    document.getElementById('ws-edit-title').value     = title;
    document.getElementById('ws-edit-badge').value     = badge;
    document.getElementById('ws-edit-tagline').value   = tagline;
    document.getElementById('ws-edit-cta-label').value = ctaLabel;
    document.getElementById('ws-edit-cta-url').value   = ctaUrl;
    document.getElementById('ws-edit-order').value     = order;

    // Show current image if exists
    var preview = document.getElementById('ws-edit-img-preview');
    var imgEl   = document.getElementById('ws-edit-img-el');
    if (imgUrl && imgUrl.indexOf('placeholder') === -1) {
        imgEl.src            = imgUrl;
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }

    // Show the edit panel and scroll to it
    wrap.style.display = 'block';
    wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
    feather.replace();
}

function wsSlideEditCancel() {
    document.getElementById('ws-slide-edit-wrap').style.display = 'none';
    document.getElementById('form-hero-edit').reset();
}
</script>
@endsection
