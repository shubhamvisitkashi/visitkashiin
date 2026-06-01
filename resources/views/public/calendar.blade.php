<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="Cache-Control" content="no-cache,no-store,must-revalidate">
<title>Booking Calendar — Visit Kashi</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{background:#f4f6f8;min-height:100vh;font-family:'Inter',system-ui,sans-serif;display:flex;flex-direction:column;align-items:center;padding:20px 12px 40px;}

/* ── Top brand ── */
.vk-brand{display:flex;align-items:center;gap:10px;margin-bottom:16px;width:100%;max-width:820px;}
.vk-brand-logo{width:36px;height:36px;border-radius:50%;background:#7a3dec;display:flex;align-items:center;justify-content:center;}
.vk-brand-logo svg{width:18px;height:18px;stroke:#fff;}
.vk-brand-name{font-size:1rem;font-weight:800;color:#111827;}
.vk-brand-right{margin-left:auto;}
.vk-brand-right a{font-size:.78rem;color:#9ca3af;text-decoration:none;}

/* ── Card ── */
.vkbc-card{background:#fff;border-radius:16px;box-shadow:0 2px 20px rgba(0,0,0,.08);padding:20px 20px 24px;width:100%;max-width:820px;}

/* ── Date range bar ── */
.vkbc-range-bar{display:flex;align-items:flex-end;gap:12px;margin-bottom:20px;flex-wrap:wrap;}
.vkbc-range-field{flex:1;min-width:130px;}
.vkbc-range-label{font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;}
.vkbc-range-input{
    width:100%;height:38px;border:1.5px solid #d1d5db;border-radius:8px;
    padding:0 10px;font-size:13px;color:#374151;outline:none;
    font-family:inherit;background:#fff;
    transition:border-color .15s;
}
.vkbc-range-input:focus{border-color:#7a3dec;box-shadow:0 0 0 3px rgba(122,61,236,.1);}
.vkbc-book-btn{
    height:38px;padding:0 22px;
    background:#7a3dec;color:#fff;
    border:none;border-radius:8px;
    font-size:13px;font-weight:700;
    cursor:pointer;white-space:nowrap;
    transition:background .15s;flex-shrink:0;
}
.vkbc-book-btn:hover{background:#6d28d9;}

/* ── Month nav ── */
.vkbc-nav-bar{
    display:flex;align-items:center;
    gap:8px;margin-bottom:14px;
    flex-wrap:nowrap;
}
.vkbc-nav-arrow{
    width:32px;height:32px;border:1.5px solid #e5e7eb;
    border-radius:8px;background:#fff;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    color:#374151;font-size:17px;font-weight:700;
    transition:all .15s;flex-shrink:0;line-height:1;
}
.vkbc-nav-arrow:hover{background:#f3f4f6;border-color:#9ca3af;}
.vkbc-nav-arrow:active{transform:scale(.93);}
.vkbc-nav-title{
    font-size:.78rem;font-weight:600;color:#7a3dec;
    background:#f5f3ff;border:1px solid #ddd6fe;
    border-radius:6px;padding:4px 10px;white-space:nowrap;flex-shrink:0;
}
.vkbc-nav-month{font-size:1.1rem;font-weight:800;color:#111827;flex:1;text-align:center;white-space:nowrap;}
.vkbc-nav-count{font-size:.78rem;font-weight:700;color:#6b7280;background:#f3f4f6;border-radius:20px;padding:3px 10px;white-space:nowrap;flex-shrink:0;}

/* ── Day header ── */
.vkbc-days{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;margin-bottom:3px;}
.vkbc-day-hdr{text-align:center;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;padding:5px 0;}

/* ── Grid ── */
.vkbc-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;}

/* ── Cell ── */
.vkbc-cell{
    border-radius:8px;
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    cursor:pointer;
    padding:8px 2px 7px;
    min-height:70px;
    transition:transform .12s,box-shadow .12s,opacity .12s;
    position:relative;
    overflow:hidden;
    -webkit-tap-highlight-color:transparent;
}
.vkbc-cell:hover{transform:scale(1.06);box-shadow:0 6px 20px rgba(0,0,0,.16);z-index:3;}
.vkbc-cell:active{transform:scale(.95);}
.vkbc-cell.empty{background:transparent;cursor:default;pointer-events:none;}

/* ── Colour states ── */
.vkbc-cell.available  {background:#4caf50;}
.vkbc-cell.booked     {background:#e57373;}
.vkbc-cell.unavailable{background:#bdbdbd;}
.vkbc-cell.selected   {background:#2196f3;box-shadow:0 0 0 3px rgba(33,150,243,.35);transform:scale(1.07);}
.vkbc-cell.today:not(.selected){box-shadow:inset 0 0 0 2.5px rgba(255,255,255,.7);}

/* ── Cell content ── */
.vkbc-cell-date{font-size:1.3rem;font-weight:800;color:#fff;line-height:1;text-shadow:0 1px 2px rgba(0,0,0,.12);}
.vkbc-cell-lbl{font-size:8px;font-weight:700;color:rgba(255,255,255,.85);text-transform:uppercase;letter-spacing:.08em;margin-top:4px;line-height:1;}
/* Count badge top-right */
.vkbc-cell-count{
    position:absolute;top:5px;right:6px;
    background:rgba(0,0,0,.25);
    color:#fff;font-size:9px;font-weight:800;
    border-radius:10px;padding:1px 6px;
    line-height:1.5;white-space:nowrap;
}

/* ── Legend ── */
.vkbc-legend{display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:center;margin-top:18px;padding-top:16px;border-top:1px solid #f0f0f0;}
.vkbc-leg{display:flex;align-items:center;gap:7px;font-size:12px;color:#374151;font-weight:500;}
.vkbc-leg-dot{width:14px;height:14px;border-radius:3px;flex-shrink:0;}

/* ── Info bar ── */
.vkbc-info{margin-top:14px;padding:10px 14px;background:#f8fafc;border-radius:10px;border:1px solid #e5e7eb;font-size:13px;color:#374151;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
.vkbc-info strong{color:#111827;}

/* ── Detail panel ── */
.vkbc-detail{margin-top:14px;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;overflow:hidden;display:none;}
.vkbc-detail.open{display:block;}
.vkbc-detail-head{background:linear-gradient(135deg,#1a1a2e,#7a3dec);padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:8px;}
.vkbc-detail-title{font-size:.9rem;font-weight:700;color:#fff;}
.vkbc-detail-badge{background:rgba(255,255,255,.2);color:#fff;font-size:.7rem;font-weight:700;padding:2px 9px;border-radius:20px;}
.vkbc-detail-close{background:rgba(255,255,255,.15);border:none;color:#fff;width:26px;height:26px;border-radius:50%;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.vkbc-detail-close:hover{background:rgba(255,255,255,.28);}
.vkbc-bk-list{padding:10px 14px 12px;max-height:55vh;overflow-y:auto;}

/* Booking card */
.vkbc-bk{background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;margin-bottom:8px;border-left:4px solid #4caf50;}
.vkbc-bk:last-child{margin-bottom:0;}
.vkbc-bk.bk-reserved{border-left-color:#e57373;}
.vkbc-bk.bk-pending {border-left-color:#e88c1f;}
.vkbc-bk-name{font-size:.87rem;font-weight:800;color:#111827;display:flex;align-items:center;gap:6px;margin-bottom:5px;flex-wrap:wrap;}
.vkbc-bk-star{color:#f59e0b;}
.vkbc-bk-status{font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:auto;white-space:nowrap;flex-shrink:0;}
.bk-reserved .vkbc-bk-status{background:#fee2e2;color:#b91c1c;}
.bk-pending  .vkbc-bk-status{background:#fef3c7;color:#92400e;}
.vkbc-bk-row{display:flex;align-items:flex-start;gap:6px;font-size:.75rem;color:#6b7280;margin-bottom:3px;line-height:1.4;}
.vkbc-bk-row:last-child{margin-bottom:0;}
.vkbc-bk-row svg{flex-shrink:0;margin-top:1px;}
.vkbc-bk-row strong{color:#374151;font-weight:700;}
.vkbc-empty{text-align:center;padding:28px;color:#9ca3af;font-size:.84rem;}

/* ── Loading ── */
.vkbc-loading-cell{grid-column:span 7;text-align:center;padding:32px;color:#9ca3af;font-size:.84rem;}

/* ── Responsive ── */
@media(max-width:600px){
    body{padding:12px 8px 32px;}
    .vkbc-card{padding:14px 10px 18px;border-radius:14px;}
    .vkbc-range-bar{gap:8px;}
    .vkbc-cell{min-height:52px;border-radius:6px;padding:5px 1px 5px;}
    .vkbc-cell-date{font-size:1.05rem;}
    .vkbc-cell-lbl{font-size:7px;}
    .vkbc-nav-month{font-size:.95rem;}
    .vkbc-day-hdr{font-size:9.5px;}
    .vkbc-days,.vkbc-grid{gap:2px;}
    .vkbc-legend{gap:10px;}
    .vkbc-leg{font-size:11px;}
}
@media(max-width:400px){
    .vkbc-cell{min-height:42px;padding:4px 1px;}
    .vkbc-cell-date{font-size:.9rem;}
    .vkbc-cell-lbl{display:none;}
    .vkbc-nav-title{display:none;}
    .vkbc-nav-count{display:none;}
    .vkbc-days,.vkbc-grid{gap:1px;}
    .vkbc-range-field:last-of-type{display:none;}
}
</style>
</head>
<body>

{{-- Brand ── --}}
<div class="vk-brand">
    <div class="vk-brand-logo">
        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <div class="vk-brand-name">Visit Kashi</div>
    <div class="vk-brand-right"><a href="#">Booking Calendar</a></div>
</div>

{{-- Session Timer Bar --}}
<div style="width:100%;max-width:820px;margin-bottom:8px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
        <span style="font-size:.72rem;color:#6b7280;font-weight:600;">🔐 Session expires in</span>
        <span id="vkbc-session-label" style="font-size:.72rem;font-weight:800;color:#374151;">60s</span>
    </div>
    <div style="height:4px;background:#e5e7eb;border-radius:4px;overflow:hidden;">
        <div id="vkbc-session-bar" style="height:100%;width:100%;background:#4caf50;border-radius:4px;transition:width 1s linear,background .3s;"></div>
    </div>
</div>

<div class="vkbc-card">

    {{-- Date range + Book ── --}}
    <div class="vkbc-range-bar">
        <div class="vkbc-range-field">
            <div class="vkbc-range-label">Start Date</div>
            <input type="date" class="vkbc-range-input" id="vkRangeStart" placeholder="MM/DD/YYYY">
        </div>
        <div class="vkbc-range-field">
            <div class="vkbc-range-label">End Date</div>
            <input type="date" class="vkbc-range-input" id="vkRangeEnd" placeholder="MM/DD/YYYY">
        </div>
        <button class="vkbc-book-btn" onclick="highlightRange()">Book</button>
    </div>

    {{-- Month nav ── --}}
    <div class="vkbc-nav-bar">
        <button class="vkbc-nav-arrow" onclick="changeMonth(-1)">&#8249;</button>
        <div class="vkbc-nav-title" id="vkNavTitle">Title</div>
        <div class="vkbc-nav-month" id="vkNavMonth">Loading…</div>
        <div class="vkbc-nav-count" id="vkNavCount">—</div>
        <button class="vkbc-nav-arrow" onclick="changeMonth(1)">&#8250;</button>
    </div>

    {{-- Day headers ── --}}
    <div class="vkbc-days">
        <div class="vkbc-day-hdr">SUN</div>
        <div class="vkbc-day-hdr">MON</div>
        <div class="vkbc-day-hdr">TUE</div>
        <div class="vkbc-day-hdr">WED</div>
        <div class="vkbc-day-hdr">THU</div>
        <div class="vkbc-day-hdr">FRI</div>
        <div class="vkbc-day-hdr">SAT</div>
    </div>

    {{-- Grid ── --}}
    <div class="vkbc-grid" id="vkGrid">
        <div class="vkbc-loading-cell">Loading calendar…</div>
    </div>

    {{-- Legend ── --}}
    <div class="vkbc-legend">
        <div class="vkbc-leg"><div class="vkbc-leg-dot" style="background:#4caf50;"></div> Available</div>
        <div class="vkbc-leg"><div class="vkbc-leg-dot" style="background:#e57373;"></div> Reserved</div>
        <div class="vkbc-leg"><div class="vkbc-leg-dot" style="background:#bdbdbd;"></div> Unavailable</div>
        <div class="vkbc-leg"><div class="vkbc-leg-dot" style="background:#2196f3;"></div> Selected</div>
    </div>

    {{-- Info bar ── --}}
    <div class="vkbc-info" id="vkInfo" style="display:none;">
        <span>Date: <strong id="vkInfoDate">—</strong></span>
        <span>Bookings: <strong id="vkInfoCount">0</strong></span>
    </div>

    {{-- Detail panel ── --}}
    <div class="vkbc-detail" id="vkDetail">
        <div class="vkbc-detail-head">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <div class="vkbc-detail-title" id="vkDetailDate">—</div>
                <span class="vkbc-detail-badge" id="vkDetailCount">0 bookings</span>
            </div>
            <button class="vkbc-detail-close" onclick="closeDetail()">&#x2715;</button>
        </div>
        <div class="vkbc-bk-list" id="vkBkList"></div>
    </div>

</div>

<script>
var curYear  = new Date().getFullYear();
var curMonth = new Date().getMonth();
var byDate   = {};
var selected = null;
var todayStr = new Date().toISOString().split('T')[0];
var MONTHS   = ['January','February','March','April','May','June','July','August','September','October','November','December'];

function esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function pad(n){ return String(n).padStart(2,'0'); }
function fmtDate(ds){
    var d = new Date(ds+'T00:00:00');
    return d.toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric'});
}
function fmtNum(n){ return '₹' + parseFloat(n||0).toLocaleString('en-IN'); }

async function loadMonth(){
    var last  = new Date(curYear, curMonth+1, 0);
    var start = `${curYear}-${pad(curMonth+1)}-01`;
    var end   = `${curYear}-${pad(curMonth+1)}-${pad(last.getDate())}`;

    document.getElementById('vkNavMonth').textContent = MONTHS[curMonth] + ' ' + curYear;
    document.getElementById('vkNavTitle').textContent = 'Title';
    document.getElementById('vkGrid').innerHTML = '<div class="vkbc-loading-cell">Loading…</div>';

    try {
        var url = new URL('{{ route('public.calendar.events') }}', window.location.origin);
        url.searchParams.set('start', start);
        url.searchParams.set('end', end);
        var events = await fetch(url).then(r => r.json());

        byDate = {};
        events.forEach(ev => {
            var d = (ev.start||'').split('T')[0];
            if (!byDate[d]) byDate[d] = [];
            byDate[d].push(ev);
        });

        var total = events.length;
        document.getElementById('vkNavCount').textContent = total + ' booking' + (total!==1?'s':'');
        renderGrid();
    } catch(e) {
        document.getElementById('vkGrid').innerHTML =
            '<div class="vkbc-loading-cell" style="color:#ef4444;">Failed to load. <button onclick="loadMonth()" style="color:#7a3dec;background:none;border:none;cursor:pointer;font-weight:700;">Retry</button></div>';
    }
}

function renderGrid(){
    var firstDow  = new Date(curYear, curMonth, 1).getDay(); // 0=Sun
    var daysInMonth = new Date(curYear, curMonth+1, 0).getDate();
    var todayDate = new Date(); todayDate.setHours(0,0,0,0);

    var html = '';
    // Empty cells (Sunday-first)
    for (var i=0; i<firstDow; i++) html += '<div class="vkbc-cell empty"></div>';

    for (var d=1; d<=daysInMonth; d++) {
        var ds   = `${curYear}-${pad(curMonth+1)}-${pad(d)}`;
        var evs  = byDate[ds] || [];
        var isPast = new Date(ds+'T00:00:00') < todayDate && ds !== todayStr;
        var isSel  = ds === selected;

        // Determine state — bookings always override past/available colour
        var state, lbl, countHtml = '';
        if (isSel) {
            state = 'selected';
            lbl   = evs.length > 0 ? evs.length + ' Booking' + (evs.length > 1 ? 's' : '') : 'Selected';
        } else if (evs.length > 0) {
            // Any date with bookings → red #e57373
            state = 'booked';
            lbl   = evs.length + ' Booking' + (evs.length > 1 ? 's' : '');
        } else if (isPast) {
            state = 'unavailable'; lbl = 'Unavailable';
        } else {
            state = 'available'; lbl = 'Available';
        }

        // Count badge for booked dates
        if (evs.length > 0 && state !== 'selected') {
            countHtml = `<div class="vkbc-cell-count">${evs.length}</div>`;
        }

        var todayCls = ds === todayStr ? ' today' : '';

        html += `<div class="vkbc-cell ${state}${todayCls}" onclick="selectDate('${ds}')">
            ${countHtml}
            <div class="vkbc-cell-date">${d}</div>
            <div class="vkbc-cell-lbl">${lbl}</div>
        </div>`;
    }

    // Fill trailing empties
    var total = firstDow + daysInMonth;
    var remainder = total % 7;
    if (remainder > 0) for (var j=0; j<7-remainder; j++) html += '<div class="vkbc-cell empty"></div>';

    document.getElementById('vkGrid').innerHTML = html;
}

function selectDate(ds){
    selected = ds;
    renderGrid();

    var evs = byDate[ds] || [];
    document.getElementById('vkInfo').style.display = 'flex';
    document.getElementById('vkInfoDate').textContent  = fmtDate(ds);
    document.getElementById('vkInfoCount').textContent = evs.length;

    if (evs.length === 0) {
        document.getElementById('vkDetail').classList.remove('open');
        return;
    }

    document.getElementById('vkDetailDate').textContent  = fmtDate(ds);
    document.getElementById('vkDetailCount').textContent = evs.length + ' booking' + (evs.length>1?'s':'');

    var rows = evs.map(ev => {
        var p = ev.extendedProps || {};
        var st  = (p.status||'').toLowerCase();
        var cls = (st.includes('confirm')||st.includes('complet')) ? 'bk-reserved' : 'bk-pending';
        var isVip = (p.total_amount||0) >= 15000;
        var due  = parseFloat(p.pending_amount||0);
        var paid = parseFloat(p.paid_amount||0);
        var svc  = p.service || p.services || '—';

        var payLine = '';
        if (due > 0) {
            payLine = `<div class="vkbc-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#ef4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color:#ef4444;font-weight:700;">Due: ${fmtNum(due)}</span>
            </div>`;
        } else if (paid > 0) {
            payLine = `<div class="vkbc-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#16a34a" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="color:#16a34a;font-weight:700;">Paid</span>
            </div>`;
        }

        return `<div class="vkbc-bk ${cls}">
            <div class="vkbc-bk-name">
                ${isVip?'<span class="vkbc-bk-star">★</span>':''}
                ${esc(p.guest_name||'Guest')}
                <span class="vkbc-bk-status">${esc(p.status||'')}</span>
            </div>
            ${p.contact?`<div class="vkbc-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <strong>${esc(p.contact)}</strong>
            </div>`:''}
            <div class="vkbc-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#0891b2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                ${esc(svc)}${p.pax?' · '+p.pax+' person'+(p.pax>1?'s':''):''}
            </div>
            ${payLine}
            <div class="vkbc-bk-row">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#9ca3af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Added by: <strong>${esc(p.added_by||'—')}</strong>
            </div>
        </div>`;
    }).join('');

    document.getElementById('vkBkList').innerHTML = rows;
    document.getElementById('vkDetail').classList.add('open');
    document.getElementById('vkDetail').scrollIntoView({behavior:'smooth',block:'nearest'});
}

function closeDetail(){
    document.getElementById('vkDetail').classList.remove('open');
    document.getElementById('vkInfo').style.display = 'none';
    selected = null;
    renderGrid();
}

function changeMonth(dir){
    curMonth += dir;
    if (curMonth > 11){ curMonth=0; curYear++; }
    if (curMonth < 0) { curMonth=11; curYear--; }
    selected = null;
    closeDetail();
    loadMonth();
}

function highlightRange(){
    var s = document.getElementById('vkRangeStart').value;
    var e = document.getElementById('vkRangeEnd').value;
    if (s) {
        var sd = new Date(s);
        curYear  = sd.getFullYear();
        curMonth = sd.getMonth();
        selected = s;
        closeDetail();
        loadMonth();
    }
}

loadMonth();

/* ── Session countdown & auto-redirect ── */
(function(){
    var TTL      = {{ $remaining ?? 120 }};   // seconds remaining from server
    var deadline = Date.now() + TTL * 1000;
    var bar      = document.getElementById('vkbc-session-bar');
    var label    = document.getElementById('vkbc-session-label');
    var total    = TTL;

    function tick() {
        var left = Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
        if (bar)   {
            var pct = (left / total) * 100;
            bar.style.width = pct + '%';
            bar.style.background = left > 20 ? '#4caf50' : left > 10 ? '#f59e0b' : '#ef4444';
        }
        if (label) label.textContent = left + 's';
        if (left <= 0) {
            // Session expired — redirect to PIN gate
            window.location.href = '{{ route('public.calendar') }}';
        }
    }

    tick();
    setInterval(tick, 1000);
})();
</script>
</body>
</html>
