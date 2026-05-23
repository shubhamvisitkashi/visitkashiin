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
    .health-score {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        margin: 0 auto;
    }
    .insight-card {
        border-left: 4px solid;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    .insight-card.success { border-color: #38ef7d; background: rgba(56, 239, 125, 0.1); }
    .insight-card.warning { border-color: #fee140; background: rgba(254, 225, 64, 0.1); }
    .insight-card.danger { border-color: #f45c43; background: rgba(244, 92, 67, 0.1); }
    .insight-card.info { border-color: #00f2fe; background: rgba(0, 242, 254, 0.1); }
</style>

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Executive Dashboard</h4>
            <p class="text-muted">Real-time business overview and key insights</p>
        </div>
    </div>

    <!-- Key Metrics Row 1 -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-label">MTD Revenue</div>
                <div class="metric-value">₹{{ number_format($mtdRevenue, 0) }}</div>
                <div class="metric-sublabel">
                    @if($revenueGrowth > 0)
                        <i class="mdi mdi-arrow-up"></i> {{ $revenueGrowth }}% vs last month
                    @else
                        <i class="mdi mdi-arrow-down"></i> {{ abs($revenueGrowth) }}% vs last month
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card success">
                <div class="metric-label">MTD Bookings</div>
                <div class="metric-value">{{ number_format($mtdBookings) }}</div>
                <div class="metric-sublabel">
                    @if($bookingsGrowth > 0)
                        <i class="mdi mdi-arrow-up"></i> {{ $bookingsGrowth }}% vs last month
                    @else
                        <i class="mdi mdi-arrow-down"></i> {{ abs($bookingsGrowth) }}% vs last month
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card warning">
                <div class="metric-label">Active Customers</div>
                <div class="metric-value">{{ number_format($activeCustomers) }}</div>
                <div class="metric-sublabel">Last 6 months</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card info">
                <div class="metric-label">Profit Margin</div>
                <div class="metric-value">{{ $profitMargin }}%</div>
                <div class="metric-sublabel">Current month</div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Row 2 -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">YTD Revenue</h6>
                    <h3 class="text-primary">₹{{ number_format($ytdRevenue, 0) }}</h3>
                    <p class="text-muted mb-0">{{ number_format($ytdBookings) }} bookings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">Cash Position</h6>
                    <h3 class="{{ $cashPosition >= 0 ? 'text-success' : 'text-danger' }}">
                        ₹{{ number_format(abs($cashPosition), 0) }}
                    </h3>
                    <p class="text-muted mb-0">{{ $cashPosition >= 0 ? 'Positive' : 'Negative' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">MTD Leads</h6>
                    <h3 class="text-info">{{ number_format($mtdLeads) }}</h3>
                    <p class="text-muted mb-0">This month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title">Business Health</h6>
                    <div class="health-score" style="background: conic-gradient(
                        {{ $healthScore >= 75 ? '#38ef7d' : ($healthScore >= 50 ? '#fee140' : '#f45c43') }} {{ $healthScore * 3.6 }}deg,
                        #e0e0e0 0deg
                    );">
                        <div style="background: white; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span style="color: {{ $healthScore >= 75 ? '#11998e' : ($healthScore >= 50 ? '#fa709a' : '#eb3349') }};">
                                {{ $healthScore }}
                            </span>
                        </div>
                    </div>
                    <p class="text-muted mb-0 mt-2">Health Score</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights & Alerts -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Key Insights</h6>
                    @forelse($insights as $insight)
                        <div class="insight-card {{ $insight['type'] }}">
                            <strong>{{ $insight['message'] }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">No insights available at the moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Alerts</h6>
                    @if($alerts['overdue_payments'] > 0)
                        <div class="alert alert-danger">
                            <strong>{{ $alerts['overdue_payments'] }}</strong> overdue payments
                        </div>
                    @endif
                    @if($alerts['stuck_quotations'] > 0)
                        <div class="alert alert-warning">
                            <strong>{{ $alerts['stuck_quotations'] }}</strong> stuck quotations
                        </div>
                    @endif
                    @if($alerts['low_cash'] > 0)
                        <div class="alert alert-danger">
                            Low cash position!
                        </div>
                    @endif
                    @if($alerts['overdue_payments'] == 0 && $alerts['stuck_quotations'] == 0 && $alerts['low_cash'] == 0)
                        <p class="text-success"><i class="mdi mdi-check-circle"></i> All clear!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 12-Month Trends -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">12-Month Business Trends</h6>
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Recent Bookings</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->created_at->format('d M') }}</td>
                                    <td>{{ $booking->lead->guest_name ?? 'N/A' }}</td>
                                    <td class="text-success">₹{{ number_format($booking->total_amount, 0) }}</td>
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
                    <h6 class="card-title">Recent Payments</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M') }}</td>
                                    <td>{{ $payment->booking->lead->guest_name ?? 'N/A' }}</td>
                                    <td class="text-success">₹{{ number_format($payment->amount, 0) }}</td>
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
// 12-Month Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($trends)->pluck('month')) !!},
        datasets: [
            {
                label: 'Revenue',
                data: {!! json_encode(collect($trends)->pluck('revenue')) !!},
                borderColor: 'rgba(102, 126, 234, 1)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            },
            {
                label: 'Bookings',
                data: {!! json_encode(collect($trends)->pluck('bookings')) !!},
                borderColor: 'rgba(17, 153, 142, 1)',
                backgroundColor: 'rgba(17, 153, 142, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            },
            {
                label: 'Customers',
                data: {!! json_encode(collect($trends)->pluck('customers')) !!},
                borderColor: 'rgba(250, 112, 154, 1)',
                backgroundColor: 'rgba(250, 112, 154, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Revenue (₹)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Count'
                },
                grid: {
                    drawOnChartArea: false
                }
            }
        }
    }
});
</script>
@endpush
@endsection
