@extends('admin.layouts.app')
@section('content')
<style>
.yt-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* Header */
.yt-header{background:linear-gradient(135deg,#7F1D1D,#DC2626,#EF4444);border-radius:16px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(220,38,38,.28);}
.yt-header::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.07);border-radius:50%;}
.yt-header-left{position:relative;z-index:1;display:flex;align-items:center;gap:12px;}
.yt-header-icon{width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.yt-header-text h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 2px;}
.yt-header-text p{color:rgba(255,255,255,.72);font-size:.77rem;margin:0;}

/* Layout */
.yt-layout{display:grid;grid-template-columns:360px 1fr;gap:20px;align-items:start;}
@media(max-width:1024px){.yt-layout{grid-template-columns:1fr;}}

/* Form card */
.yt-form-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;position:sticky;top:80px;}
.yt-form-head{padding:14px 18px;border-bottom:1px solid #F1F5F9;background:linear-gradient(135deg,#FEF2F2,#FFF5F5);display:flex;align-items:center;gap:8px;}
.yt-form-head-icon{width:30px;height:30px;border-radius:8px;background:#DC2626;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.yt-form-title{font-size:.88rem;font-weight:700;color:#0F172A;}
.yt-form-body{padding:18px;}
.yt-label{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.yt-input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:8px 12px;font-size:.84rem;color:#0F172A;background:#FAFBFF;outline:none;transition:border-color .15s;font-family:inherit;}
.yt-input:focus{border-color:#DC2626;box-shadow:0 0 0 3px rgba(220,38,38,.1);}
.yt-field{margin-bottom:14px;}
.yt-submit{width:100%;height:40px;background:linear-gradient(135deg,#DC2626,#EF4444);color:#fff;border:none;border-radius:10px;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;margin-top:4px;transition:opacity .2s;}
.yt-submit:hover{opacity:.88;}
.yt-submit svg{width:15px;height:15px;flex-shrink:0;}

/* Alert */
.yt-alert{display:flex;align-items:center;gap:10px;background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:11px 16px;margin-bottom:16px;font-size:.83rem;font-weight:600;color:#065F46;}

/* Videos card */
.yt-card{background:#fff;border-radius:14px;border:1px solid #E2E8F0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;}
.yt-card-head{padding:13px 18px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;background:#FAFBFF;}
.yt-card-title{font-size:.88rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:7px;}

/* Video grid */
.yt-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;padding:16px;}

/* Video card */
.yt-vid-card{border-radius:12px;border:1px solid #E2E8F0;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.05);transition:box-shadow .18s,transform .18s;}
.yt-vid-card:hover{box-shadow:0 6px 18px rgba(0,0,0,.1);transform:translateY(-2px);}
.yt-vid-card.inactive{opacity:.6;}

/* Thumbnail */
.yt-thumb{position:relative;aspect-ratio:16/9;background:#111;overflow:hidden;}
.yt-thumb img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s;}
.yt-vid-card:hover .yt-thumb img{transform:scale(1.05);}
.yt-play{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.25);}
.yt-play-btn{width:38px;height:38px;border-radius:50%;background:#FF0000;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 10px rgba(255,0,0,.5);}
.yt-play-btn svg{width:16px;height:16px;fill:#fff;margin-left:2px;}
.yt-status-dot{position:absolute;top:7px;right:7px;width:8px;height:8px;border-radius:50%;border:1.5px solid #fff;}
.yt-status-on{background:#4ade80;}
.yt-status-off{background:#9ca3af;}
.yt-order{position:absolute;top:7px;left:7px;background:rgba(0,0,0,.6);color:#fff;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:4px;}

/* Video body */
.yt-vid-body{padding:10px 12px 12px;}
.yt-vid-title{font-size:.8rem;font-weight:700;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px;}
.yt-vid-product{font-size:.68rem;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:8px;display:flex;align-items:center;gap:4px;}
.yt-vid-actions{display:flex;gap:5px;}
.yt-act{height:28px;padding:0 10px;border-radius:7px;border:none;cursor:pointer;font-size:.72rem;font-weight:700;display:inline-flex;align-items:center;gap:4px;transition:all .15s;}
.yt-act svg{width:11px;height:11px;flex-shrink:0;}
.yt-act-edit{background:#EEF2FF;color:#4F46E5;}.yt-act-edit:hover{background:#ddd6fe;}
.yt-act-tog-on {background:#FEF3C7;color:#B45309;}.yt-act-tog-on:hover{background:#fde68a;}
.yt-act-tog-off{background:#ECFDF5;color:#065F46;}.yt-act-tog-off:hover{background:#d1fae5;}
.yt-act-del{background:#FEF2F2;color:#DC2626;}.yt-act-del:hover{background:#FECACA;}

/* Empty */
.yt-empty{text-align:center;padding:44px;color:#94A3B8;}
.yt-empty svg{width:48px;height:48px;opacity:.2;display:block;margin:0 auto 12px;stroke:#94A3B8;}

/* Pagination */
.yt-pag{padding:12px 16px;border-top:1px solid #F1F5F9;}

/* Edit Modal */
.yt-modal .modal-content{border:none;border-radius:16px;overflow:hidden;}
.yt-modal .modal-header{background:linear-gradient(135deg,#7F1D1D,#DC2626);border:none;padding:14px 20px;}
.yt-modal .modal-title{color:#fff;font-size:.92rem;font-weight:800;}
.yt-modal .modal-body{padding:18px 20px;}
.yt-modal .modal-footer{padding:12px 20px;background:#F8FAFC;border-top:1px solid #E2E8F0;}
.yt-mod-submit{background:linear-gradient(135deg,#DC2626,#EF4444);color:#fff;border:none;border-radius:9px;padding:9px 20px;font-weight:700;font-size:.84rem;cursor:pointer;}
.yt-mod-cancel{background:#F1F5F9;color:#64748B;border:none;border-radius:9px;padding:9px 16px;font-weight:700;font-size:.84rem;cursor:pointer;}
</style>

<div class="yt-page">

@if(session('success'))
<div class="yt-alert">
    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#10B981" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="yt-header">
    <div class="yt-header-left">
        <div class="yt-header-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><path d="M23.5 6.2s-.3-2-1.2-2.8c-1.1-1.2-2.4-1.2-3-1.3C16.6 2 12 2 12 2s-4.6 0-7.3.1c-.6.1-1.9.1-3 1.3C.8 4.2.5 6.2.5 6.2S.2 8.5.2 10.8v2.1c0 2.3.3 4.6.3 4.6s.3 2 1.2 2.8c1.1 1.2 2.6 1.1 3.3 1.2C7.2 21.7 12 21.8 12 21.8s4.6 0 7.3-.2c.6-.1 1.9-.1 3-1.3.9-.8 1.2-2.8 1.2-2.8s.3-2.3.3-4.6v-2.1c0-2.3-.3-4.6-.3-4.6zM9.7 15.5V8.4l8.1 3.6-8.1 3.5z"/></svg>
        </div>
        <div class="yt-header-text">
            <h1>YouTube Videos</h1>
            <p>Manage video gallery for boat & event pages</p>
        </div>
    </div>
    <div style="position:relative;z-index:1;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:10px;padding:7px 14px;font-size:.78rem;font-weight:700;color:#fff;">
        {{ $videos->total() }} videos
    </div>
</div>

<div class="yt-layout">

    {{-- Add Video Form --}}
    <div>
        <div class="yt-form-card">
            <div class="yt-form-head">
                <div class="yt-form-head-icon">
                    <svg width="14" height="14" fill="#fff" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>
                </div>
                <div class="yt-form-title">Add New Video</div>
            </div>
            <div class="yt-form-body">
                <form action="{{ route('youtube-videos.store') }}" method="POST">
                    @csrf
                    <div class="yt-field">
                        <label class="yt-label">Product (Optional)</label>
                        <select name="product_id" class="yt-input">
                            <option value="">— All / General —</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ Str::limit($p->name,40) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="yt-field">
                        <label class="yt-label">Video Title</label>
                        <input type="text" name="title" class="yt-input" placeholder="e.g. Event Boat Varanasi">
                    </div>
                    <div class="yt-field">
                        <label class="yt-label">YouTube URL <span style="color:#DC2626;">*</span></label>
                        <input type="url" name="youtube_url" class="yt-input" required placeholder="https://youtu.be/...">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div class="yt-field">
                            <label class="yt-label">Sort Order</label>
                            <input type="number" name="sort_order" class="yt-input" value="0">
                        </div>
                        <div class="yt-field">
                            <label class="yt-label">Status</label>
                            <select name="status" class="yt-input">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="yt-submit">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14"/></svg>
                        Add Video
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Videos Grid --}}
    <div>
        <div class="yt-card">
            <div class="yt-card-head">
                <div class="yt-card-title">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path stroke="#DC2626" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke="#DC2626" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    All Videos
                </div>
                <span style="font-size:.73rem;color:#64748B;">{{ $videos->total() }} total</span>
            </div>

            @if($videos->isNotEmpty())
            <div class="yt-grid">
                @foreach($videos as $v)
                <div class="yt-vid-card {{ $v->status === 'inactive' ? 'inactive' : '' }}">
                    {{-- Thumbnail --}}
                    <div class="yt-thumb">
                        @if($v->video_id)
                        <img src="https://img.youtube.com/vi/{{ $v->video_id }}/hqdefault.jpg"
                             alt="{{ $v->title }}"
                             onerror="this.src='https://img.youtube.com/vi/{{ $v->video_id }}/default.jpg'">
                        @else
                        <div style="width:100%;height:100%;background:#1a1a1a;display:flex;align-items:center;justify-content:center;">
                            <svg width="32" height="32" fill="#555" viewBox="0 0 24 24"><path d="M23.5 6.2s-.3-2-1.2-2.8c-1.1-1.2-2.4-1.2-3-1.3C16.6 2 12 2 12 2s-4.6 0-7.3.1c-.6.1-1.9.1-3 1.3C.8 4.2.5 6.2.5 6.2S.2 8.5.2 10.8v2.1c0 2.3.3 4.6.3 4.6s.3 2 1.2 2.8c1.1 1.2 2.6 1.1 3.3 1.2C7.2 21.7 12 21.8 12 21.8s4.6 0 7.3-.2c.6-.1 1.9-.1 3-1.3.9-.8 1.2-2.8 1.2-2.8s.3-2.3.3-4.6v-2.1c0-2.3-.3-4.6-.3-4.6zM9.7 15.5V8.4l8.1 3.6-8.1 3.5z"/></svg>
                        </div>
                        @endif
                        <div class="yt-play">
                            <div class="yt-play-btn">
                                <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                        <div class="yt-status-dot {{ $v->status === 'active' ? 'yt-status-on' : 'yt-status-off' }}"></div>
                        <div class="yt-order">#{{ $v->sort_order }}</div>
                    </div>
                    {{-- Body --}}
                    <div class="yt-vid-body">
                        <div class="yt-vid-title" title="{{ $v->title ?? $v->youtube_url }}">
                            {{ $v->title ?? Str::limit($v->youtube_url, 30) }}
                        </div>
                        <div class="yt-vid-product">
                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24"><path stroke="#94A3B8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                            {{ $v->product->name ?? 'General' }}
                        </div>
                        <div class="yt-vid-actions">
                            <button class="yt-act yt-act-edit" data-bs-toggle="modal" data-bs-target="#editVideo{{ $v->id }}">
                                <svg fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <form action="{{ route('youtube-videos.toggle', $v) }}" method="POST" style="display:contents;">
                                @csrf
                                <button type="submit" class="yt-act {{ $v->status==='active'?'yt-act-tog-on':'yt-act-tog-off' }}">
                                    {{ $v->status==='active'?'On':'Off' }}
                                </button>
                            </form>
                            <form action="{{ route('youtube-videos.destroy', $v) }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete this video?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="yt-act yt-act-del">
                                    <svg fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade yt-modal" id="editVideo{{ $v->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Video</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('youtube-videos.update', $v) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="yt-field">
                                        <label class="yt-label">Product</label>
                                        <select name="product_id" class="yt-input">
                                            <option value="">— General —</option>
                                            @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ $v->product_id==$p->id?'selected':'' }}>{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="yt-field">
                                        <label class="yt-label">Title</label>
                                        <input type="text" name="title" class="yt-input" value="{{ $v->title }}">
                                    </div>
                                    <div class="yt-field">
                                        <label class="yt-label">YouTube URL <span style="color:#DC2626;">*</span></label>
                                        <input type="url" name="youtube_url" class="yt-input" value="{{ $v->youtube_url }}" required>
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                        <div class="yt-field">
                                            <label class="yt-label">Sort Order</label>
                                            <input type="number" name="sort_order" class="yt-input" value="{{ $v->sort_order }}">
                                        </div>
                                        <div class="yt-field">
                                            <label class="yt-label">Status</label>
                                            <select name="status" class="yt-input">
                                                <option value="active" {{ $v->status==='active'?'selected':'' }}>Active</option>
                                                <option value="inactive" {{ $v->status==='inactive'?'selected':'' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="yt-mod-cancel" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="yt-mod-submit">Update Video</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($videos->hasPages())
            <div class="yt-pag">{{ $videos->links() }}</div>
            @endif

            @else
            <div class="yt-empty">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p style="font-weight:700;color:#374151;margin:0 0 5px;">No videos added yet</p>
                <p style="font-size:.82rem;margin:0;">Add your first YouTube video using the form on the left</p>
            </div>
            @endif
        </div>
    </div>

</div>
</div>
@endsection
