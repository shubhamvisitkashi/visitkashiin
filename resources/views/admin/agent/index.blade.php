@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>{{ $page_title }}</h4>
                            </div>
                            <div class="col-md-6" style="padding-bottom:10px">
                                <x-add-btn route="{{ route('agent.create') }}" />
                            </div>
                            <div class="col-md-12">
                                <form action="{{ route('agent.index') }}" id="search_form">
                                    <div class="row">
                                        <div class="col-3">
                                            @if(Auth::guard('admin')->user()->id == 1)
                                                <div class="input-group">
                                                    <select class="form-control js-example-basic-single" name="search_staff" id="search_staff">
                                                        <option value="" selected>All Staff</option>
                                                        @foreach ($staffs as $staff)
                                                            <option value="{{ $staff->id }}"{{ $staff->id == $search_staff ? 'selected' : '' }}>
                                                                {{ $staff->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group">
                                                <select class="form-control js-example-basic-single" name="search_state" id="search_state">
                                                    <option value="" selected>All State</option>
                                                    @foreach ($states as $state)
                                                        <option
                                                            value="{{ $state->state }}"{{ $state->state == $search_state ? 'selected' : '' }}>
                                                            {{ $state->state }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group">
                                                <select class="form-control" name="search_service" id="search_service">
                                                    <option value="" selected>All Service</option>
                                                    @foreach ($agent_services as $agent_service)
                                                        <option
                                                            value="{{ $agent_service->id }}"{{ $agent_service->id == $search_service ? 'selected' : '' }}>
                                                            {{ $agent_service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group">
                                                <input type="text" name="search_key" class="form-control form-control-sm"
                                                    placeholder="Search name or phone..." value="{{ $search_key }}"
                                                    data-input>
                                                <button type="submit" class="input-group-text input-group-addon"
                                                    data-toggle>
                                                    <i data-feather="search"></i>
                                                </button>

                                                @if ($search_staff || $search_service || $search_key || $search_state)
                                                    <a href="{{ route('agent.index') }}" title="Clear Search"
                                                        class="input-group-text input-group-addon text-danger"
                                                        data-toggle><i data-feather="x-circle"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr>
                            <div class="lead-status">
                                <div class="lead-status-card">
                                    <a href="{{ route('agent.index') }}">
                                        <div
                                            class="bg-total bg-lead py-2 ps-3 pe-2 d-flex align-items-center justify-content-between">
                                            <h3>Total Agent</h3>
                                            <span>{{ $total_agent }}</span>
                                        </div>
                                    </a>
                                </div>
                                @foreach ($agent_services as $agent_service)
                                    <div class="lead-status-card">
                                        <a href="{{ route('agent.index') }}">
                                            <div
                                                class="bg-total bg-lead py-2 ps-3 pe-2 d-flex align-items-center justify-content-between">
                                                <h3>Total {{$agent_service->name}}</h3>
                                                <span>{{App\Models\Agent::wherejsonContains('service_ids',''.$agent_service->id)->when(Auth::guard('admin')->user()->id != '1', function ($query) {
                                                    $query->where('admin_id',Auth::guard('admin')->user()->id);
                                                })->when($search_staff,function($query) use ($search_staff){
                                                    $query->where('admin_id',$search_staff);
                                                })->when($search_service,function($query) use ($search_service){
                                                    $query->whereJsonContains('service_ids',''.$search_service);
                                                })->when($search_state,function($query) use ($search_state){
                                                    $query->where('state',$search_state);
                                                })->when($search_key,function($query) use ($search_key){
                                                    $query->where(function($qu) use ($search_key){
                                                        $qu->where('name','LIKE','%'.$search_key.'%')->orWhere('contact_number',$search_key)->orWhere('alt_contact_number',$search_key)->orWhere('city',$search_key);
                                                    });
                                                })->get()->count()}}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="table">
                            @include('admin.agent.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
