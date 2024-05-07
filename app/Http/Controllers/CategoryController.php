<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // show categories

    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('id')->get();



        return view('admin.categories.index', compact('categories'));
    }

    public function get($id)
    {
        return Category::find($id);
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('addBanner')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
             $category = new Category();
            if ($request->category != "") {
                $category->parent_id= $request->category;
                $parent_cat = $this->get($request->category);
                $category->position = $parent_cat->position + 1 ;
            }
            $category->name              = $request->inamecon;
            $category->save();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Category stored successfully']);
        } catch (Exception $e) {
            return redirect('addBanner')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }


}
