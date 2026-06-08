<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Services\GstInvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:booking-list|booking-create', ['only' => ['index', 'calendar', 'calendarEvents']]);
        $this->middleware('permission:booking-view', ['only' => ['show']]);
        $this->middleware('permission:booking-edit', ['only' => ['edit', 'update', 'addService', 'deleteService']]);
        $this->middleware('permission:booking-delete', ['only' => ['destroy', 'trash', 'restore', 'forceDelete']]);
        $this->middleware('permission:booking-status-change', ['only' => ['updateStatus']]);
        $this->middleware('permission:payment-create', ['only' => ['addPayment']]);
        $this->middleware('permission:booking-assign-service', ['only' => ['assignService']]);
    }

    /**
     * Display a listing of bookings (optimized)
     * Role-based filtering:
     * - Super Admin, Admin, Manager: See all bookings
     * - Staff and other roles: See only their own bookings
     */
    public function index(Request $request)
    {
        // Build base query without status filter (for statistics)
        $baseQuery = Booking::query()
            ->search($request->search)
            // Payment status filtering
            ->when($request->payment_status, fn($q, $paymentStatus) => $q->byPaymentStatus($paymentStatus))
            // Due amount filtering
            ->when($request->has('has_due') && $request->has_due !== '', function($q) use ($request) {
                if ($request->has_due == '1') {
                    return $q->withDueAmount();
                } else {
                    return $q->where('pending_amount', '<=', 0);
                }
            })
            // Service date range filtering
            ->when($request->service_date_from || $request->service_date_to, function($query) use ($request) {
                $query->whereHas('quotation.items', function($q) use ($request) {
                    if ($request->service_date_from) {
                        $q->where('service_date', '>=', $request->service_date_from);
                    }
                    if ($request->service_date_to) {
                        $q->where('service_date', '<=', $request->service_date_to);
                    }
                });
            })
            // Staff filter
            ->when($request->staff_id, function($query) use ($request) {
                $query->where('created_by', $request->staff_id);
            })
            // Service type filter
            ->when($request->service_type, function($query) use ($request) {
                $query->whereHas('quotation.items.serviceTemplate.serviceType', function($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->service_type . '%');
                });
            })
            // Role-based filtering
            ->when(!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']), function($query) {
                $query->where('created_by', auth('admin')->id());
            });

        // Calculate statistics from base query (without status filter)
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'not_started' => (clone $baseQuery)->where('booking_status', 'not_started')->count(),
            'confirmed' => (clone $baseQuery)->where('booking_status', 'confirmed')->count(),
            'in_progress' => (clone $baseQuery)->where('booking_status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('booking_status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('booking_status', 'cancelled')->count(),
            'with_due' => (clone $baseQuery)->where('pending_amount', '>', 0)->count(),
        ];

        // Build the full query with all filters (including status) for results
        $bookings = Booking::with(['lead', 'quotation', 'createdBy'])
            ->search($request->search)
            ->when($request->status, fn($q, $status) => $q->byStatus($status))
            // Payment status filtering
            ->when($request->payment_status, function($query) use ($request) {
                if($request->payment_status == 'unpaid') {
                    $query->where('paid_amount', 0.00);
                }elseif($request->payment_status == 'partially_paid') {
                    $query->where('pending_amount', '>', 0.00);
                }elseif($request->payment_status == 'fully_paid') {
                    $query->where('pending_amount', 0.00);
                }
            })
            // Service date range filtering
            ->when($request->service_date_from || $request->service_date_to, function($query) use ($request) {
                $query->whereHas('quotation.items', function($q) use ($request) {
                    if ($request->service_date_from) {
                        $q->where('service_date', '>=', $request->service_date_from);
                    }
                    if ($request->service_date_to) {
                        $q->where('service_date', '<=', $request->service_date_to);
                    }
                });
            })
            // Staff filter
            ->when($request->staff_id, function($query) use ($request) {
                $query->where('created_by', $request->staff_id);
            })
            // Service type filter
            ->when($request->service_type, function($query) use ($request) {
                $query->whereHas('quotation.items.serviceTemplate.serviceType', function($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->service_type . '%');
                });
            })
            // Role-based filtering
            ->when(!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']), function($query) {
                $query->where('created_by', auth('admin')->id());
            })
            ->latest()
            ->paginate(15);

        // Get all staff for filter dropdown (only for admins/managers)
        $staffList = [];
        if (auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            $staffList = \App\Models\Admin\Admin::select('id', 'name')->orderBy('name')->get();
        }

        // Get all service types for filter dropdown
        $serviceTypes = \App\Models\ServiceType::select('id', 'name')->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'stats', 'staffList', 'serviceTypes'), ['page_title' => 'Bookings']);
    }

    public function show($id)
    {
        $booking = Booking::with(['lead', 'quotation.items.serviceTemplate.serviceType', 'quotation.items.serviceType', 'payments.paymentAccount', 'serviceAssignments'])
            ->withSum('payments', 'amount')
            ->findOrFail($id);

        // Role-based authorization: Staff can only view their own bookings
        if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            if ($booking->created_by !== auth('admin')->id()) {
                abort(403, 'Unauthorized access. You can only view bookings you created.');
            }
        }

        // Redirect tour package bookings to the dedicated tour detail page
        $firstItem = $booking->quotation?->items?->first();
        $stName = strtolower(
            optional($firstItem?->serviceTemplate?->serviceType)->name
            ?? optional($firstItem?->serviceType)->name
            ?? ''
        );
        if (str_contains($stName, 'tour') || str_contains($stName, 'package')) {
            return redirect()->route('tour-booking.show', $id);
        }

        $paymentAccounts = \App\Models\PaymentAccount::active()->get();

        // Get service templates and types for adding new services
        $serviceTemplates = \App\Models\ServiceTemplate::with('serviceType')->active()->get();
        $serviceTypes = \App\Models\ServiceType::active()->get();

        // Get activity logs for this booking
        $activities = $booking->activities()
            ->with('causer')
            ->latest()
            ->paginate(10);

        return view('admin.bookings.show', compact('booking', 'paymentAccounts', 'serviceTemplates', 'serviceTypes', 'activities'), ['page_title' => 'Booking Details']);
    }

    /**
     * Show the form for editing a booking
     */
    public function edit($id)
    {
        $booking = Booking::with([
            'lead',
            'quotation.items.serviceTemplate.serviceType',
            'quotation.items.serviceType',
            'payments.paymentAccount',
        ])->findOrFail($id);

        // Redirect tour bookings to the dedicated tour detail/edit page
        $firstItem = $booking->quotation?->items?->first();
        $stName = strtolower(
            optional($firstItem?->serviceTemplate?->serviceType)->name
            ?? optional($firstItem?->serviceType)->name
            ?? ''
        );
        if (str_contains($stName, 'tour') || str_contains($stName, 'package')) {
            return redirect()->route('tour-booking.show', $id);
        }

        // Role-based authorization: Staff can only edit their own bookings
        if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            if ($booking->created_by !== auth('admin')->id()) {
                abort(403, 'Unauthorized access. You can only edit bookings you created.');
            }
        }

        $serviceTypes     = \App\Models\ServiceType::with(['serviceTemplates' => function($q) {
            $q->where('is_active', 1)->orderBy('name');
        }])->get();
        $paymentAccounts  = \App\Models\PaymentAccount::where('is_active', 1)->orderBy('account_name')->get();

        return view('admin.bookings.edit', compact('booking', 'serviceTypes', 'paymentAccounts'), ['page_title' => 'Modify Booking']);
    }

    /**
     * Update the booking details
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::with(['lead', 'quotation.items', 'payments'])->findOrFail($id);

        // Role-based authorization
        if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            if ($booking->created_by !== auth('admin')->id()) {
                abort(403, 'Unauthorized access. You can only update bookings you created.');
            }
        }

        $validated = $request->validate([
            'guest_name'         => 'required|string|max:255',
            'contact'            => 'required|string|max:20',
            'alt_phone'          => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'country'            => 'nullable|string|max:100',
            'pax'                => 'nullable|string|max:20',
            'tour_plan'          => 'nullable|string',
            'short_plan'         => 'nullable|string',
            'booking_date'       => 'required|date',
            'booking_start_date' => 'nullable|date',
            'booking_end_date'   => 'nullable|date',
            'booking_status'     => 'nullable|in:confirmed,in_progress,completed,cancelled',
            'discount'           => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string',
            // Existing service rows
            'services'                    => 'nullable|array',
            'services.*.id'               => 'nullable|exists:quotation_items,id',
            'services.*.quantity'         => 'required|integer|min:1',
            'services.*.unit_price'       => 'required|numeric|min:0',
            'services.*.total_price'      => 'required|numeric|min:0',
            'services.*.service_date'     => 'nullable|date',
            // New service rows
            'new_services'                        => 'nullable|array',
            'new_services.*.service_template_id'  => 'nullable|exists:service_templates,id',
            'new_services.*.quantity'             => 'required|integer|min:1',
            'new_services.*.unit_price'           => 'required|numeric|min:0',
            'new_services.*.service_date'         => 'nullable|date',
            // Deleted services
            'deleted_services'    => 'nullable|array',
            'deleted_services.*'  => 'exists:quotation_items,id',
        ]);

        DB::beginTransaction();
        try {
            // ── Lead update ─────────────────────────────────────────────
            if ($booking->lead) {
                $leadData = [
                    'guest_name'         => $validated['guest_name'],
                    'contact'            => $validated['contact'],
                    'alt_phone'          => $validated['alt_phone'] ?? null,
                    'email'              => $validated['email'] ?? null,
                    'country'            => $validated['country'] ?? null,
                    'pax'                => $validated['pax'] ?? null,
                    'booking_start_date' => $validated['booking_start_date'] ?? null,
                    'booking_end_date'   => $validated['booking_end_date'] ?? null,
                    'notes'              => $validated['notes'] ?? null,
                    'plan_detail'        => $validated['tour_plan'] ?? null,
                ];
                if ($request->filled('short_plan')) {
                    $leadData['short_plan'] = $validated['short_plan'];
                }
                $booking->lead->update($leadData);
            }

            // ── Service updates ─────────────────────────────────────────
            if (!empty($validated['services'])) {
                foreach ($validated['services'] as $svc) {
                    if (!empty($svc['id'])) {
                        // Update existing item
                        $item = \App\Models\QuotationItem::find($svc['id']);
                        if ($item) {
                            $item->update([
                                'quantity'     => $svc['quantity'],
                                'unit_price'   => $svc['unit_price'],
                                'total_price'  => $svc['total_price'],
                                'service_date' => $svc['service_date'] ?? null,
                            ]);
                        }
                    } elseif (!empty($svc['unit_price']) && $booking->quotation) {
                        // No existing item — create a new one
                        $tplId    = $svc['service_template_id'] ?? null;
                        $template = $tplId ? \App\Models\ServiceTemplate::find($tplId) : null;
                        \App\Models\QuotationItem::create([
                            'quotation_id'        => $booking->quotation->id,
                            'service_template_id' => $tplId,
                            'service_type_id'     => $template ? $template->service_type_id : null,
                            'quantity'            => $svc['quantity'] ?? 1,
                            'unit_price'          => $svc['unit_price'],
                            'total_price'         => $svc['total_price'] ?? $svc['unit_price'],
                            'service_date'        => $svc['service_date'] ?? null,
                        ]);
                    }
                }
            }

            // ── New service rows ────────────────────────────────────────
            if (!empty($validated['new_services']) && $booking->quotation) {
                foreach ($validated['new_services'] as $nsvc) {
                    if (empty($nsvc['unit_price'])) continue;
                    $tplId    = $nsvc['service_template_id'] ?? null;
                    $template = $tplId ? \App\Models\ServiceTemplate::find($tplId) : null;
                    \App\Models\QuotationItem::create([
                        'quotation_id'        => $booking->quotation->id,
                        'service_template_id' => $tplId,
                        'service_type_id'     => $template ? $template->service_type_id : null,
                        'quantity'            => $nsvc['quantity'],
                        'unit_price'          => $nsvc['unit_price'],
                        'total_price'         => $nsvc['quantity'] * $nsvc['unit_price'],
                        'service_date'        => $nsvc['service_date'] ?? null,
                    ]);
                }
            }

            // ── Service deletions ────────────────────────────────────────
            if (!empty($validated['deleted_services'])) {
                foreach ($validated['deleted_services'] as $itemId) {
                    $item = \App\Models\QuotationItem::find($itemId);
                    if ($item && $item->quotation_id == $booking->quotation_id) {
                        $item->delete();
                    }
                }
            }

            // ── Recalculate financials ───────────────────────────────────
            $discount    = (float)($validated['discount'] ?? 0);
            $currentPaid = $booking->payments->sum('amount') ?: (float)$booking->paid_amount;

            if ($booking->quotation) {
                // Subtotal = sum of all item total_prices (fresh)
                $subtotal  = (float)$booking->quotation->items()->sum('total_price');
                $netTotal  = max(0, $subtotal - $discount);

                $booking->quotation->update([
                    'subtotal'     => $subtotal,
                    'total_amount' => $netTotal,
                    'itinerary_html' => $validated['tour_plan'] ?? $booking->quotation->itinerary_html,
                ]);
            } else {
                $netTotal = (float)$request->input('total_amount', $booking->total_amount);
            }

            $booking->update([
                'booking_date'   => $validated['booking_date'],
                'booking_status' => $validated['booking_status'] ?? $booking->booking_status,
                'total_amount'   => $netTotal,
                'paid_amount'    => $currentPaid,
                'pending_amount' => max(0, $netTotal - $currentPaid),
            ]);

            DB::commit();

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Booking updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking update failed', ['error' => $e->getMessage(), 'id' => $id]);
            return back()->withInput()->with('error', 'Failed to update booking: ' . $e->getMessage());
        }
    }

    /**
     * Update short plan for a booking
     */
    public function updateShortPlan(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'short_plan' => 'nullable|string',
        ]);

        // Update lead short_plan
        if ($booking->lead) {
            $booking->lead->update([
                'short_plan' => $validated['short_plan'],
            ]);
        }

        return redirect()->route('bookings.show', $id)
            ->with('success', 'Short plan updated successfully.');
    }

    /**
     * Update full plan/itinerary for a booking
     */
    public function updateTourPlan(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'tour_plan' => 'nullable|string',
        ]);

        // Update lead's tour plan
        $booking->lead->update([
            'plan_detail' => $validated['tour_plan'],
        ]);

        // Also update quotation itinerary if exists (for backward compatibility)
        if ($booking->quotation) {
            $booking->quotation->update([
                'itinerary' => $validated['tour_plan'],
            ]);
        }

        return redirect()->route('bookings.show', $id)
            ->with('success', 'Tour plan updated successfully.');
    }

    public function addPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Role-based authorization: Staff can only add payments to their own bookings
        if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            if ($booking->created_by !== auth('admin')->id()) {
                abort(403, 'Unauthorized access. You can only add payments to bookings you created.');
            }
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
            'payment_account_id' => 'required|exists:payment_accounts,id',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        BookingPayment::create([
            'booking_id' => $booking->id,
            'payment_account_id' => $validated['payment_account_id'],
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'received_by' => auth('admin')->id(),
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Payment added successfully.');
    }

    /**
     * Update an existing payment
     */
    public function updatePayment(Request $request, $bookingId, $paymentId)
    {
        $booking = Booking::findOrFail($bookingId);
        $payment = BookingPayment::findOrFail($paymentId);

        // Verify payment belongs to this booking
        if ($payment->booking_id !== $booking->id) {
            abort(404, 'Payment not found for this booking.');
        }

        // Role-based authorization
        if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            if ($booking->created_by !== auth('admin')->id()) {
                abort(403, 'Unauthorized access. You can only edit payments for bookings you created.');
            }
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
            'payment_account_id' => 'required|exists:payment_accounts,id',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment->update([
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_account_id' => $validated['payment_account_id'],
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Payment updated successfully.');
    }

    /**
     * Delete a payment
     */
    public function deletePayment($bookingId, $paymentId)
    {
        $booking = Booking::findOrFail($bookingId);
        $payment = BookingPayment::findOrFail($paymentId);

        // Verify payment belongs to this booking
        if ($payment->booking_id !== $booking->id) {
            abort(404, 'Payment not found for this booking.');
        }

        // Role-based authorization
        if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
            if ($booking->created_by !== auth('admin')->id()) {
                abort(403, 'Unauthorized access. You can only delete payments for bookings you created.');
            }
        }

        // Store payment amount before deletion for logging
        $paymentAmount = $payment->amount;

        // Permanently delete the payment (not soft delete)
        $payment->forceDelete();

        // Reload the booking's payments relationship to reflect the deletion
        $booking->load('payments');

        // Manually update booking amounts
        $booking->updatePaidAmount();

        return back()->with('success', "Payment of ₹" . number_format($paymentAmount, 2) . " deleted successfully.");
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,in_progress,completed,cancelled',
        ]);

        $booking->update(['booking_status' => $validated['status']]);

        return back()->with('success', 'Booking status updated to ' . ucfirst($validated['status']));
    }

    public function assignService(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'quotation_item_id' => 'required|exists:quotation_items,id',
            'service_provider_id' => 'required|exists:service_providers,id',
            'service_item_id' => 'required|exists:service_items,id',
            'assigned_cost' => 'required|numeric|min:0',
            'assignment_date' => 'required|date',
            'notes' => 'nullable|string',
            'assignment_id' => 'nullable|exists:booking_service_assignments,id',
        ]);

        // If assignment_id is provided, this is a reassignment (update)
        if (!empty($validated['assignment_id'])) {
            $assignment = \App\Models\BookingServiceAssignment::findOrFail($validated['assignment_id']);

            $assignment->update([
                'service_provider_id' => $validated['service_provider_id'],
                'service_item_id' => $validated['service_item_id'],
                'assigned_cost' => $validated['assigned_cost'],
                'assigned_by' => auth('admin')->id(),
                'assignment_date' => $validated['assignment_date'],
                'notes' => $validated['notes'],
            ]);

            // Get updated assignment with relationships
            $assignment->load('serviceProvider', 'serviceItem');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor assignment updated successfully.',
                    'assignment' => $assignment
                ]);
            }

            return back()->with('success', 'Vendor assignment updated successfully.');
        }

        // Check if already assigned (for new assignments only)
        $existing = \App\Models\BookingServiceAssignment::where('booking_id', $booking->id)
            ->where('quotation_item_id', $validated['quotation_item_id'])
            ->first();

        if ($existing) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This service is already assigned to a vendor.'
                ], 422);
            }
            return back()->with('error', 'This service is already assigned to a vendor.');
        }

        // Create new assignment
        $assignment = \App\Models\BookingServiceAssignment::create([
            'booking_id' => $booking->id,
            'quotation_item_id' => $validated['quotation_item_id'],
            'service_provider_id' => $validated['service_provider_id'],
            'service_item_id' => $validated['service_item_id'],
            'assigned_cost' => $validated['assigned_cost'],
            'assigned_by' => auth('admin')->id(),
            'assignment_date' => $validated['assignment_date'],
            'notes' => $validated['notes'],
        ]);

        // Load relationships
        $assignment->load('serviceProvider', 'serviceItem');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Service assigned to vendor successfully.',
                'assignment' => $assignment
            ]);
        }

        return back()->with('success', 'Service assigned to vendor successfully.');
    }

    /**
     * Add a new service to an existing booking
     */
    public function addService(Request $request, $id)
    {
        $booking = Booking::with('quotation')->findOrFail($id);

        // If booking doesn't have a quotation, create one
        if (!$booking->quotation) {
            $quotation = \App\Models\Quotation::create([
                'lead_id' => $booking->lead_id,
                'quotation_number' => 'QT-' . strtoupper(uniqid()),
                'quotation_date' => now(),
                'valid_until' => now()->addDays(30),
                'total_amount' => 0,
                'status' => 'accepted',
                'created_by' => auth('admin')->id(),
            ]);

            $booking->update(['quotation_id' => $quotation->id]);
            $booking->refresh();
        }

        $validated = $request->validate([
            'service_template_id' => 'required|exists:service_templates,id',
            'service_type_id' => 'required|exists:service_types,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'service_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Create quotation item with manually entered total price
        $quotationItem = \App\Models\QuotationItem::create([
            'quotation_id' => $booking->quotation->id,
            'service_template_id' => $validated['service_template_id'],
            'service_type_id' => $validated['service_type_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'total_price' => $validated['total_price'], // Use manually entered total price
            'service_date' => $validated['service_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update booking total amount
        $booking->update([
            'total_amount' => $booking->quotation->fresh()->total_amount,
        ]);

        // Recalculate pending amount
        $booking->updatePaidAmount();

        return back()->with('success', 'Service added to booking successfully. Total amount updated.');
    }

    /**
     * Delete a service from booking
     */
    public function deleteService($id, $itemId)
    {
        $booking = Booking::with('quotation')->findOrFail($id);

        // Find the quotation item
        $quotationItem = \App\Models\QuotationItem::where('id', $itemId)
            ->where('quotation_id', $booking->quotation_id)
            ->first();

        if (!$quotationItem) {
            return back()->with('error', 'Service not found.');
        }

        // Delete the quotation item
        $quotationItem->delete();

        // Recalculate quotation total
        if ($booking->quotation) {
            $booking->quotation->calculateTotal();

            // Update booking total amount
            $booking->update([
                'total_amount' => $booking->quotation->fresh()->total_amount,
            ]);

            // Recalculate pending amount
            $booking->updatePaidAmount();
        }

        return back()->with('success', 'Service deleted successfully. Total amount updated.');
    }

    /**
     * Premium booking voucher (printable HTML → PDF)
     */
    public function voucher($id)
    {
        $booking = Booking::with([
            'lead',
            'quotation.items.serviceTemplate.serviceType',
            'quotation.items.serviceType',
            'payments.paymentAccount',
            'createdBy',
        ])->withSum('payments', 'amount')->findOrFail($id);

        return view('admin.bookings.voucher', compact('booking'), ['page_title' => 'Booking Voucher']);
    }

    /**
     * Generate invoice for booking
     */
    public function invoice($id)
    {
        $booking = Booking::with([
            'lead',
            'quotation.items.serviceTemplate.serviceType',
            'payments.paymentAccount',
            'createdBy'
        ])
        ->withSum('payments', 'amount')
        ->findOrFail($id);

        return view('admin.bookings.invoice', compact('booking'), ['page_title' => 'Booking Invoice']);
    }

    /**
     * Generate GST invoice for booking
     */
    public function gstInvoice($id)
    {
        $booking = Booking::with([
            'lead',
            'quotation.items.serviceTemplate.serviceType',
            'payments.paymentAccount',
            'createdBy'
        ])
        ->withSum('payments', 'amount')
        ->findOrFail($id);

        if (!$booking->is_gst_invoice) {
            return redirect()->route('bookings.show', $id)
                ->with('error', 'This booking is not marked as a GST invoice.');
        }

        return view('admin.bookings.gst-invoice', compact('booking'), ['page_title' => 'GST Invoice']);
    }

    /**
     * Update GST details for a booking
     */
    public function updateGstDetails(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $gstService = new GstInvoiceService();

        $validated = $request->validate([
            'is_gst_invoice' => 'required',
            'gst_rate' => 'nullable|numeric|in:5,12',
            'customer_gstin' => 'nullable|string|max:15',
        ]);

        // Convert to boolean (handles both '0'/'1' strings and boolean values)
        $isGst = filter_var($validated['is_gst_invoice'], FILTER_VALIDATE_BOOLEAN);
        $gstRate = $validated['gst_rate'] ?? 5;
        $customerGstin = $validated['customer_gstin'] ?? null;

        try {
            $gstService->toggleInvoiceType($booking, $isGst, $gstRate, $customerGstin);

            $message = $isGst
                ? 'Booking marked as GST invoice successfully. GST Number: ' . $booking->gst_invoice_number
                : 'Booking converted to non-GST invoice successfully.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update GST settings: ' . $e->getMessage());
        }
    }

    /**
     * Display calendar view of bookings
     */
    public function calendar()
    {
        // Get all service types for filter dropdown
        $serviceTypes = \App\Models\ServiceType::select('id', 'name')->orderBy('name')->get();
        
        return view('admin.bookings.calendar', compact('serviceTypes'), ['page_title' => 'Booking Calendar']);
    }

    /**
     * Get bookings as calendar events (JSON API) - Optimized
     * Shows bookings by service dates (from quotation items)
     */
    public function calendarEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $serviceTypeFilter = $request->input('service_type'); // Get service type filter

        // Get bookings with their quotation items that have service dates
        $bookings = Booking::select([
                'id', 'booking_number', 'booking_date', 'booking_status',
                'total_amount', 'paid_amount', 'pending_amount',
                'lead_id', 'quotation_id', 'created_by'
            ])
            ->with([
                'lead:id,guest_name,contact,pax,short_plan',
                'quotation:id',
                'quotation.items' => function($query) use ($start, $end, $serviceTypeFilter) {
                    $query->select('id', 'quotation_id', 'service_template_id', 'service_date', 'quantity', 'total_price')
                        ->whereNotNull('service_date');

                    // Filter by date range if provided
                    if ($start) {
                        $query->where('service_date', '>=', $start);
                    }
                    if ($end) {
                        $query->where('service_date', '<=', $end);
                    }

                    // Filter by service type if provided
                    if ($serviceTypeFilter) {
                        $query->whereHas('serviceTemplate.serviceType', function($q) use ($serviceTypeFilter) {
                            $q->where('name', 'LIKE', '%' . $serviceTypeFilter . '%');
                        });
                    }
                },
                'quotation.items.serviceTemplate:id,name,service_type_id',
                'quotation.items.serviceTemplate.serviceType:id,name',
                'createdBy:id,name,email'
            ])
            // Filter bookings by service type if provided
            ->when($serviceTypeFilter, function($query) use ($serviceTypeFilter, $start, $end) {
                $query->whereHas('quotation.items', function($q) use ($serviceTypeFilter, $start, $end) {
                    $q->whereNotNull('service_date');
                    
                    // Apply date filters
                    if ($start) {
                        $q->where('service_date', '>=', $start);
                    }
                    if ($end) {
                        $q->where('service_date', '<=', $end);
                    }
                    
                    // Apply service type filter
                    $q->whereHas('serviceTemplate.serviceType', function($sq) use ($serviceTypeFilter) {
                        $sq->where('name', 'LIKE', '%' . $serviceTypeFilter . '%');
                    });
                });
            })
            // Role-based filtering:
            // - Super Admin & Admin: See all bookings
            // - Manager: See all bookings (can be customized)
            // - Staff/Other roles: See only their own bookings
            ->when(!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']), function($query) {
                $query->where('created_by', auth('admin')->id());
            })
            ->limit(500)
            ->get();

        $events = [];

        foreach ($bookings as $booking) {
            // Get unique service types with icons (optimized)
            $serviceTypes = [];
            $servicesList = '';

            if ($booking->quotation && $booking->quotation->items) {
                // Get services that have dates
                $servicesWithDates = $booking->quotation->items->filter(function($item) {
                    return $item->service_date !== null;
                });

                if ($servicesWithDates->isEmpty()) {
                    // If no services have dates, skip this booking
                    continue;
                }

                $uniqueTypes = $servicesWithDates
                    ->pluck('serviceTemplate.serviceType')
                    ->filter()
                    ->unique('id')
                    ->take(5);

                foreach ($uniqueTypes as $type) {
                    if ($type) {
                        $serviceTypes[] = [
                            'name' => $type->name,
                            'icon' => $this->getServiceIcon($type->name),
                            'color' => $this->getServiceColor($type->name)
                        ];
                    }
                }

                $servicesList = $servicesWithDates
                    ->pluck('serviceTemplate.name')
                    ->filter()
                    ->take(5)
                    ->join(', ');

                // Group services by date to create separate calendar events
                $servicesByDate = $servicesWithDates->groupBy(function($item) {
                    return $item->service_date->format('Y-m-d');
                });

                // Calculate payment status
                $totalAmount = $booking->total_amount ?? 0;
                $paidAmount = $booking->paid_amount ?? 0;
                $dueAmount = $booking->pending_amount ?? 0;
                $paymentPercentage = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;

                // Event title with financial info
                $guestName = $booking->lead?->guest_name ?? 'Guest';

                // Handle created_by
                $createdBy = $booking->createdBy?->name ?? 'System';
                $createdByEmail = $booking->createdBy?->email ?? 'N/A';

                // Create an event for each service date
                foreach ($servicesByDate as $date => $servicesOnDate) {
                    // Get service types for this specific date
                    $serviceTypesOnDate = $servicesOnDate
                        ->pluck('serviceTemplate.serviceType')
                        ->filter()
                        ->unique('id');

                    // Determine color based on service types
                    $color = $this->getEventColor($serviceTypesOnDate, $booking->booking_status);
                    
                    $servicesOnDateList = $servicesOnDate
                        ->pluck('serviceTemplate.name')
                        ->filter()
                        ->join(', ');

                    $title = $guestName . ' - ' . $servicesOnDateList;

                    $events[] = [
                        'id' => $booking->id,
                        'title' => $title,
                        'start' => $date,
                        'backgroundColor' => $color['background'],
                        'borderColor' => $color['border'],
                        'extendedProps' => [
                            'booking_number' => $booking->booking_number ?? 'N/A',
                            'guest_name' => $guestName,
                            'contact' => $booking->lead?->contact ?? 'N/A',
                            'pax' => $booking->lead?->pax ?? 'N/A',
                            'status' => ucfirst(str_replace('_', ' ', $booking->booking_status)),
                            'total_amount' => $totalAmount,
                            'paid_amount' => $paidAmount,
                            'due_amount' => $dueAmount,
                            'payment_percentage' => round($paymentPercentage, 1),
                            'services' => $servicesOnDateList ?: 'No services',
                            'service_types' => $serviceTypes,
                            'short_plan' => $booking->lead?->short_plan ?? 'N/A',
                            'created_by' => $createdBy,
                            'created_by_email' => $createdByEmail,
                            'service_date' => $date,
                        ],
                    ];
                }
            }
        }

        return response()->json($events);
    }

    /**
     * Get event color based on service types
     */
    private function getEventColor($serviceTypes, $bookingStatus)
    {
        // If cancelled, always show red
        if ($bookingStatus === 'cancelled') {
            return [
                'background' => '#EF4444',
                'border' => '#DC2626'
            ];
        }

        $serviceTypeNames = $serviceTypes->pluck('name')->map(function($name) {
            return strtolower($name);
        });

        // If multiple service types, use attractive gradient colors
        if ($serviceTypeNames->count() > 1) {
            // Check for common combinations
            if ($serviceTypeNames->contains(fn($name) => str_contains($name, 'hotel')) && 
                $serviceTypeNames->contains(fn($name) => str_contains($name, 'transport'))) {
                return ['background' => '#9333EA', 'border' => '#7E22CE']; // Vibrant Purple for Hotel + Transport
            }
            
            if ($serviceTypeNames->contains(fn($name) => str_contains($name, 'boat'))) {
                return ['background' => '#0891B2', 'border' => '#0E7490']; // Cyan for boat combos
            }
            
            // Default for multiple services - Attractive Gradient Blue
            return ['background' => '#6366F1', 'border' => '#4F46E5']; // Indigo for mixed
        }

        // Single service type - use specific color
        $primaryType = $serviceTypeNames->first();
        
        foreach ($serviceTypes as $type) {
            $color = $this->getServiceColor($type->name);
            if ($color) {
                return $color;
            }
        }

        // Default color - Neutral Gray
        return ['background' => '#6B7280', 'border' => '#4B5563'];
    }

    /**
     * Get color for service type
     */
    private function getServiceColor($serviceTypeName)
    {
        $colors = [
            // Accommodation - Warm Orange
            'hotel' => ['background' => '#FF6B35', 'border' => '#E85A2A'],
            'accommodation' => ['background' => '#FF6B35', 'border' => '#E85A2A'],
            
            // Transport - Vibrant Blue
            'transport' => ['background' => '#4A90E2', 'border' => '#357ABD'],
            'vehicle' => ['background' => '#4A90E2', 'border' => '#357ABD'],
            'car' => ['background' => '#4A90E2', 'border' => '#357ABD'],
            'cab' => ['background' => '#4A90E2', 'border' => '#357ABD'],
            
            // Flight - Sky Blue
            'flight' => ['background' => '#00B4D8', 'border' => '#0096C7'],
            
            // Train - Deep Purple
            'train' => ['background' => '#7209B7', 'border' => '#560BAD'],
            
            // Guide - Fresh Green
            'guide' => ['background' => '#06D6A0', 'border' => '#05B385'],
            'tour guide' => ['background' => '#06D6A0', 'border' => '#05B385'],
            
            // Meals - Coral Pink
            'meal' => ['background' => '#FF6F91', 'border' => '#FF5277'],
            'food' => ['background' => '#FF6F91', 'border' => '#FF5277'],
            'breakfast' => ['background' => '#FF6F91', 'border' => '#FF5277'],
            'lunch' => ['background' => '#FF6F91', 'border' => '#FF5277'],
            'dinner' => ['background' => '#FF6F91', 'border' => '#FF5277'],
            
            // Activity - Energetic Teal
            'activity' => ['background' => '#00C9A7', 'border' => '#00A88E'],
            
            // Sightseeing - Royal Purple
            'sightseeing' => ['background' => '#9B59B6', 'border' => '#8E44AD'],
            
            // Boat - Ocean Blue
            'boat' => ['background' => '#1E88E5', 'border' => '#1565C0'],
            
            // Entry - Lime Green
            'entry' => ['background' => '#7CB342', 'border' => '#689F38'],
            
            // Ticket - Golden Yellow
            'ticket' => ['background' => '#FFC107', 'border' => '#FFA000'],
        ];

        $lowerName = strtolower($serviceTypeName);
        foreach ($colors as $keyword => $color) {
            if (str_contains($lowerName, $keyword)) {
                return $color;
            }
        }

        return null; // Will use default color
    }

    /**
     * Get icon for service type
     */
    private function getServiceIcon($serviceTypeName)
    {
        $icons = [
            'hotel' => 'home',
            'accommodation' => 'home',
            'transport' => 'truck',
            'vehicle' => 'truck',
            'car' => 'truck',
            'cab' => 'truck',
            'flight' => 'send',
            'train' => 'navigation',
            'guide' => 'user',
            'tour guide' => 'user',
            'meal' => 'coffee',
            'food' => 'coffee',
            'breakfast' => 'coffee',
            'lunch' => 'coffee',
            'dinner' => 'coffee',
            'activity' => 'activity',
            'sightseeing' => 'eye',
            'boat' => 'anchor',
            'entry' => 'log-in',
            'ticket' => 'credit-card',
        ];

        $lowerName = strtolower($serviceTypeName);
        foreach ($icons as $keyword => $icon) {
            if (str_contains($lowerName, $keyword)) {
                return $icon;
            }
        }

        return 'package'; // default icon
    }

    /**
     * Soft delete a booking (keeps data for recovery)
     */
    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Role-based authorization: Staff can only delete their own bookings
            if (!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager'])) {
                if ($booking->created_by !== auth('admin')->id()) {
                    abort(403, 'Unauthorized access. You can only delete bookings you created.');
                }
            }

            $bookingNumber = $booking->booking_number;

            // Track who deleted the booking
            $booking->deleted_by = auth('admin')->id();
            $booking->save();

            // Soft delete related records (cascade)
            $booking->payments()->delete();
            $booking->serviceAssignments()->delete();

            // Soft delete the booking
            $booking->delete();

            return redirect()->route('bookings.index')
                ->with('success', "Booking #{$bookingNumber} has been moved to trash. You can restore it from the trash if needed.");

        } catch (\Exception $e) {
            return redirect()->route('bookings.index')
                ->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    /**
     * View trashed bookings
     */
    public function trash()
    {
        $bookings = Booking::onlyTrashed()
            ->with(['lead', 'quotation', 'deletedBy'])
            // Role-based filtering: Staff can only see their own deleted bookings
            ->when(!auth('admin')->user()->hasAnyRole(['Super Admin', 'Admin', 'Manager']), function($query) {
                $query->where('created_by', auth('admin')->id());
            })
            ->latest('deleted_at')
            ->paginate(15);

        return view('admin.bookings.trash', compact('bookings'), ['page_title' => 'Deleted Bookings']);
    }

    /**
     * Restore a soft-deleted booking
     */
    public function restore($id)
    {
        try {
            $booking = Booking::onlyTrashed()->findOrFail($id);
            $bookingNumber = $booking->booking_number;

            // Restore related records
            $booking->payments()->restore();
            $booking->serviceAssignments()->restore();

            // Clear deleted_by and restore the booking
            $booking->deleted_by = null;
            $booking->save();
            $booking->restore();

            return redirect()->route('bookings.trash')
                ->with('success', "Booking #{$bookingNumber} has been restored successfully.");

        } catch (\Exception $e) {
            return redirect()->route('bookings.trash')
                ->with('error', 'Failed to restore booking: ' . $e->getMessage());
        }
    }

    /**
     * View comprehensive booking report in browser
     */
    public function viewReport($id)
    {
        $booking = Booking::with([
            'lead',
            'quotation.items.serviceTemplate',
            'quotation.items.serviceType',
            'serviceAssignments.serviceProvider',
            'serviceAssignments.quotationItem.serviceTemplate',
            'serviceAssignments.quotationItem.serviceType',
            'payments.paymentAccount',
            'createdBy'
        ])
        ->withSum('payments', 'amount')
        ->findOrFail($id);

        // Calculate service dates range
        $serviceDates = [];
        if ($booking->quotation && $booking->quotation->items) {
            $serviceDates = $booking->quotation->items
                ->filter(fn($item) => $item->service_date)
                ->pluck('service_date')
                ->sort();
        }

        $startDate = $serviceDates->first();
        $endDate = $serviceDates->last();

        // Calculate totals
        $totalVendorCost = $booking->serviceAssignments->sum('assigned_cost');
        $totalMargin = $booking->total_amount - $totalVendorCost;
        $totalMarginPercent = $booking->total_amount > 0 ? ($totalMargin / $booking->total_amount) * 100 : 0;

        // Calculate spending by service type
        $serviceTypeBreakdown = [];
        foreach ($booking->serviceAssignments as $assignment) {
            if ($assignment->quotationItem && $assignment->quotationItem->serviceType) {
                $serviceTypeName = $assignment->quotationItem->serviceType->name;
                if (!isset($serviceTypeBreakdown[$serviceTypeName])) {
                    $serviceTypeBreakdown[$serviceTypeName] = 0;
                }
                $serviceTypeBreakdown[$serviceTypeName] += $assignment->assigned_cost;
            }
        }
        // Sort by amount descending
        arsort($serviceTypeBreakdown);

        // Calculate vendor payment breakdown
        $vendorBreakdown = [];
        foreach ($booking->serviceAssignments as $assignment) {
            if ($assignment->serviceProvider) {
                $vendorName = $assignment->serviceProvider->name;
                if (!isset($vendorBreakdown[$vendorName])) {
                    $vendorBreakdown[$vendorName] = 0;
                }
                $vendorBreakdown[$vendorName] += $assignment->assigned_cost;
            }
        }
        // Sort by amount descending
        arsort($vendorBreakdown);

        return view('admin.bookings.report-view', compact(
            'booking',
            'startDate',
            'endDate',
            'totalVendorCost',
            'totalMargin',
            'totalMarginPercent',
            'serviceTypeBreakdown',
            'vendorBreakdown'
        ), ['page_title' => 'Booking Report']);
    }

    /**
     * Download comprehensive booking report as PDF
     */
    public function downloadReport($id)
    {
        $booking = Booking::with([
            'lead',
            'quotation.items.serviceTemplate',
            'quotation.items.serviceType',
            'serviceAssignments.serviceProvider',
            'serviceAssignments.quotationItem.serviceTemplate',
            'payments.paymentAccount',
            'createdBy'
        ])
        ->withSum('payments', 'amount')
        ->findOrFail($id);

        // Calculate service dates range
        $serviceDates = [];
        if ($booking->quotation && $booking->quotation->items) {
            $serviceDates = $booking->quotation->items
                ->filter(fn($item) => $item->service_date)
                ->pluck('service_date')
                ->sort();
        }

        $startDate = $serviceDates->first();
        $endDate = $serviceDates->last();

        // Calculate totals
        $totalVendorCost = $booking->serviceAssignments->sum('assigned_cost');
        $totalMargin = $booking->total_amount - $totalVendorCost;
        $totalMarginPercent = $booking->total_amount > 0 ? ($totalMargin / $booking->total_amount) * 100 : 0;

        $pdf = \PDF::loadView('admin.bookings.report', compact(
            'booking',
            'startDate',
            'endDate',
            'totalVendorCost',
            'totalMargin',
            'totalMarginPercent'
        ));

        $filename = 'booking-report-' . $booking->booking_number . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Permanently delete a booking (cannot be recovered)
     */
    public function forceDelete($id)
    {
        try {
            $booking = Booking::onlyTrashed()->findOrFail($id);
            $bookingNumber = $booking->booking_number;

            // Force delete related records
            $booking->payments()->forceDelete();
            $booking->serviceAssignments()->forceDelete();

            // Permanently delete the booking
            $booking->forceDelete();

            return redirect()->route('bookings.trash')
                ->with('success', "Booking #{$bookingNumber} has been permanently deleted.");

        } catch (\Exception $e) {
            return redirect()->route('bookings.trash')
                ->with('error', 'Failed to permanently delete booking: ' . $e->getMessage());
        }
    }

    /**
     * Export booking report as Excel
     */
    public function exportReport($id)
    {
        $booking = Booking::findOrFail($id);
        $filename = 'booking-report-' . $booking->booking_number . '.xlsx';
        
        return \Excel::download(new \App\Exports\BookingReportExport($id), $filename);
    }
}
