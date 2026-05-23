<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:package-list|package-create|package-edit|package-delete', ['only' => ['index','store']]);
        $this->middleware('permission:package-create', ['only' => ['create','store']]);
        $this->middleware('permission:package-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:package-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_key = $request->search_key;
        $list = Product::orderBy('name', 'asc');
        if($search_key){
            $list = $list->where('name', 'like', '%'.$search_key.'%');
        }
        $list = $list->with(['category', 'subCategory'])->paginate(15);
        return view('admin.product.index', compact('list', 'search_key'), ['page_title' => 'Product List']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_list = Category::orderBy('name', 'ASC')->get();
        return view('admin.product.create', compact('category_list'), ['page_title' => 'Product Create']);
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
            'name' => 'required',
            'description' => 'required',
            'images' => 'required',
        ]);
        $data = new Product;
        $data->category_id = $request->category_id;
        $data->subcategory_id = $request->subcategory_id;
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->description = $request->description;
        $data->meta_title = $request->meta_title;
        $data->meta_keyword = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        $data->base_price = $request->base_price;
        $data->discounted_price = $request->discounted_price;
        $data->images = $request->images;
        $data->address = $request->address;
        $data->map_location = $request->map_location;
        $data->youtube_link = $request->youtube_link;
        $data->save();
        return redirect()->route('product.index')->with('success', 'Product Added Successfully');
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
        $edit_data = Product::find($id);
        $category_list = Category::orderBy('name', 'ASC')->get();
        return view('admin.product.create', compact('category_list', 'edit_data'), ['page_title' => 'Product Create']);
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
            'name' => 'required',
            'description' => 'required',
        ]);

        $data = Product::find($id);
        $data->category_id = $request->category_id;
        $data->subcategory_id = $request->subcategory_id;
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->description = $request->description;
        $data->meta_title = $request->meta_title;
        $data->meta_keyword = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        $data->base_price = $request->base_price;
        $data->discounted_price = $request->discounted_price;
        $data->images = $request->images;
        $data->address = $request->address;
        $data->map_location = $request->map_location;
        $data->youtube_link = $request->youtube_link;
        $data->save();
        return redirect()->route('product.index')->with('success', 'Product Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('product.index')->with('error', 'Product deleted successfully !!');
    }

    public function statusUpdate($id)
    {
        $data = Product::find($id);
        if($data->is_active == "active"){
            $data->is_active = "inactivate";
            $res = "error";
            $message = "Product Inactivate Successfully";
        }elseif($data->is_active == "inactivate"){
            $data->is_active = "active";
            $res = "success";
            $message = "Product Activate Successfully";
        }
        $data->save();
        return ["message"=>$message, "res"=>$res];
    }

    public function statusOnHomeUpdate($id)
    {
        $data = Product::find($id);
        if($data->on_home == "1"){
            $data->on_home = "0";
            $res = "error";
            $message = "Product Remove from Home Successfully";
        }elseif($data->on_home == "0"){
            $data->on_home = "1";
            $res = "success";
            $message = "Product On Home Successfully";
        }
        $data->save();
        return ["message"=>$message, "res"=>$res];
    }
}
