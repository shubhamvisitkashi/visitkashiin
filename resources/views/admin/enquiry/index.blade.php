@extends('admin.layouts.app')

@section('content')
<style>
    /* ── Header ── */
    .enq-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
        color: #fff;
        padding: 1.6rem 2rem;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 15px rgba(14,165,233,.28);
    }
    .enq-header h4 { font-size: 1.4rem; font-weight: 700; margin: 0; }
    .enq-header p  { margin: 0; opacity: .85; font-size: .88rem; }

    /* ── Category Tabs ── */
    .cat-tabs {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }
    .cat-tab {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .55rem 1.1rem;
        border-radius: 30px;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        border: 2px solid transparent;
        transition: all .18s;
        background: #fff;
        color: #64748b;
        box-shadow: 0 1px 4px rgba(0,0,0,.07);
    }
    .cat-tab:hover { transform: translateY(-1px); box-shadow: 0 3px 10px rgba(0,0,0,.1); color: #334155; }
    .cat-tab .tab-count {
        background: #f1f5f9;
        color: #475569;
        font-size: .72rem;
        padding: .1rem .45rem;
        border-radius: 20px;
        font-weight: 700;
    }

    /* Active states per tab */
    .cat-tab.active-all    { background: #0ea5e9; color: #fff; border-color: #0ea5e9; }
    .cat-tab.active-all    .tab-count { background: rgba(255,255,255,.25); color: #fff; }

    .cat-tab.active-hotel  { background: #7c3aed; color: #fff; border-color: #7c3aed; }
    .cat-tab.active-hotel  .tab-count { background: rgba(255,255,255,.25); color: #fff; }

    .cat-tab.active-cab    { background: #f59e0b; color: #fff; border-color: #f59e0b; }
    .cat-tab.active-cab    .tab-count { background: rgba(255,255,255,.25); color: #fff; }

    .cat-tab.active-boat   { background: #0891b2; color: #fff; border-color: #0891b2; }
    .cat-tab.active-boat   .tab-count { background: rgba(255,255,255,.25); color: #fff; }

    .cat-tab.active-tour   { background: #16a34a; color: #fff; border-color: #16a34a; }
    .cat-tab.active-tour   .tab-count { background: rgba(255,255,255,.25); color: #fff; }

    /* ── Search card ── */
    .search-card {
        background: #fff;
        border-radius: 10px;
        padding: 1.1rem 1.4rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.07);
        margin-bottom: 1.25rem;
    }

    /* ── Table ── */
    .enq-table thead th {
        background: #f8fafc;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        padding: .8rem 1rem;
        white-space: nowrap;
    }
    .enq-table tbody td {
        vertical-align: middle;
        padding: .8rem 1rem;
        border-color: #f1f5f9;
        font-size: .86rem;
    }
    .enq-table tbody tr:hover { background: #f8fafc; }

    /* ── Badges / Pills ── */
    .badge-pkg {
        background: #eff6ff; color: #1d4ed8;
        font-size: .73rem; font-weight: 600;
        padding: .28rem .6rem; border-radius: 20px;
        display: inline-block; max-width: 180px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .badge-hotel  { background: #f5f3ff; color: #6d28d9; }
    .badge-cab    { background: #fffbeb; color: #b45309; }
    .badge-boat   { background: #ecfeff; color: #0e7490; }
    .badge-tour   { background: #f0fdf4; color: #15803d; }

    .person-pill {
        background: #f0fdf4; color: #166534;
        font-size: .75rem; font-weight: 600;
        padding: .25rem .6rem; border-radius: 20px;
        display: inline-block;
    }
    .msg-preview {
        color: #64748b; font-size: .8rem;
        max-width: 160px; overflow: hidden;
        text-overflow: ellipsis; white-space: nowrap;
        display: block;
    }

    /* ── Action buttons ── */
    .action-btn {
        border: none; border-radius: 6px;
        padding: .3rem .55rem; cursor: pointer;
        font-size: .8rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-view { background: #eff6ff; color: #1d4ed8; }
    .btn-view:hover { background: #dbeafe; }
    .btn-del  { background: #fef2f2; color: #dc2626; }
    .btn-del:hover  { background: #fee2e2; }

    /* ── Modal ── */
    .enq-modal .modal-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
        color: #fff; border-radius: 10px 10px 0 0;
    }
    .enq-modal .modal-header.h-hotel { background: linear-gradient(135deg,#7c3aed,#4c1d95); }
    .enq-modal .modal-header.h-cab   { background: linear-gradient(135deg,#f59e0b,#b45309); }
    .enq-modal .modal-header.h-boat  { background: linear-gradient(135deg,#0891b2,#164e63); }
    .enq-modal .modal-header.h-tour  { background: linear-gradient(135deg,#16a34a,#14532d); }
    .enq-modal .modal-header .btn-close { filter: invert(1); }

    .field-group { margin-bottom: 1rem; }
    .field-label {
        font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
        color: #94a3b8; margin-bottom: .2rem;
    }
    .field-value {
        font-size: .9rem; font-weight: 500; color: #1e293b;
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 6px; padding: .42rem .75rem;
        min-height: 36px; display: flex; align-items: center;
        word-break: break-word;
    }
    .field-value.msg-box {
        align-items: flex-start; min-height: 72px;
        white-space: pre-wrap; line-height: 1.5;
    }
    .section-head {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: .75rem; padding-bottom: .5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .section-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem;
    }
    .section-head h6 {
        margin: 0; font-size: .78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px; color: #475569;
    }
    .cat-badge {
        display: inline-block; font-size: .7rem; font-weight: 700;
        padding: .2rem .6rem; border-radius: 20px;
        text-transform: uppercase; letter-spacing: .5px;
    }
    .empty-state { text-align: center; padding: 4rem 0; color: #94a3b8; }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: .4; }

    /* Copy Lead button */
    .btn-copy {
        background: #f0fdf4; color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .btn-copy:hover { background: #dcfce7; }
    .btn-copy.copied { background: #15803d; color: #fff; border-color: #15803d; }

    /* Price pill */
    .price-pill {
        background: #fef9f0; color: #92400e;
        font-size: .73rem; font-weight: 700;
        padding: .25rem .6rem; border-radius: 20px;
        display: inline-block; white-space: nowrap;
    }
    .price-pill.discounted { background: #fef2f2; color: #b91c1c; }
</style>

<div class="page-content">

    {{-- Header --}}
    <div class="enq-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h4>&#128140; Enquiries</h4>
            <p>All incoming enquiries from website visitors</p>
        </div>
        <div class="text-end">
            <span style="font-size:2rem;font-weight:800;">{{ $counts['all'] }}</span>
            <div style="font-size:.78rem;opacity:.8;">Total Enquiries</div>
        </div>
    </div>

    {{-- Category Tabs --}}
    <div class="cat-tabs">
        @php
            $tabs = [
                'all'   => ['label' => 'All Enquiries',   'icon' => '&#128101;', 'count' => $counts['all']],
                'hotel' => ['label' => 'Hotel / Homestay','icon' => '&#127968;', 'count' => $counts['hotel']],
                'cab'   => ['label' => 'Cab',             'icon' => '&#128663;', 'count' => $counts['cab']],
                'boat'  => ['label' => 'Boat',            'icon' => '&#9971;',  'count' => $counts['boat']],
                'tour'  => ['label' => 'Tour Packages',   'icon' => '&#128506;', 'count' => $counts['tour']],
            ];
        @endphp
        @foreach($tabs as $key => $tab)
            <a href="{{ route('enquiry.index', array_merge(request()->except(['category','page']), ['category' => $key])) }}"
               class="cat-tab {{ $category === $key ? 'active-'.$key : '' }}">
                {!! $tab['icon'] !!} {{ $tab['label'] }}
                <span class="tab-count">{{ $tab['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="search-card">
        <form action="{{ route('enquiry.index') }}" method="GET">
            <input type="hidden" name="category" value="{{ $category }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-muted small mb-1">Date Range</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="calendar" style="width:15px;height:15px;"></i></span>
                        <input type="text" name="daterange" id="daterange"
                               placeholder="Select date range..."
                               value="{{ $search_date_range }}"
                               class="form-control" autocomplete="off" readonly>
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label text-muted small mb-1">Search by Name / Phone</label>
                    <div class="input-group">
                        <input type="text" name="search_key" class="form-control"
                               value="{{ $search_key }}" placeholder="Name or phone number...">
                        <button type="submit" class="btn btn-primary px-3">
                            <i data-feather="search" style="width:15px;height:15px;"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('enquiry.index', ['category' => $category]) }}" class="btn btn-outline-secondary w-100">
                        <i data-feather="x" style="width:15px;height:15px;"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════
         TABLE — Hotel Tab (hotel_enquiries table)
    ═══════════════════════════════════════════════ --}}
    @if($category === 'hotel')
    <div class="card" style="border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);border:none;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table enq-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hotel / Homestay</th>
                            <th>Guest</th>
                            <th>Adults</th>
                            <th>Kids</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Nights</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hotelEnquiries as $key => $h)
                            <tr>
                                <td class="text-muted fw-semibold">
                                    {{ $key + 1 + ($hotelEnquiries->currentPage() - 1) * $hotelEnquiries->perPage() }}
                                </td>
                                <td>
                                    <span class="badge-pkg badge-hotel" title="{{ $h->hotel_name }}">
                                        &#127968; {{ \Str::limit($h->hotel_name, 22) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size:.88rem;">{{ $h->guest_name }}</div>
                                    <div class="text-muted" style="font-size:.78rem;">&#128222; {{ $h->contact_number }}</div>
                                </td>
                                <td>
                                    <span class="person-pill">&#128100; {{ $h->adults }}</span>
                                </td>
                                <td>
                                    @if($h->kids > 0)
                                        <span class="person-pill" style="background:#fef9f0;color:#92400e;">&#128118; {{ $h->kids }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    {{ $h->checkin_datetime->format('d M Y') }}<br>
                                    <span class="text-muted">{{ $h->checkin_datetime->format('h:i A') }}</span>
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    {{ $h->checkout_datetime->format('d M Y') }}<br>
                                    <span class="text-muted">{{ $h->checkout_datetime->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <span style="background:#eff6ff;color:#1d4ed8;font-size:.75rem;font-weight:700;padding:.3rem .65rem;border-radius:20px;display:inline-block;">
                                        &#127769; {{ $h->nights }}N
                                    </span>
                                </td>
                                <td style="font-size:.78rem;color:#64748b;white-space:nowrap;">
                                    {{ $h->created_at->format('d M Y') }}<br>
                                    {{ $h->created_at->format('h:i A') }}
                                </td>
                                <td style="white-space:nowrap;">
                                    <button type="button"
                                        class="action-btn btn-view me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewHotelEnqModal"
                                        data-hotel="{{ $h->hotel_name }}"
                                        data-guest="{{ $h->guest_name }}"
                                        data-phone="{{ $h->contact_number }}"
                                        data-adults="{{ $h->adults }}"
                                        data-kids="{{ $h->kids }}"
                                        data-checkin="{{ $h->checkin_datetime->format('d M Y, h:i A') }}"
                                        data-checkout="{{ $h->checkout_datetime->format('d M Y, h:i A') }}"
                                        data-nights="{{ $h->nights }}"
                                        data-submitted="{{ $h->created_at->format('d M Y, h:i A') }}"
                                        title="View Details">
                                        <i data-feather="eye" style="width:14px;height:14px;"></i>
                                    </button>

                                    <form action="{{ route('hotel-enquiry.admin.delete', $h->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="action-btn btn-del" title="Delete">
                                            <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon">&#127968;</div>
                                        <div style="font-size:1rem;font-weight:600;color:#475569;">No hotel enquiries yet</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hotelEnquiries->total() > 0)
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top flex-wrap gap-2">
                <p class="mb-0 text-muted" style="font-size:.83rem;">
                    Showing <strong>{{ ($hotelEnquiries->currentPage()-1)*$hotelEnquiries->perPage()+1 }}</strong>
                    – <strong>{{ min($hotelEnquiries->currentPage()*$hotelEnquiries->perPage(), $hotelEnquiries->total()) }}</strong>
                    of <strong>{{ $hotelEnquiries->total() }}</strong> hotel enquiries
                </p>
                <div>{{ $hotelEnquiries->appends(request()->query())->links() }}</div>
            </div>
            @endif
        </div>
    </div>

    @elseif($category === 'cab')
    {{-- ═══════════════════════════════════════════════
         TABLE — Cab Tab
    ═══════════════════════════════════════════════ --}}
    <div class="card" style="border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);border:none;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table enq-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Cab</th>
                            <th>Pickup Date</th>
                            <th>Trip Type</th>
                            <th>Pickup Location</th>
                            <th>Luggage</th>
                            <th>Price</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enquires as $key => $enquiry)
                            @php
                                $msg         = $enquiry->message ?? '';
                                $tripType    = preg_match('/Trip Type:\s*(.+)/i',        $msg, $m) ? trim($m[1]) : '—';
                                $pickupLoc   = preg_match('/Pickup Location:\s*(.+)/i',  $msg, $m) ? trim($m[1]) : '—';
                                $luggageCnt  = preg_match('/Luggage Bags:\s*(\d+)/i',    $msg, $m) ? trim($m[1]) : '0';
                                $roofCarrier = preg_match('/Roof Carrier:\s*(.+)/i',     $msg, $m) ? trim($m[1]) : '—';
                                $noteText    = preg_match('/Note:\s*([\s\S]+)$/i',       $msg, $m) ? trim($m[1]) : '';
                                $cabBasePrice = $enquiry->product_base_price ?? 0;
                                $cabDiscPrice = $enquiry->product_discounted_price ?? 0;
                                $cabShowPrice = $cabDiscPrice > 0 ? $cabDiscPrice : $cabBasePrice;
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">
                                    {{ $key + 1 + ($enquires->currentPage() - 1) * $enquires->perPage() }}
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size:.88rem;">{{ $enquiry->name }}</div>
                                    @if(!is_null($enquiry->no_of_person) && $enquiry->no_of_person !== '')
                                    <div style="font-size:.75rem;color:#475569;margin:1px 0;">&#128100; {{ $enquiry->no_of_person }} Person{{ $enquiry->no_of_person != 1 ? 's' : '' }}</div>
                                    @endif
                                    <div class="text-muted" style="font-size:.78rem;">&#128222; {{ $enquiry->phone }}</div>
                                </td>
                                <td>
                                    <span class="badge-pkg badge-cab" title="{{ $enquiry->package_name }}">
                                        &#128663; {{ \Str::limit($enquiry->package_name ?? '—', 22) }}
                                    </span>
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    @if($enquiry->arrival_date)
                                        {{ date('d M Y', strtotime($enquiry->arrival_date)) }}<br>
                                        <span class="text-muted">{{ date('h:i A', strtotime($enquiry->arrival_date)) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="background:#fffbeb;color:#b45309;font-size:.72rem;font-weight:600;padding:.25rem .55rem;border-radius:20px;display:inline-block;max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $tripType }}">
                                        &#128663; {{ $tripType }}
                                    </span>
                                </td>
                                <td>
                                    <span class="msg-preview" title="{{ $pickupLoc }}" style="max-width:130px;">{{ $pickupLoc }}</span>
                                </td>
                                <td style="white-space:nowrap;">
                                    <span style="font-size:.8rem;color:#475569;">{{ $luggageCnt }} bag{{ $luggageCnt != 1 ? 's' : '' }}</span>
                                    @if(strtolower($roofCarrier) === 'yes')
                                    <br><span style="font-size:.72rem;background:#fffbeb;color:#92400e;padding:.1rem .4rem;border-radius:4px;">&#128230; Roof</span>
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">
                                    @if($cabShowPrice > 0)
                                        @if($cabDiscPrice > 0 && $cabBasePrice > $cabDiscPrice)
                                        <div style="font-size:.72rem;color:#aaa;text-decoration:line-through;">₹{{ number_format($cabBasePrice) }}</div>
                                        @endif
                                        <span class="price-pill {{ ($cabDiscPrice > 0 && $cabBasePrice > $cabDiscPrice) ? 'discounted' : '' }}">
                                            ₹{{ number_format($cabShowPrice) }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:.78rem;">—</span>
                                    @endif
                                </td>
                                <td style="font-size:.78rem;color:#64748b;white-space:nowrap;">
                                    {{ $enquiry->created_at->format('d M Y') }}<br>
                                    {{ $enquiry->created_at->format('h:i A') }}
                                </td>
                                <td style="white-space:nowrap;">
                                    <button type="button"
                                        class="action-btn btn-view me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewCabEnqModal"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-cab="{{ $enquiry->package_name }}"
                                        data-pickup="{{ $enquiry->arrival_date ? date('d M Y, h:i A', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-persons="{{ $enquiry->no_of_person }}"
                                        data-triptype="{{ $tripType }}"
                                        data-pickuploc="{{ $pickupLoc }}"
                                        data-luggage="{{ $luggageCnt }}"
                                        data-roof="{{ $roofCarrier }}"
                                        data-note="{{ $noteText }}"
                                        data-baseprice="{{ $cabBasePrice }}"
                                        data-discprice="{{ $cabDiscPrice }}"
                                        data-submitted="{{ $enquiry->created_at->format('d M Y, h:i A') }}"
                                        title="View Details">
                                        <i data-feather="eye" style="width:14px;height:14px;"></i>
                                    </button>

                                    <button type="button"
                                        class="action-btn btn-copy me-1 cab-copy-lead-btn"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-cab="{{ $enquiry->package_name }}"
                                        data-pickup="{{ $enquiry->arrival_date ? date('d M Y, h:i A', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-persons="{{ $enquiry->no_of_person }}"
                                        data-triptype="{{ $tripType }}"
                                        data-pickuploc="{{ $pickupLoc }}"
                                        data-luggage="{{ $luggageCnt }}"
                                        data-roof="{{ $roofCarrier }}"
                                        data-price="{{ $cabShowPrice > 0 ? '₹'.number_format($cabShowPrice) : 'On request' }}"
                                        data-note="{{ $noteText }}"
                                        title="Copy Lead">
                                        <i data-feather="copy" style="width:14px;height:14px;"></i>
                                    </button>

                                    <form action="{{ route('enquiry.delete', $enquiry->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="action-btn btn-del" title="Delete">
                                            <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon">&#128663;</div>
                                        <div style="font-size:1rem;font-weight:600;color:#475569;">No cab enquiries yet</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($enquires && $enquires->total() > 0)
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top flex-wrap gap-2">
                <p class="mb-0 text-muted" style="font-size:.83rem;">
                    Showing <strong>{{ ($enquires->currentPage()-1)*$enquires->perPage()+1 }}</strong>
                    – <strong>{{ min($enquires->currentPage()*$enquires->perPage(), $enquires->total()) }}</strong>
                    of <strong>{{ $enquires->total() }}</strong> cab enquiries
                </p>
                <div>{{ $enquires->appends(request()->query())->links() }}</div>
            </div>
            @endif
        </div>
    </div>

    @elseif($category === 'boat')
    {{-- ═══════════════════════════════════════════════
         TABLE — Boat Tab
    ═══════════════════════════════════════════════ --}}
    <div class="card" style="border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);border:none;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table enq-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Boat / Package</th>
                            <th>Travel Date</th>
                            <th>Time Slot</th>
                            <th>Pickup Ghat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enquires as $key => $enquiry)
                            @php
                                $msg       = $enquiry->message ?? '';
                                // Prefer new DB columns, fall back to parsing message text
                                $timeSlot  = $enquiry->time_slot    ?: (preg_match('/Time Slot:\s*(.+)/i',      $msg, $m) ? trim($m[1]) : '—');
                                $ghat      = $enquiry->pickup_ghat  ?: (preg_match('/Pickup Ghat:\s*(.+)/i',    $msg, $m) ? trim($m[1]) : '—');
                                $persons   = $enquiry->no_of_person ?: (preg_match('/Adults:\s*(\d+)/i',        $msg, $m) ? trim($m[1]) : '—');
                                $children  = $enquiry->children_count > 0 ? $enquiry->children_count : (preg_match('/Children[^:]*:\s*(\d+)/i', $msg, $m) ? trim($m[1]) : '0');
                                $noteText  = $enquiry->special_notes ?: (preg_match('/Special Notes:\s*([\s\S]+)$/i', $msg, $m) ? trim($m[1]) : '');
                                $whatsapp  = preg_match('/WhatsApp:\s*(.+)/i', $msg, $m) ? trim($m[1]) : '';

                                $basePrice  = $enquiry->product_base_price ?? 0;
                                $discPrice  = $enquiry->product_discounted_price ?? 0;
                                $showPrice  = $enquiry->booking_amount > 0 ? $enquiry->booking_amount : ($discPrice > 0 ? $discPrice : $basePrice);
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">
                                    {{ $key + 1 + ($enquires->currentPage() - 1) * $enquires->perPage() }}
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size:.88rem;">{{ $enquiry->name }}</div>
                                    @if($persons !== '—')
                                    <div style="font-size:.75rem;color:#475569;margin:1px 0;">&#128100; {{ $persons }} Person{{ $persons != 1 ? 's' : '' }}</div>
                                    @endif
                                    <div class="text-muted" style="font-size:.78rem;">&#128222; {{ $enquiry->phone }}</div>
                                    @if($whatsapp && $whatsapp !== $enquiry->phone)
                                    <div class="text-muted" style="font-size:.72rem;color:#25d366!important;">&#128172; {{ $whatsapp }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-pkg badge-boat" title="{{ $enquiry->package_name }}">
                                        &#9971; {{ \Str::limit($enquiry->package_name ?? '—', 22) }}
                                    </span>
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    @if($enquiry->arrival_date)
                                        {{ date('d M Y', strtotime($enquiry->arrival_date)) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php $isEvening = str_contains(strtolower($timeSlot), 'evening'); @endphp
                                    <span style="font-size:.72rem;font-weight:700;padding:.25rem .55rem;border-radius:20px;display:inline-block;
                                        background:{{ $isEvening ? '#fef9f0' : '#eff6ff' }};
                                        color:{{ $isEvening ? '#92400e' : '#1d4ed8' }};">
                                        {{ $isEvening ? '🌆' : '🌅' }} {{ $isEvening ? 'Evening' : 'Morning' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="msg-preview" style="max-width:110px;" title="{{ $ghat }}">{{ $ghat }}</span>
                                </td>
                                <td style="white-space:nowrap;">
                                    <button type="button"
                                        class="action-btn btn-view me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewBoatEnqModal"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-whatsapp="{{ $whatsapp }}"
                                        data-boat="{{ $enquiry->package_name }}"
                                        data-date="{{ $enquiry->arrival_date ? date('d M Y', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-slot="{{ $timeSlot }}"
                                        data-ghat="{{ $ghat }}"
                                        data-persons="{{ $persons }}"
                                        data-children="{{ $children }}"
                                        data-baseprice="{{ $basePrice }}"
                                        data-discprice="{{ $discPrice }}"
                                        data-note="{{ $noteText }}"
                                        data-submitted="{{ $enquiry->created_at->format('d M Y, h:i A') }}"
                                        title="View Details">
                                        <i data-feather="eye" style="width:14px;height:14px;"></i>
                                    </button>

                                    <button type="button"
                                        class="action-btn btn-copy me-1 copy-lead-btn"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-whatsapp="{{ $whatsapp }}"
                                        data-boat="{{ $enquiry->package_name }}"
                                        data-date="{{ $enquiry->arrival_date ? date('d M Y', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-slot="{{ $timeSlot }}"
                                        data-ghat="{{ $ghat }}"
                                        data-persons="{{ $persons }}"
                                        data-children="{{ $children }}"
                                        data-price="{{ $showPrice > 0 ? '₹'.number_format($showPrice) : 'On request' }}"
                                        data-note="{{ $noteText }}"
                                        title="Copy Lead">
                                        <i data-feather="copy" style="width:14px;height:14px;"></i>
                                    </button>

                                    <form action="{{ route('enquiry.delete', $enquiry->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="action-btn btn-del" title="Delete">
                                            <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">&#9971;</div>
                                        <div style="font-size:1rem;font-weight:600;color:#475569;">No boat enquiries yet</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($enquires && $enquires->total() > 0)
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top flex-wrap gap-2">
                <p class="mb-0 text-muted" style="font-size:.83rem;">
                    Showing <strong>{{ ($enquires->currentPage()-1)*$enquires->perPage()+1 }}</strong>
                    – <strong>{{ min($enquires->currentPage()*$enquires->perPage(), $enquires->total()) }}</strong>
                    of <strong>{{ $enquires->total() }}</strong> boat enquiries
                </p>
                <div>{{ $enquires->appends(request()->query())->links() }}</div>
            </div>
            @endif
        </div>
    </div>

    @elseif($category === 'tour')
    {{-- ═══════════════════════════════════════════════
         TABLE — Tour Packages Tab
    ═══════════════════════════════════════════════ --}}
    <div class="card" style="border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);border:none;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table enq-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Package &#128506;</th>
                            <th>Travel Date</th>
                            <th>Duration</th>
                            <th>Hotel Cat.</th>
                            <th>Price</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enquires as $key => $enquiry)
                            @php
                                $msg        = $enquiry->message ?? '';
                                $tAdults    = preg_match('/Adults:\s*(\d+)/i',              $msg, $m) ? trim($m[1]) : ($enquiry->no_of_person ?: '—');
                                $tChildren  = preg_match('/Children:\s*(\d+)/i',            $msg, $m) ? trim($m[1]) : '0';
                                $tInfants   = preg_match('/Infants:\s*(\d+)/i',             $msg, $m) ? trim($m[1]) : '0';
                                $tDuration  = preg_match('/Duration:\s*(.+)/i',             $msg, $m) ? trim($m[1]) : '—';
                                $tPickup    = preg_match('/Pickup:\s*(.+)/i',               $msg, $m) ? trim($m[1]) : '—';
                                $tDrop      = preg_match('/Drop:\s*(.+)/i',                 $msg, $m) ? trim($m[1]) : '—';
                                $tHotelCat  = preg_match('/Hotel Category:\s*(.+)/i',      $msg, $m) ? trim($m[1]) : '—';
                                $tFoodPlan  = preg_match('/Food Plan:\s*(.+)/i',            $msg, $m) ? trim($m[1]) : '—';
                                $tCabType   = preg_match('/Cab Type:\s*(.+)/i',             $msg, $m) ? trim($m[1]) : '—';
                                $tNotes     = preg_match('/Notes:\s*([\s\S]+)$/i',          $msg, $m) ? trim($m[1]) : '';

                                $tBasePrice = $enquiry->product_base_price ?? 0;
                                $tDiscPrice = $enquiry->product_discounted_price ?? 0;
                                $tShowPrice = $tDiscPrice > 0 ? $tDiscPrice : $tBasePrice;
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">
                                    {{ $key + 1 + ($enquires->currentPage() - 1) * $enquires->perPage() }}
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size:.88rem;">{{ $enquiry->name }}</div>
                                    @if($tAdults !== '—')
                                    <div style="font-size:.75rem;color:#475569;margin:1px 0;">&#128100; {{ $tAdults }} Adult{{ $tAdults != 1 ? 's' : '' }}</div>
                                    @endif
                                    <div class="text-muted" style="font-size:.78rem;">&#128222; {{ $enquiry->phone }}</div>
                                </td>
                                <td>
                                    <span class="badge-pkg badge-tour" title="{{ $enquiry->package_name }}">
                                        &#128506; {{ \Str::limit($enquiry->package_name ?? '—', 22) }}
                                    </span>
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    @if($enquiry->arrival_date)
                                        {{ date('d M Y', strtotime($enquiry->arrival_date)) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="background:#f0fdf4;color:#15803d;font-size:.72rem;font-weight:600;padding:.25rem .55rem;border-radius:20px;display:inline-block;white-space:nowrap;">
                                        &#128197; {{ $tDuration }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size:.78rem;color:#475569;display:block;max-width:100px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $tHotelCat }}">
                                        {{ $tHotelCat !== '—' ? '&#127968; '.$tHotelCat : '—' }}
                                    </span>
                                </td>
                                <td style="white-space:nowrap;">
                                    @if($tShowPrice > 0)
                                        @if($tDiscPrice > 0 && $tBasePrice > $tDiscPrice)
                                        <div style="font-size:.72rem;color:#aaa;text-decoration:line-through;">₹{{ number_format($tBasePrice) }}</div>
                                        @endif
                                        <span class="price-pill {{ ($tDiscPrice > 0 && $tBasePrice > $tDiscPrice) ? 'discounted' : '' }}">
                                            ₹{{ number_format($tShowPrice) }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:.78rem;">—</span>
                                    @endif
                                </td>
                                <td style="font-size:.78rem;color:#64748b;white-space:nowrap;">
                                    {{ $enquiry->created_at->format('d M Y') }}<br>
                                    {{ $enquiry->created_at->format('h:i A') }}
                                </td>
                                <td style="white-space:nowrap;">
                                    <button type="button"
                                        class="action-btn btn-view me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewTourEnqModal"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-package="{{ $enquiry->package_name }}"
                                        data-date="{{ $enquiry->arrival_date ? date('d M Y', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-adults="{{ $tAdults }}"
                                        data-children="{{ $tChildren }}"
                                        data-infants="{{ $tInfants }}"
                                        data-duration="{{ $tDuration }}"
                                        data-pickup="{{ $tPickup }}"
                                        data-drop="{{ $tDrop }}"
                                        data-hotelcat="{{ $tHotelCat }}"
                                        data-foodplan="{{ $tFoodPlan }}"
                                        data-cabtype="{{ $tCabType }}"
                                        data-notes="{{ $tNotes }}"
                                        data-baseprice="{{ $tBasePrice }}"
                                        data-discprice="{{ $tDiscPrice }}"
                                        data-submitted="{{ $enquiry->created_at->format('d M Y, h:i A') }}"
                                        title="View Details">
                                        <i data-feather="eye" style="width:14px;height:14px;"></i>
                                    </button>

                                    <button type="button"
                                        class="action-btn btn-copy me-1 tour-copy-lead-btn"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-package="{{ $enquiry->package_name }}"
                                        data-date="{{ $enquiry->arrival_date ? date('d M Y', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-adults="{{ $tAdults }}"
                                        data-children="{{ $tChildren }}"
                                        data-infants="{{ $tInfants }}"
                                        data-duration="{{ $tDuration }}"
                                        data-pickup="{{ $tPickup }}"
                                        data-drop="{{ $tDrop }}"
                                        data-hotelcat="{{ $tHotelCat }}"
                                        data-foodplan="{{ $tFoodPlan }}"
                                        data-cabtype="{{ $tCabType }}"
                                        data-price="{{ $tShowPrice > 0 ? '₹'.number_format($tShowPrice) : 'On request' }}"
                                        data-notes="{{ $tNotes }}"
                                        title="Copy Lead">
                                        <i data-feather="copy" style="width:14px;height:14px;"></i>
                                    </button>

                                    <form action="{{ route('enquiry.delete', $enquiry->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="action-btn btn-del" title="Delete">
                                            <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-icon">&#128506;</div>
                                        <div style="font-size:1rem;font-weight:600;color:#475569;">No tour package enquiries yet</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($enquires && $enquires->total() > 0)
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top flex-wrap gap-2">
                <p class="mb-0 text-muted" style="font-size:.83rem;">
                    Showing <strong>{{ ($enquires->currentPage()-1)*$enquires->perPage()+1 }}</strong>
                    – <strong>{{ min($enquires->currentPage()*$enquires->perPage(), $enquires->total()) }}</strong>
                    of <strong>{{ $enquires->total() }}</strong> tour enquiries
                </p>
                <div>{{ $enquires->appends(request()->query())->links() }}</div>
            </div>
            @endif
        </div>
    </div>

    @else
    {{-- ═══════════════════════════════════════════════
         TABLE — All other tabs (enquiries table)
    ═══════════════════════════════════════════════ --}}
    <div class="card" style="border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);border:none;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table enq-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Package</th>
                            <th>Arrival Date</th>
                            <th>Persons</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enquires as $key => $enquiry)
                            @php
                                $slug    = $enquiry->cat_slug ?? '';
                                $catKey  = in_array($slug, ['hotels','homestay']) ? 'hotel'
                                         : ($slug === 'cab'  ? 'cab'
                                         : ($slug === 'boat' ? 'boat' : 'tour'));
                                $badgeCls = 'badge-pkg badge-'.$catKey;
                                $catLabel = match($catKey) {
                                    'hotel' => '&#127968; Hotel',
                                    'cab'   => '&#128663; Cab',
                                    'boat'  => '&#9971; Boat',
                                    default => '&#128506; Tour',
                                };
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">
                                    {{ $key + 1 + ($enquires->currentPage() - 1) * $enquires->perPage() }}
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size:.88rem;">{{ $enquiry->name }}</div>
                                    <div class="text-muted" style="font-size:.78rem;">&#128222; {{ $enquiry->phone }}</div>
                                </td>
                                <td>
                                    <span class="cat-badge" style="
                                        background:{{ $catKey==='hotel' ? '#f5f3ff' : ($catKey==='cab' ? '#fffbeb' : ($catKey==='boat' ? '#ecfeff' : '#f0fdf4')) }};
                                        color:{{ $catKey==='hotel' ? '#6d28d9' : ($catKey==='cab' ? '#b45309' : ($catKey==='boat' ? '#0e7490' : '#15803d')) }};">
                                        {!! $catLabel !!}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $badgeCls }}" title="{{ $enquiry->package_name }}">
                                        {{ $enquiry->package_name ?: '—' }}
                                    </span>
                                </td>
                                <td style="font-size:.82rem;">
                                    {{ $enquiry->arrival_date ? date('d M Y', strtotime($enquiry->arrival_date)) : '—' }}
                                </td>
                                <td>
                                    @if(!is_null($enquiry->no_of_person) && $enquiry->no_of_person !== '')
                                        <span class="person-pill">&#128100; {{ $enquiry->no_of_person }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="msg-preview" title="{{ $enquiry->message }}">
                                        {{ $enquiry->message ? \Str::limit($enquiry->message, 40) : '—' }}
                                    </span>
                                </td>
                                <td style="white-space:nowrap;">
                                    <button type="button"
                                        class="action-btn btn-view me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewEnqModal"
                                        data-catkey="{{ $catKey }}"
                                        data-catlabel="{{ strip_tags($catLabel) }}"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-persons="{{ $enquiry->no_of_person }}"
                                        data-arrival="{{ $enquiry->arrival_date ? date('d M Y', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-checkin=""
                                        data-checkout=""
                                        data-package="{{ $enquiry->package_name }}"
                                        data-message="{{ $enquiry->message }}"
                                        data-submitted="{{ $enquiry->created_at->format('d M Y, h:i A') }}"
                                        title="View Details">
                                        <i data-feather="eye" style="width:14px;height:14px;"></i>
                                    </button>
                                    <button type="button"
                                        class="action-btn btn-copy me-1 all-copy-lead-btn"
                                        data-name="{{ $enquiry->name }}"
                                        data-phone="{{ $enquiry->phone }}"
                                        data-category="{{ strip_tags($catLabel) }}"
                                        data-package="{{ $enquiry->package_name }}"
                                        data-arrival="{{ $enquiry->arrival_date ? date('d M Y', strtotime($enquiry->arrival_date)) : '' }}"
                                        data-persons="{{ $enquiry->no_of_person }}"
                                        data-message="{{ $enquiry->message }}"
                                        title="Copy Lead">
                                        <i data-feather="copy" style="width:14px;height:14px;"></i>
                                    </button>
                                    <form action="{{ route('enquiry.delete', $enquiry->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="action-btn btn-del" title="Delete">
                                            <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-icon">&#128140;</div>
                                        <div style="font-size:1rem;font-weight:600;color:#475569;">No enquiries found</div>
                                        <div style="font-size:.85rem;margin-top:.25rem;">
                                            @if($category !== 'all') Try switching to a different category tab. @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($enquires && $enquires->total() > 0)
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top flex-wrap gap-2">
                <p class="mb-0 text-muted" style="font-size:.83rem;">
                    Showing <strong>{{ ($enquires->currentPage()-1)*$enquires->perPage()+1 }}</strong>
                    – <strong>{{ min($enquires->currentPage()*$enquires->perPage(), $enquires->total()) }}</strong>
                    of <strong>{{ $enquires->total() }}</strong> enquiries
                </p>
                <div>{{ $enquires->appends(request()->query())->links() }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>

{{-- ===== Tour Package Enquiry View Modal ===== --}}
<div class="modal fade enq-modal" id="viewTourEnqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:10px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header h-tour">
                <div>
                    <h5 class="modal-title mb-0">&#128506; Tour Package Enquiry</h5>
                    <small style="opacity:.8;" id="tm-submitted"></small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="tm-copy-btn"
                        class="btn btn-sm"
                        style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                        <i data-feather="copy" style="width:13px;height:13px;"></i> Copy Lead
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4">

                {{-- Customer Info --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#f0fdf4;">&#128100;</div>
                    <h6>Customer Information</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="field-label">Full Name</div>
                        <div class="field-value" id="tm-name">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Phone Number</div>
                        <div class="field-value" id="tm-phone">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Received On</div>
                        <div class="field-value" style="font-size:.8rem;" id="tm-submitted-field">—</div>
                    </div>
                </div>

                {{-- Package Details --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#f0fdf4;">&#128506;</div>
                    <h6>Package Details</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="field-label">Package Name</div>
                        <div class="field-value" id="tm-package">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Package Price</div>
                        <div class="field-value" id="tm-price" style="font-weight:700;color:#15803d;">—</div>
                    </div>
                </div>

                {{-- Travel Details --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fef9f0;">&#128197;</div>
                    <h6>Travel Details</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="field-label">Travel Date</div>
                        <div class="field-value" id="tm-date">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Duration</div>
                        <div class="field-value" id="tm-duration">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Pickup Point</div>
                        <div class="field-value" id="tm-pickup">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Drop Point</div>
                        <div class="field-value" id="tm-drop">—</div>
                    </div>
                    <div class="col-md-2">
                        <div class="field-label">Adults (12+)</div>
                        <div class="field-value" id="tm-adults">—</div>
                    </div>
                    <div class="col-md-2">
                        <div class="field-label">Children (2-12)</div>
                        <div class="field-value" id="tm-children">—</div>
                    </div>
                    <div class="col-md-2">
                        <div class="field-label">Infants (&lt;2)</div>
                        <div class="field-value" id="tm-infants">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Total Travellers</div>
                        <div class="field-value" id="tm-total-pax" style="font-weight:700;">—</div>
                    </div>
                </div>

                {{-- Preferences --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#f5f3ff;">&#127968;</div>
                    <h6>Preferences</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="field-label">Hotel Category</div>
                        <div class="field-value" id="tm-hotelcat">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Food Plan</div>
                        <div class="field-value" id="tm-foodplan">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Cab Type</div>
                        <div class="field-value" id="tm-cabtype">—</div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fff7ed;">&#128172;</div>
                    <h6>Special Requests / Notes</h6>
                </div>
                <div class="field-value msg-box" id="tm-notes">—</div>

            </div>
            <div class="modal-footer border-0 pt-0 gap-2">
                <button type="button" id="tm-copy-footer-btn"
                    class="btn btn-sm btn-success"
                    style="font-size:.82rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                    <i data-feather="copy" style="width:13px;height:13px;"></i> Copy Lead
                </button>
                <a id="tm-wa-btn" href="#" target="_blank" rel="noopener"
                    class="btn btn-sm"
                    style="background:#25d366;color:#fff;font-size:.82rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                    <i data-feather="message-circle" style="width:13px;height:13px;"></i> WhatsApp
                </a>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== View Enquiry Modal ===== --}}
<div class="modal fade enq-modal" id="viewEnqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:10px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header" id="modal-header">
                <div>
                    <h5 class="modal-title mb-0" id="modal-title">Enquiry Details</h5>
                    <small style="opacity:.8;" id="modal-submitted"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">

                {{-- Customer Info --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#eff6ff;">&#128100;</div>
                    <h6>Customer Information</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="field-label">Full Name</div>
                        <div class="field-value" id="modal-name">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Phone Number</div>
                        <div class="field-value" id="modal-phone">—</div>
                    </div>
                    <div class="col-md-2">
                        <div class="field-label">Category</div>
                        <div class="field-value" id="modal-category">—</div>
                    </div>
                    <div class="col-md-2">
                        <div class="field-label">Submitted On</div>
                        <div class="field-value" style="font-size:.8rem;" id="modal-submitted-field">—</div>
                    </div>
                </div>

                {{-- Package --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#f0fdf4;">&#128506;</div>
                    <h6>Package / Service</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="field-label">Package Name</div>
                        <div class="field-value" id="modal-package">—</div>
                    </div>
                </div>

                {{-- Trip Details --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fef9f0;">&#128197;</div>
                    <h6>Trip / Booking Details</h6>
                </div>
                <div class="row g-3 mb-4" id="modal-trip-section">
                    {{-- Filled dynamically via JS --}}
                </div>

                {{-- Message --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fff7ed;">&#128172;</div>
                    <h6>Message</h6>
                </div>
                <div class="field-value msg-box" id="modal-message">—</div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== Hotel Enquiry View Modal ===== --}}
<div class="modal fade enq-modal" id="viewHotelEnqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:10px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header h-hotel">
                <div>
                    <h5 class="modal-title mb-0">&#127968; Hotel Enquiry Details</h5>
                    <small style="opacity:.8;" id="hm-submitted"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">

                <div class="section-head">
                    <div class="section-icon" style="background:#f5f3ff;">&#127968;</div>
                    <h6>Hotel Information</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="field-label">Hotel / Homestay Name</div>
                        <div class="field-value" id="hm-hotel">—</div>
                    </div>
                </div>

                <div class="section-head">
                    <div class="section-icon" style="background:#eff6ff;">&#128100;</div>
                    <h6>Guest Information</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <div class="field-label">Guest Name</div>
                        <div class="field-value" id="hm-guest">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Contact Number</div>
                        <div class="field-value" id="hm-phone">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Submitted On</div>
                        <div class="field-value" style="font-size:.8rem;" id="hm-submitted-field">—</div>
                    </div>
                </div>

                <div class="section-head">
                    <div class="section-icon" style="background:#fef9f0;">&#128197;</div>
                    <h6>Stay Details</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-2">
                        <div class="field-label">Adults (18+)</div>
                        <div class="field-value" id="hm-adults">—</div>
                    </div>
                    <div class="col-md-2">
                        <div class="field-label">Kids (&lt;5)</div>
                        <div class="field-value" id="hm-kids">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Check-In</div>
                        <div class="field-value" style="font-size:.82rem;" id="hm-checkin">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Check-Out</div>
                        <div class="field-value" style="font-size:.82rem;" id="hm-checkout">—</div>
                    </div>
                    <div class="col-md-2">
                        <div class="field-label">Nights</div>
                        <div class="field-value" id="hm-nights">—</div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== Cab Enquiry View Modal ===== --}}
<div class="modal fade enq-modal" id="viewCabEnqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:10px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header h-cab">
                <div>
                    <h5 class="modal-title mb-0">&#128663; Cab Booking Enquiry</h5>
                    <small style="opacity:.8;" id="cm-submitted"></small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="cm-copy-btn"
                        class="btn btn-sm"
                        style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                        <i data-feather="copy" style="width:13px;height:13px;"></i> Copy Lead
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4">

                {{-- Customer --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fffbeb;">&#128664;</div>
                    <h6>Customer Information</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="field-label">Full Name</div>
                        <div class="field-value" id="cm-name">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Contact Number</div>
                        <div class="field-value" id="cm-phone">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Received On</div>
                        <div class="field-value" style="font-size:.8rem;" id="cm-submitted-field">—</div>
                    </div>
                </div>

                {{-- Cab --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fffbeb;">&#128663;</div>
                    <h6>Cab Details</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="field-label">Cab / Package</div>
                        <div class="field-value" id="cm-cab">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Package Price</div>
                        <div class="field-value" id="cm-price" style="font-weight:700;color:#b45309;">—</div>
                    </div>
                </div>

                {{-- Trip --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fef9f0;">&#128197;</div>
                    <h6>Trip Details</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="field-label">Pickup Date &amp; Time</div>
                        <div class="field-value" id="cm-pickup">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Trip Type</div>
                        <div class="field-value" id="cm-triptype">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Pickup Location</div>
                        <div class="field-value" id="cm-pickuploc">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Persons</div>
                        <div class="field-value" id="cm-persons">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Luggage Bags</div>
                        <div class="field-value" id="cm-luggage">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Roof Carrier</div>
                        <div class="field-value" id="cm-roof">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Est. Fare</div>
                        <div class="field-value" id="cm-est-price" style="font-weight:700;color:#b45309;">—</div>
                    </div>
                </div>

                {{-- Note --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fff7ed;">&#128172;</div>
                    <h6>Additional Note</h6>
                </div>
                <div class="field-value msg-box" id="cm-note">—</div>

            </div>
            <div class="modal-footer border-0 pt-0 gap-2">
                <button type="button" id="cm-copy-footer-btn"
                    class="btn btn-sm btn-success"
                    style="font-size:.82rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                    <i data-feather="copy" style="width:13px;height:13px;"></i> Copy Lead
                </button>
                <a id="cm-wa-btn" href="#" target="_blank" rel="noopener"
                    class="btn btn-sm"
                    style="background:#25d366;color:#fff;font-size:.82rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                    <i data-feather="message-circle" style="width:13px;height:13px;"></i> WhatsApp
                </a>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== Boat Enquiry View Modal ===== --}}
<div class="modal fade enq-modal" id="viewBoatEnqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:10px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header h-boat">
                <div>
                    <h5 class="modal-title mb-0">&#9971; Boat Booking Enquiry</h5>
                    <small style="opacity:.8;" id="bm-submitted"></small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="bm-copy-btn"
                        class="btn btn-sm"
                        style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                        <i data-feather="copy" style="width:13px;height:13px;"></i> Copy Lead
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4">

                {{-- Customer --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#ecfeff;">&#128100;</div>
                    <h6>Customer Information</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="field-label">Full Name</div>
                        <div class="field-value" id="bm-name">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Mobile Number</div>
                        <div class="field-value" id="bm-phone">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">WhatsApp Number</div>
                        <div class="field-value" id="bm-whatsapp">—</div>
                    </div>
                </div>

                {{-- Boat --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#ecfeff;">&#9971;</div>
                    <h6>Boat Details</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="field-label">Boat / Package</div>
                        <div class="field-value" id="bm-boat">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Package Price</div>
                        <div class="field-value" id="bm-price" style="font-weight:700;color:#0e7490;">—</div>
                    </div>
                </div>

                {{-- Trip Details --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fef9f0;">&#128197;</div>
                    <h6>Trip Details</h6>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="field-label">Travel Date</div>
                        <div class="field-value" id="bm-date">—</div>
                    </div>
                    <div class="col-md-5">
                        <div class="field-label">Time Slot</div>
                        <div class="field-value" id="bm-slot">—</div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Pickup Ghat</div>
                        <div class="field-value" id="bm-ghat">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Persons</div>
                        <div class="field-value" id="bm-persons">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Children (under 10)</div>
                        <div class="field-value" id="bm-children">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Received On</div>
                        <div class="field-value" style="font-size:.8rem;" id="bm-submitted-field">—</div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-label">Est. Price</div>
                        <div class="field-value" id="bm-est-price" style="font-weight:700;color:#0e7490;">—</div>
                    </div>
                </div>

                {{-- Note --}}
                <div class="section-head">
                    <div class="section-icon" style="background:#fff7ed;">&#128172;</div>
                    <h6>Special Requests / Notes</h6>
                </div>
                <div class="field-value msg-box" id="bm-note">—</div>

            </div>
            <div class="modal-footer border-0 pt-0 gap-2">
                <button type="button" id="bm-copy-footer-btn"
                    class="btn btn-sm btn-success"
                    style="font-size:.82rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                    <i data-feather="copy" style="width:13px;height:13px;"></i> Copy Lead
                </button>
                <a id="bm-wa-btn" href="#" target="_blank" rel="noopener"
                    class="btn btn-sm"
                    style="background:#25d366;color:#fff;font-size:.82rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                    <i data-feather="message-circle" style="width:13px;height:13px;"></i> WhatsApp
                </a>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Tour Enquiry Modal ──────────────────────────────────────
var tmData = {};
var tmModal = document.getElementById('viewTourEnqModal');
if (tmModal) {
    tmModal.addEventListener('show.bs.modal', function (e) {
        var b = e.relatedTarget;
        var basePrice = parseFloat(b.dataset.baseprice) || 0;
        var discPrice = parseFloat(b.dataset.discprice) || 0;
        var showPrice = discPrice > 0 ? discPrice : basePrice;

        var priceHtml = showPrice > 0
            ? (discPrice > 0 && basePrice > discPrice
                ? '<span style="text-decoration:line-through;color:#aaa;font-size:.82rem;font-weight:400;">₹' + basePrice.toLocaleString('en-IN') + '</span> ₹' + discPrice.toLocaleString('en-IN')
                : '₹' + showPrice.toLocaleString('en-IN'))
            : '—';

        var adults   = parseInt(b.dataset.adults)   || 0;
        var children = parseInt(b.dataset.children) || 0;
        var infants  = parseInt(b.dataset.infants)  || 0;
        var totalPax = adults + children + infants;

        document.getElementById('tm-name').textContent            = b.dataset.name      || '—';
        document.getElementById('tm-phone').textContent           = b.dataset.phone     || '—';
        document.getElementById('tm-submitted-field').textContent = b.dataset.submitted || '—';
        document.getElementById('tm-submitted').textContent       = 'Received: ' + (b.dataset.submitted || '');
        document.getElementById('tm-package').textContent         = b.dataset.package   || '—';
        document.getElementById('tm-price').innerHTML             = priceHtml;
        document.getElementById('tm-date').textContent            = b.dataset.date      || '—';
        document.getElementById('tm-duration').textContent        = b.dataset.duration  || '—';
        document.getElementById('tm-pickup').textContent          = b.dataset.pickup    || '—';
        document.getElementById('tm-drop').textContent            = b.dataset.drop      || '—';
        document.getElementById('tm-adults').textContent          = b.dataset.adults    || '—';
        document.getElementById('tm-children').textContent        = b.dataset.children  || '0';
        document.getElementById('tm-infants').textContent         = b.dataset.infants   || '0';
        document.getElementById('tm-total-pax').textContent       = totalPax > 0 ? totalPax + ' Person' + (totalPax !== 1 ? 's' : '') : '—';
        document.getElementById('tm-hotelcat').textContent        = b.dataset.hotelcat  || '—';
        document.getElementById('tm-foodplan').textContent        = b.dataset.foodplan  || '—';
        document.getElementById('tm-cabtype').textContent         = b.dataset.cabtype   || '—';
        document.getElementById('tm-notes').textContent           = b.dataset.notes     || '—';

        // WhatsApp link
        var waNum = (b.dataset.phone || '').replace(/\D/g, '');
        var waMsg = encodeURIComponent(
            'Hi ' + (b.dataset.name || '') + ', your tour package enquiry for "' +
            (b.dataset.package || '') + '" has been received. We will contact you shortly.'
        );
        document.getElementById('tm-wa-btn').href = waNum
            ? 'https://wa.me/' + (waNum.startsWith('91') ? waNum : '91' + waNum) + '?text=' + waMsg
            : '#';

        tmData = {
            name: b.dataset.name, phone: b.dataset.phone,
            package: b.dataset.package,
            date: b.dataset.date, duration: b.dataset.duration,
            pickup: b.dataset.pickup, drop: b.dataset.drop,
            adults: b.dataset.adults, children: b.dataset.children, infants: b.dataset.infants,
            hotelcat: b.dataset.hotelcat, foodplan: b.dataset.foodplan, cabtype: b.dataset.cabtype,
            price: showPrice > 0 ? '₹' + showPrice.toLocaleString('en-IN') : 'On request',
            notes: b.dataset.notes, submitted: b.dataset.submitted
        };

        feather.replace();
    });

    function buildTourLeadText(d) {
        return [
            '🗺️ TOUR PACKAGE LEAD',
            '──────────────────────',
            '👤 Name         : ' + (d.name     || '—'),
            '📞 Phone        : ' + (d.phone    || '—'),
            '──────────────────────',
            '📦 Package      : ' + (d.package  || '—'),
            '💰 Price        : ' + (d.price    || '—'),
            '──────────────────────',
            '📅 Travel Date  : ' + (d.date     || '—'),
            '🗓️  Duration     : ' + (d.duration || '—'),
            '📍 Pickup       : ' + (d.pickup   || '—'),
            '🏁 Drop         : ' + (d.drop     || '—'),
            '👥 Adults       : ' + (d.adults   || '—'),
            '👧 Children     : ' + (d.children || '0'),
            '👶 Infants      : ' + (d.infants  || '0'),
            '──────────────────────',
            '🏨 Hotel Cat.   : ' + (d.hotelcat || '—'),
            '🍽️  Food Plan    : ' + (d.foodplan || '—'),
            '🚗 Cab Type     : ' + (d.cabtype  || '—'),
            (d.notes ? '📝 Notes        : ' + d.notes : ''),
            '──────────────────────',
            '🕐 Received     : ' + (d.submitted || '—'),
        ].filter(Boolean).join('\n');
    }

    function doTourCopy(text, btn) {
        navigator.clipboard.writeText(text).then(function () {
            var orig = btn.innerHTML;
            btn.innerHTML = '<i data-feather="check" style="width:13px;height:13px;"></i> Copied!';
            btn.classList.add('copied');
            feather.replace();
            setTimeout(function () { btn.innerHTML = orig; btn.classList.remove('copied'); feather.replace(); }, 2000);
        });
    }

    document.getElementById('tm-copy-btn').addEventListener('click', function () {
        doTourCopy(buildTourLeadText(tmData), this);
    });
    document.getElementById('tm-copy-footer-btn').addEventListener('click', function () {
        doTourCopy(buildTourLeadText(tmData), this);
    });
}

// ── Copy Lead — tour table row buttons ──────────────────────
document.querySelectorAll('.tour-copy-lead-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        var text = [
            '🗺️ TOUR PACKAGE LEAD',
            '──────────────────────',
            '👤 Name         : ' + (d.name     || '—'),
            '📞 Phone        : ' + (d.phone    || '—'),
            '──────────────────────',
            '📦 Package      : ' + (d.package  || '—'),
            '💰 Price        : ' + (d.price    || '—'),
            '──────────────────────',
            '📅 Travel Date  : ' + (d.date     || '—'),
            '🗓️  Duration     : ' + (d.duration || '—'),
            '📍 Pickup       : ' + (d.pickup   || '—'),
            '🏁 Drop         : ' + (d.drop     || '—'),
            '👥 Adults       : ' + (d.adults   || '—'),
            '👧 Children     : ' + (d.children || '0'),
            '👶 Infants      : ' + (d.infants  || '0'),
            '──────────────────────',
            '🏨 Hotel Cat.   : ' + (d.hotelcat || '—'),
            '🍽️  Food Plan    : ' + (d.foodplan || '—'),
            '🚗 Cab Type     : ' + (d.cabtype  || '—'),
            (d.notes ? '📝 Notes        : ' + d.notes : ''),
        ].filter(Boolean).join('\n');

        navigator.clipboard.writeText(text).then(() => {
            var orig = this.innerHTML;
            this.innerHTML = '<i data-feather="check" style="width:13px;height:13px;"></i>';
            this.classList.add('copied');
            feather.replace();
            setTimeout(() => { this.innerHTML = orig; this.classList.remove('copied'); feather.replace(); }, 2000);
        });
    });
});

// Cab Enquiry Modal
var cmData = {};
var cmModal = document.getElementById('viewCabEnqModal');
if (cmModal) {
    cmModal.addEventListener('show.bs.modal', function (e) {
        var b = e.relatedTarget;
        var basePrice = parseFloat(b.dataset.baseprice) || 0;
        var discPrice = parseFloat(b.dataset.discprice) || 0;
        var showPrice = discPrice > 0 ? discPrice : basePrice;

        var priceHtml = showPrice > 0
            ? (discPrice > 0 && basePrice > discPrice
                ? '<span style="text-decoration:line-through;color:#aaa;font-size:.82rem;font-weight:400;">₹' + basePrice.toLocaleString('en-IN') + '</span> ₹' + discPrice.toLocaleString('en-IN')
                : '₹' + showPrice.toLocaleString('en-IN'))
            : '—';

        document.getElementById('cm-name').textContent            = b.dataset.name      || '—';
        document.getElementById('cm-phone').textContent           = b.dataset.phone     || '—';
        document.getElementById('cm-submitted-field').textContent = b.dataset.submitted || '—';
        document.getElementById('cm-submitted').textContent       = 'Received: ' + (b.dataset.submitted || '');
        document.getElementById('cm-cab').textContent             = b.dataset.cab       || '—';
        document.getElementById('cm-price').innerHTML             = priceHtml;
        document.getElementById('cm-pickup').textContent          = b.dataset.pickup    || '—';
        document.getElementById('cm-triptype').textContent        = b.dataset.triptype  || '—';
        document.getElementById('cm-pickuploc').textContent       = b.dataset.pickuploc || '—';
        document.getElementById('cm-persons').textContent         = b.dataset.persons   || '—';
        document.getElementById('cm-luggage').textContent         = (b.dataset.luggage || '0') + ' bag(s)';
        document.getElementById('cm-roof').textContent            = b.dataset.roof      || '—';
        document.getElementById('cm-est-price').textContent       = showPrice > 0 ? '₹' + showPrice.toLocaleString('en-IN') : '—';
        document.getElementById('cm-note').textContent            = b.dataset.note      || '—';

        // WhatsApp link
        var waNum = (b.dataset.phone || '').replace(/\D/g, '');
        var waMsg = encodeURIComponent(
            'Hi ' + (b.dataset.name || '') + ', your cab booking enquiry for "' +
            (b.dataset.cab || '') + '" has been received. We will confirm shortly.'
        );
        document.getElementById('cm-wa-btn').href = waNum
            ? 'https://wa.me/' + (waNum.startsWith('91') ? waNum : '91' + waNum) + '?text=' + waMsg
            : '#';

        cmData = {
            name: b.dataset.name, phone: b.dataset.phone,
            cab: b.dataset.cab, pickup: b.dataset.pickup,
            triptype: b.dataset.triptype, pickuploc: b.dataset.pickuploc,
            persons: b.dataset.persons, luggage: b.dataset.luggage,
            roof: b.dataset.roof,
            price: showPrice > 0 ? '₹' + showPrice.toLocaleString('en-IN') : 'On request',
            note: b.dataset.note, submitted: b.dataset.submitted
        };

        feather.replace();
    });

    function buildCabLeadText(d) {
        return [
            '🚗 CAB BOOKING LEAD',
            '──────────────────────',
            '👤 Name          : ' + (d.name      || '—'),
            '📞 Phone         : ' + (d.phone     || '—'),
            '──────────────────────',
            '🚗 Cab           : ' + (d.cab       || '—'),
            '💰 Price         : ' + (d.price     || '—'),
            '──────────────────────',
            '📅 Pickup Date   : ' + (d.pickup    || '—'),
            '🛣️  Trip Type     : ' + (d.triptype  || '—'),
            '📍 Pickup Loc.   : ' + (d.pickuploc || '—'),
            '👥 Persons       : ' + (d.persons   || '—'),
            '🧳 Luggage       : ' + (d.luggage   || '0') + ' bag(s)',
            '📦 Roof Carrier  : ' + (d.roof      || '—'),
            (d.note ? '📝 Note          : ' + d.note : ''),
            '──────────────────────',
            '🕐 Received      : ' + (d.submitted || '—'),
        ].filter(Boolean).join('\n');
    }

    function doCabCopy(text, btn) {
        navigator.clipboard.writeText(text).then(function () {
            var orig = btn.innerHTML;
            btn.innerHTML = '<i data-feather="check" style="width:13px;height:13px;"></i> Copied!';
            btn.classList.add('copied');
            feather.replace();
            setTimeout(function () { btn.innerHTML = orig; btn.classList.remove('copied'); feather.replace(); }, 2000);
        });
    }

    document.getElementById('cm-copy-btn').addEventListener('click', function () {
        doCabCopy(buildCabLeadText(cmData), this);
    });
    document.getElementById('cm-copy-footer-btn').addEventListener('click', function () {
        doCabCopy(buildCabLeadText(cmData), this);
    });
}

// ── Copy Lead — cab table row buttons ──────────────────────
document.querySelectorAll('.cab-copy-lead-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        var text = [
            '🚗 CAB BOOKING LEAD',
            '──────────────────────',
            '👤 Name          : ' + (d.name      || '—'),
            '📞 Phone         : ' + (d.phone     || '—'),
            '──────────────────────',
            '🚗 Cab           : ' + (d.cab       || '—'),
            '💰 Price         : ' + (d.price     || '—'),
            '──────────────────────',
            '📅 Pickup Date   : ' + (d.pickup    || '—'),
            '🛣️  Trip Type     : ' + (d.triptype  || '—'),
            '📍 Pickup Loc.   : ' + (d.pickuploc || '—'),
            '👥 Persons       : ' + (d.persons   || '—'),
            '🧳 Luggage       : ' + (d.luggage   || '0') + ' bag(s)',
            '📦 Roof Carrier  : ' + (d.roof      || '—'),
            (d.note ? '📝 Note          : ' + d.note : ''),
        ].filter(Boolean).join('\n');

        navigator.clipboard.writeText(text).then(() => {
            var orig = this.innerHTML;
            this.innerHTML = '<i data-feather="check" style="width:13px;height:13px;"></i>';
            this.classList.add('copied');
            feather.replace();
            setTimeout(() => { this.innerHTML = orig; this.classList.remove('copied'); feather.replace(); }, 2000);
        });
    });
});

// Hotel Enquiry Modal
var hmModal = document.getElementById('viewHotelEnqModal');
if (hmModal) {
    hmModal.addEventListener('show.bs.modal', function (e) {
        var b = e.relatedTarget;
        document.getElementById('hm-hotel').textContent         = b.dataset.hotel     || '—';
        document.getElementById('hm-guest').textContent         = b.dataset.guest     || '—';
        document.getElementById('hm-phone').textContent         = b.dataset.phone     || '—';
        document.getElementById('hm-adults').textContent        = b.dataset.adults    || '—';
        document.getElementById('hm-kids').textContent          = b.dataset.kids > 0 ? b.dataset.kids : '0';
        document.getElementById('hm-checkin').textContent       = b.dataset.checkin   || '—';
        document.getElementById('hm-checkout').textContent      = b.dataset.checkout  || '—';
        document.getElementById('hm-nights').textContent        = b.dataset.nights ? b.dataset.nights + ' Night(s)' : '—';
        document.getElementById('hm-submitted-field').textContent = b.dataset.submitted || '—';
        document.getElementById('hm-submitted').textContent     = 'Received: ' + (b.dataset.submitted || '');
    });
}

document.getElementById('viewEnqModal').addEventListener('show.bs.modal', function (e) {
    var btn = e.relatedTarget;
    var catKey    = btn.dataset.catkey    || 'tour';
    var catLabel  = btn.dataset.catlabel  || '';
    var name      = btn.dataset.name      || '—';
    var phone     = btn.dataset.phone     || '—';
    var persons   = btn.dataset.persons   || '';
    var arrival   = btn.dataset.arrival   || '';
    var checkin   = btn.dataset.checkin   || '';
    var checkout  = btn.dataset.checkout  || '';
    var pkg       = btn.dataset.package   || '—';
    var message   = btn.dataset.message   || '—';
    var submitted = btn.dataset.submitted || '';

    // Header colour
    var hdr = document.getElementById('modal-header');
    hdr.className = 'modal-header h-' + catKey;

    // Title
    var icons = { hotel:'🏨', cab:'🚗', boat:'⛵', tour:'🗺️' };
    document.getElementById('modal-title').textContent = (icons[catKey]||'') + ' Enquiry Details';
    document.getElementById('modal-submitted').textContent = '';
    document.getElementById('modal-submitted-field').textContent = submitted || '—';

    // Fields
    document.getElementById('modal-name').textContent     = name;
    document.getElementById('modal-phone').textContent    = phone;
    document.getElementById('modal-package').textContent  = pkg;
    document.getElementById('modal-category').textContent = catLabel;
    document.getElementById('modal-message').textContent  = message;

    // Dynamic trip section
    var tripHtml = '';
    if (catKey === 'hotel') {
        tripHtml += field('Check-In Date & Time', checkin || '—');
        tripHtml += field('Check-Out Date & Time', checkout || '—');
        tripHtml += field('No. of Persons', persons || '—');
    } else if (catKey === 'cab') {
        tripHtml += field('Arrival / Pickup Date', arrival || '—');
        tripHtml += field('No. of Persons', persons || '—');
    } else if (catKey === 'boat') {
        tripHtml += field('Ride Date', arrival || '—');
        tripHtml += field('No. of Persons', persons || '—');
    } else {
        tripHtml += field('Arrival Date', arrival || '—');
        tripHtml += field('No. of Persons', persons || '—');
    }
    document.getElementById('modal-trip-section').innerHTML = tripHtml;

    feather.replace();
});

function field(label, value) {
    return '<div class="col-md-4"><div class="field-label">' + label + '</div>'
         + '<div class="field-value">' + (value || '—') + '</div></div>';
}

// ── Boat Enquiry Modal ──────────────────────────────────────
var bmData = {};
var bmModal = document.getElementById('viewBoatEnqModal');
if (bmModal) {
    bmModal.addEventListener('show.bs.modal', function (e) {
        var b = e.relatedTarget;
        var basePrice = parseFloat(b.dataset.baseprice) || 0;
        var discPrice = parseFloat(b.dataset.discprice) || 0;
        var showPrice = discPrice > 0 ? discPrice : basePrice;
        var persons   = parseInt(b.dataset.persons) || 0;
        var estTotal  = showPrice > 0 ? showPrice : 0;

        // price display
        var priceHtml = showPrice > 0
            ? (discPrice > 0 && basePrice > discPrice
                ? '<span style="text-decoration:line-through;color:#aaa;font-size:.82rem;font-weight:400;">₹' + basePrice.toLocaleString('en-IN') + '</span> ₹' + discPrice.toLocaleString('en-IN')
                : '₹' + showPrice.toLocaleString('en-IN'))
            : '—';

        document.getElementById('bm-name').textContent         = b.dataset.name      || '—';
        document.getElementById('bm-phone').textContent        = b.dataset.phone     || '—';
        document.getElementById('bm-whatsapp').textContent     = b.dataset.whatsapp  || b.dataset.phone || '—';
        document.getElementById('bm-boat').textContent         = b.dataset.boat      || '—';
        document.getElementById('bm-price').innerHTML          = priceHtml;
        document.getElementById('bm-date').textContent         = b.dataset.date      || '—';
        document.getElementById('bm-slot').textContent         = b.dataset.slot      || '—';
        document.getElementById('bm-ghat').textContent         = b.dataset.ghat      || '—';
        document.getElementById('bm-persons').textContent      = b.dataset.persons   || '—';
        document.getElementById('bm-children').textContent     = b.dataset.children  || '0';
        document.getElementById('bm-submitted-field').textContent = b.dataset.submitted || '—';
        document.getElementById('bm-submitted').textContent    = 'Received: ' + (b.dataset.submitted || '');
        document.getElementById('bm-note').textContent         = b.dataset.note      || '—';
        document.getElementById('bm-est-price').textContent    = estTotal > 0 ? '₹' + estTotal.toLocaleString('en-IN') : '—';

        // WhatsApp link
        var waNum = (b.dataset.whatsapp || b.dataset.phone || '').replace(/\D/g, '');
        var waMsg = encodeURIComponent(
            'Hi ' + (b.dataset.name || '') + ', your boat booking enquiry for "' +
            (b.dataset.boat || '') + '" on ' + (b.dataset.date || '') + ' has been received. We will confirm shortly.'
        );
        document.getElementById('bm-wa-btn').href = waNum ? 'https://wa.me/' + (waNum.startsWith('91') ? waNum : '91' + waNum) + '?text=' + waMsg : '#';

        // Store for copy
        bmData = {
            name: b.dataset.name, phone: b.dataset.phone,
            whatsapp: b.dataset.whatsapp, boat: b.dataset.boat,
            date: b.dataset.date, slot: b.dataset.slot,
            ghat: b.dataset.ghat, persons: b.dataset.persons,
            children: b.dataset.children,
            price: showPrice > 0 ? '₹' + showPrice.toLocaleString('en-IN') : 'On request',
            note: b.dataset.note, submitted: b.dataset.submitted
        };

        feather.replace();
    });

    function buildBoatLeadText(d) {
        return [
            '⛵ BOAT ENQUIRY LEAD',
            '──────────────────────',
            '👤 Name     : ' + (d.name || '—'),
            '📞 Phone    : ' + (d.phone || '—'),
            '💬 WhatsApp : ' + (d.whatsapp || d.phone || '—'),
            '──────────────────────',
            '🚢 Boat     : ' + (d.boat || '—'),
            '📅 Date     : ' + (d.date || '—'),
            '🌅 Slot     : ' + (d.slot || '—'),
            '📍 Ghat     : ' + (d.ghat || '—'),
            '👥 Persons  : ' + (d.persons || '—'),
            '👶 Children : ' + (d.children || '0'),
            '💰 Price    : ' + (d.price || '—'),
            (d.note ? '📝 Note     : ' + d.note : ''),
            '──────────────────────',
            '🕐 Received : ' + (d.submitted || '—'),
        ].filter(Boolean).join('\n');
    }

    function doCopy(text, btn) {
        navigator.clipboard.writeText(text).then(function () {
            var orig = btn.innerHTML;
            btn.innerHTML = '<i data-feather="check" style="width:13px;height:13px;"></i> Copied!';
            btn.classList.add('copied');
            feather.replace();
            setTimeout(function () { btn.innerHTML = orig; btn.classList.remove('copied'); feather.replace(); }, 2000);
        });
    }

    document.getElementById('bm-copy-btn').addEventListener('click', function () {
        doCopy(buildBoatLeadText(bmData), this);
    });
    document.getElementById('bm-copy-footer-btn').addEventListener('click', function () {
        doCopy(buildBoatLeadText(bmData), this);
    });
}

// ── Copy Lead — table row buttons ──────────────────────────
document.querySelectorAll('.copy-lead-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        var text = [
            '⛵ BOAT ENQUIRY LEAD',
            '──────────────────────',
            '👤 Name     : ' + (d.name || '—'),
            '📞 Phone    : ' + (d.phone || '—'),
            '💬 WhatsApp : ' + (d.whatsapp || d.phone || '—'),
            '──────────────────────',
            '🚢 Boat     : ' + (d.boat || '—'),
            '📅 Date     : ' + (d.date || '—'),
            '🌅 Slot     : ' + (d.slot || '—'),
            '📍 Ghat     : ' + (d.ghat || '—'),
            '👥 Persons  : ' + (d.persons || '—'),
            '👶 Children : ' + (d.children || '0'),
            '💰 Price    : ' + (d.price || '—'),
            (d.note ? '📝 Note     : ' + d.note : ''),
        ].filter(Boolean).join('\n');

        navigator.clipboard.writeText(text).then(() => {
            var orig = this.innerHTML;
            this.innerHTML = '<i data-feather="check" style="width:13px;height:13px;"></i>';
            this.classList.add('copied');
            feather.replace();
            setTimeout(() => { this.innerHTML = orig; this.classList.remove('copied'); feather.replace(); }, 2000);
        });
    });
});

// All-tab copy lead
document.querySelectorAll('.all-copy-lead-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.dataset;
        var text = [
            '📋 ENQUIRY LEAD — ' + (d.category || '').toUpperCase(),
            '──────────────────────',
            '👤 Name     : ' + (d.name || '—'),
            '📞 Phone    : ' + (d.phone || '—'),
            '──────────────────────',
            '📦 Package  : ' + (d.package || '—'),
            '📅 Arrival  : ' + (d.arrival || '—'),
            '👥 Persons  : ' + (d.persons || '—'),
            (d.message ? '📝 Message  : ' + d.message : ''),
        ].filter(Boolean).join('\n');
        navigator.clipboard.writeText(text).then(() => {
            var orig = this.innerHTML;
            this.innerHTML = '<i data-feather="check" style="width:13px;height:13px;"></i>';
            this.classList.add('copied');
            feather.replace();
            setTimeout(() => { this.innerHTML = orig; this.classList.remove('copied'); feather.replace(); }, 2000);
        });
    });
});

// Delete confirmation
document.querySelectorAll('.delete-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Delete this enquiry? This cannot be undone.')) this.submit();
    });
});
</script>
@endpush

@endsection
