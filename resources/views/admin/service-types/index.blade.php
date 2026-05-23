@extends('admin.layouts.app')

@section('content')
    <!-- Font Awesome for Summernote icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        /* Ensure Summernote displays properly in modal */
        .note-editor.note-frame {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }

        .note-toolbar {
            background-color: #f9fafb;
            border-bottom: 1px solid #d1d5db;
            padding: 0.5rem;
        }

        .note-btn-group {
            margin-right: 0.5rem;
        }

        #serviceTypeModal .modal-dialog {
            max-width: 650px;
        }
    </style>

    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Service Types</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" data-bs-toggle="modal"
                    data-bs-target="#serviceTypeModal" onclick="resetForm()">
                    <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                    Add Service Type
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

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Manage Service Types</h6>
                        <p class="text-muted mb-3">Service categories like Cab, Boat, Hotel, etc.</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Providers</th>
                                        <th>Items</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($serviceTypes as $index => $type)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $type->name }}</strong></td>
                                            <td><code>{{ $type->slug }}</code></td>
                                            <td><span class="badge bg-info">{{ $type->service_providers_count }}</span></td>
                                            <td><span class="badge bg-primary">{{ $type->service_items_count }}</span></td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="status{{ $type->id }}"
                                                        {{ $type->is_active ? 'checked' : '' }}
                                                        onchange="toggleStatus({{ $type->id }})">
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary"
                                                    onclick="editServiceType({{ $type->id }})">
                                                    <i data-feather="edit"></i>
                                                </button>
                                                <form action="{{ route('service-types.destroy', $type->id) }}"
                                                    method="POST" class="d-inline" id="delete_form_{{ $type->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDeleteServiceType({{ $type->id }}, '{{ $type->name }}')">
                                                        <i data-feather="trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No service types found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Type Modal -->
    <div class="modal fade" id="serviceTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Service Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="serviceTypeForm" method="POST" action="{{ route('service-types.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" id="slug" required>
                            <small class="text-muted">Lowercase, no spaces (e.g., cab, boat, hotel)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Terms & Conditions</label>
                            <textarea class="form-control" name="terms_conditions" id="terms_conditions" rows="8"></textarea>
                            <small class="text-muted">These terms will be displayed on booking invoices for this service
                                category</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Summernote JS -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

        <script>
            // Initialize Summernote when modal is shown
            const serviceTypeModal = document.getElementById('serviceTypeModal');

            serviceTypeModal.addEventListener('shown.bs.modal', function() {
                const termsField = $('#terms_conditions');
                if (!termsField.data('summernote')) {
                    termsField.summernote({
                        height: 200,
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link']],
                            ['view', ['codeview']]
                        ],
                        placeholder: 'Enter terms & conditions for this service category...'
                    });
                }
            });

            // Destroy Summernote when modal is hidden
            serviceTypeModal.addEventListener('hidden.bs.modal', function() {
                const termsField = $('#terms_conditions');
                if (termsField.data('summernote')) {
                    termsField.summernote('destroy');
                }
            });

            function resetForm() {
                const termsField = $('#terms_conditions');

                // Reset Summernote content
                if (termsField.data('summernote')) {
                    termsField.summernote('code', '');
                }

                document.getElementById('serviceTypeForm').reset();
                document.getElementById('serviceTypeForm').action = "{{ route('service-types.store') }}";
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('modalTitle').textContent = 'Add Service Type';
            }

            function editServiceType(id) {
                // Fetch full service type data including terms_conditions
                fetch("{{ url('admin/service-types') }}/" + id + "/edit")
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('name').value = data.name;
                        document.getElementById('slug').value = data.slug;
                        document.getElementById('is_active').checked = data.is_active;

                        // Set Summernote content after a short delay to ensure it's initialized
                        setTimeout(() => {
                            const termsField = $('#terms_conditions');
                            if (termsField.data('summernote')) {
                                termsField.summernote('code', data.terms_conditions || '');
                            } else {
                                document.getElementById('terms_conditions').value = data.terms_conditions || '';
                            }
                        }, 300);

                        document.getElementById('serviceTypeForm').action = "{{ url('admin/service-types') }}/" + id;
                        document.getElementById('formMethod').value = 'PUT';
                        document.getElementById('modalTitle').textContent = 'Edit Service Type';

                        var modal = new bootstrap.Modal(document.getElementById('serviceTypeModal'));
                        modal.show();
                    });
            }

            function toggleStatus(id) {
                fetch("{{ url('admin/service-types') }}/" + id + "/toggle-status", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Status toggled successfully
                        }
                    });
            }

            // Auto-generate slug from name
            document.getElementById('name').addEventListener('input', function() {
                if (document.getElementById('formMethod').value === 'POST') {
                    document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-')
                        .replace(/(^-|-$)/g, '');
                }
            });

            // Confirm delete service type
            function confirmDeleteServiceType(id, name) {
                Swal.fire({
                    title: 'Delete Service Type?',
                    html: `<p>Are you sure you want to delete <strong>${name}</strong>?</p><p class="text-danger"><strong>Warning:</strong> This will also affect all related providers and items!</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete_form_' + id).submit();
                    }
                });
            }
        </script>
    @endpush
@endsection
