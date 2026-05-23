@props(['activities'])

<div class="activity-timeline">
    <style>
        .activity-timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
        
        .activity-item {
            position: relative;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .activity-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .activity-icon {
            position: absolute;
            left: -26px;
            top: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            border: 3px solid #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }
        
        .activity-icon.created {
            border-color: #10b981;
            background: #d1fae5;
        }
        
        .activity-icon.updated {
            border-color: #3b82f6;
            background: #dbeafe;
        }
        
        .activity-icon.deleted {
            border-color: #ef4444;
            background: #fee2e2;
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.5rem;
        }
        
        .activity-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .activity-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-description {
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        
        .activity-meta {
            font-size: 0.813rem;
            color: #6b7280;
        }
        
        .activity-time {
            font-size: 0.75rem;
            color: #9ca3af;
            white-space: nowrap;
        }
        
        .activity-changes {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 6px;
            font-size: 0.813rem;
        }
        
        .change-item {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .change-item:last-child {
            margin-bottom: 0;
        }
        
        .change-label {
            font-weight: 600;
            color: #374151;
            min-width: 100px;
        }
        
        .change-value {
            color: #6b7280;
        }
        
        .change-old {
            text-decoration: line-through;
            color: #ef4444;
        }
        
        .change-new {
            color: #10b981;
            font-weight: 600;
        }
        
        .empty-timeline {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }
        
        .empty-timeline svg {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            opacity: 0.5;
        }
    </style>
    
    @if($activities && $activities->count() > 0)
        @foreach($activities as $activity)
        <div class="activity-item">
            <div class="activity-icon {{ $activity->event ?? 'updated' }}">
                @if($activity->event == 'created')
                    <i data-feather="plus" style="width: 10px; height: 10px; color: #10b981;"></i>
                @elseif($activity->event == 'deleted')
                    <i data-feather="trash-2" style="width: 10px; height: 10px; color: #ef4444;"></i>
                @else
                    <i data-feather="edit-2" style="width: 10px; height: 10px; color: #3b82f6;"></i>
                @endif
            </div>
            
            <div class="activity-header">
                <div class="activity-user">
                    <div class="activity-avatar">
                        {{ $activity->causer ? strtoupper(substr($activity->causer->name, 0, 1)) : 'S' }}
                    </div>
                    <div class="activity-details">
                        <div class="activity-description">
                            {{ $activity->description }}
                        </div>
                        <div class="activity-meta">
                            by <strong>{{ $activity->causer->name ?? 'System' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="activity-time" title="{{ $activity->created_at->format('d M Y, h:i A') }}">
                    {{ $activity->created_at->diffForHumans() }}
                </div>
            </div>
            
            @if($activity->properties && count($activity->properties) > 0)
                @if(isset($activity->properties['attributes']) && isset($activity->properties['old']))
                    <div class="activity-changes">
                        <strong style="display: block; margin-bottom: 0.5rem; color: #374151;">Changes:</strong>
                        @foreach($activity->properties['attributes'] as $key => $newValue)
                            @if(isset($activity->properties['old'][$key]) && $activity->properties['old'][$key] != $newValue)
                                <div class="change-item">
                                    <span class="change-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="change-value">
                                        <span class="change-old">{{ $activity->properties['old'][$key] }}</span>
                                        →
                                        <span class="change-new">{{ $newValue }}</span>
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @elseif(isset($activity->properties['attributes']))
                    <div class="activity-changes">
                        @foreach($activity->properties['attributes'] as $key => $value)
                            <div class="change-item">
                                <span class="change-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                <span class="change-value">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
        @endforeach
        
        @if($activities->hasPages())
        <div class="mt-3">
            {{ $activities->links() }}
        </div>
        @endif
    @else
        <div class="empty-timeline">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h5>No Activity Yet</h5>
            <p>Activity history will appear here</p>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
