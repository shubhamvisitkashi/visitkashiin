@props(['products'])
@php
    $byRouteSlug = collect($products)->keyBy('slug');
    $priceOf = function ($slug) use ($byRouteSlug) {
        $p = $byRouteSlug->get($slug);
        if (!$p) return 0;
        return ($p->discounted_price ?? 0) > 0 ? $p->discounted_price : ($p->base_price ?? 0);
    };
    $boatUrl = fn ($subSlug, $slug) => route('product.detail', ['boat', $subSlug, $slug]);

    // Real representative products for the comparison table — one per
    // genuinely distinct boat type/use-case currently sold on the site.
    $tableRows = [
        ['label' => 'Motor Boat (Shared)', 'sub' => 'motor-boat', 'slug' => 'evening-boat-ride-in-varanasi', 'capacity' => 'Up to 10 persons', 'bestFor' => 'Ganga Aarti & sightseeing', 'duration' => '~2–2.5 hrs'],
        ['label' => 'Private Motor Boat', 'sub' => 'motor-boat', 'slug' => 'private-motor-boat-for-evening-ganga-aarti', 'capacity' => 'Up to 10 persons', 'bestFor' => 'Private Ganga Aarti', 'duration' => '~2 hrs'],
        ['label' => 'Bajra Boat', 'sub' => 'bajra-boat', 'slug' => 'private-bajra-boat-for-ganga-aarti', 'capacity' => 'Up to 50 persons', 'bestFor' => 'Groups & traditional boat', 'duration' => '~2 hrs 15 min'],
        ['label' => 'Decorated / Event Boat', 'sub' => 'event-boat', 'slug' => 'surprise-event-boat-decoration-in-varanasi-unique-decorations-for-special-occasions', 'capacity' => 'Varies by package', 'bestFor' => 'Birthdays, anniversaries & celebrations', 'duration' => 'Varies by package'],
    ];
@endphp

<section class="tc-section" aria-label="Boat booking information for Varanasi">

    <h2>Boat Booking in Varanasi</h2>
    <p>Boat booking in Varanasi covers a few genuinely different experiences on the Ganga: a shared or private motor boat ride timed for the evening Ganga Aarti, an early morning ride to catch sunrise over the river, or a traditional Bajra boat for larger groups. Pick the boat type below, or use the price table to compare options before booking online or on WhatsApp.</p>

    <h2>Ganga Aarti Boat Booking in Varanasi</h2>
    <p>The evening Ganga Aarti is performed at Dashashwamedh Ghat, and a Ganga Aarti boat ride positions you on the river for the ceremony instead of standing in the crowd on the ghat steps. Boats report around 5:30 PM and are positioned in front of the aarti for the 7:00 PM–7:45 PM ceremony, giving you a river view of the diyas, temple bells and chanting. Both shared and <a href="{{ $boatUrl('motor-boat', 'private-motor-boat-for-evening-ganga-aarti') }}">private motor boats</a> and the <a href="{{ $boatUrl('bajra-boat', 'private-bajra-boat-for-ganga-aarti') }}">private Bajra boat</a> are available for this route. <a href="{{ $boatUrl('motor-boat', 'private-motor-boat-for-evening-ganga-aarti') }}">Book Ganga Aarti Boat →</a></p>

    <h2>Private Boat Booking in Varanasi</h2>
    <p>A private boat means the whole boat is booked for your group only — no sharing with other travellers. This is a good fit for couples, families, small groups, photography, or a quieter, more personal ride during the Ganga Aarti. Both a <a href="{{ $boatUrl('motor-boat', 'private-motor-boat-for-evening-ganga-aarti') }}">private motor boat</a> and a <a href="{{ $boatUrl('bajra-boat', 'private-bajra-boat-for-ganga-aarti') }}">private Bajra boat</a> are available, and decorated boats can be arranged for birthdays, anniversaries and other special occasions.</p>

    <h2>Morning &amp; Sunrise Boat Ride in Varanasi</h2>
    <p>The morning boat ride departs before sunrise, so the exact reporting time varies with the season rather than being fixed year-round. The ride takes you past the ghats — including Assi Ghat, Dashashwamedh Ghat, Manikarnika Ghat and Harishchandra Ghat — while the city wakes up: people bathing, yoga, and the morning rituals along the riverbank, with sunrise over the Ganga along the way.</p>
    <p><a href="{{ $boatUrl('motor-boat', 'morning-boat-ride-in-varanasi') }}">Book a morning boat ride</a> to see the sunrise from the river.</p>

    <h2>Sunset Boat Ride in Varanasi</h2>
    <p>The evening boat ride starts a few hours before sunset and carries on into the Ganga Aarti, so it covers both the golden-hour view over the ghats and the evening ceremony in one trip. <a href="{{ $boatUrl('motor-boat', 'evening-boat-ride-in-varanasi') }}">Book the evening boat ride</a> for this route.</p>

    <h2>Boat Booking Near Dashashwamedh Ghat</h2>
    <p>Dashashwamedh Ghat is where the main evening Ganga Aarti is performed, and it's a supported pickup point for the private motor boat. <a href="{{ $boatUrl('motor-boat', 'pickup-dashaswamedh-ghat-private-boat-ride-for-evening-ganga-aarti') }}">Book a boat with pickup from Dashashwamedh Ghat</a> if this is your nearest or preferred boarding point.</p>

    <h2>Boat Booking Near Assi Ghat</h2>
    <p>Assi Ghat, at the southern end of the riverfront, is another supported pickup point for the evening Ganga Aarti boat ride, and is also one of the ghats covered on the morning ride. <a href="{{ $boatUrl('motor-boat', 'pickup-assi-ghat-private-boat-ride-for-evening-ganga-aarti') }}">Book a boat with pickup from Assi Ghat</a> if you're staying nearby. Pickup from <a href="{{ $boatUrl('motor-boat', 'pickup-namo-ghat-private-boat-ride-for-evening-ganga-aarti') }}">Namo Ghat</a> is also available.</p>

    <h2>Varanasi Boat Booking Price</h2>
    <p>Boat prices vary by boat type, capacity, duration, route and whether you want a private booking or a decorated boat for a special occasion — pricing can also shift around festival dates when demand is higher. The table below shows the current starting price for each boat type; final pricing for your date and group size is confirmed on WhatsApp before you book.</p>

    <div class="tc-table-wrap">
    <table class="tc-table">
        <caption>Varanasi boat booking — starting prices by boat type</caption>
        <thead>
            <tr><th scope="col">Boat Type</th><th scope="col">Capacity</th><th scope="col">Starting Price</th><th scope="col">Best For</th><th scope="col">Duration</th><th scope="col">Booking</th></tr>
        </thead>
        <tbody>
            @foreach($tableRows as $row)
            @php $price = $priceOf($row['slug']); @endphp
            <tr>
                <td data-label="Boat Type"><strong>{{ $row['label'] }}</strong></td>
                <td data-label="Capacity">{{ $row['capacity'] }}</td>
                <td data-label="Starting Price">@if($price > 0)₹{{ number_format($price) }}@else On request @endif</td>
                <td data-label="Best For">{{ $row['bestFor'] }}</td>
                <td data-label="Duration">{{ $row['duration'] }}</td>
                <td data-label="Booking"><a href="{{ $boatUrl($row['sub'], $row['slug']) }}" aria-label="Book {{ $row['label'] }}">Book Now →</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <p class="tc-table-note">Prices shown are the current starting fare per boat and can change with availability, group size and season. <a href="{{ url('/boat/dev-diwali-booking') }}">Dev Diwali boat booking</a> and <a href="{{ url('/boat/event-boat') }}">event/decorated boats</a> for special occasions have their own separate listings and pricing.</p>

    <h2>Why Choose VisitKashi for Boat Booking?</h2>
    <p>VisitKashi is a Varanasi-focused travel booking site — boat, cab, hotel and tour bookings are all handled by a local team who can help with route, timing and pickup-ghat questions before you book. Booking is enquiry-based: you submit your details through the form or WhatsApp, and our team confirms availability and the final price rather than charging an unclear online rate upfront.</p>
    <p>Looking for other Varanasi travel needs? See <a href="{{ url('/cab') }}">cab booking in Varanasi</a>, <a href="{{ url('/hotels') }}">Varanasi hotels</a>, or <a href="{{ url('/packages') }}">Varanasi tour packages</a>. Call or WhatsApp Visit Kashi at +91-7080109917, 7080109918 or 7080109919.</p>

</section>
