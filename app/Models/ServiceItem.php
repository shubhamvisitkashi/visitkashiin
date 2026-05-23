<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',
        'service_template_id',
        'base_price',
        'vendor_cost',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'vendor_cost' => 'decimal:2',
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service provider that owns this item
     */
    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    /**
     * Get the service template that this item is based on
     */
    public function serviceTemplate()
    {
        return $this->belongsTo(ServiceTemplate::class);
    }

    /**
     * Get the service type through the template
     */
    public function serviceType()
    {
        return $this->hasOneThrough(
            ServiceType::class,
            ServiceTemplate::class,
            'id', // Foreign key on service_templates table
            'id', // Foreign key on service_types table
            'service_template_id', // Local key on service_items table
            'service_type_id' // Local key on service_templates table
        );
    }

    /**
     * Get all booking services for this item
     */
    public function bookingServices()
    {
        return $this->hasMany(BookingService::class);
    }

    /**
     * Scope to get only active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by service provider
     */
    public function scopeByProvider($query, $providerId)
    {
        return $query->where('service_provider_id', $providerId);
    }

    /**
     * Scope to filter by service template
     */
    public function scopeByServiceTemplate($query, $serviceTemplateId)
    {
        return $query->where('service_template_id', $serviceTemplateId);
    }

    /**
     * Get the name from the service template
     */
    public function getNameAttribute()
    {
        return $this->serviceTemplate?->name ?? 'N/A';
    }

    /**
     * Get the description from the service template
     */
    public function getDescriptionAttribute()
    {
        return $this->serviceTemplate?->description ?? '';
    }

    /**
     * Get the cost price (vendor_cost for vendors, base_price for own services)
     */
    public function getCostPriceAttribute()
    {
        return $this->serviceProvider->isVendor() ? $this->vendor_cost : $this->base_price;
    }

    /**
     * Calculate profit margin percentage if selling price is provided
     */
    public function calculateProfitMargin($sellingPrice)
    {
        $costPrice = $this->cost_price;
        if ($costPrice == 0) {
            return 0;
        }
        return round((($sellingPrice - $costPrice) / $costPrice) * 100, 2);
    }

    /**
     * Calculate profit amount
     */
    public function calculateProfit($sellingPrice)
    {
        return $sellingPrice - $this->cost_price;
    }
}
