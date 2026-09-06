@props(['products'])
@php
    $byRouteSlug = collect($products)->keyBy('slug');
    $priceOf = function ($slug) use ($byRouteSlug) {
        $p = $byRouteSlug->get($slug);
        if (!$p) return 0;
        return ($p->discounted_price ?? 0) > 0 ? $p->discounted_price : ($p->base_price ?? 0);
    };
    $subUrl = fn ($subSlug) => route('product.sub.list', ['cab', $subSlug]);
    $prodUrl = fn ($subSlug, $slug) => route('product.detail', ['cab', $subSlug, $slug]);

    // Real vehicle-type comparison — one representative product per type,
    // used only to read the current starting price for that subcategory.
    $tableRows = [
        ['label' => 'Swift Dzire', 'sub' => 'swift-dzire', 'priceSlug' => 'swift-dzire-cab-airport-pickup', 'capacity' => 'Up to 4 persons', 'bestFor' => 'Solo &amp; couple travel, airport transfer'],
        ['label' => 'Ertiga', 'sub' => 'ertiga', 'priceSlug' => 'ertiga-for-varanasi-airport-pickup', 'capacity' => 'Up to 7 persons', 'bestFor' => 'Small families &amp; groups'],
        ['label' => 'Innova Crysta', 'sub' => 'innova-crysta', 'priceSlug' => 'innova-crysta-for-airport-pickup', 'capacity' => 'Up to 6 persons', 'bestFor' => 'Comfort, luggage &amp; outstation trips'],
        ['label' => 'Tempo Traveller', 'sub' => 'tempo-traveller', 'priceSlug' => null, 'capacity' => '12–26 seater options', 'bestFor' => 'Larger groups &amp; family functions'],
    ];
    $tempoMin = collect($products)->where('subcategory_id', 15)->map(fn($p) => ($p->discounted_price ?? 0) > 0 ? $p->discounted_price : ($p->base_price ?? 0))->filter()->min();
@endphp

<section class="tc-section" aria-label="Cab booking information for Varanasi">

    <h2>Cab Booking in Varanasi</h2>
    <p>Cab booking in Varanasi covers a few different needs: an airport or railway station transfer, a full day of local sightseeing by car, or an outstation trip to a nearby city. VisitKashi lists Swift Dzire, Ertiga, Innova Crysta and Tempo Traveller options — pick a vehicle type below, or use the price table to compare before booking online or on WhatsApp.</p>

    <h2>Cab Booking for Varanasi Airport (Pickup &amp; Drop)</h2>
    <p>Airport pickup and drop cover Lal Bahadur Shastri International Airport, Varanasi (Babatpur), with transfer to or from your hotel or any city address. Pickup includes flight tracking so the driver is waiting when you land; drop bookings should be made a few hours ahead of your flight. Airport transfer is available in <a href="{{ $prodUrl('swift-dzire', 'swift-dzire-cab-airport-pickup') }}">Swift Dzire</a>, <a href="{{ $prodUrl('ertiga', 'ertiga-for-varanasi-airport-pickup') }}">Ertiga</a> and <a href="{{ $prodUrl('innova-crysta', 'innova-crysta-for-airport-pickup') }}">Innova Crysta</a>.</p>

    <h2>Local Sightseeing by Cab in Varanasi</h2>
    <p>A full-day local sightseeing cab covers the city at your own pace — typically the Ganga ghats, the Kashi Vishwanath Temple area, Sarnath and Banaras Hindu University, with the driver waiting at each stop rather than a fixed drop-and-go schedule.</p>

    <h2>Outstation Cab Booking from Varanasi</h2>
    <p>For travel beyond the city, outstation cabs are available to Ayodhya, Prayagraj, Vindhyachal, Bodhgaya and Lucknow in Innova Crysta, Ertiga or Swift Dzire — each route has its own listing with distance, duration and inclusions specific to that trip. See the <a href="{{ $subUrl('innova-crysta') }}">Innova Crysta</a>, <a href="{{ $subUrl('ertiga') }}">Ertiga</a> or <a href="{{ $subUrl('swift-dzire') }}">Swift Dzire</a> pages for the full route list and current pricing.</p>

    <h2>Varanasi Cab Booking Price</h2>
    <p>Cab prices vary by vehicle type, trip type (airport, local or outstation), duration and route. The table below shows the current starting price for each vehicle; final pricing for your date and route is confirmed on WhatsApp before you book.</p>

    <div class="tc-table-wrap">
    <table class="tc-table">
        <caption>Varanasi cab booking — starting prices by vehicle type</caption>
        <thead>
            <tr><th scope="col">Vehicle</th><th scope="col">Capacity</th><th scope="col">Starting Price</th><th scope="col">Best For</th><th scope="col">Booking</th></tr>
        </thead>
        <tbody>
            @foreach($tableRows as $row)
            @php $price = $row['priceSlug'] ? $priceOf($row['priceSlug']) : ($tempoMin ?? 0); @endphp
            <tr>
                <td data-label="Vehicle"><strong>{{ $row['label'] }}</strong></td>
                <td data-label="Capacity">{{ $row['capacity'] }}</td>
                <td data-label="Starting Price">@if($price > 0)₹{{ number_format($price) }}@else On request @endif</td>
                <td data-label="Best For">{{ $row['bestFor'] }}</td>
                <td data-label="Booking"><a href="{{ $subUrl($row['sub']) }}" aria-label="Book {{ $row['label'] }}">View Options →</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <p class="tc-table-note">Prices shown are the current starting fare for each vehicle type and can change with route, duration and availability.</p>

    <h2>Why Choose VisitKashi for Cab Booking?</h2>
    <p>VisitKashi is a Varanasi-focused travel booking site — cab, boat, hotel and tour bookings are all handled by a local team who can help with route and vehicle questions before you book. Booking is enquiry-based: you submit your details through the form or WhatsApp, and our team confirms availability and the final price rather than charging an unclear online rate upfront.</p>
    <p>Looking for other Varanasi travel needs? See <a href="{{ url('/boat') }}">boat booking in Varanasi</a>, <a href="{{ url('/hotels') }}">Varanasi hotels</a>, or <a href="{{ url('/packages') }}">Varanasi tour packages</a>. Call or WhatsApp Visit Kashi at +91-7080109917, 7080109918 or 7080109919.</p>

</section>
