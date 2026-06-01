@php
$badgeColors = [
    ['bg'=>'#F0FDF4','color'=>'#16A34A','border'=>'#BBF7D0'],
    ['bg'=>'#EFF6FF','color'=>'#1D4ED8','border'=>'#BFDBFE'],
    ['bg'=>'#F5F3FF','color'=>'#6D28D9','border'=>'#DDD6FE'],
    ['bg'=>'#FFF7ED','color'=>'#C2410C','border'=>'#FED7AA'],
    ['bg'=>'#FFFBEB','color'=>'#B45309','border'=>'#FDE68A'],
    ['bg'=>'#FFF1F2','color'=>'#BE123C','border'=>'#FECDD3'],
];
$isAdmin = Auth::guard('admin')->user()->id == 1;
@endphp

<table class="ag-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Agent</th>
            <th>Location</th>
            @if($isAdmin)<th>Added By</th>@endif
            <th>Services</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($agents as $key => $agent)
        @php
            $initial  = strtoupper(substr($agent->name, 0, 2));
            $services = App\Models\AgentService::whereIn('id', $agent->service_ids ?? [])->get();
        @endphp
        <tr>
            {{-- # --}}
            <td style="color:#94A3B8;font-size:.78rem;font-weight:600;">
                {{ $key + 1 + ($agents->currentPage() - 1) * $agents->perPage() }}
            </td>

            {{-- Agent name + contact --}}
            <td>
                <div class="ag-name-cell">
                    <div class="ag-av">{{ $initial }}</div>
                    <div>
                        <div class="ag-name">{{ $agent->name }}</div>
                        <div class="ag-phone">
                            📞 {{ $agent->contact_number }}
                            @if($agent->alt_contact_number)
                                &nbsp;·&nbsp; {{ $agent->alt_contact_number }}
                            @endif
                        </div>
                    </div>
                </div>
            </td>

            {{-- Location --}}
            <td>
                <div class="ag-loc">{{ $agent->state ?: '—' }}</div>
                @if($agent->city)
                <div class="ag-city">{{ $agent->city }}</div>
                @endif
            </td>

            {{-- Added By (admin only) --}}
            @if($isAdmin)
            <td>
                @if($agent->admin)
                    <span class="ag-by">{{ $agent->admin->name }}</span>
                @else
                    <span style="color:#94A3B8;font-size:.75rem;">—</span>
                @endif
            </td>
            @endif

            {{-- Services --}}
            <td>
                <div class="ag-svc">
                    @forelse($services as $si => $svc)
                    @php $bc = $badgeColors[$si % count($badgeColors)]; @endphp
                    <span class="ag-svc-badge"
                        style="background:{{ $bc['bg'] }};color:{{ $bc['color'] }};border:1px solid {{ $bc['border'] }};">
                        {{ $svc->name }}
                    </span>
                    @empty
                    <span style="color:#94A3B8;font-size:.75rem;">—</span>
                    @endforelse
                </div>
            </td>

            {{-- Status --}}
            <td class="text-center">
                @if($agent->is_active == '1')
                <a href="{{ route('agent.show', $agent->id) }}?status=0" class="ag-on"
                   onclick="return confirm('Set {{ addslashes($agent->name) }} as Inactive?')">
                   ● Active
                </a>
                @else
                <a href="{{ route('agent.show', $agent->id) }}?status=1" class="ag-off"
                   onclick="return confirm('Set {{ addslashes($agent->name) }} as Active?')">
                   ○ Inactive
                </a>
                @endif
            </td>

            {{-- Actions --}}
            <td>
                <div class="ag-actions">
                    <a href="{{ route('agent.edit', $agent->id) }}" class="ag-act ag-act-edit" title="Edit">
                        <i data-feather="edit-2"></i>
                    </a>
                    <form action="{{ route('agent.destroy', $agent->id) }}" method="POST"
                          id="adel_{{ $agent->id }}" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="button" class="ag-act ag-act-del" title="Delete"
                            onclick="delAgent('{{ $agent->id }}','{{ addslashes($agent->name) }}')">
                            <i data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ $isAdmin ? 7 : 6 }}">
                <div class="ag-empty">
                    <i data-feather="user-x"></i>
                    <p style="font-weight:700;color:#374151;margin:0 0 5px;">No agents found</p>
                    <p style="font-size:.82rem;margin:0;">Adjust your filters or add a new agent</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($agents->hasPages())
<div class="ag-pag">
    <div class="ag-pag-info">
        Showing {{ $agents->firstItem() }}–{{ $agents->lastItem() }} of {{ $agents->total() }} agents
    </div>
    {!! $agents->appends([
        'search_staff'   => $search_staff,
        'search_service' => $search_service,
        'search_key'     => $search_key,
        'search_state'   => $search_state,
    ])->links() !!}
</div>
@endif
