@extends('admin.layouts.app')
@section('content')
<style>
:root{
  --t-indigo:#4F46E5;--t-violet:#7C3AED;--t-amber:#F59E0B;
  --t-green:#10B981;--t-sky:#0EA5E9;--t-rose:#EF4444;
  --t-border:#E2E8F0;--t-bg:#F8FAFC;--t-text:#0F172A;--t-sub:#475569;--t-muted:#94A3B8;
  --t-r:14px;--t-s:0 1px 4px rgba(15,23,42,.06),0 4px 16px rgba(15,23,42,.04);
}

/* ── Page ── */
.tb-page{padding:24px;background:#F1F5F9;min-height:100vh;}

/* ── Header ── */
.tb-header{background:linear-gradient(135deg,#4F46E5,#7C3AED);border-radius:var(--t-r);padding:18px 24px;display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;margin-top:50px;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(79,70,229,.25);}
.tb-header::before{content:'🗺️';position:absolute;right:20px;bottom:-10px;font-size:80px;opacity:.08;pointer-events:none;}
.tb-header h1{color:#fff;font-size:1.1rem;font-weight:800;margin:0;}
.tb-header p{color:rgba(255,255,255,.7);font-size:.75rem;margin:.2rem 0 0;}
.tb-back{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.28);border-radius:8px;padding:7px 14px;font-size:.78rem;font-weight:600;text-decoration:none;transition:.2s;position:relative;z-index:1;}
.tb-back:hover{background:rgba(255,255,255,.28);color:#fff;}

/* ── Layout ── */
.tb-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:1100px){.tb-layout{grid-template-columns:1fr;}}

/* ── Card ── */
.tb-card{background:#fff;border-radius:var(--t-r);border:1px solid var(--t-border);box-shadow:var(--t-s);margin-bottom:16px;overflow:hidden;}
.tb-card-head{padding:13px 20px;border-bottom:1px solid var(--t-border);display:flex;align-items:center;gap:10px;background:#FAFBFF;}
.tb-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;}
.tb-card-title{font-size:.88rem;font-weight:700;color:var(--t-text);}
.tb-card-body{padding:18px 20px;}

/* ── Fields ── */
.tb-label{font-size:.69rem;font-weight:700;color:var(--t-sub);text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;}
.tb-label .req{color:var(--t-rose);margin-left:2px;}
.tb-input,.tb-select,.tb-textarea{width:100%;border:1.5px solid var(--t-border);border-radius:9px;padding:9px 13px;font-size:.86rem;color:var(--t-text);background:#FAFBFF;outline:none;transition:.15s;font-family:inherit;}
.tb-input:focus,.tb-select:focus,.tb-textarea:focus{border-color:var(--t-indigo);box-shadow:0 0 0 3px rgba(79,70,229,.1);background:#fff;}
.tb-textarea{resize:vertical;min-height:72px;}
.tb-prefix-wrap{position:relative;}
.tb-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;font-weight:600;color:var(--t-sub);pointer-events:none;}
.tb-input-pl{padding-left:40px !important;}
.tb-rupee-wrap{position:relative;}
.tb-rupee{position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:.82rem;font-weight:600;color:var(--t-sub);pointer-events:none;}
.tb-input-rs{padding-left:26px !important;}

/* ── Service blocks ── */
.tb-svc{border:1.5px solid var(--t-border);border-radius:11px;margin-bottom:10px;overflow:hidden;}
.tb-svc-head{display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--t-bg);border-bottom:1px solid var(--t-border);}
.tb-svc-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.tb-svc-label{font-size:.82rem;font-weight:700;color:var(--t-text);flex:1;}
.tb-svc-badge{font-size:.75rem;font-weight:800;color:var(--t-amber);background:#FEF3C7;padding:2px 10px;border-radius:20px;border:1px solid #FDE68A;}
.tb-svc-body{padding:14px 16px;}

/* ── Expense bar ── */
.tb-exp-bar{display:grid;grid-template-columns:repeat(4,1fr) auto;gap:8px;background:linear-gradient(135deg,#FFFBEB,#FEF3C7);border:1px solid #FDE68A;border-radius:11px;padding:12px 16px;margin-top:14px;align-items:center;}
@media(max-width:600px){
  .tb-exp-bar{grid-template-columns:1fr 1fr;}
  .tb-exp-total{grid-column:1 / -1;}
}

/* ── Hotel row responsive grids (used by JS template) ── */
.tb-hotel-row-grid-1{display:grid;grid-template-columns:160px 1fr 140px;gap:8px;margin-bottom:8px;}
.tb-hotel-row-grid-2{display:grid;grid-template-columns:1fr 1fr 70px 1fr;gap:8px;align-items:end;}

/* ── Rupee prefix — flex input-group (no overlap) ── */
.tb-rupee-wrap{
  display:flex;align-items:stretch;
  border:1.5px solid var(--t-border);border-radius:9px;overflow:hidden;background:#FAFBFF;
  transition:border-color .15s,box-shadow .15s;
}
.tb-rupee-wrap:focus-within{border-color:var(--t-indigo);box-shadow:0 0 0 3px rgba(79,70,229,.1);}
.tb-rupee{
  position:static;transform:none;
  display:flex;align-items:center;padding:0 9px 0 11px;
  font-size:.82rem;font-weight:700;color:var(--t-sub);
  background:#F1F5F9;border-right:1.5px solid var(--t-border);
  white-space:nowrap;flex-shrink:0;pointer-events:none;
}
.tb-input-rs{
  flex:1;min-width:0;
  border:none !important;border-radius:0 !important;
  box-shadow:none !important;outline:none !important;
  padding:9px 11px !important;background:transparent !important;
}
.tb-input-rs:focus{border:none !important;box-shadow:none !important;background:transparent !important;}
.tb-exp-item{text-align:center;}
.tb-exp-item-icon{font-size:.9rem;margin-bottom:2px;}
.tb-exp-item-lbl{font-size:.62rem;color:#92400E;font-weight:600;margin-bottom:3px;}
.tb-exp-item-val{font-size:.95rem;font-weight:800;color:#78350F;}
.tb-exp-total{background:#fff;border-radius:8px;padding:8px 14px;text-align:center;border:1.5px solid #FDE68A;}
.tb-exp-total-lbl{font-size:.62rem;color:#1E40AF;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;}
.tb-exp-total-val{font-size:1.1rem;font-weight:800;color:#1E3A8A;}

/* ── Sidebar ── */
.tb-sidebar{position:sticky;top:80px;}
.tb-sidebar-card{background:#fff;border-radius:var(--t-r);border:1px solid var(--t-border);box-shadow:var(--t-s);padding:18px;margin-bottom:14px;}
.tb-sidebar-title{font-size:.84rem;font-weight:800;color:var(--t-text);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--t-border);}
.tb-amt-row{margin-bottom:12px;}

.tb-balance-box{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid #BFDBFE;border-radius:11px;padding:12px 16px;margin:12px 0;}
.tb-balance-lbl{font-size:.62rem;font-weight:800;color:#1E40AF;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;}
.tb-balance-row{display:flex;align-items:center;justify-content:space-between;}
.tb-balance-amt{font-size:1.8rem;font-weight:800;color:#1E3A8A;}
.tb-badge{font-size:.62rem;font-weight:800;letter-spacing:.04em;padding:3px 9px;border-radius:20px;text-transform:uppercase;}
.tb-badge-due{background:#EF4444;color:#fff;}
.tb-badge-paid{background:#10B981;color:#fff;}
.tb-badge-partial{background:#3B82F6;color:#fff;}

/* ── Profit box ── */
.tb-profit-box{background:linear-gradient(135deg,#F0FDF4,#DCFCE7);border:1px solid #BBF7D0;border-radius:11px;padding:14px 16px;margin-top:14px;}
.tb-profit-row{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;padding:5px 0;}
.tb-profit-row+.tb-profit-row{border-top:1px dashed #BBF7D0;}
.tb-profit-lbl{color:#065F46;}
.tb-profit-val{font-weight:700;color:#14532D;}
.tb-profit-final{font-size:1.1rem !important;font-weight:800 !important;}
.tb-profit-pct{font-size:.72rem;background:#D1FAE5;color:#065F46;font-weight:700;padding:2px 8px;border-radius:20px;}

/* ── Submit ── */
.tb-submit{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:10px;padding:13px 20px;font-size:.92rem;font-weight:700;cursor:pointer;transition:.2s;letter-spacing:.01em;}
.tb-submit:hover{opacity:.9;transform:translateY(-1px);}
.tb-submit svg{width:17px;height:17px;stroke:#fff;}

/* ── Inclusion pills ── */
.incl-grid{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:10px;}
.incl-pill{display:inline-flex;align-items:center;gap:5px;padding:6px 13px;border-radius:20px;border:1.5px solid var(--t-border);background:#fff;font-size:.78rem;font-weight:600;color:#475569;cursor:pointer;user-select:none;transition:all .18s;}
.incl-pill:hover{border-color:var(--t-indigo);color:var(--t-indigo);background:#EEF2FF;}
.incl-pill.active{border-color:var(--t-indigo);background:var(--t-indigo);color:#fff;}
.incl-pill input{display:none;}

/* ── Sticky mobile action bar (hidden on desktop) ── */
.tb-mob-bar{display:none;}

/* ══ MOBILE RESPONSIVE ══ */
@media(max-width:768px){
  .tb-page{padding:10px;padding-bottom:130px;}
  .tb-header{padding:12px 14px;margin-top:58px;margin-bottom:14px;flex-wrap:wrap;gap:8px;}
  .tb-header h1{font-size:.95rem;}
  .tb-header p{font-size:.7rem;}
  .tb-back{padding:6px 10px;font-size:.73rem;}
  .tb-card{margin-bottom:12px;border-radius:12px;}
  .tb-card-head{padding:10px 14px;}
  .tb-card-title{font-size:.82rem;}
  .tb-card-body{padding:12px 14px;}
  .tb-icon{width:26px;height:26px;font-size:.85rem;}
  .tb-label{font-size:.65rem;margin-bottom:4px;}
  .tb-input,.tb-select,.tb-textarea{padding:8px 11px;font-size:.82rem;}
  .tb-svc-body{padding:10px 12px;}
  .tb-svc-head{padding:8px 12px;}
  .tb-sidebar-card{padding:14px;border-radius:12px;margin-bottom:12px;}
  .tb-sidebar-title{font-size:.82rem;margin-bottom:10px;padding-bottom:8px;}
  /* hide in-page submit on mobile — sticky bar handles it */
  .tb-sidebar .tb-submit{display:none;}
  /* hotel row grids stack on mobile */
  .tb-hotel-row-grid-1{grid-template-columns:1fr !important;}
  .tb-hotel-row-grid-2{grid-template-columns:1fr 1fr !important;}
  .tb-hotel-row-grid-2>div:last-child{grid-column:1 / -1;}
  /* rupee wrap smaller on mobile */
  .tb-rupee{padding:0 8px 0 10px;font-size:.78rem;}
  .tb-input-rs{padding:8px 10px !important;font-size:.82rem !important;}
  /* balance/profit boxes */
  .tb-balance-amt{font-size:1.3rem;}
  .tb-profit-final{font-size:.95rem !important;}
  /* payment fields: 2-col compact grid */
  .tb-pay-2col{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
  /* booking amount full-width */
  .tb-booking-amt-row{grid-column:1/-1;}

  /* ── Sticky bottom action bar ── */
  .tb-mob-bar{
    display:flex;
    position:fixed;bottom:58px;left:0;right:0;
    background:#fff;
    border-top:1.5px solid #E2E8F0;
    padding:10px 14px 10px;
    gap:12px;align-items:center;
    z-index:998;
    box-shadow:0 -6px 20px rgba(0,0,0,.10);
  }
  .tb-mob-bar-info{flex:1;min-width:0;}
  .tb-mob-bar-lbl{font-size:.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.04em;line-height:1.2;}
  .tb-mob-bar-amt{font-size:1rem;font-weight:800;color:#1E3A8A;line-height:1.2;white-space:nowrap;}
  .tb-mob-bar-sub{font-size:.68rem;color:#475569;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
  .tb-mob-submit{
    flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED);
    color:#fff;border:none;border-radius:10px;
    padding:11px 18px;font-size:.82rem;font-weight:700;
    cursor:pointer;white-space:nowrap;
    box-shadow:0 4px 14px rgba(79,70,229,.35);
    display:flex;align-items:center;gap:6px;
  }
  .tb-mob-submit svg{width:15px;height:15px;stroke:#fff;}
}

/* ── Alert ── */
.tb-alert{display:flex;align-items:center;gap:10px;border-radius:11px;padding:11px 16px;margin-bottom:18px;font-size:.83rem;font-weight:600;}
.tb-alert-err{background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;}
</style>

<div class="tb-page">

@if($errors->any())
<div class="tb-alert tb-alert-err">
  <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div><strong>Please fix:</strong>
    <ul style="margin:.4rem 0 0;padding-left:16px;font-size:.8rem;">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
</div>
@endif
@if(session('error'))
<div class="tb-alert tb-alert-err">{{ session('error') }}</div>
@endif

{{-- Header --}}
<div class="tb-header">
  <div style="position:relative;z-index:1;">
    <h1>🗺️ New Tour Package Booking</h1>
    <p>Fill in guest details, services & B2B expenses</p>
  </div>
  <div style="display:flex;gap:10px;align-items:center;position:relative;z-index:1;flex-wrap:wrap;">
    <div style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 14px;font-size:.75rem;color:rgba(255,255,255,.9);font-weight:600;">
      🔢 Booking # auto-generated on save
    </div>
    <a href="{{ route('bookings.create-direct') }}" class="tb-back">← Back</a>
  </div>
</div>

<form action="{{ route('tour-booking.store') }}" method="POST" id="tb-form">
@csrf
<div class="tb-layout">

{{-- ════ LEFT ════ --}}
<div>

  {{-- 1. Guest Info --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#EDE9FE;">👤</div>
      <div class="tb-card-title">Guest Information</div>
    </div>
    <div class="tb-card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="tb-label">Guest Name <span class="req">*</span></label>
          <input type="text" name="guest_name" class="tb-input" required placeholder="Full name" value="{{ old('guest_name') }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Mobile <span class="req">*</span></label>
          @include('admin.partials.phone-input', [
              'name'     => 'phone',
              'value'    => old('phone'),
              'required' => true,
              'placeholder' => '9876543210',
          ])
        </div>
        <div class="col-md-3">
          <label class="tb-label">Email</label>
          <input type="email" name="email" class="tb-input" placeholder="email@example.com" value="{{ old('email') }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Lead Source <span class="req">*</span></label>
          <select name="lead_source_id" class="tb-select" required>
            <option value="">Select…</option>
            @foreach($leadSources as $s)
            <option value="{{ $s->id }}" {{ old('lead_source_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="tb-label">Alternate Mobile</label>
          @include('admin.partials.phone-input', [
              'name'        => 'alt_phone',
              'value'       => old('alt_phone'),
              'required'    => false,
              'placeholder' => 'Alt. number',
          ])
        </div>
        <div class="col-md-3">
          <label class="tb-label">Country</label>
          <input type="text" name="country" class="tb-input" placeholder="India" value="{{ old('country','India') }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">State</label>
          <input type="text" name="state" class="tb-input" placeholder="e.g. Uttar Pradesh" value="{{ old('state') }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">City</label>
          <input type="text" name="city" class="tb-input" placeholder="e.g. Varanasi" value="{{ old('city') }}">
        </div>
      </div>
    </div>
  </div>

  {{-- 2. Tour Info --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#EDE9FE;">📅</div>
      <div class="tb-card-title">Tour Package Info</div>
    </div>
    <div class="tb-card-body">
      <div class="row g-3">
        <div class="col-md-5">
          <label class="tb-label">Package Name <span class="req">*</span></label>
          <input type="text" name="package_name" id="tb-pkg-name" class="tb-input" required placeholder="e.g. Kashi Darshan 3D/2N" oninput="tbBuildSummary()" value="{{ old('package_name') }}">
        </div>
        <div class="col-6 col-md-2">
          <label class="tb-label">Adults</label>
          <div style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid var(--t-border);border-radius:9px;padding:5px 8px;">
            <button type="button" onclick="tbAdj('adults',-1)"
                    style="width:28px;height:28px;border-radius:7px;border:1.5px solid #CBD5E1;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;flex-shrink:0;">−</button>
            <span id="tb-adults-dis" style="font-size:1.2rem;font-weight:800;color:var(--t-indigo);min-width:24px;text-align:center;">{{ old('adults',2) }}</span>
            <button type="button" onclick="tbAdj('adults',1)"
                    style="width:28px;height:28px;border-radius:7px;border:1.5px solid var(--t-indigo);background:var(--t-indigo);font-size:1.1rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;flex-shrink:0;">+</button>
          </div>
          <input type="hidden" name="adults" id="tb-adults" value="{{ old('adults',2) }}">
        </div>
        <div class="col-6 col-md-2">
          <label class="tb-label">Children</label>
          <div style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid var(--t-border);border-radius:9px;padding:5px 8px;">
            <button type="button" onclick="tbAdj('children',-1)"
                    style="width:28px;height:28px;border-radius:7px;border:1.5px solid #CBD5E1;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;flex-shrink:0;">−</button>
            <span id="tb-children-dis" style="font-size:1.2rem;font-weight:800;color:var(--t-violet);min-width:24px;text-align:center;">{{ old('children',0) }}</span>
            <button type="button" onclick="tbAdj('children',1)"
                    style="width:28px;height:28px;border-radius:7px;border:1.5px solid var(--t-violet);background:var(--t-violet);font-size:1.1rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;flex-shrink:0;">+</button>
          </div>
          <input type="hidden" name="children" id="tb-children" value="{{ old('children',0) }}">
        </div>
        <div class="col-6 col-md-2">
          <label class="tb-label">Infants</label>
          <div style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid var(--t-border);border-radius:9px;padding:5px 8px;">
            <button type="button" onclick="tbAdj('infants',-1)"
                    style="width:28px;height:28px;border-radius:7px;border:1.5px solid #CBD5E1;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;flex-shrink:0;">−</button>
            <span id="tb-infants-dis" style="font-size:1.2rem;font-weight:800;color:#F59E0B;min-width:24px;text-align:center;">{{ old('infants',0) }}</span>
            <button type="button" onclick="tbAdj('infants',1)"
                    style="width:28px;height:28px;border-radius:7px;border:1.5px solid #F59E0B;background:#F59E0B;font-size:1.1rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;flex-shrink:0;">+</button>
          </div>
          <input type="hidden" name="infants" id="tb-infants" value="{{ old('infants',0) }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Travel Date <span class="req">*</span></label>
          <input type="date" name="tour_start" id="tb-tour-start" class="tb-input" required
                 min="{{ date('Y-m-d') }}"
                 value="{{ old('tour_start', date('Y-m-d')) }}" oninput="tbBuildSummary()">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Return Date</label>
          <input type="date" name="tour_end" id="tb-tour-end" class="tb-input" min="{{ date('Y-m-d') }}" value="{{ old('tour_end') }}" oninput="tbBuildSummary()">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Pickup Point</label>
          <input type="text" name="pickup_point" class="tb-input" placeholder="e.g. Varanasi Airport / Hotel" value="{{ old('pickup_point') }}">
        </div>
        <div class="col-md-3">
          <label class="tb-label">Drop Point</label>
          <input type="text" name="drop_point" class="tb-input" placeholder="e.g. Railway Station" value="{{ old('drop_point') }}">
        </div>
        <div class="col-12">
          <label class="tb-label">Inclusions</label>
          <input type="hidden" name="inclusions" id="tb-inclusions-hidden" value="{{ old('inclusions') }}">

          {{-- Toggle pills --}}
          <div class="incl-grid" id="incl-pills">
            @foreach([
              '🏨 Hotel / Stay', '🚕 Cab / Transport', '⛵ Boat Ride',
              '🧭 Guide', '🍳 Breakfast', '🍱 Lunch', '🍽 Dinner',
              '🪔 Puja / Aarti', '📸 Photography', '✈️ Air Tickets',
              '🚂 Train Tickets', '🎯 Sightseeing', '💐 Flowers / Garland',
            ] as $incl)
            @php $slug = preg_replace('/[^a-z0-9]/i','-', strtolower($incl)); @endphp
            <label class="incl-pill {{ collect(explode(',', old('inclusions','')))->map(fn($v)=>trim($v))->contains($incl) ? 'active' : '' }}"
                   onclick="toggleIncl('{{ addslashes($incl) }}', this)">
              {{ $incl }}
            </label>
            @endforeach
          </div>

          {{-- Custom / Other --}}
          <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
            <input type="text" id="tb-incl-custom" class="tb-input" style="flex:1;"
                   placeholder="Add custom inclusion…">
            <button type="button" onclick="addCustomIncl()"
                    style="background:var(--t-indigo);color:#fff;border:none;border-radius:8px;padding:8px 14px;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;">
              + Add
            </button>
          </div>

          {{-- Preview --}}
          <div id="incl-preview" style="display:none;margin-top:8px;background:#EEF2FF;border-radius:8px;padding:7px 12px;font-size:.76rem;color:#4338CA;font-weight:600;"></div>
        </div>
      </div>
    </div>
  </div>

  {{-- 3. B2B Expense Breakdown --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#FEF3C7;">💰</div>
      <div class="tb-card-title">B2B Expense Breakdown <span style="font-size:.7rem;font-weight:400;color:var(--t-muted);">— Vendor / Actual Cost</span></div>
    </div>
    <div class="tb-card-body">

      {{-- Hotel / Stay — Multi-row --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#10B981;"></span>
          <span class="tb-svc-label">🏨 Hotel / Stay</span>
          <span class="tb-svc-badge" id="tb-hotel-badge">₹0</span>
        </div>
        <div class="tb-svc-body" style="padding-bottom:6px;">

          {{-- Hotel rows container --}}
          <div id="hotel-rows"></div>

          {{-- Hidden total for expense calc --}}
          <input type="hidden" name="exp_hotel" id="tb-exp-hotel" value="0">

          {{-- Add row button --}}
          <button type="button" onclick="tbAddHotelRow()"
                  style="display:inline-flex;align-items:center;gap:6px;background:#F0FDF4;border:1.5px dashed #10B981;color:#065F46;border-radius:9px;padding:7px 14px;font-size:.78rem;font-weight:700;cursor:pointer;margin-top:6px;width:100%;justify-content:center;transition:.18s;"
                  onmouseover="this.style.background='#DCFCE7'" onmouseout="this.style.background='#F0FDF4'">
            ＋ Add Another Hotel / Destination
          </button>
        </div>
      </div>

      {{-- Cab --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#F59E0B;"></span>
          <span class="tb-svc-label">🚕 Cab / Transport</span>
          <span class="tb-svc-badge" id="tb-cab-badge">₹0</span>
        </div>
        <div class="tb-svc-body">
          <div class="row g-2">
            <div class="col-md-3">
              <label class="tb-label">Vehicle Type</label>
              <select name="cab_type" class="tb-select">
                <option>Sedan / Swift Dzire</option>
                <option>Ertiga</option>
                <option>SUV / Innova Crysta</option>
                <option>Tempo Traveller 12 Seat</option>
                <option>Tempo Traveller 17 Seat</option>
                <option>Tempo Traveller 20 Seat</option>
                <option>Bus 26+ Seat</option>
                <option>Other</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="tb-label">Route</label>
              <input type="text" name="cab_route" class="tb-input" placeholder="e.g. Airport → Hotel">
            </div>
            <div class="col-md-2">
              <label class="tb-label">From Date</label>
              <input type="date" name="cab_from" class="tb-input" min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
              <label class="tb-label">To Date</label>
              <input type="date" name="cab_to" class="tb-input" min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
              <label class="tb-label">Driver Name</label>
              <input type="text" name="driver_name" class="tb-input" placeholder="e.g. Ramesh Singh">
            </div>
            <div class="col-md-2">
              <label class="tb-label">Pickup Time</label>
              <input type="time" name="cab_pickup_time" class="tb-input">
            </div>
            <div class="col-md-2">
              <label class="tb-label" style="color:#D97706;font-weight:800;">B2B Expense (₹)</label>
              <div class="tb-rupee-wrap">
                <span class="tb-rupee">₹</span>
                <input type="number" name="exp_cab" id="tb-exp-cab" class="tb-input tb-input-rs" min="0" value="0" oninput="tbCalcExpense()" style="border-color:#FDE68A !important;background:#FFFBEB !important;">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Boat --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#0EA5E9;"></span>
          <span class="tb-svc-label">⛵ Boat / River Ride</span>
          <span class="tb-svc-badge" id="tb-boat-badge">₹0</span>
        </div>
        <div class="tb-svc-body">
          <div class="row g-2">
            <div class="col-md-3">
              <label class="tb-label">Boat Type</label>
              <select name="boat_type" class="tb-select">
                <option>Normal Motor Boat</option>
                <option>Light Motor Boat</option>
                <option>Premium Motor Boat</option>
                <option>Luxury Yacht</option>
                <option>Bajra Boat</option>
                <option>Cruise</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="tb-label">Ride Type</label>
              <select name="boat_ride" class="tb-select">
                <option>Morning Boat Ride</option>
                <option>Evening Boat Ride</option>
                <option>Ganga Aarti Evening</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="tb-label">Date</label>
              <input type="date" name="boat_date" class="tb-input" min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
              <label class="tb-label">Time</label>
              <input type="time" name="boat_time" class="tb-input">
            </div>
            <div class="col-md-2">
              <label class="tb-label" style="color:#D97706;font-weight:800;">B2B Expense (₹)</label>
              <div class="tb-rupee-wrap">
                <span class="tb-rupee">₹</span>
                <input type="number" name="exp_boat" id="tb-exp-boat" class="tb-input tb-input-rs" min="0" value="0" oninput="tbCalcExpense()" style="border-color:#FDE68A !important;background:#FFFBEB !important;">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Guide --}}
      <div class="tb-svc">
        <div class="tb-svc-head">
          <span class="tb-svc-dot" style="background:#8B5CF6;"></span>
          <span class="tb-svc-label">🧭 Guide</span>
          <span class="tb-svc-badge" id="tb-guide-badge">₹0</span>
        </div>
        <div class="tb-svc-body">
          <div class="row g-2">
            <div class="col-md-4">
              <label class="tb-label">Guide Name</label>
              <input type="text" name="guide_name" class="tb-input" placeholder="e.g. Ramesh Kumar">
            </div>
            <div class="col-md-2">
              <label class="tb-label">From Date</label>
              <input type="date" name="guide_from" class="tb-input" min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
              <label class="tb-label">To Date</label>
              <input type="date" name="guide_to" class="tb-input" min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
              <label class="tb-label">Language</label>
              <select name="guide_lang" class="tb-select">
                <option>Hindi</option><option>English</option><option>Hindi + English</option><option>Foreign Language</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="tb-label" style="color:#D97706;font-weight:800;">B2B Expense (₹)</label>
              <div class="tb-rupee-wrap">
                <span class="tb-rupee">₹</span>
                <input type="number" name="exp_guide" id="tb-exp-guide" class="tb-input tb-input-rs" min="0" value="0" oninput="tbCalcExpense()" style="border-color:#FDE68A !important;background:#FFFBEB !important;">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Expense total bar --}}
      <div class="tb-exp-bar">
        <div class="tb-exp-item">
          <div class="tb-exp-item-icon">🏨</div>
          <div class="tb-exp-item-lbl">Hotel</div>
          <div class="tb-exp-item-val" id="tb-exp-hotel-show">₹0</div>
        </div>
        <div class="tb-exp-item">
          <div class="tb-exp-item-icon">🚕</div>
          <div class="tb-exp-item-lbl">Cab</div>
          <div class="tb-exp-item-val" id="tb-exp-cab-show">₹0</div>
        </div>
        <div class="tb-exp-item">
          <div class="tb-exp-item-icon">⛵</div>
          <div class="tb-exp-item-lbl">Boat</div>
          <div class="tb-exp-item-val" id="tb-exp-boat-show">₹0</div>
        </div>
        <div class="tb-exp-item">
          <div class="tb-exp-item-icon">🧭</div>
          <div class="tb-exp-item-lbl">Guide</div>
          <div class="tb-exp-item-val" id="tb-exp-guide-show">₹0</div>
        </div>
        <div class="tb-exp-total">
          <div class="tb-exp-total-lbl">Total Expense</div>
          <div class="tb-exp-total-val" id="tb-total-exp">₹0</div>
        </div>
      </div>

    </div>
  </div>

  {{-- 4. Trip Summary --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#EDE9FE;">📝</div>
      <div class="tb-card-title">Trip Summary <span style="font-size:.7rem;font-weight:400;color:var(--t-muted);">(auto-filled, editable)</span></div>
    </div>
    <div class="tb-card-body">
      <textarea name="trip_summary" id="tb-summary" class="tb-textarea tb-input" rows="3" placeholder="Auto-filled from above details…"></textarea>
    </div>
  </div>

  {{-- 5. Itinerary --}}
  <div class="tb-card">
    <div class="tb-card-head">
      <div class="tb-icon" style="background:#EDE9FE;">🗓️</div>
      <div class="tb-card-title">Itinerary <span style="font-size:.7rem;font-weight:400;color:var(--t-muted);">(optional)</span></div>
    </div>
    <div class="tb-card-body">
      <textarea name="itinerary" id="tb-itinerary" style="display:none;">{{ old('itinerary') }}</textarea>
      <div id="tb-itinerary-editor" style="border:1.5px solid var(--t-border);border-radius:10px;min-height:180px;"></div>
    </div>
  </div>

  {{-- 6. Internal Notes --}}
  <div class="tb-card">
    <div class="tb-card-body" style="padding:14px 18px;">
      <label class="tb-label">Internal Notes <span style="font-weight:400;color:var(--t-muted);text-transform:none;">(staff only)</span></label>
      <textarea name="internal_notes" class="tb-textarea tb-input" rows="2" placeholder="Special instructions, follow-up reminders…"></textarea>
    </div>
  </div>

</div>{{-- /left --}}

{{-- ════ RIGHT SIDEBAR ════ --}}
<div class="tb-sidebar">

  {{-- Booking Amount --}}
  <div class="tb-sidebar-card">
    <div class="tb-sidebar-title">💳 Booking Amount</div>

    <div class="tb-amt-row">
      <label class="tb-label">Total Booking Amount (₹) <span class="req">*</span></label>
      <div class="tb-rupee-wrap">
        <span class="tb-rupee">₹</span>
        <input type="number" name="booking_amount" id="tb-booking-amt" class="tb-input tb-input-rs"
               placeholder="Amount charged to client" min="0" step="1" required
               style="font-size:1.05rem;font-weight:700;"
               oninput="tbCalcBalance();tbCalcExpense()">
      </div>
    </div>

    <div class="tb-amt-row">
      <label class="tb-label">Discount (₹)</label>
      <div class="tb-rupee-wrap">
        <span class="tb-rupee">₹</span>
        <input type="number" name="discount" id="tb-discount" class="tb-input tb-input-rs" value="0" min="0" oninput="tbCalcBalance();tbCalcExpense()">
      </div>
    </div>

    <div class="tb-amt-row">
      <label class="tb-label">Advance Paid (₹)</label>
      <div class="tb-rupee-wrap">
        <span class="tb-rupee">₹</span>
        <input type="number" name="advance_paid" id="tb-advance" class="tb-input tb-input-rs" value="0" min="0" oninput="tbCalcBalance()">
      </div>
    </div>

    <div class="tb-balance-box">
      <div class="tb-balance-lbl">Balance Due</div>
      <div class="tb-balance-row">
        <div class="tb-balance-amt" id="tb-balance">₹0</div>
        <span class="tb-badge tb-badge-due" id="tb-pay-badge">DUE</span>
      </div>
    </div>

    <div class="tb-pay-2col">
      <div class="tb-amt-row" style="margin-bottom:0;">
        <label class="tb-label">Payment Status</label>
        <select name="payment_status" id="tb-pay-status" class="tb-select" onchange="tbUpdateBadge()">
          <option value="due">Due</option>
          <option value="paid">Paid</option>
          <option value="partial">Partial</option>
        </select>
      </div>
      <div class="tb-amt-row" style="margin-bottom:0;">
        <label class="tb-label">Payment Method</label>
        <select name="payment_method" class="tb-select">
          <option value="">Select…</option>
          <option value="cash">💵 Cash</option>
          <option value="upi">📱 UPI</option>
          <option value="bank_transfer">🏦 Bank Transfer</option>
          <option value="cheque">📝 Cheque</option>
          <option value="online">💳 Online</option>
        </select>
      </div>
    </div>

    <div class="tb-amt-row" style="margin-top:10px;">
      <label class="tb-label">Transaction / Reference ID</label>
      <input type="text" name="reference_number" class="tb-input" placeholder="UPI Ref / Cheque No / TXN ID" value="{{ old('reference_number') }}">
    </div>

    <div class="tb-amt-row">
      <label class="tb-label">Bank Account <span style="font-size:.6rem;font-weight:500;color:var(--t-muted);text-transform:none;">(where advance received)</span></label>
      <select name="payment_account_id" class="tb-select">
        <option value="">— Select account —</option>
        @foreach($paymentAccounts as $acc)
        <option value="{{ $acc->id }}" {{ old('payment_account_id') == $acc->id ? 'selected' : '' }}>
          {{ $acc->account_name }}@if($acc->bank_name) — {{ $acc->bank_name }}@endif
        </option>
        @endforeach
      </select>
    </div>

    <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:12px 14px;margin-top:4px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
        <span style="font-size:.72rem;font-weight:800;color:#065F46;text-transform:uppercase;letter-spacing:.04em;">🧾 GST</span>
        <label style="display:flex;align-items:center;gap:6px;font-size:.75rem;font-weight:600;color:#065F46;cursor:pointer;">
          <input type="checkbox" name="is_gst_invoice" id="tb-gst-toggle" value="1" onchange="tbCalcGst()" {{ old('is_gst_invoice') ? 'checked' : '' }}>
          Apply GST
        </label>
      </div>
      <div id="tb-gst-fields" style="{{ old('is_gst_invoice') ? '' : 'display:none;' }}">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">
          <div>
            <label class="tb-label">GST Rate</label>
            <select name="gst_rate" id="tb-gst-rate" class="tb-select" onchange="tbCalcGst()">
              <option value="0">0%</option>
              <option value="5" {{ old('gst_rate')==5 ? 'selected':'' }}>5%</option>
              <option value="12" {{ old('gst_rate')==12 ? 'selected':'' }}>12%</option>
              <option value="18" {{ old('gst_rate')==18 ? 'selected':'' }}>18%</option>
            </select>
          </div>
          <div>
            <label class="tb-label">GST Amount</label>
            <div class="tb-rupee-wrap">
              <span class="tb-rupee">₹</span>
              <input type="number" name="gst_amount" id="tb-gst-amount" class="tb-input tb-input-rs" readonly placeholder="0" style="background:#F0FDF4 !important;">
            </div>
          </div>
        </div>
        <div>
          <label class="tb-label">Customer GSTIN</label>
          <input type="text" name="customer_gstin" class="tb-input" placeholder="Optional" value="{{ old('customer_gstin') }}" style="font-size:.8rem;">
        </div>
      </div>
    </div>
  </div>

  {{-- Marginal Profit --}}
  <div class="tb-sidebar-card">
    <div class="tb-sidebar-title">📊 Marginal Profit</div>
    <div class="tb-profit-box">
      <div class="tb-profit-row">
        <span class="tb-profit-lbl">Booking Amount</span>
        <span class="tb-profit-val" id="tb-p-booking">₹0</span>
      </div>
      <div class="tb-profit-row">
        <span class="tb-profit-lbl">Total B2B Expense</span>
        <span class="tb-profit-val" style="color:#EF4444;" id="tb-p-expense">₹0</span>
      </div>
      <div class="tb-profit-row" style="border-top:2px solid #A7F3D0;padding-top:8px;margin-top:4px;">
        <span class="tb-profit-lbl" style="font-weight:800;">Marginal Profit</span>
        <span class="tb-profit-val tb-profit-final" id="tb-p-profit">₹0</span>
      </div>
      <div style="text-align:right;margin-top:6px;">
        <span class="tb-profit-pct" id="tb-p-pct">0%</span>
      </div>
      <div style="font-size:.67rem;color:#94A3B8;text-align:center;margin-top:8px;">
        Booking − Expenses = Profit
      </div>
    </div>
  </div>

  {{-- Booking Status --}}
  <div class="tb-sidebar-card" style="padding:14px 16px;">
    <label class="tb-label">Booking Status</label>
    <select name="booking_status" id="tb-bk-status" class="tb-select" onchange="tbStatusStyle()" style="font-weight:700;">
      <option value="confirm" {{ old('booking_status','confirm')=='confirm' ? 'selected':'' }} style="color:#15803D;">✅ Confirmed</option>
      <option value="pending" {{ old('booking_status')=='pending' ? 'selected':'' }} style="color:#B45309;">⏳ Pending</option>
      <option value="inquiry" {{ old('booking_status')=='inquiry' ? 'selected':'' }} style="color:#1E40AF;">🔍 Inquiry</option>
      <option value="cancelled" {{ old('booking_status')=='cancelled' ? 'selected':'' }} style="color:#DC2626;">❌ Cancelled</option>
    </select>
  </div>

  {{-- Submit (desktop) --}}
  <button type="submit" class="tb-submit">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    Confirm Tour Booking
  </button>

</div>{{-- /sidebar --}}
</div>{{-- /layout --}}

{{-- ── Mobile sticky submit bar ── --}}
<div class="tb-mob-bar">
  <div class="tb-mob-bar-info">
    <div class="tb-mob-bar-lbl">Balance Due</div>
    <div class="tb-mob-bar-amt" id="tb-mob-balance">₹0</div>
    <div class="tb-mob-bar-sub" id="tb-mob-booking-info">Booking Amount: ₹0</div>
  </div>
  <button type="submit" class="tb-mob-submit" form="tb-form">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    Confirm
  </button>
</div>

</form>
</div>

<script>
/* ── Hotel city/name data (from DB via PHP) ─── */
var _hotelData = @json($hotelsByCity);

function tbLoadHotels() {
  var city    = document.getElementById('tb-hotel-city').value;
  var sel     = document.getElementById('tb-hotel-select');
  var nameInp = document.getElementById('tb-hotel-name');

  // Clear name input
  nameInp.value = '';

  if (!city || city === 'Other' || !_hotelData[city]) {
    sel.style.display = 'none';
    nameInp.style.display = '';
    nameInp.placeholder = 'Type hotel name…';
    return;
  }

  // Rebuild options
  sel.innerHTML = '<option value="">— Select hotel —</option>';
  _hotelData[city].forEach(function(h) {
    var opt = document.createElement('option');
    opt.value = h; opt.textContent = h;
    sel.appendChild(opt);
  });
  // Add custom option
  var custom = document.createElement('option');
  custom.value = '__custom__'; custom.textContent = '✏️ Type custom hotel name…';
  sel.appendChild(custom);

  sel.style.display = '';
  nameInp.style.display = 'none';
  nameInp.placeholder = 'Hotel name';
}

function tbOnHotelSelect() {
  var sel     = document.getElementById('tb-hotel-select');
  var nameInp = document.getElementById('tb-hotel-name');
  if (sel.value === '__custom__') {
    nameInp.style.display = '';
    nameInp.value = '';
    nameInp.focus();
  } else if (sel.value) {
    nameInp.value = sel.value;
    nameInp.style.display = 'none';
  }
}

/* ── Adults / Children counters ─────────────── */
function tbAdj(field, delta) {
  var hidden = document.getElementById('tb-' + field);
  var disp   = document.getElementById('tb-' + field + '-dis');
  var min    = field === 'adults' ? 1 : 0;
  var val    = Math.max(min, (parseInt(hidden.value) || 0) + delta);
  hidden.value       = val;
  if (disp) disp.textContent = val;
  tbBuildSummary();
}

/* ── Multi-hotel rows ────────────────────────── */
var _hotelRowCount = 0;
var _today = '{{ date("Y-m-d") }}';

function tbAddHotelRow() {
  var idx  = _hotelRowCount++;
  var wrap = document.getElementById('hotel-rows');
  var div  = document.createElement('div');
  div.id   = 'hotel-row-' + idx;
  div.style.cssText = 'border:1px solid #E2E8F0;border-radius:10px;padding:12px 14px;margin-bottom:10px;background:#FAFBFF;position:relative;';

  div.innerHTML = `
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
      <span style="font-size:.72rem;font-weight:800;color:#059669;text-transform:uppercase;letter-spacing:.05em;">🏨 Hotel ${idx + 1}</span>
      ${idx > 0 ? `<button type="button" onclick="tbRemoveHotelRow(${idx})" style="background:#FEE2E2;color:#DC2626;border:none;border-radius:6px;padding:3px 10px;font-size:.72rem;font-weight:700;cursor:pointer;">✕ Remove</button>` : ''}
    </div>

    {{-- Row 1: Destination + Hotel Name + Room Type --}}
    <div class="tb-hotel-row-grid-1">
      <div>
        <label class="tb-label">Destination</label>
        <select name="hotels[${idx}][city]" class="tb-select" onchange="tbLoadHotelOptions(${idx})">
          <option value="">Select city…</option>
          <option value="Varanasi">🕌 Varanasi</option>
          <option value="Prayagraj">🏛 Prayagraj</option>
          <option value="Ayodhya">🛕 Ayodhya</option>
          <option value="Chitrakoot">🌿 Chitrakoot</option>
          <option value="Bodhgaya">☸️ Bodhgaya</option>
          <option value="Lucknow">🏙 Lucknow</option>
          <option value="Naimisharanya">🌳 Naimisharanya</option>
          <option value="Other">📍 Other</option>
        </select>
      </div>
      <div>
        <label class="tb-label">Hotel Name</label>
        <select id="hotel-sel-${idx}" class="tb-select" onchange="tbPickHotel(${idx})" style="display:none;"><option value="">Select hotel…</option></select>
        <input type="text" name="hotels[${idx}][name]" id="hotel-inp-${idx}" class="tb-input" placeholder="Type or select hotel name…">
      </div>
      <div>
        <label class="tb-label">Room Type</label>
        <select name="hotels[${idx}][room_type]" class="tb-select" onchange="tbToggleFlatName(${idx})">
          <option value="">Select…</option>
          <option>Deluxe Room</option><option>Executive Room</option>
          <option>Premium Room</option><option>Suite</option>
          <option>Homestay Flat</option><option>Dormitory</option>
        </select>
      </div>
    </div>

    {{-- Row 1b: Flat Type / Name — only for Homestay Flat --}}
    <div id="hotel-flat-wrap-${idx}" style="display:none;margin-bottom:8px;">
      <label class="tb-label">Flat Type / Name</label>
      <input type="text" name="hotels[${idx}][flat_name]" class="tb-input" placeholder="e.g. 2 BHK Flat, 3 BHK Flat, Studio Flat">
    </div>

    {{-- Row 2: Check-in + Check-out + Nights + B2B Cost --}}
    <div class="tb-hotel-row-grid-2">
      <div>
        <label class="tb-label">Check-in</label>
        <input type="date" name="hotels[${idx}][checkin]" id="hotel-ci-${idx}" class="tb-input" min="${_today}" oninput="tbCalcRowNights(${idx})">
      </div>
      <div>
        <label class="tb-label">Check-out</label>
        <input type="date" name="hotels[${idx}][checkout]" id="hotel-co-${idx}" class="tb-input" min="${_today}" oninput="tbCalcRowNights(${idx})">
      </div>
      <div>
        <label class="tb-label">Nights</label>
        <input type="text" id="hotel-nights-${idx}" class="tb-input" readonly
               style="background:#F1F5F9;color:#059669;font-weight:800;text-align:center;" placeholder="—">
      </div>
      <div>
        <label class="tb-label" style="color:#D97706;font-weight:800;">B2B Cost (₹)</label>
        <div class="tb-rupee-wrap">
          <span class="tb-rupee">₹</span>
          <input type="number" name="hotels[${idx}][b2b]" id="hotel-b2b-${idx}" class="tb-input tb-input-rs"
                 min="0" value="0" oninput="tbSumHotels()"
                 style="border-color:#FDE68A !important;background:#FFFBEB !important;">
        </div>
      </div>
    </div>`;
  wrap.appendChild(div);
  tbSumHotels();
}

function tbRemoveHotelRow(idx) {
  var row = document.getElementById('hotel-row-' + idx);
  if (row) row.remove();
  tbSumHotels();
}

function tbToggleFlatName(idx) {
  var sel  = document.querySelector('[name="hotels[' + idx + '][room_type]"]');
  var wrap = document.getElementById('hotel-flat-wrap-' + idx);
  if (!sel || !wrap) return;
  wrap.style.display = sel.value === 'Homestay Flat' ? '' : 'none';
}

function tbCalcRowNights(idx) {
  var ci = new Date(document.getElementById('hotel-ci-' + idx)?.value);
  var co = new Date(document.getElementById('hotel-co-' + idx)?.value);
  var ni = document.getElementById('hotel-nights-' + idx);
  if (ni && !isNaN(ci) && !isNaN(co) && co > ci)
    ni.value = Math.round((co - ci) / 86400000);
}

function tbLoadHotelOptions(idx) {
  var cityEl  = document.querySelector('[name="hotels[' + idx + '][city]"]');
  var sel     = document.getElementById('hotel-sel-' + idx);
  var inp     = document.getElementById('hotel-inp-' + idx);
  var city    = cityEl?.value || '';
  inp.value   = '';
  if (!city || city === 'Other' || !_hotelData[city]) {
    sel.style.display = 'none'; inp.style.display = ''; return;
  }
  sel.innerHTML = '<option value="">— Select hotel —</option>';
  _hotelData[city].forEach(function(h) {
    var o = document.createElement('option'); o.value = h; o.textContent = h; sel.appendChild(o);
  });
  var c = document.createElement('option'); c.value='__custom__'; c.textContent='✏️ Type custom name…'; sel.appendChild(c);
  sel.style.display = ''; inp.style.display = 'none';
}

function tbPickHotel(idx) {
  var sel = document.getElementById('hotel-sel-' + idx);
  var inp = document.getElementById('hotel-inp-' + idx);
  if (sel.value === '__custom__') { inp.style.display=''; inp.value=''; inp.focus(); }
  else if (sel.value)             { inp.value = sel.value; inp.style.display='none'; }
}

function tbSumHotels() {
  var total = 0;
  document.querySelectorAll('[id^="hotel-b2b-"]').forEach(function(el) {
    total += parseFloat(el.value) || 0;
  });
  var hidden = document.getElementById('tb-exp-hotel');
  if (hidden) hidden.value = total;
  tbCalcExpense();
}

// Backward-compat stubs (old single-hotel functions no longer needed)
function tbLoadHotels()   {}
function tbOnHotelSelect(){}

/* ── Calc nights (legacy stub) ────────────────── */
function tbCalcNights() {}

/* ── Format ₹ ────────────────────────────────── */
function fmt(v) {
  return '₹' + Math.max(0, v).toLocaleString('en-IN');
}
function v(id) {
  return parseFloat(document.getElementById(id)?.value || 0);
}

/* ── Calc all expenses ───────────────────────── */
function tbCalcExpense() {
  var hotel  = v('tb-exp-hotel');
  var cab    = v('tb-exp-cab');
  var boat   = v('tb-exp-boat');
  var guide  = v('tb-exp-guide');
  var total  = hotel + cab + boat + guide;

  // Badges per service
  document.getElementById('tb-hotel-badge').textContent = fmt(hotel);
  document.getElementById('tb-cab-badge').textContent   = fmt(cab);
  document.getElementById('tb-boat-badge').textContent  = fmt(boat);
  document.getElementById('tb-guide-badge').textContent = fmt(guide);

  // Expense bar
  document.getElementById('tb-exp-hotel-show').textContent = fmt(hotel);
  document.getElementById('tb-exp-cab-show').textContent   = fmt(cab);
  document.getElementById('tb-exp-boat-show').textContent  = fmt(boat);
  document.getElementById('tb-exp-guide-show').textContent = fmt(guide);
  document.getElementById('tb-total-exp').textContent      = fmt(total);

  // Profit
  var booking  = v('tb-booking-amt');
  var discount = v('tb-discount');
  var net      = Math.max(0, booking - discount);
  var profit   = net - total;
  var pct      = net > 0 ? Math.round((profit / net) * 100) : 0;

  document.getElementById('tb-p-booking').textContent = fmt(net);
  document.getElementById('tb-p-expense').textContent = fmt(total);

  var profEl = document.getElementById('tb-p-profit');
  profEl.textContent = fmt(profit);
  profEl.style.color = profit >= 0 ? '#14532D' : '#EF4444';

  var pctEl = document.getElementById('tb-p-pct');
  pctEl.textContent = Math.max(0, pct) + '%';
  pctEl.style.background = pct >= 0 ? '#D1FAE5' : '#FEE2E2';
  pctEl.style.color = pct >= 0 ? '#065F46' : '#DC2626';
}

/* ── Calc balance ────────────────────────────── */
function tbCalcBalance() {
  var booking  = v('tb-booking-amt');
  var discount = v('tb-discount');
  var advance  = v('tb-advance');
  var balance  = Math.max(0, booking - discount - advance);
  document.getElementById('tb-balance').textContent = fmt(balance);
  tbCalcGst();
  // Sync mobile sticky bar
  var mobBal = document.getElementById('tb-mob-balance');
  var mobInfo = document.getElementById('tb-mob-booking-info');
  if (mobBal)  mobBal.textContent = fmt(balance);
  if (mobInfo) mobInfo.textContent = 'Booking: ' + fmt(booking) + (discount > 0 ? ' · Disc: ' + fmt(discount) : '') + (advance > 0 ? ' · Adv: ' + fmt(advance) : '');
  tbUpdateBadge();
  tbCalcExpense();
}

/* ── Pay badge ───────────────────────────────── */
function tbUpdateBadge() {
  var s = document.getElementById('tb-pay-status').value;
  var b = document.getElementById('tb-pay-badge');
  var map = { paid:'PAID', due:'DUE', partial:'PARTIAL' };
  var cls = { paid:'tb-badge-paid', due:'tb-badge-due', partial:'tb-badge-partial' };
  b.textContent = map[s] || 'DUE';
  b.className   = 'tb-badge ' + (cls[s] || 'tb-badge-due');
}

/* ── Auto-build trip summary ─────────────────── */
function tbBuildSummary() {
  var name  = document.getElementById('tb-pkg-name')?.value || '';
  var start = document.getElementById('tb-tour-start')?.value || '';
  var end   = document.getElementById('tb-tour-end')?.value || '';
  var adult = document.getElementById('tb-adults')?.value || '';
  var incl  = document.getElementById('tb-inclusions-hidden')?.value || '';

  var parts = [];
  if (name)  parts.push(name);
  if (start) parts.push('From: ' + new Date(start + 'T00:00:00').toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'}));
  if (end)   parts.push('To: '   + new Date(end   + 'T00:00:00').toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'}));
  if (adult) parts.push('Adults: ' + adult);
  if (incl)  parts.push('Incl: ' + incl);

  var el = document.getElementById('tb-summary');
  if (el && !el.dataset.edited) el.value = parts.join(' | ');
}

document.getElementById('tb-summary')?.addEventListener('input', function() {
  this.dataset.edited = '1';
});

// Add first hotel row on load
document.addEventListener('DOMContentLoaded', function() { tbAddHotelRow(); });

// ── CKEditor for Itinerary ─────────────────────────────────────────────

/* ── Inclusions ─────────────────────────────── */
// Keep a Set of selected inclusions
var _inclSet = new Set(
  (document.getElementById('tb-inclusions-hidden')?.value || '')
    .split(',').map(s => s.trim()).filter(Boolean)
);

// Mark pre-selected pills on load
document.querySelectorAll('.incl-pill').forEach(function(pill) {
  if (_inclSet.has(pill.textContent.trim())) pill.classList.add('active');
});

function toggleIncl(label, pill) {
  if (_inclSet.has(label)) {
    _inclSet.delete(label);
    pill.classList.remove('active');
  } else {
    _inclSet.add(label);
    pill.classList.add('active');
  }
  _syncIncl();
}

function addCustomIncl() {
  var inp = document.getElementById('tb-incl-custom');
  var val = inp.value.trim();
  if (!val) return;
  if (!_inclSet.has(val)) {
    _inclSet.add(val);
    // Create a new pill
    var lbl = document.createElement('label');
    lbl.className = 'incl-pill active';
    lbl.textContent = val;
    lbl.setAttribute('onclick', "toggleIncl('" + val.replace(/'/g,"\\'") + "', this)");
    document.getElementById('incl-pills').appendChild(lbl);
  }
  inp.value = '';
  _syncIncl();
}

function _syncIncl() {
  var csv = Array.from(_inclSet).join(', ');
  document.getElementById('tb-inclusions-hidden').value = csv;
  // Update preview
  var prev = document.getElementById('incl-preview');
  if (csv) {
    prev.style.display = '';
    prev.textContent   = '✓ ' + csv;
  } else {
    prev.style.display = 'none';
  }
  // Also keep tb-inclusions in sync for tbBuildSummary
  var legacyInp = document.getElementById('tb-inclusions');
  if (legacyInp) legacyInp.value = csv;
  tbBuildSummary();
}

// Wire Enter key on custom input
document.getElementById('tb-incl-custom')?.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') { e.preventDefault(); addCustomIncl(); }
});

// Init preview on load
_syncIncl();

/* ── GST calculation ─────────────────────────── */
function tbCalcGst() {
  var toggle = document.getElementById('tb-gst-toggle');
  var fields = document.getElementById('tb-gst-fields');
  if (!toggle.checked) { fields.style.display = 'none'; return; }
  fields.style.display = '';
  var net  = v('tb-booking-amt') - v('tb-discount');
  var rate = parseFloat(document.getElementById('tb-gst-rate')?.value || 0);
  var gst  = Math.round(net * rate / 100);
  var el   = document.getElementById('tb-gst-amount');
  if (el) el.value = gst;
}

/* ── Booking status colour ───────────────────── */
function tbStatusStyle() {
  var sel    = document.getElementById('tb-bk-status');
  var colors = { confirm:'#15803D', pending:'#B45309', inquiry:'#1E40AF', cancelled:'#DC2626' };
  sel.style.color = colors[sel.value] || '#0F172A';
}
document.addEventListener('DOMContentLoaded', tbStatusStyle);
</script>

{{-- CKEditor 5 for Itinerary --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  ClassicEditor
    .create(document.getElementById('tb-itinerary-editor'), {
      toolbar: [
        'heading','|','bold','italic','underline','|',
        'bulletedList','numberedList','|',
        'blockQuote','horizontalLine','|',
        'undo','redo'
      ],
      placeholder: 'Day 1: Arrival at Varanasi...\nDay 2: Kashi Vishwanath & Ganga Aarti...\nDay 3: Departure...',
    })
    .then(function(editor) {
      // Inject color-fix AFTER CKEditor's own dynamic CSS loads
      var s = document.createElement('style');
      s.textContent = '.ck.ck-editor__editable,.ck.ck-editor__editable_inline,.ck.ck-content{color:#0F172A!important;background:#fff!important;}.ck.ck-editor__editable p,.ck.ck-editor__editable li,.ck.ck-editor__editable h1,.ck.ck-editor__editable h2,.ck.ck-editor__editable h3{color:#0F172A!important;}';
      document.head.appendChild(s);

      var existing = document.getElementById('tb-itinerary').value;
      if (existing) editor.setData(existing);

      document.getElementById('tb-form').addEventListener('submit', function() {
        document.getElementById('tb-itinerary').value = editor.getData();
      });
    })
    .catch(function(err) { console.error('CKEditor error:', err); });
});
</script>
@endsection
