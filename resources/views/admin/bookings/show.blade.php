@extends('admin.layouts.app')

@section('content')
<style>
/* ═══════════════════════════════════════════════
   PREMIUM BOAT BOOKING DETAIL — Visit Kashi
   Design: Stripe / Linear / Airbnb Admin inspired
═══════════════════════════════════════════════ */
:root {
    --pk-purple: #6366f1;
    --pk-purple-dk: #4f46e5;
    --pk-violet: #8b5cf6;
    --pk-emerald: #10b981;
    --pk-amber: #f59e0b;
    --pk-rose: #ef4444;
    --pk-sky: #0ea5e9;
    --pk-wa: #25D366;
    --pk-gray: #6b7280;
    --pk-border: #e5e7eb;
    --pk-bg: #f8fafc;
    --pk-card: #ffffff;
    --pk-text: #0f172a;
    --pk-muted: #64748b;
    --pk-r: 16px;
    --pk-t: 0.2s ease;
    --pk-shadow: 0 1px 4px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --pk-shadow-md: 0 4px 20px rgba(0,0,0,.08);
}

/* ── Dark Mode Vars ── */
body.pk-dark {
    --pk-bg: #0f172a;
    --pk-card: #1e293b;
    --pk-border: #334155;
    --pk-text: #f1f5f9;
    --pk-muted: #94a3b8;
    --pk-shadow: 0 1px 4px rgba(0,0,0,.3);
    --pk-shadow-md: 0 4px 20px rgba(0,0,0,.3);
}

/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Page ── */
.pk-page { background: var(--pk-bg); padding-bottom: 80px; transition: background .3s ease; }

/* ── Layout Grid ── */
.pk-wrap { max-width: 1360px; margin: 0 auto; padding: 0 16px; }
.pk-grid { display: grid; grid-template-columns: 1fr 348px; gap: 20px; align-items: start; }
@media(max-width:1080px) { .pk-grid { grid-template-columns: 1fr; } }

/* ── Sticky Topbar ── */
.pk-topbar {
    position: sticky;
    top: 0;
    z-index: 200;
    background: rgba(248,250,252,.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--pk-border);
    padding: 10px 0;
    margin-bottom: 20px;
    box-shadow: 0 2px 16px rgba(99,102,241,.07);
    transition: background .3s ease;
}
body.pk-dark .pk-topbar {
    background: rgba(15,23,42,.92);
    border-bottom-color: #334155;
}
.pk-topbar-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.pk-topbar-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.pk-topbar-right { display: flex; align-items: center; gap: 7px; }

/* ── Buttons ── */
.pk-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 14px; border-radius: 9px;
    font-size: .82rem; font-weight: 600;
    border: none; cursor: pointer;
    transition: all var(--pk-t);
    text-decoration: none; line-height: 1; white-space: nowrap;
}
.pk-btn:hover { transform: translateY(-1px); }
.pk-btn svg, .pk-btn [data-feather] { width: 14px; height: 14px; flex-shrink: 0; }
.pk-btn-ghost {
    background: transparent; color: var(--pk-muted);
    border: 1.5px solid var(--pk-border);
}
.pk-btn-ghost:hover { background: var(--pk-bg); color: var(--pk-text); box-shadow: var(--pk-shadow); }
.pk-btn-icon { width: 34px; height: 34px; padding: 0; justify-content: center; }
.pk-btn-purple { background: var(--pk-purple); color: #fff; }
.pk-btn-purple:hover { background: var(--pk-purple-dk); box-shadow: 0 4px 14px rgba(99,102,241,.4); }
.pk-btn-emerald { background: var(--pk-emerald); color: #fff; }
.pk-btn-emerald:hover { box-shadow: 0 4px 14px rgba(16,185,129,.35); }
.pk-btn-rose { background: var(--pk-rose); color: #fff; }
.pk-btn-rose:hover { box-shadow: 0 4px 14px rgba(239,68,68,.35); }
.pk-btn-wa { background: var(--pk-wa); color: #fff; }
.pk-btn-wa:hover { box-shadow: 0 4px 14px rgba(37,211,102,.4); }
.pk-btn-sky { background: var(--pk-sky); color: #fff; }

/* ── Booking ID Tag ── */
.pk-id-tag {
    font-size: .8rem; font-weight: 700; color: var(--pk-purple);
    background: #eef2ff; padding: 4px 11px; border-radius: 7px;
    font-family: 'Courier New', monospace; cursor: pointer;
    transition: all var(--pk-t); display: inline-flex; align-items: center; gap: 5px;
    border: 1px solid #c7d2fe;
}
.pk-id-tag:hover { background: #e0e7ff; transform: scale(1.02); }
body.pk-dark .pk-id-tag { background: rgba(99,102,241,.2); color: #a5b4fc; border-color: rgba(99,102,241,.3); }

/* ── Status Dot (animated) ── */
@keyframes pkPulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.3);opacity:.7} }
.pk-dot {
    width: 8px; height: 8px; border-radius: 50%; display: inline-block;
    flex-shrink: 0;
}
.pk-dot.confirmed, .pk-dot.completed { background: var(--pk-emerald); animation: pkPulse 2.5s infinite; }
.pk-dot.in_progress { background: var(--pk-sky); animation: pkPulse 2s infinite; }
.pk-dot.cancelled, .pk-dot.pending { background: var(--pk-rose); animation: none; }

/* ── Hero Card ── */
.pk-hero {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 55%, #a855f7 100%);
    border-radius: 22px;
    padding: 28px 32px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 12px 40px rgba(99,102,241,.35);
}
.pk-hero::before {
    content: ''; position: absolute; top: -50px; right: -50px;
    width: 220px; height: 220px; border-radius: 50%;
    background: rgba(255,255,255,.08); pointer-events: none;
}
.pk-hero::after {
    content: ''; position: absolute; bottom: -80px; right: 80px;
    width: 300px; height: 300px; border-radius: 50%;
    background: rgba(255,255,255,.05); pointer-events: none;
}
.pk-hero::before { animation: heroOrb1 8s ease-in-out infinite; }
@keyframes heroOrb1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-20px,10px)} }
.pk-hero-inner { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap; position: relative; z-index: 1; }
.pk-hero-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; opacity: .7; margin-bottom: 5px; }
.pk-hero-name { font-size: 1.85rem; font-weight: 800; line-height: 1.1; margin-bottom: 14px; }
.pk-hero-meta { display: flex; flex-wrap: wrap; gap: 16px; }
.pk-hero-meta-item { display: flex; align-items: center; gap: 6px; font-size: .85rem; opacity: .88; }
.pk-hero-pills { display: flex; flex-direction: column; align-items: flex-end; gap: 7px; }
.pk-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 13px; border-radius: 50px;
    font-size: .78rem; font-weight: 700;
    backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    white-space: nowrap;
}
.pk-pill-glass { background: rgba(255,255,255,.18); color: #fff; border: 1px solid rgba(255,255,255,.3); }
.pk-pill-emerald { background: rgba(16,185,129,.22); color: #6EE7B7; border: 1px solid rgba(16,185,129,.3); }
.pk-pill-sky { background: rgba(14,165,233,.22); color: #BAE6FD; border: 1px solid rgba(14,165,233,.3); }
.pk-pill-amber { background: rgba(245,158,11,.22); color: #FDE68A; border: 1px solid rgba(245,158,11,.3); }
.pk-pill-rose { background: rgba(239,68,68,.22); color: #FECACA; border: 1px solid rgba(239,68,68,.3); }
@media(max-width:576px) {
    .pk-hero { padding: 20px; border-radius: 16px; }
    .pk-hero-name { font-size: 1.45rem; }
    .pk-hero-pills { flex-direction: row; flex-wrap: wrap; align-items: flex-start; }
}

/* ── Action Bar ── */
.pk-action-bar {
    background: var(--pk-card);
    border: 1px solid var(--pk-border);
    border-radius: var(--pk-r);
    padding: 11px 16px;
    margin-bottom: 20px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; flex-wrap: wrap;
    box-shadow: var(--pk-shadow);
}
.pk-action-group { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
.pk-action-sep { width: 1px; height: 24px; background: var(--pk-border); margin: 0 4px; }
.pk-stt-btn {
    padding: 5px 12px; border-radius: 7px; font-size: .78rem; font-weight: 600;
    border: 1.5px solid; cursor: pointer; background: transparent;
    transition: all var(--pk-t);
}
.pk-stt-btn:hover { transform: translateY(-1px); }
.pk-stt-btn.pending { color: #B45309; border-color: #FDE68A; }
.pk-stt-btn.pending:hover, .pk-stt-btn.pending.active { background: #FEF3C7; }
.pk-stt-btn.in_progress { color: #0369A1; border-color: #BAE6FD; }
.pk-stt-btn.in_progress:hover, .pk-stt-btn.in_progress.active { background: #E0F2FE; }
.pk-stt-btn.completed { color: #065F46; border-color: #6EE7B7; }
.pk-stt-btn.completed:hover, .pk-stt-btn.completed.active { background: #ECFDF5; }
.pk-stt-btn.cancelled { color: #991B1B; border-color: #FECACA; }
.pk-stt-btn.cancelled:hover, .pk-stt-btn.cancelled.active { background: #FEF2F2; }

/* ── Cards ── */
.pk-card {
    background: var(--pk-card);
    border: 1px solid var(--pk-border);
    border-radius: var(--pk-r);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--pk-shadow);
    transition: box-shadow var(--pk-t), transform var(--pk-t);
}
.pk-card:hover { box-shadow: var(--pk-shadow-md); }
.pk-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 18px;
    border-bottom: 1px solid var(--pk-border);
    background: var(--pk-bg);
}
.pk-card-title {
    font-size: .88rem; font-weight: 700; color: var(--pk-text);
    display: flex; align-items: center; gap: 7px; margin: 0;
}
.pk-card-title [data-feather], .pk-card-title svg { width: 15px; height: 15px; color: var(--pk-purple); }
.pk-card-body { padding: 18px; }
.pk-card-body.p0 { padding: 0; }

/* ── Info Grid (Guest Details) ── */
.pk-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px 14px;
}
@media(max-width:1024px) { .pk-info-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:480px) { .pk-info-grid { grid-template-columns: 1fr; } }
.pk-info-label {
    font-size: .7rem; font-weight: 700; color: var(--pk-muted);
    text-transform: uppercase; letter-spacing: .7px; margin-bottom: 4px;
}
.pk-info-val {
    font-size: .88rem; font-weight: 600; color: var(--pk-text);
    display: flex; align-items: center; gap: 5px;
}
.pk-info-val.empty { color: var(--pk-muted); font-weight: 400; font-style: italic; }

/* ── Boat Detail Blocks ── */
.pk-boat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
@media(max-width:768px) { .pk-boat-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:480px) { .pk-boat-grid { grid-template-columns: 1fr; } }
.pk-block {
    background: var(--pk-bg);
    border: 1px solid var(--pk-border);
    border-radius: 11px;
    padding: 13px 14px;
    transition: all var(--pk-t);
}
.pk-block:hover { border-color: #c7d2fe; background: #faf5ff; }
body.pk-dark .pk-block:hover { background: rgba(99,102,241,.06); border-color: rgba(99,102,241,.3); }
.pk-block-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: #EEF2FF; color: var(--pk-purple);
    display: flex; align-items: center; justify-content: center; margin-bottom: 8px;
}
body.pk-dark .pk-block-icon { background: rgba(99,102,241,.15); }
.pk-block-icon [data-feather] { width: 14px; height: 14px; }
.pk-block-label { font-size: .68rem; color: var(--pk-muted); font-weight: 700; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 3px; }
.pk-block-val { font-size: .9rem; font-weight: 700; color: var(--pk-text); }
.pk-block-val.empty { color: var(--pk-muted); font-weight: 400; font-style: italic; font-size: .84rem; }

/* ── Badges ── */
.pk-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 6px;
    font-size: .73rem; font-weight: 700;
}
.pk-badge [data-feather] { width: 11px; height: 11px; }
.pk-b-emerald { background: #ECFDF5; color: #065F46; }
.pk-b-sky { background: #F0F9FF; color: #0369A1; }
.pk-b-amber { background: #FFFBEB; color: #92400E; }
.pk-b-indigo { background: #EEF2FF; color: #4338CA; }
.pk-b-rose { background: #FEF2F2; color: #991B1B; }
.pk-b-gray { background: #F3F4F6; color: #4B5563; }
.pk-b-green-lg { background: linear-gradient(135deg,#ECFDF5,#D1FAE5); color: #065F46; padding: 5px 13px; font-size: .8rem; }
body.pk-dark .pk-b-emerald { background: rgba(16,185,129,.15); }
body.pk-dark .pk-b-sky { background: rgba(14,165,233,.15); }
body.pk-dark .pk-b-indigo { background: rgba(99,102,241,.15); }
body.pk-dark .pk-b-amber { background: rgba(245,158,11,.15); }
body.pk-dark .pk-b-gray { background: rgba(107,114,128,.15); }

/* ── Payment Table ── */
.pk-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
.pk-table th {
    background: var(--pk-bg); color: var(--pk-muted);
    font-weight: 700; font-size: .71rem;
    text-transform: uppercase; letter-spacing: .5px;
    padding: 10px 16px; border-bottom: 1px solid var(--pk-border);
    white-space: nowrap;
}
.pk-table td {
    padding: 12px 16px; border-bottom: 1px solid var(--pk-border);
    color: var(--pk-text); vertical-align: middle;
}
.pk-table tbody tr:last-child td { border-bottom: none; }
.pk-table tbody tr:hover { background: rgba(99,102,241,.02); }

/* ── Sidebar ── */
.pk-sidebar { display: flex; flex-direction: column; gap: 0; }
.pk-sidebar-sticky { position: sticky; top: 72px; display: flex; flex-direction: column; gap: 16px; }
@media(max-width:1080px) { .pk-sidebar-sticky { position: static; } }

/* ── Payment Summary Card ── */
.pk-pay-card { border-radius: var(--pk-r); overflow: hidden; box-shadow: var(--pk-shadow-md); }
.pk-pay-grad {
    background: linear-gradient(150deg, #4f46e5 0%, #7c3aed 100%);
    padding: 16px 18px;
    display: flex; align-items: center; justify-content: space-between;
}
.pk-pay-grad-title { color: rgba(255,255,255,.9); font-size: .88rem; font-weight: 700; display: flex; align-items: center; gap: 6px; }
.pk-pay-add {
    background: rgba(255,255,255,.2); color: #fff;
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 7px; padding: 5px 11px;
    font-size: .78rem; font-weight: 700; cursor: pointer;
    transition: all var(--pk-t); text-decoration: none;
    display: inline-flex; align-items: center; gap: 4px;
}
.pk-pay-add:hover { background: rgba(255,255,255,.32); color: #fff; transform: translateY(-1px); }
.pk-pay-body { background: var(--pk-card); }
.pk-pay-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 18px; border-bottom: 1px solid var(--pk-border);
    font-size: .86rem;
}
.pk-pay-row:last-of-type { border-bottom: none; }
.pk-pay-lbl { color: var(--pk-muted); }
.pk-pay-val { font-weight: 800; color: var(--pk-text); }
.pk-pay-val.em { color: var(--pk-emerald); }
.pk-pay-val.am { color: var(--pk-amber); }
.pk-pay-footer {
    background: var(--pk-bg); padding: 12px 18px;
    border-top: 1px solid var(--pk-border);
}
.pk-pay-progress-wrap { margin-top: 10px; }
.pk-pay-progress-label { display: flex; justify-content: space-between; font-size: .72rem; color: var(--pk-muted); margin-bottom: 5px; font-weight: 600; }
.pk-pay-bar { height: 7px; background: var(--pk-border); border-radius: 4px; overflow: hidden; }
.pk-pay-bar-fill { height: 100%; background: linear-gradient(90deg, #6366f1, #10b981); border-radius: 4px; transition: width 1s ease; }

/* ── Staff Box ── */
.pk-staff {
    background: linear-gradient(145deg, #fffbeb 0%, #fef3c7 100%);
    border: 1.5px solid #fde68a;
    border-radius: var(--pk-r);
    overflow: hidden;
}
body.pk-dark .pk-staff { background: linear-gradient(145deg,#1c1505,#271d04); border-color: #854d0e; }
.pk-staff-head {
    padding: 11px 16px;
    background: rgba(245,158,11,.12);
    border-bottom: 1px solid #fde68a;
    display: flex; align-items: center; justify-content: space-between;
}
.pk-staff-title { font-size: .84rem; font-weight: 700; color: #92400E; display: flex; align-items: center; gap: 5px; }
body.pk-dark .pk-staff-title { color: #fde68a; }
.pk-staff-body { padding: 14px 16px; }
.pk-mrow { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 10px; }
.pk-mrow:last-of-type { margin-bottom: 0; }
.pk-m-lbl { font-size: .78rem; color: #92400E; font-weight: 600; }
body.pk-dark .pk-m-lbl { color: #fde68a; }
.pk-m-val { font-size: .92rem; font-weight: 800; color: #064e3b; }
body.pk-dark .pk-m-val { color: #34d399; }
.pk-m-val.neg { color: #991b1b; }
body.pk-dark .pk-m-val.neg { color: #fca5a5; }
.pk-profit-wrap { margin-top: 12px; }
.pk-profit-lbl { display: flex; justify-content: space-between; font-size: .72rem; color: #92400E; font-weight: 600; margin-bottom: 4px; }
body.pk-dark .pk-profit-lbl { color: #fde68a; }
.pk-profit-rail { height: 6px; background: rgba(245,158,11,.2); border-radius: 3px; overflow: hidden; }
.pk-profit-fill { height: 100%; background: linear-gradient(90deg, #10b981, #059669); border-radius: 3px; transition: width .9s ease; }

/* ── WhatsApp ── */
.pk-wa-box {
    width: 100%; background: var(--pk-bg);
    border: 1.5px solid var(--pk-border);
    border-radius: 11px; padding: 13px;
    font-size: .83rem; line-height: 1.65; color: var(--pk-text);
    resize: vertical; min-height: 150px;
    font-family: inherit; transition: border-color var(--pk-t);
}
.pk-wa-box:focus { outline: none; border-color: var(--pk-purple); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

/* ── Separator ── */
.pk-sep { height: 1px; background: var(--pk-border); margin: 12px 0; }

/* ── Toast ── */
.pk-toast {
    position: fixed; bottom: 28px; left: 50%;
    transform: translateX(-50%) translateY(70px);
    background: #0f172a; color: #fff;
    padding: 10px 20px; border-radius: 11px;
    font-size: .84rem; font-weight: 600; z-index: 9999;
    box-shadow: 0 8px 24px rgba(0,0,0,.25);
    transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .3s ease;
    opacity: 0; pointer-events: none;
    display: flex; align-items: center; gap: 8px;
}
.pk-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
.pk-toast [data-feather] { width: 15px; height: 15px; color: #10b981; }

/* ── Mobile Sticky Actions ── */
.pk-mob-bar {
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 150;
    background: var(--pk-card);
    border-top: 1px solid var(--pk-border);
    padding: 10px 16px;
    display: none; gap: 8px;
    box-shadow: 0 -4px 20px rgba(0,0,0,.08);
}
@media(max-width:768px) { .pk-mob-bar { display: flex; } }

/* ── Inline Edit Mode ── */
.pk-view-field { display: block; }
.pk-edit-field  { display: none; }
.pk-edit-mode .pk-view-field { display: none !important; }
.pk-edit-mode .pk-edit-field  { display: block !important; }
.pk-edit-mode .pk-view-actions { display: none !important; }
.pk-edit-mode .pk-edit-actions { display: flex !important; }
.pk-edit-actions { display: none; gap: 8px; align-items: center; }
.pk-edit-input {
    font-size: .84rem; font-weight: 600; color: #0F172A;
    border: 1.5px solid #6366F1; border-radius: 8px;
    padding: 5px 10px; width: 100%; background: #F8FAFC;
    transition: border-color .15s, box-shadow .15s;
}
.pk-edit-input:focus { outline: none; border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
.pk-edit-select { appearance: auto; }
.pk-edit-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

/* ── Dark mode overrides ── */
body.pk-dark .pk-card-head { background: #0f172a; }
body.pk-dark .pk-action-bar { background: #1e293b; border-color: #334155; }
body.pk-dark .pk-table th { background: #0f172a; }
body.pk-dark .pk-pay-body { background: #1e293b; }
body.pk-dark .pk-pay-footer { background: #0f172a; }
body.pk-dark .pk-pay-bar { background: #334155; }
body.pk-dark .pk-wa-box { background: #0f172a; border-color: #334155; color: #f1f5f9; }
body.pk-dark .pk-mob-bar { background: #1e293b; }
body.pk-dark .pk-stt-btn.pending:hover { background: rgba(245,158,11,.1); }
body.pk-dark .pk-stt-btn.in_progress:hover { background: rgba(14,165,233,.1); }
body.pk-dark .pk-stt-btn.completed:hover { background: rgba(16,185,129,.1); }
body.pk-dark .pk-stt-btn.cancelled:hover { background: rgba(239,68,68,.1); }
body.pk-dark .pk-btn-ghost { border-color: #334155; color: #94a3b8; }
body.pk-dark .pk-btn-ghost:hover { background: #334155; color: #f1f5f9; }

@media(max-width:768px) { body { padding-bottom: 72px; } }

/* ═══════════════════════════════════════════════
   MOBILE RESPONSIVE OVERRIDES (≤768px)
═══════════════════════════════════════════════ */
@media(max-width:768px) {

  /* ── Page ── */
  .pk-page { padding-bottom: 90px; }
  .pk-wrap { padding: 0 10px; }

  /* ── Sticky topbar ── */
  .pk-topbar { padding: 8px 0; margin-bottom: 12px; }
  .pk-topbar-row { gap: 8px; }
  .pk-topbar-left { gap: 6px; }
  .pk-topbar-right { gap: 4px; }
  .pk-id-tag { font-size: .74rem; padding: 3px 9px; }

  /* ── Hero ── */
  .pk-hero { padding: 18px 16px; border-radius: 16px; margin-bottom: 12px; }
  .pk-hero-inner { flex-direction: column; gap: 14px; }
  .pk-hero-name { font-size: 1.35rem; margin-bottom: 10px; }
  .pk-hero-meta { gap: 10px; }
  .pk-hero-meta-item { font-size: .8rem; }
  .pk-hero-pills { flex-direction: row; flex-wrap: wrap; align-items: flex-start; width: 100%; }
  .pk-pill { font-size: .72rem; padding: 4px 10px; }

  /* ── Action bar: horizontal scroll ── */
  .pk-action-bar {
    padding: 10px 12px;
    overflow-x: auto;
    flex-wrap: nowrap;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    gap: 8px;
    margin-bottom: 12px;
  }
  .pk-action-bar::-webkit-scrollbar { display: none; }
  .pk-action-group { flex-wrap: nowrap; gap: 6px; flex-shrink: 0; }
  .pk-stt-btn { font-size: .72rem; padding: 5px 10px; white-space: nowrap; flex-shrink: 0; }
  .pk-btn { font-size: .78rem; padding: 7px 11px; }

  /* ── Cards ── */
  .pk-card { border-radius: 14px; margin-bottom: 12px; }
  .pk-card-head { padding: 11px 14px; }
  .pk-card-title { font-size: .82rem; }
  .pk-card-body { padding: 14px; }

  /* ── Info grid: 2-col on mobile ── */
  .pk-info-grid { grid-template-columns: 1fr 1fr; gap: 14px 10px; }
  .pk-info-label { font-size: .65rem; }
  .pk-info-val { font-size: .82rem; }

  /* ── Boat blocks: 2-col ── */
  .pk-boat-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
  .pk-block { padding: 11px 12px; border-radius: 10px; }
  .pk-block-icon { width: 26px; height: 26px; border-radius: 7px; margin-bottom: 6px; }
  .pk-block-val { font-size: .84rem; }

  /* ── Payment table: card rows ── */
  .pk-table thead { display: none; }
  .pk-table tbody tr {
    display: flex; flex-direction: column;
    background: var(--pk-card);
    border-radius: 12px;
    border: 1px solid var(--pk-border);
    margin-bottom: 8px;
    padding: 12px 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
  }
  .pk-table tbody td {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 5px 0;
    border: none;
    font-size: .81rem;
  }
  .pk-table tbody td[data-label]::before {
    content: attr(data-label);
    font-size: .64rem; font-weight: 700;
    color: var(--pk-muted); text-transform: uppercase;
    letter-spacing: .5px; flex-shrink: 0; min-width: 80px;
  }

  /* ── Sidebar payment card ── */
  .pk-pay-row { padding: 10px 14px; font-size: .82rem; }
  .pk-pay-grad { padding: 12px 14px; }
  .pk-pay-grad-title { font-size: .82rem; }
  .pk-pay-footer { padding: 10px 14px; }

  /* ── Staff/margin box ── */
  .pk-staff-head { padding: 10px 14px; }
  .pk-staff-body { padding: 12px 14px; }
  .pk-mrow { margin-bottom: 8px; }
  .pk-m-lbl { font-size: .74rem; }
  .pk-m-val { font-size: .86rem; }

  /* ── WhatsApp textarea ── */
  .pk-wa-box { font-size: .79rem; min-height: 120px; padding: 10px 12px; }

  /* ── Mobile sticky action bar ── */
  .pk-mob-bar {
    padding: 10px 14px 14px;
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-top: 1px solid var(--pk-border);
    box-shadow: 0 -4px 24px rgba(0,0,0,.1);
    gap: 8px;
    align-items: center;
  }
  .pk-mob-bar .pk-btn { flex: 1; justify-content: center; font-size: .8rem; padding: 10px 8px; border-radius: 12px; }
  .pk-mob-bar .pk-btn svg, .pk-mob-bar .pk-btn [data-feather] { width: 15px; height: 15px; }

  /* ── Action bar on mobile: show only icons for less-used btns ── */
  .pk-btn-text-hide { display: none; }

  /* ── Toast: above mobile bar ── */
  .pk-toast { bottom: 84px; }
}

@media(max-width:480px) {
  .pk-info-grid { grid-template-columns: 1fr; }
  .pk-boat-grid { grid-template-columns: 1fr 1fr; }
  .pk-hero-name { font-size: 1.2rem; }
  .pk-wrap { padding: 0 8px; }
  .pk-mob-bar .pk-btn { font-size: .74rem; padding: 9px 6px; }
}
</style>

{{-- ═══════════════════════════════════════════════
     PHP DATA PREP
═══════════════════════════════════════════════ --}}
@php
    $lead       = $booking->lead;
    $st         = $booking->booking_status;
    $stLabel    = ['confirmed'=>'✓ Confirmed','in_progress'=>'⟳ Ongoing','completed'=>'✓ Completed','cancelled'=>'✕ Cancelled','pending'=>'◷ Pending'];
    $stPill     = ['confirmed'=>'pk-pill-emerald','in_progress'=>'pk-pill-sky','completed'=>'pk-pill-glass','cancelled'=>'pk-pill-rose','pending'=>'pk-pill-amber'];

    $adults         = optional($lead)->pax ?? 0;
    $children       = optional($lead)->children ?? 0;
    $altContact     = optional($lead)->alt_phone ?? '';
    $country        = optional($lead)->country ?? 'India';
    $leadSource     = optional($lead)->source ?? 'visitkashi';
    $bookingType    = optional($lead)->booking_type ?? '—';
    $pickupGhat     = optional($lead)->pickup_ghat ?? '—';
    $dropPoint      = optional($lead)->drop_ghat ?? '—';
    $pickupTime     = optional($lead)->pickup_time ?? '—';
    $dropTime       = optional($lead)->drop_time ?? '—';
    $pickupAddress  = optional($lead)->pickup_address ?? '—';
    $eventOnBoat    = optional($lead)->event_on_boat ?? '—';

    // Boat type from quotation or lead short_plan
    $firstItem = $booking->quotation?->items?->first();
    $boatType  = optional($firstItem?->serviceTemplate)->name ?? optional($lead)->short_plan ?? '—';

    // Financial — prefer live payments sum; fall back to stored paid_amount for older bookings
    $paidAmt     = $booking->payments->sum('amount');
    if ($paidAmt == 0 && $booking->paid_amount > 0) {
        $paidAmt = (float)$booking->paid_amount;
    }
    $discountAmt = (float)($booking->discount_amount ?? 0);
    if ($discountAmt <= 0 && $booking->quotation) {
        $discountAmt = (float)($booking->quotation->discount_amount ?? 0);
    }
    $netTotal  = max(0, $booking->total_amount - $discountAmt);
    $pendAmt   = max(0, $netTotal - $paidAmt);
    $paidPct   = $netTotal > 0 ? min(100, ($paidAmt / $netTotal) * 100) : 0;

    // Vendor / margin
    $totalVendorCost = $booking->serviceAssignments->sum('assigned_cost');
    $totalMargin     = $booking->total_amount - $totalVendorCost;
    $marginPct       = $booking->total_amount > 0 ? ($totalMargin / $booking->total_amount) * 100 : 0;

    // Boatman
    $firstAssign = $booking->serviceAssignments->first();
    $boatman     = $firstAssign ? optional($firstAssign->serviceProvider)->name : null;

    // ── Stay detection ─────────────────────────────────────
    $isStay = optional($firstItem?->serviceTemplate?->serviceType)->name === 'Stay'
           || Str::startsWith(optional($lead)->short_plan ?? '', 'Property:');

    $stayProperty = '—'; $stayCheckinFull = '—'; $stayCheckoutFull = '—';
    $stayNights = '—'; $stayRooms = '—'; $stayGuests = '—';
    $stayMeal = '—'; $stayRequests = '—';

    if ($isStay) {
        $planParts = [];
        foreach (explode('|', optional($lead)->short_plan ?? '') as $seg) {
            $seg = trim($seg);
            if (str_contains($seg, ':')) {
                [$k, $v] = explode(':', $seg, 2);
                $planParts[trim($k)] = trim($v);
            }
        }
        $stayProperty    = $planParts['Property'] ?? optional($firstItem?->serviceTemplate)->name ?? '—';
        $ciRaw           = $planParts['Check-in'] ?? '';
        $ciParts         = explode('→', $ciRaw);
        $stayCheckinFull = trim($ciParts[0] ?? '') ?: ($lead->booking_start_date ? $lead->booking_start_date->format('d M Y') : '—');
        $stayCheckoutFull= isset($ciParts[1]) ? trim(preg_replace('/^Check-out:\s*/i', '', trim($ciParts[1]))) : ($lead->booking_end_date ? $lead->booking_end_date->format('d M Y') : '—');
        $stayNights      = $planParts['Duration'] ?? (optional($firstItem)->quantity ? $firstItem->quantity . ' night(s)' : '—');
        $stayRooms       = $planParts['Rooms']    ?? '—';
        $stayGuests      = $planParts['Guests']   ?? '—';
        $stayMeal        = $planParts['Meal Plan'] ?? '—';
        $stayRequests    = $planParts['Requests'] ?? '—';
    }

    // Stay computed total: unit_price = grand total (qty always 1 from form)
    // Rate per room = total / nights / rooms (for formula display)
    $stayRoomsCount    = 1;
    $stayNightsCount   = 0;
    $stayRatePerRoom   = 0;
    $stayComputedTotal = 0;
    if ($isStay) {
        preg_match('/^(\d+)/', $stayRooms, $rm);
        $stayRoomsCount  = isset($rm[1]) ? (int)$rm[1] : 1;
        preg_match('/^(\d+)/', $stayNights, $nm);
        $stayNightsCount = isset($nm[1]) ? (int)$nm[1] : 0;
        // unit_price IS the grand total (form submits total in unit_price, qty=1)
        $stayComputedTotal = (float)(optional($firstItem)->unit_price ?? 0);
        if ($stayComputedTotal <= 0) {
            $stayComputedTotal = (float)$booking->total_amount;
        }
        // Derive rate per room per night from total ÷ nights ÷ rooms
        $stayRatePerRoom = ($stayNightsCount > 0 && $stayRoomsCount > 0)
            ? ($stayComputedTotal / $stayNightsCount / $stayRoomsCount)
            : 0;
        if ($stayComputedTotal > 0) {
            $stayNet = max(0, $stayComputedTotal - $discountAmt);
            $pendAmt = max(0, $stayNet - $paidAmt);
            $paidPct = $stayNet > 0 ? min(100, ($paidAmt / $stayNet) * 100) : 0;
        }
    }

    $altMobile = trim(optional($lead)->alt_phone ?? '');

    // ── Edit-mode pre-fill values ────────────────────────────
    $editCheckinDate  = optional($lead)->booking_start_date ? $lead->booking_start_date->format('Y-m-d') : '';
    $editCheckoutDate = optional($lead)->booking_end_date   ? $lead->booking_end_date->format('Y-m-d') : '';
    $editProperty     = ($stayProperty !== '—') ? $stayProperty : '';
    preg_match('/^(\d+)\s*(?:\((.+)\))?/', $stayRooms, $erArr);
    $editRoomsCount   = $erArr[1] ?? 1;
    $editRoomType     = $erArr[2] ?? '';
    preg_match('/(\d+)\s*Adult/i', $stayGuests, $eaArr);
    preg_match('/(\d+)\s*Child/i', $stayGuests, $ecArr);
    $editAdults   = $eaArr[1] ?? 0;
    $editChildren = $ecArr[1] ?? 0;
    $editMeal     = ($stayMeal !== '—') ? $stayMeal : '';
    $editRequests = ($stayRequests !== '—') ? $stayRequests : '';
    $editTotal    = $isStay && $stayComputedTotal > 0 ? $stayComputedTotal : $booking->total_amount;

    // WhatsApp message
    if ($isStay) {
        $stayDisplayTotal = $stayComputedTotal > 0 ? $stayComputedTotal : $booking->total_amount;
        $stayPendForWa    = $stayDisplayTotal - $paidAmt;
        $waMsg = "Namaste *{$lead?->guest_name}* 🙏\n\nYour hotel booking with *Visit Kashi* is confirmed! ✅\n\n🏨 *Stay Booking Details*\nBooking ID: *{$booking->booking_number}*\nProperty: *{$stayProperty}*\nCheck-in: *{$stayCheckinFull}*\nCheck-out: *{$stayCheckoutFull}*\nDuration: *{$stayNights}*\nRooms: *{$stayRooms}*\nGuests: *{$stayGuests}*"
            . ($altMobile ? "\nAlt. Mobile: *{$altMobile}*" : "")
            . ($stayMeal !== '—' ? "\nMeal Plan: *{$stayMeal}*" : "")
            . ($stayRequests !== '—' ? "\nRequests: *{$stayRequests}*" : "")
            . "\n\n💰 *Payment Summary*\nTotal: ₹" . number_format($stayDisplayTotal, 2)
            . ($discountAmt > 0 ? "\nDiscount: -₹" . number_format($discountAmt, 2) : "")
            . "\nAdvance Paid: ₹" . number_format($paidAmt, 2)
            . "\nBalance Due: ₹" . number_format($stayPendForWa, 2)
            . "\n\nThank you! 🙏\n*Visit Kashi Team*";
    } else {
        $waMsg = "Namaste *{$lead?->guest_name}* 🙏\n\nYour booking with *Visit Kashi* is confirmed! ✅\n\n🧾 *Booking Details*\nBooking ID: *{$booking->booking_number}*\nTravel Date: *{$booking->booking_date->format('d M Y')}*\nBoat: *{$boatType}*\nPickup Ghat: *{$pickupGhat}*\nPickup Time: *{$pickupTime}*\nGuests: *{$adults} Adult(s)" . ($children ? ", {$children} Child(ren)" : "") . "*"
            . ($altMobile ? "\nAlt. Mobile: *{$altMobile}*" : "")
            . "\n\n💰 *Payment Summary*\nTotal: ₹" . number_format($booking->total_amount, 2)
            . "\nAdvance Paid: ₹" . number_format($paidAmt, 2)
            . "\nBalance Due: ₹" . number_format($pendAmt, 2)
            . "\n\nThank you! 🙏\n*Visit Kashi Team*";
    }

    $guestPhone = preg_replace('/[^0-9]/', '', optional($lead)->contact ?? '');
@endphp

{{-- ─────────────── PAGE ─────────────── --}}
<div class="pk-page" id="pkPage">

{{-- Hidden edit form (inputs live inside the page; this form submits them) --}}
<form id="editBookingForm" action="{{ route('bookings.update', $booking->id) }}" method="POST" style="display:none">
    @csrf
    @method('PUT')
    <input type="hidden" name="booking_date" value="{{ $booking->booking_date->format('Y-m-d') }}">
    <input type="hidden" name="short_plan"   id="editShortPlan" value="{{ optional($lead)->short_plan }}">
</form>

<div class="pk-wrap">

    {{-- ── Sticky Topbar ── --}}
    <div class="pk-topbar">
        <div class="pk-topbar-row">
            <div class="pk-topbar-left">
                <span class="pk-id-tag" onclick="copyId('{{ $booking->booking_number }}')" title="Click to copy">
                    <i data-feather="hash" style="width:12px;height:12px"></i>
                    {{ $booking->booking_number }}
                </span>
                <span class="pk-badge {{ $st === 'confirmed' || $st === 'completed' ? 'pk-b-emerald' : ($st === 'in_progress' ? 'pk-b-sky' : ($st === 'cancelled' ? 'pk-b-rose' : 'pk-b-amber')) }}" style="font-size:.74rem;gap:5px">
                    <span class="pk-dot {{ $st }}"></span>
                    {{ $stLabel[$st] ?? ucfirst($st) }}
                </span>
            </div>
            <div class="pk-topbar-right">
                <button class="pk-btn pk-btn-icon pk-btn-ghost" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                    <i data-feather="moon"></i>
                </button>
                <a href="{{ route('bookings.index') }}" class="pk-btn pk-btn-ghost">
                    <i data-feather="arrow-left"></i>
                    <span class="d-none d-sm-inline">All Bookings</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ── Hero Card ── --}}
    <div class="pk-hero" style="{{ $isStay ? 'background:linear-gradient(135deg,#0f766e 0%,#0d9488 45%,#059669 100%);box-shadow:0 12px 40px rgba(13,148,136,.35);' : '' }}">
        <div class="pk-hero-inner">
            <div>
                <div class="pk-hero-label">{{ $isStay ? 'Hotel Stay Booking' : 'Booking ID' }}</div>
                <div class="pk-hero-name">{{ optional($lead)->guest_name ?? 'Guest' }}</div>
                <div class="pk-hero-meta">
                    @if(optional($lead)->contact)
                    <div class="pk-hero-meta-item">
                        <span>📱</span>
                        <span>{{ $lead->contact }}</span>
                    </div>
                    @endif
                    @if($isStay && $stayProperty !== '—')
                    <div class="pk-hero-meta-item">
                        <span>🏨</span>
                        <span>{{ $stayProperty }}</span>
                    </div>
                    <div class="pk-hero-meta-item">
                        <span>📅</span>
                        <span>Check-in: {{ $stayCheckinFull }}</span>
                    </div>
                    <div class="pk-hero-meta-item">
                        <span>📅</span>
                        <span>Check-out: {{ $stayCheckoutFull }}</span>
                    </div>
                    @else
                    <div class="pk-hero-meta-item">
                        <span>📅</span>
                        <span>{{ $booking->booking_date->format('d M Y') }}</span>
                    </div>
                    @if($adults)
                    <div class="pk-hero-meta-item">
                        <span>👥</span>
                        <span>{{ $adults }} Adult{{ $adults != 1 ? 's' : '' }}{{ $children ? ', ' . $children . ' Child' . ($children != 1 ? 'ren' : '') : '' }}</span>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            <div class="pk-hero-pills">
                <span class="pk-pill pk-pill-glass">
                    <i data-feather="hash" style="width:11px;height:11px"></i>
                    {{ $booking->booking_number }}
                </span>
                @if($isStay)
                <span class="pk-pill pk-pill-emerald">
                    <span>🏨</span> Hotel Stay
                </span>
                @if($stayNights !== '—')
                <span class="pk-pill pk-pill-sky">
                    <i data-feather="moon" style="width:11px;height:11px"></i>
                    {{ $stayNights }}
                </span>
                @endif
                @else
                <span class="pk-pill pk-pill-sky">
                    <i data-feather="anchor" style="width:11px;height:11px"></i>
                    Boat
                </span>
                @endif
                <span class="pk-pill {{ $stPill[$st] ?? 'pk-pill-glass' }}">
                    <span class="pk-dot {{ $st }}" style="width:6px;height:6px"></span>
                    {{ $stLabel[$st] ?? ucfirst($st) }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Action Bar ── --}}
    <div class="pk-action-bar">
        <div class="pk-action-group">
            <a href="#" class="pk-btn pk-btn-ghost pk-view-actions" onclick="enterEditMode();return false;">
                <i data-feather="edit-2"></i> Edit
            </a>
            <div class="pk-edit-actions">
                <button type="button" class="pk-btn pk-btn-ghost" onclick="exitEditMode()">
                    <i data-feather="x"></i> Cancel
                </button>
                <button type="button" class="pk-btn" style="background:#10B981;color:#fff;gap:6px;" onclick="submitEdit()">
                    <i data-feather="check" style="width:14px;height:14px;"></i> Update
                </button>
            </div>
            <button type="button" class="pk-btn pk-btn-wa" onclick="shareWA()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.555 4.104 1.524 5.83L.057 23.882l6.197-1.625A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.373l-.359-.214-3.68.965.981-3.595-.234-.369A9.818 9.818 0 112 12a9.818 9.818 0 0110 9.818z"/></svg>
                WhatsApp
            </button>
            <button type="button" class="pk-btn pk-btn-ghost" onclick="window.open('{{ route('booking.report.view', $booking->id) }}','_blank')">
                <i data-feather="printer"></i> Print / PDF
            </button>
        </div>

        <div class="pk-action-group">
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--pk-muted)">Status:</span>
            <button type="button" class="pk-stt-btn pending {{ $st === 'pending' ? 'active' : '' }}" onclick="updateStatus('pending')">Pending</button>
            <button type="button" class="pk-stt-btn in_progress {{ $st === 'in_progress' ? 'active' : '' }}" onclick="updateStatus('in_progress')">Ongoing</button>
            <button type="button" class="pk-stt-btn completed {{ $st === 'completed' ? 'active' : '' }}" onclick="updateStatus('completed')">Completed</button>
            <button type="button" class="pk-stt-btn cancelled {{ $st === 'cancelled' ? 'active' : '' }}" onclick="updateStatus('cancelled')">Cancelled</button>
            <form id="statusForm" action="{{ route('bookings.update-status', $booking->id) }}" method="POST" class="d-none">
                @csrf
                <input type="hidden" name="status" id="statusVal">
            </form>
            <div class="pk-action-sep"></div>
            @can('booking-delete')
            <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-inline" id="deleteBookingForm">
                @csrf
                @method('DELETE')
                <button type="button" class="pk-btn pk-btn-rose" style="padding:6px 12px;font-size:.8rem" onclick="confirmDeleteBooking()">
                    <i data-feather="trash-2" style="width:13px;height:13px"></i> Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    {{-- ── Two-Column Grid ── --}}
    <div class="pk-grid">

        {{-- ══════════ MAIN ══════════ --}}
        <div>

            {{-- Guest Details --}}
            <div class="pk-card">
                <div class="pk-card-head">
                    <h6 class="pk-card-title"><i data-feather="user"></i> Guest Details</h6>
                </div>
                <div class="pk-card-body">
                    <div class="pk-info-grid">
                        <div>
                            <div class="pk-info-label">Guest Name</div>
                            <div class="pk-info-val pk-view-field">{{ optional($lead)->guest_name ?? '—' }}</div>
                            <input class="pk-edit-field pk-edit-input" form="editBookingForm" name="guest_name" value="{{ optional($lead)->guest_name }}" required>
                        </div>
                        <div>
                            <div class="pk-info-label">Mobile</div>
                            <div class="pk-info-val pk-view-field">
                                <i data-feather="phone" style="width:13px;height:13px;color:var(--pk-purple)"></i>
                                {{ optional($lead)->contact ?? '—' }}
                            </div>
                            <input class="pk-edit-field pk-edit-input" form="editBookingForm" name="contact" value="{{ optional($lead)->contact }}" required>
                        </div>
                        <div>
                            <div class="pk-info-label">Alt. Mobile</div>
                            <div class="pk-info-val pk-view-field {{ !$altContact ? 'empty' : '' }}">{{ $altContact ?: '—' }}</div>
                            <input class="pk-edit-field pk-edit-input" form="editBookingForm" name="alt_phone" value="{{ $altContact }}" placeholder="—">
                        </div>
                        <div>
                            <div class="pk-info-label">Email</div>
                            <div class="pk-info-val pk-view-field {{ !optional($lead)->email ? 'empty' : '' }}">{{ optional($lead)->email ?: '—' }}</div>
                            <input class="pk-edit-field pk-edit-input" form="editBookingForm" type="email" name="email" value="{{ optional($lead)->email }}" placeholder="—">
                        </div>
                        <div>
                            <div class="pk-info-label">Country</div>
                            <div class="pk-info-val pk-view-field"><span>🇮🇳</span> {{ $country }}</div>
                            <input class="pk-edit-field pk-edit-input" form="editBookingForm" name="country" value="{{ $country }}" placeholder="India">
                        </div>
                        <div>
                            <div class="pk-info-label">Guests</div>
                            <div class="pk-info-val">
                                @if($adults || $children)
                                    {{ $adults ? $adults . ' Adult' . ($adults != 1 ? 's' : '') : '' }}{{ $children ? ($adults ? ', ' : '') . $children . ' Child' . ($children != 1 ? 'ren' : '') : '' }}
                                @else —
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="pk-info-label">Booking Type</div>
                            <div class="pk-info-val">
                                @if($isStay)
                                    <span class="pk-badge pk-b-emerald">🏨 Hotel Stay</span>
                                @else
                                    <span class="pk-badge pk-b-sky"><i data-feather="anchor"></i> Boat</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="pk-info-label">Lead Source</div>
                            <div class="pk-info-val">{{ $leadSource ?: '—' }}</div>
                        </div>
                    </div>

                    @if($boatman)
                    <div class="pk-sep"></div>
                    <div style="display:flex;gap:16px;flex-wrap:wrap;align-items:center">
                        <div>
                            <div class="pk-info-label" style="margin-bottom:4px">Assigned Boatman</div>
                            <span class="pk-badge pk-b-emerald"><i data-feather="anchor"></i> {{ $boatman }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── Stay Details (Hotel) OR Boat Booking Details ── --}}
            @if($isStay)
            {{-- ══════ STAY DETAILS CARD ══════ --}}
            <div class="pk-card" style="border-top:3px solid #0d9488;">
                <div class="pk-card-head" style="background:linear-gradient(to right,#f0fdfa,#fff);">
                    <h6 class="pk-card-title" style="color:#0f766e;">
                        <span style="font-size:1.1rem;">🏨</span> Stay / Hotel Details
                    </h6>
                    <span style="font-size:.7rem;font-weight:700;color:#0d9488;background:#ccfbf1;padding:3px 10px;border-radius:20px;">Hotel Stay</span>
                </div>
                <div class="pk-card-body">

                    {{-- Property highlight strip --}}
                    <div class="pk-view-field" style="background:linear-gradient(135deg,#f0fdfa,#ecfdf5);border:1.5px solid #99f6e4;border-radius:14px;padding:16px 20px;margin-bottom:18px;display:flex;align-items:center;gap:16px;">
                        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#0d9488,#059669);display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0;">🏨</div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.68rem;font-weight:700;color:#0f766e;text-transform:uppercase;letter-spacing:.5px;">Property / Hotel</div>
                            <div style="font-size:1.15rem;font-weight:800;color:#0f172a;line-height:1.2;margin-top:2px;">{{ $stayProperty }}</div>
                            @if($stayRooms !== '—')<div style="font-size:.78rem;font-weight:600;color:#0d9488;margin-top:3px;">{{ $stayRooms }}</div>@endif
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div style="font-size:.65rem;font-weight:700;color:#64748b;text-transform:uppercase;">Duration</div>
                            <div style="font-size:1.4rem;font-weight:800;color:#0f766e;line-height:1;">{{ explode(' ', $stayNights)[0] ?? '—' }}</div>
                            <div style="font-size:.7rem;color:#64748b;font-weight:600;">night(s)</div>
                        </div>
                    </div>
                    {{-- Edit: Property + Dates --}}
                    <div class="pk-edit-field" style="margin-bottom:14px;">
                        <div class="pk-info-label" style="margin-bottom:4px;">Property / Hotel</div>
                        <input class="pk-edit-input" form="editBookingForm" name="stay_property" value="{{ $editProperty }}" placeholder="Property name">
                        <div class="pk-edit-row" style="margin-top:8px;">
                            <div>
                                <div class="pk-info-label" style="margin-bottom:3px;">Check-in Date</div>
                                <input class="pk-edit-input" form="editBookingForm" type="date" name="booking_start_date" value="{{ $editCheckinDate }}">
                            </div>
                            <div>
                                <div class="pk-info-label" style="margin-bottom:3px;">Check-out Date</div>
                                <input class="pk-edit-input" form="editBookingForm" type="date" name="booking_end_date" value="{{ $editCheckoutDate }}">
                            </div>
                        </div>
                    </div>

                    {{-- Check-in / Check-out timeline --}}
                    <div class="pk-view-field" style="display:grid;grid-template-columns:1fr auto 1fr;gap:0;margin-bottom:18px;align-items:center;">
                        <div style="background:#f0fdfa;border:1.5px solid #99f6e4;border-radius:14px;padding:14px 16px;text-align:center;">
                            <div style="font-size:.62rem;font-weight:800;color:#0d9488;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">✈ Check-in</div>
                            <div style="font-size:.92rem;font-weight:800;color:#0f172a;line-height:1.3;">{{ $stayCheckinFull }}</div>
                        </div>
                        <div style="text-align:center;padding:0 10px;color:#0d9488;font-size:1.2rem;">→</div>
                        <div style="background:#fff7ed;border:1.5px solid #fed7aa;border-radius:14px;padding:14px 16px;text-align:center;">
                            <div style="font-size:.62rem;font-weight:800;color:#ea580c;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">✈ Check-out</div>
                            <div style="font-size:.92rem;font-weight:800;color:#0f172a;line-height:1.3;">{{ $stayCheckoutFull }}</div>
                        </div>
                    </div>

                    {{-- Stay info grid --}}
                    <div class="pk-info-grid">
                        <div>
                            <div class="pk-info-label">Booking Date</div>
                            <div class="pk-info-val">{{ $booking->booking_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="pk-info-label">Duration</div>
                            <div class="pk-info-val pk-view-field">{{ $stayNights }}</div>
                            <div class="pk-edit-field" style="font-size:.78rem;color:#94A3B8;margin-top:4px;">Auto from dates</div>
                        </div>
                        <div>
                            <div class="pk-info-label">Rooms</div>
                            <div class="pk-info-val pk-view-field {{ $stayRooms === '—' ? 'empty' : '' }}">{{ $stayRooms }}</div>
                            <div class="pk-edit-field pk-edit-row">
                                <input class="pk-edit-input" form="editBookingForm" type="number" name="stay_rooms" value="{{ $editRoomsCount }}" min="1" placeholder="1" style="width:70px">
                                <input class="pk-edit-input" form="editBookingForm" name="stay_room_type" value="{{ $editRoomType }}" placeholder="Room type">
                            </div>
                        </div>
                        <div>
                            <div class="pk-info-label">Guests</div>
                            <div class="pk-info-val pk-view-field {{ $stayGuests === '—' ? 'empty' : '' }}">{{ $stayGuests }}</div>
                            <div class="pk-edit-field pk-edit-row">
                                <div>
                                    <div style="font-size:.65rem;color:#94A3B8;margin-bottom:2px;">Adults</div>
                                    <input class="pk-edit-input" form="editBookingForm" type="number" name="stay_adults" value="{{ $editAdults }}" min="0" placeholder="0">
                                </div>
                                <div>
                                    <div style="font-size:.65rem;color:#94A3B8;margin-bottom:2px;">Children</div>
                                    <input class="pk-edit-input" form="editBookingForm" type="number" name="stay_children" value="{{ $editChildren }}" min="0" placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="pk-info-label">Meal Plan</div>
                            <div class="pk-info-val pk-view-field {{ $stayMeal === '—' ? 'empty' : '' }}">
                                @if($stayMeal !== '—')
                                    <span style="display:inline-flex;align-items:center;gap:5px;background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:.76rem;font-weight:700;">🍽 {{ $stayMeal }}</span>
                                @else —
                                @endif
                            </div>
                            <select class="pk-edit-field pk-edit-input pk-edit-select" form="editBookingForm" name="stay_meal">
                                <option value="">— None —</option>
                                @foreach(['EP','CP','MAP','AP','All Inclusive'] as $mp)
                                    <option value="{{ $mp }}" {{ $editMeal === $mp ? 'selected' : '' }}>{{ $mp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <div class="pk-info-label">Special Requests</div>
                            <div class="pk-info-val pk-view-field {{ $stayRequests === '—' ? 'empty' : '' }}">{{ $stayRequests }}</div>
                            <input class="pk-edit-field pk-edit-input" form="editBookingForm" name="stay_requests" value="{{ $editRequests }}" placeholder="—">
                        </div>
                    </div>

                    {{-- Total Amount (edit mode) --}}
                    <div class="pk-edit-field" style="margin-top:14px;padding-top:14px;border-top:1px dashed #E2E8F0;">
                        <div class="pk-info-label" style="margin-bottom:4px;">Total Amount (₹)</div>
                        <input class="pk-edit-input" form="editBookingForm" type="number" name="total_amount" id="editTotalAmount" value="{{ $editTotal }}" min="0" step="0.01" required style="font-size:1rem;font-weight:800;">
                    </div>

                    <div class="pk-sep"></div>
                    <div>
                        <div class="pk-info-label" style="margin-bottom:4px;">Internal Notes</div>
                        <div class="pk-info-val pk-view-field" style="white-space:pre-wrap;">{{ $booking->notes ?: '—' }}</div>
                        <textarea class="pk-edit-field pk-edit-input" form="editBookingForm" name="notes" rows="2" placeholder="Internal notes…" style="resize:vertical;">{{ $booking->notes }}</textarea>
                    </div>
                </div>
            </div>

            @else
            {{-- ══════ BOAT BOOKING DETAILS CARD ══════ --}}
            <div class="pk-card">
                <div class="pk-card-head">
                    <h6 class="pk-card-title"><i data-feather="anchor"></i> Boat Booking Details</h6>
                </div>
                <div class="pk-card-body">
                    <div class="pk-boat-grid">
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="calendar"></i></div>
                            <div class="pk-block-label">Booking Date</div>
                            <div class="pk-block-val">{{ $booking->booking_date->format('d M Y') }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="anchor"></i></div>
                            <div class="pk-block-label">Boat Type</div>
                            <div class="pk-block-val {{ $boatType === '—' ? 'empty' : '' }}">{{ $boatType }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="star"></i></div>
                            <div class="pk-block-label">Event on Boat</div>
                            <div class="pk-block-val {{ $eventOnBoat === '—' ? 'empty' : '' }}">{{ $eventOnBoat }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="map-pin"></i></div>
                            <div class="pk-block-label">Ghat / Pickup</div>
                            <div class="pk-block-val {{ $pickupGhat === '—' ? 'empty' : '' }}">{{ $pickupGhat }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="navigation"></i></div>
                            <div class="pk-block-label">Pickup Address</div>
                            <div class="pk-block-val {{ $pickupAddress === '—' ? 'empty' : '' }}">{{ $pickupAddress }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="clock"></i></div>
                            <div class="pk-block-label">Pickup Time</div>
                            <div class="pk-block-val {{ $pickupTime === '—' ? 'empty' : '' }}">{{ $pickupTime }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="clock"></i></div>
                            <div class="pk-block-label">Drop Time</div>
                            <div class="pk-block-val {{ $dropTime === '—' ? 'empty' : '' }}">{{ $dropTime }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="map-pin"></i></div>
                            <div class="pk-block-label">Drop Point</div>
                            <div class="pk-block-val {{ $dropPoint === '—' ? 'empty' : '' }}">{{ $dropPoint }}</div>
                        </div>
                        <div class="pk-block">
                            <div class="pk-block-icon"><i data-feather="tag"></i></div>
                            <div class="pk-block-label">Ride Type</div>
                            <div class="pk-block-val {{ $bookingType === '—' ? 'empty' : '' }}">{{ $bookingType }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- WhatsApp Share --}}
            <div class="pk-card">
                <div class="pk-card-head">
                    <h6 class="pk-card-title">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.555 4.104 1.524 5.83L.057 23.882l6.197-1.625A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.373l-.359-.214-3.68.965.981-3.595-.234-.369A9.818 9.818 0 112 12a9.818 9.818 0 0110 9.818z"/></svg>
                        WhatsApp Share
                    </h6>
                    <button type="button" class="pk-btn pk-btn-ghost" style="padding:5px 10px;font-size:.76rem" onclick="copyWA()">
                        <i data-feather="copy" style="width:12px;height:12px"></i> Copy
                    </button>
                </div>
                <div class="pk-card-body">
                    <textarea class="pk-wa-box" id="waBox">{{ $waMsg }}</textarea>
                    <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
                        <button type="button" class="pk-btn pk-btn-wa" onclick="shareWA()">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.555 4.104 1.524 5.83L.057 23.882l6.197-1.625A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.373l-.359-.214-3.68.965.981-3.595-.234-.369A9.818 9.818 0 112 12a9.818 9.818 0 0110 9.818z"/></svg>
                            Share on WhatsApp
                        </button>
                        <button type="button" class="pk-btn pk-btn-ghost" onclick="copyWA()">
                            <i data-feather="copy"></i> Copy Text
                        </button>
                    </div>
                </div>
            </div>

            {{-- Payment Records --}}
            <div class="pk-card">
                <div class="pk-card-head">
                    <h6 class="pk-card-title"><i data-feather="credit-card"></i> Payment Records</h6>
                    <button type="button" class="pk-btn pk-btn-emerald" style="padding:6px 12px;font-size:.78rem"
                        data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i data-feather="plus" style="width:12px;height:12px"></i> Add Payment
                    </button>
                </div>
                <div class="pk-card-body p0">
                    <div class="table-responsive">
                        <table class="pk-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                    <th>Type</th>
                                    <th>Method</th>
                                    <th>Recorded By</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($booking->payments as $idx => $payment)
                                <tr>
                                    <td data-label="Date">
                                        <div style="font-weight:600;font-size:.85rem">{{ $payment->payment_date->format('d M Y') }}</div>
                                        <div style="font-size:.72rem;color:var(--pk-muted)">{{ $payment->payment_date->diffForHumans() }}</div>
                                    </td>
                                    <td data-label="Amount" class="text-end">
                                        <strong style="color:var(--pk-emerald);font-size:.9rem">₹{{ number_format($payment->amount, 2) }}</strong>
                                    </td>
                                    <td data-label="Type">
                                        <span class="pk-badge {{ $idx === 0 ? 'pk-b-indigo' : 'pk-b-sky' }}">{{ $idx === 0 ? 'Advance' : 'Payment' }}</span>
                                    </td>
                                    <td data-label="Method">
                                        <span class="pk-badge pk-b-gray">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                    </td>
                                    <td data-label="Account" style="font-size:.82rem">{{ optional($payment->paymentAccount)->account_name ?? 'Visit Kashi' }}</td>
                                    <td data-label="Actions" class="text-center">
                                        <div style="display:flex;gap:4px;justify-content:center">
                                            <button type="button" style="width:28px;height:28px;border-radius:7px;border:none;cursor:pointer;background:#FEF3C7;color:#B45309;display:flex;align-items:center;justify-content:center;"
                                                data-bs-toggle="modal" data-bs-target="#editPaymentModal"
                                                onclick="editPayment({{ $payment->id }},'{{ $payment->payment_date->format('Y-m-d') }}',{{ $payment->amount }},'{{ $payment->payment_method }}',{{ $payment->payment_account_id }},'{{ $payment->reference_number }}','{{ addslashes($payment->notes ?? '') }}')"
                                                title="Edit">
                                                <i data-feather="edit-2" style="width:12px;height:12px"></i>
                                            </button>
                                            <form action="{{ route('bookings.delete-payment', [$booking->id, $payment->id]) }}" method="POST" class="d-inline" id="delete_payment_form_{{ $payment->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" style="width:28px;height:28px;border-radius:7px;border:none;cursor:pointer;background:#FEF2F2;color:#EF4444;display:flex;align-items:center;justify-content:center;"
                                                    onclick="confirmDeletePayment({{ $payment->id }},{{ $payment->amount }})" title="Delete">
                                                    <i data-feather="trash-2" style="width:12px;height:12px"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" style="text-align:center;padding:28px;color:var(--pk-muted)">
                                        <i data-feather="inbox" style="width:32px;height:32px;opacity:.3;display:block;margin:0 auto 8px"></i>
                                        No payments recorded yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Activity Timeline --}}
            @if($activities && count($activities) > 0)
            <div class="pk-card">
                <div class="pk-card-head">
                    <h6 class="pk-card-title"><i data-feather="activity"></i> Activity History</h6>
                </div>
                <div class="pk-card-body">
                    <x-activity-timeline :activities="$activities" />
                </div>
            </div>
            @endif

        </div>{{-- /main --}}

        {{-- ══════════ SIDEBAR ══════════ --}}
        <div class="pk-sidebar">
        <div class="pk-sidebar-sticky">

            {{-- Payment Summary --}}
            <div class="pk-pay-card">
                <div class="pk-pay-grad">
                    <div class="pk-pay-grad-title">
                        <i data-feather="pie-chart" style="width:14px;height:14px;opacity:.8"></i>
                        Payment Summary
                    </div>
                </div>
                <div class="pk-pay-body">
                    <div class="pk-pay-row">
                        <span class="pk-pay-lbl">Total Amount</span>
                        @php $displayTotal = ($isStay && $stayComputedTotal > 0) ? $stayComputedTotal : $booking->total_amount; @endphp
                        <span class="pk-pay-val">₹{{ number_format($displayTotal, 2) }}</span>
                    </div>
                    @if($isStay && $stayComputedTotal > 0 && $stayRatePerRoom > 0 && $stayNightsCount > 0)
                    <div style="font-size:.72rem;color:var(--pk-muted);text-align:right;margin:-6px 0 4px;padding-bottom:6px;border-bottom:1px dashed var(--pk-border)">
                        ₹{{ number_format($stayRatePerRoom, 0) }}/room &times; {{ $stayNightsCount }}N &times; {{ $stayRoomsCount }}R
                    </div>
                    @endif
                    @if($discountAmt > 0)
                    <div class="pk-pay-row">
                        <span class="pk-pay-lbl" style="color:#F59E0B;">Discount</span>
                        <span class="pk-pay-val" style="color:#F59E0B;">-₹{{ number_format($discountAmt, 2) }}</span>
                    </div>
                    @php $netAfterDisc = $displayTotal - $discountAmt; @endphp
                    <div class="pk-pay-row" style="border-top:1px dashed var(--pk-border);padding-top:5px;">
                        <span class="pk-pay-lbl">Net Total</span>
                        <span class="pk-pay-val">₹{{ number_format($netAfterDisc, 2) }}</span>
                    </div>
                    @endif
                    <div class="pk-pay-row">
                        <span class="pk-pay-lbl">Advance Paid</span>
                        <span class="pk-pay-val em">₹{{ number_format($paidAmt, 2) }}</span>
                    </div>
                    <div class="pk-pay-row">
                        <span class="pk-pay-lbl">Balance Due</span>
                        <span class="pk-pay-val {{ $pendAmt > 0 ? 'am' : 'em' }}">₹{{ number_format($pendAmt, 2) }}</span>
                    </div>
                </div>
                <div class="pk-pay-footer">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                        @if($pendAmt <= 0)
                            <span class="pk-badge pk-b-green-lg"><i data-feather="check-circle"></i> Fully Paid</span>
                        @else
                            <span class="pk-badge pk-b-amber"><i data-feather="clock"></i> ₹{{ number_format($pendAmt, 2) }} Due</span>
                        @endif
                        @if($booking->payments->first())
                        <span style="font-size:.75rem;color:var(--pk-muted)">
                            via {{ ucfirst(str_replace('_',' ',$booking->payments->first()->payment_method)) }}
                        </span>
                        @endif
                    </div>
                    <div class="pk-pay-progress-wrap">
                        <div class="pk-pay-progress-label">
                            <span>Payment Progress</span>
                            <span style="color:var(--pk-emerald)">{{ number_format($paidPct, 0) }}%</span>
                        </div>
                        <div class="pk-pay-bar">
                            <div class="pk-pay-bar-fill" style="width:{{ $paidPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Internal Staff Box --}}
            <div class="pk-staff">
                <div class="pk-staff-head">
                    <div class="pk-staff-title">
                        <i data-feather="lock" style="width:13px;height:13px"></i>
                        Internal — Staff Only
                    </div>
                    <span class="pk-badge pk-b-amber" style="font-size:.68rem">Not on voucher</span>
                </div>
                <div class="pk-staff-body">
                    <div class="pk-mrow">
                        <div class="pk-m-lbl">Vendor Cost</div>
                        <div class="pk-m-val" style="color:var(--pk-rose)">₹{{ number_format($totalVendorCost, 2) }}</div>
                    </div>
                    <div class="pk-mrow">
                        <div class="pk-m-lbl">Margin / Profit</div>
                        <div class="pk-m-val {{ $totalMargin < 0 ? 'neg' : '' }}">
                            ₹{{ number_format($totalMargin, 2) }}
                            <span style="font-size:.78rem;opacity:.75">({{ number_format($marginPct, 1) }}%)</span>
                        </div>
                    </div>
                    <div class="pk-profit-wrap">
                        <div class="pk-profit-lbl">
                            <span>Profit Margin</span>
                            <span>{{ number_format($marginPct, 1) }}%</span>
                        </div>
                        <div class="pk-profit-rail">
                            <div class="pk-profit-fill" style="width:{{ min(100, max(0, $marginPct)) }}%"></div>
                        </div>
                    </div>
                    @if($boatman)
                    <div class="pk-sep"></div>
                    <div class="pk-m-lbl" style="margin-bottom:5px">Assigned Boatman</div>
                    <span class="pk-badge pk-b-emerald"><i data-feather="anchor"></i> {{ $boatman }}</span>
                    @endif
                </div>
            </div>

            {{-- Downloads --}}
            <div class="pk-card">
                <div class="pk-card-head">
                    <h6 class="pk-card-title"><i data-feather="download"></i> Download / Invoice</h6>
                </div>
                <div class="pk-card-body" style="display:flex;flex-direction:column;gap:8px">
                    @if($booking->is_gst_invoice)
                    <a href="{{ route('booking.gst-invoice', $booking->id) }}" target="_blank"
                        class="pk-btn pk-btn-ghost" style="justify-content:center">
                        <i data-feather="file-text"></i> GST Invoice
                    </a>
                    @else
                    <a href="{{ route('booking.invoice', $booking->id) }}" target="_blank"
                        class="pk-btn pk-btn-ghost" style="justify-content:center">
                        <i data-feather="file-text"></i> Regular Invoice
                    </a>
                    @endif
                    <a href="{{ route('booking.report.view', $booking->id) }}" target="_blank"
                        class="pk-btn pk-btn-purple" style="justify-content:center">
                        <i data-feather="printer"></i> Print Report
                    </a>
                </div>
            </div>

        </div>
        </div>{{-- /sidebar --}}

    </div>{{-- /pk-grid --}}
</div>{{-- /pk-wrap --}}
</div>{{-- /pk-page --}}

{{-- Mobile Sticky Bar --}}
<div class="pk-mob-bar">
    {{-- WhatsApp --}}
    <button type="button" onclick="shareWA()"
        style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;background:#25D366;border:none;border-radius:14px;color:#fff;padding:10px 6px;cursor:pointer;font-size:.7rem;font-weight:700;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.555 4.104 1.524 5.83L.057 23.882l6.197-1.625A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.373l-.359-.214-3.68.965.981-3.595-.234-.369A9.818 9.818 0 112 12a9.818 9.818 0 0110 9.818z"/></svg>
        WhatsApp
    </button>
    {{-- Add Payment --}}
    <button type="button" data-bs-toggle="modal" data-bs-target="#addPaymentModal"
        style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;background:#6366f1;border:none;border-radius:14px;color:#fff;padding:10px 6px;cursor:pointer;font-size:.7rem;font-weight:700;">
        <i data-feather="plus-circle" style="width:20px;height:20px;"></i>
        Payment
    </button>
    {{-- Edit --}}
    <a href="{{ route('bookings.edit', $booking->id) }}"
        style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;background:#f59e0b;border:none;border-radius:14px;color:#fff;padding:10px 6px;cursor:pointer;font-size:.7rem;font-weight:700;text-decoration:none;">
        <i data-feather="edit-2" style="width:20px;height:20px;stroke:#fff;"></i>
        Edit
    </a>
    {{-- Print --}}
    <button type="button" onclick="window.open('{{ route('booking.report.view', $booking->id) }}','_blank')"
        style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;background:#e0e7ff;border:none;border-radius:14px;color:#4338ca;padding:10px 6px;cursor:pointer;font-size:.7rem;font-weight:700;">
        <i data-feather="printer" style="width:20px;height:20px;"></i>
        Print
    </button>
</div>

{{-- Toast --}}
<div class="pk-toast" id="pkToast">
    <i data-feather="check-circle"></i>
    <span id="pkToastMsg">Copied!</span>
</div>

{{-- ═══════════════════════════════ MODALS ═══════════════════════════════ --}}

{{-- Add Payment --}}
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;padding:16px 20px">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;display:flex;align-items:center;gap:7px">
                    <i data-feather="plus-circle" style="width:16px;height:16px"></i> Add Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bookings.add-payment', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:20px">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select" onchange="filterAccounts()" required>
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="upi">UPI</option>
                            <option value="card">Card</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Payment Account <span class="text-danger">*</span></label>
                        <select name="payment_account_id" id="payment_account_id" class="form-select" onchange="updateAccInfo()" required>
                            <option value="">Select Payment Method First</option>
                            @foreach($paymentAccounts as $acct)
                                <option value="{{ $acct->id }}" data-type="{{ $acct->account_type }}"
                                    data-balance="{{ $acct->current_balance }}"
                                    data-number="{{ $acct->account_number }}" style="display:none">
                                    {{ $acct->account_name }} ({{ $acct->formatted_type }})
                                </option>
                            @endforeach
                        </select>
                        <div id="acct-info" class="mt-1" style="display:none"><small class="text-muted" id="acct-details"></small></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        <small class="text-muted">Balance Due: <strong style="color:#f59e0b">₹{{ number_format($pendAmt, 2) }}</strong></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Reference</label>
                        <input type="text" name="reference_number" class="form-control" placeholder="UPI Ref, Cheque No…">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e5e7eb;padding:12px 20px">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i data-feather="check" style="width:14px;height:14px"></i> Add Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Payment --}}
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:18px;overflow:hidden;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;padding:16px 20px">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;display:flex;align-items:center;gap:7px">
                    <i data-feather="edit" style="width:16px;height:16px"></i> Edit Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPaymentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding:20px">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="edit_payment_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="edit_payment_method" class="form-select" onchange="filterEditAccounts()" required>
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="upi">UPI</option>
                            <option value="card">Card</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Account <span class="text-danger">*</span></label>
                        <select name="payment_account_id" id="edit_payment_account_id" class="form-select" required>
                            <option value="">Select Method First</option>
                            @foreach($paymentAccounts as $acct)
                                <option value="{{ $acct->id }}" data-type="{{ $acct->account_type }}">
                                    {{ $acct->account_name }} ({{ $acct->formatted_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Reference</label>
                        <input type="text" name="reference_number" id="edit_reference_number" class="form-control">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" style="font-size:.84rem">Notes</label>
                        <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding:12px 20px">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i data-feather="save" style="width:14px;height:14px"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════ JS ═══════════════════════════════ --}}
<script>
// ── Dark Mode ──
function toggleDark() {
    const body = document.body;
    const on = body.classList.toggle('pk-dark');
    localStorage.setItem('pkDark', on ? '1' : '0');
    const ico = document.querySelector('#darkBtn [data-feather]');
    if (ico) ico.setAttribute('data-feather', on ? 'sun' : 'moon');
    feather.replace();
    showToast(on ? 'Dark mode on' : 'Light mode on', 'moon');
}
(function(){
    if (localStorage.getItem('pkDark') === '1') {
        document.body.classList.add('pk-dark');
        document.addEventListener('DOMContentLoaded', () => {
            const ico = document.querySelector('#darkBtn [data-feather]');
            if (ico) { ico.setAttribute('data-feather', 'sun'); feather.replace(); }
        });
    }
})();

// ── Toast ──
function showToast(msg, icon='check-circle') {
    const t = document.getElementById('pkToast');
    const m = document.getElementById('pkToastMsg');
    m.textContent = msg;
    const ico = t.querySelector('[data-feather]');
    if (ico) ico.setAttribute('data-feather', icon);
    feather.replace();
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2600);
}

// ── Copy Booking ID ──
function copyId(id) {
    navigator.clipboard.writeText(id).then(() => showToast('Booking ID copied!'));
}

// ── WhatsApp ──
function copyWA() {
    navigator.clipboard.writeText(document.getElementById('waBox').value)
        .then(() => showToast('Message copied!'));
}
function shareWA() {
    const msg = document.getElementById('waBox').value;
    const phone = '{{ $guestPhone }}';
    const url = 'https://wa.me/91' + phone + '?text=' + encodeURIComponent(msg);
    window.open(url, '_blank');
}

// ── Status Update ──
function updateStatus(status) {
    const nm = {pending:'Pending',in_progress:'Ongoing',completed:'Completed',cancelled:'Cancelled'};
    Swal.fire({
        title: 'Update Status?',
        html: `<p>Change to <strong>${nm[status]}</strong>?</p>`,
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#6366f1', cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, update!'
    }).then(r => {
        if (r.isConfirmed) {
            document.getElementById('statusVal').value = status;
            document.getElementById('statusForm').submit();
        }
    });
}

// ── Delete Booking ──
function confirmDeleteBooking() {
    Swal.fire({
        title:'Delete Booking?',
        html:'<p>This cannot be <strong>undone</strong>.</p>',
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#ef4444', cancelButtonColor:'#6b7280',
        confirmButtonText:'Yes, delete!'
    }).then(r => { if(r.isConfirmed) document.getElementById('deleteBookingForm').submit(); });
}

// ── Delete Payment ──
function confirmDeletePayment(paymentId, amount) {
    Swal.fire({
        title:'Delete Payment?',
        html:`<p>Delete ₹${parseFloat(amount).toFixed(2)} payment?</p>`,
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#ef4444', cancelButtonColor:'#6b7280',
        confirmButtonText:'Yes, delete!'
    }).then(r => { if(r.isConfirmed) document.getElementById('delete_payment_form_' + paymentId).submit(); });
}

// ── Payment Account Filtering ──
function filterAccounts() {
    const method = document.getElementById('payment_method').value;
    const sel    = document.getElementById('payment_account_id');
    sel.value = '';
    document.getElementById('acct-info').style.display = 'none';
    const opts = sel.querySelectorAll('option');
    let has = false;
    opts.forEach(o => {
        if (!o.value) return;
        const show = o.dataset.type === method;
        o.style.display = show ? 'block' : 'none';
        if (show) has = true;
    });
    sel.options[0].text = has ? 'Select Account' : 'No accounts for this method';
    feather.replace();
}

function updateAccInfo() {
    const sel = document.getElementById('payment_account_id');
    const opt = sel.options[sel.selectedIndex];
    const box = document.getElementById('acct-info');
    if (opt.value) {
        const bal = parseFloat(opt.dataset.balance);
        document.getElementById('acct-details').innerHTML =
            `Balance: <strong>₹${bal.toFixed(2)}</strong>${opt.dataset.number ? ' · ' + opt.dataset.number : ''}`;
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

// ── Edit Payment ──
function editPayment(id, date, amount, method, accountId, reference, notes) {
    const form = document.getElementById('editPaymentForm');
    form.action = `{{ route('bookings.update-payment', [$booking->id, ':id']) }}`.replace(':id', id);
    document.getElementById('edit_payment_date').value    = date;
    document.getElementById('edit_amount').value          = amount;
    document.getElementById('edit_payment_method').value  = method;
    document.getElementById('edit_reference_number').value = reference || '';
    document.getElementById('edit_notes').value           = notes || '';
    filterEditAccounts();
    document.getElementById('edit_payment_account_id').value = accountId;
    feather.replace();
}

function filterEditAccounts() {
    const method = document.getElementById('edit_payment_method').value;
    const sel    = document.getElementById('edit_payment_account_id');
    const opts   = sel.querySelectorAll('option');
    let has = false;
    opts.forEach(o => {
        if (!o.value) return;
        const show = o.dataset.type === method;
        o.style.display = show ? 'block' : 'none';
        if (show) has = true;
    });
    sel.options[0].text = has ? 'Select Account' : 'No accounts for this method';
    feather.replace();
}

// ── Init ──
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    // Auto-enter edit mode when ?edit=1 is in the URL
    if (new URLSearchParams(location.search).get('edit') === '1') {
        enterEditMode();
    }
});

// ── Inline Edit ──────────────────────────────────────────────
const pkPage = document.getElementById('pkPage');

function enterEditMode() {
    pkPage.classList.add('edit-mode');
    feather.replace();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function exitEditMode() {
    pkPage.classList.remove('edit-mode');
    feather.replace();
}

function buildEditShortPlan() {
    const ci    = document.querySelector('[name="booking_start_date"]')?.value;
    const co    = document.querySelector('[name="booking_end_date"]')?.value;
    const prop  = document.querySelector('[name="stay_property"]')?.value?.trim();
    const rooms = document.querySelector('[name="stay_rooms"]')?.value;
    const rtype = document.querySelector('[name="stay_room_type"]')?.value?.trim();
    const adults= parseInt(document.querySelector('[name="stay_adults"]')?.value || 0);
    const kids  = parseInt(document.querySelector('[name="stay_children"]')?.value || 0);
    const meal  = document.querySelector('[name="stay_meal"]')?.value;
    const req   = document.querySelector('[name="stay_requests"]')?.value?.trim();

    const fmt = d => { if (!d) return ''; const dt = new Date(d); return dt.toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}); };
    let nights = 0;
    if (ci && co) { nights = Math.round((new Date(co) - new Date(ci)) / 86400000); }

    const lines = [];
    if (prop)  lines.push('Property: ' + prop);
    if (ci && co) lines.push('Check-in: ' + fmt(ci) + '  →  Check-out: ' + fmt(co));
    if (nights > 0) lines.push('Duration: ' + nights + ' night(s)');
    const roomStr = rooms + (rtype ? ' (' + rtype + ')' : '');
    if (rooms) lines.push('Rooms: ' + roomStr);
    const guestParts = [];
    if (adults > 0) guestParts.push(adults + ' Adult' + (adults > 1 ? 's' : ''));
    if (kids   > 0) guestParts.push(kids   + ' Child' + (kids   > 1 ? 'ren' : ''));
    if (guestParts.length) lines.push('Guests: ' + guestParts.join(', '));
    if (meal)  lines.push('Meal Plan: ' + meal);
    if (req)   lines.push('Requests: ' + req);

    document.getElementById('editShortPlan').value = lines.join(' | ');
}

function submitEdit() {
    const isStay = {{ $isStay ? 'true' : 'false' }};
    if (isStay) buildEditShortPlan();
    document.getElementById('editBookingForm').submit();
}
</script>

@endsection

@section('script')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
