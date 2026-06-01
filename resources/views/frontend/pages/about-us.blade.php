@extends('frontend.layouts.app')

@section('meta')
<title>About Visit Kashi – Varanasi's Most Trusted Travel Company</title>
<meta name="description" content="Visit Kashi is Varanasi's most trusted travel company, founded by Shubham Mishra. Built on the ghats, powered by 5+ years of ground-level experience in tours, boat rides, hotels, and cabs." />
<link rel="canonical" href="{{ url('/about-us') }}" />
<meta property="og:title" content="About Visit Kashi – Varanasi's Most Trusted Travel Company" />
<meta property="og:description" content="Founded by Shubham Mishra, Visit Kashi was built on the ghats of Varanasi after 5+ years of real ground-level experience serving thousands of guests." />
<meta property="og:url" content="{{ url('/about-us') }}" />
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "About Visit Kashi",
  "url": "{{ url('/about-us') }}",
  "mainEntity": {
    "@type": "TravelAgency",
    "name": "Visit Kashi",
    "foundingDate": "2018",
    "founder": {
      "@type": "Person",
      "name": "Shubham Mishra",
      "jobTitle": "Founder",
      "alumniOf": { "@type": "CollegeOrUniversity", "name": "SMS Varanasi" }
    },
    "description": "Visit Kashi is Varanasi's most trusted travel company offering curated tour packages, boat rides, hotel bookings, and cab services — built by locals, for travellers.",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Varanasi",
      "addressRegion": "Uttar Pradesh",
      "addressCountry": "IN"
    },
    "areaServed": "Varanasi"
  }
}
</script>
@endsection

@include('frontend.pages._layout')

@push('styles')
<style>
/* ── Founder card ── */
.vk-founder {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 0;
    align-items: stretch;
    background: linear-gradient(135deg,#1A2B4C 0%,#0d1420 100%);
    border-radius: 20px;
    overflow: hidden;
    margin: 8px 0 32px;
    color: #fff;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
}
@media(max-width:768px){
    .vk-founder { grid-template-columns: 1fr; }
    .vk-founder-avatar { height: 320px; order: -1; }
}
.vk-founder-info {
    padding: 36px 36px 36px 36px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.vk-founder-avatar {
    width: 100%;
    height: 100%;
    min-height: 340px;
    border-radius: 0 15px 15px 0;
    overflow: hidden;
    background: #0d1420;
    flex-shrink: 0;
}
.vk-founder-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    border-radius: 0 15px 15px 0;
}
@media(max-width:768px){
    .vk-founder-avatar,
    .vk-founder-avatar img { border-radius: 0; }
}
.vk-founder-info h3 {
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
    border: none;
    padding: 0;
}
.vk-founder-info .vk-founder-role {
    font-size: 13px;
    color: #F5A623;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 14px;
    display: block;
}
.vk-founder-info p {
    font-size: 14px;
    color: rgba(255,255,255,.75);
    line-height: 1.7;
    margin: 0 0 10px;
}
.vk-founder-info p:last-child { margin: 0; }
.vk-founder-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 14px;
}
@media(max-width:600px){ .vk-founder-tags { justify-content:center; } }
.vk-founder-tag {
    font-size: 12px;
    font-weight: 600;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    color: rgba(255,255,255,.85);
    padding: 4px 12px;
    border-radius: 50px;
}

/* ── Origin story timeline ── */
.vk-timeline { margin: 8px 0 0; }
.vk-timeline-item {
    display: grid;
    grid-template-columns: 44px 1fr;
    gap: 0 18px;
    margin-bottom: 8px;
}
.vk-timeline-left {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.vk-timeline-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #D94F2B;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #D94F2B;
    flex-shrink: 0;
    margin-top: 4px;
}
.vk-timeline-line {
    width: 2px;
    flex: 1;
    background: linear-gradient(#D94F2B, #f0f0f0);
    margin-top: 6px;
    min-height: 32px;
}
.vk-timeline-item:last-child .vk-timeline-line { display:none; }
.vk-timeline-content { padding-bottom: 24px; }
.vk-timeline-content h4 {
    font-size: 14px;
    font-weight: 700;
    color: #1A2B4C;
    margin: 0 0 4px;
}
.vk-timeline-content p {
    font-size: 14px;
    color: #666;
    line-height: 1.65;
    margin: 0;
}

/* ── Coordinator mention ── */
.vk-coordinator {
    background: #fff8f2;
    border: 1px solid #fdd5c8;
    border-radius: 12px;
    padding: 18px 22px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    margin: 24px 0;
}
.vk-coordinator i {
    font-size: 22px;
    color: #D94F2B;
    flex-shrink: 0;
    margin-top: 2px;
}
.vk-coordinator p { margin: 0; font-size: 14.5px; color: #555; line-height: 1.7; }
.vk-coordinator strong { color: #1A2B4C; }

/* ── Tagline closer ── */
.vk-tagline-close {
    text-align: center;
    padding: 28px 0 8px;
    border-top: 2px solid #f0f2f5;
    margin-top: 36px;
}
.vk-tagline-close p {
    font-size: 17px;
    font-weight: 700;
    color: #1A2B4C;
    letter-spacing: -.01em;
    margin: 0;
}
.vk-tagline-close span { color: #D94F2B; }
</style>
@endpush

@section('content')

<section class="vk-page-hero">
    <div class="container">
        <nav class="vk-page-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span>/</span>
            <span aria-current="page">About Us</span>
        </nav>
        <h1>Visit Kashi – Varanasi's Most Trusted Travel Company</h1>
        <p>Built on the ghats. Powered by passion. Trusted by thousands.</p>
    </div>
</section>

<div class="vk-page-wrap">
    <div class="container">
        <article class="vk-page-card">

            {{-- Opening statement --}}
            <h2>Who We Are</h2>
            <div class="vk-info-box">
                <p>Visit Kashi is more than just a travel company — it is the result of passion, dedication, and years of real ground-level experience in the spiritual city of Varanasi.</p>
            </div>

            {{-- Founder section --}}
            <h2>Meet the Founder</h2>
            <div class="vk-founder" itemscope itemtype="https://schema.org/Person">
                {{-- Text — LEFT --}}
                <div class="vk-founder-info">
                    <h3 itemprop="name">Shubham Mishra</h3>
                    <span class="vk-founder-role" itemprop="jobTitle">Founder, Visit Kashi</span>
                    <p>Founded Visit Kashi after years of understanding the true needs of travellers visiting Varanasi — from completing his <strong style="color:#fff">BCA from SMS Varanasi</strong> to working hands-on in the hotel industry as a receptionist, building every piece of this company from the ground up.</p>
                    <p>For over 5 years, Shubham personally handled on-ground operations, managed travel experiences, resolved real-time guest issues, and built a trusted network of local vendors, boatmen, drivers, and hotels across Varanasi.</p>
                    <div class="vk-founder-tags">
                        <span class="vk-founder-tag"><i class="fa fa-graduation-cap"></i> BCA – SMS Varanasi</span>
                        <span class="vk-founder-tag"><i class="fa fa-map-marker"></i> Varanasi Native</span>
                        <span class="vk-founder-tag"><i class="fa fa-clock-o"></i> 5+ Years Experience</span>
                        <span class="vk-founder-tag"><i class="fa fa-users"></i> 10,000+ Guests Served</span>
                    </div>
                </div>
                {{-- Photo — RIGHT --}}
                <div class="vk-founder-avatar" aria-label="Shubham Mishra, Founder of Visit Kashi">
                    <img src="{{ asset('frontend/images/founder-shubham-mishra.jpg') }}"
                         alt="Shubham Mishra – Founder, Visit Kashi"
                         itemprop="image" />
                </div>
            </div>

            {{-- Journey timeline --}}
            <h2>Our Journey</h2>
            <div class="vk-timeline">
                <div class="vk-timeline-item">
                    <div class="vk-timeline-left"><div class="vk-timeline-dot"></div><div class="vk-timeline-line"></div></div>
                    <div class="vk-timeline-content">
                        <h4>Education &amp; Early Steps</h4>
                        <p>After completing his Bachelor of Computer Applications (BCA) from SMS Varanasi, Shubham joined the hotel industry as a receptionist. Those six months were a masterclass in guest expectations, hospitality standards, and the real challenges travellers face in an unfamiliar city.</p>
                    </div>
                </div>
                <div class="vk-timeline-item">
                    <div class="vk-timeline-left"><div class="vk-timeline-dot"></div><div class="vk-timeline-line"></div></div>
                    <div class="vk-timeline-content">
                        <h4>Born on the Ghats, Not in an Office</h4>
                        <p>The foundation of Visit Kashi was not built inside a boardroom — it was built on the ghats of Varanasi, in the narrow lanes of the old city, through continuous coordination with local vendors, boatmen, drivers, hotels, and thousands of guests over five years of ground-level work.</p>
                    </div>
                </div>
                <div class="vk-timeline-item">
                    <div class="vk-timeline-left"><div class="vk-timeline-dot"></div><div class="vk-timeline-line"></div></div>
                    <div class="vk-timeline-content">
                        <h4>Building the Network</h4>
                        <p>From sunrise boat rides on the Ganga to spiritual tours, cab services, Ganga Aarti arrangements, and hotel stays — every service at Visit Kashi was shaped by direct experience, real guest feedback, and an obsession with quality and reliability.</p>
                    </div>
                </div>
                <div class="vk-timeline-item">
                    <div class="vk-timeline-left"><div class="vk-timeline-dot"></div><div class="vk-timeline-line"></div></div>
                    <div class="vk-timeline-content">
                        <h4>The Name Behind the Brand</h4>
                        <p>The name <strong>"Visit Kashi"</strong> was suggested by coordinator <strong>Mr. Rajiv Katare</strong>, whose support and guidance played an important role during the company's early journey — a reminder that great things are always built together.</p>
                    </div>
                </div>
                <div class="vk-timeline-item">
                    <div class="vk-timeline-left"><div class="vk-timeline-dot"></div><div class="vk-timeline-line"></div></div>
                    <div class="vk-timeline-content">
                        <h4>Varanasi's Most Trusted Travel Company</h4>
                        <p>Today, Visit Kashi proudly serves travellers from across India and around the world — with reliable services, deep local knowledge, and the kind of genuine hospitality that only comes from people who truly love their city.</p>
                    </div>
                </div>
            </div>

            {{-- Coordinator acknowledgement --}}
            <div class="vk-coordinator">
                <i class="fa fa-quote-left" aria-hidden="true"></i>
                <p>A special acknowledgement to <strong>Mr. Rajiv Katare</strong>, whose suggestion of the name <strong>"Visit Kashi"</strong> and early guidance helped shape the identity and direction of the company during its most formative period.</p>
            </div>

            {{-- Mission --}}
            <h2>Our Mission</h2>
            <div class="vk-info-box">
                <p><em>"To make every visitor's Kashi experience safe, soulful, and unforgettable — by combining local expertise with modern convenience."</em></p>
            </div>
            <p>We believe Varanasi is not just a destination — it is an experience that touches the soul. Our mission is to ensure every traveller, whether a first-time visitor or a returning pilgrim, leaves with memories they will carry for a lifetime.</p>

            {{-- What we offer --}}
            <h2>What We Offer</h2>
            <div class="vk-about-values">
                <div class="vk-about-value">
                    <i class="fa fa-ship" aria-hidden="true"></i>
                    <div>
                        <h4>Ganga Boat Rides</h4>
                        <p>Dawn Aarti rides, evening Ganga Aarti cruises, sunrise tours, and private charters with certified boatmen.</p>
                    </div>
                </div>
                <div class="vk-about-value">
                    <i class="fa fa-map" aria-hidden="true"></i>
                    <div>
                        <h4>Tour Packages</h4>
                        <p>Curated itineraries covering temples, ghats, cuisine trails, and spiritual experiences — from day trips to week-long journeys.</p>
                    </div>
                </div>
                <div class="vk-about-value">
                    <i class="fa fa-bed" aria-hidden="true"></i>
                    <div>
                        <h4>Hotels &amp; Homestays</h4>
                        <p>Handpicked accommodations near the ghats — from heritage havelis to comfortable budget stays.</p>
                    </div>
                </div>
                <div class="vk-about-value">
                    <i class="fa fa-car" aria-hidden="true"></i>
                    <div>
                        <h4>Cab &amp; Transport</h4>
                        <p>Reliable airport transfers, outstation cabs to Sarnath, Ayodhya, Prayagraj, and local city rides.</p>
                    </div>
                </div>
            </div>

            {{-- Why choose us --}}
            <h2>Why Travellers Choose Us</h2>
            <ul>
                <li><strong>100% local expertise</strong> — our team lives and breathes Varanasi every day</li>
                <li><strong>Verified partners</strong> — every hotel, boat, and cab operator is personally vetted</li>
                <li><strong>Transparent pricing</strong> — no hidden charges; what you see is what you pay</li>
                <li><strong>24/7 support</strong> — real people on WhatsApp and phone, not automated bots</li>
                <li><strong>Customisation</strong> — every itinerary tailored to your group, budget, and interests</li>
                <li><strong>Secure payments</strong> — encrypted transactions via trusted Indian payment gateways</li>
            </ul>

            {{-- Values --}}
            <h2>Our Values</h2>
            <div class="vk-about-values">
                <div class="vk-about-value">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <div>
                        <h4>Authenticity</h4>
                        <p>We showcase the real Varanasi — its rituals, flavours, and stories — not a tourist caricature.</p>
                    </div>
                </div>
                <div class="vk-about-value">
                    <i class="fa fa-shield" aria-hidden="true"></i>
                    <div>
                        <h4>Safety First</h4>
                        <p>All experiences are safety-checked. Boatmen are trained and certified. Your wellbeing is our top priority.</p>
                    </div>
                </div>
                <div class="vk-about-value">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <div>
                        <h4>Community</h4>
                        <p>We work with local artisans, boatmen families, and small hotel owners — helping Varanasi's communities thrive.</p>
                    </div>
                </div>
                <div class="vk-about-value">
                    <i class="fa fa-leaf" aria-hidden="true"></i>
                    <div>
                        <h4>Responsible Tourism</h4>
                        <p>Clean Ganga initiatives, plastic-free tours, and responsible waste management on every boat ride.</p>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="vk-about-cta">
                <h3>Ready to Experience Kashi?</h3>
                <p>Talk to our team — free consultation, no obligation.</p>
                <a href="{{ route('contact.show') }}">Contact Us Today</a>
            </div>

            {{-- Closing tagline --}}
            <div class="vk-tagline-close">
                <p><span>Visit Kashi</span> — Experience Varanasi with Trusted Locals.</p>
            </div>

        </article>
    </div>
</div>

@endsection
