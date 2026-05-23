@extends('admin.layouts.app')

@section('content')
    <style>
        .calendar-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 24px;
        }

        .calendar-header {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            color: white;
            padding: 20px 24px;
            border-radius: 12px 12px 0 0;
            margin: -24px -24px 24px -24px;
            box-shadow: 0 4px 15px rgba(8, 145, 178, 0.25);
        }

        .calendar-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .calendar-header p {
            margin: 4px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        #calendar {
            margin-top: 20px;
        }

        /* FullCalendar Customization */
        .fc {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            color: #1f2937;
        }

        .fc-button {
            background: #0891b2 !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 8px 16px !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
            transition: all 0.3s ease !important;
            color: white !important;
            box-shadow: 0 2px 4px rgba(8, 145, 178, 0.3) !important;
        }

        .fc-button .fc-icon {
            color: white !important;
            font-size: 16px !important;
        }

        .fc-button:hover {
            background: #0e7490 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.4) !important;
            color: white !important;
        }

        .fc-button:active,
        .fc-button-active {
            background: #155e75 !important;
            color: white !important;
        }

        .fc-button:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
        }

        /* Ensure icons are visible */
        .fc-icon-chevron-left:before,
        .fc-icon-chevron-right:before {
            color: white !important;
            font-weight: bold !important;
        }

        .fc-daygrid-day {
            transition: background-color 0.2s ease;
        }

        .fc-daygrid-day:hover {
            background-color: #f9fafb;
        }

        .fc-daygrid-day-number {
            font-weight: 500;
            color: #374151;
            padding: 8px;
        }

        .fc-event {
            border-radius: 6px;
            padding: 6px 8px !important;
            margin: 2px 4px !important;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none !important;
            min-height: 50px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            z-index: 10 !important;
        }

        /* Prevent events from crossing day borders */
        .fc-daygrid-event {
            margin: 2px 4px !important;
            white-space: normal !important;
        }

        .fc-daygrid-event-harness {
            margin-bottom: 2px !important;
        }

        /* Multi-day events */
        .fc-event-start,
        .fc-event-end {
            border-radius: 6px !important;
        }

        .fc-event-start:not(.fc-event-end) {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            margin-right: 0 !important;
            padding-right: 4px !important;
        }

        .fc-event-end:not(.fc-event-start) {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            margin-left: 0 !important;
            padding-left: 4px !important;
        }

        .fc-event:not(.fc-event-start):not(.fc-event-end) {
            border-radius: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
        }

        /* Day cell borders */
        .fc-daygrid-day {
            border: 1px solid #e5e7eb !important;
        }

        .fc-daygrid-day-frame {
            min-height: 100px;
        }

        /* Custom Event Content */
        .fc-event-main {
            padding: 0 !important;
        }

        .event-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .event-title {
            font-weight: 600;
            font-size: 13px;
            line-height: 1.2;
        }

        .event-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            font-size: 11px;
            opacity: 0.95;
        }

        .event-services {
            display: flex;
            gap: 3px;
            align-items: center;
        }

        .event-service-icon {
            width: 14px;
            height: 14px;
            stroke-width: 2.5;
            opacity: 0.9;
        }

        .event-creator {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 10px;
            opacity: 0.85;
        }

        .event-creator svg {
            width: 12px;
            height: 12px;
        }

        .fc-day-today {
            background-color: #fef3c7 !important;
        }

        /* Legend */
        .calendar-legend {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
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
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }

        .stat-card.confirmed {
            border-color: #10b981;
        }

        .stat-card.in-progress {
            border-color: #3b82f6;
        }

        .stat-card.completed {
            border-color: #8b5cf6;
        }

        .stat-card.cancelled {
            border-color: #ef4444;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Loading State */
        .calendar-loading {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .calendar-loading i {
            font-size: 48px;
            margin-bottom: 16px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .calendar-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white !important;
            text-decoration: none;
        }

        .action-btn i {
            width: 18px !important;
            height: 18px !important;
            stroke-width: 2.5;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white !important;
        }

        .action-btn.btn-outline-primary {
            background: white;
            color: #0891b2 !important;
            border: 2px solid #0891b2;
        }

        .action-btn.btn-outline-primary:hover {
            background: #0891b2;
            color: white !important;
        }

        .action-btn.btn-primary {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        }

        .action-btn.btn-outline-secondary {
            background: white;
            color: #6b7280 !important;
            border: 2px solid #e5e7eb;
        }

        .action-btn.btn-outline-secondary:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Service Type Icons */
        .service-types {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 12px 0;
        }

        .service-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .service-type-badge i {
            width: 14px;
            height: 14px;
            color: #667eea;
        }

        /* Payment Progress */
        .payment-progress {
            margin: 16px 0;
        }

        .payment-progress-bar {
            height: 24px;
            background: #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        .payment-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            font-weight: 700;
        }

        .payment-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .payment-stat {
            text-align: center;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .payment-stat-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .payment-stat-value {
            font-size: 16px;
            font-weight: 700;
        }

        .payment-stat-value.total {
            color: #667eea;
        }

        .payment-stat-value.paid {
            color: #10b981;
        }

        .payment-stat-value.due {
            color: #f59e0b;
        }

        /* Creator Info */
        .creator-info {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            border-radius: 8px;
            margin-top: 12px;
            border-left: 3px solid #667eea;
        }

        .creator-info i {
            width: 16px;
            height: 16px;
            color: #667eea;
        }

        .creator-info span {
            font-size: 13px;
            color: #4c1d95;
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .calendar-container {
                padding: 1rem;
                border-radius: 8px;
            }

            .calendar-header {
                padding: 1.25rem 1rem;
                margin: -1rem -1rem 1rem -1rem;
            }

            .calendar-header h4 {
                font-size: 1.125rem;
            }

            .calendar-header p {
                font-size: 0.813rem;
            }

            .calendar-actions {
                gap: 0.5rem;
            }

            .action-btn {
                padding: 0.625rem 0.875rem;
                font-size: 0.813rem;
                flex: 1;
            }

            .action-btn i {
                width: 14px !important;
                height: 14px !important;
            }

            .calendar-legend {
                gap: 8px;
                padding: 12px;
            }

            .legend-item {
                font-size: 11px;
                gap: 6px;
            }

            .legend-color {
                width: 14px;
                height: 14px;
            }

            /* Compact calendar toolbar */
            .fc-toolbar {
                flex-direction: column !important;
                gap: 12px !important;
            }

            .fc-toolbar-chunk {
                display: flex !important;
                justify-content: center !important;
            }

            .fc-toolbar-title {
                font-size: 1.125rem !important;
                margin: 8px 0 !important;
            }

            .fc-button {
                padding: 6px 12px !important;
                font-size: 0.75rem !important;
            }

            .fc-button .fc-icon {
                font-size: 14px !important;
            }

            /* Smaller day cells */
            .fc-daygrid-day-frame {
                min-height: 60px !important;
            }

            .fc-daygrid-day-number {
                font-size: 0.875rem !important;
                padding: 4px !important;
            }

            /* Compact events */
            .fc-event {
                padding: 2px 4px !important;
                margin: 1px 2px !important;
                font-size: 10px !important;
                min-height: 35px !important;
            }

            .event-title {
                font-size: 11px;
            }

            .event-meta {
                font-size: 9px;
            }

            .event-service-icon {
                width: 12px !important;
                height: 12px !important;
            }

            /* Modal adjustments */
            .modal-body .row {
                flex-direction: column;
            }

            .modal-body .col-md-6 {
                max-width: 100%;
            }

            .payment-stats {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .service-types {
                gap: 6px;
            }

            .service-type-badge {
                font-size: 11px;
                padding: 5px 10px;
            }
        }

        @media (max-width: 576px) {
            .calendar-header h4 {
                font-size: 1rem;
            }

            .calendar-header p {
                font-size: 0.75rem;
            }

            .action-btn {
                padding: 0.625rem 0.75rem;
                font-size: 0.75rem;
            }

            .calendar-legend {
                padding: 10px;
                gap: 6px;
            }

            .legend-item {
                font-size: 10px;
                flex: 0 0 calc(50% - 3px);
            }

            .fc-toolbar-title {
                font-size: 1rem !important;
            }

            .fc-button {
                padding: 5px 10px !important;
                font-size: 0.688rem !important;
            }

            /* Hide view switcher on very small screens */
            .fc-toolbar-chunk:last-child {
                display: none !important;
            }

            /* Smaller events */
            .fc-daygrid-day-frame {
                min-height: 50px !important;
            }

            .fc-event {
                font-size: 9px !important;
                padding: 1px 3px !important;
                min-height: 30px !important;
            }

            .event-title {
                font-size: 10px;
            }

            .event-meta {
                display: none;
                /* Hide meta on very small screens */
            }
        }

        /* Touch improvements */
        @media (hover: none) and (pointer: coarse) {
            .fc-button {
                min-height: 44px !important;
                min-width: 44px !important;
            }

            .fc-event {
                min-height: 35px !important;
            }

            .action-btn {
                min-height: 44px !important;
            }
        }
    </style>

    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <!-- Quick Actions -->
                <div class="calendar-actions">
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary action-btn">
                        <i data-feather="list"></i> List View
                    </a>
                    <button type="button" class="btn btn-primary action-btn" onclick="calendar.today()">
                        <i data-feather="calendar"></i> Today
                    </button>
                    <button type="button" class="btn btn-outline-secondary action-btn" onclick="refreshCalendar()">
                        <i data-feather="refresh-cw"></i> Refresh
                    </button>
                </div>

                <!-- Service Type Filter -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #374151; font-size: 0.95rem;">
                        <i data-feather="filter" style="width: 16px; height: 16px;"></i>
                        Filter by Service Type
                    </label>
                    <select id="serviceTypeFilter" class="form-select" style="max-width: 300px;">
                        <option value="">All Services</option>
                        @foreach ($serviceTypes as $serviceType)
                            <option value="{{ strtolower($serviceType->name) }}">
                                {{ $serviceType->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">
                        <i data-feather="info" style="width: 12px; height: 12px;"></i>
                        Filter calendar events by service type
                    </small>
                </div>

                <!-- Calendar Container -->
                <div class="calendar-container">
                    <div class="calendar-header">
                        <h4><i data-feather="calendar"></i> Booking Calendar</h4>
                        <p>View and manage all your bookings in calendar format</p>
                    </div>

                    <!-- Loading State -->
                    <div id="calendarLoading" class="calendar-loading" style="display: none;">
                        <i data-feather="loader"></i>
                        <p>Loading calendar...</p>
                    </div>

                    <!-- Calendar -->
                    <div id="calendar"></div>

                    <!-- Legend -->
                    <div class="calendar-legend">
                        <div style="width: 100%; margin-bottom: 8px;">
                            <strong style="color: #374151; font-size: 0.95rem;">
                                <i data-feather="palette" style="width: 14px; height: 14px;"></i>
                                Service Type Colors
                            </strong>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #FF6B35;"></div>
                            <span>🏨 Hotel</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #4A90E2;"></div>
                            <span>🚗 Transport</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #00B4D8;"></div>
                            <span>✈️ Flight</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #7209B7;"></div>
                            <span>🚂 Train</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #06D6A0;"></div>
                            <span>👨‍🦯 Guide</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #FF6F91;"></div>
                            <span>🍽️ Meals</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #00C9A7;"></div>
                            <span>🎯 Activity</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #9B59B6;"></div>
                            <span>👁️ Sightseeing</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #1E88E5;"></div>
                            <span>⛵ Boat</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #6366F1;"></div>
                            <span>🔀 Multiple</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #EF4444;"></div>
                            <span>❌ Cancelled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="eventModalTitle">
                        <i data-feather="info"></i> Booking Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a id="viewBookingBtn" href="#" class="btn btn-primary">
                        <i data-feather="eye"></i> View Full Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            // Detect if mobile
            const isMobile = window.innerWidth < 768;

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: isMobile ? 'listWeek' : 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: isMobile ? '' : 'dayGridMonth,dayGridWeek,listWeek'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week',
                    list: 'List'
                },
                height: 'auto',
                aspectRatio: isMobile ? 1.2 : 1.8,
                events: function(info, successCallback, failureCallback) {
                    const serviceType = document.getElementById('serviceTypeFilter').value;
                    const url = new URL('{{ route('bookings.calendar.events') }}', window.location
                        .origin);
                    url.searchParams.append('start', info.startStr);
                    url.searchParams.append('end', info.endStr);
                    if (serviceType) {
                        url.searchParams.append('service_type', serviceType);
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            failureCallback(error);
                        });
                },
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                eventContent: function(arg) {
                    const props = arg.event.extendedProps;

                    // Create service icons HTML
                    let serviceIconsHtml = '';
                    if (props.service_types && props.service_types.length > 0 && !isMobile) {
                        const iconsToShow = props.service_types.slice(0, 3); // Show max 3 icons
                        serviceIconsHtml = iconsToShow.map(type =>
                            `<i data-feather="${type.icon}" class="event-service-icon"></i>`
                        ).join('');
                    }

                    // Create custom HTML
                    const customHtml = `
                        <div class="event-content">
                            <div class="event-title">${arg.event.title}</div>
                            ${(!isMobile || window.innerWidth > 576) ? `
                                                                                                            <div class="event-meta">
                                                                                                                ${serviceIconsHtml ? `<div class="event-services">${serviceIconsHtml}</div>` : ''}
                                                                                                                <div class="event-creator">
                                                                                                                    <i data-feather="user" style="width: 11px; height: 11px;"></i>
                                                                                                                    <span>${props.created_by}</span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        ` : ''}
                        </div>
                    `;

                    return {
                        html: customHtml
                    };
                },
                loading: function(isLoading) {
                    document.getElementById('calendarLoading').style.display = isLoading ? 'block' :
                        'none';
                    document.getElementById('calendar').style.display = isLoading ? 'none' : 'block';
                },
                eventDidMount: function(info) {
                    // Replace feather icons in the event only
                    try {
                        const icons = info.el.querySelectorAll('[data-feather]');
                        icons.forEach(icon => {
                            const iconName = icon.getAttribute('data-feather');
                            if (iconName && feather.icons[iconName]) {
                                icon.innerHTML = feather.icons[iconName].toSvg();
                            }
                        });
                    } catch (error) {
                        console.warn('Error replacing feather icons:', error);
                    }

                    // Add tooltip
                    info.el.title = info.event.extendedProps.booking_number + ' - ' +
                        info.event.extendedProps.guest_name;
                },
                // Mobile-specific settings
                dayMaxEvents: isMobile ? 2 : true,
                moreLinkClick: 'popover',
                navLinks: true,
                editable: false,
                selectable: false
            });

            calendar.render();
            feather.replace();

            // Handle window resize
            window.addEventListener('resize', function() {
                calendar.updateSize();
            });

            // Service type filter change handler
            document.getElementById('serviceTypeFilter').addEventListener('change', function() {
                refreshCalendar();
            });
        });

        function showEventDetails(event) {
            const props = event.extendedProps;

            // Build service types badges with colors
            let serviceTypesBadges = '';
            if (props.service_types && props.service_types.length > 0) {
                serviceTypesBadges = props.service_types.map(type => {
                    const color = type.color ? type.color.background : '#6366F1';
                    return `<div class="service-type-badge" style="background: linear-gradient(135deg, ${color}15 0%, ${color}25 100%); border-left: 3px solid ${color}; padding: 8px 12px; border-radius: 6px; margin-bottom: 8px;">
                        <i data-feather="${type.icon}" style="width: 16px; height: 16px; color: ${color}; margin-right: 8px;"></i>
                        <span style="color: ${color}; font-weight: 600; font-size: 13px;">${type.name}</span>
                    </div>`;
                }).join('');
            }

            // Determine status badge color
            const statusColors = {
                'Confirmed': '#10b981',
                'In Progress': '#3b82f6',
                'Completed': '#8b5cf6',
                'Cancelled': '#ef4444',
                'Not Started': '#6b7280'
            };
            const statusColor = statusColors[props.status] || '#6b7280';

            const modalBody = `
                <div style="padding: 8px;">
                    <!-- Header Card -->
                    <div style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); padding: 20px; border-radius: 12px; margin-bottom: 20px; color: white; box-shadow: 0 4px 15px rgba(8, 145, 178, 0.25);">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <div style="font-size: 12px; opacity: 0.9; margin-bottom: 4px;">BOOKING NUMBER</div>
                                <div style="font-size: 24px; font-weight: 700;">${props.booking_number}</div>
                            </div>
                            <div style="text-align: right;">
                                <span style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <i data-feather="calendar" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                                    ${props.service_date}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Info Card -->
                    <div style="background: #f9fafb; padding: 16px; border-radius: 10px; margin-bottom: 16px; border: 1px solid #e5e7eb;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            <div>
                                <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">
                                    <i data-feather="user" style="width: 12px; height: 12px;"></i> Guest Name
                                </div>
                                <div style="font-size: 16px; font-weight: 600; color: #1f2937;">${props.guest_name}</div>
                            </div>
                            <div>
                                <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">
                                    <i data-feather="phone" style="width: 12px; height: 12px;"></i> Contact
                                </div>
                                <div style="font-size: 16px; font-weight: 600; color: #1f2937;">${props.contact}</div>
                            </div>
                            <div>
                                <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">
                                    <i data-feather="users" style="width: 12px; height: 12px;"></i> Persons
                                </div>
                                <div style="font-size: 16px; font-weight: 600; color: #1f2937;">${props.pax} Person(s)</div>
                            </div>
                            <div>
                                <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">
                                    <i data-feather="activity" style="width: 12px; height: 12px;"></i> Status
                                </div>
                                <span style="background: ${statusColor}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                    ${props.status}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Services Section -->
                    ${serviceTypesBadges ? `
                                                                <div style="margin-bottom: 16px;">
                                                                    <div style="font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 12px; display: flex; align-items: center;">
                                                                        <i data-feather="package" style="width: 16px; height: 16px; margin-right: 8px; color: #0891b2;"></i>
                                                                        Services Included
                                                                    </div>
                                                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px;">
                                                                        ${serviceTypesBadges}
                                                                    </div>
                                                                </div>
                                                            ` : ''}

                    <!-- Services List -->
                    ${props.services !== 'No services' ? `
                                                                <div style="background: #fffbeb; border-left: 3px solid #f59e0b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                                                                    <div style="font-size: 12px; font-weight: 600; color: #92400e; margin-bottom: 4px;">DETAILED SERVICES</div>
                                                                    <div style="font-size: 13px; color: #78350f; line-height: 1.6;">${props.services}</div>
                                                                </div>
                                                            ` : ''}

                    <!-- Payment Section -->
                    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 16px; border-radius: 10px; margin-bottom: 16px; border: 1px solid #86efac;">
                        <div style="font-size: 14px; font-weight: 700; color: #166534; margin-bottom: 12px; display: flex; align-items: center;">
                            <i data-feather="credit-card" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                            Payment Information
                        </div>
                        
                        <!-- Progress Bar -->
                        <div style="background: #e5e7eb; height: 32px; border-radius: 16px; overflow: hidden; margin-bottom: 12px; position: relative;">
                            <div style="background: linear-gradient(90deg, #10b981 0%, #059669 100%); height: 100%; width: ${props.payment_percentage}%; display: flex; align-items: center; justify-content: center; transition: width 0.3s ease;">
                                <span style="color: white; font-weight: 700; font-size: 13px; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                    ${props.payment_percentage}% Paid
                                </span>
                            </div>
                        </div>

                        <!-- Payment Stats -->
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                            <div style="text-align: center; background: white; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                                <div style="font-size: 11px; color: #6b7280; font-weight: 600; margin-bottom: 4px;">TOTAL</div>
                                <div style="font-size: 18px; font-weight: 700; color: #667eea;">₹${props.total_amount.toLocaleString('en-IN')}</div>
                            </div>
                            <div style="text-align: center; background: white; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                                <div style="font-size: 11px; color: #6b7280; font-weight: 600; margin-bottom: 4px;">PAID</div>
                                <div style="font-size: 18px; font-weight: 700; color: #10b981;">₹${props.paid_amount.toLocaleString('en-IN')}</div>
                            </div>
                            <div style="text-align: center; background: white; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                                <div style="font-size: 11px; color: #6b7280; font-weight: 600; margin-bottom: 4px;">DUE</div>
                                <div style="font-size: 18px; font-weight: 700; color: ${props.due_amount > 0 ? '#f59e0b' : '#10b981'};">₹${props.due_amount.toLocaleString('en-IN')}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tour Plan -->
                    ${props.short_plan && props.short_plan !== 'N/A' ? `
                                                                <div style="background: #f3f4f6; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                                                                    <div style="font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 6px;">
                                                                        <i data-feather="map" style="width: 12px; height: 12px;"></i> TOUR PACKAGE
                                                                    </div>
                                                                    <div style="font-size: 13px; color: #1f2937; line-height: 1.5;">${props.short_plan}</div>
                                                                </div>
                                                            ` : ''}

                    <!-- Created By -->
                    <div style="background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%); padding: 10px 14px; border-radius: 8px; display: flex; align-items: center; border-left: 3px solid #0891b2;">
                        <i data-feather="user-check" style="width: 16px; height: 16px; color: #0891b2; margin-right: 8px;"></i>
                        <span style="font-size: 13px; color: #164e63; font-weight: 600;">Booked by: ${props.created_by}</span>
                    </div>
                </div>
            `;

            document.getElementById('eventModalBody').innerHTML = modalBody;
            document.getElementById('viewBookingBtn').href = '/admin/bookings/' + event.id;

            const modal = new bootstrap.Modal(document.getElementById('eventModal'));
            modal.show();

            // Re-initialize feather icons
            setTimeout(() => feather.replace(), 100);
        }

        function refreshCalendar() {
            calendar.refetchEvents();
        }
    </script>
@endsection
