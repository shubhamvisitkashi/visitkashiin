@php
$svcColors = [
    0  => ['bg'=>'#EFF6FF','color'=>'#1D4ED8','border'=>'#BFDBFE'],
    1  => ['bg'=>'#F0FDF4','color'=>'#16A34A','border'=>'#BBF7D0'],
    2  => ['bg'=>'#FFF7ED','color'=>'#C2410C','border'=>'#FED7AA'],
    3  => ['bg'=>'#F5F3FF','color'=>'#6D28D9','border'=>'#DDD6FE'],
    4  => ['bg'=>'#FFFBEB','color'=>'#B45309','border'=>'#FDE68A'],
    5  => ['bg'=>'#FFF1F2','color'=>'#BE123C','border'=>'#FECDD3'],
];
@endphp
<table class="vn-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Vendor</th>
            <th>Location</th>
            @if(Auth::guard('admin')->user()->id == 1)
            <th>Added By</th>
            @endif
            <th>Services</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vendors as $key => $vendor)
        @php
            $initial  = strtoupper(substr($vendor->name, 0, 2));
            $services = App\Models\VendorService::whereIn('id', $vendor->service_ids ?? [])->get();
        @endphp
        <tr>
            {{-- # --}}
            <td style="color:#94A3B8;font-size:.78rem;font-weight:600;">
                {{ $key + 1 + ($vendors->currentPage() - 1) * $vendors->perPage() }}
            </td>

            {{-- Vendor name + contacts --}}
            <td>
                <div class="vn-name-cell">
                    <div class="vn-av">{{ $initial }}</div>
                    <div>
                        <div class="vn-name">{{ $vendor->name }}</div>
                        <div class="vn-phone">
                            📞 {{ $vendor->contact_number }}
                            @if($vendor->alt_contact_number)
                                &nbsp;·&nbsp; {{ $vendor->alt_contact_number }}
                            @endif
                        </div>
                    </div>
                </div>
            </td>

            {{-- Location --}}
            <td>
                <div class="vn-loc">{{ $vendor->state ?: '—' }}</div>
                @if($vendor->city)
                <div class="vn-city">{{ $vendor->city }}</div>
                @endif
            </td>

            {{-- Added by (admin only) --}}
            @if(Auth::guard('admin')->user()->id == 1)
            <td>
                @if($vendor->admin)
                <span class="vn-addedby">
                    {{ $vendor->admin->name }}
                </span>
                @else
                <span style="color:#94A3B8;font-size:.75rem;">—</span>
                @endif
            </td>
            @endif

            {{-- Services --}}
            <td>
                <div class="vn-svc">
                    @forelse($services as $si => $svc)
                    @php $sc = $svcColors[$si % count($svcColors)]; @endphp
                    <span class="vn-svc-badge"
                        style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border:1px solid {{ $sc['border'] }};">
                        {{ $svc->name }}
                    </span>
                    @empty
                    <span style="color:#94A3B8;font-size:.75rem;">—</span>
                    @endforelse
                </div>
            </td>

            {{-- Status --}}
            <td class="text-center">
                @if($vendor->is_active == '1')
                <a href="{{ route('vendor.show', $vendor->id) }}?status=0" class="vn-status-on"
                   onclick="return confirm('Set {{ addslashes($vendor->name) }} as Inactive?')">
                   ● Active
                </a>
                @else
                <a href="{{ route('vendor.show', $vendor->id) }}?status=1" class="vn-status-off"
                   onclick="return confirm('Set {{ addslashes($vendor->name) }} as Active?')">
                   ○ Inactive
                </a>
                @endif
            </td>

            {{-- Actions --}}
            <td>
                <div class="vn-actions">
                    <a href="{{ route('vendor.edit', $vendor->id) }}" class="vn-act vn-act-edit" title="Edit">
                        <i data-feather="edit-2"></i>
                    </a>
                    <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST"
                          id="vdel_{{ $vendor->id }}" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="button" class="vn-act vn-act-del" title="Delete"
                            onclick="delVendor('{{ $vendor->id }}','{{ addslashes($vendor->name) }}')">
                            <i data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ Auth::guard('admin')->user()->id == 1 ? 7 : 6 }}">
                <div class="vn-empty">
                    <i data-feather="briefcase"></i>
                    <p style="font-weight:700;color:#374151;margin:0 0 5px;">No vendors found</p>
                    <p style="font-size:.82rem;margin:0;">Try adjusting filters or add a new vendor</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($vendors->hasPages())
<div class="vn-pag">
    <div class="vn-pag-info">
        Showing {{ $vendors->firstItem() }}–{{ $vendors->lastItem() }} of {{ $vendors->total() }} vendors
    </div>
    {!! $vendors->appends([
        'search_staff'   => $search_staff,
        'search_service' => $search_service,
        'search_key'     => $search_key,
        'search_state'   => $search_state
    ])->links() !!}
</div>
@endif
