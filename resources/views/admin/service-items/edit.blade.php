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
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Service Items for {{ $provider->name }}</h2>
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

                <form action="{{ route('service-items.update', $provider->id) }}" method="POST" id="serviceItemsForm">
                    @csrf
                    @method('PUT')

                    <!-- Provider Info -->
                    <div class="alert alert-info">
                        <strong>Provider:</strong> {{ $provider->name }} ({{ ucfirst($provider->type) }})
                    </div>

                    <hr>

                    {{-- Instructions --}}
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Instructions:</strong> Check the templates this provider offers and fill in the pricing.
                        Uncheck templates to remove them. Only checked templates will be saved.
                    </div>

                    <!-- Service Template Rates Table -->
                    <h5 class="mb-3">Manage Rates for Service Templates</h5>
                    <p class="text-muted">Check templates to add/update rates, uncheck to remove them.</p>

                    <div class="table-responsive">
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
                                    <th>Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceTemplates as $serviceTypeName => $templates)
                                    @foreach ($templates as $template)
                                        @php
                                            $existingItem = $existingItems->get($template->id);
                                            $isChecked = $existingItem !== null;
                                        @endphp
                                        <tr class="template-row {{ $isChecked ? 'table-success' : '' }}"
                                            data-template-id="{{ $template->id }}">
                                            <td>
                                                <input type="checkbox" name="template_enabled[{{ $template->id }}]"
                                                    class="form-check-input template-checkbox" value="1"
                                                    {{ $isChecked ? 'checked' : '' }}>
                                                <!-- Hidden input to track service_template_id for enabled templates -->
                                                <input type="hidden"
                                                    name="templates[{{ $template->id }}][service_template_id]"
                                                    value="{{ $template->id }}" {{ !$isChecked ? 'disabled' : '' }}>
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
                                                <input type="number" name="templates[{{ $template->id }}][vendor_cost]"
                                                    class="form-control form-control-sm template-input @error('templates.' . $template->id . '.vendor_cost') is-invalid @enderror"
                                                    step="0.01" min="0"
                                                    value="{{ old('templates.' . $template->id . '.vendor_cost', $existingItem?->vendor_cost) }}"
                                                    placeholder="0.00" {{ !$isChecked ? 'disabled' : '' }}>
                                                @error('templates.' . $template->id . '.vendor_cost')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" name="templates[{{ $template->id }}][base_price]"
                                                    class="form-control form-control-sm template-input @error('templates.' . $template->id . '.base_price') is-invalid @enderror"
                                                    step="0.01" min="0"
                                                    value="{{ old('templates.' . $template->id . '.base_price', $existingItem?->base_price) }}"
                                                    placeholder="0.00" {{ !$isChecked ? 'disabled' : '' }}>
                                                @error('templates.' . $template->id . '.base_price')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" name="templates[{{ $template->id }}][capacity]"
                                                    class="form-control form-control-sm template-input @error('templates.' . $template->id . '.capacity') is-invalid @enderror"
                                                    min="1"
                                                    value="{{ old('templates.' . $template->id . '.capacity', $existingItem?->capacity) }}"
                                                    placeholder="{{ $template->capacity ?? '' }}"
                                                    {{ !$isChecked ? 'disabled' : '' }}>
                                                @error('templates.' . $template->id . '.capacity')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="checkbox" name="templates[{{ $template->id }}][is_active]"
                                                    class="form-check-input template-input" value="1"
                                                    {{ $existingItem?->is_active ?? true ? 'checked' : '' }}
                                                    {{ !$isChecked ? 'disabled' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Service Items
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
                const selectAllCheckbox = document.getElementById('selectAll');
                const templateCheckboxes = document.querySelectorAll('.template-checkbox');
                const form = document.getElementById('serviceItemsForm');

                // Select all functionality
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        templateCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                            toggleInputs(checkbox);
                            updateRowHighlight(checkbox);
                        });
                    });
                }

                // Individual checkbox toggle
                templateCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        toggleInputs(this);
                        updateRowHighlight(this);
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
                        }
                    });

                    // Hide error messages when unchecking
                    if (!checkbox.checked) {
                        errorMessages.forEach(msg => {
                            msg.style.display = 'none';
                        });
                    } else {
                        errorMessages.forEach(msg => {
                            msg.style.display = 'block';
                        });
                    }
                }

                function updateRowHighlight(checkbox) {
                    const row = checkbox.closest('.template-row');
                    if (checkbox.checked) {
                        row.classList.add('table-success');
                    } else {
                        row.classList.remove('table-success');
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
