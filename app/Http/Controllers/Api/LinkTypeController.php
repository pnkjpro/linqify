<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinkType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LinkTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $linkTypes = LinkType::active()
            ->ordered()
            ->withCount('links')
            ->get();

        return response()->json($linkTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:link_types,name',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Set sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = LinkType::max('sort_order') + 1;
        }

        $linkType = LinkType::create($validated);

        return response()->json($linkType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LinkType $linkType): JsonResponse
    {
        $linkType->loadCount('links');
        
        return response()->json($linkType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LinkType $linkType): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:link_types,name,' . $linkType->id,
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $linkType->update($validated);

        return response()->json($linkType->fresh()->loadCount('links'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LinkType $linkType): JsonResponse
    {
        // Check if link type has links
        if ($linkType->links()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete link type that is being used by links. Please change the link type for existing links first.'
            ], 422);
        }

        $linkType->delete();

        return response()->json(['message' => 'Link type deleted successfully']);
    }
}
