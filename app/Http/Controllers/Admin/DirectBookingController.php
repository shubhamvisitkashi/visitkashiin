<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Booking;
use App\Models\ServiceTemplate;
use App\Models\ServiceType;
use App\Models\Payment;
use App\Models\BookingPayment;
use App\Models\PaymentAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DirectBookingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:booking-create');
    }

    /**
     * Show direct booking creation form
     * No lead required - everything in one page
     */
    /** Hub — booking type selector */
    public function create()
    {
        return view('admin.bookings.create-direct', [], ['page_title' => 'New Booking']);
    }

    /** Dedicated Stay/Hotel booking form */
    public function createStay()
    {
        $serviceTypes = ServiceType::with(['serviceTemplates' => function($query) {
            $query->where('is_active', 1)->orderBy('name');
        }])->get();

        $stayServiceType = $serviceTypes->first(function($t) {
            return str_contains(strtolower($t->name), 'hotel') || str_contains(strtolower($t->name), 'stay');
        });
        $stayTemplates   = $stayServiceType?->serviceTemplates ?? collect();
        $paymentAccounts = PaymentAccount::where('is_active', 1)->orderBy('account_name')->get();
        $leadSources     = LeadSource::orderBy('name')->get();

        return view('admin.bookings.create-stay', compact('stayTemplates', 'paymentAccounts', 'leadSources'), [
            'page_title' => 'New Stay Booking',
        ]);
    }

    /** OLD unified create — kept for backward compat (redirects to hub) */
    public function createOld()
    {
        // Get service types with templates for selection
        $serviceTypes = ServiceType::with(['serviceTemplates' => function($query) {
            $query->where('is_active', 1)->orderBy('name');
        }])->get();

        // Get payment accounts for payment section
        $paymentAccounts = PaymentAccount::where('is_active', 1)
            ->orderBy('account_name')
            ->get();

        // Get lead sources
        $leadSources = LeadSource::orderBy('name')->get();

        return view('admin.bookings.create-direct', compact('serviceTypes', 'paymentAccounts', 'leadSources'), [
            'page_title' => 'Create New Booking'
        ]);
    }

    /**
     * Store new booking directly
     * Auto-creates lead and quotation in background
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                // Customer Information (Required)
                'guest_name'        => 'required|string|max:255',
                'phone'             => 'required|string|max:20',
                'alt_phone'         => 'nullable|string|max:20',
                'email'             => 'nullable|email|max:255',
                'country'           => 'nullable|string|max:100',
                'pax'               => 'nullable|integer|min:1',
                'short_plan'        => 'required|string',
                'lead_source_id'    => 'required|exists:lead_sources,id',

                // Stay-specific (optional — only sent by Stay form)
                'booking_start_date' => 'nullable|date',
                'booking_end_date'   => 'nullable|date',
                'checkin_time'       => 'nullable|string|max:10',
                'checkout_time'      => 'nullable|string|max:10',

                // Services (Required)
                'services'                          => 'required|array|min:1',
                'services.*.service_template_id'    => 'nullable|exists:service_templates,id',
                'services.*.quantity'               => 'nullable|integer|min:1',
                'services.*.unit_price'             => 'required|numeric|min:0',
                'services.*.service_date'           => 'nullable|date',

                // Payment
                'advance_paid'        => 'required|numeric|min:0',
                'discount'            => 'nullable|numeric|min:0',
                'payment_status'      => 'nullable|string',
                'payment_method'      => 'nullable|string|in:cash,upi',
                'cash_receiver_name'  => 'nullable|string|max:255',
                'vendor_cost'         => 'required|numeric|min:0',

                // Additional Details (Optional)
                'guest_notes'      => 'nullable|string',
                'internal_notes'   => 'nullable|string',
                'tags'             => 'nullable|string',
            ]);

            // Set default quantity if not provided
            foreach ($validated['services'] as $key => $service) {
                if (!isset($service['quantity']) || empty($service['quantity'])) {
                    $validated['services'][$key]['quantity'] = 1;
                }
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Direct booking validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return back()->withErrors($e->errors())->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($validated['services'] as $service) {
                $subtotal += $service['quantity'] * $service['unit_price'];
            }

            // Apply discount (form field name is 'discount')
            $discountAmount      = (float)($validated['discount'] ?? 0);
            $amountAfterDiscount = max(0, $subtotal - $discountAmount);

            // Apply GST
            $gstAmount = 0;
            if (!empty($validated['apply_gst']) && !empty($validated['gst_rate'])) {
                $gstAmount = ($amountAfterDiscount * $validated['gst_rate']) / 100;
            }

            $totalAmount = $amountAfterDiscount + $gstAmount;

            // STEP 1: Auto-create Lead (background)
            $lead = Lead::create([
                'guest_name'          => $validated['guest_name'],
                'contact'             => $validated['phone'],
                'alt_phone'           => $validated['alt_phone'] ?? null,
                'email'               => $validated['email'] ?? null,
                'country'             => $validated['country'] ?? null,
                'pax'                 => $validated['pax'] ?? null,
                'booking_start_date'  => $validated['booking_start_date'] ?? null,
                'booking_end_date'    => $validated['booking_end_date'] ?? null,
                'short_plan'          => $validated['short_plan'],
                'lead_source_id'      => $validated['lead_source_id'],
                'booking_status'      => 'confirm',
                'total_amount'        => $totalAmount,
                'plan_detail'         => null,
                'notes'               => $validated['internal_notes'] ?? null,
                'added_by'            => auth('admin')->id(),
            ]);

            // STEP 2: Auto-create Quotation (background, auto-approved)
            $quotation = Quotation::create([
                'lead_id' => $lead->id,
                'quotation_number' => 'QT-' . time() . '-' . $lead->id,
                'quotation_date' => now()->format('Y-m-d'),
                'valid_until' => now()->addDays(7),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'discount_type' => 'fixed',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'tax_rate' => 0,
                'status' => 'accepted', // Auto-approved
                'notes' => 'Auto-created via Direct Booking',
                'itinerary_html' => null,
                'created_by' => auth('admin')->id(),
            ]);

            // STEP 3: Create Quotation Items (services)
            foreach ($validated['services'] as $service) {
                $templateId = $service['service_template_id'] ?? null;
                $template   = $templateId ? ServiceTemplate::find($templateId) : null;

                QuotationItem::create([
                    'quotation_id'        => $quotation->id,
                    'service_type_id'     => $template ? $template->service_type_id : null,
                    'service_template_id' => $templateId,
                    'quantity'            => $service['quantity'] ?? 1,
                    'unit_price'          => $service['unit_price'],
                    'total_price'         => ($service['quantity'] ?? 1) * $service['unit_price'],
                    'service_date'        => $service['service_date'] ?? null,
                    'notes'               => $service['notes'] ?? null,
                ]);
            }

            // STEP 4: Create Booking (main entity)
            $advancePaid    = (float)($validated['advance_paid'] ?? 0);
            $pendingAmount  = max(0, $totalAmount - $advancePaid);

            $booking = Booking::create([
                'quotation_id'    => $quotation->id,
                'lead_id'         => $lead->id,
                'booking_number'  => 'BK-' . date('Ymd') . '-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT),
                'booking_date'    => now()->format('Y-m-d'),
                'booking_status'  => 'confirmed',
                'total_amount'    => $totalAmount,
                'paid_amount'     => $advancePaid,
                'pending_amount'  => $pendingAmount,
                'discount_amount' => $discountAmount,
                'vendor_cost'     => !empty($validated['vendor_cost']) ? (float)$validated['vendor_cost'] : null,
                'tax_amount'      => 0,
                'notes'           => $validated['internal_notes'] ?? null,
                'created_by'      => auth('admin')->id(),
            ]);

            // STEP 5: Link quotation to booking
            $quotation->update([
                'is_converted' => true,
                'booking_id' => $booking->id,
            ]);

            // STEP 6: Create payment record for advance paid
            if ($advancePaid > 0) {
                $selectedAccountId = !empty($request->payment_account_id)
                    ? $request->payment_account_id
                    : PaymentAccount::first()?->id;
                BookingPayment::create([
                    'booking_id'          => $booking->id,
                    'payment_account_id'  => $selectedAccountId,
                    'payment_date'        => now()->format('Y-m-d'),
                    'amount'              => $advancePaid,
                    'payment_method'      => $validated['payment_method'] ?? 'cash',
                    'cash_receiver_name'  => ($validated['payment_method'] ?? '') === 'cash'
                                             ? ($validated['cash_receiver_name'] ?? null)
                                             : null,
                    'received_by'         => auth('admin')->id(),
                ]);
            }

            DB::commit();

            // Log activity
            Log::info('Direct booking created', [
                'booking_id' => $booking->id,
                'lead_id' => $lead->id,
                'quotation_id' => $quotation->id,
                'created_by' => auth('admin')->id(),
            ]);

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Booking created successfully! Booking Number: ' . $booking->booking_number);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Direct booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Save booking as draft (incomplete booking)
     */
    public function saveDraft(Request $request)
    {
        // TODO: Implement draft save functionality
        // This will allow users to save incomplete bookings and resume later
        
        return back()->with('info', 'Draft save functionality coming soon!');
    }
}
