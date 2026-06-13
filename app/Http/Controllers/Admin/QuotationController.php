<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Lead;
use App\Models\ServiceTemplate;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:quotation-list|quotation-create', ['only' => ['index']]);
        $this->middleware('permission:quotation-view', ['only' => ['show']]);
        $this->middleware('permission:quotation-create', ['only' => ['create','store']]);
        $this->middleware('permission:quotation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:quotation-delete', ['only' => ['destroy']]);
        $this->middleware('permission:quotation-send', ['only' => ['sendEmail']]);
        $this->middleware('permission:quotation-approve', ['only' => ['updateStatus']]);
        $this->middleware('permission:quotation-convert', ['only' => ['convertToBooking']]);
        $this->middleware('permission:quotation-download', ['only' => ['downloadPdf']]);
    }

    /**
     * Display a listing of quotations (optimized)
     */
    public function index(Request $request)
    {
        $quotations = Quotation::with(['lead', 'createdBy', 'items'])
            ->search($request->search)
            ->when($request->status, fn($q, $status) => $q->byStatus($status))
            ->latest()
            ->paginate(15);

        return view('admin.quotations.index', compact('quotations'), ['page_title' => 'Quotations']);
    }

    /**
     * Show the form for creating a new quotation
     */
    public function create(Request $request)
    {
        $leadId = $request->get('lead_id');
        $lead = $leadId ? Lead::findOrFail($leadId) : null;
        
        $leads = Lead::latest()->get();
        $serviceTypes = ServiceType::active()->get();
        $serviceTemplates = ServiceTemplate::active()->with('serviceType')->get();

        return view('admin.quotations.create', compact('leads', 'lead', 'serviceTypes', 'serviceTemplates'), 
            ['page_title' => 'Create Quotation']);
    }

    /**
 * Store a newly created quotation
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'lead_id' => 'required|exists:leads,id',
        'quotation_date' => 'required|date',
        'valid_until' => 'nullable|date|after:quotation_date',
        'notes' => 'nullable|string',
        'itinerary_html' => 'nullable|string',
        'service_charge' => 'nullable|numeric|min:0',
        'gst_type' => 'required|in:include,exclude',
        'gst_percentage' => 'required|numeric|min:0|max:100',
        'gst_amount' => 'required|numeric|min:0',
        'subtotal' => 'required|numeric|min:0',
        'custom_total' => 'nullable|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.service_template_id' => 'nullable|exists:service_templates,id',
        'items.*.service_type_id' => 'nullable|exists:service_types,id',
        'items.*.custom_name' => 'nullable|string|max:255',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.service_date' => 'nullable|date',
        'items.*.notes' => 'nullable|string',
    ]);

    foreach ($validated['items'] as $itemData) {
        if (empty($itemData['service_template_id']) && (empty($itemData['custom_name']) || empty($itemData['service_type_id']))) {
            return back()->withInput()->with('error', 'Please select a service or provide a custom name and category for each item.');
        }
    }

    DB::beginTransaction();
    try {
        // Calculate total amount
        $servicesTotal = collect($validated['items'])->sum('unit_price');
        $serviceCharge = $validated['service_charge'] ?? 0;
        $subtotal = $servicesTotal + $serviceCharge;
        $gstAmount = $validated['gst_amount'];
        $totalAmount = $validated['gst_type'] === 'include' ? $subtotal : $subtotal + $gstAmount;

        // Create quotation
        $quotation = Quotation::create([
            'lead_id' => $validated['lead_id'],
            'quotation_date' => $validated['quotation_date'],
            'valid_until' => $validated['valid_until'] ?? now()->addDays(7),
            'notes' => $validated['notes'],
            'itinerary_html' => $validated['itinerary_html'] ?? null,
            'service_charge' => $serviceCharge,
            'subtotal' => $subtotal,
            'gst_type' => $validated['gst_type'],
            'gst_percentage' => $validated['gst_percentage'],
            'gst_amount' => $gstAmount,
            'total_amount' => $totalAmount,
            'custom_total' => $validated['custom_total'] ?? null,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        // Create quotation items
        foreach ($validated['items'] as $itemData) {
            if (!empty($itemData['service_template_id'])) {
                $template = ServiceTemplate::find($itemData['service_template_id']);

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'service_template_id' => $itemData['service_template_id'],
                    'service_type_id' => $template->service_type_id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'service_date' => $itemData['service_date'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            } else {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'service_template_id' => null,
                    'service_type_id' => $itemData['service_type_id'],
                    'custom_name' => $itemData['custom_name'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'service_date' => $itemData['service_date'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }
        }

        DB::commit();

        return redirect()->route('quotations.show', $quotation->id)
            ->with('success', 'Quotation created successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Failed to create quotation: ' . $e->getMessage());
    }
}

    /**
     * Display the specified quotation
     */
    public function show($id)
    {
        $quotation = Quotation::with(['lead', 'items.serviceTemplate.serviceType', 'createdBy'])->findOrFail($id);
        
        return view('admin.quotations.show', compact('quotation'), ['page_title' => 'Quotation Details']);
    }

    /**
     * Show the form for editing the specified quotation
     */
    public function edit($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        
        // Only allow editing draft quotations
        if ($quotation->status !== 'draft') {
            return redirect()->route('quotations.show', $id)
                ->with('error', 'Only draft quotations can be edited.');
        }

        $leads = Lead::latest()->get();
        $serviceTypes = ServiceType::active()->get();
        $serviceTemplates = ServiceTemplate::active()->with('serviceType')->get();

        return view('admin.quotations.edit', compact('quotation', 'leads', 'serviceTypes', 'serviceTemplates'), 
            ['page_title' => 'Edit Quotation']);
    }

    /**
 * Update the specified quotation
 */
public function update(Request $request, $id)
{
    $quotation = Quotation::findOrFail($id);

    if ($quotation->status !== 'draft') {
        return back()->with('error', 'Only draft quotations can be updated.');
    }

    $validated = $request->validate([
        'lead_id' => 'required|exists:leads,id',
        'quotation_date' => 'required|date',
        'valid_until' => 'nullable|date|after:quotation_date',
        'notes' => 'nullable|string',
        'itinerary_html' => 'nullable|string',
        'service_charge' => 'nullable|numeric|min:0',
        'gst_type' => 'required|in:include,exclude',
        'gst_percentage' => 'required|numeric|min:0|max:100',
        'gst_amount' => 'required|numeric|min:0',
        'subtotal' => 'required|numeric|min:0',
        'custom_total' => 'nullable|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.service_template_id' => 'nullable|exists:service_templates,id',
        'items.*.service_type_id' => 'nullable|exists:service_types,id',
        'items.*.custom_name' => 'nullable|string|max:255',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.service_date' => 'nullable|date',
        'items.*.notes' => 'nullable|string',
    ]);

    foreach ($validated['items'] as $itemData) {
        if (empty($itemData['service_template_id']) && (empty($itemData['custom_name']) || empty($itemData['service_type_id']))) {
            return back()->withInput()->with('error', 'Please select a service or provide a custom name and category for each item.');
        }
    }

    DB::beginTransaction();
    try {
        // Calculate total amount
        $servicesTotal = collect($validated['items'])->sum('unit_price');
        $serviceCharge = $validated['service_charge'] ?? 0;
        $subtotal = $servicesTotal + $serviceCharge;
        $gstAmount = $validated['gst_amount'];
        $totalAmount = $validated['gst_type'] === 'include' ? $subtotal : $subtotal + $gstAmount;

        // Update quotation
        $quotation->update([
            'lead_id' => $validated['lead_id'],
            'quotation_date' => $validated['quotation_date'],
            'valid_until' => $validated['valid_until'],
            'notes' => $validated['notes'],
            'itinerary_html' => $validated['itinerary_html'] ?? null,
            'service_charge' => $serviceCharge,
            'subtotal' => $subtotal,
            'gst_type' => $validated['gst_type'],
            'gst_percentage' => $validated['gst_percentage'],
            'gst_amount' => $gstAmount,
            'total_amount' => $totalAmount,
            'custom_total' => $validated['custom_total'] ?? null,
        ]);

        // Delete existing items and create new ones
        $quotation->items()->delete();

        foreach ($validated['items'] as $itemData) {
            if (!empty($itemData['service_template_id'])) {
                $template = ServiceTemplate::find($itemData['service_template_id']);

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'service_template_id' => $itemData['service_template_id'],
                    'service_type_id' => $template->service_type_id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'service_date' => $itemData['service_date'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            } else {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'service_template_id' => null,
                    'service_type_id' => $itemData['service_type_id'],
                    'custom_name' => $itemData['custom_name'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'service_date' => $itemData['service_date'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }
        }

        DB::commit();

        return redirect()->route('quotations.show', $quotation->id)
            ->with('success', 'Quotation updated successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Failed to update quotation: ' . $e->getMessage());
    }
}

    /**
     * Update quotation status
     */
    public function updateStatus(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        $quotation->update(['status' => $validated['status']]);

        return back()->with('success', 'Quotation status updated to ' . ucfirst($validated['status']));
    }

    /**
     * Remove the specified quotation
     */
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);

        if ($quotation->status === 'accepted' || $quotation->is_converted) {
            return back()->with('error', 'Cannot delete accepted or converted quotations.');
        }

        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('success', 'Quotation deleted successfully.');
    }

    /**
     * Convert quotation to booking
     */
    public function convertToBooking($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);

        if ($quotation->status !== 'accepted') {
            return back()->with('error', 'Only accepted quotations can be converted to bookings.');
        }

        if ($quotation->is_converted) {
            return back()->with('error', 'This quotation has already been converted to a booking.');
        }

        DB::beginTransaction();
        try {
            $booking = \App\Models\Booking::create([
                'quotation_id' => $quotation->id,
                'lead_id' => $quotation->lead_id,
                'booking_date' => now(),
                'booking_status' => 'confirmed',
                'total_amount' => $quotation->total_amount,
                'paid_amount' => 0,
                'created_by' => Auth::id(),
            ]);

            // Mark quotation as converted
            $quotation->is_converted = true;
            $quotation->booking_id = $booking->id;
            $quotation->save();

            DB::commit();

            return redirect()->route('quotations.show', $quotation->id)
                ->with('success', 'Quotation converted to booking successfully! Booking Number: ' . $booking->booking_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to convert quotation: ' . $e->getMessage());
        }
    }

    /**
     * Download quotation as PDF
     */
    public function downloadPdf($id)
    {
        $quotation = Quotation::with(['lead', 'items.serviceTemplate.serviceType', 'createdBy'])->findOrFail($id);
        
        $pdf = \PDF::loadView('admin.quotations.pdf', compact('quotation'));
        
        return $pdf->download('Quotation-' . $quotation->quotation_number . '.pdf');
    }

    /**
     * Send quotation via email
     */
    public function sendEmail(Request $request, $id)
    {
        $quotation = Quotation::with(['lead', 'items.serviceTemplate.serviceType', 'createdBy'])->findOrFail($id);
        
        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string',
        ]);

        try {
            // Generate PDF
            $pdf = \PDF::loadView('admin.quotations.pdf', compact('quotation'));
            
            // Send email
            \Mail::send('admin.quotations.email', [
                'quotation' => $quotation,
                'customMessage' => $validated['message'] ?? null
            ], function($message) use ($quotation, $validated, $pdf) {
                $message->to($validated['email'])
                        ->subject('Quotation - ' . $quotation->quotation_number)
                        ->attachData($pdf->output(), 'Quotation-' . $quotation->quotation_number . '.pdf');
            });

            return back()->with('success', 'Quotation sent successfully to ' . $validated['email']);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

}
