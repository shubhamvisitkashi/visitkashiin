@extends('admin.layouts.app')

@section('content')
<style>
/* ═══════════════════════════════════════════════════════════
   VISIT KASHI — BOOKING CALENDAR
   Airbnb-style · Stable · No overflow
   Architecture: work WITH .page-content, never fight it
═══════════════════════════════════════════════════════════ */

/* ── 1. Global reset scoped to this page ── */
*, *::before, *::after { box-sizing: border-box; }

/* Prevent any horizontal scroll at html/body level */
html { scrollbar-gutter: stable; }

/* ── 2. Page shell — NO negative margins, NO calc hacks ── */
.vkc-page {
    margin: 0;          /* stay inside .page-content padding */
    padding: 0 0 0 12px;
    background: #f7f8fa;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
    box-sizing: border-box;
}

/* ── 3. Top bar ── */
.vkc-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 4px 0 18px;
    background: #f7f8fa;
    position: relative;
    z-index: 10;
    margin-top: 80px;
}

.vkc-nav {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: nowrap;
}

.vkc-nav-btn {
    width: 34px; height: 34px;
    border-radius: 50%;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #374151;
    transition: background .12s, border-color .12s;
    flex-shrink: 0;
}
.vkc-nav-btn:hover { background: #f3f4f6; border-color: #9ca3af; }
.vkc-nav-btn svg { width: 15px; height: 15px; stroke-width: 2.2; }

.vkc-month-title {
    font-size: clamp(1.2rem, 2.5vw, 1.65rem);
    font-weight: 800;
    color: #111827;
    letter-spacing: -0.025em;
    white-space: nowrap;
}

.vkc-today-btn {
    height: 32px;
    padding: 0 12px;
    border: 1.5px solid #0891b2;
    border-radius: 8px;
    background: #fff;
    color: #0891b2;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background .12s, color .12s;
}
.vkc-today-btn:hover { background: #0891b2; color: #fff; }

.vkc-topbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

/* ── 4. Controls ── */
.vkc-select {
    height: 36px;
    padding: 0 10px;
    border: 1.5px solid #e5e7eb;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    background: #fff;
    cursor: pointer;
    outline: none;
    max-width: 160px;
    min-width: 120px;
}
.vkc-select:focus { border-color: #0891b2; box-shadow: 0 0 0 3px rgba(8,145,178,.1); }

.vkc-btn {
    height: 36px;
    padding: 0 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 9px;
    background: #fff;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    text-decoration: none !important;
    transition: background .12s, border-color .12s;
    white-space: nowrap;
}
.vkc-btn:hover { background: #f3f4f6; border-color: #9ca3af; color: #111827; }
.vkc-btn svg { width: 13px; height: 13px; flex-shrink: 0; }
.vkc-btn.primary { background: #0891b2; border-color: #0891b2; color: #fff !important; }
.vkc-btn.primary:hover { background: #0e7490; }

/* ── 5. Stats row ── */
.vkc-stats {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: clamp(8px, 1.2vw, 14px);
    margin-bottom: 20px;
    width: 100%;
}

.vkc-stat {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 14px;
    padding: clamp(10px, 1.5vw, 16px);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.vkc-stat-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.vkc-stat-icon svg { width: 18px; height: 18px; }

.vkc-stat-body { min-width: 0; }

.vkc-stat-val {
    font-size: clamp(1rem, 2vw, 1.3rem);
    font-weight: 900;
    color: #111827;
    letter-spacing: -0.02em;
    line-height: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.vkc-stat-lbl {
    font-size: 10.5px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-top: 4px;
    white-space: nowrap;
}

/* ── 6. Calendar wrapper ── */
.vkc-cal-wrap {
    background: #f8fafc;
    border: 1.5px solid #e5e7eb;
    border-radius: 18px;
    overflow: hidden;
    width: 100%;
    min-width: 0;
    padding: 12px;
}

/* Day name headers */
.vkc-day-headers {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 2px;
    background: transparent;
    padding: 0;
    margin-bottom: 4px;
}
.vkc-day-hdr {
    text-align: center;
    font-size: clamp(9.5px, 1vw, 11.5px);
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .07em;
    padding: 8px 2px;
    min-width: 0;
}

/* Calendar grid */
.vkc-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: clamp(2px, 0.4vw, 4px);
    width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

/* ── 7. Date cell ── */
.vkc-cell {
    border-radius: clamp(6px, 1vw, 10px);
    min-height: clamp(64px, 9vw, 100px);
    padding: clamp(6px, 0.8vw, 10px);
    cursor: pointer;
    transition: transform .12s, box-shadow .12s, filter .12s;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-width: 0;
    /* Default: available (green) */
    background: #4caf50;
}
.vkc-cell:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,.22);
    z-index: 3;
}
.vkc-cell:active { transform: scale(.96); }

/* Empty cell */
.vkc-cell.empty {
    background: transparent !important;
    cursor: default;
    pointer-events: none;
    box-shadow: none !important;
    transform: none !important;
}

/* Booked — any date with bookings */
.vkc-cell.has-booking { background: #e57373; }

/* Unavailable — past, no bookings */
.vkc-cell.unavailable { background: #bdbdbd; }

/* Selected */
.vkc-cell.selected {
    background: #2196f3 !important;
    box-shadow: 0 0 0 3px rgba(33,150,243,.45) !important;
    transform: scale(1.07) !important;
}

/* Today ring */
.vkc-cell.today { box-shadow: inset 0 0 0 2.5px rgba(255,255,255,.7); }

/* Today date number */
.vkc-cell.today .vkc-date-num {
    background: rgba(255,255,255,.35);
    border-radius: 50%;
    width: clamp(20px, 2.5vw, 26px);
    height: clamp(20px, 2.5vw, 26px);
    display: flex; align-items: center; justify-content: center;
    font-size: clamp(10px, 1.1vw, 13px);
}

/* Date number */
.vkc-date-num {
    font-size: clamp(1rem, 1.6vw, 1.3rem);
    font-weight: 800;
    color: #fff;
    line-height: 1;
    flex-shrink: 0;
    text-shadow: 0 1px 2px rgba(0,0,0,.12);
}

/* Sub label */
.vkc-count-area { margin-top: auto; min-width: 0; }
.vkc-count-num {
    font-size: clamp(8px, .9vw, 10px);
    font-weight: 700;
    color: rgba(255,255,255,.88);
    line-height: 1.3;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-transform: uppercase;
    letter-spacing: .06em;
}
.vkc-count-lbl { display: none; }

/* Count badge top-right */
.vkc-cell-badge {
    position: absolute; top: 5px; right: 6px;
    background: rgba(0,0,0,.25);
    color: #fff; font-size: 9px; font-weight: 800;
    border-radius: 10px; padding: 1px 6px;
    line-height: 1.5; white-space: nowrap;
}

/* Dots / triangles / bar hidden — not needed */
.vkc-tri-in, .vkc-tri-out { display: none; }
.vkc-dots { display: none; }
.vkc-bar  { display: none; }

/* ── Clickable stat card ── */
.vkc-stat-clickable {
    cursor: pointer;
    transition: box-shadow .18s, transform .18s, border-color .18s;
    border-color: #fed7aa !important;
}
.vkc-stat-clickable:hover {
    box-shadow: 0 4px 16px rgba(234,88,12,.15);
    transform: translateY(-2px);
    border-color: #ea580c !important;
}
.vkc-stat-clickable.active {
    border-color: #ea580c !important;
    background: #fff7ed !important;
    box-shadow: 0 4px 16px rgba(234,88,12,.2);
}
#stat-vip-card.active {
    border-color: #d97706 !important;
    background: #fefce8 !important;
    box-shadow: 0 4px 16px rgba(217,119,6,.2);
}
#stat-vip-card:hover {
    border-color: #d97706 !important;
    box-shadow: 0 4px 16px rgba(217,119,6,.15);
}

/* ── Legend bar ── */
.vkc-legend-bar {
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
    justify-content: center;
    padding: 14px 0 4px;
    border-top: 1px solid #f0f0f0;
    margin-top: 14px;
}
.vkc-leg-item { display:flex; align-items:center; gap:7px; font-size:12.5px; color:#374151; font-weight:500; }
.vkc-leg-sw { width:26px; height:26px; border-radius:5px; flex-shrink:0; }

/* ── Booking cards in day detail (same as public calendar) ── */
.cal-bk {
    background: #f8fafc; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 12px 14px; margin-bottom: 8px;
    border-left: 4px solid #4caf50; cursor: pointer;
    transition: box-shadow .15s;
}
.cal-bk:last-child { margin-bottom: 0; }
.cal-bk:hover { box-shadow: 0 3px 12px rgba(0,0,0,.1); }
.cal-bk.bk-cal-reserved { border-left-color: #e57373; }
.cal-bk.bk-cal-pending  { border-left-color: #e88c1f; }

.cal-bk-name {
    font-size: .87rem; font-weight: 800; color: #111827;
    display: flex; align-items: center; gap: 6px;
    margin-bottom: 6px; flex-wrap: wrap;
}
.cal-bk-star { color: #f59e0b; }
.cal-bk-status {
    font-size: .65rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; margin-left: auto;
    white-space: nowrap; flex-shrink: 0;
    background: #fee2e2; color: #b91c1c;
}
.bk-cal-pending .cal-bk-status { background: #fef3c7; color: #92400e; }
.bk-cal-reserved .cal-bk-status { background: #fee2e2; color: #b91c1c; }
.cal-bk-row {
    display: flex; align-items: flex-start; gap: 6px;
    font-size: .75rem; color: #6b7280; margin-bottom: 4px; line-height: 1.4;
}
.cal-bk-row:last-child { margin-bottom: 0; }
.cal-bk-row svg { flex-shrink: 0; margin-top: 1px; }
.cal-bk-row strong { color: #374151; font-weight: 700; }

/* ── 8. Loading state ── */
.vkc-loading {
    grid-column: span 7;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 56px 20px;
    gap: 12px;
    color: #9ca3af;
    font-size: 13px;
    font-weight: 500;
}
@keyframes spin { to { transform: rotate(360deg); } }
.vkc-spin { animation: spin .9s linear infinite; width: 20px; height: 20px; flex-shrink: 0; }

/* ── 9. Day detail panel ── */
.vkc-detail-wrap {
    margin-top: 16px;
    width: 100%;
    min-width: 0;
}
.vkc-detail {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    animation: fadeSlide .2s ease;
    width: 100%;
}
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.vkc-detail-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
    gap: 12px;
}
.vkc-detail-title {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    min-width: 0;
}
.vkc-detail-badge {
    background: #0891b2;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
}
.vkc-detail-close {
    width: 30px; height: 30px;
    border-radius: 50%;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #6b7280;
    transition: background .12s, border-color .12s, color .12s;
    flex-shrink: 0;
}
.vkc-detail-close:hover { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }
.vkc-detail-close svg { width: 13px; height: 13px; stroke-width: 2.5; }

/* ── 10. Booking rows ── */
.vkc-list { padding: 10px 14px 12px; max-height: 60vh; overflow-y: auto; }

.vkc-bk-row {
    display: grid;
    grid-template-columns: 4px 1fr auto auto;
    align-items: center;
    gap: 12px;
    padding: 11px 8px;
    border-radius: 10px;
    cursor: pointer;
    transition: background .12s;
    border-bottom: 1px solid #f3f4f6;
    min-width: 0;
}
.vkc-bk-row:last-child { border-bottom: none; }
.vkc-bk-row:hover { background: #f8fafc; }

.vkc-bk-type {
    align-self: stretch;
    border-radius: 4px;
    min-height: 40px;
    flex-shrink: 0;
}

.vkc-bk-body { min-width: 0; }

.vkc-bk-top {
    display: flex;
    align-items: baseline;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 3px;
    min-width: 0;
}
.vkc-bk-name {
    font-size: 13.5px;
    font-weight: 700;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
.vkc-bk-num { font-size: 11px; font-weight: 600; color: #9ca3af; white-space: nowrap; }

.vkc-bk-bottom {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    min-width: 0;
}
.vkc-bk-svc {
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}
.vkc-bk-pax { font-size: 12px; color: #9ca3af; white-space: nowrap; }

/* Pay bar */
.vkc-pay-bar { height: 3px; background: #e5e7eb; border-radius: 2px; margin-top: 5px; overflow: hidden; max-width: 100px; }
.vkc-pay-fill { height: 100%; border-radius: 2px; }

/* Right column */
.vkc-bk-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
    flex-shrink: 0;
    min-width: 80px;
}
.vkc-bk-amt { font-size: 14px; font-weight: 800; color: #111827; white-space: nowrap; }
.vkc-status {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
    white-space: nowrap;
}

/* Actions */
.vkc-bk-actions {
    display: flex;
    gap: 5px;
    flex-shrink: 0;
}
.vkc-act-btn {
    width: 30px; height: 30px;
    border-radius: 7px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #6b7280;
    text-decoration: none !important;
    transition: background .12s, border-color .12s, color .12s;
    flex-shrink: 0;
}
.vkc-act-btn:hover { background: #f0fdff; border-color: #0891b2; color: #0891b2; }
.vkc-act-btn svg { width: 13px; height: 13px; stroke-width: 2; }

/* Empty state */
.vkc-empty-state {
    text-align: center;
    padding: 36px 20px;
    color: #9ca3af;
}
.vkc-empty-state svg { width: 36px; height: 36px; margin-bottom: 10px; opacity: .3; display: block; margin-left: auto; margin-right: auto; }
.vkc-empty-state p { font-size: 13px; font-weight: 500; margin: 0; }

/* ── 11. Booking Popup Modal ── */
.vkp-backdrop {
    position: fixed; inset: 0;
    background: rgba(10,15,30,.52);
    z-index: 1060;
    opacity: 0; visibility: hidden;
    transition: opacity .22s, visibility .22s;
    display: flex; align-items: center; justify-content: center;
    padding: 16px;
}
.vkp-backdrop.open { opacity: 1; visibility: visible; }

.vkp-modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 540px;
    max-height: 90vh;
    display: flex; flex-direction: column;
    box-shadow: 0 24px 80px rgba(0,0,0,.22);
    transform: scale(.93) translateY(10px);
    transition: transform .24s cubic-bezier(.34,1.36,.64,1);
    overflow: hidden;
}
.vkp-backdrop.open .vkp-modal { transform: scale(1) translateY(0); }

/* Modal header */
.vkp-head {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 18px 22px 14px;
    background: linear-gradient(135deg,#0891b2 0%,#0e7490 100%);
    flex-shrink: 0; gap: 12px;
}
.vkp-head-vip {
    background: linear-gradient(135deg,#d97706 0%,#b45309 100%);
}
.vkp-head-left { min-width: 0; }
.vkp-head-num {
    font-size: 1.15rem; font-weight: 900; color: #fff;
    letter-spacing: -.02em; line-height: 1.1;
}
.vkp-head-guest {
    font-size: 13px; font-weight: 600; color: rgba(255,255,255,.82);
    margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.vkp-head-right { display: flex; align-items: flex-start; gap: 8px; flex-shrink: 0; }
.vkp-status-badge {
    display: inline-flex; align-items: center;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 800;
    background: rgba(255,255,255,.2); color: #fff;
    border: 1.5px solid rgba(255,255,255,.35);
    white-space: nowrap;
}
.vkp-close-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1.5px solid rgba(255,255,255,.35);
    background: rgba(255,255,255,.15);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    color: #fff; transition: background .12s; flex-shrink: 0;
}
.vkp-close-btn:hover { background: rgba(255,255,255,.28); }
.vkp-close-btn svg { width: 13px; height: 13px; stroke-width: 2.5; }

/* Modal body */
.vkp-body { flex: 1; overflow-y: auto; padding: 18px 22px 4px; }
.vkp-body::-webkit-scrollbar { width: 4px; }
.vkp-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

.vkp-sec { margin-bottom: 18px; }
.vkp-sec-lbl {
    font-size: 10px; font-weight: 800; color: #9ca3af;
    text-transform: uppercase; letter-spacing: .07em; margin-bottom: 8px;
}

/* Info grid */
.vkp-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.vkp-info-cell {
    background: #f8fafc; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 10px 13px; min-width: 0;
}
.vkp-info-lbl { font-size: 9.5px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 3px; }
.vkp-info-val { font-size: 13px; font-weight: 700; color: #0f172a; overflow: hidden; text-overflow: ellipsis; }

/* Service rows */
.vkp-svc-row {
    display: flex; align-items: center; gap: 9px;
    padding: 9px 12px; background: #f8fafc;
    border: 1px solid #e5e7eb; border-radius: 9px;
    margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #374151;
}
.vkp-svc-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }

/* Payment section */
.vkp-pay-bar-wrap {
    height: 18px; background: #e5e7eb; border-radius: 10px;
    overflow: hidden; margin: 8px 0;
}
.vkp-pay-fill {
    height: 100%; background: linear-gradient(90deg,#16a34a,#22c55e);
    display: flex; align-items: center; justify-content: flex-end;
    padding-right: 6px; font-size: 10px; font-weight: 800; color: #fff;
    min-width: 30px; transition: width .4s ease;
}
.vkp-pay-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
.vkp-pay-cell {
    text-align: center; background: #f8fafc;
    border: 1px solid #e5e7eb; border-radius: 9px; padding: 10px 6px;
}
.vkp-pay-label { font-size: 9.5px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 3px; }
.vkp-pay-val { font-size: 15px; font-weight: 900; }

/* Plan / package */
.vkp-plan {
    font-size: 13px; color: #374151; line-height: 1.7;
    background: #f8fafc; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 11px 14px;
}

/* Date+service row highlight */
.vkp-meta-row {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 8px; margin-bottom: 14px;
    padding: 10px 14px; background: #f0fdff; border: 1.5px solid #bae6fd;
    border-radius: 10px;
}
.vkp-meta-date { font-size: 13px; font-weight: 700; color: #0e7490; }
.vkp-meta-pax { font-size: 12px; font-weight: 600; color: #6b7280; }

/* Modal footer */
.vkp-foot {
    padding: 12px 18px; border-top: 1px solid #e5e7eb;
    display: flex; gap: 8px; flex-shrink: 0; background: #fafafa;
    flex-wrap: wrap;
}
.vkp-foot-btn {
    flex: 1; min-width: 100px; height: 38px;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    border-radius: 10px; font-size: 13px; font-weight: 700;
    cursor: pointer; text-decoration: none !important;
    border: 1.5px solid #e5e7eb; background: #fff; color: #374151;
    transition: background .12s, border-color .12s, color .12s;
    white-space: nowrap;
}
.vkp-foot-btn:hover { background: #f3f4f6; border-color: #9ca3af; color: #111827; }
.vkp-foot-btn svg { width: 13px; height: 13px; flex-shrink: 0; }
.vkp-foot-btn.primary { background: #0891b2; border-color: #0891b2; color: #fff !important; }
.vkp-foot-btn.primary:hover { background: #0e7490; }
.vkp-foot-btn.wa { background: #25d366; border-color: #25d366; color: #fff !important; }
.vkp-foot-btn.wa:hover { background: #16a34a; }

@media (max-width: 480px) {
    .vkp-info-grid { grid-template-columns: 1fr; }
    .vkp-pay-grid { grid-template-columns: 1fr; }
    .vkp-foot-btn { font-size: 12px; }
}

/* ── 12. FAB ── */
.vkc-fab {
    position: fixed;
    bottom: 28px; right: 28px;
    background: #0891b2;
    color: #fff !important;
    border: none;
    border-radius: 50px;
    padding: 13px 22px;
    font-size: 13.5px;
    font-weight: 700;
    display: flex; align-items: center; gap: 7px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(8,145,178,.4);
    transition: box-shadow .15s, transform .15s;
    z-index: 900;
    text-decoration: none !important;
}
.vkc-fab:hover { box-shadow: 0 8px 30px rgba(8,145,178,.5); transform: translateY(-2px); color: #fff !important; }
.vkc-fab svg { width: 15px; height: 15px; stroke-width: 2.5; }

/* ── 12. Drawer ── */
.vkd-backdrop {
    position: fixed; inset: 0;
    background: rgba(15,23,42,.38);
    z-index: 1040;
    opacity: 0; visibility: hidden;
    transition: opacity .25s, visibility .25s;
}
.vkd-backdrop.open { opacity: 1; visibility: visible; }

.vkd-drawer {
    position: fixed;
    top: 0; right: 0; bottom: 0;
    width: min(420px, 100vw);
    background: #fff;
    border-left: 1.5px solid #e5e7eb;
    box-shadow: -4px 0 32px rgba(0,0,0,.1);
    z-index: 1050;
    transform: translateX(100%);
    transition: transform .28s cubic-bezier(.4,0,.2,1);
    display: flex; flex-direction: column; overflow: hidden;
}
.vkd-drawer.open { transform: translateX(0); }

.vkd-head {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 18px 20px 14px;
    border-bottom: 1px solid #e5e7eb;
    background: #fafafa;
    flex-shrink: 0;
    gap: 12px;
}
.vkd-head-left { min-width: 0; }
.vkd-num { font-size: 1.25rem; font-weight: 900; color: #0891b2; letter-spacing: -.02em; }
.vkd-guest { font-size: 13px; font-weight: 600; color: #374151; margin-top: 4px; }

.vkd-close {
    width: 30px; height: 30px;
    border-radius: 7px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #6b7280;
    transition: background .12s;
    flex-shrink: 0;
}
.vkd-close:hover { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }
.vkd-close svg { width: 13px; height: 13px; stroke-width: 2.5; }

.vkd-body { flex: 1; overflow-y: auto; padding: 16px 20px; }
.vkd-body::-webkit-scrollbar { width: 4px; }
.vkd-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

.vkd-sec { margin-bottom: 16px; }
.vkd-lbl { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 7px; }
.vkd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.vkd-cell { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 9px; padding: 10px 12px; min-width: 0; }
.vkd-cell-l { font-size: 9.5px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 3px; }
.vkd-cell-v { font-size: 13px; font-weight: 700; color: #0f172a; overflow: hidden; text-overflow: ellipsis; }
.vkd-status { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; color: #fff; }

.vkd-svc-row { display: flex; align-items: center; gap: 8px; padding: 8px 10px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #374151; min-width: 0; }
.vkd-svc-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

.vkd-pay-bar-wrap { height: 16px; background: #e5e7eb; border-radius: 8px; overflow: hidden; margin: 8px 0; }
.vkd-pay-fill { height: 100%; background: linear-gradient(90deg,#16a34a,#22c55e); display: flex; align-items: center; justify-content: flex-end; padding-right: 5px; font-size: 10px; font-weight: 800; color: #fff; min-width: 28px; }

.vkd-pay-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
.vkd-pay-cell { text-align: center; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 9px 4px; }
.vkd-pay-label { font-size: 9.5px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 3px; }
.vkd-pay-val { font-size: 14px; font-weight: 900; }

.vkd-foot {
    padding: 12px 16px;
    border-top: 1px solid #e5e7eb;
    display: flex; gap: 8px;
    flex-shrink: 0;
    background: #fafafa;
}
.vkd-foot .vkc-btn { flex: 1; justify-content: center; }

/* ═══════════════════════════════════════
   RESPONSIVE BREAKPOINTS
   (working inward from large → small)
═══════════════════════════════════════ */

/* 1440px+ — generous spacing */
@media (min-width: 1440px) {
    .vkc-cell { min-height: 110px; }
    .vkc-stats { gap: 16px; }
}

/* 1280px */
@media (max-width: 1280px) {
    .vkc-cell { min-height: 96px; }
}

/* 1024px — laptop */
@media (max-width: 1024px) {
    .vkc-cell { min-height: 82px; }
    .vkc-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .vkc-bk-actions { display: none; }
    .vkc-bk-row { grid-template-columns: 4px 1fr auto; }
}

/* 768px — tablet */
@media (max-width: 768px) {
    .vkc-topbar { flex-direction: column; align-items: flex-start; gap: 10px; }
    .vkc-topbar-right { width: 100%; }
    .vkc-select { flex: 1; max-width: none; }
    .vkc-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; margin-bottom: 14px; }
    .vkc-cell { min-height: 66px; }
    .vkc-dots { display: none; }
    .vkc-bk-right { min-width: 70px; }
    .vkd-drawer { width: 100vw; top: auto; height: 88vh; border-radius: 18px 18px 0 0; border-left: none; border-top: 1.5px solid #e5e7eb; }
    .vkc-fab { bottom: 18px; right: 14px; padding: 11px 18px; font-size: 12.5px; }
    .vkd-pay-grid { grid-template-columns: repeat(3,1fr); }
}

/* 480px — mobile */
@media (max-width: 480px) {
    .vkc-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 7px; }
    .vkc-stat { padding: 10px; gap: 8px; }
    .vkc-stat-icon { width: 32px; height: 32px; }
    .vkc-cell { min-height: 52px; border-radius: 8px; }
    .vkc-day-hdr { font-size: 9px; padding: 8px 2px; letter-spacing: 0; }
    .vkc-bk-row { grid-template-columns: 3px 1fr; gap: 8px; }
    .vkc-bk-right { display: none; }
    .vkd-pay-grid { grid-template-columns: 1fr; }
    .vkd-grid { grid-template-columns: 1fr; }
    .vkc-fab { padding: 10px 16px; font-size: 12px; }
    .vkc-month-title { font-size: 1.15rem; }
}
</style>

<div class="vkc-page">

    {{-- ── TOP BAR ── --}}
    <div class="vkc-topbar">
        <div class="vkc-nav">
            <button class="vkc-nav-btn" onclick="changeMonth(-1)">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <div class="vkc-month-title" id="monthTitle">Loading…</div>
            <button class="vkc-nav-btn" onclick="changeMonth(1)">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            <button class="vkc-today-btn" onclick="goToday()">Today</button>
        </div>
        <div class="vkc-topbar-right">
            <select id="svcFilter" class="vkc-select" onchange="loadMonth()">
                <option value="">All Services</option>
                <option value="boat">⛵ Boat Bookings</option>
                <option value="cab">🚗 Cab Bookings</option>
                @foreach ($serviceTypes as $s)
                    <option value="{{ strtolower($s->name) }}">{{ $s->name }}</option>
                @endforeach
            </select>
            {{-- Share Calendar PIN button --}}
            @php
                // Always read fresh from DB (bypass any caching)
                $calPin = \App\Models\WebsiteSetup::where('name','calendar_pin')->value('value') ?? '——';
            @endphp
            <button onclick="document.getElementById('calShareModal').style.display='flex'"
                style="display:inline-flex;align-items:center;gap:6px;background:#7a3dec;color:#fff;border:none;border-radius:9px;padding:7px 14px;font-size:.8rem;font-weight:700;cursor:pointer;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/></svg>
                Share
            </button>
            <a href="{{ route('bookings.index') }}" class="vkc-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                List View
            </a>

            {{-- Share + PIN Modal --}}
            <div id="calShareModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center;padding:16px;">
                <div style="background:#fff;border-radius:20px;width:100%;max-width:420px;box-shadow:0 24px 60px rgba(0,0,0,.3);overflow:hidden;">

                    {{-- Modal Header --}}
                    <div style="background:linear-gradient(135deg,#1a1a2e,#7a3dec);padding:20px 24px;display:flex;align-items:center;gap:12px;">
                        <div style="width:44px;height:44px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:1rem;font-weight:800;color:#fff;">Share Calendar</div>
                            <div style="font-size:.76rem;color:rgba(255,255,255,.6);">Share link + PIN with staff or partners</div>
                        </div>
                        <button onclick="document.getElementById('calShareModal').style.display='none'" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:50%;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">&#x2715;</button>
                    </div>

                    <div style="padding:20px 24px;">

                        {{-- Success banner (shown by JS after AJAX save) --}}
                        <div id="pinSuccessBanner" style="display:none;background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:.82rem;font-weight:700;color:#065F46;align-items:center;gap:8px;"></div>

                        @if(session('cal_pin_success'))
                        <div style="background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:.82rem;font-weight:600;color:#065F46;display:flex;align-items:center;gap:8px;">
                            ✓ {{ session('cal_pin_success') }}
                        </div>
                        @endif

                        {{-- Calendar Link --}}
                        <div style="background:#F5F3FF;border:1.5px solid #DDD6FE;border-radius:10px;padding:12px 14px;margin-bottom:14px;">
                            <div style="font-size:.68rem;font-weight:700;color:#7C3AED;text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;">📎 Calendar Link</div>
                            <div style="font-size:.8rem;color:#374151;word-break:break-all;font-family:monospace;" id="calShareUrl">{{ url('/calendar') }}</div>
                        </div>

                        {{-- Current PIN --}}
                        <div id="calPinSection" style="background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:10px;padding:14px 16px;margin-bottom:16px;transition:background .3s,border-color .3s,transform .2s;">
                            <div style="font-size:.68rem;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;">🔐 Current Access PIN</div>
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                                <span id="calPinDisplay" style="font-size:2rem;font-weight:900;color:#065F46;letter-spacing:.35em;font-family:monospace;line-height:1;transition:color .3s;">{{ $calPin }}</span>
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
                                    <span style="font-size:.7rem;background:#d1fae5;color:#065F46;border-radius:20px;padding:2px 8px;font-weight:700;">Active</span>
                                    <span style="font-size:.68rem;color:#9ca3af;">Public calendar</span>
                                </div>
                            </div>
                        </div>

                        {{-- Set New PIN --}}
                        <div style="background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:12px;padding:14px;">
                            <div style="font-size:.78rem;font-weight:700;color:#374151;margin-bottom:10px;">🔑 Set New 6-Digit PIN</div>
                            <form action="{{ route('calendar.pin.update') }}" method="POST" id="pinUpdateForm">
                                @csrf
                                <div style="display:flex;gap:8px;margin-bottom:8px;justify-content:center;">
                                    @for($i=0;$i<6;$i++)
                                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                           class="pin-digit-box" id="pinBox{{ $i }}" autocomplete="off"
                                           style="width:44px;height:52px;min-width:0;flex-shrink:0;border:2px solid #E2E8F0;border-radius:10px;text-align:center;font-size:1.4rem;font-weight:800;color:#1a1a2e;outline:none;font-family:monospace;transition:border-color .15s,box-shadow .15s;background:#fff;"
                                           oninput="pinBoxInput(this,{{ $i }})"
                                           onkeydown="pinBoxKey(event,{{ $i }})">
                                    @endfor
                                    <input type="hidden" name="calendar_pin" id="pinHiddenVal">
                                </div>
                                <div style="display:flex;gap:8px;">
                                    <button type="button" onclick="genRandomPin()" style="flex:1;height:38px;background:#F1F5F9;color:#374151;border:1.5px solid #E2E8F0;border-radius:9px;font-size:.78rem;font-weight:700;cursor:pointer;">
                                        🎲 Random PIN
                                    </button>
                                    <button type="submit" id="pinSaveBtn" style="flex:1;height:38px;background:linear-gradient(135deg,#7a3dec,#1c97e9);color:#fff;border:none;border-radius:9px;font-size:.82rem;font-weight:700;cursor:pointer;">
                                        ✓ Save PIN
                                    </button>
                                </div>
                                <div id="pinError" style="color:#ef4444;font-size:.72rem;margin-top:5px;display:none;">Please enter all 6 digits.</div>
                            </form>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex;gap:8px;margin-top:14px;">
                            <button onclick="copyCalShare()" style="flex:1;height:40px;background:#7a3dec;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:.82rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Copy Link + PIN
                            </button>
                            <button onclick="document.getElementById('calShareModal').style.display='none'" style="flex:1;height:40px;background:#F1F5F9;color:#374151;border:none;border-radius:10px;font-weight:700;font-size:.82rem;cursor:pointer;">Close</button>
                        </div>

                    </div>
                </div>
            </div>

            <script>
            // ── PIN box input navigation ──
            function pinBoxInput(el, i) {
                el.value = el.value.replace(/\D/g,'');
                if (el.value && i < 5) document.getElementById('pinBox'+(i+1)).focus();
                updatePinHidden();
                // Live preview in Current Access PIN
                var preview = '';
                for (var j = 0; j < 6; j++) {
                    var v = document.getElementById('pinBox'+j)?.value || '';
                    preview += v || '—';
                }
                var display = document.getElementById('calPinDisplay');
                if (display) {
                    display.textContent = preview.replace(/—/g, '—');
                    display.style.color = preview.includes('—') ? '#9ca3af' : '#065F46';
                    display.style.letterSpacing = '.3em';
                }
            }
            function pinBoxKey(e, i) {
                if (e.key==='Backspace') {
                    if (!document.getElementById('pinBox'+i).value && i > 0)
                        document.getElementById('pinBox'+(i-1)).focus();
                    // Trigger live preview update after clearing
                    setTimeout(function(){ pinBoxInput(document.getElementById('pinBox'+i), i); }, 0);
                }
            }
            function updatePinHidden() {
                var pin = '';
                for(var j=0;j<6;j++) pin += document.getElementById('pinBox'+j).value;
                document.getElementById('pinHiddenVal').value = pin;
            }
            function genRandomPin() {
                var pin = String(Math.floor(100000 + Math.random() * 900000));
                for(var j=0;j<6;j++) {
                    var box = document.getElementById('pinBox'+j);
                    box.value = pin[j];
                    box.style.borderColor = '#7a3dec';
                }
                document.getElementById('pinHiddenVal').value = pin;
                // Update live preview
                var display = document.getElementById('calPinDisplay');
                if (display) {
                    display.textContent = pin;
                    display.style.color = '#065F46';
                }
            }
            document.getElementById('pinUpdateForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updatePinHidden();
                var pin = document.getElementById('pinHiddenVal').value;

                // Validate 6 digits
                if (pin.length !== 6 || !/^\d{6}$/.test(pin)) {
                    document.getElementById('pinError').style.display = 'block';
                    return;
                }
                document.getElementById('pinError').style.display = 'none';

                var btn = document.getElementById('pinSaveBtn');
                btn.textContent = 'Saving…';
                btn.disabled = true;

                // AJAX save — use FormData for reliable CSRF + validation
                var fd = new FormData();
                fd.append('_token', '{{ csrf_token() }}');
                fd.append('calendar_pin', pin);

                fetch('{{ route('calendar.pin.update') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: fd
                })
                .then(function(r) {
                    if (!r.ok) { throw new Error('HTTP ' + r.status); }
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        // ── 1. Update Current Access PIN display ──
                        var display = document.getElementById('calPinDisplay');
                        if (display) {
                            display.textContent = data.pin;
                            display.style.color  = '#065F46';
                            display.style.letterSpacing = '.3em';
                        }

                        // ── 2. Flash-highlight the PIN section ──
                        var pinSection = document.getElementById('calPinSection');
                        if (pinSection) {
                            pinSection.style.transition = 'background .3s,border-color .3s,transform .2s';
                            pinSection.style.background   = '#bbf7d0';
                            pinSection.style.borderColor  = '#16a34a';
                            pinSection.style.transform    = 'scale(1.02)';
                            setTimeout(function(){
                                pinSection.style.background  = '#ECFDF5';
                                pinSection.style.borderColor = '#6EE7B7';
                                pinSection.style.transform   = 'scale(1)';
                            }, 700);
                        }

                        // ── 3. Show success banner ──
                        var banner = document.getElementById('pinSuccessBanner');
                        if (banner) {
                            banner.textContent = '✓ Current PIN updated to ' + data.pin;
                            banner.style.display = 'flex';
                            setTimeout(function(){ banner.style.display = 'none'; }, 4000);
                        }

                        // ── 4. Clear PIN boxes & reset live preview ──
                        for(var j=0;j<6;j++){
                            var box = document.getElementById('pinBox'+j);
                            if(box){ box.value=''; box.style.borderColor='#E2E8F0'; box.style.boxShadow='none'; }
                        }
                        document.getElementById('pinHiddenVal').value = '';
                        // Reset live preview back to saved PIN
                        if (display) { display.textContent = data.pin; display.style.color = '#065F46'; }
                    }
                })
                .catch(function() {
                    alert('Failed to save PIN. Please try again.');
                })
                .finally(function() {
                    btn.textContent = '✓ Save PIN';
                    btn.disabled = false;
                });
            });

            // Focus style on PIN boxes
            document.querySelectorAll('.pin-digit-box').forEach(function(box) {
                box.addEventListener('focus', function(){
                    this.style.borderColor='#7a3dec';
                    this.style.boxShadow='0 0 0 3px rgba(122,61,236,.15)';
                });
                box.addEventListener('blur', function(){
                    this.style.borderColor = this.value ? '#7a3dec' : '#E2E8F0';
                    this.style.boxShadow='none';
                });
            });

            function copyCalShare() {
                var pin = document.getElementById('calPinDisplay').textContent.trim();
                var url = document.getElementById('calShareUrl').textContent.trim();
                navigator.clipboard.writeText('Booking Calendar: ' + url + '\nPIN: ' + pin).then(function(){
                    var btn = event.currentTarget;
                    btn.textContent = '✓ Copied!';
                    setTimeout(function(){ btn.innerHTML = '<svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Copy Link + PIN'; }, 2000);
                });
            }
            document.getElementById('calShareModal').addEventListener('click', function(e){
                if(e.target===this) this.style.display='none';
            });
            @if(session('cal_pin_success'))
            // Auto-open modal if PIN was just saved
            document.addEventListener('DOMContentLoaded', function(){
                document.getElementById('calShareModal').style.display='flex';
            });
            @endif
            </script>
        </div>
    </div>

    {{-- ── STATS ── --}}
    @php $isAdmin = auth('admin')->user()->hasAnyRole(['Super Admin','Admin','Manager']); @endphp
    <div class="vkc-stats" style="grid-template-columns: repeat({{ $isAdmin ? 5 : 4 }}, minmax(0, 1fr));">
        <div class="vkc-stat">
            <div class="vkc-stat-icon" style="background:#eff6ff;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="vkc-stat-body"><div class="vkc-stat-val" id="st-total">—</div><div class="vkc-stat-lbl">Bookings</div></div>
        </div>
        @if($isAdmin)
        <div class="vkc-stat" style="border-color:#d1fae5;">
            <div class="vkc-stat-icon" style="background:#f0fdf4;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="vkc-stat-body">
                <div class="vkc-stat-val" id="st-rev" style="color:#16a34a;">—</div>
                <div class="vkc-stat-lbl">Total Revenue</div>
                <div id="st-rev-sub" style="font-size:10px;color:#9ca3af;margin-top:2px;font-weight:500;"></div>
            </div>
        </div>
        @endif
        <div class="vkc-stat vkc-stat-clickable" id="stat-pending-card" onclick="showPendingPayments()" title="Click to view all pending payments">
            <div class="vkc-stat-icon" style="background:#fff7ed;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="vkc-stat-body">
                <div class="vkc-stat-val" id="st-pending">—</div>
                <div class="vkc-stat-lbl">Pending Pay <span style="font-size:9px;color:#ea580c;">▼ View</span></div>
            </div>
        </div>
        <div class="vkc-stat vkc-stat-clickable" id="stat-vip-card" onclick="showVipBookings()" title="Click to view VIP bookings" style="border-color:#fde68a !important;">
            <div class="vkc-stat-icon" style="background:#fef9c3;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="vkc-stat-body">
                <div class="vkc-stat-val" id="st-vip" style="color:#ca8a04;">—</div>
                <div class="vkc-stat-lbl">VIP (&gt;₹15K) <span style="font-size:9px;color:#ca8a04;">▼ View</span></div>
            </div>
        </div>

        {{-- Booking Occupancy --}}
        <div class="vkc-stat" style="border-color:#e0e7ff;">
            <div class="vkc-stat-icon" style="background:#eef2ff;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
            </div>
            <div class="vkc-stat-body">
                <div class="vkc-stat-val" id="st-occ" style="color:#4f46e5;">—</div>
                <div class="vkc-stat-lbl">Occupancy</div>
                <div id="st-occ-sub" style="font-size:10px;color:#9ca3af;margin-top:2px;font-weight:500;"></div>
            </div>
        </div>
    </div>

    {{-- ── CALENDAR ── --}}
    <div class="vkc-cal-wrap">
        <div class="vkc-day-headers">
            <div class="vkc-day-hdr">Sun</div>
            <div class="vkc-day-hdr">Mon</div>
            <div class="vkc-day-hdr">Tue</div>
            <div class="vkc-day-hdr">Wed</div>
            <div class="vkc-day-hdr">Thu</div>
            <div class="vkc-day-hdr">Fri</div>
            <div class="vkc-day-hdr">Sat</div>
        </div>
        <div class="vkc-grid" id="calGrid">
            <div class="vkc-loading">
                <svg class="vkc-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                Loading calendar…
            </div>
        </div>
    </div>

    {{-- ── Legend ── --}}
    <div class="vkc-legend-bar">
        <div class="vkc-leg-item"><div class="vkc-leg-sw" style="background:#4caf50;"></div> Available</div>
        <div class="vkc-leg-item"><div class="vkc-leg-sw" style="background:#e57373;"></div> Booked</div>
        <div class="vkc-leg-item"><div class="vkc-leg-sw" style="background:#bdbdbd;"></div> Unavailable</div>
        <div class="vkc-leg-item"><div class="vkc-leg-sw" style="background:#2196f3;"></div> Selected</div>
    </div>

    {{-- ── DAY DETAIL ── --}}
    <div id="dayDetail" style="display:none;" class="vkc-detail-wrap"></div>

    {{-- VIP Bookings Panel --}}
    <div id="vipPanel" style="display:none;" class="vkc-detail-wrap">
        <div class="vkc-detail">
            <div class="vkc-detail-head" style="background:linear-gradient(135deg,#78350f,#d97706);">
                <div class="vkc-detail-title" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><polygon stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span style="color:#fff;font-weight:700;">VIP Bookings <span style="color:#fde68a;">(&gt;₹15K)</span></span>
                    <span id="vipCount" style="background:rgba(255,255,255,.2);color:#fff;font-size:11px;font-weight:800;padding:2px 9px;border-radius:20px;">0</span>
                    <span id="vipTotal" style="background:rgba(255,255,255,.15);color:#fde68a;font-size:11px;font-weight:700;padding:2px 9px;border-radius:20px;margin-left:auto;"></span>
                </div>
                <button onclick="closeVipPanel()" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:28px;height:28px;border-radius:50%;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">&#x2715;</button>
            </div>
            <div class="vkc-list" id="vipList"></div>
        </div>
    </div>

    {{-- Pending Payments Panel --}}
    <div id="pendingPanel" style="display:none;" class="vkc-detail-wrap">
        <div class="vkc-detail">
            <div class="vkc-detail-head" style="background:linear-gradient(135deg,#7c2d12,#ea580c);">
                <div class="vkc-detail-title" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="color:#fff;font-weight:700;">Pending Payments</span>
                    <span id="pendingCount" style="background:rgba(255,255,255,.2);color:#fff;font-size:11px;font-weight:800;padding:2px 9px;border-radius:20px;">0</span>
                    <span id="pendingTotal" style="background:rgba(255,255,255,.15);color:#fff;font-size:11px;font-weight:700;padding:2px 9px;border-radius:20px;margin-left:auto;"></span>
                </div>
                <button onclick="closePendingPanel()" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:28px;height:28px;border-radius:50%;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">&#x2715;</button>
            </div>
            <div class="vkc-list" id="pendingList"></div>
        </div>
    </div>

</div>

{{-- Booking Popup Modal --}}
<div class="vkp-backdrop" id="vkpBackdrop" onclick="closeBookingPopup(event)">
    <div class="vkp-modal" id="vkpModal">
        <div class="vkp-head" id="vkpHead">
            <div class="vkp-head-left">
                <div class="vkp-head-num" id="vkpNum">—</div>
                <div class="vkp-head-guest" id="vkpGuest">—</div>
            </div>
            <div class="vkp-head-right">
                <span class="vkp-status-badge" id="vkpStatusBadge">—</span>
                <button class="vkp-close-btn" onclick="closeBookingPopupBtn()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </div>
        <div class="vkp-body" id="vkpBody"></div>
        <div class="vkp-foot">
            <button class="vkp-foot-btn" onclick="closeBookingPopupBtn()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Close
            </button>
            <a id="vkpViewBtn" href="#" class="vkp-foot-btn primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Full Details
            </a>
            <a id="vkpWaBtn" href="#" target="_blank" class="vkp-foot-btn wa">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                WhatsApp
            </a>
        </div>
    </div>
</div>

{{-- Drawer --}}
<div class="vkd-backdrop" id="vkdBackdrop" onclick="closeDrawer()"></div>
<div class="vkd-drawer" id="vkdDrawer">
    <div class="vkd-head">
        <div class="vkd-head-left">
            <div class="vkd-num" id="vkdNum">—</div>
            <div class="vkd-guest" id="vkdGuest">—</div>
        </div>
        <button class="vkd-close" onclick="closeDrawer()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="vkd-body" id="vkdBody"></div>
    <div class="vkd-foot">
        <button class="vkc-btn" onclick="closeDrawer()">Close</button>
        <a id="vkdViewBtn" href="#" class="vkc-btn primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            View Details
        </a>
        <a id="vkdWaBtn" href="#" target="_blank" class="vkc-btn" style="background:#25d366;border-color:#25d366;color:#fff !important;flex:1;justify-content:center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            WhatsApp
        </a>
    </div>
</div>

@can('booking-create')
<a href="{{ route('bookings.create-direct') }}" class="vkc-fab">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    New Booking
</a>
@endcan

<script>
const IS_ADMIN = {{ $isAdmin ? 'true' : 'false' }};
const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const STATUS_COLORS = {
    'Confirmed':'#16a34a','In Progress':'#2563eb','Completed':'#7c3aed',
    'Cancelled':'#dc2626','Not Started':'#6b7280','Pending':'#d97706'
};

let curYear  = new Date().getFullYear();
let curMonth = new Date().getMonth();
let byDate   = {};
let selectedDate = null;

document.addEventListener('DOMContentLoaded', loadMonth);

/* ── FETCH ── */
async function loadMonth() {
    document.getElementById('dayDetail').style.display = 'none';
    selectedDate = null;
    document.getElementById('calGrid').innerHTML =
        `<div class="vkc-loading"><svg class="vkc-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>Loading…</div>`;

    const first = new Date(curYear, curMonth, 1);
    const last  = new Date(curYear, curMonth + 1, 0);
    const svc   = document.getElementById('svcFilter').value;

    const url = new URL('{{ route('bookings.calendar.events') }}', window.location.origin);
    url.searchParams.set('start', dfmt(first));
    url.searchParams.set('end',   dfmt(last));
    if (svc) url.searchParams.set('service_type', svc);

    try {
        const events = await fetch(url).then(r => r.json());
        byDate = {};
        events.forEach(ev => {
            const d = ev.start.split('T')[0];
            if (!byDate[d]) byDate[d] = [];
            byDate[d].push(ev);
        });
        renderGrid();
        allMonthEvents = events;          // store for pending/vip panels
        closePendingPanel();              // reset on month change
        closeVipPanel();
        renderStats(events);
    } catch(e) {
        document.getElementById('calGrid').innerHTML =
            `<div class="vkc-loading" style="color:#ef4444;grid-column:span 7;">Failed to load. <button onclick="loadMonth()" style="color:#0891b2;background:none;border:none;cursor:pointer;font-weight:700;margin-left:6px;">Retry</button></div>`;
    }
}

/* ── RENDER GRID ── */
function renderGrid() {
    document.getElementById('monthTitle').textContent = MONTHS[curMonth] + ' ' + curYear;

    const today    = new Date(); today.setHours(0,0,0,0);
    const todayStr = dfmt(today);
    const firstDay = new Date(curYear, curMonth, 1);
    const totalDays= new Date(curYear, curMonth + 1, 0).getDate();
    const startDow = firstDay.getDay();

    let cells = [];
    for (let i = 0; i < startDow; i++) cells.push({ empty: true });
    for (let d = 1; d <= totalDays; d++) {
        const ds  = `${curYear}-${pad(curMonth+1)}-${pad(d)}`;
        const evs = byDate[ds] || [];
        cells.push({ date: d, ds, evs, isToday: ds === todayStr });
    }
    while (cells.length % 7 !== 0) cells.push({ empty: true });

    const todayDate = new Date(); todayDate.setHours(0,0,0,0);

    document.getElementById('calGrid').innerHTML = cells.map(c => {
        if (c.empty) return `<div class="vkc-cell empty"></div>`;

        const has    = c.evs.length > 0;
        const sel    = c.ds === selectedDate;
        const isPast = new Date(c.ds + 'T00:00:00') < todayDate && !c.isToday;

        // Colour — same logic as public calendar
        let state, lbl;
        if (sel) {
            state = 'selected';
            lbl   = has ? `${c.evs.length} Booking${c.evs.length>1?'s':''}` : 'Selected';
        } else if (has) {
            state = 'has-booking';                          // red #e57373
            lbl   = `${c.evs.length} Booking${c.evs.length>1?'s':''}`;
        } else if (isPast) {
            state = 'unavailable';                          // grey
            lbl   = 'Unavailable';
        } else {
            state = '';                                     // default green
            lbl   = 'Available';
        }

        let cls = `vkc-cell ${state}`;
        if (c.isToday) cls += ' today';

        const badge = has && !sel
            ? `<div class="vkc-cell-badge">${c.evs.length}</div>`
            : '';

        return `<div class="${cls}" onclick="selectDate('${c.ds}')">
            ${badge}
            <div class="vkc-date-num">${c.date}</div>
            <div class="vkc-count-area">
                <span class="vkc-count-num">${lbl}</span>
            </div>
        </div>`;
    }).join('');
}

/* ── SELECT DATE ── */
function selectDate(ds) {
    selectedDate = ds;
    renderGrid();

    const evs   = byDate[ds] || [];
    const panel = document.getElementById('dayDetail');

    if (!evs.length) {
        panel.style.display = 'block';
        panel.innerHTML = `<div class="vkc-detail">
            <div class="vkc-detail-head">
                <div class="vkc-detail-title">${fmtDisplay(ds)}</div>
                <button class="vkc-detail-close" onclick="closeDetail()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="vkc-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <p>No bookings on this date</p>
            </div>
        </div>`;
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        return;
    }

    const rows = evs.map(ev => {
        const p      = ev.extendedProps || {};
        const isVip  = (p.total_amount || 0) >= 15000;
        const st     = (p.status||'').toLowerCase();
        const cls    = (st.includes('confirm')||st.includes('complet')) ? 'bk-cal-reserved' : 'bk-cal-pending';
        const svc    = (p.service_types && p.service_types.length)
                     ? p.service_types.map(t => t.name).join(', ')
                     : (p.services || p.service || '—');
        const due    = parseFloat(p.due_amount    || 0);
        const paid   = parseFloat(p.paid_amount   || 0);
        const addedBy = p.created_by || p.added_by || '—';
        const phone  = (p.contact||'').replace(/[^0-9]/g,'');
        const waHref = phone
            ? `https://wa.me/91${phone}?text=${encodeURIComponent('Namaste '+p.guest_name+' ji 🙏\nBooking '+p.booking_number+'\n— Visit Kashi Team')}`
            : null;

        let payLine = '';
        if (due > 0) {
            payLine = `<div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#ef4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color:#ef4444;font-weight:700;">Due: ₹${fmtNum(due)}</span>
            </div>`;
        } else if (paid > 0) {
            payLine = `<div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#16a34a" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color:#16a34a;font-weight:700;">Paid</span>
            </div>`;
        }

        return `<div class="cal-bk ${cls}" onclick="openDrawer(${JSON.stringify(ev).replace(/"/g,'&quot;')})">
            <div class="cal-bk-name">
                ${isVip ? '<span class="cal-bk-star">★</span>' : ''}
                ${esc(p.guest_name || 'Guest')}
                <span class="cal-bk-status">${esc(p.status||'')}</span>
            </div>
            ${p.contact ? `<div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <strong>${esc(p.contact)}</strong>
                ${waHref ? `<a href="${waHref}" target="_blank" onclick="event.stopPropagation()" style="color:#16a34a;margin-left:6px;font-weight:700;font-size:.72rem;text-decoration:none;">WhatsApp</a>` : ''}
            </div>` : ''}
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#0891b2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                ${esc(svc)}${p.pax ? ' · ' + p.pax + ' person' + (p.pax > 1 ? 's' : '') : ''}
            </div>
            ${payLine}
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#9ca3af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Added by: <strong>${esc(addedBy)}</strong>
            </div>
        </div>`;
    }).join('');

    panel.style.display = 'block';
    panel.innerHTML = `<div class="vkc-detail">
        <div class="vkc-detail-head">
            <div class="vkc-detail-title">
                ${fmtDisplay(ds)}
                <span class="vkc-detail-badge">${evs.length} booking${evs.length>1?'s':''}</span>
            </div>
            <button class="vkc-detail-close" onclick="closeDetail()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="vkc-list">${rows}</div>
    </div>`;
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ── VIP BOOKINGS PANEL ── */
function showVipBookings() {
    const vipPanel = document.getElementById('vipPanel');
    const card     = document.getElementById('stat-vip-card');

    if (vipPanel.style.display !== 'none') {
        closeVipPanel(); return;
    }

    // Close other panels
    document.getElementById('dayDetail').style.display = 'none';
    document.getElementById('pendingPanel').style.display = 'none';
    document.getElementById('stat-pending-card').classList.remove('active');
    selectedDate = null; renderGrid();

    // Deduplicate VIP bookings
    const seen = new Set();
    const vips = allMonthEvents.filter(ev => {
        const key = ev.extendedProps?.booking_number || ev.id;
        if (seen.has(key)) return false;
        seen.add(key);
        return parseFloat(ev.extendedProps?.total_amount || 0) >= 15000
            && (ev.extendedProps?.status||'').toLowerCase() !== 'cancelled';
    });

    // Sort by total_amount desc
    vips.sort((a, b) => parseFloat(b.extendedProps?.total_amount||0) - parseFloat(a.extendedProps?.total_amount||0));

    const totalRev = vips.reduce((s,ev) => s + parseFloat(ev.extendedProps?.total_amount||0), 0);

    document.getElementById('vipCount').textContent = vips.length + ' booking' + (vips.length!==1?'s':'');
    document.getElementById('vipTotal').textContent = 'Total: ₹' + fmtNum(totalRev);

    if (vips.length === 0) {
        document.getElementById('vipList').innerHTML =
            '<div style="text-align:center;padding:28px;color:#9ca3af;font-size:.84rem;">No VIP bookings this month.</div>';
        vipPanel.style.display = 'block';
        card.classList.add('active');
        vipPanel.scrollIntoView({ behavior:'smooth', block:'nearest' });
        return;
    }

    const rows = vips.map(ev => {
        const p      = ev.extendedProps || {};
        const due    = parseFloat(p.due_amount  || 0);
        const paid   = parseFloat(p.paid_amount || 0);
        const total  = parseFloat(p.total_amount|| 0);
        const svc    = (p.service_types && p.service_types.length)
                     ? p.service_types.map(t => t.name).join(', ')
                     : (p.services || p.service || '—');
        const addedBy = p.created_by || p.added_by || '—';

        let payLine = '';
        if (due > 0) {
            payLine = `<div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#ef4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color:#ef4444;font-weight:700;">Due: ₹${fmtNum(due)}</span>
                <span style="color:#9ca3af;font-size:.68rem;">/ Total ₹${fmtNum(total)}</span>
            </div>`;
        } else if (paid > 0) {
            payLine = `<div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#16a34a" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color:#16a34a;font-weight:700;">Paid</span>
            </div>`;
        }

        return `<div class="cal-bk bk-cal-reserved" style="border-left-color:#d97706;" onclick="openDrawer(${JSON.stringify(ev).replace(/"/g,'&quot;')})">
            <div class="cal-bk-name">
                <span class="cal-bk-star">★</span>
                ${esc(p.guest_name || 'Guest')}
                <span class="cal-bk-status" style="background:#fef3c7;color:#92400e;">${esc(p.status||'')}</span>
            </div>
            <div class="cal-bk-row" style="color:#d97706;font-weight:800;font-size:.82rem;">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#d97706" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                ₹${fmtNum(total)}
            </div>
            ${p.contact ? `<div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <strong>${esc(p.contact)}</strong>
            </div>` : ''}
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#0891b2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                ${esc(svc)}${p.pax ? ' · ' + p.pax + ' person' + (p.pax>1?'s':'') : ''}
            </div>
            ${payLine}
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#9ca3af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Service: <strong>${esc(ev.start||'—')}</strong>
            </div>
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#9ca3af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Added by: <strong>${esc(addedBy)}</strong>
            </div>
        </div>`;
    }).join('');

    document.getElementById('vipList').innerHTML = rows;
    vipPanel.style.display = 'block';
    card.classList.add('active');
    vipPanel.scrollIntoView({ behavior:'smooth', block:'nearest' });
}

function closeVipPanel() {
    document.getElementById('vipPanel').style.display = 'none';
    document.getElementById('stat-vip-card').classList.remove('active');
}

/* ── PENDING PAYMENTS PANEL ── */
var allMonthEvents = [];

function showPendingPayments() {
    // Collect unique pending bookings from allMonthEvents
    const seen = new Set();
    const pending = allMonthEvents.filter(ev => {
        const key = ev.extendedProps?.booking_number || ev.id;
        if (seen.has(key)) return false;
        seen.add(key);
        return parseFloat(ev.extendedProps?.due_amount || 0) > 0;
    });

    const panel = document.getElementById('pendingPanel');
    const card  = document.getElementById('stat-pending-card');

    if (panel.style.display !== 'none') {
        // toggle off
        closePendingPanel();
        return;
    }

    // Close other panels
    document.getElementById('dayDetail').style.display = 'none';
    closeVipPanel();
    selectedDate = null;
    renderGrid();

    if (pending.length === 0) {
        panel.style.display = 'block';
        document.getElementById('pendingList').innerHTML =
            '<div style="text-align:center;padding:28px;color:#9ca3af;font-size:.84rem;">No pending payments found.</div>';
        document.getElementById('pendingCount').textContent = '0';
        document.getElementById('pendingTotal').textContent = '';
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        card.classList.add('active');
        return;
    }

    // Sort by due amount desc
    pending.sort((a, b) => parseFloat(b.extendedProps?.due_amount||0) - parseFloat(a.extendedProps?.due_amount||0));

    const totalDue = pending.reduce((s, ev) => s + parseFloat(ev.extendedProps?.due_amount||0), 0);

    document.getElementById('pendingCount').textContent = pending.length + ' booking' + (pending.length>1?'s':'');
    document.getElementById('pendingTotal').textContent = 'Total Due: ₹' + fmtNum(totalDue);

    const rows = pending.map(ev => {
        const p     = ev.extendedProps || {};
        const isVip = (p.total_amount || 0) >= 15000;
        const due   = parseFloat(p.due_amount || 0);
        const svc   = (p.service_types && p.service_types.length)
                    ? p.service_types.map(t => t.name).join(', ')
                    : (p.services || p.service || '—');
        const addedBy = p.created_by || p.added_by || '—';

        return `<div class="cal-bk bk-cal-reserved" onclick="openDrawer(${JSON.stringify(ev).replace(/"/g,'&quot;')})">
            <div class="cal-bk-name">
                ${isVip ? '<span class="cal-bk-star">★</span>' : ''}
                ${esc(p.guest_name || 'Guest')}
                <span class="cal-bk-status">${esc(p.status||'')}</span>
            </div>
            ${p.contact ? `<div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <strong>${esc(p.contact)}</strong>
            </div>` : ''}
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#0891b2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                ${esc(svc)}${p.pax ? ' · ' + p.pax + ' person' + (p.pax>1?'s':'') : ''}
            </div>
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#ef4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color:#ef4444;font-weight:800;">Due: ₹${fmtNum(due)}</span>
                <span style="color:#9ca3af;font-size:.68rem;">/ Total ₹${fmtNum(p.total_amount)}</span>
            </div>
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#9ca3af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Service date: <strong>${esc(ev.start || '—')}</strong>
            </div>
            <div class="cal-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#9ca3af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Added by: <strong>${esc(addedBy)}</strong>
            </div>
        </div>`;
    }).join('');

    document.getElementById('pendingList').innerHTML = rows;
    panel.style.display = 'block';
    card.classList.add('active');
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function closePendingPanel() {
    document.getElementById('pendingPanel').style.display = 'none';
    document.getElementById('stat-pending-card').classList.remove('active');
}

function closeDetail() {
    document.getElementById('dayDetail').style.display = 'none';
    selectedDate = null;
    renderGrid();
}

/* ── STATS ── */
function renderStats(events) {
    // Deduplicate by booking_number — same booking may appear on multiple service dates
    const seen    = new Set();
    const unique  = events.filter(ev => {
        const key = ev.extendedProps?.booking_number || ev.id;
        if (seen.has(key)) return false;
        seen.add(key); return true;
    });
    const active  = unique.filter(ev => (ev.extendedProps?.status||'').toLowerCase() !== 'cancelled');

    set('st-total',   unique.length);
    set('st-pending', unique.filter(ev => (ev.extendedProps?.due_amount||0) > 0).length);
    set('st-vip',     unique.filter(ev => (ev.extendedProps?.total_amount||0) >= 15000).length);

    if (IS_ADMIN) {
        const totalBilled = active.reduce((s,ev) => s + parseFloat(ev.extendedProps?.total_amount  || 0), 0);
        const totalPaid   = active.reduce((s,ev) => s + parseFloat(ev.extendedProps?.paid_amount   || 0), 0);
        const totalDue    = active.reduce((s,ev) => s + parseFloat(ev.extendedProps?.due_amount    || 0), 0);

        set('st-rev', '₹' + fmtNum(totalBilled));

        const subEl = document.getElementById('st-rev-sub');
        if (subEl) {
            const paidPct = totalBilled > 0 ? Math.round((totalPaid / totalBilled) * 100) : 0;
            const pctColor = paidPct >= 80 ? '#059669' : paidPct >= 50 ? '#d97706' : '#ef4444';
            subEl.innerHTML =
                `<span style="color:#16a34a;font-weight:700;">₹${fmtNum(totalPaid)} collected</span>` +
                (totalDue > 0 ? ` &nbsp;·&nbsp; <span style="color:#d97706;">₹${fmtNum(totalDue)} due</span>` : '') +
                ` &nbsp;<span style="background:#d1fae5;color:${pctColor};border-radius:4px;padding:1px 5px;font-size:9px;font-weight:800;">${paidPct}%</span>`;
        }
    }

    /* ── Booking Occupancy ──────────────────────────────────────
       Occupancy = unique days that have ≥1 active booking
                   ÷ total days in current month × 100
    ──────────────────────────────────────────────────────────── */
    const daysInMonth   = new Date(curYear, curMonth + 1, 0).getDate();
    // Use all events (not deduplicated) — each event = one service date
    const bookedDays    = new Set(
        events.filter(ev => (ev.extendedProps?.status||'').toLowerCase() !== 'cancelled')
              .map(ev => (ev.start||'').split('T')[0]).filter(d => d)
    ).size;
    const occupancyPct  = daysInMonth > 0 ? Math.round((bookedDays / daysInMonth) * 100) : 0;

    // Colour: green ≥70%, amber ≥40%, red <40%
    const occColor = occupancyPct >= 70 ? '#16a34a' : (occupancyPct >= 40 ? '#d97706' : '#ef4444');
    const occEl    = document.getElementById('st-occ');
    const occSubEl = document.getElementById('st-occ-sub');

    if (occEl)    { occEl.textContent = occupancyPct + '%'; occEl.style.color = occColor; }
    if (occSubEl) { occSubEl.innerHTML = `<span style="font-weight:600;color:${occColor};">${bookedDays}</span> <span>/ ${daysInMonth} days booked</span>`; }
}

/* ── BOOKING POPUP ── */
function openBookingPopup(ev) {
    if (typeof ev === 'string') ev = JSON.parse(ev);
    const p   = ev.extendedProps || {};
    const vip = (p.total_amount||0) >= 15000;
    const sc  = STATUS_COLORS[p.status] || '#6b7280';
    const pct = Math.min(100, p.payment_percentage||0);
    const dueClr = (p.due_amount||0) > 0 ? '#d97706' : '#16a34a';
    const payBarCol = pct >= 100 ? 'linear-gradient(90deg,#16a34a,#22c55e)' : (pct > 60 ? 'linear-gradient(90deg,#f59e0b,#fbbf24)' : 'linear-gradient(90deg,#ef4444,#f87171)');

    /* Header */
    const head = document.getElementById('vkpHead');
    head.className = 'vkp-head' + (vip ? ' vkp-head-vip' : '');
    document.getElementById('vkpNum').textContent   = (vip ? '★ ' : '') + (p.booking_number || 'N/A');
    document.getElementById('vkpGuest').textContent = p.guest_name || '—';
    document.getElementById('vkpStatusBadge').textContent = p.status || '—';

    /* Links */
    document.getElementById('vkpViewBtn').href = p.url || (p.booking_type === 'boat' ? '/admin/boat-booking/' + p.booking_number : p.booking_type === 'cab' ? '/admin/cab-bookings/' + String(ev.id).replace('cab_','') : '/admin/bookings/' + ev.id);
    const phone = (p.contact||'').replace(/[^0-9]/g,'');
    const waMsg = encodeURIComponent(`Namaste ${p.guest_name} ji 🙏\nBooking ${p.booking_number} on ${p.service_date} confirmed.\nPersons: ${p.pax} | Total: ₹${fmtNum(p.total_amount)}\n— Visit Kashi Team`);
    const waEl  = document.getElementById('vkpWaBtn');
    if (phone) { waEl.href = `https://wa.me/91${phone}?text=${waMsg}`; waEl.style.display = ''; }
    else        { waEl.style.display = 'none'; }

    /* Services */
    let svcHtml = '';
    if (p.service_types && p.service_types.length) {
        svcHtml = p.service_types.map(t => {
            const c = t.color?.background || '#6366f1';
            return `<div class="vkp-svc-row"><div class="vkp-svc-dot" style="background:${c};"></div><span>${esc(t.name)}</span></div>`;
        }).join('');
    } else if (p.services && p.services !== 'No services') {
        svcHtml = `<div class="vkp-svc-row"><div class="vkp-svc-dot" style="background:#6366f1;"></div><span>${esc(p.services)}</span></div>`;
    }

    document.getElementById('vkpBody').innerHTML = `
        <div class="vkp-meta-row">
            <span class="vkp-meta-date">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;vertical-align:-2px;margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                ${esc(p.service_date || '—')}
            </span>
            <span class="vkp-meta-pax">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;vertical-align:-2px;margin-right:3px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                ${esc(String(p.pax||'—'))} persons
            </span>
        </div>

        <div class="vkp-sec">
            <div class="vkp-sec-lbl">Guest Information</div>
            <div class="vkp-info-grid">
                <div class="vkp-info-cell">
                    <div class="vkp-info-lbl">Full Name</div>
                    <div class="vkp-info-val">${esc(p.guest_name||'—')}</div>
                </div>
                <div class="vkp-info-cell">
                    <div class="vkp-info-lbl">Contact</div>
                    <div class="vkp-info-val">${esc(p.contact||'—')}</div>
                </div>
                <div class="vkp-info-cell">
                    <div class="vkp-info-lbl">Persons (Pax)</div>
                    <div class="vkp-info-val">${esc(String(p.pax||'—'))}</div>
                </div>
                <div class="vkp-info-cell">
                    <div class="vkp-info-lbl">Booked By</div>
                    <div class="vkp-info-val">${esc(p.created_by||'—')}</div>
                </div>
            </div>
        </div>

        ${svcHtml ? `<div class="vkp-sec"><div class="vkp-sec-lbl">Services</div>${svcHtml}</div>` : ''}

        <div class="vkp-sec">
            <div class="vkp-sec-lbl">Payment Summary</div>
            <div class="vkp-pay-bar-wrap">
                <div class="vkp-pay-fill" style="width:${pct}%;background:${payBarCol};">${pct}%</div>
            </div>
            <div class="vkp-pay-grid">
                <div class="vkp-pay-cell">
                    <div class="vkp-pay-label">Total</div>
                    <div class="vkp-pay-val" style="color:#0891b2;">₹${fmtNum(p.total_amount)}</div>
                </div>
                <div class="vkp-pay-cell">
                    <div class="vkp-pay-label">Paid</div>
                    <div class="vkp-pay-val" style="color:#16a34a;">₹${fmtNum(p.paid_amount)}</div>
                </div>
                <div class="vkp-pay-cell">
                    <div class="vkp-pay-label">Due</div>
                    <div class="vkp-pay-val" style="color:${dueClr};">₹${fmtNum(p.due_amount)}</div>
                </div>
            </div>
        </div>

        ${p.short_plan && p.short_plan !== 'N/A' ? `
        <div class="vkp-sec">
            <div class="vkp-sec-lbl">Package / Plan</div>
            <div class="vkp-plan">${esc(p.short_plan)}</div>
        </div>` : ''}
    `;

    const sbw = window.innerWidth - document.documentElement.clientWidth;
    document.getElementById('vkpBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = sbw + 'px';
}

function closeBookingPopup(e) {
    if (e.target !== document.getElementById('vkpBackdrop')) return;
    closeBookingPopupBtn();
}

function closeBookingPopupBtn() {
    document.getElementById('vkpBackdrop').classList.remove('open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

/* ── DRAWER ── */
function openDrawer(ev) {
    if (typeof ev === 'string') ev = JSON.parse(ev);
    const p   = ev.extendedProps || {};
    const vip = (p.total_amount||0) >= 15000;
    const sc  = STATUS_COLORS[p.status] || '#6b7280';
    const pct = Math.min(100, p.payment_percentage||0);
    const dueClr = (p.due_amount||0) > 0 ? '#d97706' : '#16a34a';

    document.getElementById('vkdNum').textContent   = p.booking_number || 'N/A';
    document.getElementById('vkdNum').style.color   = vip ? '#d97706' : '#0891b2';
    document.getElementById('vkdGuest').textContent = (vip?'★ ':'') + (p.guest_name||'—');
    document.getElementById('vkdViewBtn').href      = p.url || (p.booking_type === 'boat' ? '/admin/boat-booking/' + p.booking_number : p.booking_type === 'cab' ? '/admin/cab-bookings/' + String(ev.id).replace('cab_','') : '/admin/bookings/' + ev.id);

    const phone = (p.contact||'').replace(/[^0-9]/g,'');
    const waMsg = encodeURIComponent(`Namaste ${p.guest_name} ji 🙏\nBooking ${p.booking_number} on ${p.service_date} confirmed.\nPersons: ${p.pax} | Total: ₹${fmtNum(p.total_amount)}\n— Visit Kashi Team`);
    document.getElementById('vkdWaBtn').href = phone ? `https://wa.me/91${phone}?text=${waMsg}` : '#';

    let svcHtml = '';
    if (p.service_types && p.service_types.length) {
        svcHtml = p.service_types.map(t => {
            const c = t.color?.background || '#6366f1';
            return `<div class="vkd-svc-row"><div class="vkd-svc-dot" style="background:${c};"></div><span>${esc(t.name)}</span></div>`;
        }).join('');
    } else if (p.services && p.services !== 'No services') {
        svcHtml = `<div class="vkd-svc-row"><div class="vkd-svc-dot" style="background:#6366f1;"></div><span>${esc(p.services)}</span></div>`;
    }

    document.getElementById('vkdBody').innerHTML = `
        <div class="vkd-sec">
            <div class="vkd-lbl">Overview</div>
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;margin-bottom:9px;">
                <span class="vkd-status" style="background:${sc};">${esc(p.status||'—')}</span>
                <span style="font-size:12px;font-weight:600;color:#6b7280;">${esc(p.service_date||'—')}</span>
            </div>
            <div class="vkd-grid">
                <div class="vkd-cell"><div class="vkd-cell-l">Guest</div><div class="vkd-cell-v">${esc(p.guest_name||'—')}</div></div>
                <div class="vkd-cell"><div class="vkd-cell-l">Contact</div><div class="vkd-cell-v">${esc(p.contact||'—')}</div></div>
                <div class="vkd-cell"><div class="vkd-cell-l">Persons</div><div class="vkd-cell-v">${esc(String(p.pax||'—'))}</div></div>
                <div class="vkd-cell"><div class="vkd-cell-l">Booked by</div><div class="vkd-cell-v">${esc(p.created_by||'—')}</div></div>
            </div>
        </div>
        ${svcHtml ? `<div class="vkd-sec"><div class="vkd-lbl">Services</div>${svcHtml}</div>` : ''}
        <div class="vkd-sec">
            <div class="vkd-lbl">Payment</div>
            <div class="vkd-pay-bar-wrap"><div class="vkd-pay-fill" style="width:${pct}%;">${pct}%</div></div>
            <div class="vkd-pay-grid">
                <div class="vkd-pay-cell"><div class="vkd-pay-label">Total</div><div class="vkd-pay-val" style="color:#0891b2;">₹${fmtNum(p.total_amount)}</div></div>
                <div class="vkd-pay-cell"><div class="vkd-pay-label">Paid</div><div class="vkd-pay-val" style="color:#16a34a;">₹${fmtNum(p.paid_amount)}</div></div>
                <div class="vkd-pay-cell"><div class="vkd-pay-label">Due</div><div class="vkd-pay-val" style="color:${dueClr};">₹${fmtNum(p.due_amount)}</div></div>
            </div>
        </div>
        ${p.short_plan && p.short_plan !== 'N/A' ? `<div class="vkd-sec"><div class="vkd-lbl">Package</div><div style="font-size:13px;color:#374151;background:#f8fafc;border:1px solid #e5e7eb;border-radius:9px;padding:10px 12px;line-height:1.6;">${esc(p.short_plan)}</div></div>` : ''}
    `;

    const sbw = window.innerWidth - document.documentElement.clientWidth;
    document.getElementById('vkdBackdrop').classList.add('open');
    document.getElementById('vkdDrawer').classList.add('open');
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = sbw + 'px';
}

function closeDrawer() {
    document.getElementById('vkdBackdrop').classList.remove('open');
    document.getElementById('vkdDrawer').classList.remove('open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeBookingPopupBtn();
        closeDrawer();
    }
});

/* ── NAV ── */
function changeMonth(d) {
    curMonth += d;
    if (curMonth > 11) { curMonth = 0; curYear++; }
    if (curMonth < 0)  { curMonth = 11; curYear--; }
    loadMonth();
}
function goToday() {
    curYear = new Date().getFullYear();
    curMonth = new Date().getMonth();
    loadMonth();
}

/* ── HELPERS ── */
function dfmt(d) { return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`; }
function pad(n)  { return String(n).padStart(2,'0'); }
function fmtNum(n) {
    const v = Number(n)||0;
    if (v >= 100000) return (v/100000).toFixed(1).replace(/\.0$/,'')+'L';
    if (v >= 1000)   return (v/1000).toFixed(1).replace(/\.0$/,'')+'K';
    return v.toLocaleString('en-IN');
}
function set(id,v) { const el=document.getElementById(id); if(el) el.textContent=v; }
function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function fmtDisplay(ds) {
    return new Date(ds+'T00:00:00').toLocaleDateString('en-IN',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
}
function evColor(ev) {
    if ((ev.extendedProps?.status||'').toLowerCase() === 'cancelled') return '#EF4444';
    return ev.backgroundColor || '#6366F1';
}
</script>
@endsection
