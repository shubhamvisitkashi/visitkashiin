@extends('frontend.layouts.app')

@section('meta')
<title>Contact Us | Visit Kashi – Varanasi Travel Experts</title>
<meta name="description" content="Get in touch with Visit Kashi for Varanasi tour packages, boat rides, hotel bookings, and cab services. We're here to help plan your perfect Kashi experience." />
<meta name="keywords" content="contact visit kashi, varanasi travel contact, book varanasi tour, visitkashi phone number, varanasi tour operator" />
<link rel="canonical" href="{{ url('/contact-us') }}" />
<meta property="og:title" content="Contact Visit Kashi – Varanasi Travel Experts" />
<meta property="og:description" content="Reach out to plan your Varanasi trip — boat rides, packages, hotels, and cab services." />
<meta property="og:url" content="{{ url('/contact-us') }}" />
<meta property="og:type" content="website" />
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact Visit Kashi",
  "url": "{{ url('/contact-us') }}",
  "mainEntity": {
    "@type": "TravelAgency",
    "name": "{{ websiteSetupValue('site_name') ?? 'Visit Kashi' }}",
    "telephone": "+91-{{ websiteSetupValue('contact_number') }}",
    "email": "{{ websiteSetupValue('email') }}",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Varanasi",
      "addressRegion": "Uttar Pradesh",
      "addressCountry": "IN"
    }
  }
}
</script>
@endsection

@push('styles')
<style>
/* ── Contact Page ── */
.vk-contact-hero {
    background: linear-gradient(135deg, #1A2B4C 0%, #0d1420 100%);
    padding: 72px 0 48px;
    text-align: center;
    color: #fff;
}
.vk-contact-hero h1 {
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    margin: 0 0 10px;
    letter-spacing: -.02em;
}
.vk-contact-hero p {
    font-size: 16px;
    color: rgba(255,255,255,.72);
    margin: 0 auto;
    max-width: 520px;
}
.vk-contact-breadcrumb {
    margin: 0 0 18px;
    font-size: 13px;
    color: rgba(255,255,255,.55);
}
.vk-contact-breadcrumb a {
    color: rgba(255,255,255,.7);
}
.vk-contact-breadcrumb span { margin: 0 6px; }

/* Layout */
.vk-contact-section {
    padding: 64px 0 80px;
    background: #f8f9fa;
}
.vk-contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 40px;
    align-items: start;
}
@media(max-width:768px) {
    .vk-contact-grid { grid-template-columns: 1fr; }
}

/* Info cards */
.vk-contact-info { display: flex; flex-direction: column; gap: 20px; }
.vk-contact-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    display: flex;
    gap: 16px;
    align-items: flex-start;
}
.vk-contact-card__icon {
    width: 48px;
    height: 48px;
    background: #fff5f2;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 20px;
    color: #D94F2B;
}
.vk-contact-card__body h3 {
    font-size: 15px;
    font-weight: 700;
    color: #1A2B4C;
    margin: 0 0 4px;
}
.vk-contact-card__body p,
.vk-contact-card__body a {
    font-size: 14px;
    color: #555;
    margin: 0;
    line-height: 1.6;
}
.vk-contact-card__body a:hover { color: #D94F2B; }

.vk-contact-social { display: flex; gap: 10px; margin-top: 8px; }
.vk-contact-social a {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f0f2f5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1A2B4C;
    font-size: 15px;
    transition: background .2s, color .2s;
}
.vk-contact-social a:hover { background: #D94F2B; color: #fff; }

/* Form */
.vk-contact-form-wrap {
    background: #fff;
    border-radius: 20px;
    padding: 36px 32px;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
}
.vk-contact-form-wrap h2 {
    font-size: 22px;
    font-weight: 700;
    color: #1A2B4C;
    margin: 0 0 6px;
}
.vk-contact-form-wrap > p {
    font-size: 14px;
    color: #666;
    margin: 0 0 24px;
}
.vk-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media(max-width:576px) { .vk-form-row { grid-template-columns: 1fr; } }
.vk-form-group { margin-bottom: 16px; }
.vk-form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
}
.vk-form-group input,
.vk-form-group select,
.vk-form-group textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    color: #333;
    background: #fafafa;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    font-family: inherit;
}
.vk-form-group input:focus,
.vk-form-group select:focus,
.vk-form-group textarea:focus {
    border-color: #D94F2B;
    box-shadow: 0 0 0 3px rgba(217,79,43,.1);
    background: #fff;
}
.vk-form-group textarea { resize: vertical; min-height: 110px; }

.vk-contact-submit {
    width: 100%;
    padding: 14px;
    background: #D94F2B;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s, transform .15s;
    margin-top: 4px;
}
.vk-contact-submit:hover {
    background: #bf3e1e;
    transform: translateY(-1px);
}

/* Map */
.vk-contact-map {
    margin-top: 56px;
}
.vk-contact-map h2 {
    font-size: 22px;
    font-weight: 700;
    color: #1A2B4C;
    margin: 0 0 16px;
    text-align: center;
}
.vk-contact-map iframe {
    width: 100%;
    height: 380px;
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,.1);
    display: block;
}

/* Alert */
.vk-alert-success {
    background: #d1fae5;
    border: 1px solid #6ee7b7;
    color: #065f46;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 500;
}
.vk-alert-error {
    background: #fee2e2;
    border: 1px solid #fca5a5;
    color: #991b1b;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 500;
}

/* WhatsApp CTA */
.vk-contact-whatsapp {
    background: #25D366;
    color: #fff;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none !important;
    transition: background .2s, transform .15s;
}
.vk-contact-whatsapp:hover { background: #128C7E; transform: translateY(-2px); color: #fff; }
.vk-contact-whatsapp i { font-size: 24px; }
.vk-contact-whatsapp span { font-size: 14px; font-weight: 600; line-height: 1.4; }
.vk-contact-whatsapp small { display: block; font-weight: 400; opacity: .85; font-size: 12px; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="vk-contact-hero">
    <div class="container">
        <nav class="vk-contact-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Contact Us</span>
        </nav>
        <h1>Get in Touch with Us</h1>
        <p>Planning a Varanasi trip? We'd love to help you create an unforgettable experience.</p>
    </div>
</section>

{{-- Main Content --}}
<section class="vk-contact-section">
    <div class="container">
        <div class="vk-contact-grid">

            {{-- Left: Contact Info --}}
            <div class="vk-contact-info">

                {{-- Address --}}
                <div class="vk-contact-card">
                    <div class="vk-contact-card__icon">
                        <i class="flaticon-maps-and-flags" aria-hidden="true"></i>
                    </div>
                    <div class="vk-contact-card__body">
                        <h3>Our Office</h3>
                        <p>{{ websiteSetupValue('address') }}</p>
                        <p>Varanasi, Uttar Pradesh – India</p>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="vk-contact-card">
                    <div class="vk-contact-card__icon">
                        <i class="flaticon-phone-call" aria-hidden="true"></i>
                    </div>
                    <div class="vk-contact-card__body">
                        <h3>Call / WhatsApp</h3>
                        <a href="tel:+91{{ websiteSetupValue('contact_number') }}">+91-{{ websiteSetupValue('contact_number') }}</a><br>
                        <a href="tel:+917080109918">+91-7080109918</a><br>
                        <a href="tel:+917080109919">+91-7080109919</a>
                    </div>
                </div>

                {{-- Email --}}
                <div class="vk-contact-card">
                    <div class="vk-contact-card__icon">
                        <i class="flaticon-mail" aria-hidden="true"></i>
                    </div>
                    <div class="vk-contact-card__body">
                        <h3>Email Us</h3>
                        @php $contactEmail = websiteSetupValue('email'); @endphp
                        <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        <p style="margin-top:4px;">We respond within 24 hours</p>
                    </div>
                </div>

                {{-- WhatsApp quick link --}}
                <a href="https://wa.me/91{{ websiteSetupValue('contact_number') }}?text=Hi%20Visit%20Kashi%2C%20I%20want%20to%20book%20a%20tour%20to%20Varanasi"
                   target="_blank" rel="noopener noreferrer"
                   class="vk-contact-whatsapp"
                   aria-label="Chat with Visit Kashi on WhatsApp">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                    <span>Chat on WhatsApp <small>Fastest way to reach us</small></span>
                </a>

                {{-- Social --}}
                <div class="vk-contact-card">
                    <div class="vk-contact-card__icon">
                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                    </div>
                    <div class="vk-contact-card__body">
                        <h3>Follow Us</h3>
                        <p>Stay updated with travel tips &amp; offers</p>
                        <div class="vk-contact-social">
                            <a href="https://www.facebook.com/visitkashiofficial/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                            <a href="https://www.instagram.com/visitkashi/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
                            <a href="https://twitter.com/visit_kashi" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.youtube.com/@visitkashi" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Contact Form --}}
            <div class="vk-contact-form-wrap">
                <h2>Send Us a Message</h2>
                <p>Fill in the form and our team will get back to you shortly.</p>

                @if(session('contact_success'))
                    <div class="vk-alert-success" role="alert">
                        <i class="fa fa-check-circle"></i> {{ session('contact_success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="vk-alert-error" role="alert">
                        <i class="fa fa-exclamation-circle"></i>
                        @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="vk-form-row">
                        <div class="vk-form-group">
                            <label for="c_name">Full Name <span style="color:#D94F2B">*</span></label>
                            <input type="text" id="c_name" name="name" placeholder="Your name"
                                   value="{{ old('name') }}" required maxlength="100" />
                        </div>
                        <div class="vk-form-group">
                            <label for="c_phone">Phone / WhatsApp <span style="color:#D94F2B">*</span></label>
                            <input type="tel" id="c_phone" name="phone" placeholder="+91 XXXXX XXXXX"
                                   value="{{ old('phone') }}" required maxlength="15" />
                        </div>
                    </div>

                    <div class="vk-form-group">
                        <label for="c_email">Email Address</label>
                        <input type="email" id="c_email" name="email" placeholder="your@email.com"
                               value="{{ old('email') }}" maxlength="150" />
                    </div>

                    <div class="vk-form-group">
                        <label for="c_subject">I'm interested in</label>
                        <select id="c_subject" name="subject">
                            <option value="">— Select a topic —</option>
                            <option value="Tour Packages" {{ old('subject') == 'Tour Packages' ? 'selected' : '' }}>Tour Packages</option>
                            <option value="Boat Rides" {{ old('subject') == 'Boat Rides' ? 'selected' : '' }}>Boat Rides</option>
                            <option value="Hotel Booking" {{ old('subject') == 'Hotel Booking' ? 'selected' : '' }}>Hotel Booking</option>
                            <option value="Cab / Transportation" {{ old('subject') == 'Cab / Transportation' ? 'selected' : '' }}>Cab / Transportation</option>
                            <option value="General Enquiry" {{ old('subject') == 'General Enquiry' ? 'selected' : '' }}>General Enquiry</option>
                        </select>
                    </div>

                    <div class="vk-form-group">
                        <label for="c_message">Message <span style="color:#D94F2B">*</span></label>
                        <textarea id="c_message" name="message" placeholder="Tell us about your travel plans, dates, number of people, or any specific requirements…" required maxlength="1000">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="vk-contact-submit">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp; Send Message
                    </button>
                </form>
            </div>

        </div>

        {{-- Map --}}
        <div class="vk-contact-map">
            <h2>Find Us in Varanasi</h2>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3607.3!2d82.9893146!3d25.30033!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x398e320074cfe905%3A0x8de866595a972a69!2sVisit%20Kashi!5e0!3m2!1sen!2sin!4v1"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Visit Kashi Office Location in Varanasi"
                aria-label="Google Map showing Visit Kashi location in Varanasi">
            </iframe>
        </div>

    </div>
</section>

@endsection
