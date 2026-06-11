<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CabBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'customer_name', 'customer_phone', 'customer_alt_phone',
        'customer_email', 'gst_number',
        'pickup_address', 'drop_address',
        'trip_type', 'pickup_date', 'pickup_time',
        'return_date', 'total_days', 'total_km',
        'vehicle_id', 'vehicle_name', 'vehicle_number', 'seating_capacity', 'vehicle_count',
        'no_of_adults', 'no_of_children', 'carrier_on_roof', 'child_seat',
        'wheelchair_accessible', 'ac_required', 'luggage_details', 'flight_train_number',
        'base_fare', 'driver_allowance', 'toll_tax', 'parking',
        'state_tax', 'night_charges', 'extra_km_charges',
        'vendor_cost', 'discount', 'total_amount', 'advance_paid', 'pending_amount',
        'booking_status', 'payment_status',
        'lead_source_id', 'created_by', 'notes',
    ];

    protected $casts = [
        'pickup_date'  => 'date',
        'return_date'  => 'date',
        'pickup_time'  => 'datetime:H:i',
    ];

    // ── Relations ─────────────────────────────────────────────────
    public function vehicle()       { return $this->belongsTo(Vehicle::class); }
    public function payments()      { return $this->hasMany(CabBookingPayment::class); }
    public function createdBy()     { return $this->belongsTo(Admin::class, 'created_by'); }
    public function leadSource()    { return $this->belongsTo(LeadSource::class); }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeByStatus($q, $s)        { return $q->where('booking_status', $s); }
    public function scopeByPayment($q, $s)        { return $q->where('payment_status', $s); }
    public function scopeSearch($q, $term)        {
        if (!$term) return $q;
        return $q->where(function($qu) use ($term) {
            $qu->where('booking_number', 'like', "%$term%")
               ->orWhere('customer_name',  'like', "%$term%")
               ->orWhere('customer_phone', 'like', "%$term%")
               ->orWhere('vehicle_name',   'like', "%$term%");
        });
    }

    // ── Helpers ───────────────────────────────────────────────────
    public static function generateNumber(): string
    {
        $prefix = 'CB-' . date('Ymd') . '-';
        $last   = static::where('booking_number', 'like', $prefix . '%')
                        ->orderByDesc('id')->value('booking_number');
        $seq    = $last ? ((int)substr($last, -4) + 1) : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function recalcPaymentStatus(): void
    {
        $paid = $this->payments()->sum('amount');
        $status = 'unpaid';
        if ($paid > 0 && $paid >= $this->total_amount) $status = 'paid';
        elseif ($paid > 0) $status = 'partial';
        $this->update([
            'advance_paid'   => $paid,
            'pending_amount' => max(0, $this->total_amount - $paid),
            'payment_status' => $status,
        ]);
    }

    public function getTripTypeLabelAttribute(): string
    {
        return $this->trip_type ?? '';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->booking_status) {
            'pending'   => 'warning',
            'confirmed' => 'primary',
            'assigned'  => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }

    public function getPaymentColorAttribute(): string
    {
        return match($this->payment_status) {
            'paid'    => 'success',
            'partial' => 'warning',
            'unpaid'  => 'danger',
            default   => 'secondary',
        };
    }

    public function getSubtotalAttribute(): float
    {
        $perVehicle = (float)$this->base_fare
             + (float)$this->driver_allowance
             + (float)$this->toll_tax
             + (float)$this->parking
             + (float)$this->state_tax
             + (float)$this->night_charges
             + (float)$this->extra_km_charges;

        return $perVehicle * max(1, (int)$this->vehicle_count);
    }
}
