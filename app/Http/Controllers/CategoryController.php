<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // Displays a list of all categories
    public function index(Request $request)
    {
        $categories = Category::all();  // Retrieve all categories from the database
        return view('categories.index', compact('categories'));  // Pass the categories to the view
    }

    // Shows the form to create a new category
    public function create()
    {
        // Currently, the create view is not being returned, but this is where you'd show the form to create a category
    }

    // Stores a new category in the database
    public function store(StoreCategoryRequest $request)
    {
        // Log the incoming request data for debugging purposes
        \Log::info('Category Data:', $request->all());

        // Create the new category with the provided data
        $category = Category::create([
            'name' => $request->input('categoryName'),
            'description' => $request->input('categoryDescription'),
        ]);

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', 'Category created successfully!');
    }

    // Displays a specific category by its ID
    public function show($id)
    {
        $category = Category::findOrFail($id);  // Find the category by ID or throw a 404 error if not found
        return view('categories.index', compact('category'));  // Pass the category to the view
    }

    // Shows the form to edit an existing category
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));  // Pass the category to the edit view
    }

    // Updates the specified category in the database
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Currently, no logic is implemented, but here you would handle the update of the category's data
    }

    // Deletes the specified category from the database
    public function destroy($categoryId)
    {
        $category = Category::find($categoryId);
        $category->delete();
        // Delete the category

        // Redirect back to the index page, but currently the deleted category is incorrectly being passed to the view
        return view('categories.index', compact('category'));
    }
}
