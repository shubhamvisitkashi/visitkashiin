@extends('admin.layouts.app')

@section('content')
<style>
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        margin-bottom: 20px;
    }
    .metric-card.success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .metric-card.warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .metric-card.danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
    .metric-value { font-size: 32px; font-weight: 700; margin: 10px 0; }
    .metric-label { font-size: 14px; opacity: 0.9; }
</style>

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Payment & Cash Flow Analytics</h4>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('payment-analytics.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="metric-card success">
                <div class="metric-label">Total Collected</div>
                <div class="metric-value">₹{{ number_format($totalCollected, 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card danger">
                <div class="metric-label">Total Outstanding</div>
                <div class="metric-value">₹{{ number_format($totalOutstanding, 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-label">Collection Rate</div>
                <div class="metric-value">{{ $collectionRate }}%</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card warning">
                <div class="metric-label">Avg Payment Time</div>
                <div class="metric-value">{{ $avgPaymentTime }} days</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">30-Day Cash Flow Timeline</h6>
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Payment Status</h6>
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method & Outstanding -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Payment Method Distribution</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentMethods as $method)
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}</strong></td>
                                    <td class="text-success">₹{{ number_format($method->total, 0) }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $totalCollected > 0 ? number_format(($method->total / $totalCollected) * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Outstanding by Age</h6>
                    <canvas id="outstandingAgeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Collection Trend -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">6-Month Collection Trend</h6>
                    <canvas id="monthlyCollectionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Payments -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Overdue Payments (30+ days)</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking Date</th>
                                    <th>Customer</th>
                                    <th>Booking #</th>
                                    <th>Total Amount</th>
                                    <th>Paid</th>
                                    <th>Pending</th>
                                    <th>Days Overdue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overduePayments as $booking)
                                <tr>
                                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                                    <td>{{ $booking->lead->guest_name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-warning">{{ $booking->booking_number }}</span></td>
                                    <td>₹{{ number_format($booking->total_amount, 0) }}</td>
                                    <td class="text-success">₹{{ number_format($booking->paid_amount, 0) }}</td>
                                    <td class="text-danger"><strong>₹{{ number_format($booking->pending_amount, 0) }}</strong></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $booking->created_at->diffInDays(now()) }} days
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Recent Payments</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Payment Date</th>
                                    <th>Customer</th>
                                    <th>Booking #</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Account</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                    <td>{{ $payment->booking->lead->guest_name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary">{{ $payment->booking->booking_number }}</span></td>
                                    <td class="text-success"><strong>₹{{ number_format($payment->amount, 0) }}</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>{{ $payment->paymentAccount->account_name ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Cash Flow Timeline
const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
new Chart(cashFlowCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($cashFlowData)->pluck('date')) !!},
        datasets: [{
            label: 'Cash Collected',
            data: {!! json_encode(collect($cashFlowData)->pluck('amount')) !!},
            borderColor: 'rgba(17, 153, 142, 1)',
            backgroundColor: 'rgba(17, 153, 142, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Payment Status
const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
new Chart(paymentStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentStatusLabels) !!},
        datasets: [{
            data: {!! json_encode($paymentStatusData) !!},
            backgroundColor: [
                'rgba(17, 153, 142, 0.8)',
                'rgba(250, 112, 154, 0.8)',
                'rgba(235, 51, 73, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Outstanding by Age
const outstandingAgeCtx = document.getElementById('outstandingAgeChart').getContext('2d');
new Chart(outstandingAgeCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($outstanding)) !!},
        datasets: [{
            label: 'Outstanding Amount',
            data: {!! json_encode(array_values($outstanding)) !!},
            backgroundColor: [
                'rgba(250, 112, 154, 0.8)',
                'rgba(250, 112, 154, 0.9)',
                'rgba(235, 51, 73, 0.8)',
                'rgba(235, 51, 73, 0.9)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Monthly Collection Trend
const monthlyCollectionCtx = document.getElementById('monthlyCollectionChart').getContext('2d');
new Chart(monthlyCollectionCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($monthlyCollection)->pluck('month')) !!},
        datasets: [{
            label: 'Collected',
            data: {!! json_encode(collect($monthlyCollection)->pluck('collected')) !!},
            borderColor: 'rgba(102, 126, 234, 1)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
@endsection
