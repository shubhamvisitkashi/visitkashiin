@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 card-title"><h4>{{$page_title}}</h4></div>
                            <div class="col-md-6 text-end">
                                <x-cancle-btn text="Back" route="{{route('roles.index')}}" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="Name" class="form-label">Name</label>
                                    <input id="Name" class="form-control" type="text" name="name" required placeholder="Enter Role">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                @foreach ($permissionParent as $parent)
                                    <div class="col-4 mb-3">
                                        <div class="card card-outline card-primary">
                                            <div class="card-header">
                                                <div class="d-inline">
                                                    <input type="checkbox" id="all_{{$parent->parent_name}}" class="form-check-input me-2">
                                                    <label for="all_{{$parent->parent_name}}"><h5 class="card-title">{{ucwords(str_replace('-', ' ',ucwords(str_replace('_', ' ',$parent->parent_name))))}}</h5></label>
                                                </div>
                                            </div>
                                            <div class="card-body" style="height: 170px; overflow-x: hidden;">
                                                @php $permission = Spatie\Permission\Models\Permission::where('parent_name', $parent->parent_name)->get(); @endphp
                                                @foreach ($permission as $value)
                                                    <div class="mb-2">
                                                        <input type="checkbox" name="permission[]" id="roles_{{$value->name}}" class="roles_{{$parent->parent_name}} form-check-input me-2" value="{{$value->id}}">
                                                        <label for="roles_{{$value->name}}">{{ucwords(str_replace('-', ' ',ucwords(str_replace('_', ' ',$value->name))))}}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $('#all_{{$parent->parent_name}}').change(function() {
                                            $('.roles_{{$parent->parent_name}}').prop('checked', this.checked);
                                        });

                                        $('.roles_{{$parent->parent_name}}').change(function() {
                                            if ($('.roles_{{$parent->parent_name}}:checked').length == $('.roles_{{$parent->parent_name}}').length) {
                                                $('#all_{{$parent->parent_name}}').prop('checked', true);
                                            } else {
                                                $('#all_{{$parent->parent_name}}').prop('checked', false);
                                            }
                                        });
                                    </script>
                                @endforeach
                            </div>
                            <x-save-btn text="Save" />
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
