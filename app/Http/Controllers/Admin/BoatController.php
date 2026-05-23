<?php

namespace App\Http\Controllers\Admin;

use App\Models\Boat;
use App\Models\BoatType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoatController extends Controller
{

    public function index(Request $request){
        $search_boat_type = $request->search_boat_type;
        $search_event_type = $request->search_event_type;
        $boat_types = BoatType::oldest('name')->get();
        $boats = Boat::when($search_boat_type, function($query) use ($search_boat_type) {
            $query->where('boat_type_id', $search_boat_type);
        })->with('boatType')->get();

        return view('admin.boat.index', compact('search_boat_type', 'search_event_type', 'boat_types', 'boats'), ['page_title' => 'Boat']);
    }

    public function create(){
        $boat_types = BoatType::oldest('name')->get();
        return view('admin.boat.create', compact('boat_types'), ['page_title' => 'Add Boat']);
    }

    public function store(Request $request){
        $request->validate([
            'boat_type_id'          =>  [
                'required',
                'exists:boat_types,id',
                \Illuminate\Validation\Rule::unique('boats')->where(function ($query) use ($request) {
                    return $query->where('boat_type_id', $request->boat_type_id)
                                ->where('event_type', $request->event_type);
                })
            ],
            'event_type'            =>  'required|in:Regular,Festival',
            'total_available_boat'  =>  'required|integer|min:1',
            'no_of_seat'            =>  'required|integer|min:1',
            'price'                 =>  'required|numeric|min:0',
            'discounted_price'      =>  'required|numeric|min:0|lte:price',
        ], [
            'boat_type_id.unique' => 'A boat with this boat type and event type combination already exists.'
        ]);

        $boat                      = new Boat;
        $boat->boat_type_id        = $request->boat_type_id;
        $boat->event_type          = $request->event_type;
        $boat->total_available_boat = $request->total_available_boat;
        $boat->no_of_seat          = $request->no_of_seat;
        $boat->price               = $request->price;
        $boat->discounted_price    = $request->discounted_price;
        $boat->save();

        return redirect()->route('boat.index')->with('success','Boat Added Successfully!');
    }

    public function show($id) {
        $boat   = Boat::where('id', $id)->firstOrFail();
        if($boat->is_active == "1"){
            $boat->is_active = "0";
            $res = "error";
            $message = "Boat Inactivate Successfully !";
        }elseif($boat->is_active == "0"){
            $boat->is_active = "1";
            $res = "success";
            $message = "Boat Activate Successfully !";
        }
        $boat->save();
        return ["message"=>$message, "res"=>$res];
    }

    public function edit($id){
        $boat = Boat::findOrFail($id);
        $boat_types = BoatType::oldest('name')->get();
        return view('admin.boat.edit', compact('boat', 'boat_types'), ['page_title' => 'Edit Boat']);
    }

    public function update(Request $request, $id){
        $boat = Boat::findOrFail($id);

        $request->validate([
            'boat_type_id'          =>  [
                'required',
                'exists:boat_types,id',
                \Illuminate\Validation\Rule::unique('boats')->where(function ($query) use ($request) {
                    return $query->where('boat_type_id', $request->boat_type_id)
                                ->where('event_type', $request->event_type);
                })->ignore($boat->id)
            ],
            'event_type'            =>  'required|in:Regular,Festival',
            'total_available_boat'  =>  'required|integer|min:1',
            'no_of_seat'            =>  'required|integer|min:1',
            'price'                 =>  'required|numeric|min:0',
            'discounted_price'      =>  'nullable|numeric|min:0|lte:price',
        ], [
            'boat_type_id.unique' => 'A boat with this boat type and event type combination already exists.'
        ]);

        $boat->boat_type_id        = $request->boat_type_id;
        $boat->event_type          = $request->event_type;
        $boat->total_available_boat = $request->total_available_boat;
        $boat->no_of_seat          = $request->no_of_seat;
        $boat->price               = $request->price;
        $boat->discounted_price    = $request->discounted_price;
        $boat->save();

        return redirect()->route('boat.index')->with('success','Boat Updated Successfully!');
    }

    public function destroy($id) {
        $boat = Boat::findOrFail($id);
        $boat->delete();
        return back()->with('success','Boat Deleted Successfully!');
    }
}
