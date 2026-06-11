<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceTemplate;
use App\Models\ServiceType;

class ServiceTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:service-template-list|service-type-list|service-item-list');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceTemplate::with('serviceType');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $templates = $query->latest()->paginate(15)->withQueryString();

        $serviceTypes = ServiceType::active()->get();

        $stats = [
            'total'    => ServiceTemplate::count(),
            'active'   => ServiceTemplate::where('is_active', true)->count(),
            'inactive' => ServiceTemplate::where('is_active', false)->count(),
        ];

        return view('admin.service-templates.index', compact('templates', 'serviceTypes', 'stats'), ['page_title' => 'Service Templates']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceTypes = ServiceType::active()->get();
        return view('admin.service-templates.create', compact('serviceTypes'), ['page_title' => 'Add Service Template']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'property_type' => 'nullable|in:hotel,homestay',
            'bhk_type' => 'nullable|string',
            'default_selling_price' => 'required|numeric|min:0',
            'default_cost_estimate' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            // 'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ServiceTemplate::create($validated);

        return redirect()->route('service-templates.index')
            ->with('success', 'Service template created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $template = ServiceTemplate::findOrFail($id);
        $serviceTypes = ServiceType::active()->get();
        return view('admin.service-templates.edit', compact('template', 'serviceTypes'), ['page_title' => 'Edit Service Template']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $template = ServiceTemplate::findOrFail($id);

        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'property_type' => 'nullable|in:hotel,homestay',
            'bhk_type' => 'nullable|string',
            'default_selling_price' => 'required|numeric|min:0',
            'default_cost_estimate' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            // 'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $template->update($validated);

        return redirect()->route('service-templates.index')
            ->with('success', 'Service template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $template = ServiceTemplate::findOrFail($id);

        // Check if template is being used in any quotations
        if ($template->quotationItems()->count() > 0) {
            return redirect()->route('service-templates.index')
                ->with('error', 'Cannot delete this service template because it is being used in ' . $template->quotationItems()->count() . ' quotation(s). Please remove it from all quotations first or mark it as inactive instead.');
        }

        $template->delete();

        return redirect()->route('service-templates.index')
            ->with('success', 'Service template deleted successfully.');
    }
}
