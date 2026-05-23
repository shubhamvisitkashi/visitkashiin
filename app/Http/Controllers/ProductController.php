<?php

namespace App\Http\Controllers;

use App\Models\Boat;
use App\Models\BoatType;
use App\Models\BoatBooking;
use App\CPU\CategoryManager;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Models\BoatBookingRequest;

class ProductController extends Controller
{


    public function productList($slug){
        $category = CategoryManager::active()->where('slug',$slug)->first();
        
        // Return 404 if category not found
        if (!$category) {
            abort(404, 'Category not found');
        }
        
        // Eager load relationships to avoid N+1 queries
        $products = Product::where('is_active','active')
            ->where('category_id',$category->id)
            ->with(['category','subCategory'])
            ->get();
            
        return view('frontend.details',compact('products','category'));
    }

    public function productSubList($category_slug,$sub_category_slug){
        $sub_category = SubCategory::where('slug',$sub_category_slug)->first();
        
        // Return 404 if subcategory not found
        if (!$sub_category) {
            abort(404, 'Subcategory not found');
        }
        
        // Eager load relationships
        $products = Product::where('is_active','active')
            ->where('subcategory_id',$sub_category->id)
            ->with(['category','subCategory'])
            ->get();
            
        // Special handling for festival boat booking
        if($category_slug === 'festivals' && $sub_category_slug === 'dev-diwali-boat-booking') {
            $products = Boat::where('event_type', 'Festival')
                ->where('is_active', '1')
                ->with('boatType')
                ->get();
            return view('frontend.boat_booking',compact('products','sub_category'));
        }
        
        return view('frontend.details',compact('products','sub_category'));
    }

    public function productDetail($category_slug,$sub_category_slug,$product_slug){
        $category = Category::where('slug',$category_slug)->first();
        
        // Return 404 if category not found
        if (!$category) {
            abort(404, 'Category not found');
        }
        
        $sub_category = SubCategory::where('slug',$sub_category_slug)->first();
        
        // Find product with null-safe category_id
        $product = Product::where('category_id',$category->id)
            ->where('subcategory_id', optional($sub_category)->id)
            ->where('slug',$product_slug)
            ->first();
            
        // Return 404 if product not found
        if (!$product) {
            abort(404, 'Product not found');
        }
        
        return view('frontend.tour_detail',compact('product'));
    }

    public function productSearch(Request $request){
        $search_list = Product::where('is_active','active')->where('name','like','%'.$request->search_key.'%')->with(['category','subCategory'])->get();
        return view('frontend.search',compact('search_list'));
    }

    public function festivalBoatBooking($boart_type_slug){
        $boat_type = BoatType::where('slug',$boart_type_slug)->first();
        $product = Boat::where('boat_type_id',$boat_type->id)->where('event_type','Festival')->where('is_active','1')->with('boatType')->first();
        return view('frontend.boat_booking_form',compact('product','boat_type'));
    }

    public function festivalBoatBookingStore(Request $request, $boart_type_slug){
        $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'required|email|max:255',
            'customer_phone'    => 'required|string|max:20',
            'no_of_person'      => 'required|integer|min:1',
        ]);

        $boat_type = BoatType::where('slug',$boart_type_slug)->first();
        $boat = Boat::where('boat_type_id',$boat_type->id)->where('event_type','Festival')->where('is_active','1')->with('boatType')->first();
        if(!$boat) {
            return redirect()->back()->with('error', 'Invalid boat type selected.')->withInput($request->all());
        }

        $total_boat_booking = BoatBooking::where('boat_id', $boat->id)->whereDate('booking_date', '2025-11-05')->sum('no_of_person');

        $total_seat = $boat->total_available_boat * $boat->no_of_seat;
        $total_available_seat = $total_seat - $total_boat_booking;

        if($request->no_of_person > $total_available_seat) {
            return redirect()->back()->with('error', 'No seat available on this boat for the selected date.')->withInput($request->all());
        }

        $boat_booking_request = new BoatBookingRequest;
        $boat_booking_request->booking_request_id = generateBookingRequestId();
        $boat_booking_request->boat_id = $boat->id;
        $boat_booking_request->name = $request->customer_name;
        $boat_booking_request->email = $request->customer_email;
        $boat_booking_request->phone = $request->customer_phone;
        $boat_booking_request->no_of_person = $request->no_of_person;
        $boat_booking_request->total_amount = $boat->price * $request->no_of_person;
        $boat_booking_request->total_discount = ($boat->price - $boat->discounted_price) * $request->no_of_person;
        $boat_booking_request->final_amount = $boat->discounted_price * $request->no_of_person;
        $boat_booking_request->booking_date = '2025-11-05 16:00:00';
        $boat_booking_request->booking_status = 'pending';
        $boat_booking_request->payment_status = 'unpaid';
        $boat_booking_request->save();

        return redirect()->route('festival.boat.booking.payment', $boat_booking_request->booking_request_id);
    }

    public function festivalBoatBookingPayment($booking_request_id){
        $boat_booking_request = BoatBookingRequest::where('booking_request_id', $booking_request_id)->with('boat')->first();
        if(!$boat_booking_request) {
            return redirect()->route('product.sub.list', ['festivals', 'dev-diwali-boat-booking'])->with('error', 'Invalid booking request.');
        }

        $created_at = $boat_booking_request->created_at;
        $current_time = now();
        $time_difference = $created_at->diffInMinutes($current_time);

        if($time_difference > 5) {
            return redirect()->route("festival.boat.booking.payment.error", $boat_booking_request->booking_request_id)->with('error', 'Booking session has expired. Please try booking again.');
        }

        return view('frontend.boat_booking_payment', compact('boat_booking_request'));
    }

    public function festivalBoatBookingPaymentStore(Request $request, $booking_request_id){
        $boat_booking_request = BoatBookingRequest::where('booking_request_id', $booking_request_id)->with('boat')->first();
        if(!$boat_booking_request) {
            return redirect()->route('product.sub.list', ['festivals', 'dev-diwali-boat-booking'])->with('error', 'Invalid booking request.');
        }

        $created_at = $boat_booking_request->created_at;
        $current_time = now();
        $time_difference = $created_at->diffInMinutes($current_time);

        if($time_difference > 5) {
            return redirect()->route("festival.boat.booking.payment.error", $boat_booking_request->booking_request_id)->with('error', 'Booking session has expired. Please try booking again.');
        }

        $request->validate([
            'utr_number'            => 'required|string|max:255',
            'payment_screenshot'    => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $boat_booking_request->payment_detail = ['utr_number' => $request->utr_number, 'payment_screenshot' => $request->file('payment_screenshot')->store('payment_screenshots', 'public')];
        $boat_booking_request->save();

        return redirect()->route('festival.boat.booking.payment.success', $boat_booking_request->booking_request_id);
    }

    public function festivalBoatBookingPaymentSuccess($booking_request_id){
        $boat_booking_request = BoatBookingRequest::where('booking_request_id', $booking_request_id)->with('boat')->first();
        if(!$boat_booking_request) {
            return redirect()->route('product.sub.list', ['festivals', 'dev-diwali-boat-booking'])->with('error', 'Invalid booking request.');
        }

        return view('frontend.boat_booking_payment_success', compact('boat_booking_request'));
    }

    public function festivalBoatBookingPaymentError($booking_request_id){
        $boat_booking_request = BoatBookingRequest::where('booking_request_id', $booking_request_id)->with('boat')->first();
        if(!$boat_booking_request) {
            return redirect()->route('product.sub.list', ['festivals', 'dev-diwali-boat-booking'])->with('error', 'Invalid booking request.');
        }

        return view('frontend.boat_booking_payment_error', compact('boat_booking_request'));
    }

}
