<div class="mb-3">
    <label class="form-label">Service Types <span class="text-danger">*</span></label>
    <p class="text-muted small">Select all service types this provider offers</p>
    <div class="row">
        @foreach($serviceTypes as $type)
            <div class="col-md-6 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_type_ids[]" 
                           value="{{ $type->id }}" id="service_type_{{ $type->id }}"
                           {{ (isset($serviceProvider) && $serviceProvider->serviceTypes->contains($type->id)) || (is_array(old('service_type_ids')) && in_array($type->id, old('service_type_ids'))) ? 'checked' : '' }}>
                    <label class="form-check-label" for="service_type_{{ $type->id }}">
                        {{ $type->name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    @error('service_type_ids')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Provider Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $serviceProvider->name ?? '') }}" required>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Provider Type <span class="text-danger">*</span></label>
    <select name="type" class="form-select" required>
        <option value="">Select Type</option>
        <option value="vendor" {{ (old('type', $serviceProvider->type ?? '') == 'vendor') ? 'selected' : '' }}>Vendor</option>
        <option value="own" {{ (old('type', $serviceProvider->type ?? '') == 'own') ? 'selected' : '' }}>Own Service</option>
    </select>
    @error('type')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Contact Person</label>
    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $serviceProvider->contact_person ?? '') }}">
    @error('contact_person')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Contact Number</label>
    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $serviceProvider->contact_number ?? '') }}">
    @error('contact_number')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $serviceProvider->email ?? '') }}">
    @error('email')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="3">{{ old('address', $serviceProvider->address ?? '') }}</textarea>
    @error('address')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
               {{ old('is_active', $serviceProvider->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Active</label>
    </div>
</div>
