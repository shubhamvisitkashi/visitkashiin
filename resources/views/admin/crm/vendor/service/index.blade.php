@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 card-title"><h4>{{$page_title}} LIST</h4></div>
                            <div class="col-6 text-end">
                                <form action="{{route('vendor-service.index')}}" id="search_form">
                                    <div class="input-group">
                                        <input type="text" name="search_key" class="form-control form-control-sm" placeholder="Search" value="@isset($search_key){{$search_key}}@endisset" data-input>
                                        <button type="submit" class="input-group-text input-group-addon" data-toggle><i data-feather="search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vendor_services as $key=>$vendor_service)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$vendor_service->name}}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input status_update" name="is_active" value="{{ $vendor_service->id }}" @if($vendor_service->is_active == '1') checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                <x-edit-btn route="{{ route('vendor-service.edit', $vendor_service->id) }}" />
                                                <x-delete-btn route="{{ route('vendor-service.destroy', $vendor_service->id) }}" />
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @isset($edit_vendor_service)
                            <h6 class="card-title">Update {{$page_title}}</h6>
                            <form id="valid_form" method="POST" action="{{route('vendor-service.update',$edit_vendor_service->id)}}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <h6 class="card-title">Create {{$page_title}}</h6>
                            <form id="valid_form" method="POST" action="{{route('vendor-service.store')}}" enctype="multipart/form-data">
                        @endisset
                            @csrf
                            <div class="mb-2">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" class="form-control" type="text" name="name" @isset($edit_vendor_service)value="{{$edit_vendor_service->name}}" @endisset placeholder="Enter Name">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            @isset($edit_vendor_service) <x-save-btn text="Update" /> @else <x-save-btn text="Save" /> @endisset
                            @isset($edit_vendor_service)
                                <x-cancle-btn text="Cancel" route="{{route('vendor-service.index')}}" />
                            @endisset
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(".status_update").change(function() {
            var id = $(this).val();
            $.get("{{ route('vendor-service.show', '') }}/" + id, function(data) {

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                Toast.fire({
                    icon: data.res,
                    title: data.message,
                });
            });
        });
    </script>
@endsection
