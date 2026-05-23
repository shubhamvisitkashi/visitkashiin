<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type_id',
        'name',
        'description',
        'default_selling_price',
        'default_cost_estimate',
        'capacity',
        'image',
        'is_active',
    ];

    protected $casts = [
        'default_selling_price' => 'decimal:2',
        'default_cost_estimate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service type that owns the template.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the quotation items using this template.
     */
    public function quotationItems()
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * Scope a query to only include active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get estimated profit for this template.
     */
    public function getEstimatedProfitAttribute()
    {
        return $this->default_selling_price - $this->default_cost_estimate;
    }

    /**
     * Get estimated profit percentage.
     */
    public function getEstimatedProfitPercentageAttribute()
    {
        if ($this->default_cost_estimate > 0) {
            return ($this->estimated_profit / $this->default_cost_estimate) * 100;
        }
        return 0;
    }
}
