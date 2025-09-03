<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::forUser(auth()->id())
            ->ordered()
            ->withCount('links')
            ->get();

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate category name for this user
        $exists = Category::forUser(auth()->id())
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['A category with this name already exists.']
            ]);
        }

        $validated['user_id'] = auth()->id();
        
        // Set sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Category::forUser(auth()->id())->max('sort_order') + 1;
        }

        $category = Category::create($validated);

        return response()->json($category->load('links'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(404);
        }

        $category->loadCount('links');
        
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate category name for this user (excluding current category)
        $exists = Category::forUser(auth()->id())
            ->where('name', $validated['name'])
            ->where('id', '!=', $category->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['A category with this name already exists.']
            ]);
        }

        $category->update($validated);

        return response()->json($category->fresh()->loadCount('links'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(404);
        }

        // Check if category has links
        if ($category->links()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category that contains links. Please move or delete the links first.'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
