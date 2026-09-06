@extends('frontend.layouts.app')

@section('meta')
@php
    $displayName  = optional($sub_category)->name ?? optional($category)->name ?? 'Varanasi Travel';
    $catLabel     = optional($category)->name ?? '';
    $subLabel     = optional($sub_category)->name ?? '';
    $seoTitle     = $sub_category ? trim($catLabel . ' ' . $subLabel) : $displayName;

    $listingTitle = optional($sub_category)->meta_title ?? optional($category)->meta_title ?? $seoTitle;
    $listingKw    = optional($sub_category)->meta_keyword ?? optional($category)->meta_keyword ?? '';

    $rawDesc = optional($sub_category)->meta_description ?? optional($category)->meta_description ?? '';
    $isRealDesc = $rawDesc && $rawDesc !== $listingKw && substr_count($rawDesc, ',') < 5 && strlen($rawDesc) > 60;
    $listingDesc = $isRealDesc
        ? $rawDesc
        : 'Explore the best ' . $seoTitle . ' in Varanasi. Book verified, locally vetted options at the best prices with Visit Kashi — Varanasi\'s #1 travel platform.';

    $listingImg   = asset('frontend/images/logo1.png');
    // Canonical always points at the production domain (never /public/),
    // matching the pattern already used on boat/cab product pages.
    $canonicalPath = optional($sub_category)->slug
        ? '/'.optional($category)->slug.'/'.optional($sub_category)->slug
        : '/'.optional($category)->slug;
    $canonicalUrl = 'https://visitkashi.in'.$canonicalPath;
    // Avoid "... in Varanasi in Varanasi ..." when the meta title already names the city.
    $listingTitleTag = stripos($listingTitle, 'varanasi') !== false ? $listingTitle : $listingTitle.' in Varanasi';

    $firstProduct = $products->first();
    if ($firstProduct && !empty($firstProduct->images) && is_array($firstProduct->images)) {
        $listingImg = asset('backend/admin/product_images/' . $firstProduct->images[0]);
    }

    $isHotelCat    = in_array(optional($category)->slug, ['hotels','homestay']);
    $isCabCat      = str_contains(strtolower(optional($category)->slug ?? ''), 'cab');
    $isBoatCat     = str_contains(strtolower(optional($category)->slug ?? ''), 'boat');
    $isPackageCat  = str_contains(strtolower(optional($category)->slug ?? ''), 'package');
    $catSlugMain   = optional($category)->slug ?? 'default';
    $subCategories = optional($category)->subCategory ?? collect();
    $totalCount    = $products->count();

    // This generic listing template is shared by every category/subcategory
    // combination on the site. $cabHubs is the single source of truth for
    // every "vehicle hub" page (Innova Crysta, Ertiga, Swift Dzire, ...) —
    // add an entry here to bring a new vehicle type online with the same
    // topical content/FAQ/H1/outstation-table treatment.
    $cabHubs = [
        'innova-crysta' => [
            'vehicleName' => 'Innova Crysta', 'vehicleType' => 'AC SUV', 'capacity' => 6, 'perKm' => 18,
            'airportPickupSlug' => 'innova-crysta-for-airport-pickup',
            'airportDropSlug'   => 'innova-crysta-for-airport-drop',
            'sightseeingSlug'   => 'innova-crysta-for-varanasi-local-sightseen',
            'routes' => [
                ['slug' => 'innova-crysta-for-varanasi-to-vindhyachal', 'label' => 'Varanasi to Vindhyachal', 'meta' => '~60 km · 2–3 hrs', 'blurb' => 'Vindhyachal cab for Vindhyavasini Temple, usually same-day'],
                ['slug' => 'innova-crysta-for-varanasi-to-prayagraj',   'label' => 'Varanasi to Prayagraj',   'meta' => '~150 km · 2–3 hrs', 'blurb' => 'Prayagraj cab for Sangam and onward connections'],
                ['slug' => 'innova-crysta-for-varanasi-to-ayodhya',     'label' => 'Varanasi to Ayodhya',     'meta' => 'Multi-hour drive', 'blurb' => 'Ayodhya cab for Ram Mandir and temple visits'],
                ['slug' => 'innova-crysta-for-varanasi-to-bodhgaya',    'label' => 'Varanasi to Bodhgaya',    'meta' => '~255–300 km · 5–6 hrs', 'blurb' => 'Bodhgaya cab for the Mahabodhi Temple pilgrimage route'],
                ['slug' => 'innova-crysta-for-varanasi-to-lucknow',     'label' => 'Varanasi to Lucknow',     'meta' => 'Multi-hour drive', 'blurb' => 'Lucknow cab for business travel or same-day return'],
            ],
        ],
        'ertiga' => [
            'vehicleName' => 'Ertiga', 'vehicleType' => 'AC MPV', 'capacity' => 7, 'perKm' => 16,
            'airportPickupSlug' => 'ertiga-for-varanasi-airport-pickup',
            'airportDropSlug'   => 'ertiga-for-varanasi-airport-drop',
            'sightseeingSlug'   => 'ertiga-for-varanasi-local-sightseen',
            'routes' => [
                ['slug' => 'ertiga-for-varanasi-to-vindhyachal',    'label' => 'Varanasi to Vindhyachal', 'meta' => '~60 km · 2–3 hrs', 'blurb' => 'Vindhyachal cab for Vindhyavasini Temple, usually same-day'],
                ['slug' => 'ertiga-cab-for-varanasi-to-prayagraj',  'label' => 'Varanasi to Prayagraj',   'meta' => '~150 km · 2–3 hrs', 'blurb' => 'Prayagraj cab for Sangam and onward connections'],
                ['slug' => 'ertiga-cab-for-varanasi-to-ayodhya',    'label' => 'Varanasi to Ayodhya',     'meta' => 'Multi-hour drive', 'blurb' => 'Ayodhya cab for Ram Mandir and temple visits'],
                ['slug' => 'ertiga-for-varanasi-to-bodhgaya',       'label' => 'Varanasi to Bodhgaya',    'meta' => 'Multi-hour drive', 'blurb' => 'Bodhgaya cab for the Mahabodhi Temple pilgrimage route'],
                ['slug' => 'ertiga-cab-for-varanasi-to-lucknow',    'label' => 'Varanasi to Lucknow',     'meta' => 'Multi-hour drive', 'blurb' => 'Lucknow cab for business travel or same-day return'],
            ],
        ],
        'swift-dzire' => [
            'vehicleName' => 'Swift Dzire', 'vehicleType' => 'AC Sedan', 'capacity' => 4, 'perKm' => 12,
            'airportPickupSlug' => 'swift-dzire-cab-airport-pickup',
            'airportDropSlug'   => 'swift-dzire-cab-airport-drop',
            'sightseeingSlug'   => 'varanasi-local-fullday-sightseeing',
            'routes' => [
                ['slug' => 'swift-dzire-cab-for-varanasi-to-vindhyachal', 'label' => 'Varanasi to Vindhyachal', 'meta' => '~65 km · 2–3 hrs', 'blurb' => 'Vindhyachal cab for Vindhyavasini Temple, usually same-day'],
                ['slug' => 'swift-dzire-cab-for-varanasi-to-prayagraj',   'label' => 'Varanasi to Prayagraj',   'meta' => '~130 km · 3 hrs', 'blurb' => 'Prayagraj cab for Sangam and onward connections'],
                ['slug' => 'swift-dzire-cab-for-varanasi-to-ayodhya',     'label' => 'Varanasi to Ayodhya',     'meta' => '~150 km · 3 hrs', 'blurb' => 'Ayodhya cab for Ram Mandir and temple visits'],
                ['slug' => 'swift-dzire-cab-for-varanasi-to-bodhgaya',    'label' => 'Varanasi to Bodhgaya',    'meta' => '~250 km · 6–7 hrs', 'blurb' => 'Bodhgaya cab for the Mahabodhi Temple pilgrimage route'],
                ['slug' => 'swift-dzire-cab-for-varanasi-to-lucknow',     'label' => 'Varanasi to Lucknow',     'meta' => '~320 km · 6–7 hrs', 'blurb' => 'Lucknow cab for business travel or same-day return'],
            ],
        ],
    ];
    $activeCabHubKey = optional($sub_category)->slug;
    $activeCabHub = $isCabCat && $activeCabHubKey && isset($cabHubs[$activeCabHubKey]) ? $cabHubs[$activeCabHubKey] : null;
    // ── 10 context-aware FAQs ──────────────────────────────────────────
    if ($activeCabHub) {
        $vn = $activeCabHub['vehicleName'];
        $routeNames = collect($activeCabHub['routes'])->map(fn($r) => str_replace('Varanasi to ', '', $r['label']))->join(', ', ' or ');
        $faqs = [
            ['q' => "How can I book an {$vn} in Varanasi?",
             'a' => "Choose the service you need — airport transfer, local sightseeing, or an outstation route — from the listings on this page, then use the enquiry form or WhatsApp button on that page to confirm your booking."],
            ['q' => "How much does an {$vn} cab cost in Varanasi?",
             'a' => "Pricing depends on the service — airport transfer, local sightseeing, or outstation travel — and is shown on each listing above. Final fare can vary with distance, timing and group requirements; our team confirms the exact amount on WhatsApp before booking."],
            ['q' => "Can I book an {$vn} with a driver?",
             'a' => "Yes. Every {$vn} booking includes an experienced driver — self-drive is not offered."],
            ['q' => "Is {$vn} available for airport pickup in Varanasi?",
             'a' => "Yes, for pickup from Lal Bahadur Shastri International Airport, Varanasi, with drop to your hotel or any city address. See the Airport Pickup listing above."],
            ['q' => "Can I book an {$vn} for Varanasi local sightseeing?",
             'a' => "Yes, a full-day local sightseeing option is available, typically covering the Ganga ghats, the Kashi Vishwanath Temple area, Sarnath and BHU. See the Local Sightseeing listing above."],
            ['q' => "Can I book an {$vn} from Varanasi to {$routeNames}?",
             'a' => "Yes, each of these outstation routes has its own listing above with route-specific details — approximate distance, duration and what is included in the fare."],
            ['q' => "Is one-way or round-trip {$vn} booking available?",
             'a' => "Both are generally possible depending on the route — let our team know your requirement when booking and we will confirm availability and pricing for your dates."],
            ['q' => "How many passengers can travel in an {$vn}?",
             'a' => "The {$vn} seats up to {$activeCabHub['capacity']} passengers comfortably, with space for luggage."],
            ['q' => "What is included in the {$vn} fare?",
             'a' => "Inclusions vary slightly by route — typically parking charges, toll tax and driver allowance are covered. Each route listing above states what is included for that specific service."],
            ['q' => "How do I confirm my {$vn} booking?",
             'a' => "Submit the enquiry form on the relevant service page, or message us directly on WhatsApp at +91-7080109917, 7080109918 or 7080109919. Our team confirms availability and the final fare before your trip."],
        ];
    } elseif ($isHotelCat && $sub_category) {
        $loc = $subLabel; // e.g. "Near Kashi Vishwanath Temple"
        $faqs = [
            ['q' => 'What are the best hotels ' . $loc . ' in Varanasi?',
             'a' => 'Visit Kashi lists hand-picked, locally verified hotels ' . $loc . '. Each property is vetted for cleanliness, safety, and value. Browse the listings above, read the descriptions, and book the one that fits your budget and requirements.'],
            ['q' => 'How close are these hotels to Kashi Vishwanath Temple?',
             'a' => 'Hotels ' . $loc . ' are typically within a 5–15 minute walk of the Kashi Vishwanath Jyotirlinga. The narrow lanes (galis) of the old city are pedestrian-only, so proximity on the map translates directly to convenience for pilgrims and visitors.'],
            ['q' => 'What is the average cost of a hotel room ' . $loc . '?',
             'a' => 'Prices range from ₹800–₹2,500/night for budget options to ₹3,000–₹8,000/night for mid-range and heritage boutique properties. Check individual listings for current pricing — rates vary by season, room type, and availability.'],
            ['q' => 'Can I walk to the Ganga ghats from hotels ' . $loc . '?',
             'a' => 'Yes. The Kashi Vishwanath corridor connects directly to Manikarnika and Dashashwamedh ghats, both within 5–10 minutes on foot. Staying ' . $loc . ' means you can attend the Ganga Aarti and sunrise boat rides without needing transport.'],
            ['q' => 'Is it noisy staying ' . $loc . '?',
             'a' => 'The area around Kashi Vishwanath Temple is lively — temple bells, morning aarti, and street activity are part of the experience. Most hotels have sound-insulated rooms. If you\'re a light sleeper, ask for a room facing away from the main lane when booking.'],
            ['q' => 'Do hotels ' . $loc . ' offer early check-in or late check-out?',
             'a' => 'Many properties offer early check-in and late check-out subject to availability, sometimes at a small extra charge. We recommend contacting the hotel directly after booking through Visit Kashi to arrange flexible timings.'],
            ['q' => 'Are these hotels suitable for families and senior pilgrims?',
             'a' => 'Yes. Several hotels ' . $loc . ' are family-friendly with ground-floor rooms, elevator access, and vegetarian dining options — ideal for pilgrimage trips. Filter by your requirements and read the amenities section on each listing.'],
            ['q' => 'What is the best time to visit Varanasi and book hotels?',
             'a' => 'October to March is the peak season — weather is pleasant and festivals like Dev Deepawali and Maha Shivratri draw large crowds. Book at least 2–4 weeks in advance during festival periods. Summer (April–June) is hot but less crowded; monsoon (July–September) brings a mystical atmosphere with fewer tourists.'],
            ['q' => 'Do hotels ' . $loc . ' include breakfast?',
             'a' => 'Some properties include breakfast in the room rate; others offer it as an add-on. Each listing on Visit Kashi mentions included amenities. The area has excellent local eateries serving authentic Banarasi breakfast — kachori sabzi, malaiyo, and lassi — just steps away from most hotels.'],
            ['q' => 'How do I book a hotel ' . $loc . ' through Visit Kashi?',
             'a' => 'Browse the listings on this page, click on a hotel to see full details, photos, and pricing. Then use the enquiry form or WhatsApp button on the hotel page to confirm your booking. Our team responds within a few hours and handles everything — no hidden charges, no booking fees.'],
        ];
    } elseif ($isHotelCat) {
        $faqs = [
            ['q' => 'What types of hotels are available in Varanasi?',
             'a' => 'Varanasi offers a wide range — from budget guesthouses on the ghats and heritage havelis in the old city to modern business hotels and luxury riverside resorts. Visit Kashi lists verified options across all budgets.'],
            ['q' => 'What is the average hotel cost in Varanasi?',
             'a' => 'Budget hotels start from ₹700–₹1,500/night, mid-range properties run ₹2,000–₹5,000/night, and premium heritage hotels are ₹5,000–₹15,000/night. Prices peak during festival seasons like Dev Deepawali and Maha Shivratri.'],
            ['q' => 'Which area is best to stay in Varanasi?',
             'a' => 'For the authentic experience, staying near the ghats (Dashashwamedh, Assi, or Manikarnika) puts you at the heart of Varanasi. For quieter stays, Cantonment or Nadesar areas offer modern comforts with easy access to landmarks.'],
            ['q' => 'How far are ghat-side hotels from the Varanasi railway station?',
             'a' => 'Varanasi Junction (Varanasi Cantt.) is 5–8 km from the main ghat area. Auto-rickshaws and e-rickshaws are readily available; the journey takes 20–35 minutes depending on traffic.'],
            ['q' => 'Do hotels in Varanasi serve non-vegetarian food?',
             'a' => 'Many hotels near the ghats and temple areas are strictly vegetarian due to the religious environment. Non-vegetarian options are available in hotels in Cantonment, Lanka, and Sigra areas. Check the listing details before booking.'],
            ['q' => 'Can I get a hotel with a Ganga view in Varanasi?',
             'a' => 'Yes — several heritage properties and boutique hotels offer stunning Ganga-facing rooms or rooftop terraces overlooking the river. These are in high demand, so booking well in advance is recommended, especially for festival dates.'],
            ['q' => 'Are hotels near the ghats safe for solo travellers and women?',
             'a' => 'Yes. The ghat area has a strong community presence, 24-hour foot traffic, and is generally safe. Choose hotels with reception staff available around the clock. Visit Kashi only lists properties that meet basic safety standards.'],
            ['q' => 'What is the best time to visit Varanasi?',
             'a' => 'October to March offers the most comfortable weather. November brings Dev Deepawali — Varanasi\'s most spectacular festival. February–March is Maha Shivratri. Avoid peak summer (April–June) if you\'re heat-sensitive.'],
            ['q' => 'Do Varanasi hotels provide airport/railway station transfers?',
             'a' => 'Many hotels arrange airport or railway station pick-ups for a fee. You can also book a cab through Visit Kashi for a smooth, pre-arranged arrival. Mention your requirements in the booking enquiry.'],
            ['q' => 'How do I book a hotel in Varanasi through Visit Kashi?',
             'a' => 'Click on any listing to view photos, amenities, and pricing. Use the enquiry form or WhatsApp button to confirm. Our local team handles the booking — no hidden fees, no booking commission passed to you.'],
        ];
    } elseif ($isCabCat) {
        $faqs = [
            ['q' => 'What cab services are available in Varanasi?', 'a' => 'Visit Kashi offers airport transfers, railway station pickups/drops, outstation cabs (Sarnath, Prayagraj, Ayodhya, Lucknow), and local city rides — all with verified, experienced drivers.'],
            ['q' => 'How much does an airport cab from Varanasi airport cost?', 'a' => 'Varanasi (Lal Bahadur Shastri) airport to the city/ghats typically costs ₹600–₹900 for a sedan and ₹900–₹1,400 for an SUV, depending on distance and vehicle type. Check current rates on each listing.'],
            ['q' => 'Are the cab drivers verified by Visit Kashi?', 'a' => 'Yes. All cab operators and drivers listed on Visit Kashi are personally vetted, hold valid commercial vehicle licenses, and are background-checked for safety.'],
            ['q' => 'Can I book a cab for an outstation trip from Varanasi?', 'a' => 'Absolutely. Popular outstation routes include Varanasi–Prayagraj (120 km), Varanasi–Ayodhya (200 km), Varanasi–Sarnath (10 km), and Varanasi–Lucknow (310 km). Both one-way and round-trip options are available.'],
            ['q' => 'Is it safe to travel by cab in Varanasi at night?', 'a' => 'Yes. Visit Kashi\'s verified cab operators are available 24/7. For late-night rides, pre-booking is recommended so the driver meets you on time at your location.'],
            ['q' => 'Do cabs in Varanasi accept UPI or card payments?', 'a' => 'Most verified operators accept UPI (GPay, PhonePe, Paytm). Cash is always accepted. Card acceptance depends on the operator — confirm at the time of booking.'],
            ['q' => 'How far is Sarnath from Varanasi city?', 'a' => 'Sarnath — the sacred Buddhist site where Buddha gave his first sermon — is approximately 10–12 km from Varanasi Cantt. A round trip by cab takes 2–3 hours including sightseeing time.'],
            ['q' => 'Can I hire a cab for a full-day city tour of Varanasi?', 'a' => 'Yes. Full-day city tour packages with a cab and local guide are available. These typically cover major ghats, Kashi Vishwanath Temple, Sarnath, Ramnagar Fort, and local markets.'],
            ['q' => 'What is the distance from Varanasi to Prayagraj by cab?', 'a' => 'Varanasi to Prayagraj (Allahabad) is approximately 120–130 km and takes 2.5–3.5 hours by road. Visit Kashi offers one-way and return cab bookings for this route.'],
            ['q' => 'How do I book a cab in Varanasi through Visit Kashi?', 'a' => 'Browse the cab listings, select your preferred vehicle type, and use the enquiry or WhatsApp button. Our team confirms the booking with the driver details shared in advance — no surprise charges.'],
        ];
    } else {
        $faqs = [
            ['q' => 'What ' . strtolower($catLabel ?: 'experiences') . ' are available in Varanasi?',
             'a' => 'Visit Kashi offers a curated selection of ' . strtolower($catLabel ?: 'experiences') . ' in Varanasi, all locally verified and offered at transparent prices. Browse the listings above for detailed descriptions and pricing.'],
            ['q' => 'How do I book through Visit Kashi?',
             'a' => 'Click any listing, view the full details and photos, then use the enquiry form or WhatsApp button to confirm your booking. Our team responds within a few hours.'],
            ['q' => 'Are all listings on Visit Kashi verified?',
             'a' => 'Yes. Every listing is personally vetted by our local Varanasi team. We check quality, safety standards, and pricing transparency before listing any service.'],
            ['q' => 'What is the best time to visit Varanasi?',
             'a' => 'October to March is ideal — pleasant weather and major festivals. Dev Deepawali (November) and Maha Shivratri (Feb–March) are the most spectacular times to experience the city.'],
            ['q' => 'Is Varanasi safe for tourists?',
             'a' => 'Yes, Varanasi is generally safe for tourists. The main ghat areas are busy with locals, pilgrims, and tourists around the clock. As with any travel destination, stay alert and keep your belongings secure.'],
            ['q' => 'How far is Varanasi from major Indian cities?',
             'a' => 'Varanasi is well connected — 3 hours from Lucknow by train, 8–9 hours from Delhi by overnight train, and 13–14 hours from Mumbai. Varanasi airport (LKO via connection, or BHO direct) has daily flights from Delhi, Mumbai, Kolkata, and Bengaluru.'],
            ['q' => 'Do I need to pre-book, or can I walk in?',
             'a' => 'Pre-booking is strongly recommended, especially during festival seasons (Oct–March). During Dev Deepawali and Maha Shivratri, availability fills up weeks in advance. Off-season has more walk-in availability.'],
            ['q' => 'What language is spoken in Varanasi?',
             'a' => 'Hindi and Bhojpuri are the primary languages. English is understood in tourist areas, hotels, and by most guides. Our Visit Kashi team is available to assist in English, Hindi, and Bhojpuri.'],
            ['q' => 'What is the currency used in Varanasi?',
             'a' => 'Indian Rupee (INR). ATMs are available throughout the city. UPI payments (GPay, PhonePe, Paytm) are widely accepted. Carry some cash for smaller vendors, boat rides, and temple offerings.'],
            ['q' => 'Does Visit Kashi charge any booking fees?',
             'a' => 'No. Visit Kashi does not charge any booking commission or convenience fees. The price you see on the listing is what you pay — completely transparent, no hidden charges.'],
        ];
    }
@endphp

{{-- JSON-LD: BreadcrumbList + ItemList + FAQPage ──────────────────── --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
        @if($sub_category)
        { "@type": "ListItem", "position": 2, "name": "{{ addslashes($catLabel) }}", "item": "{{ route('product.list', $catSlugMain) }}" },
        { "@type": "ListItem", "position": 3, "name": "{{ addslashes($subLabel) }}", "item": "{{ $canonicalUrl }}" }
        @else
        { "@type": "ListItem", "position": 2, "name": "{{ addslashes($catLabel) }}", "item": "{{ $canonicalUrl }}" }
        @endif
      ]
    },
    {
      "@type": "ItemList",
      "name": "{{ addslashes($listingTitle) }}",
      "description": "{{ addslashes($listingDesc) }}",
      "numberOfItems": {{ $totalCount }},
      "itemListElement": [
        @foreach($products->take(20) as $i => $p)
        @php
          $pSubSlug = optional($p->subCategory)->slug;
          $pUrl = $pSubSlug
            ? route('product.detail', [$catSlugMain, $pSubSlug, $p->slug ?? '#'])
            : route('product.detail', [$catSlugMain, 'varanasi', $p->slug ?? '#']);
        @endphp
        { "@type": "ListItem", "position": {{ $i + 1 }}, "url": "{{ $pUrl }}", "name": "{{ addslashes($p->name ?? '') }}" }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }{{ $activeCabHub ? '' : ',' }}
    {{-- FAQPage structured data intentionally omitted for vehicle hub pages
         (Innova Crysta, Ertiga, Swift Dzire, ...) — see $activeCabHub. --}}
    @unless($activeCabHub)
    {
      "@type": "FAQPage",
      "mainEntity": [
        @foreach($faqs as $fi => $faq)
        {
          "@type": "Question",
          "name": "{{ addslashes($faq['q']) }}",
          "acceptedAnswer": { "@type": "Answer", "text": "{{ addslashes($faq['a']) }}" }
        }{{ $fi < count($faqs) - 1 ? ',' : '' }}
        @endforeach
      ]
    }
    @endunless
  ]
}
</script>

<link rel="canonical" href="{{ $canonicalUrl }}">
<title>{{ $listingTitleTag }} | Visit Kashi</title>
<meta name="description" content="{{ Str::limit($listingDesc, 160) }}">
<meta name="keywords" content="{{ $listingKw }}">
<meta name="robots" content="index, follow">
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ $canonicalUrl }}" />
<meta property="og:title" content="{{ $listingTitleTag }} | Visit Kashi">
<meta property="og:description" content="{{ Str::limit($listingDesc, 200) }}">
<meta property="og:image" content="{{ $listingImg }}">
<meta property="og:site_name" content="Visit Kashi">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $listingTitleTag }}">
<meta name="twitter:description" content="{{ Str::limit($listingDesc, 200) }}">
<meta name="twitter:image" content="{{ $listingImg }}">
@endsection

@push('styles')
@if($activeCabHub)
<link rel="stylesheet" href="{{ asset('frontend/css/topic-cluster.min.css') }}?v={{ filemtime(public_path('frontend/css/topic-cluster.min.css')) }}">
@endif
<style>
/* ══ Listing Page ══════════════════════════════════════════════════ */
.vkl-page { background:#f7f8fa; min-height:60vh; padding-bottom:72px; font-family:'Plus Jakarta Sans',sans-serif; }
.vkl-tabs-spacer { margin-bottom:28px; }

/* ── Hero ─────────────────────────────────────────────────────── */
.vkl-hero {
    background: linear-gradient(135deg,#1A2B4C 0%,#0d1420 100%);
    padding: 36px 0 32px;
    position: relative;
    overflow: hidden;
}
.vkl-hero::after {
    content:'';
    position:absolute;
    inset:0;
    background: radial-gradient(ellipse at 70% 50%, rgba(217,79,43,.18) 0%, transparent 65%);
    pointer-events:none;
}
.vkl-hero__inner { position:relative; z-index:1; }
.vkl-breadcrumb {
    display:flex; align-items:center; gap:6px;
    font-size:13px; color:rgba(255,255,255,.55);
    margin-bottom:14px; flex-wrap:wrap;
}
.vkl-breadcrumb a { color:rgba(255,255,255,.65); text-decoration:none; }
.vkl-breadcrumb a:hover { color:#fff; }
.vkl-breadcrumb .sep { color:rgba(255,255,255,.3); }
.vkl-hero__title {
    font-size:clamp(24px,3.2vw,38px);
    font-weight:800;
    color:#fff;
    letter-spacing:-.025em;
    margin:0 0 10px;
    line-height:1.15;
}
.vkl-hero__desc {
    font-size:15px;
    color:rgba(255,255,255,.68);
    margin:0 0 18px;
    max-width:620px;
    line-height:1.6;
}
.vkl-hero__meta {
    display:flex; align-items:center; gap:20px; flex-wrap:wrap;
    font-size:13px;
}
.vkl-hero__meta-item {
    display:flex; align-items:center; gap:6px;
    color:rgba(255,255,255,.72);
    background:rgba(255,255,255,.1);
    border:1px solid rgba(255,255,255,.15);
    border-radius:50px;
    padding:5px 14px;
}
.vkl-hero__meta-item i { color:#F5A623; }

/* ── Body layout ─────────────────────────────────────────────── */
.vkl-body { padding:32px 0 0; }

/* ── Tabs ────────────────────────────────────────────────────── */
.vkl-tabs {
    display:flex; align-items:center; gap:8px; flex-wrap:wrap;
    margin-bottom:28px;
    padding-bottom:20px;
    border-bottom:1.5px solid #e8eaf0;
}
.vkl-tab {
    display:inline-flex; align-items:center;
    padding:7px 18px;
    border-radius:50px;
    font-size:13px; font-weight:600;
    text-decoration:none !important;
    border:1.5px solid #dde0e8;
    color:#555;
    background:#fff;
    transition:all .2s;
    white-space:nowrap;
    box-shadow:0 1px 3px rgba(0,0,0,.05);
}
.vkl-tab:hover { background:#1A2B4C; color:#fff; border-color:#1A2B4C; }
.vkl-tab.active { background:#1A2B4C; color:#fff; border-color:#1A2B4C; }

/* ── Grid ────────────────────────────────────────────────────── */
.vkl-grid { display:grid !important; grid-template-columns:repeat(5,1fr) !important; gap:22px 14px; }
@media(max-width:1400px){ .vkl-grid{ grid-template-columns:repeat(5,1fr) !important } }
@media(max-width:1023px){ .vkl-grid{ grid-template-columns:repeat(3,1fr) !important } }
@media(max-width:768px) { .vkl-grid{ grid-template-columns:repeat(2,1fr) !important; gap:16px 12px } }
@media(max-width:400px) { .vkl-grid{ grid-template-columns:1fr 1fr !important; gap:12px } }

/* ── Card ────────────────────────────────────────────────────── */
.vkl-card {
    display:block;
    text-decoration:none !important;
    color:inherit;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 1px 4px rgba(0,0,0,.07);
    transition:box-shadow .25s, transform .25s;
}
.vkl-card:hover {
    box-shadow:0 8px 28px rgba(0,0,0,.13);
    transform:translateY(-3px);
}
.vkl-card__img-wrap {
    position:relative;
    width:100%;
    aspect-ratio:4/3;
    background:#f0f0f0;
    overflow:hidden;
}
.vkl-card__img-wrap img {
    width:100%; height:100%;
    object-fit:cover; display:block;
    transition:transform .45s ease;
}
.vkl-card:hover .vkl-card__img-wrap img { transform:scale(1.06); }
.vkl-card__badge {
    position:absolute; top:10px; left:10px;
    background:#fff; color:#222;
    font-size:11.5px; font-weight:700;
    padding:4px 10px; border-radius:50px;
    box-shadow:0 1px 6px rgba(0,0,0,.14);
}
.vkl-card__featured {
    position:absolute; top:10px; right:10px;
    background:#D94F2B; color:#fff;
    font-size:10.5px; font-weight:700;
    padding:3px 9px; border-radius:50px;
    letter-spacing:.04em; text-transform:uppercase;
}
.vkl-card__body { padding:14px 16px 16px; }
.vkl-card__sub {
    font-size:11px; color:#999;
    text-transform:uppercase; letter-spacing:.07em; margin-bottom:4px;
}
.vkl-card__name {
    font-size:14.5px; font-weight:700; color:#1a1a1a;
    line-height:1.35; margin-bottom:6px;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.vkl-card__addr {
    font-size:12px; color:#999; margin-bottom:8px;
    display:flex; align-items:center; gap:4px;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.vkl-card__footer {
    display:flex; align-items:center; justify-content:space-between;
    border-top:1px solid #f2f2f2; padding-top:10px; margin-top:6px;
}
.vkl-card__price { font-size:14px; color:#1a1a1a; }
.vkl-card__price strong { font-weight:700; }
.vkl-card__price .from { color:#888; font-weight:400; font-size:12px; }
.vkl-card__price .per  { color:#888; font-weight:400; font-size:12px; }

/* ── Star rating ─────────────────────────────────────────────── */
.vkl-card__stars {
    display:flex; align-items:center; gap:4px;
    margin:5px 0 6px;
}
.vkl-stars {
    display:inline-flex; gap:1px;
}
.vkl-stars svg { display:block; }
.vkl-star-val {
    font-size:12px; font-weight:700; color:#1a1a1a; line-height:1;
}
.vkl-star-count {
    font-size:11px; color:#888; font-weight:400;
}

/* ── Empty state ─────────────────────────────────────────────── */
.vkl-empty {
    grid-column:1/-1;
    text-align:center;
    padding:72px 20px;
    color:#999;
    background:#fff;
    border-radius:16px;
}
.vkl-empty__icon { font-size:2.8rem; margin-bottom:12px; }
.vkl-empty__text { font-size:1rem; font-weight:700; color:#555; margin-bottom:6px; }
.vkl-empty__sub { font-size:14px; }

/* ── SEO content block ───────────────────────────────────────── */
.vkl-seo {
    margin-top:52px;
    padding:36px 40px;
    background:#fff;
    border-radius:18px;
    box-shadow:0 1px 6px rgba(0,0,0,.06);
}
.vkl-seo__title {
    font-size:18px; font-weight:800; color:#1A2B4C;
    margin:0 0 14px; letter-spacing:-.01em;
    padding-bottom:12px;
    border-bottom:2px solid #f0f2f5;
}
.vkl-seo__body { font-size:14.5px; color:#555; line-height:1.8; }
.vkl-seo__body p { margin:0 0 10px; }
.vkl-seo__body p:last-child { margin:0; }

/* ── FAQ section ─────────────────────────────────────────────── */
.vkl-faq { margin-top:44px; }
.vkl-faq__head {
    display:flex; align-items:center; justify-content:center; gap:12px;
    margin-bottom:22px;
    text-align:center;
}
.vkl-faq__head h2 {
    font-size:22px; font-weight:800; color:#1A2B4C;
    margin:0; letter-spacing:-.02em;
}
.vkl-faq__head-badge {
    background:#fff5f2; color:#D94F2B;
    font-size:12px; font-weight:700;
    padding:4px 12px; border-radius:50px;
    border:1px solid #fdd5c8;
}
.vkl-faq-item {
    background:#fff;
    border-radius:14px;
    border:1.5px solid #eaecf0;
    margin-bottom:10px;
    overflow:hidden;
    transition:border-color .2s, box-shadow .2s;
}
.vkl-faq-item.open {
    border-color:#D94F2B;
    box-shadow:0 2px 16px rgba(217,79,43,.09);
}
.vkl-faq-q {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:18px 22px;
    cursor:pointer;
    user-select:none;
    -webkit-tap-highlight-color:transparent;
}
.vkl-faq-q__text {
    font-size:15px; font-weight:600; color:#1a1a1a;
    line-height:1.4;
    flex:1;
}
.vkl-faq-item.open .vkl-faq-q__text { color:#D94F2B; }
.vkl-faq-q__icon {
    width:28px; height:28px; flex-shrink:0;
    border-radius:50%;
    background:#f5f6f8;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; color:#555;
    transition:background .2s, transform .3s;
}
.vkl-faq-item.open .vkl-faq-q__icon {
    background:#D94F2B; color:#fff;
    transform:rotate(45deg);
}
.vkl-faq-a {
    max-height:0;
    overflow:hidden;
    transition:max-height .35s ease, padding .25s;
    padding:0 22px;
}
.vkl-faq-item.open .vkl-faq-a {
    max-height:300px;
    padding:0 22px 18px;
}
.vkl-faq-a p {
    font-size:14.5px; color:#555; line-height:1.75; margin:0;
    border-top:1px solid #f0f2f5;
    padding-top:14px;
}

/* ── Responsive ──────────────────────────────────────────────── */

/* Tablet: 768px */
@media(max-width:768px) {
    /* Hero */
    .vkl-hero                { padding:24px 0 20px; }
    .vkl-hero__inner         { padding:0 4px; }
    .vkl-breadcrumb          { font-size:12px; gap:4px; margin-bottom:10px; }
    .vkl-hero__title         { font-size:20px; line-height:1.2; margin-bottom:8px; }
    .vkl-hero__desc          { font-size:13px; margin-bottom:14px; line-height:1.55; }
    .vkl-hero__meta          { gap:8px; }
    .vkl-hero__meta-item     { font-size:12px; padding:4px 11px; gap:5px; }

    /* Body */
    .vkl-body                { padding:20px 0 0; }
    .vkl-tabs                { gap:6px; padding-bottom:16px; margin-bottom:20px; }
    .vkl-tab                 { font-size:12px; padding:6px 13px; }

    /* Grid */
    .vkl-grid                { grid-template-columns:repeat(2,1fr); gap:14px 10px; }

    /* Cards */
    .vkl-card__body          { padding:10px 12px 12px; }
    .vkl-card__name          { font-size:13.5px; }
    .vkl-card__sub           { font-size:10.5px; }
    .vkl-card__addr          { font-size:11.5px; }
    .vkl-card__price         { font-size:13px; }

    /* SEO block */
    .vkl-seo                 { padding:20px 18px; margin-top:36px; }
    .vkl-seo__title          { font-size:16px; }
    .vkl-seo__body           { font-size:14px; }

    /* FAQ */
    .vkl-faq                 { margin-top:32px; }
    .vkl-faq__head h2        { font-size:18px; }
    .vkl-faq-q               { padding:14px 16px; }
    .vkl-faq-q__text         { font-size:13.5px; }
    .vkl-faq-a               { padding:0 16px; }
    .vkl-faq-item.open .vkl-faq-a { padding:0 16px 14px; }
    .vkl-faq-a p             { font-size:13.5px; }
}

/* Small mobile: 480px */
@media(max-width:480px) {
    /* Hero */
    .vkl-hero                { padding:18px 0 16px; }
    .vkl-hero__title         { font-size:18px; }
    .vkl-hero__desc          { display:none; }
    .vkl-hero__meta          { gap:6px; }
    .vkl-hero__meta-item     { font-size:11.5px; padding:4px 10px; }

    /* Tabs — horizontal scroll instead of wrapping */
    .vkl-tabs                { flex-wrap:nowrap; overflow-x:auto; -webkit-overflow-scrolling:touch;
                                padding-bottom:12px; scrollbar-width:none; }
    .vkl-tabs::-webkit-scrollbar { display:none; }

    /* Grid */
    .vkl-grid                { grid-template-columns:repeat(2,1fr); gap:10px 8px; }
    .vkl-card__body          { padding:8px 10px 10px; }
    .vkl-card__name          { font-size:12.5px; -webkit-line-clamp:2; }
    .vkl-card__footer        { flex-direction:column; align-items:flex-start; gap:4px; }

    /* SEO */
    .vkl-seo                 { padding:16px 14px; border-radius:12px; }

    /* FAQ */
    .vkl-faq-q               { padding:12px 14px; }
    .vkl-faq-q__text         { font-size:13px; }
    .vkl-faq-q__icon         { width:24px; height:24px; font-size:13px; }
    .vkl-faq-a               { padding:0 14px; }
    .vkl-faq-item.open .vkl-faq-a { padding:0 14px 12px; }
}
</style>
@endpush

@section('content')

{{-- ── Hero ─────────────────────────────────────────────────────────── --}}
<div class="vkl-hero">
    <div class="container vkl-hero__inner">
        <nav class="vkl-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span class="sep">›</span>
            @if($sub_category)
                <a href="{{ route('product.list', $catSlugMain) }}">{{ $catLabel }}</a>
                <span class="sep">›</span>
                <span>{{ $subLabel }}</span>
            @else
                <span>{{ $catLabel }}</span>
            @endif
        </nav>

        <h1 class="vkl-hero__title">{{ $activeCabHub ? $activeCabHub['vehicleName'].' Cab Booking in Varanasi' : $seoTitle.' in Varanasi' }}</h1>

        <p class="vkl-hero__desc">{{ $activeCabHub ? 'Comfortable '.$activeCabHub['vehicleName'].' cab service for airport transfers, local sightseeing and outstation trips from Varanasi to Ayodhya, Prayagraj, Vindhyachal, Bodhgaya and Lucknow.' : Str::limit($listingDesc, 160) }}</p>

        <div class="vkl-hero__meta">
            <span class="vkl-hero__meta-item">
                <i class="fa fa-list" aria-hidden="true"></i>
                <strong>{{ $totalCount }}</strong>&nbsp;{{ Str::plural('listing', $totalCount) }}
            </span>
            <span class="vkl-hero__meta-item">
                <i class="fa fa-star" aria-hidden="true"></i>
                Verified by Visit Kashi
            </span>
            <span class="vkl-hero__meta-item">
                <i class="fa fa-lock" aria-hidden="true"></i>
                No hidden charges
            </span>
        </div>
    </div>
</div>

{{-- ── Body ─────────────────────────────────────────────────────────── --}}
<div class="vkl-page">
  <div class="container vkl-body">

    {{-- Subcategory Filter Tabs --}}
    @if($subCategories->count() > 0)
    <div class="vkl-tabs" role="tablist" aria-label="Filter by location">
        <a href="{{ route('product.list', $catSlugMain) }}"
           class="vkl-tab {{ !$sub_category ? 'active' : '' }}">
            All {{ $catLabel }}
        </a>
        @foreach($subCategories as $sc)
        <a href="{{ route('product.sub.list', [$catSlugMain, $sc->slug]) }}"
           class="vkl-tab {{ optional($sub_category)->id == $sc->id ? 'active' : '' }}">
            {{ $sc->name }}
        </a>
        @endforeach
    </div>
    @else
    <div class="vkl-tabs-spacer"></div>
    @endif

    {{-- Product Grid --}}
    <div class="vkl-grid">
      @forelse ($products as $idx => $product)
      @php
        $pCatSlug  = optional($product->category)->slug ?? 'default';
        $pSubSlug  = optional($product->subCategory)->slug;
        $detailUrl = $pSubSlug
            ? route('product.detail', [$pCatSlug, $pSubSlug, $product->slug ?? '#'])
            : route('product.detail', [$pCatSlug, 'varanasi', $product->slug ?? '#']);

        $imgFile = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null;
        $imgSrc  = ($imgFile && file_exists(public_path('backend/admin/product_images/'.$imgFile)))
                    ? asset('backend/admin/product_images/'.$imgFile)
                    : asset('backend/assets/images/placeholder.jpg');

        $isHotel  = in_array($pCatSlug, ['hotels','homestay']);
        $loadAttr = $idx < 4 ? 'eager' : 'lazy';
      @endphp

      <a href="{{ $detailUrl }}" class="vkl-card" aria-label="{{ $product->name ?? 'View listing' }}">

        <div class="vkl-card__img-wrap">
            <img src="{{ $imgSrc }}"
                 alt="{{ $product->name ?? 'Product Image' }}"
                 width="400" height="300"
                 loading="{{ $loadAttr }}"
                 decoding="{{ $loadAttr === 'eager' ? 'sync' : 'async' }}"
                 onerror="this.src='{{ asset('backend/assets/images/placeholder.jpg') }}'">


            @if($product->on_home == '1')
            <div class="vkl-card__featured">Featured</div>
            @endif
        </div>

        <div class="vkl-card__body">
            <div class="vkl-card__sub">{{ optional($product->subCategory)->name ?? $catLabel }}</div>
            <div class="vkl-card__name">{{ $product->name ?? 'Untitled' }}</div>

            {{-- Star Rating --}}
            <div class="vkl-card__stars">
                <div class="vkl-stars" aria-hidden="true">
                    @for($s = 1; $s <= 5; $s++)
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="{{ $s <= 4 ? '#F5A623' : 'none' }}" stroke="#F5A623" stroke-width="1.5">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                </div>
                <span class="vkl-star-val">{{ $isCabCat ? '4.9' : '4.8' }}</span>
                <span class="vkl-star-count">{{ $isCabCat ? '(600+)' : '(50+)' }}</span>
            </div>

            @if($product->address)
            <div class="vkl-card__addr">
                <svg width="10" height="13" viewBox="0 0 12 16" fill="none" aria-hidden="true"><path d="M6 0C3.24 0 1 2.24 1 5c0 3.75 5 11 5 11s5-7.25 5-11c0-2.76-2.24-5-5-5zm0 6.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" fill="#ccc"/></svg>
                {{ Str::limit($product->address, 42) }}
            </div>
            @endif

            <div class="vkl-card__footer">
                @if(optional($product->category)->show_price == '1')
                <div class="vkl-card__price">
                    @if(isset($product->discounted_price) && $product->discounted_price > 0)
                        <span class="from">from </span>
                        <strong>₹{{ number_format($product->discounted_price) }}</strong>
                        @if($isHotel)<span class="per">/Night</span>@endif
                    @else
                        <span class="from">Price on request</span>
                    @endif
                </div>
                @else
                <span></span>
                @endif
            </div>
        </div>

      </a>
      @empty
      <div class="vkl-empty">
          <div class="vkl-empty__icon">🗺️</div>
          <div class="vkl-empty__text">No listings found yet</div>
          <div class="vkl-empty__sub">Check back soon — we're adding more Varanasi experiences.</div>
      </div>
      @endforelse
    </div>

    {{-- ── Innova Crysta topical content — scoped to this hub only ──── --}}
    @if($activeCabHub)
    <x-cab-outstation-hub :hub="$activeCabHub" :hub-slug="$activeCabHubKey" :products="$products" />
    @endif

    {{-- ── FAQ Section ───────────────────────────────────────────────── --}}
    <section class="vkl-faq" aria-label="Frequently Asked Questions">
        <div class="vkl-faq__head">
            <h2>Frequently Asked Questions</h2>
            <span class="vkl-faq__head-badge">{{ count($faqs) }} Questions</span>
        </div>

        @foreach($faqs as $fi => $faq)
        <div class="vkl-faq-item" @unless($activeCabHub) itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" @endunless>
            <div class="vkl-faq-q" role="button" tabindex="0"
                 aria-expanded="false" aria-controls="faq-a-{{ $fi }}"
                 onclick="vklToggleFaq(this)"
                 onkeydown="if(event.key==='Enter'||event.key===' ')vklToggleFaq(this)">
                <span class="vkl-faq-q__text" @unless($activeCabHub) itemprop="name" @endunless>{{ $faq['q'] }}</span>
                <span class="vkl-faq-q__icon" aria-hidden="true">+</span>
            </div>
            <div class="vkl-faq-a" id="faq-a-{{ $fi }}"
                 @unless($activeCabHub) itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer" @endunless>
                <p @unless($activeCabHub) itemprop="text" @endunless>{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach
    </section>

  </div>
</div>

@push('scripts')
<script>
function vklToggleFaq(btn) {
    var item = btn.closest('.vkl-faq-item');
    var isOpen = item.classList.contains('open');
    // Close all
    document.querySelectorAll('.vkl-faq-item.open').forEach(function(el) {
        el.classList.remove('open');
        el.querySelector('.vkl-faq-q').setAttribute('aria-expanded', 'false');
    });
    // Open clicked (unless it was already open)
    if (!isOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
    }
}
</script>
@endpush

@endsection
