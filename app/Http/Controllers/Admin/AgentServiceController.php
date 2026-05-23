<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentService;
use Illuminate\Http\Request;

class AgentServiceController extends Controller
{

    public function index(Request $request){
        $search = $request->search;
        $agent_services = AgentService::when($search,function($query) use ($search){
            $query->where('name','like','%'.$search.'%');
        })->oldest('name')->paginate(40);

        if($request->ajax()){
            return view('admin.agent.service.table', compact('agent_services', 'search'));
        }
        return view('admin.agent.service.index', compact('agent_services', 'search'), ['page_title' => 'Agent Service']);
    }

    public function create()
    {
        //
    }

    public function store(Request $request){
        $request->validate([
            'name'  =>  'required'
        ]);

        $agent_service = new AgentService;
        $agent_service->name = $request->name;
        $agent_service->save();

        return back()->with('success','Agent Service Added Successfully!');
    }

    public function show(AgentService $agentService) {
        if($agentService->is_active == "1"){
            $agentService->is_active = "0";
            $res = "error";
            $message = "Agent Service Inactivate Successfully !";
        }elseif($agentService->is_active == "0"){
            $agentService->is_active = "1";
            $res = "success";
            $message = "Agent Service Activate Successfully !";
        }
        $agentService->save();
        return ["message"=>$message, "res"=>$res];
    }

    public function edit(Request $request,AgentService $agentService){
        $edit_agent_service = $agentService;
        $search = $request->search;
        $agent_services = AgentService::when($search,function($query) use ($search){
            $query->where('name','like','%'.$search.'%');
        })->oldest('name')->paginate(40);

        if($request->ajax()){
            return view('admin.agent.service.table', compact('edit_agent_service','agent_services', 'search'));
        }
        return view('admin.agent.service.index', compact('edit_agent_service','agent_services', 'search'), ['page_title' => 'Agent Service']);
    }

    public function update(Request $request, AgentService $agentService){
        $request->validate([
            'name'  =>  'required'
        ]);

        $agentService->name = $request->name;
        $agentService->save();

        return back()->with('success','Agent Service Updated Successfully!');
    }

    public function destroy(AgentService $agentService) {
        $agentService->delete();

        return back()->with('error','Agent Service Deleted Successfully!');
    }
}
