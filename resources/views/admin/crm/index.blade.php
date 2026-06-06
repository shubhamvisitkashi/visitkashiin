@extends('admin.layouts.app')
@section('content')
<style>
:root{--bg:#F1F5F9;--card:#fff;--border:#E2E8F0;--shadow:0 1px 3px rgba(0,0,0,.05),0 4px 16px rgba(0,0,0,.06);--text:#0F172A;--sub:#475569;--muted:#94A3B8;--indigo:#4F46E5;--emerald:#10B981;--amber:#F59E0B;}
.crm-page{background:var(--bg);min-height:100vh;padding:20px 22px;}
.crm-header{background:linear-gradient(135deg,#0F172A,#1E3A5F);border-radius:16px;padding:22px 28px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;}
.crm-header h1{color:#fff;font-size:1.4rem;font-weight:800;margin:0;}
.crm-header p{color:rgba(255,255,255,.6);font-size:.8rem;margin:.2rem 0 0;}
.crm-kpi{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;}
@media(max-width:600px){.crm-kpi{grid-template-columns:1fr;}}
.crm-kpi-card{background:var(--card);border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow);padding:18px 20px;border-left:4px solid var(--kc,var(--indigo));}
.crm-kc-label{font-size:.68rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;}
.crm-kc-value{font-size:1.8rem;font-weight:800;color:var(--text);}
.crm-kc-sub{font-size:.72rem;color:var(--muted);margin-top:3px;}

.search-bar{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:14px 18px;margin-bottom:16px;display:flex;gap:10px;align-items:center;}
.search-input{flex:1;border:1.5px solid var(--border);border-radius:9px;padding:8px 14px;font-size:.85rem;color:var(--text);background:#FAFBFF;}
.search-input:focus{outline:none;border-color:var(--indigo);}
.search-btn{background:var(--indigo);color:#fff;border:none;border-radius:9px;padding:9px 20px;font-size:.82rem;font-weight:700;cursor:pointer;}

.cust-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;}
.cust-card{background:var(--card);border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow);padding:18px;transition:.18s;text-decoration:none;color:inherit;display:block;}
.cust-card:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.10);border-color:#A5B4FC;}
.cust-avatar{width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--indigo),#7C3AED);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;margin-bottom:12px;}
.cust-name{font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:2px;}
.cust-phone{font-size:.8rem;color:var(--muted);}
.cust-stats{display:flex;gap:12px;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);}
.cust-stat{flex:1;text-align:center;}
.cust-stat-val{font-size:1rem;font-weight:800;color:var(--text);}
.cust-stat-lbl{font-size:.62rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;}
.repeat-badge{display:inline-block;background:#D1FAE5;color:#065F46;padding:2px 8px;border-radius:20px;font-size:.62rem;font-weight:700;margin-left:6px;}
</style>

<div class="crm-page">

  <div class="crm-header">
    <div>
      <h1>👥 Customer CRM</h1>
      <p>All customers — booking history, revenue, repeat analysis</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" style="color:rgba(255,255,255,.6);font-size:.8rem;text-decoration:none;">← Dashboard</a>
  </div>

  <div class="crm-kpi">
    <div class="crm-kpi-card" style="--kc:var(--indigo);">
      <div class="crm-kc-label">Total Customers</div>
      <div class="crm-kc-value">{{ number_format($totalCustomers) }}</div>
      <div class="crm-kc-sub">Unique phone numbers</div>
    </div>
    <div class="crm-kpi-card" style="--kc:var(--emerald);">
      <div class="crm-kc-label">Repeat Customers</div>
      <div class="crm-kc-value">{{ number_format($repeatCustomers) }}</div>
      <div class="crm-kc-sub">Booked more than once</div>
    </div>
    <div class="crm-kpi-card" style="--kc:var(--amber);">
      <div class="crm-kc-label">Total Revenue (Stay)</div>
      <div class="crm-kc-value">₹{{ number_format($totalRevenue) }}</div>
      <div class="crm-kc-sub">All time booking revenue</div>
    </div>
  </div>

  <form method="GET" action="{{ route('customers.index') }}">
  <div class="search-bar">
    <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, phone or email…" class="search-input">
    <button type="submit" class="search-btn">🔍 Search</button>
    @if($search)
    <a href="{{ route('customers.index') }}" style="font-size:.8rem;color:var(--muted);text-decoration:none;">Clear</a>
    @endif
  </div>
  </form>

  <div class="cust-grid">
    @forelse($customers as $c)
    @php
      $initials = strtoupper(substr($c->guest_name, 0, 1)) . (strpos($c->guest_name,' ') !== false ? strtoupper(substr(strrchr($c->guest_name,' '),1,1)) : '');
      $isRepeat  = $c->booking_count > 1;
    @endphp
    <a href="{{ route('customers.show', $c->contact) }}" class="cust-card">
      <div class="cust-avatar">{{ $initials }}</div>
      <div class="cust-name">
        {{ $c->guest_name }}
        @if($isRepeat)<span class="repeat-badge">⭐ Repeat</span>@endif
      </div>
      <div class="cust-phone">📞 {{ $c->contact }}</div>
      @if($c->email)<div class="cust-phone">✉️ {{ $c->email }}</div>@endif
      <div class="cust-stats">
        <div class="cust-stat">
          <div class="cust-stat-val">{{ $c->booking_count }}</div>
          <div class="cust-stat-lbl">Bookings</div>
        </div>
        <div class="cust-stat">
          <div class="cust-stat-val">{{ $c->country ?? 'IN' }}</div>
          <div class="cust-stat-lbl">Country</div>
        </div>
        <div class="cust-stat">
          <div class="cust-stat-val" style="font-size:.8rem;">{{ $c->last_booking ? \Carbon\Carbon::parse($c->last_booking)->format('d M') : '—' }}</div>
          <div class="cust-stat-lbl">Last Booking</div>
        </div>
      </div>
    </a>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:50px;color:var(--muted);">
      <div style="font-size:3rem;margin-bottom:12px;">👥</div>
      <div style="font-size:1rem;font-weight:600;">No customers found</div>
      <div style="font-size:.82rem;margin-top:4px;">Create bookings to see customers here</div>
    </div>
    @endforelse
  </div>

  <div style="margin-top:20px;">{{ $customers->links() }}</div>

</div>
@endsection
