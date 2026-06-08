<?php

namespace App\Http\Controllers\Admin;

use App\Models\LeadSource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeadSourceController extends Controller
{

    public function index(Request $request){
        $search_key = $request->search_key;
        $lead_sources = LeadSource::when($search_key,function($query) use ($search_key){
            $query->where('name','like','%'.$search_key.'%');
        })->oldest('name')->paginate(40);

        if($request->ajax()){
            return view('admin.lead_source.table', compact('lead_sources', 'search_key'));
        }
        return view('admin.lead_source.index', compact('lead_sources', 'search_key'), ['page_title' => 'Lead Source']);
    }

    public function store(Request $request){
        $request->validate([
            'name'  =>  'required'
        ]);

        $lead_source = new LeadSource;
        $lead_source->name  = $request->name;
        $lead_source->phone = $request->phone ?? null;
        $lead_source->save();

        return back()->with('success','Lead Source Added Successfully!');
    }

    public function show(LeadSource $leadSource) {
        if($leadSource->is_active == "1"){
            $leadSource->is_active = "0";
            $res = "error";
            $message = "Lead Source Inactivate Successfully !";
        }elseif($leadSource->is_active == "0"){
            $leadSource->is_active = "1";
            $res = "success";
            $message = "Lead Source Activate Successfully !";
        }
        $leadSource->save();
        return ["message"=>$message, "res"=>$res];
    }

    public function edit(Request $request,LeadSource $leadSource){
        $edit_lead_source = $leadSource;
        $search_key = $request->search_key;
        $lead_sources = LeadSource::when($search_key,function($query) use ($search_key){
            $query->where('name','like','%'.$search_key.'%');
        })->oldest('name')->paginate(40);

        if($request->ajax()){
            return view('admin.lead_source.table', compact('edit_lead_source', 'lead_sources', 'search_key'));
        }
        return view('admin.lead_source.index', compact('edit_lead_source', 'lead_sources', 'search_key'), ['page_title' => 'Lead Source']);
    }

    public function update(Request $request, LeadSource $leadSource){
        $request->validate([
            'name'  =>  'required'
        ]);

        $leadSource->name  = $request->name;
        $leadSource->phone = $request->phone ?? null;
        $leadSource->save();

        return redirect()->route('lead-source.index')->with('success','Lead Source Updated Successfully!');
    }

    public function destroy(LeadSource $leadSource) {
        $leadSource->delete();

        return redirect()->route('lead-source.index')->with('error','Lead Source Deleted Successfully!');
    }

}
