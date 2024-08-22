<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {

        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {

    }


    public function store(StoreCategoryRequest $request)
    {
        \Log::info('Category Data:', $request->all());

        $category = Category::create([
            'name' => $request->input('categoryName'),
            'description' => $request->input('categoryDescription'),
        ]);

        return redirect()->back()->with('success', 'Category created successfully!');
    }



    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.index', compact('category'));
    }


    public function edit(Category $category)
    {
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return view('categories.index', compact('category'));
    }

}
