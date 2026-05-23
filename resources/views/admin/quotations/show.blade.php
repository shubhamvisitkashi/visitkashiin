@extends('admin.layouts.app')

@section('content')
    <style>
        .quotation-show {
            width: 100%;
        }

        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .quotation-number {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .customer-info {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            opacity: 0.95;
        }

        .customer-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .btn-custom {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-box {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
            color: #111827;
            font-weight: 600;
        }

        .total-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .total-box .info-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .total-box .info-value {
            color: white;
            font-size: 1.75rem;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .services-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .services-table thead {
            background: #f9fafb;
        }

        .services-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .services-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .services-table tbody tr:hover {
            background: #f9fafb;
        }

        .pricing-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .pricing-row:last-child {
            border-bottom: none;
        }

        .pricing-label {
            color: #6b7280;
            font-weight: 500;
        }

        .pricing-value {
            font-weight: 600;
            color: #111827;
        }

        .final-total {
            background: #f0fdf4;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .final-total .pricing-label {
            font-size: 1.1rem;
            color: #111827;
            font-weight: 700;
        }

        .final-total .pricing-value {
            font-size: 1.5rem;
            color: #10b981;
            font-weight: 700;
        }

        .override-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .strikethrough {
            text-decoration: line-through;
            color: #9ca3af;
        }

        .itinerary-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .itinerary-box h5 {
            color: #92400e;
            margin-bottom: 1rem;
        }

        .notes-box {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 1.5rem;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .header-card {
                padding: 1.5rem;
            }

            .quotation-number {
                font-size: 1.25rem;
            }

            .customer-info {
                flex-direction: column;
                gap: 0.75rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .services-table {
                font-size: 0.875rem;
            }

            .services-table th,
            .services-table td {
                padding: 0.75rem 0.5rem;
            }

            .section-card {
                padding: 1rem;
            }
        }
    </style>

    <div class="page-content quotation-show">
        <!-- Header -->
        <div class="header-card">
            <div class="quotation-number">{{ $quotation->quotation_number }}</div>
            <div class="customer-info">
                <div class="customer-info-item">
                    <i data-feather="user"></i>
                    <span>{{ $quotation->lead->guest_name }}</span>
                </div>
                <div class="customer-info-item">
                    <i data-feather="phone"></i>
                    <span>{{ $quotation->lead->contact }}</span>
                </div>
                @if ($quotation->lead->email)
                    <div class="customer-info-item">
                        <i data-feather="mail"></i>
                        <span>{{ $quotation->lead->email }}</span>
                    </div>
                @endif
            </div>

            <div class="action-buttons">
                <a href="{{ route('quotations.index') }}" class="btn btn-light btn-custom btn-sm">
                    <i data-feather="arrow-left"></i> Back
                </a>
                @if ($quotation->status == 'draft')
                    <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-warning btn-custom btn-sm">
                        <i data-feather="edit"></i> Edit
                    </a>
                @endif
                <a href="{{ route('quotations.download-pdf', $quotation->id) }}" class="btn btn-success btn-custom btn-sm"
                    target="_blank">
                    <i data-feather="download"></i> Download PDF
                </a>
                <button type="button" class="btn btn-info btn-custom btn-sm" data-bs-toggle="modal"
                    data-bs-target="#emailModal">
                    <i data-feather="mail"></i> Send Email
                </button>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-box">
                <div class="info-label">Quotation Date</div>
                <div class="info-value">{{ $quotation->quotation_date->format('d M Y') }}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Valid Until</div>
                <div class="info-value">{{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : 'N/A' }}
                </div>
            </div>

            <div class="info-box">
                <div class="info-label">Status</div>
                <div class="info-value">
                    @if ($quotation->status == 'draft')
                        <span class="badge bg-secondary">Draft</span>
                    @elseif($quotation->status == 'sent')
                        <span class="badge bg-primary">Sent</span>
                    @elseif($quotation->status == 'accepted')
                        <span class="badge bg-success">Accepted</span>
                    @elseif($quotation->status == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-warning">Expired</span>
                    @endif
                </div>
                @if ($quotation->status != 'accepted' && $quotation->status != 'rejected')
                    <div class="mt-2">
                        <form action="{{ route('quotations.update-status', $quotation->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Update Status...</option>
                                <option value="sent">Mark as Sent</option>
                                <option value="accepted">Mark as Accepted</option>
                                <option value="rejected">Mark as Rejected</option>
                            </select>
                        </form>
                    </div>
                @endif
            </div>

            <div class="info-box total-box">
                <div class="info-label">Final Amount</div>
                <div class="info-value">₹{{ number_format($quotation->custom_total ?? $quotation->total_amount, 2) }}</div>
            </div>
        </div>

        <!-- Services -->
        <div class="section-card">
            <div class="section-title">
                <i data-feather="package"></i>
                Services Included
            </div>

            <div class="table-responsive">
                <table class="services-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Service</th>
                            <th width="20%" class="text-end">Price</th>
                            <th width="20%">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotation->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->serviceTemplate->name }}</strong><br>
                                    <small class="text-muted">{{ $item->serviceType->name }}</small>
                                </td>
                                <td class="text-end"><strong>₹{{ number_format($item->unit_price, 2) }}</strong></td>
                                <td>{{ $item->service_date ? $item->service_date->format('d M Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pricing Breakdown -->
        <div class="section-card">
            <div class="section-title">
                <i data-feather="dollar-sign"></i>
                Pricing Breakdown
            </div>

            <div class="pricing-row">
                <span class="pricing-label">Services Total</span>
                <span class="pricing-value">₹{{ number_format($quotation->items->sum('unit_price'), 2) }}</span>
            </div>

            @if ($quotation->service_charge > 0)
                <div class="pricing-row">
                    <span class="pricing-label">Service Charge</span>
                    <span class="pricing-value">₹{{ number_format($quotation->service_charge, 2) }}</span>
                </div>
            @endif

            <div class="pricing-row">
                <span class="pricing-label">Subtotal</span>
                <span class="pricing-value">₹{{ number_format($quotation->subtotal, 2) }}</span>
            </div>

            <div class="pricing-row">
                <span class="pricing-label">
                    GST ({{ number_format($quotation->gst_percentage, 0) }}%)
                    <small class="text-muted">({{ ucfirst($quotation->gst_type) }})</small>
                </span>
                <span class="pricing-value">₹{{ number_format($quotation->gst_amount, 2) }}</span>
            </div>

            @if ($quotation->custom_total && $quotation->custom_total != $quotation->total_amount)
                <div class="pricing-row">
                    <span class="pricing-label">Calculated Total</span>
                    <span class="pricing-value strikethrough">
                        ₹{{ number_format($quotation->gst_type === 'include' ? $quotation->subtotal : $quotation->subtotal + $quotation->gst_amount, 2) }}
                    </span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">
                        Custom Total
                        <span class="override-badge">Overridden</span>
                    </span>
                    <span class="pricing-value text-success">₹{{ number_format($quotation->custom_total, 2) }}</span>
                </div>
            @endif

            <div class="final-total">
                <div class="pricing-row">
                    <span class="pricing-label">Final Total Amount</span>
                    <span
                        class="pricing-value">₹{{ number_format($quotation->custom_total ?? $quotation->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Tour Plan / Itinerary -->
        @if ($quotation->itinerary_html)
            <div class="section-card">
                <div class="itinerary-box">
                    <h5><i data-feather="map"></i> Tour Plan / Itinerary</h5>
                    <div class="itinerary-content">
                        {!! \Illuminate\Support\Str::markdown($quotation->itinerary_html) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Notes -->
        @if ($quotation->notes)
            <div class="section-card">
                <div class="notes-box">
                    <h5><i data-feather="file-text"></i> Notes</h5>
                    <p class="mb-0">{{ $quotation->notes }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Email Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('quotations.send-email', $quotation->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Send Quotation via Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ $quotation->lead->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Custom Message (Optional)</label>
                            <textarea name="message" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="send"></i> Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            feather.replace();
        </script>
    @endpush
@endsection
