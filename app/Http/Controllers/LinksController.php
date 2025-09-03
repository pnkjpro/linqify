<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Category;
use App\Models\LinkType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LinksController extends Controller
{
    /**
     * Display the links index page.
     */
    public function index(Request $request): Response
    {
        $user = auth()->user();
        
        // Get user's categories and link types for filters
        $categories = Category::forUser($user->id)->ordered()->get();
        $linkTypes = LinkType::active()->ordered()->get();

        // Get query parameters for initial filtering
        $filters = [
            'category_id' => $request->get('category_id'),
            'link_type_id' => $request->get('link_type_id'),
            'is_favorite' => $request->boolean('is_favorite'),
            'search' => $request->get('search'),
        ];

        return Inertia::render('Links/Index', [
            'categories' => $categories,
            'link_types' => $linkTypes,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the create link form.
     */
    public function create(): Response
    {
        $user = auth()->user();
        
        $categories = Category::forUser($user->id)->ordered()->get();
        $linkTypes = LinkType::active()->ordered()->get();

        return Inertia::render('Links/Create', [
            'categories' => $categories,
            'link_types' => $linkTypes,
        ]);
    }

    /**
     * Show the edit link form.
     */
    public function edit(Link $link): Response
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $user = auth()->user();
        $link->load(['category', 'linkType']);
        
        $categories = Category::forUser($user->id)->ordered()->get();
        $linkTypes = LinkType::active()->ordered()->get();

        return Inertia::render('Links/Edit', [
            'link' => $link,
            'categories' => $categories,
            'link_types' => $linkTypes,
        ]);
    }

    /**
     * Show the categories management page.
     */
    public function categories(): Response
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
     * Show the settings page.
     */
    public function settings(): Response
    {
        $user = auth()->user();
        $settings = $user->settings;
        
        if (!$settings) {
            $settings = \App\Models\UserSettings::createDefaultForUser($user);
        }

        return Inertia::render('Settings', [
            'settings' => $settings,
            'user' => $user,
        ]);
    }

    /**
     * Store a new link.
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2000',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'link_type_id' => 'nullable|exists:link_types,id',
            'is_favorite' => 'boolean',
            'tags' => 'nullable|string|max:500',
        ]);

        // If category_id is provided, ensure user owns it
        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if (!$category || $category->user_id !== auth()->id()) {
                return back()->withErrors(['category_id' => 'Invalid category selected.']);
            }
        }

        $link = Link::create([
            'user_id' => auth()->id(),
            'url' => $request->url,
            'title' => $request->title ?: $this->extractTitleFromUrl($request->url),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'link_type_id' => $request->link_type_id,
            'is_favorite' => $request->boolean('is_favorite', false),
            'tags' => $request->tags,
        ]);

        return redirect()->route('links.index')->with('success', 'Link created successfully!');
    }

    /**
     * Update an existing link.
     */
    public function update(Request $request, Link $link)
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $request->validate([
            'url' => 'required|url|max:2000',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'link_type_id' => 'nullable|exists:link_types,id',
            'is_favorite' => 'boolean',
            'tags' => 'nullable|string|max:500',
        ]);

        // If category_id is provided, ensure user owns it
        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if (!$category || $category->user_id !== auth()->id()) {
                return back()->withErrors(['category_id' => 'Invalid category selected.']);
            }
        }

        $link->update([
            'url' => $request->url,
            'title' => $request->title ?: $this->extractTitleFromUrl($request->url),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'link_type_id' => $request->link_type_id,
            'is_favorite' => $request->boolean('is_favorite', false),
            'tags' => $request->tags,
        ]);

        return redirect()->route('links.index')->with('success', 'Link updated successfully!');
    }

    /**
     * Delete a link.
     */
    public function destroy(Link $link)
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $link->delete();

        return redirect()->route('links.index')->with('success', 'Link deleted successfully!');
    }

    /**
     * Extract title from URL (basic implementation).
     */
    private function extractTitleFromUrl(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST);
        return $domain ? ucfirst(str_replace('www.', '', $domain)) : 'Link';
    }
}
