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
        @forelse ($vendors as $key=>$vendor)
            <tr>
                <td>{{ $key + 1 + ($vendors->currentPage() - 1) * $vendors->perPage() }}</td>
                <td><b>Name :</b>{{ $vendor->name }}
                    <br>
                    <b>Contact :</b>{{ $vendor->contact_number }} <br>
                    <b>Alt Contact :</b>{{ $vendor->alt_contact_number }}
                </td>
                <td>
                    {{ $vendor->state }}
                </td>
                @if(Auth::guard('admin')->user()->id == 1)
                    <td>{{ $vendor->admin->name }}</td>
                @endif
                <td>
                    @php
                        $services = App\Models\VendorService::whereIn('id', $vendor->service_ids??[])->get();
                    @endphp
                    @foreach ($services as $service)
                        {{ $service->name }} @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>
                    @if ($vendor->is_active == '1')
                        <a href="{{route('vendor.show',$vendor->id)}}?status=0">
                            <span class="badge bg-success">Active</span>
                        </a>
                    @else
                        <a href="{{route('vendor.show',$vendor->id)}}?status=1">
                            <span class="badge bg-danger">Inactive</span>
                        </a>
                    @endif
                </td>
                <td>
                    <x-edit-btn route="{{ route('vendor.edit', $vendor->id) }}" />
                    <x-delete-btn route="{{ route('vendor.destroy', $vendor->id) }}" />
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
        {!! $vendors->appends(['search_staff' => $search_staff, 'search_service' => $search_service, 'search_key' => $search_key,'search_state'=>$search_state])->links() !!}
    </div>
</div>
