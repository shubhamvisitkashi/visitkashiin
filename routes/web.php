<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\HotelEnquiryController;
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
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit')->middleware('throttle:10,1');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('dashboard/pending-bookings', [DashboardController::class, 'pendingBookings'])->name('admin.dashboard.pending-bookings');
        Route::get('dashboard/staff-bookings/{userId}', [DashboardController::class, 'staffBookings'])->name('admin.dashboard.staff-bookings');
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

            //Vendor (removed - page disabled)
            // Route::resource('vendor', 'VendorController');
            // Route::get('vendor-status-update/{id}', 'VendorController@statusUpdate')->name('vendor.statusUpdate');

            //Service Templates
            Route::resource('service-templates', 'ServiceTemplateController');

            //Quotations (disabled — section removed)
            Route::get('quotations/{any?}', function() { return redirect()->route('bookings.index'); })->where('any', '.*');
            Route::any('quotations/{any}', function() { return redirect()->route('bookings.index'); })->where('any', '.*');


            //Bookings
            // Direct Booking Routes (Must be BEFORE bookings/{id} to avoid conflicts)
            // Hub — booking type selector
            Route::get('bookings/create-direct', 'DirectBookingController@create')->name('bookings.create-direct');
            // Dedicated Stay booking
            Route::get('bookings/create/stay', 'DirectBookingController@createStay')->name('bookings.create-stay');
            Route::post('bookings/store-direct', 'DirectBookingController@store')->name('bookings.store-direct');
            Route::post('bookings/save-draft', 'DirectBookingController@saveDraft')->name('bookings.save-draft');

            // Fresh Tour Package Booking
            Route::get('bookings/tour/create', 'TourBookingController@create')->name('tour-booking.create');
            Route::post('bookings/tour/store', 'TourBookingController@store')->name('tour-booking.store');
            Route::get('bookings/tour/{id}/view', 'TourBookingController@view')->name('tour-booking.view');
            Route::post('bookings/tour/{id}/payment', 'TourBookingController@addPayment')->name('tour-booking.add-payment');
            Route::get('bookings/tour/{id}', 'TourBookingController@show')->name('tour-booking.show');
            Route::put('bookings/tour/{id}', 'TourBookingController@update')->name('tour-booking.update');
            Route::get('bookings/tour/{id}/confirmation', 'TourBookingController@confirmation')->name('tour-booking.confirmation');
            Route::get('bookings/tour/{id}/voucher', 'TourBookingController@voucher')->name('tour-booking.voucher');
            Route::get('bookings/tour/{id}/pdf', 'TourBookingController@downloadPdf')->name('tour-booking.pdf');
            
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
            Route::get('booking-voucher/{id}', 'BookingController@voucher')->name('booking.voucher');
            Route::get('booking-gst-invoice/{id}', 'BookingController@gstInvoice')->name('booking.gst-invoice');
            // Reports
            Route::get('reports', 'ReportsController@index')->name('reports.index');
            Route::get('reports/revenue', 'ReportsController@revenue')->name('reports.revenue');
            Route::get('reports/bookings', 'ReportsController@bookings')->name('reports.bookings');
            Route::get('reports/export', 'ReportsController@export')->name('reports.export');
            // Customer CRM
            Route::get('customers', 'CustomerCrmController@index')->name('customers.index');
            Route::get('customers/{id}', 'CustomerCrmController@show')->name('customers.show');
            Route::get('booking-report/{id}/view', 'BookingController@viewReport')->name('booking.report.view');
            Route::get('booking-report/{id}', 'BookingController@downloadReport')->name('booking.report');
            Route::get('booking-report/{id}/export', 'BookingController@exportReport')->name('booking.report.export');            Route::post('bookings/{id}/update-gst', 'BookingController@updateGstDetails')->name('bookings.update-gst');
            Route::get('bookings-calendar', 'BookingController@calendar')->name('bookings.calendar');
            Route::get('bookings-calendar/events', 'BookingController@calendarEvents')->name('bookings.calendar.events');

            // Cab Bookings
            Route::get('cab-bookings',                                              'CabBookingController@index')->name('cab-bookings.index');
            Route::get('cab-bookings/create',                                       'CabBookingController@create')->name('cab-bookings.create');
            Route::post('cab-bookings',                                             'CabBookingController@store')->name('cab-bookings.store');
            Route::get('cab-bookings/{id}',                                         'CabBookingController@show')->name('cab-bookings.show');
            Route::get('cab-bookings/{id}/edit',                                    'CabBookingController@edit')->name('cab-bookings.edit');
            Route::put('cab-bookings/{id}',                                         'CabBookingController@update')->name('cab-bookings.update');
            Route::delete('cab-bookings/{id}',                                      'CabBookingController@destroy')->name('cab-bookings.destroy');
            Route::post('cab-bookings/{id}/add-payment',                            'CabBookingController@addPayment')->name('cab-bookings.add-payment');
            Route::delete('cab-bookings/{id}/payments/{paymentId}',                 'CabBookingController@deletePayment')->name('cab-bookings.delete-payment');
            Route::post('cab-bookings/{id}/update-status',                          'CabBookingController@updateStatus')->name('cab-bookings.update-status');
            Route::get('cab-bookings/{id}/invoice',                                 'CabBookingController@invoice')->name('cab-bookings.invoice');

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
            Route::delete('hotel-enquiry-delete/{id}','EnquiryController@destroyHotel')->name('hotel-enquiry.admin.delete');
            Route::post('enquiry-resend/{id}','EnquiryController@resend')->name('enquiry.resend');

            // Website Setup Route
            Route::resource('web_setup', 'WebsiteSetupController');

            // Hero Slider
            Route::resource('hero-slides', 'HeroSlideController')->except(['create','show','edit']);
            Route::post('hero-slides/{heroSlide}/toggle', 'HeroSlideController@toggleStatus')->name('hero-slides.toggle');

            // Promo Banners
            Route::resource('promo-banners', 'PromoBannerController')->except(['create','show','edit']);
            Route::post('promo-banners/{promoBanner}/toggle', 'PromoBannerController@toggleStatus')->name('promo-banners.toggle');

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

            //Boatmen
            Route::resource('boatmen', 'BoatmanController')->except(['create','show','edit']);
            Route::post('boatmen/{boatman}/toggle-status', 'BoatmanController@toggleStatus')->name('boatmen.toggle-status');

            //Boat Type
            Route::resource('boat-type', BoatTypeController::class)->except('create');

            //Boat
            Route::resource('boat', BoatController::class);

            //Boat Booking
            Route::resource('boat-booking', BoatBookingController::class);
            Route::get('boat-booking/{booking_id}/voucher', 'BoatBookingController@voucher')->name('boat-booking.voucher');
            Route::get('boat-booking/{booking_id}/voucher/pdf', 'BoatBookingController@voucherPdf')->name('boat-booking.voucher.pdf');
            Route::post('boat-booking/check-availability', 'BoatBookingController@checkAvailability')->name('boat-booking.check.availability');
            Route::get('send-booking-mail/{booking_id}', 'BoatBookingController@sendBookingMail')->name('send.booking.mail');
            Route::get('boat-booking-payment/{booking_id}', 'BoatBookingController@payment')->name('boat-booking.payment');
            Route::post('boat-booking-payment/{booking_id}', 'BoatBookingController@paymentStore')->name('boat-booking.payment.store');
            Route::get('boat-booking-requests', 'BoatBookingController@bookingRequest')->name('boat-booking.requests');
            Route::get('boat-booking-requests', 'BoatBookingController@bookingRequest')->name('boat-booking.requests');
            Route::post('/boat-booking-request/{booking_request_id}/cancel', 'BoatBookingController@cancelBookingRequest')->name('boat-booking-request.cancel');
            Route::post('boat-booking/{booking_id}/cancel', 'BoatBookingController@cancelBooking')->name('boat-booking.cancel');

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
            Route::get('ledger', 'LedgerController@index')->name('ledger.index');
            Route::get('executive-dashboard', 'ExecutiveDashboardController@index')->name('executive-dashboard.index');

            // Payment Accounts
            Route::get('payment-accounts/by-type/{type}', 'PaymentAccountController@getByType')->name('payment-accounts.by-type');
            Route::resource('payment-accounts', 'PaymentAccountController');
            Route::post('payment-accounts/{id}/toggle-status', 'PaymentAccountController@toggleStatus')->name('payment-accounts.toggle-status');
            Route::post('payment-accounts/{id}/reset-balance', 'PaymentAccountController@resetBalance')->name('payment-accounts.reset-balance');

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

            // YouTube Videos
            Route::get('youtube-videos', 'YoutubeVideoController@index')->name('youtube-videos.index');
            Route::post('youtube-videos', 'YoutubeVideoController@store')->name('youtube-videos.store');
            Route::put('youtube-videos/{youtubeVideo}', 'YoutubeVideoController@update')->name('youtube-videos.update');
            Route::delete('youtube-videos/{youtubeVideo}', 'YoutubeVideoController@destroy')->name('youtube-videos.destroy');
            Route::post('youtube-videos/{youtubeVideo}/toggle', 'YoutubeVideoController@toggleStatus')->name('youtube-videos.toggle');

            // Instagram Reels
            Route::get('instagram-reels', 'InstagramReelController@index')->name('instagram-reels.index');
            Route::post('instagram-reels', 'InstagramReelController@store')->name('instagram-reels.store');
            Route::put('instagram-reels/{instagramReel}', 'InstagramReelController@update')->name('instagram-reels.update');
            Route::delete('instagram-reels/{instagramReel}', 'InstagramReelController@destroy')->name('instagram-reels.destroy');
            Route::post('instagram-reels/{instagramReel}/toggle', 'InstagramReelController@toggleStatus')->name('instagram-reels.toggle');

        });

        //Change Password
        Route::get('change-password', ChangePasswordController::class)->name('change.password');
        Route::post('change-password', [ChangePasswordController::class, 'changePassword'])->name('change.password.store');
        Route::post('staff-reset-password/{id}', [ChangePasswordController::class, 'resetStaffPassword'])->name('staff.reset.password');
        Route::post('calendar-pin-update', [ChangePasswordController::class, 'updateCalendarPin'])->name('calendar.pin.update');

        // Logout
        Route::post('logout/', [LoginController::class, 'logout'])->name('admin.logout');
    });

});

//Web Route

// ── Public PIN-protected Calendar ──────────────────────────────
Route::post('calendar', [\App\Http\Controllers\PublicCalendarController::class, 'verify'])->name('public.calendar.verify');
Route::get('calendar',  [\App\Http\Controllers\PublicCalendarController::class, 'show'])->name('public.calendar');
Route::get('calendar/events', [\App\Http\Controllers\PublicCalendarController::class, 'events'])->name('public.calendar.events')->middleware('calendar.pin');

Route::view('email','email.booking_confiramtion')->name('email');

Route::get('/',[HomeController::class,'index'])->name('index');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('search',[ProductController::class,'productSearch'])->name('product.search');
Route::get('festival-boat-booking/{boat_type_slug}', [ProductController::class, 'festivalBoatBooking'])->name('festival.boat.booking');
Route::post('festival-boat-booking/{boat_type_slug}', [ProductController::class, 'festivalBoatBookingStore'])->name('festival.boat.booking.store');
Route::get('festival-boat-booking-payment/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPayment'])->name('festival.boat.booking.payment');
Route::post('festival-boat-booking-payment-store/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPaymentStore'])->name('festival.boat.booking.payment.store');
Route::get('festival-boat-booking-payment-success/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPaymentSuccess'])->name('festival.boat.booking.payment.success');
Route::get('festival-boat-booking-payment-error/{booking_request_id}', [ProductController::class, 'festivalBoatBookingPaymentError'])->name('festival.boat.booking.payment.error');

// Static pages — must be before catch-all /{slug}
Route::get('contact-us',         [ContactController::class, 'show'])->name('contact.show');
Route::post('contact-us',        [ContactController::class, 'store'])->name('contact.store');
Route::get('privacy-policy',     [PageController::class, 'privacyPolicy'])->name('page.privacy');
Route::get('cancellation-refund',[PageController::class, 'cancellationRefund'])->name('page.cancellation');
Route::get('about-us',           [PageController::class, 'aboutUs'])->name('page.about');

Route::get('/{slug}',[ProductController::class,'productList'])->name('product.list');
Route::get('/{category_slug}/{sub_category_slug}',[ProductController::class,'productSubList'])->name('product.sub.list');
Route::get('/{category_slug}/{sub_category_slug}/{product_slug}',[ProductController::class,'productDetail'])->name('product.detail');

//Enquiry
Route::post('enquiry-store',[EnquiryController::class,'store'])->name('enquiry.store');

// Hotel & Homestay Enquiry
Route::post('hotel-enquiry-store',[HotelEnquiryController::class,'store'])->name('hotel-enquiry.store');

