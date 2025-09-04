<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Services\UrlShortenerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UrlShortenerController extends Controller
{
    public function __construct(private UrlShortenerService $urlShortener)
    {
    }

    /**
     * Shorten a URL
     */
    public function shorten(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'custom_stub' => 'nullable|string|min:3|max:20|regex:/^[a-zA-Z0-9_-]+$/',
        ]);

        try {
            $userId = auth()->check() ? auth()->id() : null;
            
            $link = $this->urlShortener->createShortUrl(
                $request->url,
                $request->custom_stub,
                $userId
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $link->id,
                    'original_url' => $link->url,
                    'short_url' => $link->short_url,
                    'full_short_url' => $this->urlShortener->getFullShortUrl($link->short_url),
                    'title' => $link->title,
                    'click_count' => $link->click_count,
                    'created_at' => $link->created_at,
                ],
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while shortening the URL.',
            ], 500);
        }
    }

    /**
     * Check if custom stub is available
     */
    public function checkStub(Request $request): JsonResponse
    {
        $request->validate([
            'stub' => 'required|string|min:3|max:20|regex:/^[a-zA-Z0-9_-]+$/',
        ]);

        $isAvailable = $this->urlShortener->isStubAvailable($request->stub);

        return response()->json([
            'available' => $isAvailable,
            'stub' => $request->stub,
        ]);
    }

    /**
     * Get user's shortened URLs
     */
    public function index(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], 401);
        }

        $links = Link::where('user_id', auth()->id())
            ->whereNotNull('short_url')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $links->map(function ($link) {
                return [
                    'id' => $link->id,
                    'title' => $link->title,
                    'original_url' => $link->url,
                    'short_url' => $link->short_url,
                    'full_short_url' => $this->urlShortener->getFullShortUrl($link->short_url),
                    'click_count' => $link->click_count,
                    'created_at' => $link->created_at,
                    'last_accessed_at' => $link->last_accessed_at,
                ];
            }),
            'pagination' => [
                'current_page' => $links->currentPage(),
                'last_page' => $links->lastPage(),
                'per_page' => $links->perPage(),
                'total' => $links->total(),
            ],
        ]);
    }

    /**
     * Delete a shortened URL
     */
    public function destroy(Request $request, string $stub): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], 401);
        }

        $link = Link::where('short_url', $stub)
            ->where('user_id', auth()->id())
            ->first();

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Shortened URL not found',
            ], 404);
        }

        $link->delete();

        return response()->json([
            'success' => true,
            'message' => 'Shortened URL deleted successfully',
        ]);
    }
}
