@extends('admin.layouts.app')

@section('content')
    <style>
        .targets-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .target-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            border-left: 4px solid #e5e7eb;
        }

        .target-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left-color: #667eea;
        }

        .target-card.achieved {
            border-left-color: #10b981;
            background: linear-gradient(to right, #f0fdf4 0%, white 100%);
        }

        .target-card.not-achieved {
            border-left-color: #f59e0b;
        }

        .progress-custom {
            height: 8px;
            border-radius: 4px;
            background: #e5e7eb;
        }

        .progress-custom .progress-bar {
            border-radius: 4px;
        }

        .stat-badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
        }
    </style>

    <div class="page-content">
        <!-- Header -->
        <div class="targets-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">Target Management</h2>
                    <p class="mb-0 opacity-90">Set and manage monthly margin targets for staff members</p>
                </div>
                <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#setTargetModal">
                    <i data-feather="plus"></i> Set New Target
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('targets.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Month</label>
                        <select name="month" class="form-select">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Year</label>
                        <select name="year" class="form-select">
                            @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="filter"></i> Filter
                        </button>
                        <a href="{{ route('targets.index') }}" class="btn btn-outline-secondary">
                            <i data-feather="x"></i> Clear
                        </a>
                        <form method="POST" action="{{ route('targets.recalculate') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <button type="submit" class="btn btn-success">
                                <i data-feather="refresh-cw"></i> Recalculate All
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>

        <!-- Targets List -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i data-feather="target"></i>
                    Targets for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                </h5>
            </div>
            <div class="card-body">
                @if ($targets->count() > 0)
                    @foreach ($targets as $target)
                        <div class="target-card {{ $target->is_achieved ? 'achieved' : 'not-achieved' }}">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h6 class="mb-1">{{ $target->user->name }}</h6>
                                    <small class="text-muted">{{ $target->user->email }}</small>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="stat-badge bg-light">
                                        <div class="small text-muted">Target</div>
                                        <div class="fw-bold text-primary">₹{{ number_format($target->target_margin, 0) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div
                                        class="stat-badge {{ $target->is_achieved ? 'bg-success-subtle' : 'bg-warning-subtle' }}">
                                        <div class="small text-muted">Achieved</div>
                                        <div class="fw-bold {{ $target->is_achieved ? 'text-success' : 'text-warning' }}">
                                            ₹{{ number_format($target->achieved_margin, 0) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-semibold">Progress</small>
                                        <small class="fw-bold">{{ $target->achievement_percentage }}%</small>
                                    </div>
                                    <div class="progress progress-custom">
                                        <div class="progress-bar {{ $target->is_achieved ? 'bg-success' : 'bg-warning' }}"
                                            style="width: {{ min(100, $target->achievement_percentage) }}%"></div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    @if ($target->is_achieved)
                                        <span class="badge bg-success">
                                            <i data-feather="check-circle" style="width: 12px; height: 12px;"></i>
                                            Achieved
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i data-feather="clock" style="width: 12px; height: 12px;"></i>
                                            In Progress
                                        </span>
                                    @endif
                                    <div class="mt-2">
                                        <a href="{{ route('targets.breakdown', $target->id) }}"
                                            class="btn btn-sm btn-outline-info" title="View Breakdown">
                                            <i data-feather="list" style="width: 12px; height: 12px;"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="editTarget({{ $target->id }}, {{ $target->target_margin }}, '{{ $target->notes }}')">
                                            <i data-feather="edit-2" style="width: 12px; height: 12px;"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteTarget({{ $target->id }}, '{{ $target->user->name }}')">
                                            <i data-feather="trash-2" style="width: 12px; height: 12px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if ($target->notes)
                                <div class="mt-2 pt-2 border-top">
                                    <small class="text-muted">
                                        <i data-feather="message-circle" style="width: 12px; height: 12px;"></i>
                                        {{ $target->notes }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i data-feather="target" style="width: 64px; height: 64px; opacity: 0.3;"></i>
                        <h5 class="mt-3 text-muted">No Targets Set</h5>
                        <p class="text-muted">Set targets for staff members to track their monthly performance</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#setTargetModal">
                            <i data-feather="plus"></i> Set First Target
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Set Target Modal -->
    <div class="modal fade" id="setTargetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('targets.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i data-feather="target"></i> Set New Target
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Staff Member <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">Select Staff Member</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Month <span class="text-danger">*</span></label>
                                <select name="month" class="form-select" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                                <select name="year" class="form-select" required>
                                    @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Target Margin (₹) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="target_margin" class="form-control" placeholder="e.g., 50000"
                                min="0" step="100" required>
                            <small class="text-muted">Monthly margin target (profit) for this staff member</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes or instructions..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i> Set Target
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Target Modal -->
    <div class="modal fade" id="editTargetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editTargetForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i data-feather="edit-2"></i> Edit Target
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Target Margin (₹) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="target_margin" id="edit_target_margin" class="form-control"
                                min="0" step="100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes (Optional)</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i> Update Target
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editTarget(id, targetMargin, notes) {
            document.getElementById('editTargetForm').action = `/admin/targets/${id}`;
            document.getElementById('edit_target_margin').value = targetMargin;
            document.getElementById('edit_notes').value = notes || '';
            new bootstrap.Modal(document.getElementById('editTargetModal')).show();
        }

        function deleteTarget(id, userName) {
            Swal.fire({
                title: 'Delete Target?',
                html: `Are you sure you want to delete the target for <strong>${userName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/targets/${id}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
