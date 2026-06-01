@extends('frontend.layouts.app')

@section('meta')
<title>Cancellation & Refund Policy | Visit Kashi – Varanasi Tours</title>
<meta name="description" content="Read Visit Kashi's Cancellation and Refund Policy. Understand cancellation timelines, refund amounts, and how to cancel your Varanasi tour, boat ride, or hotel booking." />
<link rel="canonical" href="{{ url('/cancellation-refund') }}" />
<meta property="og:title" content="Cancellation & Refund Policy | Visit Kashi" />
<meta property="og:url" content="{{ url('/cancellation-refund') }}" />
@endsection

@include('frontend.pages._layout')

@section('content')

<section class="vk-page-hero">
    <div class="container">
        <nav class="vk-page-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span>/</span>
            <span aria-current="page">Cancellation &amp; Refund Policy</span>
        </nav>
        <h1>Cancellation &amp; Refund Policy</h1>
        <p>Clear, fair policies so you can book with confidence.</p>
    </div>
</section>

<div class="vk-page-wrap">
    <div class="container">
        <article class="vk-page-card">

            <div class="vk-page-meta">
                <span><i class="fa fa-calendar" aria-hidden="true"></i> Last updated: May 2025</span>
            </div>

            <div class="vk-info-box">
                <p>We understand that travel plans can change. Our cancellation policy is designed to be fair to both our guests and our service partners. Please read this carefully before making a booking.</p>
            </div>

            <h2>1. Tour Packages &amp; Experiences</h2>
            <p>The following refund schedule applies to all tour packages, guided experiences, and activity bookings:</p>

            <table class="vk-refund-table" aria-label="Tour package cancellation refund schedule">
                <thead>
                    <tr>
                        <th>Cancellation Notice</th>
                        <th>Refund Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>15 days or more before travel date</td>
                        <td class="highlight">100% refund</td>
                    </tr>
                    <tr>
                        <td>7 – 14 days before travel date</td>
                        <td class="highlight">75% refund</td>
                    </tr>
                    <tr>
                        <td>3 – 6 days before travel date</td>
                        <td class="highlight">50% refund</td>
                    </tr>
                    <tr>
                        <td>1 – 2 days before travel date</td>
                        <td class="highlight">25% refund</td>
                    </tr>
                    <tr>
                        <td>Same day or no-show</td>
                        <td class="highlight">No refund</td>
                    </tr>
                </tbody>
            </table>

            <h2>2. Boat Ride Bookings</h2>
            <p>Boat rides are subject to weather and river conditions. The following policy applies:</p>
            <table class="vk-refund-table" aria-label="Boat ride cancellation refund schedule">
                <thead>
                    <tr>
                        <th>Cancellation Notice</th>
                        <th>Refund Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>48+ hours before departure</td>
                        <td class="highlight">100% refund</td>
                    </tr>
                    <tr>
                        <td>24 – 48 hours before departure</td>
                        <td class="highlight">50% refund</td>
                    </tr>
                    <tr>
                        <td>Less than 24 hours / no-show</td>
                        <td class="highlight">No refund</td>
                    </tr>
                    <tr>
                        <td>Cancelled by us due to weather / river conditions</td>
                        <td class="highlight">100% refund or free rescheduling</td>
                    </tr>
                </tbody>
            </table>

            <h2>3. Hotel Bookings</h2>
            <p>Hotel cancellation policies vary by property. The specific cancellation terms applicable to your booking will be communicated at the time of confirmation. General guidelines:</p>
            <ul>
                <li>Cancellations made 72+ hours before check-in: typically full refund (minus processing fee)</li>
                <li>Cancellations within 72 hours: subject to first-night charge or property-specific policy</li>
                <li>No-shows: typically charged for the full booking duration</li>
            </ul>
            <p>Please check your booking confirmation email for the exact cancellation terms for your hotel.</p>

            <h2>4. Cab Bookings</h2>
            <ul>
                <li>Cancellations made 24+ hours before pick-up: 100% refund</li>
                <li>Cancellations within 24 hours of pick-up: 50% refund</li>
                <li>No-show or cancellation after driver dispatch: No refund</li>
            </ul>

            <h2>5. Festival &amp; Special Event Bookings</h2>
            <div class="vk-info-box">
                <p><strong>Note:</strong> Bookings made for festival periods (Dev Deepawali, Maha Shivratri, Holi, etc.) are non-refundable once confirmed, due to high demand and advance vendor commitments. We strongly recommend travel insurance for festival bookings.</p>
            </div>

            <h2>6. How to Cancel</h2>
            <p>To initiate a cancellation, please contact us through any of the following methods:</p>
            <ul>
                <li><strong>Phone / WhatsApp:</strong> <a href="tel:+91{{ websiteSetupValue('contact_number') }}">+91-{{ websiteSetupValue('contact_number') }}</a> or +91-7080109918</li>
                <li><strong>Email:</strong> <a href="mailto:{{ optional(App\Models\WebsiteSetup::where('name','email')->first())->value }}">{{ optional(App\Models\WebsiteSetup::where('name','email')->first())->value }}</a></li>
                <li><strong>Contact form:</strong> <a href="{{ route('contact.show') }}">Visit our Contact page</a></li>
            </ul>
            <p>Please provide your booking reference number, name, and travel date when requesting a cancellation. Cancellation requests are processed within 2 business days.</p>

            <h2>7. Refund Processing</h2>
            <ul>
                <li>Approved refunds are processed within <strong>5–7 business days</strong></li>
                <li>Refunds are credited to the original payment method (bank card, UPI, or wallet)</li>
                <li>Bank processing times may add an additional 3–5 days depending on your bank</li>
                <li>Cash payments are refunded via bank transfer (provide account details at time of cancellation)</li>
            </ul>

            <h2>8. Modifications &amp; Rescheduling</h2>
            <p>We are happy to help you reschedule your booking subject to availability. Rescheduling requests made 48+ hours before the original travel date are free of charge. Last-minute rescheduling may be treated as a cancellation and re-booking.</p>

            <h2>9. Force Majeure</h2>
            <p>In cases of cancellation due to natural disasters, government-mandated travel restrictions, or other events beyond our control, we will offer full credit valid for 12 months or a refund at our discretion. We are not liable for any additional costs (flights, accommodation, etc.) incurred due to such events.</p>

            <h2>10. Questions?</h2>
            <p>If you have any questions about our cancellation policy, please <a href="{{ route('contact.show') }}">contact us</a> — we're here to help.</p>

        </article>
    </div>
</div>

@endsection
