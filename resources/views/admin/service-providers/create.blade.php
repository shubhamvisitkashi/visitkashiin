@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('service-providers.index') }}">Service Providers</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Add New Service Provider</h6>
                    <form method="POST" action="{{ route('service-providers.store') }}">
                        @csrf
                        @include('admin.service-providers._form')
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Create Provider</button>
                            <a href="{{ route('service-providers.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
