@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('service-providers.index') }}">Service Providers</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Edit Service Provider</h6>
                    <form method="POST" action="{{ route('service-providers.update', $serviceProvider->id) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.service-providers._form')
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update Provider</button>
                            <a href="{{ route('service-providers.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
