<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:category-list|category-delete', ['only' => ['index','store']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = "";
        $list = Category::orderBy('name', 'asc');
        if($request->search){
            $search = $request->search;
            $list = $list->where('name', 'like', '%' . $search. '%');
        }
        $list = $list->paginate(40);
        return view('admin.category.index', compact('list', 'search'), ['page_title' => 'Category']);
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
            'name' => 'required',
        ]);
        Category::updateOrCreate(
            ['name' => $request->name],
            [
                'name'  => $request->name,
                'term_and_condition'  => $request->term_and_condition,
                'slug'  => Str::slug($request->name),
                'meta_title'  => $request->meta_title,
                'meta_keyword'  => $request->meta_keyword,
                'meta_description'  => $request->meta_description,
            ]
        );
        return redirect()->route('category.index')->with('success', 'Category add successfully !!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Category::find($id);
        if($data->is_active == "active"){
            $data->is_active = "inactivate";
            $res = "error";
            $message = "Category Inactivate Successfully";
        }elseif($data->is_active == "inactivate"){
            $data->is_active = "active";
            $res = "success";
            $message = "Category Activate Successfully";
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
        $list = Category::orderBy('name', 'asc')->paginate(40);
        $edit_data = Category::find($id);
        return view('admin.category.index', compact('list', 'edit_data'), ['page_title' => 'Category']);
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
            'name' => 'required',
        ]);
        Category::updateOrCreate(
            ['id' => $id],
            [
                'name'  => $request->name,
                'term_and_condition'  => $request->term_and_condition,
                'slug'  => Str::slug($request->name),
                'meta_title'  => $request->meta_title,
                'meta_keyword'  => $request->meta_keyword,
                'meta_description'  => $request->meta_description,
            ]
        );
        return redirect()->route('category.index')->with('success', 'Category add successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('category.index')->with('error', 'Category deleted successfully !!');
    }

    public function getSubCategory($categoryId)
    {
        return SubCategory::where('category_id', $categoryId)->orderBy('name', 'asc')->get();
    }

    public function updateOnHomeStatus($category_id){
        $data = Category::find($category_id);
        if($data->on_home == "1"){
            $data->on_home = "0";
            $res = "error";
            $message = "Category Remove from Home Successfully";
        }elseif($data->on_home == "0"){
            $data->on_home = "1";
            $res = "success";
            $message = "Category On Home Successfully";
        }
        $data->save();
        return ["message"=>$message, "res"=>$res];
    }

    public function updateShowPriceStatus($category_id){
        $data = Category::find($category_id);
        if($data->show_price == "1"){
            $data->show_price = "0";
            $res = "error";
            $message = "Category Price Not Show Successfully";
        }elseif($data->show_price == "0"){
            $data->show_price = "1";
            $res = "success";
            $message = "Category Price Show Successfully";
        }
        $data->save();
        return ["message"=>$message, "res"=>$res];
    }
}
