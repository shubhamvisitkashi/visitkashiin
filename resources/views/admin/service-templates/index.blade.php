@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Service Templates</h4>
            <p class="text-muted">Manage service types for quotations (Innova Crysta, Sedan AC, etc.)</p>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ route('service-templates.create') }}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                Add Template
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Templates List</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service Type</th>
                                    <th>Template Name</th>
                                    <th>Capacity</th>
                                    <th>Selling Price</th>
                                    <th>Est. Cost</th>
                                    <th>Est. Profit</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $index => $template)
                                <tr>
                                    <td>{{ $templates->firstItem() + $index }}</td>
                                    <td><span class="badge bg-info">{{ $template->serviceType->name }}</span></td>
                                    <td><strong>{{ $template->name }}</strong></td>
                                    <td>{{ $template->capacity ? $template->capacity . ' persons' : '-' }}</td>
                                    <td>₹{{ number_format($template->default_selling_price, 2) }}</td>
                                    <td>₹{{ number_format($template->default_cost_estimate, 2) }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            ₹{{ number_format($template->estimated_profit, 2) }}
                                            ({{ number_format($template->estimated_profit_percentage, 1) }}%)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('service-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('service-templates.destroy', $template->id) }}" method="POST" class="d-inline" id="delete_form_{{ $template->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger deleteBtn" data-name="{{ $template->name }}">
                                                <i data-feather="trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No templates found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $templates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
