<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\VendorService;
use App\Http\Controllers\Controller;

class VendorServiceController extends Controller
{

    public function index(Request $request){
        $search_key = $request->search_key;
        $vendor_services = VendorService::when($search_key,function($query) use ($search_key){
            $query->where('name','like','%'.$search_key.'%');
        })->oldest('name')->paginate(40);

        if($request->ajax()){
            return view('admin.crm.vendor.service.table', compact('vendor_services', 'search_key'));
        }
        return view('admin.crm.vendor.service.index', compact('vendor_services', 'search_key'), ['page_title' => 'Vendor Service']);
    }

    public function store(Request $request){
        $request->validate([
            'name'  =>  'required'
        ]);

        $vendor_service = new VendorService;
        $vendor_service->name = $request->name;
        $vendor_service->save();

        return back()->with('success','Vendor Service Added Successfully!');
    }

    public function show(VendorService $vendorService) {
        if($vendorService->is_active == "1"){
            $vendorService->is_active = "0";
            $res = "error";
            $message = "Vendor Service Inactivate Successfully !";
        }elseif($vendorService->is_active == "0"){
            $vendorService->is_active = "1";
            $res = "success";
            $message = "Vendor Service Activate Successfully !";
        }
        $vendorService->save();
        return ["message"=>$message, "res"=>$res];
    }

    public function edit(Request $request,VendorService $vendorService){
        $edit_vendor_service = $vendorService;
        $search = $request->search;
        $vendor_services = VendorService::when($search,function($query) use ($search){
            $query->where('name','like','%'.$search.'%');
        })->oldest('name')->paginate(40);

        if($request->ajax()){
            return view('admin.crm.vendor.service.table', compact('edit_vendor_service','vendor_services', 'search'));
        }
        return view('admin.crm.vendor.service.index', compact('edit_vendor_service','vendor_services', 'search'), ['page_title' => 'Vendor Service']);
    }

    public function update(Request $request, VendorService $vendorService){
        $request->validate([
            'name'  =>  'required'
        ]);

        $vendorService->name = $request->name;
        $vendorService->save();

        return redirect()->route('vendor-service.index')->with('success','Vendor Service Updated Successfully!');
    }

    public function destroy(VendorService $vendorService) {
        $vendorService->delete();

        return redirect()->route('vendor-service.index')->with('error','Vendor Service Deleted Successfully!');
    }

}
