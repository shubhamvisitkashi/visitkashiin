@extends('admin.layouts.app')
@section('content')

<style>
:root {
  --cb-bg:#F0F4FF;--cb-card:#fff;--cb-border:#E2E8F0;
  --cb-indigo:#4F46E5;--cb-blue:#3B82F6;--cb-emerald:#10B981;
  --cb-amber:#F59E0B;--cb-rose:#EF4444;--cb-sky:#0EA5E9;
  --cb-violet:#7C3AED;--cb-slate:#475569;
}
body{background:var(--cb-bg);}
.cb-wrap{padding:20px 24px;}

/* Stat Cards */
.cb-stat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;margin-bottom:24px;}
.cb-stat{background:#fff;border-radius:14px;padding:16px 18px;border:1px solid var(--cb-border);box-shadow:0 1px 3px rgba(0,0,0,.05);position:relative;overflow:hidden;}
.cb-stat-accent{position:absolute;top:0;left:0;width:4px;height:100%;border-radius:14px 0 0 14px;}
.cb-stat-lbl{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:6px;}
.cb-stat-val{font-size:1.6rem;font-weight:800;color:#0F172A;line-height:1;}
.cb-stat-sub{font-size:.72rem;color:#64748B;margin-top:4px;}

/* Filter Bar */
/* ── Filter Bar — single linear row ── */
.cb-filter-bar {
    display: flex;
    align-items: center;
    gap: 0;
    background: #fff;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 18px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
    flex-wrap: nowrap;
}
.cb-filter-bar input,
.cb-filter-bar select {
    border: none;
    border-right: 1.5px solid #E2E8F0;
    padding: 0 14px;
    font-size: .82rem;
    color: #374151;
    background: #fff;
    height: 44px;
    outline: none;
    flex: 1;
    min-width: 0;
    font-family: inherit;
    transition: background .15s;
}
.cb-filter-bar input { min-width: 180px; flex: 2; }
.cb-filter-bar select { min-width: 120px; cursor: pointer; }
.cb-filter-bar input:focus,
.cb-filter-bar select:focus {
    background: #F5F3FF;
    color: #4F46E5;
}
.cb-filter-bar input::placeholder { color: #9CA3AF; }
.cb-filter-bar-divider { width: 1.5px; height: 44px; background: #E2E8F0; flex-shrink: 0; }
.cb-filter-actions { display: flex; align-items: center; gap: 0; flex-shrink: 0; }
.cb-filter-btn {
    height: 44px;
    padding: 0 18px;
    border: none;
    border-radius: 0;
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: background .15s, opacity .15s;
    text-decoration: none;
}
.cb-filter-btn.primary {
    background: #4F46E5;
    color: #fff;
    border-left: 1.5px solid #4338CA;
}
.cb-filter-btn.primary:hover { background: #4338CA; }
.cb-filter-btn.ghost {
    background: #F8FAFC;
    color: #6B7280;
    border-left: 1.5px solid #E2E8F0;
}
.cb-filter-btn.ghost:hover { background: #F1F5F9; color: #374151; }

@media(max-width:900px){
    .cb-filter-bar { flex-wrap: wrap; border-radius: 12px; gap: 0; }
    .cb-filter-bar input,
    .cb-filter-bar select { border-right: none; border-bottom: 1.5px solid #E2E8F0; height: 40px; flex: 1 1 150px; }
    .cb-filter-actions { width: 100%; border-top: 1.5px solid #E2E8F0; }
    .cb-filter-btn { flex: 1; justify-content: center; height: 40px; }
    .cb-filter-btn.primary { border-left: none; border-right: 1.5px solid #4338CA; }
    .cb-filter-btn.ghost { border-left: none; }
}

/* Table */
.cb-table-card{background:#fff;border-radius:14px;border:1px solid var(--cb-border);box-shadow:0 1px 3px rgba(0,0,0,.05);overflow:hidden;}
.cb-table-head{padding:14px 20px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;}
.cb-table-title{font-size:.9rem;font-weight:700;color:#0F172A;}
.cb-tbl{width:100%;border-collapse:collapse;}
.cb-tbl thead th{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94A3B8;padding:10px 16px;background:#FAFBFF;border-bottom:1px solid #F1F5F9;white-space:nowrap;}
.cb-tbl tbody tr{border-bottom:1px solid #F8FAFC;transition:background .15s;}
.cb-tbl tbody tr:hover{background:#F8FAFF;}
.cb-tbl tbody td{padding:12px 16px;font-size:.82rem;color:#374151;vertical-align:middle;}
.cb-tbl tbody tr:last-child{border-bottom:none;}

/* Badges */
.cb-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;}
.cb-badge.pending  {background:#FEF3C7;color:#92400E;}
.cb-badge.confirmed{background:#DBEAFE;color:#1E40AF;}
.cb-badge.assigned {background:#E0F2FE;color:#075985;}
.cb-badge.completed{background:#D1FAE5;color:#065F46;}
.cb-badge.cancelled{background:#FEE2E2;color:#991B1B;}
.cb-badge.unpaid   {background:#FEE2E2;color:#991B1B;}
.cb-badge.partial  {background:#FEF3C7;color:#92400E;}
.cb-badge.paid     {background:#D1FAE5;color:#065F46;}

/* Trip type pill */
.cb-trip{font-size:.7rem;font-weight:600;color:#7C3AED;background:#EDE9FE;padding:2px 8px;border-radius:10px;white-space:nowrap;}

/* Actions */
.cb-acts{display:grid;grid-template-columns:1fr 1fr;gap:5px;width:70px;}
.cb-act{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;text-decoration:none;border:none;}
.cb-act svg{width:14px;height:14px;stroke:currentColor;}
.cb-act.view   {background:#DBEAFE;color:#1D4ED8;}.cb-act.view:hover   {background:#BFDBFE;}
.cb-act.edit   {background:#EDE9FE;color:#6D28D9;}.cb-act.edit:hover   {background:#DDD6FE;}
.cb-act.invoice{background:#D1FAE5;color:#065F46;}.cb-act.invoice:hover{background:#A7F3D0;}
.cb-act.danger {background:#FEE2E2;color:#DC2626;}.cb-act.danger:hover {background:#FECACA;}

/* Page header */
.cs-header{background:linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%);border-radius:16px;padding:20px 26px;margin-bottom:20px;margin-top:50px;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;box-shadow:0 8px 24px rgba(79,70,229,.28);}
.cs-header::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;pointer-events:none;}
.cs-header::after{content:'🚗';position:absolute;bottom:-10px;right:18px;font-size:90px;opacity:.07;line-height:1;pointer-events:none;}
.cs-header-title{color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 2px;}
.cs-header-sub{color:rgba(255,255,255,.7);font-size:.76rem;margin:0;}
/* New Booking button */
.cb-new-btn{display:inline-flex;align-items:center;gap:7px;background:#fff;color:#4F46E5;border:none;border-radius:10px;padding:9px 18px;font-size:.84rem;font-weight:700;cursor:pointer;text-decoration:none;transition:all .2s;position:relative;z-index:1;box-shadow:0 2px 8px rgba(0,0,0,.12);}
.cb-new-btn:hover{background:#EEF2FF;color:#4338CA;text-decoration:none;transform:translateY(-1px);}

/* Route cell */
.cb-route{display:flex;flex-direction:column;gap:2px;}
.cb-route-from{font-size:.8rem;font-weight:600;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;}
.cb-route-to{font-size:.72rem;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;}
.cb-route-arrow{font-size:.65rem;color:#94A3B8;}

/* Customer cell */
.cb-cust-name{font-weight:700;color:#0F172A;}
.cb-cust-phone{font-size:.75rem;color:#64748B;}

/* Empty state */
.cb-empty{text-align:center;padding:60px 20px;color:#94A3B8;}
.cb-empty svg{width:48px;height:48px;stroke:#CBD5E1;margin-bottom:12px;}
.cb-empty p{font-size:.88rem;}

@media(max-width:768px){
  .cb-wrap{padding:12px;}
  .cb-stat-grid{grid-template-columns:repeat(2,1fr);}
  .cb-tbl thead{display:none;}
  .cb-tbl tbody td{display:block;padding:6px 14px;}
  .cb-tbl tbody td::before{content:attr(data-label);font-size:.65rem;color:#94A3B8;display:block;font-weight:700;text-transform:uppercase;}
}
</style>

<div class="cb-wrap">

  {{-- Flash Messages --}}
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:10px;">
    <i data-feather="check-circle" style="width:16px;height:16px;margin-right:6px;"></i>
    {{ session('success') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:10px;">
    {{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Page Header --}}
  <div class="cs-header">
    <div style="position:relative;z-index:1;">
      <h4 class="cs-header-title">Cab Bookings</h4>
      <p class="cs-header-sub">Manage all cab &amp; vehicle bookings</p>
    </div>
    <a href="{{ route('cab-bookings.create') }}" class="cb-new-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New Cab Booking
    </a>
  </div>

  {{-- Stats Cards --}}
  <div class="cb-stat-grid">
    <div class="cb-stat">
      <div class="cb-stat-accent" style="background:#4F46E5;"></div>
      <div class="cb-stat-lbl">Total</div>
      <div class="cb-stat-val">{{ number_format($stats['total']) }}</div>
      <div class="cb-stat-sub">All bookings</div>
    </div>
    <div class="cb-stat">
      <div class="cb-stat-accent" style="background:#0EA5E9;"></div>
      <div class="cb-stat-lbl">Today's Trips</div>
      <div class="cb-stat-val">{{ $stats['today'] }}</div>
      <div class="cb-stat-sub">Pickup today</div>
    </div>
    <div class="cb-stat">
      <div class="cb-stat-accent" style="background:#F59E0B;"></div>
      <div class="cb-stat-lbl">Pending</div>
      <div class="cb-stat-val">{{ $stats['pending'] }}</div>
      <div class="cb-stat-sub">Needs action</div>
    </div>
    <div class="cb-stat">
      <div class="cb-stat-accent" style="background:#3B82F6;"></div>
      <div class="cb-stat-lbl">Confirmed</div>
      <div class="cb-stat-val">{{ $stats['confirmed'] }}</div>
      <div class="cb-stat-sub">Ready to go</div>
    </div>
    <div class="cb-stat">
      <div class="cb-stat-accent" style="background:#10B981;"></div>
      <div class="cb-stat-lbl">Completed</div>
      <div class="cb-stat-val">{{ $stats['completed'] }}</div>
      <div class="cb-stat-sub">Trips done</div>
    </div>
    <div class="cb-stat">
      <div class="cb-stat-accent" style="background:#7C3AED;"></div>
      <div class="cb-stat-lbl">Revenue</div>
      <div class="cb-stat-val" style="font-size:1.15rem;">₹{{ number_format($stats['revenue'] / 1000, 1) }}K</div>
      <div class="cb-stat-sub">Total billed</div>
    </div>
    <div class="cb-stat">
      <div class="cb-stat-accent" style="background:#EF4444;"></div>
      <div class="cb-stat-lbl">Due Amount</div>
      <div class="cb-stat-val" style="font-size:1.1rem;color:#EF4444;">₹{{ number_format($stats['pending_payment'] / 1000, 1) }}K</div>
      <div class="cb-stat-sub">Pending payment</div>
    </div>
  </div>

  {{-- Filter Bar --}}
  <form method="GET" action="{{ route('cab-bookings.index') }}" class="cb-filter-bar">
    <input type="text" name="search" placeholder="🔍  Search by name, phone, booking ID…"
           value="{{ request('search') }}" style="flex:1;min-width:200px;">
    <select name="status">
      <option value="">All Status</option>
      @foreach(['pending'=>'Pending','confirmed'=>'Confirmed','assigned'=>'Assigned','completed'=>'Completed','cancelled'=>'Cancelled'] as $v=>$l)
        <option value="{{ $v }}" {{ request('status') == $v ? 'selected' : '' }}>{{ $l }}</option>
      @endforeach
    </select>
    <select name="payment_status">
      <option value="">Payment Status</option>
      <option value="unpaid"  {{ request('payment_status')=='unpaid'  ? 'selected' : '' }}>Unpaid</option>
      <option value="partial" {{ request('payment_status')=='partial' ? 'selected' : '' }}>Partial</option>
      <option value="paid"    {{ request('payment_status')=='paid'    ? 'selected' : '' }}>Paid</option>
    </select>
    <select name="trip_type">
      <option value="">Trip Type</option>
      @foreach(['one_way'=>'One Way','round_trip'=>'Round Trip','multi_city'=>'Multi City','airport_transfer'=>'Airport Transfer','local_rental'=>'Local Rental'] as $v=>$l)
        <option value="{{ $v }}" {{ request('trip_type') == $v ? 'selected' : '' }}>{{ $l }}</option>
      @endforeach
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" title="Pickup From">
    <input type="date" name="date_to"   value="{{ request('date_to') }}"   title="Pickup To">
    @if(auth('admin')->user()->hasAnyRole(['Super Admin','Admin','Manager']))
    <select name="staff_id">
      <option value="">All Staff</option>
      @foreach($staffList as $s)
        <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
      @endforeach
    </select>
    @endif
    <div class="cb-filter-actions">
      <button type="submit" class="cb-filter-btn primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Filter
      </button>
      @if(request()->hasAny(['search','status','payment_status','trip_type','date_from','date_to','staff_id']))
      <a href="{{ route('cab-bookings.index') }}" class="cb-filter-btn ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        Clear
      </a>
      @endif
    </div>
  </form>

  {{-- Bookings Table --}}
  <div class="cb-table-card">
    <div class="cb-table-head">
      <div class="cb-table-title">
        Bookings
        <span style="font-size:.72rem;font-weight:400;color:#94A3B8;margin-left:6px;">{{ $query->total() }} records</span>
      </div>
    </div>

    @if($query->isEmpty())
    <div class="cb-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 17H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v10c0 1.1-.9 2-2 2z"/><path d="M12 17v4M8 21h8"/></svg>
      <p>No cab bookings found. <a href="{{ route('cab-bookings.create') }}" style="color:#4F46E5;font-weight:600;">Create first booking →</a></p>
    </div>
    @else
    <div style="overflow-x:auto;">
    <table class="cb-tbl">
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Customer</th>
          <th>Route</th>
          <th>Vehicle</th>
          <th>Pickup Date</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Staff</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($query as $b)
        <tr>
          <td data-label="Booking ID">
            <a href="{{ route('cab-bookings.show', $b->id) }}" style="font-weight:700;color:#4F46E5;text-decoration:none;font-size:.8rem;">
              {{ $b->booking_number }}
            </a>
            <div style="font-size:.68rem;color:#94A3B8;">{{ $b->created_at->format('d M Y') }}</div>
          </td>
          <td data-label="Customer">
            <div class="cb-cust-name">{{ $b->customer_name }}</div>
            <div class="cb-cust-phone">📞 {{ $b->customer_phone }}</div>
          </td>
          <td data-label="Route">
            <div class="cb-route">
              <div class="cb-route-from">📍 {{ Str::limit($b->pickup_address, 30) }}</div>
              <div class="cb-route-arrow">↓</div>
              <div class="cb-route-to">🏁 {{ Str::limit($b->drop_address, 30) }}</div>
            </div>
          </td>
          <td data-label="Vehicle">
            <div style="font-weight:600;font-size:.82rem;color:#0F172A;">{{ $b->vehicle_name }}</div>
            @if($b->vehicle_number)
            <div style="font-size:.7rem;color:#94A3B8;">{{ $b->vehicle_number }}</div>
            @endif
            @if($b->seating_capacity)
            <div style="font-size:.68rem;color:#64748B;">{{ $b->seating_capacity }} seats</div>
            @endif
          </td>
          <td data-label="Pickup Date">
            <div style="font-weight:600;font-size:.82rem;">{{ $b->pickup_date->format('d M Y') }}</div>
            <div style="font-size:.72rem;color:#64748B;">
              {{ \Carbon\Carbon::parse($b->pickup_time)->format('g:i A') }}
            </div>
            @if($b->total_days > 1)
            <div style="font-size:.68rem;color:#94A3B8;">{{ $b->total_days }} day(s)</div>
            @endif
          </td>
          <td data-label="Amount">
            <div style="font-weight:700;color:#0F172A;">₹{{ number_format($b->total_amount, 2) }}</div>
            @if($b->pending_amount > 0)
            <div style="font-size:.72rem;color:#EF4444;">Due: ₹{{ number_format($b->pending_amount, 2) }}</div>
            @endif
          </td>
          <td data-label="Status">
            <span class="cb-badge {{ $b->booking_status }}">{{ ucfirst($b->booking_status) }}</span>
          </td>
          <td data-label="Staff">
            <div style="font-size:.78rem;color:#475569;">{{ optional($b->createdBy)->name ?? '—' }}</div>
          </td>
          <td data-label="Actions" style="white-space:nowrap;">
            <div class="cb-acts">
              <a href="{{ route('cab-bookings.show', $b->id) }}" class="cb-act view" title="View">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('cab-bookings.edit', $b->id) }}" class="cb-act edit" title="Edit">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="{{ route('cab-bookings.invoice', $b->id) }}" class="cb-act invoice" title="Invoice" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              </a>
              <form action="{{ route('cab-bookings.destroy', $b->id) }}" method="POST"
                    onsubmit="return confirm('Delete booking {{ $b->booking_number }}?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="cb-act danger" title="Delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    @if($query->hasPages())
    <div style="padding:14px 18px;border-top:1px solid #F1F5F9;">
      {{ $query->links() }}
    </div>
    @endif
    @endif
  </div>

</div>

<script>document.addEventListener('DOMContentLoaded',()=>feather.replace());</script>
@endsection
