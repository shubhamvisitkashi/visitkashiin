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
                                <x-cancle-btn route="{{route('product.index')}}" text="Back"/>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @isset($edit_data)
                            <form id="valid_form" method="POST" action="{{route('product.update', $edit_data->id)}}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <form id="valid_form" method="POST" action="{{route('product.store')}}" enctype="multipart/form-data">
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
                                    <select class="form-control js-example-basic-single" name="subcategory_id" id="subcategory_id">
                                        <option value="" selected disabled>Select Sub Category</option>

                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Title</label>
                                    <input id="name" class="form-control" type="text" name="name" placeholder="Enter Title" @isset($edit_data)value="{{$edit_data->name}}"@endisset required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="base_price" class="form-label">Base Price</label>
                                    <input id="base_price" class="form-control" type="number" step="0.01" name="base_price" placeholder="Enter Base Price" @isset($edit_data)value="{{$edit_data->base_price}}" @else value="0.00" @endisset required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="discounted_price" class="form-label">Discounted Price</label>
                                    <input id="discounted_price" class="form-control" type="number" step="0.01" name="discounted_price" placeholder="Enter Base Price" @isset($edit_data)value="{{$edit_data->discounted_price}}"@endisset required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="images" class="form-label">Images</label>
                                    <div class="needsclick dropzone" id="document-dropzone"></div>
                                </div>
                                {{-- <div class="col-md-12 mb-3">
                                    <label for="images" class="form-label">Images</label>
                                    <div class="input-group">
                                        <input id="images" class="form-control" type="file" name="images[]" accept="image/*" @isset($edit_data) @else required @endisset>
                                        <span class="input-group-text input-group-addon btn btn-outline-success add_image_field" data-toggle=""><i class="btn-icon-prepend" data-feather="plus-circle"></i></span>
                                    </div>
                                    <div class="image_fields"></div>
                                </div> --}}
                                <div class="col-md-4 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input id="address" class="form-control" type="text" name="address" placeholder="Enter Address" @isset($edit_data)value="{{$edit_data->address}}"@endisset>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="map_location" class="form-label">Map Location</label>
                                    <input id="map_location" class="form-control" type="text" name="map_location" placeholder="Enter Map Location" @isset($edit_data)value="{{$edit_data->map_location}}"@endisset>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="youtube_link" class="form-label">Youtube Link</label>
                                    <input id="youtube_link" class="form-control" type="text" name="youtube_link" placeholder="Enter Youtube Link" @isset($edit_data)value="{{$edit_data->youtube_link}}"@endisset>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="editor" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="editor" rows="10" placeholder="Enter description" required>@isset($edit_data){{$edit_data->description}}@endisset</textarea>
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
@push('scripts')

<script>
    var uploadedDocumentMap = {}
    Dropzone.options.documentDropzone = {
        dictDefaultMessage: "Select File to Upload",
        url: '{{route("image.upload")}}',
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function(file, response) {
            $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">')
            uploadedDocumentMap[file.name] = response.name
        },
        removedfile: function(file) {
            file.previewElement.remove();
            if (typeof file.name !== 'undefined') {
                name = file.name
            } else {
                name = uploadedDocumentMap[file.name]
            }
            $('form').find('input[name="images[]"][value="' + name + '"]').remove();
        },
        init: function() {
            @if (isset($edit_data) && is_array($edit_data->images))
                @foreach ($edit_data->images as $gallery)
                    var existingFileUrl = "{{ asset('backend/admin/product_images/'.$gallery) }}";
                    var mockFile = {
                        name: "{{ $gallery }}",
                        size: "30.3",
                        accepted: true
                    };
                    // Add the mock file to the Dropzone
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, existingFileUrl);
                    this.emit("complete", mockFile);
                    // Disable further file uploads
                    // Customize the look and behavior of the thumbnail
                    this.options.thumbnail.call(this, mockFile, existingFileUrl);
                    $('form').append('<input type="hidden" name="images[]" value="{{ $gallery }}">');
                @endforeach
            @endif
        }
    }
</script>

@endpush
