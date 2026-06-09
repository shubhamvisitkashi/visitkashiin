<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Booking Confirmation</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Email Client Reset */
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif !important;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            width: 100% !important;
            min-width: 100%;
            -webkit-font-smoothing: antialiased;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            background: #b04700;
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .logo-section {
            margin-bottom: 15px;
        }

        .logo-image {
            width: 165px;
            height: auto;
            display: block;
            margin: 0 auto 8px auto;
            max-width: 100%;
        }

        .company-unit {
            display: block;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            margin-bottom: 20px;
        }

        .title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .subtitle {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 8px;
            opacity: 0.9;
        }

        .journey-text {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Content */
        .content {
            padding: 20px;
        }

        /* Booking Reference */
        .booking-ref-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .booking-ref-card {
            background: linear-gradient(135deg, #f9c54e 0%, #ff7214 100%);
            border-radius: 12px;
            padding: 15px 20px;
            display: inline-block;
            min-width: 200px;
        }

        .booking-ref-label {
            color: white;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .booking-ref-number {
            color: white;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1.5px;
        }

        /* Info Grid */
        .info-grid {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-item {
            background: #fefbf1;
            border: 1px solid #ffeaa4;
            border-radius: 8px;
            padding: 15px;
            box-sizing: border-box;
        }

        .info-content-wrapper {
            display: block;
            width: 100%;
        }

        .content-cell {
            margin-bottom: 8px;
        }

        .badge-cell {
            margin-top: 20px;
        }

        .info-label {
            font-size: 13px;
            color: #93461f;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            color: #93461f;
            font-weight: 600;
            line-height: 1.2;
        }

        .paid-badge {
            background: #f0fdf4;
            color: #4ade80;
            border: 1px solid #4ade80;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .paid-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #dc2626;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .paid-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #16a34a;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .paid-warning {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #d97706;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 20px;
            font-weight: 600;
            display: inline-block;
        }

        /* Mobile Responsive for Info Grid - ENHANCED */
        @media only screen and (max-width: 600px) {
            /* Force table elements to behave like divs on mobile */
            .info-table,
            .info-table tbody,
            .info-table tr,
            .info-table td {
                display: block !important;
                width: 100% !important;
                border: none !important;
            }

            /* Hide spacer elements on mobile */
            .spacer-cell,
            .spacer-row {
                display: none !important;
            }

            .info-item {
                width: 100% !important;
                margin-bottom: 12px !important;
                padding: 15px !important;
                display: block !important;
                box-sizing: border-box !important;
            }

            .info-value {
                font-size: 15px !important;
            }

            .info-label {
                font-size: 12px !important;
            }

            .paid-badge {
                font-size: 10px !important;
                padding: 4px 8px !important;
                margin-top: 8px !important;
            }

            .paid-danger,
            .paid-success,
            .paid-warning {
                font-size: 20px !important;
                padding: 3px 8px !important;
                margin-top: 8px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .info-item {
                padding: 12px !important;
                margin-bottom: 10px !important;
            }

            .info-value {
                font-size: 14px !important;
            }

            .info-label {
                font-size: 11px !important;
            }
        }

        /* Journey Section */
        .journey-section {
            background: #fefbf1;
            border: 1px solid #ffeaa4;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .journey-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .journey-title {
            font-size: 20px;
            color: #93461f;
            font-weight: 700;
        }

        .journey-grid {
            width: 100%;
        }

        .journey-grid table {
            width: 100%;
            border-collapse: collapse;
        }

        .journey-item {
            width: 33.33%;
            text-align: center;
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px 8px;
            vertical-align: top;
        }

        .journey-icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f9c54e, #ff7214);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .journey-label {
            font-size: 12px;
            color: #93461f;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .journey-time {
            font-size: 18px;
            color: #93461f;
            font-weight: 700;
        }

        .journey-place {
            font-size: 13px;
            color: #93461f;
            font-weight: 400;
        }

        /* Notice */
        .notice-card {
            background: #b04700;
            color: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-top: 15px;
        }

        .notice-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .notice-text {
            font-size: 12px;
            opacity: 0.95;
        }

        /* Screenshot Area Divider */
        .screenshot-divider {
            margin: 30px 0;
            text-align: center;
            position: relative;
        }

        .screenshot-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ddd, transparent);
        }

        .screenshot-text {
            background: white;
            padding: 0 20px;
            color: #666;
            font-size: 12px;
            font-style: italic;
        }

        /* Terms Section */
        .terms-section {
            padding: 20px;
            background: white;
        }

        .section-header {
            margin-bottom: 20px;
            text-align: center;
        }

        .section-title {
            font-size: 18px;
            color: #93461f;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .terms-content {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .term-number {
            font-weight: 600;
            color: #93461f;
        }

        .warning-box {
            background: #fef2f2;
            border-left: 4px solid #f87171;
            border-radius: 0 6px 6px 0;
            padding: 12px;
            margin: 15px 0;
            font-size: 13px;
            color: #b91c1c;
            font-weight: 600;
        }

        /* Contact Footer */
        .contact-footer {
            background: #b04700;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .contact-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .website-info {
            margin-bottom: 15px;
        }

        .website-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .website-url {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .support-info {
            margin-top: 15px;
        }

        .support-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .phone-number {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            margin: 0 3px;
            display: inline-block;
        }

        /* Mobile Responsive - Enhanced */
        @media only screen and (max-width: 600px) {
            body {
                padding: 5px !important;
            }

            .email-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .header {
                padding: 20px 15px !important;
            }

            .logo-image {
                width: 140px !important;
            }

            .company-unit {
                font-size: 12px !important;
                margin-bottom: 15px !important;
            }

            .title {
                font-size: 20px !important;
                line-height: 1.2 !important;
                margin-bottom: 10px !important;
            }

            .subtitle {
                font-size: 16px !important;
            }

            .content {
                padding: 15px !important;
            }

            /* Journey section mobile */
            .journey-section {
                padding: 15px !important;
                margin-bottom: 15px !important;
            }

            .journey-title {
                font-size: 18px !important;
            }

            .notice-card {
                padding: 12px !important;
            }

            .notice-title {
                font-size: 13px !important;
                line-height: 1.3 !important;
            }

            /* Terms section mobile */
            .terms-section {
                padding: 15px !important;
            }

            .section-title {
                font-size: 16px !important;
            }

            .terms-content {
                font-size: 12px !important;
                line-height: 1.5 !important;
            }

            .warning-box {
                padding: 10px !important;
                font-size: 12px !important;
            }

            /* Contact footer mobile */
            .contact-footer {
                padding: 20px 15px !important;
            }

            .website-label {
                font-size: 13px !important;
            }

            .support-title {
                font-size: 13px !important;
            }

            .phone-number {
                font-size: 11px !important;
                padding: 3px 8px !important;
                margin: 2px !important;
                display: inline-block !important;
            }

            /* Screenshot divider mobile */
            .screenshot-divider {
                margin: 20px 0 !important;
            }

            .screenshot-text {
                font-size: 11px !important;
                padding: 0 15px !important;
            }
        }

        /* Extra small mobile devices */
        @media only screen and (max-width: 480px) {
            .title {
                font-size: 18px !important;
            }

            .subtitle {
                font-size: 14px !important;
            }

            .info-value {
                font-size: 14px !important;
            }

            .info-label {
                font-size: 12px !important;
            }

            .journey-title {
                font-size: 16px !important;
            }

            .notice-title {
                font-size: 12px !important;
            }
        }

        /* Outlook Specific */
        .info-item,
        .journey-item {
            width: 48% !important;
        }

        /* Outlook mobile fix */
        @media screen and (max-width: 525px) {
            .info-item,
            .journey-item {
                width: 100% !important;
                display: block !important;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('frontend/images/icon/logo.png') }}" alt="Visit Kashi Logo" class="logo-image">
                <div class="company-unit">(A unit of Albino Stays Pvt. Ltd.)</div>
            </div>
            <h1 class="title">Dev Diwali Boat Booking</h1>
            <h1 class="title">24 Nov 2026</h1>
            <div class="subtitle">(E-Ticket: Boarding Pass)</div>
            <p class="subtitle">
                Booking ID:
                @isset($boat_booking)
                    {{ $boat_booking->booking_id }}
                @endisset
            </p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Info Grid -->
            <div class="info-grid">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="info-table">
                    <tr>
                        <td class="info-item" style="width: 48%; vertical-align: top;">
                            <div class="info-content-wrapper">
                                <div class="content-cell">
                                    <div class="info-label"><b>Boat Category</b></div>
                                    <div class="info-value">
                                        @isset($boat_booking)
                                            {{ $boat_booking->boat?->boatType?->name }}
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="spacer-cell" style="width: 4%;"></td>
                        <td class="info-item" style="width: 48%; vertical-align: top;">
                            <div class="info-content-wrapper">
                                <div class="content-cell">
                                    <div class="info-label"><b>Guest Name</b></div>
                                    <div class="info-value">
                                        @isset($boat_booking)
                                            {{ $boat_booking->name }} <br>
                                            {{ $boat_booking->phone }}
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                    <tr class="spacer-row">
                        <td style="height: 10px;"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="info-item" style="width: 48%; vertical-align: top;">
                            <div class="info-content-wrapper">
                                <div class="content-cell">
                                    <div class="info-label"><b>No. of Persons :</b>
                                        @isset($boat_booking)
                                            <span class="info-value">{{ $boat_booking->no_of_person }}</span>
                                        @endisset
                                    </div>
                                </div>
                                <div class="content-cell">
                                    <div class="info-label"><b>Seat Number</b></div>
                                    <div class="info-value">
                                        @isset($boat_booking)
                                            {{ $boat_booking->seat_number }}
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="spacer-cell" style="width: 4%;"></td>
                        <td class="info-item" style="width: 48%; vertical-align: top;">
                            <div class="info-content-wrapper">
                                <div class="content-cell">
                                    <div class="info-label"><b>Payment Status</b></div>
                                    <div class="badge-cell">
                                        @isset($boat_booking)
                                            @if($boat_booking->payment_status === 'partial')
                                                <div class="paid-danger">Due</div>
                                            @elseif($boat_booking->payment_status === 'paid')
                                                <div class="paid-success">Paid</div>
                                            @elseif($boat_booking->payment_status === 'failed')
                                                <div class="paid-danger">Failed</div>
                                            @else
                                                <div class="paid-danger">{{ ucfirst($boat_booking->payment_status) }}</div>
                                            @endif
                                        @endisset
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Journey Section -->
            <div class="journey-section">
                <div class="journey-header">
                    <h2 class="journey-title">Important</h2>
                </div>
                <div class="notice-card">
                    <div class="notice-title">Reporting : 04:00 PM Ravidas Ghat | Start: 05:00 PM-08:00 PM</div>
                    <div class="notice-title">Google Map Location : <a href="https://maps.app.goo.gl/LWThuFZY3nkr5Q1G6" style="color: white;">https://maps.app.goo.gl/LWThuFZY3nkr5Q1G6</a></div>
                </div>
            </div>
        </div>

        <!-- Screenshot Divider -->
        <div class="screenshot-divider">
            <span class="screenshot-text">— Screenshot above for quick reference —</span>
        </div>

        <!-- Terms Section -->
        <div class="terms-section">
            <div class="section-header">
                <h2 class="section-title">Experience the divine glow of Dev Diwali with comfort, safety, and devotion ensured by Visit Kashi</h2>
            </div>

            <div class="terms-content">
                <span class="term-number">1.</span> On this sacred occasion, we cover all 84 Ghats, along with the grand Laser Show, Fire Show & Ganga Aarti.
            </div>
            <div class="terms-content">
                <span class="term-number">2.</span> Due to heavy boat traffic in the Ganga, for your safety Normal/Light boats will not be parked at Dashashwamedh Ghat Aarti area.
            </div>
            <div class="terms-content">
                <span class="term-number">3.</span> Food & Snacks <br>
                                                        Cruise → Varanasi Local Street Food Catering Service <br>
                                                        Other Boats → Packed Snacks Only
            </div>
            <div class="terms-content">
                <span class="term-number">4.</span> Important Note: As per Government protocol and current guidelines, all guests are requested to kindly follow the instructions and cooperate with the crew for a safe and smooth experience.
            </div>
        </div>

        <div class="terms-section">
            <div class="section-header">
                <h2 class="section-title">Terms & Conditions</h2>
            </div>

            <div class="terms-content">
                <span class="term-number">1.</span> All guests must arrive at the ghat at least 30 minutes prior to the
                reporting time.
            </div>
            <div class="terms-content">
                <span class="term-number">2.</span> Boarding is allowed only after presenting the confirmed
                ticket/booking.
            </div>
            <div class="terms-content">
                <span class="term-number">3.</span> Guests must follow all instructions given by the boat crew/staff
                for safety reasons.
            </div>
            <div class="terms-content">
                <span class="term-number">4.</span> The company is not responsible for any delays due to traffic,
                crowd, or personal reasons.
            </div>
            <div class="terms-content">
                <span class="term-number">5.</span> Carrying alcohol, drugs, or inflammable materials on the boat is
                strictly prohibited.
            </div>
            <div class="terms-content">
                <span class="term-number">6.</span> The accompanying person will be responsible for the safety of
                children and elderly passengers.
            </div>
            <div class="terms-content">
                <span class="term-number">7.</span> Misbehavior, violence, or creating a scene with staff/crew/other guests will lead to immediate cancellation of the booking without any refund. The company reserves the right to deny entry or deboard such guests.
            </div>

            <div class="section-header" style="margin-top: 30px;">
                <h2 class="section-title">Cancellation & Refund Policy</h2>
            </div>

            <div class="warning-box">
                <strong>⚠️ All bookings for Dev Deepawali are Non-Refundable</strong>
            </div>

            <div class="terms-content">
                <span class="term-number">1.</span> No refunds or rescheduling will be allowed under any circumstances
                (delay, no-show, personal emergency, etc.).
            </div>
            <div class="terms-content">
                <span class="term-number">2.</span> In case the boat service is canceled due to administrative orders
                or adverse weather conditions: An alternative slot/boat may be offered, or the booking amount can be
                utilized as a future credit.
            </div>
            <div class="terms-content">
                <span class="term-number">3.</span> No refund will be provided for partial attendance (fewer guests
                than booked).
            </div>
            <div class="terms-content">
                <span class="term-number">4.</span> <strong>Dispute & Jurisdiction:</strong> Any disputes will be
                subject exclusively to the jurisdiction of Varanasi Judiciary.
            </div>
        </div>

        <!-- Contact Footer -->
        <div class="contact-footer">
           <div class="website-info">
                <div class="website-label">Website:visitkashi.com</div>
            </div>

            <div class="support-info">
                <div class="support-title">Support: 7080109917, 7080109918, 7080109919</div>
            </div>
            <p>All Rights Reserved © visitkashi 2017-2025</p>
        </div>
    </div>
</body>
</html>
