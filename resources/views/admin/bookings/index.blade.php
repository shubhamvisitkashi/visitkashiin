@extends('admin.layouts.app')

@section('content')
    <style>
        /* Modern Bookings Page Styling - Same Pattern as Leads */
        .bookings-header {
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

        .stat-card.not-started {
            border-left-color: #6b7280;
        }

        .stat-card.confirmed {
            border-left-color: #3b82f6;
        }

        .stat-card.in-progress {
            border-left-color: #f59e0b;
        }

        .stat-card.completed {
            border-left-color: #10b981;
        }

        .stat-card.cancelled {
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

        .booking-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            border-left: 4px solid #e5e7eb;
        }

        .booking-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left-color: #667eea;
        }

        .booking-card .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .booking-card .booking-number {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .booking-card .customer-name {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .booking-card .booking-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .booking-card .info-item {
            display: flex;
            flex-direction: column;
        }

        .booking-card .info-label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .booking-card .info-value {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .booking-card .actions {
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

        .action-btn.btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .action-btn.btn-info:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
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
            .bookings-header {
                padding: 1.25rem 1rem;
                margin-bottom: 1rem;
            }

            .bookings-header h2 {
                font-size: 1.25rem;
                margin-bottom: 0.25rem;
            }

            .bookings-header p {
                font-size: 0.813rem;
            }

            .bookings-header .btn {
                padding: 0.5rem 0.875rem;
                font-size: 0.813rem;
                white-space: nowrap;
            }

            .bookings-header .btn svg {
                width: 14px !important;
                height: 14px !important;
            }

            .bookings-header .d-flex.gap-2 {
                width: 100%;
                justify-content: stretch;
            }

            .bookings-header .d-flex.gap-2 .btn {
                flex: 1;
                justify-content: center;
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

            .booking-card {
                padding: 0.875rem;
                margin-bottom: 0.75rem;
            }

            .booking-card .booking-number {
                font-size: 1rem;
                margin-bottom: 0.125rem;
            }

            .booking-card .customer-name {
                font-size: 0.813rem;
            }

            .booking-card .booking-header {
                margin-bottom: 0.75rem;
            }

            .booking-card .booking-info {
                grid-template-columns: 1fr 1fr;
                gap: 0.625rem;
                margin-bottom: 0.75rem;
            }

            .booking-card .info-label {
                font-size: 0.688rem;
                margin-bottom: 0.125rem;
            }

            .booking-card .info-value {
                font-size: 0.813rem;
            }

            .booking-card .actions {
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
            .bookings-header h2 {
                font-size: 1.063rem;
            }

            .bookings-header p {
                font-size: 0.75rem;
            }

            .bookings-header .d-flex.gap-2 {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }

            .bookings-header .btn {
                padding: 0.75rem 0.5rem;
                font-size: 0.688rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.375rem;
                min-height: 70px;
            }

            .bookings-header .btn svg {
                width: 20px !important;
                height: 20px !important;
                margin: 0 !important;
            }

            .stat-card .stat-value {
                font-size: 1.125rem;
            }

            .stat-card .stat-icon {
                width: 24px;
                height: 24px;
            }

            .booking-card .booking-info {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .action-btn {
                padding: 0.625rem 0.375rem;
                font-size: 0.625rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.25rem;
                flex: 1 1 calc(33.333% - 0.375rem);
                min-width: 0;
                text-align: center;
            }

            .action-btn svg {
                width: 16px !important;
                height: 16px !important;
                margin: 0 !important;
            }

            .action-btn span {
                line-height: 1.1;
                font-size: 0.625rem;
                display: block;
            }

            .mobile-toggle-btn {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                min-height: 48px;
            }

            .stats-row .col-lg-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 992px) {
            .mobile-toggle-btn {
                display: flex !important;
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
            padding: 0.875rem 1rem;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            min-height: 48px;
        }

        .mobile-toggle-btn:hover {
            background: #f9fafb;
        }

        .mobile-toggle-btn:active {
            background: #f3f4f6;
            transform: scale(0.99);
        }

        .mobile-toggle-btn i {
            transition: transform 0.3s ease;
        }

        .mobile-toggle-btn.collapsed i:last-child {
            transform: rotate(-90deg);
        }

        /* Touch improvements */
        @media (hover: none) and (pointer: coarse) {
            .stat-card:active {
                transform: scale(0.98);
            }

            .action-btn:active {
                transform: scale(0.96);
            }

            .booking-card:active {
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
            }
        }

        /* View Toggle Styles */
        .view-toggle-btn {
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .view-toggle-btn.active {
            background: white !important;
            color: #667eea !important;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .view-toggle-btn:not(.active) {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }

        .view-toggle-btn:not(.active):hover {
            background: rgba(255, 255, 255, 0.3) !important;
        }

        /* Table View Styles */
        .table-view-container {
            display: none;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .table-view-container.active {
            display: block;
        }

        .bookings-list.active {
            display: block;
        }

        .bookings-list:not(.active) {
            display: none;
        }

        .bookings-table {
            width: 100%;
            margin: 0;
        }

        .bookings-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .bookings-table thead th {
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
            color: white !important;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bookings-table thead th:last-child {
            border-right: none;
        }

        .bookings-table thead .group-header {
            background: rgba(0, 0, 0, 0.15);
            font-size: 0.813rem;
            font-weight: 700;
            padding: 0.75rem 1rem;
            text-align: center;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .bookings-table thead .sub-header {
            font-size: 0.688rem;
            padding: 0.75rem 0.5rem;
            text-align: center;
        }

        .bookings-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .bookings-table tbody tr:hover {
            background: #f9fafb;
        }

        .bookings-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .bookings-table tbody td .mb-1 {
            margin-bottom: 0.5rem;
        }

        .bookings-table tbody td .small {
            font-size: 0.813rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-bottom: 0.25rem;
        }

        .bookings-table tbody td .small:last-child {
            margin-bottom: 0;
        }

        .bookings-table tbody td .small i {
            flex-shrink: 0;
        }

        .bookings-table .table-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .bookings-table .table-action-btn {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            color: white;
            text-decoration: none;
            white-space: nowrap;
        }

        .bookings-table .table-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .bookings-table .table-action-btn.btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .bookings-table .table-action-btn.btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .bookings-table .table-action-btn.btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bookings-table .table-action-btn.btn-orange {
            background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%);
        }

        .bookings-table .table-action-btn.btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        /* Responsive Table */
        @media (max-width: 1200px) {
            .table-view-container {
                overflow-x: auto;
            }

            .bookings-table {
                min-width: 1000px;
            }
        }

        @media (max-width: 768px) {
            .view-toggle-btn span {
                display: none !important;
            }

            .view-toggle-btn {
                padding: 0.5rem 0.75rem;
            }
        }
    </style>

    <div class="page-content">
        <!-- Header -->
        <div class="bookings-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">Tour Bookings</h2>
                    <p class="mb-0 opacity-90">Manage confirmed tour bookings and reservations</p>
                </div>
                <div class="d-flex gap-2 flex-column flex-sm-row align-items-stretch align-items-sm-center">
                    <!-- View Toggle -->
                    <div class="btn-group" role="group" aria-label="View Toggle">
                        <button type="button" class="btn btn-light btn-lg view-toggle-btn active" data-view="box">
                            <i data-feather="grid"></i> <span class="d-none d-md-inline">Box View</span>
                        </button>
                        <button type="button" class="btn btn-light btn-lg view-toggle-btn" data-view="table">
                            <i data-feather="list"></i> <span class="d-none d-md-inline">Table View</span>
                        </button>
                    </div>
                    <a href="{{ route('bookings.calendar') }}" class="btn btn-light btn-lg">
                        <i data-feather="calendar"></i> <span class="d-none d-md-inline">Calendar</span>
                    </a>
                    <a href="{{ route('bookings.trash') }}" class="btn btn-outline-light btn-lg">
                        <i data-feather="trash-2"></i> <span class="d-none d-md-inline">Trash</span>
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Mobile Filter Toggle -->
        <button class="mobile-toggle-btn collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#filterSection">
            <span><i data-feather="filter" style="width: 16px; height: 16px;"></i> Filters</span>
            <i data-feather="chevron-down" style="width: 20px; height: 20px;"></i>
        </button>

        <!-- Search & Filters -->
        <div class="search-card collapse show" id="filterSection">
            <form method="GET" action="{{ route('bookings.index') }}">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">Booking Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Not
                                Started
                            </option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Ongoing
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">
                            <i data-feather="dollar-sign" style="width: 14px; height: 14px;"></i>
                            Payment Status
                        </label>
                        <select name="payment_status" class="form-select">
                            <option value="">All Payments</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid (₹0
                                Paid)
                            </option>
                            <option value="partially_paid"
                                {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid (Due
                                Remaining)
                            </option>
                            <option value="fully_paid" {{ request('payment_status') == 'fully_paid' ? 'selected' : '' }}>
                                Fully Paid</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">
                            <i data-feather="calendar" style="width: 14px; height: 14px;"></i>
                            Service Date Range
                        </label>
                        <div class="input-group">
                            <input type="date" name="service_date_from" class="form-control form-control-sm"
                                value="{{ request('service_date_from') }}" placeholder="From">
                            <span class="input-group-text px-2" style="font-size: 0.75rem;">to</span>
                            <input type="date" name="service_date_to" class="form-control form-control-sm"
                                value="{{ request('service_date_to') }}" placeholder="To">
                        </div>
                        @if (request('service_date_from') || request('service_date_to'))
                            <small class="text-muted d-block mt-1">
                                @if (request('service_date_from') && request('service_date_to'))
                                    {{ \Carbon\Carbon::parse(request('service_date_from'))->format('d M') }} -
                                    {{ \Carbon\Carbon::parse(request('service_date_to'))->format('d M Y') }}
                                @elseif(request('service_date_from'))
                                    From {{ \Carbon\Carbon::parse(request('service_date_from'))->format('d M Y') }}
                                @else
                                    Until {{ \Carbon\Carbon::parse(request('service_date_to'))->format('d M Y') }}
                                @endif
                            </small>
                        @endif
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">
                            <i data-feather="alert-circle" style="width: 14px; height: 14px;"></i>
                            Due Amount
                        </label>
                        <select name="has_due" class="form-select">
                            <option value="">All Bookings</option>
                            <option value="1" {{ request('has_due') == '1' ? 'selected' : '' }}>With Due Amount
                            </option>
                            <option value="0" {{ request('has_due') == '0' ? 'selected' : '' }}>No Due Amount</option>
                        </select>
                    </div>

                    @if (auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']))
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-semibold">
                                <i data-feather="user-check" style="width: 14px; height: 14px;"></i>
                                Created By Staff
                            </label>
                            <select name="staff_id" class="form-select">
                                <option value="">All Staff</option>
                                @foreach ($staffList as $staff)
                                    <option value="{{ $staff->id }}"
                                        {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">
                            <i data-feather="package" style="width: 14px; height: 14px;"></i>
                            Service Type
                        </label>
                        <select name="service_type" class="form-select">
                            <option value="">All Service Types</option>
                            @foreach ($serviceTypes as $serviceType)
                                <option value="{{ strtolower($serviceType->name) }}"
                                    {{ request('service_type') == strtolower($serviceType->name) ? 'selected' : '' }}>
                                    {{ $serviceType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Search</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Booking number, customer name..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="search" style="width: 16px; height: 16px;"></i>
                            </button>
                            @if (request('search') ||
                                    request('status') ||
                                    request('payment_status') ||
                                    request('has_due') ||
                                    request('staff_id') ||
                                    request('service_type') ||
                                    request('service_date_from') ||
                                    request('service_date_to'))
                                <a href="{{ route('bookings.index') }}" class="btn btn-outline-danger"
                                    title="Clear Filters">
                                    <i data-feather="x" style="width: 16px; height: 16px;"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Mobile Stats Toggle -->
        <button class="mobile-toggle-btn collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#statsSection">
            <span><i data-feather="bar-chart-2" style="width: 16px; height: 16px;"></i> Statistics</span>
            <i data-feather="chevron-down" style="width: 20px; height: 20px;"></i>
        </button>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4 stats-row collapse show" id="statsSection">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('bookings.index') }}" class="text-decoration-none">
                    <div class="stat-card total">
                        <div class="stat-icon" style="background: #ede9fe;">
                            <i data-feather="calendar" style="color: #667eea; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Total</div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('bookings.index') }}?status=not_started" class="text-decoration-none">
                    <div class="stat-card not-started">
                        <div class="stat-icon" style="background: #f3f4f6;">
                            <i data-feather="circle" style="color: #6b7280; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Not Started</div>
                        <div class="stat-value">{{ $stats['not_started'] }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('bookings.index') }}?status=confirmed" class="text-decoration-none">
                    <div class="stat-card confirmed">
                        <div class="stat-icon" style="background: #dbeafe;">
                            <i data-feather="check" style="color: #3b82f6; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Confirmed</div>
                        <div class="stat-value">{{ $stats['confirmed'] }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('bookings.index') }}?status=in_progress" class="text-decoration-none">
                    <div class="stat-card in-progress">
                        <div class="stat-icon" style="background: #fef3c7;">
                            <i data-feather="clock" style="color: #f59e0b; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Ongoing</div>
                        <div class="stat-value">{{ $stats['in_progress'] }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('bookings.index') }}?status=completed" class="text-decoration-none">
                    <div class="stat-card completed">
                        <div class="stat-icon" style="background: #d1fae5;">
                            <i data-feather="check-circle" style="color: #10b981; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Completed</div>
                        <div class="stat-value">{{ $stats['completed'] }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('bookings.index') }}?status=cancelled" class="text-decoration-none">
                    <div class="stat-card cancelled">
                        <div class="stat-icon" style="background: #fee2e2;">
                            <i data-feather="x-circle" style="color: #ef4444; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Cancelled</div>
                        <div class="stat-value">{{ $stats['cancelled'] }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('bookings.index') }}?has_due=1" class="text-decoration-none">
                    <div class="stat-card" style="border-left-color: #f97316;">
                        <div class="stat-icon" style="background: #ffedd5;">
                            <i data-feather="alert-circle" style="color: #f97316; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">With Due</div>
                        <div class="stat-value">{{ $stats['with_due'] }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Bookings List - Box View -->
        <div class="bookings-list active">
            @forelse ($bookings as $booking)
                <div class="booking-card">
                    <div class="booking-header">
                        <div>
                            <div class="booking-number">{{ $booking->booking_number }}</div>
                            <div class="customer-name">
                                <i data-feather="user" style="width: 14px; height: 14px;"></i>
                                {{ $booking->lead->guest_name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            @if ($booking->booking_status == 'not_started')
                                <span class="badge-modern bg-secondary">Not Started</span>
                            @elseif($booking->booking_status == 'confirmed')
                                <span class="badge-modern bg-primary">Confirmed</span>
                            @elseif($booking->booking_status == 'in_progress')
                                <span class="badge-modern bg-info">Ongoing</span>
                            @elseif($booking->booking_status == 'completed')
                                <span class="badge-modern bg-success">Completed</span>
                            @else
                                <span class="badge-modern bg-danger">Cancelled</span>
                            @endif
                        </div>
                    </div>


                    <div class="booking-info">
                        <div class="info-item">
                            <div class="info-label">Guest Name</div>
                            <div class="info-value">{{ $booking->lead->guest_name ?? 'N/A' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Contact & Pax</div>
                            <div class="info-value">
                                <i data-feather="phone" style="width: 14px; height: 14px;"></i>
                                {{ $booking->lead->contact ?? 'N/A' }}
                                <span class="ms-2">
                                    <i data-feather="users" style="width: 14px; height: 14px;"></i>
                                    {{ $booking->lead->pax ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Booking Date</div>
                            <div class="info-value">{{ $booking->booking_date->format('d M Y') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Created By</div>
                            <div class="info-value">
                                <i data-feather="user-check" style="width: 14px; height: 14px;"></i>
                                {{ $booking->createdBy->name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Payment Details</div>
                            <div class="info-value d-flex gap-2 flex-wrap">
                                <span class="badge bg-primary"
                                    style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">Total:
                                    ₹{{ number_format($booking->total_amount, 0) }}</span>
                                <span class="badge bg-success" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">Paid:
                                    ₹{{ number_format($booking->paid_amount, 0) }}</span>
                                <span class="badge bg-warning text-dark"
                                    style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">Due:
                                    ₹{{ number_format($booking->pending_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="actions">
                        <a href="{{ route('bookings.show', $booking->id) }}" class="action-btn btn-info"
                            title="View Details">
                            <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                            <span>View Details</span>
                        </a>

                        <a href="{{ route('bookings.edit', $booking->id) }}" class="action-btn"
                            style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);" title="Edit Booking">
                            <i data-feather="edit" style="width: 16px; height: 16px;"></i>
                            <span>Edit</span>
                        </a>

                        @if ($booking->is_gst_invoice)
                            <!-- GST Invoice Button -->
                            <a href="{{ route('booking.gst-invoice', $booking->id) }}" class="action-btn"
                                style="background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%);"
                                title="Download GST Invoice" target="_blank">
                                <i data-feather="file-text" style="width: 16px; height: 16px;"></i>
                                <span>GST Invoice</span>
                            </a>
                        @else
                            <!-- Booking Confirmation Button -->
                            <a href="{{ route('booking.invoice', $booking->id) }}" class="action-btn"
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"
                                title="Download Booking Confirmation" target="_blank">
                                <i data-feather="file-text" style="width: 16px; height: 16px;"></i>
                                <span>Booking Confirmation</span>
                            </a>
                        @endif

                        <a href="{{ route('booking.report.view', $booking->id) }}" class="action-btn"
                            style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);"
                            title="View Booking Report">
                            <i data-feather="file-text" style="width: 16px; height: 16px;"></i>
                            <span>Report</span>
                        </a>

                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn deleteBtn"
                                style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;"
                                title="Delete Booking" data-name="{{ $booking->booking_number }}">
                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>

                    @if ($booking->is_gst_invoice)
                        <div style="margin-top: 8px;">
                            <span class="badge"
                                style="background: linear-gradient(135deg, #FF6600 0%, #ff8533 100%); color: white; font-size: 10px;">
                                GST: {{ $booking->gst_invoice_number }}
                            </span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h4>No Bookings Found</h4>
                    <p>Try adjusting your search filters or check back later.</p>
                </div>
            @endforelse
        </div>

        <!-- Bookings List - Table View -->
        <div class="table-view-container">
            @if ($bookings->count() > 0)
                <table class="bookings-table table">
                    <thead>
                        <!-- Group Headers Row -->
                        <tr>
                            <th class="group-header">Booking Details</th>
                            <th class="group-header">Customer Details</th>
                            <th class="group-header">Status</th>
                            <th class="group-header">Payment Details</th>
                            <th class="group-header">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <!-- Booking Details (Merged) -->
                                <td>
                                    <div class="mb-1">
                                        <strong>{{ $booking->booking_number }}</strong>
                                        @if ($booking->is_gst_invoice)
                                            <br><small class="text-muted">GST: {{ $booking->gst_invoice_number }}</small>
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        <i data-feather="user-check" style="width: 12px; height: 12px;"></i>
                                        {{ $booking->createdBy->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-muted small">
                                        <i data-feather="calendar" style="width: 12px; height: 12px;"></i>
                                        {{ $booking->booking_date->format('d M Y') }}
                                    </div>
                                </td>

                                <!-- Customer Details (Merged) -->
                                <td>
                                    <div class="mb-1">
                                        <strong>{{ $booking->lead->guest_name ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="text-muted small">
                                        <i data-feather="phone" style="width: 12px; height: 12px;"></i>
                                        {{ $booking->lead->contact ?? 'N/A' }}
                                    </div>
                                    <div class="text-muted small">
                                        <i data-feather="users" style="width: 12px; height: 12px;"></i>
                                        {{ $booking->lead->pax ?? 'N/A' }} Pax
                                    </div>
                                </td>

                                <!-- Status -->
                                <td>
                                    @if ($booking->booking_status == 'not_started')
                                        <span class="badge bg-secondary">Not Started</span>
                                    @elseif($booking->booking_status == 'confirmed')
                                        <span class="badge bg-primary">Confirmed</span>
                                    @elseif($booking->booking_status == 'in_progress')
                                        <span class="badge bg-info">Ongoing</span>
                                    @elseif($booking->booking_status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>

                                <!-- Payment Details (Merged) -->
                                <td>
                                    <div class="mb-1">
                                        <span class="badge bg-primary" style="font-size: 0.75rem;">
                                            Total: ₹{{ number_format($booking->total_amount, 0) }}
                                        </span>
                                    </div>
                                    <div class="mb-1">
                                        <span class="badge bg-success" style="font-size: 0.75rem;">
                                            Paid: ₹{{ number_format($booking->paid_amount, 0) }}
                                        </span>
                                    </div>
                                    <div>
                                        @if ($booking->pending_amount > 0)
                                            <span class="badge bg-warning text-dark" style="font-size: 0.75rem;">
                                                Due: ₹{{ number_format($booking->pending_amount, 0) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.75rem;">
                                                Fully Paid
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('bookings.show', $booking->id) }}"
                                            class="table-action-btn btn-info" title="View Booking Details"
                                            data-bs-toggle="tooltip">
                                            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <a href="{{ route('bookings.edit', $booking->id) }}"
                                            class="table-action-btn btn-warning" title="Edit Booking"
                                            data-bs-toggle="tooltip">
                                            <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        @if ($booking->is_gst_invoice)
                                            <a href="{{ route('booking.gst-invoice', $booking->id) }}"
                                                class="table-action-btn btn-orange" title="Download GST Invoice"
                                                target="_blank" data-bs-toggle="tooltip">
                                                <i data-feather="file-text" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('booking.invoice', $booking->id) }}"
                                                class="table-action-btn btn-success" title="Download Booking Confirmation"
                                                target="_blank" data-bs-toggle="tooltip">
                                                <i data-feather="file-text" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('booking.report.view', $booking->id) }}"
                                            class="table-action-btn btn-info" title="View Booking Report"
                                            data-bs-toggle="tooltip">
                                            <i data-feather="file-text" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="table-action-btn btn-danger deleteBtn"
                                                title="Delete Booking" data-name="{{ $booking->booking_number }}"
                                                data-bs-toggle="tooltip">
                                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h4>No Bookings Found</h4>
                    <p>Try adjusting your search filters or check back later.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($bookings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {!! $bookings->appends(request()->query())->links() !!}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // View Toggle Functionality
            const viewToggleBtns = document.querySelectorAll('.view-toggle-btn');
            const boxView = document.querySelector('.bookings-list');
            const tableView = document.querySelector('.table-view-container');

            // Load saved view preference from localStorage
            const savedView = localStorage.getItem('bookingsView') || 'box';
            switchView(savedView);

            viewToggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const view = this.getAttribute('data-view');
                    switchView(view);
                    localStorage.setItem('bookingsView', view);
                });
            });

            function switchView(view) {
                viewToggleBtns.forEach(btn => {
                    if (btn.getAttribute('data-view') === view) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });

                if (view === 'box') {
                    boxView.classList.add('active');
                    tableView.classList.remove('active');
                } else {
                    boxView.classList.remove('active');
                    tableView.classList.add('active');
                }

                // Re-initialize feather icons after view switch
                setTimeout(() => {
                    feather.replace();
                    // Initialize Bootstrap tooltips for table action buttons
                    initializeTooltips();
                }, 100);
            }

            // Initialize Bootstrap Tooltips
            function initializeTooltips() {
                // Dispose all existing tooltips first
                const existingTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                existingTooltips.forEach(function(el) {
                    const tooltip = bootstrap.Tooltip.getInstance(el);
                    if (tooltip) {
                        tooltip.dispose();
                    }
                });

                // Initialize new tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl, {
                        placement: 'top',
                        trigger: 'hover focus',
                        html: false
                    });
                });
            }

            // Initialize tooltips on page load
            initializeTooltips();

            // Handle collapse events for chevron rotation
            const filterSection = document.getElementById('filterSection');
            const statsSection = document.getElementById('statsSection');

            if (filterSection) {
                filterSection.addEventListener('show.bs.collapse', function() {
                    const btn = document.querySelector('[data-bs-target="#filterSection"]');
                    if (btn) {
                        btn.classList.remove('collapsed');
                        const chevron = btn.querySelector('i:last-child');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                });

                filterSection.addEventListener('hide.bs.collapse', function() {
                    const btn = document.querySelector('[data-bs-target="#filterSection"]');
                    if (btn) {
                        btn.classList.add('collapsed');
                        const chevron = btn.querySelector('i:last-child');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });
            }

            if (statsSection) {
                statsSection.addEventListener('show.bs.collapse', function() {
                    const btn = document.querySelector('[data-bs-target="#statsSection"]');
                    if (btn) {
                        btn.classList.remove('collapsed');
                        const chevron = btn.querySelector('i:last-child');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                });

                statsSection.addEventListener('hide.bs.collapse', function() {
                    const btn = document.querySelector('[data-bs-target="#statsSection"]');
                    if (btn) {
                        btn.classList.add('collapsed');
                        const chevron = btn.querySelector('i:last-child');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });
            }

            setTimeout(() => {
                feather.replace();
            }, 100);
        });
    </script>
@endsection
