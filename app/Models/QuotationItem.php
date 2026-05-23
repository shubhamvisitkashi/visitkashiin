<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'service_template_id',
        'service_type_id',
        'quantity',
        'unit_price',
        'total_price',
        'service_date',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'service_date' => 'date',
    ];

    /**
     * Boot method to auto-calculate total
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });

        static::saved(function ($item) {
            $item->quotation->calculateTotal();
        });

        static::deleted(function ($item) {
            $item->quotation->calculateTotal();
        });
    }

    /**
     * Relationships
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function serviceTemplate()
    {
        return $this->belongsTo(ServiceTemplate::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
