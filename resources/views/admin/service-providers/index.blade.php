@extends('admin.layouts.app')

@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Service Providers</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <a href="{{ route('service-providers.create') }}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                    <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                    Add Provider
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('service-providers.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Provider Type</label>
                                    <select name="type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>Vendor
                                        </option>
                                        <option value="own" {{ request('type') == 'own' ? 'selected' : '' }}>Own Service
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Service Type</label>
                                    <select name="service_type_id" class="form-select">
                                        <option value="">All Services</option>
                                        @foreach ($serviceTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ request('service_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Name, contact person, phone..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                        <a href="{{ route('service-providers.index') }}"
                                            class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Providers List</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Service</th>
                                        <th>Contact</th>
                                        <th>Items</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($providers as $index => $provider)
                                        <tr>
                                            <td>{{ $providers->firstItem() + $index }}</td>
                                            <td><strong>{{ $provider->name }}</strong></td>
                                            <td>
                                                @if ($provider->type == 'vendor')
                                                    <span class="badge bg-warning">Vendor</span>
                                                @else
                                                    <span class="badge bg-success">Own Service</span>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach ($provider->serviceTypes as $serviceType)
                                                    <span class="badge bg-info me-1">{{ $serviceType->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $provider->contact_person }}<br>
                                                    {{ $provider->contact_number }}
                                                </small>
                                            </td>
                                            <td><span class="badge bg-primary">{{ $provider->service_items_count }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $provider->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $provider->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('service-providers.edit', $provider->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('service-providers.destroy', $provider->id) }}"
                                                    method="POST" class="d-inline" id="delete_form_{{ $provider->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDeleteProvider({{ $provider->id }}, '{{ $provider->name }}')">
                                                        <i data-feather="trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No providers found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $providers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDeleteProvider(id, name) {
                Swal.fire({
                    title: 'Delete Service Provider?',
                    html: `<p>Are you sure you want to delete <strong>${name}</strong>?</p><p class="text-danger"><strong>Warning:</strong> This will also delete all related service items!</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete_form_' + id).submit();
                    }
                });
            }
        </script>
    @endpush
@endsection
