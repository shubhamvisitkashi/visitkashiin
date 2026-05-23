@extends('admin.layouts.app')

@section('content')
    <style>
        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .calendar-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }

        /* FullCalendar Customization */
        .fc {
            font-family: Arial, sans-serif;
        }

        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.85rem;
        }

        .fc-daygrid-event {
            white-space: normal !important;
            align-items: normal !important;
        }

        .fc-event-title {
            font-weight: 600;
        }

        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
        }

        .fc-button {
            background: #667eea !important;
            border-color: #667eea !important;
            text-transform: capitalize !important;
        }

        .fc-button:hover {
            background: #5568d3 !important;
            border-color: #5568d3 !important;
        }

        .fc-button-active {
            background: #4c51bf !important;
            border-color: #4c51bf !important;
        }

        /* Legend */
        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        /* Event Details Modal */
        .event-detail-row {
            display: flex;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .event-detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .event-detail-label {
            font-weight: 600;
            min-width: 120px;
            color: #6b7280;
        }

        .event-detail-value {
            color: #111827;
        }
    </style>

    <div class="page-content">
        <!-- Header -->
        <div class="calendar-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">📅 Service Calendar</h2>
                    <p class="mb-0 opacity-90">View all service assignments by date</p>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background: #3b82f6;"></div>
                <span>Hotel/Accommodation</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #10b981;"></div>
                <span>Cab/Transport</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #f59e0b;"></div>
                <span>Guide</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #ef4444;"></div>
                <span>Meal/Food</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #8b5cf6;"></div>
                <span>Boat</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #6b7280;"></div>
                <span>Other Services</span>
            </div>
        </div>

        <!-- Calendar -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Service Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventDetails">
                    <!-- Event details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,listWeek'
                },
                events: function(info, successCallback, failureCallback) {
                    fetch('{{ route('service-calendar.events') }}?start=' + info.startStr + '&end=' +
                            info.endStr)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => failureCallback(error));
                },
                eventClick: function(info) {
                    var event = info.event;
                    var props = event.extendedProps;

                    var detailsHtml = `
                        <div class="event-detail-row">
                            <div class="event-detail-label">Service:</div>
                            <div class="event-detail-value"><strong>${props.service}</strong></div>
                        </div>
                        <div class="event-detail-row">
                            <div class="event-detail-label">Date:</div>
                            <div class="event-detail-value">${event.start.toLocaleDateString('en-IN', { 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}</div>
                        </div>
                        <div class="event-detail-row">
                            <div class="event-detail-label">Booking:</div>
                            <div class="event-detail-value">${props.booking}</div>
                        </div>
                        <div class="event-detail-row">
                            <div class="event-detail-label">Guest:</div>
                            <div class="event-detail-value">${props.guest}</div>
                        </div>
                        <div class="event-detail-row">
                            <div class="event-detail-label">Provider:</div>
                            <div class="event-detail-value">${props.provider}</div>
                        </div>
                        <div class="event-detail-row">
                            <div class="event-detail-label">Cost:</div>
                            <div class="event-detail-value"><strong>${props.cost}</strong></div>
                        </div>
                        ${props.notes ? `
                            <div class="event-detail-row">
                                <div class="event-detail-label">Notes:</div>
                                <div class="event-detail-value">${props.notes}</div>
                            </div>
                            ` : ''}
                    `;

                    document.getElementById('eventDetails').innerHTML = detailsHtml;
                    var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                },
                eventDidMount: function(info) {
                    // Add tooltip
                    info.el.title = info.event.title + ' - ' + info.event.extendedProps.guest;
                },
                height: 'auto',
                contentHeight: 'auto',
                aspectRatio: 1.8
            });

            calendar.render();

            // Refresh calendar when window is resized
            window.addEventListener('resize', function() {
                calendar.updateSize();
            });
        });
    </script>
@endsection
