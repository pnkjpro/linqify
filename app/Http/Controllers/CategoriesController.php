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
}
