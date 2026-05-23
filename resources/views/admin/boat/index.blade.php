@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">{{$page_title}} Management</h2>
                <p class="text-muted mb-0">Manage your boat efficiently</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- List Section -->
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0 fw-semibold text-dark">
                                    <i class="fas fa-ship me-2"></i>{{$page_title}} List
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <form action="{{route('boat.index')}}" method="GET" class="d-flex">
                                    <div class="row g-2 w-100">
                                        <div class="col-md-4">
                                            <select name="search_boat_type" class="form-select">
                                                <option value="">All Boat Types</option>
                                                @foreach($boat_types as $boat_type)
                                                    <option value="{{$boat_type->id}}" @if($search_boat_type == $boat_type->id) selected @endif>
                                                        {{$boat_type->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="search_event_type" class="form-select">
                                                <option value="">All Event Types</option>
                                                <option value="Regular" @if($search_event_type == 'Regular') selected @endif>Regular</option>
                                                <option value="Festival" @if($search_event_type == 'Festival') selected @endif>Festival</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="btn-group w-100" role="group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter me-1"></i>Filter
                                                </button>
                                                <a href="{{route('boat.index')}}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-redo me-1"></i>Reset
                                                </a>
                                                <a href="{{route('boat.create')}}" class="btn btn-success">
                                                    <i class="fas fa-plus me-1"></i>Add
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center fw-semibold">#</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-tag me-1"></i>Boat Type</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-image me-1"></i>Event Type</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-toggle-on me-1"></i>Total Available Boat</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-toggle-on me-1"></i>No of Seat</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-toggle-on me-1"></i>Price</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-toggle-on me-1"></i>Status</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-cogs me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($boats as $boat)
                                        <tr class="border-bottom">
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $loop->index + 1 }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-semibold text-dark">{{$boat->boatType?->name}}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-semibold text-dark">{{$boat->event_type}}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-semibold text-dark">{{$boat->total_available_boat}}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-semibold text-dark">{{$boat->no_of_seat}} / Boat</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-semibold text-dark">
                                                    @if($boat->price != $boat->discounted_price)
                                                        <del>₹ {{number_format($boat->price)}}</del>
                                                    @endif
                                                    ₹ {{number_format($boat->discounted_price)}}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input type="checkbox" class="form-check-input status_update fs-5" name="is_active" value="{{ $boat->id }}" @if($boat->is_active == '1') checked @endif>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <x-edit-btn route="{{ route('boat.edit', $boat->id) }}" />
                                                    <x-delete-btn route="{{ route('boat.destroy', $boat->id) }}" />
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-frown text-muted mb-3" style="font-size: 3rem;"></i>
                                                    <h5 class="text-muted mb-2">No Data Found</h5>
                                                    <p class="text-muted mb-0">There are no boat to display.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".status_update").change(function() {
            var slug = $(this).val();
            var checkbox = $(this);
            checkbox.prop('disabled', true);
            $.get("{{ route('boat.show', '') }}/" + slug, function(data) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'colored-toast'
                    }
                });
                Toast.fire({
                    icon: data.res,
                    title: data.message,
                });
                checkbox.prop('disabled', false);
                location.reload();
            }).fail(function() {
                checkbox.prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update status. Please try again.',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });
    </script>

    <style>
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }
    </style>
@endsection
