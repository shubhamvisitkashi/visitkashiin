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
                    <form method="POST" action="{{ route('service-templates.update', $template->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="service_type_id" class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                                <select id="service_type_id" class="form-select @error('service_type_id') is-invalid @enderror" name="service_type_id" required>
                                    <option value="">Select Service Type</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" data-name="{{ $type->name }}" {{ old('service_type_id', $template->service_type_id) == $type->id ? 'selected' : '' }}>
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
                                       name="name" value="{{ old('name', $template->name) }}" 
                                       placeholder="e.g., Innova Crysta, Sedan AC" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                          name="description" rows="2"
                                          placeholder="Brief description of the service">{{ old('description', $template->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12" id="address-field" style="display:none;">
                                <label for="address" class="form-label fw-semibold">Hotel/Homestay Address</label>
                                <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                          name="address" rows="2"
                                          placeholder="Full property address shown on the booking confirmation">{{ old('address', $template->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Shown in the Hotel/Homestay Address section of the Stay booking confirmation</small>
                            </div>

                            <div class="col-md-6" id="property-type-field" style="display:none;">
                                <label for="property_type" class="form-label fw-semibold">Property Type</label>
                                <select id="property_type" class="form-select @error('property_type') is-invalid @enderror" name="property_type">
                                    <option value="">Select Property Type</option>
                                    <option value="hotel" {{ old('property_type', $template->property_type) === 'hotel' ? 'selected' : '' }}>Hotel</option>
                                    <option value="homestay" {{ old('property_type', $template->property_type) === 'homestay' ? 'selected' : '' }}>Homestay</option>
                                </select>
                                @error('property_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6" id="bhk-type-field" style="display:none;">
                                <label for="bhk_type" class="form-label fw-semibold">BHK Type</label>
                                <select id="bhk_type" class="form-select @error('bhk_type') is-invalid @enderror" name="bhk_type">
                                    <option value="">Select BHK Type</option>
                                    <option value="Studio/1 RK" {{ old('bhk_type', $template->bhk_type) === 'Studio/1 RK' ? 'selected' : '' }}>Studio/1 RK</option>
                                    <option value="1 BHK" {{ old('bhk_type', $template->bhk_type) === '1 BHK' ? 'selected' : '' }}>1 BHK</option>
                                    <option value="2 BHK" {{ old('bhk_type', $template->bhk_type) === '2 BHK' ? 'selected' : '' }}>2 BHK</option>
                                    <option value="3 BHK" {{ old('bhk_type', $template->bhk_type) === '3 BHK' ? 'selected' : '' }}>3 BHK</option>
                                    <option value="4 BHK" {{ old('bhk_type', $template->bhk_type) === '4 BHK' ? 'selected' : '' }}>4 BHK</option>
                                    <option value="5 BHK" {{ old('bhk_type', $template->bhk_type) === '5 BHK' ? 'selected' : '' }}>5 BHK</option>
                                    <option value="Villa/Duplex" {{ old('bhk_type', $template->bhk_type) === 'Villa/Duplex' ? 'selected' : '' }}>Villa/Duplex</option>
                                </select>
                                @error('bhk_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="default_selling_price" class="form-label fw-semibold">Default Selling Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" id="default_selling_price" 
                                           class="form-control @error('default_selling_price') is-invalid @enderror" 
                                           name="default_selling_price" value="{{ old('default_selling_price', $template->default_selling_price) }}" 
                                           min="0" required>
                                </div>
                                @error('default_selling_price')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="default_cost_estimate" class="form-label fw-semibold">Default Cost Estimate <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" id="default_cost_estimate" 
                                           class="form-control @error('default_cost_estimate') is-invalid @enderror" 
                                           name="default_cost_estimate" value="{{ old('default_cost_estimate', $template->default_cost_estimate) }}" 
                                           min="0" required>
                                </div>
                                @error('default_cost_estimate')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="capacity" class="form-label fw-semibold">Capacity (Persons)</label>
                                <input type="number" id="capacity" 
                                       class="form-control @error('capacity') is-invalid @enderror" 
                                       name="capacity" value="{{ old('capacity', $template->capacity) }}" 
                                       min="1" placeholder="e.g., 4, 7, 12">
                                @error('capacity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Available for quotations)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> Update Template
                            </button>
                            <a href="{{ route('service-templates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function toggleAddressField() {
    var sel = document.getElementById('service_type_id');
    var opt = sel.options[sel.selectedIndex];
    var name = (opt && opt.getAttribute('data-name') ? opt.getAttribute('data-name') : '').toLowerCase();
    var isStay = name.includes('stay') || name.includes('hotel') || name.includes('homestay');
    document.getElementById('address-field').style.display = isStay ? '' : 'none';
    document.getElementById('property-type-field').style.display = isStay ? '' : 'none';
    if (!isStay) {
        document.getElementById('bhk-type-field').style.display = 'none';
    } else {
        toggleBhkField();
    }
}
function toggleBhkField() {
    var propType = document.getElementById('property_type').value;
    document.getElementById('bhk-type-field').style.display = (propType === 'homestay') ? '' : 'none';
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('service_type_id').addEventListener('change', toggleAddressField);
    document.getElementById('property_type').addEventListener('change', toggleBhkField);
    toggleAddressField();
});
</script>
@endsection
