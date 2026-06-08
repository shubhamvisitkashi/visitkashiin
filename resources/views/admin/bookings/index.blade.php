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
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 50px;
  margin-bottom: 20px;
  position: relative;
  overflow: hidden;
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

/* ── Tour booking inline expand ── */
.bk-tour-row:hover { background:#FAF5FF !important; }
.bk-tour-row.expanded { background:#F5F3FF !important; border-left:3px solid #7C3AED; }

.bk-expand-row > td { background:#fff; }
.bk-expand-panel { border:2px solid #C4B5FD; border-top:none; border-radius:0 0 12px 12px; overflow:hidden; }

.bk-exp-header { background:linear-gradient(135deg,#4338CA,#7C3AED); padding:12px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.bk-exp-btn { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:7px; font-size:.75rem; font-weight:700; text-decoration:none !important; transition:.15s; cursor:pointer; }
.bk-exp-btn:hover { opacity:.85; }

.bk-exp-body { padding:16px 18px; background:#fff; }
.bk-exp-grid { display:grid; grid-template-columns:1fr 1.4fr 1fr; gap:16px; }
@media(max-width:900px){ .bk-exp-grid { grid-template-columns:1fr 1fr; } }
@media(max-width:600px){ .bk-exp-grid { grid-template-columns:1fr; } }

.bk-exp-section-title { font-size:.68rem; font-weight:800; color:#7C3AED; text-transform:uppercase; letter-spacing:.06em; margin-bottom:10px; padding-bottom:6px; border-bottom:1.5px solid #E9D5FF; }
.bk-exp-row { display:flex; justify-content:space-between; align-items:center; padding:5px 0; font-size:.8rem; border-bottom:1px dashed #EDE9FE; }
.bk-exp-row:last-child { border-bottom:none; }
.bk-exp-row span { color:#6B7280; }
.bk-exp-row strong { color:#111827; }
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

  /* ── Page ── */
  .bk-page { padding:10px 10px 70px; }

  /* ── Header ── */
  .bk-header { padding:14px 16px; margin-bottom:12px; border-radius:14px; margin-top:58px; }
  .bk-header-title h1 { font-size:1rem; }
  .bk-header-title p  { font-size:.72rem; }
  .bk-header-actions  { gap:5px; }
  .bk-hbtn { padding:6px 10px; font-size:.72rem; }

  /* ── Stats ── */
  .bk-stats { grid-template-columns:repeat(2,1fr); gap:8px; margin-bottom:12px; }

  /* ── Tabs: horizontal scroll ── */
  .bk-cats {
    flex-wrap:nowrap; overflow-x:auto; gap:6px;
    padding-bottom:4px; margin-bottom:12px;
    -webkit-overflow-scrolling:touch; scrollbar-width:none;
  }
  .bk-cats::-webkit-scrollbar { display:none; }
  .bk-cat { flex-shrink:0; padding:7px 13px; font-size:.74rem; }

  /* ── Filter ── */
  .bk-filter { padding:10px 12px; }
  .bk-filter form > div { flex-direction:column !important; gap:8px !important; }
  .bk-filter .form-select, .bk-filter .form-control { width:100% !important; max-width:none !important; }
  .bk-filter .input-group { width:100% !important; max-width:none !important; }
  .bk-filter .btn { width:100% !important; }

  /* ── Table → Card view ── */
  .bkt-table thead { display:none; }

  .bkt-table tbody tr {
    display:block;
    background:#fff;
    border-radius:16px;
    border:1px solid #EAECF0;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
    margin-bottom:12px;
    overflow:hidden;
    cursor:pointer;
    transition:box-shadow .2s, transform .2s;
  }
  .bkt-table tbody tr:hover {
    box-shadow:0 6px 20px rgba(79,70,229,.14);
    transform:translateY(-2px);
  }

  /* Hide checkbox column on mobile */
  .bkt-table tbody td:first-child { display:none; }

  /* All data cells */
  .bkt-table tbody td {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 16px;
    border:none;
    border-bottom:1px solid #F3F4F6;
    font-size:.82rem;
    min-height:42px;
  }
  .bkt-table tbody td:last-child { border-bottom:none; }

  /* Label prefix for each cell */
  .bkt-table tbody td[data-label]::before {
    content: attr(data-label);
    font-size:.64rem; font-weight:700; color:#9CA3AF;
    text-transform:uppercase; letter-spacing:.6px;
    flex-shrink:0; min-width:82px; margin-right:8px;
  }

  /* Booking ID cell — card header strip */
  .bkt-table tbody td[data-label="Booking ID"] {
    background:linear-gradient(135deg,#EEF2FF,#F5F3FF);
    border-bottom:2px solid #E0E7FF;
    padding:11px 16px;
    min-height:auto;
    align-items:flex-start;
    flex-direction:column;
    gap:2px;
  }
  .bkt-table tbody td[data-label="Booking ID"]::before { display:none; }

  /* Actions row */
  .bkt-table tbody td:not([data-label]):not(:first-child) {
    background:#FAFBFF;
    border-top:1px solid #F0F0F5;
    padding:10px 16px 12px;
    border-bottom:none !important;
  }
  .bkt-acts {
    width:100%;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:6px;
  }
  .bkt-acts::before { display:none; }
  .bkt-act {
    height:42px; border-radius:10px;
    font-size:.7rem; gap:3px;
    flex-direction:column; justify-content:center;
  }
  .bkt-act [data-feather], .bkt-act svg { width:15px; height:15px; }

  /* Boat/Cab section card table on mobile */
  .bkt-card { border-radius:14px; }
  .bkt-head { padding:12px 14px; }
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
  .bk-pagination-wrap .page-item .page-link {
    min-width:36px;
    height:36px;
    font-size:.78rem;
    border-radius:9px;
  }
}

/* ══════════════════════════════════════════════════
   TOUR DASHBOARD METRICS
══════════════════════════════════════════════════ */
.tm-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:18px;}
@media(max-width:1200px){.tm-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:640px){.tm-grid{grid-template-columns:repeat(2,1fr);}}
.tm-card{background:#fff;border-radius:12px;border:1px solid #E5E7EB;padding:14px 16px;display:flex;flex-direction:column;gap:4px;position:relative;overflow:hidden;transition:box-shadow .2s;}
.tm-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.08);}
.tm-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.tm-card.c1::before{background:linear-gradient(90deg,#4F46E5,#7C3AED);}
.tm-card.c2::before{background:linear-gradient(90deg,#0EA5E9,#38BDF8);}
.tm-card.c3::before{background:linear-gradient(90deg,#F59E0B,#FBBF24);}
.tm-card.c4::before{background:linear-gradient(90deg,#10B981,#34D399);}
.tm-card.c5::before{background:linear-gradient(90deg,#8B5CF6,#A78BFA);}
.tm-card.c6::before{background:linear-gradient(90deg,#EF4444,#F87171);}
.tm-lbl{font-size:.67rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.05em;}
.tm-val{font-size:1.5rem;font-weight:800;color:#111827;line-height:1;}
.tm-sub{font-size:.7rem;color:#6B7280;margin-top:2px;}
.tm-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:1.6rem;opacity:.12;}

/* ══ Bulk Action Toolbar ══ */
.bk-bulk-bar{background:#1E1B4B;border-radius:10px;padding:10px 16px;margin-bottom:12px;display:none;align-items:center;gap:10px;flex-wrap:wrap;}
.bk-bulk-bar.show{display:flex;}
.bk-bulk-count{color:#A5B4FC;font-size:.8rem;font-weight:700;flex:1;white-space:nowrap;}
.bk-bulk-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:7px;font-size:.76rem;font-weight:700;cursor:pointer;border:none;transition:.15s;text-decoration:none !important;white-space:nowrap;}
.bk-bulk-btn:hover{opacity:.88;}
.bkb-green{background:#059669;color:#fff;}
.bkb-red{background:#DC2626;color:#fff;}
.bkb-blue{background:#2563EB;color:#fff;}
.bkb-amber{background:#D97706;color:#fff;}
.bkb-gray{background:rgba(255,255,255,.15);color:#fff;}

/* ══ Enhanced Action Buttons ══ */
.bkt-acts{display:flex;gap:3px;align-items:center;justify-content:flex-end;flex-wrap:nowrap;}
.bkt-act{width:28px;height:28px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;text-decoration:none !important;border:none;transition:.15s;position:relative;}
.bkt-act svg,.bkt-act i[data-feather]{width:13px;height:13px;}
.bkt-act-view{background:#EFF6FF;color:#1D4ED8;}
.bkt-act-edit{background:#F5F3FF;color:#7C3AED;}
.bkt-act-print{background:#F0FDF4;color:#059669;}
.bkt-act-del{background:#FEF2F2;color:#DC2626;}
.bkt-act-wa{background:#DCFCE7;color:#16A34A;}
.bkt-act-pdf{background:#FEF3C7;color:#D97706;}
.bkt-act:hover{opacity:.8;transform:scale(1.1);}

/* Tooltip */
.bkt-act[data-tip]{position:relative;}
.bkt-act[data-tip]::after{content:attr(data-tip);position:absolute;bottom:calc(100% + 5px);left:50%;transform:translateX(-50%);background:#1F2937;color:#fff;font-size:.65rem;font-weight:600;padding:3px 7px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity .15s;}
.bkt-act[data-tip]:hover::after{opacity:1;}

/* ══ Checkbox ══ */
.bk-row-check{width:16px;height:16px;accent-color:#4F46E5;cursor:pointer;}

/* ══ Quick View Modal ══ */
.qv-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9998;display:none;align-items:center;justify-content:center;padding:16px;}
.qv-overlay.open{display:flex;}
.qv-modal{background:#fff;border-radius:16px;width:100%;max-width:780px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.25);animation:qvIn .22s ease;}
@keyframes qvIn{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
.qv-header{background:linear-gradient(135deg,#4F46E5,#7C3AED);padding:18px 22px;display:flex;align-items:center;justify-content:space-between;border-radius:16px 16px 0 0;}
.qv-title{color:#fff;font-size:1rem;font-weight:800;}
.qv-close{background:rgba(255,255,255,.2);border:none;color:#fff;width:30px;height:30px;border-radius:8px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;}
.qv-close:hover{background:rgba(255,255,255,.35);}
.qv-body{padding:20px;}
.qv-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
@media(max-width:560px){.qv-grid{grid-template-columns:1fr;}}
.qv-field{background:#F9FAFB;border-radius:8px;padding:10px 12px;}
.qv-field-label{font-size:.63rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.qv-field-value{font-size:.88rem;font-weight:600;color:#111827;}
.qv-field-full{grid-column:1/-1;}
.qv-status-bar{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;}
.qv-chip{display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:700;padding:4px 11px;border-radius:20px;}
.qv-chip-confirmed{background:#D1FAE5;color:#065F46;}
.qv-chip-paid{background:#D1FAE5;color:#065F46;}
.qv-chip-partial{background:#FEF3C7;color:#92400E;}
.qv-chip-due{background:#FEE2E2;color:#991B1B;}
.qv-chip-cancelled{background:#F3F4F6;color:#374151;}
.qv-actions{display:flex;gap:8px;flex-wrap:wrap;padding:14px 20px;border-top:1px solid #F3F4F6;background:#F9FAFB;border-radius:0 0 16px 16px;}
.qv-act-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-size:.79rem;font-weight:700;text-decoration:none !important;border:none;cursor:pointer;transition:.15s;}
.qv-act-btn:hover{opacity:.88;}
.qvb-edit{background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;}
.qvb-conf{background:#059669;color:#fff;}
.qvb-wa{background:#16A34A;color:#fff;}
.qvb-pdf{background:#D97706;color:#fff;}
.qvb-close{background:#F3F4F6;color:#374151;}

/* ══ Timeline ══ */
.qv-timeline{padding:4px 0;}
.qv-tl-item{display:flex;gap:12px;padding:8px 0;position:relative;}
.qv-tl-item+.qv-tl-item::before{content:'';position:absolute;left:10px;top:0;bottom:0;width:1px;background:#E5E7EB;}
.qv-tl-dot{width:20px;height:20px;border-radius:50%;background:#4F46E5;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;font-size:.6rem;color:#fff;}
.qv-tl-dot.done{background:#059669;}
.qv-tl-dot.pending{background:#D1D5DB;}
.qv-tl-text{flex:1;}
.qv-tl-event{font-size:.82rem;font-weight:700;color:#111827;}
.qv-tl-meta{font-size:.72rem;color:#6B7280;margin-top:2px;}

/* ══ Status badge improvements ══ */
.bkt-status-confirmed{background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;}
.bkt-status-not_started{background:#F3F4F6;color:#374151;border:1px solid #E5E7EB;}
.bkt-status-in_progress{background:#FEF3C7;color:#92400E;border:1px solid #FDE68A;}
.bkt-status-completed{background:#DBEAFE;color:#1E40AF;border:1px solid #BFDBFE;}
.bkt-status-cancelled{background:#FEE2E2;color:#991B1B;border:1px solid #FECACA;}

/* ══ Row select highlight ══ */
tr.bk-row-selected > td { background:#EEF2FF !important; }
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
      @if(str_contains(strtolower(request('service_type','')), 'tour'))
      <a href="{{ route('tour-booking.create') }}" class="bk-hbtn primary" style="background:linear-gradient(135deg,#7C3AED,#5B21B6);box-shadow:0 4px 14px rgba(124,58,237,.35);">
        <i data-feather="map"></i> New Tour Booking
      </a>
      @else
      <a href="{{ route('bookings.create-direct') }}" class="bk-hbtn primary">
        <i data-feather="plus"></i> New Booking
      </a>
      @endif
      <a href="{{ route('bookings.calendar') }}" class="bk-hbtn">
        <i data-feather="calendar"></i> <span class="d-none d-md-inline">Calendar</span>
      </a>
      <a href="{{ route('bookings.trash') }}" class="bk-hbtn">
        <i data-feather="trash-2"></i> <span class="d-none d-md-inline">Trash</span>
      </a>
    </div>
  </div>

  {{-- ══ TOUR DASHBOARD METRICS (only on tour filter) ══ --}}
  @if(str_contains(strtolower(request('service_type','')), 'tour'))
  @php
    $tourBase = \App\Models\Booking::whereHas('quotation.items.serviceType', fn($q) => $q->where('name','like','%tour%'));
    $todayRev = (clone $tourBase)->whereDate('booking_date', today())->sum('total_amount');
    $monthRev = (clone $tourBase)->whereMonth('booking_date', now()->month)->whereYear('booking_date', now()->year)->sum('total_amount');
    $todayTours = (clone $tourBase)->whereDate('booking_date', today())->count();
    $pendingPay = (clone $tourBase)->where('pending_amount','>',0)->count();
    $totalConf  = (clone $tourBase)->where('booking_status','confirmed')->count();
    $upcoming   = (clone $tourBase)->where('booking_status','confirmed')->whereHas('lead', fn($q) => $q->where('booking_start_date','>=',today()))->count();
  @endphp
  <div class="tm-grid">
    <div class="tm-card c1">
      <div class="tm-lbl">Total Confirmed</div>
      <div class="tm-val">{{ $totalConf }}</div>
      <div class="tm-sub">Tour packages</div>
      <div class="tm-icon">🗺️</div>
    </div>
    <div class="tm-card c2">
      <div class="tm-lbl">Today's Bookings</div>
      <div class="tm-val">{{ $todayTours }}</div>
      <div class="tm-sub">{{ today()->format('d M') }}</div>
      <div class="tm-icon">📅</div>
    </div>
    <div class="tm-card c3">
      <div class="tm-lbl">Pending Payments</div>
      <div class="tm-val">{{ $pendingPay }}</div>
      <div class="tm-sub">Due balance</div>
      <div class="tm-icon">💳</div>
    </div>
    <div class="tm-card c4">
      <div class="tm-lbl">Today's Revenue</div>
      <div class="tm-val">₹{{ number_format($todayRev/1000,1) }}K</div>
      <div class="tm-sub">{{ today()->format('d M Y') }}</div>
      <div class="tm-icon">💰</div>
    </div>
    <div class="tm-card c5">
      <div class="tm-lbl">Monthly Revenue</div>
      <div class="tm-val">₹{{ number_format($monthRev/1000,1) }}K</div>
      <div class="tm-sub">{{ now()->format('M Y') }}</div>
      <div class="tm-icon">📊</div>
    </div>
    <div class="tm-card c6">
      <div class="tm-lbl">Upcoming Tours</div>
      <div class="tm-val">{{ $upcoming }}</div>
      <div class="tm-sub">Scheduled ahead</div>
      <div class="tm-icon">🚀</div>
    </div>
  </div>
  @endif

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
    $activeCat = request('service_type', '');

    // Stay & Tour come from the bookings table (via quotation service types)
    $catBase  = \App\Models\Booking::where('booking_status','confirmed');
    $countStay = (clone $catBase)->whereHas('quotation.items.serviceTemplate.serviceType',
        function($q){ $q->where('name','LIKE','%stay%')->orWhere('name','LIKE','%hotel%'); })->count();
    $countTour = (clone $catBase)->whereHas('quotation.items.serviceTemplate.serviceType',
        function($q){ $q->where('name','LIKE','%tour%')->orWhere('name','LIKE','%package%'); })->count();

    // Boat & Cab have their own dedicated tables
    $countBoat = \App\Models\BoatBooking::count();
    $countCab  = \App\Models\CabBooking::count();
    $countAll  = $stats['total'] + $countBoat + $countCab;
  @endphp
  <div class="bk-cats">
    {{-- All --}}
    <a href="{{ route('bookings.index') }}"
       class="bk-cat cat-all {{ !$activeCat ? 'active' : '' }}">
      <span class="bk-cat-emoji">📋</span>
      All Bookings
      <span class="bk-cat-count">{{ $countAll }}</span>
    </a>

    {{-- Boat → dedicated boat-booking index --}}
    <a href="{{ route('boat-booking.index') }}"
       class="bk-cat cat-boat">
      <span class="bk-cat-emoji">⛵</span>
      Boat
      <span class="bk-cat-count">{{ $countBoat }}</span>
    </a>

    {{-- Stay --}}
    <a href="{{ route('bookings.index') }}?service_type=stay"
       class="bk-cat cat-stay {{ str_contains(strtolower($activeCat),'stay') || str_contains(strtolower($activeCat),'hotel') ? 'active' : '' }}">
      <span class="bk-cat-emoji">🏨</span>
      Stay
      <span class="bk-cat-count">{{ $countStay }}</span>
    </a>

    {{-- Cab → dedicated cab-bookings index --}}
    <a href="{{ route('cab-bookings.index') }}"
       class="bk-cat cat-cab">
      <span class="bk-cat-emoji">🚗</span>
      Cab
      <span class="bk-cat-count">{{ $countCab }}</span>
    </a>

    {{-- Tour Packages --}}
    <a href="{{ route('bookings.index') }}?service_type=tour"
       class="bk-cat cat-tour {{ str_contains(strtolower($activeCat),'tour') ? 'active' : '' }}">
      <span class="bk-cat-emoji">🗺️</span>
      Tour Packages
      <span class="bk-cat-count">{{ $countTour }}</span>
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

    {{-- ══ Bulk Actions Toolbar ══ --}}
    <div class="bk-bulk-bar" id="bkBulkBar">
      <span class="bk-bulk-count" id="bkBulkCount">0 selected</span>
      <button class="bk-bulk-btn bkb-green" onclick="bkBulkAction('confirm')">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Confirm
      </button>
      <button class="bk-bulk-btn bkb-red" onclick="bkBulkAction('cancel')">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Cancel
      </button>
      <button class="bk-bulk-btn bkb-blue" onclick="bkBulkExport()">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Export
      </button>
      <button class="bk-bulk-btn bkb-amber" onclick="bkBulkWhatsApp()">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075a8.167 8.167 0 01-2.385-1.475 8.166 8.166 0 01-1.653-2.059c-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg> WhatsApp
      </button>
      <button class="bk-bulk-btn bkb-gray" onclick="bkBulkClear()">✕ Clear</button>
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
              <th style="width:36px;padding:10px 8px;">
                <input type="checkbox" class="bk-row-check" id="bkCheckAll" title="Select All">
              </th>
              <th>Booking ID</th>
              <th>Guest</th>
              <th>Type</th>
              <th>Travel Date</th>
              <th>Status</th>
              <th>Amount</th>
              @if($staffList->count())
              <th>Added By</th>
              @endif
              <th style="display:none;">Assigned</th>
              <th style="width:1%;white-space:nowrap;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bookings as $booking)
              @php
                /* ── Service type — check both paths ── */
                $firstQItem = $booking->quotation?->items?->first();
                $stName = optional($firstQItem?->serviceTemplate?->serviceType)->name
                       ?? optional($firstQItem?->serviceType)->name
                       ?? '';
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

              <tr
                @if($typeKey === 'tour')
                  onclick="bkToggleExpand({{ $booking->id }},event)"
                  class="bk-tour-row"
                @else
                  onclick="window.location='{{ route('bookings.show', $booking->id) }}'"
                @endif
                style="cursor:pointer;" id="bk-row-{{ $booking->id }}">

                {{-- CHECKBOX --}}
                <td onclick="event.stopPropagation()" style="padding:0 8px;vertical-align:middle;border-bottom:none !important;">
                  <input type="checkbox" class="bk-row-check bk-row-cb"
                         value="{{ $booking->id }}"
                         data-name="{{ $booking->lead->guest_name ?? '' }}"
                         data-phone="{{ preg_replace('/[^0-9]/','',$booking->lead->contact??'') }}"
                         data-amount="{{ $booking->total_amount }}"
                         onchange="bkUpdateBulk()">
                </td>

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

                {{-- TYPE / PACKAGE --}}
                <td data-label="Type">
                  <span class="bkt-type bkt-type-{{ $typeKey }}">{{ $typeEmoji }} {{ $typeLabel }}</span>
                  @if($typeKey === 'tour')
                    @php
                      $pkgName = $booking->quotation?->items?->first()?->serviceTemplate?->name
                              ?? ($booking->lead?->short_plan ? Str::limit(strip_tags($booking->lead->short_plan), 40) : null);
                    @endphp
                    @if($pkgName)
                      <div style="font-size:.7rem;color:#6D28D9;font-weight:600;margin-top:3px;max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $pkgName }}">📦 {{ $pkgName }}</div>
                    @endif
                    @php
                      $tdays = null;
                      if($booking->lead?->booking_start_date && $booking->lead?->booking_end_date) {
                          $tdays = \Carbon\Carbon::parse($booking->lead->booking_start_date)->diffInDays(\Carbon\Carbon::parse($booking->lead->booking_end_date)) + 1;
                      }
                    @endphp
                    @if($tdays)
                      <div style="font-size:.68rem;color:#9CA3AF;margin-top:1px;">{{ $tdays }}D/{{ $tdays-1 }}N</div>
                    @endif
                  @endif
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

                {{-- ADDED BY (admin/manager only) --}}
                @if($staffList->count())
                <td data-label="Added By" onclick="event.stopPropagation()">
                  @php $creator = $booking->createdBy; @endphp
                  @if($creator)
                  <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;flex-shrink:0;">
                      {{ strtoupper(substr($creator->name, 0, 1)) }}
                    </div>
                    <span style="font-size:.78rem;font-weight:600;color:#374151;white-space:nowrap;">{{ $creator->name }}</span>
                  </div>
                  @else
                  <span style="font-size:.75rem;color:#9CA3AF;">—</span>
                  @endif
                </td>
                @endif

                {{-- ASSIGNED --}}
                <td data-label="Assigned" style="display:none;">
                  @if($assigned)
                    <span class="bkt-assigned">{{ $assigned }}</span>
                  @else
                    <span class="bkt-assigned empty">—</span>
                  @endif
                </td>

                {{-- ACTIONS --}}
                @php
                  $waMsg = urlencode("Namaste *{$booking->lead->guest_name}* 🙏\n\nYour Tour Booking is confirmed! ✅\n\nBooking ID: *{$booking->booking_number}*\nPackage: *".($booking->lead->short_plan ? Str::limit(strip_tags($booking->lead->short_plan),60) : 'Tour Package')."*\nTotal: ₹".number_format($booking->total_amount)."\nBalance Due: ₹".number_format($booking->pending_amount)."\n\nFor any help call us.");
                @endphp
                <td onclick="event.stopPropagation()" style="white-space:nowrap;padding:6px 8px!important;border-bottom:none!important;">
                  <div class="bkt-acts">

                    {{-- 👁 View Full Details --}}
                    @if($typeKey === 'tour')
                    <a href="{{ route('tour-booking.view', $booking->id) }}" target="_blank" class="bkt-act bkt-act-view" data-tip="View Details">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                    @elseif($typeKey !== 'stay')
                    <a href="{{ route('bookings.show', $booking->id) }}" class="bkt-act bkt-act-view" data-tip="View Details">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                    @endif

                    {{-- ✏️ Edit --}}
                    @if($typeKey === 'tour')
                      <a href="{{ route('tour-booking.show', $booking->id) }}" class="bkt-act bkt-act-edit" data-tip="Edit Booking">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </a>
                    @else
                      <a href="{{ route('bookings.edit', $booking->id) }}" class="bkt-act bkt-act-edit" data-tip="Edit Booking">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </a>
                    @endif

                    {{-- 🖨 Print / Confirmation --}}
                    @if($typeKey === 'tour')
                      <a href="{{ route('tour-booking.confirmation', $booking->id) }}" target="_blank"
                         class="bkt-act bkt-act-print" data-tip="Print Confirmation"
                         style="background:#EDE9FE;color:#7C3AED;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                      </a>
                    @elseif($booking->is_gst_invoice)
                      <a href="{{ route('booking.gst-invoice', $booking->id) }}" target="_blank" class="bkt-act bkt-act-print" data-tip="GST Invoice">
                        <i data-feather="printer"></i>
                      </a>
                    @else
                      <a href="{{ route('booking.invoice', $booking->id) }}" target="_blank" class="bkt-act bkt-act-print" data-tip="Invoice">
                        <i data-feather="printer"></i>
                      </a>
                    @endif


                    {{-- 🗑 Delete --}}
                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" style="display:contents;">
                      @csrf @method('DELETE')
                      <button type="button" class="bkt-act bkt-act-del deleteBtn" data-tip="Delete"
                              data-name="{{ $booking->booking_number }}">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                      </button>
                    </form>

                  </div>
                </td>

              </tr>

              {{-- ── Inline Expand Panel (tour bookings only) ── --}}
              @if($typeKey === 'tour')
              <tr id="bk-expand-{{ $booking->id }}" class="bk-expand-row" style="display:none;">
                <td colspan="10" style="padding:0;border-bottom:2px solid #C4B5FD;">
                  <div class="bk-expand-panel">

                    {{-- Header strip --}}
                    <div class="bk-exp-header">
                      <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:1.1rem;">🗺️</span>
                        <div>
                          <div style="font-size:.85rem;font-weight:800;color:#fff;">{{ $booking->booking_number }}</div>
                          <div style="font-size:.72rem;color:rgba(255,255,255,.75);">Tour Package — {{ $booking->booking_date->format('d M Y') }}</div>
                        </div>
                      </div>
                      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <a href="{{ route('tour-booking.confirmation', $booking->id) }}" target="_blank" class="bk-exp-btn" style="background:#fff;color:#7C3AED;">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                          Confirmation
                        </a>
                        <a href="{{ route('tour-booking.show', $booking->id) }}" class="bk-exp-btn" style="background:rgba(255,255,255,.15);color:#fff;">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                          Edit
                        </a>
                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete {{ $booking->booking_number }}?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="bk-exp-btn" style="background:#FEE2E2;color:#DC2626;border:none;cursor:pointer;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                            Delete
                          </button>
                        </form>
                        <button onclick="bkToggleExpand({{ $booking->id }},event)" class="bk-exp-btn" style="background:rgba(255,255,255,.12);color:#fff;border:none;cursor:pointer;">✕ Close</button>
                      </div>
                    </div>

                    {{-- Details grid --}}
                    <div class="bk-exp-body">
                      <div class="bk-exp-grid">

                        {{-- Guest --}}
                        <div class="bk-exp-section">
                          <div class="bk-exp-section-title">👤 Guest Details</div>
                          <div class="bk-exp-row"><span>Name</span><strong>{{ $booking->lead->guest_name ?? '—' }}</strong></div>
                          <div class="bk-exp-row"><span>Mobile</span><strong>+91 {{ $booking->lead->contact ?? '—' }}</strong></div>
                          @if($booking->lead->email)<div class="bk-exp-row"><span>Email</span><strong>{{ $booking->lead->email }}</strong></div>@endif
                          @if($booking->lead->pax)<div class="bk-exp-row"><span>Guests</span><strong>{{ $booking->lead->pax }} person(s)</strong></div>@endif
                          @if($booking->lead->booking_start_date)<div class="bk-exp-row"><span>From</span><strong>{{ \Carbon\Carbon::parse($booking->lead->booking_start_date)->format('d M Y') }}</strong></div>@endif
                          @if($booking->lead->booking_end_date)<div class="bk-exp-row"><span>To</span><strong>{{ \Carbon\Carbon::parse($booking->lead->booking_end_date)->format('d M Y') }}</strong></div>@endif
                        </div>

                        {{-- Package --}}
                        <div class="bk-exp-section">
                          <div class="bk-exp-section-title">🗺️ Package</div>
                          <div style="font-size:.82rem;color:#334155;line-height:1.65;background:#F5F3FF;border-radius:8px;padding:10px 12px;border:1px solid #C4B5FD;">
                            {{ $booking->lead->short_plan ?? '—' }}
                          </div>
                          @if($booking->lead->plan_detail)
                          <div style="margin-top:8px;font-size:.77rem;color:#475569;line-height:1.7;background:#F8FAFC;border-radius:8px;padding:8px 12px;white-space:pre-line;max-height:80px;overflow:hidden;">{{ Str::limit($booking->lead->plan_detail, 200) }}</div>
                          @endif
                        </div>

                        {{-- Payment --}}
                        <div class="bk-exp-section">
                          <div class="bk-exp-section-title">💳 Payment</div>
                          @php $expPaid = $booking->paid_amount ?? 0; $expDue = max(0,$booking->total_amount - $expPaid); @endphp
                          <div class="bk-exp-row"><span>Total Amount</span><strong style="font-size:.95rem;">₹{{ number_format($booking->total_amount) }}</strong></div>
                          @if($booking->discount_amount > 0)<div class="bk-exp-row"><span>Discount</span><strong style="color:#059669;">−₹{{ number_format($booking->discount_amount) }}</strong></div>@endif
                          <div class="bk-exp-row"><span>Amount Paid</span><strong style="color:#059669;">₹{{ number_format($expPaid) }}</strong></div>
                          <div class="bk-exp-row"><span>Balance Due</span>
                            <strong style="color:{{ $expDue > 0 ? '#DC2626' : '#059669' }};">
                              {{ $expDue > 0 ? '₹'.number_format($expDue) : '✅ Paid' }}
                            </strong>
                          </div>
                          @if($booking->notes && str_contains($booking->notes,'B2B:'))
                          <div style="margin-top:6px;font-size:.72rem;color:#92400E;background:#FEF3C7;border-radius:6px;padding:6px 10px;">
                            {{ Str::after($booking->notes, 'B2B:') ? '💰 '.trim(explode("\n",$booking->notes)[1] ?? Str::after($booking->notes,'B2B:')) : '' }}
                          </div>
                          @endif
                        </div>

                      </div>
                    </div>

                  </div>
                </td>
              </tr>
              @endif
              {{-- ── /Expand Panel ── --}}

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

{{-- ══ BOAT BOOKINGS SECTION (shown in All Bookings view) ══ --}}
@if(!request('service_type') && !request('status') && $recentBoatBookings->count())
<div class="bkt-card" style="margin-top:20px;">
  <div class="bkt-head">
    <div class="bkt-head-left">
      <span style="font-size:1rem;">⛵</span>
      <span>Boat Bookings</span>
      <span style="font-size:.75rem;font-weight:600;color:#6B7280;margin-left:4px;">({{ $recentBoatBookings->count() }})</span>
    </div>
    <a href="{{ route('boat-booking.index') }}" class="bkt-new" style="background:#EFF6FF;color:#0369A1;border-color:#BAE6FD;">View All →</a>
  </div>
  <div style="overflow-x:auto;">
    <table class="bkt-table">
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Guest</th>
          <th>Boat</th>
          <th>Date</th>
          <th>Amount</th>
          <th>Status</th>
          <th style="width:1%;white-space:nowrap;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($recentBoatBookings as $bk)
        @php
          $bPaid = (float)($bk->payments_sum_amount ?? 0);
          $bDue  = max(0, (float)$bk->final_amount - $bPaid);
          $bPaySt = $bk->payment_status ?? ($bDue <= 0 ? 'paid' : ($bPaid > 0 ? 'partial' : 'unpaid'));
          $bkSt   = $bk->booking_status ?? 'confirmed';
          $bName  = optional(optional($bk->boat)->boatType)->name ?? 'Boat';
        @endphp
        <tr style="cursor:pointer;" onclick="window.location='{{ route('boat-booking.show', $bk->booking_id) }}'">
          <td data-label="Booking ID">
            <div style="font-size:.82rem;font-weight:700;color:#0369A1;white-space:nowrap;font-family:monospace;">{{ $bk->booking_id }}</div>
            <div style="font-size:.72rem;color:#9CA3AF;margin-top:2px;">{{ \Carbon\Carbon::parse($bk->booking_date)->format('d M Y') }}</div>
          </td>
          <td data-label="Guest">
            <div style="font-size:.85rem;font-weight:700;color:#111827;">{{ $bk->name }}</div>
            <div style="font-size:.73rem;color:#6B7280;">{{ $bk->phone }}</div>
          </td>
          <td data-label="Boat">
            <span class="bkt-type" style="background:#E0F2FE;color:#0369A1;">⛵ {{ $bName }}</span>
          </td>
          <td data-label="Date">
            <span style="font-size:.82rem;color:#374151;font-weight:600;">{{ \Carbon\Carbon::parse($bk->booking_date)->format('d M Y') }}</span>
            @if($bk->pickup_time)<div style="font-size:.7rem;color:#6B7280;">🕐 {{ \Carbon\Carbon::parse($bk->pickup_time)->format('h:i A') }}</div>@endif
          </td>
          <td data-label="Amount">
            <div class="bkt-amount">₹{{ number_format($bk->final_amount) }}</div>
            @if($bDue > 0)<div class="bkt-due-blink">Due ₹{{ number_format($bDue) }}</div>@endif
          </td>
          <td data-label="Status">
            <span class="bkt-status bkt-s-{{ $bkSt }}">{{ ucfirst($bkSt) }}</span>
            <span class="bkt-status bkt-s-{{ $bPaySt }}" style="margin-top:3px;display:block;">{{ ucfirst($bPaySt) }}</span>
          </td>
          <td onclick="event.stopPropagation()" style="white-space:nowrap;padding:6px 8px!important;">
            <div class="bkt-acts">
              <a href="{{ route('boat-booking.show', $bk->booking_id) }}" class="bkt-act bkt-act-view" title="View">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('boat-booking.edit', $bk->booking_id) }}" class="bkt-act bkt-act-edit" title="Edit">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="{{ route('boat-booking.voucher', $bk->booking_id) }}" class="bkt-act bkt-act-print" title="Voucher">
                <i data-feather="printer"></i>
              </a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

{{-- ══ CAB BOOKINGS SECTION (shown in All Bookings view) ══ --}}
@if(!request('service_type') && !request('status') && $recentCabBookings->count())
<div class="bkt-card" style="margin-top:20px;">
  <div class="bkt-head">
    <div class="bkt-head-left">
      <span style="font-size:1rem;">🚗</span>
      <span>Cab Bookings</span>
      <span style="font-size:.75rem;font-weight:600;color:#6B7280;margin-left:4px;">({{ $recentCabBookings->count() }})</span>
    </div>
    <a href="{{ route('cab-bookings.index') }}" class="bkt-new" style="background:#FEF3C7;color:#B45309;border-color:#FDE68A;">View All →</a>
  </div>
  <div style="overflow-x:auto;">
    <table class="bkt-table">
      <thead>
        <tr>
          <th>Booking #</th>
          <th>Customer</th>
          <th>Route</th>
          <th>Pickup Date</th>
          <th>Amount</th>
          <th>Status</th>
          <th style="width:1%;white-space:nowrap;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($recentCabBookings as $cb)
        @php
          $cPaySt = $cb->payment_status ?? 'unpaid';
          $cBkSt  = $cb->booking_status ?? 'confirmed';
        @endphp
        <tr style="cursor:pointer;" onclick="window.location='{{ route('cab-bookings.show', $cb->id) }}'">
          <td data-label="Booking #">
            <div style="font-size:.82rem;font-weight:700;color:#B45309;white-space:nowrap;font-family:monospace;">{{ $cb->booking_number }}</div>
            <div style="font-size:.72rem;color:#9CA3AF;margin-top:2px;">{{ \Carbon\Carbon::parse($cb->created_at)->format('d M Y') }}</div>
          </td>
          <td data-label="Customer">
            <div style="font-size:.85rem;font-weight:700;color:#111827;">{{ $cb->customer_name }}</div>
            <div style="font-size:.73rem;color:#6B7280;">{{ $cb->customer_phone }}</div>
          </td>
          <td data-label="Route">
            <div style="font-size:.78rem;color:#374151;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit($cb->pickup_address, 20) }} → {{ Str::limit($cb->drop_address, 20) }}</div>
            <div style="font-size:.7rem;color:#6B7280;">{{ $cb->trip_type }}</div>
          </td>
          <td data-label="Pickup">
            <span style="font-size:.82rem;color:#374151;font-weight:600;">{{ \Carbon\Carbon::parse($cb->pickup_date)->format('d M Y') }}</span>
            @if($cb->pickup_time)<div style="font-size:.7rem;color:#6B7280;">🕐 {{ \Carbon\Carbon::parse($cb->pickup_time)->format('h:i A') }}</div>@endif
          </td>
          <td data-label="Amount">
            <div class="bkt-amount">₹{{ number_format($cb->total_amount) }}</div>
            @if($cb->advance_paid > 0)<div style="font-size:.7rem;color:#059669;">Paid ₹{{ number_format($cb->advance_paid) }}</div>@endif
          </td>
          <td data-label="Status">
            <span class="bkt-status bkt-s-{{ $cBkSt }}">{{ ucfirst($cBkSt) }}</span>
            <span class="bkt-status bkt-s-{{ $cPaySt }}" style="margin-top:3px;display:block;">{{ ucfirst($cPaySt) }}</span>
          </td>
          <td onclick="event.stopPropagation()" style="white-space:nowrap;padding:6px 8px!important;">
            <div class="bkt-acts">
              <a href="{{ route('cab-bookings.show', $cb->id) }}" class="bkt-act bkt-act-view" title="View">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('cab-bookings.edit', $cb->id) }}" class="bkt-act bkt-act-edit" title="Edit">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

{{-- ══ QUICK VIEW MODAL ══ --}}
<div class="qv-overlay" id="qvOverlay" onclick="if(event.target===this)bkCloseQV()">
  <div class="qv-modal" id="qvModal">
    <div class="qv-header">
      <div>
        <div class="qv-title">🗺️ Tour Booking — <span id="qvBkNum">—</span></div>
        <div style="color:rgba(255,255,255,.7);font-size:.74rem;margin-top:2px;" id="qvBkDate"></div>
      </div>
      <button class="qv-close" onclick="bkCloseQV()">✕</button>
    </div>
    <div class="qv-body">
      {{-- Status chips --}}
      <div class="qv-status-bar" id="qvStatusBar"></div>

      {{-- Info grid --}}
      <div class="qv-grid">
        <div class="qv-field">
          <div class="qv-field-label">Guest Name</div>
          <div class="qv-field-value" id="qvGuest"></div>
        </div>
        <div class="qv-field">
          <div class="qv-field-label">Contact Number</div>
          <div class="qv-field-value" id="qvPhone"></div>
        </div>
        <div class="qv-field">
          <div class="qv-field-label">Email Address</div>
          <div class="qv-field-value" id="qvEmail" style="font-size:.82rem;"></div>
        </div>
        <div class="qv-field">
          <div class="qv-field-label">Number of Guests</div>
          <div class="qv-field-value" id="qvPax"></div>
        </div>
        <div class="qv-field qv-field-full">
          <div class="qv-field-label">Tour Package</div>
          <div class="qv-field-value" id="qvPkg" style="font-size:.85rem;line-height:1.5;"></div>
        </div>
        <div class="qv-field">
          <div class="qv-field-label">Total Amount</div>
          <div class="qv-field-value" id="qvTotal" style="font-size:1.1rem;color:#059669;"></div>
        </div>
        <div class="qv-field">
          <div class="qv-field-label">Balance Due</div>
          <div class="qv-field-value" id="qvDue"></div>
        </div>
        <div class="qv-field">
          <div class="qv-field-label">Booking Date</div>
          <div class="qv-field-value" id="qvBkDateVal"></div>
        </div>
        <div class="qv-field">
          <div class="qv-field-label">Booking Status</div>
          <div class="qv-field-value" id="qvStatus"></div>
        </div>
      </div>

      {{-- Booking Timeline --}}
      <div style="margin-top:18px;">
        <div style="font-size:.72rem;font-weight:800;color:#4F46E5;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">📋 Booking Timeline</div>
        <div class="qv-timeline" id="qvTimeline"></div>
      </div>
    </div>

    {{-- Modal actions --}}
    <div class="qv-actions" id="qvActions"></div>
  </div>
</div>

<script>
/* ══ Tour booking expand/collapse ══ */
function bkToggleExpand(id, event) {
  if (event && event.target.closest('a,button,form,input')) return;
  var panel = document.getElementById('bk-expand-' + id);
  var row   = document.getElementById('bk-row-' + id);
  if (!panel) return;
  var open = panel.style.display !== 'none';
  panel.style.display = open ? 'none' : 'table-row';
  row.classList.toggle('expanded', !open);
  if (!open) { feather.replace(); panel.scrollIntoView({behavior:'smooth', block:'nearest'}); }
}

/* ══ Quick View Modal ══ */
function bkQuickView(id, bkNum, guest, phone, email, pkg, bkDate, pax, total, due, status, payStatus) {
  document.getElementById('qvBkNum').textContent   = bkNum;
  document.getElementById('qvBkDate').textContent  = 'Created: ' + bkDate;
  document.getElementById('qvBkDateVal').textContent = bkDate;
  document.getElementById('qvGuest').textContent   = guest || '—';
  document.getElementById('qvPhone').textContent   = phone ? '+91 ' + phone : '—';
  document.getElementById('qvEmail').textContent   = email || '—';
  document.getElementById('qvPax').textContent     = pax + ' person(s)';
  document.getElementById('qvPkg').textContent     = pkg || 'Tour Package';
  document.getElementById('qvTotal').textContent   = '₹' + total;
  document.getElementById('qvDue').textContent     = due > 0 ? '₹' + due : '✅ Fully Paid';
  document.getElementById('qvDue').style.color     = due > 0 ? '#DC2626' : '#059669';

  // Status chips
  var statusColors = { confirmed:'qv-chip-confirmed', cancelled:'qv-chip-cancelled' };
  var payColors    = { paid:'qv-chip-paid', partial:'qv-chip-partial', unpaid:'qv-chip-due' };
  document.getElementById('qvStatus').textContent = status.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase());
  document.getElementById('qvStatusBar').innerHTML =
    '<span class="qv-chip ' + (statusColors[status] || 'qv-chip-confirmed') + '">● ' + status.replace(/_/g,' ') + '</span>' +
    '<span class="qv-chip ' + (payColors[payStatus] || 'qv-chip-due') + '">' + payStatus + '</span>';

  // Timeline
  var now = new Date().toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'});
  var tlItems = [
    { event:'Booking Created', meta:'Created on ' + bkDate, done:true },
    { event:'Confirmation Sent', meta:'Auto-confirmed', done: status !== 'not_started' },
    { event:'Payment Received', meta: due <= 0 ? 'Fully paid' : 'Partial payment', done: payStatus !== 'unpaid' },
    { event:'Tour Started', meta:'Pending', done: status === 'in_progress' || status === 'completed' },
    { event:'Tour Completed', meta:'Pending', done: status === 'completed' },
  ];
  document.getElementById('qvTimeline').innerHTML = tlItems.map(function(t){
    return '<div class="qv-tl-item">' +
      '<div class="qv-tl-dot ' + (t.done ? 'done' : 'pending') + '">' + (t.done ? '✓' : '○') + '</div>' +
      '<div class="qv-tl-text"><div class="qv-tl-event">' + t.event + '</div><div class="qv-tl-meta">' + t.meta + '</div></div></div>';
  }).join('');

  // Actions
  var waMsg = encodeURIComponent('Namaste *' + guest + '* 🙏\n\nYour tour booking *' + bkNum + '* is confirmed! ✅\nTotal: ₹' + total + '\nBalance: ₹' + due);
  document.getElementById('qvActions').innerHTML =
    '<a href="{{ url("admin/bookings/tour") }}/' + id + '" class="qv-act-btn qvb-edit">✏️ Edit</a>' +
    '<a href="{{ url("admin/bookings/tour") }}/' + id + '/confirmation" target="_blank" class="qv-act-btn qvb-conf">🖨 Confirmation</a>' +
    (phone ? '<a href="https://wa.me/91' + phone + '?text=' + waMsg + '" target="_blank" class="qv-act-btn qvb-wa">📱 WhatsApp</a>' : '') +
    '<a href="{{ url("admin/bookings/tour") }}/' + id + '/pdf" target="_blank" class="qv-act-btn qvb-pdf">📄 PDF</a>' +
    '<button class="qv-act-btn qvb-close" onclick="bkCloseQV()">✕ Close</button>';

  document.getElementById('qvOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function bkCloseQV() {
  document.getElementById('qvOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

// Close on Escape
document.addEventListener('keydown', function(e){ if(e.key==='Escape') bkCloseQV(); });

/* ══ Bulk Actions ══ */
function bkUpdateBulk() {
  var checked = document.querySelectorAll('.bk-row-cb:checked');
  var bar     = document.getElementById('bkBulkBar');
  var count   = document.getElementById('bkBulkCount');
  count.textContent = checked.length + ' selected';
  bar.classList.toggle('show', checked.length > 0);
  // Highlight rows
  document.querySelectorAll('.bk-row-cb').forEach(function(cb){
    var row = cb.closest('tr');
    if (row) row.classList.toggle('bk-row-selected', cb.checked);
  });
}

function bkBulkClear() {
  document.querySelectorAll('.bk-row-cb').forEach(function(cb){ cb.checked = false; });
  document.querySelectorAll('tr.bk-row-selected').forEach(function(r){ r.classList.remove('bk-row-selected'); });
  document.getElementById('bkBulkBar').classList.remove('show');
}

function bkBulkAction(action) {
  var ids = Array.from(document.querySelectorAll('.bk-row-cb:checked')).map(cb => cb.value);
  if (!ids.length) return;
  if (!confirm(action === 'confirm' ? 'Confirm ' + ids.length + ' bookings?' : 'Cancel ' + ids.length + ' bookings?')) return;
  // Submit to existing update-status route for each (simple approach)
  var form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ route("bookings.index") }}';
  form.innerHTML = '@csrf<input name="_method" value="POST"><input name="bulk_action" value="' + action + '">' +
    ids.map(id => '<input name="ids[]" value="' + id + '">').join('');
  document.body.appendChild(form);
  form.submit();
}

function bkBulkExport() {
  var ids = Array.from(document.querySelectorAll('.bk-row-cb:checked')).map(cb => cb.value);
  if (!ids.length) { alert('Select bookings to export.'); return; }
  // CSV export from selected data
  var rows = [['Booking ID','Guest','Phone','Amount','Status']];
  document.querySelectorAll('.bk-row-cb:checked').forEach(function(cb){
    var row = cb.closest('tr');
    if (!row) return;
    var cells = row.querySelectorAll('td');
    rows.push([
      cb.value,
      cb.dataset.name || '',
      cb.dataset.phone || '',
      cb.dataset.amount || '',
      'confirmed'
    ]);
  });
  var csv = rows.map(r => r.map(v => '"'+v+'"').join(',')).join('\n');
  var a = document.createElement('a');
  a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
  a.download = 'tour-bookings-' + new Date().toISOString().slice(0,10) + '.csv';
  a.click();
}

function bkBulkWhatsApp() {
  var cbs = document.querySelectorAll('.bk-row-cb:checked');
  if (!cbs.length) { alert('Select bookings first.'); return; }
  var first = cbs[0];
  var phone = first.dataset.phone;
  if (!phone) { alert('No phone number for selected booking.'); return; }
  var msg = encodeURIComponent('Namaste! Your tour booking confirmation is ready. Booking ID: ' + first.value);
  window.open('https://wa.me/91' + phone + '?text=' + msg, '_blank');
}

/* ══ Select All checkbox ══ */
document.addEventListener('DOMContentLoaded', function(){
  feather.replace();

  var checkAll = document.getElementById('bkCheckAll');
  if (checkAll) {
    checkAll.addEventListener('change', function(){
      document.querySelectorAll('.bk-row-cb').forEach(function(cb){ cb.checked = checkAll.checked; });
      bkUpdateBulk();
    });
  }

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
