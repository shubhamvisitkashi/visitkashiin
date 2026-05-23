@extends('admin.layouts.app')
@section('content')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            background: #f5f7fa;
        }

        .page-content {
            width: 100%;
            padding: 0 20px;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 20px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header p {
            font-size: 15px;
            opacity: 0.9;
            margin: 0;
        }

        /* Metric Cards */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .metric-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-gradient);
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .metric-title {
            font-size: 13px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
        }

        .metric-icon i {
            color: white;
            font-size: 20px;
        }

        .metric-value {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .metric-change {
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .metric-change.positive {
            color: #38ef7d;
        }

        .metric-change.negative {
            color: #ff6b6b;
        }

        /* Modern Card */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .modern-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modern-card-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        /* Chart Card */
        .chart-card {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 20px;
            height: 100%;
        }

        .chart-title {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Table Styles */
        .modern-table {
            width: 100%;
        }

        .modern-table thead {
            background: #f8f9fc;
        }

        .modern-table thead th {
            padding: 15px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a5568;
            border: none;
        }

        .modern-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f9;
            color: #2d3748;
            font-size: 14px;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fc;
        }

        .badge-high-value {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-dormant {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-new {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Date Filter Styles */
        .filter-container {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .filter-btn.active {
            background: white;
            color: #667eea;
            border-color: white;
        }

        .custom-date-inputs {
            display: none;
            gap: 10px;
            align-items: center;
        }

        .custom-date-inputs.show {
            display: flex;
        }

        .date-input {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 13px;
            backdrop-filter: blur(10px);
        }

        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .apply-filter-btn {
            padding: 8px 20px;
            border-radius: 8px;
            border: none;
            background: white;
            color: #667eea;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .apply-filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        }
    </style>

    <div class="page-content">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2><i data-feather="users" class="me-2"></i>Customer Analytics</h2>
                <p>Comprehensive insights into customer behavior, demographics, and business patterns</p>
                
                <!-- Date Range Filter -->
                <form method="GET" action="{{ route('customer.analytics') }}" id="filterForm">
                    <div class="filter-container">
                        <button type="button" class="filter-btn {{ !request('date_range') || request('date_range') == 'all' ? 'active' : '' }}" onclick="setFilter('all')">
                            All Time
                        </button>
                        <button type="button" class="filter-btn {{ request('date_range') == 'today' ? 'active' : '' }}" onclick="setFilter('today')">
                            Today
                        </button>
                        <button type="button" class="filter-btn {{ request('date_range') == '7days' ? 'active' : '' }}" onclick="setFilter('7days')">
                            Last 7 Days
                        </button>
                        <button type="button" class="filter-btn {{ request('date_range') == '30days' ? 'active' : '' }}" onclick="setFilter('30days')">
                            Last 30 Days
                        </button>
                        <button type="button" class="filter-btn {{ request('date_range') == '6months' ? 'active' : '' }}" onclick="setFilter('6months')">
                            Last 6 Months
                        </button>
                        <button type="button" class="filter-btn {{ request('date_range') == 'year' ? 'active' : '' }}" onclick="setFilter('year')">
                            Last Year
                        </button>
                        <button type="button" class="filter-btn {{ request('date_range') == 'custom' ? 'active' : '' }}" onclick="toggleCustomDates()">
                            Custom Range
                        </button>
                        
                        <div class="custom-date-inputs {{ request('date_range') == 'custom' ? 'show' : '' }}" id="customDates">
                            <input type="date" name="start_date" class="date-input" value="{{ request('start_date') }}" placeholder="Start Date">
                            <input type="date" name="end_date" class="date-input" value="{{ request('end_date') }}" placeholder="End Date">
                            <button type="submit" class="apply-filter-btn">Apply</button>
                        </div>
                        
                        <input type="hidden" name="date_range" id="dateRangeInput" value="{{ request('date_range', 'all') }}">
                    </div>
                </form>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-title">Total Customers</span>
                    <div class="metric-icon" style="background: var(--primary-gradient);">
                        <i data-feather="users"></i>
                    </div>
                </div>
                <div class="metric-value">{{ number_format($total_customers) }}</div>
                <div class="metric-change positive">
                    <i data-feather="trending-up" style="width: 16px; height: 16px;"></i>
                    Unique Customers
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-title">Repeat Rate</span>
                    <div class="metric-icon" style="background: var(--success-gradient);">
                        <i data-feather="repeat"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $repeat_customer_rate }}%</div>
                <div class="metric-change positive">
                    <i data-feather="arrow-up" style="width: 16px; height: 16px;"></i>
                    Customer Retention
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-title">Avg CLV</span>
                    <div class="metric-icon" style="background: var(--info-gradient);">
                        <i data-feather="dollar-sign"></i>
                    </div>
                </div>
                <div class="metric-value">₹{{ number_format($avg_clv, 0) }}</div>
                <div class="metric-change positive">
                    <i data-feather="trending-up" style="width: 16px; height: 16px;"></i>
                    Lifetime Value
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-title">Growth Rate</span>
                    <div class="metric-icon" style="background: {{ $customer_growth >= 0 ? 'var(--success-gradient)' : 'var(--danger-gradient)' }};">
                        <i data-feather="{{ $customer_growth >= 0 ? 'trending-up' : 'trending-down' }}"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $customer_growth >= 0 ? '+' : '' }}{{ $customer_growth }}%</div>
                <div class="metric-change {{ $customer_growth >= 0 ? 'positive' : 'negative' }}">
                    <i data-feather="{{ $customer_growth >= 0 ? 'arrow-up' : 'arrow-down' }}" style="width: 16px; height: 16px;"></i>
                    vs Last Month
                </div>
            </div>
        </div>

        <!-- Analytics Charts Grid -->
        <div class="row g-4 mb-4">
            <!-- Customer Acquisition Trend -->
            <div class="col-lg-8">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="trending-up" style="width: 18px; height: 18px;"></i>
                        Customer Acquisition Trend (6 Months)
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="acquisitionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Customer Segmentation -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="pie-chart" style="width: 18px; height: 18px;"></i>
                        Customer Segmentation
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="segmentationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Geographic Distribution -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="map-pin" style="width: 18px; height: 18px;"></i>
                        Top 10 Cities
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="cityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Revenue by Customer Type -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="dollar-sign" style="width: 18px; height: 18px;"></i>
                        Revenue by Customer Type
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="revenueTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Booking Frequency -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="bar-chart-2" style="width: 18px; height: 18px;"></i>
                        Booking Frequency
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="frequencyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Customers -->
            <div class="col-lg-8">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="award" style="width: 18px; height: 18px;"></i>
                        Top 10 Customers by Revenue
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="topCustomersChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- PAX Distribution -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="users" style="width: 18px; height: 18px;"></i>
                        PAX Distribution
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="paxChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Booking Lead Time -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="chart-title">
                        <i data-feather="clock" style="width: 18px; height: 18px;"></i>
                        Booking Lead Time Analysis
                    </h6>
                    <div style="position: relative; height: 300px;">
                        <canvas id="leadTimeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers Table -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h5>
                    <i data-feather="star" class="me-2"></i>
                    Top 20 Customers
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>City</th>
                                <th>Total Revenue</th>
                                <th>Bookings</th>
                                <th>Total PAX</th>
                                <th>Last Booking</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_customers_table as $customer)
                                <tr>
                                    <td><strong>{{ $customer->guest_name }}</strong></td>
                                    <td>{{ $customer->contact }}</td>
                                    <td>{{ $customer->city ?? 'N/A' }}</td>
                                    <td><strong>₹{{ number_format($customer->total_revenue, 0) }}</strong></td>
                                    <td>{{ $customer->total_bookings }}</td>
                                    <td>{{ $customer->total_pax }}</td>
                                    <td>{{ $customer->last_booking_date ? \Carbon\Carbon::parse($customer->last_booking_date)->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        @if($customer->total_revenue >= 50000)
                                            <span class="badge-high-value">High Value</span>
                                        @elseif($customer->total_bookings >= 3)
                                            <span class="badge-high-value">Loyal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No customer data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Customers Table -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h5>
                    <i data-feather="user-plus" class="me-2"></i>
                    Recent Customers (Last 30 Days)
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>City</th>
                                <th>First Booking Date</th>
                                <th>PAX</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_customers_table as $customer)
                                <tr>
                                    <td><strong>{{ $customer->guest_name }}</strong></td>
                                    <td>{{ $customer->contact }}</td>
                                    <td>{{ $customer->city ?? 'N/A' }}</td>
                                    <td>{{ $customer->created_at->format('d M Y') }}</td>
                                    <td>{{ $customer->pax ?? 'N/A' }}</td>
                                    <td><span class="badge-new">New Customer</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No recent customers</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Dormant Customers Table -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h5>
                    <i data-feather="user-x" class="me-2"></i>
                    Dormant Customers (No Booking in 6+ Months)
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>City</th>
                                <th>Last Booking Date</th>
                                <th>Days Since Last Booking</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dormant_customers_table as $customer)
                                <tr>
                                    <td><strong>{{ $customer->guest_name }}</strong></td>
                                    <td>{{ $customer->contact }}</td>
                                    <td>{{ $customer->city ?? 'N/A' }}</td>
                                    <td>{{ $customer->created_at->format('d M Y') }}</td>
                                    <td>{{ $customer->created_at->diffInDays(now()) }} days</td>
                                    <td><span class="badge-dormant">Dormant</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No dormant customers</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Customer Acquisition Trend
        new Chart(document.getElementById('acquisitionChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'New Customers',
                    data: {!! json_encode($new_customers_trend) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Customer Segmentation
        new Chart(document.getElementById('segmentationChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($segment_labels) !!},
                datasets: [{
                    data: {!! json_encode($segment_data) !!},
                    backgroundColor: ['#4facfe', '#38ef7d', '#fee140', '#ff6b6b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 10, font: { size: 11 } } }
                }
            }
        });

        // Geographic Distribution
        new Chart(document.getElementById('cityChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($city_labels) !!},
                datasets: [{
                    label: 'Customers',
                    data: {!! json_encode($city_data) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    y: { grid: { display: false } }
                }
            }
        });

        // Revenue by Customer Type
        new Chart(document.getElementById('revenueTypeChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenue_by_type_labels) !!},
                datasets: [{
                    label: 'Revenue (₹)',
                    data: {!! json_encode($revenue_by_type_data) !!},
                    backgroundColor: ['#4facfe', '#38ef7d'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Booking Frequency
        new Chart(document.getElementById('frequencyChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($frequency_labels) !!},
                datasets: [{
                    label: 'Customers',
                    data: {!! json_encode($frequency_data) !!},
                    backgroundColor: 'rgba(250, 112, 154, 0.8)',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Top Customers
        new Chart(document.getElementById('topCustomersChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($top_customer_names) !!},
                datasets: [{
                    label: 'Revenue (₹)',
                    data: {!! json_encode($top_customer_revenue) !!},
                    backgroundColor: 'rgba(56, 239, 125, 0.8)',
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    },
                    y: { grid: { display: false } }
                }
            }
        });

        // PAX Distribution
        new Chart(document.getElementById('paxChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($pax_labels) !!},
                datasets: [{
                    label: 'Bookings',
                    data: {!! json_encode($pax_data) !!},
                    backgroundColor: 'rgba(79, 172, 254, 0.8)',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Booking Lead Time
        new Chart(document.getElementById('leadTimeChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($lead_time_labels) !!},
                datasets: [{
                    label: 'Bookings',
                    data: {!! json_encode($lead_time_data) !!},
                    backgroundColor: 'rgba(254, 225, 64, 0.8)',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Date Filter Functions
        function setFilter(range) {
            document.getElementById('dateRangeInput').value = range;
            if (range !== 'custom') {
                document.getElementById('filterForm').submit();
            }
        }

        function toggleCustomDates() {
            const customDates = document.getElementById('customDates');
            const dateRangeInput = document.getElementById('dateRangeInput');
            
            if (customDates.classList.contains('show')) {
                customDates.classList.remove('show');
                dateRangeInput.value = 'all';
            } else {
                customDates.classList.add('show');
                dateRangeInput.value = 'custom';
            }
        }
    </script>
@endsection
