@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-6"><h4>{{$page_title}}</h4></div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{route('enquiry.index')}}">
                            <div class="row">
                                <div class="col-3 card-title"></div>
                                <div class="col-3 card-title"></div>
                                <div class="col-3 card-title">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i data-feather="calendar"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="daterange" placeholder="Select Date..." value="{{$search_date_range}}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-3 card-title">
                                    <div class="input-group">
                                        <input type="text" name="search_key" class="form-control form-control-sm" value="{{$search_key}}" placeholder="Search" data-input>
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
                                        <th>Name</th>
                                        <th>Arrival Date/Time</th>
                                        {{-- <th>Message</th> --}}
                                        <th>Package Name</th>
                                        {{-- <th>Created At</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($enquires as $key=>$enquiry)
                                        <tr>
                                            <td>{{ $key + 1 + ($enquires->currentPage() - 1) * $enquires->perPage() }}</td>
                                            <td>Name : {{$enquiry->name}}<br>
                                                Phone :{{$enquiry->phone}}
                                            </td>
                                            <td>{{date('d-m-Y h:i A', strtotime($enquiry->arrival_date))}}</td>
                                            {{-- <td>{{$enquiry->message}}</td> --}}
                                            <td>{{$enquiry->package_name}}</td>
                                            {{-- <td>{{$enquiry->created_at->format('d-m-Y h:i A')}}</td> --}}
                                            <td>
                                                {{-- <x-edit-btn route="{{ route('product.edit', $enquiry->id) }}" /> --}}
                                                <x-delete-btn route="{{ route('enquiry.delete', $enquiry->id) }}" />
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
                                        <p><b>Showing {{($enquires->currentpage()-1)*$enquires->perpage()+1}} to {{(($enquires->currentpage()-1)*$enquires->perpage())+$enquires->count()}} of {{$enquires->total()}} Enquires</b></p>
                                    </div>
                                    <div class="col-md-8 d-flex justify-content-end">
                                        {!! $enquires->links() !!}
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
