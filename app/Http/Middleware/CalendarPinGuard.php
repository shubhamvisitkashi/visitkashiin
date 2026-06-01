<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CalendarPinGuard
{
    public function handle(Request $request, Closure $next)
    {
        // Always let PIN verify POST through
        if ($request->isMethod('POST') && $request->routeIs('public.calendar.verify')) {
            return $next($request);
        }

        $currentPin   = optional(\App\Models\WebsiteSetup::where('name', 'calendar_pin')->first())->value;
        $sessionPin   = $request->session()->get('calendar_verified_pin');
        $verifiedAt   = $request->session()->get('calendar_verified_at');
        $ttl          = 120; // seconds

        // Verified = pin matches + not expired
        $verified = $currentPin
            && $sessionPin
            && $sessionPin === $currentPin
            && $verifiedAt
            && (time() - $verifiedAt) <= $ttl;

        if (!$verified) {
            // Clear stale session
            $request->session()->forget(['calendar_pin_ok', 'calendar_verified_pin']);

            // For events API return 401
            if ($request->routeIs('public.calendar.events')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // For main page redirect to PIN gate
            if (!$request->routeIs('public.calendar.verify')) {
                return redirect()->route('public.calendar')->withErrors(['pin' => 'Please enter PIN.']);
            }
        }

        return $next($request);
    }
}
