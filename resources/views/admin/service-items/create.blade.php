@extends('admin.layouts.app')

@section('content')
    <style>
        /* Validation Error Styling */
        .alert-danger {
            border-left: 4px solid #dc3545;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }

        .alert-danger .alert-heading {
            color: #842029;
            font-weight: 600;
        }

        .alert-danger ul {
            padding-left: 20px;
        }

        .alert-danger li {
            color: #58151c;
            margin-bottom: 4px;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5;
        }

        .is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* Highlight error rows */
        tr:has(.is-invalid) {
            background-color: #fff5f5 !important;
        }

        /* Disabled input styling */
        .template-input:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Add Service Items for Provider</h2>
            <a href="{{ route('service-items.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">Validation Errors</h5>
                                <p class="mb-2">Please fix the following errors:</p>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('service-items.store') }}" method="POST" id="serviceItemsForm">
                    @csrf

                    <!-- Step 1: Select Provider -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Service Provider <span class="text-danger">*</span></label>
                            <select name="service_provider_id" id="service_provider_id" class="form-select" required>
                                <option value="">Select Provider</option>
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider->id }}"
                                        {{ old('service_provider_id', $providerId) == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }} ({{ ucfirst($provider->type) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('service_provider_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    @if ($providerId && count($existingItems) > 0)
                        <div class="alert alert-warning">
                            <i data-feather="info"></i> <strong>Note:</strong> This provider already has
                            {{ count($existingItems) }} service item(s).
                            Items marked with <span class="badge bg-warning text-dark">Exists</span> will be updated if you
                            submit them again.
                            To edit existing items, <a href="{{ route('service-items.edit', $providerId) }}"
                                class="alert-link">click here</a>.
                        </div>
                    @endif

                    {{-- Instructions --}}
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Instructions:</strong> Check the checkbox for each service template that this provider
                        offers.
                        Only checked templates will be saved. You can leave templates unchecked if the vendor doesn't
                        provide that service.
                    </div>

                    <!-- Step 2: Service Template Rates Table -->
                    <h5 class="mb-3">Set Rates for Service Templates</h5>
                    <p class="text-muted">Select the templates you want to add rates for and fill in the pricing details.
                    </p>

                    <div class="table-responsive">
                        @if ($providerId && $serviceTemplates->count() > 0)
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Service Type</th>
                                        <th>Template Name</th>
                                        <th>Capacity</th>
                                        <th>Default Price (₹)</th>
                                        <th>Vendor Cost (₹) <span class="text-danger">*</span></th>
                                        <th>Base Price (₹) <span class="text-danger">*</span></th>
                                        <th>Capacity Override</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceTemplates as $serviceTypeName => $templates)
                                        @foreach ($templates as $template)
                                            @php
                                                $alreadyExists = in_array($template->id, $existingItems);
                                            @endphp
                                            <tr class="template-row {{ $alreadyExists ? 'table-warning' : '' }}"
                                                data-template-id="{{ $template->id }}">
                                                <td>
                                                    <input type="checkbox" name="template_enabled[{{ $template->id }}]"
                                                        class="form-check-input template-checkbox" value="1">
                                                    <!-- Hidden input to track service_template_id for enabled templates -->
                                                    <input type="hidden"
                                                        name="templates[{{ $template->id }}][service_template_id]"
                                                        value="{{ $template->id }}" disabled>
                                                    @if ($alreadyExists)
                                                        <small class="text-warning d-block">
                                                            <i data-feather="alert-circle"
                                                                style="width: 12px; height: 12px;"></i> Exists
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>{{ $serviceTypeName }}</td>
                                                <td>
                                                    <strong>{{ $template->name }}</strong>
                                                    @if ($template->description)
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $template->capacity ?? 'N/A' }}</td>
                                                <td>₹{{ number_format($template->default_selling_price, 2) }}</td>
                                                <td>
                                                    <input type="number"
                                                        name="templates[{{ $template->id }}][vendor_cost]"
                                                        class="form-control form-control-sm template-input @error('templates.' . $template->id . '.vendor_cost') is-invalid @enderror"
                                                        step="0.01" min="0" placeholder="0.00"
                                                        value="{{ old('templates.' . $template->id . '.vendor_cost') }}"
                                                        disabled>
                                                    @error('templates.' . $template->id . '.vendor_cost')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" name="templates[{{ $template->id }}][base_price]"
                                                        class="form-control form-control-sm template-input @error('templates.' . $template->id . '.base_price') is-invalid @enderror"
                                                        step="0.01" min="0" placeholder="0.00"
                                                        value="{{ old('templates.' . $template->id . '.base_price') }}"
                                                        disabled>
                                                    @error('templates.' . $template->id . '.base_price')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" name="templates[{{ $template->id }}][capacity]"
                                                        class="form-control form-control-sm template-input @error('templates.' . $template->id . '.capacity') is-invalid @enderror"
                                                        min="1" placeholder="{{ $template->capacity ?? '' }}"
                                                        value="{{ old('templates.' . $template->id . '.capacity') }}"
                                                        disabled>
                                                    @error('templates.' . $template->id . '.capacity')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Please select a service provider above to view available
                                service templates for their service types.
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Service Items
                        </button>
                        <a href="{{ route('service-items.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const providerSelect = document.getElementById('service_provider_id');
                const selectAllCheckbox = document.getElementById('selectAll');
                const templateCheckboxes = document.querySelectorAll('.template-checkbox');
                const form = document.getElementById('serviceItemsForm');

                // Reload page when provider is selected to load their templates
                if (providerSelect) {
                    providerSelect.addEventListener('change', function() {
                        if (this.value) {
                            window.location.href = '{{ route('service-items.create') }}?provider_id=' + this
                                .value;
                        }
                    });
                }

                // Select all functionality
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        templateCheckboxes.forEach(checkbox => {
                            if (!checkbox.disabled) {
                                checkbox.checked = this.checked;
                                toggleInputs(checkbox);
                            }
                        });
                    });
                }

                // Individual checkbox toggle
                templateCheckboxes.forEach(checkbox => {
                    // Check if this row has any filled inputs (from old values after validation error)
                    const row = checkbox.closest('.template-row');
                    const inputs = row.querySelectorAll('.template-input');
                    let hasValue = false;

                    inputs.forEach(input => {
                        if (input.value && input.value.trim() !== '') {
                            hasValue = true;
                        }
                    });

                    // If there are old values, check the checkbox and mark it as checked in the form
                    if (hasValue) {
                        checkbox.checked = true;
                    }

                    // Initialize state on page load
                    toggleInputs(checkbox);

                    checkbox.addEventListener('change', function() {
                        toggleInputs(this);
                    });
                });

                function toggleInputs(checkbox) {
                    const row = checkbox.closest('.template-row');
                    const inputs = row.querySelectorAll('.template-input');
                    const hiddenTemplateId = row.querySelector('input[name*="service_template_id"]');
                    const errorMessages = row.querySelectorAll('.invalid-feedback');

                    // Enable/disable the hidden service_template_id input
                    if (hiddenTemplateId) {
                        if (checkbox.checked) {
                            hiddenTemplateId.removeAttribute('disabled');
                        } else {
                            hiddenTemplateId.setAttribute('disabled', 'disabled');
                        }
                    }

                    inputs.forEach(input => {
                        if (checkbox.checked) {
                            // Enable inputs
                            input.removeAttribute('disabled');
                            if (input.name.includes('vendor_cost') || input.name.includes('base_price')) {
                                input.setAttribute('required', 'required');
                            }
                        } else {
                            // Disable inputs and clear validation errors
                            input.setAttribute('disabled', 'disabled');
                            input.removeAttribute('required');
                            input.classList.remove('is-invalid');

                            // Don't clear value on unchecking in case user wants to re-check
                        }
                    });

                    // Hide error messages when unchecking
                    if (!checkbox.checked) {
                        errorMessages.forEach(msg => {
                            msg.style.display = 'none';
                        });
                        row.style.backgroundColor = '';
                    } else {
                        errorMessages.forEach(msg => {
                            msg.style.display = 'block';
                        });
                    }
                }

                // Form submission - Remove unchecked template data completely
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // For each unchecked checkbox, remove ALL related inputs for that template
                        templateCheckboxes.forEach(checkbox => {
                            if (!checkbox.checked) {
                                const row = checkbox.closest('.template-row');
                                const templateId = row.getAttribute('data-template-id');

                                // Remove all inputs related to this template
                                const allInputs = row.querySelectorAll('input, select, textarea');
                                allInputs.forEach(input => {
                                    if (input.name) {
                                        input.removeAttribute('name');
                                    }
                                });
                            }
                        });

                        // Also remove disabled inputs from submission as a fallback
                        document.querySelectorAll('.template-input:disabled').forEach(input => {
                            input.removeAttribute('name');
                        });
                    });
                }

                // Auto-scroll to first error if validation failed
                @if ($errors->any())
                    setTimeout(function() {
                        const firstError = document.querySelector('.is-invalid, .alert-danger');
                        if (firstError) {
                            firstError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }, 100);
                @endif

                // Auto-copy vendor cost to base price
                document.querySelectorAll('input[name*="vendor_cost"]').forEach(vendorCostInput => {
                    vendorCostInput.addEventListener('input', function() {
                        const row = this.closest('.template-row');
                        const basePriceInput = row.querySelector('input[name*="base_price"]');

                        if (basePriceInput && !basePriceInput.disabled) {
                            // Only copy if base price is empty or user hasn't manually changed it
                            if (!basePriceInput.value || basePriceInput.value === '' || basePriceInput
                                .dataset.manuallyChanged !== 'true') {
                                basePriceInput.value = this.value;
                            }
                        }
                    });
                });

                // Mark base price as manually changed when user edits it
                document.querySelectorAll('input[name*="base_price"]').forEach(basePriceInput => {
                    basePriceInput.addEventListener('input', function() {
                        this.dataset.manuallyChanged = 'true';
                    });

                    // Reset manual change flag when row is unchecked/re-checked
                    basePriceInput.addEventListener('focus', function() {
                        const row = this.closest('.template-row');
                        const checkbox = row.querySelector('.template-checkbox');
                        if (checkbox && !checkbox.checked) {
                            this.dataset.manuallyChanged = 'false';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
```
