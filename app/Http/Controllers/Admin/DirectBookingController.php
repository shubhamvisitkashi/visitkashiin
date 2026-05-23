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
    public function create()
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
                'guest_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'short_plan' => 'required|string',
                'lead_source_id' => 'required|exists:lead_sources,id',
                
                // Services (Required)
                'services' => 'required|array|min:1',
                'services.*.service_template_id' => 'required|exists:service_templates,id',
                'services.*.quantity' => 'nullable|integer|min:1',
                'services.*.unit_price' => 'required|numeric|min:0',
                'services.*.service_date' => 'nullable|date',
                
                // Additional Details (Optional)
                'tour_plan' => 'nullable|string',
                'internal_notes' => 'nullable|string',
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

            // Apply discount
            $discountAmount = 0;
            if (!empty($validated['discount_amount'])) {
                if ($validated['discount_type'] === 'percentage') {
                    $discountAmount = ($subtotal * $validated['discount_amount']) / 100;
                } else {
                    $discountAmount = $validated['discount_amount'];
                }
            }

            $amountAfterDiscount = $subtotal - $discountAmount;

            // Apply GST
            $gstAmount = 0;
            if (!empty($validated['apply_gst']) && !empty($validated['gst_rate'])) {
                $gstAmount = ($amountAfterDiscount * $validated['gst_rate']) / 100;
            }

            $totalAmount = $amountAfterDiscount + $gstAmount;

            // STEP 1: Auto-create Lead (background)
            $lead = Lead::create([
                'guest_name' => $validated['guest_name'],
                'contact' => $validated['phone'],
                'short_plan' => $validated['short_plan'],
                'lead_source_id' => $validated['lead_source_id'],
                'booking_status' => 'confirm',
                'total_amount' => $totalAmount,
                'plan_detail' => $validated['tour_plan'] ?? null,
                'added_by' => auth('admin')->id(),
            ]);

            // STEP 2: Auto-create Quotation (background, auto-approved)
            $quotation = Quotation::create([
                'lead_id' => $lead->id,
                'quotation_number' => 'QT-' . time() . '-' . $lead->id,
                'quotation_date' => now()->format('Y-m-d'), // Use today's date
                'valid_until' => now()->addDays(7),
                'total_amount' => $totalAmount,
                'discount_amount' => 0,
                'discount_type' => 'fixed',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'tax_rate' => 0,
                'status' => 'accepted', // Auto-approved
                'notes' => 'Auto-created via Direct Booking',
                'itinerary_html' => $validated['tour_plan'] ?? null,
                'created_by' => auth('admin')->id(),
            ]);

            // STEP 3: Create Quotation Items (services)
            foreach ($validated['services'] as $service) {
                $template = ServiceTemplate::find($service['service_template_id']);
                
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'service_type_id' => $template->service_type_id,
                    'service_template_id' => $service['service_template_id'],
                    'quantity' => $service['quantity'],
                    'unit_price' => $service['unit_price'],
                    'total_price' => $service['quantity'] * $service['unit_price'],
                    'service_date' => $service['service_date'] ?? null,
                    'notes' => $service['notes'] ?? null,
                ]);
            }

            // STEP 4: Create Booking (main entity)
            $booking = Booking::create([
                'quotation_id' => $quotation->id,
                'lead_id' => $lead->id,
                'booking_number' => 'BK-' . date('Ymd') . '-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT),
                'booking_date' => now()->format('Y-m-d'),
                'booking_status' => 'confirmed',
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'pending_amount' => $totalAmount,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'notes' => $validated['internal_notes'] ?? null,
                'created_by' => auth('admin')->id(),
            ]);

            // STEP 5: Link quotation to booking
            $quotation->update([
                'is_converted' => true,
                'booking_id' => $booking->id,
            ]);

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
