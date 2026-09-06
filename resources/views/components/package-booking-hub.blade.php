@props(['products'])
@php
    $byRouteSlug = collect($products)->keyBy('slug');
    $priceOf = function ($slug) use ($byRouteSlug) {
        $p = $byRouteSlug->get($slug);
        if (!$p) return 0;
        return ($p->discounted_price ?? 0) > 0 ? $p->discounted_price : ($p->base_price ?? 0);
    };
    $pkgUrl = fn ($slug) => route('product.detail', ['packages', 'varanasi', $slug]);

    // Real package durations currently sold, in order.
    $durationRows = [
        ['label' => '1 Night / 2 Days', 'slug' => '1-night-2-days-varanasi-tour-package', 'bestFor' => 'A short Varanasi visit'],
        ['label' => '2 Nights / 3 Days', 'slug' => '2-nights-3-days-varanasi-tour-package', 'bestFor' => 'Ghats, temples & Sarnath at an easy pace'],
        ['label' => '3 Nights / 4 Days', 'slug' => '3-nights-4-days-varanasi-tour-package', 'bestFor' => 'A fuller Varanasi itinerary'],
        ['label' => '4 Nights / 5 Days', 'slug' => '4-nights-5-days-varanasi-tour-package', 'bestFor' => 'A relaxed, in-depth trip'],
    ];
@endphp

<section class="tc-section" aria-label="Varanasi tour package information">

    <h2>Varanasi Tour Packages</h2>
    <p>VisitKashi's Varanasi tour packages range from a short 1-night visit to a relaxed 4-night stay, plus combined pilgrimage tours covering Kashi, Prayagraj and Ayodhya together. Every package includes the core Varanasi experience — the ghats, the evening Ganga Aarti, Kashi Vishwanath Temple and Sarnath — with the itinerary adjusted for how many days you have.</p>

    <h2>Multi-Day Varanasi Tour Packages</h2>
    <p>Choose a package based on how much time you have in the city — each covers the same core sights, with a fuller itinerary and more temples or excursions added as the duration increases.</p>

    <div class="tc-table-wrap">
    <table class="tc-table">
        <caption>Varanasi tour packages — starting prices by duration</caption>
        <thead>
            <tr><th scope="col">Duration</th><th scope="col">Best For</th><th scope="col">Starting Price</th><th scope="col">Booking</th></tr>
        </thead>
        <tbody>
            @foreach($durationRows as $row)
            @php $price = $priceOf($row['slug']); @endphp
            <tr>
                <td data-label="Duration"><strong>{{ $row['label'] }}</strong></td>
                <td data-label="Best For">{{ $row['bestFor'] }}</td>
                <td data-label="Starting Price">@if($price > 0)₹{{ number_format($price) }}@else On request @endif</td>
                <td data-label="Booking"><a href="{{ $pkgUrl($row['slug']) }}" aria-label="Book the {{ $row['label'] }} Varanasi tour package">Book Now →</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <p class="tc-table-note">Prices shown are the current starting fare per package and can vary with hotel category, group size and season.</p>

    <h2>Kashi, Prayagraj &amp; Ayodhya Tour Package</h2>
    <p>For a pilgrimage covering all three cities, the 3 Nights / 4 Days package takes in the Kashi Vishwanath Jyotirlinga and evening Ganga Aarti in Varanasi, a boat ride to the Triveni Sangam in Prayagraj, and Ram Janmabhoomi Mandir darshan in Ayodhya — with cab transfers between cities and hotel stays in Kashi included. <a href="{{ $pkgUrl('kashi-prayagraj-ayodhya-3-nights-4-days-tour-package-spiritual-triangle-tour') }}">View the Kashi, Prayagraj &amp; Ayodhya tour package</a>.</p>

    <h2>Family &amp; Group Travel Packages</h2>
    <p>For families and larger groups, itineraries can be customised around comfortable accommodation, guided temple visits and a shared pace rather than a fixed schedule. <a href="{{ $pkgUrl('varanasi-family-and-group-travel-packages-custom-tours-itineraries') }}">View family &amp; group travel packages</a>.</p>

    <h2>What's Included in a Varanasi Tour Package</h2>
    <p>Inclusions vary by package, but generally cover hotel accommodation, a car with driver for sightseeing and transfers, and airport or railway station pickup and drop. Boat ride charges, temple entry fees and meals are sometimes listed as exclusions or add-ons — check the individual package for exactly what's included before booking.</p>

    <h2>Why Choose VisitKashi for Tour Packages?</h2>
    <p>VisitKashi is a Varanasi-focused travel booking site — packages, boat, cab and hotel bookings are all handled by a local team who can help customise an itinerary before you book. Booking is enquiry-based: you submit your details through the form or WhatsApp, and our team confirms the itinerary, hotel category and final price.</p>
    <p>Looking for other Varanasi travel needs? See <a href="{{ url('/boat') }}">boat booking in Varanasi</a>, <a href="{{ url('/cab') }}">cab booking in Varanasi</a>, or <a href="{{ url('/hotels') }}">Varanasi hotels</a>. Call or WhatsApp Visit Kashi at +91-7080109917, 7080109918 or 7080109919.</p>

</section>
