@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 card-title"><h4>{{$page_title}} LIST</h4></div>
                            <div class="col-6 text-end">
                                <form action="{{route('sub-category.index')}}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search" value="@isset($search){{$search}}@endisset" data-input>
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
                                        <th>Category</th>
                                        <th>Name</th>
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
                                            <td>{{$data->name}}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input status_update" name="is_active" value="{{ $data->id }}" @if($data->is_active == 'active') checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                @can('sub_category-edit')
                                                <x-edit-btn route="{{ route('sub-category.edit', $data->id) }}" />
                                                @endcan
                                                @can('sub_category-delete')
                                                <x-delete-btn route="{{ route('sub-category.destroy', $data->id) }}" />
                                                @endcan
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        @isset($edit_data)
                            <h6 class="card-title">Update {{$page_title}}</h6>
                            <form id="valid_form" method="POST" action="{{route('sub-category.update',$edit_data->id)}}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <h6 class="card-title">Create {{$page_title}}</h6>
                            <form id="valid_form" method="POST" action="{{route('sub-category.store')}}" enctype="multipart/form-data">
                        @endisset
                            @csrf
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control js-example-basic-single" name="category_id" id="category_id" required>
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($category_list as $category_data)
                                        <option value="{{$category_data->id}}" @if(isset($edit_data) && $category_data->id == $edit_data->category_id) selected @endif>{{$category_data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" class="form-control" type="text" name="name" @isset($edit_data)value="{{$edit_data->name}}" @endisset required placeholder="Enter Category Name">
                            </div>
                            <hr>
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input id="meta_title" class="form-control" type="text" name="meta_title" placeholder="Enter Meta Title" @isset($edit_data)value="{{$edit_data->meta_title}}"@endisset>
                                </div>
                                <div class="mb-3">
                                    <label for="meta_keyword" class="form-label">Meta Keywords</label>
                                    <input id="meta_keyword" class="form-control" type="text" name="meta_keyword" placeholder="Enter Meta Keyword" @isset($edit_data)value="{{$edit_data->meta_keyword}}"@endisset>
                                </div>
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" id="meta_description" rows="2" placeholder="Enter Meta description">@isset($edit_data){{$edit_data->meta_description}}@endisset</textarea>
                                </div>

                            @isset($edit_data) <x-save-btn text="Update" /> @else <x-save-btn text="Save" /> @endisset
                            @isset($edit_data)
                                <x-cancle-btn text="Cancel" route="{{route('sub-category.index')}}" />
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
            $.get("{{ route('sub-category.show', '') }}/" + id, function(data) {

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
