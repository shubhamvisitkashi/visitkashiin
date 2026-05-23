<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Vendor;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Models\VendorService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index(Request $request){
        $search_staff = $request->search_staff;
        $search_service = $request->search_service;
        $search_state = $request->search_state;
        $search_key = $request->search_key;

        $staffs = Admin::all();

        $states = State::oldest('state')->get();

        $vendor_services = VendorService::oldest('name')->get();

        $vendors = Vendor::when(Auth::guard('admin')->user()->id != '1', function ($query) {
            $query->where('admin_id',Auth::guard('admin')->user()->id);
        })->when(Auth::guard('admin')->user()->id == '1' && $search_staff,function($query) use ($search_staff){
            $query->where('admin_id',$search_staff);
        })->when($search_service,function($query) use ($search_service){
            $query->whereJsonContains('service_ids',''.$search_service);
        })->when($search_state,function($query) use ($search_state){
            $query->where('state',$search_state);
        })->when($search_key,function($query) use ($search_key){
            $query->where(function($qu) use ($search_key){
                $qu->where('name','LIKE','%'.$search_key.'%')->orWhere('contact_number',$search_key)->orWhere('alt_contact_number',$search_key)->orWhere('city',$search_key);
            });
        })->with('admin')->latest();

        $total_vendor = $vendors->get()->count();

        $vendors = $vendors->paginate(40);

        if($request->ajax()){
            return view('admin.crm.vendor.table',comapact('search_staff','search_service','search_key','vendor_services','vendors','search_state','total_vendor','staffs'));
        }

        return view('admin.crm.vendor.index',compact('search_staff','search_service','search_key','vendor_services','vendors','states','search_state','total_vendor','staffs'),['page_title'=>'Vendor List']);
    }

    public function create(){
        $vendor_services = VendorService::oldest('name')->get();
        $states = State::oldest('state')->get();

        return view('admin.crm.vendor.create',compact('vendor_services','states'),['page_title'=>'Add Vendor']);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  =>  'required',
            'contact_number'        =>  'required|digits:10|numeric',
            'alt_contact_number'    =>  'nullable|digits:10|numeric',
        ]);

        $vendor = new Vendor;
        $vendor->admin_id = Auth::guard('admin')->user()->id;
        $vendor->name = $request->name;
        $vendor->contact_number = $request->contact_number;
        $vendor->alt_contact_number = $request->alt_contact_number;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->address = $request->address;
        $vendor->service_ids = $request->service_ids;
        $vendor->save();

        return redirect()->route('vendor.index')->with('success','Vendor Added Successfully!');
    }

    public function show(Request $request,Vendor $vendor){
        $vendor->is_active = $request->status;
        $vendor->save();

        return back()->with('success','Vendor Status Successfully!');
    }

    public function edit(Vendor $vendor){
        $vendor_services = VendorService::oldest('name')->get();
        $states = State::oldest('state')->get();

        return view('admin.crm.vendor.edit',compact('vendor_services','vendor','states'),['page_title'=>'Edit Vendor']);
    }

    public function update(Request $request, Vendor $vendor){
        $request->validate([
            'name'                  =>  'required',
            'contact_number'        =>  'required|digits:10|numeric',
            'alt_contact_number'    =>  'nullable|digits:10|numeric',
        ]);

        $vendor->name = $request->name;
        $vendor->contact_number = $request->contact_number;
        $vendor->alt_contact_number = $request->alt_contact_number;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->address = $request->address;
        $vendor->service_ids = $request->service_ids;
        $vendor->save();

        return redirect()->route('vendor.index')->with('success','Vendor Updated Successfully!');
    }

    public function destroy(Vendor $vendor){
        $vendor->delete();

        return back()->with('error','Vendor Deleted Successfully!');
    }

    public function getCity(Request $request){
        $state = State::where('state',$request->state)->first();
        return $cities = City::where('state_id',$state->id)->get();
    }

}
