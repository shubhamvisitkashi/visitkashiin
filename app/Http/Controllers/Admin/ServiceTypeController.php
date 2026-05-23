<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of service types
     */
    public function index()
    {
        $serviceTypes = ServiceType::withCount(['serviceProviders', 'serviceItems'])
            ->orderBy('name')
            ->get();

        return view('admin.service-types.index', compact('serviceTypes'));
    }

    /**
     * Store a newly created service type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:service_types,name',
            'slug' => 'required|string|max:100|unique:service_types,slug|alpha_dash',
            'terms_conditions' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serviceType = ServiceType::create($validated);

        return redirect()->route('service-types.index')
            ->with('success', 'Service type created successfully.');
    }

    /**
     * Get service type data for editing
     */
    public function edit(ServiceType $serviceType)
    {
        return response()->json($serviceType);
    }

    /**
     * Update the specified service type
     */
    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:service_types,name,' . $serviceType->id,
            'slug' => 'required|string|max:100|unique:service_types,slug,' . $serviceType->id . '|alpha_dash',
            'terms_conditions' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serviceType->update($validated);

        return redirect()->route('service-types.index')
            ->with('success', 'Service type updated successfully.');
    }

    /**
     * Remove the specified service type
     */
    public function destroy(ServiceType $serviceType)
    {
        // Check if service type has associated providers or items
        if ($serviceType->serviceProviders()->count() > 0 || $serviceType->serviceItems()->count() > 0) {
            return redirect()->route('service-types.index')
                ->with('error', 'Cannot delete service type with associated providers or items.');
        }

        $serviceType->delete();

        return redirect()->route('service-types.index')
            ->with('success', 'Service type deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(ServiceType $serviceType)
    {
        $serviceType->update(['is_active' => !$serviceType->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $serviceType->is_active,
            'message' => 'Status updated successfully.'
        ]);
    }
}
