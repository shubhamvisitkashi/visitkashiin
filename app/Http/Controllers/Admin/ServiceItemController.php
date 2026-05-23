<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceItem;
use App\Models\ServiceProvider;
use App\Models\ServiceTemplate;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceItemController extends Controller
{
    /**
     * Display a listing of service items
     */
    public function index(Request $request)
    {
        $query = ServiceItem::with(['serviceProvider', 'serviceTemplate.serviceType'])
            ->withCount('bookingServices');

        // Filter by service template
        if ($request->filled('service_template_id')) {
            $query->where('service_template_id', $request->service_template_id);
        }

        // Filter by provider
        if ($request->filled('service_provider_id')) {
            $query->where('service_provider_id', $request->service_provider_id);
        }

        // Search by template name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('serviceTemplate', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(15);
        $serviceTemplates = ServiceTemplate::active()->with('serviceType')->orderBy('name')->get();
        $providers = ServiceProvider::active()->orderBy('name')->get();

        return view('admin.service-items.index', compact('items', 'serviceTemplates', 'providers'));
    }

    /**
     * Show the form for creating a new item (tabular format)
     */
    public function create(Request $request)
    {
        $providers = ServiceProvider::with('serviceTypes')->active()->orderBy('name')->get();
        
        $providerId = $request->get('provider_id');
        $serviceTemplates = collect();
        $existingItems = [];
        
        if ($providerId) {
            $provider = ServiceProvider::with('serviceTypes')->find($providerId);
            
            if ($provider) {
                // Get service type IDs for this provider
                $serviceTypeIds = $provider->serviceTypes->pluck('id')->toArray();
                
                // Get templates only for provider's service types
                $serviceTemplates = ServiceTemplate::active()
                    ->with('serviceType')
                    ->whereIn('service_type_id', $serviceTypeIds)
                    ->get()
                    ->groupBy('serviceType.name');
                
                // Get existing items
                $existingItems = ServiceItem::where('service_provider_id', $providerId)
                    ->pluck('service_template_id')
                    ->toArray();
            }
        }

        return view('admin.service-items.create', compact('providers', 'serviceTemplates', 'providerId', 'existingItems'));
    }

    /**
     * Store newly created items (bulk from tabular form)
     */
    public function store(Request $request)
    {
        // Custom validation messages
        $messages = [
            'service_provider_id.required' => 'Please select a service provider.',
            'service_provider_id.exists' => 'The selected service provider is invalid.',
            'templates.required' => 'Please select at least one service template by checking the checkbox.',
            'templates.min' => 'Please select at least one service template by checking the checkbox.',
            'templates.*.vendor_cost.required' => 'Vendor cost is required for all checked templates.',
            'templates.*.vendor_cost.numeric' => 'Vendor cost must be a valid number.',
            'templates.*.vendor_cost.min' => 'Vendor cost must be at least 0.',
            'templates.*.base_price.required' => 'Base price is required for all checked templates.',
            'templates.*.base_price.numeric' => 'Base price must be a valid number.',
            'templates.*.base_price.min' => 'Base price must be at least 0.',
            'templates.*.capacity.integer' => 'Capacity must be a whole number.',
            'templates.*.capacity.min' => 'Capacity must be at least 1.',
        ];

        $validated = $request->validate([
            'service_provider_id' => 'required|exists:service_providers,id',
            'templates' => 'required|array|min:1',
            'templates.*.service_template_id' => 'required|exists:service_templates,id',
            'templates.*.vendor_cost' => 'required|numeric|min:0',
            'templates.*.base_price' => 'required|numeric|min:0',
            'templates.*.capacity' => 'nullable|integer|min:1',
        ], $messages);

        $createdCount = 0;
        $updatedCount = 0;
        
        foreach ($validated['templates'] as $templateData) {
            // Use updateOrCreate to either update existing or create new
            $item = ServiceItem::updateOrCreate(
                [
                    'service_provider_id' => $validated['service_provider_id'],
                    'service_template_id' => $templateData['service_template_id'],
                ],
                [
                    'vendor_cost' => $templateData['vendor_cost'],
                    'base_price' => $templateData['base_price'],
                    'capacity' => $templateData['capacity'] ?? null,
                    'is_active' => $templateData['is_active'] ?? true,
                ]
            );
            
            if ($item->wasRecentlyCreated) {
                $createdCount++;
            } else {
                $updatedCount++;
            }
        }

        $message = "Successfully created {$createdCount} service item(s)";
        if ($updatedCount > 0) {
            $message .= " and updated {$updatedCount} existing item(s)";
        }
        $message .= ".";

        return redirect()->route('service-items.index')
            ->with('success', $message);
    }

    /**
     * Show the form for editing items for a specific provider (tabular format)
     */
    public function edit($providerId)
    {
        $provider = ServiceProvider::with('serviceTypes')->findOrFail($providerId);
        
        // Get service type IDs for this provider
        $serviceTypeIds = $provider->serviceTypes->pluck('id')->toArray();
        
        // Get templates only for provider's service types
        $serviceTemplates = ServiceTemplate::active()
            ->with('serviceType')
            ->whereIn('service_type_id', $serviceTypeIds)
            ->get()
            ->groupBy('serviceType.name');

        // Get existing items for this provider
        $existingItems = ServiceItem::where('service_provider_id', $providerId)
            ->with('serviceTemplate')
            ->get()
            ->keyBy('service_template_id');

        return view('admin.service-items.edit', compact('provider', 'serviceTemplates', 'existingItems'));
    }

    /**
     * Update items for a provider (bulk from tabular form)
     */
    public function update(Request $request, $providerId)
    {
        $provider = ServiceProvider::findOrFail($providerId);

        // Custom validation messages
        $messages = [
            'templates.*.vendor_cost.required' => 'Vendor cost is required for all checked templates.',
            'templates.*.vendor_cost.numeric' => 'Vendor cost must be a valid number.',
            'templates.*.vendor_cost.min' => 'Vendor cost must be at least 0.',
            'templates.*.base_price.required' => 'Base price is required for all checked templates.',
            'templates.*.base_price.numeric' => 'Base price must be a valid number.',
            'templates.*.base_price.min' => 'Base price must be at least 0.',
            'templates.*.capacity.integer' => 'Capacity must be a whole number.',
            'templates.*.capacity.min' => 'Capacity must be at least 1.',
        ];

        $validated = $request->validate([
            'templates' => 'nullable|array',
            'templates.*.service_template_id' => 'required|exists:service_templates,id',
            'templates.*.vendor_cost' => 'required|numeric|min:0',
            'templates.*.base_price' => 'required|numeric|min:0',
            'templates.*.capacity' => 'nullable|integer|min:1',
            'templates.*.is_active' => 'boolean',
        ], $messages);

        $updatedCount = 0;
        $createdCount = 0;
        
        // Get all template IDs from the form
        $submittedTemplateIds = collect($validated['templates'] ?? [])->pluck('service_template_id')->toArray();
        
        // Delete items that were removed (not in submitted list)
        ServiceItem::where('service_provider_id', $providerId)
            ->whereNotIn('service_template_id', $submittedTemplateIds)
            ->delete();

        // Update or create items
        foreach ($validated['templates'] ?? [] as $templateData) {
            $item = ServiceItem::where('service_provider_id', $providerId)
                ->where('service_template_id', $templateData['service_template_id'])
                ->first();
            
            if ($item) {
                $item->update([
                    'vendor_cost' => $templateData['vendor_cost'],
                    'base_price' => $templateData['base_price'],
                    'capacity' => $templateData['capacity'] ?? null,
                    'is_active' => $templateData['is_active'] ?? true,
                ]);
                $updatedCount++;
            } else {
                ServiceItem::create([
                    'service_provider_id' => $providerId,
                    'service_template_id' => $templateData['service_template_id'],
                    'vendor_cost' => $templateData['vendor_cost'],
                    'base_price' => $templateData['base_price'],
                    'capacity' => $templateData['capacity'] ?? null,
                    'is_active' => $templateData['is_active'] ?? true,
                ]);
                $createdCount++;
            }
        }

        return redirect()->route('service-items.index')
            ->with('success', "Updated {$updatedCount} and created {$createdCount} service item(s).");
    }

    /**
     * Remove the specified item
     */
    public function destroy(ServiceItem $serviceItem)
    {
        // Check if item has associated bookings
        if ($serviceItem->bookingServices()->count() > 0) {
            return redirect()->route('service-items.index')
                ->with('error', 'Cannot delete service item with associated bookings.');
        }

        $serviceItem->delete();

        return redirect()->route('service-items.index')
            ->with('success', 'Service item deleted successfully.');
    }

    /**
     * Get items by provider (AJAX)
     */
    public function getByProvider($providerId)
    {
        $items = ServiceItem::where('service_provider_id', $providerId)
            ->with('serviceTemplate')
            ->active()
            ->get();

        return response()->json($items->map(function($item) {
            return [
                'id' => $item->id,
                'service_template_id' => $item->service_template_id,
                'name' => $item->serviceTemplate->name,
                'base_price' => $item->base_price,
                'vendor_cost' => $item->vendor_cost,
                'capacity' => $item->capacity,
                'cost_price' => $item->cost_price,
            ];
        }));
    }

    /**
     * Calculate profit for given prices (AJAX)
     */
    public function calculateProfit(Request $request)
    {
        $sellingPrice = $request->input('selling_price', 0);
        $costPrice = $request->input('cost_price', 0);
        
        $profitAmount = $sellingPrice - $costPrice;
        $profitPercentage = $costPrice > 0 ? round(($profitAmount / $costPrice) * 100, 2) : 0;

        return response()->json([
            'profit_amount' => $profitAmount,
            'profit_percentage' => $profitPercentage,
        ]);
    }
}
