<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Admin;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Booking extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'lead_id',
        'booking_number',
        'booking_date',
        'booking_status',
        'total_amount',
        'discount_amount',
        'paid_amount',
        'pending_amount',
        'vendor_cost',
        'created_by',
        'deleted_by',
        'is_gst_invoice',
        'gst_invoice_number',
        'gst_rate',
        'taxable_amount',
        'gst_amount',
        'customer_gstin',
        'company_name',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'is_gst_invoice' => 'boolean',
        'gst_rate' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'gst_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
            if (empty($booking->booking_date)) {
                $booking->booking_date = now();
            }
            $booking->pending_amount = $booking->total_amount - $booking->paid_amount;
        });

        static::updating(function ($booking) {
            $booking->pending_amount = $booking->total_amount - $booking->paid_amount;
        });
    }

    public static function generateBookingNumber()
    {
        $date = now()->format('Ymd');
        $prefix = 'B-' . $date;
        
        // Include soft-deleted and hard-deleted bookings to avoid duplicate booking numbers
        // Search by booking_number pattern instead of created_at to handle all edge cases
        $lastBooking = self::withTrashed()
            ->where('booking_number', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(booking_number, -3) AS UNSIGNED) DESC')
            ->first();
        
        $sequence = $lastBooking ? (int)substr($lastBooking->booking_number, -3) + 1 : 1;
        
        // Safety check: If somehow we still get a duplicate, keep incrementing
        $maxAttempts = 1000;
        $attempts = 0;
        $bookingNumber = $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        while (self::withTrashed()->where('booking_number', $bookingNumber)->exists() && $attempts < $maxAttempts) {
            $sequence++;
            $bookingNumber = $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
            $attempts++;
        }
        
        return $bookingNumber;
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function serviceAssignments()
    {
        return $this->hasMany(BookingServiceAssignment::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(Admin::class, 'deleted_by');
    }

    /**
     * Query Scopes for Performance Optimization
     */
    
    /**
     * Filter by booking status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('booking_status', $status);
    }

    /**
     * Search bookings by number or lead name
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('booking_number', 'like', "%{$search}%")
              ->orWhereHas('lead', function($q2) use ($search) {
                  $q2->where('guest_name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Filter confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('booking_status', 'confirmed');
    }

    /**
     * Filter completed bookings
     */
    public function scopeCompleted($query)
    {
        return $query->where('booking_status', 'completed');
    }

    /**
     * Filter bookings with due amount (pending payment)
     */
    public function scopeWithDueAmount($query)
    {
        return $query->where('pending_amount', '>', 0);
    }

    /**
     * Filter by payment status
     * - fully_paid: paid_amount >= total_amount (no pending amount)
     * - partially_paid: 0 < paid_amount < total_amount (has both payment and pending)
     * - unpaid: paid_amount = 0 (no payment received)
     */
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        if ($paymentStatus === 'fully_paid') {
            // Fully paid: paid amount equals or exceeds total amount
            return $query->whereRaw('paid_amount >= total_amount')
                         ->orWhere('pending_amount', '<=', 0);
        } elseif ($paymentStatus === 'partially_paid') {
            // Partially paid: has some payment but still has pending amount
            return $query->where('paid_amount', '>', 0)
                         ->where('pending_amount', '>', 0);
        } elseif ($paymentStatus === 'unpaid') {
            // Unpaid: no payment received at all
            return $query->where('paid_amount', '<=', 0)
                         ->orWhere('paid_amount', 0);
        }
        return $query;
    }

    /**
     * Update paid amount from payments
     */
    public function updatePaidAmount()
    {
        $this->paid_amount = $this->payments()->sum('amount');
        $this->pending_amount = $this->total_amount - $this->paid_amount;
        $this->save();
    }

    /**
     * Activity Log Configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['booking_number', 'booking_status', 'total_amount', 'paid_amount', 'pending_amount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('booking')
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => 'Booking created',
                'updated' => 'Booking updated',
                'deleted' => 'Booking deleted',
                default => "Booking {$eventName}"
            });
    }
}
