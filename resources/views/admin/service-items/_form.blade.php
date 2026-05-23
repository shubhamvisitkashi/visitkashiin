<div class="mb-3">
    <label class="form-label">Service Type <span class="text-danger">*</span></label>
    <select name="service_type_id" id="service_type_id" class="form-select" required onchange="loadProviders()">
        <option value="">Select Service Type</option>
        @foreach($serviceTypes as $type)
            <option value="{{ $type->id }}" {{ (old('service_type_id', $serviceItem->service_type_id ?? '') == $type->id) ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
    @error('service_type_id')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Service Provider <span class="text-danger">*</span></label>
    <select name="service_provider_id" id="service_provider_id" class="form-select" required>
        <option value="">Select Provider</option>
        @if(isset($providers))
            @foreach($providers as $provider)
                <option value="{{ $provider->id }}" {{ (old('service_provider_id', $serviceItem->service_provider_id ?? '') == $provider->id) ? 'selected' : '' }}>
                    {{ $provider->name }} ({{ ucfirst($provider->type) }})
                </option>
            @endforeach
        @endif
    </select>
    @error('service_provider_id')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Item Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $serviceItem->name ?? '') }}" required>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $serviceItem->description ?? '') }}</textarea>
    @error('description')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Base Price (₹) <span class="text-danger">*</span></label>
        <input type="number" name="base_price" class="form-control" step="0.01" value="{{ old('base_price', $serviceItem->base_price ?? 0) }}" required>
        <small class="text-muted">For own services</small>
        @error('base_price')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Vendor Cost (₹) <span class="text-danger">*</span></label>
        <input type="number" name="vendor_cost" class="form-control" step="0.01" value="{{ old('vendor_cost', $serviceItem->vendor_cost ?? 0) }}" required>
        <small class="text-muted">For vendor services</small>
        @error('vendor_cost')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Capacity (PAX)</label>
    <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $serviceItem->capacity ?? '') }}">
    @error('capacity')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
               {{ old('is_active', $serviceItem->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Active</label>
    </div>
</div>

@push('scripts')
<script>
function loadProviders() {
    const serviceTypeId = document.getElementById('service_type_id').value;
    const providerSelect = document.getElementById('service_provider_id');
    
    if (!serviceTypeId) {
        providerSelect.innerHTML = '<option value="">Select Provider</option>';
        return;
    }
    
    fetch(`/admin/service-providers/by-type/${serviceTypeId}`)
        .then(response => response.json())
        .then(data => {
            providerSelect.innerHTML = '<option value="">Select Provider</option>';
            data.forEach(provider => {
                const option = document.createElement('option');
                option.value = provider.id;
                option.textContent = `${provider.name} (${provider.type.charAt(0).toUpperCase() + provider.type.slice(1)})`;
                providerSelect.appendChild(option);
            });
        });
}
</script>
@endpush
