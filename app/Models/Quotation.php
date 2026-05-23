<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Admin\Admin;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quotation extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'lead_id',
        'quotation_number',
        'quotation_date',
        'valid_until',
        'total_amount',
        'custom_total',
        'service_charge',
        'subtotal',
        'gst_type',
        'gst_amount',
        'gst_percentage',
        'status',
        'notes',
        'itinerary_html',
        'created_by',
        'is_converted',
        'booking_id',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'total_amount' => 'decimal:2',
        'custom_total' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'is_converted' => 'boolean',
    ];

    /**
     * Append accessor attributes to array/JSON
     */
    protected $appends = ['itinerary'];

    /**
     * Boot method to auto-generate quotation number
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($quotation) {
            if (empty($quotation->quotation_number)) {
                $quotation->quotation_number = self::generateQuotationNumber();
            }
            if (empty($quotation->quotation_date)) {
                $quotation->quotation_date = now();
            }
            if (empty($quotation->valid_until)) {
                $quotation->valid_until = now()->addDays(7);
            }
        });
    }

    /**
     * Generate unique quotation number
     */
    public static function generateQuotationNumber()
    {
        $date = now()->format('Ymd');
        $lastQuotation = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastQuotation ? (int)substr($lastQuotation->quotation_number, -3) + 1 : 1;
        return 'Q-' . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relationships
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    /**
     * Query Scopes for Performance Optimization
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'sent']);
    }

    /**
     * Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Search quotations by number or lead name
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('quotation_number', 'like', "%{$search}%")
              ->orWhereHas('lead', function($q2) use ($search) {
                  $q2->where('guest_name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Filter converted quotations
     */
    public function scopeConverted($query)
    {
        return $query->where('is_converted', true);
    }

    /**
     * Filter non-converted quotations
     */
    public function scopeNotConverted($query)
    {
        return $query->where('is_converted', false);
    }

    /**
     * Attributes
     */
    public function getIsExpiredAttribute()
    {
        return $this->valid_until && $this->valid_until->isPast() && $this->status !== 'accepted';
    }

    /**
     * Accessor for itinerary (maps to itinerary_html)
     */
    public function getItineraryAttribute()
    {
        return $this->attributes['itinerary_html'] ?? null;
    }

    /**
     * Mutator for itinerary (maps to itinerary_html)
     */
    public function setItineraryAttribute($value)
    {
        $this->attributes['itinerary_html'] = $value;
    }

    /**
     * Calculate total from items
     */
    public function calculateTotal()
    {
        $this->total_amount = $this->items()->sum('total_price');
        $this->save();
    }

    /**
     * Activity Log Configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['quotation_number', 'status', 'total_amount', 'valid_until', 'is_converted'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('quotation')
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => 'Quotation created',
                'updated' => 'Quotation updated',
                'deleted' => 'Quotation deleted',
                default => "Quotation {$eventName}"
            });
    }
}
