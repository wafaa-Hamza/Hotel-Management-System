<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Spatie\Activitylog\Facades\CauserResolver;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('view-category');

        $categories = Category::all();

        

        return response()->json($categories);
    }
    public function categoryPagination($id)
    {
        $this->authorize('view-category');

        $categories = Category::paginate(request()->segment(count(request()->segments())));



        return response()->json($categories);
    }


    public function store(CategoryRequest $request)
    {
        $this->authorize('create-category');

       $category =  Category::create($request->validated());
     
        return response()->json($category);
    }

    public function show(Category $category) 
    {
        $this->authorize('view-category');

        return response()->json($category);
    }


    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('edit-category');

        $category->update($request->validated());
       
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete-category');

        $category->delete();
       
        return response()->json(['message' => 'Deleted Successfuly',
            'status' => true,
        ], 200);
    }
}