<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Booking;
use App\Models\ServiceTemplate;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuickBookingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:booking-create');
    }

    /**
     * Show quick booking form for a lead
     */
    public function showQuickBookingForm($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $serviceTypes = ServiceType::with('serviceTemplates')->get();
        
        return view('admin.lead.quick-booking', compact('lead', 'serviceTypes'), [
            'page_title' => 'Quick Booking - ' . $lead->guest_name
        ]);
    }

    /**
     * Create booking directly from lead (creates quotation and auto-approves)
     * Quick booking = Quotation process shortcut (auto-approved and converted)
     */
    public function createQuickBooking(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*.service_template_id' => 'required|exists:service_templates,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.unit_price' => 'required|numeric|min:0',
            'services.*.service_date' => 'nullable|date',
            'itinerary' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total amount
            $totalAmount = 0;
            foreach ($validated['services'] as $service) {
                $totalAmount += $service['quantity'] * $service['unit_price'];
            }

            // Step 1: Create Quotation (with all details)
            $quotation = Quotation::create([
                'lead_id' => $lead->id,
                'quotation_number' => 'QT-' . time(),
                'quotation_date' => now(),
                'valid_until' => now()->addDays(7),
                'total_amount' => $totalAmount,
                'status' => 'accepted', // Auto-approved
                'notes' => 'Approved via Quick Booking',
                'itinerary_html' => $validated['itinerary'] ?? null,
                'created_by' => auth('admin')->id(),
            ]);

            // Step 2: Create Quotation Items (services)
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
                ]);
            }

            // Step 3: Create Booking (auto-convert quotation)
            $booking = Booking::create([
                'quotation_id' => $quotation->id,
                'lead_id' => $lead->id,
                'booking_date' => now(),
                'booking_status' => 'confirmed',
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth('admin')->id(),
            ]);

            // Step 4: Mark quotation as converted
            $quotation->update([
                'is_converted' => true,
                'booking_id' => $booking->id,
            ]);

            // Step 5: Update lead
            $lead->update([
                'booking_status' => 'confirm',
                'total_amount' => $totalAmount,
                'plan_detail' => $validated['itinerary'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Quick booking created successfully! Booking Number: ' . $booking->booking_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Create quotation with auto-convert option
     */
    public function createQuickQuotation(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*.service_template_id' => 'required|exists:service_templates,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.unit_price' => 'required|numeric|min:0',
            'services.*.service_date' => 'nullable|date',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:quotation_date',
            'itinerary' => 'nullable|string',
            'notes' => 'nullable|string',
            'auto_accept_and_convert' => 'nullable|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($validated['services'] as $service) {
                $totalAmount += $service['quantity'] * $service['unit_price'];
            }

            $autoAcceptAndConvert = isset($validated['auto_accept_and_convert']) && ($validated['auto_accept_and_convert'] == '1' || $validated['auto_accept_and_convert'] === true);

            $quotation = Quotation::create([
                'lead_id' => $lead->id,
                'quotation_number' => 'QT-' . time(),
                'quotation_date' => $validated['quotation_date'],
                'valid_until' => $validated['valid_until'] ?? null,
                'total_amount' => $totalAmount,
                'status' => $autoAcceptAndConvert ? 'accepted' : 'draft',
                'notes' => $validated['notes'] ?? null,
                'itinerary_html' => $validated['itinerary'] ?? null,
                'created_by' => auth('admin')->id(),
            ]);

            // Create quotation items
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
                ]);
            }

            // Auto-convert to booking if requested
            if ($autoAcceptAndConvert) {
                $booking = Booking::create([
                    'quotation_id' => $quotation->id,
                    'lead_id' => $lead->id,
                    'booking_date' => now(),
                    'booking_status' => 'confirmed',
                    'total_amount' => $totalAmount,
                    'paid_amount' => 0,
                    'created_by' => auth('admin')->id(),
                ]);

                $quotation->update([
                    'is_converted' => true,
                    'booking_id' => $booking->id,
                ]);

                $lead->update([
                    'booking_status' => 'confirm',
                    'total_amount' => $totalAmount,
                ]);

                DB::commit();

                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Quotation created and converted to booking successfully!');
            }

            DB::commit();

            return redirect()->route('quotations.show', $quotation->id)
                ->with('success', 'Quotation created successfully! Quotation Number: ' . $quotation->quotation_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create quotation: ' . $e->getMessage());
        }
    }
}
