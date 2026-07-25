@extends('admin.layouts.app')
@section('content')

<style>
:root {
  --hub-bg: #EEF2FF;
  --hub-card: #fff;
  --hub-border: #E2E8F0;
  --hub-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 20px rgba(0,0,0,.07);
  --hub-text: #0F172A;
  --hub-muted: #64748B;
}
.hub-page {
  background: var(--hub-bg);
  min-height: 100vh;
  padding: 28px 24px;
}

/* ── Header ────────────────────────────────── */
.hub-header {
  background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #1E40AF 100%);
  border-radius: 20px;
  padding: 32px 36px;
  margin-bottom: 32px;
  margin-top: 50px;
  position: relative;
  overflow: hidden;
  text-align: center;
}
.hub-header::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 240px; height: 240px;
  border-radius: 50%;
  background: rgba(255,255,255,.05);
}
.hub-header::after {
  content: '';
  position: absolute;
  bottom: -80px; left: -30px;
  width: 200px; height: 200px;
  border-radius: 50%;
  background: rgba(79,70,229,.12);
}
.hub-header-inner { position: relative; z-index: 1; }
.hub-header h1 { color: #fff; font-size: 1.8rem; font-weight: 800; margin: 0 0 8px; letter-spacing: -.02em; }
.hub-header p { color: rgba(255,255,255,.65); font-size: .92rem; margin: 0; }
.hub-header-back {
  position: absolute;
  top: 20px; right: 20px;
  z-index: 1;
  background: rgba(255,255,255,.12);
  border: 1.5px solid rgba(255,255,255,.2);
  color: rgba(255,255,255,.8);
  border-radius: 10px;
  padding: 8px 16px;
  font-size: .78rem;
  font-weight: 600;
  text-decoration: none;
  transition: .18s;
}
.hub-header-back:hover { background: rgba(255,255,255,.22); color: #fff; }

/* ── Grid ──────────────────────────────────── */
.hub-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  max-width: 900px;
  margin: 0 auto 32px;
}

/* ── Booking type card ─────────────────────── */
.btype-card {
  background: linear-gradient(165deg, var(--tc-bg) 0%, #fff 58%);
  border: 2px solid var(--tc-bg);
  border-radius: 20px;
  padding: 32px 28px;
  text-decoration: none;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0;
  transition: all .25s ease;
  position: relative;
  overflow: hidden;
  cursor: pointer;
}
.btype-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 5px;
  background: var(--tc-gradient);
  opacity: .7;
  transition: opacity .25s, height .25s;
}
.btype-card:hover { transform: translateY(-5px); box-shadow: 0 16px 48px rgba(0,0,0,.14); border-color: var(--tc-color); }
.btype-card:hover::before { opacity: 1; height: 6px; }

.btype-icon-wrap {
  width: 72px; height: 72px;
  border-radius: 20px;
  background: var(--tc-gradient);
  box-shadow: 0 8px 20px -6px var(--tc-color);
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem;
  margin-bottom: 18px;
  transition: transform .25s;
}
.btype-card:hover .btype-icon-wrap { transform: scale(1.08) rotate(-3deg); }

.btype-label {
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--hub-text);
  margin-bottom: 6px;
  letter-spacing: -.01em;
}
.btype-desc {
  font-size: .83rem;
  color: var(--hub-muted);
  line-height: 1.55;
  margin-bottom: 20px;
  flex: 1;
}
.btype-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 20px;
}
.btype-tag {
  background: var(--tc-bg);
  color: var(--tc-color);
  border-radius: 20px;
  padding: 3px 10px;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .02em;
}
.btype-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--tc-gradient);
  color: #fff;
  border: none;
  border-radius: 12px;
  padding: 12px 22px;
  font-size: .85rem;
  font-weight: 700;
  transition: opacity .18s, transform .18s;
  text-decoration: none;
  width: 100%;
  justify-content: center;
}
.btype-btn:hover { opacity: .88; transform: translateY(-1px); color: #fff; }
.btype-btn svg { width: 16px; height: 16px; stroke: #fff; }

/* ── Quick links ───────────────────────────── */
.hub-quick {
  max-width: 900px;
  margin: 0 auto;
  background: var(--hub-card);
  border-radius: 16px;
  border: 1px solid var(--hub-border);
  box-shadow: var(--hub-shadow);
  padding: 20px 24px;
}
.hub-quick-title {
  font-size: .8rem;
  font-weight: 700;
  color: var(--hub-muted);
  text-transform: uppercase;
  letter-spacing: .06em;
  margin-bottom: 14px;
}
.hub-quick-links {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.hub-quick-link {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: var(--qc-bg, #F8FAFC);
  border: 1.5px solid var(--qc-border, var(--hub-border));
  border-radius: 10px;
  padding: 9px 16px;
  font-size: .8rem;
  font-weight: 600;
  color: var(--qc-color, var(--hub-text));
  text-decoration: none;
  transition: .18s;
}
.hub-quick-link:hover { filter: brightness(.97); transform: translateY(-1px); }

/* Recent count badges */
.btype-count {
  position: absolute;
  top: 18px; right: 18px;
  background: var(--tc-bg);
  color: var(--tc-color);
  border-radius: 20px;
  padding: 4px 12px;
  font-size: .72rem;
  font-weight: 800;
}

/* ══════════════════════════════════════
   MOBILE RESPONSIVE — App View ≤ 767px
══════════════════════════════════════ */
@media (max-width: 767px) {

  .hub-page {
    padding: 12px 12px 90px;
    background: #F1F5F9;
  }

  /* Header — compact on mobile */
  .hub-header {
    border-radius: 16px;
    padding: 20px 16px 20px;
    margin-bottom: 16px;
    margin-top: 60px;
    text-align: left;
  }
  .hub-header h1 {
    font-size: 1.3rem;
    margin-bottom: 4px;
    padding-right: 80px; /* avoid overlap with back btn */
  }
  .hub-header p {
    font-size: .78rem;
    padding-right: 10px;
  }
  .hub-header-back {
    top: 14px; right: 14px;
    padding: 6px 12px;
    font-size: .72rem;
    border-radius: 8px;
  }

  /* Grid — single column, readable rows on mobile */
  .hub-grid {
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 16px;
  }

  /* Cards — icon + title/desc side by side, tags & CTA span full width below */
  .btype-card {
    display: grid;
    grid-template-columns: 54px 1fr;
    column-gap: 14px;
    row-gap: 6px;
    align-items: start;
    border-radius: 16px;
    padding: 16px 16px 14px;
    border-width: 1.5px;
  }
  .btype-card:hover { transform: none; }
  .btype-card:active { transform: scale(.98); opacity: .93; }

  /* Corner badge → small inline chip next to the icon instead of hidden */
  .btype-count {
    position: static;
    grid-column: 2;
    grid-row: 1;
    justify-self: start;
    margin-bottom: 2px;
    font-size: .64rem;
    padding: 3px 9px;
  }

  /* Icon — spans the title+desc rows on the left */
  .btype-icon-wrap {
    grid-column: 1;
    grid-row: 1 / 4;
    width: 54px; height: 54px;
    border-radius: 14px;
    font-size: 1.4rem;
    margin-bottom: 0;
    align-self: start;
  }

  /* Label */
  .btype-label {
    grid-column: 2;
    grid-row: 2;
    font-size: 1rem;
    margin-bottom: 0;
    line-height: 1.25;
  }

  /* Description — kept visible on mobile for clarity */
  .btype-desc {
    display: block;
    grid-column: 2;
    grid-row: 3;
    font-size: .76rem;
    line-height: 1.45;
    margin-bottom: 0;
  }

  /* Tags — full width row beneath icon+text, all tags visible */
  .btype-tags {
    grid-column: 1 / -1;
    grid-row: 4;
    gap: 5px;
    margin-bottom: 10px;
    margin-top: 2px;
  }
  .btype-tag {
    font-size: .64rem;
    padding: 2px 8px;
  }

  /* CTA button — full width row at the bottom */
  .btype-btn {
    grid-column: 1 / -1;
    grid-row: 5;
    padding: 10px 10px;
    font-size: .78rem;
    border-radius: 10px;
    gap: 6px;
  }
  .btype-btn svg { width: 14px; height: 14px; }

  /* Quick links — horizontal scroll */
  .hub-quick {
    border-radius: 14px;
    padding: 14px 14px;
  }
  .hub-quick-title { margin-bottom: 10px; }
  .hub-quick-links {
    gap: 8px;
    flex-wrap: nowrap;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 4px;
    scrollbar-width: none;
  }
  .hub-quick-links::-webkit-scrollbar { display: none; }
  .hub-quick-link {
    padding: 8px 12px;
    font-size: .75rem;
    border-radius: 9px;
    white-space: nowrap;
    flex-shrink: 0;
  }
}
</style>

<div class="hub-page">

  {{-- Header --}}
  <div class="hub-header">
    <div class="hub-header-inner">
      <h1>✈️ New Booking</h1>
      <p>Choose the booking type to get started — each has its own dedicated form</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="hub-header-back">← Dashboard</a>
  </div>

  {{-- Booking type cards --}}
  <div class="hub-grid">

    {{-- STAY / HOTEL --}}
    <a href="{{ route('bookings.create-stay') }}"
       class="btype-card"
       style="--tc-color:#059669;--tc-bg:#D1FAE5;--tc-gradient:linear-gradient(135deg,#059669,#047857);">
      <div class="btype-count" style="background:#D1FAE5;color:#065F46;">🏨 Hotel / Stay</div>
      <div class="btype-icon-wrap">🏨</div>
      <div class="btype-label">Stay / Hotel Booking</div>
      <div class="btype-desc">
        Hotels, homestays, guesthouses & heritage accommodations.
        Check-in / check-out, room category, meal plan, special requests.
      </div>
      <div class="btype-tags">
        <span class="btype-tag">Check-in/out</span>
        <span class="btype-tag">Room Category</span>
        <span class="btype-tag">Meal Plan</span>
        <span class="btype-tag">PAX</span>
      </div>
    </a>

    {{-- CAB --}}
    <a href="{{ route('cab-bookings.create') }}"
       class="btype-card"
       style="--tc-color:#D97706;--tc-bg:#FEF3C7;--tc-gradient:linear-gradient(135deg,#F59E0B,#D97706);">
      <div class="btype-count" style="background:#FEF3C7;color:#92400E;">🚗 Cab / Transport</div>
      <div class="btype-icon-wrap">🚗</div>
      <div class="btype-label">Cab / Transport Booking</div>
      <div class="btype-desc">
        Airport transfers, city rides, outstation trips & multi-day tours.
        Vehicle selection, route, driver details & charge breakdown.
      </div>
      <div class="btype-tags">
        <span class="btype-tag">Pickup / Drop</span>
        <span class="btype-tag">Vehicle Type</span>
        <span class="btype-tag">Trip Type</span>
        <span class="btype-tag">Tolls & Extras</span>
      </div>
    </a>

    {{-- BOAT --}}
    <a href="{{ route('boat-booking.create') }}"
       class="btype-card"
       style="--tc-color:#0369A1;--tc-bg:#E0F2FE;--tc-gradient:linear-gradient(135deg,#0EA5E9,#0284C7);">
      <div class="btype-count" style="background:#E0F2FE;color:#0369A1;">⛵ Boat / Ghat</div>
      <div class="btype-icon-wrap">⛵</div>
      <div class="btype-label">Boat / Ghat Booking</div>
      <div class="btype-desc">
        Ganga Aarti rides, sunrise cruises, birthday & anniversary celebrations,
        private events, photography & decoration packages.
      </div>
      <div class="btype-tags">
        <span class="btype-tag">Boat Type</span>
        <span class="btype-tag">Event Type</span>
        <span class="btype-tag">Ghat</span>
        <span class="btype-tag">Decoration</span>
      </div>
    </a>

    {{-- TOUR PACKAGE --}}
    <a href="{{ route('tour-booking.create') }}"
       class="btype-card"
       style="--tc-color:#6D28D9;--tc-bg:#EDE9FE;--tc-gradient:linear-gradient(135deg,#7C3AED,#6D28D9);">
      <div class="btype-count" style="background:#EDE9FE;color:#6D28D9;">🗺️ Tour Package</div>
      <div class="btype-icon-wrap">🗺️</div>
      <div class="btype-label">Tour Package Booking</div>
      <div class="btype-desc">
        Complete tour packages with hotel, cab, boat & guide included.
        Kashi darshan, custom itineraries, multi-day packages.
      </div>
      <div class="btype-tags">
        <span class="btype-tag">Itinerary</span>
        <span class="btype-tag">Hotel + Cab</span>
        <span class="btype-tag">Guide</span>
        <span class="btype-tag">Multi-day</span>
      </div>
    </a>

  </div>{{-- /hub-grid --}}

  {{-- Quick links --}}
  <div class="hub-quick">
    <div class="hub-quick-title">Quick Actions</div>
    <div class="hub-quick-links">
      <a href="{{ route('bookings.index') }}"      class="hub-quick-link" style="--qc-bg:#EEF2FF;--qc-border:#C7D2FE;--qc-color:#4338CA;">📋 All Bookings</a>
      <a href="{{ route('cab-bookings.index') }}"  class="hub-quick-link" style="--qc-bg:#FEF3C7;--qc-border:#FDE68A;--qc-color:#92400E;">🚗 Cab Bookings</a>
      <a href="{{ route('boat-booking.index') }}"  class="hub-quick-link" style="--qc-bg:#E0F2FE;--qc-border:#BAE6FD;--qc-color:#0369A1;">⛵ Boat Bookings</a>
      <a href="{{ route('bookings.calendar') }}"   class="hub-quick-link" style="--qc-bg:#FCE7F3;--qc-border:#FBCFE8;--qc-color:#BE185D;">📅 Calendar View</a>
      <a href="{{ route('customers.index') }}"     class="hub-quick-link" style="--qc-bg:#D1FAE5;--qc-border:#A7F3D0;--qc-color:#065F46;">👥 Customer CRM</a>
      <a href="{{ route('reports.index') }}"       class="hub-quick-link" style="--qc-bg:#EDE9FE;--qc-border:#DDD6FE;--qc-color:#6D28D9;">📊 Reports</a>
    </div>
  </div>

</div>

@endsection
