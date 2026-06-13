@extends('admin.layouts.app')

@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4>{{ $page_title }}</h4>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-secondary btn-sm">
                                    <i data-feather="arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('quotations.update', $quotation->id) }}" id="quotation-form">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="lead_id" class="form-label fw-semibold">Customer/Lead <span
                                            class="text-danger">*</span></label>
                                    <select id="lead_id" class="form-select @error('lead_id') is-invalid @enderror"
                                        name="lead_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach ($leads as $leadOption)
                                            <option value="{{ $leadOption->id }}"
                                                {{ old('lead_id', $quotation->lead_id) == $leadOption->id ? 'selected' : '' }}>
                                                {{ $leadOption->guest_name }} - {{ $leadOption->contact }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lead_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="quotation_date" class="form-label fw-semibold">Quotation Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" id="quotation_date"
                                        class="form-control @error('quotation_date') is-invalid @enderror"
                                        name="quotation_date"
                                        value="{{ old('quotation_date', $quotation->quotation_date->format('Y-m-d')) }}"
                                        required>
                                    @error('quotation_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="valid_until" class="form-label fw-semibold">Valid Until</label>
                                    <input type="date" id="valid_until"
                                        class="form-control @error('valid_until') is-invalid @enderror" name="valid_until"
                                        value="{{ old('valid_until', $quotation->valid_until?->format('Y-m-d')) }}">
                                    @error('valid_until')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        <i data-feather="map"></i> Tour Plan / Itinerary
                                        <small class="text-muted">(Optional)</small>
                                    </label>
                                    <textarea id="itinerary_html" name="itinerary_html" rows="10">{{ old('itinerary_html', $quotation->itinerary_html) }}</textarea>
                                    <small class="text-muted">Create a detailed day-by-day itinerary for your
                                        customer</small>
                                </div>

                                <div class="col-md-12">
                                    <label for="notes" class="form-label fw-semibold">Notes</label>
                                    <textarea id="notes" class="form-control" name="notes" rows="2">{{ old('notes', $quotation->notes) }}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Services <span
                                            class="text-danger">*</span></label>
                                    <div id="quotation-items-container"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                        onclick="addQuotationItem()">
                                        <i data-feather="plus"></i> Add Service
                                    </button>
                                </div>

                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="service_charge" class="form-label fw-semibold">Service
                                                        Charge</label>
                                                    <input type="number" step="0.01" id="service_charge"
                                                        class="form-control" name="service_charge"
                                                        value="{{ old('service_charge', $quotation->service_charge ?? 0) }}"
                                                        min="0" onchange="calculateTotal()">
                                                    <small class="text-muted">Additional service charge (if any)</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">GST</label>
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="gst_type"
                                                                id="gst_include" value="include" onchange="calculateTotal()"
                                                                {{ old('gst_type', $quotation->gst_type ?? 'exclude') == 'include' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="gst_include">
                                                                Include GST
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="gst_type"
                                                                id="gst_exclude" value="exclude"
                                                                onchange="calculateTotal()"
                                                                {{ old('gst_type', $quotation->gst_type ?? 'exclude') == 'exclude' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="gst_exclude">
                                                                Exclude GST
                                                            </label>
                                                        </div>
                                                        <input type="number" step="0.01" id="gst_percentage"
                                                            class="form-control" name="gst_percentage"
                                                            value="{{ old('gst_percentage', $quotation->gst_percentage ?? 18) }}"
                                                            min="0" max="100" style="width: 100px;"
                                                            onchange="calculateTotal()">
                                                        <span>%</span>
                                                    </div>
                                                    <input type="hidden" name="gst_amount" id="gst_amount"
                                                        value="{{ $quotation->gst_amount ?? 0 }}">
                                                    <input type="hidden" name="subtotal" id="subtotal"
                                                        value="{{ $quotation->subtotal ?? 0 }}">
                                                </div>
                                            </div>

                                            <hr class="my-3">

                                            <div class="pricing-breakdown">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Services Total:</span>
                                                    <strong id="services-total">₹0.00</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Service Charge:</span>
                                                    <strong id="service-charge-display">₹0.00</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Subtotal:</span>
                                                    <strong id="subtotal-display">₹0.00</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>GST (<span id="gst-percent-display">18</span>%):</span>
                                                    <strong id="gst-amount-display">₹0.00</strong>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="override_total" onchange="toggleTotalOverride()"
                                                            {{ old('custom_total', $quotation->custom_total ?? null) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="override_total">
                                                            <strong>Override Total Amount</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center"
                                                    id="total-display-section">
                                                    <h5 class="mb-0">Total Amount:</h5>
                                                    <h5 class="mb-0 text-success" id="total-amount">₹0.00</h5>
                                                </div>
                                                <div class="d-none" id="total-override-section">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-md-6">
                                                            <label class="form-label small mb-1">Calculated Total:</label>
                                                            <div class="text-muted" id="calculated-total">₹0.00</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small mb-1">Custom Total:</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                id="custom_total" name="custom_total"
                                                                value="{{ old('custom_total', $quotation->custom_total ?? 0) }}"
                                                                min="0" onchange="updateCustomTotal()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Update Quotation
                                </button>
                                <a href="{{ route('quotations.show', $quotation->id) }}"
                                    class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = 0;
        const serviceTemplates = @json($serviceTemplates);
        const existingItems = @json($quotation->items);

        function addQuotationItem(existingItem = null) {
            const container = document.getElementById('quotation-items-container');
            const templateId = existingItem ? existingItem.service_template_id : '';
            const unitPrice = existingItem ? existingItem.unit_price : 0;
            const serviceDate = existingItem ? existingItem.service_date : '';
            const customName = existingItem ? existingItem.custom_name : '';
            const isCustom = !!(existingItem && !templateId && customName);

            // Find the category for existing item
            let selectedCategory = '';
            if (templateId) {
                const template = serviceTemplates.find(t => t.id == templateId);
                if (template) {
                    selectedCategory = template.service_type.name;
                }
            } else if (isCustom && existingItem.service_type_id) {
                const template = serviceTemplates.find(t => t.service_type.id == existingItem.service_type_id);
                if (template) {
                    selectedCategory = template.service_type.name;
                }
            }

            const html = `
            <div class="card mb-2 quotation-item" id="item-${itemIndex}">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small">Service Category</label>
                            <select class="form-select form-select-sm category-select" onchange="filterServiceTemplates(${itemIndex})" required>
                                <option value="">Select Category</option>
                                ${[...new Set(serviceTemplates.map(t => t.service_type.name))].map(cat => `<option value="${cat}" ${cat === selectedCategory ? 'selected' : ''}>${cat}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Service Template</label>
                            <select class="form-select form-select-sm template-select" name="items[${itemIndex}][service_template_id]"
                                    onchange="loadTemplateDetails(${itemIndex})" required ${selectedCategory ? '' : 'disabled'}>
                                <option value="">Select ${selectedCategory ? 'Service' : 'Category First'}</option>
                            </select>
                            <input type="hidden" class="service-type-input" name="items[${itemIndex}][service_type_id]" value="">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Final Price</label>
                            <input type="number" step="0.01" class="form-control form-control-sm price-input" name="items[${itemIndex}][unit_price]"
                                   value="${unitPrice}" min="0" onchange="calculateTotal()" required>
                            <input type="hidden" name="items[${itemIndex}][quantity]" value="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Service Date</label>
                            <input type="date" class="form-control form-control-sm" name="items[${itemIndex}][service_date]" value="${serviceDate}">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeItem(${itemIndex})">
                                <i data-feather="trash-2"></i>
                            </button>
                        </div>
                        <div class="col-md-12 custom-name-row d-none mt-2">
                            <label class="form-label small">Custom Item Name</label>
                            <input type="text" class="form-control form-control-sm custom-name-input" name="items[${itemIndex}][custom_name]" placeholder="Enter custom item name" value="${customName || ''}">
                        </div>
                    </div>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);

            // If editing existing item, populate the template dropdown
            if (selectedCategory) {
                const currentIndex = itemIndex;
                setTimeout(() => {
                    filterServiceTemplates(currentIndex, isCustom ? '__custom__' : templateId, isCustom ? existingItem.service_type_id : null);
                }, 0);
            }

            feather.replace();
            itemIndex++;
            calculateTotal();
        }

        function filterServiceTemplates(index, preselectedId = null, customServiceTypeId = null) {
            const item = document.getElementById(`item-${index}`);
            const categorySelect = item.querySelector('.category-select');
            const templateSelect = item.querySelector('.template-select');
            const selectedCategory = categorySelect.value;

            if (selectedCategory) {
                const filteredTemplates = serviceTemplates.filter(t => t.service_type.name === selectedCategory);
                const serviceTypeId = customServiceTypeId || (filteredTemplates.length ? filteredTemplates[0].service_type.id : '');
                templateSelect.innerHTML = '<option value="">Select Service</option>' +
                    filteredTemplates.map(t =>
                        `<option value="${t.id}" data-price="${t.default_selling_price}" ${t.id == preselectedId ? 'selected' : ''}>${t.name}</option>`
                    ).join('') +
                    `<option value="__custom__" data-service-type-id="${serviceTypeId}" ${preselectedId === '__custom__' ? 'selected' : ''}>✏️ Type custom name…</option>`;
                templateSelect.disabled = false;

                // If preselected, load its price
                if (preselectedId && preselectedId !== '__custom__') {
                    const selectedOption = templateSelect.options[templateSelect.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        item.querySelector('.price-input').value = selectedOption.dataset.price;
                    }
                }

                if (preselectedId === '__custom__') {
                    templateSelect.removeAttribute('name');
                    item.querySelector('.service-type-input').value = serviceTypeId;
                    item.querySelector('.custom-name-row').classList.remove('d-none');
                    item.querySelector('.custom-name-input').required = true;
                } else {
                    templateSelect.setAttribute('name', `items[${index}][service_template_id]`);
                    item.querySelector('.service-type-input').value = '';
                    item.querySelector('.custom-name-row').classList.add('d-none');
                    item.querySelector('.custom-name-input').required = false;
                    item.querySelector('.custom-name-input').value = '';
                    if (!preselectedId) {
                        item.querySelector('.price-input').value = 0;
                    }
                }
            } else {
                templateSelect.innerHTML = '<option value="">Select Category First</option>';
                templateSelect.disabled = true;
                templateSelect.setAttribute('name', `items[${index}][service_template_id]`);
                item.querySelector('.price-input').value = 0;
                item.querySelector('.service-type-input').value = '';
                item.querySelector('.custom-name-row').classList.add('d-none');
                item.querySelector('.custom-name-input').required = false;
                item.querySelector('.custom-name-input').value = '';
            }
            calculateTotal();
        }

        function loadTemplateDetails(index) {
            const item = document.getElementById(`item-${index}`);
            const select = item.querySelector('.template-select');
            const priceInput = item.querySelector('.price-input');
            const serviceTypeInput = item.querySelector('.service-type-input');
            const customNameRow = item.querySelector('.custom-name-row');
            const customNameInput = item.querySelector('.custom-name-input');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value === '__custom__') {
                select.removeAttribute('name');
                serviceTypeInput.value = selectedOption.dataset.serviceTypeId || '';
                customNameRow.classList.remove('d-none');
                customNameInput.required = true;
                priceInput.value = 0;
                calculateTotal();
            } else {
                select.setAttribute('name', `items[${index}][service_template_id]`);
                serviceTypeInput.value = '';
                customNameRow.classList.add('d-none');
                customNameInput.required = false;
                customNameInput.value = '';
                if (selectedOption.value) {
                    priceInput.value = selectedOption.dataset.price;
                    calculateTotal();
                }
            }
        }

        function removeItem(index) {
            document.getElementById(`item-${index}`).remove();
            calculateTotal();
        }

        function calculateTotal() {
            // Calculate services total
            let servicesTotal = 0;
            document.querySelectorAll('.quotation-item').forEach(item => {
                const price = parseFloat(item.querySelector('.price-input').value) || 0;
                servicesTotal += price;
            });

            // Get service charge
            const serviceCharge = parseFloat(document.getElementById('service_charge')?.value) || 0;

            // Calculate subtotal
            const subtotal = servicesTotal + serviceCharge;

            // Get GST settings
            const gstType = document.querySelector('input[name="gst_type"]:checked')?.value || 'exclude';
            const gstPercentage = parseFloat(document.getElementById('gst_percentage')?.value) || 18;

            let gstAmount = 0;
            let totalAmount = 0;

            if (gstType === 'include') {
                // GST is included in the subtotal
                // Formula: GST Amount = Subtotal × (GST% / (100 + GST%))
                gstAmount = subtotal * (gstPercentage / (100 + gstPercentage));
                totalAmount = subtotal;
            } else {
                // GST is excluded, add to subtotal
                gstAmount = subtotal * (gstPercentage / 100);
                totalAmount = subtotal + gstAmount;
            }

            // Update displays
            document.getElementById('services-total').textContent = `₹${servicesTotal.toFixed(2)}`;
            document.getElementById('service-charge-display').textContent = `₹${serviceCharge.toFixed(2)}`;
            document.getElementById('subtotal-display').textContent = `₹${subtotal.toFixed(2)}`;
            document.getElementById('gst-percent-display').textContent = gstPercentage.toFixed(0);
            document.getElementById('gst-amount-display').textContent = `₹${gstAmount.toFixed(2)}`;

            // Check if total is overridden
            const isOverridden = document.getElementById('override_total')?.checked || false;
            if (isOverridden) {
                document.getElementById('calculated-total').textContent = `₹${totalAmount.toFixed(2)}`;
                const customTotal = parseFloat(document.getElementById('custom_total')?.value) || 0;
                document.getElementById('total-amount').textContent = `₹${customTotal.toFixed(2)}`;
            } else {
                document.getElementById('total-amount').textContent = `₹${totalAmount.toFixed(2)}`;
                document.getElementById('custom_total').value = totalAmount.toFixed(2);
            }

            // Update hidden fields
            document.getElementById('gst_amount').value = gstAmount.toFixed(2);
            document.getElementById('subtotal').value = subtotal.toFixed(2);
        }

        function toggleTotalOverride() {
            const isChecked = document.getElementById('override_total').checked;
            const displaySection = document.getElementById('total-display-section');
            const overrideSection = document.getElementById('total-override-section');

            if (isChecked) {
                displaySection.classList.add('d-none');
                overrideSection.classList.remove('d-none');
                // Set custom total to calculated total
                const currentTotal = document.getElementById('total-amount').textContent.replace('₹', '').replace(',', '');
                document.getElementById('custom_total').value = parseFloat(currentTotal) || 0;
                document.getElementById('calculated-total').textContent = document.getElementById('total-amount')
                    .textContent;
            } else {
                displaySection.classList.remove('d-none');
                overrideSection.classList.add('d-none');
                calculateTotal();
            }
        }

        function updateCustomTotal() {
            const customTotal = parseFloat(document.getElementById('custom_total')?.value) || 0;
            document.getElementById('total-amount').textContent = `₹${customTotal.toFixed(2)}`;
        }

        // Load existing items on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (existingItems.length > 0) {
                existingItems.forEach(item => {
                    addQuotationItem(item);
                });
            } else {
                addQuotationItem();
            }

            // Initialize EasyMDE for itinerary
            if (document.getElementById('itinerary_html')) {
                const easyMDE = new EasyMDE({
                    element: document.getElementById('itinerary_html'),
                    spellChecker: false,
                    placeholder: "Example:\n\n## Day 1: Arrival in Varanasi\n- Pick up from airport/railway station\n- Check-in to hotel\n- Evening Ganga Aarti at Dashashwamedh Ghat\n- Overnight stay\n\n## Day 2: Varanasi Sightseeing\n- Early morning boat ride on Ganges\n- Visit Kashi Vishwanath Temple\n- Sarnath excursion\n- Return to hotel",
                    toolbar: ["bold", "italic", "heading", "|", "unordered-list", "ordered-list", "|",
                        "link", "preview", "|", "guide"
                    ],
                    minHeight: "300px",
                    status: false,
                });
            }
        });
    </script>
@endsection
