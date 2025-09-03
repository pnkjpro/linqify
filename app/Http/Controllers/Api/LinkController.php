<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Category;
use App\Models\LinkType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Link::forUser(auth()->id())
            ->with(['category', 'linkType']);

        // Apply filters
        if ($request->filled('category_id')) {
            $query->inCategory($request->category_id);
        }

        if ($request->filled('link_type_id')) {
            $query->ofType($request->link_type_id);
        }

        if ($request->filled('is_favorite')) {
            $query->favorites();
        }

        if ($request->filled('is_public')) {
            $query->public();
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('tags')) {
            $tags = is_array($request->tags) ? $request->tags : [$request->tags];
            $query->withTags($tags);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'popular':
                $query->popular();
                break;
            case 'recent':
                $query->recent();
                break;
            case 'recently_accessed':
                $query->recentlyAccessed();
                break;
            case 'title':
                $query->orderBy('title', $sortOrder);
                break;
            case 'url':
                $query->orderBy('url', $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 20), 100);
        $links = $query->paginate($perPage);

        return response()->json($links);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string|max:1000',
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where('user_id', auth()->id())
            ],
            'link_type_id' => 'required|exists:link_types,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_favorite' => 'boolean',
            'is_public' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();

        // Auto-scrape metadata if not provided
        if (empty($validated['title']) || empty($validated['description'])) {
            $metadata = $this->scrapeMetadata($validated['url']);
            $validated = array_merge($validated, $metadata);
        }

        $link = Link::create($validated);
        $link->load(['category', 'linkType']);

        return response()->json($link, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Link $link): JsonResponse
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $link->load(['category', 'linkType']);
        
        return response()->json($link);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link): JsonResponse
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string|max:1000',
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where('user_id', auth()->id())
            ],
            'link_type_id' => 'required|exists:link_types,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_favorite' => 'boolean',
            'is_public' => 'boolean',
        ]);

        $link->update($validated);
        $link->load(['category', 'linkType']);

        return response()->json($link);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link): JsonResponse
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $link->delete();

        return response()->json(['message' => 'Link deleted successfully']);
    }

    /**
     * Toggle favorite status of a link.
     */
    public function toggleFavorite(Link $link): JsonResponse
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $link->update(['is_favorite' => !$link->is_favorite]);
        $link->load(['category', 'linkType']);

        return response()->json([
            'link' => $link,
            'message' => $link->is_favorite ? 'Added to favorites' : 'Removed from favorites'
        ]);
    }

    /**
     * Increment click count for a link.
     */
    public function incrementClick(Link $link): JsonResponse
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $link->incrementClickCount();

        return response()->json([
            'click_count' => $link->click_count,
            'last_accessed_at' => $link->last_accessed_at
        ]);
    }

    /**
     * Search links.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $links = Link::forUser(auth()->id())
            ->with(['category', 'linkType'])
            ->search($request->q)
            ->orderBy('click_count', 'desc')
            ->limit(50)
            ->get();

        return response()->json($links);
    }

    /**
     * Scrape metadata from URL.
     */
    public function scrapeMeta(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $metadata = $this->scrapeMetadata($request->url);

        return response()->json($metadata);
    }

    /**
     * Scrape metadata from a URL.
     */
    private function scrapeMetadata(string $url): array
    {
        try {
            $response = Http::timeout(10)->get($url);
            
            if (!$response->successful()) {
                return [];
            }

            $html = $response->body();
            $metadata = [];

            // Extract title
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
                $metadata['meta_title'] = trim(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
            }

            // Extract meta description
            if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
                $metadata['meta_description'] = trim(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
            }

            // Extract Open Graph image
            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
                $metadata['meta_image'] = $matches[1];
            }

            // Use meta title as title if title is not set
            if (empty($metadata['title']) && !empty($metadata['meta_title'])) {
                $metadata['title'] = $metadata['meta_title'];
            }

            // Use meta description as description if description is not set
            if (empty($metadata['description']) && !empty($metadata['meta_description'])) {
                $metadata['description'] = $metadata['meta_description'];
            }

            return $metadata;
        } catch (\Exception $e) {
            return [];
        }
    }
}
