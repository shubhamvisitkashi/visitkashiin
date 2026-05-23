@extends('admin.layouts.app')

@section('content')
    <style>
        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .calendar-header h2 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .calendar-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .legend {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            flex-shrink: 0;
        }

        /* FullCalendar Button Styles */
        .fc-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            color: white !important;
            padding: 8px 16px !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s ease !important;
        }

        .fc-button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
        }

        .fc-button:active,
        .fc-button-active {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%) !important;
        }

        .fc-button .fc-icon {
            color: white !important;
            font-size: 16px !important;
        }

        .fc-toolbar-title {
            font-size: 24px !important;
            font-weight: 700 !important;
            color: #111827 !important;
        }

        /* Calendar Event Styles */
        .fc-event {
            border: none !important;
            padding: 4px 8px !important;
            margin: 2px 4px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            border-radius: 4px !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }

        .fc-event:hover {
            transform: scale(1.02) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
            z-index: 10 !important;
        }

        .fc-event-title {
            font-weight: 600 !important;
        }

        /* Day cell borders */
        .fc-daygrid-day {
            border: 1px solid #e5e7eb !important;
        }

        .fc-daygrid-day-frame {
            min-height: 100px;
        }

        /* Event Content Styles */
        .event-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .event-title {
            font-weight: 600;
            font-size: 13px;
        }

        .event-meta {
            font-size: 11px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .legacy-badge-inline {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            padding: 2px 6px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Modal Styles */
        .lead-modal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .lead-modal .modal-title {
            font-weight: 700;
        }

        .lead-modal .btn-close {
            filter: brightness(0) invert(1);
        }

        .legacy-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 12px;
        }

        .info-section {
            margin-bottom: 24px;
        }

        .info-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 600;
        }

        .payment-summary-modal {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .payment-grid-modal {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .payment-item-modal {
            background: white;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
        }

        .payment-label-modal {
            font-size: 10px;
            color: #92400e;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .payment-value-modal {
            font-size: 18px;
            font-weight: 700;
        }

        .payment-value-modal.total {
            color: #667eea;
        }

        .payment-value-modal.paid {
            color: #10b981;
        }

        .payment-value-modal.due {
            color: #ef4444;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-btn.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .action-btn.btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .calendar-header {
                padding: 1.25rem 1rem;
                margin-bottom: 1rem;
            }

            .calendar-header h2 {
                font-size: 1.25rem;
                margin-bottom: 0.25rem;
            }

            .calendar-header p {
                font-size: 0.813rem;
            }

            .calendar-header .btn {
                padding: 0.5rem 0.875rem;
                font-size: 0.813rem;
            }

            .calendar-container {
                padding: 1rem;
                border-radius: 8px;
            }

            .legend {
                gap: 8px;
                padding: 12px;
            }

            .legend-item {
                font-size: 11px;
                gap: 6px;
            }

            .legend-color {
                width: 16px;
                height: 16px;
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
            }

            .event-title {
                font-size: 11px;
            }

            .event-meta {
                font-size: 9px;
            }

            .legacy-badge-inline {
                font-size: 8px;
                padding: 1px 4px;
            }

            /* Modal adjustments */
            .info-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .payment-grid-modal {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .action-btn {
                padding: 8px 12px;
                font-size: 12px;
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .calendar-header h2 {
                font-size: 1.063rem;
            }

            .calendar-header .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }

            .calendar-container {
                padding: 0.75rem;
            }

            .legend {
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
                min-height: 28px !important;
            }

            .action-btn {
                min-height: 44px !important;
            }
        }
    </style>

    <div class="page-content">
        <div class="calendar-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2>Leads Calendar</h2>
                    <p>View all leads by their travel dates</p>
                </div>
                <a href="{{ route('lead.index') }}" class="btn btn-light">
                    <i data-feather="list"></i> <span class="d-none d-sm-inline">Back to List View</span>
                </a>
            </div>
        </div>

        <div class="calendar-container">
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: #10b981;"></div>
                    <span>Complete</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #3b82f6;"></div>
                    <span>Confirm</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #f59e0b;"></div>
                    <span>Follow-up</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ef4444;"></div>
                    <span>Cancel</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #f59e0b; border: 2px solid #d97706;"></div>
                    <span>Legacy</span>
                </div>
            </div>

            <div id="calendar"></div>
        </div>
    </div>

    <!-- Lead Details Modal -->
    <div class="modal fade lead-modal" id="leadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadModalTitle">Lead Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="leadModalBody">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // Detect if mobile
            const isMobile = window.innerWidth < 768;

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: isMobile ? 'listWeek' : 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: isMobile ? '' : 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: function(info, successCallback, failureCallback) {
                    const url = '{{ route('leads.calendar.events') }}';

                    fetch(url + '?start=' + info.startStr + '&end=' + info.endStr)
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data);
                        })
                        .catch(error => {
                            console.error('Error loading events:', error);
                            failureCallback(error);
                        });
                },
                eventClick: function(info) {
                    showLeadDetails(info.event);
                },
                eventContent: function(arg) {
                    let props = arg.event.extendedProps;
                    let html = '<div class="event-content">';
                    html += '<div class="event-title">' + arg.event.title + '</div>';

                    if (!isMobile || window.innerWidth > 576) {
                        html += '<div class="event-meta">';
                        if (props.is_legacy) {
                            html += '<span class="legacy-badge-inline">🗄️</span>';
                        }
                        html += '<span>👤 ' + (props.pax || 'N/A') + '</span>';
                        html += '</div>';
                    }

                    html += '</div>';
                    return {
                        html: html
                    };
                },
                eventDidMount: function(info) {
                    feather.replace();
                },
                height: 'auto',
                aspectRatio: isMobile ? 1.2 : 1.8,
                // Mobile-specific settings
                dayMaxEvents: isMobile ? 2 : true,
                moreLinkClick: 'popover',
                navLinks: true,
                editable: false,
                selectable: false
            });

            calendar.render();

            // Handle window resize
            window.addEventListener('resize', function() {
                calendar.updateSize();
            });

            function showLeadDetails(event) {
                const props = event.extendedProps;

                let title = props.guest_name;
                if (props.is_legacy) {
                    title +=
                        '<span class="legacy-indicator"><i data-feather="database" style="width: 14px; height: 14px;"></i> Old System</span>';
                }

                document.getElementById('leadModalTitle').innerHTML = title;

                let html = '';

                // Guest Information
                html += '<div class="info-section">';
                html += '<div class="info-section-title">Guest Information</div>';
                html += '<div class="info-grid">';
                html +=
                    '<div class="info-item"><div class="info-label">Contact</div><div class="info-value"><a href="tel:' +
                    (props.contact || '') + '">' + (props.contact || 'N/A') + '</a></div></div>';
                html += '<div class="info-item"><div class="info-label">Pax</div><div class="info-value">' + (props
                    .pax || 'N/A') + '</div></div>';
                html += '<div class="info-item"><div class="info-label">Lead Source</div><div class="info-value">' +
                    props.lead_source + '</div></div>';
                html +=
                    '<div class="info-item"><div class="info-label">Booking Status</div><div class="info-value"><span class="badge bg-primary">' +
                    props.booking_status + '</span></div></div>';
                html +=
                    '<div class="info-item"><div class="info-label">Payment Status</div><div class="info-value"><span class="badge bg-info">' +
                    props.payment_status + '</span></div></div>';
                html += '</div>';
                html += '</div>';

                // Payment Summary
                if (props.total_amount > 0) {
                    html += '<div class="info-section">';
                    html += '<div class="info-section-title">Payment Summary</div>';
                    html += '<div class="payment-summary-modal">';
                    html += '<div class="payment-grid-modal">';
                    html +=
                        '<div class="payment-item-modal"><div class="payment-label-modal">Total</div><div class="payment-value-modal total">₹' +
                        props.total_amount.toLocaleString() + '</div></div>';
                    html +=
                        '<div class="payment-item-modal"><div class="payment-label-modal">Paid</div><div class="payment-value-modal paid">₹' +
                        props.paid_amount.toLocaleString() + '</div></div>';
                    html +=
                        '<div class="payment-item-modal"><div class="payment-label-modal">Due</div><div class="payment-value-modal due">₹' +
                        props.pending_amount.toLocaleString() + '</div></div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                }

                // Short Plan
                if (props.short_plan) {
                    html += '<div class="info-section">';
                    html += '<div class="info-section-title">Short Plan</div>';
                    html +=
                        '<div style="background: #f9fafb; padding: 12px; border-radius: 6px; white-space: pre-wrap;">' +
                        props.short_plan + '</div>';
                    html += '</div>';
                }

                // Actions
                html += '<div class="info-section">';
                html += '<div class="d-flex flex-column flex-sm-row gap-2">';

                if (props.is_legacy) {
                    html += '<a href="/admin/lead-legacy-details/' + props.lead_id +
                        '" class="action-btn btn-warning"><i data-feather="eye" style="width: 14px; height: 14px;"></i> View Details</a>';
                } else {
                    html += '<a href="/admin/lead/' + props.lead_id +
                        '" class="action-btn btn-primary"><i data-feather="eye" style="width: 14px; height: 14px;"></i> View Lead</a>';
                }

                html += '<a href="/admin/lead/' + props.lead_id +
                    '/edit" class="action-btn btn-primary"><i data-feather="edit" style="width: 14px; height: 14px;"></i> Edit</a>';
                html += '</div>';
                html += '</div>';

                document.getElementById('leadModalBody').innerHTML = html;

                // Re-initialize feather icons
                feather.replace();

                // Show modal
                var modal = new bootstrap.Modal(document.getElementById('leadModal'));
                modal.show();
            }
        });
    </script>
@endsection
