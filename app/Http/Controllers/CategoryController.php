<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Request;
use http\Env\Response;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all categories
     */
    public function all()
    {
        $categories = Category::all();
        return response($categories, 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, Category::$rules);

        $category = new Category();
        $category->name = $request->input("name");
        $category->email = $request->input("image");
        $category->description = $request->input("description");
        $category->user = $request->user()->id;
        $category->save();

        return response()->json($category, 201);
    }
}
