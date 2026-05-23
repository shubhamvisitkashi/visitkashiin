@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">Create New Boat</h2>
                <p class="text-muted mb-0">Add a new boat to your fleet</p>
            </div>
            <div>
                <a href="{{route('boat.index')}}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-ship me-2"></i>Boat Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="boat_form" method="POST" action="{{route('boat.store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="boat_type_id" class="form-label fw-semibold"><i class="fas fa-ship text-primary me-1"></i>Boat Type <span class="text-danger">*</span></label>
                                    <select id="boat_type_id" class="form-select form-select-lg border-2" name="boat_type_id" required>
                                        <option value="">Select Boat Type</option>
                                        @foreach($boat_types as $boat_type)
                                            <option value="{{$boat_type->id}}" @if(old('boat_type_id') == $boat_type->id) selected @endif>{{$boat_type->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('boat_type_id')
                                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="event_type" class="form-label fw-semibold"><i class="fas fa-calendar text-primary me-1"></i>Event Type <span class="text-danger">*</span></label>
                                    <select id="event_type" class="form-select form-select-lg border-2" name="event_type" required>
                                        <option value="">Select Event Type</option>
                                        <option value="Regular" @if(old('event_type') == 'Regular') selected @endif>Regular</option>
                                        <option value="Festival" @if(old('event_type') == 'Festival') selected @endif>Festival</option>
                                    </select>
                                    @error('event_type')
                                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="total_available_boat" class="form-label fw-semibold"><i class="fas fa-list-ol text-primary me-1"></i>Total Available Boats <span class="text-danger">*</span></label>
                                    <input id="total_available_boat" class="form-control form-control-lg border-2" type="number" name="total_available_boat" value="{{old('total_available_boat')}}" placeholder="Enter total available boats" min="1" required>
                                    @error('total_available_boat')
                                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="no_of_seat" class="form-label fw-semibold"><i class="fas fa-chair text-primary me-1"></i>Number of Seats (per Boat) <span class="text-danger">*</span></label>
                                    <input id="no_of_seat" class="form-control form-control-lg border-2" type="number" name="no_of_seat" value="{{old('no_of_seat')}}" placeholder="Enter number of seats (per Boat)" min="1" required>
                                    @error('no_of_seat')
                                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="price" class="form-label fw-semibold"><i class="fas fa-rupee-sign text-primary me-1"></i>Regular Price <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light">₹</span>
                                        <input id="price" class="form-control border-2" type="number" name="price" value="{{old('price')}}" placeholder="Enter regular price" min="0" step="0.01" required>
                                    </div>
                                    @error('price')
                                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="discounted_price" class="form-label fw-semibold"><i class="fas fa-tag text-success me-1"></i>Discounted Price <small class="text-muted">(Optional)</small></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light">₹</span>
                                        <input id="discounted_price" class="form-control border-2" type="number" name="discounted_price" value="{{old('discounted_price')}}" placeholder="Enter discounted price" min="0" step="0.01">
                                    </div>
                                    {{-- <div class="form-text"><i class="fas fa-info-circle me-1"></i>Leave empty if no discount applies</div> --}}
                                    @error('discounted_price')
                                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-top">
                                <div class="d-flex gap-3 justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fas fa-save me-2"></i>Save Boat</button>
                                    <button type="reset" class="btn btn-outline-secondary btn-lg px-4"><i class="fas fa-redo me-2"></i>Reset</button>
                                    <a href="{{route('boat.index')}}" class="btn btn-outline-danger btn-lg px-4"><i class="fas fa-times me-2"></i>Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('boat_form').addEventListener('submit', function(e) {
            const regularPrice = parseFloat(document.getElementById('price').value) || 0;
            const discountedPrice = parseFloat(document.getElementById('discounted_price').value) || 0;
            if (discountedPrice > regularPrice && discountedPrice > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Price!',
                    text: 'Discounted price cannot be higher than regular price.',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    <style>
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .card { transition: all 0.3s ease; }
    </style>
@endsection
