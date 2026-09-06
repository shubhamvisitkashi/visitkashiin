@extends('frontend.layouts.app')

@php
    $dd_now          = now();
    $dd_devDiwaliEnd = \Carbon\Carbon::parse('2026-11-24')->endOfDay();
    $isDevDiwali     = optional($sub_category)->slug === 'dev-diwali-booking'
                       && $dd_now->lte($dd_devDiwaliEnd);
    $isMotorBoat     = optional($sub_category)->slug === 'motor-boat';
    $isBajraBoat     = optional($sub_category)->slug === 'bajra-boat';

    // Single source of truth for the Dev Diwali FAQ — used for both the
    // visible on-page FAQ section and the FAQPage JSON-LD schema, so the
    // two never drift out of sync.
    $ddFaqs = $isDevDiwali ? [
        [
            'q' => 'What is Dev Diwali in Varanasi?',
            'a' => 'Dev Diwali (Dev Deepawali) is celebrated on Kartik Purnima, fifteen days after Diwali, when the ghats of Varanasi are lit with lakhs of earthen diyas to welcome the gods to the banks of the Ganga. It is one of the most spectacular festivals in India, and a boat ride on the river is the best way to see the illuminated ghats.',
        ],
        [
            'q' => 'When is Dev Diwali in Varanasi in 2026?',
            'a' => 'Dev Diwali in Varanasi falls on 24 November 2026. Boat and cruise bookings for this fixed date are open now on VisitKashi, and seats fill up quickly as the day approaches.',
        ],
        [
            'q' => 'What is the best time for a Dev Diwali boat ride?',
            'a' => 'For Dev Diwali boat booking, the reporting time is 4:00 PM at Ravidas Ghat (we recommend arriving by 3:30 PM to complete boarding formalities), with the boat ride running from 5:00 PM to 8:00 PM, covering the evening Ganga Aarti and the peak diya illumination.',
        ],
        [
            'q' => 'How much does Dev Diwali boat booking cost in Varanasi?',
            'a' => 'Dev Diwali boat booking in Varanasi starts from ₹3,499 per person for a shared boat ride, with Bajra boats, cruise boats, private motor boats and luxury Maharaja boats available at higher price points depending on boat type, capacity and privacy. Exact pricing for each boat is shown on its booking page.',
        ],
        [
            'q' => 'Which ghats can I see from the boat?',
            'a' => 'Your Dev Diwali boat ride gives you a clear river view of the illuminated ghats of Varanasi and the evening Ganga Aarti performed on the steps — a view that is impossible to get from the crowded banks on this night.',
        ],
        [
            'q' => 'Can I book a private boat for Dev Diwali?',
            'a' => 'Yes. Along with shared and group boat rides, VisitKashi offers private boat options for Dev Diwali, including a private motor boat and a private Maharaja boat, so your group can enjoy the festival away from the crowd.',
        ],
        [
            'q' => 'Can families and senior citizens book a Dev Diwali boat?',
            'a' => 'Yes, Dev Diwali boat rides are suitable for families, couples and senior citizens. Every boat is operated by a licensed, experienced boatman and life jackets are provided for all passengers.',
        ],
        [
            'q' => 'Where is the reporting point for Dev Diwali boat booking?',
            'a' => 'The reporting point for Dev Diwali boat booking is Ravidas Ghat, Varanasi. Please arrive by 3:30 PM for the 4:00 PM reporting time so boarding can be completed before the boat departs.',
        ],
        [
            'q' => 'How can I book a Dev Diwali boat online?',
            'a' => 'You can book a Dev Diwali boat online on this page — choose a boat, fill in the enquiry form with your name, phone number and number of guests, and our team will confirm your booking by call or WhatsApp. You can also call or WhatsApp us directly at +91-7080109917 / 7080109918 / 7080109919.',
        ],
        [
            'q' => 'Is Dev Diwali boat booking refundable?',
            'a' => 'Because Dev Diwali falls on a single fixed date (24 November 2026) with very high demand, cancellation and rescheduling terms are confirmed at the time of booking. Please call or WhatsApp our team before booking to check the current cancellation policy for your chosen boat.',
        ],
        [
            'q' => 'How early should I book my Dev Diwali boat?',
            'a' => "It's best to confirm your Dev Diwali boat booking as early as possible. Seats across all boat categories fill up quickly in the weeks before 24 November, so early booking gives you the best choice of boat and price.",
        ],
    ] : [];

    // Single source of truth for the Motor Boat FAQ — used for both the
    // visible on-page FAQ section and the FAQPage JSON-LD schema.
    $mbFaqs = $isMotorBoat ? [
        [
            'q' => 'How can I book a boat in Varanasi?',
            'a' => 'Choose a boat from the options listed on this page, then submit the enquiry form with your name and phone number, or message us directly on WhatsApp. Our team confirms your Varanasi boat booking by call or WhatsApp with the reporting time and pickup ghat.',
        ],
        [
            'q' => 'What is the price of a motor boat in Varanasi?',
            'a' => 'Motor boat booking in Varanasi starts from around ₹3,499 per person for morning and evening rides. A private motor boat hire starts from around ₹3,499 for shorter routes and ₹3,999 for the full Assi Ghat to Namo Ghat route. Exact pricing for each boat is shown on its individual booking page.',
        ],
        [
            'q' => 'Can I book a private motor boat for Ganga Aarti?',
            'a' => 'Yes. VisitKashi offers a private motor boat for evening Ganga Aarti, with pickup options from Assi Ghat, Namo Ghat, Dashashwamedh Ghat and Shivala Ghat — so your group can watch the Aarti from the river without sharing the boat.',
        ],
        [
            'q' => 'Can I book a private boat for all 84 ghats of Varanasi?',
            'a' => 'Yes. The private motor boat route from Assi Ghat to Namo Ghat covers all 84 ghats of Varanasi over roughly 7 km along the riverfront, and is available for both morning and evening booking.',
        ],
        [
            'q' => 'What is the best time for a boat ride in Varanasi?',
            'a' => 'Both morning and evening boat rides are popular for different reasons — mornings for the sunrise and the quieter ghats before the day gets going, evenings for the Ganga Aarti ceremony and the illuminated riverfront.',
        ],
        [
            'q' => 'What time does the morning boat ride start?',
            'a' => 'The morning boat ride usually starts before sunrise and runs for about one to two hours, covering ghats such as Assi Ghat, Dashashwamedh Ghat, Manikarnika Ghat and Harishchandra Ghat.',
        ],
        [
            'q' => 'What time is the evening Ganga Aarti boat ride?',
            'a' => 'The Ganga Aarti takes place from 7:00 PM to 7:45 PM at Dashashwamedh Ghat. For the private motor boat, reporting time is 5:30 PM and the ride runs from 5:45 PM to 7:45 PM, so you are positioned on the river in time for the full ceremony.',
        ],
        [
            'q' => 'Which ghats can I see during the boat ride?',
            'a' => 'Depending on the route you choose, your boat ride can cover Assi Ghat, Tulsi Ghat, Shivala Ghat, Harishchandra Ghat, Kedar Ghat, Dashashwamedh Ghat, Manikarnika Ghat, Rajendra Prasad Ghat and Namo Ghat.',
        ],
        [
            'q' => 'Can I book a boat from Assi Ghat?',
            'a' => 'Yes, pickup from Assi Ghat is available for the private motor boat evening Ganga Aarti ride and other motor boat options on this page.',
        ],
        [
            'q' => 'Can I book a boat from Dashashwamedh Ghat?',
            'a' => 'Yes, Dashashwamedh Ghat is one of the pickup points available for the private motor boat Ganga Aarti ride, and it is also where the boat is positioned during the Aarti ceremony.',
        ],
        [
            'q' => 'Can I book a boat from Namo Ghat?',
            'a' => 'Yes, pickup from Namo Ghat is available, and it also marks one end of the full 84-ghat private boat route from Assi Ghat.',
        ],
        [
            'q' => 'Is the motor boat private or shared?',
            'a' => 'Both options are available. You can choose a shared/group motor boat ride at a lower per-person price, or book a fully private motor boat so your group has the boat to yourselves — this is shown on each boat listing above.',
        ],
        [
            'q' => 'How many people can travel in a private motor boat?',
            'a' => 'Capacity varies by boat — a private motor boat typically seats up to around 15 people. Exact capacity for each boat is shown on its individual booking page.',
        ],
        [
            'q' => 'How long is the Varanasi boat ride?',
            'a' => 'Morning and evening boat rides typically run for one to two hours. The private motor boat for evening Ganga Aarti runs for about two hours, from 5:45 PM to 7:45 PM.',
        ],
        [
            'q' => 'How can I confirm my boat booking?',
            'a' => 'Submit the enquiry form on this page, or call or WhatsApp us directly at +91-7080109917, 7080109918 or 7080109919. Our team confirms availability, price and your reporting point.',
        ],
        [
            'q' => 'Can I book a Varanasi boat online?',
            'a' => 'Yes, you can start your booking online on this page by choosing a boat and sending an enquiry — no advance payment is needed to enquire, and our team follows up by call or WhatsApp to confirm.',
        ],
        [
            'q' => 'Is Ganga Aarti visible from the boat?',
            'a' => 'Yes. On the evening ride, the boat is positioned near Dashashwamedh Ghat during the 7:00 PM to 7:45 PM Ganga Aarti, giving you a clear river view of the ceremony.',
        ],
        [
            'q' => 'Can I book a morning private motor boat?',
            'a' => 'Yes, a private motor boat is available for the morning ride as well, covering ghats such as Assi Ghat, Dashashwamedh Ghat, Manikarnika Ghat and Harishchandra Ghat.',
        ],
        [
            'q' => 'What is the difference between morning and evening boat rides?',
            'a' => 'The morning ride is built around sunrise, calmer ghats and the daily rituals along the river. The evening ride is timed around the Ganga Aarti ceremony and the illuminated ghats after sunset.',
        ],
        [
            'q' => 'Why should I book my Varanasi boat ride with Visit Kashi?',
            'a' => 'VisitKashi is a local Varanasi travel company with 5+ years of experience booking boat rides on the Ganga, transparent per-boat pricing, WhatsApp booking confirmation and a local team on call to help you choose the right boat for your group.',
        ],
    ] : [];

    // Single source of truth for the Bajra Boat FAQ — used for both the
    // visible on-page FAQ section and the FAQPage JSON-LD schema.
    $bbFaqs = $isBajraBoat ? [
        [
            'q' => 'What is a Bajra boat?',
            'a' => 'A Bajra boat is a traditional, larger boat used on the Ganga in Varanasi — commonly booked privately for the evening Ganga Aarti or decorated for celebrations, since it comfortably seats bigger groups than a standard motor boat.',
        ],
        [
            'q' => 'Can I book a private Bajra boat for Ganga Aarti?',
            'a' => 'Yes. The private Bajra boat is positioned on the river in front of the Ganga Aarti ceremony at Dashaswamedh Ghat, so your group has the boat to yourselves rather than sharing it with other travellers.',
        ],
        [
            'q' => 'How much does a Bajra boat cost in Varanasi?',
            'a' => 'The private Bajra boat for Ganga Aarti starts from around ₹10,000, while a flower-decorated or event-decorated Bajra boat for a celebration starts from around ₹16,500. Exact pricing depends on group size and any decoration requirements.',
        ],
        [
            'q' => 'How many people can travel in a Bajra boat?',
            'a' => 'Capacity varies by boat and package — check the individual listing for the exact seating capacity of that specific Bajra boat before booking.',
        ],
        [
            'q' => 'Can I book a decorated Bajra boat for a birthday or anniversary?',
            'a' => 'Yes, flower-decorated and event-decoration Bajra boats are available for birthdays, anniversaries and similar celebrations on the Ganga.',
        ],
        [
            'q' => 'What time is the evening Ganga Aarti from a Bajra boat?',
            'a' => 'The evening Ganga Aarti at Dashashwamedh Ghat typically runs from around 7:00 PM to 7:45 PM. Reporting time and the exact schedule for your date are confirmed by our team on WhatsApp when you book.',
        ],
        [
            'q' => 'Where can I board a Bajra boat in Varanasi?',
            'a' => 'Pickup is generally available from Assi Ghat, Dashashwamedh Ghat or Namo Ghat, depending on the specific boat — select or confirm your preferred pickup point when booking.',
        ],
        [
            'q' => 'How do I book a Bajra boat online?',
            'a' => 'Browse the Bajra boat listings above, then use the enquiry form or WhatsApp button on the boat you want. Our team confirms availability, pickup point and the final price before your trip.',
        ],
    ] : [];
@endphp

{{-- ══ SEO META ══════════════════════════════════════════════════════════════ --}}
@section('meta')
@php
    $blTitle = optional($sub_category)->meta_title
        ?? (optional($sub_category)->name ? optional($sub_category)->name . ' in Varanasi' : 'Boat Rides in Varanasi');
    $blDesc  = optional($sub_category)->meta_description
        ?? 'Book private & group boat rides in Varanasi on the sacred Ganga River. Best prices on motor boats, row boats, Ganga Aarti rides & sunrise experiences. Instant confirmation.';
    $blKw    = optional($sub_category)->meta_keyword
        ?? 'boat ride varanasi, ganga aarti boat, varanasi boat booking, motor boat varanasi, sunrise boat ride, ganga river tour';
    $pageUrl = url()->current();
    $ogImage = asset('frontend/images/logo1.png');
    // Canonical always points at the production domain (never /public/),
    // for every boat subcategory — not just the two that had a hardcoded
    // override below.
    $canonicalUrl = 'https://visitkashi.in/boat/' . (optional($sub_category)->slug ?? '');
    $pageTitleTag = $blTitle . ' | Visit Kashi – Varanasi Boat Rides';

    if ($isDevDiwali) {
        $blTitle = 'Dev Diwali Varanasi Boat Booking 2026 | 24 November';
        $blDesc  = 'Book your Dev Diwali Varanasi Boat Booking for 24 November 2026 with VisitKashi. Reserve a boat or cruise, view illuminated ghats and Ganga Aarti from the river';
        $blKw    = 'dev diwali varanasi boat booking 2026, dev diwali boat booking varanasi, dev deepawali boat booking varanasi, dev diwali cruise booking varanasi, dev deepawali cruise booking 2026, varanasi dev diwali cruise booking, dev diwali boat ride varanasi, dev diwali ganga boat ride, dev diwali varanasi cruise, varanasi boat booking for dev diwali, dev diwali ganga cruise, dev diwali boat booking 2026, dev deepawali varanasi boat booking, varanasi dev diwali boat ride, dev diwali ganga aarti boat booking';
        $canonicalUrl = 'https://visitkashi.in/boat/dev-diwali-booking';
        $pageTitleTag = $blTitle;
    }

    if ($isBajraBoat) {
        $blTitle = 'Bajra Boat Booking in Varanasi';
        $blDesc  = 'Book a traditional Bajra boat in Varanasi — private for the evening Ganga Aarti, or decorated for a birthday or anniversary celebration on the Ganga. Book online today!';
        $blKw    = 'bajra boat booking varanasi, private bajra boat for ganga aarti, bajra boat ganga aarti varanasi, decorated bajra boat varanasi, bajra boat price varanasi, book bajra boat online';
        $pageTitleTag = $blTitle;
    }

    if ($isMotorBoat) {
        $blTitle = 'Varanasi Boat Booking | Private Motor Boat for Ganga Aarti | Visit Kashi';
        $blDesc  = 'Book your Varanasi boat ride online with Visit Kashi. Private motor boat for Ganga Aarti, morning & evening rides, and all 84 Ghats. Book Online today!';
        $blKw    = 'varanasi boat booking, boat booking in varanasi, motor boat booking in varanasi, private motor boat in varanasi, book motor boat for ganga aarti, book private motor boat for evening ganga aarti, book private motor boat all 84 ghats, morning boat ride in varanasi, evening boat ride in varanasi, ganga aarti boat booking varanasi, private boat ride varanasi';
        $canonicalUrl = 'https://visitkashi.in/boat/motor-boat';
        $pageTitleTag = $blTitle;
    }
@endphp

<link rel="canonical" href="{{ $canonicalUrl }}">
<title>{{ $pageTitleTag }}</title>
<meta name="description" content="{{ $blDesc }}">
<meta name="keywords" content="{{ $blKw }}">
<meta name="robots" content="index, follow">

{{-- Open Graph --}}
<meta property="og:type"        content="website">
<meta property="og:url"         content="{{ $canonicalUrl }}">
<meta property="og:title"       content="{{ $blTitle }}">
<meta property="og:description" content="{{ $blDesc }}">
<meta property="og:image"       content="{{ $ogImage }}">
<meta property="og:site_name"   content="Visit Kashi">
<meta property="og:locale"      content="en_IN">

{{-- Twitter --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $blTitle }}">
<meta name="twitter:description" content="{{ $blDesc }}">
<meta name="twitter:image"       content="{{ $ogImage }}">

{{-- JSON-LD: Breadcrumb --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Boat","item":"{{ url('/boat') }}"},
    {"@type":"ListItem","position":3,"name":"{{ optional($sub_category)->name ?? 'Boat Rides' }}","item":"{{ $pageUrl }}"}
  ]
}
</script>

{{-- JSON-LD: ItemList (listing of boat tours) --}}
@php
    // Built as a plain PHP array + json_encode (rather than string-interpolated
    // JSON) so a raw newline/control character in a product's stored description
    // can never produce invalid JSON-LD.
    $blItemList = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $blTitle,
        'description' => $blDesc,
        'url' => $pageUrl,
        'numberOfItems' => $products->count(),
        'itemListElement' => $products->values()->map(function ($p, $idx) {
            $item = [
                '@type' => 'TouristAttraction',
                'name' => $p->name,
                'description' => Str::limit(strip_tags($p->description ?? ''), 150),
                'url' => route('product.detail', [optional($p->category)->slug ?? 'boat', optional($p->subCategory)->slug ?? 'motor-boat', $p->slug]),
                'image' => !empty($p->images) ? asset('backend/admin/product_images/'.((is_array($p->images) ? $p->images : json_decode($p->images, true) ?? [])[0] ?? '')) : asset('backend/assets/images/placeholder.jpg'),
                'touristType' => 'Family, Couple, Group',
                'availableLanguage' => 'Hindi, English',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Varanasi',
                    'addressRegion' => 'Uttar Pradesh',
                    'addressCountry' => 'IN',
                ],
            ];
            if (($p->discounted_price ?? 0) > 0) {
                $item['offers'] = [
                    '@type' => 'Offer',
                    'price' => (string) $p->discounted_price,
                    'priceCurrency' => 'INR',
                    'availability' => 'https://schema.org/InStock',
                ];
            }
            return ['@type' => 'ListItem', 'position' => $idx + 1, 'item' => $item];
        })->all(),
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($blItemList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- JSON-LD: LocalBusiness --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Visit Kashi",
  "description": "Varanasi's trusted travel company for boat rides, tours and spiritual experiences on the Ganga.",
  "url": "{{ url('/') }}",
  "telephone": "{{ websiteSetupValue('contact_number') }}",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Varanasi",
    "addressRegion": "Uttar Pradesh",
    "postalCode": "221001",
    "addressCountry": "IN"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "reviewCount": "500",
    "bestRating": "5"
  }
}
</script>

@if($isDevDiwali)
{{-- JSON-LD: FAQPage — mirrors the visible FAQ section exactly --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ], $ddFaqs),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif

@if($isMotorBoat)
{{-- JSON-LD: Service — the motor boat booking service itself (distinct from the
     per-boat Offers already in the ItemList above, so this does not duplicate them) --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "Motor Boat Booking",
  "name": "Varanasi Boat Booking – Private Motor Boat for Ganga Aarti & 84 Ghats",
  "description": "{{ $blDesc }}",
  "provider": {
    "@type": "LocalBusiness",
    "name": "Visit Kashi",
    "url": "{{ url('/') }}",
    "telephone": "{{ websiteSetupValue('contact_number') }}"
  },
  "areaServed": {
    "@type": "City",
    "name": "Varanasi"
  },
  "url": "{{ $canonicalUrl }}"
}
</script>

{{-- JSON-LD: FAQPage — mirrors the visible FAQ section exactly --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ], $mbFaqs),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif

@if($isBajraBoat)
{{-- JSON-LD: Service — the Bajra boat booking service itself (distinct from
     the per-boat Offers already in the ItemList above) --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "Bajra Boat Booking",
  "name": "Bajra Boat Booking in Varanasi – Ganga Aarti & Private Boat",
  "description": "{{ $blDesc }}",
  "provider": {
    "@type": "LocalBusiness",
    "name": "Visit Kashi",
    "url": "{{ url('/') }}",
    "telephone": "{{ websiteSetupValue('contact_number') }}"
  },
  "areaServed": {
    "@type": "City",
    "name": "Varanasi"
  },
  "url": "{{ $canonicalUrl }}"
}
</script>

{{-- JSON-LD: FAQPage — mirrors the visible FAQ section exactly --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ], $bbFaqs),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
@endsection

@push('styles')
<link rel="preload" href="{{ asset('frontend/css/boat-listing.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-listing.min.css')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/boat-listing.min.css') }}?v={{ filemtime(public_path('frontend/css/boat-listing.min.css')) }}"></noscript>
<style>
/* ── Breadcrumb ── */
.bl-breadcrumb{background:#f6f8fc;border-bottom:1px solid #ebebeb;padding:10px 0;}
.bl-breadcrumb nav{display:flex;align-items:center;gap:6px;font-size:.78rem;color:#888;flex-wrap:wrap;}
.bl-breadcrumb a{color:#555;text-decoration:none;transition:color .15s;}
.bl-breadcrumb a:hover{color:#0f3460;}
.bl-breadcrumb span{color:#bbb;}

/* ── Hero improvements ── */
.bl-hero{background:linear-gradient(150deg,#050e1f 0%,#0c2a50 50%,#0f3460 100%);padding:56px 0 0;position:relative;overflow:hidden;}
.bl-hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");}
.bl-hero-intro{color:rgba(255,255,255,.72);font-size:.92rem;line-height:1.7;max-width:760px;margin:14px 0 0;}
.bl-hero-stats{display:flex;align-items:center;gap:20px;flex-wrap:wrap;margin-top:22px;}
.bl-hero-ctas{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-top:26px;}
.bl-hero-cta-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 26px;background:linear-gradient(135deg,#0f3460,#1a5276);color:#fff;border-radius:11px;font-size:.88rem;font-weight:800;text-decoration:none;letter-spacing:.02em;transition:opacity .2s,transform .2s;}
.bl-hero-cta-btn:hover{opacity:.9;transform:translateY(-1px);color:#fff;text-decoration:none;}
.bl-hero-cta-btn--wa{background:#25D366;}
.bl-hero-stat{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:30px;padding:6px 14px;}
.bl-hero-stat-val{color:#fff;font-size:.88rem;font-weight:800;}
.bl-hero-stat-lbl{color:rgba(255,255,255,.6);font-size:.72rem;font-weight:500;}

/* ── Improved card ── */
.bl-card{border-radius:16px;overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.07);transition:transform .25s,box-shadow .25s;display:flex;flex-direction:column;text-decoration:none;color:inherit;}
.bl-card:hover{transform:translateY(-5px);box-shadow:0 16px 40px rgba(0,0,0,.14);text-decoration:none;color:inherit;}
.bl-img-wrap{position:relative;aspect-ratio:4/3;overflow:hidden;background:#e0e8f0;display:block;text-decoration:none;}
.bl-img-wrap::after{content:'';position:absolute;bottom:0;left:0;right:0;height:50%;background:linear-gradient(to top,rgba(0,0,0,.55),transparent);pointer-events:none;z-index:2;}
.bl-img-slide img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s;}
.bl-card:hover .bl-img-slide img{transform:scale(1.06);}

/* Book Now button */
.bl-book-btn{display:flex;align-items:center;justify-content:center;gap:7px;margin-top:12px;padding:10px 0;background:linear-gradient(135deg,#0f3460,#1a5276);color:#fff;border-radius:10px;font-size:.82rem;font-weight:700;letter-spacing:.02em;transition:opacity .2s,transform .2s;text-decoration:none;}
.bl-book-btn:hover{opacity:.9;transform:translateY(-1px);color:#fff;text-decoration:none;}
.bl-book-btn svg{width:14px;height:14px;stroke:#fff;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;}

/* Instant badge on image */
.bl-instant-badge{position:absolute;bottom:12px;left:12px;z-index:4;background:linear-gradient(135deg,#16a085,#1abc9c);color:#fff;font-size:.62rem;font-weight:800;padding:3px 9px;border-radius:20px;letter-spacing:.03em;text-transform:uppercase;}

/* Card body — Airbnb style */
.bl-card-body{padding:10px 0 12px;display:flex;flex-direction:column;flex:1;}
.bl-title{font-size:.9rem;font-weight:700;color:#1a1a1a;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0 0 5px;}
.bl-price-rating-row{display:flex;align-items:center;justify-content:space-between;gap:6px;flex-wrap:nowrap;}
.bl-price-inline{display:flex;align-items:baseline;gap:4px;min-width:0;flex-wrap:wrap;}
.bl-price{font-size:.92rem;font-weight:800;color:#222;}
.bl-price-unit{font-size:.78rem;color:#717171;font-weight:400;}
.bl-price-unit--muted{font-style:italic;color:#aaa;}
.bl-price-call-now{font-size:.76rem;font-weight:700;color:#b45309;white-space:nowrap;}
.bl-price-old{font-size:.78rem;color:#e74c3c;text-decoration:line-through;font-weight:500;}
.bl-rating{display:flex;align-items:center;gap:3px;font-size:.78rem;font-weight:700;color:#1a1a1a;flex-shrink:0;white-space:nowrap;}
.bl-rating svg{fill:#222;}

/* Trust strip */
.bl-trust-strip{background:#f6f9fc;border-top:1px solid #e8eef4;border-bottom:1px solid #e8eef4;padding:16px 0;margin-bottom:32px;}
.bl-trust-strip-inner{display:flex;align-items:center;justify-content:center;gap:32px;flex-wrap:wrap;}
.bl-trust-item{display:flex;align-items:center;gap:8px;font-size:.8rem;color:#444;font-weight:600;}
.bl-trust-item svg{flex-shrink:0;}

/* Section headers */
.bl-section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;}
.bl-section-title{font-size:1.3rem;font-weight:800;color:#111;margin:0;letter-spacing:-.02em;}
.bl-section-sub{font-size:.83rem;color:#888;margin:4px 0 0;}

/* Extracted from inline styles (CSS refactor) */
.bl-success-notice{background:#f0fdf4;border:1.5px solid #86efac;border-radius:12px;padding:14px 20px;text-align:center;color:#166534;font-size:.93rem;font-weight:700;margin-bottom:24px;}
.bl-no-results{text-align:center;padding:48px;color:#999;font-size:.95rem;}
.bl-show-all-btn{border:none;background:none;color:#0f3460;font-weight:700;cursor:pointer;font-size:.95rem;}

/* Responsive */
@media(max-width:767px){
  .bl-trust-strip-inner{gap:16px;}
  .bl-hero-stats{gap:10px;}
  .bl-hero-stat{padding:5px 10px;}
}
</style>
@endpush

@section('content')

{{-- ── Breadcrumb (SEO + UX) ── --}}
<div class="bl-breadcrumb">
    <div class="container">
        <nav aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>›</span>
            <a href="{{ route('product.list', 'boat') }}">Boat</a>
            <span>›</span>
            <span>{{ optional($sub_category)->name ?? 'Boat Rides' }}</span>
        </nav>
    </div>
</div>

{{-- ══ HERO ══════════════════════════════════════════════════════════════════ --}}
<div class="bl-hero">
    <div class="bl-hero-ripple"></div>
    <div class="container bl-hero-inner">

        <div class="bl-hero-badge">
            <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Ganga River · Varanasi
        </div>

        <h1>{{ $isDevDiwali ? 'Dev Diwali Varanasi Boat Booking 2026' : ($isMotorBoat ? 'Varanasi Boat Booking – Private Motor Boat for Ganga Aarti & 84 Ghats' : ($isBajraBoat ? 'Bajra Boat Booking in Varanasi' : (optional($sub_category)->name ?? 'Motor Boat Rides in Varanasi'))) }}</h1>

        @if($isMotorBoat)
        <p class="bl-hero-intro">Book a private motor boat in Varanasi for a morning ride along the ghats or an evening ride timed to the Ganga Aarti. VisitKashi's motor boat booking covers the full stretch of the river from Assi Ghat to Namo Ghat — all 84 ghats of Varanasi — with private, no-sharing options for couples, families and small groups. Choose a sunrise ride to see the ghats wake up, or an evening ride to watch the Ganga Aarti and the diya-lit riverfront from the water. Every boat is booked online in minutes, confirmed by our local Varanasi team over call or WhatsApp, with clear per-boat pricing and no hidden charges.</p>
        @endif

        <div class="bl-hero-meta">
            <div class="bl-hero-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Varanasi Ghats, Uttar Pradesh
            </div>
            <div class="bl-hero-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Morning & Evening Rides Available
            </div>
            <div class="bl-hero-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Instant Booking Confirmation
            </div>
        </div>

        <div class="bl-hero-stats">
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">4.9★</span>
                <span class="bl-hero-stat-lbl">Avg Rating</span>
            </div>
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">10,000+</span>
                <span class="bl-hero-stat-lbl">Happy Travelers</span>
            </div>
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">{{ $products->count() }}</span>
                <span class="bl-hero-stat-lbl">Boat Options</span>
            </div>
            <div class="bl-hero-stat">
                <span class="bl-hero-stat-val">5+ Yrs</span>
                <span class="bl-hero-stat-lbl">Experience</span>
            </div>
        </div>

        @if($isMotorBoat)
        <div class="bl-hero-ctas">
            <a href="#blGrid" class="bl-hero-cta-btn">
                <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Check Boat Availability
            </a>
            <a href="https://wa.me/917080109917?text=Hi%2C+I+want+to+book+a+motor+boat+in+Varanasi" target="_blank" rel="noopener noreferrer" class="bl-hero-cta-btn bl-hero-cta-btn--wa">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2.003C6.477 2.003 2 6.479 2 12.007c0 1.763.463 3.418 1.26 4.861L2 22l5.278-1.243A9.963 9.963 0 0012.004 22c5.527 0 10.004-4.477 10.004-10.004S17.531 2.003 12.004 2.003z"/></svg>
                Chat on WhatsApp
            </a>
        </div>
        @endif

    </div>
    <svg class="bl-hero-wave" viewBox="0 0 1440 52" fill="#fff" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,32 C240,52 480,10 720,32 C960,54 1200,12 1440,32 L1440,52 L0,52 Z"/>
    </svg>
</div>

{{-- ── Filter Bar ── --}}
<div class="bl-filterbar">
    <div class="container">
        <div class="bl-filterbar-inner">
            <button class="bl-filter-pill active" data-filter="all">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                All Rides
            </button>
            <button class="bl-filter-pill" data-filter="morning">🌅 Morning</button>
            <button class="bl-filter-pill" data-filter="evening">🌆 Evening Aarti</button>
            <button class="bl-filter-pill" data-filter="private">🔒 Private</button>
            <button class="bl-filter-pill" data-filter="group">👥 Group</button>
            <button class="bl-filter-pill" data-filter="event">🎉 Events</button>
            <div class="bl-filter-sep"></div>
            <span class="bl-result-count" id="blResultCount">{{ $products->count() }} ride{{ $products->count() != 1 ? 's' : '' }}</span>
        </div>
    </div>
</div>

{{-- ══ DEV DIWALI ENQUIRY FORM ════════════════════════════════════════════════ --}}
@if($isDevDiwali)
<style>
.dd-enq-section{background:linear-gradient(150deg,#1a0800 0%,#4a1800 50%,#7c2d12 100%);padding:52px 0;}
.dd-enq-row{display:grid;grid-template-columns:1fr 420px;gap:48px;align-items:center;}
.dd-enq-info-wrap{color:#fff;}
.dd-enq-flame{font-size:3.2rem;line-height:1;margin-bottom:16px;display:block;}
.dd-enq-info-title{font-size:1.95rem;font-weight:900;line-height:1.15;margin:0 0 14px;background:linear-gradient(90deg,#fbbf24,#f59e0b,#d97706);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.dd-enq-info-sub{font-size:.95rem;color:rgba(255,255,255,.82);line-height:1.7;margin:0 0 24px;}
.dd-enq-highlights{display:flex;flex-direction:column;gap:10px;}
.dd-enq-hl{display:flex;align-items:flex-start;gap:10px;font-size:.87rem;color:rgba(255,255,255,.85);}
.dd-enq-hl-icon{font-size:.95rem;flex-shrink:0;margin-top:2px;}
.dd-enq-form-card{background:#fff;border-radius:20px;padding:30px;box-shadow:0 20px 60px rgba(0,0,0,.4);}
.dd-enq-form-title{font-size:1.05rem;font-weight:800;color:#1a1a1a;margin:0 0 5px;}
.dd-enq-date-pill{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#7c2d12,#b45309);color:#fff;font-size:.68rem;font-weight:700;padding:4px 12px;border-radius:20px;margin-bottom:20px;letter-spacing:.04em;text-transform:uppercase;}
.dd-enq-fld{margin-bottom:14px;}
.dd-enq-fld label{display:block;font-size:.78rem;font-weight:700;color:#374151;margin-bottom:5px;}
.dd-enq-fld-hint{font-size:.65rem;font-weight:400;color:#9ca3af;text-transform:none;letter-spacing:0;}
.dd-enq-fld input{width:100%;box-sizing:border-box;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:10px;font-size:.92rem;color:#1a1a1a;outline:none;transition:border-color .2s,box-shadow .2s;}
.dd-enq-fld input:hover{border-color:#b8bfc9;}
.dd-enq-fld input:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.12);}
.dd-enq-fld input[readonly]{background:#f9f5f0;color:#555;cursor:default;}
.dd-enq-row2{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;}
.dd-enq-row2 .dd-enq-fld{margin-bottom:0;}
.dd-enq-counter{display:flex;align-items:center;border:1.5px solid #d1d5db;border-radius:10px;overflow:hidden;transition:border-color .2s;}
.dd-enq-counter:focus-within{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.12);}
.dd-enq-counter button{background:#f3f4f6;border:none;width:38px;height:44px;font-size:1.2rem;font-weight:700;cursor:pointer;color:#374151;transition:background .15s,color .15s;flex-shrink:0;line-height:1;}
.dd-enq-counter button:hover:not(:disabled){background:#fef3c7;color:#92400e;}
.dd-enq-counter button:active:not(:disabled){transform:scale(.94);}
.dd-enq-counter button:disabled{color:#d1d5db;cursor:default;}
.dd-enq-counter input{flex:1;width:0;border:none;outline:none;text-align:center;font-size:1rem;font-weight:700;color:#1a1a1a;background:#fff;}
.dd-enq-submit-btn{width:100%;padding:14px;border:none;border-radius:12px;background:linear-gradient(135deg,#7c2d12,#b45309,#d97706);color:#fff;font-size:.95rem;font-weight:800;cursor:pointer;letter-spacing:.02em;transition:opacity .2s,transform .2s,box-shadow .2s;margin-top:10px;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 6px 18px rgba(180,83,9,.3);}
.dd-enq-submit-btn:hover{opacity:.94;transform:translateY(-1px);box-shadow:0 8px 22px rgba(180,83,9,.4);}
.dd-enq-submit-btn:active{transform:translateY(0);}
.dd-enq-note{display:flex;align-items:center;justify-content:center;gap:6px;font-size:.72rem;color:#9ca3af;margin:10px 0 0;text-align:center;line-height:1.5;}
.dd-enq-req{color:#dc2626;}
.dd-enq-wa-link{display:flex;align-items:center;justify-content:center;gap:8px;color:rgba(255,255,255,.7);font-size:.8rem;text-decoration:none;font-weight:600;margin-top:14px;transition:color .2s;}
.dd-enq-wa-link:hover{color:#fff;text-decoration:none;}
@media(max-width:920px){.dd-enq-row{grid-template-columns:1fr;gap:28px;}.dd-enq-info-wrap{text-align:center;}.dd-enq-highlights{align-items:center;}}
@media(max-width:480px){.dd-enq-section{padding:36px 0;}.dd-enq-form-card{padding:22px;}.dd-enq-row2{grid-template-columns:1fr 1fr;gap:8px;}}
</style>

<section class="dd-enq-section" id="dd-book-form">
    <div class="container">

        @if(session('success'))
        <div class="bl-success-notice">
            ✓ {{ session('success') }}
        </div>
        @endif

        <div class="dd-enq-row">

            {{-- Left: Event highlights --}}
            <div class="dd-enq-info-wrap">
                <span class="dd-enq-flame">🪔</span>
                <h2 class="dd-enq-info-title">Dev Diwali Varanasi<br>24 November 2026</h2>
                <p class="dd-enq-info-sub">Watch 1 lakh+ earthen lamps illuminate the sacred Ghats of Varanasi on the most magical night of the year. Secure your exclusive Dev Diwali boat ride today.</p>
                <div class="dd-enq-highlights">
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">🚤</span><span>Exclusive boat ride on the sacred Ganga River</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">🪔</span><span>1 Lakh+ diyas lit on 84 Ghats of Varanasi</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">📅</span><span>Fixed date — 24 November 2026 only</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">📍</span><span>Ravidas Ghat pickup · Per person pricing</span></div>
                    <div class="dd-enq-hl"><span class="dd-enq-hl-icon">✅</span><span>Instant WhatsApp confirmation</span></div>
                </div>
            </div>

            {{-- Right: Enquiry form --}}
            <div>
                <div class="dd-enq-form-card">
                    <div class="dd-enq-form-title">Book Dev Diwali Boat Ride</div>
                    <div class="dd-enq-date-pill">🪔 24 November 2026 · Fixed Date</div>

                    <form action="{{ route('enquiry.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_name" value="Dev Diwali Boat Booking – Varanasi 2026">
                        <input type="hidden" name="arrival_date" value="2026-11-24">
                        <input type="hidden" name="pickup_ghat"  value="Ravidas Ghat">
                        <input type="hidden" name="time_slot"    value="">

                        <div class="dd-enq-fld">
                            <label>Full Name <span class="dd-enq-req">*</span></label>
                            <input type="text" name="name" required placeholder="Your name" value="{{ old('name') }}">
                        </div>
                        <div class="dd-enq-fld">
                            <label>Phone <span class="dd-enq-req">*</span></label>
                            <input type="tel" name="phone" required placeholder="+91 XXXXX XXXXX" value="{{ old('phone') }}">
                        </div>
                        <div class="dd-enq-fld">
                            <label>Travel Date</label>
                            <input type="text" value="24 November 2026" readonly>
                        </div>
                        <div class="dd-enq-row2">
                            <div class="dd-enq-fld">
                                <label>Adults <span class="dd-enq-req">*</span></label>
                                <div class="dd-enq-counter">
                                    <button type="button" id="ddPersonsMinus" onclick="ddCount(-1)" aria-label="Decrease adults">&#8722;</button>
                                    <input type="number" name="no_of_person" id="ddPersons" value="2" min="1" max="200" readonly>
                                    <button type="button" id="ddPersonsPlus" onclick="ddCount(1)" aria-label="Increase adults">&#43;</button>
                                </div>
                            </div>
                            <div class="dd-enq-fld">
                                <label>Children <span class="dd-enq-fld-hint">&lt;10 yrs</span></label>
                                <div class="dd-enq-counter">
                                    <button type="button" id="ddChildrenMinus" onclick="ddChildCount(-1)" aria-label="Decrease children" disabled>&#8722;</button>
                                    <input type="number" name="children_count" id="ddChildren" value="0" min="0" max="20" readonly>
                                    <button type="button" id="ddChildrenPlus" onclick="ddChildCount(1)" aria-label="Increase children">&#43;</button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="dd-enq-submit-btn">
                            <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Submit Enquiry
                        </button>
                        <p class="dd-enq-note">🔒 No payment now — we'll confirm availability by call or WhatsApp.</p>
                    </form>
                </div>

                <a href="https://wa.me/917080109917?text=Hi%2C+I+want+to+book+Dev+Diwali+Boat+Ride+on+24+November+2026" target="_blank" rel="noopener noreferrer" class="dd-enq-wa-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2.003C6.477 2.003 2 6.479 2 12.007c0 1.763.463 3.418 1.26 4.861L2 22l5.278-1.243A9.963 9.963 0 0012.004 22c5.527 0 10.004-4.477 10.004-10.004S17.531 2.003 12.004 2.003z"/></svg>
                    WhatsApp: 7080109917
                </a>
            </div>

        </div>
    </div>
</section>
@endif

{{-- ══ BOAT CARDS GRID ════════════════════════════════════════════════════════ --}}
<div class="bl-grid-section">
    <div class="container">

        <div class="bl-section-head">
            <div>
                <h2 class="bl-section-title">Available Boat Rides</h2>
                <p class="bl-section-sub">All rides include life jackets • Professional boatmen • Ganga river experience</p>
            </div>
        </div>

        <div class="bl-grid" id="blGrid">
            @forelse($products as $product)
            @php
                $imgs = (!empty($product->images) && is_array($product->images))
                    ? $product->images
                    : ((!empty($product->images) && is_string($product->images))
                        ? json_decode($product->images, true) ?? []
                        : []);
                $imgUrls = array_map(fn($f) => asset('backend/admin/product_images/'.$f), $imgs);
                $fallback = asset('backend/assets/images/placeholder.jpg');
                if (empty($imgUrls)) $imgUrls = [$fallback];

                $detailUrl = route('product.detail', [
                    optional($product->category)->slug ?? 'boat',
                    optional($product->subCategory)->slug ?? 'motor-boat',
                    $product->slug
                ]);

                $hasDiscount = ($product->base_price ?? 0) > 0
                    && ($product->discounted_price ?? 0) > 0
                    && $product->base_price > $product->discounted_price;
                $pct = $hasDiscount
                    ? round((($product->base_price - $product->discounted_price) / $product->base_price) * 100)
                    : 0;

                $name = strtolower($product->name);
                $tags = [];
                if (str_contains($name, 'morning'))                                                $tags[] = ['🌅', 'Morning Ride'];
                if (str_contains($name, 'evening'))                                                 $tags[] = ['🌆', 'Evening'];
                if (str_contains($name, 'aarti'))                                                   $tags[] = ['🪔', 'Ganga Aarti'];
                if (str_contains($name, 'private'))                                                 $tags[] = ['🔒', 'Private'];
                if (str_contains($name, 'group'))                                                   $tags[] = ['👥', 'Group'];
                if (str_contains($name,'decoration')||str_contains($name,'birthday')||str_contains($name,'anniversary')) $tags[] = ['🎉', 'Event'];
                if (str_contains($name, 'assi'))                                                    $tags[] = ['📍', 'Assi Ghat'];
                if (str_contains($name, 'dashaswamedh'))                                            $tags[] = ['📍', 'Dashashwamedh'];
                if (str_contains($name, 'namo'))                                                    $tags[] = ['📍', 'Namo Ghat'];
                if (empty($tags))                                                                   $tags[] = ['⛵', 'Boat Ride'];
                $tags = array_slice($tags, 0, 3);

                // filter keywords for JS data attribute
                $filterKeys = [];
                if (str_contains($name, 'morning'))  $filterKeys[] = 'morning';
                if (str_contains($name, 'evening') || str_contains($name, 'aarti')) $filterKeys[] = 'evening';
                if (str_contains($name, 'private'))  $filterKeys[] = 'private';
                if (str_contains($name, 'group'))    $filterKeys[] = 'group';
                if (str_contains($name,'decoration')||str_contains($name,'birthday')||str_contains($name,'anniversary')) $filterKeys[] = 'event';
                if (empty($filterKeys)) $filterKeys[] = 'all';
            @endphp

            <article class="bl-card"
                     data-filter="{{ implode(' ', $filterKeys) }}"
                     itemscope itemtype="https://schema.org/Product">

                {{-- Image Slider --}}
                <a href="{{ $detailUrl }}" class="bl-img-wrap" data-cur="0" data-total="{{ count($imgUrls) }}" aria-label="{{ $product->name }} - boat ride photo">
                    <meta itemprop="image" content="{{ $imgUrls[0] }}">

                    <div class="bl-img-track">
                        @foreach($imgUrls as $k => $url)
                        <div class="bl-img-slide">
                            <img src="{{ $url }}"
                                 alt="{{ $product->name }} – boat ride in Varanasi"
                                 loading="{{ $k === 0 ? 'eager' : 'lazy' }}"
                                 width="400" height="300"
                                 onerror="this.src='{{ $fallback }}'">
                        </div>
                        @endforeach
                    </div>

                    @if(count($imgUrls) > 1)
                    <button class="bl-img-arrow prev" aria-label="Previous photo" tabindex="-1">&#8249;</button>
                    <button class="bl-img-arrow next" aria-label="Next photo" tabindex="-1">&#8250;</button>
                    <div class="bl-img-counter">1 / {{ count($imgUrls) }}</div>
                    <div class="bl-dots">
                        @foreach(array_slice($imgUrls, 0, 5) as $k => $u)
                        <div class="bl-dot {{ $k === 0 ? 'active' : '' }}"></div>
                        @endforeach
                    </div>
                    @endif

                    <span class="bl-type-badge">Popular</span>
                    <button class="bl-heart" aria-label="Save to wishlist" tabindex="0">♡</button>
                </a>

                {{-- Card Body --}}
                <div class="bl-card-body" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <meta itemprop="priceCurrency" content="INR">

                    {{-- Title --}}
                    <h3 class="bl-title" itemprop="name">{{ $product->name }}</h3>

                    {{-- Price + Rating row --}}
                    <div class="bl-price-rating-row">
                        <div class="bl-price-inline">
                            @if($isDevDiwali)
                                <span class="bl-price-call-now">📞 Call Now · Best Price Guaranteed</span>
                            @elseif(($product->discounted_price ?? 0) > 0)
                                @if($hasDiscount)
                                <span class="bl-price-old">₹{{ number_format($product->base_price) }}</span>
                                @endif
                                <span class="bl-price" itemprop="price" content="{{ $product->discounted_price }}">₹{{ number_format($product->discounted_price) }}</span>
                                <span class="bl-price-unit">/ trip</span>
                            @elseif(($product->base_price ?? 0) > 0)
                                <span class="bl-price" itemprop="price" content="{{ $product->base_price }}">₹{{ number_format($product->base_price) }}</span>
                                <span class="bl-price-unit">/ trip</span>
                            @else
                                <span class="bl-price-unit bl-price-unit--muted">Price on request</span>
                            @endif
                        </div>
                        <div class="bl-rating" aria-label="Rating 4.9">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="#222"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            4.9
                        </div>
                    </div>

                </div>
            </article>

            @empty
            <div class="bl-empty">
                <div class="bl-empty-icon">⛵</div>
                <div class="bl-empty-title">No boat rides found</div>
                <div class="bl-empty-sub">Check back soon — more experiences are being added.</div>
            </div>
            @endforelse
        </div>

        <p id="blNoResults" class="bl-no-results" style="display:none;">
            No rides match this filter. <button onclick="blSetFilter('all')" class="bl-show-all-btn">Show all →</button>
        </p>

    </div>
</div>

{{-- ══ DEV DIWALI SEO CONTENT ═════════════════════════════════════════════════ --}}
@if($isDevDiwali)
<style>
.dd-seo{background:#fff;border-top:1px solid #f0f0f0;padding:48px 0;}
.dd-seo-intro{max-width:820px;margin:0 0 8px;}
.dd-seo h2{font-size:1.3rem;font-weight:800;color:#111;letter-spacing:-.02em;margin:44px 0 14px;}
.dd-seo > .dd-seo-intro h2{margin-top:0;}
.dd-seo h3{font-size:.98rem;font-weight:700;color:#1a2b4c;margin:0 0 6px;padding-left:12px;border-left:3px solid #d4850a;}
.dd-seo p{font-size:.92rem;color:#444;line-height:1.75;margin:0 0 12px;max-width:820px;}
.dd-seo-block{margin-bottom:18px;}
.dd-seo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px 28px;margin-top:18px;}
.dd-seo-grid.cols-2{grid-template-columns:repeat(2,1fr);}
.dd-seo-grid.cols-5{grid-template-columns:repeat(5,1fr);}
.dd-seo-grid .dd-seo-block p{margin-bottom:0;}
.dd-seo-boat-card{background:#f9fafb;border:1px solid #eef1f5;border-radius:14px;padding:18px 20px;display:flex;flex-direction:column;}
.dd-seo-boat-card h3{border-left:none;padding-left:0;}
.dd-seo-boat-price{font-size:.86rem;font-weight:800;color:#0f3460;margin:6px 0 10px;}
.dd-seo-boat-link{margin-top:auto;font-size:.8rem;font-weight:700;color:#b45309;text-decoration:none;}
.dd-seo-boat-link:hover{text-decoration:underline;}
.dd-seo-steps{counter-reset:dd-step;list-style:none;padding:0;margin:18px 0 0;display:flex;flex-direction:column;gap:16px;}
.dd-seo-steps li{display:flex;gap:14px;align-items:flex-start;}
.dd-seo-steps li::before{counter-increment:dd-step;content:counter(dd-step);flex-shrink:0;width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#0f3460,#1a5276);color:#fff;font-size:.82rem;font-weight:800;display:flex;align-items:center;justify-content:center;}
.dd-seo-steps h3{margin:0 0 3px;border:none;padding:0;}
.dd-seo-steps p{margin:0;}
.dd-seo-cta{display:inline-flex;align-items:center;gap:8px;margin-top:6px;padding:11px 26px;background:linear-gradient(135deg,#7c2d12,#b45309,#d97706);color:#fff;border-radius:11px;font-size:.86rem;font-weight:800;text-decoration:none;letter-spacing:.02em;transition:opacity .2s,transform .2s;}
.dd-seo-cta:hover{opacity:.9;transform:translateY(-1px);color:#fff;text-decoration:none;}
.dd-seo-faq{max-width:900px;}
.dd-seo-faq-item{border-bottom:1px solid #e5e7eb;padding:18px 0;}
.dd-seo-faq-item:first-child{padding-top:0;}
.dd-seo-faq-item:last-child{border-bottom:none;padding-bottom:0;}
.dd-seo-faq-item h3{margin:0 0 8px;}
.dd-seo-faq-item p{margin:0;max-width:none;}
.dd-seo-mt{margin-top:12px;}
@media(max-width:900px){.dd-seo-grid{grid-template-columns:repeat(2,1fr);}.dd-seo-grid.cols-5{grid-template-columns:repeat(2,1fr);}}
@media(max-width:560px){.dd-seo-grid,.dd-seo-grid.cols-2,.dd-seo-grid.cols-5{grid-template-columns:1fr;}}
</style>

<section class="dd-seo">
    <div class="container">

        {{-- Intro --}}
        <div class="dd-seo-intro">
            <h2>Dev Diwali Boat Booking in Varanasi – 24 November 2026</h2>
            <p>Dev Diwali Varanasi boat booking for 2026 is now open with VisitKashi. On 24 November 2026, the ghats of Kashi are lit with lakhs of earthen diyas for Dev Deepawali, and a boat ride on the Ganga is the best way to watch the illuminated ghats, the evening Ganga Aarti and the fireworks from the river. Choose from motor boats, Bajra boats, cruise boats and private luxury boats and secure your Dev Diwali cruise booking below.</p>
        </div>

        {{-- Why book a boat --}}
        <h2>Book Your Dev Diwali Cruise on the Ganga</h2>
        <div class="dd-seo-grid cols-3">
            <div class="dd-seo-block">
                <h3>Why Book a Boat for Dev Diwali in Varanasi?</h3>
                <p>A Dev Diwali boat ride gives you an open river view of the illuminated ghats without fighting the crowds that fill the banks that night — the closest and clearest way to experience Dev Deepawali in Varanasi.</p>
            </div>
            <div class="dd-seo-block">
                <h3>Experience the Illuminated Ghats from the Ganga</h3>
                <p>As your boat moves along the river, you'll see lakhs of diyas lit along the steps of the ghats, turning the entire riverfront into a glowing, golden skyline for one night of the year.</p>
            </div>
            <div class="dd-seo-block">
                <h3>Enjoy Ganga Aarti, Fireworks and Dev Diwali Celebrations</h3>
                <p>Your Dev Diwali boat ride is timed to cover the evening Ganga Aarti and the fireworks over the Ganga, so you experience the full Dev Diwali celebration from the water.</p>
            </div>
        </div>

        {{-- Booking options — driven from real live products --}}
        <h2>Dev Diwali Boat &amp; Cruise Booking Options</h2>
        <div class="dd-seo-grid cols-3">
            @php
                $ddLabel = function($name) {
                    $n = strtolower($name);
                    if (str_contains($n, 'maharaja'))                              return 'Maharaja Boat — Luxury Private Boat';
                    if (str_contains($n, 'cruise'))                                return 'Cruise Boat';
                    if (str_contains($n, 'bajra'))                                 return 'Bajra Boat';
                    if (str_contains($n, 'light motor') && str_contains($n,'group')) return 'Private Light Motor Boat (Group)';
                    if (str_contains($n, 'light motor'))                           return 'Light Motor Boat';
                    if (str_contains($n, 'private') && str_contains($n, 'motor'))  return 'Private Motor Boat';
                    return 'Motor Boat';
                };
            @endphp
            @foreach($products as $sp)
            @php
                $spPrice = ($sp->discounted_price ?? 0) > 0 ? $sp->discounted_price : ($sp->base_price ?? 0);
                $spUrl   = route('product.detail', [optional($sp->category)->slug ?? 'boat', optional($sp->subCategory)->slug ?? 'dev-diwali-booking', $sp->slug]);
            @endphp
            <div class="dd-seo-boat-card">
                <h3>{{ $ddLabel($sp->name) }}</h3>
                <p>{{ Str::limit(strip_tags($sp->description ?? ''), 90) ?: 'Dev Diwali boat booking on the Ganga in Varanasi.' }}</p>
                @if($spPrice > 0)
                <div class="dd-seo-boat-price">From ₹{{ number_format($spPrice) }}</div>
                @endif
                <a href="{{ $spUrl }}" class="dd-seo-boat-link">View &amp; Book →</a>
            </div>
            @endforeach
        </div>

        {{-- What's included --}}
        <h2>What is Included in Dev Diwali Boat Booking?</h2>
        <div class="dd-seo-grid cols-5">
            <div class="dd-seo-block"><h3>Ganga Boat Ride</h3><p>A full boat ride on the Ganga during the Dev Diwali celebration.</p></div>
            <div class="dd-seo-block"><h3>View of the Ghats</h3><p>An open river view of the illuminated ghats of Varanasi.</p></div>
            <div class="dd-seo-block"><h3>Ganga Aarti</h3><p>The evening Ganga Aarti viewed live from your boat.</p></div>
            <div class="dd-seo-block"><h3>Fireworks</h3><p>Fireworks over the Ganga as part of the Dev Diwali celebrations.</p></div>
            <div class="dd-seo-block"><h3>1 Lakh+ Diya Illumination</h3><p>Lakhs of earthen diyas lit across the ghats of Kashi.</p></div>
        </div>

        {{-- Timings & reporting --}}
        <h2>Dev Diwali Boat Booking Timings &amp; Reporting Point</h2>
        <div class="dd-seo-grid cols-3">
            <div class="dd-seo-block"><h3>Reporting Time</h3><p>4:00 PM at Ravidas Ghat — we recommend arriving by 3:30 PM to complete boarding formalities.</p></div>
            <div class="dd-seo-block"><h3>Boat Ride Timing</h3><p>5:00 PM to 8:00 PM, covering the evening Ganga Aarti and peak diya illumination.</p></div>
            <div class="dd-seo-block"><h3>Boarding Point</h3><p>Ravidas Ghat, Varanasi — the fixed boarding point for all Dev Diwali boat bookings.</p></div>
        </div>

        {{-- Pricing --}}
        <h2>Dev Diwali Boat Booking Price in Varanasi</h2>
        <div class="dd-seo-grid cols-3">
            <div class="dd-seo-block"><h3>Group / Shared Boat Booking</h3><p>Shared Dev Diwali boat and light motor boat rides start from ₹3,499 per person.</p></div>
            <div class="dd-seo-block"><h3>Premium Cruise Options</h3><p>Bajra boat and cruise boat bookings start from ₹9,999 per person.</p></div>
            <div class="dd-seo-block"><h3>Private Boat Booking</h3><p>Private motor boats and the luxury Maharaja boat start from ₹45,000 for exclusive hire.</p></div>
        </div>
        <p class="dd-seo-mt">Exact Dev Diwali boat booking price for each boat type — including seat availability and group rates — is shown on the individual boat's booking page above.</p>

        {{-- How to book --}}
        <h2>How to Book a Boat for Dev Diwali in Varanasi?</h2>
        <ol class="dd-seo-steps">
            <li><div><h3>Step 1 – Select Your Boat</h3><p>Choose a Dev Diwali boat or cruise from the options above — motor boat, Bajra boat, cruise boat or a private luxury boat.</p></div></li>
            <li><div><h3>Step 2 – Confirm Date and Number of Guests</h3><p>Dev Diwali is fixed for 24 November 2026 — just enter your name, phone number and number of guests in the enquiry form.</p></div></li>
            <li><div><h3>Step 3 – Make Your Booking</h3><p>Submit the enquiry form or message us directly on WhatsApp to lock in your Dev Diwali boat booking.</p></div></li>
            <li><div><h3>Step 4 – Receive Booking Confirmation</h3><p>Our team confirms your Dev Diwali boat booking by call or WhatsApp with your reporting time and boarding point.</p></div></li>
        </ol>
        <a href="#dd-book-form" class="dd-seo-cta">
            <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Book Your Dev Diwali Boat
        </a>

        {{-- Why VisitKashi --}}
        <h2>Why Choose VisitKashi for Dev Diwali Boat Booking?</h2>
        <div class="dd-seo-grid cols-3">
            <div class="dd-seo-block"><h3>Local Varanasi Travel Experts</h3><p>VisitKashi is based in Varanasi with first-hand knowledge of the ghats, the river and the Dev Diwali celebrations.</p></div>
            <div class="dd-seo-block"><h3>Experienced Boat Booking Team</h3><p>5+ years of experience booking boat rides and Dev Diwali cruises on the Ganga for Indian and international travellers.</p></div>
            <div class="dd-seo-block"><h3>Transparent Booking Assistance</h3><p>Clear pricing per boat type, with our team on call to help you pick the right boat for your group.</p></div>
            <div class="dd-seo-block"><h3>Dedicated Customer Support</h3><p>Reach us anytime by call or WhatsApp at +91-7080109917, 7080109918 or 7080109919 for booking help.</p></div>
        </div>

        {{-- FAQ --}}
        <h2 id="dd-faq">Dev Diwali Varanasi Boat Booking – Frequently Asked Questions</h2>
        <div class="dd-seo-faq">
            @foreach($ddFaqs as $faq)
            <div class="dd-seo-faq-item">
                <h3>{{ $faq['q'] }}</h3>
                <p>{{ $faq['a'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ══ MOTOR BOAT SEO CONTENT ═════════════════════════════════════════════════ --}}
@if($isMotorBoat || $isBajraBoat)
@php
    $mbUrl = fn($slug) => route('product.detail', ['boat', 'motor-boat', $slug]);
    $bbUrl = fn($slug) => route('product.detail', ['boat', 'bajra-boat', $slug]);
@endphp

<section class="mb-seo">
    <div class="container">

    @if($isBajraBoat)
        {{-- H2: Bajra Boat Booking in Varanasi --}}
        <div class="mb-seo-intro">
            <h2>Bajra Boat Booking in Varanasi</h2>
            <p>A Bajra boat is a traditional, larger boat on the Ganga — commonly booked privately for the evening Ganga Aarti, or decorated for a birthday, anniversary or other celebration. Pickup is generally available from Assi Ghat, Dashashwamedh Ghat or Namo Ghat depending on the boat, and pricing is shown on each listing below.</p>
        </div>

        <div class="mb-seo-grid cols-3">
            <div class="mb-seo-block">
                <h3>Private Bajra Boat for Ganga Aarti</h3>
                <p>A private Bajra boat positioned in front of the evening Ganga Aarti at Dashashwamedh Ghat, with no sharing with other groups.</p>
                <a href="{{ $bbUrl('private-bajra-boat-for-ganga-aarti') }}" class="mb-seo-link">Book Private Bajra Boat →</a>
            </div>
            <div class="mb-seo-block">
                <h3>Flower Decorated Bajra Boat</h3>
                <p>A Bajra boat decorated with flowers for anniversaries, proposals and other special river celebrations.</p>
                <a href="{{ $bbUrl('flower-decorated-bajra-boat') }}" class="mb-seo-link">Book Flower Decorated Boat →</a>
            </div>
            <div class="mb-seo-block">
                <h3>Surprise Event Decoration Bajra Boat</h3>
                <p>A custom-decorated Bajra boat for birthday or anniversary surprises on the river.</p>
                <a href="{{ $bbUrl('surprise-event-decoration-bajra-boat-booking-for-birthdayanniversary') }}" class="mb-seo-link">Book Event Decoration Boat →</a>
            </div>
        </div>

        {{-- H2: Evening Ganga Aarti from a Bajra Boat --}}
        <h2>Evening Ganga Aarti from a Bajra Boat</h2>
        <p>The evening Ganga Aarti at Dashashwamedh Ghat typically runs from around 7:00 PM to 7:45 PM. A private Bajra boat is positioned on the river for the ceremony, giving your group a river view of the diyas, chants and temple bells without sharing the boat with other travellers. Exact reporting time and pickup point are confirmed by our team when you book.</p>
        <a href="{{ $bbUrl('private-bajra-boat-for-ganga-aarti') }}" class="mb-seo-cta">
            <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Book Private Bajra Boat for Ganga Aarti
        </a>

        {{-- H2: Ghats covered --}}
        <h2>Ghats Along the Bajra Boat Route</h2>
        <p>Depending on the route, a Bajra boat ride can take in a number of the historic ghats of Varanasi along the riverfront.</p>
        <div class="mb-seo-ghat-tags">
            <span class="mb-seo-ghat-tag">Assi Ghat</span>
            <span class="mb-seo-ghat-tag">Shivala Ghat</span>
            <span class="mb-seo-ghat-tag">Harishchandra Ghat</span>
            <span class="mb-seo-ghat-tag">Dashashwamedh Ghat</span>
            <span class="mb-seo-ghat-tag">Manikarnika Ghat</span>
            <span class="mb-seo-ghat-tag">Rajendra Prasad Ghat</span>
            <span class="mb-seo-ghat-tag">Namo Ghat</span>
        </div>

        {{-- Internal links to related boat pages --}}
        <h2>Explore More Boat Options in Varanasi</h2>
        <div class="mb-seo-grid cols-4">
            <div class="mb-seo-block"><h3>Private Motor Boat</h3><p>A faster, more flexible private motor boat for the evening Ganga Aarti.</p><a href="{{ $mbUrl('private-motor-boat-for-evening-ganga-aarti') }}" class="mb-seo-link">View Motor Boats →</a></div>
            <div class="mb-seo-block"><h3>Motor Boat Booking</h3><p>Shared and private motor boat rides for mornings and evenings on the Ganga.</p><a href="{{ route('product.sub.list', ['boat', 'motor-boat']) }}" class="mb-seo-link">View Motor Boats →</a></div>
            <div class="mb-seo-block"><h3>Dev Diwali Boat Booking</h3><p>Book a boat for Dev Diwali, when the ghats are lit with lakhs of diyas.</p><a href="{{ route('product.sub.list', ['boat', 'dev-diwali-booking']) }}" class="mb-seo-link">View Dev Diwali Boats →</a></div>
            <div class="mb-seo-block"><h3>Event Boat Booking</h3><p>Larger decorated boats for birthdays, anniversaries and other celebrations.</p><a href="{{ route('product.sub.list', ['boat', 'event-boat']) }}" class="mb-seo-link">View Event Boats →</a></div>
        </div>
    @endif

    @if($isMotorBoat)
        {{-- H2 1: Private Motor Boat Booking in Varanasi --}}
        <div class="mb-seo-intro">
            <h2>Private Motor Boat Booking in Varanasi</h2>
            <p>VisitKashi's private motor boat booking in Varanasi covers both the morning and evening river experience — from a peaceful sunrise ride along the ghats to a front-row seat on the water for the evening Ganga Aarti. Boats can be booked privately (no sharing) or as a shared group ride, with pickup available from Assi Ghat, Dashashwamedh Ghat, Namo Ghat and Shivala Ghat. Whether you want a short ride near the main ghats or the full Assi Ghat to Namo Ghat route covering all 84 ghats, you can check options and book online below.</p>
        </div>

        <div class="mb-seo-grid cols-3">
            <div class="mb-seo-block">
                <h3>Book Private Motor Boat for Evening Ganga Aarti</h3>
                <p>Watch the Ganga Aarti from the river on a private motor boat, positioned near Dashashwamedh Ghat for the full 7:00 PM–7:45 PM ceremony.</p>
                <a href="{{ $mbUrl('private-motor-boat-for-evening-ganga-aarti') }}" class="mb-seo-link">Book Evening Ganga Aarti Boat →</a>
            </div>
            <div class="mb-seo-block">
                <h3>Book Private Motor Boat for Morning Ride</h3>
                <p>Start before sunrise for a calm ride along Assi Ghat, Dashashwamedh Ghat, Manikarnika Ghat and Harishchandra Ghat.</p>
                <a href="{{ $mbUrl('morning-boat-ride-in-varanasi') }}" class="mb-seo-link">Book Morning Boat Ride →</a>
            </div>
            <div class="mb-seo-block">
                <h3>Book Private Motor Boat for All 84 Ghats</h3>
                <p>Take the full Assi Ghat to Namo Ghat route — about 7 km along the riverfront, covering all 84 ghats of Varanasi.</p>
                <a href="{{ $mbUrl('pickup-assi-ghat-private-boat-ride-for-evening-ganga-aarti') }}" class="mb-seo-link">Book 84 Ghats Boat Ride →</a>
            </div>
        </div>

        {{-- H2 2: Morning Boat Ride --}}
        <h2>Morning Boat Ride in Varanasi</h2>
        <p>A morning boat ride in Varanasi usually starts before sunrise and runs for about one to two hours along the river. As the sky lightens over the Ganga, your boatman takes you past Assi Ghat, Dashashwamedh Ghat, Manikarnika Ghat and Harishchandra Ghat, where you can see the day's first rituals, yoga and bathing along the steps. Mornings tend to be quieter than the middle of the day, which makes a private morning boat ride a good choice for photography and for simply taking in the riverfront at a slower pace.</p>
        <a href="{{ $mbUrl('morning-boat-ride-in-varanasi') }}" class="mb-seo-cta">
            <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Book Morning Boat Ride in Varanasi
        </a>

        {{-- H2 3: Evening Boat Ride & Ganga Aarti --}}
        <h2>Evening Boat Ride &amp; Ganga Aarti in Varanasi</h2>
        <p>The evening boat ride is timed around the Ganga Aarti, which takes place from 7:00 PM to 7:45 PM at Dashashwamedh Ghat. For the private motor boat, reporting time is 5:30 PM and the ride runs from 5:45 PM to 7:45 PM, so you're on the water — not fighting the crowd on the steps — for the full ceremony of fire, chants and diyas along the riverfront.</p>
        <div class="mb-seo-grid cols-3">
            <div class="mb-seo-block">
                <h3>Book Motor Boat for Ganga Aarti</h3>
                <p>A shared or private motor boat ride positioned on the river for the evening Ganga Aarti ceremony.</p>
                <a href="{{ $mbUrl('evening-boat-ride-in-varanasi') }}" class="mb-seo-link">Book Evening Boat Ride →</a>
            </div>
            <div class="mb-seo-block">
                <h3>Book Private Motor Boat for Evening Ganga Aarti</h3>
                <p>A no-sharing private motor boat for your group, with pickup from your choice of ghat.</p>
                <a href="{{ $mbUrl('private-motor-boat-for-evening-ganga-aarti') }}" class="mb-seo-link">Book Private Evening Boat →</a>
            </div>
            <div class="mb-seo-block">
                <h3>Best Ghats for Ganga Aarti Boat Pickup</h3>
                <p>Pickup is available from Assi Ghat, Namo Ghat, Dashashwamedh Ghat and Shivala Ghat, whichever is closest to you.</p>
                <a href="{{ $mbUrl('shivala-ghat-boat-booking-for-ganga-aarti-varanasi') }}" class="mb-seo-link">Book from Shivala Ghat →</a>
            </div>
        </div>

        {{-- H2 4: 84 Ghats Boat Ride --}}
        <h2>Varanasi 84 Ghats Boat Ride</h2>
        <p>The private motor boat route from Assi Ghat to Namo Ghat covers roughly 7 km of the Varanasi riverfront — all 84 ghats — compared to the shorter Assi Ghat to Manikarnika Ghat route of about 3.5 km covering around 50 ghats. Both morning and evening departures are available, and the full route is a good fit for families, couples and small groups who want to see the entire riverfront in one ride rather than a short section.</p>
        <div class="mb-seo-ghat-tags">
            <span class="mb-seo-ghat-tag">Assi Ghat</span>
            <span class="mb-seo-ghat-tag">Tulsi Ghat</span>
            <span class="mb-seo-ghat-tag">Shivala Ghat</span>
            <span class="mb-seo-ghat-tag">Harishchandra Ghat</span>
            <span class="mb-seo-ghat-tag">Kedar Ghat</span>
            <span class="mb-seo-ghat-tag">Dashashwamedh Ghat</span>
            <span class="mb-seo-ghat-tag">Manikarnika Ghat</span>
            <span class="mb-seo-ghat-tag">Rajendra Prasad Ghat</span>
            <span class="mb-seo-ghat-tag">Namo Ghat</span>
        </div>

        {{-- H2 5: Why book with Visit Kashi --}}
        <h2>Why Book Your Varanasi Boat Ride with Visit Kashi?</h2>
        <div class="mb-seo-grid cols-4">
            <div class="mb-seo-block"><h3>Verified Boat Services</h3><p>Every boat listed is checked and operated by an experienced local boatman.</p></div>
            <div class="mb-seo-block"><h3>Private Boat Options</h3><p>Choose a fully private motor boat when you don't want to share your ride.</p></div>
            <div class="mb-seo-block"><h3>Local Varanasi Team</h3><p>A Varanasi-based team that knows the ghats, the river and the Aarti timings.</p></div>
            <div class="mb-seo-block"><h3>Online Booking</h3><p>Check availability and start your booking on this page in a few minutes.</p></div>
            <div class="mb-seo-block"><h3>WhatsApp Confirmation</h3><p>Get your booking, reporting time and pickup ghat confirmed over WhatsApp.</p></div>
            <div class="mb-seo-block"><h3>Transparent Pricing</h3><p>Per-boat pricing shown upfront, with no hidden charges added later.</p></div>
            <div class="mb-seo-block"><h3>Customer Support</h3><p>Reach us by call or WhatsApp at +91-7080109917, 7080109918 or 7080109919.</p></div>
            <div class="mb-seo-block"><h3>Experienced Travel Company</h3><p>5+ years booking boat rides and river experiences on the Ganga in Varanasi.</p></div>
        </div>

        {{-- Internal links to related boat pages --}}
        <h2>Explore More Boat Options in Varanasi</h2>
        <div class="mb-seo-grid cols-4">
            <div class="mb-seo-block"><h3>Light Motor Boat</h3><p>A lighter motor boat option for smaller groups on the Ganga Aarti route.</p><a href="{{ $mbUrl('light-motor-boat-assi-ghat-boat-booking-for-ganga-aarti-varanasi') }}" class="mb-seo-link">View Light Motor Boat →</a></div>
            <div class="mb-seo-block"><h3>Bajra Boat Booking</h3><p>A traditional Bajra boat for a slower, more scenic ride on the river.</p><a href="{{ route('product.sub.list', ['boat', 'bajra-boat']) }}" class="mb-seo-link">View Bajra Boats →</a></div>
            <div class="mb-seo-block"><h3>Dev Diwali Boat Booking</h3><p>Book a boat for Dev Diwali, when the ghats are lit with lakhs of diyas.</p><a href="{{ route('product.sub.list', ['boat', 'dev-diwali-booking']) }}" class="mb-seo-link">View Dev Diwali Boats →</a></div>
            <div class="mb-seo-block"><h3>Cruise &amp; Event Boat Booking</h3><p>Larger cruise boats and decorated event boats for celebrations on the Ganga.</p><a href="{{ route('product.sub.list', ['boat', 'event-boat']) }}" class="mb-seo-link">View Event Boats →</a></div>
        </div>
    @endif

        {{-- FAQ — shared markup, sourced from whichever subcategory's FAQ array is active --}}
        @php $activeFaqs = $isMotorBoat ? $mbFaqs : ($isBajraBoat ? $bbFaqs : []); @endphp
        <h2 id="mb-faq">{{ $isBajraBoat ? 'Bajra Boat Booking – Frequently Asked Questions' : 'Varanasi Boat Booking – Frequently Asked Questions' }}</h2>
        <div class="mb-seo-faq">
            @foreach($activeFaqs as $faq)
            <div class="mb-seo-faq-item">
                <h3>{{ $faq['q'] }}</h3>
                <p>{{ $faq['a'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ── YouTube Shorts Section ── --}}
@php
    use App\Models\YoutubeVideo;
    $shortVideos = YoutubeVideo::active()->orderBy('sort_order')->orderByDesc('id')->limit(12)->get()->filter(fn($v) => $v->video_id)->values();
@endphp
@if($shortVideos->isNotEmpty())
<section class="bl-yt-section" aria-label="Boat ride videos">
    <div class="container">
        <div class="bl-yt-header">
            <div class="bl-yt-header-left">
                <div class="bl-yt-logo" aria-hidden="true">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none"><rect width="28" height="20" rx="4" fill="#FF0000"/><path d="M11 6l8 4-8 4V6z" fill="#fff"/></svg>
                </div>
                <div>
                    <h2 class="bl-yt-title">Boat Ride Videos</h2>
                    <p class="bl-yt-sub">Real experiences captured on the sacred Ganga river</p>
                </div>
            </div>
            <a href="https://www.youtube.com/@visitkashi" target="_blank" rel="noopener noreferrer" class="bl-yt-subscribe" aria-label="Subscribe to Visit Kashi on YouTube">
                <svg width="16" height="12" viewBox="0 0 28 20" fill="none"><rect width="28" height="20" rx="4" fill="#fff" fill-opacity=".3"/><path d="M11 6l8 4-8 4V6z" fill="#fff"/></svg>
                Subscribe
            </a>
        </div>

        <div class="bl-yt-slider-wrap">
            <button type="button" class="bl-yt-nav prev" id="blYtPrev" aria-label="Scroll videos left" disabled>&#8249;</button>
            <div class="bl-yt-grid" id="blYtTrack">
                @foreach($shortVideos as $vid)
                @php $vidTitle = $vid->title ?: 'Varanasi Boat Ride Video – Visit Kashi'; @endphp
                <a href="https://www.youtube.com/shorts/{{ $vid->video_id }}"
                   class="bl-yt-card"
                   target="_blank" rel="noopener noreferrer"
                   aria-label="Watch {{ e($vidTitle) }} on YouTube"
                   onclick="return blYtOpen(event, '{{ $vid->video_id }}', '{{ e($vidTitle) }}')">
                    <div class="bl-yt-thumb">
                        <img src="{{ $vid->thumbnail }}"
                             alt="{{ e($vidTitle) }} | Varanasi boat ride"
                             loading="lazy" width="300" height="533"
                             onerror="this.src='https://img.youtube.com/vi/{{ $vid->video_id }}/default.jpg'">
                        <div class="bl-yt-play-btn" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <span class="bl-yt-shorts-badge" aria-hidden="true">
                            <svg width="10" height="14" viewBox="0 0 10 14" fill="none"><path d="M5.8 0L0 7.5h4.2L4.2 14 10 6.5H5.8L5.8 0z" fill="#fff"/></svg>
                            Shorts
                        </span>
                    </div>
                    <p class="bl-yt-caption">{{ $vidTitle }}</p>
                </a>
                @endforeach
            </div>
            <button type="button" class="bl-yt-nav next" id="blYtNext" aria-label="Scroll videos right">&#8250;</button>
        </div>
    </div>
</section>

<div class="bl-yt-modal" id="blYtModal" role="dialog" aria-modal="true" aria-label="Video player">
    <div class="bl-yt-modal-box">
        <button class="bl-yt-modal-close" onclick="blYtClose()" aria-label="Close video">&#x2715;</button>
        <p class="bl-yt-modal-title" id="blYtModalTitle"></p>
        <div class="bl-yt-iframe-wrap">
            <iframe id="blYtIframe" src="" allowfullscreen frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture" title="YouTube video"></iframe>
        </div>
    </div>
</div>

{{-- JSON-LD: VideoObject list — mirrors the video cards above for Google video rich results --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'ItemList',
    'itemListElement' => $shortVideos->map(function($v, $i) {
        return [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'item'     => [
                '@type'        => 'VideoObject',
                'name'         => $v->title ?: 'Varanasi Boat Ride Video – Visit Kashi',
                'description'  => ($v->title ? $v->title . ' — ' : '') . 'Boat ride experience in Varanasi with Visit Kashi.',
                'thumbnailUrl' => [$v->thumbnail],
                'uploadDate'   => ($v->created_at ?? now())->toAtomString(),
                'contentUrl'   => $v->youtube_url,
                'embedUrl'     => $v->embed_url,
                'publisher'    => [
                    '@type' => 'Organization',
                    'name'  => 'Visit Kashi',
                    'logo'  => ['@type' => 'ImageObject', 'url' => asset('frontend/images/logo1.png')],
                ],
            ],
        ];
    })->values()->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif

{{-- ── Instagram Reels Section ── --}}
@php
    use App\Models\InstagramReel;
    $listingReels = InstagramReel::active()->orderBy('sort_order')->orderByDesc('id')->limit(5)->get();
    function isReelUrl($url) {
        return preg_match('#instagram\.com/(reel|p)/[a-zA-Z0-9_-]+#', $url);
    }
@endphp


<style>
.bl-yt-section{padding:52px 0 40px;background:#fff;border-top:1px solid #f0f0f0;}
.bl-yt-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;flex-wrap:wrap;gap:14px;}
.bl-yt-header-left{display:flex;align-items:center;gap:14px;}
.bl-yt-logo{width:48px;height:34px;display:flex;align-items:center;justify-content:center;background:#FF0000;border-radius:10px;flex-shrink:0;}
.bl-yt-title{font-size:1.28rem;font-weight:800;color:#111;margin:0 0 3px;}
.bl-yt-sub{font-size:.83rem;color:#888;margin:0;}
.bl-yt-subscribe{display:inline-flex;align-items:center;gap:7px;background:#FF0000;color:#fff;font-size:.83rem;font-weight:700;padding:9px 20px;border-radius:22px;text-decoration:none;transition:background .2s,transform .2s;white-space:nowrap;}
.bl-yt-subscribe:hover{background:#cc0000;transform:translateY(-2px);color:#fff;text-decoration:none;}
.bl-yt-slider-wrap{position:relative;}
.bl-yt-grid{display:flex;gap:12px;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:thin;scrollbar-color:#e2e8f0 transparent;padding-bottom:8px;scroll-padding-left:4px;}
.bl-yt-grid::-webkit-scrollbar{height:6px;}
.bl-yt-grid::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:10px;}
.bl-yt-card{flex:0 0 190px;scroll-snap-align:start;cursor:pointer;border-radius:14px;overflow:hidden;background:#000;box-shadow:0 3px 14px rgba(0,0,0,.12);transition:transform .3s,box-shadow .3s;display:block;text-decoration:none;color:inherit;}
.bl-yt-card:hover{transform:translateY(-5px) scale(1.02);box-shadow:0 12px 32px rgba(0,0,0,.22);text-decoration:none;color:inherit;}
.bl-yt-thumb{position:relative;aspect-ratio:9/16;overflow:hidden;}
.bl-yt-thumb img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s;}
.bl-yt-card:hover .bl-yt-thumb img{transform:scale(1.06);}
.bl-yt-play-btn{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:52px;height:52px;border-radius:50%;background:rgba(255,0,0,.85);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(255,0,0,.5);transition:transform .2s,background .2s;}
.bl-yt-card:hover .bl-yt-play-btn{transform:translate(-50%,-50%) scale(1.12);background:#ff0000;}
.bl-yt-shorts-badge{position:absolute;bottom:10px;left:10px;background:rgba(0,0,0,.7);color:#fff;font-size:.65rem;font-weight:700;padding:3px 8px;border-radius:4px;display:flex;align-items:center;gap:4px;letter-spacing:.04em;}
.bl-yt-caption{font-size:.78rem;font-weight:600;color:#222;padding:9px 10px 10px;margin:0;background:#fff;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.bl-yt-nav{position:absolute;top:calc(50% - 26px);width:40px;height:40px;border-radius:50%;background:#fff;border:1px solid #e5e7eb;box-shadow:0 4px 16px rgba(0,0,0,.16);color:#111;font-size:1.4rem;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:3;transition:opacity .2s,transform .2s;}
.bl-yt-nav:hover{transform:scale(1.06);}
.bl-yt-nav.prev{left:-14px;}
.bl-yt-nav.next{right:-14px;}
.bl-yt-nav[disabled]{opacity:0;pointer-events:none;}
.bl-yt-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:10000;align-items:center;justify-content:center;}
.bl-yt-modal.open{display:flex;}
.bl-yt-modal-box{position:relative;width:90vw;max-width:420px;}
.bl-yt-modal-close{position:absolute;top:-44px;right:0;background:rgba(255,255,255,.15);border:none;color:#fff;width:36px;height:36px;border-radius:50%;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;}
.bl-yt-modal-close:hover{background:rgba(255,255,255,.3);}
.bl-yt-modal-title{color:#fff;font-size:.88rem;font-weight:600;margin:0 0 10px;text-align:center;padding:0 36px;}
.bl-yt-iframe-wrap{position:relative;padding-bottom:177.78%;height:0;border-radius:12px;overflow:hidden;}
.bl-yt-iframe-wrap iframe{position:absolute;inset:0;width:100%;height:100%;}
@media(max-width:767px){.bl-yt-nav{display:none;}}
@media(max-width:640px){.bl-yt-card{flex-basis:150px;}.bl-yt-grid{gap:8px;}}

.bl-ig-section{padding:52px 0 44px;background:#fafafa;border-top:1px solid #efefef;}
.bl-ig-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:14px;}
.bl-ig-header-left{display:flex;align-items:center;gap:14px;}
.bl-ig-logo-wrap{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);flex-shrink:0;}
.bl-ig-title{font-size:1.3rem;font-weight:800;color:#111;margin:0 0 3px;}
.bl-ig-sub{font-size:.83rem;color:#888;margin:0;}
.bl-ig-handle{color:#dc2743;font-weight:600;text-decoration:none;}
.bl-ig-handle:hover{text-decoration:underline;}
.bl-ig-follow-btn{display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;font-size:.82rem;font-weight:700;padding:9px 20px;border-radius:22px;text-decoration:none;transition:transform .2s,opacity .2s;white-space:nowrap;}
.bl-ig-follow-btn:hover{transform:translateY(-2px);opacity:.92;color:#fff;text-decoration:none;}
.bl-ig-embeds{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;align-items:start;}
.bl-ig-embed-wrap{width:100%;}
.bl-ig-embed-wrap .instagram-media{min-width:0!important;width:100%!important;}
.bl-reel-thumb-card{display:block;text-decoration:none;border-radius:12px;overflow:hidden;box-shadow:0 3px 14px rgba(0,0,0,.12);transition:transform .25s;}
.bl-reel-thumb-card:hover{transform:translateY(-4px);text-decoration:none;}
.bl-reel-thumb-card img{width:100%;aspect-ratio:9/16;object-fit:cover;display:block;}
.bl-reel-thumb-placeholder{aspect-ratio:9/16;background:linear-gradient(135deg,#fff0f5,#ffecd2);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;}
.bl-reel-thumb-placeholder span{font-size:.78rem;font-weight:600;color:#dc2743;}
.bl-reel-thumb-title{font-size:.78rem;color:#555;padding:8px 10px;margin:0;background:#fff;}
.bl-ig-setup{text-align:center;padding:40px 20px;background:linear-gradient(135deg,#fff5f5,#fff0fa);border:1.5px dashed #f5b0c0;border-radius:16px;}
.bl-ig-setup-icon{margin:0 auto 14px;width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);display:flex;align-items:center;justify-content:center;}
.bl-ig-setup h3{font-size:1.1rem;font-weight:700;color:#1a1a1a;margin:0 0 8px;}
.bl-ig-setup p{font-size:.88rem;color:#666;margin:0;line-height:1.7;}
.bl-ig-footer{text-align:center;margin-top:24px;}
.bl-ig-view-all{display:inline-flex;align-items:center;gap:8px;font-size:.88rem;font-weight:700;color:#dc2743;text-decoration:none;border:1.5px solid #dc2743;border-radius:24px;padding:9px 24px;transition:background .2s,color .2s;}
.bl-ig-view-all:hover{background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);color:#fff;border-color:transparent;text-decoration:none;}
@media(max-width:1024px){.bl-ig-embeds{grid-template-columns:repeat(3,1fr);}}
@media(max-width:768px){.bl-ig-embeds{grid-template-columns:repeat(2,1fr);}.bl-ig-header{flex-direction:column;align-items:flex-start;}}
@media(max-width:480px){.bl-ig-embeds{grid-template-columns:1fr;}}
</style>
@endsection

@push('scripts')
<script>
@if($isDevDiwali)
window.ddCount = function(n) {
    var el = document.getElementById('ddPersons');
    if (!el) return;
    var v = Math.max(1, Math.min(200, (parseInt(el.value) || 1) + n));
    el.value = v;
    var minus = document.getElementById('ddPersonsMinus'), plus = document.getElementById('ddPersonsPlus');
    if (minus) minus.disabled = v <= 1;
    if (plus)  plus.disabled  = v >= 200;
};
window.ddChildCount = function(n) {
    var el = document.getElementById('ddChildren');
    if (!el) return;
    var v = Math.max(0, Math.min(20, (parseInt(el.value) || 0) + n));
    el.value = v;
    var minus = document.getElementById('ddChildrenMinus'), plus = document.getElementById('ddChildrenPlus');
    if (minus) minus.disabled = v <= 0;
    if (plus)  plus.disabled  = v >= 20;
};
@endif

(function () {

    /* ── YouTube popup ── */
    var ytModal  = document.getElementById('blYtModal');
    var ytIframe = document.getElementById('blYtIframe');
    var ytTitle  = document.getElementById('blYtModalTitle');
    window.blYtOpen = function(id, title) {
        if (!ytModal) return;
        ytIframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0&playsinline=1';
        if (ytTitle) ytTitle.textContent = title || '';
        ytModal.classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.blYtClose = function() {
        if (!ytModal) return;
        ytIframe.src = '';
        ytModal.classList.remove('open');
        document.body.style.overflow = '';
    };
    if (ytModal) {
        ytModal.addEventListener('click', function(e) { if (e.target === ytModal) blYtClose(); });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && ytModal && ytModal.classList.contains('open')) blYtClose();
    });

    /* ── Filter pills (functional) ── */
    var allCards   = document.querySelectorAll('.bl-card');
    var noResults  = document.getElementById('blNoResults');
    var countEl    = document.getElementById('blResultCount');

    window.blSetFilter = function(filter) {
        document.querySelectorAll('.bl-filter-pill').forEach(function(p){ p.classList.remove('active'); });
        var btn = document.querySelector('[data-filter="' + filter + '"]');
        if (btn) btn.classList.add('active');
        var visible = 0;
        allCards.forEach(function(card) {
            var keys = card.dataset.filter || 'all';
            var show = filter === 'all' || keys.includes(filter);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (noResults) noResults.style.display = visible === 0 ? '' : 'none';
        if (countEl) countEl.textContent = visible + ' ride' + (visible !== 1 ? 's' : '');
    };

    document.querySelectorAll('.bl-filter-pill').forEach(function(pill) {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            blSetFilter(this.dataset.filter || 'all');
        });
    });

    /* ── Heart toggle ── */
    document.querySelectorAll('.bl-heart').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            var saved = this.classList.toggle('saved');
            this.textContent = saved ? '♥' : '♡';
            this.style.color = saved ? '#e31c5f' : '';
        });
    });

    /* ── Image slider per card ── */
    document.querySelectorAll('.bl-img-wrap[data-total]').forEach(function(wrap) {
        var track   = wrap.querySelector('.bl-img-track');
        var prevBtn = wrap.querySelector('.bl-img-arrow.prev');
        var nextBtn = wrap.querySelector('.bl-img-arrow.next');
        var counter = wrap.querySelector('.bl-img-counter');
        var dots    = wrap.querySelectorAll('.bl-dot');
        var total   = parseInt(wrap.dataset.total) || 1;
        var cur     = 0;
        if (total <= 1 || !track) return;

        function go(idx) {
            cur = (idx + total) % total;
            track.style.transform = 'translateX(-' + (cur * 100) + '%)';
            if (counter) counter.textContent = (cur + 1) + ' / ' + total;
            dots.forEach(function(d, i) { d.classList.toggle('active', i === cur); });
        }

        if (prevBtn) prevBtn.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); go(cur - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); go(cur + 1); });

        var startX = 0;
        track.addEventListener('touchstart', function(e) { startX = e.touches[0].clientX; }, { passive: true });
        track.addEventListener('touchend', function(e) {
            var dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 40) go(dx < 0 ? cur + 1 : cur - 1);
        });
    });

})();
</script>
@endpush
