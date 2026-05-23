@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 card-title">
                                <h4>{{ $page_title }}</h4>
                            </div>
                            <div class="col-6 text-end">
                                <x-cancle-btn route="{{ route('agent.index') }}" text="Back" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('agent.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input id="name" class="form-control" type="text" name="name"
                                        value="{{ old('name') }}" placeholder="Agent Name..." required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input id="contact_number" class="form-control" type="number" name="contact_number"
                                        value="{{ old('contact_number') }}" placeholder="Contact Number..." required>
                                    @error('contact_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="alt_contact_number" class="form-label">Alt Contact Number</label>
                                    <input id="alt_contact_number" class="form-control" type="number"
                                        name="alt_contact_number" value="{{ old('alt_contact_number') }}" placeholder="Alt Contact Number...">
                                    @error('alt_contact_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <select name="state" id="state" class="form-control js-example-basic-single" onchange="getCity()">
                                        <option value="">Select State...</option>
                                        @foreach ($states as $state)
                                            <option value="{{$state->state}}" @if(old('state') == $state->state) selected @endif>{{$state->state}}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <select name="city" id="city" class="form-control js-example-basic-single">
                                        <option value="">Select City...</option>
                                    </select>
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input id="address" class="form-control" type="text" name="address"
                                        value="{{ old('address') }}" placeholder="Address...">
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    @foreach ($agent_services as $agent_service)
                                        <label>{{ $agent_service->name }}</label>
                                        <input type="checkbox" name="service_ids[]" value="{{ $agent_service->id }}" @if(in_array($agent_service->id,old('service_ids[]')??[])) checked @endif>
                                    @endforeach
                                </div>
                            </div>
                            <x-save-btn text="Save" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

        <script>

            @if(old('state'))
                $(getCity());
            @endif

            function getCity(){
                var state = $('#state').val();
                $.ajax({
                    type: 'POST',
                    url: '{{route("get.city")}}',
                    data: {
                        _token:"{{csrf_token()}}",
                        state:state
                    },
                    success: function(data) {
                        var old_city = "{{old('city')}}";
                        $('#city').empty();
                        $('#city').append('<option value="">Select City...</option>');
                        $.each(data, function(key, val) {
                            if(old_city == val.city){
                                $('#city').append('<option value="'+ val.city +'" selected>'+ val.city +'</option>');
                            }else{
                                $('#city').append('<option value="'+ val.city +'">'+ val.city +'</option>');
                            }
                        });
                    }
                });
            }

        </script>

    @endpush

@endsection
