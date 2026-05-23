@extends('admin.layouts.app')

@section('content')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        /* Mobile-First Responsive Design */
        body {
            padding-bottom: 120px;
            /* Space for sticky summary */
        }

        .quick-booking-container {
            max-width: 100%;
            padding: 0.5rem;
        }

        /* Compact Customer Header */
        .customer-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .customer-info-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .customer-info-card h3 i {
            width: 18px;
            height: 18px;
        }

        .customer-info-card p {
            margin-bottom: 0.25rem;
            opacity: 0.95;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .customer-info-card p i {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.25rem;
        }

        .back-btn-mobile {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            margin-top: 0.5rem;
        }

        .back-btn-mobile i {
            width: 14px;
            height: 14px;
        }

        /* Compact Service Rows */
        .service-row {
            background: #ffffff;
            border-radius: 10px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            border: 1.5px solid #e9ecef;
            transition: all 0.2s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .service-row:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.75rem;
            color: #495057;
            margin-bottom: 0.3rem;
            display: block;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1.5px solid #e0e0e0;
            padding: 0.5rem 0.6rem;
            font-size: 0.85rem;
            transition: all 0.2s;
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        /* Compact Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06);
            margin-bottom: 1rem;
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 1.5px solid #e9ecef;
            border-radius: 10px 10px 0 0 !important;
            padding: 0.75rem 1rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 0.95rem;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-header h5 i {
            width: 18px;
            height: 18px;
        }

        .card-body {
            padding: 0.75rem 1rem;
        }

        /* Compact Buttons */
        .btn-add-service {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-add-service:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-add-service i {
            width: 14px;
            height: 14px;
        }

        .btn-remove {
            background: #ef4444;
            border: none;
            color: white;
            padding: 0.4rem;
            border-radius: 6px;
            transition: all 0.2s;
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .btn-remove:hover {
            background: #dc2626;
            transform: scale(1.05);
        }

        .btn-remove i {
            width: 14px;
            height: 14px;
        }

        /* Sticky Bottom Summary */
        .summary-sticky {
            display: none;
            /* Hidden by default, shown on desktop with d-none d-md-block */
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.75rem 1rem;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .summary-info {
            display: flex;
            gap: 1rem;
            flex: 1;
        }

        .summary-stat {
            text-align: center;
        }

        .summary-stat-label {
            font-size: 0.7rem;
            opacity: 0.9;
            display: block;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .summary-stat-value {
            font-size: 1.1rem;
            font-weight: 700;
            display: block;
        }

        /* Highlight total amount */
        #total_amount {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .btn-create-booking {
            background: white;
            color: #059669;
            border: none;
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .btn-create-booking:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: #059669;
        }

        .btn-create-booking i {
            width: 16px;
            height: 16px;
        }

        /* Collapsible Sections */
        .collapsible-toggle {
            cursor: pointer;
            user-select: none;
        }

        .collapsible-toggle::after {
            content: '▼';
            float: right;
            font-size: 0.8rem;
            transition: transform 0.3s;
        }

        .collapsible-toggle.collapsed::after {
            transform: rotate(-90deg);
        }

        /* Mobile Optimizations - Maximize Space */
        @media (max-width: 768px) {
            body {
                padding-bottom: 120px !important;
                /* Space for mobile summary card */
            }

            .quick-booking-container {
                padding: 0;
                /* Remove container padding on mobile */
            }

            .customer-info-card {
                padding: 0.75rem;
                margin-bottom: 0.75rem;
                border-radius: 0;
                /* Flush with edges */
            }

            .customer-info-card h3 {
                font-size: 1rem;
            }

            /* Remove card styling on mobile - flat design */
            .card {
                border: none;
                border-radius: 0;
                box-shadow: none;
                margin-bottom: 0;
                background: transparent;
            }

            .card-header {
                padding: 0.75rem;
                background: #f8f9fa;
                border-bottom: 1px solid #e9ecef;
                border-radius: 0;
                margin-bottom: 0.5rem;
            }

            .card-body {
                padding: 0 0.5rem;
                /* Minimal horizontal padding */
            }

            .service-row {
                padding: 0.75rem;
                margin-bottom: 0.75rem;
                border-radius: 8px;
            }

            .form-control,
            .form-select {
                font-size: 0.85rem;
                padding: 0.5rem;
            }

            .summary-info {
                gap: 0.5rem;
            }

            .summary-stat-value {
                font-size: 0.9rem;
            }

            .btn-create-booking {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }

            /* Stack service fields vertically on mobile */
            .service-row .row {
                row-gap: 0.5rem;
            }

            .service-row .col-6,
            .service-row .col-12 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

        /* Desktop Optimizations */
        @media (min-width: 769px) {
            .quick-booking-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 1rem;
            }

            .customer-info-card {
                padding: 1rem 1.25rem;
            }

            .summary-sticky {
                position: relative;
                border-radius: 12px;
                margin-top: 1rem;
            }

            body {
                padding-bottom: 0;
            }
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

        /* Textarea compact */
        textarea.form-control {
            min-height: 60px;
        }

        /* Summernote compact */
        .note-editor {
            border-radius: 6px;
        }

        .note-toolbar {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
    </style>

    <div class="page-content">
        <div class="quick-booking-container">
            <!-- Compact Customer Header -->
            <div class="customer-info-card">
                <h3><i data-feather="user"></i> {{ $lead->guest_name }}</h3>
                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <p><i data-feather="phone"></i> {{ $lead->contact }}</p>
                    </div>
                    @if ($lead->email)
                        <div class="col-md-6">
                            <p><i data-feather="mail"></i> {{ $lead->email }}</p>
                        </div>
                    @endif
                    @if ($lead->short_plan)
                        <div class="col-12">
                            <p><i data-feather="calendar"></i> {!! strip_tags($lead->short_plan) !!}</p>
                        </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('quick-booking.create', $lead->id) }}" method="POST" id="quickBookingForm">
                @csrf
                <input type="hidden" name="create_quotation" value="0">

                <!-- Tour Plan (Open by default) -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i data-feather="map"></i> Tour Plan (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="itinerary" id="itinerary_editor" class="form-control" rows="4"></textarea>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i data-feather="package"></i> Services</h5>
                    </div>
                    <div class="card-body" id="services_container">
                        <!-- Services will be added here -->
                    </div>
                    <!-- Add Service Button - Common for all services -->
                    <div class="card-footer bg-white border-top-0 text-center pt-0">
                        <button type="button" class="btn-add-service" onclick="addService()">
                            <i data-feather="plus"></i> Add Service
                        </button>
                    </div>
                </div>

                <!-- Collapsible Notes -->
                <div class="card mb-4">
                    <div class="card-header collapsible-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#notesSection">
                        <h5 class="mb-0"><i data-feather="message-square"></i> Notes (Optional)</h5>
                    </div>
                    <div class="collapse" id="notesSection">
                        <div class="card-body">
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Mobile Summary Card (Premium UI) -->
                <div class="card mb-3 d-md-none"
                    style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border: none; border-radius: 16px; box-shadow: 0 8px 24px rgba(5, 150, 105, 0.3); overflow: hidden;">
                    <div class="card-body" style="padding: 1.25rem;">
                        <!-- Summary Header -->
                        <div
                            style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(255, 255, 255, 0.2);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="opacity: 0.9;">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="9" y1="9" x2="15" y2="9"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                            <span
                                style="font-size: 0.85rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; opacity: 0.95;">Booking
                                Summary</span>
                        </div>

                        <!-- Stats Row -->
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem;">
                            <div style="flex: 1;">
                                <!-- Services Count -->
                                <div
                                    style="background: rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 0.75rem; text-align: center; backdrop-filter: blur(10px);">
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; gap: 0.4rem; margin-bottom: 0.4rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z">
                                            </path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                        <span
                                            style="font-size: 0.7rem; font-weight: 600; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Services</span>
                                    </div>
                                    <span style="font-size: 1.5rem; font-weight: 900; display: block; line-height: 1;"
                                        id="mobile_total_services">0</span>
                                </div>
                            </div>

                            <div style="flex: 1;">
                                <!-- Total Amount -->
                                <div
                                    style="background: rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 0.75rem; text-align: center; backdrop-filter: blur(10px);">
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; gap: 0.4rem; margin-bottom: 0.4rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        <span
                                            style="font-size: 0.7rem; font-weight: 600; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Total</span>
                                    </div>
                                    <span
                                        style="font-size: 1.75rem; font-weight: 900; display: block; line-height: 1; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);"
                                        id="mobile_total_amount">₹0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Create Button -->
                        <button type="submit"
                            style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.9rem 1.5rem; font-size: 1.05rem; font-weight: 800; background: white; color: #059669; border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; letter-spacing: 0.5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>CREATE BOOKING</span>
                        </button>
                    </div>
                </div>

                <!-- Desktop Summary (Premium UI) -->
                <div class="summary-sticky d-none d-md-block">
                    <div
                        style="margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(255, 255, 255, 0.2);">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="opacity: 0.9;">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="9" y1="9" x2="15" y2="9"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                            <span
                                style="font-size: 0.95rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; opacity: 0.95;">Booking
                                Summary</span>
                        </div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-info">
                            <!-- Services Stat Card -->
                            <div
                                style="background: rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 1rem; text-align: center; backdrop-filter: blur(10px); flex: 1;">
                                <div
                                    style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z">
                                        </path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    <span class="summary-stat-label" style="margin-bottom: 0;">Services</span>
                                </div>
                                <span class="summary-stat-value" id="total_services" style="font-size: 1.75rem;">0</span>
                            </div>

                            <!-- Total Amount Stat Card -->
                            <div
                                style="background: rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 1rem; text-align: center; backdrop-filter: blur(10px); flex: 1;">
                                <div
                                    style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    <span class="summary-stat-label" style="margin-bottom: 0;">Total Amount</span>
                                </div>
                                <span class="summary-stat-value" id="total_amount"
                                    style="font-size: 2rem; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">₹0</span>
                            </div>
                        </div>

                        <!-- Create Button -->
                        <button type="submit" class="btn-create-booking"
                            style="padding: 1rem 2rem; font-size: 1.1rem; min-width: 200px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2); letter-spacing: 0.5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>CREATE BOOKING</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Compact Service Template -->
    <template id="service_template">
        <div class="service-row" data-index="INDEX">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="services[INDEX][service_date]" class="form-control" required>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select class="form-select service-type-select" required onchange="updateServiceOptions(INDEX)">
                        <option value="">Select Type</option>
                        @foreach ($serviceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Service <span class="text-danger">*</span></label>
                    <select name="services[INDEX][service_template_id]" class="form-select service-select" required
                        onchange="updateServicePrice(INDEX)" disabled>
                        <option value="">Select Type First</option>
                    </select>
                    <input type="hidden" name="services[INDEX][quantity]" value="1">
                </div>
                <div class="col-8 col-md-3">
                    <label class="form-label">Price <span class="text-danger">*</span></label>
                    <input type="number" name="services[INDEX][unit_price]" class="form-control price-input"
                        min="0" step="0.01" required onchange="calculateTotal()">
                </div>
                <!-- Remove Button -->
                <div class="col-4 col-md-1">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="button" class="btn-remove w-100" onclick="removeService(INDEX)"
                        title="Remove Service">
                        <i data-feather="trash-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
        let serviceIndex = 0;

        function addService() {
            const template = document.getElementById('service_template').content.cloneNode(true);
            let html = template.querySelector('.service-row').outerHTML;
            html = html.replace(/INDEX/g, serviceIndex);
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

            // Update desktop/sticky summary
            document.getElementById('total_services').textContent = totalServices;
            document.getElementById('total_amount').textContent = '₹' + totalAmount.toFixed(0);

            // Update mobile summary
            const mobileServices = document.getElementById('mobile_total_services');
            const mobileAmount = document.getElementById('mobile_total_amount');
            if (mobileServices) mobileServices.textContent = totalServices;
            if (mobileAmount) mobileAmount.textContent = '₹' + totalAmount.toFixed(0);
        }

        function updateServiceOptions(index) {
            const serviceTypeSelect = document.querySelector(`.service-row[data-index="${index}"] .service-type-select`);
            const serviceSelect = document.querySelector(`select[name="services[${index}][service_template_id]"]`);
            const priceInput = document.querySelector(`input[name="services[${index}][unit_price]"]`);

            const selectedTypeId = serviceTypeSelect.value;
            serviceSelect.innerHTML = '<option value="">Select Service</option>';

            if (!selectedTypeId) {
                serviceSelect.disabled = true;
                serviceSelect.innerHTML = '<option value="">Select Type First</option>';
                priceInput.value = '';
                calculateTotal();
                return;
            }

            serviceSelect.disabled = false;
            const serviceTypes = @json($serviceTypes);
            const selectedType = serviceTypes.find(type => type.id == selectedTypeId);

            if (selectedType && selectedType.service_templates) {
                selectedType.service_templates.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.id;
                    option.dataset.price = template.default_selling_price;
                    option.textContent =
                        `${template.name} (₹${parseFloat(template.default_selling_price).toFixed(0)})`;
                    serviceSelect.appendChild(option);
                });
            }

            priceInput.value = '';
            calculateTotal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            addService();
            feather.replace();

            // Handle collapse toggle icons
            document.querySelectorAll('.collapsible-toggle').forEach(el => {
                el.addEventListener('click', function() {
                    this.classList.toggle('collapsed');
                });
            });
        });
    </script>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#itinerary_editor').summernote({
                height: 150,
                placeholder: 'Enter tour itinerary...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic']],
                    ['para', ['ul', 'ol']],
                ],
            });
        });
    </script>
@endsection
