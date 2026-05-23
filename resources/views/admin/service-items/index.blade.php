@extends('admin.layouts.app')

@section('content')
    <style>
        /* Modern Card Styles */
        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 24px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .modern-card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.12);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 32px;
            border-radius: 16px;
            color: white;
            margin-bottom: 28px;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .page-header h2 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
            position: relative;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 15px;
            position: relative;
        }

        /* Filter Card */
        .filter-card {
            background: #f8f9fc;
            border: 1px solid #e3e6ef;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .filter-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-title i {
            color: #667eea;
        }

        /* Form Controls */
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            display: block;
        }

        .form-select,
        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Modern Buttons */
        .btn-modern {
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-modern i {
            width: 18px;
            height: 18px;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-secondary-modern {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary-modern:hover {
            background: #cbd5e0;
            color: #2d3748;
        }

        /* Table Styles */
        .modern-table {
            width: 100%;
            margin-bottom: 0;
        }

        .modern-table thead {
            background: #f8f9fc;
        }

        .modern-table thead th {
            padding: 16px 18px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a5568;
            border: none;
            white-space: nowrap;
        }

        .modern-table tbody td {
            padding: 18px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f9;
            color: #2d3748;
            font-size: 14px;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fc;
        }

        /* Provider Info */
        .provider-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .provider-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .provider-details strong {
            display: block;
            font-size: 14px;
            color: #2d3748;
            margin-bottom: 2px;
        }

        .provider-details small {
            font-size: 12px;
            color: #718096;
        }

        /* Template Info */
        .template-info strong {
            display: block;
            font-size: 14px;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .template-info small {
            font-size: 12px;
            color: #718096;
            line-height: 1.4;
        }

        /* Modern Badges */
        .badge-modern {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.3px;
        }

        .badge-success-modern {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .badge-secondary-modern {
            background: #e2e8f0;
            color: #64748b;
        }

        .badge-info-modern {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .badge-warning-modern {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        /* Price Display */
        .price-display {
            font-weight: 600;
            color: #10b981;
            font-size: 14px;
        }

        .vendor-cost {
            color: #f59e0b;
        }

        /* Action Buttons */
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            padding: 0;
        }

        .action-btn i {
            width: 16px;
            height: 16px;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state-icon i {
            width: 40px;
            height: 40px;
            color: #667eea;
        }

        .empty-state h5 {
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 18px;
        }

        .empty-state p {
            color: #a0aec0;
            margin-bottom: 20px;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e3e6ef;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon i {
            width: 24px;
            height: 24px;
            color: white;
        }

        .stat-content h4 {
            margin: 0 0 4px 0;
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-content p {
            margin: 0;
            font-size: 13px;
            color: #718096;
        }

        /* Alert Styles */
        .alert-modern {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .alert-success-modern {
            background: linear-gradient(135deg, #10b98115 0%, #05966915 100%);
            color: #059669;
            border-left: 4px solid #10b981;
        }

        .alert-danger-modern {
            background: linear-gradient(135deg, #ef444415 0%, #dc262615 100%);
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 24px;
            }

            .page-header h2 {
                font-size: 24px;
            }

            .filter-card {
                padding: 16px;
            }

            .modern-table thead th,
            .modern-table tbody td {
                padding: 12px;
                font-size: 13px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <div class="page-content">
        <!-- Page Header -->
        <div class="page-header animate-fade-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h2>
                        <i data-feather="grid" style="width: 28px; height: 28px; margin-right: 8px;"></i>
                        Service Items
                    </h2>
                    <p>Manage all your service items and inventory across providers</p>
                </div>
                <a href="{{ route('service-items.create') }}" class="btn btn-primary-modern">
                    <i data-feather="plus-circle"></i>
                    Add Service Item
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success-modern alert-dismissible fade show animate-fade-in">
                <i data-feather="check-circle" style="width: 20px; height: 20px;"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger-modern alert-dismissible fade show animate-fade-in">
                <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="stats-row animate-fade-in" style="animation-delay: 0.1s">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i data-feather="package"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $items->total() }}</h4>
                    <p>Total Items</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i data-feather="check-circle"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $items->where('is_active', true)->count() }}</h4>
                    <p>Active Items</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <i data-feather="users"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $providers->count() }}</h4>
                    <p>Providers</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i data-feather="briefcase"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $items->sum('booking_services_count') }}</h4>
                    <p>Total Bookings</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card animate-fade-in" style="animation-delay: 0.2s">
            <div class="filter-title">
                <i data-feather="filter"></i>
                Filter Service Items
            </div>
            <form method="GET" action="{{ route('service-items.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Service Provider</label>
                    <select name="service_provider_id" class="form-select">
                        <option value="">All Providers</option>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}"
                                {{ request('service_provider_id') == $provider->id ? 'selected' : '' }}>
                                {{ $provider->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Service Template</label>
                    <select name="service_template_id" class="form-select">
                        <option value="">All Templates</option>
                        @foreach ($serviceTemplates as $template)
                            <option value="{{ $template->id }}"
                                {{ request('service_template_id') == $template->id ? 'selected' : '' }}>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by template name..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary-modern flex-fill">
                        <i data-feather="search"></i>
                        Filter
                    </button>
                    <a href="{{ route('service-items.index') }}" class="btn btn-secondary-modern">
                        <i data-feather="x"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Service Items Table -->
        <div class="modern-card animate-fade-in" style="animation-delay: 0.3s">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Service Type</th>
                                <th>Template</th>
                                <th>Capacity</th>
                                <th>Vendor Cost</th>
                                <th>Base Price</th>
                                <th>Status</th>
                                <th>Bookings</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        @if ($item->serviceProvider)
                                            <div class="provider-info">
                                                <div class="provider-icon">
                                                    {{ strtoupper(substr($item->serviceProvider->name, 0, 2)) }}
                                                </div>
                                                <div class="provider-details">
                                                    <strong>{{ $item->serviceProvider->name }}</strong>
                                                    <small>{{ ucfirst($item->serviceProvider->type) }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->serviceTemplate && $item->serviceTemplate->serviceType)
                                            <span class="badge-modern badge-info-modern">
                                                <i data-feather="layers" style="width: 12px; height: 12px;"></i>
                                                {{ $item->serviceTemplate->serviceType->name }}
                                            </span>
                                        @else
                                            <span class="badge-modern badge-secondary-modern">
                                                <i data-feather="help-circle" style="width: 12px; height: 12px;"></i>
                                                N/A
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->serviceTemplate)
                                            <div class="template-info">
                                                <strong>{{ $item->serviceTemplate->name }}</strong>
                                                @if ($item->serviceTemplate->description)
                                                    <small>{{ Str::limit($item->serviceTemplate->description, 40) }}</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-secondary-modern">
                                            <i data-feather="users" style="width: 12px; height: 12px;"></i>
                                            {{ $item->capacity ?? ($item->serviceTemplate?->capacity ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="price-display vendor-cost">
                                            ₹{{ number_format($item->vendor_cost, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="price-display">
                                            ₹{{ number_format($item->base_price, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->is_active)
                                            <span class="badge-modern badge-success-modern">
                                                <i data-feather="check-circle" style="width: 12px; height: 12px;"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="badge-modern badge-secondary-modern">
                                                <i data-feather="x-circle" style="width: 12px; height: 12px;"></i>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-warning-modern">
                                            <i data-feather="calendar" style="width: 12px; height: 12px;"></i>
                                            {{ $item->booking_services_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('service-items.edit', $item->service_provider_id) }}"
                                                class="action-btn btn-edit" title="Edit Items">
                                                <i data-feather="edit-2"></i>
                                            </a>
                                            <form action="{{ route('service-items.destroy', $item->id) }}" method="POST"
                                                class="d-inline" id="delete_form_{{ $item->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-btn btn-delete" title="Delete"
                                                    onclick="confirmDeleteItem({{ $item->id }}, '{{ $item->serviceTemplate->name ?? 'this item' }}')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="border-0">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i data-feather="inbox"></i>
                                            </div>
                                            <h5>No Service Items Found</h5>
                                            <p>Start by adding your first service item to manage your inventory.</p>
                                            <a href="{{ route('service-items.create') }}" class="btn btn-primary-modern">
                                                <i data-feather="plus-circle"></i>
                                                Add Service Item
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($items->hasPages())
                    <div class="p-4 border-top">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize Feather Icons
            feather.replace();

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Confirm delete service item
            function confirmDeleteItem(id, name) {
                Swal.fire({
                    title: 'Delete Service Item?',
                    html: `<p>Are you sure you want to delete <strong>${name}</strong>?</p><p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>`,
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
