@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>{{$page_title}}</h4>
                                </div>
                            </div>
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="valid_form" method="POST" action="{{route('web_setup.store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="banner" class="form-label">Banner</label>
                                    <input id="banner" class="form-control" type="file" name="banner" accept="image/*">
                                    <input type="hidden" name="type[]" value="banner">
                                    <img src="{{asset('backend/admin/website_setup/'.websiteSetupValue('banner'))}}" class="mt-2" alt="Logo" height="50" width="150" onerror="this.onerror=null; this.src='{{asset('backend/no-pictures.png')}}'">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="banner_title" class="form-label">Banner Title</label>
                                    <input id="banner_title" class="form-control" type="text" name="banner_title" placeholder="Enter Contact Number" value="{{websiteSetupValue('banner_title')}}">
                                    <input type="hidden" name="type[]" value="banner_title">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="banner_description" class="form-label">Banner Description</label>
                                    <input id="banner_description" class="form-control" type="text" name="banner_description" placeholder="Enter Contact Number" value="{{websiteSetupValue('banner_description')}}">
                                    <input type="hidden" name="type[]" value="banner_description">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input id="logo" class="form-control" type="file" name="logo" accept="image/*">
                                    <input type="hidden" name="type[]" value="logo">
                                    <img src="{{asset('backend/admin/website_setup/'.websiteSetupValue('logo'))}}" class="mt-2" alt="Logo" height="50" width="150" onerror="this.onerror=null; this.src='{{asset('backend/no-pictures.png')}}'">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="favicon" class="form-label">Favicon</label>
                                    <input id="favicon" class="form-control" type="file" name="favicon" accept="image/*">
                                    <input type="hidden" name="type[]" value="favicon">
                                    <img src="{{asset('backend/admin/website_setup/'.websiteSetupValue('favicon'))}}" class="mt-2" alt="Favicon" height="50" width="50" onerror="this.onerror=null; this.src='{{asset('backend/no-pictures.png')}}'">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="footer_logo" class="form-label">Footer Logo</label>
                                    <input id="footer_logo" class="form-control" type="file" name="footer_logo" accept="image/*">
                                    <input type="hidden" name="type[]" value="footer_logo">
                                    <img src="{{asset('backend/admin/website_setup/'.websiteSetupValue('footer_logo'))}}" class="mt-2" alt="Logo" height="50" width="150" onerror="this.onerror=null; this.src='{{asset('backend/no-pictures.png')}}'">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="footer_description" class="form-label">Footer Description</label>
                                    <input id="footer_description" class="form-control" type="text" name="footer_description" placeholder="Enter Contact Number" value="{{websiteSetupValue('footer_description')}}">
                                    <input type="hidden" name="type[]" value="footer_description">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input id="contact_number" class="form-control" type="number" name="contact_number" placeholder="Enter Contact Number" value="{{websiteSetupValue('contact_number')}}">
                                    <input type="hidden" name="type[]" value="contact_number">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="whats_app_number" class="form-label">WhatsApp Number</label>
                                    <input id="whats_app_number" class="form-control" type="text" name="whats_app_number" placeholder="Enter Contact Number" value="{{websiteSetupValue('whats_app_number')}}">
                                    <input type="hidden" name="type[]" value="whats_app_number">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" class="form-control" type="email" name="email" placeholder="Enter Email" value="{{websiteSetupValue('email')}}">
                                    <input type="hidden" name="type[]" value="email">
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" class="form-control" id="address" rows="2" placeholder="Enter Full Address">{{websiteSetupValue('address')}}</textarea>
                                    <input type="hidden" name="type[]" value="address">
                                </div>
                                
                                <!-- GST Settings -->
                                <div class="col-md-12 mb-2">
                                    <hr>
                                    <h5 class="text-primary">
                                        <i data-feather="file-text"></i> GST Invoice Settings
                                    </h5>
                                    <small class="text-muted">These details will appear on GST invoices</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="company_gstin" class="form-label">Company GSTIN</label>
                                    <input id="company_gstin" class="form-control" type="text" name="company_gstin" placeholder="22AAAAA0000A1Z5" value="{{websiteSetupValue('company_gstin')}}" maxlength="15">
                                    <input type="hidden" name="type[]" value="company_gstin">
                                    <small class="text-muted">15-digit GST Identification Number</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="company_legal_name" class="form-label">Company Legal Name</label>
                                    <input id="company_legal_name" class="form-control" type="text" name="company_legal_name" placeholder="Visit Kashi Pvt Ltd" value="{{websiteSetupValue('company_legal_name')}}">
                                    <input type="hidden" name="type[]" value="company_legal_name">
                                    <small class="text-muted">Registered company name as per GST</small>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="notice" class="form-label">Notice</label>
                                    <textarea name="notice" class="form-control" id="editor" rows="10" placeholder="Enter Notice" required>{{websiteSetupValue('notice')}}</textarea>
                                    <input type="hidden" name="type[]" value="notice">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="notice" class="form-label">Privacy Policy</label>
                                    <textarea name="privacy_policy" class="form-control" id="editor1" rows="10" placeholder="Enter Privacy Policy" required>{{websiteSetupValue('privacy_policy')}}</textarea>
                                    <input type="hidden" name="type[]" value="privacy_policy">
                                </div>
                                
                                <hr>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group flatpickr">
                                        <span class="input-group-text input-group-addon bg-primary" data-toggle><i class="text-white" data-feather="facebook"></i></span>
                                        <input type="text" name="facebook" class="form-control" placeholder="Enter Facebook Link" value="{{websiteSetupValue('facebook')}}" data-input>
                                        <input type="hidden" name="type[]" value="facebook">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group flatpickr">
                                        <span class="input-group-text input-group-addon bg-info" data-toggle><i class="text-white" data-feather="twitter"></i></span>
                                        <input type="text" name="twitter" class="form-control" placeholder="Enter Twitter Link" value="{{websiteSetupValue('twitter')}}" data-input>
                                        <input type="hidden" name="type[]" value="twitter">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group flatpickr">
                                        <span class="input-group-text input-group-addon bg-danger" data-toggle><i class="text-white" data-feather="instagram"></i></span>
                                        <input type="text" name="instagram" class="form-control" placeholder="Enter Instagram Link" value="{{websiteSetupValue('instagram')}}" data-input>
                                        <input type="hidden" name="type[]" value="instagram">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group flatpickr">
                                        <span class="input-group-text input-group-addon bg-danger" data-toggle><i class="text-white" data-feather="youtube"></i></span>
                                        <input type="text" name="youtube" class="form-control" placeholder="Enter Youtube Link" value="{{websiteSetupValue('youtube')}}" data-input>
                                        <input type="hidden" name="type[]" value="youtube">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group flatpickr">
                                        <span class="input-group-text input-group-addon bg-primary" data-toggle><i class="text-white" data-feather="linkedin"></i></span>
                                        <input type="text" name="linkedin" class="form-control" placeholder="Enter Linkedin Link" value="{{websiteSetupValue('linkedin')}}" data-input>
                                        <input type="hidden" name="type[]" value="linkedin">
                                    </div>
                                </div>
                            </div>
                            <x-save-btn text="Save" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
