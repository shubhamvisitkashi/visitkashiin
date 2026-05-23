@extends('admin.layouts.app')

@section('content')
<style>
    .activity-log-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }
    
    .activity-log-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .log-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    
    .log-item:hover {
        background: #f9fafb;
    }
    
    .log-item:last-child {
        border-bottom: none;
    }
    
    .log-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .log-icon.created {
        background: #d1fae5;
        color: #10b981;
    }
    
    .log-icon.updated {
        background: #dbeafe;
        color: #3b82f6;
    }
    
    .log-icon.deleted {
        background: #fee2e2;
        color: #ef4444;
    }
    
    .log-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .log-badge.lead { background: #ede9fe; color: #7c3aed; }
    .log-badge.quotation { background: #dbeafe; color: #2563eb; }
    .log-badge.booking { background: #d1fae5; color: #059669; }
    .log-badge.payment { background: #fef3c7; color: #d97706; }
</style>

<div class="page-content">
    <!-- Header -->
    <div class="activity-log-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i data-feather="activity"></i> Activity Logs
                </h2>
                <p class="mb-0 opacity-90">Complete system activity history and audit trail</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark px-3 py-2">
                    <i data-feather="database" style="width: 16px; height: 16px;"></i>
                    {{ $activities->total() }} Total Activities
                </span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('activity-logs.index') }}">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-semibold">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">All Users</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('user_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold">Activity Type</label>
                    <select name="log_name" class="form-select">
                        <option value="">All Types</option>
                        @foreach($logNames as $logName)
                            <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>
                                {{ ucfirst($logName) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                
                <div class="col-lg-3 col-md-12">
                    <label class="form-label small fw-semibold">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search description..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="search" style="width: 16px; height: 16px;"></i>
                        </button>
                        @if(request()->hasAny(['user_id', 'log_name', 'date_from', 'date_to', 'search']))
                        <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-danger">
                            <i data-feather="x" style="width: 16px; height: 16px;"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Activity List -->
    <div class="activity-log-card">
        <div class="card-header bg-light p-3">
            <h5 class="mb-0">
                <i data-feather="list"></i> Activity History
            </h5>
        </div>
        <div class="card-body p-0">
            @forelse($activities as $activity)
            <div class="log-item">
                <div class="d-flex gap-3">
                    <div class="log-icon {{ $activity->event ?? 'updated' }}">
                        @if($activity->event == 'created')
                            <i data-feather="plus" style="width: 20px; height: 20px;"></i>
                        @elseif($activity->event == 'deleted')
                            <i data-feather="trash-2" style="width: 20px; height: 20px;"></i>
                        @else
                            <i data-feather="edit-2" style="width: 20px; height: 20px;"></i>
                        @endif
                    </div>
                    
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong class="text-dark">{{ $activity->description }}</strong>
                                @if($activity->log_name)
                                    <span class="log-badge {{ $activity->log_name }} ms-2">{{ $activity->log_name }}</span>
                                @endif
                            </div>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                        
                        <div class="d-flex gap-3 text-sm">
                            <span class="text-muted">
                                <i data-feather="user" style="width: 14px; height: 14px;"></i>
                                <strong>{{ $activity->causer->name ?? 'System' }}</strong>
                            </span>
                            <span class="text-muted">
                                <i data-feather="calendar" style="width: 14px; height: 14px;"></i>
                                {{ $activity->created_at->format('d M Y, h:i A') }}
                            </span>
                            @if($activity->subject_type)
                                <span class="text-muted">
                                    <i data-feather="tag" style="width: 14px; height: 14px;"></i>
                                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                </span>
                            @endif
                        </div>
                        
                        @if($activity->properties && count($activity->properties) > 0)
                            @if(isset($activity->properties['attributes']) && isset($activity->properties['old']))
                                <div class="mt-2 p-2 bg-light rounded">
                                    <small class="text-muted d-block mb-1"><strong>Changes:</strong></small>
                                    @foreach($activity->properties['attributes'] as $key => $newValue)
                                        @if(isset($activity->properties['old'][$key]) && $activity->properties['old'][$key] != $newValue)
                                            <small class="d-block">
                                                <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                <span class="text-danger text-decoration-line-through">{{ $activity->properties['old'][$key] }}</span>
                                                →
                                                <span class="text-success fw-bold">{{ $newValue }}</span>
                                            </small>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i data-feather="inbox" style="width: 60px; height: 60px;" class="text-muted mb-3"></i>
                <h5 class="text-muted">No Activities Found</h5>
                <p class="text-muted">Try adjusting your filters</p>
            </div>
            @endforelse
        </div>
        
        @if($activities->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-center">
                {{ $activities->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
@endsection
