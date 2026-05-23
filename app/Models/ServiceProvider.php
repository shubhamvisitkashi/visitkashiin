<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'contact_number',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the service types that this provider offers
     */
    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'service_provider_service_type');
    }

    /**
     * Backward compatibility: Get first service type
     */
    public function serviceType()
    {
        return $this->serviceTypes()->first();
    }

    /**
     * Get all service items for this provider
     */
    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }

    /**
     * Get all booking services for this provider
     */
    public function bookingServices()
    {
        return $this->hasManyThrough(BookingService::class, ServiceItem::class);
    }

    /**
     * Get all service assignments for this provider
     */
    public function serviceAssignments()
    {
        return $this->hasMany(BookingServiceAssignment::class);
    }

    /**
     * Get all vendor payments for this provider
     */
    public function vendorPayments()
    {
        return $this->hasMany(VendorPayment::class);
    }

    /**
     * Scope to get only vendor providers
     */
    public function scopeVendors($query)
    {
        return $query->where('type', 'vendor');
    }

    /**
     * Scope to get only own service providers
     */
    public function scopeOwn($query)
    {
        return $query->where('type', 'own');
    }

    /**
     * Scope to get only active providers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by service type
     */
    public function scopeByServiceType($query, $serviceTypeId)
    {
        return $query->whereHas('serviceTypes', function($q) use ($serviceTypeId) {
            $q->where('service_types.id', $serviceTypeId);
        });
    }

    /**
     * Check if this is a vendor
     */
    public function isVendor()
    {
        return $this->type === 'vendor';
    }

    /**
     * Check if this is own service
     */
    public function isOwn()
    {
        return $this->type === 'own';
    }

    /**
     * Get total amount assigned to this vendor from all bookings
     */
    public function getTotalAssignedAttribute()
    {
        return $this->serviceAssignments()->sum('assigned_cost');
    }

    /**
     * Get total amount paid to this vendor
     */
    public function getTotalPaidAttribute()
    {
        return $this->vendorPayments()->sum('amount');
    }

    /**
     * Get outstanding balance (amount due to vendor)
     */
    public function getOutstandingBalanceAttribute()
    {
        return $this->total_assigned - $this->total_paid;
    }

    /**
     * Get last payment date
     */
    public function getLastPaymentDateAttribute()
    {
        $lastPayment = $this->vendorPayments()->latest('payment_date')->first();
        return $lastPayment ? $lastPayment->payment_date : null;
    }

    /**
     * Get count of assignments
     */
    public function getAssignmentsCountAttribute()
    {
        return $this->serviceAssignments()->count();
    }
}
