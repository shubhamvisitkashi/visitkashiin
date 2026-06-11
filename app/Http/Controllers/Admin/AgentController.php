<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Agent;
use App\Models\State;
use App\Models\Admin\Admin;
use App\Models\AgentService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{

    public function index(Request $request){
        $search_staff = $request->search_staff;
        $search_service = $request->search_service;
        $search_state = $request->search_state;
        $search_key = $request->search_key;

        $staffs = Admin::all();

        $states = State::oldest('state')->get();

        $agent_services = AgentService::oldest('name')->get();

        $agents = Agent::when(Auth::guard('admin')->user()->id != '1', function ($query) {
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

        $total_agent = $agents->get()->count();

        $agents = $agents->paginate(40);

        if($request->ajax()){
            return view('admin.agent.table',compact('search_staff','search_service','search_key','agent_services','agents','search_state','total_agent','staffs'));
        }

        return view('admin.agent.index',compact('search_staff','search_service','search_key','agent_services','agents','states','search_state','total_agent','staffs'),['page_title'=>'Agent List']);
    }

    public function create(){
        $agent_services = AgentService::oldest('name')->get();
        $states = State::oldest('state')->get();

        return view('admin.agent.create',compact('agent_services','states'),['page_title'=>'Add Agent']);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  =>  'required',
            'contact_number'        =>  'required|digits:10|numeric',
            'alt_contact_number'    =>  'nullable|digits:10|numeric',
        ]);

        $agent = new Agent;
        $agent->admin_id = Auth::guard('admin')->user()->id;
        $agent->name = $request->name;
        $agent->contact_number = $request->contact_number;
        $agent->alt_contact_number = $request->alt_contact_number;
        $agent->state = $request->state;
        $agent->city = $request->city;
        $agent->address = $request->address;
        $agent->service_ids = $request->service_ids;
        $agent->save();

        return redirect()->route('agent.index')->with('success','Agent Added Successfully!');
    }

    public function show(Request $request,Agent $agent){
        $agent->is_active = $request->status;
        $agent->save();

        return back()->with('success','Agent Status Successfully!');
    }

    public function edit(Agent $agent){
        $agent_services = AgentService::oldest('name')->get();
        $states = State::oldest('state')->get();

        return view('admin.agent.edit',compact('agent_services','agent','states'),['page_title'=>'Edit Agent']);
    }

    public function update(Request $request, Agent $agent){
        $request->validate([
            'name'                  =>  'required',
            'contact_number'        =>  'required|digits:10|numeric',
            'alt_contact_number'    =>  'nullable|digits:10|numeric',
        ]);

        $agent->name = $request->name;
        $agent->contact_number = $request->contact_number;
        $agent->alt_contact_number = $request->alt_contact_number;
        $agent->state = $request->state;
        $agent->city = $request->city;
        $agent->address = $request->address;
        $agent->service_ids = $request->service_ids;
        $agent->save();

        return redirect()->route('agent.index')->with('success','Agent Updated Successfully!');
    }

    public function destroy(Agent $agent){
        $agent->delete();

        return back()->with('error','Agent Deleted Successfully!');
    }

    public function getCity(Request $request){
        $state = State::where('state',$request->state)->first();
        return $cities = City::where('state_id',$state->id)->get();
    }
}
