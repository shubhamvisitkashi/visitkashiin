@extends('admin.layouts.app')
@section('content')

<style>
/* ── Product Form Design System ─────────────────────────────── */
:root {
  --h-bg:        #EEF2FF;
  --h-card:      #FFFFFF;
  --h-border:    #E2E8F0;
  --h-shadow:    0 1px 3px rgba(15,23,42,.06), 0 4px 16px rgba(15,23,42,.04);
  --h-shadow-md: 0 4px 24px rgba(15,23,42,.10);
  --h-text:      #0F172A;
  --h-sub:       #475569;
  --h-muted:     #94A3B8;
  --h-indigo:    #4F46E5;
  --h-emerald:   #10B981;
  --h-amber:     #F59E0B;
  --h-rose:      #EF4444;
  --h-sky:       #0EA5E9;
  --h-violet:    #7C3AED;
  --h-r:         14px;
  --h-t:         0.2s ease;
}

.prd-page { background: var(--h-bg); min-height: 100vh; padding: 24px; }

/* ── Header ── */
.prd-header {
  background: linear-gradient(135deg, var(--h-indigo) 0%, var(--h-violet) 100%);
  border-radius: var(--h-r);
  padding: 22px 28px;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 24px;
  position: relative; overflow: hidden;
}
.prd-header::before {
  content: '';
  position: absolute; top: -40px; right: -40px;
  width: 180px; height: 180px;
  background: rgba(255,255,255,.06); border-radius: 50%;
}
.prd-header-title { position: relative; z-index: 1; }
.prd-header-title h1 { color: #fff; font-size: 1.4rem; font-weight: 700; margin: 0; }
.prd-header-title p  { color: rgba(255,255,255,.72); font-size: .82rem; margin: .25rem 0 0; }
.prd-header-actions  { position: relative; z-index: 1; display: flex; gap: 10px; }

/* ── Layout ── */
.prd-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }
@media(max-width:1100px) { .prd-layout { grid-template-columns: 1fr; } }

/* ── Section Cards ── */
.prd-section {
  background: var(--h-card);
  border-radius: var(--h-r);
  border: 1px solid var(--h-border);
  box-shadow: var(--h-shadow);
  margin-bottom: 20px; overflow: hidden;
  transition: box-shadow var(--h-t);
}
.prd-section:hover { box-shadow: var(--h-shadow-md); }

.prd-section-head {
  display: flex; align-items: center; gap: 12px;
  padding: 15px 22px;
  border-bottom: 1px solid var(--h-border);
  background: linear-gradient(90deg, rgba(79,70,229,.04) 0%, transparent 100%);
}
.prd-section-icon {
  width: 34px; height: 34px;
  background: var(--section-color, var(--h-indigo));
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.prd-section-icon svg, .prd-section-icon i[data-feather] { width:16px; height:16px; stroke:#fff; }
.prd-section-label { font-size: .85rem; font-weight: 700; color: var(--h-text); }
.prd-section-desc  { font-size: .73rem; color: var(--h-muted); margin: 1px 0 0; }
.prd-section-body  { padding: 22px; }

/* ── Form Fields ── */
.prd-field label {
  font-size: .76rem; font-weight: 600; color: var(--h-sub);
  text-transform: uppercase; letter-spacing: .4px;
  margin-bottom: 6px; display: block;
}
.prd-field label .req { color: var(--h-rose); margin-left: 2px; }

.prd-field .form-control,
.prd-field select.form-control {
  border: 1.5px solid var(--h-border);
  border-radius: 9px;
  padding: 9px 13px;
  font-size: .875rem; color: var(--h-text);
  transition: border-color var(--h-t), box-shadow var(--h-t);
  background: #FAFBFF;
}
.prd-field .form-control:focus,
.prd-field select.form-control:focus {
  border-color: var(--h-indigo);
  box-shadow: 0 0 0 3px rgba(79,70,229,.12);
  background: #fff; outline: none;
}

.prd-field .input-wrap { position: relative; }
.prd-field .input-wrap .form-control { padding-left: 38px; }
.prd-field .field-icon {
  position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
  color: var(--h-muted); pointer-events: none;
  display: flex; align-items: center;
}
.prd-field .field-icon svg { width:15px; height:15px; }
.prd-field .field-icon.symbol { font-size:.88rem; font-weight:700; }

/* ── Price badges ── */
.price-badge {
  font-size:.68rem; font-weight:600;
  padding: 2px 8px; border-radius: 20px;
  margin-left: 6px; vertical-align: middle;
}
.price-badge.base { background:#EEF2FF; color:var(--h-indigo); }
.price-badge.disc { background:#D1FAE5; color:#065F46; }

/* ── Dropzone ── */
.prd-dropzone-wrap .dropzone {
  border: 2px dashed var(--h-border) !important;
  border-radius: 12px !important;
  background: #FAFBFF !important;
  min-height: 150px !important;
  transition: border-color var(--h-t), background var(--h-t) !important;
}
.prd-dropzone-wrap .dropzone:hover,
.prd-dropzone-wrap .dropzone.dz-drag-hover {
  border-color: var(--h-indigo) !important;
  background: #F5F3FF !important;
}
.prd-dropzone-wrap .dz-message { padding: 30px 24px !important; text-align: center; }

/* ── Char counter ── */
.char-counter { font-size:.7rem; color:var(--h-muted); float:right; margin-top:4px; }
.char-counter.warn { color:var(--h-amber); }
.char-counter.over { color:var(--h-rose); font-weight:600; }

/* ── SERP preview ── */
.serp-label {
  font-size:.72rem; font-weight:700; color:var(--h-muted);
  text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px;
}
.serp-box {
  background:#fff; border:1px solid var(--h-border);
  border-radius:10px; padding:14px 16px;
}
.serp-url   { font-size:.72rem; color:#188038; margin-bottom:2px; }
.serp-title { font-size:.95rem; color:#1a0dab; font-weight:600; line-height:1.3; }
.serp-desc  { font-size:.8rem; color:#4d5156; margin-top:4px; line-height:1.5; }

/* ── Sidebar ── */
.prd-sidebar { position:sticky; top:80px; }

.prd-action-card {
  background: var(--h-card);
  border-radius: var(--h-r);
  border: 1px solid var(--h-border);
  box-shadow: var(--h-shadow); overflow:hidden;
  margin-bottom:16px;
}
.prd-action-head {
  padding:13px 18px;
  background:linear-gradient(90deg,rgba(79,70,229,.06) 0%,transparent 100%);
  border-bottom:1px solid var(--h-border);
  font-size:.82rem; font-weight:700; color:var(--h-text);
  display:flex; align-items:center; gap:8px;
}
.prd-action-head svg { width:14px; height:14px; stroke:var(--h-indigo); }
.prd-action-body { padding:18px; }

.btn-prd-save {
  display:block; width:100%;
  background:linear-gradient(135deg,var(--h-indigo) 0%,var(--h-violet) 100%);
  color:#fff; border:none; border-radius:9px;
  padding:11px; font-size:.875rem; font-weight:700;
  cursor:pointer; transition:opacity var(--h-t),transform var(--h-t);
  text-align:center; margin-bottom:10px;
}
.btn-prd-save:hover { opacity:.88; transform:translateY(-1px); color:#fff; }

.btn-prd-back {
  display:block; width:100%; background:transparent;
  color:var(--h-sub); border:1.5px solid var(--h-border);
  border-radius:9px; padding:9px; font-size:.82rem; font-weight:600;
  cursor:pointer; transition:background var(--h-t),border-color var(--h-t);
  text-align:center; text-decoration:none;
}
.btn-prd-back:hover { background:var(--h-bg); border-color:var(--h-muted); color:var(--h-text); }

/* ── Checklist ── */
.prd-checklist { list-style:none; padding:0; margin:16px 0 0; }
.prd-checklist li {
  display:flex; align-items:center; gap:9px;
  font-size:.78rem; color:var(--h-sub);
  padding:5px 0; border-bottom:1px solid rgba(226,232,240,.5);
}
.prd-checklist li:last-child { border-bottom:none; }
.chk-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; transition:background .3s; }
.chk-dot.done    { background:var(--h-emerald); }
.chk-dot.pending { background:var(--h-border); }

/* ── Tips card ── */
.prd-tip-card {
  background:linear-gradient(135deg,#FFF7ED 0%,#FEF3C7 100%);
  border:1px solid #FDE68A; border-radius:var(--h-r); padding:16px;
}
.prd-tip-card .tip-head {
  display:flex; align-items:center; gap:7px;
  font-size:.8rem; font-weight:700; color:#92400E; margin-bottom:8px;
}
.prd-tip-card .tip-head svg { width:13px; height:13px; stroke:#D97706; }
.prd-tip-card ul { list-style:none; padding:0; margin:0; }
.prd-tip-card ul li {
  font-size:.73rem; color:#78350F; padding:3px 0;
  display:flex; align-items:flex-start; gap:6px;
}
.prd-tip-card ul li::before { content:'•'; color:#F59E0B; flex-shrink:0; margin-top:1px; }

/* ── Mobile header btn ── */
.btn-header-back {
  background:rgba(255,255,255,.18); color:#fff;
  border:1px solid rgba(255,255,255,.35); border-radius:8px;
  padding:8px 16px; font-size:.82rem; font-weight:600;
  text-decoration:none; transition:background var(--h-t);
  display:inline-flex; align-items:center; gap:6px;
}
.btn-header-back:hover { background:rgba(255,255,255,.28); color:#fff; }
.btn-header-back svg  { width:14px; height:14px; stroke:#fff; }
</style>

<div class="prd-page">

  @isset($edit_data)
    <form id="valid_form" method="POST" action="{{route('product.update', $edit_data->id)}}" enctype="multipart/form-data">
    @method('PUT')
  @else
    <form id="valid_form" method="POST" action="{{route('product.store')}}" enctype="multipart/form-data">
  @endisset
    @csrf

    {{-- ── Page Header ─────────────────────────────────── --}}
    <div class="prd-header">
      <div class="prd-header-title">
        <h1>
          <i data-feather="package" style="width:20px;height:20px;stroke:#fff;display:inline;vertical-align:sub;margin-right:8px;"></i>
          {{ $page_title }}
        </h1>
        <p>{{ isset($edit_data) ? 'Update product details and SEO settings' : 'Fill in the details to publish a new listing' }}</p>
      </div>
      <div class="prd-header-actions">
        <a href="{{ route('product.index') }}" class="btn-header-back">
          <i data-feather="arrow-left"></i> Back
        </a>
      </div>
    </div>

    <div class="prd-layout">

      {{-- ── LEFT COLUMN ──────────────────────────────── --}}
      <div class="prd-main">

        {{-- SECTION 1: Basic Info --}}
        <div class="prd-section">
          <div class="prd-section-head">
            <div class="prd-section-icon"><i data-feather="info"></i></div>
            <div>
              <div class="prd-section-label">Basic Information</div>
              <div class="prd-section-desc">Category, title and pricing</div>
            </div>
          </div>
          <div class="prd-section-body">
            <div class="row g-3">
              <div class="col-md-6 prd-field">
                <label for="category_id">Category <span class="req">*</span></label>
                <select class="form-control js-example-basic-single" name="category_id" id="category_id" onchange="getSubCategory()" required>
                  <option value="" selected disabled>Select Category</option>
                  @foreach ($category_list as $category_data)
                    <option value="{{$category_data->id}}" @if(isset($edit_data) && $category_data->id == $edit_data->category_id) selected @endif>{{$category_data->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 prd-field">
                <label for="subcategory_id">Sub Category</label>
                <select class="form-control js-example-basic-single" name="subcategory_id" id="subcategory_id">
                  <option value="" selected disabled>Select Sub Category</option>
                </select>
              </div>
              <div class="col-md-12 prd-field">
                <label for="name">Product Title <span class="req">*</span></label>
                <div class="input-wrap">
                  <span class="field-icon"><i data-feather="type"></i></span>
                  <input id="name" class="form-control" type="text" name="name"
                         placeholder="e.g. Morning Ganga Aarti Boat Ride in Varanasi"
                         @isset($edit_data)value="{{$edit_data->name}}"@endisset required>
                </div>
              </div>
              <div class="col-md-6 prd-field">
                <label for="base_price">
                  Base Price <span class="req">*</span>
                  <span class="price-badge base">MRP</span>
                </label>
                <div class="input-wrap">
                  <span class="field-icon symbol" style="color:var(--h-indigo);">₹</span>
                  <input id="base_price" class="form-control" type="number" step="0.01" name="base_price"
                         placeholder="0.00"
                         @isset($edit_data)value="{{$edit_data->base_price}}" @else value="0.00" @endisset required>
                </div>
              </div>
              <div class="col-md-6 prd-field">
                <label for="discounted_price">
                  Discounted Price <span class="req">*</span>
                  <span class="price-badge disc">Sale</span>
                </label>
                <div class="input-wrap">
                  <span class="field-icon symbol" style="color:var(--h-emerald);">₹</span>
                  <input id="discounted_price" class="form-control" type="number" step="0.01" name="discounted_price"
                         placeholder="0.00"
                         @isset($edit_data)value="{{$edit_data->discounted_price}}"@endisset required>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- SECTION 2: Media --}}
        <div class="prd-section">
          <div class="prd-section-head">
            <div class="prd-section-icon" style="background:var(--h-sky);"><i data-feather="image"></i></div>
            <div style="flex:1;">
              <div class="prd-section-label">Product Images</div>
              <div class="prd-section-desc">First image = Cover · Drag to reorder · Max 2 MB · PNG, JPG, WEBP</div>
            </div>
            <span style="font-size:.72rem;font-weight:600;background:#EFF6FF;color:#0369A1;padding:4px 10px;border-radius:20px;border:1px solid #BAE6FD;white-space:nowrap;">
              ⭐ 1st image = Cover
            </span>
          </div>
          <div class="prd-section-body">

            {{-- Existing images — draggable grid --}}
            <div id="prd-img-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;margin-bottom:16px;min-height:10px;">
            </div>

            {{-- Dropzone --}}
            <div class="prd-dropzone-wrap">
              <div class="needsclick dropzone" id="document-dropzone">
                <div class="dz-message">
                  <div style="margin-bottom:10px;">
                    <i data-feather="upload-cloud" style="width:38px;height:38px;stroke:var(--h-indigo);"></i>
                  </div>
                  <strong style="font-size:.88rem;color:var(--h-text);">Click or drop new images here</strong>
                  <p style="font-size:.78rem;color:var(--h-muted);margin:.4rem 0 0;">PNG, JPG, WEBP · Max 2 MB each</p>
                </div>
              </div>
            </div>

            <p style="font-size:.72rem;color:var(--h-muted);margin-top:8px;"><i data-feather="info" style="width:12px;height:12px;vertical-align:middle;margin-right:3px;"></i>Drag the thumbnails above to reorder. The <strong>first image</strong> will be used as the product cover.</p>
          </div>
        </div>

        <style>
        /* ── Image Grid ── */
        #prd-img-grid { min-height: 20px; }
        .prd-img-item {
          position: relative; border-radius: 10px; overflow: hidden;
          aspect-ratio: 4/3; cursor: grab; background: #F1F5F9;
          border: 2.5px solid transparent; transition: border-color .2s, box-shadow .2s;
          user-select: none;
        }
        .prd-img-item.is-cover { border-color: #F59E0B; box-shadow: 0 0 0 2px #FDE68A; }
        .prd-img-item.dragging { opacity: .45; cursor: grabbing; }
        .prd-img-item.drag-over { border-color: #4F46E5; border-style: dashed; }
        .prd-img-item img { width:100%;height:100%;object-fit:cover;display:block;pointer-events:none; }
        /* Cover badge */
        .prd-img-cover-badge {
          position:absolute;top:6px;left:6px;
          background:linear-gradient(135deg,#F59E0B,#D97706);
          color:#fff;font-size:.6rem;font-weight:800;
          padding:2px 7px;border-radius:20px;letter-spacing:.04em;
          pointer-events:none; text-transform:uppercase;
        }
        /* Delete btn */
        .prd-img-del {
          position:absolute;top:5px;right:5px;
          width:24px;height:24px;border-radius:50%;
          background:rgba(220,38,38,.85);border:none;color:#fff;
          font-size:14px;line-height:1;cursor:pointer;
          display:flex;align-items:center;justify-content:center;
          opacity:0;transition:opacity .18s;
        }
        .prd-img-item:hover .prd-img-del { opacity:1; }
        /* Drag handle hint */
        .prd-img-drag-hint {
          position:absolute;bottom:0;left:0;right:0;
          background:rgba(0,0,0,.5);color:rgba(255,255,255,.8);
          font-size:.6rem;text-align:center;padding:3px;
          opacity:0;transition:opacity .18s;
          pointer-events:none;
        }
        .prd-img-item:hover .prd-img-drag-hint { opacity:1; }
        /* SortableJS ghost */
        .sortable-ghost { opacity:.25; }
        .sortable-drag { opacity:.9; box-shadow:0 8px 24px rgba(0,0,0,.2); }
        </style>

        {{-- SECTION 3: Location & Links --}}
        <div class="prd-section">
          <div class="prd-section-head">
            <div class="prd-section-icon" style="background:var(--h-emerald);"><i data-feather="map-pin"></i></div>
            <div>
              <div class="prd-section-label">Location &amp; Media Links</div>
              <div class="prd-section-desc">Address, Google Maps embed URL, and YouTube video</div>
            </div>
          </div>
          <div class="prd-section-body">
            <div class="row g-3">
              <div class="col-md-4 prd-field">
                <label for="address">Address</label>
                <div class="input-wrap">
                  <span class="field-icon"><i data-feather="map-pin"></i></span>
                  <input id="address" class="form-control" type="text" name="address"
                         placeholder="e.g. Dashashwamedh Ghat, Varanasi"
                         @isset($edit_data)value="{{$edit_data->address}}"@endisset>
                </div>
              </div>
              <div class="col-md-4 prd-field">
                <label for="map_location">
                  Map Location
                  <small style="font-weight:400;text-transform:none;font-size:.7rem;letter-spacing:0;">(iframe src)</small>
                </label>
                <div class="input-wrap">
                  <span class="field-icon"><i data-feather="navigation"></i></span>
                  <input id="map_location" class="form-control" type="text" name="map_location"
                         placeholder="Paste Google Maps embed URL"
                         @isset($edit_data)value="{{$edit_data->map_location}}"@endisset>
                </div>
              </div>
              <div class="col-md-4 prd-field">
                <label for="youtube_link">YouTube Link</label>
                <div class="input-wrap">
                  <span class="field-icon"><i data-feather="youtube"></i></span>
                  <input id="youtube_link" class="form-control" type="text" name="youtube_link"
                         placeholder="https://youtube.com/watch?v=..."
                         @isset($edit_data)value="{{$edit_data->youtube_link}}"@endisset>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- SECTION 4: Description --}}
        <div class="prd-section">
          <div class="prd-section-head">
            <div class="prd-section-icon" style="background:var(--h-violet);"><i data-feather="file-text"></i></div>
            <div>
              <div class="prd-section-label">Description</div>
              <div class="prd-section-desc">Full product description shown on the listing page</div>
            </div>
          </div>
          <div class="prd-section-body prd-field">
            <label for="editor">Content <span class="req">*</span></label>
            <textarea name="description" class="form-control" id="editor" rows="10"
                      placeholder="Describe the experience in detail…" required>@isset($edit_data){{$edit_data->description}}@endisset</textarea>
          </div>
        </div>

        {{-- SECTION 5: SEO --}}
        <div class="prd-section">
          <div class="prd-section-head">
            <div class="prd-section-icon" style="background:var(--h-amber);"><i data-feather="search"></i></div>
            <div>
              <div class="prd-section-label">SEO Settings</div>
              <div class="prd-section-desc">Optimise how this page appears in Google search results</div>
            </div>
          </div>
          <div class="prd-section-body">
            <div class="row g-3">
              <div class="col-md-6 prd-field">
                <label for="meta_title">
                  Meta Title
                  <small style="font-weight:400;text-transform:none;font-size:.7rem;letter-spacing:0;">· 50–60 chars ideal</small>
                </label>
                <input id="meta_title" class="form-control" type="text" name="meta_title" maxlength="80"
                       placeholder="e.g. Morning Boat Ride in Varanasi – Visit Kashi"
                       @isset($edit_data)value="{{$edit_data->meta_title}}"@endisset>
                <span class="char-counter" id="meta_title_count">0 / 60</span>
              </div>
              <div class="col-md-6 prd-field">
                <label for="meta_keyword">Meta Keywords</label>
                <input id="meta_keyword" class="form-control" type="text" name="meta_keyword"
                       placeholder="boat ride varanasi, ganga ghat, sunrise boat"
                       @isset($edit_data)value="{{$edit_data->meta_keyword}}"@endisset>
                <small style="font-size:.7rem;color:var(--h-muted);">Comma-separated keywords</small>
              </div>
              <div class="col-md-12 prd-field">
                <label for="meta_description">
                  Meta Description
                  <small style="font-weight:400;text-transform:none;font-size:.7rem;letter-spacing:0;">· 150–160 chars ideal</small>
                </label>
                <textarea name="meta_description" class="form-control" id="meta_description" rows="3" maxlength="200"
                          placeholder="A concise summary shown in Google results (150–160 characters)…">@isset($edit_data){{$edit_data->meta_description}}@endisset</textarea>
                <span class="char-counter" id="meta_desc_count">0 / 155</span>
              </div>
            </div>

            {{-- SERP Preview --}}
            <div class="mt-4">
              <div class="serp-label">Google Preview</div>
              <div class="serp-box">
                <div class="serp-url">visitkashi.in › <span id="serp_slug">product-slug</span></div>
                <div class="serp-title" id="serp_title">Meta title will appear here</div>
                <div class="serp-desc" id="serp_desc">Meta description will appear here. Keep it between 150–160 characters for best display in search results.</div>
              </div>
            </div>

          </div>
        </div>

        {{-- Mobile Save --}}
        <div class="d-lg-none mb-4 d-flex gap-2">
          <button type="submit" class="btn-prd-save" style="margin-bottom:0;">
            <i data-feather="save" style="width:15px;height:15px;stroke:#fff;vertical-align:middle;margin-right:5px;"></i>
            {{ isset($edit_data) ? 'Update Product' : 'Save Product' }}
          </button>
          <a href="{{ route('product.index') }}" class="btn-prd-back">Cancel</a>
        </div>

      </div>{{-- /prd-main --}}

      {{-- ── SIDEBAR ──────────────────────────────────── --}}
      <div class="prd-sidebar d-none d-lg-block">

        <div class="prd-action-card">
          <div class="prd-action-head">
            <i data-feather="send"></i>
            Publish
          </div>
          <div class="prd-action-body">
            <button type="submit" class="btn-prd-save">
              <i data-feather="save" style="width:15px;height:15px;stroke:#fff;vertical-align:middle;margin-right:5px;"></i>
              {{ isset($edit_data) ? 'Update Product' : 'Save &amp; Publish' }}
            </button>
            <a href="{{ route('product.index') }}" class="btn-prd-back">
              <i data-feather="x" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i> Cancel
            </a>

            {{-- Checklist --}}
            <ul class="prd-checklist">
              <li><span class="chk-dot pending" id="chk-cat"></span> Category selected</li>
              <li><span class="chk-dot pending" id="chk-title"></span> Title entered</li>
              <li><span class="chk-dot pending" id="chk-price"></span> Price set</li>
              <li><span class="chk-dot pending" id="chk-img"></span> Images uploaded</li>
              <li><span class="chk-dot pending" id="chk-desc"></span> Description added</li>
              <li><span class="chk-dot pending" id="chk-seo"></span> SEO meta filled</li>
            </ul>
          </div>
        </div>

        {{-- Tips --}}
        <div class="prd-tip-card">
          <div class="tip-head">
            <i data-feather="zap"></i> SEO Tips
          </div>
          <ul>
            <li>Include the destination in the title (e.g. "Varanasi Boat Ride")</li>
            <li>Meta title: 50–60 characters for best display</li>
            <li>Meta description: 150–160 chars with a call-to-action</li>
            <li>First image becomes the search result thumbnail</li>
            <li>Use commas to separate meta keywords</li>
          </ul>
        </div>

      </div>{{-- /prd-sidebar --}}

    </div>{{-- /prd-layout --}}

  </form>

  {{-- Hidden clone for legacy multi-upload (kept for compatibility) --}}
  <div class="clone hide" style="display:none">
    <div class="imageDivField control-group lst input-group mt-3">
      <div class="input-group">
        <input class="form-control" type="file" name="images[]" accept="image/*" required>
        <span class="input-group-text input-group-addon btn btn-outline-danger danger1">
          <i class="btn-icon-prepend" data-feather="x-circle"></i>
        </span>
      </div>
    </div>
  </div>

</div>{{-- /prd-page --}}

<script>
  /* ── Sub-category AJAX ── */
  @isset($edit_data)
    $(function(){ getSubCategory(); });
  @endisset

  function getSubCategory(){
    var selectedSub = "";
    @isset($edit_data)
      selectedSub = "{{ $edit_data->subcategory_id }}";
    @endisset
    $('#subcategory_id').empty();
    var catId = $('#category_id').val();
    $.get("{{ route('getSubCategory', '') }}/" + catId, function(data){
      $('#subcategory_id').append("<option value='' selected disabled>Select Sub Category</option>");
      $.each(data, function(k, v){
        var sel = (selectedSub == v.id) ? ' selected' : '';
        $('#subcategory_id').append('<option value="' + v.id + '"' + sel + '>' + v.name + '</option>');
      });
    });
  }

  $(".add_image_field").click(function(){
    $(".image_fields").before($(".clone").html());
  });
  $("body").on("click", ".danger1", function(){
    $(this).parents(".imageDivField").remove();
  });

  /* ── Char counters ── */
  function initCounter(inputId, counterId, max){
    var el = document.getElementById(inputId);
    var counter = document.getElementById(counterId);
    if (!el || !counter) return;
    function refresh(){
      var n = el.value.length;
      counter.textContent = n + ' / ' + max;
      counter.className = 'char-counter' + (n > max ? ' over' : n > max * 0.88 ? ' warn' : '');
    }
    el.addEventListener('input', refresh);
    refresh();
  }
  initCounter('meta_title', 'meta_title_count', 60);
  initCounter('meta_description', 'meta_desc_count', 155);

  /* ── SERP preview ── */
  function updateSerp(){
    var title = document.getElementById('meta_title').value;
    var desc  = document.getElementById('meta_description').value;
    var name  = document.getElementById('name').value;
    document.getElementById('serp_title').textContent = title || name || 'Meta title will appear here';
    document.getElementById('serp_desc').textContent  = desc  || 'Meta description will appear here.';
    if (name) {
      document.getElementById('serp_slug').textContent =
        name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
  }
  ['meta_title', 'meta_description', 'name'].forEach(function(id){
    var el = document.getElementById(id);
    if (el) el.addEventListener('input', updateSerp);
  });
  updateSerp();

  /* ── Sidebar checklist ── */
  function dot(id, ok){
    var el = document.getElementById(id);
    if (el) el.className = 'chk-dot ' + (ok ? 'done' : 'pending');
  }
  function refreshChecklist(){
    dot('chk-cat',   !!document.getElementById('category_id').value);
    dot('chk-title', (document.getElementById('name').value || '').trim().length > 2);
    var bp = parseFloat(document.getElementById('base_price').value);
    dot('chk-price', !isNaN(bp) && bp > 0);
    var ed = document.getElementById('editor');
    dot('chk-desc',  ed && ed.value.length > 20);
    var mt = (document.getElementById('meta_title').value || '').trim();
    var md = document.getElementById('meta_description');
    dot('chk-seo',   mt.length > 5 && md && md.value.trim().length > 10);
  }
  setInterval(refreshChecklist, 600);

  document.addEventListener('DOMContentLoaded', function(){
    refreshChecklist();
    updateSerp();
    if (typeof feather !== 'undefined') feather.replace();
  });
</script>

@endsection
@push('scripts')
{{-- SortableJS for drag-to-reorder --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
/* ── Image gallery state ── */
var prdImages = [];   // [{name:'file.jpg', url:'https://...'}]
var uploadedMap = {}; // file.name -> server filename

/* ── Render image grid ── */
function prdRenderGrid() {
    var grid = document.getElementById('prd-img-grid');
    grid.innerHTML = '';
    prdImages.forEach(function(img, i) {
        var item = document.createElement('div');
        item.className = 'prd-img-item' + (i === 0 ? ' is-cover' : '');
        item.dataset.name = img.name;
        item.innerHTML = '<img src="' + img.url + '" alt="Product image ' + (i+1) + '">'
            + (i === 0 ? '<div class="prd-img-cover-badge">⭐ Cover</div>' : '')
            + '<button type="button" class="prd-img-del" onclick="prdRemoveImg(\'' + img.name.replace(/'/g,"\\'") + '\')" title="Remove">✕</button>'
            + '<div class="prd-img-drag-hint">☰ drag to reorder</div>';
        grid.appendChild(item);
    });
    prdSyncHiddenInputs();
    dot('chk-img', prdImages.length > 0);
}

/* ── Remove image ── */
function prdRemoveImg(name) {
    prdImages = prdImages.filter(function(i){ return i.name !== name; });
    $('form').find('input[name="images[]"][value="' + name + '"]').remove();
    prdRenderGrid();
}

/* ── Sync hidden inputs with current order ── */
function prdSyncHiddenInputs() {
    $('form').find('input[name="images[]"]').remove();
    prdImages.forEach(function(img) {
        $('form').append('<input type="hidden" name="images[]" value="' + img.name + '">');
    });
}

/* ── SortableJS ── */
document.addEventListener('DOMContentLoaded', function() {
    Sortable.create(document.getElementById('prd-img-grid'), {
        animation: 180,
        ghostClass: 'sortable-ghost',
        dragClass:  'sortable-drag',
        handle:     '.prd-img-item',
        onEnd: function(evt) {
            // Reorder prdImages to match DOM order
            var items = document.querySelectorAll('.prd-img-item');
            var newOrder = [];
            items.forEach(function(el) {
                var n = el.dataset.name;
                var found = prdImages.find(function(x){ return x.name === n; });
                if (found) newOrder.push(found);
            });
            prdImages = newOrder;
            prdRenderGrid(); // re-render to update cover badge
        }
    });

    /* ── Load existing images (edit mode) ── */
    @if (isset($edit_data) && is_array($edit_data->images))
    @foreach ($edit_data->images as $gallery)
    prdImages.push({ name: "{{ $gallery }}", url: "{{ asset('backend/admin/product_images/'.$gallery) }}" });
    @endforeach
    @endif
    prdRenderGrid();
});

/* ── Dropzone — new uploads ── */
Dropzone.options.documentDropzone = {
    dictDefaultMessage: "Click or drop images here",
    url: '{{ route("image.upload") }}',
    maxFilesize: 2,
    acceptedFiles: 'image/*',
    addRemoveLinks: false,
    previewsContainer: false,   // we manage our own grid
    clickable: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    success: function(file, response) {
        uploadedMap[file.name] = response.name;
        prdImages.push({ name: response.name, url: "{{ asset('backend/admin/product_images/') }}/" + response.name });
        prdRenderGrid();
        // Reset dropzone preview
        this.removeFile(file);
    },
    error: function(file, msg) {
        Swal.fire({ icon:'error', title:'Upload failed', text: typeof msg === 'string' ? msg : 'Max 2MB · JPG/PNG/WEBP only', timer:3000, showConfirmButton:false });
        this.removeFile(file);
    },
    complete: function(file) {
        // handled in success/error
    }
};
</script>
@endpush
