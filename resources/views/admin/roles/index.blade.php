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
                                @can('role-create')
                                    <x-add-btn route="{{route('roles.create')}}" />
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($list as $key=> $data)
                                        <tr>
                                            <td>{{($key+1) + ($list->currentPage() - 1)*$list->perPage()}}</td>
                                            <td> {{$data->name}} </td>
                                            <td>
                                                @can('role-edit')
                                                    <x-edit-btn route="{{ route('roles.edit', $data->id) }}" />
                                                @endcan
                                                @can('role-delete')
                                                    <x-delete-btn route="{{ route('roles.destroy', $data->id) }}" />
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
                            <div class="mt-2">
                                {{ $list->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
