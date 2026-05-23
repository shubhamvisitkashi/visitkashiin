<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sub_category-list|sub_category-delete', ['only' => ['index','store']]);
        $this->middleware('permission:sub_category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = "";
        $list = SubCategory::orderBy('name', 'asc');
        if($request->search){
            $search = $request->search;
            $list = $list->where('name', 'like', '%' . $search. '%');
        }
        $list = $list->with('category')->paginate(40);
        $category_list = Category::orderBy('name', 'asc')->get();
        return view('admin.sub_category.index', compact('list', 'search', 'category_list'), ['page_title' => 'Sub Category']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'category_id'   => 'required',
            'name'          => 'required',
        ]);
        SubCategory::updateOrCreate(
            ['name' => $request->name],
            [
                'category_id' => $request->category_id,
                'name'  => $request->name,
                'slug'  => Str::slug($request->name),
                'meta_title' => $request->meta_title,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description
            ]
        );
        return redirect()->route('sub-category.index')->with('success', 'Sub Category add successfully !!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = SubCategory::find($id);
        if($data->is_active == "active"){
            $data->is_active = "inactivate";
            $res = "error";
            $message = "SubCategory Inactivate Successfully";
        }elseif($data->is_active == "inactivate"){
            $data->is_active = "active";
            $res = "success";
            $message = "SubCategory Activate Successfully";
        }
        $data->save();
        return ["message"=>$message, "res"=>$res];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $list = SubCategory::orderBy('name', 'asc')->with('category')->paginate(40);
        $edit_data = SubCategory::find($id);
        $category_list = Category::orderBy('name', 'asc')->get();
        return view('admin.sub_category.index', compact('list', 'edit_data', 'category_list'), ['page_title' => 'Sub Category']);
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
            'category_id'   => 'required',
            'name'          => 'required',
        ]);
        SubCategory::updateOrCreate(
            ['id' => $id],
            [
                'category_id' => $request->category_id,
                'name'  => $request->name,
                'slug'  => Str::slug($request->name),
                'meta_title' => $request->meta_title,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description
            ]
        );
        return redirect()->route('sub-category.index')->with('success', 'Sub Category update successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubCategory::destroy($id);
        return redirect()->route('sub-category.index')->with('error', 'Sub Category delete successfully !!');
    }
}
