<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'service_item_id',
        'service_type_id',
        'quantity',
        'selling_price',
        'cost_price',
        'profit_amount',
        'profit_percentage',
        'service_date',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'selling_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'profit_percentage' => 'decimal:2',
        'service_date' => 'date',
    ];

    /**
     * Boot the model and register events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate profit when saving
        static::saving(function ($bookingService) {
            $bookingService->calculateProfit();
        });
    }

    /**
     * Get the lead that owns this booking service
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the service item
     */
    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }

    /**
     * Get the service type
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Calculate and set profit amount and percentage
     */
    public function calculateProfit()
    {
        $this->profit_amount = $this->selling_price - $this->cost_price;
        
        if ($this->cost_price > 0) {
            $this->profit_percentage = round(($this->profit_amount / $this->cost_price) * 100, 2);
        } else {
            $this->profit_percentage = 0;
        }
    }

    /**
     * Get total selling price (quantity * selling_price)
     */
    public function getTotalSellingPriceAttribute()
    {
        return $this->quantity * $this->selling_price;
    }

    /**
     * Get total cost price (quantity * cost_price)
     */
    public function getTotalCostPriceAttribute()
    {
        return $this->quantity * $this->cost_price;
    }

    /**
     * Get total profit (quantity * profit_amount)
     */
    public function getTotalProfitAttribute()
    {
        return $this->quantity * $this->profit_amount;
    }

    /**
     * Scope to filter by lead
     */
    public function scopeByLead($query, $leadId)
    {
        return $query->where('lead_id', $leadId);
    }

    /**
     * Scope to filter by service type
     */
    public function scopeByServiceType($query, $serviceTypeId)
    {
        return $query->where('service_type_id', $serviceTypeId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('service_date', [$startDate, $endDate]);
    }
}
