<?php

namespace App\Http\Controllers\Admin;

use App\Models\BoatType;
use App\Services\ImageCompressor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoatTypeController extends Controller
{

    public function index(Request $request){
        $search_key = $request->search_key;
        $boat_types = BoatType::when($search_key,function($query) use ($search_key){
            $query->where('name','like','%'.$search_key.'%');
        })->oldest('name')->paginate(40);

        return view('admin.boat_type.index', compact('boat_types', 'search_key'), ['page_title' => 'Boat Type']);
    }

    public function store(Request $request){
        $request->validate([
            'name'  =>  'required',
            'image' =>  'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $boat_type                  = new BoatType;
        $boat_type->slug            = Str::slug($request->name).rand(11111, 99999);
        $boat_type->name            = $request->name;
        $boat_type->image           = $request->image ? $request->file('image')->store('boat_type', 'public') : null;
        if ($boat_type->image) {
            ImageCompressor::compress(storage_path('app/public/' . $boat_type->image));
        }
        $boat_type->seo_title       = $request->seo_title ?? $request->name;
        $boat_type->seo_keyword     = $request->seo_keyword ?? $request->name;
        $boat_type->seo_description = $request->seo_description ?? $request->name;
        $boat_type->save();

        return back()->with('success','Boat Type Added Successfully!');
    }

    public function show($slug) {
        $boatType   = BoatType::where('slug', $slug)->firstOrFail();
        if($boatType->is_active == "1"){
            $boatType->is_active = "0";
            $res = "error";
            $message = "Boat Type Inactivate Successfully !";
        }elseif($boatType->is_active == "0"){
            $boatType->is_active = "1";
            $res = "success";
            $message = "Boat Type Activate Successfully !";
        }
        $boatType->save();
        return ["message"=>$message, "res"=>$res];
    }

    public function edit(Request $request, $slug){
        $boatType   = BoatType::where('slug', $slug)->firstOrFail();

        $edit_boat_type = $boatType;

        $search_key = $request->search_key;
        $boat_types = BoatType::when($search_key,function($query) use ($search_key){
            $query->where('name','like','%'.$search_key.'%');
        })->oldest('name')->paginate(40);

        return view('admin.boat_type.index', compact('edit_boat_type', 'boat_types', 'search_key'), ['page_title' => 'Boat Type']);
    }

    public function update(Request $request, $slug){
        $boat_type   = BoatType::where('slug', $slug)->firstOrFail();
        $request->validate([
            'name'  =>  'required|unique:boat_types,name,' . $boat_type->id,
            'image' =>  'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $boat_type->name            = $request->name;
        if($request->hasFile('image')) {
            $boat_type->image           = $request->file('image')->store('boat_type', 'public');
            ImageCompressor::compress(storage_path('app/public/' . $boat_type->image));
        }
        $boat_type->seo_title       = $request->seo_title ?? $request->name;
        $boat_type->seo_keyword     = $request->seo_keyword ?? $request->name;
        $boat_type->seo_description = $request->seo_description ?? $request->name;
        $boat_type->save();

        return redirect()->route('boat-type.index')->with('success','Boat Type Updated Successfully!');
    }

    public function destroy($slug) {
        $boatType   = BoatType::where('slug', $slug)->firstOrFail();
        $boatType->delete();

        return redirect()->route('boat-type.index')->with('error','Boat Type Deleted Successfully!');
    }

}
