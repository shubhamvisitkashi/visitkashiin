@extends('admin.layouts.app')

@section('content')
    <style>
        /* Modern Leads Page Styling */
        .leads-header {
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

        .stat-card.complete {
            border-left-color: #10b981;
        }

        .stat-card.confirm {
            border-left-color: #3b82f6;
        }

        .stat-card.follow-up {
            border-left-color: #f59e0b;
        }

        .stat-card.cancel {
            border-left-color: #ef4444;
        }

        .stat-card.revenue {
            border-left-color: #059669;
        }

        .stat-card.business {
            border-left-color: #8b5cf6;
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

        .lead-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            border-left: 4px solid #e5e7eb;
        }

        .lead-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left-color: #667eea;
        }

        .lead-card .lead-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .lead-card .lead-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .lead-card .lead-contact {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .lead-card .lead-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .lead-card .info-item {
            display: flex;
            flex-direction: column;
        }

        .lead-card .info-label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .lead-card .info-value {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .lead-card .actions {
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

        .action-btn.btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .action-btn.btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }

        .action-btn.btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .action-btn.btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
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

        /* Old System Data Badge */
        .old-system-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
        }

        .old-system-badge i {
            width: 12px;
            height: 12px;
        }

        /* Old Payment Data Section */
        .old-payment-section {
            margin-top: 12px;
            padding: 12px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }

        .old-payment-section .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .old-payment-section .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .old-payment-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .old-payment-item {
            text-align: center;
            padding: 8px;
            background: white;
            border-radius: 6px;
            border: 1px solid #fbbf24;
        }

        .old-payment-item .label {
            font-size: 10px;
            color: #92400e;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .old-payment-item .value {
            font-size: 14px;
            font-weight: 700;
            color: #78350f;
        }

        .old-payment-item.total .value {
            color: #667eea;
        }

        .old-payment-item.paid .value {
            color: #10b981;
        }

        .old-payment-item.due .value {
            color: #ef4444;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .leads-header {
                padding: 1.25rem 1rem;
                margin-bottom: 1rem;
            }

            .leads-header h2 {
                font-size: 1.25rem;
                margin-bottom: 0.25rem;
            }

            .leads-header p {
                font-size: 0.813rem;
            }

            .leads-header .btn {
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                white-space: nowrap;
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
                padding: 0.625rem 0.75rem;
                min-height: 44px;
                /* Better touch target */
            }

            .search-card .btn {
                min-height: 44px;
                /* Better touch target */
            }

            .stat-card {
                padding: 0.625rem;
                min-height: 70px;
            }

            .stat-card .stat-icon {
                width: 24px;
                height: 24px;
                margin-bottom: 0.25rem;
            }

            .stat-card .stat-icon i {
                width: 14px !important;
                height: 14px !important;
            }

            .stat-card .stat-label {
                font-size: 0.625rem;
                margin-bottom: 0.125rem;
            }

            .stat-card .stat-value {
                font-size: 1.125rem;
            }

            /* Mobile toggle button - always visible on mobile */
            .mobile-toggle-btn {
                display: flex !important;
            }

            .lead-card {
                padding: 1rem;
                margin-bottom: 0.875rem;
                position: relative;
                /* Swipe indicator */
                background: linear-gradient(90deg, transparent 0%, white 10%, white 100%);
            }

            .lead-card::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 4px;
                background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
                border-radius: 4px 0 0 4px;
            }

            .lead-card .lead-name {
                font-size: 1.063rem;
                margin-bottom: 0.25rem;
                line-height: 1.3;
            }

            .lead-card .lead-contact {
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                gap: 0.375rem;
            }

            /* Click-to-call on mobile */
            .lead-card .lead-contact a {
                color: #667eea;
                text-decoration: none;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                padding: 0.25rem 0.5rem;
                border-radius: 6px;
                transition: all 0.2s;
            }

            .lead-card .lead-contact a:active {
                background: #ede9fe;
                transform: scale(0.98);
            }

            .lead-card .lead-header {
                margin-bottom: 0.875rem;
            }

            .lead-card .lead-info {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                margin-bottom: 0.875rem;
            }

            .lead-card .info-label {
                font-size: 0.688rem;
                margin-bottom: 0.188rem;
            }

            .lead-card .info-value {
                font-size: 0.813rem;
                line-height: 1.4;
            }

            .lead-card .actions {
                gap: 0.5rem;
                display: flex;
                flex-wrap: wrap;
            }

            .action-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
                gap: 0.25rem;
                min-height: 38px;
                justify-content: center;
                border-radius: 8px;
                flex: 0 1 auto;
            }

            .action-btn svg {
                width: 14px !important;
                height: 14px !important;
            }

            .badge-modern {
                padding: 0.313rem 0.625rem;
                font-size: 0.688rem;
            }

            .empty-state {
                padding: 2.5rem 1rem;
            }

            .empty-state svg {
                width: 64px;
                height: 64px;
            }

            .empty-state h4 {
                font-size: 1.063rem;
            }

            .empty-state p {
                font-size: 0.875rem;
            }

            /* Old payment section mobile */
            .old-payment-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .old-payment-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: left;
            }
        }

        @media (max-width: 576px) {
            .leads-header h2 {
                font-size: 1.125rem;
            }

            .leads-header .btn {
                padding: 0.5rem 0.875rem;
                font-size: 0.813rem;
            }

            .leads-header .btn svg {
                width: 14px !important;
                height: 14px !important;
            }

            .stat-card {
                padding: 0.5rem;
                min-height: 60px;
            }

            .stat-card .stat-value {
                font-size: 1rem;
            }

            .stat-card .stat-icon {
                width: 20px;
                height: 20px;
            }

            .stat-card .stat-icon i {
                width: 12px !important;
                height: 12px !important;
            }

            .stat-card .stat-label {
                font-size: 0.563rem;
            }

            .lead-card .lead-info {
                grid-template-columns: 1fr;
                gap: 0.625rem;
            }

            .lead-card .info-item {
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #f3f4f6;
            }

            .lead-card .info-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .action-btn {
                flex: 0 1 auto;
                justify-content: center;
                padding: 0.5rem 0.625rem;
                font-size: 0.688rem;
                min-height: 40px;
                white-space: nowrap;
            }

            .action-btn span {
                display: inline;
            }

            .action-btn svg {
                margin: 0 !important;
                width: 14px !important;
                height: 14px !important;
            }

            .mobile-toggle-btn {
                display: flex;
                align-items: center;
                justify-content: space-between;
                min-height: 48px;
            }

            /* Stats in 2 columns on mobile */
            .stats-row .col-lg-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            /* Better badge wrapping */
            .lead-card .lead-header>div:last-child {
                width: 100%;
                margin-top: 0.5rem;
            }

            /* Horizontal scroll for badges */
            .lead-card .lead-header .d-flex.gap-2 {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .lead-card .lead-header .d-flex.gap-2::-webkit-scrollbar {
                display: none;
            }
        }

        /* Show toggle buttons on tablet and mobile */
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

        .mobile-toggle-btn:hover { background: #f9fafb; }
        .mobile-toggle-btn:active { background: #f3f4f6; transform: scale(0.99); }
        .mobile-toggle-btn i { transition: transform 0.3s ease; }
        .mobile-toggle-btn.collapsed i:last-child { transform: rotate(-90deg); }

        @media (hover: none) and (pointer: coarse) {
            .stat-card:active { transform: scale(0.98); }
        }

        /* ── Compact Lead Rows ── */
        .lead-list-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 3px rgba(15,23,42,.05);
            overflow: hidden;
        }
        .lead-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-bottom: 1px solid #F1F5F9;
            transition: background 0.15s;
            cursor: pointer;
        }
        .lead-row:last-child { border-bottom: none; }
        .lead-row:hover { background: #F8FAFF; }

        /* serial number */
        .lead-row .lr-num {
            width: 26px; height: 26px;
            background: #EEF2FF; color: #4F46E5;
            border-radius: 7px;
            font-size: .7rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* name + phone */
        .lead-row .lr-info { flex: 1; min-width: 0; }
        .lead-row .lr-name {
            font-size: .875rem; font-weight: 600; color: #0F172A;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            line-height: 1.3;
        }
        .lead-row .lr-phone {
            font-size: .75rem; color: #64748B;
            display: flex; align-items: center; gap: 4px;
            margin-top: 1px;
        }
        .lead-row .lr-phone svg { width:11px; height:11px; flex-shrink:0; }

        /* pax pill */
        .lr-pax {
            display: inline-flex; align-items: center; gap: 4px;
            background: #F0FDF4; color: #15803D;
            border: 1px solid #BBF7D0;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: .72rem; font-weight: 700;
            white-space: nowrap; flex-shrink: 0;
        }
        .lr-pax svg { width:11px; height:11px; }

        /* added-by + date chips */
        .lr-meta {
            display: flex; flex-direction: column; gap: 3px;
            flex-shrink: 0; min-width: 90px;
        }
        .lr-meta-item {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .7rem; color: #64748B; white-space: nowrap;
        }
        .lr-meta-item svg { width:10px; height:10px; flex-shrink:0; }
        .lr-meta-item strong { color: #334155; font-weight: 600; }
        @media(max-width:768px) { .lr-meta { display: none; } }

        /* status dot */
        .lr-status {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }
        .lr-status.complete  { background: #10B981; }
        .lr-status.confirm   { background: #3B82F6; }
        .lr-status.follow-up { background: #F59E0B; }
        .lr-status.cancel    { background: #EF4444; }
        .lr-status.new       { background: #94A3B8; }

        /* action buttons */
        .lr-actions { display: flex; gap: 4px; flex-shrink: 0; }
        .lr-btn {
            width: 30px; height: 30px;
            border-radius: 7px; border: 1px solid transparent;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background 0.15s, border-color 0.15s;
            text-decoration: none; background: transparent;
        }
        .lr-btn svg { width:14px; height:14px; }
        .lr-btn.edit  { color: #F59E0B; border-color: #FDE68A; }
        .lr-btn.edit:hover  { background: #FEF3C7; color: #D97706; }
        .lr-btn.delete { color: #EF4444; border-color: #FECACA; }
        .lr-btn.delete:hover { background: #FEE2E2; color: #DC2626; }
        .lr-btn.view  { color: #4F46E5; border-color: #C7D2FE; }
        .lr-btn.view:hover  { background: #EEF2FF; }

        @media(max-width:576px) {
            .lead-row { padding: 10px 12px; gap: 9px; }
            .lead-row .lr-num { display: none; }
        }
    </style>

    <div class="page-content">
        <!-- Header -->
        <div class="leads-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">{{ $page_title }}</h2>
                    <p class="mb-0 opacity-90">Manage and track all your customer leads</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('leads.calendar') }}" class="btn btn-light btn-lg">
                        <i data-feather="calendar"></i> Calendar View
                    </a>
                    @can('booking-create')
                        <a href="{{ route('bookings.create-direct') }}" class="btn btn-light btn-lg">
                            <i data-feather="plus"></i> New Booking
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Mobile Filter Toggle -->
        <button class="mobile-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection">
            <span><i data-feather="filter" style="width: 16px; height: 16px;"></i> Filters</span>
            <i data-feather="chevron-down" style="width: 20px; height: 20px;"></i>
        </button>

        <!-- Search & Filters -->
        <div class="search-card collapse" id="filterSection">
            <form action="{{ route('lead.index') }}" method="GET" id="searchForm">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">Date Range</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i data-feather="calendar" style="width: 16px; height: 16px;"></i>
                            </span>
                            <input type="text" name="daterange" placeholder="Select dates..." value="{{ $search_date }}"
                                class="form-control border-start-0" id="daterangePicker">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold">Source</label>
                        <select class="form-select" name="source_id">
                            <option value="">All Sources</option>
                            @foreach (App\Models\LeadSource::orderBy('name')->get() as $source)
                                <option value="{{ $source->id }}" {{ $source->id == $source_id ? 'selected' : '' }}>
                                    {{ $source->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold">Service Type</label>
                        <select class="form-select" name="search_service_type_id">
                            <option value="">All Service Types</option>
                            @foreach (App\Models\ServiceType::oldest('name')->get() as $service)
                                <option value="{{ $service->id }}"
                                    {{ $service->id == ($search_service_type_id ?? '') ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if (auth()->id() == 1)
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label small fw-semibold">Staff</label>
                            <select class="form-select" name="search_staff_id">
                                <option value="">All Staff</option>
                                @foreach (App\Models\Admin\Admin::oldest('name')->get() as $staff)
                                    <option value="{{ $staff->id }}"
                                        {{ $staff->id == $search_staff_id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold">Search</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Name or phone..."
                                value="{{ $search }}">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="search" style="width: 16px; height: 16px;"></i>
                            </button>
                            @if ($search_date || $search)
                                <a href="{{ route('lead.index') }}" class="btn btn-outline-danger" title="Clear">
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
        <div class="row g-3 mb-4 stats-row collapse" id="statsSection">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('lead.index') }}?search={{ $search }}&daterange={{ $search_date }}"
                    class="text-decoration-none">
                    <div class="stat-card total">
                        <div class="stat-icon" style="background: #ede9fe;">
                            <i data-feather="users" style="color: #667eea; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Total Leads</div>
                        <div class="stat-value">{{ $total_lead }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('lead.index') }}?status=complete&search={{ $search }}&daterange={{ $search_date }}"
                    class="text-decoration-none">
                    <div class="stat-card complete">
                        <div class="stat-icon" style="background: #d1fae5;">
                            <i data-feather="check-circle" style="color: #10b981; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Completed</div>
                        <div class="stat-value">{{ $total_complete }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('lead.index') }}?status=confirm&search={{ $search }}&daterange={{ $search_date }}"
                    class="text-decoration-none">
                    <div class="stat-card confirm">
                        <div class="stat-icon" style="background: #dbeafe;">
                            <i data-feather="check" style="color: #3b82f6; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Confirmed</div>
                        <div class="stat-value">{{ $total_confirm }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('lead.index') }}?status=follow up&search={{ $search }}&daterange={{ $search_date }}"
                    class="text-decoration-none">
                    <div class="stat-card follow-up">
                        <div class="stat-icon" style="background: #fef3c7;">
                            <i data-feather="clock" style="color: #f59e0b; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Follow-up</div>
                        <div class="stat-value">{{ $total_follow_up }}</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('lead.index') }}?status=cancel&search={{ $search }}&daterange={{ $search_date }}"
                    class="text-decoration-none">
                    <div class="stat-card cancel">
                        <div class="stat-icon" style="background: #fee2e2;">
                            <i data-feather="x-circle" style="color: #ef4444; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Cancelled</div>
                        <div class="stat-value">{{ $total_cancel }}</div>
                    </div>
                </a>
            </div>

            @if (auth()->id() == 1)
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="stat-card revenue">
                        <div class="stat-icon" style="background: #d1fae5;">
                            <i data-feather="dollar-sign" style="color: #059669; width: 18px; height: 18px;"></i>
                        </div>
                        <div class="stat-label">Revenue</div>
                        <div class="stat-value">₹{{ number_format($total_revenue, 0) }}</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Leads List -->
        @if ($list->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h4>No Leads Found</h4>
                <p>Try adjusting your search filters or add a new lead to get started.</p>
            </div>
        @else
            <div class="lead-list-card">
                @foreach ($list as $key => $data)
                    @php
                        $statusClass = match($data->booking_status) {
                            'complete'  => 'complete',
                            'confirm'   => 'confirm',
                            'follow up' => 'follow-up',
                            'cancel'    => 'cancel',
                            default     => 'new',
                        };
                        $latestBooking = $data->bookings()->latest()->first();
                    @endphp
                    <div class="lead-row" onclick="window.location='{{ route('lead.details', $data->id) }}'">

                        {{-- Serial --}}
                        <div class="lr-num">{{ $list->firstItem() + $loop->index }}</div>

                        {{-- Status dot --}}
                        <span class="lr-status {{ $statusClass }}" title="{{ ucfirst($data->booking_status ?? 'New') }}"></span>

                        {{-- Name + Phone --}}
                        <div class="lr-info">
                            <div class="lr-name">{{ $data->guest_name }}</div>
                            <div class="lr-phone">
                                <i data-feather="phone"></i>
                                {{ $data->contact }}
                            </div>
                        </div>

                        {{-- Pax --}}
                        <span class="lr-pax">
                            <i data-feather="users"></i>
                            {{ $data->pax ?? '–' }}
                        </span>

                        {{-- Added By + Date --}}
                        <div class="lr-meta">
                            <span class="lr-meta-item">
                                <i data-feather="user"></i>
                                <strong>{{ $data->getAddedBy->name ?? 'N/A' }}</strong>
                            </span>
                            <span class="lr-meta-item">
                                <i data-feather="calendar"></i>
                                {{ $data->created_at ? $data->created_at->format('d M Y') : '–' }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="lr-actions" onclick="event.stopPropagation()">
                            {{-- View / Quick-book --}}
                            @if ($latestBooking)
                                <a href="{{ route('bookings.show', $latestBooking->id) }}"
                                   class="lr-btn view" title="View Booking">
                                    <i data-feather="external-link"></i>
                                </a>
                            @else
                                <a href="{{ route('quick-booking.form', $data->id) }}"
                                   class="lr-btn view" title="Quick Book">
                                    <i data-feather="zap"></i>
                                </a>
                            @endif

                            @can('lead-edit')
                                <a href="{{ route('lead.edit', $data->id) }}"
                                   class="lr-btn edit" title="Edit">
                                    <i data-feather="edit-2"></i>
                                </a>
                            @endcan

                            @can('lead-delete')
                                <form action="{{ route('lead.destroy', $data->id) }}" method="POST"
                                      id="delete_form_{{ $data->id }}" style="display:contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="lr-btn delete" title="Delete"
                                            onclick="confirmDeleteLead({{ $data->id }}, '{{ addslashes($data->guest_name) }}')">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        @if ($list->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {!! $list->appends(request()->query())->links() !!}
            </div>
        @endif
    </div>

    <script>
        // Confirm delete lead - Global function for inline onclick handlers
        // This must be in global scope to be accessible from inline onclick attributes
        window.confirmDeleteLead = function(id, guestName) {
            Swal.fire({
                title: 'Delete Lead?',
                html: `<p>Are you sure you want to delete lead for <strong>${guestName}</strong>?</p><p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>`,
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
        };

        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

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

            // Re-initialize feather icons after any DOM changes
            setTimeout(() => {
                feather.replace();
            }, 100);
        });
    </script>
@endsection
