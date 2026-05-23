@extends('admin.layouts.app')

@section('content')
    <style>
        /* Modern Quotations Page Styling - Same as Leads */
        .quotations-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .search-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 0.875rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border-left: 3px solid;
            cursor: pointer;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-card.total {
            border-left-color: #667eea;
        }

        .stat-card.draft {
            border-left-color: #6b7280;
        }

        .stat-card.sent {
            border-left-color: #3b82f6;
        }

        .stat-card.accepted {
            border-left-color: #10b981;
        }

        .stat-card.rejected {
            border-left-color: #ef4444;
        }

        .stat-card .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
        }

        .stat-card .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .quotation-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            border-left: 4px solid #e5e7eb;
        }

        .quotation-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left-color: #667eea;
        }

        .quotation-card .quotation-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .quotation-card .quotation-number {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .quotation-card .customer-name {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .quotation-card .quotation-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .quotation-card .info-item {
            display: flex;
            flex-direction: column;
        }

        .quotation-card .info-label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .quotation-card .info-value {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .quotation-card .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.625rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: white;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .action-btn.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .action-btn.btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
        }

        .action-btn.btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .action-btn.btn-info:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .action-btn.btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .action-btn.btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .badge-modern {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .quotations-header {
                padding: 1.25rem 1rem;
                margin-bottom: 1rem;
            }

            .quotations-header h2 {
                font-size: 1.25rem;
                margin-bottom: 0.25rem;
            }

            .quotations-header p {
                font-size: 0.813rem;
            }

            .search-card {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .search-card .form-label {
                font-size: 0.75rem;
                margin-bottom: 0.25rem;
            }

            .search-card .form-control,
            .search-card .form-select {
                font-size: 0.875rem;
                padding: 0.5rem;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-card .stat-icon {
                width: 28px;
                height: 28px;
                margin-bottom: 0.375rem;
            }

            .stat-card .stat-label {
                font-size: 0.688rem;
                margin-bottom: 0.125rem;
            }

            .stat-card .stat-value {
                font-size: 1.25rem;
            }

            .quotation-card {
                padding: 0.875rem;
                margin-bottom: 0.75rem;
            }

            .quotation-card .quotation-number {
                font-size: 1rem;
                margin-bottom: 0.125rem;
            }

            .quotation-card .customer-name {
                font-size: 0.813rem;
            }

            .quotation-card .quotation-header {
                margin-bottom: 0.75rem;
            }

            .quotation-card .quotation-info {
                grid-template-columns: 1fr 1fr;
                gap: 0.625rem;
                margin-bottom: 0.75rem;
            }

            .quotation-card .info-label {
                font-size: 0.688rem;
                margin-bottom: 0.125rem;
            }

            .quotation-card .info-value {
                font-size: 0.813rem;
            }

            .quotation-card .actions {
                gap: 0.375rem;
            }

            .action-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.813rem;
                gap: 0.375rem;
            }

            .action-btn svg {
                width: 14px !important;
                height: 14px !important;
            }

            .badge-modern {
                padding: 0.25rem 0.5rem;
                font-size: 0.688rem;
            }
        }

        @media (max-width: 576px) {
            .quotations-header h2 {
                font-size: 1.125rem;
            }

            .quotations-header .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .stat-card .stat-value {
                font-size: 1.125rem;
            }

            .stat-card .stat-icon {
                width: 24px;
                height: 24px;
            }

            .quotation-card .quotation-info {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .action-btn {
                flex: 1;
                justify-content: center;
                padding: 0.5rem;
                font-size: 0.75rem;
            }

            .action-btn span {
                display: none;
            }

            .action-btn svg {
                margin: 0 !important;
            }

            .mobile-toggle-btn {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .stats-row .col-lg-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 992px) {
            .mobile-toggle-btn {
                display: flex;
            }
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .mobile-toggle-btn {
            display: none;
            width: 100%;
            padding: 0.75rem;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mobile-toggle-btn:hover {
            background: #f9fafb;
        }

        .mobile-toggle-btn i {
            transition: transform 0.3s ease;
        }

        .mobile-toggle-btn.collapsed i:last-child {
            transform: rotate(-90deg);
        }

        .collapsible-section {
            max-height: 5000px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .collapsible-section:not(.show) {
            max-height: 0;
            opacity: 0;
            margin-bottom: 0 !important;
        }
    </style>

    <div class="page-content">
        <!-- Header -->
        <div class="quotations-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">Quotations</h2>
                    <p class="mb-0 opacity-90">Manage and track customer quotations</p>
                </div>
                <a href="{{ route('quotations.create') }}" class="btn btn-light btn-lg">
                    <i data-feather="plus"></i> Create Quotation
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Mobile Filter Toggle -->
        <button class="mobile-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection">
            <span><i data-feather="filter" style="width: 16px; height: 16px;"></i> Filters</span>
            <i data-feather="chevron-down" style="width: 20px; height: 20px;"></i>
        </button>

        <!-- Search & Filters -->
        <div class="search-card collapsible-section collapse show" id="filterSection">
            <form method="GET" action="{{ route('quotations.index') }}">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <label class="form-label small fw-semibold">Search</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Quotation number, customer name..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="search" style="width: 16px; height: 16px;"></i>
                            </button>
                            @if (request('search') || request('status'))
                                <a href="{{ route('quotations.index') }}" class="btn btn-outline-danger" title="Clear">
                                    <i data-feather="x" style="width: 16px; height: 16px;"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Mobile Stats Toggle -->
        <button class="mobile-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#statsSection">
            <span><i data-feather="bar-chart-2" style="width: 16px; height: 16px;"></i> Statistics</span>
            <i data-feather="chevron-down" style="width: 20px; height: 20px;"></i>
        </button>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4 stats-row collapsible-section collapse show" id="statsSection">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('quotations.index') }}" class="text-decoration-none">
                    <div class="stat-card total">
                        <div class="stat-icon" style="background: #ede9fe;">
                            <i data-feather="file-text" style="color: #667eea; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Total</div>
                        <div class="stat-value">{{ $quotations->total() }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('quotations.index') }}?status=draft" class="text-decoration-none">
                    <div class="stat-card draft">
                        <div class="stat-icon" style="background: #f3f4f6;">
                            <i data-feather="edit-3" style="color: #6b7280; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Draft</div>
                        <div class="stat-value">{{ $quotations->where('status', 'draft')->count() }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('quotations.index') }}?status=sent" class="text-decoration-none">
                    <div class="stat-card sent">
                        <div class="stat-icon" style="background: #dbeafe;">
                            <i data-feather="send" style="color: #3b82f6; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Sent</div>
                        <div class="stat-value">{{ $quotations->where('status', 'sent')->count() }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('quotations.index') }}?status=accepted" class="text-decoration-none">
                    <div class="stat-card accepted">
                        <div class="stat-icon" style="background: #d1fae5;">
                            <i data-feather="check-circle" style="color: #10b981; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Accepted</div>
                        <div class="stat-value">{{ $quotations->where('status', 'accepted')->count() }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('quotations.index') }}?status=rejected" class="text-decoration-none">
                    <div class="stat-card rejected">
                        <div class="stat-icon" style="background: #fee2e2;">
                            <i data-feather="x-circle" style="color: #ef4444; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Rejected</div>
                        <div class="stat-value">{{ $quotations->where('status', 'rejected')->count() }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quotations List -->
        <div class="quotations-list">
            @forelse ($quotations as $quotation)
                <div class="quotation-card">
                    <div class="quotation-header">
                        <div>
                            <div class="quotation-number">{{ $quotation->quotation_number }}</div>
                            <div class="customer-name">
                                <i data-feather="user" style="width: 14px; height: 14px;"></i>
                                {{ optional($quotation->lead)->guest_name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            @if ($quotation->status == 'draft')
                                <span class="badge-modern bg-secondary">Draft</span>
                            @elseif($quotation->status == 'sent')
                                <span class="badge-modern bg-primary">Sent</span>
                            @elseif($quotation->status == 'accepted')
                                <span class="badge-modern bg-success">Accepted</span>
                            @elseif($quotation->status == 'rejected')
                                <span class="badge-modern bg-danger">Rejected</span>
                            @else
                                <span class="badge-modern bg-warning">Expired</span>
                            @endif

                            @if ($quotation->is_converted)
                                <span class="badge-modern bg-info">Converted</span>
                            @endif
                        </div>
                    </div>

                    <div class="quotation-info">
                        <div class="info-item">
                            <div class="info-label">Date</div>
                            <div class="info-value">{{ $quotation->quotation_date->format('d M Y') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Valid Until</div>
                            <div class="info-value">
                                {{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : '-' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Amount</div>
                            <div class="info-value">
                                <strong>₹{{ number_format($quotation->total_amount, 2) }}</strong>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Items</div>
                            <div class="info-value">
                                <span class="badge bg-info">{{ $quotation->items->count() }} items</span>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="{{ route('quotations.show', $quotation->id) }}" class="action-btn btn-info"
                            title="View Details">
                            <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                            <span>View</span>
                        </a>
                        @if ($quotation->status == 'draft')
                            <a href="{{ route('quotations.edit', $quotation->id) }}" class="action-btn btn-primary"
                                title="Edit">
                                <i data-feather="edit" style="width: 16px; height: 16px;"></i>
                                <span>Edit</span>
                            </a>
                        @endif
                        @if (!$quotation->is_converted && $quotation->status != 'accepted')
                            <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST"
                                class="d-inline" id="delete_form_{{ $quotation->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="action-btn btn-danger" title="Delete"
                                    onclick="confirmDeleteQuotation({{ $quotation->id }}, '{{ $quotation->quotation_number }}')">
                                    <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                    <span>Delete</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h4>No Quotations Found</h4>
                    <p>Try adjusting your search filters or create a new quotation.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($quotations->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {!! $quotations->appends(request()->query())->links() !!}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // Handle toggle button clicks
            const toggleButtons = document.querySelectorAll('.mobile-toggle-btn');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('data-bs-target');
                    const targetElement = document.querySelector(targetId);
                    const chevronIcon = this.querySelector('i:last-child');

                    if (targetElement) {
                        if (targetElement.classList.contains('show')) {
                            targetElement.classList.remove('show');
                            this.classList.add('collapsed');
                            if (chevronIcon) {
                                chevronIcon.style.transform = 'rotate(-90deg)';
                            }
                        } else {
                            targetElement.classList.add('show');
                            this.classList.remove('collapsed');
                            if (chevronIcon) {
                                chevronIcon.style.transform = 'rotate(0deg)';
                            }
                        }
                    }
                });
            });

            setTimeout(() => {
                feather.replace();
            }, 100);

            // Confirm delete quotation
            function confirmDeleteQuotation(id, quotationNumber) {
                Swal.fire({
                    title: 'Delete Quotation?',
                    html: `<p>Are you sure you want to delete quotation <strong>${quotationNumber}</strong>?</p><p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>`,
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
        });
    </script>
@endsection
