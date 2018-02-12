<?php

namespace App\Http\Controllers;

use App\Exceptions\ConflictHttpException;
use App\Exceptions\NotFoundException;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['only'=>['create','delete']]);
    }

    /**
     * Get all categories
     */
    public function all()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }


    /**
 * Get all locations
 */
    public function fetchLocations() {
        $categories = Category::where('type', "LOCATION")->get();
        return response()->json($categories, 200);
    }

    /**
     * Get all activities
     */
    public function fetchActicities() {
        $categories = Category::where('type', "ACTIVITY" )->get();
        return response()->json($categories, 200);
    }

    /**
     * Create a new category.
     * @param Request $request - request object
     * @return \Illuminate\Http\JsonResponse
     * @throws ConflictHttpException
     */
    public function create(Request $request)
    {
        $this->validate($request, Category::$rules);

        $category_name = strtolower($request->input("name"));
        $categories = Category::all();

        foreach ($categories as $item) {
            if ($item->name === $category_name) {
                throw new ConflictHttpException("category name is already taked");
            }
        }

        $category = new Category();
        $category->name = $category_name;
        $category->type = $request->input("type");
        $category->description = $request->input("description");
        $category->save();

        return response()->json($category, 201);
    }


    /**
     * Delete category by id.
     *
     * @param $id - category id
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category){
            throw new NotFoundException("category not found");
        }
        $category->delete();

        return response()->json("category deleted successfully", 200);
    }

}
