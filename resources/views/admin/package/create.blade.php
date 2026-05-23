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
                                <x-cancle-btn route="{{route('package.index')}}" text="Back"/>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @isset($edit_data)
                            <form id="valid_form" method="POST" action="{{route('package.update', $edit_data->id)}}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <form id="valid_form" method="POST" action="{{route('package.store')}}" enctype="multipart/form-data">
                        @endisset
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-control js-example-basic-single" name="category_id" id="category_id" onchange="getSubCategory()" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($category_list as $category_data)
                                            <option value="{{$category_data->id}}" @if(isset($edit_data) && $category_data->id == $edit_data->category_id) selected @endif>{{$category_data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subcategory_id" class="form-label">Sub Category</label>
                                    <select class="form-control js-example-basic-single" name="subcategory_id" id="subcategory_id" required>
                                        <option value="" selected disabled>Select Sub Category</option>

                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Title</label>
                                    <input id="name" class="form-control" type="text" name="name" placeholder="Enter Title" @isset($edit_data)value="{{$edit_data->name}}"@endisset required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="images" class="form-label">Images</label>
                                    <div class="input-group">
                                        <input id="images" class="form-control" type="file" name="images[]" accept="image/*" @isset($edit_data) @else required @endisset>
                                        <span class="input-group-text input-group-addon btn btn-outline-success add_image_field" data-toggle=""><i class="btn-icon-prepend" data-feather="plus-circle"></i></span>
                                    </div>
                                    <div class="image_fields"></div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="editor" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="editor" rows="2" placeholder="Enter description" required>@isset($edit_data){{$edit_data->description}}@endisset</textarea>
                                </div>
                                <hr>
                                <div class="col-md-6 mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input id="meta_title" class="form-control" type="text" name="meta_title" placeholder="Enter Meta Title" @isset($edit_data)value="{{$edit_data->meta_title}}"@endisset>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meta_keyword" class="form-label">Meta Keywords</label>
                                    <input id="meta_keyword" class="form-control" type="text" name="meta_keyword" placeholder="Enter Meta Keyword" @isset($edit_data)value="{{$edit_data->meta_keyword}}"@endisset>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" id="meta_description" rows="2" placeholder="Enter Meta description">@isset($edit_data){{$edit_data->meta_description}}@endisset</textarea>
                                </div>
                            </div>
                            @isset($edit_data)<x-save-btn text="Update"/> @else<x-save-btn text="Save"/> @endif
                        </form>
                        <div class="clone hide" style="display:none">
                            <div class="imageDivField control-group lst input-group mt-3">
                                <div class="input-group">
                                    <input id="images" class="form-control" type="file" name="images[]" accept="image/*" required>
                                    <span class="input-group-text input-group-addon btn btn-outline-danger danger1" data-toggle=""><i class="btn-icon-prepend" data-feather="x-circle"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        @isset($edit_data)
            $(getSubCategory());
        @endisset
        function getSubCategory(){
            var selected_sub_category_id = "";
            @isset($edit_data)
                selected_sub_category_id = "{{$edit_data->subcategory_id}}";
            @endisset
            $('#subcategory_id').empty();
            var categoryId = $('#category_id').val();
            $.get("{{ route('getSubCategory', '') }}/"+categoryId, function(data){
                $('#subcategory_id').append("<option value='' selected disabled>Select Sub Category</option>");
                $.each(data, function(key,val) {
                    if(selected_sub_category_id==val.id){
                        $('#subcategory_id').append("<option value="+val.id+" selected>"+val.name+"</option>");
                    }else{
                        $('#subcategory_id').append("<option value="+val.id+" >"+val.name+"</option>");
                    }
                });
            });
        }

        $(".add_image_field").click(function() {
            var lsthmtl = $(".clone").html();
            $(".image_fields").before(lsthmtl);
        });
        $("body").on("click", ".danger1", function() {
            $(this).parents(".imageDivField").remove();
        });
    </script>
@endsection
