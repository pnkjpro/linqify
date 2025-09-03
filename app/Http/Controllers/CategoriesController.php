<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoriesController extends Controller
{
    /**
     * Display the categories index page.
     */
    public function index(): Response
    {
        $user = auth()->user();
        
        $categories = Category::forUser($user->id)
            ->withCount('links')
            ->ordered()
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the create category form.
     */
    public function create(): Response
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Show the edit category form.
     */
    public function edit(Category $category): Response
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|in:blue,green,red,yellow,purple,pink,indigo,gray',
            'icon' => 'required|string|max:10',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'sort_order' => Category::where('user_id', auth()->id())->max('sort_order') + 1,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category)
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|in:blue,green,red,yellow,purple,pink,indigo,gray',
            'icon' => 'required|string|max:10',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category)
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
