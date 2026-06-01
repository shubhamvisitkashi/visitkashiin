<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ServiceType;

class PublicCalendarController extends Controller
{
    private function currentPin(): string
    {
        return optional(\App\Models\WebsiteSetup::where('name', 'calendar_pin')->first())->value ?? '000000';
    }

    const SESSION_TTL = 120; // seconds before PIN re-entry required

    private function isVerified(Request $request): bool
    {
        $current    = $this->currentPin();
        $sessionPin = $request->session()->get('calendar_verified_pin');
        $verifiedAt = $request->session()->get('calendar_verified_at');

        // PIN must match AND session must not have expired
        if (!$sessionPin || $sessionPin !== $current) return false;
        if (!$verifiedAt || (time() - $verifiedAt) > self::SESSION_TTL) return false;

        return true;
    }

    private function clearSession(Request $request): void
    {
        $request->session()->forget([
            'calendar_pin_ok',
            'calendar_verified_pin',
            'calendar_verified_at',
        ]);
    }

    /** Show PIN gate OR calendar */
    public function show(Request $request)
    {
        if ($this->isVerified($request)) {
            $serviceTypes = ServiceType::select('id', 'name')->orderBy('name')->get();
            $remaining    = self::SESSION_TTL - (time() - $request->session()->get('calendar_verified_at', time()));
            return view('public.calendar', compact('serviceTypes', 'remaining'));
        }

        $this->clearSession($request);
        return view('public.calendar_pin', ['error' => null]);
    }

    /** Handle PIN form submission */
    public function verify(Request $request)
    {
        $entered = trim($request->input('pin', ''));
        $correct = $this->currentPin();

        if ($entered === $correct) {
            $request->session()->regenerate();
            $request->session()->put('calendar_verified_pin', $correct);
            $request->session()->put('calendar_verified_at',  time());
            return redirect()->route('public.calendar');
        }

        return view('public.calendar_pin', ['error' => 'Incorrect PIN. Please try again.']);
    }

    /** Events JSON API — protected by CalendarPinGuard middleware */
    public function events(Request $request)
    {
        $start = $request->get('start');
        $end   = $request->get('end');

        $bookings = Booking::with([
                'lead:id,guest_name,contact,pax,booking_start_date',
                'quotation.items.serviceTemplate:id,name',
                'createdBy:id,name',
            ])
            ->where('booking_status', '!=', 'cancelled')
            ->whereHas('quotation.items', function ($q) use ($start, $end) {
                $q->whereNotNull('service_date')
                  ->when($start, fn($q2) => $q2->where('service_date', '>=', $start))
                  ->when($end,   fn($q2) => $q2->where('service_date', '<=', $end));
            })
            ->get();

        $events = [];
        foreach ($bookings as $bk) {
            if (!$bk->quotation) continue;

            foreach ($bk->quotation->items as $item) {
                if (!$item->service_date) continue;
                $events[] = [
                    'id'    => $bk->id . '_' . $item->id,
                    'title' => ($bk->lead->guest_name ?? 'Guest') . ' — ' . ($item->serviceTemplate->name ?? 'Service'),
                    'start' => $item->service_date->format('Y-m-d'),
                    'extendedProps' => [
                        'booking_number'  => $bk->booking_number,
                        'status'          => ucfirst($bk->booking_status),
                        'guest_name'      => $bk->lead->guest_name ?? '—',
                        'contact'         => $bk->lead->contact ?? '—',
                        'pax'             => $bk->lead->pax ?? '—',
                        'service'         => $item->serviceTemplate->name ?? '—',
                        'services'        => $item->serviceTemplate->name ?? '—',
                        'added_by'        => optional($bk->createdBy)->name ?? '—',
                        'total_amount'    => (float)($bk->total_amount  ?? 0),
                        'paid_amount'     => (float)($bk->paid_amount   ?? 0),
                        'pending_amount'  => (float)($bk->pending_amount ?? 0),
                    ],
                ];
            }
        }

        return response()->json($events);
    }
}
