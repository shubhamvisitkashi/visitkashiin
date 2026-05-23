@extends('admin.layouts.app')

@section('content')
<style>
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .metric-card.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .metric-card.warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .metric-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .metric-value {
        font-size: 32px;
        font-weight: 700;
        margin: 10px 0;
    }
    .metric-label {
        font-size: 14px;
        opacity: 0.9;
    }
</style>

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Profit Analytics</h4>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('profit-analytics.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Service Type</label>
                                <select name="service_type_id" class="form-select">
                                    <option value="">All Services</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" {{ $serviceTypeId == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Provider Type</label>
                                <select name="provider_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="vendor" {{ $providerType == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                    <option value="own" {{ $providerType == 'own' ? 'selected' : '' }}>Own Service</option>
                                </select>
                            </div>
                            <div class="col-md-2">
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
                <div class="metric-label">Total Revenue</div>
                <div class="metric-value">₹{{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card warning">
                <div class="metric-label">Total Cost</div>
                <div class="metric-value">₹{{ number_format($totalCost, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card success">
                <div class="metric-label">Total Profit</div>
                <div class="metric-value">₹{{ number_format($totalProfit, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card info">
                <div class="metric-label">Profit Margin</div>
                <div class="metric-value">{{ $profitMargin }}%</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Service-wise Profit</h6>
                    <canvas id="serviceWiseChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Monthly Profit Trend</h6>
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Service-wise Breakdown -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Service-wise Breakdown</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service Type</th>
                                    <th>Revenue</th>
                                    <th>Cost</th>
                                    <th>Profit</th>
                                    <th>Margin %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceWiseProfit as $service)
                                <tr>
                                    <td><strong>{{ $service->serviceType->name }}</strong></td>
                                    <td>₹{{ number_format($service->total_revenue, 2) }}</td>
                                    <td>₹{{ number_format($service->total_cost, 2) }}</td>
                                    <td class="text-success"><strong>₹{{ number_format($service->total_profit, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $service->total_revenue > 0 ? number_format(($service->total_profit / $service->total_revenue) * 100, 2) : 0 }}%
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

    <!-- Top Profitable Services -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Top 10 Profitable Services</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service Item</th>
                                    <th>Bookings</th>
                                    <th>Total Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topServices as $index => $service)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service->serviceItem->name }}</td>
                                    <td><span class="badge bg-info">{{ $service->booking_count }}</span></td>
                                    <td class="text-success"><strong>₹{{ number_format($service->total_profit, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Recent Service Bookings</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Lead</th>
                                    <th>Service</th>
                                    <th>Provider</th>
                                    <th>Qty</th>
                                    <th>Selling Price</th>
                                    <th>Cost</th>
                                    <th>Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->service_date->format('d M Y') }}</td>
                                    <td>{{ $booking->lead->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->serviceItem->name }}</td>
                                    <td>
                                        {{ $booking->serviceItem->serviceProvider->name }}
                                        <br><small class="text-muted">({{ ucfirst($booking->serviceItem->serviceProvider->type) }})</small>
                                    </td>
                                    <td>{{ $booking->quantity }}</td>
                                    <td>₹{{ number_format($booking->selling_price, 2) }}</td>
                                    <td>₹{{ number_format($booking->cost_price, 2) }}</td>
                                    <td class="text-success"><strong>₹{{ number_format($booking->profit_amount, 2) }}</strong></td>
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
// Service-wise Profit Chart
const serviceWiseCtx = document.getElementById('serviceWiseChart').getContext('2d');
new Chart(serviceWiseCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($serviceWiseProfit->pluck('serviceType.name')) !!},
        datasets: [{
            data: {!! json_encode($serviceWiseProfit->pluck('total_profit')) !!},
            backgroundColor: [
                'rgba(102, 126, 234, 0.8)',
                'rgba(17, 153, 142, 0.8)',
                'rgba(250, 112, 154, 0.8)',
                'rgba(79, 172, 254, 0.8)',
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Trend Chart
const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
new Chart(monthlyTrendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($monthlyTrend)->pluck('month')) !!},
        datasets: [{
            label: 'Profit',
            data: {!! json_encode(collect($monthlyTrend)->pluck('profit')) !!},
            borderColor: 'rgba(102, 126, 234, 1)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection
