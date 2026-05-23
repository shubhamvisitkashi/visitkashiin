<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\AgentServiceController;
use App\Http\Controllers\Admin\ChangePasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin Route

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('customer-analytics', 'App\Http\Controllers\Admin\CustomerAnalyticsController@index')->name('customer.analytics');
        Route::post('change_theme', [DashboardController::class, 'changeTheme'])->name('admin.theme');

        Route::group(['namespace' => 'App\Http\Controllers\Admin'], function () {

            // Category
            Route::resource('category', 'CategoryController');
            Route::get('get-subcategory/{categoryId}', 'CategoryController@getSubCategory')->name('getSubCategory');
            Route::get('update-on-home-status/{categoryId}', 'CategoryController@updateOnHomeStatus')->name('admin.update.on.home.status');
            Route::get('update-show-price-status/{categoryId}', 'CategoryController@updateShowPriceStatus')->name('admin.update.show.price.status');

            // Sub Category
            Route::resource('sub-category', 'SubCategoryController');

            // Product
            Route::resource('product', 'ProductController');
            Route::get('product-status-update/{id}', 'ProductController@statusUpdate')->name('product.statusUpdate');
            Route::get('product-on-home-status-update/{id}', 'ProductController@statusOnHomeUpdate')->name('admin.product.on.home.status.update');

            //Vendor
            Route::resource('vendor', 'VendorController');
            Route::get('vendor-status-update/{id}', 'VendorController@statusUpdate')->name('vendor.statusUpdate');

            //Service Templates
            Route::resource('service-templates', 'ServiceTemplateController');

            //Quotations
            Route::resource('quotations', 'QuotationController');
            Route::patch('quotations/{id}/update-status', 'QuotationController@updateStatus')->name('quotations.update-status');
            Route::post('quotations/{id}/convert-to-booking', 'QuotationController@convertToBooking')->name('quotations.convert-to-booking');
            Route::get('quotations/{id}/download-pdf', 'QuotationController@downloadPdf')->name('quotations.download-pdf');
            Route::post('quotations/{id}/send-email', 'QuotationController@sendEmail')->name('quotations.send-email');


            //Bookings
            // Direct Booking Routes (Must be BEFORE bookings/{id} to avoid conflicts)
            Route::get('bookings/create-direct', 'DirectBookingController@create')->name('bookings.create-direct');
            Route::post('bookings/store-direct', 'DirectBookingController@store')->name('bookings.store-direct');
            Route::post('bookings/save-draft', 'DirectBookingController@saveDraft')->name('bookings.save-draft');
            
            Route::get('bookings', 'BookingController@index')->name('bookings.index');
            Route::get('bookings/trash/list', 'BookingController@trash')->name('bookings.trash');
            Route::get('bookings/{id}', 'BookingController@show')->name('bookings.show');
            Route::get('bookings/{id}/edit', 'BookingController@edit')->name('bookings.edit');
            Route::put('bookings/{id}', 'BookingController@update')->name('bookings.update');
            Route::delete('bookings/{id}', 'BookingController@destroy')->name('bookings.destroy');
            Route::post('bookings/{id}/restore', 'BookingController@restore')->name('bookings.restore');
            Route::delete('bookings/{id}/force-delete', 'BookingController@forceDelete')->name('bookings.force-delete');
            Route::put('bookings/{id}/short-plan', 'BookingController@updateShortPlan')->name('bookings.update-short-plan');
            Route::put('bookings/{id}/tour-plan', 'BookingController@updateTourPlan')->name('bookings.update-tour-plan');
            Route::post('bookings/{id}/add-payment', 'BookingController@addPayment')->name('bookings.add-payment');
            Route::put('bookings/{bookingId}/payments/{paymentId}', 'BookingController@updatePayment')->name('bookings.update-payment');
            Route::delete('bookings/{bookingId}/payments/{paymentId}', 'BookingController@deletePayment')->name('bookings.delete-payment');
            Route::post('bookings/{id}/update-status', 'BookingController@updateStatus')->name('bookings.update-status');
            Route::post('bookings/{id}/assign-service', 'BookingController@assignService')->name('bookings.assign-service');
            Route::post('bookings/{id}/add-service', 'BookingController@addService')->name('bookings.add-service');
            Route::delete('bookings/{id}/delete-service/{itemId}', 'BookingController@deleteService')->name('bookings.delete-service');
            Route::get('booking-invoice/{id}', 'BookingController@invoice')->name('booking.invoice');
            Route::get('booking-gst-invoice/{id}', 'BookingController@gstInvoice')->name('booking.gst-invoice');
            Route::get('booking-report/{id}/view', 'BookingController@viewReport')->name('booking.report.view');
            Route::get('booking-report/{id}', 'BookingController@downloadReport')->name('booking.report');
            Route::get('booking-report/{id}/export', 'BookingController@exportReport')->name('booking.report.export');            Route::post('bookings/{id}/update-gst', 'BookingController@updateGstDetails')->name('bookings.update-gst');
            Route::get('bookings-calendar', 'BookingController@calendar')->name('bookings.calendar');
            Route::get('bookings-calendar/events', 'BookingController@calendarEvents')->name('bookings.calendar.events');

            // Service Calendar
            Route::get('service-calendar', 'ServiceCalendarController@index')->name('service-calendar.index');
            Route::get('service-calendar/events', 'ServiceCalendarController@getEvents')->name('service-calendar.events');


            // Vendor Settlements
            Route::get('vendor-settlements', 'VendorSettlementController@index')->name('vendor-settlements.index');
            Route::get('vendor-settlements/{id}', 'VendorSettlementController@show')->name('vendor-settlements.show');
            Route::post('vendor-settlements/{id}/add-payment', 'VendorSettlementController@addPayment')->name('vendor-settlements.add-payment');

            //Leads
            Route::resource('lead', 'LeadController');
            Route::get('lead-details/{id}', 'LeadController@details')->name('lead.details');
            Route::post('vendorStore', 'LeadController@vendorStore')->name('vendor_store');
            Route::post('booking-status/{id}', 'LeadController@bookingStatus')->name('booking.status');
            Route::post('pay-status/{id}', 'LeadController@payStatus')->name('pay.status');
            Route::post('payment-status/{id}', 'LeadController@paymentStatus')->name('payment.status');
            Route::get('lead-invoice/{id}', 'LeadController@invoice')->name('lead.invoice');
            Route::get('lead-legacy-details/{id}', 'LeadController@legacyDetails')->name('lead.legacy-details');
            Route::get('leads-calendar', 'LeadController@calendar')->name('leads.calendar');
            Route::get('leads-calendar/events', 'LeadController@calendarEvents')->name('leads.calendar.events');

            // Quick Booking Routes
            Route::get('quick-booking/{leadId}', 'QuickBookingController@showQuickBookingForm')->name('quick-booking.form');
            Route::post('quick-booking/{leadId}', 'QuickBookingController@createQuickBooking')->name('quick-booking.create');
            Route::post('quick-quotation/{leadId}', 'QuickBookingController@createQuickQuotation')->name('quick-quotation.create');


            // Package
            Route::resource('package', 'PackageController');
            Route::get('package-status-update/{id}', 'PackageController@statusUpdate')->name('package.statusUpdate');

            //Enquiry
            Route::get('enquiry-index','EnquiryController@index')->name('enquiry.index');
            Route::delete('enquiry-delete/{id}','EnquiryController@destroy')->name('enquiry.delete');

            // Website Setup Route
            Route::resource('web_setup', 'WebsiteSetupController');

            //Image Upload
            Route::post('image-upload','ImageUploadController@upload')->name('image.upload');

            // Staff Route
            Route::resource('staffs', 'StaffController');
            Route::resource('roles', 'RoleController');

            //Agent Service
            Route::resource('agent-service', 'AgentServiceController')->except('create');

            //Agent
            Route::resource('agent', 'AgentController');
            Route::post('get-city','AgentController@getCity')->name('get.city');

            //Vendor Service
            Route::resource('vendor-service', VendorServiceController::class)->except('create');

            //Lead Source
            Route::resource('lead-source', LeadSourceController::class);

            //Boat Type
            Route::resource('boat-type', BoatTypeController::class)->except('create');

            //Boat
            Route::resource('boat', BoatController::class);

            //Boat Booking
            Route::resource('boat-booking', BoatBookingController::class);
            Route::post('boat-booking/check-availability', 'BoatBookingController@checkAvailability')->name('boat-booking.check.availability');
            Route::get('send-booking-mail/{booking_id}', 'BoatBookingController@sendBookingMail')->name('send.booking.mail');
            Route::get('boat-booking-payment/{booking_id}', 'BoatBookingController@payment')->name('boat-booking.payment');
            Route::post('boat-booking-payment/{booking_id}', 'BoatBookingController@paymentStore')->name('boat-booking.payment.store');
            Route::get('boat-booking-requests', 'BoatBookingController@bookingRequest')->name('boat-booking.requests');
            Route::get('boat-booking-requests', 'BoatBookingController@bookingRequest')->name('boat-booking.requests');
            Route::post('/boat-booking-request/{booking_request_id}/cancel', 'BoatBookingController@cancelBookingRequest')->name('boat-booking-request.cancel');

            // Service Management Routes
            // Service Types
            Route::resource('service-types', 'ServiceTypeController')->except(['show', 'create', 'edit']);
            Route::get('service-types/{serviceType}/edit', 'ServiceTypeController@edit')->name('service-types.edit');
            Route::post('service-types/{serviceType}/toggle-status', 'ServiceTypeController@toggleStatus')->name('service-types.toggle-status');
            
            // Service Providers
            Route::resource('service-providers', 'ServiceProviderController');
            Route::get('service-providers/by-type/{serviceTypeId}', 'ServiceProviderController@getByServiceType')->name('service-providers.by-type');
            Route::get('service-providers/by-template/{templateId}', 'ServiceProviderController@getByTemplate')->name('service-providers.by-template');
            
            // Service Items
            Route::resource('service-items', 'ServiceItemController');
            Route::get('service-items/by-provider/{providerId}', 'ServiceItemController@getByProvider')->name('service-items.by-provider');
            Route::post('service-items/calculate-profit', 'ServiceItemController@calculateProfit')->name('service-items.calculate-profit');
            
            // Profit Analytics
            Route::get('profit-analytics', 'ProfitAnalyticsController@index')->name('profit-analytics.index');
            Route::get('profit-analytics/export', 'ProfitAnalyticsController@exportReport')->name('profit-analytics.export');

            // New Analytics Dashboards
            Route::get('sales-analytics', 'SalesAnalyticsController@index')->name('sales-analytics.index');
            Route::get('payment-analytics', 'PaymentAnalyticsController@index')->name('payment-analytics.index');
            Route::get('executive-dashboard', 'ExecutiveDashboardController@index')->name('executive-dashboard.index');

            // Payment Accounts
            Route::get('payment-accounts/by-type/{type}', 'PaymentAccountController@getByType')->name('payment-accounts.by-type');
            Route::resource('payment-accounts', 'PaymentAccountController');
            Route::post('payment-accounts/{id}/toggle-status', 'PaymentAccountController@toggleStatus')->name('payment-accounts.toggle-status');

            // Target Management
            Route::get('targets', 'UserTargetController@index')->name('targets.index');
            Route::post('targets', 'UserTargetController@store')->name('targets.store');
            Route::put('targets/{id}', 'UserTargetController@update')->name('targets.update');
            Route::delete('targets/{id}', 'UserTargetController@destroy')->name('targets.destroy');
            Route::post('targets/recalculate', 'UserTargetController@recalculateAll')->name('targets.recalculate');
            Route::get('targets/{id}/breakdown', 'UserTargetController@breakdown')->name('targets.breakdown');
            Route::get('my-targets', 'UserTargetController@myTargets')->name('my-targets');

            // Activity Logs
            Route::get('activity-logs', 'ActivityLogController@index')->name('activity-logs.index');

        });

        //Change Password
        Route::get('change-password', ChangePasswordController::class)->name('change.password');
        Route::post('change-password', [ChangePasswordController::class, 'changePassword'])->name('change.password.store');

        // Logout
        Route::post('logout/', [LoginController::class, 'logout'])->name('admin.logout');
    });

});

//Web Route


Route::view('email','email.booking_confiramtion')->name('email');

Route::get('/',[HomeController::class,'index'])->name('index');

Route::get('search',[ProductController::class,'productSearch'])->name('product.search');
Route::get('festival-boat-booking/{boat_type_slug}', [ProductController::class, 'festivalBoatBooking'])->name('festival.boat.booking');
Route::post('festival-boat-booking/{boat_type_slug}', [ProductController::class, 'festivalBoatBookingStore'])->name('festival.boat.booking.store');
Route::get('festival-boat-booking-payment/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPayment'])->name('festival.boat.booking.payment');
Route::post('festival-boat-booking-payment-store/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPaymentStore'])->name('festival.boat.booking.payment.store');
Route::get('festival-boat-booking-payment-success/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPaymentSuccess'])->name('festival.boat.booking.payment.success');
Route::get('festival-boat-booking-payment-error/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPaymentError'])->name('festival.boat.booking.payment.error');
Route::get('/{slug}',[ProductController::class,'productList'])->name('product.list');
Route::get('/{category_slug}/{sub_category_slug}',[ProductController::class,'productSubList'])->name('product.sub.list');
Route::get('/{category_slug}/{sub_category_slug}/{product_slug}',[ProductController::class,'productDetail'])->name('product.detail');

//Enquiry
Route::post('enquiry-store',[EnquiryController::class,'store'])->name('enquiry.store');

