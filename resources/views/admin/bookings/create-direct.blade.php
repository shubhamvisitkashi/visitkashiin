@extends('admin.layouts.app')

@section('content')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        .quick-booking-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .customer-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .customer-info-card h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.95;
        }

        .service-row {
            background: #ffffff;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 2px solid #e9ecef;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .service-row:hover {
            border-color: #667eea;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #495057;
            margin-bottom: 0.4rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1.5px solid #e0e0e0;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .summary-card {
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .summary-card h5 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .summary-item {
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }

        .summary-item label {
            font-size: 0.8rem;
            color: #6b7280;
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .summary-item h3 {
            margin: 0;
            font-weight: 700;
            color: #1f2937;
        }

        .btn-add-service {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-add-service:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-remove {
            background: #ef4444;
            border: none;
            color: white;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-remove:hover {
            background: #dc2626;
            transform: scale(1.05);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.25rem;
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: #2d3748;
        }

        .amount-in-words {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            color: #92400e;
            font-weight: 500;
        }

        .btn-create-booking {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.85rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-create-booking:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .service-row {
            animation: slideIn 0.3s ease;
        }
    </style>

    <div class="page-content">
        <div class="quick-booking-container">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Create New Booking</h2>
                    <p class="text-muted mb-0">Quick booking without lead or quotation</p>
                </div>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left"></i> Back to Bookings
                </a>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i data-feather="alert-circle"></i> Validation Errors</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i data-feather="check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i data-feather="x-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('bookings.store-direct') }}" method="POST" id="quickBookingForm">
                @csrf

                <!-- Customer Information -->
                <div class="customer-info-card">
                    <h5><i data-feather="user"></i> Customer Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-white">Guest Name <span class="text-warning">*</span></label>
                            <input type="text" name="guest_name"
                                class="form-control @error('guest_name') is-invalid @enderror"
                                value="{{ old('guest_name') }}" required placeholder="Enter guest name">
                            @error('guest_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-white">Phone <span class="text-warning">*</span></label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" required placeholder="Enter phone number">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-white">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Enter email address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-white">Lead Source <span class="text-warning">*</span></label>
                            <select name="lead_source_id" class="form-select @error('lead_source_id') is-invalid @enderror"
                                required>
                                <option value="">Select Lead Source</option>
                                @foreach ($leadSources as $source)
                                    <option value="{{ $source->id }}"
                                        {{ old('lead_source_id') == $source->id ? 'selected' : '' }}>
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lead_source_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Short Description / Plan -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i data-feather="file-text"></i> Short Description / Plan <span
                                class="text-danger">*</span></h5>
                    </div>
                    <div class="card-body">
                        <textarea name="short_plan" id="short_plan_editor" class="form-control" rows="4" required></textarea>
                        <small class="text-muted">Brief description of customer requirements or travel plan</small>
                    </div>
                </div>

                <!-- Tour Plan / Itinerary (Detailed) -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i data-feather="map"></i> Tour Plan / Itinerary (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="tour_plan" id="itinerary_editor" class="form-control" rows="6"></textarea>
                        <small class="text-muted">Detailed itinerary with day-by-day plan</small>
                    </div>
                </div>

                <!-- Services -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i data-feather="package"></i> Services</h5>
                        <button type="button" class="btn-add-service" onclick="addService()">
                            <i data-feather="plus" style="width: 16px; height: 16px;"></i> Add Service
                        </button>
                    </div>
                    <div class="card-body" id="services_container">
                        <!-- Services will be added here -->
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i data-feather="message-square"></i> Notes (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="internal_notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                    </div>
                </div>

                <!-- Summary Section (at bottom) -->
                <div class="summary-card">
                    <h5 class="mb-4"><i data-feather="check-circle"></i> Booking Summary</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="summary-item">
                                <label>Total Services</label>
                                <h3 id="total_services">0</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <label>Total Amount</label>
                                <h3 id="total_amount">₹0.00</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <button type="submit" class="btn-create-booking w-100">
                                    <i data-feather="check" style="width: 18px; height: 18px;"></i>
                                    Create Booking
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Amount in Words -->
                    <div class="amount-in-words">
                        <i data-feather="info" style="width: 16px; height: 16px;"></i>
                        <strong>Amount in Words:</strong> <span id="amount_in_words">Zero Rupees Only</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Template -->
    <template id="service_template">
        <div class="service-row" data-index="INDEX">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Service Date <span class="text-danger">*</span></label>
                    <input type="date" name="services[INDEX][service_date]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Service Type <span class="text-danger">*</span></label>
                    <select class="form-select service-type-select" required onchange="updateServiceOptions(INDEX)">
                        <option value="">Select Service Type</option>
                        @foreach ($serviceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Service <span class="text-danger">*</span></label>
                    <select name="services[INDEX][service_template_id]" class="form-select service-select" required
                        onchange="updateServicePrice(INDEX)" disabled>
                        <option value="">Select Service Type First</option>
                    </select>
                    <!-- Hidden quantity field for backend compatibility -->
                    <input type="hidden" name="services[INDEX][quantity]" value="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Price <span class="text-danger">*</span></label>
                    <input type="number" name="services[INDEX][unit_price]" class="form-control price-input"
                        min="0" step="0.01" required onchange="calculateTotal()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn-remove w-100" onclick="removeService(INDEX)">
                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i> Remove
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
        let serviceIndex = 0;

        // Number to Words Conversion (Indian English)
        function numberToWords(num) {
            if (num === 0) return 'Zero Rupees Only';

            const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen',
                'Nineteen'
            ];

            function convertLessThanThousand(n) {
                if (n === 0) return '';
                if (n < 10) return ones[n];
                if (n < 20) return teens[n - 10];
                if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 !== 0 ? ' ' + ones[n % 10] : '');
                return ones[Math.floor(n / 100)] + ' Hundred' + (n % 100 !== 0 ? ' ' + convertLessThanThousand(n % 100) :
                    '');
            }

            const crore = Math.floor(num / 10000000);
            const lakh = Math.floor((num % 10000000) / 100000);
            const thousand = Math.floor((num % 100000) / 1000);
            const remainder = num % 1000;

            let result = '';
            if (crore > 0) result += convertLessThanThousand(crore) + ' Crore ';
            if (lakh > 0) result += convertLessThanThousand(lakh) + ' Lakh ';
            if (thousand > 0) result += convertLessThanThousand(thousand) + ' Thousand ';
            if (remainder > 0) result += convertLessThanThousand(remainder);

            return result.trim() + ' Rupees Only';
        }

        function addService() {
            const template = document.getElementById('service_template').content.cloneNode(true);
            const html = template.querySelector('.service-row').outerHTML.replace(/INDEX/g, serviceIndex);
            document.getElementById('services_container').insertAdjacentHTML('beforeend', html);
            serviceIndex++;
            feather.replace();
            calculateTotal();
        }

        function removeService(index) {
            document.querySelector(`.service-row[data-index="${index}"]`).remove();
            calculateTotal();
        }

        function updateServicePrice(index) {
            const select = document.querySelector(`select[name="services[${index}][service_template_id]"]`);
            const priceInput = document.querySelector(`input[name="services[${index}][unit_price]"]`);
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption && selectedOption.dataset.price) {
                priceInput.value = selectedOption.dataset.price;
                calculateTotal();
            }
        }

        function calculateTotal() {
            let totalServices = 0;
            let totalAmount = 0;

            document.querySelectorAll('.service-row').forEach(row => {
                const price = parseFloat(row.querySelector('.price-input')?.value || 0);

                if (price > 0) {
                    totalServices++;
                    totalAmount += price;
                }
            });

            document.getElementById('total_services').textContent = totalServices;
            document.getElementById('total_amount').textContent = '₹' + totalAmount.toFixed(2);
            document.getElementById('amount_in_words').textContent = numberToWords(Math.floor(totalAmount));
        }

        // Update service options based on selected service type
        function updateServiceOptions(index) {
            const serviceTypeSelect = document.querySelector(`.service-row[data-index="${index}"] .service-type-select`);
            const serviceSelect = document.querySelector(`select[name="services[${index}][service_template_id]"]`);
            const priceInput = document.querySelector(`input[name="services[${index}][unit_price]"]`);

            const selectedTypeId = serviceTypeSelect.value;

            // Clear current service options
            serviceSelect.innerHTML = '<option value="">Select Service</option>';

            if (!selectedTypeId) {
                serviceSelect.disabled = true;
                serviceSelect.innerHTML = '<option value="">Select Service Type First</option>';
                priceInput.value = '';
                calculateTotal();
                return;
            }

            // Enable service dropdown
            serviceSelect.disabled = false;

            // Get services for selected type
            const serviceTypes = @json($serviceTypes);
            const selectedType = serviceTypes.find(type => type.id == selectedTypeId);

            if (selectedType && selectedType.service_templates) {
                selectedType.service_templates.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.id;
                    option.dataset.price = template.default_selling_price;
                    option.textContent =
                        `${template.name} (₹${parseFloat(template.default_selling_price).toFixed(2)})`;
                    serviceSelect.appendChild(option);
                });
            }

            // Reset price
            priceInput.value = '';
            calculateTotal();
        }

        // Add first service on page load
        document.addEventListener('DOMContentLoaded', function() {
            addService();
            feather.replace();

            // Form submission logging
            document.getElementById('quickBookingForm').addEventListener('submit', function(e) {
                console.log('Form submitting...');
                const services = document.querySelectorAll('.service-row');
                console.log('Total services:', services.length);
            });
        });
    </script>
@endsection

@section('script')
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Replace feather icons in alerts
            feather.replace();
            // Initialize Short Plan Editor
            $('#short_plan_editor').summernote({
                height: 150,
                placeholder: 'Brief description of customer requirements or travel plan...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['view', ['codeview']]
                ]
            });

            // Initialize Itinerary Editor
            $('#itinerary_editor').summernote({
                height: 200,
                placeholder: 'Enter detailed tour itinerary here...',
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
                styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
                fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact',
                    'Tahoma', 'Times New Roman', 'Verdana'
                ],
                callbacks: {
                    onInit: function() {
                        console.log('Summernote initialized');
                    }
                }
            });
        });
    </script>
@endsection
