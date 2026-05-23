@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 row">
            <div class="col-12">
                @if(auth()->guard('admin')->user()->id === 1)
                    <h2 class="fw-bold text-primary mb-1">🚢 {{$page_title}} Management</h2>
                    <p class="text-muted mb-0">Manage your boat bookings efficiently</p>
                    <div class="mt-2">
                        <span class="badge bg-primary fs-6 px-3 py-2 mb-2">
                            <i class="fas fa-rupee-sign me-1"></i>
                            Total Amount: ₹{{ number_format($total_amount, 2) }}
                        </span>
                        <span class="badge bg-info fs-6 px-3 py-2 mb-2">
                            <i class="fas fa-rupee-sign me-1"></i>
                            Total Discount: ₹{{ number_format($total_discount_amount, 2) }}
                        </span>
                        <span class="badge bg-success fs-6 px-3 py-2 mb-2">
                            <i class="fas fa-rupee-sign me-1"></i>
                            Total Payment: ₹{{ number_format($total_final_amount, 2) }}
                        </span>
                        <span class="badge bg-warning fs-6 px-3 py-2 mb-2">
                            <i class="fas fa-rupee-sign me-1"></i>
                            Total Collection: ₹{{ number_format($total_payments, 2) }}
                        </span>
                        <span class="badge bg-danger fs-6 px-3 py-2 mb-2">
                            <i class="fas fa-rupee-sign me-1"></i>
                            Total Due: ₹{{ number_format(($total_final_amount - $total_payments), 2) }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="col-12 text-end">
                <a href="{{route('boat-booking.create')}}" class="btn btn-primary btn-lg">
                    <i data-feather="plus"></i>New Booking
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-ship fa-2x"></i>
                        </div>
                        <h4 class="text-primary mb-0">{{ $total_persons }}</h4>
                        <small class="text-muted" style="font-size: 20px;">Total Bookings</small>
                    </div>
                </div>
            </div>
            @foreach ($boat_type_stats as $boat_type_stat)
                <div class="col-md-3 mb-2">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="fas fa-ship fa-2x"></i>
                            </div>
                            <h4 class="text-primary mb-0">Seat {{ $boat_type_stat[0]['total_persons'] }} Booked </h4>
                            <h4 class="text-primary mb-0">Seat {{ ($boat_type_stat[0]['boat']['total_available_boat'] *  $boat_type_stat[0]['boat']['no_of_seat']) - $boat_type_stat[0]['total_persons'] }} Available </h4>
                            <h4 class="text-primary mb-0">Seat {{ ($boat_type_stat[0]['boat']['total_available_boat'] *  $boat_type_stat[0]['boat']['no_of_seat']) }} Total </h4>
                            <small class="text-muted" style="font-size: 20px;">{{$boat_type_stat[0]['boat']['event_type']}} {{$boat_type_stat[0]['boat']->boatType?->name}}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!-- Main Data Table -->
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow">
                    <!-- Search Header -->
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <h6 class="mb-3 text-white"><i class="fas fa-filter me-2"></i>Search & Filter</h6>
                        <form action="{{route('boat-booking.index')}}" method="GET">
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <select name="search_boat_type" class="form-select form-select-sm">
                                        <option value="">All Boat Types</option>
                                        @foreach($boat_types as $boat_type)
                                            <option value="{{$boat_type->id}}" @if($search_boat_type == $boat_type->id) selected @endif>
                                                {{$boat_type->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="search_event_type" class="form-select form-select-sm">
                                        <option value="">All Event Types</option>
                                        @foreach($event_types as $event_type)
                                            <option value="{{$event_type}}" @if($search_event_type == $event_type) selected @endif>{{$event_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="search_date" placeholder="Select Date..." value="{{ $search_date }}" class="form-control form-control-sm daterange" id="daterangePicker">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="search_user" class="form-control form-control-sm" placeholder="Name, phone, email..." value="{{ $search_user }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="search_booking_id" class="form-control form-control-sm" placeholder="Booking ID..." value="{{ $search_booking_id }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="search_payment_status" class="form-select form-select-sm">
                                        <option value="">All</option>
                                        <option value="paid" @if($search_payment_status === 'paid') selected @endif>Paid</option>
                                        <option value="partial" @if($search_payment_status === 'partial') selected @endif>Due</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-light btn-sm">
                                            <i data-feather="search"></i>
                                        </button>
                                        <a href="{{route('boat-booking.index')}}" class="btn btn-outline-light btn-sm">
                                            <i data-feather="x-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        @if(auth()->guard('admin')->user()->id === 1)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 align-items-center">
                                        <button type="button" id="exportSelectedBtn" class="btn btn-success btn-sm" disabled>
                                            <i data-feather="file-text"></i> Export Selected
                                        </button>
                                        <button type="button" id="exportAllBtn" class="btn btn-warning btn-sm">
                                            <i data-feather="download"></i> Export All
                                        </button>
                                        <button type="button" id="clearSelectionBtn" class="btn btn-secondary btn-sm">
                                            <i data-feather="x"></i> Clear Selection
                                        </button>
                                        <span class="badge bg-light text-dark" id="selectedCount">0 selected</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Table Content -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 modern-table">
                                <thead class="table-light">
                                    <tr>
                                        @if(auth()->guard('admin')->user()->id === 1)
                                            <th class="text-center fw-semibold border-0">
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                        @endif
                                        <th class="text-center fw-semibold border-0">#</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-id-card text-primary me-1"></i>Booking Details</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-ship text-success me-1"></i>Boat & Event</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-user text-info me-1"></i>Customer Info</th>
                                        <th class="fw-semibold border-0"><i class="fas fa-rupee-sign text-warning me-1"></i>Payment Details</th>
                                        <th class="text-center fw-semibold border-0"><i class="fas fa-info-circle text-secondary me-1"></i>Status</th>
                                        <th class="text-center fw-semibold border-0"><i class="fas fa-cogs text-dark me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($boat_bookings as $boat_booking)
                                        <tr class="border-bottom-light">
                                            @if(auth()->guard('admin')->user()->id === 1)
                                                <td class="text-center">
                                                    <input type="checkbox" name="booking_ids[]" value="{{ $boat_booking->booking_id }}" class="form-check-input booking-checkbox" data-booking-id="{{ $boat_booking->booking_id }}">
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                <span class="badge bg-primary text-white rounded-pill">{{ $boat_bookings->firstItem() + $loop->index }}</span>
                                            </td>

                                            <!-- Booking Details -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-bold text-primary mb-1">{{$boat_booking->booking_id}}</div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-calendar-plus me-1"></i>
                                                        Created: {{$boat_booking->created_at->format('d M, Y')}}
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-calendar-check me-1"></i>
                                                        Event: {{$boat_booking->booking_date}}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Boat & Event -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-semibold text-success mb-1">
                                                        <i class="fas fa-ship me-1"></i>{{$boat_booking->boat?->boatType?->name}}
                                                    </div>
                                                    <div class="fw-semibold text-warning mb-1">
                                                        <i class="fas fa-ship me-1"></i>{{$boat_booking->boat?->event_type}}
                                                    </div>
                                                    @if($boat_booking->seat_number)
                                                        <div class="fw-semibold text-info mb-1">
                                                            <i class="fas fa-chair me-1"></i>Seat No: {{$boat_booking->seat_number}}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Customer Info -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                        {{ substr($boat_booking->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark mb-1">{{$boat_booking->name}}</div>
                                                        <div class="small text-muted mb-1">
                                                            <i class="fas fa-users me-1"></i>{{$boat_booking->no_of_person}} persons
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-phone me-1"></i>{{$boat_booking->phone}}
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-phone me-1"></i>{{$boat_booking->email}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Payment Details -->
                                            <td>
                                                <div class="payment-info">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-primary small">Total:</span>
                                                        <span class="fw-bold text-primary">₹{{number_format($boat_booking->total_amount, 2)}}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-info small">Discount:</span>
                                                        <span class="fw-bold text-info">₹{{number_format($boat_booking->total_discount, 2)}}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-success small">Final:</span>
                                                        <span class="fw-bold text-success">₹{{number_format($boat_booking->final_amount, 2)}}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-warning small">Paid:</span>
                                                        <span class="text-warning fw-semibold">₹{{number_format($boat_booking->payments_sum_amount ?? 0, 2)}}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-danger small">Due:</span>
                                                        <span class="text-{{($boat_booking->final_amount - ($boat_booking->payments_sum_amount ?? 0)) > 0 ? 'danger' : 'success'}} fw-semibold">
                                                            ₹{{number_format($boat_booking->final_amount - ($boat_booking->payments_sum_amount ?? 0), 2)}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Status -->
                                            <td class="text-center">
                                                <div class="mb-2">
                                                    <span class="badge bg-{{$boat_booking->booking_status == 'confirmed' ? 'success' : 'warning'}} status-badge">
                                                        {{ucfirst($boat_booking->booking_status)}}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="badge bg-{{$boat_booking->payment_status == 'paid' ? 'success' : ($boat_booking->payment_status == 'partial' ? 'warning' : 'danger')}} status-badge">
                                                        @if($boat_booking->payment_status === 'partial') Due @else {{ucfirst($boat_booking->payment_status)}} @endif
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-outline-primary btn-sm" href="{{route('boat-booking.edit', $boat_booking->booking_id)}}" title="Edit Booking">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <a class="btn btn-outline-primary btn-sm" href="{{route('send.booking.mail', $boat_booking->booking_id)}}" title="Send Mail">
                                                        <i data-feather="mail"></i>
                                                    </a>
                                                    <a class="btn btn-outline-success btn-sm" href="{{route('boat-booking.payment', $boat_booking->booking_id)}}" title="Payments">
                                                        <i data-feather="credit-card"></i>
                                                    </a>
                                                    <form action="{{ route('boat-booking.destroy', $boat_booking->booking_id) }}" method="POST" style="display:inline">
                                                        @method('Delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm deleteBtn" title="Delete"><i data-feather="trash-2"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-ship text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                                    <h5 class="text-muted mb-2">No Bookings Found</h5>
                                                    <p class="text-muted mb-3">There are no boat bookings matching your criteria.</p>
                                                    <a href="{{route('boat-booking.create')}}" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Create First Booking
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($boat_bookings->hasPages())
                            <div class="card-footer bg-light border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Showing {{ $boat_bookings->firstItem() }} to {{ $boat_bookings->lastItem() }} of {{ $boat_bookings->total() }} results
                                    </div>
                                    <div>
                                        {{ $boat_bookings->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for PDF export -->
    <form id="exportForm" action="{{ route('boat-booking.index') }}" method="GET" style="display: none;">
        <input type="hidden" name="export" value="pdf">
        <input type="hidden" name="search_boat_type" value="{{ $search_boat_type }}">
        <input type="hidden" name="search_event_type" value="{{ $search_event_type }}">
        <input type="hidden" name="search_booking_id" value="{{ $search_booking_id }}">
        <input type="hidden" name="search_user" value="{{ $search_user }}">
        <input type="hidden" name="search_date" value="{{ $search_date }}">
        <input type="hidden" name="search_payment_status" value="{{ $search_payment_status }}">
        <div id="exportBookingIds"></div>
    </form>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }

        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .payment-info {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            border-left: 3px solid #28a745;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .border-bottom-light {
            border-bottom: 1px solid #e9ecef !important;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-group .btn {
            margin-right: 2px;
            border-radius: 6px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            padding: 15px 12px;
        }

        .table td {
            padding: 15px 12px;
            vertical-align: middle;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const bookingCheckboxes = document.querySelectorAll('.booking-checkbox');
            const exportSelectedBtn = document.getElementById('exportSelectedBtn');
            const exportAllBtn = document.getElementById('exportAllBtn');
            const clearSelectionBtn = document.getElementById('clearSelectionBtn');
            const selectedCountSpan = document.getElementById('selectedCount');
            const exportForm = document.getElementById('exportForm');
            const exportBookingIds = document.getElementById('exportBookingIds');

            // Store selected booking IDs in localStorage
            const STORAGE_KEY = 'selected_booking_ids';

            // Get selected bookings from localStorage
            function getSelectedBookings() {
                const stored = localStorage.getItem(STORAGE_KEY);
                return stored ? JSON.parse(stored) : [];
            }

            // Save selected bookings to localStorage
            function saveSelectedBookings(bookingIds) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(bookingIds));
            }

            // Initialize checkboxes based on stored selections
            function initializeCheckboxes() {
                const selectedBookings = getSelectedBookings();

                bookingCheckboxes.forEach(checkbox => {
                    const bookingId = checkbox.getAttribute('data-booking-id');
                    if (selectedBookings.includes(bookingId)) {
                        checkbox.checked = true;
                    }
                });

                updateSelectAllState();
                updateExportButtons();
            }

            // Update select all checkbox state
            function updateSelectAllState() {
                const totalCheckboxes = bookingCheckboxes.length;
                const checkedCheckboxes = document.querySelectorAll('.booking-checkbox:checked').length;

                if (checkedCheckboxes === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCheckboxes === totalCheckboxes) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }

            // Update export buttons and counter
            function updateExportButtons() {
                const selectedBookings = getSelectedBookings();
                const count = selectedBookings.length;

                selectedCountSpan.textContent = `${count} selected`;
                exportSelectedBtn.disabled = count === 0;
            }

            // Handle select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                const selectedBookings = getSelectedBookings();

                bookingCheckboxes.forEach(checkbox => {
                    const bookingId = checkbox.getAttribute('data-booking-id');

                    if (this.checked) {
                        checkbox.checked = true;
                        if (!selectedBookings.includes(bookingId)) {
                            selectedBookings.push(bookingId);
                        }
                    } else {
                        checkbox.checked = false;
                        const index = selectedBookings.indexOf(bookingId);
                        if (index > -1) {
                            selectedBookings.splice(index, 1);
                        }
                    }
                });

                saveSelectedBookings(selectedBookings);
                updateExportButtons();
            });

            // Handle individual checkbox changes
            bookingCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    const selectedBookings = getSelectedBookings();

                    if (this.checked) {
                        if (!selectedBookings.includes(bookingId)) {
                            selectedBookings.push(bookingId);
                        }
                    } else {
                        const index = selectedBookings.indexOf(bookingId);
                        if (index > -1) {
                            selectedBookings.splice(index, 1);
                        }
                    }

                    saveSelectedBookings(selectedBookings);
                    updateSelectAllState();
                    updateExportButtons();
                });
            });

            // Clear selection functionality
            clearSelectionBtn.addEventListener('click', function() {
                localStorage.removeItem(STORAGE_KEY);
                bookingCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
                updateExportButtons();
            });

            // Export Selected functionality
            exportSelectedBtn.addEventListener('click', function() {
                const selectedBookings = getSelectedBookings();

                if (selectedBookings.length === 0) {
                    alert('Please select at least one booking to export.');
                    return;
                }

                // Clear previous booking IDs
                exportBookingIds.innerHTML = '';

                // Add selected booking IDs to form
                selectedBookings.forEach(bookingId => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_bookings[]';
                    input.value = bookingId;
                    exportBookingIds.appendChild(input);
                });

                // Submit form
                exportForm.submit();
            });

            // Export All functionality
            exportAllBtn.addEventListener('click', function() {
                // Remove selected bookings filter for export all
                exportBookingIds.innerHTML = '';
                exportForm.submit();
            });

            // Initialize checkboxes on page load
            initializeCheckboxes();
        });
    </script>
@endsection
