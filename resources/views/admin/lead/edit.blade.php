@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="mb-0">{{ $page_title }}</h4>
                        <x-cancle-btn route="{{ route('lead.index') }}" text="Back" />
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <form id="valid_form" method="POST" action="{{ route('lead.update', $lead->id) }}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3 pb-2 border-bottom">
                                <i data-feather="info" class="me-2"></i>Basic Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="enquiry_date" class="form-label fw-semibold">
                                        Enquiry Date <span class="text-danger">*</span>
                                    </label>
                                    <input id="enquiry_date" class="form-control" type="date" name="enquiry_date"
                                        value="{{ old('enquiry_date', $lead->enquiry_date) }}" required>
                                    @error('enquiry_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="booking_date" class="form-label fw-semibold">
                                        <i data-feather="calendar" style="width: 16px; height: 16px;"></i>
                                        Booking Date Range
                                    </label>
                                    <input type="text" name="daterange" id="booking_daterange"
                                        placeholder="Select booking dates..."
                                        @if ($lead->booking_start_date) value="{{ old('daterange', date('m/d/Y', strtotime($lead->booking_start_date)) . ' - ' . date('m/d/Y', strtotime($lead->booking_end_date))) }}" @endif
                                        class="form-control" style="cursor: pointer;">
                                    <small class="text-muted">Click to select start and end dates</small>
                                    @error('booking_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="lead_source_id" class="form-label fw-semibold">
                                        Lead Source <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control js-example-basic-single" name="lead_source_id"
                                            id="lead_source_id" required>
                                            <option value="">--Select Lead Source--</option>
                                            @foreach ($lead_sources as $lead_source)
                                                <option value="{{ $lead_source->id }}"
                                                    @if (old('lead_source_id', $lead->lead_source_id) == $lead_source->id) selected @endif>
                                                    {{ $lead_source->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#myModal" title="Add New Lead Source">
                                            <i data-feather="plus"></i>
                                        </button>
                                    </div>
                                    @error('lead_source_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Guest Details Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3 pb-2 border-bottom">
                                <i data-feather="users" class="me-2"></i>Guest Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="guest_name" class="form-label fw-semibold">Guest Name</label>
                                    <input id="guest_name" class="form-control" type="text" name="guest_name"
                                        placeholder="Enter guest name" value="{{ old('guest_name', $lead->guest_name) }}">
                                    @error('guest_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="contact" class="form-label fw-semibold">Contact</label>
                                    <input id="contact" class="form-control" type="text" name="contact"
                                        placeholder="Enter contact number" value="{{ old('contact', $lead->contact) }}">
                                    @error('contact')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="pax" class="form-label fw-semibold">Number of Pax</label>
                                    <input id="pax" class="form-control" type="number" name="pax"
                                        placeholder="Enter number of guests" value="{{ old('pax', $lead->pax) }}"
                                        min="1">
                                    @error('pax')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Plan Details Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3 pb-2 border-bottom">
                                <i data-feather="file-text" class="me-2"></i>Plan Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="short_plan" class="form-label fw-semibold">Short Description /
                                        Plan</label>
                                    <textarea id="short_plan" class="form-control" name="short_plan" rows="3"
                                        placeholder="Brief description of customer requirements or travel plan">{{ old('short_plan', $lead->short_plan) }}</textarea>
                                    <small class="text-muted">Capture the customer's basic requirements here. Detailed
                                        planning will be done in the quotation.</small>
                                    @error('short_plan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="service_ids" class="form-label fw-semibold">Service Types</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach ($serviceTypes ?? [] as $serviceType)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="service_ids[]"
                                                    value="{{ $serviceType->id }}"
                                                    id="service_type_{{ $serviceType->id }}"
                                                    @if (in_array($serviceType->id, old('service_ids', $lead->service_ids) ?? [])) checked @endif>
                                                <label class="form-check-label"
                                                    for="service_type_{{ $serviceType->id }}">
                                                    {{ $serviceType->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">Select the types of services this lead is interested in
                                        (e.g., Hotel, Transport, Sightseeing)</small>
                                    @error('service_ids')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('lead.index') }}" class="btn btn-light">
                                        <i data-feather="x" class="me-1"></i> Cancel
                                    </a>
                                    <x-save-btn text="Update Lead" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lead Source Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="myModalLabel">
                        <i data-feather="plus-circle" class="me-2"></i>Add Lead Source
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('vendor_store') }}" method="POST" id="lead_source_form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input id="name" class="form-control" type="text" name="name"
                                placeholder="Enter lead source name" required>
                            <small class="text-danger" id="name_error"></small>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">
                                Phone <span class="text-danger">*</span>
                            </label>
                            <input id="phone" class="form-control" type="tel" name="phone"
                                placeholder="Enter phone number" required>
                            <small class="text-danger" id="phone_error"></small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i data-feather="x" class="me-1"></i> Cancel
                        </button>
                        <button class="btn btn-primary" type="submit" id="save_modal_button">
                            <i data-feather="save" class="me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <script>
        // Initialize CKEditor for short description
        $(document).ready(function() {
            if (CKEDITOR.instances['short_plan']) {
                CKEDITOR.instances['short_plan'].destroy(true);
            }
            CKEDITOR.replace('short_plan', {
                height: 200,
                toolbar: [{
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'styles',
                        items: ['Format']
                    },
                    {
                        name: 'colors',
                        items: ['TextColor', 'BGColor']
                    },
                    {
                        name: 'tools',
                        items: ['Maximize']
                    },
                    {
                        name: 'editing',
                        items: ['Undo', 'Redo']
                    }
                ]
            });
        });

        // Lead Source Form Submission
        $('#lead_source_form').on('submit', function(event) {
            event.preventDefault();

            const saveButton = $('#save_modal_button');
            saveButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            const formData = new FormData(this);
            const formAction = $(this).attr('action');

            // Clear previous errors
            $('#name_error, #phone_error').html('');

            $.ajax({
                type: 'POST',
                url: formAction,
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (res.success === true) {
                        $('#lead_source_form')[0].reset();
                        $('#myModal').modal('hide');

                        // Add new option to select
                        $('#lead_source_id').append(
                            `<option value="${res.vendor.id}" selected>${res.vendor.name}</option>`
                        );

                        // Show success message
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: res.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                },
                error: function(res) {
                    if (res.responseJSON && res.responseJSON.errors) {
                        $('#name_error').html(res.responseJSON.errors.name ? res.responseJSON.errors
                            .name[0] : '');
                        $('#phone_error').html(res.responseJSON.errors.phone ? res.responseJSON.errors
                            .phone[0] : '');
                    }
                },
                complete: function() {
                    saveButton.prop('disabled', false).html(
                        '<i data-feather="save" class="me-1"></i> Save');
                    feather.replace();
                }
            });
        });

        // Initialize feather icons
        $(document).ready(function() {
            feather.replace();
        });
    </script>
@endpush
