<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Address</th>
            @if(Auth::guard('admin')->user()->id == 1)
                <th>Added By</th>
            @endif
            <th>Service</th>
            <th>Is Actice</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($agents as $key=>$agent)
            <tr>
                <td>{{ $key + 1 + ($agents->currentPage() - 1) * $agents->perPage() }}</td>
                <td><b>Name :</b>{{ $agent->name }}
                    <br>
                    <b>Contact :</b>{{ $agent->contact_number }} <br>
                    <b>Alt Contact :</b>{{ $agent->alt_contact_number }}
                </td>
                <td>
                    {{ $agent->state }}
                </td>
                @if(Auth::guard('admin')->user()->id == 1)
                    <td>{{ $agent->admin->name }}</td>
                @endif
                <td>
                    @php
                        $services = App\Models\AgentService::whereIn('id', $agent->service_ids??[])->get();
                    @endphp
                    @foreach ($services as $service)
                        {{ $service->name }} @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>
                    @if ($agent->is_active == '1')
                        <a href="{{route('agent.show',$agent->id)}}?status=0">
                            <span class="badge bg-success">Active</span>
                        </a>
                    @else
                        <a href="{{route('agent.show',$agent->id)}}?status=1">
                            <span class="badge bg-danger">Inactive</span>
                        </a>
                    @endif
                </td>
                <td>
                    <x-edit-btn route="{{ route('agent.edit', $agent->id) }}" />
                    <x-delete-btn route="{{ route('agent.destroy', $agent->id) }}" />
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center text-danger">
                    <i class="link-icon" data-feather="frown" style="width: 50px; height:50px;"></i><br>
                    Opps!! There Are No Data Found..
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<hr>
<div class="row">
    <div class="col-md-12 d-flex justify-content-end">
        {!! $agents->appends(['search_staff' => $search_staff, 'search_service' => $search_service, 'search_key' => $search_key,'search_state'=>$search_state])->links() !!}
    </div>
</div>
