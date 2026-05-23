@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">{{$page_title}}</h6>
                        <form id="valid_form" method="POST" action="{{route('change.password.store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="password" class="form-label">password</label>
                                <input id="password" class="form-control" type="text" name="password" required placeholder="Enter Password...">
                            </div>
                            <x-save-btn text="Change Password" />
                        </form>
                    </div>`
                </div>
            </div>
        </div>
    </div>
@endsection
