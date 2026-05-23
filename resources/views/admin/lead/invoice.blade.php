<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Invoice | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <style>
        @media print {
            .invoice-6 {
                background: #fff;
            }
        }
    </style>
</head>

<body>

    <!-- Invoice 6 start -->
    <div class="invoice-6 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner clearfix">
                        <div class="invoice-info clearfix" id="invoice_wrapper">
                            <div class="invoice-headar">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="invoice-logo">
                                            <!-- logo started -->
                                            <div class="logo">
                                                <img src="{{ asset('backend/logo.jpeg') }}" alt="logo">
                                            </div>
                                            <!-- logo ended -->
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="invoice-contact-us">
                                            <ul class="link">
                                                <li><b>Address:</b> {{ websiteSetupValue('address') }}</li>
                                                <li> <b>Support:</b> {{ websiteSetupValue('contact_number') }} ,
                                                    {{ websiteSetupValue('whats_app_number') }}
                                                </li>
                                                <li><b>Email:</b> {{ websiteSetupValue('email') }} </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-contant">
                                <div class="invoice-top">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h3 class="invoice-name">Booking @if($lead_data->booking_status == 'quatation') Quatation @else Confirmation @endif</h3>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6><b>Booking Number :</b> {{ $lead_data->booking_id }}</h6> <br>
                                        </div>
                                        <hr>
                                        <div class="col-sm-7 mb-30">
                                            <div class="invoice-number">
                                                <p class="invo-addr-1 mb-0">
                                                    <b>Guest Name :</b> {{ $lead_data->guest_name }}<br />
                                                    <b>Contact No. : </b> {{ $lead_data->contact }}<br />
                                                    <b>Number of Person :</b> {{ $lead_data->pax ?? 0 }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 mb-30 text-start">
                                            <div class="invoice-number">
                                                <div class="">
                                                    <p class="invo-addr-1 mb-0">
                                                        @if($lead_data->short_plan)
                                                            <b>{{ $lead_data->short_plan }}</b> <br>
                                                        @endif
                                                        <b>Booking Date :</b>
                                                        @if($lead_data->booking_start_date == $lead_data->booking_end_date)
                                                        {{ dateFormat($lead_data->booking_start_date) }}
                                                        @else
                                                        {{ dateFormat($lead_data->booking_start_date) }} - {{ dateFormat($lead_data->booking_end_date) }}<br>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-center">
                                    <div class="order-summary">
                                        <div class="table-outer">
                                            <table class="default-table invoice-table">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Amt.</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>{!! $lead_data->plan_detail!!}</td>
                                                        <td>Rs. {{ $lead_data->total_amount ?? 0 }}/-</td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td style="text-align: end;" class="fw-bold">Total</td>
                                                        <td>Rs. {{ $payment_data->total_amount ?? 0 }}/-</td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td style="text-align: end;" class="fw-bold"><span class="text-success">Paid</span></td>
                                                        <td><span class="text-success">Rs. {{ $lead_data->lead_payments_sum_paid_amount ?? 0 }}/-</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: end;" class="fw-bold"><span class="text-danger">Due</span></td>
                                                        <td><span class="text-danger">Rs. {{ $lead_data->total_amount - $lead_data->lead_payments_sum_paid_amount}}/-</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-bottom">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="terms-conditions">
                                                {{-- <ul>
                                                <h3 class="inv-title-1 mb-10 mt-3 fw-bold">Terms & Conditions :</h3>
                                                <li> Full payment or an agreed deposit must be
                                                    made at the time of
                                                    booking.</li>
                                                <li> The remaining balance, if any, must be
                                                    settled before the start of
                                                    the journey.</li>
                                                <h3 class="inv-title-1 mb-10 mt-3 fw-bold">Cancellation Policy:</h3> --}}
                                                {{-- <li> Cancellations made 7 days or more before the
                                                    scheduled are eligible
                                                    for a full refund.</li>
                                                <li> Cancellations made within 2 days of date
                                                    are non-refundable.</li> --}}
                                                    {{-- <li>Non Refundable</li>

                                                <h3 class="inv-title-1 mb-10 mt-3 fw-bold">Modifications:</h3>
                                                <li> Visit Kashi reserves the right to modify these terms and conditions
                                                at any time without prior notice. Customers are advised to review
                                                these terms regularly.</li>

                                                <h3 class="inv-title-1 mb-10 mt-3 fw-bold">Liability:</h3>
                                                <li>  Visit Kashi Travel Agency will not be liable for any loss or damage
                                                to personal property during the journey.</li> --}}

                                                {{-- <h3 class="inv-title-1 mb-10 mt-3 fw-bold">Driver Allowance and Night Charges:</h3>
                                                <li> An additional driver allowance fee will be charged if the trip
                                                extends beyond 8 hours.</li>
                                                <li> Night charges ₹300/- will apply for travel between 09:00 PM and 6:00
                                                AM. These charges are to compensate the driver for night-time work
                                                and will be added to the total fare.</li>

                                                <h3 class="inv-title-1 mb-10 mt-3 fw-bold">Parking and Toll Taxes:</h3>
                                                <li>All parking fees, toll taxes, and state entry taxes (if applicable)
                                                are the responsibility of the customer and are not included in the
                                                quoted fare.</li>
                                                <li>These charges are to be paid directly at the respective locations or
                                                reimbursed to the driver upon presentation of receipts.</li>
                                                <li>By booking a cab with Visit Kashi, you acknowledge that you have
                                                read, understood, and agreed to these terms and conditions. For any
                                                questions or further clarifications, please contact our customer
                                                service team.</li> --}}

                                                {{-- <p class="text-center mt-3"><b>!! Thank you for choosing Visit Kashi for your travel needs in
                                                Varanasi. Safe travels !! </b></p>
                                                </p>
                                            </ul> --}}
                                            @php
                                                $categories = App\Models\Admin\Category::whereIn('id', $lead_data->tm_category_ids)->groupBy('id')->get();
                                            @endphp
                                            @foreach ($categories as $category)
                                                {!!$category->term_and_condition!!}
                                            @endforeach
                                            {{-- {!!websiteSetupValue('privacy_policy')!!} --}}
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-lg-12">
                                                    <p class="text-center"><b>www.visitkashi.com</b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-btn-section clearfix d-print-none">
                            <a href="javascript:window.print()" class="btn btn-lg btn-print">
                                <i class="fa fa-print"></i> Download Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Invoice 6 end -->

</body>

</html>
