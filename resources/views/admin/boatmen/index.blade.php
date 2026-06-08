@extends('admin.layouts.app')
@section('content')
<style>
.bm-page { padding: 24px; background: #F8FAFC; min-height: 100vh; }
.bm-header { background: linear-gradient(135deg,#0EA5E9,#0284C7); border-radius: 14px; padding: 20px 24px; margin-bottom: 22px; margin-top: 50px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.bm-header h1 { color: #fff; font-size: 1.15rem; font-weight: 800; margin: 0; }
.bm-header p  { color: rgba(255,255,255,.75); font-size: .75rem; margin: 3px 0 0; }
.bm-card  { background: #fff; border-radius: 14px; border: 1px solid #E2E8F0; box-shadow: 0 1px 4px rgba(0,0,0,.05); overflow: hidden; margin-bottom: 20px; }
.bm-card-head { padding: 14px 20px; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; gap: 8px; background: #FAFBFF; }
.bm-card-title { font-size: .88rem; font-weight: 700; color: #0F172A; }
.bm-card-body { padding: 18px 20px; }
.bm-label { font-size: .68rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 5px; display: block; }
.bm-input, .bm-select, .bm-textarea { width: 100%; border: 1.5px solid #E2E8F0; border-radius: 9px; padding: 9px 13px; font-size: .86rem; color: #0F172A; background: #FAFBFF; outline: none; transition: .15s; font-family: inherit; }
.bm-input:focus, .bm-select:focus, .bm-textarea:focus { border-color: #0EA5E9; box-shadow: 0 0 0 3px rgba(14,165,233,.1); }
.bm-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-size: .82rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; transition: .18s; }
.bm-btn-primary { background: linear-gradient(135deg,#0EA5E9,#0284C7); color: #fff; }
.bm-btn-primary:hover { opacity: .9; color: #fff; }
.bm-btn-danger  { background: #FEE2E2; color: #DC2626; }
.bm-btn-danger:hover { background: #FCA5A5; }
.bm-btn-success { background: #D1FAE5; color: #065F46; }
.bm-table { width: 100%; border-collapse: collapse; }
.bm-table th { background: #F8FAFC; padding: 10px 14px; text-align: left; font-size: .68rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .04em; border-bottom: 2px solid #E2E8F0; }
.bm-table td { padding: 12px 14px; font-size: .84rem; color: #0F172A; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
.bm-table tr:hover td { background: #F8FAFC; }
.bm-badge-on  { background: #D1FAE5; color: #065F46; font-size: .65rem; font-weight: 800; padding: 2px 9px; border-radius: 20px; }
.bm-badge-off { background: #FEE2E2; color: #DC2626; font-size: .65rem; font-weight: 800; padding: 2px 9px; border-radius: 20px; }
.bm-alert-ok  { background: #D1FAE5; border: 1.5px solid #6EE7B7; color: #065F46; border-radius: 10px; padding: 10px 16px; font-size: .82rem; font-weight: 600; margin-bottom: 16px; }
.bm-alert-err { background: #FEF2F2; border: 1.5px solid #FECACA; color: #991B1B; border-radius: 10px; padding: 10px 16px; font-size: .82rem; font-weight: 600; margin-bottom: 16px; }
</style>

<div class="bm-page">

  {{-- Header --}}
  <div class="bm-header">
    <div>
      <h1>⛵ Boatmen Management</h1>
      <p>Manage boatmen / operators assigned to boat bookings</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="bm-btn" style="background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.3);">← Dashboard</a>
  </div>

  @if(session('success'))
  <div class="bm-alert-ok">✅ {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div class="bm-alert-err">❌ {{ session('error') }}</div>
  @endif

  <div style="display:grid;grid-template-columns:340px 1fr;gap:20px;align-items:start;">

    {{-- Add / Edit Form --}}
    <div class="bm-card" id="bm-form-card">
      <div class="bm-card-head">
        <span>🧑‍✈️</span>
        <span class="bm-card-title" id="bm-form-title">Add New Boatman</span>
      </div>
      <div class="bm-card-body">
        <form id="bm-form" action="{{ route('boatmen.store') }}" method="POST">
          @csrf
          <input type="hidden" name="_method" id="bm-method" value="POST">

          <div style="margin-bottom:12px;">
            <label class="bm-label">Name <span style="color:#EF4444;">*</span></label>
            <input type="text" name="name" id="bm-name" class="bm-input" required placeholder="e.g. Ramesh Kumar">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
            <div>
              <label class="bm-label">Phone</label>
              <input type="text" name="phone" id="bm-phone" class="bm-input" placeholder="Mobile number">
            </div>
            <div>
              <label class="bm-label">Alt. Phone</label>
              <input type="text" name="alt_phone" id="bm-alt-phone" class="bm-input" placeholder="Alt. number">
            </div>
          </div>
          <div style="margin-bottom:12px;">
            <label class="bm-label">Ghat / Location</label>
            <input type="text" name="ghat" id="bm-ghat" class="bm-input" placeholder="e.g. Dashashwamedh Ghat">
          </div>
          <div style="margin-bottom:14px;">
            <label class="bm-label">Notes</label>
            <textarea name="notes" id="bm-notes" class="bm-input bm-textarea" rows="2" placeholder="Any extra info..."></textarea>
          </div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
            <label style="font-size:.82rem;font-weight:600;color:#374151;">Active</label>
            <input type="checkbox" name="is_active" id="bm-active" value="1" checked
                   style="width:16px;height:16px;cursor:pointer;">
          </div>
          <div style="display:flex;gap:8px;">
            <button type="submit" class="bm-btn bm-btn-primary" id="bm-submit-btn">
              ＋ Add Boatman
            </button>
            <button type="button" class="bm-btn" id="bm-cancel-btn"
                    style="display:none;background:#F1F5F9;color:#475569;"
                    onclick="bmReset()">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Boatmen Table --}}
    <div class="bm-card">
      <div class="bm-card-head">
        <span>📋</span>
        <span class="bm-card-title">All Boatmen ({{ count($boatmen) }})</span>
      </div>
      @if($boatmen->isEmpty())
      <div style="text-align:center;padding:40px;color:#94A3B8;">
        <div style="font-size:2rem;margin-bottom:8px;">⛵</div>
        No boatmen added yet. Add your first boatman using the form.
      </div>
      @else
      <div style="overflow-x:auto;">
        <table class="bm-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Ghat</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($boatmen as $bm)
            <tr>
              <td style="color:#94A3B8;font-size:.78rem;">{{ $loop->iteration }}</td>
              <td style="font-weight:700;">{{ $bm->name }}</td>
              <td style="color:#475569;">
                {{ $bm->phone ?: '—' }}
                @if($bm->alt_phone)
                  <br><span style="font-size:.72rem;color:#94A3B8;">{{ $bm->alt_phone }}</span>
                @endif
              </td>
              <td style="color:#475569;">{{ $bm->ghat ?: '—' }}</td>
              <td>
                <form action="{{ route('boatmen.toggle-status', $bm->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button type="submit" class="{{ $bm->is_active ? 'bm-badge-on' : 'bm-badge-off' }}"
                          style="border:none;cursor:pointer;background:{{ $bm->is_active ? '#D1FAE5' : '#FEE2E2' }}">
                    {{ $bm->is_active ? '● Active' : '○ Inactive' }}
                  </button>
                </form>
              </td>
              <td>
                <div style="display:flex;gap:6px;">
                  <button type="button" class="bm-btn bm-btn-success"
                          style="padding:5px 12px;font-size:.72rem;"
                          onclick="bmEdit({{ $bm->id }},'{{ addslashes($bm->name) }}','{{ addslashes($bm->phone ?? '') }}','{{ addslashes($bm->alt_phone ?? '') }}','{{ addslashes($bm->ghat ?? '') }}','{{ addslashes($bm->notes ?? '') }}',{{ $bm->is_active ? 'true' : 'false' }})">
                    ✏️ Edit
                  </button>
                  <form action="{{ route('boatmen.destroy', $bm->id) }}" method="POST" style="display:inline;"
                        onsubmit="return confirm('Delete {{ addslashes($bm->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bm-btn bm-btn-danger" style="padding:5px 12px;font-size:.72rem;">🗑</button>
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

  </div>
</div>

<script>
var baseUrl = '{{ url("admin/boatmen") }}';

function bmEdit(id, name, phone, altPhone, ghat, notes, isActive) {
    document.getElementById('bm-form').action = baseUrl + '/' + id;
    document.getElementById('bm-method').value = 'PUT';
    document.getElementById('bm-name').value     = name;
    document.getElementById('bm-phone').value    = phone;
    document.getElementById('bm-alt-phone').value= altPhone;
    document.getElementById('bm-ghat').value     = ghat;
    document.getElementById('bm-notes').value    = notes;
    document.getElementById('bm-active').checked = isActive;
    document.getElementById('bm-form-title').textContent = 'Edit Boatman';
    document.getElementById('bm-submit-btn').textContent = '✔ Update Boatman';
    document.getElementById('bm-cancel-btn').style.display = '';
    document.getElementById('bm-form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function bmReset() {
    document.getElementById('bm-form').action = baseUrl;
    document.getElementById('bm-form').reset();
    document.getElementById('bm-method').value = 'POST';
    document.getElementById('bm-form-title').textContent = 'Add New Boatman';
    document.getElementById('bm-submit-btn').textContent = '＋ Add Boatman';
    document.getElementById('bm-cancel-btn').style.display = 'none';
    document.getElementById('bm-active').checked = true;
}
</script>
@endsection
