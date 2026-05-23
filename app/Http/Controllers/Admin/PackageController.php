<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Package;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = "";
        $list = Package::orderBy('name', 'asc');
        if($request->search){
            $search = $request->search;
            $list = $list->where('name', 'like', '%' . $search. '%');
        }
        $list = $list->with(['category', 'subCategory'])->paginate(40);
        return view('admin.package.index', compact('list', 'search'), ['page_title' => 'Package List']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_list = Category::orderBy('name', 'ASC')->get();
        return view('admin.package.create', compact('category_list'), ['page_title' => 'Package Create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'images' => 'required',
        ]);

        $data = new Package;
        $data->category_id = $request->category_id;
        $data->subcategory_id = $request->subcategory_id;
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->description = $request->description;
        $data->meta_title = $request->meta_title;
        $data->meta_keyword = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        $imagesNames = [];
        foreach($request->file('images') as $image){

            $filename = time().rand(10, 99).'.'.$image->extension();
            $image->move(public_path('backend/admin/package_images'), $filename);
            $imagesNames[] = $filename;

        }
        $data->images = $imagesNames;
        $data->save();
        return redirect()->route('package.index')->with('success', 'Package Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit_data = Package::find($id);
        $category_list = Category::orderBy('name', 'ASC')->get();
        return view('admin.package.create', compact('category_list', 'edit_data'), ['page_title' => 'Package Create']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);

        $data = Package::find($id);
        $data->category_id = $request->category_id;
        $data->subcategory_id = $request->subcategory_id;
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->description = $request->description;
        $data->meta_title = $request->meta_title;
        $data->meta_keyword = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        $imagesNames = [];
        if($request->hasFile('image')){
            foreach($request->file('images') as $image){

                $filename = time().rand(10, 99).'.'.$image->extension();
                $image->move(public_path('backend/admin/package_images'), $filename);
                $imagesNames[] = $filename;

            }
            $data->images = $imagesNames;
        }
        $data->save();
        return redirect()->route('package.index')->with('success', 'Package Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Package::destroy($id);
        return redirect()->route('package.index')->with('error', 'Package deleted successfully !!');
    }

    public function statusUpdate($id)
    {
        $data = Package::find($id);
        if($data->is_active == "active"){
            $data->is_active = "inactivate";
            $res = "error";
            $message = "Package Inactivate Successfully";
        }elseif($data->is_active == "inactivate"){
            $data->is_active = "active";
            $res = "success";
            $message = "Package Activate Successfully";
        }
        $data->save();
        return ["message"=>$message, "res"=>$res];
    }
}
