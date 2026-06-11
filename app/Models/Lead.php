<?php

namespace App\Models;

use App\Models\Enquiry;
use App\Models\Admin\Admin;
use App\Models\LeadSource;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Lead extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $casts = [
        'tm_category_ids' => 'array',
        'service_ids' => 'array',
        'enquiry_date' => 'date',
        'booking_start_date' => 'date',
        'booking_end_date' => 'date',
    ];

    protected $fillable = [
        'guest_name',
        'contact',
        'alt_phone',
        'email',
        'address',
        'pax',
        'country',
        'state',
        'city',
        'booking_start_date',
        'booking_end_date',
        'short_plan',
        'plan_detail',
        'booking_status',
        'total_amount',
        'payment_status',
        'added_by',
        'assigned_user',
        'lead_source_id',
        'tm_category_ids',
        'service_ids',
        'notes',
    ];

    public function getAddedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function getAssignedUser()
    {
        return $this->belongsTo(Admin::class, 'assigned_user');
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function leadPayments() {
        return $this->hasMany(LeadPayment::class);
    }

    /**
     * Get all booking services for this lead
     */
    public function bookingServices()
    {
        return $this->hasMany(BookingService::class);
    }

    /**
     * Get all bookings for this lead
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Calculate and update service totals
     */
    public function calculateServiceTotals()
    {
        $bookingServices = $this->bookingServices;
        
        $this->services_total = $bookingServices->sum('total_selling_price');
        $this->services_cost = $bookingServices->sum('total_cost_price');
        $this->services_profit = $bookingServices->sum('total_profit');
        
        $this->save();
    }

    /**
     * Get total profit including services
     */
    public function getTotalProfitAttribute()
    {
        return ($this->services_profit ?? 0);
    }

    /**
     * Query Scopes for Performance Optimization
     */
    
    /**
     * Apply common filters to lead queries
     * Eliminates duplicate filtering logic across the application
     */
    public function scopeApplyFilters($query, array $filters)
    {
        return $query
            // Filter by current user if not super admin
            ->when(auth()->id() != 1, function($q) {
                $q->where('added_by', auth()->id());
            })
            // Filter by lead source
            ->when($filters['source_id'] ?? null, function($q, $sourceId) {
                $q->where('lead_source_id', $sourceId);
            })
            // Filter by assigned staff
            ->when($filters['staff_id'] ?? null, function($q, $staffId) {
                $q->where('added_by', $staffId);
            })
            // Filter by service type (JSON column)
            ->when($filters['service_type_id'] ?? null, function($q, $serviceTypeId) {
                $q->whereJsonContains('service_ids', (string)$serviceTypeId);
            })
            // Filter by date range
            ->when($filters['date_range'] ?? null, function($q, $dateRange) {
                [$startDate, $endDate] = static::parseDateRange($dateRange);
                $q->where(function($qu) use ($startDate, $endDate) {
                    $qu->whereBetween('booking_start_date', [$startDate, $endDate])
                       ->orWhereBetween('booking_end_date', [$startDate, $endDate]);
                });
            });
    }

    /**
     * Filter leads by booking status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('booking_status', $status);
    }

    /**
     * Search leads by name or contact
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('guest_name', 'like', "%{$search}%")
              ->orWhere('contact', 'like', "%{$search}%");
        });
    }

    /**
     * Helper: Parse date range string into Carbon instances
     * Format: "MM/DD/YYYY - MM/DD/YYYY"
     */
    public static function parseDateRange($dateRange)
    {
        $dates = explode('-', $dateRange);
        
        $startDate = \Carbon\Carbon::parse(trim($dates[0]))->startOfDay();
        $endDate = \Carbon\Carbon::parse(trim($dates[1]))->endOfDay();
        
        return [$startDate, $endDate];
    }

    /**
     * Activity Log Configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['guest_name', 'status', 'phone', 'email', 'total_amount', 'assigned_user'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('lead')
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => 'Lead created',
                'updated' => 'Lead updated',
                'deleted' => 'Lead deleted',
                default => "Lead {$eventName}"
            });
    }

}
