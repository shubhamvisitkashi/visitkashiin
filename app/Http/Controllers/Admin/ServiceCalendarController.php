<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingServiceAssignment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:booking-list', ['only' => ['index', 'getEvents']]);
    }

    /**
     * Display the service calendar
     */
    public function index()
    {
        return view('admin.service-calendar.index', [
            'page_title' => 'Service Calendar'
        ]);
    }

    /**
     * Get service assignments as calendar events
     */
    public function getEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $assignments = BookingServiceAssignment::with([
            'booking.lead',
            'serviceItem.serviceTemplate',
            'serviceProvider'
        ])
        ->whereBetween('assignment_date', [$start, $end])
        ->get();

        $events = $assignments->map(function ($assignment) {
            $serviceName = $assignment->serviceItem->serviceTemplate->name ?? 'Service';
            $providerName = $assignment->serviceProvider->name ?? 'N/A';
            $guestName = $assignment->booking->lead->guest_name ?? 'N/A';
            $bookingNumber = $assignment->booking->booking_number ?? 'N/A';

            return [
                'id' => $assignment->id,
                'title' => $serviceName,
                'start' => $assignment->assignment_date->format('Y-m-d'),
                'backgroundColor' => $this->getServiceColor($serviceName),
                'borderColor' => $this->getServiceColor($serviceName),
                'extendedProps' => [
                    'service' => $serviceName,
                    'provider' => $providerName,
                    'guest' => $guestName,
                    'booking' => $bookingNumber,
                    'cost' => '₹' . number_format($assignment->assigned_cost, 2),
                    'notes' => $assignment->notes ?? '',
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Get color based on service type
     */
    private function getServiceColor($serviceName)
    {
        $serviceName = strtolower($serviceName);
        
        if (str_contains($serviceName, 'hotel') || str_contains($serviceName, 'accommodation')) {
            return '#3b82f6'; // Blue
        } elseif (str_contains($serviceName, 'cab') || str_contains($serviceName, 'transport')) {
            return '#10b981'; // Green
        } elseif (str_contains($serviceName, 'guide')) {
            return '#f59e0b'; // Orange
        } elseif (str_contains($serviceName, 'meal') || str_contains($serviceName, 'food')) {
            return '#ef4444'; // Red
        } elseif (str_contains($serviceName, 'boat')) {
            return '#8b5cf6'; // Purple
        } else {
            return '#6b7280'; // Gray
        }
    }
}
