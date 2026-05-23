@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-6"><h4>{{$page_title}}</h4></div>
            <div class="col-md-6" style="padding-bottom:10px">
                @can('package-create')
                <x-add-btn route="{{route('product.create')}}" />
                @endcan
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{route('product.index')}}">
                            <div class="row">
                                <div class="col-3 card-title"></div>
                                <div class="col-3 card-title">
                                    <div class="input-group">
                                        <select name="search_category" id="search_category" class="form-control  js-example-basic-single">
                                            <option value="">Select Category...</option>
                                                @foreach (App\Models\Admin\Category::all() as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 card-title">
                                    <div class="input-group">
                                        <select name="search_sub_category" id="search_sub_category" class="form-control  js-example-basic-single">
                                            <option value="">Select Sub Category...</option>
                                            @foreach (App\Models\Admin\SubCategory::all() as $sub_category)
                                                <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 card-title">
                                    <div class="input-group">
                                        <input type="text" name="search_key" class="form-control form-control-sm" placeholder="Search" value="{{$search_key}}" data-input>
                                        <button type="submit" class="input-group-text input-group-addon" data-toggle><i data-feather="search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                        <th>On Home</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($list as $key=>$data)
                                        <tr>
                                            <td>{{ $key + 1 + ($list->currentPage() - 1) * $list->perPage() }}</td>
                                            <td>{{$data->category->name}}</td>
                                            <td>{{optional($data->subCategory)->name}}</td>
                                            <td>{{$data->name}}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input on_home" name="on_home" value="{{$data->id}}" @if($data->on_home == '1') checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input status_update" name="is_active" value="{{ $data->id }}" @if($data->is_active == 'active') checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                @can('package-edit')
                                                <x-edit-btn route="{{ route('product.edit', $data->id) }}" />
                                                @endcan
                                                @can('package-delete')
                                                <x-delete-btn route="{{ route('product.destroy', $data->id) }}" />
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
                            <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><b>Showing {{($list->currentpage()-1)*$list->perpage()+1}} to {{(($list->currentpage()-1)*$list->perpage())+$list->count()}} of {{$list->total()}} Products</b></p>
                                    </div>
                                    <div class="col-md-8 d-flex justify-content-end">
                                        {!! $list->appends(['search_key'=>$search_key])->links() !!}
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".status_update").change(function() {
            var id = $(this).val();
            $.get("{{ route('product.statusUpdate', '') }}/" + id, function(data) {

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

        $(".on_home").change(function() {
            var id = $(this).val();
            $.get("{{ route('admin.product.on.home.status.update', '') }}/" + id, function(data) {

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
