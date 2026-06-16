<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoatBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id', 'booked_by', 'boat_id',
        'name', 'email', 'phone', 'no_of_person', 'adults', 'children',
        'total_amount', 'total_discount', 'final_amount',
        'booking_date', 'seat_number', 'booking_status', 'payment_status',
        // Route & timing
        'boarding_ghat', 'drop_ghat', 'boat_timing', 'pickup_time', 'drop_time',
        // Event/booking type
        'booking_type', 'event_on_boat', 'celebration_type',
        // Add-ons
        'decoration', 'photographer', 'live_music', 'priest', 'flowers', 'fireworks',
        // Notes & source
        'guest_notes', 'special_requests', 'lead_source_id',
        // Pricing
        'payment_method', 'payment_account_id', 'vendor_cost', 'b2b_vendor_cost', 'margin_amount',
        'extra_per_person_rate', 'base_pax', 'created_by',
        // Staff
        'boatman_id',
        // Cancellation
        'cancel_reason', 'refund_amount', 'non_refund_amount', 'cancelled_at',
    ];

    protected $casts = [
        'refund_amount'     => 'decimal:2',
        'non_refund_amount' => 'decimal:2',
        'cancelled_at'      => 'datetime',
    ];

    public function boat() {
        return $this->belongsTo(Boat::class, 'boat_id');
    }

    public function payments() {
        return $this->hasMany(BoatBookingPayment::class, 'boat_booking_id');
    }

    public function createdBy() {
        return $this->belongsTo(Admin::class, 'created_by');
    }

}
