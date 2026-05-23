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
                                <form action="{{route('lead-source.index')}}" method="GET">
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
                                        <th>Phone</th>
                                       <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i= 1;
                                    @endphp
                                    @forelse ($lead_sources as $lead_source)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$lead_source->name}}</td>
                                            <td>{{$lead_source->phone}}</td>

                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input status_update" name="is_active" value="{{ $lead_source->id }}" @if($lead_source->is_active == '1') checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                <x-edit-btn route="{{ route('lead-source.edit', $lead_source->id) }}" />
                                                <x-delete-btn route="{{ route('lead-source.destroy', $lead_source->id) }}" />
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
                        @isset($edit_lead_source)
                            <h6 class="card-title">Update {{$page_title}}</h6>
                            <form id="valid_form" method="POST" action="{{route('lead-source.update',$edit_lead_source->id)}}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <h6 class="card-title">Create {{$page_title}}</h6>
                            <form id="valid_form" method="POST" action="{{route('lead-source.store')}}" enctype="multipart/form-data">
                        @endisset
                            @csrf
                            <div class="mb-2">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" class="form-control" type="text" name="name" @isset($edit_lead_source)value="{{$edit_lead_source->name}}" @endisset placeholder="Enter Name">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="phone" class="form-label">Phone</label>
                                <input id="phone" class="form-control" type="text" name="phone" @isset($edit_lead_source)value="{{$edit_lead_source->phone}}" @endisset placeholder="Enter Number">
                                @error('phone')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            @isset($edit_lead_source) <x-save-btn text="Update" /> @else <x-save-btn text="Save" /> @endisset
                            @isset($edit_lead_source)
                                <x-cancle-btn text="Cancel" route="{{route('vendor.index')}}" />
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
            $.get("{{ route('lead-source.show', '') }}/" + id, function(data) {
                console.log(data);

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
