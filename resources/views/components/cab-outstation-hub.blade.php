@props(['hub', 'hubSlug', 'products'])
@php
    $vn = $hub['vehicleName'];
    $byRouteSlug = collect($products)->keyBy('slug');
    $priceOf = function ($slug) use ($byRouteSlug) {
        $p = $byRouteSlug->get($slug);
        if (!$p) return 0;
        return ($p->discounted_price ?? 0) > 0 ? $p->discounted_price : ($p->base_price ?? 0);
    };
    $routeUrl = fn ($slug) => route('product.detail', ['cab', $hubSlug, $slug]);

    // "Compare other vehicles" links — every known cab hub/subcategory except this one.
    $otherVehicles = [
        'innova-crysta'   => 'Innova Crysta',
        'ertiga'          => 'Ertiga',
        'swift-dzire'     => 'Swift Dzire',
        'tempo-traveller' => 'Tempo Traveller',
    ];
    unset($otherVehicles[$hubSlug]);
@endphp

<section class="tc-section" aria-label="About {{ $vn }} cab booking in Varanasi">

    <h2>Book {{ $vn }} in Varanasi</h2>
    <p>A {{ $vn }} booked here comes with a driver — this is a chauffeur-driven service, not self-drive rental. The {{ $hub['vehicleType'] }} seats up to {{ $hub['capacity'] }} passengers with room for luggage, and is available for airport transfers, a full day of local sightseeing, or outstation trips to nearby destinations. Pick the service you need from the listings above, or read on for route-specific details.</p>

    <h2>Why Book a {{ $vn }} for Varanasi Travel?</h2>
    <p>The {{ $vn }} is a common choice for Varanasi travel because it comfortably seats a family or small group along with luggage — useful for airport runs with bags, and for longer outstation drives. Every booking includes an experienced driver familiar with the local routes and the highways to nearby cities.</p>

    @if(!empty($hub['airportPickupSlug']) || !empty($hub['airportDropSlug']))
    <h2>{{ $vn }} Airport Transfer in Varanasi</h2>
    <p>Airport pickup and drop cover Lal Bahadur Shastri International Airport, Varanasi (Babatpur), with transfer to or from your hotel or any city address. Pickup includes flight tracking so the driver is waiting when you land; drop bookings should be made a few hours ahead of your flight.</p>
    <div class="tc-routes">
        @if(!empty($hub['airportPickupSlug']))
        <a class="tc-route-card" href="{{ $routeUrl($hub['airportPickupSlug']) }}">
            <h3>Airport Pickup</h3>
            <p>Arrival transfer from Varanasi Airport to your hotel or city address.</p>
            @if($priceOf($hub['airportPickupSlug']) > 0)<span class="tc-route-price">From ₹{{ number_format($priceOf($hub['airportPickupSlug'])) }}</span>@endif
        </a>
        @endif
        @if(!empty($hub['airportDropSlug']))
        <a class="tc-route-card" href="{{ $routeUrl($hub['airportDropSlug']) }}">
            <h3>Airport Drop</h3>
            <p>Departure transfer from your hotel to Varanasi Airport.</p>
            @if($priceOf($hub['airportDropSlug']) > 0)<span class="tc-route-price">From ₹{{ number_format($priceOf($hub['airportDropSlug'])) }}</span>@endif
        </a>
        @endif
    </div>
    @endif

    @if(!empty($hub['sightseeingSlug']))
    <h2>{{ $vn }} for Varanasi Local Sightseeing</h2>
    <p>A full-day local sightseeing booking is built around the way Varanasi is usually explored — the driver waits at each stop rather than a fixed drop-and-go schedule. Typical routes take in the Ganga ghats, the Kashi Vishwanath Temple area, Sarnath and Banaras Hindu University; some ghat-side lanes are pedestrian-only, so the final stretch on foot is normal.</p>
    <div class="tc-routes">
        <a class="tc-route-card" href="{{ $routeUrl($hub['sightseeingSlug']) }}">
            <h3>Local Sightseeing</h3>
            <p>Full-day {{ $vn }} for the ghats, temples and Sarnath.</p>
            @if($priceOf($hub['sightseeingSlug']) > 0)<span class="tc-route-price">From ₹{{ number_format($priceOf($hub['sightseeingSlug'])) }}</span>@endif
        </a>
    </div>
    @endif

    @if(!empty($hub['routes']))
    <h2>Outstation {{ $vn }} from Varanasi</h2>
    <p>Each outstation taxi route below has its own booking page with full route-specific details — the fare, distance and drive time vary by destination, so check the relevant page before booking.</p>
    <div class="tc-table-wrap">
    <table class="tc-table">
        <caption>{{ $vn }} outstation taxi fares from Varanasi</caption>
        <thead>
            <tr><th scope="col">Route</th><th scope="col">Distance / Duration</th><th scope="col">Starting Fare</th><th scope="col">Booking</th></tr>
        </thead>
        <tbody>
            @foreach($hub['routes'] as $route)
            @php
                $destShort = str_replace('Varanasi to ', '', $route['label']);
                $price = $priceOf($route['slug']);
            @endphp
            <tr>
                <td data-label="Route"><strong>{{ $vn }} {{ $route['label'] }} Taxi</strong><br><span class="tc-table-sub">{{ $route['blurb'] }}</span></td>
                <td data-label="Distance / Duration">{{ $route['meta'] }}</td>
                <td data-label="Starting Fare">@if($price > 0)₹{{ number_format($price) }}@else On request @endif</td>
                <td data-label="Booking"><a href="{{ $routeUrl($route['slug']) }}" aria-label="Book {{ $vn }} from {{ $route['label'] }}">Book {{ $destShort }} Cab →</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <p class="tc-table-note">*Outstation tariff runs at approximately ₹{{ $hub['perKm'] }}/km, plus parking, toll tax and driver allowance. Minimum billing is 200 km of running per day, regardless of the actual distance covered. The starting fares above are our current package rates for that specific route — confirm the exact fare for your dates on WhatsApp.</p>
    @endif

    <h2>How to Book a {{ $vn }} in Varanasi</h2>
    <ol class="tc-steps">
        <li>Open the listing for the service you need — airport, local sightseeing, or a specific outstation route.</li>
        <li>Fill in the enquiry form with your date, pickup location and passenger count, or message us directly on WhatsApp.</li>
        <li>Our team confirms the vehicle, driver details and final fare before your trip.</li>
    </ol>
    <p>
        For a private motor boat or hotel booking alongside your cab, see our <a href="{{ url('/boat') }}">Varanasi boat booking</a> and <a href="{{ url('/hotels') }}">Varanasi hotels</a> pages.
        @if(!empty($otherVehicles))
        Looking for a different vehicle instead? Compare
        @foreach($otherVehicles as $slug => $label)<a href="{{ route('product.sub.list', ['cab', $slug]) }}">{{ $label }}</a>{{ !$loop->last ? ($loop->remaining == 1 ? ' or ' : ', ') : '' }}@endforeach
        cab options in Varanasi.
        @endif
        Call or WhatsApp Visit Kashi at +91-7080109917, 7080109918 or 7080109919.
    </p>
</section>
