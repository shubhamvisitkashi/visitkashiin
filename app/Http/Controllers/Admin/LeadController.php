<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Lead;
use App\Models\Vendor;
use App\Models\Enquiry;
use App\Models\LeadSource;
use App\Models\LeadPayment;
use App\Models\AgentService;
use Illuminate\Http\Request;
use App\Models\VendorService;
use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\Models\ServiceProvider;
use App\Models\ServiceItem;
use App\Models\BookingService;

class LeadController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:lead-list|lead-create|lead-edit|lead-delete', ['only' => ['index','store']]);
        $this->middleware('permission:lead-create', ['only' => ['create','store']]);
        $this->middleware('permission:lead-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:lead-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of leads with optimized queries
     * Uses model scopes to eliminate code duplication
     */
    public function index(Request $request)
    {
        // Prepare filter parameters - only apply if explicitly provided
        $filters = [
            'source_id' => $request->source_id,
            'staff_id' => $request->search_staff_id,
            'service_type_id' => $request->search_service_type_id,
            'date_range' => $request->daterange, // Only use if provided
        ];

        // Store search form in session for navigation
        session(['leadSearchForm' => array_merge([
            'search' => $request->search,
            'status' => $request->status,
            'daterange' => $filters['date_range'],
        ], $filters)]);

        // Create base query with filters applied only if provided
        $baseQuery = Lead::query();
        
        // Apply filters only if they exist
        if ($filters['date_range']) {
            $baseQuery->applyFilters($filters);
        } else {
            // Apply non-date filters individually
            $baseQuery->when(auth()->id() != 1, function($q) {
                $q->where('added_by', auth()->id());
            })
            ->when($filters['source_id'], function($q, $sourceId) {
                $q->where('lead_source_id', $sourceId);
            })
            ->when($filters['staff_id'], function($q, $staffId) {
                $q->where('added_by', $staffId);
            })
            ->when($filters['service_type_id'], function($q, $serviceTypeId) {
                $q->whereJsonContains('service_ids', (string)$serviceTypeId);
            });
        }

        // Get all statistics efficiently using the same base query
        $total_complete = (clone $baseQuery)->byStatus('complete')->count();
        $total_confirm = (clone $baseQuery)->byStatus('confirm')->count();
        $total_follow_up = (clone $baseQuery)->byStatus('follow up')->count();
        $total_cancel = (clone $baseQuery)->byStatus('cancel')->count();
        $total_lead = (clone $baseQuery)->count();
        $total_revenue = (clone $baseQuery)->sum('total_amount');
        $total_expense = (clone $baseQuery)->sum('total_expense');

        // Build main list query with additional filters
        $list = (clone $baseQuery)
            ->search($request->search)
            ->when($request->status, fn($q, $status) => $q->byStatus($status))
            ->latest() // Always order by latest first (newest to oldest)
            ->with(['getAddedBy', 'bookings'])
            ->withSum('leadPayments', 'paid_amount')
            ->paginate(25);

        // Extract variables for view compatibility
        $search = $request->search;
        $search_date = $filters['date_range'];
        $source_id = $filters['source_id'];
        $status = $request->status;
        $search_staff_id = $filters['staff_id'];
        $search_service_type_id = $filters['service_type_id'];

        return view('admin.lead.index', compact(
            'list', 'search', 'search_date', 'source_id',
            'total_complete', 'total_confirm', 'total_follow_up', 'total_cancel',
            'total_lead', 'status', 'search_staff_id', 'total_revenue',
            'total_expense', 'search_service_type_id'
        ), ['page_title' => 'Leads']);
    }

    public function create(){
        return redirect()->route('bookings.create-direct')
            ->with('info', 'Use the New Booking form to create bookings directly.');
    }

    public function store(Request $request){
        $this->validate($request,[
            'guest_name' => 'required',
            'contact'    => 'required',
            'enquiry_date' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = new Lead;
            $data->booking_id       = 'B-'.date('Ymd').$data->id.rand(11, 99);
            $data->added_by         = Auth::guard('admin')->user()->id;
            $data->enquiry_date     = $request->enquiry_date;

            // Handle date range
            if($request->daterange){
                $data->booking_start_date   = date('Y-m-d',strtotime(explode('-',$request->daterange)[0]));
                $data->booking_end_date   = date('Y-m-d',strtotime(explode('-',$request->daterange)[1]));
            }
            $data->guest_name       = $request->guest_name;
            $data->contact          = $request->contact;
            $data->pax              = $request->pax;
            $data->short_plan       = $request->short_plan;
            $data->service_ids      = $request->service_ids;
            $data->tm_category_ids      = $request->tm_category_ids;
            $data->country          = $request->country;
            $data->state            = $request->state;
            $data->city             = $request->city;
            $data->lead_source_id   = $request->lead_source_id;
            $data->booking_status   = 'follow up';
            $data->plan_detail      = $request->plan_detail;
            $data->remark           = $request->remark;
            $data->save();

            // Handle booking services
            if ($request->has('booking_services') && is_array($request->booking_services)) {
                foreach ($request->booking_services as $service) {
                    if (isset($service['service_item_id']) && $service['service_item_id']) {
                        BookingService::create([
                            'lead_id' => $data->id,
                            'service_item_id' => $service['service_item_id'],
                            'service_type_id' => $service['service_type_id'] ?? null,
                            'quantity' => $service['quantity'] ?? 1,
                            'selling_price' => $service['selling_price'] ?? 0,
                            'cost_price' => $service['cost_price'] ?? 0,
                            'service_date' => $service['service_date'] ?? now(),
                            'notes' => $service['notes'] ?? null,
                        ]);
                    }
                }
                $data->calculateServiceTotals();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create lead: ' . $e->getMessage());
        }

        $searchForm = session('leadSearchForm', []);

        return redirect()->route('lead.index', $searchForm)->with('success', 'Leads add successfully !!');
    }

    public function show($id){
        $data = Lead::where('id',$id)->with('leadPayments')->withSum('leadPayments','paid_amount')->first();
        $lead_payment = LeadPayment::where('lead_id', $id)->first();

        // Get activity logs for this lead
        $activities = $data->activities()
            ->with('causer')
            ->latest()
            ->paginate(10);

        $searchForm = session('leadSearchForm', []);

        return view('admin.lead.view',compact('data', 'lead_payment', 'searchForm', 'activities'), ['page_title' => 'View Lead Status']);
    }

    /**
     * Display detailed lead information
     */
    public function details($id)
    {
        $data = Lead::where('id', $id)
            ->with(['leadPayments', 'getAddedBy', 'leadSource'])
            ->withSum('leadPayments', 'paid_amount')
            ->firstOrFail();

        // Get activity logs for this lead
        $activities = $data->activities()
            ->with('causer')
            ->latest()
            ->paginate(10);

        $searchForm = session('leadSearchForm', []);

        return view('admin.lead.details', compact('data', 'searchForm', 'activities'), ['page_title' => 'Lead Details']);
    }

    public function edit($id){
        $lead = Lead::with('bookingServices.serviceItem.serviceProvider')->find($id);
        $lead_sources = LeadSource::oldest('name')->get();
        $vendor_services = VendorService::oldest('name')->get();
        $serviceTypes = ServiceType::active()->with('serviceProviders')->get();
        $serviceItems = ServiceItem::active()->with(['serviceProvider', 'serviceType'])->get();

        return view('admin.lead.edit', compact('lead_sources', 'lead', 'vendor_services', 'serviceTypes', 'serviceItems'), ['page_title' => 'Lead Edit']);
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'guest_name'    => 'required',
            'contact'       => 'required',
            'enquiry_date'  => 'required',

        ]);

        $data = Lead::findOrFail($id);
        $data->enquiry_date     = $request->enquiry_date;

        // Handle date range
        if($request->daterange){
            $data->booking_start_date   = date('Y-m-d',strtotime(explode('-',$request->daterange)[0]));
            $data->booking_end_date   = date('Y-m-d',strtotime(explode('-',$request->daterange)[1]));
        }
        $data->guest_name       = $request->guest_name;
        $data->contact          = $request->contact;
        $data->pax              = $request->pax;
        $data->short_plan       = $request->short_plan;
        $data->service_ids      = $request->service_ids;
        $data->tm_category_ids      = $request->tm_category_ids;
        $data->country          = $request->country;
        $data->state            = $request->state;
        $data->city             = $request->city;
        $data->lead_source_id   = $request->lead_source_id;
        $data->plan_detail      = $request->plan_detail;
        $data->remark           = $request->remark;
        $data->save();

        // Handle booking services update
        if ($request->has('booking_services')) {
            // Delete existing booking services
            $data->bookingServices()->delete();

            // Create new booking services
            if (is_array($request->booking_services)) {
                foreach ($request->booking_services as $service) {
                    if (isset($service['service_item_id']) && $service['service_item_id']) {
                        BookingService::create([
                            'lead_id' => $data->id,
                            'service_item_id' => $service['service_item_id'],
                            'service_type_id' => $service['service_type_id'] ?? null,
                            'quantity' => $service['quantity'] ?? 1,
                            'selling_price' => $service['selling_price'] ?? 0,
                            'cost_price' => $service['cost_price'] ?? 0,
                            'service_date' => $service['service_date'] ?? now(),
                            'notes' => $service['notes'] ?? null,
                        ]);
                    }
                }
            }
            // Recalculate lead service totals
            $data->calculateServiceTotals();
        }

        $searchForm = session('leadSearchForm', []);
        return redirect()->route('lead.index', $searchForm)->with('success', 'Leads Updated successfully !!');
    }

    public function destroy($id)
    {
        try {
            $lead = Lead::findOrFail($id);
            
            // Check if lead has associated bookings
            if ($lead->bookings()->count() > 0) {
                return redirect()
                    ->route('lead.index')
                    ->with('error', 'Cannot delete lead with associated bookings. Please delete the bookings first.');
            }
            
            // Delete associated lead payments first
            $lead->leadPayments()->delete();
            
            // Delete associated booking services
            $lead->bookingServices()->delete();
            
            // Delete the lead (soft delete)
            $lead->delete();
            
            return redirect()
                ->route('lead.index')
                ->with('success', 'Lead deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('lead.index')
                ->with('error', 'Failed to delete lead: ' . $e->getMessage());
        }
    }

    public function vendorStore(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'phone' => 'required|unique:lead_sources,phone|string|max:20'
        ]);
        $data = new LeadSource;
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->save();
        return response()->json([
            'success'   => true,
            'message'   => "Vendor Add Successfully !!",
            'vendor'    => $data
        ], 200);
    }

    public function bookingStatus(Request $request, $id)
    {
        $request->validate([
            'booking_status'  =>'required',
        ]);
        
        $lead = Lead::where('id', $id)->first();
        $lead->booking_status = $request->booking_status;
        $lead->save();
        
        // If status is changed to 'confirm', check if booking exists and redirect to it
        if ($request->booking_status === 'confirm') {
            $booking = \App\Models\Booking::where('lead_id', $lead->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($booking) {
                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Status updated to Confirmed! Viewing booking details.');
            } else {
                return redirect()->back()
                    ->with('info', 'Status updated to Confirmed. Please create a booking for this lead.');
            }
        }
        
        return redirect()->back()->with('success', 'Status Updated Successfully!');
    }

    public function paymentStatus(Request $request, $id)
    {
        $lead = Lead::where('id',$id)->first();
        $lead->booking_status = $request->booking_status;
        $lead->payment_status = $request->payment_status;
        $lead->total_amount = $request->total_amount;
        $lead->total_expense = $request->total_expense;
        $lead->save();

        $lead_payment_ids = LeadPayment::where('lead_id',$lead->id)->pluck('id')->toArray();

        $delete_ids = array_diff($lead_payment_ids,$request->ids??[]);
        LeadPayment::whereIn('id',$delete_ids)->delete();

        foreach($request->payment_date as $key=>$payment_date){
            if(isset($request->ids[$key])){
                $edit_id = $request->ids[$key];
            }else{
                $edit_id = null;
            }
            $payment = LeadPayment::where('id',$edit_id)->first();
            if(!$payment){
                $payment = new LeadPayment;
                $payment->added_by      = Auth::guard('admin')->user()->id;
            }
            $payment->lead_id       = $lead->id;
            $payment->payment_mode  = $request->payment_mode[$key];
            $payment->payment_date  = $payment_date;
            $payment->paid_amount   = $request->paid_amount[$key];
            $payment->remark        = $request->remark[$key];
            $payment->save();
        }

        return redirect()->route('lead.show', $lead->id)->with('success', 'Payment Status Updated Successfully!');
    }

    public function invoice($id)
    {
        $lead_data = Lead::with('getAddedBy')->with('leadPayments')->withSum('leadPayments','paid_amount')->findOrFail($id);
        return view('admin.lead.invoice',compact('lead_data'), ['page_title' => 'Invoice']);
    }

    /**
     * Display legacy/old system data for a lead
     */
    public function legacyDetails($id)
    {
        $lead = Lead::with(['getAddedBy', 'leadPayments', 'leadSource', 'bookingServices.serviceItem.serviceProvider'])
            ->withSum('leadPayments', 'paid_amount')
            ->findOrFail($id);

        // Check if this is actually old system data (no new bookings)
        if ($lead->bookings()->exists()) {
            return redirect()->route('lead.index')->with('error', 'This lead has been converted to the new booking system.');
        }

        return view('admin.lead.legacy-details', compact('lead'), ['page_title' => 'Legacy Booking Details']);
    }

    /**
     * Display leads calendar view
     */
    public function calendar()
    {
        return view('admin.lead.calendar', ['page_title' => 'Leads Calendar']);
    }

    /**
     * Get leads events for calendar - Optimized
     */
    public function calendarEvents(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');

            // Optimized query - only select needed columns
            $leads = Lead::when(auth()->id() != 1, function($q) {
                    $q->where('added_by', auth()->id());
                })
                ->where(function($q) use ($start, $end) {
                    // Check booking dates OR enquiry date
                    $q->where(function($qu) use ($start, $end) {
                        $qu->whereBetween('booking_start_date', [$start, $end])
                           ->orWhereBetween('booking_end_date', [$start, $end])
                           ->orWhere(function($quu) use ($start, $end) {
                               $quu->where('booking_start_date', '<=', $start)
                                   ->where('booking_end_date', '>=', $end);
                           });
                    })->orWhereBetween('enquiry_date', [$start, $end]);
                })
                ->with([
                    'getAddedBy:id,name',
                    'bookings:id,lead_id',
                    'leadSource:id,name'
                ])
                ->limit(500)
                ->get();

            $events = $leads->map(function($lead) {
                // Check if this is a legacy lead (no new bookings)
                $isLegacy = $lead->bookings->isEmpty();

                // Determine color based on booking status and legacy status
                $color = $this->getLeadEventColor($lead->booking_status, $isLegacy);

                // Build title
                $title = $lead->guest_name;
                if ($lead->total_amount > 0) {
                    $title .= ' - ₹' . number_format($lead->total_amount, 0);
                }

                // Use booking_start_date if available, otherwise use enquiry_date
                $startDate = $lead->booking_start_date ?? $lead->enquiry_date;
                $endDate = $lead->booking_end_date;

                // If we have an end date, add 1 day for FullCalendar's exclusive end date
                if ($endDate && $startDate != $endDate) {
                    $endDate = date('Y-m-d', strtotime($endDate . ' +1 day'));
                } else {
                    $endDate = null; // Single day event
                }

                return [
                    'id' => $lead->id,
                    'title' => $title,
                    'start' => $startDate,
                    'end' => $endDate,
                    'color' => $color,
                    'extendedProps' => [
                        'lead_id' => $lead->id,
                        'guest_name' => $lead->guest_name,
                        'contact' => $lead->contact,
                        'pax' => $lead->pax,
                        'booking_status' => ucfirst($lead->booking_status),
                        'payment_status' => ucfirst($lead->payment_status ?? 'N/A'),
                        'total_amount' => $lead->total_amount ?? 0,
                        'paid_amount' => $lead->paid_amount ?? 0,
                        'pending_amount' => $lead->pending_amount ?? 0,
                        'lead_source' => $lead->leadSource->name ?? 'N/A',
                        'added_by' => $lead->getAddedBy->name ?? 'System',
                        'is_legacy' => $isLegacy,
                        'short_plan' => $lead->short_plan,
                        'has_booking' => !$isLegacy,
                    ],
                ];
            });

            return response()->json($events);
        } catch (\Exception $e) {
            \Log::error('Leads Calendar Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            // Return empty array so calendar doesn't break
            return response()->json([]);
        }
    }

    /**
     * Get event color based on status and legacy flag
     */
    private function getLeadEventColor($status, $isLegacy)
    {
        // Legacy leads have orange tones
        if ($isLegacy) {
            return match($status) {
                'complete' => '#f59e0b', // Orange
                'confirm' => '#fb923c',  // Light orange
                'cancel' => '#ea580c',   // Dark orange
                default => '#fbbf24',    // Yellow-orange
            };
        }

        // New leads have standard colors
        return match($status) {
            'complete' => '#10b981', // Green
            'confirm' => '#3b82f6',  // Blue
            'cancel' => '#ef4444',   // Red
            default => '#f59e0b',    // Orange
        };
    }

    public function payStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status'  =>'required',
        ]);

        $status = Lead::where('id', $id)->first();
        $status->payment_status = $request->payment_status;
        $status->save();
        return redirect()->back()->with('success', 'Payment Status Updated Successfully!');
    }

}
