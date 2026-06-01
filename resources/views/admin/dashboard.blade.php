@extends('admin.layouts.app')
@section('content')
<style>
/* ══════════════════════════════════════════════
   HORIZON DASHBOARD — Clean Enterprise SaaS UI
   Palette: Slate bg · White cards · Indigo accent
   ══════════════════════════════════════════════ */

/* ── Tokens ─────────────────────────────────── */
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

/* ── Base ───────────────────────────────────── */
.page-content {
    background: var(--h-bg);
    min-height: 100vh;
    padding: clamp(14px, 2vw, 24px);
    box-sizing: border-box;
    max-width: 100%;
    overflow-x: hidden;
}
@media (max-width: 767px) { .page-content { padding: 12px; } }

/* ── Horizon Header ─────────────────────────── */
.hz-header {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #0EA5E9 100%);
    border-radius: 16px;
    padding: clamp(16px,2.5vw,28px) clamp(18px,3vw,32px);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}
.hz-header::before {
    content:'';
    position:absolute; inset:0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hz-header-left { position:relative; z-index:1; }
.hz-header h1 { color:#fff; font-size:clamp(1.25rem,3vw,1.75rem); font-weight:700; margin:0; }
.hz-header p  { color:rgba(255,255,255,.75); font-size:.875rem; margin:.25rem 0 0; }
.hz-header-actions { display:flex; gap:10px; flex-wrap:wrap; position:relative; z-index:1; }
.hz-header-btn {
    display:inline-flex; align-items:center; gap:7px;
    padding:9px 18px; border-radius:10px; font-size:.8rem; font-weight:600;
    text-decoration:none; transition: var(--h-t); white-space:nowrap;
    background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.25);
    backdrop-filter:blur(8px);
}
.hz-header-btn:hover { background:rgba(255,255,255,.25); color:#fff; transform:translateY(-1px); }
.hz-header-btn.primary { background:#fff; color:var(--h-indigo); border-color:#fff; }
.hz-header-btn.primary:hover { background:#f0f0ff; }
@media(max-width:600px) { .hz-header { flex-direction:column; align-items:flex-start; } }

/* ── Card base ──────────────────────────────── */
.hz-card {
    background: var(--h-card);
    border-radius: var(--h-r);
    box-shadow: var(--h-shadow);
    border: 1px solid var(--h-border);
    overflow: hidden;
    transition: box-shadow var(--h-t);
}
.hz-card:hover { box-shadow: var(--h-shadow-md); }
.hz-card-head {
    display:flex; align-items:center; justify-content:space-between;
    padding: 18px 22px 0;
    gap:12px;
}
.hz-card-title {
    display:flex; align-items:center; gap:9px;
    font-size:.9rem; font-weight:700; color: var(--h-text);
}
.hz-card-title svg, .hz-card-title i[data-feather] {
    width:17px; height:17px; stroke:var(--h-indigo); flex-shrink:0;
}
.hz-card-body { padding:18px 22px 22px; }

/* ── KPI Row ── */
.hz-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: clamp(8px, 1vw, 14px);
    margin-bottom: 20px;
}
@media(max-width:1200px) { .hz-kpi-row { grid-template-columns: repeat(3, minmax(0,1fr)); } }

/* ── KPI Card ── */
.hz-kpi {
    background: var(--h-card);
    border-radius: var(--h-r);
    box-shadow: var(--h-shadow);
    border: 1px solid var(--h-border);
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: box-shadow var(--h-t), transform var(--h-t);
    position: relative;
    overflow: hidden;
    text-decoration: none;
}
.hz-kpi::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    background: var(--kpi-color, var(--h-indigo));
    border-radius: 0 0 var(--h-r) var(--h-r);
}
.hz-kpi:hover { box-shadow: var(--h-shadow-md); transform: translateY(-2px); }
.hz-kpi-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: var(--kpi-bg, rgba(79,70,229,.1));
    margin-bottom: 4px;
}
.hz-kpi-icon svg, .hz-kpi-icon i[data-feather] {
    width: 18px; height: 18px; stroke: var(--kpi-color, var(--h-indigo));
}
.hz-kpi-value { font-size: 1.75rem; font-weight: 800; color: var(--h-text); line-height: 1; }
.hz-kpi-label { font-size: .75rem; font-weight: 600; color: var(--h-muted); text-transform: uppercase; letter-spacing: .5px; }

/* ── Mobile KPI: horizontal swipe scroll ── */
@media(max-width:640px) {
    .hz-kpi-row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 6px;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
    }
    .hz-kpi-row::-webkit-scrollbar { display: none; }

    .hz-kpi {
        flex: 0 0 130px;
        min-width: 130px;
        padding: 14px 13px 16px;
        gap: 6px;
        border-radius: 14px;
        scroll-snap-align: start;
    }
    /* Left colour bar on mobile instead of bottom bar */
    .hz-kpi::after {
        top: 0; bottom: 0; left: 0; right: auto;
        width: 4px; height: 100%;
        border-radius: var(--h-r) 0 0 var(--h-r);
    }
    .hz-kpi-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        margin-bottom: 2px;
    }
    .hz-kpi-icon svg, .hz-kpi-icon i[data-feather] { width: 16px; height: 16px; }
    .hz-kpi-value { font-size: 1.5rem; }
    .hz-kpi-label { font-size: .62rem; letter-spacing: .3px; }
}

/* ── Metrics Row ────────────────────────────── */
.hz-metrics { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:clamp(8px,1vw,14px); margin-bottom:20px; }
@media(max-width:1000px) { .hz-metrics { grid-template-columns:repeat(2,minmax(0,1fr)); } }
@media(max-width:480px)  { .hz-metrics { grid-template-columns:1fr; } }

.hz-metric {
    background:var(--h-card); border-radius:var(--h-r);
    border:1px solid var(--h-border); box-shadow:var(--h-shadow);
    padding:18px 20px;
    display:flex; align-items:center; gap:16px;
}
.hz-metric-icon {
    width:48px; height:48px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    background:var(--met-bg, rgba(79,70,229,.1));
}
.hz-metric-icon svg, .hz-metric-icon i[data-feather] {
    width:20px; height:20px; stroke:var(--met-color, var(--h-indigo));
}
.hz-metric-info { flex:1; min-width:0; }
.hz-metric-label { font-size:.72rem; color:var(--h-muted); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
.hz-metric-val { font-size:1.3rem; font-weight:800; color:var(--h-text); line-height:1.3; }
.hz-metric-badge {
    display:inline-flex; align-items:center; gap:3px;
    font-size:.7rem; font-weight:700; padding:3px 8px; border-radius:20px;
    margin-top:4px;
}
.hz-metric-badge.up   { background:#d1fae5; color:#065f46; }
.hz-metric-badge.down { background:#fee2e2; color:#991b1b; }
.hz-metric-badge svg, .hz-metric-badge i[data-feather] { width:11px; height:11px; }

/* ── Two-col grid ───────────────────────────── */
.hz-grid-2 { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:clamp(10px,1.2vw,16px); margin-bottom:20px; }
.hz-grid-3 { display:grid; grid-template-columns:2fr minmax(0,1fr); gap:clamp(10px,1.2vw,16px); margin-bottom:20px; }
@media(max-width:1000px) { .hz-grid-2, .hz-grid-3 { grid-template-columns:1fr; } }

/* ── Target Widget ──────────────────────────── */
.hz-target-month { font-size:.72rem; color:var(--h-muted); font-weight:600; text-transform:uppercase; margin-bottom:14px; letter-spacing:.5px; }
.hz-target-row { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:10px; }
.hz-target-val { font-size:1.6rem; font-weight:800; color:var(--h-text); }
.hz-target-pct { font-size:1rem; font-weight:700; }
.hz-progress-track { background:#F1F5F9; border-radius:99px; height:10px; overflow:hidden; margin-bottom:10px; }
.hz-progress-fill  { height:100%; border-radius:99px; transition:width .6s cubic-bezier(.4,0,.2,1); }
.hz-target-stats { display:flex; gap:12px; }
.hz-tstat { flex:1; background:#F8FAFC; border-radius:10px; padding:10px 12px; }
.hz-tstat-label { font-size:.68rem; color:var(--h-muted); font-weight:600; text-transform:uppercase; }
.hz-tstat-val   { font-size:1rem; font-weight:700; color:var(--h-text); margin-top:2px; }
.hz-target-note { margin-top:12px; font-size:.78rem; font-weight:600; padding:8px 12px; border-radius:8px; display:flex; align-items:center; gap:6px; }
.hz-target-note.info { background:#EEF2FF; color:var(--h-indigo); }
.hz-target-note.success { background:#D1FAE5; color:#065F46; }
.hz-target-note svg, .hz-target-note i[data-feather] { width:14px; height:14px; flex-shrink:0; }

/* ── Upcoming services ──────────────────────── */
.hz-svc-date { display:flex; align-items:center; gap:10px; padding:10px 0 6px; }
.hz-svc-date-badge {
    min-width:38px; height:38px; border-radius:10px;
    background:var(--h-indigo); color:#fff;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    font-size:.62rem; font-weight:700; line-height:1.1; text-transform:uppercase;
}
.hz-svc-date-badge b { font-size:1rem; font-weight:800; }
.hz-svc-date-label { font-size:.83rem; font-weight:700; color:var(--h-text); }
.hz-svc-date-sub   { font-size:.72rem; color:var(--h-muted); }

.hz-svc-item {
    display:flex; align-items:flex-start; gap:12px;
    padding:12px 0; border-bottom:1px solid var(--h-border);
}
.hz-svc-item:last-child { border-bottom:none; }
.hz-svc-dot { width:8px; height:8px; border-radius:50%; margin-top:6px; flex-shrink:0; }
.hz-svc-name { font-size:.85rem; font-weight:700; color:var(--h-text); }
.hz-svc-meta { font-size:.72rem; color:var(--h-muted); margin-top:2px; display:flex; gap:8px; flex-wrap:wrap; }
.hz-svc-tag { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:20px; font-size:.7rem; font-weight:600; }
.hz-svc-actions { margin-left:auto; display:flex; gap:6px; }
.hz-link-btn {
    display:inline-flex; align-items:center; gap:5px;
    padding:5px 11px; border-radius:7px; font-size:.75rem; font-weight:600;
    text-decoration:none; transition:var(--h-t); border:1px solid var(--h-border);
    background:#fff; color:var(--h-sub);
}
.hz-link-btn:hover { background:var(--h-indigo); color:#fff; border-color:var(--h-indigo); }

/* ── Chart card ─────────────────────────────── */
.hz-chart-wrap { padding:20px 22px; }
.hz-chart-wrap canvas { max-height:260px; }
.hz-chart-tabs { display:flex; gap:4px; margin-bottom:16px; }
.hz-chart-tab {
    padding:5px 14px; border-radius:8px; font-size:.75rem; font-weight:600;
    cursor:pointer; border:1px solid transparent; transition:var(--h-t);
    color:var(--h-muted);
}
.hz-chart-tab.active { background:var(--h-indigo); color:#fff; }
.hz-chart-tab:not(.active):hover { background:#F1F5F9; color:var(--h-text); }

/* ── Activity feed ──────────────────────────── */
.hz-activity { padding:0; max-height:340px; overflow-y:auto; }
.hz-activity::-webkit-scrollbar { width:4px; }
.hz-activity::-webkit-scrollbar-thumb { background:#E2E8F0; border-radius:4px; }

.hz-act-item { display:flex; gap:12px; padding:12px 22px; transition:background var(--h-t); }
.hz-act-item:hover { background:#F8FAFC; }
.hz-act-line { display:flex; flex-direction:column; align-items:center; }
.hz-act-dot {
    width:32px; height:32px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
}
.hz-act-dot svg, .hz-act-dot i[data-feather] { width:14px; height:14px; }
.hz-act-connector { width:2px; flex:1; min-height:12px; background:var(--h-border); margin-top:4px; }
.hz-act-body { flex:1; padding-top:4px; }
.hz-act-title { font-size:.82rem; font-weight:600; color:var(--h-text); }
.hz-act-desc  { font-size:.75rem; color:var(--h-muted); margin-top:2px; }
.hz-act-time  { font-size:.68rem; color:var(--h-muted); margin-top:4px; display:flex; align-items:center; gap:4px; }
.hz-act-time svg, .hz-act-time i[data-feather] { width:11px; height:11px; }

/* ── Mini charts row ────────────────────────── */
.hz-charts-4 { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:clamp(8px,1vw,14px); }
.hz-mini-chart { background:var(--h-card); border-radius:var(--h-r); border:1px solid var(--h-border); box-shadow:var(--h-shadow); padding:18px; min-width:0; }
.hz-mini-chart-title { font-size:.8rem; font-weight:700; color:var(--h-text); display:flex; align-items:center; gap:7px; margin-bottom:14px; }
.hz-mini-chart-title svg, .hz-mini-chart-title i[data-feather] { width:15px; height:15px; stroke:var(--h-indigo); flex-shrink:0; }
.hz-mini-chart canvas { max-height:180px; width:100% !important; }
/* Mobile horizontal scroll wrapper */
.hz-charts-4-scroll { display:none; }
@media(max-width:767px) {
  .hz-charts-4 { display:none !important; }
  .hz-charts-4-scroll {
    display:flex;
    gap:12px;
    overflow-x:auto;
    padding-bottom:10px;
    scroll-snap-type:x mandatory;
    -webkit-overflow-scrolling:touch;
    margin-bottom:20px;
  }
  .hz-charts-4-scroll::-webkit-scrollbar { height:4px; }
  .hz-charts-4-scroll::-webkit-scrollbar-track { background:#f0f0f0; border-radius:2px; }
  .hz-charts-4-scroll::-webkit-scrollbar-thumb { background:var(--h-indigo); border-radius:2px; }
  .hz-charts-4-scroll .hz-mini-chart {
    flex:0 0 75vw;
    max-width:260px;
    scroll-snap-align:start;
    padding:16px;
  }
  .hz-charts-4-scroll .hz-mini-chart canvas { max-height:160px; width:100% !important; }
  .hz-charts-4-scroll .hz-mini-chart-title { font-size:.78rem; margin-bottom:12px; }
}

/* ── Summary strip ──────────────────────────── */
.hz-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:clamp(8px,1vw,12px); margin-bottom:20px; }
@media(max-width:900px) { .hz-summary { grid-template-columns:repeat(2,minmax(0,1fr)); } }

.hz-sum-item { display:flex; align-items:center; gap:12px; background:var(--h-card); border-radius:var(--h-r); border:1px solid var(--h-border); padding:14px 16px; box-shadow:var(--h-shadow); }
.hz-sum-icon { width:40px; height:40px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
.hz-sum-icon svg, .hz-sum-icon i[data-feather] { width:18px; height:18px; }
.hz-sum-label { font-size:.7rem; color:var(--h-muted); font-weight:600; text-transform:uppercase; }
.hz-sum-val   { font-size:1.1rem; font-weight:800; color:var(--h-text); }

/* ── Team table ─────────────────────────────── */
.hz-team-head { display:flex; align-items:center; gap:12px; padding:18px 22px 14px; border-bottom:1px solid var(--h-border); flex-wrap:wrap; }
.hz-team-head h6 { font-size:.9rem; font-weight:700; color:var(--h-text); margin:0; flex:1; }
.hz-table { width:100%; border-collapse:collapse; }
.hz-table th { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--h-muted); padding:10px 14px; background:#F8FAFC; border-bottom:1px solid var(--h-border); white-space:nowrap; }
.hz-table td { padding:13px 14px; font-size:.82rem; color:var(--h-text); border-bottom:1px solid #F1F5F9; vertical-align:middle; }
.hz-table tr:hover td { background:#FAFBFF; }
.hz-table tr:last-child td { border-bottom:none; }
.hz-table tfoot td { background:#F8FAFC; font-weight:700; font-size:.82rem; border-top:2px solid var(--h-border); }

/* Avatar */
.hz-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--h-indigo),var(--h-violet)); color:#fff; font-size:.78rem; font-weight:700; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 2px 6px rgba(99,102,241,.30); }
.hz-lead-avatar { width:28px; height:28px; border-radius:8px; background:#EEF2FF; color:var(--h-indigo); display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; }
.hz-lead-avatar svg, .hz-lead-avatar i[data-feather] { width:13px; height:13px; }

/* ── Dual-color progress bar ─────────────────── */
.hz-pbar {
    position:relative;
    background:#EEF2FF;
    border-radius:99px;
    height:10px;
    overflow:hidden;
    width:100%;
    min-width:120px;
}
/* Achieved fill (foreground) */
.hz-pbar-fill {
    height:100%;
    border-radius:99px;
    position:relative;
    z-index:2;
    transition:width .6s ease;
}
/* Target marker line */
.hz-pbar-target {
    position:absolute;
    top:0; bottom:0;
    width:3px;
    background:rgba(99,102,241,.55);
    border-radius:2px;
    z-index:3;
}
/* Labels under bar */
.hz-pbar-labels {
    display:flex;
    justify-content:space-between;
    margin-top:4px;
    font-size:.64rem;
    font-weight:600;
    color:var(--h-muted);
}
.hz-pbar-labels .lbl-achieved { color:var(--h-emerald); }
.hz-pbar-labels .lbl-target   { color:var(--h-indigo); }

/* Legend dots */
.hz-pbar-legend {
    display:flex;
    align-items:center;
    gap:14px;
    padding:10px 14px 14px;
    font-size:.7rem;
    font-weight:600;
    color:var(--h-muted);
}
.hz-pbar-legend span { display:inline-flex; align-items:center; gap:5px; }
.hz-pbar-legend .dot { width:10px; height:10px; border-radius:3px; display:inline-block; }
.hz-pbar-legend .dot-achieved { background:linear-gradient(90deg,#10B981,#34D399); }
.hz-pbar-legend .dot-overachieved { background:linear-gradient(90deg,#F59E0B,#FBBF24); }
.hz-pbar-legend .dot-target { background:#C7D2FE; border:2px solid #6366F1; }

/* ── Status badges ──────────────────────────── */
.hz-badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 10px; border-radius:20px; font-size:.7rem; font-weight:700;
}
.hz-badge svg, .hz-badge i[data-feather] { width:11px; height:11px; }
.hz-badge.complete  { background:#D1FAE5; color:#065F46; }
.hz-badge.confirm   { background:#DBEAFE; color:#1E40AF; }
.hz-badge.followup  { background:#FEF3C7; color:#92400E; }
.hz-badge.cancel    { background:#FEE2E2; color:#991B1B; }
.hz-badge.new       { background:#EEF2FF; color:#3730A3; }
.hz-badge.pending   { background:#F1F5F9; color:#475569; }

/* ── Today bar ──────────────────────────────── */
.hz-today-tag { display:inline-flex; align-items:center; gap:5px; font-size:.7rem; font-weight:700; padding:3px 10px; border-radius:20px; }
.hz-today-tag.today    { background:rgba(239,68,68,.12); color:var(--h-rose); }
.hz-today-tag.tomorrow { background:rgba(245,158,11,.12); color:var(--h-amber); }

/* ── Empty state ────────────────────────────── */
.hz-empty { text-align:center; padding:40px 20px; }
.hz-empty-icon { width:56px; height:56px; background:#F1F5F9; border-radius:16px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:12px; }
.hz-empty-icon svg, .hz-empty-icon i[data-feather] { width:24px; height:24px; stroke:var(--h-muted); }
.hz-empty h6 { font-size:.9rem; font-weight:700; color:var(--h-sub); margin-bottom:4px; }
.hz-empty p  { font-size:.78rem; color:var(--h-muted); margin:0; }

/* ── FAB ─────────────────────────────────────── */
.hz-fab {
    position:fixed; bottom:28px; right:28px; z-index:100;
    width:54px; height:54px; border-radius:50%;
    background:linear-gradient(135deg,var(--h-indigo),var(--h-violet));
    color:#fff; box-shadow:0 8px 24px rgba(79,70,229,.4);
    display:flex; align-items:center; justify-content:center;
    text-decoration:none; transition:transform var(--h-t), box-shadow var(--h-t);
}
.hz-fab:hover { transform:scale(1.1); box-shadow:0 12px 32px rgba(79,70,229,.5); color:#fff; }
.hz-fab svg, .hz-fab i[data-feather] { width:22px; height:22px; }
@media(max-width:991px) { .hz-fab { bottom:76px; } }

/* ── Section label ──────────────────────────── */
.hz-section-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--h-muted); margin-bottom:10px; margin-top:8px; }

/* ── Animate ────────────────────────────────── */
@keyframes hzFadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
.hz-fade { animation:hzFadeUp .4s ease both; }
.hz-fade-1 { animation-delay:.05s; }
.hz-fade-2 { animation-delay:.10s; }
.hz-fade-3 { animation-delay:.15s; }
.hz-fade-4 { animation-delay:.20s; }
.hz-fade-5 { animation-delay:.25s; }

/* ══════════════════════════════════════════════
   MOBILE RESPONSIVE IMPROVEMENTS
   ══════════════════════════════════════════════ */

/* Hide specific columns on mobile */
@media(max-width:767px) { .hz-hide-mob { display:none !important; } }

/* Header: tighter */
@media(max-width:580px) {
  .hz-header { padding:18px 16px; border-radius:16px; margin-bottom:16px; }
  .hz-header h1 { font-size:1.1rem; }
  .hz-header p  { font-size:.76rem; }
  .hz-header-btn {
    padding: 5px 5px;
    font-size: 11px;
    gap: 5px;
    border-radius: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 110px;
  }
  .hz-header-actions { gap: 6px; flex-wrap: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
  .hz-header-actions::-webkit-scrollbar { display: none; }
}


/* Cards: tighter padding */
@media(max-width:600px) {
  .hz-card-head { padding:14px 14px 0; flex-wrap:wrap; gap:8px; }
  .hz-card-body { padding:12px 14px 16px; }
  .hz-chart-wrap { padding:12px 14px; }
  .hz-team-head { padding:12px 14px; flex-wrap:wrap; gap:8px; }
}

/* Target cards */
@media(max-width:600px) {
  .hz-target-val { font-size:1.3rem; }
  .hz-target-stats { gap:8px; }
  .hz-tstat { padding:8px 9px; }
  .hz-tstat-val { font-size:.9rem; }
  .hz-target-note { font-size:.73rem; padding:7px 10px; }
}

/* Mini charts: desktop responsive breakpoints */
@media(max-width:1100px) {
  .hz-charts-4 { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:640px) {
  .hz-chart-wrap canvas { max-height:220px; }
}

/* Upcoming services: actions stack below content */
@media(max-width:600px) {
  .hz-svc-item { flex-wrap:wrap; }
  .hz-svc-actions { margin-left:0 !important; flex-wrap:wrap; }
  .hz-svc-meta { gap:4px 8px; }
  .hz-svc-date-badge { min-width:34px; height:34px; }
}

/* Summary strip */
@media(max-width:480px) {
  .hz-sum-item { padding:11px 12px; gap:9px; }
  .hz-sum-icon { width:34px; height:34px; border-radius:8px; }
  .hz-sum-icon svg,.hz-sum-icon i[data-feather] { width:15px; height:15px; }
  .hz-sum-val { font-size:.98rem; }
  .hz-sum-label { font-size:.63rem; }
}

/* Tables: tighter cells */
@media(max-width:640px) {
  .hz-table th { padding:8px 9px; font-size:.62rem; }
  .hz-table td { padding:9px 9px; font-size:.78rem; }
  .hz-table .hz-avatar { width:28px; height:28px; font-size:.68rem; }
  .hz-table .hz-lead-avatar { width:24px; height:24px; }
}

/* Pbar: no min-width on mobile */
@media(max-width:640px) { .hz-pbar { min-width:60px; } }

/* Activity feed */
@media(max-width:600px) {
  .hz-act-item { padding:10px 14px; gap:9px; }
  .hz-act-title { font-size:.8rem; }
  .hz-act-desc  { font-size:.71rem; }
  .hz-act-dot { width:28px; height:28px; }
}

/* FAB: higher + smaller on tiny screens */
@media(max-width:480px) {
  .hz-fab { bottom:72px; right:14px; width:48px; height:48px; }
  .hz-fab svg,.hz-fab i[data-feather] { width:19px; height:19px; }
}
</style>

<div class="page-content">

    {{-- ── HEADER ──────────────────────────────────────────── --}}
    <div class="hz-header hz-fade">
        <div class="hz-header-left">
            <h1>
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                @endphp
                {{ $greeting }}, {{ auth()->user()->name ?? 'Admin' }} 👋
            </h1>
            <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Visit Kashi Admin Dashboard</p>
        </div>
        <div class="hz-header-actions">
            @can('booking-create')
            <a href="{{ route('bookings.create-direct') }}" class="hz-header-btn primary">
                <i data-feather="plus-circle"></i> New Booking
            </a>
            @endcan
            @can('booking-list')
            <a href="{{ route('bookings.index') }}" class="hz-header-btn">
                <i data-feather="calendar"></i> Bookings
            </a>
            @endcan
            @can('payment-create')
            <a href="{{ route('payment-analytics.index') }}" class="hz-header-btn">
                <i data-feather="credit-card"></i> Payments
            </a>
            @endcan
        </div>
    </div>

    {{-- ── KPI ROW ───────────────────────────────────────── --}}
    @canany(['lead-list','dashboard-view'])
    <div class="hz-kpi-row hz-fade hz-fade-1">
        <a href="{{ route('lead.index') }}" class="hz-kpi" style="--kpi-color:var(--h-indigo);--kpi-bg:rgba(79,70,229,.1)">
            <div class="hz-kpi-icon"><i data-feather="users"></i></div>
            <div class="hz-kpi-value">{{ $total_lead }}</div>
            <div class="hz-kpi-label">Today's Leads</div>
        </a>
        <a href="{{ route('lead.index') }}" class="hz-kpi" style="--kpi-color:var(--h-emerald);--kpi-bg:rgba(16,185,129,.1)">
            <div class="hz-kpi-icon"><i data-feather="check-circle"></i></div>
            <div class="hz-kpi-value">{{ $total_complete }}</div>
            <div class="hz-kpi-label">Completed</div>
        </a>
        <a href="{{ route('lead.index') }}" class="hz-kpi" style="--kpi-color:var(--h-sky);--kpi-bg:rgba(14,165,233,.1)">
            <div class="hz-kpi-icon"><i data-feather="check"></i></div>
            <div class="hz-kpi-value">{{ $total_confirm }}</div>
            <div class="hz-kpi-label">Confirmed</div>
        </a>
        <a href="{{ route('lead.index') }}" class="hz-kpi" style="--kpi-color:var(--h-amber);--kpi-bg:rgba(245,158,11,.1)">
            <div class="hz-kpi-icon"><i data-feather="repeat"></i></div>
            <div class="hz-kpi-value">{{ $total_follow_up }}</div>
            <div class="hz-kpi-label">Follow Up</div>
        </a>
        <a href="{{ route('lead.index') }}" class="hz-kpi" style="--kpi-color:var(--h-rose);--kpi-bg:rgba(239,68,68,.1)">
            <div class="hz-kpi-icon"><i data-feather="x-circle"></i></div>
            <div class="hz-kpi-value">{{ $total_cancel }}</div>
            <div class="hz-kpi-label">Cancelled</div>
        </a>
    </div>
    @endcanany

    {{-- ── METRICS ROW (hidden) ── --}}

    {{-- ── TARGET + UPCOMING ────────────────────────────── --}}
    @php
        $targetService = app(\App\Services\TargetCalculationService::class);
        $currentTarget = $targetService->getCurrentMonthTarget(auth('admin')->id());
        $lastTarget    = $targetService->getLastMonthTarget(auth('admin')->id());
    @endphp

    @if ($currentTarget || $lastTarget)
    <div class="hz-grid-2 hz-fade hz-fade-3">
        @if ($currentTarget)
        <div class="hz-card">
            <div class="hz-card-head">
                <div class="hz-card-title"><i data-feather="target"></i> {{ now()->format('F Y') }} Target</div>
                @if ($currentTarget->is_achieved)
                    <span class="hz-badge complete"><i data-feather="award"></i> Achieved!</span>
                @endif
            </div>
            <div class="hz-card-body">
                <div class="hz-target-month">Progress Tracker</div>
                <div class="hz-target-row">
                    <div>
                        <div style="font-size:.72rem;color:var(--h-muted);font-weight:600;">Achieved</div>
                        <div class="hz-target-val" style="color:{{ $currentTarget->is_achieved ? 'var(--h-emerald)' : 'var(--h-indigo)' }}">
                            ₹{{ number_format($currentTarget->achieved_margin, 0) }}
                        </div>
                    </div>
                    <div class="hz-target-pct" style="color:{{ $currentTarget->is_achieved ? 'var(--h-emerald)' : 'var(--h-indigo)' }}">
                        {{ $currentTarget->achievement_percentage }}%
                    </div>
                </div>
                <div class="hz-progress-track">
                    <div class="hz-progress-fill" style="width:{{ min(100, $currentTarget->achievement_percentage) }}%; background:{{ $currentTarget->is_achieved ? 'var(--h-emerald)' : 'linear-gradient(90deg,var(--h-indigo),var(--h-violet))' }}"></div>
                </div>
                <div class="hz-target-stats">
                    <div class="hz-tstat">
                        <div class="hz-tstat-label">Target</div>
                        <div class="hz-tstat-val">₹{{ number_format($currentTarget->target_margin, 0) }}</div>
                    </div>
                    <div class="hz-tstat">
                        <div class="hz-tstat-label">{{ $currentTarget->is_achieved ? 'Exceeded by' : 'Remaining' }}</div>
                        <div class="hz-tstat-val" style="color:{{ $currentTarget->is_achieved ? 'var(--h-emerald)' : 'var(--h-rose)' }}">
                            ₹{{ number_format(abs($currentTarget->is_achieved ? $currentTarget->achieved_margin - $currentTarget->target_margin : $currentTarget->remaining_margin), 0) }}
                        </div>
                    </div>
                </div>
                @if (!$currentTarget->is_achieved)
                    <div class="hz-target-note info"><i data-feather="info"></i> ₹{{ number_format($currentTarget->remaining_margin, 0) }} more to hit this month's target</div>
                @else
                    <div class="hz-target-note success"><i data-feather="award"></i> Exceeded target by ₹{{ number_format($currentTarget->achieved_margin - $currentTarget->target_margin, 0) }}</div>
                @endif
            </div>
        </div>
        @endif

        @if ($lastTarget)
        <div class="hz-card">
            <div class="hz-card-head">
                <div class="hz-card-title"><i data-feather="clock"></i> {{ now()->subMonth()->format('F Y') }} Performance</div>
                @if ($lastTarget->is_achieved)
                    <span class="hz-badge complete"><i data-feather="star"></i> Hit!</span>
                @else
                    <span class="hz-badge cancel">{{ $lastTarget->achievement_percentage }}%</span>
                @endif
            </div>
            <div class="hz-card-body">
                <div class="hz-target-month">Last Month Review</div>
                <div class="hz-target-row">
                    <div>
                        <div style="font-size:.72rem;color:var(--h-muted);font-weight:600;">Achieved</div>
                        <div class="hz-target-val" style="color:{{ $lastTarget->is_achieved ? 'var(--h-emerald)' : 'var(--h-sub)' }}">
                            ₹{{ number_format($lastTarget->achieved_margin, 0) }}
                        </div>
                    </div>
                    <div class="hz-target-pct" style="color:{{ $lastTarget->is_achieved ? 'var(--h-emerald)' : 'var(--h-amber)' }}">
                        {{ $lastTarget->achievement_percentage }}%
                    </div>
                </div>
                <div class="hz-progress-track">
                    <div class="hz-progress-fill" style="width:{{ min(100, $lastTarget->achievement_percentage) }}%; background:{{ $lastTarget->is_achieved ? 'var(--h-emerald)' : 'var(--h-amber)' }}"></div>
                </div>
                <div class="hz-target-stats">
                    <div class="hz-tstat">
                        <div class="hz-tstat-label">Target</div>
                        <div class="hz-tstat-val">₹{{ number_format($lastTarget->target_margin, 0) }}</div>
                    </div>
                    <div class="hz-tstat">
                        <div class="hz-tstat-label">{{ $lastTarget->is_achieved ? 'Exceeded' : 'Shortfall' }}</div>
                        <div class="hz-tstat-val" style="color:{{ $lastTarget->is_achieved ? 'var(--h-emerald)' : 'var(--h-rose)' }}">
                            {{ $lastTarget->is_achieved ? '+' : '-' }}{{ number_format(abs($lastTarget->achievement_percentage - 100), 1) }}%
                        </div>
                    </div>
                </div>
                @if ($lastTarget->is_achieved)
                    <div class="hz-target-note success"><i data-feather="award"></i> Exceeded by {{ number_format($lastTarget->achievement_percentage - 100, 1) }}% above target</div>
                @else
                    <div class="hz-target-note info"><i data-feather="activity"></i> Achieved {{ $lastTarget->achievement_percentage }}% of last month's target</div>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ── TEAM TARGET ────────────────────────────────────── --}}
    @canany(['dashboard-view','staff-list'])
    @php
        $targetService = app(\App\Services\TargetCalculationService::class);
        $teamTargets = $targetService->getTeamCurrentMonthTargets();
        if ($teamTargets->count() > 0) {
            $totalTeamTarget   = $teamTargets->sum('target_margin');
            $totalTeamAchieved = $teamTargets->sum('achieved_margin');
            $teamAchievementPercentage = $totalTeamTarget > 0 ? round(($totalTeamAchieved / $totalTeamTarget) * 100, 1) : 0;
            $teamIsAchieved    = $totalTeamAchieved >= $totalTeamTarget;
        }
    @endphp
    @if (isset($teamTargets) && $teamTargets->count() > 0)
    <div class="hz-card hz-fade hz-fade-3" style="margin-bottom:20px;overflow:hidden;">

        {{-- Header --}}
        <div class="hz-team-head">
            <div class="hz-card-title"><i data-feather="users"></i> Team Target — {{ now()->format('F Y') }}</div>
            @if ($teamIsAchieved)
                <span class="hz-badge complete"><i data-feather="award"></i> Team Achieved!</span>
            @else
                <span class="hz-badge pending"><i data-feather="activity"></i> {{ $teamAchievementPercentage }}% of target</span>
            @endif
            <div style="margin-left:auto;font-size:.75rem;font-weight:600;color:var(--h-muted);background:#F1F5F9;padding:4px 10px;border-radius:20px;">
                {{ $teamTargets->count() }} {{ Str::plural('Member', $teamTargets->count()) }}
            </div>
        </div>

        {{-- Team overall bar --}}
        <div style="padding:14px 22px 0;background:linear-gradient(to right,#F8FAFF,#FAFBFF);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <span style="font-size:.72rem;font-weight:700;color:var(--h-muted);text-transform:uppercase;letter-spacing:.5px;">Team Overall Progress</span>
                <span style="font-size:.78rem;font-weight:800;color:{{ $teamIsAchieved ? 'var(--h-emerald)' : 'var(--h-indigo)' }}">{{ $teamAchievementPercentage }}%</span>
            </div>
            <div class="hz-pbar" style="height:14px;">
                <div class="hz-pbar-fill" style="width:{{ min(100,$teamAchievementPercentage) }}%;background:{{ $teamIsAchieved ? 'linear-gradient(90deg,#10B981,#34D399)' : 'linear-gradient(90deg,#6366F1,#818CF8)' }};"></div>
                <div class="hz-pbar-target" style="left:calc(100% - 3px);"></div>
            </div>
            <div class="hz-pbar-labels">
                <span class="lbl-achieved">Achieved ₹{{ number_format($totalTeamAchieved, 0) }}</span>
                <span class="lbl-target">Target ₹{{ number_format($totalTeamTarget, 0) }}</span>
            </div>
        </div>

        {{-- Legend --}}
        <div class="hz-pbar-legend">
            <span><i class="dot dot-achieved"></i> Achieved</span>
            <span><i class="dot dot-overachieved"></i> Near Target (75%+)</span>
            <span><i class="dot dot-target"></i> Target (100%)</span>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="hz-table">
                <thead>
                    <tr>
                        <th class="hz-hide-mob">#</th>
                        <th>Member</th>
                        <th>Target</th>
                        <th>Achieved</th>
                        <th class="hz-hide-mob" style="min-width:180px;">Progress</th>
                        <th>%</th>
                        <th class="hz-hide-mob">Share</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teamTargets->sortByDesc('achieved_margin') as $i => $target)
                    @php
                        $share    = $totalTeamAchieved > 0 ? round(($target->achieved_margin / $totalTeamAchieved) * 100, 1) : 0;
                        $pct      = min(100, $target->achievement_percentage);
                        $barColor = $target->is_achieved
                            ? 'linear-gradient(90deg,#10B981,#34D399)'
                            : ($pct >= 75
                                ? 'linear-gradient(90deg,#F59E0B,#FBBF24)'
                                : 'linear-gradient(90deg,#6366F1,#818CF8)');
                        $avatarColors = [
                            'linear-gradient(135deg,#6366F1,#8B5CF6)',
                            'linear-gradient(135deg,#10B981,#059669)',
                            'linear-gradient(135deg,#F59E0B,#D97706)',
                            'linear-gradient(135deg,#EF4444,#DC2626)',
                            'linear-gradient(135deg,#3B82F6,#2563EB)',
                            'linear-gradient(135deg,#EC4899,#DB2777)',
                        ];
                        $avatarBg = $avatarColors[$i % count($avatarColors)];
                    @endphp
                    <tr>
                        <td class="hz-hide-mob" style="color:var(--h-muted);font-weight:700;font-size:.72rem;">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="hz-avatar" style="background:{{ $avatarBg }}">
                                    {{ strtoupper(substr($target->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.83rem;">{{ $target->user->name }}</div>
                                    @if($target->is_achieved)
                                    <div style="font-size:.65rem;color:var(--h-emerald);font-weight:600;">🏆 Target hit!</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="font-weight:600;">₹{{ number_format($target->target_margin, 0) }}</td>
                        <td style="font-weight:800;color:{{ $target->is_achieved ? 'var(--h-emerald)' : ($pct >= 75 ? '#D97706' : 'var(--h-text)') }}">
                            ₹{{ number_format($target->achieved_margin, 0) }}
                        </td>
                        <td class="hz-hide-mob">
                            <div class="hz-pbar" style="height:10px;">
                                <div class="hz-pbar-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
                                <div class="hz-pbar-target" style="left:calc(100% - 3px);"></div>
                            </div>
                            <div class="hz-pbar-labels">
                                <span class="lbl-achieved">{{ $pct }}%</span>
                                <span class="lbl-target">100%</span>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight:800;font-size:.88rem;color:{{ $target->is_achieved ? 'var(--h-emerald)' : ($pct >= 75 ? '#D97706' : 'var(--h-indigo)') }}">
                                {{ $target->achievement_percentage }}%
                            </span>
                        </td>
                        <td class="hz-hide-mob">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div style="background:#EEF2FF;border-radius:99px;height:5px;flex:1;min-width:40px;overflow:hidden;">
                                    <div style="height:100%;width:{{ $share }}%;background:var(--h-indigo);border-radius:99px;"></div>
                                </div>
                                <span style="font-weight:700;font-size:.78rem;color:var(--h-indigo);min-width:32px;">{{ $share }}%</span>
                            </div>
                        </td>
                        <td>
                            @if ($target->is_achieved)
                                <span class="hz-badge complete"><i data-feather="check-circle"></i> Done</span>
                            @elseif($pct >= 75)
                                <span class="hz-badge followup"><i data-feather="trending-up"></i> Near</span>
                            @else
                                <span class="hz-badge pending"><i data-feather="clock"></i> {{ $target->achievement_percentage }}%</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="font-weight:700;font-size:.82rem;">TEAM TOTAL</td>
                        <td style="font-weight:700;">₹{{ number_format($totalTeamTarget, 0) }}</td>
                        <td style="font-weight:800;color:{{ $teamIsAchieved ? 'var(--h-emerald)' : 'var(--h-text)' }}">
                            ₹{{ number_format($totalTeamAchieved, 0) }}
                        </td>
                        <td class="hz-hide-mob">
                            <div class="hz-pbar" style="height:10px;">
                                <div class="hz-pbar-fill" style="width:{{ min(100,$teamAchievementPercentage) }}%;background:{{ $teamIsAchieved ? 'linear-gradient(90deg,#10B981,#34D399)' : 'linear-gradient(90deg,#6366F1,#818CF8)' }};"></div>
                                <div class="hz-pbar-target" style="left:calc(100% - 3px);"></div>
                            </div>
                            <div class="hz-pbar-labels">
                                <span class="lbl-achieved">{{ min(100,$teamAchievementPercentage) }}%</span>
                                <span class="lbl-target">100%</span>
                            </div>
                        </td>
                        <td><span style="font-weight:800;color:{{ $teamIsAchieved ? 'var(--h-emerald)' : 'var(--h-indigo)' }}">{{ $teamAchievementPercentage }}%</span></td>
                        <td colspan="2">
                            <span class="hz-badge {{ $teamIsAchieved ? 'complete' : 'confirm' }}">
                                <i data-feather="{{ $teamIsAchieved ? 'award' : 'bar-chart-2' }}"></i>
                                {{ $teamIsAchieved ? 'Target Achieved' : 'In Progress' }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
    @endcanany

    {{-- ── MAIN CHART + ACTIVITY ────────────────────────── --}}
    @canany(['dashboard-view','analytics-customer','activity-log-view'])
    <div class="hz-grid-3 hz-fade hz-fade-3">
        <div class="hz-card">
            <div class="hz-card-head">
                <div class="hz-card-title"><i data-feather="bar-chart-2"></i> Business Analytics</div>
                <span style="font-size:.72rem;color:var(--h-muted);">Last 6 Months</span>
            </div>
            <div class="hz-chart-wrap">
                <canvas id="businessChart"></canvas>
            </div>
        </div>

        <div class="hz-card">
            <div class="hz-card-head">
                <div class="hz-card-title"><i data-feather="activity"></i> Recent Activity</div>
            </div>
            <div class="hz-activity" id="activityFeed">
                @if ($recent_activity->count() > 0)
                    @foreach ($recent_activity as $i => $activity)
                    <div class="hz-act-item">
                        <div class="hz-act-line">
                            <div class="hz-act-dot" style="background:{{ ['booking'=>'rgba(79,70,229,.12)','lead'=>'rgba(16,185,129,.12)','payment'=>'rgba(14,165,233,.12)'][$activity['type']] ?? 'rgba(148,163,184,.15)' }}">
                                <i data-feather="{{ $activity['icon'] }}" style="stroke:{{ ['booking'=>'var(--h-indigo)','lead'=>'var(--h-emerald)','payment'=>'var(--h-sky)'][$activity['type']] ?? 'var(--h-muted)' }}"></i>
                            </div>
                            @if (!$loop->last)<div class="hz-act-connector"></div>@endif
                        </div>
                        <div class="hz-act-body">
                            <div class="hz-act-title">{{ $activity['title'] }}</div>
                            <div class="hz-act-desc">{{ $activity['description'] }}</div>
                            <div class="hz-act-time"><i data-feather="clock"></i>{{ $activity['time']->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="hz-empty" style="padding:30px 20px;">
                        <div class="hz-empty-icon"><i data-feather="activity"></i></div>
                        <h6>No Recent Activity</h6>
                        <p>Activity will appear here as you work</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endcanany


    {{-- ── MINI CHARTS ─────────────────────────────────── --}}
    @canany(['dashboard-view','analytics-customer','analytics-profit'])
    <div class="hz-section-label hz-fade hz-fade-4">Analytics Overview</div>

    @php
        $totalRevenueChart  = array_sum($totalBusiness);
        $totalExpenseChart  = array_sum($totalExpense);
        $totalProfitChart   = array_sum($totalProfit);
        $profitMarginPct    = $totalRevenueChart > 0 ? round(($totalProfitChart / $totalRevenueChart) * 100, 1) : 0;
    @endphp
    <div class="hz-summary hz-fade hz-fade-4">
        <div class="hz-sum-item">
            <div class="hz-sum-icon" style="background:rgba(99,102,241,.1)"><i data-feather="dollar-sign" style="stroke:#6366F1"></i></div>
            <div>
                <div class="hz-sum-label">Total Revenue</div>
                <div class="hz-sum-val">₹{{ number_format($totalRevenueChart) }}</div>
            </div>
        </div>
        <div class="hz-sum-item">
            <div class="hz-sum-icon" style="background:rgba(239,68,68,.1)"><i data-feather="trending-down" style="stroke:var(--h-rose)"></i></div>
            <div>
                <div class="hz-sum-label">Total Expenses</div>
                <div class="hz-sum-val" style="color:var(--h-rose);">₹{{ number_format($totalExpenseChart) }}</div>
            </div>
        </div>
        <div class="hz-sum-item">
            <div class="hz-sum-icon" style="background:rgba(16,185,129,.1)"><i data-feather="trending-up" style="stroke:var(--h-emerald)"></i></div>
            <div>
                <div class="hz-sum-label">Net Profit</div>
                <div class="hz-sum-val" style="color:var(--h-emerald);">₹{{ number_format($totalProfitChart) }}</div>
            </div>
        </div>
        <div class="hz-sum-item">
            <div class="hz-sum-icon" style="background:rgba(245,158,11,.1)"><i data-feather="percent" style="stroke:var(--h-amber)"></i></div>
            <div>
                <div class="hz-sum-label">Profit Margin</div>
                <div class="hz-sum-val" style="color:var(--h-amber);">{{ $profitMarginPct }}%</div>
            </div>
        </div>
    </div>

    {{-- Desktop: 4-column grid --}}
    <div class="hz-charts-4 hz-fade hz-fade-4" style="margin-bottom:20px;">
        <div class="hz-mini-chart">
            <div class="hz-mini-chart-title"><i data-feather="pie-chart"></i> Booking Status</div>
            <canvas id="statusChart"></canvas>
        </div>
        <div class="hz-mini-chart">
            <div class="hz-mini-chart-title"><i data-feather="users"></i> Lead Sources</div>
            <canvas id="leadSourceChart"></canvas>
        </div>
        <div class="hz-mini-chart">
            <div class="hz-mini-chart-title"><i data-feather="credit-card"></i> Payment Status</div>
            <canvas id="paymentChart"></canvas>
        </div>
    </div>

    {{-- Mobile: horizontal swipe scroll --}}
    <div class="hz-charts-4-scroll hz-fade hz-fade-4">
        <div class="hz-mini-chart">
            <div class="hz-mini-chart-title"><i data-feather="pie-chart"></i> Booking Status</div>
            <canvas id="statusChartM"></canvas>
        </div>
        <div class="hz-mini-chart">
            <div class="hz-mini-chart-title"><i data-feather="users"></i> Lead Sources</div>
            <canvas id="leadSourceChartM"></canvas>
        </div>
        <div class="hz-mini-chart">
            <div class="hz-mini-chart-title"><i data-feather="credit-card"></i> Payment Status</div>
            <canvas id="paymentChartM"></canvas>
        </div>
    </div>

    <div class="hz-grid-2 hz-fade hz-fade-5" style="margin-bottom:20px;">
        <div class="hz-card">
            <div class="hz-card-head"><div class="hz-card-title"><i data-feather="trending-up"></i> Booking Trends (6 Months)</div></div>
            <div class="hz-chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="hz-card">
            <div class="hz-card-head"><div class="hz-card-title"><i data-feather="clock"></i> Daily Bookings (7 Days)</div></div>
            <div class="hz-chart-wrap"><canvas id="dailyChart"></canvas></div>
        </div>
    </div>
    @endcanany

    {{-- ── TODAY'S LEADS ────────────────────────────────── --}}
    @can('lead-list')
    <div class="hz-card hz-fade hz-fade-5" style="margin-bottom:20px;">
        <div class="hz-card-head">
            <div class="hz-card-title"><i data-feather="calendar"></i> Today's Leads <span class="hz-badge confirm ms-2">{{ $today_leads->count() }}</span></div>
            <a href="{{ route('lead.index') }}" class="hz-link-btn"><i data-feather="arrow-right" style="width:13px;height:13px;"></i>View All</a>
        </div>
        <div class="hz-card-body" style="padding:0;">
            @if ($today_leads->count() > 0)
            <div class="table-responsive">
                <table class="hz-table">
                    <thead><tr><th>Guest</th><th>Contact</th><th class="hz-hide-mob">Pax</th><th class="hz-hide-mob">Booking Date</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach ($today_leads as $lead)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="hz-lead-avatar"><i data-feather="user"></i></div>
                                    <span style="font-weight:600;">{{ $lead->guest_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>{{ $lead->contact ?? 'N/A' }}</td>
                            <td class="hz-hide-mob">{{ $lead->pax ?? '—' }}</td>
                            <td class="hz-hide-mob">
                                {{ $lead->booking_start_date ? \Carbon\Carbon::parse($lead->booking_start_date)->format('d M Y') : '—' }}
                                @if ($lead->booking_end_date)
                                    <span style="color:var(--h-muted);font-size:.72rem;display:block;">to {{ \Carbon\Carbon::parse($lead->booking_end_date)->format('d M Y') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($lead->booking_status == 'complete')   <span class="hz-badge complete"><i data-feather="check-circle"></i> Complete</span>
                                @elseif($lead->booking_status == 'confirm')  <span class="hz-badge confirm"><i data-feather="check"></i> Confirmed</span>
                                @elseif($lead->booking_status == 'follow up')<span class="hz-badge followup"><i data-feather="repeat"></i> Follow Up</span>
                                @elseif($lead->booking_status == 'cancel')   <span class="hz-badge cancel"><i data-feather="x-circle"></i> Cancelled</span>
                                @else                                         <span class="hz-badge new"><i data-feather="clock"></i> Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('lead.show', $lead->id) }}" class="hz-link-btn"><i data-feather="eye" style="width:13px;height:13px;"></i> View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="hz-empty"><div class="hz-empty-icon"><i data-feather="calendar"></i></div><h6>No Leads Today</h6><p>No leads with booking starting today</p></div>
            @endif
        </div>
    </div>
    @endcan

    {{-- ── TODAY'S ENQUIRIES ───────────────────────────── --}}
    @can('enquiry-list')
    <div class="hz-card hz-fade hz-fade-5" style="margin-bottom:20px;">
        <div class="hz-card-head">
            <div class="hz-card-title"><i data-feather="message-circle"></i> New Enquiries <span class="hz-badge confirm ms-2">{{ $today_enquiries->count() }}</span></div>
            <a href="{{ route('enquiry.index') }}" class="hz-link-btn"><i data-feather="arrow-right" style="width:13px;height:13px;"></i>View All</a>
        </div>
        <div class="hz-card-body" style="padding:0;">
            @if ($today_enquiries->count() > 0)
            <div class="table-responsive">
                <table class="hz-table">
                    <thead><tr><th>Name</th><th>Contact</th><th class="hz-hide-mob">Email</th><th class="hz-hide-mob">Message</th><th>Time</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach ($today_enquiries as $enquiry)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="hz-lead-avatar"><i data-feather="user"></i></div>
                                    <span style="font-weight:600;">{{ $enquiry->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>{{ $enquiry->phone ?? '—' }}</td>
                            <td class="hz-hide-mob" style="color:var(--h-sky);">{{ $enquiry->email ?? '—' }}</td>
                            <td class="hz-hide-mob" style="color:var(--h-sub);max-width:200px;">{{ Str::limit($enquiry->message ?? '—', 45) }}</td>
                            <td style="color:var(--h-muted);font-size:.75rem;">{{ $enquiry->created_at->format('h:i A') }}</td>
                            <td><span class="hz-badge new"><i data-feather="star"></i> New</span></td>
                            <td>
                                <button type="button" class="hz-link-btn"
                                    onclick="hzEnqView({{ json_encode(['name'=>$enquiry->name,'phone'=>$enquiry->phone,'email'=>$enquiry->email,'message'=>$enquiry->message,'time'=>$enquiry->created_at->format('d M Y h:i A')]) }})"
                                    style="border:none;background:none;cursor:pointer;">
                                    <i data-feather="eye" style="width:13px;height:13px;"></i> View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="hz-empty"><div class="hz-empty-icon"><i data-feather="inbox"></i></div><h6>No Enquiries Today</h6><p>No new enquiries for today</p></div>
            @endif
        </div>
    </div>

    {{-- Enquiry Quick-View Modal --}}
    <div id="hzEnqModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:16px;padding:24px;width:90%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,.25);position:relative;">
            <button onclick="document.getElementById('hzEnqModal').style.display='none'" style="position:absolute;top:14px;right:14px;border:none;background:#F1F5F9;border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:16px;color:#64748B;">✕</button>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#4F46E5,#7C3AED);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-feather="message-circle" style="width:16px;height:16px;stroke:#fff;"></i>
                </div>
                <div>
                    <div style="font-size:.9rem;font-weight:800;color:#0F172A;">Enquiry Details</div>
                    <div style="font-size:.72rem;color:#94A3B8;" id="hzEnqTime"></div>
                </div>
            </div>
            <div style="display:grid;gap:10px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div style="background:#F8FAFC;border-radius:10px;padding:10px 13px;">
                        <div style="font-size:.65rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;">Name</div>
                        <div style="font-size:.85rem;font-weight:700;color:#0F172A;" id="hzEnqName">—</div>
                    </div>
                    <div style="background:#F8FAFC;border-radius:10px;padding:10px 13px;">
                        <div style="font-size:.65rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;">Phone</div>
                        <div style="font-size:.85rem;font-weight:700;color:#0F172A;" id="hzEnqPhone">—</div>
                    </div>
                </div>
                <div style="background:#F8FAFC;border-radius:10px;padding:10px 13px;">
                    <div style="font-size:.65rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;">Email</div>
                    <div style="font-size:.85rem;color:#4F46E5;" id="hzEnqEmail">—</div>
                </div>
                <div style="background:#F8FAFC;border-radius:10px;padding:10px 13px;">
                    <div style="font-size:.65rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;">Message</div>
                    <div style="font-size:.85rem;color:#374151;line-height:1.6;white-space:pre-wrap;" id="hzEnqMessage">—</div>
                </div>
            </div>
            <div style="margin-top:16px;display:flex;gap:8px;">
                <a href="{{ route('enquiry.index') }}" class="hz-link-btn" style="flex:1;justify-content:center;">
                    <i data-feather="list" style="width:13px;height:13px;"></i> All Enquiries
                </a>
                <button onclick="document.getElementById('hzEnqModal').style.display='none'" style="flex:1;height:36px;border:1.5px solid #E2E8F0;border-radius:9px;background:#F1F5F9;color:#64748B;font-size:.82rem;font-weight:700;cursor:pointer;">Close</button>
            </div>
        </div>
    </div>
    <script>
    function hzEnqView(data) {
        document.getElementById('hzEnqName').textContent    = data.name    || '—';
        document.getElementById('hzEnqPhone').textContent   = data.phone   || '—';
        document.getElementById('hzEnqEmail').textContent   = data.email   || '—';
        document.getElementById('hzEnqMessage').textContent = data.message || '—';
        document.getElementById('hzEnqTime').textContent    = data.time    || '';
        var m = document.getElementById('hzEnqModal');
        m.style.display = 'flex';
        if (typeof feather !== 'undefined') feather.replace();
    }
    document.getElementById('hzEnqModal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
    </script>
    @endcan

</div>{{-- /.page-content --}}


{{-- ── CHARTS ────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const HZ_PALETTE = ['#4F46E5','#10B981','#0EA5E9','#F59E0B','#EF4444','#7C3AED','#06B6D4'];

const chartDefaults = {
    responsive: true, maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: 'rgba(15,23,42,.9)', padding: 10, cornerRadius: 8,
            titleFont: { size: 13, weight: '600' }, bodyFont: { size: 12 }
        }
    },
    scales: {
        y: { beginAtZero: true, grid: { color: 'rgba(15,23,42,.06)', drawBorder: false }, ticks: { font: { size: 11 } } },
        x: { grid: { display: false, drawBorder: false }, ticks: { font: { size: 11 } } }
    }
};

// Business Analytics chart
const bCtx = document.getElementById('businessChart');
if (bCtx) new Chart(bCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_map(fn($m) => \Carbon\Carbon::parse($m)->format('M Y'), $months)) !!},
        datasets: [
            {
                label: 'Revenue',
                data: {!! json_encode($totalBusiness) !!},
                backgroundColor: 'rgba(99,102,241,.85)',
                borderRadius: 6, borderSkipped: false, order: 1
            },
            {
                label: 'Expenses',
                data: {!! json_encode($totalExpense) !!},
                backgroundColor: 'rgba(239,68,68,.65)',
                borderRadius: 6, borderSkipped: false, order: 2
            },
            {
                label: 'Net Profit',
                data: {!! json_encode($totalProfit) !!},
                type: 'line',
                borderColor: '#10B981',
                backgroundColor: 'rgba(16,185,129,.12)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#10B981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                order: 0
            }
        ]
    },
    options: {
        ...chartDefaults,
        plugins: {
            ...chartDefaults.plugins,
            legend: {
                display: true,
                position: 'top',
                labels: { usePointStyle: true, padding: 18, font: { size: 12, weight: '600' } }
            },
            tooltip: {
                ...chartDefaults.plugins.tooltip,
                callbacks: {
                    label: c => ' ' + c.dataset.label + ': ₹' + Math.abs(c.parsed.y).toLocaleString('en-IN')
                }
            }
        },
        scales: {
            ...chartDefaults.scales,
            y: {
                ...chartDefaults.scales.y,
                ticks: {
                    callback: v => '₹' + (Math.abs(v) >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K'),
                    font: { size: 11 },
                    maxTicksLimit: 8
                }
            }
        }
    }
});

// Status doughnut
const sCtx = document.getElementById('statusChart');
if (sCtx) new Chart(sCtx, {
    type: 'doughnut',
    data: { labels: {!! json_encode($status_labels) !!}, datasets: [{ data: {!! json_encode($status_data) !!}, backgroundColor: HZ_PALETTE, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 11 } } }, tooltip: chartDefaults.plugins.tooltip }, cutout: '60%' }
});

// Lead source bar
const lCtx = document.getElementById('leadSourceChart');
if (lCtx) new Chart(lCtx, {
    type: 'bar',
    data: { labels: {!! json_encode($lead_source_labels) !!}, datasets: [{ label: 'Leads', data: {!! json_encode($lead_source_data) !!}, backgroundColor: HZ_PALETTE.map(c => c + 'CC'), borderRadius: 6 }] },
    options: { ...chartDefaults, scales: { ...chartDefaults.scales, y: { ...chartDefaults.scales.y, ticks: { font:{ size:10 }, maxTicksLimit:8, precision:0 } }, x: { ...chartDefaults.scales.x, ticks: { font:{ size:10 } } } } }
});

// Payment pie
const pCtx = document.getElementById('paymentChart');
if (pCtx) new Chart(pCtx, {
    type: 'pie',
    data: { labels: {!! json_encode($payment_labels) !!}, datasets: [{ data: {!! json_encode($payment_data) !!}, backgroundColor: ['#10B981','#F59E0B','#EF4444','#0EA5E9'], borderWidth: 2, borderColor: '#fff', hoverOffset: 5 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { padding: 12, font:{ size:11 } } }, tooltip: chartDefaults.plugins.tooltip } }
});

// Mobile duplicates (shown only on ≤767px via CSS)
const sMCtx = document.getElementById('statusChartM');
if (sMCtx) new Chart(sMCtx, {
    type: 'doughnut',
    data: { labels: {!! json_encode($status_labels) !!}, datasets: [{ data: {!! json_encode($status_data) !!}, backgroundColor: HZ_PALETTE, borderWidth: 2, borderColor: '#fff', hoverOffset: 4 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { padding: 10, font: { size: 10 } } }, tooltip: chartDefaults.plugins.tooltip }, cutout: '58%' }
});
const lMCtx = document.getElementById('leadSourceChartM');
if (lMCtx) new Chart(lMCtx, {
    type: 'bar',
    data: { labels: {!! json_encode($lead_source_labels) !!}, datasets: [{ label: 'Leads', data: {!! json_encode($lead_source_data) !!}, backgroundColor: HZ_PALETTE.map(c => c + 'CC'), borderRadius: 5 }] },
    options: { ...chartDefaults, scales: { ...chartDefaults.scales, y: { ...chartDefaults.scales.y, ticks: { font:{size:9}, maxTicksLimit:8, precision:0 } }, x: { ...chartDefaults.scales.x, ticks: { font:{size:9} } } } }
});
const pMCtx = document.getElementById('paymentChartM');
if (pMCtx) new Chart(pMCtx, {
    type: 'pie',
    data: { labels: {!! json_encode($payment_labels) !!}, datasets: [{ data: {!! json_encode($payment_data) !!}, backgroundColor: ['#10B981','#F59E0B','#EF4444','#0EA5E9'], borderWidth: 2, borderColor: '#fff', hoverOffset: 4 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { padding: 10, font:{size:10} } }, tooltip: chartDefaults.plugins.tooltip } }
});

// Trend line
const tCtx = document.getElementById('trendChart');
if (tCtx) new Chart(tCtx, {
    type: 'line',
    data: { labels: {!! json_encode($months) !!}, datasets: [{ label: 'Bookings', data: {!! json_encode($booking_trends) !!}, borderColor: '#0EA5E9', backgroundColor: 'rgba(14,165,233,.12)', borderWidth: 2.5, fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#0EA5E9', pointBorderColor: '#fff', pointBorderWidth: 2 }] },
    options: { ...chartDefaults }
});

// Daily line
const dCtx = document.getElementById('dailyChart');
if (dCtx) new Chart(dCtx, {
    type: 'line',
    data: { labels: {!! json_encode($daily_labels) !!}, datasets: [{ label: 'Bookings', data: {!! json_encode($daily_bookings) !!}, borderColor: '#7C3AED', backgroundColor: 'rgba(124,58,237,.12)', borderWidth: 2.5, fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#7C3AED', pointBorderColor: '#fff', pointBorderWidth: 2 }] },
    options: { ...chartDefaults, scales: { ...chartDefaults.scales, y: { ...chartDefaults.scales.y, ticks: { font:{ size:11 }, maxTicksLimit:8, precision:0 } } } }
});
</script>
@endsection
