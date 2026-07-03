<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'no_of_person',
        'arrival_date',
        'checkin_time',
        'checkout_time',
        'message',
        'package_id',
        'package_name',
        'booking_amount',
        'time_slot',
        'pickup_ghat',
        'children_count',
        'special_notes',
        'luggage_bags',
        'roof_carrier',
    ];

    /**
     * Decode any HTML-entity-encoded ampersands (e.g. "&amp;") in older records
     * so the package name displays correctly everywhere.
     */
    public function getPackageNameAttribute($value)
    {
        while ($value && str_contains($value, '&amp;')) {
            $value = html_entity_decode($value, ENT_QUOTES);
        }
        return $value;
    }

    /**
     * Determine the lead category, icon, and email subject prefix based on package_name.
     */
    public function leadCategory(): array
    {
        $pn = strtolower($this->package_name ?? '');

        if (str_contains($pn, 'boat') || str_contains($pn, 'ganga') || str_contains($pn, 'aarti') || str_contains($pn, 'cruise')) {
            return ['category' => 'Boat Ride',    'icon' => '⛵', 'subject' => 'New Boat Enquiry'];
        }
        if (str_contains($pn, 'cab') || str_contains($pn, 'car') || str_contains($pn, 'taxi') || str_contains($pn, 'traveller') || str_contains($pn, 'seater') || str_contains($pn, 'airport') || str_contains($pn, 'railway')) {
            return ['category' => 'Cab Booking',  'icon' => '🚕', 'subject' => 'New Cab Enquiry'];
        }
        if (str_contains($pn, 'hotel') || str_contains($pn, 'homestay') || str_contains($pn, 'stay') || str_contains($pn, 'room')) {
            return ['category' => 'Stay / Hotel', 'icon' => '🏨', 'subject' => 'New Hotel Enquiry'];
        }
        if (str_contains($pn, 'package') || str_contains($pn, 'tour') || str_contains($pn, 'varanasi to') || str_contains($pn, 'sightseeing')) {
            return ['category' => 'Tour Package', 'icon' => '🗺️', 'subject' => 'New Tour Package Enquiry'];
        }
        if (str_contains($pn, 'contact')) {
            return ['category' => 'Contact', 'icon' => '✉️', 'subject' => 'New Contact Enquiry'];
        }
        return ['category' => 'General', 'icon' => '📋', 'subject' => 'New Enquiry'];
    }

    /**
     * Send (or resend) the admin lead-notification email for this enquiry.
     */
    public function sendLeadMail(): void
    {
        $cat = $this->leadCategory();

        Mail::send('email.enquiry_mail', [
            'name'           => $this->name,
            'phone'          => $this->phone,
            'arrival_date'   => $this->arrival_date ? date('d M Y', strtotime($this->arrival_date)) : null,
            'messages'       => $this->message,
            'package_name'   => $this->package_name,
            'no_of_person'   => $this->no_of_person,
            'children_count' => (int)($this->children_count ?? 0),
            'time_slot'      => $this->time_slot,
            'pickup_ghat'    => $this->pickup_ghat,
            'special_notes'  => $this->special_notes,
            'booking_amount' => $this->booking_amount,
            'luggage_bags'   => (int)($this->luggage_bags ?? 0),
            'roof_carrier'   => $this->roof_carrier,
            'checkin_time'   => $this->checkin_time  ? date('d M Y, h:i A', strtotime($this->checkin_time))  : null,
            'checkout_time'  => $this->checkout_time ? date('d M Y, h:i A', strtotime($this->checkout_time)) : null,
            'enquiry'        => $this,
            'enq_category'   => $cat['category'],
            'enq_icon'       => $cat['icon'],
        ], function ($message) use ($cat) {
            $message->to('help.visitkashi@gmail.com');
            $message->cc('info.visitkashi@gmail.com');
            $message->subject($cat['subject'] . ' — ' . $this->package_name . ' | Visit Kashi');
        });
    }
}
