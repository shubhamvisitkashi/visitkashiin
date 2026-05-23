@extends('admin.layouts.app')

@push('styles')
    <!-- Font Awesome for Summernote icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1">{{ $page_title }}</h4>
                            <p class="text-muted mb-0">Booking #{{ $booking->booking_number }}</p>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="btn btn-light">
                            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back to Bookings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Guest Information Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3 pb-2 border-bottom">
                                <i data-feather="user" class="me-2"></i>Guest Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="guest_name" class="form-label fw-semibold">
                                        Guest Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('guest_name') is-invalid @enderror"
                                        id="guest_name" name="guest_name"
                                        value="{{ old('guest_name', $booking->lead->guest_name ?? '') }}" required>
                                    @error('guest_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="contact" class="form-label fw-semibold">
                                        Contact Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('contact') is-invalid @enderror"
                                        id="contact" name="contact"
                                        value="{{ old('contact', $booking->lead->contact ?? '') }}" required>
                                    @error('contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control" id="email"
                                        value="{{ $booking->lead->email ?? 'N/A' }}" disabled>
                                    <small class="text-muted">Email field is read-only</small>
                                </div>

                                <div class="col-md-4">
                                    <label for="pax" class="form-label fw-semibold">Number of Persons</label>
                                    <input type="text" class="form-control" id="pax"
                                        value="{{ $booking->lead->pax ?? 'N/A' }}" disabled>
                                    <small class="text-muted">Pax field is read-only</small>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3 pb-2 border-bottom">
                                <i data-feather="calendar" class="me-2"></i>Booking Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="booking_date" class="form-label fw-semibold">
                                        Booking Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('booking_date') is-invalid @enderror"
                                        id="booking_date" name="booking_date"
                                        value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}"
                                        required>
                                    @error('booking_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="total_amount" class="form-label fw-semibold">
                                        Total Amount (₹) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('total_amount') is-invalid @enderror"
                                        id="total_amount" name="total_amount"
                                        value="{{ old('total_amount', $booking->total_amount) }}" step="0.01"
                                        min="0" required>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Current: ₹{{ number_format($booking->total_amount, 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Plan Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3 pb-2 border-bottom">
                                <i data-feather="map" class="me-2"></i>Tour Plan / Itinerary
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="tour_plan" class="form-label fw-semibold">Tour Plan Details</label>
                                    <textarea class="form-control @error('tour_plan') is-invalid @enderror" id="tour_plan" name="tour_plan" rows="6"
                                        placeholder="Enter tour plan, itinerary, or package details...">{{ old('tour_plan', $booking->quotation->itinerary ?? ($booking->lead->short_plan ?? '')) }}</textarea>
                                    @error('tour_plan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Describe the tour plan, destinations, activities,
                                        etc.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Services Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h5 class="text-primary mb-0">
                                    <i data-feather="list" class="me-2"></i>Services
                                </h5>
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#addServiceModal">
                                    <i data-feather="plus" style="width: 14px; height: 14px;"></i> Add Service
                                </button>
                            </div>
                            @if ($booking->quotation && $booking->quotation->items->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Service</th>
                                                <th class="text-center" style="width: 140px;">Service Date</th>
                                                <th class="text-center" style="width: 100px;">Quantity</th>
                                                <th class="text-end" style="width: 130px;">Unit Price</th>
                                                <th class="text-end" style="width: 130px;">Total Price</th>
                                                <th class="text-center" style="width: 80px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="servicesTableBody">
                                            @foreach ($booking->quotation->items as $index => $item)
                                                <tr data-item-id="{{ $item->id }}">
                                                    <td>
                                                        <strong>{{ $item->serviceTemplate->name ?? 'N/A' }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $item->serviceType->name ?? 'N/A' }}
                                                        </small>
                                                        <input type="hidden" name="services[{{ $index }}][id]"
                                                            value="{{ $item->id }}">
                                                        <input type="hidden"
                                                            name="services[{{ $index }}][service_template_id]"
                                                            value="{{ $item->service_template_id }}">
                                                        <input type="hidden"
                                                            name="services[{{ $index }}][service_type_id]"
                                                            value="{{ $item->service_type_id }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="date"
                                                            name="services[{{ $index }}][service_date]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $item->service_date ? $item->service_date->format('Y-m-d') : '' }}"
                                                            placeholder="Select date">
                                                        @if ($item->service_date)
                                                            <small
                                                                class="text-muted d-block mt-1">{{ $item->service_date->diffForHumans() }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number"
                                                            name="services[{{ $index }}][quantity]"
                                                            class="form-control form-control-sm text-center"
                                                            value="{{ $item->quantity }}" min="1" required>
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="services[{{ $index }}][unit_price]"
                                                            class="form-control form-control-sm text-end"
                                                            value="{{ $item->unit_price }}" step="0.01"
                                                            min="0" required>
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="services[{{ $index }}][total_price]"
                                                            class="form-control form-control-sm text-end"
                                                            value="{{ $item->total_price }}" step="0.01"
                                                            min="0" required>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger remove-service-btn"
                                                            onclick="removeService(this, {{ $item->id }})"
                                                            title="Remove Service">
                                                            <i data-feather="trash-2"
                                                                style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i data-feather="alert-circle" style="width: 16px; height: 16px;"></i>
                                    No services added yet. Click "Add Service" to add services to this booking.
                                </div>
                            @endif
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('bookings.index') }}" class="btn btn-light">
                                        <i data-feather="x" class="me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="save" class="me-1"></i> Update Booking
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i data-feather="info" style="width: 16px; height: 16px;"></i> Important Notes
                    </h6>
                    <ul class="mb-0 text-muted small">
                        <li>Updating guest information will reflect in the associated lead record</li>
                        <li>Changes to the total amount will not affect existing payments</li>
                        <li>Tour plan updates will be reflected in quotations and invoices</li>
                        <li>Current Paid Amount: <strong
                                class="text-success">₹{{ number_format($booking->paid_amount ?? 0, 2) }}</strong></li>
                        <li>Current Due Amount: <strong
                                class="text-warning">₹{{ number_format($booking->pending_amount ?? 0, 2) }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i data-feather="plus"></i> Add Service to Booking
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Use the "Add Service" feature on the booking details page to add new services
                        with full vendor assignment capabilities.</p>
                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-primary">
                        <i data-feather="external-link"></i> Go to Booking Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global function to remove service row
        function removeService(button, itemId) {
            // Use SweetAlert if available, otherwise use native confirm
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Remove Service?',
                    text: 'Are you sure you want to remove this service from the booking?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performServiceRemoval(button, itemId);
                        Swal.fire('Removed!',
                            'Service has been marked for removal. Click "Update Booking" to save changes.',
                            'success');
                    }
                });
            } else {
                if (confirm('Are you sure you want to remove this service?')) {
                    performServiceRemoval(button, itemId);
                }
            }
        }

        function performServiceRemoval(button, itemId) {
            const row = button.closest('tr');

            // Add hidden input to mark this service for deletion
            const form = document.querySelector('form');
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'deleted_services[]';
            deleteInput.value = itemId;
            form.appendChild(deleteInput);

            // Remove the row from table
            row.remove();

            // Show message if no services remaining
            const tbody = document.getElementById('servicesTableBody');
            if (tbody && tbody.children.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="5" class="text-center text-muted">No services remaining. Click "Update Booking" to save changes.</td></tr>';
            }

            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    </script>

    @push('scripts')
        <!-- Summernote JS -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Feather icons
                feather.replace();

                // Initialize Summernote on tour_plan textarea
                $('#tour_plan').summernote({
                    height: 300,
                    placeholder: 'Enter tour plan, itinerary, or package details...',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onInit: function() {
                            console.log('Summernote initialized on tour_plan');
                        }
                    }
                });
            });

            // Function to remove service row
            function removeService(button, itemId) {
                if (confirm('Are you sure you want to remove this service?')) {
                    const row = button.closest('tr');

                    // Add hidden input to mark this service for deletion
                    const form = document.querySelector('form');
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_services[]';
                    deleteInput.value = itemId;
                    form.appendChild(deleteInput);

                    // Remove the row from table
                    row.remove();

                    // Show success message
                    const tbody = document.getElementById('servicesTableBody');
                    if (tbody.children.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="5" class="text-center text-muted">No services remaining. Save to apply changes.</td></tr>';
                    }
                }
            }
        </script>
    @endpush
@endsection
