@extends('admin.layouts.app')
@section('content')
    <style media="all">
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 0.875rem;
            font-weight: normal;
            padding: 0;
            margin: 0;
        }

        table {
            width: 100%;
        }

        .table-responsive h2 {
            text-align: center;
            margin-bottom: 40px;
        }

        .table-responsive .table {
            border-collapse: collapse;
            width: 100%;

            margin-top: 10px;
        }

        .table-responsive .table th {
            border: 1px solid #333;
            text-align: center;
            padding: 15px;
            color: #fff;
            background: #932806;
        }

        .table-responsive .table td {
            border: 1px solid #333;
            text-align: left;
            padding: 15px;
        }

        .print {
            width: 80px;
            background: #932806;
            border: 1px solid #666;
            padding: 9px 0;
            color: #fff;
            margin: 0px 20px;
            cursor: pointer;
            cursor: pointer;
        }

        .print-button {
            display: flex;
            justify-content: end;
        }

        .print-area {
            border: 1px solid #666;
            margin: 0;
        }

        .term-condition p {
            font-size: 14px;
            font-weight: 400;
            text-align: left;
        }

        .note p {
            font-size: 16px;
            font-weight: 400;
            text-align: left;
        }

        .charges p {
            font-size: 16px;
            font-weight: 400;
            text-align: left;
        }

        .thanks {
            color: #e30707ec;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
        }

        @media print {
            .print-button {
                display: none !important;
            }

            .sidebar,
            .navbar,
            .footer {
                display: none !important;
            }

            .print-area {
                margin: 0 !important;
            }

            .table td {
                white-space: break-spaces !important;
            }

            .page-break {
                page-break-before: always;
                padding-top: 40px;
            }

            .print-area {
                border: 0;
                margin: 0;
            }
        }
    </style>
    <div class="page-content p-2">
        <div class="print-area p-4">
            <div class="print-button">
                <button class="print" title="Print Invoice" onclick="printInvoice()">Print</button>
            </div>
            <div class="table-responsive">
                <h2>Booking Confirmation</h2>
                <table>
                    <tr>
                        <td></td>
                        <td>
                            <img src="{{ asset('backend/assets/images/logo.jpeg') }}" width="200">
                        </td>
                        <td style="text-align:right;  padding:0 10px; width:45%;"><b>Address:</b>
                            {{ websiteSetupValue('address') }}
                            <br><b>Support:</b> {{ websiteSetupValue('contact_number') }} ,
                            {{ websiteSetupValue('whats_app_number') }}
                            <br><span><b>Email:</b> {{ websiteSetupValue('email') }} <br>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <hr>
                <div style="padding:1rem;">
                    <table>
                        <tr>
                            <td><b>Guest Name :</b> {{ $lead_data->guest_name }}</td>
                            <td><b>Booking Date :</b> {{ dateFormat($lead_data->booking_date) }}</td>
                        </tr>
                        <tr>
                            <td><b>Contact No. : </b> {{ $lead_data->contact }}</td>
                            <td><b>Booking Number :</b> {{ $lead_data->booking_id }}</td>
                        </tr>
                        <tr>
                            <td><b>Number Of Person :</b> {{ $lead_data->pax ?? 0 }} </td>
                        </tr>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Service</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td>{{ $lead_data->address ?? 0 }}</td>
                            <td>Rs. {{ $payment_data->total_amount ?? 0 }}/-</td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Total</td>
                            <td><b> Rs. {{ $payment_data->total_amount ?? 0 }}/-</b></td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Paid</td>
                            <td><b> Rs. {{ $payment_data->paid_amount ?? 0 }}/-</b></td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Due</td>
                            <td><b> Rs. {{ $payment_data->due_amount ?? 0 }}/-</b></td>
                        </tr>
                    </table>
                </div>
                <div class="row mt-5 mb-5">
                    <div class="col-lg-12 col-md-12 text-end">
                        <h3 class="thanks">Regards and Thanks - </h3>

                        <h5 style="margin-right:40px;">
                            <i>{{ $lead_data->added_by != '1' ? $lead_data->getAddedBy->name : 'Visit Kashi' }}</i>
                        </h5>
                    </div>
                </div>
                <div class="row mt-5 mb-2 term-condition page-break">
                    <div class="col-lg-12 col-md-12">
                        <h5>Booking Confirmation Terms and Conditions:</h5>
                        <p><i class="bi bi-dot"></i>Full payment or an agreed deposit must be made at the time of booking.
                        </p>
                        <p><i class="bi bi-dot"></i>The remaining balance, if any, must be settled before the start of the
                            journey.</p>
                        <p><i class="bi bi-dot"></i>Confirmation will be sent via email or SMS upon successful booking.</p>
                    </div>
                </div>
                <div class="row mt-2 mb-2 term-condition">
                    <div class="col-lg-12 col-md-12">
                        <h5>Cancellation Policy:</h5>
                        <p><i class="bi bi-dot"></i>Cancellations made 7 days or more before the scheduled are eligible for
                            a full refund.</p>
                        <p><i class="bi bi-dot"></i>Cancellations made within 2 days of date are non-refundable.</p>
                    </div>
                </div>
                <div class="row mt-2 mb-2 term-condition">
                    <div class="col-lg-12 col-md-12">
                        <h5>Weather Conditions:</h5>
                        <p><i class="bi bi-dot"></i>The ride may be cancelled or rescheduled due to adverse weather
                            conditions or safety concerns.</p>
                        <p><i class="bi bi-dot"></i>VisitKashi will notify participants of any changes in schedule as early
                            as possible</p>
                    </div>
                </div>
                <div class="row mt-2 mb-2 term-condition">
                    <div class="col-lg-12 col-md-12">
                        <h5>Dispute Resolution: </h5>
                        <p><i class="bi bi-dot"></i>Any disputes arising out of these terms and conditions will be subject
                            to the jurisdiction of the courts in Varanasi.
                        </p>
                    </div>
                </div>
                <div class="row mt-2 mb-2 term-condition">
                    <div class="col-lg-12 col-md-12">
                        <h5>Modifications:</h5>
                        <p><i class="bi bi-dot"></i>Visit Kashi reserves the right to modify these terms and conditions at
                            any time without prior notice. Customers are advised to review these terms regularly.
                        </p>
                    </div>
                </div>
                <div class="row mt-2 mb-2 term-condition">
                    <div class="col-lg-12 col-md-12">
                        <h5>Behaviour and Conduct:</h5>
                        <p><i class="bi bi-dot"></i>Customers are expected to maintain decent behaviour during the journey.
                        </p>
                        <p><i class="bi bi-dot"></i>The driver has the right to terminate the trip without refund in case of
                            any abusive or threatening behaviour by the customer or their party.</p>
                    </div>
                </div>
                <div class="row mt-2 mb-2 term-condition">
                    <div class="col-lg-12 col-md-12">
                        <h5>Liability:</h5>
                        <p><i class="bi bi-dot"></i>Visit Kashi Travel Agency will not be liable for any loss or damage to
                            personal property during the journey.
                        </p>
                        <p><i class="bi bi-dot"></i>The agency will not be responsible for delays caused by traffic, weather
                            conditions, or any unforeseen circumstances.</p>
                    </div>
                </div>

                @if (in_array('Boat', $lead_data->enquiry_for))
                    <div class="row mt-2 mb-2 term-condition">
                        <div class="col-lg-12 col-md-12">
                            <h5>Morning/Evening Ganga Aarti :</h5>
                            <p><i class="bi bi-dot"></i>The boat ride duration is approximately 02:30hr included Ganga
                                Aarti.
                                Pickup from your nearest Ghat (NAMO/Dashaswamedh/Ravidas Ghat.)
                            </p>
                            <p><i class="bi bi-dot"></i>Participants must arrive at the designated meeting point before 15
                                Min.
                            </p>
                            <p><i class="bi bi-dot"></i>As Per Jal Police Guidelines After Ganga Aarti boat ride not allowed
                                in
                                night </p>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2 term-condition">
                        <div class="col-lg-12 col-md-12">
                            <h5>Morning Ganga Aarti begins just before sunrise at Assi Ghat Varanasi</h5>
                            <p><i class="bi bi-dot"></i>The exact time varies throughout the year according to the time of
                                sunrise, usually between 5:00 AM and 5:30 AM.</p>
                            <p><i class="bi bi-dot"></i>Evening Ganga Aarti usually starts around sunset at Dashaswamedh
                                Ghat.
                                The timing varies according to the season, typically beginning between 6:00 PM and 7:00 PM.
                            </p>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2 term-condition">
                        <div class="col-lg-12 col-md-12">
                            <h5>Recommendations: </h5>
                            <p><i class="bi bi-dot"></i>Arrive at least 30-45 minutes early to find a good viewing spot as
                                it
                                gets very crowded. Late arrivals we are not responsible for Good View of Ganga Aarti and all
                                Ghat Darshan. </p>
                            <p><i class="bi bi-dot"></i>Consider hiring a boat for a unique view of the aarti from the
                                river.
                            </p>
                            <p><i class="bi bi-dot"></i>Be mindful of your belongings due to the large crowd. </p>
                            <p><i class="bi bi-dot"></i>Photography is allowed, but it is respectful to ask permission
                                before
                                taking close-up shots of people or priests.</p>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2 term-condition">
                        <div class="col-lg-12 col-md-12">
                            <h5>Safety and Conduct: </h5>
                            <p><i class="bi bi-dot"></i>All participants must wear life jackets provided by VisitKashi
                                during
                                the boat ride.</p>
                            <p><i class="bi bi-dot"></i>Participants must follow the instructions of the boat captain for
                                safety
                                reasons.</p>
                            <p><i class="bi bi-dot"></i>Standing or moving around in the boat during the ride is prohibited
                                for
                                safety reasons.</p>
                            <p><i class="bi bi-dot"></i>VisitKashi and its affiliates are not liable for any personal
                                injury,
                                loss, or damage to personal belongings during ride.</p>
                        </div>
                    </div>
                @elseif (in_array('Cab', $lead_data->enquiry_for))
                    <div class="row mt-2 mb-2 term-condition">
                        <div class="col-lg-12 col-md-12">
                            <h5>Driver Allowance and Night Charges:</h5>
                            <p><i class="bi bi-dot"></i>An additional driver allowance fee will be charged if the trip
                                extends
                                beyond 8 hours.</p>
                            <p><i class="bi bi-dot"></i>Night charges ₹300/- will apply for travel between 09:00 PM and 6:00
                                AM.
                                These charges are to compensate the driver for night-time work and will be added to the
                                total
                                fare.</p>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2 term-condition">
                        <div class="col-lg-12 col-md-12">
                            <h5>Parking and Toll Taxes:</h5>
                            <p><i class="bi bi-dot"></i>All parking fees, toll taxes, and state entry taxes (if applicable)
                                are
                                the responsibility of the customer and are not included in the quoted fare.</p>
                            <p><i class="bi bi-dot"></i>These charges are to be paid directly at the respective locations or
                                reimbursed to the driver upon presentation of receipts.</p>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2 term-condition">
                        <div class="col-lg-12 col-md-12">
                            <h5>Vehicle Use:</h5>
                            <p><i class="bi bi-dot"></i>The vehicle must be used for lawful purposes only.</p>
                            <p><i class="bi bi-dot"></i>Any damage caused to the vehicle by the customer or their party will
                                be
                                charged to the customer.</p>
                        </div>
                    </div>
                    <div class="row">
                        <p>By booking a cab with Visit Kashi, you acknowledge that you have read, understood, and agreed to
                            these terms and conditions. For any questions or further clarifications, please contact our
                            customer
                            service team.</p>
                    </div>
                @endif
                <div class="row mt-5">
                    <div class="col-lg-12 col-md-12 text-end">
                        <p class="thanks text-center"><b>!! Thank you for choosing Visit Kashi for your travel needs in
                                Varanasi. Safe travels !!</b></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <p class="text-center"><b>www.visitkashi.com</b></p>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
Terms and Conditions:
Full payment or an agreed deposit must be made at the time of booking.
The remaining balance, if any, must be settled before the start of the journey.

Cancellation Policy:
Cancellations made 7 days or more before the scheduled are eligible for a full refund.
Cancellations made within 2 days of date are non-refundable.

Modifications:
Visit Kashi reserves the right to modify these terms and conditions at any time without prior notice. Customers are advised to review these terms regularly.

Liability:
Visit Kashi Travel Agency will not be liable for any loss or damage to personal property during the journey.

Driver Allowance and Night Charges:
An additional driver allowance fee will be charged if the trip extends beyond 8 hours.
Night charges ₹300/- will apply for travel between 09:00 PM and 6:00 AM. These charges are to compensate the driver for night-time work and will be added to the total fare.

Parking and Toll Taxes:
All parking fees, toll taxes, and state entry taxes (if applicable) are the responsibility of the customer and are not included in the quoted fare.
These charges are to be paid directly at the respective locations or reimbursed to the driver upon presentation of receipts.

By booking a cab with Visit Kashi, you acknowledge that you have read, understood, and agreed to these terms and conditions. For any questions or further clarifications, please contact our customer service team.

!! Thank you for choosing Visit Kashi for your travel needs in Varanasi. Safe travels !!

