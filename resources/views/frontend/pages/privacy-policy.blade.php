@extends('frontend.layouts.app')

@section('meta')
<title>Privacy Policy | Visit Kashi – Varanasi Travel Platform</title>
<meta name="description" content="Read Visit Kashi's Privacy Policy to understand how we collect, use, and protect your personal information when you book tours, boat rides, and hotels in Varanasi." />
<link rel="canonical" href="{{ url('/privacy-policy') }}" />
<meta property="og:title" content="Privacy Policy | Visit Kashi" />
<meta property="og:url" content="{{ url('/privacy-policy') }}" />
@endsection

@include('frontend.pages._layout')

@section('content')

<section class="vk-page-hero">
    <div class="container">
        <nav class="vk-page-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span>/</span>
            <span aria-current="page">Privacy Policy</span>
        </nav>
        <h1>Privacy Policy</h1>
        <p>How we collect, use, and protect your personal information.</p>
    </div>
</section>

<div class="vk-page-wrap">
    <div class="container">
        <article class="vk-page-card" itemscope itemtype="https://schema.org/WebPage">

            <div class="vk-page-meta">
                <span><i class="fa fa-calendar" aria-hidden="true"></i> Last updated: May 2025</span>
                <span><i class="fa fa-map-marker" aria-hidden="true"></i> Applicable in India</span>
            </div>

            <div class="vk-info-box">
                <p>Visit Kashi ("we", "our", "us") is committed to protecting your privacy. This policy explains how we handle your data when you use our website or services. By using visitkashi.in, you agree to this policy.</p>
            </div>

            <h2>1. Information We Collect</h2>
            <h3>Information you provide directly</h3>
            <ul>
                <li>Name, phone number, and email address when you make a booking or enquiry</li>
                <li>Travel dates, group size, and special requirements you share with us</li>
                <li>Payment details processed securely through our payment gateway partners</li>
            </ul>
            <h3>Information collected automatically</h3>
            <ul>
                <li>Browser type, device type, and IP address</li>
                <li>Pages visited and time spent on the site (via Google Analytics)</li>
                <li>Cookies and similar tracking technologies</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <ul>
                <li>To process and confirm your bookings for tours, boat rides, hotels, and cabs</li>
                <li>To contact you with booking confirmations, updates, and itinerary details</li>
                <li>To respond to your enquiries and provide customer support</li>
                <li>To send promotional offers and travel tips (only with your consent; you can opt out anytime)</li>
                <li>To improve our website and service quality through analytics</li>
                <li>To comply with legal obligations</li>
            </ul>

            <h2>3. Sharing of Information</h2>
            <p>We do <strong>not</strong> sell or rent your personal data to third parties. We may share your information only in these circumstances:</p>
            <ul>
                <li><strong>Service partners:</strong> Hotels, transport operators, and boat operators who need your details to deliver the booked service</li>
                <li><strong>Payment processors:</strong> Secure gateway partners for completing transactions</li>
                <li><strong>Legal requirements:</strong> When required by law, court order, or government authority</li>
            </ul>

            <h2>4. Cookies</h2>
            <p>We use cookies to remember your preferences, analyse traffic, and deliver relevant content. You can control cookies through your browser settings. Disabling cookies may affect some website features.</p>
            <p>We use Google Analytics (with IP anonymisation enabled) to understand how visitors use our site.</p>

            <h2>5. Data Security</h2>
            <p>We implement industry-standard security measures including SSL encryption, secure servers, and access controls to protect your personal data. However, no internet transmission is 100% secure, and we encourage you to take precautions when sharing sensitive information online.</p>

            <h2>6. Data Retention</h2>
            <p>We retain your personal data for as long as necessary to fulfil the purposes described in this policy, or as required by law. Booking records are retained for a minimum of 3 years for accounting and legal compliance purposes.</p>

            <h2>7. Your Rights</h2>
            <p>Under applicable data protection law, you have the right to:</p>
            <ul>
                <li>Access the personal data we hold about you</li>
                <li>Request correction of inaccurate data</li>
                <li>Request deletion of your data (subject to legal obligations)</li>
                <li>Withdraw consent for marketing communications at any time</li>
            </ul>
            <p>To exercise any of these rights, contact us at <a href="mailto:{{ optional(App\Models\WebsiteSetup::where('name','email')->first())->value }}">{{ optional(App\Models\WebsiteSetup::where('name','email')->first())->value }}</a>.</p>

            <h2>8. Third-Party Links</h2>
            <p>Our website may contain links to third-party websites (e.g., hotel partners, payment gateways). We are not responsible for the privacy practices of these sites and encourage you to review their privacy policies.</p>

            <h2>9. Children's Privacy</h2>
            <p>Our services are not directed at children under the age of 18. We do not knowingly collect personal information from minors. If you believe a minor has submitted data to us, please contact us and we will delete it promptly.</p>

            <h2>10. Changes to This Policy</h2>
            <p>We may update this Privacy Policy from time to time. The latest version will always be available on this page with the revision date. Continued use of our website after changes constitutes acceptance of the updated policy.</p>

            <h2>11. Contact Us</h2>
            <p>For any privacy-related questions or requests, please reach out:</p>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:{{ optional(App\Models\WebsiteSetup::where('name','email')->first())->value }}">{{ optional(App\Models\WebsiteSetup::where('name','email')->first())->value }}</a></li>
                <li><strong>Phone:</strong> +91-{{ websiteSetupValue('contact_number') }}</li>
                <li><strong>Address:</strong> {{ websiteSetupValue('address') }}, Varanasi, Uttar Pradesh, India</li>
            </ul>

        </article>
    </div>
</div>

@endsection
