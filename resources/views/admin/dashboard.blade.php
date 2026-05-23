@extends('admin.layouts.app')
@section('content')
    <style>
        /* Compact Target Widget Styles */
        .modern-card.target-compact {
            padding: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .modern-card.target-compact .modern-card-header {
            padding: 0 0 0.75rem 0 !important;
            margin-bottom: 0.75rem !important;
        }

        .modern-card.target-compact .modern-card-header h5 {
            font-size: 0.875rem !important;
            margin: 0 !important;
        }

        .modern-card.target-compact .modern-card-body {
            padding: 0 !important;
        }

        .modern-card.target-compact .stat-box {
            padding: 0.625rem !important;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .modern-card.target-compact .stat-label {
            font-size: 0.688rem !important;
            margin-bottom: 0.25rem !important;
        }

        .modern-card.target-compact .stat-value {
            font-size: 1.125rem !important;
        }

        .modern-card.target-compact .progress {
            height: 6px !important;
        }

        .modern-card.target-compact .alert {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.75rem !important;
        }

        .modern-card.target-compact .badge {
            font-size: 0.688rem !important;
            padding: 0.25rem 0.625rem !important;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .modern-card.target-compact {
                padding: 0.875rem !important;
                margin-bottom: 0.875rem !important;
            }

            .modern-card.target-compact .modern-card-header {
                padding: 0.75rem 0.875rem !important;
                margin-bottom: 0.75rem !important;
            }

            .modern-card.target-compact .modern-card-header h5 {
                font-size: 0.813rem !important;
            }

            .modern-card.target-compact .modern-card-header .badge {
                font-size: 0.625rem !important;
                padding: 0.188rem 0.5rem !important;
            }

            /* Keep stats side by side on mobile */
            .modern-card.target-compact .row.g-3 {
                gap: 0.625rem !important;
            }

            .modern-card.target-compact .stat-box {
                padding: 0.5rem !important;
            }

            .modern-card.target-compact .stat-label {
                font-size: 0.625rem !important;
                margin-bottom: 0.188rem !important;
            }

            .modern-card.target-compact .stat-value {
                font-size: 0.938rem !important;
                font-weight: 700;
            }

            .modern-card.target-compact .progress {
                height: 5px !important;
            }

            .modern-card.target-compact .alert {
                padding: 0.438rem 0.625rem !important;
                font-size: 0.688rem !important;
            }

            .modern-card.target-compact .alert i {
                width: 12px !important;
                height: 12px !important;
            }

            .modern-card.target-compact .small {
                font-size: 0.688rem !important;
            }

            /* Stack target cards on mobile */
            .row.g-3.mb-3>.col-lg-6 {
                margin-bottom: 0.5rem;
            }

            .row.g-3.mb-3>.col-lg-6:last-child {
                margin-bottom: 0;
            }
        }

        /* Extra small devices */
        @media (max-width: 576px) {
            .modern-card.target-compact {
                padding: 0.75rem !important;
            }

            .modern-card.target-compact .modern-card-header h5 {
                font-size: 0.75rem !important;
            }

            .modern-card.target-compact .stat-value {
                font-size: 0.875rem !important;
            }

            .modern-card.target-compact .alert {
                font-size: 0.625rem !important;
            }
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --purple-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        body {
            background: #f5f7fa;
        }

        .page-content {
            width: 100%;
            padding: 0 20px;
        }

        /* Welcome Header - Optimized */
        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 30px;
            border-radius: 15px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.25);
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-header h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .welcome-header p {
            font-size: 13px;
            opacity: 0.85;
            margin: 0;
        }

        /* Quick Actions Bar */
        .quick-actions-bar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .quick-actions-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .quick-actions-container::-webkit-scrollbar {
            height: 6px;
        }

        .quick-actions-container::-webkit-scrollbar-track {
            background: #f1f3f9;
            border-radius: 10px;
        }

        .quick-actions-container::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            background: #f8f9fc;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 180px;
            border: 2px solid transparent;
        }

        .quick-action-item:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: #e5e7eb;
        }

        .quick-action-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .quick-action-item:hover .quick-action-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .quick-action-icon i {
            color: white;
            width: 22px;
            height: 22px;
        }

        .quick-action-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .quick-action-title {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }

        .quick-action-subtitle {
            font-size: 12px;
            color: #718096;
        }


        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-gradient);
            transition: width 0.3s ease;
        }

        .stat-card:hover::before {
            width: 100%;
            opacity: 0.05;
        }

        .stat-card.complete::before {
            background: var(--success-gradient);
        }

        .stat-card.confirm::before {
            background: var(--info-gradient);
        }

        .stat-card.followup::before {
            background: var(--warning-gradient);
        }

        .stat-card.cancel::before {
            background: var(--danger-gradient);
        }

        .stat-card-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: transform 0.3s ease;
        }

        .stat-card:hover .stat-card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-card.complete .stat-card-icon {
            background: var(--success-gradient);
        }

        .stat-card.confirm .stat-card-icon {
            background: var(--info-gradient);
        }

        .stat-card.followup .stat-card-icon {
            background: var(--warning-gradient);
        }

        .stat-card.cancel .stat-card-icon {
            background: var(--danger-gradient);
        }

        .stat-card-icon i {
            color: white;
            font-size: 22px;
        }

        .stat-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            color: #2d3748;
            counter-reset: num var(--num);
        }

        .stat-card p {
            font-size: 13px;
            color: #718096;
            margin: 5px 0 0 0;
            font-weight: 500;
        }

        /* Performance Metrics */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
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
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--info-gradient);
        }

        .metric-icon i {
            color: white;
            font-size: 18px;
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

        /* Modern Card Styles */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .modern-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 12px 18px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .modern-card-header h5 {
            margin: 0;
            font-size: 0.938rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .modern-card-header .badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .modern-card-header .btn-light {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 10px;
            padding: 5px 14px;
            transition: all 0.3s ease;
            font-size: 0.813rem;
        }

        .modern-card-header .btn-light:hover {
            background: white;
            color: #667eea;
        }

        /* Target Widget Header Enhancement */
        .modern-card.target-compact .modern-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0.875rem 1rem !important;
            border-radius: 8px;
            margin-bottom: 0.875rem !important;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }

        .modern-card.target-compact .modern-card-header h5 {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modern-card.target-compact .modern-card-header .badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            padding: 0.25rem 0.75rem;
            font-size: 0.688rem;
            border-radius: 20px;
        }

        /* Mobile Responsive Headers */
        @media (max-width: 768px) {
            .modern-card-header {
                padding: 10px 14px;
            }

            .modern-card-header h5 {
                font-size: 0.875rem;
            }

            .modern-card.target-compact .modern-card-header {
                padding: 0.75rem 0.875rem !important;
            }
        }

        /* Modern Table */
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

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fc;
        }

        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .user-avatar i {
            color: white;
        }

        /* Modern Badges */
        .modern-badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-complete {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .badge-confirm {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .badge-followup {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .badge-cancel {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        .badge-new {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Action Button */
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.1;
        }

        .empty-state-icon i {
            font-size: 40px;
            color: white;
        }

        .empty-state h5 {
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .empty-state p {
            color: #a0aec0;
            margin: 0;
            font-size: 14px;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 350px;
            padding: 20px;
        }

        /* Chart Card */
        .chart-card {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 20px;
            height: 100%;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

        /* Summary Stats */
        .summary-stat {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .summary-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .summary-icon i {
            color: white;
            font-size: 22px;
        }

        .summary-content {
            flex: 1;
        }

        .summary-label {
            font-size: 12px;
            color: #718096;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }


        /* Recent Activity Timeline */
        .activity-timeline {
            padding: 20px;
        }

        .activity-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f9;
            transition: all 0.2s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: #f8f9fc;
            padding-left: 10px;
            margin-left: -10px;
            padding-right: 10px;
            margin-right: -10px;
            border-radius: 10px;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .activity-icon.lead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .activity-icon.enquiry {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .activity-icon i {
            color: white;
            font-size: 18px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .activity-description {
            color: #718096;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .activity-time {
            color: #a0aec0;
            font-size: 12px;
        }

        /* Quick Actions */
        .quick-actions {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quick-action-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .quick-action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.5);
        }

        .quick-action-btn i {
            font-size: 24px;
        }

        .quick-action-tooltip {
            position: absolute;
            right: 70px;
            background: #2d3748;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .quick-action-btn:hover .quick-action-tooltip {
            opacity: 1;
        }

        /* Two Column Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .welcome-header {
                padding: 20px;
            }

            .welcome-header h2 {
                font-size: 22px;
            }

            .quick-actions-bar {
                padding: 15px;
                margin-bottom: 20px;
            }

            .quick-action-item {
                min-width: 160px;
                padding: 12px 15px;
            }

            .quick-action-icon {
                width: 40px;
                height: 40px;
            }

            .quick-action-title {
                font-size: 13px;
            }

            .quick-action-subtitle {
                font-size: 11px;
            }

            /* Mobile Responsive Table */
            .upcoming-services-table thead {
                display: none;
            }

            .upcoming-services-table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                padding: 15px;
                background: white;
            }

            .upcoming-services-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border: none;
            }

            .upcoming-services-table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #718096;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                flex-shrink: 0;
                width: 80px;
            }

            .upcoming-services-table tbody td:last-child {
                border-bottom: none;
            }

            .modern-table thead th,
            .modern-table tbody td {
                padding: 10px;
                font-size: 12px;
            }

            .quick-actions {
                bottom: 20px;
                right: 20px;
            }

            .quick-action-btn {
                width: 48px;
                height: 48px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Upcoming Services - Card Layout */
        .upcoming-services-container {
            padding: 25px;
        }

        .service-date-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f3f9;
        }

        .date-badge {
            width: 65px;
            height: 65px;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .date-day {
            font-size: 24px;
            font-weight: 700;
            color: white;
            line-height: 1;
        }

        .date-month {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .date-info {
            flex: 1;
        }

        .date-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .date-subtitle {
            font-size: 13px;
            color: #718096;
        }

        .today-badge,
        .tomorrow-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .today-badge {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .tomorrow-badge {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
            border-color: #667eea;
        }

        /* Service Card Header */
        .service-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px;
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            border-bottom: 1px solid #f1f3f9;
        }

        .service-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .service-icon i {
            color: white;
            width: 22px;
            height: 22px;
        }

        .service-title-section {
            flex: 1;
            min-width: 0;
        }

        .service-name {
            font-size: 15px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .service-type {
            font-size: 12px;
            color: #718096;
        }

        .service-status-badge {
            flex-shrink: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge i {
            width: 12px;
            height: 12px;
        }

        .status-confirmed {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Service Card Body */
        .service-card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .service-info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon i {
            color: white;
            width: 18px;
            height: 18px;
        }

        .customer-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .booking-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .vendor-icon.assigned {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .vendor-icon.unassigned {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        }

        .info-content {
            flex: 1;
            min-width: 0;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .info-meta {
            font-size: 12px;
            color: #a0aec0;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .phone-link {
            color: #667eea;
            text-decoration: none;
            transition: color 0.2s;
        }

        .phone-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .assigned-meta {
            color: #38ef7d;
        }

        .unassigned-meta {
            color: #fa709a;
        }

        /* Service Card Footer */
        .service-card-footer {
            padding: 15px 18px;
            background: #f8f9fc;
            border-top: 1px solid #f1f3f9;
            display: flex;
            gap: 10px;
        }

        .btn-view-details,
        .btn-assign-vendor {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-view-details {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-view-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-assign-vendor {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-assign-vendor:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: scale(0.5);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-count {
            animation: countUp 0.5s ease-out forwards;
        }

        /* Mobile Responsive for Service Cards */
        @media (max-width: 768px) {
            .upcoming-services-container {
                padding: 15px;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .service-date-header {
                margin-bottom: 15px;
            }

            .date-badge {
                width: 55px;
                height: 55px;
            }

            .date-day {
                font-size: 20px;
            }

            .date-title {
                font-size: 16px;
            }

            .date-subtitle {
                font-size: 12px;
            }

            .service-card-header {
                padding: 15px;
            }

            .service-card-body {
                padding: 15px;
            }

            .service-card-footer {
                flex-direction: row;
            }

            .btn-view-details {
                width: 100%;
            }

            /* Hide assign vendor button on mobile */
            .btn-assign-vendor {
                display: none;
            }
        }
    </style>


    <div class="page-content">
        <!-- Welcome Header - Optimized -->
        <div class="welcome-header animate-fade-in-up">
            <div class="welcome-content">
                <h2>👋 Welcome, {{ auth()->user()->name ?? 'Admin' }}!</h2>
                <p>{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>

        <!-- Quick Actions Bar -->
        <div class="quick-actions-bar animate-fade-in-up" style="animation-delay: 0.05s">
            <div class="quick-actions-container">
                @can('lead-create')
                    <a href="{{ route('lead.create') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i data-feather="user-plus"></i>
                        </div>
                        <div class="quick-action-text">
                            <span class="quick-action-title">New Lead</span>
                            <span class="quick-action-subtitle">Add customer</span>
                        </div>
                    </a>
                @endcan

                @can('booking-list')
                    <a href="{{ route('bookings.index') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i data-feather="calendar"></i>
                        </div>
                        <div class="quick-action-text">
                            <span class="quick-action-title">Bookings</span>
                            <span class="quick-action-subtitle">View all</span>
                        </div>
                    </a>
                @endcan

                @can('payment-create')
                    <a href="{{ route('bookings.index') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i data-feather="credit-card"></i>
                        </div>
                        <div class="quick-action-text">
                            <span class="quick-action-title">Payments</span>
                            <span class="quick-action-subtitle">Manage payments</span>
                        </div>
                    </a>
                @endcan

                @can('service-provider-list')
                    <a href="{{ route('service-providers.index') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);">
                            <i data-feather="users"></i>
                        </div>
                        <div class="quick-action-text">
                            <span class="quick-action-title">Vendors</span>
                            <span class="quick-action-subtitle">Manage vendors</span>
                        </div>
                    </a>
                @endcan

                @can('service-template-list')
                    <a href="{{ route('service-templates.index') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                            <i data-feather="package"></i>
                        </div>
                        <div class="quick-action-text">
                            <span class="quick-action-title">Services</span>
                            <span class="quick-action-subtitle">View services</span>
                        </div>
                    </a>
                @endcan

                @can('user-list')
                    <a href="{{ route('users.index') }}" class="quick-action-item">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                            <i data-feather="settings"></i>
                        </div>
                        <div class="quick-action-text">
                            <span class="quick-action-title">Settings</span>
                            <span class="quick-action-subtitle">Manage users</span>
                        </div>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Monthly Target Tracking Widget - Compact & Modern -->
        @php
            $targetService = app(\App\Services\TargetCalculationService::class);

            // Get targets with calculated margins using centralized service
            $currentTarget = $targetService->getCurrentMonthTarget(auth('admin')->id());
            $lastTarget = $targetService->getLastMonthTarget(auth('admin')->id());
        @endphp

        @if ($currentTarget || $lastTarget)
            <div class="row g-3 mb-3">
                <!-- Current Month Target -->
                @if ($currentTarget)
                    <div class="col-lg-6">
                        <div class="modern-card target-compact animate-fade-in-up" style="animation-delay: 0.05s">
                            <div class="modern-card-header">
                                <h5>
                                    <i data-feather="target" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                                    {{ now()->format('F Y') }} Target
                                </h5>
                                @if ($currentTarget->is_achieved)
                                    <span class="badge bg-success">
                                        <i data-feather="check-circle" style="width: 14px; height: 14px;"></i>
                                        Achieved
                                    </span>
                                @endif
                            </div>
                            <div class="modern-card-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="stat-label">Target Margin</div>
                                            <div class="stat-value text-primary">
                                                ₹{{ number_format($currentTarget->target_margin, 0) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="stat-label">Achieved</div>
                                            <div class="stat-value text-success">
                                                ₹{{ number_format($currentTarget->achieved_margin, 0) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small fw-semibold">Progress</span>
                                        <span
                                            class="small fw-bold text-primary">{{ $currentTarget->achievement_percentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 12px; border-radius: 6px;">
                                        <div class="progress-bar {{ $currentTarget->is_achieved ? 'bg-success' : 'bg-primary' }}"
                                            role="progressbar"
                                            style="width: {{ min(100, $currentTarget->achievement_percentage) }}%"
                                            aria-valuenow="{{ $currentTarget->achievement_percentage }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>

                                @if (!$currentTarget->is_achieved)
                                    <div class="alert alert-info mb-0"
                                        style="background: #e0f2fe; border: none; padding: 0.75rem;">
                                        <small>
                                            <i data-feather="info" style="width: 14px; height: 14px;"></i>
                                            <strong>₹{{ number_format($currentTarget->remaining_margin, 0) }}</strong>
                                            remaining to achieve target
                                        </small>
                                    </div>
                                @else
                                    <div class="alert alert-success mb-0"
                                        style="background: #d1fae5; border: none; padding: 0.75rem;">
                                        <small>
                                            <i data-feather="trophy" style="width: 14px; height: 14px;"></i>
                                            Congratulations! Target achieved with
                                            <strong>₹{{ number_format($currentTarget->achieved_margin - $currentTarget->target_margin, 0) }}</strong>
                                            extra margin
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Last Month Summary -->
                @if ($lastTarget)
                    <div class="col-lg-6">
                        <div class="modern-card target-compact animate-fade-in-up" style="animation-delay: 0.1s">
                            <div class="modern-card-header">
                                <h5>
                                    <i data-feather="bar-chart-2" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                                    {{ now()->subMonth()->format('F Y') }} Performance
                                </h5>
                                @if ($lastTarget->is_achieved)
                                    <span class="badge bg-success">
                                        <i data-feather="check-circle" style="width: 14px; height: 14px;"></i>
                                        Achieved
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i data-feather="alert-circle" style="width: 14px; height: 14px;"></i>
                                        Not Achieved
                                    </span>
                                @endif
                            </div>
                            <div class="modern-card-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="stat-label">Target</div>
                                            <div class="stat-value text-muted">
                                                ₹{{ number_format($lastTarget->target_margin, 0) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="stat-label">Achieved</div>
                                            <div
                                                class="stat-value {{ $lastTarget->is_achieved ? 'text-success' : 'text-warning' }}">
                                                ₹{{ number_format($lastTarget->achieved_margin, 0) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Achievement Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small fw-semibold">Achievement</span>
                                        <span
                                            class="small fw-bold {{ $lastTarget->is_achieved ? 'text-success' : 'text-warning' }}">
                                            {{ $lastTarget->achievement_percentage }}%
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 12px; border-radius: 6px;">
                                        <div class="progress-bar {{ $lastTarget->is_achieved ? 'bg-success' : 'bg-warning' }}"
                                            role="progressbar"
                                            style="width: {{ min(100, $lastTarget->achievement_percentage) }}%"
                                            aria-valuenow="{{ $lastTarget->achievement_percentage }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>

                                @if ($lastTarget->is_achieved)
                                    <div class="alert alert-success mb-0"
                                        style="background: #d1fae5; border: none; padding: 0.75rem;">
                                        <small>
                                            <i data-feather="thumbs-up" style="width: 14px; height: 14px;"></i>
                                            Great job! Exceeded target by
                                            <strong>{{ number_format($lastTarget->achievement_percentage - 100, 1) }}%</strong>
                                        </small>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0"
                                        style="background: #fef3c7; border: none; padding: 0.75rem;">
                                        <small>
                                            <i data-feather="trending-down" style="width: 14px; height: 14px;"></i>
                                            Achieved <strong>{{ $lastTarget->achievement_percentage }}%</strong> of target
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Team Target Tracking (Admin Only) -->
        @canany(['dashboard-view', 'staff-list'])
            @php
                // Get all current month targets with calculated margins using service
                $teamTargets = $targetService->getTeamCurrentMonthTargets();

                if ($teamTargets->count() > 0) {
                    $totalTeamTarget = $teamTargets->sum('target_margin');
                    $totalTeamAchieved = $teamTargets->sum('achieved_margin');

                    $teamAchievementPercentage =
                        $totalTeamTarget > 0 ? round(($totalTeamAchieved / $totalTeamTarget) * 100, 2) : 0;
                    $teamIsAchieved = $totalTeamAchieved >= $totalTeamTarget;
                }
            @endphp

            @if (isset($teamTargets) && $teamTargets->count() > 0)
                <div class="modern-card animate-fade-in-up mb-3" style="animation-delay: 0.15s">
                    <div class="modern-card-header">
                        <h5>
                            <i data-feather="users" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                            Team Target - {{ now()->format('F Y') }}
                        </h5>
                        @if ($teamIsAchieved)
                            <span class="badge bg-success">
                                <i data-feather="trophy" style="width: 14px; height: 14px;"></i>
                                Team Goal Achieved
                            </span>
                        @endif
                    </div>
                    <div class="modern-card-body">
                        <!-- Team Summary -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="stat-box" style="background: #f0f9ff; padding: 1rem; border-radius: 8px;">
                                    <div class="stat-label"
                                        style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem;">Team Target</div>
                                    <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: #0284c7;">
                                        ₹{{ number_format($totalTeamTarget / 1000, 0) }}K
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box"
                                    style="background: {{ $teamIsAchieved ? '#f0fdf4' : '#fef3c7' }}; padding: 1rem; border-radius: 8px;">
                                    <div class="stat-label"
                                        style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem;">Team Achieved</div>
                                    <div class="stat-value"
                                        style="font-size: 1.5rem; font-weight: 700; color: {{ $teamIsAchieved ? '#10b981' : '#f59e0b' }};">
                                        ₹{{ number_format($totalTeamAchieved / 1000, 0) }}K
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box" style="background: #faf5ff; padding: 1rem; border-radius: 8px;">
                                    <div class="stat-label"
                                        style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem;">Achievement</div>
                                    <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: #9333ea;">
                                        {{ $teamAchievementPercentage }}%
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box" style="background: #fef2f2; padding: 1rem; border-radius: 8px;">
                                    <div class="stat-label"
                                        style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem;">Team Size</div>
                                    <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: #dc2626;">
                                        {{ $teamTargets->count() }} {{ Str::plural('Member', $teamTargets->count()) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Progress Bar -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Overall Team Progress</span>
                                <span class="fw-bold text-primary">{{ $teamAchievementPercentage }}%</span>
                            </div>
                            <div class="progress" style="height: 14px; border-radius: 7px;">
                                <div class="progress-bar {{ $teamIsAchieved ? 'bg-success' : 'bg-primary' }}"
                                    role="progressbar" style="width: {{ min(100, $teamAchievementPercentage) }}%"
                                    aria-valuenow="{{ $teamAchievementPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <!-- Individual Staff Contributions -->
                        <div class="mb-2">
                            <h6 class="fw-semibold mb-3">
                                <i data-feather="bar-chart-2" style="width: 16px; height: 16px;"></i>
                                Individual Contributions
                            </h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="font-size: 0.875rem;">
                                <thead style="background: #f9fafb;">
                                    <tr>
                                        <th style="padding: 0.75rem;">Staff Member</th>
                                        <th class="text-end" style="padding: 0.75rem;">Target</th>
                                        <th class="text-end" style="padding: 0.75rem;">Achieved</th>
                                        <th class="text-center" style="padding: 0.75rem; width: 200px;">Progress</th>
                                        <th class="text-center" style="padding: 0.75rem;">Contribution</th>
                                        <th class="text-center" style="padding: 0.75rem;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teamTargets->sortByDesc('achieved_margin') as $target)
                                        @php
                                            $contribution =
                                                $totalTeamAchieved > 0
                                                    ? round(($target->achieved_margin / $totalTeamAchieved) * 100, 1)
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td style="padding: 0.75rem;">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle"
                                                        style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.75rem; margin-right: 0.75rem;">
                                                        {{ strtoupper(substr($target->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $target->user->name }}</div>
                                                        <small class="text-muted">{{ $target->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end" style="padding: 0.75rem;">
                                                <span
                                                    class="fw-semibold">₹{{ number_format($target->target_margin, 0) }}</span>
                                            </td>
                                            <td class="text-end" style="padding: 0.75rem;">
                                                <span
                                                    class="fw-semibold text-{{ $target->is_achieved ? 'success' : 'warning' }}">
                                                    ₹{{ number_format($target->achieved_margin, 0) }}
                                                </span>
                                            </td>
                                            <td style="padding: 0.75rem;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1"
                                                        style="height: 8px; border-radius: 4px;">
                                                        <div class="progress-bar {{ $target->is_achieved ? 'bg-success' : 'bg-warning' }}"
                                                            style="width: {{ min(100, $target->achievement_percentage) }}%">
                                                        </div>
                                                    </div>
                                                    <small class="fw-bold" style="min-width: 45px; text-align: right;">
                                                        {{ $target->achievement_percentage }}%
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="text-center" style="padding: 0.75rem;">
                                                <span class="badge"
                                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.75rem;">
                                                    {{ $contribution }}%
                                                </span>
                                            </td>
                                            <td class="text-center" style="padding: 0.75rem;">
                                                @if ($target->is_achieved)
                                                    <span class="badge bg-success">
                                                        <i data-feather="check" style="width: 12px; height: 12px;"></i>
                                                        Done
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i data-feather="clock" style="width: 12px; height: 12px;"></i>
                                                        {{ $target->achievement_percentage }}%
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background: #f9fafb; font-weight: 600;">
                                    <tr>
                                        <td style="padding: 0.75rem;">TOTAL</td>
                                        <td class="text-end" style="padding: 0.75rem;">
                                            ₹{{ number_format($totalTeamTarget, 0) }}</td>
                                        <td class="text-end" style="padding: 0.75rem;">
                                            ₹{{ number_format($totalTeamAchieved, 0) }}</td>
                                        <td colspan="3" class="text-center" style="padding: 0.75rem;">
                                            <span class="badge {{ $teamIsAchieved ? 'bg-success' : 'bg-primary' }}"
                                                style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                                Team Achievement: {{ $teamAchievementPercentage }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if (!$teamIsAchieved)
                            <div class="alert alert-info mt-3 mb-0" style="background: #e0f2fe; border: none;">
                                <i data-feather="info" style="width: 16px; height: 16px;"></i>
                                <strong>₹{{ number_format($totalTeamTarget - $totalTeamAchieved, 0) }}</strong> more needed to
                                achieve team target
                            </div>
                        @else
                            <div class="alert alert-success mt-3 mb-0" style="background: #d1fae5; border: none;">
                                <i data-feather="trophy" style="width: 16px; height: 16px;"></i>
                                Congratulations! Team has exceeded the target by
                                <strong>₹{{ number_format($totalTeamAchieved - $totalTeamTarget, 0) }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endcanany

        <!-- Upcoming Services (Next 3 Days) - Improved UI -->
        @canany(['booking-list', 'dashboard-view'])
            <div class="modern-card animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="modern-card-header">
                    <h5>
                        <i data-feather="calendar" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                        Upcoming Services (Next 3 Days)
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge" id="upcomingServicesCount">0</span>
                        <a href="{{ route('bookings.index') }}" class="btn-light">View All</a>
                    </div>
                </div>
                @php
                    $upcomingServices = \App\Models\Booking::with([
                        'lead',
                        'quotation.items.serviceTemplate.serviceType',
                        'serviceAssignments.serviceProvider',
                    ])
                        ->whereHas('quotation.items', function ($query) {
                            $query
                                ->whereBetween('service_date', [now()->startOfDay(), now()->addDays(3)->endOfDay()])
                                ->whereNotNull('service_date');
                        })
                        ->where('booking_status', '!=', 'cancelled')
                        // Role-based filtering:
                        // - Super Admin, Admin, Manager, Accountant, Viewer: See all bookings
                        // - Staff, Sales: See only their own bookings
                        ->when(
                            !auth()
                                ->user()
                                ->hasAnyRole(['Super Admin', 'Admin', 'Manager', 'Accountant', 'Viewer']),
                            function ($query) {
                                $query->where('created_by', auth()->id());
                            },
                        )
                        ->orderBy('created_at', 'desc')
                        ->get();

                    // Group services by date
                    $servicesByDate = collect();
                    foreach ($upcomingServices as $booking) {
                        $upcomingItems = $booking->quotation->items->filter(function ($item) {
                            return $item->service_date &&
                                $item->service_date->between(now()->startOfDay(), now()->addDays(3)->endOfDay());
                        });

                        foreach ($upcomingItems as $item) {
                            $dateKey = $item->service_date->format('Y-m-d');
                            if (!$servicesByDate->has($dateKey)) {
                                $servicesByDate->put($dateKey, collect());
                            }

                            // Group by booking within each date
                            $bookingKey = $booking->id;
                            $dateServices = $servicesByDate->get($dateKey);

                            if (!$dateServices->has($bookingKey)) {
                                $dateServices->put($bookingKey, [
                                    'booking' => $booking,
                                    'services' => collect(),
                                ]);
                            }

                            $dateServices->get($bookingKey)['services']->push($item);
                        }
                    }
                    $servicesByDate = $servicesByDate->sortKeys();
                    $totalBookings = $servicesByDate->flatten(1)->count();
                @endphp

                @if ($totalBookings > 0)
                    <div class="upcoming-services-container">
                        @foreach ($servicesByDate as $date => $bookingsOnDate)
                            @php
                                $dateObj = \Carbon\Carbon::parse($date);
                                $isToday = $dateObj->isToday();
                                $isTomorrow = $dateObj->isTomorrow();
                                $totalServicesOnDate = $bookingsOnDate->sum(function ($bookingData) {
                                    return $bookingData['services']->count();
                                });
                            @endphp

                            <!-- Date Header -->
                            <div class="service-date-header">
                                <div class="date-badge">
                                    <div class="date-day">{{ $dateObj->format('d') }}</div>
                                    <div class="date-month">{{ $dateObj->format('M') }}</div>
                                </div>
                                <div class="date-info">
                                    <div class="date-title">
                                        @if ($isToday)
                                            <span class="today-badge"><i data-feather="zap"
                                                    style="width: 14px; height: 14px;"></i> Today</span>
                                        @elseif($isTomorrow)
                                            <span class="tomorrow-badge"><i data-feather="sunrise"
                                                    style="width: 14px; height: 14px;"></i> Tomorrow</span>
                                        @else
                                            {{ $dateObj->format('l') }}
                                        @endif
                                    </div>
                                    <div class="date-subtitle">{{ $bookingsOnDate->count() }}
                                        {{ Str::plural('booking', $bookingsOnDate->count()) }} • {{ $totalServicesOnDate }}
                                        {{ Str::plural('service', $totalServicesOnDate) }}</div>
                                </div>
                            </div>

                            <!-- Bookings Grid -->
                            <div class="services-grid">
                                @foreach ($bookingsOnDate as $bookingData)
                                    @php
                                        $booking = $bookingData['booking'];
                                        $services = $bookingData['services'];
                                        $hasUnassigned = $services->contains(function ($item) use ($booking) {
                                            return !$booking->serviceAssignments
                                                ->where('quotation_item_id', $item->id)
                                                ->first();
                                        });
                                    @endphp

                                    <div class="service-card">
                                        <!-- Booking Header -->
                                        <div class="service-card-header">
                                            <div class="service-icon">
                                                <i data-feather="calendar"></i>
                                            </div>
                                            <div class="service-title-section">
                                                <div class="service-name">{{ $booking->lead->guest_name }}</div>
                                                <div class="service-type">
                                                    {{ $booking->booking_number }} • {{ $services->count() }}
                                                    {{ Str::plural('service', $services->count()) }}</div>
                                            </div>
                                            <div class="service-status-badge">
                                                @if ($booking->booking_status == 'confirmed')
                                                    <span class="status-badge status-confirmed">
                                                        <i data-feather="check-circle"></i> Confirmed
                                                    </span>
                                                @elseif($booking->booking_status == 'completed')
                                                    <span class="status-badge status-completed">
                                                        <i data-feather="check"></i> Completed
                                                    </span>
                                                @else
                                                    <span class="status-badge status-pending">
                                                        {{ ucfirst($booking->booking_status) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Booking Body -->
                                        <div class="service-card-body">
                                            <!-- Services List -->
                                            <div class="service-info-row"
                                                style="border-bottom: 1px solid #f1f3f9; padding-bottom: 12px; margin-bottom: 12px;">
                                                <div class="info-icon"
                                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <i data-feather="package"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">Services Scheduled</div>
                                                    <div class="info-value"
                                                        style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px;">
                                                        @foreach ($services as $service)
                                                            @php
                                                                $assignment = $booking->serviceAssignments
                                                                    ->where('quotation_item_id', $service->id)
                                                                    ->first();
                                                            @endphp
                                                            <span class="badge"
                                                                style="background: {{ $assignment ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)' }}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                                <i data-feather="{{ $assignment ? 'check-circle' : 'alert-circle' }}"
                                                                    style="width: 12px; height: 12px;"></i>
                                                                {{ $service->serviceTemplate->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Contact Info -->
                                            <div class="service-info-row">
                                                <div class="info-icon customer-icon">
                                                    <i data-feather="phone"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">Contact</div>
                                                    <div class="info-value">
                                                        <a href="tel:{{ $booking->lead->contact }}"
                                                            class="phone-link">{{ $booking->lead->contact }}</a>
                                                    </div>
                                                    <div class="info-meta">
                                                        <i data-feather="users" style="width: 12px; height: 12px;"></i>
                                                        {{ $booking->lead->pax ?? 'N/A' }}
                                                        {{ Str::plural('person', $booking->lead->pax ?? 0) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Assignment Status -->
                                            @if ($hasUnassigned)
                                                <div class="service-info-row">
                                                    <div class="info-icon vendor-icon unassigned">
                                                        <i data-feather="alert-triangle"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <div class="info-label">Vendor Assignment</div>
                                                        <div class="info-value text-warning">Some services need vendor
                                                            assignment</div>
                                                        <div class="info-meta unassigned-meta">
                                                            <i data-feather="alert-circle"
                                                                style="width: 12px; height: 12px;"></i>
                                                            Action required
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="service-info-row">
                                                    <div class="info-icon vendor-icon assigned">
                                                        <i data-feather="check-circle"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <div class="info-label">Vendor Assignment</div>
                                                        <div class="info-value text-success">All services assigned</div>
                                                        <div class="info-meta assigned-meta">
                                                            <i data-feather="check-circle"
                                                                style="width: 12px; height: 12px;"></i>
                                                            Ready to go
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Booking Footer -->
                                        <div class="service-card-footer">
                                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn-view-details">
                                                <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                                                View Details
                                            </a>
                                            @if ($hasUnassigned)
                                                <a href="{{ route('bookings.show', $booking->id) }}"
                                                    class="btn-assign-vendor">
                                                    <i data-feather="user-plus" style="width: 16px; height: 16px;"></i>
                                                    Assign Vendors
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('upcomingServicesCount').textContent = '{{ $totalBookings }}';
                        });
                    </script>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i data-feather="calendar"></i>
                        </div>
                        <h5>No Upcoming Services</h5>
                        <p>No services scheduled for the next 3 days</p>
                    </div>
                @endif
            </div>
        @endcanany

        <!-- Lead Status Stats -->
        @canany(['lead-list', 'dashboard-view'])
            <div class="stats-grid animate-fade-in-up" style="animation-delay: 0.1s">
                <a href="{{ route('lead.index') }}" class="text-decoration-none">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i data-feather="calendar"></i>
                        </div>
                        <h3 class="animate-count">{{ $total_lead }}</h3>
                        <p>Today's Leads</p>
                    </div>
                </a>

                <a href="{{ route('lead.index') }}" class="text-decoration-none">
                    <div class="stat-card complete">
                        <div class="stat-card-icon">
                            <i data-feather="check-circle"></i>
                        </div>
                        <h3 class="animate-count">{{ $total_complete }}</h3>
                        <p>Completed</p>
                    </div>
                </a>

                <a href="{{ route('lead.index') }}" class="text-decoration-none">
                    <div class="stat-card confirm">
                        <div class="stat-card-icon">
                            <i data-feather="check"></i>
                        </div>
                        <h3 class="animate-count">{{ $total_confirm }}</h3>
                        <p>Confirmed</p>
                    </div>
                </a>

                <a href="{{ route('lead.index') }}" class="text-decoration-none">
                    <div class="stat-card followup">
                        <div class="stat-card-icon">
                            <i data-feather="repeat"></i>
                        </div>
                        <h3 class="animate-count">{{ $total_follow_up }}</h3>
                        <p>Follow Up</p>
                    </div>
                </a>

                <a href="{{ route('lead.index') }}" class="text-decoration-none">
                    <div class="stat-card cancel">
                        <div class="stat-card-icon">
                            <i data-feather="x-circle"></i>
                        </div>
                        <h3 class="animate-count">{{ $total_cancel }}</h3>
                        <p>Cancelled</p>
                    </div>
                </a>
            </div>
        @endcanany

        <!-- Performance Metrics -->
        @canany(['dashboard-view', 'analytics-profit', 'analytics-customer'])
            <div class="metrics-grid animate-fade-in-up" style="animation-delay: 0.15s">
                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Conversion Rate</span>
                        <div class="metric-icon" style="background: var(--success-gradient);">
                            <i data-feather="trending-up"></i>
                        </div>
                    </div>
                    <div class="metric-value">{{ $conversion_rate }}%</div>
                    <div class="metric-change positive">
                        <i data-feather="arrow-up" style="width: 16px; height: 16px;"></i>
                        This Month
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Avg Deal Value</span>
                        <div class="metric-icon" style="background: var(--info-gradient);">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>
                    <div class="metric-value">₹{{ number_format($avg_deal_value, 0) }}</div>
                    <div class="metric-change positive">
                        <i data-feather="arrow-up" style="width: 16px; height: 16px;"></i>
                        Per Booking
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <span class="metric-title">Monthly Growth</span>
                        <div class="metric-icon"
                            style="background: {{ $monthly_growth >= 0 ? 'var(--success-gradient)' : 'var(--danger-gradient)' }};">
                            <i data-feather="{{ $monthly_growth >= 0 ? 'trending-up' : 'trending-down' }}"></i>
                        </div>
                    </div>
                    <div class="metric-value">{{ $monthly_growth >= 0 ? '+' : '' }}{{ $monthly_growth }}%</div>
                    <div class="metric-change {{ $monthly_growth >= 0 ? 'positive' : 'negative' }}">
                        <i data-feather="{{ $monthly_growth >= 0 ? 'arrow-up' : 'arrow-down' }}"
                            style="width: 16px; height: 16px;"></i>
                        vs Last Month
                    </div>
                </div>
            </div>
        @endcanany

        <!-- Dashboard Grid: Chart + Activity -->
        @canany(['dashboard-view', 'analytics-customer', 'activity-log-view'])
            <div class="dashboard-grid animate-fade-in-up" style="animation-delay: 0.2s">
                <!-- Business Analytics Chart -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5>
                            <i data-feather="bar-chart-2" class="me-2"></i>
                            Business Analytics (Last 6 Months)
                        </h5>
                    </div>
                    <div class="chart-container">
                        <canvas id="businessChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5>
                            <i data-feather="activity" class="me-2"></i>
                            Recent Activity
                        </h5>
                    </div>
                    <div class="activity-timeline">
                        @if ($recent_activity->count() > 0)
                            @foreach ($recent_activity as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon {{ $activity['type'] }}">
                                        <i data-feather="{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-description">{{ $activity['description'] }}</div>
                                        <div class="activity-time">
                                            <i data-feather="clock" style="width: 12px; height: 12px;"></i>
                                            {{ $activity['time']->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state" style="padding: 30px 20px;">
                                <div class="empty-state-icon" style="width: 60px; height: 60px;">
                                    <i data-feather="activity" style="font-size: 30px;"></i>
                                </div>
                                <h5 style="font-size: 14px;">No Recent Activity</h5>
                                <p style="font-size: 12px;">Activity will appear here</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endcanany

        <!-- Comprehensive Analytics Section -->
        @canany(['dashboard-view', 'analytics-customer', 'analytics-profit'])
            <div class="modern-card animate-fade-in-up" style="animation-delay: 0.23s">
                <div class="modern-card-header">
                    <h5>
                        <i data-feather="pie-chart" class="me-2"></i>
                        Comprehensive Analytics & Reports
                    </h5>
                </div>
                <div style="padding: 25px;">
                    <!-- Analytics Grid -->
                    <div class="row g-4">
                        <!-- Booking Status Distribution -->
                        <div class="col-lg-4 col-md-6">
                            <div class="chart-card">
                                <h6 class="chart-title">
                                    <i data-feather="pie-chart" style="width: 18px; height: 18px;"></i>
                                    Booking Status Distribution
                                </h6>
                                <div style="position: relative; height: 280px;">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Lead Sources -->
                        <div class="col-lg-4 col-md-6">
                            <div class="chart-card">
                                <h6 class="chart-title">
                                    <i data-feather="users" style="width: 18px; height: 18px;"></i>
                                    Top Lead Sources
                                </h6>
                                <div style="position: relative; height: 280px;">
                                    <canvas id="leadSourceChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Top Cities -->
                        <div class="col-lg-4 col-md-6">
                            <div class="chart-card">
                                <h6 class="chart-title">
                                    <i data-feather="map-pin" style="width: 18px; height: 18px;"></i>
                                    Top Cities
                                </h6>
                                <div style="position: relative; height: 280px;">
                                    <canvas id="cityChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="col-lg-4 col-md-6">
                            <div class="chart-card">
                                <h6 class="chart-title">
                                    <i data-feather="credit-card" style="width: 18px; height: 18px;"></i>
                                    Payment Status
                                </h6>
                                <div style="position: relative; height: 280px;">
                                    <canvas id="paymentChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Trends -->
                        <div class="col-lg-4 col-md-6">
                            <div class="chart-card">
                                <h6 class="chart-title">
                                    <i data-feather="trending-up" style="width: 18px; height: 18px;"></i>
                                    Booking Trends (6 Months)
                                </h6>
                                <div style="position: relative; height: 280px;">
                                    <canvas id="trendChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Pattern -->
                        <div class="col-lg-4 col-md-6">
                            <div class="chart-card">
                                <h6 class="chart-title">
                                    <i data-feather="calendar" style="width: 18px; height: 18px;"></i>
                                    Daily Booking Pattern (7 Days)
                                </h6>
                                <div style="position: relative; height: 280px;">
                                    <canvas id="dailyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Stats Row -->
                    <div class="row g-3 mt-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-stat">
                                <div class="summary-icon" style="background: var(--success-gradient);">
                                    <i data-feather="dollar-sign"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-label">Total Revenue</div>
                                    <div class="summary-value">₹{{ number_format($total_revenue_all, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-stat">
                                <div class="summary-icon" style="background: var(--info-gradient);">
                                    <i data-feather="users"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-label">Avg PAX per Booking</div>
                                    <div class="summary-value">{{ $avg_pax }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-stat">
                                <div class="summary-icon" style="background: var(--warning-gradient);">
                                    <i data-feather="calendar"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-label">Total Bookings</div>
                                    <div class="summary-value">{{ array_sum($booking_trends) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-stat">
                                <div class="summary-icon" style="background: var(--primary-gradient);">
                                    <i data-feather="trending-up"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-label">Conversion Rate</div>
                                    <div class="summary-value">{{ $conversion_rate }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcanany

        <!-- Today's Leads -->
        @can('lead-list')
            <div class="modern-card animate-fade-in-up" style="animation-delay: 0.25s">
                <div class="modern-card-header">
                    <h5>
                        <i data-feather="calendar" class="me-2"></i>
                        Today's Leads
                        <span class="badge ms-2">{{ $today_leads->count() }}</span>
                    </h5>
                    <a href="{{ route('lead.index') }}" class="btn btn-light btn-sm">
                        <i data-feather="arrow-right" class="me-1" style="width: 16px; height: 16px;"></i>View All
                    </a>
                </div>
                <div class="modern-card-body">
                    @if ($today_leads->count() > 0)
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Guest</th>
                                        <th>Contact</th>
                                        <th>Pax</th>
                                        <th>Booking Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($today_leads as $lead)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar">
                                                        <i data-feather="user"></i>
                                                    </div>
                                                    <span class="fw-semibold">{{ $lead->guest_name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <i data-feather="phone" class="text-success me-1"
                                                    style="width: 16px; height: 16px;"></i>
                                                {{ $lead->contact ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <i data-feather="users" class="text-primary me-1"
                                                    style="width: 16px; height: 16px;"></i>
                                                {{ $lead->pax ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    {{ $lead->booking_start_date ? \Carbon\Carbon::parse($lead->booking_start_date)->format('d M Y') : 'N/A' }}
                                                    @if ($lead->booking_end_date)
                                                        <br><small>to
                                                            {{ \Carbon\Carbon::parse($lead->booking_end_date)->format('d M Y') }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($lead->booking_status == 'complete')
                                                    <span class="modern-badge badge-complete">
                                                        <i data-feather="check-circle" style="width: 14px; height: 14px;"></i>
                                                        Complete
                                                    </span>
                                                @elseif($lead->booking_status == 'confirm')
                                                    <span class="modern-badge badge-confirm">
                                                        <i data-feather="check" style="width: 14px; height: 14px;"></i>
                                                        Confirmed
                                                    </span>
                                                @elseif($lead->booking_status == 'follow up')
                                                    <span class="modern-badge badge-followup">
                                                        <i data-feather="repeat" style="width: 14px; height: 14px;"></i>
                                                        Follow Up
                                                    </span>
                                                @elseif($lead->booking_status == 'cancel')
                                                    <span class="modern-badge badge-cancel">
                                                        <i data-feather="x-circle" style="width: 14px; height: 14px;"></i>
                                                        Cancelled
                                                    </span>
                                                @else
                                                    <span class="modern-badge badge-new">
                                                        <i data-feather="clock" style="width: 14px; height: 14px;"></i>
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('lead.show', $lead->id) }}" class="action-btn">
                                                    <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i data-feather="calendar"></i>
                            </div>
                            <h5>No Leads Today</h5>
                            <p>There are no leads with booking starting today.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endcan

        <!-- Today's Enquiries -->
        @can('enquiry-list')
            <div class="modern-card animate-fade-in-up" style="animation-delay: 0.3s">
                <div class="modern-card-header">
                    <h5>
                        <i data-feather="message-circle" class="me-2"></i>
                        New Enquiries
                        <span class="badge ms-2">{{ $today_enquiries->count() }}</span>
                    </h5>
                    <a href="{{ route('enquiry.index') }}" class="btn btn-light btn-sm">
                        <i data-feather="arrow-right" class="me-1" style="width: 16px; height: 16px;"></i>View All
                    </a>
                </div>
                <div class="modern-card-body">
                    @if ($today_enquiries->count() > 0)
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Message</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($today_enquiries as $enquiry)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar">
                                                        <i data-feather="user"></i>
                                                    </div>
                                                    <span class="fw-semibold">{{ $enquiry->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <i data-feather="phone" class="text-success me-1"
                                                    style="width: 16px; height: 16px;"></i>
                                                {{ $enquiry->phone ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <i data-feather="mail" class="text-info me-1"
                                                    style="width: 16px; height: 16px;"></i>
                                                {{ $enquiry->email ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="text-muted" title="{{ $enquiry->message ?? 'No message' }}">
                                                    {{ Str::limit($enquiry->message ?? 'No message', 40) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i data-feather="clock" class="me-1"
                                                        style="width: 14px; height: 14px;"></i>
                                                    {{ $enquiry->created_at->format('h:i A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="modern-badge badge-new">
                                                    <i data-feather="star" style="width: 14px; height: 14px;"></i>
                                                    New
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i data-feather="inbox"></i>
                            </div>
                            <h5>No Enquiries Today</h5>
                            <p>There are no new enquiries for today.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endcan

    <!-- Quick Actions -->
    @can('lead-create')
        <div class="quick-actions">
            <a href="{{ route('lead.create') }}" class="quick-action-btn">
                <i data-feather="plus"></i>
                <span class="quick-action-tooltip">Add New Lead</span>
            </a>
        </div>
    @endcan

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Business Analytics Chart
        const ctx = document.getElementById('businessChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($totalBusiness) !!},
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }, {
                        label: 'Expenses',
                        data: {!! json_encode($totalExpense) !!},
                        borderColor: '#ff6b6b',
                        backgroundColor: 'rgba(255, 107, 107, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#ff6b6b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ₹' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₹' + value.toLocaleString();
                                },
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Booking Status Distribution Chart (Doughnut)
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($status_labels) !!},
                    datasets: [{
                        data: {!! json_encode($status_data) !!},
                        backgroundColor: [
                            '#667eea',
                            '#38ef7d',
                            '#00f2fe',
                            '#fee140',
                            '#ff6b6b'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // Lead Source Chart (Bar)
        const leadSourceCtx = document.getElementById('leadSourceChart');
        if (leadSourceCtx) {
            new Chart(leadSourceCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($lead_source_labels) !!},
                    datasets: [{
                        label: 'Leads',
                        data: {!! json_encode($lead_source_data) !!},
                        backgroundColor: 'rgba(102, 126, 234, 0.8)',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Top Cities Chart (Horizontal Bar)
        const cityCtx = document.getElementById('cityChart');
        if (cityCtx) {
            new Chart(cityCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($city_labels) !!},
                    datasets: [{
                        label: 'Bookings',
                        data: {!! json_encode($city_data) !!},
                        backgroundColor: 'rgba(56, 239, 125, 0.8)',
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Payment Status Chart (Pie)
        const paymentCtx = document.getElementById('paymentChart');
        if (paymentCtx) {
            new Chart(paymentCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($payment_labels) !!},
                    datasets: [{
                        data: {!! json_encode($payment_data) !!},
                        backgroundColor: [
                            '#38ef7d',
                            '#fee140',
                            '#ff6b6b',
                            '#00f2fe'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // Booking Trends Chart (Area)
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'Bookings',
                        data: {!! json_encode($booking_trends) !!},
                        borderColor: '#4facfe',
                        backgroundColor: 'rgba(79, 172, 254, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#4facfe',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Daily Booking Pattern Chart (Line)
        const dailyCtx = document.getElementById('dailyChart');
        if (dailyCtx) {
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($daily_labels) !!},
                    datasets: [{
                        label: 'Bookings',
                        data: {!! json_encode($daily_bookings) !!},
                        borderColor: '#fa709a',
                        backgroundColor: 'rgba(250, 112, 154, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#fa709a',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
