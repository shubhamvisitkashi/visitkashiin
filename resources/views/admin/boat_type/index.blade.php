@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">{{$page_title}} Management</h2>
                <p class="text-muted mb-0">Manage your boat types efficiently</p>
            </div>
            <div class="d-flex gap-3">
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-ship me-2"></i>Total: {{$boat_types->total()}}
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-check-circle me-2"></i>Active: {{ $boat_types->where('is_active', 1)->count() }}
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning fs-6 px-3 py-2">
                        <i class="fas fa-pause-circle me-2"></i>Inactive: {{ $boat_types->where('is_active', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- List Section -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="fas fa-ship me-2"></i>{{$page_title}} List
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <form action="{{route('boat-type.index')}}" method="GET" class="d-flex">
                                    <div class="input-group">
                                        <input type="text" name="search_key" class="form-control" placeholder="Search boat types..." value="{{$search_key}}">
                                        <button type="submit" class="btn btn-light border">
                                            <i data-feather="search"></i>
                                        </button>
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
                                        <th class="text-center fw-semibold"><i class="fas fa-tag me-1"></i>Name</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-image me-1"></i>Image</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-toggle-on me-1"></i>Status</th>
                                        <th class="text-center fw-semibold"><i class="fas fa-cogs me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($boat_types as $boat_type)
                                        <tr class="border-bottom">
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $boat_types->firstItem() + $loop->index }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-semibold text-dark">{{$boat_type->name}}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <img src="{{$boat_type->image}}" class="rounded border shadow-sm" height="60" width="60" style="object-fit: cover;" alt="{{$boat_type->name}}">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input type="checkbox" class="form-check-input status_update fs-5" name="is_active" value="{{ $boat_type->slug }}" @if($boat_type->is_active == '1') checked @endif>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <x-edit-btn route="{{ route('boat-type.edit', $boat_type->slug) }}" />
                                                    <x-delete-btn route="{{ route('boat-type.destroy', $boat_type->slug) }}" />
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-frown text-muted mb-3" style="font-size: 3rem;"></i>
                                                    <h5 class="text-muted mb-2">No Data Found</h5>
                                                    <p class="text-muted mb-0">There are no boat types to display.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($boat_types->hasPages())
                            <div class="card-footer bg-light border-top-0">
                                <div class="d-flex justify-content-center">
                                    {{ $boat_types->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-success text-white border-0">
                        <h5 class="mb-0 fw-semibold">
                            @isset($edit_boat_type)
                                <i class="fas fa-edit me-2"></i>Update {{$page_title}}
                            @else
                                <i class="fas fa-plus me-2"></i>Create {{$page_title}}
                            @endisset
                        </h5>
                    </div>
                    <div class="card-body">
                        @isset($edit_boat_type)
                            <div class="alert alert-info border-0 mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>You are editing: <strong>{{$edit_boat_type->name}}</strong></small>
                            </div>
                            <form id="valid_form" method="POST" action="{{route('boat-type.update',$edit_boat_type->slug)}}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <form id="valid_form" method="POST" action="{{route('boat-type.store')}}" enctype="multipart/form-data">
                        @endisset
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-tag text-primary me-1"></i>Name <span class="text-danger">*</span>
                                </label>
                                <input id="name" class="form-control form-control-lg border-2" type="text" name="name" @isset($edit_boat_type)value="{{$edit_boat_type->name}}"@endisset placeholder="Enter boat type name">
                                @error('name')
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{$message}}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">
                                    <i class="fas fa-image text-primary me-1"></i>Image
                                </label>
                                <input id="image" class="form-control form-control-lg border-2" type="file" name="image" accept="image/*">
                                @isset($edit_boat_type)
                                    @if($edit_boat_type->image)
                                        <div class="mt-2">
                                            <div class="border rounded p-2 bg-light">
                                                <small class="text-muted d-block mb-1">Current Image:</small>
                                                <img src="{{$edit_boat_type->image}}" class="rounded border" height="80" width="80" style="object-fit: cover;" alt="{{$edit_boat_type->name}}">
                                            </div>
                                        </div>
                                    @endif
                                @endisset
                                @error('image')
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{$message}}
                                    </div>
                                @enderror
                            </div>
                            <!-- SEO Section -->
                            <div class="border rounded p-3 mb-3 bg-light">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="fas fa-search-plus me-1"></i>SEO Settings
                                </h6>
                                <div class="mb-3">
                                    <label for="seo_title" class="form-label fw-semibold">SEO Title</label>
                                    <input id="seo_title" class="form-control border-2" type="text" name="seo_title" @isset($edit_boat_type)value="{{$edit_boat_type->seo_title}}"@endisset placeholder="Enter SEO title">
                                    @error('seo_title')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label fw-semibold">SEO Keywords</label>
                                    <input id="seo_keywords" class="form-control border-2" type="text" name="seo_keywords" @isset($edit_boat_type)value="{{$edit_boat_type->seo_keywords}}"@endisset placeholder="Enter SEO keywords">
                                    @error('seo_keywords')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{$message}}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label for="seo_description" class="form-label fw-semibold">SEO Description</label>
                                    <textarea id="seo_description" class="form-control border-2" name="seo_description" rows="3" placeholder="Enter SEO description">@isset($edit_boat_type){{$edit_boat_type->seo_description}}@endisset</textarea>
                                    @error('seo_description')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{$message}}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                @isset($edit_boat_type)
                                    <x-save-btn text="Update Boat Type" />
                                    <x-cancle-btn text="Cancel" route="{{route('boat-type.index')}}" />
                                @else
                                    <x-save-btn text="Save Boat Type" />
                                @endisset
                            </div>
                        </form>
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
            $.get("{{ route('boat-type.show', '') }}/" + slug, function(data) {
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
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
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
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
@endsection
