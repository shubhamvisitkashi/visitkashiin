<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'terms_conditions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all service providers for this service type
     */
    public function serviceProviders()
    {
        return $this->belongsToMany(ServiceProvider::class, 'service_provider_service_type');
    }

    /**
     * Get all service templates for this service type
     */
    public function serviceTemplates()
    {
        return $this->hasMany(ServiceTemplate::class);
    }

    /**
     * Get all service items for this service type through templates
     */
    public function serviceItems()
    {
        return $this->hasManyThrough(ServiceItem::class, ServiceTemplate::class);
    }

    /**
     * Get all booking services for this service type
     */
    public function bookingServices()
    {
        return $this->hasMany(BookingService::class);
    }

    /**
     * Scope to get only active service types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get service type by slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}
