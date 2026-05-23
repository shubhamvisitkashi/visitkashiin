<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnquiryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:enquiry-list', ['only' => ['index']]);

    }

    public function index(Request $request){
        $search_date_range = $request->daterange;
        $search_key = $request->search_key;

        $enquires = Enquiry::orderBy('id','desc');

        if($search_date_range)
        {
            $dates=explode('-',$search_date_range);
            $d1=strtotime($dates[0]);
            $d2=strtotime($dates[1]);
            $da1=date('Y-m-d',$d1);
            $da2=date('Y-m-d',$d2);
            $startDate = Carbon::createFromFormat('Y-m-d', $da1)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $da2)->endOfDay();

            $date=$dates[0].' - '.$dates[1];
            $enquires = $enquires->whereBetween('created_at', [$startDate, $endDate]);
        }

        if($search_key){
            $enquires = $enquires->where('name',$search_key)->orWhere('phone',$search_key);
        }

        $enquires = $enquires->paginate(10);

        return view('admin.enquiry.index',compact('enquires','search_date_range','search_key'),['page_title'=>'Enquiries']);
    }

    public function destroy($id){
        $enquiry = Enquiry::find($id);
        if($enquiry){
            $enquiry->delete();

            return back()->with('success','Enquiry Deleted Successfully!');
        }else{
            return back()->with('error','No Enquiry Found!');
        }
    }

}
