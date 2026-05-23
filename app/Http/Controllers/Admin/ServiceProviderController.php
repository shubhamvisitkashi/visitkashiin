<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of service providers
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::with('serviceTypes')
            ->withCount('serviceItems');

        // Filter by type (vendor/own)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by service type
        if ($request->filled('service_type_id')) {
            $query->whereHas('serviceTypes', function($q) use ($request) {
                $q->where('service_types.id', $request->service_type_id);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        $providers = $query->orderBy('name')->paginate(15);
        $serviceTypes = ServiceType::active()->orderBy('name')->get();

        return view('admin.service-providers.index', compact('providers', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new provider
     */
    public function create()
    {
        $serviceTypes = ServiceType::active()->orderBy('name')->get();
        return view('admin.service-providers.create', compact('serviceTypes'));
    }

    /**
     * Store a newly created provider
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type_ids' => 'required|array|min:1',
            'service_type_ids.*' => 'exists:service_types,id',
            'name' => 'required|string|max:200',
            'type' => 'required|in:vendor,own',
            'contact_person' => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            // 'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Extract service type IDs before creating
        $serviceTypeIds = $validated['service_type_ids'];
        unset($validated['service_type_ids']);

        $provider = ServiceProvider::create($validated);

        // Sync service types
        $provider->serviceTypes()->sync($serviceTypeIds);

        return redirect()->route('service-providers.index')
            ->with('success', 'Service provider created successfully.');
    }

    /**
     * Show the form for editing the specified provider
     */
    public function edit(ServiceProvider $serviceProvider)
    {
        $serviceProvider->load('serviceTypes');
        $serviceTypes = ServiceType::active()->orderBy('name')->get();
        return view('admin.service-providers.edit', compact('serviceProvider', 'serviceTypes'));
    }

    /**
     * Update the specified provider
     */
    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        $validated = $request->validate([
            'service_type_ids' => 'required|array|min:1',
            'service_type_ids.*' => 'exists:service_types,id',
            'name' => 'required|string|max:200',
            'type' => 'required|in:vendor,own',
            'contact_person' => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            // 'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Extract service type IDs before updating
        $serviceTypeIds = $validated['service_type_ids'];
        unset($validated['service_type_ids']);

        $serviceProvider->update($validated);

        // Sync service types
        $serviceProvider->serviceTypes()->sync($serviceTypeIds);

        return redirect()->route('service-providers.index')
            ->with('success', 'Service provider updated successfully.');
    }

    /**
     * Remove the specified provider
     */
    public function destroy(ServiceProvider $serviceProvider)
    {
        // Check if provider has associated items
        if ($serviceProvider->serviceItems()->count() > 0) {
            return redirect()->route('service-providers.index')
                ->with('error', 'Cannot delete provider with associated service items.');
        }

        $serviceProvider->delete();

        return redirect()->route('service-providers.index')
            ->with('success', 'Service provider deleted successfully.');
    }

    /**
     * Get providers by service type (for AJAX)
     */
    public function getByServiceType($serviceTypeId)
    {
        $providers = ServiceProvider::whereHas('serviceTypes', function($q) use ($serviceTypeId) {
            $q->where('service_types.id', $serviceTypeId);
        })->orderBy('name')->get(['id', 'name']);

        return response()->json($providers);
    }

    /**
     * Get providers by template (for vendor assignment)
     */
    public function getByTemplate($templateId)
    {
        // Get providers who have service items for this template
        $providers = ServiceProvider::whereHas('serviceItems', function($q) use ($templateId) {
            $q->where('service_template_id', $templateId);
        })
        ->with(['serviceItems' => function($q) use ($templateId) {
            $q->where('service_template_id', $templateId);
        }])
        ->orderBy('name')
        ->get();

        // Map to only return needed fields
        $result = $providers->map(function($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'service_items' => $provider->serviceItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'rate' => $item->vendor_cost // Use vendor_cost as the rate
                    ];
                })
            ];
        });

        return response()->json($result);
    }
}
