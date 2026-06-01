@extends('admin.layouts.app')

@section('content')
<style>
/* ── Design tokens ── */
:root {
  --h-bg:      #EEF2FF;
  --h-card:    #FFFFFF;
  --h-border:  #E2E8F0;
  --h-shadow:  0 1px 3px rgba(15,23,42,.06), 0 4px 16px rgba(15,23,42,.04);
  --h-shadow-md:0 4px 24px rgba(15,23,42,.10);
  --h-text:    #0F172A;
  --h-sub:     #475569;
  --h-muted:   #94A3B8;
  --h-indigo:  #4F46E5;
  --h-emerald: #10B981;
  --h-amber:   #F59E0B;
  --h-rose:    #EF4444;
  --h-sky:     #0EA5E9;
  --h-r:       14px;
  --h-t:       0.2s ease;
}

.bk-page { background: var(--h-bg); min-height: 100vh; padding: 24px; }

/* ── Header ── */
.bk-header {
  background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
  border-radius: var(--h-r);
  padding: 20px 26px;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px; position: relative; overflow: hidden;
}
.bk-header::before {
  content:''; position:absolute; top:-30px; right:-30px;
  width:160px; height:160px;
  background:rgba(255,255,255,.06); border-radius:50%;
}
.bk-header-title { position:relative; z-index:1; }
.bk-header-title h1 { color:#fff; font-size:1.35rem; font-weight:700; margin:0; }
.bk-header-title p  { color:rgba(255,255,255,.72); font-size:.82rem; margin:.2rem 0 0; }
.bk-header-actions { display:flex; gap:8px; position:relative; z-index:1; flex-wrap:wrap; }
.bk-hbtn {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,255,255,.18); color:#fff;
  border:1px solid rgba(255,255,255,.3); border-radius:8px;
  padding:8px 14px; font-size:.8rem; font-weight:600;
  text-decoration:none; transition:background var(--h-t);
}
.bk-hbtn:hover { background:rgba(255,255,255,.28); color:#fff; }
.bk-hbtn svg { width:14px; height:14px; stroke:#fff; }
.bk-hbtn.primary { background:#fff; color:var(--h-indigo); border-color:#fff; }
.bk-hbtn.primary:hover { background:#f0f0ff; color:var(--h-indigo); }
.bk-hbtn.primary svg { stroke:var(--h-indigo); }

/* ── Stats ── */
.bk-stats { display:grid; grid-template-columns:repeat(7,1fr); gap:12px; margin-bottom:20px; }
@media(max-width:1200px){ .bk-stats{ grid-template-columns:repeat(4,1fr); } }
@media(max-width:640px) { .bk-stats{ grid-template-columns:repeat(2,1fr); } }

.bk-stat {
  background:var(--h-card); border-radius:12px;
  border:1px solid var(--h-border); box-shadow:var(--h-shadow);
  padding:12px 14px; text-decoration:none;
  border-left:3px solid var(--stat-color,var(--h-indigo));
  transition:box-shadow var(--h-t), transform var(--h-t);
  display:block;
}
.bk-stat:hover { box-shadow:var(--h-shadow-md); transform:translateY(-2px); }
.bk-stat-label { font-size:.68rem; font-weight:700; color:var(--h-muted); text-transform:uppercase; letter-spacing:.4px; }
.bk-stat-value { font-size:1.5rem; font-weight:800; color:var(--h-text); line-height:1.1; margin-top:2px; }

/* ── Filter card ── */
.bk-filter {
  background:var(--h-card); border-radius:var(--h-r);
  border:1px solid var(--h-border); box-shadow:var(--h-shadow);
  margin-bottom:20px; overflow:hidden;
}
.bk-filter-head {
  padding:12px 18px; display:flex; align-items:center; justify-content:space-between;
  cursor:pointer; user-select:none;
  border-bottom:1px solid transparent; transition:border-color var(--h-t);
}
.bk-filter-head.open { border-bottom-color:var(--h-border); }
.bk-filter-head-left { display:flex; align-items:center; gap:8px;
  font-size:.82rem; font-weight:700; color:var(--h-text); }
.bk-filter-head-left svg { width:14px; height:14px; stroke:var(--h-indigo); }
.bk-filter-toggle { width:24px; height:24px; border-radius:6px; background:var(--h-bg);
  display:flex; align-items:center; justify-content:center; }
.bk-filter-toggle svg { width:14px; height:14px; stroke:var(--h-muted); transition:transform .25s; }
.bk-filter-toggle.open svg { transform:rotate(180deg); }
.bk-filter-body { padding:16px 18px; }
.bk-filter-body .form-control,
.bk-filter-body .form-select {
  border:1.5px solid var(--h-border); border-radius:8px;
  font-size:.82rem; color:var(--h-text); background:#FAFBFF;
  padding:8px 12px;
}
.bk-filter-body .form-control:focus,
.bk-filter-body .form-select:focus {
  border-color:var(--h-indigo); box-shadow:0 0 0 3px rgba(79,70,229,.1); outline:none;
}
.bk-filter-body label { font-size:.72rem; font-weight:700; color:var(--h-sub);
  text-transform:uppercase; letter-spacing:.3px; margin-bottom:5px; }

/* ── Compact booking list ── */
.bk-list-card {
  background:var(--h-card); border-radius:var(--h-r);
  border:1px solid var(--h-border); box-shadow:var(--h-shadow);
  overflow:hidden;
}

/* Column header bar */
.bk-list-head {
  display:grid;
  grid-template-columns: 28px 180px 1fr 100px 160px 140px;
  gap:0; padding:9px 16px;
  background:linear-gradient(90deg,rgba(79,70,229,.06) 0%,transparent 100%);
  border-bottom:2px solid var(--h-border);
}
.bk-list-head span {
  font-size:.67rem; font-weight:700; color:var(--h-muted);
  text-transform:uppercase; letter-spacing:.5px;
}
@media(max-width:1100px){ .bk-list-head{ display:none; } }

/* Row */
.bk-row {
  display:grid;
  grid-template-columns: 28px 180px 1fr 100px 160px 140px;
  gap:0; padding:11px 16px;
  border-bottom:1px solid #F1F5F9;
  transition:background var(--h-t);
  align-items:center; cursor:pointer;
}
.bk-row:last-child { border-bottom:none; }
.bk-row:hover { background:#F8FAFF; }

/* ── Row cells ── */

/* 1. Serial */
.bk-row .bk-num {
  width:24px; height:24px; border-radius:6px;
  background:#EEF2FF; color:var(--h-indigo);
  font-size:.65rem; font-weight:700;
  display:flex; align-items:center; justify-content:center;
}

/* 2. Booking ref + date */
.bk-ref { min-width:0; }
.bk-ref-num { font-size:.82rem; font-weight:700; color:var(--h-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.bk-ref-date { font-size:.7rem; color:var(--h-muted); display:flex; align-items:center; gap:3px; margin-top:2px; }
.bk-ref-date svg { width:10px; height:10px; }
.bk-gst-badge {
  display:inline-block; font-size:.6rem; font-weight:700;
  background:linear-gradient(135deg,#FF6600,#ff8533);
  color:#fff; border-radius:4px; padding:1px 5px; margin-left:5px; vertical-align:middle;
}

/* 3. Guest */
.bk-guest { min-width:0; }
.bk-guest-name { font-size:.875rem; font-weight:600; color:var(--h-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.bk-guest-sub { font-size:.72rem; color:var(--h-muted); display:flex; align-items:center; gap:10px; margin-top:2px; flex-wrap:wrap; }
.bk-guest-sub span { display:flex; align-items:center; gap:3px; }
.bk-guest-sub svg { width:10px; height:10px; }

/* 4. Status */
.bk-status-wrap { display:flex; align-items:center; }
.bk-status {
  display:inline-block; font-size:.67rem; font-weight:700;
  text-transform:uppercase; letter-spacing:.4px;
  padding:4px 9px; border-radius:20px; white-space:nowrap;
}
.bk-status.not-started { background:#F1F5F9; color:#64748B; }
.bk-status.confirmed   { background:#DBEAFE; color:#1D4ED8; }
.bk-status.in-progress { background:#FEF3C7; color:#92400E; }
.bk-status.completed   { background:#D1FAE5; color:#065F46; }
.bk-status.cancelled   { background:#FEE2E2; color:#991B1B; }

/* 5. Payment */
.bk-pay { font-size:.72rem; }
.bk-pay-row { display:flex; align-items:center; justify-content:space-between; gap:6px; margin-bottom:2px; }
.bk-pay-row:last-child { margin-bottom:0; }
.bk-pay-label { color:var(--h-muted); font-weight:600; flex-shrink:0; }
.bk-pay-val   { font-weight:700; }
.bk-pay-val.total { color:var(--h-text); }
.bk-pay-val.paid  { color:var(--h-emerald); }
.bk-pay-val.due   { color:var(--h-rose); }
.bk-pay-val.clear { color:var(--h-muted); }

/* 6. Actions */
.bk-actions { display:flex; gap:4px; align-items:center; justify-content:flex-end; flex-wrap:wrap; }
.bk-btn {
  width:30px; height:30px; border-radius:7px;
  border:1px solid transparent;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer; transition:background var(--h-t), border-color var(--h-t);
  text-decoration:none; background:transparent;
}
.bk-btn svg { width:14px; height:14px; }
.bk-btn.view   { color:var(--h-sky);     border-color:#BAE6FD; }
.bk-btn.view:hover   { background:#E0F2FE; }
.bk-btn.edit   { color:var(--h-amber);   border-color:#FDE68A; }
.bk-btn.edit:hover   { background:#FEF3C7; color:#D97706; }
.bk-btn.inv    { color:#10B981;           border-color:#A7F3D0; }
.bk-btn.inv:hover    { background:#D1FAE5; }
.bk-btn.inv.gst { color:#F97316;          border-color:#FED7AA; }
.bk-btn.inv.gst:hover{ background:#FFEDD5; }
.bk-btn.rpt    { color:var(--h-indigo);  border-color:#C7D2FE; }
.bk-btn.rpt:hover    { background:#EEF2FF; }
.bk-btn.del    { color:var(--h-rose);    border-color:#FECACA; }
.bk-btn.del:hover    { background:#FEE2E2; color:#DC2626; }

/* Mobile stacked layout */
@media(max-width:1100px){
  .bk-row {
    grid-template-columns: 1fr;
    gap:8px; padding:14px 16px;
  }
  .bk-num { display:none; }
  .bk-list-head { display:none; }
  .bk-row > * { width:100%; }
  .bk-actions { justify-content:flex-start; }
  .bk-status-wrap { justify-content:flex-start; }
  .bk-pay { display:flex; gap:14px; flex-wrap:wrap; }
  .bk-pay-row { flex-direction:column; gap:0; align-items:flex-start; }
}

/* Empty state */
.bk-empty { text-align:center; padding:56px 24px; color:var(--h-muted); }
.bk-empty svg { width:64px; height:64px; opacity:.4; margin-bottom:16px; }
.bk-empty h4 { color:var(--h-sub); font-size:1.05rem; font-weight:700; margin:0 0 6px; }
.bk-empty p  { font-size:.85rem; margin:0; }

/* Mobile toggle btn */
.bk-toggle-btn {
  display:none; width:100%; padding:12px 16px;
  background:var(--h-card); border:1px solid var(--h-border);
  border-radius:10px; font-weight:600; font-size:.82rem; color:var(--h-text);
  margin-bottom:16px; cursor:pointer;
  align-items:center; justify-content:space-between;
  transition:background var(--h-t);
}
.bk-toggle-btn:hover { background:var(--h-bg); }
.bk-toggle-btn svg { width:16px; height:16px; stroke:var(--h-muted); transition:transform .25s; }
.bk-toggle-btn.open svg.chevron { transform:rotate(180deg); }
@media(max-width:992px){ .bk-toggle-btn{ display:flex; } }

/* ── Category Tabs ── */
.bk-cats {
  display:flex; gap:8px; margin-bottom:20px;
  flex-wrap:wrap; align-items:center;
}
.bk-cat {
  display:inline-flex; align-items:center; gap:8px;
  padding:9px 18px; border-radius:50px;
  font-size:.82rem; font-weight:700;
  text-decoration:none; cursor:pointer;
  border:2px solid var(--h-border);
  background:var(--h-card); color:var(--h-sub);
  box-shadow:var(--h-shadow);
  transition:all var(--h-t);
  white-space:nowrap;
}
.bk-cat:hover { transform:translateY(-2px); box-shadow:var(--h-shadow-md); color:var(--h-text); }
.bk-cat-emoji { font-size:1rem; line-height:1; }
.bk-cat-count {
  background:#F1F5F9; color:var(--h-muted);
  border-radius:20px; padding:1px 8px;
  font-size:.68rem; font-weight:800;
  transition:all var(--h-t);
}
/* Active states per category */
.bk-cat.active { color:#fff !important; border-color:transparent; }
.bk-cat.active .bk-cat-count { background:rgba(255,255,255,.25); color:#fff; }
.bk-cat.cat-all.active    { background:linear-gradient(135deg,#4F46E5,#7C3AED); box-shadow:0 4px 14px rgba(79,70,229,.35); }
.bk-cat.cat-boat.active   { background:linear-gradient(135deg,#0EA5E9,#0284C7); box-shadow:0 4px 14px rgba(14,165,233,.35); }
.bk-cat.cat-stay.active   { background:linear-gradient(135deg,#10B981,#059669); box-shadow:0 4px 14px rgba(16,185,129,.35); }
.bk-cat.cat-cab.active    { background:linear-gradient(135deg,#F59E0B,#D97706); box-shadow:0 4px 14px rgba(245,158,11,.35); }
.bk-cat.cat-tour.active   { background:linear-gradient(135deg,#8B5CF6,#7C3AED); box-shadow:0 4px 14px rgba(139,92,246,.35); }
/* Hover colors (not active) */
.bk-cat.cat-boat:hover  { border-color:#BAE6FD; color:#0369A1; }
.bk-cat.cat-stay:hover  { border-color:#6EE7B7; color:#065F46; }
.bk-cat.cat-cab:hover   { border-color:#FDE68A; color:#B45309; }
.bk-cat.cat-tour:hover  { border-color:#DDD6FE; color:#6D28D9; }
@media(max-width:576px){
  .bk-cats { gap:6px; }
  .bk-cat { padding:7px 13px; font-size:.78rem; }
}

/* ══════════════════════════════════════
   NEW CLEAN TABLE DESIGN (screenshot)
══════════════════════════════════════ */
.bkt-card {
  background:#fff; border-radius:16px;
  border:1px solid #E5E7EB;
  box-shadow:0 1px 4px rgba(0,0,0,.05);
  overflow:hidden;
}
.bkt-head {
  display:flex; align-items:center; justify-content:space-between;
  padding:18px 24px;
  border-bottom:1px solid #F0F0F5;
}
.bkt-head-left { display:flex; align-items:center; gap:10px; }
.bkt-icon {
  width:34px; height:34px; border-radius:9px;
  background:#EEF2FF; display:flex; align-items:center; justify-content:center;
}
.bkt-icon [data-feather] { width:16px; height:16px; color:#4F46E5; }
.bkt-title { font-size:1rem; font-weight:700; color:#0F172A; }
.bkt-subtitle { font-size:.78rem; color:#9CA3AF; margin-left:4px; }
.bkt-new {
  display:inline-flex; align-items:center; gap:6px;
  background:#4F46E5; color:#fff;
  border:none; border-radius:9px;
  padding:8px 16px; font-size:.82rem; font-weight:700;
  cursor:pointer; text-decoration:none;
  transition:all .2s ease;
}
.bkt-new:hover { background:#4338CA; color:#fff; box-shadow:0 4px 12px rgba(79,70,229,.35); transform:translateY(-1px); }
.bkt-new [data-feather] { width:14px; height:14px; }

/* Table */
.bkt-table { width:100%; border-collapse:collapse; }
.bkt-table thead tr { border-bottom:2px solid #F0F0F5; }
.bkt-table thead th {
  font-size:.68rem; font-weight:700; letter-spacing:.8px;
  text-transform:uppercase; color:#9CA3AF;
  padding:14px 20px; text-align:left; white-space:nowrap;
  background:#FAFAFA;
}
.bkt-table tbody tr {
  border-bottom:1px solid #000;
  transition:background .15s ease; cursor:pointer;
}
.bkt-table tbody tr:last-child { border-bottom:none; }
.bkt-table tbody tr:hover { background:#F8FAFF; }
.bkt-table tbody td { padding:1px 36px; vertical-align:middle; }

/* Booking ID cell */
.bkt-id-link {
  font-size:.85rem; font-weight:700; color:#4F46E5;
  text-decoration:none; display:block;
  transition:color .15s;
}
.bkt-id-link:hover { color:#4338CA; text-decoration:underline; }
.bkt-id-date { font-size:.73rem; color:#9CA3AF; margin-top:2px; }

/* Guest cell */
.bkt-guest-name { font-size:.875rem; font-weight:700; color:#111827; }
.bkt-guest-phone { font-size:.75rem; color:#6B7280; margin-top:3px; display:flex; align-items:center; gap:4px; }

/* Type pill */
.bkt-type {
  display:inline-flex; align-items:center; gap:5px;
  padding:4px 11px; border-radius:20px;
  font-size:.75rem; font-weight:700; white-space:nowrap;
}
.bkt-type-boat  { background:#E0F2FE; color:#0369A1; }
.bkt-type-stay  { background:#DCFCE7; color:#166534; }
.bkt-type-cab   { background:#FEF3C7; color:#92400E; }
.bkt-type-tour  { background:#EDE9FE; color:#6D28D9; }
.bkt-type-other { background:#F3F4F6; color:#4B5563; }

/* Travel date */
.bkt-date { font-size:.855rem; font-weight:600; color:#374151; }

/* Status pill */
.bkt-status {
  display:inline-block; padding:4px 12px; border-radius:20px;
  font-size:.75rem; font-weight:700; white-space:nowrap;
}
.bkt-s-confirmed  { background:#DCFCE7; color:#166534; }
.bkt-s-completed  { background:#DBEAFE; color:#1D4ED8; }
.bkt-s-in_progress{ background:#FEF3C7; color:#92400E; }
.bkt-s-cancelled  { background:#FEE2E2; color:#991B1B; }
.bkt-s-not_started{ background:#F3F4F6; color:#6B7280; }
.bkt-s-pending    { background:#FEF3C7; color:#92400E; }

/* Payment pill */
.bkt-pay {
  display:inline-block; padding:4px 12px; border-radius:20px;
  font-size:.75rem; font-weight:700; white-space:nowrap;
}
.bkt-pay-paid    { background:#DCFCE7; color:#166534; }
.bkt-pay-partial { background:#FEF3C7; color:#92400E; }
.bkt-pay-unpaid  { background:#FEE2E2; color:#991B1B; }

/* Amount */
.bkt-amount { font-size:.9rem; font-weight:800; color:#111827; white-space:nowrap; }

/* ── Blink animation for due payments ── */
@keyframes bktBlink {
    0%,100% { opacity: 1; }
    50%      { opacity: 0.2; }
}
.bkt-due-blink {
    color: #EF4444;
    font-size: .72rem;
    font-weight: 700;
    margin-top: 2px;
    animation: bktBlink 1.2s ease-in-out infinite;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.bkt-due-blink::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #EF4444;
    display: inline-block;
    flex-shrink: 0;
}

/* Assigned */
.bkt-assigned { font-size:.82rem; color:#374151; font-weight:600; }
.bkt-assigned.empty { color:#9CA3AF; }

/* Action buttons */
.bkt-acts { display:grid; grid-template-columns:repeat(2,38px); gap:6px; }
.bkt-act {
  width:100%; height:36px; border-radius:9px; border:none;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer; transition:all .15s ease;
  text-decoration:none;
}
.bkt-act:hover { transform:translateY(-1px); }
.bkt-act [data-feather], .bkt-act svg { width:15px; height:15px; flex-shrink:0; }
.bkt-act-view  { background:#F3F4F6; color:#374151; }
.bkt-act-view:hover  { background:#E5E7EB; }
.bkt-act-edit  { background:#F3F4F6; color:#374151; }
.bkt-act-edit:hover  { background:#E5E7EB; }
.bkt-act-wa    { background:#22C55E; color:#fff; }
.bkt-act-wa:hover    { background:#16A34A; box-shadow:0 4px 10px rgba(34,197,94,.35); }
.bkt-act-print { background:#EEF2FF; color:#4F46E5; }
.bkt-act-print:hover { background:#E0E7FF; }
.bkt-act-del   { background:#FEE2E2; color:#EF4444; }
.bkt-act-del:hover   { background:#FECACA; }

/* Empty state */
.bkt-empty { text-align:center; padding:60px 24px; color:#9CA3AF; }
.bkt-empty [data-feather] { width:52px; height:52px; opacity:.3; display:block; margin:0 auto 16px; }
.bkt-empty h4 { color:#6B7280; font-size:1rem; font-weight:700; margin:0 0 6px; }
.bkt-empty p  { font-size:.84rem; margin:0; }

/* GST badge */
.bkt-gst { display:inline-block; font-size:.6rem; font-weight:700; background:linear-gradient(135deg,#F97316,#EA580C); color:#fff; border-radius:4px; padding:1px 5px; margin-left:5px; vertical-align:middle; }

/* ═══════════════════════════════════════════════════════
   MOBILE RESPONSIVE — App-card layout (≤768px)
═══════════════════════════════════════════════════════ */
@media(max-width:900px){

  /* ── Page padding ── */
  .bk-page { padding: 12px; }

  /* ── Header compact ── */
  .bk-header { padding:16px 18px; margin-bottom:14px; border-radius:14px; margin-top:50px; }
  .bk-header-title h1 { font-size:1.1rem; }
  .bk-header-title p  { font-size:.75rem; }
  .bk-header-actions  { gap:6px; }
  .bk-hbtn { padding:7px 10px; font-size:.75rem; }

  /* ── Stats: 2-col grid ── */
  .bk-stats { grid-template-columns:repeat(2,1fr); gap:8px; margin-bottom:14px; }

  /* ── Category tabs: horizontal scroll ── */
  .bk-cats {
    flex-wrap:nowrap;
    overflow-x:auto;
    gap:6px;
    padding-bottom:4px;
    margin-bottom:14px;
    -webkit-overflow-scrolling:touch;
    scrollbar-width:none;
  }
  .bk-cats::-webkit-scrollbar { display:none; }
  .bk-cat { flex-shrink:0; padding:7px 13px; font-size:.76rem; }

  /* ── Table: hide header, show cards ── */
  .bkt-table thead { display:none; }

  .bkt-table tbody tr {
    display:flex;
    flex-direction:column;
    background:#fff;
    border-radius:14px;
    border:1px solid #EAECF0;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
    margin-bottom:10px;
    padding:0;
    overflow:hidden;
    cursor:pointer;
    transition:box-shadow .2s, transform .2s;
  }
  .bkt-table tbody tr:hover {
    box-shadow:0 4px 16px rgba(79,70,229,.12);
    transform:translateY(-1px);
  }

  /* All td reset */
  .bkt-table tbody td {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 16px;
    border:none;
    border-bottom:1px solid #F3F4F6;
    font-size:.82rem;
    min-height:44px;
  }
  .bkt-table tbody td:last-child { border-bottom:none; }

  /* Label chip before each cell */
  .bkt-table tbody td[data-label]::before {
    content: attr(data-label);
    font-size:.65rem;
    font-weight:700;
    color:#9CA3AF;
    text-transform:uppercase;
    letter-spacing:.6px;
    flex-shrink:0;
    min-width:80px;
    margin-right:8px;
  }

  /* Card top strip: booking ref + status coloured bar */
  .bkt-table tbody td:first-child {
    background:linear-gradient(135deg,#EEF2FF 0%,#F5F3FF 100%);
    border-bottom:2px solid #E0E7FF;
    padding:12px 16px;
    font-weight:700;
    font-size:.88rem;
    color:#4F46E5;
    min-height:auto;
  }
  .bkt-table tbody td:first-child::before { display:none; }

  /* Actions row — full width button row */
  .bkt-acts {
    width:100%;
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(44px,1fr));
    gap:6px;
    padding:10px 16px 12px;
    background:#FAFAFA;
    border-top:1px solid #F0F0F5;
    border-bottom:none !important;
    margin-left:0;
  }
  .bkt-acts::before { display:none; }
  .bkt-act {
    height:40px;
    border-radius:10px;
    font-size:.72rem;
    gap:4px;
    flex-direction:column;
    justify-content:center;
  }
  .bkt-act [data-feather], .bkt-act svg { width:16px; height:16px; }
}

@media(max-width:480px){
  .bk-stats { grid-template-columns:repeat(2,1fr); }
  .bkt-table tbody td { font-size:.8rem; padding:9px 14px; }
}

/* ═══════════════════════════════════════════════════════
   MOBILE PAGINATION — App-style
═══════════════════════════════════════════════════════ */
.bk-pagination-wrap {
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:12px;
  margin-top:20px;
  padding:0 4px;
}
.bk-pagination-info {
  font-size:.78rem;
  color:#9CA3AF;
  font-weight:500;
  text-align:center;
}
.bk-pagination-info strong { color:#4F46E5; font-weight:700; }

/* Override Laravel default pagination */
.bk-pagination-wrap nav { width:100%; }
.bk-pagination-wrap .pagination {
  display:flex;
  justify-content:center;
  flex-wrap:wrap;
  gap:6px;
  list-style:none;
  padding:0; margin:0;
}
.bk-pagination-wrap .page-item .page-link {
  min-width:38px;
  height:38px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:10px;
  border:1.5px solid #E5E7EB;
  background:#fff;
  color:#374151;
  font-size:.82rem;
  font-weight:600;
  padding:0 10px;
  transition:all .18s ease;
  text-decoration:none;
  line-height:1;
}
.bk-pagination-wrap .page-item .page-link:hover {
  background:#EEF2FF;
  border-color:#6366F1;
  color:#4F46E5;
}
.bk-pagination-wrap .page-item.active .page-link {
  background:linear-gradient(135deg,#4F46E5,#7C3AED);
  border-color:transparent;
  color:#fff;
  box-shadow:0 4px 12px rgba(79,70,229,.35);
}
.bk-pagination-wrap .page-item.disabled .page-link {
  background:#F9FAFB;
  color:#D1D5DB;
  border-color:#F3F4F6;
  cursor:not-allowed;
}
.bk-pagination-wrap .page-item:first-child .page-link,
.bk-pagination-wrap .page-item:last-child .page-link {
  font-size:1rem;
  font-weight:700;
}

@media(max-width:640px){
  /* On mobile show only: prev · 1 2 3 · next */
  .bk-pagination-wrap .page-item:not(:first-child):not(:last-child):not(.active) {
    /* hide number items far from active — let Laravel handle dots */
  }
  .bk-pagination-wrap .page-item .page-link {
    min-width:36px;
    height:36px;
    font-size:.78rem;
    border-radius:9px;
  }
}
</style>

<div class="bk-page">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ── Header ── --}}
  <div class="bk-header">
    <div class="bk-header-title">
      <h1><i data-feather="calendar" style="width:20px;height:20px;stroke:#fff;display:inline;vertical-align:sub;margin-right:8px;"></i>Tour Bookings</h1>
      <p>Manage confirmed tour bookings and reservations</p>
    </div>
    <div class="bk-header-actions">
      <a href="{{ route('bookings.create-direct') }}" class="bk-hbtn primary">
        <i data-feather="plus"></i> New Booking
      </a>
      <a href="{{ route('bookings.calendar') }}" class="bk-hbtn">
        <i data-feather="calendar"></i> <span class="d-none d-md-inline">Calendar</span>
      </a>
      <a href="{{ route('bookings.trash') }}" class="bk-hbtn">
        <i data-feather="trash-2"></i> <span class="d-none d-md-inline">Trash</span>
      </a>
    </div>
  </div>

  {{-- ── Stats ── --}}
  <div class="bk-stats">
    <a href="{{ route('bookings.index') }}" class="bk-stat" style="--stat-color:#4F46E5;">
      <div class="bk-stat-label">Total</div>
      <div class="bk-stat-value">{{ $stats['total'] }}</div>
    </a>
    <a href="{{ route('bookings.index') }}?status=not_started" class="bk-stat" style="--stat-color:#64748B;">
      <div class="bk-stat-label">Not Started</div>
      <div class="bk-stat-value">{{ $stats['not_started'] }}</div>
    </a>
    <a href="{{ route('bookings.index') }}?status=confirmed" class="bk-stat" style="--stat-color:#3B82F6;">
      <div class="bk-stat-label">Confirmed</div>
      <div class="bk-stat-value">{{ $stats['confirmed'] }}</div>
    </a>
    <a href="{{ route('bookings.index') }}?status=in_progress" class="bk-stat" style="--stat-color:#F59E0B;">
      <div class="bk-stat-label">Ongoing</div>
      <div class="bk-stat-value">{{ $stats['in_progress'] }}</div>
    </a>
    <a href="{{ route('bookings.index') }}?status=completed" class="bk-stat" style="--stat-color:#10B981;">
      <div class="bk-stat-label">Completed</div>
      <div class="bk-stat-value">{{ $stats['completed'] }}</div>
    </a>
    <a href="{{ route('bookings.index') }}?status=cancelled" class="bk-stat" style="--stat-color:#EF4444;">
      <div class="bk-stat-label">Cancelled</div>
      <div class="bk-stat-value">{{ $stats['cancelled'] }}</div>
    </a>
    <a href="{{ route('bookings.index') }}?has_due=1" class="bk-stat" style="--stat-color:#F97316;">
      <div class="bk-stat-label">With Due</div>
      <div class="bk-stat-value">{{ $stats['with_due'] }}</div>
    </a>
  </div>

  {{-- ── Category Tabs ── --}}
  @php
    $activeCat  = request('service_type', '');
    $catBase    = \App\Models\Booking::where('booking_status','confirmed');
    $countFor   = fn(string $keyword) => (clone $catBase)
        ->whereHas('quotation.items.serviceTemplate.serviceType',
            fn($q) => $q->where('name','LIKE',"%{$keyword}%"))
        ->count();
    $catCounts = [
      'boat' => $countFor('boat'),
      'stay' => $countFor('stay'),
      'cab'  => $countFor('cab'),
      'tour' => $countFor('tour'),
    ];
  @endphp
  <div class="bk-cats">
    {{-- All --}}
    <a href="{{ route('bookings.index') }}"
       class="bk-cat cat-all {{ !$activeCat ? 'active' : '' }}">
      <span class="bk-cat-emoji">📋</span>
      All Bookings
      <span class="bk-cat-count">{{ $stats['total'] }}</span>
    </a>

    {{-- Boat --}}
    <a href="{{ route('bookings.index') }}?service_type=boat&status=confirmed"
       class="bk-cat cat-boat {{ str_contains(strtolower($activeCat),'boat') ? 'active' : '' }}">
      <span class="bk-cat-emoji">⛵</span>
      Boat
      <span class="bk-cat-count">{{ $catCounts['boat'] }}</span>
    </a>

    {{-- Stay --}}
    <a href="{{ route('bookings.index') }}?service_type=stay&status=confirmed"
       class="bk-cat cat-stay {{ str_contains(strtolower($activeCat),'stay') ? 'active' : '' }}">
      <span class="bk-cat-emoji">🏨</span>
      Stay
      <span class="bk-cat-count">{{ $catCounts['stay'] }}</span>
    </a>

    {{-- Cab --}}
    <a href="{{ route('bookings.index') }}?service_type=cab&status=confirmed"
       class="bk-cat cat-cab {{ str_contains(strtolower($activeCat),'cab') ? 'active' : '' }}">
      <span class="bk-cat-emoji">🚗</span>
      Cab
      <span class="bk-cat-count">{{ $catCounts['cab'] }}</span>
    </a>

    {{-- Tour Packages --}}
    <a href="{{ route('bookings.index') }}?service_type=tour&status=confirmed"
       class="bk-cat cat-tour {{ str_contains(strtolower($activeCat),'tour') ? 'active' : '' }}">
      <span class="bk-cat-emoji">🗺️</span>
      Tour Packages
      <span class="bk-cat-count">{{ $catCounts['tour'] }}</span>
    </a>
  </div>

  {{-- ── Filters (single line) ── --}}
  @php
    $activeFilters = collect(['status','payment_status','service_date_from','service_date_to','has_due','staff_id','service_type','search'])->filter(fn($k)=>request($k))->count();
  @endphp
  <div class="bk-filter" style="padding:14px 20px;">
    <form method="GET" action="{{ route('bookings.index') }}">
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">

        <select name="status" class="form-select form-select-sm" style="width:130px;">
          <option value="">All Status</option>
          <option value="not_started"  {{ request('status')=='not_started'  ? 'selected':'' }}>Not Started</option>
          <option value="confirmed"    {{ request('status')=='confirmed'    ? 'selected':'' }}>Confirmed</option>
          <option value="in_progress"  {{ request('status')=='in_progress'  ? 'selected':'' }}>Ongoing</option>
          <option value="completed"    {{ request('status')=='completed'    ? 'selected':'' }}>Completed</option>
          <option value="cancelled"    {{ request('status')=='cancelled'    ? 'selected':'' }}>Cancelled</option>
        </select>

        <select name="payment_status" class="form-select form-select-sm" style="width:130px;">
          <option value="">All Payment</option>
          <option value="unpaid"         {{ request('payment_status')=='unpaid'         ? 'selected':'' }}>Unpaid</option>
          <option value="partially_paid" {{ request('payment_status')=='partially_paid' ? 'selected':'' }}>Partial</option>
          <option value="fully_paid"     {{ request('payment_status')=='fully_paid'     ? 'selected':'' }}>Fully Paid</option>
        </select>

        <select name="service_type" class="form-select form-select-sm" style="width:130px;">
          <option value="">All Types</option>
          @foreach($serviceTypes as $st)
            <option value="{{ strtolower($st->name) }}" {{ request('service_type')==strtolower($st->name) ? 'selected':'' }}>{{ $st->name }}</option>
          @endforeach
        </select>

        @if(auth('admin')->user()->hasAnyRole(['Super Admin','Admin','Manager']))
        <select name="staff_id" class="form-select form-select-sm" style="width:130px;">
          <option value="">All Staff</option>
          @foreach($staffList as $staff)
            <option value="{{ $staff->id }}" {{ request('staff_id')==$staff->id ? 'selected':'' }}>{{ $staff->name }}</option>
          @endforeach
        </select>
        @endif

        <div class="input-group input-group-sm" style="width:220px;">
          <input type="date" name="service_date_from" class="form-control" value="{{ request('service_date_from') }}" placeholder="From">
          <span class="input-group-text" style="padding:0 6px;">–</span>
          <input type="date" name="service_date_to" class="form-control" value="{{ request('service_date_to') }}" placeholder="To">
        </div>

        <div class="input-group input-group-sm" style="flex:1;min-width:180px;max-width:260px;">
          <input type="text" name="search" class="form-control" placeholder="Search name, booking…" value="{{ request('search') }}">
        </div>

        <button type="submit" class="btn btn-primary btn-sm px-3" style="white-space:nowrap;">
          <i data-feather="search" style="width:13px;height:13px;"></i> Search
        </button>

        @if($activeFilters)
          <a href="{{ route('bookings.index') }}" class="btn btn-outline-danger btn-sm px-3" title="Clear filters" style="white-space:nowrap;">
            <i data-feather="x" style="width:13px;height:13px;"></i> Clear
          </a>
        @endif

      </div>
    </form>
  </div>

  {{-- ── Bookings List ── --}}
  <div class="bkt-card">

    {{-- Card header --}}
    <div class="bkt-head">
      <div class="bkt-head-left">
        <i data-feather="list" style="width:18px;height:18px;"></i>
        <span>Bookings</span>
        @if($bookings->total() > 0)
          <span style="font-size:.75rem;font-weight:600;color:#6B7280;margin-left:4px;">({{ $bookings->total() }})</span>
        @endif
      </div>
      <a href="{{ route('bookings.create-direct') }}" class="bkt-new">
        <i data-feather="plus" style="width:14px;height:14px;"></i> New
      </a>
    </div>

    @if($bookings->isEmpty())
      <div class="bkt-empty">
        <i data-feather="calendar"></i>
        <h4>No Bookings Found</h4>
        <p>Try adjusting the filters or create a new booking.</p>
      </div>
    @else
      <div style="overflow-x:auto;">
        <table class="bkt-table">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Guest</th>
              <th>Type</th>
              <th>Travel Date</th>
              <th>Status</th>
              <th>Amount</th>
              <th style="display:none;">Assigned</th>
              <th style="width:1%;white-space:nowrap;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bookings as $booking)
              @php
                /* ── Service type ── */
                $stName  = optional($booking->quotation?->items?->first()?->serviceTemplate?->serviceType)->name ?? '';
                $stLower = strtolower($stName);
                if      (str_contains($stLower,'boat'))                          { $typeKey='boat';  $typeLabel='Boat';  $typeEmoji='⛵'; }
                elseif  (str_contains($stLower,'stay')||str_contains($stLower,'hotel')) { $typeKey='stay';  $typeLabel='Stay';  $typeEmoji='🏨'; }
                elseif  (str_contains($stLower,'cab'))                           { $typeKey='cab';   $typeLabel='Cab';   $typeEmoji='🚗'; }
                elseif  (str_contains($stLower,'tour'))                          { $typeKey='tour';  $typeLabel='Tour';  $typeEmoji='🗺️'; }
                else                                                             { $typeKey='other'; $typeLabel=$stName ?: 'Booking'; $typeEmoji='📋'; }

                /* ── Payment status ── */
                $payStatus = $booking->pending_amount <= 0
                    ? 'paid'
                    : ($booking->paid_amount > 0 ? 'partial' : 'unpaid');
                $payLabel  = ['paid'=>'Paid','partial'=>'Partial','unpaid'=>'Unpaid'][$payStatus];

                /* ── Booking status ── */
                $statusKey   = $booking->booking_status ?? 'pending';
                $statusLabel = match($statusKey) {
                    'not_started' => 'Not Started',
                    'confirmed'   => 'Confirmed',
                    'in_progress' => 'Ongoing',
                    'completed'   => 'Completed',
                    'cancelled'   => 'Cancelled',
                    default       => ucfirst(str_replace('_',' ',$statusKey)),
                };

                /* ── Travel date ── */
                $firstItem  = $booking->quotation?->items?->first();
                $travelDate = optional($firstItem)->service_date
                    ? \Carbon\Carbon::parse($firstItem->service_date)->format('d M Y')
                    : $booking->booking_date->format('d M Y');

                /* ── Assigned vendor ── */
                $assigned = optional($booking->serviceAssignments->first()?->serviceProvider)->name;

                /* ── WhatsApp ── */
                $phone = preg_replace('/[^0-9]/', '', $booking->lead->contact ?? '');
              @endphp

              <tr onclick="window.location='{{ route('bookings.show', $booking->id) }}'" style="cursor:pointer;">

                {{-- BOOKING ID --}}
                <td data-label="Booking ID">
                  <div style="font-size:.82rem;font-weight:700;color:#111827;white-space:nowrap;">
                    {{ $booking->booking_number }}
                    @if($booking->is_gst_invoice)<span class="bkt-gst">GST</span>@endif
                  </div>
                  <div style="font-size:.73rem;color:#9CA3AF;margin-top:2px;">{{ $booking->booking_date->format('d M Y') }}</div>
                </td>

                {{-- GUEST --}}
                <td data-label="Guest">
                  <div style="font-size:.85rem;font-weight:700;{{ $booking->pending_amount > 0 ? 'color:#c12f00;background:#FEF08A;padding:1px 6px;border-radius:4px;display:inline-block;' : 'color:#111827;' }}">{{ $booking->lead->guest_name ?? 'N/A' }}</div>
                  <div style="font-size:.73rem;color:#6B7280;margin-top:2px;">{{ $booking->lead->contact ?? '–' }}</div>
                </td>

                {{-- TYPE --}}
                <td data-label="Type">
                  <span class="bkt-type bkt-type-{{ $typeKey }}">{{ $typeEmoji }} {{ $typeLabel }}</span>
                </td>

                {{-- TRAVEL DATE --}}
                <td data-label="Travel Date">
                  <span style="font-size:.82rem;color:#374151;font-weight:600;white-space:nowrap;">{{ $travelDate }}</span>
                </td>

                {{-- STATUS --}}
                <td data-label="Status">
                  <span class="bkt-status bkt-s-{{ $statusKey }}">{{ $statusLabel }}</span>
                </td>

                {{-- AMOUNT --}}
                <td data-label="Amount">
                  <div class="bkt-amount">₹{{ number_format($booking->total_amount, 0) }}</div>
                  @if($booking->pending_amount > 0)
                    <div class="bkt-due-blink">Due ₹{{ number_format($booking->pending_amount, 0) }}</div>
                  @endif
                </td>

                {{-- ASSIGNED --}}
                <td data-label="Assigned" style="display:none;">
                  @if($assigned)
                    <span class="bkt-assigned">{{ $assigned }}</span>
                  @else
                    <span class="bkt-assigned empty">—</span>
                  @endif
                </td>

                {{-- ACTIONS --}}
                <td onclick="event.stopPropagation()" style="white-space:nowrap;padding:0!important;border-bottom:none!important;">
                  <div class="bkt-acts">
                    <a href="{{ route('bookings.show', $booking->id) }}" class="bkt-act bkt-act-view" title="View">
                      <i data-feather="eye"></i>
                    </a>
<a href="{{ route('bookings.edit', $booking->id) }}" class="bkt-act bkt-act-edit" title="Edit">
                      <i data-feather="edit-2"></i>
                    </a>
                    @if($booking->is_gst_invoice)
                      <a href="{{ route('booking.gst-invoice', $booking->id) }}" target="_blank" class="bkt-act bkt-act-print" title="GST Invoice">
                        <i data-feather="printer"></i>
                      </a>
                    @else
                      <a href="{{ route('booking.invoice', $booking->id) }}" target="_blank" class="bkt-act bkt-act-print" title="Invoice">
                        <i data-feather="printer"></i>
                      </a>
                    @endif
                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" style="display:contents;">
                      @csrf @method('DELETE')
                      <button type="button" class="bkt-act bkt-act-del deleteBtn" title="Delete"
                              data-name="{{ $booking->booking_number }}">
                        <i data-feather="trash-2"></i>
                      </button>
                    </form>
                  </div>
                </td>

              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif

  </div>

  {{-- Pagination --}}
  @if($bookings->hasPages())
    <div class="bk-pagination-wrap">
      <div class="bk-pagination-info">
        Showing <strong>{{ $bookings->firstItem() }}</strong>–<strong>{{ $bookings->lastItem() }}</strong>
        of <strong>{{ $bookings->total() }}</strong> bookings
        &nbsp;·&nbsp; Page <strong>{{ $bookings->currentPage() }}</strong> of <strong>{{ $bookings->lastPage() }}</strong>
      </div>
      {!! $bookings->appends(request()->query())->links() !!}
    </div>
  @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  feather.replace();

  /* ── Delete confirmation ── */
  document.querySelectorAll('.deleteBtn').forEach(function(btn){
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      const name = this.dataset.name;
      const form = this.closest('form');
      Swal.fire({
        title: 'Delete Booking?',
        html: '<p>Delete booking <strong>' + name + '</strong>?</p><p class="text-danger"><strong>This cannot be undone.</strong></p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel'
      }).then(function(result){
        if(result.isConfirmed) form.submit();
      });
    });
  });

  setTimeout(function(){ feather.replace(); }, 100);
});
</script>
@endsection
