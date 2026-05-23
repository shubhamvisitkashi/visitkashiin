@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 card-title"><h4>{{$page_title}}</h4></div>
                            <div class="col-6 text-end">
                                <x-add-btn route="{{route('package.create')}}" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Subcategory</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i= 1;
                                    @endphp
                                    @forelse ($list as $data)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$data->category->name}}</td>
                                            <td>{{$data->subCategory->name}}</td>
                                            <td>{{$data->name}}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input status_update" name="is_active" value="{{ $data->id }}" @if($data->is_active == 'active') checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                <x-edit-btn route="{{ route('package.edit', $data->id) }}" />
                                                <x-delete-btn route="{{ route('package.destroy', $data->id) }}" />
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
        </div>
    </div>
    <script>
        $(".status_update").change(function() {
            var id = $(this).val();
            $.get("{{ route('package.statusUpdate', '') }}/" + id, function(data) {

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
