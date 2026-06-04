@extends('admin.layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style>
:root{
  --h-bg:#EEF2FF;--h-card:#fff;--h-border:#E2E8F0;
  --h-shadow:0 1px 3px rgba(15,23,42,.06),0 4px 16px rgba(15,23,42,.04);
  --h-text:#0F172A;--h-sub:#475569;--h-muted:#94A3B8;
  --h-indigo:#4F46E5;--h-emerald:#10B981;--h-amber:#F59E0B;
  --h-rose:#EF4444;--h-sky:#0EA5E9;--h-violet:#7C3AED;
  --h-r:14px;--h-t:0.2s ease;
}
.cb-page{background:var(--h-bg);min-height:100vh;padding:24px;}

.cb-header{background:linear-gradient(135deg,var(--h-indigo) 0%,var(--h-violet) 100%);
  border-radius:var(--h-r);padding:20px 26px;
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:24px;overflow:hidden;position:relative;}
.cb-header::before{content:'';position:absolute;top:-30px;right:-30px;
  width:150px;height:150px;background:rgba(255,255,255,.07);border-radius:50%;}
.cb-header h1{color:#fff;font-size:1.3rem;font-weight:700;margin:0;position:relative;z-index:1;}
.cb-header p{color:rgba(255,255,255,.72);font-size:.8rem;margin:.2rem 0 0;position:relative;z-index:1;}
.cb-back{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.18);
  color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:8px;
  padding:8px 14px;font-size:.8rem;font-weight:600;text-decoration:none;transition:background var(--h-t);
  position:relative;z-index:1;}
.cb-back:hover{background:rgba(255,255,255,.28);color:#fff;}

.cb-step{background:var(--h-card);border-radius:var(--h-r);border:1px solid var(--h-border);
  box-shadow:var(--h-shadow);margin-bottom:20px;overflow:hidden;}
.cb-step-head{padding:14px 22px;border-bottom:1px solid var(--h-border);display:flex;align-items:center;gap:10px;}
.cb-step-num{width:26px;height:26px;border-radius:8px;background:var(--h-indigo);
  color:#fff;font-size:.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;}
.cb-step-title{font-size:.9rem;font-weight:700;color:var(--h-text);}
.cb-step-sub{font-size:.73rem;color:var(--h-muted);margin:.1rem 0 0;}

.type-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;padding:20px;}
@media(max-width:768px){.type-grid{grid-template-columns:repeat(2,1fr);}}
.type-card{border:2.5px solid var(--tc-border,#E2E8F0);border-radius:16px;
  padding:24px 16px 18px;text-align:center;cursor:pointer;
  transition:all .22s ease;background:#fff;position:relative;user-select:none;}
.type-card:hover{border-color:var(--tc-color);background:var(--tc-bg);transform:translateY(-4px);box-shadow:0 8px 24px rgba(0,0,0,.10);}
.type-card.active{border-color:var(--tc-color);background:var(--tc-bg);box-shadow:0 0 0 4px rgba(var(--tc-rgb),.15);}
.type-card .tc-check{position:absolute;top:10px;right:10px;width:20px;height:20px;
  background:var(--tc-color);border-radius:50%;display:none;align-items:center;justify-content:center;}
.type-card.active .tc-check{display:flex;}
.tc-check svg{width:11px;height:11px;stroke:#fff;}
.tc-icon{width:60px;height:60px;border-radius:16px;background:var(--tc-bg);margin:0 auto 12px;
  display:flex;align-items:center;justify-content:center;transition:background .2s;}
.type-card:hover .tc-icon,.type-card.active .tc-icon{background:var(--tc-color);}
.tc-icon svg{width:28px;height:28px;stroke:var(--tc-color);transition:stroke .2s;}
.type-card:hover .tc-icon svg,.type-card.active .tc-icon svg{stroke:#fff;}
.tc-label{font-size:.9rem;font-weight:700;color:var(--h-text);}
.tc-desc{font-size:.72rem;color:var(--h-muted);margin:.4rem 0 0;line-height:1.4;}

.cb-forms{display:none;}
.cb-form-section{display:none;}
.cb-form-section.active{display:block;}

/* Legacy form cards (Stay / Cab / Tour) */
.fs-card{background:var(--h-card);border-radius:var(--h-r);border:1px solid var(--h-border);
  box-shadow:var(--h-shadow);margin-bottom:18px;overflow:hidden;}
.fs-head{padding:13px 20px;border-bottom:1px solid var(--h-border);display:flex;align-items:center;gap:10px;
  background:linear-gradient(90deg,rgba(79,70,229,.04) 0%,transparent 100%);}
.fs-icon{width:32px;height:32px;border-radius:9px;background:var(--fi-color,var(--h-indigo));
  display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.fs-icon svg{width:15px;height:15px;stroke:#fff;}
.fs-title{font-size:.84rem;font-weight:700;color:var(--h-text);}
.fs-body{padding:20px;}
.f-label{font-size:.73rem;font-weight:700;color:var(--h-sub);text-transform:uppercase;
  letter-spacing:.4px;margin-bottom:5px;display:block;}
.f-label .req{color:var(--h-rose);margin-left:2px;}
.f-ctrl{border:1.5px solid var(--h-border) !important;border-radius:9px !important;
  padding:9px 13px !important;font-size:.875rem !important;color:var(--h-text) !important;
  background:#FAFBFF !important;transition:border-color var(--h-t),box-shadow var(--h-t) !important;}
.f-ctrl:focus{border-color:var(--h-indigo) !important;box-shadow:0 0 0 3px rgba(79,70,229,.12) !important;
  background:#fff !important;outline:none !important;}
.price-bar{background:linear-gradient(135deg,#F0FDF4,#DCFCE7);border:1px solid #BBF7D0;
  border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:24px;flex-wrap:wrap;}
.price-bar-item{text-align:center;}
.price-bar-item .pbi-label{font-size:.67rem;font-weight:700;color:#065F46;text-transform:uppercase;letter-spacing:.4px;}
.price-bar-item .pbi-val{font-size:1.4rem;font-weight:800;color:#14532D;}
.svc-pick-row{background:#F8FAFF;border:1.5px solid var(--h-border);border-radius:10px;padding:14px 16px;}
.amt-words{background:linear-gradient(135deg,#FEF3C7,#FDE68A);border-left:4px solid #F59E0B;
  border-radius:10px;padding:12px 16px;color:#92400E;font-size:.82rem;font-weight:500;}

.btn-submit{display:inline-flex;align-items:center;gap:8px;
  background:linear-gradient(135deg,var(--h-indigo) 0%,var(--h-violet) 100%);
  color:#fff;border:none;border-radius:10px;
  padding:12px 28px;font-size:.95rem;font-weight:700;
  cursor:pointer;transition:opacity var(--h-t),transform var(--h-t);}
.btn-submit:hover{opacity:.88;transform:translateY(-1px);color:#fff;}
.btn-submit svg{width:17px;height:17px;stroke:#fff;}

/* ══════════════════════════════════
   PREMIUM BOAT FORM — NB Design
══════════════════════════════════ */
.nb-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:1140px){.nb-layout{grid-template-columns:1fr;padding-bottom:80px;}}

.nb-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;
  box-shadow:0 1px 3px rgba(0,0,0,.05),0 2px 8px rgba(0,0,0,.04);
  margin-bottom:16px;overflow:hidden;}
.nb-card-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;
  display:flex;align-items:center;gap:12px;background:#FAFBFF;}
.nb-card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.nb-card-title{font-size:.87rem;font-weight:700;color:#0F172A;}
.nb-card-body{padding:20px;}

.nb-label{font-size:.72rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px;display:block;}
.nb-req{color:#EF4444;margin-left:2px;}
.nb-input{border:1.5px solid #E2E8F0 !important;border-radius:10px !important;
  padding:9px 13px !important;font-size:.875rem !important;color:#0F172A !important;
  background:#FAFBFF !important;transition:all .2s ease !important;width:100%;}
.nb-input:focus{border-color:#0EA5E9 !important;box-shadow:0 0 0 3px rgba(14,165,233,.12) !important;
  background:#fff !important;outline:none !important;}

.nb-phone-wrap{position:relative;}
.nb-phone-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);
  font-size:.8rem;font-weight:600;color:#64748B;z-index:2;pointer-events:none;}
.nb-input-phone{padding-left:40px !important;}

/* Boat Cards */
.boat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
@media(max-width:700px){
  .boat-grid{display:flex;gap:10px;overflow-x:auto;padding-bottom:8px;scroll-snap-type:x mandatory;}
  .boat-grid::-webkit-scrollbar{height:4px;}
  .boat-grid::-webkit-scrollbar-thumb{background:#CBD5E1;border-radius:4px;}
}
.boat-card{border:2px solid #E8ECF4;border-radius:14px;padding:16px 12px;
  cursor:pointer;text-align:center;transition:all .22s ease;
  background:#fff;position:relative;user-select:none;flex-shrink:0;scroll-snap-align:start;}
@media(max-width:700px){.boat-card{min-width:148px;}}
.boat-card:hover{border-color:#0EA5E9;background:#F0F9FF;transform:translateY(-3px);
  box-shadow:0 6px 20px rgba(14,165,233,.15);}
.boat-card.selected{border-color:#0EA5E9;background:#F0F9FF;
  box-shadow:0 0 0 3px rgba(14,165,233,.22);}
.boat-card-check{position:absolute;top:8px;right:8px;width:20px;height:20px;
  background:#0EA5E9;border-radius:50%;font-size:.68rem;color:#fff;
  display:none;align-items:center;justify-content:center;font-weight:700;}
.boat-card.selected .boat-card-check{display:flex;}
.boat-card-emoji{font-size:2rem;margin-bottom:8px;line-height:1;}
.boat-card-name{font-size:.78rem;font-weight:700;color:#0F172A;line-height:1.3;margin-bottom:4px;}
.boat-card-cap{font-size:.67rem;color:#94A3B8;margin-bottom:6px;}
.boat-card-price{font-size:.95rem;font-weight:800;color:#0EA5E9;}
.boat-card-extra{font-size:.64rem;color:#64748B;margin-top:2px;}

/* Person counter */
.nb-counter-wrap{display:flex;align-items:center;gap:16px;}
.nb-counter-btn{width:38px;height:38px;border-radius:50%;border:2px solid #E2E8F0;
  background:#fff;font-size:1.1rem;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:all .2s;color:#475569;font-weight:700;padding:0;flex-shrink:0;line-height:1;}
.nb-counter-btn:hover{border-color:#0EA5E9;color:#0EA5E9;background:#F0F9FF;}
.nb-counter-val{font-size:1.5rem;font-weight:800;min-width:40px;text-align:center;color:#0F172A;}
.nb-counter-info{font-size:.73rem;color:#94A3B8;}

.nb-pax-price{background:#F8FAFF;border:1px solid #E8ECF4;border-radius:10px;padding:12px 16px;margin-top:14px;}
.nb-pax-row{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:#475569;padding:4px 0;}
.nb-pax-row+.nb-pax-row{border-top:1px dashed #E8ECF4;}
.nb-pax-total{font-weight:700 !important;color:#0F172A !important;font-size:.85rem !important;}

.nb-warning{background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;
  padding:8px 12px;color:#EF4444;font-size:.78rem;margin-top:10px;}

/* Sidebar */
.nb-sidebar{position:sticky;top:20px;}
.nb-sidebar-card{background:#fff;border-radius:16px;border:1px solid #E8ECF4;
  box-shadow:0 1px 3px rgba(0,0,0,.05),0 2px 8px rgba(0,0,0,.04);
  padding:20px;margin-bottom:16px;}
.nb-sidebar-head{display:flex;align-items:center;gap:10px;margin-bottom:16px;
  padding-bottom:14px;border-bottom:1px solid #F1F5F9;}
.nb-amount-row{margin-bottom:14px;}
.nb-rupee-wrap{position:relative;}
.nb-rupee{position:absolute;left:12px;top:50%;transform:translateY(-50%);
  font-size:.85rem;font-weight:600;color:#475569;z-index:2;pointer-events:none;}
.nb-rupee-input{padding-left:28px !important;}

.nb-balance-box{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);
  border:1px solid #BFDBFE;border-radius:12px;padding:14px 16px;margin:16px 0;}
.nb-balance-label{font-size:.63rem;font-weight:800;letter-spacing:.6px;text-transform:uppercase;color:#1E40AF;margin-bottom:6px;}
.nb-balance-row{display:flex;align-items:center;justify-content:space-between;gap:10px;}
.nb-balance-amount{font-size:1.8rem;font-weight:800;color:#1E3A8A;line-height:1;}
.nb-balance-badge{background:#F59E0B;color:#fff;font-size:.62rem;font-weight:800;
  letter-spacing:.4px;padding:3px 8px;border-radius:20px;text-transform:uppercase;}
.nb-balance-badge.paid{background:#10B981;}
.nb-balance-badge.due{background:#EF4444;}
.nb-balance-badge.partial{background:#3B82F6;}

.nb-staff-card{background:linear-gradient(135deg,#FFFBEB,#FEF3C7);
  border:1.5px dashed #F59E0B;border-radius:16px;padding:16px;margin-bottom:16px;}
.nb-staff-head{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.nb-not-confirm-badge{margin-left:auto;background:#FEE2E2;color:#EF4444;
  font-size:.6rem;font-weight:700;padding:2px 7px;border-radius:10px;text-transform:uppercase;letter-spacing:.3px;}
.nb-margin-box{background:rgba(255,255,255,.7);border:1px solid #FDE68A;border-radius:10px;padding:12px 14px;margin-top:8px;}
.nb-margin-label{font-size:.63rem;font-weight:800;letter-spacing:.5px;color:#92400E;margin-bottom:4px;text-transform:uppercase;}
.nb-margin-row{display:flex;align-items:center;justify-content:space-between;}
.nb-margin-amount{font-size:1.2rem;font-weight:800;color:#78350F;}
.nb-margin-pct{font-size:.85rem;font-weight:700;color:#92400E;background:#FDE68A;padding:2px 8px;border-radius:6px;}

/* Payment Summary card in sidebar */
.nb-pay-sum{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:0;margin:14px 0;overflow:hidden;}
.nb-pay-sum-head{background:linear-gradient(135deg,#0F172A,#1E293B);padding:10px 14px;display:flex;align-items:center;gap:8px;color:#fff;font-size:.78rem;font-weight:700;letter-spacing:.02em;}
.nb-pay-sum-body{padding:12px 14px;}
.nb-pay-sum-row{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;padding:5px 0;}
.nb-pay-sum-row+.nb-pay-sum-row{border-top:1px dashed #E8ECF4;}
.nb-pay-sum-lbl{color:#64748B;font-weight:500;}
.nb-pay-sum-val{font-weight:700;color:#0F172A;}
.nb-pay-sum-formula{font-size:.68rem;color:#94A3B8;text-align:right;margin:-3px 0 5px;padding-bottom:7px;border-bottom:1px dashed #E8ECF4;}
.nb-pay-sum-balance{font-weight:800;font-size:.95rem;color:#EF4444;}
.nb-pay-sum-advance{color:#10B981;}
.nb-pay-sum-bar-wrap{margin-top:10px;padding-top:10px;border-top:1px solid #F1F5F9;}
.nb-pay-sum-bar-lbl{display:flex;justify-content:space-between;font-size:.68rem;color:#94A3B8;margin-bottom:4px;}
.nb-pay-sum-bar-track{height:6px;background:#E2E8F0;border-radius:99px;overflow:hidden;}
.nb-pay-sum-bar-fill{height:100%;background:linear-gradient(90deg,#10B981,#059669);border-radius:99px;width:0%;transition:width .4s ease;}

/* Mobile sticky CTA */
.nb-mobile-cta{display:none;}
@media(max-width:1140px){
  .nb-mobile-cta{display:flex;position:fixed;bottom:0;left:0;right:0;z-index:999;
    background:#fff;border-top:1px solid #E8ECF4;padding:12px 16px;
    align-items:center;gap:12px;box-shadow:0 -4px 16px rgba(0,0,0,.08);}
  .nb-submit-desktop{display:flex !important;width:100%;justify-content:center;}
  .nb-sidebar{position:static;display:block;}
  .nb-sidebar-card,.nb-staff-card{border-radius:16px;}
  .nb-mobile-cta{display:none !important;}
}
.nb-mobile-balance{flex:1;}
.nb-mobile-balance span{font-size:.68rem;color:#64748B;display:block;text-transform:uppercase;font-weight:600;letter-spacing:.4px;}
.nb-mobile-balance strong{font-size:1.1rem;font-weight:800;color:#0F172A;}

/* ── Hotel / Property Cards ── */
.hotel-card {
  border:2px solid #E8ECF4; border-radius:14px; padding:14px 12px;
  cursor:pointer; text-align:center; transition:all .22s ease;
  background:#fff; position:relative; user-select:none;
}
.hotel-card:hover { border-color:#10B981; background:#F0FDF4; transform:translateY(-3px); box-shadow:0 6px 20px rgba(16,185,129,.15); }
.hotel-card.selected { border-color:#10B981; background:#F0FDF4; box-shadow:0 0 0 3px rgba(16,185,129,.22); }
.hotel-card-check {
  position:absolute; top:8px; right:8px; width:20px; height:20px;
  background:#10B981; border-radius:50%; font-size:.68rem; color:#fff;
  display:none; align-items:center; justify-content:center; font-weight:700;
}
.hotel-card.selected .hotel-card-check { display:flex; }
.hotel-card-icon { font-size:1.8rem; margin-bottom:6px; line-height:1; }
.hotel-card-name { font-size:.76rem; font-weight:700; color:#0F172A; line-height:1.3; margin-bottom:4px; }
.hotel-card-price { font-size:.92rem; font-weight:800; color:#10B981; }
.hotel-card-per { font-size:.65rem; font-weight:500; color:#94A3B8; }
.hotel-card-custom { border-style:dashed; border-color:#C4B5FD; }
.hotel-card-custom:hover { border-color:#7C3AED; background:#EDE9FE; }
.hotel-card-custom.selected { border-color:#7C3AED; background:#EDE9FE; box-shadow:0 0 0 3px rgba(124,58,237,.18); }
.hotel-card-custom.selected .hotel-card-check { background:#7C3AED; }
.hotel-card-del {
  position:absolute; top:7px; left:7px;
  width:18px; height:18px; border-radius:50%;
  background:#FEE2E2; color:#DC2626; border:none;
  font-size:.7rem; font-weight:800; line-height:1;
  display:none; align-items:center; justify-content:center;
  cursor:pointer; z-index:5; padding:0;
  transition:background .12s;
}
.hotel-card.selected .hotel-card-del { display:flex; }
.hotel-card-del:hover { background:#DC2626; color:#fff; }

/* ── Stay Adults/Kids counter ── */
.stay-counter-wrap{display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:6px 8px;margin-top:2px;}
.stay-counter-btn{width:30px;height:30px;border-radius:8px;border:1.5px solid #CBD5E1;background:#fff;color:#475569;font-size:1.1rem;font-weight:700;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;}
.stay-counter-btn:hover{border-color:#0EA5E9;color:#0EA5E9;background:#F0F9FF;}
.stay-counter-val{font-size:1.25rem;font-weight:800;min-width:28px;text-align:center;color:#0F172A;}

/* ── Mobile overrides ── */
@media(max-width:768px){
  .cb-page{padding:10px;}
  .cb-header{padding:14px 16px;margin-bottom:14px;flex-wrap:wrap;gap:10px;margin-top:60px;}
  .cb-header h1{font-size:1.05rem;}
  .cb-header p{font-size:.72rem;}
  .cb-back{padding:6px 11px;font-size:.74rem;}
  .type-grid{padding:14px;gap:10px;}
  .tc-icon{width:48px;height:48px;}
  .tc-label{font-size:.8rem;}
  .tc-desc{display:none;}
  .type-card{padding:16px 10px 12px;border-radius:12px;}
  .cb-step-head{padding:12px 14px;flex-wrap:wrap;gap:8px;}
  .cb-step-head button{font-size:.7rem;padding:5px 10px;}

  /* nb-card-body improvements */
  .nb-card-body{padding:14px 12px;}
  .nb-card-head{padding:12px 14px;gap:8px;}
  .nb-card-icon{width:28px;height:28px;border-radius:8px;flex-shrink:0;}
  .nb-card-title{font-size:.82rem;}
  .nb-card{border-radius:12px;margin-bottom:12px;}

  /* form fields inside nb-card-body */
  .nb-label{font-size:.68rem;margin-bottom:4px;}
  .nb-input{padding:8px 11px !important;font-size:.82rem !important;border-radius:8px !important;}
  .row.g-3{--bs-gutter-x:10px;--bs-gutter-y:10px;}
  .row.g-3>[class*=col-md]{padding-left:5px;padding-right:5px;}

  .boat-grid{grid-template-columns:1fr 1fr;display:grid;overflow:visible;}
  .boat-card{min-width:unset;padding:12px 8px;}
  .boat-card-emoji{font-size:1.5rem;margin-bottom:6px;}
  .boat-card-name{font-size:.72rem;}
  .boat-card-cap{font-size:.62rem;}
  .boat-card-price{font-size:.85rem;}
  .boat-card-extra{font-size:.6rem;}

  .nb-counter-wrap{gap:10px;}
  .nb-counter-val{font-size:1.3rem;min-width:32px;}
  .nb-counter-btn{width:34px;height:34px;}
  .nb-counter-info{font-size:.68rem;}

  .nb-layout{gap:12px;}
  .nb-pax-price{padding:10px 12px;}
  .nb-pax-row{font-size:.75rem;}
  .nb-mobile-cta .btn-submit{padding:9px 16px;font-size:.82rem;}

  /* sidebar inside nb-card-body on mobile */
  .nb-sidebar-card{padding:14px 12px;border-radius:12px;}
  .nb-sidebar-head{gap:8px;margin-bottom:12px;padding-bottom:10px;}
  .nb-balance-amount{font-size:1.5rem;}
  .nb-pay-sum-body{padding:10px 12px;}
  .nb-pay-sum-row{font-size:.75rem;}
  .stay-counter-wrap{padding:5px 7px;}
  .stay-counter-btn{width:28px;height:28px;font-size:1rem;}
  .stay-counter-val{font-size:1.1rem;min-width:24px;}
}

@media(max-width:480px){
  .cb-page{padding:8px;}
  .type-grid{grid-template-columns:repeat(2,1fr);padding:10px;gap:8px;}
  .type-card{padding:14px 8px 10px;}
  .tc-icon{width:40px;height:40px;}
  .tc-icon svg{width:20px;height:20px;}
  .boat-grid{grid-template-columns:1fr 1fr;gap:8px;}

  /* nb-card-body compact */
  .nb-card-body{padding:10px;}
  .nb-card-head{padding:10px 12px;}
  .nb-card{border-radius:10px;margin-bottom:10px;}
  .nb-input{padding:7px 10px !important;font-size:.8rem !important;}
  .nb-label{font-size:.65rem;}

  .nb-pax-sections{flex-direction:row !important;gap:16px !important;flex-wrap:nowrap !important;}
  .nb-pax-sections>div{flex:1;min-width:0;}
  .nb-pax-divider{display:block !important;}
  .nb-counter-val{font-size:1.1rem;}
  .nb-counter-btn{width:32px;height:32px;font-size:1rem;}
  .row.g-3>[class*=col-md]{margin-bottom:0;}
  .cb-step-head .cb-step-sub{display:none;}

  .nb-sidebar-card{padding:10px;}
  .nb-balance-amount{font-size:1.3rem;}
  .stay-counter-btn{width:26px;height:26px;}
  .hotel-card{padding:10px 8px;border-radius:10px;}
  .hotel-card-icon{font-size:1.5rem;margin-bottom:4px;}
  .hotel-card-name{font-size:.7rem;}
  .hotel-card-price{font-size:.82rem;}
}

@media(max-width:360px){
  .nb-card-body{padding:8px;}
  .nb-card-head{padding:8px 10px;}
  .nb-input{padding:6px 9px !important;font-size:.78rem !important;}
  .nb-label{font-size:.63rem;}
  .nb-counter-btn{width:30px;height:30px;font-size:.95rem;}
  .nb-counter-val{font-size:1rem;}
  .boat-card{padding:10px 6px;}
  .boat-card-emoji{font-size:1.3rem;}
  .hotel-card{padding:8px 6px;}
}

/* Hotel cards grid responsive */
.hotel-cards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-bottom:12px;}
@media(max-width:600px){.hotel-cards-grid{grid-template-columns:repeat(2,1fr);gap:8px;}}
@media(max-width:360px){.hotel-cards-grid{grid-template-columns:1fr;gap:6px;}}

/* ══ Tour Package Service Blocks ══════════════════════════════ */
.tp-svc-block{padding:14px 0;}
.tp-svc-header{display:flex;align-items:center;gap:10px;margin-bottom:10px;cursor:pointer;}
.tp-svc-toggle{display:flex;align-items:center;cursor:pointer;margin:0;}
.tp-svc-toggle input[type=checkbox]{display:none;}
.tp-svc-dot{width:14px;height:14px;border-radius:50%;border:2px solid #E2E8F0;transition:all .2s;flex-shrink:0;}
.tp-svc-toggle input:checked + .tp-svc-dot{border-color:transparent;box-shadow:0 0 0 3px rgba(16,185,129,.2);}
.tp-svc-icon{font-size:1.1rem;line-height:1;}
.tp-svc-name{font-size:.85rem;font-weight:700;color:#0F172A;flex:1;}
.tp-svc-cost{font-size:.82rem;font-weight:700;color:#7C3AED;background:#EDE9FE;padding:2px 10px;border-radius:20px;}
.tp-svc-body{background:#FAFBFF;border:1.5px solid #E2E8F0;border-radius:12px;padding:14px 16px;transition:all .2s;}
.tp-svc-body.disabled{opacity:.4;pointer-events:none;}
.tp-divider{height:1px;background:#F1F5F9;margin:4px 0;}

/* Expense total bar */
.tp-expense-total{display:flex;align-items:center;gap:8px;flex-wrap:wrap;background:linear-gradient(135deg,#FFFBEB,#FEF3C7);border:1px solid #FDE68A;border-radius:12px;padding:12px 16px;margin-top:16px;}
.tp-exp-item{display:flex;flex-direction:column;align-items:center;gap:2px;font-size:.7rem;color:#92400E;}
.tp-exp-item span:first-child{font-size:.85rem;}
.tp-exp-item strong{font-size:.88rem;font-weight:800;color:#78350F;}
.tp-exp-total strong{font-size:1rem;color:#1E40AF;}
.tp-exp-total{background:rgba(255,255,255,.6);border-radius:8px;padding:4px 10px;}
.tp-exp-plus{font-size:.9rem;font-weight:700;color:#D97706;}
</style>

<div class="cb-page">

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
      <strong>Please fix:</strong>
      <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4">
      {{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="cb-header">
    <div>
      <h1><i data-feather="anchor" style="width:18px;height:18px;stroke:#fff;display:inline;vertical-align:sub;margin-right:8px;"></i>New Booking</h1>
      <p>Select booking type, then fill in the details</p>
    </div>
    <a href="{{ route('bookings.index') }}" class="cb-back">
      <i data-feather="arrow-left" style="width:14px;height:14px;stroke:#fff;"></i> Back
    </a>
  </div>

  {{-- ── STEP 1 ── --}}
  <div class="cb-step" id="step1">
    <div class="cb-step-head">
      <div class="cb-step-num">1</div>
      <div>
        <div class="cb-step-title">Choose Booking Type</div>
        <div class="cb-step-sub">Select the service you want to book</div>
      </div>
    </div>
    <div class="type-grid">
      <div class="type-card" id="tc-boat" style="--tc-color:#0EA5E9;--tc-bg:#E0F2FE;--tc-border:#BAE6FD;--tc-rgb:14,165,233;" onclick="selectType('boat')">
        <span class="tc-check"><svg viewBox="0 0 24 24" fill="none" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
        <div class="tc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20M12 3L4 14h16L12 3z"/><line x1="12" y1="3" x2="12" y2="14"/></svg></div>
        <div class="tc-label">Boat</div>
        <div class="tc-desc">River rides, ghat tours & sunrise/sunset cruises</div>
      </div>
      <div class="type-card" id="tc-stay" style="--tc-color:#10B981;--tc-bg:#D1FAE5;--tc-border:#A7F3D0;--tc-rgb:16,185,129;" onclick="selectType('stay')">
        <span class="tc-check"><svg viewBox="0 0 24 24" fill="none" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
        <div class="tc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="tc-label">Stay</div>
        <div class="tc-desc">Hotels, homestays & heritage accommodation</div>
      </div>
      <div class="type-card" id="tc-cab" style="--tc-color:#F59E0B;--tc-bg:#FEF3C7;--tc-border:#FDE68A;--tc-rgb:245,158,11;" onclick="selectType('cab')">
        <span class="tc-check"><svg viewBox="0 0 24 24" fill="none" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
        <div class="tc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 4v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
        <div class="tc-label">Cab</div>
        <div class="tc-desc">Airport transfers, city rides & outstation trips</div>
      </div>
      <div class="type-card" id="tc-tour" style="--tc-color:#7C3AED;--tc-bg:#EDE9FE;--tc-border:#C4B5FD;--tc-rgb:124,58,237;" onclick="selectType('tour')">
        <span class="tc-check"><svg viewBox="0 0 24 24" fill="none" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
        <div class="tc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg></div>
        <div class="tc-label">Tour Packages</div>
        <div class="tc-desc">Full-day tours, Kashi darshan & custom packages</div>
      </div>
    </div>
  </div>

  {{-- ── STEP 2 ── --}}
  <div class="cb-forms" id="step2">
    <div class="cb-step" style="margin-bottom:18px;">
      <div class="cb-step-head">
        <div class="cb-step-num">2</div>
        <div>
          <div class="cb-step-title" id="step2-title">Fill Booking Details</div>
          <div class="cb-step-sub">Complete all required fields then submit</div>
        </div>
        <button type="button" onclick="resetType()" style="margin-left:auto;background:transparent;border:1.5px solid var(--h-border);border-radius:8px;padding:6px 14px;font-size:.75rem;font-weight:600;color:var(--h-sub);cursor:pointer;">← Change Type</button>
      </div>
    </div>

    {{-- ════════════════════════════════════
         BOAT FORM — Premium Design
    ════════════════════════════════════ --}}
    <div class="cb-form-section" id="form-boat">
      <form action="{{ route('bookings.store-direct') }}" method="POST" id="form-boat-submit">
        @csrf

        <div class="nb-layout">

          {{-- LEFT: Main --}}
          <div class="nb-main">

            {{-- Booking Info --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#EFF6FF;color:#3B82F6;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="nb-card-title">Booking Information</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="nb-label">Booking ID</label>
                    <input type="text" class="form-control nb-input" readonly placeholder="Auto-generated" style="background:#F8FAFC !important;color:#94A3B8 !important;">
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Booking Date <span class="nb-req">*</span></label>
                    <input type="date" name="booking_date" id="boat_booking_date" class="form-control nb-input" required onchange="buildBoatPlan()">
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Lead Source <span class="nb-req">*</span></label>
                    <select name="lead_source_id" class="form-select nb-input" required>
                      <option value="">Select…</option>
                      @foreach($leadSources as $s)<option value="{{ $s->id }}" {{ old('lead_source_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {{-- Guest Details --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#F0FDF4;color:#10B981;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="nb-card-title">Guest Details</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="nb-label">Guest Name <span class="nb-req">*</span></label>
                    <input type="text" name="guest_name" class="form-control nb-input" required placeholder="Full name" value="{{ old('guest_name') }}">
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Mobile <span class="nb-req">*</span></label>
                    <div class="nb-phone-wrap">
                      <span class="nb-phone-prefix">+91</span>
                      <input type="tel" name="phone" class="form-control nb-input nb-input-phone" required placeholder="XXXXX XXXXX" maxlength="10" value="{{ old('phone') }}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Alt. Mobile</label>
                    <div class="nb-phone-wrap">
                      <span class="nb-phone-prefix">+91</span>
                      <input type="tel" name="alt_phone" class="form-control nb-input nb-input-phone" placeholder="Alternate" maxlength="10">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Email Address</label>
                    <input type="email" name="email" class="form-control nb-input" placeholder="guest@email.com" value="{{ old('email') }}">
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Country</label>
                    <select name="country" class="form-select nb-input">
                      <option value="India" selected>🇮🇳 India</option>
                      <option value="USA">🇺🇸 USA</option>
                      <option value="UK">🇬🇧 UK</option>
                      <option value="UAE">🇦🇪 UAE</option>
                      <option value="Australia">🇦🇺 Australia</option>
                      <option value="Canada">🇨🇦 Canada</option>
                      <option value="Singapore">🇸🇬 Singapore</option>
                      <option value="Other">🌍 Other</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {{-- Select Boat --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#F0F9FF;color:#0EA5E9;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M2 20h20M12 3L4 14h16L12 3z"/><line x1="12" y1="3" x2="12" y2="14"/></svg>
                </div>
                <div class="nb-card-title">Select Boat &nbsp;<span style="font-size:.72rem;font-weight:400;color:#EF4444;">* required</span></div>
              </div>
              <div class="nb-card-body">
                <div class="boat-grid" id="boat-cards-grid">
                  <div class="boat-card" data-boat="normal-motor" data-name="Normal Motor Boat" data-base="4000" data-extra="300" data-base-pax="10" data-max-pax="25" onclick="selectBoat(this)">
                    <div class="boat-card-check">✓</div>
                    <div class="boat-card-emoji">🚤</div>
                    <div class="boat-card-name">Normal Motor Boat</div>
                    <div class="boat-card-cap">Base 10 | Max 25</div>
                    <div class="boat-card-price">₹4,000</div>
                    <div class="boat-card-extra">+₹300/extra person</div>
                  </div>
                  <div class="boat-card" data-boat="light-motor" data-name="Light Motor Boat" data-base="5000" data-extra="300" data-base-pax="10" data-max-pax="25" onclick="selectBoat(this)">
                    <div class="boat-card-check">✓</div>
                    <div class="boat-card-emoji">⛵</div>
                    <div class="boat-card-name">Light Motor Boat</div>
                    <div class="boat-card-cap">Base 10 | Max 25</div>
                    <div class="boat-card-price">₹5,000</div>
                    <div class="boat-card-extra">+₹300/extra person</div>
                  </div>
                  <div class="boat-card" data-boat="premium-light" data-name="Premium Light Motor Boat" data-base="6000" data-extra="300" data-base-pax="10" data-max-pax="25" onclick="selectBoat(this)">
                    <div class="boat-card-check">✓</div>
                    <div class="boat-card-emoji">⚡</div>
                    <div class="boat-card-name">Premium Light Motor Boat</div>
                    <div class="boat-card-cap">Base 10 | Max 25</div>
                    <div class="boat-card-price">₹6,000</div>
                    <div class="boat-card-extra">+₹300/extra person</div>
                  </div>
                  <div class="boat-card" data-boat="luxury-yacht" data-name="Luxury Mini Yacht" data-base="8500" data-extra="500" data-base-pax="10" data-max-pax="20" onclick="selectBoat(this)">
                    <div class="boat-card-check">✓</div>
                    <div class="boat-card-emoji">🛥️</div>
                    <div class="boat-card-name">Luxury Mini Yacht</div>
                    <div class="boat-card-cap">Base 10 | Max 20</div>
                    <div class="boat-card-price">₹8,500</div>
                    <div class="boat-card-extra">+₹500/extra person</div>
                  </div>
                  <div class="boat-card" data-boat="bajra" data-name="Bajra Boat" data-base="11999" data-extra="500" data-base-pax="40" data-max-pax="50" onclick="selectBoat(this)">
                    <div class="boat-card-check">✓</div>
                    <div class="boat-card-emoji">🚣</div>
                    <div class="boat-card-name">Bajra Boat</div>
                    <div class="boat-card-cap">Base 40 | Max 50</div>
                    <div class="boat-card-price">₹11,999</div>
                    <div class="boat-card-extra">+₹500/extra person</div>
                  </div>
                  <div class="boat-card" data-boat="cruise" data-name="Cruise" data-base="30000" data-extra="400" data-base-pax="60" data-max-pax="150" onclick="selectBoat(this)">
                    <div class="boat-card-check">✓</div>
                    <div class="boat-card-emoji">🚢</div>
                    <div class="boat-card-name">Cruise</div>
                    <div class="boat-card-cap">Base 60 | Max 150</div>
                    <div class="boat-card-price">₹30,000</div>
                    <div class="boat-card-extra">+₹400/extra person</div>
                  </div>
                </div>
                <div id="boat-cap-warning" class="nb-warning" style="display:none;">
                  ⚠️ Number of persons exceeds maximum capacity for this boat.
                </div>
              </div>
            </div>

            {{-- Number of Persons --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#FFF7ED;color:#F97316;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="nb-card-title">Number of Persons</div>
              </div>
              <div class="nb-card-body">
                <div class="nb-pax-sections" style="display:flex;gap:32px;flex-wrap:wrap;align-items:flex-start;">

                  {{-- Adults --}}
                  <div>
                    <div class="nb-label" style="margin-bottom:10px;">Persons (Adults)</div>
                    <div class="nb-counter-wrap">
                      <button type="button" class="nb-counter-btn" onclick="changePax(-1)">−</button>
                      <span class="nb-counter-val" id="pax-display">1</span>
                      <button type="button" class="nb-counter-btn" onclick="changePax(1)">+</button>
                    </div>
                    <input type="hidden" name="boat_pax" id="boat_pax" value="1">
                    <div class="nb-counter-info" id="pax-info" style="margin-top:6px;">Select a boat to see capacity</div>
                  </div>

                  <div class="nb-pax-divider" style="width:1px;background:#E8ECF4;align-self:stretch;min-height:60px;"></div>

                  {{-- Children --}}
                  <div>
                    <div class="nb-label" style="margin-bottom:10px;">Children <span style="font-weight:400;color:#94A3B8;text-transform:none;font-size:.68rem;">(under 10)</span></div>
                    <div class="nb-counter-wrap">
                      <button type="button" class="nb-counter-btn" onclick="changeChildren(-1)">−</button>
                      <span class="nb-counter-val" id="children-display">0</span>
                      <button type="button" class="nb-counter-btn" onclick="changeChildren(1)">+</button>
                    </div>
                    <input type="hidden" name="boat_children" id="boat_children" value="0">
                    <div class="nb-counter-info" style="margin-top:6px;">Free / half rate</div>
                  </div>

                </div>

                <div class="nb-pax-price" id="pax-price-preview" style="display:none;margin-top:16px;">
                  <div class="nb-pax-row">
                    <span>Base price (up to <span id="pax-base-persons">10</span> persons)</span>
                    <span id="pax-base-price-show">₹0</span>
                  </div>
                  <div class="nb-pax-row" id="pax-extra-row" style="display:none;">
                    <span>Extra (<span id="pax-extra-count">0</span> × <span id="pax-extra-rate">₹0</span>/person)</span>
                    <span id="pax-extra-cost">₹0</span>
                  </div>
                  <div class="nb-pax-row nb-pax-total">
                    <span>Boat Subtotal</span>
                    <span id="pax-subtotal">₹0</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Boat Booking Details --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#F0F9FF;color:#0EA5E9;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="nb-card-title">Boat Booking Details</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="nb-label">Booking Type <span class="nb-req">*</span></label>
                    <select id="boat_type" class="form-select nb-input" required onchange="buildBoatPlan()">
                      <option value="Morning Boat Ride">🌅 Morning Boat Ride</option>
                      <option value="Evening Boat Ride">🌇 Evening Boat Ride</option>
                      <option value="Evening Boat Ride and Ganga Aarti">🪔 Evening Boat Ride and Ganga Aarti</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Event on Boat</label>
                    <select id="boat_event" class="form-select nb-input" onchange="buildBoatPlan()">
                      <option value="None / Regular Ride">None / Regular Ride</option>
                      <option value="Birthday Celebration">🎂 Birthday Celebration</option>
                      <option value="Anniversary">💑 Anniversary</option>
                      <option value="Corporate Event">💼 Corporate Event</option>
                      <option value="Photo Shoot">📸 Photo Shoot</option>
                      <option value="Puja / Ceremony">🪔 Puja / Ceremony</option>
                      <option value="Proposal">💍 Proposal</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {{-- Route & Timing --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#F0FDF4;color:#10B981;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="nb-card-title">Route &amp; Timing</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-2">
                  <div class="col-8 col-md-6">
                    <label class="nb-label">Pickup Ghat <span class="nb-req">*</span></label>
                    <select id="boat_pickup_ghat" class="form-select nb-input" required onchange="buildBoatPlan()">
                      <option value="">Select ghat…</option>
                      <option>Adikeshav Ghat</option>
                      <option>Namo Ghat</option>
                      <option>Telianala Ghat</option>
                      <option>Dashashwamedh Ghat</option>
                      <option>Lalita Ghat</option>
                      <option>Manikarnika Ghat</option>
                      <option>Shivala Ghat</option>
                      <option>Assi Ghat</option>
                      <option>Ravidas Ghat Nagwa</option>
                    </select>
                  </div>
                  <div class="col-4 col-md-6">
                    <label class="nb-label">Pickup Time <span class="nb-req">*</span></label>
                    <input type="time" id="boat_time" class="form-control nb-input" required onchange="buildBoatPlan()">
                  </div>
                  <div class="col-8 col-md-5">
                    <label class="nb-label">Drop Ghat <span class="nb-req">*</span></label>
                    <select id="boat_drop_ghat" class="form-select nb-input" required onchange="buildBoatPlan()">
                      <option value="">Select drop ghat…</option>
                      <option>Same as Pickup</option>
                      <option>Adikeshav Ghat</option>
                      <option>Namo Ghat</option>
                      <option>Telianala Ghat</option>
                      <option>Dashashwamedh Ghat</option>
                      <option>Lalita Ghat</option>
                      <option>Manikarnika Ghat</option>
                      <option>Shivala Ghat</option>
                      <option>Assi Ghat</option>
                      <option>Ravidas Ghat Nagwa</option>
                    </select>
                  </div>
                  <div class="col-4 col-md-4">
                    <label class="nb-label">Drop / End Time</label>
                    <input type="time" id="boat_end_time" class="form-control nb-input" onchange="buildBoatPlan()">
                  </div>
                </div>
              </div>
            </div>

            {{-- hidden backend compat fields --}}
            <input type="hidden" name="services[0][service_template_id]" id="boat-svc-id"
              value="{{ optional($serviceTypes->first()?->serviceTemplates->first())->id }}">
            <input type="hidden" name="services[0][quantity]" id="boat-qty" value="1">
            <input type="hidden" name="services[0][service_date]" id="boat-svc-date">

            {{-- Notes --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#FFF7ED;color:#F97316;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="nb-card-title">Notes</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="nb-label">Guest Notes <span style="font-weight:400;color:#94A3B8;text-transform:none;font-size:.68rem;">(shown on voucher)</span></label>
                    <textarea name="guest_notes" class="form-control nb-input" rows="3" placeholder="Special requests, preferences…" style="resize:none;"></textarea>
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Internal Notes <span style="font-weight:400;color:#94A3B8;text-transform:none;font-size:.68rem;">(staff only)</span></label>
                    <textarea name="internal_notes" class="form-control nb-input" rows="3" placeholder="Staff instructions, follow-up…" style="resize:none;"></textarea>
                  </div>
                </div>
              </div>
            </div>

            {{-- Status & Tags --}}
            <div class="nb-card">
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="nb-label">Booking Status</label>
                    <input type="text" class="form-control nb-input" value="✅ Confirmed" readonly style="background:#F0FDF4 !important;color:#15803D !important;font-weight:600;">
                    <input type="hidden" name="booking_status" value="confirmed">
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Tags</label>
                    <input type="text" name="tags" class="form-control nb-input" placeholder="vip, repeat, group, urgent…">
                  </div>
                </div>
              </div>
            </div>

          </div>{{-- /nb-main --}}

          {{-- RIGHT: Sticky Sidebar --}}
          <div class="nb-sidebar">

            <div class="nb-sidebar-card">
              <div class="nb-sidebar-head">
                <div class="nb-card-icon" style="background:#F0FDF4;color:#10B981;width:28px;height:28px;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <div class="nb-card-title" style="font-size:.9rem;">Booking Amount</div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label">Total Booking Amount (₹) <span class="nb-req">*</span></label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee">₹</span>
                  <input type="number" name="services[0][unit_price]" id="boat-price-field" class="form-control nb-input nb-rupee-input" placeholder="Auto from boat pricing" min="0" step="0.01" required readonly style="background:#F0F9FF !important;color:#0EA5E9 !important;font-weight:700;" oninput="calcBoatBalance()">
                </div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label">Discount (₹) <span style="font-weight:400;color:#94A3B8;text-transform:none;font-size:.68rem;">— auto</span></label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee">₹</span>
                  <input type="number" name="discount" id="boat-discount" class="form-control nb-input nb-rupee-input" value="0" min="0" oninput="calcBoatBalance()">
                </div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label">Advance Paid (₹)</label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee">₹</span>
                  <input type="number" name="advance_paid" id="boat-advance" class="form-control nb-input nb-rupee-input" value="0" min="0" oninput="calcBoatBalance()">
                </div>
              </div>

              <div class="nb-balance-box">
                <div class="nb-balance-label">Balance Due</div>
                <div class="nb-balance-row">
                  <div class="nb-balance-amount" id="boat-balance">₹0</div>
                  <span class="nb-balance-badge" id="boat-pay-badge">PENDING</span>
                </div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label">Payment Status</label>
                <select name="payment_status" id="boat-pay-status" class="form-select nb-input" onchange="updateBoatPayBadge()">
                  <option value="paid">Paid</option>
                  <option value="due">Due</option>
                </select>
              </div>

              <div class="nb-amount-row" style="margin-bottom:0;">
                <label class="nb-label">Payment Method</label>
                <select name="payment_method" class="form-select nb-input">
                  <option value="">Select…</option>
                  <option value="cash">Cash</option>
                  <option value="upi">UPI / GPay / PhonePe</option>
                </select>
              </div>
            </div>

            <div class="nb-staff-card">
              <div class="nb-staff-head">
                <span>🛡️</span>
                <span style="font-size:.82rem;font-weight:700;color:#92400E;">Internal — Staff Only</span>
                <span class="nb-not-confirm-badge">Not on confirmation</span>
              </div>
              <div class="nb-amount-row" style="margin-top:14px;">
                <label class="nb-label" style="color:#78350F;">Assign Boatman</label>
                <select name="boatman" id="boat_boatman" class="form-select nb-input" onchange="buildBoatPlan()" style="border-color:#FDE68A !important;background:#FFFDF5 !important;">
                  <option value="">Select boatman…</option>
                  <option>Babloo Sahni</option>
                  <option>Anil Sahni</option>
                  <option>Gagan Singh</option>
                </select>
              </div>
              <div class="nb-amount-row">
                <label class="nb-label" style="color:#78350F;">Vendor / Supplier Cost (₹)</label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee" style="color:#92400E;">₹</span>
                  <input type="number" name="vendor_cost" id="boat-vendor-cost" class="form-control nb-input nb-rupee-input" value="0" min="0" oninput="calcBoatMargin()">
                </div>
              </div>
              <label class="nb-label" style="color:#78350F;">Margin on Booking (₹)</label>
              <div class="nb-margin-box">
                <div class="nb-margin-label">Profit Margin</div>
                <div class="nb-margin-row">
                  <div class="nb-margin-amount" id="boat-margin">₹0</div>
                  <div class="nb-margin-pct" id="boat-margin-pct">0%</div>
                </div>
              </div>
            </div>

            <button type="submit" form="form-boat-submit" class="btn-submit nb-submit-desktop" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" width="17" height="17" stroke="white" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              Confirm Boat Booking
            </button>

          </div>{{-- /nb-sidebar --}}
        </div>{{-- /nb-layout --}}

        {{-- Hidden fields --}}
        <input type="hidden" name="short_plan" id="boat-short-plan" value="Boat booking">
        <input type="hidden" name="tour_plan" value="">

      </form>

      {{-- Mobile sticky CTA --}}
      <div class="nb-mobile-cta">
        <div class="nb-mobile-balance">
          <span>Total &nbsp;<strong id="mob-total" style="color:#0EA5E9;font-size:.9rem;">₹0</strong></span>
          <strong id="mob-balance">Balance: ₹0</strong>
        </div>
        <button type="submit" form="form-boat-submit" class="btn-submit" style="padding:10px 18px;font-size:.82rem;white-space:nowrap;">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" width="14" height="14" stroke="white" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          Confirm
        </button>
      </div>
    </div>{{-- /form-boat --}}

    {{-- ════════════════════════════════════
         STAY FORM — Premium Design
    ════════════════════════════════════ --}}
    <div class="cb-form-section" id="form-stay">
      @php $stayTemplates = $serviceTypes->firstWhere('name','Stay')?->serviceTemplates ?? collect(); @endphp
      <form action="{{ route('bookings.store-direct') }}" method="POST" id="form-stay-submit">
        @csrf

        <div class="nb-layout">

          {{-- ── LEFT: Main ── --}}
          <div class="nb-main">

            {{-- Booking Information --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#EFF6FF;color:#3B82F6;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="nb-card-title">Booking Information</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="nb-label">Booking Date</label>
                    <input type="text" class="form-control nb-input" readonly
                           value="{{ now()->format('d M, Y') }}"
                           style="background:#F8FAFC !important;color:#94A3B8 !important;">
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Lead Source <span class="nb-req">*</span></label>
                    <select name="lead_source_id" class="form-select nb-input" required>
                      <option value="">Select source…</option>
                      @foreach($leadSources as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Booking Status</label>
                    <input type="text" class="form-control nb-input" value="✅ Confirmed" readonly
                           style="background:#F0FDF4 !important;color:#15803D !important;font-weight:600;">
                    <input type="hidden" name="booking_status" value="confirmed">
                  </div>
                </div>
              </div>
            </div>

            {{-- Guest Details --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#F0FDF4;color:#10B981;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="nb-card-title">Guest Details</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="nb-label">Guest Name <span class="nb-req">*</span></label>
                    <input type="text" name="guest_name" class="form-control nb-input" required
                           placeholder="Full name" value="{{ old('guest_name') }}">
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Mobile <span class="nb-req">*</span></label>
                    <div class="nb-phone-wrap">
                      <span class="nb-phone-prefix">+91</span>
                      <input type="tel" name="phone" class="form-control nb-input nb-input-phone"
                             required placeholder="XXXXX XXXXX" maxlength="10"
                             value="{{ old('phone') }}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Alt. Mobile</label>
                    <div class="nb-phone-wrap">
                      <span class="nb-phone-prefix">+91</span>
                      <input type="tel" name="alt_phone" class="form-control nb-input nb-input-phone"
                             placeholder="Optional" maxlength="10">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Email</label>
                    <input type="email" name="email" class="form-control nb-input"
                           placeholder="guest@email.com" value="{{ old('email') }}">
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Country</label>
                    <select name="country" class="form-select nb-input">
                      <option value="India" selected>🇮🇳 India</option>
                      <option value="USA">🇺🇸 USA</option>
                      <option value="UK">🇬🇧 UK</option>
                      <option value="UAE">🇦🇪 UAE</option>
                      <option value="Australia">🇦🇺 Australia</option>
                      <option value="Canada">🇨🇦 Canada</option>
                      <option value="Singapore">🇸🇬 Singapore</option>
                      <option value="Other">🌍 Other</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {{-- Property / Hotel --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#D1FAE5;color:#059669;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="nb-card-title">Property / Hotel &nbsp;<span style="font-size:.72rem;font-weight:400;color:#EF4444;">* required</span></div>
              </div>
              <div class="nb-card-body">

                {{-- Listed properties grid --}}
                <div class="hotel-cards-grid" id="hotel-cards-grid">
                  @foreach($stayTemplates as $t)
                  <div class="hotel-card"
                       data-id="{{ $t->id }}"
                       data-name="{{ $t->name }}"
                       data-price="{{ (float)$t->default_selling_price }}"
                       onclick="selectHotel(this)">
                    <button type="button" class="hotel-card-del" onclick="event.stopPropagation();clearHotel()" title="Remove">✕</button>
                    <div class="hotel-card-check">✓</div>
                    <div class="hotel-card-icon">🏨</div>
                    <div class="hotel-card-name">{{ $t->name }}</div>
                    <div class="hotel-card-price">₹{{ number_format((float)$t->default_selling_price, 0) }}<span class="hotel-card-per">/night</span></div>
                  </div>
                  @endforeach

                  {{-- Custom / not listed --}}
                  <div class="hotel-card hotel-card-custom" id="hotel-card-custom" onclick="selectHotelCustom()">
                    <button type="button" class="hotel-card-del" onclick="event.stopPropagation();clearHotel()" title="Remove">✕</button>
                    <div class="hotel-card-check">✓</div>
                    <div class="hotel-card-icon">✏️</div>
                    <div class="hotel-card-name" style="color:#4F46E5;">Not listed?</div>
                    <div class="hotel-card-price" style="color:#6B7280;font-size:.7rem;">Add custom</div>
                  </div>
                </div>

                {{-- Custom property mini-form (hidden by default) --}}
                <div id="stay-custom-hotel-wrap" style="display:none;margin-top:14px;background:#F5F3FF;border:1.5px solid #C4B5FD;border-radius:14px;padding:16px 18px;">
                  <div style="font-size:.72rem;font-weight:800;color:#7C3AED;text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px;">
                    ✏️ Add Custom Property
                  </div>
                  <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                      <label class="nb-label" style="color:#6D28D9;">Property Name <span class="nb-req">*</span></label>
                      <input type="text" id="stay_hotel" class="form-control nb-input"
                             placeholder="e.g. Ganges View Hotel, Varanasi"
                             style="border-color:#C4B5FD !important;"
                             oninput="buildStayPlan()">
                    </div>
                    <div class="col-md-3">
                      <label class="nb-label" style="color:#6D28D9;">Room Type</label>
                      <select id="stay_custom_room_type" class="form-select nb-input"
                              style="border-color:#C4B5FD !important;"
                              onchange="onCustomRoomTypeChange()">
                        <option value="">Select type</option>
                        <option value="Deluxe Room" data-price="2500">Deluxe Room</option>
                        <option value="Executive Room" data-price="3500">Executive Room</option>
                        <option value="Premium Room" data-price="5000">Premium Room</option>
                        <option value="Homestay Flats" data-price="1500">Homestay Flats</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label class="nb-label" style="color:#6D28D9;">Price / Night (₹) <span class="nb-req">*</span></label>
                      <div class="nb-rupee-wrap">
                        <span class="nb-rupee" style="color:#7C3AED;">₹</span>
                        <input type="number" id="stay_custom_price" class="form-control nb-input nb-rupee-input"
                               placeholder="0" min="0" step="1"
                               style="border-color:#C4B5FD !important;">
                      </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                      <button type="button" onclick="saveCustomHotel()"
                              style="width:100%;height:40px;background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#fff;border:none;border-radius:10px;font-size:.82rem;font-weight:700;cursor:pointer;transition:opacity .15s;"
                              onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                        Save
                      </button>
                    </div>
                  </div>
                  {{-- Extra Bed / Mattress --}}
                  <div class="row g-2 mt-2">
                    <div class="col-12">
                      <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                        <label style="display:flex;align-items:center;gap:7px;cursor:pointer;font-size:.82rem;font-weight:600;color:#6D28D9;margin:0;">
                          <input type="checkbox" id="stay_custom_extra_bed" onchange="toggleExtraBedPrice()"
                                 style="width:16px;height:16px;accent-color:#7C3AED;cursor:pointer;">
                          Extra Bed / Mattress
                        </label>
                        <div id="stay-extra-bed-price-wrap" style="display:none;align-items:center;gap:6px;">
                          <span style="font-size:.78rem;color:#7C3AED;font-weight:600;">₹</span>
                          <input type="number" id="stay_extra_bed_price" class="form-control nb-input"
                                 placeholder="Price/bed" min="0" step="1"
                                 style="width:120px;border-color:#C4B5FD !important;font-size:.82rem;">
                          <span style="font-size:.74rem;color:#6B7280;">/night</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="stay-custom-error" style="display:none;margin-top:8px;font-size:.75rem;color:#EF4444;font-weight:600;"></div>
                </div>

                {{-- Hidden fields --}}
                <input type="hidden" name="services[0][service_template_id]" id="stay-svc-tmpl-id" value="">
              </div>
            </div>

            {{-- Stay Details --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#FEF3C7;color:#D97706;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="nb-card-title">Stay Details</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-6 col-md-3">
                    <label class="nb-label">Check-in <span class="nb-req">*</span></label>
                    <input type="date" id="stay_checkin" name="booking_start_date"
                           class="form-control nb-input" required
                           onchange="buildStayPlan();calcNights()">
                    <input type="time" id="stay_checkin_time" name="checkin_time"
                           class="form-control nb-input mt-1" value="14:00"
                           onchange="buildStayPlan()"
                           style="font-size:.8rem !important;color:#0891b2 !important;font-weight:600 !important;">
                  </div>
                  <div class="col-6 col-md-3">
                    <label class="nb-label">Check-out <span class="nb-req">*</span></label>
                    <input type="date" id="stay_checkout" name="booking_end_date"
                           class="form-control nb-input" required
                           onchange="buildStayPlan();calcNights()">
                    <input type="time" id="stay_checkout_time" name="checkout_time"
                           class="form-control nb-input mt-1" value="11:00"
                           onchange="buildStayPlan()"
                           style="font-size:.8rem !important;color:#dc2626 !important;font-weight:600 !important;">
                  </div>
                  <div class="col-4 col-md-2">
                    <label class="nb-label">Nights</label>
                    <input type="text" id="stay_nights" class="form-control nb-input" readonly
                           placeholder="Auto" style="background:#F1F5F9 !important;text-align:center;font-weight:700;">
                  </div>
                  <div class="col-4 col-md-2">
                    <label class="nb-label">Adults <span style="font-weight:500;color:#94A3B8;text-transform:none;font-size:.67rem;">(18+)</span></label>
                    <div class="stay-counter-wrap">
                      <button type="button" class="stay-counter-btn" onclick="changeStayAdults(-1)">−</button>
                      <span class="stay-counter-val" id="stay-adults-display">1</span>
                      <button type="button" class="stay-counter-btn" onclick="changeStayAdults(1)">+</button>
                    </div>
                    <input type="hidden" id="stay_guests" name="adults" value="1">
                  </div>
                  <div class="col-4 col-md-2">
                    <label class="nb-label">Children <span style="font-weight:500;color:#94A3B8;text-transform:none;font-size:.67rem;">(5+)</span></label>
                    <div class="stay-counter-wrap">
                      <button type="button" class="stay-counter-btn" onclick="changeStayKids(-1)">−</button>
                      <span class="stay-counter-val" id="stay-kids-display">0</span>
                      <button type="button" class="stay-counter-btn" onclick="changeStayKids(1)">+</button>
                    </div>
                    <input type="hidden" id="stay_kids" name="kids" value="0">
                  </div>
                  <div class="col-md-3">
                    <label class="nb-label">Room Type</label>
                    <select id="stay_room_type" class="form-select nb-input" onchange="buildStayPlan()">
                      <option value="">Select room type</option>
                      <option>Deluxe Room</option>
                      <option>Executive Room</option>
                      <option>Premium Room</option>
                      <option>Homestay Flats</option>
                    </select>
                  </div>
                  <div class="col-6 col-md-2">
                    <label class="nb-label">No. of Rooms <span class="nb-req">*</span></label>
                    <input type="number" id="stay_rooms" name="rooms" class="form-control nb-input"
                           min="1" value="1" required onchange="recalcStayTotal();buildStayPlan()">
                  </div>
                  <div class="col-md-3">
                    <label class="nb-label">Meal Plan</label>
                    <select id="stay_meal" class="form-select nb-input" onchange="buildStayPlan()">
                      <option value="">No meals included</option>
                      <option value="CP">CP – Breakfast only</option>
                      <option value="MAP">MAP – Breakfast + Dinner</option>
                      <option value="AP">AP – All meals</option>
                      <option value="EP">EP – Room only</option>
                    </select>
                  </div>
                  <div class="col-6 col-md-2">
                    <label class="nb-label">Extra Bed (₹)</label>
                    <div class="nb-rupee-wrap" style="max-width:120px;">
                      <span class="nb-rupee" style="color:#8B5CF6;">₹</span>
                      <input type="number" id="stay_extra_bed_amt" class="form-control nb-input nb-rupee-input"
                             placeholder="0" min="0" step="1"
                             oninput="recalcStayTotal();buildStayPlan()">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label class="nb-label">Special Requests</label>
                    <input type="text" id="stay_special" class="form-control nb-input"
                           placeholder="e.g. early check-in, Ganga view"
                           oninput="buildStayPlan()">
                  </div>
                </div>

                {{-- Booking preview strip --}}
                <div id="stay-plan-preview" style="display:none;margin-top:16px;background:#F0FDF4;border:1.5px solid #BBF7D0;border-radius:10px;padding:12px 16px;">
                  <div style="font-size:.65rem;font-weight:800;color:#065F46;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Voucher Summary Preview</div>
                  <div id="stay-plan-text" style="font-size:.82rem;color:#14532D;font-weight:600;line-height:1.6;"></div>
                </div>

                {{-- Hidden service fields --}}
                <input type="hidden" name="services[0][quantity]" id="stay-svc-qty" value="1">
                <input type="hidden" name="services[0][service_date]" id="stay-svc-date">
                <input type="hidden" name="short_plan" id="stay-short-plan" value="Stay booking">
                <input type="hidden" name="tour_plan" value="">
              </div>
            </div>

            {{-- Notes --}}
            <div class="nb-card">
              <div class="nb-card-head">
                <div class="nb-card-icon" style="background:#FFF7ED;color:#F97316;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="nb-card-title">Notes</div>
              </div>
              <div class="nb-card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="nb-label">Guest Notes <span style="font-weight:400;color:#94A3B8;text-transform:none;font-size:.68rem;">(shown on voucher)</span></label>
                    <textarea name="guest_notes" class="form-control nb-input" rows="3"
                              placeholder="Welcome message, special arrangements…"
                              style="resize:none;"></textarea>
                  </div>
                  <div class="col-md-6">
                    <label class="nb-label">Internal Notes <span style="font-weight:400;color:#94A3B8;text-transform:none;font-size:.68rem;">(staff only)</span></label>
                    <textarea name="internal_notes" class="form-control nb-input" rows="3"
                              placeholder="Staff instructions, follow-up tasks…"
                              style="resize:none;"></textarea>
                  </div>
                </div>
              </div>
            </div>

          </div>{{-- /nb-main --}}

          {{-- ── RIGHT: Sticky Sidebar ── --}}
          <div class="nb-sidebar">

            <div class="nb-sidebar-card">
              <div class="nb-sidebar-head">
                <div class="nb-card-icon" style="background:#F0FDF4;color:#10B981;width:28px;height:28px;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <div class="nb-card-title" style="font-size:.9rem;">Booking Amount</div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label" style="display:flex;align-items:center;justify-content:space-between;">
                  Total Amount (₹) <span class="nb-req">*</span>
                  <span id="stay-price-auto-tag" style="display:none;font-size:.63rem;font-weight:700;color:#10B981;background:#D1FAE5;padding:2px 7px;border-radius:20px;text-transform:uppercase;letter-spacing:.03em;">Auto</span>
                </label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee">₹</span>
                  <input type="number" name="services[0][unit_price]" id="stay-price-field"
                         class="form-control nb-input nb-rupee-input"
                         placeholder="Select property + dates"
                         min="0" step="0.01" required
                         oninput="calcStayBalance()">
                </div>
                <div id="stay-per-night-note" style="font-size:.7rem;color:#6B7280;margin-top:5px;display:none;font-weight:600;"></div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label">Discount (₹)</label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee">₹</span>
                  <input type="number" name="discount" id="stay-discount"
                         class="form-control nb-input nb-rupee-input"
                         value="0" min="0" oninput="calcStayBalance()">
                </div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label">Advance Paid (₹)</label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee">₹</span>
                  <input type="number" name="advance_paid" id="stay-advance"
                         class="form-control nb-input nb-rupee-input"
                         value="0" min="0" oninput="calcStayBalance()">
                </div>
              </div>

              {{-- Live Payment Summary --}}
              <div class="nb-pay-sum">
                <div class="nb-pay-sum-head">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                  Payment Summary
                  <span id="stay-pay-badge" class="nb-balance-badge due" style="margin-left:auto;font-size:.58rem;">PENDING</span>
                </div>
                <div class="nb-pay-sum-body">
                  <div class="nb-pay-sum-row">
                    <span class="nb-pay-sum-lbl">Total Amount</span>
                    <span class="nb-pay-sum-val" id="stay-sum-total">₹0</span>
                  </div>
                  <div class="nb-pay-sum-formula" id="stay-sum-formula" style="display:none;"></div>
                  <div class="nb-pay-sum-row" id="stay-sum-discount-row" style="display:none;">
                    <span class="nb-pay-sum-lbl">Discount</span>
                    <span class="nb-pay-sum-val" id="stay-sum-discount" style="color:#F59E0B;">-₹0</span>
                  </div>
                  <div class="nb-pay-sum-row">
                    <span class="nb-pay-sum-lbl">Advance Paid</span>
                    <span class="nb-pay-sum-val nb-pay-sum-advance" id="stay-sum-advance">₹0</span>
                  </div>
                  <div class="nb-pay-sum-row">
                    <span class="nb-pay-sum-lbl" style="font-weight:700;color:#0F172A;">Balance Due</span>
                    <span class="nb-pay-sum-balance" id="stay-balance">₹0</span>
                  </div>
                  <div class="nb-pay-sum-bar-wrap">
                    <div class="nb-pay-sum-bar-lbl">
                      <span>Payment Progress</span>
                      <span id="stay-sum-pct" style="color:#10B981;font-weight:700;">0%</span>
                    </div>
                    <div class="nb-pay-sum-bar-track">
                      <div class="nb-pay-sum-bar-fill" id="stay-sum-bar"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="nb-amount-row">
                <label class="nb-label">Payment Status</label>
                <select name="payment_status" id="stay-pay-status" class="form-select nb-input"
                        onchange="updateStayPayBadge()">
                  <option value="due">Due</option>
                  <option value="paid">Paid</option>
                </select>
              </div>

              <div class="nb-amount-row" style="margin-bottom:0;">
                <label class="nb-label">Payment Method</label>
                <select name="payment_method" class="form-select nb-input">
                  <option value="">Select…</option>
                  <option value="cash">Cash</option>
                  <option value="upi">UPI / GPay / PhonePe</option>
                  <option value="bank_transfer">Bank Transfer</option>
                  <option value="card">Card</option>
                </select>
              </div>
            </div>

            {{-- Staff Internal --}}
            <div class="nb-staff-card">
              <div class="nb-staff-head">
                <span>🛡️</span>
                <span style="font-size:.82rem;font-weight:700;color:#92400E;">Internal — Staff Only</span>
                <span class="nb-not-confirm-badge">Not on confirmation</span>
              </div>
              <div class="nb-amount-row" style="margin-top:14px;">
                <label class="nb-label" style="color:#78350F;">Hotel / Vendor Cost (₹)</label>
                <div class="nb-rupee-wrap">
                  <span class="nb-rupee" style="color:#92400E;">₹</span>
                  <input type="number" name="vendor_cost" id="stay-vendor-cost"
                         class="form-control nb-input nb-rupee-input"
                         value="0" min="0" oninput="calcStayMargin()"
                         style="border-color:#FDE68A !important;background:#FFFDF5 !important;">
                </div>
              </div>
              <label class="nb-label" style="color:#78350F;">Margin on Booking</label>
              <div class="nb-margin-box">
                <div class="nb-margin-label">Profit Margin</div>
                <div class="nb-margin-row">
                  <div class="nb-margin-amount" id="stay-margin">₹0</div>
                  <div class="nb-margin-pct" id="stay-margin-pct">0%</div>
                </div>
              </div>
              <div class="nb-amount-row" style="margin-bottom:0;">
                <label class="nb-label" style="color:#78350F;">Tags</label>
                <input type="text" name="tags" class="form-control nb-input"
                       placeholder="vip, repeat, group…"
                       style="border-color:#FDE68A !important;background:#FFFDF5 !important;">
              </div>
            </div>

            <button type="submit" form="form-stay-submit" class="btn-submit nb-submit-desktop"
                    style="width:100%;justify-content:center;--h-indigo:#10B981;--h-violet:#059669;">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" width="17" height="17" stroke="white" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              Confirm Stay Booking
            </button>

          </div>{{-- /nb-sidebar --}}
        </div>{{-- /nb-layout --}}

      </form>
    </div>

    {{-- ════════════════════════════════════
         CAB FORM
    ════════════════════════════════════ --}}
    <div class="cb-form-section" id="form-cab">
      <form action="{{ route('bookings.store-direct') }}" method="POST">
        @csrf
        <div class="fs-card">
          <div class="fs-head"><div class="fs-icon" style="--fi-color:#F59E0B;"><i data-feather="user"></i></div><div class="fs-title">Customer Information</div></div>
          <div class="fs-body">
            <div class="row g-3">
              <div class="col-md-3"><label class="f-label">Guest Name <span class="req">*</span></label><input type="text" name="guest_name" class="form-control f-ctrl" required placeholder="Full name"></div>
              <div class="col-md-3"><label class="f-label">Phone <span class="req">*</span></label><input type="tel" name="phone" class="form-control f-ctrl" required placeholder="Mobile number"></div>
              <div class="col-md-3"><label class="f-label">Email</label><input type="email" name="email" class="form-control f-ctrl" placeholder="Email address"></div>
              <div class="col-md-3"><label class="f-label">Lead Source <span class="req">*</span></label>
                <select name="lead_source_id" class="form-select f-ctrl" required>
                  <option value="">Select source</option>
                  @foreach($leadSources as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                </select></div>
            </div>
          </div>
        </div>
        <div class="fs-card">
          <div class="fs-head"><div class="fs-icon" style="--fi-color:#F59E0B;"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 4v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div><div class="fs-title">Journey Details</div></div>
          <div class="fs-body">
            <div class="row g-3">
              <div class="col-md-3"><label class="f-label">Pickup Date <span class="req">*</span></label><input type="date" id="cab_date" class="form-control f-ctrl" required onchange="buildCabPlan()"></div>
              <div class="col-md-3"><label class="f-label">Pickup Time</label><input type="time" id="cab_time" class="form-control f-ctrl" onchange="buildCabPlan()"></div>
              <div class="col-md-3"><label class="f-label">Journey Type</label>
                <select id="cab_type" class="form-select f-ctrl" onchange="buildCabPlan()">
                  <option value="">Select type</option>
                  <option>One Way</option><option>Round Trip</option>
                  <option>Local Half Day</option><option>Local Full Day</option>
                  <option>Airport Transfer</option><option>Railway Transfer</option>
                </select></div>
              <div class="col-md-3"><label class="f-label">No. of Passengers</label><input type="number" id="cab_pax" class="form-control f-ctrl" min="1" placeholder="e.g. 4" onchange="buildCabPlan()"></div>
              <div class="col-md-6"><label class="f-label">Pickup Location <span class="req">*</span></label><input type="text" id="cab_pickup" class="form-control f-ctrl" placeholder="e.g. Varanasi Railway Station" onkeyup="buildCabPlan()"></div>
              <div class="col-md-6"><label class="f-label">Drop Location</label><input type="text" id="cab_drop" class="form-control f-ctrl" placeholder="e.g. Sarnath / Allahabad" onkeyup="buildCabPlan()"></div>
              <div class="col-md-12"><label class="f-label">Vehicle Preference</label>
                <select id="cab_vehicle" class="form-select f-ctrl" onchange="buildCabPlan()">
                  <option value="">Any vehicle</option>
                  <option>Sedan (Swift Dzire / Etios)</option><option>SUV (Innova / Ertiga)</option>
                  <option>Tempo Traveller (12 Seater)</option><option>Mini Bus</option><option>Auto Rickshaw</option>
                </select></div>
            </div>
          </div>
        </div>
        <div class="fs-card">
          <div class="fs-head"><div class="fs-icon" style="--fi-color:#F59E0B;"><i data-feather="package"></i></div><div class="fs-title">Select Cab Service &amp; Price</div></div>
          <div class="fs-body">
            <div class="svc-pick-row">
              <div class="row g-3 align-items-end">
                <div class="col-md-4"><label class="f-label">Service Type</label>
                  <select class="form-select f-ctrl svc-type-sel" onchange="loadTemplates(this,'cab-svc-sel','cab-price')">
                    <option value="">Select type</option>
                    @foreach($serviceTypes as $t)<option value="{{ $t->id }}" data-templates="{{ json_encode($t->serviceTemplates) }}">{{ $t->name }}</option>@endforeach
                  </select></div>
                <div class="col-md-4"><label class="f-label">Service / Package</label>
                  <select name="services[0][service_template_id]" id="cab-svc-sel" class="form-select f-ctrl" required disabled onchange="pickPrice(this,'cab-price')">
                    <option value="">Select service type first</option>
                  </select>
                  <input type="hidden" name="services[0][quantity]" value="1">
                  <input type="hidden" name="services[0][service_date]" id="cab-svc-date"></div>
                <div class="col-md-4"><label class="f-label">Price (₹) <span class="req">*</span></label>
                  <input type="number" name="services[0][unit_price]" id="cab-price" class="form-control f-ctrl" min="0" step="0.01" required placeholder="0.00" oninput="updateSummary('cab-summary','cab-price')"></div>
              </div>
            </div>
            <div class="price-bar mt-3" id="cab-summary">
              <div class="price-bar-item"><div class="pbi-label">Total Amount</div><div class="pbi-val" id="cab-total">₹0</div></div>
              <div class="price-bar-item" style="flex:1;"><div class="pbi-label">Amount in Words</div><div style="font-size:.82rem;font-weight:600;color:#065F46;" id="cab-words">Zero Rupees Only</div></div>
            </div>
          </div>
        </div>
        <input type="hidden" name="short_plan" id="cab-short-plan" value="Cab booking">
        <input type="hidden" name="tour_plan" value="">
        <div class="fs-card">
          <div class="fs-head"><div class="fs-icon"><i data-feather="message-square"></i></div><div class="fs-title">Internal Notes (Optional)</div></div>
          <div class="fs-body"><textarea name="internal_notes" class="form-control f-ctrl" rows="2" placeholder="Any special instructions…"></textarea></div>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn-submit" style="--h-indigo:#F59E0B;--h-violet:#D97706;color:#fff;"><i data-feather="check-circle"></i> Confirm Cab Booking</button>
        </div>
      </form>
    </div>

    {{-- ════════════════════════════════════
         TOUR PACKAGES FORM
    ════════════════════════════════════ --}}
    <div class="cb-form-section" id="form-tour">
      <form action="{{ route('bookings.store-direct') }}" method="POST" id="form-tour-submit">
        @csrf

        <div class="nb-layout">

        {{-- ══════ LEFT COLUMN ══════ --}}
        <div class="nb-main">

          {{-- 1. Customer --}}
          <div class="nb-card">
            <div class="nb-card-head">
              <div class="nb-card-icon" style="background:#EDE9FE;color:#7C3AED;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
              <div class="nb-card-title">Customer Information</div>
            </div>
            <div class="nb-card-body">
              <div class="row g-3">
                <div class="col-md-3"><label class="nb-label">Guest Name <span class="nb-req">*</span></label><input type="text" name="guest_name" class="form-control nb-input" required placeholder="Full name"></div>
                <div class="col-md-3"><label class="nb-label">Phone <span class="nb-req">*</span></label>
                  <div class="nb-phone-wrap"><span class="nb-phone-prefix">+91</span><input type="tel" name="phone" class="form-control nb-input nb-input-phone" required placeholder="Mobile" maxlength="10"></div>
                </div>
                <div class="col-md-3"><label class="nb-label">Email</label><input type="email" name="email" class="form-control nb-input" placeholder="email@example.com"></div>
                <div class="col-md-3"><label class="nb-label">Lead Source <span class="nb-req">*</span></label>
                  <select name="lead_source_id" class="form-select nb-input" required>
                    <option value="">Select…</option>
                    @foreach($leadSources as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>

          {{-- 2. Tour Info --}}
          <div class="nb-card">
            <div class="nb-card-head">
              <div class="nb-card-icon" style="background:#EDE9FE;color:#7C3AED;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </div>
              <div class="nb-card-title">Tour Package Info</div>
            </div>
            <div class="nb-card-body">
              <div class="row g-3">
                <div class="col-md-5"><label class="nb-label">Package Name <span class="nb-req">*</span></label><input type="text" id="tour_name" class="form-control nb-input" placeholder="e.g. Kashi Darshan 3D/2N" oninput="buildTourPlan()"></div>
                <div class="col-md-2"><label class="nb-label">Adults</label><input type="number" id="tour_adults" class="form-control nb-input" min="1" value="2" oninput="buildTourPlan()"></div>
                <div class="col-md-2"><label class="nb-label">Children</label><input type="number" id="tour_children" class="form-control nb-input" min="0" value="0" oninput="buildTourPlan()"></div>
                <div class="col-md-3"><label class="nb-label">Booking Date <span class="nb-req">*</span></label><input type="date" id="tour_start" class="form-control nb-input" required oninput="buildTourPlan()"></div>
              </div>
            </div>
          </div>

          {{-- 3. B2B Expense Breakdown — date range wise --}}
          <div class="nb-card">
            <div class="nb-card-head">
              <div class="nb-card-icon" style="background:#FFF7ED;color:#F59E0B;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
              </div>
              <div class="nb-card-title">B2B Expense Breakdown <span style="font-size:.7rem;font-weight:400;color:#94A3B8;">(Vendor / Actual Cost)</span></div>
            </div>
            <div class="nb-card-body" style="padding-bottom:8px;">

              {{-- HOTEL --}}
              <div class="tp-svc-block" id="tp-hotel-block">
                <div class="tp-svc-header">
                  <label class="tp-svc-toggle">
                    <input type="checkbox" id="tp-hotel-on" onchange="tpToggle('hotel')" checked>
                    <span class="tp-svc-dot" style="background:#10B981;"></span>
                  </label>
                  <span class="tp-svc-icon">🏨</span>
                  <span class="tp-svc-name">Hotel / Stay</span>
                  <span class="tp-svc-cost" id="tp-hotel-cost-badge">₹0</span>
                </div>
                <div class="tp-svc-body" id="tp-hotel-body">
                  <div class="row g-2">
                    <div class="col-md-4"><label class="nb-label">Hotel Name</label><input type="text" id="tp_hotel_name" class="form-control nb-input" placeholder="e.g. Ganges View Hotel"></div>
                    <div class="col-md-2"><label class="nb-label">Check-in</label><input type="date" id="tp_hotel_from" class="form-control nb-input" oninput="tpCalcHotel()"></div>
                    <div class="col-md-2"><label class="nb-label">Check-out</label><input type="date" id="tp_hotel_to" class="form-control nb-input" oninput="tpCalcHotel()"></div>
                    <div class="col-md-2"><label class="nb-label">Nights</label><input type="number" id="tp_hotel_nights" class="form-control nb-input" min="1" value="1" readonly style="background:#F8FAFC !important;color:#64748B !important;"></div>
                    <div class="col-md-2"><label class="nb-label">Cost (₹) <span class="nb-req">*</span></label>
                      <div class="nb-rupee-wrap"><span class="nb-rupee">₹</span><input type="number" id="tp_hotel_cost" class="form-control nb-input nb-rupee-input" min="0" value="0" oninput="calcTourMargin()"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tp-divider"></div>

              {{-- CAB --}}
              <div class="tp-svc-block" id="tp-cab-block">
                <div class="tp-svc-header">
                  <label class="tp-svc-toggle">
                    <input type="checkbox" id="tp-cab-on" onchange="tpToggle('cab')" checked>
                    <span class="tp-svc-dot" style="background:#F59E0B;"></span>
                  </label>
                  <span class="tp-svc-icon">🚕</span>
                  <span class="tp-svc-name">Cab / Transport</span>
                  <span class="tp-svc-cost" id="tp-cab-cost-badge">₹0</span>
                </div>
                <div class="tp-svc-body" id="tp-cab-body">
                  <div class="row g-2">
                    <div class="col-md-3"><label class="nb-label">Vehicle Type</label>
                      <select id="tp_cab_type" class="form-select nb-input">
                        <option>Sedan / Swift Dzire</option>
                        <option>SUV / Innova</option>
                        <option>Tempo Traveller (12 Seat)</option>
                        <option>Tempo Traveller (20 Seat)</option>
                        <option>Bus (26+ Seat)</option>
                        <option>Other</option>
                      </select>
                    </div>
                    <div class="col-md-3"><label class="nb-label">Route / Destination</label><input type="text" id="tp_cab_route" class="form-control nb-input" placeholder="e.g. Airport to Hotel"></div>
                    <div class="col-md-2"><label class="nb-label">From Date</label><input type="date" id="tp_cab_from" class="form-control nb-input"></div>
                    <div class="col-md-2"><label class="nb-label">To Date</label><input type="date" id="tp_cab_to" class="form-control nb-input"></div>
                    <div class="col-md-2"><label class="nb-label">Cost (₹) <span class="nb-req">*</span></label>
                      <div class="nb-rupee-wrap"><span class="nb-rupee">₹</span><input type="number" id="tp_cab_cost" class="form-control nb-input nb-rupee-input" min="0" value="0" oninput="calcTourMargin()"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tp-divider"></div>

              {{-- BOAT --}}
              <div class="tp-svc-block" id="tp-boat-block">
                <div class="tp-svc-header">
                  <label class="tp-svc-toggle">
                    <input type="checkbox" id="tp-boat-on" onchange="tpToggle('boat')" checked>
                    <span class="tp-svc-dot" style="background:#0EA5E9;"></span>
                  </label>
                  <span class="tp-svc-icon">⛵</span>
                  <span class="tp-svc-name">Boat / River Ride</span>
                  <span class="tp-svc-cost" id="tp-boat-cost-badge">₹0</span>
                </div>
                <div class="tp-svc-body" id="tp-boat-body">
                  <div class="row g-2">
                    <div class="col-md-3"><label class="nb-label">Boat Type</label>
                      <select id="tp_boat_type" class="form-select nb-input">
                        <option>Normal Motor Boat</option>
                        <option>Light Motor Boat</option>
                        <option>Premium Motor Boat</option>
                        <option>Luxury Yacht</option>
                        <option>Bajra Boat</option>
                        <option>Cruise</option>
                      </select>
                    </div>
                    <div class="col-md-3"><label class="nb-label">Ride Type</label>
                      <select id="tp_boat_ride" class="form-select nb-input">
                        <option>Morning Boat Ride</option>
                        <option>Evening Boat Ride</option>
                        <option>Ganga Aarti Evening</option>
                        <option>Sunrise Ride</option>
                        <option>Full Day</option>
                      </select>
                    </div>
                    <div class="col-md-2"><label class="nb-label">Date</label><input type="date" id="tp_boat_date" class="form-control nb-input"></div>
                    <div class="col-md-2"><label class="nb-label">Time</label><input type="time" id="tp_boat_time" class="form-control nb-input"></div>
                    <div class="col-md-2"><label class="nb-label">Cost (₹) <span class="nb-req">*</span></label>
                      <div class="nb-rupee-wrap"><span class="nb-rupee">₹</span><input type="number" id="tp_boat_cost" class="form-control nb-input nb-rupee-input" min="0" value="0" oninput="calcTourMargin()"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tp-divider"></div>

              {{-- GUIDE --}}
              <div class="tp-svc-block" id="tp-guide-block">
                <div class="tp-svc-header">
                  <label class="tp-svc-toggle">
                    <input type="checkbox" id="tp-guide-on" onchange="tpToggle('guide')" checked>
                    <span class="tp-svc-dot" style="background:#8B5CF6;"></span>
                  </label>
                  <span class="tp-svc-icon">🧭</span>
                  <span class="tp-svc-name">Guide</span>
                  <span class="tp-svc-cost" id="tp-guide-cost-badge">₹0</span>
                </div>
                <div class="tp-svc-body" id="tp-guide-body">
                  <div class="row g-2">
                    <div class="col-md-4"><label class="nb-label">Guide Name</label><input type="text" id="tp_guide_name" class="form-control nb-input" placeholder="e.g. Ramesh Kumar"></div>
                    <div class="col-md-2"><label class="nb-label">From Date</label><input type="date" id="tp_guide_from" class="form-control nb-input"></div>
                    <div class="col-md-2"><label class="nb-label">To Date</label><input type="date" id="tp_guide_to" class="form-control nb-input"></div>
                    <div class="col-md-2"><label class="nb-label">Language</label>
                      <select id="tp_guide_lang" class="form-select nb-input">
                        <option>Hindi</option><option>English</option><option>Hindi + English</option><option>Foreign Language</option>
                      </select>
                    </div>
                    <div class="col-md-2"><label class="nb-label">Cost (₹) <span class="nb-req">*</span></label>
                      <div class="nb-rupee-wrap"><span class="nb-rupee">₹</span><input type="number" id="tp_guide_cost" class="form-control nb-input nb-rupee-input" min="0" value="0" oninput="calcTourMargin()"></div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Expense Total bar --}}
              <div class="tp-expense-total">
                <div class="tp-exp-item">
                  <span>🏨</span><span id="tp-hotel-exp-lbl">Hotel</span><strong id="tp-hotel-exp-val">₹0</strong>
                </div>
                <span class="tp-exp-plus">+</span>
                <div class="tp-exp-item">
                  <span>🚕</span><span>Cab</span><strong id="tp-cab-exp-val">₹0</strong>
                </div>
                <span class="tp-exp-plus">+</span>
                <div class="tp-exp-item">
                  <span>⛵</span><span>Boat</span><strong id="tp-boat-exp-val">₹0</strong>
                </div>
                <span class="tp-exp-plus">+</span>
                <div class="tp-exp-item">
                  <span>🧭</span><span>Guide</span><strong id="tp-guide-exp-val">₹0</strong>
                </div>
                <span class="tp-exp-plus">=</span>
                <div class="tp-exp-item tp-exp-total">
                  <span>Total</span><strong id="tp-total-exp-val">₹0</strong>
                </div>
              </div>

              <input type="hidden" name="vendor_cost" id="tp-vendor-cost-hidden" value="0">
            </div>
          </div>

          {{-- 4. Trip Summary --}}
          <div class="nb-card">
            <div class="nb-card-head">
              <div class="nb-card-icon" style="background:#EDE9FE;color:#7C3AED;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              </div>
              <div class="nb-card-title">Trip Summary <span style="font-size:.7rem;font-weight:400;color:#94A3B8;">(auto-filled, editable)</span></div>
            </div>
            <div class="nb-card-body">
              <textarea name="short_plan" id="tour-short-plan" class="form-control nb-input" rows="3" required placeholder="Auto-filled from services above…" style="resize:none;"></textarea>
            </div>
          </div>

          {{-- 5. Itinerary --}}
          <div class="nb-card">
            <div class="nb-card-head">
              <div class="nb-card-icon" style="background:#EDE9FE;color:#7C3AED;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7"/></svg>
              </div>
              <div class="nb-card-title">Itinerary <span style="font-size:.7rem;font-weight:400;color:#94A3B8;">(optional)</span></div>
            </div>
            <div class="nb-card-body">
              <textarea name="tour_plan" id="tour_itinerary" class="form-control nb-input" rows="4" placeholder="Day-by-day itinerary…" style="resize:vertical;"></textarea>
            </div>
          </div>

          <div class="nb-card">
            <div class="nb-card-body" style="padding:14px 20px;">
              <textarea name="internal_notes" class="form-control nb-input" rows="2" placeholder="Internal notes (staff only)…" style="resize:none;"></textarea>
            </div>
          </div>

        </div>{{-- /nb-main --}}

        {{-- ══════ RIGHT SIDEBAR ══════ --}}
        <div class="nb-sidebar">

          {{-- Booking Amount --}}
          <div class="nb-sidebar-card">
            <div class="nb-sidebar-head">
              <div class="nb-card-icon" style="background:#EDE9FE;color:#7C3AED;width:28px;height:28px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
              </div>
              <div class="nb-card-title" style="font-size:.9rem;">Booking Amount</div>
            </div>

            <div class="nb-amount-row">
              <label class="nb-label">Total Booking Amount (₹) <span class="nb-req">*</span></label>
              <div class="nb-rupee-wrap">
                <span class="nb-rupee">₹</span>
                <input type="number" name="services[0][unit_price]" id="tour-booking-amount"
                       class="form-control nb-input nb-rupee-input"
                       placeholder="Total charged to client" min="0" step="1" required
                       style="font-size:1.1rem !important;font-weight:700 !important;"
                       oninput="calcTourMargin();calcTourBalance()">
              </div>
            </div>

            <div class="nb-amount-row">
              <label class="nb-label">Discount (₹)</label>
              <div class="nb-rupee-wrap"><span class="nb-rupee">₹</span>
                <input type="number" name="discount" id="tour-discount" class="form-control nb-input nb-rupee-input" value="0" min="0" oninput="calcTourBalance();calcTourMargin()">
              </div>
            </div>

            <div class="nb-amount-row">
              <label class="nb-label">Advance Paid (₹)</label>
              <div class="nb-rupee-wrap"><span class="nb-rupee">₹</span>
                <input type="number" name="advance_paid" id="tour-advance" class="form-control nb-input nb-rupee-input" value="0" min="0" oninput="calcTourBalance()">
              </div>
            </div>

            <div class="nb-balance-box">
              <div class="nb-balance-label">Balance Due</div>
              <div class="nb-balance-row">
                <div class="nb-balance-amount" id="tour-balance">₹0</div>
                <span class="nb-balance-badge due" id="tour-pay-badge">DUE</span>
              </div>
            </div>

            <div class="nb-amount-row">
              <label class="nb-label">Payment Status</label>
              <select name="payment_status" id="tour-pay-status" class="form-select nb-input" onchange="updateTourPayBadge()">
                <option value="due">Due</option>
                <option value="paid">Paid</option>
              </select>
            </div>
            <div class="nb-amount-row" style="margin-bottom:0;">
              <label class="nb-label">Payment Method</label>
              <select name="payment_method" class="form-select nb-input">
                <option value="">Select…</option>
                <option value="cash">Cash</option>
                <option value="upi">UPI / GPay / PhonePe</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cheque">Cheque</option>
              </select>
            </div>
          </div>

          {{-- Marginal Profit box --}}
          <div class="nb-sidebar-card" style="padding:16px;">
            <div style="font-size:.7rem;font-weight:700;color:#92400E;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">💰 Marginal Profit</div>

            <div style="display:flex;justify-content:space-between;font-size:.8rem;color:#475569;padding:5px 0;border-bottom:1px dashed #E2E8F0;">
              <span>Booking Amount</span><span id="tp-summary-booking" style="font-weight:600;color:#222;">₹0</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.8rem;color:#475569;padding:5px 0;border-bottom:1px dashed #E2E8F0;">
              <span>Total Expense (B2B)</span><span id="tp-summary-expense" style="font-weight:600;color:#EF4444;">₹0</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:8px 0 0;font-weight:700;">
              <span style="color:#065F46;">Marginal Profit</span>
              <span id="tp-summary-profit" style="color:#065F46;font-size:1.1rem;">₹0</span>
            </div>
            <div style="text-align:right;margin-top:2px;">
              <span id="tp-summary-pct" style="font-size:.75rem;font-weight:700;background:#D1FAE5;color:#065F46;padding:2px 8px;border-radius:20px;">0%</span>
            </div>
            <div style="font-size:.68rem;color:#94A3B8;margin-top:8px;text-align:center;">Booking − Expenses = Profit</div>
          </div>

          {{-- Status --}}
          <div class="nb-sidebar-card" style="padding:14px 16px;">
            <label class="nb-label">Booking Status</label>
            <input type="text" class="form-control nb-input" value="✅ Confirmed" readonly style="background:#F0FDF4 !important;color:#15803D !important;font-weight:600;">
            <input type="hidden" name="booking_status" value="confirmed">
          </div>

          <button type="submit" form="form-tour-submit" class="btn-submit" style="width:100%;justify-content:center;background:linear-gradient(135deg,#7C3AED,#5B21B6);">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" width="17" height="17" stroke="white" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Confirm Tour Booking
          </button>

        </div>{{-- /nb-sidebar --}}
        </div>{{-- /nb-layout --}}

        {{-- Hidden backend fields --}}
        <input type="hidden" name="services[0][service_template_id]" value="{{ optional($serviceTypes->first()?->serviceTemplates->first())->id }}">
        <input type="hidden" name="services[0][quantity]" value="1">
        <input type="hidden" name="services[0][service_date]" id="tour-svc-date">

      </form>
    </div>

  </div>{{-- /cb-forms --}}
</div>

<script>
/* ── Type selection ── */
let activeType = null;
const typeLabels = {boat:'Boat Booking',stay:'Stay Booking',cab:'Cab Booking',tour:'Tour Package Booking'};

function selectType(type) {
  if (type === 'cab') {
    window.location.href = '{{ route("cab-bookings.create") }}';
    return;
  }
  document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
  document.getElementById('tc-' + type).classList.add('active');
  activeType = type;
  document.querySelectorAll('.cb-form-section').forEach(f => f.classList.remove('active'));
  document.getElementById('form-' + type).classList.add('active');
  document.getElementById('step2').style.display = 'block';
  document.getElementById('step2-title').textContent = typeLabels[type];
  if (type !== 'boat') autoSelectServiceType(type);
  if (type === 'boat') autoSelectBoatServiceType();
  feather.replace();
  document.getElementById('step2').scrollIntoView({behavior:'smooth',block:'start'});
}

function resetType() {
  document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
  document.querySelectorAll('.cb-form-section').forEach(f => f.classList.remove('active'));
  document.getElementById('step2').style.display = 'none';
  activeType = null;
  window.scrollTo({top:0,behavior:'smooth'});
}

/* ── Boat state ── */
let selectedBoat = null;
let boatPax = 1;

const BOAT_DATA = {
  'normal-motor':  {name:'Normal Motor Boat',    base:4000,  extra:300, basePax:10, maxPax:25},
  'light-motor':   {name:'Light Motor Boat',      base:5000,  extra:300, basePax:10, maxPax:25},
  'premium-light': {name:'Premium Light Motor Boat', base:6000, extra:300, basePax:10, maxPax:25},
  'luxury-yacht':  {name:'Luxury Mini Yacht',     base:8500,  extra:500, basePax:10, maxPax:20},
  'bajra':         {name:'Bajra Boat',            base:11999, extra:500, basePax:40, maxPax:50},
  'cruise':        {name:'Cruise',                base:30000, extra:400, basePax:60, maxPax:150},
};

function selectBoat(card) {
  document.querySelectorAll('.boat-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  selectedBoat = BOAT_DATA[card.dataset.boat];

  document.getElementById('pax-base-persons').textContent = selectedBoat.basePax;
  document.getElementById('pax-extra-rate').textContent = '₹' + selectedBoat.extra.toLocaleString('en-IN');
  document.getElementById('pax-base-price-show').textContent = '₹' + selectedBoat.base.toLocaleString('en-IN');
  document.getElementById('pax-price-preview').style.display = 'block';
  document.getElementById('pax-info').textContent = 'Max ' + selectedBoat.maxPax + ' persons';

  calcBoatTotal();
  buildBoatPlan();
}

let boatChildren = 0;
function changeChildren(delta) {
  boatChildren = Math.max(0, boatChildren + delta);
  document.getElementById('children-display').textContent = boatChildren;
  document.getElementById('boat_children').value = boatChildren;
  buildBoatPlan();
}

function changePax(delta) {
  boatPax = Math.max(1, boatPax + delta);
  if (selectedBoat && boatPax > selectedBoat.maxPax) {
    boatPax = selectedBoat.maxPax;
    document.getElementById('boat-cap-warning').style.display = 'block';
  } else {
    document.getElementById('boat-cap-warning').style.display = 'none';
  }
  document.getElementById('pax-display').textContent = boatPax;
  document.getElementById('boat_pax').value = boatPax;
  document.getElementById('boat-qty').value = boatPax;
  calcBoatTotal();
  buildBoatPlan();
}

function calcBoatTotal() {
  if (!selectedBoat) return;
  const extra = Math.max(0, boatPax - selectedBoat.basePax);
  const extraCost = extra * selectedBoat.extra;
  const subtotal = selectedBoat.base + extraCost;

  if (extra > 0) {
    document.getElementById('pax-extra-row').style.display = 'flex';
    document.getElementById('pax-extra-count').textContent = extra;
    document.getElementById('pax-extra-cost').textContent = '₹' + extraCost.toLocaleString('en-IN');
  } else {
    document.getElementById('pax-extra-row').style.display = 'none';
  }
  document.getElementById('pax-subtotal').textContent = '₹' + subtotal.toLocaleString('en-IN');
  document.getElementById('boat-price-field').value = subtotal;
  const mobTotal = document.getElementById('mob-total');
  if (mobTotal) mobTotal.textContent = '₹' + subtotal.toLocaleString('en-IN');
  calcBoatBalance();
}

function calcBoatBalance() {
  const total   = parseFloat(document.getElementById('boat-price-field').value || 0);
  const discount= parseFloat(document.getElementById('boat-discount').value || 0);
  const advance = parseFloat(document.getElementById('boat-advance').value || 0);
  const balance = Math.max(0, total - discount - advance);
  document.getElementById('boat-balance').textContent = '₹' + balance.toLocaleString('en-IN');
  document.getElementById('mob-balance').textContent  = 'Balance: ₹' + balance.toLocaleString('en-IN');
  calcBoatMargin();
}

function calcBoatMargin() {
  const total   = parseFloat(document.getElementById('boat-price-field').value || 0);
  const discount= parseFloat(document.getElementById('boat-discount').value || 0);
  const vendor  = parseFloat(document.getElementById('boat-vendor-cost').value || 0);
  const net     = total - discount;
  const margin  = net - vendor;
  const pct     = net > 0 ? Math.round((margin / net) * 100) : 0;
  document.getElementById('boat-margin').textContent    = '₹' + Math.max(0, margin).toLocaleString('en-IN');
  document.getElementById('boat-margin-pct').textContent= Math.max(0, pct) + '%';
}

function updateBoatPayBadge() {
  const s = document.getElementById('boat-pay-status').value;
  const b = document.getElementById('boat-pay-badge');
  b.textContent = s === 'paid' ? 'PAID' : 'DUE';
  b.className   = 'nb-balance-badge ' + s;
}

function autoSelectBoatServiceType() {
  // no-op: service template auto-seeded from server via hidden input
}

function buildBoatPlan() {
  const date    = document.getElementById('boat_booking_date').value;
  const type    = document.getElementById('boat_type').value;
  const event   = document.getElementById('boat_event').value;
  const pickup  = document.getElementById('boat_pickup_ghat').value;
  const drop    = document.getElementById('boat_drop_ghat').value;
  const time    = document.getElementById('boat_time').value;
  const boatName= selectedBoat ? selectedBoat.name : '';
  const svcDate = document.getElementById('boat-svc-date');
  if (svcDate && date) svcDate.value = date;
  let plan = type || 'Boat booking';
  if (boatName) plan += ' — ' + boatName;
  if (date)   plan += ' | ' + formatDate(date);
  if (time)   plan += ', ' + time;
  if (pickup) plan += ' | From: ' + pickup;
  if (drop && drop !== 'Same as Pickup') plan += ' → ' + drop;
  if (boatPax) plan += ' | Adults: ' + boatPax;
  if (boatChildren > 0) plan += ', Children: ' + boatChildren;
  if (event && event !== 'None / Regular Ride') plan += ' | ' + event;
  document.getElementById('boat-short-plan').value = plan;
}

/* ── Legacy service type auto-select (Stay/Cab/Tour) ── */
const TYPE_KEYWORDS = {
  stay: ['stay','hotel','homestay','accommodation','lodge'],
  cab:  ['cab','car','taxi','transfer','transport'],
  tour: ['tour','package','sightseeing','pilgrimage']
};
function autoSelectServiceType(formType) {
  const form = document.getElementById('form-' + formType);
  if (!form) return;
  const sel = form.querySelector('.svc-type-sel');
  if (!sel) return;
  const keywords = TYPE_KEYWORDS[formType] || [];
  for (const opt of sel.options) {
    if (keywords.some(k => opt.textContent.toLowerCase().includes(k))) {
      sel.value = opt.value;
      sel.dispatchEvent(new Event('change'));
      break;
    }
  }
}

function loadTemplates(typeSelect, targetId, priceId) {
  const target = document.getElementById(targetId);
  if (!target) return;
  target.innerHTML = '<option value="">Select service</option>';
  if (!typeSelect.value) { target.disabled = true; return; }
  const templates = JSON.parse(typeSelect.options[typeSelect.selectedIndex].dataset.templates || '[]');
  templates.forEach(tpl => {
    const o = document.createElement('option');
    o.value = tpl.id;
    o.dataset.price = tpl.default_selling_price;
    o.textContent = tpl.name + ' (₹' + parseFloat(tpl.default_selling_price).toFixed(0) + ')';
    target.appendChild(o);
  });
  target.disabled = false;
}

function pickPrice(select, priceId) {
  const opt = select.options[select.selectedIndex];
  const p = document.getElementById(priceId);
  if (p && opt && opt.dataset.price) {
    p.value = parseFloat(opt.dataset.price).toFixed(2);
    p.dispatchEvent(new Event('input'));
  }
}

function updateSummary(summaryId, priceId) {
  const p = parseFloat(document.getElementById(priceId)?.value || 0);
  const totalEl = document.getElementById(summaryId.replace('-summary','-total'));
  const wordsEl = document.getElementById(summaryId.replace('-summary','-words'));
  if (totalEl) totalEl.textContent = '₹' + p.toLocaleString('en-IN');
  if (wordsEl) wordsEl.textContent = numberToWords(Math.floor(p));
}

/* ── STAY: Hotel card selection ── */
let selectedHotelName      = '';
let selectedHotelPrice     = 0;
let selectedCustomRoomType = '';
let selectedExtraBed       = false;
let selectedExtraBedPrice  = 0;

function selectHotel(card) {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');

  selectedHotelName  = card.dataset.name;
  selectedHotelPrice = parseFloat(card.dataset.price || 0);

  document.getElementById('stay-svc-tmpl-id').value = card.dataset.id;
  document.getElementById('stay-custom-hotel-wrap').style.display = 'none';

  recalcStayTotal();
  buildStayPlan();
}

function selectHotelCustom() {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('hotel-card-custom').classList.add('selected');

  selectedHotelName      = '';
  selectedHotelPrice     = 0;
  selectedCustomRoomType = '';
  selectedExtraBed       = false;
  selectedExtraBedPrice  = 0;
  document.getElementById('stay-svc-tmpl-id').value = '';

  /* Show the mini-form, reset all fields */
  const wrap = document.getElementById('stay-custom-hotel-wrap');
  wrap.style.display = 'block';
  document.getElementById('stay_hotel').value                          = '';
  document.getElementById('stay_custom_price').value                   = '';
  document.getElementById('stay_custom_room_type').value               = '';
  document.getElementById('stay_custom_extra_bed').checked             = false;
  document.getElementById('stay-extra-bed-price-wrap').style.display   = 'none';
  document.getElementById('stay_extra_bed_price').value                = '';
  document.getElementById('stay-custom-error').style.display           = 'none';
  document.getElementById('stay_hotel').focus();

  const priceEl = document.getElementById('stay-price-field');
  if (priceEl) { priceEl.value = ''; priceEl.style.background = ''; }
  updateStayPerNightNote();
  buildStayPlan();
}

function clearHotel() {
  document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
  const wrap = document.getElementById('stay-custom-hotel-wrap');
  if (wrap) wrap.style.display = 'none';
  selectedHotelName      = '';
  selectedHotelPrice     = 0;
  selectedCustomRoomType = '';
  selectedExtraBed       = false;
  selectedExtraBedPrice  = 0;
  document.getElementById('stay-svc-tmpl-id').value = '';
  const priceEl = document.getElementById('stay-price-field');
  if (priceEl) { priceEl.value = ''; priceEl.style.background = ''; }
  const autoTag = document.getElementById('stay-price-auto-tag');
  if (autoTag) autoTag.style.display = 'none';
  calcStayBalance();
  updateStayPerNightNote();
  buildStayPlan();
}

function onCustomRoomTypeChange() {
  const sel      = document.getElementById('stay_custom_room_type');
  const priceEl  = document.getElementById('stay_custom_price');
  const opt      = sel.options[sel.selectedIndex];
  const suggested = opt?.dataset?.price;
  if (suggested && (!priceEl.value || parseFloat(priceEl.value) === 0)) {
    priceEl.value = suggested;
  }
}

function toggleExtraBedPrice() {
  const cb   = document.getElementById('stay_custom_extra_bed');
  const wrap = document.getElementById('stay-extra-bed-price-wrap');
  if (cb.checked) {
    wrap.style.display = 'flex';
    document.getElementById('stay_extra_bed_price').focus();
  } else {
    wrap.style.display = 'none';
    document.getElementById('stay_extra_bed_price').value = '';
  }
}

function changeStayAdults(delta) {
  const el   = document.getElementById('stay_guests');
  const disp = document.getElementById('stay-adults-display');
  let val = parseInt(el.value || 0) + delta;
  if (val < 0) val = 0;
  el.value         = val;
  disp.textContent = val;
  buildStayPlan();
}

function changeStayKids(delta) {
  const el   = document.getElementById('stay_kids');
  const disp = document.getElementById('stay-kids-display');
  let val = parseInt(el.value || 0) + delta;
  if (val < 0) val = 0;
  el.value         = val;
  disp.textContent = val;
  buildStayPlan();
}

function saveCustomHotel() {
  const nameEl    = document.getElementById('stay_hotel');
  const priceEl   = document.getElementById('stay_custom_price');
  const rtEl      = document.getElementById('stay_custom_room_type');
  const extraCb   = document.getElementById('stay_custom_extra_bed');
  const extraPrEl = document.getElementById('stay_extra_bed_price');
  const errEl     = document.getElementById('stay-custom-error');
  const name      = (nameEl?.value || '').trim();
  const price     = parseFloat(priceEl?.value || 0);
  const roomType  = rtEl?.value || '';
  const hasExtra  = extraCb?.checked || false;
  const extraPrice= hasExtra ? parseFloat(extraPrEl?.value || 0) : 0;

  /* Validate */
  if (!name) {
    errEl.textContent   = '⚠ Please enter the property name.';
    errEl.style.display = 'block';
    nameEl.focus();
    return;
  }
  if (!price || price <= 0) {
    errEl.textContent   = '⚠ Please enter a valid price per night.';
    errEl.style.display = 'block';
    priceEl.focus();
    return;
  }
  errEl.style.display = 'none';

  /* Store globally for buildStayPlan */
  selectedHotelName      = name;
  selectedHotelPrice     = price;
  selectedCustomRoomType = roomType;
  selectedExtraBed       = hasExtra;
  selectedExtraBedPrice  = extraPrice;

  /* Recalculate total using new price × nights × rooms */
  recalcStayTotal();

  /* Update the custom card label */
  const card = document.getElementById('hotel-card-custom');
  card.querySelector('.hotel-card-name').textContent = name + (roomType ? ' · ' + roomType : '');
  let priceHtml = '₹' + price.toLocaleString('en-IN') + '<span class="hotel-card-per">/night</span>';
  if (hasExtra && extraPrice > 0) {
    priceHtml += '<br><span style="font-size:.62rem;color:#7C3AED;font-weight:600;">+₹' + extraPrice.toLocaleString('en-IN') + ' extra bed</span>';
  }
  card.querySelector('.hotel-card-price').innerHTML = priceHtml;
  card.querySelector('.hotel-card-icon').textContent = '🏨';

  /* Collapse the mini-form */
  document.getElementById('stay-custom-hotel-wrap').style.display = 'none';

  updateStayPerNightNote();
  buildStayPlan();
}

function recalcStayTotal() {
  if (selectedHotelPrice <= 0) return;
  const nights   = parseInt(document.getElementById('stay_nights')?.value) || 0;
  const rooms    = parseInt(document.getElementById('stay_rooms')?.value)  || 1;
  const extraBed = parseFloat(document.getElementById('stay_extra_bed_amt')?.value || 0);
  const roomTotal    = selectedHotelPrice * nights * rooms;
  const extraTotal   = extraBed * nights;
  const total        = roomTotal + extraTotal;
  const priceEl = document.getElementById('stay-price-field');
  if (priceEl) {
    priceEl.value = total > 0 ? total.toFixed(2) : '';
    priceEl.style.background = total > 0 ? '#F0FDF4' : '';
  }
  const autoTag = document.getElementById('stay-price-auto-tag');
  if (autoTag) autoTag.style.display = total > 0 ? 'inline-flex' : 'none';
  calcStayBalance();
  updateStayPerNightNote();
}

function updateStayPerNightNote() {
  const nights   = parseInt(document.getElementById('stay_nights')?.value) || 0;
  const rooms    = parseInt(document.getElementById('stay_rooms')?.value)  || 1;
  const extraBed = parseFloat(document.getElementById('stay_extra_bed_amt')?.value || 0);
  const noteEl   = document.getElementById('stay-per-night-note');
  if (!noteEl) return;
  if (selectedHotelPrice > 0 && nights > 0) {
    let parts = ['₹' + selectedHotelPrice.toLocaleString('en-IN', {maximumFractionDigits:0}) + '/room'];
    if (rooms > 1) parts.push(rooms + ' rooms');
    parts.push(nights + ' night' + (nights > 1 ? 's' : ''));
    let html = parts.join(' <span style="color:#CBD5E1;">×</span> ');
    if (extraBed > 0) {
      html += ' <span style="color:#CBD5E1;">+</span> <span style="color:#8B5CF6;">₹' + extraBed.toLocaleString('en-IN', {maximumFractionDigits:0}) + ' extra bed × ' + nights + 'N</span>';
    }
    noteEl.style.display = 'block';
    noteEl.innerHTML = html;
  } else {
    noteEl.style.display = 'none';
  }
}

function calcStayBalance() {
  const total    = parseFloat(document.getElementById('stay-price-field')?.value || 0);
  const discount = parseFloat(document.getElementById('stay-discount')?.value || 0);
  const advance  = parseFloat(document.getElementById('stay-advance')?.value || 0);
  const net      = Math.max(0, total - discount);
  const balance  = Math.max(0, net - advance);
  const pct      = total > 0 ? Math.min(100, Math.round((advance / total) * 100)) : 0;
  const fmt      = v => '₹' + v.toLocaleString('en-IN', {maximumFractionDigits:0});

  // Balance Due (legacy + new)
  const balEl = document.getElementById('stay-balance');
  if (balEl) balEl.textContent = fmt(balance);

  // Summary card rows
  const sumTotal = document.getElementById('stay-sum-total');
  if (sumTotal) sumTotal.textContent = fmt(total);

  const sumAdv = document.getElementById('stay-sum-advance');
  if (sumAdv) sumAdv.textContent = fmt(advance);

  // Discount row
  const discRow = document.getElementById('stay-sum-discount-row');
  const discVal = document.getElementById('stay-sum-discount');
  if (discRow) discRow.style.display = discount > 0 ? '' : 'none';
  if (discVal) discVal.textContent = '-' + fmt(discount);

  // Progress bar
  const bar = document.getElementById('stay-sum-bar');
  const pctEl = document.getElementById('stay-sum-pct');
  if (bar) bar.style.width = pct + '%';
  if (pctEl) pctEl.textContent = pct + '%';

  // Formula
  const nights = parseInt(document.getElementById('stay_nights')?.value) || 0;
  const rooms  = parseInt(document.getElementById('stay_rooms')?.value)  || 1;
  const fmEl   = document.getElementById('stay-sum-formula');
  if (fmEl) {
    if (selectedHotelPrice > 0 && nights > 0) {
      const rateStr = '₹' + selectedHotelPrice.toLocaleString('en-IN', {maximumFractionDigits:0});
      fmEl.textContent = rateStr + '/room × ' + nights + 'N × ' + rooms + 'R';
      fmEl.style.display = '';
    } else {
      fmEl.style.display = 'none';
    }
  }

  updateStayPayBadge();
  calcStayMargin();
  updateStayPerNightNote();
}

function calcStayMargin() {
  const total   = parseFloat(document.getElementById('stay-price-field')?.value || 0);
  const discount= parseFloat(document.getElementById('stay-discount')?.value || 0);
  const vendor  = parseFloat(document.getElementById('stay-vendor-cost')?.value || 0);
  const net     = total - discount;
  const margin  = net - vendor;
  const pct     = net > 0 ? Math.round((margin / net) * 100) : 0;
  const mEl = document.getElementById('stay-margin');
  const pEl = document.getElementById('stay-margin-pct');
  if (mEl) mEl.textContent = '₹' + Math.max(0, margin).toLocaleString('en-IN');
  if (pEl) pEl.textContent = Math.max(0, pct) + '%';
}

function updateStayPayBadge() {
  const s = document.getElementById('stay-pay-status')?.value;
  const b = document.getElementById('stay-pay-badge');
  if (!b) return;
  const map = { paid:'PAID', due:'DUE' };
  b.textContent = map[s] || 'PENDING';
  b.className   = 'nb-balance-badge ' + (s || 'due');
}

function buildStayPlan() {
  const ci      = document.getElementById('stay_checkin')?.value;
  const co      = document.getElementById('stay_checkout')?.value;
  const ciTime  = document.getElementById('stay_checkin_time')?.value;
  const coTime  = document.getElementById('stay_checkout_time')?.value;
  const nights  = document.getElementById('stay_nights')?.value;
  const rooms   = document.getElementById('stay_rooms')?.value;
  const adults  = document.getElementById('stay_guests')?.value;
  const kids    = document.getElementById('stay_kids')?.value;
  const rt      = document.getElementById('stay_room_type')?.value;
  const ml        = document.getElementById('stay_meal')?.value;
  const extraBed  = parseFloat(document.getElementById('stay_extra_bed_amt')?.value || 0);
  const special   = document.getElementById('stay_special')?.value;

  /* Hotel name and room type */
  const isCustom   = document.getElementById('hotel-card-custom')?.classList.contains('selected');
  const hotelName  = isCustom ? selectedHotelName : selectedHotelName;
  const effectiveRt = isCustom && selectedCustomRoomType ? selectedCustomRoomType : rt;

  /* Sync service hidden fields */
  const svcDate = document.getElementById('stay-svc-date');
  if (svcDate && ci) svcDate.value = ci;

  /* Build short_plan text (shown on voucher) */
  let lines = [];
  if (hotelName) lines.push('Property: ' + hotelName);
  if (ci && co)  lines.push('Check-in: ' + formatDate(ci) + (ciTime ? ' ' + fmtTime(ciTime) : '') + '  →  Check-out: ' + formatDate(co) + (coTime ? ' ' + fmtTime(coTime) : ''));
  if (nights)    lines.push('Duration: ' + nights + ' night(s)');
  if (rooms)     lines.push('Rooms: ' + rooms + (effectiveRt ? ' (' + effectiveRt + ')' : ''));
  if (isCustom && selectedExtraBed) {
    const xbLine = 'Extra Bed/Mattress: Yes' + (selectedExtraBedPrice > 0 ? ' (₹' + selectedExtraBedPrice.toLocaleString('en-IN') + '/night)' : '');
    lines.push(xbLine);
  }
  const guestParts = [];
  if (parseInt(adults) > 0) guestParts.push(adults + ' Adult' + (parseInt(adults) > 1 ? 's' : ''));
  if (parseInt(kids)   > 0) guestParts.push(kids   + ' Child' + (parseInt(kids)   > 1 ? 'ren' : ''));
  if (guestParts.length)    lines.push('Guests: ' + guestParts.join(', '));
  if (ml)           lines.push('Meal Plan: ' + ml);
  if (extraBed > 0) lines.push('Extra Bed: ₹' + extraBed.toLocaleString('en-IN'));
  if (special)      lines.push('Requests: ' + special);

  const plan = lines.join(' | ');
  const planEl = document.getElementById('stay-short-plan');
  if (planEl) planEl.value = plan || 'Stay booking';

  /* Live preview */
  const previewWrap = document.getElementById('stay-plan-preview');
  const previewText = document.getElementById('stay-plan-text');
  if (previewWrap && previewText && lines.length > 0) {
    previewWrap.style.display = 'block';
    previewText.innerHTML = lines.map(l => {
      const [k, v] = l.split(': ');
      return `<span style="color:#065F46;font-weight:700;">${k}:</span> ${v || ''}`;
    }).join('<br>');
  } else if (previewWrap) {
    previewWrap.style.display = 'none';
  }
}

function calcNights() {
  const ci = new Date(document.getElementById('stay_checkin').value);
  const co = new Date(document.getElementById('stay_checkout').value);
  if (!isNaN(ci) && !isNaN(co) && co > ci) {
    const n = Math.round((co - ci) / 86400000);
    document.getElementById('stay_nights').value = n;
  } else {
    document.getElementById('stay_nights').value = '';
  }
  recalcStayTotal();
}

function buildCabPlan() {
  const d  = document.getElementById('cab_date').value;
  const t  = document.getElementById('cab_time').value;
  const ty = document.getElementById('cab_type').value;
  const pu = document.getElementById('cab_pickup').value;
  const dr = document.getElementById('cab_drop').value;
  const p  = document.getElementById('cab_pax').value;
  const v  = document.getElementById('cab_vehicle').value;
  const svcDate = document.getElementById('cab-svc-date');
  if (svcDate && d) svcDate.value = d;
  let plan = ty || 'Cab transfer';
  if (d)  plan += ' on ' + formatDate(d);
  if (t)  plan += ' at ' + t;
  if (pu) plan += ' from ' + pu;
  if (dr) plan += ' to ' + dr;
  if (p)  plan += ', ' + p + ' pax';
  if (v)  plan += ' [' + v + ']';
  document.getElementById('cab-short-plan').value = plan;
}

/* ── TOUR PACKAGE FUNCTIONS ── */

function tpToggle(svc) {
  const on   = document.getElementById('tp-' + svc + '-on').checked;
  const body = document.getElementById('tp-' + svc + '-body');
  if (body) body.classList.toggle('disabled', !on);
  calcTourMargin();
}

function tpCalcHotel() {
  const from = document.getElementById('tp_hotel_from').value;
  const to   = document.getElementById('tp_hotel_to').value;
  if (from && to) {
    const d = Math.max(0, Math.round((new Date(to) - new Date(from)) / 86400000));
    document.getElementById('tp_hotel_nights').value = d || 1;
  }
}

function calcTourMargin() {
  const on = id => document.getElementById('tp-' + id + '-on')?.checked !== false;
  const val = id => parseFloat(document.getElementById(id)?.value || 0);
  const fmt = v => '₹' + Math.max(0, v).toLocaleString('en-IN');

  const hotel = on('hotel') ? val('tp_hotel_cost')  : 0;
  const cab   = on('cab')   ? val('tp_cab_cost')    : 0;
  const boat  = on('boat')  ? val('tp_boat_cost')   : 0;
  const guide = on('guide') ? val('tp_guide_cost')  : 0;
  const totalExp = hotel + cab + boat + guide;

  /* update individual badges */
  document.getElementById('tp-hotel-cost-badge').textContent = fmt(hotel);
  document.getElementById('tp-cab-cost-badge').textContent   = fmt(cab);
  document.getElementById('tp-boat-cost-badge').textContent  = fmt(boat);
  document.getElementById('tp-guide-cost-badge').textContent = fmt(guide);

  /* update expense bar */
  document.getElementById('tp-hotel-exp-val').textContent = fmt(hotel);
  document.getElementById('tp-cab-exp-val').textContent   = fmt(cab);
  document.getElementById('tp-boat-exp-val').textContent  = fmt(boat);
  document.getElementById('tp-guide-exp-val').textContent = fmt(guide);
  document.getElementById('tp-total-exp-val').textContent = fmt(totalExp);

  /* profit */
  const booking  = val('tour-booking-amount');
  const discount = val('tour-discount');
  const net      = booking - discount;
  const profit   = net - totalExp;
  const pct      = net > 0 ? Math.round((profit / net) * 100) : 0;

  document.getElementById('tp-summary-booking').textContent = fmt(net);
  document.getElementById('tp-summary-expense').textContent = fmt(totalExp);
  const profEl = document.getElementById('tp-summary-profit');
  const pctEl  = document.getElementById('tp-summary-pct');
  if (profEl) { profEl.textContent = fmt(profit); profEl.style.color = profit >= 0 ? '#065F46' : '#EF4444'; }
  if (pctEl)  {
    pctEl.textContent = Math.max(0, pct) + '%';
    pctEl.style.background = pct >= 0 ? '#D1FAE5' : '#FEE2E2';
    pctEl.style.color      = pct >= 0 ? '#065F46' : '#EF4444';
  }

  /* save vendor_cost */
  document.getElementById('tp-vendor-cost-hidden').value = totalExp;
}

function calcTourBalance() {
  const total   = parseFloat(document.getElementById('tour-booking-amount')?.value || 0);
  const discount= parseFloat(document.getElementById('tour-discount')?.value || 0);
  const advance = parseFloat(document.getElementById('tour-advance')?.value || 0);
  const balance = Math.max(0, total - discount - advance);
  const fmt = v => '₹' + v.toLocaleString('en-IN');
  const balEl = document.getElementById('tour-balance');
  if (balEl) balEl.textContent = fmt(balance);
  updateTourPayBadge();
}

function updateTourPayBadge() {
  const s = document.getElementById('tour-pay-status')?.value;
  const b = document.getElementById('tour-pay-badge');
  if (!b) return;
  b.textContent = s === 'paid' ? 'PAID' : 'DUE';
  b.className   = 'nb-balance-badge ' + (s || 'due');
}

function buildTourPlan() {
  const n = document.getElementById('tour_name')?.value;
  const s = document.getElementById('tour_start')?.value;
  const a = document.getElementById('tour_adults')?.value;
  const c = document.getElementById('tour_children')?.value;
  if (document.getElementById('tour-svc-date') && s) document.getElementById('tour-svc-date').value = s;
  let parts = [];
  if (n) parts.push(n);
  if (s) parts.push('From: ' + formatDate(s));
  if (parseInt(a) > 0) parts.push('Adults: ' + a);
  if (parseInt(c) > 0) parts.push('Children: ' + c);
  /* add service summaries */
  if (document.getElementById('tp-hotel-on')?.checked && document.getElementById('tp_hotel_name')?.value)
    parts.push('Hotel: ' + document.getElementById('tp_hotel_name').value);
  if (document.getElementById('tp-boat-on')?.checked)
    parts.push('Boat: ' + (document.getElementById('tp_boat_type')?.value || ''));
  if (document.getElementById('tp-cab-on')?.checked)
    parts.push('Cab: ' + (document.getElementById('tp_cab_type')?.value || ''));
  if (document.getElementById('tp-guide-on')?.checked && document.getElementById('tp_guide_name')?.value)
    parts.push('Guide: ' + document.getElementById('tp_guide_name').value);
  const plan = parts.join(' | ') || 'Tour Package';
  const el = document.getElementById('tour-short-plan');
  if (el) el.value = plan;
}

/* Legacy stubs */
let tourSvcIdx = 0;
function addTourService() {}
function removeTourSvc() {}
function calcTourTotal() {}

function formatDate(d) {
  if (!d) return '';
  const dt = new Date(d + 'T00:00:00');
  return dt.toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'});
}
function fmtTime(t) {
  if (!t) return '';
  const [h, m] = t.split(':').map(Number);
  const ampm = h >= 12 ? 'PM' : 'AM';
  const h12  = h % 12 || 12;
  return h12 + ':' + String(m).padStart(2,'0') + ' ' + ampm;
}

function numberToWords(num) {
  if (!num) return 'Zero Rupees Only';
  const ones=['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine'];
  const teens=['Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
  const tens=['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
  function lt1000(n){
    if(!n)return'';if(n<10)return ones[n];if(n<20)return teens[n-10];
    if(n<100)return tens[Math.floor(n/10)]+(n%10?' '+ones[n%10]:'');
    return ones[Math.floor(n/100)]+' Hundred'+(n%100?' '+lt1000(n%100):'');
  }
  let r='';
  const cr=Math.floor(num/10000000),lk=Math.floor((num%10000000)/100000),
        th=Math.floor((num%100000)/1000),rm=num%1000;
  if(cr)r+=lt1000(cr)+' Crore ';if(lk)r+=lt1000(lk)+' Lakh ';
  if(th)r+=lt1000(th)+' Thousand ';if(rm)r+=lt1000(rm);
  return r.trim()+' Rupees Only';
}

document.addEventListener('DOMContentLoaded', function(){
  feather.replace();
  addTourService();
});
</script>

@endsection
