@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <form action="{{ route('payment.status', $data->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h4>{{ $page_title }}</h4>
                                </div>
                                <div class="col-md-8 text-end">
                                    <a href="{{ route('quick-booking.form', $data->id) }}"
                                        class="btn btn-success btn-icon-text me-2">
                                        <i class="btn-icon-prepend" data-feather="zap"></i>
                                        Quick Booking
                                    </a>
                                    <a href="{{ route('quotations.create', ['lead_id' => $data->id]) }}"
                                        class="btn btn-primary btn-icon-text me-2">
                                        <i class="btn-icon-prepend" data-feather="file-text"></i>
                                        Create Quotation
                                    </a>
                                    <x-cancle-btn route="{!! route('lead.index', $searchForm) !!}" text="Back" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card card-body">
                                        <h6>Customer Details :</h6>
                                        <p>Name: {{ $data->guest_name }}</p>
                                        <p>Phone: {{ $data->contact }}</p>
                                        <p>Plan: {{ $data->short_plan }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-body">
                                        <label for="booking_status" class="form-label">Booking Status :</label>
                                        <select class="form-control" name="booking_status">
                                            <option value="">Select Status</option>
                                            <option value="quatation" @if ($data->booking_status == 'quatation') selected @endif>
                                                Quatation</option>
                                            <option value="follow up" @if ($data->booking_status == 'follow up') selected @endif>
                                                Follow up</option>
                                            <option value="confirm" @if ($data->booking_status == 'confirm') selected @endif>
                                                Confirmed</option>
                                            <option value="complete" @if ($data->booking_status == 'complete') selected @endif>
                                                Completed</option>
                                            <option value="cancel" @if ($data->booking_status == 'cancel') selected @endif>
                                                Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-body">
                                        <label for="total_amount" class="form-label">Booking Amount :</label>
                                        <input type="number" name="total_amount" class="form-control"
                                            value="{{ $data->total_amount }}" placeholder="Booking Amount">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-body">
                                        <label for="due_amount" class="form-label">Due Amount :</label>
                                        <input type="number" name="due_amount" class="form-control"
                                            value="{{ $data->total_amount - $data->lead_payments_sum_paid_amount }}"
                                            placeholder="Due Amount" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-body">
                                        <label for="total_expense" class="form-label">Total Expense :</label>
                                        <input type="number" name="total_expense" class="form-control"
                                            value="{{ $data->total_expense }}" placeholder="Total Expense">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-body">
                                        <label for="payment_status" class="form-label">Payment Status :</label>
                                        <select class="form-control" name="payment_status">
                                            <option value="">Select Status</option>
                                            <option value="not paid" @if ($data->payment_status == 'not paid') selected @endif>Not
                                                Paid</option>
                                            <option value="paid" @if ($data->payment_status == 'paid') selected @endif>Paid
                                            </option>
                                            <option value="due" @if ($data->payment_status == 'due') selected @endif>Due
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body" id="payment_div">
                            <h4>Payment Details :</h4>
                            @forelse ($data->leadPayments??[] as $key=>$lead_payment)
                                <div class="row mt-3 g-3" id="payment_row_{{ $key }}">
                                    <input type="hidden" name="ids[]" value="{{ $lead_payment?->id }}">
                                    <div class="col-md-3">
                                        <label for="payment_date" class="form-label">Payment Date :</label>
                                        <input id="payment_date" class="form-control" type="date" name="payment_date[]"
                                            value="{{ $lead_payment?->payment_date }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="payment_mode" class="form-label">Payment Mode :</label>
                                        <select class="form-select" name="payment_mode[]">
                                            <option value="online" @if ($lead_payment?->payment_mode == 'online') selected @endif>Online
                                            </option>
                                            <option value="cash" @if ($lead_payment?->payment_mode == 'cash') selected @endif>Cash
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="paid_amount" class="form-label">Paid Amount :</label>
                                        <input class="form-control number_only" type="text"
                                            value="{{ $lead_payment?->paid_amount }}" name="paid_amount[]"
                                            placeholder="Enter Paid Amount">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="remark" class="form-label">Remark :</label>
                                        <input class="form-control" type="text" name="remark[]"
                                            value="{{ $lead_payment?->remark }}" placeholder="Enter remark">
                                    </div>
                                    @if ($key == 0)
                                        <div class="col-md-1">
                                            <a class="btn btn-success btn-sm btn-icon-text mt-4"
                                                onclick="addDiv()">Add</a>
                                        </div>
                                    @else
                                        <div class="col-md-1">
                                            <a class="btn btn-danger btn-sm btn-icon-text mt-4"
                                                onclick="removeDiv({{ $key }})">Remove</a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="row mt-3 g-3">
                                    <div class="col-md-3">
                                        <label for="payment_date" class="form-label">Payment Date :</label>
                                        <input id="payment_date" class="form-control" type="date"
                                            name="payment_date[]">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="payment_mode" class="form-label">Payment Mode :</label>
                                        <select class="form-select" name="payment_mode[]">
                                            <option value="online">Online</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="paid_amount" class="form-label">Paid Amount :</label>
                                        <input class="form-control number_only" type="text" name="paid_amount[]"
                                            placeholder="Enter Paid Amount">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="remark" class="form-label">Remark :</label>
                                        <input class="form-control" type="text" name="remark[]"
                                            placeholder="Enter remark">
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-success btn-sm btn-icon-text mt-4" onclick="addDiv()">Add</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col" style="margin-top:40px">
                <x-save-btn text="save" />
            </div>
        </form>

        <!-- Activity Timeline Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i data-feather="activity" style="width: 20px; height: 20px;"></i>
                            Activity History
                        </h5>
                    </div>
                    <div class="card-body">
                        <x-activity-timeline :activities="$activities" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var x = "{{ count($data->lead_payments ?? []) }}";

        function addDiv() {
            $('#payment_div').append('<div class="row mt-3 g-3" id="payment_row_' + x + '">' +
                '<div class="col-md-3">' +
                '<label for="payment_date" class="form-label">Payment Date :</label>' +
                '<input id="payment_date" class="form-control" type="date" name="payment_date[]">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="payment_mode" class="form-label">Payment Mode :</label>' +
                '<select class="form-select" name="payment_mode[]">' +
                '<option value="online">Online</option>' +
                '<option value="cash">Cash</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-2">' +
                '<label for="paid_amount" class="form-label">Paid Amount :</label>' +
                '<input class="form-control number_only" type="text" name="paid_amount[]" placeholder="Enter Paid Amount">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="remark" class="form-label">Remark :</label>' +
                '<input class="form-control" type="text" name="remark[]" placeholder="Enter remark">' +
                '</div>' +
                '<div class="col-md-1">' +
                '<a class="btn btn-danger btn-sm btn-icon-text mt-4" onclick="removeDiv(' + x + ')">Remove</a>' +
                '</div>' +
                '</div>');
            x++;
        }

        function removeDiv(row_id) {
            $('#payment_row_' + row_id).remove();
        }
    </script>
@endsection
