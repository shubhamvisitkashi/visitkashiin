@extends('admin.layouts.app')

@section('content')
    <style>
        /* Modern Trash Page Styling */
        .trash-header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .booking-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            border-left: 4px solid #fca5a5;
            opacity: 0.9;
        }

        .booking-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            opacity: 1;
        }

        .booking-card .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .booking-card .booking-number {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .booking-card .customer-name {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .booking-card .booking-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        @media(max-width:600px){
            .booking-card .booking-info{grid-template-columns:repeat(2,1fr);gap:.6rem;}
        }
        @media(max-width:360px){
            .booking-card .booking-info{grid-template-columns:1fr;gap:.5rem;}
        }

        .booking-card .info-item {
            display: flex;
            flex-direction: column;
        }

        .booking-card .info-label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .booking-card .info-value {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .booking-card .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.625rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: white;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .action-btn.btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .action-btn.btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .badge-modern {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="page-content">
        <!-- Header -->
        <div class="trash-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">🗑️ Deleted Bookings</h2>
                    <p class="mb-0 opacity-90">Recover or permanently delete bookings</p>
                </div>
                <a href="{{ route('bookings.index') }}" class="btn btn-light btn-lg">
                    <i data-feather="arrow-left"></i> Back to Bookings
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Bookings List -->
        <div class="bookings-list">
            @forelse ($bookings as $booking)
                <div class="booking-card">
                    <div class="booking-header">
                        <div>
                            <div class="booking-number">{{ $booking->booking_number }}</div>
                            <div class="customer-name">
                                <i data-feather="user" style="width: 14px; height: 14px;"></i>
                                {{ optional($booking->lead)->guest_name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="badge-modern bg-danger">Deleted</span>
                        </div>
                    </div>

                    <div class="booking-info">
                        <div class="info-item">
                            <div class="info-label">Deleted On</div>
                            <div class="info-value">{{ $booking->deleted_at->format('d M Y, h:i A') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Deleted By</div>
                            <div class="info-value">{{ optional($booking->deletedBy)->name ?? 'System' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Booking Date</div>
                            <div class="info-value">{{ $booking->booking_date->format('d M Y') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Total Amount</div>
                            <div class="info-value">
                                <strong class="text-primary">₹{{ number_format($booking->total_amount, 2) }}</strong>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Contact</div>
                            <div class="info-value">
                                <i data-feather="phone" style="width: 14px; height: 14px;"></i>
                                {{ optional($booking->lead)->contact ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <form action="{{ route('bookings.restore', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="action-btn btn-success" title="Restore Booking">
                                <i data-feather="rotate-ccw" style="width: 16px; height: 16px;"></i>
                                <span>Restore</span>
                            </button>
                        </form>

                        <button type="button" class="action-btn btn-danger" title="Permanently Delete"
                            onclick="confirmPermanentDelete({{ $booking->id }}, '{{ $booking->booking_number }}')">
                            <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                            <span>Delete Permanently</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <h4>No Deleted Bookings</h4>
                    <p>The trash is empty. Deleted bookings will appear here.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($bookings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {!! $bookings->links() !!}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            setTimeout(() => {
                feather.replace();
            }, 100);
        });

        // Permanent delete confirmation function
        function confirmPermanentDelete(bookingId, bookingNumber) {
            document.getElementById('deleteBookingNumber').textContent = bookingNumber;
            document.getElementById('deleteForm').action = '{{ url('admin/bookings') }}/' + bookingId + '/force-delete';

            var deleteModal = new bootstrap.Modal(document.getElementById('permanentDeleteModal'));
            deleteModal.show();
        }
    </script>

    <!-- Permanent Delete Confirmation Modal -->
    <div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-labelledby="permanentDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="permanentDeleteModalLabel">
                        <i data-feather="alert-triangle"></i> Permanent Delete Warning
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i data-feather="alert-octagon" style="width: 64px; height: 64px; color: #ef4444;"></i>
                        </div>
                        <h6 class="text-center mb-3">⚠️ This action CANNOT be undone!</h6>
                        <div class="alert alert-danger">
                            <strong>Booking #<span id="deleteBookingNumber"></span></strong>
                        </div>
                        <p class="text-muted small mb-0">
                            <strong>Warning:</strong> This will permanently delete the booking and all associated data
                            including payments and service assignments. This data cannot be recovered.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i data-feather="x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="trash-2"></i> Yes, Delete Permanently
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
