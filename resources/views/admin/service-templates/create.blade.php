@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4>{{ $page_title }}</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('service-templates.index') }}" class="btn btn-secondary btn-sm">
                                <i data-feather="arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('service-templates.store') }}">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="service_type_id" class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                                <select id="service_type_id" class="form-select @error('service_type_id') is-invalid @enderror" name="service_type_id" required>
                                    <option value="">Select Service Type</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('service_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_type_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" 
                                       placeholder="e.g., Innova Crysta, Sedan AC" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">This will be shown to sales team in quotations</small>
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="2" 
                                          placeholder="Brief description of the service">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="default_selling_price" class="form-label fw-semibold">Default Selling Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" id="default_selling_price" 
                                           class="form-control @error('default_selling_price') is-invalid @enderror" 
                                           name="default_selling_price" value="{{ old('default_selling_price', 0) }}" 
                                           min="0" required>
                                </div>
                                @error('default_selling_price')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Price shown to customer</small>
                            </div>

                            <div class="col-md-4">
                                <label for="default_cost_estimate" class="form-label fw-semibold">Default Cost Estimate <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" id="default_cost_estimate" 
                                           class="form-control @error('default_cost_estimate') is-invalid @enderror" 
                                           name="default_cost_estimate" value="{{ old('default_cost_estimate', 0) }}" 
                                           min="0" required>
                                </div>
                                @error('default_cost_estimate')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Estimated cost for profit calculation</small>
                            </div>

                            <div class="col-md-4">
                                <label for="capacity" class="form-label fw-semibold">Capacity (Persons)</label>
                                <input type="number" id="capacity" 
                                       class="form-control @error('capacity') is-invalid @enderror" 
                                       name="capacity" value="{{ old('capacity') }}" 
                                       min="1" placeholder="e.g., 4, 7, 12">
                                @error('capacity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Available for quotations)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> Create Template
                            </button>
                            <a href="{{ route('service-templates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
