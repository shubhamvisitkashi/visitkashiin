@extends('admin.layouts.app')
@section('content')
<style>
.ig-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* Header — Instagram gradient */
.ig-header{background:linear-gradient(135deg,#405DE6,#5851DB,#833AB4,#C13584,#E1306C,#FD1D1D);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(193,53,132,.3);}
.ig-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.ig-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.ig-header-icon{width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ig-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.ig-header-text p{color:rgba(255,255,255,.72);font-size:.77rem;margin:0;}
.ig-count-badge{position:relative;z-index:1;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:10px;padding:7px 14px;font-size:.78rem;font-weight:700;color:#fff;}

/* Layout */
.ig-layout{display:grid;grid-template-columns:360px 1fr;gap:20px;align-items:start;}
@media(max-width:1024px){.ig-layout{grid-template-columns:1fr;}}

/* Form card */
.ig-form-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.ig-form-head{padding:14px 18px;border-bottom:1px solid #F1F5F9;background:linear-gradient(135deg,#FDF2F8,#FCE7F3);display:flex;align-items:center;gap:8px;}
.ig-form-head-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#C13584,#E1306C);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ig-form-title{font-size:.88rem;font-weight:700;color:#0F172A;}
.ig-form-body{padding:18px;}
.ig-label{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.ig-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:8px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.ig-input:focus{border-color:#C13584;box-shadow:0 0 0 3px rgba(193,53,132,.1);}
.ig-field{margin-bottom:14px;}

/* Thumbnail upload area */
.ig-upload-area{border:2px dashed #E2E8F0;border-radius:10px;padding:14px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;position:relative;}
.ig-upload-area:hover{border-color:#C13584;background:#FDF2F8;}
.ig-upload-area input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;}
.ig-upload-icon{font-size:1.6rem;margin-bottom:5px;}
.ig-upload-txt{font-size:.75rem;color:#64748B;font-weight:600;}
.ig-upload-sub{font-size:.68rem;color:#94A3B8;}

.ig-submit{width:100%;height:40px;background:linear-gradient(135deg,#833AB4,#C13584,#E1306C);color:#fff;border:none;border-radius:10px;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;margin-top:4px;transition:opacity .2s;}
.ig-submit:hover{opacity:.88;}

/* Alert */
.ig-alert{display:flex;align-items:center;gap:10px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;color:#065F46;}

/* Reels grid */
.ig-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.ig-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#FAFBFF;}
.ig-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}

.ig-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;padding:14px;}

/* Reel card — portrait 9:16 */
.ig-reel-card{border-radius:12px;overflow:hidden;background:#111;position:relative;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:transform .2s,box-shadow .2s;cursor:default;}
.ig-reel-card:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(0,0,0,.18);}
.ig-reel-card.inactive{opacity:.55;}
.ig-reel-thumb{aspect-ratio:9/16;position:relative;overflow:hidden;}
.ig-reel-thumb img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s;}
.ig-reel-card:hover .ig-reel-thumb img{transform:scale(1.05);}
.ig-reel-no-thumb{width:100%;aspect-ratio:9/16;background:linear-gradient(160deg,#833AB4,#E1306C,#FD1D1D);display:flex;align-items:center;justify-content:center;}

/* Overlay */
.ig-reel-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.7) 0%,transparent 45%);pointer-events:none;}

/* Top badges */
.ig-reel-top{position:absolute;top:8px;left:8px;right:8px;display:flex;justify-content:space-between;align-items:flex-start;z-index:2;}
.ig-reel-order{background:rgba(0,0,0,.55);color:#fff;font-size:.62rem;font-weight:700;padding:2px 6px;border-radius:4px;}
.ig-reel-status{width:8px;height:8px;border-radius:50%;border:1.5px solid rgba(255,255,255,.8);}
.ig-reel-on{background:#4ade80;}
.ig-reel-off{background:#9ca3af;}

/* IG icon top-right */
.ig-reel-icon{position:absolute;top:8px;right:8px;z-index:2;}
.ig-reel-icon svg{width:18px;height:18px;}

/* Bottom info */
.ig-reel-bottom{position:absolute;bottom:0;left:0;right:0;padding:8px 10px 10px;z-index:2;}
.ig-reel-title{font-size:.72rem;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:5px;}
.ig-reel-actions{display:flex;gap:4px;}
.ig-ract{height:26px;padding:0 8px;border-radius:6px;border:none;cursor:pointer;font-size:.68rem;font-weight:700;display:inline-flex;align-items:center;gap:3px;transition:all .15s;backdrop-filter:blur(4px);}
.ig-ract svg{width:11px;height:11px;flex-shrink:0;}
.ig-ract-edit{background:rgba(255,255,255,.2);color:#fff;}.ig-ract-edit:hover{background:rgba(255,255,255,.35);}
.ig-ract-tog-on {background:rgba(251,191,36,.3);color:#fef3c7;}.ig-ract-tog-on:hover{background:rgba(251,191,36,.5);}
.ig-ract-tog-off{background:rgba(74,222,128,.3);color:#d1fae5;}.ig-ract-tog-off:hover{background:rgba(74,222,128,.5);}
.ig-ract-del{background:rgba(239,68,68,.3);color:#fecaca;}.ig-ract-del:hover{background:rgba(239,68,68,.55);}

/* Product tag */
.ig-prod-tag{display:inline-flex;align-items:center;gap:3px;background:rgba(255,255,255,.15);color:rgba(255,255,255,.85);font-size:.62rem;font-weight:600;padding:1px 6px;border-radius:4px;margin-bottom:4px;}

/* External link chip */
.ig-ext-link{display:inline-flex;align-items:center;gap:3px;background:rgba(255,255,255,.15);color:#fff;font-size:.62rem;font-weight:600;padding:2px 7px;border-radius:6px;text-decoration:none;backdrop-filter:blur(4px);transition:background .15s;}
.ig-ext-link:hover{background:rgba(255,255,255,.28);color:#fff;}

/* Empty */
.ig-empty{text-align:center;padding:44px;color:#94A3B8;}

/* Pagination */
.ig-pag{padding:12px 16px;border-top:1px solid #F1F5F9;}

/* Modal */
.ig-modal .modal-content{border:none;border-radius:16px;overflow:hidden;}
.ig-modal .modal-header{background:linear-gradient(135deg,#833AB4,#C13584,#E1306C);border:none;padding:14px 20px;}
.ig-modal .modal-title{color:#fff;font-size:.92rem;font-weight:800;}
.ig-modal .modal-body{padding:18px 20px;}
.ig-modal .modal-footer{padding:12px 20px;background:#F8FAFC;border-top:1px solid #E2E8F0;}
.ig-mod-submit{background:linear-gradient(135deg,#833AB4,#C13584);color:#fff;border:none;border-radius:9px;padding:9px 20px;font-weight:700;font-size:.84rem;cursor:pointer;}
.ig-mod-cancel{background:#F1F5F9;color:#64748B;border:none;border-radius:9px;padding:9px 16px;font-weight:700;font-size:.84rem;cursor:pointer;}
</style>

<div class="ig-page">

@if(session('success'))
<div class="ig-alert">
    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#10B981" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="ig-header">
    <div class="ig-header-left">
        <div class="ig-header-icon">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                <rect x="2" y="2" width="20" height="20" rx="5.5" fill="#fff" fill-opacity=".25"/>
                <rect x="2" y="2" width="20" height="20" rx="5.5" stroke="#fff" stroke-width="1.5"/>
                <circle cx="12" cy="12" r="4.5" stroke="#fff" stroke-width="1.5"/>
                <circle cx="17.5" cy="6.5" r="1.2" fill="#fff"/>
            </svg>
        </div>
        <div class="ig-header-text">
            <h1>Instagram Reels</h1>
            <p>Manage reels & highlights for public-facing pages</p>
        </div>
    </div>
    <div class="ig-count-badge">{{ $reels->total() }} reels</div>
</div>

<div class="ig-layout">

    {{-- Add Reel Form --}}
    <div>
        <div class="ig-form-card">
            <div class="ig-form-head">
                <div class="ig-form-head-icon">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14"/></svg>
                </div>
                <div class="ig-form-title">Add New Reel</div>
            </div>
            <div class="ig-form-body">
                <form action="{{ route('instagram-reels.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="ig-field">
                        <label class="ig-label">Product (Optional)</label>
                        <select name="product_id" class="ig-input">
                            <option value="">— All / General —</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ Str::limit($p->name,40) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ig-field">
                        <label class="ig-label">Reel Title</label>
                        <input type="text" name="title" class="ig-input" placeholder="e.g. Event Boat Highlights">
                    </div>
                    <div class="ig-field">
                        <label class="ig-label">Instagram Reel URL <span style="color:#C13584;">*</span></label>
                        <input type="url" name="reel_url" class="ig-input" required placeholder="https://www.instagram.com/reel/...">
                    </div>
                    <div class="ig-field">
                        <label class="ig-label">Thumbnail Cover</label>
                        <div class="ig-upload-area">
                            <input type="file" name="thumbnail" accept="image/*" onchange="previewThumb(this)">
                            <div class="ig-upload-icon" id="thumbPreview">📸</div>
                            <div class="ig-upload-txt">Click to upload cover image</div>
                            <div class="ig-upload-sub">JPG, PNG · Portrait (9:16) recommended</div>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div class="ig-field">
                            <label class="ig-label">Sort Order</label>
                            <input type="number" name="sort_order" class="ig-input" value="0">
                        </div>
                        <div class="ig-field">
                            <label class="ig-label">Status</label>
                            <select name="status" class="ig-input">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="ig-submit">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14"/></svg>
                        Add Reel
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Reels Grid --}}
    <div>
        <div class="ig-card">
            <div class="ig-card-head">
                <div class="ig-card-title">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5.5" stroke="#C13584" stroke-width="1.5"/><circle cx="12" cy="12" r="4.5" stroke="#C13584" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="1.2" fill="#C13584"/></svg>
                    All Reels
                </div>
                <span style="font-size:.73rem;color:#64748B;">{{ $reels->total() }} total</span>
            </div>

            @if($reels->isNotEmpty())
            <div class="ig-grid">
                @foreach($reels as $r)
                <div class="ig-reel-card {{ $r->status==='inactive'?'inactive':'' }}">

                    {{-- Thumbnail --}}
                    <div class="ig-reel-thumb">
                        @if($r->thumbnail)
                            <img src="{{ Storage::url($r->thumbnail) }}" alt="{{ $r->title }}">
                        @else
                            <div class="ig-reel-no-thumb">
                                <svg width="36" height="36" fill="none" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5.5" fill="#fff" fill-opacity=".2"/><circle cx="12" cy="12" r="4.5" stroke="#fff" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="1.2" fill="#fff"/></svg>
                            </div>
                        @endif
                        <div class="ig-reel-overlay"></div>

                        {{-- Top badges --}}
                        <div class="ig-reel-top">
                            <div class="ig-reel-order">#{{ $r->sort_order }}</div>
                            <div class="ig-reel-status {{ $r->status==='active'?'ig-reel-on':'ig-reel-off' }}"></div>
                        </div>

                        {{-- Bottom info --}}
                        <div class="ig-reel-bottom">
                            @if(optional($r->product)->name)
                            <div class="ig-prod-tag">
                                <svg width="9" height="9" fill="none" viewBox="0 0 24 24"><path stroke="rgba(255,255,255,.7)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                                {{ Str::limit($r->product->name,18) }}
                            </div>
                            @endif
                            <div class="ig-reel-title">{{ $r->title ?? Str::limit($r->reel_url,28) }}</div>
                            <div class="ig-reel-actions">
                                <button class="ig-ract ig-ract-edit" data-bs-toggle="modal" data-bs-target="#editReel{{ $r->id }}">
                                    <svg fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <form action="{{ route('instagram-reels.toggle', $r) }}" method="POST" style="display:contents;">
                                    @csrf
                                    <button type="submit" class="ig-ract {{ $r->status==='active'?'ig-ract-tog-on':'ig-ract-tog-off' }}">
                                        {{ $r->status==='active'?'On':'Off' }}
                                    </button>
                                </form>
                                <a href="{{ $r->reel_url }}" target="_blank" class="ig-ext-link" title="Open on Instagram">
                                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <form action="{{ route('instagram-reels.destroy', $r) }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete this reel?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ig-ract ig-ract-del">
                                        <svg fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade ig-modal" id="editReel{{ $r->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Reel</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('instagram-reels.update', $r) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="ig-field">
                                        <label class="ig-label">Product</label>
                                        <select name="product_id" class="ig-input">
                                            <option value="">— General —</option>
                                            @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ $r->product_id==$p->id?'selected':'' }}>{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="ig-field">
                                        <label class="ig-label">Title</label>
                                        <input type="text" name="title" class="ig-input" value="{{ $r->title }}">
                                    </div>
                                    <div class="ig-field">
                                        <label class="ig-label">Reel URL <span style="color:#C13584;">*</span></label>
                                        <input type="url" name="reel_url" class="ig-input" value="{{ $r->reel_url }}" required>
                                    </div>
                                    <div class="ig-field">
                                        <label class="ig-label">Thumbnail</label>
                                        <input type="file" name="thumbnail" class="ig-input" accept="image/*" style="padding:6px;">
                                        @if($r->thumbnail)
                                        <div style="margin-top:8px;display:flex;align-items:center;gap:8px;">
                                            <img src="{{ Storage::url($r->thumbnail) }}" style="width:40px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #E2E8F0;">
                                            <span style="font-size:.72rem;color:#64748B;">Current cover</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                        <div class="ig-field">
                                            <label class="ig-label">Sort Order</label>
                                            <input type="number" name="sort_order" class="ig-input" value="{{ $r->sort_order }}">
                                        </div>
                                        <div class="ig-field">
                                            <label class="ig-label">Status</label>
                                            <select name="status" class="ig-input">
                                                <option value="active" {{ $r->status==='active'?'selected':'' }}>Active</option>
                                                <option value="inactive" {{ $r->status==='inactive'?'selected':'' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="ig-mod-cancel" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="ig-mod-submit">Update Reel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($reels->hasPages())
            <div class="ig-pag">{{ $reels->links() }}</div>
            @endif

            @else
            <div class="ig-empty">
                <svg width="52" height="52" fill="none" viewBox="0 0 24 24" style="opacity:.2;display:block;margin:0 auto 14px;"><rect x="2" y="2" width="20" height="20" rx="5.5" stroke="#94A3B8" stroke-width="1.5"/><circle cx="12" cy="12" r="4.5" stroke="#94A3B8" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="1.2" fill="#94A3B8"/></svg>
                <p style="font-weight:700;color:#374151;margin:0 0 5px;">No reels added yet</p>
                <p style="font-size:.82rem;margin:0;">Add your first Instagram reel using the form on the left</p>
            </div>
            @endif
        </div>
    </div>

</div>
</div>

<script>
function previewThumb(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var prev = document.getElementById('thumbPreview');
            prev.innerHTML = '<img src="'+e.target.result+'" style="width:60px;height:90px;object-fit:cover;border-radius:8px;margin:0 auto 4px;display:block;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
