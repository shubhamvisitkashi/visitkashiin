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
    .metric-card.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .metric-value { font-size: 32px; font-weight: 700; margin: 10px 0; }
    .metric-label { font-size: 14px; opacity: 0.9; }
    .metric-sublabel { font-size: 12px; opacity: 0.8; margin-top: 5px; }
</style>

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Sales Performance Analytics</h4>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('sales-analytics.index') }}">
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
            <div class="metric-card">
                <div class="metric-label">Total Leads</div>
                <div class="metric-value">{{ number_format($totalLeads) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card success">
                <div class="metric-label">Total Bookings</div>
                <div class="metric-value">{{ number_format($totalBookings) }}</div>
                <div class="metric-sublabel">{{ $overallConversionRate }}% conversion rate</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card warning">
                <div class="metric-label">Total Revenue</div>
                <div class="metric-value">₹{{ number_format($totalRevenue, 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card info">
                <div class="metric-label">Avg Deal Size</div>
                <div class="metric-value">₹{{ number_format($avgDealSize, 0) }}</div>
                <div class="metric-sublabel">{{ $salesCycleDays }} days avg cycle</div>
            </div>
        </div>
    </div>

    <!-- Conversion Rates -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">Lead → Quotation</h6>
                    <h2 class="text-primary">{{ $leadToQuotationRate }}%</h2>
                    <p class="text-muted mb-0">{{ number_format($totalQuotations) }} quotations</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">Quotation → Booking</h6>
                    <h2 class="text-success">{{ $quotationToBookingRate }}%</h2>
                    <p class="text-muted mb-0">{{ number_format($totalBookings) }} bookings</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">Overall Conversion</h6>
                    <h2 class="text-info">{{ $overallConversionRate }}%</h2>
                    <p class="text-muted mb-0">Lead to booking</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Sales Funnel</h6>
                    <canvas id="funnelChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Deal Size Distribution</h6>
                    <canvas id="dealSizeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">6-Month Sales Trend</h6>
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Salesperson Performance -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Salesperson Leaderboard</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Total Leads</th>
                                    <th>Converted</th>
                                    <th>Conversion Rate</th>
                                    <th>Total Revenue</th>
                                    <th>Avg Deal Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salespeople as $index => $person)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $person->name }}</strong></td>
                                    <td>{{ $person->total_leads }}</td>
                                    <td>{{ $person->converted_leads }}</td>
                                    <td>
                                        <span class="badge {{ $person->conversion_rate >= 25 ? 'bg-success' : ($person->conversion_rate >= 15 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $person->conversion_rate }}%
                                        </span>
                                    </td>
                                    <td class="text-success"><strong>₹{{ number_format($person->total_revenue, 0) }}</strong></td>
                                    <td>₹{{ number_format($person->avg_deal_size, 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lead Source Performance -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Lead Source Performance</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>Total Leads</th>
                                    <th>Converted</th>
                                    <th>Conversion Rate</th>
                                    <th>Revenue Generated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leadSources as $source)
                                <tr>
                                    <td><strong>{{ $source->name }}</strong></td>
                                    <td>{{ $source->total_leads }}</td>
                                    <td>{{ $source->converted }}</td>
                                    <td>
                                        <span class="badge {{ $source->conversion_rate >= 20 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $source->conversion_rate }}%
                                        </span>
                                    </td>
                                    <td class="text-success"><strong>₹{{ number_format($source->revenue, 0) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Conversions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Recent Conversions</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Booking #</th>
                                    <th>Amount</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentConversions as $booking)
                                <tr>
                                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                                    <td>{{ $booking->lead->guest_name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary">{{ $booking->booking_number }}</span></td>
                                    <td class="text-success"><strong>₹{{ number_format($booking->total_amount, 0) }}</strong></td>
                                    <td>{{ $booking->createdBy->name ?? 'N/A' }}</td>
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
// Sales Funnel Chart
const funnelCtx = document.getElementById('funnelChart').getContext('2d');
new Chart(funnelCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($funnelData['labels']) !!},
        datasets: [{
            label: 'Count',
            data: {!! json_encode($funnelData['data']) !!},
            backgroundColor: [
                'rgba(102, 126, 234, 0.8)',
                'rgba(17, 153, 142, 0.8)',
                'rgba(250, 112, 154, 0.8)',
                'rgba(79, 172, 254, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Deal Size Distribution
const dealSizeCtx = document.getElementById('dealSizeChart').getContext('2d');
new Chart(dealSizeCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($dealSizeLabels) !!},
        datasets: [{
            data: {!! json_encode($dealSizeData) !!},
            backgroundColor: [
                'rgba(102, 126, 234, 0.8)',
                'rgba(17, 153, 142, 0.8)',
                'rgba(250, 112, 154, 0.8)',
                'rgba(79, 172, 254, 0.8)',
                'rgba(255, 193, 7, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Monthly Trend
const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
new Chart(monthlyTrendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($monthlyTrend)->pluck('month')) !!},
        datasets: [
            {
                label: 'Leads',
                data: {!! json_encode(collect($monthlyTrend)->pluck('leads')) !!},
                borderColor: 'rgba(102, 126, 234, 1)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            },
            {
                label: 'Quotations',
                data: {!! json_encode(collect($monthlyTrend)->pluck('quotations')) !!},
                borderColor: 'rgba(17, 153, 142, 1)',
                backgroundColor: 'rgba(17, 153, 142, 0.1)',
                tension: 0.4
            },
            {
                label: 'Bookings',
                data: {!! json_encode(collect($monthlyTrend)->pluck('bookings')) !!},
                borderColor: 'rgba(250, 112, 154, 1)',
                backgroundColor: 'rgba(250, 112, 154, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
@endsection
